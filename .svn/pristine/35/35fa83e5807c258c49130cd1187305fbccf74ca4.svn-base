<?php
/**
 * User: ckf
 * Date: 2016/09/06
 * Time: 12:50
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class users_status_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_users_status_log');
    }

    public function index(){
        $this->_viewData['title'] = lang('users_status_log');

        //会员状态映射
        $sta_arr = array(
            '1' => lang('enabled'),
            '2' => lang('sleep')
        );

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $list = $this->tb_users_status_log->selectAll($searchData);
        $this->_viewData['list'] = $list;


        $this->load->library('pagination');
        $url = 'admin/users_status_log';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_users_status_log->getExceptionRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['sta_arr'] = $sta_arr;

        $this->_viewData['page_data'] = array(
            'uid' => $searchData['uid'],
        );

        parent::index('admin/');
    }

}