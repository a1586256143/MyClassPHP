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
    function connect(){
        $this->_db = mysql_connect(Config('DB_HOST'),Config('DB_USER'),Config('DB_PASS'));
        if(!$this->_db){
            throw new MyError(mysql_error());
        }
        mysql_select_db(Config('DB_TABS'));
    }

    /**
     * query方法
     * @author Colin <15070091894@163.com>
     */
    function query($sql){
        $_result = mysql_query($sql,$this->_db);
        return $_result;
    }

    /**
     * close方法
     * @author Colin <15070091894@163.com>
     */
    function close(){
        mysql_close($this->_db);
    }
}
?>