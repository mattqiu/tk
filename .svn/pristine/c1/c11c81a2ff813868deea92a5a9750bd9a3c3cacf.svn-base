<?php
/**
 * sql语句封装类
 * @author terry
 */
class o_sql_filter extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function addSqlFilter($filter){

        foreach ($filter as $k => $v) {
            if ($v == '') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', ($v));
                    break;
                case 'idEmail':
                    $this->db->where('user_id', $v);
                    break;
                case 'page':
                    $this->db->limit(10, ($filter['page'] - 1) * 10);
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

}
