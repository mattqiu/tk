<?php
/**
 * @author Terry
 */
class tb_profit_stat extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 根据日期获取利润
     * @param $profit_date  2016 或 201607 或 20160703
     * @return 利润 int（单位：分）
     * @author Terry
     */
    public function getProfitByDate($profit_date){

        $res = $this->db->select('profit')->from('profit_stat')->where('profit_date',$profit_date)->get()->row_array();
        return $res?$res['profit']:0;
    }

    /**
     * 根据日期记录利润
     * @param $profit_date  2016 或 201607 或 20160703
     * @param $profit int  利润（单位：分）
     * @return boolean
     * @author Terry
     */
    public function recordProfitByDate($profit_date,$profit){

        $res = $this->db->insert('profit_stat',array(
            'profit_date'=>$profit_date,
            'profit'=>$profit
        ));

        return $res?true:false;
    }
    
}
