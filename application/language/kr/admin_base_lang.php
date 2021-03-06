<?php
/****paypal提现****/
$lang["Receiver's account is invalid"]=' 무효된 수취인 계좌입니다. ';
$lang["Sender has insufficient funds"]='발송인 자금 부족';
$lang["User's country is not allowed"]='사용자 소재지 국가의 재한으로 사용 불가입니다. ';
$lang["User's credit card is not in the list of allowed countries of the gaming merchant"]='사용자의 신용카드는 상가 허용국가 리스트에 기재되지 않았습니다. ';
$lang["Cannot pay self"]='스스로 결제할수 없다. ';
$lang["Sender's account is locked or inactive"]='수취인의 계좌는 동결상태 혹은 무효입니다.';
$lang["Receiver's account is locked or inactive"]='수취인의 계좌는 동결상태 혹은 무효입니다.';
$lang["Either the sender or receiver exceeded the transaction limit"]='발송인 혹은 수취인의께서 거래한도를 넘었습니다. ';
$lang["Spending limit exceeded"]='지출 한도를 넘었습니다. ';
$lang["User is restricted"]='사용자 제한';
$lang["Negative balance"]='마이너스 잔액';
$lang["Receiver's address is in a non-receivable country or a PayPal zero country"]='수취인주소는 수취불가 국가나 PayPal허용하지 않은 국가입니다. ';
$lang["Invalid currency"]='화폐무효';
$lang["Sender's address is located in a restricted State (e.g., California)"]='발송인주소는 주의 제한을 받는다.（예로: 캘리포니아 주）';
$lang["Receiver's address is located in a restricted State (e.g., California)"]='수취인주소는 주의 제한을 받는다.（예로: 캘리포니아 주）';
$lang["Market closed and transaction is between 2 different countries"]='시장의 폐쇄 혹은 2국가간의 거래가 마감 되였다. ';
$lang["Internal error"]='PayPal 위험 통제 재한(내부오류)';
$lang["Zero amount"]='금액 제로';
$lang["Receiving limit exceeded"]='접수 제한을 초과하였습니다.';
$lang["Duplicate mass payment"]='대량으로 반복 결제 하였습니다. ';
$lang["Transaction was declined"]='거래가 거절당하였습니다. ';
$lang["Per-transaction sending limit exceeded"]='제한을 넘은 매개의 거래';
$lang["Transaction currency cannot be received by the recipient"]='수취인은 거래화폐를 받을수 없습니다.';
$lang["Currency compliance"]='준법화폐';
$lang["The mass payment was declined because the secondary user sending the mass payment has not been verified"]='대량의 결제의 이차적사용자께서 검증되지 않아 대량적결제 요구가 거절되였습니다.';
$lang["Regulatory review - Pending"]='감독 관리 심사- 대기중';
$lang["Regulatory review - Blocked"]='감독 관리 심사- 저지';
$lang["Receiver is unregistered"]='수신자 미등록';
$lang["Receiver is unconfirmed"]='수신자 미확인';
$lang["Youth account recipient"]='청년계좌 수취인';
$lang["POS cumulative sending limit exceeded"]='POS누적 발송이 제한을 넘었습니다.';
$lang['submit_paypal'] = 'Paypal처리 제출';
$lang['paypal_withdrawal_list'] = 'Paypal인출 리스트';
$lang['batch_modification'] = "'처리 됨' 상태로 대량 수정 하였습니다.";
$lang['Paypal Account number'] = "Paypal계정";
$lang['Delete_user'] = "该ID存在资金变动记录，将转为公司预留账户";
$lang['paypal_pending_log'] = '페이팔 환불 상태 주문 내역';
$lang['paypal_pending_ts'] = '당신 확인 주문이 처리되어 있습니까!';
$lang['paypal_pending_cl'] = '처리';
/** 黑名单列表 */
$lang['add_blacklist'] = '필터링 추가';
$lang['blacklist'] = '필터링 리스트';
$lang['blacklist_ex'] = '필터링';
$lang['enter_blacklist'] = '필터링을 입력하세요.';
$lang['user_up_time'] = '用户级别升级时间';
$lang['admin_user_rank'] = '用户级别';
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
$lang['btn_check'] = '正向检测';
$lang['btn_f_check'] = '反向检测';
$lang['transfer_contr'] = '转账监控';

//补发佣金信息
$lang['admin_show_user_monthly'] = "是否显示用户业绩&nbsp;&nbsp;(默认显示最近3个月的数据)";
$lang['admin_show_day_bonsu_monthly'] = "显示每天全球利润分红队列信息";
$lang['admin_show_week_bonsu_monthly'] = "显示每周团队销售分红队列信息";
$lang['admin_show_month_bonsu_monthly'] = "显示每月团队销售分红队列信息";
$lang['admin_day_bonus_list'] = "每天全球利润分红队列";
$lang['admin_week_bonus_list'] = "每周团队销售分红队列";
$lang['admin_month_bonus_list'] = "每月团队销售分红队列";

/**会员中心*/
$lang['admin_exchange_user_email_title'] = '이메일주소 수정';
$lang['admin_exchange_user_mobile_title'] = '휴대전화번호 수정';
$lang['admin_exchange_user_info_content'] = '수정정보를 확인 하시나요?';
$lang['admin_remark_input_not_null'] = '비고정보를 비울수 없습니다！';
$lang['admin_remark_option_name'] = '실행자';
$lang['admin_remark_option_time'] = '비고시간';
$lang['user_order_achievement_repair'] = '订单延迟业绩修复';
/***奖金类型**/
$lang['pre_week_team_bonus'] = '周团队组织分红奖';
$lang['pre_month_team_bonus'] = '月团队组织分红奖';
$lang['pre_month_leader_bonus'] = '月领导组织分红奖';
$lang['pre_amount_bonus'] = '发放金额（单位：美元）';
$lang['user_sale_up_time'] = '职称升级时间';
$lang['user_achievement_edit'] = '批量修复业绩';

$lang['pre_bonus_submit'] = '重新预发奖';
$lang['pre_bonus_submit_note'] = '注：点击此按钮，将重新生成预发奖，可能会影响到实际发奖，操作时，请及时联系技术人员.';
$lang['title_level_0'] = '店主(SO)';
$lang['title_level_1'] = '资深店主(MSO)';
$lang['title_level_2'] = '市场主管(MSB)';
$lang['title_level_3'] = ' 高级市场主管(SMD)';
$lang['title_level_4'] = '市场总监(EMD)';
$lang['title_level_5'] = '全球市场销售副总裁(GVP)';
$lang['user_achievement_note'] = '注：此功能批量修复会员业绩，请按照如下图所示的格式，存放用户id.';

/***冻结账号**/
$lang['frost_user_time'] = "동결 시간";
$lang['day'] = "일";
$lang['frost_forever'] = "장기 동결";
$lang['please_select_frost_time'] = "동결 시간 선택";
$lang['pre_day_bonus'] = '日分红奖';
$lang['pre_new_user_bonus'] = '新会员专项奖';
$lang['develop_msg'] = '开发者管理';
/**会员统计*/
$lang['user_error_total'] = '错误数';
$lang['admin_store_statistics_total'] = '통계';
$lang['admin_store_statistics_datetime'] = '기일';
$lang['admin_user_level_f'] = '프리급';
$lang['admin_user_level_b'] = '브론즈급';
$lang['admin_user_level_s'] = '실버급';
$lang['admin_user_level_g'] = '골드급';
$lang['admin_user_level_p'] = '다이아몬드급';
$lang['admin_user_level_t'] = '합계(SUM)';
$lang['admin_everyday_level_t'] = '매일 통계(SUM)';
$lang['admin_everyday_level_count_t'] = '총 가입회원 인원수：';
$lang['admin_everyday_level__t'] = '유료회원 인원수：';
$lang['admin_month_level_t'] = '월 통계';
/**公告*/
$lang['admin_board_title_not_null'] = '제목을 비울수 없습니다！';
$lang['admin_board_conteng_not_null'] = '公공지 내용을 비울수 없습니다！';
$lang['admin_board_english_title_err'] = '영어 제목을 비울수 없다！';
$lang['admin_board_chinese_title_err'] = '간체 중국어 제목을 비울수 없다！';
$lang['admin_board_hk_title_err'] = '번체 중국어 제목을 비울수 없다！';
$lang['admin_board_kr_title_err'] = '한국어 제목을 비울수 없다！';
$lang['admin_board_en_content_err'] = '영어 내용을 비울수 없다！';
$lang['admin_board_zh_content_err'] = '간체 중국어 내용을 비울수 없다！';
$lang['admin_board_hk_content_err'] = '번체 중국어 내용을 비울수 없다！';
$lang['admin_board_kr_content_err'] = '한국어 내용을 비울수 없다！';

/*MVP颁奖报名名单*/
$lang['mvp_apply_list'] = 'MVP颁奖报名名单';
$lang['mvp_professional_title'] = '职称';
$lang['mvp_apply_time'] = '报名时间';
$lang['mvp_apply_number'] = '报名号';
$lang['users_amount_check'] = '提现用户转账记录检测';
$lang['users_check_btn'] = '检测';

//mvp直播授权订单
$lang['mvp_live_list'] = 'MVP直播授权订单';
$lang['pls_input_luyan_account'] = '请输入第一路演平台帐号';
$lang['luyan_account'] = '第一路演平台账号';
$lang['mvp_pay_amount'] = '支付金额';
$lang['third_party_order_id'] = '第三方交易号';

