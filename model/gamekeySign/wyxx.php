<?php



class wyxx
{

    public $return_form = 2; //判断是否需要返回转接口的所有数据

    private $secret = 'c930ea7686f0fdc5c849a59167608fa2';

    public $uid_to_sdkid_url = "https://apisdk.gaore.com/channel/get_zhangwan_uid.php";

    public $sdkid_to_uid_url = "https://apisdk.gaore.com/channel/get_zhangwan_uid.php";

    private $cooperationID = [10=>'bx'];

	public function __construct(){

    }

    #根据cp给的聚合uid换成平台自身的uid，用于信息推送时候替换成自己平台的uid,type1代表掌玩聚合id换平台id，2代表平台id换掌玩聚合id
    public function sdkid_to_uid_url($sdkid){

	    if(empty($sdkid)){
	        return false;
        }

        $time = time();
        $key = $this->secret;
        $sign = md5($sdkid.'154'.'2'.$time.$key);
        $post_data = [
            'user_id'  => $sdkid,
            'mtype'  => 154,
            'type' => 2,
            'time' => $time,
            'sign' => $sign

        ];

	    $res = self::fetchUrl($this->sdkid_to_uid_url.'?'.http_build_query($post_data));
        $res = json_decode($res,1);


        if(isset($res['code']) && $res['code'] == 1){

            if($res['data']['user_id'] == $sdkid){
                $id = $res['data']['openid'];
            }else{
                $id = '';
            }


            if($id){
                $id = self::youyuChangebx($id);
            }

            return $id;

        }else{
            return false;
        }

    }



    #根据平台自身的uid换cp给的聚合uid，用于调用cp封禁、禁言接口
    public function uid_to_sdkid_url(){

    }


    public function youyuChangebx($uid)
    {
        $secret = '3zNHnfaSawJRoxSdmiPQ2VdxjfUH5kW2';
        $time = time();


        $sign =md5($uid.$time.$secret);

        $post_data = [
            'uid'  => $uid,
            'type' => 1,
            'time' => $time,
            'sign' => $sign

        ];
        $url = 'http://api.e7j.cn/activity/bx/index';
        $res = self::fetchUrl($url.'?'.http_build_query($post_data));
        $res = json_decode($res,1);

        $return = [];

        if(isset($res['state']['code']) && $res['state']['code'] == 1){

            $return['tkey'] = isset($res['data'][0]['cooperationID']) && key_exists($res['data'][0]['cooperationID'],$this->cooperationID) ? $this->cooperationID[$res['data'][0]['cooperationID']] : '';
            $return['sdkid'] = isset($res['data'][0]['sdkUID']) ? $res['data'][0]['sdkUID'] : $uid;
        }

        return $return;

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