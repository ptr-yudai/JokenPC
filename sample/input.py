#!/bin/env python
# coding: utf-8

#
# 入力生成コード
# 問題1: N回Hello, World!を出力する
#

import sys

# 何回目の入力か
n = int(sys.argv[1])

if n == 0:   # 1つ目は小さい入力
    print("3")
elif n == 1: # 2つ目は大きい入力
    print("126")
elif n == 2: # 3つ目はゼロ
    print("0")
else:        # ありえない入力だけど一応
    print("55")
