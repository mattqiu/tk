<?php
/****paypal提现****/
$lang["Receiver's account is invalid"]="Receiver's account is invalid";
$lang["Sender has insufficient funds"]="Sender has insufficient funds";
$lang["User's country is not allowed"]="User's country is not allowed";
$lang["User's credit card is not in the list of allowed countries of the gaming merchant"]="User's credit card is not in the list of allowed countries of the gaming merchant";
$lang["Cannot pay self"]="Cannot pay self";
$lang["Sender's account is locked or inactive"]="Sender's account is locked or inactive";
$lang["Receiver's account is locked or inactive"]="Receiver's account is locked or inactive";
$lang["Either the sender or receiver exceeded the transaction limit"]="Either the sender or receiver exceeded the transaction limit";
$lang["Spending limit exceeded"]="Spending limit exceeded";
$lang["User is restricted"]="User is restricted";
$lang["Negative balance"]="Negative balance";
$lang["Receiver's address is in a non-receivable country or a PayPal zero country"]="Receiver's address is in a non-receivable country or a PayPal zero country";
$lang["Invalid currency"]="Invalid currency";
$lang["Sender's address is located in a restricted State (e.g., California)"]="Sender's address is located in a restricted State (e.g., California)";
$lang["Receiver's address is located in a restricted State (e.g., California)"]="Receiver's address is located in a restricted State (e.g., California)";
$lang["Market closed and transaction is between 2 different countries"]="Market closed and transaction is between 2 different countries";
$lang["Internal error"]="PayPal wind control limit (internal error)";
$lang["Zero amount"]="Zero amount";
$lang["Receiving limit exceeded"]="Receiving limit exceeded";
$lang["Duplicate mass payment"]="Duplicate mass payment";
$lang["Transaction was declined"]="Transaction was declined";
$lang["Per-transaction sending limit exceeded"]="Per-transaction sending limit exceeded";
$lang["Transaction currency cannot be received by the recipient"]="Transaction currency cannot be received by the recipient";
$lang["Currency compliance"]="Currency compliance";
$lang["The mass payment was declined because the secondary user sending the mass payment has not been verified"]="The mass payment was declined because the secondary user sending the mass payment has not been verified";
$lang["Regulatory review - Pending"]="Regulatory review - Pending";
$lang["Regulatory review - Blocked"]="Regulatory review - Blocked";
$lang["Receiver is unregistered"]="Receiver is unregistered";
$lang["Receiver is unconfirmed"]="Receiver is unconfirmed";
$lang["Youth account recipient"]="Youth account recipient";
$lang["POS cumulative sending limit exceeded"]="POS cumulative sending limit exceeded";
$lang['paypal_withdrawal_list'] = 'PayPal list of withdrawals';
$lang['submit_paypal'] = 'Submit Paypal processing';
$lang['batch_modification'] = "Batch changed to 'processed' status";
$lang['Paypal Account number'] = "Paypal Account number";
$lang['Delete_user'] = "该ID存在资金变动记录，将转为公司预留账户";
$lang['paypal_pending_log'] = 'Paypal Refund Status Order Record';
$lang['paypal_pending_ts'] =' You are sure that the order has been processed! ';
$lang['paypal_pending_cl'] = 'handle';
/** 黑名单列表 */
$lang['add_blacklist'] = 'Add sensitive words';
$lang['blacklist'] = 'Sensitive words List';
$lang['blacklist_ex'] = ' Sensitive words';
$lang['enter_blacklist'] = 'Please enter sensitive words';
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

/**order modify*/

$lang['admin_trade_repair_modify'] = 'Modify information';
$lang['admin_trade_repair_component'] = 'Manually split order';
$lang['admin_trade_repair_rollback'] = 'Restore status';
$lang['admin_trade_repair_cancel_rollback'] = 'Restore cancel status';
$lang['admin_trade_repair_addnumber'] = 'Manually add transaction number';
$lang['user_up_time'] = '用户级别升级时间';
$lang['admin_user_rank'] = '用户级别';

/***奖金类型**/
$lang['pre_week_team_bonus'] = '周团队组织分红奖';
$lang['pre_month_team_bonus'] = '月团队组织分红奖';
$lang['pre_month_leader_bonus'] = '月领导组织分红奖';
$lang['pre_amount_bonus'] = '发放金额（单位：美元）';
$lang['pre_bonus_submit'] = '重新预发奖';
$lang['pre_bonus_submit_note'] = '注：点击此按钮，将重新生成预发奖，可能会影响到实际发奖，操作时，请及时联系技术人员.';
$lang['user_sale_up_time'] = '职称升级时间';
$lang['users_amount_check'] = '提现用户转账记录检测';
$lang['users_check_btn'] = '检测';
$lang['user_achievement_note'] = '注：此功能批量修复会员业绩，请按照如下图所示的格式，存放用户id.';


/***冻结账号**/
$lang['frost_user_time'] = "Length of freeze time";
$lang['day'] = "Day(s)";
$lang['frost_forever'] = "Long-term freeze";
 $lang['please_select_frost_time'] = "Choose length of freeze time";

/**会员中心*/
$lang['admin_exchange_user_email_title'] = 'Modify mailbox';
$lang['admin_exchange_user_mobile_title'] = 'Modify mobile number';
$lang['admin_exchange_user_info_content'] = 'Confirm modification?';
$lang['admin_remark_input_not_null'] = 'Remarks cannot be blank！';
$lang['admin_remark_option_name'] = 'Operator';
$lang['admin_remark_option_time'] = 'Remarks time';
$lang['user_order_achievement_repair'] = '订单延迟业绩修复';
/**会员统计*/
$lang['admin_store_statistics_total'] = 'Total';
$lang['admin_store_statistics_datetime'] = 'Date';
$lang['admin_user_level_f'] = 'Free member';
$lang['admin_user_level_b'] = 'Bronze member';
$lang['admin_user_level_s'] = 'Silver member';
$lang['admin_user_level_g'] = 'Gold member';
$lang['admin_user_level_p'] = 'Diamond member';
$lang['admin_user_level_t'] = 'Total(SUM)';
$lang['admin_everyday_level_t'] = 'Daily subtotal(SUM)';
$lang['admin_everyday_level_count_t'] = 'Total members：';
$lang['admin_everyday_level__t'] = 'Paid fee members：';
$lang['admin_month_level_t'] = 'Monthly statistics';

/*MVP颁奖报名名单*/
$lang['mvp_apply_list'] = 'MVP颁奖报名名单';
$lang['mvp_professional_title'] = '职称';
$lang['mvp_apply_time'] = '报名时间';
$lang['mvp_apply_number'] = '报名号';
$lang['pre_day_bonus'] = '日分红奖';
$lang['pre_new_user_bonus'] = '新会员专项奖';
$lang['develop_msg'] = '开发者管理';
//mvp直播授权订单
$lang['mvp_live_list'] = 'MVP直播授权订单';
$lang['pls_input_luyan_account'] = '请输入第一路演平台帐号';
$lang['luyan_account'] = '第一路演平台账号';
$lang['mvp_pay_amount'] = '支付金额';
$lang['third_party_order_id'] = '第三方交易号';
$lang['user_achievement_edit'] = '批量修复业绩';
/**公告*/
$lang['user_error_total'] = '错误数';
$lang['admin_board_title_not_null'] = 'Title cannot be blank！';
$lang['admin_board_conteng_not_null'] = 'Notice content cannot be blank！';
$lang['admin_board_english_title_err'] = 'Title in English cannot be blank！';
$lang['admin_board_chinese_title_err'] = 'Title in Chinese cannot be blank！';
$lang['admin_board_hk_title_err'] = 'Title in Traditional Chinese cannot be blank！';
$lang['admin_board_kr_title_err'] = 'Title in Korean cannot be blank！';
$lang['admin_board_en_content_err'] = 'Content in English cannot be blank！';
$lang['admin_board_zh_content_err'] = 'Content in Chinese cannot be blank！';
$lang['admin_board_hk_content_err'] = 'Content in Traditional Chinese cannot be blank！';
$lang['admin_board_kr_content_err'] = 'Content in Korean cannot be blank！';


/** 售后订单业务流程 */
$lang['admin_exchange_rate_error'] = 'Exchange rate format error';
$lang['admin_not_demote_tip'] = '铜级、银级店铺不能降级操作^^^^';
$lang['admin_go_process'] = 'Go Process';
$lang['admin_check_pass'] = 'Approve';
$lang['admin_upload_return_fee'] = 'Upload return fee orders';
$lang['admin_return_fee_tip1'] = 'Order status is abnormal';
$lang['admin_return_fee_tip2'] = 'Refund amount is greater than shipping fee.';
$lang['admin_return_fee_tip3'] = 'Shipping fee has been already refunded';
$lang['admin_return_fee_tip4'] = 'The customer with this order has cancelled membership';
$lang['admin_refund_amount'] = 'Order refund freight: $%s';
$lang['admin_add_after_sale'] = 'Create Refund/Downgrade Order';
$lang['admin_after_sale_id'] = 'Processing Number';
$lang['admin_after_sale_uid'] = 'Related member ID';
$lang['admin_after_sale_name'] = 'Related member name';
$lang['admin_after_sale_type'] = 'Request Category';
$lang['admin_after_sale_type_0'] = 'Withdraw Membership';
$lang['admin_after_sale_type_1'] = 'Downgrade';
$lang['admin_after_sale_type_2'] = 'Return Freight';
$lang['admin_after_sale_method'] = 'Method of refund';
$lang['admin_after_sale_method_0'] = 'Transfer to bank';
$lang['admin_after_sale_method_1'] = 'Transfer to Cash Bucket';
$lang['admin_after_sale_method_2'] = 'Into Alipay';
$lang['admin_after_sale_amount'] = 'Amount of refund';
$lang['refund_amount_error'] = 'Amount of refund can not be 0';
$lang['admin_after_sale_remark'] = 'Feedback';
$lang['admin_after_sale_remark_error'] = 'Feedback can not be empty';
$lang['admin_after_sale_remark_example'] = 'Member request to downgrade from Gold to Silver level';
$lang['admin_add_after_sale_list'] = 'Processing management';
$lang['admin_after_sale_demote'] = 'Downgrade';
$lang['admin_after_sale_status_0'] = 'In process';
$lang['admin_after_sale_status_1'] = 'Finish withdraw (Waiting for bank transfer)';
$lang['admin_after_sale_status_2'] = 'Completed(Refund to Cash Bucket)';
$lang['admin_after_sale_status_3'] = 'Refund through bank transfer';
$lang['admin_after_sale_status_4'] = 'Reject withedraw';
$lang['admin_after_sale_status_5'] = 'Reject refund';
$lang['admin_after_sale_status_6'] = 'Void request';
$lang['admin_as_upgrade_order'] = 'Upgrade the order';
$lang['admin_as_consumed_order'] = 'Cancelled process';
$lang['admin_as_refund'] = 'Refund process';
$lang['admin_as_not_exist'] = 'Can not find the process number';
$lang['admin_as_status_error'] = 'It is a company account. The request can not be proceeded';
$lang['admin_as_view_remark'] = 'View/Add note';
$lang['admin_as_action_log'] = 'The log of process';
$lang['admin_as_update'] = 'Edit';
$lang['admin_as_payee_no_exist'] = 'Payee\'s member ID is not exist';
$lang['admin_after_sale_amount_error'] = 'The format of refund amount is incorrect';
$lang['admin_after_sale_submit'] = 'Request to take back the commission';
$lang['admin_after_sale_repeat'] = 'The request refund is already exist';
$lang['admin_after_sale_demote_info'] = 'The request is under the processing';
$lang['admin_email_or_id'] = 'Administrator\'s ID \/ email box';
$lang['admin_as_upload_info'] = 'Upload receipt';
$lang['admin_as_del_upload_info'] = 'Delete receipt';
$lang['admin_after_sale_coupons'] = '注意：扣减代品券不足以支持降级，部分退款金额需要抵掉代品券。^^^^';
$lang['is_grant_generation'] = 'get paid on team sales commissions';
$lang['admin_after_sale_brank_name'] = '开户行不能输入特殊字符，不能为空，最多能输入50个中文字。';
$lang['admin_after_sale_brank_num'] = '银行账户只能输入数字，不能为空，最多能输入50个数字。';
$lang['admin_after_sale_brank_pop'] = '开户名只能输入数字，不能为空，最多能输入50个数字。';

