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
    function query($sql);
    function select_db($tables);
    function fetch_array($query);
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
            if(strtolower(Config('DB_TYPE')) == 'mysqli'){
                $this->_db = new \MyClass\libs\DataBase\Mysqli();
            }else if(strtolower(Config('DB_TYPE')) == 'mysql'){
                $this->_db = new \MyClass\libs\DataBase\Mysql();
            }else if(strtolower(Config('DB_TYPE')) == 'pdo'){
                $this->_db = new \MyClass\libs\DataBase\PDO();
            }
            $this->_db->connect();
            $this->CheckDatabase();
            $this->_db->query('SET NAMES '.Config('DB_CODE'));
            return $this->_db;
        }
    }

    /**
     * 确认数据库是否存在
     * @author Colin <15070091894@163.com>
     */
    public function CheckDatabase(){
        $database = Config('DB_TABS');
        $result = $this->_db->select_db($database);
        if(!$result){
            throw new MyError('数据库不存在或数据库名不正确！'.$database);
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