
<?php
/*paypal提现语言包*/
$lang['paypal'] = 'PayPal';
$lang['paypal_withdraw'] = 'paypal인출';
$lang['confirm_paypal_info'] = ' paypal정보확인，paypal계좌：{0}';
$lang['account'] = '계정';
$lang['account_name'] = '계정명';
/*paypal提现*/
$lang['paypal_prompt1'] = '인출 수수료는 2%이며 $50를 넘을수 없습니다.';
$lang['paypal_email'] = 'paypal이메일주소';
$lang['paypal_email_q'] = 'paypal이메일주소 확인';
$lang['paypal_binding'] = 'paypal관련하기';
$lang['paypal_unbundling'] = 'paypal관련해제';
$lang['prompt_titlesa']='인증번호는 회원님의 사서함으로 전송되였습니다.';
$lang['prompt_2sa']='1、이메일주소를 로그인하여 확인하세요.';
$lang['paypal_tishi']='언 바운드 페이팔';
$lang['where_code']='이 코드는 어디에 있습니까?';
$lang['withdrawal_paypal_tip']='가장 큰 현금 인출 량을 초과하지 않는 : $ 60,000';
$lang['withdrawal_paypal_tip2']='내 보낸 파일의 총 금액은 $ 60,000 초과 할 수 없습니다 철회';
$lang['withdrawal_paypal_tip3']='导出文件的笔数不能超过250笔';
/** 4月份休眠用户活动*/
$lang['april_title'] = '도움말';
$lang['april_email_title'] = 'TPS계정이 휴면상태부터 정상상태로의 관련소식';
$lang['april_email_content'] = '회사는 4월에 유료 오랜 고객들을 위한 할인 서비스가있습니다. 만약 회원님께서 전에 호스팅비 미납이 있으시면 이번달내에 주문매출액이 50달러 누적할수있다면 지금의 계정상태를 정상상태로 회복시킬수 있습니다.
회원님은 이 할인 서비스에 참여하지 않아도 호스팅비버킷이나 캐시버킷에 있는 금액으로 미납의 호스팅비의 50%로 전에 미납의 호스팅비를 납부할수 있습니다.（4월부터 오랜 고객의 호스팅비 50%할인： 실버급：10달러；골드급：20달러；다이아몬드급：30달러）';
$lang['april_content_1'] = '회원님께서 호스팅비를 2개월이상 미납함을 시스템에서 검출 되었습니다. 이에 대하여 회사는 특혜 이벤트를 실시할 예정입니다.：회원님은 당월에 주문판매액을 $50 누적하여 전에 연체된 호스팅비와 서로 상쇄되여 계정을 정상복귀 할수있다.';
$lang['april_content_2'] = 'A) 특혜 이벤트에 응모한다.';
$lang['april_content_3'] = '이 할인 서비스에 참여하지 않지만 캐시버킷에서 차액을 자동으로 호스팅비버킷에다 이체하여 호스팅비납부함을 동의한다.(실버급：10달러；골드급：20달러；다이아몬드급：30달러）';
$lang['april_content_4'] = 'B)  이벤트에 응모하지 않는다.';
$lang['april_content_5'] = '주의：1、이벤트에서 완성한 $50의 주문은 취소 할수없다.2、계정이 정상복귀전 휴면기간에 받지 못한 보너스는 추가 지불하지 않는다. 회원님께서 이번달 내에 어떤 시간 이든 개인점포에서 $50의 주문을 완성하시면 회원님계정의 커미션 지급기능은 바로 정상 복귀된다.';
$lang['queue_order_content'] = 'TPS시스템에서 이미 오더<span class="msg">%s</span>의 PayPal결제통지를 받았습니다. 지금 등록회원수가 너무 많아 회원님의 오더 보너스는 대기행렬순으로 지급됩니다. 인내심을 가지고 기다려 주시기 바랍니다.';

/** 发送注册验证码 */
$lang['email_captcha_title'] = 'TPS인증번호';
$lang['email_captcha_content'] = '귀하의 TPS인증번호는 ： %s입니다. 유효시간은 30분이며 인증을 서둘러 진행하세요.';
$lang['phone_captcha_content'] = '【TPS】귀하의 TPS인증번호는 ： %s입니다. 유효시간은 30분이며 인증을 서둘러 진행하세요.';
$lang['reg_success_account'] = '위의 로그인  링크를 클릭하여 계정을 로그인하시고 자료를 보완하세요.';

$lang['ucenter_loc_sure'] = '지금 구역과 주문의 배송구역과 일치하지 않습니다. 이 작업은 주문의 배송구역으로 전환할수있습니다.전환 하시겠습니까?';

$lang['mobile_code_will_send'] = "（인증번호를 휴대전화로 보내기：<span style color:'red'>:mobile:</span>)";
$lang['mobile_code_has_send'] = " 인증번호를 사용자계정에 연동된 휴대전화번호로 보내였습니다. :mobile:，확인하시기 바랍니다.！";
$lang['mobile_not_verified'] = "사용자의 계정은 아직 휴대전화 본인인증을 진행하지 않았습니다. 인증을 진행해야만  주소수정이 가능합니다.";
$lang['mobile_not_bind'] = "사용자의 계정은 아직 휴대전화 연동을 진행하지 않았습니다. 연동을 진행해야만  주소수정이 가능합니다. <a style='color:blue;font-style:bold;font-size:16px;' href='/ucenter/account_info'>연동하기</a>";


/** 银联预付卡 */
$lang['pre_card_title'] = '”LFG”Global UnionPay선불신용카드';
$lang['pre_card_tip'] = '이 카드는 지금 대한민국、홍콩、마카오의 회원만 신청 가능 합니다.';
$lang['pc_name'] = '이름';
$lang['chinese_name'] = '한자이름';
$lang['pc_card_no'] = '카드번호';
$lang['pc_mobile'] = '핸드폰번호';
$lang['pc_card_no_tip'] = '카드를 수령하지 않았으면 기입하지 않아도 됩니다. ';
$lang['pc_card_no_tip2'] = '”LFG”Global UnionPay카드를 수령한 회원님은 카드번호를 정확히 입력하여 귀속활성화 하도록합니다. ';
$lang['pc_nationality'] = '국적';
$lang['pc_issuing_country'] = '여권 발급 국가';
$lang['pc_address_prove'] = '주소증명서';
$lang['pc_ID_card'] = '신분증 번호';
$lang['pc_ID_card_type_0'] = '주민 등록증';
$lang['pc_ID_card_type_1'] = '여권';
$lang['pc_ID_no'] = '정확한 신분증 번호를 입력하세요.';
$lang['pc_ID_card_upload'] = '신분증명 업로드';
$lang['pc_ID_card_upload_tip'] = '등록증은 앞뒤면 모두, 여권은 사진 있는면 만 업로드';
$lang['pc_ID_front'] = '앞면';
$lang['pc_ID_reverse'] = '뒤면';
$lang['pc_ID_card_ship'] = '카드 받을 주소';
$lang['pc_country'] = '거주 국가';
$lang['pc_ship_to_address'] = '자세한 주소를 입력하세요.';
$lang['pc_submit'] = '개통 신청';
$lang['pc_email'] = 'EMail주소';
$lang['pc_payment_tip'] = '카드 발급과 제조비 $5입니다. 카드 발급 활성화시간은 대략1주~2주시간이 소요합니다. 자료심사가 통과하지 않으면 카드 발급비용이 반환됩니다. ';
$lang['pc_agreement'] = '이미 <span class="yued c-hong">《카드 발급 합의》</span>을 읽었음';
$lang['pc_status_0'] = '미지급';
$lang['pc_status_1'] = '심사 기다리는 중';
$lang['pc_status_2'] = '기각';
$lang['pc_status_3'] = '카드 발급중';
$lang['pc_status_4'] = '이미 보냈다.';
$lang['pc_status_5'] = '심사 하였다.';
$lang['pc_status_pending'] = '카드 발급중';
$lang['pc_apply_tip'] = ' ”LFG”Global UnionPay선불신용카드를 <a href="/ucenter/prepaid_card">여기 클릭</a>하여  신청 발급하기';
$lang['pc_applied'] = '이미 ”LFG”Global UnionPay선불신용카드를 신청 하였습니다.';
$lang['pc_applied_success'] = '자료 제출이 성공하였습니다.심사 기다리는 중..';
$lang['check_prepaid_card'] = '선불신용카드 심사';
$lang['pc_address_prove_tip'] = '거주지 증명（보내는 기관이나 부서는 반드시 정부부서, 수도와 전기의 공급, 은행등 공공기관여야 한다.）';
$lang['prepaid_card_no_exist'] = '존제하지 않은 카드 번호 입니다.';
$lang['assign_card_no'] = '카드번호 분배';
$lang['assign_card_no_error'] = '이 카드번호에 상태에 이상이 있습니다.';
$lang['pc_without'] = 'UnionPay카드가 이미 분배 되였습니다. ';
$lang['pc_agree_t'] = '회원님의 권리를 보장하기 위하여 아래의 내용을 먼저 열독하세요.';



$lang['admin_withdrawal_success_content'] = '회원님이 TPS에 신청한 인출요구는 이미 성공적으로 처리되었습니다. 해당한 인출 계좌를 조회하세요.';
$lang['admin_withdrawal_success_title'] = 'TPS인출 처리 성공 통지';

//提现
$lang['withdorw_list_not_null'] = "아직 완성하지 않은 커미션 추가 주문기록이 있습니다. 이로인하여 인출 작업이 제하됩니다.！";
/*welcome*/
$lang['last_login_info'] = '회원님께서 마지막으로 :time 에  :contry :province :city에서 로그인 하셨습니다.';
$lang['mall_expenditure'] = '쇼핑몰 소비';
$lang['user_is_store'] = '사용자는 이미 점주입니다.';
$lang['mothlyFeeCoupon'] = '호스팅비 쿠폰';
$lang['clickToUse'] = '클릭하여 사용하기';
$lang['return_back'] = '커미션 회수와 돌려주기';
$lang['order_profit_negative'] = '주문 이윤이 $0.01에 이르지 못했다.';
$lang['maxie_mobile'] = 'Maxie Mobile';
$lang['split_order_tip'] = '당신의 주문 중에 상품이 같지 않은 창고나 상가에 소속하기 때문에  주문이 분할하여 따로 배송 할겁니다. 불편한 점 양해 바랍니다.';
$lang['order_0_'] = '이 주문 상품은 판촉품이여서 이윤 배당이 없으나 주문금액은 당신의 점포 매출업적에 누적합니다.';
$lang['upgrade_switch_tip'] = '시스템 점검으로 인하여 지금은 몰 업그레이드 기능이 임시 정지되여있습니다. 불편을 일으킨 점 양해 바랍니다. 감사합니다.';

/*超过3个月未付款的通知邮件*/
$lang['over3MonthNotyfyTitle'] = '호스팅(월관리)비 추가납부 혜택';
$lang['over3MonthNotyfyContent'] = '안녕하세요. 이 이메일을 보낸 이유는 당신의 계정은 이미 3개월 동안 호스팅(월관리)비를 납부 하지 않았음을 알리고자 합니다. 당신의 계정을 정상상태로 회복하여 2X5메트릭스상을 계속 받을수 있게 우리는 당신 계정을 위하여 특별혜택을 드립니다.  즉1개월의 호스팅비만 납부하기만 （3개월의 호스팅비를 납부할 필요가 없다.）하면 당신의 계정을 정상상태로 회복시킬수 있습니다.열독에 시간 내주셔서 감사합니다. 의문 있을시 고객센터에 문의 하시기 바랍니다. 감사합니다.';

/** 提交订单是 地址信息提示 */
$lang['order_address_error_tip'] = '주소 혹은 전화 입력오류로 인해 반품/환불 및 반품/환불은 거절 당할수 있습니다. 왕복 배송비는 공제 됩니다.입력한 주소를 다시 한번 확인해 주시기 바랍니다.';
$lang['edit_address'] = '주소 수정';
$lang['customs_clearing_number'] = '세관 번호';

