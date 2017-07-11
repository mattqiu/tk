<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Unassigned_tickets extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_tickets');
        $this->load->model('m_admin_user');
        $this->load->model('tb_admin_tickets_attach');
        $this->load->model('tb_admin_tickets_logs');
        $this->load->model('tb_admin_tickets_black_list');
        $this->load->model('tb_admin_tickets_record');
    }

    public function index(){
        $this->_viewData['title'] = lang('unassigned_tickets');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
        $searchData['language'] = isset($searchData['language'])?$searchData['language']:'';
        $searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
        $searchData['order_by'] = isset($searchData['order_by'])?$searchData['order_by']:'';
        $searchData['order_by_time'] = isset($searchData['order_by_time'])?$searchData['order_by_time']:'';
        $searchData['admin_id'] = 0;//未分配的订单
        $searchData['status']   = 0;//状态为新建的
        $list = $this->tb_admin_tickets->get_unassigned_tickets_list($searchData);
        $this->_viewData['list'] = $list;
        $url = 'admin/unassigned_tickets';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_tickets->get_tickets_count($searchData);
        $config['cur_page'] = $searchData['page'];;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $customers = $this->m_admin_user->get_customers();
        $this->_viewData['cus'] = $customers;
        $black = $this->tb_admin_tickets_black_list->get_all_black_uid();
        $black_arr = array();
        if(!empty($black)){ foreach($black as $item){  array_push( $black_arr,$item['uid']); }}
        $this->_viewData['black'] =$black_arr;

        //统计
        $count_arr =$this->tb_admin_tickets->calculate_tickets_count();
        $count_result = array();
        if(!empty($count_arr)){foreach($count_arr as $k=>$item){ $count_result[$item['admin_id']] = $item;}}
        $this->_viewData['count_result'] =$count_result;

        $today_count = $this->tb_admin_tickets->calculate_tickets_count_by_today();
        $today_result = array();
        if(!empty($today_count)){foreach($today_count as $k=>$item){ if($item['admin_id']!=''){ $today_result[$item['admin_id']] = $item;}}}
        $this->_viewData['today_result'] =$today_result;

        $auto_status = $this->db->from('config_site')->select('value')->where('name','auto_assign')->get()->row_array();
        $this->_viewData['auto_status'] = $auto_status;

        $o_url = 'admin/unassigned_tickets';
        unset($searchData['order_by']);
        unset($searchData['order_by_time']);
        add_params_to_url($o_url, $searchData);
        $this->_viewData['order_url'] = $o_url;//只做单一排序

        $this->load->model('tb_admin_tickets_customer_role');
        $role = $this->tb_admin_tickets_customer_role->get_customer_by_admin_id($this->_adminInfo['id']);
        if(!empty($role)){
            $this->_viewData['cus_role'] = $role['role'];
        }else{
            $this->_viewData['cus_role']=1;
        }
        $this->_viewData['pls_select_customer'] = lang("pls_select_customer"); //请选择客服
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    /** 查看其中一个未分配工单信息*/
    public function get_unassigned_tickets_info($id = NULL){
        if(is_numeric($id)){
            $row = $this->tb_admin_tickets->get_unassigned_tickets_info_by_id($id);
            if($row) {
                $this->_viewData['title'] = lang('tickets_info');
                if ($row['is_attach'] == 1) {
                    $is_reply = 0;
                    $attach = $this->tb_admin_tickets_attach->get_attach_by_tickets_id($row['id'], $is_reply);
                    $row['attach'] = $attach;
                }
                $this->_viewData['row'] = $row;
                $this->load->model('m_admin_user');
                $customers = $this->m_admin_user->get_customers();
                $this->_viewData['cus'] = $customers;
                $this->load->view('admin/unassigned_tickets_info', $this->_viewData);
            }
        }
    }

    public function do_transfer(){

        $this->load->model('tb_admin_tickets_customer_role');

        $role = $this->tb_admin_tickets_customer_role->get_customer_by_admin_id($this->_adminInfo['id']);

        if(!empty($role)){

            $role  = $role['role'];

        }else{

            $role  = 1;

        }

        if($role==1 && !check_right('tickets_assign_right') && !check_right('unallocated_tickets_assign_right')){
            return;
        }

        if($this->input->is_ajax_request()){
            $data = $this->input->post();//$data['c_id'] 为目标客服id
            $status_arr = array(
                'tickets_id'=>$data['id'],
                'old_data'  =>0,
                'new_data'  =>$data['c_id'],
                'data_type' =>3,
                'admin_id'  =>$this->_adminInfo['id'],
                'is_admin'  =>1,
            );
            $record = array(
                'admin_id'  =>$data['c_id'],
                'type'      =>1,
                'count'     =>1,
                'assign_time'=>date('Y-m-d'),
            );
            $this->db->trans_start();
            $this->tb_admin_tickets_logs->add_log($status_arr);//log
            $this->tb_admin_tickets->transfer_tickets_to_other(array('id'=>$data['id'],'all_id'=>''),$data['c_id']);
            $this->tb_admin_tickets_record->add_record($record);
            $this->db->trans_complete();
            if($this->db->trans_status()!=FALSE){
                die(json_encode(array('success'=>1,'msg'=>lang('transfer_tickets_success'))));
            }else{
                die(json_encode(array('success'=>0,'msg'=>lang('transfer_tickets_fail'))));
            }
        }else{
            die(json_encode(array('success'=>0,'msg'=>lang('transfer_tickets_fail'))));
        }
    }


//    /**标记订单到自己的名下*/
//    public function assign_tickets(){
//        if($this->input->is_ajax_request()){
//            $id       = $this->input->post('id');
//            $admin_id = $this->_adminInfo['id'];
//            if(is_numeric($id)){
//                $status_arr = array(
//                    'tickets_id'=>$id,
//                    'old_data'=>0,
//                    'new_data'=>1,
//                    'data_type'=>1,
//                    'admin_id'=>$admin_id,
//                    'is_admin'=>1,
//                );
//                $this->tb_admin_tickets_status->add_log($status_arr);//log
//                $res = $this->tb_admin_tickets->assign_tickets($id,$admin_id);
//                if($res){
//                    die(json_encode(array('success'=>1,'msg'=>lang('assign_success'))));
//                }else{
//                    die(json_encode(array('success'=>0,'msg'=>lang('assign_fail'))));
//                }
//            }
//        }
//    }

    public function batch_transfer(){

        $this->load->model('tb_admin_tickets_customer_role');

        $role = $this->tb_admin_tickets_customer_role->get_customer_by_admin_id($this->_adminInfo['id']);

        if(!empty($role)){

            $role  = $role['role'];

        }else{

            $role  = 1;

        }

        $param = $this->input->post();

        if($role==1 && !check_right('tickets_assign_right') && !check_right('unallocated_tickets_assign_right')){
            return;
        }

        if($param && isset($param['cus'])){
            $cus_id = $param['cus'];
            $this->db->trans_start();
            $this->tb_admin_tickets->batch_transfer($param['checkboxes'],$cus_id);
            $arr = array();
            foreach($param['checkboxes'] as $c){
                $a = array(
                    'tickets_id'=>$c,
                    'old_data'=>0,
                    'new_data'=>$cus_id,
                    'data_type'=>3,
                    'admin_id'=>$this->_adminInfo['id'],
                    'is_admin'=>1,
                );
                array_push($arr,$a);
            }
            $record = array(
                'admin_id'  =>$cus_id,
                'type'      =>1,
                'count'     =>count($param['checkboxes']),
                'assign_time'=>date('Y-m-d'),
            );
            $this->tb_admin_tickets_logs->batch_add_logs($arr);//log
            $this->tb_admin_tickets_record->add_record($record);
            $this->db->trans_complete();
            if($this->db->trans_status()!=FALSE){//相同地址
                header( "Location:".$_SERVER['HTTP_REFERER']);
            }else{
                header( "Location:".$_SERVER['HTTP_REFERER']);
            }
        }
    }

    //设置客服工作的状态
    public function change_cus_work_status(){
        $data = $this->input->post();
        if($this->input->is_ajax_request() && !empty($data)){
            $this->db->set('status',$data['w_status'])->where('id',$data['c_id'])->update('admin_users');
            $res = $this->db->affected_rows();
            if($res){
                if($data['w_status']==2){
                    die(json_encode(array('success'=>1,'msg'=>sprintf(lang('tickets_cus_leave'),$data['c_num']))));
                }else{
                    die(json_encode(array('success'=>1,'msg'=>sprintf(lang('tickets_cus_work'),$data['c_num']))));
                }
            }
        }
        die(json_encode(array('success'=>0,'msg'=>sprintf(lang('change_status_fail'),$data['c_num']))));
    }

    //设置自动手动分配,改变状态
    public function auto_assign_status(){
        $data = $this->input->post();
        if($this->input->is_ajax_request() && !empty($data)){
            $this->db->set('value',$data['val'])->where('name','auto_assign')->update('config_site');
            $res = $this->db->affected_rows();
            if($res){
                if($data['val']=='yes'){
                    die(json_encode(array('success'=>1,'msg'=>lang('tickets_auto_assign'))));
                }else{
                    die(json_encode(array('success'=>1,'msg'=>lang('tickets_hand_assign'))));
                }
            }
        }
        die(json_encode(array('success'=>1,'msg'=>lang('tickets_auto_assign_fail'))));
    }
    //执行自动分配
    public function auto_assign_tickets(){

        $this->load->model('tb_admin_tickets_customer_role');

        $role = $this->tb_admin_tickets_customer_role->get_customer_by_admin_id($this->_adminInfo['id']);

        if(!empty($role)){

            $role  = $role['role'];

        }else{

            $role  = 1;

        }

        if($role==1 && !check_right('tickets_assign_right') && !check_right('unallocated_tickets_assign_right')){
            return;
        }

        if($this->input->is_ajax_request()){
            if($this->tb_admin_tickets->auto_assign()){
                die(json_encode(array('success'=>1,'msg'=>lang('transfer_tickets_success'))));
            }
        }
        die(json_encode(array('success'=>1,'msg'=>lang('transfer_tickets_fail'))));
    }

    //全部中文工单
    public function auto_assign_tickets_zh(){

        if(!in_array($this->_adminInfo['id'],array(68,144))){
            $status = false;
        }else{
            $status =  $this->tb_admin_tickets->auto_assign_zh();
        }

        if($status){
            die(json_encode(array('success'=>1,'msg'=>'成功！')));
        }else{
            die(json_encode(array('success'=>1,'msg'=>'失败！')));
        }
    }

    //全部韩文工单
    public function auto_assign_tickets_kr(){

        if(!in_array($this->_adminInfo['id'],array(68,144))){
            $status = false;
        }else{
            $status =  $this->tb_admin_tickets->auto_assign_kr();
        }

        if($status){
            die(json_encode(array('success'=>1,'msg'=>'成功！')));
        }else{
            die(json_encode(array('success'=>1,'msg'=>'失败！')));
        }
    }
}