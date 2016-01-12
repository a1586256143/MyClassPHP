<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 19:52
    FileType : 数据类
    FileName : Db.class.php
*/
namespace MyClass\libs;
//适配器接口
interface IDataBase{
    function connect();
    function query($_sql);
    function close();
}
class Db{
    protected $_db;
    /**
     * 获取数据库类
     * @author Colin <15070091894@163.com>
     */
    public function GetDB(){
        if($this->_db){
            return $this->_db;
        }else{
            if(strtolower(DB_TYPE) == 'mysqli'){
                $this->_db = new \MyClass\libs\DataBase\Mysqli();
            }else if(strtolower(DB_TYPE) == 'mysql'){
                $this->_db = new \MyClass\libs\DataBase\Mysql();
            }else if(strtolower(DB_TYPE) == 'pdo'){
                $this->_db = new \MyClass\libs\DataBase\PDO();
            }
            $this->_db->connect();
            $this->_db->query('SET NAMES '.DB_CODE);
            return $this->_db;
        }
    }

    /**
     * 关闭数据库方法
     * @author Colin <15070091894@163.com>
     */
    public function CloseDB()
    {
        $this->_db->close();
    }
}
?>