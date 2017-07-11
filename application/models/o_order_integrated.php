<?php
/**
 * 多平台订单集成处理
 * @author terry
 */
class o_order_integrated extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 订单业绩年月的更新（多平台订单处理）
     */
    public function updateScore_year_monthIntegrated($order_id,$statYearMonth){

        $this->load->model('tb_trade_orders');
        $this->load->model('tb_mall_orders');
        $this->load->model('tb_one_direct_orders');
        $this->load->model('tb_walmart_orders');

        $this->tb_trade_orders->updateScore_year_month($order_id,$statYearMonth);//更新订单表中的业绩年月
        $this->tb_mall_orders->updateScore_year_month($order_id,$statYearMonth);//更新订单表中的业绩年月
        $this->tb_one_direct_orders->updateScore_year_month($order_id,$statYearMonth);//更新订单表中的业绩年月
        $this->tb_walmart_orders->updateScore_year_month($order_id,$statYearMonth);//更新订单表中的业绩年月
    }

}
