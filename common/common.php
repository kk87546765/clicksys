<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 */
class Common{
    function get_client_ip($type = 0) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        //优先代理IP
        if (isset ($_SERVER['HTTP_X_REAL_IP'])){
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }


    //匹配ID地址
    function IpToLocation($userip){
        require_once(dirname(dirname(__FILE__)) . "/helper/IP.class.php");
        $regions = array (
            '北京' => '北京',
            '天津' => '天津',
            '河北' => '石家庄',
            '山西' => '太原',
            '内蒙古'=>'呼和浩特',
            '辽宁'=>'沈阳',
            '吉林'=>'长春',
            '黑龙江'=>'哈尔滨',
            '上海' => '上海',
            '江苏'=>'南京',
            '浙江'=>'杭州',
            '安徽'=>'合肥',
            '福建'=>'福州',
            '江西'=>'南昌',
            '山东'=>'济南',
            '河南'=> '郑州',
            '湖北' => '武汉',
            '湖南' => '长沙',
            '广东' => '广州',
            '广西' => '南宁',
            '海南' => '海口',
            '重庆' => '重庆',
            '四川' => '成都',
            '贵州' => '贵阳',
            '云南' => '昆明',
            '西藏' => '拉萨',
            '陕西' => '西安',
            '甘肃' => '兰州',
            '青海' => '西宁',
            '宁夏' => '银川',
            '新疆' => '乌鲁木齐',
            '香港' => '香港',
            '澳门' => '澳门',
            '台湾' => '台湾',
        );

        $ips = IP::find($userip);
        return array('country'=>(!empty($ips[2]) && $ips[2] != $ips[1] ? $ips[2] : (isset($regions[$ips[1]]) ? $regions[$ips[1]] : $ips[1])), 'area'=>$ips[1]);
    }

    function setlog($msg, $dir = '', $file = '') {
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

    function Json($code = 0 , $message = '', $data = array()){
        if(!is_numeric($code)){
            return '';
        }

        $statusList = array(
            200 => "Success",
            201 => "error, Lost must params!",
            202 => "error, sign error!",
            203 => "error parameter!",
            301 => "301 Moved Permanently",
            302 => "302 Moved Temporarily ",
            304 => "304 Not Modified",
            403 => "403 Forbidden",
            404 => "404 Not Found",
            406 => "Not Acceptable",
            500 => "500 Internal Server Error"
        );

        if (!$message){
            $message = isset($statusList[$code]) ? $statusList[$code] : 'Unknown error' ;
        }

        $result = array(
            'code' => $code,
            'msg' => $message,
            'data' => $data
        );
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
        return $result;
    }

    /**
     * php防注入和XSS攻击通用过滤.
     * @param $arr
     */
    function safeFilter (&$arr)
    {

        $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');

        if (is_array($arr))
        {
            foreach ($arr as $key => $value)
            {
                if (!is_array($value)) {
                    $value  = addslashes($value);
                    $value       = preg_replace($ra,'',$value);     //删除非打印字符，粗暴式过滤xss可疑字符串
                    $arr[$key]     = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为 HTML 实体

                } else {
                    self::SafeFilter($arr[$key]);
                }
            }
        }
        return $arr;
    }

    function checkParam($key) {
        if (!$key) return '';
        $is_md5 = preg_match("/^[a-z0-9A-Z]{32}$/", $key);

        if ($is_md5){
            $res = strtoupper(trim($key));
        } else {
            $res = strtoupper(md5(trim($key)));
        }
        return $res;
    }

    function checkIp($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }



    public function HeaderLocation($apk_url = '', $code = 200)
    {
        $statusList = array(
            200 => "200 OK",
            301 => "301 Moved Permanently",
            302 => "302 Moved Temporarily ",
            304 => "304 Not Modified",
            403 => "403 Forbidden",
            404 => "404 Not Found",
            406 => "Not Acceptable",
            500 => "500 Internal Server Error"
        );
        $protocol = 'HTTP/1.1 ';
        (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] !== "HTTP/1.1") && $protocol = 'HTTP/1.0 ';

        header($protocol . $code . ' ' . $statusList[$code]);
        header('Status:' . $code . ' ' . $statusList[$code]);
        if ($apk_url) {
            echo("<script language='javascript'>window.location.href='{$apk_url}';</script>");
        }
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

    //生产唯一ID
    public function getUniqid()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $Uniqid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
        return $Uniqid;
    }

}
