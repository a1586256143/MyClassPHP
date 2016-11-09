<?php
/*
	Author : Colin,
	Creation time : 2015-8-1
	FileType : 函数库
	FileName : function.php
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
function C($name , $method = null , $param = null){
	//默认模块
	//if(empty($module)) $module = Config('DEFAULT_MODULE');
	//默认控制器
	if(empty($name)) $name = Config('DEFAULT_CONTROLLER');
	//默认方法
	if(empty($method)) $method = Config('DEFAULT_METHOD');
	//文件路径
	$rootpathPrint = '%s%s/';
	$filepath = sprintf($rootpathPrint , APP_PATH , Config('DEFAULT_CONTROLLER_LAYER')) . get_filename($name);
	//如果不存在
	if(!file_exists($filepath)){
		//查找空操作文件
		$empty_name = Config('EMPTY_CONTROLLER');
		$empty = get_filename(Config('EMPTY_CONTROLLER'));
		if(!file_exists($rootpath . $empty)){
			throw new \system\MyError($name . '控制器不存在！');
		}
		$name = $empty_name;
	}
	//引入命名空间以及目录
	$name = require_module($name , 'CONTROLLER' , null);
	//创建控制器
	$controller = \system\ObjFactory::CreateController($name);
	if(!method_exists($controller , $method)){
		$empty_method = Config('EMPTY_METHOD');
		if(!method_exists($controller , $empty_method)){
			throw new \system\MyError($method.'()这个方法不存在');
		}
		$method = $empty_method;
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
	return $controller->$method();
}

/**
 * M方法实例化模型
 * @param name 模型名称
 * @author Colin <15070091894@163.com>
 */
function M($name = null){
	if(empty($name)){
		//创建系统模型   不带表名
		return system\ObjFactory::CreateSystemModel();
	}else {
		//创建系统模型   带表名
		return system\ObjFactory::CreateSystemModel($name);
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
	$filepath = APP_PATH . '/' . get_filename($name);
	//文件不存在
	if(!file_exists(str_replace('\\', '/', $filepath))){
		//创建系统模型
		$obj = \system\ObjFactory::CreateSystemModel($tables);
	}else{
		//创建模型 带表名
		$obj = \system\ObjFactory::CreateModel($name);
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
	$debug = MY_DEBUG;
	//记录日志
	WriteLog($message);
	if($debug){
		throw new \system\MyError($message);
	}else{
		throw new \system\MyError(Config('ERROR_MESSAGE'));
	}
}

/**
 * 跳转方法
 * @param string $url 地址  U('/Home/Index/index') 或 U('Index/index')
 * @author Colin <15070091894@163.com>
 */
function U($url , $param = null){
	$url = ltrim(rtrim($url , '/') , '/');
	$subject = \system\Url::getCurrentUrl(false , true);
	$path = $subject['path'];
	//匹配是否是/开头，如果在/开头则访问模块
	if(strpos($url , '/') == 0){	
		preg_match("/\/([\w]+)\//" , $url , $match);
		if(!$match[0]){
			$match[0] = $modules;
		}
		//$path = preg_replace("/\/([\w]+)\//", $match[1] . '/' , $path);
		$path = $url;
	}else{
		$path = $url;
	}
	$params = http_build_query($param);
	$path = explode('/' , $path);
	$action = array_pop($path);
	$url = array_filter(array_unique(array_merge($path)));
	$filter = '/index.php?c='.implode('/' , $url) . '&a=' . $action;
	if(null != $param){
		$filter .= '&' . $params;
	}
	return $filter;
}

/**
 * 解析U函数所需的指定URL格式，例如 array('id' => 1 , 'user' => 'admin')
 * @param  array $param 需要解析的数组格式
 * @author Colin <15070091894@163.com>
 */
function params($param = null){
	if(null == $param && !is_array($param)){
		return '';
	}
	$params = '';
	foreach($param as $key => $value){
		$params .= $key . '/' . $value . '/';
	}
	return '/' . substr($params , 0 , -1);
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
	$path = $layer.'\\'.$name;
	if($module){
		$path = $layer.'\\' . $module . '\\' .$name;
	}
	return str_replace('/' , '\\' , $path);
}

/**
 * 引入常规文件    没有返回值
 * @param path 文件路径
 * @param modules 加载的模块
 * @author Colin <15070091894@163.com>
 */
function require_file($path , $modules = '' , $return = true){
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
			if($return){
				return require_once $path;
			}else{
				require_once $path;
			}
			
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
		throw new \system\MyError('session未打开！请进入config.php打开');
	}
}

/**
 * 接收post和get值函数
 * @param type 要获取的POST或GET
 * @param formname 要获取的POST或type的表单名
 * @param function 要使用的函数
 * @param default 默认值
 * @author Colin <15070091894@163.com>
 */
function values($type , $formname = null , $function = 'trim' , $default = null){
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
	//解析函数，得到函数名
	$function = explode(',', $function);
	$processing = '';
	if(is_array($string)){
		foreach ($string as $key => $value) {
			if(is_array($value)){
				foreach ($value as $k => $v) {
					//对得到的值 使用函数处理
					foreach ($function as $fk => $fv) {
						$v = $fv($v);
						$processing[$key][$k] = $v;
					}
				}
			}else{
				//对得到的值 使用函数处理
				foreach ($function as $fk => $fv) {
					$value = $fv($value);
					$processing[$key] = $value;
				}
			}
			
			
		}
	}else if(is_string($string)){
		//对得到的值 使用函数处理
		foreach ($function as $key => $value) {
			$processing = $value($string);
		}
	}
	if(!$processing){
		//是否存在默认值。如果处理后的结果为空，则返回默认值
		$processing = $default === null ? null : $default;
	}
	return $processing;
}

/**
 * 缓存管理
 * @param name 存储的名称
 * @param value 存储的value
 * @author Colin <15070091894@163.com>
 */
function S($name , $value = null,$time=0){
	//实例化一个缓存句柄
	$cache = \system\ObjFactory::CreateCache();
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
		return $time?$cache->readCache($name,$time):$cache->readCache($name);
	}
}

