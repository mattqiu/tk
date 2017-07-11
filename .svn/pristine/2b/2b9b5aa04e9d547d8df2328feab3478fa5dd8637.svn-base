<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/6/7
 * Time: 12:19
 */
class tb_users_credit extends MY_Model
{
    protected $table = "users_credit";
    protected $table_name = "users_credit";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_users_credit_reward_sale_rank");
        $this->load->model("tb_users_credit_reward_user_rank");
        $this->load->model("tb_users_credit_log");
        $this->load->model("tb_users");
    }

    public function init_user_credit($uid)
    {
        $data = [
            'uid'=>$uid,
            'credit'=>0,
            'created_time'=>date("Y-m-d H:i:s",time())
        ];
        return $this->db->insert($this->table,$data);
    }



    /**
     * @author brady.wang
     * @desc 检查是否存在积分记录
     * @param $uid
     * @return bool
     */
    public function check_exists($uid)
    {
        $res = $this->get([
            'select' => "uid,credit",
            'where' => [
                'uid' => $uid
            ],
            'limit' => 1
        ], false, true, true);
        if (empty($res)) {
            return false;
        } else {
            return $res;
        }
    }


    public function add_data($data)
    {
        return  $this->db->insert($this->table, $data);
    }

    public function update_data($data,$where)
    {
        $this->db->update($this->table,$data,$where);
    }
}