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
	public function __construct($_tplFile){
		if(!$this->_tpl = file_get_contents($_tplFile)){
			throw new MyError('读取模板文件出错！'.$this->_tpl);
		}
	}
	
	/**
     * 解析普通变量
     * @author Colin <15070091894@163.com>
     */
	private function parVar(){
		$_patten = '/\{\$([\w]+)\}/';
		if(preg_match($_patten,$this->_tpl)){
			$this->_tpl = preg_replace($_patten,"<?php echo \$this->_vars['$1'] ?>",$this->_tpl);
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
		$_patten = '/\{foreach\s+\$([\w]+)\(([\w]+),([\w]+)\)\}/';
		$_endpatten = '/\{\/foreach\}/';
		$_pattenvar = '/\{@([\w]+)([\w\-\>\+]*)\}/';
		if(preg_match($_patten,$this->_tpl)){
			if(preg_match($_endpatten,$this->_tpl)){
				$this->_tpl = preg_replace($_patten,"<?php foreach(\$this->_vars['$1'] as \$$2=>\$$3){ ?>",$this->_tpl);
				$this->_tpl = preg_replace($_endpatten,"<?php } ?>",$this->_tpl);
				if(preg_match($_pattenvar,$this->_tpl)){
					$this->_tpl = preg_replace($_pattenvar,"<?php echo \$$1$2 ?>",$this->_tpl);
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
		$_patten = '/\{include\s+file=(\"|\')([\w\.\-\/]+)(\"|\')\}/';
		if(preg_match($_patten,$this->_tpl,$_file)){
			$_filename = APP_PATH.TPL_DIR.$_file[2].TPL_TYPE;
			if(!file_exists($_filename) || empty($_file)){
				throw new MyError($_filename.'包含文件出错！请检查！');
			}
			$_patten1 = '/Layout/';
			if(preg_match($_patten1,$_file[2])){
				$this->_tpl = preg_replace($_patten,"<?php \$this->Layout('$2') ?>",$this->_tpl);
			}else{
				$this->_tpl = preg_replace($_patten,"<?php include TPL_DIR.'$2'.TPL_TYPE ?>",$this->_tpl);
			}
		}
	}

	/**
     * 解析系统变量
     * @author Colin <15070091894@163.com>
     */
	private function parConfig(){
		$_patten = '/<!--\{([\w]+)\}-->/';
		if(preg_match($_patten,$this->_tpl)){
			$this->_tpl = preg_replace($_patten,"<?php echo \$this->_config['$1']; ?>",$this->_tpl);
		}
	}
	
	/**
     * 解析注释
     * @author Colin <15070091894@163.com>
     */
	private function parCommon(){
		$_patten = '/\{#\}(.*)\{#\}/';
		if(preg_match($_patten,$this->_tpl)){
			$this->_tpl = preg_replace($_patten,"<?php /* $1 */ ?>",$this->_tpl);
		}
	}

	/**
     * 解析count方法
     * @author Colin <15070091894@163.com>
     */
	private function parCount(){
		$_patten = '/\{count\(\$([\w]+)\)\}/';
		if(preg_match($_patten,$this->_tpl)){
			$this->_tpl = preg_replace($_patten,"<?php echo count(\$this->_vars['$1']) ?>",$this->_tpl);
		}
	}

	/**
     * 解析exit;
     * @author Colin <15070091894@163.com>
     */
	public function parExit(){
		$_patten = '/\{exit\}/';
		if(preg_match($_patten,$this->_tpl)){
			$this->_tpl = preg_replace($_patten,"<?php echo exit; ?>",$this->_tpl);
		}
	}

	/**
     * 解析print方法
     * @author Colin <15070091894@163.com>
     */
	private function parPrint(){
		$_patten = '/\{print\(\$([\w]+)\)\}/';
		if(preg_match($_patten,$this->_tpl)){
			$this->_tpl = preg_replace($_patten,"<?php print_r(\$this->_vars['$1']) ?>",$this->_tpl);
		}
	}
	
	/**
     * 对外公开的方法
     * @author Colin <15070091894@163.com>
     */
	public function comile($_parFile){
		//解析模板变量
		$this->parVar();
		$this->parIF();
		$this->parForeach();
		$this->parinclude();
		$this->parConfig();
		$this->parCommon();
		$this->parCount();
		$this->parExit();
		$this->parPrint();
		//生成编译文件
		file_put_contents($_parFile,$this->_tpl);
	}
}
?>