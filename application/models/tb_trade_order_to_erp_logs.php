<?php

class tb_trade_order_to_erp_logs extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查同步流水中是否存在指定订单未推送的流水
     * @return: true 是; false 否
     */
    public function checkErpLogsById($orderId) {

        $where = array('order_id' => $orderId);
        $tmp = $this->db->select("id")->where($where)
            ->not_like("oper_data",'a:2:{s:9:"oper_type";s:6:"remark"',"after")
            ->get('trade_order_to_erp_oper_queue')->row_array();
        if ($tmp) {
            return true;
        } else {
            return false;
        }
    }
}
