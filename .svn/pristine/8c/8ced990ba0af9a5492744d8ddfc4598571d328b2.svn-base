<?php
/**
 *　业务逻辑层-商城首页
 * @date: 2016-5-11
 * @author: sky yuan
 */
class o_index extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /** 
     * 根据位置获取ads
     * @date: 2016-5-11 
     * @author: sky yuan
     * @parameter: $loc/区域code
     * @return: 
     */ 
    function get_index_ads($loc='index',$lang=true,$limit=10) {
        $this->load->model('tb_mall_ads');
        return $this->tb_mall_ads->get_index_ads($loc,$lang,$limit);
    }
    
    /** 
     * 获取推荐商品
     * @date: 2016-5-11 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function get_recommend_goods($where,$limit,$order,$language_id=1,$location_id=840) {
        $this->load->model('tb_mall_goods_main');
        return $this->tb_mall_goods_main->get_recommend_goods($where,$limit,$order,$language_id,$location_id);
    }

    /* 根据大类id获取最热的4个推荐产品  */
    function get_recomment_goods_by_cateid($children, $limit = 4, $order = []) { 
        $this->load->model('tb_mall_goods_main');
        return $this->tb_mall_goods_main->get_recomment_goods_by_cateid($children, $limit, $order);
    }
    
    /**
     * 获取一级分类
     * @date: 2016-5-17
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    public function get_top_category($language_id=''){
        $this->load->model('tb_mall_goods_category');
        
        return $this->tb_mall_goods_category->get_top_category($language_id);
    }
    
    /** 
     * 获取商品浏览历史
     * @date: 2016-5-17 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    public function get_history_list($goods_his='') {
        $this->load->model('tb_mall_goods_main');
    
        $history_goods=array();
        if(!empty($goods_his)) {
            $history_goods=$this->tb_mall_goods_main->get_history_goods(trim($goods_his,','));
        }
    
        return $history_goods;
    }
    
    /** 
     * 获取首页新闻列表5条最新
     * @date: 2016-5-17 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    public function get_notice_list($lan_id,$type_id) {
        $this->load->model('tb_news');
        
        return $this->tb_news->get_notice_list($lan_id,$type_id);
    }
    
    /**
     * 获取详情页面信息
     * @date: 2016-5-20
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    public function get_goods_detail($language_id,$goods_sn_main,$goods_sn,$loc_id=840,$user_id='') {

        $this->load->model('tb_mall_goods_main');
        $this->load->model('tb_mall_goods_main_detail');
        $this->load->model('tb_mall_goods');
        $this->load->model('tb_mall_goods_gallery');
        $this->load->model('tb_mall_goods_detail_img'); 
        $this->load->model('tb_mall_wish');
        
        $this->load->model('m_goods');
        
        $goods_main=array();          
        
        $goods_main=$this->tb_mall_goods_main->get_goods_main($language_id,$goods_sn_main);
        $goods_id=$goods_main['goods_id'];
        
        $goods_main['is_attention']=0;
        if($user_id) {
            if($this->tb_mall_wish->check_user_is_attention_goods($user_id,$goods_id)) {
                $goods_main['is_attention']=1;
            }
        }
        
        if(empty($goods_sn)) {
            $goods_sn=$goods_main['goods_sn_main'].'-1';
        }
      
        //$rs[$k]['left_time']=0;
        $goods_main['price_off'] = 0;
        /*商品在促销期 */
        if ($goods_main['is_promote'] == 1){
            $this->load->model("tb_mall_goods_promote");
            $promote = $this->tb_mall_goods_promote->get_goods_promote_info($goods_main['goods_sn_main'],date('Y-m-d H:i:s'),$goods_sn);
            if($promote){
                $this->load->model('o_mall_goods_main');
                $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price_main'], $goods_main, $language_id);
               
                $goods_main['shop_price'] = $promote_info['shop_price'];
                $goods_main['price_off'] = $promote_info['price_off'];

            }
        }
        $goods_main['shop_price'] = number_format($goods_main['shop_price'],2);
        
        $this->m_goods->add_goods_click($goods_id); //增加点击量
        
        if(!empty($goods_main)) {
            $this->load->model("tb_mall_goods_main");
            $goods_main['relation_goods']=$this->tb_mall_goods_main->get_recommend_goods(" cate_id = {$goods_main['cate_id']}",10,' sort_order asc',$language_id,$loc_id);
       
            //获取商品扩展信息
            $goods_main['extends']=$this->tb_mall_goods_main_detail->get_goods_extends($goods_main['goods_id']);
        
            //获取商品子sku信息
            $goods_sub=$this->tb_mall_goods->get_goods_info($goods_main['goods_sn_main'],$language_id);

            $attr_arr=$color_arr=$size_arr=$other_arr=array();
            foreach($goods_sub as $k=>$v){
        
                $attr_arr[$k]['sn']=$v['goods_sn'];
                $attr_arr[$k]['color']=$v['color'];
                $attr_arr[$k]['size']=$v['size'];
                $attr_arr[$k]['other']=$v['customer'];
                $attr_arr[$k]['goods_number']=$v['goods_number'];
                $attr_arr[$k]['price']=$v['price'];
        
                if(!empty($v['color']) && !in_array($v['color'], $color_arr)) {
                    //$tmp_row=$this->tb_mall_goods_gallery->get_goods_gallery($v['goods_sn']);
                    //$color_arr[$v['color']]=isset($tmp_row['thumb_img'])?$tmp_row['thumb_img']:'';
                    $color_arr[]=$v['color'];
                }
        
                if(!empty($v['size']) && !in_array($v['size'],$size_arr)) {
                    $size_arr[]=$v['size'];
                }
        
                if(!empty($v['customer']) && !in_array($v['customer'],$other_arr)) {
                    $other_arr[]=$v['customer'];
                }
        
                if($v['goods_sn'] == $goods_sn) {
                    $goods_main['goods_number']=$v['goods_number'];
        
                    if($v['price'] > 0 && $goods_main['price_off'] == 0) {
                        $goods_main['shop_price']=$v['price']; //优先选择子sku的私有价格
                    }
        
                    $goods_main['color']=$v['color'];
                    $goods_main['size']=$v['size'];
                    $goods_main['other']=$v['customer'];
                }
            }
            sort($size_arr);
            $goods_main['sn_list']=json_encode($attr_arr);
            $goods_main['sn_list_arr']=$attr_arr;
            $goods_main['color_list']=$color_arr;
            $goods_main['size_list']=$size_arr;
            $goods_main['other_list']=$other_arr;
        
            //获取商品相册图
            $goods_main['img_list']=$this->tb_mall_goods_gallery->get_goods_gallery_list($goods_sn);
        
            //获取商品详细图
            $goods_main['detail_img_list']=$this->tb_mall_goods_detail_img->get_goods_detail_img($language_id,$goods_main['goods_sn_main']);
        }
        
        return $goods_main; 
        
    }
    
}