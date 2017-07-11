<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class user_move_position extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('forced_matrix');
        $this->load->model('m_forced_matrix');
        $this->load->model('m_overrides');
        $this->load->model('m_helper');
    }

    public function index() {

       
        $this->_viewData['title'] = lang('user_move_position');       
        parent::index('admin/');
    }


    /**
     * @desc 用户位置移动
     */
    public function upmoveposition(){       

        $move_uid = $this->input->post('uid');
        $paserd_uid = $this->input->post('paserd_uid');
        
        $this->load->model('tb_users');
        $res = $this->tb_users->movePosition($move_uid,$paserd_uid); 
        if($res === true) {
            echo json_encode(array("success"=>true));   //移动更新成功
        } elseif($res === false ) {
            echo json_encode(array("success"=>false));   //移动更新失败
        } else {
            echo json_encode(array("success"=>-1));     //移动非法失败
        }
                       
    }

}

?>