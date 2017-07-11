<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/6/8
 * Time: 18:51
 */
class repair extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_users_credit");
        $this->load->model("tb_users_credit_log");
        $this->load->model("tb_users");
    }


    /**
     * @
     */
    public function user_credit()
    {
        header("content-type:text/html;charset=utf-8");
        ini_set("memory_limit", "5000M");
        ignore_user_abort();
        set_time_limit(0);
        $uid = $this->input->get("uid");
        if (empty($uid)) {
            echo "uid参数错误";
        }
        $uid_arr = explode(",", $uid);

        $sql = "select id,user_rank,sale_rank from users where id in(" . $uid . ") order by id asc  ";
        $res = $this->db->query($sql)->result_array();
        $this->db->trans_begin();
        if (!empty($res)) {
            foreach ($res as $v) {
                $old_user = [
                    'id' => $v['id'],
                    'user_rank' => 4,
                    'sale_rank' => 0
                ];
                $this->load->model('o_users_credit_init');
                $this->load->model('tb_users_credit');

                $new_credit = $this->o_users_credit_init->calc_user_credit_init($v['id']);
                var_dump("用户" . $v['id'] . "积分为" . $new_credit);

                $old_res = $this->tb_users_credit->check_exists($v['id']);
                if (!empty($old_res) && isset($old_res['credit'])) {
                    $old_credit = $old_res['credit'];
                } else {
                    $old_credit = 0;
                }
                //插入到用户积分表
                $this->o_users_credit_init->edit_user_credit_init($v['id'], $new_credit, $old_credit, 0, config_item("user_credit_remark_message")['init_user_batch']);

                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $msg = '[success]修复用户积分成功 ' . json_encode($v['id'] . "-" . $new_credit);
                    dump($msg);
                    $this->m_log->createCronLog($msg);
                } else {
                    $this->db->trans_rollback();
                    $msg = '[faile]修复用户积分失败 page' . json_encode($v['id'] . "-" . $new_credit);
                    dump($msg);
                    $this->m_log->createCronLog($msg);
                }
            }
        }
    }
}