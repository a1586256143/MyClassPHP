<?php
/**
 * 微信基础类
 * @author Colin <amcolin@126.com>
 */
class WxBase{
	//APPID
	protected $appid;
	//APP签名		
	protected $appsecret;			
	protected $appsercet_path;
	//微信临时code
	protected $code;
	//微信的回调地址
	protected $redirect_uri;	
	//微信openid地址
	protected $openid_uri;
	//微信获取access token 地址
	protected $accesstoken_uri;
	//access_token的值
	protected static $info;

	/**
	 * 初始化方法，初始化APPID APPSECRET 地址等信息
	 * @param string $scope 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
	 * @param string $state 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节
	 * @author Colin <15070091894@163.com>
	 */
	public function __construct($scope = 'snsapi_base' , $state = 'STATE'){
		$this->appid = Config('APPID');
		$this->appsecret = Config('APPSERCET');
		$this->redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		$this->openid_uri = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$this->redirect_uri}&response_type=code&scope=$scope&state=$state#wechat_redirect";
		$this->appsercet_path = './cert/cert.pem';
	}

	/**
	 * 获取用户的Open Id
	 * @author Colin <15070091894@163.com>
	 */
	public function getOpenid(){
		$this->code = values('get' , 'code');
		if(!$this->code){
			header("Location: {$this->openid_uri}");
			exit();
		}else{
			unset($this->code);
			self::$info = $this->getAccessToken();
			return self::$info;
		}
	}

	/**
	 * 获取用户的临时acctoken
	 * @author Colin <15070091894@163.com>
	 */
	public function getAccessToken(){
		$this->code = values('get' , 'code');
		$this->accesstoken_uri = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$this->code}&grant_type=authorization_code";
		return $this->_curl($this->accesstoken_uri);
	}

	/**
	 * Curl操作
	 * @param url 要访问地址
	 * @param data 数据
	 * @param timeout 超时
	 * @return array
	 * @author Colin <15070091894@163.com>
	 */
	public function _curl($url , $data = '' , $timeout = 3){
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在 
		curl_setopt($ch, CURLOPT_URL, $url);
		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
		$response = curl_exec($ch); 
		if ($response  === FALSE) {
			echo "cURL 具体出错信息: " . curl_error($ch);
		}
		return json_decode($response , true);
	}
}