<?php
require_once(dirname(dirname(__FILE__)) . "/db/class.db.php");
require_once(dirname(dirname(__FILE__)) . "/db/RedisModel.class.php");


class LevelInfo
{
    protected $redis = [];
    protected $key = 'clickList';
    protected $key_bak = '';
	public function __construct()
    {
        $this->key_bak = 'clickDis'.date('Y-m-d-H',time());
        $this->redis = RedisModel::instance();
}

    public function setInfo($data){
    	if(empty($data) || !is_array($data)) return false;

    	$info = $this->redis->lpush($this->key,json_encode(['data'=>$data,'type'=>300])); //300是等级上报类型

		return $info ? json_decode($info,true) : [];
    }

}