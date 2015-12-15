<?php
	/*
		Author : Colin,
		Creation time : 2015-8-1 10:30:21
		FileType :模板类
		FileName :Templets.class.php
	*/
	namespace MyClass\libs;
	class Templates
	{
		//创建一数组来保存注入的变量
		private $_vars = array();
		//保存系统变量
		private $_config = array();
		
		//创建构造方法  来验证各个目录是否存在
		public function __construct()
		{
			if(!is_dir(APP_PATH.TPL_DIR))
			{
				throw new MyError('请正确设置模板文件目录');
			}
		}
		
		//assign()方法  用于注入变量
		public function assign($_var,$_value)
		{
			//$_var 表示 模板文件中的变量名
			//$_value 表示要在模板文件中显示的值
			if(isset($_var) && !empty($_var))
			{
				//相当于 $this->_vars['name'] = 'Colin';
				$this->_vars[$_var] = $_value;
			}else
			{
				throw new MyError('请设置模板变量名！');
			}
		}

		//注入核心变量
		private function assignCoreVar()
		{
			//地址
			$_url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
			//注入当前地址变量
			$this->assign('URL', $_url);
			//去除脚本名
			$_dir = substr($_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"],0,-9);
			$_dir = $_dir.APP_PATH.PUBLIC_DIR;

			//注入css，img,js地址变量
			$this->assign('PUBLIC',$_dir);
		}
		
		//display()方法 载入文件。生成编译文件和缓存文件
		public function display($_file)
		{
			$_array = Url::GetUrl();

			//注入核心变量
			$this->assignCoreVar();
			
			//检查默认控制器是否存在
			if(!file_exists(APP_PATH.'/Controller/'.DEFAULT_CONTROLLER.'Controller.class.php'))
			{
			    throw new MyError(DEFAULT_CONTROLLER.'控制器不存在！');
			}
			
			//这里会出问题
			empty($_array['method']) ? $_array['method'] = DEFAULT_ACTION : $_array['method'];

			//如果$_file为空
			empty($_file) ? $_file = $_array['method'].TPL_TYPE : $_file = $_file.TPL_TYPE;

			//设置路径
			$_dirname = APP_PATH.TPL_DIR.$_array['name'].'/';
			$_dircname = APP_PATH.TPL_C_DIR.$_array['name'].'/';
			$_dircache = APP_PATH.CACHE.$_array['name'].'/';
			
			//判断方法目录是否存在
			if(!is_dir($_dirname))
			{
				throw new MyError($_dirname.'目录不存在');
			}

			//设置模板文件路径
			$_tplFile = $_dirname.$_file;

			//判断模板文件是否存在
			if(!file_exists($_tplFile))
			{
				throw new MyError($_tplFile.'模板文件不存在！');
			}
			
			
			//判断编译文件夹和缓存文件夹是否存在
			if(!is_dir(APP_PATH.TPL_C_DIR))@mkdir(APP_PATH.TPL_C_DIR);
			if(!is_dir(APP_PATH.CACHE))@mkdir(APP_PATH.CACHE);

			//判断缓存文件夹以控制器命名的文件夹
			if(!is_dir($_dircname))@mkdir($_dircname);
			if(!is_dir($_dircache))@mkdir($_dircache);
			
			//生成编译文件
			$_parFile = $_dircname.md5($_file).$_file.'.php';
			//生成缓冲文件
			$_cacheFile = $_dircache.md5($_file).$_file.'.html';
			//当第二次执行相同文件时，直接调用缓存文件。避开编译

			if(IS_CACHE)
			{
				//判断编译文件和缓存文件是否存在
				if(file_exists($_parFile) && file_exists($_cacheFile))
				{
					//判断模板文件是否修改
					//编译文件的修改时间>=模板文件的修改时间 && 缓存文件的修改时间大于编译文件的修改时间  证明他没有被修改过！
					if(filemtime($_parFile) >= filemtime($_tplFile) && filemtime($_cacheFile) >= filemtime($_parFile))
					{
						//载入缓存文件
						include $_cacheFile;
						//让程序不在执行了
						return;
					}
				}
			}
			//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
			if(!file_exists($_parFile) || (filemtime($_parFile) < filemtime($_tplFile)))//编译文件的修改时间<tpl模板文件的修改时间
			{
				//实例化解析类
				$_parser = ObjFactory::CreateTemplatesParse($_tplFile);
				//调用解析类里面的公共方法
				$_parser->comile($_parFile);
			}
			//引入编译文件
			include $_parFile;
			//缓存功能
			if(IS_CACHE)
			{
				//获取缓冲区数据并写入文件
				file_put_contents($_cacheFile,ob_get_contents());
				//清除缓冲区的数据
				ob_end_clean();
				//载入缓存文件
				include $_cacheFile;
			}
		}

		//载入show文件
		public function showdisplay($_file)
		{
			$_array = Url::GetUrl();
			//载入系统核心变量
			$this->assignCoreVar();
			if(!file_exists(TEMPLATES_DIR.SITE_TEMPLATES.$_file))throw new MyError(TEMPLATES_DIR.SITE_TEMPLATES.$_file.'模板文件不存在！');
			//编译文件目录是否存在
			if(!file_exists(TPL_C_DIR.$_array['name']))throw new MyError($_array['name'].'编译目录文件不存在');
			//判断缓存文件夹是否存在
			//生成编译文件
			$_parFile = TPL_C_DIR.$_array['name'].'/'.$_file.md5($_file).$_file.'.php';
			//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
			//实例化解析类;
			$_parser = ObjFactory::CreateTemplatesParse(TEMPLATES_DIR.SITE_TEMPLATES.$_file);
			//调用解析类里面的公共方法
			$_parser->comile($_parFile);
			//引入编译文件
			include $_parFile;
		}

		//载入layout方法
		public function Layout($_file)
		{
			//是否为空
			if(empty($_file))
			{
				$_array = URL::getControllerModel();
				$_file = $_array['method'];
			}

			//注入系统核心变量
			$this->assignCoreVar();

			$_tplFile = APP_PATH.TPL_DIR.$_file;

			//判断是否写了目录名支持持一级
			$_patten = '/(.*)\/(.*)/';
			if(preg_match($_patten,$_file,$_match))
			{
				$_tplFile = APP_PATH.LAYOUT_DIR.'/'.$_match[2].TPL_TYPE;
			}else
			{
				$_tplFile = APP_PATH.LAYOUT_DIR.'/'.$_file.TPL_TYPE;
			}

			//判断模板文件是否存在
			if(!file_exists($_tplFile))
			{
				throw new MyError($_tplFile.'视图文件不存在！');
			}

			//生成编译文件
			if(isset($_match[2]))
			{
				$_parFile = APP_PATH.TPL_C_DIR.md5($_match[2]).$_match[2].'.php';
			}else
			{
				$_parFile = APP_PATH.TPL_C_DIR.md5($_file).$_file.'.php';
			}
			//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
			if(!file_exists($_parFile) || (filemtime($_parFile) < filemtime($_tplFile)))//编译文件的修改时间<tpl模板文件的修改时间
			{
				//实例化解析类
				$_parser = \Myclass\libs\ObjFactory::CreateTemplatesParse($_tplFile);
				//调用解析类里面的公共方法
				$_parser->comile($_parFile);
			}
			//引入编译文件
			include $_parFile;
		}
	}
?>