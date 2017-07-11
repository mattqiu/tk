<?php
/**
 * User: ckf
 * Date: 2016/5/25
 * Time: 16:44
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class cron_doing extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_cron_doing');
    }

    /* 脚本任务管理列表 */
    public function index(){

        $this->_viewData['title'] = lang('cron_doing');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['cron_name'] = isset($searchData['cron_name'])?$searchData['cron_name']:'';
        $list = $this->tb_cron_doing->selectAll($searchData);
        $this->_viewData['list'] = $list;


        $this->load->library('pagination');
        $url = 'admin/cron_doing';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_cron_doing->getExceptionRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['page_data'] = array(
            'cron_name' => $searchData['cron_name'],
            'page' => $searchData['page'],
        );

        parent::index('admin/');
    }

    /* 删除脚本任务管理记录 */
    public function cronDel(){
        $searchData = $this->input->get()?$this->input->get():array();
        $id = $searchData['id'];
        $list = $this->tb_cron_doing->findOne($id);
        if(empty($list)){
            echo 'id不存在';
            die();
        }
        $return = $this->tb_cron_doing->deldete($id);
        if($return){
            redirect(base_url('admin/cron_doing/index'));
        }
    }


}