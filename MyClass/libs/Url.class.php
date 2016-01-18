<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 16:14
    FileType :地址处理类
    FileName : Url.class.php
*/
namespace MyClass\libs;
class Url{
    /**
     * URL模型  1
     * @author Colin <15070091894@163.com>
     */
    public static function urlmodel1(){
        $controller = values('get' , 'c');
        $method = values('get' , 'a');
        if(empty($controller)){
            C('Index' , $method);
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
            unset($parse_path[1]);
        }
        $parse_path = array_merge($parse_path);

        @list($controller , $method) = $parse_path;
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
            $array = array_values(values('get.' , null , 'trim'));
            foreach ($array as $key => $value) {
               $parse_path[$key] = $value;
            }
        }else{
            $parse_path = array_filter(explode('/', $parse_url['path']));
        }
        if($is_return_current_url) return $current_url;
        if($is_return_array) return $parse_url;
        return $parse_path;
    }

    /**
     * 获取域名
     * @return array
     * @author Colin <15070091894@163.com>
     */
    public static function getSiteUrl(){
        $parse_url = self::getCurrentUrl(false , true);
        return $parse_url['scheme'].'://'.$parse_url['host'];
    }
}
?>