$lang['read'] = '읽음';
$lang['company_account'] = '회사 계정';
$lang['ok'] = '확인';
$lang['cancel'] = '취소';
$lang['_no'] = '취소';
$lang['add'] = '추가';
$lang['uniqueCard'] = '등록증이 이미 존재하여 있습니다.';
$lang['demote_level'] = '커미션 회수';
$lang['transfer_point'] = '커미션을 배당포인트로 전환';
$lang['transfer_cash'] = '배당포인트를 커미션으로 전환';
$lang['funds_pwd_reset'] = 'PIN번호 리셋';
$lang['yspay'] = '인롄（yspay결제）';
$lang['funds_pwd_tip'] = 'PIN번호는 반드시 8-16자리의 숫자와 대、소 영문자의 조합여야 합니다.';
$lang['forgot_funds_pwd'] = 'PIN번호 찾기?';
$lang['payee_info_incomplete'] = '수취인의 은행카드 정보가 완전하지 않습니다.';
$lang['payee_info'] = '수취인 정보';
$lang['bank_name'] = '계좌 개설 은행명';
$lang['bank_card_number'] = '계좌번호';
$lang['c_bank_card_number'] = '계좌번호 확인';
$lang['card_number_match'] = '계좌번호가 일치하지 않습니다.';
$lang['card_holder_name'] = '계좌 개설인 이름';
$lang['remark'] = '비고';
$lang['remark_content'] = '비고';
$lang['bank_'] = '은행명';
$lang['bank_name_branches'] = '은행 주소';
$lang['subbranch'] = '은행 주소';
$lang['confirm_bank_info'] = '수취인 정보 확인：{0}{1}，계좌번호：{2}，계좌 소유인：{3}';
$lang['confirm_maxie_info'] = 'Maxie Mobile 정보 확인: {0}';
$lang['example1'] = ': 예-KOOK MIN BANK';
$lang['example2'] = ': 주-영어주소';

$lang['withdrawal'] = '인출';
$lang['cancel_withdrawal'] = '인출취소';
$lang['month_fee_date'] = '호스팅(월 관리)비 청구일';
$lang['day_th'] = '일';
$lang['type_tps'] = '수동';
$lang['withdrawal_tip'] = '소수점 아래 두자리까지 보류';
$lang['coupon'] = '쿠폰';
$lang['monthly_fee_coupon_notice'] = '호스팅비 쿠폰을 갖고 계십니다. 쿠폰을 사용하여 해당 레벨의 1개월의 호스팅비를 지불할수 있습니다.';
$lang['no_active_monthly_fee_coupon'] = '이미 사용한 호스팅비 쿠폰 입니다.';
$lang['free_mem_have_no_monthly_fee_coupon'] = '프리회원인 이유로 호스팅비 쿠폰이 없습니다.';
$lang['user_monthli_fee_coupon_success'] = '호스팅비 쿠폰을 성공적으로 사용 하셨습니다.시스템은 이미 회원님의 호스팅비 버킷으로 상응한 비용을 충전 하였습니다.확인 부탁드립니다. ';

$lang['freeze_tip_title'] = '몰 호스팅비 미납 알림';
$lang['freeze_tip_content'] ='<p>존경하신 회원님，</p>
<p>주의:회원님은 %s일—%s 일의 몰 호스팅비 미납이 7일 초과됨으로 인하여 현재의 계정은 보너스 수령이 정지 되였습니다. 빠른 시간내에 호스팅비 버킷으로 연체된 모든 호스팅비를 보충하세요. 감사합니다.</p>
<p>행운을 빕니다.</p>
<p>TPS 관리단체</p>';

$lang['id_card_num_exist'] = '이미 존재한 등록증번호 입니다.';
$lang['complete_info'] = '모든 개인 정보의 정확여부를 확인하세요. 아래의 제출버튼을 클릭한후 개인 자료에 관한 수정이 불가능 합니다. TPS의 심사를 기다리세요.';
$lang['reset_email_tip'] = '주의: 로그인 비밀번호를 입력한후 회원님에게 링크가 있는 메일을 <strong style="color: #ff0000">%s</strong>이메일 주소로 보낼겁니다. ';
$lang['ewallet_email_tip'] = '주의：신청 성공후 전자지갑 정보에 관한 메일 한통을 회원님의 <strong style="color: #ff0000">%s</strong> 이메일 메일 주소로 보낼겁니다. 링크를 클릭하여 전자지갑 계정을 활성화 하세요.';

$lang['month_fee_note'] = '결제 완료후 만약 호스팅비 버킷에 변화가 없으면 당황하지 마시고 몇 분후 새로고침을 진행하세요.';
$lang['payment_note'] = '결제 완료후 만약 레벨에 변화가 없으면 당황하지 마시고 몇 분후 새로고침을 진행하세요.';
$lang['ewallet_success'] = '축하합니다. 전자지갑의 신청이 성공 하였습니다.';
$lang['no_ewallet_name'] = '전자지갑의 아이디를 입력하세요.';
$lang['login_use'] = '전자지갑 로그인 아이디';
$lang['login_email'] = '전자지갑에 관한 모든 메일을 받는다.（활성화，계좌 이체，통지 등）';
$lang['ewallet_name'] = '전자지갑 아이디';
$lang['ewallet_apply'] = '전자지갑 계정 신청';
$lang['ewallet_email'] = '전자지갑 이메일 주소';
$lang['ewallet_before'] = '청구 처리중 입니다...';
$lang['ewallet_after'] = '조작성공,전자지갑으로 이동...';
$lang['ewallet_tip'] = '나의 계정 &rarr; 계정정보으로 나아가서 전자지갑계정을 신청하세요.';

$lang['store_level'] = '몰 레벨';
$lang['alert'] = '제시';
$lang['disclaimer'] = '성명';
$lang['welcome_notice1'] = '회원님은 현제 프리 호스틴비 레벨이므로 2*5메트릭스 보너스와 138메트릭스 월드 일 판매 배당 보너스를 획득할수 없습니다. <br>회원님 팀 하위의 무료 점주께서 회원님보다 빨리 실버급으로 업그레이드할 경우 회원님은 2*5메트릭스에 참여할수 없습니다.<a href="/ucenter/member_upgrade">여기 클릭하여 바로 업그레이드하기 >></a>';
$lang['welcome_notice2'] = '현제 회원님은 프리레벨의 점포임으로 팀 판매 배당과 임의의 배당 보너스를 받을수  없습니다.<a href="/ucenter/member_upgrade">여기를 클릭하여 업그레이드하기 >></a>';
$lang['upgrade_notice'] = '”일괄 집접 업그레이드”를 통해 호스팅비와 몰 레벨을 동시에 업그레이드 할수 있습니다.<a class="go_upall_div" href="Javascript: void(0);">여기 클릭하여 "일괄 집접 업그레이드" 진행하기 >></a>';

$lang['monthly_fee_'] = '차례 1 ：매트릭스에 가입/ 매트릭스 중의 호스팅비 레벨 업그레이드하기';
$lang['cur_monthly_fee_level'] = '호스팅비 레벨 : ';
$lang['product_set'] = '제품세트 구매';
$lang['month_fee_user_rank'] = '호스팅비 레벨이 정확하지 않습니다.';
$lang['month_user_rank'] = '호스팅비 레벨이 정확하지 않습니다.';
$lang['month_fee_rank_empty'] = '먼저 차례1을 완성하세요.';
$lang['upgrade_once_in_all'] = '일괄 집접 업그레이드： [매트릭스에 가입 /매트릭스의 호스팅비 레벨을 업그레이드하기] + [제품세트구매]';
$lang['upgrade_all_level_title'] = '(호스팅비 & 몰) 레벨';

$lang['monthly_fee_level'] = '호스팅비 레벨';
$lang['diamond'] = '다이아몬드급';
$lang['gold'] = '골드급';
$lang['silver'] = '실버급';
$lang['bronze'] = '브론즈급';
$lang['free'] = '프리급';
$lang['realName'] = '실명';
$lang['user_address'] = '주소';
$lang['mobile'] = '핸드폰번호';
$lang['welcome_page'] = '환영 페이지';
$lang['welcome_msg'] = 'TPS에 가입해 주셔서 환영합니다.';
$lang['review_account_info'] = '항목으로 이동';
$lang['review_account_info_2'] = '계정 정보를 확인하시고 상관 자료를 보완하세요.';
$lang['view_complete_your_info'] = '계정 정보 조사/보완하기';
$lang['up_level'] = '업그레이드';
$lang['up_level_notice_2'] = '업그레이드 진행하기';
$lang['order_pay_time'] = '주문지불시간';
$lang['customer_'] = '고객';
$lang['order_amount'] = '주문금액';
$lang['individual_store_sales_commission'] = '몰 개인 판매 커미션';
$lang['order_id'] = '주문번호';
$lang['commission'] = '커미션';
$lang['accumulation_commission'] = '누적 커미션 금액';
$lang['commission_log'] = '커미션 기록';
$lang['my_rank'] = '나의 직함';
$lang['profit_sharing_info'] = '배당정보';
$lang['profit_sharing_time'] = '배당시간';
$lang['profit_sharing_require'] = '배당조건';
$lang['profit_sharing_formula'] = '배당계산법';
$lang['profit_sharing_time_content'] = '매주 월요일의 0시';
$lang['profit_sharing_time_content_month'] = '매월 8일0시';
$lang['profit_sharing_require_content'] = '실버 레벨이상의 점포여야 합니다.';
$lang['profit_sharing_require_content2'] = '자신의 쇼핑몰이 지난 주에 최소한 $35이상의 이미 결제완료 주문이 한건 있어야 합니다.';
$lang['profit_sharing_require_content3'] = '자신의 쇼핑몰이 지난 달에 최소한 10건 이상의 이미 결제완료 주문있어야 하고 총금액은 $350이상여야 합니다.';
$lang['profit_sharing_formula_content'] = '자신의 배당포인트 / 회사 총 배당포인트 * 회사 전주 이익의 4%';
$lang['profit_sharing_formula_content_month'] = '자신의 배당포인트 / 회사 총 배당포인트 * 회사 전주 이익의 10%';
$lang['profit_sharing_countdown'] = '다음번 배당까지 아직';
$lang['profit_sharing_enable'] = '다음번 배당에 참여 할수있나요?';
$lang['yes'] = '있다';
$lang['no'] = '없다';
$lang['profit_sharing_point_to_money'] = '배당포인트에세 캐시버킷으로 이체';
$lang['profit_sharing_point_to_money_log'] = '배당포인트에세 캐시버킷로의 이체기록';
$lang['no_condition_1'] = '회원님은 실버급이상의 회원이 아닙니다.';
$lang['no_condition_2'] = '회원님 쇼핑몰의 이번주 주문금액은 $35에 도달하지 못했습니다.';
$lang['no_condition_3'] = '회원님 쇼핑몰의 이번달 주문금액은 $350에 도달하지 못했습니다. ';
$lang['no_condition_4'] = '회원님 쇼핑몰의 이번달 주문수가 10개에 도달하지 못했습니다.';
$lang['sharing_point'] = '배당포인트';
$lang['bonus_point'] = '(나의 매일 획득한 배당포인트)';
$lang['bonus_point_note'] = '주의：월 첫날 총 배당포인트를 근거로하여 매달 최대30%를 캐시 버킷으로 전입할수 있습니다.';
$lang['first_month_day'] = '(당월의 첫날)';
$lang['total_point'] = '총';
$lang['sharing_point_enable_exchange'] = '이체 가능한 배당포인트';
$lang['point'] = '포인트';
$lang['reward_sharing_point'] = '보너스 배당포인트';
$lang['commissions_to_sharing_point_auto'] = '커미션을 배당포인트로 자동 이체';
$lang['sale_commissions_sharing_point'] = '판매 커미션을 배당포인트로 자동 이체';
$lang['forced_matrix_sharing_point'] = '매트릭스 커미션을 배당포인트로 자동 이체';
$lang['validity'] = '유효기간';
$lang['profit_sharing_sharing_point'] = '배당을 배당포인트로 자동 이체';
$lang['manually_sharing_point'] = '캐시버킷에서 배당포인트로 이체';
$lang['sharing_point_to_money'] = '배당포인트에서 캐시버킷로 이체';
$lang['proportion'] = '비율';
$lang['cur_commission_lack'] = '현제 현금 금액이 부족합니다.';
$lang['cur_sharing_point_lack'] = '현재 배당포인트가 부족합니다.';
$lang['positive_num_error'] = '0보다 큰 수치를 입력해주세요.(완수가 아닌경우 소수점후 두자리까지보류해 주세요.)';
$lang['save'] = '저장';
$lang['save_success'] = '저장성공!';
$lang['shift_success'] = '이체성공!';
$lang['profit_sharing_point_log'] = '배당포인트 전입기록';
$lang['pls_sel_profit_sharing_adden_type'] = '배당 전입유형을 선택하세요.';
$lang['current_commission'] = '캐시 버킷';
$lang['move'] = '이체';
$lang['to'] = '으로';
$lang['save_false'] = '보존실패!';
$lang['level_not_enable'] = '쇼핑몰 활성화하지 않았습니다.';
$lang['month_fee_fail_notice'] = '쇼핑몰 호스팅비 미납알림';
$lang['month_fee_fail_content'] = '
 회원님께서는 %s일 - %s일까지의  쇼핑몰 호스팅비를 납부하지 않았음을 알려 드립니다.  %s내로 호스팅비 버킷으로 충전하고 쇼핌몰 호스팅비를 납부하여 회원님의 계정상태 정상화를 보장시켜 시스템에서 제때에 회원님께 각종 커미션과 보너스를  보낼수 있도록 하십시오. 회원님께서는 <a target="_blank" href="%s">여기</a>를 클릭하여 비용을 납부할수 있습니다. 배려에 감사드립니다.';