/** 导入运单号 */
$lang['admin_no_lock'] = 'Unlocked Order';
$lang['admin_yes_lock'] = 'Locked Order';
$lang['admin_order_lock'] = 'Order has been locked and cannot be update';
$lang['admin_select_is_lock'] = 'Lock export orders';
$lang['admin_file_format'] = 'File format is not supported';
$lang['admin_file_data'] = 'The file data is empty';
$lang['admin_file_not_full'] = 'EXCEL %s line, the information is not complete';
$lang['admin_file_order_status'] = '%s order status mismatch';
$lang['admin_file_not_freight'] = '%s logistics number does not exist';
$lang['admin_select_file'] = 'Please select EXCEL to upload';
$lang['admin_request_failed'] = 'Request failed';
$lang['admin_upload_freight'] = 'Upload Tracking Number';
$lang['admin_scan_shipping'] = 'Scan And Delivery';
$lang['admin_scan_order_id'] = 'Order ID';
$lang['admin_scan_track_id'] = 'Tracking Number';
$lang['admin_export_orders'] = 'Export Orders';
$lang['admin_download_model'] = 'Download Model';
$lang['admin_ship_note_type'] = 'Shipping Note Type';
$lang['admin_ship_note_type1'] = 'N Days Later';
$lang['admin_ship_note_type2'] = 'Set Date';
$lang['admin_ship_note_val'] = 'Shipping Date';
$lang['admin_ship_note_val_eg'] = 'eg:5 or 2015/11/11';
$lang['add_admin'] = 'Add Account';
$lang['order_report'] = 'Order Report';
$lang['store_report'] = 'Store Report';
$lang['order_status_3'] = 'Pending';
$lang['order_status_4'] = 'Shipped';
$lang['order_status_5'] = 'Payment settlement';
$lang['refund_card_number'] = 'Refund Card Number';
$lang['transfer_card_number'] = 'Transfer Card Number';
$lang['receive_card_number'] = 'Receive Card Number';
$lang['receive_email'] = 'Receive email';
$lang['transfer_1'] = 'Transfer';
$lang['transfer_2'] = 'Refund';
$lang['china_weight_fee'] = 'China Goods Freight';
$lang['usa_weight_fee'] = 'USA Goods Freight';
$lang['shipping_com'] = 'Logistics Company';
$lang['first_line_format_error'] = 'First line is not the same';
$lang['upload_big_excel_error'] = 'Upload file too large, please split multiple files to upload';
$lang['upload_excel_fail'] = 'Upload file failed';

/** 支付方式 */
$lang['pay_name'] = 'Payment Name';
$lang['pay_desc'] = 'Payment Description';
$lang['pay_currency'] = 'Payment Currency';
$lang['payment_list'] = 'Payment List';
$lang['edit_payment'] = 'Edit Payment';
$lang['is_enabled'] = 'Is Enabled？';
$lang['not_enabled'] = 'Not Enabled';
$lang['yes_enabled'] = 'Enabled';
$lang['yspay_username'] = 'Yspay Username';
$lang['yspay_merchantname'] = 'Yspay Merchant Name';
$lang['yspay_pfxpath'] = 'Yspay Pfx Path';
$lang['yspay_pfxpassword'] = 'Yspay Pfx Password';
$lang['yspay_certpath'] = 'Yspay Cert Path';
$lang['yspay_host'] = 'Yspay Host';
$lang['unionpay_merId'] = 'Unionpay MerId';
$lang['unionpay_pfxpath'] = 'Unionpay Pfx Path';
$lang['unionpay_pfxpassword'] = 'Unionpay Pfx Password';
$lang['unionpay_certpath'] = 'Unionpay Cert Path';
$lang['unionpay_host'] = 'Unionpay Host';
$lang['paypal_account'] = 'Paypal Account';
$lang['paypal_submit_url'] = 'Paypal Submit Url';
$lang['paypal_host'] = 'Paypal Host';
$lang['ewallet_key'] = 'eWallet Key';
$lang['ewallet_password'] = 'eWallet Password';
$lang['ewallet_host'] = 'eWallet Host';
$lang['ewallet_login'] = 'eWallet Login';
$lang['alipay_account'] = 'Alipay Account';
$lang['alipay_key'] = 'Alipay Key';
$lang['alipay_partner'] = 'Alipay Partner';

$lang['old_month'] = 'Old Monthly Fee Pool';
$lang['user_not_free'] = 'Users must be free and no branch';
$lang['delete_free_user'] = 'Delete Free User';
$lang['half_year_exe'] = 'Can\'t use this operation within six months';
$lang['new_month'] = 'End-of-Day Available Balance';
$lang['money_update'] = 'Update Amount';
$lang['month_type_1'] = 'Recharge';
$lang['month_type_4'] = 'Pay monthly fee';
$lang['action_charge_month'] = 'Initiatives to waive past due monthly fee(s)';
$lang['join_action_charge_month'] = 'You have joined the "Purchase-To-Offset Monthly Fee" plan.';
$lang['action_charge_month_tip'] = 'System: The retail orders for "the Initiatives to waive past due monthly fee(s)" can\'t be cancelled or returned';
$lang['monthly_fee_detail'] = 'Monthly Fee Detail';
$lang['is_withdrawal'] = 'Store Owner already withdrawal';
$lang['is_transfer'] = 'Store Owner already transfer';


$lang['update'] = 'Update';
$lang['clear'] = 'Delete user\'s eWallet account';
$lang['update_success'] = 'Update Success';
$lang['update_failure'] = 'Update Failure';
$lang['reject'] = 'Reject';
$lang['no_operate'] = 'Unable to operate';
$lang['all'] = 'All';
$lang['reapply'] = 'Please reapply for cash withdraw after cancelling';
$lang['status_title'] = 'Member Status';
$lang['status_title'] = 'Member Status';
$lang['status_0'] = 'Inactive';
$lang['status_1'] = 'Normal';
$lang['status_2'] = 'Suspended';
$lang['status_3'] = 'Freeze';
$lang['status_4'] = 'Company Account';
$lang['cash_withdrawal_list'] = 'Manually Cash Withdrawal List';
$lang['withdrawal_cash'] = 'Withdrawal Cash';
$lang['withdrawal_type'] = 'Withdrawal Type';
$lang['withdrawal_account'] = 'Withdrawal Account';
$lang['tps_manually'] = 'TPS Manually';
$lang['tps_paid'] = 'Manually Paid';
$lang['tps_status_0'] = 'Unprocessed';
$lang['tps_status_1'] = 'Processed';
$lang['tps_status_2'] = 'Processing';
$lang['tps_status_4'] = 'canceled';
$lang['sure'] = 'Are you sure ?';
$lang['sure_delivery'] = 'Are you sure that you have received the product order ?';


$lang['lifecycle'] = 'Account Details';
$lang['no_title'] = 'Please Enter News Title';
$lang['no_source'] = 'Please Enter News Source';
$lang['no_content'] = 'Please Enter News Content';
$lang['no_img'] = 'Please Upload News Img';
$lang['news_img'] = 'Upload News Img';
$lang['required'] = 'Required';
$lang['hot_news'] = 'Hot News';
$lang['sort'] = 'Sort';
$lang['sort_exist'] = 'Sort numbers existed';
$lang['display'] = 'Display';
$lang['no_display'] = 'No Display';
$lang['need_display'] = 'Display';
$lang['important_title'] = 'Important';
$lang['order_search'] = 'Check Upgrading Detail';
$lang['upgrade_order'] = 'Product Set Order';
$lang['upgrade_month_order'] = 'Join Matrix Order';
$lang['month_fee_order'] = 'Recharge Month Fee order';
$lang['txn_id'] = 'Transaction ID';
$lang['order_sn'] = 'Order Number';
$lang['payment'] = 'Payment Method';
$lang['pay_time'] = 'Pay Time';
$lang['unpaid'] = 'Unpaid';
$lang['paid'] = 'Paid';
$lang['usd_money'] = 'Dollars';

$lang['news_lang']='Language';
$lang['news_cate']='Category';
$lang['news_ok']='Add Category';
$lang['news_cate_name']='News';

$lang['approve'] = 'Approve';
$lang['pending'] = 'Pending';
$lang['refuse'] = 'Reject';
$lang['refuse_reason'] = 'Reject Reason';
$lang['action'] = 'Action';
$lang['check_card'] = 'ID Verification';
$lang['check_card_id'] = 'Government ID#';
$lang['reset_password']='Reset Password';
$lang['new_card_number'] = 'New Government ID#';
$lang['scan'] = 'Scan Photo';
$lang['scan_back'] = 'Scan Back Photo';
$lang['create_time'] = 'Create Time';
$lang['check_status'] = 'Check Status';

