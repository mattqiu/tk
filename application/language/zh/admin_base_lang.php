<?php
/****paypal提现****/
$lang["Receiver's account is invalid"]='收件人的帐户无效';
$lang["Sender has insufficient funds"]='发件人资金不足';
$lang["User's country is not allowed"]='用户的国家不允许使用';
$lang["User's credit card is not in the list of allowed countries of the gaming merchant"]='用户的信用卡不在商家的允许国家列表中';
$lang["Cannot pay self"]='不能自我支付';
$lang["Sender's account is locked or inactive"]='发件人的帐户已锁定或无效';
$lang["Receiver's account is locked or inactive"]='收件人的帐户已锁定或无效';
$lang["Either the sender or receiver exceeded the transaction limit"]='发件人或收件人超出了交易限制';
$lang["Spending limit exceeded"]='超出支出限额';
$lang["User is restricted"]='用户受限';
$lang["Negative balance"]='负余额';
$lang["Receiver's address is in a non-receivable country or a PayPal zero country"]='接收方地址是在非收款国家或贝宝零国家';
$lang["Invalid currency"]='货币无效';
$lang["Sender's address is located in a restricted State (e.g., California)"]='发件人地址位于受限州（例如，加利福尼亚州）';
$lang["Receiver's address is located in a restricted State (e.g., California)"]='接收方地址位于受限州（例如，加利福尼亚州）';
$lang["Market closed and transaction is between 2 different countries"]='市场关闭，交易在两个不同的国家之间';
$lang["Internal error"]='PayPal风控限制(内部错误)';
$lang["Zero amount"]='金额为零';
$lang["Receiving limit exceeded"]='超过接收限制';
$lang["Duplicate mass payment"]='重复大量付款';
$lang["Transaction was declined"]='交易被拒绝';
$lang["Per-transaction sending limit exceeded"]='每个发送交易超出限制';
$lang["Transaction currency cannot be received by the recipient"]='收款人无法收到交易货币';
$lang["Currency compliance"]='货币合规';
$lang["The mass payment was declined because the secondary user sending the mass payment has not been verified"]='大量付款被拒，因为发送大量付款的次要用户尚未验证';
$lang["Regulatory review - Pending"]='监管审查 - 待定';
$lang["Regulatory review - Blocked"]='监管审查 - 阻止';
$lang["Receiver is unregistered"]='接收方未注册';
$lang["Receiver is unconfirmed"]='接收方未确认';
$lang["Youth account recipient"]='青年帐户收件人';
$lang["POS cumulative sending limit exceeded"]='POS累计发送超出限制';
$lang['paypal_withdrawal_list'] = 'paypal提现列表';
$lang['submit_paypal'] = '提交Paypal处理';
$lang['batch_modification'] = "批量修改为'已处理'状态";
$lang['Paypal Account number'] = "Paypal账号";
$lang['Delete_user'] = "该ID存在资金变动记录，将转为公司预留账户";
$lang['paypal_pending_log'] = 'paypal退款状态订单记录';
$lang['paypal_pending_ts'] = '您确定该订单已经处理完成！';
$lang['paypal_pending_cl'] = '处理';

/****同步到沃好****/
$lang['synchronize_wo_hao'] = '同步到沃好';
/** 黑名单列表 */
$lang['add_blacklist'] = '添加敏感词';
$lang['blacklist'] = '敏感词列表';
$lang['blacklist_ex'] = '敏感词';
$lang['enter_blacklist'] = '请输入敏感词';
$lang['btn_check'] = '正向检测';
$lang['btn_f_check'] = '反向检测';
$lang['transfer_contr'] = '转账监控';
$lang['title_level_0'] = '店主(SO)';
$lang['title_level_1'] = '资深店主(MSO)';
$lang['title_level_2'] = '市场主管(MSB)';
$lang['title_level_3'] = ' 高级市场主管(SMD)';
$lang['title_level_4'] = '市场总监(EMD)';
$lang['title_level_5'] = '全球市场销售副总裁(GVP)';
$lang['user_achievement_edit'] = '批量修复业绩';

/** 支付宝提现失败通知，异步通知只会取英文的文字 */
$lang['DAILY_QUOTA_LIMIT_EXCEED'] = '日限额超限';
$lang['RECEIVE_ACCOUNT_ERROR'] = '收款账户有误或不存在';
$lang['RECEIVE_SINGLE_MONEY_ERROR'] = '收款金额超限';
$lang['TRANSFER_AMOUNT_NOT_ENOUGH'] = '转账余额不足，批次失败';
$lang['RECEIVE_USER_NOT_EXIST'] = '收款用户不存在';
$lang['ILLEGAL_USER_STATUS'] = '用户状态不正确';
$lang['ACCOUN_NAME_NOT_MATCH'] = '用户姓名和收款名称不匹配';
$lang['ERROR_OTHER_CERTIFY_LEVEL_LIMIT'] = '收款账户实名认证信息不完整，无法收款';
$lang['ERROR_OTHER_NOT_REALNAMED'] = '收款账户尚未实名认证，无法收款';
$lang['USER_NOT_EXIST'] = '用户不存在';
$lang['PERMIT_NON_BANK_LIMIT_PAYEE_L0_FORBIDDEN'] = '根据监管部门的要求，对方未完善身份信息，无法收款';
$lang['PERMIT_CHECK_PERM_AML_CERT_MISS_4_TRANS_LIMIT'] = '您的账户收付款额度超限';
$lang['PAYEE_ACCOUNT_IS_RELEASED'] = '收款账户名与他人重复，无法进行收付款';

/**会员中心*/
$lang['admin_exchange_user_email_title'] = '邮箱修改';
$lang['admin_exchange_user_mobile_title'] = '手机号修改';
$lang['admin_exchange_user_info_content'] = '确定要修改信息吗?';
$lang['admin_remark_input_not_null'] = '备注信息不能为空！';
$lang['admin_remark_option_name'] = '操作人';
$lang['admin_remark_option_time'] = '备注时间';
$lang['grant_user_hand_bonus_option'] = '手动发奖';
$lang['user_order_achievement_repair'] = '订单延迟业绩修复';
$lang['user_sale_up_time'] = '职称升级时间';
$lang['user_up_time'] = '用户级别升级时间';
$lang['admin_user_rank'] = '用户级别';
$lang['users_amount_check'] = '提现用户转账记录检测';
$lang['users_check_btn'] = '检测';

/***冻结账号**/
$lang['frost_user_time'] = "冻结时长";
$lang['day'] = "天";
$lang['frost_forever'] = "长期冻结";
$lang['please_select_frost_time'] = "请选择冻结时长";

/***奖金类型**/
$lang['pre_week_team_bonus'] = '周团队组织分红奖';
$lang['pre_month_team_bonus'] = '月团队组织分红奖';
$lang['pre_month_leader_bonus'] = '月领导组织分红奖';
$lang['pre_day_bonus'] = '日分红奖';
$lang['pre_new_user_bonus'] = '新会员专项奖';
$lang['develop_msg'] = '开发者管理';
$lang['pre_amount_bonus'] = '发放金额（单位：美元）';
$lang['pre_bonus_submit'] = '重新预发奖';
$lang['pre_bonus_submit_note'] = '注：点击此按钮，将重新生成预发奖，可能会影响到实际发奖，操作时，请及时联系技术人员.';
$lang['user_achievement_note'] = '注：此功能批量修复会员业绩，请按照如下图所示的格式，存放用户id.';

/**公告*/
$lang['admin_board_title_not_null'] = '标题不能全部为空！';
$lang['admin_board_conteng_not_null'] = '公告内容不能全部为空！';
$lang['admin_board_english_title_err'] = '英文标题不能为空！';
$lang['admin_board_chinese_title_err'] = '中文标题不能为空！';
$lang['admin_board_hk_title_err'] = '繁文标题不能为空！';
$lang['admin_board_kr_title_err'] = '韩文标题不能为空！';
$lang['admin_board_en_content_err'] = '英文内容不能为空！';
$lang['admin_board_zh_content_err'] = '中文内容不能为空！';
$lang['admin_board_hk_content_err'] = '繁文内容不能为空！';
$lang['admin_board_kr_content_err'] = '韩文内容不能为空！';

/**会员统计*/
$lang['user_error_total'] = '错误数';
$lang['admin_store_statistics_total'] = '总统计';
$lang['admin_store_statistics_datetime'] = '日期';
$lang['admin_user_level_f'] = '免费会员';
$lang['admin_user_level_b'] = '铜级会员';
$lang['admin_user_level_s'] = '银级会员';
$lang['admin_user_level_g'] = '金级会员';
$lang['admin_user_level_p'] = '钻石会员';
$lang['admin_user_level_t'] = '合计(SUM)';
$lang['admin_everyday_level_t'] = '每日小计';
$lang['admin_everyday_level_count_t'] = '总加盟会员：';
$lang['admin_everyday_level__t'] = '缴费会员：';
$lang['admin_month_level_t'] = '月总计';
$lang['register_total'] = '注册总人数';
$lang['upgrade_total'] = '升级总人数';
$lang['upgrade_total_for_c'] = '铜级升级总人数';
$lang['upgrade_total_for_s'] = '银级升级总人数';
$lang['upgrade_total_for_g'] = '白金级升级总人数';
$lang['upgrade_total_for_d'] = '钻石升级总人数';
$lang['upgrade_total_for_f'] = '免费总人数';

/*MVP颁奖报名名单*/
$lang['mvp_apply_list'] = 'MVP颁奖报名名单';
$lang['mvp_professional_title'] = '职称';
$lang['mvp_apply_time'] = '报名时间';
$lang['mvp_apply_number'] = '报名号';

//mvp直播授权订单
$lang['mvp_live_list'] = 'MVP直播授权订单';
$lang['pls_input_luyan_account'] = '请输入第一路演平台帐号';
$lang['luyan_account'] = '第一路演平台账号';
$lang['mvp_pay_amount'] = '支付金额';
$lang['third_party_order_id'] = '第三方交易号';

