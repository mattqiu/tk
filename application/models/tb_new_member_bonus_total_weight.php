<?php
/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/4/18
 * Time: 10:21
 */

class tb_new_member_bonus_total_weight extends  MY_Model
{
    protected $table = "new_member_bonus_total_weight";
    protected $table_name = "new_member_bonus_total_weight";

    public function __construct()
    {
        parent::__construct();
    }

    public function del_by_day($day)
    {
        $this->db->query("delete from ".$this->table." where create_time=".$day);
        return $this->db->affected_rows();
    }

    public function get_by_day($day)
    {
        $res = $this->db->from($this->table)
            ->where(['create_time'=>$day])
            ->limit(1)
            ->get()
            ->row_array();
        if (!empty($res)) {
            return $res['total_weight'];
        } else {
            return false;
        }
    }
}