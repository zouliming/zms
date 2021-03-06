<?php

#================================================
#Version:       1.1.0
#Author:	liming.zou@vipshop.com
#First Release:	2009-02-03 14:54:00
#Recent Update:	2009-03-26 17:20:00
#Funcnames:		
#== not_blank uint uint_length length eq
#== safe_text no_quot ip_text email url date_text time_text datetime_text
#== lc uc url_decode str_trim str_ltrim str_rtrim
#================================================

class Validator {

    public $success;
    public $valid;
    public $form;
    public $error;

    function Validator() {
        $this->success = 1;
        $this->valid = array();
        $this->form = array();
        $this->error = array();
    }

    #================检验请求参数的方法（参数可以是数组）================
    #==$va = new Validator();
    #==$va->check(array(
    #	'name1' => array('funcname1', 'funcname2', array('funcname3', funcparam1, funcparam2)),
    #	'name2' => array('funcname1', 'funcname2', array('funcname3', funcparam1, funcparam2)),
    #));
    #==$va->check(array(
    #	'funcname1' => array('funcname1', 'funcname2', array('funcname3', funcparam1, funcparam2)),
    #	'name2' => array('funcname1', 'funcname2', array('funcname3', funcparam1, funcparam2)),
    #));

    function check($data, $args) {
        if (is_array($args)) {
            foreach ($args as $arg) {
                $columnStr = array_shift($arg);
                $func_name = array_shift($arg);
                $func_param = $arg ? $arg : array();
                
                $columnArr = explode(",", $columnStr);
                foreach ($columnArr as $column) {
                    //如果某个字段已经验证不通过，则跳过
                    if(array_key_exists($column, $this->error)){
                        continue;
                    }else{
                        if (method_exists($this, $func_name)) {
                            $info = call_user_func(array($this, $func_name), $data[$column], $func_param);
                            $is_succ = $info[0];
                            $error_msg = isset($info[1]) ? $info[1] : FALSE;
                            if($is_succ==FALSE){
                                $this->error[$column] = $error_msg;
                            }
                        } else {
                            throw new Exception('VALIDATOR FUNCITON :' . $func_name . ' NOT EXISTS');
                        }
                    }
                }
                if(!empty($this->error)){
                    $this->success = FALSE;
                    break;
                }
            }
        } else {
            throw new Exception('MODEL RULES IS NOT AN ARRAY');
        }
    }

    #=======================================各类检验参数的函数============================================
    #=========函数接收3个参数，分别是请求参数名称(string)、请求参数值(string)、检验参数(array)=========
    #=========函数返回2个值，分别是标识是否通过检验的值(TRUE/FALSE)、错误信息(string)==========
    #==================常用函数==================
    #=========检验是否为空=========
    #==$va->check(array(
    #	'name' => array('required'),
    #));

    private function required($value, $func_param) {
        if (!empty($value) || $value === "0") {
            return array(TRUE);
        }
        return array(FALSE, '不能为空');
    }

    #=========检验是否为正整数=========
    #==$va->check(array(
    #	'name' => array('uint'),
    #));

    private function uint($value, $func_param) {
        if (is_string($value) && preg_match("/^\d*$/", $value) || is_int($value) && $value >= 0 || empty($value)) {
            return array(TRUE);
        }
        return array(FALSE, '只能为正整数');
    }

    #=========检验正整数是否在某个范围内=========
    #==$va->check(array(
    #	'name' => array(array('uint_length', 1, 100)),
    #));

    private function uint_length($value, $func_param) {
        if (!count($func_param) > 1) {
            die("VALIDATOR $name CHECK_FUNC uint_length PARAM ERROR");
        }
        $minValue = $func_param[0];
        $maxValue = $func_param[1];
        if (is_string($value) && preg_match("/^\d*$/", $value) && $value >= $minValue && $value <= $maxValue || is_int($value) && $value >= 0 || empty($value)) {
            return array(TRUE);
        }
        return array(FALSE, "必须在" . $minValue . "~" . $maxValue . "范围内");
    }

    #=========检验字符串长度是否在$minLen和$maxLen之间=========
    #==$va->check(array(
    #	'name' => array(array('length', 1, 255)),
    #));

