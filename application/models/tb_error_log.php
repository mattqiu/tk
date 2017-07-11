<?php

/**
 * User: Able
 * Date: 2017/3/23
 * Time: 16:06
 */
class tb_error_log extends MY_Model
{
    protected $table = "error_log";

    public function __construct()
    {
        parent::__construct();
    }

    public function add_error_log($data){
        $this->db->insert($this->table,$data);
    }
}