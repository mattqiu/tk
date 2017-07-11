<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 注册 控制器
 * @author TPS
 * leon
 */
class Register extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 显示界面
     * @see MY_Controller::index()
     */
    public function index() {
        
        $location_id=$this->session->userdata('location_id');
        $this->_viewData['title']=lang('nav_register').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        $this->_viewData['login_language_id']=$location_id;//登录的国家
        //防csrf攻击
        $csrf_hash = md5(uniqid(rand(), TRUE));
        set_cookie("my_csrf_name", $csrf_hash, 0, get_public_domain());
        $this->_viewData['csrf_hash'] = $csrf_hash;
        
        parent::index('mall/','register','header');
    }

    /**
     * 注册 验证输入框中的信息 --- leon
     */
    public function checkRegisterItemNew() {

        $requestData = $this->input->post();

        $registerData = array();               //进行验证的数组
        $typeName = $requestData['itemName'];  //要验证的字段名
        $itemVal = $requestData['itemVal'];    //要验证的字段值

        if($typeName == 'email_captcha' || $typeName == 'mobile_captcha'){
            $registerData = array('captcha' => $requestData['itemVal'],'account'=>trim($requestData['account']));
        }elseif ($typeName == 'email_pwd' || $typeName == 'mobile_pwd') {
            $registerData = array('pwd' => $requestData['itemVal'],'account'=>trim($requestData['account']));
        }elseif ($typeName == 'email_pwd_again' || $typeName == 'mobile_pwd_again') {
            $registerData = array('pwd_again' => $requestData['itemVal'],'account'=>trim($requestData['account']));
        }else{

            $registerData = array($requestData['itemName'] => $requestData['itemVal'],'account'=>trim($requestData['account']));

            if(isset($requestData['reg_type'])){
                $registerData = array($requestData['itemName'] => $requestData['itemVal'],'account'=>trim($requestData['account']),'reg_type'=>trim($requestData['reg_type']));
            }else{
                $registerData = array($requestData['itemName'] => $requestData['itemVal'],'account'=>trim($requestData['account']));
            }
        }

        //判断是不是店铺加盟界面的账号
        if(isset($registerData['emailmobile']) || !empty($registerData['emailmobile'])){
            if(!preg_match("/[^\d ]/",$registerData['emailmobile'])){
                $registerData = array('mobile' => $requestData['itemVal'],'reg_type'=>trim($requestData['reg_type']));
            }else{
                $registerData = array('email' => $requestData['itemVal'],'reg_type'=>trim($requestData['reg_type']));
            }
            unset($registerData['emailmobile']);
        }

        //$this->load->model('m_user');
        $this->load->model('tb_users');
        $this->load->helper('cookie');
        
        //获取redis保存的注册验证码  
        $sid = get_cookie('register_captcha');   //redis保存key
        if(strlen($sid) == 32 ) {
            $code = $this->tb_users->getRegisterCaptcha($sid); 
        } else {
            $send = get_cookie('captcha_send'); 
            if($send == 1) {  //已经点击发送
                 $code = 1;
            } else {
                 $code = 0;
            }
             
        }
        
        if($code ==  -1) {   //Redis获取注册验证码过期 
             
              $registerData['redis_code'] = -1;
            
        } else if($code == 1) {   //redis保存失败 改为cookie保存
                $cookie_code  = get_cookie('regcookie_captcha');
                if(strlen($cookie_code) == 32) {
                  $registerData['cookie_code'] = $cookie_code ; 
                } else {
                  $registerData['cookie_code'] = 0;   //用户可能自行改动cookie值
                }
                
        } else if($code == 0) {   //redis保存失败 改为cookie保存
                
               $registerData['cookie_code'] = 0;   //用户可能自行改动cookie值

        } else {     //Redis获取验证码成功
                $registerData['redis_code'] = $code;    
        }
       
        $checkResult = $this->tb_users->checkRegisterItems_new($registerData);

        $confirmData = array();
        foreach ($checkResult as $key => $value) {
            $confirmData[$typeName]=$value;
        }

        echo json_encode($confirmData);
        exit;
    }

    /**
     * 注册 发送验证码 --- leon
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

            //判断账号属于什么
            if($cur_language_id == 2){
                if(is_numeric($account) && is_phone($account)){             //手机
                    $count=$this->phone_yzm($account, 1);                   //发送验证码
                }elseif(is_email($account)){                                //邮箱
                    $count=$this->email_yzm($account, $cur_language_id);    //发送验证码
                }
            }else{
                if(is_email($account)){                                     //邮箱
                    $count=$this->email_yzm($account, $cur_language_id);    //发送验证码
                }
            }
            
            if(isset($count)){
                $this->load->helper('cookie');
                set_cookie("captcha_send", 1, 1800, get_public_domain());  //点击成功送验证码
                die(json_encode(array('success'=>1)));//成功发送验证码
            }else{
                die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));//从新发送
            }
        }
    }

    /**
     * 注册 提交注册信息 --- leon
     */
    public function submit_register(){
        
        if (config_item('website_stop_join')) {
            echo json_encode(array('success' => TRUE, 'jumpUrl' => base_url('/announce')));
            exit;
        }

        $data = $this->input->post();
        $requestData = $this->edit_data($data);//修改提交数据中的key值
        
        //防csrf
        if(isset($requestData['my_csrf_token'])) {
            $cookie_csrf_hash = get_cookie("my_csrf_name");
            $form_csrf_hash = $requestData['my_csrf_token'];
            if($cookie_csrf_hash !== $form_csrf_hash) {
                echo json_encode(array('success' => 0, 'msg' => lang('try_again')));
                exit;
            }
        }

        //判断协议是不是有选择
        if(!isset($requestData['disclaimer'])){
            $requestData['disclaimer'] = FALSE;
        }

        //判断提交的是什么
        if(isset($requestData['email'])){
            unset($requestData['mobile']);
            $requestData['account']=trim($requestData['email']);
            $requestData['pwdOriginal']=$requestData['pwd'];
        }elseif (isset($requestData['mobile'])) {
            unset($requestData['email']);
            $requestData['account']=trim($requestData['mobile']);
            $requestData['pwdOriginal']=$requestData['pwd'];
        }

        $this->load->model('tb_users');
        $this->load->helper('cookie');
        
        //获取redis保存的注册验证码  
        $sid = get_cookie('register_captcha');   //redis保存key
        if(strlen($sid) == 32 ) {
            $code = $this->tb_users->getRegisterCaptcha($sid); 
        } else {
            $send = get_cookie('captcha_send'); 
            if($send == 1) {  //已经点击发送
                 $code = 1;
            } else {
                 $code = 0;
            }
            
        }
        
        if($code ==  -1) {   //Redis获取注册验证码过期 
                $requestData['redis_code'] = -1;

        } else if($code == 1 ) {   //redis保存失败 改为cookie保存
                $cookie_code  = get_cookie('regcookie_captcha');
                if(strlen($cookie_code) == 32) {
                  $requestData['cookie_code'] = $cookie_code ; 
                } else {
                  $requestData['cookie_code'] = 0;   //用户可能自行改动cookie值
                }
                
        } else if($code == 0) {
                $requestData['cookie_code'] = 0;
        }  else  {     //Redis获取验证码成功
                $requestData['redis_code'] = $code;    
        }
        
     
        $checkResult = $this->tb_users->checkRegisterItems_new($requestData);//验证信心
        $success = true;
        foreach($checkResult as $resultItem){
            if(!$resultItem['isRight']){
                $success = false;
                break;
            }
        }

        if($success){
            $account_info = trim($requestData['account']);
            unset($requestData['captcha']);
            unset($requestData['pwd']);
            unset($requestData['pwd_again']);
            unset($requestData['account']);
            unset($requestData['disclaimer']);
            unset($requestData['my_csrf_token']);
            unset($requestData['redis_code']);
            unset($requestData['cookie_code']);

            $requestData = TrimArray($requestData);//去除值的空格
       
            $registerData = $this->tb_users->register_user($requestData);//注册账号

            if($registerData === FALSE){
                //清空验证码
                $this->session->unset_userdata($account_info);        //删除记录的验证码数据
                echo json_encode(array('success' => 0, 'msg' => lang('try_again')));
                exit;
            }
            $publicDomain = get_public_domain();
            set_cookie("reg_data", serialize($registerData), 0, $publicDomain);
            set_cookie("my_csrf_name", "", time()-100, get_public_domain());
            set_cookie("register_captcha", "", time()-100, $publicDomain);
            set_cookie("regcookie_captcha", "", time()-100, $publicDomain);  
            set_cookie("captcha_send", "", time()-100, $publicDomain);
        }
        echo json_encode(array('success'=>$success,'checkResult'=>$checkResult));
        exit;
    }




    /**
     * 修改提交的数组中的key值 --- leon
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
            }elseif ($key == 'emailmobile') {//店铺加盟账号
                if(!preg_match("/[^\d ]/",$value)){
                    $registerData['mobile'] = $value;
                }else{
                    $registerData['email'] = $value;
                }
            }else{
                $registerData[$key] = $value;
            }
        }
        return $registerData;
    }


    //下面的是 没有修改之前的方法 -----------------------------------

    /**
     * 店铺加盟 提交注册信息
     */
    public function submit_subordinate() {
        
        if (config_item('website_stop_join')) {
            echo json_encode(array('success' => TRUE, 'jumpUrl' => base_url('/announce')));
            exit;
        }
        
        $requestData = $this->input->post();
        if (!isset($requestData['disclaimer'])) {
            $requestData['disclaimer'] = FALSE;
        }

        $requestData['reg_type'] = isset($requestData['reg_type']) ? $requestData['reg_type'] : 0 ; //单选
        $requestData['captcha'] = isset($requestData['captcha']) ? $requestData['captcha'] : 0 ;    //验证码

        $requestData['action_id'] = 1;

        $this->load->model('m_user');
        $requestData['is']=1;//用于判断是否提交
        $checkResult = $this->m_user->checkRegisterItems($requestData);//检测提交的信息
        unset($requestData['is']);
        $success = TRUE;
        foreach ($checkResult as $resultItem) {
            if (!$resultItem['isRight']) {
                $success = FALSE;
                break;
            }
        }
        if ($success) {
            unset($requestData['disclaimer']);
            unset($requestData['captcha']);
            unset($requestData['action_id']);
            $reg_type = isset($requestData['reg_type']) ? $requestData['reg_type'] : 0 ;//判断是有账号注册还是无账号注册

            /** 手机注册 */
            if(is_numeric($requestData['email'])){
                $requestData['mobile'] = $requestData['email'];
                unset($requestData['email']);
            }

            $registerData = $this->m_user->register($requestData);   //注册账号
            if($registerData === FALSE){
                echo json_encode(array('success' => 0, 'msg' => lang('try_again')));
                exit;
            }
            
            if($reg_type == 1){ //已有賬戶直接跳轉到用戶後台首頁
                delete_cookie("userInfo",  get_public_domain());                      //去掉之前的登陆cookie
                $userInfo = $this->m_user->getUserByIdOrEmail($registerData['id']);   //获取用户信息
                set_cookie("userInfo", serialize(array('uid' => $userInfo['id'],'sign' => sha1($userInfo['token']))), 0, get_public_domain());
                echo json_encode(array('success' => $success, 'jumpUrl' =>'/ucenter'));
                exit;
            }else{
                $publicDomain = get_public_domain();
                set_cookie("reg_data", serialize($registerData), 0, $publicDomain);
            }
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }

    /**
     * 验证输入的信息
     * 原始方法
     */
    public function checkRegisterItem() {
        
        $requestData = $this->input->post();
        
        $email_new = isset($requestData['email_new']) ? $requestData['email_new'] : '';
		$reg_type = isset($requestData['reg_type']) ? $requestData['reg_type'] : '0';

        $registerData = array(
            $requestData['itemName'] => $requestData['itemVal'],
            'pwdVal' => $requestData['pwdVal'],
            'email_new'=>$email_new,
            'reg_type'=>$reg_type
        );

		if(isset($registerData['captcha'])){
			$registerData['email'] = $email_new;
			$registerData['action_id'] = 1;
		}

        $this->load->model('m_user');
        $checkResult = $this->m_user->checkRegisterItems($registerData);
        echo json_encode($checkResult);
        exit;
    }

    /**
     * 提交注册信息
     */
    public function submit() {
        if (config_item('website_stop_join')) {
            echo json_encode(array('success' => TRUE, 'jumpUrl' => base_url('/announce')));
            exit;
        }
        $requestData = $this->input->post();
        if (!isset($requestData['disclaimer'])) {
            $requestData['disclaimer'] = FALSE;
        }

		$requestData['reg_type'] = isset($requestData['reg_type']) ? $requestData['reg_type'] : 0 ;
		$requestData['captcha'] = isset($requestData['captcha']) ? $requestData['captcha'] : 0 ;

		if(isset($requestData['reg_type']) && $requestData['reg_type'] == 1){ //已有賬戶
			unset($requestData['email_re']);
			unset($requestData['pwdOriginal_re']);
		}

		$requestData['action_id'] = 1;

        $this->load->model('m_user');
        $requestData['is']=1;//用于判断是否提交
        $checkResult = $this->m_user->checkRegisterItems($requestData);
        unset($requestData['is']);
        $success = TRUE;
        foreach ($checkResult as $resultItem) {
            if (!$resultItem['isRight']) {
                $success = FALSE;
                break;
            }
        }

        if ($success) {
            unset($requestData['pwdOriginal_re']);
            unset($requestData['email_re']);
            unset($requestData['disclaimer']);
            unset($requestData['captcha']);
            unset($requestData['action_id']);
			$reg_type = isset($requestData['reg_type']) ? $requestData['reg_type'] : 0 ;

			/** 手機註冊 */
			if(is_numeric($requestData['email'])){
				$requestData['mobile'] = $requestData['email'];
				unset($requestData['email']);
			}

            $registerData = $this->m_user->register($requestData);

			if($registerData === FALSE){
				echo json_encode(array('success' => 0, 'msg' => lang('try_again')));
				exit;
			}
			if($reg_type == 1){ //已有賬戶直接跳轉到用戶後台首頁
				delete_cookie("userInfo",  get_public_domain()); //去掉之前的登陆cookie
				$userInfo = $this->m_user->getUserByIdOrEmail($registerData['id']);
				set_cookie("userInfo", serialize(array('uid' => $userInfo['id'],'sign' => sha1($userInfo['token']))), 0, get_public_domain());
				echo json_encode(array('success' => $success, 'jumpUrl' =>'/ucenter'));
				exit;
			}else{

				$publicDomain = get_public_domain();
				set_cookie("reg_data", serialize($registerData), 0, $publicDomain);

			}
        }
		echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
//        log_message("ERROR",json_encode(array('success' => $success, 'checkResult' => $checkResult)));
		exit;
    }

	/**
	 * 添加注册验证码
	 */
	public function add_captcha(){
		if($this->input->is_ajax_request()){
			
			$email_or_phone = $this->input->post('email_or_phone');
			$reg_type = $this->input->post('reg_type') ? $this->input->post('reg_type') : 0;
			$action_id = $this->input->post('action_id') ? $this->input->post('action_id') : 1;

			//判断内容是不是为空
			if(!$email_or_phone){
				die(json_encode(array('success'=>0,'msg'=>lang('regi_errormsg_mail'))));
			}

			if($action_id == 4){//绑定操作，检查手机号，或者邮箱是否已绑定过
				$this->load->model('tb_users');
				$is_binding = $this->tb_users->is_check_binding($email_or_phone);
				if($is_binding){
					die(json_encode(array('success'=>0,'msg'=>lang('is_binding_info'))));
				}
			}

			$this->load->model('m_user');
			$checkResult = $this->m_user->checkRegisterItems(array('email'=>$email_or_phone,'reg_type'=>$reg_type));
			if($checkResult['email']['isRight'] === FALSE ){
				die(json_encode(array('success'=>0,'checkResult'=>$checkResult)));
			}

			$cur_language_id = get_cookie('curLan_id', true);
			if (empty($cur_language_id)) {
				$cur_language_id = 1;
			}
            if(is_numeric($email_or_phone) && preg_match('/^1[34578]{1}\d{9}$/',$email_or_phone)){ //手机注册
                $count=$this->phone_yzm($email_or_phone, $action_id);
            }  else  if(preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $email_or_phone)){//邮箱
                $count=$this->email_yzm($email_or_phone, $cur_language_id);
            }
			if(isset($count)){
				die(json_encode(array('success'=>1)));
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
			}
		}
	}
    
    /**
	 * 发送验证码
	 */
	public function add_captcha_zj(){
		if($this->input->is_ajax_request()){
			$email_or_phone = trim($this->input->post('email_or_phone'));
			$reg_type = $this->input->post('reg_type') ? $this->input->post('reg_type') : 0;
			$action_id = $this->input->post('action_id') ? $this->input->post('action_id') : 1;

			if(!$email_or_phone){
				die(json_encode(array('success'=>0,'msg'=>lang('regi_errormsg_mail'))));
			}
			$cur_language_id = get_cookie('curLan_id', true);
			if (empty($cur_language_id)) {
				$cur_language_id = 1;
			}
			if(is_numeric($email_or_phone) && preg_match('/^1[34578]{1}\d{9}$/',$email_or_phone)){ //手机注册
				$result = $this->phone_yzm_v2($email_or_phone, $action_id);
			}  else  if(preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $email_or_phone)){//邮箱
				$result = $this->email_yzm($email_or_phone, $cur_language_id);
			}
			if($result['error'] == false){
				die(json_encode(array('success'=>1,'error'=>false,'message'=>'success')));
			}else{
                if($result['sub_code'] == "isv.BUSINESS_LIMIT_CONTROL") {
                    $message = lang("send_code_frequency");
                } elseif($result['sub_code'] == 'isv.MOBILE_NUMBER_ILLEGAL') { //手機號碼格式錯誤
                    $message = lang('mobile_format_error');
                } else {
                    $message = lang('mobile_system_update');
                }
				die(json_encode(array('success'=>0,'msg'=>lang('try_again'),'error'=>true,'message'=>$message)));
			}
		}
	}
     
     /**
     * 重置绑定邮箱/仅重置绑定邮箱的时候用
     */
    public function bindEmail(){
		if($this->input->is_ajax_request()){
			$email_or_phone = $this->input->post('email_or_phone');
                        $regi_emails_or_phone = $this->input->post('regi_emails_or_phone');
			$reg_type = $this->input->post('reg_type') ? $this->input->post('reg_type') : 0;
			$action_id = $this->input->post('action_id') ? $this->input->post('action_id') : 1;
                        if($email_or_phone !==$regi_emails_or_phone){ //两次邮箱输入不一致
                            die(json_encode(array('success'=>0,'msg'=>lang('regi_email2'))));
                        }
                        //验证邮箱格式
			if(!$email_or_phone || !preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $email_or_phone)){
				die(json_encode(array('success'=>0,'msg'=>lang('regi_errormsg_mail'))));
			}

			if($action_id == 4){//绑定操作，检查手机号，或者邮箱是否已绑定过
				$this->load->model('tb_users');
				$is_binding = $this->tb_users->is_check_binding($email_or_phone);
				if($is_binding){
					die(json_encode(array('success'=>0,'msg'=>lang('is_binding_info'))));//邮箱已经被注册
				}
			}
			$cur_language_id = get_cookie('curLan_id', true);
			if (empty($cur_language_id)) {
				$cur_language_id = 1;
			}
                        //发送邮箱验证码
                        $count=$this->email_yzm($email_or_phone, $cur_language_id);
			if(isset($count)){
				die(json_encode(array('success'=>1)));
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
			}
		}
	}

}



