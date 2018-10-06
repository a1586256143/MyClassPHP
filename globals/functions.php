<?php
//函数库
/**
 * 组装表名
 * @param  [type] $table [description]
 * @author Colin <amcolin@126.com>
 * @return [type]        [description]
 */
function getFix($table){
	return 'my_' . $table;
}

/**
 * 生成密码
 * @author Colin <amcolin@126.com>
 * @return [type] [description]
 */
function outPass($user , $pass){
	//加密为 首先加密密码，在把加密后的密码和用户名加密，最后把加密后的密码 用sha1加密安全码
	$key = 'myclass';
    $key = $key ? $key : 'my';
	$pass = md5($pass . $user);
	return sha1($pass . $key);
}