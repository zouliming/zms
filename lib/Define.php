<?php
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
?>