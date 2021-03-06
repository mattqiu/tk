<?php
/*paypal提现语言包*/
$lang['paypal'] = 'PayPal';
$lang['paypal_withdraw'] = 'paypal提现';
$lang['confirm_paypal_info'] = '确认paypal信息，paypal账号：{0}';
$lang['account'] = '帐号';
$lang['account_name'] = '帐号名称';

//转账提现增加短信验证功能
$lang['mobile_verify_not'] = "手机号未认证";
$lang['not_bind_mobile'] = "您未绑定手机号码，请至<a href = '/ucenter/account_info/index' class='go_modify_PIN'>账户信息</a>栏目进行验证";
$lang['not_verify_mobile'] = "您的手机号未认证，请至<a href = '/ucenter/account_info/index' class='go_modify_PIN'>账户信息</a>栏目进行验证";
$lang['mobile_not_confirm'] = "手机号未认证";






/*paypal提现*/
$lang['paypal_prompt1'] = '手续费为提现金额的2%，最高不超过$50';
$lang['paypal_email'] = 'paypal邮箱';
$lang['paypal_email_q'] = '确认paypal邮箱';
$lang['paypal_binding'] = '绑定paypal';
$lang['paypal_unbundling'] = '解绑paypal';
$lang['no_than']='不能低于';
$lang['prompt_titlesa']='验证码已直接发送到您所填写的邮箱中。';
$lang['prompt_2sa']='1、请登录该邮箱查看。';
$lang['paypal_tishi']='未绑定paypal';
$lang['where_code']='验证码在哪里？';
$lang['withdrawal_paypal_tip']='单笔提现金额最大不超过：$60000';
$lang['withdrawal_bank_tip']='(单笔提现金额最大不超过：$12000)';
$lang['withdrawal_paypal_tip2']='导出文件的提现总金额不能超过$60000';
$lang['withdrawal_paypal_tip3']='导出文件的笔数不能超过250笔';
/** 4月份休眠用户活动*/
$lang['april_title'] = '温馨提示';
$lang['april_email_title'] = '有关TPS账户从休眠到正常状态的消息';
$lang['april_email_content'] = '公司在4月份针对付费的老客户有一项优惠活动。如您之前有欠月费，现在可以通过在本月内做到累积50美金订单销售额来使得账户从欠月费恢复正常。
你也可以选择不参加该计划，用月费池或现金池的钱按所欠月费的50%来支付过去的月费（从4月份开始，老会员月费减半： 银级：10美；金级：20美金；钻石：30美金）';
$lang['april_content_1'] = '系统检测到您已经欠月费2个月或以上，公司对此有优惠计划：您可以通过做到累积50美金订单销售额来抵扣之前所欠月费，使得账户恢复正常。';
$lang['april_content_2'] = '参加此优惠计划';
$lang['april_content_3'] = '不参加该计划，并同意设置自动从现金池转差额到月费池以支付月费。(银级：10美；金级：20美金；钻石：30美金）';
$lang['april_content_4'] = '不参加该计划';
$lang['april_content_5'] = '注意：1、此计划中完成的50美金订单不可取消；2、账户恢复之前因为休眠没有拿到的奖金不补发。';
$lang['queue_order_content'] = 'TPS系统已经收到订单<span class="msg">%s</span>的支付通知，由于加盟会员过多，您的订单正在排队发放奖励，请耐心等待。';

/** 发送注册验证码 */
$lang['email_captcha_title'] = 'TPS验证码';
$lang['email_captcha_content'] = '您的TPS验证码： %s，有效时间30分钟，请尽快验证！';
$lang['phone_captcha_content'] = '【TPS】您的TPS验证码： %s，有效时间30分钟，请尽快验证！';
$lang['reg_success_account'] = '请点击上方登入链接,登录账户,完善资料!';

//修改订单地址
$lang['mobile_code_will_send'] = "（验证码将发送至手机号：<span style color:'red'>:mobile:</span>)";
$lang['mobile_code_has_send'] = "验证码已经发送到您的账户绑定手机号 :mobile:上，请查收！";
$lang['mobile_not_verified'] = "您的账户未验证手机号码！您需要先验证手机号码才能修改地址!";
$lang['mobile_not_bind'] = "您的账户未绑定手机号码！您需要先绑定手机号码才能修改地址! <a style='color:blue;font-style:bold;font-size:16px;' href='/ucenter/account_info'>去绑定</a>";



$lang['ucenter_loc_sure'] = '当前区域和订单的配送区域不一致,此操作将会切换至订单的配送区域,是否切换？';

/** 银联预付卡 */
$lang['pre_card_title'] = '侨达“环球通”银联预付卡';
$lang['pre_card_tip'] = '本卡目前仅限韩国、香港、澳门会员申请。';
$lang['pc_name'] = '姓名';
$lang['chinese_name'] = '中文名';
$lang['pc_card_no'] = '卡号';
$lang['pc_mobile'] = '手机号码';
$lang['pc_card_no_tip'] = '未领取卡片不用填写 ';
$lang['pc_card_no_tip2'] = '已领取环球通银联卡的会员请正确填写卡号，以便绑定激活。';
$lang['pc_nationality'] = '国籍';
$lang['pc_issuing_country'] = '护照发放国';
$lang['pc_address_prove'] = '地址证明';
$lang['pc_ID_card'] = '证件号码';
$lang['pc_ID_card_type_0'] = '身份证';
$lang['check_ID_card'] = '审核身份证';
$lang['pc_ID_card_type_1'] = '护照';
$lang['pc_ID_no'] = '输入有效证件号码';
$lang['pc_ID_card_upload'] = '身份证明上传';
$lang['pc_ID_card_upload_tip'] = '身份证需要正反二面,护照上传正面';
$lang['pc_ID_front'] = '正面';
$lang['pc_ID_reverse'] = '反面';
$lang['pc_ID_card_ship'] = '卡片寄送地址';
$lang['pc_country'] = '居住国家';
$lang['pc_ship_to_address'] = '请输入详细地址';
$lang['pc_submit'] = '申请开通';
$lang['pc_email'] = 'EMail地址';
$lang['pc_payment_tip'] = '开卡制卡费$5。开卡激活，大概需要1到2周时间。资料审核不通过，将会退回开卡费。';
$lang['pc_agreement'] = '我已阅读<span class="yued c-hong">《开卡协议》</span>。';
$lang['pc_status_0'] = '未支付';
$lang['pc_status_1'] = '待审核';
$lang['pc_status_2'] = '驳回';
$lang['pc_status_3'] = '开卡中';
$lang['pc_status_4'] = '已寄出';
$lang['pc_status_5'] = '已审核';
$lang['pc_status_pending'] = '开卡中';
$lang['pc_apply_tip'] = '“环球通”银联预付卡  申领<a href="/ucenter/prepaid_card">点这里</a>';
$lang['pc_applied'] = '已申请“环球通”银联预付卡';
$lang['pc_applied_success'] = '资料提交成功,等待审核...';
$lang['check_prepaid_card'] = '审核预付卡';
$lang['pc_address_prove_tip'] = '护照或户口本';
$lang['prepaid_card_no_exist'] = '卡号不存在';
$lang['assign_card_no'] = '分配卡号';
$lang['assign_card_no_error'] = '此卡号状态异常';
$lang['pc_without'] = '银联卡已分配完';
$lang['pc_agree_t'] = '为保障您的权利，请先阅读以下内容。';



$lang['admin_withdrawal_success_content'] = '您在TPS申请的提现请求，已成功处理。请查询相应提现账号。';
$lang['admin_withdrawal_success_title'] = 'TPS提现成功处理通知';

//提现
$lang['withdorw_list_not_null'] = "您还有补单记录未完成，所以不允许提现！";

/*welcome*/
$lang['last_login_info'] = '您上次于 :time 在 :contry :province :city 登录';
$lang['mall_expenditure'] = '商城消費';
$lang['user_is_store'] = '用户已经是店主';
$lang['mothlyFeeCoupon'] = '月费券';
$lang['clickToUse'] = '点击使用';
$lang['return_back'] = '佣金抽回返补';
$lang['order_profit_negative'] = '订单利润不足$0.01';
$lang['maxie_mobile'] = 'Maxie Mobile';
$lang['split_order_tip'] = '您订单中的商品在不同库房或属不同商家，故拆分为以下订单分开配送，给您带来的不便敬请谅解。';
$lang['order_0_'] = '该订单产品是促销品，没有利润提成，但订单金额仍会累计到您店铺的销售业绩。';
$lang['upgrade_switch_tip'] = '因系统维护，现暂停店铺升级功能！其他功能不受影响，给您造成不便，敬请谅解！谢谢！';

/*超过3个月未付款的通知邮件*/
$lang['over3MonthNotyfyTitle'] = '月费补缴优惠';
$lang['over3MonthNotyfyContent'] = '您好。你收到这封邮件是因为您的账号已经有3个月没有缴月管理费了。为能让您的账号恢复到正常状态以便您能继续收到您的2X5见点奖， 我们已给您账号一个特殊优惠， 即您只需要补缴一个月的月费（而不用补缴3个月的月费）即可以马上恢复您的账号至正常状态。非常谢谢您的耐心。有问题欢迎致电给客服。谢谢。';

/** 提交订单是 地址信息提示 */
$lang['order_address_error_tip'] = '因地址有误或电话不正确造成退货退款或拒收退货的情况，在退款时会扣除来回运费。请仔细检查收货地址！';
$lang['edit_address'] = '修改地址';
$lang['customs_clearing_number'] = '海关号';

