<?php
	/*
		Author : Colin,
		Creation time : 2015-8-1
		FileType : 
		FileName : 
	*/

	function ShowMessage($_message)
	{
		header('Content-Type:text/html;charset=UTF-8');
		die('<div style="width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;"><dl style="padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;"><dt style="padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;">MyClass提示信息</dt><dd style="padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0">'.$_message.'</dd></dl></div>');
	}

	//C方法实例化控制器
	function C($_name,$_method=null)
	{
		if(!file_exists(APP_PATH.'/Controller/'.$_name.'Controller.class.php'))
		{
			ShowMessage($_name.'控制器不存在！');
		}
		if(empty($_method))
		{
			$_controller = \MyClass\libs\ObjFactory::CreateController($_name);

			$_controller->index();
		}else
		{
			$_controller = \MyClass\libs\ObjFactory::CreateController($_name);

			if(!method_exists($_controller,$_method))
			{
				ShowMessage($_method.'这个方法不存在');
			}
			return $_controller->$_method();
		}
	}
	
	//M方法实例化模型
	function M($_name = null)
	{
		if(empty($_name))
		{
			return $_obj = MyClass\libs\ObjFactory::CreateSystemModel();
		}else 
		{
			return $_obj = MyClass\libs\ObjFactory::CreateSystemModel($_name);
		}
	}
	
	//D方法实例化数据库模型
	function D($_name,$_method=null)
	{
		if(!$_name)
		{
			ShowMessage('D方法必须传递一个值');
		}
		$_obj = \MyClass\libs\ObjFactory::CreateModel($_name);

		if(empty($_method))
		{
			return $_obj;
		}else
		{
			return $_obj->$_method();
		}
		
	}
	
	//E方法对错误进行提醒
	function E($_message)
	{
		throw new \MyClass\libs\MyError($_message);
	}
	
	//跳转方法
	function U($url)
	{
		$_subjecturl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
		$_newurl =$_subjecturl.'/'.$url;
		header('Location:'.$_newurl);
	}

?>