<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:21
	FileType :模板类
	FileName :Templets.class.php
*/
namespace MyClass\libs;
class Templates{
	//创建一数组来保存注入的变量
	private $_vars = array();
	//保存系统变量
	private $_config = array();
	
	/**
     * 创建构造方法  来验证各个目录是否存在
     * @author Colin <15070091894@163.com>
     */
	public function __construct(){
		if(!is_dir(APP_PATH.Config('TPL_DIR'))){
			throw new MyError('请正确设置模板文件目录');
		}
	}
	
	/**
     * assign()方法  用于注入变量
     * @param var 模板中的变量名
     * @return value 模板中变量名的值
     * @author Colin <15070091894@163.com>
     */
	public function assign($var , $value){
		if(isset($var) && !empty($var)){
			//相当于 $this->_vars['name'] = 'Colin';
			$this->_vars[$var] = $value;
		}else{
			throw new MyError('请设置模板变量名！');
		}
	}

	/**
     * 注入核心变量
     * @author Colin <15070091894@163.com>
     */
	private function assignCoreVar(){
		if(!defined('__PUBLIC__')) define('__PUBLIC__' , Url::getSiteUrl().'/Public');
		if(!defined('__URL__')) define('__URL__' , Url::getCurrentUrl(true));
	}
	
	/**
     * display()方法 载入文件。生成编译文件和缓存文件
     * @param file 文件名
     * @return data 文件值
     * @author Colin <15070091894@163.com>
     */
	public function display($file , $data = null){
		@list($controller , $method) = Url::getCurrentUrl();

		//注入核心变量
		$this->assignCoreVar();
		$default_controller = Config('DEFAULT_CONTROLLER');
		$default_action = Config('DEFAULT_METHOD');
		//检查默认控制器是否存在
		if(!file_exists(APP_PATH.'/Controller/'.$default_controller.'Controller.class.php')){
		    throw new MyError($default_controller.'控制器不存在！');
		}

		empty($controller) ? $controller = $default_controller : $controller;
		
		empty($method) ? $method = $default_action : $method;

		//如果$_file为空
		empty($file) ? $file = $method.Config('TPL_TYPE') : $file = $file.Config('TPL_TYPE');

		//设置路径
		$dirname = APP_PATH.Config('TPL_DIR').$controller.'/';
		$dircname = APP_PATH.Config('TPL_C_DIR').$controller.'/';
		$cachedir = APP_PATH.Config('TPL_CACHE').$controller.'/';

		//判断方法目录是否存在
		if(!is_dir($dirname)){
			throw new MyError($dirname.'目录不存在');
		}

		//设置模板文件路径
		$tplFile = $dirname.$file;
		//判断模板文件是否存在
		if(!file_exists($tplFile)){
			throw new MyError($tplFile.'模板文件不存在！');
		}
		
		//判断编译文件夹和缓存文件夹是否存在
		if(!is_dir(APP_PATH.Config('TPL_C_DIR'))) @mkdir(APP_PATH.Config('TPL_C_DIR'));
		if(!is_dir(APP_PATH.Config('TPL_CACHE'))) @mkdir(APP_PATH.Config('TPL_CACHE'));

		//判断缓存文件夹以控制器命名的文件夹
		if(!is_dir($dircname)) @mkdir($dircname);
		if(!is_dir($cachedir)) @mkdir($cachedir);
		//生成编译文件
		$parFile = $dircname.md5($file).$file.'.php';
		//生成缓冲文件
		$cacheFile = $cachedir.md5($file).$file.'.html';
		//当第二次执行相同文件时，直接调用缓存文件。避开编译
		if(IS_CACHE){
			//判断编译文件和缓存文件是否存在
			if(file_exists($parFile) && file_exists($cacheFile)){
				//判断模板文件是否修改
				//编译文件的修改时间>=模板文件的修改时间 && 缓存文件的修改时间大于编译文件的修改时间  证明他没有被修改过！
				if(filemtime($parFile) >= filemtime($tplFile) && filemtime($cacheFile) >= filemtime($parFile)){
					//载入缓存文件
					include $cacheFile;
					//让程序不在执行了
					return;
				}
			}
		}
		//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
		if(!file_exists($parFile) || (filemtime($parFile) < filemtime($tplFile))){
			//编译文件的修改时间<tpl模板文件的修改时间
			//实例化解析类
			$_parser = ObjFactory::CreateTemplatesParse($tplFile);
			//调用解析类里面的公共方法
			$_parser->comile($parFile);
		}
		//引入编译文件
		include $parFile;
		//缓存功能
		if(IS_CACHE){
			//获取缓冲区数据并写入文件
			file_put_contents($cacheFile,ob_get_contents());
			//清除缓冲区的数据
			ob_end_clean();
			//载入缓存文件
			include $cacheFile;
		}
	}

