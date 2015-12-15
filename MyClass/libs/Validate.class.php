<?php
    /*
        Author : Colin,
        Creation time : 2015/8/17 16:09
        FileType : 验证类
        FileName : Validate.class.php
    */
    namespace Myclass\libs;
    class Validate
    {
        public $_string = '';           //字段
        public $_required = true;       //必填
        public $_parstring = array();   //解析后存储字段
        public $_maxlength = '';        //最大长度
        public $_minlength = '';        //最小长度
        public $_msg = '';              //提示信息
        public $_code = 'utf-8';        //字符编码

        public function __construct($_string)
        {
            $this->_string = $_string;
            $this->parString();
        }

        //解析字段
        public function parString()
        {
            if(is_array($this->_string))
            {
                foreach($this->_string as $_key=>$_value)
                {
                    $this->_parstring[$_key] = trim($_value);
                }
            }
        }

        //验证是否为空
        public function CheckNull()
        {
            foreach($this->_parstring as $_key=>$_value)
            {
                if(strlen($this->_parstring[$_key]) == 0)
                {
                    $this->_msg = $_key.'这是一个必填字段';
                    return true;
                }
            }
        }

        //验证最大长度
        public function CheckMaxLength()
        {
            if(mb_strlen($this->_parstring->$_key,$this->_code) > $this->_maxlength)
            {
                return $this->_msg;
            }
        }

        //验证最小长度
        public function CheckMinLength()
        {
            if(mb_strlen($this->_parstring->$_key,$this->_code) < $this->_minlength)
            {
                return $this->_msg;
            }
        }

        //验证最大和最小
        public function CheckLength()
        {
            foreach($this->_parstring as $_key=>$_value)
            {
                for($_i = 1;$_i<count($this->_parstring[$_key]);$_i++)
                {
                    if(mb_strlen($this->_parstring[$_key],$this->_code) <= $this->_minlength)
                    {
                        $this->_msg = $_key.'字段长度不满足'.$this->_maxlength;
                        return true;
                    }elseif(mb_strlen($this->_parstring[$_key],$this->_code) > $this->_maxlength)
                    {
                        $this->_msg = $_key.'字段长度超过了'.$this->_maxlength;
                        return true;
                    }
                }
            }
        }

        //公共方法
        public function common()
        {
            $this->CheckNull();
        }
    }

?>