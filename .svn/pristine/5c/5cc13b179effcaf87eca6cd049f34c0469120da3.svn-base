<?php

class tb_mall_goods_effect extends MY_Model {
    protected $table_name = "mall_goods_effect";
    function __construct() {
        parent::__construct();
    }

    /* 获取风格列表  */
    public function getEffectList() {
        $language_id=intval($this->session->userdata('language_id'));

        return $this->db->where('language_id',$language_id)->get('mall_goods_effect')->result_array();
    }
}