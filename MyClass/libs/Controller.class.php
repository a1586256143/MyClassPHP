<?php
	/*
	    Author : Colin,
	    Creation time : 2015-8-1 10:30:21
	    FileType :控制器父类
	    FileName :Controller.class.php
	*/
	namespace MyClass\libs;
	class Controller
	{
		protected $view;
	
		//初始化函数
		public function __construct()
		{
			$this->view = View::$view;
		}

		/*
	     *display函数
	     * $_filename文件名
	     * */
		protected function display($_filename=null)
		{
			$this->view->display($_filename);
		}

		/*
	     * 载入公共视图文件
	     *@$_filename  要被调用的视图文件名，可以为目录/$_filename
	     * */
		protected function Layout($_filename=null)
		{
			$this->view->Layout($_filename);
		}

		/*
	     * 展示效果文件
	     *@_file  要被调用的视图文件名
	     * */
		public function showdisplay($_file)
		{
			if(empty($_file))
			{
				throw new MyError('模板文件不能为空');
			}
			$this->view->showdisplay($_file);
		}
	
		/*
	     * 注入变量
	     *@_name  模板中要被输出的变量名
	     * @_value 在模板中输出的值
	     * */
		protected function assign($_name,$_value)
		{
			$this->view->assign($_name,$_value);
		}
	
		/*
	     *报错方法
	     *@_message 要输出的错误内容
	     * */
		protected function ShowMessage($_message)
		{
			header('Content-Type:text/html;charset=UTF-8');
			die('<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>');
		}

		/*
	     *成功后显示的对话框
	     *@_message 要输出的内容
		 *@_time 刷新的时间
		 *@_url  要跳转的地址。为空则跳转为上一个页面
	     * */
		protected function Success($_message,$_time,$_url=null)
		{
			header('Content-Type:text/html;charset=UTF-8');
			echo '<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>';
			echo empty($_url) ? "<meta http-equiv='refresh' content='$_time; url={$_SERVER["HTTP_REFERER"]}' />" : "<meta http-equiv='refresh' content='$_time; url=$_url' />";
			exit;
		}

		/*
	     *错误后显示的对话框
	     *@_message 要输出的内容
		 *@_time 刷新的时间
		 *@_url  要跳转的地址。为空则跳转为上一个页面
	     * */
		protected function Error($_message,$_time,$_url=null)
		{
			header('Content-Type:text/html;charset=UTF-8');
			echo '<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>';
			echo empty($_url) ? "<meta http-equiv='refresh' content='$_time; url={$_SERVER["HTTP_REFERER"]}' />" : "<meta http-equiv='refresh' content='$_time; url=$_url' />";
			exit;
		}
	}
?>