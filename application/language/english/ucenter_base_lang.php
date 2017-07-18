<?php
/*paypal提现语言包*/
$lang['paypal'] = 'PayPal';
$lang['paypal_withdraw'] = 'Commission withdrawal via PayPal';
$lang['confirm_paypal_info'] = 'Confirm PayPal information, PayPal account number：{0}';
$lang['account'] = 'account';
$lang['account_name'] = 'Name on account';

//转账提现增加短信验证功能
$lang['mobile_verify_not'] = "手机号未认证";
$lang['not_bind_mobile'] = "您未绑定手机号码，请至<a href = '/ucenter/account_info/index' >账户信息</a>栏目进行验证";
$lang['not_verify_mobile'] = "您的手机号未认证，请至<a href = '/ucenter/account_info/index'>账户信息</a>栏目进行验证";
$lang['mobile_not_confirm'] = "手机号未认证";

/*paypal提现*/
$lang['paypal_prompt1'] = 'The service charge will be 2% on the amount requested with the maximum not exceeding $50.00 per transaction.';
$lang['paypal_email'] = 'PayPal email';
$lang['paypal_email_q'] = 'Confirm PayPal email';
$lang['paypal_binding'] = 'Binding with PayPal';
$lang['paypal_unbundling'] = 'Unbinding with PayPal';
$lang['prompt_titlesa']='Verification code has been sent to your email.';
$lang['prompt_2sa']= 'a. please check your email.';
$lang['paypal_tishi']='Unbound paypal';
$lang['where_code']='Where is the verification code?';
$lang['withdrawal_paypal_tip']="The maximum single withdrawal amount can't exceed $ 60000";
$lang['withdrawal_bank_tip']="(The maximum single withdrawal amount can't exceed $ 12000)";
$lang['withdrawal_paypal_tip']="The maximum single withdrawal amount can't exceed $ 60000";
$lang['withdrawal_paypal_tip2']='The total amount of the extracted file can not exceed $ 60000';
$lang['withdrawal_paypal_tip3']='导出文件的笔数不能超过250笔';
/** 4月份休眠用户活动*/
$lang['april_title'] = 'Tips';
$lang['april_email_title'] = 'News about TPS account from Suspended to Normal';
$lang['april_email_content'] = 'During the month of April, TPS will make a special exception to allow the suspended accounts to be re-activated. The detail is as follows:<br>
If your account has been suspended due to the past due monthly fees, now you have a chance to restore your TPS account status back to "Normal" by just making a total retail sales of $50 during the month of April. Alternatively, you also can choose not to take advantage of this special offer by using your Monthly Fee Bucket or Cash Bucket balance (If there is enough amount to be deducted for the 50% of the total past due monthly fee amount) to pay for the past due monthly fees.<br>
Oh, by the way, we have just posted announcement recently that starting April, Monthly fee for existing store owners will be reduced to half: Silver $10; Gold $20 and Diamond $30.
';
$lang['april_content_1'] = 'The System has detected that you are more than 2 month of monthly fee past due. This month the company has a special plan for those who has past due monthly balance. All you need to do is to complete a retail order of at least $50 at any one of our 3 shopping sites before the end of this month, you account will be unfrozen and restored to normal status that you will continue to receive commissions from your own personal sales and/or team sales.  I decide to';
$lang['april_content_2'] = 'A) Participate this Special Plan;';
$lang['april_content_3'] = 'not join this program, and agree to set moving gap automatically from Cash Bucket to Monthly Fee Bucket to pay monthly fee.(Silver: $10; Gold: $20; Diamond: $30)';
$lang['april_content_4'] = 'B) I am not participate this Special Plan.';
$lang['april_content_5'] = 'Please note that: The retail order of minimum $50 in this plan can\'t be cancelled; The missiong commssions when the account is inactive  will not be paid out, however, the sytem will resume commission payout the moment the $50 order is completed.';
$lang['queue_order_content'] = 'TPS system already received transaction notification of invoice number<span class="msg">%s</span>from PayPal. Since too many joining members, your order is on the waiting list to process commission. Please wait, thank you.';

/** 发送注册验证码 */
$lang['email_captcha_title'] = 'TPS verification code';
$lang['email_captcha_content'] = 'your verification code for TPS： %s，valid for 30 minutes only, please verify as soon as possible!';
$lang['phone_captcha_content'] = '【TPS】your verification code for TPS： %s，valid for 30 minutes only, please verify as soon as possible!';
$lang['reg_success_account'] = 'Please click the login link above, log into your account,full fill your information!';

$lang['ucenter_loc_sure'] = 'Your shipping address is not matching the current shopping region. Would you like the system switch to correct region for you?';


//修改订单地址
$lang['mobile_code_will_send'] = "（Verification code will be sent to the mobile number：<span style color:'red'>:mobile:</span>)";
$lang['mobile_code_has_send'] = "Verification code has been sent to the account bound mobile number :mobile:，Please check！";
$lang['mobile_not_verified'] = "our mobile number has to be verified with the account before the address can be changed!";
$lang['mobile_not_bind'] = "Your mobile number has to bind with the account before the address can be changed! <a style='color:blue;font-style:bold;font-size:16px;' href='/ucenter/account_info'>Bind</a>";


/** 银联预付卡 */
$lang['pre_card_title'] = 'LFG Global UnionPay Prepaid Card';
$lang['pre_card_tip'] = 'For now only members in Korea, Hongkong and Macao can apply';
$lang['pc_name'] = 'Name';
$lang['chinese_name'] = 'Chinese Name';
$lang['pc_card_no'] = 'Card Number';
$lang['pc_card_no_tip'] = 'Leave blank if you have not received the Prepaid Card from TPS';
$lang['pc_card_no_tip2'] = 'Please enter the Prepaid card number correctly for activation.';
$lang['pc_mobile'] = 'Mobile Phone No.';
$lang['pc_nationality'] = 'Nationality';
$lang['pc_issuing_country'] = 'Passport Issuing Country';
$lang['pc_address_prove'] = 'Address Proof';
$lang['pc_ID_card'] = 'ID Number';
$lang['pc_ID_card_type_0'] = 'ID';
$lang['pc_ID_card_type_1'] = 'Passport';
$lang['pc_ID_no'] = 'Enter Valid ID or Passport No.';
$lang['pc_ID_card_upload'] = 'Upload ID Proof';
$lang['pc_ID_card_upload_tip'] = 'Upload Both Front & Back of ID or Picture Page of Passport';
$lang['pc_ID_front'] = 'Front';
$lang['pc_ID_reverse'] = 'Back';
$lang['pc_ID_card_ship'] = 'Card Mailing Address';
$lang['pc_country'] = 'Country of Residence';
$lang['pc_ship_to_address'] = 'Enter detailed Home Address';
$lang['pc_submit'] = 'Apply to Activate';
$lang['pc_email'] = 'Email Address';
$lang['pc_payment_tip'] = 'Card Application Fee $5。Allow 1-2 weeks for activation';
$lang['pc_agreement'] = 'I have read<span class="yued c-hong">《Prepay Card Issuing Agreement》</span>。';
$lang['pc_status_0'] = 'Unpaid';
$lang['pc_status_1'] = 'Pending for Approval';
$lang['pc_status_2'] = 'Rejected';
$lang['pc_status_3'] = 'In Process';
$lang['pc_status_4'] = 'Mailed Out';
$lang['pc_status_5'] = 'Reviewed';
$lang['pc_apply_tip'] = '“LFG”Global UnionPay Prepaid Card, Apply <a href="/ucenter/prepaid_card">Click Here</a>';
$lang['pc_applied'] = 'Have Applied for “LFG” Global UnionPay Prepaid Card';
$lang['pc_applied_success'] = 'Application Successfully Submited, Pending for Approval...';
$lang['check_prepaid_card'] = 'review Prepaid Card';
$lang['pc_address_prove_tip'] = '<br><b>*</b>Please upload one voucher as document provided by GOVERNMENT, water, power Department, banks or other public service agencies.
（all bill or statement must be with 3 months）<br><b>*</b>Please write the agency name on it in English if the voucher is all in Korean.
<br><b>*</b>Please make sure the address you fill in above must match that on the uploaded address proof document';
$lang['prepaid_card_no_exist'] = 'Card No. does not exist';
$lang['assign_card_no'] = 'Assign Card No.';
$lang['assign_card_no_error'] = 'This Card No. is abnormal';
$lang['pc_without'] = 'UnionPay Cards are all Assigned';
$lang['pc_agree_t'] = 'For your personal identity protection, please read the following agreement carefully. ';


$lang['admin_withdrawal_success_content'] = 'Your Cash Withdrawal request with TPS has been processed successfully. Please check the corresponding bank account for detail. ';
$lang['admin_withdrawal_success_title'] = 'TPS cash withdrawal successful notification';

//提现
$lang['withdorw_list_not_null'] = "Cash withdrawal is not allowed since a cancelled order has not been made up yet.";

/*welcome*/
$lang['last_login_info'] = 'Last time you logged in from :city :province :contry at :time';
$lang['mall_expenditure'] = 'Mall Expenditure';
$lang['user_is_store'] = 'User is already a store owner';
$lang['mothlyFeeCoupon'] = 'Monthly Fee Coupon';
$lang['clickToUse'] = 'Click To Use';
$lang['return_back'] = 'Return Of Chargeback Comm';
$lang['order_profit_negative'] = ' Order profit is less than $0.01';
$lang['maxie_mobile'] = 'Maxie Mobile';
$lang['split_order_tip'] = 'Please note that the order has been split because the products you ordered are from different warehouses. We apologize for any inconvenience this change may have caused.';
$lang['order_0_'] = 'Order product is promotional item， there is no profit. But this order is counted as your store sales.';
$lang['upgrade_switch_tip'] = 'We will close store upgrade function temporarily for system maintenance.It will not affect other functions.  We apologize that it may cause the inconvenience for you. Thank you for your understanding and patience.';

/*超过3个月未付款的通知邮件*/
$lang['over3MonthNotyfyTitle'] = 'Monthly fee makeup exception';
$lang['over3MonthNotyfyContent'] = 'Hi, you have received this email is becuase your accout has been past due over 3 months for the monthly fee. To thank you for your patience while we are striving to improve our opportunity, we have made an exception to allow you to restore your account to ACTIVE and NORMAl status by your paying just one month monthly fee instead of paying for all the past due monthly fee. As you may and may not aware, you already have many distributors in your matrix, so it probably make lot of economic sense for you to take the necessary action to restore your account to Active or Normal status, so that you will be able to receive commissions.  Once again, we thank you for your patience.';