/** 售后订单业务流程 */
$lang['admin_exchange_rate_error'] = '汇率格式错误';
$lang['admin_not_demote_tip'] = '铜级、银级店铺不能降级操作';
$lang['admin_go_process'] = '去处理';
$lang['admin_check_pass'] = '审核通过';
$lang['admin_upload_return_fee'] = '上传退运费售后单';
$lang['admin_return_fee_tip1'] = '订单状态异常';
$lang['admin_return_fee_tip2'] = '退款金额大于订单运费';
$lang['admin_return_fee_tip3'] = '退运费订单不能重复提交';
$lang['admin_return_fee_tip4'] = '订单的顾客已经申请退会操作';
$lang['admin_refund_amount'] = '订单退运费:$%s';
$lang['admin_add_after_sale'] = '新建售后订单';
$lang['admin_after_sale_id'] = '售后单号';
$lang['admin_after_sale_uid'] = '关联会员ID';
$lang['admin_after_sale_name'] = '关联会员姓名';
$lang['admin_after_sale_type'] = '售后问题分类';
$lang['admin_after_sale_type_0'] = '退会';
$lang['admin_after_sale_type_1'] = '降级';
$lang['admin_after_sale_type_2'] = '退运费';
$lang['admin_after_sale_method'] = '退款支付方式';
$lang['admin_after_sale_method_0'] = '转账到银行';
$lang['admin_after_sale_method_1'] = '转入现金池';
$lang['admin_after_sale_method_2'] = '转入支付宝';
$lang['admin_after_sale_type_3'] = '退货';
$lang['admin_after_sale_amount'] = '退款金额';
$lang['refund_amount_error'] = '退款金额不能为空或等于0';
$lang['admin_after_sale_remark'] = '反馈内容';
$lang['admin_after_sale_remark_error'] = '反馈内容不能为空';
$lang['admin_after_sale_remark_example'] = '会员要求店铺降级为银级，原为白金。';
$lang['admin_add_after_sale_list'] = '售后管理';
$lang['admin_after_sale_demote'] = '降级等级';
$lang['admin_after_sale_status_0'] = '待处理';
$lang['admin_after_sale_status_1'] = '已抽回(待付款到银行)';
$lang['admin_after_sale_status_2'] = '已抽回(已退款到现金池)';
$lang['admin_after_sale_status_3'] = '已退款到银行';
$lang['admin_after_sale_status_4'] = '抽回驳回';
$lang['admin_after_sale_status_5'] = '退款驳回';
$lang['admin_after_sale_status_6'] = '作废';
$lang['admin_after_sale_status_7'] = '已录入';
$lang['admin_as_upgrade_order'] = '升级订单';
$lang['admin_as_consumed_order'] = '消费订单';
$lang['admin_as_refund'] = '退款处理';
$lang['admin_as_not_exist'] = '售后单号不存在';
$lang['admin_as_status_error'] = '用户状态是公司账户，无法操作!';
$lang['admin_as_view_remark'] = '查看/添加';
$lang['admin_as_action_log'] = '售后处理操作记录表';
$lang['admin_as_update'] = '修改';
$lang['admin_as_payee_no_exist'] = '收款人会员ID不存在';
$lang['admin_after_sale_amount_error'] = '降级退款金额格式不正确';
$lang['admin_after_sale_submit'] = '申请抽回';
$lang['admin_after_sale_repeat'] = '该会员售后退会订单已申请...';
$lang['admin_after_sale_demote_info'] = '该会员售后降级订单正在处理...';
$lang['admin_email_or_id'] = '管理员ID / 邮箱';
$lang['admin_as_upload_info'] = '上传回执单';
$lang['admin_as_del_upload_info'] = '删除回执单';
$lang['admin_after_sale_coupons'] = '注意：扣减代品券不足以支持降级，部分退款金额需要抵掉代品券。';
$lang['is_grant_generation'] = '发放团队销售佣金';
$lang['admin_after_sale_brank_name'] = '开户行不能输入特殊字符，不能为空，最多能输入50个中文字。';
$lang['admin_after_sale_brank_num'] = '银行账户只能输入数字，不能为空，最多能输入50个数字。';
$lang['admin_after_sale_brank_pop'] = '开户名只能输入数字，不能为空，最多能输入50个数字。';

/** 导入运单号 */
$lang['admin_no_lock'] = '未锁定订单';
$lang['admin_yes_lock'] = '已锁定订单';
$lang['admin_select_is_lock'] = '锁定导出订单';
$lang['admin_order_lock'] = '订单已锁定，无法更改';
$lang['admin_file_format'] = '文件格式不支持';
$lang['admin_file_data'] = '文件数据为空';
$lang['admin_file_not_full'] = 'EXCEL中第%s行，信息不完整';
$lang['admin_file_order_status'] = '%s订单状态不匹配';
$lang['admin_file_not_freight'] = '%s物流號不存在';
$lang['admin_select_file'] = '请选择要上传的EXCEL';
$lang['admin_request_failed'] = '请求失败';
$lang['admin_upload_freight'] = '上传运单号';
$lang['admin_scan_shipping'] = '扫描发货';
$lang['admin_scan_order_id'] = '扫描订单ID';
$lang['admin_scan_track_id'] = '扫描运单号';
$lang['admin_export_orders'] = '导出订单';
$lang['admin_download_model'] = '下载模板';
$lang['admin_ship_note_type'] = '发货备注类型';
$lang['admin_ship_note_type1'] = 'N天后';
$lang['admin_ship_note_type2'] = '固定日期';
$lang['admin_ship_note_val'] = '发货日期';
$lang['admin_ship_note_val_eg'] = '例如:5或者 2015/11/11';
$lang['add_admin'] = '添加账户';
$lang['order_report'] = '订单报表';
$lang['store_report'] = '店铺报表';
$lang['order_status_3'] = '待发货';
$lang['order_status_4'] = '已发货';
$lang['order_status_5'] = '货款结算';
$lang['admin_order_status_holding'] = '冻结';
$lang['refund_card_number'] = '退款人身份证';
$lang['transfer_card_number'] = '转让人身份证';
$lang['receive_card_number'] = '接收人身份证';
$lang['receive_email'] = '接收人邮箱';
$lang['transfer_1'] = '转让';
$lang['transfer_2'] = '退款';
$lang['china_weight_fee'] = '中国商品运费';
$lang['usa_weight_fee'] = '美国商品运费';
$lang['shipping_com'] = '物流公司';
$lang['first_line_format_error'] = '第一行格式不相同';
$lang['upload_big_excel_error'] = '上传文件过大,请拆分多个文件进行上传';
$lang['upload_excel_fail'] = '上传文件失败';

/** 支付方式 */
$lang['pay_name'] = '支付名称';
$lang['pay_desc'] = '支付描述';
$lang['pay_currency'] = '支付货币';
$lang['payment_list'] = '支付方式';
$lang['edit_payment'] = '编辑支付';
$lang['is_enabled'] = '是否启用?';
$lang['not_enabled'] = '不启用';
$lang['yes_enabled'] = '启用';
$lang['yspay_username'] = '银盛用户名';
$lang['yspay_merchantname'] = '银盛商户名';
$lang['yspay_pfxpath'] = '银盛私钥名';
$lang['yspay_pfxpassword'] = '银盛私钥密码';
$lang['yspay_certpath'] = '银盛公钥名';
$lang['yspay_host'] = '银盛请求地址';
$lang['unionpay_merId'] = '银联用户名';
$lang['unionpay_pfxpath'] = '银联私钥名';
$lang['unionpay_pfxpassword'] = '银联私钥密码';
$lang['unionpay_certpath'] = '银联公钥名';
$lang['unionpay_host'] = '银联请求地址';
$lang['paypal_account'] = '贝宝账号';
$lang['paypal_submit_url'] = '贝宝提交地址';
$lang['paypal_host'] = '贝宝请求地址';
$lang['ewallet_key'] = '电子钱包密钥';
$lang['ewallet_password'] = '电子钱包密码';
$lang['ewallet_host'] = '电子钱包请求地址';
$lang['ewallet_login'] = '电子钱包登陆地址';
$lang['alipay_account'] = '支付宝账号';
$lang['alipay_key'] = '支付宝密钥';
$lang['alipay_partner'] = '支付宝合作者ID';
$lang['kuaifupay_account'] = '账户';
$lang['kuaifupay_key'] = '密钥';

$lang['old_month'] = '旧月费池';
$lang['user_not_free'] = '用户必须是免费用户并且没有分店';
$lang['delete_free_user'] = '删除免费用户';
$lang['half_year_exe'] = '六个月之内不能使用这操作';
$lang['new_month'] = '月费池余额';
$lang['money_update'] = '变动金额';
$lang['month_type_1'] = '充值';
$lang['month_type_4'] = '交月费';
$lang['action_charge_month'] = '活动抵扣月费';
$lang['join_action_charge_month'] = '您已参加活动抵扣月费计划。';
$lang['action_charge_month_tip'] = '系统:活动抵扣月费订单不能取消、退货。';
$lang['monthly_fee_detail'] = '月费明细表';
$lang['is_withdrawal'] = '店主已经提现了，不能退款';
$lang['is_transfer'] = '店主已经转账了，不能退款';



$lang['update'] = '修改';
$lang['clear'] = '删除电子钱包账户';
$lang['update_success'] = '修改成功';
$lang['update_failure'] = '更新失败';
$lang['reject'] = '驳回';
$lang['no_operate'] = '不能操作';
$lang['all'] = '全选';
$lang['reapply'] = '请取消后重新申请提现';
$lang['status_title'] = '店铺状态';
$lang['status_0'] = '未激活';
$lang['status_1'] = '激活';
$lang['status_2'] = '休眠';
$lang['status_3'] = '冻结';
$lang['status_4'] = '公司店铺';
$lang['cash_withdrawal_list'] = '手动处理提现金额';
$lang['withdrawal_cash'] = '提现金额';
$lang['withdrawal_type'] = '提现类型';
$lang['withdrawal_account'] = '提现账号';
$lang['tps_manually'] = 'TPS手动';
$lang['tps_paid'] = '已手动支付';
$lang['tps_status_0'] = '未处理';
$lang['tps_status_1'] = '已处理';
$lang['tps_status_2'] = '处理中';
$lang['tps_status_3'] = '驳回';
$lang['tps_status_4'] = '已取消';
$lang['sure'] = '确定？';
$lang['sure_delivery'] = '你确认已经收到此订单的产品？';

$lang['lifecycle'] = '账户详情';
$lang['no_title'] = '请输入新闻标题';
$lang['no_source'] = '请输入新闻来源';
$lang['no_content'] = '请输入新闻内容';
$lang['no_img'] = '请上传新闻图片';
$lang['news_img'] = '上传新闻图片';
$lang['required'] = '必填项';
$lang['hot_news'] = '热门新闻';
$lang['sort'] = '排序高低';
$lang['sort_exist'] = '排序数字已存在';
$lang['display'] = '是否显示';
$lang['no_display'] = '不显示';
$lang['important_title'] = '重要';
$lang['need_display'] = '显示';
$lang['order_search'] = '升级订单查询';
$lang['upgrade_order'] = '产品套装订单';
$lang['upgrade_month_order'] = '升级月费订单';
$lang['month_fee_order'] = '充值月费订单';
$lang['txn_id'] = '交易号';
$lang['order_sn'] = '订单号';
$lang['payment'] = '付款方式';
$lang['pay_time'] = '付款時間';
$lang['unpaid'] = '未付款';
$lang['paid'] = '已付款';
$lang['usd_money'] = '美金';

$lang['news_lang']='所属语言';
$lang['news_cate']='所属分类';
$lang['news_ok']='添加分类';
$lang['news_cate_name']='新闻公告';
$lang['news_cate_need']='请输入分类名';

$lang['approve'] = '通过';
$lang['refuse'] = '未通过';
$lang['pending'] = '待审核';
$lang['refuse_reason'] = '驳回原因';
$lang['action'] = '操作';
$lang['check_card'] = '审核身份证';
$lang['check_card_id'] = '身份证号';
$lang['new_card_number'] = '新身份证号';
$lang['scan'] = '扫描件';
$lang['scan_back'] = '扫描件背面';
$lang['create_time'] = '创建时间';
$lang['check_status'] = '审核状态';

/** Add by Andy*/
$lang['check_admin'] = '审核人';
$lang['check_time'] = '审核时间';
$lang['relive_ban'] = '解除禁止';
$lang['relive_success'] = '解除成功';
$lang['relive_fail']    = '解除失败';

$lang['news_manage'] = '新闻管理';
$lang['bulletin_board_list'] = '公告栏列表';
$lang['add_bulletin_board'] = '添加公告栏';
$lang['add_news'] = '添加新闻';
$lang['title'] = '新闻标题';
$lang['source'] = '新闻来源';
$lang['content'] = '內容';
$lang['news_list'] = '新闻列表';
$lang['news_type'] = '类型';

