<?php
/*
	Author : Colin,
	Creation time : 2016/1/13 18:26:30
	FileType :空操作类
	FileName :Cache.class.php
*/
namespace MyClass\libs\Controller;
use MyClass\libs\Controller;
class NoValueController extends Controller{
	public function _error(){

	}

	public function _showError($string , $type){
		dump($string);
		dump($type);
	}
}