/** 提交订单是 地址信息提示 */
//$lang['order_address_error_tip'] = 'Due to wrong shipping address, wrong phone number or unknown receiver, requesting refund, we are going to deduct round trip shipping fee. Please double check your shipping address.';
$lang['order_address_error_tip'] = 'If package cannot be delivered due to incorrect shipping information, the buyer is responsible for the return shipping fee which will be deducted from the refund amount. Please double check the shipping information before submitting the order.';
$lang['edit_address'] = 'Edit Address';
$lang['customs_clearing_number'] = 'Customs Clearing Number';

$lang['read'] = 'Read';
$lang['company_account'] = 'Company Account';
$lang['ok'] = 'Yes';
$lang['cancel'] = 'Cancel';
$lang['_no'] = 'No';
$lang['add'] = 'Add';
$lang['uniqueCard'] = 'Government ID number already existed';
$lang['demote_level'] = 'Chargeback';
$lang['transfer_point'] = 'Commission convert to Bonus Point';
$lang['transfer_cash'] = 'Bonus Point convert to Commission';
$lang['funds_pwd_reset'] = 'Reset PIN';
$lang['yspay'] = 'Yspay';
$lang['funds_pwd_tip'] = 'New PIN must be combination of 8-16 numbers and letters using both uppercase and lowercase.';
$lang['forgot_funds_pwd'] = 'Forgot PIN?';
$lang['payee_info_incomplete'] = 'Payee bank card info is incomplete';
$lang['payee_info'] = 'Payee Info';
$lang['bank_name'] = 'Bank Name';
$lang['bank_card_number'] = 'Bank Account Number';
$lang['c_bank_card_number'] = 'Re-Enter Bank Account Number';
$lang['card_number_match'] = 'The Bank Account is not consistent';
$lang['card_holder_name'] = 'Bank Account Holder Name';
$lang['remark_content'] = 'For members outside China, please enter the SWIFT code here';
$lang['remark'] = 'Remark';
$lang['bank_'] = 'Bank Name';
$lang['bank_name_branches'] = 'Branch Address';
$lang['subbranch'] = 'Branch Address';
$lang['confirm_bank_info'] = 'Confirm your payee information: {0} {1} ,bank account number: {2},bank holder name: {3}';
$lang['confirm_maxie_info'] = 'Confirm your maxie mobile card No: {0}';
$lang['example1'] = ':Citibank';
$lang['example2'] = '';

$lang['withdrawal'] = 'Withdrawal';
$lang['cancel_withdrawal'] = 'Cancel Withdrawal';
$lang['month_fee_date'] = 'Monthly Fee Date';
$lang['month_fee_date'] = 'Monthly Fee Date';
$lang['day_th'] = 'th';
$lang['type_tps'] = 'Bank Info';
$lang['withdrawal_tip'] = 'Keep two decimal places';
$lang['coupon'] = 'Coupon';
$lang['monthly_fee_coupon_notice'] = 'You have been awarded a monthly fee voucher. You can use it to pay for the past due monthly fee or use it at next monthly fee due date.';
$lang['no_active_monthly_fee_coupon'] = 'You have applied your monthly fee voucher.';
$lang['free_mem_have_no_monthly_fee_coupon'] = 'There is no monthly fee voucher for you for your a free member.';
$lang['user_monthli_fee_coupon_success'] = 'You have successfully applied the monthly fee voucher which has been added into your monthly fee bucket.';

$lang['freeze_tip_title'] = 'Alert: The Monthly Fee is due';
$lang['freeze_tip_content'] = '<p>Dear Store Owner,</p>
<p>Please note that because %s—%s period Monthly Fee has been overdue for more than a week, your account has been put into inactive status immediately and all your commissions are stopped. Please make up all your missing mMonthly fee in order to get your account back to normal. Thank you for your immediate attention.</p>
<p>The best regards.</p>
<p>TPS Management Team</p>';
$lang['id_card_num_exist'] = 'Government ID# exist';
$lang['complete_info'] = 'Please verify all personal info, then press the submit button below. Please know that after submit, it is not be able to Edit again. Just wait for TPS to verify.';
$lang['reset_email_tip'] = 'Note: After entering your TPS password, a link will be sent to your <strong style="color: #ff0000">%s</strong> email box.';
$lang['ewallet_email_tip'] = 'Note: After the registration, you are going to receive an email to your <strong style="color: #ff0000">%s</strong> mail box. Please open the email to click the link to activate your account.';

$lang['month_fee_note'] = 'If you don\'t see the change of your monthly fee after completed the payment, please don\'t be panic, try to refresh after a few minutes.';
$lang['payment_note'] = 'If you don\'t see the change of your rank after completed the payment, <br> please don\'t be panic, try to refresh after a few minutes.';
$lang['no_ewallet_name'] = 'Please enter username of eWallet.';
$lang['ewallet_success'] = 'Congratulations, ewallet  apply for success .';
$lang['login_use'] = 'Login username of eWallet.';
$lang['login_email'] = 'Received all email(activation,transfer,notification,etc.) of eWallet .';
$lang['ewallet_name'] = 'I-payout eWallet';
$lang['ewallet_apply'] = 'Apply for eWallet Account';
$lang['ewallet_email'] = 'eWallet Email';
$lang['ewallet_before'] = 'Is processing this request...';
$lang['ewallet_after'] = 'Successful operation,immediate go to eWallet...';
$lang['ewallet_tip'] = 'Please go to My Account &rarr; Account Info apply for eWallet.';

$lang['store_level'] = 'Store Level';
$lang['alert'] = 'Alert';
$lang['disclaimer'] = 'Disclaimer';
$lang['welcome_notice1'] = 'You are Free Member now,  so you have not been placed on individual Forced Matrix and companywide 138 Matrix yet.<br> You will lose those team members who upgrade themselves (to Silver or above ) before you on the Forced Matrix.<a href="/ucenter/member_upgrade" style="color:#fcff00;">Click Here For Immediate Upgrade >></a>';
$lang['welcome_notice2'] = 'You have a Free Store now, so you will not receive your personal sales commissions and any bonus.<a href="/ucenter/member_upgrade">Click Here For Immediate Upgrade >></a>';
$lang['upgrade_notice'] = 'You can go "Direct upgrade step" to simultaneously upgrade monthly fee level and store level. <a class="go_upall_div" href="Javascript: void(0);">Click here to go "Direct upgrade step" >></a>';

$lang['monthly_fee_'] = 'Step 1 ：Matrix Level Upgrade';
$lang['cur_monthly_fee_level'] = 'Monthly Fee : ';
$lang['product_set'] = 'Store Upgrade / Buy Product Set';
$lang['month_fee_user_rank'] = 'The monthly fee is incorrect.';
$lang['month_user_rank'] = 'The monthly level is incorrect.';
$lang['month_fee_rank_empty'] = 'Please complete Step 1';
$lang['upgrade_once_in_all'] = 'Direct upgrade step: [Join Matrix / Upgrade Your Level Of Matrix] + [Buy Product Set]';
$lang['upgrade_all_level_title'] = '[Monthly Fee & Store] Level';

$lang['monthly_fee_level'] = 'Monthly Fee Level';
$lang['diamond'] = 'Diamond';
$lang['gold'] = 'Gold';
$lang['silver'] = 'Silver';
$lang['bronze'] = 'Bronze';
$lang['free'] = 'Free';
$lang['realName'] = 'Full Name';
$lang['user_address'] = 'Address';
$lang['mobile'] = 'Mobile';
$lang['welcome_page'] = 'Welcome Page';
$lang['welcome_msg'] = 'Welcome to TPS';
$lang['review_account_info'] = 'You can go to';
$lang['review_account_info_2'] = 'to view your account info,complete and edit it.';
$lang['view_complete_your_info'] = 'View/Edit Your Account Info';
$lang['up_level'] = 'Upgrade';
$lang['up_level_notice_2'] = 'to upgrade.';
$lang['order_pay_time'] = 'Order Pay Time';
$lang['customer_'] = 'Customer';
$lang['order_amount'] = 'Order Amount';
$lang['individual_store_sales_commission'] = 'My Store Sales Commission';
$lang['order_id'] = 'Order Num';
$lang['commission'] = 'Commission';
$lang['accumulation_commission'] = 'Accumulation Commission';
$lang['commission_log'] = 'Commission Log';
$lang['my_rank'] = 'My Rank';
$lang['profit_sharing_info'] = 'Daily Sales Bonus Info';
$lang['profit_sharing_time'] = 'Daily Sales Bonus Time';
$lang['profit_sharing_require'] = 'Daily Sales Bonus Qualifications';
$lang['profit_sharing_formula'] = 'Daily Sales Bonus Formula';
$lang['profit_sharing_time_content'] = '0:00 on Monday';
$lang['profit_sharing_time_content_month'] = 'Each month at 0:00 on the 8th';
$lang['profit_sharing_require_content'] = 'Need more members Silver.';
$lang['profit_sharing_require_content2'] = 'On their own shops have at least one week, more than $35 paid orders.';
$lang['profit_sharing_require_content3'] = 'On their own shops in January has paid at least 10 orders, and the total amount of $ 350 or more.';
$lang['profit_sharing_formula_content'] = 'My Sharing Points / Total Sharing Point * 4% Of Total Company Last Week Profits';
$lang['profit_sharing_formula_content_month'] = 'My Sharing Points / Total Sharing Point * 10% Of Total Company Last Month Profits';
$lang['profit_sharing_countdown'] = 'The next Daily Sales Bonus will come in';
$lang['profit_sharing_enable'] = 'Can join in Daily Sales Bonus next week?';
$lang['yes'] = 'Yes';
$lang['no'] = 'No';
$lang['profit_sharing_point_to_money'] = 'Daily Sales Bonus Point To Cash Bucket';
$lang['profit_sharing_point_to_money_log'] = 'The Log of Transfering from Daily Sales Bonus Bucket To Cash Bucket';
$lang['no_condition_1'] = 'You are not a member of the silver level and above.';
$lang['no_condition_2'] = 'Order amount of your store is less than $ 35 this week.';
$lang['no_condition_3'] = 'Order amount of your store is less than $ 350 this month.';
$lang['no_condition_4'] = 'Order count of your store is less than 10 this month.';
$lang['sharing_point'] = 'Daily Sales Point Bucket';
$lang['bonus_point'] = '(My Earned Daily Sales Points)';
$lang['bonus_point_note'] = 'Note: Up to 30% of the total Daily Sales Points on the 1st day of each month can be converted to cash bucket for commission withdrawal.';
$lang['first_month_day'] = 'The first day of the month';
$lang['total_point'] = 'Total';
$lang['sharing_point_enable_exchange'] = 'Daily Sales Points Total';
$lang['point'] = ' Point';
$lang['reward_sharing_point'] = 'Bonus Point';
$lang['commissions_to_sharing_point_auto'] = 'Sales Commission To Daily Sales Point Conversion';
$lang['sale_commissions_sharing_point'] = 'Sale Commissions Automatic Sharing Point';
$lang['forced_matrix_sharing_point'] = 'Forced Matrix Automatic Sharing Point';
$lang['validity'] = 'Validity';
$lang['profit_sharing_sharing_point'] = 'Daily Sales Bonus Automatic Sharing Point';
$lang['manually_sharing_point'] = 'Cash Turn Sharing Point';
$lang['sharing_point_to_money'] = 'Convert Daily Sales Point To Cash Bucket';
$lang['proportion'] = 'Proportion';
$lang['cur_commission_lack'] = 'The current lack of money.';
$lang['cur_sharing_point_lack'] = 'The current lack of Daily Sales Bonus point.';
$lang['positive_num_error'] = 'Enter a value greater than 0, if it is a decimal, retain two decimal places.';
$lang['save'] = 'Save';
$lang['save_success'] = 'Save Success!';
$lang['shift_success'] = 'Move success!';
$lang['save_false'] = 'Save False!';
$lang['level_not_enable'] = 'Store Not Enabled';
$lang['profit_sharing_point_log'] = 'The Log of Accumulating Daily Sales Bonus Point';
$lang['pls_sel_profit_sharing_adden_type'] = 'Please select type of Daily Sales Bonus point into';
$lang['current_commission'] = 'Cash Bucket';
$lang['move'] = 'Move';
$lang['to'] = 'To';
$lang['month_fee_fail_notice'] = 'Alert of Past Due for Monthly Fee Payment';
$lang['month_fee_fail_content'] = '
Please note that the System has failed to withdraw monthly fee from your account for the period of %s - %s due to insufficient balance in your Cash Bucket. In order to avoid losing all the privileges and benefits of your account, please reload your Monthly Fee Bucket within %s. Failing to do the needful will cause all the commissions to stop. You can also by <a target="_blank" href="%s">click here</a> to pay the Monthly Fee. Thank you for your attention.
';
$lang['month_fee_fail_content_90'] = '
Please note that your account is now inactive due to its 7 days past due for monthly fee, which results in immediate stop of receiving all commission distributions into your account. Please reload or transfer enough money ASAP to your Monthly Fee Bucket, so that System can withdraw past due amount from your monthly fee bucket in order for your account to resume receiving commissions. You can alsos <a target="_blank" href="%s">click here</a> to pay the Monthly Fee. Thank you for your immediate attention.';
$lang['24hours'] = '24 hours';
$lang['7day'] = '7 days';
$lang['bonus_plan_name'] = 'Dividend name';
$lang['param_a'] = 'Parameter one';
$lang['param_b'] = 'Parameter two';
$lang['param_c'] = 'Parameter three';
$lang['param_d'] = 'Parameter four';




