<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Sharing_point_to_cash extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('sharing_point_to_cash',$this->_adminInfo);
    }
    public function index($id = NULL) {

        $this->_viewData['title'] = lang('sharing_point_to_money');
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
				 $result['profit_sharing_point'] =  $user['profit_sharing_point'];
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

			$this->load->model('m_profit_sharing');
			$res = $this->m_profit_sharing->sharingPointToMoney($user,$money,true);
			$success = TRUE;
			if(!$res['error_code']){
				$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_sharing_point_to_cash','users',$uid,
					'profit_sharing_point',$user['profit_sharing_point'],$user['profit_sharing_point']-$money,-1*$money);
				$success=TRUE;
				$msg = '√ ' .lang('shift_success');
			}else{
				$success=FALSE;
				$error = config_item('error_code');
				$msg = '× ' .lang($error[$res['error_code']]);
			}

			echo json_encode(array('success' => $success, 'msg' => $msg,'data'=>$res));
        }
    }
}