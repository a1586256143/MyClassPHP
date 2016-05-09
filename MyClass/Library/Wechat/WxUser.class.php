<?php
/**
 * 微信用户类
 * @author Colin <amcolin@126.com>
 */
class WxUser extends WxBase{
	/**
	 * 获取用户的基本资料
	 * @param string $openid 用户的唯一标识
	 * @param string $access_token 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
	 * @return 获取的用户信息
	 * @author Colin <15070091894@163.com>
	 */
	public static function getUserBaseinfo($openid , $access_token){
		if(!$openid) $openid = parent::$info['openid'];
		if(!$access_token) $access_token = parent::$info['access_token'];
		$userinfo_uri = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
		$info = parent::_curl($userinfo_uri);
		return $info;
	}
}