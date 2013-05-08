<?php
/*
 * 这个类用来定义一些常量
 * 
 */

// 设置时区,上海--以免出现时间不正确的情况
date_default_timezone_set("Asia/Shanghai");

//设置默认的控制器
define('DEFAULT_CONTROLLER','welcome');
//设置默认的Action
define('DEFAULT_ACTION','index');

//设置默认的后台session名称
define('VPF_SESSION','vpfsession');

define('APP_DOMAIN','zouliming.com');
define('APP_URL','http://v.zouliming.com');
define('JS_URL',APP_URL.'/js/');
define('CSS_URL',APP_URL.'/css/');
?>