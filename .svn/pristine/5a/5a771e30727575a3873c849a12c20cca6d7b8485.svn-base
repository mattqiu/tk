<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/6/9
 * Time: 14:11
 */
class tb_users_credit_queue_user_rank extends MY_Model
{
    protected $table = "users_credit_queue_user_rank";
    protected $table_name = "users_credit_queue_user_rank";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_users_credit_reward_user_rank");
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

        $res = $this->db->from($this->table)->select("id")->where(array("uid" => $data['uid'], 'before_user_rank' => $data['before_user_rank'], 'after_user_rank' => $data['after_user_rank']))->get()->row_array();
        if (!empty($res)) {
            $this->db->replace($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }
        //echo $this->db->last_query();exit;
        return $this->db->insert_id();

    }

    /**
     * @author brady
     * @desc 根据队列里面的数据，计算用户的积分
     * @param $queue_data 队列数据
     * @return mixed
     */
    public function set_uids_credit($queue_data)
    {

        $uid = $queue_data['uid'];

        //获取父类10级
        $ids = $this->tb_users->get_ten_parent_ids($uid);
        if (!empty($ids)) {
            //获取用户的积分
            //一级父类
            $team = [];
            $team['1'] = $this->tb_users_credit_reward_user_rank->get_credit_by_level(1, $queue_data['before_user_rank'], $queue_data['after_user_rank']);
            //二级父类
            $team['2'] = $this->tb_users_credit_reward_user_rank->get_credit_by_level(2, $queue_data['before_user_rank'], $queue_data['after_user_rank']);
            //二级到十级父类
            $team['3'] = $this->tb_users_credit_reward_user_rank->get_credit_by_level(3, $queue_data['before_user_rank'], $queue_data['after_user_rank']);

        }
        $uid_change_credit = []; //准备加积分的十级父类
        foreach ($ids as $k => $v) {
            if ($k == 1 || $k == 2) {
                $uid_change_credit[$v] = array('credit' => $team[$k], 'team' => $k);
            } else {
                $uid_change_credit[$v] = array('credit' => $team['3'], 'team' => $k);
            }
        }
        $uids = array_keys($uid_change_credit);
        $user_info_all = $this->tb_users->get([  //批量获取是个用户的信息
                'select' => 'id,sale_rank',
                'where_in' => [
                    'key' => "id",
                    'value' => $uids
                ]
            ]
        );

        foreach ($uid_change_credit as $k => $v) {
            $this->set_uid_credit($k, $v['credit'], $queue_data);
        }
        //删除该条记录
        $this->db->delete($this->table, array('id' => $queue_data['id']));

    }

    /**
     * @author
     * @desc 设置单个用户的积分
     * @param $uid 积分要变化的id
     * @param $new_credit 新的积分
     * @param 等级变化队列
     * @$user_info_all 十个用户的信息
     */
    public function set_uid_credit($uid, $new_credit, $queue_data)
    {
        $this->load->model("tb_users_credit");
        $remark = "用户 " . $queue_data['uid'] . " 从 " . config_item('user_rank_name')[$queue_data['before_user_rank']] . " 变动到 " . config_item('user_rank_name')[$queue_data['after_user_rank']];
        $data = array(
            'uid' => $uid,
            'credit' => $new_credit,
            'created_time' => date("Y-m-d H:i:s", time()),
            'updated_time' => date("Y-m-d H:i:s", time()),
        );

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
        if (config_item("credit_switch") == "on") {
            $this->check_next_sale_rank_level($user_info, $total_credit, $queue_data['type']);
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
                $this->load->model("tb_users_credit_queue_sale_rank");
                $queue_data = array(
                    'uid' => $user_info['id'],
                    'before_sale_rank' => $user_info['sale_rank'],
                    'after_sale_rank' => $new_level,
                    'created_time' => date("Y-m-d H:i:s"),
                    'type' => $type
                );
                $this->db->where('id', $user_info['id'])->update('users', array('sale_rank' => $new_level, 'sale_rank_up_time' => date('Y-m-d H:i:s')));

                $this->tb_users_credit_queue_sale_rank->add_queue($queue_data);;
            }
        } else {
            if ($new_level != $user_info['sale_rank']) {
                $this->load->model("tb_users_credit_queue_sale_rank");
                $queue_data = array(
                    'uid' => $user_info['id'],
                    'before_sale_rank' => $user_info['sale_rank'],
                    'after_sale_rank' => $new_level,
                    'created_time' => date("Y-m-d H:i:s"),
                    'type' => $type
                );
                $this->db->where('id', $user_info['id'])->update('users', array('sale_rank' => $new_level, 'sale_rank_up_time' => date('Y-m-d H:i:s')));
                $this->tb_users_credit_queue_sale_rank->add_queue($queue_data);
            }
        }


    }


}