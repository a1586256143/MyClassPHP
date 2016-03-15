<?php
/*
	Author : Colin,
	Creation time : 2016/3/15 12:54
	FileType :数据库工具类,不支持切换数据库操作
	FileName :DataBase.class.php
*/
namespace MyClass\libs\Tool;
class DataBase{
	protected $db;			//数据库操作句柄
	protected $sql;			//最后生成sql语句

	/**
	 * 构造方法初始化
	 * @author Colin <15070091894@163.com>
	 */
	public function __construct(){
		$this->db = \MyClass\libs\ObjFactory::getIns();
	}

	/**
	 * 创建数据库
	 * @param string $database 创建的数据库名
	 * @return int 返回创建状态
	 * @author Colin <15070091894@163.com>
	 */
	public function createDatabase($database = null){
		$this->sql = "CREATE DATABASE `$database`";
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
		$this->db->query($this->sql);
	}
}