$lang['month_fee_fail_content_90'] = '
%s—%s의 쇼핑몰 호스팅비 미납이 7일 넘음으로 회원님의 계정상태가 휴면(일시정지)에 처해 있어 보너스를 잠시 받을수 없는 점에 유의해 주시기 바랍니다. 빠른 시일내로 미납한 호스팅비를 호스팅비 버킷으로 충전하여 계정상태를 정상으로 회복하시기 바랍니다.회원님께서는 <a target="_blank" href="%s">여기</a>를 클릭하여 납부 할수 있습니다. 배려에 감사드립니다.';
$lang['24hours'] = '24시간';
$lang['7day'] = '7일';

/*我的信息*/
$lang['my_msg'] = '나의 메세지';

/*我的代品券*/
$lang['exchangeCoupon'] = '바우처';
$lang['suitExchangeCouponRule'] = '바우처 규정설명';
$lang['suitExchangeCouponRuleContent'] = '바우처 규정설명 내용';
$lang['only_use_in_exchange'] = '구매 제한구역의 상품만 선택구매 가능';
$lang['num'] = '수량';
$lang['expiration'] = '만료기한';
$lang['unlimited'] = '무제한';
$lang['goto_use'] = '사용하기';
$lang['no_exchange_coupons'] = '회원님은 아직 바우처가 없습니다.';
$lang['coupons_total_num']='보유한 바우처:total_num:장';
$lang['value']='가치';

/*关于代品券*/
$lang['about_exchange_coupon'] = '바우처에 관하여';
$lang['exchange_coupon_1_title'] = '一、바우처란 무엇인가?';
$lang['exchange_coupon_1_content'] = '바우처는 회원들의 만족도를 업필하기 위하여 상가에서 진행하는 세트상품과 세트단품을 교환하는 교환쿠폰 입니다. 바우처의 액면금액은 각각 $100 / $50 / $20 / $10 / $1 총 5종이 있습니다. 바우처는 회원본인이 TPS쇼핑몰에서만 사용가능 하며 양도나 현금인출을 금지 되여있습니다. 쇼핑몰 업그레이드 제품을 선택시 나오는 제품세트 특별 판매구역에서 세트과 세트 단품을 교환하는데만 사용가능 합니다.';
$lang['exchange_coupon_2_title'] = '二、바우처의 사용규정';
$lang['exchange_coupon_2_content'] = '1. 바우처는 제품세트 특별 판매구역에서 세트과 세트 단품을 교환하는데만 사용가능 합니다.；<br />
                2. 바우처는 회원본인이 TPS쇼핑몰에서만 사용가능 하며 양도나 현금인출을 금지 되여있고 TPS몰 호스팅비와 운송비를 대신 납부하는데 사용할수 없습니다.；<br />
                3. 반품/환불시 바우처는 회원님 계정으로 돌려드립니다.；<br />
                4. 심천QianHai YunJiPin전자 상거래 유한책임회사는 이 바우처에 대한 최종 해석권을 갇고있습니다.';
$lang['exchange_coupon_3_title'] = '三、바우처 발급방식 설명';
$lang['exchange_coupon_3_content_1'] = '1. TPS138회원 백 오피스에서 몰레벨 업그레이드를 진행시 회원님이 부분의 제품세트및 세트단품만 선택하면 남은 금액은 바우처로 선택할수 있고 나중에 제품세트 특별 판매구역에서 자기가 좋아하는 제품세트및 세트단품을 교환할수 있습니다. ';
$lang['exchange_coupon_3_content_2'] = '2. 몰레벨 업그레이드를 진행시 바우처를 선택한 회원님은 <span style="color:#23a1d1;">“나의계정—>나의 바우처”</span>에서 확인할수 있습니다.';
$lang['exchange_coupon_4_title'] = '四、바우처에 관한 질문';
$lang['exchange_coupon_4_content'] = '
(1) 바우처금액은 현금인출 가능한가요?<br />
  답：불가능합니다.<br />
(2) 바우처를 사용한 주문이 반품할경우 환불은 어떻게 진행되나요? 바우처는 돌려주나요?<br />
  답：바우처를 사용하여 제품세트 특별 판매구역에서 교환한 주문은 반품시 실제결제금액으로 환불시키며 이미 사용한 바우처는 회원님계정으로 돌려드립니다.<br />
(3) 바우처로 TPS몰 호스팅비、운송비등을 납부할수있나요?<br />
  답：모두 불가능합니다.<br />
(4) 使用바우처를 사용하여 제품세트및 세트단품을 교환구매할시 그중 바우처의 금액은 영수증을 뗄수있나요?<br />
  답：뗄수 없습니다. 바우처를 사용한 금액은 이미 몰 업그레이드때의 주문에서 영수증을 떼여 주었으므로 중복발급이 불가능 함으로 주문의 실제지불 금액부분만 영수증을 떼여 드릴수 있습니다.';

/*账户信息*/
$lang['member_url'] = ' TPS쇼핑몰 점포 웹사이트 주소';
$lang['member_name'] = ' TPS쇼핑몰 점포 명칭';
$lang['modify_member_url'] = '웹사이트 주소 수정';
$lang['member_url_prefix_format_error'] = '웹사이트 주소의 접두문자는 4-15자리의 수자、영문자모만 사용가능합니다.';
$lang['url_can_not_be_other_id'] = '기타 회원님의 아이디로 회원님의 웹사이트 접두문자로 사용할수 없습니다.';
$lang['modify'] = '수정';
$lang['modify_store_url'] = '점포 웹사이트 주소 수정';
$lang['modify_store_url_notice'] = '회원님은 아직 <span id="storeModifyleftCounts">%s</span>번 수정 기회가 남아있습니다.';
$lang['modify_member_url_notice'] = '회원님은 아직 <span id="memberModifyleftCounts">%s</span>번 수정기회가 남아있습니다.';
$lang['modify_store_url_count_end'] = '점포 웹사이트 주소 수정 기회가 이미다 사용 하였습니다.';
$lang['modify_member_url_count_end'] = '회원 개인웹사이트 주소 수정 기회가 이미다 사용 하였습니다.';
$lang['store_url_prefix_format_error'] = '웹사이트 주소의 접두문자는 4-15자리의 수자、영문자모만 사용가능합니다.';
$lang['store_url_exist'] = '이미 존재하는 점포 웹사이트 주소 입니다.';
$lang['url_exist'] = '이미 존재하는 웹사이트 주소 입니다.';
$lang['modify_store_url_success'] = '점포 웹사이트 주소 수정이 성공하였습니다.';
$lang['id_card_scan_type_ext_error'] = '등록증 스캔본 포맷이 정확하지 않습니다.';
$lang['id_card_scan_too_large'] = '등록증 스캔본 크기는 10Mb를 초과할수 없습니다.';
$lang['id_scan_condition'] = '파일크기는 10Mb넘지 않고 포맷은 jpg,gif,bmp,jpeg,png 여야 합니다.';
$lang['pls_complete_auth_info'] = '이 메세지를 보이시면  회원님의 신분검증이 TPS심사를 통과하지 못하였음을 표시 합니다.';
$lang['enable'] = '활성화';
$lang['sensitive'] = '사용자 이름에 금지된 단어가 존재합니다. ';
$lang['have_black_word'] = '금지된 단어가 존재합니다.';
$lang['enable_cur_level'] = '쇼핑몰 점포 활성화기';
$lang['id_card_scan_ok'] = '이미 신분증 스캔본을 업로드 하였습니다.';
$lang['not_uploaded'] = '업로드하지 않았습니다.';
$lang['person_id_card_num_exitst'] = '등록증 번호를 이미 제출한적이 있습니다.';
$lang['terms_and_agreement'] = '협의조항';
$lang['terms_1'] = '저는 톰프슨파트너유한회사에 가맹 하는것을 동의하며 자본투자와 주식투자가 아닙니다. 저는 일시 지불한 비용은 저에게 적합한 국외 브랜드 제품세트를 구매하기 위해서임을 명확히 이해합니다. 아무런 방법,수단,제품,경험이 없어도 가입할수 있는 크로스 보더 전가 상거래와 마케팅 플랫폼이며 회사에서 점포(몰)에게 쇼핑몰에서 재공하는 명품구매할인과 상업기회가 창조 해낸 재무수익을 향유합니다.';
$lang['terms_2'] = '<p>개인 몰에 일반 소비자와 고객이 존재하지 않을경우 TPS에서는 그어떤 보너스도 발급하지 않습니다. 만약 처음부터 실버급 이상의 제품세트, 몰 호스팅비 혹은 일회용 쇼핑타운 가맹비 및 제품세트비용을 구매할 경우 3일동안 무료 몰을 체험할수 있습니다. 제4 공작일부터 TPS에서는 환불을 동의하지 않습니다. </p>';

/*收货地址*/
$lang['shipping_addr'] = '받을 주소';

/*订单中心*/
$lang['order_center'] = '주문 센터';

/*我的订单*/
$lang['my_orders'] = '나의 자아 소비 주문';
$lang['my_tps_orders'] = '나의 몰 고객 주문';
$lang['my_one_direct_orders'] = '나의 1Direct.us주문';
$lang['my_walhao_store_orders'] = '나의Walhao주문';
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
$lang['my_affiliate'] = '나의Affiliate주문';
$lang['order_confirm_time'] = '주문 확인 시간';
$lang['order_pay_date'] = '주문지불시간';
$lang['order_update_date'] = '订单更新时间';
$lang['effective_performance'] = '유효실적';

/* 收据 */
$lang['order_receipt_font'] = "pmingliu";
$lang['order_receipt_company'] = "QianHai YunJiPin";
$lang['order_receipt_company_address'] = "수소：";
$lang['order_receipt_company_address_detail'] = "21/F, Easy Tower,  No.609 Tai Nan West Street, Cheung Sha Wan, Kowloon, Hong Kong";
$lang['order_receipt_company_phone'] = "전화번호：";
$lang['order_receipt_company_phone_detail'] = "(852)2690-0193";
$lang['order_receipt_company_fax'] = "fax：";
$lang['order_receipt_company_fax_detail'] = "(852)3706-2329";
$lang['order_receipt_company_email'] = "이메일：";
$lang['order_receipt_company_email_detail'] = "support@tps138.com";
$lang['order_receipt_purchase_date'] = "구매날자：";
$lang['order_receipt_member_id'] = "회원 ID ：";
$lang['order_receipt_store_level'] = "몰 레벨：";
$lang['order_receipt_user_phone'] = "전화번호：";
$lang['order_receipt_receiving_address'] = "받을 주소：";
$lang['order_receipt_title'] = "영수증";
$lang['order_receipt_order_number'] = "주문번호：";
$lang['order_receipt_detail_product'] = "상품묘사";
$lang['order_receipt_detail_price'] = "상품가격<span>（달러）</span>";
$lang['order_receipt_detail_qty'] = "수량";
$lang['order_receipt_detail_amount'] = "합계<span>（달러）</span>";
$lang['order_receipt_coupons'] = "TPS바우처";
$lang['order_receipt_product_amount'] = "상품합계：";
$lang['order_receipt_coupons_amount'] = "바우처：";
$lang['order_receipt_freight'] = "운송비：";
$lang['order_receipt_actual_payment'] = "실제 지불금액：";
$lang['order_receipt_payment_terms'] = "결제방법：";
$lang['order_receipt_commitment'] = "모든 상품은 3일내 무조건 환불가능을 약속합니다.";
$lang['order_receipt_payment_billing_unit'] = "영수증 발급기관：";
$lang['order_receipt_thank'] = "방문해 주셔서 감사합니다.";

