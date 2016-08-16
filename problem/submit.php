<?php
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* 開催期間でない */
if ($jpc->in_session() !== 0) {
    header("Location: /");
    exit();
}
/* ログイン済み */
if ($jpc->auth->is_logged_in() === false) {
    header("Location: /login/");
    exit();
}
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
/* 正解済み */
if ($jpc->auth->is_solved($jpc->prob->info['id'])) {
    header("Location: /problem/");
    exit();
}
/* 暗号化ユーザー名を設定 */
$jpc->auth->encrypt_username();
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
	    <!-- Errpr : 成功 -->
	    <div class="alert alert-success fade in" id="complete" style="display: none;">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>完了： </strong>
		<p>おめでとうございます！<?php print((string)$jpc->prob->info['score']."[pt]"); ?>を獲得しました。</p>
	    </div>
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
		    <p>以下に各入力に対する出力の正誤が表示されます。</p>
		    <table class="table table-bordered">
			<tr>
			    <?php for($i = 1; $i <= (int)$jpc->prob->info['exec_time']; $i++) { ?>
				<th>入力<?php print($i); ?></th>
			    <?php } ?>
			</tr>
			<tr>
			    <?php for($i = 1; $i <= (int)$jpc->prob->info['exec_time']; $i++) { ?>
				<td><span id="<?php print("icon".(string)$i); ?>" style="font-size: 24px;"></span></td>
			    <?php } ?>
			</tr>
		    </table>
		</div>
	    </div>

	    <!-- Panel : コンパイル結果 -->
	    <div class="panel panel-default">
		<div class="panel-heading">コンパイル結果</div>
		<div class="panel-body">
		    <code id="compile">なし</code>
		</div>
	    </div>

	    <textarea id="id" style="display: none;" disabled><?php print($jpc->h($_GET['id'])); ?></textarea>
	    <textarea id="lang" style="display: none;" disabled><?php print($jpc->h($_POST['lang'])); ?></textarea>
	    <textarea id="code" style="display: none;" disabled><?php print($jpc->h($_POST['code'])); ?></textarea>
	    <textarea id="iv" style="display: none;" disabled><?php print($jpc->auth->enc_iv); ?></textarea>
	    <textarea id="user" style="display: none;" disabled><?php print($jpc->auth->enc_user); ?></textarea>
	</div>
    </body>
</html>
