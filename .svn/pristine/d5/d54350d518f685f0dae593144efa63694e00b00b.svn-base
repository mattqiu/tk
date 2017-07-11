<?php
/**
 * 	处理登陆，注册的业务model
 * @author john
 */
class o_login_register extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	/** 每10秒 列队 处理 发送注册验证码*/
	public function sendRegisterCode(){

		$this->load->model('m_debug');

		$cronName = 'sendRegisterCode';

		$this->load->model('tb_cron_doing');
		$cron = $this->tb_cron_doing->get_cron_by_name($cronName);

		if($cron){
			if($cron['false_count'] > 12){
				$this->tb_cron_doing->del_cron_by_name($cronName);
				return false;
			}
			$this->tb_cron_doing->update_cron_by_name($cron['id'],array('false_count'=>$cron['false_count']+1));
			return false;
		}

		$this->load->model('tb_users_register_captcha');
		$codes = $this->tb_users_register_captcha->get_captcha_top100();

		if($codes){

			$this->tb_cron_doing->add_cron_row($cronName);

			$this->load->model('m_user');
			$LanArr = $this->m_user->getMonthFeeMailLan();

			foreach($codes as $item){

				$item['email_or_phone'] = trim($item['email_or_phone']);

				if($item['language_id']==2){
					$lang = 'zh';
				}elseif($item['language_id']==3){
					$lang = 'hk';
				}elseif($item['language_id']==4){
					$lang = 'kr';
				}else{
					$lang = 'english';
				}

				$real_lang = $LanArr[$lang];

				if(is_numeric($item['email_or_phone']) && preg_match('/^1[34578]{1}\d{9}$/',$item['email_or_phone'])){ //手机注册

					include_once APPPATH .'/third_party/taobao/TopSdk.php';

					$phone_cfg = config_item('phone_cfg');
					$phone_cfg_info  = $phone_cfg[$item['action_id']] ? $phone_cfg[$item['action_id']] :  $phone_cfg[1];

					$c = new TopClient;
					$c->appkey = "23362350";
					$c->secretKey = "7615d82fa94a199d8ad2c303f2e6c9e6";
					$c->format = "json";

					$req = new AlibabaAliqinFcSmsNumSendRequest;

					$req->setSmsType("normal");
					$req->setSmsParam(str_replace('@@@@',$item['code'],$phone_cfg_info['param']));
					$req->setSmsFreeSignName($phone_cfg_info['signature']);
					$req->setRecNum($item['email_or_phone']);
					$req->setSmsTemplateCode($phone_cfg_info['template']);

					$resp = $c->execute($req);

					if(json_encode($resp)['code'] == 0 ){
						$this->tb_users_register_captcha->update_captcha($item['id'],array('status'=>1));
					}else{
					}

				}
				//邮箱注册
				else if(preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $item['email_or_phone'])){

					$data['email'] = $item['email_or_phone'];
					$data['dear'] = $real_lang['dear'];
					$data['email_end'] = $real_lang['email_end'];

					$data['content'] = sprintf($real_lang['email_captcha_content'],$item['code']);
					$content = $this->load->view('ucenter/public_email',$data,TRUE);
					send_mail($item['email_or_phone'],$real_lang['email_captcha_title'],$content,array(),"");
					$this->tb_users_register_captcha->update_captcha($item['id'],array('status'=>1));
				}

			}
			$this->tb_cron_doing->del_cron_by_name($cronName);
		}

	}

	/** 检查登陆信息 by john */
	public function checkUserLogin($loginData) {

		$error_code = array('error_code' => 0);

		$loginName = trim($loginData['loginName']);
		$pwd = trim($loginData['pwdOriginal']);

		if (!$loginName) {
			$error_code['error_code'] = 1001 ; //请填写您的邮箱/ID。
		}

		$this->load->model('tb_users');
		$userInfo = $this->tb_users->getUserByIdOrEmail($loginName);
		if (!$userInfo) {
			$error_code['error_code'] = 1002; //您的邮箱/ID不存在。
			return $error_code;
		}

		if ($this->pwdEncryption($pwd, $userInfo['token']) !== $userInfo['pwd']) {
			$error_code['error_code'] = 1003; //您的密码有误。
			return $error_code;
		}

		if ($userInfo['status'] == '0') {
			$error_code['error_code'] = 1004;//您的账户未激活.
			return $error_code;
		}

		if ($userInfo['status'] == '3') {
			$error_code['error_code'] = 1005; //您的账户被冻结
			return $error_code;
		}

		if ($userInfo['status'] == '4') {
			$error_code['error_code'] = 1006; //公司预留账户禁止登录。
			return $error_code;
		}

		if($error_code['error_code'] === 0){

			/* 获取购物车商品数量 */
            $this->load->model('tb_user_cart');
            $res = $this->tb_user_cart->get_number($userInfo['id'],$loginData['location_id']);
            $userInfo['cart_total_num'] = $res[0]['total_number'];

			/**
			 * 身份證審核信息
			 */
			$this->load->model('tb_user_id_card_info');
			$id_card = $this->tb_user_id_card_info->getUserIdCardField($userInfo['id']);
			if($id_card){
				$userInfo['id_card_num'] = $id_card['id_card_num'];
				$userInfo['id_card_scan'] = $id_card['id_card_scan'];
				$userInfo['id_card_scan_back'] = $id_card['id_card_scan_back'];
				$userInfo['check_status'] = $id_card['check_status'];
				$userInfo['check_info'] = $id_card['check_info'];
			}else{
				$userInfo['id_card_num'] = $userInfo['id_card_scan'] = $userInfo['id_card_scan_back'] =
				$userInfo['check_status'] = $userInfo['check_info'] = "";
			}


			/**
			 * 個人資料是否完善
			 */
			if(!$userInfo['name'] || !$userInfo['is_verified_email'] || !$userInfo['is_verified_mobile']
				|| $userInfo['country_id'] != NULL || !$userInfo['pwd_take_out_cash'] || !$id_card['status']){
				$userInfo['is_complete_info'] = 0;
			}else{
				$userInfo['is_complete_info'] = 1;
			}

			/**
			 * 是否有未支付的订单
			 */
			$this->load->model('tb_trade_orders');
			$userInfo['is_not_payment'] = $this->tb_trade_orders->is_no_payment($userInfo['id']);

			/**
			 * 默認地址ID
			 */
			$this->load->model('m_trade');
			$userInfo['default_address_id']["156"] = $this->m_trade->get_default_address_loc($userInfo['id'],156);
			$userInfo['default_address_id']["344"] = $this->m_trade->get_default_address_loc($userInfo['id'],344);
			$userInfo['default_address_id']["410"] = $this->m_trade->get_default_address_loc($userInfo['id'],410);
			$userInfo['default_address_id']["000"] = $this->m_trade->get_default_address_loc($userInfo['id'],000);
			$userInfo['default_address_id']["840"] = $this->m_trade->get_default_address_loc($userInfo['id'],840);

			$error_code['userInfo'] = $userInfo;
		}
		return  $error_code;

	}

	/** 加密 原生态的密码 */
	public function pwdEncryption($pwdOriginal, $token) {
		return sha1('!#*' . trim($pwdOriginal) . $token . 'tps');
	}

	/**
	 * 接口添加注册验证码(老方法，插入数据库)
	 */
	public function api_add_captcha($dataPost,$code){

		$email_or_phone = $dataPost['email_or_phone'];
		$reg_type = $dataPost['reg_type'] ;
		$language_id = $dataPost['language_id'];
		$action_id = $dataPost['action_id'];

		if(!in_array($reg_type,array(0,1,2)) || !in_array($language_id, array(1,2,3,4)) || !in_array($action_id, array(1,2,3,4)) ){
			return array('error_code'=>101); //参数错误
		}

		if($action_id == 4){//绑定操作，检查手机号，或者邮箱是否已绑定过
			$this->load->model('tb_users');
			$is_binding = $this->tb_users->is_check_binding($email_or_phone);
			if($is_binding){
				die(json_encode(array('error_code'=>1045)));
			}
		}

		$this->load->model('m_user');
		$checkResult = $this->m_user->checkRegisterItems(array('email'=>$email_or_phone,'reg_type'=>$reg_type),'api');
		if($checkResult['email']['isRight'] === FALSE ){
			return array('error_code'=>$checkResult['email']['error_code']);
		}

		$this->load->model('tb_users_register_captcha');
		$count = $this->tb_users_register_captcha->add_captcha($email_or_phone,$language_id,$action_id,$code);
		if($count){
			return array('error_code'=>0);
		}else{
			return array('error_code'=>1111);//插入数据失败
		}
	}
        
        //直接发送手机验证码，此处和MY基类里的方法一样
        public function phone_yzm($da='',$code='') {
            $phone=$da['email_or_phone'];
            $action_id=$da['action_id'];
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
                $newdata = array($phone=>array(
                    'email_or_phone'  => $phone,
                    'code'     => $code,
                    'expire_time' => time()+3600
                ));
                $this->session->set_userdata($newdata);
                return array('error_code'=>0);
            }else{
                return array('error_code'=>1111);//插入数据失败
            }
        }

}
