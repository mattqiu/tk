<?php
/**
 * @author Terry
 */
class tb_user_cart extends MY_Model {

    // 购物车上限数
    const CART_ITEM_LIMIT = 99;
    //购物车不同商品数限制
    const CART_NUM_LIMIT = 20;
    protected $table_name = "user_cart";
    function __construct() {
        parent::__construct();
    }

    /**
     * 购物车同步
     * @param $uid  用户ID
     * @param $country_id 区域ID
     * @return array 该用户的购物车信息
     */
    public function get_cart($uid,$country_id){

        //订单数据
        $where = ["customer_id"=>$uid,"country_id"=>$country_id];
        $res = $this->get_list("*",$where,[],self::CART_NUM_LIMIT);

        $this->load->model('tb_mall_goods');
        //重新获取价格
        foreach($res as $k=>$item){
            $res[$k]['shop_price'] = $this->tb_mall_goods->get_goods_price($item['goods_sn']);
        }
        return $res;
    }

    /* 删除购物车商品 */
    public function delete_cart($uid,$goods_sn,$location_id){

        //检查购物车是否存在该商品
        $where = ["customer_id"=>$uid,"goods_sn"=>$goods_sn,"country_id"=>$location_id];
        $res = $this->get_one("id",$where);
        if(empty($res)){
            return 1008;
        }
        $bool = $this->delete_one($where);
        return $bool===true ? 0 : 107;
    }

    /* 编辑购物车数量 */
    public function edit_cart_number($uid,$goods_sn,$location_id,$goods_number){
        //检查购物车是否存在该商品
        $where = ["customer_id"=>$uid,"goods_sn"=>$goods_sn,"country_id"=>$location_id];
        $res = $this->get_one("id",$where);
        if(empty($res)){
            return 1008;
        }
        //验证数量
        if(!is_numeric($goods_number) || $goods_number > 99){
            return 1009;
        }
        $bool = $this->update_one($where,["goods_number"=>$goods_number]);
        return $bool===true ? 0 : 107;
    }

    /* 添加到购物车 */
    public function add_cart($uid,$goods_sn,$goods_number,$country_id){
        //检查区域ID
        $country_id_arr = $this->db->query("select country_id from mall_goods_sale_country where country_id = $country_id")->row_array();
        if(empty($country_id_arr)){
            return 1008;
        }
        //检查子SKU
//        $goods_sn_main_arr = $this->db->query("select goods_sn_main from mall_goods where goods_sn = '$goods_sn'")->row_array();
        $this->load->model("tb_mall_goods");
        $goods_sn_main_arr = $this->tb_mall_goods->get_one("goods_sn_main",["goods_sn"=>$goods_sn]);
        if(empty($goods_sn_main_arr)){
            return 1009;
        }
        //如果两个商品goods_sn相同,区域相同,顾客ID相同,则数量叠加
        $where = ["customer_id"=>$uid,"goods_sn"=>$goods_sn,"country_id"=>$country_id];
        $select = $this->get_one("goods_number",$where);
        if(!empty($select))
        {
            $new_goods_number = $select['goods_number'] + $goods_number;
            $bool = $this->update_one($where,["goods_number"=>$new_goods_number]);
        }
        else
        {
            $goods_sn_main = $goods_sn_main_arr['goods_sn_main'];
//            $goods_info = $this->db->select('goods_name,goods_img,market_price,shop_price,country_flag')
//                ->where('goods_sn_main', $goods_sn_main)->get('mall_goods_main')->row_array();
            $this->load->model("tb_mall_goods_main");
            $goods_info = $this->get_one("goods_name,goods_img,market_price,shop_price,country_flag",
                ["goods_sn_main"=>$goods_sn_main]);
            $user_cart_data = array(
                'customer_id' => $uid,
                'goods_sn' => $goods_sn,
                'goods_sn_main' => $goods_sn_main,
                'goods_name' => $goods_info['goods_name'],
                'goods_img' => $goods_info['goods_img'],
                'market_price' => $goods_info['market_price'],
                'shop_price' => $goods_info['shop_price'],
                'country_flag' => $goods_info['country_flag'],
                'goods_number' => $goods_number,
                'country_id' => $country_id,
            );

            $bool = $this->insert_one($user_cart_data);
        }

        return $bool===true ? 0 : 107;
    }

    /* 获取用户在一区域的购物车商品数量 */
    public function get_number($uid,$location_id){
        $sql = "select sum(`goods_number`) as total_number from user_cart WHERE customer_id = {$uid} AND country_id = {$location_id}";
        $res = $this->db_slave->query($sql)->result_array();
        return $res;
    }

