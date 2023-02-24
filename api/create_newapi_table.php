<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 15:36
 */
ini_set('display_errors', 1);            //错误信息
ini_set('display_startup_errors', 1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息
require_once(dirname(dirname(__FILE__)) . "/model/CreateNewapiCass.php");
$create = new CreateNewaiCass();



/*$create->createCommonMember();
$create->createMemberSdkToken();
$create->createCommonMemberUdid();
$create->createCommonMemberSim();
$create->createCommonMemberName();
$create->CreateIndex('uid', 'app_common_member', 'uid');*/

//$create->createMemberUidToken();

//$create->CreateIndex('android_user_ip', 'common_click_android_log_bak', 'user_ip');
//$create->CreateIndex('ios_user_ip', 'common_click_ios_log', 'user_ip');

//$create->dropIndex('user_ip');
//$create->dropIndex('ios_user_ip');

//新建表
//$create->CreateKeyspace();
//$create->createClickLog();
//$create->createMatchChannelOaid();
//$create->createMatchChannelImei();
//$create->createMatchChannelAndroidId();
//$create->createMatchChannelIdfa();
//$create->createMatchChannelIp();
















//删除表
//$create->deletetable('app_common_member_udid');

//清空表
//$create->deletetable('common_click_log');


