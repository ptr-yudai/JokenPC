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
	$statement->bindParam(':user',
			      $_POST['username'],
			      PDO::PARAM_STR);
	$statement->bindParam(':pass',
			      md5($this->jpc->config->salt . $_POST['password']),
			      PDO::PARAM_STR);
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
	    $this->jpc->log('cation', "確認用パスワードが一致していません。", false);
	    return;
	}
	
	// アカウントに登録する
	$statement = $this->jpc->pdo->prepare('INSERT INTO account(user, team, pass, mime, score, solved) VALUES(:user, :team, :pass, 0, 0, "");');
	$statement->bindParam(':user',
			      $_POST['username'],
			      PDO::PARAM_STR);
	$statement->bindValue(':team',
			      $_POST['username'],
			      PDO::PARAM_STR);
	$statement->bindParam(':pass',
			      md5($this->jpc->config->salt . $_POST['password']),
			      PDO::PARAM_STR);
	$statement->execute();
	
	// 成功
	$this->jpc->log('success', "登録が完了しました。ログインしてください。", true);
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
     * 指定したユーザーが存在するかを確認する
     */
    function user_exist($username)
    {
	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。", false);
	    return;
	}

	// ユーザー名を確認
	$statement = $this->jpc->pdo->prepare('SELECT id FROM account WHERE user=:user;');
	$statement->bindParam(':user', $username, PDO::PARAM_STR);
	$statement->execute();
	if ($statement->rowCount() > 0) return true;
	return false;
    }

    /*
     * 問題が解答済みかを調べる
     */
    function is_solved($id)
    {
	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。", false);
	    return true;
	}
	
	$this->get_userinfo();
	$probs = explode(',', $this->userinfo['solved']);
	foreach($probs as $index=>$prob) {
	    if ((string)$prob === (string)$id) {
		return true;
	    }
	}
	return false;
    }

    /*
     * ユーザー情報を取得する
     */
    function get_userinfo()
    {
	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。", false);
	    return;
	}

	// ユーザー情報を確認
	$statement = $this->jpc->pdo->prepare('SELECT * FROM account WHERE user=:user;');
	$statement->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->execute();
	$this->userinfo = $statement->fetch(PDO::FETCH_ASSOC);
    }

    /*
     * 全てのユーザー情報を取得する
     */
    function get_all_userinfo()
    {
	// データベースに接続できていない
	if ($this->jpc->pdo === null) {
	    $this->jpc->log('warning', "データベースに接続できません。", false);
	    return;
	}
	
	// ユーザー情報を確認
	$query = $this->jpc->pdo->query('SELECT * FROM account;');
	$this->users = $query->fetchAll(PDO::FETCH_ASSOC);
	// ソート
	usort($this->users, function($a, $b) {
	    return $b['score'] - $a['score'];
	});
    }

    /*
     * ランクを取得
     */
    function get_rank($score)
    {
	foreach($this->users as $index => $user) {
	    if ((string)$user['score'] === (string)$score) {
		$rank = $index + 1;
		break;
	    }
	}
	return $rank;
    }

    /*
     * ユーザー名を暗号化してbase64
     */
    function encrypt_username()
    {
	if ($this->is_logged_in() === false) return '';
	// 初期化
	srand();
	$size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$iv = mcrypt_create_iv($size, MCRYPT_RAND);
	$this->enc_iv = base64_encode($iv);
	// 暗号化
	$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->jpc->config->enckey, $_SESSION['username'], MCRYPT_MODE_CBC, $iv);
	$this->enc_user = base64_encode($encrypted);
	return;
    }

    /*
     * 順位を表すアイコンのクラスを返す
     */
    function put_king($rank)
    {
	$class = ' class="glyphicon glyphicon-king"';
	switch($rank) {
	    case 0:
		$class .= ' style="color: Gold;"';
		break;
	    case 1:
		$class .= ' style="color: DarkGray;"';
		break;
	    case 2:
		$class .= ' style="color: SaddleBrown;"';
		break;
	    default:
		$class = '';
		break;
	}
	return $class;
    }

    /*
     * コンストラクタ
     */
    function __construct($jpc)
    {
	$this->jpc = $jpc;
	$this->enc_user = "";
	$this->enc_iv = "";
	$this->userinfo = array();
	$this->users = array();
    }
}
?>
