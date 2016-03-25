<?php
//这里是接口的入口地址
//这里实现所有接口功能
include_once("lib/mysql.func.php");
include_once("core/Message.php");
header("Content-type:text/html;charset=utf-8");

//连接数据库
connect();


//获取某个运动的申请列表
function getSportApplys(){
    $id=$_POST['s_id'];
    $phone = $_POST['phone'];
    
    $sql0 = "select `phone` from `sport` where `s_id`={$id} limit 1";
    $master0 = getRow($sql0); 
    $mPhone0 = $master0['phone'];
    if($phone==$mPhone0)
    {
        //发起者
        $sql = "select * from `apply` where `s_id`={$id}";
        $result = getRows($sql);
        if(!$result){
            return Message::show(200);
        }
        return Message::show(201,$result);
    }
    else
    {
        $sql = "select * from `apply` where `s_id`={$id} and `phone`='{$phone}' limit 1";
        $result = getRow($sql);
        if(!$result){
            //游客
            return Message::show(200);
        }
        //申请者
        return Message::show(201,$result);
        
     }
}


//更新某个申请信息(id,name,phone)
function updateSportApply(){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $sql = "update `apply` 
            set `name`='{$name}' , `phone`='{$phone}' 
            where `id`={$id} 
            limit 1";
    $result = update($sql);
    if(!$result){
    	return Message::show(400);
    }
    
	return Message::show(200);
}
    

//添加申请信息(s_id,name,phone,lat,lon)
function applySport(){
    $id = $_POST['s_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $sql = "insert into `apply` (`s_id`,`name`,`phone`) values ({$id},'{$name}','{$phone}')";
    $result = insert($sql);
    if(!$result){
    	return Message::show(400);
    }
    
	return Message::show(200);
}

//删除申请信息(id)
function deleteSportApply(){
    $id=$_POST['id'];
	$sql = "delete from `apply`
            where `id`={$id}
            limit 1";
    $result = deleteRow($sql);
    if(!$result){
    	return Message::show(400);
    }
    
	return Message::show(200);
}

$act = $_GET['act'];
if(!$act){
	die("<h1>Welcome to 网络课程设计<h1><h5> by group 罗东&代甜甜&刘胜</h5>");
}
if($act == 'list'){
	getSportList();
}
?>