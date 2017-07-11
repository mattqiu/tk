<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Synchronize_wo_hao extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('synchronize_wo_hao', $this->_adminInfo);
    }

    public function index() {
        $this->_viewData['title'] = lang('synchronize_wo_hao');
        parent::index('admin/');
    }

    /** 同步到沃好 */
    public function synchronize() {
        if ($this->input->is_ajax_request()) {
            $uid = $this->input->post('user_id');
            $this->load->model('m_user');
            $uids=array_unique(array_filter(explode(',', $uid)));//分割，去重，去空后的id数组
            foreach ($uids as $v) {
                $this->m_user->addInfoToWohaoSyncQueue($v, array(0));
            }
            die(json_encode(array('success' => 1,'msg' => 'OK')));
        }
    }

}
