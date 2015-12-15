<?php
    /*
        Author : Colin,
        Creation time : 2015/8/7 19:50
        FileType : 工厂类  new对象
        FileName : ObjFactory.class.php
    */
    namespace MyClass\libs;

    class ObjFactory
    {
        //创建数据库对象
        static function CreateDateBase()
        {
            return new Db();
        }

        //创建模板类对象
        static function CreateTemplates()
        {
            return new Templates();
        }

        //创建模板解析类对象
        static function CreateTemplatesParse($_tplFile)
        {
            return new Parser($_tplFile);
        }

        //创建控制器类
        //@_name为控制器名
        static function CreateController($_name)
        {
            $_obj = $_name.'Controller';
            return new $_obj();
        }

        //创建模型类
        //@_name为模型名
        static function CreateModel($_name)
        {
            $_obj = $_name.'Model';
            return new $_obj($_name);
        }

        //创建模型类
        //@_tables 为数据表名称
        static function CreateSystemModel($_tables)
        {
            return new Model($_tables);
        }

        //创建视图类
        //@_name为视图文件名称
        static function CreateView($_name)
        {
            $_obj = $_name.'View';
            return new $_obj();
        }

        //创建分页类
        //@_total 总数
        //@_pagesize 分页数
        static function CreatePage($_total,$_pagesize)
        {
            return new Page($_total,$_pagesize);
        }

        //创建时间类
        static function CreateDate()
        {
            return new Date();
        }

        //验证码类
        static function CreateCode()
        {
            return new Code();
        }
    }

?>