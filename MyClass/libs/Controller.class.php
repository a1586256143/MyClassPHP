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
	protected function display($fileName = null , $data){
		$this->view->display($fileName , $data);
	}

	/**
     * 载入公共视图文件
     * @param $fileName  要被调用的视图文件名，可以为目录
     * @author Colin <15070091894@163.com>
     */
	protected function Layout($fileName = null){
		$this->view->Layout($fileName);
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
     * 报错方法
     * @param message  要输出的错误内容
     * @author Colin <15070091894@163.com>
     */
	protected function ShowMessage($_message){
		header('Content-Type:text/html;charset=UTF-8');
		die('<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>');
	}

	/**
     * 成功后显示的对话框
     * @param message  要输出的内容
     * @param time  刷新的时间
     * @param url  要跳转的地址。为空则跳转为上一个页面
     * @author Colin <15070091894@163.com>
     */
	protected function Success($_message,$_time,$_url=null){
		header('Content-Type:text/html;charset=UTF-8');
		echo '<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>';
		echo empty($_url) ? "<meta http-equiv='refresh' content='$_time; url={$_SERVER["HTTP_REFERER"]}' />" : "<meta http-equiv='refresh' content='$_time; url=$_url' />";
		exit;
	}

	/**
     * 错误后显示的对话框
     * @param message  要输出的内容
     * @param time  刷新的时间
     * @param url  要跳转的地址。为空则跳转为上一个页面
     * @author Colin <15070091894@163.com>
     */
	protected function Error($_message,$_time,$_url=null){
		header('Content-Type:text/html;charset=UTF-8');
		echo '<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>';
		echo empty($_url) ? "<meta http-equiv='refresh' content='$_time; url={$_SERVER["HTTP_REFERER"]}' />" : "<meta http-equiv='refresh' content='$_time; url=$_url' />";
		exit;
	}
}
?>