/** 售后订单业务流程 */
$lang['admin_exchange_rate_error'] = '환율 규칙에 오류가 있습니다.';
$lang['admin_not_demote_tip'] = '브론즈급、실버급 몰은 레벨감등을 진행할수 없습니다.';
$lang['admin_go_process'] = 'Go Process';
$lang['admin_check_pass'] = '심사통과';
$lang['admin_upload_return_fee'] = '운송비반환 A/S주문 업로드하기';
$lang['admin_return_fee_tip1'] = '주문 상태 이상';
$lang['admin_return_fee_tip2'] = '주문배송료는 환불금액보다크다.';
$lang['admin_return_fee_tip3'] = '배송료반환주문서는 중복 제출 할수 없다.';
$lang['admin_return_fee_tip4'] = '주문의 고객은 이미 탈회신청 하였습니다.';
$lang['admin_refund_amount'] = '주문배송료반환:$%s';
$lang['admin_add_after_sale'] = 'A/S주문 명세서 새로만들기';
$lang['admin_after_sale_id'] = 'A/S주문 명세서 번호';
$lang['admin_after_sale_uid'] = '연관회원ID';
$lang['admin_after_sale_name'] = '연관회원이름';
$lang['admin_after_sale_type'] = 'A/S상관유형';
$lang['admin_after_sale_type_0'] = '탈회';
$lang['admin_after_sale_type_1'] = '감등';
$lang['admin_after_sale_type_2'] = '배송료 반환';
$lang['admin_after_sale_method'] = '환불 지급 방식';
$lang['admin_after_sale_method_0'] = '계좌 이체';
$lang['admin_after_sale_method_1'] = '캐시버킷으로 대체';
$lang['admin_after_sale_method_2'] = 'Alipay의 전송';
$lang['admin_after_sale_amount'] = '환불금액';
$lang['refund_amount_error'] = '환불금액은 비우거나 0일수 없다.';
$lang['admin_after_sale_remark'] = '피드백 내용';
$lang['admin_after_sale_remark_error'] = '피드백 내용을 비울수 없습니다.';
$lang['admin_after_sale_remark_example'] = '회원님은 레벨 골드몰로 부터 레벨 실버몰로 낮추는 요청을 하였습니다.';
$lang['admin_add_after_sale_list'] = 'A/S관리';
$lang['admin_after_sale_demote'] = '감등 레벨';
$lang['admin_after_sale_status_0'] = '처리 대기 중';
$lang['admin_after_sale_status_1'] = '회수 하였습니다.(은행에 결제 대기 중입니다.)';
$lang['admin_after_sale_status_2'] = '회수 하였습니다.(이미 캐시버킷으로 환불하였습니다.)';
$lang['admin_after_sale_status_3'] = '이미 은행으로 환불하였습니다.';
$lang['admin_after_sale_status_4'] = '회수거절';
$lang['admin_after_sale_status_5'] = '환불거절';
$lang['admin_after_sale_status_6'] = '폐기';
$lang['admin_after_sale_status_7'] = '입력 되였습니다.';
$lang['admin_as_upgrade_order'] = '주문 업그레이드';
$lang['admin_as_consumed_order'] = '소비주문';
$lang['admin_as_refund'] = '환불처리';
$lang['admin_as_not_exist'] = 'A/S주문번호 존제하지 않습니다.';
$lang['admin_as_status_error'] = '사용자의 상태는 회사계정 입니다.실행할수 없습니다.';
$lang['admin_as_view_remark'] = '살펴보기/추가';
$lang['admin_as_action_log'] = 'A/S처리실행기록표';
$lang['admin_as_update'] = '수정';
$lang['admin_as_payee_no_exist'] = '수취인 회원ID존제하지 않습니다.';
$lang['admin_after_sale_amount_error'] = '감등할 화불금액의 서식이 정확하지않습니다.';
$lang['admin_after_sale_submit'] = '회수 신청';
$lang['admin_after_sale_repeat'] = '이 회원의 A/S탈회주문은 이미 신청 하였습니다.';
$lang['admin_after_sale_demote_info'] = '이 회원의 A/S탈회주문은 처리중  입니다.';
$lang['admin_email_or_id'] = '관리인ID / 이메일';
$lang['admin_as_upload_info'] = '수령증 업로드';
$lang['admin_as_del_upload_info'] = '수령증 삭제';
$lang['admin_after_sale_coupons'] = '주의：삭감한 바우처는 감등하는데 부족합니다. 부분 환불금액은 바우처의 부족한 부분 대신해 사용됩니다.';
$lang['is_grant_generation'] = '팀 매출 커미션 발급';
$lang['admin_after_sale_brank_name'] = '开户行不能输入特殊字符，不能为空，最多能输入50个中文字。';
$lang['admin_after_sale_brank_num'] = '银行账户只能输入数字，不能为空，最多能输入50个数字。';
$lang['admin_after_sale_brank_pop'] = '开户名只能输入数字，不能为空，最多能输入50个数字。';

/** 导入运单号 */
$lang['admin_no_lock'] = '잠기지 않은 주문';
$lang['admin_yes_lock'] = '이미 짐긴 주문';
$lang['admin_select_is_lock'] = '도출주문 잠그기';
$lang['admin_order_lock'] = '주문이 잠겨져 있어 변경할수 없습니다.';
$lang['admin_file_format'] = '지원되지 않은 파일포맷입니다.';
$lang['admin_file_data'] = '파일 데이터가 비여있습니다.';
$lang['admin_file_not_full'] = 'EXCEL중 제%s행의 정보가 불완전합니다.';
$lang['admin_file_order_status'] = '%s주문상태자 맞지 않습니다.';
$lang['admin_file_not_freight'] = '%s물류번호가 존제하지 않는다.';
$lang['admin_select_file'] = '업로드 하실EXCEL파일을 선택하세요.';
$lang['admin_request_failed'] = '요청이 실패했슴니다.';
$lang['admin_upload_freight'] = '송장 번호 업로드';
$lang['admin_scan_shipping'] = '배송 스캔';
$lang['admin_scan_order_id'] = '주문ID 스캔';
$lang['admin_scan_track_id'] = '송장 번호 스캔';
$lang['admin_export_orders'] = '주문서 도출';
$lang['admin_download_model'] = '양식 다운로드';
$lang['admin_ship_note_type'] = '발송유형비고란';
$lang['admin_ship_note_type1'] = 'N일후';
$lang['admin_ship_note_type2'] = '지정기일';
$lang['admin_ship_note_val'] = '발송 일자';
$lang['admin_ship_note_val_eg'] = '예를 들어:5혹은 2015/11/11';
$lang['add_admin'] = '계정추가';
$lang['order_report'] = '주문명세보고표';
$lang['store_report'] = '몰 보고표';
$lang['order_status_3'] = '발송 대기 중';
$lang['order_status_4'] = '발송했습니다.';
$lang['order_status_5'] = '货款结算';
$lang['admin_order_status_holding'] = '당분간 중지';
$lang['refund_card_number'] = '환불인 등록증';
$lang['transfer_card_number'] = '양도임 등록증';
$lang['receive_card_number'] = '수신인 등록증';
$lang['receive_email'] = '수신인 메일';
$lang['transfer_1'] = '양도';
$lang['transfer_2'] = '환불';
$lang['china_weight_fee'] = '중국 상품 배송료';
$lang['usa_weight_fee'] = '미국 상품 배송료';
$lang['shipping_com'] = '물류회사';
$lang['first_line_format_error'] = '첫째 줄 형식 다르다';
$lang['upload_big_excel_error'] = '업로드 너무 커서 좀 분할. 여러 개의 파일을 업로드 진행하다';
$lang['upload_excel_fail'] = '업로드 실패';

/** 支付方式 */
$lang['pay_name'] = '결제명칭';
$lang['pay_desc'] = '결제묘사';
$lang['pay_currency'] = '결제화페';
$lang['payment_list'] = '결제방식';
$lang['edit_payment'] = '결제편집';
$lang['is_enabled'] = '사용하기 시작하시 겠습니까?';
$lang['not_enabled'] = '아니요';
$lang['yes_enabled'] = '예';
$lang['yspay_username'] = 'yspay_username';
$lang['yspay_merchantname'] = 'yspay_merchantname';
$lang['yspay_pfxpath'] = 'yspay_pfxpath';
$lang['yspay_pfxpassword'] = 'yspay_pfxpassword';
$lang['yspay_certpath'] = 'yspay_certpath';
$lang['yspay_host'] = 'yspay_host';
$lang['unionpay_merId'] = 'unionpay_merId';
$lang['unionpay_pfxpath'] = 'unionpay_pfxpath';
$lang['unionpay_pfxpassword'] = 'unionpay_pfxpassword';
$lang['unionpay_certpath'] = 'unionpay_certpath';
$lang['unionpay_host'] = 'unionpay_host';
$lang['paypal_account'] = 'paypal_account';
$lang['paypal_submit_url'] = 'paypal_submit_url';
$lang['paypal_host'] = 'paypal_host';
$lang['ewallet_key'] = 'ewallet_key';
$lang['ewallet_password'] = 'ewallet_password';
$lang['ewallet_host'] = 'ewallet_host';
$lang['ewallet_login'] = 'ewallet_login';
$lang['alipay_account'] = 'alipay_account';
$lang['alipay_key'] = 'alipay_key';
$lang['alipay_partner'] = 'alipay_partnerID';

$lang['old_month'] = '(구)호스팅비 버킷';
$lang['user_not_free'] = '이용자는 꼭 프리이용자 여야하며 지점이 없어야 합니다.';
$lang['delete_free_user'] = '프리이용자 삭제';
$lang['half_year_exe'] = '6개월이네로 이 조작을 사용할수 없습니다.';
$lang['new_month'] = '호스팅비 버킷 잔액';
$lang['money_update'] = '변동 금액';
$lang['month_type_1'] = '충전';
$lang['month_type_4'] = '호스팅비(월 관리비)납부';
$lang['action_charge_month'] = '호스팅비 상쇄 이벤트';
$lang['join_action_charge_month'] = '회원님께서는 이미 호스팅비 상쇄 이벤트에 참여하였습니다.';
$lang['action_charge_month_tip'] = '알림:호스팅비 상쇄 이벤트의 주문은 취소/환불 할수 없습니다.';
$lang['monthly_fee_detail'] = '호스팅비(월 관리비)명세서';
$lang['is_withdrawal'] = '몰주인이 형금 인출을 진행하여 환불이 안됩니다.';
$lang['is_transfer'] = '몰주인이 계좌이체를 진행하여 환불이 안됩니다.';



$lang['update'] = '수정';
$lang['clear'] = '전자지갑 계정 삭제';
$lang['update_success'] = '수정 성공';
$lang['update_failure'] = '갱신 실패';
$lang['reject'] = '기각(거절)';
$lang['no_operate'] = '조작할 수 없다.';
$lang['all'] = '전부선택';
$lang['reapply'] = '취소후 다시 인출 신청 하세요.';
$lang['status_title'] = '몰 상태';
$lang['status_0'] = '비활성화';
$lang['status_1'] = '활성화';
$lang['status_2'] = '휴면';
$lang['status_3'] = '일시정지(동결)';
$lang['status_4'] = '회사몰';
$lang['cash_withdrawal_list'] = '인출 금액 수동 처리';
$lang['withdrawal_cash'] = '인출 금액';
$lang['withdrawal_type'] = '인출 유형';
$lang['withdrawal_account'] = '인출 계정';
$lang['tps_manually'] = '수동TPS';
$lang['tps_paid'] = '이미 수동 지불 하였습니다.';
$lang['tps_status_0'] = '미 해결';
$lang['tps_status_1'] = '해결 완료';
$lang['tps_status_2'] = '해결 중';
$lang['tps_status_4'] = '취소됨';
$lang['sure'] = '확인？';
$lang['sure_delivery'] = '이 주문의 제품을 받으신걸 확인 하시겠습니까？';

