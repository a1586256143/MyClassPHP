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
		protected $_Prefix = DB_PREFIX;
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
		
		//构造方法
		public function __construct($_tables=null)
		{
			if(empty($_tables))
			{
				return $this;
			}
			//获取数据库对象
			$this->_db = ObjFactory::CreateDateBase()->GetDB();
			//转小写
			$this->_DataName = strtolower($_tables);
			//执行判断表方法
			$this->TablesType();
		}
		
		//判断类型
		protected function TablesType()
		{
			if(empty($this->_TrueTables))
			{
				$this->_TablesName = '`'.$this->_Prefix.$this->_DataName.'`';
			}else 
			{
				$this->_TablesName = '`'.$this->_TrueTables.'`';
			}
		}
		
		//多表查询-tables方法
		public function Tables($_tables = null)
		{
		    $this->_Tables =$_tables;
		    return $this;
		}
		
		//多表查询-field方法
		public function Field($_field)
		{
		    if(empty($_field))
		    {
		        throw new MyError(__METHOD__.'请设置字段！');
		    }else 
		    {
		        $this->_Fields = $_field;
		    }
		    return $this;
		}

		//插入数据函数
		protected function ADUP()
		{
			if(!$this->_db->query($this->_Sql))
			{
				throw new MyError('SQL语句执行错误'.$this->_Sql);
			}
			return $this->_db->affected_rows;
		}
		
		//获取一条数据
		protected function Getonedata()
		{
			$_result = $this->_db->query($this->_Sql);
			if(!$_result)throw new MyError('Sql语句错误'.$this->_Sql);
			$_array = array();
			while ($_rows = $_result->fetch_object())
			{
				$_array[] = $_rows;
			}
			return $_array;
		}

		//获取所有字段方法
		protected function GetAllFuild()
		{
			$_result = $this->_db->query($this->_Sql);
			$_array = array();
			$_obj = new \stdClass();
			while ($_rows = $_result->fetch_field())
			{
			    $_name = $_rows->name;
				$_obj->$_name = $_rows->name;
			}
			return $_obj;
		}

		/*
		 * 获取数量
		 *
		 * */
		protected function GetNum()
		{
			$_result = $this->_db->query($this->_Sql);
			return $_result->num_rows;
		}

		/*
		 * 解析函数
		 * @_array 要被解析的数据
		 * */
		protected function ParData($_type,$_array)
		{
			$_b = '';
			$_c = '';
			if($_type == 'ist')
			{
				if(is_array($_array))
				{
					foreach ($_array as $_key => $_value) {
						$_b .= '`'.$_key.'`' . ',';
						$_c .= "'" . $_value . "',";
					}
					$this->_ParKey = substr($_b, 0, -1);
					$this->_ParValue = substr($_c, 0, -1);
				}else if(is_string($_array))
				{
					throw new MyError('解析insert sql 字段失败!'.$this->_Sql);
				}

			}else if($_type == 'upd')
			{
				foreach ($_array as $_key => $_value)
				{
					$_b .= '`'.$_key.'`'. '=' ."'". $_value."'" . ',';
				}
				$this->_ParKey = ' SET '.substr($_b, 0, -1);
			}
		}

		//获取数据库所有字段
		public function AllFuild()
		{
			//$this->_Sql = "SHOW Full COLUMNS FROM ".$this->_TablesName;
			///$this->_Sql = "SHOW FIELDS FROM $this->_TablesName";
			$this->_Sql = "SELECT * FROM $this->_TablesName";
			return $this->GetAllFuild();
		}


		//执行源生的sql语句
		public function query($_sql=null)
		{
			if(empty($_sql))
			{
				return $this->ADUP();
			}else
			{
				$this->_Sql = $_sql;
				return $this->ADUP();
			}
		}

		//查询函数
		public function select()
		{
		    if($this->_Tables != null)
		    {
		        $this->_Sql = "SELECT $this->_Fields FROM ".$this->_Tables.' '.$this->_Where.$this->_Value.$this->_Order.$this->_Limit;
		    }else 
		    {
		        $this->_Sql = "SELECT * FROM ".$this->_TablesName.$this->_Where.$this->_Value.$this->_Order.$this->_Limit;
		    }
			return $this->Getonedata();
		}

		/*
		 * 查询数据库条数
		 *
		 * */
		public function selectNum()
		{
			$this->_Sql = "SELECT id FROM ".$this->_TablesName.$this->_Where.$this->_Value;
			return $this->GetNum();
		}
		
		/*
		 * @fuild 字段名称
		 * @wherevalue 字段值
		 * @whereor OR和AND
		 * */
		public function where($_fuild,$_wherevalue = null,$_whereor = null)
		{
			$_a = '';
			$_fuildlen = count($_fuild);
			$i = 0;
			if($_whereor != null)
			{
				$this->_WhereOR = $_whereor;
			}
			//遍历字段
			if(is_array($_fuild))
			{
				//判断是否为多条数据
				if(count($_fuild) > 1)
				{
					//遍历字段
					foreach ($_fuild as $_key=>$_value)
					{
						$i ++ ;
						//判断是否为数字或字符串
						if(is_string($_value))
						{
							//判断是否为最后一个
							if($i != $_fuildlen)
							{
								$_a .= '`'.$_key.'`'."='".$_value."' ".$this->_WhereOR." ";
							}else 
							{
								$_a .= '`'.$_key.'`'."='".$_value."'";
							}
						//判断是否为数字
						}else if(is_numeric($_value))
						{
							if($i != $_fuildlen)
							{
								$_a .= '`'.$_key.'`'."=".$_value." ".$this->_WhereOR." ";
							}else 
							{
								$_a .= '`'.$_key.'`'."='".$_value."'";
							}
						}
						$this->_Where =  " WHERE ".$_a;
						$this->_Value = '';
					}
				}else 
				{
					//如果不是字段的长度不大于1条 执行下面
					foreach ($_fuild as $_key=>$_value)
					{
						if(is_string($_value))
						{
						    $_a .= '`'.$_key.'`'."='".$_value."'";
						//判断是否为数字
						}else if(is_numeric($_value))
						{
							$_a .= '`'.$_key.'`'."='".$_value."'";
						}
					}
					$this->_Where =  " WHERE ".$_a;
				}
			}else 
			{
			    
				//如果字段为数组的时候，那么直接使用遍历
				//判断是否为数字或字符串
				if(is_string($_wherevalue))
				{
					$_a .= "='".$_wherevalue."'";
				//判断是否为数字
				}else if(is_numeric($_wherevalue))
				{
					$_a .= "=".$_wherevalue;
				}
				$this->_Where = " WHERE ".$_fuild;
				$this->_Value = $_a;
			}	
			return $this;
		}
		
		/*
		 * @_array   要插入的数据
		 * */
		public function Add($_array)
		{
            if(empty($_array))
            {
                throw new MyError(__METHOD__.'没有传入参数值！');
            }
			$this->ParData('ist',$_array);
			$this->_Sql = "INSERT INTO ".$this->_TablesName."(".$this->_ParKey.") VALUES (".$this->_ParValue.")";
			return $this->ADUP($this->_Sql);
		}
		
		/*
		 * 删除函数
		 * @_fuild 被删除的字段
		 * @_uniqid 唯一标示符
		 * */
		public function Del($_fuild,$_uniqid)
		{
			$this->_Sql = "DELETE FROM ".$this->_TablesName." WHERE ".$_fuild."=".$_uniqid;
			return $this->ADUP($this->_Sql);
		}
		
		/*
		 * 修改函数
		 * @_fuild	要被修改的字段
		 * @_value	要被修改的值
		 * */
		public function Upd($_fuild,$_value=null)
		{
			if(is_string($_fuild))
			{
				$this->_ParKey = ' SET '.'`'.$_fuild.'`'."='".$_value."'";
			}else if(is_array($_fuild))
			{
				$this->ParData('upd',$_fuild);
			}
			$this->_Sql = "UPDATE ".$this->_TablesName.$this->_ParKey.$this->_Where.$this->_Value;
			return $this->ADUP();
		}
		
		/*
		 * 别名
		 * @_as 新的别名
		 * */
		public function Alias($_as)
		{
			$this->_Alias = ' AS '.$_as;
			return $this;
		}
		
		/**
		 * 求最大值
		 * @_fuild  要求出最大值的数值
		 * 
		 * */
		public function max($_fuild)
		{
			$this->_Sql = "SELECT MAX($_fuild)$this->_Alias FROM ".$this->_TablesName;
			return $this->Getonedata();
		}
		
		/*
		 * 最小值
		 * @_fuild   要被求出最小值的字段
		 * 
		 * */
		public function min($_fuild)
		{
			$this->_Sql = "SELECT MIN($_fuild)$this->_Alias FROM ".$this->_TablesName;
			return $this->Getonedata();
		}
		
		/*
		 * 某个字段求和
		 *@_fuild 要被求和的字段
		 * 
		 * */
		public function sum($_fuild)
		{
			$this->_Sql = "SELECT SUM($_fuild)$this->_Alias FROM ".$this->_TablesName;
			return $this->Getonedata();
		}
		
		/**
		 * @param strinng $_fuild
		 * return object
		 */
		public function Avg($_fuild)
		{
		    $this->_Sql = "SELECT AVG($_fuild)$this->_Alias FROM ".$this->_TablesName;
		    return $this->Getonedata();
		}
		
		/*
		 * limt
		 * 
		 * */
		public function limit($_num)
		{
			$this->_Limit = "LIMIT ".$_num;
			return $this;
		}

		/*
		 * order
		 *
		 * */
		public function order($_fiuld,$_desc)
		{
			$this->_Order = " ORDER BY ".$_fiuld." ".$_desc." ";
			return $this;
		}


		/*
		 * 容错处理机制
		 */
		public function __call($_fun,$_param=null)
		{
			ShowMessage($_fun.'()这个方法不存在！');
		}

		/*
         * 静态方法容错处理机制
         */
		static public function __callStatic($_fun,$_param=null)
		{
			ShowMessage(__METHOD__.'()这个方法不存在！');
		}

		/*
		 * invoke方法  处理吧类当成函数来使用
		 */
		public function __invoke()
		{
			ShowMessage(__CLASS__.'这不是一个函数');
		}

	}
?>