$lang['tps138_admin'] = 'TPS管理后台';
$lang['user_management'] = '会员管理';
$lang['user_list'] = '会员列表';
$lang['user_info'] = '会员信息详情';
$lang['view_detail_info'] = '查看详情';
$lang['view_detail_score'] = '查看业绩';
$lang['status'] = '状态';
$lang['not_enable'] = '未激活';
$lang['enabled'] = '正常';
$lang['sleep'] = '休眠';
$lang['account_disable'] = '账户冻结';
$lang['company_keep'] = '公司预留账户';
$lang['month_fee_date'] = '月费日';
$lang['day'] = '日';
$lang['day_th'] = '号';
$lang['admin_account_manage'] = '账户管理';
$lang['admin_account_list'] = '后台账户列表';
$lang['role'] = '角色';
$lang['role_super'] = '管理员';
$lang['role_customer_service'] = '客服';
$lang['role_customer_service_lv1'] = '客服-lv1';
$lang['role_customer_service_lv2'] = '客服-lv2';
$lang['role_customer_service_manager'] = '客服经理';
$lang['operations_personnel'] = '运营';
$lang['role_storehouse_korea'] ='导单发货（韩国）';
$lang['role_storehouse_hongkong'] ='导单发货（香港）';
$lang['financial_officer'] = '财务';
$lang['account_disable'] = '冻结';
$lang['account_reenable'] = '解冻';
$lang['admin_user_account_disabled_hint'] = '输入密码错误次数过多，账号已被锁定。如需帮助，请联系组长经理。';
$lang['admin_user_login_pwd_error'] = '您的密码有误，错误超过3次将锁定账户';

$lang['account_disable_z'] = '正常冻结';
$lang['account_reenable_z'] = '正常解冻';
$lang['account_disable_m'] = '欠月费且冻结';
$lang['account_reenable_m'] = '欠月费且解冻';
$lang['resert_user_status'] = '恢复用户状态';
$lang['signouting'] = '退会中';
$lang['signouting_not_accounts'] = '退会中不能转账';
$lang['signouting_not_withdrawals'] = '退会中不能提现';
$lang['signouting_not_pay'] = '退会中不能现金池支付';

$lang['account_enable'] = '激活';
$lang['reset_password']='重置密码';
$lang['enable_store_level'] = '激活店铺等级';
$lang['upgrade_store_level'] = '升级店铺等级';
$lang['upgrade_user_manually'] = '手动升级会员等级';
$lang['user_id'] = '用户id';
$lang['please_sel_level'] = '请选择等级';
$lang['user_id_list_requied'] = '请填写会员id';
$lang['month_fee_or_user_rank_requied'] = '请选择月管理费等级或店铺等级';
$lang['submit_success'] = '提交成功';
$lang['upgrade_success_num'] = '%s 个会员已处理。';
$lang['upgrade_no_num'] = '无有效会员id。';
$lang['user_ids_notice'] = '多个id以逗号分隔';
$lang['no_permission'] = '没有权限';
$lang['wo_hao_tongbu_tishi'] = '填写用户ID，多个请用英文逗号分隔';

$lang['unbundling'] = '解绑';
$lang['unbundling_success'] = '解绑成功';
$lang['unbundling_fail'] = '解绑失败';
$lang['will_unbinding'] = '你将解除绑定';

/*佣金*/
$lang['commission_admin'] = '佣金管理';
$lang['commission_add_or_reduce'] = '佣金增减';
$lang['pls_input_amount'] = '请填写金额';
$lang['amount_condition'] = '金额必须为大于0的数值，如果是小数，保留小数点后面两位。';
$lang['why'] = '原因';
$lang['pls_input_reson'] = '请填写原因';
$lang['amount_limit'] = '金额限制';

/*佣金特别处理*/
$lang['commission_month_sum'] = '该月奖金总计';
$lang['commission_month'] = '会员月佣金统计';
$lang['commission_special_do'] = '奖金特别处理';
$lang['commission_special_check'] = '补发奖金计算';
$lang['add_in_qualified_list'] = '加入当月发奖列队';
$lang['pls_sel_comm_item'] = '请选择奖项';
$lang['pls_input_right_uid'] = '请输入正确的用户id。';
$lang['fix_user_commission'] = '补发会员奖金';
$lang['pls_sel_date'] = '请选择日期';
$lang['date_error_over_today'] = '结束日期不能大于今天';
$lang['month_date_error_over_today'] = '每月15号发放每月杰出店铺分红奖和团队组织分红奖，选择日期时请包含15号！';

/*奖金制度管理*/
$lang['incentive_system_management'] = "奖金制度管理";
$lang['reward_name'] = "奖励制度名称";
$lang['reward_content'] = "奖励制度名称";
$lang['reward_status'] = "显示状态";
$lang['reward_sort'] = "排序";
$lang['reward_op'] = "操作";
$lang['add_reward'] = "添加奖金制度";
$lang['add_show'] = "显示";
$lang['add_hide'] = "隐藏";
$lang['is_show_hide'] = "是否显示";


/*月费池管理*/
$lang['monthfee_pool_admin'] = '月费池管理';
$lang['monthfee_pool_add_or_reduce'] = '月费池增减';

//team
$lang['enter'] = '输入用户ID后，按回车键';
$lang['no_exist'] = '会员ID不存在';
$lang['join_matrix_time'] = '加入矩阵时间';
$lang['buy_product_time'] = '购买产品套装时间';

/*优惠券*/
$lang['coupon'] = '优惠券';

/*会员列表*/
$lang['search_notice'] = 'ID / 邮箱 / 姓名';

/*提现申请列表*/
$lang['generate_batch'] = '生成批次';
$lang['generate_time'] = '生成时间';
$lang['process_time'] = '处理时间';
$lang['generate_batch_num']='生成批处理文件号';
$lang['total_items']='总笔数';
$lang['total_money']='总金额';
$lang['payment_reason']='付款理由';

$lang['number_hao'] = '序号';
$lang['fee_num'] = '手续费($)';
$lang['the_actual_amount'] = '实际到账金额($)';
$lang['paypal_account_t'] = '支付宝账号';
$lang['paypal_status'] = '支付宝状态';
$lang['payee_name']='收款人姓名';
$lang['application_time'] = '申请时间';
$lang['money_num']='金额($)';
$lang['exchange_rate']="汇率";
$lang['criticism_num']="批次数";
$lang['batch_number']="批次号";
$lang['operation']="操作";
$lang['export_EXCEL']="导出EXCEL";
$lang['process_result']='处理结果';
$lang['result_ok']='成功';
$lang['result_false']='失败';
$lang['unselected']='未选择提现申请';
$lang['unselected_reason']='未选择理由';
$lang['batch_error']='您的选项中，已有批次存在，请重新选择';
$lang['reject_confirm']='您将驳回该笔提现，请填写驳回原因！';
$lang['cause']='原因';
$lang['view_batch']='查看批次';
$lang['alipay_withdraw'] = '支付宝提现列表';
$lang['bank_withdraw'] = '提现列表';
$lang['payment_interface'] = '代付接口';
$lang['payment_type_1'] = '银联';
$lang['payment_type_2'] = '财付通';
$lang['payment_type_3'] = '快付通';
$lang['payment_type_4'] = '汇元支付';
$lang['submit_pay_tyep'] = '提交代付付款';
//$lang['batch_list'] = '查看批次';
$lang['batch_xq'] = '批次详情';
$lang['process']='查看';
$lang['cancel_batch']='取消批次';
$lang['submit_alipay']='提交支付宝';
$lang['total_items_ts']='当前页笔数：%s 笔';
$lang['total_money_ts']='提现总金额：$%s';
$lang['fee_num_ts']='手续费总额：$%s';
$lang['fee_num_ts2']='手续费';
$lang['the_actual_amount_ts']='实际打款总金额：$%s';
$lang['please_choose']='请选择';
$lang['choose1']='货款';
$lang['choose2']='运费';
$lang['choose3']='饭钱';
$lang['choose4']='销售佣金';
$lang['status_n1']='待处理';
$lang['status_n2']='处理中';
$lang['status_n3']='处理完成';
$lang['cancel_confirm']='取消批次提示';
$lang['double_confirm']='确定取消这个批次吗?';


/*清空用户账户信息*/
$lang['clear_member_account_info'] = '转让用户账户';
$lang['new_email'] = '新的Email';
$lang['new_password_note'] = '该账户的初始密码是：%s';

//矩阵管理
$lang['matrix_manage']='矩阵管理';
$lang['change_2x5_position']='2x5矩阵位置替换';

$lang['matrix_alert1']='User ID 挂靠到 Parent ID 树下';
$lang['matrix_alert2']='UserID1 与 UserID2 交换位置';

$lang['card_notice'] = 'ID / 姓名';
$lang['total_money'] = '总金额';

/***月费转现金池***/
$lang['month_fee_to_amount'] = '月费转现金池';
$lang['user_id'] = '用户ID';
$lang['month_fee'] = '月费池';
$lang['amount_'] = '现金池';
$lang['to'] = '转入';
$lang['confirm'] = '确定';
$lang['user_not_exits'] = 'ID 不存在';
$lang['cash_not_null'] = '金额不能为空';
$lang['max_cash'] = '最大可转入：';
$lang['id_not_null'] = 'ID 不能为空';
$lang['month_fee_error'] = '请输入大于0的金额(最多两位小数)';
$lang['not_bigger'] = '不能超过限额';
$lang['transfer_to_success'] = '转入成功';
$lang['transfer_to_fail'] = '转入失败';


/**2x5佣金补偿**/
$lang['commission_2x5'] = '2x5佣金管理';
$lang['please_input_user_id'] = '收取佣金的ID';
$lang['please_input_pay_user_id'] = '发放佣金的ID';
$lang['confirm'] = '确定';

$lang['select_order_repair_date'] = '选择补单年月';
$lang['select_comm_order_type']   = '选择佣金补单类型';
$lang['pls_select_order_year']    = '请选择补单年月';
$lang['pls_select_comm_order_type'] = '请选择佣金补单类型';
$lang['add_comm_success'] = '佣金补单成功';
$lang['add_comm_fail'] = '不需要补单';
$lang['cur_month_add_comm_ban'] = '此月份不能补单';
$lang['comm_tips'] = '提示';
$lang['you_will_add_a_comm'] = '你将添加一条佣金补单:';
$lang['add_comm_year_month'] = '补单年月';
$lang['comm_order_type'] = '佣金补单类型';
$lang['need_add_comm_mount'] = '需补单金额';

$lang['alert_commission_compensation_ok'] = '补偿佣金成功,1个会员受影响';
$lang['alert_commission_compensation_fail'] = '0个会员受影响';
$lang['alert_commission_compensation_noExits'] = '发放佣金的ID还未进入排序';
$lang['alert_commission_compensation_notNull'] = 'ID 不能为空';

$lang['commission_forget'] = '佣金漏发';
$lang['commission_error'] = '佣金错发';
$lang['commission_repeat'] = '佣金重复';

$lang['reason'] = '-----原因-----';

$lang['see_user_back_office'] = '查看用户后台';
$lang['score_month'] = '业绩月份';
$lang['store_sale_amount'] = '店铺销售额';
$lang['current_store_sale_total_amount'] = '当前店铺总销售额';

/* 产品管理相关  */
$lang['goods_doba_import']='DOBA产品csv导入';
$lang['brand_exists']='品牌名称已存在!';
$lang['cate_exist']='分类名称已存在 !';
$lang['goods_manage']='产品管理';
$lang['add_category']='添加分类';
$lang['category_list']='分类管理';
$lang['add_goods']='添加商品';
$lang['goods_list']='商品列表';
$lang['ads_list']='广告管理';
$lang['ads_add']='增加广告';
$lang['edit_ads']='编辑广告';
$lang['goods_group_list']='商品套餐列表';
$lang['edit_category']='编辑分类';
$lang['edit_goods']='编辑商品';

