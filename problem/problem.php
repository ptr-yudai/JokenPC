<?php 
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
/* ページ設定 */
$jpc->title = "問題 - JPC";
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
	    <!-- Panel : 問題 -->
	    <div class="panel panel-default">
		<div class="panel-heading">問題タイトル</div>
		<div class="panel-body">
		    <p>1+1=?</p>
		</div>
	    </div>
	    <!-- Panel : 制限 -->
	    <div class="panel panel-default">
		<div class="panel-heading">制限</div>
		<div class="panel-body">
		    <p>計算時間：3秒以内<br>メモリ使用量：64[MB]以下</p>
		</div>
	    </div>
	    <!-- Panel : 提出フォーム -->
	    <div class="panel panel-default">
		<div class="panel-heading">提出</div>
		<div class="panel-body">
		    <form class="form-horizontal">
			<div class="form-group">
			    <label class="col-sm-2 control-label" for="lang">言語</label>
			    <div class="col-sm-2">
				<select class="form-control" id="lang">
				    <option>C</option>
				    <option>C++</option>
				    <option>Python 2</option>
				    <option>Python 3</option>
				</select>
			    </div>
			</div>
			<div class="form-group">
			    <label class="col-sm-2 control-label" for="code">ソースコード</label>
			    <div class="col-sm-10">
				<textarea placeholder="ソースコード" rows="32" class="form-control" id="code"></textarea>
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
