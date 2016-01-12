<?php
/*
	Author : Colin,
	Creation time : 2015-7-31 09:18:38
	FileType : 
	FileName : 
*/
namespace MyClass\libs;

class Model
{
	//数据库句柄
	protected $_db = '';
	//获取数据表前缀
	protected $_Prefix = '';
	//获取数据库名
	protected $_DataName = '';
	//多表查询数据库名，必须带数据前缀
	protected $_Tables;
	//多表查询字段名
	protected $_Fields;
	//数据表的真实名字
	protected $_TrueTables = '';
	//数据表判断后存放的字段
	protected $_DataNameName = '';
	//where字段
	protected $_Where = '';
	//where value 
	protected $_Value = '';
	//where 条件的 OR and
	protected $_WhereOR = "AND";
	//sql语句
	protected $_Sql = '';
	//解析后存放的字段
	protected $_ParKey = '';
	//解析后存放的字段
	protected $_ParValue = '';
	//字符别名
	protected $_Alias = '';
	//limit
	protected $_Limit = '';
	//order
	protected $_Order = '';
	
	/**
	 * 构造方法
	 * @author Colin <15070091894@163.com>
	 */
	public function __construct($_tables = null){
		//数据库信息是否填写
		self::CheckConnectInfo();
		//配置数据库表前缀
		$this->_Prefix = Config('DB_PREFIX');
		if(empty($_tables)){
			return $this;
		}
		//获取数据库对象
		$this->_db = ObjFactory::CreateDateBase()->GetDB();
		//转小写
		$this->_DataName = strtolower($_tables);		
		//执行判断表方法
		$this->TablesType();
		//确认表是否存在
		self::CheckDataBase();
	}
	
	/**
	 * 判断类型
	 * @author Colin <15070091894@163.com>
	 */
	protected function TablesType(){
		if(empty($this->_TrueTables)){
			$this->_TablesName = '`'.$this->_Prefix.$this->_DataName.'`';
		}else {
			$this->_TablesName = '`'.$this->_TrueTables.'`';
		}
	}
	
	/**
	 * 多表查询-tables方法
	 * @param tables 表名
	 * @author Colin <15070091894@163.com>
	 */
	public function Tables($tables = null){
	    $this->_Tables = $tables;
	    return $this;
	}
	
	/**
	 * 多表查询-field方法
	 * @param field 字段名
	 * @author Colin <15070091894@163.com>
	 */
	public function Field($field){
	    if(empty($field)){
	        throw new MyError(__METHOD__.'请设置字段！');
	    }else {
	        $this->_Fields = $field;
	    }
	    return $this;
	}

	/**
	 * 插入数据函数
	 * @author Colin <15070091894@163.com>
	 */
	protected function ADUP(){
		if(!$this->_db->query($this->_Sql)){
			throw new MyError('SQL语句执行错误'.$this->_Sql);
		}
		return $this->_db->affected_rows;
	}
	
	/**
	 * 获取一条数据
	 * @author Colin <15070091894@163.com>
	 */
	protected function Getonedata(){
		$_result = $this->_db->query($this->_Sql);
		if(!$_result)throw new MyError('Sql语句错误'.$this->_Sql);
		$_array = array();
		while ($_rows = $_result->fetch_object()){
			$_array[] = $_rows;
		}
		return $_array;
	}

	/**
	 * 获取所有字段方法
	 * @author Colin <15070091894@163.com>
	 */
	protected function GetAllFuild(){
		$_result = $this->_db->query($this->_Sql);
		$_array = array();
		$_obj = new \stdClass();
		while ($_rows = $_result->fetch_field()){
		    $_name = $_rows->name;
			$_obj->$_name = $_rows->name;
		}
		return $_obj;
	}

	/**
	 * 获取数量
	 * @author Colin <15070091894@163.com>
	 */
	protected function GetNum(){
		$_result = $this->_db->query($this->_Sql);
		return $_result->num_rows;
	}

