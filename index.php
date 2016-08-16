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
		    <p>
			Joken Programming Contestへようこそ！<br>
			開始日時：<?php print($jpc->config->start_time->format('Y/m/d H:i:s')); ?><br>
			終了日時：<?php print($jpc->config->end_time->format('Y/m/d H:i:s')); ?>
			<?php
			switch($jpc->in_session()) {
			    case -1:
				print("<br>まだ大会は開催していません。");
				break;
			    case 0:
				print("<br>ただいま大会が開催されています。");
				break;
			    case 1:
				print("<br>既に大会は終了しました。");
				break;
			}
			?>
		    </p>
		</div>
	    </div>
	</div>
    </body>
</html>
