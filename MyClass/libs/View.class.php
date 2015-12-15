<?php
	/*
		Author : Colin,
		Creation time : 2015-8-1 10:30:38
		FileType : 视图类
		FileName : View.class.php
	*/
	namespace MyClass\libs;
	class View 
	{
		//静态成员
		public static $view;
		
		//初始化成员信息
		public static function init($_type)
		{
			if($_type == 'tpl')
			{
				//require_once MyClass.'/libs/MyClassPHP/Templates.class.php';
				self::$view = ObjFactory::CreateTemplates();
			}
		}
		
		//display方法
		public static function display($_filename)
		{
			return self::$view->display($_filename);
		}
		
		//assign方法
		public static function assign($_name,$_value)
		{
			return self::$view->assign($_name,$_value);
		}
	}
?>