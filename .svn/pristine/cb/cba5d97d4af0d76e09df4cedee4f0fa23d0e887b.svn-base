<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class tickets_black_list extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_tickets_black_list');
    }

    public function index()
    {
        $this->_viewData['title'] = lang('tickets_black_list');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['status'] = 0;
        $list = $this->tb_admin_tickets_black_list->get_black_list($searchData);
        $this->_viewData['list'] = $list;
        $url = 'admin/tickets_black_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_tickets_black_list->get_black_list_count($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    public function delete_tickets_black_list(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post(NULL,TRUE);
            if(is_numeric($data['id'])){
                $b = $this->tb_admin_tickets_black_list->delete_black_list($data['id']);
                if($b){
                    die(json_encode(array('success'=>1,'msg'=>lang('update_black_list_success'))));
                }
            }
        }
        die(json_encode(array('success'=>0,'msg'=>lang('update_black_list_fail'))));
    }

    public function add_tickets_black_list(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post(NULL,TRUE);
            if(is_numeric($data['uid'])){
                $b = $this->tb_admin_tickets_black_list->is_black_list($data['uid']);
                if(!$b){
                    $insert_arr = array(
                        'uid' =>$data['uid'],
                        'admin_id'=>$this->_adminInfo['id'],
                    );
                    $r = $this->tb_admin_tickets_black_list->add_black_list($insert_arr);
                    if($r){
                        die(json_encode(array('success'=>1,'msg'=>lang('add_black_list_success'))));
                    }
                }else{
                    die(json_encode(array('success'=>0,'msg'=>lang('black_list_exist'))));
                }
            }
        }
        die(json_encode(array('success'=>0,'msg'=>lang('add_black_list_fail'))));
    }
}