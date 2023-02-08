<?php

/* $database_config = [
    'driver'=>'mysql',
    'host'=> 'localhost',
    'database'=> 'cobroco_wp482',
    'username'=> 'cobroco_wp482',
    'password'=> '9KSCA(]p77!-1(6P',
    'charset'=>'utf8',
    'collation'=>'utf8_unicode_ci',
    'prefix'=>''

]; */

$database_config = [
    'driver'=>'mysql',
    'host'=>'127.0.0.1',
    'database'=>'bd_diamante',
    'username'=>'root',
    'password'=>'',
    'charset'=>'utf8',
    'collation'=>'utf8_unicode_ci',
    'prefix'=>''

];


$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($database_config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;