<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 14:19
 */
require_once(dirname(dirname(__FILE__)) . "/db/CassandraDb.php");
require_once(dirname(dirname(__FILE__)) . "/model/CommonModel.php");

class CreateCass extends CommonModel
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * IOS点击日志表
     */
    /*public function createClickIosLog()
    {
        $sql = "
        CREATE TABLE common_click_ios_log ( 
    idfa varchar, 
    advter_id int, 
    app_id int, 
    user_agent varchar,
    user_ip inet,
    country varchar,
    area varchar,
    ctime int,
    extrainfo text,
    PRIMARY KEY ( advter_id,idfa,ctime) )WITH CLUSTERING ORDER BY (idfa DESC) ;
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }*/

    /**
     * 点击日志表
     */
    public function createClickIosLog()
    {
        $sql = "
        CREATE TABLE common_click_log ( 
            sign_key varchar, 
            imei varchar, 
            idfa varchar, 
            oaid varchar,
            android_id varchar,
            channnel_ver varchar,  
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
     * Android点击日志表
     */
    /*public function createClickAndroidLog()
    {
        $sql = "
        CREATE TABLE common_click_android_log ( 
    imei varchar,
    udid varchar,
    advter_id int, 
    app_id int, 
    user_agent varchar,
    user_ip inet,
    country varchar,
    area varchar,
    ctime int,
    extrainfo text,
    PRIMARY KEY ( advter_id,imei,ctime) )WITH CLUSTERING ORDER BY (imei DESC);
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }*/

    public function createClickAndroidLog()
    {
        $sql = "
       CREATE TABLE common_click_android_log ( 
	    sign_key varchar, 
        imei varchar,
        advter_id int,  
        app_id int,     
        user_agent varchar,  
        user_ip inet,
        country varchar,
        area varchar,
        ctime int,   
        extrainfo text,    
        PRIMARY KEY ( sign_key) );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * Android点击IP表
     */
    public function createClickAndroidIpLog()
    {
        $sql = "
        CREATE TABLE common_click_android_ip_log ( 
          sign_key VARCHAR, 
          android_sign_key VARCHAR ,
          PRIMARY KEY ( sign_key) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * IOS点击IP表
     */
    public function createClickIosIpLog()
    {
        $sql = "
        CREATE TABLE common_click_ios_ip_log ( 
          sign_key varchar, 
          ios_sign_key VARCHAR,
          PRIMARY KEY ( sign_key) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }



    /**
     * Android匹配激活表
     */
    public function createMatchAndroidActivate()
    {
        $sql = "
        CREATE TABLE match_activate_android ( 
    gid int,
    udid varchar,
    PRIMARY KEY ( gid,udid) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);

    }

    /**
     * Android渠道匹配表-imei
     */
    public function createMatchAndroidChannelImei()
    {
        $sql = "
        CREATE TABLE match_android_channel_imei ( 
    imei varchar,
    channel int,
    chn_name varchar,
    PRIMARY KEY ( imei) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-udid
     */
    public function createMatchAndroidChannelUdid()
    {
        $sql = "
        CREATE TABLE match_android_channel_udid ( 
    udid varchar,
    channel int,
    chn_name varchar,
    PRIMARY KEY ( udid) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-ip
     */
    public function createMatchAndroidChannelIp()
    {
        $sql = "
        CREATE TABLE match_android_channel_ip ( 
    ip varchar,
    channel int,
    chn_name varchar,
    PRIMARY KEY ( ip) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * IOS渠道匹配表-idfa
     */
    public function createMatchIosChannelIdfa()
    {
        $sql = "
        CREATE TABLE match_ios_channel_idfa ( 
    idfa varchar,
    channel int,
    chn_name varchar,
    PRIMARY KEY ( idfa) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * IOS渠道匹配表-mac
     */
    public function createMatchIosChannelMac()
    {
        $sql = "
        CREATE TABLE match_ios_channel_mac ( 
    mac varchar,
    channel int,
    chn_name varchar,
    PRIMARY KEY ( mac) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * IOS渠道匹配表-ip
     */
    public function createMatchIosChannelIp()
    {
        $sql = "
        CREATE TABLE match_ios_channel_ip ( 
    ip varchar,
    channel int,
    chn_name varchar,
    PRIMARY KEY ( ip) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * 渠道匹配规则表
     */
    public function createMatchRule()
    {
        $sql = "
           CREATE TABLE match_rule(
      advter_id int,
      type int,
      first_rule int,
      second_rule int,
      thirdly_rule int,			
      extrainfo text,
      PRIMARY KEY (advter_id) ); 
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }




    /**
     * 打开汇总表
     */
    public function createOpenCollectLog()
    {
        $sql = "
           CREATE TABLE open_collect_log ( 
        ver varchar,  
        url varchar, 
        open_num counter, 
        dis_open_num counter, 
        ctime int,
        PRIMARY KEY ( ctime,ver,url) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * 下载汇总表
     */
    public function createDownloadCollectLog()
    {
        $sql = "
           CREATE TABLE download_collect_log ( 
        ver varchar,  
        url varchar, 
        download_num counter, 
        dis_download_num counter, 
        ctime int,  
        PRIMARY KEY ( ctime,ver,url) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /*删除表*/
    public function deletetable($table)
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

    public function createChannelConfig()
    {
        $sql = "
            CREATE TABLE channel_config(
                channel_id int,
                channel_name varchar,
                channel_code varchar,
                callback_func varchar,
                ios_click_param varchar,
                android_click_param varchar,
                PRIMARY KEY(channel_id,channel_code)
            );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * 渠道回调配置表
     * @return [type] [description]
     */
    public function createChnnCallbackConf()
    {
        $sql = "
            CREATE TABLE channel_callback_config(
                channel_code varchar,
                game_id int,
                adver_id int,
                sign_key varchar,
                encrypt_key varchar,
                extrainfo varchar,
                PRIMARY KEY(channel_code,game_id,adver_id)
            );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    public function createAdvterInfo()
    {
        $sql = "
            CREATE TABLE advter_info(
                advter_id int,
                game_id int,
                channel_from int,
                appstore_id varchar,
                appstore_url varchar,
                reyun_url varchar,
                adtrack_url varchar,
                PRIMARY KEY(advter_id)
            );
        ";
        $res = $this->cass->CreateStickTable($sql);
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


    public function createAndroidInfo()
    {
        $sql = "
            CREATE TABLE android_info(
                advter_id int,
                game_id int,
                channel_from int,
                ver varchar,
                callback_url varchar,
                PRIMARY KEY(advter_id)
            );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * oppoSDK基础表
     */
    public function cteateOpppLog()
    {
        $sql = "
        CREATE TABLE common_oppo_log ( 
    ssoid varchar,
    channel int,
    adid int, 
    game_id int, 
    ver varchar,
    type int,
    amount int,
    ctime int,
    PRIMARY KEY ( adid,ctime,ver) )WITH CLUSTERING ORDER BY (ctime DESC);
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * OPPO汇总表
     */
    public function createOppoCollectLog()
    {
        $sql = "
           CREATE TABLE oppo_collect_log ( 
        adid int,   
        pay_num counter,  
        login_num counter,  
        reg_num counter,  
        ctime int,  
        ver varchar, 
        PRIMARY KEY ( ctime,adid,ver) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * OPPO注册汇总表
     */
    public function createOppoRegLog()
    {
        $sql = "
           CREATE TABLE oppo_reg_log ( 
        ssoid varchar,  
        ctime int,  
        adid int,   
        PRIMARY KEY ( ssoid,ctime,adid) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * OPPO注册汇总表
     */
    public function createOppoPayLog()
    {
        $sql = "
           CREATE TABLE oppo_pay_log ( 
        adid int,   
        ssoid varchar,  
        ctime int,  
        amount int,
        PRIMARY KEY ( ssoid,ctime,adid) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * 积分墙idfa临时排重表
     */
    public function createIntegralLog()
    {
        $sql = "
           CREATE TABLE integral_log ( 
        appstore_id int,   
        channel_id int,
        idfa varchar,  
        ctime int,  
        PRIMARY KEY ( idfa,appstore_id,channel_id) ) ;
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * 积分墙idfa激活表
     */
    public function createIntegralActivateLog()
    {
        $sql = "
           CREATE TABLE integral_activate_log ( 
        appstore_id int,   
        channel_id int,
        idfa varchar,  
        ctime int,  
        PRIMARY KEY ( idfa,appstore_id,channel_id) ) ;
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * 积分墙idfa激活表(统计用)
     */
    public function createIntegralActivateCronLog()
    {
        $sql = "
           CREATE TABLE integral_activate_cron_log ( 
        appstore_id int,   
        idfa varchar,  
        channel_id int,
        ctime int,  
        PRIMARY KEY ( ctime,idfa,appstore_id) ) ;
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    public function createIntegralWallInfo()
    {
        $sql = "
            CREATE TABLE Integral_wall_info(
                integral varchar,
                advter_id int,
                PRIMARY KEY(integral)
            );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    public function createCommonClickAndroidLog()
    {
        $sql = "
            CREATE TABLE common_click_android_log_bak ( 
    imei varchar,
    udid varchar,  
    advter_id int,  
    app_id int,     
    user_agent varchar,  
    user_ip inet,
    country varchar,
    area varchar,
    ctime int,  
    extrainfo text,
    PRIMARY KEY ( imei,advter_id,user_ip) );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * VIVO汇总表
     */
    public function createVivoCollectLog()
    {
        $sql = "
           CREATE TABLE vivo_collect_log ( 
        adid int,   
        pay_num counter,  
        amount counter,  
        reg_num counter,  
        ctime int,  
        ver varchar, 
        PRIMARY KEY ( ctime,adid,ver) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Vivo注册汇总表
     */
    public function createVivoRegLog()
    {
        $sql = "
           CREATE TABLE vivo_reg_log ( 
        vivo_key varchar,
        open_id varchar,
        channel varchar,  
        ctime int,  
        adid int,   
        PRIMARY KEY (vivo_key) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * SDK表
     */
    public function createSdkLog()
    {
        $sql = "
           CREATE TABLE sdk_log ( 
         sdk_key varchar,
         type int,   
         ctime int,  
         ver varchar, 
        PRIMARY KEY ( sdk_key,type) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * SDK表
     */
    public function createSdkPayLog()
    {
        $sql = "
           CREATE TABLE sdk_pay_log ( 
         sdk_key varchar,
         ctime int,
         num counter,  
         amount counter,  
        PRIMARY KEY ( sdk_key,ctime) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }



    /**
     * VIVO汇总表
     */
    public function createSdkCollectLog()
    {
        $sql = "
           CREATE TABLE sdk_collect_log ( 
        adid int,
        ctime int,
        ver varchar, 
        act_num counter,  
        reg_num counter,  
        pay_num counter,  
        amount counter,  
        PRIMARY KEY ( ctime,adid,ver) );
	  ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-imei
     */
    public function createMatchAndroidChannelImeiCreative()
    {
        $sql = "
        CREATE TABLE match_android_channel_imei_creative ( 
    key varchar,
    aid varchar,
    cid varchar,
    PRIMARY KEY ( key) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-ip
     */
    public function createMatchAndroidChannelIpCreative()
    {
        $sql = "
        CREATE TABLE match_android_channel_ip_creative ( 
    key varchar,
    aid varchar,
    cid varchar,
    PRIMARY KEY ( key) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    /**
     * IOS渠道匹配表-idfa
     */
    public function createMatchIosChannelIdfaCreative()
    {
        $sql = "
        CREATE TABLE match_ios_channel_idfa_creative ( 
    key varchar,
    aid varchar,
    cid varchar,
    PRIMARY KEY ( key) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-ip
     */
    public function createMatchIosChannelIpCreative()
    {
        $sql = "
        CREATE TABLE match_ios_channel_ip_creative ( 
            key varchar,
            aid varchar,
            cid varchar,
            PRIMARY KEY ( key) );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


    public function createClickAndroidOaidLog()
    {
        $sql = "
       CREATE TABLE common_click_android_oaid_log ( 
	    sign_key varchar, 
        oaid varchar,
        advter_id int,  
        app_id int,     
        user_agent varchar,  
        user_ip inet,
        country varchar,
        area varchar,
        ctime int,   
        extrainfo text,    
        PRIMARY KEY ( sign_key) );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-oaid
     */
    public function createMatchIosChannelOaidCreative()
    {
        $sql = "
        CREATE TABLE match_ios_channel_oaid_creative ( 
            key varchar,
            aid varchar,
            cid varchar,
            PRIMARY KEY ( key) );
        ";
        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }

    /**
     * Android渠道匹配表-ip
     */
    public function createMatchAndroidChannelOaidCreative()
    {
        $sql = "
        CREATE TABLE match_android_channel_oaid_creative ( 
    key varchar,
    aid varchar,
    cid varchar,
    PRIMARY KEY ( key) );
        ";

        $res = $this->cass->CreateStickTable($sql);
        var_dump($res);
    }


}