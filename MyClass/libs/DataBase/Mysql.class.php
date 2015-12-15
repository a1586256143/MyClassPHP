<?php
    /*
        Author : Colin,
        Creation time : 2015/8/7 21:06
        FileType : mysql操作类
        FileName : Mysql.php
    */
    namespace MyClass\libs\DataBase;
    use MyClass\libs\IDataBase;

    class Mysql implements IDataBase
    {
        protected $_db;
        function connect()
        {
            $this->_db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
            mysql_select_db(DB_TABS);
        }

        function query($_sql)
        {
            $_result = mysql_query($_sql,$this->_db);
            return $_result;
        }

        function close()
        {
            mysql_close($this->_db);
        }
    }
?>