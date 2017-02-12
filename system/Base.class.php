<?php
/**
 * 基础类，处理视图数据
 * @author Colin <15070091894@163.com>
 */
namespace system;
class Base{	
	//是否get
	protected static $is_get;
	//是否post
	protected static $is_post;
	//存储session
	protected static $session;
	//存储缓存
	protected static $cache;
	//存储get字段
	protected static $get;
	//存储post字段
	protected static $post;
	//视图
	protected static $view;

	/**
     * 初始化函数
     * @author Colin <15070091894@163.com>
     */
	public function __construct(){
		self::init();
	}

	/**
	 * 初始化
	 * @return [type] [description]
	 */
	protected static function init(){
		self::$view = View::$view;
		self::$is_get = GET;
		self::$is_post = POST;
		self::$session = session();
		self::$cache = S();
		self::$get = values('get.');
		self::$post = values('post.');
	}

	/**
	 * 重定向
	 * @param url  跳转地址
     * @param info  跳转时提示的信息
     * @param time  跳转时间
     * @author Colin <15070091894@163.com>
	 */
	public static function redirect($url , $info = '正在跳转.....', $time = 3){
		if(!empty($info)){
			echo "<meta http-equiv='refresh' content='$time; url=$url'/>";
			$this->ShowMessage($info , true);
		}
		header("Location:$url");
	}

	/**
	 * 返回json数据
	 * @param message  输出信息
     * @param url  跳转地址
     * @param status  信息状态
     * @author Colin <15070091894@163.com>
	 */
	public static function ajaxReturn($message , $url = null, $status = 0){
		$return['info'] = $message;
		$return['url'] = $url;
		$return['status'] = $status;
		ajaxReturn($return);
		exit;
	}

	/**
	 * 显示视图
	 * @return [type] [description]
	 */
	protected static function view($filename , $params){
		$filename = $filename . Config('TPL_TYPE');
		$path = APP_PATH . ltrim(self::$view->template_dir , '/') . $filename;
		if($params){
			foreach ($params as $key => $value) {
				self::$view->assign($key , $value);
			}
		}
		self::$view->display($path);
	}

	/**
	 * 提示信息模板
	 * @param message  输出信息
     * @author Colin <15070091894@163.com>
	 */
	public static function MessageTemplate($message , $type , $param = array()){
		$tpl = Config('TPL_' . $type . '_PAGE');
		if(!$tpl){
			E('请设置提示载入的页面');
		}
		if(count(explode('/' , $tpl)) <= 1){
			$tpl = MyClass . '/Tpl/' . $tpl . Config('TPL_TYPE');
		}
		self::$view->assign('param' , $param);
		self::$view->assign('message' , $message);
		self::$view->display($tpl);
	}

	/**
     * 成功后显示的对话框
     * @param message  要输出的信息
     * @param time  刷新的时间
     * @param url  要跳转的地址。为空则跳转为上一个页面
     * @author Colin <15070091894@163.com>
     */
	protected static function success($message , $url = null , $time = 3){
		self::MessageTemplate($message , 'SUCCESS' , array('url' => $url , 'time' => $time , 'status' => 1));
	}

	/**
     * 错误后显示的对话框
     * @param message  要输出的信息
     * @param time  刷新的时间
     * @param url  要跳转的地址。为空则跳转为上一个页面
     * @author Colin <15070091894@163.com>
     */
	protected static function error($message , $url = null , $time = 3){
		self::MessageTemplate($message , 'ERROR' , array('url' => $url , 'time' => $time , 'status' => 0));
	}
}
?>