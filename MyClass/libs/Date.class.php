<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:21
	FileType :时间类
	FileName :Date.class.php
*/
namespace MyClass\libs;
class Date{
	/**
	 * 获取时间
	 * @param timezone 时间区域
	 * @return int
	 * @author Colin <15070091894@163.com>
	 */
	public function GetDate($timezone){
		if($timezone == null){
			date_default_timezone_set('PRC');
			return time();
		}else{
			date_default_timezone_set($timezone);
			return time();
		}
	}
	
	/**
	 * 设置时间格式
	 * @param model 时间格式
	 * @param timestamp 时间戳
	 * @return string
	 * @author Colin <15070091894@163.com>
	 */
	public function SetDate($model , $timestamp){
		date_default_timezone_set('PRC');
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