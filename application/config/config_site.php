<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/* Set 环境类型：production,development*/
@define('ENVIRONMENT', 'development');

/* Set timezone. */
date_default_timezone_set('PRC');

/* Database config. */
$config['db_site'] = array(
		'host' => "192.168.0.153:3309",
		'userName' => "tps138test",
		'pwd' => "fh_SpGv0OpRG",
		'dbName' => "tps138",
);
$config['db_slave'] = array(
		'host' => "192.168.0.153:3309",
		'userName' => "tps138test",
		'pwd' => "fh_SpGv0OpRG",
		'dbName' => "tps138",
);
//
//$config['db_site'] = array(
//		'host' => "192.168.0.158:3308",
//		'userName' => "tps138dev",
//		'pwd' => "4svpAY2FSd_O",
//		'dbName' => "tps138",
//);
///* Database slave config. */
//$config['db_slave'] = array(
//		'host' => "192.168.0.158:3308",
//		'userName' => "tps138dev",
//		'pwd' => "4svpAY2FSd_O",
//		'dbName' => "tps138",
//);

$config['redis'] = array(
		'stop'=>0,
		'host'=>"192.168.0.154",
		'port'=>"6379",
		'auth'=>"8Zvrex00yK13",
		'config_redis_server_set'=>[
//    [
//        'host'=>'',
//        'port'=>'',
//        'auth'=>'',
//    ]
		],
		'config_redis_server_add'=>[

		],
		'config_redis_server_delete'=>[

		],
);

/*图片服务器url*/
$config['img_server_url'] = 'http://img.tps138.dev';

/*Member root id.*/
$config['mem_root_id'] = '1380100217';

/*Admin root account*/
$config['admin_root_account'] = array(
    'account'=>'terry.lu@tps138.com',
    'pwd'=>'terry111'
);

/*Website close, website stop register*/
$config['website_closed'] = FALSE;
$config['website_stop_join'] = false;

/*Payment*/
$config['sandbox'] = TRUE;
$config['payment_test'] = TRUE;

/*沃好商城配置*/
$config['wohao_host'] = 'walhao.com';
$config['wohao_api_status'] = 1;
$config['wohao_api_host'] = '115.29.221.224';

/*开启8-8号新规则*/
$config['check_card_switch'] = TRUE;
$config['enter_138_new_rule']=TRUE;

/*ip地理位置接口：1=>淘宝ip接口；2=>freegeoip接口*/
$config['ip_geo_api'] = 1;

/*是否加载分享js*/
$config['load_share_js'] = TRUE;

/** 服务器国家位置 :中国 CN 国外：US 香港：HK */
$config['position_country_code'] = 'CN';

/** 升級開關 1：關閉升級  0：不關閉升級　*/
$config['upgrade_switch'] = '0';

/*月费降价一半*/
$config['month_half_price'] = false;

/** 团队销售提成订单要求 TRUE:有訂單要求 FALSE:沒有訂單要求 */
$config['team_sales_order_rule']=false;

/*销售精英日分红测试开关*/
$config['dailyBonusEliteTest'] = false;

/*Mobile相关配置*/
$config['mobile'] = array(
	'api_key'=>'34rt3Er34',
	'api_key2'=>'er34gJT34r',
);

/*分红奖测试模式*/
$config['leader_bonus_test'] = false;

// ERP API IP and hostname
// 内网环境 api_erp_iphost 为服务器 IP, api_erp_lanhost 为虚拟域名
// 外网环境 api_erp_iphost 为域名，api_erp_lanhost 为空
$config['api_erp_iphost'] = 'http://113.106.63.242/';
$config['api_erp_lanhost'] = 'www.wms.test';

/** OSS 配置信息  0 为默认值 测试环境**/
$config['oss_api_cfg'] = array(
		0 =>array(               //默认配置
				'access_key_id'     => 'LTAIAKudOPnuOvB3',
				'access_key_secret' => 'TJeTwgHHjfq71PQz3jgkL65Kny8kMR',
				'endpoint'          => 'oss-cn-shenzhen.aliyuncs.com',//OSS数据中心访问域名  oss-cn-shenzhen.aliyuncs.com
				'bucket'            => 'tps-test',//存储空间名字
				'acl'               => 'public-read',//设置存储空间读写权限
				'is_c_ame'          => false,//是否对Bucket做了域名绑定，并且Endpoint参数填写的是自己的域名
		),
);
