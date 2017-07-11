<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *　商城首页
 * @date: 2015-7-20
 * @author: sky yuan
 */
class Index extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    /* 首页 */
    public function index()
    { 
        parent::__getMallAds($this->_viewData['curLocation_id']);

        /* 头部信息 */
  		$this->_viewData['title']=lang('m_title').' - '.lang('site_name');
  		$this->_viewData['keywords']=lang('m_keywords');
  		$this->_viewData['description']=lang('m_description');
  		$this->_viewData['canonical']=base_url();
        $redis_title = $this->config->item('tps138mallgoods');
        $language_id = $this->_viewData['curLan_id'];
        $location_id = $this->_viewData['curLocation_id'];


        /* 广告 */
        /*
        $this->load->model("tb_mall_ads");
        $redis_key = $redis_title.':index:index:banner_ads:location_id:index_'.$location_id;
        $this->_viewData['banner_ads'] = $this->tb_mall_ads->get_index_ads('index_'.$this->_viewData['curLocation_id'],false, 10, $redis_key);
        */

        /* 热卖推荐  */
        //$this->_viewData['best_goods'] =$this->m_goods->get_recommend_goods('is_hot=1 and is_home=1',20,'last_update desc,add_time desc');
        /* 新品上架 */
        $this->load->model('tb_mall_goods_main');

        $recommend_order = [];
        $recommend_order['last_update'] = "desc";
        $recommend_order['add_time'] = "desc";
        $recommend_where = [];
        $recommend_where['is_new']=1;
        $recommend_where['is_home']=1;
        //$redis_key = $redis_title.':index:index:new_goods:location_id:'.$location_id;
        $this->_viewData['new_goods'] =$this->tb_mall_goods_main->get_recommend_goods($recommend_where,75,$recommend_order,$language_id, $location_id);

        /* 特惠专区 */
       // $redis_key = $redis_title.':index:index:promote_goods:location_id:'.$location_id;
        $index_data['promote_goods'] =$this->tb_mall_goods_main->get_recommend_goods(['is_promote' => 1,'is_home'=>1], 75, $recommend_order,$language_id, $location_id); //50个改75个



        /* 热卖推荐 */
        $recommend_where = [];
        $recommend_where['is_hot']=1;
        $recommend_where['is_home']=1;
        //$redis_key = $redis_title.':index:index:hot_goods:location_id:'.$location_id;
        $this->_viewData['hot_goods'] =$this->tb_mall_goods_main->get_recommend_goods($recommend_where,12,$recommend_order, $language_id, $location_id);



        /* 推荐分类 */
        $level_goods_arr=array();
        $arr_cate_id=array();
        $floor_name =array();
        switch($this->_viewData['curLocation_id']) {
            case '840': //美国
  		        $arr_cate_id=$this->config->item('floor_layer')[840]['floor_cate'];
                $floor_name = $this->config->item('floor_layer')[840]['floor_name']; //快速楼层 leon 添加
                break;
            case '156': //中国
                $arr_cate_id=$this->config->item('floor_layer')[156]['floor_cate'];
                $floor_name = $this->config->item('floor_layer')[156]['floor_name']; //快速楼层
                break;
            case '344': //香港
                $arr_cate_id=$this->config->item('floor_layer')[344]['floor_cate'];
                $floor_name = $this->config->item('floor_layer')[344]['floor_name']; //快速楼层
                break;
            case '410': //韩国
	            $arr_cate_id=$this->config->item('floor_layer')[410]['floor_cate'];
                $floor_name = $this->config->item('floor_layer')[410]['floor_name']; //快速楼层 leon 添加
                break;
        }



        if(!empty($arr_cate_id)) {
            foreach($arr_cate_id as $k=>$v) {
                $children = getChildArr($this->_viewData['category_all'],$k);
                $children=$children.$k; //选中类下所有子类

                //$redis_key = $redis_title.':index:index:goods_list:location_id:'.$location_id . ':cate_id:'.$k;
                $level_goods_arr[$k]['cate']=$v;
                $level_goods_arr[$k]['goods_list']=$this->tb_mall_goods_main->get_recomment_goods_by_cateid($children, 15, ['last_update' => 'DESC'],$language_id, $location_id);

                 if ('156' != $this->_viewData['curLocation_id']) {
                    //$redis_key = $redis_title.':index:index:goods_list_hot:location_id:'.$location_id . ':cate_id:'.$k;
                    $level_goods_arr[$k]['goods_list_hot'] = $this->tb_mall_goods_main->get_recomment_goods_by_cateid($children, 9, ['comment_count' =>  'DESC'],$language_id, $location_id);
                }

               /* $redis_key = $redis_title.':index:index:ads_list:ads_id:'.$k;
                $level_goods_arr[$k]['ads_list']=$this->tb_mall_ads->get_index_ads($k, true, 10, $redis_key);*/
            }

                //$level_goods_arr[$k]['ads_list']=$this->tb_mall_ads->get_index_ads($k);
        }

        $this->_viewData['level_goods']=$level_goods_arr;
        $this->_viewData['level_goods_floor']=$floor_name;

        /* 获取history */
        $this->_viewData['history_goods'] =$this->m_goods->get_history_list();

        parent::index(THEME_NAME.'/','index');

    }

    /* 产品详情  */
    public function product()
    {
        $product_data = $this->_viewData;

        $product_data['goods_sn_main']=$goods_sn_main=trim($this->input->get('snm'));
        $goods_sn=trim($this->input->get('sn')); //子sku

        $one_goods_main = $this->db->select('is_doba_goods, last_update,doba_product_id, goods_sn_main,doba_item_id ')->where(["goods_sn_main"=>$goods_sn_main])->get('mall_goods_main')->row_array();
           //doba商品，更新库存，以确保订单的有效性,最多4小时更新一次
        if($one_goods_main['is_doba_goods'] && time() - $one_goods_main['last_update'] > 14400) {
            $this->m_goods->modify_doba_inventory($one_goods_main['doba_product_id'],$one_goods_main['doba_item_id'],$one_goods_main['goods_sn_main']);
        }

        //是否审核中的产品预览
        $is_prev_view=intval($this->input->get('is_view'));
        $this->load->model("m_goods");

        $goods_sn = empty($goods_sn) ? $this->m_goods->get_valid_sub_sku($goods_sn_main) : $goods_sn;

        $goods_info=$this->m_goods->get_goods_info($goods_sn_main,$goods_sn,$is_prev_view);

        $goods_id=$goods_info['goods_id'];

        if(empty($goods_id)) {
            header("Location: " . base_url('error_404'));
            exit;
        }

        $goods_info['old_shop_price'] = $goods_info['shop_price'];
        //doba商品，更新库存，以确保订单的有效性,最多4小时更新一次
       /* if($goods_info['is_doba_goods'] && time() - $goods_info['last_update'] > 14400) {
            $this->m_goods->modify_doba_inventory($goods_info['doba_product_id'],$goods_info['doba_item_id'],$goods_info['goods_sn_main']);
        }*/

        $goods_info['left_time']=0;
        $goods_info['price_off'] = 0;

        //获取发货地址--只在中国区域显示
        $addrName = '';
        if($product_data['curLocation_id'] == 156) {
            $this->load->model('tb_mall_supplier');
            $addrCode = $this->tb_mall_supplier->get_shipping_address($goods_info['shipper_id']);
            if (!empty($addrCode)) {
                $this->load->model('tb_trade_addr_linkage');
                if ($addrCode['addr_lv3'] != 0) {
                    $city_name_arr = $this->tb_trade_addr_linkage->get_one("name",
                        ["code"=>$addrCode['addr_lv3'],"country_code"=>156]);
//                    var_dump($city_name_arr);exit(__FILE__.",".__LINE__."<BR>");
                    //$this->db->select('name')->where('code', $addrCode['addr_lv3'])->where('country_code', 156)->get('trade_addr_linkage')->row_array();
                }
                $addrName = isset($city_name_arr['name']) ? $city_name_arr['name'] : '';

                //直辖市的直接显示市 上海 北京 重庆 天津
                if(in_array($addrCode['addr_lv2'],array(11,12,31,50))){
                    $province_name_arr = $this->tb_trade_addr_linkage->get_one("name",
                        ["code"=>$addrCode['addr_lv2'],"country_code"=>156]);
                    //$this->db->select('name')->where('code', $addrCode['addr_lv2'])->where('country_code', 156)->get('trade_addr_linkage')->row_array();
                    $addrName = isset($province_name_arr['name']) ? $province_name_arr['name'] : '';
                }
            }
        }
        $product_data['addrName'] = $addrName;
        if ($goods_info !== false) {
            $time= time();
            $day  = date('Y-m-d H:i:s');
            /*商品在促销期 */
            if ($goods_info['is_promote']){
                $this->load->model("tb_mall_goods_promote");
                $promote = $this->tb_mall_goods_promote->get_goods_promote_info($goods_info['goods_sn_main'],$day,$goods_sn);
                if($promote){
                    $goods_info['left_time']=strtotime($promote['end_time'])-$time;
                    $language_id=(int)$this->session->userdata('language_id');
                    $this->load->model("o_mall_goods_main");
                    $promote_info=$this->o_mall_goods_main->cal_price_off($promote["promote_price"], $goods_info, $language_id);

                    $goods_info['shop_price'] = $promote_info['shop_price'];
                    $goods_info['price_off'] = $promote_info['price_off'];
                }
            }
        }

        //检查是否是doba产品，实时更新库存和价格
        /* if($goods_info['is_doba_goods']) {
            $this->m_goods->modify_doba_inventory($goods_info['doba_product_id'],$goods_info['doba_item_id'],$goods_info['goods_sn_main']);
        } */

        //Baker 2017-6-26 点击量存在Redis
       /* $this->m_goods->add_goods_click($goods_id); //增加点击量*/
        $this->load->model('tb_mall_goods_main');
        $this->tb_mall_goods_main->add_goods_click($goods_sn_main);//增加点击量

        $product_data['goods_sn']= $goods_sn;

        /* 头部信息 */
        $product_data['title']=$goods_info['extends']['meta_title'].' - '.lang('site_name');
        $product_data['keywords']=$goods_info['extends']['meta_keywords'];
        $product_data['description']=$goods_info['extends']['meta_desc'];
        $product_data['canonical']=base_url().'index/product?snm='.$goods_info['goods_sn_main'];

        $product_data['goods_info']=$goods_info;

        /* 套餐详情 */
        if($goods_info['is_alone_sale'] == 2 && $goods_info['group_goods_id']) {
            $product_data['goods_group_info']=$this->m_goods->get_goods_group_info($goods_info['group_goods_id']);
        }

        /* 面包屑导航  */
        $product_data['nav_title']= $this->m_goods->get_nav_title( $product_data['category_all'],$goods_info['cate_id']);

        /* 收藏数量 */
        $product_data['wish_goods_count']= $this->m_goods->get_wish_goods_count($goods_info['goods_id']);

        /* 热销推荐 二级分类下商品  */
        $cate_id = $goods_info['cate_id'];
        foreach ($product_data['category_all'] as $v) {
            if($cate_id == $v['cate_id']) {
                $cate_id = $v['parent_id'];
            }
        }

        $children = getChildArr($product_data['category_all'],$cate_id);
        $children=$children.$cate_id; //选中类下所有子类,包含自己
        if(empty($children))
        {
            $children=$cate_id; //3级类
        }

        $this->load->model('o_index');
        $product_data['recomment_goods'] =$this->o_index->get_recommend_goods(["cate_id IN($children)"=>null],10,['sort_order'=>'asc']);

        /* 计算运费  */
//  		if($goods_info['is_alone_sale']=1 && isset($this->_userInfo['id']) && !empty($this->_userInfo['id'])) {
//  			$fee = $this->m_trade->get_product_shipping_fee(
//				$this->_userInfo['id'],
//				$goods_sn_main,
//				$product_data['curCur'],
//				$product_data['currency_all'],
//				$product_data['curLocation_id']
//			);
//
//  		   $product_data['ship_fee'] = $fee == false ? '10.00' : $fee;
//  		}else {
//  		   $product_data['ship_fee'] = '10.00';
//  		}

        /* 赠品  */
        $product_data['gift_list']=$this->m_goods->get_gift_list_in_goods($goods_info['gift_skus']);


        /* 保存浏览历史  */
        $goods_his=get_cookie('goods_history');
        $goods_his_arr=explode(',',$goods_his);
        $goods_his_arr=array_unique($goods_his_arr);

        if(!in_array($goods_id,$goods_his_arr)) {
            array_unshift($goods_his_arr, $goods_id);

            $goods_his_arr=array_slice($goods_his_arr,0,10);
        }
        $goods_his=implode(',', $goods_his_arr);
        set_cookie('goods_history', $goods_his, 86400 * 30,get_public_domain(),'/');

        /* 获取history */
        $product_data['history_goods'] =$this->m_goods->get_history_goods(trim($goods_his,','));

        /* 获取评论列表 */
        $product_data['goods_comments'] =$this->m_goods->get_goods_comments($goods_info['goods_sn_main']);

        /* 获取评论分组 */
        $product_data['goods_comments_star'] =$this->m_goods->get_goods_comments_star($goods_info['goods_sn_main']);

        //供应商QQ
        if ( '156' == $product_data['curLocation_id'] && $product_data['goods_info']['supplier_id']) {
            $this->load->model('tb_mall_supplier');
            $tb_mall_supplier = $this->tb_mall_supplier->get_one("supplier_qq", ['supplier_id' => $product_data['goods_info']['supplier_id']]);
            $product_data['supplier_qq'] = $tb_mall_supplier['supplier_qq'] ? explode(",", $tb_mall_supplier['supplier_qq']) : [];
            $product_data['supplier_qq'] = array_filter($product_data['supplier_qq']);
        }
  		$this->_viewData = $product_data;
        unset($product_data);

        parent::index(THEME_NAME.'/','product');

        return;
    }

    /* 产品分类  */
    public function category(){
        $this->load->model("tb_empty");

        $cate_sn=trim($this->input->get('sn'));        //一级分类信息

        $category_data = $this->_viewData;

        $cate_info=$this->m_goods->get_cate_info($cate_sn);

        /* seo权重集中  */
        $category_data['cate_url']=$category_data['canonical']=base_url().'index/category?sn='.$cate_sn;
        $cate_id=$cate_info['cate_id'];

        if(empty($cate_id)) {
            header("Location: " . base_url('error_404'));
            exit;
        }

        $searchData = $this->input->get()?$this->input->get():array();
        unset($searchData['sn']);
        //print_r($searchData);
        //排序
        $order='composite';

        if(isset($searchData['order'])) {
            $order=$searchData['order'];
        }else {
            $searchData['order'] = $order;
        }
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['language_id']=intval($this->session->userdata('language_id'));

        /* 获取分类下所有商品  */
        $this->load->library('pagination');

        $getChildArrKey = "index:category:childarr:".$cate_id;
        $children_str = $this->tb_empty->redis_get($getChildArrKey);
        if($children_str)
        {
            $children = $children_str;
        }else{
            $children = getChildArr($category_data['category_all'],$cate_id);
            $this->tb_empty->redis_set($getChildArrKey,$children,600);
        }

        $children=$children.$cate_id; //选中类下所有子类,包含自己
        if(empty($children))
        {
            $children=$cate_id; //3级类
        }
        $searchData['cate_id']=$children;

        /* 头部信息 */
        $category_data['title']=$cate_info['meta_title'].' - '.lang('site_name');
        $category_data['keywords']=$cate_info['meta_keywords'];
        $category_data['description']=$cate_info['meta_desc'];
        $category_data['cate_info']=$cate_info;

        /* 面包屑导航  */
        $category_data['nav_title']= $this->m_goods->get_nav_title( $category_data['category_all'],$cate_id);

        /* 价格  */
        $price_arr=array(
            array('0','99'),
            array('100','199'),
            array('200','299'),
            array('300','599'),
            array('600','999999')
        );

        $category_data['price_all']=$price_arr;

        /* 风格  */
        //$category_data['effect_all']=$this->m_goods->getEffectList();

        /* 颜色  */
        //$category_data['color_all']=$this->m_goods->get_goods_attr('color');

        /* 品牌  */
        $category_data['brand_all']=$this->m_goods->getBrandList($children);

        $category_data['order']=$order;
        $category_data['arr']=isset($searchData['arr']) ? ($searchData['arr'] == 'down' ? '' : 'down') : '';
        $category_data['brand_id']=isset($searchData['brand_id']) ? $searchData['brand_id'] : '';
        $category_data['effect_id']=isset($searchData['effect_id']) ? $searchData['effect_id'] : '';
        $category_data['price_pram']=isset($searchData['price']) ? $searchData['price'] : '';

        //leon 优化sql 原始内容
        //$category_data['goods']=$this->m_goods->get_goods_info_by_cateid($searchData,40);
        //leon 修改后的内容
        $category_data['goods']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'','','','1');

        $url = 'index/category?sn='.$cate_sn;

        $category_data['total_rows']=$config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData);

        //去掉不需要的分页参数
        unset($searchData['language_id'],$searchData['cate_id']);
        add_params_to_url($url, $searchData);
        $category_data['page_link']=$url;

        /* Pager */
        $config['per_page'] = 40;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $category_data['pager'] = $this->pagination->create_links();
        $category_data['cur_page']=$searchData['page'];
        $category_data['total_page']=ceil($config['total_rows'] / $config['per_page']);


        /* 获取history */
        $category_data['history_goods'] =$this->m_goods->get_history_list();

        $this->_viewData = $category_data;

        parent::index(THEME_NAME.'/','category');
    }

    /* 产品搜索  */
    public function search() {
        $this->load->helper('security');
        $keywords   = addslashes(xss_clean(strip_tags(trim($this->input->get('keywords')))));
        $keywords   = str_replace(['%', '_'], '', $keywords);
        $str_lenght = get_str_lenght_utf8($keywords);
        if($str_lenght > 200){
            $keywords=mb_substr($keywords,0,200,'utf8');
        }

        /* 头部信息 */
        $this->_viewData['title']=$keywords.' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/search?keywords='.$keywords;

        /* 面包屑导航  */
        $this->_viewData['nav_title']= $keywords;

        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();

        //排序
        $order='composite';

        if(isset($searchData['order'])) {
            $order=$searchData['order'];
        }else {
            $searchData['order'] = $order;
        }

        $this->_viewData['order']=$order;
        $this->_viewData['arr']=isset($searchData['arr']) ? ($searchData['arr'] == 'down' ? '' : 'down') : '';

        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        $searchData['language_id']=intval($this->session->userdata('language_id'));

        $searchData['keywords']=$keywords;


        //leon 优化sql 原始内容
        //$this->_viewData['goods']=$this->m_goods->get_goods_info_by_cateid($searchData,40);
        //leon 修改后的内容
        $this->_viewData['goods']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'','','','1');

        $url = 'index/search';

        $this->_viewData['total_rows']=$config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData);
        //去掉不需要的分页参数
        unset($searchData['language_id']);
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link']=$url;

        /* Pager */
        $config['per_page'] = 40;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['cur_page']=$searchData['page'];
        $this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);

        /* 获取history */
        $this->_viewData['history_goods'] = $this->m_goods->get_history_list();

        parent::index(THEME_NAME.'/','search');
    }

    




    /* 新品  */
    public function goods_new() {
        $cate_id=intval($this->input->get('cate_id'));
        $this->_viewData['cate_id'] = $cate_id;

        $date=trim($this->input->get('date'));
        $this->_viewData['date'] = $date;

        /* 头部信息 */
        $this->_viewData['title']= strip_tags(lang('label_nav_new')).' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/goods_new';

        //获取新品banner
       /* $this->load->model("tb_mall_ads");
        $this->_viewData['banner_ads'] = $this->tb_mall_ads->get_index_ads('new_'.$this->_viewData['curLocation_id'],false);*/

        //获取新品推荐产品
        //$this->_viewData['best_goods'] =$this->m_goods->get_recommend_goods('is_new=1 and is_best=1',20,'last_update desc,add_time desc');

        //获取半月内时间列表
        /* $date_list=array();
        for($i=0;$i<=15;$i++) {
            $date_list[] =date('Y-m-d',mktime(0,0,0,date('m'),date('d') - $i,date('Y')));
        }
        $this->_viewData['date_list'] = $date_list; */

        //获取新品列表
        $this->load->library('pagination');
        $page = intval($this->input->get('page'));

        $searchData = array();

        $searchData['page'] = max($page,1);

        if(!empty($cate_id)) {
            $children = getChildArr($this->_viewData['category_all'],$cate_id);
            $children=$children.$cate_id; //选中类下所有子类,包含自己
            if(empty($children))
            {
                $children=$cate_id; //3级类
            }

            if(!empty($children)) {
                $searchData['cate_id'] = $children;
            }
        }

        /* if(!empty($date)) {
            $searchData['date'] =$date; //选择了日期
        }else {
            $searchData['date_start'] =time() - 15 * 3600 * 24; //默认获取15天内的新品
        } */

        $searchData['language_id']=intval($this->session->userdata('language_id'));
        $searchData['is_new']= 1;

        //leon 优化sql原始内容
        //$this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'add_time desc');
        //leon 修改后的内容
        $this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'add_time desc','','','1');

        $url = 'index/goods_new';

        $this->_viewData['total_rows']=$config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData);
        //去掉不需要的分页参数
        unset($searchData['language_id']);
        $searchData['cate_id'] = $cate_id;
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link']=$url;

        /* Pager */
        $config['per_page'] = 40;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['cur_page']=$searchData['page'];
        $this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);

        parent::index(THEME_NAME.'/','new');
    }

    /* 热卖  */
    public function goods_hot() {
        $cate_id=intval($this->input->get('cate_id'));
        $this->_viewData['cate_id'] = $cate_id;

        /* 头部信息 */
        $this->_viewData['title']=strip_tags(lang('label_nav_hot')).' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/goods_hot';

        //获取热卖banner
       /* $this->load->model("tb_mall_ads");
        $this->_viewData['banner_ads'] = $this->tb_mall_ads->get_index_ads('hot_'.$this->_viewData['curLocation_id'],false);*/

        //获取热卖推荐产品
        //$this->_viewData['best_goods'] =$this->m_goods->get_recommend_goods('is_hot=1 and is_best=1',20,'last_update desc,add_time desc');

        //获取热卖产品列表
        $this->load->library('pagination');
        $page = intval($this->input->get('page'));

        $searchData = array();

        $searchData['page'] = max($page,1);

        if(!empty($cate_id)) {
            $children = getChildArr($this->_viewData['category_all'],$cate_id);
            $children=$children.$cate_id; //选中类下所有子类,包含自己
            if(empty($children))
            {
                $children=$cate_id; //3级类
            }

            if(!empty($children)) {
                $searchData['cate_id'] = $children;
            }
        }

        $searchData['language_id']=intval($this->session->userdata('language_id'));
        $searchData['is_hot']= 1;



        //leon 优化sql原始内容
        //$this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'is_best desc,last_update desc');
        //leon 修改后的内容
        $this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'is_best desc,last_update desc','','','1');




        $url = 'index/goods_hot';

        $this->_viewData['total_rows']=$config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData);
        //去掉不需要的分页参数
        unset($searchData['language_id']);
        $searchData['cate_id'] = $cate_id;
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link']=$url;

        /* Pager */
        $config['per_page'] = 40;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['cur_page']=$searchData['page'];
        $this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);

        parent::index(THEME_NAME.'/','hot');
    }

    /* 免邮  */
    public function goods_free_ship() {
        $redis_title = $this->config->item('tps138mallgoods');
        $language_id = $this->_viewData['curLan_id'];
        $location_id = $this->_viewData['curLocation_id'];
		$cache_time  = $this->config->item('mall_redis_time');

        $cate_id=intval($this->input->get('cate_id'));
        $this->_viewData['cate_id'] = $cate_id;

        /* 头部信息 */
        $this->_viewData['title']=strip_tags(lang('label_nav_free_ship')).' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/goods_free_ship';

        //获取免邮 banner
        $this->load->model("tb_mall_ads");
        $this->_viewData['banner_ads'] = $this->tb_mall_ads->get_index_ads('free_'.$location_id,false);

        //获取免邮 推荐产品
        //$this->_viewData['best_goods'] =$this->m_goods->get_recommend_goods('is_free_shipping=1 and is_best=1',20,'last_update desc,add_time desc');

        //获取免邮 产品列表
        $this->load->library('pagination');
        $page = intval($this->input->get('page'));

        $searchData = array();

        $searchData['page'] = max($page,1);

        if(!empty($cate_id)) {
            $children = getChildArr($this->_viewData['category_all'],$cate_id);
            $children=$children.$cate_id; //选中类下所有子类,包含自己
            if(empty($children))
            {
                $children=$cate_id; //3级类
            }

            if(!empty($children)) {
                $searchData['cate_id'] = $children;
            }
        }

        $searchData['language_id']      = $language_id;
        $searchData['is_free_shipping'] = 1;


        //leon 优化sql原始内容
        //$this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'last_update desc,add_time desc');
        //leon 修改之后的内容
        //$this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'last_update desc,add_time desc','','','1');

		
	/*	$redis_key  = $redis_title.':index:goods_free_ship';
		$redis_key .= ':location_id:'.$location_id;
		$redis_key .= ':cate_id:'.$cate_id;
		$redis_key .= ':page:'.$searchData['page'];*/

		// 获取分类商品信息
		$this->load->model('o_mall_goods');
		$goodsList = $this->o_mall_goods->get_goods_info_by_cateid($searchData,40,'last_update desc,add_time desc','','','1', $cate_id );
		$this->_viewData['goods_list'] = empty($goodsList['goods']) ? [] : $goodsList['goods'];


        $url = 'index/goods_free_ship';
		
		$this->_viewData['total_rows']=$config['total_rows'] = empty($goodsList['total']) ? 0 : $goodsList['total'];
		unset($goodsList, $searchData['language_id']);

        $searchData['cate_id'] = $cate_id;
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link']=$url;

        /* Pager */
        $config['per_page'] =40;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['cur_page']=$searchData['page'];
        $this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);

        parent::index(THEME_NAME.'/','free_ship');
    }

    /* 促销  */
    public function promote() {
        $redis_title = $this->config->item('tps138mallgoods');
        $language_id = $this->_viewData['curLan_id'];
        $location_id = $this->_viewData['curLocation_id'];
		$cache_time  = $this->config->item('mall_redis_time');
        
        $cate_id=intval($this->input->get('cate_id'));
        $this->_viewData['cate_id'] = $cate_id;

        /* 头部信息 */
        $this->_viewData['title']=strip_tags(lang('label_nav_promote')).' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/promote';

        //获取促销banner
        $this->load->model("tb_mall_ads");
        $this->_viewData['banner_ads'] = $this->tb_mall_ads->get_index_ads('promote_'.$location_id,false);

        //获取促销推荐产品
        //$this->_viewData['best_goods'] =$this->m_goods->get_recommend_goods('is_promote=1 and is_best=1',20,'last_update desc,add_time desc');

        //获取促销产品列表
        $this->load->library('pagination');
        $page = intval($this->input->get('page'));

        $searchData = array();

        $searchData['page'] = max($page,1);

        if(!empty($cate_id)) {
            $children = getChildArr($this->_viewData['category_all'],$cate_id);
            $children=$children.$cate_id; //选中类下所有子类,包含自己
            if(empty($children))
            {
                $children=$cate_id; //3级类
            }

            if(!empty($children)) {
                $searchData['cate_id'] = $children;
            }
        }

        $searchData['language_id'] = $language_id;
        $searchData['is_promote']  = 1;


        //leon 优化sql原始内容
        //$this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'sort_order asc,last_update desc');
        //leon 修改之后的内容
        //$this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'sort_order asc,last_update desc','','','1');


		// 获取分类商品信息
		$this->load->model('o_mall_goods');
        $this->_viewData['goods_list']=$this->m_goods->get_goods_info_by_cateid($searchData,40,'sort_order asc,last_update desc','','','1');
        $url = 'index/promote';

        $this->_viewData['total_rows']=$config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData);


        $url = 'index/promote';
		unset($searchData['language_id']);

        $searchData['cate_id'] = $cate_id;
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link']=$url;

        /* Pager */
        $config['per_page'] = 40;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['cur_page']=$searchData['page'];
        $this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);

        parent::index(THEME_NAME.'/','promote');
    }



	/**
	 * 全球购
	 * leon
	 */
	pubLic function global_shopping(){
        
        if($this->_viewData['curLocation_id'] == '156'){
           // $redis_title = $this->config->item('tps138mallgoods');
            $language_id = $this->_viewData['curLan_id'];
            $location_id = $this->_viewData['curLocation_id'];
           // $redis_time  = $this->config->item('mall_redis_time');
            
      		$qqg_info = $this->input->get('elevent');                           //获取地址中的参数
            
      		$global_info_array = $this->m_goods->judge_global_goods($qqg_info); //整合 国家数据
            if ($global_info_array['state']) {
                $state = implode("','", $global_info_array['state']);
                $state_all = "country_flag IN ('{$state}')";
                $state = " AND  country_flag IN ('{$state}')";
            }
            
      		$this->_viewData['is_guo_info'] = $global_info_array['global'];     //判断当前是哪个馆
      		$this->load->model('o_index');
      		/* 新品上架 */
            //$redis_key = $redis_title.':index:global_shopping:new_goods:location_id:'.$location_id.':key:'.md5('is_new=1'. $state);
      		$new_goods = $this->o_index->get_recommend_goods('is_new=1'. $state,12,'last_update desc,add_time desc', $language_id, $location_id);
      		$this->_viewData['new_goods'] = $new_goods;
            
      		/* 热门推荐 */
            //$redis_key = $redis_title.':index:global_shopping:hot_goods:location_id:'.$location_id.':key:'.md5('is_hot=1'. $state);
      		$hot_goods= $this->o_index->get_recommend_goods('is_hot=1' . $state,12,'last_update desc,add_time desc', $language_id, $location_id);
      		$this->_viewData['hot_goods'] = $hot_goods;
            
      		/* 特惠促销 */
            //$redis_key = $redis_title.':index:global_shopping:promote_goods:location_id:'.$location_id.':key:'.md5('is_promote=1'. $state);
      		$promote_goods = $this->o_index->get_recommend_goods('is_promote=1' . $state,12,'last_update desc,add_time desc', $language_id, $location_id);
      		$this->_viewData['promote_goods'] = $promote_goods;
      		
      		/* 全部馆商品 */
            //$redis_key = $redis_title.':index:global_shopping:global_goods:location_id:'.$location_id.':key:'.md5($state_all);
      		$global_goods = $this->o_index->get_recommend_goods($state_all,32,'last_update desc,add_time desc', $language_id, $location_id);
      		$this->_viewData['global_goods'] = $global_goods;
            
      		/* 头部信息 */
      		$this->_viewData['title']=lang('m_title').' - '.lang('site_name');
      		$this->_viewData['keywords']=lang('m_keywords');
      		$this->_viewData['description']=lang('m_description');
      		$this->_viewData['canonical']=base_url();
            
    		//品牌logo内容
    		//$this->_viewData['global_brand_logo']=$global_info_array['global_ads'];//品牌logo ID
    		$this->load->model('tb_mall_goods_ads');
    		$where = ['region_code' => $this->_viewData['curLocation_id'], 'status' => 1, 'media' => 1];
    		$where['position_id'] = $global_info_array['global_ads'];
    		$global_mall_ads_list = $this->tb_mall_goods_ads->get_ads_list($where);
            
            if (!empty($this->_viewData['store_id'])  &&  !empty($this->_userInfo)) {
                foreach ($global_mall_ads_list as $key => &$value) {
                    if (!empty($value['action_val'])) {
                        //$mall_ads_list[$key]['action_val'] = strpos($value['action_val'], 'www') !== false ?  str_ireplace("www",$this->_viewData['store_id'],$value['action_val']) : str_ireplace("mall",$this->_viewData['store_id'],$value['action_val']);
                        //$mall_ads_list[$k][$key]['action_val'] =str_replace("mall",$this->_viewData['store_id'],$value['action_val']);
                        
                        $value['action_val'] = str_ireplace("mall",$this->_viewData['store_id'],$value['action_val']);
                        
                    }
                }
            }

            $this->_viewData['global_mall_ads_list'] = $global_mall_ads_list;
            
      		parent::index(THEME_NAME.'/',$global_info_array['global_views']);
    
        }else{
          redirect(base_url('/'));
        }
	}


	/**
	 * 全球购产品分类筛选
	 * leon
	 */
	public function global_category(){

        if($this->_viewData['curLocation_id'] == '156'){
           // $redis_title = $this->config->item('tps138mallgoods');
            $language_id = $this->_viewData['curLan_id'];
            $location_id = $this->_viewData['curLocation_id'];
    		
            $searchData = $this->input->get() ? $this->input->get() : array();
    
      		if(empty($searchData)) {
      			header("Location: " . base_url('error_404'));
      			exit;
      		}
    
      		$global_info         = $searchData['sn'];            //馆的信息
      		$global_product_type = $searchData['type'];          //馆中的产品类型
      		$global_product_brand_id = $searchData['brand_id'];  //馆中的产品类型
    
      		$global_info_array = $this->m_goods->judge_global_goods($global_info,$global_product_type);//整合 国家数据
    
      		$searchData['country_flag'] = $global_info_array['state']; //产品属于什么馆
    
      		//是不是有产品分类
      		$type_url='';
      		if(!empty($global_product_type)){
      			$searchData[$global_product_type] = 1;                     //产品的类型
      			$type_url = '&type='.$global_product_type;                 //url地址
      		}
    
      		//是不是品牌分类
      		$brand_id_url='';
      		if(!empty($global_product_brand_id)){
      			$searchData['brand_id'] = $global_product_brand_id;        //产品的类型
      			$brand_id_url = '&brand_id='.$global_product_brand_id;     //url地址
      		}
    
      		//排序
      		$order='composite';                  //默认综合排序
      		if(isset($searchData['order'])) {
      			$order=$searchData['order'];       //使用指定排序
      		}else {
      			$searchData['order'] = $order;     //使用默认排序
      		}
    
      		$this->_viewData['order'] = $order;  //记录选择的排序内容
      		$this->_viewData['arr']   = isset($searchData['arr']) ? ($searchData['arr'] == 'down' ? '' : 'down') : '';//排序中的 价格的升和降
      		$this->_viewData['price_pram']=isset($searchData['price']) ? $searchData['price'] : '';   //记录选择价格排序
      		$searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page'] : 1),1); //分页
      		$searchData['language_id'] = intval($this->session->userdata('language_id'));             //获取当前用户的国家
    
      		unset($searchData['sn']);   //去除对应的馆信息
      		unset($searchData['type']); //去除对应的馆的内容信息
    
      		//leon 修改后的内容
            $tmp = $searchData;
             if ($tmp['country_flag']) {
                $md5_where = implode(',', $tmp['country_flag']);
                unset($tmp['country_flag']);
            } 
            /*$md5_where .= implode(",", $tmp);
            unset($tmp);*/
            //$redis_key = $redis_title.':index:global_category:goods:location_id:'.$location_id.':key:'.md5($md5_where);
            /*$get_goods = $this->m_goods->get_goods_info_by_cateid_redis($searchData,40,'',$location_id,$language_id, '1');  
            $this->_viewData['goods'] = $get_goods['list'];
            $this->_viewData['total_rows'] = $get_goods['total_rows'];
            unset($get_goods);*/
            $this->_viewData['goods'] = $this->m_goods->get_goods_info_by_cateid($searchData,40,'','','', '1'); //产品内容
      		$this->_viewData['total_rows'] = $config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData); //产品数量
             
      		//去掉不需要的分页参数
      		unset($searchData['language_id']);
      		unset($searchData['country_flag']);
      		unset($searchData[$global_product_type]);
    
      		/* 分页内容 */
      		$url = 'index/global_category?sn='.$global_info.'&type='.$global_product_type; //组合分页地址
      		add_params_to_url($url, $searchData);                                          //整合地址信息
      		$this->_viewData['page_link']=$url;                                            //记录地址信息
      		$this->load->library('pagination');
      		$config['per_page'] = 40;                                                      //每页显示的条数
      		$config['base_url'] = base_url($url);                                          //地址
      		$config['cur_page'] = $searchData['page'];
      		$this->pagination->initialize_ucenter($config);
    
      		$this->_viewData['pager'] = $this->pagination->create_links();
      		$this->_viewData['cur_page']=$searchData['page'];
      		$this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);
    
      		/* 头部信息 */
      		$this->_viewData['title']=lang($global_info_array['global']).' - '.lang('site_name');
    
      		/* seo权重集中  */
      		$is_canonical_url = $this->_viewData['canonical'] = base_url().'index/global_category?sn='.$global_info.$type_url.$brand_id_url;
      		$this->_viewData['cate_url'] = $is_canonical_url;
    
      		if($global_product_type){
      		    /* 面包屑导航  */
      		    $this->_viewData['nav_title']= ' - '.lang($global_info_array['global']).' - '.lang($global_product_type);
      		}else{
      		    /* 面包屑导航  */
      		    $this->_viewData['nav_title']= ' - '.lang($global_info_array['global']);
      		}
      		
      		/* 价格  */
      		$price_arr=array(array('0','99'), array('100','199'), array('200','299'), array('300','599'), array('600','999999'));
      		$this->_viewData['price_all']=$price_arr;
    
      		/* 获取history */
      		$this->_viewData['history_goods'] =$this->m_goods->get_history_list();
    
      		parent::index(THEME_NAME.'/','global_category');
    
        }else{
          redirect(base_url('/'));
        }
	}



    /* 套装产品  */
    public function product_g()
    {

        /* 头部信息 */
        $this->_viewData['title']=lang('label_group_sale').' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/product_g';

        /* 面包屑导航  */
        $this->_viewData['nav_title']= lang('label_group_sale');

        $this->load->library('pagination');

        $searchData = $this->input->get()?$this->input->get():array();

        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        $searchData['language_id']=intval($this->session->userdata('language_id'));

        $searchData['is_alone_sale']=2; //读取套装产品
        $searchData['is_for_upgrade']=1; //读取只用于升级的套装

        //leon 优化sql原始内容
        //$goods_rs=$this->m_goods->get_goods_info_by_cateid($searchData,15);
        //leon 修改之后的内容
        $goods_rs=$this->m_goods->get_goods_info_by_cateid($searchData,15,'','','','1');

        $this->_viewData['goods']=$goods_rs;

        //获取套装中的所有单品
        $group_ids='';
        foreach($goods_rs as $goods) {
            $group_ids.=$goods['group_goods_id'].',';
        }
        $group_ids=trim($group_ids,',');
        $this->_viewData['goods_all_list']=$this->m_goods->get_goods_group_info_list($group_ids);

        $url = 'index/product_g';

        $this->_viewData['total_rows']=$config['total_rows'] = $this->m_goods->get_cate_goods_total($searchData);
        //去掉不需要的分页参数
        unset($searchData['language_id']);
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link']=$url;

        /* Pager */
        $config['per_page'] = 15;
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];

        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['cur_page']=$searchData['page'];
        $this->_viewData['total_page']=ceil($config['total_rows'] / $config['per_page']);

        parent::index(THEME_NAME.'/','product_g');
    }

    /* 增加喜欢数量  */
    public function set_like_num() {
        $return_info=array(
            'err'=>0,
            'data'=>lang('info_novalid')
        );
        if($this->input->is_ajax_request()) {

            $goods_id=intval($this->input->get('goods_id'));
            $action=$this->input->get('action');

            $status=$this->m_goods->set_like_num($goods_id,$action);

            if(!$status) {
                $return_info['err']=1;
            }
        }

        echo json_encode($return_info);
    }

    /* 收藏  */
    public function add_wish() {

        // 校验用户登陆状态
        if (empty($this->_userInfo))
        {
            $back_url = substr($_SERVER['HTTP_REFERER'], strlen(base_url()));
            if(empty($back_url)){
                $back_url = 'index';
            }
            $url = base_url('login').'?redirect='.$back_url;
            die(json_encode(array('success'=>0,'url'=>$url)));
        }

        $user_id=$this->get_user_id();

        if($user_id && $this->input->is_ajax_request()) {

            $goods_id=intval($this->input->get('goods_id'));
            $goods_sn=trim($this->input->get('goods_sn'));

            if($goods_id && $goods_sn){
                $this->load->model("tb_mall_wish");
                $status=$this->tb_mall_wish->add_wish($goods_id,$user_id,$goods_sn);
                if($status) {
                    die(json_encode(array('success'=>1)));
                }
            }

            die(json_encode(array('success'=>0)));
        }

        die(json_encode(array('success'=>0,'url'=>'')));
    }

    /* 帮助中心  */
    public function help()
    {
        $id=intval($this->input->get('aid'));

        /* 获取文章列表  */
        $this->_viewData['artical']=$artical=$this->m_global->get_artical($id);

        /* 头部信息 */
        $this->_viewData['title']=$artical['artical']['title'].' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/help';

        parent::index(THEME_NAME.'/','help');
    }

    /* 获取新闻详情  */
    public function get_new_detail() {
        $id=intval($this->input->get('id'));

        if($this->input->is_ajax_request()) {
            $this->load->model('m_news');

            $detail=$this->m_news->get_news_detail($id);

            exit($detail);
        }

        exit('failed');
    }

    /* app下载页 */
    public function app_download() {

        switch($this->_viewData['curLanguage']) {
            case 'hk':
            case 'zh':
                $lang='cn';
                break;
            case 'kr':
                $lang='kor';
                break;
            case 'english':
                $lang='en';
                break;
        }
        $this->_viewData['lang']= $lang;

        if($this->_viewData['is_login']) {
            $this->load->view(THEME_NAME.'/h5_app2', $this->_viewData);
        }else{
            $this->load->view(THEME_NAME.'/h5_app1', $this->_viewData);
        }
    }

    public function choose_package(){
        parent::index(THEME_NAME.'/','choose_Package',$header='header1');
    }

    /* 获取新闻详情  */
    public function news(){

        $id=intval($this->input->get('id'));

        $this->load->model('m_news');

        $news=$this->m_news->get_news_detail($id);

        $this->_viewData['news']=$news;

        $this->_viewData['title']=$news['title'].' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        $this->_viewData['canonical']=base_url().'index/news';

        parent::index(THEME_NAME.'/','news');
    }

    /* 用户反馈  */
    public function feedback()
    {
        /* 头部信息 */
        $this->_viewData['title']=lang('label_feedback').' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';

        /* 不科学！ */
        $lang_id=$this->session->userdata('language_id');
        switch($lang_id){
            case 1:
                $id=57;
                break;
            case 2:
                $id=55;
                break;
            case 3:
                $id=56;
                break;
        }

        /* 获取文章列表  */
        $this->_viewData['artical']=$this->m_global->get_artical($id);

        /* seo权重集中  */
        $this->_viewData['cate_url']=$this->_viewData['canonical']=base_url().'index/feedback';

        parent::index(THEME_NAME.'/','feedback');
    }

    /* 用户反馈 提交 */
    public function feedback_do()
    {

        $this->load->helper('email');
        $this->load->helper('security');

        $email=trim($this->input->post('email'));
        if(!valid_email($email)) {
            exit('failed');
        }

        $content=xss_clean(strip_tags(trim($this->input->post('content'))));
        if(empty($content)) {
            exit('failed');
        }

        if($email && $content && $this->input->is_ajax_request()) {
            $user_id=isset($this->_userInfo['id']) ? $this->_userInfo['id'] : 0;
            $status=$this->m_goods->add_feedback($email,$content,$user_id);

            if($status) {
                exit('ok');
            }

            exit('failed');
        }
    }

    /* 获取更多评论  */
    public function get_comments_page() {
        $page=intval($this->input->get('page'));
        $goods_sn_main=trim($this->input->get('goods_sn_main'));

        if($goods_sn_main) {
            $rs=$this->m_goods->get_comments($goods_sn_main,$page);

            if(!empty($rs)) {
                $arr=array();
                foreach($rs as $k=>$val) {
                    $arr[$k]['com_user']='****'.substr($val['com_user'],4);
                    $arr[$k]['add_date']=date('Y/m/d H:i:s',$val['add_time']);
                    $arr[$k]['com_score']=($val['com_score']/5)*100;
                }
                exit(json_encode($arr));
            }
        }

        exit(json_encode(array()));
    }

    /* 活动-中秋 */
    public function mooncake() {
        /* 头部信息 */
        $this->_viewData['title']=lang('label_mooncake').' - '.lang('site_name');
        $this->_viewData['keywords']='';
        $this->_viewData['description']='';
        $this->_viewData['canonical']=base_url();

        parent::index(THEME_NAME.'/','mooncake');
    }


    public function icp() {
        $this->_viewData['title'] = '增值电信业务经营许可证';
        parent::index(THEME_NAME.'/','icp');
    }



    /**
     * TPS 峰会   验证是不是有登陆系统,必须登陆系统才能跳转到参加界面
     * leon
     */
    public function tps_summit_meeting_login(){
        // 校验用户登陆状态
        if (empty($this->_userInfo)){
            $data = array('success'=>0,'url'=>'login');              //登陆界面
        }else{
            $data = array('success'=>1,'url'=>'tps_summit_meeting'); //参加展会界面
        }
        echo json_encode($data);
        exit;
    }

    /**
     * TPS 峰会     主界面
     * leon
     */
    public function tps_summit_meeting(){

        //判断时间
        if(time() > 1488297599){// 27号12点的测试时间戳：1488168000       ，        28号23点59分的确定结束时间戳：1488297599
            $data['is_show']=false;//0
        }else{
            $data['is_show']=true; //1
        }

        $bool = $this->is_mobile();
        if($bool){
            $this->load->view(THEME_NAME.'/'.'tps_summit_meeting_app',$data);//手机端界面
        }else{
            $this->load->view(THEME_NAME.'/'.'tps_summit_meeting',$data);    //PC端界面
        }
    }

    /**
     * TPS 峰会      参展会员信息的判断
     * leon
     */
    public function tps_summit_meeting_judge(){

        $requestData = $this->input->post();
        $name = trim($requestData['name']);        //页面中填写的 会员名称
        $account = trim($requestData['account']);  //页面中填写的 会员ID

        $user_info = $this->_userInfo;                  //当前登录系统的 会员信息
        $user_id = $user_info['id'];                    //当前登录系统的 会员ID
        $user_name = trim($user_info['name']);          //当前登录系统的 会员名称
        $user_level = (int)$user_info['sale_rank'];     //当前登录系统的 会员等级

        $this->load->model('tb_mvp_list');
        $count = $this->tb_mvp_list->get_mvp_success_count();//已经成功报名 参加展会的会员人数

        if($count >= config_item('mvp_count')){
            $data = array('success'=>5);//报名人数超过限制，不可以在报名
        }else{
            if($name == $user_name){
                if($account == $user_id){
                    $bool = $this->tb_mvp_list->is_pay($user_id);//判断是不是已经参加
                    if($bool == false){
                        if($user_level == 4 || $user_level == 5){
                            $data = array('success'=>0);//通过（此会员可以报名）
                        }else{
                            $data = array('success'=>1);//会员等级不够，不可以报名
                        }
                    }else{
                        $data = array('success'=>3);//会员已经报名成功
                    }
                }else{
                    $data = array('success'=>2);//会员填写的ID 和当前登陆中的ID 不一样
                }
            }else{
                $data = array('success'=>4);//会员填写的ID 和 当前登陆中的ID 不一样
            }
        }

        echo json_encode($data);
        exit;
    }

    /**
     * 判断 是手机端还是PC端访问
     * @return
     *  真 表示手机端访问
     *  假 表示PC端访问
     *
     *  leon
     */
    function is_mobile(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
        $is_mobile = false;
        foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                $is_mobile = true;
                break;
            }
        }
        return $is_mobile;
    }



    /**
     * 判断是否是通过手机访问
     *
     * return
     *  真   手机端访问
     *  假   PC端访问
     *
     *  （此方法暂时没有使用）
     */
    public static function isMobile() {

        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp',
                'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
                'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
                'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断是手机端还是PC端访问
     * @return boolean
     *
     * leon
     * （此方法暂时没有使用）
     */
    function is_mobile_request(){
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser = '0';
        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;
        if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;
        if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;
        if(isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda','xda-'
        );
        if(in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;
        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        // Pre-final check to reset everything if the user is on Windows
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser=0;
        // But WP7 is also Windows, with a slightly different characteristic
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;
        if($mobile_browser>0)
            return true;
        else
            return false;
    }
}