/** Add by Andy*/
$lang['check_admin'] = 'Audit Admin';
$lang['check_time'] = 'Audit Time';
$lang['relive_ban'] = 'Unblock';
$lang['relive_success'] = 'Unblock successful';
$lang['relive_fail']    = 'Unblock failed';

$lang['news_manage'] = 'News Manage';
$lang['add_news'] = 'Add News';
$lang['bulletin_board_list'] = 'List of Announcements';
$lang['add_bulletin_board'] = 'Add Announcements to Bulletin Board';
$lang['title'] = 'Title';
$lang['source'] = 'Source';
$lang['content'] = 'Content';
$lang['news_list'] = 'News List';
$lang['news_type'] = 'Type';

$lang['tps138_admin'] = 'TPS Admin';
$lang['user_management'] = 'Member Management';
$lang['user_list'] = 'Member List';
$lang['user_info'] = 'Member Information Details';
$lang['view_detail_info'] = 'View Detail Info';
$lang['status'] = 'Status';
$lang['not_enable'] = 'Inactive';
$lang['enabled'] = 'Normal';
$lang['sleep'] = 'Suspended';
$lang['account_disable'] = 'Account disabled';
$lang['company_keep'] = 'Company Keep Account';
$lang['month_fee_date'] = 'Monthly Fee Date';
$lang['day'] = 'Day';
$lang['day_th'] = 'th';
$lang['admin_account_manage'] = 'Account Manage';
$lang['admin_account_list'] = 'Admin Account List';
$lang['role'] = 'Role';
$lang['role_super'] = 'Administrator';
$lang['role_customer_service'] = 'Customer Service';
$lang['role_customer_service_lv1'] = 'Customer Service-lv1';
$lang['role_customer_service_lv2'] = 'Customer Service-lv2';
$lang['role_customer_service_manager'] = 'Customer Service Manager';
$lang['operations_personnel'] = 'Operation';
$lang['role_storehouse_korea'] ='Export Order/Shipment (Korea)';
$lang['role_storehouse_hongkong'] ='Export Order/Shipment （HongKong）';
$lang['financial_officer'] = 'Financial Personnel';
$lang['account_disable'] = 'Disable';
$lang['account_reenable'] = 'Reopen';
$lang['account_disable_z'] = 'Disabled';
$lang['account_reenable_z'] = 'Reopened';
$lang['account_disable_m'] = 'Disabled with past due fee';
$lang['account_reenable_m'] = 'Reopened with past due fee';
$lang['signouting'] = 'Sign out';
$lang['account_enable'] = 'Enable';
$lang['enable_store_level'] = 'Enable Store Level';
$lang['upgrade_store_level'] = 'Upgrade Store Level';
$lang['upgrade_user_manually'] = 'Manually Upgrade Membership Levels';
$lang['user_id'] = 'User Id';
$lang['please_sel_level'] = 'Please Select Level';
$lang['user_id_list_requied'] = 'Please fill in the member id';
$lang['month_fee_or_user_rank_requied'] = 'Please select the monthly fee level or member type.';
$lang['submit_success'] = 'Submitted successfully';
$lang['upgrade_success_num'] = '%s members have been processed.';
$lang['upgrade_no_num'] = 'No valid membership id.';
$lang['user_ids_notice'] = 'A plurality of id separated by commas.';
$lang['no_permission'] = 'No Permission';
$lang['resert_user_status'] = '恢复用户状态';
$lang['admin_user_account_disabled_hint'] = '输入密码错误次数过多，账号已被锁定。如需帮助，请联系组长经理。';
$lang['admin_user_login_pwd_error'] = '您的密码有误，错误超过3次将锁定账户';

$lang['signouting_not_accounts'] = 'Withdrawal of transfer';
$lang['signouting_not_withdrawals'] = 'Not to mention the withdrawal';
$lang['signouting_not_pay'] = 'Can not be paid in cash pool';
/*佣金*/
$lang['commission_admin'] = 'Commission Management';
$lang['commission_add_or_reduce'] = 'Changes in commission';
$lang['pls_input_amount'] = 'Please fill in the amount.';
$lang['amount_condition'] = 'Amount value must be greater than 0, and if the decimal retain two decimal places.';
$lang['why'] = 'Why';
$lang['pls_input_reson'] = 'Please fill in the reason.';
$lang['amount_limit'] = '金额限制';

$lang['unbundling'] = 'Unbind';
$lang['unbundling_success'] = 'Unbinding successful';
$lang['unbundling_fail'] = 'Unbinding failed';
$lang['will_unbinding'] = 'You will unbind';

/*佣金特别处理*/
$lang['commission_month_sum'] = 'Total bonus for the month';
$lang['commission_month'] = "Member's monthly commission statistics";
$lang['commission_special_do'] = 'Bonus special treatment';
$lang['add_in_qualified_list'] = '加入当月发奖列队';
$lang['pls_sel_comm_item'] = '请选择奖项';
$lang['pls_input_right_uid'] = '请输入正确的用户id。';
$lang['fix_user_commission'] = '补发会员奖金';
$lang['pls_sel_date'] = '请选择日期';
$lang['date_error_over_today'] = '结束日期不能大于今天';
$lang['month_date_error_over_today'] = 'When selecting date for monthly Top performers and monthly team sales Residual Bonus ,please include the 15th of the month.';
/*月费池管理*/
$lang['monthfee_pool_admin'] = 'Month Fee Pool Management';
$lang['monthfee_pool_add_or_reduce'] = 'Changes in month fee pool';

//team
$lang['enter'] = 'Input User ID,Then ENTER';
$lang['no_exist'] = 'The member id does not exist.';
$lang['join_matrix_time'] = 'Join Matrix Time';
$lang['buy_product_time'] = 'Buy Product Set Time';
$lang['commission_special_check'] = '补发奖金计算';

/*优惠券*/
$lang['coupon'] = 'Coupon';

/*会员列表*/
$lang['search_notice'] = 'ID / E-mail / Name';
$lang['card_notice'] = 'ID / Name';
$lang['total_money'] = 'Total Money';

/*提现申请列表*/
$lang['generate_batch'] = 'Generate batch';
$lang['generate_time'] = 'Generation time';
$lang['process_time'] = 'processing time';
$lang['generate_batch_num']='Batch file number';
$lang['total_items']='Total';
$lang['total_money']='Total amount';
$lang['payment_reason']='Payment reason';

$lang['number_hao'] = 'Serial number';
$lang['fee_num'] = 'Counter Fee($)';
$lang['the_actual_amount'] = 'Actual arrival amount($)';
$lang['paypal_account_t'] = 'Alipay';
$lang['paypal_status'] = 'Alipay state';
$lang['payee_name']='Payee name';
$lang['application_time'] = 'Application time';
$lang['money_num']='Amount of money($)';
$lang['exchange_rate']="exchange rate";
$lang['criticism_num']="Batch number";
$lang['batch_number']="Batch number";
$lang['operation']="operation";
$lang['export_EXCEL']="导出EXCEL";
$lang['process_result']='Treatment result';
$lang['result_ok']='success';
$lang['result_false']='failure';
$lang['unselected']='No application for withdrawal';
$lang['unselected_reason']='No reason to choose';
$lang['batch_error']='In your option, an existing batch exists, please select again';
$lang['reject_confirm']='You will reject the offer, please fill out the reasons for the rejection！';
$lang['cause']='Reason';
$lang['view_batch']='View the batch';
$lang['alipay_withdraw'] = 'Commission withdrawal with Alipay';
$lang['bank_withdraw'] = 'Mention the list';
$lang['payment_interface'] = 'Payment interface';
$lang['payment_type_1'] = 'unionpay';
$lang['payment_type_2'] = 'TenPay';
$lang['payment_type_3'] = 'Fast delivery';
$lang['payment_type_4'] = 'Remittance payment';
$lang['submit_pay_tyep'] = 'Submission payment';
//$lang['batch_list'] = '查看批次';
$lang['batch_xq'] = 'Batch details';
$lang['process']='See';
$lang['cancel_batch']='Cancel batch';
$lang['submit_alipay']='Submitted to Alipay';
$lang['total_items_ts']='Current page number：%s ';
$lang['total_money_ts']='Cash withdrawal amount：$%s';
$lang['fee_num_ts']='Total fee：$%s';
$lang['fee_num_ts2']='Counter Fee';
$lang['the_actual_amount_ts']='Actual amount of money：$%s';
$lang['please_choose']='Please select';
$lang['choose1']='货款';
$lang['choose2']='运费';
$lang['choose3']='饭钱';
$lang['choose4']='Sales commission';
$lang['status_n1']='Pending treatment';
$lang['status_n2']='In treatment';
$lang['status_n3']='Finish processing';
$lang['cancel_confirm']='Cancel batch';
$lang['double_confirm']='Are you sure you want to cancel this batch?';

/*清空用户账户信息*/
$lang['clear_member_account_info'] = 'Transfer User Account';
$lang['new_email'] = 'New email';
$lang['new_password_note'] = 'The initial password for this user is:%s';

/**2x5佣金补偿**/

$lang['commission_2x5'] = 'Commission Management For 2x5';
$lang['commission_compensation'] = 'Confirm';

$lang['please_input_user_id'] = "Get Commission's ID";
$lang['please_input_pay_user_id'] = "Pay Commission's ID";

$lang ['commission_forget'] = 'Commission Missing';
$lang ['commission_error'] = 'Commission Error';
$lang ['commission_repeat'] = 'Commission Repeat';

$lang ['reason'] = '-----Reasons-----';
$lang['commission_2x5'] = 'Commission compensation for 2x5';


/***月費轉現金池***/
$lang['month_fee_to_amount'] = 'Month Fee Transfer To Amount';
$lang['user_id'] = 'User ID';
$lang['month_fee'] = 'Month Fee';
$lang['amount_'] = 'Amount';
$lang['to'] = 'Transfer To';
$lang['confirm'] = 'Confirm';
$lang['user_not_exits'] = 'ID does not exist';
$lang['cash_not_exits'] = "The cash can't be empty";
$lang['max_cash'] = 'Limit：';

