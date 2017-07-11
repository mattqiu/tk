<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class commission_admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('commission_admin',$this->_adminInfo);
    }

    public function index() {
        
        $this->_viewData['title'] = lang('commission_admin');

        $this->load->model('m_admin_user');
        $this->load->model('tb_admin_manage_commission_logs');

        $searchData = $this->input->get()?$this->input->get():array();
        $page_url['page'] = $searchDatas['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $page_url['uid'] = $searchDatas['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $page_url['admin_email'] = isset($searchData['admin_email'])?$searchData['admin_email']:'';
        //通过邮箱查询用户ID 
        if($page_url['admin_email']){
            $one_admin = $this->m_admin_user->getUserByIdOrEmail($page_url['admin_email']);
            $searchDatas['admin_id'] =isset($one_admin["id"]) ? $one_admin["id"] :"";
        }
      //  $searchDatas['admin_id'] = $this->_viewData['adminInfo']['id'];
        //echo "<pre>";print_r($searchDatas);exit;
        $list = $this->tb_admin_manage_commission_logs->selectAll($searchDatas);

        $admin_id_arr = array();
        $admin_email  = array();

        foreach($list as $k=>$v){
//            $list[$k]['operator_table'] = '';
//            $list[$k]['operator_table_id'] = '';

            /** 20170313 不要每次查询数据库
            $one = $this->m_admin_user->getInfo($v['admin_id']);
            $list[$k]['email'] = isset($one['email'])?$one['email']:'';
            **/

            //20170313 一次查询所有数据,andy
            array_push($admin_id_arr,(int)$v['admin_id']);

//            if(!empty($v['key'])){
//                $arr = explode('|',$v['key']);
//                $list[$k]['operator_table'] = isset($arr[0])?$arr[0]:'';
//                $list[$k]['operator_table_id'] = isset($arr[1])?$arr[1]:'';
//            }

        }

        //获取匹配的管理员邮箱
        $admin_id_arr   = array_unique($admin_id_arr);
        $admin_info     = $this->m_admin_user->getAdminEmailByIdArr($admin_id_arr);

        //组装匹配数组
        if($admin_info){

            foreach($admin_info as $v){
                $admin_email[$v['id']] = $v;
            }

        }

        $this->_viewData['admin_email'] = $admin_email;

        $this->_viewData['list'] = $list;

        $this->load->library('pagination');
        $url = 'admin/commission_admin';
        add_params_to_url($url, $page_url);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_admin_manage_commission_logs->getExceptionRows($searchDatas);
        $config['cur_page'] = $searchDatas['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchDatas;

        $this->_viewData['page_data'] = array(
            'admin_email' => $page_url['admin_email'],
            'uid' => $searchDatas['uid'],
        );

        parent::index('admin/');
    }

    public function commChangeSub(){
        $postData = $this->input->post();
        $this->load->model('m_admin_user');
        $errorMsg = $this->m_admin_user->checkCommChangeSubData($this->_adminInfo['id'],$postData);
        if($errorMsg){
            $success = FALSE;
            $msg = $errorMsg;
        }else{
            $success = TRUE;
            $msg = lang('submit_success');
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
}
