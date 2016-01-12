<?php
/*
    Author : Colin,
    Creation time : 2015/8/7 15:01
    FileType :路由类
    FileName : Route.class.php
*/
namespace MyClass\libs;
class Route{
    /**
     * 验证路由规则
     * @author Colin <15070091894@163.com>
     */
    public function CheckRoute(){
        if(Config('URL_MODEL') == 1){
            Url::urlmodel1();
        }else if(Config('URL_MODEL') == 2){
            Url::urlmodel2();
        }
    }
}
?>