$lang['label_ads_img']='广告图片';
$lang['label_ads_sort']='显示顺序';
$lang['label_ads_url']='广告链接';
$lang['label_ads_status']='显示状态';
$lang['label_ads_lang']='广告语种';
$lang['label_ads_location']='显示位置';

$lang['label_goods_group_add']='添加套餐';
$lang['label_goods_group_edit']='编辑套餐';
$lang['label_goods_group_search']='搜索产品';
$lang['label_goods_group_ok']='搜索';
$lang['label_goods_group_keywords']='商品关键词';
$lang['label_goods_group_num']='数量';
$lang['label_goods_group_id']='套餐ID';
$lang['label_goods_group_content']='套餐内容';

$lang['label_brand_list']='品牌管理';
$lang['label_brand_add']='添加品牌';
$lang['label_brand_name']='品牌名称';
$lang['label_brand_id']='品牌ID';
$lang['label_language']='所属语言';
$lang['label_language_all']='所有语种';
$lang['label_brand_list_m']='品牌列表';

$lang['label_cate_parent']='上级分类';
$lang['label_cate_name']='分类名称';
$lang['label_cate_sn']='分类SN';
$lang['label_cate_desc']='分类描述';
$lang['label_cate_icon']='分类ICON';
$lang['label_cate_sort']='分类排序';
$lang['label_cate_meta_title']='SEO标题';
$lang['label_cate_meta_keywords']='SEO关键字';
$lang['label_cate_meta_desc']='SEO描述';
$lang['label_cate_top']='顶级分类';

$lang['label_goods_display_state']='显示该语种';
$lang['label_goods_cate']='所属分类';
$lang['label_goods_shipper']='发货商';
$lang['label_goods_shipper_sel']='所有发货商';
$lang['label_goods_brand']='所属品牌';
$lang['label_goods_effect']='所属风格';
$lang['label_goods_name']='商品名称';
$lang['label_goods_name_cn']='商品名称（中文）';
$lang['label_goods_main_sn']='商品主SKU';
$lang['label_goods_img']='商品主图(250*250)';
$lang['label_is_change_width']='不缩放';
$lang['label_goods_sku']='创建子SKU';
$lang['label_goods_img_gallery']='商品相册图';
$lang['label_goods_img_detail']='商品详情图';
$lang['label_goods_stock']='库存数量';
$lang['label_goods_warn']='预警数量';
$lang['label_goods_weight']='产品重量（kg）';
$lang['label_goods_bulk']='体积';
$lang['label_goods_purchase_price']='采购价(usd)';
$lang['label_goods_market_price']='市场价(usd)';
$lang['label_goods_shop_price']='销售价(usd)';
$lang['label_goods_is_promote']='是否促销';
$lang['label_goods_promote_start']='促销开始时间';
$lang['label_goods_promote_end']='促销结束时间';
$lang['label_goods_promote_price']='促销价';
$lang['label_goods_sale']='上架';
$lang['label_goods_unsale']='下架';
$lang['label_goods_looking']='审核中';
$lang['label_goods_delete']='删除';
$lang['label_goods_best']='推荐';
$lang['label_goods_new']='新品';
$lang['label_goods_hot']='热卖';
$lang['label_goods_home']='首页展示';
$lang['label_goods_ship']='包邮';
$lang['label_goods_24']='24小时发货';
$lang['label_goods_voucher']='可代金卷购买';
$lang['label_goods_alone_sale']='单品';
$lang['label_goods_group_sale']='套装';
$lang['label_goods_group_sale_upgrade']='升级套装';
$lang['label_goods_for_upgrade']='用于升级';
$lang['label_goods_group_sale_ids']='套装ID';
$lang['label_goods_note']='产品备注（附属说明）';
$lang['label_goods_note1']='红字特殊备注';
$lang['label_goods_store']='所属仓库';
$lang['label_goods_add_user']='添加者';
$lang['label_goods_update_user']='更新者';
$lang['label_goods_add_time']='添加时间';
$lang['label_goods_update_time']='更新时间';
$lang['label_goods_sort']='排序';
$lang['label_goods_desc']='详情描述(文字描述)';
$lang['label_goods_desc_pic']='详情描述(长图片)';
$lang['label_goods_sale_country']='销售国家';
$lang['label_yes']='是';
$lang['label_no']='否';
$lang['label_sub_sn']='商品SKU';
$lang['label_color']='商品颜色';
$lang['label_size']='商品尺寸';
$lang['label_customer']='自定义';
$lang['label_sel_store']='所有仓库';
$lang['label_sel_store_third']='供应商仓库';
$lang['label_sel_cate']='所有分类';
$lang['label_sel_status']='所有状态';
$lang['label_sel_supplier']='所有供应商';
$lang['label_sel']='- 请选择 -';
$lang['label_flag']='产地';
$lang['label_goods_gift']='赠品(多个sku英文逗号分隔)';

$lang['label_new'] = '新品推荐';
$lang['label_comment'] = '热卖推荐';
$lang['label_supplier'] = '供应商管理';
$lang['label_supplier_add'] = '增加供应商';
$lang['label_supplier_edit'] = '编辑供应商';
$lang['label_supplier_name'] = '公司名';
$lang['label_supplier_user'] = '联系人';
$lang['label_supplier_tel'] = '手机';
$lang['label_supplier_phone'] = '电话';
$lang['label_supplier_qq'] = 'QQ';
$lang['label_supplier_ww'] = '旺旺';
$lang['label_supplier_addr'] = '地址';
$lang['label_supplier_email'] = 'Email';
$lang['label_supplier_link'] = '公司网站';
$lang['label_supplier_shipping'] = '自发货供应商';
$lang['info_supplier_exist'] = '该供应商已经存在，请不要重复添加';
$lang['info_supplier_username_exist'] = '该供应商用户名已经存在，请不要重复添加';
$lang['label_supplier_n'] = '供应商';
$lang['label_supplier_username'] = '用户名';
$lang['label_supplier_password'] = '密码';

$lang['label_cn'] = '中国';
$lang['label_us'] = '美国';
$lang['label_hk']='香港';
$lang['label_ne']='新西兰';
$lang['label_ho']='荷兰';
$lang['label_as']='澳大利亚';
$lang['label_fr']='法国';
$lang['label_ko']='韩国';
$lang['label_tw']='台湾';
$lang['label_jp']='日本';
$lang['label_sp']='西班牙';
$lang['label_ph']='菲律宾';
$lang['label_chi']='智利';
$lang['label_ge']='德国';
$lang['label_ca']='加拿大';
$lang['label_fi']='芬兰';
$lang['label_sg']='新加坡';

$lang['info_success']='数据提交成功';
$lang['info_failed']='数据提交失败，请认真填写所有带  * 数据';
$lang['info_unvalid_request']='非法请求';
$lang['info_error']='操作失败，请重试';
$lang['info_price_err']='销售价不能大于市场价';
$lang['info_price_err1']='销售价必须大于(10 / 9 * 采购价)';
$lang['info_err_weight']='产品重量不合法';
$lang['info_err_purchase_price']='采购价不合法';

$lang['reset_user_pwd']='重置用户密码';
$lang['confirm_user_id']='请确认用户ID';
$lang['id_not_identical']='两次输入的ID必须相同';
$lang['this_user_name_is']='该用户的姓名是：';
$lang['reset_pwd_success_admin']='重置密码成功,初始密码为:';

/* 交易管理 */
$lang['admin_trade_title'] = '交易管理';
/* 问题反馈  */
$lang['label_feedback'] = '问题反馈';
$lang['label_feedback_email'] = 'Email';
$lang['label_feedback_userid'] = '用户ID';
$lang['label_feedback_content'] = '内容';
$lang['label_feedback_date'] = '提交时间';
$lang['label_feedback_state'] = '状态';
$lang['label_feedback_state_yes'] = '已处理';
$lang['label_feedback_state_no'] = '未处理';
$lang['label_feedback_change_state'] = '隐藏';
$lang['label_server'] = '售后服务';

/* 订单管理 */
$lang['admin_trade_order'] = '订单管理';
$lang['admin_trade_order_attach'] = '主订单 id';
$lang['admin_order_info'] = '订单信息';
$lang['admin_order_info_basic'] = '基本信息';
$lang['admin_order_id'] = '订单 id';
$lang['pc_order_id'] = '订单 id ';
$lang['admin_order_prop'] = '订单类型';
$lang['admin_order_prop_normal'] = '普通订单';
$lang['admin_order_prop_component'] = '包含子订单';
$lang['admin_order_prop_merge'] = '合单订单';
$lang['admin_order_uid'] = '顾客 id';
$lang['admin_order_customer'] = '顾客';
$lang['admin_order_store_id'] = '店铺 id';
$lang['admin_order_consignee'] = '收货人';
$lang['admin_order_phone'] = '联系电话';
$lang['admin_order_deliver_addr'] = '收货地址';
$lang['admin_order_zip_code'] = '邮政编码';
$lang['admin_order_customs_clearance'] = '海关报关号';
$lang['admin_order_deliver_time'] = '送货时间';
$lang['admin_order_expect_deliver_date'] = '预计发货日期';
$lang['admin_order_expect_deliver_date_invalid'] = '预计发货日期不能早于当前日期';
$lang['admin_order_info_goods'] = '商品信息';
$lang['admin_order_goods_list'] = '商品列表';
$lang['admin_order_goods_sn'] = 'sku';
$lang['admin_order_goods_name'] = '名称';
$lang['admin_order_goods_quantity'] = '数量';
$lang['admin_order_remark'] = '备注';
$lang['admin_order_info_pay'] = '支付信息';
$lang['admin_order_receipt'] = '是否需要收据';
$lang['admin_order_receipt_0'] = '不需要';
$lang['admin_order_receipt_1'] = '需要';
$lang['admin_order_currency'] = '货币';
$lang['admin_order_rate'] = '汇率';
$lang['admin_order_goods_amount'] = '商品总计';
$lang['admin_order_deliver_fee'] = '运费';
$lang['admin_order_amount'] = '实付金额';
$lang['admin_order_amount_usd'] = '实付金额（美元）';
$lang['admin_order_profit_usd'] = '利润（美元）';
$lang['admin_order_payment'] = '支付方式';
$lang['admin_order_payment_unpay'] = '未支付';
$lang['admin_order_payment_group'] = '预付款';
$lang['admin_order_payment_coupon'] = '代品券换购';
$lang['admin_order_payment_alipay'] = '支付宝';
$lang['admin_order_payment_unionpay'] = '银联支付';
$lang['admin_order_payment_paypal'] = 'PayPal';
$lang['admin_order_payment_ewallet'] = 'eWallet';
$lang['admin_order_payment_yspay'] = '银盛支付';
$lang['admin_order_payment_amount'] = '余额支付';
$lang['admin_order_pay_time'] = '支付时间';
$lang['admin_order_notify_num'] = '接口回调次数';
$lang['admin_order_pay_txn_id'] = '第三方交易号';
$lang['admin_order_info_status'] = '状态信息';
$lang['admin_order_info_create_time'] = '创建时间';
$lang['admin_order_info_freight'] = '收货信息';
$lang['admin_order_info_deliver_time'] = '发货时间';
$lang['admin_order_info_receive_time'] = '收货时间';
$lang['admin_order_info_update_time'] = '更新时间';
$lang['admin_order_status'] = '订单状态';
$lang['admin_order_status_all'] = '所有状态';
$lang['admin_order_status_init'] = '正在发货中';
$lang['admin_order_status_checkout'] = '等待付款';
$lang['admin_order_status_paied'] = '等待发货';
$lang['admin_order_status_delivered'] = '等待收货';
$lang['admin_order_status_arrival'] = '等待评价';
$lang['admin_order_status_finish'] = '已完成';
$lang['admin_order_status_returning'] = '退货中';
$lang['admin_order_status_refund'] = '退货完成';
$lang['admin_order_refund'] = '退货';
$lang['admin_order_status_cancel'] = '订单取消';
$lang['admin_order_status_component'] = '已拆分';
$lang['admin_order_status_doba_exception'] = 'DOBA异常订单';
$lang['admin_order_operate'] = '操作';
$lang['admin_order_operate_deliver'] = '确认发货';
$lang['admin_order_confirm_cancel'] = '一旦取消订单状态将不可逆转，是否确定取消订单？';
$lang['admin_order_cancel_confirm'] = '订单取消确认';
$lang['admin_order_cancel'] = '取消';
$lang['admin_order_deliver_box_title'] = '填写发货信息';
$lang['admin_order_deliver_box_id'] = '快递信息';
$lang['admin_order_tracking_num'] = '运单号';
$lang['admin_order_remark_system'] = '系统备注';
$lang['admin_order_customer_remark'] = '顾客可见备注';
$lang['admin_order_customer_remark_add'] = "添加顾客可见备注";
$lang['admin_order_system_remark'] = '后台系统可见备注';
$lang['admin_order_system_remark_add'] = "添加后台系统可见备注";
$lang['admin_order_remark_operator'] = '后台操作人员';
$lang['admin_order_remark_create_time'] = '创建时间';
$lang['admin_order_expect_deliver_date'] = '预计发货时间';
$lang['admin_order_shipping_print'] = '打印快递单';
$lang['set_except_group']='设置免套餐会员';
$lang['id_format_is_not_correct']='ID格式不正确';
$lang['admin_doba_order_fix'] = '手动获取doba信息';
$lang['admin_doba_order_id'] = 'Doba ID';
$lang['admin_doba_order_request'] = '获取信息';
$lang['admin_doba_order_request_succ'] = '获取信息成功';

