<?php
/** 
 *　商品主表数据访问层
 * @date: 2016-5-11 
 * @author: sky yuan
 */
class tb_mall_goods_main extends MY_Model {
    protected $table_name = "mall_goods_main";
    public $click_name = 'mall:mall_goods_main:goods_click_num:';
    function __construct() {
        parent::__construct();
    }

    /**
     * 商品点击量 2017-06-26
     *@param $goods_sn_main string
     *@author Baker
     *@return Bool 
     */

    public function add_goods_click($goods_sn_main = '') {
        $goods_sn_main = trim($goods_sn_main);
        if (empty($goods_sn_main)) {
            return false;
        }
        $num = intval($this->redis_get($this->click_name.$goods_sn_main));
        if (!$num) {
            $one = $this->db->select('click_count')->where(['goods_sn_main' => $goods_sn_main])->get('mall_goods_main')->row_array();
            $num = intval($one['click_count']);
        }
        return $this->redis_set($this->click_name.$goods_sn_main, ++$num);
    }

    /**
     * 获取推荐商品
     * $where跟$order必须同时为string或同时为array
     * @param $where array or string
     * @param $limit
     * @param $order array or string
     * @param $language_id
     * @param $location_id
     * @return array|bool
     */
    public function get_recommend_goods($where,$limit,$order,$language_id,$location_id) {
        $language_id  = (int)$this->session->userdata('language_id');
        $location_id  = $this->session->userdata('location_id');
        $language_id  = !$language_id ? 1 : $language_id;
        $location_id  = !$location_id ? "840" : $location_id;

        if ($where && is_array($where)) {
            $where['language_id']  = $language_id;
            $where['sale_country'] = $location_id;
            $where['is_on_sale']   = 1;
            $rs = $this->get_list("*", $where, [], $limit, 0, $order);
        } else if ($where && is_string($where)) {
            $where .=  ' and language_id='. $language_id;
            $where .=  ' and sale_country='. $location_id;
            $where .=  ' and is_on_sale=1';
            $sql ="select * from mall_goods_main where $where order by $order limit $limit";
            $rs = $this->db_slave->query($sql)->result_array();
        }

        if ($rs !== false) {
            $day  = date('Y-m-d H:i:s');
            foreach($rs as $k=>$row) {
                $rs[$k]['price_off'] = 0;
                /*商品在促销期 */
                if ($row['is_promote'] == 1){
                    $this->load->model("tb_mall_goods_promote");
                    $promote = $this->tb_mall_goods_promote->get_goods_promote_info($row['goods_sn_main'],$day);
                    if($promote){
                        $this->load->model("o_mall_goods_main");
                        $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price_main'], $row, $language_id);
                        
                        $rs[$k]['shop_price'] = $promote_info['shop_price'];
                        $rs[$k]['price_off'] = $promote_info['price_off'];
                    }
                }
            }
        }
        return $rs;
    }

    /** 
     * 获取浏览历史
     * @date: 2016-5-17 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function get_history_goods($goods_ids) {
        $sql="select * from mall_goods_main where goods_id IN($goods_ids) order by find_in_set(goods_id,'".$goods_ids."')";
        $rs = $this->db_slave->query($sql)->result_array();

		if ($rs !== false) {
		    //$time= time();
			$day  = date('Y-m-d H:i:s');
		    foreach($rs as $k=>$row) {
				$rs[$k]['price_off'] = 0;
				/*商品在促销期 */
				if ($row['is_promote'] == 1){
                    $this->load->model("tb_mall_goods_promote");
					$promote = $this->tb_mall_goods_promote->get_goods_promote_info($row['goods_sn_main'],$day);;
					if($promote){
                        $this->load->model("o_mall_goods_main");
						$promote_info=$this->o_mall_goods_main->cal_price_off($promote['goods_sn_main'], $row, 1);
                        
                        $rs[$k]['shop_price'] = $promote_info['shop_price'];
                        $rs[$k]['price_off'] = $promote_info['price_off'];
					}
				}
		    }

		}
		return $rs;
    } 
    
    /**
     * 获取详情页面信息
     * @date: 2016-5-20
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    public function get_goods_main($language_id,$goods_sn_main) {
        $where = array(
            'is_delete'     => 0,
            'language_id'   => $language_id,
            'goods_sn_main' => $goods_sn_main,
        );
        return $this->get_one("*", $where);
    }
    /**
     *  单个商品
     *@param select string
     *@param $where string array
     *@return array
     *@author baker
     *@date 2017-3-1 
     */
    public function get_one_goods($select = '*', $where = '') {
        $rs = [];
        if ($where  && is_string($where)) {
            $rs = $this->db_slave->where($where)->select($select)->get($this->table_name)->row_array();
        } elseif ($where  && is_array($where)) {
           $rs  = $this->get_one($select, $where);
        }
        return $rs;

    }
