<?php
/**
 * @author Terry
 */
class tb_user_suite_exchange_coupon extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 代品卷
     * @param $uid  用户ID
     * @return 代品卷总金额
     */
    public function get_total_money($uid,$status=0){
        $return = array();
        $res = $this->db->query("select face_value,count(id) num from user_suite_exchange_coupon a where a.uid=$uid and a.status=$status group by face_value order by face_value desc")->result_array();
        foreach($res as $item){
            $return['m'.$item['face_value']] = $item['num'];
        }
        return $return;
    }
}
