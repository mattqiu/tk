<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_msg extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_news');
    }

    public function index(){
        $this->_viewData['title'] = lang('my_msg');
        $searchData = $this->input->get();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $this->_viewData['lists'] = $this->m_news->getBoardListFromUsers($searchData,$this->_userInfo['id']);

        $this->load->library('pagination');
        $url = 'ucenter/my_msg';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_news->getBoardRowsShow($this->_userInfo['id']);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();

        parent::index();
    }

    public function getBoardCount(){
        if($this->input->is_ajax_request()){
			$count = $this->m_news->getBoardRowsFromUsers($this->_userInfo['id']);
			set_cookie('unread_count',$count,0,get_public_domain());
            die(json_encode(array('count'=>$count)));
        }
    }

    public function hadRead(){
        if($this->input->is_ajax_request()){
            $ids = $this->input->post('checkboxes');
            foreach($ids as $id){
                $this->db->replace('bulletin_board_status',array(
                    'board_id'=>$id,
                    'uid'=>$this->_userInfo['id'],
                    'status'=>1,
                ));
            }
            die(json_encode(array('success'=>1)));
        }
    }

    public function deleteMsg(){
        if($this->input->is_ajax_request()){
            $ids = $this->input->post('checkboxes');
            foreach($ids as $id){
                $this->db->replace('bulletin_board_status',array(
                    'board_id'=>$id,
                    'uid'=>$this->_userInfo['id'],
                    'status'=>2,
                ));
            }
            die(json_encode(array('success'=>1)));
        }
    }



}

