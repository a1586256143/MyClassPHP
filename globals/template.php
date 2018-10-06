<?php
//此文件为模板中使用的__常量名__格式配置文件，配置格式为
//if(!defined('常量名')) define('常量名' , '常量值');
if(!defined('__URL__')) define('__URL__' , getCurrentUrl());
if(!defined('__PUBLIC__')) define('__PUBLIC__' , Config('PUBLIC_DIR'));
if(!defined('__CSS__')) define('__CSS__' , Config('PUBLIC_DIR') . '/admin/css');
if(!defined('__JS__')) define('__JS__' , Config('PUBLIC_DIR') . '/admin/scripts');
if(!defined('__VENDOR__')) define('__VENDOR__' , Config('PUBLIC_DIR') . '/admin/vendor');
if(!defined('__IMG__')) define('__IMG__' , Config('PUBLIC_DIR') . '/admin/img');
?>