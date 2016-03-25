<?php
header("Content-type: text/html; charset=utf-8");

/* echo "<br>".$_SERVER['PHP_SELF'];#当前正在执行脚本的文件名，与 document root相关
echo "<br>".$_SERVER['argv'];#传递给该脚本的参数。
echo "<br>".$_SERVER['argc']; #包含传递给程序的命令行参数的个数（如果运行在命令行模式）。
echo "<br>".$_SERVER['GATEWAY_INTERFACE']; #服务器使用的 CGI 规范的版本。例如，“CGI/1.1”。
echo "<br>".$_SERVER['SERVER_NAME']; #当前运行脚本所在服务器主机的名称。
echo "<br>".$_SERVER['SERVER_SOFTWARE']; #服务器标识的字串，在响应请求时的头部中给出。
echo "<br>".$_SERVER['SERVER_PROTOCOL']; #请求页面时通信协议的名称和版本。例如，“HTTP/1.0”。
echo "<br>".$_SERVER['REQUEST_METHOD']; #访问页面时的请求方法。例如：“GET”、“HEAD”，“POST”，“PUT”。
echo "<br>".$_SERVER['QUERY_STRING']; #查询(query)的字符串。
echo "<br>".$_SERVER['DOCUMENT_ROOT']; #当前运行脚本所在的文档根目录。在服务器配置文件中定义。
echo "<br>".$_SERVER['HTTP_ACCEPT']; #当前请求的 Accept: 头部的内容。
echo "<br>".$_SERVER['HTTP_ACCEPT_CHARSET']; #当前请求的 Accept-Charset: 头部的内容。例如：“iso-8859-1,*,utf-8”。
echo "<br>".$_SERVER['HTTP_ACCEPT_ENCODING']; #当前请求的 Accept-Encoding: 头部的内容。例如：“gzip”。
echo "<br>".$_SERVER['HTTP_ACCEPT_LANGUAGE'];#当前请求的 Accept-Language: 头部的内容。例如：“en”。
echo "<br>".$_SERVER['HTTP_CONNECTION']; #当前请求的 Connection: 头部的内容。例如：“Keep-Alive”。
echo "<br>".$_SERVER['HTTP_HOST']; #当前请求的 Host: 头部的内容。
echo "<br>".$_SERVER['HTTP_REFERER']; #链接到当前页面的前一页面的 URL 地址。
echo "<br>".$_SERVER['HTTP_USER_AGENT']; #当前请求的 User_Agent: 头部的内容。
echo "<br>".$_SERVER['HTTPS'];# — 如果通过https访问,则被设为一个非空的值(on)，否则返回off
echo "<br>".$_SERVER['REMOTE_ADDR']; #正在浏览当前页面用户的 IP 地址。
echo "<br>".$_SERVER['REMOTE_HOST']; #正在浏览当前页面用户的主机名。
echo "<br>".$_SERVER['REMOTE_PORT']; #用户连接到服务器时所使用的端口。
echo "<br>".$_SERVER['SCRIPT_FILENAME']; #当前执行脚本的绝对路径名。
echo "<br>".$_SERVER['SERVER_ADMIN']; #管理员信息
echo "<br>".$_SERVER['SERVER_PORT'];  #服务器所使用的端口
echo "<br>".$_SERVER['SERVER_SIGNATURE']; #包含服务器版本和虚拟主机名的字符串。
echo "<br>".$_SERVER['PATH_TRANSLATED']; #当前脚本所在文件系统（不是文档根目录）的基本路径。
echo "<br>".$_SERVER['SCRIPT_NAME']; #包含当前脚本的路径。这在页面需要指向自己时非常有用。
echo "<br>".$_SERVER['REQUEST_URI']; #访问此页面所需的 URI。例如，“/index.html”。
echo "<br>".$_SERVER['PHP_AUTH_USER']; #当 PHP 运行在 Apache 模块方式下，并且正在使用 HTTP 认证功能，这个变量便是用户输入的用户名。
echo "<br>".$_SERVER['PHP_AUTH_PW'];  #当 PHP 运行在 Apache 模块方式下，并且正在使用 HTTP 认证功能，这个变量便是用户输入的密码。
echo "<br>".$_SERVER['AUTH_TYPE'];  #当 PHP 运行在 Apache 模块方式下，并且正在使用 HTTP 认证功能，这个变量便是认证的类型。 */
$ip=$_SERVER['REMOTE_ADDR'];


