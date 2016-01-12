<?php
	/*
		Author : Colin,
		Creation time : 2015-7-31下午06:41:10
		FileType : 
		FileName : 
	*/
	
	define('MyClass',dirname(__FILE__));
	
	if(defined(APP_PATH)){
		defined('APP_PATH','./Application');
	}
	
	//运行时文件
	define('RunTime',APP_PATH.'/RunTime');
	
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