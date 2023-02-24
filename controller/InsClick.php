<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29
 * Time: 14:08
 */

require_once(dirname(dirname(__FILE__)) . "/model/ClickLog.php");

class InsClick
{
    /**
     * @param $data
     * @param $type
     * @return bool|void
     */
    public function index($data)
    {
        $data = [
            'sign_key'=>$data['sign_key'],
            'imei'=>$data['imei'],
            'idfa'=>$data['idfa'],
            'oaid'=>$data['oaid'],
            'user_ip'=>$data['user_ip'],
            'game_id'=>$data['game_id'],
            'ctime'=>(string)$data['ctime'],
            'user_agent'=>$data['user_agent'],
            'android_id'=>$data['android_id'],
            'cid'=>isset($data['cid']) ? $data['cid'] : '0',
            'aid'=>isset($data['aid']) ? $data['aid'] : '0',
            'channel_type'=>isset($data['channel_type']) ? (string)$data['channel_type'] : '0',
            'channel_ver'=>$data['ver_id'],
            'extrainfo'=>$data['extrainfo'],
        ];
        $clickOaidLog = new ClickLog();
        $res = $clickOaidLog->add($data);
        return $res;
    }
}