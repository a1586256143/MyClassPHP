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
		//获取文件内容
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
		//打开目录
		$dir_soule = opendir($path);
		return $dir_soule;
	}

	/**
	 * 清除目录内所有的数据
	 * @param path 要打开的目录
	 * @author Colin <15070091894@163.com>
	 */
	public function ClearPathData($path){
		//打开目录
		$dir_soule = $this->OpenFileDir($path);
		//读取目录内容
		while ($filename = readdir($dir_soule)) {
			//屏蔽. 和 .. 特殊操作符
			if(in_array($filename , array('.' , '..'))) continue;
			//删除文件
			$this->DeleteFile($path.$filename);
		}
	}

	/**
	 * 获取目录下的所有文件
	 * @param path 要打开的目录
	 * @param path 返回指定格式的文件
	 * @author Colin <15070091894@163.com>
	 */
	public function getDirAllFile($path = null , $suffix = 'php'){
		//打开目录
		$dir_soule = $this->OpenFileDir($path);
		while ($filename = readdir($dir_soule)) {
			$filepath = $path . '/' . $filename;
			//获取文件信息，主要获取文件后缀
			$info = pathinfo($filename);
			//屏蔽不是$suffix的文件
			if($info['extension'] != $suffix) continue;
			//屏蔽. 和 .. 特殊操作符
			if(in_array($filename , array('.' , '..'))) continue;
			if(is_dir($filepath)){
				//如果是文件夹则递归
				$file['dir'] = $this->getDirAllFile($filepath);
			}else{
				$file[] = $filepath;
			}	
		}
		return $file;
	}

	/**
	 * 写入文件
	 * @param filename 文件名
	 * @param data 数据
	 * @author Colin <15070091894@163.com>
	 */
	public function WriteFile($filename , $data){
		//对数据进行json编码
		$data = json_encode($data);
		//生成$filename文件
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
		//删除文件
		return @unlink($filename);
	}
}