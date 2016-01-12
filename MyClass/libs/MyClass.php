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
			//注册autoload方法
			spl_autoload_register('MyClass\\libs\\MyClass::autoload');
			//加载常亮函数
			self::ReqConst();
			//判断文件夹是否存在
			self::Dir();
			//解析常量方法
			self::ParConst();
			//视图初始化
			self::View();
			//初始化URL模式
			self::UrlModel();
		}catch (MyError $m){
			E($m);
		}
	}
	
	/**
	 * 自动加载
	 * @param ClassName 类名
	 * @author Colin <15070091894@163.com>
	 */
	public static function autoload($ClassName){
        $patten = "/\//";
        $patten2 = "/\\\\/";
        if(preg_match_all($patten2 , $ClassName , $match)){
            $ClassName = preg_replace($patten2 , '/' , $ClassName);
        }
		if(substr($ClassName , -5) == 'Model'){
			if(preg_match_all($patten , $ClassName)){
				//匹配到了\号  就从libs下加载文件
				require_once $ClassName.'.class.php';
			}else{
				$_name = substr($ClassName , 0 , -5);
				//没有匹配到，判断是否为空 为空则是libs下的model  否则是 APP_PATH下的Model
				empty($_name) ? require_once $ClassName.'.class.php': require_once APP_PATH.'/Model/'.$ClassName.'.class.php';
			}
		}else if(substr($ClassName , -10) == 'Controller'){
			//判断截取后的字符是否为空，如果为空就是libs\下的controller
			//判断是否有/  如果有那么代表是命名空间
			if(preg_match_all($patten , $ClassName)){
				//匹配到了\号  就从libs下加载文件
				require_once $ClassName.'.class.php';
			}else{
				$_name = substr($ClassName , 0 , -10);
				//没有匹配到，判断是否为空 为空则是libs下的controller  否则是 APP_PATH下的controller
				empty($_name) ? require_once $ClassName.'.class.php': require_once APP_PATH.'/Controller/'.$ClassName.'.class.php';
			}

		}else{
			//如果$_classname不满足以上两个条件，那么加载libs下的文件
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
	 * 常亮引入方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ReqConst(){
		require_once MyClass . '/Common/functions.php';
		$const = require_once MyClass . '/Conf/config.php';
		$const1 = require_once APP_PATH . '/Conf/config.php';
		$const3 = array_replace_recursive($const , $const1);
		Config($const3);
	}

	/**
	 * 解析常量方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function ParConst(){
	    if(Config('SESSION_START') != 0){
	        session_start();
	    }
	}
	
	/**
	 * 目录结构方法
	 * @author Colin <15070091894@163.com>
	 */
	public static function Dir(){
		@mkdir(APP_PATH);        //建立App目录
		@mkdir(RunTime);            //建立运行目录
		@mkdir(ControllerDIR);   //建立控制器目录
		@mkdir(ModelDIR);        //建立模型目录
		@mkdir(ConfDIR);            //建立配置文件目录
		@mkdir(APP_PATH.Config('TPL_DIR'));//建立视图文件目录

		//生成默认的配置文件
		if(!file_exists(ConfDIR.'/config.php')){
            @file_put_contents(ConfDIR.'/config.php',@file_get_contents(MyClass.'/tpl/config.php'));
        }
		//生成默认的控制器
		if(!file_exists(ControllerDIR.'/IndexController.class.php')){
			@file_put_contents(ControllerDIR.'/IndexController.class.php',@file_get_contents(MyClass.'/tpl/index.php'));
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