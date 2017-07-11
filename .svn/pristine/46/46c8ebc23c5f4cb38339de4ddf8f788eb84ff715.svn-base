<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class faq extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->_viewData['title'] = lang('faq');
        $language_id=(int)$this->session->userdata('language_id');
        $cate_id = 1132;
        switch($language_id){
            case 1:
                $cate_id = 1131;
                break;
            case 2:
                $cate_id = 1132;
                break;
            case 3:
                $cate_id = 1133;
                break;
            case 4:
                $cate_id = 1134;
                break;
        }
        $one = $this->db->where('language_id',$language_id)->where('cate_id',$cate_id)->get('news')->row_array();
        $this->_viewData['html_content'] = isset($one['html_content'])?$one['html_content']:'';
        parent::index();
    }

}