/*账户安全*/
$lang['account_safe'] = '계정안보';

/*主控面板*/
$lang['cumulative_statistics'] = '통계누적';
$lang['direct_push'] = '지점 수';
$lang['cumulative_dividends'] = '배당누적';
$lang['cumulative_forced_matrix_award'] = '매트릭스누적';
$lang['cumulative_sales_commission'] = '판매공제누적';
$lang['announcement'] = '공지사항';
$lang['recommended_members'] = '추천 점포';
$lang['join_time'] = '가입 시간';
$lang['enable_time'] = '활성화 시간';
$lang['inactive'] = '활성화하지 않았습니다.';
$lang['store_rating'] = '몰 레벨';
$lang['cur_title'] = '지금 직함';
$lang['title_level_0'] = '보통점주';
$lang['title_level_1'] = '베테랑 점주(MSO)';
$lang['title_level_2'] = '마케팅 주관자(MSB)';
$lang['title_level_3'] = '고급 마케팅 주관자(SMD)';
$lang['title_level_4'] = '마케팅 총감(EMD)';
$lang['title_level_5'] = '글로벌 마케팅 부총재(GVP)';
$lang['profit_sharing_pool'] = '배당 버킷';
$lang['sharing_point_month_limit'] = '배당포인트 전의가 월 한도를 넘었습니다. ';
$lang['sharing_point_lacking'] = '배당포인트가 부족합니다.';
$lang['month_fee_pool'] = '호스팅비 버킷';
$lang['cash_pool_to_month_fee_pool'] = '캐시 버킷에서 호스팅비 버킷으로 이체';
$lang['month_1'] = '1개월';
$lang['month_3'] = '3개월';
$lang['month_6'] = '6개월';
$lang['month_2'] = '2개월';
$lang['month'] = '월수';
$lang['add_fee'] = '충전';
$lang['no_year'] = '년 선택';
$lang['no_month'] = '월수를 선택하세요.';
$lang['transfer_to_other_members'] = '기타 회원에게 이체하기';
$lang['transfer_to_cash_sum'] = '기타 회원에게 이체한 총액：';
$lang['give'] = 'give';
$lang['member'] = '회원';
$lang['member_id'] = '회원ID';
$lang['no_need_tran_to_self'] = '회원님은 자신에게 이체할수 없습니다.';
$lang['MEMBER_TRANSFER_MONEY'] = '회원지간에 이체';
$lang['tran_to_mem_alert'] = '당신은 [%s달러]를 회원:%s에게 이체하려 합니다. 이번 이체의 금액은 %s가 소유 할수 있으므로 뜻밖의 이체위험의 감당은 회원님이 스스로 책임져야 합니다. 이체를 계속 진행하시겠습니까?';
$lang['funds_pwd'] = 'PIN번호';
$lang['funds_pwd_error'] = 'PIN번호가 정확하지 않는다.';
$lang['no_funds_pwd_notice'] = 'PIN번호를 설치하지 않았을 경우<a href="/ucenter/account_info/index#modifyPIN" class="go_modify_PIN">계정정보</a>에서 설정하세요. ';
$lang['tran_to_mem_china_disabled'] = '기타 회원에게 이체하는 기능은 현제 중국시장 구역에서 상용가능하지 않습니다.';
$lang['monthly_fee_coupon_note'] = '아직 %s장 호스팅비 쿠폰이 남아 있습니다.';
$lang['monthly_fee_coupon_note_limit'] = '주의: 3개월 내에는 호스팅비 쿠폰을 1번만 사용가능 합니다.';

/*佣金报表*/
$lang['commission_report'] = '커미션 보고표';
$lang['funds_change_report'] = '자금변동 보고표';
$lang['current_month_comm'] = '당월 각종 커미션 통계:';
$lang['comm_statis_history'] = '월 역사 각종 커미션 통계:';
//$lang['2x5_force_matrix'] = '2*5 매트릭스 커미션';
$lang['2x5_force_matrix'] = '매월 팀조직 배당 보너스';
$lang['138_force_matrix'] = '138배당 보너스';
$lang['group_sale'] = '팀 판매 배당';
$lang['group_sale_infinity'] = '매월 총재 매출보너스';
$lang['personal_sale'] = '개인 판매 배당';
$lang['week_profit_sharing'] = '매일 배당';
//$lang['daily_bonus_elite'] = '매출  엘리트 일배당';
$lang['day_profit_sharing'] = '매일 배당';
$lang['week_leader_matching'] = '주간 리더십 보너스';
$lang['month_leader_profit_sharing'] = '월 우수 몰 배당 보너스';
$lang['month_middel_leader_profit_sharing'] = '매달 팀 판매 리더십 보너스';
$lang['month_top_leader_profit_sharing'] = '매월 탑 리더 배당상';
$lang['total_stat'] = '총액 통계';
$lang['up_tps_level'] = '업그레이드 비용';
$lang['today_commission'] = '당일 커미션';
$lang['current_month_commission'] = '당월 커미션';
$lang['real_time'] = '실시간';

/*现金池转月费池*/
$lang['cash_to_month_fee_pool_log'] = '캐시 버킷에서 호스팅비 버킷으로';

/*提现*/
$lang['take_out_cash'] = '현금인출';
$lang['take_out_cash_type'] = '현금인출방법';
$lang['bank_card'] = '은행카드';
$lang['bank'] = '은행';
$lang['bank_card_num'] = '은행카드 계좌번호';
$lang['payee_name'] = '수취인 이름';
$lang['take_out_max_amount'] = '인출가능한 최대 금액';
$lang['take_out_cash_sum'] = '역사 인출의 총액：';
$lang['take_out_amount'] = '인출금액';
$lang['take_out_pwd'] = 'PIN번호';
//$lang['take_out_pwd2'] = '반드시 6자리 수자야 함';
$lang['password_strength'] = '비밀 번호 안전성';
$lang['weak'] = '약';
$lang['medium'] = '중';
$lang['strong'] = '강';
$lang['take_out_pwd2'] = '반드시 8-16자리의 숫자와 대、소 영문자의 조합여야 합니다';
$lang['re_take_out_pwd'] = 'PIN번호 확인';
$lang['take_out_cash_notice_1'] = '1）매달 우리는15일과 30일 두번의 현금인출 처리가있습니다. 만약 1일—15일사이에 신청을 제출하였으면 그달의 30일에 현금인출 처리가 진행되고 16일—31일에 신청을 제출하면 그 다음 달 15일에 현금인출 처리가 진행됩니다.
<br>2）점주는 현금인출 신청하기전 반드시 유효한 등록증을 업로드하여 심사과정을 맞혀야한다. 이는 사기를 예방하여 회원의 개인이익을  보장하기 위해 취한 조치입니다.<br>3）이 제시중 나오는 날자는 모두 근무일을 말하는 것이며 법정 공휴일일시 잡업은 다음 근무일로 넘어가 진행합니다.';
$lang['take_out_cash_notice_2'] = '매달 15일전으로 신청하신 현금인출은 해당 월말에 발급해 드립니다. ';
$lang['take_out_cash_notice_3'] = '매달 15일후로 신청하신 현금인출은 그 다음 달 15일에 발급해 드립니다. ';
$lang['no_take_cash_pwd'] = '여기를 클릭하여 처음으로 PIN번호를 설정하기';
$lang['had_take_cash_pwd'] = 'PIN번호 수정';
$lang['take_cash_pwd_exit'] = '이미 PIN번호를 설치 하였습니다.';
$lang['take_cash_pwd_not_exit'] = '아직 PIN번호를 설치하지 않았습니다.';
$lang['set_take_cash_pwd'] = 'PIN번호 설치';
$lang['set_take_cash_pwd_success'] = 'PIN번호 설치 성공';
$lang['modify_take_cash_pwd_success'] = 'PIN번호 수정 성공';
$lang['modify_take_out_pwd'] = 'PIN번호 수정';
$lang['old_take_out_pwd'] = '원 PIN번호';
$lang['take_out_success'] = '인출성공';
$lang['pls_sel_take_out_type'] = '인출방식을 선택하세요.';
$lang['pls_input_correct_amount'] = '정확한 인출금액을 입력하세요.';
$lang['pls_input_correct_amount2'] = '최소 100달러 인출해야 합니다.';
$lang['pls_input_correct_take_out_pwd'] = '비밀번호 입력 오류가 자금';
$lang['pls_pwd_retry']='오류의 과도한 번호를 입력 다시 시도하거나 1 시간 암호 후 자금을 다시 설정하시기 바랍니다';
$lang['not_fill_alipay_account'] = 'alipay계정을 설치하지 않았습니다.';

$lang['withdrawal_fee_'] = '현금인출 수수료';
$lang['withdrawal_actual_'] = '실제 받을 이체금액';
$lang['withdrawal_alipay_'] = 'alipay계정';
$lang['withdrawal_alipay_tip'] = 'alipay 제한으로 인하여 건마다 인출가능한 최대 금액은 $7000를 넘을수 없습니다.';
$lang['withdrawal_alipay_tip2'] = 'alipay 현금인출의 수수료는 건마다 최대 $5를 넘을수 없습니다.';
$lang['binding_alipay'] = 'alipay 연동하기';
$lang['confirm_alipay_info'] = 'alipay 정보를 확인하세요. alipay계정：{0}';

$lang['alipay_actual_name']='alipay 실제이름';
$lang['alipay_binding']='alipay 연동하기';
$lang['alipay_unbundling']='alipay 연동해제';
$lang['alipay_binding_accounts']='alipay계정';
$lang['alipay_binding_accounts_q']='alipay계정 확인';
$lang['alipay_binding_vcode']='인증번호';
$lang['capital_withdrawals_password']='PIN번호';
$lang['alipay_binding_name_prompt']='%s로 실명 인증한 alipay계정을 입력하세요.';
$lang['alipay_binding_email']='사서함 Alipay의 계정을 결합';
$lang['repeat_account'] = '알리페이 계정 중복!';
$lang['confirm_account'] = '알리페이 계정을 다시 입력하세요';
$lang['different_account'] = '두번입력하신 알리페이 계정이 일치하지 않습니다';
$lang['prompt_title']='인증번호는 회원님의 alipay계정으로 전송되였습니다.';
$lang['for_example']='예를 들어';
$lang['prompt_1']='1、회원님의 alipay계정이 휴대전화번호인 경우, 휴대전화의 수신 메세지를 확인하세요.';
$lang['prompt_2']='2、회원님의 alipay계정이 이메일주소인 경우, 이메일주소를 로그인하여 확인하세요.';
$lang['forms_authentication_geshi']='규격에 맡지 않습니다. 다시 입력하세요.';
$lang['forms_authentication_num']='두번 입력한 정보가 일치하지 않습니다. 다시 입력하세요.';

/*提现记录*/
$lang['cash_take_out_logs'] = '인출기록';
$lang['cash_take_out_account'] = '인출계정';

/*店铺升級*/
$lang['join_fee'] = '제품세트';
$lang['cur_level'] = '지금 몰 레벨';
$lang['pls_sel_level'] = '몰 레벨을 선택하세요.';
$lang['no_need_upgrade'] = '업그레이드할 필요가 없습니다.';
$lang['amount_cannot_be_empty'] = '금액을 비우시면 안됩니다.';
$lang['pls_sel_payment'] = '지불방식을 선택하세요.';
$lang['info_need_complete_for_pay_member'] = '유료몰이 보완해야할 정보';
$lang['pay_success'] = '지불성공';
$lang['submit_success'] = '제출성공';
$lang['pls_complete_info'] = '먼저 계정정보 항목에서 등록증번호와 등록증 복사본을 보완하세요.';
$lang['pls_enable_level'] = '먼저 계정정보 항목에서 회원님의 레벨을 활성화 하세요.';
$lang['change_monthly_level'] = '호스팅비 레벨 변경';
$lang['pls_sel_monthly_level'] = '호스팅비 레벨을 선택하세요.';
$lang['cannot_change_monthly_fee_level'] = '호스팅비 레벨을 수정할수 없습니다.';
$lang['no_change'] = '자료에 아무런 변경이 없습니다.';
$lang['month_fee_level_change_note'] = '호스팅비 레벨이 다음  호스팅비 납부일에 %s로 변경 됩니다.';
$lang['month_fee_level_change_desc'] = '여기서 더욱 낮은 호스팅비를 선택할수 있습니다. 제출성공 후 다음 달의 호스팅비는 새로운 등급에 근거하여 비용을 납부하며 그후 회원님의 호스팅비 레벨은 수정후의 레벨으로 갱신되며 만약 수정후의 호스팅비 레벨이 회원님의 몰레벨에 비하여 낮을시 몰레벨도 따라서 강등 됩니다.';

