<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/config.php');
require(dirname(__FILE__).'/auth.php');

class JPC
{
    /*
     * ログ出力
     */
    function log($level, $message)
    {
	$this->log_level[] = $level;
	$this->log_message[] = $message;
    }
    
    /*
     * 初期化
     */
    function load()
    {
	// 設定読み込み
	$this->config = new JPC_Config();
	
	// データベースに接続しておく
	try {
	    $this->pdo = new PDO('mysql:host='.$this->config->db_host.
				 ';dbname='.$this->config->db_name.
				 ';charset=utf8',
				 $this->config->db_username,
				 $this->config->db_password);
	} catch (PDOException $error) {
	    $this->pdo = null;
	}
	
	// 認証モジュール
	$this->auth = new JPC_Auth($this);
    }
    
    function __construct()
    {
	// 各種モジュールを読み込み
	$this->load();
	// ページの初期設定
	$this->title = "";
	$this->navbar_active = 0;
	// ログの初期設定
	$this->log_level = array();
	$this->log_message = array();
    }
}
?>
