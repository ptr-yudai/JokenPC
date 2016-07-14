JokenPC BETA
====

情報処理研究会用プログラミングコンテストのサーバー

## Description

プログラミングコンテストの参加者、問題、スコア、判定などを管理するためのPHP + Pythonサーバーです。

## VS. 

## Requirement

PHP5.xくらい

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
    score INT NOT NULL,
    category CHAR(64) NOT NULL,
    solved INT NOT NULL,
    solved_user TEXT NOT NULL,
    last_date DATETIME,
    PRIMARY KEY (id)
);
```

設定を/jpc/config.phpに記述し、PHPを動作できる環境に設置したら完了です。

## Note

* 入力スクリプトおよび出力スクリプトはPHPのルートディレクトリに設置しないこと。  
* スティッキービットの立っているディレクトリには注意すること。  

## Screenshots


## Licence

MIT Lisence

## Author

[ptr-yudai](https://github.com/ptr-yudai)
