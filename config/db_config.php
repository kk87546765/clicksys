<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Time: 14:08
 */
return array(
    'adv_system' => array(
        'db_host' => '192.168.0.173',
        'db_port' => '3306',
        'db_name' => 'adv_system',
        'db_user' => 'put_read',
        'db_pwd' => 'TX52TdrUW!@ird0yMubP'
    ),

    'redis' => array(
        'db_host' => '127.0.0.1',
        'db_port' => 6379,
        'db_pwd'  =>'',
    ),

    'cassandra' => array(
//        'db_host' => 'cds-proxy-pub-wz97i3921v60924v-1-core-003.cassandra.rds.aliyuncs.com',  //公网
        'db_host'=>'cds-wz97i3921v60924v-1-core-003.cassandra.rds.aliyuncs.com', //私网
        'db_port' => 9042,
        'db_name' => 'click',
        'db_user' => 'cassandra',
        'db_pwd' => 'xiLIXN@4874216q',
    ),

    'start_scylla'=>1 //是否开启scylla
);