<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Cash_to_sharing_point extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('cash_to_sharing_point',$this->_adminInfo);
    }
    public function index($id = NULL) {

        $this->_viewData['title'] = lang('manually_sharing_point');
        parent::index('admin/');
    }

    /** 檢查用戶是否可以降級 */
    public function check(){
         if($this->input->is_ajax_request()){
             $id = $this->input->post('id');
             $this->load->model('m_user');
             $user = $this->m_user->getUserByIdOrEmail($id);

             if($user){
				 $result['name'] = $user['name'];
				 $result['amount'] = $user['amount'];
				 $result['profit_sharing_point'] =  $this->m_user->getTotalSharingPoint($id);
                 $result['store_level'] = lang(config_item('user_ranks')[$user['user_rank']]);
                 $result['monthly_fee_level'] = lang(config_item('monthly_fee_ranks')[$user['month_fee_rank']]);
                 die(json_encode(array('success'=>1,'result'=>$result)));
             }else{
                 die(json_encode(array('success'=>0,'msg'=>lang('no_exist'))));
             }
         }
    }

    /** 执行降级操作 */
    public function do_transfer(){
        if($this->input->is_ajax_request()){
			$money = trim($this->input->post('money'));
			$uid = trim($this->input->post('id'));
			$this->load->model('m_user');
			$user = $this->m_user->getUserByIdOrEmail($uid);
			if (!is_numeric($money) || $money <= 0 ||  get_decimal_places($money)>2) {
				$success = FALSE;
				$msg = '× ' .lang('positive_num_error');
			}elseif($money>$user['amount']){
				$success = FALSE;
				$msg = '× ' .lang('cur_commission_lack');
			}else{
				$this->load->model('m_profit_sharing');
				$data = $this->m_profit_sharing->manuallyAddSharingPoint($user,$money);
				$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_cash_to_sharing_point','users',$uid,
					'amount',$user['amount'],$user['amount']-$money,-1*$money);
				$success = TRUE;
				$msg = '√ ' .lang('shift_success');
			}

			echo json_encode(array('success' => $success, 'msg' => $msg,'data'=>isset($data)?$data:array()));
        }
    }
}