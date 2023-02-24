<?php

use Quan\Common\Libraries\Logger;

require_once(dirname(dirname(__FILE__)) . "/common/common.php");
require_once(dirname(dirname(__FILE__)) . "/model/kefuChat.php");


class kefuChat
{
    protected $common, $config,$data,$gamekey;

    protected $is_creative = false;

    const RETURN_FORM_ID = 1;

    const RETURN_FORM_ARRAY = 2;

    private $stop_queue = 0;

    protected $zw_allow_send = ['y9cqjh'];

    public function __construct()
    {
        $this->config = require_once(dirname(dirname(__FILE__)) . "/config/config.php");
        $this->gamekey = require_once(dirname(dirname(__FILE__)) . "/config/gamekey.php");
    }

    public function index($data)
    {
        try {
        $this->common = new Common();


        if(empty($data['data'])){
            die($this->common->Json(-103, 'data数据有误或者格式不对'));exit;
        }

        if(is_string($data['data'])){
            $data['data'] = json_decode(htmlspecialchars_decode($data['data']) ,1);
        }

        //处理只有单一数据不是数组的信息
        if(!isset($data['data'][0])){
            $tmp_data_arr0[0] = $data['data'];
            $data['data'] = $tmp_data_arr0;

        }


        $this->common->SafeFilter($data);

        $this->authorized($data);

        $data_params = $this->change_params($data,$data['data']);

        //推送聊天信息到掌玩聚合
        if(isset($data_params[0]['gkey']) && in_array($data_params[0]['gkey'],$this->zw_allow_send)){
            $this->pushZWMessage($data_params);
        }
        if($this->stop_queue){
            die($this->common->Json(1, 'ok'));
        }

        $kefu_chat_model = new kefuChatModel();
        $kefu_chat_model->setInfo($data_params);   //写入队列

        } catch (Exception $e) {
            error_log(date('Y-m-d H:i:s', time()) . $e->getMessage() . "\r\n", 3, dirname(dirname(__FILE__)) . '/log/' . date('Y-m-d', time()) . 'debug_level.log');
        }


        die($this->common->Json(1, 'ok'));



    }

    /**
     * @param $data     接收信息
     */
    protected function change_params($data,$content)
    {
        $chatList = [];
        if(empty($content)){
            die($this->common->Json(-4, '内容不能为空或者格式有误'));
        }


        foreach($content as $k=>$v){

            $new_data_list = [];
            $new_data_list['gkey'] = isset($data['gkey']) ? $data['gkey'] : '';
            $new_data_list['tkey'] = isset($data['tkey']) ? $data['tkey'] : '';

            //判断是否需要将渠道uid转换成自己平台的uid
            $open_id = $this->checkNeedChangeUid($data['gkey'],$v);

            if(is_array($open_id)){

                $v['openid'] = $v['uid'];
                $v['uid'] = $open_id['sdkid'];

                //判断是否需要停止入队列
                if(!empty($open_id['stop_queue'])){
                    $this->stop_queue = $open_id['stop_queue'];
                }

                $new_data_list['tkey'] = !empty($open_id['tkey']) ? $open_id['tkey'] : $new_data_list['tkey'];

                $v['repeat_ext'] = isset($open_id['ext']) ? $open_id['ext'] : [];

            }else{

                //将正确的平台uid放入对应字段，聚合的uid放入channel_uid字段中
                if($open_id){
                    $v['openid'] = $v['uid'];
                    $v['uid'] = $open_id;

                }
            }

            //特殊处理九州仙剑传
            if($data['gkey'] == 'jzxjz' && $data['tkey'] == 'asjd'){
                $new_data_list['tkey'] = 'mh';
            }

            $v = $this->changeInfo($data['gkey'],$v);

            $new_data_list['sid'] = isset($v['sid']) ? $v['sid'] :'';
            $new_data_list['sname'] = isset($v['sname']) ? $v['sname'] :'';
            $new_data_list['uid'] = isset($v['uid']) ? $v['uid'] : 0;
            $new_data_list['uid_str'] = isset($v['uid']) ? $v['uid'] : '';
            $new_data_list['uname'] = isset($v['uname']) ? $v['uname'] : '';
            $new_data_list['roleid'] = isset($v['roleid']) ? $v['roleid'] : 0;
            $new_data_list['type'] = isset($v['type']) ? $v['type'] : 0;
            $new_data_list['content'] = isset($v['content']) ? $v['content'] :'';
            $new_data_list['time'] = isset($v['time']) ? $v['time'] : 0; //聊天记录时间
            $new_data_list['ip'] = isset($v['ip']) ? $v['ip'] : '';
            $new_data_list['imei'] = isset($v['imei']) ? $v['imei'] : '';
            $new_data_list['role_level'] = isset($v['role_level']) ? $v['role_level'] : 0;
            $new_data_list['to_uid'] = isset($v['to_uid']) ? $v['to_uid'] :0;
            $new_data_list['to_role_id'] = isset($v['to_role_id']) ? $v['to_role_id'] :0;
            $new_data_list['to_uname'] = isset($v['to_uname']) ? $v['to_uname'] :'';
            $new_data_list['ext'] = isset($data['ext']) ? $data['ext'] :'';
            $new_data_list['openid'] = isset($v['openid']) ? $v['openid'] :'';

            $new_data_list['repeat_ext'] = isset($v['repeat_ext']) ? $v['repeat_ext'] :'';

            array_push($chatList,$new_data_list);
        }


        return $chatList;
    }


