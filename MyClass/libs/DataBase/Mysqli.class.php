<?php
    /*
        Author : Colin,
        Creation time : 2015/8/7 21:06
        FileType : mysqli操作类
        FileName : Mysqli.php
    */
    namespace MyClass\libs\DataBase;
    use MyClass\libs\IDataBase;

    class Mysqli implements IDataBase
    {
        protected $_db;
        
        function connect()
        {
            $this->_db = new \mysqli(DB_HOST,DB_USER,DB_PASS,DB_TABS);
        }

        function query($_sql)
        {
            return $this->_db->query($_sql);
        }

        function close()
        {
            $this->_db->close($this->_db);
        }
    }
?>