<?php
require(dirname(__FILE__).'/../jpc/init.php');
$jpc = new JPC();
$jpc->auth->logout();
header("Location: /login/");
exit();
?>
