<?php

/* 检查是否为0或者1 */
function check1and0($var){
	if($var == 1){
		return 1;
	}
	return false;
}

/* 检查是否为1或者2 */
function check1and2($var){
	if(!is_numeric($var)){
		return false;
	}
	if(!$var==2 && !$var==1){
		return false;
	}
	return $var;
}

/* 检查是否为整数 */
function checkNumber($var){
	if(!is_numeric($var)){
		return 0;
	}
	return $var;
}

/* 验证手机号 */
function checkPhone($var){
	if(preg_match("/^1[0-9]{10}$/",$var)){    
		//验证通过    
		return $var; 
	}else{    
		//手机号码格式不对    
		return false;
	}
}




?>