$lang['id_not_null'] = "The ID can't be empty";
$lang['month_fee_error'] = 'Please enter a number greater than 0';
$lang['not_bigger'] = 'No more than limit';

$lang['transfer_to_success'] = 'Transfer to success';
$lang['transfer_to_fail'] = 'Transfer to fail';

$lang['see_user_back_office'] = "Review";

/* 产品管理相关  */
$lang['brand_exists']='Brand name already exists!';
$lang['cate_exist']='Category name already exists!';
$lang['goods_doba_import']='Import Doba Products';

$lang['select_order_repair_date'] = 'Select date for orders to make-up';
$lang['select_comm_order_type']   = 'Select bonus/commission type for orders to make-up';
$lang['pls_select_order_year']    = 'Please select date for orders to make-up';
$lang['pls_select_comm_order_type'] = 'Please select bonus/commission type for orders to make-up';
$lang['add_comm_success'] = 'Bonus/commission added successful';
$lang['add_comm_fail'] = 'No make-up order needed';
$lang['cur_month_add_comm_ban'] = 'Cannot make-up orders for this month';
$lang['comm_tips'] = 'Hint';
$lang['you_will_add_a_comm'] = 'Orders to make up for bonus/commission will be added:';
$lang['add_comm_year_month'] = 'Date for make-up order';
$lang['comm_order_type'] = 'Type of bonus/commission for make-up';
$lang['need_add_comm_mount'] = 'Amount required for the make-up order';

$lang['goods_manage']='Goods Manage';
$lang['add_category']='Add Category';
$lang['category_list']='Category Manage';
$lang['add_goods']='Add Goods';
$lang['goods_list']='Goods List';
$lang['ads_list']='Ads Manage';
$lang['ads_add']='Add Ads';
$lang['edit_ads']='Edit Ads';
$lang['goods_group_list']='Product Set List';
$lang['edit_category']='Edit Category';
$lang['edit_goods']='Edit Goods';

$lang['label_ads_img']='Ads Imgages';
$lang['label_ads_sort']='Orders';
$lang['label_ads_url']='Ads Url';
$lang['label_ads_status']='Status';
$lang['label_ads_lang']='Language';
$lang['label_ads_location']='Location';

$lang['label_goods_group_add']='Add Product Set';
$lang['label_goods_group_edit']='Edit Product Set';
$lang['label_goods_group_search']='Search products';
$lang['label_goods_group_ok']='Search';
$lang['label_goods_group_keywords']='Key Word of Product';
$lang['label_goods_group_num']='Amount';
$lang['label_goods_group_id']='Product Set ID';
$lang['label_goods_group_content']='Content of Product Set';

$lang['label_brand_list']='Brand Name Management';
$lang['label_brand_add']='Add Brand';
$lang['label_brand_name']='Brand Name';
$lang['label_brand_id']='Brand ID';
$lang['label_language']='Language';
$lang['label_language_all']='All Languages';
$lang['label_brand_list_m']='Brand List';

$lang['label_cate_parent']='Parent';
$lang['label_cate_name']='Category Name';
$lang['label_cate_sn']='SN';
$lang['label_cate_desc']='Description';
$lang['label_cate_icon']='ICON';
$lang['label_cate_sort']='Category Sorting';
$lang['label_cate_meta_title']='SEO Heading';
$lang['label_cate_meta_keywords']='SEO Key Word';
$lang['label_cate_meta_desc']='SEO Description';
$lang['label_cate_top']='Top Category';

$lang['label_goods_display_state']='Show the language';
$lang['label_goods_cate']='Sub-Category';
$lang['label_goods_shipper']='Shipper';
$lang['label_goods_shipper_sel']='All Shipper';
$lang['label_goods_brand']='Name Brand';
$lang['label_goods_effect']='Style';
$lang['label_goods_name']='Product Name';
$lang['label_goods_name_cn']='Product Name（Chinese）';
$lang['label_goods_main_sn']='Product Main SKU';
$lang['label_goods_img']='Product Main Photo (250*250)';
$lang['label_is_change_width']='Not scaling';
$lang['label_goods_sku']='Create Secondary SKU';
$lang['label_goods_img_gallery']='Product Album';
$lang['label_goods_img_detail']='Detail Picture';
$lang['label_goods_stock']='In Stock Amount';
$lang['label_goods_warn']='Alert Amount';
$lang['label_goods_weight']='Product Weight（kg）';
$lang['label_goods_bulk']='Bulk';
$lang['label_goods_purchase_price']='Purchase Price(usd)';
$lang['label_goods_market_price']='Market Price(usd)';
$lang['label_goods_shop_price']='Shop Price(usd)';
$lang['label_goods_is_promote']='If On Sale';
$lang['label_goods_promote_start']='On Sale Starting Time';
$lang['label_goods_promote_end']='On Sale Finish Time';
$lang['label_goods_promote_price']='On Sale Price';
$lang['label_goods_sale']='On Shelf';
$lang['label_goods_unsale']='Off Shelf';
$lang['label_goods_looking']='Waiting for audit';
$lang['label_goods_delete']='Delete';
$lang['label_goods_best']='Recommend';
$lang['label_goods_new']='New';
$lang['label_goods_hot']='Hot sale';
$lang['label_goods_home']='Home Page Display';
$lang['label_goods_ship']='Free Shipping';
$lang['label_goods_24']='Shipping in 24 hours';
$lang['label_goods_voucher']='Voucher goods';
$lang['label_goods_alone_sale']='Single Item';
$lang['label_goods_group_sale']='Product Set';
$lang['label_goods_group_sale_upgrade']='Product Set For Upgrade';
$lang['label_goods_for_upgrade']='For upgrading';
$lang['label_goods_group_sale_ids']='Product Set ID';
$lang['label_goods_note']='Product Note（Description）';
$lang['label_goods_note1']='Special Note';
$lang['label_goods_store']='Storage';
$lang['label_goods_add_user']='Add by';
$lang['label_goods_update_user']='Update by';
$lang['label_goods_add_time']='Time Added';
$lang['label_goods_update_time']='Time Updated';
$lang['label_goods_sort']='Sorting Order';
$lang['label_goods_desc']='Detail Descriptiong';
$lang['label_goods_desc_pic']='Detail Descriptiong(Pictures)';
$lang['label_goods_sale_country']='Sale country';
$lang['label_yes']='Yes';
$lang['label_no']='No';
$lang['label_sub_sn']='Product SKU';
$lang['label_color']='Product Color';
$lang['label_size']='Size';
$lang['label_customer']='Customer';
$lang['label_sel_store']='All Storage';
$lang['label_sel_store_third']='Supliser Storehouse';
$lang['label_sel_cate']='All Category';
$lang['label_sel_status']='All State';
$lang['label_sel_supplier']='All Supplier';
$lang['label_sel']='- Please Select -';
$lang['label_flag']='Place of Production';
$lang['label_goods_gift']='Gift(sku)';

$lang['label_new'] = 'Recommend New Products';
$lang['label_comment'] = 'Recommend On Sale Product';
$lang['label_supplier'] = 'Supplier Manage';
$lang['label_supplier_add'] = 'Add Supplier';
$lang['label_supplier_edit'] = 'Edit Supplier';
$lang['label_supplier_name'] = 'Company';
$lang['label_supplier_user'] = 'Contacter';
$lang['label_supplier_tel'] = 'Tel';
$lang['label_supplier_phone'] = 'Phone';
$lang['label_supplier_qq'] = 'QQ';
$lang['label_supplier_ww'] = 'WangWang';
$lang['label_supplier_addr'] = 'Address';
$lang['label_supplier_email'] = 'Email';
$lang['label_supplier_link'] = 'Company website';
$lang['label_supplier_shipping'] = 'Supplier Delivery';
$lang['info_supplier_exist'] = 'The Company has exist.';
$lang['info_supplier_username_exist'] = 'The Username has exist.';
$lang['label_supplier_n'] = 'Supplier';
$lang['label_supplier_username'] = 'Username';
$lang['label_supplier_password'] = 'Password';

$lang['label_cn'] = 'China';
$lang['label_us'] = 'America';
$lang['label_hk']='Hongkong';
$lang['label_ne']='New Zealand';
$lang['label_ho']='Holland';
$lang['label_as']='Australia';
$lang['label_fr']='France';
$lang['label_ko']='Korea';
$lang['label_tw']='Taiwan';
$lang['label_jp']='Japan';
$lang['label_sp']='Span';
$lang['label_ph']='Philippines';
$lang['label_chi']='Chile';
$lang['label_ge']='German';
$lang['label_ca']='Canada';
$lang['label_fi']='Finland';

$lang['info_success']='Data Submit Success';
$lang['info_failed']='Data Submit Failed，Please fill in all data with * carefully';
$lang['info_unvalid_request']='Invalid Request';
$lang['info_error']='Failed，Please Try Again';
$lang['info_price_err']='Shop price can not be greater than the market price';
$lang['info_price_err1']='Shop price must be greater than (10 / 9 * purchase price)';
$lang['info_err_weight']='Product weight is wrong';
$lang['info_err_purchase_price']='Purchase price is wrong';

$lang['reset_user_pwd']="Reset User Password";
$lang['confirm_user_id']='Please confirm user id';
$lang['id_not_identical']='Enter the ID must be the same twice';
$lang['this_user_name_is']="The user's real name is:";

$lang['reset_pwd_success_admin']='Successfully reset the password, the initial password is:';

