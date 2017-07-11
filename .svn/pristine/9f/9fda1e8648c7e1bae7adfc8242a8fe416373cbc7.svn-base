<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 忘记密码 控制器
 * @author TPS
 */
class Forgot_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 显示界面
     * @see MY_Controller::index()
     */
    public function index() {
        
        $language_id = intval($this->session->userdata('language_id'));
        $location_id=$this->session->userdata('location_id');

        
        $this->_viewData['title']=lang('fgot_your_pwd').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        $this->_viewData['login_language_id']=$location_id;//登录的国家
        
        parent::index('mall/','forgot_pwd','header');
    }
    
    
    
    /**
     * 验证 输入的信息
     * leon 添加的方法
     */
    public function checkRegisterItemNew() {
        $requestData = $this->input->post();
    
        $registerData = array();
    
        $typeName = $requestData['itemName'];//要验证的字段名
    
        if($typeName == 'email_captcha' || $typeName == 'mobile_captcha'){
    
            $registerData = array('captcha' => $requestData['itemVal'],'account'=>trim($requestData['account']));
    
        }elseif ($typeName == 'email_pwd' || $typeName == 'mobile_pwd') {
    
            $registerData = array('pwd' => $requestData['itemVal'],'account'=>trim($requestData['account']));
    
        }elseif ($typeName == 'email_pwd_again' || $typeName == 'mobile_pwd_again') {
    
            $registerData = array('pwd_again' => $requestData['itemVal'],'account'=>trim($requestData['account']));
    
        }else{
            $registerData = array($requestData['itemName'] => $requestData['itemVal'],'account'=>trim($requestData['account']));
        }
    
        $this->load->model('m_user');
        $checkResult = $this->m_user->checkRegisterItems_new($registerData,'ispwd');
    
        $confirmData=array();
        foreach ($checkResult as $key => $value) {
            $confirmData[$typeName]=$value;
        }
    
        echo json_encode($confirmData);
        exit;
    }
    /**
     * 发送验证码
     * leon 添加的新方法
     */
    public function send_captcha(){
    
        if($this->input->is_ajax_request()){
             
            $account = $this->input->post('account');//账号信息
            $account = trim($account);
            //判断内容是不是为空
            if(!$account){
                die(json_encode(array('success'=>0,'msg'=>lang('regi_errormsg_mail'))));
            }
    
            //判断当前的国家
            $cur_language_id = get_cookie('curLan_id', true);
            if (empty($cur_language_id)) {
                $cur_language_id = 1;
            }
    
            if($cur_language_id == 2){
                if(is_numeric($account) && is_phone($account)){             //手机
                    $count=$this->phone_yzm($account, $cur_language_id);
                }elseif(is_email($account)){                                //邮箱
                    $count=$this->email_yzm($account, $cur_language_id);
                }
            }else{
                if(is_email($account)){                                     //邮箱
                    $count=$this->email_yzm($account, $cur_language_id);
                }
            }
    
            if(isset($count)){
                die(json_encode(array('success'=>1)));
            }else{
                die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
            }
        }
    }
    /**
     * 提交 修改密码
     * leon 添加的新方法
     */
    public function submit_register(){
    
        if (config_item('website_stop_join')) {
            echo json_encode(array('success' => TRUE, 'jumpUrl' => base_url('/announce')));
            exit;
        }
        
        $data = $this->input->post();
        $requestData = $this->edit_data($data);
        
        //判断协议是不是有选择
        if(!isset($requestData['disclaimer'])){
            $requestData['disclaimer'] = FALSE;
        }
    
        //判断提交的是什么
        if(isset($requestData['email'])){
            unset($requestData['mobile']);
            $requestData['account']=trim($requestData['email']);
        }elseif (isset($requestData['mobile'])) {
            unset($requestData['email']);
            $requestData['account']=trim($requestData['mobile']);
        }
       
        //比较2次输入的密码是否一致
        if(!isset($requestData['compare_pwd'])) {
            $requestData['compare_pwd'] = "Y";
        }
        $this->load->model('m_user');
        $checkResult = $this->m_user->checkRegisterItems_new($requestData,'ispwd');
        $success = true;
        foreach($checkResult as $resultItem){
            if(!$resultItem['isRight']){
                $success = false;
                break;
            }
        }
    
        if($success){
            $userInfo = $this->m_user->getUserByIdOrEmail($requestData['account']);
            $newPwdEncy = $this->m_user->pwdEncryption(trim($requestData['pwd']), $userInfo['token']);
            $this->m_user->updatePwdEncy($userInfo['id'],$newPwdEncy);
            $this->m_user->addInfoToWohaoSyncQueue($userInfo['id'],array(2));
            $msg = lang('reset_pwd_success');

            //清空验证码
            $this->session->unset_userdata($requestData['account']);        //删除记录的验证码数据

            echo json_encode(array('success'=>$success,'msg'=>$msg));
            exit;
        }
        echo json_encode(array('success'=>$success,'checkResult'=>$checkResult));
        exit;
    }
    
    
    /**
     * 修改数组中的数据
     */
    public function edit_data($data){
        $registerData = array();
        foreach ($data as $key => $value) {
            if($key == 'email_captcha' || $key == 'mobile_captcha'){
                $registerData['captcha'] = $value;
            }elseif ($key == 'email_pwd' || $key == 'mobile_pwd') {
                $registerData['pwd'] = $value;
            }elseif ($key == 'email_pwd_again' || $key == 'mobile_pwd_again') {
                $registerData['pwd_again'] = $value;
            }elseif ($key == 'email_disclaimer' || $key == 'mobile_disclaimer') {
                $registerData['disclaimer'] = $value;
            }else{
                $registerData[$key] = $value;
            }
        }
        return $registerData;
    }
    
    
    
    /**
     * 2017-01-09 修改原来的需求 此方法不再使用
     * 发送邮件
     */
    public function sendPwdResetMail(){
        $email = $this->input->post('email');
        $this->load->model('m_user');
        $emailCheckRes = $this->m_user->checkUserEmail($email);
        if($emailCheckRes['isRight']){
            $updateTokentime = time();
            $this->m_user->writeUserUpdateTokenTime($emailCheckRes['userInfo']['id'],$updateTokentime);

			$data['email'] = $emailCheckRes['userInfo']['email'];
			$resetPwdUrl = base_url('reset_pwd').'?uid='.$emailCheckRes['userInfo']['id'].'&updateToken='.$this->m_user->createToken($updateTokentime);
            $data['content'] = lang('reset_mail_text').'<br/><a href="'.  $resetPwdUrl.'">'.$resetPwdUrl.'</a>';
			$data['dear'] = lang('dear_');
			$data['email_end'] = lang('email_end');
			$content = $this->load->view('ucenter/public_email',$data,TRUE);

			send_mail($email, lang('pwd_reset'), $content);

			$success = TRUE;
            $msg = lang('send_reset_mail_success');
        }else{
            $success = FALSE;
            $msg = $emailCheckRes['msg'];
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
    }

}
