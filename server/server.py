# coding: utf-8

#
# このファイルはHTTP経由でアクセスできない別の場所に移動してください
# また、root権限で実行される必要があります
#

import JPC

if __name__ == '__main__':
    server = JPC.JPC("/var/www/html/server/config.json")
    server.run()
