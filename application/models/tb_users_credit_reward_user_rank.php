<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/6/7
 * Time: 12:18
 */
class tb_users_credit_reward_user_rank extends MY_Model
{
    protected $table = "users_credit_reward_user_rank";
    protected $table_name = "users_credit_reward_user_rank";

    //一级
    protected $credit_1_4_5 = 50;//免费到铜级
    protected $credit_1_5_3 = 50;//铜级到银级
    protected $credit_1_3_2 = 100;//银级到白金
    protected $credit_1_2_1 = 100;//白金到钻石

    //二级
    protected $credit_2_4_5 = 25;//免费到铜级
    protected $credit_2_5_3 = 25;//铜级到银级
    protected $credit_2_3_2 = 50;//银级到白金
    protected $credit_2_2_1 = 50;//白金到钻石

    //三级到十级
    protected $credit_3_4_5 = 5;//免费到铜级
    protected $credit_3_5_3 = 5;//铜级到银级
    protected $credit_3_3_2 = 10;//银级到白金
    protected $credit_3_2_1 = 10;//白金到钻石


    public function __construct()
    {
        parent::__construct();

        $this->load->model("tb_users");
    }

    /**
     * @param $team_level
     * @param $old
     * @param $new
     * @return mixed
     * user_rank` tinyint(3) unsigned NOT NULL DEFAULT '4' COMMENT '用户等级, 1钻石，2白金级，3银级，4免费会员，5铜级会员',
     *  4 5 2 2 1
     */
    public function get_credit_by_level($team_level = 1, $old, $new)
    {

        if (!in_array($team_level, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]) || empty($old) || empty($new)) {
            echo "参数错误";
            return 0;
        }
        $team_level = ($team_level >= 3) ? 3 : $team_level;
        switch ($old) {
            case "4": //免费店铺开始
            {
                if ($new == 4) { //免费到免费
                    $credit = 0;
                } elseif ($new == 5) {//免费到铜级
                    $credit_str = "credit_" . $team_level . "_4_5";
                    $credit = $this->$credit_str;
                } elseif ($new == 3) { //免费到银级
                    $credit_str1 = "credit_" . $team_level . "_4_5";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit = $this->$credit_str1 + $this->$credit_str2;
                } elseif ($new == 2) {
                    $credit_str1 = "credit_" . $team_level . "_4_5";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit_str3 = "credit_" . $team_level . "_3_2";
                    $credit = $this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3;
                } elseif ($new == 1) {
                    $credit_str1 = "credit_" . $team_level . "_4_5";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit_str3 = "credit_" . $team_level . "_3_2";
                    $credit_str4 = "credit_" . $team_level . "_2_1";
                    $credit = $this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4;
                }
            }
                break;

            case "5": //铜级店铺开始
            {
                if ($new == 4) { //铜级到免费
                    $credit_str = "credit_" . $team_level . "_4_5";
                    $credit = -$this->$credit_str;
                } elseif ($new == 5) {//铜级到铜级
                    $credit = 0;
                } elseif ($new == 3) { //铜级到银级
                    $credit_str1 = "credit_" . $team_level . "_5_3";
                    $credit = $this->$credit_str1;
                } elseif ($new == 2) { //银级到白金
                    $credit_str1 = "credit_" . $team_level . "_5_3";
                    $credit_str2 = "credit_" . $team_level . "_3_2";
                    $credit = $this->$credit_str2 + $this->$credit_str1;
                } elseif ($new == 1) { //白金岛钻石
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit_str3 = "credit_" . $team_level . "_3_2";
                    $credit_str4 = "credit_" . $team_level . "_2_1";
                    $credit = $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4;
                }
            }
                break;

            case "3": //银级店铺开始
            {
                if ($new == 4) { //银级到免费
                    $credit_str1 = "credit_" . $team_level . "_4_5";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit = -($this->$credit_str1 + $this->$credit_str2);
                } elseif ($new == 5) {//银级到铜级
                    $credit_str1 = "credit_" . $team_level . "_5_3";
                    $credit = -$this->$credit_str1;
                } elseif ($new == 3) { //银级到银级
                    $credit = 0;
                } elseif ($new == 2) { //银级到白金
                    $credit_str2 = "credit_" . $team_level . "_3_2";
                    $credit = $this->$credit_str2;
                } elseif ($new == 1) { //银级岛钻石
                    $credit_str3 = "credit_" . $team_level . "_3_2";
                    $credit_str4 = "credit_" . $team_level . "_2_1";
                    $credit = $this->$credit_str3 + $this->$credit_str4;
                }
            }
                break;

            case "2": //白金店铺开始
            {
                if ($new == 4) { //白金到免费
                    $credit_str1 = "credit_" . $team_level . "_4_5";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit_str3 = "credit_" . $team_level . "_3_2";
                    $credit = -($this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3);
                } elseif ($new == 5) {//白金到铜级
                    $credit_str1 = "credit_" . $team_level . "_3_2";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit = -($this->$credit_str1 + $this->$credit_str2);
                } elseif ($new == 3) { //白金到银级
                    $credit_str1 = "credit_" . $team_level . "_3_2";
                    $credit = $this->$credit_str1;
                } elseif ($new == 2) { //白金到白金
                    $credit = 0;
                } elseif ($new == 1) { //白金到钻石
                    $credit_str4 = "credit_" . $team_level . "_2_1";
                    $credit = $this->$credit_str4;
                }
            }
                break;

            case "1": //钻石店铺开始
            {
                if ($new == 4) { //钻石到免费
                    $credit_str1 = "credit_" . $team_level . "_4_5";
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit_str3 = "credit_" . $team_level . "_3_2";
                    $credit_str4 = "credit_" . $team_level . "_2_1";
                    $credit = -($this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3 + $this->$credit_str4);
                } elseif ($new == 5) {//钻石到铜级
                    $credit_str2 = "credit_" . $team_level . "_5_3";
                    $credit_str1 = "credit_" . $team_level . "_3_2";
                    $credit_str3 = "credit_" . $team_level . "_2_1";
                    $credit = -($this->$credit_str1 + $this->$credit_str2 + $this->$credit_str3);
                } elseif ($new == 3) { //钻石到银级
                    $credit_str1 = "credit_" . $team_level . "_3_2";
                    $credit_str2 = "credit_" . $team_level . "_2_1";

                    $credit = -($this->$credit_str1 + $this->$credit_str2);
                } elseif ($new == 2) { //钻石到白金
                    $credit_str2 = "credit_" . $team_level . "_2_1";
                    $credit = -($this->$credit_str2);
                } elseif ($new == 1) { //钻石到钻石
                    $credit = 0;
                }
            }
                break;
            default: {
                $credit = 0;
            }

        }
        return $credit;

    }


}