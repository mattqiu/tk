<?php

class tb_mall_goods_attribute extends MY_Model {

    protected $table_name = "mall_goods_attribute";
    function __construct() {
        parent::__construct();
    }

    /* 获取颜色尺码等属性 */
    public function get_goods_attr($attr_name,$where_language=true) {
        $language_id=intval($this->session->userdata('language_id'));
        if($where_language) {
            $this->db->where('language_id',$language_id);
        }
        return $this->db->where('attr_name',$attr_name)->order_by('attr_values')
            ->get('mall_goods_attribute')->result_array();
    }
}