$lang['lifecycle'] = '계정 상황';
$lang['no_title'] = '뉴스제목을 입력하세요.';
$lang['no_source'] = '뉴스출처를 입력하세요.';
$lang['no_content'] = '뉴스내용을 입력하세요.';
$lang['no_img'] = '뉴스사진을 업로드 하세요.';
$lang['news_img'] = '뉴스사진 업로드';
$lang['required'] = '입력필수';
$lang['hot_news'] = 'hot뉴스';
$lang['sort'] = '배열높이';
$lang['sort_exist'] = '이미 존제한 배열숫자입니다. ';
$lang['display'] = '표시 여부';
$lang['no_display'] = '표시하지 않음';
$lang['important_title'] = '중요';
$lang['need_display'] = '표시';
$lang['order_search'] = '업그레이드 주문 조회';
$lang['upgrade_order'] = '제품세트 주문';
$lang['upgrade_month_order'] = '업그레이드 호스팅비(월 관리비)주문';
$lang['month_fee_order'] = '호스팅비 충전 주문';
$lang['txn_id'] = '거래번호';
$lang['order_sn'] = '주문번호';
$lang['payment'] = '결제방식';
$lang['pay_time'] = '결제시간';
$lang['unpaid'] = '미결제';
$lang['paid'] = '결제완료';
$lang['usd_money'] = '달러($)';

$lang['news_lang']='소속언어';
$lang['news_cate']='소속분류';
$lang['news_ok']='분류첨첨가';
$lang['news_cate_name']='뉴스공지';
$lang['news_cate_need']='분류명칭을 입력하세요.';

$lang['approve'] = '통과';
$lang['refuse'] = '미통과';
$lang['pending'] = '심사 대기 중';
$lang['refuse_reason'] = '기각(거절)원인';
$lang['action'] = '조작';
$lang['reset_password']='비밀번호 수정';
$lang['check_card'] = '주민등록증 심사';
$lang['check_card_id'] = '주민등록번호';
$lang['new_card_number'] = '새로운 주민등록번호';
$lang['scan'] = '스캔본';
$lang['scan_back'] = '스캔본 뒤면';
$lang['create_time'] = '창설시간';
$lang['check_status'] = '심사상태';

/** Add by Andy*/
$lang['check_admin'] = '심사 사람';
$lang['check_time'] = '심사 시간';
$lang['relive_ban'] = '해제금지';
$lang['relive_success'] = '해제성공';
$lang['relive_fail']    = '해제실패';

$lang['news_manage'] = '뉴스관리';
$lang['bulletin_board_list'] = '공고란 리스트';
$lang['add_bulletin_board'] = '공고란 첨가';
$lang['add_news'] = '뉴스첨가';
$lang['title'] = '뉴스 제목';
$lang['source'] = '뉴스 출처';
$lang['content'] = '내용';
$lang['news_list'] = '뉴스 리스트';
$lang['news_type'] = '유형';

$lang['tps138_admin'] = 'TPS백 관리';
$lang['user_management'] = '회원 관리';
$lang['user_list'] = '회원 리스트';
$lang['user_info'] = '회원 상세 정보';
$lang['view_detail_info'] = '자세한 정보 살펴보기';
$lang['status'] = '상태';
$lang['not_enable'] = '비활성화';
$lang['enabled'] = '정상';
$lang['sleep'] = '휴면';
$lang['account_disable'] = '계정일시정지(동결)';
$lang['company_keep'] = '회사 리저브더워드 계정';
$lang['month_fee_date'] = '호스팅비(월관리비) 일';
$lang['day'] = '일';
$lang['day_th'] = '일';
$lang['admin_account_manage'] = '계정 관리';
$lang['admin_account_list'] = '백계정 리스트';
$lang['role'] = '역할';
$lang['role_super'] = '관리자';
$lang['role_customer_service'] = '고객 서비스';
$lang['role_customer_service_lv1'] = '고객 서비스-lv1';
$lang['role_customer_service_lv2'] = '고객 서비스-lv2';
$lang['role_customer_service_manager'] = '고객 서비스 매니저';
$lang['operations_personnel'] = '운영';
$lang['role_storehouse_korea'] ='주문을 도출하여 발송하기（대한민국）';
$lang['role_storehouse_hongkong'] ='주문을 도출하여 발송하기（HongKong）';
$lang['financial_officer'] = '제무';
$lang['account_disable'] = '동결(일시정지)';
$lang['account_reenable'] = '해동';
$lang['account_disable_z'] = '정상동결';
$lang['account_reenable_z'] = '정상동결해제';
$lang['account_disable_m'] = '호스팅비미납동결';
$lang['account_reenable_m'] = '호스팅비미납동결해제';
$lang['account_enable'] = '활성화';
$lang['enable_store_level'] = '몰레벨 활성화';
$lang['upgrade_store_level'] = '몰레벨 업그레이드';
$lang['upgrade_user_manually'] = '수동으로 회원레벨 업그레이드';
$lang['user_id'] = '사용자id';
$lang['please_sel_level'] = '레벨을 선택하세요.';
$lang['user_id_list_requied'] = '사용자id를 입력하세요.';
$lang['month_fee_or_user_rank_requied'] = '월 호스팅비레벨 혹은 몰레벨을 선택하세요.';
$lang['submit_success'] = '제출성공';
$lang['upgrade_success_num'] = '이미%s 명의 회원을 처리되었습니다.';
$lang['upgrade_no_num'] = '유효한 회원id가 없습니다.';
$lang['user_ids_notice'] = '몇개의 id는 쉼표“,”로 갈라놓아야 합니다.';
$lang['no_permission'] = '권한이 없습니다.';
$lang['resert_user_status'] = '恢复用户状态';
$lang['signouting'] = '退会中';
$lang['signouting_not_accounts'] = '할 수 있게 중 이체';
$lang['signouting_not_withdrawals'] = '할 수 있게 중 현금을 인출하다';
$lang['signouting_not_pay'] = '현금 지불 할 수 있게 중 연못';
$lang['admin_user_account_disabled_hint'] = '输入密码错误次数过多，账号已被锁定。如需帮助，请联系组长经理。';
$lang['admin_user_login_pwd_error'] = '您的密码有误，错误超过3次将锁定账户';

/*佣金*/
$lang['commission_admin'] = '커미션 관리';
$lang['commission_add_or_reduce'] = '커미션 변동';
$lang['pls_input_amount'] = '금액을 입력하세요';
$lang['amount_condition'] = '금액은 꼭 0보다 큰 수치여야하며 소수일시 소수점후 2자리까지 남겨야한다.';
$lang['why'] = '원인';
$lang['pls_input_reson'] = '원인을 입력하세요.';
$lang['amount_limit'] = '金额限制';

$lang['unbundling'] = '연동해제';
$lang['unbundling_success'] = '연동해제 성공';
$lang['unbundling_fail'] = '연동해제 실패';
$lang['will_unbinding'] = '연동해제를 진행하고 있습니다.';

/*佣金特别处理*/
$lang['commission_month_sum'] = '당월 보너스 합계';
$lang['commission_month'] = '회원 월 커미션 통계';
$lang['commission_special_do'] = '보너스 특별처치';
$lang['add_in_qualified_list'] = '加入当月发奖列队';
$lang['pls_sel_comm_item'] = '请选择奖项';
$lang['pls_input_right_uid'] = '请输入正确的用户id。';
$lang['fix_user_commission'] = '补发会员奖金';
$lang['pls_sel_date'] = '请选择日期';
$lang['date_error_over_today'] = '结束日期不能大于今天';
$lang['month_date_error_over_today'] = '월 우수 몰 배당 보너스는 매월 15일에 지급되며 날짜 선택시 15일을 포함하시기 바랍니다';
/*月费池管理*/
$lang['monthfee_pool_admin'] = '호스팅비 버킷 관리';
$lang['monthfee_pool_add_or_reduce'] = '호스팅비 버킷 변동';

//team
$lang['enter'] = '사용자의 ID를 입력 후 Enter키를 누르세요.';
$lang['no_exist'] = '회원ID는 존제하지 않는다.';
$lang['join_matrix_time'] = '매트릭스에 가입시간';
$lang['buy_product_time'] = '제품세트 구매시간';

/*优惠券*/
$lang['coupon'] = '쿠폰';

/*会员列表*/
$lang['search_notice'] = 'ID / 이메일/ 이름';

/*提现申请列表*/
$lang['generate_batch'] = '차수 생성하기';
$lang['generate_time'] = '생성시간';
$lang['process_time'] = '처리시간';
$lang['generate_batch_num']='일괄 처리 파일넘버 생성하기';
$lang['total_items']='총 건수';
$lang['total_money']='총 금액';
$lang['payment_reason']='지급 원인';
$lang['commission_special_check'] = '补发奖金计算';

$lang['number_hao'] = '시리얼 넘버';
$lang['fee_num'] = '수수료($)';
$lang['the_actual_amount'] = '실제입금 금액($)';
$lang['paypal_account_t'] = '알리페이 계정';
$lang['paypal_status'] = '알리페이 상태';
$lang['payee_name']='수취인 성명';
$lang['application_time'] = '신청시간';
$lang['money_num']='금액($)';
$lang['exchange_rate']="환율";
$lang['criticism_num']="차수";
$lang['batch_number']="차수 넘버";
$lang['operation']="조작";
$lang['export_EXCEL']="EXCEL도출";
$lang['process_result']='처리결과';
$lang['result_ok']='성공';
$lang['result_false']='실패';
$lang['unselected']='현금인출 신청을 선택하지 않았습니다.';
$lang['unselected_reason']='원인을 선택하지 않았습니다. ';
$lang['batch_error']='선택항에 차수가 존재합니다. 다시 선택하세요.';
$lang['reject_confirm']='현금 인출 기각원인을 기입하세요. ';
$lang['cause']='원인';
$lang['view_batch']='차수조회';
$lang['alipay_withdraw'] = 'Alipay인출 리스트';
$lang['bank_withdraw'] = '인출 리스트';
$lang['payment_interface'] = '그리고 지불 인터페이스';
$lang['payment_type_1'] = 'CUP';
$lang['payment_type_2'] = 'TenPay';
$lang['payment_type_3'] = '를 지불 빠른';
$lang['payment_type_4'] = '위안 지급';
$lang['submit_pay_tyep'] = '지불을 제출하고 지불';
$lang['batch_xq'] = '차수 디테일';
$lang['process']='조회';
$lang['cancel_batch']='차수 취소';
$lang['submit_alipay']='알리페이 제출';
$lang['total_items_ts']='현재 페이지 건수：%s 건';
$lang['total_money_ts']='인출 총 금액：$%s';
$lang['fee_num_ts']='수수료 총액：$%s';
$lang['fee_num_ts2']='수수료';
$lang['the_actual_amount_ts']='실제 입금 총 금액：$%s';
$lang['please_choose']='선택하세요.';
$lang['choose1']='상품 대금';
$lang['choose2']='배송비';
$lang['choose3']='식비';
$lang['choose4']='판매 커미션';
$lang['status_n1']='처리 대기중';
$lang['status_n2']='처리중';
$lang['status_n3']='처리완성';
$lang['cancel_confirm']='차수 취소 제시';
$lang['double_confirm']='이번 차수 취소를 확인하시겠습니까?';


/*清空用户账户信息*/
$lang['clear_member_account_info'] = '사용자 계정 양도';
$lang['new_email'] = 'new Email';
$lang['new_password_note'] = '이 계정의 초기 비밀번호는 ：%s';

