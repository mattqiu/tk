<?php
/** 
 * 商品图片相册数据访问层
 * @date: 2016-5-20 
 * @author: sky yuan
 * @parameter: 
 * @return: 
 */ 
class tb_mall_goods_gallery extends MY_Model {

    protected $table_name = "mall_goods_gallery";
    function __construct() {
        parent::__construct();
    }
    
    /** 
     *　获取商品属性图片信息
     * @date: 2016-5-20 
     * @author: sky yuan
     */
    function get_goods_gallery($goods_sn) {
        return $this->db->select('thumb_img')->where('goods_sn',$goods_sn)->get('mall_goods_gallery')->row_array();
    }
    
    /** 
     * 获取商品相册列表
     * @date: 2016-5-20 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function get_goods_gallery_list($goods_sn) {
        return $this->db->where('goods_sn',$goods_sn)->get('mall_goods_gallery')->result_array();
    }
    
}