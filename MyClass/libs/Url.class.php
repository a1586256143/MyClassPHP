<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 16:14
    FileType :地址处理类
    FileName : Url.class.php
*/
namespace MyClass\libs;
class Url{
    protected static $controller;
    protected static $method;
    public static $param = array();

    /**
     * 初始化方法
     * @author Colin <15070091894@163.com>
     */
    public function __construct(){
        self::$controller = Config('DEFAULT_CONTROLLER_VAR');
        self::$method = Config('DEFAULT_METHOD_VAR');
    }

    /**
     * URL模型  1
     * @author Colin <15070091894@163.com>
     */
    public function urlmodel1(){
        $controller = values('get' , self::$controller);
        $method = values('get' , self::$method);
        //定义控制器和方法常量
        self::define_controller_method($controller , $method);
        //运行地址
        self::exec_url($controller , $method);
    }

    /**
     * URL模型  2
     * @author Colin <15070091894@163.com>
     */
    public function urlmodel2(){
        $parse_path = self::getCurrentUrl();
        $count = count(self::$param);
        list($controller , $method) = $parse_path;
        //定义控制器和方法常量
        self::define_controller_method($controller , $method);
        //运行地址
        self::exec_url($controller , $method);
    }

    /**
     * 处理参数 支持两种地址模式  1 普通模式 2 pathinfo模式
     * @author Colin <15070091894@163.com>
     */
    protected static function paramS(){
        $count = count(self::$param);
        $new_pams = '';
        if($count > 0){
            if(Config('URL_MODEL') == 2){
                if(($count % 2) == 0){
                    for ($i = 0; $i < $count ; $i += 2) { 
                        $new_pams[self::$param[$i]] = self::$param[$i + 1];
                        $_GET[self::$param[$i]] = self::$param[$i + 1];
                    }
                }
            }else{
                $get = values('get.');
                foreach ($get as $key => $value) {
                    if($key == self::$controller || $key == self::$method){
                        continue;
                    }
                    $new_pams[$key] = $value;
                }
            }
        }
        return $new_pams;
    }

    /**
     * 定义控制器常量和方法常量
     * @param controller 控制器名
     * @param method 方法名
     * @author Colin <15070091894@163.com>
     */
    protected static function define_controller_method($controller , $method){
        define('CONTROLLER_NAME' , $controller);
        define('METHOD_NAME' , $method);
    }

    /**
     * 执行控制器
     * @param controller 控制器名
     * @param method 方法名
     * @author Colin <15070091894@163.com>
     */
    protected static function exec_url($controller , $method = null){
        $params = self::paramS();
        if(empty($controller)){
            C('Index' , 'index');
            exit;
        }
        C($controller , $method , $params);
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
        $path_array = explode('/' , ROOT_PATH);
        $patten = '/\./';
        foreach ($parse_path as $key => $value) {
            //处理根目录
            if(in_array($value , $path_array)){
                unset($parse_path[$key]);
            }
            $parse_path = array_merge($parse_path);
        }
        //匹配是否是index.php
        if(!empty($parse_path)){
            if(preg_match($patten,$parse_path[0],$match)){
                //确认文件是否存在
                if(!file_exists(ROOT_PATH.$parse_path[0])){
                    E('无效的入口文件'.$parse_path[0]);
                }

                unset($parse_path[0]);
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