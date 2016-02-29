<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:21
	FileType :模板类
	FileName :Templets.class.php
*/
namespace MyClass\libs\Templates\MyTemplate;
use MyClass\libs\MyError;
use MyClass\libs\ObjFactory;
use MyClass\libs\Url;
class Templates{
	//创建一数组来保存注入的变量
	private $_vars = array();
	//保存系统变量
	private $_config = array();
	//模板目录
	public $template_dir;
	public $compile_dir;
	public $cache_dir;

	/**
     * assign()方法  用于注入变量
     * @param var 模板中的变量名
     * @return value 模板中变量名的值
     * @author Colin <15070091894@163.com>
     */
	public function assign($var , $value){
		if(isset($var) && !empty($var)){
			//相当于 $this->_vars['name'] = 'Colin';
			$this->$var = $value;
		}else{
			throw new MyError('请设置模板变量名！');
		}
	}

	/**
     * display()方法 载入文件。生成编译文件和缓存文件
     * @param file 文件名
     * @author Colin <15070091894@163.com>
     */
	public function display($file){
		//获取模板名
		$filename = $this->getTemplateName($file);
		@list($controller , $method) = Url::getCurrentUrl();

		//默认控制器和默认方法
		$default_controller = Config('DEFAULT_CONTROLLER');
		$default_action = Config('DEFAULT_METHOD');
		//检查默认控制器是否存在
		if(!file_exists(APP_PATH.'/Controller/'.$default_controller.'Controller.class.php')){
		    throw new MyError($default_controller.'控制器不存在！');
		}

		$controller = empty($controller) ? $default_controller : $controller;
	
		//设置路径
		$dirname = $this->template_dir.$controller.'/';
		
		//编译文件目录
		$dircname = $this->compile_dir.ltrim(APP_NAME , './').'/'.$controller.'/';

		//判断编译文件夹和缓存文件夹是否存在
		$dir = array($this->compile_dir , $this->compile_dir.ltrim(APP_NAME , './') , $dircname);

		//生成文件夹
		outdir($dir);

		//判断方法目录是否存在
		if(!is_dir($dirname)){
			throw new MyError($dirname.'目录不存在');
		}
		//判断模板文件是否存在
		if(!file_exists($file)){
			throw new MyError($file.'模板文件不存在！');
		}
		
		//生成编译文件
		$parFile = $dircname.md5($filename).$filename.'.php';
		//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
		if(!file_exists($parFile) || (filemtime($parFile) < filemtime($file))){
			//编译文件的修改时间<tpl模板文件的修改时间
			//实例化解析类
			$_parser = ObjFactory::CreateTemplatesParse('tpl' , $file);
			//调用解析类里面的公共方法
			$_parser->comile($parFile);
		}
		//引入编译文件
		require $parFile;
	}

	/**
	 * 获取模板名
	 */
	protected function getTemplateName($path = null){
		$explode = explode('/' , $path);
		$filearray = array_pop($explode);
		$filename = explode('.' , $filearray);
		return $filename[0];
	}

	/**
     * 载入layout方法
     * @param file 文件名
     * @author Colin <15070091894@163.com>
     */
	public function Layout($file){
		list($controller , $method) = URL::getCurrentUrl();

		$tplFile = $file;
		//判断是否写了目录名支持持一级
		$_patten = '/(.*)\/(.*)/';
		if(preg_match($_patten , $file , $_match)){
			$tplFile = Config('LAYOUT_DIR').'/'.$_match[2];
		}
		
		//设置路径
		$dircname = Config('TPL_C_DIR').ltrim(APP_NAME , './').'/'.$controller.'/';

		//判断模板文件是否存在
		if(!file_exists($tplFile)){
			throw new MyError($tplFile.'视图文件不存在！');
		}
		$name = $this->getTemplateName($file);
		$name = str_replace('/', '_', $name);
		//生成编译文件
		$parFile = $dircname.md5($name).$name.'.php';
		
		//判断编译文件是否存在 如果存在那么就直接调用编译文件 如果不存在 那么久重新编译生成
		if(!file_exists($parFile) || (filemtime($parFile) < filemtime($tplFile))){
			//编译文件的修改时间<tpl模板文件的修改时间
			//实例化解析类
			$parser = ObjFactory::CreateTemplatesParse('tpl' , $tplFile);
			//调用解析类里面的公共方法
			$parser->comile($parFile);
		}
		//引入编译文件
		include $parFile;
	}
}
?>