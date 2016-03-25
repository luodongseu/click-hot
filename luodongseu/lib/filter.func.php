<?php
/* 登录过滤 */
function checkLogin(){
	if(!isset($_SESSION['admin_id'])){
		direct("login.html");
		die();
	}
}

?>