	/**
     * 载入show文件
     * @param file 文件名
     * @author Colin <15070091894@163.com>
     */
	public function showdisplay($file){
		$array = Url::getCurrentUrl();
		//载入系统核心变量
		$this->assignCoreVar();
		if(!file_exists(TEMPLATES_DIR.SITE_TEMPLATES.$file))E(TEMPLATES_DIR.SITE_TEMPLATES.$file.'模板文件不存在！');
		//编译文件目录是否存在
		if(!file_exists(Config('TPL_C_DIR').$array[1]))E($array[1].'编译目录文件不存在');
		//判断缓存文件夹是否存在
		//生成编译文件
		$parFile = Config('TPL_C_DIR').$array[1].'/'.$file.md5($file).$file.'.php';
		//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
		//实例化解析类;
		$_parser = ObjFactory::CreateTemplatesParse(TEMPLATES_DIR.SITE_TEMPLATES.$file);
		//调用解析类里面的公共方法
		$_parser->comile($parFile);
		//引入编译文件
		include $parFile;
	}

	/**
     * 载入layout方法
     * @param file 文件名
     * @author Colin <15070091894@163.com>
     */
	public function Layout($file){
		//是否为空
		if(empty($file)){
			list($controller , $method) = URL::getCurrentUrl();
			$file = $method;
		}

		//注入系统核心变量
		$this->assignCoreVar();

		$tplFile = APP_PATH.Config('TPL_DIR').$file;

		//判断是否写了目录名支持持一级
		$_patten = '/(.*)\/(.*)/';
		if(preg_match($_patten,$file,$_match)){
			$tplFile = APP_PATH.Config('LAYOUT_DIR').'/'.$_match[2].Config('TPL_TYPE');
		}else{
			$tplFile = APP_PATH.Config('LAYOUT_DIR').'/'.$file.Config('TPL_TYPE');
		}

		//判断模板文件是否存在
		if(!file_exists($tplFile)){
			throw new MyError($tplFile.'视图文件不存在！');
		}

		//生成编译文件
		$parFile = $dircname.md5($file).$file.'.php';
		//生成缓冲文件
		$cacheFile = $cachedir.md5($file).$file.'.html';
		//当第二次执行相同文件时，直接调用缓存文件。避开编译
		if(IS_CACHE){
			//判断编译文件和缓存文件是否存在
			if(file_exists($parFile) && file_exists($cacheFile)){
				//判断模板文件是否修改
				//编译文件的修改时间>=模板文件的修改时间 && 缓存文件的修改时间大于编译文件的修改时间  证明他没有被修改过！
				if(filemtime($parFile) >= filemtime($tplFile) && filemtime($cacheFile) >= filemtime($parFile)){
					//载入缓存文件
					include $cacheFile;
					//让程序不在执行了
					return;
				}
			}
		}
		//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
		if(!file_exists($parFile) || (filemtime($parFile) < filemtime($tplFile))){
			//编译文件的修改时间<tpl模板文件的修改时间
			//实例化解析类
			$parser = ObjFactory::CreateTemplatesParse($tplFile);
			//调用解析类里面的公共方法
			$parser->comile($parFile);
		}
		//引入编译文件
		include $parFile;
		//缓存功能
		if(IS_CACHE){
			//获取缓冲区数据并写入文件
			file_put_contents($cacheFile,ob_get_contents());
			//清除缓冲区的数据
			ob_end_clean();
			//载入缓存文件
			include $cacheFile;
		}
	}
}
?>