<?php 
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ログイン */

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
	    <!-- Panel : ログイン -->
	    <div class="panel panel-default">
		<div class="panel-heading">ログイン</div>
		<div class="panel-body">
		    <p>以下のフォームからログインしてください。</p>
		    <form class="form-horizontal" method="POST">
			<input type="hidden" value="login">
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="username">ユーザー名</label>
			    <div class="col-sm-4">
				<input type="text" class="form-control" id="username" name="username" placeholder="ユーザー名" required>
			    </div>
			    <span class="help-block">ユーザー名は64文字以内で設定してください。</span>
			</div>
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="password">パスワード</label>
			    <div class="col-sm-4">
				<input type="password" class="form-control" id="password" name="password" placeholder="パスワード" required>
			    </div>
			    <span class="help-block">パスワードは128文字以内で設定してください。</span>
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
			<input type="hidden" value="signup">
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="username">ユーザー名</label>
			    <div class="col-sm-4">
				<input type="text" class="form-control" id="username" name="username" placeholder="ユーザー名" required>
			    </div>
			    <span class="help-block">ユーザー名は64文字以内で設定してください。</span>
			</div>
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="password">パスワード</label>
			    <div class="col-sm-4">
				<input type="password" class="form-control" id="password" name="password" placeholder="パスワード" required>
			    </div>
			    <span class="help-block">パスワードは128文字以内で設定してください。</span>
			</div>
			<div class="form-group">
			    <label class="col-sm-1 control-label" for="password">パスワード(確認)</label>
			    <div class="col-sm-4">
				<input type="password" class="form-control" id="password" name="password" placeholder="パスワード(確認)" required>
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
