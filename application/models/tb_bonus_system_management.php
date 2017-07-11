<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/5/22
 * Time: 17:00
 */
class tb_bonus_system_management extends MY_Model
{

    protected $table = "bonus_system_management";

    public function __construct()
    {
        parent::__construct();
    }


    public function getList($filter,$perPage){
        $this->db->from($this->table);
        $this->db->where('lang',$filter['language_id'])->order_by("sort", "desc")->order_by("id", "desc");
        return $this->db->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }


    public function getTotal($filter){
       $this->db->from($this->table);
       $this->db->where('lang',$filter['language_id']);
       return $this->db->count_all_results();
    }


    public function addBonusSystem($data){
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }

    public function getByIdBonus($id){
        $this->db->from($this->table);
        $this->db->where('id',$id);
        return $this->db->get()->row_array();
    }

    public function updateBouns($where,$data){
        $this->db->where('id',$where);
        $this->db->update($this->table,$data);
        return $this->db->affected_rows();
    }

    public function getTitle(){
        $this->db->from($this->table);
        $this->db->select("id,title");
        return $this->db->get()->result_array();
    }


    public function getSort(){
        $result = $this->db->query("select max(sort) as sort from bonus_system_management limit 1")->row_array();
        return $result['sort'];
    }
}