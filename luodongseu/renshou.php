<?php

//这里是接口的入口地址
//这里实现所有接口功能
include_once("lib/mysql.func.php");
include_once("core/Message.php");
header("Content-type:text/html;charset=utf-8");

error_reporting(E_ALL);

//连接数据库
connect();

$url = "http://www.chinalife.com.cn/jobs/module/appliedpost/syslap.do";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量



$sql = "select * from `renshou` where `text`='xinxi' order by `nid`";
$result = getRows($sql);

if(!$result){
	echo "对不起~我错了~我没有存数据~";
}

foreach($result as $key => $value){  
    $post_data = array ("searchApplyLoginInfoID" => $value['nid']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    
    preg_match('/stationid=[0-9]+/',$output,$wid);
    if(!strpos($wid[0],'17729')){
    	continue;
    }
    
    echo "<br><hr>  面试者  <br>";
    echo "他的简历编号:  ".$value['nid']."<br>";
    //echo "他的文本信息:  ".$value['text']."<br>";
    echo "他的进度:     ".$value['level']."<br>";
    
    
    echo "他的职位编号:  ";
    echo $wid[0];
    echo "<br>";
    echo "<br>";
    
    echo "他的最新信息:  ".$output."<br>";
}
?>