<?php
class AppConfig{
    //数据库配置
//    public static $databaseSet = array(
//        'host'=>"127.0.0.1",
//        'username'=>"zouliming",
//        'password'=>"caozuo",
//        'database' => "vendorplatform",
//    );
    public static $databaseSet = array(
        'host'=>"10.100.90.170",
        'username'=>"vipshop",
        'password'=>"vipshop123",
        'database' => "vendorplatform",
    );
    //cookie配置
    public static $cookieSet = array(
        'domain' => APP_DOMAIN,
        'path' => '/',
        'lifetime' => '36000',
        'expire' => '36000',
        'secure' => false,
    );
}

?>