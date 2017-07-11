<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


//  开发自定义静态常量
/**
 * 店鋪分佈圖 顯示的層數  0 ：显示全部层数
 */
define('SHOW_LEVEL',3);

/**
 * 团队销售利润提成奖 最高拿10代
 */
define('TEAM_SALES_OVERRIDES_ALGEBRA',3);

/**
 *  根据会员等级 得到团队销售提成奖多少代
 */
define('LEVEL_DIAMOND_GENERATION',3); //钻石拿10代
define('LEVEL_GOLD_GENERATION',3);//白金拿6代
define('LEVEL_SILVER_GENERATION',3);//银级拿4代
define('LEVEL_FREE_GENERATION',1);//免费拿1代
define('LEVEL_COPPER_GENERATION',2);//銅級拿2代

/**
 * 店铺等级的定义 1：钻石  2：白金 3：银级 4：免费 5:铜级
 */
define('LEVEL_DIAMOND',1);
define('LEVEL_GOLD',2);
define('LEVEL_SILVER',3);
define('LEVEL_FREE',4);
define('LEVEL_COPPER',5);

/**
 * 7个奖励制度
 */
define('REWARD_1',1); //REWARD_1=>2*5见点佣金；
define('REWARD_2',2); //REWARD_2=>138见点佣金；
define('REWARD_3',3); //REWARD_3=>团队销售佣金；
define('REWARD_4',4); //REWARD_4=>团队无限代；
define('REWARD_5',5); //REWARD_5=>个人店铺销售佣金；
define('REWARD_6',6); //REWARD_6=>周分红；
define('REWARD_7',7); //REWARD_7=>周领导对等奖

/**
 *  无限代奖励的条件
 */
define('PRODUCTS_CASH_RATIO',0.8); //店铺产品的销售利润的20%（产品如下:钻石 3600，白金 1800，银级 900）
define('INFINITY_RATIO',0.0025);    //无限代奖励：發放本團隊11代開始总销售利润的0.025%
define('INFINITY_ALGEBRA',11);    //无限代奖励：下线11代开始
define('TEAM_COUNT_LIMIT',2); //分支团队至少 2 组
define('TEAM_MEMBER_LIMIT',1500); // 分支团队最多计数 1500
define('TEAM_SILVER_LIMIT',3000); // 团队有3000个白银级别以上的店主


/* End of file constants.php */
/* Location: ./application/config/constants.php */

/*Forced Matrix*/
define('ROOT_ID',1381234567);
define('DIAMOND_CASH',2.10);
define('GOLD_CASH',1.40);
define('SILVER_CASH',0.70);
define('FREE_CASH',0.00);
define('COPPER_CASH',0.35);
define('MAX_X',138);



/*用户等级类型*/
define('LEVEL_TYPE_MONTHLY_FEE', 1);
define('LEVEL_TYPE_STORE', 2);

/*佣金报表分表时间*/
define('TIME_NODE_OLD',201606);

/* 主题名称 */
define('THEME_NAME','mall');
/* 主题名称 移动版 */
define('THEME_MOBILE','mobile');
/* 主题目录 */
define('THEME','themes/'.THEME_NAME);
define('MOBILE','themes/'.THEME_MOBILE);
