<?php
/****paypal提现****/
$lang["Receiver's account is invalid"]='收件人的帳戶無效';
$lang["Sender has insufficient funds"]='發件人資金不足';
$lang["User's country is not allowed"]='用戶的國家不允許使用';
$lang["User's credit card is not in the list of allowed countries of the gaming merchant"]='用戶的信用卡不在商家的允許國家列表中';
$lang["Cannot pay self"]='不能自我支付';
$lang["Sender's account is locked or inactive"]='發件人的帳戶已鎖定或無效';
$lang["Receiver's account is locked or inactive"]='收件人的帳戶已鎖定或無效';
$lang["Either the sender or receiver exceeded the transaction limit"]='發件人或收件人超出了交易限制';
$lang["Spending limit exceeded"]='超出支出限額';
$lang["User is restricted"]='用戶受限';
$lang["Negative balance"]='負余額';
$lang["Receiver's address is in a non-receivable country or a PayPal zero country"]='接收方地址是在非收款國家或貝寶零國家';
$lang["Invalid currency"]='貨幣無效';
$lang["Sender's address is located in a restricted State (e.g., California)"]='發件人地址位於受限州（例如，加利福尼亞州）';
$lang["Receiver's address is located in a restricted State (e.g., California)"]='接收方地址位於受限州（例如，加利福尼亞州）';
$lang["Market closed and transaction is between 2 different countries"]='市場關閉，交易在兩個不同的國家之間';
$lang["Internal error"]='PayPal風險控制（內部錯誤）';
$lang["Zero amount"]='金額為零';
$lang["Receiving limit exceeded"]='超過接收限制';
$lang["Duplicate mass payment"]='重復大量付款';
$lang["Transaction was declined"]='交易被拒絕';
$lang["Per-transaction sending limit exceeded"]='每個發送交易超出限制';
$lang["Transaction currency cannot be received by the recipient"]='收款人無法收到交易貨幣';
$lang["Currency compliance"]='貨幣合規';
$lang["The mass payment was declined because the secondary user sending the mass payment has not been verified"]='大量付款被拒，因為發送大量付款的次要用戶尚未驗證';
$lang["Regulatory review - Pending"]='監管審查 - 待定';
$lang["Regulatory review - Blocked"]='監管審查 - 阻止';
$lang["Receiver is unregistered"]='接收方未註冊';
$lang["Receiver is unconfirmed"]='接收方未確認';
$lang["Youth account recipient"]='青年帳戶收件人';
$lang["POS cumulative sending limit exceeded"]='POS累計發送超出限制';
$lang['paypal_withdrawal_list'] = 'paypal提現列表';
$lang['submit_paypal'] = '提交Paypal處理';
$lang['batch_modification'] = "批量修改為'已處理'狀態";
$lang['Paypal Account number'] = "Paypal賬號";
$lang['Delete_user'] = "该ID存在资金变动记录，将转为公司预留账户";
$lang['paypal_pending_log'] = 'paypal退款狀態訂單記錄';
$lang['paypal_pending_ts'] = '您確定該訂單已經處理完成！';
$lang['paypal_pending_cl'] = '處理';
/** 黑名单列表 */
$lang['add_blacklist'] = '添加敏感词';
$lang['blacklist'] = '敏感词列表';
$lang['blacklist_ex'] = '敏感词';
$lang['enter_blacklist'] = '请输入敏感词';
$lang['btn_check'] = '正向检测';
$lang['btn_f_check'] = '反向检测';
$lang['transfer_contr'] = '转账监控';
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
$lang['title_level_0'] = '店主(SO)';
$lang['title_level_1'] = '资深店主(MSO)';
$lang['title_level_2'] = '市场主管(MSB)';
$lang['title_level_3'] = ' 高级市场主管(SMD)';
$lang['title_level_4'] = '市场总监(EMD)';
$lang['title_level_5'] = '全球市场销售副总裁(GVP)';

$lang['users_amount_check'] = '提现用户转账记录检测';
$lang['users_check_btn'] = '检测';

/**会员中心*/
$lang['pre_day_bonus'] = '日分红奖';
$lang['pre_new_user_bonus'] = '新会员专项奖';
$lang['develop_msg'] = '开发者管理';
$lang['user_error_total'] = '错误数';
$lang['admin_exchange_user_email_title'] = '郵箱修改';
$lang['admin_exchange_user_mobile_title'] = '手機號修改';
$lang['admin_exchange_user_info_content'] = '確定要修改信息嗎?';
$lang['admin_remark_input_not_null'] = '備註信息不能為空！';
$lang['admin_remark_option_name'] = '操作人';
$lang['admin_remark_option_time'] = '備註時間';
$lang['user_order_achievement_repair'] = '订单延迟业绩修复';
/***奖金类型**/
$lang['pre_week_team_bonus'] = '周团队组织分红奖';
$lang['pre_month_team_bonus'] = '月团队组织分红奖';
$lang['pre_month_leader_bonus'] = '月领导组织分红奖';
$lang['pre_amount_bonus'] = '发放金额（单位：美元）';
$lang['user_sale_up_time'] = '职称升级时间';

/***冻结账号**/
$lang['frost_user_time'] = "凍結時長";
$lang['day'] = "天";
$lang['frost_forever'] = "長期凍結";
$lang['please_select_frost_time'] = "請選擇凍結時長";

$lang['pre_bonus_submit'] = '重新预发奖';
$lang['pre_bonus_submit_note'] = '注：点击此按钮，将重新生成预发奖，可能会影响到实际发奖，操作时，请及时联系技术人员.';
$lang['user_achievement_edit'] = '批量修复业绩';
/**会员统计*/
$lang['admin_store_statistics_total'] = '總統計';
$lang['admin_store_statistics_datetime'] = '日期';
$lang['admin_user_level_f'] = '免費會員';
$lang['admin_user_level_b'] = '銅級會員';
$lang['admin_user_level_s'] = '銀級會員';
$lang['admin_user_level_g'] = '金級會員';
$lang['admin_user_level_p'] = '鉆石會員';
$lang['admin_user_level_t'] = '合計(SUM)';
$lang['admin_everyday_level_t'] = '每日小計';
$lang['admin_everyday_level_count_t'] = '總加盟會員：';
$lang['admin_everyday_level__t'] = '繳費會員：';
$lang['admin_month_level_t'] = '月總計';
/**公告*/
$lang['admin_board_title_not_null'] = '標題不能全部為空！';
$lang['admin_board_conteng_not_null'] = '公告內容不能全部為空！';
$lang['admin_board_english_title_err'] = '英文標題不能為空！';
$lang['admin_board_chinese_title_err'] = '中文標題不能為空！';
$lang['admin_board_hk_title_err'] = '繁文標題不能為空！';
$lang['admin_board_kr_title_err'] = '韓文標題不能為空！';
$lang['admin_board_en_content_err'] = '英文內容不能為空！';
$lang['admin_board_zh_content_err'] = '中文內容不能為空！';
$lang['admin_board_hk_content_err'] = '繁文內容不能為空！';
$lang['admin_board_kr_content_err'] = '韓文內容不能為空！';
$lang['user_achievement_note'] = '注：此功能批量修复会员业绩，请按照如下图所示的格式，存放用户id.';
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
$lang['admin_exchange_rate_error'] = '匯率格式錯誤';
$lang['admin_not_demote_tip'] = '銅級、銀級店鋪不能降級操作';
$lang['admin_go_process'] = '去處理';
$lang['admin_check_pass'] = '審核通過';
$lang['admin_upload_return_fee'] = '上傳退運費售後單';
$lang['admin_return_fee_tip1'] = '订单状态异常';
$lang['admin_return_fee_tip2'] = '退款金额大于订单运费';
$lang['admin_return_fee_tip3'] = '退运费订单不能重复提交';
$lang['admin_return_fee_tip4'] = '订单的顾客已经申请退会操作';
$lang['admin_refund_amount'] = '订单退运费:$%s';
$lang['admin_add_after_sale'] = '新建售後訂單';
$lang['admin_after_sale_id'] = '售後單號';
$lang['admin_after_sale_uid'] = '關聯會員ID';
$lang['admin_after_sale_name'] = '關聯會員姓名';
$lang['admin_after_sale_type'] = '售後問題分類';
$lang['admin_after_sale_type_0'] = '退會';
$lang['admin_after_sale_type_1'] = '降級';
$lang['admin_after_sale_type_2'] = '退運費';
$lang['admin_after_sale_method'] = '退款支付方式';
$lang['admin_after_sale_method_0'] = '轉賬到銀行';
$lang['admin_after_sale_method_1'] = '轉入現金池';
$lang['admin_after_sale_method_2'] = '轉入支付寶';
$lang['admin_after_sale_amount'] = '退款金額';
$lang['refund_amount_error'] = '退款金額不能為空或等於0';
$lang['admin_after_sale_remark'] = '反饋內容';
$lang['admin_after_sale_remark_error'] = '反饋內容不能為空';
$lang['admin_after_sale_remark_example'] = '會員要求店鋪降級為銀級，原為白金。 ';
$lang['admin_add_after_sale_list'] = '售後管理';
$lang['admin_after_sale_demote'] = '降級等級';
$lang['admin_after_sale_status_0'] = '待處理';
$lang['admin_after_sale_status_1'] = '已抽回(待付款到銀行)';
$lang['admin_after_sale_status_2'] = '已抽回(已退款到現金池)';
$lang['admin_after_sale_status_3'] = '已退款到銀行';
$lang['admin_after_sale_status_4'] = '抽回駁回';
$lang['admin_after_sale_status_5'] = '退款駁回';
$lang['admin_after_sale_status_6'] = '作廢';
$lang['admin_after_sale_status_6'] = '作廢';
$lang['admin_after_sale_status_7'] = '已錄入';
$lang['admin_as_upgrade_order'] = '升級訂單';
$lang['admin_as_consumed_order'] = '消費訂單';
$lang['admin_as_refund'] = '退款處理';
$lang['admin_as_not_exist'] = '售後單號不存在';
$lang['admin_as_status_error'] = '用戶狀態是公司賬戶，無法操作!';
$lang['admin_as_view_remark'] = '查看/添加備註';
$lang['admin_as_action_log'] = '售後處理操作記錄表';
$lang['admin_as_update'] = '修改';
$lang['admin_as_payee_no_exist'] = '收款人會員ID不存在';
$lang['admin_after_sale_amount_error'] = '退款金額格式不正確';
$lang['admin_after_sale_submit'] = '申請抽回';
$lang['admin_after_sale_repeat'] = '該會員售後退會訂單已申請...';
$lang['admin_after_sale_demote_info'] = '該會員售後降級訂單正在處理...';
$lang['admin_email_or_id'] = '管理員ID / 郵箱';
$lang['admin_as_upload_info'] = '上傳回執單';
$lang['admin_as_del_upload_info'] = '刪除回執單';
$lang['admin_after_sale_coupons'] = '注意：扣減代品券不足以支持降級，部分退款金額需要抵掉代品券。 ';
$lang['is_grant_generation'] = '發放團隊銷售佣金';

