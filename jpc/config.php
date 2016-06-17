<?php
class JPC_Config
{
    /*
     * この関数では重要な情報を初期化します。
     * 管理者はあらかじめこれらの項目を必ず設定してください。
     */
    function configure()
    {
	/*
	 * プロコンの設定
	 */
	// 開催場所(時刻)
	$this->place = 'Asia/Tokyo';                     // タイムゾーン
	date_default_timezone_set($this->place);      // ここは変更しない
	$this->start_time = date('2016/06/30 12:00:00'); // 開始時刻
	$this->end_time = date('2016/06/30 13:00:00');   // 終了時刻

	/*
	 * 公開設定
	 */
	$this->mode = 'debug';                 // debug, release
	
	/*
	 * データベースの設定
	 */
	$this->db_host = 'localhost';          // データベースのあるホスト名
	$this->db_name = 'joken_procon';       // データベース名
	$this->db_username = 'pc_master';      // アクセスするユーザー名
	$this->db_password = 'debug_password'; // ユーザーのパスワード
    }

    function __construct()
    {
	$this->configure();
    }
}
?>
