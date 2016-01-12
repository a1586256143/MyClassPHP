<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 21:06
    FileType : mysqli操作类
    FileName : Mysqli.php
*/
namespace MyClass\libs\DataBase;
use MyClass\libs\IDataBase;

class Mysqli implements IDataBase{
    protected $_db;
    
    /**
     * 连接数据库操作
     * @author Colin <15070091894@163.com>
     */
    function connect(){
        $this->_db = new \mysqli(Config('DB_HOST'),Config('DB_USER'),Config('DB_PASS'),Config('DB_TABS'));
        if(mysqli_connect_errno()){
            E(mysqli_connect_error());
        }
    }

    /**
     * query方法
     * @author Colin <15070091894@163.com>
     */
    function query($sql){
        return $this->_db->query($sql);
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