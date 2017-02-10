<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 15:01
    FileType :路由类
    FileName : Route.class.php
*/
namespace system;
use system\Url;
class Route{
    protected static $routes = array();

    /**
     * 初始化路由
     * @return [type] [description]
     */
    public static function init(){
        require_once Common . '/routes.php';
    }

    /**
     * 添加路由规则
     * @author Colin <15070091894@163.com>
     */
    public static function add($item){
        self::$routes = $item;
        self::parseRoutes();
    }

    /**
     * 路由分组
     * @return [type] [description]
     */
    public static function group($groupName , $attr = array()){
        $parse_url = Url::parseUrl();
        //处理attr路由规则
        if(!$attr || !$attr['routes']){
            E("请设置 $groupName 路由组的属性");
        }
        //给$groupName增加/
        $groupName = '/' . ltrim($groupName , '/');
        foreach ($attr['routes'] as $key => $value) {
            //给key 增加 /
            $key = '/' . ltrim($key , '/');
            self::$routes[$groupName . $key] = $value;
        }
        self::parseRoutes();
    }

    /**
     * 解析路由
     * @return [type] [description]
     */
    public static function parseRoutes(){
        $parse_url = Url::parseUrl();
        if(array_key_exists($parse_url , self::$routes)){
            $controllerOrAction = explode('@' , self::$routes[$parse_url]);
            self::execRoute($controllerOrAction[0] , $controllerOrAction[1]);
            //没有找到路由，开始找{}参数
        }else{
            $parse_url_array = explode('/' , rtrim(ltrim($parse_url , '/') , '/'));
            foreach( self::$routes as $key => $value ){
                $paramPatten = '/([\{\w\_\}]+)+/';
                if(preg_match_all($paramPatten , $key , $match)){
                    $preg_replace_param = preg_replace('/\/{([\w\_]+)}/' , '' , $key);
                    $key_array = explode('/' , rtrim(ltrim($key , '/') , '/'));
                    //位数一样
                    if(count($match[1]) == count($parse_url_array)){
                        //去除没有{}的
                        if(preg_match_all('/{([\w\_]+)}/' , implode('/' , $match[1]) , $matches)){
                            $equalLength[] = $match[1];
                        }
                        continue;
                    }
                }
            }

            //处理获取的长度数组
            foreach ($equalLength as $key => $value) {
                //拼装成 /hello/admin/{uid}
                $items = '/' . implode('/' , $value);
                //获取{的起始位置
                if($start = strpos($items , '{')){
                    //截取{后的位置，得到 /hello/admin/
                    $item = substr($items , 0 , $start);
                    //当前地址一样截取
                    $parse_url_item = substr($parse_url , 0 , $start);
                    //是否相等
                    if($item == $parse_url_item){
                        array_map('maps' , $parse_url_array , $value);
                        //执行
                        if(array_key_exists($items , self::$routes)){
                            $controllerOrAction = explode('@' , self::$routes[$items]);
                            self::execRoute($controllerOrAction[0] , $controllerOrAction[1]);
                        }else{
                            E('未知的路由' . $items);
                        }
                    }
                }else{
                    E('路由错误，请刷新' . $items);
                }
            }  
        }
    }

    /**
     * 执行路由
     * @return [type] [description]
     */
    public static function execRoute($namespace , $method){
        $controller = new $namespace;
        //分割数组
        $class_name_array = explode('\\' , $namespace);
        //得到controllers\index 中的 index
        $get_class_name = array_pop($class_name_array);
        //拼接路径，并自动将路由中的index转换成Index
        $controller_path = APP_PATH . ltrim(implode('/' , $class_name_array) , '/') . '/' . ucfirst($get_class_name) . '.class.php';
        //是否存在控制器
        if(!file_exists($controller_path)){
            throw new \system\MyError($get_class_name . ' 控制器不存在！');
        }
        //控制器方法不否存在
        if(!method_exists($controller , $method)){
            throw new \system\MyError($method.'() 这个方法不存在');
        }
        //反射
        $ReflectionMethod = new \ReflectionMethod($controller , $method);
        $method_params = $ReflectionMethod->getParameters($method);
        //处理参数返回
        $param = array_filter(values('get.'));
        if(!empty($param)){
            if(!empty($method_params)){
                foreach ($method_params as $key => $value) {
                    $var[$value->name] = $param[$value->name];
                }
                return $ReflectionMethod->invokeArgs($controller , array_filter($var));
            }
        }
        return $controller->$method();
    }

    /**
     * 验证路由规则
     * @author Colin <15070091894@163.com>
     */
    public function startRoute(){
        require_once Common . '/routes.php';
    }
}
?>