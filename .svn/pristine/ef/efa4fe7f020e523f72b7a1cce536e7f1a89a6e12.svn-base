<?php
/**
 * 套餐单品信息数据表
 * leon
 */
class tb_mall_goods_group extends MY_Model {
    protected $table_name = "mall_goods_group";
    function __construct() {
        parent::__construct();
    }


    /**
     * 获取存在指定SKU的套装组ID --- leon
     * @param $group_goods_id      产品SKU
     * @return array               存在这个产品的套餐组ID
     */
    public function get_exist_sku_goods_group_id($group_goods_id){
        $select = "group_id,group_goods";
        $goods_group_data = $this->get_list($select);
        if(empty($goods_group_data)) {
            return array();
        }
        $goods_group_id = array();
        foreach ($goods_group_data as $key => $value){
            $group_id = $value['group_id'];
            $goods_arr = explode('|', $value['group_goods']);
            foreach($goods_arr as $k => $g) {
                $tmp_arr = explode('*',$g);
                $a = (int)trim($group_goods_id);
                $b = (int)trim($tmp_arr[0]);
                if($a == $b){
                    $goods_group_id[$key] = $group_id;
                }
            }
        }
        return $goods_group_id;
    }

    /**
     * 获取 产品套装组中的 产品列表数据 --- leon
     * 参数:
     * 	goods_group_id  套装ID
     * return
     *  一个套装和全部的套装单品数据
     */
    public function get_goods_group_list($goods_group_id,$country_id = 156){

        $language_id=(int)$this->session->userdata('language_id');

        if($language_id == ''){
            $this->load->model('tb_language');
            $language_id = $this->tb_language->get_language_by_location($country_id);
        }

        //获取的是一个套装内容
        $select="group_id,group_goods";
        $where=['group_id'=>$goods_group_id];
        $rs = $this->get_one($select,$where);

        if(empty($rs)) {
            return false;
        }

        $goods_arr=explode('|', $rs['group_goods']);//使用一个字符串分割另一个字符串
        $goods=array();
        $number=0;
        $total=0.00;

        $day  = date('Y-m-d H:i:s');
        foreach($goods_arr as $k=>$g) {
            $tmp_arr=explode('*',$g);//是一个产品的sku
            //获取 套装中的产品
            $this->load->model('tb_mall_goods_main');
            $select_goods="goods_id,goods_sn_main,goods_name,goods_img,market_price,shop_price,is_promote,promote_price,is_on_sale,promote_start_date,promote_end_date,is_hot,is_new,is_free_shipping,country_flag,group_goods_id";
            $where_goods=['goods_sn_main'=>$tmp_arr[0],'language_id'=>$language_id];
            $tmp_rs = $this->tb_mall_goods_main->get_one($select_goods,$where_goods);
            //判断是不是有内容
            if(empty($tmp_rs)){
                continue;
            }
            /*商品在促销期 */
            if ($tmp_rs['is_promote'] == 1){
                $this->load->model("tb_mall_goods_promote");
                //获取促销商品的信息
                $promote = $this->tb_mall_goods_promote->get_goods_promote_info($tmp_rs['goods_sn_main'],$day);
                if($promote){
                    $this->load->model('o_mall_goods_main');
                    //计算折扣比例        promote_price：促销价格。货币美元，单位分           $tmp_rs：产品信息
                    $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $tmp_rs, $language_id);
                    $tmp_rs['shop_price'] = $promote_info['shop_price'];
                    $tmp_rs['price_off'] = $promote_info['price_off'];
                }
            }
            if(empty($tmp_rs)){
                continue;
            }
            $goods['list'][$k]['info']=$tmp_rs;            //产品信息
            $goods['list'][$k]['num']=$tmp_arr[1];         //产品数量
            $total += $tmp_rs['shop_price'] * $tmp_arr[1]; //产品价*产品数量
            $number += $tmp_arr[1];//记录数量
        }
        $goods['number']=$number;  //套餐中单品的数量
        $goods['total']=$total;    //套餐中商品的总价钱
        $goods['goods_name']='';   //套餐名称
        $goods['shop_price']=0;    //套餐价格
        $goods['goods_sn_main']='';//套餐 sku
        $goods['language_id']='';  //

        return $goods;
    }

    /**
     * 获取套餐中全部的单品 sku --- leon
     * @param $package_data            产品的套装ID
     * @return array                   套装中全部的产品 sku
     */
    public function get_goods_item_all_data($package_data){

        $package_group_id = implode(',',$package_data);
        $where = "group_id in({$package_group_id})";
        $sql = "SELECT group_goods FROM mall_goods_group WHERE {$where}";
        $item_all_sku = $this->db_slave->query($sql)->result_array();

        if(empty($item_all_sku)){
            return array();
        }

        $good_sku = array();
        $int = 0;
        foreach($item_all_sku as $key=>$value){
            $goods_aku_array = explode('|', $value['group_goods']);
            foreach($goods_aku_array as $k=>$v){
                $sku_str = explode('*',$v);
                $sku = trim($sku_str[0]);
                if(strlen($sku) == 8){
                    $good_sku[$int]= $sku;
                    $int++;
                }
            }
        }

        $good_sku = array_unique($good_sku);//去重
        $good_sku = array_values($good_sku);
        return $good_sku;
    }






}




