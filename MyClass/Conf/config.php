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
	
    //URL设置
	'URL_MODEL'			=>	2,						//URL模式

	//目录设置
	'PUBLIC_DIR'		=>	'/Public',				//公共文件地址
	'LAYOUT_DIR'		=>	'/View/Layout',			//视图公共模块

	//session设置
    'SESSION_START'		=>	1,            			//开启session

    //控制器设置
    'DEFAULT_CONTROLLER'=>	'Index', 				//默认控制器
    'DEFAULT_MODULE'	=>	'Home', 				//默认模块
    'DEFAULT_ACTION'	=>	'index', 				//默认方法
    
    //模板引擎设置
    'TPL_MODEL'			=>	'tpl',					//模板引擎
	'TPL_TYPE'			=>	'.html',				//模板类型
    'TPL_DIR'			=>	'/View/',				//模板文件存放目录
    'TPL_C_DIR'			=>	'/RunTime/Template_c/',	//编译文件存放目录

    //缓存设置
    'CACHE_DATA_DIR'	=>	'/RunTime/Cache/Data/', //缓存文件存放目录
    'CACHE_OUT_PREFIX'	=>	'ca_',   				//缓存文件名生成规则
    'CACHE_OUT_SUFFIX'	=>	'.json'   				//缓存存储后缀
);
?>