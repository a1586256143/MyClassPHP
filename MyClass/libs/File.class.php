<?php
/*
	Author : Colin,
	Creation time : 2016/1/12 21:01
	FileType :文件类
	FileName :File.class.php
*/
namespace MyClass\libs;
class File {
	protected $file;

	/**
	 * 打开文件
	 * @param filename 文件名
	 * @author Colin <15070091894@163.com>
	 */
	public function OpenFile($filename){
		$file_resoule = file_get_contents($filename);
		if(!$file_resoule){
			return null;
		}
		return $file_resoule;
	}

	/**
	 * 打开目录
	 * @param path 要打开的目录
	 * @author Colin <15070091894@163.com>
	 */
	public function OpenFileDir($path){
		$dir_soule = opendir($path);
		return $dir_soule;
	}

	/**
	 * 清除目录内所有的数据
	 * @param path 要打开的目录
	 * @author Colin <15070091894@163.com>
	 */
	public function ClearPathData($path){
		$dir_soule = $this->OpenFileDir($path);
		while ($filename = readdir($dir_soule)) {
			if($filename == '.' || $filename == '..'){
				continue;
			}
			$this->DeleteFile($path.$filename);
		}
	}

	/**
	 * 写入文件
	 * @param filename 文件名
	 * @param data 数据
	 * @author Colin <15070091894@163.com>
	 */
	public function WriteFile($filename , $data){
		$data = json_encode($data);
		$fileobj = file_put_contents($filename, $data);
		if($fileobj){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 删除文件
	 * @author Colin <15070091894@163.com>
	 */
	public function DeleteFile($filename){
		return @unlink($filename);
	}
}