/** 导入运单号 */
$lang['admin_no_lock'] = '未鎖定訂單';
$lang['admin_yes_lock'] = '已鎖定訂單';
$lang['admin_select_is_lock'] = '鎖定導出訂單';
$lang['admin_order_lock'] = '訂單已鎖定，無法更改';
$lang['admin_file_format'] = '文件格式不支持';
$lang['admin_file_data'] = '文件數據為空';
$lang['admin_file_not_full'] = 'EXCEL中第%s行，信息不完整';
$lang['admin_file_order_status'] = '%s訂單狀態不匹配';
$lang['admin_file_not_freight'] = '%s物流號不存在';
$lang['admin_select_file'] = '請選擇要上傳的EXCEL';
$lang['admin_request_failed'] = '請求失敗';
$lang['admin_upload_freight'] = '上傳運單號';
$lang['admin_scan_shipping'] = '掃描發貨';
$lang['admin_scan_order_id'] = '掃描訂單ID';
$lang['admin_scan_track_id'] = '掃描運單號';
$lang['admin_export_orders'] = '導出訂單';
$lang['admin_download_model'] = '下載模板';
$lang['admin_ship_note_type'] = '發貨備註類型';
$lang['admin_ship_note_type1'] = 'N天后';
$lang['admin_ship_note_type2'] = '固定日期';
$lang['admin_ship_note_val'] = '發貨日期';
$lang['admin_ship_note_val_eg'] = '例如：5或者2015/11/11';
$lang['add_admin'] = '添加賬戶';
$lang['order_report'] = '訂單報表';
$lang['store_report'] = '店鋪報表';
$lang['order_status_3'] = '待發貨';
$lang['order_status_4'] = '已發貨';
$lang['order_status_5'] = '貨款結算';
$lang['refund_card_number'] = '退款人身份證';
$lang['transfer_card_number'] = '轉讓人身份證';
$lang['receive_card_number'] = '接收人身份證';
$lang['receive_email'] = '轉讓人郵箱';
$lang['transfer_1'] = '轉讓';
$lang['transfer_2'] = '退款';
$lang['china_weight_fee'] = '中國運費';
$lang['usa_weight_fee'] = '美國商品運費';
$lang['shipping_com'] = '物流公司';
$lang['first_line_format_error'] = '第一行格式不相同';
$lang['upload_big_excel_error'] = '上傳文件過大，請折開多個檔案進行上傳';
$lang['upload_excel_fail'] = '上傳文件失敗';

/** 支付方式 */
$lang['pay_name'] = '支付名稱';
$lang['pay_desc'] = '支付描述';
$lang['pay_currency'] = '支付貨幣';
$lang['payment_list'] = '支付方式';
$lang['edit_payment'] = '編輯支付';
$lang['is_enabled'] = '是否啟用';
$lang['not_enabled'] = '不啟用';
$lang['yes_enabled'] = '啟用';
$lang['yspay_username'] = '銀盛用戶名';
$lang['yspay_merchantname'] = '銀盛商戶名';
$lang['yspay_pfxpath'] = '銀盛私鑰名';
$lang['yspay_pfxpassword'] = '銀盛私鑰密碼';
$lang['yspay_certpath'] = '銀盛公鑰名';
$lang['yspay_host'] = '銀盛請求地址';
$lang['unionpay_merId'] = '銀聯用戶名';
$lang['unionpay_pfxpath'] = '銀聯私鑰名';
$lang['unionpay_pfxpassword'] = '銀聯私鑰密碼';
$lang['unionpay_certpath'] = '銀聯公鑰名';
$lang['unionpay_host'] = '銀聯請求地址';
$lang['paypal_account'] = '貝寶賬號';
$lang['paypal_submit_url'] = '貝寶提交地址';
$lang['paypal_host'] = '貝寶請求地址';
$lang['ewallet_key'] = '電子錢包密钥';
$lang['ewallet_password'] = '電子錢包密码';
$lang['ewallet_host'] = '電子錢包請求地址';
$lang['ewallet_login'] = '電子錢包登錄地址';
$lang['alipay_account'] = '支付寶賬號';
$lang['alipay_key'] = '支付寶密鑰';
$lang['alipay_partner'] = '支付寶合作者ID';

$lang['old_month'] = '舊月費池';
$lang['user_not_free'] = '用戶必須是免費用戶並且沒有分店';
$lang['delete_free_user'] = '刪除免費用戶';
$lang['half_year_exe'] = '六個月之內不能使用這操作';
$lang['new_month'] = '月費池餘額';
$lang['money_update'] = '變動金額';
$lang['month_type_1'] = '充值';
$lang['month_type_4'] = '交月費';
$lang['action_charge_month'] = '活動抵扣月費';
$lang['join_action_charge_month'] = '您已參加活動抵扣月費計劃。';
$lang['action_charge_month_tip'] = '系統:活動抵扣月費訂單不能取消、退貨。';
$lang['monthly_fee_detail'] = '月費明细表';
$lang['is_withdrawal'] = '店主已經提現了，不能退款';
$lang['is_transfer'] = '店主已經轉賬了，不能退款';


$lang['update'] = '修改';
$lang['clear'] = '刪除電子錢包賬戶';
$lang['update_success'] = '修改成功';
$lang['update_failure'] = '更新失敗';
$lang['reject'] = '駁回';
$lang['no_operate'] = '不能操作';
$lang['all'] = '全選';
$lang['reapply'] = '請取消後重新申請提現';
$lang['status_title'] = '店鋪狀態';
$lang['status_0'] = '未激活';
$lang['status_1'] = '激活';
$lang['status_2'] = '休眠';
$lang['status_3'] = '凍結';
$lang['status_4'] = '公司店鋪';
$lang['cash_withdrawal_list'] = '手動處理提現金額';
$lang['withdrawal_cash'] = '提現金額';
$lang['withdrawal_type'] = '提現類型';
$lang['withdrawal_account'] = '提現賬號';
$lang['tps_manually'] = 'TPS手動';
$lang['tps_paid'] = '已手動支付';
$lang['tps_status_0'] = '未處理';
$lang['tps_status_1'] = '已處理';
$lang['tps_status_2'] = '處理中';
$lang['tps_status_4'] = '已取消';
$lang['sure'] = '確定？';
$lang['sure_delivery'] = '你確認已經收到此訂單的產品？';

$lang['lifecycle'] = '賬戶詳情';
$lang['no_title'] = '請輸入新聞標題';
$lang['no_source'] = '請輸入新聞來源';
$lang['no_content'] = '請輸入新聞內容';
$lang['no_img'] = '請上傳新聞圖片';
$lang['news_img'] = '上傳新聞圖片';
$lang['required'] = '必填项';
$lang['hot_news'] = '熱門新聞';
$lang['sort'] = '排序高低';
$lang['sort_exist'] = '排序數字已存在';
$lang['display'] = '是否顯示';
$lang['no_display'] = '不顯示';
$lang['important_title'] = '重要';
$lang['need_display'] = '顯示';
$lang['order_search'] = '升級訂單查詢';
$lang['upgrade_order'] = '產品套裝訂單';
$lang['upgrade_month_order'] = '升級月費訂單';
$lang['month_fee_order'] = '充值月費訂單';
$lang['txn_id'] = '交易號';
$lang['order_sn'] = '訂單號';
$lang['payment'] = '付款方式';
$lang['pay_time'] = '付款時間';
$lang['unpaid'] = '未付款';
$lang['paid'] = '已付款';
$lang['usd_money'] = '美金';

$lang['news_lang']='所属语言';
$lang['news_cate']='所属分类';
$lang['news_ok']='添加分类';
$lang['news_cate_name']='新闻公告';

$lang['approve'] = '通過';
$lang['refuse'] = '未通過';
$lang['pending'] = '待審核';
$lang['refuse_reason'] = '駁回原因';
$lang['action'] = '操作';
$lang['reset_password']='重置密碼';
$lang['check_card'] = '審核身份證';
$lang['check_card_id'] = '身份證號';
$lang['new_card_number'] = '新身份證號';
$lang['scan'] = '掃描件';
$lang['scan_back'] = '掃描件背面';
$lang['create_time'] = '創建時間';
$lang['check_status'] = '審核狀態';

