<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/27
 * Time: 10:17
 */
class tb_grant_pre_sales_executive_bonus extends MY_Model
{

    protected $table = "grant_pre_sales_executive_bonus";

    public function __construct()
    {
        parent::__construct();
    }

    public function getList(){
        $this->from($this->table);
        return $this->select("uid,amount")->get()->result_array();
    }
}