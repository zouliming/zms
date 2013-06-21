<?php
//设置DEBUG模式
defined('BEE_DEBUG') or define('BEE_DEBUG',FALSE);

define("SEP",substr(PHP_OS,0,3)=='WIN'?"\\":"/");
//项目根目录
define('APP_DIRECTORY', str_replace('/', SEP, $_SERVER['DOCUMENT_ROOT']));
//类库目录
define('LIB_DIRECTORY',__DIR__);
//Model目录
define('MODEL_DIRECTORY',APP_DIRECTORY.SEP.'Model');
//view目录
define('VIEW_DIRECTORY',APP_DIRECTORY.SEP.'View');
//控制器目录
define('CONTROLLER_DIRECTORY',APP_DIRECTORY.SEP.'Controller');
//配置文件目录
define('CONFIG_DIRECTORY',APP_DIRECTORY.SEP.'Config');

//设置默认的控制器
define('DEFAULT_CONTROLLER','welcome');
//设置默认的Action
define('DEFAULT_ACTION','index');

//设置Debug
if(BEE_DEBUG){
    define('BEE_BEGIN_TIME',      microtime(TRUE));
    define('BEE_MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
    if(BEE_MEMORY_LIMIT_ON) define('BEE_START_MEMS', memory_get_usage());
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
}else{
    error_reporting(0);
}
?>