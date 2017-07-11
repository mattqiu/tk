<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/6/8
 * Time: 18:24
 */
class tb_users_credit_log extends MY_Model
{
    protected $table = "users_credit_log";
    protected $table_name = "users_credit_log";

    public function __construct()
    {
        parent::__construct();
        $year_month = date("Ym",time());
        $this->table = $this->table."_".$year_month;
        $this->table_name = $this->table."_".$year_month;
        //$this->create_table();
    }

    public function create_table()
    {
        $sql = "CREATE TABLE if not exists `".$this->table."` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
              `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前的积分',
              `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后的积分',
              `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
              `child_uid` int(11) NOT NULL DEFAULT '0' COMMENT '影响该次变动的用户',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='积分变动日志表';";
        $this->db->query($sql);
    }

    /**
     * @author brady
     * @desc 创建积分变动日志
     */
    public function create_log($uid, $old, $new,$child_uid=0,$remark='')
    {
        $data['uid'] = $uid;
        $data['before_amount'] = $old;
        $data['after_amount'] = $new;
        $data['created_time'] = date("Y-m-d H:i:s");
        $data['child_uid'] = $child_uid;
        $data['remark'] = $remark;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}