/** Add by Andy*/
$lang['check_admin'] = '稽核人';
$lang['check_time'] = '稽核時間';
$lang['relive_ban'] = '解除禁止';
$lang['relive_success'] = '解除成功';
$lang['relive_fail']    = '解除失敗';

$lang['news_manage'] = '新聞管理';
$lang['add_news'] = '添加新聞';
$lang['bulletin_board_list'] = '公告欄列表';
$lang['add_bulletin_board'] = '添加公告欄';
$lang['title'] = '新聞標題';
$lang['source'] = '新聞來源';
$lang['content'] = '新聞內容';
$lang['news_list'] = '新聞列表';
$lang['news_type'] = '新聞類型';

$lang['tps138_admin'] = 'TPS管理後臺';
$lang['user_management'] = '會員管理';
$lang['user_list'] = '會員列表';
$lang['user_info'] = '會員信息詳情';
$lang['view_detail_info'] = '查看詳情';
$lang['status'] = '狀態';
$lang['not_enable'] = '未激活';
$lang['enabled'] = '正常';
$lang['sleep'] = '休眠';
$lang['account_disable'] = '賬戶凍結';
$lang['company_keep'] = '公司預留帳戶';
$lang['month_fee_date'] = '月費日';
$lang['day'] = '日';
$lang['day_th'] = '號';
$lang['admin_account_manage'] = '帳戶管理';
$lang['admin_account_list'] = '後臺帳戶列表';
$lang['role'] = '角色';
$lang['role_super'] = '管理員';
$lang['role_customer_service'] = '客服';
$lang['role_customer_service_lv1'] = '客服-lv1';
$lang['role_customer_service_lv2'] = '客服-lv2';
$lang['role_customer_service_manager'] = '客服經理';
$lang['operations_personnel'] = '運營';
$lang['role_storehouse_korea'] ='導單發貨（韓國）';
$lang['role_storehouse_hongkong'] ='導單發貨（香港）';
$lang['financial_officer'] = '財務';
$lang['account_disable'] = '凍結';
$lang['account_reenable'] = '解凍';
$lang['account_enable'] = '激活';
$lang['enable_store_level'] = '激活店鋪等級';
$lang['upgrade_store_level'] = '升級店舖等級';
$lang['upgrade_user_manually'] = '手動升級會員等級';
$lang['user_id'] = '用戶id';
$lang['please_sel_level'] = '請選擇等級';
$lang['user_id_list_requied'] = '請填寫會員id';
$lang['month_fee_or_user_rank_requied'] = '請選擇月管理費等級或店舖等級';
$lang['submit_success'] = '提交成功';
$lang['account_disable_z'] = '正常凍結';
$lang['account_reenable_z'] = '正常解凍';
$lang['account_disable_m'] = '欠月費且凍結';
$lang['account_reenable_m'] = '欠月費且解凍';
$lang['upgrade_success_num'] = '%s 個會員已處理。';
$lang['upgrade_no_num'] = '無有效會員id。';
$lang['user_ids_notice'] = '多個id以逗號分隔';
$lang['no_permission'] = '沒有權限';
$lang['resert_user_status'] = '恢复用户状态';
$lang['signouting'] = '退会中';
$lang['signouting_not_accounts'] = '退會中不能轉帳';
$lang['signouting_not_withdrawals'] = '退會中不能提現';
$lang['signouting_not_pay'] = '退會中不能現金池支付';
$lang['admin_user_account_disabled_hint'] = '输入密码错误次数过多，账号已被锁定。如需帮助，请联系组长经理。';
$lang['admin_user_login_pwd_error'] = '您的密码有误，错误超过3次将锁定账户';
/*佣金*/
$lang['commission_admin'] = '佣金管理';
$lang['commission_add_or_reduce'] = '佣金增減';
$lang['pls_input_amount'] = '請填寫金額';
$lang['amount_condition'] = '金额必须为大于0的数值，如果是小数，保留小数点后面两位。';
$lang['why'] = '原因';
$lang['pls_input_reson'] = '請填寫原因';
$lang['amount_limit'] = '金额限制';

$lang['unbundling'] = '解綁';
$lang['unbundling_success'] = '解綁成功';
$lang['unbundling_fail'] = '解綁失敗';
$lang['will_unbinding'] = '你將解除綁定';

/*佣金特别处理*/
$lang['commission_month_sum'] = '該月獎金總計';
$lang['commission_month'] = '會員月佣金統計';
$lang['commission_special_do'] = '獎金特別處理';
$lang['add_in_qualified_list'] = '加入當月發獎列表';
$lang['pls_sel_comm_item'] = '请选择奖项';
$lang['pls_input_right_uid'] = '请输入正确的用户id。';
$lang['fix_user_commission'] = '补发会员奖金';
$lang['pls_sel_date'] = '请选择日期';
$lang['date_error_over_today'] = '结束日期不能大于今天';
$lang['month_date_error_over_today'] = '每月15號發放每月傑出店鋪分紅獎和團隊組織分紅獎，選擇日期時請包含15號!';
/*月费池管理*/
$lang['monthfee_pool_admin'] = '月費池管理';
$lang['monthfee_pool_add_or_reduce'] = '月費池增減';

//team
$lang['enter'] = '輸入用戶ID後，按回車鍵';
$lang['no_exist'] = '會員ID不存在';
$lang['join_matrix_time'] = '加入矩陣時間';
$lang['buy_product_time'] = '購買產品套裝時間';

/*优惠券*/
$lang['coupon'] = '優惠券';

/*会员列表*/
$lang['search_notice'] = 'ID / 郵箱 / 姓名';
$lang['card_notice'] = 'ID / 姓名';
$lang['total_money'] = '總金額';
$lang['commission_special_check'] = '补发奖金计算';

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
$lang['alipay_withdraw'] = '支付寶提現列表';
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
$lang['clear_member_account_info'] = '轉讓用戶賬戶';
$lang['new_email'] = '新的Email';
$lang['new_password_note'] = '該賬戶的初始密碼是：%s';

$lang['commission_2x5'] = '2x5佣金管理';
$lang['commission_compensation'] = '確定';

$lang['please_input_user_id'] = '收取佣金的ID';
$lang['please_input_pay_user_id'] = '發放佣金的ID';

$lang['commission_forget'] = '佣金漏發';
$lang['commission_error'] = '佣金錯發';
$lang['commission_repeat'] = '佣金重復';

$lang['reason'] = '-----原因-----';


$lang['commission_2x5'] = '2x5佣金補償';


/***月費轉現金池***/
$lang['month_fee_to_amount'] = '月費轉現金池';
$lang['user_id'] = '用戶ID';
$lang['month_fee'] = '月費池';
$lang['amount_'] = '現金池';
$lang['to'] = '轉入';
$lang['confirm'] = '確定';
$lang['user_not_exits'] = 'ID 不存在';
$lang['max_cash'] = '最大可轉入：';
$lang['id_not_null'] = 'ID 不能為空';
$lang['month_fee_error'] = '請輸入大於0的金額(最多兩位小數)';
$lang['not_bigger'] = '不能超過限額';
$lang['transfer_to_success'] = '轉入成功';
$lang['transfer_to_fail'] = '轉入失敗';
$lang['cash_not_exits'] = '金額不能為空';

$lang['see_user_back_office'] = '查看用戶後臺';

$lang['select_order_repair_date'] ='選擇補單年月';
$lang['select_comm_order_type'] ='選擇佣金補單類型';
$lang['pls_select_order_year'] ='請選擇補單年月';
$lang['pls_select_comm_order_type'] ='請選擇佣金補單類型';
$lang['add_comm_success'] ='佣金補單成功';
$lang['add_comm_fail'] ='不需要補單';
$lang['cur_month_add_comm_ban'] ='此月份不能補單';
$lang['comm_tips'] ='提示';
$lang['you_will_add_a_comm'] ='你將添加一行佣金補單：';
$lang['add_comm_year_month'] ='補單年月';
$lang['comm_order_type'] ='佣金補單類型';
$lang['need_add_comm_mount'] ='需補單金額';

/* 产品管理相关  */
$lang['goods_doba_import']='DOBA產品csv導入';
$lang['brand_exists']='品牌名稱已存在!';
$lang['cate_exist']='分類名稱已存在 !';
$lang['goods_manage']='產品管理';
$lang['add_category']='添加分類';
$lang['category_list']='分類管理';
$lang['add_goods']='添加商品';
$lang['goods_list']='商品列表';
$lang['ads_list']='廣告管理';
$lang['ads_add']='新增廣告';
$lang['edit_ads']='編輯廣告';
$lang['goods_group_list']='商品套餐清單';
$lang['edit_category']='編輯分類';
$lang['edit_goods']='編輯商品';

$lang['label_ads_img']='廣告圖片';
$lang['label_ads_sort']='顯示順序';
$lang['label_ads_url']='廣告連結';
$lang['label_ads_status']='顯示狀態';
$lang['label_ads_lang']='廣告語種';
$lang['label_ads_location']='顯示位置';

$lang['label_goods_group_add']='添加套餐';
$lang['label_goods_group_edit']='編輯套餐';
$lang['label_goods_group_search']='蒐索產品';
$lang['label_goods_group_ok']='蒐索';
$lang['label_goods_group_keywords']='商品關鍵字';
$lang['label_goods_group_num']='數量';
$lang['label_goods_group_id']='套餐ID';
$lang['label_goods_group_content']='套餐内容';

$lang['label_brand_list']='品牌管理';
$lang['label_brand_add']='添加品牌';
$lang['label_brand_name']='品牌名稱';
$lang['label_brand_id']='品牌ID';
$lang['label_language']='所屬語言';
$lang['label_language_all']='所有語種';
$lang['label_brand_list_m']='品牌列表';

