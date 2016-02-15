<?php
/*
	Author : Colin,
	Creation time : 2015-8-1
	FileType : MyClassPHP引导类
	FileName : MyClass.php
*/
namespace MyClass\libs;

class MyClass{
	//静态成员
	public static $_obj;
	
	/**
	 * 运行方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function run(){
		try {
			error_reporting ( E_ERROR  |  E_PARSE );
			//注册autoload方法
			spl_autoload_register('MyClass\\libs\\MyClass::autoload');
			//register_shutdown_function('MyClass\\libs\\MyError::shutdown_function');
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
		require_once MyClass . '/Common/functions.php';
		if(preg_match_all("/\\\\/" , $ClassName , $match)){
			//是否为命名空间加载
			$ClassName = preg_replace("/\\\\/", "/", $ClassName);
			require_file(ROOT_PATH.$ClassName.'.class.php');
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
		$dir = array(APP_PATH , RunTime , ControllerDIR , ModelDIR , ConfDIR , CommonDIR , APP_PATH.Config('TPL_DIR') , APP_PATH.Config('CACHE_DIR'));
		foreach ($dir as $key => $value) {
			//创建文件夹
			outdir($value);
		}

		//生成默认的文件
		self::outDefaultFile();
	}

	/**
	 * 加载函数库
	 * @author Colin <15070091894@163.com>
	 */
	public static function loadFunction(){
		require_once MyClass . '/Common/functions.php';
		$app = APP_PATH.'/Common/functions.php';
		require_file($app);
	}

	/**
	 * 常量引入方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ReqConst(){
		//合并config文件内容
		$merge = replace_recursive_params(MyClass . '/Conf/config.php' , APP_PATH . '/Conf/config.php' , Common . '/Conf/config.php');
		//加入配置文件
		Config($merge);
		if(file_exists(APP_PATH.'/Conf/template.php')){
    		require_once APP_PATH.'/Conf/template.php';
    	}else{
    		//加载模板常量库
			require_file(MyClass.'/Conf/template.php');	
    	}
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
		if(Config('TPL_MODEL') == 'tpl'){
			View::init('tpl');
		}
	}
}
?>