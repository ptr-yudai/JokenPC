<?php
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ログイン済み */
if ($jpc->auth->is_logged_in() === false) {
    header("Location: /login/");
}
/* ユーザー情報を取得 */
$jpc->auth->get_userinfo();
/* ページ設定 */
$jpc->title = "個人設定 - JPC";
$jpc->navbar_active = -1;
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
	    <!-- Panel : 問題一覧 -->
	    <div class="panel panel-default">
		<div class="panel-heading">ステータス</div>
		<div class="panel-body">
		    <!-- カテゴリ一覧 -->
		    <div class="panel-group" id="category">
			<div class="panel-body">
			    <table class="table table-bordered">
				<thead>
				    <tr>
					<th class="col-md-2">ユーザー名</th>
					<td class="col-md-8"><?php print($jpc->h($jpc->auth->userinfo['user'])); ?></td>
				    </tr>
				</thead>
				<tbody>
				    <tr>
					<th>スコア</th>
					<td><?php print($jpc->h($jpc->auth->userinfo['score'])."[pt]"); ?></td>
				    </tr>
				</tbody>
			    </table>
			</div>
			<?php print_r($jpc->auth->userinfo); ?>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>
