<?php
/*
	Author : Colin,
	Creation time : 2016/1/13 22:43
	FileType :权限类
	FileName :Auth.class.php
*/
namespace system;
class Auth{
	protected $auth_other;	//验证其他
	protected $uid;			//用户UID

	/*
	-- 表的结构 `mc_auth`

	CREATE TABLE IF NOT EXISTS `mc_auth` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
	  `title` varchar(40) NOT NULL COMMENT '权限名称',
	  `url` varchar(100) DEFAULT NULL COMMENT '权限地址，需加入模块名例如Admin/User/Member',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限表' AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	-- 表的结构 `mc_auth_group`

	CREATE TABLE IF NOT EXISTS `mc_auth_group` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限组ID',
	  `name` varchar(40) NOT NULL COMMENT '组名称',
	  `auths` text COMMENT '组规则',
	  `remark` varchar(100) DEFAULT NULL COMMENT '组备注',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限组' AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	-- 表的结构 `mc_auth_users`

	CREATE TABLE IF NOT EXISTS `mc_auth_users` (
	  `gid` int(10) unsigned NOT NULL COMMENT '权限组ID',
	  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
	  PRIMARY KEY (`gid`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限用户表';

	-- --------------------------------------------------------
	 */
	
	public function __construct($auth_other = null){
		$this->auth_other = $auth_other;
		if($this->auth_other === null){
			$this->auth_other = Config('AUTH_OTHER');
		}
	}

	/**
	 * 验证权限
	 * @param  uid 用户权限id
	 * @param model 模式 1=>url模式,2=>权限id模式,3=>权限名称模式
	 * @author Colin <15070091894@163.com>
	 */
	public function check($uid = null , $model = 1){
		$auths = $this->getUserAuths($uid);
		$current = Url::getCurrentUrl();
		list($controller , $method) = $current;
		foreach ($auths as $key => $value) {
			$url = explode( '/' , $value['url']);
			$auth_method = array_pop($url);
			$auth_controller = implode('/' , $url);
			//是否验证其他 不验证，则验证表中的规则
			if($this->auth_other === false){
				$auth = D('Auth');
				if(!$find = $auth->where(array('url' => implode('/', $current)))->find()){
					return true;
				}
			}
			if($auth_controller == $controller && $auth_method == $method){
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取用户权限列表
	 * @param  uid 用户权限id
	 * @author Colin <15070091894@163.com>
	 */
	protected function getUserAuths($uid = null){
		if(empty($uid)){
			throw new MyError('请设置权限uid');
		}
		//获取用户的权限组
		$auth_users = D('AuthUsers')->where(array('uid' => $uid))->find();
		//根据权限组查找组权限
		$auth_group = D('AuthGroup')->where('id' , $auth_users['gid'])->find();
		//根据权限规则查找对应的权限
		$model = D('Auth');
		$auths = $model->field('url')->in('id' , $auth_group['auths'])->select();
		return $auths;
	}

	/**
	 * 获取所有权限列表
	 * @author Colin <15070091894@163.com>
	 */
	protected function getAuths(){
		return D('Auth')->select();
	}
}