<?php

class m_group extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->model('m_goods');
		$this->load->model('m_trade');
        $this->load->model('m_erp');
        $this->load->model('tb_mall_goods_number_exception');
        $this->load->model('tb_trade_orders');
	}


	/***选择套装***/
	public function choose_group($group_id, $group_num, $cancel_product)
	{
		$publicDomain = get_public_domain();
		$str = isset($_COOKIE['group']) ? unserialize($_COOKIE['group']) : '';

		/***取消勾选则不累加**/
		if($cancel_product!=true) {
			$id_and_num = $group_id . '-' . $group_num;
			if ($str != '') {
				$id_and_num = $str . '|' . $id_and_num;
			}

			$groupList = $this->get_goods_by_id_and_num($id_and_num);
			set_cookie('group', serialize($id_and_num), 0, $publicDomain);
		}

		/**取消勾选**/
		if ($cancel_product) {
			$groupList = $this->get_goods_by_id_and_num($str);
			unset($groupList[$group_id]);
			$this->delete_cookie_by_id($group_id,$str,1);
		}

		return $groupList;
	}


	/***选择单品***/
	public function choose_goods($goods_id,$goods_num,$cancel_product){
		$publicDomain = get_public_domain();
		$str = isset($_COOKIE['goods']) ? unserialize($_COOKIE['goods']) : '';

		/***取消勾选则不累加**/
		if($cancel_product!=true) {
			$id_and_num = $goods_id . '-' . $goods_num;
			if ($str != '') {
				$id_and_num = $str . '|' . $id_and_num;
			}

			$goodsList = $this->get_goods_by_id_and_num($id_and_num);
			set_cookie('goods', serialize($id_and_num), 0, $publicDomain);
		}

		/**取消勾选**/
		if ($cancel_product) {
			$goodsList = $this->get_goods_by_id_and_num($str);
			unset($goodsList[$goods_id]);
			$this->delete_cookie_by_id($goods_id,$str,2);
		}

		return $goodsList;
	}


	/**选择代品券***/
	public function choose_coupons($coupons_id,$coupons_num,$cancel_product){
		$publicDomain = get_public_domain();
		$str = isset($_COOKIE['coupons']) ? unserialize($_COOKIE['coupons']) : '';

		/***取消勾选则不累加**/
		if($cancel_product!=true) {
			$id_and_num = $coupons_id . '-' . $coupons_num;
			if ($str != '') {
				$id_and_num = $str . '|' . $id_and_num;
			}

			$couponsList = $this->get_coupons_by_id_and_num($id_and_num);
			set_cookie('coupons', serialize($id_and_num), 0, $publicDomain);
		}

		/**取消勾选**/
		if ($cancel_product) {
			$couponsList = $this->get_coupons_by_id_and_num($str);
			unset($couponsList[$coupons_id]);
			$this->delete_cookie_by_id($coupons_id,$str,3);
		}

		return $couponsList;
	}


	/*
	* @param $product_id   商品ID
	* @param $product_num  商品数量
	* @param $product_type 商品类型
	*/
	public function all_product($product_id,$product_num,$product_type,$cancel_product){
		$all_product=array();
		if($product_type==1){
			$all_product['group']=$this->choose_group($product_id,$product_num,$cancel_product);

			$goods_id_and_num=isset($_COOKIE['goods'])?unserialize($_COOKIE['goods']):'';
			$all_product['goods']=$this->get_goods_by_id_and_num($goods_id_and_num);

			$coupons_id_and_num=isset($_COOKIE['coupons'])?unserialize($_COOKIE['coupons']):'';
			$all_product['coupons']=$this->get_coupons_by_id_and_num($coupons_id_and_num);;

		}if($product_type==2){
			$group_id_and_num=isset($_COOKIE['group'])?unserialize($_COOKIE['group']):'';
			$all_product['group']=$this->get_goods_by_id_and_num($group_id_and_num);

			$all_product['goods']=$this->choose_goods($product_id,$product_num,$cancel_product);

			$coupons_id_and_num=isset($_COOKIE['coupons'])?unserialize($_COOKIE['coupons']):'';
			$all_product['coupons']=$this->get_coupons_by_id_and_num($coupons_id_and_num);;

		}if($product_type==3){
			$group_id_and_num=isset($_COOKIE['group'])?unserialize($_COOKIE['group']):'';
			$all_product['group']=$this->get_goods_by_id_and_num($group_id_and_num);

			$goods_id_and_num=isset($_COOKIE['goods'])?unserialize($_COOKIE['goods']):'';
			$all_product['goods']=$this->get_goods_by_id_and_num($goods_id_and_num);

			$all_product['coupons']=$this->choose_coupons($product_id,$product_num,$cancel_product);
		}
		return $all_product;
	}

    /*
	* @param $product_id   商品ID
	* @param $product_num  商品数量
	* @param $product_type 商品类型
	*/
    public function all_product_coupons($product_id,$product_num,$product_type,$cancel_product){
        $all_product=array();
        if($product_type==1){
            $all_product['group']=$this->choose_group($product_id,$product_num,$cancel_product);

            $goods_id_and_num=isset($_COOKIE['goods'])?unserialize($_COOKIE['goods']):'';
            $all_product['goods']=$this->get_goods_by_id_and_num_coupons($goods_id_and_num);

            $coupons_id_and_num=isset($_COOKIE['coupons'])?unserialize($_COOKIE['coupons']):'';
            $all_product['coupons']=$this->get_coupons_by_id_and_num($coupons_id_and_num);;

        }if($product_type==2){
            $group_id_and_num=isset($_COOKIE['group'])?unserialize($_COOKIE['group']):'';
            $all_product['group']=$this->get_goods_by_id_and_num_coupons($group_id_and_num);

            $all_product['goods']=$this->choose_goods($product_id,$product_num,$cancel_product);

            $coupons_id_and_num=isset($_COOKIE['coupons'])?unserialize($_COOKIE['coupons']):'';
            $all_product['coupons']=$this->get_coupons_by_id_and_num($coupons_id_and_num);;

        }if($product_type==3){
            $group_id_and_num=isset($_COOKIE['group'])?unserialize($_COOKIE['group']):'';
            $all_product['group']=$this->get_goods_by_id_and_num_coupons($group_id_and_num);

            $goods_id_and_num=isset($_COOKIE['goods'])?unserialize($_COOKIE['goods']):'';
            $all_product['goods']=$this->get_goods_by_id_and_num($goods_id_and_num);

            $all_product['coupons']=$this->choose_coupons($product_id,$product_num,$cancel_product);
        }
        return $all_product;
    }


	/**删除cookie***/
	public function del_cookie(){
		if(isset($_COOKIE['group'])){
			delete_cookie("group", get_public_domain());
		}
		if(isset($_COOKIE['goods'])){
			delete_cookie("goods", get_public_domain());
		}
		if(isset($_COOKIE['coupons'])){
			delete_cookie("coupons", get_public_domain());
		}
	}

	/***选购页面提示***/
	public function show_info($user_id,$name){
		$data = null;
		$sql = "select * from users_level_change_log where level_type=2 and uid=$user_id order by create_time desc";
		$result = $this->db->query($sql)->result();
		if (!empty($result)) {
			$data['user_id'] = $user_id;
			$data['old_level'] = $result[0]->old_level;
			$data['new_level'] = $result[0]->new_level;
			$data['create_time'] = $result[0]->create_time;
			$data['name']=$name;

			if ($data['old_level'] == 4) $data['old_level'] = lang('member_free');
			if ($data['old_level'] == 3) $data['old_level'] = lang('member_silver');
			if ($data['old_level'] == 2) $data['old_level'] = lang('member_platinum');
			if ($data['old_level'] == 1) $data['old_level'] = lang('member_diamond');

			$this->load->model('m_user');
			$is_true = $this->m_user->is_first_upgrade_time_1_1($user_id);
			if($is_true){
				$joinFeeAndMonthFee = config_item('old_join_fee_and_month_fee');
			}else{
				$joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
			}

			if ($data['new_level'] == 1) {
				$data['pay_money'] = ($joinFeeAndMonthFee[1]['join_fee']);
				$data['new_level'] = lang('member_diamond');
			}
			if ($data['new_level'] == 2) {
				$data['pay_money'] = ($joinFeeAndMonthFee[2]['join_fee']);
				$data['new_level'] = lang('member_platinum');
			}
			if ($data['new_level'] == 3) {
				$data['pay_money'] = ($joinFeeAndMonthFee[3]['join_fee']);
				$data['new_level'] = lang('member_silver');
			}
		}
		return $data;
	}

	public function show_info_upgrade($user_id,$product_set){
		$data = null;
		$this->load->model('m_forced_matrix');
		$user_info = $this->m_forced_matrix->userInfo($user_id);
		$user_rank = $user_info->user_rank;
		$name = $user_info->name;
		$data['name'] = $name;
		$data['create_time'] = date('Y-m-d',time());

		$this->load->model('m_user');
		$joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();

		/**
		 * 　如果不是免费店铺，查看是否是１.１号之前升级的。如果是，得到当时升级店铺的费用，计算升級差价
		 */
		/*$old_join_fee = $this->m_user->upgrade_before_1_1_amount($user_id,$user_rank);
		if($old_join_fee !== FALSE){
			$joinFeeAndMonthFee[$user_rank]['join_fee'] = $old_join_fee;
		}*/

		$diamond_cash = ($joinFeeAndMonthFee[1]['join_fee']);
		$platinum_cash = ($joinFeeAndMonthFee[2]['join_fee']);
		$silver_cash = ($joinFeeAndMonthFee[3]['join_fee']);
        $bronze_cash = ($joinFeeAndMonthFee[5]['join_fee']);
		$already_cash = 0;

		if ($user_rank == 4) {
			$data['old_level'] = lang('member_free');
			$already_cash=0;
		}
		if ($user_rank == 5) {
			$data['old_level'] = lang('level_bronze');
			$already_cash=$bronze_cash;
		}
		if ($user_rank == 3){
			$data['old_level'] = lang('member_silver');
			$already_cash=$silver_cash;
		}
		if ($user_rank == 2){
			$data['old_level'] = lang('member_platinum');
			$already_cash=$platinum_cash;
		}
		if ($user_rank == 1) {
			$data['old_level'] = lang('member_diamond');
			$already_cash=$diamond_cash;
		}


		if ($product_set['level'] == 1) {
			$data['pay_money'] =$diamond_cash-$already_cash ;
			$data['now_level'] = lang('member_diamond');
		}
		if ($product_set['level'] == 2) {
			$data['pay_money'] =$platinum_cash-$already_cash ;
			$data['now_level'] = lang('member_platinum');
		}
		if ($product_set['level'] == 3) {
			$data['pay_money'] =$silver_cash-$already_cash ;
			$data['now_level'] = lang('member_silver');
		}
        if ($product_set['level'] == 5) {
            $data['pay_money'] =$bronze_cash-$already_cash ;
            $data['now_level'] = lang('member_bronze');
        }

		return $data;
	}


	/**
	 * 获取套装单品详细信息
	 *
	 * leon 新增 参数 $condition_data
	 * 搜索的数据
	 */
	public function group_info($country_id,$condition_data=array())
	{
		$language_id = (int)$this->session->userdata('language_id');

        if($language_id == ''){
            $this->load->model('tb_language');
            $language_id = $this->tb_language->get_language_by_location($country_id);
        }

		$goods_group_info = array();
		$where = "is_alone_sale = 2";
		$where .= " AND is_for_upgrade = 1";
		//$where .= " AND sale_country like '%{$country_id}%'";//leon 2016-12-22 取消like的使用
		$where .= " AND sale_country='{$country_id}'";
		$where .= " AND mgm.group_goods_id = mgg.group_id";
		$where .= " AND language_id = '{$language_id}'";
		$where .= " AND is_on_sale = 1";

		/**
		 * leon
		 * 新增搜索内容
		 */
		if(!empty($condition_data['search'])){
		    $search =  trim($condition_data['search']);
		    //判断是不是为纯数字
		    if(!preg_match("/[^\d ]/",$search)){
		        //是纯数字
		        if(strlen($search) == 8){
		            $group_info = $this->m_goods->get_goods_group_info_id($search);
		            $group_id='';
		            if(!empty($group_info)){
		                foreach ($group_info as $ke=>$va){
		                    $group_id.=$va.',';
		                }
		                $group_id=trim($group_id,',');
		                $where .= " AND group_goods_id in({$group_id})";
		            }else{
		                $where .= " AND group_goods_id =''";
		            }
		        }else{
		            $where .= " AND goods_name like '%{$search}%'";
		        }
		    }else{
		        $where .= " AND goods_name like '%{$search}%'";
		    }
		}

		$sql = "SELECT mgm.* FROM mall_goods_main AS mgm, mall_goods_group AS mgg WHERE {$where}";
		$groups = $this->db->query($sql)->result();
		if (!empty($groups))
		{
			foreach ($groups as $group)
			{
                //获取套装单品数据  一个套餐里面的全部产品数据
				$group_info = $this->m_goods->get_goods_group_info($group->group_goods_id);

                //过滤单品数据绑定错误的套餐
                if(!isset($group_info['list'])){
                    continue;
                }

				$goods_group_info[$group->goods_id] = $group_info;//一个套餐中的 全部产品数据

                $goods_group_info[$group->goods_id]['shop_price'] = round($group->shop_price);//四舍五入(销售价，美元)

                //如果是促销商品,则取促销价
                if($group->is_promote == '1')
                {
                    $promote_price_arr = $this->db->select('promote_price')
                        ->where('goods_sn_main',$group->goods_sn_main)
                        ->where('start_time <',date("Y-m-d H:i:s"))
                        ->where('end_time >',date("Y-m-d H:i:s"))
                        ->get('mall_goods_promote')->row_array();

                    if(!empty($promote_price_arr)){
                        $goods_group_info[$group->goods_id]['shop_price'] = round($promote_price_arr['promote_price']/100);//取促销价
                    }else{
                        $goods_group_info[$group->goods_id]['shop_price'] = round($group->shop_price);                     //取销售价
                    }
                }

                $goods_group_info[$group->goods_id]['market_price'] = $group->market_price;    //市场价
				$goods_group_info[$group->goods_id]['goods_name'] = $group->goods_name;        //套餐产品的名字
				$goods_group_info[$group->goods_id]['goods_sn_main'] = $group->goods_sn_main;  //套餐产品的SKU
				$goods_group_info[$group->goods_id]['language_id'] = $group->language_id;
                $goods_group_info[$group->goods_id]['is_on_sale'] = $group->is_on_sale;        //是否销售中
			}
		}

        //过滤掉库存为0的商品
        foreach($goods_group_info as $k => $item){
//            $goods_number_arr = $this->db->select('goods_number')
//                ->where('goods_sn_main',$item['goods_sn_main'])
//                ->where('language_id',$language_id)
//                ->get('mall_goods')->row_array();
            $this->load->model("tb_mall_goods");
            $goods_number_arr = $this->tb_mall_goods->get_one("goods_number",
                ["goods_sn_main"=>$item['goods_sn_main'],"language_id"=>$language_id]);
            if(!empty($goods_number_arr)){
                if($goods_number_arr['goods_number'] == 0){
                    unset($goods_group_info[$k]);
                }
            }
        }

		return $goods_group_info;
	}


	/**
	 * 套餐中的单品过搜索数据方法
	 *
	 * leon 新增 参数 $condition_data
	 * 搜索的数据
	 */
	public function group_info_one($country_id,$condition_data=array())
	{
		$language_id = (int)$this->session->userdata('language_id');

		$search =  trim($condition_data['search']);

		if($language_id == ''){
			$this->load->model('tb_language');
			$language_id = $this->tb_language->get_language_by_location($country_id);
		}

		$goods_group_info = array();

		$where = "is_alone_sale = 2";
		$where .= " AND is_for_upgrade = 1";

		//$where .= " AND sale_country like '%{$country_id}%'";//leon 2016-12-22 取消like的使用
		$where .= " AND sale_country='{$country_id}'";
		$where .= " AND mgm.group_goods_id = mgg.group_id";
		$where .= " AND language_id = '{$language_id}'";
		$where .= " AND is_on_sale = 1";

			//判断是不是为纯数字
			if(!preg_match("/[^\d ]/",$search)){
				//是纯数字
				if(strlen($search) == 8){
					$group_info = $this->m_goods->get_goods_group_info_id($search);
					//$group_id='';
					if(!empty($group_info)){
//						foreach ($group_info as $ke=>$va){
//							$group_id.=$va.',';
//						}
//						$group_id=trim($group_id,',');
//						$where .= " AND group_goods_id in({$group_id})";

						$where .= " AND goods_sn_main ='{$search}'";
					}else{
						$where .= " AND group_goods_id =''";
					}
				}else{
					$where .= " AND goods_name like '%{$search}%'";
				}
			}else{
				$where .= " AND goods_name like '%{$search}%'";
			}
			//$sql = "SELECT mgm.* FROM mall_goods_main AS mgm WHERE {$where}";

		$sql = "SELECT mgm.* FROM mall_goods_main AS mgm, mall_goods_group AS mgg WHERE {$where}";

		$groups = $this->db->query($sql)->result();

		if (!empty($groups))
		{
			foreach ($groups as $group)
			{
				//获取套装单品数据  一个套餐里面的全部产品数据
				$group_info = $this->m_goods->get_goods_group_info($group->group_goods_id);

				//过滤单品数据绑定错误的套餐
				if(!isset($group_info['list'])){
					continue;
				}

				$goods_group_info[$group->goods_id] = $group_info;//一个套餐中的 全部产品数据

				$goods_group_info[$group->goods_id]['shop_price'] = round($group->shop_price);//四舍五入(销售价，美元)

				//如果是促销商品,则取促销价
				if($group->is_promote == '1')
				{
					$promote_price_arr = $this->db->select('promote_price')
							->where('goods_sn_main',$group->goods_sn_main)
							->where('start_time <',date("Y-m-d H:i:s"))
							->where('end_time >',date("Y-m-d H:i:s"))
							->get('mall_goods_promote')->row_array();

					if(!empty($promote_price_arr)){
						$goods_group_info[$group->goods_id]['shop_price'] = round($promote_price_arr['promote_price']/100);//取促销价
					}else{
						$goods_group_info[$group->goods_id]['shop_price'] = round($group->shop_price);                     //取销售价
					}
				}

				$goods_group_info[$group->goods_id]['market_price'] = $group->market_price;    //市场价
				$goods_group_info[$group->goods_id]['goods_name'] = $group->goods_name;        //套餐产品的名字
				$goods_group_info[$group->goods_id]['goods_sn_main'] = $group->goods_sn_main;  //套餐产品的SKU
				$goods_group_info[$group->goods_id]['language_id'] = $group->language_id;
				$goods_group_info[$group->goods_id]['is_on_sale'] = $group->is_on_sale;        //是否销售中
			}
		}

		//过滤掉库存为0的商品
		foreach($goods_group_info as $k => $item){
//			$goods_number_arr = $this->db->select('goods_number')
//					->where('goods_sn_main',$item['goods_sn_main'])
//					->where('language_id',$language_id)
//					->get('mall_goods')->row_array();
            $this->load->model("tb_mall_goods");
            $goods_number_arr = $this->tb_mall_goods->get_one("goods_number",
                ['goods_sn_main'=>$item['goods_sn_main'],'language_id'=>$language_id]);
			if(!empty($goods_number_arr)){
				if($goods_number_arr['goods_number'] == 0){
					unset($goods_group_info[$k]);
				}
			}
		}

		return $goods_group_info;
	}


	/* 获取产品套装详情  */
	function get_goods_group_info($goods_group_id) {
		$language_id=(int)$this->session->userdata('language_id');
		$rs=$this->db->where('group_id',$goods_group_id)->get('mall_goods_group')->row_array();

		if(empty($rs)) {
			return false;
		}

		$goods_arr=explode('|', $rs['group_goods']);
		$goods=array();
		$number=0;
		$total=0.00;
		foreach($goods_arr as $k=>$g) {
			$tmp_arr=explode('*',$g);

			$tmp_rs=$this->db->select('goods_id,goods_sn_main,goods_name,goods_img,market_price,shop_price')->where('is_on_sale',1)->where('goods_sn_main',$tmp_arr[0])->where('language_id',$language_id)->get('mall_goods_main')->row_array();

			$tmp_rs['shop_price']=round($tmp_rs['shop_price']);	 //四舍五入
			$number+=$tmp_arr[1];

			$goods['list'][$k]['info']=$tmp_rs;
			$goods['list'][$k]['num']=$tmp_arr[1];

			$total+=$tmp_rs['shop_price'] * $tmp_arr[1];
		}
		$goods['number']=$number;
		$goods['total']=$total;
		$goods['goods_name']='';
		$goods['shop_price']=0;
		$goods['goods_sn_main']='';
		$goods['language_id']='';

		return $goods;
	}


	/**
	 * 根据id和数量,返回商品信息
	 */
	public function get_goods_by_id_and_num($id_and_num)
	{
		$goods_list = array();
		$language_id = (int)$this->session->userdata('language_id');

		if (empty($id_and_num))
		{
			return array();
		}

		$goods = array();
		$goods_arr = explode("|", $id_and_num);
		foreach ($goods_arr as $v)
		{
			list($goods_id, $qty) = explode("-", $v);
			$goods[] = array('goods_id' => $goods_id, 'qty' => $qty);
		}

		foreach ($goods as $v)
		{
			$main_select = "goods_sn_main, purchase_price,is_promote,shop_price, goods_img, goods_name, store_code";
			$main_sql = "SELECT {$main_select} FROM mall_goods_main WHERE goods_id = {$v['goods_id']}";
			$goods_main_info = $this->db->query($main_sql)->row_array();
			if (!empty($goods_main_info))
			{
				$select = "price,goods_sn";
//				$where = "goods_sn_main = '{$goods_main_info['goods_sn_main']}' AND language_id = {$language_id}";
//				$sql = "SELECT {$select} FROM mall_goods WHERE {$where}";
//				$goods_info = $this->db->query($sql)->row_array();
                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one($select,
                    ["goods_sn_main"=>$goods_main_info['goods_sn_main'],'language_id'=>$language_id]);
                if($goods_main_info['is_promote'] == '1')
                {
                    $promote_price_arr = $this->db->select('promote_price')->where('goods_sn_main',$goods_main_info['goods_sn_main'])
                        ->get('mall_goods_promote')->row_array();
                    if(!empty($promote_price_arr)){
                        $goods_info['price'] = round($promote_price_arr['promote_price']/100,2);
                    }
                }
			}

			$goods_list[$v['goods_id']] = array(
				'goods_id' => $v['goods_id'],
				'goods_img' => $goods_main_info['goods_img'],
				'shop_price' => round($goods_info['price'],2),
				'purchase_price' => $goods_main_info['purchase_price'],
				'goods_name' => $goods_main_info['goods_name'],
				'goods_sn_main' => $goods_main_info['goods_sn_main'],
				'goods_sn' => $goods_info['goods_sn'],
				'goods_num' => $v['qty'],
				'store_code' => $goods_main_info['store_code'],
			);
		}
		return $goods_list;
	}

    /**
     * 根据id和数量,返回商品信息
     */
    public function get_goods_by_id_and_num_coupons($id_and_num)
    {
        $goods_list = array();
        $language_id = (int)$this->session->userdata('language_id');

        if (empty($id_and_num))
        {
            return array();
        }

        $goods = array();
        $goods_arr = explode("|", $id_and_num);
        foreach ($goods_arr as $v)
        {
            list($goods_id, $qty) = explode("-", $v);
            $goods[] = array('goods_id' => $goods_id, 'qty' => $qty);
        }

        foreach ($goods as $v)
        {
            $main_select = "goods_sn_main, purchase_price,is_promote,shop_price, goods_img, goods_name, store_code";
            $main_sql = "SELECT {$main_select} FROM mall_goods_main WHERE goods_id = {$v['goods_id']}";
            $goods_main_info = $this->db->query($main_sql)->row_array();
            if (!empty($goods_main_info))
            {
                $select = "price,goods_sn";
//				$where = "goods_sn_main = '{$goods_main_info['goods_sn_main']}' AND language_id = {$language_id}";
//				$sql = "SELECT {$select} FROM mall_goods WHERE {$where}";
//				$goods_info = $this->db->query($sql)->row_array();
                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one($select,
                    ["goods_sn_main"=>$goods_main_info['goods_sn_main'],'language_id'=>$language_id]);
                if($goods_main_info['is_promote'] == '1')
                {
                    $promote_price_arr = $this->db->select('promote_price')->where('goods_sn_main',$goods_main_info['goods_sn_main'])
                        ->get('mall_goods_promote')->row_array();
                    if(!empty($promote_price_arr)){
                        $goods_info['price'] = round($promote_price_arr['promote_price']/100);
                    }
                }
            }

            $goods_list[$v['goods_id']] = array(
                'goods_id' => $v['goods_id'],
                'goods_img' => $goods_main_info['goods_img'],
                'shop_price' => round($goods_info['price']),
                'purchase_price' => $goods_main_info['purchase_price'],
                'goods_name' => $goods_main_info['goods_name'],
                'goods_sn_main' => $goods_main_info['goods_sn_main'],
                'goods_sn' => $goods_info['goods_sn'],
                'goods_num' => $v['qty'],
                'store_code' => $goods_main_info['store_code'],
            );
        }
        return $goods_list;
    }

	/**
	 * 根据代品券ID和数量获取信息
	 */
	public function get_coupons_by_id_and_num($id_and_num)
	{
		if ($id_and_num == '')
		{
			return array();
		}

		$coupons = array();
		$arr = explode("|", $id_and_num);
		foreach ($arr as $v)
		{
			list($id, $num) = explode('-', $v);
			$coupons[] = array(
				'id' => $id,
				'num' => $num,
			);
		}

		$coupons_list = array();
		$coupons_money_map = config_item('coupons_money');
		foreach ($coupons as $v)
		{
			$coupons_list[$v['id']] = array(
				'coupons_id' => $v['id'],
				'coupons_money' => $coupons_money_map[$v['id']],
				'coupons_num' => $v['num'],
			);
		}
		return $coupons_list;
	}


	/***
	 * 根据商品id删除指定cookie
	 * @id  商品id
	 * @id_and_num  id与num组成的string
	 * $type	商品类型(1=>套餐，2单品，3代品券)
	 **/
	public function delete_cookie_by_id($id,$id_and_num,$type){
		if(strpos($id_and_num,'|')!=false) {
			$str=explode('|',$id_and_num);
			foreach($str as $k=>$v){
				if((explode('-',$v)[0])==$id){
					unset ($str[$k]);
				}
			}
			$str=implode("|", $str);

			if($type==1){
				set_cookie('group', serialize($str), 0, get_public_domain());
			}
			if($type==2){
				set_cookie('goods', serialize($str), 0, get_public_domain());
			}
			if($type==3){
				set_cookie('coupons', serialize($str), 0, get_public_domain());
			}
		}else{
			if($type==1){
				delete_cookie('group', get_public_domain());
			}
			if($type==2){
				delete_cookie('goods', get_public_domain());
			}
			if($type==3){
				delete_cookie('coupons',get_public_domain());
			}
		}
	}


	/***创建html代码***/
	public function create_html($all_product,$img_host){
		$html='';
		$details_url=base_url('index/product?snm=');

		foreach($all_product as $k=>$v){
			if($k=='group'){
				foreach($v as $group){
					$html .='<li>';
					$html .='<dl>';
					$html .='<dt class="mt-10"><a><img src="'.$img_host.$group['goods_img'].'"></a></dt>';
					$html .='<dd><a class="tit" target="_blank">'.$group['goods_name'].'</a>';
					$html .='<p class="c-o t-o-h">$'.round($group['shop_price'],2).'</p>
					<div class="Spinner">
							<a class="DisDe" href="javascript:void(0)" onclick="decrease_group('.$group['goods_id'].');"><i>-</i></a>
							<input class="Amount" id="amount_'.$group['goods_id'].'" value="'.$group['goods_num'].'" autocomplete="off" maxlength="2" readonly="readonly">
							<a class="Increase" href="javascript:void(0)" onclick="increase_group('.$group['goods_id'].');"><i>+</i></a>
						</div>
					<p class="j-j"><a href="javaScript:del_group('.$group['goods_id'].');"  product_type="1" product_id='.$group['goods_id'].' class="c-o delete">'.lang('delete').'</a></p>';
					$html .='</dd>';
					$html .='</dl>';
					$html .='</li>';
				}
				continue;
			}
			if($k=='goods'){
				foreach($v as $goods){
					$html .='<li>';
					$html .='<dl>';
					$html .='<dt class="mt-10"><a><img src="'.$img_host.$goods['goods_img'].'"></a></dt>';
                    $html .='<dd><a class="tit" target="_blank">'.$goods['goods_name'].'</a>';

                    $html .='<p class="c-o t-o-h">$'.round($goods['shop_price'],2).'</p>
					<div class="Spinner">
							<a class="DisDe" href="javascript:void(0)" onclick="decrease_goods('.$goods['goods_id'].');"><i>-</i></a>
							<input class="Amount" id="amount_goods_'.$goods['goods_id'].'" value="'.$goods['goods_num'].'" autocomplete="off" maxlength="2" readonly="readonly">
							<a class="Increase" href="javascript:void(0)" onclick="increase_goods('.$goods['goods_id'].');"><i>+</i></a>
						</div>
					<p class="j-j"><a href="javaScript:del_goods('.$goods['goods_id'].');"  product_type="2" product_id='.$goods['goods_id'].' class="c-o delete">'.lang('delete').'</a></p>';
//					$html .='<p><b>$'.round($goods['shop_price']).'</b><b>×</b><b>'.$goods['goods_num'].'</b><a href="javaScript:del_goods('.$goods['goods_id'].');" product_type="2" product_id='.$goods['goods_id'].' class="c-o delete">'.lang('delete').'</a></p>';

                    $html .='</dd>';
					$html .='</dl>';
					$html .='</li>';
				}
				continue;
			}
			if($k=='coupons'){
				foreach ($v as $coupons) {
					$html .='<li>';
					$html .='<dl>';
					$html .='<dt><div class="coupons_box"><p>'.lang('d_p_coupons').'</p><p>'.$coupons['coupons_money'].'</p></div></dt>';
					$html .='<dd><b>×</b><b>'.$coupons['coupons_num'].'</b>';
					$html .='<p><a href="javaScript:del_coupons('.$coupons['coupons_id'].');"  product_type="3" product_id='.$coupons['coupons_id'].' class="c-b delete">'.lang('delete').'</a></p>';
					$html .='</dd>';
					$html .='</dl>';
					$html .='</li>';
				}
				continue;
			}
		}
		return $html;
	}
	/***创建html代码***/
	public function create_html_coupons($all_product,$img_host){
		$html='';
		$details_url=base_url('index/product?snm=');

		foreach($all_product as $k=>$v){
			if($k=='group'){
				foreach($v as $group){
					$html .='<li>';
					$html .='<dl>';
					$html .='<dt class="mt-10"><a><img src="'.$img_host.$group['goods_img'].'"></a></dt>';
					$html .='<dd><a class="tit" target="_blank">'.$group['goods_name'].'</a>';
					$html .='<p class="c-o t-o-h">$'.round($group['shop_price']).'</p>
					<div class="Spinner">
							<a class="DisDe" href="javascript:void(0)" onclick="decrease_group('.$group['goods_id'].');"><i>-</i></a>
							<input class="Amount" id="amount_'.$group['goods_id'].'" value="'.$group['goods_num'].'" autocomplete="off" maxlength="2" readonly="readonly">
							<a class="Increase" href="javascript:void(0)" onclick="increase_group('.$group['goods_id'].');"><i>+</i></a>
						</div>
					<p class="j-j"><a href="javaScript:del_group('.$group['goods_id'].');"  product_type="1" product_id='.$group['goods_id'].' class="c-o delete">'.lang('delete').'</a></p>';
					$html .='</dd>';
					$html .='</dl>';
					$html .='</li>';
				}
				continue;
			}
			if($k=='goods'){
				foreach($v as $goods){
					$html .='<li>';
					$html .='<dl>';
					$html .='<dt class="mt-10"><a><img src="'.$img_host.$goods['goods_img'].'"></a></dt>';
					$html .='<dd><a class="tit" target="_blank">'.$goods['goods_name'].'</a>';

					$html .='<p class="c-o t-o-h">$'.round($goods['shop_price']).'</p>
					<div class="Spinner">
							<a class="DisDe" href="javascript:void(0)" onclick="decrease_goods_c('.$goods['goods_id'].');"><i>-</i></a>
							<input class="Amount" id="amount_goods_'.$goods['goods_id'].'" value="'.$goods['goods_num'].'" autocomplete="off" maxlength="2" readonly="readonly">
							<a class="Increase" href="javascript:void(0)" onclick="increase_goods_c('.$goods['goods_id'].');"><i>+</i></a>
						</div>
					<p class="j-j"><a href="javaScript:del_goods('.$goods['goods_id'].');"  product_type="2" product_id='.$goods['goods_id'].' class="c-o delete">'.lang('delete').'</a></p>';
//					$html .='<p><b>$'.round($goods['shop_price']).'</b><b>×</b><b>'.$goods['goods_num'].'</b><a href="javaScript:del_goods('.$goods['goods_id'].');" product_type="2" product_id='.$goods['goods_id'].' class="c-o delete">'.lang('delete').'</a></p>';

					$html .='</dd>';
					$html .='</dl>';
					$html .='</li>';
				}
				continue;
			}
			if($k=='coupons'){
				foreach ($v as $coupons) {
					$html .='<li>';
					$html .='<dl>';
					$html .='<dt><div class="coupons_box"><p>'.lang('d_p_coupons').'</p><p>'.$coupons['coupons_money'].'</p></div></dt>';
					$html .='<dd><b>×</b><b>'.$coupons['coupons_num'].'</b>';
					$html .='<p><a href="javaScript:del_coupons('.$coupons['coupons_id'].');"  product_type="3" product_id='.$coupons['coupons_id'].' class="c-b delete">'.lang('delete').'</a></p>';
					$html .='</dd>';
					$html .='</dl>';
					$html .='</li>';
				}
				continue;
			}
		}
		return $html;
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
					$total_money += $shop_price * $group_num;
				}
			}
			else if ($k == 'goods')
			{
				foreach ($v as $goods)
				{
					$shop_price = $goods['shop_price'];
					$goods_num = $goods['goods_num'];
					$total_money += $shop_price * $goods_num;
				}
			}
