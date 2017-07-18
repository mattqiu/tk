<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc: 银行卡
 * Date: 2017/7/5
 * Time: 19:56
 */
class tb_users_bank_card extends  MY_Model
{
    protected $table = "users_bank_card";
    protected $table_name  ="users_bank_card";

    public function __construnct()
    {
        parent::__construct();
    }

    public function add_bank_card($data)
    {
        $data['create_time'] = date("Y-m-d H:i:s");
        return $this->add($data);

    }

    /**
     * @author brady.wang
     * @desc 删除用户绑定的银行卡
     */
    public function del_bank_card($uid)
    {
        return $this->db->delete($this->table,array('uid'=>$uid));

    }


}