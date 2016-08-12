<?php 
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ログイン済み */
if ($jpc->auth->is_logged_in() === false) {
    header("Location: /login/");
}
/* 全問題を取得 */
$jpc->prob->get_all_problems();
/* ページ設定 */
$jpc->title = "問題一覧 - JPC";
$jpc->navbar_active = 2;
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
		<div class="panel-heading">問題一覧</div>
		<div class="panel-body">
		    <!-- カテゴリ一覧 -->
		    <p>現在、以下の問題が公開されています。</p>
		    <div class="panel-group" id="category">
			<?php foreach($jpc->prob->problem_list as $category => $problems) { ?>
			    <div class="panel panel-primary">
				<a class="list-group-item active" data-toggle="collapse" data-parent="#category" href="#category-<?php print($jpc->h($category)); ?>">
				    <?php print($jpc->h($category)); ?><span class="badge"><?php print(count($problems)); ?></span>
				</a>
				<div class="panel-collapse collapse" id="category-<?php print($jpc->h($category)); ?>">
				    <div class="panel-body">
					<table class="table table-hover">
					    <thead>
						<tr>
						    <th></th><th>問題</th><th>スコア</th><th>正解者数</th>
						</tr>
					    </thead>
					    <tbody>
						<?php foreach($problems as $index => $record) { ?>
						    <tr>
							<?php if ($jpc->auth->is_solved($record['id'])) { ?>
							    <td><span class="glyphicon glyphicon-ok"></span></td>
							<?php } else { ?>
							    <td><span class="glyphicon glyphicon-remove"></span></td>
							<?php } ?>
							<td><a href="/problem/problem.php?id=<?php print($record['id']); ?>" target="_blank"><?php print($jpc->h($record['title'])); ?></a></td>
							<td><?php print((string)$record['score']."[pt]"); ?></td>
							<td><?php print((string)$record['solved']); ?></td>
						    </tr>
						<?php } ?>
					    </tbody>
					</table>
				    </div>
				</div>
			    </div>
			<?php } ?>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>
