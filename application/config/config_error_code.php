<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/* error code config. */
$config['error_code'] = array(
    0 => 'success',//成功
    100 => 'Interface Closed',//接口关闭
    101 => 'data_error',//参数错误
    102 => 'hacker',//黑客行为
    103 => 'system_error',//系统错误
    104 => 'no_changed_content',//没有修改的内容
    105 => 'validate_error',//数据校验失败
    1001 => 'regi_errormsg_mail',//邮箱地址有误
    1002 => 'regi_errormsg_mail_exist',//该邮箱地址已注册
    1003 => 'regi_errormsg_pwd',//密码格式不正确，密码为6-18位的字符，不能含空格,不能全为数字！
    1004 => 'regi_errormsg_repwd',//两次密码不一致
    1005 => 'regi_errormsg_repwd_2',//请先填写密码,
    1006 => 'regi_errormsg_parent_id',//请输入正确的源店铺ID
    1007 => 'regi_errormsg_agreed',//请勾选协议条款
    1008 => 'user_not_exit',//用户不存在
    1009 => 'user_had_enabled',//用户已经激活
    1010 => 'user_pwd_error',//用户密码错误
    1011 => 'languageid_error',//语言id错误
    1012 => 'account_active_false',//失效的链接地址
    1013 => 'positive_num_error',//请输入大于0的数值，如果是小数，保留小数点后面两位。
    1014 => 'sharing_point_month_limit',//分红点转出超过每月限额。
    1015 => 'input_country',//请选择国家/地区。
    1016 => 'pls_input_person_id_card_num',//请输入身份证号码！
    1017 => 'person_id_card_num_error',//请输入身份证号码！
    1018 => 'pls_upload_id_card_scan',//请上传身份证扫描件！
    1019 => 'login_captcha_error',//您的验证码有误！
    1020 => 'pls_sel_mem_rank',//请选择会员类型！
    1021 => 'login_status_error',//用戶沒有激活
    1022 => 'user_name_error',//用戶真实姓名
    1024 => 'sensitive',//真实姓名包含禁用字
    1023 => 'mobile_error',//用戶手机号
    1025 => 'sharing_point_lacking',//分红点不足。
    1026 => 'address_repeat',//地址和全名已經存在。
    1027 => 'no_address',//輸入地址。
    1028 => 'no_disclaimer',//没有勾引协议。
    1029 => 'mobile_error',//请输入正确的手机号，提现将进行验证。
    1030 => 'cur_commission_lack',//当前现金余额不足。
    1031 => 'no_need_upgrade',//无需升级。
    1032 => 'no_active_monthly_fee_coupon',//没有可使用的月费抵用券。
    1033 => 'user_id_list_requied',//请填写会员id
    1034 => 'half_year_exe',//半年之內不能重置
    1035 => 'email_match',//邮箱不匹配
    1036 => 'uniqueCard',//身份证号已存在
    1037 => 'cart_over_limit',//购物车数量超过上限
    1038 => 'user_is_store',//用户已经是店主
    1039 => 'goods_low_stocks',//商品库存不足
    1040 => 'no_shop_keeper_id',//没有店主id
    1041 => 'invalid_user_address',//无效的收货地址
    1042 => 'invalid_area',//收货区域有误
    1043 => 'captcha_expire',//验证码过期失效
    1044 => 'had_order_repairing_year_month',//已经有补单中的年月了，请先取消后才能标记新的补单年月。
    1045 => 'is_binding_info',//已经有补单中的年月了，请先取消后才能标记新的补单年月。
    1046 => 'proportion_error',//佣金自动转分红点比例的格式错误。
    1047 => 'no_need_tran_to_self',//您无需转帐给自己。
    1048 => 'funds_pwd_error',//资金密码不正确。
	1049 => 'uniqueMobile',//手机号已存在
	1050 => 'unique_job_number',//客服工号唯一
	1051 => 'job_number_error',//客服工号错误
	1052 => 'tps_email_info',//请输入正确的邮箱地址
	1053 => 'tps_unique_email',//该邮箱已经注册
    1054 => 'cart_num_over_limit',//购物车SKU数超过上限
);
/**************Paypal大宗付款错误码*******************/
$config['paypal_error_code'] = array(
    1001 => "Receiver's account is invalid",
    1002 => "Sender has insufficient funds",
    1003 => "User's country is not allowed",
    1004 => "User's credit card is not in the list of allowed countries of the gaming merchant",
    3004 => "Cannot pay self",
    3014 => "Sender's account is locked or inactive",
    3015 => "Receiver's account is locked or inactive",
    3016 => "Either the sender or receiver exceeded the transaction limit",
    3017 => "Spending limit exceeded",
    3047 => "User is restricted",
    3078 => "Negative balance",
    3148 => "Receiver's address is in a non-receivable country or a PayPal zero country",
    3535 => "Invalid currency",
    3547 => "Sender's address is located in a restricted State (e.g., California)",
    3558 => "Receiver's address is located in a restricted State (e.g., California)",
    3769 => "Market closed and transaction is between 2 different countries",
    4001 => "Internal error",
    4002 => "Internal error",
    8319 => "Zero amount",
    8330 => "Receiving limit exceeded",
    8331 => "Duplicate mass payment",
    9302 => "Transaction was declined",
    11711 => "Per-transaction sending limit exceeded",
    14159 => "Transaction currency cannot be received by the recipient",
    14550 => "Currency compliance",
    14761 => "The mass payment was declined because the secondary user sending the mass payment has not been verified",
    14764 => "Regulatory review - Pending",
    14765 => "Regulatory review - Blocked",
    14767 => "Receiver is unregistered",
    14768 => "Receiver is unconfirmed",
    14769 => "Youth account recipient",
    14800 => "POS cumulative sending limit exceeded",
);

/* wms api return code */
$config['wms_api_retcode'] = array(
    0       => 'unknow',    // 未知错误
    200     => 'success',   // 成功
    1000001 => 'ip_forbid', // IP 受限
    1000002 => 'sign_err',  // request sign 签名有误
);

/** 計算無限代獎勵：店鋪金額 **/
$config['level_cash'] = array(
    1 => 1500,//钻石级
    2 => 1000,//白金级
    3 => 500,//银级
    4 => 0,   //免费
    5 => 250   //免费
);

/**  团队销售提成的百分比  1:钻石 2：白金 3：银级 4：免费 销售利润的提成 **/
$config['percent_1'] = array(
    1=>0.20,
    2=>0.10,
    3=>0.05,
    4=>0.02,
    5=>0.02,
    6=>0.02,
    7=>0.02,
    8=>0.02,
    9=>0.02,
   10=>0.02,
);
$config['percent_2'] = array(
    1=>0.15,
    2=>0.08,
    3=>0.05,
    4=>0.02,
    5=>0.02,
    6=>0.02,
);
$config['percent_3'] = array(
	1=>0.10,
	2=>0.05,
	3=>0.03,
);
$config['percent_5'] = array(
	1=>0.07,
	2=>0.05,
);
$config['percent_4'] = array(
    1=>0.05 //如果是免费会员 销售提成拿一代，只能拿0.1
);


//測試金額的用戶郵箱
$config['test_pay_email'] = array(
    '373524997@qq.com',
    '476471995@qq.com',
    'john.he@ecosko.com',
    'terry.lu@ecosko.com',
    'terry.lu@tps138.com',
    'john.he4@ecosko.com',
    'john.he3@ecosko.com',
    'john.he4@ecosko.com',
    'duke.soong@tps138.com',
	'dukejzs@163.com'
);