//			else if ($k == 'coupons')
//			{
//				foreach ($v as $coupons)
//				{
//					$coupons_money = $coupons['coupons_money'];
//					$coupons_num = $coupons['coupons_num'];
//					$total_money += $coupons_money * $coupons_num;
//				}
//			}
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
                    $total_money += $shop_price * $group_num;
                }
            }
            else if ($k == 'goods')
            {
                foreach ($v as $goods)
                {
                    $shop_price = round($goods['shop_price']);
                    $goods_num = $goods['goods_num'];
                    $total_money += $shop_price * $goods_num;
                }
            }
//			else if ($k == 'coupons')
//			{
//				foreach ($v as $coupons)
//				{
//					$coupons_money = $coupons['coupons_money'];
//					$coupons_num = $coupons['coupons_num'];
//					$total_money += $coupons_money * $coupons_num;
//				}
//			}
        }
        return $total_money;
    }

	/**
	 * 获得cookie里的所有商品信息
	 */
	public function get_all_product()
	{
		$all_product = array();

		$cookie_all_product = array();
		$cookie_all_product['group'] = isset($_COOKIE['group']) ? unserialize($_COOKIE['group']) : '';
		$cookie_all_product['goods'] = isset($_COOKIE['goods']) ? unserialize($_COOKIE['goods']) : '';
		$cookie_all_product['coupons'] = isset($_COOKIE['coupons']) ? unserialize($_COOKIE['coupons']) : '';

		$all_product['group'] = $this->get_goods_by_id_and_num($cookie_all_product['group']);
		$all_product['goods'] = $this->get_goods_by_id_and_num($cookie_all_product['goods']);
		$all_product['coupons'] = $this->get_coupons_by_id_and_num($cookie_all_product['coupons']);

		return $all_product;
	}

	/**
	 * 获得用户升级的费用(选购)
	 */
	public function get_user_rank_money($user_id)
	{
		$pay_money = 0;
		$sql = "select new_level from users_level_change_log where level_type=2 and uid=$user_id order by create_time desc";
		$result = $this->db->query($sql)->result();
		if(!empty($result)){
			$new_level=$result[0]->new_level;

			$this->load->model('m_user');
			$is_true = $this->m_user->is_first_upgrade_time_1_1($user_id);
			if($is_true){
				$joinFeeAndMonthFee = config_item('old_join_fee_and_month_fee');
			}else{
				$joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
			}

			if ($new_level == 1) {
				$pay_money = ($joinFeeAndMonthFee[1]['join_fee']);
			}
			if ($new_level == 2) {
				$pay_money = ($joinFeeAndMonthFee[2]['join_fee']);
			}
			if ($new_level == 3) {
				$pay_money = ($joinFeeAndMonthFee[3]['join_fee']);
			}
			if ($new_level == 4) {
				$pay_money = ($joinFeeAndMonthFee[4]['join_fee']);
			}
		}
		return $pay_money;
	}

	/**获取代品券金额**/
	public function get_coupons_total_money(){
		$coupons_total_money=0;
		$id_and_num = isset($_COOKIE['coupons']) ? unserialize($_COOKIE['coupons']) : '';
		if($id_and_num=='' || $id_and_num==array()){
			return $coupons_total_money;
		}

		//有多个商品
		if(strpos($id_and_num,'|')!=false)
		{
			$str=explode('|',$id_and_num);
			foreach($str as $v){
				$sub_str=explode('-',$v);
				$id_list[]=$sub_str[0];
				$num_list[]=$sub_str[1];
			}
		} else {
			$str=explode('-',$id_and_num);
			$id_list[]=$str[0];
			$num_list[]=$str[1];
		}

		foreach ($id_list as $k => $id) {
			$unit=config_item('coupons_money')[$id]*$num_list[$k];  //单种券的总价
			$coupons_total_money+=$unit;
		}
		return $coupons_total_money;
	}

	/**
	 * 获得购物车里的代品券总额
	 */
	public function get_coupons_amount($coupons)
	{
		$coupons_amount = 0;
		if (empty($coupons))
		{
			return $coupons_amount;
		}
		foreach ($coupons as $v)
		{
			$coupons_money = $v['coupons_money'];
			$coupons_num = $v['coupons_num'];
			$coupons_amount += $coupons_money * $coupons_num;
		}
		return $coupons_amount;
	}

	/**
	 * 拼接goods_list
	 * goods_sn(goods_sn_main+ "-" +language_id )
	 * 89210589-1:1$49463861-1:1(goods_sn:数量 $ goods_sn:数量)
	 */
	public function get_goods_list(){
		$all_product=$this->get_all_product();

		$goods_list = array();
		foreach ($all_product['group'] as $v) {
			$group_id = $v['goods_id'];
			$res = $this->db->query("select goods_sn_main from mall_goods_main where goods_id='$group_id' ")->result();
			if (!empty($res)) {
				$goods_sn = $res[0]->goods_sn_main.'-'.'1';
				$group_num = $v['goods_num'];
				$unit = $goods_sn . ':' . $group_num;
				$goods_list[] = $unit;
			}
		}
		foreach ($all_product['goods'] as $v) {
			$goods_id = $v['goods_id'];
			$res = $this->db->query("select goods_sn_main from mall_goods_main where goods_id='$goods_id' ")->result();
			if (!empty($res)) {
				$goods_sn = $res[0]->goods_sn_main.'-'.'1';
				$goods_num = $v['goods_num'];
				$unit = $goods_sn . ':' . $goods_num;
				$goods_list[] = $unit;
			}
		}
		$goods_list = implode("$", $goods_list);

		return $goods_list;
	}

	/**
	 * 获取升级套餐价格
	 */
	public function get_upgrade_money($user_rank,$user_id)
	{
		$pay_money = 250;
		$product_set = isset($_COOKIE['product_set']) ? unserialize($_COOKIE['product_set']) : array();
		if(empty($product_set)){
			redirect(base_url('/'));exit;
		}

		$this->load->model('m_user');
		$joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();

		/**
		 * 　如果不是免费店铺，查看是否是１.１号之前升级的。如果是，得到当时升级店铺的费用，计算升級差价
		 */
		/*$old_join_fee = $this->m_user->upgrade_before_1_1_amount($user_id,$user_rank);
		if($old_join_fee !== FALSE){
			$joinFeeAndMonthFee[$user_rank]['join_fee'] = $old_join_fee;
		}*/

        $pay_money = $joinFeeAndMonthFee[$product_set['level']]['join_fee'] - $joinFeeAndMonthFee[$user_rank]['join_fee'];

		return $pay_money;
	}

    /***
     * @param $attr 订单基本数据
     * @param $split_list 供应商对应的商品列表
     * @param $order_type   订单类型(choose=>选购订单,upgrade=>升级订单,coupons=>代品券订单,exchange=>换货订单)
     * @return order id
     */
    public function make_order($attr,$split_list,$order_type,$dpq=FALSE){

        $this->load->model('m_coupons');
        $this->load->model('tb_mall_goods');
        $this->load->model('m_suite_exchange_coupon');

//        //防止重复提交
//		$redis_key = md5(serialize($attr));
////        $this->m_debug->log($redis_key);
//		$res = $this->o_trade->redis_anti_repeat($redis_key);
//		//缓存存在该值，阻止重复提交
//		if($res){
//            echo json_encode(array('success' => false,'msg'=>lang('not_repeat_submit')));
//            exit();
//		}

        //获得默认收货地址(如果与开始选择时的国家不一致,则阻止提交)
        $this->load->model("tb_trade_user_address");
        $address_list = $this->tb_trade_user_address->get_deliver_address_by_id($attr['address_id']);
        $address_id = $this->tb_trade_user_address->get_area_by_addr($address_list);

        $cur_language_id = get_cookie('curLan_id', true);

        //所选区域ID
        $country_id = $this->session->userdata('location_id');

        if($address_id != $country_id)
        {
            echo json_encode(array('success'=>false,'msg'=>lang('default_country_not_ok')));
            exit();
        }

        //如果是美国地址,新的验证规则
        if($attr['country'] == 840)
        {
            $this->load->model('m_validate');
            $this->m_validate->verify_addr_for_us($address_list);
            //拼接姓名
            $attr['consignee'] = $address_list['first_name'].' '.$address_list['last_name'];
        }

        //如果是韩国地址，字段必须要有zip_code值
        if($address_list['country'] == 410)
        {
            $this->load->model('m_validate');
            $this->m_validate->validate_addr_for_korea($address_list);
        }

        //获取用户的代品券总额
        $coupons_list = $this->m_coupons->get_coupons_list($attr['customer_id']);
        $coupons_total_money = $coupons_list['total_money'] * 100;

        $language_id = (int)$this->session->userdata('language_id');

        //获取所有选购的商品
        $all_product = $this->get_all_product();

        //要换的订单信息
        $exchange = isset($_COOKIE['exchange'])?unserialize($_COOKIE['exchange']):array();
        $ex_order_id = isset($exchange['order_id'])?$exchange['order_id']:'';
        $exchange_mon1 = $exchange_mon = isset($exchange['order_money'])?$exchange['order_money']:'';

        //选了代品券为1,否则为0,如果是代品券换购,则为2
        $insert_attr['discount_type'] = $all_product['coupons'] == array() ? '0' : '1';
        if($order_type == 'coupons')
        {
            $insert_attr['discount_type'] = '2';
            $goods_total_money = $this->get_product_total_money_coupons($all_product);
//			print_r($coupons_total_money);print_r($goods_total_money);exit;
            if($coupons_total_money >= ($goods_total_money * 100))
            {
                $insert_attr['status'] = '3';
                $insert_attr['payment_type'] = '2';
            }
            else
            {
                $insert_attr['status'] = '2';
            }
        }

        //为插入主单准备的goods_list数据
        $child_order_goods = [];

        //换货的
        if($order_type == 'exchange') {
            $goods_total_money = $this->get_product_total_money($all_product);
            if (bccomp($exchange_mon,$goods_total_money,2) == 0) {
                //等待发货
                $insert_attr['status'] = '3';
            } else {
                $insert_attr['status'] = '2';
            }
        }
        //运费为0
        $insert_attr['deliver_fee_usd'] = 0;

        //预计发货时间(下单时间延后三天)
        $insert_attr['expect_deliver_date'] = date('Y-m-d',time() + 3600 * 24 * config_item('expect_deliver_date'));

        //顾客id
        $insert_attr['customer_id'] = $attr['customer_id'];

        //店主id
        $insert_attr['shopkeeper_id'] = 0;

        //收货人姓名
        $insert_attr['consignee'] = $attr['consignee'];

        //联系电话
        $insert_attr['phone'] = $attr['phone'];

        //备用电话
        $insert_attr['reserve_num'] = $attr['reserve_num'];

        //获取对应的地区
        $this->load->model("tb_trade_user_address");
        $area = $this->tb_trade_user_address->get_area_by_addr($address_list);
        $insert_attr['area'] = $area;

        //收货地址
        $insert_attr['address'] = trim($attr['address']);

        //送货时间(周一到周五,或者周六到周末)
        $insert_attr['deliver_time_type'] = $attr['deliver_time_type'];

        //备注
        $insert_attr['remark'] = $attr['remark'];

        //是否需要收据
        $insert_attr['need_receipt'] = $attr['need_receipt'];

        //币种
        $insert_attr['currency'] = 'USD';

        //下单时兑美元汇率
        $insert_attr['currency_rate'] = 1;

        //邮编
        $insert_attr['zip_code'] = $attr['zip_code'];

        //韩国海关号
        $insert_attr['customs_clearance'] = $attr['customs_clearance'];

        //主订单ID
		$this->load->model("m_split_order");

        if($order_type == 'exchange'){
            $component_id = $this->m_split_order->create_component_id_ex('PC');
        }else{
            $component_id = $this->m_split_order->create_component_id('P');
        }

        //插入主订单时的信息
        $main_insert_attr = $insert_attr;

        $this->db->trans_begin();//事務開始

        $count = 0;
        //循环提交子订单
        foreach($split_list as $k => $item)
        {
            $goods_all = array();
            ++$count;

            //第一个订单插入代品券金额 //2017-03-01 00:00:00 后升级订单不在发放代品券
            if($count == 1 && date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon'))
            {
                $insert_attr['discount_amount_usd'] = ($this->get_coupons_amount($all_product['coupons'])) * 100;
            }
            else
            {
                $insert_attr['discount_amount_usd'] = 0;
            }
            //不需要拆单
            if(count($split_list) == 1)
            {
                //换货订单
                if($order_type == 'exchange'){
                    //普通订单,关联的order id 为自身id
                    $insert_attr['order_id'] = $this->m_split_order->create_component_id_ex('NC');
                    $insert_attr['order_prop'] = '0';
                    $insert_attr['attach_id'] = $insert_attr['order_id'];
                }else {
                    //普通订单,关联的order id 为自身id
                    $insert_attr['order_id'] = $this->m_split_order->create_component_id('N');
                    $insert_attr['order_prop'] = '0';
                    $insert_attr['attach_id'] = $insert_attr['order_id'];
                }
            }
            else
            {
                if($order_type == 'exchange'){
                    //普通订单,关联的order id 为自身id
                    $insert_attr['order_id'] = $this->m_split_order->create_component_id_ex('CC');
                    $insert_attr['order_prop'] = '1';
                    $insert_attr['attach_id'] = $component_id;
                }else {
                    $insert_attr['order_id'] = $this->m_split_order->create_component_id('C');
                    $insert_attr['order_prop'] = '1';
                    $insert_attr['attach_id'] = $component_id;
                }
            }

            //供应商id
            $insert_attr['shipper_id'] = $k;

            $goods_amount = 0;
            $goods_list = array();
            $main_goods_list = array();

            foreach($item as $goods)
            {
                //子商品表增加字段purchase_price,成本价由此字段决定
//                $mall_goods_main = $this->db->select('goods_sn_main,purchase_price,goods_number')
//                    ->where('goods_sn', $goods['goods_sn'])
//                    ->where('language_id', $language_id)
//                    ->get('mall_goods')
//                    ->row_array();
                $this->load->model("tb_mall_goods");
                $mall_goods_main = $this->tb_mall_goods->get_one('goods_sn_main,purchase_price,goods_number',
                    ['goods_sn'=>$goods['goods_sn'],'language_id'=>$language_id]);

                if(empty($mall_goods_main)){
                    continue;
                }

                $goods_info = $this->db->select('goods_name,shop_price,shipper_id,store_code,is_promote')
                    ->where('goods_sn_main', $mall_goods_main['goods_sn_main'])
                    ->where('language_id', $language_id)
                    ->get('mall_goods_main')
                    ->row_array();

                if(empty($goods_info)) {
                    continue;
                }

                if($goods['quantity'] > $mall_goods_main['goods_number']){
                    echo json_encode(array('code'=>'204','msg'=>'<'.$goods_info['goods_name'].'>'.lang('understock')));
                    exit;
                }

                //获取商品价格
               $goods_info['shop_price'] = $this->tb_mall_goods->get_goods_price($goods['goods_sn']);

                //如果是代品券选购,商品价格不能有小数点
                if($order_type == "coupons"){
                    $goods_info['shop_price'] = round($goods_info['shop_price']);
                }

                //订单商品总价
                $goods_amount += $goods_info['shop_price'] * $goods['quantity'] * 100;

                //goods list
                $goods_list[] = $goods['goods_sn'].":".$goods['quantity'];

                //所有的 goods list
                $child_order_goods[][$goods['goods_sn']] = [
                    "goods_sn"=>$goods['goods_sn'],
                    "goods_number"=>$goods['quantity']
                ];
                //所有商品清单,用于库存增减
                $goods_all[] = $goods;
            }

            //商品金额
            $insert_attr['goods_amount'] = $goods_amount;
            $insert_attr['goods_amount_usd'] = $goods_amount;

            //goods list
            $insert_attr['goods_list'] = implode('$',$goods_list);

            //订单金额
            $insert_attr['order_amount'] = $insert_attr['discount_amount_usd'] + $goods_amount ;
            $insert_attr['order_amount_usd'] = $insert_attr['discount_amount_usd'] + $goods_amount;

            //如果是选购订单,订单利润为0,支付类型为预付款,订单状态待发货
            if($order_type == 'choose')
            {
                $insert_attr['order_profit_usd'] = 0 * 0.8;
                $insert_attr['payment_type'] = '1';
                $insert_attr['status'] = Order_enum::STATUS_SHIPPING;
                $insert_attr['order_type'] = '1';
                $insert_attr['pay_time'] = date('Y-m-d H:i:s',time());
            }

            //如果是升级订单,订单利润=订单金额*0.8,订单状态待付款
            if($order_type == 'upgrade')
            {
                $insert_attr['order_profit_usd'] = $insert_attr['order_amount_usd'] * 0.8;
                $insert_attr['status'] = Order_enum::STATUS_CHECKOUT;
                $insert_attr['order_type'] = '2';
            }

			//如果是换货订单exchange
			if($order_type == 'exchange') {
                $insert_attr['order_type'] = '5';//订单类型，换货订单
                $insert_attr['remark'] = $exchange['order_id'].'#'.$insert_attr['remark'];

                //如果所选的商品价值刚好等于原来订单的实付金额，则不用在支付
                if($exchange_mon * 100 >= $insert_attr['goods_amount_usd']){
                    $insert_attr['discount_amount_usd'] = $insert_attr['goods_amount_usd'];
                    $insert_attr['order_amount'] = 0;
                    $insert_attr['order_amount_usd'] = 0;
                    $insert_attr['pay_time'] = date('Y-m-d H:i:s',time());
//                    $insert_attr['txn_id'] = 'TD';
                    $exchange_mon = $exchange_mon - $insert_attr['goods_amount_usd'] / 100;
                }else{
//                    $insert_attr['status'] = Order_enum::STATUS_CHECKOUT;
                    $insert_attr['discount_amount_usd'] = $exchange_mon * 100;
                    $insert_attr['order_amount'] = $insert_attr['goods_amount_usd'] - $exchange_mon * 100;
                    $insert_attr['order_amount_usd'] = $insert_attr['order_amount'];
                    $exchange_mon = 0;
                }
                $insert_attr['order_profit_usd'] = $insert_attr['order_amount_usd'] * 0.8;
			}
            //如果是代品券订单
            if($order_type == 'coupons')
            {
                //如果用户代品券金额大于商品金额,则无需补差价
                if(($coupons_total_money >= $insert_attr['goods_amount_usd']))
                {
                    $insert_attr['discount_amount_usd'] = $insert_attr['goods_amount_usd'];
                    $insert_attr['order_amount'] = 0;
                    $insert_attr['order_amount_usd'] = 0;
                    $insert_attr['pay_time'] = date('Y-m-d H:i:s',time());
                    $coupons_total_money = $coupons_total_money - $insert_attr['goods_amount_usd'];
                }
                else
                {
                    $insert_attr['discount_amount_usd'] = $coupons_total_money;
                    $insert_attr['order_amount'] = $insert_attr['goods_amount_usd'] - $coupons_total_money;
                    $insert_attr['order_amount_usd'] = $insert_attr['order_amount'];
                    $coupons_total_money = 0;
                }
                $insert_attr['order_profit_usd'] = $insert_attr['order_amount_usd'] * 0.8;
                $insert_attr['order_type'] = '3';
            }

            //如果未设置，填充默认的score_year_month
            if(!isset($insert_attr['score_year_month']))
            {
                $insert_attr['score_year_month'] = date('Ym');
            }

            //插入数据
//            $this->db->insert('trade_orders',$insert_attr);
            $this->load->model("tb_trade_orders");
            $this->tb_trade_orders->insert_one($insert_attr);

            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($insert_attr['goods_list'],$language_id);
            $this->m_trade->insert_order_goods_info($insert_attr['order_id'],$goods_order);

            //修改商品库存
            $this->update_goods_number($goods_all, $insert_attr['order_id']);
        }


        //换货的
        if($order_type == 'exchange')
        {
            $goods_total_money = $this->get_product_total_money($all_product);
            if (bccomp($exchange_mon1,$goods_total_money,2) == 0) {
                //等待发货
//                $order_info = $this->tb_trade_orders->getOrderInfo($ex_order_id, array('status,remark'));
//                $str = '换货订单：'.$insert_attr['order_id'];
//                $res = $this->db->where('order_id',$ex_order_id)->update('trade_orders',array('status'=> '90','remark'=>$str));

                //原订单状态退货完成
//                $this->db->where('order_id',$ex_order_id)->update('trade_orders',array('status'=> Order_enum::STATUS_CANCEL));
                $this->load->model("tb_trade_orders");
                $this->tb_trade_orders->update_one(["order_id"=>$ex_order_id],array('status'=>Order_enum::STATUS_CANCEL));
                //订单同步到erp
                $insert_data = array();
                $insert_data['oper_type'] = 'modify';
                $insert_data['data']['order_id'] = $ex_order_id;
                $insert_data['data']['status'] = Order_enum::STATUS_CANCEL;

                //记录到trade_order_remark_record
                $this->db->insert('trade_order_remark_record',array(
                    'order_id'=>$ex_order_id,
                    'type'=>'1',
                    'remark'=>'换货订单:'.$insert_attr['order_id'],
                    'admin_id'=> 0,
                    'operator'=>'system'
                ));
                //备注同步到erp
                $insert_data_remark = array();
                $insert_data_remark['oper_type'] = 'remark';
                $insert_data_remark['data']['order_id'] = $ex_order_id;
                $insert_data_remark['data']['remark'] = '换货订单:'.$insert_attr['order_id'];
                $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
                $insert_data_remark['data']['recorder'] = 0; //操作人
                $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

                $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注
                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
            }
        }

        //插入主订单
        if(count($split_list) > 1 )
        {
            $main_insert_attr['order_id'] = $component_id;
            $main_insert_attr['order_prop'] = '2';
            $main_insert_attr['attach_id'] = $component_id;
            $main_insert_attr['status'] = Order_enum::STATUS_COMPONENT;

            //换货订单
            if($order_type == 'exchange'){
                $main_insert_attr['order_type'] = 5;
                $main_insert_attr['remark'] = $ex_order_id.'#';
//                $main_insert_attr['remark'] = $ex_order_id.'#'.$insert_attr['remark'];
            }

            //查询子订单
//            $child_order = $this->db->select('*')
//                ->where('order_prop', '1')
//                ->where('attach_id', $component_id)
//                ->get('trade_orders')->result_array();
            $this->load->model("tb_trade_orders");
            $child_order = $this->tb_trade_orders->get_list_auto([
               "where"=>[
                   'order_prop'=>'1',
                   'attach_id'=>$component_id
               ]
            ]);

            if(empty($child_order))
            {
                echo json_encode(array('success' => false,'msg'=>lang('info_error')));
                exit();
            }

            //初始化
            $main_insert_attr['goods_amount'] = 0;
            $main_insert_attr['goods_amount_usd'] = 0;
            $main_insert_attr['order_amount'] = 0;
            $main_insert_attr['order_amount_usd'] = 0;
            $main_insert_attr['order_profit_usd'] = 0;
            $main_insert_attr['discount_amount_usd'] = 0;

            foreach($child_order as $child)
            {
                if(@$child['goods_list']) {
                    $main_goods_list[] = $child['goods_list'];
                    $main_insert_attr['goods_list'] = implode('$',$main_goods_list);
                }
                $main_insert_attr['goods_amount'] += $child['goods_amount'];
                $main_insert_attr['goods_amount_usd'] += $child['goods_amount_usd'];
                $main_insert_attr['order_amount'] += $child['order_amount'];
                $main_insert_attr['order_amount_usd'] += $child['order_amount_usd'];
                $main_insert_attr['order_profit_usd'] += $child['order_profit_usd'];
                $main_insert_attr['discount_amount_usd'] += $child['discount_amount_usd'];
            }

            if(!isset($main_insert_attr['score_year_month']))
            {
                //订单业绩年月
                $main_insert_attr['score_year_month'] = date('Ym');
            }

//            $this->db->insert('trade_orders',$main_insert_attr);
            $this->load->model("tb_trade_orders");
            $this->tb_trade_orders->insert_one($main_insert_attr);

            //若没有主单的goods_list，自行拼凑一个，因为新结构goods_list字段已经移除
            if(!isset($main_insert_attr['goods_list']))
            {
                $main_insert_attr['goods_list'] = $this->get_goods_list_str($child_order_goods);
            }
            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($main_insert_attr['goods_list'],$language_id);
            $this->m_trade->insert_order_goods_info($component_id,$goods_order);

            //返回order id
            $ret_id = $component_id;
        }
        else
        {
            //返回order id
            $ret_id = $insert_attr['order_id'];
        }

        /***********************************订单类型差异化***************************************/

        //如果是选购订单
        if($order_type == 'choose')
        {
            //发放代品券
            foreach($all_product['coupons'] as $item) {
                $this->m_suite_exchange_coupon->add($attr['customer_id'],$item['coupons_money'],$item['coupons_num']);
            }

            //如果勾选了收据 插入收据邮件
            if(isset($attr['need_receipt']) && $attr['need_receipt']) {
                $this->db->insert('sync_send_receipt_email',array('uid'=>$attr['customer_id'],'order_id'=>$ret_id,'type'=>0));
            }

            //变成已选购状态
            $this->db->where('id',$attr['customer_id'])->set('is_choose',1)->update('users');
        }

        //如果是升级订单
        if($order_type == 'upgrade')
        {
            //升级订单不在发放代品券
            // 防止 cookie 丢失数据，把券存入数据库//2017-03-01 00:00:00 后升级订单不在发放代品券
            if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')) {
                if (!empty($all_product['coupons'])) {
                    foreach ($all_product['coupons'] as $v) {
                        $coupons_attr = array(
                            'user_id' => $attr['customer_id'],
                            'order_id' => $ret_id,
                            'coupons_value' => $v['coupons_money'],
                            'coupons_num' => $v['coupons_num']
                        );
                        $this->db->insert('temp_save_coupons', $coupons_attr);
                    }
                }
            }

            // 升级订单需要插入 trade_orders_type 以区分非套餐订单
            $product_set = isset($_COOKIE['product_set']) ? unserialize($_COOKIE['product_set']) : array();
            $this->db->insert('trade_orders_type', array(
                'order_id' => $ret_id,
                'type' => 1,
                'level' => $product_set['level'],
                'amount' => $product_set['amount'],
            ));
        }
        //如果是换货订单
        if($order_type == 'exchange')
        {
            // 升级订单的换货订单需要插入 trade_orders_type 以区分非套餐订单
            $exchange = isset($_COOKIE['exchange']) ? unserialize($_COOKIE['exchange']) : array();

			//升级订单的换货
			if($exchange['type'] == 'exchange'){
				$this->db->insert('trade_orders_type', array(
						'order_id' => $ret_id,
						'type' => 1,
						'level' => $exchange['now_level'],
						'amount' => $insert_attr['goods_amount_usd'] / 100,
				));
			}
        }

        //保存到trade_order_to_erp_log表,再定时推送到erp
//        $this->load->model('m_erp');
        $this->load->model("tb_trade_orders");
        //保存到订单推送队列表,再定时推送到erp
        if(true){
            if (count($split_list) > 1)
            {
                $where_trade_orders_queue = ['attach_id'=>$ret_id,'order_prop'=>'1'];
            }else{
                $where_trade_orders_queue = ['order_id'=>$ret_id];
            }
            $this->load->model("o_erp");
            $this->o_erp->trade_order_create($where_trade_orders_queue,$cur_language_id);
        }

//        //修改商品库存
//        $this->update_goods_number($goods_all);
        if($dpq==1){//代品券订单时的处理
            $ret_id =$ret_id ? $ret_id : FALSE;
            if($ret_id === FALSE)
            {
                echo json_encode(array('success' => false,'msg'=>lang('info_error')));
                exit();
            }
            if(!isset($attr['customer_id']) || $attr['customer_id'] == ''){
                echo json_encode(array('success' => false,'msg'=>lang('info_error')));
                exit();
            }
            $coupons_list = $this->m_coupons->get_coupons_list($attr['customer_id']);
//            $goods_amount_usd = $this->db->query("select goods_amount_usd from trade_orders WHERE order_id = '$ret_id'")->row()->goods_amount_usd;
            $goods_amount_usd = $this->tb_trade_orders->get_one("goods_amount_usd",["order_id"=>$ret_id])["goods_amount_usd"];
            $goods_amount_usd = $goods_amount_usd/100;

            //不需要补差价,直接扣除代品券
            if(($coupons_list['total_money']) >= $goods_amount_usd)
            {
                $this->load->model("m_suite_exchange_coupon");
                $this->m_suite_exchange_coupon->useCoupon($attr['customer_id'],$goods_amount_usd);
            }
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('success' => false,'msg'=>lang('info_error')));
            exit();
        }
        else
        {
            $this->db->trans_commit();
            return $ret_id ? $ret_id : FALSE;
        }
    }

    /**
     * 若没有主单的goods_list，根据子订单产品列表，自行拼凑一个，
     * 因为新结构goods_list字段已经移除
     * @param $child_order_goods
     * @return string
     */
    public function get_goods_list_str($child_order_goods)
    {
        $res = "";
        foreach($child_order_goods as $k=>$v)
        {
            if($v)
            {
                foreach($v as $i)
                {
                    if($res)
                    {
                        $res .= "$";
                    }
                    $res .= $i['goods_sn'].":".$i['goods_number'];
                }
            }
        }
        return $res;
    }

    //根据address_id 获取国家码
	public function get_default_address_country($address_id){
		$default_country='';
        $this->load->model("tb_trade_user_address");
        $res = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);
        if(!empty($res)){
			$default_country=$res["country"];
		}
		return $default_country;
	}


	//重新选购套装的用户的信息
	public function again_choose_group($user_id){
		$this->load->model('m_forced_matrix');
		$data=array();
		$sql="select * from users_level_change_log where uid=$user_id order by create_time ASC;";
		$res=$this->db->query($sql)->result_array();
		if(!empty($res)){
			$data['user_id']=$user_id;
			$data['old_level']=$res[0]['old_level'];
			$data['new_level']=$res[0]['new_level'];
			$data['create_time']=$res[0]['create_time'];
			$data['name']=$this->m_forced_matrix->userInfo($user_id)->name;

			if($data['old_level']==4)$data['old_level']=lang('member_free');
			if($data['old_level']==3)$data['old_level']=lang('member_silver');
			if($data['old_level']==2)$data['old_level']=lang('member_platinum');
			if($data['old_level']==1)$data['old_level']=lang('member_diamond');

			$this->load->model('m_user');
			$joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();

			if($data['new_level']==3){
				$data['new_level']=lang('member_silver');
				$data['pay_money']=$joinFeeAndMonthFee[3]['join_fee'];
			}
			if($data['new_level']==2){
				$data['new_level']=lang('member_platinum');
				$data['pay_money']=$joinFeeAndMonthFee[2]['join_fee'];
			}
			if($data['new_level']==1){
				$data['new_level']=lang('member_diamond');
				$data['pay_money']=$joinFeeAndMonthFee[1]['join_fee'];
			}
		}
		return $data;
	}

    //修改商品库存(减)
    public function update_goods_number($goods_all , $order_id = 0){
        if($goods_all == array()){
            return false;
        }

        foreach($goods_all as $item)
        {
//            $goods_info = $this->db->select('product_id,goods_number,is_lock')
//                ->where('goods_sn',$item['goods_sn'])->get('mall_goods')->row_array();
            $this->load->model("tb_mall_goods");
            $goods_info = $this->tb_mall_goods->get_one('product_id,goods_number,is_lock',['goods_sn'=>$item['goods_sn']]);
			//锁定的不能增减库存
			if(empty($goods_info) || $goods_info['is_lock'] == 1){
				continue;
			}
			$new_qty = $goods_info['goods_number'] - $item['quantity'];
			$goods_number = $new_qty < 0 ? 0 : $new_qty;
//			$res = $this->db->where('product_id', $goods_info['product_id'])->set('goods_number',$goods_number)->update('mall_goods');
//            $res = $this->tb_mall_goods->update_one(['product_id'=>$goods_info['product_id']],['goods_number'=>$goods_number]);
            $this->tb_mall_goods->mall_goods_redis_log($goods_info['product_id'],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__.",goods_number:".$goods_info['goods_number'].",quantity:".$item['quantity']);
            $res = $this->tb_mall_goods->update_goods_number_in_redis($goods_info['product_id'],$goods_number);

			//库存同步到erp,取消同步库存到ERP的队列
//			if($res){
//                $goods_num = array();
//				$goods_num['goods_sn'] = $item['goods_sn'];
//				$goods_num['quantity'] = $item['quantity'];
//				$goods_num['inventory'] = $goods_number;
//				$goods_num['order_id'] = $order_id;
//				$goods_num['oper_type'] = 'dec'; //减库存
//				//插入到库存队列表
//				$this->m_erp->trade_order_to_erp_inventory_queue($goods_num);//订单取消或退货，加库存，同步到ERP
//			}
        }
    }


    //修改商品库存(加)
    public function add_goods_number($goods_all, $order_id = 0){
        if($goods_all == array()){
            return false;
        }

        foreach($goods_all as $item) {
//            $goods_info = $this->db->select('product_id,goods_number,is_lock')
//                ->where('goods_sn', $item['goods_sn'])->get('mall_goods')->row_array();
            $this->load->model("tb_mall_goods");
            $goods_info = $this->tb_mall_goods->get_one('product_id,goods_number,is_lock',['goods_sn'=>$item['goods_sn']]);
            //锁定的不能增减库存
            if(empty($goods_info) || $goods_info['is_lock'] == 1){
                continue;
            }
            $new_qty = $goods_info['goods_number'] + $item['quantity'];
            $goods_number = $new_qty < 0 ? 0 : $new_qty;
//            $res = $this->db->where('product_id', $goods_info['product_id'])->set('goods_number', $goods_number)->update('mall_goods');
            $this->tb_mall_goods->mall_goods_redis_log($goods_info['product_id'],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
            $res = $this->tb_mall_goods->update_goods_number_in_redis($goods_info['product_id'],$goods_number);
            //库存同步到erp,取消同步库存到ERP的队列
//            if($res){
//                $goods_num = array();
//                $goods_num['goods_sn'] = $item['goods_sn'];
//                $goods_num['quantity'] = $item['quantity'];
//                $goods_num['inventory'] = $goods_number;
//                $goods_num['order_id'] = $order_id;
//                $goods_num['oper_type'] = 'inc'; //加库存
//                //插入到库存队列表
//                $this->m_erp->trade_order_to_erp_inventory_queue($goods_num);//订单取消或退货，加库存，同步到ERP
//            }
        }
    }

    /* 去升级 */
    public function do_member_upgrade($location_id){

        //检查区域ID
        $country_id_arr = $this->db->query("select country_id from mall_goods_sale_country where country_id = $location_id")->row_array();
        if(empty($country_id_arr)){
            return 1008;
        }
        $group_info = $this->m_group->group_info($location_id);
        return $group_info;
    }

    /* 处理成手机端数组 */
    public function format_array($ret_arr,$location_id){
        $data = array();
        foreach($ret_arr as $k =>$v){
            $group = array();
            $group['group_id'] = $k;
            $group['number'] = $v['number'];
            $group['total'] = $v['total'];
            $group['goods_name'] = $v['goods_name'];
            $group['shop_price'] = $v['shop_price'];
            $group['goods_sn_main'] = $v['goods_sn_main'];
            $group['goods_sn'] = $v['goods_sn_main']."-1";
            $group['language_id'] = $v['language_id'];
            $group['market_price'] = $v['market_price'];
            $group['is_on_sale'] = $v['is_on_sale'];
            $group['goods_info'] = array();

            foreach($v['list'] as $k1=>$v1){
                $goods = array();
                $goods['goods_id'] = $v1['info']['goods_id'];
                $goods['goods_sn_main'] = $v1['info']['goods_sn_main'];
                $goods['goods_sn'] = $v1['info']['goods_sn_main']."-1";
                $goods['goods_name'] = $v1['info']['goods_name'];
                $goods['goods_img'] = $v1['info']['goods_img'];
                $goods['market_price'] = $v1['info']['market_price'];
                $goods['shop_price'] = $v1['info']['shop_price'];
                $goods['is_promote'] = $v1['info']['is_promote'];
                $goods['promote_price'] = $v1['info']['promote_price'];
                $goods['is_on_sale'] = $v1['info']['is_on_sale'];
                $goods['promote_start_date'] = $v1['info']['promote_start_date'];
                $goods['is_hot'] = $v1['info']['is_hot'];
                $goods['is_new'] = $v1['info']['is_new'];
                $goods['is_free_shipping'] = $v1['info']['is_free_shipping'];
                $goods['country_flag'] = $v1['info']['country_flag'];
                $goods['num'] = $v1['num'];

                $group['goods_info'][] = $goods;
            }
            $data[] = $group;
        }
        return $data;
    }
}