//矩阵管理
$lang['matrix_manage']='매트릭스 관리';
$lang['change_2x5_position']='2x5매트릭스 위치 바꾸기';

$lang['matrix_alert1']='User ID 를Parent ID 밑으로 보속시키다.';
$lang['matrix_alert2']='UserID1 와UserID2의 위치를 바꾸다.';

$lang['card_notice'] = 'ID / 이름';
$lang['total_money'] = '총 금액';

/***月费转现金池***/
$lang['month_fee_to_amount'] = '호스팅비를 캐시 버킷으로 이체 ';
$lang['user_id'] = '사용자ID';
$lang['month_fee'] = '호스팅비 버킷';
$lang['amount_'] = '캐시 버킷';
$lang['to'] = '전입';
$lang['confirm'] = '확인';
$lang['user_not_exits'] = 'ID 가 존제하지 않는다.';
$lang['cash_not_null'] = '금액은 비울수 없다.';
$lang['max_cash'] = '최대전입수：';
$lang['id_not_null'] = 'ID 를 비울수 없다.';
$lang['month_fee_error'] = '0보다 큰 금액을 입력하세요.(소수점 뒤 2자리까지)';
$lang['not_bigger'] = '한도를 초과할수 없습니다.';
$lang['transfer_to_success'] = '전입성공';
$lang['transfer_to_fail'] = '전입실패';


/**2x5佣金补偿**/
$lang['commission_2x5'] = '2x5커미션 관리';
$lang['please_input_user_id'] = '커미션을 수납할ID';
$lang['please_input_pay_user_id'] = '커미션을 지급할ID';
$lang['confirm'] = '확인';



$lang['alert_commission_compensation_ok'] = '커미션 보상 성공,1명의 회훤에 영향을 받게 하였습니다. ';
$lang['alert_commission_compensation_fail'] = '0명의 회훤에 영향을 받게 하였습니다. ';
$lang['alert_commission_compensation_noExits'] = '커미션지급의 ID는 아직배열에들어있지않습니다.';
$lang['alert_commission_compensation_notNull'] = 'ID 비울수 없다.';

$lang['select_order_repair_date'] = '추가주문 년/월을 선택';
$lang['select_comm_order_type']   = '커미션 추가주문 종류을 선택';
$lang['pls_select_order_year']    = '추가주문 년/월을 선택하세요.';
$lang['pls_select_comm_order_type'] = '커미션 추가주문 종류을 선택하세요.';
$lang['add_comm_success'] = '커미션 추가주문 성공';
$lang['add_comm_fail'] = '추가주문할 필요가 없습니다.';
$lang['cur_month_add_comm_ban'] = '이 달에 추가주문을 진행할수 없습니다. ';
$lang['comm_tips'] = '제시';
$lang['you_will_add_a_comm'] = '커미션 추가주문 한건이 추가됩니다:';
$lang['add_comm_year_month'] = '추가주문 년/월';
$lang['comm_order_type'] = '커미션 추가주문 종류';
$lang['need_add_comm_mount'] = '추가주문할 금액';

$lang['commission_forget'] = '커미션 지급에서 빠지다.';
$lang['commission_error'] = '커미션 틀리게 지급';
$lang['commission_repeat'] = '커미션 중복';

$lang['reason'] = '-----원인-----';

$lang['see_user_back_office'] = '사용자백 살펴뵤기';

/* 产品管理相关  */
$lang['goods_doba_import']='DOBA제품csv들여오기';
$lang['brand_exists']='브랜드명칭은 이미 존재하여있습니다.';
$lang['cate_exist']='분류명칭은 이미 존재하여있습니다.';
$lang['goods_manage']='제품관리';
$lang['add_category']='분류추가';
$lang['category_list']='분류관리';
$lang['add_goods']='상품추가';
$lang['goods_list']='상품 리스트';
$lang['ads_list']='광고관리';
$lang['ads_add']='광고추가';
$lang['edit_ads']='광고편집';
$lang['goods_group_list']='상품 세트 리스트';
$lang['edit_category']='분류편집';
$lang['edit_goods']='상품편집';

$lang['label_ads_img']='광고사';
$lang['label_ads_sort']='보여줄 순서';
$lang['label_ads_url']='광고링크';
$lang['label_ads_status']='나타나는 상태';
$lang['label_ads_lang']='과고어종';
$lang['label_ads_location']='보여주는 위치';

$lang['label_goods_group_add']='세트추가';
$lang['label_goods_group_edit']='세트편집';
$lang['label_goods_group_search']='삼품검색';
$lang['label_goods_group_ok']='검색';
$lang['label_goods_group_keywords']='상품키워드';
$lang['label_goods_group_num']='수량';
$lang['label_goods_group_id']='세트ID';
$lang['label_goods_group_content']='세트내용';

$lang['label_brand_list']='브랜드관리';
$lang['label_brand_add']='브랜드추가';
$lang['label_brand_name']='브랜드명칭';
$lang['label_brand_id']='브랜드ID';
$lang['label_language']='소속언어';
$lang['label_language_all']='소속어종';
$lang['label_brand_list_m']='브랜드목록';

$lang['label_cate_parent']='상급분류';
$lang['label_cate_name']='분류명칭';
$lang['label_cate_sn']='분류SN';
$lang['label_cate_desc']='분류서술';
$lang['label_cate_icon']='부류ICON';
$lang['label_cate_sort']='분류소팅';
$lang['label_cate_meta_title']='SEO타이틀';
$lang['label_cate_meta_keywords']='SEO키워드';
$lang['label_cate_meta_desc']='SEO서술';
$lang['label_cate_top']='최고급분류';

$lang['label_goods_display_state']='해당 어종 나타내다.';
$lang['label_goods_cate']='소속분류';
$lang['label_goods_shipper']='송하인';
$lang['label_goods_shipper_sel']='모든송하인';
$lang['label_goods_brand']='소속브랜드';
$lang['label_goods_effect']='소속스타일';
$lang['label_goods_name']='상품명칭';
$lang['label_goods_name_cn']='상품명칭（중국어）';
$lang['label_goods_main_sn']='상품SKU';
$lang['label_goods_img']='상품사진(250*250)';
$lang['label_is_change_width']='줌인하지 않음';
$lang['label_goods_sku']='부속SKU창설';
$lang['label_goods_img_gallery']='상품앨범사진';
$lang['label_goods_img_detail']='상품명세사진';
$lang['label_goods_stock']='재고수';
$lang['label_goods_warn']='조기경보수';
$lang['label_goods_weight']='상품무게（kg）';
$lang['label_goods_bulk']='부피';
$lang['label_goods_purchase_price']='매입가(usd)';
$lang['label_goods_market_price']='시장가(usd)';
$lang['label_goods_shop_price']='판매가(usd)';
$lang['label_goods_is_promote']='할인판매하시겠습니까?';
$lang['label_goods_promote_start']='할인 시작시간';
$lang['label_goods_promote_end']='할인 끝날시간';
$lang['label_goods_promote_price']='힐인적용가';
$lang['label_goods_sale']='판매하기';
$lang['label_goods_unsale']='판매취소';
$lang['label_goods_looking']='심사중';
$lang['label_goods_delete']='삭제';
$lang['label_goods_best']='추천';
$lang['label_goods_new']='신상품';
$lang['label_goods_hot']='베스트';
$lang['label_goods_home']='홈페이지 전시';
$lang['label_goods_ship']='무료배송';
$lang['label_goods_24']='24시간발송';
$lang['label_goods_voucher']='바우처구매 가능';
$lang['label_goods_alone_sale']='단품';
$lang['label_goods_group_sale']='세트';
$lang['label_goods_group_sale_upgrade']='업그레이드세트';
$lang['label_goods_for_upgrade']='업그레이드에 사용';
$lang['label_goods_group_sale_ids']='세트ID';
$lang['label_goods_note']='상품비고（부속설명）';
$lang['label_goods_note1']='빨간글로 특수 비고';
$lang['label_goods_store']='소속창고';
$lang['label_goods_add_user']='첨가하는 자';
$lang['label_goods_update_user']='엡데이트하는 자';
$lang['label_goods_add_time']='첨가 시간';
$lang['label_goods_update_time']='업데이트 시간';
$lang['label_goods_sort']='정렬';
$lang['label_goods_desc']='자세사항 묘사(문자 묘사)';
$lang['label_goods_desc_pic']='자세사항 묘사(long사잔)';
$lang['label_goods_sale_country']='판매국가';
$lang['label_yes']='예';
$lang['label_no']='아니요';
$lang['label_sub_sn']='상품SKU';
$lang['label_color']='상품칼라';
$lang['label_size']='상품규격';
$lang['label_customer']='자체 정의';
$lang['label_sel_store']='모든 창고';
$lang['label_sel_store_third']='공금업자 창고';
$lang['label_sel_cate']='모든 분류';
$lang['label_sel_status']='모든 상태';
$lang['label_sel_supplier']='모든 공금업자';
$lang['label_sel']='- 선택하세요 -';
$lang['label_flag']='생산지';
$lang['label_goods_gift']='사은품(몇개 일시sku사이에 영문 쉼표”,”를 사용하세요)';

$lang['label_new'] = '신상품 추천';
$lang['label_comment'] = '베스트 추천';
$lang['label_supplier'] = '곡급업자 관리';
$lang['label_supplier_add'] = '공급업자 추가';
$lang['label_supplier_edit'] = '공급업자 편집';
$lang['label_supplier_name'] = '회사명';
$lang['label_supplier_user'] = '이름';
$lang['label_supplier_tel'] = '휴대폰';
$lang['label_supplier_phone'] = '전화';
$lang['label_supplier_qq'] = 'QQ';
$lang['label_supplier_ww'] = '旺旺';
$lang['label_supplier_addr'] = '주소';
$lang['label_supplier_email'] = 'Email';
$lang['label_supplier_link'] = '홈페이지';
$lang['label_supplier_shipping'] = '스스로 출하하는 공급업자';
$lang['info_supplier_exist'] = '이 공급업자는 이미 존재한 공급업자입니다. 중복추가 하지 마십시오.';
$lang['info_supplier_username_exist'] = '이 공급업자 사용자ID는 이미 존재하여있습니다. 중복추가 하지 마십시오.';
$lang['label_supplier_n'] = '공급업자';
$lang['label_supplier_username'] = '사용자ID';
$lang['label_supplier_password'] = '비밀번호';

$lang['label_cn'] = '중국';
$lang['label_us'] = '미국';
$lang['label_hk']='홍콩';
$lang['label_ne']='뉴질랜드';
$lang['label_ho']='네덜란드';
$lang['label_as']='오스트레일리아';
$lang['label_fr']='프랑스';
$lang['label_ko']='대한민국';
$lang['label_tw']='타이완';
$lang['label_jp']='일본';
$lang['label_sp']='스페인';
$lang['label_ph']='필리핀';
$lang['label_chi']='칠레';
$lang['label_ge']='독일';
$lang['label_ca']='캐나다';
$lang['label_fi']='핀란드';

