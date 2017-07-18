<?php
/*paypal提现语言包*/
$lang['paypal'] = 'PayPal';
$lang['paypal_withdraw'] = 'paypal提現';
$lang['confirm_paypal_info'] = '確認paypal信息，paypal帳戶：{0}';
$lang['account'] = '帳戶';
$lang['account_name'] = '帳戶名稱';
//转账提现增加短信验证功能
$lang['mobile_verify_not'] = "手機號未認證";
$lang['not_bind_mobile'] = "您未綁定手機號碼，請至<a href = '/ucenter/account_info/index'>賬戶信息</a>欄目進行驗證";
$lang['not_verify_mobile'] = "您的手機號未認證,請至賬戶信息欄目進行驗證";
$lang['mobile_not_confirm'] = "手機號未認證";

/*paypal提现*/
$lang['paypal_prompt1'] = '手續費為提現金額的2%，最高不超過$50';
$lang['paypal_email'] = 'paypal郵箱';
$lang['paypal_email_q'] = '確認paypal郵箱';
$lang['paypal_binding'] = '綁定paypal';
$lang['paypal_unbundling'] = '解綁paypal';
$lang['prompt_titlesa']='驗證碼已直接發送到您所填寫的郵箱中。';
$lang['prompt_2sa']='1、請登錄該郵箱查看。 ';
$lang['paypal_tishi']='未綁定paypal';
$lang['where_code']='驗證碼在哪裡？';
$lang['withdrawal_paypal_tip']='單筆提現金額最大不超過：$60000';
$lang['withdrawal_bank_tip']='(單筆提現金額最大不超過：$12000)';
$lang['withdrawal_paypal_tip2']='導出文件的提現總金額不能超過$60000';
$lang['withdrawal_paypal_tip3']='導出文件的筆數不能超过250筆';
/** 4月份休眠用户活动*/
$lang['april_title'] = '温馨提示';
$lang['april_email_title'] = '有關TPS賬戶從休眠到正常狀態的消息';
$lang['april_email_content'] = '公司在4月份針對付費的老客戶有一項優惠活動。如您之前有欠月費,現在可以通過在本月內做到累積50美金訂單銷售額來使得賬戶從欠月費恢復正常。
你也可以選擇不參加該計劃,用月費池或現金池的錢按所欠月費的50%來支付過去的月費(從4月份開始,老會員月費減半: 銀級:10美;金級:20美金;鑽石:30美金)';
$lang['april_content_1'] = '系统检测到您已经欠月费2个月或以上，公司对此有优惠计划：您可以通过在当月做到累积50美金订单销售额来抵扣之前所欠的所有月费，使得账户恢复正常。';
$lang['april_content_2'] = 'A）參加此優惠計劃';
$lang['april_content_3'] = '不參加該計劃,並同意設置自動從現金池轉差額到月費池以支付月費。 (銀級:10美;金級:20美金;鑽石:30美金)';
$lang['april_content_4'] = 'B）不參加該計劃';
$lang['april_content_5'] = '注意：1、此计划中完成的50美金订单不可取消；2、账户恢复之前因为休眠没有拿到的奖金不补发。但是本月任何时候只要在你个人店铺完成50美金订单，你账户将立即恢复佣金发放功能。';
$lang['queue_order_content'] = 'TPS系統已經收到訂單<span class="msg">%s</span>的支付通知,由於加盟會員過多,您的訂單正在排隊發放獎勵,請耐心等待。';

/** 發送註冊驗證碼 */
$lang['email_captcha_title'] = 'TPS驗證碼';
$lang['email_captcha_content'] = '您的TPS驗證碼： %s，有效時間30分鐘，請盡快驗證！ ';
$lang['phone_captcha_content'] = '【TPS】您的TPS驗證碼： %s，有效時間30分鐘，請盡快驗證！ ';
$lang['reg_success_account'] = '請點擊上方登入鏈接,登錄賬戶,完善資料!';

$lang['ucenter_loc_sure'] = '當前區域和訂單的配送區域不一致,此操作將會切換至訂單的配送區域,是否切換？';


$lang['mobile_code_will_send'] = "（The verification code will be sent to the phone number：<span style color:'red'>:mobile:</span>)";
$lang['mobile_code_has_send'] = "Verification code has been sent to your account binding phone number: mobile: , please check!";
$lang['mobile_not_verified'] = "Your account has not verified your phone number! You need to verify your phone number before you can change your address!";
$lang['mobile_not_bind'] = "Your account is not bundled with mobile phone number! You need to first bind the phone number to modify the address! <a style='color:blue;font-style:bold;font-size:16px;' href='/ucenter/account_info'>To bind</a>";


/** 银联预付卡 */
$lang['pre_card_title'] = '橋達“環球通”銀聯預付卡';
$lang['pre_card_tip'] = '本卡目前僅限韓國、香港、澳門會員申請。';
$lang['pc_name'] = '姓名';
$lang['chinese_name'] = '中文名';
$lang['pc_mobile'] = '手機號碼';
$lang['pc_card_no_tip'] = '未领取卡片不用填写 ';
$lang['pc_card_no_tip2'] = '已领取环球通银联卡的会员请正确填写卡号，以便绑定激活。';
$lang['pc_card_no'] = '卡號';
$lang['pc_nationality'] = '國家';
$lang['pc_issuing_country'] = '護照發放國';
$lang['pc_address_prove'] = '地址證明';
$lang['pc_ID_card'] = '證件號碼';
$lang['pc_ID_card_type_0'] = '身份證';
$lang['pc_ID_card_type_1'] = '護照';
$lang['pc_ID_no'] = '輸入有效證件號碼';
$lang['pc_ID_card_upload'] = '身份證明上傳';
$lang['pc_ID_card_upload_tip'] = '身份證需要正反二面,護照上傳正面';
$lang['pc_ID_front'] = '正面';
$lang['pc_ID_reverse'] = '反面';
$lang['pc_ID_card_ship'] = '卡片寄送地址';
$lang['pc_country'] = '居住國家';
$lang['pc_ship_to_address'] = '請輸入詳細地址';
$lang['pc_submit'] = '申請開通';
$lang['pc_email'] = 'EMail地址';
$lang['pc_payment_tip'] = '開卡制卡費$5。開卡激活，大概需要1到2周时间。資料審核不通過，將會退回開卡費。';
$lang['pc_agreement'] = '我已閱讀<span class="yued c-hong">《開卡協議》</span>。';
$lang['pc_status_0'] = '未支付';
$lang['pc_status_1'] = '待審核';
$lang['pc_status_2'] = '駁回';
$lang['pc_status_3'] = '開卡中';
$lang['pc_status_4'] = '已寄出';
$lang['pc_status_5'] = '已審核';
$lang['pc_status_pending'] = '開卡中';
$lang['pc_apply_tip'] = '“環球通”銀聯預付卡申領<a href="/ucenter/prepaid_card">點這裡</a>';
$lang['pc_applied'] = '已申請“環球通”銀聯預付卡';
$lang['pc_applied_success'] = '資料提交成功,等待審核...';
$lang['check_prepaid_card'] = '審核預付卡';
$lang['pc_address_prove_tip'] = '護照或戶口本';
$lang['prepaid_card_no_exist'] = '卡號不存在';
$lang['assign_card_no'] = '分配卡號';
$lang['assign_card_no_error'] = '此卡號狀態異常';
$lang['pc_without'] = '銀聯卡已分配完';
$lang['pc_agree_t'] = '為保障您的權利，請先閱讀以下內容。';

$lang['admin_withdrawal_success_content'] = '您在TPS申請的提現請求,已成功處理。請查詢相應提現賬號。 ';
$lang['admin_withdrawal_success_title'] = 'TPS提現成功處理通知';

//提现
$lang['withdorw_list_not_null'] = "您尚有補單記錄未完成，所以不允許提現！";

/*welcome*/
$lang['last_login_info'] = '您上次於 :time 在 :contry :province :city 登錄';
$lang['mall_expenditure'] = '商城消費';
$lang['user_is_store'] = '用戶已經是店主';
$lang['mothlyFeeCoupon'] = '月費券';
$lang['clickToUse'] = '點擊使用';
$lang['return_back'] = '佣金抽回返补';
$lang['order_profit_negative'] = '訂單利潤不足$0.01';
$lang['maxie_mobile'] = 'Maxie Mobile';
$lang['split_order_tip'] = '您訂單中的商品在不同庫房或屬不同商家，故拆分為以下訂單分開配送，給您帶來的不便敬請諒解。';
$lang['order_0_'] = '該訂單產品是促銷品，沒有利潤提成，但訂單金額仍會累計到您店舖的銷售業績。';
$lang['upgrade_switch_tip'] = '因係統維護，現暫停店鋪升級功能！其他功能不受影響，給您造成不便，敬請諒解！謝謝！';

/*超过3个月未付款的通知邮件*/
$lang['over3MonthNotyfyTitle'] = '月費補繳優惠';
$lang['over3MonthNotyfyContent'] = '您好。你收到這封郵件是因為您的賬號已經有3個月沒有繳月管理費了。為能讓您的賬號恢復到正常狀態以便您能繼續收到您的2X5見點獎， 我們已給您賬號一個特殊優惠， 即您只需要補繳一個月的月費（而不用補繳3個月的月費）即可以馬上恢復您的賬號至正常狀態。非常謝謝您的耐心。有問題歡迎致電給客服。謝謝。';

/** 提交订单是 地址信息提示 */
$lang['order_address_error_tip'] = '因地址有誤或電話不正確造成退貨退款或拒收退貨的情況，在退款時會扣除來回運費。請仔細檢查收貨地址！';
$lang['edit_address'] = '修改地址';
$lang['customs_clearing_number'] = '海關號';

