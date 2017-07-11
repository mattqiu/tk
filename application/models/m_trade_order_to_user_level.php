<?php

class m_trade_order_to_user_level extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    public function add_trade_order_user_level($order_id,$uid,$old_level,$new_level,$type){
        $this->db->insert('trade_order_to_user_level',array(
            'order_id'=>$order_id,
            'uid'=>$uid,
            'old_level'=>$old_level,
            'new_level'=>$new_level,
            'type'=>$type,
            'system_time'=>date("Y-m-d H:i:s",time())
        ));
    }
}