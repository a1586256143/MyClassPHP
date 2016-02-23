<?php
/*
	Author : Colin,
	Creation time : 2015-7-31 09:18:38
	FileType : 
	FileName : 
*/
namespace MyClass\libs;
class Model{
	//数据库句柄
	protected $db = '';
	//获取数据表前缀
	protected $db_prefix = '';
	//获取数据库名
	protected $DataName = '';
	//多表查询数据库名，必须带数据前缀
	protected $Tables;
	//From表名
	protected $From;
	//多表查询字段名
	protected $Fields = '*';
	//数据表的真实名字
	protected $TrueTables = '';
	//数据表判断后存放的字段
	protected $DataNameName = '';
	//where字段
	protected $Where = null;
	//where value 
	protected $value = null;
	//where 条件的 OR and
	protected $WhereOR = "AND";
	//sql语句
	protected $Sql = '';
	//解析后存放的字段
	protected $ParKey = '';
	//解析后存放的字段
	protected $Parvalue = '';
	//字符别名
	protected $Alias = '';
	//limit
	protected $Limit = '';
	//order
	protected $Order = '';
	
	/**
	 * 构造方法
	 * @author Colin <15070091894@163.com>
	 */
	public function __construct($tables = null){
		//设置类成员
		self::setClassMember();
		//数据库信息是否填写
		self::CheckConnectInfo();
		//获取数据库对象
		$this->db = ObjFactory::getIns();
		if(empty($tables)){
			return $this;
		}
		//执行判断表方法
		$this->TablesType($tables);
		//确认表是否存在
		$this->CheckTables();
	}
	
	/**
	 * 判断类型
	 * @author Colin <15070091894@163.com>
	 */
	protected function TablesType($tables){
		$tables = $this->parTableName($tables);
		//转小写
		$this->DataName = strtolower($tables);	
		if(empty($this->TrueTables)){
			$this->TablesName = $this->db_tabs.'.`'.$this->db_prefix.$this->DataName.'`';
		}else {
			$this->TablesName = $this->db_tabs.'.`'.$this->TrueTables.'`';
		}
		$this->from($this->TablesName);
	}

	/**
	 * 解析表名的大写
	 * @author Colin <15070091894@163.com>
	 */
	protected function parTableName($tables){
		$tablename = array_filter(preg_split('/(?=[A-Z])/', $tables));
		$tablename = implode('_', $tablename);
		return $tablename;
	}
	
	/**
	 * field方法
	 * @param field 字段名
	 * @author Colin <15070091894@163.com>
	 */
	public function field($field){
	    if(!empty($field)){
	        $this->Fields = $field;
	    }
	    return $this;
	}

	/**
	 * From函数
	 * @param  tables 表名
	 * @author Colin <15070091894@163.com>
	 */
	public function from($tables = null){
		$tables = $tables === null ? $this->TablesName : $tables;
		$this->From = ' FROM ' . $tables;
		return $this;
	}

	/**
	 * 执行sql语句函数
	 * @author Colin <15070091894@163.com>
	 */
	protected function ADUP($sql = null , $ist = null){
		$sql = $sql === null ? $this->Sql : $sql;
		$query = $this->db->query($this->Sql);
		if(!$query){
			throw new MyError('SQL语句执行错误'.$this->Sql);
		}
		if($ist == 'ist'){
			return $this->db->insert_id();
		}else if($ist == 'upd'){
			return $this->db->affected_rows();
		}
		return $query;
	}

