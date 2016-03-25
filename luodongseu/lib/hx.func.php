<?php
/*环信的操作*/


/*环信注册*/
function hxRegister($username="",$password="",$nickname=""){
	$url="https://a1.easemob.com/l-studio2015/momo/users";
    $body=array(
	    "username"=>"$username",
	    "password"=>"$password",
	    "nickname"=>"$nickname"
    );
    // var_dump($body);
    $patoken=json_encode($body);
    $res = postCurl($url,$patoken);
    $Result = array();
    $Result =  json_decode($res, true);
    //var_dump($Result);
    if(isset($Result['error'])){
    	return false;
    }else{
    	return true;
    }
}


/**修改用戶昵称*/
function hxUpdateNick($username="",$nickname=""){
	$url="https://a1.easemob.com/l-studio2015/momo/users/".$username;
    $body=array(
	    "nickname"=>$nickname
    );
    // var_dump($body);
    $pbody=json_encode($body);
    $token = hxGetToken();
//    var_dump($token);
    $ptoken = array(
    	'Authorization: Bearer '.$token
    );
    $res = postCurl($url,$pbody, $ptoken,'PUT');
    $Result = array();
    $Result =  json_decode($res, true);
    // var_dump($Result);
    if(isset($Result['error'])){
    	return false;
    }else{
    	return true;
    }
}

/* 加黑名单 */
function hxAddBlack($username="",$blackname=""){
	$url="https://a1.easemob.com/l-studio2015/momo/users/".$username."/blocks/users";
    $body=array(
	    "usernames"=>array($blackname)
    );
    // var_dump($body);
    $pbody=json_encode($body);
    $token = hxGetToken();
    /* var_dump($token); */
    $ptoken = array(
    	'Authorization: Bearer '.$token
    );
    $res = postCurl($url,$pbody, $ptoken);
    $Result = array();
    $Result =  json_decode($res, true);
   /* var_dump($Result); */
    if(isset($Result['error'])){
    	return false;
    }else{
    	return true;
    }
}

/* 移除黑名单 */
function hxRemoveBlack($username="",$blackname=""){
	$url="https://a1.easemob.com/l-studio2015/momo/users/".$username."/blocks/users";
    $body=array(
	    "usernames"=>array($blackname)
    );
    // var_dump($body);
    $pbody=json_encode($body);
    $token = hxGetToken();
    /* var_dump($token); */
    $ptoken = array(
    	'Authorization: Bearer '.$token
    );
    $res = postCurl($url,$pbody, $ptoken,"DELETE");
    $Result = array();
    $Result =  json_decode($res, true);
   /* var_dump($Result); */
    if(isset($Result['error'])){
    	return false;
    }else{
    	return true;
    }
}

/**
*获取HXapp管理员token    
*
*	返回token   7 天内有效
*/
function hxGetToken()
{
    $url="https://a1.easemob.com/l-studio2015/momo/token";
    $body=array(
	    "grant_type"=>"client_credentials",
	    "client_id"=>"YXA6R2c20IHSEeWIltu326Dqsw",
	    "client_secret"=>"YXA6PONH1QZ4xWinOJQT-1f9GsWTBjw"
    );
    $patoken=json_encode($body);
    $res = postCurl($url,$patoken);
    $tokenResult = array();
    
    $tokenResult =  json_decode($res, true);
    return $tokenResult["access_token"];    
}
	



//postCurl方法
function postCurl($url, $body, $header = array(), $method = "POST")
{
    array_push($header, 'Accept:application/json');
    array_push($header, 'Content-Type:application/json');
    array_push($header, 'http:multipart/form-data');

    $ch = curl_init();//启动一个curl会话
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, $method, 1);
    
    switch ($method){ 
        case "GET" : 
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        break; 
        case "POST": 
            curl_setopt($ch, CURLOPT_POST,true); 
        break; 
        case "PUT" : 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
        break; 
        case "DELETE":
            curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
        break; 
    }
    
    curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    if (isset($body{3}) > 0) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    if (count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    $ret = curl_exec($ch);
    $err = curl_error($ch);
    

    curl_close($ch);
    //clear_object($ch);
    //clear_object($body);
    //clear_object($header);

    if ($err) {
        return $err;
    }

    return $ret;
}

?>