/**
 * 日志
 * @author Colin <15070091894@163.com>
 */
function WriteLog($message , $logConfigName = 'LOG_NAME_FORMAT'){
	$logDir = Config('LOGDIR');
	//创建日志文件夹
	outdir($logDir);
	//日志文件名格式
	$logName = Config($logConfigName);
	$logName = date($logName , time());
	//日志后缀
	$logSuffix = Config('LOG_SUFFIX');
	$file = new \system\File();
	$url = getCurrentUrl();
	$logPath = $logDir . '/' . $logName . $logSuffix;
	$data = date('[ Y-m-d H:i:s ]') . " $url\r\n$message\r\n";
	$status = $file->AppendFile($logPath , $data , false);
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
		$config = array_merge($config , $name);
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
	return \system\Url::getCurrentUrl(true);
}

/**
 * 获取站点地址
 * @author Colin <15070091894@163.com>
 */
function getSiteUrl($scame = true){
	return \system\Url::getSiteUrl($scame);
}


/**
 * 设置Public地址
 * @param  public public目录的相对地址 可以直接填写Public
 * @author Colin <15070091894@163.com>
 */
function setPublicUrl($public){
	return getSiteUrl(false).$public;
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
	return \system\Date::getDate($prc);
}

/**
 * 第三方类库调用
 * @param name 第三方类库名称
 * @author Colin <15070091894@163.com>
 */
function library($name = null){
	list($filedir , $filename) = explode('/' , $name);
	if($filename == '*'){
		$path = Library . '/' . $filedir;
		$file = new \system\File();
		//获取目录下的所有文件
		$allfile = $file->getDirAllFile($path , 'php');
		//如果目录空 ，则返回null
		if(!$allfile) return;
		foreach($allfile as $key => $value){
			//如果是dir,则需要再次遍历，加载
			if(is_array($value)){
				foreach ($value as $k => $v) {
					require_file($v , null , false);
				}
			}else{
				require_file($value , null , false);
			}
		}
	}else{
		//把@替换成.
		$filename = str_replace('@' , '.' , $filename);
		$path = Library.'/'.$filedir.'/'.$filename.'.php';
		if(!file_exists($path)){
			E('文件不存在'.$name);	
		}
		require_file($path);
	}
}

/**
 * 处理Model类的 array_filter 过滤 0 操作
 * @author Colin <15070091894@163.com>
 */
function myclass_filter($array = array()){
	foreach ($array as $key => $value) {
		if($value === null || $value === ''){
			continue;
		}
		$result[$key] = $value;
	}
	return $result;
}

/**
 * 验证表单安全码
 * @param string $secur_number 表单提交的安全码
 * @author Colin <15070091894@163.com>
 */
function checkSecurity($secur_number = null){
	$system = session('secur_number');
	if($secur_number == $system){
		session('secur_number' , 'null');
		return true;
	}
	return false;
}

/**
 * 获取类名称
 * @return [type] [description]
 */
function get_filename($name){
	return $name . Config('DEFAULT_CLASS_SUFFIX');
}
?>