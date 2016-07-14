<?php 
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ログイン済み */
if ($jpc->auth->is_logged_in() === false) {
    header("Location: /login/");
}
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
			<!-- Algorithm -->
			<div class="panel panel-primary">
				<a class="list-group-item active" data-toggle="collapse" data-parent="#category" href="#category-algorithm">
				    Algorithm
				    <span class="badge">5</span>
				</a>
			    <div class="panel-collapse collapse" id="category-algorithm">
				<div class="panel-body">
				    <table class="table table-hover">
					<thead>
					    <tr>
						<th></th>
						<th>問題</th>
						<th>スコア</th>
						<th>正解者数</th>
					    </tr>
					</thead>
					<tbody>
					    <tr>
						<td><span class="glyphicon glyphicon-ok"></span></td>
						<td>練習問題</td>
						<td>10</td>
						<td>4</td>
					    </tr>
					</tbody>
				    </table>
				</div>
			    </div>
			</div>
			<!-- Math -->
			<div class="panel panel-primary">
			    <a class="list-group-item active" data-toggle="collapse" data-parent="#category" href="#category-math">
				Math
			    </a>
			    <div class="panel-collapse collapse" id="category-math">
				<div class="panel-body">
				    <p>Hello, world!</p>
				</div>
			    </div>
			</div>
			<!-- Cryptography -->
			<div class="panel panel-primary">
			    <a class="list-group-item active" data-toggle="collapse" data-parent="#category" href="#category-cryptography">
				Cryptography
			    </a>
			    <div class="panel-collapse collapse" id="category-cryptography">
				<div class="panel-body">
				    <p>Hello, world!</p>
				</div>
			    </div>
			</div>
			<!-- Data Proc -->
			<div class="panel panel-primary">
			    <a class="list-group-item active" data-toggle="collapse" data-parent="#category" href="#category-data">
				Media
			    </a>
			    <div class="panel-collapse collapse" id="category-data">
				<div class="panel-body">
				    <p>Hello, world!</p>
				</div>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>
