<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);//^E_WARNING^E_NOTICE

/**
*	连接数据库	
*	@return 连接
*/
function connect(){
	$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS) or die("数据库连接失败".mysql_error());
	mysql_set_charset('utf8');
	mysql_select_db(SAE_MYSQL_DB) or die("指定数据库打开失败");
}

/**
*	插入数据	
*	@return 插入的数据的ID
*/
function insert($sql){
	$result = mysql_query($sql) ;
	if($result){
		$res = mysql_insert_id();
		return $res?$res:true;
	}else{
		return false;
	}
}

/**
*	查询单行数据	
*	@return 
*/
function getRow($sql){
	$result = mysql_query($sql);
	if($result){
		$row = mysql_fetch_assoc($result);
		return $row;
	}else{
		return false;
	}
}

/**
*	查询多行数据
*	@return 
*/
function getRows($sql){
	$result = mysql_query($sql) or die(mysql_error());
	$rows = array();
	if($result){
		while($row = mysql_fetch_assoc($result)){
			array_push($rows,$row);
		}
		return $rows;
	}else{
		return false;
	}
}

/**
*	得到结果集的数量
*	@return 
*/
function getResultNum($sql){
	$result = mysql_query($sql);
	return mysql_num_rows($result);
}

/**
*	删除单行纪录
*	@return 
*/
function deleteRow($sql){
	$result = mysql_query($sql);
	return mysql_affected_rows();
}

/**
*	update单行纪录
*	@return 
*/
function update($sql){
	$result = mysql_query($sql);
	return mysql_affected_rows();
}


?>