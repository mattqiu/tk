<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Api extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model("service_message_model");
    }
 
    public function profitSharingPoint()
    {
        $user_id = $this->input->post('user_id', true);
        try{
            if(empty($user_id)) {
                throw new Exception("params lost");
            }
            $this->load->model("m_user");
            $profitSharingPoint = $this->m_user->getTotalSharingPoint($user_id);
            $data = array('error'=>true,"message"=>"success","profitSharingPoint"=>$profitSharingPoint);
            echo json_encode($data);exit;
        } catch(Exception $e) {
            $data = array('error'=>true,"message"=>$e->getMessage());
            echo json_encode($data);exit;
        }

    }
}
