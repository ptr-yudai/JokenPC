<?php
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* まだ開催されていない */
if ($jpc->in_session() === -1) {
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
/* ページ設定 */
$jpc->title = $jpc->prob->info['title']." - JPC";
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
	    <!-- Info : 解答済み -->
	    <?php if ($jpc->auth->is_solved($jpc->prob->info['id'])) { ?>
		<div class="alert alert-info fade in" id="complete">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		    <strong>情報： </strong>
		    <p>あなたは既にこの問題を解答済みです。</p>
		</div>
	    <?php } ?>
	    <!-- Panel : 問題 -->
	    <div class="panel panel-default">
		<div class="panel-heading"><?php print($jpc->prob->info['title']); ?></div>
		<div class="panel-body">
		    <?php print($jpc->prob->info['problem']); ?>
		</div>
	    </div>
	    <!-- Panel : 備考 -->
	    <?php if ($jpc->prob->info['note'] !== '') { ?>
		<div class="panel panel-default">
		    <div class="panel-heading">備考</div>
		    <div class="panel-body">
			<?php print($jpc->prob->info['note']); ?>
		    </div>
		</div>
	    <?php } ?>
	    <!-- Panel : 入出力例 -->
	    <div class="panel panel-default">
		<div class="panel-heading">入出力例</div>
		<div class="panel-body">
		    <div class="form-inline">
			<div class="form-group">
			    <label for="input">入力例：</label><br>
			    <textarea class="form-control" rows="3" id="input" disabled><?php print($jpc->prob->info['ie_input']); ?></textarea>
			</div>
			<div class="form-group">
			    <label for="output">出力例：</label><br>
			    <textarea class="form-control" rows="3" id="output" disabled><?php print($jpc->prob->info['ie_output']); ?></textarea>
			</div>
		    </div>
		</div>
	    </div>
	    <!-- Panel : 制限 -->
	    <div class="panel panel-default">
		<div class="panel-heading">制限</div>
		<div class="panel-body">
		    <p>
			計算時間：<?php print($jpc->prob->info['lim_time']); ?>以内<br>
			メモリ使用量：<?php print($jpc->prob->info['lim_memory']); ?>以下
		    </p>
		</div>
	    </div>
	    <!-- Panel : 提出フォーム -->
	    <div class="panel panel-default">
		<div class="panel-heading">提出</div>
		<div class="panel-body">
		    <form class="form-horizontal" method="POST" action=<?php print("./submit.php?id=".(string)$jpc->prob->id); ?>>
			<div class="form-group">
			    <label class="col-sm-2 control-label" for="lang">言語</label>
			    <div class="col-sm-2">
				<select class="form-control" id="lang" name="lang">
				    <?php
				    foreach($jpc->config->language as $lang => $compile) {
					print("<option>".$lang."</option>");
				    }
				    ?>
				</select>
			    </div>
			</div>
			<div class="form-group">
			    <label class="col-sm-2 control-label" for="code">ソースコード</label>
			    <div class="col-sm-10">
				<textarea placeholder="ソースコード" rows="16" class="form-control" id="code" name="code" required></textarea>
			    </div>
			</div>
			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">提出</button>
			    </div>
			</div>
		    </form>
		</div>
	    </div>
	</div>
    </body>
</html>
