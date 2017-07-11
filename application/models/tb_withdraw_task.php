<?php
/**
 * @author Terry
 */
class tb_withdraw_task extends MY_Model {

    protected $table = "withdraw_task";
    function __construct() {
        parent::__construct();
        $this->load->model("tb_order_repair_log");
    }
    
    /**
     * 新增一条抽回记录
     * @param $uid 用户id
     * @param $comm_type 相应奖金类型
     * @param $comm_year_month 相应奖金年月
     * @param $sale_amount_lack 所欠业绩销售额
     * @return boolean
     */
    public function addOne($uid,$comm_type,$comm_year_month,$order_year_month,$sale_amount_lack,$admin_id =0){

        $this->load->model('tb_commission_logs');
        if ($comm_type == 26) {
        	$comm_amount = $this->getAmountByUidCommTypeYearMonth($uid,$comm_type,$comm_year_month);

        	$comm_year_month = date("Ym",strtotime($comm_year_month));
        } else {
        	$comm_amount = $this->tb_commission_logs->getAmountByUidCommTypeYearMonth($uid,$comm_type,$comm_year_month);
        }
        $res = $this->db->replace('withdraw_task',array(
            'uid'=>$uid,
            'comm_type'=>$comm_type,
            'comm_year_month'=>$comm_year_month,
            'comm_amount'=>$comm_amount,
            'order_year_month'=>$order_year_month,
            'sale_amount_lack'=>$sale_amount_lack,
        ));

        $log = [
            'uid'=>$uid,
            'admin_id'=>$admin_id,
            'item_type'=>$comm_type,
            'amount'=>$sale_amount_lack,
            'order_year_month'=>$order_year_month,
            'create_time'=>date("Y-m-d H:i:s")
        ];
        $this->tb_order_repair_log->add($log);
        return true;
    }
    
    /**
     * @author brady 
     * @desc 获取新会员奖 从当前月到次月的奖金 
     * @param string $uid
     * @param int $comm_type
     * @param string $comm_year_month
     * @return number
     */
    public function getAmountByUidCommTypeYearMonth($uid,$comm_type,$comm_year_month)
    {
    	$this->load->model("tb_cash_account_log_x");
    	$comm_year_month_new = date("Ym",strtotime($comm_year_month));
    	$next_month_new = date("Ym",strtotime("+1 month",strtotime($comm_year_month)));  

//        $table1 = get_cash_account_log_table($uid,$comm_year_month_new);
//        $table2 = get_cash_account_log_table($uid,$next_month_new);
        $amount1 = $this->tb_cash_account_log_x->get_sum_by_type_uid($uid,$comm_type,$comm_year_month_new);

        $amount2 = $this->tb_cash_account_log_x->get_sum_by_type_uid($uid,$comm_type,$next_month_new);
//    	$res1 = $this->db->from($table1)->select_sum('amount')->where(['uid'=>$uid,'item_type'=>$comm_type])->get()->row_array();
//    	$amount1 =  $res1?$res1['amount']:0;
//    	$res2 = $this->db->from($table2)->select_sum('amount')->where(['uid'=>$uid,'item_type'=>$comm_type])->get()->row_array();
//    	$amount2 =  $res2?$res2['amount']:0;

    	return $amount1 + $amount2;
    	
    }

    /**
     * 删除记录
     */
    public function deleteWithdrawTask($uid,$comm_type,$comm_year_month){
        $this->db->where('uid',$uid)->where('comm_type',$comm_type)->where('comm_year_month',$comm_year_month)->delete('withdraw_task');
    }

    /**
     * 获取佣金补单列表
     */
    public function getCommOrderRepariList($filter) {

        $this->load->model('o_sql_filter');

        $this->db->from('withdraw_task');
        $this->o_sql_filter->addSqlFilter($filter);
        return $this->db->order_by('order_year_month','asc')->order_by('sale_amount_lack','asc')->get()->result_array();
    }

    /**
     * 获取列表总数
     */
    public function getCommOrderRepariNum($filter) {

        $this->load->model('o_sql_filter');

        $this->db->from('withdraw_task');
        if(isset($filter['page'])){
            unset($filter['page']);
        }
        $this->o_sql_filter->addSqlFilter($filter);
        return $this->db->count_all_results();
    }

    public function getCommOrderRepariNumByUid($uid){
        //出1秒的慢日志
        $uid = (int)$uid;
        return $this->db->select('count(*) totalNum')->from('withdraw_task')->where('uid',$uid)->get()->row_object()->totalNum;
    }

    /*检查是否有补单的*/
    public function checkOrderRepairing($uid){
        $uid = (int)$uid;
        $res = $this->db->from('withdraw_task')->select('order_year_month')->where('uid',$uid)->where('status',1)->get();
        if ($res) {
        	$res = $res->row_array();
	        return $res?$res['order_year_month']:'';
        }
        return array();
    }

    /**
     * 标记补单状态
     */
    public function markOrderRepair($uid,$order_year_month){

        $this->db->where('uid', $uid)->where('status',1)->set('status', 0)->update('withdraw_task');
        $this->db->where('uid', $uid)->where('order_year_month',$order_year_month)->set('status', '1')->update('withdraw_task');
        $error_code = 0;
        $log = [
            'uid'=>$uid,
            'admin_id'=>0,
            'item_type'=>0,
            'amount'=>0,
            'order_year_month'=>$order_year_month,
            'create_time'=>date("Y-m-d H:i:s")
        ];
        $this->tb_order_repair_log->add($log);

        return $error_code;
    }

    /**
     * 修改补订单有效期
     */
    public function updateDeadLine($uid,$comm_type,$comm_year_month,$create_time){

        $this->db->where('uid', $uid)->where('comm_type',$comm_type)->where('comm_year_month',$comm_year_month)->set('create_time', $create_time)->update('withdraw_task');
        return true;
    }

    /**
     * 补订单处理。
     */
    public function orderRepair($uid,$orderAmount){

        $this->db->where('uid',$uid)->where('status',1)->where('sale_amount_lack <=',$orderAmount)->delete('withdraw_task');
        $this->db->where('uid',$uid)->where('status',1)->set('sale_amount_lack', 'sale_amount_lack-' . $orderAmount, FALSE)->update('withdraw_task');
    }

    /**
     * 获取到期未补单的需要抽回的佣金记录。
     */
    public function getArrivedWithdraw(){

        $deadLine = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-13,date("Y")));
        return $this->db->select('uid,comm_type,comm_year_month')->from('withdraw_task')->where('create_time <',$deadLine)->get()->result_array();
    }
    
}