$lang['read'] = '已读';
$lang['company_account'] = '公司账户';
$lang['ok'] = '确认';
$lang['cancel'] = '取消';
$lang['_no'] = '取消';
$lang['add'] = '添加';
$lang['uniqueCard'] = '身份证号码已经存在';
$lang['demote_level'] = '佣金抽回';
$lang['transfer_point'] = '佣金转分红点';
$lang['transfer_cash'] = '分红点转佣金';
$lang['funds_pwd_reset'] = '重置资金密码';
$lang['yspay'] = '银联（银盛支付）';
$lang['funds_pwd_tip'] = '资金密码必须是8-16位的数字与大小写字母的组合';
$lang['forgot_funds_pwd'] = '忘记了资金密码?';
$lang['payee_info_incomplete'] = '收款人的银行卡信息不完整';
$lang['payee_info'] = '收款人信息';
$lang['bank_name'] = '开户行名称';
$lang['bank_card_number'] = '卡号';
$lang['c_bank_card_number'] = '确认卡号';
$lang['card_number_match'] = '卡号不一致';
$lang['card_holder_name'] = '开户人名称';
$lang['remark'] = '备注';
$lang['remark_content'] = '备注';
$lang['bank_'] = '银行名称';
$lang['bank_name_branches'] = '支行名称';
$lang['subbranch'] = '支行名称';
$lang['confirm_bank_info'] = '确认收款人信息：{0}{1}，卡号：{2}，持卡人：{3}';
$lang['confirm_maxie_info'] = '确认 Maxie Mobile 信息: {0}';
$lang['example1'] = '';
$lang['example2'] = ':如-南头支行';

$lang['withdrawal'] = '提现';
$lang['cancel_withdrawal'] = '取消提现';
$lang['month_fee_date'] = '月费日';
$lang['day_th'] = '号';
$lang['type_tps'] = '手动';
$lang['withdrawal_tip'] = '保留2位小数';
$lang['coupon'] = '优惠券';
$lang['monthly_fee_coupon_notice'] = '您有一张月费抵用券，您可使用它支付相应等级的一个月的月费。';
$lang['no_active_monthly_fee_coupon'] = '您的月费抵用券已经使用过了。';
$lang['free_mem_have_no_monthly_fee_coupon'] = '您是免费会员，没有月费抵用券。';
$lang['user_monthli_fee_coupon_success'] = '使用月费抵用券成功,系统已经给您的月费池充值了相应的月费，请查收！';

$lang['freeze_tip_title'] = '店铺月费欠款提醒';
$lang['freeze_tip_content'] ='<p>尊敬的会员，</p>
<p>请您注意由于%s日—%s 日的店铺管理费未付已超过7天，您当前账户已经停止收到奖金。请立即向月费池补足所有拖欠的店铺管理费。谢谢您的关注。</p>
<p>顺祝安好！</p>
<p>TPS 管理团队</p>';

$lang['id_card_num_exist'] = '身份证号已存在';
$lang['complete_info'] = '请确认所有个人信息准确无误，在点击下面提交键后，个人资料将不能修改，等待TPS审核。';
$lang['reset_email_tip'] = '注意：输入登陆密码后，会有一个链接发送到您的<strong style="color: #ff0000">%s</strong>邮箱。 ';
$lang['ewallet_email_tip'] = '注意：申请成功后，会有一封关于电子钱包信息的邮件发送到您的 <strong style="color: #ff0000">%s</strong> 邮箱，点击链接激活您的电子钱包账户。';

$lang['month_fee_note'] = '支付完成后，如果月费池金额没有改变，请不要恐慌，几分钟后刷新试试。';
$lang['payment_note'] = '支付完成后，如果等级没有改变，请不要恐慌，几分钟后刷新试试。';
$lang['ewallet_success'] = '恭喜，电子钱包申请成功。';
$lang['no_ewallet_name'] = '请输入电子钱包的用户名。';
$lang['login_use'] = '电子钱包的登录用户名。';
$lang['login_email'] = '接收电子钱包的所有邮件（激活，转账，通知等等）。';
$lang['ewallet_name'] = '电子钱包用户名';
$lang['ewallet_apply'] = '申请电子钱包账户';
$lang['ewallet_email'] = '电子钱包邮箱';
$lang['ewallet_before'] = '正在处理这个请求...';
$lang['ewallet_after'] = '操作成功,马上跳转到電子錢包...';
$lang['ewallet_tip'] = '请前往 我的帐户 &rarr; 账户信息 申请電子錢包帐号。';

$lang['store_level'] = '店铺等级';
$lang['alert'] = '提示';
$lang['disclaimer'] = '声明';
$lang['welcome_notice1'] = '你现在还是免费的月费等级，因此无法得到2×5矩阵见点奖和138矩阵全球每日销售分红奖。<br>并且你团队下面的免费店主如果在你之前升级成为银级以上的店主，他们可能永远不会在你的2×5矩阵中。<a href="/ucenter/member_upgrade">点击这里马上升级 >></a>';
$lang['welcome_notice2'] = '現在你是免費的店鋪，因此无法得到团队销售提成奖和任何分红奖。<a href="/ucenter/member_upgrade">点击这里马上升级 >></a>';
$lang['upgrade_notice'] = '您可以"一步直接升级"，来同时升级月管理费等级和店铺等级。<a class="go_upall_div" href="Javascript: void(0);">点击这里去"一步直接升级" >></a>';

$lang['monthly_fee_'] = '步骤 1 ：加入矩阵 / 升级矩阵中的月费等级';
$lang['cur_monthly_fee_level'] = '月费等级 : ';
$lang['product_set'] = '购买产品套装';
$lang['month_fee_user_rank'] = '月费等级不正确';
$lang['month_user_rank'] = '月费等级不正确';
$lang['month_fee_rank_empty'] = '请先完成步骤1';
$lang['upgrade_once_in_all'] = '一步直接升级： [加入矩阵 / 升级矩阵中的月费等级] + [购买产品套装]';
$lang['upgrade_all_level_title'] = '(月管理费 & 店铺) 等级';

$lang['monthly_fee_level'] = '月管理费等级';
$lang['diamond'] = '钻石级';
$lang['gold'] = '白金级';
$lang['silver'] = '银级';
$lang['bronze'] = '铜级';
$lang['free'] = '免费级';
$lang['realName'] = '真实姓名';
$lang['user_address'] = '地址';
$lang['mobile'] = '手机号';
$lang['welcome_page'] = '欢迎页面';
$lang['welcome_msg'] = '欢迎加入TPS';
$lang['review_account_info'] = '您可以到栏目';
$lang['review_account_info_2'] = '查看您的账户信息，并完善相关资料。';
$lang['view_complete_your_info'] = '查看/完善 账户信息';
$lang['up_level'] = '升级';
$lang['up_level_notice_2'] = '进行升级。';
$lang['order_pay_time'] = '订单付款时间';
$lang['customer_'] = '顾客';
$lang['order_amount'] = '订单金额';
$lang['individual_store_sales_commission'] = '个人店铺销售提成';
$lang['order_id'] = '订单号';
$lang['commission'] = '佣金';
$lang['accumulation_commission'] = '累积提成金额';
$lang['commission_log'] = '提成记录';
$lang['my_rank'] = '我的职称';
$lang['profit_sharing_info'] = '分红信息';
$lang['profit_sharing_time'] = '分红时间';
$lang['profit_sharing_require'] = '分红条件';
$lang['profit_sharing_formula'] = '分红算法';
$lang['profit_sharing_time_content'] = '每周一的0点';
$lang['profit_sharing_time_content_month'] = '每月8号0点';
$lang['profit_sharing_require_content'] = '需要银级以上店铺';
$lang['profit_sharing_require_content2'] = '自己的店铺上一周至少要有一个$35以上的已付款订单。';
$lang['profit_sharing_require_content3'] = '自己的店铺上一月至少要有10个已付款订单,且总金额$350以上。';
$lang['profit_sharing_formula_content'] = '自己的分红点 / 公司总分红点 * 公司上周利润的4%';
$lang['profit_sharing_formula_content_month'] = '自己的分红点 / 公司总分红点 * 公司上月利润的10%';
$lang['profit_sharing_countdown'] = '距离下次分红还有';
$lang['profit_sharing_enable'] = '能否参与下次分红?';
$lang['yes'] = '能';
$lang['no'] = '不能';
$lang['profit_sharing_point_to_money'] = '分红点转现金池';
$lang['profit_sharing_point_to_money_log'] = '分红点转现金池记录';
$lang['no_condition_1'] = '您不是銀級及以上會員。';
$lang['no_condition_2'] = '您的店鋪本周訂單金額不足$35。';
$lang['no_condition_3'] = '您的店鋪本月訂單金額不足$350。';
$lang['no_condition_4'] = '您的店鋪本月訂單不足10個。';
$lang['sharing_point'] = '分红点';
$lang['bonus_point'] = '(我每天所掙得的分紅點)';
$lang['bonus_point_note'] = '注：以当月第一天总分红点为依据，每月最多只可以转30%到现金池。';
$lang['first_month_day'] = '(当月第一天)';
$lang['total_point'] = '总共';
$lang['sharing_point_enable_exchange'] = '可转移分红点';
$lang['point'] = '点';
$lang['reward_sharing_point'] = '奖励分红点';
$lang['commissions_to_sharing_point_auto'] = '佣金自动转分红点';
$lang['sale_commissions_sharing_point'] = '销售佣金自动转分红点';
$lang['forced_matrix_sharing_point'] = '见点佣金自动转分红点';
$lang['validity'] = '有效期至';
$lang['profit_sharing_sharing_point'] = '分红自动转分红点';
$lang['manually_sharing_point'] = '现金池转分红点';
$lang['sharing_point_to_money'] = '分红点转现金池';
$lang['proportion'] = '比例';
$lang['cur_commission_lack'] = '当前现金余额不足。';
$lang['cur_sharing_point_lack'] = '当前分红点不足。';
$lang['positive_num_error'] = '请输入大于0的数值，如果是小数，保留小数点后面两位。';
$lang['save'] = '保存';
$lang['save_success'] = '保存成功!';
$lang['shift_success'] = '转移成功!';
$lang['profit_sharing_point_log'] = '分红点转入记录';
$lang['pls_sel_profit_sharing_adden_type'] = '请选择分红转入类型';
$lang['current_commission'] = '现金池';
$lang['move'] = '转';
$lang['to'] = '到';
$lang['save_false'] = '保存失败!';
$lang['level_not_enable'] = '店铺未激活';
$lang['month_fee_fail_notice'] = '店铺月费欠款提醒';
$lang['month_fee_fail_content'] = '
请您注意系统未能从月费池扣付 %s日 - %s日 的店铺管理费。请在%s内为月费池充值支付店铺管理费，以保证您的账户状态正常，系统能按时发放您应得的各种佣金和奖励。 您可以点击<a target="_blank" href="%s">这里</a>缴费。谢谢您的关注。';
$lang['month_fee_fail_content_90'] = '
请您注意由于%s—%s的店铺管理费未付已超过7天，您当前账户已经处于休眠状态，将暂时无法收到奖金。请尽快向月费池补足所有拖欠的店铺管理费，以使您的账户恢复正常。您可以点击<a target="_blank" href="%s">这里</a>缴费。谢谢您的关注。';
$lang['24hours'] = '24小时';
$lang['7day'] = '7天';


