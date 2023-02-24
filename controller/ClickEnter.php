<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 16:52
 */
require_once(dirname(dirname(__FILE__)) . "/common/common.php");
require_once(dirname(dirname(__FILE__)) . "/model/AdvterInfo.php");
require_once(dirname(dirname(__FILE__)) . "/model/MatchClick.php");
require_once(dirname(__FILE__) . "/InsClick.php");


class ClickEnter
{
    protected $common, $config,$channel_config;

    protected $is_creative = false;

    public function __construct()
    {
        $this->config = require_once(dirname(dirname(__FILE__)) . "/config/config.php");
        $this->channel_config = require_once(dirname(dirname(__FILE__)) . "/config/channel_config.php");
    }

    public function index($data)
    {
        $this->common = new Common();

        $this->common->SafeFilter($data);

        $this->parment_filtration($data);

        $channel = isset($this->channel_config[$data['cname']]) ? $this->channel_config[$data['cname']] : '';

        $data_params = $data_match_params = $this->change_params($channel, $data);   //处理数据

        if ($this->config['start_scylla'] == 1) {
            try {
                $InsClick = new InsClick();
                $InsClick->index($data_params);   //写入cassandra

                $MatchClick = new MatchClick();
                $MatchClick->index($data_params);     //写入匹配表

            } catch (Exception $e) {
                error_log(date('Y-m-d H:i:s', time()) . $e->getMessage() . "\r\n", 3, dirname(dirname(__FILE__)) . '/log/' . date('Y-m-d', time()) . 'debug.log');
            }
        }
        // IOS双接、地址跳转
        if ($data['ostype'] == 1) {
            $advterInfo = new AdvterInfo();
            $channel_ver_info = $advterInfo->getInfo($data['channel_ver']);
            if (!isset($channel['log_return']) && $channel_ver_info){
                $this->common->HeaderLocation($channel_ver_info['download_url']);
            } else {
                $str = isset($channel['log_return'])  ? json_encode($channel['log_return']) : null;
                echo $str;
            }
        } else {
            $res = isset($channel['log_return']) ? json_encode($channel['log_return']) : '';
            echo $res;
        }
    }

    /**
     * @param $channel  渠道配置
     * @param $data     接收信息
     */
    protected function change_params($channel, $data)
    {
        //公共转换参数
        $fixed = ['user_agent' => '', 'idfa' => '', 'ip' => '', 'extrainfo' => '', 'imei' => '', 'timestamp' => '','put_creative_id'=>0,'put_plan_id'=>0,'put_campaign_id'=>0]; //有可能需要转换的参数
        $params = isset($channel['log_params']) ? $channel['log_params'] : '';
        $extrainfo = json_encode($_SERVER['QUERY_STRING']);

        //广告宏参转换
        if ($params) {
            foreach ($fixed as $field => $value) {
                foreach ($params as $k => $v) {
                    if (!isset($data[$field]) && $k == $field) {
                        $data[$field] = isset($data[$v]) ? $data[$v] : '';
                        $data['extrainfo'] = $extrainfo;
                    }
                }
            }
        }
        // 时间戳毫秒转秒
        if (isset($data['timestamp']) && strlen($data['timestamp']) > 10) {
            $data['timestamp'] = $data['timestamp'] / 1000;
        }

        $row['imei'] = $data['imei'] = isset($data['imei']) ? $data['imei'] : '';
        $row['idfa'] = $data['idfa'] = isset($data['idfa']) ? $data['idfa'] : '';


        //过滤字段
        $exclude_list = [
            1 => ['__IFDA__', '00000000-0000-0000-0000-000000000000', ''],
            2 => ['__IMEI__', '']
        ];
        $muid_list = [1=>'idfa', 2=>'imei'];
        $muid = $data[$muid_list[$data['ostype']]]; //$data['idfa'] / $data['imei']
        $muid = isset($muid) && in_array($muid, $exclude_list[$data['ostype']]) ? '' : $muid; //参数过滤

        $commonModel = new Common();
        $row[$muid_list[$data['ostype']]] = $commonModel->checkParam($muid) ;  //$row['idfa'] / $row['imei']
        $row['oaid'] = isset($data['oaid']) ? $commonModel->checkParam($data['oaid']) : '';
        $row['android_id'] = isset($data['android_id']) ? $commonModel->checkParam($data['android_id']) : '';

        //已下为公共参数
        $row['sign_key'] = $commonModel->getUniqid();
        $row['os_type'] = isset($data['optype']) ? $data['optype'] : 1;
        $row['game_id'] = isset($data['game_id']) ? $data['game_id'] : 0;
        $row['ctime'] = isset($data['timestamp']) ? $data['timestamp'] : time();
        $row['channel_ver'] = isset($data['channel_ver']) ? $data['channel_ver'] : '';
        $row['ver_id'] = isset($data['ver_id']) ? $data['ver_id'] : '';
        $row['extrainfo'] = isset($data['extrainfo']) ? $data['extrainfo'] : $extrainfo;
        $row['user_ip'] = isset($data['ip']) && $commonModel->checkIp($data['ip']) ? $data['ip'] : $commonModel->get_client_ip();
        $row['user_agent'] = isset($data['user_agent']) ? $data['user_agent'] : (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');

        if (isset($data['put_creative_id'])) {
            $this->is_creative = true;
            $row['channel_type'] = isset($channel['creative_type']) ? $channel['creative_type'] : 1; //渠道类型
            $row['cid'] = isset($data['put_creative_id']) ? $data['put_creative_id'] : 0; //创意ID
            $row['aid'] = isset($data['put_plan_id']) ? $data['put_plan_id']: 0; //计划ID
            $row['campaign_id'] = isset($data['put_campaign_id']) ? $data['put_campaign_id'] : 0; //广告主ID
            $row['aid_name'] = isset($data['aid_name']) ?  $data['aid_name'] : ''; //计划名称
        }
        if (isset($_GET['debug']) && $_GET['debug'] == 'lizc'){
            var_dump($row);exit;
        }
        return $row;
    }

    /**
     * 参数过滤判断
     * @param $data
     */
    protected function parment_filtration($data)
    {
        if (!isset($data['ostype']) || !isset($data['channel_ver']) || !isset($data['game_id'])) {
            die($this->common->Json(201));
        }
    }

}