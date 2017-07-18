<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$config['subdomain_reserved'] = array('cs', 'kr', 'mall','pay');
$config['tps138mallgoods'] = 'mall';
$config['mall_redis_time'] = 300;

/* 会员规则切换时间 */
$config['rule_switch_time'] = '2026-07-01';

$config['cash_account_log_cut_table_end'] = 201706;//拆表开始日期
$config['cash_account_log_cut_table_end_1'] = 201707;//月费开始日期
$time = time();

if($time >= strtotime("2017-07-01 00:00:00")) {
    $config['cash_account_log_read_new'] = true; //是否读新表
} else {
    $config['cash_account_log_read_new'] = false; //是否读新表
}

$config['cash_account_log_write_both'] = true; //是否新表旧表一起插入
//$config['cash_account_log_temp_write'] = false; //是否写临时表
/* 拥有奖金特别处理功能的管理员id */
//$config['comm_special_admin_ids'] = array(1, 8, 9, 61, 62, 71, 116, 121, 192, 198, 210, 212, 173, 188, 210, 212,103,224,107,120,129,389,160,99,270,251,331,128,78,268,256);

/* 佣金类型（改为用户现金池变动纪录） */
$config['commission_type'] = array(
    5 => 'personal_sale', //5=>个人店铺销售提成奖
    3 => 'group_sale', //3=>团队销售业绩提成奖
    6 => 'week_profit_sharing', //6=>每天全球利润分红
    24 => 'daily_top_performers_sales_pool', //24=>销售精英日分红
    1 => '2x5_force_matrix', //1=>每月团队组织分红奖
    8 => 'month_leader_profit_sharing', //8=>每月杰出店铺分红
    2 => '138_force_matrix', //2=>138见点佣金
    25 => 'plan_week_share', //每周团队分红
    7 => 'week_leader_matching', //7=>每周领导对等奖
    23 => 'month_middel_leader_profit_sharing', //23=>每月领导分红奖
    // 19 => 'month_top_leader_profit_sharing',//19=>每月领袖分红奖
    4 => 'group_sale_infinity', //4=>团队无限代    更改为  每月总裁销售奖
    16 => 'demote_level',   //16=>佣金抽回(退款),
    26 => 'new_member_bonus', //新用户专享奖金
    27 => 'supplier_recommendation', //供应商推荐奖
);


/*
 * 分红参数循环,提示信息
*/
$config['brouns_param_numbers'] = array(
    5 => [], //5=>个人店铺销售提成奖
    3 => [], //3=>团队销售业绩提成奖
    6 => ['yesterday_profit_percentage','join_bonus','user_bonus'], //6=>每天全球利润分红
    24 => ['total_user_sales_share'], //24=>销售精英日分红
    1 => ['last_month_profit_percentage','last_month_bonus'], //1=>每月团队组织分红奖
    8 => ['global_gross_profit','join_bonus','user_bonus'], //8=>每月杰出店铺分红
    2 => ['global_gross_profit','matrix_bonus'], //2=>138见点佣金
    25 => ['last_week_profit_percentage','sales_weight_percentage','title_weight_percentage','bonus_percentege','store_level_weight_percentage'], //每周团队分红
    7 => ['last_week_percentage'], //7=>每周领导对等奖
    23 => ['last_month_profit_percentage','join_bonus'], //23=>每月领导分红奖
    4 => [], //4=>团队无限代    更改为  每月总裁销售奖
    16 => [],   //16=>佣金抽回(退款)
    26 =>['yesterday_sales_share'], //新用户专属奖金
    27 => 'supplier_recommendation', //供应商推荐奖
);

/*短信验证码有效时间*/
$config['verify_code_expire_time'] = [
    'mobile'=>5*60,
    'email'=>30*60,
];
/*验证码位数*/
$config['verify_code_counts'] = [
    'mobile'=>6,
    'email'=>6,
];

//用户职称对应需要达到的积分点
$config['users_credit_config'] = [
    '0'=>['title'=>"普通会员","credit"=>0],
    '1'=>['title'=>"资深","credit"=>150],
    '2'=>['title'=>"主管","credit"=>900],
    '3'=>['title'=>"高级市场主管","credit"=>2700],
    '4'=>['title'=>"市场总监","credit"=>8100],
    '5'=>['title'=>"销售副总裁","credit"=>24300],
];
$config['credit_switch'] = "off"; //是否启用积分影响职称
$config["user_credit_remark_message"]=[
    'init_user_first'=>"首次初始化用户积分",
    'init_user'=>'单个初始化用户积分',
    'init_user_batch'=>'批量初始化用户积分'
];


//一级团队会员升级获得积分
//1钻石，2白金级，3银级，4免费会员，5铜级会员',
$config['user_rank_name'] = [
    '4'=>"免费店铺",
    '5'=>"铜级店铺",
    '3'=>"银级店铺",
    '2'=>"白金店铺",
    '1'=>"钻石店铺",
];

$config['sale_rank_name'] = [
    '0'=>"普通会员",
    '1'=>"资深店主",
    '2'=>"市场主管",
    '3'=>"高级市场主管",
    '4'=>"市场总监",
    '5'=>"销售副总裁",
];




/* 涉及补单的佣金类型 */
$config['commission_type_for_order_repair'] = array(
    6 => 'week_profit_sharing', //6=>每天全球利润分红
    2 => '138_force_matrix', //138
    24 => 'daily_bonus_elite', //24=>销售精英日分红
    8 => 'month_leader_profit_sharing', //8=>每月杰出店铺分红
    7 => 'week_leader_matching', //7=>周领导对等奖
    23 => 'month_middel_leader_profit_sharing', //23=>月领导分红奖
    4 => 'group_sale_infinity', //4=>团队无限代
    1 => '2x5_force_matrix', //
    25 => 'plan_week_share', //
    26 => 'new_member_bonus', //新用户专属奖金  20170406新加
    27 => 'supplier_recommendation', //供应商推荐奖 20170414
);

