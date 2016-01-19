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
			$this->_vars[$var] = $_value;
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
		$_dirname = APP_PATH.Config('TPL_DIR').$controller.'/';
		$_dircname = APP_PATH.Config('TPL_C_DIR').$controller.'/';
		//$_dircache = APP_PATH.Config('CACHE').$array[1].'/';

		//判断方法目录是否存在
		if(!is_dir($_dirname)){
			throw new MyError($_dirname.'目录不存在');
		}

		//设置模板文件路径
		$_tplFile = $_dirname.$file;
		//判断模板文件是否存在
		if(!file_exists($_tplFile)){
			throw new MyError($_tplFile.'模板文件不存在！');
		}
		
		//判断编译文件夹和缓存文件夹是否存在
		if(!is_dir(APP_PATH.Config('TPL_C_DIR')))@mkdir(APP_PATH.Config('TPL_C_DIR'));
		//if(!is_dir(APP_PATH.Config('CACHE')))@mkdir(APP_PATH.Config('CACHE'));

		//判断缓存文件夹以控制器命名的文件夹
		if(!is_dir($_dircname))@mkdir($_dircname);
		//if(!is_dir($_dircache))@mkdir($_dircache);
		//处理传入数据
		if(!empty($data)){
			if(is_array($data)){
				foreach ($data as $key => $value) {
					$keyname = "$$key = '$value';";
					eval($keyname);
				}
			}elseif(is_string($data)){
				$data = $data;
			}
		}
		include $_tplFile;
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
		$_parFile = Config('TPL_C_DIR').$array[1].'/'.$file.md5($file).$file.'.php';
		//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
		//实例化解析类;
		$_parser = ObjFactory::CreateTemplatesParse(TEMPLATES_DIR.SITE_TEMPLATES.$file);
		//调用解析类里面的公共方法
		$_parser->comile($_parFile);
		//引入编译文件
		include $_parFile;
	}

	/**
     * 载入layout方法
     * @param file 文件名
     * @author Colin <15070091894@163.com>
     */
	public function Layout($file){
		//是否为空
		if(empty($file)){
			$array = URL::getCurrentUrl();
			$file = $array[2];
		}

		//注入系统核心变量
		$this->assignCoreVar();

		$_tplFile = APP_PATH.Config('TPL_DIR').$file;

		//判断是否写了目录名支持持一级
		$_patten = '/(.*)\/(.*)/';
		if(preg_match($_patten,$file,$_match)){
			$_tplFile = APP_PATH.Config('LAYOUT_DIR').'/'.$_match[2].Config('TPL_TYPE');
		}else{
			$_tplFile = APP_PATH.Config('LAYOUT_DIR').'/'.$file.Config('TPL_TYPE');
		}

		//判断模板文件是否存在
		if(!file_exists($_tplFile)){
			throw new MyError($_tplFile.'视图文件不存在！');
		}

		//引入编译文件
		include $_tplFile;
	}
}
?>