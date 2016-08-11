# coding: utf-8

from geventwebsocket.handler import WebSocketHandler
from gevent import pywsgi, sleep
import json
import MySQLdb

class JPC:
    #
    # 初期化
    #
    def __init__(self, filepath_config):
        import hashlib
        # 設定ファイルをロード
        fp = open(filepath_config, 'r')
        config = json.load(fp)
        fp.close()
        # 設定をクラス変数に格納
        self.host        = config['host']
        self.port        = config['port']
        self.langlist    = json.load(open(config['langfile'], 'r'))
        self.enckey      = hashlib.md5(config['key']).digest()
        self.db_host     = config['db_host']
        self.db_name     = config['db_name']
        self.db_username = config['db_username']
        self.db_password = config['db_password']
        return

    #
    # チェック
    #
    def execute(self):
        import codecs
        import commands
        import os
        import pwd
        # 情報を取得
        code = self.packet['code']
        lang = self.packet['lang']
        script = self.langlist['compile'][lang]
        extension = self.langlist['extension'][lang]
        # 必要なデータを生成
        filepath_in = self.randstr(8) + extension
        filepath_out = self.randstr(8)
        username = self.randstr(16)
        # /tmpに移動
        os.chdir('/tmp/')
        # ユーザーを作成する
        try:
            os.system("useradd {0}".format(username))
            pwnam = pwd.getpwnam(username)
        except Exception:
            return
        # コードを生成
        fp = codecs.open(filepath_in, 'w', 'utf-8')
        fp.write(code)
        fp.close()
        # コンパイル
        compile_result = commands.getoutput(
            script.format(input=filepath_in, output=filepath_out)
        )
        # コードを削除
        try:
            os.remove(filepath_in)
        except Exception:
            pass
        # コンパイル結果を送信
        try:
            self.ws.send(json.dumps({'compile': compile_result}))
        except Exception:
            pass
        # コンパイルできているか
        if not os.path.exists(filepath_out):
            print("[INFO] コンパイルに失敗しました。")
            return
        # 実行ファイルの権限を変更
        try:
            os.chmod(filepath_out, 0500)
            os.chown(filepath_out, pwnam.pw_uid, pwnam.pw_gid)
            # 出力例も一応
            os.chown(self.record['output_code'], pwnam.pw_uid, pwnam.pw_gid)
        except Exception:
            try:
                os.remove(filepath_out)
                os.system("userdel {0}".format(username))
            except Exception:
                print("[ERROR] /tmp/{0}の削除に失敗しました。".format(filepath_out))
                print("[ERROR] ユーザー{0}の削除に失敗しました。".format(username))
            return
        # チェックする
        clear = True
        for n in range(int(self.record['exec_time'])):
            print("[INFO] {0}回目の試行が開始されました。".format(n + 1))
            # 実行開始を宣言
            try:
                self.ws.send(json.dumps({'attempt': n + 1}))
            except Exception:
                pass
            # 入力を生成
            self.input_data = commands.getoutput(
                self.record['input_code'] + " " + str(n)
            )
            # 出力を生成
            self.output_data = self.run_command(username, self.record['output_code'])
            # 実行結果を取得
            result = self.run_command(username, './'+filepath_out)
            #print "Input : ", self.input_data
            #print "Answer : ", self.output_data
            #print "Result : ", result
            # タイムアウト
            if result == False:
                self.ws.send(json.dumps({'failure': n + 1}))
                clear = False
                print("[INFO] タイムアウトしました。")
                continue
            # 結果が違う
            if self.output_data.rstrip('\n') != result.rstrip('\n'):
                self.ws.send(json.dumps({'failure': n + 1}))
                clear = False
                print("[INFO] 結果に誤りがあります。")
                continue
            # 実行結果を宣言
            try:
                self.ws.send(json.dumps({'success': n + 1}))
                print("[INFO] チェックが成功しました。")
            except Exception:
                pass
        # 成功通知
        if clear:
            self.ws.send('{"complete":"success"}')
            self.update_db()
        else:
            self.ws.send('{"complete":"failure"}')

        # 実行ファイルを削除
        try:
            os.remove(filepath_out)
            os.system("userdel {0}".format(username))
        except Exception:
            print("[ERROR] /tmp/{0}の削除に失敗しました。".format(filepath_out))
            print("[ERROR] ユーザー{0}の削除に失敗しました。".format(username))
        return

    #
    # コマンドを制限付きで実行
    #
    def run_command(self, username, filepath):
        import subprocess
        import time
        import sys
        # プロセスを生成
        proc = subprocess.Popen(
            [
                'su',
                username,
                '-c',
                'ulimit',
                '-v',
                str(self.record['limit_memory']),
                filepath
            ],
            stdout = subprocess.PIPE,
            stderr = subprocess.PIPE,
            stdin = subprocess.PIPE,
        )
        # 入力を送る
        proc.stdin.write(self.input_data.rstrip('\n') + '\n')
        proc.stdin.close()
        # 時間制限を設定
        deadline = time.time() + float(self.record['limit_time']) / 1000.0
        while time.time() < deadline and proc.poll() == None:
            time.sleep(0.20)
        # タイムアウト
        if proc.poll() == None:
            if float(sys.version[:3]) >= 2.6:
                proc.terminate()
            return False
        # 正常終了
        stdout = proc.stdout.read()
        return stdout

    #
    # 点数を追加
    #
    def update_db(self):
        import time
        cursor = self.db.cursor(MySQLdb.cursors.DictCursor)
        # スコアを追加
        cursor.execute("UPDATE account SET score=score+{score} WHERE user='{user}';".format(score=int(self.record['score']), user=self.user))
        # 解答済み問題を追加
        cursor.execute("UPDATE account SET solved=concat('{id},', solved) WHERE user='{user}';".format(id=self.record['id'], user=self.user))
        # 解答数をインクリメント
        cursor.execute("UPDATE problem SET solved=solved+1 WHERE id={id};".format(id=self.record['id']))
        # 解答ユーザーを更新
        cursor.execute("UPDATE problem SET solved_user='{user}' WHERE id={id};".format(user=self.user, id=self.record['id']))
        # 解答時間を更新
        cursor.execute("UPDATE problem SET last_date='{date}' WHERE id={id};".format(date=time.strftime('%Y-%m-%d %H:%M:%S'), id=self.record['id']))
        cursor.close()
        self.db.commit()
        return

    #
    # 新規要求を処理
    #
    def handle(self, env, response):
        self.ws = env['wsgi.websocket']
        print("[INFO] 新しい要求を受信しました。")
        # 要求を取得
        self.packet = self.ws.receive()
        if not self.analyse_packet(): return
        # 問題を取得
        self.get_problem()
        # 実行
        self.execute()
        return

    #
    # 問題の詳細を取得
    #
    def get_problem(self):
        cursor = self.db.cursor(MySQLdb.cursors.DictCursor)
        cursor.execute("SELECT * FROM problem WHERE id={id};".format(id=self.packet['id']))
        self.record = cursor.fetchall()[0]
        cursor.close()
        return

    #
    # データを解析
    #
    def analyse_packet(self):
        from Crypto.Cipher import AES
        # パケットをJSONとして展開
        try:
            self.packet = json.loads(self.packet)
        except Exception:
            print("[ERROR] JSONの展開に失敗しました。")
            return False
        # データの整合性を確認
        if not self.check_payload():
            print("[ERROR] 不正なデータであると判別されました。")
            self.ws.send('{"error":"無効なデータが送信されました。"}')
            return False
        # ユーザー名を復号化
        iv = self.packet['iv'].decode('base64')
        enc_user = self.packet['user'].decode('base64')
        aes = AES.new(self.enckey, AES.MODE_CBC, iv)
        self.user = aes.decrypt(enc_user).replace('\x00', '')
        print("[INFO] この試行のユーザーは{0}です。".format(self.user))
        # エスケープ
        self.user = MySQLdb.escape_string(self.user)
        self.packet['id'] = int(self.packet['id'])
        return True
        
    #
    # payloadが有効かを調べる
    #
    def check_payload(self):
        # 最低限の情報が記載されているか
        if 'lang' not in self.packet : return False
        if 'code' not in self.packet : return False
        if 'id'   not in self.packet : return False
        if 'iv'   not in self.packet : return False
        if 'user' not in self.packet : return False
        # 言語が使用可能か
        if 'compile' not in self.langlist   : return False
        if 'extension' not in self.langlist : return False
        if self.packet['lang'] not in self.langlist['compile']   : return False
        if self.packet['lang'] not in self.langlist['extension'] : return False
        # データが正しい
        return True

    #
    # ランダムな文字列を生成
    #
    def randstr(self, length):
        import random
        import string
        return ''.join([
            random.choice(string.ascii_letters + string.digits)
            for i in range(length)
        ])


    #
    # リクエストを受ける
    #
    def procon(self, env, response):
        path = env['PATH_INFO']
        if path == "/":
            return self.handle(env, response)
        return

    #
    # サーバーを稼働させる
    #
    def run(self):
        # サーバー初期化
        server = pywsgi.WSGIServer(
            (self.host, self.port),
            self.procon,
            handler_class = WebSocketHandler
        )
        # SQLへの接続
        self.db = MySQLdb.connect(host    = self.db_host,
                                  db      = self.db_name,
                                  user    = self.db_username,
                                  passwd  = self.db_password,
                                  charset = 'utf8',
                              )
        # サーバー稼働
        server.serve_forever()
        return
