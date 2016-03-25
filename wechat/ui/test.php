<?php
/**
 * Description: 通过code换取网页授权access_token
 * 首先请注意，这里通过code换取的网页授权access_token,与基础支持中的access_token不同。
 * 公众号可通过下述接口来获取网页授权access_token。
 * 如果网页授权的作用域为snsapi_base，则本步骤中获取到网页授权access_token的同时，也获取到了openid，snsapi_base式的网页授权流程即到此为止。
 * @param $code getCode()获取的code参数
 */
$code = $_GET['code'];
echo \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);