$lang['read'] = '已讀';
$lang['company_account'] = '公司賬戶';
$lang['ok'] = '確認';
$lang['cancel'] = '取消';
$lang['_no'] = '取消';
$lang['add'] = '添加';
$lang['demote_level'] = '佣金抽回';
$lang['uniqueCard'] = '身份證號碼已經存在';
$lang['transfer_point'] = '佣金轉分紅點';
$lang['transfer_cash'] = '分紅點轉佣金';
$lang['funds_pwd_reset'] = '重置資金密碼';
$lang['yspay'] = '銀聯（銀盛支付）';
$lang['funds_pwd_tip'] = '資金密碼必須是8-16位的數字與大小寫字母的組合';
$lang['forgot_funds_pwd'] = '忘記了資金密碼?';
$lang['payee_info_incomplete'] = '收款人的銀行卡信息不完整';
$lang['payee_info'] = '收款人信息';
$lang['bank_name'] = '開戶行名稱';
$lang['bank_card_number'] = '卡號';
$lang['c_bank_card_number'] = '確認卡號';
$lang['card_number_match'] = '卡號不一致';
$lang['card_holder_name'] = '開戶人名稱';
$lang['remark'] = '備註';
$lang['remark_content'] = '備註';
$lang['bank_'] = '銀行名稱';
$lang['bank_name_branches'] = '支行名稱';
$lang['subbranch'] = '支行名稱';
$lang['confirm_bank_info'] = '確認收款人信息：{0}{1}，卡號：{2}，持卡人：{3}';
$lang['confirm_maxie_info'] = '確認 Maxie Mobile 信息: {0}';
$lang['example1'] = '';
$lang['example2'] = ':如-南頭支行';


$lang['withdrawal'] = '提現';
$lang['cancel_withdrawal'] = '取消提現';
$lang['month_fee_date'] = '月費日';
$lang['day_th'] = '號';
$lang['type_tps'] = '手動';
$lang['withdrawal_tip'] = '保留2位小數';
$lang['coupon'] = '優惠券';
$lang['monthly_fee_coupon_notice'] = '您有一張月費抵用券，您可使用它支付相應等級的一個月的月費。';
$lang['no_active_monthly_fee_coupon'] = '您的月費抵用券已經使用過了。';
$lang['free_mem_have_no_monthly_fee_coupon'] = '您是免費會員，沒有月費抵用券。';
$lang['user_monthli_fee_coupon_success'] = '使用月費抵用券成功,系統已經給您的月費池充值了相應的月費，請查收！';

$lang['freeze_tip_title'] = '店铺月费欠款提醒';
$lang['freeze_tip_content']='<p>尊敬的會員，</p>
<p>請您注意由於%s日—%s 日的店舖管理費未付已超過7天，您當前賬戶已經停止收到獎金。請立即向月費池補足所有拖欠的店舖管理費。謝謝您的關注。</p>
<p>順祝安好！</p>
<p>TPS138管理團隊</p>';

$lang['id_card_num_exist'] = '身份證號已存在';
$lang['complete_info'] = '請確認所有個人信息準確無誤，在點擊下面提交鍵後，個人資料將不能修改，等待TPS審核。';
$lang['reset_email_tip'] = '注意：輸入登陸密碼後，會有一個鏈接發送到您的<strong style="color: #ff0000">%s</strong>郵箱。';
$lang['ewallet_email_tip'] = '注意：申請成功後，會有​​一封關於電子錢包信息的郵件發送到您的 <strong style="color: #ff0000">%s</strong> 郵箱，點擊鏈接激活您的電子錢包賬戶。';

$lang['month_fee_note'] = '支付完成後，如果月費池金額沒有改變，請不要恐慌，幾分鐘後刷新試試。';
$lang['payment_note'] = '支付完成後，如果等級沒有改變，請不要恐慌，幾分鐘後刷新試試。';
$lang['ewallet_success'] = '恭喜，電子錢包申請成功。';
$lang['no_ewallet_name'] = '請輸入電子錢包的用戶名。';
$lang['login_use'] = '電子錢包的登錄用戶名。';
$lang['login_email'] = '接收電子錢包的所有郵件（激活，轉賬，通知等等）。';
$lang['ewallet_name'] = '電子錢包用戶名';
$lang['ewallet_apply'] = '申請電子錢包賬戶';
$lang['ewallet_email'] = '電子錢包郵箱';
$lang['ewallet_before'] = '正在處理這個請求...';
$lang['ewallet_after'] = '操作成功,馬上跳轉到電子錢包...';
$lang['ewallet_tip'] = '請前往 我的賬戶 &rarr; 賬戶信息 申請電子錢包帳號。';

$lang['store_level'] = '店鋪等級';
$lang['alert'] = '提示';
$lang['disclaimer'] = '聲明';
$lang['welcome_notice1'] = '你現在還是免費的月費等級，因此無法得到2×5矩陣見點獎和138矩陣全球每日銷售分紅獎。 <br>並且你團隊下面的免費店主如果在你之前升級成為銀級以上的店主，他們可能永遠不會在你的2×5矩陣中。<a href="/ucenter/member_upgrade">點擊這裡馬上升級 >></a>';
$lang['welcome_notice2'] = '現在你是免費的店鋪，因此無法得到團​​隊銷售提成獎和任何日分紅獎。<a href="/ucenter/member_upgrade">點擊這裡馬上升級 >></a>';
$lang['upgrade_notice'] = '您可以"一步直接升級"，來同時升級月管理費等級和店舖等級。<a class="go_upall_div" href="Javascript: void(0);">點擊這裡去"一步直接升級" >></a>';

$lang['monthly_fee_'] = '步驟 1 ：加入矩陣 / 升級矩陣中的月費等級';
$lang['cur_monthly_fee_level'] = '月費等級 : ';
$lang['product_set'] = '購買產品套裝';
$lang['month_fee_user_rank'] = '月費等級不正確';
$lang['month_user_rank'] = '月費等級不正確';
$lang['month_fee_rank_empty'] = '請先完成步驟1';
$lang['upgrade_once_in_all'] = '一步直接升級： [加入矩陣/ 升級矩陣中的月費等級] + [購買產品套裝]';
$lang['upgrade_all_level_title'] = '(月管理費 & 店鋪) 等級';

$lang['monthly_fee_level'] = '月管理費等級';
$lang['diamond'] = '鑽石級';
$lang['gold'] = '白金級';
$lang['silver'] = '銀級';
$lang['bronze'] = '銅級';
$lang['free'] = '免費級';
$lang['realName'] = '真實姓名';
$lang['user_address'] = '地址';
$lang['mobile'] = '手機號';
$lang['welcome_page'] = '歡迎頁面';
$lang['welcome_msg'] = '歡迎歡迎加入TPS';
$lang['review_account_info'] = '您可以到欄目';
$lang['review_account_info_2'] = '查看您的帳戶信息，并完善相關資料。';
$lang['view_complete_your_info'] = '查看/完善 帳戶信息';
$lang['up_level'] = '升級';
$lang['up_level_notice_2'] = '進行升級。';
$lang['order_pay_time'] = '訂單付款時間';
$lang['customer_'] = '顧客';
$lang['order_amount'] = '訂單金額';
$lang['individual_store_sales_commission'] = '個人店鋪銷售提成';
$lang['order_id'] = '訂單號';
$lang['commission'] = '佣金';
$lang['accumulation_commission'] = '累積提成金額';
$lang['commission_log'] = '提成記錄';
$lang['my_rank'] = '我的職稱';
$lang['profit_sharing_info'] = '分紅信息';
$lang['profit_sharing_time'] = '分紅時間';
$lang['profit_sharing_require'] = '分紅條件';
$lang['profit_sharing_formula'] = '分紅算法';
$lang['profit_sharing_time_content'] = '每週一的0點';
$lang['profit_sharing_time_content_month'] = '每月8號0點';
$lang['profit_sharing_require_content'] = '需要銀級及以上店鋪。';
$lang['profit_sharing_require_content2'] = '自己的店鋪上一週至少要有金額在$35上的已付款訂單。';
$lang['profit_sharing_require_content3'] = '自己的店鋪上一月至少要有10個已付款訂單，且總金額$350以上。';
$lang['profit_sharing_formula_content'] = '自己的分紅點 / 公司的分紅點 * 公司上週利潤的4%';
$lang['profit_sharing_formula_content_month'] = '自己的分紅點 / 公司的分紅點 * 公司上月利潤的4%';
$lang['profit_sharing_countdown'] = '距離下次分紅還有';
$lang['profit_sharing_enable'] = '能否參與下次分紅?';
$lang['yes'] = '能';
$lang['no'] = '不能';
$lang['profit_sharing_point_to_money'] = '分紅點轉現金池';
$lang['profit_sharing_point_to_money_log'] = '分紅點轉現金池記錄';
$lang['no_condition_1'] = '您不是银级及以上店铺。';
$lang['no_condition_2'] = '您的店铺本周订单金额不足$35。';
$lang['no_condition_3'] = '您的店铺本月订单金额不足$350。';
$lang['no_condition_4'] = '您的店铺本月订单不足10个';
$lang['sharing_point'] = '分紅點';
$lang['bonus_point'] = '(我每天所挣得的分紅點)';
$lang['bonus_point_note'] = '注：以當月第一天總分紅點為依據，每月最多只可以轉30%到現金池。';
$lang['first_month_day'] = '(當月第一天)';
$lang['total_point'] = '總共';
$lang['sharing_point_enable_exchange'] = '可轉移分紅點';
$lang['point'] = '點';
$lang['reward_sharing_point'] = '獎勵分紅點';
$lang['commissions_to_sharing_point_auto'] = '佣金自動轉分紅點';
$lang['sale_commissions_sharing_point'] = '銷售佣金自動轉分紅點';
$lang['forced_matrix_sharing_point'] = '見點佣金自動轉分紅點';
$lang['validity'] = '有效期至';
$lang['profit_sharing_sharing_point'] = '分紅自動轉分紅點';
$lang['manually_sharing_point'] = '現金池轉分紅點';
$lang['sharing_point_to_money'] = '分紅點轉現金池';
$lang['proportion'] = '比例';
$lang['cur_commission_lack'] = '當前現金餘額不足。';
$lang['cur_sharing_point_lack'] = '當前分紅點不足。';
$lang['positive_num_error'] = '請輸入大於0的數值，如果是小數，保留小數點後兩位。';
$lang['save'] = '保存';
$lang['save_success'] = '保存成功!';
$lang['shift_success'] = '轉移成功!';
$lang['profit_sharing_point_log'] = '分紅點轉入記錄';
$lang['pls_sel_profit_sharing_adden_type'] = '請選擇分紅轉入類型';
$lang['current_commission'] = '現金池';
$lang['move'] = '轉';
$lang['to'] = '到';
$lang['save_false'] = '保存失敗!';
$lang['level_not_enable'] = '店鋪未激活';
$lang['month_fee_fail_notice'] = '店鋪月費欠款提醒';
$lang['month_fee_fail_content'] = '
請您注意系統未能從月費池扣付%s日- %s日的店舖管理費。請在%s內為月費池充值支付店舖​​管理費，以保證您的賬戶狀態正常，系統能按時發放您應得的各種佣金和獎勵。您可以點擊<a target="_blank" href="%s">這裡</a>繳費。謝謝您的關注。
';
$lang['month_fee_fail_content_90'] = '
請您注意由於%s—%s的店舖管理費未付已超過7天，您當前賬戶已經處於休眠狀態，將暫時無法收到獎金。請盡快向月費池補足所有拖欠的店舖管理費，以使您的賬戶恢復正常。您可以點擊<a target="_blank" href="%s">這裡</a>繳費。謝謝您的關注。';
$lang['24hours'] = '24小時';
$lang['7day'] = '7天';
$lang['bonus_plan_name'] = '分紅名稱';
$lang['param_a'] = '參數壹';
$lang['param_b'] = '參數贰';
$lang['param_c'] = '参数叁';
$lang['param_d'] = '參數肆';




