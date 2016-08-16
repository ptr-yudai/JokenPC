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
	// チーム戦
	$this->has_team = false;                         // true:チーム戦
	// 開催場所(時刻)
	$this->place = 'Asia/Tokyo';                     // タイムゾーン
	date_default_timezone_set($this->place);      // ここは変更しない
	$this->start_time = new DateTime('2016/08/15 19:00:00'); // 開始時刻
	$this->end_time = new DateTime('2016/08/15 19:00:00');   // 終了時刻

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
	$this->salt = 'debug_salt';            // パスワードハッシュのソルト

	/*
	 * プログラムの設定
	 */
	$json = file_get_contents(dirname(__FILE__)."/lang.json");
	$json = mb_convert_encoding($json, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$this->language = json_decode($json, true)['compile'];    // 言語とコンパイル
	$this->enckey = md5("k3y1", true);    // 通信暗号化キー(server.pyと共通)
    }

    function __construct()
    {
	$this->configure();
    }
}
?>
