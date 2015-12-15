<?php
    /*
        Author : Colin,
        Creation time : 2015/8/7 15:01
        FileType :路由类
        FileName : Route.class.php
    */
    namespace MyClass\libs;
    class Route
    {
        //验证路由规则
        public static function CheckRoute()
        {
            if(URL_MODEL == 1)
            {
                $_c = $_GET['c'];
                $_a = $_GET['a'];
                C($_c,$_a);
            }else if(URL_MODEL == 2)
            {
                //获取当前URL
                $_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $_patten = '/.php\/(.*)(.*)/';
                preg_match_all($_patten,$_url,$_match);
                if(empty($_match[0]))
                {
                    C('Index');
                }
                if(!empty($_match[1]))
                {
                    $_url = explode('/',$_match[1][0]);
                    $_name = ucwords($_url[0]);
                    $_method = @$_url[1];
                    if(empty($_url[0]))
                    {
                        C('Index','index');
                    }else
                    {
                        empty($_method) ? C($_name,null) : C($_name,$_method);
                    }
                }
            }
        }
    }

?>