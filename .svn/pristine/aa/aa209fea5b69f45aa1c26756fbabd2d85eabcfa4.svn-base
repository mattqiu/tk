<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class join_plan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
    }

    public function index($id = NULL) {
        $this->_viewData['title'] = lang('action_charge_month');
        parent::index('admin/');
    }

    public function do_action(){

        if($this->input->is_ajax_request()){
            $data = $this->input->post();
            $user_id=$data['user_id'];
            $confirm_user_id=$data['confirm_user_id'];
            if(!preg_match("/^[0-9]*$/",$user_id) || trim($user_id)==''){
                echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
                exit();
            }

            if(trim($user_id)!=trim($confirm_user_id)){
                echo json_encode(array('success'=>false,'msg'=>lang('id_not_identical')));
                exit();
            }

            $this->load->model('m_user');
            $userInfo=$this->m_user->getUserByIdOrEmail($user_id);
            if(!$userInfo){
                echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
                exit();
            }

			if($userInfo['status'] != 2){
				echo json_encode(array('success'=>false,'msg'=>'用户不是休眠状态.'));
				exit();
			}

			$this->load->model('tb_users_store_sale_info_monthly');
			$amount = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($user_id,date('Ym'));
			if($amount < 5000 && $user_id !=1380129614){
				echo json_encode(array('success'=>false,'msg'=>'当月的店铺销售额不足$50.'));
				exit();
			}

            $this->db->trans_start();
			/**
			 * 活动抵扣月费记录
			 */
			$this->db->update('users',array('status'=>1,'store_qualified'=>1),array('id'=> $user_id));
			$this->load->model('m_commission');
			$date_time = date('Y-m-d H:i:s');
			$this->m_commission->monthFeeChangeLog($user_id,$userInfo['month_fee_pool'],$userInfo['month_fee_pool'],0.00,$date_time,8);

			$this->db->where('uid',$user_id)->delete('users_april_plan');
			$this->db->query("delete from users_month_fee_fail_info where uid=".$user_id); //移除扣月费失败记录


			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_action_join_plan','users',$user_id,
				'','','');
            $this->db->trans_complete();
            echo json_encode(array('success'=>TRUE,'msg'=>lang('update_success')));
            exit();
        }
    }
    /**
     * 手动删除会员的活动记录
     * Ckf
     * 20161117
     */
    public function delPlan(){
        if($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $user_id = $data['user_id'];
            $confirm_user_id = $data['confirm_user_id'];
            if (!preg_match("/^[0-9]*$/", $user_id) || trim($user_id) == '') {
                echo json_encode(array('success' => false, 'msg' => lang('user_not_exits')));
                exit();
            }

            if (trim($user_id) != trim($confirm_user_id)) {
                echo json_encode(array('success' => false, 'msg' => lang('id_not_identical')));
                exit();
            }

            $this->load->model('m_user');
            $userInfo = $this->m_user->getUserByIdOrEmail($user_id);
            if (!$userInfo) {
                echo json_encode(array('success' => false, 'msg' => lang('user_not_exits')));
                exit();
            }
            //查询该会员是否有参加活动
            $this->load->model('tb_users_april_plan');
            $res = $this->tb_users_april_plan->get_user_plan($user_id);
            if(!$res){
                echo json_encode(array('success' => false, 'msg' => lang('not_join_action_charge_month')));
                exit();
            }

            $this->db->trans_start();
            //删除记录
            $this->db->where('uid',$user_id)->delete('users_april_plan');
            //记录操作日志
            $this->m_log->adminActionLog($this->_adminInfo['id'],'del_user_plan','users_april_plan',$user_id,'','','');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('success'=>false,'msg'=>lang('delete_failure')));
                exit();
            }
            echo json_encode(array('success'=>TRUE,'msg'=>lang('delete_success')));
            exit();
        }
    }

}