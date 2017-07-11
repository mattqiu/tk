<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class commission_2x5 extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_forced_matrix');
    }

//    public function index(){
//        $this->_viewData['title'] = lang('commission_2x5');
//        $msg=0;
//        $data=$this->input->post();
//        if($data!=false) {
//            $leader_id=$data['leader_id'];                  //收取佣金的ID
//            $user_id=$data['user_id'];                      //发放佣金的ID
//            $commissionOption=$data['commissionOption'];    //操作选项 1=>加 ,2=>减
//            $why=$data['why'];                              //原因选项
//            if(trim($leader_id!='')&& trim($user_id!='') && $why!=0){
//                if($commissionOption==1){
//                    $this->m_forced_matrix_admin->getCommission($user_id,$leader_id);
//                }
//                if($commissionOption==2){
//                    $this->m_forced_matrix_admin->removeCommission($user_id,$leader_id);
//                }
//            }else{
//                $msg=1;
//            }
//        }
//        $this->_viewData['msg']=$msg;
//        parent::index('admin/');
//    }
}