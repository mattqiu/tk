<?php
/**
 *　商品分类表数据访问层
 * @date: 2016-5-17
 * @author: sky yuan
 */
class tb_mall_goods_category extends MY_Model {

    protected $table_name = "mall_goods_category";
    function __construct() {
        parent::__construct();
    }
    
    /** 
     * 获取一级分类
     * @date: 2016-5-17 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    public function get_top_category($language_id='',$parent_id=0){
//        $this->db->where('status',1);
        $where['status'] = 1;
        if(!empty($language_id)) {
//            $this->db->where('language_id',$language_id);
            $where['language_id'] = $language_id;
        }
//        $rs=$this->db->where('parent_id',0)->order_by('sort_order')->get('mall_goods_category')->result_array();
        $where['parent_id'] = $parent_id;
        $rs = $this->get_list("*",$where,[],1000,0,['sort_order'=>'asc']);
        return $rs;
    }


}
