<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc: 用户冻结列表
 * Date: 2017/6/22
 * Time: 16:54
 */
class tb_user_frost_list extends MY_Model
{
    protected $table = "user_frost_list";
    protected $table_name = "user_frost_list";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author brady.wang
     * @desc 添加进入冻结
     * @param $uid 冻结用户
     * @param $frost_days 冻结天数
     */
    public function add_queue($uid,$frost_days)
    {
        $frost_days = intval($frost_days);
        $create_time = date("Y-m-d");
        $end_time = date("Y-m-d",strtotime("+".$frost_days." day"));
        $data = [
            'uid'=>$uid,
            'frost_days'=>$frost_days,
            'create_time'=>date("Y-m-d H:i:s"),
            'end_time'=>$end_time
        ];
        if ($frost_days >0) {
            return $res = $this->replace($data);
        }else {
            return true;
        }
    }

    /**
     * @author brady.wang
     * @desc   解除冻结
     * @param $uid 用户id
     * @return mixed int
     */
    public function del_queue($uid)
    {
        return $this->db->delete($this->table,array('uid'=>$uid));
    }

    /**
     * @author brady.wang
     * @desc   用户解冻队列删除
     */
    public function cron_unfrost_user()
    {
        $res = $this->db->from($this->table)
            ->where(array('end_time <'=> date("Y-m-d H:i:s",time())))
            ->order_by('id asc')
            ->get()
            ->result_array();
        $ids = [];
        $uids = [];
        if (!empty($res)) {
            foreach($res as $v) {
                $ids[] = $v['id'];
                $uids[] = $v['uid'];
            }
            $this->db->where_in('id',$ids);
            $this->db->delete($this->table);
        }
        $this->db->where_in('id', $uids)->set('status', 1)->update('users');
        return $uids;
    }


}