<?php
/**
 * @author Terry
 */
class tb_one_direct_orders extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 更新订单的业绩年月
     */
    public function updateScore_year_month($order_id,$score_year_month){

        $this->db->where('order_id', $order_id)->set('score_year_month', $score_year_month)->update('one_direct_orders');
    }

    /**
     * 更新订单的状态
     */
    public function updateOneDirectOrderStatus($order_id,$status){

        $this->db->where('order_id', $order_id)->set('status', $status)->update('one_direct_orders');
    }

    /**获取1direct订单的状态*/
    public function getOneDirectOrderStatus($order_id){

        $res = $this->db->from('one_direct_orders')->select('status')->where('order_id',$order_id)->get()->row_array();
        return $res?$res['status']:false;
    }

    /**
	 * 根据订单号获取订单信息。
     */
    public function get1DirctOrderInfo($order_id){

    	return $this->db->from('one_direct_orders')->select('shopkeeper_id,order_amount_usd * 100 goods_amount_usd,create_time pay_time,score_year_month')->where('order_id',$order_id)->get()->row_array();
    }

    /**
     * 根据订单ID获取信息
     * @author: derrick
     * @date: 2017年4月14日
     * @param: @param String $order_id 订单Id
     * @reurn: return_type
     */
    public function find_by_order_id($order_id, $field = '*') {
    	$res = $this->db->select($field)->where('order_id', $order_id)->get('one_direct_orders');
    	if ($res) {
    		return $res->row_array();
    	}
    	return array();
    }
}
