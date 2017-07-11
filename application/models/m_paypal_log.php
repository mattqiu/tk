<?php

class m_paypal_log extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //绑定paypal
    public function add_payapl($uid, $order) {
        $this->db->insert('paypal_log', array('uid' => $uid, 'paypal_email' => $order, 'time' => date('Y-m-d h:i:s', time())));
    }

    //解绑paypal
    public function del_payapl($uid, $order) {
        $this->db->where('uid', $uid);
        $this->db->where('paypal_email', $order);
        $this->db->delete('paypal_log');
    }

    //查询paypal
    public function get_paypal($uid) {
        $this->db->from('paypal_log');
        $res = $this->db->where('uid', $uid)->get()->row_array();
        return $res;
    }

}