/* 交易管理 */
$lang['admin_trade_title'] = 'Trade Management';
/* 问题反馈  */
$lang['label_feedback'] = 'Feedback';
$lang['label_feedback_email'] = 'Email';
$lang['label_feedback_userid'] = 'User ID';
$lang['label_feedback_content'] = 'Contents';
$lang['label_feedback_date'] = 'Date';
$lang['label_feedback_state'] = 'State';
$lang['label_feedback_state_yes'] = 'Replied';
$lang['label_feedback_state_no'] = 'No reply';
$lang['label_feedback_change_state'] = 'hide';
$lang['label_server'] = 'Sale Service';
/* 订单管理 */
$lang['admin_trade_order'] = 'Order Management';
$lang['admin_trade_order_attach'] = 'Main order id';
$lang['admin_order_info'] = 'Order Information';
$lang['admin_order_info_basic'] = 'Basic Information';
$lang['admin_order_id'] = 'Order id';
$lang['pc_order_id'] = 'Order id';
$lang['admin_order_prop'] = 'Order Type';
$lang['admin_order_prop_normal'] = 'Regular Order';
$lang['admin_order_prop_component'] = 'Component Order';
$lang['admin_order_prop_merge'] = 'Merge Order';
$lang['admin_order_uid'] = 'Customer id';
$lang['admin_order_customer'] = 'Customer';
$lang['admin_order_store_id'] = 'Store id';
$lang['admin_order_consignee'] = 'Receiver';
$lang['admin_order_phone'] = 'Phone Number';
$lang['admin_order_deliver_addr'] = 'Delivery Address';
$lang['admin_order_zip_code'] = 'Zip Code';
$lang['admin_order_customs_clearance'] = 'Customs Clearance';
$lang['admin_order_deliver_time'] = 'Shipping Time';
$lang['admin_order_expect_deliver_date'] = 'Est. Shipping Date';
$lang['admin_order_expect_deliver_date_invalid'] = 'estimated shipping date can not be earlier than current date';
$lang['admin_order_info_goods'] = 'Product Information';
$lang['admin_order_goods_list'] = 'List of Products';
$lang['admin_order_goods_sn'] = 'sku';
$lang['admin_order_goods_name'] = 'Product Name';
$lang['admin_order_goods_quantity'] = 'Quatity of Items';
$lang['admin_order_remark'] = 'Note';
$lang['admin_order_info_pay'] = 'Payment Information';
$lang['admin_order_receipt'] = 'Do you want receipt';
$lang['admin_order_receipt_0'] = 'No';
$lang['admin_order_receipt_1'] = 'Yes';
$lang['admin_order_currency'] = 'Currency';
$lang['admin_order_rate'] = 'Exchange Rate';
$lang['admin_order_goods_amount'] = 'Total Amount';
$lang['admin_order_deliver_fee'] = 'Shipping & Handling Fee';
$lang['admin_order_amount'] = 'Final Total';
$lang['admin_order_amount_usd'] = 'Final Total (USD)';
$lang['admin_order_profit_usd'] = 'Profit (USD)';
$lang['admin_order_payment'] = 'Payment Method';
$lang['admin_order_payment_unpay'] = 'Pending Payment';
$lang['admin_order_payment_group'] = 'Prepaid';
$lang['admin_order_payment_coupon'] = 'Exchange With Voucher';
$lang['admin_order_payment_alipay'] = 'AliPay';
$lang['admin_order_payment_unionpay'] = 'Union Pay';
$lang['admin_order_payment_paypal'] = 'PayPal';
$lang['admin_order_payment_ewallet'] = 'eWallet';
$lang['admin_order_payment_yspay'] = 'YSPay';
$lang['admin_order_payment_amount'] = 'Cash Bucket';
$lang['admin_order_pay_time'] = 'Payment Time';
$lang['admin_order_notify_num'] = 'API call back count';
$lang['admin_order_pay_txn_id'] = 'Third Party Trade Number';
$lang['admin_order_info_status'] = 'Status';
$lang['admin_order_info_create_time'] = 'Creat Time';
$lang['admin_order_info_freight'] = 'Delivery Information';
$lang['admin_order_info_deliver_time'] = 'Shipping Time';
$lang['admin_order_info_receive_time'] = 'Receiving Time';
$lang['admin_order_info_update_time'] = 'Update Time';
$lang['admin_order_status'] = 'Order Status';
$lang['admin_order_status_all'] = 'Entire Status';
$lang['admin_order_status_init'] = 'Order In Process';
$lang['admin_order_status_checkout'] = 'Waiting for Payment';
$lang['admin_order_status_paied'] = 'Waiting for Shipping';
$lang['admin_order_status_delivered'] = 'Shipped';
$lang['admin_order_status_arrival'] = 'Evaluation';
$lang['admin_order_status_finish'] = 'Complete';
$lang['admin_order_status_returning'] = 'Returning';
$lang['admin_order_status_holding'] = 'holding';
$lang['admin_order_status_refund'] = 'Return Complete';
$lang['admin_order_refund'] = 'Order Refund';
$lang['admin_order_status_cancel'] = 'Cancel Order';
$lang['admin_order_status_component'] = 'Has been split';
$lang['admin_order_status_doba_exception'] = 'Doba Exception Order';
$lang['admin_order_operate'] = 'operation';
$lang['admin_order_operate_deliver'] = 'Confirm Shipping';
$lang['admin_order_confirm_cancel'] = 'Once the order is cancelled, you can not reverse the order. Do you really want to do that?';
$lang['admin_order_cancel_confirm'] = 'Confirm to cancel the order';
$lang['admin_order_cancel'] = 'Cancel';
$lang['admin_order_deliver_box_title'] = 'Please fill out the shipping information';
$lang['admin_order_deliver_box_id'] = 'Shipping Information';
$lang['admin_order_tracking_num'] = 'Tracking Number';
$lang['admin_order_remark_system'] = 'System Remark';
$lang['admin_order_customer_remark'] = 'Remark Section';
$lang['admin_order_customer_remark_add'] = "Content of Remark for the Customer";
$lang['admin_order_system_remark'] = 'Backoffice Section';
$lang['admin_order_system_remark_add'] = "Content of Backoffice Remark";
$lang['admin_order_remark_operator'] = 'Back Office Operator';
$lang['admin_order_remark_create_time'] = 'Creat Time';
$lang['admin_order_expect_deliver_date'] = 'Estimated Shipping Time';
$lang['admin_order_shipping_print'] = 'Shipping Print';
$lang['admin_doba_order_fix'] = '手动获取doba信息';
$lang['admin_doba_order_id'] = 'Doba ID';
$lang['admin_doba_order_request'] = '获取信息';
$lang['admin_doba_order_request_succ'] = '获取信息成功';

/* 导入订单 */
$lang['admin_trade_order_import'] = 'Import Order Data';

$lang['set_except_group']='设置免套餐会员';

//重置选购套装
$lang['not_find_this_order']="Did not find that the user's choose order";
$lang['this_order_not_reset']='The order can not be reset';
$lang['reset_choose_group_success']='Order reset successfully';
$lang['this_user_not_choose']='The user did not buy, no need to reset';
$lang['this_user_upgrade_not_reset']='The user has been upgraded to operate, Can not reset';
$lang['reset_group']='Reset Order';
$lang['reset_upgrade_group']='Reset upgrade order';
$lang['not_find_this_upgrade_order']="Did not find that the user's upgrade order";
$lang['you_use_coupons_not_can_reset']='Inadequate coupons, can not reset';
$lang['order_a_timeout_not_can_reset']='The order is over 3 days, it can not be returned.';
$lang['this_user_have_more_than_once_upgrade_record']="This user is a phased upgrade, you can not reset the orders";


/*导入第三方订单*/
$lang['import_third_part_orders'] = 'Import The Third Party Orders';

/* 供应商系统 相关 */
$lang['sys_supplier_title']='TPS Supplier Management System';
$lang['coupons_manage']='Voucher Management';
$lang['coupons_add_or_reduce']='Changes in voucher';
$lang['voucher']='Voucher';

$lang['voucher_not_null']='Please enter the voucher amount.';
$lang['remark_not_null']='Please enter a reason.';
$lang['please_enter_correct_voucher']='Please enter the correct ticket amount.';

$lang['voucher_value']='Voucher Value';

$lang['order_not_exits']='The order not exits';
$lang['order_id_not_null']="The order number can't be empty";
$lang['this_order_is_choose_order']='The order is choose order, the customer id is:';
$lang['this_order_is_upgrade_order']='The order is upgrade order, the customer id is:';
$lang['this_order_is_basic_order']='The order is general orders, can not be reset';
$lang['check_order_type']='Check Order Type';
$lang['orderid_default_config'] = 'Default configuration';
$lang['orderid_users_config'] = 'Parameter configuration';
$lang['orderid_freight_info_cv'] = 'Tracking number';
$lang['orderid_freight_info_null'] = 'Tracking number empty';
$lang['refund'] = 'Refund';
$lang['no_refund'] = 'Not refundable';
$lang['no_cancel_order'] = '订单已被导出，取消时需要跟运营部确认订单是否发货,谨慎操作！！！^^^';
$lang['order_refund'] = 'Order refund';
$lang['refund_coupons']='Refund Voucher';
$lang['only_cancel']='Only Cancel';

/** paypal 查询 */
$lang['admin_paypal_failure_search'] = 'PayPal Refund/Reversal Order Search';
$lang['admin_paypal_failure_list'] = 'PayPal Refund/Reversal Orders';

/* 运营系统 - 仓库管理 */
$lang['admin_oper_storehouse_ALL'] = "All Storehouse";
$lang['admin_oper_shipper_ALL'] = "All Shipper";
$lang['admin_oper_storehouse_CNSZ'] = "China Shenzhen Storehouse";
$lang['admin_oper_storehouse_CNHK'] = "China Hongkong Storehouse";
$lang['admin_oper_storehouse_USATL'] = "USA Atlanta Storehouse";
$lang['admin_oper_storehouse_USANOPAL'] = "USA NOPAL Storehouse";
$lang['admin_oper_storehouse_USAJBB'] = "USA JBB Storehouse";
$lang['admin_oper_storehouse_USANI'] = "USA Grace of Graviola Storehouse";
$lang['admin_oper_storehouse_USASAMTJ'] = "USA Epic Sam LLC Storehouse";
$lang['admin_oper_storehouse_USAIE'] = "USA Insight Eye Storehouse";

$lang['admin_oper_storehouse_KRSL'] = "Korea Seoul Storehouse";
$lang['admin_oper_storehouse_KRKK'] = "Kangseo Chemical ---Wine Bar Soap";
$lang['admin_oper_storehouse_KRCPC'] = "CPBIO Co. ltd.---99 skin care cosmetic";
$lang['admin_oper_storehouse_KRFHC'] = "Fusion Health Care---Broccoli + Healing Angels";
$lang['admin_oper_storehouse_KRSSL'] = "Southernlink Services LLC ---Sprout Powder Diet";
$lang['admin_oper_storehouse_KRWM'] = "Korea Water Maker Storehouse";
$lang['admin_oper_storehouse_KRFLX'] = "Korea Florex Storehouse";
$lang['admin_oper_storehouse_KRHCL'] = "HANKUKBOWONBIO Co. Ltd-- pillow, toothpaste &　Power Knee";
$lang['admin_oper_storehouse_KRPS'] = "Korea Ssophyya Storehouse";
$lang['admin_oper_storehouse_KRG'] = "Korea Ginseng Storehouse";
$lang['admin_oper_storehouse_KRDC'] = "Korea Dr. Cell Storehouse";
$lang['admin_oper_storehouse_KRSSD'] = "Korea Seng Seng Dan Storehouse";
$lang['admin_oper_storehouse_KRKSCG'] = "Korea KAMIJOA+Si-Lite+Cheongln Storehouse";
$lang['admin_oper_storehouse_KRCG'] = "Korea 染发+Cheongin gold Storehouse";

