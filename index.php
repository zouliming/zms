<?
define("SEP",substr(PHP_OS,0,3)=='WIN'?"\\":"/");
//项目根目录
define('APP_DIRECTORY', dirname(__FILE__).SEP);
//类库目录
define('LIB_DIRECTORY',APP_DIRECTORY.'Lib');
//Model目录
define('MODEL_DIRECTORY',APP_DIRECTORY.'Model');
//view目录
define('VIEW_DIRECTORY',APP_DIRECTORY.'View');
//控制器目录
define('CONTROLLER_DIRECTORY',APP_DIRECTORY.'Controller');
//配置文件目录
define('CONFIG_DIRECTORY',APP_DIRECTORY.'Config');

//加载全局定义常量
require_once CONFIG_DIRECTORY . SEP . 'define.php';
//加载全局函数库
require_once LIB_DIRECTORY . SEP . 'Common.php';
//加载核心类库文件
require_once LIB_DIRECTORY . SEP . 'Loader.php';
Loader::setBasePath(
    array(
        LIB_DIRECTORY,
        CONFIG_DIRECTORY,
        CONTROLLER_DIRECTORY,
        MODEL_DIRECTORY,
        VIEW_DIRECTORY
    )
);
$app = new Bee();
$app->run();
?>