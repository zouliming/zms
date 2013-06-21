<?
define('BEE_DEBUG',true);
$bee = "./Lib/Bee.php";
$config = "./Config/main.php";
require_once($bee);
$app = new Bee($config);
$app->run();
?>