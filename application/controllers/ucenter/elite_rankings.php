<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class elite_rankings extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->_viewData['title'] = lang('elite_rankings');
        $this->load->Model('m_elite_rankings');

        $personal_commission=$this->m_elite_rankings->Ranking_2x5();
        $this->_viewData['personal_commission']=$personal_commission;

        $company_commission=$this->m_elite_rankings->Ranking_138();
        $this->_viewData['company_commission']=$company_commission;

        $team_commission=$this->m_elite_rankings->Ranking_generationSales();
        $this->_viewData['team_commission']=$team_commission;

        $infinite_commission=$this->m_elite_rankings->Ranking_infinityGeneration();
        $this->_viewData['infinite_commission']=$infinite_commission;

        $amount_store_commission=$this->m_elite_rankings->Ranking_personalSales();
        $this->_viewData['amount_store_commission']=$amount_store_commission;

        $amount_profit_sharing_comm=$this->m_elite_rankings->Ranking_weeklyProfit();
        $this->_viewData['amount_profit_sharing_comm']=$amount_profit_sharing_comm;

        $amount_weekly_Leader_comm=$this->m_elite_rankings->Ranking_weeklyCheckMatching();
        $this->_viewData['amount_weekly_Leader_comm']=$amount_weekly_Leader_comm;

        $amount_monthly_leader_comm=$this->m_elite_rankings->Ranking_monthlyTopPerformers();
        $this->_viewData['amount_monthly_leader_comm']=$amount_monthly_leader_comm;
        parent::index();
    }

}