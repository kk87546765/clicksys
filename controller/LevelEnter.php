<?php

require_once(dirname(dirname(__FILE__)) . "/common/common.php");
require_once(dirname(dirname(__FILE__)) . "/model/LevelInfo.php");

class LevelEnter
{
    protected $common, $config,$data;

    protected $is_creative = false;

    public function __construct()
    {
        $this->config = require_once(dirname(dirname(__FILE__)) . "/config/config.php");

    }

    public function index($data)
    {

        $this->common = new Common();

        $this->common->SafeFilter($data);

        $this->parment_filtration($data);

        $data_params = $this->change_params($data);

        try {
            $LevelInfo = new LevelInfo();
            $LevelInfo->setInfo($data_params);   //写入队列

        } catch (Exception $e) {
            error_log(date('Y-m-d H:i:s', time()) . $e->getMessage() . "\r\n", 3, dirname(dirname(__FILE__)) . '/log/' . date('Y-m-d', time()) . 'debug_level.log');
        }
        echo "ok";



    }

    /**
     * @param $data     接收信息
     */
    protected function change_params($data)
    {
        $time = time();
        $extension = $data['extension'];
        $channelInfo = $data['channelInfo'];
        $new_data_list = [];
        $new_data_list['appid'] = isset($data['appid']) ? $data['appid'] : 0;
        $new_data_list['uid'] = isset($extension['userID']) ? $extension['userID'] : 0;
        $new_data_list['sdk_uid'] = isset($channelInfo['sdkUserID']) ? $channelInfo['sdkUserID'] : 0;
        $new_data_list['add_time'] = $time;
        $new_data_list['levelup_time'] = $time;
        $new_data_list['role_id'] = isset($extension['roleID']) ? $extension['roleID'] : 0;
        $new_data_list['role_name'] = isset($extension['roleName']) ? $extension['roleName'] : '';
        $new_data_list['role_level'] = isset($extension['roleLevel']) ? $extension['roleLevel'] : 0;
        $new_data_list['role_server'] = isset($extension['serverName']) ? $extension['serverName'] :'';
        $new_data_list['server_id'] = isset($extension['serverID']) ? $extension['serverID'] :0;
        $new_data_list['trans_level'] = isset($extension['roleTransLevel']) ? $extension['roleTransLevel'] :0;
        $new_data_list['extension'] = isset($channelInfo['extension']) ? $channelInfo['extension'] :0;
        $new_data_list['type'] = isset($extension['type']) ? $extension['type'] : 'levelup';
        return $new_data_list;
    }

    /**
     * 参数过滤判断
     * @param $data
     */
    protected function parment_filtration($data)
    {
        if (($data['extension']['type'] != 'login' && $data['extension']['type'] != 'levelup')||!isset($data['channelInfo']['sdkUserID']) ||!isset($data['extension']['serverID']) || !isset($data['extension']['userID']) || !isset($data['appid'])|| !isset($data['extension']['roleID'])|| !isset($data['extension']['roleLevel'])) {
            die($this->common->Json(201));
        }
    }

}