/*我的信息*/
$lang['my_msg'] = '我的消息';

/*我的代品券*/
$lang['exchangeCoupon'] = '代 品 券';
$lang['suitExchangeCouponRule'] = '代品券規則說明';
$lang['suitExchangeCouponRuleContent'] = '代品券规则说明内容';
$lang['only_use_in_exchange'] = '只限換購區選購商品';
$lang['num'] = '數量';
$lang['expiration'] = '過期時間';
$lang['unlimited'] = '無限制';
$lang['goto_use'] = '去使用';
$lang['no_exchange_coupons'] = '您目前沒有代品券。';
$lang['coupons_total_num']='總共有代品券:total_num:張';
$lang['value']='價值';

/*关于代品券*/
$lang['about_exchange_coupon'] = '關於代品券';
$lang['exchange_coupon_1_title'] = '一、什麼是代品券';
$lang['exchange_coupon_1_content'] = '代品券是商家為提高會員滿意度，進行的兌換套餐和套餐單品的兌換券，代品券共有五個面額，分別為$100 / $50 / $20 / $10 / $1。本代品券只限本會員及本商城使用，不可轉讓和提現。在店鋪升級選擇產品時出現，只可在產品套餐特賣區用於兌換套餐和套餐單品。';
$lang['exchange_coupon_2_title'] = '二、代品券的使用規則';
$lang['exchange_coupon_2_content'] = '
1. 本代品券只可用於在產品套餐特賣區兌換會員套餐和套餐單品；<br />
2. 本券只限本會員及本商城使用，不可轉讓和提現；不可用於TPS店舖管理費、運費的繳納；<br />
3. 如遇退貨，此券將退回本會員賬號；<br />
4. 此券最終解釋權歸深圳前海雲集品電子商務有限公司所有。';
$lang['exchange_coupon_3_title'] = '三、代品券發放方式說明';
$lang['exchange_coupon_3_content_1'] = '1. 在TPS138會員後台進行店鋪升級時，如果會員只選擇部分產品套餐及套餐單品，剩下的金額可選擇代品券，以後到產品套餐特賣區兌換自己喜歡的產品套餐及套餐單品。';
$lang['exchange_coupon_3_content_2'] = '2. 店鋪升級時勾選了代品券的會員，可在<span style="c​​olor:#23a1d1;">“我的賬戶—>我的代品券”</span>裡查看。';
$lang['exchange_coupon_4_title'] = '四、代品券的常見問題';
$lang['exchange_coupon_4_content'] = '
(1) 代品券金額是否可以提現？ <br />
答：不可以。 <br />
(2) 使用代品券的訂單退貨時，如何退款？代品券是否可退回？ <br />
答：使用代品券進入產品套餐特賣區換購的訂單，如發生退貨，退款結算按照實際結算金額退款。對於已使用的代品券，可以返還會員賬戶。 <br />
(3) 代品券可否沖抵TPS店舖管理費、運費等?<br />
答：都不可以。 <br />
(4) 使用代品券兌換產品套餐和套餐單品時，其中代品券的金額能開收據嗎？ <br />
答：不能，代品券所用金額已在店鋪升級時的訂單中開具過收據，不可重複。只能開具訂單實際支付金額的收據。';

/*账户信息*/
$lang['member_url'] = 'TPS商城店鋪網址';
$lang['member_name'] = 'TPS商城店鋪名稱';
$lang['modify_member_url'] = '修改網址';
$lang['member_url_prefix_format_error'] = '網址前綴只能是4-15位的數字字母';
$lang['url_can_not_be_other_id'] = '您不能使用其他會員的ID做為您的網址前缀。';
$lang['modify'] = '修改';
$lang['modify_store_url'] = '修改店鋪網址';
$lang['modify_store_url_notice'] = '您還有<span id="storeModifyleftCounts">%s</span>次修改機會。';
$lang['modify_member_url_notice'] = '您還有<span id="memberModifyleftCounts">%s</span>次修改機會。';
$lang['modify_store_url_count_end'] = '您的店鋪網址修改機會已用完。';
$lang['modify_member_url_count_end'] = '會員個人網址修改機會已用完。';
$lang['store_url_prefix_format_error'] = '店鋪網址前綴只能是4-15位的數字字母';
$lang['store_url_exist'] = '店鋪網址已經存在';
$lang['url_exist'] = '該網址已經存在';
$lang['modify_store_url_success'] = '修改店鋪網址成功。';
$lang['id_card_scan_type_ext_error'] = '身份證掃描件格式不對';
$lang['id_card_scan_too_large'] = '身份證掃描件大小不能超過10M';
$lang['id_scan_condition'] = '大小不超過10M，格式為jpg,gif,bmp,jpeg,png';
$lang['pls_complete_auth_info'] = '當您看到這個信息時，表明您的身份驗證還沒有通過TPS的審核。';
$lang['enable'] = '激活';
$lang['have_black_word'] = '存在敏感詞';
$lang['enable_cur_level'] = '激活您的店鋪';
$lang['id_card_scan_ok'] = '身份證掃描件已上傳';
$lang['not_uploaded'] = '未上傳';
$lang['person_id_card_num_exitst'] = '身份證號已提交過了。';
$lang['terms_and_agreement'] = '協議條款';
$lang['terms_1'] = '我同意加盟湯普森合夥人有限公司，不是股權投資和股票投資。我明白我一次性支付的費用是為了購買一套適合自己的國外品牌產品套裝，以及一個“裸商入駐”的跨境電商產品和營銷平臺，以享受公司給店鋪在其購物商城上所提供的名品購物折扣和其商機所帶來的財務收入。';
$lang['terms_2'] = '<p>如果在我個人店鋪商城沒有普通消費者和顧客，TPS將不會給我發放任何獎金。如果我是從一開始就直接購買銀級以上的產品套裝，我的每月店鋪網站管理費或一次性商城加盟費及購買產品套裝費用，將有三天的免費網店試用期。從第四個工作日開始，TPS將不會同意退款要求。</p>';

/*收货地址*/
$lang['shipping_addr'] = '收貨地址';

/*订单中心*/
$lang['order_center'] = '訂單中心';

/*我的订单*/
$lang['my_orders'] = '我的自我消费訂單';
$lang['my_tps_orders'] = '我的店鋪客戶訂單';
$lang['my_one_direct_orders'] = '我的美國商城訂單';
$lang['my_walhao_store_orders'] = '我的沃好訂單';
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
$lang['my_affiliate'] = '我的Affiliate 訂單';
$lang['order_confirm_time'] = '訂單確認時間';
$lang['order_pay_date'] = '訂單支付時間';
$lang['order_update_date'] = '订单更新时间';
$lang['effective_performance'] = '有效業績';

/* 收据 */
$lang['order_receipt_font'] = "pmingliu";
$lang['order_receipt_company'] = "前海雲集品";
$lang['order_receipt_company_address'] = "地址：";
$lang['order_receipt_company_address_detail'] = "香港九龍長沙灣大南西街609号永義廣場21樓全層";
$lang['order_receipt_company_phone'] = "電話：";
$lang['order_receipt_company_phone_detail'] = "(852)2690-0193";
$lang['order_receipt_company_fax'] = "傳真：";
$lang['order_receipt_company_fax_detail'] = "(852)3706-2329";
$lang['order_receipt_company_email'] = "郵箱：";
$lang['order_receipt_company_email_detail'] = "support@tps138.com";
$lang['order_receipt_purchase_date'] = "購買日期：";
$lang['order_receipt_member_id'] = "會員 ID ：";
$lang['order_receipt_store_level'] = "店鋪等級：";
$lang['order_receipt_user_phone'] = "電話：";
$lang['order_receipt_receiving_address'] = "收貨地址：";
$lang['order_receipt_title'] = "收據";
$lang['order_receipt_order_number'] = "訂單號：";
$lang['order_receipt_detail_product'] = "商品描述";
$lang['order_receipt_detail_price'] = "單品價格<span>（美元）</span>";
$lang['order_receipt_detail_qty'] = "數量";
$lang['order_receipt_detail_amount'] = "總計<span>（美元）</span>";
$lang['order_receipt_coupons'] = "TPS代品券";
$lang['order_receipt_product_amount'] = "商品總計：";
$lang['order_receipt_coupons_amount'] = "代品券：";
$lang['order_receipt_freight'] = "運費：";
$lang['order_receipt_actual_payment'] = "實際支付：";
$lang['order_receipt_payment_terms'] = "支付方式：";
$lang['order_receipt_commitment'] = "承諾所有商品三天無理由退貨。";
$lang['order_receipt_payment_billing_unit'] = "出具收據單位：";
$lang['order_receipt_thank'] = "感謝惠顧！歡迎再次光臨";

/*账户安全*/
$lang['account_safe'] = '賬戶安全';