$lang['label_cate_parent']='上級分類';
$lang['label_cate_name']='分類名稱';
$lang['label_cate_sn']='分類SN';
$lang['label_cate_desc']='分類描述';
$lang['label_cate_icon']='分類ICON';
$lang['label_cate_sort']='分類排序';
$lang['label_cate_meta_title']='SEO標題';
$lang['label_cate_meta_keywords']='SEO關鍵字';
$lang['label_cate_meta_desc']='SEO描述';
$lang['label_cate_top']='頂級分類';

$lang['label_goods_display_state']='顯示該語種';
$lang['label_goods_cate']='所屬分類';
$lang['label_goods_shipper']='發貨商';
$lang['label_goods_shipper_sel']='所有發貨商';
$lang['label_goods_brand']='所屬品牌';
$lang['label_goods_effect']='所屬風格';
$lang['label_goods_name']='商品名稱';
$lang['label_goods_name_cn']='商品名稱（中文）';
$lang['label_goods_main_sn']='商品主SKU';
$lang['label_goods_img']='商品主圖（250*250）';
$lang['label_is_change_width']='不縮放';
$lang['label_goods_sku']='創建子SKU';
$lang['label_goods_img_gallery']='商品相册圖';
$lang['label_goods_img_detail']='商品詳情圖';
$lang['label_goods_stock']='庫存數量';
$lang['label_goods_warn']='預警數量';
$lang['label_goods_weight']='產品重量（kg）';
$lang['label_goods_bulk']='體積';
$lang['label_goods_purchase_price']='採購價(usd)';
$lang['label_goods_market_price']='市場價(usd)';
$lang['label_goods_shop_price']='銷售價(usd)';
$lang['label_goods_is_promote']='是否促銷';
$lang['label_goods_promote_start']='促銷開始時間';
$lang['label_goods_promote_end']='促銷結束時間';
$lang['label_goods_promote_price']='促銷價';
$lang['label_goods_sale']='上架';
$lang['label_goods_unsale']='下架';
$lang['label_goods_looking']='稽核中';
$lang['label_goods_delete']='删除';
$lang['label_goods_best']='推薦';
$lang['label_goods_new']='新品';
$lang['label_goods_hot']='熱賣';
$lang['label_goods_home']='首頁展示';
$lang['label_goods_ship']='包郵';
$lang['label_goods_24']='24小時發貨';
$lang['label_goods_voucher']='可代金卷購買';
$lang['label_goods_alone_sale']='單品';
$lang['label_goods_group_sale']='套裝';
$lang['label_goods_group_sale_upgrade']='陞級套裝';
$lang['label_goods_for_upgrade']='用於陞級';
$lang['label_goods_group_sale_ids']='套裝ID';
$lang['label_goods_note']='產品備註（附屬說明）';
$lang['label_goods_note1']='紅字特殊備註';
$lang['label_goods_store']='所屬倉庫';
$lang['label_goods_add_user']='添加者';
$lang['label_goods_update_user']='更新者';
$lang['label_goods_add_time']='添加時間';
$lang['label_goods_update_time']='更新時間';
$lang['label_goods_sort']='排序';
$lang['label_goods_desc']='詳情描述（文字描述）';
$lang['label_goods_desc_pic']='詳情描述（長圖片）';
$lang['label_goods_sale_country']='銷售國家';
$lang['label_yes']='是';
$lang['label_no']='否';
$lang['label_sub_sn']='商品SKU';
$lang['label_color']='商品顏色';
$lang['label_size']='商品尺寸';
$lang['label_customer']='自定義';
$lang['label_sel_store']='所有倉庫';
$lang['label_sel_store_third']='供應商倉庫';
$lang['label_sel_cate']='所有分類';
$lang['label_sel_status']='所有狀態';
$lang['label_sel_supplier']='所有供應商';
$lang['label_sel']='- 請選擇 -';
$lang['label_flag']='產地';
$lang['label_goods_gift']='贈品(多個sku英文逗號分隔)';

$lang['label_new'] = '新品举荐';
$lang['label_comment'] = '热卖举荐';
$lang['label_supplier'] = '供應商管理';
$lang['label_supplier_add'] = '新增供應商';
$lang['label_supplier_edit'] = '编辑供應商';
$lang['label_supplier_name'] = '公司名';
$lang['label_supplier_user'] = '連絡人';
$lang['label_supplier_tel'] = '手機';
$lang['label_supplier_phone'] = '電話';
$lang['label_supplier_qq'] = 'QQ';
$lang['label_supplier_ww'] = '旺旺';
$lang['label_supplier_addr'] = '地址';
$lang['label_supplier_email'] = 'Email';
$lang['label_supplier_link'] = '公司網站';
$lang['label_supplier_shipping'] = '自發貨供應商';
$lang['info_supplier_exist'] = '該供應商已經存在，請不要重複添加';
$lang['info_supplier_username_exist'] = '該供應商用戶名已經存在，請不要重複添加';
$lang['label_supplier_n'] = '供應商';
$lang['label_supplier_username'] = '用戶名';
$lang['label_supplier_password'] = '密碼';

$lang['label_cn'] = '中國';
$lang['label_us'] = '美國';
$lang['label_hk']='香港';
$lang['label_ne']='紐西蘭';
$lang['label_ho']='荷蘭';
$lang['label_as']='澳大利亞';
$lang['label_fr']='法國';
$lang['label_ko']='韓國';
$lang['label_tw']='臺灣';
$lang['label_jp']='日本';
$lang['label_sp']='西班牙';
$lang['label_ph']='菲律賓';
$lang['label_chi']='智利';
$lang['label_ge']='德國';
$lang['label_ca']='加拿大';
$lang['label_fi']='芬蘭';

$lang['info_success']='数据提交成功';
$lang['info_failed']='数据提交失败，请认真填写所有带  * 数据';
$lang['info_unvalid_request']='非法请求';
$lang['info_error']='操作失败，请重试';
$lang['info_price_err']='銷售價不能大於市場價';
$lang['info_price_err1']='銷售價必須大於（10 / 9 *採購價）';
$lang['info_err_weight']='產品重量不合法';
$lang['info_err_purchase_price']='採購價不合法';

$lang['reset_user_pwd']='重置用戶密碼';
$lang['confirm_user_id']='请確認用戶ID';
$lang['id_not_identical']='兩次輸入的ID必須相同';
$lang['this_user_name_is']='該用戶的姓名是：';
$lang['reset_pwd_success_admin']='重置密碼成功,初始密碼為:';

