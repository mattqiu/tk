<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc: 用户积分初始化
 * Date: 2017/6/10
 * Time: 10:31
 */
class o_users_credit_init extends MY_Model
{
    protected $table = "users_credit";
    protected $table_name = "users_credit";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_users_credit_reward_user_rank");
        $this->load->model("tb_users_credit_reward_sale_rank");
        $this->load->model("tb_users_credit");
        $this->load->model("tb_users_credit_log");

    }

    /**
     * @param $uid 用户id
     * @param $old_user_info 初始化的时候要计算的用户信息
     */
    public function calc_user_credit_init($uid)
    {

        $credit_user_rank = $this->get_user_credit_by_user_rank($uid);
        $credit_sale_rank = $this->get_user_credit_by_sale_rank($uid);
       // echo "用戶积分：" . $credit_user_rank . " 职称积分：" . $credit_sale_rank;
        return $credit_user_rank + $credit_sale_rank;
    }

    public function  get_user_credit_by_user_rank($uid)
    {

        $sql0 = "select user_rank ,count(id) as count from users where parent_id=" . $uid . " GROUP BY user_rank";//#第一层，user_rank=5/3/2/1时，分别乘以50/100/200/300
        $sql1 = "select user_rank ,count(id) as count from users where parent_id in ( select id from users where parent_id=" . $uid . ")GROUP BY user_rank"; //#第二层,user_rank=5/3/2/1时，分别乘以5/5/10/10
        $sql2 = "select user_rank ,count(id) as count from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")) GROUP BY user_rank";//#第三层,user_rank=5/3/2/1时，分别乘以25/50/100/150
        $sql3 = "select user_rank ,count(id)  as count from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . "))) GROUP BY user_rank";//;#第四层
        $sql4 = "select user_rank ,count(id)  as count from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")))) GROUP BY user_rank";//; #第五层
        $sql5 = "select user_rank ,count(id) as count from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")))) ) GROUP BY user_rank";//; #第六层
        $sql6 = "select user_rank ,count(id) as count from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")))) )) GROUP BY user_rank";//; #第七层
        $sql7 = "select user_rank ,count(id) as count from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")))) )) ) GROUP BY user_rank ";//;#第八层
        $sql8 = "select user_rank ,count(id) as count from users where parent_id in (select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")))) )) ) ) GROUP BY user_rank";//; #第九层
        $sql9 = "select user_rank ,count(id) as count from users where parent_id in (select id from users where parent_id in (select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in (select id from users where parent_id in ( select id from users where parent_id in ( select id from users where parent_id=" . $uid . ")))) )) ) )  ) GROUP BY user_rank ";//;#第十层
        $credit_rank = [
            '1' => [
                '4' => 0,
                '5' => 50,
                '3' => 100,
                '2' => 200,
                '1' => 300
            ],
            '2' => [
                '4' => 0,
                '5' => 25,
                '3' => 50,
                '2' => 100,
                '1' => 150
            ],
            '3' => [
                '4' => 0,
                '5' => 5,
                '3' => 10,
                '2' => 20,
                '1' => 30
            ],
        ];
        $total = 0;
        for ($i = 0; $i < 10; $i++) {
            $val = "sql" . $i;
            $res = $this->db->query($$val)->result_array();

            if (!empty($res)) {
                if ($i == 0) { //第一层
                    foreach ($res as $v) {
                        $total += $credit_rank['1'][$v['user_rank']] * $v['count'];
                    }
                } elseif ($i == 1) {
                    foreach ($res as $v) {
                        $total += $credit_rank['2'][$v['user_rank']] * $v['count'];
                    }
                } else {
                    foreach ($res as $v) {
                        $total += $credit_rank['3'][$v['user_rank']] * $v['count'];
                    }
                }
            }

        }
        return $total;
    }

    public function get_user_credit_by_sale_rank($uid)
    {
        #职称晋级
        $sql0 = "select sale_rank,count(id) as count from users where parent_id=" . $uid . " GROUP BY sale_rank";//; #第一层,user_rank=1/2/3/4/5时，分别乘以100/400/1300/4000/12100
        $sql1 = "select sale_rank,count(id) as count  from users where parent_id in ( select id from users where parent_id=" . $uid . ") GROUP BY sale_rank";// #第二层,user_rank=1/2/3/4/5时，分别乘以50/200/650/2000/6050
        $credit_rank = [
            '1' => [
                '0' => 0,
                '1' => 100,
                '2' => 400,
                '3' => 1300,
                '4' => 4000,
                '5' => 12100
            ],
            '2' => [
                '0' => 0,
                '1' => 50,
                '2' => 200,
                '3' => 650,
                '4' => 2000,
                '5' => 6050
            ]
        ];
        $total = 0;
        for ($i = 0; $i < 2; $i++) {
            $val = "sql" . $i;
            $res = $this->db->query($$val)->result_array();

            if (!empty($res)) {
                if ($i == 0) { //第一层
                    foreach ($res as $v) {
                        $total += $credit_rank['1'][$v['sale_rank']] * $v['count'];
                    }
                } elseif ($i == 1) {
                    foreach ($res as $v) {
                        $total += $credit_rank['2'][$v['sale_rank']] * $v['count'];
                    }
                }
            }

        }
        return $total;
    }


    /**
     * @author brady
     * @desc 初始化的时候 编辑用户的积分，对用户的职称不影响
     * @param $uid
     * @param $new_credit
     * @param $old_credit
     * @param string $remark
     */
    public function edit_user_credit_init($uid, $new_credit, $old_credit, $child_uid = 0, $remark = '')
    {
        $old_res = $this->tb_users_credit->check_exists($uid);

        if ($new_credit != $old_credit ||($new_credit == 0 && empty($res))) {
            $data = array(
                'uid' => $uid,
                'credit' => $new_credit,
                'created_time' => date("Y-m-d H:i:s", time()),
                'updated_time' => date("Y-m-d H:i:s", time()),
            );
            if ($old_res) {
                unset($data['created_time']);
                $this->db->update($this->table, $data, ['uid' => $uid]);
            } else {
                $this->db->insert($this->table, $data);
            }

            $affected_rows = $this->db->affected_rows();
            if ($affected_rows > 0) {
                //添加日志
                $this->tb_users_credit_log->create_log($uid, $old_credit, $new_credit, $child_uid, $remark);
            }
        }

    }
}