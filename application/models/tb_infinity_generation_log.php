<?php
/**
 * @author Terry
 */
class tb_infinity_generation_log extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查某用户是否在当月杰出店铺分红奖里面。
     */
    public function checkIfInCurMonthChairmanList($uid){

        $res = $this->db->from('infinity_generation_log')->select('uid')->where('uid',$uid)->where('qualified_time',date('Y-m'))->get()->row_array();
        return $res?true:false;
    }
    
}