	/**
	 * 解析函数
	 * @param type 解析的类型
	 * @param array 要被解析的数据
	 * @author Colin <15070091894@163.com>
	 */
	protected function ParData($type , $array){
		$_b = '';
		$_c = '';
		if($type == 'ist'){
			if(is_array($array)){
				foreach ($array as $_key => $_value) {
					$_b .= '`'.$_key.'`' . ',';
					$_c .= "'" . $_value . "',";
				}
				$this->_ParKey = substr($_b, 0, -1);
				$this->_ParValue = substr($_c, 0, -1);
			}else if(is_string($array)){
				throw new MyError('解析insert sql 字段失败!'.$this->_Sql);
			}

		}else if($type == 'upd'){
			foreach ($array as $_key => $_value){
				$_b .= '`'.$_key.'`'. '=' ."'". $_value."'" . ',';
			}
			$this->_ParKey = ' SET '.substr($_b, 0, -1);
		}
	}

	/**
	 * 获取数据库所有字段
	 * @author Colin <15070091894@163.com>
	 */
	public function AllFuild(){
		//$this->_Sql = "SHOW Full COLUMNS FROM ".$this->_TablesName;
		///$this->_Sql = "SHOW FIELDS FROM $this->_TablesName";
		$this->_Sql = "SELECT * FROM $this->_TablesName";
		return $this->GetAllFuild();
	}


	/**
	 * 执行源生的sql语句
	 * @param sql sql语句
	 * @author Colin <15070091894@163.com>
	 */
	public function query($sql=null){
		if(empty($sql)){
			return $this->ADUP();
		}else{
			$this->_Sql = $sql;
			return $this->ADUP();
		}
	}

	/**
	 * 查询函数
	 * @author Colin <15070091894@163.com>
	 */
	public function select(){
	    if($this->_Tables != null){
	        $this->_Sql = "SELECT $this->_Fields FROM ".$this->_Tables.' '.$this->_Where.$this->_Value.$this->_Order.$this->_Limit;
	    }else {
	        $this->_Sql = "SELECT * FROM ".$this->_TablesName.$this->_Where.$this->_Value.$this->_Order.$this->_Limit;
	    }
		return $this->Getonedata();
	}

	/**
	 * 查询数据库条数
	 * @author Colin <15070091894@163.com>
	 */
	public function selectNum(){
		$this->_Sql = "SELECT id FROM ".$this->_TablesName.$this->_Where.$this->_Value;
		return $this->GetNum();
	}
	
	/**
	 * 条件
	 * @param fuild 字段名称
	 * @param wherevalue 字段值
	 * @param whereor OR和AND
	 * @author Colin <15070091894@163.com>
	 */
	public function where($field , $wherevalue = null , $whereor = null){
		$_a = '';
		$fieldlen = count($field);
		$i = 0;
		if($whereor != null){
			$this->_WhereOR = $whereor;
		}
		//遍历字段
		if(is_array($field)){
			//判断是否为多条数据
			if(count($field) > 1){
				//遍历字段
				foreach ($field as $_key => $_value){
					$i ++ ;
					//判断是否为数字或字符串
					if(is_string($_value)){
						//判断是否为最后一个
						if($i != $fieldlen){
							$_a .= '`'.$_key.'`'."='".$_value."' ".$this->_WhereOR." ";
						}else {
							$_a .= '`'.$_key.'`'."='".$_value."'";
						}
					//判断是否为数字
					}else if(is_numeric($_value)){
						if($i != $fieldlen){
							$_a .= '`'.$_key.'`'."=".$_value." ".$this->_WhereOR." ";
						}else {
							$_a .= '`'.$_key.'`'."='".$_value."'";
						}
					}
					$this->_Where =  " WHERE ".$_a;
					$this->_Value = '';
				}
			}else {
				//如果不是字段的长度不大于1条 执行下面
				foreach ($field as $_key => $_value){
					if(is_string($_value)){
					    $_a .= '`'.$_key.'`'."='".$_value."'";
					//判断是否为数字
					}else if(is_numeric($_value)){
						$_a .= '`'.$_key.'`'."='".$_value."'";
					}
				}
				$this->_Where =  " WHERE ".$_a;
			}
		}else {
			//如果字段为数组的时候，那么直接使用遍历
			//判断是否为数字或字符串
			if(is_string($wherevalue)){
				$_a .= "='".$wherevalue."'";
			//判断是否为数字
			}else if(is_numeric($wherevalue)){
				$_a .= "=".$wherevalue;
			}
			$this->_Where = " WHERE ".$_fuild;
			$this->_Value = $_a;
		}	
		return $this;
	}
	