/* 交易管理 */
$lang['admin_trade_title'] = '交易管理';
/* 问题反馈  */
$lang['label_feedback'] = '問題迴響';
$lang['label_feedback_email'] = 'Email';
$lang['label_feedback_userid'] = '用戶ID';
$lang['label_feedback_content'] = '内容';
$lang['label_feedback_date'] = '提交時間';
$lang['label_feedback_state'] = '狀態';
$lang['label_feedback_state_yes'] = '已處理';
$lang['label_feedback_state_no'] = '未處理';
$lang['label_feedback_change_state'] = '隱藏';
$lang['label_server'] = '售後服務';
/* 订单管理 */
$lang['admin_trade_order'] = '訂單管理';
$lang['admin_trade_order_attach'] = '主訂單 id';
$lang['admin_order_info'] = '訂單資訊';
$lang['admin_order_info_basic'] = '基本資訊';
$lang['admin_order_id'] = '訂單 id';
$lang['pc_order_id'] = '訂單 id ';
$lang['admin_order_prop'] = '訂單類型';
$lang['admin_order_prop_normal'] = '普通訂單';
$lang['admin_order_prop_component'] = '包含子訂單';
$lang['admin_order_prop_merge'] = '合單定單';
$lang['admin_order_uid'] = '顧客 id';
$lang['admin_order_customer'] = '顧客';
$lang['admin_order_store_id'] = '店鋪 id';
$lang['admin_order_consignee'] = '收貨人';
$lang['admin_order_phone'] = '聯繫電話';
$lang['admin_order_deliver_addr'] = '收貨地址';
$lang['admin_order_zip_code'] = '郵政編碼';
$lang['admin_order_customs_clearance'] = '海關報關號';
$lang['admin_order_deliver_time'] = '送貨時間';
$lang['admin_order_expect_deliver_date'] = '預計發貨日期';
$lang['admin_order_expect_deliver_date_invalid'] = '預計發貨日期不能早於當前日期';
$lang['admin_order_info_goods'] = '商品資訊';
$lang['admin_order_goods_list'] = '商品列表';
$lang['admin_order_goods_sn'] = 'sku';
$lang['admin_order_goods_name'] = '名稱';
$lang['admin_order_goods_quantity'] = '數量';
$lang['admin_order_remark'] = '備註';
$lang['admin_order_info_pay'] = '支付信息';
$lang['admin_order_receipt'] = '是否需要收據';
$lang['admin_order_receipt_0'] = '不需要';
$lang['admin_order_receipt_1'] = '需要';
$lang['admin_order_currency'] = '貨幣';
$lang['admin_order_rate'] = '匯率';
$lang['admin_order_goods_amount'] = '商品總計';
$lang['admin_order_deliver_fee'] = '運費';
$lang['admin_order_amount'] = '實付金額';
$lang['admin_order_amount_usd'] = '實付金額（美元）';
$lang['admin_order_profit_usd'] = '利潤（美元）';
$lang['admin_order_payment'] = '支付方式';
$lang['admin_order_payment_unpay'] = '未支付';
$lang['admin_order_payment_group'] = '預付款';
$lang['admin_order_payment_coupon'] = '代品券換購';
$lang['admin_order_payment_alipay'] = '支付寶';
$lang['admin_order_payment_unionpay'] = '銀聯支付';
$lang['admin_order_payment_paypal'] = 'PayPal';
$lang['admin_order_payment_ewallet'] = 'eWallet';
$lang['admin_order_payment_yspay'] = '銀盛支付';
$lang['admin_order_payment_amount'] = '餘額支付';
$lang['admin_order_pay_time'] = '支付時間';
$lang['admin_order_notify_num'] = '接口回調次數';
$lang['admin_order_pay_txn_id'] = '第三方交易號';
$lang['admin_order_info_status'] = '狀態信息';
$lang['admin_order_info_create_time'] = '創建時間';
$lang['admin_order_info_freight'] = '收貨信息';
$lang['admin_order_info_deliver_time'] = '發貨時間';
$lang['admin_order_info_receive_time'] = '收貨時間';
$lang['admin_order_info_update_time'] = '更新時間';
$lang['admin_order_status'] = '訂單狀態';
$lang['admin_order_status_all'] = '所有狀態';
$lang['admin_order_status_init'] = '正在發貨中';
$lang['admin_order_status_checkout'] = '等待付款';
$lang['admin_order_status_paied'] = '等待發貨';
$lang['admin_order_status_delivered'] = '等待收貨';
$lang['admin_order_status_arrival'] = '等待評價';
$lang['admin_order_status_finish'] = '已完成';
$lang['admin_order_status_returning'] = '退貨中';
$lang['admin_order_status_holding'] = '凍結';
$lang['admin_order_status_refund'] = '退貨完成';
$lang['admin_order_refund'] = '退貨';
$lang['admin_order_status_cancel'] = '訂單取消';
$lang['admin_order_status_component'] = '已拆分';
$lang['admin_order_status_doba_exception'] = 'doba异常訂單';
$lang['admin_order_operate'] = '操作';
$lang['admin_order_operate_deliver'] = '確認發貨';
$lang['admin_order_confirm_cancel'] = '一旦取消訂單狀態將不可逆轉，是否確定取消訂單？';
$lang['admin_order_cancel_confirm'] = '訂單取消確認';
$lang['admin_order_cancel'] = '取消';
$lang['admin_order_deliver_box_title'] = '填寫發貨信息';
$lang['admin_order_deliver_box_id'] = '快遞信息';
$lang['admin_order_tracking_num'] = '運單號';
$lang['admin_order_remark_system'] = '系統備註';
$lang['admin_order_customer_remark'] = '顧客可見備註';
$lang['admin_order_customer_remark_add'] = "添加顧客可見備註";
$lang['admin_order_system_remark'] = '後台系統可見備註';
$lang['admin_order_system_remark_add'] = "添加後台系統可見備註";
$lang['admin_order_remark_operator'] = '後台操作人員';
$lang['admin_order_remark_create_time'] = '創建時間';
$lang['admin_order_expect_deliver_date'] = '預計發貨時間';
$lang['admin_order_shipping_print'] = '打印快遞單';
$lang['set_except_group']='設置免套餐會員';
$lang['id_format_is_not_correct']='ID格式不正確';
$lang['admin_doba_order_fix'] = '手动获取doba信息';
$lang['admin_doba_order_id'] = 'Doba ID';
$lang['admin_doba_order_request'] = '获取信息';
$lang['admin_doba_order_request_succ'] = '获取信息成功';

/* 订单修复 */
$lang['admin_trade_repair'] = '訂單修復';
$lang['admin_trade_repair_modify'] = '資訊修改';
$lang['admin_trade_repair_component'] = '手動拆單';
$lang['admin_trade_repair_rollback'] = '狀態回滾';
$lang['admin_trade_repair_addnumber'] = '手動添加交易號';
$lang['orderid_not_null'] = '訂單號不能為空';
$lang['txnid_not_null'] = '交易號不能為空';
$lang['orderid_not_exits'] = '該訂單號無效';
$lang['orderid_ture'] = '添加成功';
$lang['admin_trade_repair_number'] = '交易號';

/* 导入订单 */
$lang['admin_trade_order_import'] = '導入訂單數據';

//重置選購套裝
$lang['not_find_this_order']='沒有發現該用戶的選購訂單';
$lang['this_order_not_reset']='只有待發貨的訂單才可以重置';
$lang['reset_choose_group_success']='重置訂單成功';
$lang['this_user_not_choose']='該用戶還沒選購,不需要重置';
$lang['this_user_upgrade_not_reset']='該用戶在換購後進行過升級店鋪操作,不能重置';
$lang['reset_group']='重置訂單';
$lang['reset_upgrade_group']='重置升級訂單';
$lang['not_find_this_upgrade_order']='沒有發現該用護的升級訂單';
$lang['reset_type']='重置類型';
$lang['you_use_coupons_not_can_reset']='代品券不足,不能重置';
$lang['order_a_timeout_not_can_reset']='訂單已經超過三天,不能重置';
$lang['this_user_have_more_than_once_upgrade_record']="該用戶為階段性升級,不能重置訂單";

/*导入第三方订单*/
$lang['import_third_part_orders'] = '導入第三方訂單';

/* 供应商系统 相关 */
$lang['sys_supplier_title']='TPS供應商管理系統';
$lang['coupons_manage']='代品券管理';
$lang['coupons_add_or_reduce']='代品券增減';
$lang['voucher']='代品券';

$lang['orderid_default_config'] = '默認配置';
$lang['orderid_users_config'] = '參數配置';
$lang['orderid_freight_info_cv'] = '運單號覆蓋';
$lang['orderid_freight_info_null'] = '運單號清空';

$lang['voucher_not_null']='請輸入代品券金額';
$lang['remark_not_null']='請輸入原因';
$lang['please_enter_correct_voucher']='請輸入正確的代品券金額';

$lang['voucher_value']='代品券金額';

$lang['order_not_exits']='該訂單不存在';
$lang['order_id_not_null']='訂單號不能為空';
$lang['this_order_is_choose_order']='該訂單是選購訂單,顧客id是:';
$lang['this_order_is_upgrade_order']='該訂單是升級訂單,顧客id是:';
$lang['this_order_is_basic_order']='該訂單是普通訂單,不能重置';
$lang['check_order_type']='檢測訂單類型';

$lang['refund'] = '退款';
$lang['no_refund'] = '不退款';
$lang['no_cancel_order'] = '订单已被导出，取消时需要跟运营部确认订单是否发货,谨慎操作！！！';
$lang['order_refund'] = '訂單退款';
$lang['refund_coupons']='退還代品券';
$lang['only_cancel']='僅取消';

/** paypal 查询 */
$lang['admin_paypal_failure_search'] = '貝寶退款退款/撤銷訂單查詢';
$lang['admin_paypal_failure_list'] = '貝寶退款退款/撤銷訂單列表';

/* 运营系统 - 仓库管理 */
$lang['admin_oper_storehouse_ALL'] = "所有倉庫";
$lang['admin_oper_shipper_ALL'] = "所有發貨商";
$lang['admin_oper_storehouse_CNSZ'] = "中國深圳倉庫";
$lang['admin_oper_storehouse_CNHK'] = "中國香港倉庫";
$lang['admin_oper_storehouse_USATL'] = "美國亞特蘭大倉庫";
$lang['admin_oper_storehouse_USANOPAL'] = "美國NOPAL倉庫";
$lang['admin_oper_storehouse_USAJBB'] = "美國JBB倉庫";
$lang['admin_oper_storehouse_USANI'] = "美國Grace of Graviola倉庫";
$lang['admin_oper_storehouse_USASAMTJ'] = "美國Epic Sam LLC仓库";
$lang['admin_oper_storehouse_USAIE'] = "美國 Insight Eye 倉庫";

$lang['admin_oper_storehouse_KRSL'] = "韓國首爾倉庫";
$lang['admin_oper_storehouse_KRKK'] = "韓國首爾-硫磺葡萄酒皂倉庫";
$lang['admin_oper_storehouse_KRCPC'] = "韓國首爾-99美白倉庫";
$lang['admin_oper_storehouse_KRFHC'] = "韓國西兰花粉 + 康复天使倉庫";
$lang['admin_oper_storehouse_KRSSL'] = "韓國减肥粉倉庫";
$lang['admin_oper_storehouse_KRWM'] = "韓國净水器倉庫";
$lang['admin_oper_storehouse_KRFLX'] = "韓國Florex倉庫";
$lang['admin_oper_storehouse_KRHCL'] = "韓國牙膏，护膝倉庫";
$lang['admin_oper_storehouse_KRPS'] = "韓國Ssophyya倉庫";
$lang['admin_oper_storehouse_KRG'] = "韓國 Ginseng 倉庫";
$lang['admin_oper_storehouse_KRDC'] = "韓國 Dr. Cell 倉庫";
$lang['admin_oper_storehouse_KRSSD'] = "韓國 Seng Seng Dan 倉庫";
$lang['admin_oper_storehouse_KRKSCG'] = "韓國东方医学粉+KAMIJOA染发膏+Si-Lite牙膏倉庫";
$lang['admin_oper_storehouse_KRCG'] = "韓國染发+Cheongin gold倉庫";

