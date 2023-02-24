<?php
require_once(dirname(dirname(__FILE__)) . "/db/class.db.php");
require_once(dirname(dirname(__FILE__)) . "/db/RedisModel.class.php");


class kefuChatModel
{
    protected $redis = [];
    protected $key = 'chatList';
    protected $key_bak = '';
	public function __construct()
    {
        $this->key_bak = 'chatDis'.date('Y-m-d-H',time());
        $this->redis = RedisModel::instance();
}

    public function setInfo($data){
    	if(empty($data) || !is_array($data)) return false;

    	foreach($data as $k=>$v){
            $info = $this->redis->lpush($this->key,json_encode(['data'=>$v,'type'=>1])); //1是等级上报类型
        }


		return $info ? json_decode($info,true) : [];
    }

}