//账户信息
$lang['input_name_rule'] = '사용자 명칭은 반드시 3자리 문자 이상이여야 합니다.';
$lang['input_store_name_rule'] = '점포명칭은 반드시 3자리문자이상이고 36자리문자 이하여야 합니다.';
$lang['input_store_name_exit'] = '이미존재한 점포명칭입니다.';
$lang['input_store_name_tip'] = '주：점포명칭은 한글로 최대12자 가능.';
$lang['input_name_rule_100'] = '사용자 명칭은 반드시 100자리 문자이하이여야 합니다';
$lang['start_date'] = '시작날자';
$lang['end_date'] = '마감날자';
$lang['input_start_date'] = '시작날자를 선택해 주세요.';
$lang['input_end_date'] = '마감날자를 선택해 주세요.';
$lang['input_date_error'] = '시작날자는 마감날자전 이여야 합니다.';
$lang['input_username'] = '명칭을 입력해 주세요.';
$lang['account_success'] = '자료수정 성공';
$lang['account_error'] = '자료수정 실패 혹은 변함이 없음';
$lang['submit'] = '제출';
$lang['email'] = '이메일';
$lang['profile'] = '개인정보';
$lang['username'] = '사용자명';
$lang['ori_password'] = '원래 비밀번호';
$lang['new_password'] = '새 비밀번호';
$lang['re_password'] = '비밀번호 확인';
$lang['country'] = '국가';
$lang['month_upgrade_from']='호스팅비 레벨이';
$lang['shop_upgrade_from']='몰 레벨이';
$lang['upgrade_to']='에서 업그레이드하여';
$lang['downgrade_to']='에서 감등하여';
$lang['month_upgrade_log_label']='호스팅비 레벨 변동 기록';
$lang['shop_upgrade_log_label']='점포 레벨 변동 기록';

$lang['modify_mobile_number'] = '휴대전화번호 수정';
$lang['pls_input_new_number'] = '새 휴대전화번호를 입력하세요.';
$lang['modify_success'] = '성공적으로 수정하였습니다.';
$lang['check_card_wait'] = '<div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">등록증심사는 대락 2분 소요합니다,</div><div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">잠시 기다려 주십시오</div>';

$lang['check_exceed_three'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:12px;font-family:微软雅黑;">님의 등록증 사진은 3번의 시스템 자동심사에 통과하지 않아 인공심사로 넘어갑니다</div><div style="margin-top:20px; font-size:12px; text-align:center;font-family:微软雅黑;">결과를 조금 더 기다려주세요</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">확인</button></div>';

$lang['check_taiwan_card'] = '<div style="text-align: center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:12px;font-family:微软雅黑;">님의 등록증 사진은  않아 인공심사로 넘어갑니다 </div><div style="margin-top:20px; font-size:12px; text-align:center;font-family:微软雅黑;">결과를 조금 더 기다려주세요</div><div style="margin-top:15px; text-align:center;"><button  onclick="confirm_card()"  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;"  type="button">확인</button></div>';

$lang['check_passed'] ='<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/correct.png"/></div><div style="text-align: center;font-size:20px;font-family:微软雅黑;">님의 주민등록증은 이미 심사 통과하였습니다</div><div style="margin-top:20px; text-align:center;"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">확인</button></div>';

$lang['check_failed'] = '<div style="text-align:center;padding:20px 0;box-sizing:border-box;"><img src="../img/card/error.png"/></div><div style="text-align:center; color:#000;font-size:14px;font-family:微软雅黑;line-height:25px;">님의 주민등록증은 심사에 기닥되였습니다</div><div style="color:#999;font-size:15px;font-family:微软雅黑;line-height:35px;margin-top:20px;box-sizing:border-box;padding-left: 20px;">기각 원인에는:</div><ul style="margin:0;list-style:none;color:#999;font-size:12px;font-family:微软雅黑;line-height:35px;padding-left: 20px;" ><li style="line-height: 25px;">( 1 )기제하신 개인정보과 등록증의 정보가 일치하지 않는다</li><li style="line-height: 25px;">( 2 )등록증 사진이 너무 흐리다</li><li style="line-height: 25px;">( 3 )19살미만</li></ul><div style="list-style:none;color:#999;font-size:12px;font-family:微软雅黑;line-height:35px;padding-left: 20px;">원인을 조회하고 규칙에 맡게 다시 업로드 하시고 심사를 마치세요.</div><div style="margin-top:20px; text-align:center"><button  onclick="confirm_card()"  type="button" style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;">확인</button></div>';

$lang['check_maintenance'] = '<div style="text-align: center; padding:10px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:16px;font-family:微软雅黑;padding:0 80px;line-height:25px; box-sizing:border-box;">죄송합니다, 감사 보수,</div><div style="margin-top:20px; font-size:16px; text-align:center; font-family:微软雅黑;">2시간 후 다시 시도하십시오.<div style="margin-top:15px; text-align:center;"><button  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;" onclick="confirm_card()"  type="button">확인</button></div>';

$lang['check_passed_info'] = '님의 등록증은 이미 <span style="color:red;" >%s</span> 에 심사를 통과 하였습니다.';
$lang['upload_failed'] = '죄송합니다, 업로드 실패';

//团队销售提成奖励
$lang['current_algebra_title'] = '누적 단체공제 대수';
$lang['current_rank'] = '(지금의 몰 레벨)';
$lang['QSOs'] = '개(합격점포)';
$lang['QRCs'] = '개(합격주문)';
$lang['current_algebra'] = '(지금 향유하는 단체공제 단계)';
$lang['learn_more_rule'] = '더욱 많은 보너스 규정을 알아보기';
$lang['freeze'] = '휴면';
$lang['enjoy_gold'] = '골드단체의 판매 이윤';
$lang['enjoy_diamond'] = '다이아몬드단체의 매출 이윤';

//总裁奖励
// $lang['infinity_con1'] = '지난 달 반드시 다이아몬드 레벨이여야 합니다.';
// $lang['infinity_con2'] = '최소한 3000개의 합격실버급(혹은 이상)몰을 누적해야함 (최소 2조의 단체: 매조 단체는 최다 게수:1500). ';
// $lang['infinity_con3'] = '개인 점포는 30개 합격 주문 누적';
$lang['infinity_countdown'] = '다음의 보너스 발급까지:';
$lang['infinity_enable'] = '다음 보너스  발급에 참여할수 있습니까?';
$lang['infinity'] = '매달 단체 매출 총재 보너스';
$lang['infinity_title'] = '총재 보너스';
$lang['infinity_info'] = '팀 매출 총재 보너스 정보';
$lang['infinity_log'] = '총재 보너스 일지';
$lang['infinity_date_title'] = '총재 보너스 장려시간';
$lang['infinity_date_content'] = '다음달 10일';
$lang['infinity_qualifications_title'] = '총재 보너스의 합격조건';
$lang['infinity_formula_title'] = '총재 보너스의 보너스 계산방법';
$lang['infinity_formula_content'] = '합격자는 팀의 지나달 제11대로부터 시작하는 매출 이윤 총액×0.5%를 획득할수있다.';
$lang['qualified_time'] = '합격 월';
$lang['grant_time'] = '합격시간 통계';
$lang['is_grant'] = '발급 하였습니까?';

//上传头像
$lang['user_avatar'] = '프로필사진';
$lang['new_user_avatar'] = '새로운 프로필사진';
$lang['upload'] = '업로드';
$lang['upload_avatar'] = '프로필사진 업로드';
$lang['reselect'] = '포토를 다시 선택하세요.';
$lang['cropped_tip'] = '제시:오려내기 구역을 선택하세요.';
$lang['upload_tip'] = 'JPG、GIF혹은PNG포맷의 파일을 업로드 할수 있으며 파일 크기는 <strong>1.0MB</strong>를 초과할수 없으며 <br>규정 널비와 높이는 <strong >1024*800</strong>초과해선 안됩니다.';

//用户升级 购买店铺
$lang['current_level'] = '지금 몰 레벨';
$lang['member_level'] = '몰 레벨';
$lang['opening_time'] = '오프닝 시간';
$lang['payment_method'] = '납부방식';
$lang['amount'] = '응납비용';
$lang['confirm_purchase'] = '구매확인';
$lang['buy_now'] = '바로 구매';
$lang['buy_member'] = '몰 구매하기';
$lang['go_pay'] = '결제하러 가기';
$lang['payment_tip'] = '납부 알림';
$lang['upgrade_level'] = '몰 업그레이드';
$lang['annual_fee'] = '년 호스팅비';
$lang['monthly_fee'] = '월 호스팅비';
$lang['alipay'] = '결제 페이지';
$lang['payment_content1'] = "결제성공전 결제인증 창을 닫지 마십시오. ";
$lang['payment_content2']= "결제성공후 결제하신 상황에 근거하여 아래의 버턴을 클릭해 주세요.";
$lang['payment_success']= "결제완성";
$lang['payment_error']= "결제에 문제 생김";

//奖励制度介绍
$lang['reward_tip'] = '<strong>QSO (합격점포):</strong><ul><li> 브론즈급（혹은이상）의 점포：<ol><li>점포(몰)가 이미 활성화 상태임</li><li>제때에 몰 호스팅비 납부</li></ol></li><li>프리몰：<ol><li>자신의 몰이 50달라 혹은이상의 판매액(운송비 미포함)을 누적.</li></ol></li></ul>';
// $lang['reward_tip2'] = '<strong>QRC (합격고객):</strong><ul><li>주문금액을 $25이상 달성</li><li>점주인 혼자가 아닌</li></ul>';
$lang['directly'] = '나의 지점몰';
$lang['store_url'] = ' Walhao점포 사이트';
$lang['rewards_introduced'] = '보너스 제도';
$lang['r1'] = '개인 제품 매출 보너스';
$lang['r2'] = '단체 매출 업적 공제 보너스';
$lang['r3'] = '단체 조직 매트릭스 보너스';
$lang['r4'] = '매주 월드 이윤 배당 보너스';
$lang['r8'] = '매월 월드 이윤 배당 보너스';
$lang['r5'] = '매주 리더십 보너스';
$lang['r6'] = '매달 팀 판매 리더십 보너스';
$lang['r7'] = '매월 단체 매출 업적 총재 보너스';

/* 个人店铺销售提成奖 */
$lang['r1_content'] = '조건：<br/> 임의등급의 개인점포.';
$lang['r1_content_notice'] = '보너스：<br/> 점주인은 개인점포 매출이윤의 20%를 얻으실 수 입니다.';

/* 团队销售业绩提成奖 */
$lang['r2_content1'] = '<ul><li>[프리점포]</li></ul>';
$lang['r2_content1_1'] = '조건：<br/>개인점포가 프리점포 입니다；<br/>
보너스：<br/>제1대점포 매출이윤의 5%';
$lang['r2_content_1'] = '<ul><li>[실버점포]</li></ul>';
$lang['r2_content_2'] = '<ul><li>[골드점포]</li></ul>';
$lang['r2_content_3'] = '<ul><li>[다이아모드점포]</li></ul>';
$lang['r2_content_5'] = '<ul><li>[브론즈점포]</li></ul>';
$lang['r2_content_5_1'] = '조건：<br/>개인점포가 브론즈점포 입니다；
<br/>보너스：<br/>제1대점포 매출이윤의 10%, 제2대점포 매출이윤의 5% ';
$lang['r2_content_1_1'] = '조건：<br/>개인점포가 실버점포 입니다；<br/>
보너스：<br/>제1대점포 매출이윤의 12%, 제2대점포 매출이윤의 7%';
$lang['r2_content_2_1'] = '조건：<br/>개인점포가 골드점포 입니다；<br/>
보너스：<br/>제1대점포 매출이윤의 15%, 제2대점포 매출이윤의 10% ';
$lang['r2_content_3_1'] = '조건：<br/>개인점포가 다이아몬드점포 입니다；<br/>
보너스：<br/>제1대점포 매출이윤의 20%, 제2대점포 매출이윤의 12% <br/><br/><br/>';

/*  每月团队组织分红奖  */
$lang['r3_content_1'] = '';
$lang['r3_content_2'] = '
조건：<br/>
1) 다이아몬드급 합격점포;<br/>
2) 점주인의 직함은 마케팅 주관자 (MSB) 혹은 그 이상;<br/>
3) 전월 개인점포 매출누적금액 100달러.<br/><br/>
보너스：<br/>
보너스는 회원님의 현재 직함, 점포등급 및 개인점포 전월 판매액에 의하여 분배됩니다. 본사는 매월 월드 총 이윤의 10%에서 본 보너스를 분배합니다.
<br/><br/>
보너스 발급일：<br/>
매월 15일.';
$lang['r3_content_3'] = '';

