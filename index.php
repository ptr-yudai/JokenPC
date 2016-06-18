<?php 
require(dirname(__FILE__).'/jpc/init.php');
$jpc = new JPC();
/* ページ設定 */
$jpc->title = "ホーム - JPC";
$jpc->navbar_active = 1;
?>
<!DOCTYPE html>
<html>
    <!-- Head -->
    <head>
	<?php require(dirname(__FILE__).'/global/head.php'); ?>
    </head>
    <!-- Body -->
    <body>
	<?php require(dirname(__FILE__).'/global/navbar.php'); ?>
	<div class="container-fluid">
	    <!-- Panel : ようこそ -->
	    <div class="panel panel-default">
		<div class="panel-heading">ようこそ</div>
		<div class="panel-body">
		    Joken Programming Contestへようこそ！
		</div>
	    </div>
	</div>
    </body>
</html>
