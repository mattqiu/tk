<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/1/7
 * Time: 14:32
 */
class tb_user_mobile_bind_log extends MY_Model
{
    protected $table = "user_bind_mobile_log";
    protected $table_name = "user_bind_mobile_log";

    public function add_log($data)
    {
        if (empty($data)) {
            return false;
        }
        $data['create_time'] = time();
        $data['type'] = empty($data['type']) ? 'bind_mobile' : $data['type'];
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }

}