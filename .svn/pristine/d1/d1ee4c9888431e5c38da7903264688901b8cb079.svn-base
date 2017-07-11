<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class blacklist extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_blacklist');
        //$this->m_global->checkPermission('blacklist',$this->_adminInfo);
    }

    public function index($id = NULL) {
        $this->_viewData['title'] = lang('blacklist');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['content'] = isset($searchData['content'])?$searchData['content']:'';
        $searchData['admin_id'] = isset($searchData['admin_id'])?$searchData['admin_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->tb_admin_blacklist->get_blacklist($searchData);

        $this->load->library('pagination');
        $url = 'admin/blacklist';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_admin_blacklist->get_blacklist_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }
    
    public function view_add_blacklist(){
        $this->load->view('admin/add_blacklist');
    }

    /**
     *  添加敏感词
     */
    public function add_blacklist(){
        if($this->input->is_ajax_request()){
            $content = trimall($this->input->post('t_name',true));

            if($content == ''){
                die(json_encode(array('success'=>0,'msg'=>lang('enter_blacklist'))));
            }

            $data = array('content'=>$content,'admin_id'=>$this->_adminInfo['id']);
            $success = $this->tb_admin_blacklist->add_blacklist($data);
            $msg = $success ? '' : lang('add_template_fail');
            die(json_encode(array('success'=>$success,'msg'=>$msg)));
        }
    }



}