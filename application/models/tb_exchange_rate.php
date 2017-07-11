<?php
/**
 * Created by PhpStorm.
 * User: tico
 * Date: 2017/4/25
 * Time: 11:15
 */
class tb_exchange_rate extends MY_Model
{
    protected $table_name = "exchange_rate";
    function __construct(){
        parent::__construct();
    }

    /**
     * @添加汇率log
     * @author andy
     * @date 20170620
     */
    public function addRateHistory(){

        $history = $this->db->from('exchange_rate')->get()->result_array();

        $this->db->insert_batch('exchange_rate_history',$history);

    }

}