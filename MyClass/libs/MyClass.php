<?php
/*
	Author : Colin,
	Creation time : 2015-8-1
	FileType : MyClassPHP引导类
	FileName : MyClass.php
*/
namespace MyClass\libs;
class MyClass{

	/**
	 * 运行方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function run(){
		try {
			//加载配置文件
			self::loadConfig();
			//注册autoload方法
       		spl_autoload_register('MyClass\\libs\\MyClass::autoload');
       		//收集错误
       		MyError::error_traceassstring();
			//判断文件夹是否存在
			self::Dir();
			//解析表单方法
			self::formMethod();
			//视图初始化
			self::View();
			//初始化URL模式
			self::UrlModel();
		}catch (MyError $m){
			die($m);
		}
	}
	
	/**
	 * 自动加载
	 * @param ClassName 类名
	 * @author Colin <15070091894@163.com>
	 */
	public static function autoload($ClassName){
		$getModule = values('get' , Config('DEFAULT_MODULE_VAR'));
		$getModule = $getModule ? $getModule : Config('DEFAULT_MODULE');
		//处理多模块文件载入问题
		$extra_module = Config('EXTRA_MODULE');
		array_push($extra_module , $getModule);
		$extra_module = array_unique($extra_module);
		foreach ($extra_module as $key => $value) {
			$patten = "/^$value/";
			if(preg_match($patten , $ClassName)){
				$ClassName = preg_replace($patten , ltrim(APP_NAME , './') . '\\'  .$value, $ClassName);
			}
		}
		if(preg_match("/\\\\/" , $ClassName)){
			//是否为命名空间加载
			$ClassName = preg_replace("/\\\\/", "/", $ClassName);

			require_file(ROOT_PATH . $ClassName . Config('DEFAULT_CLASS_SUFFIX'));
		}
	}

	/**
	 * 加载配置文件
	 * @author Colin <15070091894@163.com>
	 */
	public static function loadConfig(){
		//DEBUG
		if(!defined('MY_DEBUG')) define('MY_DEBUG' , true);
		require_once MyClass . '/Common/functions.php';
		//合并config文件内容
		$merge = replace_recursive_params(MyClass . '/Conf/config.php' , Common . '/Conf/config.php');
		//加入配置文件
		Config($merge);
		//加载当前模块下的配置文件
		$modules = defined('CURRENT_MODULE') ? CURRENT_MODULE : Config('DEFAULT_MODULE');
		$app = APP_PATH . '/' . $modules . '/Conf/config.php';
		if(file_exists($app)){
			$config = require_file($app);
			$merge = array_replace_recursive(Config() , $config);
			Config($merge);
		}
	}

	/**
	 * URL模式
	 * @author Colin <15070091894@163.com>
	 */
	public static function UrlModel(){
		$route = new Route();
		$route->startRoute();
	}
	
	/**
	 * 目录结构方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function Dir(){
		//加载常量
		self::ReqConst();
		self::loadFunction();
		$view = APP_PATH . '/' . Config('DEFAULT_MODULE') .Config('TPL_DIR');
		$cache = ltrim(Config('CACHE_DIR') , './');
		$dir = array(
					APP_PATH , 
					Module , 
					RunTime , 
					ControllerDIR , 
					ModelDIR , 
					ConfDIR , 
					CommonDIR , 
					$view , 
					$cache , 
					Common
				);
		foreach ($dir as $key => $value) {
			//创建文件夹
			outdir($value);
		}
		//生成默认的文件
		self::outDefaultFile();
		//设置默认时间格式
		Date::set_timezone();
	}

	/**
	 * 加载函数库
	 * @author Colin <15070091894@163.com>
	 */
	public static function loadFunction(){
		require_once MyClass . '/Common/functions.php';
		$app = array(CommonDIR.'/functions.php' , Common.'/Common/functions.php');
		require_file($app);
	}

	/**
	 * 设置默认工作空间目录结构
	 * @author Colin <15070091894@163.com>
	 */
	public static function setWorks(){
		$module = defined('CURRENT_MODULE') ? CURRENT_MODULE : Config('DEFAULT_MODULE');
		define('Module' , APP_PATH . '/' . $module);
		$dirnames = array(
						'ControllerDIR' => Module . '/Controller' , 
						'ModelDIR' 		=> Module . '/Model' , 
						'ConfDIR' 		=> Module . '/Conf' , 
						'CommonDIR' 	=> Module . '/Common'
					);
		foreach ($dirnames as $key => $value) {
			if(!defined($key)){
				define($key , $value);
			}
		}
	}

	/**
	 * 常量引入方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ReqConst(){
		//默认模块
		self::setWorks();
		//解析常量方法
		self::ParConst();
	}

	/**
	 * 解析常量方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ParConst(){
		//解析session
	    if(Config('SESSION_START')){
	        session_start();
	    }
	    //解析自动引入
	    if(Config('AUTO_REQUIRE')){
	    	//自动引入
	    	$auto_require_file = Config('AUTO_REQUIRE_FILE');
	    	if(empty($auto_require_file)){
	    		return;
	    	}
	    	$dir = explode(',' , Config('AUTO_REQUIRE_FILE'));
	    	require_file($dir , APP_PATH);
	    }
	}

	/**
	 * 生成默认的配置文件、控制器
	 * @author Colin <15070091894@163.com>
	 */
	public static function outDefaultFile(){
		$data = array(
					array(ConfDIR . '/config.php' , View::createConfig()) , 
					array(ConfDIR . '/template.php' , View::createTemplate()) , 
					array(ControllerDIR . '/' . Config('DEFAULT_CONTROLLER') . Config('DEFAULT_CLASS_SUFFIX') , View::createIndex(Config('DEFAULT_CONTROLLER')))
				);
		foreach ($data as $key => $value) {
			if(!file_exists($value[0])){
				file_put_contents($value[0] , $value[1]);
			}
		}
	}

	/**
	 * 视图初始化
	 * @author Colin <15070091894@163.com>
	 */
	public static function View(){
		//初始化视图工厂
		View::init(Config('TPL_MODEL') , Config('TPL_CONFIG'));
	}

	/**
	 * 设定常量，判断各种表单方式
	 * @author Colin <15070091894@163.com>
	 */
	public static function formMethod(){
		$request_method = $_SERVER["REQUEST_METHOD"];
		$request_method == 'POST' ? define('POST' , true) : define('POST' , false);
		$request_method == 'GET' ? define('GET' , true) : define('GET' , false);
	}
}
?>