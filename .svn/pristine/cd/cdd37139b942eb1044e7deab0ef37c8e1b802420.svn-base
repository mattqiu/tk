<?php
/**
 * @author Terry
 */
class tb_stat_intr_mem_month extends MY_Model {

    protected $table_name = "stat_intr_mem_month";
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 检查某个会员是否推荐过付费的会员（也就是是否卖过套餐）
     */
    public function checkIfIntrPayedMember($uid,$year_month=''){

        if($year_month){

            $sql = 'SELECT * FROM stat_intr_mem_month where uid='.$uid.' and `year_month`="'.$year_month.'" and (member_bronze_num>0 or member_silver_num>0 or member_platinum_num>0 or member_diamond_num>0)';
        }else{

            $sql = 'SELECT * FROM stat_intr_mem_month where uid='.$uid.' and (member_bronze_num>0 or member_silver_num>0 or member_platinum_num>0 or member_diamond_num>0)';
        }

        $res = $this->db_slave->query($sql)->row_object();
        
        
        return $res?true:false;
    }

    //查询会员的套餐销售额
    public function getProductSetSale($uid,$year_month){

        $res = $this->db_slave->select('pro_set_amount')->from('stat_intr_mem_month')->where('uid',$uid)->where('year_month',$year_month)->get()->row_array();
        return $res?$res['pro_set_amount']:0;
    }

    /**
     * @author brady
     * @description 获取2017/04/01之后的会员的套餐销售额
     */
    public function getProductSaleAmount($uid,$year_month)
    {
        $res = $this->db->select('sum(pro_set_amount) as pro_set_amount')->from('stat_intr_mem_month')->where('uid',$uid)->where('year_month >=',$year_month)->get()->row_array();
        return $res?$res['pro_set_amount']:0;
    }
    
}