    /**
     * 获取购物车页面数据
     */
    public function get_cart_page_data($store_id, $lang_id, $flag, $rate, $curLocation_id,$uid=0)
    {
        $data = array(
            'items' => array(),
            'count' => 0,
            'goods_amount' => "$0.00",
            'save' => "$0.00",
        );
        $day = date("Y-m-d H:i:s");

        if($uid)
        {
            //如果登陆了，使用数据库购物车
            $where = ["country_id"=>$curLocation_id,"customer_id"=>$uid];
            $user_cart_fromdb = $this->get_list("goods_sn,goods_number as quantity",$where,[],self::CART_NUM_LIMIT);
            $total = 0;
            foreach($user_cart_fromdb as $k=>$v)
            {
                $cont[$v['goods_sn']] = $v;
                $total = bcadd($total,$v['quantity']);
            }
            //将未登陆时的购物车列表也添加进来
            $cart_info_str = get_cookie("cart_cont_{$curLocation_id}_{$store_id}");
            $cart_info_nologin = unserialize($cart_info_str);
            if($cart_info_str and $cart_info_nologin)
            {
                if(isset($cart_info_nologin['cont']) and is_array($cart_info_nologin['cont'])) {
                    foreach ($cart_info_nologin['cont'] as $goods_sn => $v) {
                        if (!empty($cont) and in_array($goods_sn, array_keys($cont))) {
                            //$cont[$goods_sn]['quantity'] = bcadd($cont[$goods_sn]['quantity'],$v['quantity']);
                        } else {
                            $cont[$goods_sn] = ["goods_sn" => $goods_sn, "quantity" => $v['quantity']];
                            $total = bcadd($total, $v['quantity']);
                            if(count($cont) >= self::CART_NUM_LIMIT)
                            {
                                break;
                            }
                        }
                    }
                }
            }
            if(isset($cont) and is_array($cont)){
                //将数量为0的数据清理
                foreach($cont as $k=>$v)
                {
                    if(bccomp($v['quantity'],0) == 0){
                        //unset($cont[$k]);
                        continue;
                    }
                }
                //附加到购物车数据中
                $cart_info['cont'] = $cont;
            }
            $cart_info['total'] = $total;
        }
        else
        {
            //如果未登陆，使用cookie购物车
            $cart_info_str = get_cookie("cart_cont_{$curLocation_id}_{$store_id}");
            $cart_info = unserialize($cart_info_str);
            if (false == $cart_info_str || !isset($cart_info['cont']) || !isset($cart_info['total']))
            {
                return $data;
            }
        }

        $count = 0;
        $goods_amount = 0;
        $total_save = 0;

        if(isset($cart_info['cont']) and is_array($cart_info['cont']))
        {
            foreach ($cart_info['cont'] as $goods_sn => $v)
            {
                // 获取商品规格信息
//                $goods_info = $this->db
//                    ->select('goods_sn_main, color, size, customer, price')
//                    ->where('goods_sn', $goods_sn)
//                    ->where('language_id', $lang_id)
//                    ->limit(1)
//                    ->get('mall_goods')
//                    ->row_array();
                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one('goods_sn_main, color, size, customer, price',
                    ['goods_sn'=>$goods_sn,'language_id'=>$lang_id]);
                if (empty($goods_info))
                {
                    //删除购物车里有，但数据库没有的产品
                    $delete_where = ['customer_id'=>$uid,"goods_sn"=>$goods_sn];
                    $this->delete_one($delete_where);

                    $cart_info['total'] -= $v['quantity'];
                    if ($cart_info['total'] < 0)
                    {
                        $cart_info['total'] = 0;
                    }
                    unset($cart_info['cont'][$goods_sn]);

                    log_message('error', "[get_cart_page_data] invalid sn: {$goods_sn}, lang: {$lang_id}");
                    continue;
                }

                // 获取商品信息
//                $goods_main = $this->db
//                    ->select('goods_name, goods_img, market_price, sale_country, is_promote')
//                    ->where('goods_sn_main', $goods_info['goods_sn_main'])
//                    ->where('language_id', $lang_id)
//                    ->limit(1)
//                    ->get('mall_goods_main')
//                    ->row_array();
                $this->load->model("tb_mall_goods_main");
                $goods_main = $this->tb_mall_goods_main->get_one('goods_name, goods_img, market_price, sale_country, is_promote',
                    ['goods_sn_main'=>$goods_info['goods_sn_main'],'language_id'=>$lang_id]);
                if (empty($goods_main))
                {
                    //删除购物车里有，但数据库没有的产品
                    $delete_where = ['customer_id'=>$uid,"goods_sn_main"=>$goods_info['goods_sn_main']];
                    $this->delete_one($delete_where);

                    $cart_info['total'] -= $v['quantity'];
                    if ($cart_info['total'] < 0)
                    {
                        $cart_info['total'] = 0;
                    }
                    unset($cart_info['cont'][$goods_sn]);

                    log_message('error', "[get_cart_detail_data] invalid main sn: {$goods_info['goods_sn_main']}, lang: {$lang_id}");
                    continue;
                }

                // 销售区域
                $cur_lang = get_cookie('curLan', true);
                $cur_lang = empty($cur_lang) ? 'english' : $cur_lang;
//                $sale_country_list = $this->db->get('mall_goods_sale_country')->result_array();
                $this->load->model("tb_mall_goods_sale_country");
                $sale_country_list = $this->tb_mall_goods_sale_country->get_list("*");
                $country_list = array();
                foreach ($sale_country_list as $info)
                {
                    if (!isset($info["name_{$cur_lang}"]))
                    {
                        log_message('error', "[get_cart_detail_data] error lang: {$cur_lang}, country: ".var_export($info, true));
                        continue;
                    }
                    $country_list[$info['country_id']] = $info["name_{$cur_lang}"];
                }

                // 各种语言的分隔号
                $separatrix_map = array(
                    'zh' => "、",
                    'hk' => "、",
                    'english' => " &",
                    'kr' => "、",
                );
                $separatrix = $separatrix_map[$cur_lang];

                $country_str = "";
                $country_arr = explode("$", $goods_main['sale_country']);
                foreach ($country_arr as $country)
                {
                    $country_str .= "{$country_list[$country]}{$separatrix} ";
                }
                $country_str = trim($country_str, "{$separatrix} ");

                /* 为防止浮点误差，所有商品价格转为以分为单位的整型量计算 */
                $price = intval(round($goods_info['price'] * 100 * $rate));
                $market_price = intval(round($goods_main['market_price'] * 100 * $rate));

                /*商品在促销期 */
                if ($goods_main['is_promote'] == 1){
                    $promote = $this->db->select('promote_price')
                        ->where('goods_sn',$goods_sn)
                        ->where('start_time <=',$day)
                        ->where('end_time >=',$day)
                        ->limit(1)->get('mall_goods_promote')->row_array();
                    if($promote){
                        $price = $promote['promote_price']*$rate;
                    }
                }

                // 商品总计、节省额
                $amount_to = $price * $v['quantity'];
                $save = ($market_price - $price) * $v['quantity'];
                $data['items'][$goods_sn] = array(
                    'goods_sn' => $goods_sn,
                    'goods_sn_main' => $goods_info['goods_sn_main'],
                    'goods_img' => $goods_main['goods_img'],
                    'goods_name' => $goods_main['goods_name'],
                    'country_str' => $country_str,
                    'color_size' => trim("{$goods_info['color']} {$goods_info['size']}"),
                    'price' => $flag.number_format($price / 100, 2),
                    'market_price' => $flag.number_format($market_price / 100, 2),
                    'quantity' => $v['quantity'],
                    'amount_to' => $flag.number_format($amount_to / 100, 2),
                );
                $data['items'][$goods_sn]['attribute'] = array(
                    'color' => $goods_info['color'],
                    'size' => $goods_info['size'],
                    'customer' => $goods_info['customer'],
                );

                $goods_amount += $amount_to;
                $total_save += $save;
                $count += $v['quantity'];
            }
        }
//        var_dump($cont);exit(__FILE__.",".__LINE__."<BR>");

        $data['count'] = $count;
        $data['goods_amount'] = $flag.number_format($goods_amount / 100, 2);
        $data['save'] = $flag.number_format($total_save / 100, 2);

        // 如果购物车里面有无效商品，数组序列值会有所改变，将新的购物车内容存入 cookie
        $novel_cart_info_str = serialize($cart_info);
        if ($cart_info_str != $novel_cart_info_str)
        {
            try{
                set_cookie("cart_cont_{$curLocation_id}_{$store_id}", $novel_cart_info_str, 86400 * 30, get_public_domain(), '/');
            }
            catch (Exception $e)
            {
                log_message("ERROR",$e->getMessage());
            }
        }
//        print_r($data);exit;
        return $data;
    }

