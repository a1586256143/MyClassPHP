<?php
if(!defined('__URL__')) define('__URL__' , getCurrentUrl());
if(!defined('__PUBLIC__')) define('__PUBLIC__' , setPublicUrl(ltrim(APP_NAME , '.') . '/Public'));
?>