/*我的信息*/
$lang['my_msg'] = '我的消息';

/*我的代品券*/
$lang['exchangeCoupon'] = '代 品 券';
$lang['suitExchangeCouponRule'] = '代品券规则说明';
$lang['suitExchangeCouponRuleContent'] = '代品券规则说明内容';
$lang['only_use_in_exchange'] = '只限换购区选购商品';
$lang['num'] = '数量';
$lang['expiration'] = '过期时间';
$lang['unlimited'] = '无限制';
$lang['goto_use'] = '去使用';
$lang['no_exchange_coupons'] = '您目前没有代品券。';
$lang['coupons_total_num']='总共有代品券:total_num:张';
$lang['value']='价值';

/*关于代品券*/
$lang['about_exchange_coupon'] = '关于代品券';
$lang['exchange_coupon_1_title'] = '一、什么是代品券';
$lang['exchange_coupon_1_content'] = '代品券是商家为提高会员满意度，进行的兑换套餐和套餐单品的兑换券，代品券共有五个面额，分别为$100 / $50 / $20 / $10 / $1。本代品券只限本会员及本商城使用，不可转让和提现。在店铺升级选择产品时出现，只可在产品套餐特卖区用于兑换套餐和套餐单品。';
$lang['exchange_coupon_2_title'] = '二、代品券的使用规则';
$lang['exchange_coupon_2_content'] = '1. 本代品券只可用于在产品套餐特卖区兑换会员套餐和套餐单品；<br />
                2. 本券只限本会员及本商城使用，不可转让和提现；不可用于TPS店铺管理费、运费的缴纳；<br />
                3. 如遇退货，此券将退回本会员账号；<br />
                4. 此券最终解释权归深圳前海云集品电子商务有限公司所有。';
$lang['exchange_coupon_3_title'] = '三、代品券发放方式说明';
$lang['exchange_coupon_3_content_1'] = '1. 在TPS138会员后台进行店铺升级时，如果会员只选择部分产品套餐及套餐单品，剩下的金额可选择代品券，以后到产品套餐特卖区兑换自己喜欢的产品套餐及套餐单品。';
$lang['exchange_coupon_3_content_2'] = '2. 店铺升级时勾选了代品券的会员，可在<span style="color:#23a1d1;">“我的账户—>我的代品券”</span>里查看。';
$lang['exchange_coupon_4_title'] = '四、代品券的常见问题';
$lang['exchange_coupon_4_content'] = '
(1) 代品券金额是否可以提现？<br />
  答：不可以。<br />
(2) 使用代品券的订单退货时，如何退款？代品券是否可退回？<br />
  答：使用代品券进入产品套餐特卖区换购的订单，如发生退货，退款结算按照实际结算金额退款。对于已使用的代品券，可以返还会员账户。<br />
(3) 代品券可否冲抵TPS店铺管理费、运费等?<br />
  答：都不可以。<br />
(4) 使用代品券兑换产品套餐和套餐单品时，其中代品券的金额能开收据吗？<br />
  答：不能，代品券所用金额已在店铺升级时的订单中开具过收据，不可重复。只能开具订单实际支付金额的收据。';

/*账户信息*/
$lang['member_url'] = 'TPS商城店铺网址';
$lang['member_name'] = 'TPS商城店铺名称';
$lang['modify_member_url'] = '修改网址';
$lang['member_url_prefix_format_error'] = '网址前綴只能是4-15位的数字字母';
$lang['url_can_not_be_other_id'] = '您不能使用其他会员的id做为您的网址前缀。';
$lang['modify'] = '修改';
$lang['modify_store_url'] = '修改店铺网址';
$lang['modify_store_url_notice'] = '您还有<span id="storeModifyleftCounts">%s</span>次修改机会。';
$lang['modify_member_url_notice'] = '您还有<span id="memberModifyleftCounts">%s</span>次修改机会。';
$lang['modify_store_url_count_end'] = '您的店鋪网址修改机会已用完。';
$lang['modify_member_url_count_end'] = '会员个人网址修改机会已用完。';
$lang['store_url_prefix_format_error'] = '店铺网址前綴只能是4-15位的数字字母';
$lang['store_url_exist'] = '店铺网址已经存在';
$lang['url_exist'] = '该网址已经存在';
$lang['modify_store_url_success'] = '修改店铺网址成功。';
$lang['id_card_scan_type_ext_error'] = '身份证扫描件格式不对';
$lang['id_card_scan_too_large'] = '身份证扫描件大小不能超过10M';
$lang['id_scan_condition'] = '大小不超过10M,格式为jpg,gif,bmp,jpeg,png';
$lang['pls_complete_auth_info'] = '当您看到这个信息时，表明您的身份验证还没有通过TPS的审核。';
$lang['enable'] = '激活';
$lang['sensitive'] = '用户名存在敏感詞!';
$lang['have_black_word'] = '存在敏感詞';
$lang['enable_cur_level'] = '激活您的店铺';
$lang['id_card_scan_ok'] = '身份证扫描件已上传';
$lang['not_uploaded'] = '未上传';
$lang['person_id_card_num_exitst'] = '身份证号已提交过了。';
$lang['terms_and_agreement'] = '协议条款';
$lang['terms_1'] = '我同意加盟汤普森合伙人有限公司，不是股权投资和股票投资。我明白我一次性支付的费用是为了购买一套适合自己的国外品牌产品套装， 以及一个“裸商入驻”的跨境电商产品和营销平台，以享受公司给店铺在其购物商城上所提供的名品购物折扣和其商机所带来的财务收入。';
$lang['terms_2'] = '<p>如果在我个人店铺商城没有普通消费者和顾客，TPS 将不会给我发放任何奖金。如果我是从一开始就直接购买银级以上的产品套装，我的每月店铺网站管理费或一次性商城加盟费及购买产品套装费用, 将有三天的免费网店试用期。从第四个工作日开始，TPS将不会同意退款要求。</p>';

/*收货地址*/
$lang['shipping_addr'] = '收货地址';

/*订单中心*/
$lang['order_center'] = '订单中心';

/*我的订单*/
$lang['my_orders'] = '我的自我消费订单';
$lang['my_tps_orders'] = '我的店铺客户订单';
$lang['my_one_direct_orders'] = '我的美国商城订单';
$lang['my_walhao_store_orders'] = '我的沃好订单';
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
$lang['my_affiliate'] = '我的Affiliate订单';
$lang['order_confirm_time'] = '订单确认时间';
$lang['order_pay_date'] = '订单支付时间';
$lang['order_update_date'] = '订单更新时间';
$lang['effective_performance'] = '有效业绩';

/* 收据 */
$lang['order_receipt_font'] = "pmingliu";
$lang['order_receipt_company'] = "前海云集品";
$lang['order_receipt_company_address'] = "地址：";
$lang['order_receipt_company_address_detail'] = "香港九龙长沙湾大南西街609号永义广场21楼全层";
$lang['order_receipt_company_phone'] = "电话：";
$lang['order_receipt_company_phone_detail'] = "(852)2690-0193";
$lang['order_receipt_company_fax'] = "传真：";
$lang['order_receipt_company_fax_detail'] = "(852)3706-2329";
$lang['order_receipt_company_email'] = "邮箱：";
$lang['order_receipt_company_email_detail'] = "support@tps138.com";
$lang['order_receipt_purchase_date'] = "购买日期：";
$lang['order_receipt_member_id'] = "会员 ID ：";
$lang['order_receipt_store_level'] = "店铺等级：";
$lang['order_receipt_user_phone'] = "电话：";
$lang['order_receipt_receiving_address'] = "收货地址：";
$lang['order_receipt_title'] = "收据";
$lang['order_receipt_order_number'] = "订单号：";
$lang['order_receipt_detail_product'] = "商品描述";
$lang['order_receipt_detail_price'] = "单品价格<span>（美元）</span>";
$lang['order_receipt_detail_qty'] = "数量";
$lang['order_receipt_detail_amount'] = "总计<span>（美元）</span>";
$lang['order_receipt_coupons'] = "TPS代品券";
$lang['order_receipt_product_amount'] = "商品总计：";
$lang['order_receipt_coupons_amount'] = "代品券：";
$lang['order_receipt_freight'] = "运费：";
$lang['order_receipt_actual_payment'] = "实际支付：";
$lang['order_receipt_payment_terms'] = "支付方式：";
$lang['order_receipt_commitment'] = "承诺所有商品三天无理由退货。";
$lang['order_receipt_payment_billing_unit'] = "出具收据单位：";
$lang['order_receipt_thank'] = "感谢惠顾！欢迎再次光临";

/*账户安全*/
$lang['account_safe'] = '账户安全';