/*主控面板*/
$lang['cumulative_statistics'] = '累積統計';
$lang['direct_push'] = '分店鋪數';
$lang['cumulative_dividends'] = '累積分紅';
$lang['cumulative_forced_matrix_award'] = '累積見點獎';
$lang['cumulative_sales_commission'] = '累積銷售提成';
$lang['announcement'] = '公告';
$lang['recommended_members'] = '推薦的店鋪';
$lang['join_time'] = '加盟時間';
$lang['enable_time'] = '激活時間';
$lang['inactive'] = '未激活';
$lang['store_rating'] = '網店等級';
$lang['cur_title'] = '當前職稱';
$lang['title_level_0'] = '普通店主';
$lang['title_level_1'] = '資深店主(MSO)';
$lang['title_level_2'] = '市場主管(MSB)';
$lang['title_level_3'] = '高級市場主管(SMD)';
$lang['title_level_4'] = '市場總監(EMD)';
$lang['title_level_5'] = '全球市场销售副总裁(GVP)';
$lang['profit_sharing_pool'] = '分紅池';
$lang['sharing_point_month_limit'] = '分紅點轉出超過每月限額。';
$lang['sharing_point_lacking'] = '分紅點不足。';
$lang['month_fee_pool'] = '月費池';
$lang['cash_pool_to_month_fee_pool'] = '現金池轉月費池';
$lang['month_1'] = '1個月';
$lang['month_3'] = '3個月';
$lang['month_6'] = '6個月';
$lang['month_2'] = '2個月';
$lang['month'] = '月數';
$lang['add_fee'] = '充值';
$lang['no_year'] = '請選擇年份';
$lang['no_month'] = '請選擇月份';
$lang['transfer_to_other_members'] = '轉帳給其他會員';
$lang['give'] = '給';
$lang['member'] = '會員';
$lang['member_id'] = '會員ID';
$lang['no_need_tran_to_self'] = '您無需轉帳給自己。';
$lang['MEMBER_TRANSFER_MONEY'] = '會員之間轉帳';
$lang['tran_to_mem_alert'] = '您將轉帳[%s美金]給會員:%s，此次轉賬金額將歸%s所有，意外轉賬風險將由您自己承擔，您確認要轉賬嗎？';
$lang['funds_pwd'] = '資金密碼';
$lang['funds_pwd_error'] = '資金密碼不正確';
$lang['no_funds_pwd_notice'] = '若您尚未設置資金密碼，請至<a href="/ucenter/account_info/index#modifyPIN" class="go_modify_PIN">賬戶信息</a>欄目進行設置。';
$lang['tran_to_mem_china_disabled'] = '轉帳給其他會員的功能目前在中國市場關閉。';
$lang['monthly_fee_coupon_note'] = '您還剩餘%s張月費抵用券。';
$lang['monthly_fee_coupon_note_limit'] = '請注意：每3個月內只能用1次月費抵用券。';

/*佣金报表*/
$lang['commission_report'] = '佣金报表';
$lang['funds_change_report'] = '資金變動報表';
$lang['current_month_comm'] = '當月各項傭金統計:';
$lang['comm_statis_history'] = '歷史月份各項傭金統計:';
//$lang['2x5_force_matrix'] = '2*5 見點佣金';
$lang['2x5_force_matrix'] = '月團隊組織分紅獎';
$lang['138_force_matrix'] = '138 見點佣金';
$lang['group_sale'] = '團隊銷售佣金';
$lang['group_sale_infinity'] = '團隊總裁獎';
$lang['personal_sale'] = '個人店鋪銷售提成獎';
$lang['week_profit_sharing'] = '每天全球利潤分紅';
$lang['daily_bonus_elite'] = '銷售精英日分紅';
$lang['day_profit_sharing'] = '每天全球利潤分紅';
$lang['week_leader_matching'] = '周領導對等獎';
$lang['month_leader_profit_sharing'] = '月傑出店鋪分紅';
$lang['month_middel_leader_profit_sharing'] = '月領導分紅獎';
$lang['month_top_leader_profit_sharing'] = '每月領袖分紅獎';
$lang['total_stat'] = '總額統計';
$lang['up_tps_level'] = '升級費用';
$lang['today_commission'] = '當日佣金';
$lang['current_month_commission'] = '當月佣金';
$lang['real_time'] = '實時';

/*现金池转月费池*/
$lang['cash_to_month_fee_pool_log'] = '現金池轉月費池日誌';

/*提现*/
$lang['take_out_cash'] = '提現';
$lang['take_out_cash_type'] = '提現方式';
$lang['bank_card'] = '銀行卡';
$lang['bank'] = '銀行';
$lang['bank_card_num'] = '銀行卡帳號';
$lang['payee_name'] = '收款人姓名';
$lang['take_out_max_amount'] = '可提現最大金額';
$lang['take_out_amount'] = '提現金額';
$lang['take_out_pwd'] = '資金密碼';
$lang['password_strength'] = '密碼強度';
$lang['weak'] = '弱';
$lang['medium'] = '中';
$lang['strong'] = '強';
$lang['take_out_pwd2'] = '必須是8-16位的數字與大小寫字母的組合';
$lang['re_take_out_pwd'] = '確認資金密碼';
$lang['take_out_cash_notice_1'] = '1）每月我們有15日和30日兩次處理提現。如果是在1日—15日之間提出申請的將會在本月的30日處理提現；如果是在16日—31日提出申請將會在下一個月的15日處理提現。
<br>2）每個店主在申請提現前必須上傳有效身份證並通過審核。這是為了防止欺詐，為了保護每個會員的個人利益。<br>3）
此提示中的日期均為工作日，如遇法定節假日則順延至下一工作日。';
$lang['take_out_cash_notice_2'] = '每月15號之前申請的提現將在本月底發放';
$lang['take_out_cash_notice_3'] = '每月15號之後申請的提現將在下月15號發放';
$lang['no_take_cash_pwd'] = '第一次設置資金密碼，點此設置。';
$lang['had_take_cash_pwd'] = '修改資金密碼';
$lang['take_cash_pwd_exit'] = '您已經設置了資金密碼！';
$lang['take_cash_pwd_not_exit'] = '您還沒有設置資金密碼！';
$lang['set_take_cash_pwd'] = '設置資金密碼';
$lang['set_take_cash_pwd_success'] = '設置資金密碼成功。';
$lang['modify_take_cash_pwd_success'] = '修改資金密碼成功。';
$lang['modify_take_out_pwd'] = '修改資金密碼';
$lang['old_take_out_pwd'] = '原資金密碼';
$lang['take_out_success'] = '提現成功';
$lang['pls_sel_take_out_type'] = '請選擇提現方式';
$lang['pls_input_correct_amount'] = '請填寫正確的資金金額';
$lang['pls_input_correct_amount2'] = '至少提現100美金';
$lang['pls_input_correct_take_out_pwd'] = '資金密碼輸入錯誤';
$lang['pls_pwd_retry']='輸入錯誤次數過多，請一小時後重試或重置資金密碼';
$lang['not_fill_alipay_account'] = '您還未設置支付寶帳戶。';

/** 支付寶提現 **/
$lang['withdrawal_fee_'] = '提現手續費';
$lang['withdrawal_actual_'] = '實際到帳金額';
$lang['withdrawal_alipay_'] = '支付寶賬號';
$lang['withdrawal_alipay_tip'] = '支付寶限制，單筆提現金額最大不超過：$7000';
$lang['withdrawal_alipay_tip2'] = '支付寶提現手續費，單筆最大不超過：$5';
$lang['confirm_alipay_info'] = '確認支付寶信息，支付寶賬號：{0}';

$lang['alipay_actual_name']='支付寶真實姓名';
$lang['alipay_binding']='綁定支付寶';
$lang['alipay_unbundling']='支付寶解綁';
$lang['alipay_binding_accounts']='支付寶帳號';
$lang['alipay_binding_accounts_q']='確認支付寶帳號';
$lang['alipay_binding_vcode']='驗證碼';
$lang['capital_withdrawals_password']='資金密碼';
$lang['alipay_binding_name_prompt']='請輸入以%s實名認證的支付寶帳號';
$lang['alipay_binding_email']='綁定郵箱支付寶帳號';
$lang['repeat_account'] = '支付寶帳號重復!';
$lang['confirm_account'] = '請再次輸入支付寶賬號';
$lang['different_account'] = '兩次輸入的支付寶賬號不相同';
$lang['prompt_title']='驗證碼已直接發送到您所填寫的支付寶賬號中。 ';
$lang['for_example']='例如';
$lang['prompt_1']='1、您的支付寶賬號是手機號碼，請查看短信。 ';
$lang['prompt_2']='2、您的支付寶賬號是郵箱，請登錄該郵箱查看。 ';
$lang['forms_authentication_geshi']='格式不正確！請重新輸入';
$lang['forms_authentication_num']='支付宝账号不一致！請重新輸入';

/*提现记录*/
$lang['cash_take_out_logs'] = '提现记录';
$lang['cash_take_out_account'] = '提現帳戶';

/*店鋪升級*/
$lang['join_fee'] = '產品套裝';
$lang['cur_level'] = '當前店鋪等級';
$lang['pls_sel_level'] = '請選擇店鋪級別';
$lang['no_need_upgrade'] = '無需升級';
$lang['amount_cannot_be_empty'] = '金額不能為空';
$lang['pls_sel_payment'] = '請選擇支付方式';
$lang['info_need_complete_for_pay_member'] = '付費店鋪需要完善的信息';
$lang['pay_success'] = '支付成功。';
$lang['submit_success'] = '提交成功。';
$lang['pls_complete_info'] = '請先在帳戶信息欄目補全您的身份證號和身份證複印件。';
$lang['pls_enable_level'] = '請先在帳戶信息欄目激活您的等級。';
$lang['change_monthly_level'] = '更改月費等級';
$lang['pls_sel_monthly_level'] = '請選擇月費等級';
$lang['cannot_change_monthly_fee_level'] = '不能修改月費等級！';
$lang['no_change'] = '信息没有任何修改！';
$lang['month_fee_level_change_note'] = '月費等級將在下個月費日變更為%s。';
$lang['month_fee_level_change_desc'] = '您可以在這裡選擇更低級別的月費等級，成功提交後，您下個月的月費將按照新的等級來繳納，屆時您的月費等級將更新為修改後的等級，如果修改後的月費等級低於您的店舖等級，店舖等級將會隨之降級。';