/* 资金变动类型（改为用户现金池变动纪录） */
$config['funds_change_report'] = array(
    5 => 'personal_sale', //5=>个人店铺销售提成奖
    3 => 'group_sale', //3=>团队销售佣金
    6 => 'week_profit_sharing', //6=>每天全球利润分红
    24 => 'daily_bonus_elite', //24=>销售精英日分红
    1 => '2x5_force_matrix', //1=>2*5见点佣金
    8 => 'month_leader_profit_sharing', //8=>每月杰出店铺分红
    2 => '138_force_matrix', //2=>138见点佣金
    25 => 'plan_week_share', //每周团队分红
    7 => 'week_leader_matching', //7=>周领导对等奖
    23 => 'month_middel_leader_profit_sharing', //23=>月领导分红奖
    // 19 => 'month_top_leader_profit_sharing',//19=>每月领袖分红奖
    4 => 'group_sale_infinity', //4=>团队无限代
    9 => 'special_funds', //9=>特殊款项处理
    10 => 'withdrawal', //10=>提現
    12 => 'cancel_withdrawal', //12=>取消提現
    11 => 'MEMBER_TRANSFER_MONEY', //11=>会员间现金池转帐
    13 => 'up_tps_level', //13=>升级月费等级，店铺等级。
    14 => 'cash_pool_to_month_fee_pool', //14=>現金池轉月費池。
    15 => 'month_fee_to_amount', //15=>月费转现金池
    16 => 'demote_level', //16=>佣金抽回(退款)
    17 => 'transfer_point', //17=>佣金轉分紅點
    18 => 'transfer_cash', //18=>分紅點轉佣金
    20 => 'mall_expenditure', //20=>商城購買訂單消費
    21 => 'return_back', //21=>佣金抽回返补
    22 => 'order_refund', //22=>订单退款
    26 => 'new_member_bonus', //新用户专属奖金
    27 => 'supplier_recommendation', //供应商推荐奖
    28 => 'month_expense', //收取会员月费
);
/** 月費變動類型 by john 2015-7-6 */
$config['monthly_fee_report'] = array(
    1 => 'month_type_1', //1=>充值月費
    2 => 'cash_pool_to_month_fee_pool', //2=>現金池轉月費
    3 => 'coupon', //3=>月費券
    4 => 'month_type_4', //4=>交月費
    5 => 'month_fee_to_amount', //5=>月費轉現金池
    6 => 'demote_level', //6=>佣金抽回(退款)
    7 => 'special_funds', //7=>特殊款项处理
    8 => 'action_charge_month', //8=>活动抵扣月费
);

$config['store_url_modify_counts_limit'] = 3;
$config['member_url_modify_counts_limit'] = 3;

/* 国家和地区 */
$config['countrys_and_areas'] = array(
    1 => 'con_china',
    2 => 'con_usa',
    3 => 'con_korea',
    4 => 'con_hongkong',
    5 => 'con_singapore',
    6 => 'con_philippines',
    7 => 'con_malaysia',
    8 => 'con_taiwan',
    9 => 'con_mexico',
    10 => 'con_canada',
    11 => 'con_vietnam',
    12 => 'con_russia',
    13 => 'con_kazahstan',
    14 => 'con_japan',
    0 => 'con_others',
);

/* 提现方式 */
$config['take_out_type'] = array(
//    1 => 'bank_card',
   // 2 => 'type_alipay',
    3 => 'type_tps',
    4 => 'maxie_mobile',
    5 => 'paypal',
    6 =>'debit_card'//银行卡提现 add by brady.wang 2017/07/05
);

/* 會員等級相关配置 */
$config['user_ranks'] = array(
    1 => 'member_diamond',
    2 => 'member_platinum',
    3 => 'member_silver',
    5 => 'member_bronze',
    4 => 'member_free',
);

/** 會員月費等級 */
$config['monthly_fee_ranks'] = array(
    1 => 'diamond',
    2 => 'gold',
    3 => 'silver',
    4 => 'free',
    5 => 'bronze'
);

$config['levels'] = array(
    1 => 'level_diamond',
    2 => 'level_platinum',
    3 => 'level_silver',
    5 => 'bronze',
    4 => 'level_free',
);
$config['level_type'] = array(
    LEVEL_TYPE_MONTHLY_FEE => 'monthly_fee_level',
    LEVEL_TYPE_STORE => 'member_level',
);

/* 同步用户到沃好的字段配置 */
$config['sync_to_wohao_items'] = array(
    1 => array('email', 'email'),
    2 => array('pwd', 'pwd'),
    3 => array('pwd_token', 'token'),
    4 => array('parent_id', 'parent_id'),
    5 => array('languageid', 'languageid'),
    6 => array('name', 'name'),
    7 => array('mobile', 'mobile'),
    8 => array('country_id', 'country_id'),
    9 => array('address', 'address'),
    10 => array('store_prefix', 'store_url'),
    11 => array('store_level', 'user_rank'),
    12 => array('create_time', 'create_time'),
    13 => array('status', 'status'),
);

//For php mailer.
$config['mail_name'] = 'TPS';
$config['mail_passwd'] = '34Re3r3er3t3t34E2';
$config['mail_address'] = 'info@shoptps.com';
$config['mail_smtp'] = 'smtp.mxhichina.com';
$config['mail_smtp_port'] = 465;

$config['mail_senders'] = array(
    'info@shoptps.com',
    'info2@shoptps.com',
    'info3@shoptps.com',
    'info4@shoptps.com',
    'info5@shoptps.com',
    'info6@shoptps.com',
    'info7@shoptps.com',
    'info8@shoptps.com',
    'info9@shoptps.com',
    'info10@shoptps.com',
);

/**用户冻结解冻权限*/
$config['users_option_frozen_power'] = array
(
//    'judy.huang@shoptps.com',
//    'fanny.hou@shoptps.com',
//    'nora.huang@shoptps.com',
//    'aimee.jiang@shoptps.com',
//    'jane.jin@shoptps.com',
//    'jenny.zhang@shoptps.com',
//    'sabrina.li@shoptps.com',
//    'jeff.pan@shoptps.com',
//    'sophie.shi@shoptps.com',
//    'lina.wong@shoptps.com',
//    'terry.lu@shoptps.com',
//    'sandy.chen@shoptps.com',
//    'shenqing.meng@shoptps.com',
//    'danny.lin@shoptps.com',
//    'candy.xu@shoptps.com',
//    'surchow.zhou@shoptps.com',
//    'ada.li@shoptps.com',
//    'tina.zeng@shoptps.com',
//    'yuting.xu@shoptps.com'

    //2017-03-08
    'danny.lin@shoptps.com',
    'sandy.chen@shoptps.com',
    'judy.huang@shoptps.com',
    'jeff.pan@shoptps.com',
    'sophie.shi@shoptps.com',
    'lina.wong@shoptps.com',
    'sabrina.li@shoptps.com',
    'terry.lu@shoptps.com',
    'shenqing.meng@shoptps.com',
    'ckf.chen@shoptps.com',
    'fanny.hou@shoptps.com',
    'gorban.qiu@shoptps.com',//20170330
    'brady.wang@shoptps.com'
);


