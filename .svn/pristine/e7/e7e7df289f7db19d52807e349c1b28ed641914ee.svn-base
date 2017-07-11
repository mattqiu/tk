<?php
/**
 * @author Terry
 */
class tb_system_grant_bonus_queue_list extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 根据分红奖类型，修改发奖状态
     * @param 分红类型 $item_type
     */
    public function edit($item_type)
    {
        $edit_sql = "update system_grant_bonus_queue_list set grant_type = 1 where item_type=".$item_type;
        $this->db->query($edit_sql);
    }
    
    /**
     * 新增后台执行发奖命令
     * @param 分红类型 $item_type
     * @param 发奖类型(1.预发奖；2.发奖)  $pre_type  
     */
    public function add($item_type,$pre_type)
    {
        $c_time = strtotime(date('Y-m-d H:i:s'));
        $sql = "insert into system_grant_bonus_queue_list set item_type=".$item_type.",pre_type=".$pre_type.", grant_type=0 ,create_time=".$c_time;
        $this->db->query($sql);
    }
 
    /**
     * 根据分红奖清除信息
     * @param 发奖类型  $item_type
     */
    public function del($item_type)
    {
        $sql = "delete from system_grant_bonus_queue_list where item_type = ".$item_type;
        $this->db->query($sql);
    }


    public function getTotal($item_type){
        $res = $this->db->from("system_grant_bonus_queue_list")
            ->select('count(1) as number')
            ->where(array('item_type'=>$item_type))
            ->get()
            ->row_object()
            ->number;
        return $res;
    }
    
    
}
