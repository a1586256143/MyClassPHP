<?php
	/*
		Author : Colin,
		Creation time : 2015-8-1
		FileType : MyClassPHP引导类
		FileName : MyClass.php
	*/
	namespace MyClass\libs;

	class MyClass
	{
		//静态成员
		public static $_obj;
		
		//运行方法
		public static function run()
		{
			try {
				//注册autoload方法
				spl_autoload_register('MyClass\\libs\\MyClass::autoload');
				//判断文件夹是否存在
				self::Dir();
				//加载常亮函数
				self::ReqConst();
				//解析常量方法
				self::ParConst();
				//视图初始化
				self::View();
				//初始化URL模式
				self::UrlModel();
			}catch (MyError $m)
			{
				echo $m;
			}
		}
		
		//自动加载方法
		/*public static function autoload($_ClassName)
		{
			$_patten = "#.*\\\\#";
			if(substr($_ClassName,-5) == 'Model')
			{
				if(preg_match_all($_patten,$_ClassName))
				{
					//匹配到了\号  就从libs下加载文件
					require_once $_ClassName.'.class.php';
				}else
				{
					//没有匹配到，判断是否为空 为空则是libs下的model  否则是 APP_PATH下的Model
					empty(substr($_ClassName,0,-5)) ? require_once $_ClassName.'.class.php': require_once APP_PATH.'/Model/'.$_ClassName.'.class.php';
				}
			}else if(substr($_ClassName,-10) == 'Controller')
			{
				//判断截取后的字符是否为空，如果为空就是libs\下的controller
				//判断是否有/  如果有那么代表是命名空间
				if(preg_match_all($_patten,$_ClassName))
				{
					//匹配到了\号  就从libs下加载文件
					require_once $_ClassName.'.class.php';
				}else
				{
					//没有匹配到，判断是否为空 为空则是libs下的controller  否则是 APP_PATH下的controller
					empty(substr($_ClassName,0,-10)) ? require_once $_ClassName.'.class.php': require_once APP_PATH.'/Controller/'.$_ClassName.'.class.php';
				}

			}else
			{
				//如果$_classname不满足以上两个条件，那么加载libs下的文件
				require_once $_ClassName.'.class.php';
			}
		}*/
		
		public static function autoload($_ClassName)
		{
            $_patten = "/\//";
            $_patten2 = "/\\\\/";
            if(preg_match_all($_patten2,$_ClassName,$match)){
                $_ClassName = preg_replace($_patten2,'/',$_ClassName);
            }
			if(substr($_ClassName,-5) == 'Model')
			{
				if(preg_match_all($_patten,$_ClassName))
				{
					//匹配到了\号  就从libs下加载文件
					require_once $_ClassName.'.class.php';
				}else
				{
					//没有匹配到，判断是否为空 为空则是libs下的model  否则是 APP_PATH下的Model
					empty(substr($_ClassName,0,-5)) ? require_once $_ClassName.'.class.php': require_once APP_PATH.'/Model/'.$_ClassName.'.class.php';
				}
			}else if(substr($_ClassName,-10) == 'Controller')
			{
				//判断截取后的字符是否为空，如果为空就是libs\下的controller
				//判断是否有/  如果有那么代表是命名空间
				if(preg_match_all($_patten,$_ClassName))
				{
					//匹配到了\号  就从libs下加载文件
					require_once $_ClassName.'.class.php';
				}else
				{
                   
                    
					//没有匹配到，判断是否为空 为空则是libs下的controller  否则是 APP_PATH下的controller
					empty(substr($_ClassName,0,-10)) ? require_once $_ClassName.'.class.php': require_once APP_PATH.'/Controller/'.$_ClassName.'.class.php';
				}

			}else
			{
                //var_dump($_ClassName);
				//如果$_classname不满足以上两个条件，那么加载libs下的文件
                require_once $_ClassName.'.class.php';
			}
		}

		//URL模式
		public static function UrlModel()
		{
			Route::CheckRoute();
		}
		
		//常亮引入方法
		public static function ReqConst()
		{
			require_once MyClass . '/Common/functions.php';
			$_const = require_once MyClass . '/Conf/config.php';
			$_const1 = require_once APP_PATH . '/Conf/config.php';
			$_const3 = array_replace_recursive($_const,$_const1);
			
			//是否为空
			$_patten = '/DB\_(.*)/';
			foreach ($_const3 as $_key => $_value)
			{
				/*if(preg_match_all($_patten,$_key,$_match))
				{
					//数据库密码除外
					if($_key != 'DB_PASS' && $_value == '')
					{
						ShowMessage('数据库未配置:'.$_key);
					}
				}*/
				if(!defined($_key))define($_key,$_value);
			}
		}
		
		//解析常量方法
		public static function ParConst()
		{
		    if(SESSION_OPEN != 0)
		    {
		        session_start();
		    }
		}
		
		//目录结构方法
		public static function Dir()
		{
			@mkdir(APP_PATH);        //建立App目录
			@mkdir(RunTime);            //建立运行目录
			@mkdir(ControllerDIR);   //建立控制器目录
			@mkdir(ModelDIR);        //建立模型目录
			@mkdir(ConfDIR);            //建立配置文件目录
			@mkdir(APP_PATH.TPL_DIR);//建立视图文件目录
			//生成默认的配置文件
			if(!file_exists(ConfDIR.'/config.php'))
			{
                @file_put_contents(ConfDIR.'/config.php',@file_get_contents(MyClass.'/tpl/config.php'));
            }
			//生成默认的控制器
			if(!file_exists(ControllerDIR.'/IndexController.class.php'))
			{
				@file_put_contents(ControllerDIR.'/IndexController.class.php',@file_get_contents(MyClass.'/tpl/index.php'));
			}
		}
		
		public static function View()
		{
			//初始化视图工厂
			if(TPL_MODEL == 'tpl')
			{
				View::init('tpl');
			}
		}
	}
?>