<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7
 * Time: 16:23
 */

class writeFile
{

    const ROUTE_CHAT  = 'chat/';   //升级的存储地址
//    const ROOT_ROUTE = '/home/wwwroot/clicksys/log/chat/'; //根目录
    const ROOT_ROUTE = '/home/wwwroot/clicksys/log/chat/'; //根目录

    /**
     * 写入
     * @param $data
     * @param $type
     */
    public function write($data, $type)
    {
        $type_route = $this->returnTypeRoute($type);
        $date_dir = date('Y-m-d',time()).'/';

        $dir = static::ROOT_ROUTE.$type_route.$date_dir;

        !file_exists($dir) && mkdir($dir, 0777, true);

        $filename = $type.'.log';
        $data = json_encode($data);

        $this->save($data, $dir,$filename);


    }

    /**
     * 写入文件
     * @param $message
     * @param $destination
     */
    public function save($message, $destination, $filename)
    {
        $destination = $destination.$filename;
        $file_size = 2*1024*1024;
        if(is_file($destination) && $file_size <= filesize($destination) ){
            rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }

        error_log("{$message}\r\n", 3,$destination);

    }

    /**
     * 返回正确存放目录
     * @param $type
     * @return string
     */
    public function returnTypeRoute($type)
    {
        $route = '';
        switch ($type)
        {
            case static::ROUTE_CHAT:
                //处理聊天信息
                $route = static::ROUTE_CHAT;
                break;
        }

        return $route;

    }

}