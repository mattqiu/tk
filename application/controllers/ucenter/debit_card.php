<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc:
 * Date: 2017/7/5
 * Time: 17:19
 */
class debit_card extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_users_bank_card");
    }

    public function index()
    {

        $user = $this->_userInfo;

        $this->_viewData['user'] = $user;
        $param = [
            'where'=>[
                'uid'=>$user['id']
            ],
            'limit'=>1
        ];
        $user_card = $this->tb_users_bank_card->get($param,false,true,true);
        if (!empty($user_card)) {
            $this->_viewData['title'] = lang("unbind_bank_card");
        } else {
            $this->_viewData['title'] = lang("bind_bank_card");
        }
        //获取银行卡信息
        $this->_viewData['user_card'] = $user_card;
        parent::index();
    }

    /**
     * @author brady.wang
     * @desc   绑定银行卡
     */
    public function do_bind()
    {
        $data = $this->input->post();
        $output = ['success'=>true,'message'=>'','data'=>[]];
        $this->load->model("tb_users_bank_card");
        $this->load->model("tb_mobile_message_log");
        $this->load->model("service_message_model");
        $type = $data['bd_type'];

        //数据验证
        try {
            if(empty($data['bank_name'])) {
                throw new Exception(lang('please_input_bank_name'));
            }
            if (!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $data['bank_name'])) {
                throw new Exception(lang("bank_name_china_only"));
            }

            if(empty($data['bank_branch_name'])) {
                throw new Exception(lang("please_input_bank_branch_name"));
            }

            if (!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $data['bank_branch_name'])) {
                throw new Exception(lang("bank_branch_name_china_only"));
            }

            if(empty($data['bank_number'])) {
                throw new Exception(lang("please_input_bank_number"));
            }

            if (!preg_match("/^\d{1,50}$/", $data['bank_number'])) {
                throw new Exception(lang("bank_number_only_number"));
            }

            if(empty($data['bank_number_repeat'])) {
                throw new Exception(lang("confirm_bank_number"));
            }

            if($data['bank_number_repeat'] !== $data['bank_number']) {
                throw new Exception(lang("bank_number_not_same"));
            }

            if(empty($data['vcode'])) {
                throw new Exception(lang("phone_code_not_null"));
            }

            if(empty($data['password'])) {
                throw new Exception(lang("please_input_password"));
            }

            if ($this->m_user->encyTakeCashPwd($data['password'], $this->_userInfo['token']) !== $this->_userInfo['pwd_take_out_cash']) {
                throw new Exception(lang("funds_pwd_error"));
            }

            //验证短信验证码是否正确
            $this->tb_mobile_message_log->verify_mobile_code($this->_userInfo['mobile'], $data['vcode']);

            //验证成功 后删除验证码
            $this->tb_mobile_message_log->delete_code($this->_userInfo['mobile']); //删除验证码


            unset($data['bank_number_repeat']);
            unset($data['vcode']);
            unset($data['password']);
            unset($data['bd_type']);
            $this->tb_users_bank_card->add_bank_card($data);
            $output['message'] = lang("binding_mobile_success");
            echo json_encode($output);

        }catch (Exception $e) {
            if (is_numeric($e->getMessage())) {
                $this->service_message_model->error_response($e->getMessage());
            } else {
                $output['success'] = false;
                $output['message'] = $e->getMessage();
                echo json_encode($output);
            }
        }





    }

    public function do_unbind()
    {
        $data = $this->input->post();
        $output = ['success'=>true,'message'=>'','data'=>[]];
        $this->load->model("tb_users_bank_card");
        $type = $data['bd_type'];

        //检测 资金密码是否正确
        $this->load->model('m_user');
        $this->load->model('service_message_model');
        $this->load->model('tb_mobile_message_log');
        try {

            if(empty($data['vcode'])) {
                throw new Exception(lang("phone_code_not_null"));
            }

            if(empty($data['password'])) {
                throw new Exception(lang("please_input_password"));
            }

            if ($this->m_user->encyTakeCashPwd($data['password'], $this->_userInfo['token']) !== $this->_userInfo['pwd_take_out_cash']) {
                throw new Exception(lang("funds_pwd_error"));
            }

            //验证码验证
            if(!preg_match('/\d{4,6}/',$data['vcode'])) {
                throw new Exception(lang("phone_code_rule_error"));
            }


            //验证短信验证码是否正确
            $this->tb_mobile_message_log->verify_mobile_code($this->_userInfo['mobile'], $data['vcode']);

            //验证成功 后删除验证码
            $this->tb_mobile_message_log->delete_code($this->_userInfo['mobile']); //删除验证码

            unset($data['bd_type']);
            unset($data['vcode']);
            unset($data['password']);
            $this->tb_users_bank_card->del_bank_card($data['uid']);
            $output['message'] = '解绑成功';
            echo json_encode($output);

        } catch(Exception $e) {
            if (is_numeric($e->getMessage())) {
                $this->service_message_model->error_response($e->getMessage());
            } else {
                $output['success'] = false;
                $output['message'] = $e->getMessage();
                echo json_encode($output);
            }

        }



    }
}