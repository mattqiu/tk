<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class set_except_group extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->m_global->checkPermission('set_except_group',$this->_adminInfo);
    }

    public function index()
    {
        $this->_viewData['title'] = lang('set_except_group');
        parent::index('admin/');
    }

    public function check_data(){
        $id_list = $this->input->post('id_list');
        $list=array();
        if ($id_list == '') {
            echo json_encode(array('success' => false, 'msg' => lang('id_not_null')));
            exit();
        }

        if (!preg_match('/^[0-9,]+$/', $id_list)) {
            echo json_encode(array('success' => false, 'msg' => lang('id_format_is_not_correct')));
            exit();
        }

        if (strpos($id_list, ',')) {
            $list = explode(',', $id_list);
        }else{
            $list[]=$id_list;
        }


        foreach ($list as $v) {
            $sql = "insert into set_except_group (user_id) value($v) ";
            $this->db->query($sql);
        }
        echo json_encode(array('success' => true, 'msg' => lang('submit_success')));
        exit();
    }
}

