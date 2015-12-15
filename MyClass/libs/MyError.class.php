<?php
    /*
        Author : Colin,
        Creation time : 2015/8/2 16:46
        FileType :
        FileName : MyError.class.php
    */
	namespace Myclass\libs;
	class MyError extends \Exception
	{
		function __construct($_message)
		{
			$this->message = $_message;
		}
		
		function __toString()
		{
			return "<div style='width:85%;height:100%;margin:0 auto;font-family:微软雅黑'><ul style='list-style:none;width:100%;height:100%;'><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error级别：".$this->getCode()."</li><li style='line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error信息：<font color='red' style='word-break: break-all;'>".$this->getMessage()."</font></li><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error文件位置：".$this->getFile()."</li><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error行数：".$this->getLine()."</li></ul></div>";
		}
	}
?>