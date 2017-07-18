<?php
/** change user's mobile
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/6
 * Time: 16:35
 */
class Change_mobile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("m_user");
        $this->load->model("tb_users");
        $this->load->model("service_message_model");
    }

    /**
     * @author wys
     * @description change user's mobile
     * @time 2017/03/06
     */
    public function index()
    {
        $this->_viewData['title'] = lang('change_mobile');
        // 获取用户信息
        if(empty($this->_userInfo)){
            redirect(base_url('login'));exit;
        }
        $user_info = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);

        $user_info['mobile_encrypt'] = mobile_encrypy($user_info['mobile']);
        $user_info['email_encrypt'] = email_encrypy($user_info['email']);

        $this->_viewData['user_info'] = $user_info;
        parent::index();
    }

    /**
     * @author  brady.wang
     * @description 验证邮箱验证码是否正确
     */
    public function verify_email_code()
    {

        $output = array();
        $html = '<div class="code_msg" style="height:300px;"><div style="margin-bottom:25px"><p style="float:left;width:80px">'.lang('new_phone').'</p><input type="text" id="new_phone" name="new_phone"  placeholder="'.lang('new_phone').'" maxlength="15"  onkeyup="value=value.replace(/[^\d]/g,\'\') "onbeforepaste="clipboardData.setData(\'text\',clipboardData.getData(\'text\').replace(/[^\d]/g,\'\'))"/></div>
                <div><p style="float:left;width:80px">'.lang('alipay_binding_vcode').'</p><input type="text" name="new_code" maxlength="6" id="new_code" placeholder="'.lang('alipay_binding_vcode').'"/><input type="button" value="'.lang('tps_get_captcha').'" style="margin-left:15px" id="get_new_code"/></div></div>
                <input type="button" class="next_btn1"  id="info_submit" value = "'.lang('submit').'" >';
        $output['message'] = $html;
        $uid = $this->_userInfo['id'];
        $user_info = $this->m_user->getUserByIdOrEmail($uid);
        try {

            if(!$this->input->is_ajax_request()) {
                throw  new Exception("40501001");
            }
            if(empty($user_info['id'])) {
                throw new Exception("10501010");//未登陆
            }

            $email_code = intval(trim($this->input->get_post('old_code',true)));
            $session = $this->session->userdata($user_info['email']);

            //验证验证码是否正确
            if(empty($email_code)) {
                throw new Exception("10501042");
            }

            if (!empty($session['email_or_phone'])) {
                if ($user['email'] = $session['email_or_phone'] && $session['code'] == $email_code) {
                    if (time()> $session['expire_time']) {
                        throw new Exception("10501006");
                    }
                } else {
                    throw new Exception("10501005");//验证码错误
                }
            } else {
                throw new Exception("10501037");
            }
            //验证码正确 返回 进行下一步
            $output['message'] = $html;
            $this->service_message_model->success_response($output);
        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }

    }//end of verify_email_code

    /**
     * @author brady.wang
     * @description 验证手机验证码是否正确
     */
   public function verify_mobile_code()
   {

       $output = [];
       $html = '<div class="code_msg" style="height:300px;"><div style="margin-bottom:25px"><p style="float:left;width:80px">'.lang('new_phone').'</p><input type="text" id="new_phone" name="new_phone"  placeholder="'.lang('new_phone').'" maxlength="15"  onkeyup="value=value.replace(/[^\d]/g,\'\') "onbeforepaste="clipboardData.setData(\'text\',clipboardData.getData(\'text\').replace(/[^\d]/g,\'\'))"/></div>
                <div><p style="float:left;width:80px">'.lang('alipay_binding_vcode').'</p><input type="text" name="new_code" maxlength="6" id="new_code" placeholder="'.lang('alipay_binding_vcode').'"/><input type="button" value="'.lang('tps_get_captcha').'" style="margin-left:15px" id="get_new_code"/></div></div>
                <input type="button" class="next_btn1"  id="info_submit" value = "'.lang('submit').'" >';
       $output['message'] = $html;

       $this->load->model("service_message_model");
       $this->load->model("tb_mobile_message_log");
       $this->load->model('tb_users');
       $this->load->model('m_user');
       try {

           if(!$this->input->is_ajax_request()) {
               throw  new Exception("40501001");
           }

           $mobile = trim($this->input->post('mobile', true));
           $code = trim($this->input->post("old_code", true));
           $uid = $this->input->post('uid',true);
           //手机号码非空验证
           if(empty($mobile)) {
               throw new Exception("10501001");
           }
           //手机号码格式验证
           if(!preg_match('/^1[34578]\d{9}$/', $mobile)) {
               throw new Exception("10501002");
           }
           //短信验证码验证
           if(empty($code)) {
               throw new Exception("10501003");
           }

           //验证短信验证码是否正确
           $this->tb_mobile_message_log->verify_mobile_code($mobile, $code);

           //验证成功 后删除验证码
           $this->tb_mobile_message_log->delete_code($mobile); //删除验证码
           $this->service_message_model->success_response($output);

       } catch (Exception $e) {
           $this->service_message_model->error_response($e->getMessage());
       }
   }

    /**
     * @author brady.wang
     * @description change user's mobile
     * @time 2017/03/07
     */
    public function submit_change()
    {
        $this->load->model("service_message_model");
        $this->load->model("tb_mobile_message_log");
        $this->load->model('tb_user_mobile_bind_log');
        $this->load->model('tb_users');
        $this->load->model('m_user');
        $data = $this->input->post();
        $user_info = $this->_userInfo;
        try {
            if(!$this->input->is_ajax_request()) {
                throw  new Exception("40501001");
            }
            if (empty($data['uid']) || $data['uid'] !== $user_info['id']) {
                throw  new Exception("10501010");
            }
            if (empty($data['new_mobile'])) {
                throw  new Exception("10501045");//手机号不能为空
            }

            if(!preg_match('/^1[34578]\d{9}$/',$data['new_mobile'])) {
                throw new Exception("10501002");//手机号格式错误
            }

           if(empty($data['new_code'])) {
               throw  new Exception("10501042");//验证码不能为空
           }
            //验证手机号是否被使用
            $mobile_res = $this->m_user->check_mobile_exists($data['new_mobile']);
            if(!empty($mobile_res)) {
                throw new Exception("10501007");
            }

            //验证短信验证码是否正确
            $this->tb_mobile_message_log->verify_mobile_code($data['new_mobile'], $data['new_code']);
            $this->db->trans_start();
            $affected_rows = $this->tb_users->binding_mobile_or_email($this->_userInfo['id'], $data['new_mobile']);
            $this->tb_users->unbind_mobile($this->_userInfo['id'],  $data['new_mobile']);
            if($affected_rows > 0) {
                $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'], array(7)); //同步信息
                //日志
                $logs = [
                    'type' => "modify_bind_mobile",
                    'old_mobile' => $data['old_mobile'],
                    'new_mobile' => $data['new_mobile'],
                    'create_time' => time()
                ];
                $this->tb_user_mobile_bind_log->add_log($logs);
                $this->tb_mobile_message_log->delete_code( $data['new_mobile']); //删除验证码
                $this->db->trans_complete();
                $data['message'] = lang("binding_mobile_success");
                $this->service_message_model->success_response($data);
            } else {
                throw new Exception("10501004");//绑定手机号失败
            }

        } catch(Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }
    }
}