/* 每天全球利润分红奖 */
$lang['r4_content_1'] = '매일 월드 이윤 배당 보너스는 매월 점포에 매출업적을 가져오는 점주인을 위해 설계한것입니다.';
$lang['r4_content_2'] = '조건：<br/>
전월 브론즈급(혹은 그 이상) 점포의 매출액이 25달러 (프리점주는 100달러) 누적 되었을 시 익월에 매일 월드 이윤 배당 보너스를 받으실 수 있습니다.<br/><br/>
보너스：<br/> 본 보너스는 회원님의 등급 및 본 회원님의 일반주문 판매액에 의하여 계산됩니다. 회원님의 등급과 일반주문 판매액이 높을수록 더 많은 보너스를 분배 받으실 수 있습니다.<br/>
본 보너스는 매일 본사의 월드 총 매출이윤의 10% 에서 분배될 것입니다';

/* 138分红 */
$lang['r9_content_1'] = '<ul><li>회사 매트릭스의 가입 조건：<br/> 유료점주인 혹은 이름 및 신분증 인증이 통과된 프리점주인. </li></ul>';
$lang['r9_content_2'] = '<ul><li>조건 ：<br/> 전월의 개인점포 매출액이 50달러 혹은 그 이상을 누적한 브론즈급(혹은 그 이상)의 점포는 익월 1일부터 138보너스를 받으실 수 있습니다. </li></ul>';
$lang['r9_content_3'] = '<ul><li>회사 매트릭스 규칙：</br> 매트릭스에 가입한 점포를 왼쪽으로부터 오른쪽까지 138개의 위치를 배열하고 139번째 점포부터 다음행에 같은 방식으로 138개 위치를 배열하며 나머지도 동일한 방식으로 배열합니다.</li></ul>';
$lang['r9_content_4'] = '<ul><li>보너스：회사측은 매트릭스 중 모든 성원의 당일 매출이윤의 5%를 매트릭스중 조건에 만족하는 점주인에게 나누어주며 분배규칙은 점주인에 소속되어있는 점포수로 계산합니다.</li></ul>';
$lang['r9_content_5'] = '<ul><li>보너스 계산법 ：(점주인의 138매트릭스하의 인원수 / 모든 배당에 참여하는 점주인 매트릭스 아래 인원수의 합 * 매트릭스 모든 성원의 당일 주문 매출이윤 * 5%) ＋ (매트릭스 모든 성원의 당일 제품세트 매출이윤* 5%／ 이 배당 보너스를 만족하는 모든 베테랑 점주인의 인원수).</li></ul>';

/* 每周领导对等奖 */
$lang['r5_content_1'] = '주간 리더십 보너스는 신규점주인을 도와 사업을 빨리 발전하게 한 마케팅 리더를 위해 설계한 것입니다.';
$lang['r5_content_2'] = '조건：<br/>
1）다이아몬드급 합격점포；<br/>
2）점주인의 직함은 반드시 마케팅 주관자(혹은 이상)；<br/>
3）플랫폼의 전월 개인점포 매출누적액 100달러 혹은 그 이상.';
$lang['r5_content_3'] = '
보너스：<br/> 조건을 만족하는 점주인은 익월 매주 월요일에 본 보너스를 분배 받으실 수 있습니다.<br/><br/>
보너스 계산방법 ：<br/> 점주인의（하부 1대 점포+ 하부 2대 점포）모든 점주 보너스금액* 5% <br/>
';
$lang['r5_content_4'] = '<br/><span class="label label-important">주의：<br/>
1) 이 보너스는 매월초에 전월 조건을 만족하는 점주인을 심사하고 심사에 통과된 점주인은 이번 달 매주 본 보너스를 분배 받으실 수 있습니다. <br/>
2) 이 보너스 제도는 매월 필요주문금액이 있습니다 .</span>';

/* 每月杰出店铺分红奖   */
$lang['r6_content_1'] = '매월 우수 점포 배당 보너스는 업적이 우수한 점포를 위하여 설계한 것입니다.';
$lang['r6_content_2'] = '<br/>
조건:<br/>
1）실버급 합격점포；<br/>
2）점주인의 직함이 반드시 베테랑 점주(혹은 이상)；<br/>
3）전월 개인점포의 일반 매출누적액 100달러 혹은 그 이상. <br/><br/>
보너스：<br/>조건에 만족하는 점주는 익월 15일에 보너스를 받으실 수 있습니다.<br/><br/>
보너스 계산방법：<br/>(회사 전월 월드 매출이윤 * 4% ／ 합격 총 인원수)+( 사용자 배당 포인트수 / 배당에 참여하는 모든 사용자의 총 배당 포인트수 * 회사 전월 월드 매출이윤 * 6%) . <br/>
';
$lang['r6_content_3'] = '<br/><span class="label label-important">
주의：<br/>
이 보너스 제도는 매월 필요주문금액이 있습니다.</span>';

/*  每月总裁销售奖  */
$lang['r7_content_1'] = '매월 총재 매출 보너스는 본사의 마케팅 발전에 중대한 공헌이 있으신 글로벌 마케팅 부총재를 장려하기 위하여 설계한 것입니다.';
$lang['r7_content_2'] = '
조건：<br/>
1）다이아몬드급 합격점포；<br/>
2）점주인의 직함은 글로벌 마케팅 부총재；<br/>
3）해당 단체에 브론즈급 (혹은 이상)의 합격점포수가 3000개 혹은 그 이상 있으시면 1/2규칙이 여기에 적용되여 각 그룹의 단체에 최대 1500개의 브론즈급 (혹은 이상)합격점포만 계산합니다. 이는 리더가 더욱 발전력있는 단체건설을 격려하기 위해서입니다.<br/>
4）전월 개인점포 매출누적금액 250달러. <br/><br/>
보너스：조건에 만족하는 글로벌 마케팅 부총재는 그 단체의 제2항 보너스 제도외의 전달 매출이윤의 0.25%를 얻을수있고 보너스는 매월 15일에 발부함.
<br/><br/><span class="label label-important">주의：이 보너스 제도는 매월 필요주문금액이 있습니다.</span>';

$lang['r8_content_1'] = '조건：<br/>
1）다이아몬드급 합격점포；<br/>
2）점주인의 직함이 마케팅 총감；<br/>
3）전달에 개인점포가 100달러의 매출액 그리고 4개 혹은이상의 주문수를 달성. <br/><br/>
보너스：조건에 만족하는 마케팅 총감은 다음 달15일에 이 보너스를 향유함.<br/><br/>
보너스계산법：각 합격 리더님의 보너스＝회사 전달의 월드 매출이윤* 1% ／합격 리더님의 총 인원수.';
$lang['r8_content_2'] = '<br/><span class="label label-important">주의：이 보너스 제도는 매달마다 몰의 주문금액의 요구가 있다.</span>';

/*  每月领导分红奖   */
$lang['r10_content_1'] = '1)고급 마케팅 주관자:<br/>
조건：<br/>
a）다이아몬드급 합격점포；<br/>
b）점주인의 직함은 고급 마케팅 주관자;<br/>
c）전월 개인점포 매출누적금액 100달러 혹은 그 이상. <br/>
보너스：조건을 만족하는 고급 마케팅 주관자는 익월 15일에 이 보너스를 분배 받으실 수 있습니다.<br/>
보너스 계산방법：합격 리더님의 보너스＝(회사 전월 월드 매출이윤 * 3%／합격 리더님의 총 인원수)+(해당 리더 배당포인트 / 이 배당에 참여하는 리더의 총 배당포인트* 회사 전월의 월드 매출이윤 * 1%). <br/><br/>

2)마케팅 총감:<br/>
조건：<br/>
a）다이아몬드급 합격점포；<br/>
b）점주인의 직함이 마케팅 총감；<br/>
c）전월 개인점포 매출누적금액 200달러. <br/>
보너스：조건을 만족하는 마케팅 총감은 익월 15일에 본 보너스를 받으실 수 있습니다. <br/>
보너스 계산방법：각 합격 리더님의 보너스＝(회사 전월의 월드 매출이윤 * 1%／합격 리더님의 총 인원수)+(해당 리더 배당포인트 / 이 배당에 참여하는 리더의 총 배당포인트* 회사 전월의 월드 매출이윤 * 0.5%). <br/><br/>

3) 글로벌 마케팅 부총재:<br/>
조건：<br/>
a）다이아몬드급 합격점포；<br/>
b）점주인의 직함이 글로벌 마케팅 부총재;<br/>
c）전월 개인점포 매출누적금액 300달러.<br/>
보너스：조건에 만족하는 글로벌 마케팅 부총재는 익월 15일에 본 보너스를 받으실 수 있습니다.<br/>
보너스 계산방법：각 합격 리더님의 보너스＝(회사 전월 월드 매출이윤 * 0.5%／합격 리더님의 총 인원수)+(해당 리더 배당포인트 / 이 배당에 참여하는 리더의 총 배당포인트* 회사 전월 월드 매출이윤 * 0.25%).<br/><br/>';
$lang['r10_content_2'] = '<span class="label label-important">주의：이 보너스 제도는 매월 필요주문금액이 있습니다.</span>';


$lang['r11_content']="
조건：<br/>
1）TPS 임의등급의 합격점주（프리점주 포함）；<br/>
2）전월 브론즈급(혹은이상) 세트상품 (업그레이드용 세트상품 포함)의 매출 혹은 개인점포의 전월 일반주문 매출누적액이 250달러(혹은이상) 달성되어야 합니다.<br/>
<br/>
보너스：<br/>만약 점주가 전월에 상기 조건을 만족할 시 이번달부터 매일 본 배당을 분배 받으실 수 있습니다.<br/>
<br/>
보너스 계산법：<br/>사용자의 전월 매출액(세트상품 매출액+일반주문 매출액)／ 본 배당에 참여하는 사용자의 총 매출액 * 회사 전일 매출이윤의 10%<br/>
<br/><br/>
<span class='label label-important'>주의: <br/>1)이 보너스 제도는 매월 필요매출금액이 있습니다.(단품이나 세트판매).<br/>
2) 등급하락 후 새롭게 진행한 업그레이드로 인해 발생한 세트판매액은 계산되지 않습니다.</span>
";

/* 新会员专享奖  */
$lang['r12_content_1'] = '
조건：<br/>TPS 에 신규가입하신 회원님께서는 브론즈급 (혹은 이상) 으로 업그레이드 하시고  동시에 일반주문에서 50달러 (혹은 이상) 소비되면 달성 일 다음날부터 업그레이드 성공후 30일까지 이 보너스를 받으실 수 있습니다.<br/> 보너스는 회원등급과 소비된 주문의 금액에 따라 계산되며 등급과 소비액이 높을수록 보너스 금액도 따라 높아집니다.<br/><br/>
보너스：<br/>사용자보너스 = 사용자 주문 총금액(업그레이드용 주문 + 일반주문)／이 보너스를 받을실 수 있는 모든 사용자 주문의 총금액 * 회사 전일 매출이윤의 2%. <br/>
<br/>';

