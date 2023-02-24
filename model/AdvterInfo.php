<?php
require_once(dirname(dirname(__FILE__)) . "/db/class.db.php");
require_once(dirname(dirname(__FILE__)) . "/db/RedisModel.class.php");


class AdvterInfo
{
    protected $redis = [];

	public function __construct()
    {
        $this->redis = RedisModel::instance();
}

    public function getInfo($channel_ver = 0){
    	if(empty($channel_ver)) return false;
        $channel_ver_info = $this->redis->get($channel_ver);
        if(!$channel_ver_info){
            $config = require(dirname(dirname(__FILE__)) . "/config/db_config.php");
            $model  = new Database();
            $model->connect($config['adv_system']);
            $model->query("SELECT channel_ver,game_id,download_url from adv_system.tbl_advert_channel_ver_info where channel_ver = {$channel_ver}");
            $rs = $model->get();
            if(isset($rs[0]) && $rs[0]) {
                $channel_ver_info = [
                    'game_id' => $rs[0]['game_id'] ? (int)$rs[0]['game_id'] : 0,
                    'download_url' => isset($rs[0]['download_url']) ? $rs[0]['download_url'] : '',
                ];
                $channel_ver_info = json_encode($channel_ver_info);
                $this->redis->set($channel_ver,$channel_ver_info,3600);
            }
        }

		return $channel_ver_info ? json_decode($channel_ver_info,true) : [];
    }


}