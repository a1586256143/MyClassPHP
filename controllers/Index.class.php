<?php 
namespace controllers;
use system\Base;
use models\User;
class Index extends Base{
	/**
	 * 首页
	 * @return [type] [description]
	 */
	public function index(){
		self::view('index');
	}

	/**
	 * 关于我们
	 * @return [type] [description]
	 */
	public function about(){
		self::view('about/index');
	}

	/**
	 * 登录
	 * @return [type] [description]
	 */
	public function login(){
		if(self::$is_post){
			$user = new User();
			$status = $user->login();
			var_dump($status);
			//处理post数据
		}
		self::view('auth/login');
	}

	/**
	 * 注册
	 * @return [type] [description]
	 */
	public function register(){
		self::view('auth/register');
	}
}