/*每周团队销售分红*/
$lang['r_week_share_content'] = "
조건:<br/>
1) 다이아몬드급 합격점포;<br/>
2) 점주인의 직함이 반드시 베테랑 점주(MSO)이거나 그 이상;<br/>
3) 전월의 개인점포의 누적 판매액 100달러;<br/>
<br/> 보너스:<br/> 처음으로 합격하는 회원님은 그 다음 월요일에 해당 보너스를 분배 받으실 수 있습니다.<br/><br/>
보너스 계산방법:<br/>
보너스는 회원님의 전월 개인점포의 일반주문 판매액, 전월 점주인의 직함, 전월 개인점포 등급과 배당 포인트수에 따라 분배됩니다. 점주인의 직함, 개인점포 등급과 개인점포 일반주문 판매액이 높을수록 분배된 보너스의 금액도 많아집니다.<br/>본사는 매주 월드 총 이윤의 10%를 상금으로 분배합니다.
";

/*佣金补单*/
$lang['commission_order_repair'] = "커미션  주문 추가";
$lang['repair_order_year_month'] = "주문 추가 년월";
$lang['commission_type'] = "커미션 유형";
$lang['commission_year_month'] = "커미션 년월";
$lang['sale_amount_lack'] = "주문 추가할 금액";
$lang['deadline'] = "유효기간";
$lang['repair_order'] = "주문 추가";
$lang['order_repairing'] = "주문 추가 중...";
$lang['score_year_month'] = "업적 년월";
$lang['comm_date'] = "커미션 날짜";
$lang['commission_withdraw_amount'] = "회수할 금액";
$lang['if_not_repair_order_before_deadline'] = "만약 유효기간내 주문 추가를 진행하지 않을시";
$lang['order_repair_notice'] = "주의：<br/>
     아래의 리스트는 회원님께서 주문을 취소한후 그에 따른 일부 커미션이 수령조건에 만족되지 못하여 생성 된겄입니다.<br/> 
	 회원님께서는 반드시 유효기간 내에 해당 주문금액을 보충해야 합니다. 아니면 해당 커미션이 시스템으로 인하여 회수처리 됩니다.<br/>
	 회원님께서 진행하신 커미션 주문 추가의 모든 주문 금액은 커미션 주문 추가해야하는 그달의 업적으로 삼는다.";
$lang['order_repair_step'] = "주문 추가 과정：<br/>
1、회원님께서 주문 추가하려는 기록뒤의 “주문 추가” 버튼을 클릭하세요. 클릭한후 버튼이 “주문 추가 중”의 상태로 변경 됩니다.<br/>
2、쇼핑 몰에서 주문을 하세요. 주문완성 금액이 주문 추가 금액보다 많거나 같을시 이 리스트 중 해당하는 주문 추가 해야할 기록이 사라지게 됩니다. 이는 주문 추가를 성공적으로 진행하였음을 의미 합니다.
";
$lang['modifyVal_illegal'] = "부정확한 유효기간";

//职称晋升介绍
$lang['rank_advancement']="직함 승진 소개";

$lang['mso']="a) 베테랑 점주(MSO)： ";
$lang['mso_context']="1） 자신의 쇼핑 몰은 브론즈(혹은이상)급의 합격점포.；2) 3개의 브론즈급(혹은이상)인 합격지점을 오픈.";

$lang['sm']="b) 마케팅 주관자(MSB)：";
$lang['sm_context']="1）자신의 쇼핑 몰은 브론즈(혹은이상)급의 합격점포.；2）자신의 지점중 3명의 베테랑 점주가 있고 한 그룹중 최소 한명이 베테랑 점주임.";

$lang['sd']="c) 고급 마케팅 주관자(SMD)：";
$lang['sd_context']="1）자신의 쇼핑 몰은 브론즈(혹은이상)급의 합격점포；2）자신의 지점중 3명의 마케팅 주관자가 있고 한 그룹중 최소 한명이 마케팅 주관자임.";

$lang['vp']="d) 마케팅 총감(EMD)：";
$lang['vp_context']="1）자신의 쇼핑 몰은 브론즈(혹은이상)급의 합격점포；2）자신의 지점중 3명의 고급 마케팅 주관자가 있고 한 그룹중 최소 한명이 고급 마케팅 주관자임.";

$lang['gvp']="e) 글로벌 마케팅 부총재(GVP)：";
$lang['gvp_context']=" 1）자신의 쇼핑 몰은 브론즈(혹은이상)급의 합격점포；2）자신의 지점중 3명의 마케팅총감이 있고 한 그룹중 최소 한명이 마케팅총감임.";

$lang['finally explanation right']="f) 본사에서  최종 해석권을 갖습니다.";  

$lang['back_account'] = '<span style="color: purple"></span>초후 계정 항목으로 되돌아갑니다.';

$lang['Bulletin_title'] = 'TPS공고';
$lang['upload'] = '업로드';
$lang['important'] = '<span style="color:#f00;font-weight:bold;"> 중요 제시：</span>';
$lang['enroll'] = '신입 가맹 링크';
$lang['evaluate']='상품 평가';
$lang['no_collection'] = '아직 팔로우한 상품이 없습니다.';
$lang['try_again'] = '다시 시도 하세요.';
$lang['receipt_title_'] = ' TPS몰 영수증';
$lang['receipt_content_'] = ' TPS몰 영수증이 송달 됬습니다. 부속 파일을 살펴보세요.';
$lang['deliver_content_'] = '귀하는 %s에 구매한 제품 주문번호 %s는 이미 %s을/를 통하여 발송 하였습니다. 송장번호는 %s 입니다. 배송상태 조회를 진행하세요.';
$lang['deliver_title_'] = 'TPS몰 주문방송 알림';
$lang['order_date'] = '주문 날짜';
$lang['order_amount_no'] = '주문 금액';
$lang['order_amount_no_tip'] = '주문 금액에 운송비 포함하지 않는다.';
$lang['customer_name'] = '고객명';
$lang['filter_month'] = '월';
$lang['cancel_collection'] = '팔로우 취소';

$lang['you_also_not_choose_product']='아직 선색 구매한 상품이 없습니다.환영 페이지에 방문하여 고르세요.';
$lang['dear_'] = '존경하신';
$lang['email_end'] = 'support@shoptps.com을 통하여 고객 서비스를 연락하세요.<br>
갑사합니다.<br>
TPS관리 팀';

$lang['account_status']='계정 상태:';
$lang['fee_num_msg']='<span style="color: #880000;">( 합계:fee_num:개월 호스팅비 미납)</span>';
$lang['fee_num_msg_one']='<span style="color: #880000;">( 합계:fee_num:개월 호스팅비 미납)</span>';
$lang['sale_rank_up_time']='(이 직함은 <span style="color: #F57403">:sale_rank_up_time:</span>에 달성 하였다.)';
$lang['month_fee_arrears']='몰 관리비(호스팅비) 미납하여 완납후 업그레이드를 진행하세요.';

//^^^^
$lang['daily_top_performers_sales_pool'] = '매출 엘리트 일 배당 보너스';
$lang['daily_bonus_elite'] = '매출 엘리트 일 배당 보너스';
$lang['cash_pool_auto_to_month_fee_pool'] = '캐시버킷으로 자동지급';
$lang['new_func'] = '새 기능 알림';
$lang['auto_to_month_fee_pool_notice'] = "
<p>존경하신tps회원님，회원님의 매달 호스팅비 납부를 편리하기 위하여 </p>
<p>우리는 시스템에【캐시버킷으로 자동지급】기능을 증가 하였습니다.</p>
<p>회원님께서 이 기능을 설정할시 매달 호스팅비일에 호스팅버킷의 잔액이 호스팅비 납부에 부족하면 시스템은 자동으로 회원님의 캐시버킷에서 필요한 차액을 호스팅버킷으로 전입하여 호스팅비납부를 진행할수있도록 합니다.</p>
<p><a href='ucenter/commission#month_fee_auto_to'>여기 클릭하여 설정하기</a></p>";

/** 售后中心 start **/
$lang['tickets_center'] = ' A/S 센터';
$lang['add_tickets'] = ' A/S청구서 새로만들기';
$lang['my_tickets'] = '나의 A/S청구서';
$lang['tickets_cus_num']='상담원 번호';
$lang['tickets_title'] = 'A/S제목';
$lang['tickets_id'] = 'A/S청구서번호';
$lang['tickets_info'] = 'A/S청구서 상황';
$lang['pls_input_title'] = '제목을 입력하십시오. ';
$lang['exceed_words_limit'] = '글자수 제한을 초과하였습니다.';
$lang['count_'] = '총';
$lang['remain_'] = '나머지';
$lang['max_limit_'] = '최대';
$lang['_words'] = '자';
$lang['waiting_progress'] = '해결대기';
$lang['in_progress'] = '진행중';
$lang['ticket_resolved'] = '해결완료';
$lang['had_graded'] = '평점마침';
$lang['apply_close'] = '중단신청';
$lang['tickets_no_exist'] = '죄송합니다. A/S청구서가 존재하지 않습니다.';
$lang['attach_no_exist'] = '죄송합니다. 첨부 파일이 존재하지 않습니다.';
$lang['tickets_closed_can_not_reply'] = ' A/S청구서가 종료되여 답장을 보낼수 없습니다.';
$lang['pls_input_reply_content'] = '답장내용을 입력하세요. ';
$lang['submit_as_waiting_resolve'] = '해결대기로 제출';
$lang['submit_as_resolved'] = '해결완료로 제출';
$lang['confirm_submit_tickets_as_resolved'] = '해결완료로 제출하겠습니까? 제출후 A/S청구서는 종료상태로 표기되며 답장을 보낼수 없습니다.';
$lang['tickets_closed_can_not_reply'] = ' A/S청구서가 종료되여 답장을 보낼수 없습니다.';
$lang['kindly_remind'] = '도움말';
$lang['tickets_type'] = ' A/S문제유형';
$lang['add_and_quit'] = '회원/정보관리';
$lang['join_issue'] = '개인 정보 관리';
$lang['quit_issue'] = '감등/탈퇴신청';
$lang['up_or_down_grade'] = '업그레이드/결제문의';
$lang['monthly_fee_problem'] = '호스팅비 문의';
$lang['platform_fee_problem'] = '플랫폼 관리 수수료';
$lang['reward_system'] = '보너스제도';
$lang['product_recommendation'] = '제품추천';
$lang['shop_transfer'] = '명의 변경';
$lang['commission_problem'] = '커미션 문의';
$lang['order_problem'] = '주문/AS';
$lang['freight_problem'] = '반품/교환/AS';
$lang['withdraw_funds_problem'] = '현금 인출 문의';
$lang['walhao_store'] = ' walhao몰';
$lang['tickets_check_order_status']='배송지연';
$lang['tickets_change_delivery_information']='배송정보 변경';
$lang['tickets_order_cancellation']='주문취소';
$lang['tickets_product_review']='기타 주문 문의';
$lang['tickets_member_suggestions']='회원의견';
$lang['other'] = '기타';
$lang['tickets_after_sales_problem'] = '반품/교환/AS';
$lang['shipping_logistics_problems'] = '배송지연';
$lang['tickets_product_damage'] = '반품/교환/AS';
$lang['tickets_leakage_wrong_product'] = '기타 주문 문의';