//, $language_id, location_id, $redis_key = ''
    /* 根据大类id获取最热的4个推荐产品  */
    public function get_recomment_goods_by_cateid($cate_ids, $limit = 4, $order = '', $language_id, $location_id) {
        if(empty($cate_ids)) {
            return array();
        }
        $location_id= $location_id ?  $location_id : $this->session->userdata('location_id');
        $language_id= $language_id ? intval($language_id) : (int)$this->session->userdata('language_id');
        $limit = intval($limit);
        $order = !empty($order) ? $order : [];
        $rs = $this->get_list('goods_name,goods_sn_main,goods_img,shop_price,market_price,country_flag,is_new,is_hot,is_promote,is_free_shipping,promote_price,promote_start_date,promote_end_date,is_direc_goods',
             [
                 'is_on_sale'=>1,
                 'is_hot'=>1,
                 'is_best'=>1,
                 'language_id'=>$language_id,
                 "cate_id IN($cate_ids)"=>null,
                 'sale_country'=>$location_id,
             ],[], $limit, 0, $order);

        if ($rs !== false) {
            $day  = date('Y-m-d H:i:s');
            foreach($rs as $k=>$row) {
                $rs[$k]['price_off'] = 0;
                /*商品在促销期 */
                if ($row['is_promote'] == 1){
                    $this->load->model("tb_mall_goods_promote");
                    $promote = $this->tb_mall_goods_promote->get_goods_promote_info($row['goods_sn_main'],$day);
                    if($promote){
                        $this->load->model("o_mall_goods_main");
                        $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $row, $language_id);

                        $rs[$k]['shop_price'] = $promote_info['shop_price'];
                        $rs[$k]['price_off'] = $promote_info['price_off'];

                    }
                }
            }
        }
        return $rs;
    }


    /**
     * 获取全部套装数据 --- leon
     * @param $country_id       区域ID
     * @param $condition_data   搜索内容 array
     * @return array            返回全部的套装数据
     */
    public function get_all_goods_group_data($country_id,$condition_data){
        $language_id = (int)$this->session->userdata('language_id');
        if($language_id == ''){
            $this->load->model('tb_language');
            $language_id = $this->tb_language->get_language_by_location($country_id);
        }
        //默认条件
        $where = "language_id = '{$language_id}'";           //
        $where .= " AND is_on_sale = 1";                     //是否销售中
        $where .= " AND is_alone_sale = 2";                  //1单品   2套餐
        $where .= " AND is_for_upgrade = 1";                 //是否用于升级的套装产品
        $where .= " AND sale_country='{$country_id}'";       //销售国家,$分割的国家id，000表示其他地区
        $where .= " AND mgm.group_goods_id = mgg.group_id";  //
        //搜索条件
        if(!empty($condition_data['search'])){
            $search =  trim($condition_data['search']);
            //判断是不是为纯数字
            if(!preg_match("/[^\d ]/",$search)){
                //是不是sku
                if(strlen($search) == 8){
                    $this->load->model('tb_mall_goods_group');
                    $group_info = $this->tb_mall_goods_group->get_exist_sku_goods_group_id($search);//搜索包含这个sku的套餐ID
                    $group_id='';
                    if(!empty($group_info)){
                        foreach ($group_info as $ke=>$va){
                            $group_id.=$va.',';//记录全部的套餐ID
                        }
                        $group_id=trim($group_id,',');//
                        $where .= " AND group_goods_id in({$group_id})";
                    }else{
                        $where .= " AND group_goods_id =''";//套餐中没有这个单品
                    }
                }else{
                    $where .= " AND goods_name like '%{$search}%'";//搜索数字名称
                }
            }else{
                $where .= " AND goods_name like '%{$search}%'";//按照搜索的内容搜索产品名称
            }
        }
        $sql="SELECT mgm.goods_id,mgm.goods_sn_main,mgm.goods_name,mgm.goods_img,mgm.market_price,mgm.shop_price,mgm.is_promote,mgm.promote_price,mgm.is_on_sale,mgm.promote_start_date,mgm.promote_end_date,mgm.is_hot,mgm.is_new,mgm.is_free_shipping,mgm.country_flag,mgm.group_goods_id,mgm.language_id FROM mall_goods_main AS mgm, mall_goods_group AS mgg WHERE {$where}";
        $groups = $this->db_slave->query($sql)->result_array();
        if(empty($groups)){
            return array();
        }
        //整理套装ID
        $package_data = array();
        foreach($groups as $key=>$value){
            $package_data['list'][$key] = $value;
            $package_data['group_goods_id'][$key] = $value['group_goods_id'];
        }
        $package_data['group_goods_id']=array_unique($package_data['group_goods_id']);//去重
        return $package_data;
    }

    /**
     * 整理 套餐数据和套餐中的单品数据 --- leon
     * @param $package_data   套餐数据数组
     * @return mixed          返回套装和套装中的单品 详细数据
     */
    public function get_useable_goods_group_data($package_data){
        $language_id = (int)$this->session->userdata('language_id');
        if (!empty($package_data['list'])){
            foreach ($package_data['list'] as $key=>$group){
                $this->load->model('tb_mall_goods_group');
                //获取套装单品数据  一个套餐里面的全部产品数据
                $group_info = $this->tb_mall_goods_group->get_goods_group_list($group['group_goods_id']);
                //过滤单品数据绑定错误的套餐
                if(!isset($group_info['list'])){
                    continue;
                }
                $goods_group_info[$group['goods_id']] = $group_info;                               //套装中的 全部单品产品数据
                $goods_group_info[$group['goods_id']]['shop_price'] = round($group['shop_price']); //套装的价钱    四舍五入(销售价，美元)
                //如果是促销商品,则取促销价
                if($group['is_promote'] == '1') {
                    $this->load->model('tb_mall_goods_promote');
                    $promote_price_arr = $this->tb_mall_goods_promote->get_goods_promote_info($group['goods_sn_main'],date("Y-m-d H:i:s"));
                    if(!empty($promote_price_arr)){
                        $goods_group_info[$group['goods_id']]['shop_price'] = round($promote_price_arr['promote_price']/100);//取促销价
                    }else{
                        $goods_group_info[$group['goods_id']]['shop_price'] = round($group->shop_price);                     //取销售价
                    }
                }
                $goods_group_info[$group['goods_id']]['market_price'] = $group['market_price'];    //市场价
                $goods_group_info[$group['goods_id']]['goods_name'] = $group['goods_name'];        //套餐产品的名字
                $goods_group_info[$group['goods_id']]['goods_sn_main'] = $group['goods_sn_main'];  //套餐产品的SKU
                $goods_group_info[$group['goods_id']]['is_on_sale'] = $group['is_on_sale'];        //是否销售中
                $goods_group_info[$group['goods_id']]['language_id'] = $group['language_id'];
                //代品卷换购界面需要的数据
                $goods_group_info[$group['goods_id']]['goods_id'] = $group['goods_id'];                //产品ID
                $goods_group_info[$group['goods_id']]['is_promote'] = $group['is_promote'];            //是否促销商品
                $goods_group_info[$group['goods_id']]['is_hot'] = $group['is_hot'];                    //是否最热产品
                $goods_group_info[$group['goods_id']]['is_new'] = $group['is_new'];                    //是否最新产品
                $goods_group_info[$group['goods_id']]['is_free_shipping'] = $group['is_free_shipping'];//是否免邮
                $goods_group_info[$group['goods_id']]['country_flag'] = $group['country_flag'];        //产地旗标名
                $goods_group_info[$group['goods_id']]['goods_img'] = $group['goods_img'];              //套餐图片
            }
        }
        //过滤掉库存为0的商品
        foreach($goods_group_info as $k => $item){
            $this->load->model('tb_mall_goods');
            $select="goods_number";
            $where=['goods_sn_main'=>$item['goods_sn_main'],'language_id'=>$language_id];
            $goods_number_arr = $this->tb_mall_goods->get_one($select,$where);
            if(!empty($goods_number_arr)){
                if($goods_number_arr['goods_number'] == 0){
                    unset($goods_group_info[$k]);
                }
            }
        }
        return $goods_group_info;
    }

    /**
     * 获取 全部套装中的单品数据信息信息 --- leon
     * @param $package_data      套餐数据数组
     * @return mixed             单品数据
     */
    public function get_useable_group_goods_data($package_data,$condition_data){

        $language_id = (int)$this->session->userdata('language_id');

        $this->load->model('tb_mall_goods_group');
        $item_all_sku = $this->tb_mall_goods_group->get_goods_item_all_data($package_data['group_goods_id']);//获取套餐中的全部SKU
        $item_all_sku_str = implode(',',$item_all_sku);

        if(!empty($condition_data)){
            $search = trim($condition_data['search']);
            if(!preg_match("/[^\d ]/",$search)){
                if(strlen($search) == 8){
                    //搜索SKU
                    if(in_array($search,$item_all_sku)){
                        //搜索单个sku产品
                        $where = "goods_sn_main = '{$search}'";
                    }else{
                        //产品sku为空内容不存在
                        $where = "goods_sn_main = ''";
                    }
                }else{
                    //搜索名称
                    $where = "goods_name like '%{$search}%'";               //按照搜索的内容搜索产品名称
                    $where .= " AND goods_sn_main in({$item_all_sku_str})";
                }
            }else{
                //搜搜名称
                $where = "goods_name like '%{$search}%'";                   //按照搜索的内容搜索产品名称
                $where .= " AND goods_sn_main in({$item_all_sku_str})";
            }
        }else{
            //全部单品数据
            $where = "goods_sn_main in({$item_all_sku_str})";
        }

        $sql="SELECT mgm.goods_id,mgm.goods_sn_main,mgm.goods_name,mgm.goods_img,mgm.market_price,mgm.shop_price,mgm.is_promote,mgm.promote_price,mgm.is_on_sale,mgm.promote_start_date,mgm.promote_end_date,mgm.is_hot,mgm.is_new,mgm.is_free_shipping,mgm.country_flag,mgm.group_goods_id,mgm.language_id FROM mall_goods_main AS mgm WHERE {$where}";

        $groups = $this->db_slave->query($sql)->result_array();//获取全部的单品数据

        //整理商品数据
        foreach($groups as $k => $item){

            //过滤下架商品
            if($item['is_on_sale'] == '0'){
                unset($groups[$k]);
            }

            $this->load->model('tb_mall_goods');
            $select="goods_number";
            $where=['goods_sn_main'=>$item['goods_sn_main'],'language_id'=>$language_id];
            $goods_number_arr = $this->tb_mall_goods->get_one($select,$where);
            //过滤库存为0的
            if(!empty($goods_number_arr)){
                if($goods_number_arr['goods_number'] == 0){
                    unset($groups[$k]);
                }
            }

            //如果是促销商品,则取促销价
            if($item['is_promote'] == '1') {
                $this->load->model('tb_mall_goods_promote');
                $promote_price_arr = $this->tb_mall_goods_promote->get_goods_promote_info($item['goods_sn_main'],date("Y-m-d H:i:s"));
                if(!empty($promote_price_arr)){
                    $groups[$k]['shop_price'] = round($promote_price_arr['promote_price']/100);//取促销价
                }
            }
        }
        $groups = array_values($groups);
        return $groups;
    }

    public function update_combo($where = [], $combo_id = 0) {
        if (empty($where)) {
            return  0;
        }
        $combo_id = intval($combo_id);
        $this->db->where_in('goods_sn_main', $where);
        $this->db->update($this->table_name, ['is_combo' => $combo_id]);
        return $this->db->affected_rows();
    }

    public function get_goods_combo($where) {
        $page = intval($where['page']);
        unset($where['page']);
        $ret = array(
            'count' => 0,
            'page'  => $page,
            'list'  => []
        );
        if (empty($where)) {
            return $ret;
        }
        if ($where['goods_name']) {
            $goods_name = $where['goods_name'];
            unset($where['goods_name']);
        }
        if ($where['range']) {
            $range = intval($where['range']);
            unset($where['range']);
        }
        $where['is_combo'] = 1;
        $this->db_slave->where($where);
        if (!empty($goods_name)) {
            $this->db_slave->like('goods_name', $goods_name);
        }
        $db = clone($this->db_slave);
        if ($range >= 1) {
            $goods_main_list = $this->db_slave->get('mall_goods_main')->result_array();
            if (empty($goods_main_list)) {
                return $ret;
            }
            foreach ($goods_main_list as $key => $value) {
                $arr[]= $value['goods_sn_main'];
            }
            $sn = implode("','", $arr);
            $sn = "'".$sn ."'";
            $arr = [];
            $having = $range == 1 ? 'combo >= 1 AND combo < 2' : ($range == 2 ? 'combo >= 2 AND combo < 3' : ($range == 3 ?  'combo >= 3 AND combo < 4' : 'combo >= 4'));
            $sql = "SELECT  MIN(FLOOR((price - purchase_price - (price * 0.05)) / purchase_price)) AS combo, goods_sn_main FROM mall_goods WHERE goods_sn_main IN ($sn)  GROUP BY goods_sn_main  HAVING  {$having} ";
            $goods_list = $this->db_slave->query($sql)->result_array();
            if (empty($goods_list)) {
                return $ret;
            }
            foreach ($goods_list as $key => $value) {
                $arr[] = $value['goods_sn_main'];
            }
            $db->where_in('goods_sn_main', $arr);
        }
        $db_list = clone($db);
        $count = $db_list->count_all_results($this->table_name);
        $db->order_by('goods_id desc')->select('goods_sn_main')->limit(20, ($page - 1) * 20);
        $list = $db->get($this->table_name)->result_array();
        $ret['count'] = $count;
        $ret['list']  = $list;
        $ret['page']  = $page;
        return $ret;
    }




}