/*我的信息*/
$lang['my_msg'] = 'My profile';

/*我的代品券*/
$lang['exchangeCoupon'] = 'Voucher';
$lang['suitExchangeCouponRule'] = 'Illustration of Voucher Rule';
$lang['suitExchangeCouponRuleContent'] = 'Detail of Voucher Rule';
$lang['only_use_in_exchange'] = 'These products are only limited for voucher exchange';
$lang['num'] = 'Quantity';
$lang['expiration'] = 'Expire time';
$lang['unlimited'] = 'No limit';
$lang['goto_use'] = 'For using';
$lang['no_exchange_coupons'] = 'Currently you do not have voucher.';
$lang['coupons_total_num']='Total :total_num: Voucher';
$lang['value']='Value';

/*关于代品券*/
$lang['about_exchange_coupon'] = 'About Voucher';
$lang['exchange_coupon_1_title'] = 'A. What is voucher';
$lang['exchange_coupon_1_content'] = 'In order to meet customers\' requeirment, merchants a certain certificats to exchange product set and single product. There are 5 denominations for TPS voucher, they are: $100 / $50 / $20 / $10 / $1。It is only limited for members to use this voucher in our online store. It can not be transferd to other members nor be cashed out. It is only appeared when you upgrade your store level for selecting product set. It can be used only in our product set sale special section.';
$lang['exchange_coupon_2_title'] = 'B. The regulation of using voucher';
$lang['exchange_coupon_2_content'] = '1. These vouchers can only be used at product set sale special section；<br />
                2. These vouchers can only be used by member himself/herself at our TPS shopping site. It is not allowed to transfer nor to cash out. It is not allowed to use for online store managing fee nor for shipping fee；<br />
                3. If return item occured, the voucher is going to be back to the member\'s account；<br />
                4. TPS shopping site has the final right to interprete the voucher.';
$lang['exchange_coupon_3_title'] = 'C. Instruction of the method to grant voucher';
$lang['exchange_coupon_3_content_1'] = '1. When a member to upgrade his/her online store level at TPS back office,  the member can select part of product set and single products,  select rest part for voucher in future use. ';
$lang['exchange_coupon_3_content_2'] = '2. Member who check the voucher box when they upgrade online store level, he/she can go to check at <span style="color:#23a1d1;">“My Account—>My Voucher”</span>';
$lang['exchange_coupon_4_title'] = 'D. Common questions about voucher';
$lang['exchange_coupon_4_content'] = '
(1) Can I cash out my voucher？<br />
Answer：No. <br />
(2) If I return the product which was exchanged by vouche, how do I get refund？Do I get voucher back？<br />
Answer：If you returned the item which purchased by voucher，It is going to get full refund. The voucher is going to return to your account.<br />
(3) Can I use voucher to pay TPS online store managing fee or shipping fee?<br />
Answer：No, none of them<br />
(4) When I use voucher to purshase product set and single product, can I get a receipt for amount of voucher I paid?<br />
Answer：No, because you already got receipt when you paid for voucher, you can not get double receipt.';

/*账户信息*/
$lang['member_url'] = 'TPS Store Url';
$lang['member_name'] = 'TPS Store Name';
$lang['modify_member_url'] = 'Edit TPS Store Url';
$lang['member_url_prefix_format_error'] = 'The prefix for TPS Store domain must be 4-15 digits or letters. ';
$lang['url_can_not_be_other_id'] = 'You should not use other TPS Store ID as your website name.';
$lang['store_url'] = 'Walhao Store Url';
$lang['modify'] = 'Edit';
$lang['modify_store_url'] = 'Edit Store Url';
$lang['modify_store_url_notice'] = 'You can only make <span id="storeModifyleftCounts">%s</span> changes';
$lang['modify_member_url_notice'] = 'You can only make <span id="memberModifyleftCounts">%s</span> changes';
$lang['modify_store_url_count_end'] = 'URL Editing your store has run out of opportunities.';
$lang['modify_member_url_count_end'] = 'You have used all your IP change options.';
$lang['store_url_prefix_format_error'] = 'Store URL prefix is only 4-15 digit alphanumeric';
$lang['store_url_exist'] = 'This shop url already exists.';
$lang['url_exist'] = 'This url already exists.';
$lang['modify_store_url_success'] = 'Edit store url success.';
$lang['id_card_scan_type_ext_error'] = 'Id card scan file extention error.';
$lang['id_card_scan_too_large'] = 'Id card scan file size can not bigger than 10M.';
$lang['id_scan_condition'] = 'It can\'t be largger than 10M, in jpg, gif,bmp,jpeg,png format';
$lang['pls_complete_auth_info'] = 'When you see this message, your name and identity have not been verified.';
$lang['enable'] = 'Enable';
$lang['sensitive'] = 'Username exist sensitive words!';
$lang['have_black_word'] = 'There have sensitive words';
$lang['enable_cur_level'] = 'Enable Your Store';
$lang['id_card_scan_ok'] = 'Id Card Scan is ok.';
$lang['not_uploaded'] = 'Not Uploaded';
$lang['person_id_card_num_exitst'] = 'ID number has been submitted before.';
$lang['terms_and_agreement'] = 'Agreement';
$lang['terms_1'] = 'I understand the TPS plan is not an equity investment or stock purchase plan. The one time or monthly fee spent with TPS is for the payment of a Product Set that I will need for my own use as well as the product demo samples, as well as for the setup of a TPS turnkey eCommerce store and marketing site.';
$lang['terms_2'] = '<p>I will not get paid commission by just enrolling other store owners without making product sales on my site, and I will ONLY get paid by acquiring online shopping customers. Both the onetime fee, and the monthly web hosting fee is not refundable after 3 business days free look for those who decide to purchase a Silver, Gold or Diamond Package from the get-go. ';

/*收货地址*/
$lang['shipping_addr'] = 'Shipping Addr';

/*订单中心*/
$lang['order_center'] = 'Order Center';

/*我的订单*/
$lang['my_orders'] = 'My Own Orders';
$lang['my_tps_orders'] = 'My Customer Orders';
$lang['my_walhao_store_orders'] = 'My Walhao Orders';
$lang['order_from_2149'] = 'Walmart';
$lang['order_from_3184'] = 'Macys.com';
$lang['order_from_38606'] = 'Best Buy Co, Inc';
$lang['order_from_38726'] = 'FlowerShopping.com';
$lang['order_from_36808'] = 'TireBuyer.com';
$lang['order_from_13565'] = 'PetSmart';
$lang['order_from_24278'] = 'LampsPlus.com';
$lang['order_from_40846'] = 'Dressilyme';
$lang['order_from_24550'] = 'Vitamin World';
$lang['order_from_2025'] = 'wine.com';
$lang['order_from_36342'] = 'Buy.com';
$lang['order_from_3002'] = 'Beauty.com';
$lang['order_from_24522'] = 'World of Watches';
$lang['order_from_38507'] = 'Famous Footwear';
$lang['order_from_38733'] = 'Sam\'s Club';
$lang['my_affiliate'] = 'My Affiliate Orders';
$lang['my_one_direct_orders'] = 'My 1direct.us Orders';
$lang['order_confirm_time'] = 'Order Confirmation Time';
$lang['order_pay_date'] = 'Order payment time';
$lang['order_update_date'] = '订单更新时间';
$lang['effective_performance'] = 'Effective Achievement';


