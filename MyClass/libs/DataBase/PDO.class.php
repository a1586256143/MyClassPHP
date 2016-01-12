<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 21:07
    FileType : pdo操作类
    FileName : PDO.php
*/
namespace MyClass\libs\DataBase;
use MyClass\libs\IDataBase;

class PDO implements IDataBase{
    protected $_db;

    /**
     * 连接数据库操作
     * @author Colin <15070091894@163.com>
     */
    function connect(){
        $this->_db = new \PDO("mysql:host=".Config('DB_HOST').";dbname=".Config('DB_TABS')."","".Config('DB_USER')."","".Config('DB_PASS')."");
    }

    /**
     * query方法
     * @author Colin <15070091894@163.com>
     */
    function query($sql){
        $_result = $this->_db->query($sql);
        return $_result;
    }

    /**
     * close方法
     * @author Colin <15070091894@163.com>
     */
    function close(){
        $this->_db->close($this->_db);
    }
}
?>