<?php 
namespace controllers\admin;
use system\Base;
use models\AuthUser;
class Login extends Base{
	/**
	 * 首页
	 * @return [type] [description]
	 */
	public function index(){
		self::view('admin/Login/index');
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
    		$model = new AuthUser();
    		$find = $model->login($post['user'] , $post['pass']);
    		$find = self::showResult($find);
    		//保存用户信息
			$this->saveSession($find);
			$url = 'Admin/Index/index';
			//更新用户的登录时间
			// logAppend('用户登录' , 0);
			$map = array('id' => $find['id']);
			$data = array('last_time' => time());
			$model->where($map)->save($data);
			success($url);
			//处理post数据
			return self::success('登陆成功' , '/');
		}
	}

	/**
	 * 注册
	 * @return [type] [description]
	 */
	public function register(){
		self::view('auth/register');
	}
}