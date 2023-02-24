<?php
ini_set('display_errors', 1);            //错误信息
ini_set('display_startup_errors', 1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息

header("Access-Control-Allow-Origin:*");
/**
 * Created by PhpStorm.
 * User: Administrator
 */
require_once(dirname(__FILE__) . "/controller/LevelEnter.php");
$data = $_REQUEST;   //接收参数


$click = new LevelEnter();
$click->index($data);





