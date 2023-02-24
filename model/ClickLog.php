<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/28
 * Time: 14:18
 */
require_once(dirname(dirname(__FILE__)) . "/db/CassandraDb.php");
require_once(dirname(dirname(__FILE__)) . "/model/CommonModel.php");

class ClickLog extends CommonModel
{
    const CLICK_TABLE = 'common_click_log';
    const EFFECT_DAY = 30;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 写入日志
     * @param $data
     * @return type
     */
    public function add($data)
    {
        $time = static::EFFECT_DAY * 86400;
        $ins_res = $this->cass->Insert(static::CLICK_TABLE, $data, $time);
        return $ins_res;
    }

    /**
     * 删除指定条件的数据
     * @param $map
     * @return type
     */
    public function delete($map)
    {
        return $this->cass->Delete(static::CLICK_TABLE, $map);
    }


    /**
     * 根据条件获取数据
     * @param $map
     * @param int $limit
     * @return array
     */
    public function getData($map, $limit = null)
    {
        return $this->cass->Select('*', static::CLICK_TABLE, $map, $limit);
    }




}