<?php
/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/4/27
 * Time: 14:32
 */

class tb_company_calc_monthly extends MY_Model
{
    protected $table = "company_calc_monthly";
    protected $table_name = "company_calc_monthly";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author brady
     * @desc   统计某一个月的 总的业绩 只针对中国地区
     * @date   2017/04/27
     */
    public function calc_month_total($year_month)
    {
        $start_time =  time();
        $total_goods_amount = 0;
        $total_order_profit_usd = 0;
        $month_fee = 0;


        $totals_level = $this->get_totals_level($year_month); //升级订单查询
        $totals_ord = $this->get_totals_ord($year_month); //普通订单查询
        $total_goods_amount_level = $totals_level->total_amount;

        $total_goods_amount_ord= $totals_ord->total_amount;
        $total_order_profit_usd_ord = $totals_ord->total_order_profit_usd;

        $operating_cost = 0;
        $cost_price = 0;

        $operating_cost += $total_goods_amount_level * 0.05; //升级订单的运营成本
        $cost_price  += $total_goods_amount_level * 0.2;//升级订单的成本价
        $operating_cost += $total_goods_amount_ord * 0.05; //普通订单的运营成本
        $cost_price  += $total_goods_amount_ord * 0.95 - $total_order_profit_usd_ord ;//普通订单的成本价
        $total_goods_amount = $total_goods_amount_ord + $total_goods_amount_level;
//        dump($totals_ord);
//        dump($totals_level);
//        echo "总的订单金额:".$total_goods_amount."\n";
//        echo "成本：".$cost_price."\n";
//        echo "运营成本：".$operating_cost."\n";
        //普通订单：成本价 = 商品价格 * 0.95 -  利润
        //升级订单，成本价 = 商品价格 * 0.2
        //运营成本（商品金额 * 5%）

        $month_fee = $this->get_month_fee($year_month);
        $bonus = $this->get_total_bonus($year_month);

        $data = [
            'country_id'=>1,
            "year_month"=>$year_month,
            'goods_amount'=>$total_goods_amount,
            'month_fee'=>abs($month_fee*100),
            'cost_price'=> tps_int_format($cost_price),
            'operating_cost'=>tps_int_format($operating_cost),
            'bonus'=>$bonus, //十二个奖项的总的金额
            'upate_time'=>date("Y-m-d H:i:s")
        ];

        $this->replace($data);
        //echo $this->db->last_query();
        $insert_id = $this->db->insert_id();
        if ($insert_id > 0) {
            $message = "[success] 统计成功.".$year_month;
            $this->m_log->createCronLog($message);
        } else {
            $message = "[faile] 统计失败.".$year_month;
            $this->m_log->createCronLog($message);
        }

        $end_time = time();
        $desc_time = $end_time - $start_time;
        var_dump("月份：".$year_month." 耗时:".$desc_time."s");
        return $insert_id;
    }


    /**
     * 批量获取用户的订单升级订单的金额和利润
     * @author brady
     */
    public function get_totals_level($year_month)
    {
        exit("FUNCTION EXIT,".__FILE__.",".__LINE__."<BR>");
//        $start_end = get_month_first_end($year_month);
//        $mallProfitTps = $this->db_slave->select('sum(a.goods_amount_usd) as total_amount')
//            ->from('trade_orders as a')
//            ->join("temp_uid as b",'a.customer_id = b.uid')
//            ->where("a.order_type !=",'4')
//            ->not_like("a.order_id","L","after")
//            ->not_like("a.order_id","S","after")
//            ->where('a.pay_time >=',$start_end['start'] )
//            ->where('a.pay_time <', $start_end['end'])
//            ->where_in('a.order_prop', array('0', '1'))
//            ->where_in('a.status', array('90','100','111','1','3', '4', '5', '6'))
//            ->get()
//            ->row_object();
//        return $mallProfitTps;

    }

    /**
     * 批量获取用户的订单普通订单的金额和利润
     * @author brady
     */
    public function get_totals_ord($year_month)
    {
        exit("function exit;".__FILE__.",".__LINE__."<BR>");
//        return false;
//        $start_end = get_month_first_end($year_month);
//
//        $mallProfitTps = $this->db_slave->select('sum(a.goods_amount_usd) as total_amount, sum(a.order_profit_usd) as total_order_profit_usd')
//            ->from('trade_orders as a')
//            ->join("temp_uid as b",'a.customer_id = b.uid')
//            ->where("a.order_type =",'4')
//            ->not_like("a.order_id","L","after")
//            ->not_like("a.order_id","S","after")
//            ->where('a.pay_time >=',$start_end['start'] )
//            ->where('a.pay_time <', $start_end['end'])
//            ->where_in('a.order_prop', array('0', '1'))
//            ->where_in('a.status', array('90','100','111','1','3', '4', '5', '6'))
//            ->get()
//            ->row_object();
//        return $mallProfitTps;

    }

    /**
     * @author brady
     * @desc 获取某个月的总的交的月费
     * @param $year_month 201704
     */
    public function get_month_fee($year_month)
    {
        $start_end = get_month_first_end($year_month);

        $month_fee = $this->db_slave->select('sum(a.cash) as total_fee')
            ->from('month_fee_change as a')
            ->join("temp_uid as b",'a.user_id = b.uid')
            ->where('a.create_time >=',$start_end['start'] )
            ->where('a.create_time <', $start_end['end'])
            ->get()
            ->row_object();
        return $month_fee ? $month_fee->total_fee : 0;
    }




//    public function get_total_bonus($year_month)
//    {
//
//        $start_end = get_month_first_end($year_month);
//        $arr = config_item('commission_type_for_order_repair');
//        $keys = array_keys($arr);
//        $cut_time = "201606";
//        $flag = 0;
//        if ($year_month > $cut_time) {
//
//            //使用分表查询
//            $table = "cash_account_log_".$year_month;
//            $total_bonus =  $this->db_slave->from($table." as a")
//                ->select("sum(a.amount) as total_bonus")
//                ->join("temp_uid as b",'a.uid = b.uid')
//                ->where_in("item_type",$keys)
//                ->get()
//                ->row_object();
//        } else {
//            //使用单表查询
//            $flag = 1;
//            $table = "commission_logs";
//            $total_bonus =  $this->db_slave->from($table." as a")
//                ->select("sum(a.amount) as total_bonus")
//                ->join("temp_uid as b",'a.uid = b.uid')
//                ->where_in("type",$keys)
//                ->where('a.create_time >=',$start_end['start'] )
//                ->where('a.create_time <', $start_end['end'])
//                ->get()
//                ->row_object();
//        }
//        if ($flag == 0) {
//            return $total_bonus ? $total_bonus->total_bonus: 0;
//        } else {
//            return $total_bonus ? 100 *( $total_bonus->total_bonus): 0;
//        }
//    }





}