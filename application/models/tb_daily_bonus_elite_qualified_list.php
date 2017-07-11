<?php
/**
 * 精英日分红合格用户表
 * @author Terry
 */
class tb_daily_bonus_elite_qualified_list extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 添加用户到合格表
     */
    public function addToEliteDailyQualiList($uid){

        $this->load->model('tb_stat_intr_mem_month');
        $this->load->model('tb_users_store_sale_info_monthly');

        $res = $this->db->select('qualified_day')->from('daily_bonus_elite_qualified_list')->where('uid',$uid)->get()->row_array();
        if(!$res || $res['qualified_day']==date('Ymd')){
            $this->db->replace('daily_bonus_elite_qualified_list',array(
                'uid'=>$uid,
                'bonus_shar_weight'=>$this->tb_stat_intr_mem_month->getProductSetSale($uid,date('Ym',strtotime("- 1 month"))) + $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($uid,date('Ym',strtotime("- 1 month"))),
                'qualified_day'=>date('Ymd')
            ));
        }
    }

    /*从合格列表中删除某个用户*/
    public function deleteOneUser($uid){

        $this->db->query("delete from daily_bonus_elite_qualified_list where uid=".$uid);
    }

    /**
     * 获取发奖列表人数
     */
    public function getDailyEliteSharNum(){

        return $this->db->query("select count(*) totalNum from daily_bonus_elite_qualified_list where qualified_day< ".date('Ymd'))->row_object()->totalNum;
    }

    /**
     * 统计今天分红人员的总权重点
     */
    public function statDailyEliteSharTotalWeight(){

        $res = $this->db->query("select sum(bonus_shar_weight) total_weight from daily_bonus_elite_qualified_list where qualified_day< ".date('Ymd'))->row_array();
        return $res?$res['total_weight']:0;
    }
}
