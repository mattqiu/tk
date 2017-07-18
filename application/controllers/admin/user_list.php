<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_admin_helper');

        /*设置后台默认页跳转。*/
        // if($this->_adminInfo['role']==4){
        //     redirect(base_url('admin/cash_withdrawal_list'));
        // }
        // if($this->_adminInfo['role']==3){
        //     redirect(base_url('admin/trade'));
        // }

         if(in_array($this->_adminInfo['role'],array(6,7))){
             redirect(base_url('admin/export_orders'));
         }
    }
    
    

    public function index() {
        $this->load->model('tb_cash_account_log_x');
        // 设定程序永不超时
        
        $this->_viewData['title'] = lang('user_list');
        $this->_viewData['confirm_email_title'] = lang('admin_exchange_user_email_title'); //邮箱修改
        $this->_viewData['confirm_mobile_title'] = lang('admin_exchange_user_mobile_title'); //手机号修改
        $this->_viewData['confirm_info_content'] = lang('admin_exchange_user_info_content'); //提示信息     
        $this->_viewData['admin_order_remark'] = lang('admin_order_remark'); //备注        
        $this->_viewData['admin_remark_input_not_null'] = lang('admin_remark_input_not_null'); //备注信息不能为空
        $this->_viewData['process'] = lang('process'); //查看       
        
        $this->_viewData['account_disable_z'] = lang('account_disable_z'); //正常冻结
        $this->_viewData['account_reenable_z'] = lang('account_reenable_z'); //正常解冻
        $this->_viewData['account_disable_m'] = lang('account_disable_m'); //欠月费且冻结
        $this->_viewData['resert_user_status'] = lang('resert_user_status'); //恢复用户状态
     
        $this->_viewData['account_disable_z'] = lang('account_disable_z'); //正常冻结
               
        $this->_viewData['label_yes'] = lang('label_yes'); 
        $this->_viewData['label_no'] = lang('label_no'); 
        
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['idEmail'] = trim(isset($searchData['idEmail'])?$searchData['idEmail']:'');
        $searchData['user_rank'] = isset($searchData['user_rank'])?$searchData['user_rank']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['parent_id'] = isset($searchData['parent_id'])?$searchData['parent_id']:'';
        $searchData['ewallet_name'] = isset($searchData['ewallet_name'])?$searchData['ewallet_name']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['mobile'] = isset($searchData['mobile'])?$searchData['mobile']:'';
                
        //用户冻结解封权限  
        if(in_array($this->_adminInfo['email'],config_item('users_option_frozen_power')))
        {
            $this->_viewData['user_rooter'] = 1;
        }
        else
        {
            $this->_viewData['user_rooter'] = 0;
        }
        //-->end 用户冻结解封权限  
        $is_query = 0;
        $tmp_where_arr = $searchData;
        unset($tmp_where_arr["page"]);
        unset($tmp_where_arr["ewallet_name"]);
        
        if (!$_GET) {
            $this->_viewData['err_code'] = 1003;
        }else{
            foreach($tmp_where_arr as $tv){
                if($tv){
                    $is_query =1;break;
                }
            }
            $this->_viewData['err_code'] = $is_query==1 ? 1001 : 1003;
        }
        
        
        
        $this->_viewData['list'] = $is_query==1 ?  $this->m_user->getUserListNew($searchData) :"";
       // echo $this->db->last_query();
       // echo "<pre>";print_r($this->_viewData['list']);exit;
        $this->load->library('pagination');
        $url = 'admin/user_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $is_query==1 ? $this->m_user->getUserListRows($searchData) :0;
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    /**
     * @author brady.wang
     * @desc 用户支付宝后台解绑
     */
    public function alipay_account_unbind()
    {
        header("content-type:text/html;charset=utf-8");
        $admin_info = $this->_adminInfo;
        if ($admin_info['id'] == 160) {
            $uid = trim($this->input->get("id",true));
            if (empty($uid)) {
                echo "缺少参数用户id";
            } else {
                $this->load->model("tb_users");
                $param = [
                    'select'=>"id,alipay_account,alipay_name",
                    'where'=>[
                        'id'=>$uid
                    ],
                    'limit'=>1

                ];
                $res = $this->tb_users->get($param,false,true,true);
                if (empty($res)) {
                    echo "用户不存在";
                } else {
                    $this->load->model("m_user");
                    $res = $this->m_user->alipay_unbinding($uid);
                    echo $this->db->last_query();
                    echo "<br>";
                    if ($res >0) {
                        echo "解绑成功";
                    } else {
                        echo "解绑失败";
                    }
                }
            }
        } else {
            echo "hacker";
        }
    }
    
    public function get_user_info_detail(){
        $id = $this->input->post('id', true);


        $output = ['success'=>'false','msg'=>'','data'=>[]];
        try {
            $id =  trim($id);
            if (empty($id)) {
                throw new Exception("params error");
            }
            $userInfo = current($this->m_user->getInfo($id));
            if ($userInfo == false) {
                throw new Exception("user not exists");
            }

        } catch(Exception $e) {
            $output['msg'] = $e->getMessage();
            echo json_encode($output);
            exit;
        }

        $userInfo['user_rank_text'] = lang(config_item('user_ranks')[$userInfo['user_rank']]);
		$userInfo['mobile'] = $userInfo['is_verified_mobile'] == '1' ? '<span class="get_mobile">'.$userInfo['mobile'].'</span></span>'."<strong style='color: #008000'>". lang('is_binding')."</strong>" :$userInfo['mobile'];
		$userInfo['email'] = $userInfo['is_verified_email'] == '1' ? $userInfo['email']."<strong style='color: #008000'>". lang('is_binding')."</strong>" :$userInfo['email'];
        $country = "<select name='country'><option value=''>------</option>";
        foreach (config_item('countrys_and_areas') as $key => $value) {
            $selected = $userInfo['country_id'] == $key ? 'selected':'';
            $country .= "<option value=$key $selected>".lang($value)."</option>";
        }
        $country .= '</selectd>';
        $userInfo['country_text'] = $country;
        $userInfo['sale_rank_text'] = lang('title_level_'.$userInfo['sale_rank']);
        $userInfo['amount_text'] = '$ '.$userInfo['amount'];
        $userInfo['profit_sharing_point_text'] = $userInfo['profit_sharing_point'].' '.lang('point');
        $userInfo['month_fee_pool_text'] = '$ '.$userInfo['month_fee_pool'];
        $card  = $this->m_admin_helper->getCardOne($id);

        if($card['check_status'] == 0){
            $status = lang('no_validate');
            if($card['check_info']){
                $status = lang('validate_failure').$card['check_info'];
            }
            $class = 'text-error';
        }else if($card['check_status'] == 1){
            $status = lang('verify_info');
            $class = 'text-error';
        }else{
            $status = lang('validate_success');
            $class = 'text-success';
        }
        $log = $this->m_admin_helper->getTransferLog($id);
        if($log && time() < strtotime($log['create_time']." +6 month" )){
            $relieve = "<button class='btn relive_ban'>".lang('relive_ban')."</button>";
            $userInfo['id_card_scan_text'] = "<strong class=$class>$status</strong>".$relieve;
        }else{
            $userInfo['id_card_scan_text'] = "<strong class=$class>$status</strong>";
        }

        //手机号，paypal，alipay 解除绑定

        if($userInfo['mobile'])
        {
            $mobile_unbundling = "<button class='btn mobile_unbundling'>".lang('unbundling')."</button>";
            $userInfo['mobile']         = $userInfo['mobile'].$mobile_unbundling;
        }

        if($userInfo['alipay_account'])
        {
            $alipay_unbundling = "<button class='btn alipay_unbundling'>".lang('unbundling')."</button>";
            $userInfo['alipay_account'] = '<span class="get_alipay">'.$userInfo['alipay_account'].'</span>'.$alipay_unbundling;
        }

        $this->load->model('m_paypal_log');
        $res = $this->m_paypal_log->get_paypal($id);

        if($res)
        {
            $paypal_unbundling = "<button class='btn paypal_unbundling'>".lang('unbundling')."</button>";
            $userInfo['paypal_account']  ='<span class="get_paypal">'.$res['paypal_email'].'</span>'.$paypal_unbundling;
        }

        $userInfo['status_text'] = lang(config_item('store_status')[$userInfo['status']]);
        $userInfo['month_fee_date_text'] = $userInfo['month_fee_date']?$userInfo['month_fee_date'].lang('day_th'):'';

        //生命週期
        $lifecycle = date('Y-m-d H:i:s', $userInfo['create_time']);
        $userInfo['join_matrix_time'] = $lifecycle;
        
        //2015-6-24 modify by sky yuan 增加用户月费等级和店铺等级变化日志
        $monthLevel = $this->m_user->getUserLevelChangeLog($id );
        $shopLevel = $this->m_user->getUserLevelChangeLog($id ,2);
        $count_monthLevel=count($monthLevel);
        $count_shopLevel=count($shopLevel);
        
        $upgrade_text=$upgrade_text_store='';
        foreach($monthLevel as $key=>$item) {
        	$upgrade_text.='<div class="box"><span style="color:#f98635; font-weight:bold;">';
        	$upgrade_text.=$item['create_time'].'</span>';
        	 
        	if($item['old_level'] > $item['new_level'])
        		$upgrade_text.=lang('upgrade');
        	else
        		$upgrade_text.=lang('downgrade');
        	 
        	$upgrade_text.=lang('month_upgrade_from').'<span style="color:#f98635;">'.lang($item['old_level_desc']).'</span>';
        	 
        	if($item['old_level'] > $item['new_level'])
        		$upgrade_text.=lang('upgrade_to');
        	else
        		$upgrade_text.=lang('downgrade_to');
        	 
        	$upgrade_text.='<span style="color:#f98635;">'.lang($item['new_level_desc']).'</span></div>';
        	 
        	if(($key+1) < $count_monthLevel) {
        		$upgrade_text.='<span class="arrow arrow-right"></span>';
        	}
        }
        $userInfo['month_level_change_log'] =$upgrade_text;
        
        foreach($shopLevel as $key=>$item) {
        	$upgrade_text_store.='<div class="box"><span style="color:#f98635; font-weight:bold;">';
        	$upgrade_text_store.=$item['create_time'].'</span>';
        	 
        	if($item['old_level'] > $item['new_level'] || ($item['old_level']==4 && $item['new_level'] == 5) )
        		$upgrade_text_store.=lang('upgrade');
        	else
        		$upgrade_text_store.=lang('downgrade');
        	 
        	$upgrade_text_store.=lang('shop_upgrade_from').'<span style="color:#f98635;">'.lang($item['old_level_desc']).'</span>';
        	 
        	if($item['old_level'] > $item['new_level'] || ($item['old_level']==4 && $item['new_level'] == 5))
        		$upgrade_text_store.=lang('upgrade_to');
        	else
        		$upgrade_text_store.=lang('downgrade_to');
        	 
        	$upgrade_text_store.='<span style="color:#f98635;">'.lang($item['new_level_desc']).'</span></div>';
        
        	if(($key+1) < $count_shopLevel) {
        		$upgrade_text_store.='<span class="arrow arrow-right"></span>';
        	}
        }
        $userInfo['store_level_change_log'] =$upgrade_text_store;

        /** 電子錢包 刪除功能  by john 2015-6-29 */
        if($userInfo['ewallet_name']){

            $userInfo['ewallet_name'] = $userInfo['ewallet_name'].'<a href="##" id="clear_ewallet" style="margin-left:30px;">'.lang('clear').'</a>';
        }

        /** 代品劵数量,总额 by Andy 2016-03-23*/
        $this->load->model('m_suite_exchange_coupon');
        $Coupons_res = $this->m_suite_exchange_coupon->getALL($userInfo['id']);
        $couponsNum = 0;
        foreach($Coupons_res as $cNum){
            $couponsNum+=intval($cNum);
        }
        $allCoupons = $this->m_suite_exchange_coupon->getAmount($userInfo['id']);
        $userInfo['coupon_total_amount']=$couponsNum.lang('zhang').'--'.$allCoupons;

        echo json_encode(array('success'=>TRUE,'msg'=>'','data'=>$userInfo));
    }
    
    public function enable_user_account(){
        $id = $this->input->post('id');
        $this->load->model('m_user');
        $noVeri = TRUE;
        $success = $this->m_user->enableAccount(array('id'=>$id),$noVeri);
		if($success == 0){
			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_enable_user_account','users',$id,
				'status','0','1');
		}
        echo json_encode(array('success'=>TRUE));
    }
    
    public function disable_user_account(){
        $id = $this->input->post('id');        
        $this->load->model('m_user');      
		$success = $this->m_user->disableAccount($id);
		if($success == 0){
			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_disable_user_account','users',$id,
				'status','1','3');			
		}
        echo json_encode(array('success'=>TRUE));
    }
    
    /**
     * 新增备注
     */
    public function option_users_account()
    {
        $id = $this->input->post('id');
        $remark = $this->input->post('remark');
        $optype = $this->input->post('optype');
        $frost_days = $this->input->post('frost_days');
        $frost_days = intval($frost_days);
        $frost_forever = intval($this->input->post('frost_forever'));
        $data = $this->input->post();

        $this->load->model('m_user');
        $this->load->model('tb_users_frozen_remark');
        $this->load->model('tb_user_frost_list');
        $this->load->model('service_message_model');

        $this->db->trans_begin();
        try {
            if(1==$optype)
            {
                if ($frost_forever == 1 && $frost_days <=0) {
                    $remark = $remark." #".lang("frost_user_time").":".lang("frost_forever");
                } else {
                    $remark = $remark." #".lang("frost_user_time").":".$frost_days.lang("day");
                }
                if ($frost_days >0) {
                    //添加进入冻结日志表里面
                    $this->tb_user_frost_list->add_queue($id,$frost_days);
                }
                $success = $this->m_user->disableAccount($id);
                if($success == 0){
                    $this->m_log->adminActionLog($this->_adminInfo['id'],'admin_disable_user_account','users',$id,
                        'status','1','3');
                }
            }
            else if(2==$optype)
            {
                $this->m_user->reenableAccount($id);
                $this->m_log->adminActionLog($this->_adminInfo['id'],'admin_reenable_user_account','users',$id,
                    'status','3','1');

                //从冻结列表删除 如果有
                $this->tb_user_frost_list->del_queue($id);
            }
            $this->tb_users_frozen_remark->add_user_frozen_remark($id,$this->_adminInfo['email'],$remark,$optype);
            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
            } else {
                throw new Exception(lang("info_error"));
                $this->db->trans_rollback();
            }
            echo json_encode(array("success"=>true,"msg"=>lang("success")));

        } catch(Exception $e){
            echo json_encode(array('success'=>false,'msg'=>$e->getMessage()));
        }


    }
    
    /**
     * 恢复用户状态到正常
     */
    public function resert_user_status()
    {
        $uid = $this->input->post('id');
        $remark = $this->input->post('remark');
        $this->load->model('tb_users');
        $this->load->model('tb_user_id_card_info');        
        $this->load->model('tb_users_frozen_remark');
        $this->tb_users->modify_user_status($uid,1);
        $this->tb_user_id_card_info->addNewuserIdCardField($uid);
        $this->tb_users_frozen_remark->add_user_frozen_remark($uid,$this->_adminInfo['email'],$remark,3);
        echo json_encode(array('success'=>TRUE));
    }
    
    /**
     * 获取用户备注的所有信息
     */
    public function get_users_remark_all($id)
    {                   
        $this->_viewData['job_number']=lang('job_number'); //编号
        $this->_viewData['admin_order_remark']=lang('admin_order_remark'); //备注
        $this->_viewData['admin_order_info_create_time']=lang('admin_remark_option_time'); //时间
        $this->_viewData['operator_email']=lang('admin_remark_option_name'); //操作人
        $this->_viewData['action']=lang('action'); //操作
        $this->_viewData['resert_user_status'] = lang('resert_user_status'); //恢复用户状态
        $this->_viewData['account_disable']=lang('account_disable'); //冻结
        $this->_viewData['account_reenable']=lang('account_reenable'); //解冻      
        $this->load->model('tb_users_frozen_remark');        
        $query = $this->tb_users_frozen_remark->get_users_frozen_remark($id);
        $this->_viewData['remark_date'] = $query;
        $this->load->view('admin/users_frozen_remarks', $this->_viewData);
    }
    
    
    
    public function reenable_user_account(){
        $id = $this->input->post('id');        
        $this->load->model('m_user');
        $this->m_user->reenableAccount($id);

		$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_reenable_user_account','users',$id,
			'status','3','1');		
        echo json_encode(array('success'=>TRUE));
    }
    
    public function upgrade_store_level_by_month_fee(){
        $id = $this->input->post('id');
        $this->load->model('m_order');
        $this->m_order->uptradeStoreLevelManually($id);
        echo json_encode(array('success'=>TRUE));
    }
    
    /**
     * 修改会员信息
     * @author Terry
     */
    public function modify_mem_info(){

        $dataPost = $this->input->post();

        if(!empty($dataPost['check'])){

            if(!check_right('modify_member_email_or_phone') && !in_array($this->_adminInfo['role'],array(0,2))){
                echo json_encode(array('success'=>FALSE,'msg'=>'No Permission!'));exit;
            }

        }else{

            if(!in_array($this->_adminInfo['role'],array(0,1,2))){
                echo json_encode(array('success'=>FALSE,'msg'=>'No Permission!'));exit;
            }

        }

        $this->load->model('m_user');
        if($dataPost['fieldName'] !== 'name'){
            $value = trimall($dataPost['modifyVal']);
        }else{
            $value = $dataPost['modifyVal'];
        }
		$success = TRUE;
		$before = $this->db->select($dataPost['fieldName'])->where('id',$dataPost['uid'])->get('users')->row_array();
        $error_code = $this->m_user->modifyUserInfo($dataPost['uid'],$dataPost['fieldName'],$value);
        if($error_code){
            $success = FALSE;
            $msg = lang(config_item('error_code')[$error_code]);
        }
        if($success && in_array($dataPost['fieldName'],array('name','email','mobile','country_id','address'))){
            switch($dataPost['fieldName']){
                case 'name':
                    $fieldItemId = 6;
                    break;
                case 'email':
                    $fieldItemId = 1;
                    break;
                case 'mobile':
                    $fieldItemId = 7;
                    break;
                case 'country_id':
                    $fieldItemId = 8;
                    break;
                case 'address':
                    $fieldItemId = 9;
                    break;
            }
            $this->m_user->addInfoToWohaoSyncQueue($dataPost['uid'],array($fieldItemId));
        }
		if($success === TRUE){
			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_edit_user_info','users',$dataPost['uid'],
				$dataPost['fieldName'],$before[$dataPost['fieldName']],$dataPost['modifyVal']);
		}
        echo json_encode(array('success'=>isset($success)?$success:TRUE,'msg'=>isset($msg)?$msg:''));
    }

    //解除封禁
    //andy
    public function relive_ban(){
        if($this->input->is_ajax_request()){
            $id = trim($this->input->post('id'));
            $update_time =  date("Y-m-d H:i:s", strtotime("-1 year"));
            $log = $this->m_admin_helper->getTransferLog($id);
            if($log){
                $this->db->set('create_time',$update_time)->where('id',$log['id'])->update('user_transfer_refund_logs');
                $num = $this->db->affected_rows();
                if($num){
                    die(json_encode(array('success'=>1)));
                }
            }
        }
        die(json_encode(array('success'=>0)));
    }

    //支付宝,paypal,mobile 解绑
    public function do_unbinding(){

        if($this->input->is_ajax_request()){

            $id     = trim($this->input->post('id'));
            $type   = trim($this->input->post('type'));

            if($id)
            {
                switch($type){
                    case 1: {//支付宝解绑

                        $this->load->model('m_user');

                        $this->db->trans_start();

                        $alipay_info = $this->m_user->get_alipay_info($id);
                        $this->m_user->alipay_unbinding($id);

                        $insert_log  = array(
                            'old_data' =>$id.'&&'.$alipay_info['alipay_account'].'&&'.$alipay_info['alipay_name'],
                            'new_data' =>'',
                            'admin_id' =>$this->_adminInfo['id'],
                            'type'     =>1,
                        );
                        $this->db->insert('unbinding_account_log',$insert_log);

                        $this->db->trans_complete();
                        if($this->db->trans_status()!==FALSE)
                        {
                            die(json_encode(array('success'=>1)));
                        }
                        break;

                    }

                    case 2:{//paypal解绑

                        $this->load->model('m_paypal_log');

                        $paypal_email   = trim($this->input->post('email'));

                        $this->db->trans_start();

                        $info = $this->m_paypal_log->get_paypal($id);
                        $this->m_paypal_log->del_payapl($id, $paypal_email);

                        $insert_log  = array(
                            'old_data' =>$id.'&&'.$info['paypal_email'].'&&'.$info['time'],
                            'new_data' =>'delete...',
                            'admin_id' =>$this->_adminInfo['id'],
                            'type'     =>2,
                        );
                        $this->db->insert('unbinding_account_log',$insert_log);

                        $this->db->trans_complete();

                        if($this->db->trans_status()!==FALSE)
                        {
                            die(json_encode(array('success'=>1)));
                        }
                        break;

                    }

                    case 3:{//手机号解绑

                        $this->load->model('m_user');

                        $this->db->trans_start();

                        $info = $this->m_user->get_mobile_info($id);
                        $this->m_user-> mobile_unbinding($id);


                        $insert_log  = array(
                            'old_data' =>$id.'&&'.$info['mobile'].'&&'.$info['is_verified_mobile'],
                            'new_data' =>'',
                            'admin_id' =>$this->_adminInfo['id'],
                            'type'     =>3,
                        );
                        $this->db->insert('unbinding_account_log',$insert_log);

                        $this->db->trans_complete();

                        if($this->db->trans_status()!==FALSE)
                        {
                            die(json_encode(array('success'=>1)));
                        }
                        break;

                    }
                    default:break;
                }
            }
        }

        die(json_encode(array('success'=>0)));

    }

    public function unfrost()
    {
        $this->_viewData['title'] = lang('unfrost');
        $this->_viewData['curControlName'] = 'unfrost';

        parent::index('admin/', 'unfrost');
    }

    public function unfrost_submit()
    {
        $this->load->model("tb_users");
        $this->load->model("tb_logs_unfrost_user");
        $data = ['error'=>false,"msg"=>lang("unforst_success")];
        $admin_info = $this->_adminInfo;
        $account = $this->input->post("account");
        $account_confirm = $this->input->post("account_confirm");

        try {
            if (REDIS_STOP == 1) {
                throw new Exception("redis_off");
            }

            if (empty($account)) {
                throw new Exception("please_input_unfrost_account");
            }

            if (empty($account_confirm)) {
                throw new Exception("please_input_unfrost_account_again");
            }

            if ($account !== $account_confirm) {
                throw new Exception("input_unfrost_not_same");
            }

            $user_info = $this->tb_users->getUserByIdOrEmail($account);//获取用户信息
            if (empty($user_info)) {
                throw new Exception("USER_NOT_EXIST");
            }
            $uid = $user_info['id'];

            $key = "mall:login:submit:error_counts:".$uid;
            $count = $this->tb_users->redis_get($key);
            if (!$count || $count < 5) {
                throw new Exception("unfrost_needless");
            }
            $this->tb_users->redis_del($key);
            $log = array('admin_id'=>$admin_info['id'],'account'=>substr($account,0,24),'create_time'=>date("Y-m-d H:i:s"));
            $this->tb_logs_unfrost_user->add($log);
            echo  json_encode($data);
        } catch(Exception $e) {
            $data['error'] = true;
            $data['msg'] = lang($e->getMessage());
            echo json_encode($data);
        }


    }

    /**
     * 统计会员团队人数
     * @author: derrick
     * @date: 2017年5月18日
     * @param: 
     * @reurn: return_type
     */
    public function group_stat() {
    	$this->_viewData['title'] = lang('group_stat');
    	$this->_viewData['curControlName'] = 'group_stat';
    	
    	$user_id = $this->input->post('user_id');
    	$start = $this->input->post('start');
    	$end = $this->input->post('end');
    	
    	$result = array(
    		'register_total' => 0,
    		'upgrade_total' => 0,
    		'upgrade_total_for_c' => 0,
    		'upgrade_total_for_s' => 0,
    		'upgrade_total_for_g' => 0,
    		'upgrade_total_for_d' => 0,
    		'upgrade_total_for_f' => 0
    	);
    	if (empty($start)) {
    		$start = date('Y-m-01', strtotime(date("Y-m-d")));
    	}
    	if (empty($end)) {
    		$end = date("Y-m-d");
    	}
    	$this->load->model('tb_users');
    	$parents = $this->tb_users->get_user_info($user_id, '*');
    	if ($parents) {
	    	$ids = array($user_id);
	    	$_start = date('Y-m-d H:i:s', $parents['create_time']);
	    	
	    	//统计时间段内的所有注册会员
	    	$start_mic = $start.' 00:00:00';
	    	$end_mic = $end.' 23:59:59';
	    	$start_mic_str = strtotime($start_mic);
	    	$end_mic_str = strtotime($end_mic);
	    	while (true) {
		    	$users = $this->tb_users->find_by_parent_and_create_time($ids, $_start, $end_mic, 'id, create_time');
		    	if (empty($users)) break;
		    	$ids = [];
		    	foreach ($users as $u) {
		    		if ($u['create_time'] >= $start_mic_str && $u['create_time'] <= $end_mic_str) {
			    		$result['register_total']++;
		    		}
			    	$ids[] = $u['id'];
		    	}
	    	}
	    	
	    	//统计时间段内所有付费会员
	    	$this->load->model('tb_users_level_change_log');
	    	$ids = array($user_id);
	    	$childrens = [];
	    	while (true) {
	    		$users = $this->tb_users->find_by_parent_and_create_time($ids, $_start, $end_mic, 'id');
	    		if (empty($users)) break;
	    		$ids = [];
	    		foreach ($users as $u) {
	    			$ids[] = $u['id'];
	    			$childrens[] = $u['id'];
	    		}
	    	}
	    	
	    	$changes = $this->tb_users_level_change_log->check_user_is_change_in_time($childrens, $start_mic, $end_mic, 2);
	    	$exists = [];
	    	foreach ($changes as $c) {
	    		if (in_array($c['uid'], $exists)) {
	    			continue;
	    		}
	    		$exists[] = $c['uid'];
	    		$result['upgrade_total']++;
	    		switch ($c['new_level']) {
	    			case LEVEL_DIAMOND:
	    				$result['upgrade_total_for_d']++;
	    				break;
	    			case LEVEL_GOLD:
	    				$result['upgrade_total_for_g']++;
	    				break;
	    			case LEVEL_SILVER:
	    				$result['upgrade_total_for_s']++;
	    				break;
	    			case LEVEL_COPPER:
	    				$result['upgrade_total_for_c']++;
	    				break;
	    			case LEVEL_FREE:
	    				$result['upgrade_total_for_f']++;
	    				break;
	    		}
	    	}
    	}
    	
    	$result['user_id'] = $user_id;
    	$result['start'] = $start;
    	$result['end'] = $end;
    	$this->_viewData['result'] = $result;
    	parent::index('admin/', 'group_stat');
    }

    /**
     * @author: derrick 修复职称
     * @date: 2017年6月29日
     * @param: 
     * @reurn: return_type
     */
	public function fix_user_rank() {
		$this->_viewData['title'] = lang('fix_user_rank');
		$this->_viewData['curControlName'] = 'fix_user_rank';
		
		parent::index('admin/', 'fix_user_rank');
	}
	
	/**
	 * @author: derrick 修复职称
	 * @date: 2017年6月29日
	 * @param: 
	 * @reurn: return_type
	 */
	public function do_fix_user_rank() {
		if($this->input->is_ajax_request()){
			$user_id = trim($this->input->get_post('user_id'));
			$type = $this->input->get_post('type');
			if (empty($user_id) || empty($type)) {
				echo json_encode(array('code' => 0, 'message' => lang('user_id_list_requied')));
				exit;
			}
                        $this->load->model('tb_users_child_group_info');
                        $group_user = $this->tb_users_child_group_info->find_by_group_id($user_id);
                        $list_user = $this->tb_users_child_group_info->find_by_user_id($user_id);
			$this->load->model('o_queen');
			if ($type == 1) {//修复职称等级
				$this->o_queen->en_unique_queue(o_queen::QUEEN_USER_RANK_TITLE, $user_id);
			} elseif($type == 2) {//修复职称变动时间
				$this->o_queen->en_unique_queue(o_queen::QUEEN_USER_SALE_RANK_UP_TIME, $user_id);
                        }else{//修复会员对应关系
                            $this->o_queen->en_unique_queue(o_queen::QUEEN_USER_LOGIC, $user_id);
                        }
			echo json_encode(array('code' => 1, 'message' => lang('fix_user_rank_later'),'group_user'=>$group_user,'list_user'=>$list_user));
			exit;
		}
	}

	/**
	 * @author: derrick 查看业绩
	 * @date: 2017年7月5日
	 * @param: 
	 * @reurn: return_type
	 */
	public function user_score_list() {
                $this->_viewData['err'] = 0;
		$this->_viewData['title'] = lang('view_user_score');
		$this->_viewData['curControlName'] = 'user_list';
                $searchData = $this->input->get()?$this->input->get():array();
                $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
                $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
                $searchData["year"] = isset($searchData['year'])?$searchData['year']:'';
                $searchData['month'] = isset($searchData['month'])?$searchData['month']:'';
                if(!empty($searchData["year"]) && empty($searchData['month'])){
                    $searchData["start_year_month"] = $searchData["year"]."01";
                    $searchData["end_year_month"] = $searchData["year"]."12";
                }
                if(empty($searchData["year"]) && !empty($searchData['month'])){
                    $searchData["year"] = date("Y",time());//选择了月份，年份为空的时候取最新
                }
                //组合年月
                $searchData['year_month'] = $searchData["year"] && $searchData['month'] ? date("Ym",strtotime($searchData["year"]."-".$searchData["month"])) : "";
                $this->_viewData['searchData'] = $searchData;
                if(empty($searchData['uid'])){
                    $this->_viewData['err']=1 ;
                }else{
                    $perPage = 12;//每页显示数量
                    $this->load->model(array('tb_users_store_sale_info_monthly', 'tb_users_store_sale_info'));
                    $list = $this->tb_users_store_sale_info_monthly->find_by_user_id($searchData, $perPage);

                    $total = $this->tb_users_store_sale_info->getUserSaleInfo($searchData['uid']);

                    $this->_viewData['list'] = $list;
                    $this->_viewData['total'] = $total;//业绩总额

                    $this->load->library('pagination');
                    $url = 'admin/user_list/user_score_list';
                    add_params_to_url($url, $searchData);
                    $config['base_url'] = base_url($url);
                    $config['per_page'] = $perPage;
                    $config['total_rows'] = $this->tb_users_store_sale_info_monthly->find_by_user_id_Rows($searchData);
                    $config['cur_page'] = $searchData['page'];
                    $this->pagination->initialize_ucenter($config);
                    $this->_viewData['pager'] = $this->pagination->create_links(true);
                }
		parent::index('admin/', 'view_user_score');
	}
}