    /**
     * 修改购物车并返回页面需刷新的数据
     */
    public function edit_cart($store_id, $flag, $rate, $type, $sn, $qty = 0, $curLocation_id,$uid = 0)
    {
        $this->load->model('tb_mall_goods');

        $data = array(
            'code' => 0,
            'count' => 0,
            'sn' => $sn,
            'qty' => 0,
            'amount_to' => "$0.00",
            'total_amout' => "$0.00",
            'total_save' => "$0.00",
        );

        // 从 cookie 获取购物车内容
        $cart_info = unserialize(get_cookie("cart_cont_{$curLocation_id}_{$store_id}"));
        if (false == $cart_info || !isset($cart_info['cont'][$sn]) || !isset($cart_info['total']))
        {
            $data['code'] = 101;
            //如果未登陆直接返回，否则可能购物车是数据库购物车，需要继续进行判断
            if(!$uid){
                return $data;
            }
        }

        //查询数据库是否购物车表里存在数据
        $country_id = $this->session->userdata('location_id');
        $where = ["goods_sn"=>$sn,"customer_id"=>$uid,"country_id"=>$country_id];
        $cartinfo_fromdb = $this->get_one("*",$where);

        // 修改购物车内容
        switch ($type)
        {
            // 减一
            case 1:
                $cart_info['total']--;
                $cart_info['cont'][$sn]['quantity']--;
                if ($cart_info['cont'][$sn]['quantity'] <= 0)
                {
                    unset($cart_info['cont'][$sn]);
                    $this->delete_one($where);
                }
                break;

            // 加一
            case 2:
                if (++$cart_info['total'] > self::CART_ITEM_LIMIT)
                {
                    $data['code'] = 1037;
                    return $data;
                }
                //添加数量不能大于库存数
                $res = $this->tb_mall_goods->get_goods_number($sn, $country_id);
                $cart_quantity = $cart_info['cont'][$sn]['quantity'];
                if ($cart_quantity >= $res['goods_number']) {
                    $data['code'] = 1038;
                    $data['qty'] = $cart_quantity;
                    return $data;
                }
                $cart_info['cont'][$sn]['quantity']++;
                break;

            // 设置数量
            case 3:
                // 如果 qty 不合法
                if (!filter_var($qty, FILTER_VALIDATE_INT))
                {
                    $data['code'] = 103;
                    $data['qty'] = $cart_info['cont'][$sn]['quantity'];
                    return $data;
                }

                // 如果 qty 小于 0
                if ($qty < 0)
                {
                    $qty = 0;
                }

                $offset = $cart_info['cont'][$sn]['quantity'] - $qty;
                if ($cart_info['total'] - $offset > self::CART_ITEM_LIMIT)
                {
                    $data['code'] = 1037;
                    $data['qty'] = $cart_info['cont'][$sn]['quantity'];
                    return $data;
                }

                //购物车数量不能大于库存数
                $res = $this->tb_mall_goods->get_goods_number($sn, $country_id);
                if ($qty > $res['goods_number']) {
                    $data['code'] = 1038;
                    $data['qty'] = $cart_info['cont'][$sn]['quantity'];
                    return $data;
                }

                if ($qty == 0)
                {
                    unset($cart_info['cont'][$sn]);
                    $this->delete_one($where);
                }
                else
                {
                    $cart_info['cont'][$sn]['quantity'] = $qty;
                }
                $cart_info['total'] -= $offset;
                break;

            // 删除
            case 4:
                $cart_info['total'] -= $cart_info['cont'][$sn]['quantity'];
                //删除数据库的数据
                $this->delete_one($where);
                unset($cart_info['cont'][$sn]);
                break;

            default:
                return 103;
        }

        set_cookie("cart_cont_{$curLocation_id}_{$store_id}", serialize($cart_info), 86400 * 30, get_public_domain(), '/');

        // 获取页面数据，商品总数量
        $data['count'] = $cart_info['total'];

        // qty
        if (isset($cart_info['cont'][$sn]))
        {
            $data['qty'] = $cart_info['cont'][$sn]['quantity'];
        }

        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }

