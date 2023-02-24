<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 16:59
 */
class CommonModel{

    protected $cass;
    public function __construct()
    {
        //连接失败的时候最多尝试重连5次
        $flag = false;
        for ($i = 1; $i <= 3; $i++)
        {
            try{
                $this->cass = CassandraDb::instance();
                $this->cass->ConnectCluster();
                $flag = true;
                break;
            }catch(\Exception $e){
                $this->log('【'.date('Y-m-d H:i:s').'】'.$e->getMessage().',connect_times=' . $i . "\r\n", 'cass/login/');
            }
        }
    }

    public function log($msg, $dir = '', $file = '') {
        $maxsize = 2 * 1024 * 1024;
        $base_dir = dirname(dirname(__FILE__)).'/log/';
        !empty($dir) && $base_dir .= $dir;

        if(!is_dir($base_dir)) {
            mkdir($base_dir, 0777, true);
        }

        empty($file) && $file = date('Ymd').'.log';

        $path = $base_dir.$file;
        //检测文件大小，默认超过2M则备份文件重新生成 2*1024*1024
        if(is_file($path) && $maxsize <= filesize($path) )
            rename($path,dirname($path).'/'.time().'-'.basename($path));

        error_log($msg, 3, $path);
    }




}