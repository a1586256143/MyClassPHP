<?php
/*
	Author : Colin,
	Creation time : 2016/1/12 20:48
	FileType :缓存类
	FileName :Cache.class.php
*/
namespace Myclass\libs;
class Cache {
	public function __construct(){
		$this->file = new File();
		$this->cache_out_suffix = Config('CACHE_OUT_SUFFIX');
		$this->cache_out_filename = Config('CACHE_OUT_FILENAME');
		$this->cache_data_dir = APP_PATH.Config('CACHE_DATA_DIR');
	}

	/**
	 * 输出文件名
	 * @param name 缓存名
	 * @param data 存储数据
	 * @author Colin <15070091894@163.com>
	 */
	public function outputFileName($name , $data){
		//组合地址
		$FileName = $this->UrlAndDefaultSuffix($name);
		//写入文件
		if(!$this->file->WriteFile($FileName , $data)){
			throw new MyError('写入文件失败！'.$FileName);
		}
	}

	/**
	 * 生成缓存默认前缀
	 * @param name 缓存名
	 * @param data 存储数据
	 * @author Colin <15070091894@163.com>
	 */
	public function cache_out_filename(){
		if(empty($this->cache_out_filename)){
			$this->cache_out_filename = substr(date('Y') , 2 , 2).'_';
		}
	}

	/**
	 * 组装地址以及默认前缀
	 * @param name 缓存名
	 * @author Colin <15070091894@163.com>
	 */
	public function UrlAndDefaultSuffix($name){
		//生成文件名默认前缀
		$this->cache_out_filename();
		//生成文件名
		$FileName = $this->cache_out_filename.md5($this->cache_out_filename.$name).$this->cache_out_suffix;
		//组合地址
		$FileName = $this->cache_data_dir.$FileName;
		return $FileName;
	}

	/**
	 * 读取缓存
	 * @param name 缓存名
	 * @param data 存储数据
	 * @author Colin <15070091894@163.com>
	 */
	public function readCache($name){
		$FileName = $this->UrlAndDefaultSuffix($name);
		$json = $this->file->OpenFile($FileName);
		dump(json_decode($json));
	}

	/**
	 * 移除缓存
	 * @param name 缓存名
	 * @author Colin <15070091894@163.com>
	 */
	public function removeCache($name){
		$FileName = $this->UrlAndDefaultSuffix($name);
		//开始删除文件
		$this->file->DeleteFile($FileName);
	}

	/**
	 * 清楚全部缓存
	 * @author Colin <15070091894@163.com>
	 */
	public function clearCache(){
		$this->file->ClearPathData($this->cache_data_dir);
	}
}