/*主控面板*/
$lang['cumulative_statistics'] = '累积统计';
$lang['direct_push'] = '分店铺数';
$lang['cumulative_dividends'] = '累积分红';
$lang['cumulative_forced_matrix_award'] = '累积见点奖';
$lang['cumulative_sales_commission'] = '累积销售提成';
$lang['announcement'] = '公告';
$lang['recommended_members'] = '推荐的店铺';
$lang['join_time'] = '加盟时间';
$lang['enable_time'] = '激活时间';
$lang['inactive'] = '未激活';
$lang['store_rating'] = '网店等级';
$lang['cur_title'] = '当前职称';
$lang['title_level_0'] = '普通店主';
$lang['title_level_1'] = '资深店主(MSO)';
$lang['title_level_2'] = '市场主管(MSB)';
$lang['title_level_3'] = ' 高级市场主管(SMD)';
$lang['title_level_4'] = '市场总监(EMD)';
$lang['title_level_5'] = '全球市场销售副总裁(GVP)';
$lang['profit_sharing_pool'] = '分红池';
$lang['sharing_point_month_limit'] = '分红点转出超过每月限额。';
$lang['sharing_point_lacking'] = '分红点不足。';
$lang['month_fee_pool'] = '月费池';
$lang['cash_pool_to_month_fee_pool'] = '现金池转月费池';
$lang['month_1'] = '1个月';
$lang['month_3'] = '3个月';
$lang['month_6'] = '6个月';
$lang['month_2'] = '2个月';
$lang['month'] = '月数';
$lang['add_fee'] = '充值';
$lang['no_year'] = '请选择年份';
$lang['no_month'] = '请选择月份';
$lang['transfer_to_other_members'] = '转帐给其他会员';
$lang['transfer_to_cash_sum'] = '转账给其他会员的总额：';
$lang['give'] = '给';
$lang['member'] = '会员';
$lang['member_id'] = '会员ID';
$lang['no_need_tran_to_self'] = '您无需转帐给自己。';
$lang['MEMBER_TRANSFER_MONEY'] = '会员之间转帐';
$lang['tran_to_mem_alert'] = '您将转帐[%s美金]给会员:%s，此次转账金额将归%s所有，意外转账风险将由您自己承担，您确认要转账吗？';
$lang['funds_pwd'] = '资金密码';
$lang['funds_pwd_error'] = '资金密码不正确';
$lang['no_funds_pwd_notice'] = '若您尚未设置资金密码，请至<a href="/ucenter/account_info/index#modifyPIN" class="go_modify_PIN">账户信息</a>栏目进行设置。';
$lang['no_funds_phone_notice'] = '若您尚未验证手机号，请至<a href="/ucenter/account_info/index#phone" class="go_modify_PIN">账户信息</a>栏目进行设置。';
$lang['phone_yzm_tishi'] = '(验证码将发送到%s这台手机)';
$lang['tran_to_mem_china_disabled'] = '转帐给其他会员的功能目前在中国市场关闭。';
$lang['monthly_fee_coupon_note'] = '您还剩余%s张月费抵用券。';
$lang['monthly_fee_coupon_note_limit'] = '请注意：每3个月内只能用1次月费抵用券。';


/*佣金报表*/
$lang['commission_report'] = '佣金报表';
$lang['current_month_comm'] = '当月各项佣金统计:';
$lang['comm_statis_history'] = '历史月份各项佣金统计:';
$lang['funds_change_report'] = '资金变动报表';
//$lang['2x5_force_matrix'] = '2*5 见点佣金';
$lang['2x5_force_matrix'] = '月团队组织分红奖';
$lang['138_force_matrix'] = '138 见点佣金';
$lang['group_sale'] = '团队销售佣金';
$lang['group_sale_infinity'] = '团队总裁奖';
$lang['personal_sale'] = '个人店铺销售提成奖';
$lang['week_profit_sharing'] = '每天全球利润分红';
$lang['daily_bonus_elite'] = '销售精英日分红';
$lang['day_profit_sharing'] = '每天全球利润分红';
$lang['week_leader_matching'] = '周领导对等奖';
$lang['month_leader_profit_sharing'] = '月杰出店铺分红';
$lang['month_middel_leader_profit_sharing'] = '月领导分红奖';
$lang['month_top_leader_profit_sharing'] = '每月领袖分红奖';
$lang['total_stat'] = '总额统计';
$lang['up_tps_level'] = '升级费用';
$lang['today_commission'] = '当日佣金';
$lang['current_month_commission'] = '当月佣金';
$lang['real_time'] = '实时';

/*现金池转月费池*/
$lang['cash_to_month_fee_pool_log'] = '现金池转月费池日志';

/*提现*/
$lang['take_out_cash'] = '提现';
$lang['take_out_cash_type'] = '提现方式';
$lang['bank_card'] = '银行卡';
$lang['bank'] = '银行';
$lang['bank_card_num'] = '银行卡账号';
$lang['payee_name'] = '收款人姓名';
$lang['take_out_max_amount'] = '可提现最大金额';
$lang['take_out_cash_sum'] = '历史提现的总额：';
$lang['take_out_amount'] = '提现金额';
$lang['take_out_pwd'] = '资金密码';
$lang['password_strength'] = '密码强度';
$lang['weak'] = '弱';
$lang['medium'] = '中';
$lang['strong'] = '强';
$lang['take_out_pwd2'] = '必须是8-16位的数字与大小写字母的组合';
$lang['re_take_out_pwd'] = '确认资金密码';
$lang['take_out_cash_notice_1'] = '1）每月我们有15日和30日两次处理提现。如果是在1日—15日之间提出申请的将会在本月的30日处理提现；如果是在16日—31日提出申请将会在下一个月的15日处理提现。
<br>2）每个店主在申请提现前必须上传有效身份证并通过审核。这是为了防止欺诈，为了保护每个会员的个人利益。<br>3）此提示中的日期均为工作日，如遇法定节假日则顺延至下一工作日。';
$lang['take_out_cash_notice_2'] = '每月15号之前申请的提现将在本月底发放';
$lang['take_out_cash_notice_3'] = '每月15号之后申请的提现将在下月15号发放';
$lang['no_take_cash_pwd'] = '第一次设置资金密码，点此设置';
$lang['had_take_cash_pwd'] = '修改资金密码';
$lang['take_cash_pwd_exit'] = '您已经设置了资金密码！';
$lang['take_cash_pwd_not_exit'] = '您还没有设置资金密码！';
$lang['set_take_cash_pwd'] = '设置资金密码';
$lang['set_take_cash_pwd_success'] = '设置资金密码成功。';
$lang['modify_take_cash_pwd_success'] = '修改资金密码成功。';
$lang['modify_take_out_pwd'] = '修改资金密码';
$lang['old_take_out_pwd'] = '原资金密码';
$lang['take_out_success'] = '提现成功';
$lang['pls_sel_take_out_type'] = '请选择提现方式';
$lang['pls_input_correct_amount'] = '请填写正确的提现金额';
$lang['pls_input_correct_amount2'] = '至少提现100美金';
$lang['pls_input_correct_take_out_pwd'] = '资金密码输入错误';
$lang['pls_pwd_retry']='输入错误次数过多，请一小时后重试或重置资金密码';
$lang['not_fill_alipay_account'] = '您还未设置支付宝账户。';

/** 支付宝提现 **/
$lang['withdrawal_fee_'] = '提现手续费';
$lang['withdrawal_actual_'] = '实际到帐金额';
$lang['withdrawal_alipay_'] = '支付宝账号';
$lang['withdrawal_alipay_tip'] = '支付宝限制，单笔提现金额最大不超过：$7000';
$lang['withdrawal_alipay_tip2'] = '支付宝提现手续费，单笔最大不超过：$5';
$lang['confirm_alipay_info'] = '确认支付宝信息，支付宝账号：{0}';

$lang['alipay_actual_name']='支付宝真实姓名';
$lang['alipay_binding']='绑定支付宝';
$lang['alipay_unbundling']='支付宝解绑';
$lang['alipay_binding_accounts']='支付宝帐号';
$lang['alipay_binding_accounts_q']='确认支付宝帐号';
$lang['alipay_binding_vcode']='验证码';
$lang['capital_withdrawals_password']='资金密码';
$lang['alipay_binding_name_prompt']='请输入以%s实名认证的支付宝账号';
$lang['alipay_binding_email']='绑定邮箱支付宝帐号';
$lang['repeat_account'] = '支付宝帐号重复!';
$lang['confirm_account'] = '请再次输入支付宝账号';
$lang['different_account'] = '两次输入的支付宝账号不相同';
$lang['prompt_title']='验证码已直接发送到您所填写的支付宝账号中。';
$lang['for_example']='例如';
$lang['prompt_1']='1、您的支付宝账号是手机号码，请查看短信。';
$lang['prompt_2']='2、您的支付宝账号是邮箱，请登录该邮箱查看。';
$lang['forms_authentication_geshi']='格式不正确！请重新输入';
$lang['forms_authentication_num']='支付宝账号不一致！请重新输入';


/*提现记录*/
$lang['cash_take_out_logs'] = '提现记录';
$lang['cash_take_out_account'] = '提现账户';

/*店铺升級*/
$lang['join_fee'] = '产品套装';
$lang['cur_level'] = '当前店铺等级';
$lang['pls_sel_level'] = '请选择店铺级别';
$lang['no_need_upgrade'] = '无需升级';
$lang['amount_cannot_be_empty'] = '金额不能为空';
$lang['pls_sel_payment'] = '请选择支付方式';
$lang['info_need_complete_for_pay_member'] = '付费店铺需要完善的信息';
$lang['pay_success'] = '支付成功！';
$lang['submit_success'] = '提交成功。';
$lang['pls_complete_info'] = '请先在账户信息栏目补全您的身份证号和身份证复印件。';
$lang['pls_enable_level'] = '请先在账户信息栏目激活您的等级。';
$lang['change_monthly_level'] = '更改月费等级';
$lang['pls_sel_monthly_level'] = '请选择月费等级';
$lang['cannot_change_monthly_fee_level'] = '不能修改月费等级！';
$lang['no_change'] = '信息没有任何修改！';
$lang['month_fee_level_change_note'] = '月费等级将在下个月费日变更为%s。';
$lang['month_fee_level_change_desc'] = '您可以在这里选择更低级别的月费等级，成功提交后，您下个月的月费将按照新的等级来缴纳，届时您的月费等级将更新为修改后的等级，如果修改后的月费等级低于您的店铺等级，店铺等级将会随之降级。';

