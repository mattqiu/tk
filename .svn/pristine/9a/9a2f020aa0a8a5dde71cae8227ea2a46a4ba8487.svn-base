<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/1/6
 * Time: 12:17
 * 手机验证码发送
 */
class Mobile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_mobile_message_log");
        $this->load->model("service_message_model");
    }

    /**
     * 获取验证码
     */
    public function get_mobile_code()
    {
        $output = ['error' => true, 'message' => ''];
        try {
            if($this->input->is_ajax_request()) {
                $mobile = trim($this->input->post('mobile', true));
                $type = trim($this->input->post('type', true));
                $type = empty($type) ? '' : $type;
                $action_id = $this->input->get_post('action_id');
                $action_id = empty($action_id) ? 4 : $action_id;
                if($type == "modify_mobile_bind" || $type == 'bind_mobile') {
                    $this->load->model('m_user');
                    $mobile_res = $this->m_user->check_mobile_exists($mobile);
                    if(!empty($mobile_res)) {
                        throw new Exception(lang('phone_has_been_userd'));
                    }
                }
                //手机号码非空验证
                if(empty($mobile)) {
                    throw new Exception(lang('please_input_mobile'));
                }
                //手机号码格式验证
                if(!preg_match('/^1[34578]\d{9}$/', $mobile)) {
                    throw new Exception(lang("mobile_format_error"));
                }

                $send_res = $this->tb_mobile_message_log->send_mobile_code($mobile,$action_id);
                if($send_res['error'] == true) {
                    throw new Exception($send_res['msg']);
                }
                //发送成功
                $output['error'] = false;
                $output['message'] = 'success';
                echo json_encode($output);

            } else {
                $output['message'] = "hacker"; //非ajax请求
                echo json_encode($output);
            }

        } catch (Exception $e) {
            $output['message'] = $e->getMessage();
            echo json_encode($output);
        }
    }

    public function send_email_code()
    {
        $email  = trim($this->input->post('email'));
        $uid = $this->input->post('uid',true);


        try {
            if (empty($email)) {
                throw new Exception("40001000");//系统错误 无参数
            }
            $this->load->model("m_user");
            $user_info = $this->m_user->getUserByIdOrEmail($uid);
//            if($user_info['send_email_time'] > time() - 30) {
//                throw new Exception("10501034");//操作频繁
//            }

            if($this->input->is_ajax_request()){
                $cur_language_id = get_cookie('curLan_id', true);
                if (empty($cur_language_id)) {
                    $cur_language_id = 1;
                }

                if(preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $email)) {//邮箱
                    $this->email_yzm_v2($email, $cur_language_id);
                    if (!empty($uid)) {
                        $this->m_user->updateCreateTime($uid);
                    }
                    $data['message'] = '';
                    $this->service_message_model->success_response($data);
                } else {
                    throw new Exception("10501036");//邮箱格式不正确
                }


            } else {
                throw new Exception("40501001");
            }
        } catch(Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }

    }

}