<?php
header("Content-type: text/html; charset=utf-8");
$mysql = new SaeMysql();

echo time().'<br/>';
var_dump($_POST);

//$sql = "INSERT  INTO `guest` ( `ip`) VALUES ('".$ip."') ";
//$mysql->runSql($sql);