/* 订单修复 */
$lang['admin_trade_repair'] = '订单修复';
$lang['admin_trade_repair_modify'] = '信息修改';
$lang['admin_trade_repair_component'] = '手动拆单';
$lang['admin_trade_repair_rollback'] = '状态回滚';
$lang['admin_trade_repair_cancel_rollback'] = '[取消状态]还原';
$lang['admin_trade_repair_addnumber'] = '手动添加交易号';
$lang['admin_trade_repair_number'] = '交易号';
$lang['orderid_not_null'] = '订单号不能为空';
$lang['txnid_not_null'] = '交易号不能为空';
$lang['orderid_not_exits'] = '该订单号无效';
$lang['orderid_ture'] = '添加成功';

$lang['orderid_default_config'] = '默认配置';
$lang['orderid_users_config'] = '参数配置';
$lang['orderid_freight_info_cv'] = '运单号覆盖';
$lang['orderid_freight_info_null'] = '运单号清空';

/* 导入订单 */
$lang['admin_trade_order_import'] = '导入订单数据';

//重置选购套装
$lang['not_find_this_order']='没有发现该用户的选购订单';
$lang['not_find_this_upgrade_order']='没有发现该用户的升级订单';
$lang['this_order_not_reset']='只有待发货的订单才可以重置';
$lang['reset_choose_group_success']='订单重置成功';
$lang['this_user_not_choose']='该用户还没选购,不需要重置';
$lang['this_user_upgrade_not_reset']='该用户在换购后进行过升级店铺操作,不能重置';
$lang['reset_choose_group']='重置选购订单';
$lang['reset_upgrade_group']='重置升级订单';
$lang['reset_group']='重置订单';
$lang['reset_type']='重置类型';
$lang['you_use_coupons_not_can_reset']='代品券不足,不能重置';
$lang['order_a_timeout_not_can_reset']='订单已经超过三天,不能重置';
$lang['this_user_have_more_than_once_upgrade_record']="该用户为阶段性升级,不能重置订单";

/*导入第三方订单*/
$lang['import_third_part_orders'] = '导入第三方订单';

/* 供应商系统 相关 */
$lang['sys_supplier_title']='TPS供应商管理系统';
$lang['coupons_manage']='代品券管理';
$lang['coupons_add_or_reduce']='代品券增减';
$lang['voucher']='代品券';
$lang['voucher_not_null']='请输入代品券金额';
$lang['remark_not_null']='请输入原因';
$lang['please_enter_correct_voucher']='请输入正确的代品券金额';
$lang['voucher_value']='代品券金额';

$lang['order_not_exits']='该订单不存在';
$lang['order_id_not_null']='订单号不能为空';
$lang['this_order_is_choose_order']='该订单是选购订单,顾客id是:';
$lang['this_order_is_upgrade_order']='该订单是升级订单,顾客id是:';
$lang['this_order_is_basic_order']='该订单是普通订单,不能重置';
$lang['check_order_type']='检测订单类型';

$lang['refund'] = '退款';
$lang['no_refund'] = '不退款';
$lang['no_cancel_order'] = '订单已被导出，取消时需要跟运营部确认订单是否发货,谨慎操作！！！';
$lang['refund_coupons']='退还代品券';
$lang['only_cancel']='仅取消';
$lang['order_refund'] = '订单退款';

/** paypal 查询 */
$lang['admin_paypal_failure_search'] = '贝宝退款/撤销订单查询';
$lang['admin_paypal_failure_list'] = '贝宝退款/撤销订单列表';

/* 运营系统 - 仓库管理 */
$lang['admin_oper_storehouse_ALL'] = "所有仓库";
$lang['admin_oper_shipper_ALL'] = "所有发货商";
$lang['admin_oper_storehouse_CNSZ'] = "中国深圳仓库";
$lang['admin_oper_storehouse_CNHK'] = "中国香港仓库";
$lang['admin_oper_storehouse_USATL'] = "美国亚特兰大仓库";
$lang['admin_oper_storehouse_USANOPAL'] = "美国NOPAL仓库";
$lang['admin_oper_storehouse_USAJBB'] = "美国JBB仓库";
$lang['admin_oper_storehouse_USANI'] = "美国Grace of Graviola仓库";
$lang['admin_oper_storehouse_USASAMTJ'] = "美国Epic Sam LLC仓库";
$lang['admin_oper_storehouse_USAIE'] = "美国 Insight Eye 仓库";

$lang['admin_oper_storehouse_KRSL'] = "韩国首尔仓库";
$lang['admin_oper_storehouse_KRKK'] = "韩国首尔-硫磺葡萄酒皂仓库";
$lang['admin_oper_storehouse_KRCPC'] = "韩国首尔-99美白仓库";
$lang['admin_oper_storehouse_KRFHC'] = "韩国西兰花粉 + 康复天使仓库";
$lang['admin_oper_storehouse_KRSSL'] = "韩国减肥粉仓库";
$lang['admin_oper_storehouse_KRWM'] = "韩国净水器仓库";
$lang['admin_oper_storehouse_KRFLX'] = "韩国Florex仓库";
$lang['admin_oper_storehouse_KRHCL'] = "韩国牙膏，护膝仓库";
$lang['admin_oper_storehouse_KRPS'] = "韩国Ssophyya仓库";
$lang['admin_oper_storehouse_KRG'] = "韩国 Ginseng 仓库";
$lang['admin_oper_storehouse_KRDC'] = "韩国 Dr. Cell 仓库";
$lang['admin_oper_storehouse_KRSSD'] = "韩国 Seng Seng Dan 仓库";
$lang['admin_oper_storehouse_KRKSCG'] = "韩国东方医学粉+KAMIJOA染发膏+Si-Lite牙膏仓库";
$lang['admin_oper_storehouse_KRCG'] = "韩国染发+Cheongin gold仓库";

$lang['admin_oper_storehouse_CNXY'] = "中国熙媛仓库（供应商）";
$lang['admin_oper_storehouse_CNZYP'] = "中国尊誉品-大米仓库（供应商）";
$lang['admin_oper_storehouse_CNGWM'] = "中国国威铭-红酒仓库（供应商）";
$lang['admin_oper_storehouse_CNLT'] = "中国绿糖-奶瓶仓库（供应商）";
$lang['admin_oper_storehouse_CNFJ'] = "中国富嘉-公仔,抱枕仓库（供应商）";
$lang['admin_oper_storehouse_CNWD'] = "中国问鼎-西北特产仓库（供应商）";
$lang['admin_oper_storehouse_CNMY'] = "中国航远-红酒仓库（供应商）";
$lang['admin_oper_storehouse_CNFM'] = "中国福麦五谷仓库（供应商）";
$lang['admin_oper_storehouse_CNKSD'] = "中国凯盛达仓库（供应商）";
$lang['admin_oper_storehouse_CNJGH'] = "中国巾帼汇-茅台酒仓库（供应商）";
$lang['admin_oper_storehouse_CNTFT'] = "中国天福堂仓库（供应商）";
$lang['admin_oper_storehouse_CNJGH1'] = "中国巾帼汇-玛咖仓库（供应商）";
$lang['admin_oper_storehouse_CNFFMY'] = "中国黑龙江五发米业仓库（供应商）";
$lang['admin_oper_storehouse_CNYG'] = "中国北京阳光庄苑仓库（供应商）";
$lang['admin_oper_storehouse_CNYHM'] = "中国深圳市亿海铭仓库（供应商）";
$lang['admin_oper_storehouse_CNBDS'] = "中国深圳市班德施-咖啡酵素仓库（供应商）";
$lang['admin_oper_storehouse_CNZSH'] = "中国宜兴紫砂壶仓库（供应商）";
$lang['admin_oper_storehouse_CNYP'] = "中国雅培仓库（供应商）";
$lang['admin_oper_storehouse_CNJJ'] = "中国金绛食品仓库（供应商）";
$lang['admin_oper_storehouse_CNSL'] = "中国深圳市狮龙仓库（供应商）";
$lang['admin_oper_storehouse_CNWLL'] = "中国威利来有限公司仓库（供应商）";
$lang['admin_oper_storehouse_CNJMD'] = "中国威海姜名都仓库（供应商）";
$lang['admin_oper_storehouse_CNYCK'] = "中国钰诚康仓库（供应商）";
$lang['admin_oper_storehouse_CNLHY'] = "中国绿禾源仓库（供应商）";

$lang['admin_supplier_store_code'] = '仓库对应供应商列表';
$lang['admin_supplier'] = '供应商';
$lang['admin_store_code'] = '仓库';

/** 商品名称 */
$lang['admin_mini_water'] = '迷你装净水器';
$lang['admin_family_water'] = '家庭装净水器';
$lang['admin_powder'] = '减肥营养粉';
$lang['admin_flx'] = '植物软胶囊';

/* 区域 */
$lang['zone_area_chn'] = "中国大陆";
$lang['zone_area_usa_other'] = "美国及其他地区";
$lang['zone_area_kor'] = "韩国";
$lang['zone_area_hkg_mac_twn_asean'] = "港澳台及东南亚";


/**拆分订单***/
$lang['split_order'] = "拆分订单";
$lang['item_status_exception'] = '该订单的子订单状态异常,不能重新拆单';
$lang['this_order_not_need_split_order']='该订单不需要拆单(商品只对应到一个仓库)';
$lang['only_wait_delivery_can_split_order'] = "只有待发货的商品才可以拆单";
$lang['the_split_order_success'] = '拆单成功,拆分的子订单如下：';

