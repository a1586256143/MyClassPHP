<?php
    /*
        Author : Colin,
        Creation time : 2015/8/7 21:07
        FileType : pdo操作类
        FileName : PDO.php
    */
    namespace MyClass\libs\DataBase;
    use MyClass\libs\IDataBase;

    class PDO implements IDataBase
    {
        protected $_db;
        function connect()
        {
            $this->_db = new \PDO("mysql:host=".DB_HOST.";dbname=".DB_TABS."","".DB_USER."","".DB_PASS."");
        }

        function query($_sql)
        {
            $_result = $this->_db->query($_sql);
            return $_result;
        }

        function close()
        {
            $this->_db->close($this->_db);
        }
    }
?>