//账户信息
$lang['input_name_rule'] = '用戶名長度必須大於3个字符。';
$lang['input_store_name_rule'] = '店鋪名稱長度必須大於3个字符和小於36个个字符';
$lang['input_store_name_exit'] = '店鋪名稱已存在';
$lang['input_store_name_tip'] = '注：店鋪名稱中文最大12個漢字。';
$lang['input_name_rule_100'] = '用戶名長度必須小於100个字符。';
$lang['sensitive'] = '用戶名存在敏感詞!';
$lang['start_date'] = '開始日期';
$lang['end_date'] = '結束日期';
$lang['input_start_date'] = '請選擇開始日期!';
$lang['input_end_date'] = '請選擇結束日期!';
$lang['input_date_error'] = '開始日期大於結束日期!';
$lang['input_username'] = '請輸入名稱!';
$lang['account_success'] = '信息修改成功!';
$lang['account_error'] = '信息修改失敗或信息沒有改變!';
$lang['submit'] = '提交';
$lang['email'] = '郵箱';
$lang['profile'] = '個人信息';
$lang['username'] = '用戶名';
$lang['ori_password'] = '原始密碼';
$lang['new_password'] = '新密碼';
$lang['re_password'] = '確認密碼';
$lang['country'] = '國家';
$lang['month_upgrade_from']='月費等級從';
$lang['shop_upgrade_from']='店鋪等級從';
$lang['upgrade_to']='陞級到';
$lang['downgrade_to']='降級到';
$lang['month_upgrade_log_label']='月費等級變動記錄';
$lang['shop_upgrade_log_label']='店鋪等級變動記錄';

$lang['modify_mobile_number'] = '修改手機號碼';
$lang['pls_input_new_number'] = '請輸入新的手機號碼';
$lang['modify_success'] = '修改成功';

$lang['check_card_wait'] = '<div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">身份證審核大約需要2分鐘,</div><div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">請您耐心等待!</div>';

$lang['check_exceed_three'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">您的身份證照片經系統審核3次未通過,</div><div style="margin-top:20px; font-size:20px; text-align:center;font-family:微软雅黑;">將轉為人工審核! 請耐心等候!</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">确定</button></div>';

$lang['check_taiwan_card'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">您的身份證照片將轉為人工審核!</div><div style="margin-top:20px; font-size:20px; text-align:center;font-family:微软雅黑;">請耐心等候!</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">确定</button></div>';

$lang['check_passed'] ='<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/correct.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">您的身份證已通過審核!</div><div style="margin-top:20px; text-align:center;"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">確定</button></div>';

$lang['check_failed'] = '<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/error.png"/></div><div style="text-align:center; color:#000;font-size:20px;font-family:微软雅黑;line-height:25px;">您的身份證未通過審核</div><div style="color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;margin-top:25px;box-sizing:border-box;padding-left: 20px;">可能的原因有:</div><ul style="margin:0;list-style:none;color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;padding-left: 20px;" ><li style="line-height: 25px;">( 1 )證件與填寫的信息不壹致</li><li style="line-height: 25px;">( 2 )身份證照片不清晰</li><li style="line-height: 25px;">( 3 )未滿18歲</li></ul><div style="list-style:none;color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;padding-left: 20px;">請您查看原因，並按照規則重新上傳照片進行審核!</div><div style="margin-top:20px; text-align:center"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">確定</button></div>';

$lang['check_maintenance'] = '<div style="text-align: center; padding:10px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:16px;font-family:微软雅黑;padding:0 80px;line-height:25px; box-sizing:border-box;">抱歉,系統審核功能維護中,</div><div style="margin-top:20px; font-size:16px; text-align:center; font-family:微软雅黑;">請在2個小時之後再嘗試.<div style="margin-top:15px; text-align:center;"><button  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;" onclick="confirm_card()"  type="button">確定</button></div>';
$lang['check_passed_info'] = '您的身份證已經在 <span style="color:red;" >%s</span> 通過了審核.';
$lang['upload_failed'] = '抱歉,上傳失敗.';

//团队销售提成奖励
$lang['current_algebra_title'] = '累積團隊銷售提成代數';
$lang['current_rank'] = '(當前店鋪等級)';
$lang['QSOs'] = '個(合格店鋪)';
$lang['QRCs'] = '個(合格訂單)';
$lang['current_algebra'] = '(目前享受的團隊提成級別)';
$lang['learn_more_rule'] = '了解更多此獎勵規則';
$lang['freeze'] = '休眠';
$lang['enjoy_gold'] = '白金團隊銷售利潤';
$lang['enjoy_diamond'] = '鑽石團隊銷售利潤';


//无限代奖励
$lang['infinity_con1'] = '上個月必須是鑽石店鋪';
$lang['infinity_con2'] = '累積至少3000個合格銅級（或以上）店鋪(至少2組團隊:每組團隊最多計數1500)';
$lang['infinity_con3'] = '個人店鋪累積30個合格客戶';
$lang['infinity_countdown'] = '距離下次獎勵還有';
$lang['infinity_enable'] = '能否參加下次獎勵？';
$lang['infinity'] = '每月團隊銷售利潤總裁獎';
$lang['infinity_title'] = '總裁獎';
$lang['infinity_info'] = '團隊銷售總裁獎信息';
$lang['infinity_log'] = '總裁獎勵日誌';
$lang['infinity_date_title'] = '總裁獎的時間';
$lang['infinity_date_content'] = '次月十號';
$lang['infinity_qualifications_title'] = '總裁獎的合格條件';
$lang['infinity_formula_title'] = '總裁獎的獎勵算法';
$lang['infinity_formula_content'] = '合格者獲得本身團隊從第11代開始的銷售利潤總額×0.5%';
$lang['qualified_time'] = '合格月份';
$lang['grant_time'] = '統計合格時間';
$lang['is_grant'] = '是否已發放';

//上传头像
$lang['user_avatar'] = '用戶頭像';
$lang['new_user_avatar'] = '新用戶頭像';
$lang['upload'] = '上傳';
$lang['upload_avatar'] = '上傳頭像';
$lang['reselect'] = '重新選擇圖片';
$lang['cropped_tip'] = '提示:請選擇裁剪區域.';
$lang['upload_tip'] = '你可以上傳JPG、GIF或PNG格式的文件，文件大小不能超過<strong>1.0MB</strong>.<br>規定寬和高不能超過<strong>1024*800</strong>.';

//用户升级 购买会员
$lang['current_level'] = '當前店鋪等級';
$lang['member_level'] = '店鋪等級';
$lang['opening_time'] = '開通時長';
$lang['payment_method'] = '付款方式';
$lang['amount'] = '應付金額';
$lang['confirm_purchase'] = '確認購買';
$lang['buy_now'] = '馬上購買';
$lang['buy_member'] = '購買店鋪';
$lang['go_pay'] = '去支付';
$lang['payment_tip'] = '付款提示';
$lang['upgrade_level'] = '店鋪升級';
$lang['annual_fee'] = '年管理費';
$lang['monthly_fee'] = '月管理費';
$lang['alipay'] = '付款頁面';
$lang['payment_content1'] = "支付完成前，請不要關閉此支付驗證窗口。";
$lang['payment_content2']= "支付完成後，請根據您支付的情況點擊下面按鈕。";
$lang['payment_success']= "支付完成";
$lang['payment_error']= "支付遇到問題";

//奖励制度介绍
$lang['reward_tip'] = '<strong>QSO (合格店鋪):</strong><ul><li>銅級（或以上）店鋪：<ol><li>店鋪已激活</li><li>按時付店鋪每月管理費</li></ol></li><li>免費店鋪：<ol><li>自己的店鋪累積50美元或以上的銷售額（不含運費）。</li></ol></li></ul>';
// $lang['reward_tip2'] = '<strong>QRC (合格客戶):</strong><ul><li>下了金額25美元以上的訂單</li><li>非店主自己</li></ul>';
$lang['directly'] = '我的分店';
$lang['store_url'] = '沃好商城店鋪網址';
$lang['rewards_introduced'] = '獎勵制度';
$lang['r1'] = '个人产品销售奖';
$lang['r2'] = '团队销售业绩提成奖';
$lang['r3'] = '团队组织见点奖';
$lang['r4'] = '每周全球利润分红奖';
$lang['r8'] = '每月全球利润分红奖';
$lang['r5'] = '每周领导对等奖';
$lang['r6'] = '每月领导分红奖';
$lang['r7'] = '每月团队销售业绩无限代奖';

/* 个人店铺销售提成奖   */
$lang['r1_content'] = '條件：<br/>個人店舖是任何級別的店鋪。';
$lang['r1_content_notice'] = '獎勵：<br/>店主將獲得其個人店鋪銷售利潤的20%。';

/*  团队销售业绩提成奖   */
$lang['r2_content1'] = '<ul><li>[免費店鋪]</li></ul>';
$lang['r2_content1_1'] = '條件：<br/>個人店舖是免​​費店鋪；<br/>
獎勵：<br/>第壹級店鋪銷售利潤提成5%。';
$lang['r2_content_1'] = '<ul><li>[銀級店鋪]</li></ul>';
$lang['r2_content_1_1'] = '條件：<br/>個人店舖是銀級店鋪；<br/>
獎勵：<br/>第壹級店鋪銷售利潤提成12%，第二級店鋪銷售利潤提成7%。<br/>';
$lang['r2_content_2'] = '<ul><li>[白金店鋪]</li></ul>';
$lang['r2_content_3'] = '<ul><li>[鑽石店鋪]</li></ul>';
$lang['r2_content_5'] = '<ul><li>[銅級店鋪]</li></ul>';
$lang['r2_content_5_1'] = '條件：<br/>個人店舖是銅級店鋪；<br/>
獎勵：<br/>第壹級店鋪銷售利潤提成10%，第二級店鋪銷售利潤提成5%。<br/><br/> ';
$lang['r2_content_2_1'] = '條件：<br/>個人店舖是白金店鋪；<br/>
獎勵：<br/>第壹級店鋪銷售利潤提成15%，第二級店鋪銷售利潤提成10%。<br/><br/>';
$lang['r2_content_3_1'] = '條件：<br/>個人店舖是鑽石店鋪；<br/>
獎勵：<br/>第壹級店鋪銷售利潤提成20%，第二級店鋪銷售利潤提成12%。<br/><br/><br/>';

/* 每月团队组织分红奖   */
$lang['r3_content_1'] = '';
$lang['r3_content_2'] = '
條件：<br/>
(1)鉆石級合格店鋪；<br/>
(2)店主職稱是市場主管或以上；<br/>
(3)上個月個人店鋪累計了100美金銷售額。
<br/>
獎勵：獎金將根據會員的現有職稱、店鋪等級、其個人店鋪上個月的零售訂單銷售額情況來分配獎金。公司每月從全球總銷售利潤裏拿出10%來發放獎金。<br/>
<br/>
發獎日期：每月15號。';

/* 每天全球利润分红奖 */
$lang['r4_content_1'] = '每天全球利潤分紅獎是為那些每月能給店鋪帶來銷售業績的店主而設計的。';
$lang['r4_content_2'] = '條件：<br/>
上個月個人付費店鋪累積了25美元(免費店主需要100美元)的銷售額，下個月獲得每天全球銷售利潤分紅獎。<br/><br/>
獎金：<br/> 根據會員的等級，以及該會員消費的普通零售訂單金額來計算，會員等級越高，和消費的普通零售訂單金額越多，該項獎金將更多。<br/>公司每天從全球總銷售利潤裏拿出10%來發放獎金。
';

$lang['r9_content_1'] = '<ul><li>加入公司矩陣條件：<br/>付月費店主 或 名字身份通過驗證的免費店主。</li></ul>';
$lang['r9_content_2'] = '<ul><li>公司矩陣規則：<br/>將加入到矩陣的店鋪從左到右排滿138個位置，然後從139個店鋪開始，從左到右排列到下壹排，以此類推。</li></ul>';
$lang['r9_content_3'] = '<ul><li>條件：<br/>上個月個人店鋪累計銷售50美金或以上，店鋪等級銅級或以上，則下個月壹號開始獲得138獎金。</li></ul>';
$lang['r9_content_4'] = '<ul><li>獎勵：<br/>公司拿出矩陣中所有成員當天銷售利潤的5%來分給矩陣中滿足條件的店主，分配規則依據店主下面有多少個店鋪來核算。</li></ul>';
$lang['r9_content_5'] = '<ul><li>獎勵算法：<br/>(店主138矩陣下面人數 / 所有參加該分紅店主矩陣下面人數總和 * 公司所有成員當天訂單銷售利潤 * 5%) ＋ (公司所有成員當天產品套裝銷售利潤 * 5%／滿足該分紅獎所有合格人數)。</li></ul>';

/* 每周领导对等奖 */
$lang['r5_content_1'] = '每周領導對等獎是為了獎勵那些幫助其新店主快速發展生意的市場領導人而設計的。';
$lang['r5_content_2'] = '條件：<br/>
1）鉆石級合格店鋪；<br/>
2）店主職稱是市場主管或以上；<br/>
3）其個人店鋪零售訂單上月後臺顯示必須達到100美金或以上。';
$lang['r5_content_3'] = '
獎勵：<br/> 滿足條件的店主可以在下個月的每周享受該獎勵，獎勵每周壹發放。<br/><br/>
獎勵算法：<br/>店主所有的（壹級分店店主+二級分店店主）的上周獎金 * 5%。<br/>
';
$lang['r5_content_4'] = '<br/><span class="label label-important">註意：<br/>
1) 該項獎金在每月初審核上個月符合條件的店主，審核通過的店主在本月每周都可享受該項獎金。<br/>
2) 該項獎金制度每月都有店鋪的訂單金額要求。</span>';

