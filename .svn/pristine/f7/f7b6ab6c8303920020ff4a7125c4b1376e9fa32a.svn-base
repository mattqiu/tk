<?php
/**
 * @author Terry
 */
class tb_trade_order extends MY_Model {

    protected $table_name = "trade_orders";
    function __construct() {
        parent::__construct();
        $this->load->model('m_trade');
    }
    
    /**
     * 查询订货号是否是有效的
     * @return boolean
     * @author Terry
     */
    public function findOne($orderId) {
//        $this->db->from('trade_orders');
//        $status = $this->db->select('status')->where('order_id',$orderId)->get()->row_array();
        $status = $this->get_one("status",["order_id"=>$orderId]);
        return $status;
    }


    /**
     * 我的店铺订单
     * @param $uid  用户ID
     * @param $time 下单时间
     * @return 订单商品详情
     */
    public function get_store_order($uid,$time){
        $data = array();

        //订单数据
        $select = "order_id,customer_id,shopkeeper_id,status,order_amount_usd,deliver_fee_usd,created_at,goods_amount_usd,order_type,discount_amount_usd,consignee,phone,zip_code,address,remark";
//        $sql = "select $select from trade_orders WHERE shopkeeper_id = {$uid} AND order_prop <> '2' AND created_at LIKE '$time%' ORDER BY created_at DESC ";
//        $res = $this->db->query($sql)->result_array();
        $res = $this->get_list_auto([
            "select"=>$select,
            "where"=>[
                "shopkeeper_id" => $uid,
                "order_prop <>"=> '2',
                "created_at LIKE "=>[$time=>"after"]
            ],
            "order_by"=>["created_at"=>"desc"]
        ]);
        $this->load->model("tb_mall_goods_main");
        $this->load->model("tb_trade_orders_goods");
        //查询订单商品数据
        foreach($res as $item)
        {
            //订单数据
            $item['total_number'] = 0;
            $item['goods_info'] = array();
//            $goods_info = $this->db->select('*')->where('order_id',$item['order_id'])->get('trade_orders_goods')->result_array();
            $goods_info = $this->tb_trade_orders_goods->get_list("*",['order_id'=>$item['order_id']]);
            foreach($goods_info as $goods)
            {
                //查询产地
//                $arr = $this->db->select("country_flag,goods_img")->where('goods_sn_main',$goods['goods_sn_main'])->get('mall_goods_main')->row_array();
                $arr = $this->tb_mall_goods_main->get_one("country_flag,goods_img",
                    ['goods_sn_main'=>$goods['goods_sn_main']]);
                if(empty($arr)){
                    continue;
                }

                $goods['original'] = $arr['country_flag'];
                $goods['goods_img'] = $arr['goods_img'];
                $item['total_number'] += $goods['goods_number'];
                $item['goods_info'][] = $goods;

            }
            $data[] = $item;
        }

        return $data;
    }

    /**
     * 我的自我消费订单
     * @param $uid  用户ID
     * @param $time 下单时间
     * @return 订单商品详情
     */
    public function get_buy_order($uid,$time){
        $data = array();

        //订单数据
        $select = "order_id,customer_id,shopkeeper_id,status,order_amount_usd,deliver_fee_usd,created_at,goods_amount_usd,order_type,discount_amount_usd,consignee,phone,zip_code,address,remark,discount_type";
//        $sql = "select $select from trade_orders WHERE customer_id = {$uid} AND shopkeeper_id <> {$uid} AND order_prop <> '2' AND created_at LIKE '$time%' ORDER BY created_at DESC";
//        $res = $this->db->query($sql)->result_array();
        $res = $this->get_list_auto([
            "select"=>$select,
            "where"=>[
                "customer_id" => $uid,
                "shopkeeper_id <>"=> $uid,
                "order_prop <>"=> '2',
                "created_at LIKE "=>[$time=>"after"]
            ],
            "order_by"=>["created_at"=>"desc"]
        ]);
        //查询订单商品数据
        foreach($res as $item)
        {
            //订单数据
            $item['total_number'] = 0;
            $item['goods_info'] = array();
//            $goods_info = $this->db->select('*')->where('order_id',$item['order_id'])->get('trade_orders_goods')->result_array();
            $goods_info = $this->tb_trade_orders_goods->get_list("*",['order_id'=>$item['order_id']]);
            foreach($goods_info as $goods)
            {
                //查询产地
//                $arr = $this->db->select("country_flag,goods_img")->where('goods_sn_main',$goods['goods_sn_main'])->get('mall_goods_main')->row_array();
                $arr = $this->tb_mall_goods_main->get_one("country_flag,goods_img",
                    ['goods_sn_main'=>$goods['goods_sn_main']]);
                if(empty($arr)){
                    continue;
                }

                $goods['original'] = $arr['country_flag'];
                $goods['goods_img'] = $arr['goods_img'];
                $item['total_number'] += $goods['goods_number'];
                $item['goods_info'][] = $goods;

            }
            $data[] = $item;
        }

        return $data;
    }
}
