<?php



class csios
{

    public $from = 'zhangWan';
    public $secret = 'skHVv95EI7DG1sAzdNZZxlFDyG3IKCre';

    public $uid_to_sdkid_url =  "http://data-service.tanwan.com/customerService/user-info/search-uid";

    public $sdkid_to_uid_url = "http://data-service.tanwan.com/customerService/user-info/search-openid";

    public function __construct(){

    }

    #根据cp给的聚合uid换成平台自身的uid，用于信息推送时候替换成自己平台的uid
    public function sdkid_to_uid_url($sdkid){

        if(empty($sdkid)){
            return false;
        }

        $time = time();
        $sign = md5($this->secret.$time);
        $data['uid_list'] = json_encode([$sdkid]);
        $data['from'] = $this->from;
        $data['ts'] = $time;
        $data['sign'] = $sign;
        $url = $this->sdkid_to_uid_url.'?'.urldecode(http_build_query($data));

        $res = $this->fetchUrl($url);
        $res = json_decode($res,1);

        if($res['code'] == 200){
            return $res['data'][$sdkid];
        }else{
            return false;
        }

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