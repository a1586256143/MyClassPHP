<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:21
	FileType :时间类
	FileName :Date.class.php
*/
namespace system;
class Date{
	public static function set_timezone($timezone = 'Asia/Shanghai'){
		date_default_timezone_set($timezone);
	}

	/**
	 * 获取时间
	 * @param timezone 时间区域
	 * @return int
	 * @author Colin <15070091894@163.com>
	 */
	public static function getDate($timezone = null){
		if($timezone == null){
			date_default_timezone_set(Config('DATE_DEFAULT_TIMEZONE'));
		}else{
			date_default_timezone_set($timezone);
		}
		return time();
	}
	
	/**
	 * 设置时间格式
	 * @param model 时间格式
	 * @param timestamp 时间戳
	 * @return string
	 * @author Colin <15070091894@163.com>
	 */
	public static function setDate($model , $timestamp){
		date_default_timezone_set(Config('DATE_DEFAULT_TIMEZONE'));
		if($model == null){
			return date('Y-m-d H:i:s' , $timestamp);
		}elseif($timestamp == null){
			return date($model , time());
		}elseif($model == null && $timestamp != null){
			return date('Y-m-d H:i:s' , $timestamp);
		}elseif($model != null && $timestamp == null){
			return date($model , time());
		}elseif($model != null && $timestamp != null){
			return date($model , $timestamp);
		}
	}
}