/* 收据 */
$lang['order_receipt_font'] = "sans-serif";
$lang['order_receipt_company'] = "TPS & Partners Co. Ltd";
$lang['order_receipt_company_address'] = "Address:";
$lang['order_receipt_company_address_detail'] = "21/F, Easy Tower,  No.609 Tai Nan West Street, Cheung Sha Wan, Kowloon, Hong Kong";
$lang['order_receipt_company_phone'] = "Phone:";
$lang['order_receipt_company_phone_detail'] = "(852)2690-0193";
$lang['order_receipt_company_fax'] = "Fax:";
$lang['order_receipt_company_fax_detail'] = "(852)3706-2329";
$lang['order_receipt_company_email'] = "Email:";
$lang['order_receipt_company_email_detail'] = "support@tps138.com";
$lang['order_receipt_purchase_date'] = "Date of Purchase:";
$lang['order_receipt_member_id'] = "Member ID:";
$lang['order_receipt_store_level'] = "Store Level:";
$lang['order_receipt_user_phone'] = "Phone:";
$lang['order_receipt_receiving_address'] = "Receiving<br>Address:";
$lang['order_receipt_title'] = "RECEIPT";
$lang['order_receipt_order_number'] = "Order Number:";;
$lang['order_receipt_detail_product'] = "PRODUCT DESCRIPTION";
$lang['order_receipt_detail_price'] = "UNIT PRICE<span>(USD)</span>";
$lang['order_receipt_detail_qty'] = "QTY";
$lang['order_receipt_detail_amount'] = "AMOUNT<span>(USD)</span>";
$lang['order_receipt_coupons'] = "TPS Coupons";
$lang['order_receipt_product_amount'] = "PRODUCT AMOUNT:";
$lang['order_receipt_coupons_amount'] = "COUPONS:";
$lang['order_receipt_freight'] = "FREIGHT:";
$lang['order_receipt_actual_payment'] = "Actual payment:";
$lang['order_receipt_payment_terms'] = "PAYMENT TERMS:";
$lang['order_receipt_commitment'] = "No reason to return within three days of the company's commitment.";
$lang['order_receipt_payment_billing_unit'] = "BILLING UNIT:";
$lang['order_receipt_thank'] = "THANK YOU! PLEASE COME AGAIN";

/*账户安全*/
$lang['account_safe'] = 'Account Safety';

/*主控面板*/
$lang['cumulative_statistics'] = 'Cumulative Statistics';
$lang['direct_push'] = 'Direct Referral Members';
$lang['cumulative_dividends'] = 'Cumulative Dividends';
$lang['cumulative_forced_matrix_award'] = 'Cumulative Forced Matrix Reward';
$lang['cumulative_sales_commission'] = 'Cumulative Sales Commission';
$lang['announcement'] = 'Announcement';
$lang['recommended_members'] = 'Recommended Members';
$lang['join_time'] = 'Join Time';
$lang['enable_time'] = 'Enabled Time';
$lang['inactive'] = 'Inactive';
$lang['store_rating'] = 'Store Rating';
$lang['cur_title'] = 'Current Title';
$lang['title_level_0'] = 'Store Owner';
$lang['title_level_1'] = 'Master Store Owner (MSO)';
$lang['title_level_2'] = 'Master Store Builder(MSB)';
$lang['title_level_3'] = 'Senior Marketing Director(SMD)';
$lang['title_level_4'] = 'Elite Marketing Director(EMD)';
$lang['title_level_5'] = 'Global Vice President(GVP)';
$lang['profit_sharing_pool'] = 'Daily Sales Bonus Bucket';
$lang['sharing_point_month_limit'] = 'Point to be removed more than the month';
$lang['sharing_point_lacking'] = 'Point lacking.';
$lang['month_fee_pool'] = 'Monthly Fee Bucket';
$lang['cash_pool_to_month_fee_pool'] = 'Move Cash To Monthly Fee Bucket';
$lang['month_1'] = '1 months';
$lang['month_3'] = '3 months';
$lang['month_6'] = '6 months';
$lang['month_2'] = '2 months';
$lang['month'] = 'Months';
$lang['add_fee'] = 'Recharge';
$lang['no_year'] = 'Please select the years';
$lang['no_month'] = 'Please select the months';
$lang['transfer_to_other_members'] = 'Transfer to other members';
$lang['give'] = 'to';
$lang['member'] = 'Member';
$lang['member_id'] = 'Member ID';
$lang['no_need_tran_to_self'] = 'You do not need to transfer to yourself.';
$lang['MEMBER_TRANSFER_MONEY'] = 'Transfers Between Members';
$lang['tran_to_mem_alert'] = 'You will transfer [%s dollars] for member:%s, It is going to make the amount belong to %s. You are going to take any unpredictable risk yourself. Are you sure that you are going to proceed this transfer?';
$lang['funds_pwd'] = 'PIN';
$lang['funds_pwd_error'] = 'Incorrect PIN.';
$lang['no_funds_pwd_notice'] = 'Please go to My Account —> <a href="/ucenter/account_info/index#modifyPIN" class="go_modify_PIN"> Account Info </a> to set up your PIN, if you have not done so.';
$lang['tran_to_mem_china_disabled'] = 'The function of cash transfer between members is closed in Chinese market.';
$lang['monthly_fee_coupon_note'] = 'You still have %s monthly fee coupon for useage .';
$lang['monthly_fee_coupon_note_limit'] = 'Notice：only one monthly fee coupon can be used during 3 months.';

/*佣金报表*/
$lang['commission_report'] = 'Commission Report';
$lang['funds_change_report'] = 'Commission Detail';
$lang['current_month_comm'] = 'Commission Statistic for Current Month:';
$lang['comm_statis_history'] = 'Commission Statistic History:';
//$lang['2x5_force_matrix'] = 'Monthly 2*5 Force Matrix Commission';
$lang['2x5_force_matrix'] = 'Monthly Team Sales Residual Bonus';
$lang['138_force_matrix'] = 'Daily 138 Force Matrix Commission';
$lang['group_sale'] = 'Generation Sales Overrides';
$lang['group_sale_infinity'] = 'Monthly Presidential Sales Bonus';
$lang['personal_sale'] = 'Personal Product Sales Commission';
$lang['week_profit_sharing'] = 'Daily Sales Bonus Bucket ';
$lang['daily_bonus_elite'] = 'Daily Sales Bonus Super Pool';
$lang['day_profit_sharing'] = 'Daily Sales Bonus Bucket';
$lang['week_leader_matching'] = 'Weekly Leadership Matching Bonus';
$lang['month_leader_profit_sharing'] = 'Monthly Top Performers Bonus';
$lang['month_middel_leader_profit_sharing'] = 'Monthly Leadership Bonus';
$lang['month_top_leader_profit_sharing'] = 'Monthly Executive Bonus';
$lang['total_stat'] = 'Total Statistics';
$lang['up_tps_level'] = 'Cost Of Upgrade';
$lang['today_commission'] = 'Today Commission';
$lang['current_month_commission'] = 'Current Month Commission';
$lang['real_time'] = 'Real Time';

/*现金池转月费池*/
$lang['cash_to_month_fee_pool_log'] = 'The Log of Transfering from Cash Bucket To Monthly Fee Bucket';

/*提现*/
$lang['take_out_cash'] = 'Cash Withdrawal';
$lang['take_out_cash_type'] = 'Cash Withdrawal Type';
$lang['bank_card'] = 'Bank Card';
$lang['bank'] = 'Bank';
$lang['bank_card_num'] = 'Bank Card Num';
$lang['payee_name'] = 'Payee Name';
$lang['take_out_max_amount'] = 'Max Amount Of Withdrawal';
$lang['take_out_amount'] = 'Amount Of Withdrawal';
$lang['take_out_pwd'] = 'New PIN';
$lang['password_strength'] = 'Password Strength';
$lang['weak'] = 'weak';
$lang['medium'] = 'medium';
$lang['strong'] = 'strong';
$lang['take_out_pwd2'] = '8-16 numbers and letters using both uppercase and lowercase';
$lang['re_take_out_pwd'] = 'Confirm PIN';
$lang['take_out_cash_notice_1'] = "1) Commission withdrawal requests are processed twice a month: 15th and 30th.  If the commission withdrawal are requested on the 1st-15th, it will be processed on the 30th of the same month; if the commission withdrawal request date occurs on 16th-31st, then it will be processed on the 15th of the following month.
<br>2) Members must upload their picture ID into the TPS back office system and past the name and ID verification before any commission withdraw is granted. This is to prevent the fraud and protect our member's financial interest.
<br>3) The date in all announcement indicated as business date. If it happened to be holiday or weekend, we will take next available working date.";
$lang['take_out_cash_notice_2'] = 'Before the 15th of each month to apply to mention now issued this month';
$lang['take_out_cash_notice_3'] = 'After the 15th of each month to apply to mention now issued on the 15th of next month';
$lang['no_take_cash_pwd'] = 'For first time cash out, please click here to set up a PIN for secure cash withdrawal.';
$lang['had_take_cash_pwd'] = 'Edit PIN';
$lang['take_cash_pwd_exit'] = 'You have set up a password to cash!';
$lang['take_cash_pwd_not_exit'] = 'You have not set a password funds';
$lang['set_take_cash_pwd'] = 'Set Password To Cash';
$lang['set_take_cash_pwd_success'] = 'Set password to cash success.';
$lang['modify_take_cash_pwd_success'] = 'Edit PIN successfully';
$lang['modify_take_out_pwd'] = 'Edit Password To Cash';
$lang['old_take_out_pwd'] = 'Old PIN';
$lang['take_out_success'] = 'Withdrawals success.';
$lang['pls_sel_take_out_type'] = 'Please select Withdraw way.';
$lang['pls_input_correct_amount'] = 'Please fill in the correct amount.';
$lang['pls_input_correct_amount2'] = 'Requested commission withdrawal for over at least $100.';
$lang['pls_input_correct_take_out_pwd'] = 'Funding password entered incorrectly';
$lang['pls_pwd_retry']='Too many errors, please try or reset your funds password in one hour';
$lang['not_fill_alipay_account'] = 'You have not set the PayPal account.';

$lang['withdrawal_fee_'] =  'Withdrawal processing fee';
$lang['withdrawal_actual_'] =  'Actual amount credited';
$lang['withdrawal_alipay_'] = 'Alipay Account number';
$lang['withdrawal_alipay_tip'] = 'Alipay limit, amount per withdrawal cannot exceed: $ 7000';
$lang['withdrawal_alipay_tip2'] = 'Alipay withdrawals processing fee per transaction cannot exceed $5';
$lang['confirm_alipay_info'] =  'Confirm Alipay information and account number：{0}';
$lang['alipay_actual_name']=' Alipay legal name';
$lang['alipay_binding']= 'Link Alipay';
$lang['alipay_unbundling']= 'Disable Alipay';
$lang['alipay_binding_accounts']= 'Alipay account number';
$lang['alipay_binding_accounts_q']= 'Confirm Alipay account number';
$lang['alipay_binding_vcode']= 'Verification Code';
$lang['alipay_binding_name_prompt']= 'Please enter real name registered with Alipay account';
$lang['alipay_binding_email']='Binding Mailbox Alipay account';
$lang['repeat_account'] = 'Alipay account number already existed!';
$lang['confirm_account'] = 'Please enter Alipay account number again';
$lang['different_account'] = 'Alipay account number entered does not match';
$lang['prompt_title']=  'Verification code has been sent to your Alipay account.';
$lang['for_example']= 'For example';
$lang['prompt_1']='a.Your Alipay account number is your mobile number, please check your text message.';
$lang['prompt_2']= 'b.Your Alipay account number is your email address, please check your email.';
$lang['forms_authentication_geshi']= 'Incorrect format, please try again';
$lang['forms_authentication_num']= 'Alipay account numbers entered do not match, please try again';

