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
    function insert_id();
    function affected_rows();
    function close();
    function showerror();
}
class Db{
    protected static $db;
    /**
     * 获取数据库类
     * @author Colin <15070091894@163.com>
     */
    public static function getIns(){
        if(self::$db){
            return self::$db;
        }else{
            if(strtolower(Config('DB_TYPE')) == 'mysqli'){
                self::$db = new \MyClass\libs\DataBase\Mysqli();
            }else if(strtolower(Config('DB_TYPE')) == 'mysql'){
                self::$db = new \MyClass\libs\DataBase\Mysql();
            }else if(strtolower(Config('DB_TYPE')) == 'pdo'){
                self::$db = new \MyClass\libs\DataBase\PDO();
            }
            self::$db->connect();
            self::CheckDatabase();
            self::$db->query('SET NAMES '.Config('DB_CODE'));
            return self::$db;
        }
    }

    /**
     * 确认数据库是否存在
     * @author Colin <15070091894@163.com>
     */
    public static function CheckDatabase(){
        $database = Config('DB_TABS');
        $result = self::$db->select_db($database);
        if(!$result){
            throw new MyError('数据库不存在或数据库名不正确！'.$database);
        }
    }

    /**
     * 关闭数据库方法
     * @author Colin <15070091894@163.com>
     */
    public static function CloseDB(){
        self::$db->close();
    }
}
?>