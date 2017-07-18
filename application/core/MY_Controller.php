<?php

/**
 * public controller.
 * @author Terry Lu
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class MY_Controller extends CI_Controller {

	protected $_viewData = array();
    protected $_curCurrency = 'USD'; //默认货币
    protected $_curCurrency_name = '美元'; //默认货币名
    protected $_curCurrency_flag = '$'; //默认货币符号
	protected $_curLanguage = 'english'; //默认语言包
	protected $_curLanguage_name = 'English'; //默认语言名称
	protected $_curLanguage_id = 1; //默认语言id
	protected $_curLocation_id = '840'; //默认区域id

	protected $_curControlName;
	protected $_subdirectory = '';
	protected $_userInfo = array();
	protected $_adminInfo = array();
        protected $is_mobile;

    protected $_lang_cur_arr=array(
        '840'=>array('curLan_id'=>'1','curLan_name'=>'English','curLan'=>'english','curCur_flag'=>'$','curCur_name'=>'美元','curCur'=>'USD'),
        '156'=>array('curLan_id'=>'2','curLan_name'=>'简体中文','curLan'=>'zh','curCur_flag'=>'¥','curCur_name'=>'人民币','curCur'=>'CNY'),
        '344'=>array('curLan_id'=>'3','curLan_name'=>'繁体中文','curLan'=>'hk','curCur_flag'=>'HK$','curCur_name'=>'港币','curCur'=>'HKD'),
        '410'=>array('curLan_id'=>'4','curLan_name'=>'한국어','curLan'=>'kr','curCur_flag'=>'₩','curCur_name'=>'韩币','curCur'=>'KRW'),
        '000'=>array('curLan_id'=>'1','curLan_name'=>'English','curLan'=>'english','curCur_flag'=>'$','curCur_name'=>'美元','curCur'=>'USD'),
    );

//	protected $_lang_arr=array( //语言和货币的默认对应关系,用于url中设置语言
//	        'english'=>array('lang_id'=>1,'name'=>'English','code'=>'USD'),
//	        'zh'=>array('lang_id'=>2,'name'=>'简体中文','code'=>'CNY'),
//	        'hk'=>array('lang_id'=>3,'name'=>'繁体中文','code'=>'HKD'),
//	        'kr'=>array('lang_id'=>4,'name'=>'한국어','code'=>'KRW'),
//	);

	/**
	 * Constructor
	 */
	public function __construct() {
//	    $this->xhprof_start();
        $this->_start_execute_time_ = microtime(true);
		parent::__construct();

		$this->load->model('m_global');
		$this->load->model('m_currency');
		$this->load->model('m_log');
		$this->load->model('m_debug');
		$this->load->model('m_goods');
		$this->load->model('m_trade');
		$this->load->library('session');
		$this->load->library('validator');


        $cur_location = self::__setSiteCookie();
		self::__initLanguage();
		self::__appendHeaderViewData();
		self::__appendUserInfo();
		self::__checkLogin();
        self::__setSiteValue($cur_location);
		self::__checkSubdomain();
		self::__getMallKeyword($cur_location);
		self::__getMallAds($cur_location, true);
		self::get_config_right();
        $this->sphinx_set();
	}
    /**
     * SPHINX设置
     * @author baker
     * @date 2017-5-22
     */

    public function sphinx_set() {
        $arr = $this->config->item('sphinx_search');
        $this->_viewData['sphinx_search_url'] = empty($arr['url']) ? 'search/index' : $arr['url'];
    }


    /**
     * xhprof 监控
     * @param $name，监控的函数名
     * @param $per ,监控比例，1到n代表n分之1
     */
	public function xhprof($name,$per=10)
    {
        echo($name.",".__FILE__.",".__LINE__."<BR>");
        $xhprof_enable = false;
        if(mt_rand(1,$per)==1){ //这里设置监控的比例
            $this->xhprof_start();
            $xhprof_enable = true;
        }
        echo($name.",".__FILE__.",".__LINE__."<BR>");
        //这里写上你要监控的函数
        eval($name);
        echo($name.",".__FILE__.",".__LINE__."<BR>");
        if($xhprof_enable ){
            $this->xhprof_finish($name);
        }
        echo($name.",".__FILE__.",".__LINE__."<BR>");
    }

    public function xhprof_start()
    {
        if(function_exists("xhprof_enable"))
        {
            xhprof_enable();
        }
        else
        {
            return;
        }
    }

    public function xhprof_finish($name)
    {
        if(function_exists("xhprof_disable")){
            $xhprof_data = xhprof_disable();
            $XHPROF_ROOT = realpath(dirname(__FILE__) .'/..');
            //$XHPROF_ROOT = "/var/www/xhprof";
            include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
            include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
//            exit($XHPROF_ROOT . "/../xhprof_lib/utils/xhprof_lib.php".",".__FILE__.",".__LINE__."<BR>");
            // save raw data for this profiler run using default
            // implementation of iXHProfRuns.
            $xhprof_runs = new XHProfRuns_Default();
            // save the run under a namespace "xhprof_foo"
            $run_id = $xhprof_runs->save_run($xhprof_data, "$name");
        }
    }

    /** 设置初始化的语言、币种cookie */
    private function __setSiteCookie(){

        $public_domain= get_public_domain();

        /* 初始化区域 */
        $is_location=get_cookie('curLoc',true);

        $show_location_tips=empty($is_location) ? true : false;

        //语言、货币的初始化信息
        if (empty($is_location) && !$this->input->is_cli_request()) {
            $cur_location = self::__ipToCountry(); //首先通过ip来定位用户所在国家，获取对应国家的语言、货币
            //国家ID对应的语言和货币
            $arr = isset($this->_lang_cur_arr[$cur_location])?$this->_lang_cur_arr[$cur_location]:$this->_lang_cur_arr['000'];

        } else {

            $cur_location = $is_location ? $is_location : '000';
            $arr = $this->_lang_cur_arr[$cur_location];
        }

        if(!get_cookie('admin_curLan',true) && $this->uri->segment(1)==='admin'){
            set_cookie('admin_curLan', $this->_curLanguage, 3600 * 24 * 365, $public_domain, '/');
            set_cookie('admin_curLan_id',  $this->_curLanguage_id, 3600 * 24 * 365, $public_domain, '/');
            set_cookie('admin_curLan_name', $this->_curLanguage_name, 3600 * 24 * 365, $public_domain, '/');
        }

        if(empty($is_location)){
            set_cookie('curLoc', $cur_location, 3600 * 24 * 365,$public_domain,'/');

            set_cookie('curLan', $arr['curLan'], 3600 * 24 * 365, $public_domain, '/');
            set_cookie('curLan_id',  $arr['curLan_id'], 3600 * 24 * 365, $public_domain, '/');
            set_cookie('curLan_name', $arr['curLan_name'], 3600 * 24 * 365, $public_domain, '/');

            set_cookie('curCur', $arr['curCur'], 3600 * 24 * 365,$public_domain,'/');
            set_cookie('curCur_name', $arr['curCur_name'], 3600 * 24 * 365,$public_domain,'/');
            set_cookie('curCur_flag', $arr['curCur_flag'], 3600 * 24 * 365,$public_domain,'/');

        }else if(get_cookie('curCur_manual',true)){ /** 用户没有手动选择货币，随区域改变，否则就用cookie的货币 */

            $arr['curCur'] = get_cookie('curCur',true);
            $arr['curCur_name'] = get_cookie('curCur_name',true);
            $arr['curCur_flag'] = get_cookie('curCur_flag',true);
        }

        $this->_viewData['curLocation_id']=$cur_location;

        $this->_viewData['curLan'] = $this->_viewData['curLanguage'] = $arr['curLan'];
        $this->_viewData['curLan_id'] = $arr['curLan_id'];
        $this->_viewData['curLan_name'] = $arr['curLan_name'];

        $this->_viewData['curCur']=$arr['curCur'];
        $this->_viewData['curCur_name']=$arr['curCur_name'];
        $this->_viewData['curCur_flag']=$arr['curCur_flag'];

        $this->session->set_userdata('location_id',$cur_location); //设置区域session
        $this->session->set_userdata('language_id',$arr['curLan_id']); //设置语种session

        $this->_curLanguage = $arr['curLan']; //默认语言包
        $this->_curLanguage_name = $arr['curCur_name']; //默认语言名称
        $this->_curLanguage_id = $arr['curLan_id']; //默认语言id
        $this->_curCurrency = $arr['curCur']; //后台需要使用到当前币种 by john
        $this->_curControlName = strtolower(get_class($this));

        $is_change = get_cookie('changeCurLoc',true);
        if($show_location_tips || $is_change === '1') { //需要提示用户区域已经切换，则获取当前地区名称
            $this->load->model("tb_mall_goods_sale_country");
            //            var_dump($cur_location);var_dump($this->_viewData['curLoc_name']);echo("<br>");exit(__FILE__.",".__LINE__);
            $sale_country = $this->tb_mall_goods_sale_country->get_sale_country($cur_location);
            $this->_viewData['curLoc_name'] = $sale_country[0]['name_'.$arr['curLan']];

            delete_cookie('changeCurLoc',$public_domain);
        }

        return $cur_location;
    }


    /** 赋值变量操作 */
    private function __setSiteValue($cur_location){

        if($this->uri->segment(1)!=='admin'){
            /* 用户商品收藏夹数量 */
            $this->_viewData['wish_count']=empty($this->_userInfo) ? 0 : $this->m_global->get_wish_count($this->_userInfo['id'],$this->_curLanguage_id);

            /* 当前汇率 */
            $this->_viewData['cur_rate']=$this->m_global->get_rate($this->_viewData['curCur']);
            $this->session->set_userdata('cur_rate',$this->_viewData['cur_rate']); //当前的汇率存入session 用于后台计算

            /* 货币 */
            $this->_viewData['currency_all'] = $this->m_global->getCurrencyList();

            /* 分类  */
            $this->_viewData['category_all'] =$this->m_goods->get_all_category($this->session->userdata('language_id'));

            /* 登录标识  */
            $this->_viewData['is_login'] =empty($this->_userInfo) ? 0 : 1;

            /* 获取底部文章  */
            $this->_viewData['artical']=$this->m_global->get_artical();

            /* 服务器host */
            $this->_viewData['web_host']=base_url();

            /* 导航功能块  */
            $this->_viewData['nav_list'] = $this->config->item('nav_layer')[$cur_location];

            /* 店铺 id */
            $this->_viewData['store_id'] = $this->m_global->getStoreId(isset($this->_userInfo['id']) ? $this->_userInfo['id'] : 0);

            /** 店铺信息 ：等级 */
            $this->_viewData['store_info'] = $this->m_global->getStoreInfo($this->_viewData['store_id']);

            /* 是否加载分享js */
            $this->_viewData['load_share_js'] = $this->config->item('load_share_js');

            /* 购物车物品数 */
            $this->load->model("tb_user_cart");
            $uid = $this->get_user_id();
            $cart_item_num = @$this->tb_user_cart->get_cart_item_num($this->_viewData['store_id'],
                $this->_viewData['curLocation_id'],$uid);
            $this->_viewData['cart_item_num'] = $cart_item_num;
            $this->_viewData['cart_item_figure'] = strlen(strval($cart_item_num));

            /* 所有销售区域  */
            //            $this->_viewData['sale_country'] = $this->m_goods->get_sale_country();
            $this->load->model("tb_mall_goods_sale_country");
            $this->_viewData['sale_country'] = $this->tb_mall_goods_sale_country->get_sale_country();
        }

        /* 图片资源服务器  */
        $this->_viewData['img_host']= $this->config->item('img_server_url').'/';
        /* 语种 */
        $this->_viewData['language_all'] = $this->m_global->getLangList();

		/** 切换语言不跳转首页，刷新本页面 */
		if(in_array($this->uri->segment(1),array('enroll','login','register'))){
			$this->_viewData['jump'] = TRUE;
		}

    }

	/*验证子域名。*/
    private function __checkSubdomain(){
        if($this->_curControlName!='cron'){
            if(!$this->m_global->checkSubdomain(get_domain_prefix())){
                redirect('http://mall.'.get_public_domain_port());
            }
        }
    }

	/*管理员后台禁止子域名。*/
	private function __subdomainForbiddenInAdmin(){
		if(get_domain_prefix()!=='mall' && get_domain_prefix()!=='pay'){
			redirect('http://mall.'.get_public_domain_port().'/admin');
		}
	}

	/*用户后台自动转向用户子域名。*/
	private function __autoRedirectToMemDomain(){
		if($this->_userInfo['member_url_prefix'] && get_domain_prefix() != 'cs' && get_domain_prefix() != 'kr'){
			if(get_domain_prefix()!==$this->_userInfo['member_url_prefix']){
				 redirect('http://'.$this->_userInfo['member_url_prefix'].'.'.get_public_domain_port().filter_input(INPUT_SERVER, 'REQUEST_URI'));
			}
		}/*else{
            if(get_domain_prefix() !== 'www'){
                redirect('http://www.'.get_public_domain_port().filter_input(INPUT_SERVER, 'REQUEST_URI'));
            }
        }*/
	}

	/**
	 * Init the language.
	 */
	private function __initLanguage() {
		$supportLanguage = config_item('supportLanguage');
		$defaultLang =$this->_curLanguage; //config_item('language');
		if($this->uri->segment(1)!=='admin'){
			$curLan = filter_input(INPUT_COOKIE, 'curLan');
		}else{
			$curLan = filter_input(INPUT_COOKIE, 'admin_curLan');
		    $curLan = !$curLan ? 'english' : $curLan;
        }
		$this->_curLanguage = ($curLan && in_array($curLan, $supportLanguage) )?$curLan:$defaultLang;
		$this->config->set_item('language', $this->_curLanguage);
		$this->lang->load('base');
		$this->lang->load('new_base');
		$this->lang->load('ucenter_base');
		$this->lang->load('admin_base');
		$this->lang->load('mall');
		$this->lang->load('level');
		//产地
		$this->load->model('m_goods_origin');
		$origin_array = $this->m_goods_origin->get_origin($this->_curLanguage);
		$this->_viewData['origin_array'] = empty($origin_array) ? array() : $origin_array;
		$this->config->set_item('country_flag_path', $this->config->item('img_server_url').'/Tpsmall/Countryflag/');
	}

	private function __appendHeaderViewData() {
		$this->_viewData['curLanguage'] = $this->_curLanguage;
		$this->_viewData['curControlName'] = $this->_curControlName;
		$this->_viewData['type'] = $this->input->get('type');
	}

	private function __appendUserInfo() {
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            if(stripos($_SERVER['HTTP_USER_AGENT'],"android")!=false||stripos($_SERVER['HTTP_USER_AGENT'],"ios")!=false||stripos($_SERVER['HTTP_USER_AGENT'],"iphone")!=false||stripos($_SERVER['HTTP_USER_AGENT'],"wp")!=false){
                $this->is_mobile='mobile';
            }else{
                $this->is_mobile='mall';
            }
        }
		$userInfoSeri = filter_input(INPUT_COOKIE, 'userInfo');
		//使用uploadify上传多文件，自带userInfo
		if($this->uri->segment(2)=='add_tickets' && $this->uri->segment(3)=='my_upload_attach'){
			$userInfoSeri = isset($_POST['userInfo']) ? $_POST['userInfo'] : '';
		}
		if($userInfoSeri){
			$cookieUnSeri = unserialize($userInfoSeri);
			if (isset($cookieUnSeri['uid']) && isset($cookieUnSeri['sign'])) {
				$this->load->model('m_user');
				$userInfo = current($this->m_user->getInfo($cookieUnSeri['uid']));
				if (isset($cookieUnSeri['readOnly']) && $cookieUnSeri['readOnly']) {
					$allow_login_config = config_item('allow_login_status_admin');
				} else {
					$allow_login_config = config_item('allow_login_status');
				}
				if (sha1($userInfo['token']) == $cookieUnSeri['sign'] && in_array($userInfo['status'],$allow_login_config)) {
					$this->_userInfo = $userInfo;
					$this->_viewData['is_store'] = $this->m_global->isStore($userInfo['parent_id']);
				}
			}
		}
       
		$this->_viewData['userInfo'] = $this->_userInfo;
	}

    private function ticowonglog($msg)
    {
        if(isset($this->_userInfo)) {
            if (isset($this->_userInfo['id'])) {
                if($this->_userInfo['id'] == '1381782417'
                    or $this->_userInfo['id'] == '1381205849')
                $this->m_debug->log($msg);
                return;
            }
        }
    }

	private function __checkLogin(){
		if($this->uri->segment(1)=='ucenter'){

			self::visitor();
			//paypal or alipy 的 ipn 异步同步通知不用登陆检查 by john
			if( !$this->_viewData['userInfo'] && $this->router->fetch_method() != 'do_notify'){
				$redirect = $this->uri->uri_string();
				if($_SERVER['QUERY_STRING']){
					$redirect .= '?' . $_SERVER['QUERY_STRING'];
				}
                /*跳转到用户登陆页面，指定Login后跳转的URL*/
				redirect(base_url('login') . '?redirect='.$redirect);
			}
			/** 检测未读消息 */
			$unread_count = get_cookie('unread_count',true);
			if($unread_count === FALSE ){
				// m by brady.wang 修改获取消息方式
				$this->load->model('tb_bulletin_board');
				$count = $this->tb_bulletin_board->get_unread_counts($this->_userInfo['id'],$this->_viewData['curLan'],$this->_userInfo['parent_id']);
				//$count = $this->db->from('bulletin_unread')->where('uid',$this->_userInfo['id'])->count_all_results();

				set_cookie('unread_count',$count,0,get_public_domain());
				$unread_count = $count;
			}
			if($unread_count > 0){
				$this->_viewData['unread_count'] = $unread_count;
			}
			/*检测用户需要补单的数量*/
			$this->load->model('tb_withdraw_task');
			$this->_viewData['order_repair_count'] = $this->tb_withdraw_task->getCommOrderRepariNumByUid($this->_userInfo['id']);

			$this->__autoRedirectToMemDomain();
			$this->_subdirectory = 'ucenter/';

		}elseif($this->uri->segment(1)=='admin'){

			$this->__subdomainForbiddenInAdmin();
			$adminUserInfoSeri = filter_input(INPUT_COOKIE, 'adminUserInfo');
			//使用uploadify上传多文件，自带adminUserInfo
			if(($this->uri->segment(2)=='my_tickets'|| $this->uri->segment(2)=='all_tickets') && $this->uri->segment(3)=='my_upload_attach'){
				$adminUserInfoSeri = isset($_POST['adminUserInfo']) ? $_POST['adminUserInfo'] : '';
			}
			if ($adminUserInfoSeri) {
				$cookieAdminUnSeri = unserialize($adminUserInfoSeri);
				if (isset($cookieAdminUnSeri['id']) && isset($cookieAdminUnSeri['sign'])) {
					$this->load->model('m_admin_user');
					$userInfo = $this->m_admin_user->getInfo($cookieAdminUnSeri['id']);
					$sign = $this->m_admin_user->createCookieSign($userInfo['token']);
					if ($sign == $cookieAdminUnSeri['sign'] && $userInfo['status']) {
						$this->_adminInfo = $userInfo;
						$this->_viewData['adminInfo'] = $this->_adminInfo;
					}
				}
			}

			if(!$this->_adminInfo && $this->uri->segment(2)!=='sign_in' && $this->uri->segment(2)!=='register'){
				redirect(base_url('admin/sign_in'));
			}
		}elseif($this->uri->segment(1)=='supplier'){ /* 供应商模块  */
			$this->__subdomainForbiddenInAdmin();

			$adminUserInfoSeri = filter_input(INPUT_COOKIE, 'adminSupplierInfo');

			$supplier_user='';
			if (!empty($adminUserInfoSeri)) {
			    $cookieAdminUnSeri = unserialize($adminUserInfoSeri);
			    $supplier_user=$cookieAdminUnSeri['supplier_user'];
			    $this->_viewData['username'] =$cookieAdminUnSeri['supplier_username'];
			}

			if (empty($supplier_user) && $this->uri->segment(2)!=='index') {
                redirect(base_url('supplier/index'));
			}

		}
                //-------------------------审核身份证客服权限控制
                $con=  isset($this->uri->segments[1])?$this->uri->segments[1]:'';
                if ($con=='admin') {//只在后台分组时生效
                    $role_id = isset($this->_adminInfo['role'])?$this->_adminInfo['role']:''; //当前用户角色ID
                    if($role_id==8){
                        if(!in_array(strtolower(get_class($this)),array('check_card','sign_in','reset_pwd'))){
                            redirect(base_url('admin/check_card'),'location');
                        }
                    }
                }
                //***********************************
	}

	private function __ipToCountry(){
		$clientIp = get_real_ip();

        $country_code = $this->m_global->get_country_code($clientIp);
        //国家code对应的国家ID
        $_country_code_loc = array('CN'=>'156','KR'=>'410','HK'=>'344','US'=>'840');
        $location_id = isset($_country_code_loc[$country_code])?$_country_code_loc[$country_code] : '000';
        return $location_id;
	}

    /**
     * 获取登陆用户的ID
     * @return int|mixed
     */
	protected function get_user_id()
    {
        $uid = 0;
        if(isset($this->_userInfo))
        {
            if(isset($this->_userInfo['id'])){
                $uid = $this->_userInfo['id'];
            }
        }
        return $uid;
    }

	protected function index($directory='',$page = '',$header='',$footer='') {

		if(config_item('website_closed')){
			$this->load->view('website_closed',$this->_viewData);
		}else {
			$this->_curControlName = $page ? $page : $this->_curControlName;
			$this->_subdirectory = $directory ? $directory : $this->_subdirectory;
			$header = $header ? $header : 'header';
			$footer = $footer ? $footer : 'footer';

			if( $this->_subdirectory == 'ucenter/' && (!$this->_userInfo['name'] || !$this->_userInfo['address'] ) && $this->m_global->isStore($this->_userInfo['parent_id']) ){
				/** 没有填写资料，始终弹出资料框 */
				$this->_curControlName = 'info_modal';
				$this->_viewData['modal'] = true;
			}else if ($this->_subdirectory == 'ucenter/' && !empty($this->_userInfo) && $this->_userInfo['status'] == '2'){
				/** 休眠用户在4月份登陆时会收到一个强制阅读的弹框　*/
				$this->load->model('m_user');
				if(!isset($this->_viewData['visitor']) && $this->m_user->get_month_fee_num($this->_userInfo['id']) > 1){
					$this->load->model('tb_users_april_plan');
					$row = $this->tb_users_april_plan->is_join_plan($this->_userInfo['id']);
					if(!$row){
							$this->_curControlName = 'korea_modal';
					}
				}
			}
			//获取工单的新消息数
			if(!empty($this->_userInfo['id']) && $this->_subdirectory == 'ucenter/'){
				$this->load->model('tb_admin_tickets');
				$new_msg_num = $this->tb_admin_tickets->mem_get_new_msg_count($this->_userInfo['id']);
				$this->_viewData['new_msg_num'] = $new_msg_num;
			}

            /**
             * 过滤菜单内容
             */
           $this->_viewData['category_all'] = @$this->edit_array($this->_viewData['category_all']);

			//当前界面的地址 --- leon
			$this->_viewData['tps_path_info'] = $this->_subdirectory . $this->_curControlName;

			$this->load->view($this->_subdirectory . $header, $this->_viewData);
			$this->load->view($this->_subdirectory . $this->_curControlName, $this->_viewData);
			$this->load->view($this->_subdirectory . $footer, $this->_viewData);
		}
		if(!empty($this->_start_execute_time_)){
		    $execute_time = floatval(microtime(true))-floatval($this->_start_execute_time_);
//            header("execute_time:$execute_time");
        }
	}

	private function visitor(){
		//使用uploadify上传多文件
		if($this->uri->segment(2)=='add_tickets' && $this->uri->segment(3)=='my_upload_attach'){
				$userInfo = isset($_POST['userInfo']) ? $_POST['userInfo'] : '';
				$cookie = unserialize($userInfo);
		}else{
				$cookie=unserialize($_COOKIE['userInfo']);
		}
		//$cookie=unserialize($_COOKIE['userInfo']);
		if(isset($cookie['readOnly']) && $cookie['readOnly']){
			$this->_viewData['visitor']='admin';
		}

	}


	protected function CheckPermission(){
		if($this->uri->segment(2)=='add_tickets' && $this->uri->segment(3)=='my_upload_attach'){
				$userInfo = isset($_POST['userInfo']) ? $_POST['userInfo'] : '';
				$cookie = unserialize($userInfo);
		}else{
				if(isset($_COOKIE['userInfo'])){
					$cookie=unserialize($_COOKIE['userInfo']);
				}
		}
		//$cookie=unserialize($_COOKIE['userInfo']);
		if(isset($cookie['readOnly']) && $cookie['readOnly']){
			if($this->input->is_ajax_request()){
				echo array('success'=>false);
			}
		}

	}
    
    /**
     * 整理 菜单数组（记录菜单中 有子菜单的 主菜单） --- leon
     * @param  [type] $menu_array 菜单数组
     * @return [type]             菜单数组
     */
    public function edit_array($menu_array){
		if (!empty($menu_array)) {
			foreach ($menu_array as $key => $value) {
				if($value['level'] == 0){
					foreach ($menu_array as $k => $v) {
						if($value['cate_id'] == $v['parent_id']){
							$menu_array[$key]['is_show']=1;//添加表示有子菜单的主菜单
						}
					}
				}
			}
		}
        return $menu_array;
    }

        //直接发送手机验证码
        public function phone_yzm($phone='',$action_id='',$msg="") {
            $code=$msg?$msg:generate_code(4);
            $action_id=$action_id?$action_id:1;
            include_once APPPATH .'/third_party/taobao/TopSdk.php';
            $phone_cfg = config_item('phone_cfg');
            $phone_cfg_info  = $phone_cfg[$action_id];
            $c = new TopClient;
            $c->appkey = "23362350";
            $c->secretKey = "7615d82fa94a199d8ad2c303f2e6c9e6";
            $c->format = "json";
            $req = new AlibabaAliqinFcSmsNumSendRequest;
            $req->setSmsType("normal");
            $req->setSmsParam(str_replace('@@@@',$code,$phone_cfg_info['param']));
            $req->setSmsFreeSignName($phone_cfg_info['signature']);
            $req->setRecNum("$phone");
            $req->setSmsTemplateCode($phone_cfg_info['template']);
            $resp = $c->execute($req);
            if(isset($resp->result->success)){
                
            $this->load->helper('cookie'); 
            $this->load->model('tb_users');
            $sid = $this->session->userdata('session_id');
            $ret = $this->tb_users->saveRegisterCaptcha($sid,$code);  //保存注册验证码到Redis
        
            if($ret === true ) {   //保存Redis成功
                set_cookie("register_captcha", $sid, 0, get_public_domain());

            } else {  //保存Redis失败, 改保存到cookie
                set_cookie("regcookie_captcha", md5(strtolower($code)."yun#138"), 1800, get_public_domain());
            }
                
                
                $newdata = array($phone=>array(
                    'email_or_phone'  => $phone,
                    'code'     => $code,
                    'expire_time' => time()+3600
                ));
                $this->session->set_userdata($newdata);
                return TRUE;
            }else{
                return FALSE;
            }
        }

		//发送短信验证码v2 m by brady.wang
		public function phone_yzm_v2($phone='',$action_id='',$msg="") {
			$code=$msg?$msg:generate_code(4);
			$action_id=$action_id?$action_id:1;
			include_once APPPATH .'/third_party/taobao/TopSdk.php';
			$phone_cfg = config_item('phone_cfg');
			$phone_cfg_info  = $phone_cfg[$action_id];
			$c = new TopClient;
			$c->appkey = "23362350";
			$c->secretKey = "7615d82fa94a199d8ad2c303f2e6c9e6";
			$c->format = "json";
			$req = new AlibabaAliqinFcSmsNumSendRequest;
			$req->setSmsType("normal");
			$req->setSmsParam(str_replace('@@@@',$code,$phone_cfg_info['param']));
			$req->setSmsFreeSignName($phone_cfg_info['signature']);
			$req->setRecNum("$phone");
			$req->setSmsTemplateCode($phone_cfg_info['template']);
			$resp = $c->execute($req);
			$res = ['error'=>true,'msg'=>''];
			if(isset($resp->result->success)){
				$newdata = array($phone=>array(
						'email_or_phone'  => $phone,
						'code'     => $code,
						'expire_time' => time()+3600
				));
				$this->session->set_userdata($newdata);
				$res = ['error'=>false,'msg'=>'success','message'=>'success'];
				return $res;
			}else{
				$res  = ['error'=>true,'msg'=>$resp->sub_msg,'message'=>$resp->sub_msg,'sub_code'=>$resp->sub_code];
				return $res;
			}
		}
        //直接发送邮件验证码
        public function email_yzm($email_or_phone='',$cur_language_id='') {
            $code = generate_code(4);
            $this->load->model('m_user');
            $this->load->helper('cookie'); 
            $this->load->model('tb_users');
            $sid = $this->session->userdata('session_id');
            $ret = $this->tb_users->saveRegisterCaptcha($sid,$code);  //保存注册验证码到Redis
        
            if($ret === true ) {   //保存Redis成功
                set_cookie("register_captcha", $sid, 0, get_public_domain());

            } else {  //保存Redis失败, 改保存到cookie
                set_cookie("regcookie_captcha", md5(strtolower($code)."yun#138"), 1800, get_public_domain());
            }
            
            $LanArr = $this->m_user->getMonthFeeMailLan();
            if($cur_language_id==2){
                    $lang = 'zh';
            }elseif($cur_language_id==3){
                    $lang = 'hk';
            }elseif($cur_language_id==4){
                    $lang = 'kr';
            }else{
                    $lang = 'english';
            }
            $real_lang = $LanArr[$lang];
            $data['email'] = $email_or_phone;
            $data['dear'] = $real_lang['dear'];
            $data['email_end'] = $real_lang['email_end'];
            $data['content'] = sprintf($real_lang['email_captcha_content'],$code);
            $content = $this->load->view('ucenter/public_email',$data,TRUE);
            /*********直接用框架邮件类发送*********/
            send_mail($email_or_phone,$real_lang['email_captcha_title'],$content);
            
            $newdata = array($email_or_phone=>array(
                'email_or_phone'  => $email_or_phone,
                'code'     => $code,
                'expire_time' => time()+3600
            ));
            $this->session->set_userdata($newdata);
            
            return TRUE;
        }

		//发送验证码v2 m brady.wang
		public function email_yzm_v2($email_or_phone='',$cur_language_id='') {
			$code = generate_code(4);
			$this->load->model('m_user');
			$LanArr = $this->m_user->getMonthFeeMailLan();
			if($cur_language_id==2){
				$lang = 'zh';
			}elseif($cur_language_id==3){
				$lang = 'hk';
			}elseif($cur_language_id==4){
				$lang = 'kr';
			}else{
				$lang = 'english';
			}
			$real_lang = $LanArr[$lang];
			$data['email'] = $email_or_phone;
			$data['dear'] = $real_lang['dear'];
			$data['email_end'] = $real_lang['email_end'];
			$data['content'] = sprintf($real_lang['email_captcha_content'],$code);
			$content = $this->load->view('ucenter/public_email',$data,TRUE);
			/*********直接用框架邮件类发送*********/
			$send_res = send_mail($email_or_phone,$real_lang['email_captcha_title'],$content);
			$newdata = array($email_or_phone=>array(
					'email_or_phone'  => $email_or_phone,
					'code'     => $code,
					'expire_time' => time()+1800
			));
			$this->session->set_userdata($newdata);

			return array('error'=>'false','message'=>'success','msg'=>'success');
		}
    
        public function Verification_Code($yzm,$mobile) {
            /****验证码校验***/
        $this->load->model('m_user');
        $mb=$this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        $user= $mobile?$mobile:$mb['mobile'];
        $session=$this->session->userdata($user);
            if(isset($session['email_or_phone'])){
                $row=$session;
				//m by brady.wang 没验证成功为什么要删除
				//$this->session->unset_userdata($user);
            }
            if (!empty($row) && strtolower($yzm) == strtolower($row['code'])) {
            if($row['expire_time'] < time()){
                    $success = FALSE;
                    $msg = lang('captcha_expire');
                    echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
                }
            }else{
                $success = FALSE;
                $msg = lang('login_captcha_error');
                echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
            }
			$this->session->unset_userdata($user);
        /****验证码结束***/
        }

	/**获取权限列表 andy**/
	private function get_config_right(){

            $this->load->model('tb_admin_right');

            $rightList    = array();

            $config_right = $this->tb_admin_right->getAllRight(array('type'=>1));

            if($config_right && is_array($config_right))
            {
                foreach($config_right as $v){
                    $rightList[$v['right_key']] = unserialize($v['right']);
                }
            }

            $this->config->set_item('config_right',$rightList);

            if(!empty($this->_adminInfo['id']))
            {
                $this->config->set_item('config_right_admin_id',$this->_adminInfo['id']);
            }else{
                $this->config->set_item('config_right_admin_id',0);
            }
    }

    private function __getMallKeyword($cur_location) {
        if(!$this->input->is_cli_request()){
    	if ($this->uri->segment(1) != 'admin') {
        	$this->load->model('tb_mall_goods_keyword');
        	$this->_viewData['keyword_key_all'] = $this->tb_mall_goods_keyword->keywords;
        	if (!$cur_location) {
        		return false;
        	}
        	$sn       = $this->input->get('sn');
        	$sn       = htmlentities(trim($sn));
        	$where    = ['region_code' => $cur_location, 'status' => 1, 'media' => 1];
        	$cate_key = '';
        	if ($sn) {
        		$cate_id = '';
        		foreach ($this->_viewData['category_all'] as $key => $value) {
        			if ($value['cate_sn'] == $sn) {
        				$cate_id = $value['parent_id'] == '0' ? $value : $value['cate_id'];
        				break;
        			}
        		}
        		if ($cate_id) {
        			$arr =  is_array($cate_id) ? $cate_id : getParentArr($this->_viewData['category_all'], $cate_id);
        		    $cate_key = $this->_viewData['keyword_key_all'][$cur_location][$arr['cate_sn']];
        			if ($cate_key ) {
        				$where['position_id'] = $cate_key;
        			} 
        			unset($arr);
        		}
        	}
        	
        	$mall_keyword_list = $this->tb_mall_goods_keyword->get_keyword($where, ['keyword', 'region_code','position_id', 'priority'], ['sort_order' => 'ASC']);
        	if ($cate_key && $mall_keyword_list) {
        		$mall_keyword_list[$this->_viewData['keyword_key_all'][$cur_location]['input']] = $mall_keyword_list[$cate_key];
        		unset($mall_keyword_list[$cate_key]);
        	}
        	$this->_viewData['mall_keyword_list'] = $mall_keyword_list;
    	}
    }
    }

    protected function __getMallAds($cur_location, $cache = false) {
        if(!$this->input->is_cli_request()){
    	if ($this->uri->segment(1) != 'admin') {
        	$this->load->model('tb_mall_goods_ads');
        	$this->_viewData['mall_ads_title'] = $this->tb_mall_goods_ads->ads;
        	if (!$cur_location) {
        		return false;
        	}
        	$where = ['region_code' => $cur_location, 'status' => 1, 'media' => 1];
        	if ($cache) {      
                $position_array = [];
        		$top = $this->_viewData['mall_ads_title'][$cur_location]['top'];
                $top && array_push($position_array, $top);

                $new = $this->_viewData['mall_ads_title'][$cur_location]['new'];
                $new && array_push($position_array, $new);

                $hot = $this->_viewData['mall_ads_title'][$cur_location]['hot'];
                $hot && array_push($position_array, $hot);

                $free = $this->_viewData['mall_ads_title'][$cur_location]['free'];
                $free && array_push($position_array, $free);

                $promote = $this->_viewData['mall_ads_title'][$cur_location]['promote'];
                $promote && array_push($position_array, $promote);

    			if ($position_array) {
    				$where['position_id'] = $position_array;
    			} 
        	}
        	$mall_ads_list = $this->tb_mall_goods_ads->get_ads($where,
        		['position_id','ad_img', 'img_subhead', 'action_type','region_code', 'action_val'],
        		['sort_order'  => 'ASC']);

            if (!empty($this->_viewData['store_id'])  &&  !empty($this->_userInfo)) {
                foreach ($mall_ads_list as $k => &$v) {
                    foreach ($v as $key => &$value) {
                        if (!empty($value['action_val'])) {
                            //$mall_ads_list[$k][$key]['action_val'] = strpos($value['action_val'], 'www') !== false ?  str_ireplace("www",$this->_viewData['store_id'],$value['action_val']) : str_ireplace("mall",$this->_viewData['store_id'],$value['action_val']);
                            //$mall_ads_list[$k][$key]['action_val'] =str_replace("mall",$this->_viewData['store_id'],$value['action_val']);
                            
                            $value['action_val'] = str_ireplace("mall",$this->_viewData['store_id'],$value['action_val']);
                            
                            
                        }
                    }
                }

            }
        	$this->_viewData['mall_ads_list'] = $mall_ads_list;
            }            
        }    	
    }

}



// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */
