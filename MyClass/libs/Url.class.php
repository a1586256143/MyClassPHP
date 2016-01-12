<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 16:14
    FileType :地址处理类
    FileName : Url.class.php
*/
namespace MyClass\libs;
class Url{
    //判断默认控制器和方法是否存在
    public static function CheckDefaultCA(){
        if(!DEFAULT_CONTROLLER){
            throw new MyError('默认控制器不见了！');
        }
        if(!DEFAULT_ACTION){
            throw new MyError('默认方法名不见了！');
        }
    }

    //对地址进行分割。得到控制和方法
    public static function getControllerModel(){
        Url::CheckDefaultCA();
        $_array = array();
        //获取当前地址
        $_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //正则匹配
        $_patten = '/.php\/(.*)(.*)/';
        preg_match_all($_patten,$_url,$_match);
        //以/分割
        $_url = explode('/',@$_match[1][0]);

        if(empty($_url[0]))
        {
            $_array['name'] = DEFAULT_CONTROLLER;
            $_array['method'] = DEFAULT_ACTION;
        }else 
        {
            //转换首字符大写控制器
            $_array['name'] = ucwords($_url[0]);
            //获取方法
            $_array['method'] = @$_url[1];
        }
        return $_array;
    }

    /**
     * URL模型  1
     * @author Colin <15070091894@163.com>
     */
    public static function urlmodel1(){
        $controller = value('get' , 'c');
        $method = value('get' , 'a');
        C($controller , $method);
    }

    /**
     * URL模型  2
     * @author Colin <15070091894@163.com>
     */
    public static function urlmodel2(){
        $parse_path = self::getCurrentUrl();
        $_patten = '/\./';
        //匹配是否是index.php
        if(preg_match($_patten,$parse_path[1],$match)){
            $controller = $parse_path[2];
            $method = @$parse_path[3];
        }else{
            $controller = $parse_path[1];
            $method = @$parse_path[2];
        }
        C($controller,$method);
    }

    /**
     * 获取当前url
     * @param is_return_current_url 是否返回当前地址
     * @return array
     * @author Colin <15070091894@163.com>
     */
    public static function getCurrentUrl($is_return_current_url = false){
        $current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $parse_url = parse_url($current_url);
        $parse_path = array_filter(explode('/', $parse_url['path']));
        if($is_return_current_url){
            return $current_url;
        }
        return $parse_path;
    }
}
?>