<?php
/*
	Author : Colin,
	Creation time : 2015-8-1
	FileType : 配置文件
	FileName : config.php
*/
return array(
	//数据库配置信息
	'DB_TYPE'			=>	'mysqli',				//数据库类型(mysql,mysqli,pdo)
	'DB_HOST'			=>	'localhost',			//主机地址
	'DB_USER'			=>	'',						//用户名
	'DB_PASS'			=>	'',						//密码
	'DB_TABS'			=>	'',						//数据库名称
	'DB_PREFIX'			=>	'',						//数据表前缀
	'DB_CODE'			=>	'UTF8',					//数据库编码
	'DB_PORT'			=>	'3306' , 				//数据库端口

	//自动引入(Noice 自动引入会很消耗资源，请谨慎使用)
	'AUTO_REQUIRE'      => false,                   //自动引入
	'AUTO_REQUIRE_FILE' => '',                      //自动引入文件,号分割多个文件 例如 /Conf/template.php

	//URL设置
	'URL_MODEL'			=>	2 , 					//URL模式，包含两种  PATHINFO模式，普通模式

	//目录设置
	'PUBLIC_DIR'		=>	ROOT_PATH . '/Public',	//公共文件地址

	//session设置
	'SESSION_START'		=>	1 ,            			//开启session

	//控制器设置
	'DEFAULT_CONTROLLER_LAYER'  =>	'Controller',   //默认控制器目录名
	'DEFAULT_CONTROLLER'		=>	'Index', 		//默认控制器
	'DEFAULT_MODULE'			=>	'Home', 		//默认模块
	'DEFAULT_METHOD'			=>	'index', 		//默认方法
	'DEFAULT_CLASS_SUFFIX'		=>	'.class.php', 	//默认类文件后缀
	'DEFAULT_CONTROLLER_VAR'	=>	'c' , 			//默认控制器变量
	'DEFAULT_METHOD_VAR' 		=>	'a' , 			//默认方法变量
	'DEFAULT_MODULE_VAR' 		=>	'm' , 			//默认模块变量
	'EXTRA_MODULE'				=>	array('Common') , //其他模块,用于扩展功能
	'EMPTY_CONTROLLER'			=>	'Empty' , 		//找不到控制器时，自动重定向至该控制器
	'EMPTY_METHOD'				=>	'_empty' , 		//找不到方法时，自动执行该方法

	//模型设置
	'DEFAULT_MODEL_LAYER'      	=>	'Model',         //默认模型目录名

	//模板引擎设置
	'TPL_MODEL'			=>	'tpl',					//模板引擎
	'TPL_TYPE'			=>	'.html',				//模板类型
	'TPL_DIR'			=>	'/View/',				//模板文件存放目录
	'TPL_C_DIR'			=>	RunTime . '/Template_c/',	//编译文件存放目录
	'TPL_CACHE'         =>  RunTime . '/Template_cache/',      //编译文件文件目录
	'TPL_CONFIG'		=>	array('template_dir' => 'TPL_DIR' , 'compile_dir' => 'TPL_C_DIR' , 'cache_dir' => 'TPL_CACHE'),					//模板配置

	//缓存设置
	'CACHE_DIR'     	=>	RunTime . '/Cache/', //缓存文件夹
	'CACHE_DATA_DIR'    =>  RunTime . '/Cache/tmp/', //S缓存文件存放目录
	'CACHE_OUT_PREFIX'	=>	'tmp_',   				//缓存文件名生成规则
	'CACHE_OUT_SUFFIX'	=>	'.json',   				//缓存存储后缀

	//日志设置
	'LOGDIR'			=> RunTime . '/Log' , 		//日志文件夹
	'LOG_NAME_FORMAT'	=> 'Ymd' , 					//日志名称格式，使用date() 参数
	'LOG_SQL_FORMAT'	=> 'SQLYmd' , 					//SQL日志名称格式，使用date() 参数
	'LOG_SUFFIX'		=> '.txt' , 				//日志后缀

	//上传配置
	'UPLOAD_DIR'		=>	'./Upload',				//上传文件的目录
	'UPLOAD_TYPE'       =>  'image/jpg,image/jpeg,image/png,image/gif',			//上传文件类型
	'UPLOAD_MAXSIZE'    =>  2097152,                   //上传文件大小
	'UPLOAD_RANDNAME'	=>  true,                   	//设置是否随机文件名

	//验证码配置
	'CODE_CHARSET'		=>	'abcdefghkmnprstuvwxyz23456789',//验证码随机因子
	'CODE_LENGTH'		=>	4,								//验证码长度
	'CODE_WIDTH'		=>	130,								//验证码宽度
	'CODE_HEIGHT'		=>	50,								//验证码高度
	'CODE_FONTSIZE'		=>	20,								//验证码字体大小
	'CODE_FONTPATH'		=>	MyClass . '/libs/include/elephant.ttf',//验证码字体文件存放路径

	//时间配置
	'DATE_DEFAULT_TIMEZONE' => 'PRC',				//默认时区

	//权限配置
	'AUTH_OTHER'        => false,                   //是否验证其他不在规则表方法

	//错误处理
	'ERROR_MESSAGE' 	=> '500，此网站可能正在维护~~~' , //当MY_DEBUG关闭，网页错误时提示信息
);
?>