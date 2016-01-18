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
			$statr_time = microtime();
			//注册autoload方法
			spl_autoload_register('MyClass\\libs\\MyClass::autoload');
			//判断文件夹是否存在
			self::Dir();
			//视图初始化
			self::View();
			//初始化URL模式
			self::UrlModel();
			$end_time = microtime();
			dump($end_time - $statr_time);
		}catch (MyError $m){
			echo ($m);
		}
	}
	
	/**
	 * 自动加载
	 * @param ClassName 类名
	 * @author Colin <15070091894@163.com>
	 */
	public static function autoload($ClassName){
		if(preg_match_all("/\\\\/" , $ClassName , $match)){
			//是否为命名空间加载			
			require_once $ClassName.'.class.php';
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
	 * 常量引入方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ReqConst(){
		$const = require_once MyClass . '/Conf/config.php';
		$const1 = require_once APP_PATH . '/Conf/config.php';
		$const3 = array_replace_recursive($const , $const1);
		Config($const3);
		//解析常量方法
		self::ParConst();
	}

	/**
	 * 解析常量方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ParConst(){
	    if(Config('SESSION_START')){
	        session_start();
	    }
	}
	
	/**
	 * 目录结构方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function Dir(){
		require_once MyClass . '/Common/functions.php';
		@mkdir(APP_PATH);       	//建立App目录
		@mkdir(RunTime);            //建立运行目录
		@mkdir(ControllerDIR);   	//建立控制器目录
		@mkdir(ModelDIR);        	//建立模型目录
		@mkdir(ConfDIR);            //建立配置文件目录
		//生成默认的配置文件
		if(!file_exists(ConfDIR.'/config.php')){
            @file_put_contents(ConfDIR.'/config.php',@file_get_contents(MyClass.'/tpl/config.php'));
        }
		//生成默认的控制器
		if(!file_exists(ControllerDIR.'/IndexController.class.php')){
			@file_put_contents(ControllerDIR.'/IndexController.class.php',@file_get_contents(MyClass.'/tpl/index.php'));
		}
		//加载常量
		self::ReqConst();
		@mkdir(APP_PATH.Config('TPL_DIR'));			//创建视图文件目录
		@mkdir(APP_PATH.Config('CACHE_DATA_DIR'));	//创建缓存文件目录
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