$config['order_modify_users_power'] = array
(
    'judy.huang@shoptps.com',
    'fanny.hou@shoptps.com',
    'nora.huang@shoptps.com',
    'aimee.jiang@shoptps.com',
    'jane.jin@shoptps.com',
    'jenny.zhang@shoptps.com',
    'sabrina.li@shoptps.com',
    'jeff.pan@shoptps.com',
    'sophie.shi@shoptps.com',
    'lina.wang@shoptps.com',
    'terry.lu@shoptps.com',
    'shenqing.meng@shoptps.com',
    'mercury.liu@shoptps.com',
    'hzl.huang@shoptps.com',
    'cynthia.li@shoptps.com',
    'jay.dou@shoptps.com',
    'soly.song@shoptps.com'
);

/*总裁助理*/
$config['assistant_persident_root'] = array
(
    'Lina.Wong@shoptps.com',
    'Sophie.Shi@shoptps.com',
    'jenny.zhang@shoptps.com',
    'nora.huang@shoptps.com',
    'allison.young@shoptps.com',
    'jane.jin@shoptps.com',
    'aimee.jiang@shoptps.com',
    'fanny.hou@shoptps.com',
    'judy.huang@shoptps.com',
    'sabrina.li@shoptps.com'
);


/*加盟費和月費*/
$config['join_fee_and_month_fee'] = array(
    1 => array(
        'join_fee' => 1500,
        'month_fee' => 60,
    ),
    2 => array(
        'join_fee' => 1000,
        'month_fee' => 40,
    ),
    3 => array(
        'join_fee' => 500,
        'month_fee' => 20,
    ),
    5 => array(
        'join_fee' => 250,
        'month_fee' => 10,
    ),
    4 => array(
        'join_fee' => 0,
        'month_fee' => 0,
    ),
);

/** 1.1之前的加盟费 */
$config['old_join_fee_and_month_fee'] = array(
    1 => array(
        'join_fee' => 1250,
    ),
    2 => array(
        'join_fee' => 750,
    ),
    3 => array(
        'join_fee' => 250,
    ),
    5 => array(
        'join_fee' => 250, //之前是没有铜级店铺的,目前是$250
    ),
    4 => array(
        'join_fee' => 0,
    )
);

$config['store_status'] = array(
    0 => 'not_enable',
    1 => 'enabled',
    2 => 'sleep',
    3 => 'account_disable',
    4 => 'company_keep',
    5 => 'account_disable_m',
    6 => 'signouting',
);

$config['id_card_scan_size_limit'] = 10240000;

$config['admin_role'] = array(
    8 => 'check_card', //审核身份证
    7 => 'role_storehouse_hongkong',
    6 => 'role_storehouse_korea',
    5 => 'role_customer_service_lv1', //客服1
    1 => 'role_customer_service_lv2', //客服2
    2 => 'role_customer_service_manager', //客服经理
    3 => 'operations_personnel', //运营
    4 => 'financial_officer', //财务
    0 => 'role_super', //管理员
);

$config['admin_account_status'] = array(
    0 => 'not_enable',
    1 => 'enabled',
    2 => 'enabled',
    3 => 'not_enable',
);

$config['words'] = array(
    'fuck', '代理', '传销', 'shit',
    '共产党', '法轮功', '习近平', '草你妈', '你妹', 'tps138', 'tps'
);
$config['url_black_words'] = '/tps|fuck|www|email|mail|walhao|wohao|yjp|mall|supplier|admin|erp/i';

//添加一个会员状态 退会中: 6 ；User:Ckf
$config['allow_login_status'] = array(1, 2, 3,6);
//后台运行公司预留账号也进入个人后台
$config['allow_login_status_admin'] = array(1, 2, 3,6,4);

//美国商城订单供应商类型
$config['vendor'] = array("0"=>"","1"=>"1Direct","2"=>"Incentibuys");

/* 免费升级到付费用户的领导人 */
$config['leader_list'] = array(
    '1380100229',
    '1380100218',
    '1380100225',
    '1380100227',
    '1380100228',
    '1380100246',
    '1380100233',
    '1380100237',
    '1380100244',
    '1380100238',
    '1380100248',
    '1380100236',
    '1380100326',
    '1380100289'
);

$config['disable_pay'] = FALSE;

