# coding: utf-8

#
# このファイルはHTTP経由でアクセスできない別の場所に移動してください
#

import commands
from geventwebsocket.handler import WebSocketHandler
from gevent import pywsgi, sleep
import json
import MySQLdb
import os
import pwd
import random
import string

# 基本設定
HOST = "127.0.0.1"                   # サーバーのアドレス
PORT = 8080                          # ポート番号
LANG = "/var/www/html/jpc/lang.json" # 言語一覧のファイルパス

# データベース設定("/jpc/config.php"と同じ)
DB_HOST = 'localhost'
DB_NAME = 'joken_procon'
DB_USERNAME = 'pc_master'
DB_PASSWORD = 'debug_password'

# post : 参加者から送られてきたデータ
# record : SQLから取得した問題情報
def execute(ws, post, record):
    global langlist
    
    # ソースコードを保存
    code = post['code']
    lang = post['lang']
    script = langlist['compile'][lang]
    extension = langlist['extension'][lang]
    
    # 必要なデータを作成
    username = randstr(16)
    password = randstr(16)
    filepath_in = randstr(8) + extension
    filepath_out = randstr(8)
    
    # /tmpに移動
    os.chdir('/tmp/')

    # ユーザーを作成する
    #os.system("useradd -p `printf {1} | mkpasswd -s -m sha-512` {0}".format(username, password))
    #pwnam = pwd.getpwnam(username)

    # コードを生成
    fp = open(filepath_in, 'w')
    fp.write(code)
    fp.close()
    # コンパイル
    compile_result = commands.getoutput(script.format(input=filepath_in, output=filepath_out))
    # コードを削除
    try:
        os.remove(filepath_in)
    except Exception:
        pass

    # コンパイル結果を送信
    try:
        ws.send(json.dumps({'compile': compile_result}))
    except Exception:
        pass

    # 実行ファイルの権限を変更
    try:
        os.chmod(filepath_out, 0500)
    except Exception:
        return

    # チェックする
    #for n in range(int(record['exec_time'])):
        

    # 実行ファイルを削除
    try:
        os.remove(filepath_out)
    except Exception:
        pass
    
    return


# ランダムな文字列を生成
def randstr(length):
    return ''.join([random.choice(string.ascii_letters + string.digits) for i in range(length)])

def handler(env, response):
    global langlist
    global DB
    
    ws = env['wsgi.websocket']
    print(".[INFO] 新しい要求を受信しました。")

    # 要求を取得
    packet = ws.receive()
    try:
        packet = json.loads(packet)
    except Exception:
        print(".[ERROR] JSONの展開に失敗しました。")
        return

    # データの整合性を確認
    if not check_payload(packet):
        print(".[ERROR] 不正なデータであると判別されました。")
        try:
            ws.send('{"error": u"無効なデータが送信されました。"}')
            print(".[INFO] 不正なデータであることを通知しました。")
        except Exception:
            print(".[ERROR] 既に接続が切断されています。")
        return

    # 問題を取得
    cursor = DB.cursor(MySQLdb.cursors.DictCursor)
    cursor.execute("SELECT * FROM problem WHERE id={id};".format(id=packet['id']))
    record = cursor.fetchall()
    cursor.close()
    
    # 実行
    execute(ws, packet, record)

    
    return

#
# payloadが有効かを調べる
#
def check_payload(packet):
    global langlist
    # 最低限の情報が記載されているか
    if 'lang' not in packet : return False
    if 'code' not in packet : return False
    if 'id'   not in packet : return False
    # 言語が使用可能か
    if 'compile' not in langlist   : return False
    if 'extension' not in langlist : return False
    if packet['lang'] not in langlist['compile']   : return False
    if packet['lang'] not in langlist['extension'] : return False
    # データに誤りがある
    return True

#
# リクエストを受ける
#
def procon(env, response):
    path = env['PATH_INFO']
    if path == "/":
        return handler(env, response)
    return None

#
# 開始位置
#
server = pywsgi.WSGIServer(
    (HOST, PORT),
    procon,
    handler_class = WebSocketHandler
)
# 言語とコンパイラ
langlist = json.load(open(LANG, 'r'))
# SQL
DB = MySQLdb.connect(host    = DB_HOST,
                     db      = DB_NAME,
                     user    = DB_USERNAME,
                     passwd  = DB_PASSWORD,
                     charset = 'utf8',
                 )
# サーバー稼働
server.serve_forever()
conn.close()
