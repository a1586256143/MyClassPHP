<?php
	/*
		Author : Colin,
		Creation time : 2015-7-31下午06:41:10
		FileType : MyClassPHP引导类
		FileName : MyClass.php
	*/

	//MyClass目录
	define('MyClass' , str_replace('\\' , '/' , dirname(__FILE__)));

	//根目录
	define('ROOT_PATH' , substr(MyClass , 0 , -7));

	//如果不存在APP_NAME
	if(!defined('APP_NAME')){
		define('APP_NAME' , './Application');
	}

	//APP路径
	define('APP_PATH' , substr(MyClass , 0 , -8).ltrim(APP_NAME , '.'));
	
	//核心文件
	define('Core' , MyClass.'/libs/');

	//第三方类库文件目录
	define('Library' , MyClass.'/Library');

	//定义运行目录
	define('RunTime' , APP_PATH.'/RunTime');

	//公共文件目录
	define('Common' , APP_NAME.'/Common');

	//定义版本信息
	define('VERSION' , '2.2');

	//引入MyClass核心文件
	require_once Core.'MyClass.php';
	MyClass\libs\MyClass::run();
?>