$config['user_upgrade_list'] = array(
    '1380100300' => 1,
    '1380100495' => 3,
    '1380100587' => 3,
    '1380100318' => 1,
    '1380100324' => 1,
    '1380100331' => 1,
    '1380100519' => 1,
    '1380100507' => 1,
    '1380100509' => 3,
    '1380100512' => 3,
    '1380100554' => 1,
    '1380100574' => 2,
    '1380100576' => 1,
    '1380100577' => 2,
    '1380100579' => 1,
    '1380100582' => 1,
    '1380100584' => 3,
    '1380100588' => 3,
    '1380100327' => 1,
    '1380100328' => 1,
    '1380100506' => 3,
    '1380100341' => 1,
    '1380100563' => 3,
    '1380100445' => 1,
    '1380100471' => 1,
    '1380100348' => 1,
    '1380100480' => 1,
    '1380100485' => 1,
    '1380100469' => 3,
    '1380100479' => 1,
    '1380100484' => 1,
    '1380100490' => 1,
    '1380100497' => 1,
    '1380100492' => 3,
    '1380100693' => 3,
    '1380100503' => 3,
    '1380100878' => 3,
    '1380100595' => 3,
    '1380100488' => 3,
    '1380100501' => 3,
    '1380100504' => 3,
    '1380100508' => 3,
    '1380100510' => 1,
    '1380100511' => 3,
    '1380100552' => 3,
    '1380100556' => 1,
    '1380100513' => 3,
    '1380100561' => 1,
    '1380100514' => 1,
    '1380100529' => 3,
    '1380100591' => 3,
    '1380100534' => 3,
    '1380100546' => 3,
    '1380100565' => 1,
    '1380100535' => 3,
    '1380100551' => 3,
    '1380100785' => 3,
    '1380100585' => 3,
    '1380100598' => 1,
    '1380100601' => 2,
    '1380100335' => 1,
    '1380100524' => 1,
    '1380100526' => 1,
    '1380100527' => 3,
    '1380100528' => 3,
    '1380100521' => 1,
    '1380100557' => 1,
    '1380100558' => 1,
    '1380100555' => 1,
    '1380100560' => 1,
    '1380100568' => 1,
    '1380100571' => 3,
    '1380100617' => 3,
    '1380100684' => 1,
    '1308100679' => 1,
    '1380100660' => 3,
    '1380100570' => 1,
    '1380100572' => 1,
    '1380100581' => 3,
    '1380100588' => 3,
    '1380100613' => 1,
    '1380100623' => 1,
    '1380100640' => 1,
    '1380100648' => 1,
    '1380100646' => 1,
    '1380100558' => 3,
    '1380100653' => 3,
    '1380100656' => 1,
    '1380100590' => 1,
    '1380100814' => 1,
    '1380100618' => 3,
    '1380100622' => 3,
    '1380100627' => 3,
    '1380100629' => 3,
    '1380100703' => 3,
    '1380100668' => 1,
    '1380100496' => 1,
    '1380100605' => 3,
    '1380100840' => 3,
    '1380100583' => 3,
    '1380100599' => 3,
    '1380100600' => 3,
);
$config['unionpay'] = array(
    'merId' => '824440357320037', //商户号
    'SDK_SIGN_CERT_PATH' => 'PRO_700000000000001_acp.pfx',
    'SDK_SIGN_CERT_PWD' => '138138',
    'SDK_ENCRYPT_CERT_PATH' => 'pro_public.cer',
    'SDK_FRONT_TRANS_URL' => 'https://gateway.95516.com/gateway/api/frontTransReq.do',
);
$config['unionpay_test'] = array(
    'merId' => '777290058113353', //商户号
    'SDK_SIGN_CERT_PATH' => 'PM_700000000000001_acp.pfx',
    'SDK_SIGN_CERT_PWD' => '000000',
    'SDK_ENCRYPT_CERT_PATH' => 'pro_public.cer',
    'SDK_FRONT_TRANS_URL' => 'https://101.231.204.80:5000/gateway/api/frontTransReq.do',
);

$config['mobile_unionpay_test'] = array(
    'merId' => '824440357320037', //商户号
    'SDK_SIGN_CERT_PATH' => 'PRO_700000000000001_acp.pfx',
    'SDK_SIGN_CERT_PWD' => '138138',
    'SDK_App_Request_Url' => 'https://gateway.95516.com/gateway/api/appTransReq.do',
);

$config['sale_rank'] = array(0 => 'SO', 1 => 'MSO', 2 => 'MSB', 3 => 'SMD', 4 => 'EMD', 5 => 'GVP');


/* * *代品券面额(按从小到大排列)*** */
$config['suite_exchange_coupon_face_value'] = array(
    100,
    50,
    20,
    10,
    1
);

/* * *代品券面额*** */
$config['coupons_money'] = array(
    '10001' => 1,
    '10002' => 10,
    '10003' => 20,
    '10004' => 50,
    '10005' => 100
);

/* * *预计发货时间(付款后延迟3天发货)** */
$config['expect_deliver_date'] = 3;

/* * *需要重新选择套餐的用户** */
$config['again_choose_group'] = array(
    '1380111068',
    '1380102474',
    '1380114233',
    '1380108432',
    '1380108434',
    '1380109892',
    '1380100220'
);

$config['wohao_dev'] = 'real';
//沃好接口
if ($config['wohao_dev'] == 'test') {
    $config['wohao_api'] = [
            'login'=>"http://112.74.22.156/Home/Userapi/tpsLogin",
            'logout'=>"http://112.74.22.156/Home/Userapi/tpsLogout"
    ];
} else {
    $config['wohao_api'] = [
            'login'=>"http://walhao.com/Home/Userapi/tpsLogin",
            'logout'=>'http://walhao.com/Home/Userapi/tpsLogout'
        ];
    }



//显示新品上架楼层配置
$config['cate_new'] = array(156, 840, 410, 344);

//显示热卖推荐楼层配置
$config['cate_hot'] = array(156, 840, 410, 344, 000);

