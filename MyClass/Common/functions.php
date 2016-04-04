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
	function ShowMessage($message){
		header('Content-Type:text/html;charset=UTF-8');
		$info = '<div style="width:400px;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;">';
		$info .= '<dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;">';
		$info .= '<dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt>';
		$info .= '<dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0;word-break:break-all;">' . $message . '</dd>';
		$info .= '<dd style="padding:0px;margin:0px;">';
		$info .= '<a href="javascript:void(0);" style="font-size:15px;color:#181884;width:100%;text-align:center;display:block;" id="back">';
		$info .= '[ 返回 ]</a></dd></dd></dl>';
		$info .= '</div><script>';
		$info .= 'var back = document.getElementById("back");
				back.onclick = function(){
					window.history.back();
				}';
		$info .= '</script>';
		die($info);
	}

	/**
	 * 返回json数据
	 * @param array 要返回的数据
	 * @author Colin <15070091894@163.com>
	 */
	function ajaxReturn($array = null){
		echo json_encode($array);
		exit;
	}

	/**
	 * C方法实例化控制器
	 * @param name 模型名称
	 * @author Colin <15070091894@163.com>
	 */
	function C($module , $name , $method = null , $param = null){
		//默认模块
		if(empty($module)) $module = Config('DEFAULT_MODULE');
		//默认控制器
		if(empty($name)) $name = Config('DEFAULT_CONTROLLER');
		//默认方法
		if(empty($method)) $method = Config('DEFAULT_METHOD');
		//文件路径
		$filepath = APP_PATH . '/' . $module .'/Controller/'.$name.Config('DEFAULT_CONTROLLER_SUFFIX').Config('DEFAULT_CLASS_SUFFIX');
		//如果不存在
		if(!file_exists($filepath)){
			throw new \MyClass\libs\MyError($name.'控制器不存在！');
		}
		//引入命名空间以及目录
		$name = require_module($name , 'CONTROLLER' , null , $module);
		//创建控制器
		$controller = \MyClass\libs\ObjFactory::CreateController($name);
		if(!method_exists($controller , $method)){
			throw new \MyClass\libs\MyError($method.'()这个方法不存在');
		}
		//反射
		$ReflectionMethod = new \ReflectionMethod($controller , $method);
		$method_params = $ReflectionMethod->getParameters($method);
		//处理参数返回
		$param = array_filter($param);
		if(!empty($param)){
			if(!empty($method_params)){
				foreach ($method_params as $key => $value) {
					$var[$value->name] = $param[$value->name];
				}
				return $ReflectionMethod->invokeArgs($controller , array_filter($var));
			}
		}
		return $controller->$method(eval($string));
	}

	/**
	 * M方法实例化模型
	 * @param name 模型名称
	 * @author Colin <15070091894@163.com>
	 */
	function M($name = null){
		if(empty($name)){
			//创建系统模型   不带表名
			return MyClass\libs\ObjFactory::CreateSystemModel();
		}else {
			//创建系统模型   带表名
			return MyClass\libs\ObjFactory::CreateSystemModel($name);
		}
	}
	
	/**
	 * D方法实例化数据库模型
	 * @param name 模型名称
	 * @param method 模型方法
	 * @author Colin <15070091894@163.com>
	 */
	function D($name = null , $method = null){
		if(empty($name)){
			ShowMessage('D方法必须传递一个值');
		}
		//查找分组
		$explode = explode('/', $name);
		if(isset($explode[1]) && !empty($explode[1])){
			//引入命名空间以及目录
			$tables = $explode[1];
			$name = require_module($explode[1] , 'MODEL' , $explode[0]);
		}else{
			$tables = $name;
			//引入命名空间以及目录
			$name = require_module($name , 'MODEL');
		}
		//文件目录
		$filepath = APP_PATH.'/'.$name.Config('DEFAULT_MODEL_SUFFIX').Config('DEFAULT_CLASS_SUFFIX');
		//文件不存在
		if(!file_exists(str_replace('\\', '/', $filepath))){
			//创建系统模型
			$obj = \MyClass\libs\ObjFactory::CreateSystemModel($tables);
		}else{
			//创建模型 带表名
			$obj = \MyClass\libs\ObjFactory::CreateModel($name);
		}
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
	function U($url , $param = null){
		$subject = \MyClass\libs\Url::getCurrentUrl(false , true);
		$path = explode('/', $subject['path']);
		$is_index = array_filter($path);
		$oldurl = empty($is_index) ? '/index.php' : '/'.$is_index[1];
		//array_shift($path);
		// $pars = '';
		// if(!empty($param)){
		// 	if(is_array($param)){
		// 		foreach ($param as $key => $value) {
		// 			$pars .= "/$key/$value";
		// 		}
		// 	}else if(is_string($param)){
		// 		$pars = $param;
		// 	}
		// }
		//list($oldurl) = $path;
		//$pars = $pars ? $pars : '';
		//$text = '/'.$oldurl.'/'.ltrim($url , '/').$pars;
		//$text = '/'.ltrim($url , '/').$pars;
		//dump(array_filter(array_unique(explode('/',$text))));
		$filter = $oldurl.'/'.implode('/' , array_unique(explode('/',ltrim($url , '/'))));
		//return $filter.$pars;
		return $filter;
	}

	/**
	 * 获取模块名生成模块路径
	 * @param name 模型名称
	 * @param type 类型
	 * @param path 自定义引入路径
	 * @param modules 模块
	 * @author Colin <15070091894@163.com>
	 */
	function require_module($name = null , $type = null , $module = null , $modules = null){
		$layer = Config('DEFAULT_'.$type.'_LAYER');
		if(!$modules){
			$modules = Config('DEFAULT_MODULE');
		}
		$path = $modules.'\\'.$layer.'\\'.$name;
		if($module){
			$path = $module.'\\'.$layer.'\\'.$name;
		}
		return $path;
	}

	/**
	 * 引入常规文件    没有返回值
	 * @param path 文件路径
	 * @param modules 加载的模块
	 * @author Colin <15070091894@163.com>
	 */
	function require_file($path , $modules = ''){
		$content = '';
		if(is_array($path)){
			foreach ($path as $key => $value) {
				if(file_exists($modules.$value)){
					$content[] = require_once $modules.$value;
				}
			}
			return $content;
		}else if(is_string($path)){
			if(file_exists($path)){
				return require_once $path;
			}
		}
	}

	/**
	 * 合并配置值
	 * @param name1 第一个需合并的数组
	 * @param name2 第二个需合并的数组
	 * @param name3 第三个需合并的数组
	 * @author Colin <15070091894@163.com>
	 */
	function replace_recursive_params($name1 , $name2 = null , $name3 = null){
		$var1 = require_file($name1);
		$var2 = require_file($name2);
		$var3 = require_file($name3);
		if(empty($var2) && empty($var3)){
			return $var1;
		}else if(empty($var3)){
			return array_replace_recursive($var1 , $var2);
		}else if(!empty($var3)){
			$merge = array_replace_recursive($var1 , $var3);
			return array_replace_recursive($merge , $var2);
		}else{
			return $var1;
		}
	}

	/**
	 * 创建文件夹 支持批量创建
	 * @param param 文件夹数组
	 * @author Colin <15070091894@163.com>
	 */
	function outdir($param){
		if(is_array($param)){
			foreach ($param as $key => $value) {
				if(!is_dir($value)){
					mkdir($value , 0777);
				}
			}
		}else if(is_string($param)){
			if(!is_dir($param)) mkdir($param , 0777);
		}
	}

	/**
	 * 打印输出函数
	 * @param array 要被打印的数据
	 * @author Colin <15070091894@163.com>
	 */
	function dump($array){
		header('Content-type:text/html;charset="UTF-8"');
		echo '<pre>';
		var_dump($array);
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
				return $_SESSION["$name"] !== 'null' ? $_SESSION["$name"] : null;
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
			return $value;
		}else if(!empty($name) && empty($value)){
			//读取缓存
			return $cache->readCache($name);
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

	/**
	 * 获取站点地址
	 * @author Colin <15070091894@163.com>
	 */
	function getSiteUrl($scame = true){
		return \MyClass\libs\Url::getSiteUrl($scame);
	}


	/**
	 * 设置Public地址
	 * @param  public public目录的相对地址 可以直接填写Public
	 * @author Colin <15070091894@163.com>
	 */
	function setPublicUrl($public){
		return getSiteUrl().$public;
	}

	/**
	 * 设置URL地址
	 * @param  url url目录的相对地址
	 * @author Colin <15070091894@163.com>
	 */
	function setUrl($url){
		return setPublicUrl($url);
	}

	/**
	 * 获取当前时间
	 * @param prc 时间区域
	 * @author Colin <15070091894@163.com>
	 */
	function getTime($prc = null){
		return \MyClass\libs\Date::getDate($prc);
	}

	/**
	 * 第三方类库调用
	 * @param name 第三方类库名称
	 * @author Colin <15070091894@163.com>
	 */
	function library($name = null){
		list($filedir , $filename) = explode('/' , $name);
		//把@替换成.
		$filename = str_replace('@' , '.' , $filename);
		$path = Library.'/'.$filedir.'/'.$filename.'.php';
		if(!file_exists($path)){
			E('文件不存在'.$name);	
		}
		require_file($path);
	}
?>