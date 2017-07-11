<?php
/**
 * @author Terry
 */
class tb_system_grant_bonus_logs extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 添加操作日志
     */
    public function add_grant_bonus_logs($data)
    {

        $time = strtotime(date('Y-m-d H:i:s'));
        $sql = "INSERT INTO system_grant_bonus_logs SET uid=".$data['uid'].", item_type=".$data['item_type'].", grant_type=".$data['grant_type'].",grant_state=".$data['grant_state']."create_time='".$time."'";
        $this->db->query($sql);
    }
    
    
}