$lang['admin_oper_storehouse_CNXY'] = "China Xiyuan Storehouse(Supplier)";
$lang['admin_oper_storehouse_CNZYP'] = "China ZunYuPin-DaMi Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNGWM'] = "China GuoWeiMing-HongJiu Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNLT'] = "China LvTang-NaiPing Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNFJ'] = "China FuJia-GongZai Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNWD'] = "China WenDing-TeCai Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNMY'] = "China HanYuan-Hngjiu Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNFM'] = "China FuMai-WuGu Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNKSD'] = "China KaiShengDa Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNJGH'] = "China JinGuoHui-MaoTaiJiu Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNTFT'] = "China TianFuTang Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNJGH1'] = "China JinGuoHui-MaKa Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNFFMY'] = "China WuFaMiYe Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNYG'] = "China YangGuang Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNYHM'] = "China YiHaiMing Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNBDS'] = "China BanDeShi Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNZSH'] = "China ZiShaHu Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNYP'] = "China YaPei Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNJJ'] = "China JinJiang Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNSL'] = "China ShiLong Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNWLL'] = "China WeiLiLai Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNJMD'] = "China JiaMinDou Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNYCK'] = "China YuChenKan Storehouse（Supplier）";
$lang['admin_oper_storehouse_CNLHY'] = "China LvHeYuan Storehouse（Supplier）";

$lang['admin_supplier_store_code'] = 'Warehouse corresponding supplier list';
$lang['admin_supplier'] = 'supplier';
$lang['admin_store_code'] = 'Warehouse';


/** 商品名称 */
$lang['admin_mini_water'] = 'Hydrogen Water Maker MINI';
$lang['admin_family_water'] = 'Hydrogen Water Maker For Family';
$lang['admin_powder'] = 'Korean Sprout Powder Diet';
$lang['admin_flx'] = 'FLOREX';

/* 区域 */
$lang['zone_area_chn'] = "Mainland China";
$lang['zone_area_usa_other'] = "U.S.A & Other";
$lang['zone_area_kor'] = "South Korea";
$lang['zone_area_hkg_mac_twn_asean'] = "Hong Kong & Macao & Taiwan & Southeast Asia";

$lang['fill_in_frozen_remark'] = 'Fill in frozen remark';
$lang['fill_in_frozen_remark_2'] = 'Please enter reason for freezing the order. If status indicates "order in process", please enter the operator from whom confirmation is received for freezing the order.';
$lang['lock_order_not_can_freeze'] = 'The order has been locked, can not be frozen';
$lang['freeze_success'] = "Frozen Success";
$lang['transaction_rollback'] = "Transaction Rollback";
$lang['order_remove_frozen'] = 'Restore';
$lang['remove_frozen_success'] = 'Restore Success';
$lang['confirm_remove_freeze'] = 'Confirm Restore?';

//订单操作log
$lang['trade_order_logs'] = 'Order Operation Log';
$lang['all_oper_code'] = 'All Type';
$lang['order_log_oper_create'] = 'Create';
$lang['order_log_oper_modify'] = 'Edit';
$lang['order_log_oper_export'] = 'Export';
$lang['order_log_oper_diliver'] = 'Delivery';
$lang['order_log_oper_reset'] = 'Reset';
$lang['order_log_oper_rollback'] = 'Rollback';
$lang['order_log_oper_cancel'] = 'Cancel';
$lang['order_log_oper_frozen'] = 'Frozen';
$lang['order_log_oper_unfrozen'] = 'Unfrozen';
$lang['order_log_oper_addr_edit'] = 'order address';
$lang['order_log_oper_erpmodify'] = 'order information';
$lang['order_log_oper_suit'] = 'Order status change for product package';
$lang['order_log_oper_recovery'] = 'Order recovery';
$lang['order_log_oper_exchange'] = '订单换货';

$lang['operator_id'] = 'Operator ID';
$lang['update_time'] = 'Operator Time';

$lang['load_finish'] = 'No more';
$lang['load_more'] = 'Load More';

$lang['this_user_not_sort'] = 'The user has not entered sorting';

$lang['the_number_of_matrix'] = 'The number of matrix:';

//后台执行sql入口
$lang['execute_sql'] = 'Execute sql';
$lang['please_enter_sql'] = 'Please enter the SQL statement';
$lang['please_enter_remark'] = 'Please enter the remark';

$lang['international_freight'] = 'International Freight';
$lang['goods_sku'] = 'Goods Sku';
$lang['please_input_freight_usd'] = 'Please enter the freight, the unit ($),Not allowed to buy region is set to 1, please';
$lang['please_input_right_sku'] = 'Please enter the correct SKU';
$lang['freight_must_is_number'] = 'The freight must be a number';
$lang['not_find_this_goods_name'] = 'Not found the goods name';

$lang['all_country'] = "All Country";
$lang['sql_source'] = 'Source Code';
$lang['system_setting'] = 'System Management';

$lang['all_status'] = 'All Status';
$lang['awaiting_processing'] = 'Awaiting Processing';
$lang['has_been_completed'] = 'Complete';

$lang['refuse'] = 'Refuse';
$lang['refuse_reason'] = 'Refuse Reason';

/**后台文件管理**/
$lang['admin_ads_file_manage'] = 'File Management';
$lang['admin_file_type'] = 'File Type ';
$lang['admin_file_announcement'] = 'Notice File';
$lang['admin_file_regime'] = 'System';
$lang['admin_commission_explain'] = 'Commission Description';
$lang['admin_file_is_show'] = 'Whether to show';
$lang['file_is_show'] = 'Display';
$lang['file_is_hide'] = 'Hide';
$lang['admin_file_name'] = 'File Name';
$lang['admin_ads_file_add'] = 'Add Files';
$lang['admin_ads_file_modify'] = 'Modify File';
$lang['admin_file_empty'] = 'The file can not be empty';
$lang['admin_file_name_empty'] = 'File name can not be empty';
$lang['admin_file_limit_10m'] = 'Size over 10M';
$lang['admin_file_name_limit_100'] = 'File length is more than 100 characters';
$lang['admin_file_upload_fail'] = 'Upload failed';
$lang['admin_file_type_empty'] = 'The file type can not be empty.';
$lang['admin_file_delete_success'] = 'Delete successfully';
$lang['admin_file_delete_fail'] = 'Delete failed';
$lang['admin_file_update_success'] = 'Update successfully';
$lang['admin_file_update_fail'] = 'Update failed';
$lang['admin_file_add_success'] = 'Add successfully';
$lang['admin_file_add_fail'] = 'Add failed';
$lang['delete_admin_file'] = 'Delete file';
$lang['admin_file_modify'] = 'Edit';
$lang['admin_file_submit_error'] = 'Please do not resubmit';
$lang['admin_file_area'] = 'Region';
$lang['admin_file_area_empty'] = 'Region can not be empty';

/*知识库管理*/
$lang['admin_knowledge'] = 'knowledge';
$lang['admin_knowledge_manage'] = 'knowledge manage';
$lang['admin_knowledge_cate_manage'] = 'knowledge category manage';
$lang['admin_knowledge_title'] = 'title';
$lang['admin_knowledge_cate'] = 'knowledge category';
$lang['admin_knowledge_add'] = 'knowledge add';
$lang['admin_knowledge_cate_add'] = 'knowledge category add';
$lang['edit'] = 'Edit';
$lang['success'] = 'success';
$lang['modify_user'] = 'modify users';
$lang['admin_knowledge_success'] = '操作成功，确定转向列表页，取消则继续操作';


/*会员个人中心文件下载*/
$lang['file_download'] = 'File Download';

/** 客服中心 start*/
$lang['tickets_center'] = 'Help Center';
$lang['history_tickets'] = 'Ticket History';
$lang['my_tickets'] = 'My Ticket';
$lang['all_tickets'] = 'All Tickets';
$lang['add_tickets']= ' Add New Ticket';
$lang['unassigned_tickets'] = 'Unassigned Ticket';
$lang['unassigned_tickets_count'] = 'Unassigned';
$lang['unprocessed_tickets_count'] = 'Unresolved';
$lang['tickets_id'] = 'Ticket Number';
$lang['tickets_sender'] = 'Sender';
$lang['tickets_closed_can_not_reply'] = 'Ticket Closed, Cannot Reply';
$lang['tickets_reply'] = 'Reply';
$lang['org_tickets_info'] = 'Original Ticket Information';
$lang['customer'] = 'Support Agent';
$lang['member'] = 'Member';
$lang['member_id'] = ' Member ID';
$lang['tickets_language'] = ' Language';
$lang['pls_t_uid'] = 'Enter Member ID';
$lang['pls_t_correct_ID'] = 'Enter Correct Member ID';
$lang['tickets_score_num'] = 'Rating';

$lang['tickets_title'] = 'Ticket Subject';
$lang['assign_to_me'] = 'My Assigned';
$lang['tickets_language'] = 'Language';
$lang['assign_success'] = 'Assigned Successful';
$lang['assign_fail'] = 'Assigned Failed';
$lang['view_ticket_detail'] = 'View Detail';
$lang['view_and_change'] = 'View / Modify';
$lang['close_tickets'] = ' Close Ticket';
$lang['view_tickets_log'] = 'View Daily Log';
$lang['confirm_close_tickets'] = 'Confirm To Close Ticket?';
$lang['close_tickets_success'] = 'Ticket Closed';
$lang['close_tickets_fail'] = 'Closing Ticket Failed';
$lang['tickets_content'] = 'Ticket Content';
$lang['picture_not_exist'] = 'Image Not Found';
$lang['tickets_no_exist'] = 'Sorry, Ticket Not Found';
$lang['attach_no_exist'] = 'Sorry, Attachment Not Found';
$lang['log_no_exist'] = 'Sorry, No Log On This Ticket';
$lang['log_info'] = 'Daily Log Detail';
$lang['tickets_take_time'] = 'Time Report';
$lang['day'] = 'Day';
$lang['hour'] = 'Hour';
$lang['minute'] = 'Minute';
$lang['second'] = 'Second';
$lang['tickets_handler'] = 'Operator';
$lang['modified_type'] = 'Type Of Modification';
$lang['old_data'] = 'Old';
$lang['new_data'] = 'New';
$lang['add_new_tickets'] = 'Add New Ticket';
$lang['new_tickets'] = 'New Ticket';
$lang['new_msg'] = 'News';

