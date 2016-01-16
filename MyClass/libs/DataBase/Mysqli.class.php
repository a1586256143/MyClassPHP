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
    public $affected_rows;
    
    /**
     * 连接数据库操作
     * @author Colin <15070091894@163.com>
     */
    public function connect(){
        $this->_db = new \mysqli(Config('DB_HOST'),Config('DB_USER'),Config('DB_PASS'));
        if(mysqli_connect_errno()){
            throw new MyError(mysqli_connect_error());
        }
    }

    /**
     * query方法
     * @author Colin <15070091894@163.com>
     */
    public function query($sql){
        return $this->_db->query($sql);
    }

    /**
     * 选择数据库方法
     * @author Colin <15070091894@163.com>
     */
    public function select_db($tables){
        return $this->_db->select_db($tables);
    }

    /**
     * 获取结果集方法
     * @param query 数据库执行后的操作句柄
     * @author Colin <15070091894@163.com>
     */
    public function fetch_array($query = null){
        return $query->fetch_array();
    }

    /**
     * close方法
     * @author Colin <15070091894@163.com>
     */
    public function close(){
        $this->_db->close($this->_db);
    }
}
?>