//账户信息
$lang['input_name_rule'] = '用户名长度必须大于3个字符。';
$lang['input_store_name_rule'] = '店铺名称長度必须大于3个字符和小于36个个字符';
$lang['input_store_name_exit'] = '店铺名称已存在';
$lang['input_store_name_tip'] = '注：店铺名称中文最多12个汉字。';
$lang['input_name_rule_100'] = '用户名长度必須小于100个字符。';
$lang['start_date'] = '开始日期';
$lang['end_date'] = '结束日期';
$lang['input_start_date'] = '请选择开始日期!';
$lang['input_end_date'] = '请选择结束日期!';
$lang['input_date_error'] = '开始日期大于结束日期!';
$lang['input_username'] = '请输入名称!';
$lang['account_success'] = '信息修改成功!';
$lang['account_error'] = '信息修改失败或信息沒有改变!';
$lang['submit'] = '提交';
$lang['email'] = '邮箱';
$lang['profile'] = '个人信息';
$lang['username'] = '用户名';
$lang['ori_password'] = '原始密码';
$lang['new_password'] = '新密码';
$lang['re_password'] = '确认密码';
$lang['country'] = '国家';
$lang['month_upgrade_from']='月费等级从';
$lang['shop_upgrade_from']='店铺等级从';
$lang['upgrade_to']='升级到';
$lang['downgrade_to']='降级到';
$lang['month_upgrade_log_label']='月费等级变动记录';
$lang['shop_upgrade_log_label']='店铺等级变动记录';

$lang['modify_mobile_number'] = '修改手机号码';
$lang['pls_input_new_number'] = '请输入新的手机号码';
$lang['modify_success'] = '修改成功';
$lang['check_card_wait'] = '<div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">身份证审核大约需要2分钟,</div><div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">请您耐心等待!</div>';

$lang['check_exceed_three'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">您的身份证照片经系统审核3次未通过,</div><div style="margin-top:20px; font-size:20px; text-align:center;font-family:微软雅黑;">将转为人工审核!请耐心等候!</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">确定</button></div>';

$lang['check_taiwan_card'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">您的身份证照片将转为人工审核!</div><div style="margin-top:20px; font-size:20px; text-align:center;font-family:微软雅黑;">请耐心等候!</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">确定</button></div>';

$lang['check_passed'] ='<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/correct.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">您的身份证已通过审核!</div><div style="margin-top:20px; text-align:center;"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">确定</button></div>';

$lang['check_failed'] = '<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/error.png"/></div><div style="text-align:center; color:#000;font-size:20px;font-family:微软雅黑;line-height:25px;">您的身份证未通过审核</div><div style="color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;margin-top:25px;box-sizing:border-box;padding-left: 20px;">可能的原因有:</div><ul style="margin:0;list-style:none;color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;padding-left: 20px;" ><li style="line-height: 25px;">( 1 )证件与填写的信息不一致</li><li style="line-height: 25px;">( 2 )身份证照片不清晰</li><li style="line-height: 25px;">( 3 )未满18岁</li></ul><div style="list-style:none;color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;padding-left: 20px;">请您查看原因，并按照规则重新上传照片进行审核!</div><div style="margin-top:20px; text-align:center"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">确定</button></div>';

$lang['check_maintenance'] = '<div style="text-align: center; padding:10px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:16px;font-family:微软雅黑;padding:0 80px;line-height:25px; box-sizing:border-box;">抱歉,系统审核功能维护中,</div><div style="margin-top:20px; font-size:16px; text-align:center; font-family:微软雅黑;">请在2个小时之后再尝试.<div style="margin-top:15px; text-align:center;"><button  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;" onclick="confirm_card()"  type="button">确定</button></div>';
$lang['check_passed_info'] = '您的身份证已经在 <span style="color:red;" >%s</span> 通过了审核.';
$lang['upload_failed'] = '抱歉,上传失败';



//团队销售提成奖励
$lang['current_algebra_title'] = '累积团队提成代数';
$lang['current_rank'] = '(当前店铺等级)';
$lang['QSOs'] = '个(合格店铺)';
$lang['QRCs'] = '个(合格订单)';
$lang['current_algebra'] = '(目前享受团队提成级别)';
$lang['learn_more_rule'] = '了解更多此奖励规则';
$lang['freeze'] = '休眠';
$lang['enjoy_gold'] = '白金团队销售利润';
$lang['enjoy_diamond'] = '钻石团队销售利润';

//总裁奖励
// $lang['infinity_con1'] = '上个月必须是钻石店铺';
// $lang['infinity_con2'] = '累积至少3000个合格铜级（或以上）店铺(至少2组团队:每组团队最多计数1500)';
// $lang['infinity_con3'] = '个人店铺累积30个合格订单';
$lang['infinity_countdown'] = '距离下次奖励还有';
$lang['infinity_enable'] = '能否参加下次奖励？';
$lang['infinity'] = '每月总裁销售奖';
$lang['infinity_title'] = '总裁奖';
$lang['infinity_info'] = '团队销售总裁奖信息';
$lang['infinity_log'] = '总裁奖励日志';
$lang['infinity_date_title'] = '总裁奖的奖励時間';
$lang['infinity_date_content'] = '次月十号';
$lang['infinity_qualifications_title'] = '总裁奖的合格条件';
$lang['infinity_formula_title'] = '总裁奖的奖励算法';
$lang['infinity_formula_content'] = '合格者获得本身团队上個月从第11代开始的销售利润总额×0.5%';
$lang['qualified_time'] = '合格月份';
$lang['grant_time'] = '统计合格时间';
$lang['is_grant'] = '是否已发放';

//上传头像
$lang['user_avatar'] = '用户头像';
$lang['new_user_avatar'] = '新用户头像';
$lang['upload'] = '上传';
$lang['upload_avatar'] = '上传头像';
$lang['reselect'] = '重新选择图片';
$lang['cropped_tip'] = '提示:请选择裁剪区域.';
$lang['upload_tip'] = '你可以上传JPG、GIF或PNG格式的文件，文件大小不能超过<strong>1.0MB</strong>.<br>規定宽和高不能超过<strong>1024*800</strong>.';

//用户升级 购买店铺
$lang['current_level'] = '当前店铺等级';
$lang['member_level'] = '店铺等级';
$lang['opening_time'] = '开通时长';
$lang['payment_method'] = '付款方式';
$lang['amount'] = '应付金额';
$lang['confirm_purchase'] = '确认购买';
$lang['buy_now'] = '马上购买';
$lang['buy_member'] = '购买店铺';
$lang['go_pay'] = '去支付';
$lang['payment_tip'] = '付款提示';
$lang['upgrade_level'] = '店铺升级';
$lang['annual_fee'] = '年管理费';
$lang['monthly_fee'] = '月管理费';
$lang['alipay'] = '付款页面';
$lang['payment_content1'] = "支付完成前，请不要关闭此支付验证窗口。";
$lang['payment_content2']= "支付完成后，请根据您支付的情况点击下面按钮。";
$lang['payment_success']= "支付完成";
$lang['payment_error']= "支付遇到问题";

//奖励制度介绍
$lang['reward_tip'] = '<strong>QSO (合格店铺):</strong><ul><li>铜级（或以上）店铺：<ol><li>店铺已激活</li><li>按时付店铺每月管理费</li></ol></li><li>免费店铺：<ol><li>自己的店铺累积50美元或以上的销售额（不含运费）。</li></ol></li></ul>';
// $lang['reward_tip2'] = '<strong>QRC (合格客户):</strong><ul><li>下了金额$25以上的订单</li><li>非店主自己</li></ul>';
$lang['directly'] = '我的分店铺';
$lang['store_url'] = '沃好商城店铺网址';
$lang['rewards_introduced'] = '奖励制度';
$lang['r1'] = '个人产品销售奖';
$lang['r2'] = '团队销售业绩提成奖';
$lang['r3'] = '团队组织见点奖';
$lang['r4'] = '每周全球利润分红奖';
$lang['r8'] = '每月全球利润分红奖';
$lang['r5'] = '每周领导对等奖';
$lang['r6'] = '每月领导分红奖';
$lang['r7'] = '每月团队销售业绩总裁奖';
$lang['r1_content'] = '条件：<br/>个人店铺是任何级别的店铺。';
$lang['r1_content_notice'] = '奖励：<br/>店主将获得其个人店铺销售利润的20%。';

$lang['r2_content1'] = '<ul><li>[免费店铺]</li></ul>';
$lang['r2_content1_1'] = '条件：<br/>个人店铺是免费店铺；<br/>
奖励：<br/>第一级店铺销售利润提成5%。';
$lang['r2_content_1'] = '<ul><li>[银级店铺]</li></ul>';
$lang['r2_content_2'] = '<ul><li>[白金店铺]</li></ul>';
$lang['r2_content_3'] = '<ul><li>[钻石店铺]</li></ul>';
$lang['r2_content_5'] = '<ul><li>[铜级店铺]</li></ul>';
$lang['r2_content_5_1'] = '条件：<br/>个人店铺是铜级店铺；
<br/>奖励：<br/>第一级店铺销售利润提成10%，第二级店铺销售利润提成5%。';
$lang['r2_content_1_1'] = '条件：<br/>个人店铺是银级店铺；<br/>
奖励：<br/>第一级店铺销售利润提成12%，第二级店铺销售利润提成7%。';
$lang['r2_content_2_1'] = '条件：<br/>个人店铺是白金店铺；<br/>
奖励：<br/>第一级店铺销售利润提成15%，第二级店铺销售利润提成10%。';
$lang['r2_content_3_1'] = '条件：<br/>个人店铺是钻石店铺；<br/>
奖励：<br/>第一级店铺销售利润提成20%，第二级店铺销售利润提成12%。<br/><br/><br/>';

/* 每月团队组织分红奖   */
$lang['r3_content_1'] = '';
$lang['r3_content_2'] = '
条件：<br/>
(1)钻石级合格店铺；<br/>
(2)店主职称是市场主管或以上；<br/>
(3)上个月个人店铺累计了100美金销售额。
<br/>
奖励：奖金将根据会员的现有职称、店铺等级、其个人店铺上个月的零售订单销售额情况来分配奖金。公司每月从全球总销售利润里拿出10%来发放奖金。<br/>
<br/>
发奖日期：每月15号。';

/* 每天全球利润分红奖 */
$lang['r4_content_1'] = '每天全球利润分红奖是为那些每月能给店铺带来销售业绩的店主而设计的。';
$lang['r4_content_2'] = '条件：<br/>
上个月个人铜级（或以上）店铺累积了25美元(免费店主需要100美元)的销售额，下个月获得每天全球销售利润分红奖。<br/><br/>
奖金：<br/> 根据会员的等级，以及该会员消费的普通零售订单金额来计算，会员等级越高，和消费的普通零售订单金额越多，该项奖金将更多。<br/>公司每天从全球总销售利润里拿出10%来发放奖金。
';

$lang['r9_content_1'] = '<ul><li>加入公司矩阵条件：<br/>付月费店主或名字身份通过验证的免费店主。</li></ul>';
$lang['r9_content_2'] = '<ul><li>公司矩阵规则：<br/>将加入到矩阵的店铺从左到右排满138个位置，然后从139个店铺开始，从左到右排列到下一排，以此类推。</li></ul>';
$lang['r9_content_3'] = '<ul><li>条件：<br/>上个月个人店铺累计销售50美金或以上，店铺等级铜级或以上，则下个月一号开始获得138奖金。</li></ul>';
$lang['r9_content_4'] = '<ul><li>奖励：<br/>公司拿出矩阵中所有成员当天销售利润的5%来分给矩阵中满足条件的店主，分配规则依据店主下面有多少个店铺来核算。</li></ul>';
$lang['r9_content_5'] = '<ul><li>奖励算法：<br/>(店主138矩阵下面人数 / 所有参加该分红店主矩阵下面人数总和 * 公司所有成员当天订单销售利润 * 5%) ＋ (公司所有成员当天产品套装销售利润 * 5%／满足该分红奖所有合格人数)。</li></ul>';

/* 每周领导对等奖 */
$lang['r5_content_1'] = '每周领导对等奖是为了奖励那些帮助其新店主快速发展生意的市场领导人而设计的。';
$lang['r5_content_2'] = '条件：<br/>
1）钻石级合格店铺；<br/>
2）店主职称是市场主管或以上；<br/>
3）其个人店铺零售订单上月后台显示必须达到100美金或以上。';
$lang['r5_content_3'] = '
奖励：<br/>满足条件的店主可以在下个月的每周享受该奖励，奖励每周一发放。<br/><br/>
奖励算法：<br/>店主所有的（一级分店店主+二级分店店主）的上周奖金 * 5%。<br/>
';
$lang['r5_content_4'] = '<br/><span class="label label-important">注意：<br/>
1) 该项奖金在每月初审核上个月符合条件的店主，审核通过的店主在本月每周都可享受该项奖金。<br/>
2) 该项奖金制度每月都有店铺的订单金额要求。</span>';