$lang['info_success']='데이터 제출성공';
$lang['info_failed']='데이터 제출이 실패되었습니다.，데이터에  * 있는 부분은 착실하게 기입하기 바랍니다.';
$lang['info_unvalid_request']='불법요청';
$lang['info_error']='조작이 실패 하였습니다. 다시 시도하세요.';
$lang['info_price_err']='판매가격은 시장가격보다 많으면 읺됩니다.';
$lang['info_price_err1']='판매가격은 (10 / 9 * 구매가)보다 많아야 합니다.';
$lang['info_err_weight']='상품 중량이 합법적이지 않습니다.';
$lang['info_err_purchase_price']='구매 가격이 합법적이지 않습니다.';

$lang['reset_user_pwd']='사용자 비밀번호 재취득';
$lang['confirm_user_id']='사용자ID를 확인하세요.';
$lang['id_not_identical']='두번 입력한ID는 같아야 합니다.';
$lang['this_user_name_is']='이사용자의 이름은：';
$lang['reset_pwd_success_admin']='비밀번호 재취득이 성공적으로 진행하였습니다.초기 비밀번호는 :';

/* 交易管理 */
$lang['admin_trade_title'] = '거래관리';
/* 问题反馈  */
$lang['label_feedback'] = '문제점제기';
$lang['label_feedback_email'] = 'E-mail';
$lang['label_feedback_userid'] = '사용자ID';
$lang['label_feedback_content'] = '내용';
$lang['label_feedback_date'] = '제출시간';
$lang['label_feedback_state'] = '상태';
$lang['label_feedback_state_yes'] = '해결 완료';
$lang['label_feedback_state_no'] = '미해결';
$lang['label_feedback_change_state'] = '숨기기';
$lang['label_server'] = 'A/S서비스';

/* 订单管理 */
$lang['admin_trade_order'] = '주문관리';
$lang['admin_trade_order_attach'] = '관련주문 id';
$lang['admin_order_info'] = '주문정보';
$lang['admin_order_info_basic'] = '기본정보';
$lang['admin_order_id'] = '주문 id';
$lang['pc_order_id'] = '주문 id ';
$lang['admin_order_prop'] = '주문유형';
$lang['admin_order_prop_normal'] = '보통주문';
$lang['admin_order_prop_component'] = '주문분렬포함';
$lang['admin_order_prop_merge'] = '통합주문';
$lang['admin_order_uid'] = '고객 id';
$lang['admin_order_customer'] = '고객';
$lang['admin_order_store_id'] = '몰id';
$lang['admin_order_consignee'] = '받는 분';
$lang['admin_order_phone'] = '연락처';
$lang['admin_order_deliver_addr'] = '주소';
$lang['admin_order_zip_code'] = '우편번호';
$lang['admin_order_customs_clearance'] = '세관통관번호';
$lang['admin_order_deliver_time'] = '배송시간';
$lang['admin_order_expect_deliver_date'] = '예상발송일';
$lang['admin_order_expect_deliver_date_invalid'] = '예상발송일은 지금날짜보다 앞일수없다. ';
$lang['admin_order_info_goods'] = '상품정보';
$lang['admin_order_goods_list'] = '상품목록';
$lang['admin_order_goods_sn'] = 'sku';
$lang['admin_order_goods_name'] = '명칭';
$lang['admin_order_goods_quantity'] = '수량';
$lang['admin_order_remark'] = '비고';
$lang['admin_order_info_pay'] = '지불정보';
$lang['admin_order_receipt'] = '영수증 필요 여부';
$lang['admin_order_receipt_0'] = '필요하지 않는다.';
$lang['admin_order_receipt_1'] = '필요 한다.';
$lang['admin_order_currency'] = '화폐';
$lang['admin_order_rate'] = '환율';
$lang['admin_order_goods_amount'] = '상품합계';
$lang['admin_order_deliver_fee'] = '배송료';
$lang['admin_order_amount'] = '실재지불금액';
$lang['admin_order_amount_usd'] = '실재금액（달러）';
$lang['admin_order_profit_usd'] = '이윤（달러）';
$lang['admin_order_payment'] = '지불방식';
$lang['admin_order_payment_unpay'] = '미 지불';
$lang['admin_order_payment_group'] = '선불금';
$lang['admin_order_payment_coupon'] = '바우처교환주문';
$lang['admin_order_payment_alipay'] = 'alipay';
$lang['admin_order_payment_unionpay'] = 'unionpay지불';
$lang['admin_order_payment_paypal'] = 'PayPal';
$lang['admin_order_payment_ewallet'] = 'eWallet';
$lang['admin_order_payment_yspay'] = 'yspay지불';
$lang['admin_order_payment_amount'] = '잔액지불';
$lang['admin_order_pay_time'] = '지불시간';
$lang['admin_order_notify_num'] = '接口回调次数';
$lang['admin_order_pay_txn_id'] = '第三方交易号';
$lang['admin_order_info_status'] = '상태정보';
$lang['admin_order_info_create_time'] = '창건시간';
$lang['admin_order_info_freight'] = '납품정보';
$lang['admin_order_info_deliver_time'] = '발송시간';
$lang['admin_order_info_receive_time'] = '납품시간';
$lang['admin_order_info_update_time'] = '경신시간';
$lang['admin_order_status'] = '주문상태';
$lang['admin_order_status_all'] = '모든상태';
$lang['admin_order_status_init'] = '발송 중';
$lang['admin_order_status_checkout'] = '결제 대기 중 ';
$lang['admin_order_status_paied'] = '발송 대기 중 ';
$lang['admin_order_status_delivered'] = '납품 대기 중';
$lang['admin_order_status_arrival'] = '평가 대기 중';
$lang['admin_order_status_finish'] = '완성 되었습니다.';
$lang['admin_order_status_returning'] = '반품 중';
$lang['admin_order_status_refund'] = '반품완료';
$lang['admin_order_refund'] = '반품';
$lang['admin_order_status_cancel'] = '주문취소';
$lang['admin_order_status_component'] = '분할 완료';
$lang['admin_order_status_doba_exception'] = 'DOBA주문이상';
$lang['admin_order_operate'] = '조작';
$lang['admin_order_operate_deliver'] = '발송확인';
$lang['admin_order_confirm_cancel'] = '주문상태를 추소하면 다시 되돌릴 수 없습니다.주문취소를 확인 하겠습니까？';
$lang['admin_order_cancel_confirm'] = '주문취소 확인';
$lang['admin_order_cancel'] = '취소';
$lang['admin_order_deliver_box_title'] = '발송정보를 입력하세요.';
$lang['admin_order_deliver_box_id'] = '택배정보';
$lang['admin_order_tracking_num'] = '송장번호';
$lang['admin_order_remark_system'] = '시스템비고';
$lang['admin_order_customer_remark'] = '고객님 볼수있는 비고';
$lang['admin_order_customer_remark_add'] = "고객님 볼수있는 비고 추가.";
$lang['admin_order_system_remark'] = '관라 시스템 볼수있는 비고';
$lang['admin_order_system_remark_add'] = "관라 시스템 볼수있는 비고 추가";
$lang['admin_order_remark_operator'] = '조작인';
$lang['admin_order_remark_create_time'] = '창건시간';
$lang['admin_order_expect_deliver_date'] = '예상발송시간';
$lang['admin_order_shipping_print'] = '송장출력';
$lang['set_except_group']='세트면제회원 설정';
$lang['id_format_is_not_correct']='ID양식이 정확하지 않다.';
$lang['admin_doba_order_fix'] = '手动获取doba信息';
$lang['admin_doba_order_id'] = 'Doba ID';
$lang['admin_doba_order_request'] = '获取信息';
$lang['admin_doba_order_request_succ'] = '获取信息成功';

/* 订单修复 */
$lang['admin_trade_repair'] = '주문복원';
$lang['admin_trade_repair_modify'] = '정보수정';
$lang['admin_trade_repair_component'] = '주수동분할';
$lang['admin_trade_repair_rollback'] = '상태복구';
$lang['admin_trade_repair_cancel_rollback'] = '취소 상태 복원';
$lang['admin_trade_repair_addnumber'] = '거래번호 수동으로 추가';
$lang['admin_trade_repair_number'] = '거래번호';
$lang['orderid_not_null'] = '주문번호를 비울수 없습니다.';
$lang['txnid_not_null'] = '거래번호를 비울수 없습니다.';
$lang['orderid_not_exits'] = '무효한 주문번호 입니다.';
$lang['orderid_ture'] = '추가성공';

/* 导入订单 */
$lang['admin_trade_order_import'] = '주문데이트 들여오기';
$lang['orderid_default_config'] = '기본 옵션';
$lang['orderid_users_config'] = '파라미터 옵션';
$lang['orderid_freight_info_cv'] = '송장번호 덮어쓰기';
$lang['orderid_freight_info_null'] = '송장번호 비우기';

//重置选购套装
$lang['not_find_this_order']='이 사용자의 선택주문이 발견되지 않았습니다.';
$lang['not_find_this_upgrade_order']='이 사용자의 업레이드주문이 발견되지 않았습니다.';
$lang['this_order_not_reset']='오직 발송 준비중인 주문만 갱신할수있습니다.';
$lang['reset_choose_group_success']='주문이 성공적으로 갱신되었습니다.';
$lang['this_user_not_choose']='이 사용자는 아직 선택주문하지 않아 갱신할 필료가없습니다.';
$lang['this_user_upgrade_not_reset']='이 사용자는 교환주문후 몰 업레이드를 실행한적이 있어서 갱신할수 없습니다.';
$lang['reset_choose_group']='선택주문 갱신';
$lang['reset_upgrade_group']='업레이드주문 갱신';
$lang['reset_group']='주문갱신';
$lang['reset_type']='타입갱신';



$lang['you_use_coupons_not_can_reset']='바우처 부족으로 갱신할수 없습니다.';
$lang['order_a_timeout_not_can_reset']='주문이 3일 초과하여 갱신할수 없습니다.';
$lang['this_user_have_more_than_once_upgrade_record']="이 사용자는 단계적 업레이드여서 주문갱신을 할수 없습니다.";

/*导入第三方订单*/
$lang['import_third_part_orders'] = '제3자주문 들여오기';

/* 供应商系统 相关 */
$lang['sys_supplier_title']='TPS공급자관리 시스템';
$lang['coupons_manage']='바우처 관리';
$lang['coupons_add_or_reduce']='바우처 변동';
$lang['voucher']='바우처';
$lang['voucher_not_null']='바우처 금액을 입력하세요.';
$lang['remark_not_null']='원인을 입력하세요.';
$lang['please_enter_correct_voucher']='정확한 바우처 금액을 입력하세요.';
$lang['voucher_value']='바우처 금액';