$lang['fill_in_frozen_remark'] = '请输入冻结原因';
$lang['fill_in_frozen_remark_2'] = '请输入冻结原因，如订单被导出请写明与哪位运营同事确认可冻结';
$lang['lock_order_not_can_freeze'] = '锁定的订单不能冻结';
$lang['freeze_success'] = "冻结成功";
$lang['transaction_rollback'] = "事务回滚了";
$lang['order_remove_frozen'] = '解除冻结';
$lang['remove_frozen_success'] = '解除成功';
$lang['confirm_remove_freeze'] = '确认解除冻结?';

//订单操作log
$lang['trade_order_logs'] = '订单操作日志';
$lang['all_oper_code'] = '所有类型';
$lang['order_log_oper_create'] = '订单创建';
$lang['order_log_oper_modify'] = '订单修改';
$lang['order_log_oper_export'] = '订单导出';
$lang['order_log_oper_diliver'] = '订单发货';
$lang['order_log_oper_reset'] = '订单重置';
$lang['order_log_oper_rollback'] = '订单回滚';
$lang['order_log_oper_cancel'] = '订单取消';
$lang['order_log_oper_frozen'] = '订单冻结';
$lang['order_log_oper_unfrozen'] = '订单解除冻结';
$lang['order_log_oper_addr_edit'] = '订单地址修改';
$lang['order_log_oper_erpmodify'] = '订单信息修改';
$lang['order_log_oper_suit'] = '产品套装的订单状态';
$lang['order_log_oper_recovery'] = '订单恢复';
$lang['order_log_oper_exchange'] = '订单换货';

// 会员换货
$lang['uc_exchange_order_logs'] = '会员换货操作日志';

$lang['operator_id'] = '操作人ID';
$lang['update_time'] = '操作时间';

$lang['load_more'] = '点击加载更多';

$lang['load_finish'] = '没有更多了';
$lang['this_user_not_sort'] = '该用户还未进入排序';
$lang['the_number_of_matrix'] = '矩阵人数:';

//后台执行sql入口
$lang['execute_sql'] = '执行SQL语句';
$lang['please_enter_sql'] = '请输入SQL语句,多条语句用分号分隔';
$lang['please_enter_remark'] = '请输入详细的备注';

//跨区运费
$lang['international_freight'] = '商品跨区运费';
$lang['goods_sku'] = '商品SKU';
$lang['find'] = '查询';
$lang['please_input_freight_usd'] = '请输入运费,单位(美元),不允许购买的地区请设置成-1';
$lang['please_input_right_sku'] = '请输入正确的SKU';
$lang['freight_must_is_number'] = '运费必须为数字';

$lang['not_find_this_goods_name'] = '未发现该商品名称';

//^^^^
$lang['all_country'] = "所有地区";
$lang['sql_source'] = 'SQL源码';
$lang['system_setting'] = '系统管理';

$lang['all_status'] = '全部状态';
$lang['awaiting_processing'] = '待处理';
$lang['has_been_completed'] = '完成';
$lang['submit_email'] = '提交者';
$lang['audit_email'] = '审核者';
$lang['audit_time'] = '审核时间';
$lang['refuse'] = '驳回';
$lang['refuse_reason'] = '驳回原因';
$lang['confirm_execute'] = '确定执行吗?';

//月费池转现金池日志列表
$lang['old_month_fee_pool'] = '转之前的月费池';
$lang['new_month_fee_pool'] = '转之后的月费池';
$lang['cash'] = '转入金额';


$lang['product_freight_delete'] = '删除商品跨区运费';
$lang['label_country']='国家';
$lang['not_find_this_product_freight'] = '未发现该纪录';
$lang['product_freight_not_be'] = '运费必须是大于等于0的整数';
$lang['delete_success'] = '删除成功';
$lang['delete_failure'] = '删除失败';
$lang['is_delete'] = '一旦删除不可逆转，是否确认删除该运费？';
$lang['delete_ok'] = '删除运费确认';


/*后台文件管理*/
$lang['admin_ads_file_manage'] = '文件管理';
$lang['admin_file_type'] = '文件类型';
$lang['admin_file_announcement'] = '公告文件';
$lang['admin_file_regime']       = '制度';
$lang['admin_commission_explain'] = '佣金说明';
$lang['admin_file_is_show'] = '是否显示';
$lang['file_is_show'] = '显示';
$lang['file_is_hide'] = '隐藏';
$lang['admin_file_name'] = '文件名称';
$lang['admin_ads_file_add'] = '添加文件';
$lang['admin_ads_file_modify'] = '修改文件';
$lang['admin_file_empty']  = '文件不能为空';
$lang['admin_file_name_empty'] = '文件名不能为空';
$lang['admin_file_limit_10m']  = '大小超过10M';
$lang['admin_file_name_limit_100'] = '文件长度超过100';
$lang['admin_file_upload_fail'] = '上传失败';
$lang['admin_file_type_empty'] = '文件类型不能为空';
$lang['admin_file_delete_success'] = '删除成功';
$lang['admin_file_delete_fail'] = '删除失败';
$lang['admin_file_update_success'] = '修改成功';
$lang['admin_file_update_fail']    = '修改失败';
$lang['admin_file_add_success']    = '添加成功';
$lang['admin_file_add_fail']       = '添加失败';
$lang['delete_admin_file'] = '删除文件';
$lang['admin_file_modify'] = '编辑';
$lang['admin_file_submit_error'] = '请勿重复提交';
$lang['admin_file_area'] = '区&nbsp;&nbsp;域';
$lang['admin_file_area_empty'] = '区域不能为空';

/*知识库管理*/
$lang['admin_knowledge'] = '客服知识库';
$lang['admin_knowledge_manage'] = '知识库管理';
$lang['admin_knowledge_cate_manage'] = '知识库分类管理';
$lang['admin_knowledge_title'] = '标题';
$lang['admin_knowledge_cate'] = '知识类型';
$lang['admin_knowledge_add'] = '知识库新增';
$lang['admin_knowledge_cate_add'] = '知识库类型新增';
$lang['edit'] = '编辑';
$lang['success'] = '成功';
$lang['modify_user'] = '更新人';
$lang['admin_knowledge_success'] = '操作成功，确定转向列表页，取消则继续操作';


/*会员个人中心文件下载*/
$lang['file_download'] = '文件下载';


/** 客服中心 start*/
$lang['tickets_center'] = '客服中心';
$lang['history_tickets'] = '历史工单';
$lang['my_tickets'] = '我的工单';
$lang['all_tickets'] = '全部工单';
$lang['add_tickets']= '新建工单';
$lang['unassigned_tickets'] = '未分配工单';
$lang['unassigned_tickets_count'] = '未分配';
$lang['unprocessed_tickets_count'] = '未处理';
$lang['tickets_id'] = '工单号';
$lang['tickets_sender'] = '发件人';
$lang['tickets_closed_can_not_reply'] = '工单已关闭，不能回复!';
$lang['tickets_reply'] = '工单回复';
$lang['org_tickets_info'] = '原始工单信息';
$lang['customer'] = '客服';
$lang['member'] = '会员';
$lang['member_id'] = '会员ID';
$lang['tickets_language'] = '语言';
$lang['pls_t_uid'] = '请输入会员ID';
$lang['pls_t_correct_ID'] = '请输入正确的会员ID';
$lang['tickets_score_num'] = '评分数';

$lang['tickets_title'] = '工单标题';
$lang['assign_to_me'] = '标记在我名下';
$lang['tickets_language'] = '语言';
$lang['assign_success'] = '标记成功';
$lang['assign_fail'] = '标记失败';
$lang['view_ticket_detail'] = '查看详情';
$lang['view_and_change'] = '查看 / 修改';
$lang['close_tickets'] = '关闭工单';
$lang['view_tickets_log'] = '查看日志';
$lang['confirm_close_tickets'] = '你确定要关闭工单吗？';
$lang['close_tickets_success'] = '工单已关闭';
$lang['close_tickets_fail'] = '工单关闭失败';
$lang['tickets_content'] = '工单问题描述';
$lang['picture_not_exist'] = '图片不存在';
$lang['tickets_no_exist'] = '抱歉，工单不存在';
$lang['attach_no_exist'] = '抱歉，附件不存在';
$lang['log_no_exist'] = '抱歉，没有该工单日志';
$lang['log_info'] = '日志详情';
$lang['tickets_take_time'] = '工单历时';
$lang['day'] = '日';
$lang['hour'] = '小时';
$lang['minute'] = '分钟';
$lang['second'] = '秒';
$lang['tickets_handler'] = '操作者';
$lang['modified_type'] = '修改的类型';
$lang['old_data'] = '旧值';
$lang['new_data'] = '新值';
$lang['add_new_tickets'] = '新建工单';
$lang['new_tickets'] = '新工单';
$lang['new_msg'] = '新消息';

$lang['t_template_name'] = '模板名称';
$lang['t_template_content'] = '模板内容';
$lang['t_template_type'] = '模板类型';
$lang['tickets_template']= '信息模板';
$lang['pls_t_t_name'] = '请输入模板名称';
$lang['pls_t_t_content'] = '请输入模板内容';
$lang['add_tickets_template'] = '添加自定义模板';
$lang['is_public']='是否公开';
$lang['template_author'] = '作者';
$lang['template_name'] = '模板名称';
$lang['template_is_public'] = '是';
$lang['template_not_public'] = '否';
$lang['template_forbid'] = '禁用';
$lang['add_template_success'] = '添加成功';
$lang['add_template_fail'] = '添加失败';
$lang['confirm_update_template'] = '确定修改模板？';
$lang['update_template_success'] = '模板修改成功';
$lang['update_template_fail'] = '模板修改失败';
$lang['confirm_delete_template'] = '确定删除模板？';
$lang['delete_template_success'] = '删除成功';
$lang['delete_template_fail'] = '删除失败';

/**黑名单**/
$lang['tickets_black_list'] = '黑名单';
$lang['black_uid'] = '会员ID';
$lang['tickets_black'] = '(黑)';
$lang['confirm_delete_black_list'] = '确定从黑名单移除吗？';
$lang['update_black_list_success'] = '已从黑名单中移除';
$lang['update_black_list_fail'] = '移除失败';
$lang['add_black_list_success'] = '已加入黑名单';
$lang['black_list_exist'] = '添加失败，该ID已在黑名单列表';
$lang['add_black_list_fail'] = '加入失败';

$lang['manual_work'] = '手动';
$lang['automatic'] = '自动';
$lang['tickets_cus_leave'] = '客服 %s 已请假';
$lang['tickets_cus_work']  = '客服 %s 已正常上班';
$lang['change_status_fail']='客服 %s 工作状态改变失败';
$lang['tickets_auto_assign'] = '系统已经设置为自动分配';
$lang['tickets_hand_assign'] = '系统已经设置为手动分配';
$lang['tickets_auto_assign_fail'] = '分配设置失败';

