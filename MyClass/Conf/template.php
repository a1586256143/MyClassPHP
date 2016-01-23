<?php
	use MyClass\libs\Url;
	/**
	 * 模板常量目录
	 */
	if(!defined('__PUBLIC__')) define('__PUBLIC__' , Url::getSiteUrl().'/Public');
	if(!defined('__URL__')) define('__URL__' , Url::getCurrentUrl(true));
?>