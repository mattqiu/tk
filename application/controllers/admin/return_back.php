<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Return_back extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('return_back',$this->_adminInfo);
    }
    public function index($id = NULL) {

        $this->_viewData['title'] = lang('return_back');
        parent::index('admin/');
    }

    /** 抽回佣金返补 */
    public function do_return_back(){
//         if($this->input->is_ajax_request()){
//             $data = $this->input->post();
//             $this->load->model('m_admin_helper');
//			 $user_id=trim($data['user_id']);
//			 $confirm_user_id=trim($data['confirm_user_id']);
//			 $start=trim($data['start']);
//			 if(!preg_match("/^[0-9]*$/",$user_id) || $user_id==''){
//				 echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
//				 exit();
//			 }
//
//			 if($user_id!=$confirm_user_id){
//				 echo json_encode(array('success'=>false,'msg'=>lang('id_not_identical')));
//				 exit();
//			 }
//			 if($start == ''){
//				 echo json_encode(array('success'=>false,'msg'=>'please select time'));
//				 exit();
//			 }
//             $result = $this->m_admin_helper->do_return_back($user_id,$this->_adminInfo['id'],$start);
//			 die(json_encode($result));
//         }
    }


}