$lang['t_template_name'] = 'Macro Name';
$lang['t_template_content'] = 'Macro Content';
$lang['t_template_type'] = 'Macro Type';
$lang['tickets_template']= 'Macro Information';
$lang['pls_t_t_name'] = 'Enter Macro Name';
$lang['pls_t_t_content'] = 'Enter Macro Content';
$lang['add_tickets_template'] = 'Add Macro';
$lang['is_public']='Shared';
$lang['template_author'] = 'Author';
$lang['template_name'] = 'Macro Name';
$lang['template_is_public'] = 'Yes';
$lang['template_not_public'] = 'No';
$lang['template_forbid'] = 'Disabled' ;
$lang['add_template_success'] = 'Add Successful';
$lang['add_template_fail'] = 'Add Failed';
$lang['confirm_update_template'] = 'Confirm Macro Change?';
$lang['update_template_success'] = ' Macro Change Successful';
$lang['update_template_fail'] = 'Macro Change Failed';
$lang['confirm_delete_template'] = 'Confirm Delete Macro?';
$lang['delete_template_success'] = 'Delete Successful';
$lang['delete_template_fail'] = 'Delete Failed';

$lang['tickets_black_list'] = 'Black List';
$lang['black_uid'] = 'Member ID';
$lang['tickets_black'] = '(black)';
$lang['confirm_delete_black_list'] = 'Are you sure to remove from black list？';
$lang['update_black_list_success'] = 'Removed from black list';
$lang['update_black_list_fail'] = 'Failed to remove';
$lang['add_black_list_success'] = 'Already add into black list';
$lang['black_list_exist'] = 'Failed to add, this ID is already in the Black List';
$lang['add_black_list_fail'] = 'Failed to add';

$lang['manual_work'] = 'Manually';
$lang['automatic'] = 'Automatically';
$lang['tickets_cus_leave'] = 'Staff %s took a day off';
$lang['tickets_cus_work']  = 'Staff %s is on duty';
$lang['change_status_fail']='Failed to change staff %s working status';
$lang['tickets_auto_assign'] = 'System is already set as automatically tickets distribution';
$lang['tickets_hand_assign'] = 'System is already set as manually tickets distribution';
$lang['tickets_auto_assign_fail'] = 'Failed to set tickets distribution';

$lang['tickets_status'] = 'Status';
$lang['tickets_priority'] = 'Priority';
$lang['modified_manager'] = 'Transfer';
$lang['tickets_assign'] = 'Assign';
$lang['submit_as'] = 'Submit As';
$lang['submit_as_waiting_reply'] = 'Pending Reply';
$lang['submit_as_waiting_discuss'] = 'Pending Discussion';
$lang['add_tickets_tips'] = 'Comment';
$lang['tickets_send_fail'] = 'Send Message Failed';
$lang['tickets_send_success'] = 'Send Message Successful';
$lang['apply_close_tickets'] = 'Request Close Ticket';
$lang['view_tickets'] = 'View Ticket';
$lang['r_waiting_reply'] = 'Submit As Pending ';
$lang['r_waiting_discuss'] = 'Submit As Discussion';
$lang['tickets_tips']='Comment';
$lang['r_tickets_resolved'] = 'Submit As Resolved';
$lang['auto_reply_tickets'] = 'Auto reply';
$lang['close_tickets_send_email'] = 'Send message';
$lang['auto_close_tickets'] = 'Auto close ticket';

$lang['tickets_label'] = 'Comment';
$lang['pls_input_tips'] = 'Enter Comment';
$lang['no_tips'] = 'No Comment';
$lang['add_tips_success'] = 'Add Comment Successful';
$lang['add_tips_fail'] = 'Add Comment Failed';

$lang['tickets_type'] = 'Type';
$lang['add_and_quit'] = 'Join/Cancel Membership';
$lang['join_issue'] = 'Profile Question';
$lang['quit_issue'] = 'Withdraw Question';
$lang['up_or_down_grade'] = 'Upgrade/Downgrade';
$lang['monthly_fee_problem'] = 'Monthly Fee Issue';
$lang['platform_fee_problem'] = 'Platform Management Fee';
$lang['reward_system'] = 'Comp Plan';
$lang['product_recommendation'] = 'Vendor Referral';
$lang['shop_transfer'] = 'Account Transfer';
$lang['commission_problem'] = 'Commission Issue';
$lang['order_problem'] = 'Order Issue';
$lang['freight_problem'] = 'Shipping Fee Issue';
$lang['withdraw_funds_problem'] = 'Commission Request Issue';
$lang['walhao_store'] = 'Walhao Shopping Mall';
$lang['tickets_check_order_status']='Check order status';
$lang['tickets_change_delivery_information']='Change delivery information';
$lang['tickets_order_cancellation']='Order cancellation';
$lang['tickets_product_review']='Product review';
$lang['tickets_member_suggestions']='Suggestion';
$lang['other'] = 'Others';
$lang['tickets_after_sales_problem'] = 'After sale issues';
$lang['shipping_logistics_problems'] = 'Trace package';
$lang['tickets_product_damage'] = 'Damaged package';
$lang['tickets_leakage_wrong_product'] = 'Mis-shipment';

$lang['pls_t_type'] = 'Select Issue Type';
$lang['pls_t_title'] = 'Enter Ticket Subject';
$lang['pls_t_tid'] = 'Please enter ticket number';
$lang['pls_t_content'] = 'Enter Ticket Content';
$lang['exceed_words_limit'] = 'Exceed Characters Limit';
$lang['pls_t_uid_aid'] = 'Please enter member ID/Staff ID';
$lang['pls_t_tid_uid'] = 'Enter Ticket #/Member ID';
$lang['remain_'] = 'Remaining';
$lang['max_limit_'] = 'Enter';
$lang['_words'] = 'Characters';
$lang['pls_input_reply_content'] = 'Enter Reply';
$lang['tickets_info'] = 'Ticket Detail';

$lang['pls_t_status'] = 'Select Status';
$lang['pls_t_priority'] = 'Select Priority';
$lang['tickets_status'] = 'Ticket Status';
$lang['new_ticket'] = 'New';
$lang['open_ticket'] = 'Opened';
$lang['waiting_reply'] = 'Pending Reply';
$lang['waiting_discuss'] = 'Pending Counseling';
$lang['ticket_resolved'] = 'Resolved';
$lang['had_graded'] = 'Rated';
$lang['apply_close'] = 'Request Close Ticket ';
$lang['had_apply_tickets'] = 'Already Requested Close Ticket';
$lang['tickets_priority'] = 'Ticket Priority';
$lang['priority'] = 'Priority';
$lang['reply'] = 'Reply';
$lang['general_tickets'] = 'General';
$lang['preferential_tickets'] = 'Priority';
$lang['urgent_tickets'] = 'Urgent';
$lang['change_tickets_priority_fail'] = 'Modify Priority Failed';
$lang['change_tickets_priority_success'] = ' Modify Priority Successful';
$lang['tickets_transfer'] = 'Ticket Transfer';
$lang['transfer_tickets_fail'] = 'Ticket Transfer Failed';
$lang['transfer_tickets_success'] = 'Ticket Transfer Successful';
$lang['change_status_success'] = 'Modify Status Successful';
$lang['change_status_fail'] = 'Modify Status Failed';
$lang['pls_select_customer'] = 'Select Support Agent';
$lang['change_type_success'] = 'Change type complete';
$lang['change_type_fail'] = 'Failed to change type';
$lang['change_tickets_type']='Switch question categories';

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

$lang['tickets_customer_role'] = 'Managing Customer Service Staff';
$lang['tickets_customer_role_1'] = 'CS Staff';
$lang['tickets_customer_role_2'] = 'Manager on duty during holidays';
$lang['tickets_customer_permission'] = 'Authority';
$lang['job_number']='Identification';
$lang['confirm_update_customer_1'] = 'Are you sure to change to as holidy on duty manager?';
$lang['confirm_update_customer_2'] = 'Are you sure to change back to CS Staff?';
$lang['customer_role_invalid_action'] = 'Invalid operation';
$lang['search_data'] = '请输入搜索条件查询';

$lang['tickets_area_usa'] = 'USA Region';
$lang['tickets_area_china'] = 'China Region';
$lang['tickets_area_hk'] = 'Hong Kong Region';
$lang['tickets_area_korea'] = 'Korea Region';
$lang['unique_job_number'] = 'Support ticket already exist';
$lang['job_number_error']  ='Support ticket consists of 3 digits';
$lang['assign_cus_job_number'] = 'Assigned agent number';
$lang['cus_job_number'] = 'Agent number';
$lang['not_customer'] = 'non–supporter';

$lang['button_text'] = 'Select Attachment';
$lang['is_exists'] = 'File Already Exist';
$lang['remain_upload_limit'] = 'Exceed Maximum Number Of Attachments Allowed';
$lang['queue_size_limit'] = 'Exceed Maximum Number Of Attachments Allowed';
$lang['exceeds_size_limit'] = 'File Exceed Size Limitation';
$lang['is_empty'] = 'File Cannot Be Blank';
$lang['not_accepted_type'] = 'File Format Error';
$lang['upload_limit_reached'] = 'Reached Upload Limit';
$lang['attach_delete_success'] = 'Delete Successful';
$lang['attach_no_permissions'] = 'Sorry, Unauthorized Access';
$lang['attach_cannot_find'] = 'File Not Found';
$lang['not_support_mobile_upload'] = 'Mobile phone not able to upload attachments';
/**售后中心 end **/

$lang['user_email_exception_list'] = 'Users send and receive mail exception list';
$lang['goods_number_exception'] = 'Merchandise inventory record';
$lang['admin_trade_repair'] = 'Order repair';

//月费池转现金池日志列表
$lang['old_month_fee_pool'] = 'Before turning the monthly fee pool';
$lang['new_month_fee_pool'] = 'After the transfer of the monthly fee pool';
$lang['cash'] = 'Transfer amount';

