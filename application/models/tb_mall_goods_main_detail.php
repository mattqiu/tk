<?php
/**
 *　商品主表扩展数据访问层
 * @date: 2016-5-11
 * @author: sky yuan
 */
class tb_mall_goods_main_detail extends MY_Model {

    protected $table_name = "mall_goods_main_detail";
    function __construct() {
        parent::__construct();
    }

    /** 
     * 获取商品扩展信息
     * @date: 2016-5-20 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function get_goods_extends($goods_main_id) {
        return $this->db->where('goods_main_id',$goods_main_id)->get('mall_goods_main_detail')->row_array();
    }
}