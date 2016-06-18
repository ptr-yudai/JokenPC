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
     * サインアップ
     */
    function signup()
    {
	// 正常な登録ポストをフィルタリング
	if (empty($_POST['type'])
	    || empty($_POST['username'])
		|| empty($_POST['password'])
		    || empty($_POST['password_confirm'])) return;
	if ($_POST['type'] !== "signup") return;
	
	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。", false);
	    return;
	}
	
	// ユーザー名とパスワードの長さを確認
	if (strlen($_POST['username']) >= 64) {
	    $this->jpc->log('cation', "ユーザー名は64文字未満に設定してください。", false);
	    return;
	}
	if (strlen($_POST['password']) >= 128) {
	    $this->jpc->log('cation', "パスワードは128文字未満に設定してください。", false);
	    return;
	}

	// ユーザー名が使用できるかを確認
	if ($this->user_exist($_POST['username'])) {
	    $this->jpc->log('cation', "このユーザー名は既に存在します。", false);
	    return;
	}

	// パスワードが正しいかを確認
	if ($_POST['password'] !== $_POST['password_confirm']) {
	    $this->jpc->log('cation', "パスワードが一致していません。", false);
	    return;
	}
	
	// アカウントに登録する
	$statement = $this->pdo->prepare('INSERT INTO account(user, pass, score, solved) VALUES(:user, :pass, 0, "");');
	$statement->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
	$statement->bindParam(':pass', md5($_POST['password']), PDO::PARAM_STR);
	$statement->execute();
	
	// 成功
	$this->fatal_error('signup', "登録が完了しました。ログインしてください。", true);
    }

    /*
     * ログインしているか
     */
    function is_logged_in()
    {
	if (empty($_SESSION['login'])
	    || empty($_SESSION['username'])) return false;
	if ($_SESSION['login'] !== true) return false;
	return true;
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
