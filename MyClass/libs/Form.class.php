<?php
/*
	Author : Colin,
	Creation time : 2016/01/15 18:49
	FileType : 表单类
	FileName : From.class.php
*/
namespace MyClass\libs;
class Form {
	public static $form;

	/**
	 * 打开表单
	 * @param string $action 表单提交地址
	 * @param int $id 表单id
	 * @param string $class 表单所拥有的类
	 * @param string $attr 额外属性，例如onclick="xxx()";
	 * @author Colin <15070091894@163.com>
	 */
	public static function openForm($action = null , $id = null , $class = null , $attr = null){
		self::$form .= "<form action='$action' id='$id' class='$class' $attr>";
	}

	/**
	 * 关闭表单
	 * @author Colin <15070091894@163.com>
	 */
	public static function closeForm(){
		self::$form .= "</form>";
		self::echoForm();
	}

	/**
	 * 输出表单
	 * @author Colin <15070091894@163.com>
	 */
	public static function echoForm(){
		echo self::$form;
	}

	public function submit(){
		echo 'submit';
	}
	
}