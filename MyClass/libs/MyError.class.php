<?php

/*
    Author : Colin,
    Creation time : 2015/8/2 16:46
    FileType :
    FileName : MyError.class.php
*/
namespace MyClass\libs;
class MyError extends \Exception{
    protected static $info;
    
    /**
     * 构造方法
     * @author Colin <15070091894@163.com>
     */
    public function __construct($message) {
        $this->message = $message;
    }

    /**
     * info初始化
     */
    protected static function info_initialize($code , $message , $file , $line){
        header('Content-type:text/html;charset="utf-8"');
        self::$info = "<div style='width:85%;height:100%;margin:0 auto;font-family:微软雅黑'>";
        self::$info .= "<ul style='list-style:none;width:100%;height:100%;'>";
        self::$info .= "<li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error级别：" . $code . "</li>";
        self::$info .= "<li style='line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error信息：<font color='red' style='word-break: break-all;'>" . $message . "</font></li>";
        self::$info .= "<li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error文件位置：" . $file . "</li>";
        self::$info .= "<li style='height:40px;line-height:40px;font-size:20px;color:#333;word-break: break-all;'>Error行数：" . $line . "</li>";
        self::$info .= "</ul></div>";
    }
    
    /**
     * 显示错误消息
     * @author Colin <15070091894@163.com>
     */
    public function __toString() {
        self::info_initialize($this->getCode() , $this->getMessage() , $this->getFile(), $this->getLine());
        //return $this->getTraceAsString();
        return self::$info;
    }
    
    public static function customError($errno, $errstr , $errfile , $errline) {
    	if (!(error_reporting() & $errno)) {
	        return;
   		}
        self::info_initialize($errno , $errstr , $errfile , $errline);
    	exit(self::$info);
    }

    public static function shutdown_function(){   	
		$e = error_get_last();
        self::customError($e['type'] , $e['message'] , $e['file'] , $e['line']);
    }
}
?>