/*提现记录*/
$lang['cash_take_out_logs'] = 'The Log of  Cash Withdrawal';
$lang['cash_take_out_account'] = 'Cash Withdrawal Account';

/*會員升級*/
$lang['join_fee'] = 'Product Set';
$lang['cur_level'] = 'Current Store Level';
$lang['pls_sel_level'] = 'Please Select Level';
$lang['no_need_upgrade'] = 'No Need Upgrade';
$lang['amount_cannot_be_empty'] = 'The amount can not be empty';
$lang['pls_sel_payment'] = 'Please Select Payment Method!';
$lang['info_need_complete_for_pay_member'] = 'Infomation Need To Complete For Pay Member';
$lang['pay_success'] = 'Pay Success.';
$lang['submit_success'] = 'Submit Success.';
$lang['pls_complete_info'] = 'Please fill in the personal information section full of your ID number and identity card.';
$lang['pls_enable_level'] = 'Please enable your level in the account info column.';
$lang['change_monthly_level'] = 'Change Monthly Fee Level';
$lang['pls_sel_monthly_level'] = 'Please select monthly fee level';
$lang['cannot_change_monthly_fee_level'] = 'You can not change the monthly fee level!';
$lang['no_change'] = 'No modification.';
$lang['month_fee_level_change_note'] = 'Monthly fee level is going to be changed to %s at next due day.';
$lang['month_fee_level_change_desc'] = 'You can change your store and monthly fee level here. After modified, next month store level is going to be calculated as new level you chosed. Please know that your store level is going to adjusted the same as your monthly fee level if the store level is higher than your monthly fee level.';

//账户信息
$lang['start_date'] = 'Start Date';
$lang['end_date'] = 'End Date';
$lang['input_start_date'] = 'Please input start date!';
$lang['input_name_rule'] = 'User name length must be more than 3 characters.';
$lang['input_store_name_rule'] = 'Store name length must be more than 3 and less than 40 characters.';
$lang['input_store_name_exit'] = 'Store name already exists';
$lang['input_store_name_tip'] = 'Note：For store name, please no more than 40 characters.';
$lang['input_name_rule_100'] = 'User name length must be less than 100 characters.';
$lang['input_end_date'] = 'Please input end date!';
$lang['input_date_error'] = 'Start date is greater than end date!';
$lang['input_username'] = 'Please enter your user name!';
$lang['account_success'] = 'Information Edit success!';
$lang['account_error'] = 'Information failed to Edit or not change!';
$lang['submit'] = 'Submit';
$lang['email'] = 'Email';
$lang['profile'] = 'Profile';
$lang['username'] = 'Username';
$lang['ori_password'] = 'Original Password';
$lang['new_password'] = 'New Password';
$lang['re_password'] = 'Re Enter Password';
$lang['country'] = 'Country';
$lang['upgrade']=' Upgrade ';
$lang['downgrade']=' Downgrade ';
$lang['month_upgrade_from']='Monthly Fee Level from ';
$lang['shop_upgrade_from']='Store Level from ';
$lang['upgrade_to']=' to ';
$lang['downgrade_to']=' to ';
$lang['month_upgrade_log_label']='Record of Monthly Fee Level adjustment';
$lang['shop_upgrade_log_label']='Record of Store Level adjustment';

$lang['modify_mobile_number'] = 'modify mobile number';
$lang['pls_input_new_number'] = 'Please input the new mobile number';
$lang['modify_success'] = 'Modification successful';

$lang['check_card_wait'] = '<div style="text-align: center;font-family: 微软雅黑;font-size:14px;font-weight:blod;line-height:25px;">ID verification will take approximately 2 minutes,</div><div style="text-align: center;font-family: 微软雅黑;font-size:14px;font-weight:blod;line-height:25px;">Please be patient !</div>';

$lang['check_exceed_three'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:12px;font-family:微软雅黑;">Your ID verification has to be verified manually since it has failed three times.</div><div style="margin-top:10px; font-size:12px; text-align:center;font-family:微软雅黑;">Please be patient.</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">OK</button></div>';

$lang['check_taiwan_card'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:12px;font-family:微软雅黑;">Your ID verification has to be verified manually.</div><div style="margin-top:10px; font-size:12px; text-align:center;font-family:微软雅黑;">Please be patient.</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">OK</button></div>';

$lang['check_passed'] ='<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/correct.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">ID verification approved!</div><div style="margin-top:20px; text-align:center;"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">OK</button></div>';

$lang['check_failed'] = '<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/error.png"/></div><div style="text-align:center; color:#000;font-size:20px;font-family:微软雅黑;line-height:25px;">ID verification failed</div><div style="color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;margin-top:25px;box-sizing:border-box;padding-left: 20px;">Possible reason :</div><ul style="margin:0;list-style:none;color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;padding-left: 20px;" ><li style="line-height: 25px;">( 1 )Information entered not consistent with ID card</li><li style="line-height: 25px;">( 2 )Image of ID card not clear</li><li style="line-height: 25px;">( 3 )Under 18 years of age</li></ul><div style="list-style:none;color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;padding-left: 20px;">Please review the reason for failure and re-upload your ID card for verification!</div><div style="margin-top:20px; text-align:center"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">OK</button></div>';

$lang['check_maintenance'] = '<div style="text-align: center; padding:10px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:16px;font-family:微软雅黑;padding:0 80px;line-height:25px; box-sizing:border-box;">ID verification under maintenance,</div><div style="margin-top:20px; font-size:16px; text-align:center; font-family:微软雅黑;">Please try again in 2 hours.<div style="margin-top:15px; text-align:center;"><button  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;" onclick="confirm_card()"  type="button">OK</button></div>';

$lang['upload_failed'] = 'Sorry,Upload failed!';

$lang['check_passed_info'] = 'Your ID verification was approved on <span style="color:red;" >%s</span>.';

//团队销售提成奖励
$lang['current_algebra_title'] = 'Cumulative Team Commission Algebra';
$lang['current_rank'] = '(Current Store Level)';
$lang['QSOs'] = '(Qualified Store Owner)';
$lang['QRCs'] = '(Qualified Retail Customer)';
$lang['current_algebra'] = '(Now Enjoy Team Commission Level)';
$lang['learn_more_rule'] = 'Learn More This Award Rules';
$lang['freeze'] = 'Suspended';
$lang['enjoy_gold'] = 'Gold Team Sales Bonus';
$lang['enjoy_diamond'] = 'Diamond Team Sales Bonus';


//无限代奖励
$lang['infinity_con1'] = 'Must be a Diamond SO last month';
$lang['infinity_con2'] = 'Accumulated more than 3000 Silver or above stores.(At least two branches: each branch no more than 1500 Silver or above stores)';
$lang['infinity_con3'] = 'Individual stores accumulated 30 QRCs';
$lang['infinity_countdown'] = 'Distance rewards next time will come in';
$lang['infinity_enable'] = 'Can join in rewards next month';
$lang['infinity'] = 'Monthly Presidential Sales Bonus';
$lang['infinity_title'] = 'Infinity Team Sales Bonus';
$lang['infinity_info'] = 'Infinity Team Sales Bonus Info';
$lang['infinity_log'] = 'Infinity Team Sales Bonus Log';
$lang['infinity_date_title'] = 'Infinity generation rewards time';
$lang['infinity_date_content'] = 'Next month on the 10th';
$lang['infinity_qualifications_title'] = 'Infinity Team Sales Bonus qualifications';
$lang['infinity_formula_title'] = 'Infinity Team Sales Bonus formula';
$lang['infinity_formula_content'] = 'Qualifiers get  the more than 11th generation of total sales profit× 0.5%';
$lang['qualified_time'] = 'Qualified Month';
$lang['grant_time'] = 'Statistics Qualified Time';
$lang['is_grant'] = 'Whether Grant';

//上传头像
$lang['user_avatar'] = 'User Avatar';
$lang['new_user_avatar'] = 'New User Avatar';
$lang['upload'] = 'Upload';
$lang['upload_avatar'] = 'Upload Avatar';
$lang['reselect'] = 'Reselect the picture';
$lang['cropped_tip'] = 'Tip: Please select image cropping area.';
$lang['upload_tip'] = 'You can upload JPG, GIF or PNG file format, the file size can not exceed <strong>1.0MB</strong>.<br>The provisions of width and height can not exceed <strong>1024*800</strong>.';

//用户升级 购买会员
$lang['current_level'] = 'Current Store Level';
$lang['member_level'] = 'Member Level';
$lang['opening_time'] = 'Opening Time';
$lang['validity'] = 'Expiration Date';
$lang['payment_method'] = 'Payment Method';
$lang['amount'] = 'Amount Payable';
$lang['confirm_purchase'] = 'Confirm Purchase';
$lang['buy_now'] = 'Buy Now';
$lang['buy_member'] = 'Buy Member';
$lang['go_pay'] = 'Pay Now';
$lang['payment_tip'] = 'Payment Tip';
$lang['upgrade_level'] = 'Member Upgrade';
$lang['annual_fee'] = 'Annual Fee';
$lang['monthly_fee'] = 'Monthly Fee';
$lang['alipay'] = 'Payment Page';
$lang['payment_content1'] = "Before the payment is completed,please do not close this window payment verification.";
$lang['payment_content2']= "You pay according to the situation after the payment is completed click the button below.";
$lang['payment_success']= "Payment completion";
$lang['payment_error']= "Pay encounter problems";

//奖励制度介绍
$lang['reward_tip'] = '<strong>QSO (Qualified Store Owner): </strong><ul><li>For Paid Store: <ol><li>Purchase a Bronze,Silver, Gold or Diamond Product Set;</li><li>Pay monthly fee.</li></ol></li><li>For FREE Store:<ol><li>The store owner has accumulated retail orders of $50.</li></ol></li></ul>';
// $lang['reward_tip2'] = '<strong>QRC (Qualified Retail Customer):</strong><ul><li>Purchase a product value of $25;</li><li>Can’t be the store owner himself or herself.</li></ul>';
$lang['directly'] = 'My Personal Stores';
$lang['rewards_introduced'] = 'Comp Plan';
$lang['r1'] = 'Personal Product Sales Commission';
$lang['r2'] = 'Generation Sales Overrides';
$lang['r3'] = 'Companywide Forced Matrix';
$lang['r4'] = 'Daily Sales Bonus Pool';
$lang['r8'] = 'Monthly Sales Bonus Pool';
$lang['r5'] = 'Weekly Leadership Matching bonus';
$lang['r6'] = 'Monthly Leadership Bonus Pool';
$lang['r7'] = 'Infinity Team Sales Overrides';
$lang['r1_content'] = '20% of sales profit on individual retail product sales made at the store owner’s shopping site. ';
// $lang['r1_content_notice'] = '<span class="label label-important">Notice!</span>Qualified FREE store owner will not get paid for the first personal order of $25 he or she placed on his or her own site. It will be credit towards the sponsor as a qualifying retail customer (QRC) and the sponsor will get paid the commission. ';

$lang['r2_content1'] = '<ul><li>[Free Store]</li></ul>';
$lang['r2_content1_1'] = 'Condition：<br/>Qualified Free Store Owner.<br/>
Bonus：<br/>You will receive 5% on 1st Generation Sales Overrides.';
$lang['r2_content_1'] = '<ul><li>[Silver QSO]</li></ul>';
$lang['r2_content_2'] = '<ul><li>[Gold QSO]</li></ul>';
$lang['r2_content_3'] = '<ul><li>[Diamond QSO]</li></ul>';
$lang['r2_content_5'] = '<ul><li>[Bronze QSO]</li></ul>';
$lang['r2_content_5_1'] = 'Condition：<br/>Qualified Bronze Store Owner.
<br/>Bonus：<br/>You will receive 10% on 1st Generation Sales Overrides and 5% on 2nd Generation Sales Overrides.';
$lang['r2_content_1_1'] = 'Condition：<br/>Qualified Silver Store Owner.<br/>
Bonus：<br/>You will receive 12% on 1st Generation Sales Overrides and 7% on 2nd Generation Sales Overrides.';
$lang['r2_content_2_1'] = 'Conditon：<br/>Qualified Gold Store Owner.<br/>
Bonus：<br/>You will receive 15% on 1st Generation Sales Overrides and 10% on 2nd Generation Sales Overrides.';
$lang['r2_content_3_1'] = 'Condition：<br/>Qualified Diamond Store Owner.<br/>
Bonus：<br/>You will receive 20% on 1st Generation Sales Overrides and 12% on 2nd Generation Sales Overrides.<br/><br/><br/>';

/*  每月团队组织分红奖  */
$lang['r3_content_1'] = '
New Qualification for the “Monthly Team Sales Residual Plan”: <br/>
1) A Qualified Diamond QSO  who have accumulated retail sales of $100  in their store during the previous calendar month.<br/>
2) The QSO must be a Master Store Builder (MSB).<br/><br/>
New Payout Rule: <br/>
Commission will be distributed based on a member\'s current rank, the current store level, and the total PERSONAL retail sales amount from the previous calendar month. The company will allocate 10% of the profit from total global sales of the current month towards this monthly pool.<br/>
<br/>
 New Payout Date:<br/>
