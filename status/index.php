<?php
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ログイン済み */
if ($jpc->auth->is_logged_in() === false) {
    header("Location: /login/");
}
/* ユーザー情報を取得 */
$jpc->auth->get_all_userinfo();
/* ページ設定 */
$jpc->title = "ステータス - JPC";
$jpc->navbar_active = 3;
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
		<div class="panel-heading">順位</div>
		<div class="panel-body">
		    <div class="panel-group" id="category">
			<div class="panel-body">
			    <table class="table table-striped table-bordered">
				<thead>
				    <tr>
					<th class="col-md-1">順位</th>
					<th>名前</th>
					<th>スコア</th>
				    </tr>
				</thead>
				<tbody>
				    <?php foreach($jpc->auth->users as $index=>$user) { ?>
					<tr>
					    <td>
						<span<?php print($jpc->auth->put_king($index)); ?>></span>
						<?php print($jpc->auth->get_rank($user['score'])); ?>
					    </td>
					    <td><?php print($user['user']); ?></td>
					    <td><?php print(''.$user['score'].'[pt]'); ?></td>
					</tr>
				    <?php } ?>
				</tbody>
			    </table>
			</div>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>
