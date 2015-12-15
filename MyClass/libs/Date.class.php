<?php
	/*
		Author : Colin,
		Creation time : 2015-8-1 10:30:21
		FileType :时间类
		FileName :Date.class.php
	*/
	namespace MyClass\libs;
	class Date
	{
		public function GetDate($_timezone)
		{
			if($_timezone == null)
			{
				date_default_timezone_set('PRC');
				return time();
			}else
			{
				date_default_timezone_set($_timezone);
				return time();
			}
		}
		
		public function SetDate($_model,$_timestamp)
		{
			date_default_timezone_set('PRC');
			if($_model == null)
			{
				return date('Y-m-d H:i:s',$_timestamp);
			}elseif($_timestamp == null)
			{
				return date($_model,time());
			}elseif($_model == null && $_timestamp != null)
			{
				return date('Y-m-d H:i:s',$_timestamp);
			}elseif($_model != null && $_timestamp == null)
			{
				return date($_model,time());
			}elseif($_model != null && $_timestamp != null)
			{
				return date($_model,$_timestamp);
			}
		}
	}