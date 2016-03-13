<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 21:06
    FileType : mysql操作类
    FileName : Mysql.php
*/
namespace MyClass\libs\DataBase;
use MyClass\libs\Db;

class Mysql extends Db{
    protected $_db;

    /**
     * 连接数据库操作
     * @author Colin <15070091894@163.com>
     */
    public function connect(){
        $this->_db = mysql_connect(Config('DB_HOST'),Config('DB_USER'),Config('DB_PASS'));
        if(!$this->_db){
            throw new \MyClass\libs\MyError(mysql_error());
        }
    }

    /**
     * 选择数据库方法
     * @author Colin <15070091894@163.com>
     */
    public function select_db($tables){
        return mysql_select_db($tables);
    }

    /**
     * query方法
     * @author Colin <15070091894@163.com>
     */
    public function query($sql){
        $_result = mysql_query($sql,$this->_db);
        return $_result;
    }

    /**
     * 获取结果集方法
     * @param query 数据库执行后的操作句柄
     * @author Colin <15070091894@163.com>
     */
    public function fetch_array($query = null){
        $fetch = mysql_fetch_array($query);
        mysql_free_result($fetch);
        return $fetch;
    }

    /**
     * 取得上一步 INSERT 操作产生的 ID 
     * @author Colin <15070091894@163.com>
     */
    public function insert_id(){
        return mysql_insert_id();
    }

    /**
     *  MySQL 操作所影响的记录行数 
     * @author Colin <15070091894@163.com>
     */
    public function affected_rows(){
        return $this->_db->affected_rows;
    }

    /**
     * close方法
     * @author Colin <15070091894@163.com>
     */
    public function close(){
        mysql_close($this->_db);
    }

    /**
     * 返回上一个操作所产生的错误信息
     * @author Colin <15070091894@163.com>
     */
    public function showerror(){
        return mysql_error();
    }

    /**
     * 获取表所有字段
     * @author Colin <15070091894@163.com>
     */
    public function getFields($table){
        $prefix = Config('DB_PREFIX');
        dump($prefix);
        //select COLUMN_NAME from information_schema.COLUMNS where table_name = 'your_table_name';
    }
}
?>