<?php
date_default_timezone_set('PRC');

/**
*	转换时间格式
*	@return date
*/
function formateTime($ptime)
{
	if(!(int)$ptime)
	{
		die('系统故障,请重试');
	}
	$ntime=time()-$ptime;
	
	if($ntime<60)
	{
		//小于1分钟
		if($ntime<=3)
		{
			return '刚刚发布';
		}
		return $ntime.'秒前发布';
	}else if($ntime<60*60)
	{
		$minu=$ntime/60;
		$sec=$ntime%60;
		//小于1小时
		return (int)$minu.'分'.$sec.'秒前发布';
	}else if($ntime<60*60*24)
	{
		//小于1天
		$hou=$ntime/(60*60);
		$minu=($ntime%(60*60))/60;
		$sec=($ntime%(60*60))%60;
		return (int)$hou.'小时'.(int)$minu.'分'.$sec.'秒前发布';
	}else{
		return date("Y-m-d H:i:s", $ptime).'发布';
	}
}





?>