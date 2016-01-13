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
		$this->setConfig($config);
	}

	/**
	 * 设置配置
	 * @param config 自定义配置
	 * @author Colin <15070091894.com>
	 */
	public function setConfig($config){
		if(!empty($config)){
			foreach ($config as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * 开始验证
	 * @param string 要处理的字符串
	 * @param config 自定义配置
	 * @author Colin <15070091894.com>
	 */
	public function Validate($string = null , $config = null) {
		$this->string = $string;
		$this->setConfig($config);
		if(empty($string)){
			$this->string = values('post.');
		}
		$this->parString();
		if(is_array($this->parstring)){
			foreach ($this->parstring as $key => $value) {
				$this->name = $key;
				$this->CheckRules($value);
			}
		}else if(is_string($this->parstring)){
			$this->CheckRules($string , null);
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
		}else if(is_string($this->string)){
			$this->parstring = $this->string;
		}
	}
	
	/**
	 * 验证是否为空
	 * @param  string 要处理的值
	 * @author Colin <15070091894.com>
	 */
	public function CheckNull($string) {
		if($this->require){
			if(empty($string) && strlen($string) == 0){
				$this->_showInfo($this->name . '不能为空！');
				showMessage($this->info);
			}
		}
	}
	
	/**
	 * 验证最大长度
	 * @param  string 要处理的值
	 * @author Colin <15070091894.com>
	 */
	public function CheckMaxLength($string) {
		if (mb_strlen($string, $this->charset) > $this->maxlength) {
			$this->_showInfo($this->name . '的长度超过'.$this->maxlength.'位');
			showMessage($this->info);
		}
	}
	
	/**
	 * 验证最小长度
	 * @param  string 要处理的值
	 * @author Colin <15070091894.com>
	 */
	public function CheckMinLength($string) {
		if (mb_strlen($string, $this->charset) < $this->minlength) {
			$this->_showInfo($this->name . '的长度不能低于'.$this->minlength.'位');
			showMessage($this->info);
		}
	}

	/**
	 * 验证规则
	 * @param  string 要处理的值
	 * @author Colin <15070091894.com>
	 */
	public function CheckRules($string){
		$this->CheckNull($string);
		if(!empty($this->minlength)) $this->CheckMinLength($string);
		if(!empty($this->maxlength)) $this->CheckMaxLength($string);
	}

	/**
	 * 显示信息
	 * @param  info 要显示的消息
	 * @author Colin <15070091894.com>
	 */
	protected function _showInfo($info = null){
		if(empty($this->info)){
			$this->info = $info;
		}
	}
}
?>