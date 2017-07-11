<?php
/**
 * 利润类。
 */
class m_profit extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 获取公司昨天的全球销售利润（商城销售+店铺产品套装销售）
     * @author Terry
     */
    public function getCompanyProfitYesterday(){

        $timePeriod = $this->__getLastDayPeriod();

        $mallProfit = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('mall_orders as a')->where('a.create_time >=',$timePeriod['start'])->where('a.create_time <',$timePeriod['end'])->get()->row_object()->totalProfit;
        $mallProfit = $mallProfit?$mallProfit:0;

        $mallProfitOnederect = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('one_direct_orders as a')->where('a.create_time >=',$timePeriod['start'])->where('a.create_time <',$timePeriod['end'])->get()->row_object()->totalProfit;
        $mallProfitOnederect = $mallProfitOnederect?$mallProfitOnederect:0;

//        $mallProfitTps = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('trade_orders as a')->where('a.pay_time >=',$timePeriod['start'])->where('a.pay_time <',$timePeriod['end'])->where_in('order_prop',array('0','2'))->where_in('status',array('3','4','5','6'))->get()->row_object()->totalProfit;
//        $mallProfitTps = $mallProfitTps?$mallProfitTps/100:0;
        $this->load->model("tb_trade_orders");
        $mallProfitTps = $this->tb_trade_orders->get_sum_auto([
            "column"=>"order_profit_usd",
            "where"=>[
                'pay_time >='=>$timePeriod['start'],
                'pay_time <'=>$timePeriod['end'],
                'order_prop'=>array('0','2'),
                'status'=>array('3','4','5','6')
            ]
        ]);
        $mallProfitTps = isset($mallProfitTps['order_profit_usd'])?$mallProfitTps['order_profit_usd']/100:0;

        $tpsProductMoney = $this->db->query("select sum(a.join_fee) as totalMoney from user_upgrade_order a where a.pay_time>='".$timePeriod['start']."' and a.pay_time<'".$timePeriod['end']."' and a.status=2")->row_object()->totalMoney;
        $tpsProductMoney = $tpsProductMoney?$tpsProductMoney:0;

		return tps_money_format($mallProfit+$mallProfitOnederect+$mallProfitTps+$tpsProductMoney*0.8);
    }
    
    /**
     * 获取公司上个月的全球销售利润（商城销售+店铺产品套装销售）
     * @author Terry
     */
    public function getCompanyProfitLastMonth(){

        $timePeriod = $this->__getLastMonthTimePeriod();

        $mallProfit = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('mall_orders as a')->where('a.create_time >=',$timePeriod['start'])->where('a.create_time <',$timePeriod['end'])->get()->row_object()->totalProfit;
        $mallProfit = $mallProfit?$mallProfit:0;

        $mallProfitOnederect = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('one_direct_orders as a')->where('a.create_time >=',$timePeriod['start'])->where('a.create_time <',$timePeriod['end'])->get()->row_object()->totalProfit;
        $mallProfitOnederect = $mallProfitOnederect?$mallProfitOnederect:0;

//        $mallProfitTps = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('trade_orders as a')->where('a.pay_time >=',$timePeriod['start'])->where('a.pay_time <',$timePeriod['end'])->where_in('order_prop',array('0','2'))->where_in('status',array('3','4','5','6'))->get()->row_object()->totalProfit;
//        $mallProfitTps = $mallProfitTps?$mallProfitTps/100:0;

        $this->load->model("tb_trade_orders");
        $mallProfitTps = $this->tb_trade_orders->get_sum_auto([
            "column"=>"order_profit_usd",
            "where"=>[
                'pay_time >='=>$timePeriod['start'],
                'pay_time <'=>$timePeriod['end'],
                'order_prop'=>array('0','2'),
                'status'=>array('3','4','5','6')
            ]
        ]);
        $mallProfitTps = isset($mallProfitTps['order_profit_usd'])?$mallProfitTps['order_profit_usd']/100:0;

        $tpsProductMoney = $this->db->query("select sum(a.join_fee) as totalMoney from user_upgrade_order a where a.pay_time>='".$timePeriod['start']."' and a.pay_time<'".$timePeriod['end']."' and a.status=2")->row_object()->totalMoney;
        $tpsProductMoney = $tpsProductMoney?$tpsProductMoney:0;

        return $mallProfit+$mallProfitOnederect+$mallProfitTps+$tpsProductMoney*0.8;
    }
    
    /**
     * 获取上个月的时间区间
     * @return type
     * @author Terry Lu
     */
    public function __getLastMonthTimePeriod(){
        $startTimestamp = strtotime(date('Y-m',strtotime('-1 month', time())));
        $endTimestamp = strtotime(date('Y-m'));
        return array(
            'start'=>date('Y-m-d H:i:s',$startTimestamp),
            'startTimestamp'=>$startTimestamp,
            'end'=>date('Y-m-d H:i:s',$endTimestamp),
            'endTimestamp'=>$endTimestamp,
        );
    }
    
    /**
     * 获取昨天的时间区间
     * @return type
     * @author Terry Lu
     */
    public function __getLastDayPeriod(){
        $start = date('Y-m-d',strtotime('-1 day', time()));
        $startTimestamp = strtotime($start);
        $endTimestamp = time()+24*3600;
        $end = date('Y-m-d',$endTimestamp);
        return array(
            'start'=>$start,
            'startTimestamp'=>$startTimestamp,
            'end'=>$end,
            'endTimestamp'=>$endTimestamp,
        );
    }


}