/* 每月杰出店铺分红奖 */
$lang['r6_content_1'] = '每月傑出店鋪分紅獎是為那些有傑出成就的店鋪而設計的。';
$lang['r6_content_2'] = '
<br/>
條件:<br/>
1）銀級合格店鋪 ；<br/>
2）店主職稱必須是資深店主（及以上）；<br/>
3）其個人店鋪零售訂單上月後臺顯示必須達到100美金或以上。<br/><br/>
獎勵：滿足條件的店主可以在下個月15號拿到獎勵。<br/>
獎勵算法：(公司上月全球銷售利潤 * 4%／合格總人數)+(該店主分紅點 / 參加該分紅所有用戶的總分紅點 * 公司上月全球銷售利潤 * 6%)。<br/>
';
$lang['r6_content_3'] = '<br/><span class="label label-important">
註意：該項獎金制度每月有店鋪的訂單金額要求。</span>';

/*  每月总裁销售奖   */
$lang['r7_content_1'] = '每月總裁銷售獎是為獎勵那些對公司市場發展有重大貢獻的全球銷售副總裁而設計的。';
$lang['r7_content_2'] = '
條件：<br/>
1）鉆石級合格店鋪；<br/>
2）店主職稱需要是全球銷售副總裁；<br/>
3）其團隊擁有銅級（及以上）合格店鋪數3000及以上，1/2規則在此適用，即每組團隊最多只計1500個銅級（及以上）合格店鋪，這樣做是為了激勵領導人建設更具有發展力的團隊；<br/>
4）個人店鋪上個月達到了250美元的銷售額。<br/><br/>
獎勵：滿足條件的全球銷售副總裁將獲得其團隊第二項獎金制度以外的上月銷售利潤的0.25%，獎勵將在每個月15號發放。
<br/><br/><span class="label label-important">註意：該項獎金制度每月都有店鋪的訂單金額要求。</span>';


$lang['r8_content_1'] = '條件：<br/>
1）鑽石級合格店鋪；<br/>
2）店主職稱為市場總監；<br/>
3）上個月個人店鋪達到了100美元的銷售額以及4個及以上訂單數。 <br/><br/>
獎勵：滿足條件的市場領袖可以在下個月15號享受該獎勵。 <br/><br/>
獎勵算法：每位合格領袖的獎勵＝公司上月全球銷售利潤* 1% ／合格領袖的總人數。';
$lang['r8_content_2'] = '<br/><span class="label label-important">注意：該項獎金制度每月都有店舖的訂單金額和訂單數要求。 </span>';

$lang['r10_content_1'] = '1)高級市場主管:<br/>
條件：<br/>
a）鑽石級合格店鋪；<br/>
b）店主職稱為高級市場主管;<br/>
c）其個人店鋪零售訂單上月後臺顯示必須達到100美金或以上。 <br/>
獎勵：滿足條件的高級市場主管可以在下個月15號享受該獎勵。 <br/>
獎勵算法：每位合格領導人的獎勵＝(公司上月全球銷售利潤* 3%／合格領導人的總人數)+(該領導分紅點/ 參加該分紅領導的總分紅點* 公司上月全球銷售利潤* 1%) 。 <br/><br/>

2)市場總監:<br/>
條件：<br/>
a）鑽石級合格店鋪；<br/>
b）店主職稱為市場總監;<br/>
c）上個月個人店鋪達到了200美元的銷售額。 <br/>
獎勵：滿足條件的市場總監可以在下個月15號享受該獎勵。 <br/>
獎勵算法：每位合格領導人的獎勵＝(公司上月全球銷售利潤* 1%／合格領導人的總人數)+(該領導分紅點/ 參加該分紅領導的總分紅點* 公司上月全球銷售利潤* 0.5%)。 <br/><br/>

3)全球銷售​​副總裁:<br/>
條件：<br/>
a）鑽石級合格店鋪；<br/>
b）店主職稱為全球銷售副總裁;<br/>
c）上個月個人店鋪達到了300美元的銷售額。 <br/>
獎勵：滿足條件的全球銷售副總裁可以在下個月15號享受該獎勵。 <br/>
獎勵算法：每位合格領導人的獎勵＝(公司上月全球銷售利潤* 0.5%／合格領導人的總人數)+(該領導分紅點/ 參加該分紅領導的總分紅點* 公司上月全球銷售利潤* 0.25%) 。 <br/><br/>
';
$lang['r10_content_2'] = '<br/><span class="label label-important">注意：該項獎金制度每月都有店舖的訂單金額要求。 </span>';

/*销售精英日分红*/
$lang['r11_content']="
條件：<br/>
1）任何級別的合格店主（含免費店主）；<br/>
2）上個月完成壹個銅級或以上產品套餐（含升級套裝）的銷售 或者 其個人店鋪上個月零售訂單銷售額達到250美金或以上。<br/>
<br/>
獎勵：<br/>如果店主上個月滿足了以上條件，則本月每天都可以享受該分紅。<br/>
<br/>
獎勵算法：用戶上個月的銷售額（套裝銷售額＋零售訂單銷售額）／ 參加該分紅用戶的總銷售額 * 公司昨天銷售利潤的10%<br/>
<br/><br/>
<span class='label label-important'>註意：<br/>
1) 該項獎金制度每月都有銷售要求（單品或套餐銷售）；<br/>
2) 因為降級後又重新升級產生的套裝銷售額不再重復計算；<br/>
";

/* 新会员专享奖  */
$lang['r12_content_1'] = '
條件：<br/>
新註冊成為TPS會員的用戶，在升級為銅級（或以上）並且下了50美金（或以上）的零售訂單之後，將在合格之後第二天開始享受該項獎金，直到店鋪升級日的30天後截止。<br/>
獎金根據會員的等級、以及該會員消費的訂單金額來計算，會員等級越高、消費的訂單金額越多，該項獎金將更多。<br/>
<br/>
獎勵：<br/>單個用戶獎金 = 該用戶所下的訂單總金額（含升級的訂單+普通零售訂單）／ 享受該獎金的所有用戶的訂單總金額 * 公司昨天銷售利潤的2%。<br/>
<br/>';

/*每周团队分红*/
$lang['r_week_share_content'] = "
[條件]<br/>
1) 鉆石級合格店鋪；<br/>
2) 店主職稱是資深店主及以上；<br/>
3) 上個月個人店鋪累計了100美金銷售額。<br/>
<br/><br/>
[獎勵]<br/>
第壹次合格的會員，下壹個周壹享受該獎金。<br/>
[獎勵算法]<br/>
獎金根據會員上個月個人店鋪零售訂單銷售額、上個月店主職稱、上個月個人店鋪等級和分紅點來分配獎金。<br/>店主職稱越高，個人店鋪等級越高，個人店鋪零售訂單銷售額越多，則將會分配到更豐厚的獎金。<br/>公司每周從全球利潤中拿出10%進行分配。
";

