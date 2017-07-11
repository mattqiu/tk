<?php
/**
 * @author ckf
 */
class tb_trade_orders_doba_order_info extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查询订货号是否是已推送的doba订单
     * @return boolean
     * @author ckf
     */
    public function findOne($orderId) {
        $this->db->from('trade_orders_doba_order_info');
        $one = $this->db->where('order_id',$orderId)->get()->row_array();
        return $one;
    }

}
