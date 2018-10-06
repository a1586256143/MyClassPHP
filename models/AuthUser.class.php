<?php 
namespace models;
use system\Model;
class AuthUser extends Model{
	/**
	 * 登陆
	 * @param  [type] $user [description]
	 * @param  [type] $pass [description]
	 * @return [type]       [description]
	 */
	public function login($user , $pass){
		$pass = outPass($user , $pass);
		$map = array('user' => $user);
		$find = $this->where($map)->getLastSql();
		if(!$find){
			return self::status(101);
		}
		if($find['pass'] != $pass){
			return self::status(102);
		}
		// 检查权限组
		$find = M('AuthUser as mu')
							->join('@auth_group_access as aga ON aga.uid = mu.id')
							->join('@auth_group as ag ON ag.id = aga.group_id')
							->where(array('mu.user' => $map['user']))
							->field('mu.id,mu.user,mu.real_name,mu.pass,mu.status,aga.group_id as gid,mu.last_time,ag.title as group_name,aga.link_table,aga.link_id')
							->find();
		if(!$find){
			return self::status(103);
		}
		return $find;
	}

	/**
	 * 状态
	 * @param  [type] $status [description]
	 * @return [type]         [description]
	 */
	protected static function status($status){
		$statusArray = array(
			'101' => '用户不存在' , 
			'102' => '密码错误' , 
			'103' => '无权限登陆' , 
			'104' => '账户异常，请联系管理员'
		);
		return array('error_code' => $status , 'error_msg' => $statusArray[$status]);
	}
}
