<?php
require(dirname(__FILE__).'/config.php');

class JPC
{
    function load()
    {
	$config = new JPC_Config();
    }
    
    function __construct()
    {
	// 各種モジュールを読み込み
	$this->load();
	// ページの初期設定
	$this->title = "";
	$this->navbar_active = 0;
    }
}
?>