$lang['order_not_exits']='이 주문이 존재하지 않습니다.';
$lang['order_id_not_null']='주문번호를 비우시면 안됩니다.';
$lang['this_order_is_choose_order']='이 주문은 선택주문 입니다. 고객님의 id는:';
$lang['this_order_is_upgrade_order']='이 주문은 업그레이드주문 입니다. 고객님의 id는:';
$lang['this_order_is_basic_order']='이 주문은 보통주문 입니다. 갱신할수 없습니다.';
$lang['check_order_type']='주문유형을 검측하기';

$lang['refund'] = '환불하기';
$lang['no_refund'] = '환불하지 않음';
$lang['no_cancel_order'] = '오더가 도출 되었습니다. 주문 취소시 반드시 먼저 운영팀과 주문의 발송여부를 확인하여야 합니다. 이점에 유의하세요.';
$lang['refund_coupons']='바우처 돌려주기';
$lang['only_cancel']='오직 취소';
$lang['order_refund'] = '주문환불';

/** paypal 查询 */
$lang['admin_paypal_failure_search'] = '페이팔환불/주문취소 조회';
$lang['admin_paypal_failure_list'] = '페이팔환불/주문취소 리스트';

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

$lang['admin_supplier_store_code'] = '창고와 그에 대응하는 공급업자 리스트';
$lang['admin_supplier'] = '공급업자';
$lang['admin_store_code'] = '창고';

/** 商品名称 */
$lang['admin_mini_water'] = '미니 정수기';
$lang['admin_family_water'] = '가정용 정수기';
$lang['admin_powder'] = '다이어트 영양분말';
$lang['admin_flx'] = '식물성 연캡슐';

/* 区域 */
$lang['zone_area_chn'] = "중국 대륙";
$lang['zone_area_usa_other'] = "미국와기타구역";
$lang['zone_area_kor'] = "대한민국";
$lang['zone_area_hkg_mac_twn_asean'] = "홍콩,마카오,타이완와 동남아시아";


/**拆分订单***/
$lang['split_order'] = "분할주문";
$lang['item_status_exception'] = '이주문의 분렬에 이상이 있습니다.다시 분할주문을 할수없습니다.';
$lang['this_order_not_need_split_order']='이 주문는 분할할 필요가 없슴니다.(상품은 하나의 창고에만 대응합니다.)';
$lang['only_wait_delivery_can_split_order'] = "오직 발송준비중 인상품만 분할주문할수 있습니다.";
$lang['the_split_order_success'] = '분할주문 성공,분열주문은 아래와 같습니다.：';

$lang['fill_in_frozen_remark'] = '당분간 중지된 원인을 입력하세요.';
$lang['fill_in_frozen_remark_2'] = '동결원인을 입력하세요. 주문이 도출될시 대응하는 운영책임자와 동결가능여부를 확인하시기 바랍니다.';
$lang['lock_order_not_can_freeze'] = '잠겨진 오더는 당분간 중지를 진행 할수 없습니다.';
$lang['freeze_success'] = "당분간 중지성공";
$lang['transaction_rollback'] = "업무가 복구 되였습니다.";
$lang['order_remove_frozen'] = '당분간 중지해제';
$lang['remove_frozen_success'] = '해제성공';
$lang['confirm_remove_freeze'] = '당분간 중지 해제 하시 겠습니까?';

//订单操作log
$lang['trade_order_logs'] = '주문 조작 일지';
$lang['all_oper_code'] = '전체 카테고리';
$lang['order_log_oper_create'] = '주문 새로 만들기';
$lang['order_log_oper_modify'] = '주문 수정';
$lang['order_log_oper_export'] = '주문 엑스포트하기';
$lang['order_log_oper_diliver'] = '주문 발송';
$lang['order_log_oper_reset'] = '주문 재취득';
$lang['order_log_oper_rollback'] = '주문 복구';
$lang['order_log_oper_cancel'] = '주문 취소';
$lang['order_log_oper_frozen'] = '주문 당분간 중지';
$lang['order_log_oper_unfrozen'] = '주문 당분간 중지 해제';
$lang['order_log_oper_addr_edit'] = '주문 주소수정';
$lang['order_log_oper_erpmodify'] = "오더정보 수정";
$lang['order_log_oper_suit'] = '제품 주문 상태 변경 세트';
$lang['order_log_oper_recovery'] = '주문을 다시';
$lang['order_log_oper_exchange'] = '订单换货';
$lang['operator_id'] = '오퍼레이터ID';
$lang['update_time'] = '조작시간';

$lang['load_more'] = 'more로딩';

$lang['load_finish'] = '더 많은 결과가 없습니다.';
$lang['this_user_not_sort'] = '이 사용자는 아직 배열에 들어있지 않습니다.';
$lang['the_number_of_matrix'] = '매트릭스 인원수:';

//后台执行sql入口
$lang['execute_sql'] = 'SQL어구 실행';
$lang['please_enter_sql'] = 'SQL어구를 입력하세요.';
$lang['please_enter_remark'] = '상세한 비고를 입력하세요.';

//跨区运费
$lang['international_freight'] = '상품 구역당 배송료';
$lang['goods_sku'] = '상품SKU';
$lang['find'] = '조회';
$lang['please_input_freight_usd'] = '배송료를 입력하세요.,단위(달러),구매 불허 구역은 -1로 설정하세요.';
$lang['please_input_right_sku'] = '정확한SKU를 입력하세요.';
$lang['freight_must_is_number'] = '배송료는 꼭 숫자야 합니다.';

$lang['not_find_this_goods_name'] = '이 상품명에 대한 결과가 없습니다.';

//^^^^
$lang['all_country'] = "모든구역";
$lang['sql_source'] = 'SQLsource';
$lang['system_setting'] = '시스템 세팅';


/**后台文件管理**/
$lang['admin_ads_file_manage'] = '파일 관리';
$lang['admin_file_type'] = '파일유형';
$lang['admin_file_announcement'] = '공문 파일';
$lang['admin_file_regime'] = '제도';
$lang['admin_commission_explain'] = '커미션 설명';
$lang['admin_file_is_show'] = '디스플레이 여부';
$lang['file_is_show'] = '디스플레이';
$lang['file_is_hide'] = '숨기다';
$lang['admin_file_name'] = '파일명';
$lang['admin_ads_file_add'] = '파일 첨부';
$lang['admin_ads_file_modify'] = '파일 수정';
$lang['admin_file_empty'] = '파일은 비워둘 수 없습니다';
$lang['admin_file_name_empty'] = '파일명은 비워둘 수 없습니다';
$lang['admin_file_limit_10m'] = '크기가 10M 를 초과하였습니다';
$lang['admin_file_name_limit_100'] = '파일명 길이가 100자를 초과하였습니다.';
$lang['admin_file_upload_fail'] = '업로드에 실패하였습니다';
$lang['admin_file_type_empty'] = '파일 유형은 비워둘 수 없습니다.';
$lang['admin_file_delete_success'] = '삭제를 성공하였습니다';
$lang['admin_file_delete_fail'] = '삭제를 실패하였습니다';
$lang['admin_file_update_success'] = '수정을 성공하였습니다';
$lang['admin_file_update_fail'] = '수정을 실패하였습니다';
$lang['admin_file_add_success'] = '추가를 성공하였습니다';
$lang['admin_file_add_fail'] = '추가를 실패하였습니다';
$lang['delete_admin_file'] = '파일 삭제';
$lang['admin_file_modify'] = '편집';
$lang['admin_file_submit_error'] = '중복 제출은 하지 말아 주십시오';
$lang['admin_file_area'] = '국가 구역';
$lang['admin_file_area_empty'] = '국가 구역란은 비워둘 수 없습니다';

/*知识库管理*/
$lang['admin_knowledge'] = '지식 베이스';
$lang['admin_knowledge_manage'] = '지식 베이스';
$lang['admin_knowledge_cate_manage'] = '지식 베이스 클래스';
$lang['admin_knowledge_title'] = '제목';
$lang['admin_knowledge_cate'] = '지식 베이스';
$lang['admin_knowledge_add'] = '추가';
$lang['admin_knowledge_cate_add'] = '지식 베이스 추가';
$lang['edit'] = '편집';
$lang['success'] = '성공';
$lang['modify_user'] = '편집';
$lang['admin_knowledge_success'] = '操作成功，确定转向列表页，取消则继续操作';

/*会员个人中心文件下载*/
$lang['file_download'] = '파일 다운로드';


/** A/S中心 start*/
$lang['tickets_center'] = ' A/S 센터';
$lang['history_tickets'] = '역사 A/S청구서';
$lang['my_tickets'] = '나의 A/S청구서';
$lang['all_tickets'] = '전부 A/S청구서';
$lang['add_tickets']= 'A/S청구서 새로만들기';
$lang['unassigned_tickets'] = '미분배 A/S청구서';
$lang['unassigned_tickets_count'] = '미분배';
$lang['unprocessed_tickets_count'] = '미처리';
$lang['tickets_id'] = 'A/S청구서 번호';
$lang['tickets_sender'] = '발신자';
$lang['tickets_closed_can_not_reply'] = 'A/S청구서가 종료되여 답장을 보낼수 없습니다.';
$lang['tickets_reply'] = 'A/S청구서 답장';
$lang['org_tickets_info'] = '오리지널A/S청구서 정보';
$lang['customer'] = '상담원';
$lang['member'] = '회원';
$lang['member_id'] = '회원ID';
$lang['tickets_language'] = '언어';
$lang['pls_t_uid'] = '회원ID를 입력하세요.';
$lang['pls_t_correct_ID'] = '정확한 회원ID를 입력하세요.';
$lang['tickets_score_num'] = '평점';

$lang['tickets_title'] = 'A/S제목';
$lang['assign_to_me'] = '나의 명의로 표기';
$lang['tickets_language'] = '언어';
$lang['assign_success'] = '표기성공';
$lang['assign_fail'] = '표기 실패';
$lang['view_ticket_detail'] = '자세한 정보보기';
$lang['view_and_change'] = '조회/수정';
$lang['close_tickets'] = 'A/S청구 중단';
$lang['view_tickets_log'] = '일지 보기';
$lang['confirm_close_tickets'] = 'A/S청구서를 종료하시겠습니까?';
$lang['close_tickets_success'] = 'A/S청구서 이미종료';
$lang['close_tickets_fail'] = 'A/S청구서 종료실패';
$lang['tickets_content'] = 'A/S문제 묘사';
$lang['picture_not_exist'] = '이미지가 없습니다.';
$lang['tickets_no_exist'] = '죄송합니다. A/S청구서가 존재하지 않습니다. ';
$lang['attach_no_exist'] = '죄송합니다. 첨부 파일이 존재하지 않습니다. ';
$lang['log_no_exist'] = '죄송합니다. 청구서일지가 없습니다.';
$lang['log_info'] = '일지정보';
$lang['tickets_take_time'] = 'A/S청구서 처리 시간';
$lang['day'] = '일';
$lang['hour'] = '시간';
$lang['minute'] = '분';
$lang['second'] = '초';
$lang['tickets_handler'] = '실행자';
$lang['modified_type'] = '수정한 유형';
$lang['old_data'] = '옛값';
$lang['new_data'] = '새값';
$lang['add_new_tickets'] = 'A/S청구서 새로만들기';
$lang['new_tickets'] = '새로운 A/S청구서';
$lang['new_msg'] = '새소식';

