<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2016/11/24
 * Time: 15:33
 */
class M_base extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查询
     * @param array $params
     * @return array
     */
    public function get(array $params, $get_rows = false,$get_one=false)
    {
        $this->db->from($this->table);
        if (isset($params['select'])) {
            if (isset($params['select_escape'])){
                $this->db->select($params['select'], false);
            }else{
                $this->db->select($params['select']);
            }
        }
        if (isset($params['where']) && is_array($params['where'])) {
            $this->db->where($params['where']);
        }

        if (isset($params['join'])){
            foreach ($params['join'] as $item){
                $this->db->join($item['table'], $item['where'], $item['type']);
            }
        }

        if (isset($params['limit'])) {
            $this->db->limit($params['limit']);
        }


        if (isset($params['group'])) {
            $this->db->group_by($params['group']);
        }
        if (isset($params['order'])) {
            $this->db->order_by($params['order']);
        }
        $result = $this->db->get();
        if (!$get_one) {
            return $get_rows ? $result->num_rows() : ($result->num_rows() > 0 ? $result->result_array() : array());
        } else {
            return $get_rows ? $result->num_rows() : ($result->num_rows() > 0 ? $result->row_array() : array());
        }
    }
}