$lang['admin_oper_storehouse_CNXY'] = "中國熙媛倉庫（供應商）";
$lang['admin_oper_storehouse_CNZYP'] = "中國尊譽品-大米倉庫（供應商）";
$lang['admin_oper_storehouse_CNGWM'] = "中國國威銘-紅酒倉庫（供應商）";
$lang['admin_oper_storehouse_CNLT'] = "中國绿糖-奶瓶倉庫（供應商）";
$lang['admin_oper_storehouse_CNFJ'] = "中國富嘉-公仔,抱枕倉庫（供應商）";
$lang['admin_oper_storehouse_CNWD'] = "中國問鼎-西北特產倉庫（供應商）";
$lang['admin_oper_storehouse_CNMY'] = "中國航远-紅酒倉庫（供應商）";
$lang['admin_oper_storehouse_CNFM'] = "中國福麦五谷倉庫（供應商）";
$lang['admin_oper_storehouse_CNKSD'] = "中國凯盛达倉庫（供應商）";
$lang['admin_oper_storehouse_CNJGH'] = "中國巾帼汇-茅台酒倉庫（供應商）";
$lang['admin_oper_storehouse_CNTFT'] = "中國天福堂倉庫（供應商）";
$lang['admin_oper_storehouse_CNJGH1'] = "中國巾帼汇-玛咖倉庫（供應商）";
$lang['admin_oper_storehouse_CNFFMY'] = "中國黑龍江五发米业倉庫（供應商）";
$lang['admin_oper_storehouse_CNYG'] = "中國北京阳光庄苑倉庫（供應商）";
$lang['admin_oper_storehouse_CNYHM'] = "中國深圳市亿海铭倉庫（供應商）";
$lang['admin_oper_storehouse_CNBDS'] = "中國深圳市班德施-咖啡酵素倉庫（供應商）";
$lang['admin_oper_storehouse_CNZSH'] = "中國宜兴紫砂壶倉庫（供應商）";
$lang['admin_oper_storehouse_CNYP'] = "中國雅培倉庫（供應商）";
$lang['admin_oper_storehouse_CNJJ'] = "中國金绛食品倉庫（供應商）";
$lang['admin_oper_storehouse_CNSL'] = "中國深圳市狮龙倉庫（供應商）";
$lang['admin_oper_storehouse_CNWLL'] = "中國威利来倉庫（供應商）";
$lang['admin_oper_storehouse_CNJMD'] = "中國威海姜名都倉庫（供應商）";
$lang['admin_oper_storehouse_CNYCK'] = "中國钰诚康倉庫（供應商）";
$lang['admin_oper_storehouse_CNLHY'] = "中國绿禾源倉庫（供應商）";

$lang['admin_supplier_store_id'] = '倉庫對應供應商列表';
$lang['admin_supplier'] = '供應商';
$lang['admin_store_code'] = '倉庫';

/** 商品名称 */
$lang['admin_mini_water'] = '迷你裝淨水器';
$lang['admin_family_water'] = '家庭裝淨水器';
$lang['admin_powder'] = '減肥營養粉';
$lang['admin_flx'] = '植物軟膠囊';

/* 区域 */
$lang['zone_area_chn'] = "中國大陸";
$lang['zone_area_usa_other'] = "美國及其他地區";
$lang['zone_area_kor'] = "韓國";
$lang['zone_area_hkg_mac_twn_asean'] = "港澳台及東南亞";

$lang['fill_in_frozen_remark'] = '請輸入凍結原因';
$lang['fill_in_frozen_remark_2'] = '請輸入凍結原因，如訂單被導出請寫明與哪位運營同事確認可凍結';
$lang['lock_order_not_can_freeze'] = '鎖定的訂單不能凍結';
$lang['freeze_success'] = "凍結成功";
$lang['transaction_rollback'] = "事務回滾了";
$lang['order_remove_frozen'] = '解除凍結';
$lang['remove_frozen_success'] = '解除成功';
$lang['confirm_remove_freeze'] = '確認解除凍結?';


//訂單操作log
$lang['trade_order_logs'] = '訂單操作日志';
$lang['all_oper_code'] = '所有類型';
$lang['order_log_oper_create'] = '訂單創建';
$lang['order_log_oper_modify'] = '訂單修改';
$lang['order_log_oper_export'] = '訂單導出';
$lang['order_log_oper_diliver'] = '訂單發貨';
$lang['order_log_oper_reset'] = '訂單重置';
$lang['order_log_oper_rollback'] = '訂單回滾';
$lang['order_log_oper_cancel'] = '訂單取消';
$lang['order_log_oper_frozen'] = '訂單凍結';
$lang['order_log_oper_unfrozen'] = '訂單解除凍結';
$lang['order_log_oper_addr_edit'] = '訂單地址修改';
$lang['order_log_oper_erpmodify'] = '訂單信息修改';
$lang['order_log_oper_suit'] = '產品套裝的訂單狀態變更';
$lang['order_log_oper_recovery'] = '訂單恢復';
$lang['order_log_oper_exchange'] = '订单换货';

$lang['operator_id'] = '操作人ID';
$lang['update_time'] = '操作時間';

$lang['load_finish'] = '沒有更多了';
$lang['load_more'] = '點擊加載更多';

$lang['this_user_not_sort'] = '該用戶還未進入排序';
$lang['the_number_of_matrix'] = '矩陣人數:';

//后台执行sql入口
$lang['execute_sql'] = '執行SQL語句';
$lang['please_enter_sql'] = '請輸入SQL語句,多條語句用分號分隔';
$lang['please_enter_remark'] = '请输入详细的备注';

//跨区运费
$lang['international_freight'] = '商品跨區運費';
$lang['goods_sku'] = '商品SKU';
$lang['please_input_freight_usd'] = '請輸入運費,單位(美元),不允許購買的地區請設置成-1';
$lang['please_input_right_sku'] = '請輸入正確的SKU';
$lang['freight_must_is_number'] = '運費必須爲數字';
$lang['not_find_this_goods_name'] = '未發現該商品名稱';

//^^^^
$lang['all_country'] = "所有地區";
$lang['sql_source'] = 'SQL源碼';
$lang['system_setting'] = '系統管理';

$lang['all_status'] = '全部狀態';
$lang['awaiting_processing'] = '待處理';
$lang['has_been_completed'] = '完成';
$lang['refuse'] = '駁回';
$lang['refuse_reason'] = '駁回原因';

//月费池转现金池日志列表
$lang['old_month_fee_pool'] = '轉之前的月費池';
$lang['new_month_fee_pool'] = '轉之後的月費池';
$lang['cash'] = '轉入金額';


$lang['product_freight_delete'] = '删除商品跨區運費';
$lang['label_country']='國家';
$lang['not_find_this_product_freight'] = '未發現該紀錄';
$lang['product_freight_not_be'] = '運費必須是大於等於0的整數';
$lang['delete_success'] = '删除成功';
$lang['delete_failure'] = '删除失敗';
$lang['is_delete'] = '一旦删除不可逆轉，是否確認删除該運費？';

$lang['delete_ok'] = '删除運費確認';

$lang['choose_group'] = '選購訂單 ';
$lang['generation_group'] = '代品劵訂單 ';
$lang['retail_group'] = '零售訂單';
$lang['all_group'] = '所有類型訂單 ';

$lang['goods_number_exception'] = '商品庫存异常記錄';
$lang['number_zh'] = '簡體中文庫存';
$lang['number_hk'] = '繁體中文庫存';
$lang['number_english'] = '英文庫存';
$lang['number_kr'] = '韓文庫存';
$lang['number_null'] = '該語種無庫存  ';


/*后台文件管理*/
$lang['admin_ads_file_manage'] = '文件管理';
$lang['admin_file_type'] = '文件類型';
$lang['admin_file_announcement'] = '公告文件';
$lang['admin_file_regime'] = '制度';
$lang['admin_commission_explain'] = '佣金說明';
$lang['admin_file_is_show'] = '是否顯示';
$lang['file_is_show'] = '顯示';
$lang['file_is_hide'] = '隱藏';
$lang['admin_file_name'] = '文件名稱';
$lang['admin_ads_file_add'] = '添加文件';
$lang['admin_ads_file_modify'] = '修改文件';
$lang['admin_file_empty'] = '文件不能為空';
$lang['admin_file_name_empty'] = '文件名不能為空';
$lang['admin_file_limit_10m'] = '大小超過10M';
$lang['admin_file_name_limit_100'] = '文件長度超過100';
$lang['admin_file_upload_fail'] = '上傳失敗';
$lang['admin_file_type_empty'] = '文件類型不能為空';
$lang['admin_file_delete_success'] = '刪除成功';
$lang['admin_file_delete_fail'] = '刪除失敗';
$lang['admin_file_update_success'] = '修改成功';
$lang['admin_file_update_fail'] = '修改失敗';
$lang['admin_file_add_success'] = '添加成功';
$lang['admin_file_add_fail'] = '添加失敗';
$lang['delete_admin_file'] = '刪除文件';
$lang['admin_file_modify'] = '編輯';
$lang['admin_file_submit_error'] = '請勿重複提交';
$lang['admin_file_area'] = '區&nbsp;&nbsp;域';
$lang['admin_file_area_empty'] = '區域不能為空';

/*知识库管理*/
$lang['admin_knowledge'] = '知識庫';
$lang['admin_knowledge_manage'] = '知識庫管理';
$lang['admin_knowledge_cate_manage'] = '知識庫分類管理';
$lang['admin_knowledge_title'] = '標題';
$lang['admin_knowledge_cate'] = '知識庫分類';
$lang['admin_knowledge_add'] = '知識庫添加';
$lang['admin_knowledge_cate_add'] = '知識庫分類添加';
$lang['edit'] = '編輯';
$lang['success'] = '成功';
$lang['modify_user'] = '更新人';
$lang['admin_knowledge_success'] = '操作成功，确定转向列表页，取消则继续操作';

/*会员个人中心文件下载*/
$lang['file_download'] = '文件下載';

