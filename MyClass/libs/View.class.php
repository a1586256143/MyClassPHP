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
	public static function init($type){
		if($type == 'tpl'){
			//require_once MyClass.'/libs/MyClassPHP/Templates.class.php';
			self::$view = ObjFactory::CreateTemplates();
		}
	}
	
	/**
     * display方法
     * @author Colin <15070091894@163.com>
     */
	public static function display($FileName){
		return self::$view->display($FileName);
	}
	
	/**
     * assign方法
     * @author Colin <15070091894@163.com>
     */
	public static function assign($name , $value){
		return self::$view->assign($name , $value);
	}
}
?>