$lang['t_template_name'] = '템플릿 명칭';
$lang['t_template_content'] = '템플릿 내용';
$lang['t_template_type'] = '템플릿 유형';
$lang['tickets_template']= '정보 템플릿';
$lang['pls_t_t_name'] = '템플릿 명칭을 입력하세요.';
$lang['pls_t_t_content'] = '템플릿 내용을 입력하세요. ';
$lang['add_tickets_template'] = '자체 정의 템플릿 추가';
$lang['is_public']='공개여부';
$lang['template_author'] = '작성자';
$lang['template_name'] = '템플릿 명칭';
$lang['template_is_public'] = '예';
$lang['template_not_public'] = '아니요';
$lang['template_forbid'] = '사용을 금하다';
$lang['add_template_success'] = '추가 성공';
$lang['add_template_fail'] = '추가 실패';
$lang['confirm_update_template'] = '템플릿을 수정하시겠습니까?';
$lang['update_template_success'] = '템플릿을 수정성공';
$lang['update_template_fail'] = '템플릿을 수정실패';
$lang['confirm_delete_template'] = '템플릿을 삭제하시겠습니까?';
$lang['delete_template_success'] = '삭제성공';
$lang['delete_template_fail'] = '삭제실패';

$lang['tickets_black_list'] = '블랙리스트';
$lang['black_uid'] = '회원ID';
$lang['tickets_black'] = '(블랙)';
$lang['confirm_delete_black_list'] = '블랙리스트에서 제명 하시겠습니까?';
$lang['update_black_list_success'] = '블랙리스트에서 제명하였습니다.';
$lang['update_black_list_fail'] = '제명실패';
$lang['add_black_list_success'] = '블랙리스트에 넣었습니다.';
$lang['black_list_exist'] = '추가실패. 이 ID는 이미 블랙리스트에 올라 있습니다.';
$lang['add_black_list_fail'] = '추가실패';

$lang['manual_work'] = '수동';
$lang['automatic'] = '자동';
$lang['tickets_cus_leave'] = '상담원  %s 휴가';
$lang['tickets_cus_work']  = '상담원 %s 정상근무';
$lang['change_status_fail']='상담원 %s 근무상태 변경 실패';
$lang['tickets_auto_assign'] = '시스템을 자동분배로 설정하였습니다.';
$lang['tickets_hand_assign'] = '시스템을 수동분배로 설정하였습니다.';
$lang['tickets_auto_assign_fail'] = '분배 성정실패';

$lang['tickets_status'] = 'A/S청구서 상태';
$lang['tickets_priority'] = 'A/S청구서 우선 순위';
$lang['modified_manager'] = 'A/S청구서 옮기기';
$lang['tickets_assign'] = 'A/S청구서 분배';
$lang['submit_as'] = '제출상태';
$lang['submit_as_waiting_reply'] = '응답대기';
$lang['submit_as_waiting_discuss'] = '상담대기';
$lang['add_tickets_tips'] = '주석';
$lang['tickets_send_fail'] = '메세지 발송실패';
$lang['tickets_send_success'] = '메세지 발송성공';
$lang['apply_close_tickets'] = 'A/S청구서종료 신청';
$lang['view_tickets'] = 'A/S청구서조회';
$lang['r_waiting_reply'] = '답장-응답대기';
$lang['r_waiting_discuss'] = '답장-상담대기';
$lang['tickets_tips']='주석';
$lang['r_tickets_resolved'] = '답장-해결완료';
$lang['auto_reply_tickets'] = '자동 응답';
$lang['close_tickets_send_email'] = '이메일 보내기';
$lang['auto_close_tickets'] = 'A/S청구서 자동정지';

$lang['tickets_label'] = '주석';
$lang['pls_input_tips'] = '주석을 입력하세요.';
$lang['no_tips'] = '주석 없음';
$lang['add_tips_success'] = '주석 추가성공';
$lang['add_tips_fail'] = '주석 추가실패';

$lang['tickets_type'] = 'A/S문제유형';
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
$lang['walhao_store'] = 'walhao몰';
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

$lang['pls_t_type'] = '문제 분류를 선택하세요. ';
$lang['pls_t_title'] = 'A/S제목을 입력하세요.';
$lang['pls_t_tid'] = 'A/S청구서 번호를 입력하세요.';
$lang['pls_t_content'] = 'A/S내용을 입력하세요.';
$lang['exceed_words_limit'] = '글자수 제한에 초과하였습니다.';
$lang['pls_t_uid_aid'] = '회원 ID/상담원 ID를 입력하세요.';
$lang['pls_t_tid_uid'] = 'A/S청구서번호/회원ID를 입력하세요.';
$lang['remain_'] = '남은 글자수';
$lang['max_limit_'] = '입력할수 있는 글자수';
$lang['_words'] = '자';
$lang['pls_input_reply_content'] = '답장내용을 입력하세요. ';
$lang['tickets_info'] = 'A/S청구서명세';

$lang['pls_t_status'] = '상태를 선택하세요. ';
$lang['pls_t_priority'] = '우선 순위를 선택하세요.';
$lang['tickets_status'] = 'A/S청구서 상태';
$lang['new_ticket'] = '새로 만들기';
$lang['open_ticket'] = '시작함';
$lang['waiting_reply'] = '응답대기';
$lang['waiting_discuss'] = '상담대기';
$lang['ticket_resolved'] = '해결완료';
$lang['had_graded'] = '평점마침';
$lang['apply_close'] = '중단신청';
$lang['had_apply_tickets'] = '중단신청 완료';
$lang['tickets_priority'] = 'A/S청구서 우선 순위';
$lang['priority'] = '우선 순위';
$lang['reply'] = '답장';
$lang['general_tickets'] = '일반';
$lang['preferential_tickets'] = '우선';
$lang['urgent_tickets'] = '긴급';
$lang['change_tickets_priority_fail'] = '우선 순위 수정 실패';
$lang['change_tickets_priority_success'] = '우선 순위 수정 성공';
$lang['tickets_transfer'] = 'A/S청구서 옮기기';
$lang['transfer_tickets_fail'] = 'A/S청구서 옮기기 실패';
$lang['transfer_tickets_success'] = 'A/S청구서 옮기기 성공';
$lang['change_status_success'] = '상태수정 성공';
$lang['change_status_fail'] = '상태수정 실패';
$lang['pls_select_customer'] = '상담원을 선택하세요.';
$lang['change_type_success'] = '유형 수정 성공';
$lang['change_type_fail'] = '유형 수정 실패';
$lang['change_tickets_type']='문의 유형 수정';

$lang['tickets_statistics'] = 'A/S청구서 통계';
$lang['cus_id'] = '상담원ID';
$lang['pls_cus_id'] = '상담원ID를 입력하세요.';
$lang['tickets_statistics_time'] = '통계시간';
$lang['num_id'] = '넘버';
$lang['cus_name'] = '이름';
$lang['today_in_tickets'] = 'A/S청구서 분배하기';
$lang['today_out_tickets'] = 'A/S청구서 옮겨보내기';
$lang['today_unprocessed_tickets'] = '당일 뉴 A/S청구서가 처리하지 않았습니다.';
$lang['today_tickets_count'] = '당일 A/S청구서 총수';
$lang['all_unprocessed_tickets_count'] = '전부 미처리된 뉴 A/S청구서';
$lang['new_msg_tickets'] = 'A/S청구서 새소식';
$lang['waiting_discuss_tickets'] = '상담 대기 A/S청구서';
$lang['waiting_reply_tickets'] = '답변 대기 A/S청구서';
$lang['all_tickets_count'] = 'A/S청구서 총수';
$lang['search_data'] = '请输入搜索条件查询';

$lang['tickets_customer_role'] = '상담원 계정관리';
$lang['tickets_customer_role_1'] = '상담원';
$lang['tickets_customer_role_2'] = '휴일근무매니저';
$lang['tickets_customer_permission'] = '권한';
$lang['job_number']='번호';
$lang['confirm_update_customer_1'] = '권한을 휴일근무매니저로 변경 하시겠습니까?';
$lang['confirm_update_customer_2'] = '권한을 상담원으로 변경 하시겠습니까?';
$lang['customer_role_invalid_action'] = '무효한 동작입니다';

$lang['tickets_area_usa'] = '미국지역';
$lang['tickets_area_china'] = '중국지역';
$lang['tickets_area_hk'] = '홍콩지역';
$lang['tickets_area_korea'] = '한국지역';
$lang['unique_job_number'] = '상담원번호가 존제되여 있습니다.';
$lang['job_number_error']  ='상담원번호는 3자리의 숫자입니다.';
$lang['assign_cus_job_number'] = '상담원번호 분배';
$lang['cus_job_number'] = '번호';
$lang['not_customer'] = '非客服';

$lang['button_text'] = '첨부 파일 선택';
$lang['is_exists'] = '이미 존재한 파일입니다. ';
$lang['remain_upload_limit'] = '선택한 파일수가 남은 업로드수를 초과하였습니다. ';
$lang['queue_size_limit'] = '선택한 파일수가 행렬의 수량을 초과하였습니다.';
$lang['exceeds_size_limit'] = '파일의 크기가 제한에 넘었습니다. ';
$lang['is_empty'] = '파일을 비울수 없습니다. ';
$lang['not_accepted_type'] = '업로드에 맞지 않은 파일 형식입니다. ';
$lang['upload_limit_reached'] = '업로드 한계에 이르렀습니다. ';
$lang['attach_delete_success'] = '삭제성공';
$lang['attach_no_permissions'] = '죄송합니다. 권한이 부족합니다.';
$lang['attach_cannot_find'] = '파일을 찾을수 없습니다. ';
$lang['not_support_mobile_upload'] = '첨부파일 업데이트는 휴대전화에서 지원되지 않습니다.';
/**售后中心 end **/

$lang['all_status'] = '전부상태';
$lang['awaiting_processing'] = '처리 대기';
$lang['has_been_completed'] = '완성';
$lang['submit_email'] = '제출자';
$lang['audit_email'] = '심사자';
$lang['audit_time'] = '심사시간';
$lang['refuse'] = '기각';
$lang['refuse_reason'] = '기각원인';
$lang['confirm_execute'] = '집행에 확인합니까?';


$lang['is_delete_uid'] = '사용자 삭제확인';
$lang['user_email_exception_list'] = '사용자 예외 목록의 메일 송수신';
$lang['goods_number_exception'] = '상품의 재고 이상 기록';

//月费池转现金池日志列表
$lang['old_month_fee_pool'] = '이체전의 호스팅비버킷';
$lang['new_month_fee_pool'] = '이체후의 호스팅비버킷';
$lang['cash'] = '이체금액';


