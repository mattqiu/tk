<?php
/**
 * User: jason
 */
class o_trade extends MY_Model
{

    function __construct(){
        parent::__construct();
        $this->load->model('tb_trade_orders');
    }

    /**
     * @param $uid  顾客ID
     * @param $goods_list 拼接sku和数量
     * @param $location_id  区域ID
     * @param $currency 币种
     * @param $address_id 默认收货地址ID
     * @return 下单数据
     */
    public function make_order_page($uid,$goods_list,$location_id,$currency,$address_id){
        $data = array();

        //拆分goods_list
        $this->load->model('m_split_order');
        $this->load->model('m_trade');
        $new_arr = $this->m_split_order->split_goods_list($goods_list);

        //获取拆单列表
        $split_list = $this->m_split_order->get_split_list($new_arr,$location_id);

        $this->load->model("tb_trade_user_address");
        $address_info = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);

        // 无效的收货地址
        if (empty($address_info) || $address_info['uid'] != $uid)
        {
            $ret_code['code'] = 1004;
            return $ret_code;
        }

        $data = $this->get_check_out_data($split_list,$location_id,$currency,$address_info);

        return $data;
    }


    /* 获取提交数据 */
    public function get_check_out_data($split_list,$location_id,$currency,$address){

        $this->load->model('tb_language');
        $this->load->model('m_trade');

        //汇率
        $this->load->model("tb_exchange_rate");
        $rate = $this->tb_exchange_rate->get_one_auto(["select"=>"rate","where"=>['currency'=>$currency]])["rate"];
//        $rate = $this->db->select('rate')->from('exchange_rate')->where('currency', $currency)->get()->row_object()->rate;

        //当前语言
        $language_id = $this->tb_language->get_language_by_location($location_id);

        //所有订单总额
        $order_amount_total = 0;

        //返回数据
        $data = array(
            'order_list'=>array(),
            'consignee'=>'',
            'phone'=>'',
            'zip_code'=>'',
            'address'=>'',
            'code'=>0
        );

        foreach($split_list as $k =>$v)
        {
            //初始化订单数据
            $order = array(
                'goods_info'=>array(),
                'goods_amount'=>0,
                'order_amount'=>0,
                'freight_fee'=>0,
                'goods_weight'=>0,
                'goods_size'=>0
            );

            foreach($v as $v1)
            {
                $item = array();

//                $goods_info = $this->db->select('goods_sn_main,price')->where('goods_sn',$v1['goods_sn'])->where('language_id',$language_id)
//                    ->get('mall_goods')->row_array();
                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one('goods_sn_main,price',
                    ['goods_sn'=>$v1['goods_sn'],'language_id'=>$language_id]);
                if(empty($goods_info)){
                    continue;
                }

                $goods_info_main = $this->db->select('goods_name,country_flag,shop_price,goods_img,is_promote,goods_weight,goods_size,is_free_shipping,doba_drop_ship_fee,doba_ship_cost,is_doba_goods')
                    ->where('goods_sn_main',$goods_info['goods_sn_main'])
                    ->where('language_id',$language_id)
                    ->get('mall_goods_main')->row_array();

                if(empty($goods_info_main)){
                    continue;
                }

                $price = $goods_info['price'];

                //如果是促销产品
                if($goods_info_main['is_promote'] == 1){
                    $goods_info_promote = $this->db->select('promote_price')
                        ->where('goods_sn',$v1['goods_sn'])
                        ->where('start_time <',date("Y-m-d H:i:s"))
                        ->where('end_time >',date("Y-m-d H:i:s"))
                        ->get('mall_goods_promote')->row_array();

                    if(!empty($goods_info_promote)){
                        $price = $goods_info_promote['promote_price']/100;
                    }
                }
                //每个商品信息
                $item['goods_sn'] = $v1['goods_sn'];
                $item['goods_sn_main'] = $goods_info['goods_sn_main'];
                $item['shop_price'] = tps_money_format($price * $rate);
                $item['goods_name'] = $goods_info_main['goods_name'];
                $item['goods_img'] = $goods_info_main['goods_img'];
                $item['country_flag'] = $goods_info_main['country_flag'];
                $item['quantity'] = $v1['quantity'];

                $order['goods_info'][] = $item;

                //查询商品跨区运费
                $international_freight_arr = $this->db->select('freight_fee')->from('trade_freight_fee_international')
                    ->where('goods_sn_main',$goods_info['goods_sn_main'])
                    ->where('country_id',$location_id)
                    ->get()->row_array();

                //1.如果有跨区运费,优先选择跨区运费
                if(!empty($international_freight_arr)){
                    $order['freight_fee'] += tps_money_format($international_freight_arr['freight_fee'] * $rate);
                }

                //2.如果是doba商品,则计算doba运费
                if($goods_info_main['is_doba_goods'] == 1){
                    $doba_fee = $this->m_trade->doba_goods_shipping_fee($v,$address);
                    $order['freight_fee'] = tps_money_format($doba_fee * $rate);
                }

                //3.如果没有跨区运费,且不是包邮商品,不是doba商品,则累加重量、体积
                if(empty($international_freight_arr))
                {
                    if ($goods_info_main['is_free_shipping'] == 0 && $goods_info_main['is_doba_goods'] == 0) {
                        $order['goods_weight'] += $goods_info_main['goods_weight'] * 1000 * $v1['quantity'];
                        $order['goods_size'] += $goods_info_main['goods_size'] * 1000 * $v1['quantity'];
                    }
                }

                //商品总价
                $order['goods_amount'] += tps_money_format($item['shop_price'] * $v1['quantity']);
            }

            //计算运费
            $store = $this->db->select("*")->where("shipper_id", $k)->get("mall_goods_shipper")->row_array();
            if (!empty($store)) {

                //运费(美分)
                $fee = $this->m_trade->get_shipping_fee(
                    $address,
                    $store['area_rule'],
                    $order['goods_weight'],
                    $store['store_location_code'],
                    $v,
                    $order['goods_size'],
                    $location_id
                );
            } else {
                $fee = false;

                //如果是doba订单,返回0
                if($k == 100){
                    $fee = 0;
                }
            }

            if($fee !== false){
                $fee = tps_money_format($fee / 100 * $rate);
                $order['freight_fee'] += $fee;
                $order['order_amount'] = $order['goods_amount'] + $order['freight_fee'];
            }else{
                $order['freight_fee'] = false;
                $order['order_amount'] = '--';
            }

            $order_amount_total += $order['order_amount'];
            $data['order_list'][] = $order;
        }

        $data['order_amount_total'] = $order_amount_total;
        $data['consignee'] = $address['consignee'];
        $data['phone'] = $address['phone'];
        $data['zip_code'] = $address['zip_code'];
        $this->load->model("tb_trade_addr_linkage");
        $address_arr = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($address);
        $data['address'] = $address_arr['country'].' '.$address_arr['address'];

        //检测此订单是否可以提交
        foreach($data['order_list'] as $v){
            if($v['freight_fee'] === false){
                $data['code'] = 1005;
            }
        }

        return $data;
    }


    /**
     * @param $uid  顾客ID
     * @param $new_level 要升的等级
     * @param $goods_list  拼接sku和数量
     * @param $coupons_list 拼接代品券id和数量
     * @param $location_id 区域ID
     * @return 下单数据
     */
    public function make_order_page_for_upgrade($uid,$new_level,$goods_list,$coupons_list,$location_id,$address_id){

        $this->load->model('m_user');
        $this->load->model('tb_language');
        $this->load->model('m_trade');

        $language_id = $this->tb_language->get_language_by_location($location_id);

        $group_num = 0;                 //所选套餐数量

        //订单详情数据
        $data = array(
            'error_code'=>0,
            'order_list'=>array(),
            'coupons_list'=>array(),
            'consignee'=>'',
            'phone'=>'',
            'zip_code'=>'',
            'address'=>'',
            'order_amount_total'=>0,
            'coupons_amount_total'=>0
        );

        //计算升级的费用
        $user = $this->m_user->getUserByIdOrEmail($uid);
        $user_rank = $user['user_rank'];
        $join_fee = $this->m_user->getJoinFeeAndMonthFee();
        $upgrade_money = $join_fee[$new_level]['join_fee']  - $join_fee[$user_rank]['join_fee'];

        //获取拆单列表
        $this->load->model('m_split_order');
        $new_arr = $this->m_split_order->split_goods_list($goods_list);
        $split_list = $this->m_split_order->get_split_list($new_arr,$location_id);

        //订单数据
        foreach($split_list as $k =>$v)
        {
            //初始化订单数据
            $order = array(
                'goods_info'=>array(),
                'goods_amount'=>0,
                'order_amount'=>0,
                'freight_fee'=>0,
            );

            //订单商品数据
            foreach($v as $v1)
            {
                $item = array();
//                $goods_info = $this->db->select('goods_sn_main,price')->where('goods_sn',$v1['goods_sn'])->where('language_id',$language_id)
//                    ->get('mall_goods')->row_array();

                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one('goods_sn_main,price',
                    ['goods_sn'=>$v1['goods_sn'],'language_id'=>$language_id]);

                if(empty($goods_info)){
                    continue;
                }

                $goods_info_main = $this->db->select('goods_name,country_flag,shop_price,goods_img,is_promote,is_alone_sale')
                    ->where('goods_sn_main',$goods_info['goods_sn_main'])
                    ->where('language_id',$language_id)
                    ->get('mall_goods_main')->row_array();

                if(empty($goods_info_main)){
                    continue;
                }

                $price = $goods_info['price'];

                //如果是促销产品
                if($goods_info_main['is_promote'] == 1){
                    $goods_info_promote = $this->db->select('promote_price')->where('goods_sn',$v1['goods_sn'])
                        ->where('start_time <',date("Y-m-d H:i:s"))
                        ->where('end_time >',date("Y-m-d H:i:s"))
                        ->get('mall_goods_promote')->row_array();

                    if(!empty($goods_info_promote)){
                        $price = $goods_info_promote['promote_price']/100;
                    }
                }

                //每个商品信息
                $item['goods_sn'] = $v1['goods_sn'];
                $item['goods_sn_main'] = $goods_info['goods_sn_main'];
                $item['shop_price'] = round($price);
                $item['goods_name'] = $goods_info_main['goods_name'];
                $item['goods_img'] = $goods_info_main['goods_img'];
                $item['country_flag'] = $goods_info_main['country_flag'];
                $item['quantity'] = $v1['quantity'];

                $order['goods_info'][] = $item;

                $order['goods_amount'] += $item['shop_price'] * $v1['quantity'];

                //累加套装数量
                if($goods_info_main['is_alone_sale'] == 2){
                    ++$group_num;
                }
            }

            //订单商品价格 + 订单运费 = 订单金额
            $order['order_amount'] = $order['goods_amount'] + $order['freight_fee'];
            $data['order_list'][] = $order;

            //累加订单金额
            $data['order_amount_total'] += $order['order_amount'];
        }

        //计算代品券总价
        if(!empty($coupons_list)) {
            $new_arr_coupons = $this->m_split_order->split_goods_list($coupons_list);
            $coupons_map = config_item('coupons_money');
            foreach ($new_arr_coupons as $k => $v) {
                $coupons = array();
                $coupons['value'] = $coupons_map[$k];
                $coupons['quantity'] = $v;
                $data['coupons_amount_total'] += $coupons['value'] * $coupons['quantity'];
                $data['coupons_list'][] = $coupons;
            }
        }

        //验证金额是否一致
        $product_money = $data['order_amount_total'] + $data['coupons_amount_total'];
        if($upgrade_money != $product_money){
            $data['error_code'] = 1007;
            return $data;
        }

        //至少要选择一个套装
        if($group_num == 0){
            $data['error_code'] = 1008;
            return $data;
        }

        //获取收货地址
//        $address = $this->db->select('*')->where('id',$address_id)->get('trade_user_address')->row_array();
        $this->load->model("tb_trade_user_address");
        $address = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);
        if(empty($address)){
            $data['error_code'] = 1009;
            return $data;
        }

        $data['consignee'] = $address['consignee'];
        $data['phone'] = $address['phone'];
        $data['zip_code'] = $address['zip_code'];
        $this->load->model("tb_trade_addr_linkage");
        $address_arr = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($address);
        $data['address'] = $address_arr['country'].' '.$address_arr['address'];

        return $data;
    }


    /**
     * 代品券订单详情页
     * @param $uid  顾客ID
     * @param $goods_list  拼接sku和数量
     * @param $location_id 区域ID
     * @return 下单数据
     */
    public function make_order_page_for_coupons($uid,$goods_list,$location_id,$address_id){

        $this->load->model('m_user');
        $this->load->model('tb_language');
        $this->load->model('m_trade');
        $this->load->model('m_coupons');

        $language_id = $this->tb_language->get_language_by_location($location_id);

        //获取代品券总额
        $coupons_info = $this->m_coupons->get_coupons_list($uid);
        $coupons_money = $coupons_info['total_money'];

        //订单详情数据
        $data = array(
            'error_code'=>0,
            'order_list'=>array(),
            'consignee'=>'',
            'phone'=>'',
            'zip_code'=>'',
            'address'=>'',
            'order_amount_total'=>0,
            'coupons_amount_total'=>0
        );

        //获取拆单列表
        $this->load->model('m_split_order');
        $new_arr = $this->m_split_order->split_goods_list($goods_list);
        $split_list = $this->m_split_order->get_split_list($new_arr,$location_id);

        //订单数据
        foreach($split_list as $k =>$v)
        {
            //初始化订单数据
            $order = array(
                'goods_info'=>array(),
                'goods_amount'=>0,
                'order_amount'=>0,
                'freight_fee'=>0,
            );

            //订单商品数据
            foreach($v as $v1)
            {
                $item = array();
//                $goods_info = $this->db->select('goods_sn_main,price')->where('goods_sn',$v1['goods_sn'])->where('language_id',$language_id)
//                    ->get('mall_goods')->row_array();

                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one('goods_sn_main,price',
                    ['goods_sn'=>$v1['goods_sn'],'language_id'=>$language_id]);

                if(empty($goods_info)){
                    continue;
                }

                $goods_info_main = $this->db->select('goods_name,country_flag,shop_price,goods_img,is_promote,is_alone_sale')
                    ->where('goods_sn_main',$goods_info['goods_sn_main'])
                    ->where('language_id',$language_id)
                    ->get('mall_goods_main')->row_array();

                if(empty($goods_info_main)){
                    continue;
                }

                $price = $goods_info['price'];

                //如果是促销产品
                if($goods_info_main['is_promote'] == 1){
                    $goods_info_promote = $this->db->select('promote_price')->where('goods_sn',$k)
                        ->where('start_time <',date("Y-m-d H:i:s"))
                        ->where('end_time >',date("Y-m-d H:i:s"))
                        ->get('mall_goods_promote')->row_array();

                    if(!empty($goods_info_promote)){
                        $price = $goods_info_promote['promote_price']/100;
                    }
                }

                //每个商品信息
                $item['goods_sn'] = $v1['goods_sn'];
                $item['goods_sn_main'] = $goods_info['goods_sn_main'];
                $item['shop_price'] = round($price);
                $item['goods_name'] = $goods_info_main['goods_name'];
                $item['goods_img'] = $goods_info_main['goods_img'];
                $item['country_flag'] = $goods_info_main['country_flag'];
                $item['quantity'] = $v1['quantity'];

                $order['goods_info'][] = $item;

                $order['goods_amount'] += $item['shop_price'] * $v1['quantity'];
            }

            //如果代品券金额小于该订单商品金额
            if($coupons_money < $order['goods_amount']){
                $order['order_amount'] = $order['goods_amount'] - $coupons_money;
                $coupons_money = 0;
            }else{
                $order['order_amount'] = 0;
                $coupons_money = $coupons_money - $order['goods_amount'];
            }

            //累加订单金额
            $data['order_amount_total'] += $order['order_amount'];

            $data['order_list'][] = $order;
        }

        //获取收货地址
        //$address = $this->db->select('*')->where('id',$address_id)->get('trade_user_address')->row_array();
        $this->load->model("tb_trade_user_address");
        $address = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);
        if(empty($address)){
            $data['error_code'] = 1009;
            return $data;
        }

        $data['consignee'] = $address['consignee'];
        $data['phone'] = $address['phone'];
        $data['zip_code'] = $address['zip_code'];
        $this->load->model("tb_trade_addr_linkage");
        $address_arr = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($address);
        $data['address'] = $address_arr['country'].' '.$address_arr['address'];

        return $data;
    }


    /* 提交订单 */
    public function make_order($uid,$shopkeeper_id,$address_id,$location_id,$remark,$goods_list,$currency,$order_from=1){

        $this->load->model('m_trade');
        $this->load->model('m_split_order');
        $this->load->model('m_validate');
        $this->load->model('m_group');
        $this->load->model('tb_language');
        $this->load->model('m_erp');

        $ret_code = array(
            'code'=>0,
            'order_id'=>''
        );

        //兑美元汇率
//        $rate = $this->db->select('rate')->from('exchange_rate')->where('currency', $currency)->get()->row_object()->rate;
        $this->load->model("tb_exchange_rate");
        $rate = $this->tb_exchange_rate->get_one_auto(["select"=>"rate","where"=>['currency'=>$currency]])['rate'];

        $cur_language_id = $this->tb_language->get_language_by_location($location_id);

        // 商品不能为空
        if (empty($goods_list))
        {
            $ret_code['code'] = 1003;
            return $ret_code;
        }

        $this->load->model("tb_trade_user_address");
        $address_info = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);

        //根据地址获取国家码
        $country_code = $this->get_country_code_for_addr_lv2($address_info);

        // 无效的收货地址
        if (empty($address_info) || $address_info['uid'] != $uid || $country_code != $location_id)
        {
            $ret_code['code'] = 1004;
            return $ret_code;
        }

        //如果是美国地址,验证新的规则
        if($address_info['country'] == 840) {
            $this->m_validate->validate_addr_for_us_mobile($address_info);
        }

        //如果是韩国地址，字段必须要有zip_code值
        if($address_info['country'] == 410) {
            $this->m_validate->validate_addr_for_kr_mobile($address_info);
        }

        $new_arr = $this->m_split_order->split_goods_list($goods_list);
        $split_list = $this->m_split_order->get_split_list($new_arr,$location_id);

        $insert_attr = array();

        // 顾客ID
        $insert_attr['customer_id'] = $uid;

        //店铺ID
        $insert_attr['shopkeeper_id'] = $shopkeeper_id;

        //获取对应的地区
        $this->load->model("tb_trade_user_address");
        $area = $this->tb_trade_user_address->get_area_by_addr($address_info);
        $insert_attr['area'] = $area;

        //收货人
        $insert_attr['consignee'] = $address_info['consignee'];
        //联系电话
        $insert_attr['phone'] = $address_info['phone'];
        //备用电话
        $insert_attr['reserve_num'] = $address_info['reserve_num'];
        //收货地址
        $this->load->model("tb_trade_addr_linkage");
        $address_arr = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($address_info);
        $insert_attr['address'] = $address_arr['country'].' '.$address_arr['address'];
        //邮编
        $insert_attr['zip_code'] = $address_info['zip_code'];
        //清关号
        $insert_attr['customs_clearance'] = $address_info['customs_clearance'];

        // 送货时间类型
        $insert_attr['deliver_time_type'] = '1';

        // 订单备注
        $insert_attr['remark'] = $remark;

        // 是否需要收据
        $insert_attr['need_receipt'] = '1';

        // 支付方式。 0 未支付
        $insert_attr['payment_type'] = '0';

        // 币种
        $insert_attr['currency'] = $currency;

        // 兑美元汇率
        $insert_attr['currency_rate'] = $rate;

        // 折扣类型，0 无折扣；折扣金额 0
        $insert_attr['discount_type'] = '0';
        $insert_attr['discount_amount_usd'] = 0;

        //订单类型(普通订单)
        $insert_attr['order_type'] = '4';

        //共同属性赋值到主订单
        $main_insert_attr = $insert_attr;

        //主订单ID
        $component_id = $this->m_split_order->create_component_id('P');

        $this->db->trans_begin();

        //每个订单信息
        foreach($split_list as $k=>$v){
            $goods_weight = 0;          //订单商品总体积
            $goods_size = 0;            //订单商品总大小
            $goods_amount_usd = 0;      //订单商品总额(美元)
            $goods_purchase_price = 0;  //成本价
            $freight_fee_usd = 0;        //运费(USD)
            $goods_list = array();      //goods list
            $goods = array();           //订单所有商品

            //需要拆单
            if (count($split_list) > 1)
            {
                $insert_attr['order_id'] = $this->m_split_order->create_component_id('C');
                $insert_attr['order_prop'] = '1';
                $insert_attr['attach_id'] = $component_id;
            }
            else
            {
                //普通订单,关联的order id 为自身id
                $insert_attr['order_id'] = $this->m_split_order->create_component_id('N');
                $insert_attr['order_prop'] = '0';
                $insert_attr['attach_id'] = $insert_attr['order_id'];
            }

            $insert_attr['order_from'] = $order_from;

            //读取订单的每个商品信息
            foreach($v as $v1)
            {

//                $goods_info = $this->db->select("goods_sn_main, price, color, size")
//                    ->where("goods_sn", $v1['goods_sn'])->where("language_id", $cur_language_id)
//                    ->get("mall_goods")->row_array();

                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one("goods_sn_main, price, color, size",
                    ["goods_sn"=>$v1['goods_sn'],"language_id"=>$cur_language_id]);

                if (empty($goods_info)) {
                    continue;
                }

                $goods_main_info = $this->db->select("goods_weight,goods_size,is_free_shipping,shop_price,purchase_price,market_price,is_doba_goods,goods_name,doba_supplier_id,is_promote,supplier_id,doba_drop_ship_fee,doba_ship_cost,shipper_id")
                    ->where("goods_sn_main", $goods_info['goods_sn_main'])
                    ->where("language_id", $cur_language_id)
                    ->get("mall_goods_main")->row_array();

                if (empty($goods_main_info)) {
                    continue;
                }

                /** 商品促销期 */
                if ($goods_main_info['is_promote'] == 1){
                    $promote = $this->db->select('promote_price')->where('goods_sn',$v1['goods_sn'])
                        ->where('start_time <=', date("Y-m-d H:i:s"))
                        ->where('end_time >=',date("Y-m-d H:i:s"))
                        ->get('mall_goods_promote')->row_array();

                    if(!empty($promote)){
                        $goods_info['price'] = $promote['promote_price']/100;
                    }
                }

                //查看是否有跨区运费
                $international_freight_arr = $this->db->select('freight_fee')->from('trade_freight_fee_international')
                    ->where('goods_sn_main',$goods_info['goods_sn_main'])
                    ->where('country_id',$area)
                    ->get()->row_array();

                //1.如果有跨区运费,优先选择跨区运费
                if(!empty($international_freight_arr)){
                    $freight_fee_usd += tps_money_format($international_freight_arr['freight_fee']);
                }

                //2.如果是doba商品,则计算doba运费
                if($goods_main_info['is_doba_goods'] == 1){
                    $freight_fee_usd += $this->m_trade->doba_goods_shipping_fee($v,$address_arr);
                }

                //3.如果没有跨区运费,且不是包邮商品,不是doba商品,则累加重量、体积
                if(empty($international_freight_arr))
                {
                    if ($goods_main_info['is_free_shipping'] == 0 && $goods_main_info['is_doba_goods'] == 0) {
                        $goods_weight += $goods_main_info['goods_weight'] * 1000 * $v1['quantity'];
                        $goods_size += $goods_main_info['goods_size'] * 1000 * $v1['quantity'];
                    }
                }

                //订单商品金额(USD)
                $goods_amount_usd += $goods_info['price'] * $v1['quantity'];

                //订单商品goods_list
                $goods_list[] = $v1['goods_sn'].':'.$v1['quantity'];

                //订单商品总成本价
                $goods_purchase_price += $goods_main_info['purchase_price'] * $v1['quantity'];

                //商品汇总
                $goods[] = $v1;
            }

            //查询发货方信息
            $shipper_info = $this->db->select('*')->where('shipper_id',$k)->from('mall_goods_shipper')->get()->row_array();
            if(!empty($shipper_info)){
                $fee = $this->m_trade->get_shipping_fee(
                    $address_info,
                    $shipper_info['area_rule'],
                    $goods_weight,
                    $shipper_info['store_location_code'],
                    $v,
                    $goods_size,
                    $location_id
                );
            }else{
                $fee = false;
                if(strstr($k, 'doba')){
                    $fee = 0;
                }
            }

            if($fee !== false){
                $fee = tps_money_format($fee / 100);
                $freight_fee_usd += $fee;
            }else{

                //无法配送
                $ret_code['code'] = 1005;
                return $ret_code;
            }

            //商品 goods_list
            $insert_attr['goods_list'] = implode('$',$goods_list);

            //运费(美分)
            $insert_attr['deliver_fee_usd'] = $freight_fee_usd * 100;
            $insert_attr['deliver_fee'] = tps_money_format($freight_fee_usd * $rate) * 100;

            //商品金额
            $insert_attr['goods_amount_usd'] = $goods_amount_usd * 100;
            $insert_attr['goods_amount'] = tps_money_format($goods_amount_usd * $rate) * 100;

            //订单金额
            $insert_attr['order_amount_usd'] = $insert_attr['deliver_fee_usd'] + $insert_attr['goods_amount_usd'];
            $insert_attr['order_amount'] = $insert_attr['deliver_fee'] + $insert_attr['goods_amount'];

            //订单利润(商品价格 - 成本价 -商品价格 * 0.05)
            $insert_attr['order_profit_usd'] = ($goods_amount_usd - $goods_purchase_price - $goods_amount_usd * 0.05) * 100;

            //发货方ID
            $insert_attr['shipper_id'] = $k;

            //待支付
            $insert_attr['status'] = Order_enum::STATUS_CHECKOUT;

            //如果是doba商品(商品金额 + 运费金额 - 进货价)
            if(strstr($k, 'doba'))
            {
                $insert_attr['order_profit_usd'] = ($goods_amount_usd + $freight_fee_usd - $goods_purchase_price) * 100;
                $insert_attr['shipper_id'] = 100;
            }

            //创建订单
//            $this->db->insert('trade_orders',$insert_attr);
            $this->tb_trade_orders->insert_one($insert_attr);
            //如果是doba订单,保存到表中,付款完成后推送
            if(strstr($k, 'doba'))
            {
                $doba_insert_attr = array();
                $doba_insert_attr['order_id'] = $insert_attr['order_id'];
                $doba_insert_attr['goods_list'] = $insert_attr['goods_list'];

                //$address = $this->db->query("select * from trade_user_address WHERE id = {$address_id}")->row_array();
                $this->load->model("tb_trade_user_address");
                $address = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);

                //$country_name = $this->db->query("select name from trade_addr_linkage where country_code = {$insert_attr['area']} AND  level = 1")->row_array();
                $this->load->model("tb_trade_addr_linkage");
                $country_name = $this->tb_trade_addr_linkage->get_one("name",
                    ["country_code"=>$insert_attr['area'],"level"=>1]);
                $doba_insert_attr['phone'] = $insert_attr['phone'];
                $doba_insert_attr['city'] = $address['city'];
                $doba_insert_attr['country'] = $country_name['name'];
                $doba_insert_attr['firstname'] = $address['first_name'];
                $doba_insert_attr['lastname'] = $address['last_name'];
                $doba_insert_attr['postal'] = $insert_attr['zip_code'];
                $doba_insert_attr['state'] = $address['addr_lv2'];
                $doba_insert_attr['street'] = $address['address_detail'];
                $doba_insert_attr['create_time'] = date('Y-m-d H:i:s',time());

                $this->db->insert('trade_order_doba_log',$doba_insert_attr);
            }

            //添加到订单推送队列表
            if(true){
                $this->load->model("o_erp");
                $where_trade_orders_queue = ["order_id"=>$insert_attr['order_id']];
                $this->o_erp->trade_order_create($where_trade_orders_queue,$cur_language_id);
            }

            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($insert_attr['goods_list'],$cur_language_id);

            //记录所有子订单的商品，为主单做准备
            $child_order_goods[] = $goods_order;
            //为插入主订单准备所有子订单数据，避免再次从数据库查询读取
            $child_order_list[] = $insert_attr;

            $this->m_trade->insert_order_goods_info($insert_attr['order_id'],$goods_order);

            //修改商品库存
            $this->m_group->update_goods_number($goods, $insert_attr['order_id']);
        }

        /********************************************创建主订单***********************************************/

        //如果需要拆单,创建主订单
        if(count($split_list) > 1)
        {
            //查询子订单
//            $child_order = $this->tb_trade_orders->get_list("*",[
//                "order_prop"=>'1',
//                "attach_id"=>$component_id,
//            ]);
            //创建子订单失败
            if(empty($child_order_list))
            {
                $ret_code['code'] = 1006;
                return $ret_code;
            }

            //初始化
            $main_insert_attr['goods_amount'] = 0;
            $main_insert_attr['goods_amount_usd'] = 0;
            $main_insert_attr['order_amount'] = 0;
            $main_insert_attr['order_amount_usd'] = 0;
            $main_insert_attr['order_profit_usd'] = 0;
            $main_insert_attr['discount_amount_usd'] = 0;
            $main_insert_attr['deliver_fee_usd'] = 0;
            $main_insert_attr['order_prop'] = '2';
            $main_insert_attr['order_id'] = $component_id;
            $main_insert_attr['attach_id'] = $component_id;
            $main_insert_attr['status'] = Order_enum::STATUS_COMPONENT;

            foreach($child_order_list as $child)
            {
                if(isset($child['goods_list']))
                {
                    $main_goods_list[] = $child['goods_list'];
                    $main_insert_attr['goods_list'] = implode('$',$main_goods_list);
                }
                $main_insert_attr['goods_amount'] += $child['goods_amount'];
                $main_insert_attr['goods_amount_usd'] += $child['goods_amount_usd'];
                $main_insert_attr['order_amount'] += $child['order_amount'];
                $main_insert_attr['order_amount_usd'] += $child['order_amount_usd'];
                $main_insert_attr['order_profit_usd'] += $child['order_profit_usd'];
                $main_insert_attr['discount_amount_usd'] += $child['discount_amount_usd'];
                $main_insert_attr['deliver_fee_usd'] += $child['deliver_fee_usd'];
            }

            //若没有主单的goods_list，自行拼凑一个，因为新结构goods_list字段已经移除
            if(!isset($main_insert_attr['goods_list']))
            {
                $main_insert_attr['goods_list'] = $this->get_goods_list_str($child_order_goods);
            }

            if(!isset($main_insert_attr['score_year_month']))
            {
                //订单业绩年月
                $main_insert_attr['score_year_month'] = date('Ym');
            }

//            $this->db->insert('trade_orders',$main_insert_attr);
            $this->tb_trade_orders->insert_one($main_insert_attr);
            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($main_insert_attr['goods_list'],$cur_language_id);
            $this->m_trade->insert_order_goods_info($component_id,$goods_order);

            $ret_id = $component_id;
        }
        else
        {
            $ret_id = $insert_attr['order_id'];
        }

        //修改商品库存
