<?php
/**
 * @author Terry
 */
class tb_user_list_monthly_modify_logs extends CI_Model {


    function __construct() {
        parent::__construct();
    }
    
    
    /**
     * 添加修复业绩记录
     * @param unknown $data
     */
    public function addlogs($data)
    {
        $sql = "insert into user_list_monthly_modify_logs set uid = ".$data['uid'].
        ", amount=".$data['amount'].",amount_a=".$data['amount_a'].",create_time=Now()";       
        $this->db->query($sql);
    }
    
    /**
     * 根据用户id，获取用户销售额
     * @param unknown $uid
     * @return unknown|number
     */
    public function getUserMonthly($uid)
    {
        
        $stime = date('Y-m-01', strtotime('-1 month'))." 00:00:00";       
        $u_time = date("Ym",strtotime($stime));        
        $sql = "SELECT sale_amount FROM `users_store_sale_info_monthly` where uid = ".$uid." and `year_month`=".$u_time."";
        $query = $this->db->query($sql);
        $query_value = $query->row_array();
        if(!empty($query_value))
        {
            return $query_value['sale_amount'];
        }
        else
        {
            return 0;
        }
    }
    
    
    
}