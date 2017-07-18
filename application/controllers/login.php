<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 系统登录 控制器
 * @author TPS
 */
class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 显示界面
     * @see MY_Controller::index()
     */
    public function index() {
        
        $fp = $this->input->get('fp');
        $this->_viewData['title']=lang('login').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');

        $this->_viewData['canonical']=base_url();
        if (!empty($fp)) {
            $this->_viewData['fp']=$fp;
        }

        parent::index('mall/','login_register');
    }

    /**
     * 登陆系统
     * @author leon 修改  JacksonZheng 
     * @date 2017-06-13
     */
    public function submit() {
        $requestData = $this->input->post();
        $this->load->model('tb_users');

        $config_arr = config_item("login_captcha");
        if($config_arr['switch'] == 1) {  //为1打开登陆图片验证码
        
            //获取redis保存的验证码  
            $sid = get_cookie('key_captcha');   //redis保存key
            if(strlen($sid) == 32 ) {
              $code = $this->tb_users->getImgCaptchaCode($sid); 
            } else {
              $code = 0;   //cookie保存验证码  
            }
         
            if($code ==  -1) {   //Redis获取验证码过期 
                $requestData['redis_code'] = -1;

            } else if($code == 0) {   //redis保存失败 改为cookie保存
                $cookie_code  = get_cookie('img_captcha');
                if(strlen($cookie_code) == 32) {
                  $requestData['cookie_code'] = $cookie_code ; 
                } else {
                  $requestData['cookie_code'] = -1;   //用户可能自行改动cookie值
                }
                
            } else {     //Redis获取验证码成功
                $requestData['redis_code'] = $code;    
            }
        
        }


        $res = $this->tb_users->checkUserLogin($requestData);

        if ($res['success']) {
            $publicDomain = get_public_domain();
            $userInfoUnSeri = array('uid' => $res['userInfo']['id'], 'sign' => sha1($res['userInfo']['token']));
            if (isset($requestData['login_auto'])) {
                set_cookie("userInfo", serialize($userInfoUnSeri), 3600 * 24 * 7, $publicDomain);
            } else {
                set_cookie("userInfo", serialize($userInfoUnSeri), 0, $publicDomain);
            }
            //解除redis登录次数限制
            $key = "mall:login:submit:error_counts:".$res['userInfo']['id'];
            $this->tb_users->redis_del($key);

            /** 记录登录ip和地理位置信息。*/
            $this->tb_users->recordMemberLoginInfo($res['userInfo']['id']);

            /** 如果是刚登陆进来，做个标记，为超重要弹出框做判断 */
            set_cookie("just_login", TRUE, 0, $publicDomain);
            set_cookie("img_captcha", "", time()-100, $publicDomain);
            set_cookie("key_captcha", "", time()-100, $publicDomain);
            $url = $this->get_login_wohao_url_redirect($res['userInfo']['id']);
            set_cookie("login_wohao_url",$url,0);
            
        }
        echo json_encode($res);
        exit;
    }

    public function captcha() {
        require APPPATH . 'third_party/captcha.class.php';
        $image = new Captcha();
        $image->doimg();
        set_cookie("captcha", $image->getCode, 0, get_public_domain());
    }
    
    /*
     * @author JacksonZheng
     * @date 2017-06-05
     */
    public function output_captcha() {
        ob_clean(); 
        $this->load->helper('cookie'); 
        $this->load->model('tb_users');
        $sid = $this->session->userdata('session_id');
        require APPPATH . 'third_party/img_captcha.class.php';
        $image = new ImgCaptcha();
        $image->doimg();
        $code = $image->getCode();
        $ret = $this->tb_users->saveImgCaptchaCode($sid,$code);  //保存图片验证码到Redis
        
        if($ret === true ) {   //保存Redis成功
            set_cookie("key_captcha", $sid, 0, get_public_domain());
            
        } else {  //保存Redis失败, 改保存到cookie
            set_cookie("img_captcha", md5(strtolower($code)."yun#138"), 0, get_public_domain());
        }

    }
    
    /**
     * 查看用户后台
     *
     * 此方法 没有使用
     */
    public function seeUserBack(){
        $user_id = $_POST['uid'];
        $this->load->model('m_user');
        $res=$this->checkSeeUserBack($user_id);
        if($res['success']){
            $publicDomain = get_public_domain();
            $userInfoUnSeri = array('uid' => $res['userInfo']['id'],'sign' => sha1($res['userInfo']['token']),'readOnly'=>true);
            if (isset($requestData['login_auto'])) {
                set_cookie("userInfo", serialize($userInfoUnSeri), 3600 * 24 * 7, $publicDomain);
            } else {
               set_cookie("userInfo", serialize($userInfoUnSeri), 0, $publicDomain);
            }
        }
        echo json_encode($res);
        exit;
    }
    public function checkSeeUserBack($user_id){
        $userInfo = $this->m_user->getUserByIdOrEmail($user_id);
        if ($userInfo['status'] == '0') {
            return array('success' => FALSE, 'msg' => lang('this_login_status_error'));
        }
        if ($userInfo['status'] == '3') {
            return array('success' => FALSE, 'msg' =>lang('this_account_disabled'));
        }
        if ($userInfo['status'] == '4') {
            return array('success' => FALSE, 'msg' =>lang('company_account_cannot_login'));
        }
        if ($userInfo['status'] == '5') {
            return array('success' => FALSE, 'msg' =>lang('this_account_disabled'));
        }
        return array('success' => TRUE, 'userInfo' => $userInfo);
    }

    /**
     * 查看用户后台
     *
     * 此方法 没有使用
     */
    public function seeUserBackNew(){
        $user_id = $_POST['uid'];
        $this->load->model('m_user');
        $res=$this->checkSeeUserBackNew($user_id);
        if($res['success']){
            $publicDomain = get_public_domain();
            $userInfoUnSeri = array('uid' => $res['userInfo']['id'],'sign' => sha1($res['userInfo']['token']),'readOnly'=>true);
            if (isset($requestData['login_auto'])) {
                set_cookie("userInfo", serialize($userInfoUnSeri), 3600 * 24 * 7, $publicDomain);
            } else {
                set_cookie("userInfo", serialize($userInfoUnSeri), 0, $publicDomain);
            }
        }
        echo json_encode($res);
        exit;
    }
    public function checkSeeUserBackNew($user_id){
        $userInfo = $this->m_user->getUserByIdOrEmail($user_id);
        if ($userInfo['status'] == '0') {
            return array('success' => FALSE, 'msg' => lang('this_login_status_error'));
        }
        
//        if ($userInfo['status'] == '4') {
//            return array('success' => FALSE, 'msg' =>lang('company_account_cannot_login'));
//        }
        if ($userInfo['status'] == '5') {
            return array('success' => FALSE, 'msg' =>lang('this_account_disabled'));
        }
        return array('success' => TRUE, 'userInfo' => $userInfo);
    }
    
    /**
     * 注销
     */
    public function logout() {
        delete_cookie("userInfo",  get_public_domain());
        delete_cookie("unread_count",  get_public_domain());
        set_cookie("logout_wohao_url",config_item('wohao_api')['logout'],0);
        header("Location: " . base_url());
    }

    /**
     * @author brady.wang
     * @desc 提供给沃好的注销
     * @url
     */
    public function logoutApiWoHao()
    {
            header("Access-Control-Allow-Origin:*");
            require_once APPPATH.'third_party/AES/AES.php';
            $url = $_SERVER['REQUEST_URI'];
            $aes = new aes();
            $encrypt_id = trim($_GET['uid']);
            $key = '4svp+!A138FS+d_O';
            $uid  = $aes->aes256ecbDecrypt($encrypt_id,'', $key);
            if(!$uid) {
                $uid = '';
            }
            delete_cookie("userInfo",  get_public_domain());
            delete_cookie("unread_count",  get_public_domain());
            $url = $_SERVER['REQUEST_URI'];
            $this->load->model("tb_logs_wohao_api");
            $this->tb_logs_wohao_api->create_log($uid,$url);
            echo $_GET['jsoncallback'] . "(".json_encode(array('success'=>true,'msg'=>'注销成功')).")";
            exit;
    }

    /**
     * @author brady.wang
     * @desc 提供给沃好的，同步登陆
     */
    public function loginApiWoHao()
    {
        header("Access-Control-Allow-Origin:*");
        require_once APPPATH.'third_party/AES/AES.php';
        $url = $_SERVER['REQUEST_URI'];
        $aes = new aes();
        $encrypt_id = trim($_GET['uid']);
        $key = '4svp+!A138FS+d_O';
        $uid  = $aes->aes256ecbDecrypt($encrypt_id,'', $key);
        $this->load->model("tb_users");
        $res = $this->tb_users->get_user_info($uid);
        $this->load->model("tb_logs_wohao_api");
        if (!empty($res)) {
            $publicDomain = get_public_domain();
            $userInfoUnSeri = array('uid' => $res['id'], 'sign' => sha1($res['token']));
            if (isset($requestData['login_auto'])) {
                set_cookie("userInfo", serialize($userInfoUnSeri), 3600 * 24 * 7, $publicDomain);
            } else {
                set_cookie("userInfo", serialize($userInfoUnSeri), 0, $publicDomain);
            }
            //解除redis登录次数限制
            $key = "mall:login:submit:error_counts:".$res['id'];
            $this->tb_users->redis_del($key);

            /** 记录登录ip和地理位置信息。*/
            $this->tb_users->recordMemberLoginInfo($res['id']);
            $this->tb_logs_wohao_api->create_log($uid,$url);

            /** 如果是刚登陆进来，做个标记，为超重要弹出框做判断 */
            set_cookie("just_login", TRUE, 0, $publicDomain);
            set_cookie("img_captcha", "", time()-100, $publicDomain);
            set_cookie("key_captcha", "", time()-100, $publicDomain);
            echo $_GET['jsoncallback'] . "(".json_encode(array('success'=>true,"msg"=>'登陆成功','userInfo'=>$res)).")";
            exit;
        } else {
            $res = ['success'=>false,"msg"=>'不存在的uid'];
            echo $_GET['jsoncallback'] . "(".json_encode($res).")";
            exit;
        }
    }

    /**
     * @author brady.wang
     * @desc 前端登陆的时候，拿到返回的uid，再次请求该地址，得到url
     */
    public function get_login_wohao_url()
    {
        header("Access-Control-Allow-Origin:*");
        $id = trim($this->input->post("uid")) ;
        require_once APPPATH.'third_party/AES/AES.php';
        $aes = new aes();
        $key = '4svp+!A138FS+d_O';
        $data = $aes->aes256ecbEncrypt($id, '', $key);
        $data = urlencode($data);
        $url = config_item("wohao_api")['login']."?uid=".$data;
        $output = array('success'=>true,"msg"=>$url);
        echo json_encode($output);
    }

    public function get_login_wohao_url_redirect($id)
    {
        require_once APPPATH.'third_party/AES/AES.php';
        $aes = new aes();
        $key = '4svp+!A138FS+d_O';
        $data = $aes->aes256ecbEncrypt($id, '', $key);
        $data = urlencode($data);
        $url = config_item("wohao_api")['login']."?uid=".$data;
        return $url;
    }

}
