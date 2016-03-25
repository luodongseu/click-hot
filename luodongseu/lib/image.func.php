<?php
/**
*	创建添加水印图片
*	$text:要添加的文字
*	$src_path:被添加水印的图片
**/
function addMark($src_path,$text){
	$name = $src_path;
	
	list($src_w, $src_h) = getimagesize($src_path);//$src_w:原图片的宽
	
	//创建图片的实例
	$dst = imagecreatefromstring(file_get_contents($src_path));
	//打上文字
	$font ='../Data/simsun.ttc';//字体
	$white = imagecolorallocate($dst, 255, 255, 255);//字体颜色白色
	$black = imagecolorallocate($dst, 0, 0, 0);//字体颜色白色
	imagefilledrectangle($dst, $src_w-100,$src_h-50, $src_w, $src_h-20, $black);//填充背景色(矩形)
	imagefttext($dst, 15, 0, $src_w-80, $src_h-30, $white, $font, $text);//添加文字
	//输出图片
	list($dst_w, $dst_h, $dst_type) = getimagesize($src_path);
	switch ($dst_type) {
		case 1://GIF
			imagegif($dst,$name);
			break;
		case 2://JPG
			imagejpeg($dst,$name);
			break;
		case 3://PNG
			imagepng($dst,$name);
			break;
		default:
			break;
	}
}

/**
 * 获取文件类型
 * @param string $filename 文件名称
 * @return string 文件类型
 */
function getFileType($filename) {
	return substr($filename, strrpos($filename, '.') + 1);
}

/**
 * 保存文件缩略图
 * @param string $filename 文件名称
 * @param string $newWidth 缩略图的宽度
 * @param string $imgName 缩略图文件名称
 */
function thumbnail($filename, $newWidth, $imgName)
{
	list ( $width, $height ) = getimagesize ( $filename );
	$newHeight = $height / ($width / $newWidth);//等比例
	$newImage = imagecreatetruecolor ( $newWidth, $newHeight );
	/* $oldImage = imagecreatefromjpeg ( $filename ); */
	// 图像类型
	$type=exif_imagetype($filename);
	$support_type=array(IMAGETYPE_JPEG , IMAGETYPE_PNG , IMAGETYPE_GIF);
	if(!in_array($type, $support_type,true)) {
		return Message::show(411);
 	}
	//Load image
	switch($type) {
		case IMAGETYPE_JPEG :
			$oldImage=imagecreatefromjpeg($filename);
			break;
		case IMAGETYPE_PNG :
			$oldImage=imagecreatefrompng($filename);
			break;
		case IMAGETYPE_GIF :
			$oldImage=imagecreatefromgif($filename);
			break;
		default:
			return Message::show(411);
	}
	imagecopyresampled ( $newImage, $oldImage, 0, 0, 0, 0, $newWidth, $newHeight, $width,$height );
		 
	//输出png图像
	imagepng ( $newImage, $imgName);
  //  imagedestroy ( $filename );  //若不用于显示则不用该方法。
}


/**
*	生成缩略图
*	jpg文件上传并显示缩略图
*	返回缩略图地址
**/
function uploadImageFiles($file,$name){
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/MoMo/file/thumb/t_'.$name;
	thumbnail($file['tmp_name'], 200, $uploaddir);
	return SERVER."file/thumb/t_".$name;
}


// 获得任意大小图像，不足地方拉伸，不产生变形，不留下空白
function my_image_resize($src_file, $dst_file , $new_width , $new_height) {
	if($new_width <1 || $new_height <1) {
		return Message::show(400);
	}
	if(!file_exists($src_file)) {
		return Message::show(419);
	}
	// 图像类型
	$type=exif_imagetype($src_file);
	$support_type=array(IMAGETYPE_JPEG , IMAGETYPE_PNG , IMAGETYPE_GIF);
	if(!in_array($type, $support_type,true)) {
		return Message::show(411);
 	}
	//Load image
	switch($type) {
		case IMAGETYPE_JPEG :
			$src_img=imagecreatefromjpeg($src_file);
			break;
		case IMAGETYPE_PNG :
			$src_img=imagecreatefrompng($src_file);
			break;
		case IMAGETYPE_GIF :
			$src_img=imagecreatefromgif($src_file);
			break;
		default:
			return Message::show(411);
	}
	$w=imagesx($src_img);
	$h=imagesy($src_img);
	$ratio_w=1.0 * $new_width / $w;
	$ratio_h=1.0 * $new_height / $h;
	$ratio=1.0;
	// 生成的图像的高宽比原来的都小，或都大 ，原则是 取大比例放大，取大比例缩小（缩小的比例就比较小了）
	if( ($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
		if($ratio_w < $ratio_h) {
			$ratio = $ratio_h ; // 情况一，宽度的比例比高度方向的小，按照高度的比例标准来裁剪或放大
		}else {
			$ratio = $ratio_w ;
		}
		// 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
		$inter_w=(int)($new_width / $ratio);
		$inter_h=(int) ($new_height / $ratio);
		$inter_img=imagecreatetruecolor($inter_w , $inter_h);
		imagecopy($inter_img, $src_img, 0,0,0,0,$inter_w,$inter_h);
		// 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
		// 定义一个新的图像
		$new_img=imagecreatetruecolor($new_width,$new_height);
		imagecopyresampled($new_img,$inter_img,0,0,0,0,$new_width,$new_height,$inter_w,$inter_h);
		switch($type) {
			case IMAGETYPE_JPEG :
				imagejpeg($new_img, $dst_file,100); // 存储图像
				break;
			case IMAGETYPE_PNG :
				imagepng($new_img,$dst_file,9);
				break;
			case IMAGETYPE_GIF :
				imagegif($new_img,$dst_file,100);
			break;
				default:
			break;
		}
	} // end if 1
	// 2 目标图像 的一个边大于原图，一个边小于原图 ，先放大平普图像，然后裁剪
	// =if( ($ratio_w < 1 && $ratio_h > 1) || ($ratio_w >1 && $ratio_h <1) )
	else{
		$ratio=$ratio_h>$ratio_w? $ratio_h : $ratio_w; //取比例大的那个值
		// 定义一个中间的大图像，该图像的高或宽和目标图像相等，然后对原图放大
		$inter_w=(int)($w * $ratio);
		$inter_h=(int) ($h * $ratio);
		$inter_img=imagecreatetruecolor($inter_w , $inter_h);
		//将原图缩放比例后裁剪
		imagecopyresampled($inter_img,$src_img,0,0,0,0,$inter_w,$inter_h,$w,$h);
		// 定义一个新的图像
		$new_img=imagecreatetruecolor($new_width,$new_height);
		imagecopy($new_img, $inter_img, 0,0,0,0,$new_width,$new_height);
		switch($type) {
			case IMAGETYPE_JPEG :
				imagejpeg($new_img, $dst_file,100); // 存储图像
				break;
			case IMAGETYPE_PNG :
				imagepng($new_img,$dst_file,100);
				break;
			case IMAGETYPE_GIF :
				imagegif($new_img,$dst_file,100);
			break;
				default:
				break;
		}
	}
}// end function
?>