    protected function authorized($data)
    {

////        Logger::init();
        $gkey = $data['gkey'];
        $tkey = $data['tkey'];
        $sign1 = $data['sign'];
        $time = $data['request_time'];

        if(empty($gkey)){
            die($this->common->Json(-1, 'gkey不能为空'));
//            echo '';exit;
        }
        if(empty($tkey)){
            die($this->common->Json(-2, 'tkey不能为空'));
//            echo 'tkey不能为空';exit;
        }
        if(empty($time)){
            die($this->common->Json(-3, 'request_time不能为空'));
//            echo 'request_time不能为空';exit;
        }

        if($gkey == 'qingyun'){
            die($this->common->Json(1, 'ok'));
//            echo 'ok';exit;
        }



        $string = $data['gkey'].$data['tkey'].$data['request_time'];


        if(isset($this->gamekey[$gkey]['key'])){
            $key = $this->gamekey[$gkey]['key'];
        }else{
            die($this->common->Json(-5, '游戏参数尚未配置'));exit;
        }


        $sign2 = md5( $string. $key);

        if ($sign1 != $sign2) {
//            die($this->common->Json(-4, '验签失败'));exit;
//            $this->response->json(-101, '验签失败');
//            $this->response->send();exit;
        }

        if ($time < time() - 3600) {
//            die($this->common->Json(-5, '验签超时'));exit;
//            $this->response->json(-102, '验签超时');
//            $this->response->send();exit;
        }
    }

    private function replace_specialChar($strParam){
        $regex = "/\/|\～|\，|\。|\！|\？|\“|\”|\【|\】|\『|\』|\：|\；|\《|\》|\’|\‘|\ |\·|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        return preg_replace($regex,"",$strParam);
    }


    public function checkNeedChangeUid($gkey,$data){


        $config_game = $this->gamekey[$gkey];
        if(isset($gkey) && $config_game['need_change_uid'] == 1){
            include_once(dirname(dirname(__FILE__)) . "/model/gamekeySign/".$gkey.".php");

            //聚合uid换平台uid
            if(class_exists($gkey)){
                $game_model = new $gkey();

                //判断返回格式
                if(isset($game_model->return_form) && $game_model->return_form == self::RETURN_FORM_ARRAY){
                    $open_id_info = $game_model->sdkid_to_uid_url($data['uid']);

                    return $open_id_info;
                }else{

                    $open_id = $game_model->sdkid_to_uid_url($data['uid']);
                    return $open_id;
                }

            }

        }
        return false;
    }

    public function changeInfo($gkey,$data)
    {

        $config_game = isset($this->gamekey[$gkey]) ? $this->gamekey[$gkey] : '';

        if(isset($gkey) && $config_game['need_change_uid'] == 1){
            include_once(dirname(dirname(__FILE__)) . "/model/gamekeySign/".$gkey.".php");

            //聚合uid换平台uid
            if(class_exists($gkey)){
                $game_model = new $gkey();

                //判断返回格式
                if(method_exists($game_model,'changeInfo')){
                    $data = $game_model->changeInfo($data);
                }

            }

        }
        return $data;
    }

    public function getChannelUid($game_change_config,$chat_data)
    {
        $chat_data['time'] = time();
        $chat_data['key'] = $game_change_config['key'];
        $url = $game_change_config['need_change_uid']['sdkid_to_uid_url'];
//        $sign = eval($gkey_config['sign_rule']);
        $data = '';
        $new_data = [];
        $a = preg_match_all('/\|[a-z]*\|/', $game_change_config['need_change_uid']['sign_rule'], $data);

        foreach ($data[0] as $k => &$v) {

            $v = @trim($v, '|');

            $new_data[$v] = $chat_data[$v];
        }

        $rule = $game_change_config['need_change_uid']['sign_rule'];
        foreach($new_data as $k=>$v){

            $rule = str_replace('|'.$k.'|',$v,$rule);
        }


        $sign = eval($rule);
        $this->curl_init_post($url,$params);


    }


    function curl_init_post($url, $params,$timeout = 180, $header = array())
    {
        $ch = curl_init();
        // 设置 curl 相应属性
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        if($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //定义请求类型
        }
        $returnTransfer = curl_exec($ch);
        curl_close($ch);
        return $returnTransfer;
    }

    public function pushZWMessage($data){

        $time = time();
        $key = 'wi4ZQ50u3rH6xgXTB0eG';

        $sign = md5($data[0]['gkey'].$data[0]['tkey'].$time.$key);
        $tmp_data = [];
        foreach($data as $k=>$v){
            $tmp_data[$k]['sid'] = $v['sid'] ?? '';

            $tmp_data[$k]['sname'] = $v['sname'] ?? '';
            $tmp_data[$k]['uid'] = $v['uid'] ?? 0;
            $tmp_data[$k]['role_id'] = $v['roleid'] ?? 0;
            $tmp_data[$k]['role_name'] = $v['uname'] ?? '';
            $tmp_data[$k]['to_uid'] = $v['to_uid'] ?? 0;
            $tmp_data[$k]['to_role_id'] = $v['to_role_id'] ?? 0;
            $tmp_data[$k]['to_role_name'] = $v['to_uname'] ?? '';
            $tmp_data[$k]['type'] = $v['type'] ?? 0;
            $tmp_data[$k]['content'] = $v['content'] ?? '';
            $tmp_data[$k]['time'] = $v['time'] ?? 0;
            $tmp_data[$k]['ip'] = $v['ip'] ?? '';
            $tmp_data[$k]['imei'] = $v['imei'] ?? '';
            $tmp_data[$k]['repeat_ext'] = $v['repeat_ext'] ?? [];

        }

        $post_data = [
            'gkey' => $data[0]['gkey'],
            'tkey' => $data[0]['tkey'],

            'time' => $time,
            'ext' => $data[0]['ext'],
            'sign' => $sign,
            'data' => json_encode($tmp_data)
        ];


        $url = "http://us.hnzwwl.cn/api/push_message.php";
        $res = $this->curl_init_post($url,$post_data);
    }
}