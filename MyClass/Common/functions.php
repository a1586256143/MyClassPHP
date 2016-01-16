<?php
	/*
		Author : Colin,
		Creation time : 2015-8-1
		FileType : 
		FileName : 
	*/

	/**
	 * 显示信息
	 * @param message 信息内容
	 * @author Colin <15070091894@163.com>
	 */
	function ShowMessage($_message){
		header('Content-Type:text/html;charset=UTF-8');
		die('
			<div style="width:400px;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;">
				<dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;">
					<dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">
						MyClass提示信息
					</dt>
					<dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0;word-break:break-all;">
					'.$_message.'
					</dd>
					<dd style="padding:0px;margin:0px;">
						<a href="javascript:void(0);" style="font-size:15px;color:#181884;width:100%;text-align:center;display:block;" id="back">
							[ 返回 ]
						</a>
					</dd>
				</dl>
			</div>
			<script>
				var back = document.getElementById("back");
				back.onclick = function(){
					window.history.back();
				}
			</script>
		');
	}

	/**
	 * C方法实例化控制器
	 * @param name 模型名称
	 * @author Colin <15070091894@163.com>
	 */
	function C($name , $method = null){
		$filepath = APP_PATH.'/Controller/'.$name.Config('DEFAULT_CONTROLLER_SUFFIX').Config('DEFAULT_CLASS_SUFFIX');
		if(!file_exists($filepath)){
			throw new \MyClass\libs\MyError($filepath.'控制器不存在！');
		}
		if(empty($method)){
			$_controller = \MyClass\libs\ObjFactory::CreateController($name);
			$_controller->index();
		}else{
			$_controller = \MyClass\libs\ObjFactory::CreateController($name);
			if(!method_exists($_controller,$method)){
				throw new \MyClass\libs\MyError($method.'()这个方法不存在');
			}
			return $_controller->$method();
		}
	}

	/**
	 * M方法实例化模型
	 * @param name 模型名称
	 * @author Colin <15070091894@163.com>
	 */
	function M($name = null){
		if(empty($name)){
			return MyClass\libs\ObjFactory::CreateSystemModel();
		}else {
			return MyClass\libs\ObjFactory::CreateSystemModel($name);
		}
	}
	
	/**
	 * D方法实例化数据库模型
	 * @param name 模型名称
	 * @param method 模型方法
	 * @author Colin <15070091894@163.com>
	 */
	function D($name , $method = null){
		if(!$name){
			ShowMessage('D方法必须传递一个值');
		}
		$obj = \MyClass\libs\ObjFactory::CreateModel($name);
		if(empty($method)){
			return $obj;
		}else{
			return $obj->$method();
		}	
	}
	
	/**
	 * E方法对错误进行提醒
	 * @param message 地址
	 * @author Colin <15070091894@163.com>
	 */
	function E($message){
		throw new \MyClass\libs\MyError($message);
	}
	
	/**
	 * 跳转方法
	 * @param url 地址
	 * @author Colin <15070091894@163.com>
	 */
	function U($url){
		$subject = \MyClass\libs\Url::getCurrentUrl(false , true);
		$newurl = '/'.ltrim($url , '/');
		return $newurl;
	}

	/**
	 * 打印输出函数
	 * @param array 要被打印的数据
	 * @author Colin <15070091894@163.com>
	 */
	function dump($array){
		header('Content-type:text/html;charset="UTF-8"');
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}

	/**
	 * 设置session
	 * @param name session的名称
	 * @param value session要保存的值
	 * @author Colin <15070091894@163.com>
	 */
	function session($name = '' , $value = ''){
		if(Config('SESSION_START')){
			//session名称为空 返回所有
			if($name == ''){
				return $_SESSION;
			}else if($name == 'null'){					//清空session
				return session_destroy();
			}else if(!empty($name) && $value == ''){	//session值为空
				return $_SESSION["$name"];
			}else if(!empty($name) && !empty($value)){	//session名称和值都不为空
				$_SESSION["$name"] = $value;
			}else if(!empty($name) && $value == 'null'){
				unset($_SESSION["$name"]);
			}
		}else{
			throw new \MyClass\libs\MyError('session未打开！请进入config.php打开');
		}
	}

	/**
	 * 接收post和get值函数
	 * @param type 要获取的POST或GET
	 * @param formname 要获取的POST或type的表单名
	 * @param function 要使用的函数
	 * @author Colin <15070091894@163.com>
	 */
	function values($type , $formname = null , $function = 'trim'){
		switch ($type) {
			case 'get':
				$string = isset($_GET[$formname]) ? $_GET[$formname] : '';
				break;
			case 'get.':
				$string = $_GET;
				break;
			case 'post':
				$string = isset($_POST[$formname]) ? $_POST[$formname] : '';
				break;
			case 'post.':
				$string = $_POST;
				break;
			case 'files':
				$string = isset($_FILES[$formname]) ? $_FILES[$formname] : '';
				break;
			case 'files.':
				$string = $_FILES;
				break;
			case 'request':
				$string = $_REQUEST[$formname];
				break;
		}
		if($function == null){
			return $string;
		}
		$function = explode(',', $function);
		$processing = '';
		if(is_array($string)){
			foreach ($string as $key => $value) {
				foreach ($function as $k => $v) {
					$value = $v($value);
				}
				$processing[$key] = $value;
			}
		}else if(is_string($string)){
			foreach ($function as $key => $value) {
				$processing = $value($string);
			}
		}
		return $processing;
	}

	/**
	 * 缓存管理
	 * @param name 存储的名称
	 * @param value 存储的value
	 * @author Colin <15070091894@163.com>
	 */
	function S($name , $value = null){
		//实例化一个缓存句柄
		$cache = \MyClass\libs\ObjFactory::CreateCache();
		if($name == 'null'){
			$cache->clearCache();
		}else if(!empty($name) && $value == 'null'){
			//移除缓存
			$cache->removeCache($name);
		}else if(!empty($name) && !empty($value)){
			//生成缓存
			$cache->outputFileName($name , $value);
		}else if(!empty($name) && empty($value)){
			//读取缓存
			$cache->readCache($name);
		}
	}

	/**
	 * 系统配置
	 * @param name 存储的名称
	 * @param value 存储的value
	 * @author Colin <15070091894@163.com>
	 */
	function Config($name = null , $value = ''){
		static $config = array();
		if(empty($name)){
			return $config;
		}else if(is_array($name)){
			//设置
			$config = $name;
		}else if(is_string($name) && $value == ''){
			return $config[$name];
		}else if(is_string($name) && !empty($value)){
			$config[$name] = $value;
		}
	}

	/**
	 * 获取当前地址
	 * @author Colin <15070091894@163.com>
	 */
	function getCurrentUrl(){
		return \MyClass\libs\Url::getCurrentUrl(true);
	}
?>