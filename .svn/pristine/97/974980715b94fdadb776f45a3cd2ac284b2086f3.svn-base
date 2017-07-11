<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc: 职称变动队列
 * Date: 2017/6/10
 * Time: 15:30
 */
class tb_users_credit_queue_sale_rank extends MY_Model
{
    protected $table = "users_credit_queue_sale_rank";
    protected $table_name = "users_credit_queue_sale_rank";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_users");
    }

    /**
     * @author brady.wang
     * @desc 用户升级，加入进队列
     * @param $data
     * @return mixed
     */
    public function add_queue($data)
    {
        $res = $this->db->from($this->table)->select("id")->where(array("uid" => $data['uid'],'type'=>1, 'before_sale_rank' => $data['before_sale_rank'], 'after_sale_rank' => $data['after_sale_rank']))->get()->row_array();
        if (!empty($res)) {
            $this->db->replace($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }
        //echo $this->db->last_query();exit;
        return $this->db->insert_id();

    }

    /**
     * @author brady.wang
     * @desc 用户降级加入队列
     * @param $data
     * @return mixed
     */
    public function add_queue_demote($data)
    {
        $res = $this->db->from($this->table)->select("id")->where(array("uid" => $data['uid'],'type'=>2, 'before_sale_rank' => $data['before_sale_rank'], 'after_sale_rank' => $data['after_sale_rank']))->get()->row_array();
        if (!empty($res)) {
            $this->db->replace($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }
        //echo $this->db->last_query();exit;
        return $this->db->insert_id();

    }


    public function del_queue($id)
    {
        return $this->db->delete("users_credit_queue_sale_rank", array('id' => $id));
    }

    /**
     * @author brady
     * @desc 职称升级影响的积分变动
     * @param $uid
     */
    public function set_uids_credit($queue_data)
    {
        $uid = $queue_data['uid'];
        $ids = $this->tb_users->get_two_parent_ids($uid);
        if (!empty($ids)) {
            //获取用户的积分
            //一级父类
            $team = [];
            $team['1'] = $this->tb_users_credit_reward_sale_rank->get_credit_by_level(1, $queue_data['before_sale_rank'], $queue_data['after_sale_rank']);
            //二级父类
            $team['2'] = $this->tb_users_credit_reward_sale_rank->get_credit_by_level(2, $queue_data['before_sale_rank'], $queue_data['after_sale_rank']);

        }
//        echo "当前用户信息";
//        dump($queue_data);
//        echo "父类id";
//        dump($ids);
//        echo "获得的积分";
//        dump($team);
        $uid_change_credit = []; //准备加积分的2级父类
        foreach ($ids as $k => $v) {
            $uid_change_credit[$v] = array('credit' => $team[$k], 'team' => $k);
        }

        foreach ($uid_change_credit as $k => $v) {
            $this->set_uid_credit($k, $v['credit'], $queue_data);

        }

    }


    /**
     * @author
     * @desc 设置单个用户的积分
     */
    public function set_uid_credit($uid, $new_credit, $queue_data)
    {

        if ($uid != config_item('mem_root_id')) {
            $remark = "用户 " . $queue_data['uid'] . " 从 " . config_item('sale_rank_name')[$queue_data['before_sale_rank']] . " 升级到 " . config_item('sale_rank_name')[$queue_data['after_sale_rank']];
            $data = array(
                'uid' => $uid,
                'credit' => $new_credit,
                'created_time' => date("Y-m-d H:i:s", time()),
                'updated_time' => date("Y-m-d H:i:s", time()),
            );
            $this->load->model("tb_users_credit");
            $old_res = $this->tb_users_credit->check_exists($uid);
            if (!$old_res) {
                $old_credit = 0;
                $this->tb_users_credit->add_data($data);
            } else {
                //有数据，更新
                unset($data['created_time']);
                $old_credit = $old_res['credit'];
                $data['credit'] = $old_credit + $new_credit;
                $this->tb_users_credit->update_data($data, ['uid' => $uid]);

            }

            $total_credit = $new_credit + $old_credit;

            //添加日志
            $this->tb_users_credit_log->create_log($uid, $old_credit, $total_credit, $queue_data['uid'], $remark);

            //检测积分是否达到职称升级条件 如果达到的话要将它加入到职称升级队列
            $user_info = $this->tb_users->get([  //批量获取是个用户的信息
                'select' => 'id,sale_rank,user_rank',
                'where' => [
                    'id' => $uid,
                ]
            ], false, true, true
            );
            //积分影响等级
            if (config_item("credit_switch") == "on") {
                $this->check_next_sale_rank_level($user_info, $total_credit, $queue_data['type']);
            }
        }


    }

    /**
     * @author brady
     * @desc   检查当前积分满足的分数是否达到下一职称等级
     * @param $user_info
     * @param $credit
     */
    public function check_next_sale_rank_level($user_info, $credit, $type)
    {
        if (!empty($user_info)) {

            $this->load->model("tb_users");
            //dump($credit . "-" . $user_info['id']);
            $users_credit_config = config_item("users_credit_config");

            $new_level = '';
            foreach ($users_credit_config as $k => $v) {
                if ($credit >= $v['credit']) {
                    $new_level = $k;
                } else {
                    if ($k > 1) {
                        $new_level = $k - 1;
                    } else {
                        $new_level = 0;
                    }
                    break;
                }
            }
            if ($type == 1) { //下面用户升级导致的，用户职称只升不降
                if ($new_level > $user_info['sale_rank']) {
                    $this->tb_users->update_sale_rank($user_info, $user_info['sale_rank'], $new_level, $type);
                }
            } else {
                if ($new_level != $user_info['sale_rank']) {
                    $this->tb_users->update_sale_rank($user_info, $user_info['sale_rank'], $new_level, $type);
                }
            }
        }

    }

}