/* 每月杰出店铺分红奖 */
$lang['r6_content_1'] = '每月杰出店铺分红奖是为那些有杰出成就的店铺而设计的。';
$lang['r6_content_2'] = '
<br/>
条件:<br/>
1）银级合格店铺 ；<br/>
2）店主职称必须是资深店主（及以上）；<br/>
3）其个人店铺零售订单上月后台显示必须达到100美金或以上。<br/><br/>
奖励：满足条件的店主可以在下个月15号拿到奖励。<br/>
奖励算法：(公司上月全球销售利润 * 4%／合格总人数)+(该店主分红点 / 参加该分红所有用户的总分红点 * 公司上月全球销售利润 * 6%)。<br/>
';
$lang['r6_content_3'] = '<br/><span class="label label-important">
注意：该项奖金制度每月有店铺的订单金额要求。</span>';

/*  每月总裁销售奖  */
$lang['r7_content_1'] = '每月总裁销售奖是为奖励那些对公司市场发展有重大贡献的全球销售副总裁而设计的。';
$lang['r7_content_2'] = '
条件：<br/>
1）钻石级合格店铺；<br/>
2）店主职称需要是全球销售副总裁；<br/>
3）其团队拥有铜级（及以上）合格店铺数3000及以上，1/2规则在此适用，即每组团队最多只计1500个铜级（及以上）合格店铺，这样做是为了激励领导人建设更具有发展力的团队；<br/>
4）个人店铺上个月达到了250美元的销售额。<br/><br/>
奖励：满足条件的全球销售副总裁将获得其团队第二项奖金制度以外的上月销售利润的0.25%，奖励将在每个月15号发放。
<br/><br/><span class="label label-important">注意：该项奖金制度每月都有店铺的订单金额要求。</span>';

$lang['r8_content_1'] = '条件：<br/>
1）钻石级合格店铺；<br/>
2）店主职称为市场总监；<br/>
3）上个月个人店铺达到了100美元的销售额以及4个及以上订单数。<br/><br/>
奖励：满足条件的市场领袖可以在下个月15号享受该奖励。<br/><br/>
奖励算法：每位合格领袖的奖励＝公司上月全球销售利润 * 1% ／合格领袖的总人数。';
$lang['r8_content_2'] = '<br/><span class="label label-important">注意：该项奖金制度每月都有店铺的订单金额和订单数要求。</span>';

/* 每月领导分红奖 */
$lang['r10_content_1'] = '1)高级市场主管:<br/>
条件：<br/>
a）钻石级合格店铺；<br/>
b）店主职称为高级市场主管;<br/>
c）其个人店铺零售订单上月后台显示必须达到100美金或以上。<br/>
奖励：满足条件的高级市场主管可以在下个月15号享受该奖励。<br/>
奖励算法：每位合格领导人的奖励＝(公司上月全球销售利润 * 3%／合格领导人的总人数)+(该领导分红点 / 参加该分红领导的总分红点 * 公司上月全球销售利润 * 1%) 。<br/><br/>

2)市场总监:<br/>
条件：<br/>
a）钻石级合格店铺；<br/>
b）店主职称为市场总监;<br/>
c）上个月个人店铺达到了200美元的销售额。<br/>
奖励：满足条件的市场总监可以在下个月15号享受该奖励。<br/>
奖励算法：每位合格领导人的奖励＝(公司上月全球销售利润 * 1%／合格领导人的总人数)+(该领导分红点 / 参加该分红领导的总分红点 * 公司上月全球销售利润 * 0.5%)。<br/><br/>

3)全球销售副总裁:<br/>
条件：<br/>
a）钻石级合格店铺；<br/>
b）店主职称为全球销售副总裁;<br/>
c）上个月个人店铺达到了300美元的销售额。<br/>
奖励：满足条件的全球销售副总裁可以在下个月15号享受该奖励。<br/>
奖励算法：每位合格领导人的奖励＝(公司上月全球销售利润 * 0.5%／合格领导人的总人数)+(该领导分红点 / 参加该分红领导的总分红点 * 公司上月全球销售利润 * 0.25%) 。<br/><br/>
';
$lang['r10_content_2'] = '<span class="label label-important">注意：该项奖金制度每月都有店铺的订单金额要求。</span>';

/*销售精英日分红*/
$lang['r11_content']="
条件：<br/>
1）任何级别的合格店主（含免费店主）；<br/>
2）上个月完成一个铜级或以上产品套餐（含升级套装）的销售 或者 其个人店铺上个月零售订单销售额达到250美金或以上。<br/>
<br/>
奖励：<br/>如果店主上个月满足了以上条件，则本月每天都可以享受该分红。<br/>
<br/>
奖励算法：用户上个月的销售额（套装销售额＋零售订单销售额）／ 参加该分红用户的总销售额 * 公司昨天销售利润的10%<br/>
<br/><br/>
<span class='label label-important'>注意：<br/>
1) 该项奖金制度每月都有销售要求（单品或套餐销售）；<br/>
2) 因为降级后又重新升级产生的套装销售额不再重复计算；<br/>
";

/* 新会员专享奖  */
$lang['r12_content_1'] = '
条件：<br/>
新注册成为TPS会员的用户，在升级为铜级（或以上）店铺并且下了50美金（或以上）的零售订单之后，将在合格之后第二天开始享受该项奖金，直到店铺升级日的30天后截止。<br/>
奖金根据会员的等级、以及该会员消费的订单金额来计算，会员等级越高、消费的订单金额越多，该项奖金将更多。<br/>
<br/>
奖励：<br/>单个用户奖金 = 该用户所下的订单总金额（含升级的订单+普通零售订单）／ 享受该奖金的所有用户的订单总金额 * 公司昨天销售利润的2%。<br/>
<br/>';


/*每周团队分红*/
$lang['r_week_share_content'] = "
[条件]<br/>
1) 钻石级合格店铺；<br/>
2) 店主职称是资深店主及以上；<br/>
3) 上个月个人店铺累计了100美金销售额。<br/>
<br/><br/>
[奖励]<br/>
第一次合格的会员，下一个周一享受该奖金。<br/>
[奖励算法]<br/>
奖金根据会员上个月个人店铺零售订单销售额、上个月店主职称、上个月个人店铺等级和分红点来分配奖金。<br/>店主职称越高，个人店铺等级越高，个人店铺零售订单销售额越多，则将会分配到更丰厚的奖金。<br/>公司每周从全球利润中拿出10%进行分配。
";

/*佣金补单*/
$lang['commission_order_repair'] = "佣金补单";
$lang['repair_order_year_month'] = "补单年月";
$lang['commission_type'] = "奖项";
$lang['commission_year_month'] = "奖金年月";
$lang['sale_amount_lack'] = "需补单金额";
$lang['deadline'] = "有效期";
$lang['repair_order'] = "补单";
$lang['order_repairing'] = "补单中...";
$lang['score_year_month'] = "业绩年月";
$lang['comm_date'] = "奖金日期";
$lang['commission_withdraw_amount'] = "应收回金额";
$lang['if_not_repair_order_before_deadline'] = "如果在有效期内未补单";
$lang['order_repair_notice'] = "请注意：<br/>
1、以下列表是您在取消订单后导致某些奖金不满足拿奖要求而产生的，您需要在有效期内补足相应的订单金额，否则相关的奖金将会被系统抽回；<br/>
2、您补单的订单金额将全部算作需要补单的那个月的业绩。
";
$lang['order_repair_step'] = "补单流程：<br/>
1、点击您想要补单的那条记录后面的“补单”按钮，点击后该按钮将变为“补单中”状态；<br/>
2、去商城下订单，当完成的订单金额不小于补单金额时，此时列表中相应的需补单记录将消失，此时补单就完成了。
";
$lang['modifyVal_illegal'] = "有效期不正确";

