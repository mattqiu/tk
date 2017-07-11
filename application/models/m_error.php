<?php

class M_error extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function createError404($data) {
       $this->db->insert('error_404',$data);
       return $this->db->affected_rows();
    }

}
