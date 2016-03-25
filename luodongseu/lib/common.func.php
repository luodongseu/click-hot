<?php

/* 获取距离比重 */
function getDistancePoint($dis){
	if($dis < 100){
		return 100;
	}else if($dis < 500){
		return 80;
	}else if($dis < 1000){
		return 60;
	}else if($dis < 5000){
		return 40;
	}else if($dis < 10000){
		return 20;
	}else if($dis < 50000){
		return 10;
	}else {
		return 0;
	}
}

/* 计算两个坐标之间的距离 */
function valDistance($lat1,$lat2,$lon1,$lon2){
		$dis = round(6378.138*2*asin(sqrt(pow(sin( ($lat1*pi()/180-$lat2*pi()/180)/2),2)+cos($lat1*pi()/180)*cos($lat2*pi()/180)* pow(sin( ($lon1*pi()/180-$lon2*pi()/180)/2),2)))*1000);
		return $dis;
}


/* 获取兴趣标签比重 */
function getInterestPoint($in1,$in2){
	$int1 = explode(";",$in1);
	$int2 = explode(";",$in2);
	
	$sames = array_intersect($int1,$int2);
	$same_num  = count($sames);
	if($same_num >= 4){
		return 100;
	}else if($same_num >= 3){
		return 78;
	}else if($same_num >= 2){
		return 60;
	}else if($same_num >= 1){
		return 50;
	}else{
		return 0;
	}
}

/* 获取心情比重 */
function getMoodPoint($m1,$m2){
	/* 心情值 */
	$mood = array(
		101 => 100,
		102 => 90,
		103 => 78,
		104 => 64,
		105 => 50,
		106 => 36,
		107 => 22,
		108 => 8
	);
	return 100 - abs($mood[$m1] - $mood[$m2]);
}

/* 计算缘分值 */
function valLuky($m1,$m2,$in1,$in2,$dis,$s1,$s2,$sex1,$sex2){
	/* 异性 */
	$star_d = array(
			0 => array(80,75,90,60,100,70,80,65,95,70,85,75),
			1 => array(75,80,75,85,70,100,70,80,60,95,65,90),
			2 => array(90,75,80,75,85,70,100,60,80,65,95,70),
			3 => array(60,85,75,80,75,90,65,100,70,80,70,100),
			4 => array(100,70,85,75,80,75,85,70,95,60,80,65),
			5 => array(70,100,70,90,75,80,75,85,65,95,60,80),
			6 => array(80,70,100,65,85,75,80,75,90,65,95,60),
			7 => array(65,80,60,100,70,85,75,80,75,90,70,95),
			8 => array(95,60,80,70,95,65,90,75,80,75,100,70),
			9 => array(70,100,65,80,60,95,65,75,75,80,75,85),
			10 => array(85,65,100,70,80,60,95,70,95,75,80,75),
			11 => array(75,90,70,100,65,80,60,95,70,100,75,80),
	);

	/* 同性 */
	$star_s = array(
			0 => array(90,60,90,60,100,73,75,60,100,60,90,60),
			1 => array(60,75,70,75,60,95,70,65,75,96,60,75),
			2 => array(90,70,100,90,90,90,95,60,90,60,90,65),
			3 => array(60,75,90,70,60,95,75,90,75,80,80,90),
			4 => array(100,60,100,60,70,78,70,60,90,63,80,60),
			5 => array(73,95,90,95,78,85,90,60,90,75,60,80),
			6 => array(75,70,95,75,70,90,90,60,100,70,90,70),
			7 => array(60,65,60,90,60,60,60,60,64,80,65,90),
			8 => array(90,75,90,75,90,90,100,64,90,60,90,60),
			9 => array(60,96,60,80,63,75,70,80,60,70,60,90),
			10 => array(90,60,90,80,80,60,90,65,90,60,80,65),
			11 => array(60,75,65,90,60,80,70,90,60,90,65,90),
	);
	
	/* 心情 * 0.2 + 星座 * 0.2 + 兴趣 * 0.4 + 距离 * 0.2 */
	$mp = getMoodPoint($m1,$m2) * 0.2;	//心情
	
	if($sex1 == $sex2){								//星座
		/* 同性 */
		$starp = $star_s;
	}else{
		/* 异性 */
		$starp = $star_d;
	}
	
	$intp =  getInterestPoint($in1,$in2) * 0.2;	//兴趣
	
	$disp = getDistancePoint($dis) * 0.2;		//距离
	
	return  intval($mp + $starp[$s1][$s2] * 0.4 + $intp + $disp);
}

?>