//职称晋升介绍
$lang['rank_advancement']="职称晋升介绍";

$lang['mso']="a) 资深店主(MSO)： ";
$lang['mso_context']="1）自己的店铺是铜级（或以上）的合格店铺；2）自己成为铜级（或以上）店主后，直接推荐开了3个铜级（或以上）的合格分店铺。";

$lang['sm']="b) 市场主管(MSB)：";
$lang['sm_context']="1）自己的店铺是铜级（或以上）的合格店铺；2）自己成为铜级（或以上）店主后，分店中有3个资深店主，每组至少1个资深店主。";

$lang['sd']="c) 高级市场主管(SMD)：";
$lang['sd_context']="1）自己的店铺是铜级（或以上）的合格店铺；2）自己成为铜级（或以上）店主后，分店中有3个市场主管，每组至少1个市场主管。";

$lang['vp']="d) 市场总监(EMD)：";
$lang['vp_context']="1）自己的店铺是铜级（或以上）的合格店铺；2）自己成为铜级（或以上）店主后，分店中有3个高级市场主管，每组至少1个高级市场主管。";

$lang['gvp']="e) 全球市场销售副总裁(GVP)：";
$lang['gvp_context']="自己的店铺是铜级（或以上）的合格店铺；2）自己成为铜级（或以上）店主后，分店中有3个市场总监，每组至少1个市场总监。";

$lang['finally explanation right']="f) 公司享有最终解释权.";

$lang['back_account'] = '<span style="color: purple"></span> 秒返回到账户栏目...';

$lang['Bulletin_title'] = 'TPS公告';
$lang['upload'] = '上传';
$lang['important'] = '<span style="color:#f00;font-weight:bold;">重要提示：</span>';
$lang['enroll'] = '新人加盟链接';
$lang['evaluate']='商品评价';
$lang['no_collection'] = '目前您没有关注商品';
$lang['try_again'] = '请重试';
$lang['receipt_title_'] = 'TPS商城收据';
$lang['receipt_content_'] = 'TPS商城收据已送达，请查看附件。';
$lang['deliver_content_'] = '您于%s购买的产品订单%s现已通过%s发货完成，运单号是%s。请注意跟踪查收！';
$lang['deliver_title_'] = 'TPS商城订单发货通知';
$lang['order_date'] = '订单日期';
$lang['order_amount_no'] = '订单金额';
$lang['order_amount_no_tip'] = '订单金额不含运费';
$lang['customer_name'] = '顾客名称';
$lang['filter_month'] = '月份';
$lang['cancel_collection'] = '取消关注';

$lang['you_also_not_choose_product']='你还有未选购的商品,请从欢迎页面进入挑选';
$lang['dear_'] = '尊敬的';
$lang['email_end'] = '您可以通过support@shoptps.com随时联系我们的客服.<br>
祝好.<br>
TPS管理团队';

$lang['account_status']='账户状态:';
$lang['fee_num_msg']='<span style="color: #880000;">(累计:fee_num:个月月费未交)</span>';
$lang['fee_num_msg_one']='<span style="color: #880000;">(累计:fee_num:个月月费未交)</span>';
$lang['sale_rank_up_time']='(该职称于<span style="color: #F57403">:sale_rank_up_time:</span>达成)';
$lang['month_fee_arrears']='店铺管理费未交，请缴清后升级。';

$lang['daily_top_performers_sales_pool'] = '销售精英日分红';
$lang['cash_pool_auto_to_month_fee_pool'] = '现金池自动转月费池';
$lang['new_func'] = '新功能提醒';
$lang['auto_to_month_fee_pool_notice'] = "
<p>亲爱的tps会员，为了方便您每月的月费支付。</p>
<p>我们系统新增了一个【现金池自动转月费池】的功能。</p>
<p>您只要设置了此功能，以后每个月当您需要交月费，而月费池中的钱不够时，</p>
<p>系统会自动从您现金池中转所需的月费差额到月费池中，以便交月费。<a href='ucenter/commission#month_fee_auto_to'>点此去设置</a></p>";


/** 客服中心 start **/
$lang['tickets_center'] = '工单中心';
$lang['add_tickets'] = '申请工单';
$lang['my_tickets'] = '我的工单';
$lang['tickets_cus_num']='客服编号';
$lang['tickets_title'] = '工单标题';
$lang['tickets_id'] = '工单号';
$lang['tickets_info'] = '工单详情';
$lang['pls_input_title'] = '请输入标题';
$lang['exceed_words_limit'] = '超过字数限制';
$lang['count_'] = '共';
$lang['remain_'] = '剩余';
$lang['max_limit_'] = '最多';
$lang['_words'] = '字';
$lang['waiting_progress'] = '等待处理';
$lang['in_progress'] = '正在跟进';
$lang['ticket_resolved'] = '已解决';
$lang['had_graded'] = '已评分';
$lang['apply_close'] = '申请关闭';
$lang['tickets_no_exist'] = '抱歉，工单不存在';
$lang['attach_no_exist'] = '抱歉，附件不存在';
$lang['tickets_closed_can_not_reply'] = '工单已关闭，不能回复';
$lang['pls_input_reply_content'] = '请输入回复内容';
$lang['submit_as_waiting_resolve'] = '提交为待解决';
$lang['submit_as_resolved'] = '提交为已解决';
$lang['confirm_submit_tickets_as_resolved'] = '确认提交为已解决吗？提交后工单将处于关闭状态，不能回复！';
$lang['tickets_closed_can_not_reply'] = '工单已关闭，不能回复';
$lang['kindly_remind'] = '温馨提示';
$lang['tickets_type'] = '工单问题类型';
$lang['add_and_quit'] = '加入/退出';
$lang['join_issue'] = '加入问题';
$lang['quit_issue'] = '降级/退出申请';
$lang['up_or_down_grade'] = '升级/支付问题';
$lang['monthly_fee_problem'] = '月费问题';
$lang['platform_fee_problem'] = '平台管理费';
$lang['reward_system'] = '奖励制度';
$lang['product_recommendation'] = '产品推荐';
$lang['shop_transfer'] = '店铺转让';
$lang['commission_problem'] = '佣金问题';
$lang['order_problem'] = '订单问题';
$lang['freight_problem'] = '运费问题';
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

/**申请**/
$lang['pls_t_type'] = '请选择问题分类';
$lang['pls_t_title'] = '请输入工单标题';
$lang['pls_t_content'] = '请输入工单描述';
$lang['pls_t_title_or_id'] = '请输入标题/工单号';
$lang['tickets_save_fail']  = '申请失败';
$lang['tickets_save_success'] = '申请成功';
$lang['tickets_reply_fail'] = '回复失败';
$lang['tickets_reply_success']='回复成功';
$lang['tickets_kindly_notice'] = '尊敬的会员，请注意';
$lang['add_tickets_tip1']='1）同一个问题，您只需要建立一个工单，并在“我的工单”里面查看问题的解决进度，以及与客服进行及时交流。每一个问题从开始到结束都是专一客服为您服务，请留意客服编号。';
$lang['add_tickets_tip2']='2）不同的问题，您需要另外新建一个工单，并耐心等待系统为您分配具体的客服人员为您服务。';
$lang['add_tickets_tip3']='3）请勿对同一个问题建立多个工单，以免浪费您的宝贵时间。谢谢！';
$lang['add_tickets_tip4']='4）客服的工作时间是周一到周五的9：30-12：30和14：00-18：30。客服会尽快处理您的工单，一般情况下需要2-3个工作日，敬请谅解。';
$lang['view_tickets_title_']='跟进日志';
$lang['my_tickets_tip_']='1）您的问题客服会在24小时内跟进处理,如您需要沟通跟进客服，可以致电TPS客服电话(0755-33198568)，告知需转接的客服编号。';
$lang['my_tickets_tip2_']='2）为了确保您的问题能够尽快及时解决，请您电话沟通时直接联络分配跟进该问题的客服。';
$lang['jixu_submit']='提交新的问题';
$lang['jiexie_previous']='接续上个问题';
$lang['tips_tickets_message']='是否接续上个正在跟进中的相同类别的问题，工单号#%s或提出新的问题 ?';
$lang['tickets_email_title'] = '您的工单#%s即将自动关闭';
$lang['tickets_email_content'] = '您的工单#%s即将在%d天后自动关闭，为了确保您的问题已经解决，您可以登录系统进行确认或者致电客服。感谢您的来信！';
$lang['tickets_apply_close_tips'] = '该工单客服已申请关闭，如同意关闭，请点击“提交为已解决”并进行评分；如不同意关闭，请在对话框内继续沟通，点击“提交为待解决”，否则，系统会在12个日历日后自动关闭该工单。';

/**评分**/
$lang['t_pls_t_score'] = '请评分';
$lang['t_score'] = '分';
$lang['t_very_dissatisfied'] = '很不满意';
$lang['t_dissatisfied'] = '不满意';
$lang['t_general'] = '一般';
$lang['t_satisfaction'] = '满意';
$lang['t_very_satisfactory'] = '非常满意';

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
/**客服中心 end **/

//供应商推荐奖
$lang['supplier_recommend_commission'] = '供应商推荐奖';

$lang['total_sale_goods_number']='总销售数量:sale_number:件';

//删除免费店铺
$lang['shop_management']='店铺管理';
$lang['del_shop']='删除店铺';
$lang['is_show_delete']='一旦删除店铺将不可逆转，是否确定删除店铺？';
$lang['is_delete_show']='删除店铺确认';
$lang['del_shop_success']='成功删除店铺，3秒后自动退出';