/**申请**/
$lang['pls_t_type'] = '문제 분류를 선택하세요.';
$lang['pls_t_title'] = ' A/S제목을 입력하세요.';
$lang['pls_t_content'] = ' A/S내용을 입력하세요.';
$lang['pls_t_title_or_id'] = '제목/청구서번호를 입력하세요.';
$lang['tickets_save_fail']  = '신청실패';
$lang['tickets_save_success'] = '신청성공';
$lang['tickets_reply_fail'] = '답장실패';
$lang['tickets_reply_success']='답장성공';
$lang['tickets_kindly_notice'] = '존경하신 회원님께 알려드립니다.';
$lang['add_tickets_tip1']='1）동일한 문제에 대하여 회원님께서는 하나의 A/S청구서만 작성하시면 되고 동시에 “나의 A/S청구서”에서 문의사항의 처리 진도를 조회할수 있으며 상담원과 실시간 교류할수 있습니다. 문제마다 전문 담당 상담원이 시작부터 끝까지 서비스해드립니다. 상담원의 번호를 유의하시기 바랍니다.';
$lang['add_tickets_tip2']='2）다른 유형의 문제는 회원님께서 새로운 A/S청구서를 만들어 시스템에서 회원님께 서비스하실 상담원을 분배할것을 기다리시기 바랍니다. ';
$lang['add_tickets_tip3']='3）회원님의 귀한 시간을 낭비하지않기 위하여 동일한 문제는 여러 A/S청구서를 만들지 마십시오. 감사합니다.';
$lang['add_tickets_tip4']='4）고객 센터의 근무간은 월-금의 오전9:30-12:30,오후 14:00-18:30이며 회원님의 A/S청구서를 처리하는대 2-3근무일 걸릴수도 있습니다. 고객 센터에서 회원님의 A/S청구서를 최대한 빨리 처리하오니 이점에 양해 바랍니다.';
$lang['view_tickets_title_']='일지내용';
$lang['my_tickets_tip_']='1）회원님의 문의사항은 고객센터에서 24시간내로 처리할것입니다. TPS고객센터전화(0755-33198568)에 전화드리고 접속을 원하신 상담원번호를 입력하시기 바랍니다. ';
$lang['my_tickets_tip2_']='2）회원님의 문의사항이 보다 정확하고 빠르게 해결하기 위하여 전화로 문의하실때 직접 담당 상담원께 연락하시기 바랍니다. ';
$lang['jixu_submit']='새로운 문의 제기';
$lang['jiexie_previous']='전 문의에 이어가기';
$lang['tips_tickets_message']='이전의 진행중인 같은 유형의 문의에 계속 이어서 진행 하시겠습니까?  A/S청구서번호#%s 혹은 새로운 문의를 제기하기? ';
$lang['tickets_email_title'] = '회원님의 A/S청구서(#%s)가 곧 자동으로 중지될겁니다.';
$lang['tickets_email_content'] = '회원님의 A/S청구서(#%s)가 %d일후 자동으로 중지될겁니다. 회원님의 문의사항의 해결여부를 확보하기 위하여 회원님께서는 TPS멘버 시스템에 로그인하여 확인하거나 고객 센터에 전화로 문의하셔도 됩니다. 감사합니다.';
$lang['tickets_apply_close_tips'] = '이 A/S청구서에 대하여 상담원이 종료요청을 실행하였습니다. 종료에 동의하시면 “해결완료로 제출”를 클릭하시고 평점을 진행하세요. 만일 동의하지 않으시면 대화창에서 계속 소통하여 “해결대기로 제출”을 클릭하세요. 그렇게 하지 않는다면 시스템에서 12일후 자동적으로 이 A/S청구서를 종료할겁비다.';

/**评分**/
$lang['t_pls_t_score'] = '평점하세요.';
$lang['t_score'] = '점';
$lang['t_very_dissatisfied'] = '매우 불만하다. ';
$lang['t_dissatisfied'] = '불만하다.';
$lang['t_general'] = '보통이다.';
$lang['t_satisfaction'] = '만족하다. ';
$lang['t_very_satisfactory'] = '매우 만족하다. ';

$lang['button_text'] = '첨부 파일 선택';
$lang['is_exists'] = '이미 존재한 파일입니다.';
$lang['remain_upload_limit'] = '선택한 파일수가 남은 업로드수를 초과하였습니다. ';
$lang['queue_size_limit'] = '선택한 파일수가 행렬의 수량을 초과하였습니다.';
$lang['exceeds_size_limit'] = '파일의 크기가 제한에 넘었습니다. ';
$lang['is_empty'] = '파일을 비울수 없습니다.';
$lang['not_accepted_type'] = '업로드에 맞지 않은 파일 형식입니다.';
$lang['upload_limit_reached'] = '업로드 한계에 이르렀습니다.';
$lang['attach_delete_success'] = '삭제성공';
$lang['attach_no_permissions'] = '죄송합니다. 권한이 부족합니다.';
$lang['attach_cannot_find'] = '파일을 찾을수 없습니다. ';
$lang['not_support_mobile_upload'] = '첨부파일 업데이트는 휴대전화에서 지원되지 않습니다.';
/**售后中心 end **/

//^^^^供应商推荐奖
$lang['supplier_recommend_commission'] = 'The bonuses for Recommended supplier ';
$lang['total_sale_goods_number']='Total sales of :sale_number: items';

//删除免费店铺
$lang['shop_management']='점포관리';
$lang['del_shop']='점포삭제';
$lang['is_show_delete']='점포삭제를 실시하면 복구할수 없습니다. 점포삭제를 확인하시나요？';
$lang['is_delete_show']='점포삭제 확인';
$lang['del_shop_success']='점포를 삭제하였습니다. 3초후 자동으로 새로고침을 진행합니다.';

$lang['url_not_id_exit'] = '입력내용이 규정에 접합하지 않습니다.';
$lang['url_show'] = '수정규정: 숫자와 영문문자의 조합, 모두 영문문자의 조합, 회원님의 ID.';
$lang['card_upload_error'] = '上传失败，请稍后再试！';
//支付宝绑定解绑
$lang['please_input_code'] = "인증번호를 입력하세요.";
$lang['please_input_cash_passwd'] = "PIN번호를 입력하세요.";


//手机号绑定解绑
$lang['please_input_mobile'] = "휴대전화번호 입력";
$lang['mobile_format_error'] = "휴대전화번호 오류";
//$lang['please_input_code'] = "인증번호 입력";
$lang['hacker'] = "해커";
$lang['binding_mobile_failed'] = "휴대전화번호 인증실패";
$lang['binding_mobile_success'] = "연동성공";
$lang['mobile_code_error'] = "인증번호 오류";
$lang['mobile_code_expire'] = "시한 효력을 잃은 인증번호";
$lang['please_verify_old_phone'] = "원래의 휴대전화번호를 인증하세요.";
$lang['phone_has_been_userd'] = "사용된 휴대전화번호입니다. ";

//新增
$lang['send_code_frequency'] = "작업이 너무 빈번합니다. 잠시후 다시 시도해주십시요! ";

$lang['code_has_sent_to'] = "인증번호를  ";
$lang['please_check_out'] = "로 보냈습니다. 확인하시기 바랍니다.";
$lang['not_receive_code'] = "인증 번호를 받지 못했나요? ";
$lang['not_receive_reason'] = "원인：<br/>1、 휴대전화번호의 정확여부를 확인하세요.;<br/>2、 휴대전화 메세지차단기능 설정을 확인하세요.;<br/>3、메세지수신에 지연될수있습니다. 3-5분 기다려 주세요.;";
//新增
$lang['mobile_can_not_same'] = "새휴대전화번호는 원휴대전화번호와 같을수없습니다. ";
$lang['get_phone_code'] = "휴대전화 메세지 인증번호";
$lang['bind_success'] = "성공";

//收货地址管理 m by brady.wang
$lang['my_addresses'] = "나의 배송주소";
$lang['address_not_exists'] = "존재하지 않은 주소입니다.";
$lang['156_address'] = "중국대륙지역 배송주소";
$lang['344_address'] = "중국홍콩지역 배송주소";
$lang['840_address'] = "미국지역 배송주소";
$lang['410_address'] = "한국지역 배송주소";
$lang['000_address'] = "기타구역 배송주소";
$lang['user_region'] = "소재 지역";
$lang['user_address_detail'] = "자세한 주소";
$lang['address_mobile'] = "휴대전화번호";
$lang['address_action'] = "동작";
$lang['spread'] = "펼치기";
$lang['pack_up'] = "모음";
$lang['address_edit'] = "수정";
$lang['set_success'] = "설정성공";
$lang['set_default_address'] = "기본으로 설정";
$lang['address_limit'] = "(이구역에서 이미 :number:개 주소를 작성하였습니다. 주소를 최대 5개만 작성할수 있습니다.)";
$lang['china_land'] = "중국대륙";
$lang['china_hk'] = "중국홍콩";
$lang['other_region'] = "기타구역";
$lang['address_delete'] = "주소삭제";
$lang['you_will_del_address'] = "이 주소를 삭제하려 합니다.！";
$lang['access_deny'] = "권한이 없습니다.";
$lang['modify_address_failed'] = "수정실패";

$lang['address_phone_check'] = "6-20자리 숫자";
$lang['address_phone_check_1'] = "휴대전화번호는 오직  6-20자리 숫자여만 합니다.";
$lang['address_reserve_check'] = "문자 6-20자리";
$lang['address_reserve_check_1'] = "비상용 전화번호는 오직 6-20자리 문자여야 합니다. ";
$lang['address_zip_code_check'] = "20자이내";
$lang['address_zip_code_check_1'] = "우편번호는 20자이내여야합니다.";
$lang['mobile_system_update'] = "메세지시스템 업그레이드 중";

//新版地址验证
$lang['check_addr_rule_phone'] = "정확한 핸드폰 번호를 입력하여 주세요";
$lang['check_addr_rule_reserve_num'] = "정확한 비상연락번호를 입력하여 주세요";
$lang['check_addr_rule_zip_code'] = "정확한 우편번호를 입력하여 주세요";

//支付宝解绑绑定
$lang['alipay_account_exists'] = "賬號已經存在";
$lang['alipay_account_input_again'] = "請再次輸入支付寶賬號";
$lang['alipay_account_not_same'] = "兩次輸入不一致";
$lang['alipay_account_not_empty'] = "支付寶賬號不能為空";

//重置邮箱

$lang['please_bind_email_first'] = "이메일을 면저 연동하세요.";
$lang['update_take_cash_pwd_error'] = "수정실패! 다시 시도하세요.";
$lang['email_code_not_nul'] = "인증번호를 비울수 없습니다.";
$lang['email_rule_error'] = '이메일 주소의 규격에 오류가 있습니다.';
$lang['please_get_code'] = '인증번호를 먼저 얻으십시오.';
$lang['new_passwd_not_null'] = '새비밀번호를 비울수 없습니다.';
$lang['new_passwd_rule'] = "새비밀번호는 반드시 6자리의 숫자여야 합니다.";
$lang['enter_re_passwd'] = "비밀번호를 다시 입력하세요.";
$lang['passwd_not_same'] = "두번 입력하신 비밀번호가 일치하지 않습니다.";
$lang['enter_tps_passwd'] = "로그인 비밀번호를 입력하세요.";
$lang['phone_code_rule_error'] = "인증번호가 규격에 맞지않습니다.";
$lang['phone_reset_passwd_success'] = "비밀번호 재설정 성공";
$lang['verify_code_tip3'] = "인증번호를 :email:로 전송하였습니다. ";
$lang['tps_login_pwd_reset'] = "TPS로그인 비밀번호 ";//未翻译
$lang['funds_pwd_new'] = "새로운 자금 암호 ";
$lang['new_takecash_passwd_again'] = "새PIN번호 확인";
$lang['new_takecash_passwd'] = "새 PIN번호";
$lang['verify_code'] ="인증번호";
$lang['tps_password_wrong'] ="TPS 로그인 비밀번호를 다시 확인하세요.";

//订单地址修改
$lang['order_status_not_allow'] = "주문 상태는 주소를 수정할 수 없습니다";
#新用户专属奖金
$lang['new_member_bonus'] = "신규 회원만의 특혜 보너스";
$lang['supplier_recommendation'] = "공급상 추천 보너스";
$lang['month_expense'] = "플랫폼 관리비";


//修改手机号
$lang['change_mobile'] = "휴대전화번호 수정";
$lang['new_mobile_not_null'] = "휴대전화번호를 비울수 없다.";
$lang['re_enter_new_mobile'] = "새휴대전화번호 확인";
$lang['not_match_your_input'] = "두번 입력하신 내용이 일치 하지않습니다.";
$lang['choose_edit_type'] = "수정방식을 선택하세요.";
$lang['verify_identify'] = "인증방식";
$lang['verify_new_mobile'] = "새휴대전화번호 인증하기";
$lang['verify_by_old_phone'] = "기존 휴대전화번호로 수정";
$lang['verify_by_email'] = "이메일으로 수정하기";
$lang['new_phone_rule_error'] = "휴대전화번호 격식에 오류가 있습니다.";
$lang['new_phone'] = "새휴대전화번호";
$lang['next_step'] = "다음";
$lang['new_phone_edit_successed'] = "휴대전화번호 수정 완료！";
$lang['resend_code_again'] = "다시 받기";