        // total_amoutn, total_save
        $total_amount = 0;
        $total_save = 0;
        $goods_info = null;
        foreach ($cart_info['cont'] as $goods_sn => $v)
        {
            //清理数量为0的数据
            $this->load->model("m_debug");
            if(bccomp($v['quantity'],0) == 0)
            {
                $this->delete_one($where);
                unset($cart_info['cont'][$goods_sn]);
                continue;
            }
            // 获取商品规格信息
//            $goods_info = $this->db
//                ->select('goods_sn_main, price')
//                ->where('goods_sn', $goods_sn)
//                ->where('language_id', $cur_language_id)
//                ->get('mall_goods')
//                ->row_array();
            $this->load->model("tb_mall_goods");
            $goods_info = $this->tb_mall_goods->get_one("goods_sn_main, price",
                ["goods_sn"=>$goods_sn,"language_id"=>$cur_language_id]);
            if (empty($goods_info))
            {
                log_message('error', "[edit_cart] invalid sn: {$goods_sn}");
                continue;
            }

            // 获取商品信息
//            $goods_main = $this->db
//                ->select('market_price')
//                ->where('goods_sn_main', $goods_info['goods_sn_main'])
//                ->where('language_id', $cur_language_id)
//                ->get('mall_goods_main')
//                ->row_array();
            $this->load->model("tb_mall_goods_main");
            $goods_main = $this->tb_mall_goods_main->get_one("market_price",
                ["goods_sn_main"=>$goods_info["goods_sn_main"],"language_id"=>$cur_language_id]);
            if (empty($goods_main))
            {
                log_message('error', "[edit_cart] invalid main sn: {$goods_info['goods_sn_main']}");
                continue;
            }

            $price = intval(round($goods_info['price'] * 100 * $rate));
            $market_price = intval(round($goods_main['market_price'] * 100 * $rate));

            $amount_to = $price * $v['quantity'];

            // amount_to
            if ($goods_sn == $sn)
            {
                $data['amount_to'] = $flag.number_format($amount_to / 100, 2, ".", "");
            }

            $total_amount += $amount_to;
            $total_save += ($market_price - $price) * $v['quantity'];
        }

