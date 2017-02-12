<?php
/**
 * 表单生成
 * @author Colin <15070091894@163.com>
 */
namespace system;
class Form {
	public static $form;

	/**
	 * 打开表单
	 * @param string $action 表单提交地址
	 * @param string $method 表单方法
	 * @param string $name  表单的名称
	 * @param string $id 表单id
	 * @param string $class 表单所拥有的类
	 * @param string $attr 额外属性，例如onclick="xxx()";
	 * @author Colin <15070091894@163.com>
	 */
	public static function openForm($action = null , $method = 'post' , $name = null , $id = null , $class = null , $attr = null){
		self::$form = "<form method='$method' action='$action' name='$name' id='$id' class='$class' $attr>\n";
		self::echoForm();
		self::security();
	}

	/**
	 * 生成form校验码
	 * @param boolean $token 是否返回token
	 * @author Colin <15070091894@163.com>
	 */
	public static function security($token = false){
		for($i = 0; $i < 30; $i ++){
			$str .= dechex(mt_rand(0 , 15));
		}
		session('_token' , $str);
		if($token){
			return $str;
		}
		self::inputHidden('_token' , null , $str);
	} 

	/**
	 * 普通表单
	 * @param  string $name  input的名称
	 * @param  string $value input的值
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $placeholder h5的属性，提示作用
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function inputText($name = null , $class = null , $value = null , $id = null , $placeholder = null , $attr = null){
		if($id == null) $id = $name;
		self::input('text' , $name , $value , $class , $id , $placeholder , $attr);
	}

	/**
	 * 隐藏表单
	 * @param  string $name  input的名称
	 * @param  string $value input的值
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $placeholder h5的属性，提示作用
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function inputHidden($name = null , $class = null , $value = null , $id = null , $placeholder = null , $attr = null){
		if($id == null) $id = $name;
		self::input('hidden' , $name , $value , $class , $id , $placeholder , $attr);
	}

	/**
	 * 密码表单
	 * @param  string $name  input的名称
	 * @param  string $value input的值
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $placeholder h5的属性，提示作用
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function inputPass($name = null , $class = null , $value = null , $id = null , $placeholder = null , $attr = null){
		if($id == null) $id = $name;
		self::input('password' , $name , $value , $class , $id , $placeholder , $attr);
	}

	/**
	 * input操作,所有操作的方法集合
	 * @param  string $type  input的类型，支持的有text、hidden、button、submit
	 * @param  string $value input的值
	 * @param  string $name  input的名称
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $placeholder h5的属性，提示作用
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function input($type = null , $name = null , $value = null , $class = null , $id = null , $placeholder = null , $attr = null){
		self::$form = "\t\t<input type='$type' name='$name' placeholder='$placeholder' value='$value' id='$id' class='$class' $attr>\n";
		self::echoForm();
	}

	/**
	 * select下拉框
	 * @param  array $args 	 select 框的下拉选择项
	 * @param  string $value select的值
	 * @param  string $name  select的名称
	 * @param  string $id       select的ID属性
	 * @param  string $class select的类名
	 * @param  string $attr  select的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function select($args = array() , $value = null , $name = null , $id =null , $class = null , $attr = null){
		self::$form = "\t\t<select id='$id' name='$name' class='$class' $attr>\n";
		foreach ($args as $k => $v) {
			$selected = $k == $value ? " selected='selected' " : '';
			self::$form .= "\t\t\t\t<option value='$k' $selected>$v</option>\n";
		}
		self::$form .= "\t\t</select>\n";
		self::echoForm();
	}

	/**
	 * textarea文本域
	 * @param  string $name  textarea的名称
	 * @param  string $value textarea的值
	 * @param  string $id    textarea的ID属性
	 * @param  string $class textarea的类名
	 * @param  string $placeholder h5的属性，提示作用
	 * @param  string $attr  textarea的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function textarea($name = null , $value = null , $id = null , $class = null , $placeholder = null , $rows = 10 , $cols = 50 , $attr = null){
		self::$form = "\t\t<textarea name='$name' id='$id' class='$class' placeholder='$placeholder' rows='$rows' cols='$cols' $attr>$value</textarea>\n";
		self::echoForm();
	}

	/**
	 * 按钮
	 * @param  string $type  input的类型，支持的有text、hidden、button、submit
	 * @param  string $value input的值
	 * @param  string $name  input的名称
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function button($type = null , $class = null , $name = null , $value = '确定' , $id = null , $attr = null){
		self::$form = "\t\t<button type='$type' name='$name' id='$id' class='$class' $attr>$value</button>\n";
		self::echoForm();
	}

	/**
	 * 提交按钮
	 * @param  string $value input的值
	 * @param  string $name  input的名称
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function submitButton($value = null , $class = null , $name = null , $id = null , $attr = null){
		self::button('submit' , $class , $name , $value , $id , $attr);
	}

	/**
	 * 普通按钮，无事件
	 * @param  string $value input的值
	 * @param  string $name  input的名称
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function generalButton($value = null , $class = null , $name = null , $id = null , $attr = null){
		self::button('button' , $class , $name , $value , $id , $attr);
	}
	
	/**
	 * 重置按钮
	 * @param  string $value input的值
	 * @param  string $name  input的名称
	 * @param  string $id       input的ID属性
	 * @param  string $class input的类名
	 * @param  string $attr  input的额外属性 例如attrid = 3 ,....
	 * @author Colin <15070091894@163.com>
	 */
	public static function resetButton($value = null , $class = null , $name = null , $id = null , $attr = null){
		self::button('reset' , $class , $name , $value , $id , $attr);
	}

	/**
	 * 关闭表单
	 * @author Colin <15070091894@163.com>
	 */
	public static function closeForm(){
		self::$form = "\t\t</form>\n";
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