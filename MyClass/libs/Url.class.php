<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 16:14
    FileType :地址处理类
    FileName : Url.class.php
*/
namespace MyClass\libs;
class Url{
    public static $param = array();

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
        $count = count(self::$param);
        $new_pams = '';
        if($count > 0){
            if($count % 2 == 0){
                for ($i = 0; $i < $count ; $i += 2) { 
                    $new_pams[self::$param[$i]] = self::$param[$i + 1];
                    $_GET[self::$param[$i]] = self::$param[$i + 1];
                }
            }
        }
        @list($controller , $method) = $parse_path;
        define('CONTROLLER_NAME' , $controller);
        define('METHOD_NAME' , $method);
        C($controller , $method , $new_pams);
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
            $parse_path = array_filter(explode('/', substr($parse_url['path'] , 0 , 1)));
        }
        $patten = '/\./';
        //匹配是否是index.php
        if(!empty($parse_path)){
            if(preg_match($patten,$parse_path[1],$match)){
                unset($parse_path[1]);
            }
            $parse_path = array_merge($parse_path);
        }
        if(empty($parse_path)){
            $parse_path = array(Config('DEFAULT_CONTROLLER') , Config('DEFAULT_METHOD'));
        }
        self::$param = $parse_path;
        if($is_return_current_url) return $current_url;
        if($is_return_array) return $parse_url;
        return $parse_path;
    }

    /**
     * 获取域名
     * @param  scheme 是否返回域名
     * @return array
     * @author Colin <15070091894@163.com>
     */
    public static function getSiteUrl($scheme = false){
        $parse_url = self::getCurrentUrl(false , true);
        return $scheme ? $parse_url['scheme'].'://'.$parse_url['host'] : '';
    }
}
?>