<?
/**
 * 0：不显示错误信息，也不显示调试信息
 * 1:显示错误信息，但是不显示调试信息
 * 2：显示错误信息，也显示调试信息
 */
define('BEE_DEBUG',1);
$bee = "./Lib/Bee.php";
$config = "./Config/main.php";
require_once($bee);
$app = new Bee($config);
$app->run();
?>