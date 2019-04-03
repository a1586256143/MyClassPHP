<?php
require './vendor/autoload.php';
//调试模式，上线后建议关闭，true为开启，false为关闭
define('Debug' , true);

//MyClass目录
define('MyClass' , dirname(__FILE__));

//引入核心文件
require_once './vendor/myclassphp/src/system/Run.php';