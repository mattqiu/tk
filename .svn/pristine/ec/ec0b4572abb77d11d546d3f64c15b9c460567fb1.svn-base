<?php
error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class mobile extends REST_Controller {

    private $__requestData = array();

    function __construct() {
        parent::__construct();
        //$this->load->model('m_debug');
        //$this->load->model('m_log');

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
//        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
//        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
//        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
        $this->__requestData = $this->input->post();

        $this->__checkSign($this->__requestData['sign'], $this->__requestData['token']);//Check the sign.
    }

    /*验证令牌*/
    private function __checkSign($sign, $token) {

        if ($sign != api_mobile_create_sign($token)) {
            $this->response(array('error_code' => 102, 'data' => array()), 200);
//            $this->response(array('error_code' => 102, 'data' => array(0=>$sign,1=>api_mobile_create_sign($token))), 200);

        }
    }

    /**
     * test
     * @author Terry
     */
    function test_post() {
        $this->__requestData['res'] = 'okle';
        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
    }

    /**
     * 获取首页热卖商品
     * @date: 2016-5-4
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    function test_goods_post() {
        $this->load->model('o_index');
        $where['is_hot'] = 1;
        $where['is_home'] = 1;
        $order['last_update'] = "desc";
        $order['add_time'] = "desc";
        $this->__requestData['hot_goods_list'] = $this->o_index->get_recommend_goods($where,20,$order);
        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
    }

    /**
     * 首页-广告
     * @date: 2016-5-11
     * @author: sky yuan
     * @parameter: loc_code/区域id,默认美国index_840
     * @return:
     */
    function get_index_ads_post() {

        $this->load->model('o_index');

        $loc=isset($this->__requestData['loc_code']) ? trim($this->__requestData['loc_code']) : 'index_840';

        $this->__requestData['ads_list'] = $this->o_index->get_index_ads($loc);
        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);

     }

    /**
      * 获取首页商品组列表
      * @date: 2016-5-11
      * @author: sky yuan
      * @parameter:
      * @return:
      */
    function get_index_goods_post() {
        $this->load->model('o_index');
        $loc_id=isset($this->__requestData['loc_id']) ? trim($this->__requestData['loc_id']) : 840;
        $lan_id=isset($this->__requestData['lan_id']) ? trim($this->__requestData['lan_id']) : 1;
        $banner_code=isset($this->__requestData['banner_code']) ? trim($this->__requestData['banner_code']) : 'ios_cate';

        $recoment_order = ['last_update'=>'desc','add_time'=>'desc'];

        $recoment_hot = ['is_hot'=>1,'is_home'=>1];
        $recoment_new = ['is_hot'=>1,'is_home'=>1];
        $recoment_promote = ['is_promote'=>1,'is_home'=>1];
        $recoment_free_shipping = ['is_free_shipping'=>1,'is_home'=>1];

        switch($loc_id) {
            case 840: //美国
                $this->__requestData['promote_goods_list'] = array();
                $this->__requestData['hot_goods_list'] = $this->o_index->get_recommend_goods($recoment_hot,4,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['hot_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_hot');
                $this->__requestData['new_goods_list'] = $this->o_index->get_recommend_goods($recoment_new,6,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['new_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_new');
                $this->__requestData['free_ship_goods_list'] = array();
                break;
            case 156: //中国
                $this->__requestData['promote_goods_list'] = $this->o_index->get_recommend_goods($recoment_promote,4,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['promote_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_promote');
                $this->__requestData['hot_goods_list'] = $this->o_index->get_recommend_goods($recoment_hot,4,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['hot_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_hot');
                $this->__requestData['new_goods_list'] = $this->o_index->get_recommend_goods($recoment_new,6,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['new_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_new');
                $this->__requestData['free_ship_goods_list'] = $this->o_index->get_recommend_goods($recoment_free_shipping,8,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['free_ship_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_free');
                break;
            case 410: //韩国
                $this->__requestData['promote_goods_list'] = array();
                $this->__requestData['hot_goods_list'] = $this->o_index->get_recommend_goods($recoment_hot,4,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['hot_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_hot');
                $this->__requestData['new_goods_list'] = $this->o_index->get_recommend_goods($recoment_new,6,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['new_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_new');
                $this->__requestData['free_ship_goods_list'] = array();
                break;
            case 344: //香港
                $this->__requestData['promote_goods_list'] = $this->o_index->get_recommend_goods($recoment_promote,4,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['promote_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_promote');
                $this->__requestData['hot_goods_list'] = $this->o_index->get_recommend_goods($recoment_hot,4,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['hot_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_hot');
                $this->__requestData['new_goods_list'] = $this->o_index->get_recommend_goods($recoment_new,6,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['new_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_new');
                $this->__requestData['free_ship_goods_list'] = array();
                break;
            case 000: //其它
                $this->__requestData['promote_goods_list'] = array();
                $this->__requestData['hot_goods_list'] = $this->o_index->get_recommend_goods($recoment_hot,20,$recoment_order,$lan_id,$loc_id);
                $this->__requestData['hot_goods_list'][] = $this->o_index->get_index_ads($banner_code.'_'.$loc_id.'_hot');
                $this->__requestData['new_goods_list'] = array();
                $this->__requestData['free_ship_goods_list'] = array();
                break;
            }
            $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }

    /**
      * 获取分类
      * @date: 2016-5-17
      * @author: sky yuan
      * @parameter:
      * @return:
      */
    function get_top_category_post() {
         $this->load->model('o_index');

         $lan_id=isset($this->__requestData['lan_id']) ? trim($this->__requestData['lan_id']) : 0;
         
         $this->__requestData['category_list'] = array();
         if($lan_id) {
             $this->__requestData['category_list'] = $this->o_index->get_top_category($lan_id);
         } 
         
         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);

     }

    /**
      * 获取商品浏览历史记录
      * @date: 2016-5-17
      * @author: sky yuan
      * @parameter:
      * @return:
      */
    function get_history_goods_post() {
         $this->load->model('o_index');

         $goods_ids=isset($this->__requestData['goods_ids']) ? trim($this->__requestData['goods_ids']) : '';

         $this->__requestData['history_list'] = $this->o_index->get_history_list($goods_ids);
         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }

    /**
      * 获取商城首页公告
      * @date: 2016-5-17
      * @author: sky yuan
      * @parameter:
      * @return:
      */
    function get_notice_post() {
         $this->load->model('o_index');

         $lan_id=isset($this->__requestData['lan_id']) ? trim($this->__requestData['lan_id']) : 1;
         $type_id=isset($this->__requestData['type_id']) ? intval($this->__requestData['type_id']) : 1125;

         $this->__requestData['notice_list'] = $this->o_index->get_notice_list($lan_id,$type_id);
         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }

     /**
      * 获取详情页面信息
      * @date: 2016-5-20
      * @author: sky yuan
      * @parameter:
      * @return:
      */
     function get_goods_detail_post() {
         $this->load->model('o_index');

         $lan_id=isset($this->__requestData['lan_id']) ? trim($this->__requestData['lan_id']) : 2;
         $loc_id=isset($this->__requestData['loc_id']) ? trim($this->__requestData['loc_id']) : 156;
         $user_id=isset($this->__requestData['user_id']) ? intval($this->__requestData['user_id']) : '';

         $goods_sn_main=isset($this->__requestData['snm']) ? trim($this->__requestData['snm']) : '53586900';
         $goods_sn=isset($this->__requestData['sn']) ? trim($this->__requestData['sn']) : '';

         $this->__requestData['goods_info'] = $this->o_index->get_goods_detail($lan_id,$goods_sn_main,$goods_sn,$loc_id,$user_id);

         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }

     /**
      * 获取汇率列表
      * @date: 2016-5-23
      * @author: sky yuan
      * @parameter:
      * @return:
      */
     function get_rate_post() {
         $this->load->model('m_global');

         $cur_code=isset($this->__requestData['cur_code']) ? trim($this->__requestData['cur_code']) : '';

         $this->__requestData['rate_info'] = $this->m_global->getCurrencyList($cur_code);

         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }

     /**
      * 产品搜索
      * @date: 2016-5-27
      * @author: sky yuan
      * @parameter:
      * @return:
      */
     function goods_serach_post() {

         $this->load->model('m_goods');

         $loc_id=isset($this->__requestData['loc_id']) ? trim($this->__requestData['loc_id']) : 840;
         $searchData['language_id']=isset($this->__requestData['lan_id']) ? intval($this->__requestData['lan_id']) : 1;
         $searchData['page'] = isset($this->__requestData['page']) ? intval($this->__requestData['page']) : 1;
         $keywords=isset($this->__requestData['keywords']) ? trim($this->__requestData['keywords']) : '';
         $searchData['keywords']=addslashes($keywords);
         $searchData['order']=isset($this->__requestData['order']) ? trim($this->__requestData['order']) : 'composite';
         $searchData['arr']=isset($this->__requestData['arr']) ? ($this->__requestData['arr'] == 'down' ? '' : 'down') : '';

         $cate_id=isset($this->__requestData['cate_id']) ? intval($this->__requestData['cate_id']) : '';
         if(!empty($cate_id)) {
            $cate_all=$this->m_goods->get_all_category($searchData['language_id']);
            $children = getChildArr($cate_all,$cate_id);
            $children=$children.$cate_id; //选中类下所有子类,包含自己
            if(empty($children))
            {
                $children=$cate_id; //3级类
            }
            $searchData['cate_id']=$children;
         }

         $this->__requestData['goods_count'] = $this->m_goods->get_cate_goods_total($searchData,$loc_id);
         $this->__requestData['goods_list'] = $this->m_goods->get_goods_info_by_cateid($searchData,40,'',$loc_id,$searchData['language_id']);

         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }

     /**
      * 热卖、促销、新品、包邮
      * @date: 2016-6-1
      * @author: sky yuan
      * @parameter:
      * @return:
      */
     function get_model_goods_post() {
         $this->load->model('m_goods');
         $this->load->model('o_index');

         $loc_id=isset($this->__requestData['loc_id']) ? trim($this->__requestData['loc_id']) : 840;
         $searchData['language_id']=isset($this->__requestData['lan_id']) ? intval($this->__requestData['lan_id']) : 1;
         $searchData['page'] = isset($this->__requestData['page']) ? intval($this->__requestData['page']) : 1;
         $type=isset($this->__requestData['type']) ? trim($this->__requestData['type']) : 'is_hot';
         $ad_type=isset($this->__requestData['ad_type']) ? trim($this->__requestData['ad_type']) : 'hot';

         $searchData[$type] = 1;

         $this->__requestData['ads_list'] = $this->o_index->get_index_ads($ad_type.'_'.$loc_id,false);
         $this->__requestData['goods_count'] = $this->m_goods->get_cate_goods_total($searchData,$loc_id);
         $this->__requestData['goods_list'] = $this->m_goods->get_goods_info_by_cateid($searchData,40,'',$loc_id,$searchData['language_id']);

         $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
     }
     
     /** 
      * 添加关注
      * @date: 2016-6-24 
      * @author: sky yuan
      * @parameter: 
      * @return: 
      */
    function add_attention_post() {

        $goods_id=isset($this->__requestData['goods_id']) ? intval($this->__requestData['goods_id']) : '';
        $goods_sn=isset($this->__requestData['goods_sn_main']) ? trim($this->__requestData['goods_sn_main']) : '';
        $user_id=isset($this->__requestData['user_id']) ? intval($this->__requestData['user_id']) : '';
        
        $this->__requestData['result'] = 0;
        if($goods_id && $goods_sn && $user_id) {
            $this->load->model("tb_mall_wish");
            $status=$this->tb_mall_wish->add_wish($goods_id,$user_id,$goods_sn);
            
            if($status) {
                $this->__requestData['result'] = 1;
            }
        }

        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
    } 
    
    /** 
     * 获取关注商品列表
     * @date: 2016-6-24 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function get_attention_list_post() {
        $this->load->model('m_user_helper');
    
        $user_id=isset($this->__requestData['user_id']) ? intval($this->__requestData['user_id']) : '';

        $searchData['lan_id']=isset($this->__requestData['lan_id']) ? intval($this->__requestData['lan_id']) : 1;
        $searchData['page'] = isset($this->__requestData['page']) ? intval($this->__requestData['page']) : 1;
    
        $this->__requestData['goods_list'] = array();
        if($user_id) {
                $this->__requestData['goods_list'] = $this->m_user_helper->getCollection($searchData,$user_id,40);
                $this->__requestData['goods_list_total']=$this->m_user_helper->getCollectionRows($user_id);
        }
    
        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
    }
    
    /** 
     * 取消关注
     * @date: 2016-6-24 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function cancel_attention_post() {
        $this->load->model('m_user_helper');
        
        $user_id=isset($this->__requestData['user_id']) ? intval($this->__requestData['user_id']) : '';
        
        $goods_id=isset($this->__requestData['goods_id']) ? intval($this->__requestData['goods_id']) : '';
        
        $this->__requestData['result'] = false;
        if($user_id && $goods_id) {
            $this->__requestData['result'] = $this->m_user_helper->cancel_collection($goods_id,$user_id);
        }
        
        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
    }

	/**
	 * 登陆接口 by john
	 */
	function login_post(){

		if(!isset($this->__requestData['loginName']) ||  !isset($this->__requestData['pwdOriginal'])|| !isset($this->__requestData['location_id'])  ){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

        $this->load->model('o_login_register');

		/** 需要參數：$loginData['loginName'] 、$loginData['pwdOriginal'] */
		$check_result = $this->o_login_register->checkUserLogin($this->__requestData);

		$userInfo = $check_result['error_code'] == 0 ? $check_result['userInfo'] : array();

		$this->response(array('error_code' => $check_result['error_code'], 'data' => $userInfo), 200);
	}

	/** 普通会员 / 店主 注册接口 by john */
	function register_post(){

		if(!isset($this->__requestData['email']) ||  !isset($this->__requestData['pwdOriginal']) ||  !isset($this->__requestData['reg_type'])
			||  !isset($this->__requestData['disclaimer']) ||  !isset($this->__requestData['captcha'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		if(isset($this->__requestData['parent_id']) && $this->__requestData['parent_id'] == 0 ){ //源店铺ID是0，默认就是普通会员注册
			unset($this->__requestData['parent_id']);
		}

		$this->__requestData['action_id'] = 1;//注册验证码标识

		$this->load->model('m_user');
		$this->load->model('m_global');
		$checkResult = $this->m_user->checkRegisterItems($this->__requestData,'api');

		$success = TRUE;
		foreach ($checkResult as &$resultItem) {
			if (!$resultItem['isRight']) {
				$success = FALSE;
				$this->response(array('error_code' => $resultItem['error_code'], 'data' => array()), 200); //字段数据问题
				break;
			}
		}

		if($success){

			unset($this->__requestData['disclaimer']);
			unset($this->__requestData['captcha']);
			unset($this->__requestData['sign']);
			unset($this->__requestData['token']);
			unset($this->__requestData['action_id']);

			/** 手機註冊 */
			if(is_numeric($this->__requestData['email'])){
				$this->__requestData['mobile'] = $this->__requestData['email'];
				unset($this->__requestData['email']);
			}
			$is_register = $this->m_user->register($this->__requestData);

			if($is_register === FALSE){
				$this->response(array('error_code' => 1111, 'data' => array()), 200);//注册失败
			}

			$this->response(array('error_code' => 0, 'data' => array()), 200); //注册成功
		}

	}

	/** 发送 验证码 接口 by john */
	function send_captcha_post(){

		if(!isset($this->__requestData['email_or_phone']) ||  !isset($this->__requestData['reg_type'])
			||  !isset($this->__requestData['language_id']) ||  !isset($this->__requestData['action_id'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		if($this->__requestData['action_id'] == 4){//绑定操作，检查手机号，或者邮箱是否已绑定过
			$this->load->model('tb_users');
			$is_binding = $this->tb_users->is_check_binding($this->__requestData['email_or_phone']);
			if($is_binding){
				$this->response(array('error_code' =>1045, 'data' => array()), 200);
			}
		}

		$code = generate_code(4);

		$this->load->model('o_login_register');
		$data = $this->o_login_register->phone_yzm($this->__requestData,$code);

		$result = array();
		if($data['error_code'] == 0){
			$result = array('code'=>$code);
		}

		$this->response(array('error_code' =>$data['error_code'], 'data' => $result), 200);

	}

	/** 核对验证码是否正确 */
	function check_captcha_post(){

		if(!isset($this->__requestData['email_or_phone']) ||  !isset($this->__requestData['captcha'])|| !isset($this->__requestData['action_id']) ){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->__requestData['email'] = $this->__requestData['email_or_phone'];

		unset($this->__requestData['sign']);
		unset($this->__requestData['token']);
		unset($this->__requestData['email_or_phone']);

		$this->load->model('m_user');
		$checkResult = $this->m_user->checkRegisterItems($this->__requestData,'api');

		foreach ($checkResult as &$resultItem) {
			if (!$resultItem['isRight']) {

				$this->response(array('error_code' => $resultItem['error_code'], 'data' => array()), 200);
			}
		}

		$this->response(array('error_code' => 0, 'data' => array()), 200); //注册成功

	}

	/**
	 *  忘记了密码 -》修改密码 接口
	 */
	function forgot_pwd_post(){
		if(!isset($this->__requestData['email_or_phone']) ||  !isset($this->__requestData['newPwd'])||  !isset($this->__requestData['newPwdRe'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('m_user');
		$checkResult = $this->m_user->checkRegisterItems(array('pwdOriginal'=>$this->__requestData['newPwd'],'pwdOriginal_re'=>$this->__requestData['newPwdRe']),'api');

		$success = TRUE;
		foreach($checkResult as $resultItem){
			if(!$resultItem['isRight']){
				$success = FALSE;
				$this->response(array('error_code' => $resultItem['error_code'], 'data' => array()), 200); //字段数据问题
				break;
			}
		}
		if($success){

			$this->load->model('tb_users');
			$userInfo = $this->tb_users->getUserByIdOrEmail($this->__requestData['email_or_phone']);
			if (!$userInfo) {
				$this->response(array('error_code' =>1008, 'data' => array()), 200); //用户不存在。
			}

			$newPwdEncy = $this->m_user->pwdEncryption(trim($this->__requestData['newPwd']), $userInfo['token']);
			$this->m_user->updatePwdEncy($userInfo['id'],$newPwdEncy);
			$this->m_user->addInfoToWohaoSyncQueue($userInfo['id'],array(2));//修改密码

			$this->response(array('error_code' =>0, 'data' => array()), 200);
		}
	}

	/**
	 * 搜索店鋪 : 店铺ID ， 店铺名称 ，域名前缀
	 */
	public function search_store_post(){
		if(!isset($this->__requestData['keywords'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		if(trim($this->__requestData['keywords']) == ''){
			$this->response(array('error_code' =>1001, 'data' => array()), 200); //参数值不能为空
		}
		$this->load->model('tb_users');
		$data = $this->tb_users->search_store($this->__requestData['keywords']);
		$this->response(array('error_code' => 0, 'data' => $data), 200);
	}

	/**
	 * 第一次设置资金密码
	 */
	public function set_cash_pwd_post(){

		if(!isset($this->__requestData['cash_pwd']) ||  !isset($this->__requestData['cash_pwd_pre'])
			||  !isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$pwd = trim(base64_decode($this->__requestData['cash_pwd']));
		$pwdRe = trim(base64_decode($this->__requestData['cash_pwd_pre']));

		if (!preg_match('/^\d{6}$/', $pwd)) {
			$error_code = 1001; //资金密码必须是6位数字
		} elseif ($pwd !== $pwdRe) {
			$error_code = 1002;//两次密码不一致
		}else{
			$this->load->model('tb_users');

			$user = $this->tb_users->getUserByIdOrEmail($this->__requestData['uid']);

//			if($user['pwd_take_out_cash']){
//				$error_code = 1005;//已经设置了资金密码
//			}else{
				$is_success = $this->tb_users->saveTakeCashPwd($user['id'],$user['token'],$pwd);
				$error_code = $is_success > 0 ? 0 : 1003 ; //更新密码失败或新密码与旧密码一致
//			}

		}
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 忘记了资金密码
	 */
	public function forgot_cash_post(){

	}

	/**
	 * 修改资金密码
	 */
	public function update_cash_pwd_post(){

		if(!isset($this->__requestData['old_cash_pwd']) || !isset($this->__requestData['cash_pwd'])
			||  !isset($this->__requestData['cash_pwd_pre']) ||  !isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$oldPwd = trim($this->__requestData['old_cash_pwd']);
		$pwd = trim($this->__requestData['cash_pwd']);
		$pwdRe = trim($this->__requestData['cash_pwd_pre']);


		$this->load->model('tb_users');
		$user = $this->tb_users->getUserByIdOrEmail($this->__requestData['uid']);

		if($this->tb_users->encyTakeCashPwd($oldPwd,$user['token']) !==$user['pwd_take_out_cash']){
			$error_code = 1004;//原密码不正确
		}elseif (!preg_match('/^\d{6}$/', $pwd)) {
			$error_code = 1001; //资金密码必须是6位数字
		} elseif ($pwd !== $pwdRe) {
			$error_code = 1002;//两次密码不一致
		}else{
			$is_success = $this->tb_users->saveTakeCashPwd($user['id'],$user['token'],$pwd);
			$error_code = $is_success > 0 ? 0 : 1003 ; //更新密码失败或新密码与旧密码一致
		}

		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 *	首次设置会员姓名
	 */
	public function set_user_name_post(){
		if(!isset($this->__requestData['user_name']) || !isset($this->__requestData['uid']) ){
			$this->response(array('error_code' =>106, 'data' => $this->__requestData), 200); //参数名错误
		}

		$error_code = 0;

		if(!trim($this->__requestData['user_name'])){
			$error_code = 1001;//请输入名字
		}

		//加载CI黑名单过滤类库，初始化时传递的是存于配置文件中的参数
		$this->load->library('blacklist');
		$is_blocked = $this->blacklist->check_text($this->__requestData['user_name'])->is_blocked();
		if($is_blocked){
			$error_code = 1002;//姓名包含禁用文字;
		}

		$this->load->model('tb_users');
		$this->tb_users->saveUserName($this->__requestData['uid'],$this->__requestData['user_name']);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 设置会员国家地区
	 */
	public function set_user_country_post(){
		if(!isset($this->__requestData['country_id']) || !isset($this->__requestData['uid']) ){
			$this->response(array('error_code' =>106, 'data' => $this->__requestData), 200); //参数名错误
		}

		$error_code = 0;

		if($this->__requestData['country_id'] < 0 && $this->__requestData['country_id'] > 13){
			$error_code = 1002;//国家参数值不正确
		}

		if($this->__requestData['country_id'] == ''){
			$error_code = 1001;//请选择国家
		}

		$this->load->model('tb_users');
		$this->tb_users->set_user_country($this->__requestData['uid'],$this->__requestData['country_id']);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 绑定手机号或者邮箱
	 */
	public function binding_mobile_or_email_post(){
		if(!isset($this->__requestData['email_or_phone']) || !isset($this->__requestData['uid']) ||
			  !isset($this->__requestData['captcha'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->__requestData['email'] = $this->__requestData['email_or_phone'];

		unset($this->__requestData['sign']);
		unset($this->__requestData['token']);
		unset($this->__requestData['email_or_phone']);

		$this->__requestData['action_id'] = 4;//绑定标识
		$this->__requestData['reg_type'] = 0;//就会验证手机/邮箱的唯一性

		$this->load->model('m_user');
		$checkResult = $this->m_user->checkRegisterItems($this->__requestData,'api');

		foreach ($checkResult as &$resultItem) {
			if (!$resultItem['isRight']) {

				$this->response(array('error_code' => $resultItem['error_code'], 'data' => array()), 200);
			}
		}

		$this->load->model('tb_users');
		$this->tb_users->binding_mobile_or_email($this->__requestData['uid'],$this->__requestData['email']);
		$this->response(array('error_code' => 0, 'data' => array()), 200);
	}

	/**
	 * 得到各個國家的地區
	 */
	public function get_area_country_post(){
		$filename = "./file/js/user_address_linkage.js";
		$data = file_get_contents($filename);
		$data = str_replace('var linkage = ','',$data);

		$this->response(array('error_code' => 0, 'data' => json_decode($data)), 200);
	}

	/**
	 * 提现接口
	 */
	public function withdrawal_post(){
		if( !isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->withdrawal_process($this->__requestData);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 取消提现
	 */
	public function cancel_withdrawal_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['id'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->cancel_withdrawal($this->__requestData);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 *	上传身份证件
	 */
	public function validate_id_card_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['type']) || !isset($this->__requestData['number']) ){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('tb_user_id_card_info');
		$this->load->model('o_ucenter_info');

		/** 除自己之外的身份证号码唯一性 */
		$count = $this->tb_user_id_card_info->uniqueIdCardNum($this->__requestData['uid'],$this->__requestData['number']);
		if($count > 0){
			$this->response(array('error_code' =>1006 , 'data' => array()), 200);//身份证号码重复
		}

		$result  = $this->o_ucenter_info->upload_id_card_file($this->__requestData);
		$data = array();
		if(is_array($result)){
			$error_code = $result['error_code'];
			$data['pathImg'] =  $result['pathImg'];
		}else{
			$error_code = $result;
		}

		$this->response(array('error_code' => $error_code, 'data' => $data), 200);
	}

	/**
	 * 删除身份证
	 */
	public function del_id_card_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['type'])
			|| !isset($this->__requestData['pathImg'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->del_id_card($this->__requestData);

		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 提交审核身份证
	 */
	public function submit_id_card_post(){
		if( !isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->submit_id_card($this->__requestData);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 得到会员的地址列表
	 */
	public function get_user_address_list_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['location_id'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		if(!in_array($this->__requestData['location_id'],array('156','410','840','344','000'))){
			$this->response(array('error_code' => 1001, 'data' => array()), 200);//参数值错误
		}

		$this->load->model('o_ucenter_info');
		$address_list = $this->o_ucenter_info->get_user_address_list($this->__requestData);
		$this->response(array('error_code' => 0, 'data' => $address_list), 200);
	}

	/**
	 * 新增用户收货地址
	 */
	public function set_user_address_post(){
		if( !isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$res  = $this->o_ucenter_info->set_user_address($this->__requestData);
		if(is_array($res)){

			$this->response(array('error_code' => $res['error_code'], 'data' => array('id'=>$res['id'])), 200);
		}else{
			$this->response(array('error_code' => $res, 'data' => array()), 200);
		}
	}

	/**
	 * 修改收货地址
	 */
	public function update_user_address_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['id'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->update_user_address($this->__requestData);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 删除收货地址
	 */
	public function del_user_address_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['id'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->del_user_address($this->__requestData);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 设置默认的收货地址
	 */
	public function set_user_default_address_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['id'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->set_user_default_address($this->__requestData);
		$this->response(array('error_code' => $error_code, 'data' => array()), 200);
	}

	/**
	 * 计算充值月费
	 */
	public function cal_month_fee_post(){
		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['month'])|| !isset($this->__requestData['payment_method'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$result = $this->o_ucenter_info->cal_month_fee($this->__requestData);
		$this->response(array('error_code' => 0, 'data' => $result ), 200);
	}

	/**
	 * 充值月费
	 */
	public function paid_month_fee_post(){

		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['month']) || !isset($this->__requestData['payment_method'])
			|| !isset($this->__requestData['amount']) || !isset($this->__requestData['usd_money'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->paid_month_fee($this->__requestData);
		if(is_string($error_code)){
			$this->response(array('error_code' => 0, 'data' => $error_code ), 200);
		}else{
			$this->response(array('error_code' => $error_code, 'data' =>array()), 200);
		}
	}

	/**
	 * 订单支付
	 */
	public function go_order_pay_post(){

		if( !isset($this->__requestData['uid']) || !isset($this->__requestData['order_id']) || !isset($this->__requestData['payment_method'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		/**
		 * 如果是現金池支付,必須含有資金密碼字段
		 */
		if($this->__requestData['payment_method'] == 110 && !isset($this->__requestData['cash_pwd'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('o_ucenter_info');
		$error_code  = $this->o_ucenter_info->go_order_pay($this->__requestData);

		if(is_string($error_code)){
			$this->response(array('error_code' => 0, 'data' => $error_code ), 200);
		}else{
			$this->response(array('error_code' => $error_code, 'data' =>array()), 200);
		}
	}

	/**
	 * IP定位到国家CODE
	 */
	public function ip_to_country_post(){
		if( !isset($this->__requestData['client_ip']) ){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('m_global');
		$country_code = $this->m_global->get_country_code($this->__requestData['client_ip']);
		//国家code对应的国家ID
		$_country_code_loc = array('CN'=>'156','KR'=>'410','HK'=>'344','US'=>'840');
		$location_id = isset($_country_code_loc[$country_code])?$_country_code_loc[$country_code] : '000';
		$this->response(array('error_code' => 0, 'data' => $location_id), 200);
	}

	/**
	 * 得到用户的特定信息
	 */
	public function get_user_info_post(){

		if( !isset($this->__requestData['fields']) || !isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('o_ucenter_info');
		$userInfo  = $this->o_ucenter_info->get_user_info($this->__requestData);

		$this->response(array('error_code' => 0, 'data' => $userInfo), 200);

	}

	/**
	 * 获取用户的基础信息
	 */
	public function get_user_base_info_post(){

		if(!isset($this->__requestData['uid'])){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}
		$this->load->model('o_ucenter_info');
		$userInfo  = $this->o_ucenter_info->get_user_base_info($this->__requestData);

		$this->response(array('error_code' => 0, 'data' => $userInfo), 200);
	}

	/**
	 * 返回会员二維碼圖片地址
	 */
	public function get_store_code_post(){
		if(!isset($this->__requestData['uid']) ){
			$this->response(array('error_code' =>106, 'data' => array()), 200); //参数名错误
		}

		$this->load->model('o_ucenter_info');
		$result  = $this->o_ucenter_info->get_store_code($this->__requestData);

		if(is_array($result)){
			$this->response(array('error_code' => 0, 'data' => $result['path'] ), 200);
		}else{
			$this->response(array('error_code' => $result, 'data' =>array()), 200);
		}
	}


	/* 店铺分布图 */
	function genealogy_tree_post(){
        $this->load->model('tb_users');
        if(!isset($this->__requestData['uid'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200); //未定义参数
        }

        $res = $this->tb_users->getUserByIdOrEmail($this->__requestData['uid']);
        if(empty($res)){
            $this->response(array('error_code' =>1008, 'data' => array()), 200); //用户不存在。
        }

        $data = $this->tb_users->get_genealogy_tree($this->__requestData['uid']);
        if(!empty($data)){
            $this->response(array('error_code' => 0, 'data' => $data), 200);
        }
    }

    /* 2×5分布图 */
    function forced_matrix_2x5_post(){
        $this->load->model('tb_users');
        $this->load->model('tb_user_sort_2x5');
        if(!isset($this->__requestData['uid'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200); //未定义参数
        }

        $res = $this->tb_users->getUserByIdOrEmail($this->__requestData['uid']);
        if(empty($res)){
            $this->response(array('error_code' =>1008, 'data' => array()), 200); //用户不存在。
        }

        $res = $this->tb_user_sort_2x5->is_exist_2x5($this->__requestData['uid']);
        if(empty($res)){
            $this->response(array('error_code' =>1001, 'data' => array()), 200); //未进入2x5矩阵
        }

        $data = $this->tb_user_sort_2x5->get_forced_matrix_2x5($this->__requestData['uid']);
        if(!empty($data)){
            $this->response(array('error_code' => 0, 'data' => $data), 200);
        }

    }

    /* 138分布图 */
    function forced_matrix_138_post(){
        $this->load->model('tb_users');
        $this->load->model('tb_user_coordinates');
        if(!isset($this->__requestData['uid'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200); //未定义参数
        }

        $res = $this->tb_users->getUserByIdOrEmail($this->__requestData['uid']);
        if(empty($res)){
            $this->response(array('error_code' =>1008, 'data' => array()), 200); //用户不存在。
        }

        $res = $this->tb_user_coordinates->is_exist_138($this->__requestData['uid']);
        if(empty($res)){
            $this->response(array('error_code' =>1001, 'data' => array()), 200); //未进入138矩阵
        }

        $data = $this->tb_user_coordinates->get_forced_matrix_138($this->__requestData['uid']);
        if(!empty($data)){
            $this->response(array('error_code' => 0, 'data' => $data), 200);
        }
    }

    /**
     * 设置or取消某用户账户的“现金池自动转月费池”
     * @author Terry
     */
    public function set_cashpool_autosupply_monthlyfeepool_post(){

        $this->load->model('tb_users');

        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['is_auto']) || !in_array($this->__requestData['is_auto'],array(0,1))){
            
            $this->response(array('error_code' =>101, 'data' => array()), 200);
        }

        if($this->tb_users->setCashPoolAutoSupplyMonthlyFeePool($this->__requestData['uid'],$this->__requestData['is_auto'])){
            $this->response(array('error_code' =>0, 'data' => array()), 200);
        }else{
            $this->response(array('error_code' =>103, 'data' => array()), 200);//处理失败
        }
    }

    /**
     * 获取某用户账户是否设置了“现金池自动转月费池”
     * @author Terry
     */
    public function get_cashpool_autosupply_monthlyfeepool_post(){

        $this->load->model('tb_users');

        if(!isset($this->__requestData['uid'])){
            
            $this->response(array('error_code' =>101, 'data' => array()), 200);
        }

        $res = $this->tb_users->getCashPoolAutoSupplyMonthlyFeePool($this->__requestData['uid']);
        $this->response(array('error_code' =>0, 'data' => array('status'=>$res)), 200);
    }

    /**
     * 获取用户分红点信息（奖励分红点、普通分红点、佣金自动转分红比例）
     * @author Terry
     */
    public function get_user_sharingpoint_info_post(){

        $this->load->model('tb_users');
        $this->load->model('tb_users_sharing_point_reward');
        $this->load->model('tb_profit_sharing_point_proportion');
        $this->load->model('tb_users_profit_sharing_point_last_month');

        if(!isset($this->__requestData['uid'])){
            
            $this->response(array('error_code' =>101, 'data' => array()), 200);
        }

        $sharingPoint = $this->tb_users->getUserSharingPoint($this->__requestData['uid']);
        $sharingPointReward = $this->tb_users_sharing_point_reward->getUserRewardSharingPoint($this->__requestData['uid']);
        $sharingPointProportion = $this->tb_profit_sharing_point_proportion->getUserCommToSharingpointProportion($this->__requestData['uid']);
        $transferableSharingpoint = $this->tb_users_profit_sharing_point_last_month->getTransferableSharingpoint($this->__requestData['uid']);

        $this->response(array('error_code' =>"0", 'data' => array('sharingPoint'=>(string)$sharingPoint,'sharingPointReward'=>(string)$sharingPointReward,'sharingPointProportion'=>(string)$sharingPointProportion,'transferableSharingpoint'=>(string)$transferableSharingpoint)), 200);
    }

    /**
     * 设置／修改“佣金自动转分红点比例”。
     * @author Terry
     */
    public function set_sharingpoint_proportion_post(){

        $this->load->model('tb_profit_sharing_point_proportion');

        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['proportion']) ){
            
            $this->response(array('error_code' =>101, 'data' => array()), 200);
        }

        $error_code = $this->tb_profit_sharing_point_proportion->saveUserProportion($this->__requestData['uid'],1,$this->__requestData['proportion']);
        $this->response(array('error_code' =>(int)$error_code, 'data' => array()), 200);
    }

    /**
     * 分红点转现金池。
     * @author Terry
     */
    public function sharingpoint_to_cash_post(){

        $this->load->model('m_profit_sharing');
        $this->load->model('tb_users');

        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['point']) ){
            
            $this->response(array('error_code' =>101, 'data' => array()), 200);
        }

        $userInfo = $this->tb_users->getUserByIdOrEmail($this->__requestData['uid']);
        $res = $this->m_profit_sharing->sharingPointToMoney($userInfo,$this->__requestData['point']);

        $this->response(array('error_code' =>(int)$res['error_code'], 'data' => array()), 200);
    }

    /**
     * 根据用户id获取姓名
     * @author Terry
     */
    public function get_user_name_by_id_post(){

        $this->load->model('tb_users');

        $userInfo = $this->tb_users->getUserInfo($this->__requestData['uid'],array('name'));
        if($userInfo){
            $this->response(array('error_code' => (int)0, 'data' => (array)$userInfo), 200);
        }else{
            $this->response(array('error_code' => (int)103, 'data' => array()), 200);
        }
    }

    /**
     * 会员之间转帐
     * @author Terry
     */
    public function tran_money_between_mem_post(){

        $this->load->model('o_cash_account');

        $error_code = $this->o_cash_account->tranMoneyBetweenMem($this->__requestData['fromId'],$this->__requestData['tranToMemId'],$this->__requestData['tranToMemAmount'],$this->__requestData['tranToMemFundsPwd']);

        $this->response(array('error_code' =>(int)$error_code, 'data' => array()), 200);
    }

    /* 店铺订单 */
    function my_store_order_post(){

        $this->load->model('tb_trade_order');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_trade_order->get_store_order($this->__requestData['uid'],$this->__requestData['time']);
        if(empty($data)){
            $this->response(array('error_code' =>107, 'data' => array()), 200);
        }

        $this->response(array('error_code' =>0, 'data' => $data), 200);
    }

    /* 自我消费订单 */
    function my_buy_order_post(){
        $this->load->model('tb_trade_order');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_trade_order->get_buy_order($this->__requestData['uid'],$this->__requestData['time']);

        if(empty($data)){
            $this->response(array('error_code' =>107, 'data' => array()), 200);
        }
        $this->response(array('error_code' =>0, 'data' => $data), 200);
    }

    /* 沃好订单 */
    function my_wohao_order_post(){
        $this->load->model('tb_mall_orders');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_mall_orders->get_wohao_order($this->__requestData['uid'],$this->__requestData['time']);
        if(empty($data)){
            $this->response(array('error_code' =>107, 'data' => array()), 200);
        }
        $this->response(array('error_code' =>0, 'data' => $data), 200);
    }

    /* 佣金报表 */
    function commission_report_post(){
        //参数未定义
        $this->load->model('tb_commission_logs');
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time']) || !isset($this->__requestData['type']) || !isset($this->__requestData['pager'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        $data = $this->tb_commission_logs->get_commission_logs_mobile(
            $this->__requestData['uid'],
            $this->__requestData['time'],
            $this->__requestData['type'],
            $this->__requestData['pager']
        );

        $this->response(array('error_code' =>0, 'data' => $data), 200);

    }

    /* 添加到购物车 */
    function add_cart_post(){
        $this->load->model('tb_user_cart');
        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['goods_sn']) || !isset($this->__requestData['goods_number']) || !isset($this->__requestData['country_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $code = $this->tb_user_cart->add_cart(
            $this->__requestData['uid'],
            $this->__requestData['goods_sn'],
            $this->__requestData['goods_number'],
            $this->__requestData['country_id']
        );
        $this->response(array('error_code' =>$code, 'data' => array()), 200);
    }

	/* 获取购物车 */
	function cart_post(){
        $this->load->model('tb_user_cart');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['country_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        //请求购物车数据
        $data = $this->tb_user_cart->get_cart(
            $this->__requestData['uid'],
            $this->__requestData['country_id']
        );
        if(empty($data)){
            $this->response(array('error_code' =>107, 'data' => array()), 200);
        }
		$this->response(array('error_code' =>0, 'data' => $data), 200);
	}

    /* 删除购物车商品 */
    function delete_cart_post(){
        $this->load->model('tb_user_cart');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['goods_sn']) || !isset($this->__requestData['country_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        //删除购物车数据
        $code = $this->tb_user_cart->delete_cart(
            $this->__requestData['uid'],
            $this->__requestData['goods_sn'],
            $this->__requestData['country_id']
        );
        $this->response(array('error_code' =>$code, 'data' => array()), 200);
    }

    /* 编辑购物车商品数量 */
    function edit_cart_number_post(){
        $this->load->model('tb_user_cart');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['goods_sn']) || !isset($this->__requestData['country_id']) || !isset($this->__requestData['goods_number'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $code = $this->tb_user_cart->edit_cart_number(
            $this->__requestData['uid'],
            $this->__requestData['goods_sn'],
            $this->__requestData['country_id'],
            $this->__requestData['goods_number']
        );
        $this->response(array('error_code' =>$code, 'data' => array()), 200);
    }


    /* 代品卷 */
    function coupons_post(){
        $this->load->model('tb_user_suite_exchange_coupon');

        //参数未定义
        if(!isset($this->__requestData['uid'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        //查询代品卷
        $coupons_info = $this->tb_user_suite_exchange_coupon->get_total_money($this->__requestData['uid']);
        if(empty($coupons_info)){
            $this->response(array('error_code' =>107, 'data' => array()), 200);
        }
        $coupons_total_money = 0;

        // 去掉 m
        foreach ($coupons_info as $k => $v)
        {
            $key = substr($k, 1);
            $coupons_info[$key] = $v;
            unset($coupons_info[$k]);
        }

        // 获得总数量和总面额
        foreach ($coupons_info as $k => $v)
        {
            $coupons_total_money += $v * $k;
        }
        $data['total_money'] = (string)$coupons_total_money;

        $this->response(array('error_code' =>0, 'data' => $data), 200);
    }

    /* 代品券商品 */
    public function use_coupons_post(){
        $this->load->model('m_coupons');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['location_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        /* 代品券商品数据 */
        $data = $this->m_coupons->get_coupons_goods($this->__requestData['location_id']);
        $this->response(array('error_code' =>0, 'data' => $data), 200);
    }


    /* 代品券商品结算 */
    public function confirm_choose_coupons_post(){
        //参数未定义
        $this->load->model('o_trade');
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['goods_list']) || !isset($this->__requestData['location_id']) || !isset($this->__requestData['address_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        if($this->__requestData['goods_list'] == ''){
            $this->response(array('error_code' =>1003, 'data' => $this->__requestData), 200);
        }

        $data = $this->o_trade->make_order_page_for_coupons(
            $this->__requestData['uid'],
            $this->__requestData['goods_list'],
            $this->__requestData['location_id'],
            $this->__requestData['address_id']
        );
        $this->response(array('error_code' =>0, 'data' =>$data), 200);
    }


    /* 提现明细 */
    public function cash_take_out_logs_post(){

        $this->load->model('tb_cash_take_out_logs');
        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_cash_take_out_logs->get_cash_take_out_logs(
            $this->__requestData['uid'],
            $this->__requestData['time']
        );
        $this->response(array('error_code' =>0, 'data' =>$data), 200);
    }

    /* 分红明细 */
    public function profit_sharing_point_logs_post(){

        $this->load->model('tb_profit_sharing_point_add_log');
        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_profit_sharing_point_add_log->get_profit_sharing_point_logs(
            $this->__requestData['uid'],
            $this->__requestData['time']
        );
        $this->response(array('error_code' =>0, 'data' =>$data), 200);
    }

    /* 月费明细 */
    public function monthly_fee_logs_post(){

        $this->load->model('tb_month_fee_change');
        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['time'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_month_fee_change->get_monthly_fee_logs(
            $this->__requestData['uid'],
            $this->__requestData['time']
        );
        $this->response(array('error_code' =>0, 'data' =>$data), 200);

    }


    /* 用户升级信息 */
    public function member_upgrade_post(){

        $this->load->model('tb_users');
        //参数未定义
        if(!isset($this->__requestData['uid'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->tb_users->get_member_upgrade_info($this->__requestData['uid']);
        $this->response(array('error_code' =>0, 'data' =>$data), 200);
    }

    /* 去升级 */
    public function do_member_upgrade_post(){

        $this->load->model('m_group');
        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['location_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        //如果有未支付的升级订单,则不能升级
//        $res = $this->db->select('order_id')->where('customer_id',$this->__requestData['uid'])->where('status',2)
//            ->where('order_type',2)->from('trade_orders')->get()->row_array();
        $this->load->model('tb_trade_orders');
        $res = $this->tb_trade_orders->get_one_auto([
            "select"=>"order_id",
            "where"=>[
                'customer_id'=>$this->__requestData['uid'],
                'status'=>2,
            ]
        ]);
        if(!empty($res)){
            $this->response(array('error_code' =>1000, 'data' => $this->__requestData), 200);
        }

        $ret_arr = $this->m_group->group_info($this->__requestData['location_id']);

        //处理成手机端数组格式
        $data = $this->m_group->format_array($ret_arr,$this->__requestData['location_id']);

        $this->response(array('error_code' =>0, 'data' =>$data), 200);
    }

    /* 会员升级购物车结算 */
    public function confirm_choose_post(){
        //参数未定义
        $this->load->model('o_trade');
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['new_level']) || !isset($this->__requestData['goods_list']) || !isset($this->__requestData['coupons_list']) || !isset($this->__requestData['location_id']) || !isset($this->__requestData['address_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }
        $data = $this->o_trade->make_order_page_for_upgrade(
            $this->__requestData['uid'],
            $this->__requestData['new_level'],
            $this->__requestData['goods_list'],
            $this->__requestData['coupons_list'],
            $this->__requestData['location_id'],
            $this->__requestData['address_id']
        );
        $this->response(array('error_code' =>$data['error_code'], 'data' =>$data), 200);
    }

    /* 下单详情页 */
    public function make_order_page_post(){

        $this->load->model('o_trade');
        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['goods_list']) || !isset($this->__requestData['location_id']) || !isset($this->__requestData['currency']) || !isset($this->__requestData['address_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        //参数为空
        if(empty($this->__requestData['uid']) || empty($this->__requestData['goods_list']) || empty($this->__requestData['location_id']) || empty($this->__requestData['currency'])){
            $this->response(array('error_code' =>107, 'data' => $this->__requestData), 200);
        }

        //下单详情页数据
        $data = $this->o_trade->make_order_page(
            $this->__requestData['uid'],
            $this->__requestData['goods_list'],
            $this->__requestData['location_id'],
            $this->__requestData['currency'],
            $this->__requestData['address_id']
        );

        $this->response(array('error_code' =>$data['code'], 'data' =>$data), 200);
    }

    /*  提交订单  */
    public function do_checkout_order_post(){

        $this->load->model('o_trade');

        //参数未定义
        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['shopkeeper_id']) || !isset($this->__requestData['address_id']) || !isset($this->__requestData['location_id']) || !isset($this->__requestData['remark']) || !isset($this->__requestData['goods_list']) || !isset($this->__requestData['currency'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        //参数为空
        if(empty($this->__requestData['uid']) || empty($this->__requestData['address_id']) || empty($this->__requestData['location_id']) || empty($this->__requestData['goods_list']) || empty($this->__requestData['currency'])){
            $this->response(array('error_code' =>107, 'data' => $this->__requestData), 200);
        }

        $data = $this->o_trade->make_order(
            $this->__requestData['uid'],
            $this->__requestData['shopkeeper_id'],
            $this->__requestData['address_id'],
            $this->__requestData['location_id'],
            $this->__requestData['remark'],
            $this->__requestData['goods_list'],
            $this->__requestData['currency']
        );
        $this->response(array('error_code' =>$data['code'], 'data' =>$data['order_id']), 200);
    }

    /* 提交订单 (会员升级,代品券选购)*/
    public function do_checkout_order_group_post(){
        //参数未定义
        $this->load->model('o_trade');

        if(!isset($this->__requestData['uid']) || !isset($this->__requestData['address_id']) || !isset($this->__requestData['location_id']) || !isset($this->__requestData['remark']) || !isset($this->__requestData['goods_list']) || !isset($this->__requestData['order_type'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        //如果是升级订单,需要验证coupons_list、level参数
        if($this->__requestData['order_type'] == 2){
            if(!isset($this->__requestData['coupons_list']) || !isset($this->__requestData['level'])){
                $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
            }
            $coupons_list = $this->__requestData['coupons_list'];
            $level = $this->__requestData['level'];
        }

        //如果是代品券订单
        if($this->__requestData['order_type'] == 3){
            $coupons_list = '';
            $level = 0;
        }

        //下单详情
        $data = $this->o_trade->make_order_for_group(
            $this->__requestData['uid'],
            $this->__requestData['address_id'],
            $this->__requestData['location_id'],
            $this->__requestData['remark'],
            $this->__requestData['goods_list'],
            $this->__requestData['order_type'],
            $coupons_list,
            $level
        );
        $this->response(array('error_code' =>$data['code'], 'data' =>$data['order_id']), 200);
    }

    /* 获取商品详情页 */
    public function get_goods_details_pager_post(){
        $this->load->model('m_goods');

        //参数未定义
        if(!isset($this->__requestData['goods_id']) || !isset($this->__requestData['goods_sn_main']) || !isset($this->__requestData['location_id']) ){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        $data = $this->m_goods->get_goods_info_by_id(
            $this->__requestData['goods_id'],
            $this->__requestData['goods_sn_main'],
            $this->__requestData['location_id'],
            'https://img.tps138.net/'
        );

        $this->response(array('error_code' =>0, 'data' =>$data), 200);
    }

    //取消订单
    public function cancel_order_post(){

        //参数未定义
        if(!isset($this->__requestData['order_id'])){
            $this->response(array('error_code' =>106, 'data' => $this->__requestData), 200);
        }

        $this->load->model('o_trade');
        $code = $this->o_trade->do_cancel_order($this->__requestData['order_id']);

        $this->response(array('error_code' =>$code, 'data' =>array()), 200);
    }
}