Commission for the previous month will be distributed on the 15th of the following month. Example, store owners who qualified in November will receive the commission on December 15th and so on.
';
$lang['r3_content_2'] = '';

$lang['r3_content_5'] = '';
$lang['r3_content_3'] = '';

/* 每天全球利润分红奖 */
$lang['r4_content_1'] = 'Qualification: ';
$lang['r4_content_2'] = 'For Paid QSOs, you need  have made retail sales of $25 accumulative.';
$lang['r4_content_3'] = 'For Free QSOs, you need  have made retail sales of $100 accumulative.';
$lang['r4_content_4'] = 'Calculation: <br/> This daily sales bonus will be paid out based on the member\'s store level, and the retail order amount of the previous month. The higher the store level, the more retail order sales from the previous month, the more daily sales bonus will be paid out. <br/><br/> Company will pay out 10% from the total global sales profits of the previous day into this daily bonus pool.' ;

/* 138分红 */
$lang['r9_content_1'] = '<ul><li>Qualification To Be Placed In The 138 Matrix:<br/>
Any Bronze (or above) store, or any free store owner who has passed name and ID verification. </li></ul>';
$lang['r9_content_2'] = '<ul><li>Qualification to Get Paid in This Matrix: </br></br>
Bronze or above store owners who has accumulated retail sales of $50.00 or above during previous calendar month will receive 138 Matrix Bonus on the first day of the following month.
</li></ul>';
$lang['r9_content_3'] = '<ul><li>Matrix Placement:
138 stores will be lined up from left to right until all the 138 spots in the first line of the matrix is filled. No. 139 store will fill out the first position from left to right on the 2nd row, so on and so forth.</li></ul>';
$lang['r9_content_4'] = 'At 12.01am of every day, all the Bronze and above store owners in the 138 matrix will receive a % of the gross sales profit from all product sales including product set sale and individual product sales made by all stores in this matrix prorated based on the number of stores spilled over from top to bottom in each straight vertical line. Every day, 10% gross sales profit from all stores sales placed in this matrix will be contributed to 138 Daily Matrix Bonus Pool， with 5% being paid to each Silver and above store owner based on the number of people in his or her vertical line, and another 5% to be split even among all the Bronze and above store owners.';


/* 每周领导对等奖  */
$lang['r5_content_1'] = 'This program is designed to help those leaders who help their new team store owners to achieve a fast start of the new store owner’s business.';
$lang['r5_content_2'] = 'The qualification to receive such Weekly Leadership Matching Bonus is:';
$lang['r5_content_3'] = '<ul><li>Must be a Diamond QSO;</li><li>The QSO must be a Master Store Builder (MSB);</li><li>Has Accumulated retail sales of $100 during the previous calendar month. </li></ul>Once a QSO meets the above qualifications, TPS will pay the QSO 5% of his or her 1st & 2nd generation’s last week’s commission the following Monday.';
$lang['r5_content_4'] = '<span class="label label-important">Note: <br/>
 The above order dollar amount are monthly recurring.
</span>';

/* 每月杰出店铺分红奖   */
$lang['r6_content_1'] = 'The Monthly Top Performers Bonus Pool is designed to reward those sales leaders or top sales producers. The qualifications are as follows:';
$lang['r6_content_2'] = '
Any MSO (Silver or above)  who has made retail sales of $100 accumulative during the previous calendar month;<br/><br/>
TPS will allocate 10% of the previous month’s global sales profits to the Monthly Top Performers Bonus Pool and pay out to the QSOs on the 15th the following month based on the number of daily sales points at the end of previous calendar month.<br/>
Calculation method: (Global Sales Profit of previous month * 4%/ Sum of all qualified members) + (my Own Daily Sales Points/ Total Daily Sales Points of all Qualified Members * Total Global Sales Profit of previous month * 6%).
';
$lang['r6_content_3'] = '<span class="label label-important">Note: The above order dollar amount are monthly recurring.</span>';


$lang['r7_content_1'] = 'This plan is for those leaders who has built a large sales team.';
$lang['r7_content_2'] = 'The qualification is as follows:<br/><br/>
	a)	Must be a Diamond QSO;<br/>
	b)  Must be Global Vice President (GVP)<br/>
	c)	Must have a total of 3,000 QSOs, 50% rule applies, which means each branch can only count up to 1500 active QSOs towards the total store number requirement;<br/>
	d)	Must have made personal retail sales of $250 accumulative during the previous calendar month.<br/><br/>
TPS will allocate 0.25% of the previous month team gross profit from all product set sales and individual product sales that are not included in Comp Plan #2 -- Team Sales Commission, and pay the qualifier on the 15th of the following month.<br/><br/>
<span class="label label-important">Note: The above order dollar amount are monthly recurring.</span>
';

$lang['r8_content_1'] = '<ul><li>Must be a Qualified Diamond QSO;</li><li>Must be an EMD;</li><li>The EMD must have made personal retail sales of $100 accumulative during the previous calendar month.</li></ul>';
$lang['r8_content_2'] = '1% of company global profits shared equally among qualified EMDs. The EMD bonus for the current month will be paid out on the 15th of the following month.<br/><br/><span class="label label-important">Note: The above order dollar amount are monthly recurring.</span>';

/*  每月领导分红奖   */
$lang['r10_content_1'] = '1) SMD Bonus<br/>
The qualification is as follows:
<br/>
a）Must be a Qualified Diamond QSO;
<br/>
b）Must be an SMD;
<br/>
c）The SMD must have made personal retail sales of $100 accumulative during the previous calendar month.
<br/><br/>
3% of company global profits shared equally among qualified SMDs. The SMD bonus for the current month will be paid on the 15th of the following month.<br/><br/>

2) EMD Bonus<br/>
The qualification is as follows:
<br/>
a）Must be a Qualified Diamond QSO;<br/>
b）Must be an EMD;
<br/>
c）The EMD must have made personal retail sales of $200 accumulative during the previous calendar month.<br/><br/>
1% of company global profits shared equally among qualified EMDs. The EMD bonus for the current month will be paid out on the 15th of the following month.<br/><br/>

3) GVC Bonus<br/>
The qualification is as follows:
<br/>
a）Must be a Qualified Diamond QSO;<br/>
b）Must be an GVC;
<br/>
c）The GVC must have made personal retail sales of $300 accumulative during the previous calendar month.<br/><br/>
0.5% of company global profits shared equally among qualified GVCs. The GVC bonus for the current month will be paid out on the 15th of the following month.<br/><br/>
';
$lang['r10_content_2'] = '<span class="label label-important">Note: The above order dollar amount and order numbers are monthly recurring.
</span>';

/*销售精英日分红*/
$lang['r11_content']="
Requirement:<br/>
1）All qualified store owner, include free store owner;<br/>
2）Complete a minimum product set sale of $250, or retail sales totalling $250 in your own store during the previous month.<br/>
<br/>
Reward:You will receive the Super Daily Bonus during the current month if you complete the above sales requirement during the previous last month.<br/>
<br/>
Daily Super Bonus Calculation: Each qualified store's last month sale / The total sale amount of all qualified store owners * 10% of the previous day's company total sale profit.<br/>
<br/><br/>
<span class='label label-important'>Note: The qualification for this Daily Super Bonus is monthly recuring.</span>
";

