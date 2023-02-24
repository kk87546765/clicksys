<?php
ini_set('display_errors', 1);            //错误信息
ini_set('display_startup_errors', 1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息

header("Access-Control-Allow-Origin:*");
/**
 * Created by PhpStorm.
 * User: Administrator
 */



require_once(dirname(__FILE__) . "/controller/kefuChat.php");
require_once(dirname(__FILE__) . "/model/WriteFile.php");

$data = $_REQUEST;   //接收参数

if(empty($data)){
    $post_data = file_get_contents('php://input', 'r');
    $data = json_decode($post_data,true);
}

$click = new kefuChat();
$write = new writeFile();
$write->write($data, 1);


$click->index($data);





