<?php
/**
 * @author ckf
 */
class tb_trade_order_doba_log extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查询订货号的状态
     * @return boolean
     * @author ckf
     */
    public function findOne($orderId) {
        $this->db->from('trade_order_doba_log');
        $one = $this->db->where('order_id',$orderId)->get()->row_array();
        return $one;
    }

}
