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
	protected $tables = null;				//选中数据表名，可带全缀，可不带
	

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
	 * get方法
	 */
	public function __get($key){
		return $this->$key;
	}
	
	/**
	 * 选中数据表操作
	 * @param string $table 选择的数据表
	 * @author Colin <15070091894@163.com>
	 */
	public function useTable($table = null){
		$this->tables = $table;
		return $this;
	}
	
	/**
	 * 选中数据表操作
	 * @param string $table 选择的数据表
	 * @author Colin <15070091894@163.com>
	 */
	public function useTable($table = null){
		$this->tables = $table;
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
	 * @return bool 返回删除状态
	 * @author Colin <15070091894@163.com>
	 */
	public function dropDatabase($database = null){
		$this->sql = "DROP DATABASE `$database`";
		return $this->execute($this->sql);
	}
	
	/**
	 * 删除数据表操作
	 * @param string $table 数据表名
	 * @return bool 返回删除状态
	 * @author Colin <15070091894@163.com>
	 */
	public function dropTable($table = null){
		$this->sql = "DROP TABLE `$table`";
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
		$this->sql .= '`id` int unsigned not null auto_increment comment "id",';
		$this->sql .= 'PRIMARY KEY (`id`)';
		$this->sql .= ") ENGINE='$this->engine' DEFAULT CHARSET=$this->charset COMMENT='$this->comment' AUTO_INCREMENT=1";
		return $this->execute($this->sql);
	}

	/**
	 * 创建字段
	 * @author Colin <15070091894@163.com>
	 */

	public function createFields($fields = array()){
		$this->attr = $fields;
		$this->_parse_fieldinfo();
		return $this->execute($this->sql);
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
		//sql 创建字段语句
		// ALTER TABLE `表名` ADD `字段名` int(10) not null default 0 comment '字段备注';

		$this->sql = 'ALTER TABLE `' . Config('DB_PREFIX') . $this->tables . '` ADD ';
		$this->sql .= "`{$this->attr[0]}` {$this->attr[1]}({$this->attr[2]})";
	}
}