/** 客服中心 start*/
$lang['tickets_center'] = '客服中心';
$lang['history_tickets'] = '歷史工單';
$lang['my_tickets'] = '我的工單';
$lang['all_tickets'] = '全部工單';
$lang['add_tickets']= '新建工單';
$lang['unassigned_tickets'] = '未分配工單';
$lang['unassigned_tickets_count'] = '未分配';
$lang['unprocessed_tickets_count'] = '未處理';
$lang['tickets_id'] = '工單號';
$lang['tickets_sender'] = '發件人';
$lang['tickets_closed_can_not_reply'] = '工單已關閉，不能回复!';
$lang['tickets_reply'] = '工單回复';
$lang['org_tickets_info'] = '原始工單信息';
$lang['customer'] = '客服';
$lang['member'] = '會員';
$lang['member_id'] = '會員ID';
$lang['tickets_language'] = '語言';
$lang['pls_t_uid'] = '請輸入會員ID';
$lang['pls_t_correct_ID'] = '請輸入正確的會員ID';
$lang['tickets_score_num'] = '評分數';

$lang['tickets_title'] = '工單標題';
$lang['assign_to_me'] = '標記在我名下';
$lang['tickets_language'] = '語言';
$lang['assign_success'] = '標記成功';
$lang['assign_fail'] = '標記失敗';
$lang['view_ticket_detail'] = '查看詳情';
$lang['view_and_change'] = '查看 / 修改';
$lang['close_tickets'] = '關閉工單';
$lang['view_tickets_log'] = '查看日誌';
$lang['confirm_close_tickets'] = '你確定要關閉工單嗎？';
$lang['close_tickets_success'] = '工單已關閉';
$lang['close_tickets_fail'] = '工單關閉失敗';
$lang['tickets_content'] = '工單問題描述';
$lang['picture_not_exist'] = '圖片不存在';
$lang['tickets_no_exist'] = '抱歉，工单不存在';
$lang['attach_no_exist'] = '抱歉，附件不存在';
$lang['log_no_exist'] = '抱歉，沒有該工單日誌';
$lang['log_info'] = '日誌詳情';
$lang['tickets_take_time'] = '工單歷史';
$lang['day'] = '日';
$lang['hour'] = '小時';
$lang['minute'] = '分鐘';
$lang['second'] = '秒';
$lang['tickets_handler'] = '操作者';
$lang['modified_type'] = '修改的類型';
$lang['old_data'] = '舊值';
$lang['new_data'] = '新值';
$lang['add_new_tickets'] = '新建工單';
$lang['new_tickets'] = '新工單';
$lang['new_msg'] = '新消息';

$lang['t_template_name'] = '模板名稱';
$lang['t_template_content'] = '模板內容';
$lang['t_template_type'] = '模板類型';
$lang['tickets_template']= '信息模板';
$lang['pls_t_t_name'] = '請輸入模板名稱';
$lang['pls_t_t_content'] = '請輸入模板內容';
$lang['add_tickets_template'] = '添加自定義模板';
$lang['is_public']='是否公開';
$lang['template_author'] = '作者';
$lang['template_name'] = '模板名稱';
$lang['template_is_public'] = '是';
$lang['template_not_public'] = '否';
$lang['template_forbid'] = '禁用';
$lang['add_template_success'] = '添加成功';
$lang['add_template_fail'] = '添加失敗';
$lang['confirm_update_template'] = '確定修改模板？';
$lang['update_template_success'] = '模板修改成功';
$lang['update_template_fail'] = '模板修改失敗';
$lang['confirm_delete_template'] = '確定刪除模板？';
$lang['delete_template_success'] = '删除成功';
$lang['delete_template_fail'] = '删除失败';

/**黑名单**/
$lang['tickets_black_list'] = '黑名單';
$lang['black_uid'] = '會員ID';
$lang['tickets_black'] = '(黑)';
$lang['confirm_delete_black_list'] = '確定從黑名單移除嗎？';
$lang['update_black_list_success'] = '已從黑名單中移除';
$lang['update_black_list_fail'] = '移除失敗';
$lang['add_black_list_success'] = '已加入黑名單';
$lang['black_list_exist'] = '添加失敗，該ID已在黑名單清單';
$lang['add_black_list_fail'] = '加入失敗';

$lang['manual_work'] = '手動';
$lang['automatic'] = '自動';
$lang['tickets_cus_leave'] = '客服 %s 已請假';
$lang['tickets_cus_work'] = '客服 %s 已正常上班';
$lang['change_status_fail']='客服 %s 工作狀態改變失敗';
$lang['tickets_auto_assign'] = '系統已經設置為自動分配';
$lang['tickets_hand_assign'] = '系統已經設置為手動分配';
$lang['tickets_auto_assign_fail'] = '分配設置失敗';

$lang['tickets_status'] = '工單狀態';
$lang['tickets_priority'] = '工單優先級';
$lang['modified_manager'] = '工單轉移';
$lang['tickets_assign'] = '工單分配';
$lang['submit_as'] = '提交為';
$lang['submit_as_waiting_reply'] = '待回應';
$lang['submit_as_waiting_discuss'] = '待商議';
$lang['add_tickets_tips'] = '註釋';
$lang['tickets_send_fail'] = '信息發送失敗';
$lang['tickets_send_success'] = '信息發送成功';
$lang['apply_close_tickets'] = '申請關閉工單';
$lang['view_tickets'] = '查看工單';
$lang['r_waiting_reply'] = '回复-待回應';
$lang['r_waiting_discuss'] = '回复-待商議';
$lang['tickets_tips']='註釋';
$lang['r_tickets_resolved'] = '回复-已解決';
$lang['auto_reply_tickets'] = '自動回复';
$lang['close_tickets_send_email'] = '發送郵件';
$lang['auto_close_tickets'] = '自動關閉工單';

$lang['tickets_label'] = '註釋';
$lang['pls_input_tips'] = '請輸入註釋';
$lang['no_tips'] = '沒有註釋';
$lang['add_tips_success'] = '添加註釋成功';
$lang['add_tips_fail'] = '添加註釋失敗';

$lang['tickets_type'] = '工單問題類型';
$lang['add_and_quit'] = '加入/退出';
$lang['join_issue'] = '賬戶信息問題';
$lang['quit_issue'] = '降級/退出申請';
$lang['up_or_down_grade'] = '升級/支付問題';
$lang['monthly_fee_problem'] = '月費問題';
$lang['platform_fee_problem'] = '平台管理費';
$lang['reward_system'] = '獎勵制度';
$lang['product_recommendation'] = '產品推薦';
$lang['shop_transfer'] = '店鋪轉讓';
$lang['commission_problem'] = '佣金問題';
$lang['order_problem'] = '訂單問題';
$lang['freight_problem'] = '運費問題投訴';
$lang['withdraw_funds_problem'] = '提現問題';
$lang['walhao_store'] = '沃好商城';
$lang ['tickets_check_order_status'] ='催貨';
$lang ['tickets_change_delivery_information'] ='更改收貨信息';
$lang ['tickets_order_cancellation'] ='取消訂單';
$lang ['tickets_product_review'] ='產品投訴';
$lang ['tickets_member_suggestions'] ='會員建議';
$lang['other'] = '其他';
$lang['tickets_after_sales_problem'] = '售後問題';
$lang['shipping_logistics_problems'] = '催貨/物流問題';
$lang['tickets_product_damage'] = '產品破損';
$lang['tickets_leakage_wrong_product'] = '產品錯發/漏發';

$lang['pls_t_type'] = '請選擇問題分類';
$lang['pls_t_title'] = '請輸入工單標題';
$lang['pls_t_tid'] = '請輸入工單號';
$lang['pls_t_content'] = '請輸入工單描述';
$lang['exceed_words_limit'] = '超過字數限制';
$lang['pls_t_uid_aid'] = '請輸入會員ID/客服ID';
$lang['pls_t_tid_uid'] = '請輸入工單號/會員ID';
$lang['remain_'] = '還可以輸入';
$lang['max_limit_'] = '可以輸入';
$lang['_words'] = '字';
$lang['pls_input_reply_content'] = '請輸入回复內容';
$lang['tickets_info'] = '工單詳情';

$lang['pls_t_status'] = '請選擇狀態';
$lang['pls_t_priority'] = '請選擇優先級';
$lang['tickets_status'] = '工單狀態';
$lang['new_ticket'] = '新建';
$lang['open_ticket'] = '已開啟';
$lang['waiting_reply'] = '待回應';
$lang['waiting_discuss'] = '待商議';
$lang['ticket_resolved'] = '已解決';
$lang['had_graded'] = '已評分';
$lang['apply_close'] = '申請關閉';
$lang['had_apply_tickets'] = '已申請關閉';
$lang['tickets_priority'] = '工單優先級';
$lang['priority'] = '優先級';
$lang['reply'] = '回复';
$lang['general_tickets'] = '一般';
$lang['preferential_tickets'] = '優先';
$lang['urgent_tickets'] = '緊急';
$lang['change_tickets_priority_fail'] = '優先級修改失敗';
$lang['change_tickets_priority_success'] = '優先級修改成功';
$lang['tickets_transfer'] = '工單轉移';
$lang['transfer_tickets_fail'] = '工單轉移失敗';
$lang['transfer_tickets_success'] = '工單轉移成功';
$lang['change_status_success'] = '狀態修改成功';
$lang['change_status_fail'] = '狀態修改失敗';
$lang['pls_select_customer'] = '請選擇客服';
$lang['change_type_success'] = '修改類型成功';
$lang['change_type_fail'] = '修改類型失敗';
$lang['change_tickets_type']='修改問題類型';
$lang['search_data'] = '請輸入搜索條件查詢';