/*佣金补单*/
$lang['commission_order_repair'] = "佣金補單";
$lang['repair_order_year_month'] = "補單年月";
$lang['commission_type'] = "獎項";
$lang['commission_year_month'] = "獎金年月";
$lang['sale_amount_lack'] = "需補單金額";
$lang['deadline'] = "有效期";
$lang['repair_order'] = "補單";
$lang['order_repairing'] = "補單中...";
$lang['score_year_month'] = "業績年月";
$lang['comm_date'] = "獎金日期";
$lang['commission_withdraw_amount'] = "應收回金額";
$lang['if_not_repair_order_before_deadline'] = "如果在有效期內未補單";
$lang['order_repair_notice'] = "請注意：<br/>
1、以下列表是您在取消訂單後導致某些獎金不滿足拿獎要求而產生的，您需要在有效期內補足相應的訂單金額，否則相關的獎金將會被系統抽回；<br/>
2、您補單的訂單金額將全部算作需要補單的那個月的業績。";
$lang['order_repair_step'] = "補單流程：<br/>
1、點擊您想要補單的那條記錄後面的“補單”按鈕，點擊後該按鈕將變為“補單中”狀態；<br/>
2、去商城下訂單，當完成的訂單金額不小於補單金額時，此時列表中相應的需補單記錄將消失，此時補單就完成了。
";
$lang['modifyVal_illegal'] = "錯誤的有效期";

//职称晋升介绍
$lang['rank_advancement']="職稱晉升介紹";

$lang['mso']="a) 資深店主(MSO)： ";
$lang['mso_context']="1)自己的店鋪是銅級（或以上）的合格店鋪；2)自己成為銅級（或以上）店主後，直接推薦開了3個銅級（或以上）的合格分店鋪。";

$lang['sm']="b) 市場主管(MSB)：";
$lang['sm_context']="自己的店鋪是銅級（或以上）的合格店鋪；2)自己成為銅級（或以上）店主後，分店中有3個資深店主，每組至少1個資深店主";

$lang['sd']="c) 高級市場主管(SMD)：";
$lang['sd_context']="自己的店鋪是銅級（或以上）的合格店鋪；2)自己成為銅級（或以上）店主後，分店中有3個市場主管，每組至少1個市場主管";

$lang['vp']="d) 市場總監(EMD)：";
$lang['vp_context']="自己的店鋪是銅級（或以上）的合格店鋪；2)自己成為銅級（或以上）店主後，分店中有3個高級市場主管，每組至少1個高級市場主管";

$lang['gvp']="e) 全球市场销售副总裁(GVP)";
$lang['gvp_context']="自己的店鋪是銅級（或以上）的合格店鋪；2)自己成為銅級（或以上）店主後，分店中有3個市場總監，每組至少1個市場總監";

$lang['finally explanation right']="f) 公司享有最終解釋權.";

$lang['back_account'] = '<span style="color: purple"></span> 秒返回到賬戶欄目...';

$lang['Bulletin_title'] = 'TPS公告';
$lang['upload'] = '上傳';
$lang['important'] = '<span style="color:#f00;font-weight:bold;">重要提示：</span>';

$lang['take_out_cash_sum'] = '歷史提現的總額：';
$lang['transfer_to_cash_sum'] = '轉賬給其他會員的總額：';
$lang['enroll'] = '新人加盟鏈接';
$lang['no_collection'] = '目前您沒有關注商品';
$lang['try_again'] = '請重試';
$lang['receipt_title_'] = 'TPS商城收據';
$lang['receipt_content_'] = 'TPS商城收據已送達，請查看附件。';
$lang['deliver_title_'] = 'TPS商城訂單發貨通知';
$lang['deliver_content'] = '您於%s購買的產品訂單%s現已通過%s發貨完成,運單號是%s。請注意跟踪查收!';
$lang['order_date'] = '訂單日期';
$lang['order_amount_no'] = '訂單金額';
$lang['order_amount_no_tip'] = '訂單金額不含運費';
$lang['customer_name'] = '顧客名稱';
$lang['filter_month'] = '月份';
$lang['cancel_collection'] = '取消關注';

$lang['you_also_not_choose_product']='妳還有未選購的商品,請從歡迎頁面進入挑選';
$lang['dear_'] = '尊敬的';
$lang['email_end'] = '您可以通過support@shoptps.com隨時聯繫我們的客服.<br>
祝好.<br>
TPS管理團隊';
$lang['account_status']='賬戶狀態:';
$lang['fee_num_msg']='<span style="color: #880000;">(累計:fee_num:個月月費未交)</span>';
$lang['fee_num_msg_one']='<span style="color: #880000;">(累計:fee_num:個月月費未交)</span>';
$lang['sale_rank_up_time']='(該職稱於<span style="color: #F57403">:sale_rank_up_time:</span>達成)';
$lang['month_fee_arrears']='店舖管理費未交，請繳清後升級。';

//^^^^
$lang['daily_top_performers_sales_pool'] = '銷售精英日分紅';
$lang['cash_pool_auto_to_month_fee_pool'] = '現金池自動轉月費池';

$lang['new_func'] = '新功能提醒';
$lang['auto_to_month_fee_pool_notice'] = "
<p>親愛的tps會員，爲了方便您每月的月費支付。</p>
<p>我們系統新增了壹個【現金池自動轉月費池】的功能。</p>
<p>您只要設置了此功能，以後每個月當您需要交月費，而月費池中的錢不夠時，</p>
<p>系統會自動從您現金池中轉所需的月費差額到月費池中，以便交月費。<a href='ucenter/commission#month_fee_auto_to'>點此去設置</a></p>";

/** 客服中心 start **/
$lang['tickets_center'] = '工單中心';
$lang['add_tickets'] = '申請工單';
$lang['my_tickets'] = '我的工單';
$lang['tickets_cus_num']='客服編號';
$lang['tickets_title'] = '工單標題';
$lang['tickets_id'] = '工單號';
$lang['tickets_info'] = '工單詳情';
$lang['pls_input_title'] = '請輸入標題';
$lang['exceed_words_limit'] = '超過字數限制';
$lang['count_'] = '共';
$lang['remain_'] = '剩餘';
$lang['max_limit_'] = '最多';
$lang['_words'] = '字';
$lang['waiting_progress'] = '等待處理';
$lang['in_progress'] = '正在跟進';
$lang['ticket_resolved'] = '已解決';
$lang['had_graded'] = '已評分';
$lang['apply_close'] = '申請關閉';
$lang['tickets_no_exist'] = '抱歉，工單不存在';
$lang['attach_no_exist'] = '抱歉，附件不存在';
$lang['tickets_closed_can_not_reply'] = '工單已關閉，不能回复';
$lang['pls_input_reply_content'] = '請輸入回复內容';
$lang['submit_as_waiting_resolve'] = '提交為待解決';
$lang['submit_as_resolved'] = '提交為已解決';
$lang['confirm_submit_tickets_as_resolved'] = '確認提交為已解決嗎？提交後工單將處於關閉狀態，不能回复！';
$lang['tickets_closed_can_not_reply'] = '工單已關閉，不能回复';
$lang['kindly_remind'] = '溫馨提示';
$lang['tickets_type'] = '工單問題類型';
$lang['add_and_quit'] = '加入/退出';
$lang['join_issue'] = '加入問題';
$lang['quit_issue'] = '降級/退出申請';
$lang['up_or_down_grade'] = '升級/支付問題';
$lang['monthly_fee_problem'] = '月費問題';
$lang['platform_fee_problem'] = '平台管理費';
$lang['reward_system'] = '獎勵制度';
$lang['product_recommendation'] = '產品推薦';
$lang['shop_transfer'] = '店鋪轉讓';
$lang['commission_problem'] = '佣金問題';
$lang['order_problem'] = '訂單問題';
$lang['freight_problem'] = '運費問題';
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

/**申请**/
$lang['pls_t_type'] = '請選擇問題分類';
$lang['pls_t_title'] = '請輸入工單標題';
$lang['pls_t_content'] = '請輸入工單描述';
$lang['pls_t_title_or_id'] = '請輸入標題/工單號';
$lang['tickets_save_fail']  = '申請失敗';
$lang['tickets_save_success'] = '申請成功';
$lang['tickets_reply_fail'] = '回复失敗';
$lang['tickets_reply_success']='回復成功';
$lang['tickets_kindly_notice'] = '尊敬的會員，請注意';
$lang['add_tickets_tip1']='1）同一個問題，您只需要建立一個工單，並在“我的工單”裡面查看問題的解決進度，以及與客服進行及時交流。每一個問題從開始到結束都是專一客服為您服務，請留意客服編號。';
$lang['add_tickets_tip2']='2）不同的問題，您需要另外新建一個工單，並耐心等待系統為您分配具體的客服人員為您服務。';
$lang['add_tickets_tip3']='3）請勿對同一個問題建立多個工單，以免浪費您的寶貴時間。謝謝！';
$lang['add_tickets_tip4']='4）客服的工作時間是周一到週五的9：30-12：30和14：00-18：30。客服會盡快處理您的工單​​，一般情況下需要2-3個工作日，敬請諒解。';
$lang['view_tickets_title_']='跟進日誌';
$lang['my_tickets_tip_']='1）您的問題客服會在24小時內跟進處理,如您需要溝通跟進客服，可以致電TPS客服電話(0755-33198568)，告知需轉接的客服編號。';
$lang['my_tickets_tip2_']='2）為了確保您的問題能夠盡快及時解決，請您電話溝通時直接聯絡分配跟進該問題的客服。';
$lang['jixu_submit']='提交新的問題';
$lang['jiexie_previous']='接續上個問題';
$lang['tips_tickets_message']='是否繼續上個正在跟進中的相同類別的問題,工單號#%s或提出新的問題 ?';
$lang['tickets_email_title'] = '您的工單#%s即將自動關閉';
$lang['tickets_email_content'] = '您的工單#%s即將在%d天后自動關閉，為了確保您的問題已經解決，您可以登錄系統進行確認或者致電客服。感謝您的來信！';
$lang['tickets_apply_close_tips'] = '該工單客服已申請關閉，如同意關閉，請點擊“提交為已解決”並進行評分；如不同意關閉，請在對話框內繼續溝通，點擊“提交為待解決”，否則，系統會在12個日曆日後自動關閉該工單。';

/**评分**/
$lang['t_pls_t_score'] = '請評分';
$lang['t_score'] = '分';
$lang['t_very_dissatisfied'] = '很不滿意';
$lang['t_dissatisfied'] = '不滿意';
$lang['t_general'] = '一般';
$lang['t_satisfaction'] = '滿意';
$lang['t_very_satisfactory'] = '非常滿意';

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
/**客服中心 end **/


//供应商推荐奖
$lang['supplier_recommend_commission'] = '供應商推薦獎';
$lang['total_sale_goods_number']='總銷售數量:sale_number:件';

$lang['shop_management']='店鋪管理';
$lang['del_shop']='删除店鋪';
$lang['is_show_delete']='一旦删除店鋪將不可逆轉，是否確定删除店鋪？';
$lang['is_delete_show']='删除店鋪確認';
$lang['del_shop_success']='成功删除店鋪，3秒後自動退出';


