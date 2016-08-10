# coding: utf-8
#import os
#import random
import json
from geventwebsocket.handler import WebSocketHandler
from gevent import pywsgi, sleep

HOST = "127.0.0.1"                   # サーバーのアドレス
PORT = 8080                          # ポート番号
LANG = "/var/www/html/jpc/lang.json" # 言語一覧のファイルパス

def handler(env, response):
    global langlist
    ws = env['wsgi.websocket']
    # 要求を取得
    packet = ws.receive()
    print("[INFO] New try : ", packet)
    if packet is None:
        print("[ERROR] No packet received.")
        return
    # 最低限の情報が記載されているか
    is_valid = True
    packet = json.loads(packet)
    if 'lang' not in packet : is_valid = False
    if 'code' not in packet : is_valid = False
    if 'id' not in packet : is_valid = False
    # 言語が使用可能か
    if packet['lang'] not in langlist : is_valid = False 
    # データに誤りがある
    if not is_valid:
        try:
            ws.send('{"attempt": -1}')
            print("[INFO] Sent ERROR.")
        except Exception:
            print("[ERROR] Connection refused.")
        return
    
    # ACKを送信
    try:
        ws.send('{"attempt": 1}')
        print("[INFO] Sent ACK.")
    except Exception:
        print("[ERROR] Connection refused.")
    return

def procon(env, response):
    path = env['PATH_INFO']
    print("[INFO] Request : {0}".format(path))
    if path == "/":
        return handler(env, response)
    print("[ERROR] Request for unexpected path.")

# 開始位置
server = pywsgi.WSGIServer(
    (HOST, PORT),
    procon,
    handler_class = WebSocketHandler
)
langlist = json.load(open(LANG, 'r'))
server.serve_forever()