//手机重置密码
$lang['new_passwd_not_null'] = "新密码不能为空";
$lang['new_passwd_rule'] = "新密码必须是6位数字";
$lang['passwd_not_same'] = "两次输入密码不一致";
$lang['enter_tps_passwd'] = "请输入登陆密码";
$lang['phone_code_not_null'] = "请输入短信验证码";
$lang['phone_code_rule_error'] = "验证码格式错误";
$lang['enter_re_passwd'] = "请再次输入密码";
$lang['please_login_first'] = "请登录";
$lang['update_take_cash_pwd_error'] = "修改失败,请重试";
$lang['system_error_again_code'] = "系统错误,请重新获取短信验证码";
$lang['system_error_again'] = "系统错误,请重试";
$lang['phone_code_expire'] = "短信验证码过期";
$lang['phone_code_error'] = "短信验证码错误";
$lang['phone_reset_passwd_success'] = "密码重置成功";
$lang['email_reset_takecash_passwd'] = "邮箱重置密码";
$lang['phone_reset_takecash_passwd'] = "手机号重置密码";
$lang['new_takecash_passwd'] = "新资金密码";
$lang['new_takecash_passwd_again'] = "再次输入新资金密码";
$lang['phone_code'] = "手机验证码";
$lang['verify_code'] = "验证码";
$lang['get_phone_code'] = "获取短信验证码";
$lang['verify_code_tip1'] = "（验证码将发送到 <span style=\"color:red;\"> ";
$lang['verify_code_tip2'] = "</span> 这个手机上）若您尚未验证手机号码，请至<a href=\"account_info\">账户信息</a>栏目进行验证";
$lang['verify_code_tip3'] = "验证码将发送到 ";
$lang['tps_login_pwd_reset'] = "TPS登陆密码";

$lang['url_not_id_exit'] = '您的前缀不符合规则';
$lang['url_show'] = '前缀修改规则：数字+字母的组合或纯字母或会员自己的ID';

$lang['card_upload_error'] = '上传失败，请稍后再试！';
//支付宝绑定解绑
$lang['please_input_code'] = "请输入验证码";
$lang['please_input_cash_passwd'] = "请输入资金密码";

$lang['card_upload_error'] = '上传失败，请稍后再试！';
$lang['card_upload_error'] = '上传失败，请稍后再试！';
//支付宝绑定解绑
$lang['please_input_code'] = "请输入验证码";
$lang['please_input_cash_passwd'] = "请输入资金密码";

//手机号绑定解绑
$lang['please_input_mobile'] = "请输入手机号码";
$lang['mobile_format_error'] = "手机号码格式有误";
$lang['please_input_code'] = "请输入验证码";
$lang['hacker'] = "黑客";
$lang['binding_mobile_failed'] = "验证手机号失败";
$lang['binding_mobile_success'] = "绑定成功";
$lang['mobile_code_error'] = "验证码错误";
$lang['mobile_code_expire'] = "验证码过期";
$lang['please_verify_old_phone'] = "请先验证原手机号";
$lang['phone_has_been_userd'] = "该手机号已被使用";

$lang['send_code_frequency'] = "操作太频繁，请稍后再试";

$lang['code_has_sent_to'] = "验证码已发送至";
$lang['please_check_out'] = "请查收";
$lang['not_receive_code'] = "未收到验证码?";
$lang['not_receive_reason'] = "可能原因：<br/>1、请检查你的手机号是否填写正确;<br/>2、请检查手机短信是否设置拦截功能;<br/>3、短信可能发放延迟，请等待3-5分钟;";
$lang['mobile_can_not_same'] = "新手机不能和原手机一样";
$lang['bind_success'] = "绑定成功";

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

$lang['bank_take_cash_fee'] = "(银行卡提现手续费，0.5% 单笔最大不超过：$5)";
$lang['not_bind_bank_card'] = "未绑定银行卡";





$lang['send_code_frequency'] = "操作太频繁，请稍后再试";

$lang['code_has_sent_to'] = "验证码已发送至";
$lang['please_check_out'] = "请查收";
$lang['not_receive_code'] = "未收到验证码?";
$lang['not_receive_reason'] = "可能原因：<br/>1、请检查你的手机号是否填写正确;<br/>2、请检查手机短信是否设置拦截功能;<br/>3、短信可能发放延迟，请等待3-5分钟;";
$lang['mobile_can_not_same'] = "新手机不能和原手机一样";
$lang['bind_success'] = "绑定成功";

//收货地址管理 m by brady.wang
$lang['my_addresses'] = "我的收货地址";
$lang['address_not_exists'] = "地址不存在";
$lang['156_address'] = "中国大陆收货地址";
$lang['344_address'] = "中国香港区收货地址";
$lang['840_address'] = "美国区收货地址";
$lang['410_address'] = "韩国区收货地址";
$lang['000_address'] = "其他区收货地址";
$lang['user_region'] = "所在地区";
$lang['user_address_detail'] = "详细地址";
$lang['address_mobile'] = "手机号码";
$lang['address_action'] = "操作";
$lang['spread'] = "展开";
$lang['pack_up'] = "收起";
$lang['address_edit'] = "修改";
$lang['set_success'] = "设置成功";
$lang['set_default_address'] = "设为默认";
$lang['address_limit'] = "(该地址你已创建:number:个地址，最多可创建5个地址)";
$lang['china_land'] = "中国大陆";
$lang['china_hk'] = "中国香港";
$lang['other_region'] = "其他地区";
$lang['address_delete'] = "地址删除";
$lang['you_will_del_address'] = "您将删除该地址！";
$lang['access_deny'] = "无权限";
$lang['modify_address_failed'] = "修改失败";

$lang['address_phone_check'] = "6-20个数字";
$lang['address_phone_check_1'] = "手机号码只能是6-20个数字";
$lang['address_reserve_check'] = "6-20个字符";
$lang['address_reserve_check_1'] = "备用号码只能是6-20个字符";
$lang['address_zip_code_check'] = "小于20个字符";
$lang['address_zip_code_check_1'] = "邮政编码必须小于20个字符";
$lang['mobile_system_update'] = "短信系统维护升级中";

//新版地址验证
$lang['check_addr_rule_phone'] = "请输入正确的手机号码";
$lang['check_addr_rule_reserve_num'] = "请输入正确的备用号码";
$lang['check_addr_rule_zip_code'] = "请输入正确的邮政编码";

//支付宝解绑绑定
$lang['alipay_account_exists'] = "账号已经存在";
$lang['alipay_account_input_again'] = "请再次输入支付宝账号";
$lang['alipay_account_not_same'] = "两次输入不一致";
$lang['alipay_account_not_empty'] = "支付宝账号不能为空";

//重置邮箱
$lang['please_bind_email_first'] = "请先绑定邮箱";
$lang['email_code_not_nul'] = "验证码不能为空";
$lang['email_rule_error'] = '邮箱格式不正确';
$lang['please_get_code'] = '请先获取验证码';
$lang['tps_password_wrong'] = "TPS登陆密码不正确";

//修改手机号
$lang['change_mobile'] = "修改手机号";
$lang['new_mobile_not_null'] = "手机号不能为空";
$lang['re_enter_new_mobile'] = "请再次输入新手机号";
$lang['not_match_your_input'] = "两次输入不一致";
$lang['choose_edit_type'] = "选择修改方式";
$lang['verify_identify'] = "验证方式";
$lang['verify_new_mobile'] = "验证新手机号";
$lang['verify_by_old_phone'] = "通过原手机号修改";
$lang['verify_by_email'] = "通过邮箱修改";
$lang['new_phone_rule_error'] = "手机号格式不正确";
$lang['new_phone'] = "新手机号";
$lang['next_step'] = "下一步";
$lang['new_phone_edit_successed'] = "新手机号修改完成！";
$lang['resend_code_again'] = "重新发送";

//订单地址修改
$lang['order_status_not_allow'] = "订单状态不允许修改地址";
//新用户专属奖金
$lang['new_member_bonus'] = "新会员专享奖";
$lang['supplier_recommendation'] = "供应商推荐奖";
$lang['month_expense'] = "平台管理费";

//分红比例
$lang['system_bonus_ratio'] = '奖金比例设置';
$lang['child_bonus_plan_name'] = '子分类名称';
$lang['bonus_plan_name'] = '分类名称';
$lang['param_a'] = '全球利润百分比';
$lang['agv_b'] = '均分占比';
$lang['param_b'] = '参数二';
$lang['param_c'] = '参数三';
$lang['param_d'] = '参数四';
$lang['param_e'] = '参数五';
$lang['param'] = "参数";
$lang['add_honus_ratio'] = '添加分红比例';
$lang['parent'] = '父级';
$lang['child'] = '子级';
$lang['pls_sel'] = '请选择';
$lang['type_no_empty'] = "类型不能为空";
$lang['param_no_empty'] = "参数不能为空";
$lang['delete'] = "删除";
$lang['edit'] = "修改";
$lang['view'] = "查看";
$lang['add_success'] = '添加成功';
$lang['add_faild'] = '添加失败';
$lang['up_success'] = '更新成功';
$lang['up_faild'] = '更新失败';
$lang['delete_success'] = '添加成功';
$lang['delete_faild'] = '添加失败';
$lang['yz_int_length'] = "参数必须为整数,长度必须为两个字符";
$lang['yesterday_profit_percentage'] = "分红占昨天全球利润的百分比";
$lang['join_bonus'] = "参加分红百分比";
$lang['user_bonus'] = "个人分红百分比";
$lang['total_user_sales_share'] = "分红用户总销售百分比";
$lang['last_month_profit_percentage'] = "上月全球利润百分比";
$lang['last_month_bonus'] = "上月全球利润分红百分比";
$lang['global_gross_profit'] = "全球总利润百分比";
$lang['matrix_bonus'] = "矩阵分红百分比";
$lang['last_week_profit_percentage'] = "上周利润百分比";
$lang['sales_weight_percentage'] = "销售额权重百分比";
$lang['title_weight_percentage'] = "职称权重百分比";
$lang['bonus_percentege'] = "分红点百分比";
$lang['store_level_weight_percentage'] = "会员权重百分比";
$lang['pl_2int'] = "请填两位整数";
$lang['data_extis'] = "此分红计划已存在！";
$lang['new_members_award'] = "新会员专享奖";
$lang['yesterday_sales_share'] = "分红占昨天全球利润的百分比";
$lang['last_week_percentage'] = "上周奖金百分比";