        $data['total_amout'] = $flag.number_format($total_amount / 100, 2, ".", "");
        $data['total_save'] = $flag.number_format($total_save / 100, 2, ".", "");

        //修改购物车数据
        $cartinfo1 = array();
        $cartinfo1['customer_id'] = $uid;
        $cartinfo1['goods_sn'] = $sn;
        $cartinfo1['goods_sn_main'] = $goods_info['goods_sn_main'];
        $cartinfo1['goods_number'] = $data['qty'];
        $cartinfo1['country_id'] = $this->session->userdata('location_id');

        if(!empty($uid)){
            if($cartinfo_fromdb){
                //存在则修改
                //$this->db->where('id', $cartinfo['id'])->update('user_cart',$cartinfo1);
                if(bccomp($data['qty'],0) > 0){
                    if(!empty($cartinfo_fromdb["id"]))
                        $this->update_one(["id"=>$cartinfo_fromdb["id"]],$cartinfo1);
                }else{
                    $this->delete_one($where);
                }
            }else{
                //否则添加
                //$this->db->insert('user_cart', $cartinfo1);
                if(bccomp($cartinfo1['goods_number'],0) > 0){
                    $this->insert_one($cartinfo1);
                }
            }

        }

        if(bccomp($data['count'],0) < 1)
        {
            $data['count'] = 0;
        }

        return $data;
    }

    /**
     * 批量删除购物车
     * @param $sns
     * @return mixed $ret
     */
    public function remove_batch($store_id,$curLocation_id,$sns,$uid=0)
    {
        $arr_sn = explode(",",$sns);
        if($uid)
        {
            $this->db->where("customer_id",$uid)
                ->where_in("goods_sn",$arr_sn)
                ->delete($this->table_name);
        }
        $cart_info = unserialize(get_cookie("cart_cont_{$curLocation_id}_{$store_id}"));
        foreach($cart_info['cont'] as $sn=>$v)
        {
            if(in_array($sn,$arr_sn))
            {
                unset($cart_info['cont'][$sn]);
            }
        }
        set_cookie("cart_cont_{$curLocation_id}_{$store_id}",serialize($cart_info), 86400 * 30, get_public_domain(), '/');
        return $arr_sn;
    }
    /**
     * 购物车添加商品
     */
    public function add_to_cart($store_id, $attr , $curLocation_id,$uid = 0)
    {
        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }

        // 判断商品库存是否充足
