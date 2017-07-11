<?php

class m_coupons extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_suite_exchange_coupon');
		$this->load->model('m_group');
	}

	/**
	 * 检查用户使用过多少代品券是否大于加盟费本身
	 */
	public function get_use_coupons_sum($uid){

//		$sql = "select sum(discount_amount_usd) count from trade_orders where payment_type='2' and discount_type='2'
//		and status in ('1','3','4','5','6') and order_prop in ('0','1') and customer_id=".$uid;
//		$use_result = $this->db->query($sql)->row_array();
//        $use_sum = $use_result['count'] ? $use_result['count']/100 : 0;

		$this->load->model("tb_trade_orders");
        $use_result = $this->tb_trade_orders->get_sum_auto([
            "column"=>"discount_amount_usd",
            "where"=>[
                "payment_type"=>'2',
                "discount_type"=>'2',
                "status"=>['1','3','4','5','6'],
                "order_prop"=>["0","1"],
                "customer_id"=>$uid,
            ]
        ]);
        $use_sum = $use_result['discount_amount_usd'] ? $use_result['discount_amount_usd']/100 : 0;

		$remain =  $this->get_coupons_list($uid)['total_money'];

		$total = $use_sum + $remain;

		$this->load->model('m_user');
		$user = $this->m_user->getUserByIdOrEmail($uid);
		$is_true = $this->m_user->is_first_upgrade_time_1_1($uid);
		if($is_true && !in_array($user['id'],array('1380149334'))){
			$point = config_item('old_join_fee_and_month_fee');
		}else{
			$point = $this->m_user->getJoinFeeAndMonthFee();
		}
		$product_set = $point[$user['user_rank']]['join_fee'];

		if($total > $product_set+250){
			return false;
		}else{
			return true;
		}

	}

	/**
	 * 获得总金额和总数量
	 */
	public function get_coupons_list($user_id)
	{
        $this->load->model('m_suite_exchange_coupon');
		$coupons_info = $this->m_suite_exchange_coupon->getAll($user_id);
		$coupons_total_num = 0;
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
			$coupons_total_num += $v;
			$coupons_total_money += $v * $k;
		}
		$coupons_list['total_num'] = $coupons_total_num;
		$coupons_list['total_money'] = $coupons_total_money;

		return $coupons_list;
	}

	/*获取用户使用月费券的信息 By Terry.*/
	public function checkMonthFeeCouponLimit($uid){

		$res = $this->db->select('coupon_num_change')->from('month_fee_change')->where('user_id',$uid)->where('type',4)->order_by('id desc')->limit(2)->get()->result_array();
		
		foreach($res as $v){
			if($v['coupon_num_change']!=0){
				return true;
			}
		}
		return false;
	}

	/**
	 * 获得券详情
	 */
	public function get_coupons_info($user_id)
	{
		$coupons_info = $this->m_suite_exchange_coupon->getAll($user_id);

		//去掉m
		foreach ($coupons_info as $k => $v)
		{
			$key = substr($k, 1);
			$coupons_info[$key] = $v;
			unset($coupons_info[$k]);
		}

		return $coupons_info;
	}

	/**
	* 发月费抵用券
	* @author Terry
	*/
	public function giveMonthlyFeeCoupon($uid,$num=1){

		$this->load->model('m_user');
		$this->load->model('m_commission');

		$oldMonthlyFeeCouponNum = $this->getMonthlyFeeCouponNum($uid);
		for($i=1;$i<=$num;$i++){
			$this->db->insert('monthly_fee_coupon',array(
				'uid'=>$uid
			));
		}
		$monthlyFeeCouponNum = $this->getMonthlyFeeCouponNum($uid);
		$userInfo = current($this->m_user->getInfo($uid));
		$this->m_commission->monthFeeChangeLog($uid,$userInfo['month_fee_pool'],$userInfo['month_fee_pool'],0,date('Y-m-d H:i:s'),3,$oldMonthlyFeeCouponNum,$monthlyFeeCouponNum,$num);
		return true;
	}

	/**
	*获取用户的月费券数量。
	*@author Terry
	*/
	public function getMonthlyFeeCouponNum($uid){
		return $this->db->select('count(*) num')->from('monthly_fee_coupon')->where('uid',$uid)->get()->row_object()->num;
	}

	/**
	* 使用月费券。
	* @author Terry
	*/
	public function reduceMonthlyFeeCoupon($uid,$num){
		for($i=1;$i<=$num;$i++){
			$id = $this->db->select('id')->from('monthly_fee_coupon')->where('uid',$uid)->order_by('create_time')->get()->row_object()->id;
			$this->db->where('id',$id)->delete('monthly_fee_coupon');
		}
		return true;
	}


	/**
	 * 获得套装数据
	 */
	public function get_group_list($country_id)
	{
		$language_id = (int)$this->session->userdata('language_id');
        $this->load->model('tb_mall_goods');

        //如果为空
        if(empty($language_id)){
            $this->load->model('tb_language');
            $language_id = $this->tb_language->get_language_by_location($country_id);
        }

        //leon 2016-12-22  取消like的使用
		//$sql = "select mgm.* from mall_goods_main as mgm, mall_goods_group as mgg where is_alone_sale=2 and is_for_upgrade=1 and sale_country like '%$country_id%' and mgm.group_goods_id=mgg.group_id and language_id='$language_id' and is_on_sale=1";
		$sql = "select mgm.* from mall_goods_main as mgm, mall_goods_group as mgg where is_alone_sale=2 and is_for_upgrade=1 and sale_country='$country_id' and mgm.group_goods_id=mgg.group_id and language_id='$language_id' and is_on_sale=1";
		$group_list = $this->db->query($sql)->result();
		if (!empty($group_list))
		{
			foreach ($group_list as $k => $group)
			{
                $goods_sn = $group->goods_sn_main.'-1';
                $price = $this->tb_mall_goods->get_goods_price($goods_sn);
				$group_list[$k]->shop_price = round($price);
			}
		}

        //过滤掉库存为0的商品
        foreach($group_list as $k => $item){
//            $goods_number_arr = $this->db->select('goods_number')
//                ->where('goods_sn_main',$item->goods_sn_main)
//                ->where('language_id',$language_id)
//                ->get('mall_goods')->row_array();
            $this->load->model("tb_mall_goods");
            $goods_number_arr = $this->tb_mall_goods->get_one("goods_number",
                ["goods_sn_main"=>$item->goods_sn_main,"language_id"=>$language_id]);
            if(!empty($goods_number_arr)){
                if($goods_number_arr['goods_number'] == 0){
                    unset($group_list[$k]);
                }
            }
        }
		return $group_list;
	}

	/**
	 * 获得套装数据
	 * 
	 * 2016-12-29
	 * leon 新增方法
	 * 
	 * 添加了查询中的字段
	 */
	public function get_group_list_new($country_id){
	    
	    $language_id = (int)$this->session->userdata('language_id');
	    $this->load->model('tb_mall_goods');
	
	    //如果为空
	    if(empty($language_id)){
	        $this->load->model('tb_language');
	        $language_id = $this->tb_language->get_language_by_location($country_id);
	    }
	
	    $sql = "select mgm.goods_id,mgm.goods_sn_main,mgm.goods_name,mgm.goods_img,mgm.market_price,mgm.shop_price,mgm.is_promote,mgm.promote_price,mgm.is_on_sale,mgm.promote_start_date,mgm.promote_end_date,mgm.is_hot,mgm.is_new,mgm.is_free_shipping,mgm.country_flag from mall_goods_main as mgm, mall_goods_group as mgg where is_alone_sale=2 and is_for_upgrade=1 and sale_country='$country_id' and mgm.group_goods_id=mgg.group_id and language_id='$language_id' and is_on_sale=1";
	    $group_list = $this->db->query($sql)->result();
	    if (!empty($group_list)){
	        foreach ($group_list as $k => $group)
	            {
	            $goods_sn = $group->goods_sn_main.'-1';
	            $price = $this->tb_mall_goods->get_goods_price($goods_sn);
	            $group_list[$k]->shop_price = round($price);
        	}
    	}
	
    	//过滤掉库存为0的商品
    	foreach($group_list as $k => $item){
//    	    $goods_number_arr = $this->db->select('goods_number')
//    	    ->where('goods_sn_main',$item->goods_sn_main)
//    	    ->where('language_id',$language_id)
//    	    ->get('mall_goods')->row_array();
            $this->load->model("tb_mall_goods");
            $goods_number_arr = $this->tb_mall_goods->get_one("goods_number",
                ["goods_sn_main"=>$item->goods_sn_main,"language_id"=>$language_id]);
	        if(!empty($goods_number_arr)){
        	    if($goods_number_arr['goods_number'] == 0){
        	        unset($group_list[$k]);
        	    }
	       }
    	 }
    	 return $group_list;
    }
	
	
	/**
	 * 套装单品
	 */
	public function get_goods_list($country_id,$condition_data=array()){

        //当前语言ID
        $language_id = get_cookie('curLan_id', true);
        $this->load->model('tb_mall_goods');

        //如果为空
        if(empty($language_id)){
            $this->load->model('tb_language');
            $language_id = $this->tb_language->get_language_by_location($country_id);
        }

		$group_goods = array();
		$group_list = $this->m_group->group_info_one($country_id,$condition_data);//套餐下的全部产品信息

		//过滤掉只有一个单品的套装
		if(!empty($group_list)){
			foreach($group_list as $k => $item){
				if($item['number'] == 1){
					unset($group_list[$k]);
				}
			}
		}

		//过滤掉下架的单品
		foreach ($group_list as $k => $group){
			foreach ($group['list' ] as $k => $v){
                //如果库存为0,则过滤
                $goods_sn_main = $v['info']['goods_sn_main'];
//                $goods_number_arr = $this->db->select('goods_number')
//                ->where('goods_sn_main',$goods_sn_main)
//                ->where('language_id',$language_id)
//                ->get('mall_goods')
//                ->row_array();
                $this->load->model("tb_mall_goods");
                $goods_number_arr = $this->tb_mall_goods->get_one("goods_number",
                    ["goods_sn_main"=>$goods_sn_main,"language_id"=>$language_id]);

				if($v['info']['is_on_sale'] == '0' || $goods_number_arr['goods_number'] == 0){
					continue;
				}
				$group_goods[$v['info']['goods_id']] = $v['info'];//过滤去重产品
			}
		}

//		//追加可代金券购买的商品
//		$voucher_goods_list = $this->db->select('goods_id,goods_sn_main,goods_name,goods_img,market_price,shop_price,is_promote,is_hot,is_new,is_free_shipping,country_flag,sale_country')
//			->where('is_voucher_goods',1)
//			->where('language_id',$language_id)
//			->where('is_on_sale',1)
//			->where('sale_country',$country_id)
//            //->like('sale_country',$country_id)//leon 2016-12-22   取消like    添加了 ->where('sale_country',$country_id) 内容
//			->from('mall_goods_main')->get()->result_array();
//
//		foreach($voucher_goods_list as $k => $voucher_goods){
//            //如果库存为0,则过滤
//            $goods_number_arr = $this->db->select('goods_number')
//                ->where('goods_sn_main',$voucher_goods['goods_sn_main'])
//                ->where('language_id',$language_id)
//                ->get('mall_goods')->row_array();
//
//            if(!empty($goods_number_arr)){
//                if($goods_number_arr['goods_number'] == 0){
//                    continue;
//                }
//            }
//
//            if(!isset($group_goods[$voucher_goods['goods_id']])){
//                $group_goods[$voucher_goods['goods_id']] = $voucher_goods;
//            }
//        }

        //单品价格
        foreach($group_goods as $k => $item){
            $goods_sn = $item['goods_sn_main'].'-1';
            $price = $this->tb_mall_goods->get_goods_price($goods_sn);
            $group_goods[$k]['shop_price'] = number_format(round($price), 2, ".", "");
        }

		return $group_goods;
	}

	/**
	 * 获得挑选商品的总价
	 */
	public function get_product_total_money($all_product)
	{
		$total_money = 0;
		foreach ($all_product as $k => $v)
		{
			if ($k == 'group')
			{
				foreach ($v as $group)
				{
					$shop_price = $group['shop_price'];
					$group_num = $group['goods_num'];
					$total_money = $total_money + $shop_price * $group_num;
				}
				continue;
			}
			if ($k == 'goods')
			{
				foreach ($v as $goods)
				{
					$shop_price = $goods['shop_price'];
					$goods_num = $goods['goods_num'];
					$total_money = $total_money + $shop_price * $goods_num;
				}
				continue;
			}
		}
		return $total_money;
	}

	/**
	 * 获得挑选商品的总价
	 */
	public function get_product_total_money_coupons($all_product)
	{
		$total_money = 0;
		foreach ($all_product as $k => $v)
		{
			if ($k == 'group')
			{
				foreach ($v as $group)
				{
					$shop_price = round($group['shop_price']);
					$group_num = $group['goods_num'];
					$total_money = $total_money + $shop_price * $group_num;
				}
				continue;
			}
			if ($k == 'goods')
			{
				foreach ($v as $goods)
				{
					$shop_price = round($goods['shop_price']);
					$goods_num = $goods['goods_num'];
					$total_money = $total_money + $shop_price * $goods_num;
				}
				continue;
			}
		}
		return $total_money;
	}

    /* 代品券商品 -- 返回手机端数组格式 */
	public function get_coupons_goods($country_id){
        $group_list = $this->get_group_list(($country_id));
        $goods_list = $this->get_goods_list($country_id);

        $group_info = array();
        $goods_info = array();

        $this->load->model('tb_language');
        $language_id = $this->tb_language->get_language_by_location($country_id);

        foreach($group_list as $k=>$v){
            $item = array();

            $goods_sn = $v->goods_sn_main."-1";
//            $price_arr = $this->db->select('price')->where('goods_sn',$goods_sn)
//                ->where('language_id',$language_id)->from('mall_goods')->get()->row_array();
            $this->load->model("tb_mall_goods");
            $price_arr = $this->tb_mall_goods->get_one("price",
                ["goods_sn"=>$goods_sn,"language_id"=>$language_id]);

            $price = $price_arr == array() ? $v['shop_price'] : $price_arr['price'];

            $item['goods_id'] = $v->goods_id;
            $item['goods_sn_main'] = $v->goods_sn_main;
            $item['goods_sn'] = $goods_sn;
            $item['goods_name'] = $v->goods_name;
            $item['goods_img'] = $v->goods_img;
            $item['market_price'] = $v->market_price;
            $item['shop_price'] = round($price);
            $item['is_promote'] = $v->is_promote;
            $item['promote_price'] = $v->promote_price;
            $item['is_on_sale'] = $v->is_on_sale;
            $item['promote_start_date'] = $v->promote_start_date;
            $item['promote_end_date'] = $v->promote_end_date;
            $item['is_hot'] = $v->is_hot;
            $item['is_new'] = $v->is_new;
            $item['is_free_shipping'] = $v->is_free_shipping;
            $item['country_flag'] = $v->country_flag;
            $group_info[] = $item;
        }

        foreach($goods_list as $k=>$v){

            $goods_sn = $v['goods_sn_main']."-1";
//            $price_arr = $this->db->select('price')->where('goods_sn',$goods_sn)
//                ->where('language_id',$language_id)->from('mall_goods')->get()->row_array();
            $this->load->model("tb_mall_goods");
            $price_arr = $this->tb_mall_goods->get_one("price",
                ["goods_sn"=>$goods_sn,"language_id"=>$language_id]);

            $price = $price_arr == array() ? $v['shop_price'] : $price_arr['price'];

            $v['goods_sn'] = $v['goods_sn_main']."-1";
            $v['shop_price'] = round($price);
            $goods_info[] = $v;
        }

        //合并两个数组
        $new_arr = array_merge($group_info,$goods_info);

        return $new_arr;
    }
}
