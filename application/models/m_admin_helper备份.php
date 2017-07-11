<?php

class m_admin_helper extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getCardList($filter, $perPage = 10) {
        $this->db->from('user_id_card_info as id_card');
        $this->filterForUser($filter);
        return $this->db->order_by("id_card.check_status", "asc")->order_by("id_card.create_time", "asc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }
        
    public function up_card_status($uid,$data) {
        $this->db->where('uid',$uid)->update('user_id_card_info',$data);
        return $this->db->affected_rows();
    }

    public function filterForUser($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('id_card.create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('id_card.create_time <=', strtotime($v)+86400-1);
                    break;
				case 'check_start':
                    $this->db->where('id_card.check_time >=', strtotime($v));
                    break;
                case 'check_end':
                    $this->db->where('id_card.check_time <=', strtotime($v)+86400-1);
                    break;
                case 'idEmail':
                    if(is_numeric($v)){
                        $this->db->where('id_card.uid', $v);
                    }else{
                        $this->db->where('id_card.name',$v);
                    }
                    break;
                case 'id_card_num':
                    $this->db->where('id_card.id_card_num', $v);
                    break;
                case 'check_status':
                    if($v == 5){
                        break;
                    }
                    if($v == 4 ){
                        $this->db->where('id_card.check_status', 0)->where('id_card.check_time','0');
                    }else if($v == 0){
                        $this->db->where('id_card.check_status', 0)->where('id_card.check_time !=','0');
                    }
                    else{
                        $this->db->where('id_card.check_status', $v);
                    }
                    break;
				case 'admin_id':
                    if($v == -1){
                        $this->db->where("id_card.check_admin",'system');
                    }else{

                        $this->db->where("(id_card.check_admin_id='$v' or id_card.check_admin='$v')");
                    }
                    break;
				case 'country_id':
					$this->db->select('id_card.*');
					$this->db->join('users as u','u.id=id_card.uid','left');
					$this->db->where('u.country_id',$v);
					break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

	public function getPcCardList($filter, $perPage = 10) {
		$this->db->from('users_prepaid_card_info');
		$this->filterForPcCard($filter);
		return $this->db->order_by("create_time", "asc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}
	public function filterForPcCard($filter){
		foreach ($filter as $k => $v) {
			if ($v == '' || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'start':
					$this->db->where('create_time >=', ($v));
					break;
				case 'end':
					$this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
					break;
				case 'ID_no':
					$this->db->where('ID_no', $v);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	public function getPcCardListRows($filter) {
		$this->db->from('users_prepaid_card_info');
		$this->filterForPcCard($filter);
		return $this->db->count_all_results();
	}

    public function getCardListRows($filter) {
        $this->db->from('user_id_card_info as id_card');
        $this->filterForUser($filter);
        return $this->db->count_all_results();
    }
    public function getDeleteUsersList($filter, $perPage = 10) {
        $this->db->from('delete_users_logs');
        $this->filterForDeleteUser($filter);
        $res = $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
		if(empty($res)){
			return $res;
		}
		foreach($res as &$item){
			$row = $this->db->select('email')->from('admin_users')->where('id',$item['admin_id'])->get()->row_array();
			if(!empty($row)){
				$item['check_admin'] = $row['email'];
			}else{
				$item['check_admin'] ='';
			}

			//会员自己删除的记录
			if($item['admin_id'] == 0){
                $item['check_admin'] =lang('user_oneself_del');
            }
		}
		return $res;
    }

	public function getPayPalList($filter, $perPage = 10) {
		$this->db->from('mall_orders_paypal_refund');
		$this->filterForPayPal($filter);
		return $this->db->order_by("id", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}
	public function filterForPayPal($filter){
		foreach ($filter as $k => $v) {
			if ($v == '' || $k=='page') {
				continue;
			}
			switch ($k) {
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	public function getPayPalRows($filter) {
		$this->db->from('mall_orders_paypal_refund');
		$this->filterForPayPal($filter);
		return $this->db->count_all_results();
	}

	public function getStorehouseList($filter, $perPage = 10) {
		$this->db->from('mall_goods_storehouse');
		$this->filterForPayPal($filter);
		return $this->db->order_by('store_code','asc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}
	public function filterForStorehouse($filter){
		foreach ($filter as $k => $v) {
			if ($v == '' || $k=='page') {
				continue;
			}
			switch ($k) {
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	public function geStorehouseRows($filter) {
		$this->db->from('mall_goods_storehouse');
		$this->filterForPayPal($filter);
		return $this->db->count_all_results();
	}

	public function getTransferUsersList($filter, $perPage = 10) {
		$this->db->from('user_transfer_refund_logs');
		$this->filterForDeleteUser($filter);
		$res = $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
		if(empty($res)){
			return $res;
		}
		foreach($res as &$item){
			$row = $this->db->select('email')->from('admin_users')->where('id',$item['check_admin'])->get()->row_array();
			if(!empty($row)){
				$item['check_admin'] = $row['email'];
			}else{
				$item['check_admin'] ='';
			}
		}
		return $res;
	}

    public function filterForDeleteUser($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }


    public function getDeleteUsersRows($filter) {
        $this->db->from('delete_users_logs');
        $this->filterForDeleteUser($filter);
        return $this->db->count_all_results();
    }

	public function getTransferUsersRows($filter) {
        $this->db->from('user_transfer_refund_logs');
        $this->filterForDeleteUser($filter);
        return $this->db->count_all_results();
    }
    public function getPaymentList($filter, $perPage = 10) {
        $this->db->from('mall_payment');
        return $this->db->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    public function getPaymentRows() {
        $this->db->from('mall_payment');
        return $this->db->count_all_results();
    }

    public function updateCheckCardStatus($uid,$data){
        $this->db->where('uid',$uid)->update('user_id_card_info',$data);
        return $this->db->affected_rows();
    }

    public function getCardOne($uid) {
        return $this->db->from('user_id_card_info')->where('uid',$uid)->get()->row_array();
    }

    /** id card num 唯一性 by john 2015-7-7*/
    public function uniqueIdCardNum($num){
        return $this->db->from('user_id_card_info')->where('id_card_num',$num)->count_all_results();
    }

    public function getUpgradeList($filter, $perPage = 10) {
        $this->db->from($filter['table']);
        $this->filterForUpgrade($filter);
        return $this->db->order_by('create_time','desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    public function filterForUpgrade($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page' || $k=='table') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', strtotime($v)+86400-1);
                    break;
                case 'idEmail':

                    $this->db->where('uid', $v);
                    break;
                case 'order_sn':
                    $this->db->where('order_sn', $v);
                    break;
                case 'status':
                    $this->db->where('status', $v);
                    break;
                case 'txn_id':
                    $this->db->where('txn_id', $v);
                    break;
                case 'payment_method':
                    if($v != 'manually'){
                        $this->db->where('payment', $v);
                    }else{
                        $this->db->where('payment like', '%'.$v);
                    }
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }


    public function getUpgradeListRows($filter) {
        $this->db->from($filter['table']);
        $this->filterForUpgrade($filter);
        return $this->db->count_all_results();
    }

    public function getWithdrawalList($filter, $perPage = 10) {
        $this->db->select('c.*,u.name')->from('cash_take_out_logs c')->join('users u','u.id=c.uid')->where('c.take_out_type <>',2);
        $this->filterForWithdrawal($filter);
        return $this->db->order_by('c.create_time','asc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    /** 月费变动记录 */
    public function getMonthlyFeeListRows($filter,$uid = 0) {
        $this->db->from('month_fee_change');
		if($uid != 0){
			$this->db->where('user_id', $uid);
		}
        $this->filterForMonthlyFee($filter);
        return $this->db->count_all_results();
    }
    public function filterForMonthlyFee($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                case 'idEmail':
                    $this->db->where('user_id', $v);
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }
    public function getMonthlyFeeList($filter, $uid = 0 ,$perPage = 10) {
        $this->db->from('month_fee_change');
		if($uid != 0){
			$this->db->where('user_id', $uid);
		}
        $this->filterForMonthlyFee($filter);
        return $this->db->order_by('create_time','desc')->order_by('id','desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

	public function filterForPrepaid($filter){
		foreach ($filter as $k => $v) {
			if ($v == '' || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'start':
					$this->db->where('create_time >=', ($v));
					break;
				case 'end':
					$this->db->where('create_time <=', ($v));
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/** 导出预付卡 */
	public function exportPrepaidCard($filter){
		unset($filter['is_export_lock']);
		$this->db->select('card_no,create_time,name,ID_no,chinese_name,mobile,email,country,nationality,issuing_country,ship_to_address,uid')->from('users_prepaid_card_info');
		$this->filterForPrepaid($filter);
		return $this->db->order_by('create_time','asc')->get()->result_array();
	}

    /** 导出提现记录 */
    public function exportWithdrawalList($filter) {
        $this->db->select('c.uid,u.name,c.amount,c.account_bank,,c.subbranch_bank,c.card_number,c.account_name,c.status,c.create_time,u.country_id,u.address')->from('cash_take_out_logs c')->join('users u','u.id=c.uid');
        $this->filterForWithdrawal($filter);
        return $this->db->order_by('c.create_time','asc')->get()->result_array();
    }

	/** 导出订单记录 */
    public function exportOrderReport($filter,$admin_id) {

        $this->load->model('m_erp');
		$date = date('Y-m-d H:i:s');
		$where_cate = $where_brand = $store_code = "";
		if(!empty($filter['cate_id'])){
			$cate = $this->db->query('select cate_id from mall_goods_category where cate_sn="'.$filter['cate_id'].'"')->result_array();
			foreach($cate as $v){
				$cate_id[] = $v['cate_id'];
			}
			$cate = implode(',',$cate_id);
			$where_cate = " cate_id in ($cate)";
		}

		if(isset($filter['brand']) && $filter['brand']){
			$goods_arr = array(
				'jbb'=>array('04102940',10859170,98935069,23447653,'02853333',58622138,23464934),
				'jianfei'=>array(79646329,45896682),
				'jiaonang'=>array(18873959,48438394),
				'water'=>array(29341368),
				'family_water'=>array(61194623),
				'Insight_Eye'=>array(12790455),
				'nopal'=>array(95831589,42938001,11663452,85222155,97138869,30957403,54223289,51518543,46793200,07736510),
				'Ginseng'=>array('06132860'),
				'Dr_Cell'=>array('55940738'),
				'Seng_Seng_Dan'=>array('37536265'),

				'99'=>array(23422805,62261180,27123768,48771923),
				'Silverloy'=>array(85166203,38577628,20818749,21449770,59712755,47752547,28618754,67472829,41935664,68859622,93209539),
				'Filma'=>array(24000777,84773078),
				'Primaco'=>array(64453970,38293765),
				'rihua'=>array(93092597,'00554529',78996361,18525453,'09031455'),
				'xinxilan'=>array('05792653',35375156,78564261,95332310,63815989,49463861,68536639,35633707,55958814,67242115,85263165,62185709),
				'baojian'=>array(79537698,45099126,76042416),
				'xiangzao'=>array(35071627),
				'jiu1'=>array(49798215,42886008,26683448),
				'jiu2'=>array(22314123,79164118,82989802,51192963,49062800),
				'jiu3'=>array(92362370,28233335),
				'paopao'=>array(13483197),
				'ciliao'=>array('04373851',77998297,12802211),
				'tea'=>array('33850872',94894961,66433165),
				'nopal_suit'=>array('07736510','85222155','30957403','97138869'),
				'gongzai'=>array(77269497,54665995,39023343,75995748,8260431781517556,12776498,51724890,75759086,23146264,53175518,58855611,81762437,23880690,12610845,76454076,48421291,37797539,32479384,32890850,53883549,55820884,80576463,33645280,14764050,86198412,78489417,16354585,55040338,57799324,46947403,85559680,87306642,65868183,12920156,96508649,11691314,48341888,40752844,68044952,51020143,39625550,28440617,16836994,85628937,55302844,45901889,80182633),
			);
			$goods_all = array();
			foreach($filter['brand'] as $v){
				$brand = isset($goods_arr[$v]) ? $goods_arr[$v] : array();
				$goods_all = array_merge($goods_all,$brand);
			}
			$brand_str = implode(',',$goods_all);

			$where_brand = " goods_sn_main in ($brand_str)";
		}

		if(isset($filter['store_code_arr']) && $filter['store_code_arr']){
			//$store_code = $filter['store_code_arr'];
			$store_code_arr = explode(',',$filter['store_code_arr']);
			$store_count = count($store_code_arr);
			$store_code = '';
			if($store_count == 1){
				$store_code = '"'.$filter['store_code_arr'].'"';
				$store_code_str = '='.$store_code;
			}
			else{
				foreach($store_code_arr as $k=>$v){

					if($store_count-1 == $k){
						$store_code .= '"'.$v.'"';
					}else{
						$store_code .= '"'.$v.'",';
					}
				}
				$store_code_str = 'in ('.$store_code.')';
			}
		}

		if($where_cate || $where_brand || $store_code){
			$where_and = $where_cate && $where_brand ? 'and': '';
			if(isset($filter['store_code_arr']) && $filter['store_code_arr']){
				$sql = "select tos.pay_time,tos.consignee,tos.customer_id,tos.order_id,tos.address,tos.country_address,tos.customs_clearance,tos.zip_code,tos.phone,tos.reserve_num,tos.freight_info,tos.deliver_time,tos.remark,tos.shipper_id,tos.ID_no,tos.ID_front,tos.ID_reverse,tos.order_type
							from (select distinct order_id from trade_orders_goods tosg,mall_goods_main mgm where tosg.goods_sn_main=mgm.goods_sn_main and mgm.shipper_id $store_code_str";
				if($where_cate){
					$sql .= " and tosg.$where_cate";
				}
				if($where_brand){
					$sql .= " and tosg.$where_brand";
				}
				$sql .= ") tosg,trade_orders tos
							where tos.order_id=tosg.order_id and tos.order_prop in ('0','1')";
			}
			if(!empty($filter['area'])){
				$where_area = implode(',',$filter['area']);
				$sql .= " and tos.area in ($where_area)";
			}
			if(!empty($filter['status'])){
				if($filter['status'] == 66){
					$sql .= " and tos.status in ('4','5','6')";
				}else{
					$sql .= " and tos.status='".$filter['status']."'";
				}
			}
            //订单类型
            if(isset($filter['order_type']) && $filter['order_type'] ){
                $sql .= " and tos.order_type=".$filter['order_type'];
            }
			if(isset($filter['start_date']) && $filter['start_date'] ){
				$start_date = date("Y-m-d H:i:s", strtotime($filter['start_date']));
				$sql .= " and tos.pay_time >='".$start_date."'";
			}
			if(isset($filter['end_date']) && $filter['end_date'] ){
				$end_date = date("Y-m-d H:i:s", strtotime($filter['end_date']) + 86400 - 1);
				$sql .= " and tos.pay_time <='".$end_date."'";
			}
			if(isset($filter['start_deliver_date']) && $filter['start_deliver_date'] ){
				$start_deliver_date = date("Y-m-d H:i:s", strtotime($filter['start_deliver_date']));
				$sql .= " and tos.deliver_time >='".$start_deliver_date."'";
			}
			if(isset($filter['end_deliver_date']) && $filter['end_deliver_date'] ){
				$end_deliver_date = date("Y-m-d H:i:s", strtotime($filter['end_deliver_date']) + 86400 - 1);
				$sql .= " and tos.deliver_time <='".$end_deliver_date."'";
			}
			if(isset($filter['select_is_export_lock']) && $filter['select_is_export_lock'] !== "" ){
				$sql .= " and tos.is_export_lock=".$filter['select_is_export_lock'];
			}
			$data = $this->db->query($sql)->result_array();
		}

		$wrap = $filter['ext'] === 'html' ? "<br>" : "\n";

		$is_export_lock = FALSE;
		if(isset($filter['is_export_lock']) && $filter['is_export_lock'] == 1 ){
			$is_export_lock = TRUE;
		}

		$count_goods = $update_data = $insert_arr = array();

		foreach($data as &$item){

			// 商品信息
			$goods = $this->db->select('goods_name,goods_sn,goods_sn_main,goods_number,goods_attr')->where('order_id',$item['order_id'])->get('trade_orders_goods')->result_array();
			$goods_name = '';
			foreach ($goods as $v)
			{
				// 获取商品主要信息
				$goods_main_info = $this->db
					->select('goods_name,add_time')
					->from('mall_goods_main')
					->where('goods_sn_main', $v['goods_sn_main'])
					->where('language_id', $filter['language_id'])
					->get()
					->row_array();
				if (empty($goods_main_info))
				{
					log_message('error', "[get_order_data] get goods main info failed, goods_sn_main: {$v['goods_sn_main']}");
					$goods_main_info = $this->db
						->select('goods_name,add_time')
						->from('mall_goods_main')
						->where('goods_sn_main', $v['goods_sn_main'])
						->get()
						->row_array();
					if (empty($goods_main_info))
					{
						continue;
					}
				}

				$goods_name .=  html_entity_decode($goods_main_info['goods_name']).' * '.$v['goods_number']."$wrap";
				$goods_name .= $v['goods_attr'] ? $v['goods_attr']."$wrap":'';
                                $vgoods_sn=trim($v['goods_sn']);
				if(isset($count_goods[$vgoods_sn])){
					$count_goods[$vgoods_sn]['count'] = $count_goods[$vgoods_sn]['count']+$v['goods_number'];
				}else{
					$count_goods[$vgoods_sn]['count'] = $v['goods_number'];
					$attr = $v['goods_attr'] ? $v['goods_attr']."$wrap":'';
					$count_goods[$vgoods_sn]['name'] = html_entity_decode($goods_main_info['goods_name']).$attr;
					$count_goods[$vgoods_sn]['add_time'] = $goods_main_info['add_time'];
				}

			}

			$item['goods_list'] = $goods_name;
			$item['phone'] = ' '.$item['phone'];
			$item['phone'] = $item['reserve_num'] ? $item['phone'].'/'.$item['reserve_num'] : $item['phone'];
			if($item['freight_info']){
				$freight_info = explode('|',$item['freight_info']);
				$item['freight_info'] = " ".$freight_info[1];
				//$freight_info[0] = $this->m_user_helper->getFreightName($freight_info[0]);
			}
			$remarks = $this->db
				->select("remark,created_at,type")
				->where('order_id', $item['order_id'])
				->get('trade_order_remark_record')
				->result_array();
			$sys_remark = "";
			$cus_remark = "";
			if($remarks)foreach($remarks as $remark){
				if($remark['type'] == '1'){
					$sys_remark.= $remark['remark']."$wrap";
				}else if($remark['type'] == '2'){
					$cus_remark.= $remark['remark']."$wrap";
				}
			}
			$item['sys_remark'] = $sys_remark;
			$item['cus_remark'] = $item['remark']."$wrap".$cus_remark;

			if($is_export_lock && $item['shipper_id'] != 1){ //鎖定訂單數據
                $update = array(
                    'order_id'=>$item['order_id'],
                    'is_export_lock'=>1,
                );
                if($filter['status'] == 3){ //待發貨-》正在發貨中
                    $update['status'] ='1';
                    $insert_arr[] = array(
                        'order_id' => $item['order_id'],
                        'oper_code' => 108,
                        'statement' => "订单导出：待发货->正在发货中",
                        'operator_id' => $admin_id,
                        'update_time' => $date,
                    );

                    //同步到erp(正在发货中)
                    $update_attr = array('order_id' => $item['order_id'],'status'=>Order_enum::STATUS_INIT);
                    $this->m_erp->update_order_to_erp_log($update_attr);
                }
                $update_data[] = $update;
            }
            //订单类型的文字显示
            if($item['order_type'] == 1){
                $item['order_type'] = lang('choose_group');
            }elseif($item['order_type'] == 2){
                $item['order_type'] = lang('admin_as_upgrade_order');
            }elseif($item['order_type'] == 3){
                $item['order_type'] = lang('generation_group');
            }elseif($item['order_type'] == 4){
                $item['order_type'] = lang('retail_group');
            }elseif($item['order_type'] == ''){
                $item['order_type'] = '';
            }
		}
		if($is_export_lock && !empty($update_data)){ //批量鎖定
			$this->db->update_batch('trade_orders',$update_data,'order_id');
		}
		if($is_export_lock && !empty($insert_arr)){ //批量加订单流水
			$this->db->insert_batch('trade_orders_log',$insert_arr);
		}

		$name_str = "";
		if(isset($store_code_str)){
			$names = $this->db->query("select supplier_name from mall_supplier where supplier_id $store_code_str")->result_array();
			if($names)foreach($names as $key=>$name){
				if($key == 0){
					$name_str .= $name['supplier_name'];
				}else{
					$name_str .= '+'.$name['supplier_name'];
				}
			}
		}

		return array('data'=>$data,'count_goods'=>$count_goods,'name_str'=>$name_str);
    }

    /** 批量得到提現記錄 */
    public function getWithdrawalByIds($ids){
        $res = $this->db->from('cash_take_out_logs')->where_in('id', $ids)->get()->result_array();
        return $res;
    }
    /** 批量修改提現記錄 */
    public function updateWithdrawalByIds($ids,$status){
        $this->db->where_in('id', $ids)->update('cash_take_out_logs',array('status'=>$status));
        return $this->db->affected_rows();
    }

    public function filterForWithdrawal($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('c.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('c.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                case 'idEmail':
                    $this->db->where('c.uid', $v);
                    break;

				case 'country_id':
                    $this->db->where('u.country_id', $v);
                    break;

                case 'status':
                    $this->db->where('c.status', $v);
                    break;
				case 'name':
                    $this->db->where("u.name like '%$v%'");
                    break;

                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }


    public function getWithdrawalListRows($filter) {
		$this->db->select('c.*,u.name')->from('cash_take_out_logs c')->join('users u','u.id=c.uid')->where('c.take_out_type <>',2);
        $this->filterForWithdrawal($filter);
        return $this->db->count_all_results();
    }

    /** 檢查公告欄排序sort */
    public function checkSort($sort){
        $check = $this->db->from('bulletin_board')->where('sort',$sort)->get()->row_array();
        return $check ? TRUE : FALSE;
    }

    /** 檢查用戶是否提現了 */
    public function isWithdrawal($id){
        $res = $this->db->from('cash_take_out_logs')->where('uid',$id)->get()->row_array();
        return $res ? TRUE : FALSE;
    }
    /** 檢查用戶是否轉賬了 */
    public function isTransfer($id){

    	$this->load->model('o_cash_account');
    	$res = $this->o_cash_account->getUserTransferAmount($id);
        return $res>0?TRUE:FALSE;
    }

   	/** 发送驳回提现的提醒邮件 */
	public function sendRejectWithdrawalEmail($info,$email){

		$data['email'] = $email;
		$data['content'] = '您的提现申请被驳回，原因：<br>'.$info;
		$data['dear'] = lang('dear_');
		$data['email_end'] = lang('email_end');
		$content = $this->load->view('ucenter/public_email',$data,TRUE);
		send_mail($email, 'TPS提现申请驳回通知', $content);
	}

	/** 发送提现处理成功邮件 */
	public function sendProcessedWithdrawalEmail($email,$country_id) {

		foreach(config_item('supportLanguage') as $v){
			$lanFileKey = array_search("ucenter_base_lang.php",$this->lang->is_loaded);
			if($lanFileKey!==false){
				unset($this->lang->is_loaded[$lanFileKey]);
			}
			$this->lang->load('ucenter_base',$v);
			$return[$v] = array(
				'admin_withdrawal_success_content' => lang('admin_withdrawal_success_content'),
				'admin_withdrawal_success_title' => lang('admin_withdrawal_success_title'),
				'dear' => lang('dear_'),
				'email_end' => lang('email_end'),
			);
		}
		if($country_id==1){
			$lang = 'zh';
		}elseif($country_id==4){
			$lang = 'hk';
		}else{
			$lang = 'english';
		}
		//$return[$lang];
		$data['email'] = $email;
		$data['content'] = '-------------------------------------</br>'.$return[$lang]['admin_withdrawal_success_content'].'</br>-------------------------------------</br>';
		$data['dear'] = $return[$lang]['dear'];
		$data['email_end'] = $return[$lang]['email_end'];
		$content = $this->load->view('ucenter/public_email',$data,TRUE);

		send_mail($email, $return[$lang]['admin_withdrawal_success_title'], $content.'<br/>');
	}

    /** 得到轉讓記錄 */
    public function getTransferLog($uid){
        return $this->db->from('user_transfer_refund_logs')->where('uid',$uid)->where('type',1)->order_by('id','desc')->get()->row_array();
    }

	/**
	 * 删除免费会员
	 * @param $uid
	 * @param $userInfo
	 */
	public function deleteUserById($uid,$userInfo){
        $uid=(int)$uid;
		$this->db->where('id',$uid)->delete('users');
		$this->db->where('uid',$uid)->delete('user_id_card_info');
		/** 父類的child_count  -1 */
		$parent_ids = explode(',',$userInfo['parent_ids']);
		array_pop($parent_ids);//去掉最後的公司號
		if($parent_ids && $userInfo['status']){ /** 如果会员激活过并且有父类　*/
			foreach($parent_ids as $parent_id){
				//如果上级的child_count 为0 ，则重新统计再减一
				$this->load->model('m_user');
				$userInfo = $this->m_user->getUserByIdOrEmail($parent_id);
				if($userInfo['child_count'] == 0){
					//所有的直推人统计
                    $sql="select id,user_rank,month_fee_rank from users where parent_id=".$parent_id;
                    $children=$this->db->query($sql)->result();
                    $QSO_count = 0;
                    if(!empty($children)){
                        foreach($children as $value){
                                ++$QSO_count;
                        }
                    }
					if($QSO_count == 0){
						$QSO_count = 1;
					}
                    $this->db->where('id',$parent_id)->set('child_count',$QSO_count - 1,FALSE)->update('users');
				}else{
					$this->db->where('id',$parent_id)->set('child_count','child_count - 1',FALSE)->update('users');
				}
			}
			/** 父类的直推人统计 */
			$this->load->model('m_referrals_count');
			$this->m_referrals_count->delete_referrals_count($userInfo['parent_id'],$userInfo['enable_time']);
		}
	}

    function deleterUserLogs($uid,$parent_id,$admin_id){
        $this->db->insert('delete_users_logs',array(
            'uid'=>$uid,
            'parent_id'=>$parent_id,
            'admin_id'=>$admin_id,
        ));
    }

	/** 用户 佣金 返补 */
	function do_return_back($uid,$admin_id,$start){

		$this->load->model('o_cash_account');

		$count = $this->db->from('users')->where('id',$uid)->count_all_results();
		if($count == 0 ){
			return array('success'=>0,'msg'=>lang('no_exist'));
		}
		$is_process = $this->o_cash_account->getCashAccountLogNum(array('item_type'=>21,'related_uid'=>$uid));
		if($is_process){
			return array('success'=>0,'msg'=>lang('no_operate'));
		}
		$reduce_logs = $this->db->where('uid',$uid)->where('create_time >',$start)->get('user_reduce_commission_logs')->result_array();
		if($reduce_logs){
			$this->db->trans_start();
			foreach($reduce_logs as $reduce_log){
				$this->o_cash_account->createCashAccountLog(array(
	                'uid'=>$reduce_log['uid'],
	                'item_type'=>21,
	                'amount'=>tps_int_format($reduce_log['amount']*100),
	                'related_uid'=>$reduce_log['pay_user_id']
	            ));
				$this->db->where('id', $reduce_log['uid'])->set('amount', 'amount+' . $reduce_log['amount'], FALSE)->update('users');
			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				return array('success'=>0,'msg'=>lang('try_again'));
			}else{
				$this->m_log->adminActionLog($admin_id,'admin_return_back','commission_logs|users',$uid,
					'','','');
				return array('success'=>1,'msg'=>count($reduce_logs).'行抽回记录'.lang('submit_success'));
			}
		}else{
			return array('success'=>0,'msg'=>lang('no_operate'));
		}
	}

    //获取重置套装记录
    public function get_reset_list($filter, $perPage = 10) {
        $this->db->from('reset_group_log');
        $this->filterForDeleteUser($filter);
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取重置套装记录行数
    public function get_reset_group_rows($filter) {
        $this->db->from('reset_group_log');
        $this->filterForDeleteUser($filter);
        return $this->db->count_all_results();
    }

    //获取代品券管理日志
    public function get_coupons_manage_list($filter, $perPage = 10) {
        $this->db->from('voucher_manage_logs');
        $this->filterForDeleteUser($filter);
		$list = $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
		$adminlist = $this->db->from('admin_users')->get()->result_array();
		foreach($list as $k=>$adminid){
			foreach($adminlist as $email){
				if($adminid['admin_id'] == $email['id']){
					$list[$k]['admin_id'] = $email['email'];
				}
			}
		}
		return $list;
    }

    //获取代品券管理日志行数
    public function get_coupons_manage_rows($filter) {
        $this->db->from('voucher_manage_logs');
        $this->filterForDeleteUser($filter);
        return $this->db->count_all_results();
    }

    //获取订单流水记录
    public function get_trade_orders_log($filter, $perPage = 10) {
        $this->db->from('trade_orders_log');
        $this->filterForOrderLog($filter);

        return $this->db->order_by("update_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取订单流水记录行数
    public function get_trade_orders_log_rows($filter) {
        $this->db->from('trade_orders_log');
        $this->filterForOrderLog($filter);
        return $this->db->count_all_results();
    }

    //获取跨区运费记录
    public function get_freight_log($filter, $perPage = 10) {
        //联接查询
        $this->db->select('tf.*,au.email')->from('trade_freight_fee_international tf')->join('admin_users au','tf.admin_id = au.id');
        $this->filterForFreightLog($filter);

		$list = $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
		$country = array("156", "840", "410", "344","000");
		foreach($list as $k=>$v){
			if(!in_array($v['country_id'],$country)){
				$list[$k]['country_id'] = '000';
			}
		}
        return $list;
    }

    //获取跨区运费记录行数
    public function get_freight_log_rows($filter) {
        $this->db->from('trade_freight_fee_international tf');
        $this->filterForFreightLog($filter);
        return $this->db->count_all_results();
    }

    //过滤跨国运费时间
    public function filterForFreightLog($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('tf.create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('tf.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    //---------------------------------------------------------------------------------------------

    //获取sql执行记录
    public function get_execute_sql_log($filter, $perPage = 10) {
        //联接查询
        $this->db->from('execute_sql_log es');
        $this->filterForSqlLog($filter);

        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取sql执行记录行数
    public function get_execute_sql_log_rows($filter) {
        $this->db->from('execute_sql_log es');
        $this->filterForSqlLog($filter);
        return $this->db->count_all_results();
    }

    //过滤sql执行时间
    public function filterForSqlLog($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'status':
                    $this->db->where('es.status =', ($v));
                    break;
                case 'start':
                    $this->db->where('es.audit_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('es.audit_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    //---------------------------------------------------------------------------------


    //过滤时间
    public function filterForOrderLog($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('update_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('update_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }
//---------------------------------------------------------------------------------------审核身份证
    //获取待审核身份证列表
        public function getCardListChina($check_status) {
            $cronName = 'card_list_ck';
            $cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
            if($cron){
                if($cron['false_count'] > 3){
                    $this->db->delete('cron_doing', array('cron_name' => $cronName));
                    return false;
                }
                $this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
                return false;
            }
            
            $this->db->insert('cron_doing',array(
                'cron_name'=>$cronName
            ));
            
            $this->db->select('c.*,u.country_id'); //联表查询字段
            $this->db->from('user_id_card_info as c');
            $this->db->join('users as u', 'u.id=c.uid', 'left');
            $this->db->where('c.check_status',$check_status);
            $this->db->where('u.country_id',1);
            require_once APPPATH . 'third_party/cardAPI/CardAPI.php';
            $demo = new CardAPI();
            $qz_url = config_item('img_server_url') . '/';
            if($check_status){
                $data = $this->db->limit(10)->order_by("create_time", "asc")->get()->result_array();
            }  else {
                $this->db->where('c.check_admin','system');
                $data = $this->db->like('c.id_card_num', 'x', 'before')->order_by("create_time", "asc")->get()->result_array();
            }
            foreach ($data as $k => $value) {
                if (@fopen($qz_url . $value['id_card_scan'], 'r') && @fopen($qz_url . $value['id_card_scan_back'], 'r')) {
                    $zmian=$this->tp_cl($qz_url . $value['id_card_scan'],$value['uid']);
                    $fmian=$this->tp_cl($qz_url . $value['id_card_scan_back'],$value['uid'].'fm');
                    $jg = $demo->doPostString($zmian,$fmian); #身份证正反面类型: face/back
                    unset($zmian);unset($fmian);
                    $this->bd_card((array) json_decode($jg), $value);
                }  else {
                    $this->db->where('uid',$value['uid'])->update('user_id_card_info',array('check_status' => 0,'check_admin'=>'system', 'check_time' => time(), 'check_info' =>  '图片不全'));
                }
            }
             $this->db->delete('cron_doing', array('cron_name' => $cronName));
        }
        
    //比对身份证和填写资料是否无误
    public function bd_card($jg, $value) {
        $zm = (array) json_decode($jg['outputs'][0]->outputValue->dataValue);
        $bm = (array) json_decode($jg['outputs'][1]->outputValue->dataValue);
        $check_info = 0;
        if(!empty($zm['success'])&&!empty($bm['success'])){
            $check_info+=1;
        }
        if ($zm['name'] == str_replace(" ", "", $value['name'])) {
            $check_info+=1;
        }
        if ((strtolower($zm['num']) === strtolower($value['id_card_num']))&&(date('Y-m-d',strtotime("-18 year"))>=date('Y-m-d',strtotime(substr($zm['num'], 6, 8))))) {//大小写要统一
            $check_info+=1;
        }
        if (isset($bm['end_date'])) {
            if ($bm['end_date'] == '长期' || strtotime($bm['end_date']) > time()) {
                $check_info+=1;
            } 
        }
        if ($check_info == 4) {
            $this->db->where('uid',$value['uid'])->update('user_id_card_info',array('check_status' => 2,'check_admin'=>'system', 'check_info' =>'', 'check_time' => time()));
            $is = $this->db->affected_rows();
            if ($is) {
                $uid = $value['uid'];
                $this->load->model('m_user');
                $this->m_user->addInfoToWohaoSyncQueue($uid, array(0));
                /*                 * 如果身份审核通过,进去138矩阵* */
                $this->load->model('m_forced_matrix');
                    $this->m_forced_matrix->save_user_for_138($uid);
                    /* 对于没有拿过138奖金的,如果这个月满足了条件，则立刻加入138合格列表 */
                    $this->m_forced_matrix->join_qualified_for_138($uid);
            }
        } else {
            $this->db->where('uid',$value['uid'])->update('user_id_card_info',array('check_status' => 0,'check_admin'=>'system', 'check_time' => time(), 'check_info' =>'证件与填写的信息不一致，或者身份证不清晰或者未满18岁'));
        }
    }

    //对图片压缩尺寸，base64编码
    public function tp_cl($filename,$name) {
        //取得源图片的宽度和高度
        list($width, $height) = getimagesize($filename);
        $w = $width;
        $h = $height;
        $max = 500;
        if ($width > $height) {
            $w = $max;
            $h = $h * ($max / $width);
        } else {
            $h = $max;
            $w = $w * ($max / $height);
        }
        $image_p = imagecreatetruecolor($w, $h);
        $info=getimagesize($filename);
            switch ($info[2])     
            {     
                case 1:     
                    $image=imagecreatefromgif($filename);     
                    break;     
                case 2:     
                    $image=imagecreatefromjpeg($filename);     
                    break;     
                case 3:     
                    $image=imagecreatefrompng($filename);     
                    break;     
            }
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width, $height);
        if (!is_dir('upload/card/')) {
			mkdir('upload/card/', DIR_WRITE_MODE); // 使用最大权限0777创建文件
		}
        $new_file = "upload/card/$name.png";
        imagejpeg($image_p, $new_file, 100);
        imagedestroy($image_p);
        unset($image);
        $fp = fopen($new_file, 'r');
        if ($fp) {
            $base64_img_string = chunk_split(base64_encode(fread($fp, filesize($new_file))));
            fclose($fp);
        }
        unlink($new_file);
        return $base64_img_string;
    }

    
}