$lang['product_freight_delete'] = '상품구역간 운송비 삭제';
$lang['label_country']='국가';
$lang['not_find_this_product_freight'] = '기록이 없습니다.';
$lang['product_freight_not_be'] = '운송비는 0보다 크거나 같은 정수야 합니다.';
$lang['delete_success'] = '삭제성공';
$lang['delete_failure'] = '삭제실패';
$lang['is_delete'] = '삭제 진행후 복구 할수없습니다. 운송비 삭제를 확인하시겠습니까?';
$lang['delete_ok'] = '운송비 삭제 확인';


$lang['choose_group'] = '선택구매주문';
$lang['generation_group'] = '바우처주문';
$lang['retail_group'] = '소매주문';
$lang['all_group'] = '모든유형의 주문';


$lang['goods_number_exception'] = '상품 재고이상 기록';
$lang['number_zh'] = '중국어 재고';
$lang['number_hk'] = '중국어 재고';
$lang['number_english'] = '영어 재고';
$lang['number_kr'] = '한국어재고';
$lang['number_null'] = '이 어종에 재고가 없습니다.';
$lang['uid_not_null'] = '회원ID를 비울수 없다.';
$lang['process_num'] = '처리 횟수';
$lang['cron_doing'] = '스크립트 태스크 관리';
$lang['cron_name'] = 'Cron 이름';
$lang['false_count'] = '횟수 복귀 false';

$lang['order_not_accord_with'] = '이 주문 안 맞는 스크롤백 조건';
//手动添加138佣金合格人数
$lang['user_qualified'] = '138 합격 수가 없습니다';
$lang['add_user_qualified'] = '추가 138 커미션은 합격 인원';
$lang['commission_number'] = '커미션';
$lang['commission_isok'] = '확인 추가';

//手动添加doba订单
$lang['admin_trade_repair_adddaba'] = '수동 보내다 doba 주문';
$lang['admin_trade_isdoba'] = '이 주문은 이미 보내다 성공 할 필요가 없다, 중복 보내다';
$lang['admin_trade_doba_nopush'] = '주문을 보내다 할 필요가 없다';

//用户状态变更记录
$lang['users_status_log'] = '회원 상태 변경 기록';
$lang['users_status_front'] = '상태 변경 전';
$lang['users_status_back'] = '상태 변경 후';
$lang['buckle_fee'] = '납부 월비';
$lang['order_fee'] = '주문 상쇄 월비';
$lang['buckle_fee_error'] = '아직 납부 월비';

//佣金管理查询列表
$lang['operator_email'] = '조작 사람 메일박스';
$lang['user_oneself_del'] = '회원 자신의 삭제';
$lang['is_certificate'] = '발급 대신 제품 티켓';

//佣金查询
$lang['no_time'] = '시작일과 마감일은 한달여야 합니다. 양월조회를 진행할수 없습니다.';
$lang['no_search'] = '사용자 ID 결코 검색어를 입력하십시오.';
$lang['no_time_null'] = '시작일을 비울수 없습니다.';
$lang['is_certificate'] = '발급 대신 제품 티켓';
$lang['limit_query_month'] = '죄송합니다. 현재 회원님의 당월 데이터에 한해 검색하실 수 있습니다.';

//活动抵扣月费
$lang['delPlan_title'] = '회원 활동 기록 삭제';
$lang['not_join_action_charge_month'] = '이 회원 없다. 행사 상쇄 월비 계획';

$lang['not_Porder'] = '불허 복구 P 시작하는 소매 주문서 및 불허 복구 C 시작하는 업그레이드 주문 또는 제품 주문을 대신 티켓.';
$lang['not_repeat_insert'] = '이 주문 번호 추가됨 적이 처리를 기다리다';
$lang['admin_file_order_freight_error'] = '%s택배 회사 작법 잘못 또는 못 비어 있다';
$lang['admin_file_order_show'] = '*주: 택배 회사 칸에 쓰세요 만 허락 0 혹은 숫자 크다 0 0 위해 사용자 정의 예를 하나 주문서 두 택배 회사, 택배 회사 칸에 쓰세요 0, 택배 번호 칸에 쓰세요 택배 회사 명칭 및 홀수번호!';
$lang['order_rollback_show'] = '*주: 이 기능을 KDM의 스크롤백 까지 기다리다 기다리다 물건을 출하!';
$lang['admin_trade_repair_recovery'] = '복구 주문';
$lang['admin_trade_feright_modify'] = '송장정보 복구';
$lang['order_recovery_show'] = '* 주: 이 기능을 적용된다 취소된 소매 주문 복원할 다른 상태, 단지 고쳐 상태 및 추가 지급하다 커미션은 필요로 하는 데 가서 공제하는 수동으로 동작!';
$lang['order_modify_order_freight'] = '*주: 이 기능은 택배회사와 송장번호의 오류수정에 적용됩니다. 프로그렘은 오직 송장정보만을 수정할수있습니다. 파일 업로드시 도표 [송장번호] 렬의 수학공식 옵션을 제거하세요. 제거하지 않을시 송장번호에 수학공식을 표기할 확율이 좊습니다. 정확한 파일은 아래 이미지와 같습니다！';
$lang['order_not_recovery'] = '오직 취소 / 반품 소매 주문 회복할 수';
$lang['admin_order_status_revert'] = '복원할';
$lang['admin_order_commission'] = '재지급한다 커미션';
$lang['admin_order_not_logistics'] = '이 주문 물류 정보 비어 이 상태로 복원할 수 없습니다!';
$lang['all_express'] = '所有快递公司';
$lang['error_express'] = '快递公司和运单号需同时选择';
$lang['admin_order_repeat'] = '%s택배 단 호 중복 됐 다';
$lang['admin_repeat_data']  = '처리 실패 를 되풀이 도입 데이터 다';

$lang['admin_order_status_holding_exchange'] = '동결 (있어 바터)';
$lang['allow_exchange'] = '허용 바터';
$lang['ok_cancel'] = '확인 취소';
$lang['cancel_exchange'] = '취소 바터';
$lang['go_exchange'] = '가서 바터';
$lang['exchange_order'] = '바터 주문';
$lang['exchange_remaining_time'] = '잉여';
$lang['exchanging'] = '바터 중';
$lang['exchange_timeout'] = '바터 시간 제한';
$lang['exchange_timeout_msg'] = '72 시간 내에 미완성 바터 시스템 자동 취소';
$lang['cancel_exchange_confirm_msg'] = '당신 취소 바터 후 이 업그레이드 주문을 다시 바터 수 없을 것이다!';
$lang['exchange_timer_reset'] = '이 작업은 어떻게 초기화 가서 바터 타이머, 삭제하시겠습니까 동작을 실행할 수 있습니까?';
//修复分红
$lang['add_to_cur_month_queue'] = "당월 보너스 발급 대기열 가입";
$lang['user_has_in_queue'] = "사용자는 이미 보너스 발급 대기열에 있습니다.";
$lang['user_not_match_daily_bonus'] = "사용자께서 매일 배당조건에 만족하지 않습니다.";
$lang['please_select_queue_time'] = "대기열 가입시간을 선택하세요.";
$lang['user_order_not_match_new_bonus'] = "사용자의 주문이 신규 특혜보너스에 부합하지 않습니다.";
$lang['reward_user_bonus'] = "회원 보너스 재발급";
$lang['daily_bonus_failed_not_set'] = "매일 배당 발급실패, 발급 비율을 설정하지 않았습니다.";
$lang['daily_bonus_failed_not_set_1'] = "매일 배당 발급실패, 발급 비율은 1보다 클수 없습니다.";
$lang['not_found_this_day_profit'] = "해당 날짜의 월드 이윤 배당의 기록을 확인 할수 없습니다.";
$lang['daily_bonus_profit_not_enough'] = "매일 배당 이윤 부족";
$lang['user_level_not_match'] = "사용자께서 업그에이드 하지 않아 조건에 만족하지 않습니다.";
$lang['user_order_amount_not_match'] = "사용자께서 업그에이드를 하였으나 $50의 판매액을 만족하지 않았거나 업그네이드 주문를 취소 하였습니다.";
$lang['new_member_bonus_failed_rate'] = "신규 특혜보너스 발급실패, 발급 비율을 설정하지 않았습니다.";
$lang['new_member_bonus_failed_rate_1'] = "신규 특혜보너스 발급실패, 발급 비율은 1보다 클수 없습니다.";
$lang['new_member_bonus_profit_not_enough'] = "신규 특혜보너스 이윤 부족";
$lang['daily_bonus_month_error'] = "매일 배달은 해당 달을 넘을수 없습니다.";

$lang['pls_input_reson_1'] = '원인을 입력하세요 .주문이 도출될시 대응하는 운영책임자와 동결가능여부를 확인하시기 바랍니다.';
$lang['repair_users_amount'] = '복구 회원 현금 풀';
$lang['not_repair_amount'] = '회원 현금 풀 잔액 및 자금 변동 보고서 만장일치로 이 회원 현금 지 이상 필요 없다, 복구';
$lang['user_account_total'] = '시스템 통계 보고서 액수의 자금 변동';
$lang['repair_amount'] = '복구';
$lang['user_amount_total'] = '회원 현재 현금 풀 금액';


$lang['invoice_type_form_error'] = '请选择发票类型';
$lang['no_time_all_null'] = '日期不能为空';
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
$lang['btn_del_option'] = '确定要删除吗？';
//解冻用户登录
$lang['unfrost'] = "사용자 로그인 동결해제";
$lang['please_input_unfrost_account'] = "동결해제를 원하시는 계정을 입력하세요.";
$lang['please_input_unfrost_account_again'] = "동결해제를 원하시는 계정을 다시 입력하세요.";
$lang['input_unfrost_not_same'] = "두번 입력하신 계정이 일치하지 않습니다.";
$lang['redis_off'] = "redis서비스 종료. 동결해제할 필요가 없습니다.";
$lang['unforst_success'] = "동결해제 성공";
$lang['unfrost_needless'] = "동결해제할 필요가 없는 계정입니다.";
$lang['pls_input_reson_2'] = '작성해 주세요 원인, 만일 주문을 의해 내보내기 좀 명기하다 과 어느 분이 운영 동료 확인 할 수 없이 바터';
$lang['seleted_input_null'] = "选择不能为空！";
$lang['confirm_add_account_log'] = '确定要补全转账记录吗？';

$lang['incentive_system_management'] = "보너스 제고 관리";
$lang['reward_name'] = "보너스 제고 명칭";
$lang['reward_content'] = "보너스 제고 명칭";
$lang['reward_status'] = "표시상태";
$lang['reward_sort'] = "순위";
$lang['reward_op'] = "작업";
$lang['add_reward'] = "보너스 제고 추가";
$lang['add_show'] = "표시";
$lang['add_hide'] = "표시 않함";
$lang['is_show_hide'] = "표시여부";
$lang['users_bonus_list_check'] = "用户佣金队列异常";
$lang['import_third_order_tips'] = '문서를 업로드하기 전에 먼저 템플릿 데이터를 제거해야합니다!!!';
