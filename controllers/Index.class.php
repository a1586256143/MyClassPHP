<?php 
namespace controllers;
use system\Base;
class Index extends Base{
	public function index(){
		self::view('index');
	}
}
?>