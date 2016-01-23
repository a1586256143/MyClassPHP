<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 21:06
    FileType : mysql操作类
    FileName : Mysql.php
*/
namespace MyClass\libs\DataBase;
use MyClass\libs\IDataBase;

class Mysql implements IDataBase{
    protected $_db;

    /**
     * 连接数据库操作
     * @author Colin <15070091894@163.com>
     */
    public function connect(){
        $this->_db = mysql_connect(Config('DB_HOST'),Config('DB_USER'),Config('DB_PASS'));
        if(!$this->_db){
            throw new MyError(mysql_error());
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
     * close方法
     * @author Colin <15070091894@163.com>
     */
    public function close(){
        mysql_close($this->_db);
    }
}
?>