//自定义链接数据库
//连主库
//$link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
//连从库
//$link=mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
//if($link)
//{
//    mysql_select_db(SAE_MYSQL_DB,$link);
//    //your code goes here
//}

//用Sae接口访问数据库
//实例化Sae的SQL类
$mysql = new SaeMysql();
//实例化Sae的邮件
$mail = new SaeMail();
//$ret = $mail->quickSend( '213120988@seu.edu.cn' , '有IP访问' , 'IP地址：'.$ip , 'luodongseu@gmail.com' , '1063538305' );
//发送失败时输出错误码和错误信息
if ($ret === false)
{
    // var_dump($mail->errno(), $mail->errmsg());
}


//获取当前IP的编号
$sql = "SELECT `id` FROM `guest` where `ip`='$ip' limit 1";
$ipn = $mysql->getLine( $sql );
if($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
else{
    if(!$ipn)
    {
        //如果是首次访问
        //查找最后一个编号
        $sql = "SELECT count(*) FROM `guest`";
        $numArray = $mysql->getLine( $sql );
        if($mysql->errno() != 0)
        {
            die("Error:" . $mysql->errmsg());
        }
        else{
            //var_dump($numArray);
            $newNo = (int)$numArray['count(*)'] + 1;
            $times = '首次(No.'.$newNo.')';
            //第一次访问本站,登记
            $sql = "INSERT  INTO `guest` ( `ip`) VALUES ('".$ip."') ";
            $mysql->runSql($sql);
            if ($mysql->errno() != 0)
            {
                die("Error:" . $mysql->errmsg());
            }else{
                //插入成功
                //获取所有访问的人编号
                $sql = "SELECT * FROM `guest` limit 100";
                $data = $mysql->getData( $sql );
                //var_dump($data);
            }
        }
    }else{
        //获取当前IP的访问次数
        $sql = "SELECT count(*) FROM `guest_click` where `uip`='$ip'";
        $data = $mysql->getLine( $sql );
        if ($mysql->errno() != 0)
        {
            die("Error:" . $mysql->errmsg());
        }else{
       		//var_dump($data);
        	$times = (int)$data['count(*)'] + 1;
        }
     }
}
    
//增加一条当前IP的访问记录
$sql = "INSERT  INTO `guest_click` ( `uip`) VALUES ('".$ip."') ";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}else{
    //插入成功
}

$data=array();
//获取所有访问的人编号
$sql = "SELECT * FROM `guest` limit 100";
$data = $mysql->getData( $sql );
//var_dump($data);

//关闭数据库链接
$mysql->closeDb();
?>

<html>
<head>
<script type="text/javascript" src="jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="main.js"></script>
<link href="main.css" rel="stylesheet" type="text/css">
<title>汪小我</title>
</head>
<body>
<div class="header"> 
	<nav>
        <span>DT工作室</span>
        <ul>
            <li><a class="link" href="#">小我</a></li>
            <li><a class="link" href="#">小我</a></li>
            <li><a class="link" href="#">小我</a></li>
        </ul>
    </nav>
</div>
<div class="content">
    <div class="guest">
        <span >
        <?php
		echo "我就是我，是最美的烟火<br/>";
		echo '------------------------------------<br>';
        echo '您的IP:'.$_SERVER['REMOTE_ADDR'].'<br>';
		echo "您是第<span style='color:#fff;font-size:28px;'>$times</span>次访问本网站<br>";
		echo '------------------------------------';
        ?>
        </span>
    </div>
    <div class="allg">
        <table cellspacing="0px">
            <thead>
                <td align="center" width="100">序号   </td>
                <td align="center" width="250">IP</td>
            </thead>
            <tbody>
                <?php
                foreach((array)$data as $k=>$row)
                {
                ?>
                <tr>
                    <td align="center"><?php echo $row['id'];?></td>
                    <td align="center"><?php echo $row['ip'];?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="footer">
    <div class="share">
    	<ul>
            <li><span>分享至</span></li>
            <li><a class="link" href="#"><img src="share_facebook.png" width="20" height="20"/></a></li>
            <li><a class="link" href="#"><img src="share_weixin.png" width="20" height="20"/></a></li>
            <li><a class="link" href="#"><img src="share_qq.png" width="20" height="20"/></a></li>
        </ul>
    </div>
    <div class="copy">
    	Copyright &copy DT工作室 2015-2016
    </div>
</div>
</body>
</html>






























