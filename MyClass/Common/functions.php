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
		die('<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>');
	}

	/**
	 * C方法实例化控制器
	 * @param name 模型名称
	 * @author Colin <15070091894@163.com>
	 */
	function C($name , $method = null){
		if(!file_exists(APP_PATH.'/Controller/'.$name.'Controller.class.php')){
			ShowMessage($name.'控制器不存在！');
		}
		if(empty($method)){
			$_controller = \MyClass\libs\ObjFactory::CreateController($name);
			$_controller->index();
		}else{
			$_controller = \MyClass\libs\ObjFactory::CreateController($name);

			if(!method_exists($_controller,$method)){
				ShowMessage($method.'这个方法不存在');
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
			return $obj = MyClass\libs\ObjFactory::CreateSystemModel();
		}else {
			return $obj = MyClass\libs\ObjFactory::CreateSystemModel($name);
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
		header('Content-type:text/html;charset="utf-8"');
		throw new \MyClass\libs\MyError($message);
	}
	
	/**
	 * 跳转方法
	 * @param url 地址
	 * @author Colin <15070091894@163.com>
	 */
	function U($url){
		$_subjecturl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
		$_newurl = $_subjecturl.'/'.$url;
		return $_newurl;
	}

	/**
	 * 打印输出函数
	 * @param array 要被打印的数据
	 * @author Colin <15070091894@163.com>
	 */
	function dump($array){
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
			}
			//清空session
			if($name == 'null'){
				return session_destroy();
			}
			//session值为空
			if(!empty($name) && $value == ''){
				return $_SESSION["$name"];
			}
			//session名称和值都不为空
			if(!empty($name) && !empty($value)){
				$_SESSION["$name"] = $value;
			}
			if(!empty($name) && $value == 'null'){

				unset($_SESSION["$name"]);
			}
		}else{
			E('session未打开！请进入config.php打开');
		}
	}

	/**
	 * 接收post和get值函数
	 * @param type 要获取的POST或GET
	 * @param formname 要获取的POST或type的表单名
	 * @param function 要使用的函数
	 * @author Colin <15070091894@163.com>
	 */
	function value($type , $formname = null , $function = 'trim'){
		switch ($type) {
			case 'get':
				$string = $_GET[$formname];
				break;
			case 'get.':
				$string = $_GET;
				break;
			case 'post':
				$string = $_POST[$formname];
				break;
			case 'post.':
				$string = $_POST;
				break;
		}
		$function = explode(',', $function);
		if(is_array($string)){
			foreach ($string as $key => $value) {
				foreach ($function as $k => $v) {
					$value = $v($value);
				}
				$processing[] = $value;
			}
		}elseif(is_string($string)){
			foreach ($function as $key => $value) {
				$processing = $value($string);
			}
		}
		return $processing;
	}

	/**
	 * 系统配置
	 * @param name 存储的名称
	 * @param value 存储的value
	 * @author Colin <15070091894@163.com>
	 */
	function Config($name , $value = ''){
		static $config = array();
		if(empty($name)){
			return $config;
		}
		if(is_array($name)){
			//设置
			$config = $name;
		}elseif(is_string($name) && $value == ''){
			return $config[$name];
		}elseif(is_string($name) && !empty($value)){
			$config[$name] = $value;
		}
	}
?>