/* 网站首页布局配置 by sky */
$config['nav_layer'] = array(//导航功能布局
    '156' => array(array('name' => 'nav_home', 'link' => ''),
        array('name' => 'nav_new', 'link' => 'index/goods_new'),
        array('name' => 'nav_hot', 'link' => 'index/goods_hot'),
        //array('name' => 'nav_free_ship', 'link' => 'index/goods_free_ship'),
        array('name' => 'nav_promote', 'link' => 'index/promote'),
        //leon 修改添加以下内容
        //array('name' => 'nav_travel', 'link' => 'javascript:show_travel_msg();'),
        array(
            'name' => 'nav_Life',
            'list' => array(
                array(
                    'name' => 'nav_travel',
                    'link' => 'index/category?sn=IylmpnfIy3'//旅游    /index/category?sn=IylmpnfIy3
                    //'link' => 'javascript:show_travel_msg();'//旅游    /index/category?sn=IylmpnfIy3
                ),
                array(
                    'name' => 'nav_repast',
                    'link' => 'index/category?sn=7DZyh6OiiR'          //餐饮    39425279                index/search?keywords=啥咪牛台湾料理餐厅
                    //'link' => 'index/search?keywords=啥咪牛台湾料理餐厅'//餐饮    39425279                index/search?keywords=啥咪牛台湾料理餐厅
                )
            )
        ),
        array(
            'name'=>'nav_country',
            'list'=> array(
                array(
                    'name'=>'韩国馆',
                    'link'=>'index/global_shopping?elevent=korea'
                ),
                /*      array(
                          'name'=>'美国馆',
                          'link'=>'index/global_shopping?elevent=america'
                      ),*/
                array(
                    'name'=>'港澳台馆',
                    'link'=>'index/global_shopping?elevent=gat'
                ),
                array(
                    'name'=>'加拿大馆',
                    'link'=>'index/global_shopping?elevent=canada'
                ),
                /*  array(
                      'name'=>'日本馆',
                      'link'=>'index/global_shopping?elevent=japan'
                  ),*/
                array(
                    'name'=>'欧洲馆',
                    'link'=>'index/global_shopping?elevent=europe'
                ),
                array(
                    'name'=>'澳洲馆',
                    'link'=>'index/global_shopping?elevent=australia'
                ),
                array(
                    'name'=>'东南亚馆',
                    'link'=>'index/global_shopping?elevent=southeast-asia'
                ),
            )
        ),
        array('name' => 'about_us', 'link' => 'index/help?aid=59'),

    ), //中国
    '840' => array(array('name' => 'nav_home', 'link' => ''),
        array('name' => 'nav_new', 'link' => 'index/goods_new'),
        array('name' => 'nav_hot', 'link' => 'index/goods_hot'),
        //array('name' => 'nav_free_ship', 'link' => 'index/goods_free_ship'),
        //array('name'=>'nav_promote','link'=>'index/promote')
        array(
            'name' => 'nav_affiliate',
            'list' => array(
                array(
                    'name' => 'nav_aff_1',
                    'link' => 'http://linksynergy.walmart.com/fs-bin/click?id=fj4YOSrjbUU&offerid=223073.10005930&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_2',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&subid=&offerid=390449.1&type=10&tmpid=6933&u1=STOREID&RD_PARM1=http://www.rakuten.com/'
                ),
                array(
                    'name' => 'nav_aff_3',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=371325.16&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_4',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=448595.5292&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_5',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=450619.21&type=4&subid=0&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_6',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=422011.14&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_7',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=291828.5886&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_8',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=345415.10000102&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_9',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=414807.10000737&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_10',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=301691.10000531&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_11',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=440573.10000600&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_12',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=209195.10001729&type=4&subid=0&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_13',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=418635.413&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_14',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&subid=&offerid=373930.1&type=10&tmpid=1296&u1=STOREID&RD_PARM1=http://www.beauty.com'
                ),
                array(
                    'name' => 'nav_aff_15',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&subid=&offerid=206959.1&type=10&tmpid=5988&u1=STOREID&RD_PARM1=http://macys.com'
                ),
                array(
                    'name' => 'nav_aff_16',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=443701.10001110&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_17',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=354329.73&type=4&subid=0&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_18',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&subid=&offerid=396955.1&type=10&tmpid=13451&u1=STOREID&RD_PARM1=http://www.flowershopping.com/'
                ),
                array(
                    'name' => 'nav_aff_19',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=435856.34&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_20',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&subid=&offerid=298708.1&type=10&tmpid=13863&u1=STOREID&RD_PARM1=http://venuekings.com'
                ),
//                array(
//                    'name' => 'nav_aff_21',
//                    'link' => 'http://goto.target.com/c/249131/79123/2092?subId1=STOREID'
//                ),
                array(
                    'name' => 'nav_aff_22',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=393218.10000809&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_23',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=393224.453&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_24',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=436599.25&subid=0&type=4&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_25',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=404388.484&type=4&subid=0&u1=STOREID'
                ),
                array(
                    'name' => 'nav_aff_26',
                    'link' => 'http://click.linksynergy.com/fs-bin/click?id=fj4YOSrjbUU&offerid=445126.29&type=4&subid=0&u1=STOREID'
                ),
            )
        ),
        array('name' => 'nav_travel', 'link' => 'javascript:show_travel_msg();'),
        array('name' => 'about_us', 'link' => 'index/help?aid=63'),
    ), //美国
    '410' => array(array('name' => 'nav_home', 'link' => ''),
        array('name' => 'nav_new', 'link' => 'index/goods_new'),
        array('name' => 'nav_hot', 'link' => 'index/goods_hot'),
        //array('name'=>'nav_free_ship','link'=>'index/goods_free_ship'),
        array('name' => 'nav_promote', 'link' => 'index/promote'),
        array('name' => 'nav_travel', 'link' => 'javascript:show_travel_msg();'),
        array('name' => 'about_us', 'link' => 'index/help?aid=91'),
    ), //韩国
    '344' => array(array('name' => 'nav_home', 'link' => ''),
        array('name' => 'nav_new', 'link' => 'index/goods_new'),
        array('name' => 'nav_hot', 'link' => 'index/goods_hot'),
        //array('name'=>'nav_free_ship','link'=>'index/goods_free_ship'),
        array('name' => 'nav_promote', 'link' => 'index/promote'),
        array('name' => 'nav_travel', 'link' => 'javascript:show_travel_msg();'),
        array('name' => 'about_us', 'link' => 'index/help?aid=62'),
    ), //香港
    '000' => array(array('name' => 'nav_home', 'link' => ''),
        //array('name'=>'nav_new','link'=>'index/goods_new'),
        //array('name'=>'nav_hot','link'=>'index/goods_hot'),
        //array('name'=>'nav_free_ship','link'=>'index/goods_free_ship'),
        //array('name'=>'nav_promote','link'=>'index/promote'),
        array('name' => 'about_us', 'link' => 'index/help?aid=63'),
    ), //其它地区
    '0' => array(), //管理员后台
);
$config['floor_layer'] = array(//楼层配置
    //中国
    '156' =>array(
        'floor_name' => array('cate_1', 'cate_2', 'cate_3', 'cate_4', 'cate_5', 'cate_6', 'cate_7', 'cate_8', 'cate_9'),
        'floor_cate' => array(
            92   => array( 'cate_cn' => 'AotyTAhdbv','cate_name'  =>'食品酒水'),
            35   => array( 'cate_cn' => 'VjCumOUrIi', 'cate_name' =>'美妆个护'),
            122  => array( 'cate_cn' => 'zhJoOMFhRV','cate_name'  =>'家居日用'),
            1187 => array( 'cate_cn' => 'yH9zgV5oCK','cate_name'  =>'服饰鞋帽'),
            80   => array( 'cate_cn' => 'MRUya5BVJ5', 'cate_name' =>'营养保健'),
            158  => array( 'cate_cn' => 'dc8hfvl17J','cate_name'  =>'钟表首饰'),
            20   => array( 'cate_cn' => 'u9f0KhcZci','cate_name'  =>'母婴用品'),
            1036 => array( 'cate_cn' => 'K0JpROsUQY','cate_name'  =>'礼品箱包'),
            497  => array( 'cate_cn' => 'wk1BC6QKrZ','cate_name'  =>'数码电子'),
            1066 => array( 'cate_cn' => 'mWKI6osgkg','cate_name'  =>'汽车用品'),
            /*  65   => array( 'cate_cn' => 'AcrsRgyBJo','cate_name'  =>'运动户外'),
              1220 => array( 'cate_cn' => 'wdm5CR6M5Q','cate_name'  =>'宠物生活'),*/
        ),
    ) ,
    //美国
    '840' => array(
        'floor_name' => array('cate_1', 'cate_2', 'cate_3', 'cate_4', 'cate_5', 'cate_6', 'cate_7', 'cate_8', 'cate_9', 'cate_10'),//leon添加
        'floor_cate' => array(
            34    => array( 'cate_cn' => 'VjCumOUrIi', 'cate_name' =>'Health & Beauty'),//营养保健
            157   => array( 'cate_cn' => 'dc8hfvl17J', 'cate_name' =>'Apparel & Shoes'),//服饰鞋帽
            379   => array( 'cate_cn' => 'zhJoOMFhRV', 'cate_name' =>'Home & Garden'),//家居用品
            383   => array( 'cate_cn' => 'd3uxgyBPpI', 'cate_name' =>'Electronics'),//数码电子
            64    => array( 'cate_cn' => 'AcrsRgyBJo', 'cate_name' =>'Outdoor & Sports '),//运动户外
            395   => array( 'cate_cn' => 'S850zZjAlk', 'cate_name' =>'Books'),//休闲娱乐
            407   => array( 'cate_cn' => 'u9f0KhcZci', 'cate_name' =>'Kids, Baby & Toy'),//母婴用品
            // 256   => array( 'cate_cn' => 'r8RlR68b1Z', 'cate_name' =>'Cleaning Products'),//
            774   => array( 'cate_cn' => '8hQ3rPF5bM', 'cate_name' =>'Tool & Industrial'),//工具和工业
            // 103   => array( 'cate_cn' => 'jH44Ep2kEd', 'cate_name' =>'Eyelash'),//
            405   => array( 'cate_cn' => '1eL9JDFKbI', 'cate_name' =>'Games & Movies'),//游戏机
            496   => array( 'cate_cn' => 'wk1BC6QKrZ', 'cate_name' =>'Electric Appliance'),//鼠标
        ),
    ),
    //韩国
    '410' =>array(
        'floor_name' => array('cate_1', 'cate_2', 'cate_3', 'cate_4', 'cate_5', 'cate_6', 'cate_7'),//leon添加
        'floor_cate' => array(
            937   => array( 'cate_cn' => 'ag7taJdKkk', 'cate_name' =>'패션의류/잡화'),//服饰
            929   => array( 'cate_cn' => 'JKiS723c0U', 'cate_name' =>'뷰티/케어'),//美妆
            939   => array( 'cate_cn' => 'KgUE9iXUli', 'cate_name' =>'식품/음료'),//食品
            949   => array( 'cate_cn' => 'eEaWZSXgMB', 'cate_name' =>'건강관리'),//保健
            952   => array( 'cate_cn' => 'bjzpVSIrNC', 'cate_name' =>'홈데크/생필품'),//家居
            934   => array( 'cate_cn' => 'J51t2pALMK', 'cate_name' =>'애완용품'),//宠物
            1307  => array( 'cate_cn' => 'u3F33WlsDg', 'cate_name' =>'디지털/가전/컴퓨터'),//电器
        ),
    ) ,
    //香港
    '344' =>array(
        'floor_name' => array('cate_1', 'cate_2', 'cate_3', 'cate_4', 'cate_5', 'cate_6'),
        'floor_cate' => array(
            36     => array( 'cate_cn' => 'VjCumOUrIi', 'cate_name' =>'美妝護理 Beauty & Skin Care'),
            1337   => array( 'cate_cn' => 'Y6b1V6gh9g', 'cate_name' =>'糧油食品 Food'),
            93     => array( 'cate_cn' => 'AotyTAhdbv', 'cate_name' =>'酒水飲用 Drinks'),
            1351   => array( 'cate_cn' => 'HzHJ2ZcxIw', 'cate_name' =>'個人配飾 Accessories'),
            123    => array( 'cate_cn' => 'zhJoOMFhRV', 'cate_name' =>'家居用品 Homeware'),
            1200   => array( 'cate_cn' => 'g5k4nwGk8T', 'cate_name' =>'休閒娛樂 Leisure & Entertainment'),
        ),
    ) ,
    //其它地区
    '000' =>array() ,
);

