<?php

class m_elite_rankings extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 2x5 排行榜
     */
    public function Ranking_2x5() {
        $sql="select id,personal_commission from users where parent_id<>0 order by personal_commission desc limit 10";
        $personal_commission=$this->db->query($sql)->result();
        return $personal_commission;
    }

    /**
     * 138 排行榜
     */
    public function Ranking_138(){
        $sql="select id,company_commission from users where parent_id<>0 order by company_commission desc limit 10";
        $company_commission=$this->db->query($sql)->result();
        return $company_commission;
    }


    /**
     * 团队销售奖金 排行榜
     */
    public function Ranking_generationSales(){
        $sql="select id,team_commission from users where parent_id<>0 order by team_commission desc limit 10";
        $team_commission=$this->db->query($sql)->result();
        return $team_commission;
    }

    /**
     * 每月团队销售业绩无限代奖 排行榜
     */
    public function Ranking_infinityGeneration (){
        $sql="select id,infinite_commission from users where parent_id<>0 order by infinite_commission desc limit 10";
        $infinite_commission=$this->db->query($sql)->result();
        return $infinite_commission;
    }


    /**
     * 个人销售奖 排行榜
     */
    public function Ranking_personalSales (){
        $sql="select id,amount_store_commission from users where parent_id<>0 order by amount_store_commission desc limit 10";
        $amount_store_commission=$this->db->query($sql)->result();
        return $amount_store_commission;
    }

    /**
     * 周分红 排行榜
     */
    public function Ranking_weeklyProfit (){
        $sql="select id,amount_profit_sharing_comm from users where parent_id<>0 order by amount_profit_sharing_comm desc limit 10";
        $amount_profit_sharing_comm=$this->db->query($sql)->result();
        return $amount_profit_sharing_comm;
    }


    /**
     * 周领导对等奖 排行榜
     */
    public function Ranking_weeklyCheckMatching (){
        $sql="select id,amount_weekly_Leader_comm from users where parent_id<>0 order by amount_weekly_Leader_comm desc limit 10";
        $amount_weekly_Leader_comm=$this->db->query($sql)->result();
        return $amount_weekly_Leader_comm;
    }


    /**
     * 月领导分红奖 排行榜
     */
    public function Ranking_monthlyTopPerformers (){
        $sql="select id,amount_monthly_leader_comm from users where parent_id<>0 order by amount_monthly_leader_comm desc limit 10";
        $amount_monthly_leader_comm=$this->db->query($sql)->result();
        return $amount_monthly_leader_comm;
    }

    /***分店排行榜***/
    /**
     * @ m by brady.wang
     * @desc 加redis缓存
     * @return mixed
     */
    public function store_ranking(){
        $redis_key = config_item("redis_key")['leadership_bulletin'];
        if (REDIS_STOP == 0) {

            $res = $this->redis_get($redis_key);
            $result = json_decode($res);
            if ($res == false || !is_array(json_decode($res,true))) {
                $result = $this->store_ranking_db();
            }

        } else {
            $result = $this->store_ranking_db();
        }


        return $result;

    }

    /**
     * @author brady
     * @desc 精英排行榜 db
     */
    public function store_ranking_db()
    {
        $redis_key = config_item("redis_key")['leadership_bulletin'];
        $date = date("Ym",time());
        $pay_count="(member_silver_num+member_bronze_num+member_platinum_num+member_diamond_num)";
        $total_count="(member_free_num+member_bronze_num+member_silver_num+member_platinum_num+member_diamond_num)";
        $sql="select a.year_month,a.uid,u.name,$pay_count as pay_total, $total_count as total_count from stat_intr_mem_month a,users u where a.year_month='$date' and u.id=a.uid and u.id<>'1380100217' order by pay_total desc LIMIT 0,25";
        $result=$this->db_slave->query($sql)->result();
        $this->redis_set($redis_key,json_encode($result),config_item("redis_expire")['leadership_bulletin']);
        return $result;
    }

    /***订单排行榜***/
    public function order_ranking(){

    }


}
