<?php

class m_split_order extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("tb_trade_orders");
    }

    /**
     * 拆分goods_list
     * @param $goods_list
     * @return array
     */
    public function split_goods_list($goods_list)
    {
        if (empty($goods_list))
        {
            log_message('error', "goods not null");
            exit;
        }

        // 拆分 goods_list
        $sku_list = array();
        $num_list = array();
        $goods = explode('$', $goods_list);
        $data = array();
        foreach ($goods as $v) {
            list($sn, $qty) = explode(':', $v);
//            $sku_list[] = $sn;
//            $num_list[] = $qty;
            //套餐和单品的商品sku一样时数量应该累计，而不是覆盖
            if (array_key_exists($sn, $data)) {
                    $data[$sn] = $data[$sn] + $qty;
                } else {
                    $data[$sn] = $qty;
            }
        }
//        return array_combine($sku_list, $num_list);
        return $data;
    }

    /**
     * @param $new_arr  拆分的goods_list
     * @return array	返回商品仓库列表
     */
    public function get_store_list($new_arr)
    {
        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }

        if (empty($new_arr)) {
            log_message('error', "goods not null");
            exit;
        }

        $store_id_list = array();
        foreach ($new_arr as $k => $v)
        {
            // 获取主SKU
//            $res = $this->db_slave
//                ->select('goods_sn_main')
//                ->where('goods_sn', $k)
//                ->where('language_id', $cur_language_id)
//                ->get('mall_goods')
//                ->row_array();
            $this->load->model("tb_mall_goods");
            $res = $this->tb_mall_goods->get_one('goods_sn_main',
                ['goods_sn'=>$k,'language_id'=>$cur_language_id]);

            $goods_info = $this->db_slave
                ->select('store_code')
                ->where('goods_sn_main', $res['goods_sn_main'])
                ->where('language_id', $cur_language_id)
                ->get('mall_goods_main')
                ->row_array();

            $store_id = $goods_info['store_code'];

            if (!in_array($store_id, $store_id_list))
            {
                $store_id_list[] = $store_id;
            }
        }

        return $store_id_list;
    }

    /***
     * 根据shipper id 拆分订单
     * @param $new_arr 商品sku+商品数量
     * @return 拆单的数据
     */
    public function get_split_list($new_arr,$location_id = 840){

        //获取当前语言id
        $cur_language_id = get_cookie('curLan_id', true);

        if (empty($cur_language_id)){
            $this->load->model('tb_language');
            $cur_language_id = $this->tb_language->get_language_by_location($location_id);
        }

        $shipper_id_list = array();

        foreach ($new_arr as $k => $v)
        {
            // 获取主SKU
//            $res = $this->db_slave->select('goods_sn_main')
//                ->where('goods_sn', $k)
//                ->where('language_id', $cur_language_id)
//                ->get('mall_goods')
//                ->row_array();
            $this->load->model("tb_mall_goods");
            $res = $this->tb_mall_goods->get_one('goods_sn_main',
                ['goods_sn'=>$k,'language_id'=>$cur_language_id]);

            if(empty($res)){
                continue;
            }

            $goods_info = $this->db_slave->select('supplier_id,shipper_id,store_code,doba_supplier_id')
                ->where('goods_sn_main', $res['goods_sn_main'])
                ->where('language_id', $cur_language_id)
                ->get('mall_goods_main')
                ->row_array();

            //商品的sku和数量
            $item = array(
                'goods_sn_main'=>$res['goods_sn_main'],
                'goods_sn' => $k,
                'quantity' => $v
            );


            $shipper_id = $goods_info['shipper_id'];

            //如果是doba商品,根据doba供应商(doba_supplier_id)拆单,加上doba字符串防止与其他供应商混淆
            if($shipper_id == 100){
                $shipper_id = 'doba'.$goods_info['doba_supplier_id'];
                $goods_info['shipper_id'] = $shipper_id;
            }

            if (!isset($shipper_id_list[$shipper_id]))
            {
                $shipper_id_list[$shipper_id] = array();
            }

            foreach($shipper_id_list as $k => $shipper_goods)
            {
                if($goods_info['shipper_id'] == $k)
                {
                    $shipper_id_list[$k][] = $item;
                }
            }
        }
        return $shipper_id_list;
    }


    /**
     * 生成订单号
     */
    public function create_component_id($str)
    {
        //时间戳
        $timestamp = date('YmdHis', time());

        $rand = sprintf("%04s", mt_rand(0, 9999));

        $component_id = $str.$timestamp.$rand;

        return $component_id;
    }

    /**
     * 生成订单号,换货订单的订单号
     */
    public function create_component_id_ex($str)
    {
        //时间戳
        $timestamp = date('YmdHis', time());

        $rand = sprintf("%03s", mt_rand(0, 999));

        $component_id = $str.$timestamp.$rand;

        return $component_id;
    }


    /***
     * @param $order	订单信息
     * @param $new_arr	拆分的goods_list
     * @param $store_list	仓库列表
     */
    public function do_split_order($order,$store_list,$new_arr){

        //当前语言id
        $language_id = (int)$this->session->userdata('language_id');

        //生成主订单号
        $component_id = $order['order_id'];

        $num = 0;

        //遍历仓库
        foreach ($store_list as $k => $v)
        {

            //初始化数据
            $goods_list_arr = array();
            $goods_amount = 0;
            $purchase_price = 0;
            $num++;

            foreach ($new_arr as $k2 => $v2) {
                $res_sn = $this->db_slave->select('goods_sn_main')->from('mall_goods')->where('goods_sn', $k2)->where('language_id', $language_id)->get()->row_array();
                $res = $this->db_slave->select('shop_price,store_code,purchase_price')->from('mall_goods_main')->where('goods_sn_main', $res_sn['goods_sn_main'])->where('language_id', $language_id)->get()->row_array();

                //如果仓库相同
                if ($res['store_code'] == $v) {
                    $goods_list_arr[] = $k2 . ':' . $v2;
                    $goods_amount += $res['shop_price'] * $v2;
                    $purchase_price += $res['purchase_price'] * $v2;
                }
            }

            $order_type = $this->get_order_type($component_id);
            $discount_amount_usd = $num == 1 ? $order['discount_amount_usd'] : 0;

            //订单性质
            $order['order_prop'] = '1';

            //仓库code
            $order['store_code'] = $v;

            //关联id
            $order['attach_id'] = $component_id;

            //代品券金额
            $order['discount_amount_usd'] = $discount_amount_usd;

            //新的order_id
            $order['order_id'] = $this->m_split_order->create_component_id('C');
            $order_id_temp = $order['order_id'];
//            $res = $this->db->query("select order_id from trade_orders WHERE order_id='$order_id_temp'")->row_array();
            $res = $this->tb_trade_orders->get_one("order_id",["order_id"=>$order_id_temp]);
            if(!empty($res)){
                $order['order_id'] = $this->m_split_order->create_component_id('C');
            }

            //商品列表
            $order['goods_list'] = implode('$', $goods_list_arr);

            //状态待发货
            $order['status'] = Order_enum::STATUS_SHIPPING;

            //商品金额
            $order['goods_amount'] = $goods_amount * 100;
            $order['goods_amount_usd'] = $goods_amount * 100;

            //创建、更新时间
            $order['created_at'] = date('Y-m-d H:i:s', time());
            $order['updated_at'] = date('Y-m-d H:i:s', time());

            if ($order_type == 1) {
                //用户选购,订单金额为0,利润为0
                $order['order_amount'] = 0;
                $order['order_amount_usd'] = 0;
                $order['order_profit_usd'] = 0;
            }
            if ($order_type == 2) {
                //代品券换购,订单金额为0,利润为0
                $order['order_amount'] = 0;
                $order['order_amount_usd'] = 0;
                $order['order_profit_usd'] = 0;
            }
            if ($order_type == 3) {
                //升级订单(订单金额=商品金额+代品券金额,利润=订单金额*0.8)
                $order['order_amount'] = ($goods_amount * 100 + $discount_amount_usd);
                $order['order_amount_usd'] = ($goods_amount * 100 + $discount_amount_usd);
                $order['order_profit_usd'] = $order['order_amount_usd'] * 0.8 * 100;
            }
            if ($order_type == 4) {
                //代品券换购(支付差价,订单金额 = 商品金额-折扣金额,利润=订单金额*0.8)
                $order['order_amount'] = ($goods_amount * 100 - $discount_amount_usd);
                $order['order_amount_usd'] = ($goods_amount * 100 - $discount_amount_usd);
                $order['order_profit_usd'] = $order['order_amount_usd'] * 0.8 * 100;
            }
            if ($order_type == 5) {
                //普通订单(订单金额 = 商品金额,利润=订单金额*0.9-成本价)
                $order['order_amount'] = $goods_amount * 100;
                $order['order_amount_usd'] = $goods_amount * 100;
                $order['order_profit_usd'] = ($goods_amount * 0.9 - $purchase_price) * 100;
            }
//            $this->db->insert('trade_orders', $order);
            $this->tb_trade_orders->insert_one($order);
        }

        //更新主订单的status
//        $this->db->query("UPDATE trade_orders SET status = '100',order_prop = '2' WHERE order_id = '$component_id'");
        $this->tb_trade_orders->update_one(["order_id"=>$component_id],["status" => '100',"order_prop" => '2']);
    }


    /**
     * @param $order_id	订单号
     * @return order_type
     */
    public function get_order_type($order_id){
        //查找订单是否存在
//        $res = $this->db_slave->query("select payment_type,discount_amount_usd,order_prop,attach_id from trade_orders where order_id='$order_id'")->row_array();
        $res = $this->tb_trade_orders->get_one("payment_type,discount_amount_usd,order_prop,attach_id",
            ["order_id"=>$order_id]);
        $order_type = array(
            '1' => '1',			//选购订单
            '2' => '2',			//代品券订单
            '3' => '3',			//升级订单
            '4' => '4',			//补差价的代品券订单
            '5' => '5',			//普通订单
        );

        if(empty($res))
        {
            return false;
        }
        if($res['payment_type'] == 1)
        {
            return $order_type['1'];
        }
        if($res['payment_type'] == 2)
        {
            return $order_type['2'];
        }

        //子订单，以该订单的主订单去查询表 trade_orders_type ，存在数据则是升级订单
        if ($res['order_prop'] == 1) {
            $order_id = $res['attach_id'];
        }
        $upgrade_res=$this->db_slave->query("select * from trade_orders_type where order_id = '$order_id' ")->result_array();
        if(!empty($upgrade_res))
        {
            return $order_type['3'];
        }
        else
        {
            if($res['discount_amount_usd'] !=0)
            {
                return $order_type['4'];
            }
            else
            {
                return $order_type['5'];
            }
        }
    }

    /**
     * 修复订单类型
     * $order_id 订单id
     */
    public function get_order_type_2(){
        //查找订单类型为0的，每次5000条
//        $res = $this->db_slave->query("select payment_type,discount_type,order_prop,order_id,attach_id,order_type from trade_orders where order_type='0' limit 0,5000")->result_array();
        $res = $this->tb_trade_orders->get_list_auto([
            "select"=>"payment_type,discount_type,order_prop,order_id,attach_id,order_type",
            "where"=>["order_type"=>'0'],
            "page_size"=>5000
        ]);
//        $res = array(
//            0 => array(
//                'order_id' => 'P201603301341477731',
//                'attach_id' => 'P201603301341477731',
//                'discount_type' => '0',
//                'payment_type' => '0',
//                'order_prop' => '2',
//            ),
//            1 => array(
//                'order_id' => 'C201511061325248605',
//                'attach_id' => 'P201511061325242651',
//                'discount_type' => '2',
//                'payment_type' => '2',
//                'order_prop' => '1',
//            )
//        );
        foreach ($res as $k => $v) {
            if (empty($v)) {
                continue;
            }
            if($v['order_type'] != 0){
                continue;
            }

            $order_id = $v['order_id'];
            $payment_type = $v['payment_type'];
            $discount_type = $v['discount_type'];
            $order_prop = $v['order_prop'];

            //判断是不是主订单 order_prop= 2，P 开头
            if ($order_prop == 2) {
                $order_type = 0;
                //查找子订单，状态以子订单为准
//                $list = $this->db_slave->query("select * from trade_orders where order_id != '$order_id' and attach_id = '$order_id'")->result_array();
                $list = $this->tb_trade_orders->get_list_auto([
                   "where"=>[
                       "order_id !="=>$order_id,
                       "attach_id"=>$order_id
                   ]
                ]);
                if(!empty($list)){
                    foreach ($list as $k1 => $v1) {
                        $payment_type = $v1['payment_type'];
                        $discount_type = $v1['discount_type'];
                        if ($v1['order_type'] != 0) {
                            $order_type = $v1['order_type'];
                            break;
                        }
                    }
                }
                //子订单有order_type，而主订单没有，修改同一订单下的其他状态
                 if($order_type != 0){
                     //如果子订单有order_type，则覆盖主订单的
//                     $this->db->from('trade_orders');
//                     $this->db->where_in('attach_id',$order_id)->update('trade_orders', array('order_type' => "$order_type"));
                     $this->tb_trade_orders->update_batch(["attach_id"=>$order_id],['order_type' =>$order_type]);
                     continue;
                }
            }

            //判断是不是子订单 C
            if ($order_prop == 1) {
                //查找主订单,判断升级订单，以主订单ID查询表 trade_orders_type 有无记录为准,有记录则是升级订单 order_type = 2
//                $this->db_slave->from('trade_orders');
//                $one = $this->db_slave->where('order_id', $v['attach_id'])->get()->row_array();
                $one = $this->tb_trade_orders->get_one("order_id",["order_id"=>$v['attach_id']]);
                if(empty($one)){
                    continue;
                }
                $order_id = $one['order_id'];
            }

            //选购订单
            if ($payment_type == 1) {
//                $this->db->from('trade_orders');
//                $this->db->where_in('attach_id',$order_id)->update('trade_orders', array('order_type' => '1'));
                //echo $this->db->last_query();
                $this->tb_trade_orders->update_batch(['attach_id'=>$order_id],array('order_type' => '1'));
                continue;
            }
            //代品券订单
            if ($discount_type == 2) {
//                $this->db->from('trade_orders');
//                $this->db->where_in('attach_id',$order_id)->update('trade_orders', array('order_type' => '3'));
                $this->tb_trade_orders->update_batch(['attach_id'=>$order_id],array('order_type' => '3'));
                continue;
            }
            //升级订单 只能是主订单的ID来判断
            $upgrade_res = $this->db_slave->query("select * from trade_orders_type where order_id = '$order_id'")->result_array();
            if (!empty($upgrade_res)) {
//                $this->db->from('trade_orders');
//                $this->db->where_in('attach_id',$order_id)->update('trade_orders', array('order_type' => '2'));
                $this->tb_trade_orders->update_batch(['attach_id'=>$order_id],array('order_type' => '2'));
                continue;
            }
            //剩下的都是普通订单
//            $this->db->from('trade_orders');
//            $this->db->where_in('attach_id',$order_id)->update('trade_orders', array('order_type' => '4'));
            $this->tb_trade_orders->update_batch(['attach_id'=>$order_id],array('order_type' => '4'));

        }
    }

    /**
     * 修正商品库存异常
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function number_exception1(){
        $arr = $this->db_slave->query("select distinct goods_sn from mall_goods WHERE goods_sn not like '%dbus%'")->result_array();
//        $arr = array(
//            0 => array(
//                'goods_sn' => '95625646-1',
//            ),
//            1 => array(
//                'goods_sn' => '46952052-1'
//            )
//          );

        $num = null;
        $goods1 = array();
        $goods1['goods_sn'] = null;
        $goods1['goods_name'] = null;
        $goods1['language_id'] = 2;
        $data = array();
        $d = 0;

        foreach($arr as $k=>$v){
            $sku = $v['goods_sn'];
            // 95625646-1 46952052-1
            $query = $this->db_slave->query("SELECT `g`.`goods_sn`, `g`.`goods_sn_main`, `g`.`language_id`, `g`.`goods_number`, `m`.`goods_name`,`m`.`shipper_id`,`m`.`is_on_sale`,`g`.`product_id` FROM (`mall_goods` g) LEFT JOIN `mall_goods_main` m ON `g`.`goods_sn_main`=`m`.`goods_sn_main` WHERE `goods_sn` = '$sku' AND `g`.`language_id` = m.language_id");
            $list=$query->result_array();
            $this->load->model("tb_mall_goods");
            $this->tb_mall_goods->replace_mall_goods_goods_number($list);
            $num = null;
            if(!empty($list)){
                $num = $list[0]['goods_number'];
            }
            $i = 0;
            $idarr = array();
            foreach($list as $kk=>$vv){
                //语种库存不一致的商品
                if($num != $vv['goods_number']){
                    $m = 0;
                    foreach($list as $k1=>$v1){
                        if($v1['is_on_sale'] == 1){
                            $m++;
                        }
                        $idarr[] = $v1['product_id'];
                    }
                    if($m == 1){
                        //只有一种上架，则以上架语种的库存为准，修改其他语种库存
                        foreach($list as $k2=>$v2){
                            if($v2['is_on_sale'] == 1) {
                                $num_z = $v2['goods_number'];
//                                print_r($list);exit;
                                for($j=0;$j<count($idarr);$j++){
//                                    echo $v2['goods_sn'];
//                                    $this->db->where('product_id', $idarr[$j])->update('mall_goods', array('goods_number' => "$num_z"));
                                    $this->load->model("tb_mall_goods");
//                                    $this->tb_mall_goods->update_one(['product_id'=>$idarr[$j]],['goods_number'=>$num_z]);
                                    //更新redis里的独立库存
                                    $this->tb_mall_goods->mall_goods_redis_log($idarr[$j],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                                    $this->tb_mall_goods->update_goods_number_in_redis($idarr[$j],$num_z);
//                                     echo 'product_id： '.$idarr[$j].'; sku：'.$v2['goods_sn'].' 库存已修复<br/>';
                                }
//                                break 3;
                            }

                        }

                    }
                    if($m == 0){
                        //都没有语种上架的，则选一种不为零的库存替换其他语种
                        foreach($list as $k3=>$v3){
                            if($v3['goods_number'] != 0){
                                $num_z = $v3['goods_number'];
                                for($j=0;$j<count($idarr);$j++){
//                                    echo $v3['goods_sn'];
//                                    $this->db->where('product_id', $idarr[$j])->update('mall_goods', array('goods_number' => "$num_z"));
                                    $this->load->model("tb_mall_goods");
//                                    $this->tb_mall_goods->update_one(['product_id'=>$idarr[$j]],['goods_number' => $num_z]);
                                    //更新独立库存
                                    $this->tb_mall_goods->mall_goods_redis_log($idarr[$j],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                                    $this->tb_mall_goods->update_goods_number_in_redis($idarr[$j],$num_z);
//                                     echo 'product_id： '.$idarr[$j].'; sku：'.$v2['goods_sn'].' 库存已修复<br/>';
                                }
                                break;
                            }

                        }
                    }
                    break;
                }

            }

        }
    }


}
