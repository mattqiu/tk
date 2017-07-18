<?php
/**
 * 全局类。
 */
class m_global extends MY_Model {
    public $table_name;
    function __construct() {
        $this->table_name = 'mall_payment_new';//支付方式选择的表
        parent::__construct();
    }

    public function checkPermission($item,$userInfo){
        switch ($item) {
            case 'upgrade_user_manually':
                if(!in_array($userInfo['id'], array(1,3,5,145))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'synchronize_wo_hao':
                if(!in_array($userInfo['role'],array(0,1,2,5)) && !check_right('hk_export_order_role_7')){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'repair_users_amount':
                if(!in_array($userInfo['role'],array(0,2))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
                
            case 'withdraw_table':
                if(!in_array($userInfo['role'],array(0)) && !in_array($userInfo['id'], array(160,1,18,68,99,210,60,188,277,291,173,3,8,9,212,64,294,293,295,144,198,280))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'paypal_withdrawal_list':
                if(!in_array($userInfo['role'],array(0,2)) && !in_array($userInfo['id'], array(1,3,18,173,61,99,210,60,188,277,291,8,9,212,64,294,293,295,198,99,144,280))){
                    redirect(base_url('admin/no_permission'));
                }
                break;


            //解除冻结订单权限
            case 'remove_frozen':
                if(!in_array($userInfo['role'],array(0,2))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
                
            case 'commission_admin':
                if(!in_array($userInfo['role'], array(0)) && !check_right('commission_admin_model_right')){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'commission_special_do':
                if(!check_right('comm_special_admin_ids')){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'monthfee_pool_admin':
                if(!in_array($userInfo['role'], array(0))){
                    if(!in_array($userInfo['id'], array(145))){
                        redirect(base_url('admin/no_permission'));
                    }
                }
                break;
            case 'cash_withdrawal_list':
                if(!in_array($userInfo['id'], array(1,3,18,173)) && !in_array($userInfo['role'], array(4,2)) ){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'clear_member_account_info':
                if(!in_array($userInfo['role'],array(0,1,2))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'demote_levels':
                if(!in_array($userInfo['role'], array(0,2))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
			case 'return_back':
                if(!in_array($userInfo['id'], array(1,68))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'payment_list':
				if(!in_array($userInfo['role'],array(0)) && $userInfo['id'] != 68){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'set_except_group':
                if(!in_array($userInfo['id'],array(1,3,5))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
			case 'cash_to_sharing_point':
                if(!in_array($userInfo['id'],array(1,3,5))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
			case 'sharing_point_to_money':
                if(!in_array($userInfo['id'],array(1,3,5))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'import_third_part_orders':
                if(!in_array($userInfo['id'],array(1,9,131,420,464,144)) && !check_right('import_third_part_orders')){
                    redirect(base_url('admin/no_permission'));
                }
                break;
			case 'add_admin':
                if(!in_array($userInfo['id'],array(1,171,8,343))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'admin_account_list':
                if(!in_array($userInfo['role'],array(0)) && !in_array($userInfo['id'],array(171,8,343))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'order_repair_of_comm':
                if(!in_array($userInfo['role'],array(0,2))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            case 'bonus_plan':
                if(!in_array($userInfo['id'],array(1,3,145,280,464))){
                    redirect(base_url('admin/no_permission'));
                }
                break;
            default:
                break;
        }
        return TRUE;
    }

    public function checkSubdomain($subdomain){
        if( $this->db->from('users')->where('member_url_prefix',$subdomain)->get()->row_array() || in_array($subdomain,config_item('subdomain_reserved')) ){
            return TRUE;
        }else{
            return false;
        }
    }

    public function getMemberDomainInfo(){
        $return = '';
        $domain_prefix = get_domain_prefix();
        if($domain_prefix!=='mall'){
            $return = $this->db->select('id,member_url_prefix')->from('users')->where('member_url_prefix',$domain_prefix)->get()->row_array();
        }
        return $return;
    }

    /** 域名如果是www,或者沒有 隱藏註冊 */
    public function checkDomainIsWWW(){

        $is_register = FALSE;
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $arrHost = explode('.', $host);
        if(count($arrHost)==2 || $arrHost[0] === 'mall'){
            $is_register = TRUE;
        }
        return  $is_register;
    }

    /** 下单时當前店铺的ID */
    public function getStoreId($uid){
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $arrHost = explode('.', $host);
        $url_prefix = $arrHost[0] ? $arrHost[0] : 'mall';

		/**
		 * 普通订单，如果是会员下单，不管在哪个店铺消费，店铺ID都是该會員店鋪ID
		 * 如果顾客下单，在哪个店铺消费，店铺ID就是哪个店铺。by john 2016-07-19
		 */
		if($uid > 0){
			$is_store = $this->db->select('id,parent_id')->where('id',$uid)->get('users')->row_array();
			if( $is_store['parent_id'] > 0){
				return $is_store['id'];
			}
		}

		/**
		 * 顾客下单逻辑
		 */
		if ($url_prefix == "mall")
		{
        	return 0;
        }
        $row = $this->db->select('id')->where('member_url_prefix',$url_prefix)->get('users')->row_array();
        if (empty($row))
        {
        	return 0;
        }
        return $row['id'];
    }

	/** 得到店铺信息：等级， */
	public function getStoreInfo(){
		$host = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$arrHost = explode('.', $host);
		$url_prefix = $arrHost[0] ? $arrHost[0] : 'mall';
		if ($url_prefix == "mall")
		{
			return FALSE;
		}
//		$store = $this->db->select('id,store_name,member_url_prefix,user_rank')->where('member_url_prefix',$url_prefix)->get('users')->row_array();
		$this->load->model("tb_users");
		$store = $this->tb_users->get_one("id,store_name,member_url_prefix,user_rank",
            ['member_url_prefix'=>$url_prefix]);
		if (empty($store))
		{
			return FALSE;
		}
		$store_class = '';
		$store_url = $store['member_url_prefix'].'.'.get_public_domain();
		switch($store['user_rank']){
			case 1:
				$store_class = 'z';
				break;
			case 2:
				$store_class = 'b';
				break;
			case 3:
				$store_class = 'y';
				break;
			case 4:
				$store_class = 'p';
				break;
			case 5:
				$store_class = 'p';
				break;
		}
		$store_name = $store['store_name'] ? $store['store_name'] : $store_url ;
		return array('store_level'=>$store['user_rank'],'store_class'=>$store_class,'store_name'=>$store_name );
	}

    /** 判讀用戶是店主還是普通會員 */
    public function isStore($parent){
       return $parent ? TRUE : FALSE;
    }

    /* 获取语言列表  */
    public function getLangList($lang_name='') {
        if(!empty($lang_name)) {
            $this->db->where('code',$lang_name);
        }
    	return $this->db->get('language')->result_array();
    }

    /* 获取仓库列表  */
    public function getStoreList() {
    	return $this->db->order_by('store_code','asc')->get('mall_goods_storehouse')->result_array();
    }

    /* 获取供应货商列表*/
    public function getShipperList($role=null) {

		$this->db->select('ms.supplier_name,ms.supplier_id')->from('mall_supplier ms')->join('mall_goods_shipper mg','ms.supplier_id=mg.shipper_id','left');
		if($role == 6){
			$this->db->where('mg.area_rule','3');
		}if($role == 7){
			$this->db->where('mg.area_rule','2');
		}
		return $this->db->order_by('ms.supplier_id','asc')->get()->result_array();
       // return $this->db->order_by('supplier_id','asc')->get('mall_supplier')->result_array();
    }

    /* 获取货币  */
    public function getCurrencyList($currency_code='') {
        $this->load->model("tb_exchange_rate");
        $where =  [];
        if(!empty($currency_code)) {
            $where =  ["currency"=>$currency_code];
//            $this->db->where('currency',$currency_code);
        }
//    	return $this->db->get('exchange_rate')->result_array();
        return $this->tb_exchange_rate->get_list_auto(["where"=>$where]);
    }

    /* 创建文件目录(任意多级) */
    public function mkDirs($dir_name) {
    	if(!is_dir($dir_name)) {
    		$adir = explode('/',$dir_name);
    		$top_dir = array_shift($adir);
    		if (!empty($top_dir) && $top_dir != '.' && $top_dir != '..' && !is_dir($top_dir)) {
    			mkdir($top_dir,0777);
    		}
    		$dir = $top_dir;
    		while (list($key,$sub) = each($adir)) {
    			$dir .= '/' . $sub;
    			if (!empty($sub) && $sub != '.' && $sub != '..' && !is_dir($dir)) {
    				if(mkdir($dir) && chmod($dir,0777))
    					continue;
    				else
    					return false;
    			}
    		}
    		return true;
    	}

    	return true;
    }

    /* 获取用户的收藏夹总数 */
    public function get_wish_count($user_id,$cur_language_id) {
        $res = $this->db->from('mall_wish')
            ->select('goods_id')
            ->where(['user_id'=>$user_id])
            ->get()
            ->result_array();
        if (empty($res)) {
            return 0;
        } else {
            $goods_ids = [];
            foreach ($res as $v) {
                $goods_ids[] = $v['goods_id'];
            }
           /* $count_res = $this->db->from('mall_goods_main')
                ->select('count(*) as nums')
                ->where_in('goods_id',$goods_ids)
                ->where(['language_id'=>$cur_language_id])
                ->get()
                ->row_array();
            */
            $this->load->model("tb_mall_goods_main");
            $count_res = $this->tb_mall_goods_main->get_counts_auto([
                "where"=>['goods_id'=>$goods_ids,'language_id'=>$cur_language_id]
            ]);

            return intval($count_res);
        }
    }

    /* 获取当前语种的汇率  */
    public function get_rate($currency) {
        $tmp = $this->get_rate_row($currency);
        if($tmp)
        {
            return $tmp['rate'];
        }else{
            $this->m_debug->log("取不到数据，获取当前币种start：".$currency);
            $this->m_debug->log($tmp);
            $this->m_debug->log("取不到数据，获取当前币种end  ：".$currency);
        }
    }

    /* 获取货币中的一条记录  */
    public function get_rate_row($currency) {
        $this->load->model("tb_exchange_rate");
        $res =  $this->tb_exchange_rate->get_one_auto(["where"=>['currency'=>$currency]]);
//        $res =  $this->db->where('currency',$currency)->get('exchange_rate')->row_array();
        return $res;
    }


    /** 
     * 获取文章列表 (网站 关于我们 内容)
     * @param  integer $artical_id         [description]   新闻表内容 ID
     * @param  integer $show_app_news_type [description]
     * @return [type]                      [description]
     */
    function get_artical($artical_id=0,$show_app_news_type=1) {
    	
        $this->load->model('tb_news');
        
        $language_id=(int)$this->session->userdata('language_id');//获取语言 ID
        //$cate_info=$this->db->where('language_id',$language_id)->where('type_id !=',1125)->order_by('sort_order','asc')->get('news_type')->result_array();
    	$cate_info=$this->db_slave->where('language_id',$language_id)->where_not_in('type_id',array(1125,1131,1132,1133,1134))->order_by('sort_order','asc')->get('news_type')->result_array();

    	foreach($cate_info as $k=>$v) {
            //$cate_info[$k]['list']=$this->db_slave->select('title,id')->where('display',1)->where('language_id',$language_id)->where('cate_id',$v['type_id'])->order_by('sort','asc')->get('news')->result_array();
            $cate_info[$k]['list']= $this->tb_news->get_list('title,id',['display'=>1,'language_id'=>$language_id,'cate_id'=>$v['type_id']],array(),10,0,['sort'=>'asc']);
    	}

        //判断新闻内容 ID 
    	$artical_id = $artical_id ? $artical_id : (isset($cate_info[0]['list'][0]) ? $cate_info[0]['list'][0]['id'] : 0);
    	
        //右侧详情内容
        if($artical_id > 0) {
            //$cate_info['artical']=$this->db_slave->where('id',$artical_id)->get('news')->row_array();
            $cate_info['artical'] = $this->tb_news->get_one('*',['id'=>$artical_id]);//获取对应内容的新闻
    	}

    	foreach($cate_info as $val) {
    	    if(isset($val['list'])) {
        	    foreach($val['list'] as $art) {
        	        if($artical_id == $art['id']) {
        	            $cate_info['parent_id']=$val['type_id'];      //当前选中的内容ID
        	            $cate_info['parent_name']=$val['type_name'];  //当前选中的内容名字
        	        }
        	    }
    	    }
    	}
    	return $cate_info;
    }



    /** 得到所有可用的支付方式 */
    public function getPayments($is_currency = NULL ,$uid = '',$disabled = ''){
        if($disabled === 'm_amount'){
            $this->db->where('pay_code !=',$disabled);
        }

        /*韩国临时域名限定只能用现金池*/
        if(get_public_domain_port()=='shoptps.com'){
            $this->db->where('pay_code','m_amount');
        }
        if($disabled=='mall'){//报名费临时订单，会将此变量设为mall，以此获取报名费所需支付方式
            $this->db->where_in('pay_id',array('105','106','104','110'));
        }elseif($disabled=='mobile'){//报名费临时订单，会将此变量设为mobile，以此获取报名费所需支付方式
            $this->db->where_in('pay_id',array('105','104','110'));
        }
        if($is_currency){

            $row =  $this->db->query("select parent_id from users where id = $uid")->row_array();
            if(!$row['parent_id']){
                $this->db->where('pay_code !=','m_amount');
            }
            if(!$this->is_apply_ewallet($uid)){ //用戶沒有申請eWallet 去掉該支付方式
                $this->db->where('pay_code !=','m_ewallet');
            }
            return $this->db->where('is_enabled',1)->order_by('pay_order','asc')->get($this->table_name)->result_array();
        }else{
            return $this->db->where('is_enabled',1)->order_by('pay_order','asc')->get($this->table_name)->result_array();
        }
    }

    /** 通過pay_id得到支付方式 或 貨幣格式 */
    public function getPaymentById($pay_id,$is_currency = FALSE){
        if($is_currency){
            return $this->db->where('pay_id',$pay_id)->get($this->table_name)->row_array()['payment_currency'];
        }else{
            return $this->db->where('pay_id',$pay_id)->get($this->table_name)->row_array();
        }
    }

    /** 支付方式是否存在，启用 */
    public function isPaymentExist($pay_code){
        return $this->db->from($this->table_name)->where('pay_code',$pay_code)->where('is_enabled',1)->count_all_results();
    }

    /** 某個支付方式的配置項 */
    public function getPaymentConfig($pay_code){
        if($this->table_name=='mall_payment_new'){
            $this->load->model('tb_users');
            $tmp_config = $this->db->select('pay_config')->where('pay_code',$pay_code)->get($this->table_name)->row_array();
            if($tmp_config){
                $tmp_config = $tmp_config['pay_config'];
            }
            return unserialize_config($this->tb_users->AES_decryption($tmp_config));
        }  else {
            return unserialize_config($this->db->select('pay_config')->where('pay_code',$pay_code)->get($this->table_name)->row_array()['pay_config']);
        }
    }

    /** 用戶是否申請了Ewallet */
    public function is_apply_ewallet($uid){
        $row =  $this->db->query("select ewallet_name from users where id = $uid")->row_array();
        if($row['ewallet_name']){
            return TRUE;
        }else{
            return FALSE;
        }
    }

	/** 记录 订单推送到支付接口 的实付金额 */
	public function update_paid_amount($order){

//		$count = $this->db->from('trade_orders')->where('order_id',$order['order_sn'])->count_all_results();
        $this->load->model("tb_trade_orders");
        $count = $this->tb_trade_orders->get_counts(["order_id"=>$order['order_sn']]);
		if($count){
//			$this->db->where('order_id',$order['order_sn'])->update('trade_orders',array('format_paid_amount'=>$order['paid_amount']));//支付接口实付金额
            $this->tb_trade_orders->update_one_auto([
               "where"=>['order_id'=>$order['order_sn']],
                "data"=>array('format_paid_amount'=>$order['paid_amount'])
            ]);
		}
	}

	/** 得到国家code，显示语言 */
	public function get_country_code($clientIp){
		$ip_country = $this->db->select('country_code')->from('ip_address_info')->where('ip',$clientIp)->get()->row_array();
		if($ip_country){
			return $ip_country['country_code'];
		}else{
			return config_item('position_country_code');
		}
		return FALSE;
	}

	/** 判断如果是韩国订单，地址信息必须是韩文，不能是英文 */
	public function check_korea_address($uid,$address,$country){
		//2 等待付款；3 等待发货；4 等待收货；
//		$orders = $this->db->select('order_id,address,zip_code,customs_clearance')->where_in('status',array('1','2','3','4','5','6'))->where('area','410')->where('customer_id',$uid)->get('trade_orders')->result_array();
		$this->load->model("tb_trade_orders");
		$orders = $this->tb_trade_orders->get_list_auto([
		    "select"=>"order_id,address,zip_code,customs_clearance",
            "where"=>[
                "status"=>array('1','2','3','4','5','6'),
                'area'=>'410',
                'customer_id'=>$uid,
            ]
        ]);
		$str = '';
		if($orders)foreach($orders as $order){
			if(/*preg_match('/[a-zA-Z]{3,}/', $order['address']) ||*/ !$order['zip_code'] || !$order['customs_clearance']){
					$str ? $str .= ','.$order['order_id'] : $str.=$order['order_id'];
					continue;
			}
		}
		/*if($country == '3' && preg_match('/[a-zA-Z]{3,}/', $address)){
			$str .= ' Address ';
		}*/
		return $str;
	}

}
