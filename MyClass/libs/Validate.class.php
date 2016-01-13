<?php

/*
Author : Colin,
Creation time : 2015/8/17 16:09
FileType : 验证类
FileName : Validate.class.php
*/
namespace MyClass\libs;
class Validate {
	public $string = '';		//要处理的字符串
	public $require = 0;		//是否必填
	public $parstring = array();//处理后的字符串
	public $maxlength = '';		//最大长度
	public $minlength = '';		//最小长度
	public $info = '';			//提示消息
	public $charset = 'utf-8';	//长度判断编码

	public function __construct($config = null){
		if(!empty($config)){
			foreach ($config as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * 开始验证
	 * @param string 要处理的字符串
	 * @author Colin <15070091894.com>
	 */
	public function Validate($string = null) {
		$this->string = $string;
		if(empty($string)){
			$this->string = value('post.');
		}
		$this->parString();
		if(is_array($this->parstring)){
			foreach ($this->parstring as $key => $value) {
				$this->CheckNull($value , $key);
			}
		}
	}
	
	/**
	 * 解析字符串
	 * @author Colin <15070091894.com>
	 */
	public function parString() {
		if (is_array($this->string)) {
			foreach ($this->string as $key => $value) {
				$this->parstring[$key] = trim($value);
			}
		}
	}
	
	/**
	 * 验证是否为空
	 * @author Colin <15070091894.com>
	 */
	public function CheckNull($string , $name) {
		if($this->require){
			if(empty($string) && strlen($string) == 0){
				showMessage($name . '不能为空！');
			}
		}
	}
	
	/**
	 * 验证最大长度
	 * @author Colin <15070091894.com>
	 */
	public function CheckMaxLength() {
		if (mb_strlen($this->parstring->$key, $this->charset) > $this->maxlength) {
			return $this->info;
		}
	}
	
	/**
	 * 验证最小长度
	 * @author Colin <15070091894.com>
	 */
	public function CheckMinLength() {
		if (mb_strlen($this->parstring->$key, $this->charset) < $this->minlength) {
			return $this->info;
		}
	}
	
	/**
	 * 验证长度
	 * @author Colin <15070091894.com>
	 */
	public function CheckLength() {
		foreach ($this->parstring as $key => $value) {
			for ($i = 1;$i < count($this->parstring[$key]);$i++) {
				if (mb_strlen($this->parstring[$key], $this->charset) <= $this->minlength) {
					$this->info = $key . '长度不能小于' . $this->maxlength;
					return true;
				} 
				elseif (mb_strlen($this->parstring[$key], $this->charset) > $this->maxlength) {
					$this->info = $key . '长度不能大于' . $this->maxlength;
					return true;
				}
			}
		}
	}
}
?>