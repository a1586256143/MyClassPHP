<?php
/*
	Author : Colin,
	Creation time : 2016/3/15 12:54
	FileType :数据库工具类,不支持切换数据库操作
	FileName :DataBase.class.php
*/
namespace MyClass\libs\Tool;
class DataBase{
	protected $db;							//数据库操作句柄
	protected $sql;							//最后生成sql语句
	protected $charset = 'utf8';			//默认字符格式
	protected $encoding = 'utf8_general_ci';//默认字符编码
	protected $engine = 'MyISAM';			//默认表引擎
	protected $comment;						//表的注释
	protected $attr;						//字段组，字段信息组

	/**
	 * 构造方法初始化
	 * @author Colin <15070091894@163.com>
	 */
	public function __construct(){
		$this->db = \MyClass\libs\ObjFactory::getIns();
	}

	/**
	 * 内部set方法
	 * @param string $key   键。相对应的本类的成员
	 * @param string $value 值。相对于的本类成员的值
	 * @author Colin <15070091894@163.com>
	 */
	public function __set($key , $value){
		$this->$key = $value;
	}

	/**
	 * 创建数据库
	 * @param string $database 创建的数据库名
	 * @return int 返回创建状态
	 * @author Colin <15070091894@163.com>
	 */
	public function createDatabase($database = null){
		$this->sql = "CREATE DATABASE `$database` DEFAULT CHARACTER SET $this->charset COLLATE $this->encoding;";
		return $this->execute($this->sql);
	}

	/**
	 * 删除数据库操作
	 * @param string $database 数据库名
	 * @return string 返回删除状态
	 * @author Colin <15070091894@163.com>
	 */
	public function dropDatabase($database = null){
		$this->sql = "DROP DATABASE `$database`";
		return $this->execute($this->sql);
	}

	/**
	 * 创建表
	 * @param string $tablename 表名
	 * @return 无返回值
	 * @author Colin <15070091894@163.com>
	 */
	public function createTable($tablename = null){
		$this->sql = "CREATE TABLE IF NOT EXISTS `$tablename`(";
		$this->sql .= $this->attr;
		$this->sql .= ") ENGINE='$this->engine' DEFAULT CHARSET=$this->charset COMMENT='$this->comment' AUTO_INCREMENT=1";
	}

	/**
	 * 创建字段
	 * @author Colin <15070091894@163.com>
	 */
	public function createFields($field){
		$this->_parse_fieldinfo();
	}

	/**
	 * 字段名
	 * @param string $name 字段名
	 * @author Colin <15070091894@163.com>
	 */
	public function field_name($name = null){
		$this->attr['name'] = $name;
	}

	/**
	 * 字段长度
	 * @param int $length 字段长度
	 * @author Colin <15070091894@163.com>
	 */
	public function field_length($length = 11){
		$this->attr['length'] = $length;
	}

	/**
	 * 字段类型
	 * @param string $type 字段类型
	 * @author Colin <15070091894@163.com>
	 */
	public function field_type($type = 'varchar'){
		$this->attr['type'] = $type;
	}

	/**
	 * 是否为空
	 * @param bool $bool 是否为空，默认不为空
	 * @author Colin <15070091894@163.com>
	 */
	public function field_null($bool = false){
		$this->attr['null'] = false;
	}

	/**
	 * 是否有默认值
	 * @param string $value 默认值 为空则没有，不为空则为默认值
	 * @author Colin <15070091894@163.com>
	 */
	public function field_default($value){
		$this->attr['default'] = $value;
	}

	/**
	 * 字段备注
	 * @param string $comment 字段备注
	 * @author Colin <15070091894@163.com>
	 */
	public function field_comment($comment = null){
		$this->attr['comment'] = $comment;
	}

	/**
	 * 清空表
	 * @param string $table 清空的表名
	 * @author Colin <15070091894@163.com>
	 */
	public function truncate($table = null){
		$this->sql = "TRUNCATE $table";
		$this->execute($this->sql);
	}

	/**
	 * 执行数据库操作
	 * @param string $sql 被执行的sql语句
	 * @author Colin <15070091894@163.com>
	 */
	public function execute($sql = null){
		if(!empty($sql)){
			$this->sql = $sql;
		}
		return $this->db->query($this->sql);
	}

	/**
	 * 解析创建字段信息
	 * @return null
	 * @author Colin <15070091894@163.com>
	 */
	protected function _parse_fieldinfo(){
		dump($this->attr);
	}
}