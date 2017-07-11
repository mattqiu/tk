<?php
/**
 * @author Terry
 */
class tb_mall_orders extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 我的沃好订单
     * @param $uid  用户ID
     * @param $time 下单时间
     * @return 订单商品详情
     */
    public function get_wohao_order($uid,$time){

        //订单数据
        $sql = "select order_id,customer_id,shopkeeper_id,order_amount_usd,date(create_time) AS create_time from mall_orders WHERE shopkeeper_id = {$uid} AND create_time LIKE '$time%' order by create_time DESC ";
        $res = $this->db->query($sql)->result_array();
        return $res;
    }
        /**
         * 获取订单
         * @param type $order_id
         */
        function getWhOrderInfo($order_id){
            $res = $this->db->select('*,order_pay_time as pay_time,order_amount_usd as goods_amount_usd')->from('mall_orders')->where('order_id',$order_id)->get();
            if ($res) {
            	return $res->row_array();
            }
            return array();
        }
	/**
	 * 更新订单的业绩年月
	 */
	public function updateScore_year_month($order_id,$score_year_month){

		$this->db->where('order_id', $order_id)->set('score_year_month', $score_year_month)->update('mall_orders');
	}
        /**
         * 更改订单的状态
         */
        public function updateWhOrderStatus($order_id,$status){
            $this->db->where('order_id', $order_id)->set('status', $status)->update('mall_orders');
        }
        /**
         * 获取订单是否是已完成的状态
         * @param type $order_id
         */
        function getWhOrderStatus($order_id){
            $res = $this->db->select('order_id')->from('mall_orders')->where('order_id',$order_id)->where('status',1)->get()->row_array();
            if($res){
                return 1;
            }
            return 0;
        }
}
