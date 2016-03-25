<?php
/**
*  @todo 	use for json normal form
*/

class Message
{
	/**
	*@todo		offer to the application use
	*/
	public static function show($ret=200,$data='',$type='json')
	{
		if(!is_numeric($ret))
		{
			return '';
		}
		$result = array(
			"ret" => $ret,
			"data" => $data,
		);
		if($type=='xml')
		{
			self::toXml($result);
			exit;
		}else{
			self::toJson($result);
			exit;
		}
	}

	

	/**
	*@todo 		return json
	*/
	public static  function toJson($data)
	{
		echo json_encode($data);
	}

	/**
	*@todo		return xml
	*
	*/
	public static function toXml($data)
	{
		header("Content-Type:text/xml");//must ensure no output before
		$result = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$result.="<root>\n";
		$result.=self::getXml($data);
		$result.="</root>";
		echo $result;
	}

	/**
	*@todo		adding function for toXml
	*/
	public static function getXml($data)
	{
		$result = '';
		foreach ($data as $key => $value) {
			$result.="<$key>";
			$result.=is_array($value) ? self::getXml($value) : $value;
			$result.="</$key>\n";
		}
		return $result;
	}
}