<?php
return array(
    'project'=>array(
        'name'=>'管理后台',
        'timezone'=>'Asia/Shanghai',
        'domain'=>'zms.zouliming.com',
        'defaultPageTitle'=>'页面',
    ),
    'database'=>array(
        'host'=>"127.0.0.1",
        'username'=>"zouliming",
        'password'=>"caozuo",
        'database' => "zms",
    ),
    'cookie'=>array(
        'domain' => 'zouliming.com',
        'path' => '/',
        'lifetime' => '36000',
        'expire' => '36000',
        'secure' => false,
    ),
    //需要导入的文件
    'importFiles'=>array(
        'application.config.global'
    )
);
?>