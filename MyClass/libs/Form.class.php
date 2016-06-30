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
	 * @param string $name  表单的名称
	 * @param int $id 表单id
	 * @param string $class 表单所拥有的类
	 * @param string $attr 额外属性，例如onclick="xxx()";
	 * @author Colin <15070091894@163.com>
	 */
	public static function openForm($action = null , $name = null , $id = null , $class = null , $attr = null){
		self::$form .= "<form action='$action' name='$name' id='$id' class='$class' $attr>";
		self::echoForm();
	}

	/**
	 * 普通表单
	 * @param  string $name  input的名称
	 * @param  int $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function inputText($name = null , $id = null , $class = null , $attr = null){
		if($id == null) $id = $name;
		self::input('text' , $name , $id , $class , $attr);
	}

	/**
	 * input操作,所有操作的方法集合
	 * @param  string $type  input的类型，支持的有text、hidden、button、submit
	 * @param  string $name  input的名称
	 * @param  int $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function input($type = null , $name = null , $id = null , $class = null , $attr = null){
		self::$form = "<input type='$type' name='$name' id='$id' class='$class' $attr>";
		self::echoForm();
	}

	/**
	 * 关闭表单
	 * @author Colin <15070091894@163.com>
	 */
	public static function closeForm(){
		self::$form = "</form>";
		self::echoForm();
	}

	/**
	 * 输出表单
	 * @author Colin <15070091894@163.com>
	 */
	public function echoForm(){
		echo self::$form;
	}
}