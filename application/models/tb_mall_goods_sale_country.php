<?php

class tb_mall_goods_sale_country extends MY_Model {

    protected $table_name = "mall_goods_sale_country";
    function __construct() {
        parent::__construct();
    }

    /* 获取销售国家列表  */
    function get_sale_country($country_id=false) {
        $where = [];
        if(false !== $country_id) {
//            $this->db->where('country_id',$country_id);
            $where['country_id'] = $country_id;
//            var_dump($where);echo(__FILE__.",".__LINE__."<BR>");
        }
//        var_dump($where);echo("<br><br><br><br>".__FILE__.",".__LINE__);
//        return $this->db->order_by('country_id','desc')->get('mall_goods_sale_country')->result_array();
        return $this->get_list("*",$where,[],5000,0,["country_id"=>"desc"]);
    }

    /* 國家ID是否存在 */
    function is_exist_country($country_id){
//        return $this->db->from('mall_goods_sale_country')->where('country_id',$country_id)->count_all_results();
        return $this->get_counts(['country_id'=>$country_id]);
    }

}