<?php
//这里是接口的入口地址
//这里实现所有接口功能
include_once("lib/mysql.func.php");
include_once("core/Message.php");
header("Content-type:text/html;charset=utf-8");

//连接数据库
connect();

//获取运动列表(page,lat,lon)
//TODO 按距离目的地排序
//@return ＋已报名人数applys;＋判断是否开始
function getSportList(){
    $page = $_POST['page'];
    $page = is_numberic($page) ? ($page > 0 ? $page : 1) : 1;
    $page = $page - 1;
    $size = 20;//每页纪录的数量
    $from = $page * $size;
    
    $uLat = $_POST['lat'];
    $uLon = $_POST['lon'];
    
    //TODO 检测数据合法性（省略）
    
    $t = time();
    $today = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));//今天0点的时间戳
    $sec = $t - $today;
    $sql = "select `id`,`intro`,`place`,`name`,`phone`,`limit`,`delay`,2 * Asin(Sqrt(power(Sin(({$uLat}-`plat`)*3.1415926/180 / 2), 2) + Cos({$uLat}*3.1415926/180) * Cos(`plat`*3.1415926/180) * power(Sin(({$uLon}-'plon')*3.1415926/180 / 2), 2)))*6378.137  
             as pla_distance,2 * Asin(Sqrt(power(Sin(({$uLat}-`lat`)*3.1415926/180 / 2), 2) + Cos({$uLat}*3.1415926/180) * Cos(`lat`*3.1415926/180) * power(Sin(({$uLon}-'lon')*3.1415926/180 / 2), 2)))*6378.137  
             as user_distance from `sport` where `delay`>{$sec} order by pdis asc limit {$from},{$size}";
    $result = getRows($sql);
    
    if(!$result){
    	return Message::show(200);
    }
    
    //获取每个运动的申请者人数
    foreach($result as $key => $value){
        $sql = "select count(*) from `apply` where `s_id`={$value['id']} limit 1";
        $applys = getRow($sql);
        $result[$key]['apply_num'] = $applys['count(*)'];
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
    '{$place}',{$limit},'{$name}','{$phone}',{$plat},{p$lon},{$lat},{$lon})";
    $result = insert($sql);
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
}else if($act == 'new'){
	newSport();
}else if($act == 'del'){
	delSport();
}


else{
    return Message::show(500);
}
?>