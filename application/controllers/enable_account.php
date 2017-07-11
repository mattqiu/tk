<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Enable_account extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->_viewData['title']=lang('').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();

        $param = $this->input->get('param');
        $requestData = unserialize(base64_decode($param));
        $this->load->model('m_user');
        $res = $this->m_user->enableAccount($requestData);
        $this->_viewData['success'] = $res == 0?TRUE:FALSE;
        if ($res == 0) {
            $this->_viewData['msg'] = lang('account_active_success');
            $userInfo = $this->m_user->getUserByIdOrEmail($requestData['id']);
            set_cookie("userInfo", serialize(array('uid' => $userInfo['id'],'sign' => sha1($userInfo['token']))), 0, get_public_domain());
        } else {
            $this->_viewData['msg'] = lang('account_active_false');
        }
        parent::index('mall/','','header');
    }

}
