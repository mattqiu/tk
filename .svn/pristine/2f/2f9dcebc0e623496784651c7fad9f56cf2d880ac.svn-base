<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/6/7
 * Time: 12:17
 */
class tb_users_credit_reward_sale_rank extends MY_Model
{
    protected $table = "users_credit_reward_sale_rank";
    protected $table_name = "users_credit_reward_sale_rank";

    // 0普通店主，1资深店主，2销售经理，3销售主任，4销售总监，5全球销售副总裁',
    //一级
    protected $credit_1_0_1 = 100;//从普通会员->资深会员，系统奖励100积分；
    protected $credit_1_1_2 = 300;//从资深会员->市场主管，系统奖励300积分；；
    protected $credit_1_2_3 = 900;//从市场主管->高级市场主管，系统奖励900积分
    protected $credit_1_3_4 = 2700;//从高级市场主管->市场总监，系统奖励2700积分；
    protected $credit_1_4_5 = 8100;//从市场总监->市场副总裁，系统奖励 8100积分；

    //二级
    protected $credit_2_0_1 = 50;//从普通会员->资深会员，系统奖励150积分；
    protected $credit_2_1_2 = 150;//从资深会员->市场主管，系统奖励150积分；；
    protected $credit_2_2_3 = 450;//从市场主管->高级市场主管，系统奖励450积分
    protected $credit_2_3_4 = 1350;//从高级市场主管->市场总监，系统奖励1350积分；
    protected $credit_2_4_5 = 4050;//从市场总监->市场副总裁，系统奖励 4050积分；


    public function __construct()
    {
        parent::__construct();
    }



    /**
     * @author brady
     * @desc 根据职称初始化积分
     * @param $team_level 第几个下级
     * @param $old 旧的等级
     * @param $new 新的等级
     * @return int 积分
     * 从普通会员->资深会员，系统奖励100积分；
     * 从资深会员->市场主管，系统奖励300积分；
     * 从市场主管->高级市场主管，系统奖励900积分；
     * 从高级市场主管->市场总监，系统奖励2700积分；
     * 从市场总监->市场副总裁，系统奖励 8100积分；
     */
    public function get_credit_by_level($team_level, $old, $new)
    {
        if (!in_array($team_level, [1, 2])) {
            echo "参数错误";
            return 0;
        }
        switch ($old) {
            case "0": //普通会员开始
            {
                if ($new == 0) { //普通会员-普通会员
                    $credit = 0;
                } elseif ($new == 1) {//普通会员-资深会员
                    $credit_str = "credit_" . $team_level . "_0_1";
                    $credit = $this->$credit_str;
                } elseif ($new == 2) { //普通会员-市场主管
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit = $this->$credit_str1 + $this->$credit_str2;
                } elseif ($new == 3) { //普通会员-高级市场主管
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit = $this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3;
                } elseif ($new == 4) { //普通会员-市场总监
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = $this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4;
                } elseif ($new == 5) { //普通会员-市场副总裁
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = $this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4 + $this->$credit_str5;
                }
            }
                break;

            case "1": //资深会员开始
            {
                if ($new == 0) { //资深会员-普通会员
                    $credit_str = "credit_" . $team_level . "_0_1";
                    $credit = -$this->$credit_str;
                } elseif ($new == 1) {//资深会员-资深会员
                    $credit = 0;
                } elseif ($new == 2) { //资深会员-市场主管
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit = $this->$credit_str2;
                } elseif ($new == 3) { //资深会员-高级市场主管
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit = $this->$credit_str2 + $this->$credit_str3;
                } elseif ($new == 4) { //资深会员-市场总监
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4;
                } elseif ($new == 5) { //资深会员-市场副总裁
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4 + $this->$credit_str5;
                }
            }
                break;

            case "2": //市场主管开始
            {
                if ($new == 0) { //市场主管-普通会员
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit = -($this->$credit_str1 + $this->$credit_str2);
                } elseif ($new == 1) {//市场主管-资深会员
                    $credit_str = "credit_" . $team_level . "_1_2";
                    $credit = -$this->$credit_str;
                } elseif ($new == 2) { //市场主管-市场主管

                    $credit = 0;
                } elseif ($new == 3) { //市场主管-高级市场主管

                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit = $this->$credit_str3;
                } elseif ($new == 4) { //市场主管-市场总监

                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = $this->$credit_str3 + $this->$credit_str4;
                } elseif ($new == 5) { //市场主管-市场副总裁

                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = $this->$credit_str3 + $this->$credit_str4 + $this->$credit_str5;
                }
            }
                break;

            case "3": //高级市场主管开始
            {
                if ($new == 0) { //高级市场主管-普通会员
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit = -($this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3);
                } elseif ($new == 1) {//高级市场主管-资深会员
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit = -($this->$credit_str2 + $this->$credit_str3);
                } elseif ($new == 2) { //高级市场主管-市场主管
                    $credit_str1 = "credit_" . $team_level . "_2_3";
                    $credit = -$this->$credit_str1;
                } elseif ($new == 3) { //高级市场主管-高级市场主管
                    $credit = 0;
                } elseif ($new == 4) { //高级市场主管-市场总监

                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = $this->$credit_str4;
                } elseif ($new == 5) { //高级市场主管-市场副总裁

                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = $this->$credit_str4 + $this->$credit_str5;
                }
            }
                break;

            case "4": //市场总监开始
            {
                if ($new == 0) { //市场总监-市场总监
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = -($this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4);
                } elseif ($new == 1) {//市场总监-资深会员
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = -($this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4);
                } elseif ($new == 2) { //市场总监-市场主管
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = -($this->$credit_str3 + $this->$credit_str4);
                } elseif ($new == 3) { //市场总监-高级市场主管
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit = -($this->$credit_str4);
                } elseif ($new == 4) { //市场总监-市场总监
                    $credit = 0;
                } elseif ($new == 5) { //市场总监-市场副总裁

                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = $this->$credit_str5;
                }
            }
                break;

            case "5": //市场副总裁开始
            {
                if ($new == 0) { //市场副总裁-普通会员
                    $credit_str1 = "credit_" . $team_level . "_0_1";
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = -($this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4 + $this->$credit_str5);
                } elseif ($new == 1) {//市场副总裁-资深会员
                    $credit_str2 = "credit_" . $team_level . "_1_2";
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = -($this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4 + $this->$credit_str5);
                } elseif ($new == 2) { //市场副总裁-市场主管
                    $credit_str3 = "credit_" . $team_level . "_2_3";
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = -($this->$credit_str3 + $this->$credit_str4 + $this->$credit_str5);
                } elseif ($new == 3) { //市场副总裁-高级市场主管
                    $credit_str4 = "credit_" . $team_level . "_3_4";
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = -($this->$credit_str4 + $this->$credit_str5);
                } elseif ($new == 4) { //市场副总裁-市场总监
                    $credit_str5 = "credit_" . $team_level . "_4_5";
                    $credit = -($this->$credit_str5);
                } elseif ($new == 5) { //市场副总裁-市场副总裁
                    $credit = 0;
                }
            }
                break;
            default : {
                $credit = 0;
            }
        }
        return $credit;
    }


}