//        $goods_info = $this->db
//            ->select('goods_number,goods_sn_main,color,size,customer')
//            ->where('goods_sn', $attr['goods_sn'])
//            ->where('language_id', $cur_language_id)
//            ->get('mall_goods')
//            ->row_array();
        $this->load->model("tb_mall_goods");
        $goods_info = $this->tb_mall_goods->get_one("goods_number,goods_sn_main,color,size,customer",
            ["goods_sn"=>$attr['goods_sn'],"language_id"=>$cur_language_id]);
        if (empty($goods_info))
        {
            // 商品不存在，系统出错
            return 103;
        }
        if ($goods_info['goods_number'] < $attr['quantity'])
        {
            // 商品库存不足
            return 1039;
        }

        //检查商品的有效性 add by yuandd 2015/12/09
        $this->load->model('m_goods');
        if(!$this->m_goods->check_goods_status($attr['goods_sn'])) {
            return 1039;
        }

        // 从 cookie 获取购物车内容
        $cart_info = unserialize(get_cookie("cart_cont_{$curLocation_id}_{$store_id}"));
        if (false == $cart_info || !isset($cart_info['cont']) || !isset($cart_info['total']))
        {
            $cart_info = array(
                'cont' => array(),
                'total' => 0,
            );
        }

        // 购物车总数不能超过 CART_ITEM_LIMIT
        if ($cart_info['total'] + $attr['quantity'] > self::CART_ITEM_LIMIT)
        {
            return 1037;
        }

        if (isset($cart_info['cont'][$attr['goods_sn']]))
        {
            $cart_info['cont'][$attr['goods_sn']]['quantity'] += $attr['quantity'];
        }
        else
        {
            $cart_info['cont'][$attr['goods_sn']] = array(
                'quantity' => $attr['quantity'],
            );
        }
        $cart_info['total'] += $attr['quantity'];

        // 购物车总数不能超过 CART_NUM_LIMIT
        if (count($cart_info['cont']) > self::CART_NUM_LIMIT)
        {
            return 1054;
        }

        //添加购物车时保存商品数据到数据表
        $cart_arr = array();
        //所选区域ID
        $cart_arr['country_id'] = $this->session->userdata('location_id');
        //主sku sku 数量 顾客ID
        $cart_arr['goods_sn_main'] = $goods_info['goods_sn_main'];
        $cart_arr['goods_sn'] = $attr['goods_sn'];
        $cart_arr['goods_number'] = $attr['quantity'];
        $cart_arr['customer_id'] = $uid;
        //属性
        $cart_arr['color'] = isset($goods_info['color'])?$goods_info['color']:'';
        $cart_arr['size'] = isset($goods_info['size'])?$goods_info['size']:'';
        $cart_arr['customer'] = isset($goods_info['customer'])?$goods_info['customer']:'';

        // 获取商品信息
        $goods_main = $this->db
            ->select('goods_name,goods_img,market_price,shop_price,country_flag')
            ->where('goods_sn_main', $goods_info['goods_sn_main'])
            ->where('language_id', $cur_language_id)
            ->get('mall_goods_main')
            ->row_array();
        //商品名称 图片路径 市场价 售价 产地旗帜
        $cart_arr['goods_name'] = $goods_main['goods_name'];
        $cart_arr['goods_img'] = $goods_main['goods_img'];
        $cart_arr['market_price'] = $goods_main['market_price'];
        $cart_arr['shop_price'] = $goods_main['shop_price'];
        $cart_arr['country_flag'] = $goods_main['country_flag'];

        //添加到数据库
        if(!empty($uid)){
            //查询数据库是否购物车表里存在数据
            $cartinfo = $this->get_one("*",
                ["goods_sn"=>$attr['goods_sn'],"customer_id"=>$uid,"country_id"=>$cart_arr['country_id']]);
            if($cartinfo){
                //存在则修改
                $cart_arr['goods_number'] += $cartinfo['goods_number'];
                $this->update_one(["id"=>$cartinfo['id']],$cart_arr);
            }else{

                //数据库里购物车的产品数量不能大于指定的值
                $cart_db_count = $this->get_counts(["customer_id"=>$uid,"country_id"=>$curLocation_id]);
                if($cart_db_count)
                {
                    if ($cart_db_count > self::CART_ITEM_LIMIT)
                    {
                        return 1037;
                    }
                }

                //否则添加
                $this->insert_one($cart_arr);
            }
        }

        set_cookie("cart_cont_{$curLocation_id}_{$store_id}", serialize($cart_info), 86400 * 30, get_public_domain(), '/');

        return 0;
    }

    /**
     * 提交订单后清空购物车
     */
    public function clear_cart($store_id, $curLocation_id=1,$customer_id = 0)
    {
        delete_cookie("cart_cont_{$curLocation_id}_{$store_id}", get_public_domain(), '/');
        //删除数据库购物车信息
        //所属区域ID
        $country_id = $this->session->userdata('location_id');
        $this->load->model("tb_user_cart");
        $this->tb_user_cart->delete_batch(["customer_id"=>$customer_id,"country_id"=>$country_id]);
        return true;
    }

    /**
     * 获取购物车物品总数量
     */
    public function get_cart_item_num($store_id, $curLocation_id=1,$uid=0)
    {
        if($uid)
        {
            //如果登陆了，使用数据库购物车
            $where = ["country_id"=>$curLocation_id,"customer_id"=>$uid];
            $user_cart_fromdb = $this->get_list("goods_sn,goods_number as quantity",$where,[],self::CART_ITEM_LIMIT);
            $total = 0;
            foreach($user_cart_fromdb as $k=>$v)
            {
                $cont[$v['goods_sn']] = $v;
                $total = bcadd($total,$v['quantity']);
            }
            //将未登陆时的购物车列表也添加进来
            $cart_info_str = get_cookie("cart_cont_{$curLocation_id}_{$store_id}");
            $cart_info_nologin = unserialize($cart_info_str);
            if($cart_info_str and $cart_info_nologin) {
                if (!empty($cart_info_nologin['cont'])) {
                    foreach ($cart_info_nologin['cont'] as $goods_sn => $v) {
                        if(!empty($cont))
                        {
                            if (!in_array($goods_sn, array_keys($cont))) {
                                $total = bcadd($total, $v['quantity']);
                            }
                        }else{
                            $total = bcadd($total, $v['quantity']);
                        }
                    }
                }
            }
            return $total;
        }
        //没有登陆，使用cookie购物车数据
        $total = 0;
        $cart_info = unserialize(get_cookie("cart_cont_{$curLocation_id}_{$store_id}"));
        if (false == $cart_info || !isset($cart_info['total'])|| !isset($cart_info['cont']))
        {
            $item_count = 0;
            $total = 0;
        }
        else
        {
            foreach ($cart_info['cont'] as $goods_sn => $v)
            {
                $total = bcadd($total,$v['quantity']);
            }
        }
        return $total > -1 ? $total : "0";
    }

    /**
     * 获取购物车商品列表
     */
    public function get_cart_goods_list($store_id, $lang_id, $lang, $rate, $curLocation_id,$sn=[])
    {
        // 购物车商品列表
        $cart_info_str = get_cookie("cart_cont_{$curLocation_id}_{$store_id}");
        $cart_info = unserialize($cart_info_str);
        if (false == $cart_info_str || !isset($cart_info['cont']) || !isset($cart_info['total']))
        {
            // 如果缓存为空
            return array();
        }

        //选中的产品
        $goods_sn_list = explode(",",$sn);
        if(empty($goods_sn_list))
        {
            return array();
        }
        // 获取商品详细信息
        $goods_list = array();
        foreach ($cart_info['cont'] as $goods_sn => $v)
        {
            if(in_array($goods_sn,$goods_sn_list)){
                $goods_list[] = array('goods_sn' => $goods_sn, 'quantity' => $v['quantity']);
            }
        }
        return $this->get_goods_data($goods_list, $lang_id, $lang, $rate);
    }

    /**
     * 获取商品数据
     */
    public function get_goods_data($goods_list, $lang_id, $lang, $rate)
    {
        $goods_data = array();
        $day = date("Y-m-d H:i:s");

        // 销售区域
//        $sale_country = $this->db->limit(1000)->get('mall_goods_sale_country')->result_array();
        $this->load->model("tb_mall_goods_sale_country");
        $sale_country = $this->tb_mall_goods_sale_country->get_list("*",[],[],1000);
        $sale_country_map = array();
        foreach ($sale_country as $info)
        {
            if (isset($info["name_{$lang}"]))
            {
                $sale_country_map[$info['country_id']] = $info["name_{$lang}"];
            }
        }

        // 各种语言的分隔号
        $separatrix_map = array(
            'zh' => "、",
            'hk' => "、",
            'english' => " &",
            'kr' => "、",
        );
        $separatrix = $separatrix_map[$lang];

        //取goods_sn列表
        $goods_sn_list = [];
        foreach($goods_list as $v)
        {
            $goods_sn_list[] = $v['goods_sn'];
        }
        if(empty($goods_sn_list))
        {
            return $goods_data;
        }
        //根据goods_sn取全部mall_goods数据
        //此处上redis后再调整
//        $goods_info_list = $this->db
//            ->select('goods_sn_main, color, size, customer, price,goods_sn')
//            ->where_in('goods_sn', $goods_sn_list)
//            ->where('language_id', $lang_id)
//            ->get('mall_goods')
//            ->result_array();
        $this->load->model("tb_mall_goods");
        $goods_info_list = $this->tb_mall_goods->get_list('goods_sn_main, color, size, customer, price,goods_sn',
            ['goods_sn'=>$goods_sn_list,'language_id'=>$lang_id]);

        //取全部goods_sn_main列表
        $goods_sn_main_list = [];
        foreach($goods_info_list as $v)
        {
            $goods_sn_main_list[] = $v['goods_sn_main'];
        }
        if(empty($goods_sn_main_list))
        {
            return $goods_data;
        }
        //取全部mall_goods_main的数据
        $goods_main_list = $this->db
            ->select('goods_sn_main,goods_name, goods_weight,goods_size, store_code, sale_country, is_free_shipping,is_doba_goods,
            doba_supplier_id,supplier_id,shipper_id,is_promote,is_require_id,require_type,goods_img,doba_item_id')
            ->where_in('goods_sn_main', $goods_sn_main_list)
            ->where('language_id', $lang_id)
            ->get('mall_goods_main')
            ->result_array();

        // 获取商品信息
        foreach ($goods_list as $v)
        {
            //筛选出goods_info
            $goods_info = "";
            foreach($goods_info_list as $kl=>$vl)
            {
                if($vl['goods_sn'] == $v['goods_sn'])
                {
                    $goods_info = $vl;
                    break;
                }
            }
            if(empty($goods_info))
            {
                continue;
            }
            //筛选出goods_main
            $goods_main = "";
            foreach($goods_main_list as $kl=>$vl)
            {
                if($vl['goods_sn_main'] == $goods_info['goods_sn_main'])
                {
                    $goods_main = $vl;
                    break;
                }
            }
            if (empty($goods_main))
            {
                continue;
            }

            // 销售地区
            $country_str = "";
            $country_arr = explode("$", $goods_main['sale_country']);
            foreach ($country_arr as $country)
            {
                $country_str .= "{$sale_country_map[$country]}{$separatrix} ";
            }
            $country_str = trim($country_str, "{$separatrix} ");

            // 为防止浮点误差，所有商品价格转为以分为单位的整型量计算
            $price_usd = $goods_info['price'] * 100;
            $price_show = format_price_high_accuracy($price_usd, $rate);

            /*商品在促销期 */
            if ($goods_main['is_promote'] == 1){
                $promote = $this->db->select('promote_price')->where('goods_sn',$v['goods_sn'])
                    ->where('start_time <=',$day)->where('end_time >=',$day)
                    ->limit(1)->get('mall_goods_promote')->row_array();
                if($promote){
                    $price_usd = $promote['promote_price'];
                    $price_show = format_price_high_accuracy($price_usd, $rate);
                }
            }

            $goods_data[] = array(
                'goods_sn' => $v['goods_sn'],
                'goods_sn_main' => $goods_info['goods_sn_main'],
                'goods_name' => $goods_main['goods_name'],
                'goods_weight' => $goods_main['goods_weight'],
                'goods_size' => $goods_main['goods_size'],
                'spec' => trim("{$goods_info['color']} {$goods_info['size']} {$goods_info['customer']}"),
                'price_usd' => $price_usd,
                'price_show' => $price_show,
                'quantity' => $v['quantity'],
                'sale_country' => $country_arr,
                'store_code' => $goods_main['store_code'],
                'limit_area' => lang_attr('checkout_order_area_limit', array('area' => $country_str)),
                'is_free_shipping' => $goods_main['is_free_shipping'],
                'is_doba_goods'=>$goods_main['is_doba_goods'],
                'doba_supplier_id'=>$goods_main['doba_supplier_id'],
                'supplier_id'=>$goods_main['supplier_id'],
                'shipper_id'=>$goods_main['shipper_id'],
                'is_require_id'=>$goods_main['is_require_id'],
                'require_type'=>$goods_main['require_type'],
                'goods_img'=>$goods_main['goods_img'],
                'doba_item_id'=>$goods_main['doba_item_id']
            );
        }
        return $goods_data;
    }

}
