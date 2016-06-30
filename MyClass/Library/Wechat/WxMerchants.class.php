<?php
class WxMerchants extends WxBase{
	/**
	 * 给用户发送消息
	 */
	public function sendMessage($openid , $content){
		$appid = Config('AppID');
		$appsecret = Config('AppSecret');
		$access_token = $this->_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret");
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token['access_token']}";
		$data = array(
			'touser' => $openid , 
			'msgtype' => 'text' ,
			'text' => array(
				'content' => urlencode($content),
			)
		);
		$response = $this->_curl($url , urldecode(json_encode($data)));
		return $response;
	}
}