// 订单流水操作代码映射表
$config['order_oper_map'] = array(
    100 => "order_log_oper_create", // 订单创建
    101 => "order_log_oper_modify", // 订单修改
    108 => "order_log_oper_export", // 訂單導出
    102 => "order_log_oper_diliver", // 订单发货
    103 => "order_log_oper_reset", // 订单重置
    104 => "order_log_oper_rollback", // 订单回滚
    105 => "order_log_oper_cancel", // 订单取消
    106 => "order_log_oper_frozen", // 订单冻结
    107 => "order_log_oper_unfrozen", // 解除冻结
    109 => "order_log_oper_addr_edit", // ERP 修改订单地址信息
    110 => "order_log_oper_suit", // 记录修改商城的产品套装的订单状态
    111 => "order_log_oper_recovery", // 普通订单恢复
    112 => "order_log_oper_recovery", // 升级订单的恢复
    150 => "order_log_oper_erpmodify", // ERP 修改订单信息
    120 => "order_log_oper_exchange", // ERP 修改订单信息
    130 => "cancel_exchange", // 取消换货
);

//138矩阵佣金分成比例
$config['matrix_percent'] = 0.05;
$config['avg_percent'] = 0.05;

//2x5订单要求
$config['2x5_order_rule'] = false;

//138新规则
$config['138_order_rule'] = true;