	/**
	 * 获取数量
	 * @author Colin <15070091894@163.com>
	 */
	protected function GetNum(){
		$result = $this->query();
		return $result->num_rows;
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
				foreach ($array as $key => $value) {
					$_b .= '`'.$key.'`' . ',';
					$_c .= "'" . $value . "',";
				}
				$this->ParKey = substr($_b, 0, -1);
				$this->Parvalue = substr($_c, 0, -1);
			}else if(is_string($array)){
				throw new MyError('解析insert sql 字段失败!'.$this->Sql);
			}
		}else if($type == 'upd'){
			$pk = $this->getpk();
			foreach ($array as $key => $value){
				if($key == $pk){
					continue;
				}
				$_b .= '`'.$key.'`'. '=' ."'". $value."'" . ',';
			}
			//解析主键
			if($this->Where === null || $this->value === null){
				$this->where($pk , $array[$pk]);
			}
			$this->ParKey = ' SET '.substr($_b, 0, -1);
		}
	}

	/**
	 * 条件
	 * @param fuild 字段名称
	 * @param wherevalue 字段值
	 * @param whereor OR和AND
	 * @param sub 操作符号 可以为 =,!=,in,not in,between,not between 
	 * @author Colin <15070091894@163.com>
	 */
	public function where($field , $wherevalue = null , $whereor = null , $sub = '='){
		$_a = '';
		$fieldlen = count($field);
		$i = 0;
		if($whereor !== null) $this->WhereOR = $whereor;
		//遍历字段
		if(is_array($field)){
			//判断是否为多条数据
			if(count($field) > 1){
				//遍历字段
				foreach ($field as $key => $value){
					$i ++ ;
					//判断是否为数字或字符串
					if(is_string($value)){
						//判断是否为最后一个
						if($i != $fieldlen){
							$_a .= "`$key` $sub '$value' $this->WhereOR ";
						}else {
							$_a .= "`$key` $sub '$value'";
						}
					//判断是否为数字
					}else if(is_numeric($value)){
						if($i != $fieldlen){
							$_a .= "`$key` $sub $value $this->WhereOR ";
						}else {
							$_a .= "`$key` $sub $value";
						}
					}else if(strpos($key , '.') !== false){
						$_a .= $key.$sub.$value;
					}
					$this->Where = " WHERE ".$_a;
					$this->value = '';
				}
			}else {
				//如果字段的长度不大于1条 执行下面
				foreach ($field as $key => $value){
					if(is_string($value)){
						$_a .= "`$key` $sub '$value'";
					//判断是否为数字
					}else if(is_numeric($value)){
						$_a .= "`$key` $sub $value";
					}else if(strpos($key , '.') !== false){
						$_a .= $key.$sub.$value;
					}
				}
				$this->Where =  " WHERE ".$_a;
			}
		}else {
			//如果字段为数组的时候，那么直接使用遍历
			//判断是否为数字或字符串
			if(is_string($wherevalue)){
				$_a .= "$sub '$wherevalue'";
				//查找value中带了()的值 则不加''号
				if(strpos($wherevalue , '(') !== false || strpos($wherevalue , '.') !== false || strpos($field , '.') !== false){
					$_a = $sub.$wherevalue;
				}
			//判断是否为数字
			}else if(is_numeric($wherevalue)){
				$_a .= $sub.$wherevalue;
			}
			$this->Where = strpos($field , '.') !== false ? " WHERE $field " :" WHERE `$field` ";	
			$this->value = $_a;
		}
		return $this;
	}

	/**
	 * 获取主键
	 * @author Colin <15070091894@163.com>
	 */
	public function getpk(){
		$pk = S('TABLE_PK_FOR_'.$this->DataName);
		if(empty($pk)){
			$map['TABLE_SCHEMA'] = $this->db_tabs;
			$map['TABLE_NAME'] = $this->db_prefix.$this->DataName;
			$rows = $this->where($map)->field('COLUMN_NAME')->from('information_schema.`KEY_COLUMN_USAGE`')->find();
			$this->Where = null;
			$pk = S('TABLE_PK_FOR_'.$this->DataName , $rows);
			return $pk['COLUMN_NAME'];
		}
		return $pk['COLUMN_NAME'];
	}

	/**
	 * 获取结果集
	 * @param sql sql语句
	 * @param is_more 是否为获取多条数据
	 * @author Colin <15070091894@163.com>
	 */
	protected function getResult($sql = null , $is_more = false){
		$sql = $sql === null ? $this->Sql : $sql;
		$result = $this->ADUP($sql);
		$data = array();
		if($is_more){
			while ($rows = $result->fetch_assoc()){
				$data[] = $rows;
			}
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}


	/**
	 * 执行源生的sql语句
	 * @param sql sql语句
	 * @author Colin <15070091894@163.com>
	 */
	public function query($sql = null){
		$sql = $sql === null ? $this->Sql : $sql;
		return $this->ADUP($sql);
	}

	/**
	 * 查询函数
	 * @author Colin <15070091894@163.com>
	 */
	public function select(){
	    $this->getSql();
		return $this->getResult(null , true);
	}

	/**
	 * 得到查询的sql语句
	 * @author Colin <15070091894@163.com>
	 */
	protected function getSql(){
		if($this->Tables != null){
	        $this->Sql = "SELECT $this->Fields FROM ".$this->Tables.' '.$this->Where.$this->value.$this->Order.$this->Limit;
	    }else {
	        $this->Sql = "SELECT $this->Fields ".$this->From.$this->Where.$this->value.$this->Order.$this->Limit;
	    }
	}

	/**
	 * 获取最后执行的sql语句
	 * @author Colin <15070091894@163.com>
	 */
	public function getLastSql(){
		return $this->Sql;
	}

	/**
	 * 查询一条数据
	 * @author Colin <15070091894@163.com>
	 */
	public function find(){
		$this->getSql();
		return $this->getResult();
	}

	/**
	 * in
	 * @param field 字段
	 * @param values 值
	 * @author Colin <15070091894@163.com>
	 */
	public function in($field , $values){
		$this->where($field , '('.$values.')' , null , 'in ');
		return $this;
	}

	/**
	 * not in
	 * @param field 字段
	 * @param values 值
	 * @author Colin <15070091894@163.com>
	 */
	public function notin($field , $values){
		$this->where($field , '('.$values.')' , null , 'not in ');
		return $this;
	}

	/**
	 * like
	 * @param field 要被like的字段名
	 * @param value like的值
	 * @author Colin <15070091894@163.com>
	 */
	public function like($field , $value){
		return $this->where($field , "%$value%" , null , 'LIKE ');
	}

	/**
	 * between
	 * @param field 要被between的字段名
	 * @param between between的值 格式为 1,2
	 * @author Colin <15070091894@163.com>
	 */
	public function between($field , $between){
		$this->between_common($field , $between , 'BETWEEN');
		return $this;
	}

	/**
	 * between
	 * @param field 要被between的字段名
	 * @param between between的值 格式为 1,2
	 * @author Colin <15070091894@163.com>
	 */
	public function notbetween($field , $between){
		$this->between_common($field , $between , 'NOT BETWEEN');
		return $this;
	}

	/**
	 * between公共模块
	 * @param field 要被between的字段名
	 * @param between between的值 格式为 1,2
	 * @param keyword 关键词 BETWEEN 或者 NOT BETWEEN 
	 * @author Colin <15070091894@163.com>
	 */
	protected function between_common($field , $between , $keyword){
		$this->Where = " WHERE `$field` $keyword ";
		list($betweenleft , $betweenright) = explode( ',' , $between);
		$this->value = $betweenleft . ' AND ' . $betweenright;
	}

	/**
	 * 查询数据库条数
	 * @author Colin <15070091894@163.com>
	 */
	public function selectNum(){
		$pk = $this->getpk();
		$this->field($pk)->find();
		return $this->GetNum();
	}
	
	/**
	 * 插入数据
	 * @param values   要插入的数据
	 * @author Colin <15070091894@163.com>
	 */
	public function insert($values){
		$values = array_filter($values);
        if(empty($values)){
            throw new MyError(__METHOD__.'没有传入参数值！');
        }
		foreach ($values as $key => $value) {
			if($value != ''){
				$data[$key] = $value;
			}
		}
		$this->ParData('ist',$data);
		$this->Sql = "INSERT INTO ".$this->TablesName."(".$this->ParKey.") VALUES (".$this->Parvalue.")";
		return $this->ADUP($this->Sql , 'ist');
	}
	
	/**
	 * 删除函数
	 * @param field 被删除的字段
	 * @param value 唯一标示符
	 * @author Colin <15070091894@163.com>
	 */
	public function delete($value , $field = null){
		$field = $field === null ? $this->getpk() : $field;
		if($this->Where === null || $this->value === null){
			$this->where($field , $value);
		}
		$this->Sql = "DELETE FROM ".$this->TablesName.$this->Where.$this->value;
		return $this->ADUP();
	}
	
	/**
	 * 修改函数
	 * @param field	要被修改的字段
	 * @param value	要被修改的值
	 * @author Colin <15070091894@163.com>
	 */
	public function update($field , $value = null){
		if(is_string($field)){
			$this->ParKey = ' SET '.'`'.$field.'`'."='".$value."'";
		}else if(is_array($field)){
			foreach ($field as $key => $value) {
				if($value === ''){
					continue;
				}
				$data[$key] = $value;
			}
			$this->ParData('upd',$data);
		}
		$this->Sql = "UPDATE ".$this->TablesName.$this->ParKey.$this->Where.$this->value;
		return $this->ADUP($this->Sql , 'upd');
	}
	
	/**
	 * 别名
	 * @param as 新的别名
	 * @author Colin <15070091894@163.com>
	 */
	public function alias($as = 'alias'){
		$this->Alias = ' AS '.$as;
		return $this;
	}
	
	/**
	 * 求最大值
	 * @param fuild  要求出最大值的数值
	 * @author Colin <15070091894@163.com>
	 */
	public function max($field){
		return $this->field("MAX($field)$this->Alias")->find();
	}
	
	/**
	 * 最小值
	 * @param field   要被求出最小值的字段
	 * @author Colin <15070091894@163.com>
	 */
	public function min($field){
		return $this->field("MIN($field)$this->Alias")->find();
	}
	
	/**
	 * 某个字段求和
	 * @param field 要被求和的字段
	 * @author Colin <15070091894@163.com>
	 */
	public function sum($field){
		return $this->field("SUM($field)$this->Alias")->find();
	}
	
	/**
	 * 求平均值
	 * @param field 平均值的字段
	 * @author Colin <15070091894@163.com>
	 */
	public function avg($field){
		return $this->field("AVG($field)$this->Alias")->find();
	}
	
	/**
	 * limt
	 * @param num 查询结果集的数量 0,10
	 * @author Colin <15070091894@163.com>
	 */
	public function limit($start , $end = null){
		$start = ($start-1) * $end;
		$this->Limit = "LIMIT ".$start.','.$end;
		return $this;
	}

	/**
	 * order
	 * @author Colin <15070091894@163.com>
	 */
	public function order($field , $desc = null){
		$this->Order = " ORDER BY ".$field." ".$desc." ";
		return $this;
	}

	/**
     * 执行源生sql语句并返回结果
     * @param sql 要执行的sql语句
     * @author Colin <15070091894@163.com>
     */
	public function execute($sql){
		$query = $this->db->query($sql);
        $result = $this->db->fetch_array($query);
        return $result;
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
	protected static function CheckConnectInfo(){
		if(!Config('DB_TYPE') || !Config('DB_HOST') || !Config('DB_USER') || !Config('DB_TABS')){
			throw new MyError('请设置数据库连接信息！');
		}
	}

	/**
     * 设置类成员
     * @param tables 要验证的表名
     * @author Colin <15070091894@163.com>
     */
	protected function setClassMember(){
		$patten = '/(DB\_.*)/';
		foreach (Config() as $key => $value) {
			if(!preg_match($patten , $key , $match)){
				continue;
			}
			$member = strtolower($match[0]);
			eval('$this->'.$member.' = "'.$value.'";');
		}
	}

	/**
     * 确认表是否存在
     * @param tables 要验证的表名
     * @author Colin <15070091894@163.com>
     */
    public function CheckTables($tables = null){
    	if($tables == null){
    		$tables = $this->db_prefix.$this->DataName;
    	}
        $result = $this->execute("SHOW TABLES LIKE '%$tables%'");
       	if(empty($result)){
       		throw new MyError('数据表不存在！'.$tables);
       	}
    }
}
?>