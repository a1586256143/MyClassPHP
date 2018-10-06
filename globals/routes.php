<?php
/**
 * 该页面为网站路由
 * 所有路由全部在Route::add，如果要新增继续往后追加即可
 * 对应的格式为
 * '访问路由' => '访问方法'
 */
use system\Route\Route;
Route::add(array(
	'/' => 'Index@index' , 
	'/about' => 'Index@about' , 
));

//登录
Route::group('/public' , array(
	'routes' => array(
		'/login' => 'Index@login' , 
		'/register' => 'Index@register' , 
	)
));

//后台
Route::group('/public_admin' , array(
	'routes' => array(
		'/login' => 'admin\Login@index' , 
		'/loginpost' => 'admin\Login@login' , 
		'/register' => 'Index@register' , 
	)
));