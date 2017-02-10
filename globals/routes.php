<?php
/**
 * 该页面为网站路由
 * 所有路由全部在Route::add，如果要新增继续往后追加即可
 * 对应的格式为
 * '访问路由' => '访问方法'
 */
use system\Route;
Route::add(array(
	'/' => '\controllers\Index@index' , 
	'/hello' => '\controllers\Admin\Web\Index@index' , 
	'/index' => '\controllers\Index@index' , 
	'/hello/user/{delete_id}' => '\controllers\index@user' , 
	'/hello/admin' => '\controllers\Index@admin' , 
	'/hello/admin/{uid}' => '\controllers\Index@admin_uid' , 
	'/hello/admin/users' => '\controllers\Index@admin_user' , 
	'/hello/admin/users/admin/users' => '\controllers\Index@admin_user_admin_user' , 
	'/hello/admin/users/{testname}' => '\controllers\Index@admin_user_param' , 
	'/hello/admin/users/{testname}/{age}' => '\controllers\Index@admin_user_age_param' , 
	'/user/{name}/{age}/{username}' => '\controllers\Index@user_param' , 
));

//其它的路由
Route::add(array(
	'/hello/world' => '\controllers\Index@hello' , 
));

//路由分组
Route::group('/admin' , array(
	'middleware' => '' , 
	'routes' => array(
		'/index' => '\controllers\Admin\Index@index'
	) , 
));