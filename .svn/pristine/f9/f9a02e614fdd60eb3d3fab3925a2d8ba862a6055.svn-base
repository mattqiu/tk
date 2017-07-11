<?php
/**
 * @author Terry
 */
class tb_grant_pre_bonus_state extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 更新预发奖状态
     */
    public function edit_state($item_type,$state,$remark)
    {
        $sel_sql = "SELECT * FROM grant_pre_bonus_state WHERE item_type=".$item_type;
        $sel_query = $this->db->query($sel_sql);
        $sel_exits = $sel_query->row_array();
        if(!empty($sel_exits))
        {
            $sql = "UPDATE grant_pre_bonus_state SET state='".(int)$state."', remark='".$remark."' WHERE item_type=".$item_type;            
        }
        else 
        {
            $sql = "INSERT INTO grant_pre_bonus_state SET state='".(int)$state."', item_type=".$item_type.", remark='".$remark."'";
        }
        $this->db->query($sql);
        
    }
    
    /***
     * 获取预发奖状态
     * @param 分红类型  $item_type
     */
    public function get_state($item_type)
    {
        $sql = "SELECT * FROM grant_pre_bonus_state WHERE item_type=".$item_type;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
        
}
