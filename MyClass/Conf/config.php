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

    //自动引入(Noice 自动引入会很消耗资源，请谨慎使用)
    'AUTO_REQUIRE'      => false,                   //自动引入
    'AUTO_REQUIRE_FILE' => '',                      //自动引入文件,号分割多个文件 例如 /Conf/template.php
	
    //URL设置
	'URL_MODEL'			=>	2,						//URL模式

	//目录设置
	'PUBLIC_DIR'		=>	'/Public',				//公共文件地址
	'LAYOUT_DIR'		=>	'/View/Layout',			//视图公共模块

	//session设置
    'SESSION_START'		=>	1,            			//开启session

    //控制器设置
    'DEFAULT_CONTROLLER_LAYER'  =>  'Controller',   //默认控制器目录名
    'DEFAULT_CONTROLLER'=>	'Index', 				//默认控制器
    'DEFAULT_MODULE'	=>	'Home', 				//默认模块
    'DEFAULT_METHOD'	=>	'index', 				//默认方法
    'DEFAULT_CONTROLLER_SUFFIX' =>  'Controller',   //默认控制器后缀
    'DEFAULT_CLASS_SUFFIX'		=>	'.class.php', 	//默认类文件后缀

    //模型设置
    'DEFAULT_MODEL_LAYER'      =>  'Model',         //默认模型目录名
    'DEFAULT_MODEL_SUFFIX'      =>  'Model',        //默认模型器后缀
    
    //模板引擎设置
    'TPL_MODEL'			=>	'tpl',					//模板引擎
	'TPL_TYPE'			=>	'.html',				//模板类型
    'TPL_DIR'			=>	'/View/',				//模板文件存放目录
    'TPL_C_DIR'			=>	'/RunTime/Template_c/',	//编译文件存放目录
    'TPL_CACHE'         =>  '/RunTime/Template_cache/',      //编译文件文件目录

    //缓存设置
    'CACHE_DATA_DIR'    =>  '/RunTime/Cache/Data/', //S缓存文件存放目录
    'CACHE_DIR'     	=>	'/RunTime/Cache/', //缓存文件
    'CACHE_OUT_PREFIX'	=>	'ca_',   				//缓存文件名生成规则
    'CACHE_OUT_SUFFIX'	=>	'.json',   				//缓存存储后缀

    //上传配置
    'UPLOAD_DIR'		=>	'./Upload',				//上传文件的目录
    'UPLOAD_TYPE'       =>  'image/jpg,image/jpeg,image/png,image/gif',			//上传文件类型
    'UPLOAD_MAXSIZE'    =>  2097152,                   //上传文件大小
    'UPLOAD_ISRANDNAME' =>  1,                   	//设置是否随机文件名
);
?>