<?php
/**
 *　业务逻辑层-ERP数据推送
 * @date: 2017-06-12
 * @author: tico.wong
 */
class o_erp extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 添加创建订单的数据到队列
     * @param $where
     * @param $cur_language_id
     */
    public function trade_order_create($where,$cur_language_id)
    {
        //组装数组,添加到订单推送表
        $this->load->model("tb_trade_orders");
        $this->load->model("tb_trade_orders_goods");
        $syc_list = $this->tb_trade_orders->get_list("*",$where);

        foreach($syc_list as $syc){
            //组装数组,添加到订单推送表
            $insert_data = array();
            $insert_data['oper_type'] = 'create';
            $insert_data['data']['order_id'] = $syc['order_id'];
            $insert_data['data']['order_prop'] = $syc['order_prop'];
            $insert_data['data']['customer_id'] = $syc['customer_id'];
            $insert_data['data']['consignee'] = $syc['consignee'];
            $insert_data['data']['phone'] = $syc['phone'];
            $insert_data['data']['reserve_num'] = $syc['reserve_num'];
            $insert_data['data']['address'] = trim($syc['address']);
            $insert_data['data']['zip_code'] = $syc['zip_code'];
            $insert_data['data']['customs_clearance'] = $syc['customs_clearance'];
            $insert_data['data']['id_no'] = $syc['ID_no'];
            $insert_data['data']['id_img_front'] = $syc['ID_front'];
            $insert_data['data']['id_img_reverse'] = $syc['ID_reverse'];
            $insert_data['data']['remark'] = $syc['remark'];
            $insert_data['data']['created_at'] = $syc['created_at'];
            $insert_data['data']['currency'] = $syc['currency'];
            $insert_data['data']['currency_rate'] = $syc['currency_rate'];
            $insert_data['data']['payment_type'] = $syc['payment_type'];
            $insert_data['data']['discount_type'] = $syc['discount_type'];
            $insert_data['data']['goods_amount'] = $syc['goods_amount'];
            $insert_data['data']['deliver_fee'] = $syc['deliver_fee'];
            $insert_data['data']['order_amount'] = $syc['order_amount'];
            $insert_data['data']['goods_amount_usd'] = $syc['goods_amount_usd'];
            $insert_data['data']['deliver_fee_usd'] = $syc['deliver_fee_usd'];
            $insert_data['data']['discount_amount_usd'] = $syc['discount_amount_usd'];
            $insert_data['data']['order_amount_usd'] = $syc['order_amount_usd'];
            $insert_data['data']['order_profit_usd'] = $syc['order_profit_usd'];
            $insert_data['data']['txn_id'] = $syc['txn_id'];
            $insert_data['data']['pay_time'] = $syc['pay_time'];
            $insert_data['data']['shipper_id'] = $syc['shipper_id'];
            $insert_data['data']['status'] = $syc['status'];

            //若没有主单的goods_list，自行拼凑一个，因为新结构goods_list字段已经移除
            if(!isset($syc['goods_list']) || !$syc['goods_list'])
            {
                $tmp_order_goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number",
                    ['order_id'=>$syc['order_id']]);
                $child_order_goods_child = [];
                foreach($tmp_order_goods as $k=>$v)
                {
                    $child_order_goods_child[$k][$v['goods_sn']] = $v;
                }
                $syc['goods_list'] = $this->get_goods_list_str($child_order_goods_child);
            }

            //获取商品信息
            $goodsArr = $this->get_order_goods_arr($syc['goods_list'],$cur_language_id);
            foreach($goodsArr as $gn => $attrs){
                $insert_data['data']['goods'][$gn]['supplier_id'] = $attrs['supplier_id'];
                $insert_data['data']['goods'][$gn]['goods_name'] = $attrs['goods_name'];
                $insert_data['data']['goods'][$gn]['goods_sn_main'] = $attrs['goods_sn_main'];
                $insert_data['data']['goods'][$gn]['goods_sn'] = $attrs['goods_sn'];
                $insert_data['data']['goods'][$gn]['quantity'] = $attrs['goods_number'];
                $insert_data['data']['goods'][$gn]['price'] = $attrs['goods_price'] * 100;
                $insert_data['data']['goods'][$gn]['supply_price'] = $attrs['supply_price'];//供应商供货价
            }
            //插入到订单推送表
            $this->trade_order_to_erp_oper_queue($insert_data);
        }
    }

    /**
     * 添加到TPS订单同步ERP队列表
     * @param $insert_data
     * @return CI_DB_active_record|CI_DB_result
     */
    public function trade_order_to_erp_oper_queue($insert_data){
        //订单备注的 admin_id 换成邮箱
        $this->load->model('m_admin_user');
        if($insert_data['oper_type'] == 'remark'){
            $one = $this->m_admin_user->getInfo($insert_data['data']['recorder']);
            $insert_data['data']['recorder'] = isset($one['email'])?$one['email']:$insert_data['data']['recorder'];
        }
        $insert_data = array(
            'order_id' => $insert_data['data']['order_id'],
            'oper_data' => serialize($insert_data),
            'oper_time'=>date('Y-m-d H:i:s',time())
        );
        $res = $this->db->insert('trade_order_to_erp_oper_queue',$insert_data);
        return $res;
    }


    /**
     * 根据goods_list字符串获取订单产品信息
     * @param $goods_list
     * @param $cur_language_id
     * @return array
     */
    function get_order_goods_arr($goods_list,$cur_language_id){

        $goods_order = array();
        $goods_arr = explode('$',$goods_list);
        $day = date("Y-m-d H:i:s");
        foreach ($goods_arr as $v)
        {
            list($goods_sn, $quantity) = explode(":", $v);

            // 获取商品信息
            $this->load->model("tb_mall_goods");
            $goods_info = $this->tb_mall_goods->get_one(
                'goods_sn_main, price, color, size, customer,goods_currency',
                ['goods_sn'=>$goods_sn,'language_id'=>$cur_language_id]);

            if (empty($goods_info))
            {
                return FALSE;
            }

            // 获取商品主要信息
//            $goods_main_info = $this->db_slave
//                ->select('cate_id,market_price,is_doba_goods,goods_name,supplier_id,is_promote')
//                ->from('mall_goods_main')
//                ->where('goods_sn_main', $goods_info['goods_sn_main'])
//                ->where('language_id', $cur_language_id)
//                ->get()
//                ->row_array();
            $this->load->model("tb_mall_goods_main");
            $goods_main_info = $this->tb_mall_goods_main->get_one_auto([
                "select"=>'cate_id,market_price,is_doba_goods,goods_name,supplier_id,is_promote',
                "where"=>[
                    'goods_sn_main'=>$goods_info['goods_sn_main'],
                    'language_id'=>$cur_language_id
                ]
            ]);
            if (empty($goods_main_info))
            {
                return FALSE;
            }

            /*商品在促销期 */
            if ($goods_main_info['is_promote'] == 1){
//                $promote = $this->db_slave->select('promote_price')->where('goods_sn',$goods_sn)
//                    ->where('start_time <=',$day)->where('end_time >=',$day)
//                    ->limit(1)->get('mall_goods_promote')->row_array();
                $this->load->model("tb_mall_goods_promote");
                $promote = $this->tb_mall_goods_promote->get_one_auto([
                   "select"=>"promote_price",
                    "where"=>[
                        'goods_sn'=>$goods_sn,
                        'start_time <='=>$day,
                        'end_time >='=>$day
                    ]
                ]);
                if($promote){
                    $goods_info['price'] = $promote['promote_price']/100;
                }
            }

            $str = $goods_info['size'] ? lang('label_size').':'.$goods_info['size'] : '';
            $temp = $str ? ',':'';
            $str .= $goods_info['color'] ? $temp.lang('label_color').':'.$goods_info['color'] : '';
            $temp = $str ? ',':'';
            $str .= $goods_info['customer'] ? $temp.lang('label_other').':'.$goods_info['customer'] : '';

            $goods_order[$goods_sn]['goods_sn'] = $goods_sn;
            $goods_order[$goods_sn]['goods_name'] = $goods_main_info['goods_name'];
            $goods_order[$goods_sn]['goods_attr'] = $str;
            $goods_order[$goods_sn]['goods_number'] = $quantity;
            $goods_order[$goods_sn]['goods_price'] = $goods_info['price'];
            $goods_order[$goods_sn]['cate_id'] = $goods_main_info['cate_id'];
            $goods_order[$goods_sn]['goods_sn_main'] = $goods_info['goods_sn_main'];
            $goods_order[$goods_sn]['market_price'] = $goods_main_info['market_price'];
            $goods_order[$goods_sn]['is_doba_goods'] = $goods_main_info['is_doba_goods'];
            $goods_order[$goods_sn]['supplier_id'] = $goods_main_info['supplier_id'];
            $goods_order[$goods_sn]['supply_price'] = $goods_info['goods_currency'];//本币采购价
            //$goods_order[$goods_sn]['store_code'] = $goods_main_info['store_code'];
        }

        if(empty($goods_order))
        {
            echo json_encode(array('success'=>false,'msg'=>'Goods not exist!'));
            exit();
        }
        return $goods_order;
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

}