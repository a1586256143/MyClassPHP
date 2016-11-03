<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:38
	FileType : 视图类
	FileName : View.class.php
*/
namespace MyClass\libs;
class View {
	//静态成员
	public static $view;
	
	/**
     * 初始化成员信息
     * @param type 类型
     * @author Colin <15070091894@163.com>
     */
	public static function init($type , $config = array()){
		self::$view = ObjFactory::CreateTemplates($type , $config);
	}
	
	/**
     * display方法
     * @author Colin <15070091894@163.com>
     */
	public static function display($filename){
		return self::$view->display($filename);
	}
	
	/**
     * assign方法
     * @author Colin <15070091894@163.com>
     */
	public static function assign($name , $value){
		return self::$view->assign($name , $value);
	}

	/**
     * 创建index.php 模板
     * @author Colin <15070091894@163.com>
     */
    public static function createIndex($default){
        $namespace = defined('CURRENT_MODULE') ? CURRENT_MODULE : Config('DEFAULT_MODULE');
    	$string = "<?php 
namespace {$namespace}\Controller;
use MyClass\libs\Controller;
class $default extends Controller{
	public function index(){
		echo 'Welcome to use MyClassPHP';
	}
}
?>";
    	return $string;
    }

    /**
     * 创建config.php 模板
     * @author Colin <15070091894@163.com>
     */
    public static function createConfig(){
    	$string = "<?php
return array(
	//配置名 => 配置值
);
?>";
		return $string;
    }

    /**
     * 创建默认template.php模板
     * @author Colin <15070091894@163.com>
     */
    public static function createTemplate(){
    	$string = "<?php
//此文件为模板中使用的__常量名__格式配置文件，配置格式为
//if(!defined('常量名')) define('常量名' , '常量值');
?>";
		return $string;
    }
}
?>