<?php
/* 
显示错误信息

ajax跳转

 */

/* 跳转 */
function direct($url){
	header("Location:{$url}"); 
	exit; 
}

/* 弹出对话框 */
function alert($msg){
	echo "<script type='text/javascript'> alert('{$msg}');";
	echo "</script>";
	exit;
}
?>