/* 新会员专享奖  */
$lang['r12_content_1'] = 'Effective April 1st, 2017, all new members who upgrade to Bronze Store (or above) and have accumulated retail sales of $50 (or more) will start receiving a share of this "New Qualifier\'s Exclusive Daily Bonus" beginning the following day up to 30 days from the date of their store upgrade.<br/>The size of the bonus is determined by their store level and the amount of their retail sales order.Obviously, members with higher store level and higher retail sales amount will have bigger bonuses. <br/><br/> The formula for calculating this bonus is as follows: <br/> New Qualifier\'s Exclusive Daily Bonus = Total personal store sales amount (store upgrade orders + regular retail orders) / Total sales amount for all qualified new members  *  2% of company\'s sales profit from the previous day.';

/*每周团队销售分红*/
$lang['r_week_share_content'] = "
 It is designed to reward those sales leaders to help their team to increase sales. The bigger the sales team, the leader will earn more in this new weekly pool.<br/><br/>
 The qualifications are as follows:<br/>
Any Diamond QSO who has made retail sales of $100 accumulative during the previous calendar month.<br/>
The QSO must be a Master Store Owner (MSO).<br/><br/>
TPS will allocate 10% of the global sales profits from the previous week to the “Weekly Team Sales Bonus” and pay out the following Monday.<br/>
The calculation will be based on the member’s qualifying at the end of the previous month: member’s rank, personal store level, total retail sales (min $100, but the more the better), and the total daily sales points. <br/><br/>
Note: The above order dollar amount are monthly recurring.
";

/*佣金补单*/
$lang['commission_order_repair'] = "Orders to Make Up";
$lang['repair_order_year_month'] = "Orders to Make Up for year/month";
$lang['commission_type'] = "Bonus/Commission Type";
$lang['commission_year_month'] = "Bonus/Commission for year/month";
$lang['sale_amount_lack'] = "Required amount for the Orders to Make Up";
$lang['deadline'] = "Deadline";
$lang['repair_order'] = "Additional Order";
$lang['order_repairing'] = "Orders to Make Up in Progress ...";
$lang['score_year_month'] = "Achievement for year/month";
$lang['comm_date'] = "Achievement Date";
$lang['commission_withdraw_amount'] = "Amount of Bonus Deduction";
$lang['if_not_repair_order_before_deadline'] = "Days Left for the Make-Up Order(s)";
$lang['order_repair_notice'] = "Please note：<br/>
1、The following list indicates that the required order amount needed to make up to avoid commission pullbacks within 14 days.<br/>
2、The amount of a make up order will only be accounted for the month that requires the make up order.
";
$lang['order_repair_step'] = "Procedure of Placing a Make-Up Order：<br/>
1、Click the \"Order(s) to Make Up\" button and the system will take you to your store for you to make up the order(s). At this moment, the button status will be changed to \"Order(s) to Make Up in Progress...\". <br/>
2、Place an order in your own TPS Store. If the Order(s) to Make Up amount is greater than required order amount, the Order(s) to Make Up is completed.
";
$lang['modifyVal_illegal'] = "Incorrect Expiration Date";

//职称晋升的介绍
$lang['rank_advancement']="Rank Advancement";
$lang['mso']="a) Master Store Owner (MSO):";
$lang['mso_context']="Qualification: You are a Bronze(or above) store owner first, then you have to sell 3 Bronze(or above) product sets yourself.";

$lang['sm']="b) Master Store Builder(MSB):";
$lang['sm_context']="Qualification: In his or her genealogy tree, there need to be 3 MSOs, with 1 from each branch and minimum 3 branches.";

$lang['sd']="c) Senior Marketing Director(SMD):";
$lang['sd_context']="Qualification: In his or her genealogy tree, there need to be 3 MSBs, with at least 1 from each branch and minimum 3 branches.";

$lang['vp']="d) Elite Marketing Director(EMD):";
$lang['vp_context']="Qualification: In his or her genealogy tree, there need to be 3 SMDs, with 1 from each branch and minimum 3 branches.";

$lang['gvp']="e) Global Vice President(GVP):";
$lang['gvp_context']="Qualification: In his or her genealogy tree, there need to be 3 EMDs, with 1 from each branch and minimum 3 branches.";

$lang['finally explanation right']="f) This company has the finally explanation right.";

$lang['back_account'] = '<span style="color: purple"></span> seconds return account...';


$lang['Bulletin_title'] = 'TPS Bulletin Board';
$lang['upload'] = 'Upload';
$lang['important'] = '<span style="color:#f00;font-weight:bold;">Very Important:</span>';


$lang['take_out_cash_sum'] = 'The total Amount of Withdraw already：';
$lang['transfer_to_cash_sum'] = 'The total Amount transferring to other members：';
$lang['enroll'] = 'Enroll Url';
$lang['no_collection'] = 'Currently you do not have any favorite products on your wish list.';
$lang['try_again'] = 'Try Again';
$lang['receipt_title_'] = 'TPS Shopping Site Receipt';
$lang['receipt_content_'] = 'Please find attached receipt from TPS Shopping Site.';
$lang['deliver_title_'] = 'TPS order delivery notification';
$lang['deliver_content'] = 'On%s, Your purchase is completed, order # %s, shipping company is: %s, shipping number is: %s. Please keep tracking.';
$lang['order_date'] = 'Order Date';
$lang['order_amount_no'] = 'Order Amount';
$lang['order_amount_no_tip'] = 'Order amount does not include shipping fee';
$lang['customer_name'] = 'Customer Name';
$lang['filter_month'] = 'Month';
$lang['cancel_collection'] = 'Take Out Of My Wish List';

$lang['you_also_not_choose_product']='You need to choose more items, please enter from Welcome Page and select.';
$lang['dear_'] = 'Dear ';
$lang['email_end'] = 'Please feel free to contact customer service at support@shoptps.com.<br>
Regard.<br>
TPS Management Team';

$lang['account_status']='Account Status:';
$lang['fee_num_msg']='<span style="color: #880000;">(Cumulative :fee_num: months fee is due)</span>';
$lang['fee_num_msg_one']='<span style="color: #880000;">(Cumulative :fee_num: month fee is due)</span>';
$lang['sale_rank_up_time']='(The title reached in <span style="color: #F57403">:sale_rank_up_time:</span>)';
$lang['month_fee_arrears']='Please pay monthly fee first, then go to upgrade.';

//^^^^
//$lang['daily_top_performers_sales_pool'] = 'Daily Top Performers Sales Pool';
$lang['daily_top_performers_sales_pool'] = 'Daily Sales Bonus Super Pool';//銷售精英分紅
$lang['cash_pool_auto_to_month_fee_pool'] = 'The cash pool automatic Transferred to month fee pool';

$lang['new_func'] = 'Message';
$lang['auto_to_month_fee_pool_notice'] = "
<p>Dear tps members, in order to facilitate your payment of monthly fee,</p>
<p>We adde a new function which automatically transferred cash pool to monthly fee pool.</p>
<p>Once you set up this option, the system will automatically transfer the monthly shortfall from cash pool to monthly fee pool, if the remaining sum of </p>
<p>monthly fee pool is insufficient to pay a monthly fee.<a href='ucenter/commission#month_fee_auto_to'>Click here to go to the Settings</a></p>";

/** 售后中心 start **/
$lang['tickets_center'] = 'Help Center';
$lang['add_tickets'] = 'Add Ticket';
$lang['my_tickets'] = 'My Ticket';
$lang['tickets_cus_num']='Support Agent #';
$lang['tickets_title'] = 'Ticket Subject';
$lang['tickets_id'] = 'Ticket Number';
$lang['tickets_info'] = 'Ticket Detail';
$lang['pls_input_title'] = 'Enter Subject';
$lang['exceed_words_limit'] = 'Exceed Characters Limit';
$lang['count_'] = 'Total';
$lang['remain_'] = 'Balance';
$lang['max_limit_'] = 'Maximum';
$lang['_words'] = 'Characters';
$lang['waiting_progress'] = 'In Process';
$lang['in_progress'] = 'Follow Up';
$lang['ticket_resolved'] = 'Resolved';
$lang['had_graded'] = 'Rated';
$lang['apply_close'] = 'Request Close Ticket';
$lang['tickets_no_exist'] = 'Sorry, Ticket Does Not Exist';
$lang['attach_no_exist'] = 'Sorry, Attachment Cannot Be Found';
$lang['tickets_closed_can_not_reply'] = 'Resolved Ticket, Cannot Reply';
$lang['pls_input_reply_content'] = 'Enter Reply';
$lang['submit_as_waiting_resolve'] = 'Submit As Pending';
$lang['submit_as_resolved'] = 'Submit As Resolved';
$lang['confirm_submit_tickets_as_resolved'] = 'Confirm Resolved? Ticket Submitted Will Be Closed And Cannot Reply';
$lang['tickets_closed_can_not_reply'] = 'Resolved Ticket, Cannot Reply';
$lang['kindly_remind'] = 'Hint';

$lang['tickets_type'] = 'Types Of Issue';
$lang['add_and_quit'] = 'Join Membership';
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
$lang['walhao_store'] = 'Walhao shopping Mall';
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

/**申请**/
$lang['pls_t_type'] = 'Select Issue Type';
$lang['pls_t_title'] = 'Enter Ticket Subject';
$lang['pls_t_content'] = 'Enter Ticket Content';
$lang['pls_t_title_or_id'] = 'Enter Subject/Ticket #';
$lang['tickets_save_fail'] = 'Request Failed';
$lang['tickets_save_success'] = 'Request Successful';
$lang['tickets_reply_fail'] = 'Reply Failed';
$lang['tickets_reply_success']='Reply Successful';
$lang['tickets_kindly_notice'] = 'Dear members，please take notice of';
$lang['add_tickets_tip1']='1）The same question, please only create one working order. You can check the progress in "My Work Order", in order to communicate with Customer Service. Every question will be handled by the same CS personnel. Please pay attention to the staff ID';
$lang['add_tickets_tip2']='2）For different question, please create a serperated working order. And please be patient to wait the system to assign a staff ID to answer the question.';
$lang['add_tickets_tip3']='3）Please do not create more than one working order for the same question. That will wast your time. Thank you!';
$lang['add_tickets_tip4']='4）Customer Service working hour: Monday - Friday 9:30-12:30 and 14:00-18:30. Our Customer Service will process your order as soon as possible. It normally takes 2-3 business days. Appreciate your understanding.';
$lang['view_tickets_title_']='Follow Up Log';
$lang['my_tickets_tip_']='1）Your Issue Will Be Resolved Within 24 Hours. Or You Can Contact Support By Calling 0755-33198568 And Provide Agent’s ID Number.';
$lang['my_tickets_tip2_']='2）To Ensure Your Issue Be Resolved In A Timely Manner, Please Contact The Agent Who Has Been Assigned To Follow Up When Calling The Support Line';
$lang['jixu_submit']='Submit New Issue';
$lang['jiexie_previous']='Continue With Last Issue';
$lang['tips_tickets_message']='Is This A Continuation of Previous Similar Issue, Ticket #%s Or This Will Be A Different Issue ?';
$lang['tickets_email_title'] = 'Your ticket #%s is going to be closed';
$lang['tickets_email_content'] = 'Your ticket #%s is going to be closed in %d days. In order to be sure that your question is solved, please enter the help center let Customer Service know. Thank you for contacting us! ';
$lang['tickets_apply_close_tips'] = 'Agent Has Requested To Close The Ticket. If Agree, Please Click “Resolved” And Rate. If Disagree, Please Continue Within The Space And Click “Submit As Pending”. Otherwise, System Will Automatically Close This Ticket After 12 Days';

