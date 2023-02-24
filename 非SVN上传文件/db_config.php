<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 14:08
 */
return array(
    'adv_system' => array(
        'db_host' => '127.0.0.1.',
        'db_port' => '3306',
        'db_name' => 'adv_system',
        'db_user' => 'root',
        'db_pwd' => 'root'
    ),
    'cassandra' => array(
        'db_host' => '192.168.19.33',
        'db_port' => 9042,
        'db_name' => 'click',
    ),
    'redis' => array(
        'db_host' => '127.0.0.1',
        'db_port' => 6379,
        'db_pwd'  =>'',
    ),
    'start_scylla'=> 1 //0:关闭 1:开启
);