$lang['tickets_status'] = '工单状态';
$lang['tickets_priority'] = '工单优先级';
$lang['modified_manager'] = '工单转移';
$lang['tickets_assign'] = '工单分配';
$lang['submit_as'] = '提交为';
$lang['submit_as_waiting_reply'] = '待回应';
$lang['submit_as_waiting_discuss'] = '待商议';
$lang['add_tickets_tips'] = '注释';
$lang['tickets_send_fail'] = '信息发送失败';
$lang['tickets_send_success'] = '信息发送成功';
$lang['apply_close_tickets'] = '申请关闭工单';
$lang['view_tickets'] = '查看工单';
$lang['r_waiting_reply'] = '回复-待回应';
$lang['r_waiting_discuss'] = '回复-待商议';
$lang['tickets_tips']='注释';
$lang['r_tickets_resolved'] = '回复-已解决';
$lang['auto_reply_tickets'] = '自动回复';
$lang['close_tickets_send_email'] = '发送邮件';
$lang['auto_close_tickets'] = '自动关闭工单';

$lang['tickets_label'] = '注释';
$lang['pls_input_tips'] = '请输入注释';
$lang['no_tips'] = '没有注释';
$lang['add_tips_success'] = '添加注释成功';
$lang['add_tips_fail'] = '添加注释失败';

$lang['tickets_type'] = '工单问题类型';
$lang['add_and_quit'] = '加入/退出';
$lang['join_issue'] = '账户信息问题';
$lang['quit_issue'] = '降级/退出申请';
$lang['up_or_down_grade'] = '升级/支付问题';
$lang['monthly_fee_problem'] = '月费问题';
$lang['platform_fee_problem'] = '平台管理费';
$lang['reward_system'] = '奖励制度';
$lang['product_recommendation'] = '产品推荐';
$lang['shop_transfer'] = '店铺转让';
$lang['commission_problem'] = '佣金问题';
$lang['order_problem'] = '订单问题';
$lang['freight_problem'] = '运费问题投诉';
$lang['withdraw_funds_problem'] = '提现问题';
$lang['walhao_store'] = '沃好商城';
$lang['tickets_check_order_status']='催货';
$lang['tickets_change_delivery_information']='更改收货信息';
$lang['tickets_order_cancellation']='取消订单';
$lang['tickets_product_review']='产品投诉';
$lang['tickets_member_suggestions']='会员建议';
$lang['other'] = '其他';
$lang['tickets_after_sales_problem'] = '售后问题';
$lang['shipping_logistics_problems'] = '催货/物流问题';
$lang['tickets_product_damage'] = '产品破损';
$lang['tickets_leakage_wrong_product'] = '产品错发/漏发';

$lang['pls_t_type'] = '请选择问题分类';
$lang['pls_t_title'] = '请输入工单标题';
$lang['pls_t_tid'] = '请输入工单号';
$lang['pls_t_content'] = '请输入工单描述';
$lang['exceed_words_limit'] = '超过字数限制';
$lang['pls_t_uid_aid'] = '请输入会员ID/客服ID';
$lang['pls_t_tid_uid'] = '请输入工单号/会员ID';
$lang['remain_'] = '还可以输入';
$lang['max_limit_'] = '可以输入';
$lang['_words'] = '字';
$lang['pls_input_reply_content'] = '请输入回复内容';
$lang['tickets_info'] = '工单详情';
$lang['search_data'] = '请输入搜索条件查询';

$lang['pls_t_status'] = '请选择状态';
$lang['pls_t_priority'] = '请选择优先级';
$lang['tickets_status'] = '工单状态';
$lang['new_ticket'] = '新建';
$lang['open_ticket'] = '已开启';
$lang['waiting_reply'] = '待回应';
$lang['waiting_discuss'] = '待商议';
$lang['ticket_resolved'] = '已解决';
$lang['had_graded'] = '已评分';
$lang['apply_close'] = '申请关闭';
$lang['had_apply_tickets'] = '已申请关闭';
$lang['tickets_priority'] = '工单优先级';
$lang['priority'] = '优先级';
$lang['reply'] = '回复';
$lang['general_tickets'] = '一般';
$lang['preferential_tickets'] = '优先';
$lang['urgent_tickets'] = '紧急';
$lang['change_tickets_priority_fail'] = '优先级修改失败';
$lang['change_tickets_priority_success'] = '优先级修改成功';
$lang['tickets_transfer'] = '工单转移';
$lang['transfer_tickets_fail'] = '工单转移失败';
$lang['transfer_tickets_success'] = '工单转移成功';
$lang['change_status_success'] = '状态修改成功';
$lang['change_status_fail'] = '状态修改失败';
$lang['pls_select_customer'] = '请选择客服';
$lang['change_type_success'] = '修改类型成功';
$lang['change_type_fail'] = '修改类型失败';
$lang['change_tickets_type']='修改问题类型';

$lang['tickets_statistics'] = '工单统计';
$lang['cus_id'] = '客服ID';
$lang['pls_cus_id'] = '请输入客服ID';
$lang['tickets_statistics_time'] = '统计时间';
$lang['num_id'] = '序号';
$lang['cus_name'] = '姓名';
$lang['today_in_tickets'] = '分配工单';
$lang['today_out_tickets'] = '转出工单';
$lang['today_unprocessed_tickets'] = '当天新工单未处理';
$lang['today_tickets_count'] = '当天工单总数';
$lang['all_unprocessed_tickets_count'] = '全部未处理新工单';
$lang['new_msg_tickets'] = '新消息工单';
$lang['waiting_discuss_tickets'] = '待商议工单';
$lang['waiting_reply_tickets'] = '待回应工单';
$lang['all_tickets_count'] = '工单总量';

$lang['tickets_customer_role'] = '客服账号管理';
$lang['tickets_customer_role_1'] = '客服';
$lang['tickets_customer_role_2'] = '节假日值班经理';
$lang['tickets_customer_permission'] = '权限';
$lang['job_number']='编号';
$lang['confirm_update_customer_1'] = '确定权限修改为 节假日值班经理?';
$lang['confirm_update_customer_2'] = '确定权限修改为 客服?';
$lang['customer_role_invalid_action'] = '无效操作';

$lang['tickets_area_usa'] = '美国区域';
$lang['tickets_area_china'] = '中国区域';
$lang['tickets_area_hk'] = '香港区域';
$lang['tickets_area_korea'] = '韩国区域';
$lang['unique_job_number'] = '客服工号已存在';
$lang['job_number_error']  ='客服工号为三位数字';
$lang['assign_cus_job_number'] = '分配客服工号';
$lang['cus_job_number'] = '工号';
$lang['not_customer'] = '非客服';

$lang['button_text'] = '选择附件';
$lang['is_exists'] = '文件已存在';
$lang['remain_upload_limit'] = '选定的文件数超过上传剩余数量';
$lang['queue_size_limit'] = '选定的文件的数目超过了队列的数量';
$lang['exceeds_size_limit'] = '文件超过大小限制';
$lang['is_empty'] = '文件不能为空';
$lang['not_accepted_type'] = '不允许上传的文件格式';
$lang['upload_limit_reached'] = '已达到上传限制';
$lang['attach_delete_success'] = '移除成功';
$lang['attach_no_permissions'] = '对不起，您的权限不够';
$lang['attach_cannot_find'] = '找不到文件';
$lang['not_support_mobile_upload'] = '不支持手机上传附件';
/**售后中心 end **/

$lang['choose_group'] = '选购订单';
$lang['generation_group'] = '代品劵订单';
$lang['retail_group'] = '零售订单';
$lang['all_group'] = '所有类型订单';

$lang['goods_number_exception'] = '商品库存异常记录';
$lang['number_zh'] = '简体中文库存';
$lang['number_hk'] = '繁体中文库存';
$lang['number_english'] = '英文库存';
$lang['number_kr'] = '韩文库存';
$lang['number_null'] = '该语种无库存';
$lang['user_email_exception_list'] = '用户收发邮件例外列表';
$lang['uid_not_null'] = '用户id不能为空';
$lang['uid_ture'] = '用户id已存在';
$lang['is_uid_delete'] = '一旦删除不可逆转，是否确认删除该记录？';
$lang['is_delete_uid'] = '删除用户确认';


$lang['process_num'] = '处理次数';
$lang['cron_doing'] = '脚本任务管理';
$lang['cron_name'] = '计划任务名称';
$lang['false_count'] = 'return false 次数';

$lang['order_not_accord_with'] = '该订单不符合回滚条件';
//手动添加138佣金合格人数
$lang['user_qualified'] = '138佣金合格人数';
$lang['add_user_qualified'] = '添加138佣金合格人数';
$lang['commission_number'] = '佣金';
$lang['commission_isok'] = '确认添加';

//手动添加doba订单
$lang['admin_trade_repair_adddaba'] = '手动推送doba订单';
$lang['admin_trade_isdoba'] = '该订单已推送成功，无需重复推送';
$lang['admin_trade_doba_nopush'] = '订单无需推送';

/**周领导对等奖**/
$lang['week_leader_preview'] = '周领导对等奖预览';
$lang['week_leader_reward_date'] = '奖金所属日期';
$lang['week_leader_child_reward'] = '下级总奖金';
$lang['week_leader_current_amount'] = '已发总奖金';
$lang['week_leader_reward_percent'] = '奖金比例';
$lang['week_leader_due_amount'] = '本周应得奖金';
$lang['week_leader_total_amount'] = '发放后总奖金';
$lang['week_leader_reward_status'] = '奖金发放状态';
$lang['week_leader_reward_pay_time'] = '奖金发放日期';
$lang['week_leader_status_0'] = '未发放';
$lang['week_leader_status_2'] = '已发放';
$lang['week_leader_detail'] = '详情';
$lang['week_leader_detail_correct'] = '核对正确';
$lang['week_leader_detail_wrong'] = '核对出错';

//用户状态变更记录
$lang['users_status_log'] = '会员状态变更记录';
$lang['users_status_front'] = '变更前的状态';
$lang['users_status_back'] = '变更后的状态';
$lang['buckle_fee'] = '缴纳月费';
$lang['order_fee'] = '订单抵扣月费';
$lang['buckle_fee_error'] = '未缴纳月费';

//佣金管理查询列表
$lang['operator_email'] = '操作人邮箱';
$lang['user_oneself_del'] = '会员自己删除';
$lang['is_certificate'] = '发放代品券';

//佣金查询
$lang['no_time'] = '开始日期和结束日期不能跨月份查询';
$lang['no_search'] = '请输入用户ID并查询';
$lang['no_time_null'] = '开始日期不能为空';
$lang['no_time_all_null'] = '日期不能为空';
$lang['is_certificate'] = '发放代品券';
$lang['limit_query_month'] = '抱歉,当前仅支持您查询当月的数据!';

//活动抵扣月费
$lang['delPlan_title'] = '删除会员活动记录';
$lang['not_join_action_charge_month'] = '该会员没有参加活动抵扣月费计划';

$lang['not_Porder'] = '不允许修复P开头的零售订单和不允许修复C开头的升级订单或代品券订单';
$lang['not_repeat_insert'] = '该订单号已添加过，等待处理';
$lang['admin_file_order_freight_error'] = '%s快递公司写法有误或不能为空';
$lang['admin_file_order_show'] = '*注：快递公司一栏只允许填写0或大于0的数字，0为自定义，如一个订单两个快递公司的，快递公司一栏填写0，快递号一栏填写快递公司名称和单号！';
$lang['order_rollback_show'] = '*注：此功能只适用于等待收货回滚到等待发货！';
$lang['admin_trade_repair_recovery'] = '恢复订单';
$lang['admin_trade_feright_modify'] = '运单信息修复';
$lang['order_recovery_show'] = '*注：此功能适用于被取消或退货的零售订单恢复到其他状态，只改状态和补发佣金，需要扣款的需手动去操作！';
$lang['order_modify_order_freight'] = '*注：此功能适用于供货商提供的物流公司和运单号错误，程序只修改物流信息，上传文件时,请清除掉列[快递号]的数学公式，否则物流单号将出现数学公式，同时注意文件格式,正确的文件如下图所示！';
$lang['order_not_recovery'] = '只有取消或退货的零售订单才能恢复！';
$lang['admin_order_status_revert'] = '恢复到';
$lang['admin_order_commission'] = '补发佣金';
$lang['admin_order_not_logistics'] = '该订单物流信息为空，不能恢复到该状态！';
$lang['all_express'] = '所有快递公司';
$lang['error_express'] = '快递公司和运单号需同时选择';
$lang['admin_order_repeat'] = '%s快递单号重复';
$lang['admin_repeat_data']  = '处理失败，重复导入数据';
$lang['award_prizes']  = '手动发奖';