/**评分**/
$lang['t_pls_t_score'] = 'Rating';
$lang['t_score'] = 'Score';
$lang['t_very_dissatisfied'] = 'Very Dissatisfied';
$lang['t_dissatisfied'] = 'Dissatisfied';
$lang['t_general'] = 'Average';
$lang['t_satisfaction'] = 'Satisfied';
$lang['t_very_satisfactory'] = 'Very Satisfied';

$lang['button_text'] = 'Select Attachment';
$lang['is_exists'] = 'File Already Exist';
$lang['remain_upload_limit'] = 'Exceed Limit On Files For Upload';
 $lang['queue_size_limit'] = 'Exceed Limit On Data For Upload';
$lang['exceeds_size_limit'] = 'File Exceed Size Limitation';
$lang['is_empty'] = 'File Cannot Be Blank';
$lang['not_accepted_type'] = 'File Format Error';
$lang['upload_limit_reached'] = 'Reached Upload Limit';
$lang['attach_delete_success'] = 'Delete Successful';
$lang['attach_no_permissions'] = 'Sorry, Unauthorized Access';
$lang['attach_cannot_find'] = 'File Not Found';
$lang['not_support_mobile_upload'] = 'Mobile phone not able to upload attachments';
/**售后中心 end **/

//供应商推荐奖
$lang['supplier_recommend_commission'] = 'The bonuses for Recommended supplier';
$lang['total_sale_goods_number']='Total sales of :sale_number: items';

//删除免费店铺
$lang['shop_management']='Account Management';
$lang['del_shop']='Account Cancellation';
$lang['is_show_delete']='Cancellation can\'t be revoked, please confirm the cancellation?';
$lang['is_delete_show']='Confirm cancellation';
$lang['del_shop_success']='Account is cancelled, you will go back to the TPS home page 3 seconds later';

$lang['url_not_id_exit'] = 'Your store prefix does not comply  with the rule.';
$lang['url_show'] = 'Rules for modifying the prefix: it can only consist of numbers and letters, or only letters, or the member ID';
$lang['card_upload_error'] = '上传失败，请稍后再试！';
//支付宝绑定解绑
$lang['please_input_code'] = "Please enter verification code";
$lang['please_input_cash_passwd'] = "Please enter PIN";

//手机号绑定解绑
$lang['please_input_mobile'] = "Please enter mobile number";
$lang['mobile_format_error'] = "Mobile number format error";
//$lang['please_input_code'] = "Please enter verification code";
$lang['hacker'] = "Unauthorized user";
$lang['binding_mobile_failed'] = "Mobile verification failed";
$lang['binding_mobile_success'] = "Binding successful";
$lang['mobile_code_error'] = "Verification code error";
$lang['mobile_code_expire'] = "Verification code expired";
$lang['please_verify_old_phone'] = "Please verify original mobile number";
$lang['phone_has_been_userd'] = "Mobile number is already in use";
$lang['send_code_frequency'] = "Recurrent operation, please try again later";

$lang['code_has_sent_to'] = "Verification code has been sent to";
$lang['please_check_out'] = "Please check";
$lang['not_receive_code'] = "Did not receive the verification code?";
$lang['not_receive_reason'] = "Possible reasons：<br/>1、Please check if correct mobile number was entered;<br/>2、Please check if SMS is set to intercept;<br/>3、SMS may be delayed, please wait 3 - 5 minutes;";
$lang['mobile_can_not_same'] = "New mobile number cannot be the same as the original mobile number";
$lang['get_phone_code'] = "Obtain SMS verification code";
$lang['bind_success'] = "success";


//银行卡提现页面
$lang['debit_card'] = "银行卡提现";
$lang['bank_name'] = "开户行名称";
$lang['bank_branch_name'] = "开户行支行名称";
$lang['bank_number'] = "银行账号";
$lang['confirm_bank_number'] = "确认银行账号";
$lang['bank_user_name'] = "开户人名称";
$lang['please_input_bank_name'] = "请填写开户行名称";
$lang['please_input_bank_branch_name'] = "请填写开户行支行名称";
$lang['please_input_bank_number'] = "请填写银行账号";
$lang['please_input_password'] = "请填写资金密码";
$lang['please_bind_mobile'] = "请先绑定手机号";
$lang['please_verify_mobile'] = "请先验证手机号";
$lang['bank_number_not_same'] = "两次银行账号输入不一致";
$lang['unbind_bank_card'] = "解绑银行卡";
$lang['bind_bank_card'] = "绑定银行卡";
$lang['bank_card_infomation_lose'] = "银行卡绑定信息不完整";
$lang['beyond_amount_fee'] = "超过提现最大金额";

$lang['bind_bank_needname'] = "注意：绑定的银行帐号必须是用 ‘:name:’名义开的银行账户！";
$lang['not_beyond_50'] = "不能超过50个字符！";
$lang['bank_name_china_only'] = "开户行名称只能输入汉字";
$lang['bank_branch_name_china_only'] = "开户行支行名称只能输入汉字";
$lang['bank_number_only_number'] = "银行账号只能是数字";

$lang['bank_take_cash_fee'] = "银行卡提现手续费，单笔最大不超过：$5";
$lang['not_bind_bank_card'] = "未绑定银行卡";

//收货地址管理 m by brady.wang
$lang['my_addresses'] = "My shipping address";
$lang['address_not_exists'] = "Address does not exist";
$lang['156_address'] = "Shipping address in Mainland China";
$lang['344_address'] = "Shipping address in Hong Kong, China";
$lang['840_address'] = "Shipping address in USA";
$lang['410_address'] = "Shipping address in Korea";
$lang['000_address'] = "Shipping address in other region";
$lang['user_region'] = "Region";
$lang['user_address_detail'] = "Detailed address";
$lang['address_mobile'] = "Mobile number";
$lang['address_action'] = "Operate";
$lang['spread'] = "Expand";
$lang['pack_up'] = "Hide";
$lang['address_edit'] = "Modify";
$lang['set_success'] = "Setting successful";
$lang['set_default_address'] = "Set as default";
$lang['address_limit'] = "(:number: addresses have been created for this region,(maximum of 5 per region))";
$lang['china_land'] = "Mainland China";
$lang['china_hk'] = "Hong Kong China";
$lang['other_region'] = "Other region";
$lang['address_delete'] = "Delete address";
$lang['you_will_del_address'] = "This address will be deleted！";
$lang['access_deny'] = "Unauthorized";
$lang['modify_address_failed'] = "Modification failed";

$lang['address_phone_check'] = "6-20 digits";
$lang['address_phone_check_1'] = "Mobile number can only be 6-20 digits";
$lang['address_reserve_check'] = "6-20 characters";
$lang['address_reserve_check_1'] = "Alternate number can only be 6-20 digits";
$lang['address_zip_code_check'] = "Less than 20 characters";
$lang['address_zip_code_check_1'] = "Postal code must be less than 20 characters";
$lang['mobile_system_update'] = "SMS system maintenance";
$lang['phone_not_null'] = "Phone can not be empty";
//订单地址修改
$lang['order_status_not_allow'] = "Order status does not allow modification of address";

//新版地址验证
$lang['check_addr_rule_phone'] = "Please enter correct mobile number";
$lang['check_addr_rule_reserve_num'] = "Please enter correct alternate number";
$lang['check_addr_rule_zip_code'] = "Please enter correct postal code";

//重置邮箱
$lang['please_bind_email_first'] = "Link email address";
$lang['update_take_cash_pwd_error'] = "Modification failed, please try again";
$lang['email_code_not_nul'] = "Verification code cannot be blank";
$lang['email_rule_error'] = 'Incorrect email address';
$lang['please_get_code'] = 'Please acquire verification code';
$lang['new_passwd_not_null'] = 'New password cannot be blank';
$lang['new_passwd_rule'] = "The new password must be a 6-digit number";
$lang['enter_re_passwd'] = "Reenter password";
$lang['passwd_not_same'] = "Passwords not match";
$lang['enter_tps_passwd'] = " Please enter login password";
$lang['phone_code_rule_error'] = "Incorrect verification code";
$lang['phone_reset_passwd_success'] = "Reset password successful";
$lang['verify_code_tip3'] = "Verification code will be sent to ";
$lang['tps_login_pwd_reset'] = "TPS login password";
$lang['funds_pwd_new'] = 'New PIN';
$lang['new_takecash_passwd_again'] = "Reenter new PIN";
$lang['new_takecash_passwd'] = "New PIN";
$lang['verify_code'] ="Verification code";
$lang['tps_password_wrong'] ="Incorrect TPS login password";

#新用户专属奖金
$lang['new_member_bonus'] = "Exclusive bonus for new members";
$lang['supplier_recommendation'] = "Vendor Referral";
$lang['month_expense'] = "Platform management fee";

//修改手机号
$lang['change_mobile'] = "Modify mobile number";
$lang['new_mobile_not_null'] = "Mobile number cannot be blank";
$lang['re_enter_new_mobile'] = "Please enter new mobile number";
$lang['not_match_your_input'] = "Mobile number entered does not match";
$lang['choose_edit_type'] = "Select modification method";
$lang['verify_identify'] = "Verification method";
$lang['verify_new_mobile'] = "Verify new mobile number";
$lang['verify_by_old_phone'] = "Modify by the original mobile number";
$lang['verify_by_email'] = "Modify by emai";
$lang['new_phone_rule_error'] = "Incorrect mobile number format";
$lang['new_phone'] = "New mobile number";
$lang['next_step'] = "Next";
$lang['new_phone_edit_successed'] = "New mobile number modification completed";
$lang['resend_code_again'] = "Resend";

