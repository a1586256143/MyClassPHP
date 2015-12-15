<?php
    /*
        Author : Colin,
        Creation time : 2015/8/17 16:09
        FileType : ��֤��
        FileName : Validate.class.php
    */
    namespace Myclass\libs;
    class Validate
    {
        public $_string = '';           //�ֶ�
        public $_required = true;       //����
        public $_parstring = array();   //������洢�ֶ�
        public $_maxlength = '';        //��󳤶�
        public $_minlength = '';        //��С����
        public $_msg = '';              //��ʾ��Ϣ
        public $_code = 'utf-8';        //�ַ�����

        public function __construct($_string)
        {
            $this->_string = $_string;
            $this->parString();
        }

        //�����ֶ�
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

        //��֤�Ƿ�Ϊ��
        public function CheckNull()
        {
            foreach($this->_parstring as $_key=>$_value)
            {
                if(strlen($this->_parstring[$_key]) == 0)
                {
                    $this->_msg = $_key.'����һ�������ֶ�';
                    return true;
                }
            }
        }

        //��֤��󳤶�
        public function CheckMaxLength()
        {
            if(mb_strlen($this->_parstring->$_key,$this->_code) > $this->_maxlength)
            {
                return $this->_msg;
            }
        }

        //��֤��С����
        public function CheckMinLength()
        {
            if(mb_strlen($this->_parstring->$_key,$this->_code) < $this->_minlength)
            {
                return $this->_msg;
            }
        }

        //��֤������С
        public function CheckLength()
        {
            foreach($this->_parstring as $_key=>$_value)
            {
                for($_i = 1;$_i<count($this->_parstring[$_key]);$_i++)
                {
                    if(mb_strlen($this->_parstring[$_key],$this->_code) <= $this->_minlength)
                    {
                        $this->_msg = $_key.'�ֶγ��Ȳ�����'.$this->_maxlength;
                        return true;
                    }elseif(mb_strlen($this->_parstring[$_key],$this->_code) > $this->_maxlength)
                    {
                        $this->_msg = $_key.'�ֶγ��ȳ�����'.$this->_maxlength;
                        return true;
                    }
                }
            }
        }

        //��������
        public function common()
        {
            $this->CheckNull();
        }
    }

?>