//        $this->m_group->update_goods_number($goods);

        //清空购物车
        foreach($split_list as $v){
            foreach($v as $k) {
                $this->load->model("tb_user_cart");
                $this->tb_user_cart->delete_one(["goods_sn"=>$k['goods_sn'],"country_id"=>$location_id]);
            }
        }

        //系统繁忙,请稍后重试
        if ($this->db->trans_status() === FALSE)
        {
            $ret_code['code'] = 1010;
        }
        else
        {
            $ret_code['code'] = 0;
            $ret_code['order_id'] = $ret_id;
            $this->db->trans_commit();
        }

        return $ret_code;
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

    /* 提交订单 (用户升级,代品券)*/
    public function make_order_for_group($uid,$address_id,$location_id,$remark,$goods_list,$order_type,$coupons_list,$level){

        $this->load->model('m_trade');
        $this->load->model('m_split_order');
        $this->load->model('m_validate');
        $this->load->model('m_group');
        $this->load->model('tb_language');
        $this->load->model('m_coupons');
        $this->load->model('m_user');

        $ret_code = array(
            'code'=>0,
            'order_id'=>''
        );

        //商品列表不能为空
        if($goods_list == ''){
            $ret_code['code'] = 1003;
            return $ret_code;
        }

        // 无效的收货地址
        $this->load->model("tb_trade_user_address");
        $address_info = $this->tb_trade_user_address->get_deliver_address_by_id($address_id);

        $country_code = $this->get_country_code_for_addr_lv2($address_info);
        if (empty($address_info) || $address_info['uid'] != $uid || $country_code != $location_id)
        {
            $ret_code['code'] = 1004;
            return $ret_code;
        }

        //如果是美国地址,验证新的规则
        if($address_info['country'] == 840) {
            $this->m_validate->validate_addr_for_us_mobile($address_info);
        }

        //如果是韩国地址，字段必须要有zip_code值
        if($address_info['country'] == 410) {
            $this->m_validate->validate_addr_for_kr_mobile($address_info);
        }

        //获得当前语言ID
        $cur_language_id = $this->tb_language->get_language_by_location($location_id);

        //获得拆单列表
        $new_arr = $this->m_split_order->split_goods_list($goods_list);
        $split_list = $this->m_split_order->get_split_list($new_arr,$location_id);

        //获取用户的代品券总额
        $coupons_info = $this->m_coupons->get_coupons_list($uid);
        $coupons_total_money = $coupons_info['total_money'] * 100;

        //获取所选的代品券金额
        $coupons_value = $this->get_coupons_value($coupons_list);

        //选了代品券为1,否则为0,如果是代品券换购,则为2
        $insert_attr['discount_type'] = $coupons_list == '' ? '0' : '1';
        if($order_type == 3)
        {
            $insert_attr['discount_type'] = '2';
        }

        //运费为0
        $insert_attr['deliver_fee_usd'] = 0;

        //预计发货时间(下单时间延后三天)
        $insert_attr['expect_deliver_date'] = date('Y-m-d',time() + 3600 * 24 * config_item('expect_deliver_date'));

        //顾客id
        $insert_attr['customer_id'] = $uid;

        //店主id
        $insert_attr['shopkeeper_id'] = 0;

        //收货人姓名
        $insert_attr['consignee'] = $address_info['consignee'];

        //联系电话
        $insert_attr['phone'] = $address_info['phone'];

        //备用电话
        $insert_attr['reserve_num'] = $address_info['reserve_num'];

        //获取对应的地区
        $this->load->model("tb_trade_user_address");
        $area = $this->tb_trade_user_address->get_area_by_addr($address_info);
        $insert_attr['area'] = $area;

        //收货地址
        $this->load->model("tb_trade_addr_linkage");
        $address_arr = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($address_info);
        $insert_attr['address'] = $address_arr['country'].' '.$address_arr['address'];

        //送货时间(周一到周五,或者周六到周末)
        $insert_attr['deliver_time_type'] = '1';

        //备注
        $insert_attr['remark'] = $remark;

        //是否需要收据
        $insert_attr['need_receipt'] = '1';

        //币种
        $insert_attr['currency'] = 'USD';

        //下单时兑美元汇率
        $insert_attr['currency_rate'] = 1;

        //邮编
        $insert_attr['zip_code'] = $address_info['zip_code'];

        //韩国海关号
        $insert_attr['customs_clearance'] = $address_info['customs_clearance'];

        //主订单ID
        $component_id = $this->m_split_order->create_component_id('P');

        //插入主订单时的信息
        $main_insert_attr = $insert_attr;

        $count = 0;


        /**** 事务开始 *****/
        $this->db->trans_begin();

        //循环提交子订单
        foreach($split_list as $k => $item)
        {
            $goods_all = array();
            ++$count;

            //第一个订单插入代品券金额
            if($count == 1) {
                $insert_attr['discount_amount_usd'] = $coupons_value;
            } else {
                $insert_attr['discount_amount_usd'] = 0;
            }

            //不需要拆单
            if(count($split_list) == 1)
            {
                //普通订单,关联的order id 为自身id
                $insert_attr['order_id'] = $this->m_split_order->create_component_id('N');
                $insert_attr['order_prop'] = '0';
                $insert_attr['attach_id'] = $insert_attr['order_id'];
            }
            else
            {
                $insert_attr['order_id'] = $this->m_split_order->create_component_id('C');
                $insert_attr['order_prop'] = '1';
                $insert_attr['attach_id'] = $component_id;
            }

            //供应商id
            $insert_attr['shipper_id'] = $k;

            $goods_amount = 0;
            $goods_list = array();
            $main_goods_list = array();

            foreach($item as $goods)
            {
                //获得主sku
//                $goods_info = $this->db->select('goods_sn_main,purchase_price')
//                    ->where('goods_sn', $goods['goods_sn'])
//                    ->where('language_id', $cur_language_id)
//                    ->get('mall_goods')
//                    ->row_array();

                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one('goods_sn_main,purchase_price',
                    ['goods_sn'=>$goods['goods_sn'],'language_id'=>$cur_language_id]);

                if(empty($goods_info)){
                    continue;
                }

                $goods_info_main = $this->db->select('shop_price,shipper_id,store_code,is_promote')
                    ->where('goods_sn_main', $goods_info['goods_sn_main'])
                    ->where('language_id', $cur_language_id)
                    ->get('mall_goods_main')
                    ->row_array();

                if(empty($goods_info_main)) {
                    continue;
                }

                //如果是促销商品,则取促销价
                if($goods_info_main['is_promote'] == '1') {
                    $promote_price_arr = $this->db->select('promote_price')
                        ->where('goods_sn_main',$goods_info['goods_sn_main'])
                        ->where('start_time <',date("Y-m-d H:i:s"))
                        ->where('end_time >',date("Y-m-d H:i:s"))
                        ->get('mall_goods_promote')->row_array();
                    if(!empty($promote_price_arr)){
                        $goods_info_main['shop_price'] = round($promote_price_arr['promote_price']/100);
                    }
                }

                //商品价格不能有小数点
                $goods_info_main['shop_price'] = round($goods_info_main['shop_price']);

                //订单商品总价
                $goods_amount += $goods_info_main['shop_price'] * $goods['quantity'] * 100;

                //goods list
                $goods_list[] = $goods['goods_sn'].":".$goods['quantity'];

                //所有商品清单,用于库存增减
                $goods_all[] = $goods;
            }

            //商品金额
            $insert_attr['goods_amount'] = $goods_amount;
            $insert_attr['goods_amount_usd'] = $goods_amount;

            //goods list
            $insert_attr['goods_list'] = implode('$',$goods_list);

            //如果是升级订单,订单利润=订单金额*0.8,订单状态待付款
            if($order_type == 2)
            {
                //订单金额
                $insert_attr['order_amount'] = $insert_attr['discount_amount_usd'] + $goods_amount ;
                $insert_attr['order_amount_usd'] = $insert_attr['discount_amount_usd'] + $goods_amount;

                $insert_attr['status'] = Order_enum::STATUS_CHECKOUT;
                $insert_attr['order_type'] = '2';
            }

            //如果是代品券订单
            if($order_type == 3)
            {
                //如果用户代品券金额大于商品金额,则无需补差价
                if(($coupons_total_money >= $insert_attr['goods_amount_usd']))
                {
                    $insert_attr['discount_amount_usd'] = $insert_attr['goods_amount_usd'];
                    $insert_attr['order_amount'] = 0;
                    $insert_attr['order_amount_usd'] = 0;
                    $insert_attr['pay_time'] = date('Y-m-d H:i:s',time());
                    $coupons_total_money = $coupons_total_money - $insert_attr['goods_amount_usd'];
                    $insert_attr['status'] = Order_enum::STATUS_SHIPPING;
                }
                else
                {
                    $insert_attr['discount_amount_usd'] = $coupons_total_money;
                    $insert_attr['order_amount'] = $insert_attr['goods_amount_usd'] - $coupons_total_money;
                    $insert_attr['order_amount_usd'] = $insert_attr['order_amount'];
                    $insert_attr['status'] = Order_enum::STATUS_CHECKOUT;
                    $coupons_total_money = 0;
                }
                $insert_attr['order_type'] = '3';
            }

            //订单利润
            $insert_attr['order_profit_usd'] = $insert_attr['order_amount_usd'] * 0.8;

            //插入数据
//            $this->db->insert('trade_orders',$insert_attr);
            $this->tb_trade_orders->insert_one($insert_attr);

            //保存到trade_order_to_erp_log表,再定时推送到erp
//            $this->m_erp->add_order_to_erp_log($insert_attr);

            //组装数组,添加到订单推送表
//            $syc = $this->db->select('*')->where('order_id',$insert_attr['order_id'])->get('trade_orders')->row_array();
//            $syc = $this->tb_trade_orders->get_one("*",['order_id',$insert_attr['order_id']]);
//
//            //组装数组,添加到订单推送表
//            $insert_data = array();
//            $insert_data['oper_type'] = 'create';
//            $insert_data['data']['order_id'] = $syc['order_id'];
//            $insert_data['data']['order_prop'] = $syc['order_prop'];
//            $insert_data['data']['customer_id'] = $syc['customer_id'];
//            $insert_data['data']['consignee'] = $syc['consignee'];
//            $insert_data['data']['phone'] = $syc['phone'];
//            $insert_data['data']['reserve_num'] = $syc['reserve_num'];
//            $insert_data['data']['address'] = $syc['address'];
//            $insert_data['data']['zip_code'] = $syc['zip_code'];
//            $insert_data['data']['customs_clearance'] = $syc['customs_clearance'];
//            $insert_data['data']['id_no'] = $syc['ID_no'];
//            $insert_data['data']['id_img_front'] = $syc['ID_front'];
//            $insert_data['data']['id_img_reverse'] = $syc['ID_reverse'];
//            $insert_data['data']['remark'] = $syc['remark'];
//            $insert_data['data']['created_at'] = $syc['created_at'];
//            $insert_data['data']['currency'] = $syc['currency'];
//            $insert_data['data']['currency_rate'] = $syc['currency_rate'];
//            $insert_data['data']['payment_type'] = $syc['payment_type'];
//            $insert_data['data']['discount_type'] = $syc['discount_type'];
//            $insert_data['data']['goods_amount'] = $syc['goods_amount'];
//            $insert_data['data']['deliver_fee'] = $syc['deliver_fee'];
//            $insert_data['data']['order_amount'] = $syc['order_amount'];
//            $insert_data['data']['goods_amount_usd'] = $syc['goods_amount_usd'];
//            $insert_data['data']['deliver_fee_usd'] = $syc['deliver_fee_usd'];
//            $insert_data['data']['discount_amount_usd'] = $syc['discount_amount_usd'];
//            $insert_data['data']['order_amount_usd'] = $syc['order_amount_usd'];
//            $insert_data['data']['order_profit_usd'] = $syc['order_profit_usd'];
//            $insert_data['data']['txn_id'] = $syc['txn_id'];
//            $insert_data['data']['pay_time'] = $syc['pay_time'];
//            $insert_data['data']['shipper_id'] = $syc['shipper_id'];
//            $insert_data['data']['status'] = $syc['status'];
//
//            //获取商品信息
//            $goodsArr = $this->m_trade->get_order_goods_arr($syc['goods_list'],$cur_language_id);
//            foreach($goodsArr as $gn => $attrs){
//                $insert_data['data']['goods'][$gn]['supplier_id'] = $attrs['supplier_id'];
//                $insert_data['data']['goods'][$gn]['goods_name'] = $attrs['goods_name'];
//                $insert_data['data']['goods'][$gn]['goods_sn_main'] = $attrs['goods_sn_main'];
//                $insert_data['data']['goods'][$gn]['goods_sn'] = $attrs['goods_sn'];
//                $insert_data['data']['goods'][$gn]['quantity'] = $attrs['goods_number'];
//                $insert_data['data']['goods'][$gn]['price'] = $attrs['goods_price'] * 100;
//                $insert_data['data']['goods'][$gn]['supply_price'] = $attrs['purchase_price'];//供应商供货价
//            }
//
//            //插入到订单推送表
//            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);

            //保存到订单推送队列表,再定时推送到erp
            if(true){
                $this->load->model("o_erp");
                $where_trade_orders_queue = ['order_id',$insert_attr['order_id']];
                $this->o_erp->trade_order_create($where_trade_orders_queue,$cur_language_id);
            }

            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($insert_attr['goods_list'],$cur_language_id);
            $this->m_trade->insert_order_goods_info($insert_attr['order_id'],$goods_order);

            //修改商品库存
            $this->m_group->update_goods_number($goods_all, $insert_attr['order_id']);
        }

        //插入主订单
        if(count($split_list) > 1 )
        {
            $main_insert_attr['order_id'] = $component_id;
            $main_insert_attr['order_prop'] = '2';
            $main_insert_attr['attach_id'] = $component_id;
            $main_insert_attr['status'] = Order_enum::STATUS_COMPONENT;

            //查询子订单
//            $child_order = $this->db->select('*')
//                ->where('order_prop', '1')
//                ->where('attach_id', $component_id)
//                ->get('trade_orders')->result_array();
            $child_order = $this->tb_trade_orders->get_list("*",[
                'order_prop'=> '1',
                'attach_id'=>$component_id,
            ]);

            //创建子订单失败
            if(empty($child_order))
            {
                $ret_code['code'] = 1006;
                return $ret_code;
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
                $main_goods_list[] = $child['goods_list'];
                $main_insert_attr['goods_list'] = implode('$',$main_goods_list);
                $main_insert_attr['goods_amount'] += $child['goods_amount'];
                $main_insert_attr['goods_amount_usd'] += $child['goods_amount_usd'];
                $main_insert_attr['order_amount'] += $child['order_amount'];
                $main_insert_attr['order_amount_usd'] += $child['order_amount_usd'];
                $main_insert_attr['order_profit_usd'] += $child['order_profit_usd'];
                $main_insert_attr['discount_amount_usd'] += $child['discount_amount_usd'];
            }

//            $this->db->insert('trade_orders',$main_insert_attr);
            $this->tb_trade_orders->insert_one($main_insert_attr);
            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($main_insert_attr['goods_list'],$cur_language_id);
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

        //如果是升级订单
        if($order_type == 2)
        {
            // 防止 cookie 丢失数据，把券存入数据库
            if (!empty($coupons_list)) {
                $new_arr_coupons = $this->m_split_order->split_goods_list($coupons_list);
                $coupons_map = config_item('coupons_money');
                foreach ($new_arr_coupons as $k => $v) {
                    $coupons_attr = array(
                        'user_id' => $uid,
                        'order_id' => $ret_id,
                        'coupons_value' =>$coupons_map[$k],
                        'coupons_num' => $v
                    );
                    $this->db->insert('temp_save_coupons', $coupons_attr);
                }
            }

            // 升级订单需要插入 trade_orders_type 以区分非套餐订单
            $user_rank = $this->db->select('user_rank')->where('id',$uid)->from('users')->get()->row()->user_rank;

            $join_fee = $this->m_user->getJoinFeeAndMonthFee();
            $upgrade_amount = $join_fee[$level]['join_fee']  - $join_fee[$user_rank]['join_fee'];


            $this->db->insert('trade_orders_type', array(
                'order_id' => $ret_id,
                'type' => 1,
                'level' => $level,
                'amount' =>$upgrade_amount,
            ));
        }

        //如果是代品券换购,且不需要补差价,则直接扣取代品券,跳转到订单中心
        if($order_type == 3){
//            $order_info = $this->db->select('goods_amount_usd')->where('order_id',$ret_id)->from('trade_orders')->get()->row_array();
            $order_info =$this->tb_trade_orders->get_one("goods_amount_usd",['order_id'=>$ret_id]);
            if(empty($order_info)){
                $ret_code['code'] = 1006;
            }

            //获取用户的代品券总额
            $coupons_info = $this->m_coupons->get_coupons_list($uid);
            $coupons_total_money = $coupons_info['total_money'] * 100;

            //扣除代品券
            $this->load->model('m_suite_exchange_coupon');
            $this->m_suite_exchange_coupon->useCoupon($uid,$order_info['goods_amount_usd']/100);

            if($coupons_total_money >= $order_info['goods_amount_usd']){
                $ret_code['code'] = 1000;
            }

        }

        //修改商品库存
//        $this->m_group->update_goods_number($goods_all);

        //系统繁忙,请稍后重试
        if ($this->db->trans_status() === FALSE)
        {
            $ret_code['code'] = 1010;
        }
        else
        {
            $ret_code['order_id'] = $ret_id;
            $this->db->trans_commit();
        }

        return $ret_code;
    }

    /* 获取所选代品券金额 */
    public function get_coupons_value($coupons_list){
        $coupons_value = 0;
        if($coupons_list == '') {
            return 0;
        } else {
            $this->load->model('m_split_order');
            $new_arr_coupons = $this->m_split_order->split_goods_list($coupons_list);
            $coupons_map = config_item('coupons_money');
            foreach ($new_arr_coupons as $k => $v) {
                $coupons_value += $coupons_map[$k] * $v * 100;
            }
        }
        return $coupons_value;
    }


    /* 取消订单 */
    public function do_cancel_order($order_id){
        $goods_all = array();
        $status_map = array(1,2,3);
//        $order = $this->db->query("select customer_id,order_id,goods_amount_usd,order_amount_usd,status,goods_list,area,order_type from trade_orders where order_id = '{$order_id}'")->row_array();
        $order = $this->tb_trade_orders->get_one(
            "customer_id,order_id,goods_amount_usd,order_amount_usd,status,goods_list,area,order_type",
            ["order_id"=>$order_id]
        );
        //开始事务
        $this->db->trans_begin();

        //该订单不能取消
        if(!in_array($order['status'],$status_map)){
            return 1005;
        }
//        $ret = $this->db->where_in('order_id', $order_id)->update('trade_orders', array('status' => Order_enum::STATUS_CANCEL));
        $ret = $this->tb_trade_orders->update_batch(["order_id"=>$order_id],
            array('status' => Order_enum::STATUS_CANCEL));
        if ($ret == true) {
            //取消订单增加库存
            $this->load->model('m_split_order');
            $new_arr = $this->m_split_order->split_goods_list($order['goods_list']);
            foreach ($new_arr as $k => $v) {
                $goods = array();
                $goods['goods_sn'] = $k;
                $goods['quantity'] = $v;
                $goods_all[] = $goods;
            }
            $this->load->model('m_group');
            $this->m_group->add_goods_number($goods_all, $order_id);
        }

        //如果是代品券订单,退还代品券
        if($order['order_type'] == 3){
            $this->load->model('m_suite_exchange_coupon');
            $amount = $order['goods_amount_usd'] - $order['order_amount_usd'];
            $this->m_suite_exchange_coupon->add_voucher($order['customer_id'],$amount/100);
        }


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return 1006;
        }
        else
        {
            $this->db->trans_commit();
            return 0;
        }

    }

    //根据用户收货地址,判断属于哪个国家
    public function get_country_code_for_addr_lv2($address_info){

        $country_code = $address_info['country'];
        if($address_info['country'] == 156){
            //香港
            if($address_info['addr_lv2'] == 81){
                $country_code = 344;
            }
            //澳门和台湾
            if($address_info['addr_lv2'] == 82 || $address_info['addr_lv2'] == 71){
                $country_code = 000;
            }
        }

        return $country_code;
    }

    /**
     * redis 防重提交,10秒之内防止重复提交
     * Ckf
     * 2017/03/16 16:48
     */
    public function redis_anti_repeat($key){
        $key = 'o_trade:redis_anti_repeat:'.$key;
        $redis = $this->redis_get($key);
        if($redis){
            //值已存在，阻止提交
            return true;
        }else{
            $this->redis_set($key,'1',7200);
            return false;
        }
    }
    
    /***
     * 固定修复2017-5-1凌晨到中户12点的用户业绩
     * @param 用户id $uid
     */
    public function order_achievement_repair($uid)
    {      
//        $sql = "select order_id, customer_id, order_amount from trade_orders  where pay_time >= '2017-05-01 00:00:00' and pay_time < '2017-05-01 12:00:00' and customer_id=".$uid;
//        $query = $this->db->query($sql);
//        $query_value = $query->result_array();
        $query_value = $this->tb_trade_orders->get_list_auto([
           "select"=>"order_id, customer_id, order_amount",
            "where"=>[
                "pay_time >="=>'2017-05-01 00:00:00',
                "pay_time <"=> '2017-05-01 12:00:00',
                "customer_id" =>$uid,
            ]
        ]);
        if(!empty($query_value))
        {
            foreach($query_value as $sult)
            {
                $this->db->query("update users_store_sale_info_monthly set sale_amount=sale_amount-".$sult['order_amount']." where uid=".$uid." and `year_month`=201705");
                $monthly_exits_sql="select * from users_store_sale_info_monthly where uid =".$uid." and `year_month`=201704";
                $query_exits = $this->db->query($monthly_exits_sql);
                $query_result = $query_exits->row_array();
                if(!empty($query_result))
                {
                    $this->db->query("update users_store_sale_info_monthly set sale_amount=sale_amount+".$sult['order_amount']." where uid=".$uid." and `year_month`=201704");
                }
                else
                {
                    $add_sql = "insert into users_store_sale_info_monthly set uid =".$uid." ,`year_month`=201704,sale_amount = ".$sult['order_amount'];
                    $this->db->query($add_sql);
                }

//                $this->db->query("update trade_orders set score_year_month = '201704' where order_id='".$sult['order_id']."'");
                $this->tb_trade_orders->update_one(["order_id"=>$sult['order_id']],["score_year_month"=>'201704']);
            }
           
        }        
    }
    
}