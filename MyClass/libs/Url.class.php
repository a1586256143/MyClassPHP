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

    /**
     * URL模型  1
     * @author Colin <15070091894@163.com>
     */
    public static function urlmodel1(){
        $module = values('get' , 'm');
        $controller = values('get' , 'c');
        $method = values('get' , 'a');
        if(empty($controller)){
            C('Home/Index' , $method);
            exit;
        }
        C($controller , $method);
    }

    /**
     * URL模型  2
     * @author Colin <15070091894@163.com>
     */
    public static function urlmodel2(){
        $parse_path = self::getCurrentUrl();
        if(empty($parse_path)){
            C('Index' , 'index');
            exit;
        }
        $patten = '/\./';
        //匹配是否是index.php
        if(preg_match($patten,$parse_path[1],$match)){
            @list(, , $controller , $method) = $parse_path;
        }else{
            @list(, $controller , $method) = $parse_path;
        }
        C($controller , $method);
    }

    /**
     * 获取当前url
     * @param is_return_current_url 是否返回当前地址
     * @param is_return_array       是否返回数组
     * @return array
     * @author Colin <15070091894@163.com>
     */
    public static function getCurrentUrl($is_return_current_url = false , $is_return_array = false){
        $current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $parse_url = parse_url($current_url);
        if(Config('URL_MODEL') == 1){
            $array = array_values(value('get.' , null , 'trim'));
            foreach ($array as $key => $value) {
               $parse_path[$key + 1] = $value;
            }
        }else{
            $parse_path = array_filter(explode('/', $parse_url['path']));
        }
        if($is_return_current_url) return $current_url;
        if($is_return_array) return $parse_url;
        return $parse_path;
    }
}
?>