	/**
	 * 插入数据
	 * @param array   要插入的数据
	 * @author Colin <15070091894@163.com>
	 */
	public function Add($data){
        if(empty($data)){
            throw new MyError(__METHOD__.'没有传入参数值！');
        }
		$this->ParData('ist',$data);
		$this->_Sql = "INSERT INTO ".$this->_TablesName."(".$this->_ParKey.") VALUES (".$this->_ParValue.")";
		return $this->ADUP($this->_Sql);
	}
	
	/**
	 * 删除函数
	 * @param field 被删除的字段
	 * @param uniqid 唯一标示符
	 * @author Colin <15070091894@163.com>
	 */
	public function Del($field = 'id' , $value){
		$this->_Sql = "DELETE FROM ".$this->_TablesName." WHERE ".$field."=".$value;
		return $this->ADUP($this->_Sql);
	}
	
	/**
	 * 修改函数
	 * @param field	要被修改的字段
	 * @param value	要被修改的值
	 * @author Colin <15070091894@163.com>
	 */
	public function Upd($field , $value = null){
		if(is_string($field)){
			$this->_ParKey = ' SET '.'`'.$field.'`'."='".$value."'";
		}else if(is_array($field)){
			$this->ParData('upd',$field);
		}
		$this->_Sql = "UPDATE ".$this->_TablesName.$this->_ParKey.$this->_Where.$this->_Value;
		return $this->ADUP();
	}
	
	/**
	 * 别名
	 * @param as 新的别名
	 * @author Colin <15070091894@163.com>
	 */
	public function Alias($as){
		$this->_Alias = ' AS '.$as;
		return $this;
	}
	
	/**
	 * 求最大值
	 * @param fuild  要求出最大值的数值
	 * @author Colin <15070091894@163.com>
	 */
	public function max($field){
		$this->_Sql = "SELECT MAX($field)$this->_Alias FROM ".$this->_TablesName;
		return $this->Getonedata();
	}
	
	/**
	 * 最小值
	 * @param field   要被求出最小值的字段
	 * @author Colin <15070091894@163.com>
	 */
	public function min($field){
		$this->_Sql = "SELECT MIN($field)$this->_Alias FROM ".$this->_TablesName;
		return $this->Getonedata();
	}
	
	/**
	 * 某个字段求和
	 * @param field 要被求和的字段
	 * @author Colin <15070091894@163.com>
	 */
	public function sum($field){
		$this->_Sql = "SELECT SUM($field)$this->_Alias FROM ".$this->_TablesName;
		return $this->Getonedata();
	}
	
	/**
	 * 求平均值
	 * @param strinng $field
	 * @author Colin <15070091894@163.com>
	 */
	public function Avg($field){
	    $this->_Sql = "SELECT AVG($field)$this->_Alias FROM ".$this->_TablesName;
	    return $this->Getonedata();
	}
	
	/**
	 * limt
	 * @author Colin <15070091894@163.com>
	 */
	public function limit($num){
		$this->_Limit = "LIMIT ".$num;
		return $this;
	}

	/**
	 * order
	 * @author Colin <15070091894@163.com>
	 */
	public function order($field , $desc){
		$this->_Order = " ORDER BY ".$field." ".$desc." ";
		return $this;
	}


	/**
	 * 容错处理机制
	 * @author Colin <15070091894@163.com>
	 */
	public function __call($fun , $param=null){
		ShowMessage($fun.'()这个方法不存在！');
	}

	/**
     * 静态方法容错处理机制
     * @author Colin <15070091894@163.com>
     */
	static public function __callStatic($fun , $param=null){
		ShowMessage(__METHOD__.'()这个方法不存在！');
	}

	/**
	 * invoke方法  处理吧类当成函数来使用
	 * @author Colin <15070091894@163.com>
	 */
	public function __invoke(){
		ShowMessage(__CLASS__.'这不是一个函数');
	}

	/**
	 * 验证数据库信息是否填写
	 * @author Colin <15070091894@163.com>
	 */
	public static function CheckConnectInfo(){
		if(!Config('DB_TYPE') || !Config('DB_HOST') || !Config('DB_USER') || !Config('DB_PASS') || !Config('DB_TABS')){
			throw new MyError('请设置数据库连接信息！');
		}
	}

	/**
	 * 检查数据库是否存在
	 * @author Colin <15070091894@163.com>
	 */
	public function CheckDataBase(){
		$result = $this->_db->query("SHOW TABLES LIKE '%$this->_DataName%'");
		if(!$result){
			throw new MyError('数据库不存在！');
		}
	}
}
?>