//付费的用户等级
$config['pay_rank'] = array(1, 2, 3, 5);
//短信验证码配置
$config['phone_cfg'] = array(
    1 => array('signature' => 'tps注册验证', 'template' => 'SMS_8946049', 'param' => '{"code":"@@@@","product":"TPS"}'),
    2 => array('signature' => '变更验证', 'template' => 'SMS_8946047', 'param' => '{"code":"@@@@","product":"TPS"}'),
    3 => array('signature' => '变更验证', 'template' => 'SMS_8946047', 'param' => '{"code":"@@@@","product":"TPS"}'),
    4 => array('signature' => '变更验证', 'template' => 'SMS_8946046', 'param' => '{"code":"@@@@","product":"TPS"}'),
    5 => array('signature' => '变更验证', 'template' => 'SMS_8946046', 'param' => '{"code":"@@@@","product":"TPS"}'),
);
//$config['phone_cfg'] = array(
//    1 => array('signature' => '注册验证', 'template' => 'SMS_8946049', 'param' => '{"code":"@@@@","product":"TPS"}'),
//    2 => array('signature' => '变更验证', 'template' => 'SMS_8946047', 'param' => '{"code":"@@@@","product":"TPS"}'),
//    3 => array('signature' => '变更验证', 'template' => 'SMS_8946047', 'param' => '{"code":"@@@@","product":"TPS"}'),
//    4 => array('signature' => '变更验证', 'template' => 'SMS_8946046', 'param' => '{"code":"@@@@","product":"TPS"}'),
//    5 => array('signature' => '变更验证', 'template' => 'SMS_8946046', 'param' => '{"code":"@@@@","product":"TPS"}'),
//);

//售後中心：問題分類
$config['tickets_problem_type'] = array(
    //0 =>'add_and_quit',//加入/退出
    14 => 'tickets_change_delivery_information', //更改收货信息
    15 => 'tickets_order_cancellation', //取消订单
    //16=>'tickets_check_order_status',//催货
    //8 =>'freight_problem',//运费问题投诉
    //17=>'tickets_product_review',//产品投诉
    20 => 'shipping_logistics_problems', //催货/物流问题
    21 => 'tickets_product_damage', //产品破损
    22 => 'tickets_leakage_wrong_product', //产品错发/漏发
    //19 =>'tickets_after_sales_problem',//售后问题
    12 => 'join_issue', //账户信息问题
    3 => 'shop_transfer', //店铺转让
    2 => 'platform_fee_problem', //月费
    9 => 'withdraw_funds_problem', //提现问题
    1 => 'up_or_down_grade', //升级/支付问题
    13 => 'quit_issue', //降级/退出申请
    6 => 'commission_problem', //佣金问题
    //4 => 'reward_system',//奖励制度
    5 => 'product_recommendation', //产品推荐
    //7 =>'order_problem',//订单问题
    18 => 'tickets_member_suggestions', //会员建议
    //10 => 'walhao_store', //沃好商城
    //11 =>'other',//其他
);
//售后中心，状态
$config['tickets_status'] = array(
//	0=>'new_ticket',
//	1=>'open_ticket',
    0 => 'new_tickets',
    1 => 'new_tickets',
    2 => 'waiting_reply',
    3 => 'waiting_discuss',
    4 => 'ticket_resolved',
    5 => 'had_graded',
    6 => 'apply_close',
    7 => 'new_tickets',
    8 => 'new_msg',
);

//售后中心,优先级
$config['tickets_priority'] = array(
    0 => 'general_tickets',
    1 => 'preferential_tickets',
    2 => 'urgent_tickets',
);
//log
$config['status_data_type'] = array(
    0 => 'add_new_tickets',
    1 => 'tickets_status',
    2 => 'tickets_priority',
    3 => 'modified_manager',
    4 => 'apply_close_tickets',
    5 => 'view_tickets',
    6 => 'r_waiting_reply',
    7 => 'r_waiting_discuss',
    8 => 'tickets_tips',
    9 => 'r_tickets_resolved',
    10 => 'change_tickets_type',
    11 => 'auto_reply_tickets',
    12 => 'close_tickets_send_email',
    13 => 'auto_close_tickets',
);

//售后中心，图片后缀
$config['tickets_attach_is_picture'] = array(
    0 => '.jpg',
    1 => '.jpeg',
    2 => '.png',
    3 => '.bmp',
);

//订单同步到ERP每次推送的数量
$config['push_order_numbers'] = '128';

//商品库存同步到ERP每次推送的数量
$config['push_stock_numbers'] = '128';

$config['tickets_cus_area'] = array(
    1 => 'tickets_area_usa',
    2 => 'tickets_area_china',
    3 => 'tickets_area_hk',
    4 => 'tickets_area_korea',
);

//允许上传
$config['tickets_upload_file_type'] = '*.gif;*.jpg; *.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.bmp;*.pdf;*.rar;*.zip';

//工单统计权限
//$config['tickets_statistics_right'] = array(1,68,144,71,62,61,103,107,116,121,120,224,275,389,385,435,219,469,255);

//$config['tickets_assign_right'] = array(1,8,9,62,68,144);//全

//$config['unallocated_tickets_assign_right'] = array(435,131,162);

//$config['tickets_customer_role_right'] = array(1,8,9,62,68,144,435,99,219);

//$config['order_repair_of_comm_right'] = array(68,144,1,3,8,9,18,60,61,62,71,99,103,116,121,122,173,188,192,210,212,275,277,389,107,224,120,160,404,270,251,331,128,78,268,256);

