<?php 
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* 問題番号から詳細を取得 */
if ($jpc->prob->get_problem() === false) {
    header("Location: /problem/");
    exit();
}
/* 適切なポストをフィルタリング */
if (empty($_POST['code']) || empty($_POST['lang'])) {
    header("Location: /problem/");
    exit();
}
/* ページ設定 */
$jpc->title = "実行 - JPC";
$jpc->navbar_active = 2;
?>
<!DOCTYPE html>
<html>
    <!-- Head -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<script src="/js/websocket.js"></script>
    </head>
    <!-- Body -->
    <body>
	<?php require(dirname(__FILE__).'/../global/navbar.php'); ?>
	<div class="container-fluid">
	    <!-- Errpr : 警告 -->
	    <div class="alert alert-danger fade in" id="error" style="display: none;">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>エラー： </strong>
		<p id="error-message"></p>
	    </div>

	    <!-- Panel : 実行画面 -->
	    <div class="panel panel-default">
		<div class="panel-heading">実行</div>
		<div class="panel-body">
		    <p>プログラムをチェックしています。</p>
		    <div id="target"></div>
		</div>
	    </div>
	    <textarea id="id" style="display: none;" disabled><?php print($jpc->h($_GET['id'])); ?></textarea>
	    <textarea id="lang" style="display: none;" disabled><?php print($jpc->h($_POST['lang'])); ?></textarea>
	    <textarea id="code" style="display: none;" disabled><?php print($jpc->h($_POST['code'])); ?></textarea>
	</div>
    </body>
</html>
