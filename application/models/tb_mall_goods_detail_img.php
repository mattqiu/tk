<?php
/** 
 * 商品图片详情数据访问层
 * @date: 2016-5-20 
 * @author: sky yuan
 * @parameter: 
 * @return: 
 */ 
class tb_mall_goods_detail_img extends MY_Model {

    protected $table_name = "mall_goods_detail_img";
    function __construct() {
        parent::__construct();
    }

    /**
     * 获取商品详情图片
     * @date: 2016-5-20
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    function get_goods_detail_img($language_id,$goods_sn_main) {
        return $this->db->where('language_id',$language_id)->where('goods_sn_main',$goods_sn_main)->order_by('img_id','asc')->get('mall_goods_detail_img')->result_array();
    }
}