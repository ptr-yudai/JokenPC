JokenPC BETA
====

情報処理研究会用プログラミングコンテストのサーバー

## Description

プログラミングコンテストの参加者、問題、スコア、判定などを管理するためのPHP + Pythonサーバーです。

## VS. 

## Requirement

PHP5.xくらい
MySQL / MariaDBなど
Python 2.7

## Usage

## Install

プログラミングコンテスト用にSQLのデータベースを作成します。データベースの名前は何でも良いですが、データベース名、ホスト名は/jpc/config.phpに設定してください。
ここではlocalhostにjoken_proconを作成します。

```sql
CREATE DATABASE joken_procon;
```

また、データベースにアクセスできる権限を持ったユーザーを作成します。
ここではpc_masterをdebug_passwordというパスワードで作成します。

```sql
CREATE USER 'pc_master'@'localhost' IDENTIFIED BY 'debug_password';  
GRANT ALL PRIVILEGES ON joken_procon.* TO 'pc_master'@'localhost';
```

次に、アカウント(account)と問題(problem)のテーブルを作成します。

```sql
USE joken_procon;

CREATE TABLE account(
    id INT NOT NULL AUTO_INCREMENT,
    user CHAR(64) NOT NULL,
    team CHAR(64) NOT NULL,
    pass CHAR(128) NOT NULL,
    image MEDIUMBLOB,
    mime TINYINT NOT NULL,
    score INT NOT NULL,
    solved TEXT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE problem(
    id INT NOT NULL AUTO_INCREMENT,
    title CHAR(128) NOT NULL,
    problem TEXT NOT NULL,
    note TEXT NOT NULL,
    ie_input TEXT NOT NULL,
    ie_output TEXT NOT NULL,
    limit_time INT NOT NULL,
    limit_memory INT NOT NULL,
    input_code CHAR(64) NOT NULL,
    output_code CHAR(64) NOT NULL,
    exec_time INT NOT NULL,
    score INT NOT NULL,
    category CHAR(64) NOT NULL,
    solved INT NOT NULL,
    solved_user TEXT NOT NULL,
    last_date DATETIME,
    PRIMARY KEY (id)
);
```

設定を/jpc/config.phpに記述します。

次に、チェックサーバーの設定をします。

まず、/jpc/lang.jsonに使用可能にする言語とそのコンパイルスクリプト、拡張子を記述します。

また、標準では/server/に入っているconfig.jsonを編集し、config.phpに合わせた設定をします。

完了したら、server.pyを*必ず*HTTP経由でアクセスできない場所に設置してください。


問題を追加するにはJokenPCのデータベース上で

```sql
INSERT INTO problem(title, problem, note, ie_input, ie_output, limit_time, limit_memory, input_code, output_code, score, category)
VALUES(
	"問題タイトル",
	"問題文(html記法)",
	"備考(html記法)",
	"入力例",
	"出力例",
	制限時間(ミリ秒),
	制限メモリ(キロバイト),
	"入力を生成するコードのパス",
	"入力から出力を生成するコード(解答)のパス",
	入力チェック回数,
	得点,
	"カテゴリ"
);
```

のように実行します。
なお、入出力のコードはHTTP経由で閲覧できない場所に設置する必要があります。
また、入出力のコードは直接実行するので、実行可能ファイルもしくはシェバンなどを用いて起動できる状態にしておいてください。
もちろんchmod等で実行可能な状態にしておく必要があります。(例 : # chmod +x output.py)

## Note

* 入力スクリプトおよび出力スクリプトはPHPのルートディレクトリに設置しないこと。  
* スティッキービットの立っているディレクトリには注意すること。  

## Screenshots


## Licence

MIT Lisence

## Author

[ptr-yudai](https://github.com/ptr-yudai)
