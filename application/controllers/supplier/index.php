<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Index extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index() {
       
        parent::index('supplier/','','header_1','footer_1');
    }
    
    public function submit(){
        
        $postData = $this->input->post();
        $username = $postData['user_name'];
        $pwd = $postData['pwd'];
        $error_code = $this->m_admin_user->supplier_login($username,$pwd);
        if($error_code){
            $success=FALSE;
            $msg = lang(config_item('error_code')[$error_code]);
        }else{
            $success=TRUE;
            $data = array('jumpUrl'=>  base_url('supplier/goods/goods_list'));
        }
        echo json_encode(array('success'=>$success,'msg'=>isset($msg)?$msg:'','data'=>isset($data)?$data:array()));
    }
    
    /**
     * 退出
     */
    public function logout(){

        delete_cookie("adminSupplierInfo",  get_public_domain());        
        header("Location: " . base_url('supplier/index'));
       
    }

}