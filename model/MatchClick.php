<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29
 * Time: 10:51
 */
require_once(dirname(dirname(__FILE__)) . "/db/CassandraDb.php");
require_once(dirname(dirname(__FILE__)) . "/model/CommonModel.php");

class MatchClick extends CommonModel
{
    const MATCH_IDFA = 'match_channel_idfa';
    const MATCH_IMEI = 'match_channel_imei';
    const MATCH_OAID = 'match_channel_oaid';
    const MATCH_IP   = 'match_channel_ip';
    const MATCH_ANDROID_ID = 'match_channel_android_id';
    const EFFECT_DAY = 14;

    public function __construct()
    {
        parent::__construct();
    }

    public function index($data)
    {
        //写入匹配表
        $device = ['imei','idfa','oaid','android_id','idfa','user_ip'];
        foreach ($data as $k=>$v){
            if (in_array($k,$device) && $v != ''){
                switch ($k){
                    case 'imei': $table = static::MATCH_IMEI; break;
                    case 'idfa':$table = static::MATCH_IDFA;break;
                    case 'oaid':$table = static::MATCH_OAID;break;
                    case 'android_id':$table = static::MATCH_ANDROID_ID;break;
                    case 'user_ip':$table = static::MATCH_IP;break;
                    default : continue;
                }
                //利用channel_ver合成key
                $ins_ver_data['key'] = md5($v.$data['ver_id']);
                $ins_ver_data['log_key'] = $data['sign_key'];
                $this->add($table,$ins_ver_data);

                //利用game_id合成key
                $ins_game_data['key'] = md5($v.$data['game_id']);
                $ins_game_data['log_key'] = $data['sign_key'];
                $this->add($table,$ins_game_data);
            }

        }

    }


    /**
     * 返回是否插入成功
     * @param $data
     * @return bool
     */
    public function add($table,$data)
    {
        $dmap = [
            'key'=>['=', $data['key']],
        ];
        $res = $this->cass->Delete($table, $dmap);
        $time = static::EFFECT_DAY * 86400;
        $res = $this->cass->Insert($table, $data, $time);
        return true;
    }


}