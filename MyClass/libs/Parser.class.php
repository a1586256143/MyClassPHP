<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:21
	FileType :模板解析类
	FileName :Parser.class.php
*/
namespace MyClass\libs;

class Parser{
	//字段，保存模板内容
	private $_tpl;
	//构造方法用于获取模板内容
	public function __construct($tplFile){
		if(!file_exists($tplFile)){
			throw new MyError('读取模板文件出错！'.$this->_tpl);
		}
		$this->_tpl = file_get_contents($tplFile);
	}

	/**
     * 解析函数
     * @author Colin <15070091894@163.com>
     */
	private function parFunc(){
		$patten = "/\{\:([\w]+)\(\'([\w\/]+)\'\,([\w\(\'\'\w\=\>\$\[\]\s)]+)\)\}/i";
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php echo $1('$2' , $3) ?>",$this->_tpl);
		}
	}
	
	/**
     * 解析普通变量
     * @author Colin <15070091894@163.com>
     */
	private function parVar(){
		$patten = '/\{\$([\w]+)(\[\'[\w]+\'\])*\}/';
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php echo \$this->_vars['$1']$2 ?>",$this->_tpl);
		}
		//解析三元
		$patten1 = '/\{\$([\w]+)((\[\'[\w]+\'\])*\s\?\s[\'\'\w]+\s\:\s[\'\'\w]+)\}/';
		if(preg_match($patten1,$this->_tpl,$match)){
			$this->_tpl = preg_replace($patten1,"<?php echo \$this->_vars['$1']$2 ?>",$this->_tpl);
		}
	}

	/**
     * 解析IF语句
     * @author Colin <15070091894@163.com>
     */
	private function parIF(){
		//if语句开头的正则
		$_ifpatten = '/\{if\s+\$([\w]+)*\s(=*|>=|<=|>|<|>)\s([\w])?\}/';
		//endif语句的结束
		$_endifpatten = '/\{\/if\}/';
		//else语句查询
		$_elsepatten = '/\{else\}/';
		//匹配查找
		if(preg_match($_ifpatten,$this->_tpl)){
			//查找是否关闭IF
			if(preg_match($_endifpatten,$this->_tpl)){
				//替换
				$this->_tpl = preg_replace($_ifpatten,"<?php if(\$this->_vars['$1'] $2 '$3'){ ?>",$this->_tpl);
				$this->_tpl = preg_replace($_endifpatten,"<?php } ?>",$this->_tpl);
				//有else就替换了
				if(preg_match($_elsepatten,$this->_tpl)){
					$this->_tpl = preg_replace($_elsepatten,"<?php }else{ ?>",$this->_tpl);
				}
			}else{
				throw new MyError('if语句未关闭！'.$this->_tpl);
			}
		}
	}

	/**
     * 解析foreach语句
     * @author Colin <15070091894@163.com>
     */
	public function parForeach(){
		$patten = '/\{foreach\s+name="([\w]+)"\s+id="([\w]+)"\}/';
		$_endpatten = '/\{\/foreach\}/';
		$pattenvar = '/\{\$([\w]+)([\[\'\'\]\w\-\>\+]*)\}/';
		if(preg_match($patten,$this->_tpl)){
			if(preg_match($_endpatten,$this->_tpl)){
				$this->_tpl = preg_replace($patten,"<?php foreach(\$this->_vars['$1'] as \$key=>\$$2): ?>",$this->_tpl);
				$this->_tpl = preg_replace($_endpatten,"<?php endforeach; ?>",$this->_tpl);
				if(preg_match($pattenvar,$this->_tpl)){
					$this->_tpl = preg_replace($pattenvar,"<?php echo \$$1$2 ?>",$this->_tpl);
				}
			}else {
				throw new MyError('foreach语句未关闭！'.$this->_tpl);
			}
		}
	}

	/**
     * 解析include语句
     * @author Colin <15070091894@163.com>
     */
	private function parinclude(){
		$patten = '/\{include\s+file=(\"|\')([\w\.\-\/]+)(\"|\')\}/';
		if(preg_match($patten,$this->_tpl,$file)){
			$filename = $file[2];
			$filepath = APP_PATH.Config('TPL_DIR').$filename.Config('TPL_TYPE');
			if(!file_exists($filepath) || empty($file)){
				throw new MyError($filepath.'包含文件出错！请检查！');
			}
			$patten1 = '/Layout/';
			if(preg_match($patten1,$filename)){
				$this->_tpl = preg_replace($patten,"<?php \$this->Layout('$2') ?>",$this->_tpl);
			}else{
				$this->_tpl = preg_replace($patten,"<?php include Config('TPL_DIR').'$2'.Config('TPL_TYPE'); ?>",$this->_tpl);
			}
		}
	}

	/**
     * 解析系统变量
     * @author Colin <15070091894@163.com>
     */
	private function parConfig(){
		$patten = '/<!--\{([\w]+)\}-->/';
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php echo \$this->_config['$1']; ?>",$this->_tpl);
		}
	}
	
	/**
     * 解析注释
     * @author Colin <15070091894@163.com>
     */
	private function parCommon(){
		$patten = '/\{#\}(.*)\{#\}/';
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php /* $1 */ ?>",$this->_tpl);
		}
	}

	/**
     * 解析count方法
     * @author Colin <15070091894@163.com>
     */
	private function parCount(){
		$patten = '/\{count\(\$([\w]+)\)\}/';
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php echo count(\$this->_vars['$1']) ?>",$this->_tpl);
		}
	}

	/**
     * 解析exit;
     * @author Colin <15070091894@163.com>
     */
	public function parExit(){
		$patten = '/\{exit\}/';
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php echo exit; ?>",$this->_tpl);
		}
	}

	/**
     * 解析print方法
     * @author Colin <15070091894@163.com>
     */
	private function parPrint(){
		$patten = '/\{print\(\$([\w]+)\)\}/';
		if(preg_match($patten,$this->_tpl)){
			$this->_tpl = preg_replace($patten,"<?php print_r(\$this->_vars['$1']) ?>",$this->_tpl);
		}
	}

	/**
     * 解析__函数
     * @author Colin <15070091894@163.com>
     */
    private function parDefault(){
    	require_once MyClass.'/Conf/template.php';
    	$patten = "/(\_\_[a-zA-z]+\_\_)/";
    	if(preg_match($patten, $this->_tpl)){
    		$this->_tpl = preg_replace($patten , "<?php echo $1; ?>" , $this->_tpl);
    	}
    }
	
	/**
     * 对外公开的方法
     * @author Colin <15070091894@163.com>
     */
	public function comile($parFile){
		$this->parDefault();		//解析模板默认常量
		$this->parFunc();			//解析模板函数
		$this->parIF();				
		$this->parForeach();
		$this->parinclude();
		$this->parConfig();
		$this->parCommon();
		$this->parCount();
		$this->parExit();
		$this->parPrint();			
		$this->parVar();			//解析模板变量
		//生成编译文件
		file_put_contents($parFile,$this->_tpl);
	}
}
?>