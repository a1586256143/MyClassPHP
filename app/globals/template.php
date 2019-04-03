<?php
//此文件为模板中使用的__常量名__格式配置文件，配置格式为
//if(!defined('常量名')) define('常量名' , '常量值');
if(!defined('__URL__')) define('__URL__' , getCurrentUrl());
if(!defined('__PUBLIC__')) define('__PUBLIC__' , Config('PUBLIC_DIR'));
