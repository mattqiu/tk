<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class tickets_customer_role extends MY_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_tickets_customer_role');
    }

    public function index(){
        $this->_viewData['title'] = lang('tickets_customer_role');

        if(check_right('tickets_customer_role_right')){
            $list = $this->tb_admin_tickets_customer_role->get_all_customer();
        }else{
            $list = NULL;
        }

        $this->_viewData['list'] = $list;
        parent::index('admin/');
    }

    public function customer_role_action(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post();
            if(!empty($data)){
                if(!empty($data['role1'])){//改
                    $logs = array(
                        'admin_id'=>$this->_adminInfo['id'],
                        'cus_id'=>$data['aid'],
                        'old_value'=>$data['role1'],
                        'new_value'=>$data['role1']==1?2:1,
                    );
                    $data['role1'] = $data['role1']==1?2:1;
                    $rows = $this->tb_admin_tickets_customer_role->update_customer($data['id'],$data['role1']);
                    $this->tb_admin_tickets_customer_role->add_logs($logs);
                    if($rows){
                        die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
                    }
                }else{//加
                    $logs = array(
                        'admin_id'=>$this->_adminInfo['id'],
                        'cus_id'=>$data['aid'],
                        'old_value'=>1,
                        'new_value'=>2,
                    );
                    $arr = array(
                        'admin_id'=>$data['aid'],
                        'role'=>2
                    );
                    $rows = $this->tb_admin_tickets_customer_role->add_customer($arr);
                    $this->tb_admin_tickets_customer_role->add_logs($logs);
                    if($rows){
                        die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
                    }
                }
            }
        }
        die(json_encode(array('success'=>1,'msg'=>lang('customer_role_invalid_action'))));
    }
}