<?php 
namespace controllers;
use system\Controller;
class Index extends Controller{
	public function index(){
		echo 'index action';
		dump(getCurrentUrl(true));
		$this->display('Index/index');
	}

	public function admin(){
		echo 'this is hello/admin ';
	}

	public function admin_user(){
		echo 'this is hello/admin_user';
	}

	public function admin_user_admin_user(){
		echo 'this is /hello/admin/users/admin/users';
	}

	public function admin_user_param($testname = null){
		echo 'this is hello/admin_user_param username = ' , $testname ;
	}

	public function admin_user_age_param($testname = null , $age = null){
		echo 'this is hello/admin_user_age_param username = ' , $testname , ' , age is = ' , $age ;
	}

	public function admin_uid($uid = null){
		echo 'this is /hello/admin uid = ' . $uid;
	}

	public function hello(){
		echo 'this is hello/world';
	}

	public function user($delete_id = null){
		echo 'this is user delete_id = ' , $delete_id;
	}

	public function user_param($name , $age , $username){
		echo 'this is user params = ' , $name , ',' , $age , ',' , $username; 
	}
}
?>