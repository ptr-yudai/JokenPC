<?php 
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ログイン */
$jpc->auth->login();
$jpc->auth->signup();
/* ログイン済み */
if ($jpc->auth->is_logged_in()) {
    header("Location: /problem/");
}
/* ページ設定 */
$jpc->title = "ログイン - JPC";
$jpc->navbar_active = 0;
?>
<!DOCTYPE html>
<html>
    <!-- Head -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
    </head>
    <!-- Body -->
    <body>
	<?php require(dirname(__FILE__).'/../global/navbar.php'); ?>
	<div class="container-fluid">
	    <!-- Error : 注意 -->
	    <?php if (($error = $jpc->poplog('cation')) !== "") { ?>
		<div class="alert alert-warning fade in">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		    <strong>エラー： </strong>
		    <p><?php print($jpc->h($error)); ?></p>
		</div>
	    <?php } ?>
	    <!-- Error : 警告 -->
	    <?php if (($error = $jpc->poplog('warning')) !== "") { ?>
		<div class="alert alert-danger fade in">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		    <strong>エラー： </strong>
		    <p><?php print($jpc->h($error)); ?></p>
		</div>
	    <?php } ?>
	    <!-- Error : 成功 -->
	    <?php if (($error = $jpc->poplog('success')) !== "") { ?>
		<div class="alert alert-info fade in">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		    <strong>情報： </strong>
		    <p><?php print($jpc->h($error)); ?></p>
		</div>
	    <?php } ?>
	    <!-- Panel : ログイン -->
	    <div class="panel panel-default">
		<div class="panel-heading">ログイン</div>
		<div class="panel-body">
		    <p>以下のフォームからログインしてください。</p>
		    <form class="form-horizontal" method="POST">
			<input type="hidden" name="type" value="login">
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="username">ユーザー名</label>
			    <div class="col-sm-4">
				<input type="text" class="form-control" id="username" name="username" placeholder="ユーザー名" required>
			    </div>
			    <span class="help-block">64文字未満</span>
			</div>
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="password">パスワード</label>
			    <div class="col-sm-4">
				<input type="password" class="form-control" id="password" name="password" placeholder="パスワード" required>
			    </div>
			    <span class="help-block">128文字未満</span>
			</div>
			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">ログイン</button>
			    </div>
			</div>
		    </form>
		</div>
	    </div>

	    <!-- Panel : サインアップ -->
	    <div class="panel panel-default">
		<div class="panel-heading">新規登録</div>
		<div class="panel-body">
		    <p>アカウントを持っていない場合、以下のフォームから新規登録してください。</p>
		    <form class="form-horizontal" method="POST">
			<input type="hidden" name="type" value="signup">
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="username">ユーザー名</label>
			    <div class="col-sm-4">
				<input type="text" class="form-control" id="username" name="username" placeholder="ユーザー名" required>
			    </div>
			    <span class="help-block">64文字未満</span>
			</div>
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="password">パスワード</label>
			    <div class="col-sm-4">
				<input type="password" class="form-control" id="password" name="password" placeholder="パスワード" required>
			    </div>
			    <span class="help-block">128文字未満</span>
			</div>
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="password_confirm">パスワード(確認)</label>
			    <div class="col-sm-4">
				<input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="パスワード(確認)" required>
			    </div>
			    <span class="help-block"></span>
			</div>
			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">ログイン</button>
			    </div>
			</div>
		    </form>
		</div>
	    </div>
	</div>
    </body>
</html>