//修复异常问题
$lang['repair_abnormality'] = '修复异常问题';
$lang['repair_abnormality_sale'] = '修复异常问题';
$lang['direct_push'] = '修复会员下线总人数';
$lang['user_appellation'] = '修复会员职称';
$lang['user_monthly_sales'] = '修复会员每月销售额';
$lang['user_move_offline'] = '移动会员下线';
$lang['repair_user_sale_rank_type'] = '职称修复类型';

$lang['admin_order_status_holding_exchange'] = '冻结(待换货)';
$lang['allow_exchange'] = '允许换货';
$lang['ok_cancel'] = '确定取消';
$lang['btn_del_option'] = '确定要删除吗？';
$lang['cancel_exchange'] = '取消换货';
$lang['go_exchange'] = '去换货';
$lang['exchange_order'] = '换货订单';
$lang['exchange_remaining_time'] = '剩余';
$lang['exchanging'] = '换货中';
$lang['exchange_timeout'] = '换货超时';
$lang['exchange_timeout_msg'] = '72小时内未完成换货,系统自动取消';
$lang['cancel_exchange_confirm_msg'] = '您取消换货之后，该升级订单将不能再次换货！';
$lang['exchange_timer_reset'] = '此操作会重置[去换货]计时器，您确定要执行此操作吗？';
//修复分红
$lang['daily_bonus_month_error'] = "日分红不能跨月";
$lang['lastest_daily_month'] = "最早可以补发日期为";

//奖金特别处理
$lang['daily_bonus_month_error'] = "日分紅高不能跨月";
$lang['add_to_cur_month_queue'] = "加入当月发奖队列";
$lang['user_has_in_queue'] = "用户已经在发奖队列";
$lang['user_not_match_daily_bonus'] = "用户不满足全球日分红";
$lang['please_select_queue_time'] = "请选择加入队列时间";
$lang['user_order_not_match_new_bonus'] = "用户订单不满足新会员奖";
$lang['reward_user_bonus'] = "补发会员奖金";
$lang['daily_bonus_failed_not_set'] = "日分红发放失败 未设置发放比例";
$lang['daily_bonus_failed_not_set_1'] = "日分红发放失败 分红比例不能够超过1";
$lang['not_found_this_day_profit'] = "未找到这天的全球利润";
$lang['daily_bonus_profit_not_enough'] = "日分红利润不足";
$lang['user_level_not_match'] = "用户未升级 不满足条件";
$lang['user_order_amount_not_match'] = "用户已升级，但是不满足销50美金销售额或者取消升级订单";
$lang['new_member_bonus_failed_rate'] = "新会员专享奖发放失败 未设置发放比例";
$lang['new_member_bonus_failed_rate_1'] = "新会员专享奖发放失败 分红比例不能够超过1";
$lang['new_member_bonus_profit_not_enough'] = "新会员奖利润不足";



$lang['pls_input_reson_1'] = '请填写原因，如订单被导出请写明与哪位运营同事确认可冻结';
$lang['repair_users_amount'] = '修复会员现金池';
$lang['repair_users_point'] = '修复会员分红点';
$lang['not_repair_amount'] = '会员的现金池余额和资金变动报表一致，该会员现金池无异常不需要修复';
$lang['user_account_total'] = '系统统计资金变动报表数额';
$lang['user_point_total'] = '系统统计分红点变动明细数额';
$lang['repair_amount'] = '修复';
$lang['user_amount_total'] = '会员当前现金池金额';
$lang['user_cur_point_total'] = '会员当前分红点';
$lang['order_repair_award_prizes_tips'] = '此功能用于漏发订单的个人/团队销售提成奖';
$lang['order_already_award_prizes'] = '此订单已经手动发奖';
$lang['order_already_prizes'] = '此订单已经发奖';
$lang['award_prizes_success'] = '发奖成功';
$lang['order_score_correct'] = '订单业绩纠正';
$lang['order_score_fix_success'] = '订单业绩纠正成功';
$lang['order_not_pay'] = '订单尚未支付';
$lang['order_status_not_meet_the_requirements'] = '订单状态不符合发奖要求';
$lang['order_prizesing'] = '此订单正在发奖, 预计30分钟内发奖成功, 超过30分钟不发奖请在重新尝试执行';
$lang['order_correct_score'] = '待纠正业绩月份';
$lang['order_score_month_format_not_correct'] = '订单业绩月份格式不正确';
$lang['children_order_not_found'] = '查询不到子订单';
$lang['retail_order_allow_award_prizes'] = '零售订单才可发奖';
$lang['prizes_repair_type'] = '修复奖金类型';
$lang['prizes_repair_personal_group'] = '个人/团队两项奖';
$lang['prizes_repair_group'] = '团队单奖';
$lang['uid_is_not_parent'] = '输入用户与订单用户不存在父级关系';
$lang['group_stat'] = '会员团队人数统计';
$lang['confirm_add_account_log'] = '确定要补全转账记录吗？';

$lang['pls_input_reson_2'] = '请填写原因，如订单被导出请写明与哪位运营同事确认可允许换货';

/** invoice 发票 **/
$lang['invoice_manage'] = '发票管理';
$lang['invoice_number'] = '开票单号';
$lang['invoice_time'] = '开票时间';
$lang['invoice_total_money'] = '可开票总金额';
$lang['invoice_fact_total_money'] = '实际开票总金额';
$lang['invoice_order'] = '可开票订单';
$lang['invoice_detail'] = '开票明细';
$lang['invoice_detail_money'] = '开票金额';
$lang['invoice_top'] = '开票抬头';
$lang['invoice_money'] = '订单金额';
$lang['invoice_form'] = '发票形式';
$lang['invoice_paper'] = '纸质发票';
$lang['invoice_electron'] = '电子发票';
$lang['invoice_send_email'] = '收票邮箱';
$lang['invoice_spare_num'] = '备用号码';
$lang['invoice_address'] = '收票地址';
$lang['express_free'] = '快递费';
$lang['receive_people'] = '收件人';
$lang['format_is_not_correct'] = '格式不正确';
$lang['please_select_invoice_form'] = '请勾选发票形式';
$lang['submit'] = '提交';
$lang['cannel'] = '取消';
$lang['no_refund_data'] = '无记录';
$lang['please_input'] = '请输入';
$lang['please_select'] = '请选择';
$lang['add_invoice'] = '新开发票';
$lang['not_invoiced'] = '未开票';
$lang['invoiced'] = '已开票';
$lang['mailed'] = '已邮寄';
$lang['operation_log'] = '操作日志';
$lang['invoice_code'] = '发票编号';
$lang['courier_number'] = '快递单号';
$lang['express_type'] = '快递类型';
$lang['see_sf_free_num'] = '查看运费';
$lang['invoice_paper_remark'] = '会员[%s]纸质发票扣去快递费';
$lang['invoice_sf_free'] = '顺丰运费管理';
$lang['invoice_save_sf_free'] = '保存运费';
$lang['invoice_arrive_tyep'] = '到达类型';

$lang['invoice_type_form_error'] = '请选择发票类型';
$lang['invoice_type_2'] = '发票类型';
$lang['invoice_type_common'] = '普通发票';
$lang['invoice_type_tax'] = '增值税专用发票';
$lang['invoice_country_name_type'] = '公司名称';
$lang['invoice_identify_type'] = '纳税人识别号';
$lang['invoice_bank_name_type'] = '开户银行名称';
$lang['invoice_bank_count_type'] = '银行账户';
$lang['invoice_company_address_type'] = '公司地址';
$lang['invoice_company_phone_type'] = '公司电话';
$lang['invoice_type_word_limit_100'] = '限制输入100个字符';
$lang['invoice_county_name_type_error'] = '公司名称不能为空';
$lang['invoice_identify_type_error'] = '纳税人识别号不能为空';
$lang['invoice_bank_name_type_error'] = '开户银行名称不能为空';
$lang['invoice_bank_count_type_error']='银行账号不能为空';
$lang['invoice_company_address_type_error'] = '公司地址不能为空';
$lang['invoice_company_phone_type_error'] = '公司电话不能为空';

$lang['invoice_title_type'] = '发票抬头类型';
$lang['invoice_title_type_company'] = '公司';
$lang['invoice_title_type_personage']='个人';
$lang['invoice_taxpayer_id_number'] = '纳税人识别号';
$lang['invoice_taxpayer_id_number_error'] = '纳税识别号不能为空';


//解冻用户登录
$lang['unfrost'] = "用户登录解冻";
$lang['please_input_unfrost_account'] = "请输入要解冻的账号";
$lang['please_input_unfrost_account_again'] = "请再次输入要解冻的账号";
$lang['input_unfrost_not_same'] = "两次输入不一致";
$lang['redis_off'] = "redis服务关闭，无需解冻";
$lang['unforst_success'] = "解冻成功";
$lang['unfrost_needless'] = "无需解冻的账号";
$lang['seleted_input_null'] = "选择不能为空！";
//分红计划监控
$lang['bonus_plan_control'] = "奖金发放监控";
$lang['bonus_plan_control_oth'] = "程序运行监控";
$lang['bonus_plan_control_name'] = "名称";
$lang['bonus_plan_control_status'] = "状态";
$lang['bonus_plan_control_exec_time'] = "执行时间";
$lang['bonus_plan_control_exec_end_time'] = "完成时间";
$lang['time_consuming'] = "耗时";
$lang['description'] = "描述";
$lang['fix_user_rank'] = "职称修复";
$lang['view_user_score'] = "查看业绩";
$lang['fix_user_rank_later'] = "后台已经记录, 5分钟后自动修复. 请稍后核实";
$lang['fix_user_rank_later'] = "后台已经记录, 5分钟后自动修复. 请稍后核实";

$lang['export_bank_orders'] = "导出到银行的订单";
$lang['export_bank_time_period'] = "时段";
$lang['time_period1'] = "晚上";
$lang['time_period2'] = "白天";

//补发佣金信息
$lang['admin_show_user_monthly'] = "是否显示用户业绩&nbsp;&nbsp;(默认显示最近3个月的数据)";
$lang['admin_show_day_bonsu_monthly'] = "显示每天全球利润分红队列信息";
$lang['admin_show_week_bonsu_monthly'] = "显示每周团队销售分红队列信息";
$lang['admin_show_month_bonsu_monthly'] = "显示每月团队销售分红队列信息";
$lang['admin_day_bonus_list'] = "每天全球利润分红队列";
$lang['admin_week_bonus_list'] = "每周团队销售分红队列";
$lang['admin_month_bonus_list'] = "每月团队销售分红队列";

//导出需要过海关的订单
$lang['export_customs_orders'] = "导出要过海关的订单";

$lang['users_bonus_list_check'] = "用户佣金队列异常";
$lang['import_third_order_tips'] = '上传文档前，必须先删除模板数据!!!';