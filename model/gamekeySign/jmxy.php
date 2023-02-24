<?php



class jmxy
{

    public $secret = '';

    public $uid_to_sdkid_url = "http://advapi.e7j.cn/Vip_Customer/get_uid";
    public $sdkid_to_uid_url = "http://advapi.e7j.cn/Vip_Customer/get_uid";

    private $cooperationID = ['zhangwan'=>'zw','bingxue'=>'bx','youyu','youyu'];
	public function __construct(){

    }

    #根据cp给的聚合uid换成平台自身的uid，用于信息推送时候替换成自己平台的uid,type1代表掌玩聚合id换平台id，2代表平台id换掌玩聚合id
    public function sdkid_to_uid_url($sdkid){

        if(empty($sdkid)){
            return false;
        }

        $time = time();

        $key = 'KWpeSMaHTtveMzkwsV2oJKmO4A7Yhf0pKR3NSnIHifNpl06Iqub5iMEQFr0MpBqC';
        $sign = md5($sdkid.$time.$key);
        $post_data = [
            'uid'  => $sdkid,
            'time' => $time,
            'sign' => $sign,
            'type' => 1
        ];

        $res = self::fetchUrl($this->sdkid_to_uid_url.'?'.http_build_query($post_data));

        $res = json_decode($res,1);

        if(isset($res['state']['code']) && $res['state']['code'] == 1){
            $tmp_info = $res['data'];
            $return_info['uid'] = $sdkid;
            $return_info['sdkid'] = $tmp_info['sdkid'];
            $return_info['channel'] = $tmp_info['channel'];

            $return_info['tkey'] = isset($tmp_info['channel']) && key_exists($tmp_info['channel'],$this->cooperationID) ? $this->cooperationID[$tmp_info['channel']] : '';

            return $return_info;

        }else{


            return false;
        }

    }



    public function changeInfo($data)
    {

        switch ($data['type']){
            case 0:
                $my_type = 4;
                break;
            case 2:
                $my_type = 8;
                break;
            case 3:
                $my_type = 7;
                break;
            case 4:
                $my_type = 6;
                break;
            case 5:
                $my_type = 9;
                break;
            case 6:
                $my_type = 5;
                break;
            case 7:
                $my_type = 9;
                break;
            case 8:
                $my_type = 2;
                break;
            case 12:
                $my_type = 1;
                break;
            default:
                $my_type = $data['type'];
        }
        $data['type'] = $my_type;
        return $data;
    }


    #根据平台自身的uid换cp给的聚合uid，用于调用cp封禁、禁言接口
    public function uid_to_sdkid_url(){

    }



    function curl_init_post($url, $params, $timeout = 5) {
        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $curl_opt = array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_URL => $url,
            CURLOPT_AUTOREFERER => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER=> 0, // 跳过证书检查
            CURLOPT_SSL_VERIFYHOST=> 0  // 从证书中检查SSL加密算法是否存在
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curl_opt);
        $contents = curl_exec($ch);
        curl_close($ch);

        return $contents;
    }


    public function fetchUrl($url, $time=3, $http_code=false)
    {
        $curl_opt = array(
            CURLOPT_URL => $url,
            CURLOPT_AUTOREFERER => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $time,
        );

        if(strpos($url, 'https') === 0) {
            $curl_opt[CURLOPT_SSL_VERIFYPEER] = false;
            // $curl_opt[CURLOPT_SSL_VERIFYHOST] = 2;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curl_opt);
        $contents = curl_exec($ch);
        if ($http_code) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            unset($contents);
            return $http_code;
        }
        curl_close($ch);
        return $contents;
    }



}