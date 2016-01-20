<?php
/*
	Author : Colin,
	Creation time : 2016/01/15 18:49
	FileType : 表单类
	FileName : From.class.php
*/
namespace MyClass\libs;
class Form {
	public $form;

	/**
	 * 创建表单
	 */
	public function openform($name = 'form', $method = 'post', $class = 'form', $other = null){
		$this->form = "<form action='$method' name='$name' class='$class' $other>";
		return $this->form;
	}

	/**
	 * 创建一个input
	 */
	public function input($name , $type = 'text' , $param = array() , $value = null){
		self::creatediv();
		$this->form .= "<input type='$type' name='$name' value='$value' id='$name' />";
		self::closediv();
		return $this->form;
	}

	/**
	 * 创建一个text文本框
	 */
	public function text($name , $value = null , $param = array()){
		return self::input($name , 'text' , $param , $value);
	}

	/**
	 * 创建一个密码框
	 */
	public function password($name , $value = null , $param = array()){
		return self::input($name , 'password' , $param , $value);
	}

	/**
	 * 创建一个隐藏域
	 */
	public function hidden($name , $value = null , $param = array()){
		return self::input($name , 'hidden' , $param , $value);
	}

	/**
	 * 创建一个file文件域
	 */
	public function file($name , $value = null , $param = array()){
		return self::input($name , 'file' , $param , $value);
	}

	/**
	 * 创建一个select
	 */
	public function select($name , $value = null , $opts = array() , $param = array()){
		self::creatediv();
		$this->form .= "<select name='$name' id='$name'>";
		foreach ($opts as $key => $value) {
			$this->form .= "<option value='$key'>$value</option>";
		}
		$this->form .= "</select>";
		self::closediv();
		return $this->form;
	}

	/**
	 * 创建一个label
	 */
	public function label($text , $class = 'col-md-2'){
		$this->form = "<label class='$class'>$text</label>";
		return $this->form;
	}

	/**
	 * 创建一个div
	 */
	public function creatediv($class = 'form-group'){
		$this->form = "<div class='$class'>";
	}

	/**
	 * 关闭一个div
	 */
	public function closediv($class = 'form-group'){
		$this->form .= "</div>";
	}

	/**
	 * 创建提交按钮
	 */
	public function submit($string = '提交', $class = 'btn btn-primary' , $name = null , $param = array()){
		self::creatediv();
		$this->form .= "<button class='$class' name='$name' type='submit'>$string</button>";
		self::closediv();
		return $this->form;
	}

	/**
	 * 创建返回按钮
	 */
	public function back($string = '返回' , $class = 'btn btn-default back' , $name = null , $param = array()){
		self::creatediv();
		$this->form .= "<button class='$class' name='$name' type='back'>$string</button>";
		self::closediv();
		return $this->form;
	}

	/**
	 * 关闭表单
	 */
	public function closeform(){
		$this->form = '</form>';
		return $this->form;
	}
}