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

    /**
     * 初始化
     * @author Colin <15070091894@163.com>
     */
    public function __construct(){
        $this->url = new Url();
    }

    /**
     * 验证路由规则
     * @author Colin <15070091894@163.com>
     */
    public function startRoute(){
        if(Config('URL_MODEL') == 1){
            $this->url->urlmodel1();
        }
    }
}
?>