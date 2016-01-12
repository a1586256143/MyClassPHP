<?php
/*
    Author : Major,
    Creation time : 2016/1/12 20:14
    FileType :上传处理类
    FileName : Upload.class.php
*/
namespace MyClass\libs;
class Upload{
	public $path;						//上传文件保存路径
	public $allowtype;					//设置上传文件类型
	public $maxsize;					//限制文件上传大小
	public $israndname;					//设置是否随机重命名文件
	public $originName;					//源文件名
	public $tmpFIlename;				//临时文件名
	public $FileType;					//文件类型
	public $fileSize;					//文件大小
	public $newFileName;				//新文件名
	public $errorNum = 0;				//错误号
	public $errorMess;					//错误报告消息

	public function __construct(){
		$this->path = Config('UPLOAD_DIR');
		$this->allowtype = Config('UPLOAD_TYPE');
		$this->maxsize = Config('UPLOAD_MAXSIZE'); 
		$this->israndname = Config('UPLOAD_ISRANDNAME');
	}

	/**
	 * 文件上传
	 * @author Major <1450494434@qq.com>
	 */
	public function upload(){
		$return = true;
		//检测文件名是否合法

	}

	/**
	 *检测是否有存在文件上传的目录
	 * @author Major <1450494434@qq.com>
	 */
	public function checkFilePath(){
		if (empty($this->path)) {
			$this->errorNum = -5;
			return false;
		}
		if(!file_exists($this->path) || is_writable($this->path)){
			if (!@mkdir($this->path,0755)) {
				$this->errorNum = -4;
				return false;
			}
		}
		return true;
	}

	/**
	 *设置随机文件名
	 * @author Major <1450494434@qq.com>
	 */
    public function proRandName() {    
      $this->fileName = date('YmdHis')."_".rand(100,999);    
      return $this->fileName.'.'.$this->fileType; 
    }

    /**
	 *检测上传的文件是合法的类型
	 * @author Major <1450494434@qq.com>
	 */
	public function checkFileType(){

	}

    /**
	 *检测上传的文件是否允许的大小
	 * @author Major <1450494434@qq.com>
	 */
	public function checkSize(){
		if($this->fileSize > $this->maxsize){
			$this->errorNum = -2;
			return false;
		} else{
			return true;
		}
	}
}