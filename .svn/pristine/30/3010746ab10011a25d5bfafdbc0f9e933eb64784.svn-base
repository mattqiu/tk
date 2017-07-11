<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Sign_in extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index() {
        parent::index('admin/','','header_1','footer_1');
    }
    
    public function submit(){
        
        $postData = $this->input->post();
       // $username = $postData['user_name'];
       // $pwd = $postData['pwd'];
       // $captcha = $postData["captcha_code"];//验证码
        $config_arr = config_item("admin_login_captcha");
        if($config_arr['switch'] == 1) {  //为1打开登陆图片验证码
            //获取redis保存的验证码  
            $sid = get_cookie('admin_key_captcha');   //redis保存key
            if(strlen($sid) == 32 ) {
              $code = $this->AdminGetCaptchaCode($sid); 
            } else {
              $code = 0;   //cookie保存验证码  
            }
           // fout($code);exit;
            if($code ==  -1) {   //Redis获取验证码过期 
                $requestData['redis_code'] = -1;

            } else if($code == 0) {   //redis保存失败 改为cookie保存
                $cookie_code  = get_cookie('admin_img_captcha');
                if(strlen($cookie_code) == 32) {
                  $postData['cookie_code'] = $cookie_code ; 
                } else {
                  $postData['cookie_code'] = -1;   //用户可能自行改动cookie值
                }
                
            } else {     //Redis获取验证码成功
                $postData['redis_code'] = $code;    
            }
        
        } 
        //fout($postData);exit;
        $success = $this->m_admin_user->login($postData);
        if($success["success"]){
            $data = array('jumpUrl'=>  base_url('admin'));
        }
        echo json_encode(array('success'=>$success["success"],'msg'=>isset($success["code_msg"])?$success["code_msg"]:'','data'=>isset($data)?$data:array()));
    }
    
    /**
     * 退出
     */
    public function logout(){
        delete_cookie("adminUserInfo",  get_public_domain());
		$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_logout','admin_users',$this->_adminInfo['id'],
			'',get_real_ip(),'');
        header("Location: " . base_url('admin/sign_in'));
    }

	/** 改变管理员后台语言 */
	public function changeAdminLanguage() {
		$lan = $this->input->post('lan');
		$lan_id = $this->input->post('lan_id');
		$lan_name = $this->input->post('lan_name');

		$public_domain=get_public_domain();

		delete_cookie('admin_curLan',  $public_domain);
		delete_cookie('admin_curLan_id',  $public_domain);
		delete_cookie('admin_curLan_name',  $public_domain);

		set_cookie('admin_curLan', $lan, 3600 * 24 * 365, get_public_domain(),'/');
		set_cookie('admin_curLan_id', $lan_id, 3600 * 24 * 365, get_public_domain(),'/');
		set_cookie('admin_curLan_name', $lan_name, 3600 * 24 * 365, get_public_domain(),'/');

		echo json_encode(array('success'=>  TRUE));
		exit;
	}
        
        
        /**
         * 保存验证码到redis
         * @param type $sid
         * @param type $code
         * @return type
         */
        private  function AdminSaveCaptchaCode($sid,$code) {
            $this->load->model('tb_users');
            $redis_key = "admin_login_img_captcha:".$sid;

            $res = $this->tb_users->redis_set($redis_key,$code,600);

            return $res;     //保存成功为true  其他为非真
        }
        /**
         * redis取验证码
         * @param type $sid
         * @return type
         */
        private function AdminGetCaptchaCode($sid) {
            $this->load->model('tb_users');
            $redis_key = "admin_login_img_captcha:".$sid;
            $tmp = $this->tb_users->redis_get($redis_key);
            if($tmp)
            {
                return $tmp;

            }else{
                return -1;
            }
        }
        public function output_captcha() {
            ob_clean();
            $this->load->helper('cookie');
            $sid = $this->session->userdata('session_id');
            require APPPATH . 'third_party/img_captcha.class.php';
            $image = new ImgCaptcha();
            $image->doimg();
            $code = $image->getCode();
            $ret = $this->AdminSaveCaptchaCode($sid,$code);  //保存图片验证码到Redis

            if($ret === true ) {   //保存Redis成功
                set_cookie("admin_key_captcha", $sid, 0, get_public_domain());

            } else {  //保存Redis失败, 改保存到cookie
                set_cookie("admin_img_captcha", md5(strtolower($code)."yun#138"), 0, get_public_domain());
            }

        }


}