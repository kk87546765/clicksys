<?php
return [
    'bdxxl'=>[
        'log_params'=>[
            'advter_id'=>'advterId',
            'timestamp'=>'ts',
            'user_agent'=>'ua',
            'imei' =>'imei_md5'
        ],
    ],
    'wechat'=>[	//微信
        'log_params'=>[
            'advter_id'=>'advterId',
            'imei'=>'muid',
            'timestamp'=>'click_time',
        ],
        'log_return'=>[
            'ret'=>'0',
            'msg'=>'成功'
        ],
        'creative_type' => 6
    ],
    'gdt'=>[
        'log_params'=>[
            'advter_id'=>'advterId',
            'imei'=>'muid',
            'timestamp'=>'click_time',
            'put_creative_id'=>'creative_id', //创意
            'put_plan_id'=>'campaign_id', //计划
            'put_campaign_id'=>'advertiser_id' //广告主
        ],
        'log_return'=>[
            'ret'=>'0',
            'msg'=>'成功'
        ],
        'creative_type' => 2
    ],
    'Custom'=>[  // 自定义渠道
        'log_return'=>[
            'code'=>'0',
            'msg'=>'success',
        ],
    ],
    'jrttpay'=>[  // 头条付费
        'log_params'=>[
            'put_creative_id'=>'cid', //创意
            'put_plan_id'=>'aid', //计划
            'put_campaign_id'=>'campaign_id' //广告主
        ],
        'creative_type' => 1
    ],
    'jrtt'=>[  // 头条方案二
        'log_params'=>[
            'put_creative_id'=>'cid', //创意
            'put_plan_id'=>'aid', //计划
            'put_campaign_id'=>'campaign_id' //广告主
        ],
        'creative_type' => 1,
    ],
    'kuaishou'=>[  // 快手
        'log_params'=>[
            'put_creative_id'=>'cid', //创意
            'put_plan_id'=>'did', //计划
            'put_campaign_id'=>'aid', //广告主
            'aid_name'=>'dname'
        ],
        'creative_type' => 5
    ],
    'baidu'=>[
        'log_params'=>[
            'timestamp'=>'ts',
            'user_agent'=>'ua',
            'imei' =>'imei_md5',
            'put_creative_id'=>'aid', //创意
            'put_plan_id'=>'pid', //计划
            'put_campaign_id'=>'uid', //广告主
        ],
        'creative_type' => 3
    ],

];
