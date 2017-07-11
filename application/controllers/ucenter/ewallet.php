<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ewallet extends MY_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('m_ewallet');
    }


    /** 注册 eWallet tps用户 */
    public function register(){

        if(!$this->input->post('ewallet_name')){
            $result['success'] = 0;
            $result['msg'] = lang('no_ewallet_name');
            echo json_encode($result);exit;
        }
        $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        if(!$user['name'] || !$user['address'] || !$user['mobile']){
            $result['success'] = 0;
            $result['msg'] = lang('view_complete_your_info');
            echo json_encode($result);exit;
        }
        $user['ewallet_name'] = $this->input->post('ewallet_name',true);

        if (preg_match("/[\x7f-\xff]/", $user['ewallet_name'])){
            $result['success'] = 0;
            $result['msg'] = 'Can not contain Chinese';
            echo json_encode($result);exit;
        }

        $result = $this->m_ewallet->register_api($user);
        if($result['success']){
            $result['msg'] = lang('ewallet_success');
            $this->m_user->updateUserInfo(array('ewallet_name'=>$user['ewallet_name']),$this->_userInfo['id']);
        }
        echo json_encode($result);exit;

    }



    public function do_load(){

            $return = $this->m_ewallet->do_load();
            print_r($return);
    }
}
