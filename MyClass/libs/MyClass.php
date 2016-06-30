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
				$ClassName = preg_replace($patten , ltrim(APP_NAME , './').'\\'.$value, $ClassName);
			}
		}
		if(preg_match("/\\\\/" , $ClassName)){
			//是否为命名空间加载
			$ClassName = preg_replace("/\\\\/", "/", $ClassName);
			
			require_file(ROOT_PATH.$ClassName.'.class.php');
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
		self::userConfig();
	}

	/**
	 * 加载用户配置文件
	 * @author Colin <15070091894@163.com>
	 */
	public static function userConfig(){
		require_once MyClass . '/Common/functions.php';
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
		$route->CheckRoute();
	}
	
	/**
	 * 目录结构方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function Dir(){
		self::loadFunction();
		//加载常量
		self::ReqConst();
		$view = APP_PATH . '/' . Config('DEFAULT_MODULE') .Config('TPL_DIR');
		$cache = ltrim(Config('CACHE_DIR') , './');
		$dir = array(APP_PATH , Module , RunTime , ControllerDIR , ModelDIR , ConfDIR , CommonDIR , $view , $cache , Common);
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
		$dirnames = array('ControllerDIR' => Module.'/Controller' , 'ModelDIR' => Module.'/Model' , 'ConfDIR' => Module.'/Conf' , 'CommonDIR' => Module.'/Common');
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
	    	$dir = explode(',',Config('AUTO_REQUIRE_FILE'));
	    	require_file($dir , APP_PATH);
	    }
	}

	/**
	 * 生成默认的配置文件、控制器
	 * @author Colin <15070091894@163.com>
	 */
	public static function outDefaultFile(){
		//生成默认的配置文件
		if(!file_exists(ConfDIR.'/config.php')){
            file_put_contents(ConfDIR.'/config.php',View::createConfig());
        }
        //生成默认的配置文件
		if(!file_exists(ConfDIR.'/template.php')){
            file_put_contents(ConfDIR.'/template.php',View::createTemplate());
        }
		//生成默认的控制器
		if(!file_exists(ControllerDIR.'/IndexController.class.php')){
			file_put_contents(ControllerDIR.'/IndexController.class.php',View::createIndex());
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
}
?>