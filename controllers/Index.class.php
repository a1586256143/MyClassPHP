<?php 
namespace controllers;
use system\Base;
use models\User;
use system\Model;
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
			//处理post数据
			return self::success('登陆成功' , '/');
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