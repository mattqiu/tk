<?php

class M_country extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getCountry() {
        $res = $this->db->select('region_name')->from('country')->get()->result_array();
        return $res;
    }

}
