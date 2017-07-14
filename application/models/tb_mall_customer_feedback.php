<?php

/**
 * 客户意见反馈
 */
class tb_mall_customer_feedback extends MY_Model {
    protected $table_name = "mall_customer_feedback";
    function __construct() {
        parent::__construct();
    }

    /* 获取风格列表  */
    public function getEffectList() {
        $language_id=intval($this->session->userdata('language_id'));

        return $this->db->where('language_id',$language_id)->get('mall_customer_feedback')->result_array();
    }


    /**
     * 添加 客户意见反馈
     */
    public function add($data){
    	$this->db->insert('mall_customer_feedback', $data);
        return $this->db->insert_id();
    }

    public function get_one(){

    }

    public function get_list(){

    }


}