<?php
//这里是接口的入口地址
//这里实现所有接口功能
include_once("lib/mysql.func.php");
include_once("core/Message.php");
header("Content-type:text/html;charset=utf-8");

//error_reporting(E_ALL);
//连接数据库
connect();

//获取运动列表(page,lat,lon)
//TODO 按距离目的地排序
//@return ＋已报名人数applys;＋判断是否开始
function getSportList(){
    $page = $_POST['page'];
    $page = is_numeric($page) ? ($page > 0 ? $page : 1) : 1;
    $page = $page - 1;
    $size = 20;//每页纪录的数量
    $from = $page * $size;
    
    $uLat = $_POST['lat'];
    $uLon = $_POST['lon'];
    $phone = $_POST['phone'];
    //TODO 检测数据合法性（省略）
    
    $t = time();
    
    
    
    $sql = "select `id`,`intro`,`place`,`name`,`phone`,`limit`,`delay`,`plat`,`plon`,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(({$uLat}*PI()/180-`plat`*PI()/180)/2),2)+COS({$uLat}*PI()/180)*COS(`plat`*PI()/180)*POW(SIN(({$uLon}*PI()/180-`plon`*PI()/180)/2),2)))*1000) 
    as pla_distance,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(({$uLat}*PI()/180-`lat`*PI()/180)/2),2)+COS({$uLat}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$uLon}*PI()/180-`lon`*PI()/180)/2),2)))*1000) 
             as user_distance from `sport` where `delay`>{$t} order by pla_distance+(`delay`/1000) asc limit {$from},{$size}";
    $result = getRows($sql);
    
    // echo $sql;
    
    if(!$result){
    	return Message::show(200);
    }
    
    //获取每个运动的申请者人数
    foreach($result as $key => $value){
        $sql = "select count(*) from `apply` where `s_id`={$value['id']} limit 1";
        $applys = getRow($sql);
        $result[$key]['apply_num'] = $applys['count(*)'];
        
        $sql1 = "select `id` from `apply` where `s_id`={$value['id']} and `phone`='{$phone}' limit 1";
        $applied = getRow($sql1);
        if($applied){
        	$result[$key]['isApplyed'] = true;
        }else{
        	$result[$key]['isApplyed'] = false;
        }
    }
    
	return Message::show(201,$result);
}


//删除某个运动(id)
function delSport(){
    $id = $_POST['id'];
    
    //TODO 检测记录是否存在（省略）
    
    $sql = "delete from `sport` where `id`={$id} limit 1";
    $result = deleteRow($sql);
    if(!$result){
        return Message::show(400);
    }
    return Message::show(200);
}


//发布一个运动(intro,place,limit,name,phone,plat,plon,lat,lon)
function newSport(){
	$intro = $_POST['intro'];
    $place = $_POST['place'];
    $limit = $_POST['limit'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $plat = $_POST['plat'];
    $plon = $_POST['plon'];
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];
    $delay = $_POST['delay'];
    
    //TODO 检测数据的合法性（省略）
    
    $sql = "insert into `sport`(`intro`,`delay`,`place`,`limit`,`name`,`phone`,`plat`,`plon`,`lat`,`lon`) values('{$intro}',{$delay},
    '{$place}',{$limit},'{$name}','{$phone}',{$plat},{$plon},{$lat},{$lon})";
    //echo $sql;
    $result = insert($sql);
    if(!$result){
        return Message::show(400);
    }
    return Message::show(200);
}


//获取某人发布的所有运动
function getAllSports(){
	$name = $_POST['name'];
    $phone = $_POST['phone'];
    $page = $_POST['page'];
    
    $from = ($page -1)*20;
    
    $sql = "select *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((`lat`*PI()/180-`plat`*PI()/180)/2),2)+COS(`lat`*PI()/180)*COS(`plat`*PI()/180)*POW(SIN((`lon`*PI()/180-`plon`*PI()/180)/2),2)))*1000) as pla_distance
    from `sport` where `name`='{$name}' and `phone`='{$phone}'  order by `delay` desc limit $from, 20";
    //echo $sql;
    $result = getRows($sql);
    if(!$result){
        return Message::show(200);
    }
    return Message::show(201,$result);
}


//获取某人发布的所有运动信息数目
function getAllSportsNumber(){
	$name = $_POST['name'];
    $phone = $_POST['phone'];
    
    
    $sql = "select count(*) from `sport` where `name`='{$name}' and `phone`='{$phone}'";
    //echo $sql;
    $result = getRow($sql);
    if(!$result){
        return Message::show(201,0);
    }
    return Message::show(201,$result['count(*)']);
}

//===================================================================

//获取某个运动的申请列表
function getSportApplys(){
    $id=$_POST['s_id'];
    $phone = $_POST['phone'];
    
    $sql0 = "select `phone` from `sport` where `id`={$id} limit 1";
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
        $result = getRows($sql);
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
    $name = mysql_escape_string($_POST['name']);
    $phone = mysql_escape_string($_POST['phone']);

    // print_r($_POST);
    //检查是否已报名*
    $sql0 = "select * from `apply` where `s_id`={$id} and `phone`='{$phone}' limit 1";
    //echo $sql0;
    if(getRow($sql0)){
        //已报名
        return Message::show(400);
    }
    
    $t = time(); 
    $sql = "insert into `apply` (`s_id`,`name`,`phone`,`time`) values ({$id},'{$name}','{$phone}',{$t})";
    $result = insert($sql);
    if(!$result){
    	return Message::show(400);
    }
    
	return Message::show(200);
}

//删除申请信息(id)
function deleteSportApply(){
    $id=$_POST['id'];//运动ID
    $phone = $_POST['phone'];//用户手机号
    
    $sql0 = "select * from `apply` where `s_id`={$id} and `phone`='{$phone}' limit 1";
    if(!getRow($sql0)){
        return Message::show(400);
    }
    
    $sql = "delete from `apply` where `s_id`={$id} and `phone`='{$phone}' limit 1";
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
if($act == 'listSports'){
	getSportList();
}else if($act == 'listApplys'){
	getSportApplys();
}else if($act == 'newSport'){
	newSport();
}else if($act == 'delSport'){
	delSport();
}else if($act == 'apply'){
	applySport();
}else if($act == 'updateApply'){
	updateSportApply();
}else if($act == 'delApply'){
	deleteSportApply();
    
}
else if($act == 'mySports')
{
    getAllSports();
}
else if($act == 'mySportsNum')
{
    getAllSportsNumber();
}

else{
    return Message::show(500);

}
?>