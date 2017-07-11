<?php

/**
 * @author Terry
 */
class tb_month_sharing_members extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 检查某用户是否在当月杰出店铺分红奖里面。
     */
    public function checkIfInCurMonthQualifiedList($uid)
    {
        /*
        $res = $this->db->from('month_sharing_members')
            ->select('uid')
            ->where('uid', $uid)
            ->get()
            ->row_array();
         * 
         */
        $res = $this->db->query("select uid from month_sharing_members where uid={$uid}")->row_array();
        return $res ? true : false;
    }

    public function deleteOneUser($uid)
    {
        $this->db->query("delete from month_sharing_members where uid=" . $uid);
    }

    /**
     * 添加月杰出店铺分红奖
     * 
     * @param unknown $uid            
     */
    public function addCurMonthQualifiedUserList($uid, $point)
    {
        $customer_exits = $this->checkIfInCurMonthQualifiedList($uid);
        if (! $customer_exits) {
            $sql = "INSERT INTO  month_sharing_members SET uid =" . $uid . ", sharing_point=" . $point;
            $this->db->query($sql);
        }
    }

    /**
     * 检查补发用户每月杰出店铺发奖额度信息
     * 
     * @param unknown $uid            
     * @param unknown $item_type            
     * @param unknown $c_time            
     * @return boolean
     */
    public function checkEveryMonthAmountByUid($uid, $item_type, $c_time)
    {
        $month_team_tb_name = "cash_account_log_" . date("Ym", strtotime($c_time));        
        $month_sql = "SELECT * FROM " . $month_team_tb_name . " WHERE uid = " . $uid . " AND item_type=" . $item_type . " AND DATE_FORMAT(create_time,'%Y-%m-%d')='" . $c_time . "'";
        $query = $this->db->query($month_sql);
        $return_value = $query->row_array();
        if (! empty($return_value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * **
     * 补发每月杰出店铺发奖额度信息
     * 
     * @param 用户id $uid            
     * @param 用户分红点 $point            
     * @param 发奖类型 $item_type            
     * @param 时间 $c_time            
     * @param 不在查询范围的用户id $uid_array            
     */
    public function everyMonthRessiueAutoAmount($uid, $point, $item_type, $c_time, $uid_array, $count)
    {       
        $month_team_tb_name = "cash_account_log_" . date("Ym", strtotime($c_time));
        $month_sql = "SELECT uid, amount, create_time FROM  " . $month_team_tb_name . "  WHERE  item_type=8 AND DATE_FORMAT(create_time,'%Y-%m-%d')='" . $c_time . "'  group by amount  order by amount asc  limit 0,2";
        $query = $this->db->query($month_sql);
        return $query->result_array();             
    }
}
