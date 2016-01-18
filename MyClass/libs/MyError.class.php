<?php

/*
    Author : Colin,
    Creation time : 2015/8/2 16:46
    FileType :
    FileName : MyError.class.php
*/
namespace MyClass\libs;
class MyError extends \Exception
{
    
    /**
     * 构造方法
     * @author Colin <15070091894@163.com>
     */
    function __construct($message) {
        $this->message = $message;
    }
    
    /**
     * 显示错误消息
     * @author Colin <15070091894@163.com>
     */
    function __toString() {
        header('Content-type:text/html;charset="utf-8"');
        echo "<div style='width:85%;height:100%;margin:0 auto;font-family:微软雅黑'><ul style='list-style:none;width:100%;height:100%;'><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error级别：" . $this->getCode() . "</li><li style='line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error信息：<font color='red' style='word-break: break-all;'>" . $this->getMessage() . "</font></li><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error文件位置：" . $this->getFile() . "</li><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error行数：" . $this->getLine() . "</li></ul></div>";
    }
    
    public static function customError($errno, $errstr , $errfile , $errline) {
    	if (!(error_reporting() & $errno)) {
	        // This error code is not included in error_reporting
	        return;
   		}
    	header('Content-type:text/html;charset="utf-8"');
    	echo "<div style='width:85%;height:100%;margin:0 auto;font-family:微软雅黑'><ul style='list-style:none;width:100%;height:100%;'><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error级别：" . $errno . "</li><li style='line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error信息：<font color='red' style='word-break: break-all;'>" . $errstr . "</font></li><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error文件位置：" . $errfile . "</li><li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error行数：" . $errline . "</li></ul></div>";
        exit();
    }

    public static function shutdown_function(){   	
		$e = error_get_last();
		self::customError($e['type'] , $e['message'] , $e['file'] , $e['line']);
    }
}
?>