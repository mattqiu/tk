<?php
/**
 * 周领导对等奖发放队列表
 * @author Terry
 */
class tb_week_leader_members_queue extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 插入数据（生成发放列队）
     * @author Terry
     */
    public function insertWeekLeaderQueue(){

        $this->db->query("insert into week_leader_members_queue(uid) select uid from week_leader_members");
    }
    
}
