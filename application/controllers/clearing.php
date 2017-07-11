<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class clearing extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_trade');
        $this->load->model('m_group');
        $this->load->model('m_suite_exchange_coupon');
    }

    public function index(){
        // 校验用户登陆状态
        if (empty($this->_userInfo)) {
            redirect(base_url('login').'?redirect=clearing');
        }
        $uid=$this->_userInfo['id'];
        // 头部信息
        $this->_viewData['title']=lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        // 货币
        $this->_viewData['currency_all'] = $this->m_global->getCurrencyList();
        // 语种
        $this->_viewData['language_all'] = $this->m_global->getLangList();
        // 获取收货地址
        $this->load->model("tb_trade_user_address");
        $address_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$this->_viewData['curLocation_id']);

        if (empty($address_list)) {
            $page = "choose_checkout_for_new";
        } else {
            $page = "choose_checkout";
            $this->_viewData['address_list'] = $address_list;
        }

        //获取选购的商品
        $all_product = $this->m_group->get_all_product();

        // 获取购物车数据
        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }
        $this->_viewData['cart_cont'] = $this->m_trade->get_cart_detail_data($this->_viewData['store_id'], $cur_language_id,$this->_viewData['cur_rate'],$this->_viewData['curLocation_id']);

        //获得选购商品的总金额
        $total_money=$this->m_group->get_product_total_money($all_product);

        $this->_viewData['all_product'] = $all_product;
        $this->_viewData['total_money'] = $total_money;

        $this->_viewData['real_pay_money']=0.00;        //实际支付金额

        /***代表升级,不是选购***/
        if(isset($_COOKIE['typetoken']) && $_COOKIE['typetoken']==md5('uptoken'.$this->_userInfo['id'])){
            $this->_viewData['real_pay_money']=$this->m_group->get_upgrade_money($this->_userInfo['user_rank'],$this->_userInfo['id']);
        }

        // 配送方式与费用（单位分）
        $deliver_info = array(
            'type' => "checkout_order_deliver_type_express",
            'fee' => 1000,
        );

        $this->_viewData['deliver_info'] = $deliver_info;
		$this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');
		
        parent::index('mall/', $page, 'header1', 'footer1');
    }


    /***提交选购商品***/
    public function submit_choose_package()
    {
        $this->load->model('tb_trade_orders');

        if($this->_userInfo['user_rank']==4 || $this->_userInfo['is_choose']==1){
            echo json_encode(array('success' => false,'msg'=>lang('not_repeat_submit')));
            exit();
        }

        $data=$this->input->post('data');
        if($data)
        {
            //获得默认收货地址(如果与开始选择时的国家不一致,则阻止提交)
            $this->load->model("tb_trade_user_address");
            $address_list = $this->tb_trade_user_address->get_deliver_address_by_id($data['address_id']);

			//如果是韩国地址，字段必须要有zip_code值
			if($address_list['country'] == 410)
			{
				$this->load->model('m_validate');
				$this->m_validate->validate_addr_for_korea($address_list);
			}

            //根据选择的地址，获得对应的address code
            $map_country = $this->tb_trade_user_address->get_area_by_addr($address_list);

            $sel_country=isset($_COOKIE['sel_country'])?unserialize($_COOKIE['sel_country']):'';
            if($map_country!=$sel_country){
                echo json_encode(array('success'=>false,'msg'=>lang('default_country_not_ok')));
                exit();
            }

            //获取所有选购的商品
            $all_product = $this->m_group->get_all_product();

            /***如果没有选代品券,则无折扣(为0,否则为1)**/
            if($all_product['coupons']==array()){
                $discount_type='0';
            }else{
                $discount_type='1';
            }

            //所选总价
            $total_amount=$this->m_group->get_product_total_money($all_product);

            //代品券总价
            $coupons_total_money=$this->m_group->get_coupons_amount($all_product['coupons']);

            //商品总价
            $goods_amount=$total_amount-$coupons_total_money;

            //运费为0
            $deliver_fee=0;

            //实际支付金额为0
            $real_pay_money=0;        //实际支付金额

            $goods_list=$this->m_group->get_goods_list();

            //商品不能为空
            if($goods_list=='') {
                echo json_encode(array('success' => false, 'msg' => lang('must_choose_a_product')));
                exit();
            }

            $expect_deliver_date=date('Y-m-d',time()+3600*24*config_item('expect_deliver_date'));   //预计发货时间(下单时间延后三天)

            $this->db->trans_begin();//开始事务

			//获得仓库列表
			$this->load->model('m_split_order');
			$new_arr = $this->m_split_order->split_goods_list($goods_list);
			$store_list = $this->m_split_order->get_store_list($new_arr);

			$cur_language_id = get_cookie('curLan_id', true);
			if (empty($cur_language_id)) {
				$cur_language_id = 1;
			}

			$pay_time = date('Y-m-d H:i:s');

			//不需要拆单
			if(count($store_list) == 1)
			{

				/**变成已选购状态**/
				$user_id=$this->_userInfo['id'];
				$this->db->query("update users set is_choose=1 where id={$user_id}");

				//生成订单号
				$order_id = $this->m_split_order->create_component_id('N');

				/** 訂單商品表 */
				$goods_order = $this->m_trade->get_order_goods_arr($goods_list,$cur_language_id);
				if($goods_order === FALSE){
					echo json_encode(array('success'=>false,'msg'=>'Goods not exist!'));
					exit();
				}
				$this->m_trade->insert_order_goods_info($order_id,$goods_order);

				$trade_orders_data = array(
                    'order_id' =>$order_id,
                    'order_prop'=>'0',
                    'attach_id' => $order_id,
                    'customer_id' => $data['customer_id'],
                    'shopkeeper_id'=>0,
                    'consignee' => $data['consignee'],                      //收货人
                    'phone' => $data['phone'],                              //联系电话
                    'reserve_num' => $data['reserve_num'],                  //备用电话
                    'country_address' => $data['country_address'],                          //送货地址
                    'address' => $data['address'],                          //送货地址
                    'deliver_time_type' => $data['deliver_time_type'],      //送货时间类型(送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日)
                    'goods_list' => $goods_list,                                    //商品列表
                    'remark' => $data['remark'],                            //订单备注
                    'need_receipt' => $data['need_receipt'],                //是否需要收据(0不需要；1不需要)
                    'currency' => 'USD',                                            //币种
                    'currency_rate' => $data['currency_rate'],              //下单时兑美元汇率
                    'goods_amount' => $goods_amount*100,                            //商品总金额()
                    'goods_amount_usd' => $goods_amount*100,                        //商品总金额(美元)
                    'deliver_fee' => $deliver_fee,                                  //订单运费(0.00)
                    'order_amount' => $real_pay_money,                              //订单实付金额(0.00)
                    'discount_type'=>$discount_type,
                    'discount_amount_usd'=>$coupons_total_money*100,
                    'order_amount_usd' => $total_amount*100,                     //订单金额(美元)
                    'order_profit_usd' => $real_pay_money*0.8,                   //(修改：因为升级的时候利润已经发过了)订单利润(美元)
                    'payment_type'=>'1',
                    'status'=>Order_enum::STATUS_SHIPPING,
                    'expect_deliver_date'=>$expect_deliver_date,                   //预计发货时间
                    'area'=>$address_list['country'],                            //获取国家码
                    'pay_time'=>$pay_time,                            //支付时间
                    'zip_code'=>$data['zip_code'],                                  //邮编
                    'customs_clearance'=>$data['customs_clearance']                 //海关号
                );
				//$this->db->insert('trade_orders', $trade_orders_data);
                $this->tb_trade_orders->insert_one($trade_orders_data);

			}

			//需要拆单
			if(count($store_list) > 1)
			{
				//当前语言id
				$language_id = (int)$this->session->userdata('language_id');

				//拆分后的goods_list
				$goods_list_arr = $this->m_split_order->split_goods_list($goods_list);

				//生成主订单号
				$component_id = $this->m_split_order->create_component_id('P');


				$goods_amount = 0;
				$goods_list = "";
				$num = 0;

				foreach($store_list as $k =>$v)
				{
					++$num;
					foreach($goods_list_arr as $k2 => $v2)
					{

//						$res_sn = $this->db->select('goods_sn_main')->from('mall_goods')->where('goods_sn',$k2)->where('language_id',$language_id)->get()->row_array();
                        $this->load->model("tb_mall_goods");
                        $res_sn = $this->tb_mall_goods->get_one("goods_sn_main",
                            ["goods_sn"=>$k2,"language_id"=>$language_id]);

//						$res = $this->db->select('shop_price,store_code')->from('mall_goods_main')->where('goods_sn_main',$res_sn['goods_sn_main'])->where('language_id',$language_id)->get()->row_array();
                        $this->load->model("tb_mall_goods_main");
                        $res = $this->tb_mall_goods_main->get_one_auto(
                            [
                            "select"=>'shop_price,store_code',
                            "where"=>['goods_sn_main'=>$res_sn['goods_sn_main'],'language_id'=>$language_id]
                            ]
                        );
						//如果仓库相同
						if($res['store_code'] == $v)
						{
							$goods_list[] = $k2.':'.$v2;
							$goods_amount += $res['shop_price']*$v2;
						}
					}

					$coupons_total_money = $num==1?$coupons_total_money:0;

					//生成订单号
					$order_id = $this->m_split_order->create_component_id('C');

					//用$分割
					$goods_list = implode('$', $goods_list);

					/** 訂單商品表 */
					$goods_order = $this->m_trade->get_order_goods_arr($goods_list,$cur_language_id);
					if($goods_order === FALSE){
						echo json_encode(array('success'=>false,'msg'=>'Goods not exist!'));
						exit();
					}
					$this->m_trade->insert_order_goods_info($order_id,$goods_order);

					$order_amount = $goods_amount+$coupons_total_money;

					$trade_orders_data = array(
                        'order_id' =>$order_id,
                        'order_prop'=>'1',
                        'attach_id' => $component_id,
                        'customer_id' => $data['customer_id'],
                        'shopkeeper_id'=>0,
                        'consignee' => $data['consignee'],                      //收货人
                        'phone' => $data['phone'],                              //联系电话
                        'reserve_num' => $data['reserve_num'],                  //备用电话
                        'country_address' => $data['country_address'],
                        'address' => $data['address'],                          //送货地址
                        'deliver_time_type' => $data['deliver_time_type'],      //送货时间类型(送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日)
                        'goods_list' => $goods_list,                                    //商品列表
                        'remark' => $data['remark'],                            //订单备注
                        'need_receipt' => $data['need_receipt'],                //是否需要收据(0不需要；1不需要)
                        'currency' => 'USD',                                            //币种
                        'currency_rate' => $data['currency_rate'],              //下单时兑美元汇率
                        'goods_amount' => $goods_amount*100,                            //商品总金额()
                        'goods_amount_usd' => $goods_amount*100,                        //商品总金额(美元)
                        'deliver_fee' => $deliver_fee,                                  //订单运费(0.00)
                        'order_amount' => $order_amount*100,                              //订单实付金额(0.00)
                        'discount_type'=>$discount_type,
                        'discount_amount_usd'=>$coupons_total_money*100,
                        'order_amount_usd' => $order_amount*100,                     //订单金额(美元)
                        'order_profit_usd' => $real_pay_money*0.8,                   //(修改：因为升级的时候利润已经发过了)订单利润(美元)
                        'payment_type'=>'1',
                        'status'=>Order_enum::STATUS_SHIPPING,
                        'expect_deliver_date'=>$expect_deliver_date,                   //预计发货时间
                        'area'=>$address_list['country'],                            //获取国家码
                        'zip_code'=>$data['zip_code'],                                  //邮编
                        'pay_time'=>$pay_time,                            			//支付时间
                        'customs_clearance'=>$data['customs_clearance'],                 //海关号
                        'store_code'=>$v												//仓库id
                    );

					//$this->db->insert('trade_orders', $trade_orders_data);
                    $this->tb_trade_orders->insert_one($trade_orders_data);

					$goods_list = "";
					$goods_amount = 0;
				}


				//所选总价
				$total_amount=$this->m_group->get_product_total_money($all_product);

				//代品券总价
				$coupons_total_money=$this->m_group->get_coupons_amount($all_product['coupons']);

				//商品总价
				$goods_amount=$total_amount-$coupons_total_money;

				//商品列表
				$goods_list=$this->m_group->get_goods_list();

				/** 訂單商品表 */
				$goods_order = $this->m_trade->get_order_goods_arr($goods_list,$cur_language_id);
				if($goods_order === FALSE){
					echo json_encode(array('success'=>false,'msg'=>'Goods not exist!'));
					exit();
				}
				$this->m_trade->insert_order_goods_info($component_id,$goods_order);

				$trade_orders_data = array(
                    'order_id' =>$component_id,
                    'order_prop'=>'2',
                    'attach_id' => $component_id,
                    'customer_id' => $data['customer_id'],
                    'shopkeeper_id'=>0,
                    'consignee' => $data['consignee'],                      //收货人
                    'phone' => $data['phone'],                              //联系电话
                    'reserve_num' => $data['reserve_num'],                  //备用电话
                    'country_address' => $data['country_address'],
                    'address' => $data['address'],                          //送货地址
                    'deliver_time_type' => $data['deliver_time_type'],      //送货时间类型(送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日)
                    'goods_list' => $goods_list,                                    //商品列表
                    'remark' => $data['remark'],                            //订单备注
                    'need_receipt' => $data['need_receipt'],                //是否需要收据(0不需要；1不需要)
                    'currency' => 'USD',                                            //币种
                    'currency_rate' => $data['currency_rate'],              //下单时兑美元汇率
                    'goods_amount' => $goods_amount*100,                            //商品总金额()
                    'goods_amount_usd' => $goods_amount*100,                        //商品总金额(美元)
                    'deliver_fee' => $deliver_fee,                                  //订单运费(0.00)
                    'order_amount' => $real_pay_money,                              //订单实付金额(0.00)
                    'discount_type'=>$discount_type,
                    'discount_amount_usd'=>$coupons_total_money*100,
                    'order_amount_usd' => $total_amount*100,                     //订单金额(美元)
                    'order_profit_usd' => $real_pay_money*0.8,                   //(修改：因为升级的时候利润已经发过了)订单利润(美元)
                    'payment_type'=>'1',
                    'status'=>Order_enum::STATUS_COMPONENT,
                    'expect_deliver_date'=>$expect_deliver_date,                   //预计发货时间
                    'area'=>$address_list['country'],                            //获取国家码
                    'zip_code'=>$data['zip_code'],                                  //邮编
                    'pay_time'=>$pay_time,                            //支付时间
                    'customs_clearance'=>$data['customs_clearance'],                 //海关号
                    'store_code'=>""												//仓库id
                );

				//生成主订单
				//$this->db->insert('trade_orders',$trade_orders_data);
                $this->tb_trade_orders->insert_one($trade_orders_data);

				/**变成已选购状态**/
				$user_id=$this->_userInfo['id'];
				$this->db->query("update users set is_choose=1 where id={$user_id}");
			}


            //$order_id = $this->db->insert_id();

            //更新库存
            //$this->m_group->update_stock($goods_list);

            foreach($all_product['coupons'] as $v){
                $this->m_suite_exchange_coupon->add($this->_userInfo['id'],$v['coupons_money'],$v['coupons_num']);
            }

            /** 如果勾选了收据 插入收据邮件 */
            if(isset($data['need_receipt']) && $data['need_receipt']){
                $this->db->insert('sync_send_receipt_email',array('uid'=>$data['customer_id'],'order_id'=>$order_id,'type'=>0));
            }

            //删除cookie
//            delete_cookie('sel_country',get_public_domain());

//            $this->db->trans_commit();
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
				//删除cookie
				delete_cookie('sel_country',get_public_domain());
				echo json_encode(array('success' => true,'msg'=>lang('submit_order_ok')));
				exit();
			}
        }
    }
}
