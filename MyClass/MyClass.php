<?php
	/*
		Author : Colin,
		Creation time : 2015-7-31下午06:41:10
		FileType : 
		FileName : 
	*/

	//MyClass目录
	define('MyClass',dirname(__FILE__));

	//根目录
	define('ROOT_PATH',substr(MyClass , 0 , -7));

	//如果不存在APP_NAME
	if(!defined('APP_NAME')){
		define('APP_PATH' , './Application');
	}

	//APP路径
	define('APP_PATH' , substr(MyClass , 0 , -8).ltrim(APP_NAME , '.'));

	//运行时文件
	define('RunTime',APP_PATH.'/RunTime');

	//核心文件
	define('Core',MyClass.'/libs/');

	//是否开启缓存
	define('IS_CACHE' , true);
	
	//控制器目录
	define('ControllerDIR',APP_PATH.'/Controller');
	
	//模型目录
	define('ModelDIR',APP_PATH.'/Model');
	
	//配置文件目录
	define('ConfDIR',APP_PATH.'/Conf');
	
	//引入MyClass核心文件
	require_once './MyClass/libs/MyClass.php';

	MyClass\libs\MyClass::run();
?>