<?php
/*
    Author : Colin,
    Creation time : 2015-8-1 10:30:21
    FileType :控制器父类
    FileName :Controller.class.php
*/
namespace MyClass\libs;
class Controller{
	protected $view;
	
	/**
     * 初始化函数
     * @author Colin <15070091894@163.com>
     */
	public function __construct(){
		$this->view = View::$view;
	}

	/**
     * display函数
     * @param $fileName  要被调用的视图文件名
     * @author Colin <15070091894@163.com>
     */
	protected function display($fileName = null){
		$fileName = $this->getFilenameOrPath($fileName);
		$this->view->display($fileName);
	}

	/**
	 * 获取文件名以及路径
	 * @author Colin <15070091894@163.com>
	 */
	protected function getFilenameOrPath($FileName){
		if(empty($FileName)){
			$FileName = METHOD_NAME ? METHOD_NAME : Config('DEFAULT_METHOD');
		}
		$FileName = ltrim($FileName , '/');
		$controller = CONTROLLER_NAME ? CONTROLLER_NAME : Config('DEFAULT_CONTROLLER');
		$module = defined('CURRENT_MODULE') ? CURRENT_MODULE : Config('DEFAULT_MODULE');
		$FileName = $FileName . Config('TPL_TYPE');
		if(count(explode('/' , $FileName)) > 1){
			$path = $FileName;
		}else{
			$path = APP_PATH . '/' . $module . $this->view->template_dir . $controller . '/' . $FileName;
		}
		return $path;
	}

	/**
     * 展示效果文件
     * @param $file  要被调用的视图文件名
     * @author Colin <15070091894@163.com>
     */
	public function showdisplay($file){
		if(empty($file)){
			E('模板文件不能为空');
		}
		$this->view->showdisplay($file);
	}

	/**
     * 注入变量
     * @param name  模板中要被输出的变量名
     * @param value  在模板中输出的值
     * @author Colin <15070091894@163.com>
     */
	protected function assign($name , $value){
		$this->view->assign($name , $value);
	}

	/**
	 * 重定向
	 * @param url  跳转地址
     * @param info  跳转时提示的信息
     * @param time  跳转时间
     * @author Colin <15070091894@163.com>
	 */
	public function redirect($url , $info = '正在跳转.....', $time = 3){
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
	public function ajaxReturn($message , $url = null, $status = 0){
		$return['info'] = $message;
		$return['url'] = $url;
		$return['status'] = $status;
		ajaxReturn($return);
		exit;
	}

	/**
	 * 提示信息模板
	 * @param message  输出信息
     * @author Colin <15070091894@163.com>
	 */
	public function MessageTemplate($message , $type , $param = array()){
		$tpl = Config('TPL_' . $type . '_PAGE');
		if(!$tpl){
			E('请设置提示载入的页面');
		}
		if(count(explode('/' , $tpl)) <= 1){
			$tpl = MyClass . '/Tpl/' . $tpl;
		}
		$this->assign('param' , $param);
		$this->assign('message' , $message);
		$this->display($tpl);
	}

	/**
     * 成功后显示的对话框
     * @param message  要输出的信息
     * @param time  刷新的时间
     * @param url  要跳转的地址。为空则跳转为上一个页面
     * @author Colin <15070091894@163.com>
     */
	protected function success($message , $url = null , $time = 3){
		$this->MessageTemplate($message , 'SUCCESS' , array('url' => $url , 'time' => $time , 'status' => 1));
	}

	/**
     * 错误后显示的对话框
     * @param message  要输出的信息
     * @param time  刷新的时间
     * @param url  要跳转的地址。为空则跳转为上一个页面
     * @author Colin <15070091894@163.com>
     */
	protected function error($message , $url = null , $time = 3){
		$this->MessageTemplate($message , 'ERROR' , array('url' => $url , 'time' => $time , 'status' => 0));
	}
}
?>