$lang['product_freight_delete'] = 'Delete cross freight';
$lang['label_country']='country';
$lang['not_find_this_product_freight'] = 'The record was not found';
$lang['product_freight_not_be'] = 'Freight must be greater than or equal to 0 integer';
$lang['delete_success'] = 'Delete success';
$lang['delete_failure'] = 'Delete failed';
$lang['is_delete'] = 'Once the removal of irreversible, whether to confirm the deletion of the freight?';
$lang['delete_ok'] = 'Delete shipping confirmation';


$lang['choose_group'] = 'Purchase order';
$lang['generation_group'] = 'Substitute security order';
$lang['retail_group'] = 'Retail order';
$lang['all_group'] = 'All types of orders';


$lang['number_zh'] = 'Simplified Chinese stock';
$lang['number_hk'] = 'Traditional Chinese stock';
$lang['number_english'] = 'English stock';
$lang['number_kr'] = 'Korean stock';
$lang['number_null'] = 'The language has no inventory';
$lang['uid_not_null'] = 'User ID cannot be empty';
$lang['uid_ture'] = 'User ID already exists';
$lang['is_uid_delete'] = 'Once the deletion is irreversible, whether to confirm the deletion of the record?';
$lang['is_delete_uid'] = 'Delete user confirmation';
$lang['orderid_ture'] = 'Add success';

$lang['process_num'] = 'Processing times';
$lang['cron_doing'] = 'Script task management';
$lang['cron_name'] = 'Planning task name';
$lang['false_count'] = 'return false frequency';

$lang['order_not_accord_with'] = 'The order does not conform to the rollback condition';
//手动添加138佣金合格人数
$lang['user_qualified'] = '138 Commission on the number of qualified';
$lang['add_user_qualified'] = 'Add 138 Commission qualified number';
$lang['commission_number'] = 'Commission';
$lang['commission_isok'] = 'Confirm add';

//手动添加doba订单
$lang['admin_trade_repair_adddaba'] = 'Manual push Doba order';
$lang['admin_trade_isdoba'] = 'The order has been pushed successfully, no need to repeat push';
$lang['admin_trade_doba_nopush'] = 'Orders need not push';

//用户状态变更记录
$lang['users_status_log'] = 'Member status change record';
$lang['users_status_front'] = 'Status before change';
$lang['users_status_back'] = 'Changed state';
$lang['buckle_fee'] = 'Pay monthly';
$lang['order_fee'] = 'Order fee deduction';
$lang['buckle_fee_error'] = 'No fees';

//佣金管理查询列表
$lang['operator_email'] = 'operator mailbox';
$lang['user_oneself_del'] = 'Member delete';

$lang['is_certificate'] = 'Issuance of certificate';

//佣金查询
$lang['no_time'] = 'Start and end dates for any search have to be within a calendar month';
$lang['no_search'] = 'Please enter the user ID and query';
$lang['no_time_null'] = 'Start date cannot be blank';
$lang['is_certificate'] = 'Issuance of certificate';
$lang['limit_query_month'] = 'You can only search for current data in your current month. Sorry for the inconvenience.';

//活动抵扣月费
$lang['delPlan_title'] = 'Delete member activity record';
$lang['not_join_action_charge_month'] = 'The members did not participate in the activities of the deductible plan';

$lang['not_Porder'] = '不允许修复P开头的零售订单和不允许修复C开头的升级订单或代品券订单';
$lang['not_repeat_insert'] = 'The order number has been added, waiting for processing';
$lang['admin_file_order_freight_error'] = '%sExpress company writing error';
$lang['admin_file_order_show'] = '*Note: only allowed to fill in the 0 column of the courier company or a number greater than 0, 0 for the custom, such as an order two courier company, courier company courier No. 0 fill in the column, column and fill out the express company name and order!';
$lang['order_rollback_show'] = '*Note: this feature applies only to wait for the receipt to roll back to wait for delivery!';
$lang['admin_trade_repair_recovery'] = 'Restore order';
$lang['order_recovery_show'] = 'Note: this function is suitable for the cancellation of the retail orders back to the other, only to change the state and replacement Commission, the need for manual operation to need to charge!';
$lang['order_not_recovery'] = 'Only cancellation or return of retail orders can be restored.';
$lang['order_modify_order_freight'] = '*Note: This function is applicable to the logistics company and the tracking number provided by the supplier. This program only edits the logistics information. When uploading the file, please clear out the mathematical formula of the tracking number, otherwise the formular will appear. At the same time, please note the correct file format as shown below!';
$lang['admin_trade_feright_modify'] = 'Amending tracking information';
$lang['admin_order_status_revert'] = 'Resume to';
$lang['admin_order_commission'] = 'A commission';
$lang['admin_order_not_logistics'] = 'The order information is empty and cannot be returned to the state!';
$lang['all_express'] = '所有快递公司';
$lang['admin_order_repeat'] = '%sExpress single repeat';
$lang['admin_repeat_data']  = 'Fails, repeat the import data';

$lang['admin_order_status_holding_exchange'] = 'Freeze (for replacement)';

$lang['allow_exchange'] = 'Permit replacement';
$lang['ok_cancel'] = 'OK cancel';
$lang['cancel_exchange'] = 'Cancel the replacement';
$lang['go_exchange'] = 'To exchange';
$lang['exchange_order'] = 'A replacement order';
$lang['exchange_remaining_time'] = 'Remaining';
$lang['exchanging'] = 'In exchange';
$lang['exchange_timeout'] = 'Replacement timeout';
$lang['exchange_timeout_msg'] = 'The replacement is not completed within 72 hours and the system is cancelled automatically.';
$lang['cancel_exchange_confirm_msg'] = 'After you cancel the replacement, the upgrade order will not be renewed!';
$lang['exchange_timer_reset'] = 'This will reset the replacement timer. Are you sure you want to do this?';
//修复分红
$lang['add_to_cur_month_queue'] = "Join current month's bonus list";
$lang['user_has_in_queue'] = "Member already on the list";
$lang['user_not_match_daily_bonus'] = "Member does not qualify for Daily Sales Bonus";
$lang['please_select_queue_time'] = "Please select the time to join the list";
$lang['user_order_not_match_new_bonus'] = "Member's order does not qualify for Exclusive Bonus for New Member";
$lang['reward_user_bonus'] = "Reissue member's bonus";
$lang['daily_bonus_failed_not_set'] = "Daily Sales Bonus paid out failed, ratio has not been set";
$lang['daily_bonus_failed_not_set_1'] = "Daily sales Bonus paid out failed, ratio cannot exceed 1";
$lang['not_found_this_day_profit'] = "Global sales profit for the day cannot be found";
$lang['daily_bonus_profit_not_enough'] = "Insufficient profit for Daily Sales Bonus";
$lang['user_level_not_match'] = "Member not qualified since store is not upgraded";
$lang['user_order_amount_not_match'] = "Member has upgraded but does not meet the accumulated retail sales of $50.00 or upgrade order has been cancelled";
$lang['new_member_bonus_failed_rate'] = "Exclusive Bonus for New Member paid out failed, ratio has not been set";
$lang['new_member_bonus_failed_rate_1'] = "Exclusive Bonus for New Member paid out failed, ratio cannot exceed 1";
$lang['new_member_bonus_profit_not_enough'] = "Insufficient profit for New Member Exclusive Bonus";
$lang['daily_bonus_month_error'] = "Daily Sales Bonus cannot cross the month";

$lang['pls_input_reson_1'] = 'Please fill in the reason.If the order is exported, please indicate with the operating staff confirmed that it can be frozen';
$lang['repair_users_amount'] = 'Cash pool';
$lang['not_repair_amount'] = 'Members of the pool of cash balances and capital changes in the same report, the members of the cash pool without exception does not need to repair';
$lang['user_account_total'] = 'The amount of the funds of the system statistics';
$lang['repair_amount'] = 'repair';
$lang['user_amount_total'] = 'Current pool amount';


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
$lang['no_time_all_null'] = '日期不能为空';
$lang['btn_del_option'] = '确定要删除吗？';
$lang['invoice_title_type'] = '发票抬头类型';
$lang['invoice_title_type_company'] = '公司';
$lang['invoice_title_type_personage']='个人';
$lang['invoice_taxpayer_id_number'] = '纳税人识别号';
$lang['invoice_taxpayer_id_number_error'] = '纳税识别号不能为空';

//补发佣金信息
$lang['admin_show_user_monthly'] = "是否显示用户业绩&nbsp;&nbsp;(默认显示最近3个月的数据)";
$lang['admin_show_day_bonsu_monthly'] = "显示每天全球利润分红队列信息";
$lang['admin_show_week_bonsu_monthly'] = "显示每周团队销售分红队列信息";
$lang['admin_show_month_bonsu_monthly'] = "显示每月团队销售分红队列信息";
$lang['admin_day_bonus_list'] = "每天全球利润分红队列";
$lang['admin_week_bonus_list'] = "每周团队销售分红队列";
$lang['admin_month_bonus_list'] = "每月团队销售分红队列";

//解冻用户登录
$lang['unfrost'] = "Unlocking user login";
$lang['please_input_unfrost_account'] = "Please enter the account number that needs to be unlocked";
$lang['please_input_unfrost_account_again'] = "Please re-enter the account number that needs to be unlocked";
$lang['input_unfrost_not_same'] = " Account numbers entered do not match";
$lang['redis_off'] = "Redis service off, do not need to unlock";
$lang['unforst_success'] = "Unlocking successful";
$lang['unfrost_needless'] = "Account does not need to be unlocked";
$lang['pls_input_reson_2'] = 'Please fill in the reason, if the order is exported, please indicate with the operation of the staff to confirm that can be allowed to exchange';
$lang['seleted_input_null'] = "选择不能为空！";
$lang['confirm_add_account_log'] = '确定要补全转账记录吗？';

/*奖金制度管理*/
$lang['incentive_system_management'] = "Bonus management";
$lang['reward_name'] = "Bonus type";
$lang['reward_content'] = "Bonus type";
$lang['reward_status'] = "Display status";
$lang['reward_sort'] = "Sort";
$lang['reward_op'] = "Operate";
$lang['add_reward'] = "Add bonus";
$lang['add_show'] = "Display";
$lang['add_hide'] = "Hide";
$lang['is_show_hide'] = "If displayed";
$lang['users_bonus_list_check'] = "用户佣金队列异常";
$lang['import_third_order_tips'] = 'Before uploading a document, you must first delete the template data!!!';