    private function length($value, $func_param) {
        if (!count($func_param) > 1) {
            die("VALIDATOR $name CHECK_FUNC length PARAM ERROR");
        }
        $minLen = $func_param[0];
        $maxLen = $func_param[1];
        if (!(is_int($minLen) && $minLen >= 0 && is_int($maxLen) && $maxLen >= 0)) {
            die("VALIDATOR $name CHECK_FUNC length PARAM ERROR");
        }
        if (is_string($value) && $minLen <= strlen($value) && $maxLen >= strlen($value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, "长度必须为 $minLen~$maxLen");
    }

    #=========检验是否等于某些值=========
    #==$va->check(array(
    #	'name' => array(array('eq', 'param1', 'param2')),
    #));

    private function equal($value, $func_param) {
        if (in_array($value, $func_param['in']) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '输入不正确');
    }

    #==================专用函数==================
    #=========检验字符串是否仅由字母、数字、下划线组成=========
    #==$va->check(array(
    #	'name' => array('safe_text'),
    #));

    private function safe_text($value, $func_param) {
        if (is_string($value) && !preg_match("{[\\\/:*?'\"<>|&=]}su", $value) || empty($value)) {
            return array(TRUE);
        }
        return array(FALSE, "不能包含下列字符之一 \ / : * ? ' \" < > | & =");
    }

    #=========检验字符串是否不带引号=========
    #==$va->check(array(
    #	'name' => array('no_quot'),
    #));

    private function no_quot($value, $func_param) {
        if (is_string($value) && !preg_match("/['\"]/", $value) || empty($value)) {
            return array(TRUE);
        }
        return array(FALSE, "不允许使用引号");
    }

    #=========检验字符串是否为一个合法IP地址=========
    #==$va->check(array(
    #	'name' => array('ip_text'),
    #));

    private function ip_text($value, $func_param) {
        if (is_string($value) && preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, "不是合法的IP地址");
    }

    #=========检验字符串是否为一个合法EMAIL地址=========
    #==$va->check(array(
    #	'name' => array('email'),
    #));

    private function email($value, $func_param) {
        if (is_string($value) && preg_match("{^[\w\d_-]+(\.[\w\d_-]+)*@[\w\d_-]+(\.[\w\d_-]+)+$}i", $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '不是合法的email地址');
    }

    #=========检验字符串是否为一个合法的url地址=========
    #==$va->check(array(
    #	'name' => array('url'),
    #));

    private function url($value, $func_param) {
        if (is_string($value) && preg_match("/^https?:\/\/[\w\d_-]+\./i", $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '必须是http(s)://开始的合法域名');
    }

    #=========检验字符串是否符合日期格式=========
    #==$va->check(array(
    #	'name' => array('date_text'),
    #));

    private function date_text($value, $func_param) {
        if (is_string($value) && preg_match("/^\d{4}-\d{2}-\d{2}$/", $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '日期格式必须为yyyy-mm-dd');
    }

    #=========检验字符串是否符合时间格式=========
    #==$va->check(array(
    #	'name' => array('time_text'),
    #));

    private function time_text($value, $func_param) {
        if (is_string($value) && preg_match("/^\d{2}:\d{2}:\d{2}$/", $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '时间格式必须为hh:mm:ss');
    }

    #=========检验字符串是否符合日期时间格式=========
    #==$va->check(array(
    #	'name' => array('datetime_text'),
    #));

    private function datetime_text($value, $func_param) {
        if (is_string($value) && preg_match("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '日期时间格式必须为yyyy-MM-dd hh:mm:ss');
    }

    #=========检验字符串是否符合正则=========
    #==$va->check(array(
    #	'name' => array(array('preg', "{sdf}")),
    #));

    private function preg($value, $func_param) {
        $preg = array_shift($func_param);
        if (is_string($value) && preg_match($preg, $value) || empty($value) && $value !== "0") {
            return array(TRUE);
        }
        return array(FALSE, '不符合规范');
    }

    #==================改变请求参数值的函数==================
    #=========将请求字符串转换为小写=========
    #==$va->check(array(
    #	'name' => array('lc'),
    #));

    private function lc($value, $func_param) {
        return array(TRUE, '', strtolower($value));
    }

    #=========将请求字符串转换为大写=========
    #==$va->check(array(
    #	'name' => array('uc'),
    #));

    private function uc($value, $func_param) {
        return array(TRUE, '', strtoupper($value));
    }

    #=========将请求字符串进行url转义=========
    #==$va->check(array(
    #	'name' => array('url_decode'),
    #));

    private function url_decode($value, $func_param) {
        return array(TRUE, '', urldecode($value));
    }

    #=========去除请求字符串前后的空格=========
    #==$va->check(array(
    #	'name' => array('str_trim'),
    #));

    private function str_trim($value, $func_param) {
        return array(TRUE, '', trim($value));
    }

    #=========去除请求字符串前面的空格=========
    #==$va->check(array(
    #	'name' => array('str_ltrim'),
    #));

    private function str_ltrim($value, $func_param) {
        return array(TRUE, '', ltrim($value));
    }

    #=========去除请求字符串后面的空格=========
    #==$va->check(array(
    #	'name' => array('str_rtrim'),
    #));

    private function str_rtrim($value, $func_param) {
        return array(TRUE, '', rtrim($value));
    }

}

?>