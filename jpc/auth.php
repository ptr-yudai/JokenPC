<?php
class JPC_Auth
{
    /*
     * ログイン
     */
    function login()
    {
	// 正常なログインポストをフィルタリング
	if (empty($_POST['type'])
	    || empty($_POST['username'])
	    || empty($_POST['password'])) return;
	if ($_POST['type'] !== 'login') return;

	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。");
	    return;
	}
	
	// ユーザー名とパスワードを検索する
	$statement = $this->jpc->pdo->prepare('SELECT id FROM account WHERE user=:user AND pass=:pass;');
	$statement->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
	$statement->bindParam(':pass', md5($_POST['password']), PDO::PARAM_STR);
	$statement->execute();
	
	// ユーザーが見つかったか
	if ($statement->rowCount() > 0) {
	    // ログインに成功
	    $_SESSION['login'] = true;
	    $_SESSION['username'] = $_POST['username'];
	} else {
	    // ログインに失敗
	    $this->jpc->log('warning', "ユーザー名かパスワードが間違っています。");
	}
    }

    /*
     * ログアウト
     */
    function logout()
    {
	// 正常なログアウトポストをフィルタリング
	if (empty($_POST['type'])) return;
	if ($_POST['type'] !== "logout") return;
	
	// ログアウト
	$_SESSION['login'] = false;
	session_destroy();
	// クッキーを削除する
	if (isset($_COOKIE["PHPSESSID"])) {
	    setcookie('PHPSESSID', '', time() - 1800, '/');
	}
    }

    /*
     * コンストラクタ
     */
    function __construct($jpc)
    {
	$this->jpc = $jpc;
    }
}
?>
