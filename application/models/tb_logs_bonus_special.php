<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/4/21
 * Time: 10:34
 */
class tb_logs_bonus_special extends MY_Model
{
    protected $table = "logs_bonus_special";
    protected $table_name = "logs_bonus_special";

    public function __construct()
    {
        parent::__construct();
    }

    public function add_log($admin_info,$data,$function)
    {
        $this->add(array('admin_id'=>$admin_info['id'],'admin_email'=>$admin_info['email'],"action"=>$function,"action_data"=>
            json_encode($data)));
        return $this->db->insert_id();
    }
}