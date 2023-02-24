<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 14:19
 */
require_once(dirname(dirname(__FILE__)) . "/db/CassandraDb.php");
require_once(dirname(dirname(__FILE__)) . "/model/CommonModel.php");

class CreateNewaiCass extends CommonModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function CreateKeyspace()
    {
        $res = $this->cass->CreateKeyspace('click');
        var_dump($res);
    }



    /*删除表*/
    public  function deletetable($table)
    {
        $res = $this->cass->DropTable($table);
        var_dump($res);
    }

    /**
     * 清空表
     */
    public function TruncateTable($table)
    {
        $res = $this->cass->TruncateTable($table);
        var_dump($res);
    }

    /**
     * 创建索引
     * @param $IndexName
     * @param $TableName
     * @param $FieldName
     */
    public function createIndex($IndexName, $TableName, $FieldName)
    {
        $res = $this->cass->CreateIndex($IndexName, $TableName, $FieldName);
        var_dump($res);
    }

    /**
     * 删除索引
     * @param $IndexName
     */
    public function dropIndex($IndexName)
    {
        $res = $this->cass->DropIndex($IndexName);
        var_dump($res);
    }


    /**
     * 一键登录用户UDID表
     */
    public function createCommonMemberUdid()
    {
        $sql = "CREATE TABLE app_common_member_udid( 
    udid varchar,
    reg_gid int,
    new_user int,
    login_date int,
    uid int,
    username varchar,
    extrainfo text,
    PRIMARY KEY (udid,reg_gid,new_user,login_date));
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * 简化的用户登录表
     */
    public function createCommonMemberSim()
    {
        $sql = "CREATE TABLE app_common_member_sim(  
    username varchar,
    password varchar,
    uid int,
    cid int,
    extrainfo text,
    PRIMARY KEY (username,password) ) ;
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * 一键注册名字随机数
     */
    public function createCommonMemberName()
    {
        $sql = "
        CREATE TABLE app_common_member_name ( 
    platform_id int,
    name_num counter,
    PRIMARY KEY ( platform_id) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);

    }


    /**
     * 用户主表
     */
    public function createCommonMember()
    {
        $sql = "CREATE TABLE app_common_member(  
    username varchar,
    password varchar,
    mobile varchar,
    uid int,
    cid int,
    udid varchar,
    status int,
    safe_ask varchar,
    bund_mobile int,
    login_hits int,
    reg_ver varchar,
    new_user int,
    reg_date int,
    extrainfo text,
    idcard varchar
    PRIMARY KEY (username) ) ;
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    public function createMemberSdkToken()
    {
        $sql = "CREATE TABLE app_common_member_sdktoken(  
    sdktoken varchar,
    uid varchar,
    extrainfo text,
    PRIMARY KEY (uid) ) ;
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    public function createMemberUidToken()
    {
        $sql = "CREATE TABLE app_common_member_uid_token(  
    uid int,
    tokens varchar,
    sdktoken varchar,
    openunique varchar,
    unionid varchar,
    nickname varchar,
    extrainfo text,
    PRIMARY KEY (uid) ) ;
        ";


        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    public function createMemberGame()
    {
        $sql = "CREATE TABLE app_common_member_game(  
    uid int,
    gid int,
    ver varchar,
    ctime int,
    extrainfo text,
    PRIMARY KEY (uid,gid) ) ;
        ";


        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * 点击日志表
     */
    public function createClickLog()
    {
        $sql = "
        CREATE TABLE common_click_log ( 
            sign_key varchar, 
            imei varchar, 
            idfa varchar, 
            oaid varchar,
            android_id varchar,
            channel_ver varchar,  
            game_id varchar,
            cid int,
            aid int,
            type int,
            user_agent varchar,  
            user_ip inet,
            ctime int,
            extrainfo text,   
            PRIMARY KEY (sign_key) )";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * 匹配表-oaid
     */
    public function createMatchChannelOaid()
    {
            $sql = "
            CREATE TABLE match_channel_oaid ( 
        key varchar, 
        log_key varchar,
        PRIMARY KEY ( key) );
            ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * 匹配表-imei
     */
    public function createMatchChannelImei()
    {
        $sql = "
            CREATE TABLE match_channel_imei ( 
        key varchar, 
        log_key varchar,
        PRIMARY KEY ( key) );
            ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }
    /**
     * 匹配表-android_id
     */
    public function createMatchChannelAndroidId()
    {
        $sql = "
            CREATE TABLE match_channel_android_id ( 
        key varchar, 
        log_key varchar,
        PRIMARY KEY ( key) );
            ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }
    /**
     * 匹配表-oaid
     */
    public function createMatchChannelIdfa()
    {
        $sql = "
            CREATE TABLE match_channel_idfa ( 
        key varchar, 
        log_key varchar,
        PRIMARY KEY ( key) );
            ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }
    /**
     * 匹配表-oaid
     */
    public function createMatchChannelIp()
    {
        $sql = "
            CREATE TABLE match_channel_ip ( 
        key varchar, 
        log_key varchar,
        PRIMARY KEY ( key) );
            ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }



}