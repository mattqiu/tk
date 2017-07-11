<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/20
 * Time: 17:31
 */
class td_system_rebate_conf_child extends MY_Model {

    protected $table = "system_rebate_conf_child";

    public function __construct()
    {
        parent::__construct();
    }


    public function getList(){
        $this->db->from($this->table);
        $this->db->join('system_rebate_conf','system_rebate_conf.child_id=system_rebate_conf_child.id','left');
        $query = $this->db->get()->result_array();
        echo $this->db->last_query();
        return $query;
    }

}