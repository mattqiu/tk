<?php

class m_user extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查用户存在
     * @param  [type] $idOrEmail   [description]
     * @param  string $exceptionId [description]
     * @return [type]              [description]
     */
    public function checkUserExist($idOrEmail,$exceptionId='') {
        $this->db->from('users');
        $this->db->force_master();
        if(is_numeric($idOrEmail)){
            if(preg_match('/^1[34578]{1}\d{9}$/',$idOrEmail)){
                $this->db->where('mobile', $idOrEmail)->where('is_verified_mobile',1);
            }else{

                $this->db->where('id', $idOrEmail);
            }
        }else{
            $this->db->where('email', $idOrEmail)->where('is_verified_email',1);
        }
        if($exceptionId){
            $this->db->where('id !=',$exceptionId);
        }
        $res = $this->db->get()->row_array();
        return $res ? $res : FALSE;
    }

    public function checkMemberEmailExist($email,$uid=''){
        $res = $this->db->from('users')->where('email', $email);
        if($uid){
            $this->db->where('id !=',$uid);
        }
        $res = $this->db->get()->row_array();
        return $res?$res:false;
    }

    public function checkMemIdExist($id){
        $res = $this->db->from('users')->where('id', $id)->where('status !=',0)->get()->row_array();
        return $res?$res:false;
    }

    public function checkUserPwd($registerData) {
        $userInfo = $this->getUserByIdOrEmail($registerData['id']);
        if(!$userInfo){
            return false;
        }
        if ($this->pwdEncryption($registerData['pwdOld'], $userInfo['token']) === $userInfo['pwd']) {
            return true;
        }
        return false;
    }

    /*获取用户上次登录信息 By Terry.*/
    public function getLastLoginInfo($id,$language_id){
        $return = '';
        $res_all = $this->db->from('ip_user_login_info')->where('uid',$id)->order_by('create_time','desc')->limit(2)->get()->result_array();
        $res = isset($res_all[1])?$res_all[1]:'';
        if($res){

            if($language_id == 3){
                $language_id = 2;
            }
            $address = $this->db->where('ip',$res['ip'])->where('type',$language_id)->get('ip_address_info')->row_array();
            if(!$address){
                $address = $this->db->where('ip',$res['ip'])->where('type !=',$language_id)->get('ip_address_info')->row_array();
                if(!$address){
                    return '';
                }
            }
            $address['create_time'] = $res['create_time'];
            if($address['city']==$address['region_name']){
                $address['city'] = '';
            }
            $return = str_replace(array(':time',':contry',':province',':city'), array($address['create_time'],$address['country_name'],$address['region_name'],$address['city']), lang('last_login_info'));
        }
        return $return;
    }

    /*记录用户登录信息（ip地址，地理位置） By Terry.*/
    public function recordMemberLoginInfo($uid){
        $clientIp = get_real_ip();
        $this->db->insert('ip_user_login_info',array('uid'=>$uid,'ip'=>$clientIp));
        $this->db->insert('sync_ip_to_address',array('ip'=>$clientIp));
    }

    /*获取用户的月费coupon信息*/
    public function getMonthlyFeeCoupon($uid,$status=''){
        $this->db->from('users_coupon_monthfee')->where('uid',$uid);
        if($status!==''){
            $this->db->where('status',$status);
        }
        return $this->db->get()->row_array();
    }

    /*使用月费抵用券*/
    public function useMonthlyFeeCoupon($uid,$month_fee_pool){

        $this->load->model('m_coupons');
        $res = $this->db->from('users_coupon_monthfee')->where('uid',$uid)->where('status',0)->get()->row_array();
        if($res){
            $date_time = date('Y-m-d H:i:s');
            $this->db->where('uid', $uid)->update('users_coupon_monthfee', array('status' => 1,'use_time'=>$date_time));
            $join_fee_and_month_fee = $this->getJoinFeeAndMonthFee();
            $monthFee = $join_fee_and_month_fee[$res['coupon_level']]['month_fee'];

            /** 月費變動記錄 */
            $this->load->model('m_commission');
            $monthlyFeeCouponNum = $this->m_coupons->getMonthlyFeeCouponNum($uid);
            $this->m_commission->monthFeeChangeLog($uid,$month_fee_pool,$month_fee_pool + $monthFee,$monthFee,$date_time,3,$monthlyFeeCouponNum,$monthlyFeeCouponNum,0);

            $this->db->where('id', $uid)->set('month_fee_pool','month_fee_pool+'.$monthFee,FALSE)->update('users');
            return 0;
        }else{
            return 1032;
        }
    }

    /*重置用户账户信息，并制定新的邮箱，随即生产新的密码。*/
    public function resetMemberAccount($uid,$new_email='',$new_card_number='',$transfer_card_number='',$refund_card_number='',$admin_id,$remark,$type=1,$status=1){
        if(!$uid){
            return 1033;
        }
        if(!$this->checkMemIdExist($uid)){
            return 1008;
        }
        if (empty($new_email) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $new_email)) {
            return 1001;
        } elseif ($this->checkMemberEmailExist($new_email,$uid)) {
            return 1002;
        }
        if($type == 1){
            if(!$new_card_number){
                return 1016;
            }
            $this->load->model('m_admin_helper');
            $log = $this->m_admin_helper->getTransferLog($uid);
            if($log && time() < strtotime($log['create_time']." +6 month" )){
                return 1034;
            }
        }
        $oldUserInfo = current($this->getInfo($uid));
        $newPwd = strtolower(rangePassword(6,'NUMBER_AND_LETTER'));

        try {
            $this->db->trans_begin();
            if($type == 2){
                $this->db->where('id', $uid)->update('users', array(
                    'status'=>$status,
                    'store_qualified'=>0,
                ));
                $this->m_log->adminActionLog($admin_id,'admin_user_refund','users',$uid,
                    'id_card_num|email|status',$refund_card_number.'|'.$oldUserInfo['email'].'|'.$oldUserInfo['status'],'');
            }else{
                $this->db->where('id', $uid)->update('users', array(
                    'name' => '',
                    'email'=>$new_email,
                    'mobile'=>'',
                    'address'=>'',
                    'pwd'=>$this->pwdEncryption($newPwd,$oldUserInfo['token']),
                    'pwd_ori_md5'=>md5($newPwd),
                    'pwd_take_out_cash'=>'',
                    'id_card_num'=>$new_card_number,
                    'id_card_scan'=>'',
                    'alipay_account'=>'',
                    //'status'=>$status,
                    'store_url'=>$uid,
                    'store_url_modify_counts'=>0,
                    'member_url_prefix'=>$uid,
                    'member_url_modify_counts'=>0,
                    'ewallet_name'=>'',
                    'is_verified_mobile'=>0,
                ));
                $this->m_log->adminActionLog($admin_id,'admin_transfer_user','users',$uid,
                    'name|id_card_num|email|pwd',$oldUserInfo['name'].'|'.$transfer_card_number.'|'.$oldUserInfo['email'].'|'.$oldUserInfo['pwd'],'""|'.$new_card_number.'|'.$new_email.'|'.$newPwd);
            }
            $this->db->where('uid', $uid)->update('user_id_card_info', array(
                'name' => '',
                'id_card_num'=>$new_card_number,
                'id_card_scan'=>'',
                'id_card_scan_back'=>'',
                'check_status'=>0,
            ));
            /** 插入轉讓記錄 */
            $this->db->insert('user_transfer_refund_logs',array(
                'uid'=>$uid,
                'receive_email'=>$new_email,
                'receive_card_number'=>$new_card_number,
                'transfer_card_number'=>$transfer_card_number,
                'refund_card_number'=>$refund_card_number,
                'type'=>$type,
                'check_admin'=>$admin_id,
                'check_info'=>$remark,
            ));
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                return array('newPwd'=>$newPwd);
            } else {
                throw new Exception('error');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return 103;
        }
    }

    /**
     * 检查用户登录
     */
    public function checkUserLogin($loginData) {

        $loginName = trim($loginData['loginName']); //账号
        $pwd = trim($loginData['pwdOriginal']);     //密码

        //为空
        if (!$loginName) {
            return array('success' => FALSE, 'msg' => '* ' . lang('login_need_login_name'));
        }
        
        $userInfo = $this->getUserByIdOrEmail($loginName);//获取用户信息
        //不存在
        if (!$userInfo) {
            return array('success' => FALSE, 'msg' => '* ' . lang('login_login_name_error'));
        }
        
        //2017-01-11 leon 增加登录密码为空的提示信息
        if (!$pwd) {
            return array('success' => FALSE, 'msg' => '* ' . lang('regi_errormsg_repwd_2'));
        }

        //判断密码是不是相同

        if ($this->pwdEncryption($pwd, $userInfo['token']) !== $userInfo['pwd']) {
            return array('success' => FALSE, 'msg' => '* ' . lang('login_pwd_error'));
        }
        //未激活
        if ($userInfo['status'] == '0') {
            return array('success' => FALSE, 'msg' => '* ' . lang('login_status_error'));
        }

        //账户冻结
        if ($userInfo['status'] == '3') {
            return array('success' => FALSE, 'msg' => '* ' . lang('account_disabled'));
        }

        //公司预留账户，禁止登陆
        if ($userInfo['status'] == '4') {
            return array('success' => FALSE, 'msg' => '* ' . lang('company_account_cannot_login'));
        }

        //月费冻结，新增的冻结，leon
        if ($userInfo['status'] == '5') {
            return array('success' => FALSE, 'msg' => '* ' . lang('account_disabled'));
        }

        return array('success' => TRUE, 'userInfo' => $userInfo);
    }

    /**
     * 获取用的信息
     * $idOrEmail  ID 或者是 邮件
     * leon 添加注释信息
     * @param unknown $idOrEmail
     * @return unknown
     */
    public function getUserByIdOrEmail($idOrEmail) {
        //$res = $this->db->from('users')->where('id', $idOrEmail)->or_where("(email='$idOrEmail' and is_verified_email=1)")->or_where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();

        $idOrEmail = trim($idOrEmail);
        if( is_email($idOrEmail) ){
            $res = $this->db->from('users')->where("(email='$idOrEmail' and is_verified_email=1)")->get()->row_array();
        }elseif( strlen($idOrEmail) > 10 ){
            $res = $this->db->from('users')->where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
        }else{
            $res = $this->db->from('users')->where('id', $idOrEmail)->get()->row_array();
        }

        return $res;
    }

    /**
     * @author brady.wang  取消is_verified_mobile字段验证  否则有些手机号登陆不了
     * @param $idOrEmail
     * @return mixed
     */
    public function getUserByIdOrEmail_v1($idOrEmail) {
        //$res = $this->db->from('users')->where('id', $idOrEmail)->or_where("(email='$idOrEmail' and is_verified_email=1)")->or_where("(mobile='$idOrEmail')")->get()->result_array();

        $idOrEmail = trim($idOrEmail);
        if( is_email($idOrEmail) ){
            $res = $this->db->from('users')->where("(email='$idOrEmail' and is_verified_email=1)")->get()->row_array();
        }elseif( strlen($idOrEmail) > 10 ){
            $res = $this->db->from('users')->where("(mobile='$idOrEmail')")->get()->row_array();
        }else{
            $res = $this->db->from('users')->where('id', $idOrEmail)->get()->row_array();
        }

        if (count($res) >1) {

            if( is_email($idOrEmail) ){
                $res = $this->db_slave->from('users')->where("(email='$idOrEmail' and is_verified_email=1)")->get()->row_array();
            }elseif( strlen($idOrEmail) > 10 ){
                $res = $this->db_slave->from('users')->where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
            }else{
                $res = $this->db_slave->from('users')->where('id', $idOrEmail)->get()->row_array();
            }
            return $res;

            //return $res = $this->db->from('users')->where('id', $idOrEmail)->or_where("(email='$idOrEmail' and is_verified_email=1)")->or_where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
        } else {
            if (!empty($res[0])) {
                return $res[0];
            } else {
                return false;
            }
        }

    }

    /**
     * 获取用户信息
     * @param id || email || ids in array || emails in array
     * @return array(
     *  'user id1'=>user info1,
     *  'user id2'=>user info2,
     *  'user id3'=>user info3
     * )
     * @author Terry
     */
    public function getInfo($idOrEmails){
        $this->db->force_master();//强制使用主库查询
        $return = array();
        
        if(empty($idOrEmails)) {
            return $return;
        }
        
       // if(!is_array($idOrEmails)){
       //     $idOrEmails = array($idOrEmails);
       // }
        // m by brady.wang sql语句优化 优化效果好些不大,目前语句都有索引 影响行数也只是2
        // 考虑到传递过来的如果是数组,数组里面无法判断是否全是id 或者全是email,所以当是数组的时候,
        //用没法用正则一个个判断了
        //| id | select_type | table | type        | possible_keys | key           | key_len | ref  | rows | Extra                                   |
        //|  1 | SIMPLE      | users | index_merge | PRIMARY,email | PRIMARY,email | 4,302   | NULL |    2 | Using union(PRIMARY,email); Using where |
        //$res = $this->db->from('users')->where_in('id', $idOrEmails)->or_where_in('email', $idOrEmails)->get()->result_array();
        //$this->load->model('m_store');//并没有用到
        if (!is_array($idOrEmails)) {
            $idOrEmails = (string)$idOrEmails;
            if (preg_match('/^.*@.*/',$idOrEmails)) {
                $res = $this->db->from('users')->where('email', $idOrEmails)->limit(1)->get()->result_array();
            } else {
                $idOrEmails = (int)$idOrEmails;
                $res = $this->db->from('users')->where('id', $idOrEmails)->limit(1)->get()->result_array();
                
            }
        } else {
            foreach($idOrEmails as &$v) {
                $v = (string)$v;
            }
            $res = $this->db->from('users')->where_in('id', $idOrEmails)->or_where_in('email', $idOrEmails)->get()->result_array();
        }
        foreach($res as $item){
            $return[$item['id']] = $item;
        }
        return $return;
    }

    /*现金池转月费池 By Terry.*/
    public function amountToMonthFeePool($uid,$money){
        $this->load->model('m_profit_sharing');
        $userInfo = current($this->getInfo($uid));
        $this->m_profit_sharing->cashToMonthFeePool($userInfo,$money);
    }

    public function getUserSaleInfo($uid){
        return $this->db->from('users_sale_rank_info')->where('uid',$uid)->get()->row_array();
    }

    /*获取会员店铺销售信息。*/
    public function getUserStoreSaleInfo($uid){
        return $this->db->from('users_store_sale_info')->where('uid',$uid)->get()->row_array();
    }

    public function filterForUser($filter){
        foreach ($filter as $k => $v) {
            if ( $v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', strtotime($v)+86400-1);
                    break;
                case 'idEmail':
                    if(is_numeric($v)){
                        $this->db->where('id', $v);
                    }else{
                        $this->db->where("(email = '$v' or name like '%$v%')");
                    }
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
        $this->db->where('id !=',config_item('mem_root_id'));
    }

    public function getUserList($filter, $perPage = 10) {
        $this->db->select('id,name,mobile,email,month_fee_rank,user_rank,create_time,status,parent_id')->from('users');
        $this->filterForUser($filter);
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    public function getUserListNew($filter, $perPage = 10) {
        $user_data = array();
        $this->load->model('tb_users_frozen_remark');
        $this->db->select('id,name,mobile,email,month_fee_rank,user_rank,create_time,status,parent_id')->from('users');
        $this->filterForUser($filter);
        $query = $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        if(isset($query))
        {
            foreach($query as $sult)
            {
                
                $ret_value = $this->tb_users_frozen_remark->check_users_frozen_remark($sult['id']);                
        
                $user_data[] = array
                (
                    'id'        => $sult['id'],
                    'name'      => $sult['name'],
                    'mobile'    => $sult['mobile'],
                    'email'     => $sult['email'],
                    'month_fee_rank' => $sult['month_fee_rank'],
                    'user_rank'      => $sult['user_rank'],
                    'create_time'    => $sult['create_time'],
                    'status'         => $sult['status'],
                    'parent_id'      => $sult['parent_id'],
                    'looks'      => empty($ret_value)?false:true
                );
            }
            return $user_data;
        }        
        return $query;
    }
    
    public function getAdminUserList($filter, $role, $perPage = 10) {
        $this->db->from('admin_users');
        $this->filterForAdminUser($role,$filter);
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    public function filterForAdminUser($role,$filter=''){
        $this->db->where('role !=',0);
       // if($role==2){
       //     $this->db->where('role !=',2);
       // }
        if($filter && is_array($filter)){
            foreach($filter as $k=>$v){
                if($v==='' || $k=='page'){
                    continue;
                }
                switch($k){
                    case 'email':{
                        $this->db->like('email',$v);
                        break;
                    }
                    case 'role':{
                        $this->db->where('role',$v);
                        break;
                    }
                    case 'status':{
                        $this->db->where('status',$v);
                        break;
                    }
                    default:{
                        break;
                    }
                }
            }
        }
    }

    public function getUserListRows($filter) {
        $this->db->from('users');
        $this->filterForUser($filter);
        return $this->db->count_all_results();
    }

    public function getAdminUserListRows($filter,$role) {
        $this->db->from('admin_users');
        $this->filterForAdminUser($role,$filter);
        return $this->db->count_all_results();
    }

    public function changeStatusAdmin($id,$status){
        $this->db->where('id', $id)->update('admin_users', array('status' => $status));
        if($status==1){
            if(REDIS_STOP == 0) {
                $key = "admin_mall:login:submit:error_counts:".$id;
                $time_over = strtotime(date('Ymd')) + 86400;//今天夜里24点的时间戳
                $time_start = $time_over - time();//redis的缓存时间
                $this->redis_set($key,0);//清空账户的密码错误次数
                $this->redis_settimeout($key,$time_start);//初始化redis中的过期时间
            }
        }
    }

    public function deleteAdminAccount($id){
        $this->db->where('id', $id)->delete('admin_users');
    }
    public function resetAdminAccountPw($id){
        $token = $this->createToken(time());
        $psw_str = '1234567890ABCDEFGHIJKLOMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $psw = "";
        for($i=0;$i<6;$i++)   
        {   
            $psw .= $psw_str{mt_rand(0,62)}; //生成随机的6位密码  
        }
        $pwd_encry = $this->createPwdEncry($psw, $token);
        $this->db->where('id', $id)->update('admin_users', array('pwd_encry' => $pwd_encry,'token'=>$token));
        return $psw;
    }
    /**
     * 后台密码加密
     * @param type $pwd
     * @param type $token
     * @return type
     */
    public function createPwdEncry($pwd,$token){
        return sha1('rf8r06f'.$pwd.'ef78fe'.$token.'t56d7fb');
    }

    public function takeOutCash($uid,$data){
        $this->db->where('id', $uid)->set('amount','amount-'.$data['take_out_amount'],FALSE)->update('users');

        if($data['take_cash_type'] == 2){
            $fee = aliapy_withdrawal_fee($data['take_out_amount']);
            $this->db->insert('cash_take_out_logs',array(
                'uid'=>$uid,
                'amount'=>$data['take_out_amount'],
                'actual_amount'=>$fee['actual_fee'],
                'handle_fee'=>$fee['withdrawal_fee'],
                'take_out_type'=>$data['take_cash_type'],
                'account_name'=>trimall($data['account_name']),
                'card_number'=>trimall($data['card_number']),
                'remark'=>$data['remark'],
            ));
        }else if($data['take_cash_type'] == 5){
            $fee=$data['take_out_amount']*0.02;
            if($fee>50){
                $fee=50;
            }
            $this->db->insert('cash_take_out_logs',array(
                'uid'=>$uid,
                'amount'=>$data['take_out_amount'],
                'actual_amount'=>$data['take_out_amount']-$fee,
                'handle_fee'=>$fee,
                'take_out_type'=>$data['take_cash_type'],
                'card_number'=>trimall($data['card_number']),
                'remark'=>$data['remark'],
            ));
        }elseif($data['take_cash_type'] == 6){
            $fee = aliapy_withdrawal_fee($data['take_out_amount']);
            $this->db->insert('cash_take_out_logs',array(
                'uid'=>$uid,
                'amount'=>$data['take_out_amount'],
                'actual_amount'=>$data['take_out_amount'],
                'actual_amount'=>$fee['actual_fee'],
                'handle_fee'=>$fee['withdrawal_fee'],
                'take_out_type'=>$data['take_cash_type'],
                'account_bank'=>$data['bank_name'],
                'subbranch_bank'=>$data['bank_branch_name'],
                'account_name'=>$data['bank_user_name'],
                'card_number'=>trimall($data['bank_number']),
                'remark'=>$data['remark'],
            ));
        }else{
            $this->db->insert('cash_take_out_logs',array(
                'uid'=>$uid,
                'amount'=>$data['take_out_amount'],
                'actual_amount'=>$data['take_out_amount'],
                'handle_fee'=>0,
                'take_out_type'=>$data['take_cash_type'],
                'account_bank'=>$data['account_bank'],
                'subbranch_bank'=>$data['subbranch_bank'],
                'account_name'=>$data['account_name'],
                'card_number'=>trimall($data['card_number']),
                'remark'=>$data['remark'],
            ));
        }

    }


    /*获取月费等级变更说明*/
    public function getMonthFeeLevelChangeNote($uid){
        $return = '';
        $res = $this->db->select('new_month_fee_level')->from('month_fee_level_change')->where('uid',$uid)->get()->row_array();
        if($res){
            $return = sprintf(lang('month_fee_level_change_note'),lang(config_item('levels')[$res['new_month_fee_level']]));
        }
        return $return;
    }

    public function getUserByIds($ids) {
        if(empty($ids)){
            return array();
        }
        $this->db->from('users');
        if(!is_array($ids)){
            $this->db->where('id', $ids);
        }else{
            $this->db->where_in('id', $ids);
        }
        $res = $this->db->get()->result_array();
        $return = array();
        foreach($res as $row){
            $return[$row['id']] = $row;
        }
        return $return;
    }

    public function checkUserEmail($email,$type="reset_password") {
        $emailFormat = trim($email);
        $isRight = TRUE;
        $msg = lang('send_reset_mail_success');
        if (empty($emailFormat) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $emailFormat)) {
            $isRight = FALSE;
            $msg = sprintf(lang('validate_email'),$emailFormat);
        } else {
            $userInfo = $this->checkUserExist($emailFormat);
            if (!$userInfo) {
                $isRight = FALSE;
                $msg = lang('mail_not_exit_in_fogot_pwd');
            }
            if($type == 'active'){
                if($userInfo['status'] != 0){
                    $isRight = FALSE;
                    $msg = lang('activated_account');
                }
            }
        }
        return array('isRight' => $isRight, 'msg' => $msg,'userInfo'=>isset($userInfo)?$userInfo:array());
    }

    public function checkStoreUrlExist($storeUrl,$id){
        if($this->db->from('users')->where('store_url',$storeUrl)->where('id !=',$id)->get()->row_array()){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function checkMemberUrlExist($urlPrefix,$id){
        if($this->db->from('users')->where('member_url_prefix',$urlPrefix)->where('id !=',$id)->get()->row_array()){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function checkMemberUrlLicit($urlPrefix,$id){
        if($this->db->from('users')->where('id',$urlPrefix)->where('id !=',$id)->get()->row_array()){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function checkStoreUrlLicit($urlPrefix,$id){
        if($this->db->from('users')->where('id',$urlPrefix)->where('id !=',$id)->get()->row_array()){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function updateStoreUrl($storeUrl,$id){
        $this->db->where('id', $id)->set('store_url',$storeUrl)->set('store_url_modify_counts','store_url_modify_counts+1',FALSE)->update('users');
    }

    public function updateMemberUrl($urlPrefix,$id){
        $this->db->where('id', $id)->set('member_url_prefix',$urlPrefix)->set('member_url_modify_counts','member_url_modify_counts+1',FALSE)->update('users');
    }

    public function modifyUserInfo($id,$fieldName,$fieldVal){
        if($fieldName=='email'){
            if($this->db->select('id')->from('users')->where('email',$fieldVal)->where('id !=',$id)->get()->row_array()){
                return 1002;
            }
        }
        if($fieldName=='name'){
            $address = $this->db->select('address')->from('users')->where('id',$id)->get()->row_array();
            if($this->db->select('id')->from('users')->where('name',$fieldVal)->where('address',$address['address'])->where('id !=',$id)->get()->row_array()){
                return 1026;
            }
        }
        if($fieldName=='address'){
            $name = $this->db->select('name')->from('users')->where('id',$id)->get()->row_array();
            if($this->db->select('id')->from('users')->where('address',$fieldVal)->where('name',$name['name'])->where('id !=',$id)->get()->row_array()){
                return 1026;
            }
        }
        if($fieldName=='name' || $fieldName=='id_card_num'){
            if($fieldName=='id_card_num'){
                $this->load->model('m_admin_helper');
                $flag = $this->m_admin_helper->uniqueIdCardNum($fieldVal);
                if($flag){
                    return 1036;
                }
            }
            $this->db->where('uid', $id)->set($fieldName,$fieldVal)->update('user_id_card_info');
        }
        if($fieldName=='mobile' && $fieldVal!=''){
            $res = $this->db->from('users')->where('id !=',$id)->where('mobile',$fieldVal)->get()->row_array();
            if($res){
                return 1049;
            }
        }
        $this->db->where('id', $id)->set($fieldName,$fieldVal)->update('users');
        return 0;
    }

    /*从沃好同步用户过来。*/
    public function memberSyncFromWohao($memData){
        $return = array(
            'error_code'=>0,
            'data'=>array()
        );
        $uid = isset($memData['user_id'])?trim($memData['user_id']):0;
        if($uid){
            if(!$this->db->from('users')->where('id', $uid)->get()->row_array()){
                $return['error_code'] = 1008;
                return $return;
            }

            $updateData = array();

            /*email*/
            if(isset($memData['email'])){
                $email = trim($memData['email']);
                if (empty($email) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $email)) {
                    $return['error_code'] = 1001;
                    return $return;
                } elseif ($this->checkUserExist($email,$uid)) {
                    $return['error_code'] = 1002;
                    return $return;
                }
                $updateData['email'] = $email;
            }

            /*password*/
            if(isset($memData['pwd'])){
                $pwd = trim($memData['pwd']);
                $updateData['pwd'] = $pwd;
            }

            /*password token*/
            if(isset($memData['pwd_token'])){
                $pwd_token = trim($memData['pwd_token']);
                $updateData['token'] = $pwd_token;
            }

            /*mobile*/
            if(isset($memData['mobile'])){
                $mobile = trim($memData['mobile']);
                $updateData['mobile'] = $mobile;
            }

            /*country_id*/
            if(isset($memData['country_id'])){
                $updateData['country_id'] = trim($memData['country_id']);
            }

            /*address*/
            if(isset($memData['address'])){
                $updateData['address'] = trim($memData['address']);
            }

            /*store_prefix*/
            if(isset($memData['store_prefix'])){
                $updateData['store_url'] = trim($memData['store_prefix']);
            }

            if(!$updateData){
                $return['error_code'] = 104;
                return $return;
            }

            $this->updateUserInfo($updateData,$uid);
            return $return;

        }else{

            $addData = array();

            /*email*/
            $email = isset($memData['email'])?trim($memData['email']):'';
            if (empty($email) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $email)) {
                $return['error_code'] = 1001;
                return $return;
            } elseif ($this->checkUserExist($email,$uid)) {
                $return['error_code'] = 1002;
                return $return;
            }
            $addData['email'] = $email;

            $addData['pwd'] = isset($memData['pwd'])?trim($memData['pwd']):'';
            $addData['token'] = isset($memData['pwd_token'])?trim($memData['pwd_token']):'';

            /*parent id*/
            $parent_id = isset($memData['parent_id'])?trim($memData['parent_id']):0;
            if (!$this->checkMemIdExist($parent_id)) {
                $return['error_code'] = 1006;
                return $return;
            }
            $addData['parent_id'] = $parent_id;

            $addData['languageid'] = isset($memData['languageid'])?trim($memData['languageid']):1;

            $name = isset($memData['name'])?trim($memData['name']):'';
            if(!$name){
                $return['error_code'] = 1022;
                return $return;
            }
            $addData['name'] = $name;

            $mobile = isset($memData['mobile'])?trim($memData['mobile']):'';
            if(!$mobile){
                $return['error_code'] = 1029;
                return $return;
            }
            $addData['mobile'] = $mobile;

            $addData['country_id'] = isset($memData['country_id'])?trim($memData['country_id']):0;

            $address = isset($memData['address'])?trim($memData['address']):'';
            if(!$address){
                $return['error_code'] = 1027;
                return $return;
            }
            if($this->db->from('users')->where('address', $address)->where('name',$name)->get()->row_array()){
                $return['error_code'] = 101;
                return $return;
            }
            $addData['address'] = $address;

            $addData['create_time'] = isset($memData['create_time'])?strtotime($memData['create_time']):time();
            $addData['from'] = 1;
            $newUserInfo = $this->register($addData);
            $res = $this->enableAccount(array('id'=>$newUserInfo['id']),true);
            $return['data'] = array('user_id'=>$newUserInfo['id']);
            return $return;
        }
    }





    /**
     * 检查注册项目
     * @param  [type] $registerData [description]
     * @param  string $type         [description]
     * @return [type]               [description]
     */
    public function checkRegisterItems($registerData, $type = '') {

        $return = array();

        if(!isset($registerData['email'])){
            $registerData['email'] = isset($registerData['phone']) ? $registerData['phone'] : ( isset($registerData['email_new'])?$registerData['email_new']:'' );
            if(!$registerData['email']){
                unset($registerData['email']);
            }
        }

        foreach ($registerData as $itemName => $itemVal) {
            $itemVal = trim($itemVal);
            $isRight = TRUE;
            $error_code = 0;
            $foreachContinue = FALSE;
            switch ($itemName) {
                case 'email':
                    //检测是不是邮箱
                    if(is_numeric($itemVal)){
                        if(empty($itemVal) || !preg_match('/^1[34578]{1}\d{9}$/',$itemVal)){
                            $isRight = FALSE;
                            $error_code = 1029;
                            break;
                        }
                    }else if (empty($itemVal) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1001;
                        break;
                    }
                    //检测是不是已经存在的用户
                    if ($this->checkUserExist($itemVal) && isset($registerData['reg_type']) && $registerData['reg_type'] == 0) { //未有账户
                        $isRight = FALSE;
                        $error_code = 1002;
                    }else if(isset($registerData['reg_type']) && $registerData['reg_type'] == 1){ //已有账户
                        $user_store = $this->checkUserExist($itemVal);
                        if($user_store === FALSE){
                            $isRight = FALSE;
                            $error_code = 1008;
                        }else if($user_store['parent_id'] > 0){
                            $isRight = FALSE;
                            $error_code = 1038;
                        }
                    }
                    /*else if(isset($registerData['email_new']) && $itemVal != $registerData['email_new']){
                        $isRight = FALSE;
                        $error_code = 1035;
                    }*/
                    break;
                case 'email_re':
                    if (empty($itemVal) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1001;
                    }
                    else if(isset($registerData['email_new']) && $itemVal != $registerData['email_new']){
                        $isRight = FALSE;
                        $error_code = 1035;
                    }
                    else if(isset($registerData['email']) && $itemVal != $registerData['email']){
                        $isRight = FALSE;
                        $error_code = 1035;
                    }
                    break;
                case 'pwdOriginal':
                    $pwdLen = strlen($itemVal);
                    if(isset($registerData['reg_type']) && $registerData['reg_type'] == 1){
                        if(empty($registerData['email_new']) && empty($registerData['email'])){
                            $isRight = FALSE;
                            $error_code = 1010;
                        }else{
                            $registerData['id'] = isset($registerData['email_new']) ? $registerData['email_new'] : $registerData['email'];
                            $registerData['pwdOld'] = $itemVal;
                            if (!$this->checkUserPwd($registerData)) {
                                $isRight = FALSE;
                                $error_code = 1010;
                            }
                        }
                        break;
                    }
                    if ($pwdLen < 6 || $pwdLen > 18 || preg_match("/ /", $itemVal) || preg_match('/^\d+$/', $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1003;
                    } else {
                       // $arrLevelMatched = array(lang('regi_pwd_level_weak') => false, lang('regi_pwd_level_general') => false, lang('regi_pwd_level_strong') => false);
                       // if ($pwdLen < 10) {
                       //     $arrLevelMatched[lang('regi_pwd_level_weak')] = TRUE;
                       // } elseif ($pwdLen < 15) {
                       //     $arrLevelMatched[lang('regi_pwd_level_general')] = TRUE;
                       // } else {
                       //     $arrLevelMatched[lang('regi_pwd_level_strong')] = TRUE;
                       // }
                       // $error_code = '';
                       // foreach ($arrLevelMatched as $level => $isMatched) {
                       //     $error_code.=$isMatched ? "<div class='cur_matched'>$level</div>" : "<div>$level</div>";
                       // }
                    }
                    break;
                case 'pwdOriginal_re':
                    $pwdVal = isset($registerData['pwdVal'])?trim($registerData['pwdVal']):trim($registerData['pwdOriginal']);
                    if ($itemVal !== $pwdVal) {
                        $isRight = FALSE;
                        $error_code = 1004;
                    }
                    if (!$pwdVal) {
                        $isRight = FALSE;
                        $error_code = 1005;
                    }
                    break;
                case 'parent_id':
                    if (!$this->checkMemIdExist($itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1006;
                    }
                    break;
                case 'languageid':
                    if(!in_array($itemVal, array(1,2,3,4))){
                        $isRight = FALSE;
                        $error_code = 1011;
                    }
                    break;
                case 'country_id':
                    if($itemVal===''){
                        $isRight = FALSE;
                        $error_code = 1015;
                    }
                    break;
               // case 'user_rank':
               //     if(!$itemVal){
               //         $isRight = FALSE;
               //         $error_code = 1020;
               //     }
               //     break;
                case 'captcha':
                   // $this->load->model('tb_users_register_captcha');
                    $session=$this->session->userdata($registerData['email']);
                    if(isset($session['email_or_phone'])){
                        $row=$session;
                        if(isset($registerData['is'])){//前端ajax检测不会销毁，提交时销毁
                            $this->session->unset_userdata($registerData['email']);        //删除记录的验证码数据
                        }
                    }
                   // if(is_numeric($registerData['email']) && preg_match('/^1[34578]{1}\d{9}$/',$registerData['email'])){ //手机注册时才判断验证码
                        if (!empty($row) && strtolower($itemVal) == strtolower($row['code'])) {
                            if($row['expire_time'] < time()){
                                $isRight = FALSE;
                                $error_code = 1043;
                            }
                        }else{
                            $isRight = FALSE;
                            $error_code = 1019;
                        }
                   // }
                    break;
                case 'pwdOld':
                    if (!$this->checkUserPwd($registerData)) {
                        $isRight = FALSE;
                        $error_code = 1010;
                    }
                    break;
                case 'name':


                    if(!$itemVal){
                        $isRight = FALSE;
                        $error_code = 1022;
                    }else{
                        $this->load->library('blacklist');     //加载CI黑名单过滤类库，初始化时传递的是存于配置文件中的参数
                        if($this->blacklist->check_text(strtolower($itemVal))->is_blocked()){
                            $isRight = FALSE;
                            $error_code = 1024;
                        }
                    }
                    break;
                case 'mobile':

                    if(!$itemVal){
                        $isRight = FALSE;
                        $error_code = 1023;
                    }
                    break;
                case 'disclaimer':
                    if(!$itemVal){
                        $isRight = FALSE;
                        $error_code = 1028;
                    }
                    break;
                case 'address':
                    if(!$itemVal){
                        $isRight = FALSE;
                        $error_code = 1027;
                    }else{
                        if(isset($registerData['name']) && $registerData['name']){
                            $exist = $this->db->from('users')->where('address',$itemVal)->where('name',$registerData['name'])->count_all_results();
                            if($exist){
                                $isRight = FALSE;
                                $error_code = 1026;
                            }
                        }
                    }
                    break;
                default:
                    $foreachContinue = TRUE;
                    break;
            }
            if ($foreachContinue) {
                continue;
            }
            if ($type == 'api') {
                $return[$itemName] = array('isRight' => $isRight, 'error_code' => is_numeric($error_code) ? $error_code : 0);
            } else {
                $error = config_item('error_code');
                $return[$itemName] = array('isRight' => $isRight, 'msg' => is_numeric($error_code) ? lang($error[$error_code]) : $error_code);
            }
        }

        return $return;
    }

    /**
     * 检查注册项目
     *
     * $type  默认空是注册,'ispwd'表示忘记密码
     * 
     * leon 2017-01-05 新增
     */
    public function checkRegisterItems_new($registerData, $type = '') {
    
        $return = array();
        foreach ($registerData as $itemName => $itemVal) {
            $itemVal = trim($itemVal);
            $isRight = TRUE;//判断内容是不是有问题
            $error_code = 0;//错误信息
            $foreachContinue = FALSE;
            switch ($itemName) {
                case 'email':
                    //检测是不是邮箱
                    if (empty($itemVal) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $itemVal)) {
                        $isRight = FALSE;//错误
                        $error_code = 1052;//邮箱地址有误
                        break;
                    }
                    if($type == 'ispwd'){
                        //检测是不是已经存在的用户
                        if (!$this->checkUserExist($itemVal)) { //检测账户
                            $isRight = FALSE;
                            $error_code = 1008;//用户不存在
                        }
                        break;
                    }else{
                        //检测是不是已经存在的用户
                        if ($this->checkUserExist($itemVal)) { //检测账户
                            $isRight = FALSE;
                            $error_code = 1053;//该邮箱地址已注册
                        }
                        break;
                    }
                    break;
                case 'mobile':
                    if(empty($itemVal) || !preg_match('/^1[34578]{1}\d{9}$/',$itemVal)){
                        $isRight = FALSE;
                        $error_code = 1029;//请输入正确的手机号，提现将进行验证。
                        break;
                    }
                    if($type == 'ispwd'){
                        //检测是不是已经存在的用户
                        if (!$this->checkUserExist($itemVal)) { //检测用户是不是存在
                            $isRight = FALSE;
                            $error_code = 1008;//用户不存在
                        }
                        break;
                    }else{
                        //检测是不是已经存在的用户
                        if ($this->checkUserExist($itemVal)) { //检测用户是不是存在
                            $isRight = FALSE;
                            $error_code = 1049;//手机号已存在
                        }
                        break;
                    }
                    break;
                case 'pwd':
                    if(empty($itemVal)){
                        $isRight = FALSE;//错误
                        $error_code = 1005;//请先填写密码
                        break;
                    }
                    $pwdLen = strlen($itemVal);
                    if ($pwdLen < 6 || $pwdLen > 18 || preg_match("/ /", $itemVal) || preg_match('/^\d+$/', $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1003;//密码格式不正确，密码为6-18位的字符，不能含空格,不能全为数字！
                    }
                    break;
                case 'pwd_again':
                    if(empty($itemVal)){
                        $isRight = FALSE;//错误
                        $error_code = 1005;//请先填写密码
                        break;
                    }
                    $pwdLen = strlen($itemVal);
                    if ($pwdLen < 6 || $pwdLen > 18 || preg_match("/ /", $itemVal) || preg_match('/^\d+$/', $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1003;//密码格式不正确，密码为6-18位的字符，不能含空格,不能全为数字！
                    }
                    break;
                  case 'compare_pwd':     //比较两次输入密码是否相同
                    $pwd = trim($registerData['pwd']);
                    $pwd_again = trim($registerData['pwd_again']);
                    if ($pwd !== $pwd_again) {
                        $isRight = FALSE;
                        $error_code = 1004; //两次输入的密码不一致
                    }
                    break;
                case 'captcha':
                    $account_captcha = trim($registerData['account']);
                    $session=$this->session->userdata($account_captcha);//通过账号 获取信息
                    if(isset($session['email_or_phone'])){
                        if (!empty($session) && strtolower($itemVal) == strtolower($session['code'])) {
                            if($session['expire_time'] < time()){
                                $isRight = FALSE;
                                $error_code = 1043;//验证码过期失效
                            }
                        }else{
                            $isRight = FALSE;
                            $error_code = 1019;//您的验证码有误！
                        }
                    }else{
                        $isRight = FALSE;
                        $error_code = 1019;//您的验证码有误！
                    }

                    break;
                case 'disclaimer':
                    if(!$itemVal){
                        $isRight = FALSE;
                        $error_code = 1028;//没有勾引协议。
                    }
                    break;
                default:
                    $foreachContinue = TRUE;
                    break;
            }
            if ($foreachContinue) {
                continue;
            }
            
            /**
             * is_numeric($error_code) 转化错误数字为正确的信息
             */
            if ($type == 'api') {
                $return[$itemName] = array('isRight' => $isRight, 'error_code' => is_numeric($error_code) ? $error_code : 0);
            } else {
                $error = config_item('error_code');
                $return[$itemName] = array('isRight' => $isRight, 'msg' => is_numeric($error_code) ? lang($error[$error_code]) : $error_code);
            }
        }
        
        return $return;
    }
    
    





    /**
     * 保存用戶提现密码
     * @author Terry
     */
    public function saveTakeCashPwd($uid,$token,$pwd){
        $this->db->where('id', $uid)->update('users', array('pwd_take_out_cash' => $this->encyTakeCashPwd($pwd, $token)));
    }

    /**
     * 保存用戶身份证号
     * @author Terry
     */
    public function saveIdCardNum($uid,$idCardNum){
        $this->db->where('id', $uid)->update('users', array('id_card_num' => $idCardNum));
        $this->db->where('uid', $uid)->update('user_id_card_info', array('id_card_num' => $idCardNum));
    }

    public function saveUserName($uid,$name){
        $this->db->where('id', $uid)->update('users', array('name' => $name));
        $this->db->where('uid', $uid)->update('user_id_card_info', array('name' => $name));
    }

    public function saveUserAddr($uid,$addr){
        $this->db->where('id', $uid)->update('users', array('address' => $addr));
    }

    /**
     * 保存用戶手机号
     * @author Terry
     * @return error_code
     */
    public function saveMobile($uid,$mobileVal){
        if(!is_numeric($mobileVal)){
            return 1029;
        }
        $this->db->where('id', $uid)->update('users', array('mobile' => $mobileVal));
        return 0;
    }

    /**
     * 提现密码加密
     */
    public function encyTakeCashPwd($pwd,$token){
        return sha1($pwd.$token);
    }

    /**
     * 创建初始根会员
     * @author Terry Lu
     */
    public function createRootMembers(){
        $this->db->replace('users', array(
            'id'=>  config_item('mem_root_id'),
            'name'=>'root',
            'parent_id'=>'0',
        ));
    }

    /**
     * 注册账号
     * leon
     */
    public function register($registerData) {
        
        //设置注册时间
        if (!isset($registerData['create_time'])) {
            $registerData['create_time'] = time();
        }
        //设置token值
        if(!isset($registerData['token'])){
            $registerData['token'] = $this->createToken($registerData['create_time']);
        }
        //设置密码值
        if (!isset($registerData['pwd'])) {
            $registerData['pwd'] = $this->pwdEncryption($registerData['pwdOriginal'], $registerData['token']);
            $registerData['pwd_ori_md5'] = md5($registerData['pwdOriginal']);
            unset($registerData['pwdOriginal']);
        }
        //设置国家
        if(isset($registerData['country_id']) && !in_array($registerData['country_id'],array(1,4))){
            $registerData['languageid'] = 2;
        }
        //手机注册 ？ 更新已验证手机标识
        if(isset($registerData['mobile']) && $registerData['mobile'] ){
            $registerData['is_verified_mobile'] = 1;
        }else{
            $registerData['is_verified_email'] = 1; //更新已验证邮箱标识
        }

        
        /** 店主註冊 */
        if(isset($registerData['parent_id']) && $registerData['parent_id']) {
            
            /*构建parent_ids(所有父级id)*/
            $parent_ids_arr = array();
            $count = 1;
            $this->getTenParentIds($registerData['parent_id'],$parent_ids_arr,$count);
            $registerData['parent_ids'] = implode(',', $parent_ids_arr);

            //用户信息的更新和新增
            if($registerData['reg_type'] == 1){      //已有账户
                unset($registerData['reg_type']);
                unset($registerData['pwd']);
                unset($registerData['token']);
                if(isset($registerData['mobile']) && $registerData['mobile'] ){
                    $registerData['id'] = $this->db->select('id')->where('mobile',$registerData['mobile'])->get('users')->row_array()['id']; //获取账号对应的ID
                }else{
                    $registerData['id'] = $this->db->select('id')->where('email',$registerData['email'])->get('users')->row_array()['id'];   //获取账号对应的ID
                }

                $registerData['member_url_prefix'] = $registerData['id'];                        //添加账号对应的地址头 ID
                $registerData['store_url'] = $registerData['id'];                                //设置用户店铺URL

                $this->db->where('id',$registerData['id'])->update('users', $registerData);      //更新用户信息
            }else{    //新账户
                unset($registerData['reg_type']);
                $this->db->insert('users', $registerData);           //添加用户信息
                $registerData['id'] = $this->db->insert_id();        //返回添加的用户ID
            }

            $idCard = array('uid'=>$registerData['id'],'name'=>'');
            $this->db->replace('user_id_card_info',$idCard);            //添加表user_id_card_info中的信息
            
            $this->createUserChildGroupData($registerData['id'], $registerData['parent_id']);  /*插入用户一级组的信息表*/
            
            //生成二維碼圖片
            //create_qr_code($registerData['id']);

        }else{ /** 普通會員註冊 （沒有推薦人）*/
            unset($registerData['reg_type']);
            $registerData['name'] = '';
            $this->db->insert('users', $registerData);                  //插入数据
            $registerData['id'] = $this->db->insert_id();
        }

        $res = $this->enableAccount(array('id'=>$registerData['id']),true);    //激活账户
         
        if($res == 0){
                return 0;
        }else{
                return FALSE;
        }


    }

    public function createUserChildGroupData($id,$parent_id){
        /*插入用户一级组的信息表*/
        if ($parent_id != config_item('mem_root_id')) {
            $this->db->insert('users_child_group_info', array(
                'uid' => $parent_id,
                'group_id' => $id
            ));
        }
    }

    public function getAllParentIds($parent_id,&$parent_ids_arr){
        $parent_ids_arr[] = $parent_id;
        $grand_parent_id = $this->db->select('parent_id')->from('users')->where('id',$parent_id)->get()->row_object()->parent_id;
        if($grand_parent_id){
            $this->getAllParentIds($grand_parent_id, $parent_ids_arr);
        }
    }
    
    /*
     * @desc: 出于性能考虑 向上只递归十层推荐人
     * @author JacksonZheng
     * @date 20170306
     */
     public function getTenParentIds($parent_id,&$parent_ids_arr,&$count){
        $parent_ids_arr[] = $parent_id;
        $grand_parent_id = $this->db->select('parent_id')->from('users')->where('id',$parent_id)->get()->row_object()->parent_id;
        if($grand_parent_id){
            if($count<10) {
                $this->getTenParentIds($grand_parent_id, $parent_ids_arr,++$count);
            } 
        }
    }

    public function pwdEncryption($pwdOriginal, $token) {
        return sha1('!#*' . trim($pwdOriginal) . $token . 'tps');
    }

    public function createToken($curTimestamp) {
        return md5('148' . $curTimestamp . '96tps!#@*');
    }

    public function sendAccountActivationEmail($registerData) {
        $param = array(
            'id'=>$registerData['id'],
            'token'=>$registerData['token'],
            'time'=>time(),
        );

        $param = base64_encode(serialize($param));
        $activeUrl = base_url('enable_account?param=' . $param);
        $data['email'] = $registerData['email'];
        $data['content'] = lang('account_active_email_content') . '<br/><a href="' . $activeUrl . '">' . $activeUrl . '</a>';
        $data['dear'] = lang('dear_');
        $data['email_end'] = lang('email_end');
        $content = $this->load->view('ucenter/public_email',$data,TRUE);

        send_mail($registerData['email'], lang('account_active_email'), $content);
    }

    public function sendChangeEmail($registerData) {
        $param = array(
            'id'=>$registerData['id'],
            'token'=>$registerData['token'],
            'time'=>time(),
        );
        $param = base64_encode(serialize($param));

        $data['email'] = $registerData['email'];
        $activeUrl = base_url('reset_email/?param='.$param);
        $data['content'] = lang('account_change_email') . '<br/><a href="' . $activeUrl . '">' . $activeUrl . '</a>';
        $data['dear'] = lang('dear_');
        $data['email_end'] = lang('email_end');
        $content = $this->load->view('ucenter/public_email',$data,TRUE);

        send_mail($registerData['email'], lang('account_change_email_title'), $content);
    }

    public function sendChangeFundsPwd($registerData) {
        $param = array(
            'id'=>$registerData['id'],
            'token'=>$registerData['token'],
            'time'=>time(),
        );
        $param = base64_encode(serialize($param));

        $data['email'] = $registerData['email'];
        $activeUrl = base_url('reset_funds_pwd/?param='.$param);
        $data['content'] = lang('account_change_funds') . '<br/><a href="' . $activeUrl . '">' . $activeUrl . '</a>';
        $data['dear'] = lang('dear_');
        $data['email_end'] = lang('email_end');
        $content = $this->load->view('ucenter/public_email',$data,TRUE);
        
        send_mail($registerData['email'], lang('account_change_funds_title'), $content);
        $this->db->where('id',$registerData['id'])->update('users',array('send_email_token'=>$param));
        
    }

    public function sendValidateNewEmail($registerData) {
        $param = array(
            'id'=>$registerData['id'],
            'token'=>$registerData['token'],
            'time'=>time(),
            'new_email'=>$registerData['new_email'],
        );
        $param = base64_encode(serialize($param));

        $data['email'] = $registerData['new_email'];
        $activeUrl = base_url('reset_email/validate_email/?param='.$param);
        $data['content'] = lang('validate_new_email') . '<br/><a href="' . $activeUrl . '">' . $activeUrl . '</a>';
        $data['dear'] = lang('dear_');
        $data['email_end'] = lang('email_end');
        $content = $this->load->view('ucenter/public_email',$data,TRUE);

        send_mail($registerData['new_email'], lang('validate_new_content'), $content);
    }

    public function writeUserUpdateTokenTime($uid, $timestamp) {
        $this->db->where('id', $uid)->update('users', array('update_token_time' => $timestamp));
    }

    public function updateCreateTime($uid){
        $curTimestamp = time();
        $this->db->where('id', $uid)->update('users', array('send_email_time' => $curTimestamp));
    }

    public function updatePwdEncy($uid, $newPwdEncy){
        $curTimestamp = time();
        //解除冻结

        $key = "mall:login:submit:error_counts:".$uid;
        $this->redis_del($key);
        $this->db->where('id', $uid)->update('users', array('pwd' => $newPwdEncy,'update_token_time'=>$curTimestamp,'update_time'=>$curTimestamp));
    }

    public function computerUserUpallMoney($upgradeLevelId,$curLevelId,$monthFeeRank){
        $needMoney = $this->computeUserUpgradeMoney($upgradeLevelId, $curLevelId);
        if ($monthFeeRank > $upgradeLevelId) {
            $feeArr = $this->m_user->getJoinFeeAndMonthFee();
            $needMoney += $feeArr[$upgradeLevelId]['month_fee'];
        }
        return $needMoney;
    }

    /**
     * 計算帳戶升級費用
     * @param type $upgradeLevelId
     * @param type $curLevelId
     * @return int
     * @author Terry Lu
     */
    public function computeUserUpgradeMoney($upgradeLevelId,$curLevelId,$uid = FALSE){
        $join_fee_and_month_fee = $this->getJoinFeeAndMonthFee();

        /**
         * 　如果不是免费店铺，查看是否是１.１号之前升级的。如果是，得到当时升级店铺的费用，计算升級差价
         */
        /*$old_join_fee = $this->upgrade_before_1_1_amount($uid,$curLevelId);
        if($old_join_fee !== FALSE){
            $join_fee_and_month_fee[$curLevelId]['join_fee'] = $old_join_fee;
        }*/

        switch ($upgradeLevelId) {
            case 1:
                switch ($curLevelId) {
                    case 1:
                        return 0;
                    case 2:
                        return $join_fee_and_month_fee[1]['join_fee'] - $join_fee_and_month_fee[2]['join_fee'];
                    case 3:
                        return $join_fee_and_month_fee[1]['join_fee'] - $join_fee_and_month_fee[3]['join_fee'];
                    case 4:
                        return $join_fee_and_month_fee[1]['join_fee'] - $join_fee_and_month_fee[4]['join_fee'];
                    case 5:
                        return $join_fee_and_month_fee[1]['join_fee'] - $join_fee_and_month_fee[5]['join_fee'];
                }
                break;
            case 2:
                switch ($curLevelId) {
                    case 1:
                        return 0;
                    case 2:
                        return 0;
                    case 3:
                        return $join_fee_and_month_fee[2]['join_fee'] - $join_fee_and_month_fee[3]['join_fee'];
                    case 4:
                        return $join_fee_and_month_fee[2]['join_fee'] - $join_fee_and_month_fee[4]['join_fee'];
                    case 5:
                        return $join_fee_and_month_fee[2]['join_fee'] - $join_fee_and_month_fee[5]['join_fee'];
                }
                break;
            case 3:
                switch ($curLevelId) {
                    case 1:
                        return 0;
                    case 2:
                        return 0;
                    case 3:
                        return 0;
                    case 4:
                        return $join_fee_and_month_fee[3]['join_fee'] - $join_fee_and_month_fee[4]['join_fee'];
                    case 5:
                        return $join_fee_and_month_fee[3]['join_fee'] - $join_fee_and_month_fee[5]['join_fee'];
                }
                break;
            case 5:
                switch ($curLevelId) {
                    case 1:
                        return 0;
                    case 2:
                        return 0;
                    case 3:
                        return 0;
                    case 4:
                        return $join_fee_and_month_fee[5]['join_fee'] - $join_fee_and_month_fee[4]['join_fee'];
                    case 5:
                        return 0;
                }
                break;

            default:
                break;
        }
        return 0;
    }

    public function checkUserUpgradeData($data,$curLevelId,$uid,$type='upgrade',$monthFeeRank=''){

        $data['payment_method'] = "USD";

        if(!isset($data['upgrade_agree'])){
            $data['upgrade_agree'] = '';
        }
        $error = array();
        foreach($data as $key=>$val){
            switch ($key){
                case 'level':
                    if(!in_array($val, array(1,2,3,5))){
                        $error['level'] = lang('pls_sel_level');
                    }
                   // else if(($type == 'upgrade' || $type=='upall')){
                   //     if($curLevelId != 4 && $curLevelId<=$val){
                   //         $error['level'] = lang('no_need_upgrade');
                   //     }
                   // }
                    else if($type == 'enable' && $curLevelId != $val){
                        $error['level'] = "Hack!";
                    }
                    else{
                        $error['level'] = 0;
                    }
                    break;
                case 'amount':

                    $this->load->model('M_currency','m_currency');
                    if($type == 'enable'){
                        $curLevelId = LEVEL_FREE;
                    }
                    if(!$data['amount'] ||empty($data['amount'])){
                        $error['amount'] = lang('amount_cannot_be_empty');
                    }elseif($type=='upgrade' && $data['amount'] != $this->m_currency->price_format_array($this->computeUserUpgradeMoney($data['level'], $curLevelId,$uid),$data['payment_method'])['money']) {
                        $error['amount'] = 'Hack!';
                    }elseif($type=='upall' && $data['amount'] != $this->m_currency->price_format_array($this->computerUserUpallMoney($data['level'], $curLevelId,$monthFeeRank),$data['payment_method'])['money']) {
                        $error['amount'] = 'Hack!';
                    }else{
                        $error['amount'] = 0;
                    }
                    break;
                case 'payment_method':
                    if (!$data['payment_method']) {
                        $error['payment_method'] = lang('pls_sel_payment');
                    }else{
                        $error['payment_method'] = 0;
                    }
                    break;
                case 'upgrade_agree':
                    if (!$data['upgrade_agree']) {
                        $error['upgrade_agree'] = lang('no_agree');
                    }else{
                        $error['upgrade_agree'] = 0;
                    }
                    break;
                case 'agree':
                    if (!$data['agree']) {
                        $error['agree'] = lang('no_agree');
                    }else{
                        $error['agree'] = 0;
                    }
                    break;
            }
        }

        if(isset($error['level'])&&$error['level']===0 && isset($error['amount'])&&$error['amount']===0 && isset($error['payment_method'])&&$error['payment_method']===0&& isset($error['upgrade_agree'])&&$error['upgrade_agree']===0){
            $error_code = 0;
        }else{
            $error_code = 101;
        }
        return array('error_code'=>$error_code,'error'=>$error);
    }

    public function checkUserUpallData($data,$curLevelId,$monthFeeRank=''){
        if(!isset($data['upall_payment_method'])){
            $data['upall_payment_method']='';
        }

        if($data['upall_payment_method'] == 'eWallet' || $data['upall_payment_method'] == 'tps_amount'){
            $data['upall_payment_method']='USD';
        }

        if ($data['upall_payment_method'] == 'UP' || $data['upall_payment_method'] == 'yspay') {
            $data['upall_payment_method'] = 'CNY';
        }

        if(!isset($data['upall_upgrade_agree'])){
            $data['upall_upgrade_agree'] = '';
        }
        if(!isset($data['upall_agree'])){
            $data['upall_agree'] = '';
        }
        $error = array();
        foreach($data as $key=>$val){
            switch ($key){
                case 'upall_level':
                    if(!in_array($val, array(1,2,3))){
                        $error['upall_level'] = lang('pls_sel_level');
                    }
                    else if($curLevelId <= $val){
                        $error['upall_level'] = lang('no_need_upgrade');
                    }else{
                        $error['upall_level'] = 0;
                    }
                    break;
                case 'upall_amount':

                    $this->load->model('M_currency','m_currency');
                    if(!$data['upall_amount'] ||empty($data['upall_amount'])){
                        $error['upall_amount'] = lang('amount_cannot_be_empty');
                    }elseif($data['upall_amount'] != $this->m_currency->price_format_array($this->computerUserUpallMoney($data['upall_level'], $curLevelId,$monthFeeRank),$data['upall_payment_method'])['money']) {
                        $error['upall_amount'] = 'Hack!';
                    }else{
                        $error['upall_amount'] = 0;
                    }
                    break;
                case 'upall_payment_method':
                    if (!$data['upall_payment_method']) {
                        $error['upall_payment_method'] = lang('pls_sel_payment');
                    }else{
                        $error['upall_payment_method'] = 0;
                    }
                    break;
                case 'upall_upgrade_agree':
                    if (!$data['upall_upgrade_agree']) {
                        $error['upall_upgrade_agree'] = lang('no_agree');
                    }else{
                        $error['upall_upgrade_agree'] = 0;
                    }
                    break;
                case 'upall_agree':
                    if (!$data['upall_agree']) {
                        $error['upall_agree'] = lang('no_agree');
                    }else{
                        $error['upall_agree'] = 0;
                    }
                    break;
            }
        }

        if(isset($error['upall_level'])&&$error['upall_level']===0 && isset($error['upall_amount'])&&$error['upall_amount']===0 && isset($error['upall_payment_method'])&&$error['upall_payment_method']===0&& isset($error['upall_upgrade_agree'])&&$error['upall_upgrade_agree']===0&& isset($error['upall_agree'])&&$error['upall_agree']===0){
            $error_code = 0;
        }else{
            $error_code = 101;
        }
        return array('error_code'=>$error_code,'error'=>$error);
    }

    public function checkUserMonthData($data,$curLevelId){

        $data['payment_method'] = $this->m_global->getPaymentById($data['payment_method'],TRUE);
        if(!isset($data['payment_method'])){
            $data['payment_method']='';
        }
        if(!isset($data['month'])){
            $data['month']='';
        }
        $error = array();
        foreach($data as $key=>$val){
            switch ($key){
                case 'month':
                    if(!$data['month']){
                        $error['month'] = lang('no_month');
                    }else if(!in_array($val, array(1,2,3,6))){
                        $error['month'] = 'Hack!';
                    }
                    else{
                        $error['month'] = 0;
                    }
                    break;
                case 'amount':

                    $this->load->model('M_currency','m_currency');
                    $month_fee = $this->m_user->getJoinFeeAndMonthFee();
                    if(!$data['amount'] ||empty($data['amount'])){
                        $error['amount'] = lang('amount_cannot_be_empty');
                    }elseif( $data['amount'] != $this->m_currency->price_format_array($month_fee[$curLevelId]['month_fee'] * $data['month'],$data['payment_method'])['money'] ||
                        $data['usd_money'] != $this->m_currency->price_format_array($month_fee[$curLevelId]['month_fee'] * $data['month'])['money']){
                        $error['amount'] = 'Hack!';
                    }else{
                        $error['amount'] = 0;
                    }
                    break;
                case 'payment_method':
                    if (!$data['payment_method']) {
                        $error['payment_method'] = lang('pls_sel_payment');
                    }else{
                        $error['payment_method'] = 0;
                    }
                    break;
            }
        }

        if(isset($error['month'])&&$error['month']===0 && isset($error['amount'])&&$error['amount']===0 && isset($error['payment_method'])&&$error['payment_method']===0){
            $error_code = 0;
        }else{
            $error_code = 101;
        }
        return array('error_code'=>$error_code,'error'=>$error);
    }

    public function checkUpgradeMonthData($data){

        if(!isset($data['month_payment_method'])){
            $data['month_payment_method']='';
        }
        if(!isset($data['level'])){
            $data['level']='';
        }
        if(!isset($data['agree'])){
            $data['agree']='';
        }
        $error = array();

        $data['month_payment_method'] = $this->m_global->getPaymentById($data['month_payment_method'],TRUE);

        foreach($data as $key=>$val){
            switch ($key){
                case 'level':
                    if(!$data['level']){
                        $error['level'] = 'Hack!';
                    }else if(!in_array($val, array(1,2,3))){
                        $error['level'] = 'Hack!';
                    }
                    else{
                        $error['level'] = 0;
                    }
                    break;
                case 'amount':

                    $this->load->model('M_currency','m_currency');
                    if(!in_array($data['level'], array(1,2,3))){
                        break;
                    }
                    $month_fee = $this->m_user->getJoinFeeAndMonthFee();
                    if(!$data['amount'] ||empty($data['amount'])){
                        $error['amount'] = lang('amount_cannot_be_empty');
                    }elseif( $data['amount'] != $this->m_currency->price_format_array($month_fee[$data['level']]['month_fee'] ,$data['month_payment_method'])['money']
                        || $data['usd_money'] != $this->m_currency->price_format_array($month_fee[$data['level']]['month_fee'])['money']){
                        $error['amount'] = 'Hack!';
                    }else{
                        $error['amount'] = 0;
                    }
                    break;
                case 'month_payment_method':
                    if (!$data['month_payment_method']) {
                        $error['month_payment_method'] = lang('pls_sel_payment');
                    }else{
                        $error['month_payment_method'] = 0;
                    }
                    break;
                case 'agree':
                    if (!$data['agree']) {
                        $error['agree'] = lang('no_agree');
                    }else{
                        $error['agree'] = 0;
                    }
                    break;
            }
        }

        if(isset($error['level'])&&$error['level']===0 && isset($error['amount'])&&$error['amount']===0 && isset($error['month_payment_method'])&&$error['month_payment_method']===0 && isset($error['agree'])&&$error['agree']===0){
            $error_code = 0;
        }else{
            $error_code = 101;
        }
        return array('error_code'=>$error_code,'error'=>$error);
    }

    /**
     * 验证用户升级时填写的补充信息
     * @param type $data
     * @return type
     */
    public function checkUserInfoData($data){
        if(!isset($data['info_country'])){
            $data['info_country']='';
        }
        if(!isset($data['info_id_card_num'])){
            $data['info_id_card_num']='';
        }

        $error = array();
        $error_code = 0;
        foreach($data as $key=>$val){
            switch ($key){
                case 'info_country':
                    if(!in_array($val, array(1,2,3,4,5,6,7))){
                        $error['info_country'] = lang('input_country');
                        $error_code = 101;
                    }else{
                        $error['info_country'] = 0;
                    }
                    break;
                case 'info_id_card_num':
                    if ($data['info_country']==1) {
                        if(!$val){
                            $error['info_id_card_num'] = lang('pls_input_person_id_card_num');
                            $error_code = 101;
                        }else{
                            require APPPATH . 'third_party/idCartNumCheck.class.php';
                            $idCartNumCheckObj = new idCartNumCheck();
                            $checkRes = $idCartNumCheckObj ->checkIdentity($val);
                            if($checkRes){
                                $error['info_id_card_num']=0;
                            }else{
                                $error['info_id_card_num'] = lang('person_id_card_num_error');
                                $error_code = 101;
                            }
                        }
                    }else{
                        $error['info_id_card_num'] = 0;
                    }
                    break;
            }
        }
        return array('error_code'=>$error_code,'error'=>$error);
    }

    /**
     * 获取提现记录
     * @param type $filter
     * @param type $page
     * @param type $perPage
     * @return type
     * @author Terry Lu
     */
    public function getCashTakeOutLogs($filter, $page = false, $perPage = 10) {
        $this->db->from('cash_take_out_logs');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', $v);
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }

    public function getCashTakeOutLogsTotalRows($filter) {
        $this->db->from('cash_take_out_logs');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', $v);
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->get()->num_rows();
    }

    /**
     * 获取现金池到月费池日志
     * @param type $filter
     * @param type $page
     * @param type $perPage
     * @return type
     */
    public function getCashToMonthFeeLogs($filter, $page = false, $perPage = 10) {
        $this->db->from('cash_to_month_fee_logs');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', $v);
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }

    /**
     * 统计现金池到月费池记录条数
     * @param type $filter
     * @return type
     */
    public function getCashToMonthFeeLogsTotalRows($filter) {
        $this->db->from('cash_to_month_fee_logs');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', $v);
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->get()->num_rows();
    }

    /**
     * 奖励用户分红点（用户激活付费店铺、用户店铺升级时触发）
     * @author Terry Lu
     */
    public function rewardMemSharingPoint($uid,$rewardPoint,$validity='') {
        $validity = $validity?$validity:'+15 months';
        $this->db->insert('users_sharing_point_reward', array(
            'uid' => $uid,
            'point' => $rewardPoint,
            'end_time' => date('Y-m-d', strtotime($validity, time()))
        ));
    }

    /**
     * 获取用户总分红点
     * @author Terry
     */
    public function getTotalSharingPoint($uid){
        $userInfo = $this->getUserByIdOrEmail($uid);
        $totalPoint = $userInfo['profit_sharing_point'];
        $res = $this->getRewardSharingPointData($uid);
        $curDate = date('Y-m-d');
        foreach($res as $item){
            if($item['end_time']>=$curDate){
                $totalPoint+=$item['point'];
            }
        }
        return $totalPoint;
    }

    public function getRewardSharingPointData($uid){
        return $this->db->from('users_sharing_point_reward')->where('uid',$uid)->get()->result_array();
    }

    /**
     * 更新用户升级时填写的补充信息。
     */
    public function updateUserOtherInfo($uid,$data){
        $this->db->where('id', $uid)->set('country_id', $data['info_country'])->set('id_card_num',isset($data['info_id_card_num'])?$data['info_id_card_num']:'')->update('users');
    }

    /**
     * 绑定支付宝账户
     * @param type $uid
     */
    public function alipay_binding($uid,$data){
        $this->db->where('id', $uid)->set($data)->update('users');
    }
    /**
     * 绑定支付宝账户
     * @param type $uid
     */
    public function alipay_select($data){
        return $this->db->select('id')->from('users')->where('alipay_account',$data)->get()->row_array();
    }
    /**
     * 冻结账户
     * @param type $uid
     */
    public function disableAccount($uid){
        
        $sqls = "SELECT status FROM users WHERE id = '".$uid."'";
        $querys = $this->db->query($sqls);
        $rows = $querys->row();
        if(isset($rows))
        {
            
            if(2==$rows->status)
            {               
                $this->db->query("UPDATE users SET status=5 WHERE id='".$uid."'");
            }
            
            else if(1==$rows->status)
            {
                $this->db->query("UPDATE users SET status=3 WHERE id='".$uid."'");
            }
            
        }
        
    }

    /**
     * 解冻（重开）账户
     * @param type $uid
     */
    public function reenableAccount($uid){
        $sql = "SELECT status FROM users WHERE id = '".$uid."'";
        $query = $this->db->query($sql);
        $rows = $query->row();
        if(isset($rows))
        {
            if(5==$rows->status)
            {
                $this->db->where('id', $uid)->where('status',5)->set('status', 1)->update('users');
            }
            else if(3==$rows->status)
            {
                $this->db->where('id', $uid)->where('status',3)->set('status', 1)->update('users');
            }
        }
        
    }

    /**
     * 账户激活
     * @param type $data=array('id'=>用户id,'token'=>激活令牌);
     * @param boolean $noVeri=ture(无需验证，激活接口直接调用) or false(用户邮件激活链接激活，需验证token以及过期时间)
     * @return int $error_code #0代表成功，其他值代表各种失败错误码
     */
    public function enableAccount($data, $noVeri = false) {
        $userInfo = $this->getUserByIdOrEmail($data['id']);
        if(!$userInfo){
            return 1008;
        }
        if(!$noVeri){
            if($data['token']!==$userInfo['token']){
                return 102;
            }
            if($userInfo['send_email_time']<(time() - 3600*3)){
                return 1012;
            }
        }

        if($userInfo['status']==1){
            return 0;
        }

        /*-----------账户激活-----------------*/
        $this->db->trans_begin();

        if($this->m_global->isStore($userInfo['parent_id'])){  /** 店主激活 */

            /*更改用户状态（激活or激活未付费）*/
            $status=1;
            $this->db->where('id', $data['id'])->update('users', array('status' => $status,'store_url' => $data['id'],'member_url_prefix'=>$data['id'],'enable_time'=>date('Y-m-d H:i:s')));

            /*统计会员每月推荐的人*/
            $res_stat_intr_mem_month = $this->db->from('stat_intr_mem_month')->where('year_month',date('Ym'))->where('uid',$userInfo['parent_id'])->get()->row_array();
            if($res_stat_intr_mem_month){
                $this->db->where('year_month',$res_stat_intr_mem_month['year_month'])->where('uid',$res_stat_intr_mem_month['uid'])->set('member_free_num','member_free_num+1',FALSE)->update('stat_intr_mem_month');
            }else{
                $this->db->insert('stat_intr_mem_month', array(
                    'year_month' => date('Ym'),
                    'uid' => $userInfo['parent_id'],
                    'member_free_num' => 1
                ));
            }

            $this->load->model('m_referrals_count');
            $this->m_referrals_count->join_referrals_count($userInfo['parent_id'],4,4);

            /** 增加父類的激活會員數 */
            $this->addEnableChildCount($data['id']);

        }else{ /** 普通會員激活 */
            $this->db->where('id', $data['id'])->update('users', array('status' => 1,'enable_time'=>date('Y-m-d H:i:s')));
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 103;
        } else {
            $this->db->trans_commit();
            return 0;
        }
        /*-----------/账户激活-----------------*/
    }

    /*检查用户是否同步给walhao了*/
    public function checkIfSyncedToWalhao($uid){
        $res = $this->db->select('sync_walhao')->from('users')->where('id',$uid)->get()->row_array();
        if($res && $res['sync_walhao']==1){
            $return = TRUE;
        }else{
            $return = FALSE;
        }
        return $return;
    }

    /*将需要同步给沃好的信息加入队列 by Terry*/
    public function addInfoToWohaoSyncQueue($uid,$syncItems){
        if(config_item('wohao_api_status')){
            foreach($syncItems as $item){
                if($item==0){
                    $this->db->update('users', array('sync_walhao'=>1), array('id' => $uid));
                }
                if($this->checkIfSyncedToWalhao($uid)){
                    $this->db->insert('sync_to_wohao', array(
                        'uid' => $uid,
                        'sync_item' => $item
                    ));
                }
            }
        }
    }

    /*处理沃好同步队列*/
    public function doWohaoSyncQueue(){

        $cronName = 'doWohaoSyncQueue';

        $cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
        if($cron){
            if($cron['false_count'] > 15){
                $this->db->delete('cron_doing', array('cron_name' => $cronName));
                return false;
            }
            $this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
            return false;
        }

        $this->db->insert('cron_doing',array(
            'cron_name'=>$cronName
        ));

        /*Begin*/
        $syncData = array();
        $ids = array();
        $resItems = $this->db->query("select id,uid,sync_item from sync_to_wohao limit 200")->result_array();
        foreach($resItems as $item){
            $syncData[$item['uid']][] = $item['sync_item'];
            $ids[] = $item['id'];
        }
        foreach($syncData as $uid=>$syncItems){
            if(in_array(0, $syncItems)){
                $this->syncMemberToWohao($uid);
                continue;
            }
            $syncItemsUnique = array_unique($syncItems);
            $syncUpdateData = array();
            $userInfo = current($this->getInfo($uid));
            foreach($syncItemsUnique as $syncItem){
                $itemInfoArr = config_item('sync_to_wohao_items')[$syncItem];
                if($syncItem==12){
                    $syncUpdateData[$itemInfoArr[0]] = date('Y-m-d H:i:s',$userInfo[$itemInfoArr[1]]);
                }else{
                    $syncUpdateData[$itemInfoArr[0]] = $userInfo[$itemInfoArr[1]];
                }
                if($syncItem==1){
                    $syncUpdateData[$itemInfoArr[0]] = strtolower($userInfo[$itemInfoArr[1]]);
                }
            }
            $this->syncMemberToWohaoUpdate($uid,$syncUpdateData);
        }
        $this->db->query('delete from sync_to_wohao where id in('.implode(',', $ids).')');
        /*End*/

        $this->db->delete('cron_doing', array('cron_name' => $cronName));
    }

    /** 列队 处理 发送收据邮件 */
    public function sendEmailSyncQueue(){

        $cronName = 'sendEmailSyncQueue';
        $cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
        if($cron){
            if($cron['false_count'] > 29){
                $this->db->delete('cron_doing', array('cron_name' => $cronName));
                return false;
            }
            $this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
            return false;
        }

        $receipts = $this->db->from('sync_send_receipt_email')->limit(100)->get()->result_array();
        if($receipts){

            if (!is_dir('upload/order_receipt/')) {
                mkdir('upload/order_receipt/', DIR_READ_MODE); // 使用0755创建文件
            }

            $this->db->insert('cron_doing',array(
                'cron_name'=>$cronName
            ));

            $LanArr = $this->getMonthFeeMailLan();
            $this->load->model('m_user_helper');
            $goFeeUrl = 'https://mall.tps138.com/ucenter/commission?msg=addMonthFeeNote#month_fee';

            foreach($receipts as $receipt){

                $email = $this->db->select('email,country_id')->from('users')->where('id',$receipt['uid'])->get()->row_array();
                if(empty($email)){
                    continue;
                }
                if($email['country_id']==1){
                    $lang = 'zh';
                }elseif($email['country_id']==4){
                    $lang = 'hk';
                }elseif($email['country_id']==3){
                    $lang = 'kr';
                }else{
                    $lang = 'english';
                }

                $real_lang = $LanArr[$lang];
                $data['email'] = $email['email'];
                $data['dear'] = $real_lang['dear'];
                $data['email_end'] = $real_lang['email_end'];
                $file_path = "";

                if($receipt['type'] == '1') //订单上传的运单号
                {
//                    $order = $this->db->select('pay_time,freight_info')->where('order_id',$receipt['order_id'])->get('trade_orders')->row_array();
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one('pay_time,freight_info',["order_id"=>$receipt['order_id']]);
                    $freight_info = explode('|',$order['freight_info']);
                    $freight_info_name = $this->m_user_helper->getFreightName($freight_info[0]);
                    $data['content'] = sprintf($real_lang['deliver_content_'],$order['pay_time'],$receipt['order_id'],$freight_info_name,$freight_info[1]);
                    $content = $this->load->view('ucenter/public_email',$data,TRUE);
                    send_mail($email['email'],$real_lang['deliver_title_'],$content,array(),"");
                }
                else if($receipt['type'] == '0') //付款收据
                {
                    $file_path = 'upload/order_receipt/'.$receipt['order_id'].'.pdf';
                    if(file_exists($file_path)){

                        $data['content'] = $real_lang['receipt_content_'];
                        $content = $this->load->view('ucenter/public_email',$data,TRUE);
                        send_mail($email['email'],$real_lang['receipt_title_'],$content,array(),$file_path);
                    }
                }
                else if($receipt['type'] == '2') //4月份活動訂單
                {
                    $data['content'] = $real_lang['april_email_content'];
                    $content = $this->load->view('ucenter/public_email',$data,TRUE);
                    send_mail($email['email'],$real_lang['april_email_title'],$content,array(),"");

                }else if($receipt['type'] == '3'){ //當天欠月費的
                    $startDate = date("Y-m-d");
                    $endDate = date("Y-m-d",strtotime("+1 month"));

                    $mailTitle = $real_lang['month_fee_fail_notice'];
                    $data['content'] = sprintf($real_lang['month_fee_fail_content'], $startDate,$endDate,$real_lang['7day'],$goFeeUrl);
                    $content = $this->load->view('ucenter/public_email',$data,TRUE);
                    send_mail($email['email'], $mailTitle, $content);
                    $this->db->where('uid', $receipt['uid'])->update('users_month_fee_fail_info', array('last_mail_date' => date('Y-m-d')));

                }else if($receipt['type'] == '4'){ //6天仍未付费的会员发送提醒邮件
                    $startDate = date('Y-m-d',strtotime(date("Y-m-d"))-3600*24*7);
                    $endDate = date("Y-m-d",strtotime("$startDate +1 month"));

                    $mailTitle = $real_lang['month_fee_fail_notice'];
                    $data['content'] = sprintf($real_lang['month_fee_fail_content'], $startDate,$endDate,$real_lang['24hours'],$goFeeUrl);
                    $content = $this->load->view('ucenter/public_email',$data,TRUE);
                    send_mail($email['email'], $mailTitle, $content);
                    $this->db->where('uid', $receipt['uid'])->update('users_month_fee_fail_info', array('last_mail_date' => date('Y-m-d')));

                }else if($receipt['type'] == '5'){ //给7天以上90天内为支付月费的会员发送提醒邮件，每周发送一次（第8天单独发送一次）

                    $v = $this->db->where('uid',$receipt['uid'])->get('users_month_fee_fail_info')->row_array();

                    $timePeriod = 7*24*3600;
                    $curDayTimestamp = strtotime(date('Y-m-d'));
                    $startDate = date('Y-m-d',strtotime($v['create_time']));
                    if ( ($curDayTimestamp - strtotime($v['last_mail_date']) >= $timePeriod) || $curDayTimestamp-strtotime($startDate)==3600*24*8 ) {

                        $endDate = date("Y-m-d", strtotime("$startDate +1 month"));

                        $mailTitle = $real_lang['month_fee_fail_notice'];
                        $data['content'] = sprintf($real_lang['month_fee_fail_content_90'], $startDate, $endDate, $goFeeUrl);
                        $content = $this->load->view('ucenter/public_email',$data,TRUE);
                        send_mail($email['email'], $mailTitle, $content);
                        $this->db->where('uid', $receipt['uid'])->update('users_month_fee_fail_info', array('last_mail_date' => date('Y-m-d')));
                    }
                }elseif($receipt['type'] == '6'){//5天后还未关闭工单的发送邮件提醒
                    $data['content'] = sprintf($real_lang['tickets_email_content'],$receipt['order_id'],7);
                    $mailTitle =  sprintf($real_lang['tickets_email_title'],$receipt['order_id']);
                    $content = $this->load->view('ucenter/public_email',$data,TRUE);
                    send_mail($email['email'],$mailTitle,$content,array(),"");
                }elseif($receipt['type'] == '7'){//10天后还未关闭工单的发送邮件提醒
                    $data['content'] = sprintf($real_lang['tickets_email_content'],$receipt['order_id'],2);
                    $mailTitle =  sprintf($real_lang['tickets_email_title'],$receipt['order_id']);
                    $content = $this->load->view('ucenter/public_email',$data,TRUE);
                    send_mail($email['email'],$mailTitle,$content,array(),"");
                }
                $this->db->where('id',$receipt['id'])->delete('sync_send_receipt_email');
            }
        }
        $this->db->delete('cron_doing', array('cron_name' => $cronName));
    }

    /*会员同步到沃好*/
    public function syncMemberToWohao($id){
        $userInfo = current($this->getInfo($id));
        $token = time();
        $sign = api_create_sign($token);
        $postData = array(
            'user_id'=>$userInfo['id'],
            'email'=>$userInfo['email']?strtolower($userInfo['email']):null,
            'pwd'=>$userInfo['pwd'],
            'pwd_token'=>$userInfo['token'],
            'parent_id'=>$userInfo['parent_id'],
            'languageid'=>$userInfo['languageid']?$userInfo['languageid']:1,
            'name'=>$userInfo['name']?$userInfo['name']:null,
            'mobile'=>$userInfo['mobile']?$userInfo['mobile']:null,
            'country_id'=>(int)$userInfo['country_id'],
            'address'=>$userInfo['address']?$userInfo['address']:nulll,
            'store_prefix'=>strtolower($userInfo['store_url']),
            'store_level'=>$userInfo['user_rank'],
            'create_time'=>date('Y-m-d H:i:s',$userInfo['create_time']),
            'status'=>$userInfo['status'],
            'token'=>$token,
            'sign'=>$sign,
        );
        $url = 'http://'.config_item('wohao_api_host').'/index.php/home/userapi/adduser';
        $res = tps_curl_post2($url, $postData);
        if($res->error_code){
            $this->m_log->createInterfaceLog('同步用户到沃好失败,return:'.var_export($res, 1).';用户id:'.$userInfo['id'].'。');
        }
    }

    /*会员信息变更同步到沃好*/
    public function syncMemberToWohaoUpdate($id,$updateData){
        $token = time();
        $sign = api_create_sign($token);
        $postData = array(
            'user_id'=>$id,
            'token'=>$token,
            'sign'=>$sign,
        );
        if(isset($updateData['store_prefix'])){
            $updateData['store_prefix'] = strtolower($updateData['store_prefix']);
        }
        $postData = array_merge($postData,$updateData);
        $url = 'http://'.config_item('wohao_api_host').'/index.php/home/userapi/adduser';
        $res = tps_curl_post2($url, $postData);
        if($res->error_code){
            $this->m_log->createInterfaceLog('更新用户信息到沃好失败,return:'.var_export($res, 1).';用户id:'.$id.';变更信息:'.var_export($updateData, 1));
        }
    }

    /**
     * 增加父類激活會員數
     */
    public function addEnableChildCount($child_id){
        $user = $this->db->from('users')->select('parent_ids')->where('id',$child_id)->get()->row_array();
        $parent_ids = explode(',',$user['parent_ids']);
        array_pop($parent_ids);//去掉最後的公司號
        if($parent_ids)foreach($parent_ids as $uid){
            $uid=(int)$uid;
            //注释掉 child_count 的更新
//            $this->db->where('id', $uid)->set('child_count', 'child_count+' . 1, FALSE)->update('users');
        }
    }

    /**
     * 更新用户每月发展的会员数统计。（用户店铺升级时触发）
     * @author Terry
     */
    public function updateIntrMemMonth($userInfo,$userCurLevel){
        if ($userCurLevel == 3) {
            $updateField = 'member_silver_num';
        } elseif ($userCurLevel == 2) {
            $updateField = 'member_platinum_num';
        } elseif ($userCurLevel == 5) {
            $updateField = 'member_bronze_num';
        } else {
            $updateField = 'member_diamond_num';
        }

        switch ($userInfo['user_rank']) {
            case 5:
                $numLessField = 'member_bronze_num';
                break;
            case 4:
                $numLessField = 'member_free_num';
                break;
            case 3:
                $numLessField = 'member_silver_num';
                break;
            case 2:
                $numLessField = 'member_platinum_num';
                break;
            default:
                break;
        }

        /*计算套装销售额*/
        $this->load->model('tb_users_level_change_log');
        $maxOldLevel =$this->tb_users_level_change_log->getMaxLevelOfLast($userInfo['id']);
        $pro_set_amount = (config_item('join_fee_and_month_fee')[$userCurLevel]['join_fee']-config_item('join_fee_and_month_fee')[$maxOldLevel]['join_fee'])*100;
        if($pro_set_amount<0){
            $pro_set_amount=0;
        }

        $res_stat_intr_mem_month = $this->db->from('stat_intr_mem_month')->where('year_month',date('Ym'))->where('uid',$userInfo['parent_id'])->get()->row_array();
        if($res_stat_intr_mem_month){
            $this->db->where('year_month',$res_stat_intr_mem_month['year_month'])->where('uid',$res_stat_intr_mem_month['uid'])->set($updateField, $updateField.'+1', FALSE)->set('pro_set_amount','pro_set_amount+'.$pro_set_amount, FALSE)->update('stat_intr_mem_month');
           //更新父类的升级会员数
            $upgradeOrderList = $this->db->select('pay_time')->from('user_upgrade_order')->where('uid',$userInfo['id'])->where('status',2)->order_by("pay_time", "desc")->limit(2)->get()->result_array();
            if((isset($upgradeOrderList[1]) && date('Ym',strtotime($upgradeOrderList[1]['pay_time']))==date('Ym')) || (!isset($upgradeOrderList[1]) && date('Ym',strtotime($userInfo['enable_time']))==date('Ym'))){
                $newCount = $res_stat_intr_mem_month[$numLessField]>0?$res_stat_intr_mem_month[$numLessField]-1:0;
                $this->db->where('year_month',$res_stat_intr_mem_month['year_month'])->where('uid',$res_stat_intr_mem_month['uid'])->set($numLessField, $newCount)->update('stat_intr_mem_month');
            }
        }else{
            $this->db->insert('stat_intr_mem_month', array(
                'year_month' => date('Ym'),
                'uid' => $userInfo['parent_id'],
                $updateField => 1,
                'pro_set_amount'=>$pro_set_amount
            ));
        }
    }

    /**
     * 当新加入的会员满足日分红时，立即加入日分红用户表开始分红。
     * @param type $userInfo
     */
    public function dayProfitShareForNewMem($userInfo){
        //用户从免费到付费会员触发 上级相当于推荐了一个等级用户 获得日分红条件。START
        $parent_id = $userInfo['parent_id'];//上级id

        $parentInfo = current($this->getInfo($parent_id));
        //用户从免费到付费会员触发 上级相当于推荐了一个铜级用户 获得日分红条件。END*/

        /*免费会员，卖了套装的，则自动升级为合格店铺  store_qualified字段改为1 */
        //store_qualified 是否合格店铺  user_rank = 4为免费店铺
        if(!$parentInfo['store_qualified'] && $parentInfo['user_rank']==4){
            $this->qualifiedUser($parent_id);
        }
    }

    /**
     * 将某用户店铺设置为合格店铺。
     * @param int $uid 用户id
     * @author Terry
     */
    public function qualifiedUser($uid){
        $this->db->where('id',$uid)->set('store_qualified',1)->update('users');
    }

    public function getChildMems($uid,&$childIds=array(),$level=1){
        $childIdsRes = $this->db->select('id')->from('users')->where('parent_id',$uid)->get()->result_array();
        foreach($childIdsRes as $childIdRes){
            $childId = $childIdRes['id'];
            $childIds[] = $childId;
            $levelChild = $level-1;
            if($levelChild==0){
                continue;
            }
            $this->getChildMems($childId,$childIds,$levelChild);
        }
    }

    /**
     * 获取某用户下两级下线用户id
     * @param int $uid 用户id
     * @return array
     * @author carl
     */
    public function getChildMemberForLevelTwo($uid){
        $uid = intval($uid);
        $sql = 'select id from users where parent_id in (select id from users where parent_id='.$uid.')';
        $sql .= ' union select id from users where parent_id='.$uid;
        $res = $this->db->query($sql)->result_array();
        $ids = [];
        foreach ($res as $val) {
            $ids[] = $val['id'];
        }
        return $ids;
    }

    public function getLevelEnableAmount($user_id,$user_rank){
        $join_fee_and_month_fee = $this->getJoinFeeAndMonthFee();

        if(in_array($user_id,config_item('leader_list'))){
            return $join_fee_and_month_fee[$user_rank]['month_fee'];
        }

        switch ($user_rank) {
            case 1:
                $return = $join_fee_and_month_fee[1]['join_fee']+$join_fee_and_month_fee[1]['month_fee'];
                break;
            case 2:
                $return = $join_fee_and_month_fee[2]['join_fee']+$join_fee_and_month_fee[2]['month_fee'];
                break;
            case 3:
                $return = $join_fee_and_month_fee[3]['join_fee']+$join_fee_and_month_fee[3]['month_fee'];
                break;
            default:
                break;
        }
        return isset($return)?$return:0;
    }


    /**
     * 获取下线会员
     * @param type $uid
     */
    public function getChildMembers($uid) {
        return $this->db->select('id')->from('users')->where('parent_id', $uid)->order_by("id")->get()->result_array();
    }

    public function updatePwd($data,$token) {
        $new_pwd = $this->pwdEncryption($data['pwdOriginal'], $token);
        $this->db->where('id', $data['id'])->update('users', array('pwd' => $new_pwd));
    }

    public function updateUserInfo($data,$user_id){
        $this->db->where('id', $user_id)->update('users', $data);
        return $this->db->affected_rows();
    }
    
    public function updateUserCardInfo($data,$user_id) {
        $this->db->where('uid', $user_id)->update('user_id_card_info', $data);
        return $this->db->affected_rows();
    }
    
    public function updateImg($uid,$src){
        $user = $this->getUserByIdOrEmail($uid);
        //删除会员之前的图片
        if($user['img']){
            if(file_exists($user['img'])) {
                unlink($user['img']);
            }
        }
        $this->db->where('id', $uid)->update('users', array('img' => $src));
        return $this->db->affected_rows();
    }

    //得到用戶等級
    function getUserRank($uid)  {

        $user = $this->db->select('user_rank')->from('users')->where('id', $uid)->get()->row_array();
        return $user['user_rank'];
    }

    /**
     * 用户从免费到付费会员时触发其所有上线职称的变动。
     * @param type $uid
     */
    public function freeToFeeAffectParent($uid){
        if(config_item("credit_switch") == "on") {
            return true;
        }
    	//做为一个合格店铺，触发影响他多级上线的职称
        $userInfo = current($this->getInfo($uid));
        $parent_id = $userInfo['parent_id'];
        if ($parent_id!=  config_item('mem_root_id')) {
            $saleRankInfo = $this->db->from('users_sale_rank_info')->where('uid', $parent_id)->get()->row_array();
            if (!$saleRankInfo) {
                $this->db->replace('users_sale_rank_info', array(
                    'uid' => $parent_id,
                    'above_silver_num' => 1
                ));
            } else {
                $this->db->where('uid', $parent_id)->set('above_silver_num', 'above_silver_num+1', FALSE)->update('users_sale_rank_info');
                if ($saleRankInfo['above_silver_num'] >= 2) {
                    $this->userTitleUP($parent_id,1);
                }
            }
        }
        
        //检查自身等级
        $this->checkSelfLevel($uid);
    }

    /*获取超过3个月置为公司号的所有语言*/
    public function getAllLanOver3(){
        $return = array();
        foreach(config_item('supportLanguage') as $v){
            $lanFileKey = array_search("ucenter_base_lang.php",$this->lang->is_loaded);
            if($lanFileKey!==false){
                unset($this->lang->is_loaded[$lanFileKey]);
            }
            $this->lang->load('ucenter_base',$v);
            $return[$v] = array(
                'over3MonthNotyfyTitle'=>lang('over3MonthNotyfyTitle'),
                'over3MonthNotyfyContent'=>lang('over3MonthNotyfyContent'),
                'dear'=>lang('dear_'),
                'email_end'=>lang('email_end'),
            );
        }
        return $return;
    }

    public function checkSelfLevel($uid){
    	$this->load->model('tb_users_child_group_info');
    	$this->tb_users_child_group_info->find_by_group_id($uid);
        /*检查该用户各条腿下面的推荐人数统计，来给该用户升级到相应的职称*/
        $checkTitleId = 5;
        do {
            $checkTitleId--;
            if($checkTitleId==0){
                $resSaleRankInfo = $this->db->select('above_silver_num')->from('users_sale_rank_info')->where('uid', $uid)->get()->row_object();
                if($resSaleRankInfo){
                    $saleRankInfo = $resSaleRankInfo->above_silver_num;
                }else{
                    $saleRankInfo = 0;
                }
                if($saleRankInfo >= 3){
                    $this->userTitleUP($uid, $checkTitleId + 1);
                }
                $isEnd = TRUE;
                continue;
            }
//             $titleWhere = $this->getTitleNumWhereByTitleId($checkTitleId, 'a.');
//             $num = $this->db->query("select count(*) group_num from users_child_group_info a where a.uid=$uid and ($titleWhere)")->row_object()->group_num;
            $num = $this->tb_users_child_group_info->get_branch_user_total_num_before_level($uid, $checkTitleId);
            if ($num >= 3) {
                $this->userTitleUP($uid, $checkTitleId + 1);
                $isEnd = TRUE;
                continue;
            }
        } while (!isset($isEnd));
    }

    /**
     * @desc
     * @param type $titleIdNew 职称id(1资深店主，2销售经理，3销售主任，4销售总监，5全球销售副总裁)
     */
    public function userTitleUP($upId,$titleIdNew) {

        $this->load->model('tb_commission_logs');
        $this->load->model('tb_users_store_sale_info_monthly');

        $upUserInfo = current($this->getInfo($upId));
        if ($upUserInfo['sale_rank'] < $titleIdNew && $upUserInfo['user_rank'] != 4) {
            //更新用户职称
        	$this->db->where('id', $upId)->update('users', array('sale_rank' => $titleIdNew, 'sale_rank_up_time' => date('Y-m-d H:i:s')));
            //职称改变，加积分
            $this->load->model("tb_users_credit_queue_sale_rank");
            $queue_data = array(
                'uid'=>$upId,
                'before_sale_rank'=>$upUserInfo['sale_rank'],
                'after_sale_rank'=>$titleIdNew,
                'created_time'=>date("Y-m-d H:i:s")
            );
            $this->tb_users_credit_queue_sale_rank->add_queue($queue_data);
        	//触发职称改变的存储过程
            $old_sale_rank = $upUserInfo["sale_rank"];
            
            //$proc_sql = "call user_rank_change_week_comm('$upId','$old_sale_rank','$titleIdNew',0);";$this->db->query($proc_sql);
            $this->load->model('tb_users_store_sale_info_monthly');
            $this->tb_users_store_sale_info_monthly->user_rank_change_week_comm($upId, $old_sale_rank, $titleIdNew, 0);

            /*职称更新后，影响其所有上级组里的职称统计*/
            $fieldOfTitleNum = $this->getFieldOfTitleNumByTitleId($titleIdNew);
            if(!$fieldOfTitleNum){
                return FALSE;
            }
            $fieldOfTitleNumReduce = $this->getFieldOfTitleNumByTitleId($upUserInfo['sale_rank']);
            $arrParentIds = explode(',', $upUserInfo['parent_ids']);
            $group_ids = array_merge($arrParentIds, array($upId));
            
            $this->load->model('tb_users_child_group_info');
            foreach ($group_ids as $gid) {
            	$this->tb_users_child_group_info->update_user_level_num($gid, $fieldOfTitleNum, '+', 1);
            	if ($fieldOfTitleNumReduce) {
	            	$this->tb_users_child_group_info->update_user_level_num($gid, $fieldOfTitleNumReduce, '-', 1);
            	}
            }
            $groups = array();
            foreach ($group_ids as $gid) {
            	$groups[] = $this->tb_users_child_group_info->find_by_group_id($gid);
            }
            foreach ($groups as $gobj) {
            	if (!isset($gobj['uid'])) continue;
            	$num = $this->tb_users_child_group_info->get_branch_user_total_num_before_level($gobj['uid'], $titleIdNew);
            	if ($num >= 3) {
            		// 升级更高一级
            		$this->userTitleUP($gobj['uid'], $titleIdNew+1);
            	}
            }
            
            /* $this->db->where_in('group_id', $group_ids)->set($fieldOfTitleNum, $fieldOfTitleNum . '+1', FALSE);
            if ($fieldOfTitleNumReduce) {
                $this->db->set($fieldOfTitleNumReduce, $fieldOfTitleNumReduce.'-1', FALSE);
            }
            $this->db->update('users_child_group_info');
            $titleWhere = $this->getTitleNumWhereByTitleId($titleIdNew,'a.');
            $affectUids = $this->db->select('distinct(uid) uid')->from('users_child_group_info')->where_in('group_id', $group_ids)->get()->result_array();
            foreach ($affectUids as $affectUidItem) {
                $affectUid = $affectUidItem['uid'];
                $num = $this->db->query("select count(*) group_num from users_child_group_info a where a.uid=$affectUid and ($titleWhere)")->row_object()->group_num;
                if ($num >= 3) {
                    $this->userTitleUP($affectUid, $titleIdNew+1);
                }
            } */
        }
    }

    public function getFieldOfTitleNumByTitleId($titleId) {
        switch ($titleId) {
            case 1:
                $fieldOfTitleNum = 'mso_num';
                break;
            case 2:
                $fieldOfTitleNum = 'sm_num';
                break;
            case 3:
                $fieldOfTitleNum = 'sd_num';
                break;
            case 4:
                $fieldOfTitleNum = 'vp_num';
                break;
            default:
                $fieldOfTitleNum = '';
                break;
        }
        return $fieldOfTitleNum;
    }

    /**
     * @author: derrick 方法废弃
     * @date: 2017年6月28日
     * @param: @param unknown $titleId
     * @param: @param string $tb
     * @param: @return string
     * @reurn: string
     */
    public function getTitleNumWhereByTitleId($titleId,$tb=''){
        switch ($titleId) {
            case 1:
                $return = $tb.'mso_num>0 or '.$tb.'sm_num>0 or '.$tb.'sd_num>0 or '.$tb.'vp_num>0';
                break;
            case 2:
                $return = $tb.'sm_num>0 or '.$tb.'sd_num>0 or '.$tb.'vp_num>0';
                break;
            case 3:
                $return = $tb.'sd_num>0 or '.$tb.'vp_num>0';
                break;
            case 4:
                $return = $tb.'vp_num>0';
                break;
            default:
                $return = '';
                break;
        }
        return $return;
    }

    /*用户提交申请更改月费等级*/
    public function changeMonthFeeLevel($uid,$newMonthFeeLevel){
        $this->db->replace('month_fee_level_change', array(
            'uid' => $uid,
            'new_month_fee_level' => $newMonthFeeLevel,
            'create_time' => date('Y-m-d H:i:s')
        ));
    }

    public function removeMonthFeeLevelChange($uid){
        $this->db->query('delete from month_fee_level_change where uid='.$uid);
    }

    public function getChangedeMonthFeeLevel($uid){
        $newMonthFeeLevel = '';
        $res = $this->db->select('new_month_fee_level')->from('month_fee_level_change')->where('uid',$uid)->get()->row_array();
        if($res){
            $newMonthFeeLevel = $res['new_month_fee_level'];
        }
        return $newMonthFeeLevel;
    }

    public function addUserLevelChangeLog($uid,$old_level,$new_level,$level_type){
        $this->db->insert('users_level_change_log',array(
            'uid'=>$uid,
            'old_level'=>$old_level,
            'new_level'=>$new_level,
            'level_type'=>$level_type
        ));
    }

    public function changeMonthFeeLevelDone($uid,$changedMonthFeeLevel,$monthFeeLevel,$userRank){
        $this->db->update('users', array('month_fee_rank' => $changedMonthFeeLevel), array('id' => $uid));

        $this->db->update('users_coupon_monthfee',array('coupon_level'=>$changedMonthFeeLevel),array('uid'=>$uid,'status'=>0));
        $resCouponMonthly = $this->db->from('users_coupon_monthfee')->where('uid',$uid)->where('status',1)->get()->row_array();
        if($resCouponMonthly){
            $this->db->update('users_coupon_monthfee',array('coupon_level'=>$changedMonthFeeLevel),array('uid'=>$uid));
            $join_fee_and_month_fee = $this->getJoinFeeAndMonthFee();
            $couponFeeBefore = $join_fee_and_month_fee[$resCouponMonthly['coupon_level']]['month_fee'];
            $couponFeeNow = $join_fee_and_month_fee[$changedMonthFeeLevel]['month_fee'];
            $amount = $couponFeeBefore-$couponFeeNow;
            $this->db->where('id', $uid)->set('month_fee_pool','month_fee_pool-'.$amount,FALSE)->update('users');
        }

        $this->addUserLevelChangeLog($uid,$monthFeeLevel,$changedMonthFeeLevel,LEVEL_TYPE_MONTHLY_FEE);
        if($userRank<$changedMonthFeeLevel){
//            $this->db->update('users', array('user_rank' => $changedMonthFeeLevel), array('id' => $uid));
            $this->load->model("tb_users");
            $this->tb_users->modify_user_rank(array('id' => $uid),array('user_rank' => $changedMonthFeeLevel));
            $this->addUserLevelChangeLog($uid,$userRank,$changedMonthFeeLevel,LEVEL_TYPE_STORE);
        }
        $this->removeMonthFeeLevelChange($uid);
    }

    public function checkMonthFeeGap($uid){

        $this->load->model('m_coupons');
        $fee_num = $this->get_month_fee_num($uid);
        if($fee_num>0){

            $userInfo = current($this->getInfo($uid));

            $monthlyFeeCouponNum = $leftMonthlyFeeCouponNum = $this->m_coupons->getMonthlyFeeCouponNum($uid);//用户月费券数量
            if($monthlyFeeCouponNum){

                /*如果是韩国会员有限制逻辑*/
                if($userInfo['country_id']!=3 || !$this->m_coupons->checkMonthFeeCouponLimit($uid)){
                    if($fee_num<=$monthlyFeeCouponNum){
                        $leftMonthlyFeeCouponNum = $monthlyFeeCouponNum-$fee_num;
                        $fee_num = 0;
                    }else{
                        $fee_num = $fee_num-$leftMonthlyFeeCouponNum;
                        $leftMonthlyFeeCouponNum = 0;
                    }
                }
            }

            $join_fee_and_month_fee = $this->getJoinFeeAndMonthFee();
            $monthFeeLevel = $userInfo['month_fee_rank']==4?$userInfo['user_rank']:$userInfo['month_fee_rank'];

            if($fee_num>0){
                if($userInfo['first_monthly_fee_level']){
                    $monthFee = $join_fee_and_month_fee[$userInfo['first_monthly_fee_level']]['month_fee'] + $join_fee_and_month_fee[$monthFeeLevel]['month_fee'] * ($fee_num-1);
                }else{
                    $monthFee = $join_fee_and_month_fee[$monthFeeLevel]['month_fee'] * $fee_num;
                }
            }else{
                $monthFee = 0;
            }

            if ($userInfo['month_fee_pool'] >= $monthFee) {
                /* 从月费池扣月费 */
                $this->db->where('id', $uid)->set('month_fee_pool','month_fee_pool-'.$monthFee,FALSE)->update('users');

                /* 记录月费扣取日志 */
                $this->db->replace('users_month_fee_log', array(
                    'uid' => $uid,
                    'year_and_month' => date('Ym'),
                    'amount' => $monthFee
                ));

                /* 去除第一次月费等级纪录 */
                if($userInfo['first_monthly_fee_level']){
                    $this->db->where('id', $uid)->update('users', array('first_monthly_fee_level' => 0));
                }

                /** 月费变动记录 */
                $this->load->model('m_commission');
                $date_time = date('Y-m-d H:i:s');
                $coupon_num_change = $leftMonthlyFeeCouponNum-$monthlyFeeCouponNum;
                $this->m_commission->monthFeeChangeLog($uid,$userInfo['month_fee_pool'],$userInfo['month_fee_pool'] - $monthFee,-1 * $monthFee,$date_time,4,$monthlyFeeCouponNum,$leftMonthlyFeeCouponNum,$coupon_num_change);
                $this->m_coupons->reduceMonthlyFeeCoupon($uid,abs($coupon_num_change));

                /*判断扣取的月费是否是使用了月费抵用券的，如果是则不要发佣金。*/
                $month_fee_num = isset($fee_num) ? $fee_num : 1;
                if($this->checkMemberMonthlyFeeIsCoupon($uid)){
                    $month_fee_num = $month_fee_num-1;
                }
                $this->load->model('m_forced_matrix');
                $this->m_forced_matrix->memberFromFreeToPaid($uid, $month_fee_num,$userInfo['first_monthly_fee_level']);

                $this->db->query("delete from users_month_fee_fail_info where uid=$uid");
                $this->db->query("delete from sync_charge_month_fee where uid=$uid");
                $is = $this->db->from('users')->where('id', $uid)->where('status', 2)->get()->result_array();
                $this->db->where('id', $uid)->update('users', array('status' => 1));
                $this->db->where('id',$uid)->update('users', array('store_qualified'=>1));

                if(!empty($is)) {
                    //扣取用户月费成功，用户从休眠变成正常状态，记录日志
                    $this->db->insert('users_status_log', array(
                        'uid' => $uid,
                        'front_status' => 2,
                        'back_status' => 1,
                        'type' => 1,
                        'create_time' => time()
                    ));
                }
            }
        }
    }


    public function checkMemberMonthlyFeeIsCoupon($uid){
        if($this->db->from('users_coupon_monthfee')->where('uid',$uid)->where('status',1)->get()->row_array()){
            $this->db->where('uid', $uid)->update('users_coupon_monthfee', array('status' => 2,'monthly_fee_charge_time'=>date('Y-m-d H:i:s')));
            $return = true;
        }else{
            $return = false;
        }
        return $return;
    }

    /**
     * 处理月费扣取失败的人。
     * @author Terry
     */
    public function processMonthFeeFailMem(){
        /*筛选出当天扣取月费失败的会员id,及其扣取月费失败的时间。*/
        $todayFailMems = $this->db->select('uid,create_time')->from('users_month_fee_fail_info')->where('create_time >=',date('Y-m-d'))->get()->result_array();

        /*筛选出扣取月费失败后6天仍未付费的会员id,及其扣取月费失败的时间。*/
        $todayTimestamp = strtotime(date('Y-m-d'));
        $lastWeekDate = date('Y-m-d',$todayTimestamp-3600*24*7);
        $lastWeekDateOneDayAfter = date('Y-m-d',$todayTimestamp-3600*24*6);
        $sixDayFailMems = $this->db->select('uid,create_time')->from('users_month_fee_fail_info')->where('create_time >=',$lastWeekDate)->where('create_time <',$lastWeekDateOneDayAfter)->get()->result_array();

        /*筛选出7天以上，90天以内未支付月费的会员,并将该会员置为月费未支付*/
        $res90dayMems = $this->db->select('uid,create_time,last_mail_date')->from('users_month_fee_fail_info')->where('create_time <',$lastWeekDate)->where('create_time >=',date('Y-m-d',strtotime('-90 days')))->get()->result_array();
        $nintydayMems = array();
        $ninty_last_mail_date = array();
        foreach($res90dayMems as $v){
            $nintydayMems[] = $v['uid'];
            $ninty_last_mail_date[$v['uid']] = $v['last_mail_date'];
        }
        if ($nintydayMems) {
            //用户从正常变成休眠状态，记录日志
            foreach ($nintydayMems as $uid) {
                $is = $this->db->from('users')->where('id', $uid)->where('status', 1)->get()->result_array();
                if(!empty($is)) {
                    $this->db->insert('users_status_log', array(
                        'uid' => $uid,
                        'front_status' => 1,
                        'back_status' => 2,
                        'type' => 1,
                        'create_time' => time()
                    ));
                }
            }
            //如果状态变更成功，记录日志
            $this->db->where_in('id', $nintydayMems)->where('status', 1)->update('users', array('status' => 2, 'store_qualified' => 0));

        }
        /*筛选出90天以上未支付月费的会员，把会员的账号回收为公司号*/
        // $resOver90dayMems = $this->db->select('uid')->from('users_month_fee_fail_info')->where('create_time <',date('Y-m-d',strtotime('-90 days')))->get()->result_array();
        // $arrOver90dayMemsIds = array();
        // foreach($resOver90dayMems as $resOver90dayMem){
        //     $arrOver90dayMemsIds[] = $resOver90dayMem['uid'];
        // }
        // if ($arrOver90dayMemsIds) {
        //     $this->changeAccountToCompany($arrOver90dayMemsIds);
        //     $this->db->where_in('uid', $arrOver90dayMemsIds)->delete('users_month_fee_fail_info');
        // }

//        /*----------------------给统计出的各类会员发相应的邮件------------------*/
        $monthFeeMailLanArr = $this->getMonthFeeMailLan();
        $goFeeUrl = 'https://mall.tps138.com/ucenter/commission?msg=addMonthFeeNote#month_fee';

        /*给当天扣取月费失败的会员发送提醒邮件*/
        $startDate = date("Y-m-d");
        $endDate = date("Y-m-d",strtotime("+1 month"));
        foreach($todayFailMems as $v){
            $userInfo = current($this->getInfo($v['uid']));
            if($userInfo['country_id']==1){
                $lang = 'zh';
            }elseif($userInfo['country_id']==4){
                $lang = 'hk';
            }else{
                $lang = 'english';
            }
            $mailLangArr = $monthFeeMailLanArr[$lang];
            $mailTitle = $mailLangArr['month_fee_fail_notice'];
            $data['email'] = $userInfo['email'] ;
            $data['content'] = sprintf($mailLangArr['month_fee_fail_content'], $startDate,$endDate,$mailLangArr['7day'],$goFeeUrl);
            $data['dear'] = $mailLangArr['dear'];
            $data['email_end'] = $mailLangArr['email_end'];
            $content = $this->load->view('ucenter/public_email',$data,TRUE);
            send_mail($userInfo['email'], $mailTitle, $content);
            $this->db->where('uid', $v['uid'])->update('users_month_fee_fail_info', array('last_mail_date' => date('Y-m-d')));
        }

        /*扣取月费失败后6天仍未付费的会员发送提醒邮件*/
        $startDate = date('Y-m-d',strtotime(date("Y-m-d"))-3600*24*7);
        $endDate = date("Y-m-d",strtotime("$startDate +1 month"));
        foreach($sixDayFailMems as $v){
            $userInfo = current($this->getInfo($v['uid']));
            if($userInfo['country_id']==1){
                $lang = 'zh';
            }elseif($userInfo['country_id']==4){
                $lang = 'hk';
            }else{
                $lang = 'english';
            }
            $mailLangArr = $monthFeeMailLanArr[$lang];
            $mailTitle = $mailLangArr['month_fee_fail_notice'];
            $data['email'] = $userInfo['email'] ;
            $data['content'] = sprintf($mailLangArr['month_fee_fail_content'], $startDate,$endDate,$mailLangArr['24hours'],$goFeeUrl);
            $data['dear'] = $mailLangArr['dear'];
            $data['email_end'] = $mailLangArr['email_end'];
            $content = $this->load->view('ucenter/public_email',$data,TRUE);
            send_mail($userInfo['email'], $mailTitle, $content);
            $this->db->where('uid', $v['uid'])->update('users_month_fee_fail_info', array('last_mail_date' => date('Y-m-d')));
        }

        /*给7天以上90天内为支付月费的会员发送提醒邮件，每周发送一次（第8天单独发送一次）。*/
        $timePeriod = 7*24*3600;
        foreach($res90dayMems as $v){
            $curDayTimestamp = strtotime(date('Y-m-d'));
            $startDate = date('Y-m-d',strtotime($v['create_time']));
            if ( ($curDayTimestamp - strtotime($v['last_mail_date']) >= $timePeriod) || $curDayTimestamp-strtotime($startDate)==3600*24*8 ) {

                $endDate = date("Y-m-d", strtotime("$startDate +1 month"));
                $userInfo = current($this->getInfo($v['uid']));
                if ($userInfo['country_id'] == 1) {
                    $lang = 'zh';
                } elseif ($userInfo['country_id'] == 4) {
                    $lang = 'hk';
                } else {
                    $lang = 'english';
                }
                $mailLangArr = $monthFeeMailLanArr[$lang];
                $mailTitle = $mailLangArr['month_fee_fail_notice'];
                $data['email'] = $userInfo['email'] ;
                $data['content'] = sprintf($mailLangArr['month_fee_fail_content_90'], $startDate, $endDate, $goFeeUrl);
                $data['dear'] = $mailLangArr['dear'];
                $data['email_end'] = $mailLangArr['email_end'];
                $content = $this->load->view('ucenter/public_email',$data,TRUE);
                send_mail($userInfo['email'], $mailTitle, $content);
                $this->db->where('uid', $v['uid'])->update('users_month_fee_fail_info', array('last_mail_date' => date('Y-m-d')));
            }
        }
        /*---------------------/给统计出的各类会员发相应的邮件-------------------*/

        $this->m_log->createCronLog('处理扣月费失败的用户、发送提醒邮件。[执行完成]');
    }

    public function getMonthFeeMailLan(){
        $return = array();
        foreach(config_item('supportLanguage') as $v){
            $lanFileKey = array_search("ucenter_base_lang.php",$this->lang->is_loaded);
            if($lanFileKey!==false){
                unset($this->lang->is_loaded[$lanFileKey]);
            }
            $this->lang->load('ucenter_base',$v);
            $return[$v] = array(
                'month_fee_fail_notice'=>lang('month_fee_fail_notice'),
                'month_fee_fail_content'=>lang('month_fee_fail_content'),
                'month_fee_fail_content_90'=>lang('month_fee_fail_content_90'),
                '24hours'=>lang('24hours'),
                '7day'=>lang('7day'),
                'dear'=>lang('dear_'),
                'email_end'=>lang('email_end'),
                'receipt_title_'=>lang('receipt_title_'),
                'receipt_content_'=>lang('receipt_content_'),
                'deliver_title_'=>lang('deliver_title_'),
                'deliver_content_'=>lang('deliver_content_'),
                'april_email_content'=>lang('april_email_content'),
                'april_email_title'=>lang('april_email_title'),
                'tickets_email_title'=>lang('tickets_email_title'),
                'tickets_email_content'=>lang('tickets_email_content'),
                'email_captcha_content'=>lang('email_captcha_content'),
                'phone_captcha_content'=>lang('phone_captcha_content'),
                'email_captcha_title'=>lang('email_captcha_title'),
            );
        }
        return $return;
    }

    /**
     * 把用户账户置为公司号
     */
    public function changeAccountToCompany($uid){
        if(is_array($uid)){
            $this->db->where_in('id',$uid);
        }else{
            $this->db->where('id', $uid);
        }
        $this->db->update('users', array('status' => 4));
    }

    /**
     * 获取用户升级的加盟费和月费
     * @author Terry Lu
     */
    public function getJoinFeeAndMonthFee(){

        /*临时code*/
        $userInfo = unserialize(filter_input(INPUT_COOKIE, 'userInfo'));
        if(in_array($userInfo['uid'],array(1380152272,1380153232,1380154222,1380153215,1380165507,1380165587,1380152281,1380152279))){
            $old_join_fee_and_month_fee = config_item('old_join_fee_and_month_fee');
        }

        $join_fee_and_month_fee = config_item('join_fee_and_month_fee');
        foreach($join_fee_and_month_fee as $k=>$v){

            $join_fee_and_month_fee[$k] = array(
                'join_fee' => isset($old_join_fee_and_month_fee)?$old_join_fee_and_month_fee[$k]['join_fee']:$v['join_fee'],
                'month_fee' => config_item('month_half_price')?$v['month_fee']/2:$v['month_fee'],
            );
        }
        return $join_fee_and_month_fee;
    }

    /**
     * 更新用戶店鋪信息
     * @param type $uid
     * @param type $store_url
     * @return int
     */
    public function updateUserStoreInfo($uid, $store_url) {

        if (!$this->checkUserExist($uid)) {
            return 1008;
        }
        if(!$store_url){
            return 101;
        }
        $this->db->where('id', $uid)->update('users', array('store_url' => $store_url));
        return 0;
    }

    /**
     * 重置用户职称数据
     * @author Terry Lu
     */
    public function resetSaleRankInfo(){
        try {
            $this->db->trans_begin();

            $this->db->query('update users a set a.sale_rank=0');//清空用户表职称
            $this->db->query('delete from users_sale_rank_info');//清空用户直推的银级会员统计表
            $this->db->query('delete from users_child_group_info');//清空用户的腿数据统计表
            $this->createAllUserChildGroupData(config_item('mem_root_id'));//初始化用户的腿数据统计表

            /*重新生成职称数据*/
            $feeUsersRes = $this->db->select('id')->from('users')->where('user_rank <',4)->order_by("id")->get()->result_array();
            foreach($feeUsersRes as $feeUserRes){
                $this->freeToFeeAffectParent($feeUserRes['id']);
            }

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
            } else {
                throw new Exception('error!');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo $e->getMessage();
        }
    }

    public function createAllUserChildGroupData($uid) {
        $childMems = $this->getChildMembers($uid);
        foreach ($childMems as $childMem) {
            $cid = $childMem['id'];
            $this->createUserChildGroupData($cid, $uid);
            $this->createAllUserChildGroupData($cid);
        }
    }

    /**
     * 根据类型获取用户的等级变更记录
     * @date: 2015-6-24
     * @author: sky yuan
     * @parameter: $userId,$levelType 1：月费等级；2：店铺等级
     * @return:array
     */
    public function getUserLevelChangeLog($userId,$levelType=1) {
        $res = $this->db->from('users_level_change_log')
            ->where('uid', $userId)
            ->where('level_type',$levelType)
            ->order_by('create_time','asc')
            ->get()
            ->result_array();

        foreach($res as $k=>$v) {
            $res[$k]['old_level_desc']=config_item('monthly_fee_ranks')[$v['old_level']];
            $res[$k]['new_level_desc']=config_item('monthly_fee_ranks')[$v['new_level']];
        }

        return $res;
    }

    /***用户月费状态***/
    public function account_status($user_id,$status){
        $account_status='';
        if($status==0){
            $account_status=lang('not_enable');
        }
        if($status==1){
            $fee_num=$this->get_month_fee_num($user_id);
            $attr=array('fee_num'=>$fee_num);
            if($fee_num>0) {
                if($fee_num==1){
                    $account_status = lang('enabled').lang_attr('fee_num_msg_one', $attr);
                }else{
                    $account_status = lang('enabled').lang_attr('fee_num_msg', $attr);
                }
            }else{
                $account_status = lang('enabled');
            }
        }
        if($status==2){
            $fee_num=$this->get_month_fee_num($user_id);
            $attr=array('fee_num'=>$fee_num);
            if($fee_num==1){
                $account_status = lang('sleep').lang_attr('fee_num_msg_one', $attr);
            }else{
                $account_status = lang('sleep').lang_attr('fee_num_msg', $attr);
            }
        }
        if($status==3){
            $account_status=lang('account_disable');
        }
        if($status==4){
            $account_status=lang('company_keep');
        }

        //添加一个会员状态 退会中: 6 ；User:Ckf
        if($status==6){
            $account_status=lang('signouting');
        }

        return $account_status;
    }


    public function get_month_fee_num($user_id){
        $user_id = (int)$user_id;
        $fee_num=0;
        $res=$this->db->query("select * from users_month_fee_fail_info where uid=$user_id")->result_array();
        if(!empty($res)){
            $fail_year=date('Y',strtotime($res[0]['create_time']));
            $fail_month=date('m',strtotime($res[0]['create_time']));
            $fail_day=date('d',strtotime($res[0]['create_time']));

            $now_year=date('Y',time());
            $now_month=date('m',time());
            $now_day=date('d',time());
            if($res[0]['create_time']=='0000-00-00 00:00:00'){
                $fee_num=1;
            }else{
                $year_to_month=($now_year-$fail_year)*12;
                if($now_day>=$fail_day){
                    $fee_num=($now_month-$fail_month+1)+$year_to_month;
                }else{
                    $fee_num=($now_month-$fail_month)+$year_to_month;
                }
            }
        }
        return $fee_num;
    }

    /** 会员是否是1.1号之前升级该$level等级 */
    public function is_upgrade_before_1_1($uid,$level){

        $upgrade_log = $this->db->select('create_time')->where('new_level',$level)->where('uid',$uid)->where('level_type',2)->get('users_level_change_log')->row_array();
        if($upgrade_log && strtotime($upgrade_log['create_time']) < strtotime('2016-01-01 00:00:00')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /** 查看会员第一次升级的时间是否在1.1号之前,如果是，就是1.1之前的加盟费*/
    public function is_first_upgrade_time_1_1($uid){
        $upgrade_log = $this->db->select('create_time')->where('new_level < old_level')->where('uid',$uid)->where('level_type',2)->get('users_level_change_log')->row_array();
        if($upgrade_log && strtotime($upgrade_log['create_time']) < strtotime('2016-01-01 00:00:00')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /** 会员1.1前升级当前店铺的加盟费 */
    public function upgrade_before_1_1_amount($uid,$level){
        return FALSE;
        /*$is_true = $this->is_upgrade_before_1_1($uid,$level);
        if($is_true){
            $old_join_fee = config_item('old_join_fee_and_month_fee')[$level];
            return $old_join_fee['join_fee'];
        }else{
            return FALSE;
        }*/
    }

    /** 勾选自动转月费池 */
    public function update_is_auto($uid){
        $this->db->query("update users set is_auto = 1 where id = {$uid}");
        return $this->db->affected_rows();
    }


    /** 获取会员的名字，邮箱
     * @param $user_id
     * @return mixed
     * @author andy
     */
    public function get_user_name_email_by_id($user_id){
        $this->db->select('id,name,email')->where('id',$user_id)->from('users');
        return $this->db->get()->row_array();
    }

    //获取国家id
    public function get_user_country_id($uid){
        $this->db->from('users');
        $this->db->select('country_id');
        $this->db->where('id',$uid);
        return $this->db->get()->row_array();
    }

    /**
     * @description 更新用户体现密码
     * @author brady.wang
     * @time 2016/12/06
     * @param $uid
     * @param $token
     * @param $pwd
     * @return mixed
     */
    public function updateTakeCashPwd($uid,$token,$pwd){
        $this->db->where('id', $uid)->update('users', array('pwd_take_out_cash' => $this->encyTakeCashPwd($pwd, $token),'update_time'=>time()));
        return $affected_rows = $this->db->affected_rows();

    }

    public function verify_phone_code($code, $session)
    {

        $now = time();
        $output = [
            'error' => false,
            'msg' => ''
        ];
        try {
            if ($now > $session['expire_time']) {
                throw new Exception(lang("phone_code_expire"));
            }
            if ($code != $session['code']) {
                throw new Exception(lang("phone_code_error"));
            }
            $output['error'] = false;
            $this->session->unset_userdata($session['email_or_phone']);
            return json_encode($output);
        } catch (Exception $e) {
            $output['error'] = true;
            $output['msg'] = $e->getMessage();
            return json_encode($output);
        }

    }

    /**
     * @author brady.wang
     * @param $mobile 手机号
     * @desc 验证手机号是否被人验证了
     */
    public function check_mobile_exists($mobile)
    {
        $res = $this->db->from('users')->select('id,mobile')->where(array('mobile' => $mobile,"is_verified_mobile"=>1))->limit(1)->get()->row_array();
        if(!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }
    
    /**
     * 获取用户用户职称店铺等信息
     * @param unknown $uid
     */
    public function get_user_title_info($uid)
    {
        $sql = "SELECT id,sale_rank,user_rank FROM users WHERE id=".$uid;
        $query = $this->db->query($sql);
        $return_value = $query->row_array();
        if(!empty($return_value))
        {
            return $return_value;
        }
        else 
        {
            return null;
        }        
    }
    
    
    /**
     * 获取用户用户职称店铺等信息
     * @param 用户id $uid
     */
    public function get_user_info_all($uid)
    {
        $user_data = array();
        $sql = "SELECT id,sale_rank,user_rank,sale_rank_up_time FROM users WHERE id=".$uid;
        $query = $this->db->query($sql);
        $return_value = $query->row_array();
        if(!empty($return_value))
        {
            $user_sql = "SELECT create_time FROM users_level_change_log WHERE level_type=2 and uid=".$uid." order by create_time desc limit 1";
            $user_query = $this->db->query($user_sql);
            $user_value = $user_query->row_array();
            $user_data = array
            (
                'uid' => $uid,
                'sale_rank' => $return_value['sale_rank'],
                'user_rank' => $return_value['user_rank'],
                'sale_rank_up_time'=> $return_value['sale_rank_up_time'],
                'user_rank_up_time'=> $user_value['create_time']
            );
            if(!empty($user_value))
            {
                $user_data['user_rank_up_time'] = $user_value['create_time'];
            }            
            return $user_data;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * 根据用户id和用户职称获取想用职称用户的最小id
     * @param 用户id $uid
     * @param 用户职称  $sale_rank
     */
    public function get_user_title_info_min_id($sale_rank)
    {
        $sql = "SELECT id, sale_rank FROM  users WHERE id >= ((SELECT MAX(id) FROM users where sale_rank=".$sale_rank.")-(SELECT MIN(id) FROM users where sale_rank=".$sale_rank." ) ) * RAND() + (SELECT MIN(id) FROM users where sale_rank=".$sale_rank." )  LIMIT 1";
        
        $query = $this->db->query($sql);
        $return_value = $query->row_array();
       
        if(!empty($return_value))
        {
            return $return_value;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * 每月团队组织分红奖 - 获取最小id
     * @param 用户id $uid
     * @param 用户职称  $sale_rank
     */
    public function get_month_user_title_info_min_id($sale_rank)
    {
        $sql = "SELECT uid FROM  month_group_share_list WHERE uid >= ((SELECT MAX(uid) FROM month_group_share_list where sale_rank_weight=".$sale_rank.")-(SELECT MIN(uid) FROM month_group_share_list where sale_rank_weight=".$sale_rank." ) ) * RAND() + (SELECT MIN(uid) FROM month_group_share_list where sale_rank_weight=".$sale_rank." )  LIMIT 1";
    
        $query = $this->db->query($sql);
        $return_value = $query->row_array();
         
        if(!empty($return_value))
        {
            return $return_value;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * 补发每周团队销售分红奖
     * @param 用户id $uid
     * @param 返利佣金(美分) $money
     * @param 返利类型 $item_type
     * @param 订单号 $order_id
     * @param 发奖时间 $c_time
     */
    public function ressiue_user_amount($uid,$money,$item_type,$order_id,$c_time)
    {
        $sql = "call grant_comm_single_new(".$uid.",". (int)$money . ",".$item_type.",'','".$c_time."')";
        $this->db->query($sql);
    }

    //解绑支付宝，置空
    // andy
    public function alipay_unbinding($uid){
        $this->db->set('alipay_account','');
        $this->db->set('alipay_name','');
        $this->db->where('id',$uid);
        $this->db->update('users');

        return $this->db->affected_rows();
    }

    //获取会员支付宝信息
    // andy
    public function get_alipay_info($uid){
        $this->db->from('users');
        $this->db->select('alipay_account,alipay_name');
        $this->db->where('id',$uid);

        return $this->db->get()->row_array();
    }

    //解绑手机号
    public function mobile_unbinding($uid){
        $this->db->set('mobile','');
        $this->db->set('is_verified_mobile',0);
        $this->db->where('id',$uid);
        $this->db->update('users');

        return $this->db->affected_rows();
    }

    //获取手机信息
    public function get_mobile_info($uid){
        $this->db->from('users');
        $this->db->select('mobile,is_verified_mobile');
        $this->db->where('id',$uid);

        return $this->db->get()->row_array();
    }

}