#不可发货商品
$config['not_deliever_goods'] = ["69591406-1","46003866-1","72617009-1","75305229-1","69754989-1","71555549-1","37539079-1","75529826-1","48449483-1","89426148-1","23994313-1","81602432-1" ,"24315147-1" ,"39779845-1","22773359-1","06288284-1","02672349-1","01454689-1","20576444-1","41352594-1","35999298-1","29006749-1","49798215-1","92196878-1","67283704-1","81152525-1","49933893-1","40455162-1","41891505-1","88220897-1","11147902-1","53560731-1","54154013-1","10094773-1","26683448-1","91308910-1","94108368-1","92057899-1","10028150-1","26428786-1","80268882-1","51192963-1","09839519-1","76311024-1","45181260-1","38922898-1","65014296-1","14760021-1","69182915-1","19960625-1","21430517-1","00229022-1","87318352-1","23748732-1","52587432-1","58487180-1","06441408-1","41050753-1","68053475-1","14617423-1","38809885-1","49062800-1","47061219-1","03064034-1","34351267-1","30368869-1","32874223-1","32879230-1","71664999-1","82432072-1","64983983-1","09454843-1","43769254-1","18919729-1","38544628-1","47114325-1","91131011-1","10058695-1","65319555-1","28037178-1","54871787-1","26254836-1","91441520-1","74866103-1","91957403-1","23316534-1","50181695-1","38881458-1","93912204-1","87129073-1","46998815-1","61956498-1","80857768-1","63976856-1","82787941-1","77613244-1","59995858-1","93066489-1","47056321-1","89644474-1","66676280-1","16558324-1","50353701-1","67617967-1","19941892-1","57470092-1","38963849-1","14888157-1","90114087-1","58313812-1","42149147-1","59765481-1","35497770-1","64145473-1","16150030-1","88662334-1","56449006-1","93652246-1","71880798-1","64374483-1","32984362-1","22303489-1","31657002-1","41474962-1","03902227-1","35466681-1","37950480-1","35842070-1","61325093-1","52868147-1","86595079-1","60374008-1","08549913-1","55893783-1","86447085-1","25650850-1","58222736-1","02790042-1","87366947-1","64250106-1","74086119-1","46886030-1","58410120-1","12812514-1","42894074-1","95166319-1","33054175-1","66326290-1"];

#不可发货区域
$config['not_deliever_district'] = ['54','23','21','22','65','15','62','63'];
#不可发货时间段
$config['not_deliever_goods_start'] = "2016-11-26 00:00:00";
$config['not_deliever_goods_end'] = "2017-3-1 23:59:59";
$config['admin_phone_notice'] = array(
    'phone'=>'15014670770',
    'msg' =>' 重要提醒:身份证审核次数用尽，需重新购买次数'
);

/*阿里云印刷文字识别-身份证识别接口   目前只能是多组appKey appSecret 数组每项使用次数为10万次
 * @author JacksonZheng
 * 2017-01-19
 */
$config['aliyun_card_api'] = array(
    array('appKey'=>'24514940','appSecret'=>'eb9e581049cd51f44305a1e6211c0a80')     //150万次
);

//MVP 报名人数限制
$config['mvp_count'] = 1500;

//升级订单不再发放代品券时间
$config['upgrade_not_coupon'] = '2017-03-03 00:00:00';
//$config['upgrade_not_coupon'] = '2017-02-28 20:02:30';
//升级订单换货功能
$config['upgrade_exchange'] = '2017-03-03 00:00:00';
$config['upgrade_not_3'] = '2017-03-03 00:00:00';
$config['upgrade_not_4'] = '2017-03-02 22:00:00';
//$config['upgrade_exchange'] = '2017-03-01 00:02:30';


$config['bonus_right_away_take_off_end_time'] = "2017-04-01 00:00:00";//销售精英日分红 全球日分红 138分红第二日发放规则截止日期
$config['new_member_bonus_start_time'] = "2017-04-01 00:00:00";//新会员专属奖开始日期
$config['team_profit_old_end_time'] =   "2017-03-01 00:00:00";//团队分红旧分红截止日期
$config['team_profit_new_floor'] = 2;//新团队分红层数
//团队销售分红比例配置 m by brady.wang 2017/02/23
//免费店铺：第一级店铺销售利润提成5%；
//铜级店铺：第一级店铺销售利润提成10%，第二级店铺销售利润提成5%；
//银级店铺：第一级店铺销售利润提成12%，第二级店铺销售利润提成7%；
//白金店铺：第一级店铺销售利润提成15%，第二级店铺销售利润提成10%；
//钻石店铺：第一级店铺销售利润提成20%，第二级店铺销售利润提成12%.
//钻石
$config['team_profit_1'] = [
    1 => 0.20,
    2 => 0.12
];
//白金
$config['team_profit_2'] = [
    1 => 0.15,
    2 => 0.10
];
//银级
$config['team_profit_3'] = [
    1 => 0.12,
    2 => 0.07
];
//铜级
$config['team_profit_5'] = [
    1 => 0.10,
    2 => 0.05
];
//免费
$config['team_profit_4'] = [
    1 => 0.05,
    2 => 0.00
];
//团队分红利润提成比例, 新规则, add by derrick.zhang 2017/05/02
$config['team_profit_proportion'] = array(
    0 => array(
        1 => 0.2,
        2 => 0.15,
        3 => 0.12,
        4 => 0.05,
        5 => 0.1,
    ),
    1 => array(
        1 => 0.12,
        2 => 0.1,
        3 => 0.07,
        4 => 0.05,
        5 => 0.05,
    ),
);
$config['upgrade_not_3'] = '2017-03-03 00:00:00';

$config['commission_time'] = '201704';//修改奖金金额时间

$config['admin_file_type'] = array(
    1 => 'admin_file_announcement',
//    2 => 'admin_file_regime',
//    3 => 'admin_commission_explain',
);
$config['redis_key'] = array(
    'bulletin_board_index' => 'ucenter:bulletin_board:index:', //公告缓存键 $key = 'bulletin_board:index:'.$this->_userInfo['id'].":".$page;
    'bulletin_board_list' => 'ucenter:bulletin_board:list:', //公告缓存键 $key = 'bulletin_board:index:'.$this->_userInfo['id'].":".$page;
    "getFiveBoard" => "ucenter:welcome_new:index:getFiveBoard:",
    "cash_take_out_logs" => "ucenter:cash_take_out_logs:",//提现记录日志 .$uid.":".$page
    "leadership_bulletin" => "ucenter:leadership_bulletin",//精英排行榜
);
$config['redis_expire'] = array(
    "bulletin_board_index" => 7200,
    "bulletin_board_list" => 7200,
    "cash_take_out_logs"=>7200,
    'getFiveBoard' => 7200,
    'leadership_bulletin' => 600,
);

$config['admin_file_area'] = array(
    1 => 'U.S.A',
    2 => '中国',
    3 => '香港',
    4 => '한국',
    5 => 'Other',
);

//登陆验证码开关 0关闭 1启用
$config['login_captcha'] = array('switch'=>1);
//后台登陆验证码开关 0关闭 1启用
$config['admin_login_captcha'] = array('switch'=>1);

//导出海关的订单是不能导出这个时间之前的订单
$config['Time_too_early'] = '2017-06-20 10:45:59';

//输入真实姓名敏感词开关
$config['name_sensitive'] = array('switch'=>0);