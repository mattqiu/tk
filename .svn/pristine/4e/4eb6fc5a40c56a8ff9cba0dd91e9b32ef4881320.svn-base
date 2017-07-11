<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class coupons_manage extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->_viewData['title'] = lang('coupons_manage');

        $this->load->model('m_admin_helper');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_admin_helper->get_coupons_manage_list($searchData);

        $this->load->library('pagination');
        $url = 'admin/coupons_manage';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->get_coupons_manage_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    /***提交***/
    public function submit(){
        $data=$this->input->post();

        //用户名验证非空
        if(trim($data['user_id'])==''){
            echo json_encode(array('success'=>false,'msg'=>lang('id_not_null')));
            exit();
        }

        //代品券验证非空
        if(trim($data['voucher'])==''){
            echo json_encode(array('success'=>false,'msg'=>lang('voucher_not_null')));
            exit();
        }

        //备注验证非空
        if(trim($data['remark'])==''){
            echo json_encode(array('success'=>false,'msg'=>lang('remark_not_null')));
            exit();
        }

        //验证用户名格式
        if(!preg_match("/^[0-9]*$/",$data['user_id'])){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }

        //验证代品券格式
        if(!preg_match("/^[0-9]*$/",$data['voucher']) || $data['voucher']==0){
            echo json_encode(array('success'=>false,'msg'=>lang('please_enter_correct_voucher')));
            exit();
        }

        //验证是否有该用户
        $this->load->model('m_forced_matrix');
        if(!$this->m_forced_matrix->userInfo($data['user_id'])){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }

        $this->change_voucher($data['user_id'],$data['operation'],$data['voucher'],$data['remark']);
    }


    /***
     * @param $user_id      用户id
     * @param $operation    加或者减
     * @param $voucher      代品券金额
     */
    public function change_voucher($user_id,$operation,$voucher,$reason){
        $this->load->model('m_suite_exchange_coupon');

		$operate = $operation==1?'+':'-';
		$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_coupons_manage','user_suite_exchange_coupon',$user_id,
			'','','',$operate.$voucher);

        //加代品券
        if($operation=='1'){
            $this->m_suite_exchange_coupon->add_voucher($user_id,$voucher);

            //插入log记录
            $this->db->insert('voucher_manage_logs',array(
                'user_id'=>$user_id,
                'voucher_value'=>$voucher,
                'admin_id'=>$this->_adminInfo['id'],
                'reason'=>$reason,
                'create_time'=>date('Y-m-d h:i:s',time())
            ));

            echo json_encode(array('success'=>true,'msg'=>lang('submit_success')));
            exit();
        }

        //减代品券
        if($operation=='2'){
            $this->m_suite_exchange_coupon->useCoupon($user_id,$voucher);

            //插入log记录
            $this->db->insert('voucher_manage_logs',array(
                'user_id'=>$user_id,
                'voucher_value'=>-$voucher,
                'admin_id'=>$this->_adminInfo['id'],
                'reason'=>$reason,
                'create_time'=>date('Y-m-d h:i:s',time())
            ));

            echo json_encode(array('success'=>true,'msg'=>lang('submit_success')));
            exit();
        }

    }
}


