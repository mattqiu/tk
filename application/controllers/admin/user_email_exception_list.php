<?php
/**
 * User: ckf
 * Date: 2016/5/25
 * Time: 16:44
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class user_email_exception_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_user_email_exception_list');
        $this->load->model('tb_users');
    }

    public function index(){

        $this->_viewData['title'] = lang('user_email_exception_list');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $list = $this->tb_user_email_exception_list->selectAll($searchData);
        $this->_viewData['list'] = $list;


        $this->load->library('pagination');
        $url = 'admin/user_email_exception_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_user_email_exception_list->getExceptionRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['page_data'] = array(
            'uid' => $searchData['uid'],
        );

        parent::index('admin/');
    }

    /**
     *检验用户id
     */
    public function checkOrderId(){
        $uid_txt=$this->input->post('uid_txt');
        //不能为空
        if(trim($uid_txt)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }
        //检查用户id是否存在
        $one = $this->tb_users->getUserInfo($uid_txt);
        if(empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }
        //检查是否已经添加
        $one =$this->tb_user_email_exception_list->findOne($uid_txt);
        if(!empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_ture')));
            exit();
        }
    }
    //添加用户收发邮件例外uid
    public function checkData(){
        $txn_id=$this->input->post('uid_txt');
        //不能为空
        if(trim($txn_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }
        //检查用户id是否存在
        $one = $this->tb_users->getUserInfo($txn_id);
        if(empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }

        //检查是否已经添加
        $one =$this->tb_user_email_exception_list->findOne($txn_id);
        if(!empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_ture')));
            exit();
        }
        $add = $this->tb_user_email_exception_list->add_ones($txn_id);
        if($add){
            echo json_encode(array('success'=>true,'msg'=>lang('orderid_ture')));
        }

    }

    //删除记录
    public function do_delete_freight(){
        $id = $this->input->post('id');
        $one = $this->tb_user_email_exception_list->findOne($id);
        if(empty($one)) {
            echo json_encode(array('success' => false, 'msg' => lang("not_find_this_product_freight")));
            exit();
        }
        $res = $this->tb_user_email_exception_list->deldete($id);
        if($res){
            echo json_encode(array('success' => true, 'msg' => lang("delete_success")));
            exit();
        }else{
            echo json_encode(array('success' => false, 'msg' => lang("delete_failure")));
            exit();
        }

    }


}