//手机重置密码
$lang['new_passwd_not_null'] = "新密碼不能為空";
$lang['new_passwd_rule'] = "新密碼必須是6位數字";
$lang['passwd_not_same'] = "兩次輸入密碼不一致";
$lang['enter_tps_passwd'] = "請輸入登陸密碼";
$lang['phone_code_not_null'] = "請輸入短信驗證碼";
$lang['phone_code_rule_error'] = "驗證碼格式錯誤";
$lang['enter_re_passwd'] = "請再次輸入密碼";
$lang['please_login_first'] = "請登錄";
$lang['update_take_cash_pwd_error'] = "修改失敗,請重試";
$lang['system_error_again_code'] = "系統錯誤,請重新獲取短信驗證碼";
$lang['system_error_again'] = "系統錯誤,請重試";
$lang['phone_code_expire'] = "短信驗證碼過期";
$lang['phone_code_error'] = "短信驗證碼錯誤";
$lang['phone_reset_passwd_success'] = "密碼重置成功";
$lang['email_reset_takecash_passwd'] = "郵箱重置密碼";
$lang['phone_reset_takecash_passwd'] = "手機號重置密碼";
$lang['new_takecash_passwd'] = "新資金密碼";
$lang['new_takecash_passwd_again'] = "再次輸入新資金密碼";
$lang['phone_code'] = "手機驗證碼";
$lang['verify_code'] = "驗證碼";
$lang['get_phone_code'] = "獲取短信驗證碼";
$lang['verify_code_tip1'] = "（驗證碼將發送到 <span style=\"color:red;\"> ";
$lang['verify_code_tip2'] = "</span> 這個手機上）若您尚未驗證手機號，請至<a href=\"account_info\">賬戶信息</a>欄目進行驗證";
$lang['tps_login_pwd_reset'] = "TPS登陸密碼";

$lang['url_not_id_exit'] = '您的首碼不符合規則';
$lang['url_show'] = '首碼修改規則：數位+字母的組合或純字母或會員自己的ID';
$lang['card_upload_error'] = '上传失败，请稍后再试！';

//支付宝绑定解绑
$lang['please_input_code'] = "請輸入驗證碼";
$lang['please_input_cash_passwd'] = "請輸入資金密碼";

//支付宝绑定解绑
$lang['please_input_code'] = "請輸入驗證碼";
$lang['please_input_cash_passwd'] = "請輸入資金密碼";


//手机号绑定解绑
$lang['please_input_mobile'] = "請輸入手機號";
$lang['mobile_format_error'] = "手機號碼格式有誤";
$lang['please_input_code'] = "請輸入驗證碼";
$lang['hacker'] = "黑客";
$lang['binding_mobile_failed'] = "驗證手機號失敗";
$lang['binding_mobile_success'] = "綁定成功";
$lang['mobile_code_error'] = "驗證碼錯誤";
$lang['mobile_code_expire'] = "驗證碼過期";
$lang['please_verify_old_phone'] = "請先驗證原手機號";
$lang['phone_has_been_userd'] = "該手機號已被使用";


//手机号绑定解绑
$lang['please_input_mobile'] = "請輸入手機號";
$lang['mobile_format_error'] = "手機號碼格式有誤";
$lang['please_input_code'] = "請輸入驗證碼";
$lang['hacker'] = "黑客";
$lang['binding_mobile_failed'] = "驗證手機號失敗";
$lang['binding_mobile_success'] = "綁定成功";
$lang['mobile_code_error'] = "驗證碼錯誤";
$lang['mobile_code_expire'] = "驗證碼過期";
$lang['please_verify_old_phone'] = "請先驗證原手機號";
$lang['phone_has_been_userd'] = "該手機號已被使用";
$lang['send_code_frequency'] = "操作太頻繁，請稍後再試";

$lang['code_has_sent_to'] = "驗證碼已發送至";
$lang['please_check_out'] = "請查收";
$lang['not_receive_code'] = "未收到驗證碼?";
$lang['not_receive_reason'] = "可能原因：<br/>1、請檢查您的手機號是否填寫正確;<br/>2、請檢查手機短信是否設置攔截功能;<br/>3、短信可能發放延遲，請等待3-5分鐘;";
$lang['mobile_can_not_same'] = "新手機號不能和原手機號一樣";

$lang['bind_success'] = "綁定成功";
//银行卡提现页面
$lang['debit_card'] = "銀行卡提現";
$lang['bank_name'] = "開戶行名稱";
$lang['bank_branch_name'] = "開戶行支行名稱";
$lang['bank_number'] = "銀行稱號";
$lang['confirm_bank_number'] = "確認銀行賬號";
$lang['bank_user_name'] = "開戶人名稱";
$lang['please_input_bank_name'] = "請填寫開戶行名稱";
$lang['please_input_bank_branch_name'] = "請填寫開戶行支行名稱";
$lang['please_input_bank_number'] = "請填寫銀行賬號";
$lang['please_input_password'] = "請填寫資金密碼";
$lang['please_bind_mobile'] = "請先綁定手機號";
$lang['please_verify_mobile'] = "請先驗證手機號";
$lang['bank_number_not_same'] = "兩次銀行賬號輸入不一致";
$lang['unbind_bank_card'] = "解綁銀行卡";
$lang['bind_bank_card'] = "綁定銀行卡";
$lang['bank_card_infomation_lose'] = "銀行卡綁定信息不完整";
$lang['beyond_amount_fee'] = "超過提現最大金額";

$lang['bind_bank_needname'] = "注意：綁定的銀行賬號必須是用：‘:name:’名義開得銀行賬戶！";
$lang['not_beyond_50'] = "不能超過50個字符！";
$lang['bank_name_china_only'] = "開戶行名稱只能輸入漢字";
$lang['bank_branch_name_china_only'] = "開戶行支行名稱只能輸入漢字";
$lang['bank_number_only_number'] = "銀行賬號只能是數字";

$lang['bank_take_cash_fee'] = "銀行卡提現手續費，單筆最大不超過：$5";
$lang['not_bind_bank_card'] = "未绑定银行卡";

//收货地址管理 m by brady.wang
$lang['my_addresses'] = "我的收貨地址";
$lang['address_not_exists'] = "地址不存在";
$lang['156_address'] = "中國大陸收貨地址";
$lang['344_address'] = "中國香港區收貨地址";
$lang['840_address'] = "美國區收貨地址";
$lang['410_address'] = "韓國區收貨地址";
$lang['000_address'] = "其他地區收貨地址";
$lang['user_region'] = "所在地區";
$lang['user_address_detail'] = "詳細地址";
$lang['address_mobile'] = "手機號碼";
$lang['address_action'] = "操作";
$lang['spread'] = "展開";
$lang['pack_up'] = "收起";
$lang['address_edit'] = "修改";
$lang['set_success'] = "設置成功";
$lang['set_default_address'] = "設為默認";
$lang['address_limit'] = "(該地區你已經創建:number:個地址，最多可創建5個地址)";
$lang['china_land'] = "中國大陸";
$lang['china_hk'] = "中國香港";
$lang['other_region'] = "其他地區";
$lang['address_delete'] = "地址刪除";
$lang['you_will_del_address'] = "您將刪除地址！";
$lang['access_deny'] = "無權限";
$lang['modify_address_failed'] = "修改失敗";

$lang['address_phone_check'] = "6-20個數字";
$lang['address_phone_check_1'] = "手機號碼只能是6-20個數字";
$lang['address_reserve_check'] = "6-20個字符";
$lang['address_reserve_check_1'] = "備用號碼只能是6-20個字符";
$lang['address_zip_code_check'] = "小於20個字符";
$lang['address_zip_code_check_1'] = "郵政編碼必須小於20個字符";
$lang['checkout_addr_detail_placeholder'] = "請填寫詳細收貨地址";
$lang['mobile_system_update'] = "短信系統維護升級中";

//订单地址修改
$lang['order_status_not_allow'] = "訂單狀態不允許修改地址";
//新版地址验证
$lang['check_addr_rule_phone'] = "請輸入正確的手機號碼";
$lang['check_addr_rule_reserve_num'] = "請輸入正確的備用號碼";
$lang['check_addr_rule_zip_code'] = "請輸入正確的郵政編碼";

//重置邮箱
$lang['email_code_not_nul'] = "驗證碼不能為空";
$lang['email_rule_error'] = '郵箱格式不正確';
$lang['please_get_code'] = '請先獲取驗證碼';
$lang['verify_code_tip3'] = "驗證碼將發送到 ";
$lang['please_bind_email_first'] = "请先绑定邮箱";
$lang['new_takecash_passwd_again'] = "再次輸入資金密碼";
$lang['tps_password_wrong'] ="TPS登陸密碼不正確";

#新用户专属奖金
$lang['new_member_bonus'] = "新會員專享獎";
$lang['supplier_recommendation'] = "供應商推薦獎";
$lang['month_expense'] = "平台管理費";

//修改手机号
$lang['change_mobile'] = "修改手機號";
$lang['new_mobile_not_null'] = "手機號不能為空";
$lang['re_enter_new_mobile'] = "請再次輸入新手機號";
$lang['not_match_your_input'] = "兩次輸入不一致";
$lang['choose_edit_type'] = "選擇修改方式";
$lang['verify_identify'] = "驗證方式";
$lang['verify_new_mobile'] = "驗證新手機號";
$lang['verify_by_old_phone'] = "通過原手機號修改";
$lang['verify_by_email'] = "通過郵箱修改";
$lang['new_phone_rule_error'] = "手機號格式不正確";
$lang['new_phone'] = "新手機號";
$lang['next_step'] = "下一步";
$lang['new_phone_edit_successed'] = "新手機號修改完成！";
$lang['resend_code_again'] = "重新發送";

//解冻用户登录
$lang['unfrost'] = "用戶登錄解凍";
$lang['please_input_unfrost_account'] = "請輸入要解凍的賬號";
$lang['please_input_unfrost_account_again'] = "請再次輸入要解凍的賬號";
$lang['input_unfrost_not_same'] = "兩次輸入不一致";
$lang['redis_off'] = "redis服務關閉，無需解凍";
$lang['unforst_success'] = "解凍成功";
$lang['unfrost_needless'] = "無需解凍賬號";