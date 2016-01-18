<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 19:50
    FileType : 工厂类  new对象
    FileName : ObjFactory.class.php
*/
namespace MyClass\libs;

class ObjFactory{
    /**
     * 创建数据库对象
     * @author Colin <15070091894@163.com>
     */
    public static function CreateDateBase(){
        return new Db();
    }

    /**
     * 创建缓存类
     * @author Colin <15070091894@163.com>
     */
    public static function CreateCache(){
        return new Cache();
    }

    /**
     * 创建模板类对象
     * @author Colin <15070091894@163.com>
     */
    public static function CreateTemplates(){
        return new Templates();
    }

    /**
     * 创建模板解析类对象
     * @param tplFile 解析的文件名
     * @author Colin <15070091894@163.com>
     */
    public static function CreateTemplatesParse($tplFile){
        return new Parser($tplFile);
    }

    /**
     * 创建控制器类
     * @param name 控制器名称
     * @author Colin <15070091894@163.com>
     */
    public static function CreateController($name){
        $obj = $name.Config('DEFAULT_CONTROLLER_SUFFIX');
        return new $obj();
    }

    /**
     * 创建模型类
     * @param name 控制器名称
     * @author Colin <15070091894@163.com>
     */
    public static function CreateModel($name){
        $model = $name.Config('DEFAULT_MODEL_SUFFIX');
        return new $model($model);
    }

    /**
     * 创建系统模型类
     * @param tables 表名
     * @author Colin <15070091894@163.com>
     */
    public static function CreateSystemModel($tables = null){
        return new Model($tables);
    }

    /**
     * 创建视图类
     * @param name 为视图文件名称
     * @author Colin <15070091894@163.com>
     */
    public static function CreateView($name){
        $obj = $name.'View';
        return new $obj();
    }

    /**
     * 创建分页类
     * @param total 总数
     * @param pagesize 分页数
     * @author Colin <15070091894@163.com>
     */
    public static function CreatePage($total , $pagesize){
        return new Page($total , $pagesize);
    }

    /**
     * 创建时间类
     * @author Colin <15070091894@163.com>
     */
    public static function CreateDate(){
        return new Date();
    }

     /**
     * 验证码类
     * @author Colin <15070091894@163.com>
     */
    public static function CreateCode(){
        return new Code();
    }
}
?>