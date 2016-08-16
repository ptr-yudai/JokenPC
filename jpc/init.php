<?php
ini_set('display_errors', 1);
session_start();
require(dirname(__FILE__).'/config.php');
require(dirname(__FILE__).'/auth.php');
require(dirname(__FILE__).'/prob.php');

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
     * ログ取り出し
     */
    function poplog($level)
    {
	$ret = "";
	foreach($this->log_level as $key => $value) {
	    if ($value === $level) {
		$ret = $this->log_message[$key];
		unset($this->log_level[$key]);
		unset($this->log_message[$key]);
		break;
	    }
	}
	return $ret;
    }
    
    /*
       エスケープ
     */
    function h($str)
    {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /*
     * 時間確認
     */
    function in_session()
    {
	$now = new DateTime('now');
	if ($now < $this->config->start_time) {
	    return -1;
	}
	if ($now > $this->config->end_time) {
	    return 1;
	}
	return 0;
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
	// 問題モジュール
	$this->prob = new JPC_Prob($this);
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