$lang['tickets_statistics'] = '工單統計';
$lang['cus_id'] = '客服ID';
$lang['pls_cus_id'] = '請輸入客服ID';
$lang['tickets_statistics_time'] = '統計時間';
$lang['num_id'] = '序號';
$lang['cus_name'] = '姓名';
$lang['today_in_tickets'] = '分配工單';
$lang['today_out_tickets'] = '轉出工單';
$lang['today_unprocessed_tickets'] = '當天新工單未處理';
$lang['today_tickets_count'] = '當天工單總數';
$lang['all_unprocessed_tickets_count'] = '全部未處理新工單';
$lang['new_msg_tickets'] = '新消息工單';
$lang['waiting_discuss_tickets'] = '待商議工單';
$lang['waiting_reply_tickets'] = '待回應工單';
$lang['all_tickets_count'] = '工單總量';

$lang['tickets_customer_role'] = '客服賬號管理';
$lang['tickets_customer_role_1'] = '客服';
$lang['tickets_customer_role_2'] = '節假日值班經理';
$lang['tickets_customer_permission'] = '權限';
$lang['job_number']='編號';
$lang['confirm_update_customer_1'] = '確定權限修改為 節假日值班經理?';
$lang['confirm_update_customer_2'] = '確定權限修改為 客服?';
$lang['customer_role_invalid_action'] = '無效操作';

$lang['tickets_area_usa'] = '美國區域';
$lang['tickets_area_china'] = '中國區域';
$lang['tickets_area_hk'] = '香港區域';
$lang['tickets_area_korea'] = '韓國區域';
$lang['unique_job_number'] = '客服工號已存在';
$lang['job_number_error'] ='客服工號為三位數字';
$lang['assign_cus_job_number'] = '分配客服工號';
$lang['cus_job_number'] = '工號';
$lang['not_customer'] = '非客服';

$lang['button_text'] = '選擇附件';
$lang['is_exists'] = '文件已存在';
$lang['remain_upload_limit'] = '選定的文件數超過上傳剩餘數量';
$lang['queue_size_limit'] = '選定的文件的數目超過了隊列的數量';
$lang['exceeds_size_limit'] = '文件超過大小限制';
$lang['is_empty'] = '文件不能為空';
$lang['not_accepted_type'] = '不允許上傳的文件格式';
$lang['upload_limit_reached'] = '已達到上傳限制';
$lang['attach_delete_success'] = '移除成功';
$lang['attach_no_permissions'] = '對不起，您的權限不夠';
$lang['attach_cannot_find'] = '找不到文件';
$lang['not_support_mobile_upload'] = '不支持手機上傳附件';
/**售后中心 end **/

$lang['user_email_exception_list'] = '用戶收發郵件例外清單 ';
$lang['uid_not_null'] = '用戶id不能為空 ';
$lang['uid_ture'] = '用戶id已存在';

$lang['is_uid_delete'] = '一旦删除不可逆轉，是否確認删除該記錄？';
$lang['is_delete_uid'] = '删除用戶確認';
$lang['process_num'] = '處理次數';
$lang['cron_doing'] = '腳本任務管理 ';
$lang['cron_name'] = '計畫任務名稱 ';
$lang['false_count'] = '次數返回false';

$lang['order_not_accord_with'] = '該訂單不符合回滾條件';
//手动添加138佣金合格人数
$lang['user_qualified'] = '138傭金合格人數';
$lang['add_user_qualified'] = '添加138傭金合格人數';
$lang['commission_number'] = '傭金';
$lang['commission_isok'] = '確認添加';

//手动添加doba订单
$lang['admin_trade_repair_adddaba'] = '手動推送doba訂單';
$lang['admin_trade_isdoba'] = '該訂單已推送成功，無需重複推送';
$lang['admin_trade_doba_nopush'] = '訂單無需推送 ';

//用户状态变更记录
$lang['users_status_log'] = '會員狀態變更記錄';
$lang['users_status_front'] = '變更前的狀態 ';
$lang['users_status_back'] = '變更後的狀態';
$lang['buckle_fee'] = '繳納月費';
$lang['order_fee'] = '訂單抵扣月費';
$lang['buckle_fee_error'] = '未繳納月費';

//佣金管理查询列表
$lang['operator_email'] = '操作人郵箱';
$lang['user_oneself_del'] = '會員自己删除';
$lang['is_certificate'] = '發放代品券';

//佣金查询
$lang['no_time'] = '開始日期和結束日期不能跨月份査詢';
$lang['no_search'] = '請輸入用戶ID並査詢';
$lang['no_time_null'] = '開始日期不能為空';
$lang['is_certificate'] = '發放代品券';
$lang['limit_query_month'] = '抱歉,當前僅支持您查詢當月的數據!';

//活动抵扣月费
$lang['delPlan_title'] = '删除會員活動記錄';
$lang['not_join_action_charge_month'] = '該會員沒有參加活動抵扣月費計畫';

$lang['not_Porder'] = '此訂單為P開頭的主訂單，不允許修復，請修復子訂單';

$lang['not_Porder'] = '不允許修復P開頭的零售訂單和不允許修復C開頭的陞級訂單或代品券訂單';
$lang['not_repeat_insert'] = '該訂單號已添加過，等待處理';
$lang['admin_file_order_freight_error'] = '%s快遞公司寫法有誤或不能為空';
$lang['admin_file_order_show'] = '*注：快遞公司一欄只允許填寫0或大於0的數位，0為自定義，如一個訂單兩個快遞公司的，快遞公司一欄填寫0，快遞號一欄填寫快遞公司名稱和單號！';
$lang['order_rollback_show'] = '*注：此功能只適用於等待收貨回滾到等待發貨！';
$lang['admin_trade_repair_recovery'] = '恢復訂單';
$lang['admin_trade_feright_modify'] = '運單信息修復';
$lang['order_recovery_show'] = '*注：此功能適用於被取消的零售訂單恢復到其他狀態，只改狀態和補發傭金，需要扣款的需手動去操作！';
$lang['order_modify_order_freight'] = '*註：此功能適用於供貨商提供的物流公司和運單號錯誤，程序只修改物流信息，上傳文件時,請清除掉列[快遞號]的數學公式，否則物流單號將出現數學公式，同時註意文件格式,正確的文件如下圖所示！';
$lang['order_not_recovery'] = '只有取消或退貨的零售訂單才能恢復';
$lang['admin_order_status_revert'] = '恢復到';
$lang['admin_order_commission'] = '補發傭金 ';
$lang['admin_order_not_logistics'] = '該訂單物流資訊為空，不能恢復到該狀態！';
$lang['all_express'] = '所有快递公司';
$lang['admin_order_repeat'] = '%s快遞單號重複';
$lang['admin_repeat_data']  = '處理失敗，重複導入數據';

$lang['admin_order_status_holding_exchange'] = '凍結（待換貨）';
$lang['allow_exchange'] = '允許換貨';
$lang['ok_cancel'] = '確定取消';
$lang['cancel_exchange'] = '取消換貨';
$lang['go_exchange'] = '去換貨';
$lang['exchange_order'] = '換貨訂單';
$lang['exchange_remaining_time'] = '剩餘';
$lang['exchanging'] = '換貨中';
$lang['exchange_timeout'] = '換貨超時';
$lang['exchange_timeout_msg'] = '72小時內未完成換貨，系統自動取消';
$lang['cancel_exchange_confirm_msg'] = '您取消換貨之後，該陞級訂單將不能再次換貨！';
$lang['exchange_timer_reset'] = '此操作會重置去換貨計時器，您確定要執行此操作嗎？';
//修复分红
$lang['daily_bonus_month_error'] = "日分紅不能跨月";
$lang['add_to_cur_month_queue'] = "加入當月發獎隊列";
$lang['user_has_in_queue'] = "用戶已經在發獎隊列";
$lang['user_not_match_daily_bonus'] = "用戶不滿足全球日分紅";
$lang['please_select_queue_time'] = "請選擇加入隊列時間";
$lang['user_order_not_match_new_bonus'] = "用戶訂單不滿足新會員獎";
$lang['reward_user_bonus'] = "補發會員佣金";
$lang['daily_bonus_failed_not_set'] = "日分紅發放失敗，未設置發放比例";
$lang['daily_bonus_failed_not_set_1'] = "日分紅發放失敗，分紅比例不能超過1";
$lang['not_found_this_day_profit'] = "未找到這天的全球利潤";
$lang['daily_bonus_profit_not_enough'] = "日分紅利潤不足";
$lang['user_level_not_match'] = "用戶未升級，不滿足條件";
$lang['user_order_amount_not_match'] = "用戶已升級，但是不滿足50美金銷售額或者取消升級訂單";
$lang['new_member_bonus_failed_rate'] = "新會員專享獎發放失敗，未設置發放比例";
$lang['new_member_bonus_failed_rate_1'] = "新會員獎發放失敗，分紅比例不能超過1";
$lang['new_member_bonus_profit_not_enough'] = "新會員獎利潤不足";


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


$lang['pls_input_reson_1'] = '請填寫原因，如訂單被匯出請寫明與哪位運營同事確認可凍結';
$lang['repair_users_amount'] = '修復會員現金池';
$lang['not_repair_amount'] = '會員的現金池餘額和資金變動報表一致，該會員現金池無異常不需要修復';
$lang['user_account_total'] = '系統統計資金變動報表數額';
$lang['repair_amount'] = '修復';
$lang['user_amount_total'] = '會員當前現金池金額';
$lang['find'] = '査詢';

$lang['pls_input_reson_2'] = '請填寫原因，如訂單被匯出請寫明與哪位運營同事確認可允許換貨';