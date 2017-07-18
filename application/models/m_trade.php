<?php

class M_trade extends MY_Model
{
    private $err_code = 0;
    private $DEBUG = true;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_trade_orders");
        $this->load->model("tb_trade_orders_goods");
    }

    public function get_err_code()
    {
        return $this->err_code;
    }

    /**
     * 检查购买数量的合法性
     * @date: 2016-5-25
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    public function check_stock_valid($goods_sn,$quantity)
    {
        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }

        // 判断商品库存是否充足
//        $goods_info = $this->db_slave
//            ->select('goods_number')
//            ->where('goods_sn', $goods_sn)
//            ->where('language_id', $cur_language_id)
//            ->get('mall_goods')
//            ->row_array();
        $this->load->model("tb_mall_goods");
        $goods_info = $this->tb_mall_goods->get_one('goods_number',
            ['goods_sn'=>$goods_sn,'language_id'=>$cur_language_id]);

        if (empty($goods_info) || $goods_info['goods_number'] < $quantity)
        {
            // 商品库存不足
            return 1039;
        }

        return 0;
    }


    /****************************** 级联地址 ******************************/

    /**
     * 级联地址 - 将数据库数据转为可供前端直接使用的js文件
     * $b bool ,是否不列出美国城市的数据
     */
    public function data_convert_to_json_file($b=true)
    {
        echo(__FILE__.",".__LINE__."\n");
        // 获取国家代码
//        $this->db
//            ->select('country_code')
//            ->from('trade_addr_linkage')
//            ->where('level', 1);
//        $country_list = $this->db->get()->result_array();
        $this->load->model("tb_trade_addr_linkage");
        $country_list = $this->tb_trade_addr_linkage->get_list("country_code",["level"=>1]);
        echo(__FILE__.",".__LINE__."\n");
        $data = array();
        foreach ($country_list as $item)
        {
//            $list = $this->db
//                ->select('country_code,code, parent_code, name, level')
//                ->where('country_code', $item['country_code'])
//                ->get('trade_addr_linkage')
//                ->result_array();
            echo(__FILE__.",".__LINE__."\n");

            $list = $this->tb_trade_addr_linkage->get_list("country_code,code, parent_code, name, level",
                ["country_code"=>$item["country_code"]],[],50000);

            $arrLev = array();
            foreach ($list as $v)
            {
                if($b){
                    //过滤美国的城市
                    if($v['country_code'] == "840" and $v['level'] == "3")
                    {
                        continue;
                    }
                    //美国的"Other"州也过滤掉
                    if($v['country_code'] == "840" and $v['level'] == "2" and strtolower($v['name']) == "other")
                    {
                        continue;
                    }
                }

                $arrLev[$v['level']][$v['code']] = $v;
            }

            $levelCount = count($arrLev);
            if ($levelCount == 1)
            {
                $arrLev[1][0]['leaf'] = array();
            }
            else
            {
                for ($lv = $levelCount; $lv > 1; $lv--)
                {
                    foreach ($arrLev[$lv] as $v)
                    {
                        if (!isset($arrLev[$lv - 1][$v['parent_code']]))
                        {
                            continue;
                        }
                        if (isset($v['leaf']))
                        {
                            $leaf = $v['leaf'];
                        }
                        else
                        {
                            $leaf = array();
                        }
                        $arrLev[$lv - 1][$v['parent_code']]['leaf'][$v['code']] = array('name' => $v['name'], 'leaf' => $leaf);

                        //将Other放到最后
                        foreach($arrLev[$lv - 1][$v['parent_code']]['leaf'] as $k_code=>$v_leaf)
                        {
                            if($v_leaf['name'] == "Other")
                            {
                                unset($arrLev[$lv - 1][$v['parent_code']]['leaf'][$k_code]);
                                $arrLev[$lv - 1][$v['parent_code']]['leaf'][$k_code] = $v_leaf;
                                break;
                            }
                        }



                    }
                    unset($arrLev[$lv]);
                }
            }

            $data[$item['country_code']] = array(
                'name' => $arrLev[1][0]['name'],
                'leaf' => $arrLev[1][0]['leaf'],
            );
            unset($arrLev);
        }
        echo(__FILE__.",".__LINE__."\n");

        $fileStr = "var linkage = ".json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
        $filename = "./file/js/user_address_linkage.js";
        file_put_contents($filename, $fileStr);
        echo(__FILE__.",".__LINE__."\n");

        echo $fileStr;
        exit;
    }

    /**
     * 级联地址 - 导数据用的工具函数
     */
    public function data_tran_from_dw_to_db()
    {
        /* 将数据从下载的 tb_prov_city_area_street 过到使用着的数据库 trade_addr_linkage */
        $this->db_slave
            ->select('code, parentId, name, level')
            ->from('tb_prov_city_area_street');
        $list = $this->db_slave->get()->result_array();

        $attr = array();
        foreach ($list as $v)
        {
            if ($v['level'] == 0)
            {
                continue;
            }
            $attr[] = array(
                'country_code' => 156,
                'code' => $v['code'],
                'parent_code' => $v['parentId'],
                'name' => $v['name'],
                'level' => ++$v['level'],
            );
        }
        //$this->db->insert_batch('trade_addr_linkage', $attr);
        $this->load->model("tb_trade_addr_linkage");
        $this->tb_trade_addr_linkage->insert_batch($attr);
        exit;
    }

    /****************************** 收货地址 ******************************/

    /**
     * 添加收货地址记录
     */
    public function add_deliver_address($attr)
    {
        // 确定没有设默认地址
//        $this->db
//            ->select('id')
//            ->from('trade_user_address')
//            ->where('uid', $attr['uid'])
//            ->where('is_default', 1);
//        $default_row = $this->db->get()->row_array();
        $this->load->model("tb_trade_user_address");
        $default_row = $this->tb_trade_user_address->get_one("*",["uid"=>$attr['uid'],"is_default"=>1]);

        if (!empty($default_row) && $attr['is_default'] == 1)
        {
            $attr['is_default'] = 0;
        }
//        $this->db->insert('trade_user_address', $attr);
//        $insert_id = $this->db->insert_id();
        $attr['address_detail'] = preg_replace("/\n/i"," ",$attr['address_detail']);
        $insert_id = $this->tb_trade_user_address->insert_one($attr);

        return ($insert_id > 0) ? true : false;
    }

    /**
     * 添加收货地址记录
     */
    public function add_deliver_address_app($attr)
    {
        $this->load->model("tb_trade_user_address");
        // 确定没有设默认地址
//        $this->db->from('trade_user_address')->where('uid', $attr['uid'])->where('is_default', 1);
        $where["uid"] = $attr['uid'];
        $where["is_default"] = 1;

        if($attr['country'] == 156 && $attr['addr_lv2'] == 81){
//            $this->db->where('country',156)->where('addr_lv2',81);
//            $default_row = $this->db->get()->row_array();
            $where["country"] = 156;
            $where["addr_lv2"] = 81;
            $default_row = $this->tb_trade_user_address->get_one("*",$where);
        }else if($attr['country'] == 156 && $attr['addr_lv2'] != 81){
//            $this->db->where('country',156)->where('addr_lv2 !=',81);
//            $default_row = $this->db->get()->row_array();
            $where["country"] = 156;
            $where["addr_lv2 !="] = 81;
            $default_row = $this->tb_trade_user_address->get_one("*",$where);
        }else{
//            $this->db->where('country',$attr['country']);
//            $default_row = $this->db->get()->row_array();
            $where["country"] = 156;
            $default_row = $this->tb_trade_user_address->get_one("*",$where);
        }

        if (!empty($default_row) && $attr['is_default'] == 1)
        {
            $attr['is_default'] = 0;
        }

//        $this->db->insert('trade_user_address', $attr);
//        return $this->db->insert_id();
        return $this->tb_trade_user_address->insert_one($attr);
    }

    /**
     * 修改收货地址记录
     */
    public function edit_deliver_address($attr, $uid)
    {
        if (!isset($attr['id']))
        {
            return FALSE;
        }
        $id = $attr['id'];

        // 需要先清空2~5级地区，防止脏数据发生
        if (empty($attr['addr_lv2']))
        {
            $attr['addr_lv2'] = null;
        }
        if (empty($attr['addr_lv3']))
        {
            $attr['addr_lv3'] = null;
        }
        if (empty($attr['addr_lv4']))
        {
            $attr['addr_lv4'] = null;
        }
        if (empty($attr['addr_lv5']))
        {
            $attr['addr_lv5'] = null;
        }

        // 获取指定记录
//        $addr_info = $this->db
//            ->select('uid, consignee, phone, reserve_num, country, addr_lv2, addr_lv3, addr_lv4, addr_lv5, address_detail, zip_code, customs_clearance,first_name,last_name,city')
//            ->from('trade_user_address')
//            ->where('id', $id)
//            ->get()
//            ->row_array();
        $this->load->model("tb_trade_user_address");
        $addr_info = $this->tb_trade_user_address->get_one("*",["id"=>$id]);
        if (empty($addr_info))
        {
            log_message('error', "[edit_deliver_address] row empty, id: {$id}");
            return FALSE;
        }

        // 校验 uid
        if ($addr_info['uid'] != $uid)
        {
            log_message('error', "[edit_deliver_address] uid mismatch, uid: {$uid}, info: {$addr_info['uid']}");
            return FALSE;
        }

        // 获取需要更新的字段。先过滤出拥有相同 key 的数组，再获取 value 不同的列
        $intersect_arr = array_intersect_key($attr, $addr_info);
        $update_attr = array_diff_assoc($intersect_arr, $addr_info);
        if (empty($update_attr)) {
            return TRUE;
        }

//        $this->db->where('id', $id)->update('trade_user_address', $update_attr);
        $this->tb_trade_user_address->update_one(["id"=>$id],$update_attr);
        return TRUE;
    }

    /**
     * 设置指定用户的默认收货地址
     */
    public function set_default_deliver_address($id, $uid)
    {
//        $addr = $this->db
//            ->select('uid')
//            ->from('trade_user_address')
//            ->where('id', $id)
//            ->get()
//            ->row_array();
        $this->load->model("tb_trade_user_address");
        $addr = $this->tb_trade_user_address->get_one("*",["id"=>$id]);
        if (empty($addr) || $addr['uid'] != $uid)
        {
            log_message('error', "[set_default_deliver_address] mismatch");
            return FALSE;
        }

        // 先将所有地址默认置 0
        $update_data = array(
            'is_default' => 0,
        );
//        $this->db->where('uid', $uid)->update('trade_user_address', $update_data);
        $this->tb_trade_user_address->update_batch(["uid"=>$uid],$update_data);

        // 再设置指定地址为默认
        $update_data = array(
            'is_default' => 1,
        );
//        $this->db->where('id', $id)->update('trade_user_address', $update_data);
        $this->tb_trade_user_address->update_one(["id"=>$id],$update_data);

        return TRUE;
    }

    /**
     * 得到默認地址ID
     */
    public function get_default_address($uid){
        $this->load->model("tb_trade_user_address");
//        $row =  $this->db->select('id')->where('uid',$uid)->where('is_default',1)->get('trade_user_address')->row_array();
        $row = $this->tb_trade_user_address->get_one("id",['uid'=>$uid,'is_default'=>1]);
        return $row ? $row['id'] : 0 ;
    }

    /**
     * 得到每个区域的默认地址ID
     */
    public function get_default_address_loc($uid,$location_id){

        $this->load->model("tb_trade_user_address");
        if($location_id == 344){
//            $this->db->where('country',156)->where('addr_lv2',81);
            $where["country"] = 156;
            $where["addr_lv2"] = 81;
        }else if($location_id == 156){
//            $this->db->where('country',156)->where('addr_lv2 <>',81);
            $where["country"] = 156;
            $where["addr_lv2 !="] = 81;
        }else{
//            $this->db->where('country',$location_id);
            $where["country"] = 156;
        }
//        $row =  $this->db->select('id')->where('uid',$uid)->where('is_default',1)->get('trade_user_address')->row_array();
        $where["uid"] = $uid;
        $where["is_default"] = 1;
        $row = $this->tb_trade_user_address->get_one("id",$where);
        return  (string)$row ? $row['id'] : "" ;
    }

    /**
     * 删除收货地址
     */
    public function delete_deliver_address($id, $uid)
    {
//        $addr = $this->db
//            ->select('uid, is_default')
//            ->from('trade_user_address')
//            ->where('id', $id)
//            ->get()
//            ->row_array();
        $this->load->model("tb_trade_user_address");
        $addr = $this->tb_trade_user_address->get_one("*",["id"=>$id]);
        // 校验指定地址与用户是否匹配
        if (empty($addr) || $addr['uid'] != $uid)
        {
            log_message('error', "[set_default_deliver_address] uid mismatch");
            return FALSE;
        }

        // 删除指定地址
//        $this->db->where('id', $id)->delete('trade_user_address');
        $this->tb_trade_user_address->delete_one(["id"=>$id]);

        // 如果被删除的是默认地址，取该用户第一条记录更新为默认地址
        if (1 == $addr['is_default'])
        {
            $update_data = array(
                'is_default' => 1,
            );
//            $this->db->where('uid', $uid)->limit(1)->update('trade_user_address', $update_data);
            $this->tb_trade_user_address->update_one(["uid"=>$uid],$update_data);
        }

        return TRUE;
    }

    /**
     * 根据地址id获取用户当前选择的地址详情
     * 当前函数已移动到tb_trade_user_address里，请改用该函数
     */
    public function get_deliver_address_by_id($addr_id)
    {
        $this->load->model("tb_trade_user_address");
        $list = $this->tb_trade_user_address->get_one("*",["id"=>$addr_id]);
        return $list;
    }

    /****************************** 结算 ******************************/

    /**
     * 获取购物车商品详细数据
     */
    public function get_cart_detail_data($store_id, $lang_id, $cur_rate, $curLocation_id)
    {
        $product_data = array(
            'items' => array(),
            'total_amount' => 0,
            'total_save' => 0,
        );
        $day = date("Y-m-d H:i:s");

        $cart_info_str = get_cookie("cart_cont_{$curLocation_id}_{$store_id}");
        $cart_info = unserialize($cart_info_str);
        if (false == $cart_info_str || !isset($cart_info['cont']) || !isset($cart_info['total']))
        {
            // 如果缓存为空
            return $product_data;
        }

        $total_amount = 0;
        $total_save = 0;
        foreach ($cart_info['cont'] as $goods_sn => $v)
        {
            // 获取商品规格信息
//            $this->db
//                ->select('goods_sn_main, color, size, price')
//                ->from('mall_goods')
//                ->where('goods_sn', $goods_sn)
//                ->where('language_id', $lang_id)
//                ->limit(1);
//            $goods_info = $this->db->get()->row_array();
            $this->load->model("tb_mall_goods");
            $goods_info = $this->tb_mall_goods->get_one('goods_sn_main, color, size, price',
                ['goods_sn'=>$goods_sn,'language_id'=>$lang_id]);
            if (empty($goods_info))
            {
                $cart_info['total'] -= $v['quantity'];
                if ($cart_info['total'] < 0)
                {
                    $cart_info['total'] = 0;
                }
                unset($cart_info['cont'][$goods_sn]);

                log_message('error', "[get_cart_detail_data] invalid sn: {$goods_sn}, lang: {$lang_id}");
                continue;
            }

            // 检查商品库存 todo

            // 获取商品信息
            $this->db
                ->select('goods_name, seller_note, goods_img, market_price, goods_weight, sale_country, is_promote')
                ->from('mall_goods_main')
                ->where('goods_sn_main', $goods_info['goods_sn_main'])
                ->where('language_id', $lang_id)
                ->limit(1);
            $goods_main = $this->db->get()->row_array();
            if (empty($goods_main))
            {
                $cart_info['total'] -= $v['quantity'];
                if ($cart_info['total'] < 0)
                {
                    $cart_info['total'] = 0;
                }
                unset($cart_info['cont'][$goods_sn]);

                log_message('error', "[get_cart_detail_data] invalid main sn: {$goods_info['goods_sn_main']}, lang: {$lang_id}");
                continue;
            }

            /* 为防止浮点误差，所有商品价格转为以分为单位的整型量计算 */
            $conv_price = intval($goods_info['price'] * 100);
            $conv_market_price = intval($goods_main['market_price'] * 100);

            /*商品在促销期 */
            if ($goods_main['is_promote'] == 1){
                $promote = $this->db->select('promote_price')->where('goods_sn',$goods_sn)->where('start_time <=',$day)->where('end_time >=',$day)->limit(1)->get('mall_goods_promote')->row_array();
                if($promote){
                    $conv_price = $promote['promote_price'];
                }
            }

            // 销售区域
            $cur_lang = get_cookie('curLan', true);
            $cur_lang = empty($cur_lang) ? 'english' : $cur_lang;
            $sale_country_list = $this->db->get('mall_goods_sale_country')->result_array();
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

            // 商品总计、节省额
            $amount_to = $conv_price * $v['quantity'];
            $save = ($conv_market_price - $conv_price) * $v['quantity'];

            $product_data['items'][] = array(
                'goods_sn' => $goods_sn,
                'goods_sn_main' => $goods_info['goods_sn_main'],
                'goods_name' => $goods_main['goods_name'],
                'goods_img' => $goods_main['goods_img'],
                'goods_weight' => $goods_main['goods_weight'],
                'seller_note' => $goods_main['seller_note'],
                'color_size' => trim("{$goods_info['color']} {$goods_info['size']}"),
                'price' => $conv_price,
                'price_show' => format_price_high_accuracy($conv_price, $cur_rate),
                'market_price' => $conv_market_price,
                'quantity' => $v['quantity'],
                'amount_to' => $amount_to,
                'save' => $save,
                'country' => $country_arr,
                'country_str' => $country_str,
                'limit_area' => lang_attr('checkout_order_area_limit', array('area' => $country_str)),
            );

            $product_data['total_amount'] += $amount_to;
            $product_data['total_save'] += $save;
        }

        // 如果购物车里面有无效商品，数组序列值会有所改变，将新的购物车内容存入 cookie
        $novel_cart_info_str = serialize($cart_info);
        if ($cart_info_str != $novel_cart_info_str)
        {
            set_cookie("cart_cont_{$curLocation_id}_{$store_id}", $novel_cart_info_str, 86400 * 30, get_public_domain(), '/');
        }

        return $product_data;
    }

    /**
     * 获取直接结算商品列表
     */
    public function get_product_goods_list($goods_sn, $quantity, $lang_id, $lang, $rate)
    {
        $goods_list = array();

        $goods_list[] = array('goods_sn' => $goods_sn, 'quantity' => $quantity);

        $this->load->model("tb_user_cart");

        return $this->tb_user_cart->get_goods_data($goods_list, $lang_id, $lang, $rate);
    }

    /**
     * 检测是否包含doba产品
     * @param $goods_list
     * @return bool $res
     */
    public function have_doba_goods($goods_list)
    {
        foreach($goods_list as $g)
        {
            if($g['is_doba_goods'] == "1")
            {
                return true;
                break;
            }
        }
        return false;
    }

    /***
     * @param $goods_list	goods_list
     * @param $addr_list	收货地址列表
     * @param $currency		币种
     * @param $rate			人民币兑美元汇率
     * @param $cur_all		汇率列表
     * @return array		多维数组：每个收货地址-->对应的能发货的商品清单
     */
    public function get_checkout_data($goods_list, $addr_list, $currency, $rate, $cur_all)
    {
        $checkout_data = array();

        // 货币与汇率映射表
        $currency_map = array();
        foreach ($cur_all as $v)
        {
            $currency_map[$v['currency']] = $v['rate'];
        }

        // 配送方式映射表
        $shipping_map = array(
            1 => lang('checkout_order_deliver_type_express'),
            2 => lang('checkout_order_deliver_type_self'),
        );

        //取全部-用户的收货地区列表
        $area_list = [];
        $this->load->model("tb_trade_user_address");
        foreach ($addr_list as $addr) {
            $area_list[] = $this->tb_trade_user_address->get_area_by_addr($addr);
        }
        //取全部--goods_sn_main
        $goods_sn_main_list = [];
        foreach($goods_list as $k=>$v){
            $goods_sn_main_list[] = $v["goods_sn_main"];
        }
        //取全部--商品中是否包含跨区商品
        $this->db->select("goods_sn_main,freight_fee,country_id");
        if(!empty($goods_sn_main_list)){
            $this->db->where_in("goods_sn_main", $goods_sn_main_list);
        }
        if(!empty($area_list)){
            $this->db->where_in('country_id',$area_list);
        }
        $international_freight_list = $this->db->get("trade_freight_fee_international")->result_array();

        //取全部-批量获取支持发货地区
        $shipper_id_list = [];
        foreach ($goods_list as $goods)
        {
            $shipper_id_list[] = $goods['shipper_id'];
        }
        $mall_goods_shipper_list = $this->db->select("*")
            ->where_in("shipper_id", $shipper_id_list)
            ->get("mall_goods_shipper")->result_array();

//        var_dump($addr_list);var_dump($shipper_id_list);var_dump($mall_goods_shipper_list);exit(__FILE__.__LINE__."<BR>");
        //遍历每个收货地址,每个收货地址可发商品、不可发商品
        foreach ($addr_list as $addr)
        {
            //检验收货人的证件图片信息是否存在图片服务器
            $this->load->model('m_do_img');
            $ID_front = isset($addr['ID_front'])?$addr['ID_front']:'';
            $result = $this->m_do_img->reg_img($ID_front);
            if (!$result) {
                $addr['ID_front'] = '';//真实图片不存在则显示空值
            }
            $ID_reverse = isset($addr['ID_reverse'])?$addr['ID_reverse']:'';
            $result = $this->m_do_img->reg_img($ID_reverse);
            //该图片不存在，请重试
            if (!$result) {
                $addr['ID_reverse'] = '';
            }

            // 用户默认的收货地区
            $this->load->model("tb_trade_user_address");
            $area = $this->tb_trade_user_address->get_area_by_addr($addr);

            // 可发货的商品列表、不可发货的商品列表
            $deliverable = array();
            $invalid = array();
            foreach ($goods_list as $k=>$goods)
            {
                // 商品在销售区域内
                if (in_array($area, $goods['sale_country']))
                {
                    //商品数量小于等于0
                    if(bccomp($goods['quantity'],0) < 1)
                    {
                        unset($goods_list[$k]);
                        continue;
                    }
                    //如果是doba商品,防止和tps供应商混淆,加上doba字符串判断
                    if($goods['is_doba_goods'] == '1') {
                        $goods['shipper_id'] = 'doba'.$goods['doba_supplier_id'];
                    }

                    //初始化供应商订单列表
                    $shipper_id = $goods['shipper_id'];
                    if (!isset($deliverable[$shipper_id])) {
                        //订单体积、大小、总额、包含的商品
                        $deliverable[$shipper_id] = array(
                            'goods_weight' => 0,
                            'goods_size' => 0,
                            'product_amount' => 0,
                            'product_amount_usd' => 0,
                            'international_freight'=>0,
                            'list' => array(),
                        );
                    }

                    //查询商品中是否包含跨区商品
                    foreach($international_freight_list as $k=>$v)
                    {
                        log_message("ERROR","international_freight_list:".var_export($v,true));
                        if($v['goods_sn_main'] == $goods['goods_sn_main'] and $v['country_id'] == $area)
                        {
                            $international_freight = $v;
                            break;
                        }
                    }

                    //含有跨区运费的商品,不计算重量,运费另算
                    if(empty($international_freight))
                    {
                        if ($goods['is_free_shipping'] == 0) {
                            $deliverable[$shipper_id]['goods_weight'] += $goods['goods_weight'] * 1000 * $goods['quantity'];
                            $deliverable[$shipper_id]['goods_size'] += $goods['goods_size'] * $goods['quantity'];
                        }
                    }
                    else
                    {
                        if ($currency == 'USD') {
                            $freight_fee = number_format($international_freight['freight_fee'], 2, ".", "");
                        } else {
                            $freight_fee_usd = bcdiv($international_freight['freight_fee'], $currency_map['USD'],2);
                            $freight_fee = number_format($freight_fee_usd * $currency_map[$currency], 2, ".", "");
                        }
                        $deliverable[$shipper_id]['international_freight'] = bcadd($deliverable[$shipper_id]['international_freight'],$freight_fee,2);
                    }

                    $deliverable[$shipper_id]['product_amount'] = bcadd($deliverable[$shipper_id]['product_amount'],
                        bcmul($goods['price_show'],$goods['quantity'],3),2);
                    $deliverable[$shipper_id]['product_amount_usd'] = bcadd($deliverable[$shipper_id]['product_amount_usd'],
                        bcmul($goods['price_usd'],$goods['quantity'],3),2);

                    $deliverable[$shipper_id]['list'][] = array(
                        'goods_sn' => $goods['goods_sn'],
                        'goods_sn_main' => $goods['goods_sn_main'],
                        'goods_name' => $goods['goods_name'],
                        'spec' => $goods['spec'],
                        'price_show' => $goods['price_show'],
                        'quantity' => $goods['quantity'],
                        'is_doba_goods' => $goods['is_doba_goods'],
                        'doba_supplier_id' => $goods['doba_supplier_id'],
                        'supplier_id' => $goods['supplier_id'],
                        'store_code' => $goods['store_code'],
                        'goods_img' => $goods['goods_img'],
                        'doba_item_id' => $goods['doba_item_id'],
                    );
                }
                else
                {
                    // 不在销售区域或不在配送范围的商品
                    $invalid[] = array(
                        'goods_sn_main' => $goods['goods_sn_main'],
                        'goods_name' => $goods['goods_name'],
                        'spec' => $goods['spec'],
                        'price_show' => $goods['price_show'],
                        'quantity' => $goods['quantity'],
                        'limit_area' => $goods['limit_area'],
                        'is_doba_goods'=>$goods['is_doba_goods'],
                        'doba_supplier_id'=>$goods['doba_supplier_id'],
                        'supplier_id'=>$goods['supplier_id'],
                        'store_code'=>$goods['store_code'],
                        'no_delivery_reason' => 'not in sale area.'
                    );
                }
            }

            // 商品总计
            $product_amount = 0;

            // 计算运费
            foreach ($deliverable as $shipper_id => $item)
            {
                //如果是doba订单,计算doba运费
                if (strstr($shipper_id, 'doba'))
                {
                    $fee = $this->doba_goods_shipping_fee($item['list'],$addr);
                }
                else
                {
                    //计算普通订单运费
                    //$store = $this->db->select("*")->where("shipper_id", $shipper_id)->get("mall_goods_shipper")->row_array();
                    $store = "";
//                    var_dump($mall_goods_shipper_list);echo(__FILE__.",".__LINE__."<BR>");
                    foreach($mall_goods_shipper_list as $k=>$v)
                    {
//                        var_dump($v['shipper_id']);var_dump($shipper_id);echo(__FILE__.",".__LINE__."<BR>");
                        //取到运费模板，需要支持发货地区，////并且是指定供应商
                        if($v['shipper_id'] == $shipper_id) //and in_array($area,explode("$",$v['sale_area']))
                        {
                            $store = $v;
                            break;
                        }
                    }
//                    var_dump($store);echo(__FILE__.",".__LINE__."<BR>");
                    if (!empty($store)) {
                        $fee = $this->get_shipping_fee(
                            $addr,
                            $store['area_rule'],
                            $item['goods_weight'],
                            $store['store_location_code'],
                            $item['list'],
                            $item['goods_size']
                        );
//                        var_dump($fee);echo(__FILE__.",".__LINE__."<BR>");
                    } else {
                        $fee = false;
                    }
                }
//                var_dump($fee);exit(__FILE__.",".__LINE__."<BR>");
                //汇率转换
                if ($fee !== false)
                {
                    if (strstr($shipper_id, 'doba'))
                    {
                        $fee_amount = number_format($fee * $currency_map[$currency], 2, ".", "");
                    }
                    else
                    {
                        $fee_amount =  number_format($fee * $currency_map[$currency]/ 100, 2, ".", "");
                    }

                    $fee_amount = bcadd($deliverable[$shipper_id]['international_freight'],$fee_amount,2);

                    $deliverable[$shipper_id]['shipping_type'][] = array(
                        'type' => 1,
                        'fee' => $fee_amount,
                        'text' => $shipping_map[1],
                    );
                }
                // 商品总计
                $product_amount += $item['product_amount'];
            }

            // 商品总计转为页面显示格式
            $product_amount_show = number_format($product_amount, 2);

            // 港澳台地区特殊处理
            if ($addr['country'] == 158)
            {
                // 台湾
                $addr['country'] = 156;
                $addr['addr_lv2'] = 71;
            }
            else if ($addr['country'] == 344)
            {
                // 香港
                $addr['country'] = 156;
                $addr['addr_lv2'] = 81;
            }
            else if ($addr['country'] == 446)
            {
                // 澳门
                $addr['country'] = 156;
                $addr['addr_lv2'] = 82;
            }
//            var_dump($deliverable);exit(__FILE__.",".__LINE__."<BR>");
            $checkout_data[$addr['id']] = array(
                'addr_id' => $addr['id'],
                'is_default' => $addr['is_default'],
                'consignee' => $addr['consignee'],
                'phone' => $addr['phone'],
                'reserve_num' => $addr['reserve_num'],
                'country' => $addr['country'],
                'addr_lv2' => $addr['addr_lv2'],
                'addr_lv3' => $addr['addr_lv3'],
                'addr_lv4' => $addr['addr_lv4'],
                'address_detail' => $addr['address_detail'],
                'addr_str' => isset($addr['addr_str'])?$addr['addr_str']:'',
                'zip_code' => $addr['zip_code'],
                'customs_clearance' => $addr['customs_clearance'],
                'first_name' => $addr['first_name'],
                'last_name' => $addr['last_name'],
                'city' => $addr['city'],
                'invalid_list' => $invalid,
                'deliverable_list' => $deliverable,
                'product_amount_show' => $product_amount_show,
                'ID_no'=>$addr['ID_no'],
                'ID_front'=>$addr['ID_front'],
                'ID_reverse'=>$addr['ID_reverse'],
            );
        }

        return $checkout_data;
    }

    /**
     * 获取结算数据，针对没有收货地址的新用户
     */
    public function get_checkout_data_for_new($goods_list, $flag, $rate)
    {
        $checkout_data = array(
            'goods_list' => array(),
            'amount_show' => "$0.00",
        );

        $amount_to = 0;
        foreach ($goods_list as $v)
        {
            $amount_to += round($v['price_usd'] * $rate) * $v['quantity'];

            $checkout_data['goods_list'][] = array(
                'goods_sn_main' => $v['goods_sn_main'],
                'goods_name' => $v['goods_name'],
                'spec' => $v['spec'],
                'price_show' => $flag.$v['price_show'],
                'quantity' => $v['quantity'],
                'goods_img' => $v['goods_img']
            );
        }

        $checkout_data['amount_show'] = $flag.number_format($amount_to / 100, 2, ".", "");

        return $checkout_data;
    }

    /**
     * 创建订单的调试信息，如果事务出错，记录
     * @param string $postdata
     * @param string $msg1
     * @param string $msg2
     */
    public function make_order_debug($postdata='',$msg1='',$msg2='')
    {
        if(!$this->db->trans_status())
        {
            if ($this->DEBUG) {
                $redis_key = "m_trade:make_order:debug:" . date("YmdHi");
                $this->redis_lPush($redis_key, $msg1 . "," . $msg2);
                $this->redis_lPush($redis_key,$this->db->last_query());
                if ($this->redis_ttl($redis_key) == -1) {
                    $this->redis_setTimeout($redis_key, 60 * 60);
                }
            }
        }
    }

    /****************************** 订单 ******************************/

    /**
     * 生成订单
     */
    public function make_order($attr, $currency, $rate, $currency_map)
    {
        $this->load->model('m_group');

        $this->load->model('m_split_order');
        $day = date("Y-m-d H:i:s");

        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }

        $insert_attr = array();

        // 商品表格不能为空
        if (empty($attr['deliver']))
        {
            $this->err_code = 1030001;
            return FALSE;
        }

//        //防止重复提交
//        $redis_key = md5(serialize($attr));
//        $this->load->model('o_trade');
//        $res = $this->o_trade->redis_anti_repeat($redis_key);
//        //缓存存在该值，阻止重复提交
//        if($res){
//            echo json_encode(array('success' => false,'msg'=>lang('not_repeat_submit')));
//            exit();
//        }


        // 店主id
        $insert_attr['shopkeeper_id'] = $attr['shopkeeper_id'];

        // 顾客id;
        $insert_attr['customer_id'] = $attr['customer_id'];

        // 收货地址
        $this->load->model("tb_trade_user_address");
        $addr_info = $this->tb_trade_user_address->get_deliver_address_by_id($attr['addr_id']);
        if (empty($addr_info) || $addr_info['uid'] != $attr['customer_id'])
        {
            // 无效的收货地址
            $this->err_code = 1030002;
            return FALSE;
        }
        //修改收货人的身份证号码
        $ID_no = isset($attr['ID_no'])?$attr['ID_no']:'';
        if(!empty($ID_no)) {
            if ($addr_info['ID_no'] != $ID_no) {
                $adds_uid = isset($addr_info['uid']) ? $addr_info['uid'] : '';
                $consignee = isset($addr_info['consignee']) ? $addr_info['consignee'] : '';
//                $this->db->where('uid', $adds_uid)->where('consignee', $consignee)->update('trade_user_address', array(
//                    'ID_no' => $ID_no,
//                ));
                $this->tb_trade_user_address->update_batch(["uid"=>$adds_uid,"consignee"=>$consignee],["ID_no"=>$ID_no]);
            }
        }

        //获取对应的地区
        $this->load->model("tb_trade_user_address");
        $area = $this->tb_trade_user_address->get_area_by_addr($addr_info);
        $insert_attr['area'] = $area;

        $insert_attr['consignee'] = $addr_info['consignee'];
        $insert_attr['phone'] = $addr_info['phone'];
        $insert_attr['reserve_num'] = $addr_info['reserve_num'];
        $this->load->model("tb_trade_addr_linkage");
        $address_arr = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($addr_info);

        if($address_arr['country_code'] == 840 or $address_arr['country_code'] == 410 ){
            //美国，韩国的地址，国家取消掉
            $insert_attr['address'] = trim($address_arr['address']);
        }else{
            $insert_attr['address'] = trim($address_arr['country']).' '.trim($address_arr['address']);
        }

        $insert_attr['zip_code'] = $addr_info['zip_code'];
        $insert_attr['customs_clearance'] = $addr_info['customs_clearance'];

        // 送货时间类型
        $insert_attr['deliver_time_type'] = $attr['deliver_time_type'];

        // 订单备注
        $insert_attr['remark'] = $attr['remark'];

        // 是否需要收据
        $insert_attr['need_receipt'] = $attr['need_receipt'];

        // 支付方式。 0 未支付
        $insert_attr['payment_type'] = '0';

        // 币种
        $insert_attr['currency'] = $currency;

        // 兑美元汇率
        $insert_attr['currency_rate'] = $rate;

        // 折扣类型，0 无折扣；折扣金额 0
        $insert_attr['discount_type'] = '0';
        $insert_attr['discount_amount_usd'] = 0;

        //订单类型(普通订单)
        $insert_attr['order_type'] = '4';

        //订单业绩年月
        $insert_attr['score_year_month'] = date('Ym');

        //共同属性赋值到主订单
        $main_insert_attr = $insert_attr;

        //主订单ID
        $component_id = $this->m_split_order->create_component_id('P');

        $this->db->trans_begin();//事务开始

        $this->make_order_debug($attr,__FILE__.",".__LINE__);
        $child_order_goods = [];//新结构中没有goods_list，准备拼凑goods_list字段数据用于主表的生成
        foreach($attr['deliver'] as &$v)
        {
            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            $goods_weight = 0;          //订单商品总体积
            $goods_size = 0;            //订单商品总大小
            $goods_amount = 0;          //订单商品金额
            $goods_amount_usd = 0;      //订单商品总额(美元)
            $goods_purchase_price = 0;  //成本价
            $international_freight = 0; //商品的国际运费价格
            $international_freight_usd = 0;
            $goods_list = array();      //goods list
            //订单所有商品
            $goods = array();

            //身份证信息
            $insert_attr['ID_no'] = '';
            $insert_attr['ID_front'] = '';
            $insert_attr['ID_reverse'] = '';

            //需要拆单
            if (count($attr['deliver']) > 1)
            {
                $insert_attr['order_id'] = $this->m_split_order->create_component_id('C');
                $insert_attr['order_prop'] = '1';
                $insert_attr['attach_id'] = $component_id;
            }
            else
            {
                //普通订单,关联的order id 为自身id
                $insert_attr['order_id'] = $this->m_split_order->create_component_id('N');
                $insert_attr['order_prop'] = '0';
                $insert_attr['attach_id'] = $insert_attr['order_id'];
            }

            $this->make_order_debug($attr,__FILE__.",".__LINE__);

            //读取每个订单的商品信息
            foreach($v['list'] as $klist=>&$item)
            {
                $this->make_order_debug($attr,__FILE__.",".__LINE__);
                //子商品表增加字段purchase_price,成本价由此字段决定
//                $goods_info = $this->db->select("goods_sn_main,price,color,size,purchase_price,goods_number")
//                    ->where("goods_sn", $item['goods_sn'])->where("language_id", $cur_language_id)
//                    ->get("mall_goods")->row_array();
                $this->load->model("tb_mall_goods");
                $goods_info = $this->tb_mall_goods->get_one('goods_sn_main,price,color,size,purchase_price,goods_number',
                    ['goods_sn'=>$item['goods_sn'],'language_id'=>$cur_language_id]);

                if (empty($goods_info)) {
                    continue;
                }

                //如果购买数量大于库存,阻止提交
                if($item['quantity'] >$goods_info['goods_number']){
                    $this->err_code = 1040;
                    return FALSE;
                }

                //如果购买数量小于1,阻止提交
                if(intval($item['quantity']) < 1){
                    $this->err_code = 1040;
                    return FALSE;
                }

                $this->load->model("tb_mall_goods_main");
                $goods_main_info = $this->tb_mall_goods_main->get_one_auto(
                    [
                        "select"=>"goods_name,goods_weight, is_free_shipping,shop_price,purchase_price, goods_size, 
                        cate_id,market_price,is_doba_goods,goods_name,doba_supplier_id, is_promote,supplier_id,
                        doba_drop_ship_fee,doba_ship_cost,shipper_id,is_require_id,require_type,last_update,
                        doba_product_id,doba_item_id",
                        "where"=>["goods_sn_main"=>$goods_info['goods_sn_main'],"language_id"=>$cur_language_id],
                        "force_master"=>1,
                    ]
                );

                if (empty($goods_main_info)) {
                    continue;
                }

                $this->make_order_debug($attr,__FILE__.",".__LINE__);

                $item['doba_item_id'] = $goods_main_info['doba_item_id'];

                //doba商品的，每次下單去获取实际库存，如果更新时间在四个小时内的不用再次获取，直接取数据库的数据
                $last_time = (int)time() - (int)$goods_main_info['last_update'] - 14400;
                if($goods_main_info['is_doba_goods'] == '1' && $last_time > 0){
                    $this->load->model('m_goods');
                    $this->m_goods->modify_doba_inventory($goods_main_info['doba_product_id'],
                        $goods_main_info['doba_item_id'],$goods_info['goods_sn_main']);

                    //重新获取更新后的doba商品的信息
                    $this->load->model("tb_mall_goods");
                    $goods_info = $this->tb_mall_goods->get_one_auto([
                        "select"=>'goods_sn_main,price,color,size,purchase_price,goods_number',
                        "where"=>['goods_sn'=>$item['goods_sn'],'language_id'=>$cur_language_id],
                        "force_master"=>1
                    ]);

                    if (empty($goods_info)) {
                        continue;
                    }

                    //如果购买数量大于库存,阻止提交
                    if($item['quantity'] >$goods_info['goods_number']){
                        $this->err_code = 1040;
                        return FALSE;
                    }

                    $this->load->model("tb_mall_goods_main");
                    $goods_main_info = $this->tb_mall_goods_main->get_one_auto(
                        [
                            "select"=>"goods_name,goods_weight, is_free_shipping,shop_price,purchase_price, goods_size, cate_id,market_price,is_doba_goods,goods_name,doba_supplier_id, is_promote,supplier_id,doba_drop_ship_fee,doba_ship_cost,shipper_id,is_require_id,require_type,last_update,doba_product_id,doba_item_id",
                            "where"=>["goods_sn_main"=>$goods_info['goods_sn_main'],"language_id"=>$cur_language_id],
                            "force_master"=>1
                        ]
                    );

                    if (empty($goods_main_info)) {
                        continue;
                    }
                }

				/** 商品促销期 */
				if ($goods_main_info['is_promote'] == 1){
					$this->load->model("tb_mall_goods_promote");
					$promote = $this->tb_mall_goods_promote->get_one_auto(
					    [
					        "select"=>"promote_price",
                            "where"=>[
                                'goods_sn'=>$item['goods_sn'],
                                'start_time <='=>$day,
                                'end_time >='=>$day
                            ]
                        ]
                    );
					if($promote){
						$goods_info['price'] = $promote['promote_price']/100;
					}
				}

                $this->make_order_debug($attr,__FILE__.",".__LINE__);
                // 查看是否跨区商品
//                $international_freight_arr = $this->db->select('freight_fee')->from('trade_freight_fee_international')
//                    ->where('goods_sn_main',$goods_info['goods_sn_main'])
//                    ->where('country_id',$area)
//                    ->get()->row_array();

                // 查看是否跨区商品
                $this->load->model("tb_trade_freight_fee_international");
                $international_freight_arr = $this->tb_trade_freight_fee_international->get_one_auto(
                    [
                        "select"=>"freight_fee",
                        "where"=>['goods_sn_main'=>$goods_info['goods_sn_main'],'country_id'=>$area],
                        "force_master"=>1
                    ]
                );

                $this->make_order_debug($attr,__FILE__.",".__LINE__);
                //商品总体积和大小
                if(empty($international_freight_arr))
                {
                    if ($goods_main_info['is_free_shipping'] == 0) {
                        $goods_weight += $goods_main_info['goods_weight'] * 1000 * $item['quantity'];    //商品重量
                        $goods_size += $goods_main_info['goods_size'] * $item['quantity'];                //
                    }
                }
                else
                {
                    if ($currency == 'USD') {
                        $freight_fee = number_format($international_freight_arr['freight_fee'], 2, ".", "");
                    } else {
                        $freight_fee = number_format($international_freight_arr['freight_fee'] * $currency_map[$currency], 2, ".", "");
                    }
                    $international_freight += $freight_fee;
                    $international_freight_usd += $international_freight_arr['freight_fee'];
                }

                $this->make_order_debug($attr,__FILE__.",".__LINE__);
                $is_require_id = $goods_main_info['is_require_id'];
                $require_type = $goods_main_info['require_type'];
                //判断商品下单时是否用填写身份证号或上传身份证图片
                if($is_require_id == 1){
                    if($require_type ==1 || $require_type == 3){
                        //把身份证号码保存到订单表字段
                        $insert_attr['ID_no'] = isset($attr['ID_no'])?$attr['ID_no']:'';
                        if(empty($insert_attr['ID_no'])){
                            $this->err_code = 1030003;
                            return FALSE;
                        }
                    }
                    $this->make_order_debug($attr,__FILE__.",".__LINE__);
                    if($require_type ==2 || $require_type == 3){
                        $ID_front = isset($attr['ID_front'])?$attr['ID_front']:'';
                        $ID_reverse = isset($attr['ID_reverse'])?$attr['ID_reverse']:'';

                        //去图片服务器验证图片是否存在
                        $this->load->model('m_do_img');
                        $result1 = $this->m_do_img->reg_img($ID_front);
                        $result2 = $this->m_do_img->reg_img($ID_reverse);
                        //该图片不存在，请重试
                        if (!$result1 || !$result2) {
                            $this->err_code = 1030004;
                            return FALSE;
                        }
                        //去图片服务器把地址表的身份信息复制一份到订单表，并随机生成唯一的名字
                        $type1 = strstr($ID_front, '.');//图片后缀
                        $type2 = strstr($ID_reverse, '.');//图片后缀
                        do {
                            $rand = rand(100, 999);
                            $new_path1 = 'orderid_card/' . md5(date("His") . $rand) . $type1;//待保存的图片路径
//                            $count = $this->db->from('trade_orders')->where('ID_front', $new_path1)->or_where('ID_reverse', $new_path1)->count_all_results();
                            $count = $this->tb_trade_orders->get_counts(['ID_front'=>$new_path1],['ID_reverse'=>$new_path1]);
                        } while ($count > 0); //如果是重复路径名则重新生成名字
                        $result1 = $this->m_do_img->copy_img($ID_front,$new_path1);
                        do {
                            $rand = rand(100, 999);
                            $new_path2 = 'orderid_card/' . md5(date("His") . $rand) . $type2;//待保存的图片路径
//                            $count = $this->db->from('trade_orders')->where('ID_front', $new_path2)->or_where('ID_reverse', $new_path2)->count_all_results();
                            $count = $this->tb_trade_orders->get_counts(['ID_front'=>$new_path2],['ID_reverse'=>$new_path2]);
                        } while ($count > 0); //如果是重复路径名则重新生成名字
                        $result2 = $this->m_do_img->copy_img($ID_reverse,$new_path2);
                        //移动图片失败，请重试
                        if (!$result1 || !$result2) {
                            $this->err_code = 1030005;
                            return FALSE;
                        }

                        //把图片路径保存到订单表字段
                        $insert_attr['ID_front'] = $new_path1;
                        $insert_attr['ID_reverse'] = $new_path2;
                    }
                }

                $goods_amount += round($goods_info['price'] * 100 * $rate) * $item['quantity'];
                $goods_amount_usd += $goods_info['price'] * 100 * $item['quantity'];
                $goods_list[] = $item['goods_sn'].':'.$item['quantity'];
                $goods_purchase_price += $goods_info['purchase_price'] * 100 * $item['quantity'];

                //商品汇总
                $goods[] = $item;
            }

            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            //如果是doba订单
            if(strstr($v['shipper_id'],'doba'))
            {
                //发货商id
                //$insert_attr['shipper_id'] = substr($v['shipper_id'],4);
                $insert_attr['shipper_id'] = '100';

                //标记为doba订单
                $insert_attr['is_doba_order'] = '1';

                log_message("DEBUG","FILE:".__FILE__.",LINE:".__LINE__.",doba_goods_shipping_fee:".var_export($v['list'],true));

                //运费
                $fee = $this->doba_goods_shipping_fee($v['list'],$addr_info);
            }
            else
            {
                $shipper_id = $v['shipper_id'];
                $insert_attr['shipper_id'] = $shipper_id;
                $store = $this->db->select("*")->where("shipper_id", $shipper_id)->get("mall_goods_shipper")->row_array();
                if(!empty($store)) {
                    $fee = $this->get_shipping_fee(
                        $addr_info,
                        $store['area_rule'],
                        $goods_weight,
                        $store['store_location_code'],
                        $v['list'],
                        $goods_size
                    );

                    $fee = $fee / 100;
                } else {
                    $fee = false;
                }
            }

            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            //汇率转换
            if ($fee !== false) {
                if(strstr($v['shipper_id'],'doba'))
                {
                    $fee_amount_usd = number_format($fee / $currency_map['USD'],2,'.','');
                    $fee_amount = number_format($fee_amount_usd * $currency_map[$currency], 2, ".", "");
                }
                else
                {
                    $fee_amount_usd = $fee;
                    $fee_amount =  number_format($fee * $currency_map[$currency], 2, ".", "");
                }

                $fee_amount = $international_freight + $fee_amount;
                $fee_amount_usd = number_format($fee_amount_usd+$international_freight_usd , 2, ".", "");
            }


            $insert_attr['deliver_fee'] = $fee_amount * 100;
            $insert_attr['deliver_fee_usd'] = $fee_amount_usd * 100;
            $insert_attr['goods_amount'] = $goods_amount;
            $insert_attr['goods_amount_usd'] = $goods_amount_usd;
            $insert_attr['goods_list'] = implode('$',$goods_list);
            $insert_attr['order_amount'] = $goods_amount + $insert_attr['deliver_fee'];
            //如果订单总额小于0，不允许提交
            if(intval($insert_attr['order_amount']) < 0)
            {
                $this->err_code = 10409394;
                return FALSE;
            }
            $insert_attr['order_amount_usd'] = $goods_amount_usd + $insert_attr['deliver_fee_usd'];

            // 利润 = 商品价格 - 成本 - 商品价格 * 5%
            $product_profit = ($goods_amount_usd - $goods_purchase_price - $goods_amount_usd * 0.05);

            //如果是doba商品,利润 = 商品金额 + 运费金额 - 进货价
            if(strstr($v['shipper_id'],'doba'))
            {
                $product_profit = $goods_amount_usd + $fee_amount_usd - $goods_purchase_price;
            }

            $insert_attr['order_profit_usd'] = $product_profit;

            //待付款
            $insert_attr['status'] = Order_enum::STATUS_CHECKOUT;
            if(empty($insert_attr['goods_list'])){
                $this->err_code = 1050;
                return FALSE;
            }

            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            //创建订单
//            $this->db->insert('trade_orders',$insert_attr);
            $this->tb_trade_orders->insert_one($insert_attr);

            $this->make_order_debug($attr,__FILE__.",".__LINE__);

            //如果是doba订单,保存到表中,付款完成后推送
            if(strstr($v['shipper_id'],'doba'))
            {
                $doba_insert_attr = array();
                $doba_insert_attr['order_id'] = $insert_attr['order_id'];
                $doba_insert_attr['goods_list'] = $insert_attr['goods_list'];

//                $address = $this->db->query("select * from trade_user_address WHERE id = {$attr['addr_id']}")->row_array();
                $this->load->model("tb_trade_user_address");
                $address = $this->tb_trade_user_address->get_one("*",["id"=>$attr['addr_id']]);
//                $country_name = $this->db->query("select name from trade_addr_linkage where country_code = {$insert_attr['area']} AND  level = 1")->row_array();
                $this->load->model("tb_trade_addr_linkage");
                $country_name = $this->tb_trade_addr_linkage->get_one("name",
                    ["country_code"=>$insert_attr['area'],"level"=>1]);

                $doba_insert_attr['phone'] = $insert_attr['phone'];
                $doba_insert_attr['city'] = $address['city'];
                $doba_insert_attr['country'] = $country_name['name'];
                $doba_insert_attr['firstname'] = $address['first_name'];
                $doba_insert_attr['lastname'] = $address['last_name'];
                $doba_insert_attr['postal'] = $insert_attr['zip_code'];
                $doba_insert_attr['state'] = $address['addr_lv2'];
                $doba_insert_attr['street'] = $address['address_detail'];
                $doba_insert_attr['create_time'] = date('Y-m-d H:i:s',time());

                $this->db->insert('trade_order_doba_log',$doba_insert_attr);
            }

            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            /** 插入訂單商品表 */
            $goods_order = $this->m_trade->get_order_goods_arr($insert_attr['goods_list'],$cur_language_id);

            //记录所有子订单的商品，为主单做准备
            $child_order_goods[] = $goods_order;
            //为插入主订单准备所有子订单数据，避免再次从数据库查询读取
            $child_order_list[] = $insert_attr;

            $this->m_trade->insert_order_goods_info($insert_attr['order_id'],$goods_order);

            //TPS库存修改(用户下单减库存)
            $this->m_group->update_goods_number($goods, $insert_attr['order_id']);
        }

        $this->make_order_debug($attr,__FILE__.",".__LINE__);
        /********************************************创建主订单***********************************************/

        //如果需要拆单,创建主订单
        if(count($attr['deliver']) > 1)
        {
            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            //查询子订单
//            $child_order = $this->db->select('*')
//                ->where('order_prop', '1')
//                ->where('attach_id', $component_id)
//                ->get('trade_orders')->result_array();
//            $child_order = $this->tb_trade_orders->get_list("*",['order_prop'=>'1','attach_id'=>$component_id]);
            //取消从数据库再拿一次，直接用变量
//            $child_order = $this->tb_trade_orders->get_list_auto([
//                "select"=>"*",
//                "where"=>['order_prop'=>'1','attach_id'=>$component_id],
//                "force_master"=>1,
//            ]);

            if(empty($child_order_list))
            {
                echo json_encode(array('success' => false,'msg'=>lang('info_error')));
                exit();
            }

            //初始化
            $main_insert_attr['goods_amount'] = 0;
            $main_insert_attr['goods_amount_usd'] = 0;
            $main_insert_attr['order_amount'] = 0;
            $main_insert_attr['order_amount_usd'] = 0;
            $main_insert_attr['order_profit_usd'] = 0;
            $main_insert_attr['discount_amount_usd'] = 0;
            $main_insert_attr['deliver_fee_usd'] = 0;
            $main_insert_attr['order_prop'] = '2';
            $main_insert_attr['order_id'] = $component_id;
            $main_insert_attr['attach_id'] = $component_id;
            $main_insert_attr['status'] = Order_enum::STATUS_COMPONENT;

            foreach($child_order_list as $child)
            {
                if(isset($child['goods_list']))
                {
                    $main_goods_list[] = $child['goods_list'];
                    $main_insert_attr['goods_list'] = implode('$',$main_goods_list);
                }
                $main_insert_attr['goods_amount'] += $child['goods_amount'];
                $main_insert_attr['goods_amount_usd'] += $child['goods_amount_usd'];
                $main_insert_attr['order_amount'] += $child['order_amount'];
                $main_insert_attr['order_amount_usd'] += $child['order_amount_usd'];
                $main_insert_attr['order_profit_usd'] += $child['order_profit_usd'];
                $main_insert_attr['discount_amount_usd'] += $child['discount_amount_usd'];
                $main_insert_attr['deliver_fee_usd'] += $child['deliver_fee_usd'];
            }

            //若没有主单的goods_list，自行拼凑一个，因为新结构goods_list字段已经移除
            if(!isset($main_insert_attr['goods_list']))
            {
                $main_insert_attr['goods_list'] = $this->get_goods_list_str($child_order_goods);
            }
//            var_dump($main_insert_attr['goods_list']);
//            $this->db->insert('trade_orders',$main_insert_attr);
            $this->tb_trade_orders->insert_one($main_insert_attr);
            /** 插入訂單商品表 */
            $goods_order = $this->get_order_goods_arr($main_insert_attr['goods_list'],$cur_language_id);
            $this->insert_order_goods_info($component_id,$goods_order);
            $ret_id = $component_id;
        }
        else
        {
            $ret_id = $insert_attr['order_id'];
        }
        $this->make_order_debug($attr,__FILE__.",".__LINE__);
        //保存到订单推送队列表,再定时推送到erp
        if(true){
            if (count($attr['deliver']) > 1)
            {
                $where_trade_orders_queue = ['attach_id'=>$ret_id,'order_prop'=>'1'];
            }else{
                $where_trade_orders_queue = ['order_id'=>$ret_id];
            }
            $this->load->model("o_erp");
            $this->o_erp->trade_order_create($where_trade_orders_queue,$cur_language_id);
        }

        $this->make_order_debug($attr,__FILE__.",".__LINE__);
        //TPS库存修改(用户下单减库存)
//        $this->m_group->update_goods_number($goods);

        if ($this->db->trans_status() === FALSE)
        {
            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->make_order_debug($attr,__FILE__.",".__LINE__);
            $this->db->trans_commit();
            //删除cookie,重复提交
            delete_cookie("submit_token", get_public_domain());
            /*
            //推送doba
            $this->load->model('o_queen');
            if ($doba_insert_attr) {
            	foreach ($doba_insert_attr as $doba_attr) {
            		$this->o_queen->en_queen(o_queen::QUEEN_FOR_PUSH_DOBA, $doba_attr);
            	}
            }
            
            //推送erp
            if ($erp_arr) {
            	foreach ($erp_arr as $erp_ar) {
            		$this->o_queen->en_queen(o_queen::QUEEN_FOR_PUSH_ERP, $erp_ar);
            	}
            }
            */
            return $ret_id;
        }
    }

    /**
     * 若没有主单的goods_list，根据子订单产品列表，自行拼凑一个，
     * 因为新结构goods_list字段已经移除
     * @param $child_order_goods
     * @return string
     */
    public function get_goods_list_str($child_order_goods)
    {
        $res = "";
        foreach($child_order_goods as $k=>$v)
        {
            if($v)
            {
                foreach($v as $i)
                {
                    if($res)
                    {
                        $res .= "$";
                    }
                    $res .= $i['goods_sn'].":".$i['goods_number'];
                }
            }
        }
        return $res;
    }

    /** 生成订单商品表    insert_order_goods_info */
    public function insert_order_goods_info($order_id,$goods_arr,$is_split=FALSE){
        if($is_split === TRUE){ //拆单所有的商品
            if($goods_arr)foreach($goods_arr as $goods_info){
                foreach($goods_info as $goods_detail){
                    $goods_detail['order_id'] = $order_id;
//                    $this->db->insert('trade_orders_goods',$goods_detail);
                    $this->tb_trade_orders_goods->insert_one($goods_detail);
                }
            }
        }else{
            if($goods_arr)foreach($goods_arr as $goods_info){
                $goods_info['order_id'] = $order_id;
//                $this->db->insert('trade_orders_goods',$goods_info);
                $this->tb_trade_orders_goods->insert_one($goods_info);
            }
        }
    }

    /**
     * 订单修改
     */
    public function order_modify($order_id, $update_attr)
    {
//        $this->db->trans_start();
//        $this->db->query("SELECT order_id FROM `trade_orders` WHERE `order_id` = '{$order_id}' FOR UPDATE");
        $this->tb_trade_orders->lock_one_trade_orders($order_id);
//        $this->db->where('order_id', $order_id)->update('trade_orders', $update_attr);
        $this->tb_trade_orders->update_one(['order_id'=>$order_id],$update_attr);
//        $this->db->trans_complete();
        return TRUE;
    }

    /**
     * 订单后台修改
     */
    public function order_admin_modify($attr,$operator_id = '')
    {
        $order_id = $attr['order_id'];

//        $order_info = $this->db->where('order_id', $order_id)->get('trade_orders')->row_array();
        $order_info = $this->tb_trade_orders->get_one("*",['order_id'=>$order_id]);

        if (empty($order_info))
        {
            return FALSE;
        }

        // 修改运费需要同时修改关联字段
        $attr['deliver_fee'] = intval($attr['deliver_fee'] * 100);
        if ($order_info['deliver_fee'] != $attr['deliver_fee'])
        {
            $attr['deliver_fee_usd'] = intval($attr['deliver_fee'] / $order_info['currency_rate']);

            $attr['order_amount'] = $order_info['goods_amount'] + $attr['deliver_fee'];
            $attr['order_amount_usd'] = intval($attr['order_amount'] / $order_info['currency_rate']);

            // order_profit_usd todo
        }

        // 获取需要更新的字段。先过滤出拥有相同 key 的数组，再获取 value 不同的列
        $intersect_arr = array_intersect_key($attr, $order_info);
        $update_attr = array_diff_assoc($intersect_arr, $order_info);
        if (empty($update_attr)) {
            return TRUE;
        }

        $this->db->trans_start();//事务开始

        $before_data = array_intersect_key($order_info,$update_attr);

        $statement = '修改订单信息:'.var_export($before_data,true).'->'.var_export($update_attr,true);
        $this-> add_order_log($order_id, 150, $statement, $operator_id);

//        $this->db->query("SELECT order_id FROM `trade_orders` WHERE `order_id` = '{$order_id}' FOR UPDATE");
        $this->tb_trade_orders->lock_one_trade_orders($order_id);
//        $this->db->where('order_id', $order_id)->update('trade_orders', $update_attr);
        $this->tb_trade_orders->update_one(['order_id'=>$order_id],$update_attr);
        //同步到erp(修改订单信息)
        $this->load->model('m_erp');
        $update_item = $update_attr;
        $update_item['order_id'] = $order_id;
        unset($update_item['country_address']);
        unset($update_item['deliver_time_type']);
        unset($update_item['deliver_fee']);
        unset($update_item['expect_deliver_date']);

//        $this->m_erp->update_order_to_erp_log($update_item);


        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data'] = $update_item;
        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单


        $this->db->trans_complete();

        return TRUE;
    }

    /**
     * 通过订单号获取订单详情
     */
    public function get_order_data($order_id)
    {
        $order_data = array(
            'goods_info' => array(),
            'order_detail'=>array(),
        );

        /*$cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }*/

        // 获取订单信息
//        $order_info = $this->db_slave
//            ->from('trade_orders')
//            ->where('order_id', $order_id)
//            ->get()
//            ->row_array();
        $order_info = $this->tb_trade_orders->get_one("*",['order_id'=>$order_id]);

        if (empty($order_info))
        {
            log_message('error', "[get_order_data] get order info failed, id: {$order_id}");
            return FALSE;
        }

        // 快递信息
        if($order_info['is_doba_order'] == '0'){
            if (isset($order_info['freight_info']) && !empty($order_info['freight_info']))
            {
                list($code, $freight_id) = explode("|", $order_info['freight_info']);
            }
            else
            {
                $code = 0;
                $freight_id = "";
            }

            // 快递公司信息
            $freight_info = $this->db_slave
                ->select("company_name, tracking_url")
                ->where("company_code", $code)
                ->get("trade_freight")
                ->row_array();
            if (empty($freight_info))
            {
                $order_info['freight_info'] = $freight_id;
                $order_info['freight_url'] = null;
            }
            else
            {
                $order_info['freight_info'] = "{$freight_info['company_name']} {$freight_id}";
                $order_info['freight_url'] = get_tracking_url($freight_info['tracking_url'], $freight_id);
            }
        }else{
            $doba_info = $this->db_slave->select('doba_ship_info')->where('order_id',$order_id)->where('state','Completed')->get('trade_orders_doba_order_info')->row_array();
            if (empty($doba_info['doba_ship_info']))
            {
                $order_info['freight_info'] = '';
                $order_info['freight_url'] = null;
            }
            else
            {
                $freight_info = unserialize($doba_info['doba_ship_info']);

                $order_info['freight_info'] = "{$freight_info['carrier']} {$freight_info['track_number']}";
                $order_info['freight_url'] = null;
            }
        }
        // 系统备注
        $remark_data = $this->get_order_remark_data($order_id);
        $order_info['remark_data'] = $remark_data;

        $order_data['order_detail'] = $order_info;

        /** 订单商品信息 */
//        $goods = $this->db_slave->where('order_id',$order_id)->get('trade_orders_goods')->result_array();
        $goods = $this->tb_trade_orders_goods->get_list("*",['order_id'=>$order_id]);
        if($goods)foreach ($goods as $v){

            // 获取商品主要信息
            $goods_main_info = $this->db_slave
                ->select('goods_img,goods_id')
                ->from('mall_goods_main')
                ->where('goods_sn_main', $v['goods_sn_main'])
                //->where('language_id', $cur_language_id)
                ->get()
                ->row_array();
            if (empty($goods_main_info))
            {
                log_message('error', "[get_order_data] get goods main info failed, goods_sn_main: {$v['goods_sn_main']}");
                return FALSE;
            }
            $order_data['goods_info'][] = array(
                'goods_sn' => $v['goods_sn'],
                'goods_name' => $v['goods_name'],
                'goods_img' => $goods_main_info['goods_img'],
                'quantity' => $v['goods_number'],
                'shop_price' => $v['goods_price'],
                'goods_sn_main' => $v['goods_sn_main'],
                'goods_id' => $goods_main_info['goods_id'],
                'goods_attr' => $v['goods_attr'],
            );
        }
        return $order_data;

    }

    /**
     * 高效获得订单信息
     */
    public function get_order_data_efficient($order_id)
    {
//        return $this->db
//            ->where('order_id', $order_id)
//            ->get('trade_orders')
//            ->row_array();
        return $this->tb_trade_orders->get_one("*",['order_id'=>$order_id]);
    }

    /**
     * 得到订单的信息，请用 get_order_data_efficient 替代
     */
    public function get_order_info($order_id)
    {
        // 获取订单信息
//        $order_info = $this->db_slave
//            ->select('order_id as order_sn,order_id,discount_amount_usd,address,deliver_fee_usd,status,order_amount_usd as money,order_amount,customer_id,shopkeeper_id,order_amount_usd,order_profit_usd,goods_amount_usd,currency,
//            need_receipt,discount_type,order_prop,attach_id,goods_list,deliver_fee_usd,txn_id,order_prop,is_doba_order,notify_num,order_type,remark')
//            ->where('order_id', $order_id)
//            ->get('trade_orders')
//            ->row_array();
        $order_info = $this->tb_trade_orders->get_one_auto([
           "select"=>"order_id as order_sn,order_id,discount_amount_usd,address,deliver_fee_usd,status,
           order_amount_usd as money,order_amount,customer_id,shopkeeper_id,order_amount_usd,order_profit_usd,
           goods_amount_usd,currency,need_receipt,discount_type,order_prop,attach_id,goods_list,deliver_fee_usd,
           txn_id,order_prop,is_doba_order,notify_num,order_type,remark",
            "where"=>['order_id'=>$order_id]
        ]);
        //$address=  $this->db->select('*')->where('uid',$order_info['customer_id'])->get('trade_user_address')->result_array();
        $this->load->model("tb_trade_user_address");
        $address = $this->tb_trade_user_address->get_list("*",["uid"=>$order_info['customer_id']]);

        $jut = "";
        foreach ($address as $jt=> $value) {
            $this->load->model("tb_trade_addr_linkage");
            $addr_str = $this->tb_trade_addr_linkage->implode_detail_address_by_attr($value);
//            var_dump($addr_str);echo(__FILE__.",".__LINE__."<br><br><br>");
//            var_dump($order_info);echo(__FILE__.",".__LINE__."<br><br><br>");
            $country = isset($addr_str['country'])?$addr_str['country']:"";
            if($country.' '.$addr_str['address']==$order_info['address']){
//                var_dump($addr_str);exit(__FILE__.",".__LINE__."<br><br>");
                $jut=$address[$jt];
            }
            $order_info['country']=$value['country'];//取国家代码（用于paypal）
        }
//        exit(__FILE__.",".__LINE__."<BR>");
        $order_info['address2']=$jut;
//        $order_info['goods_list']=$this->db_slave->select('*')->where('order_id',$order_id)->get('trade_orders_goods')->result_array();
        $order_info['goods_list']=$this->tb_trade_orders_goods->get_list("*",['order_id'=>$order_id]);
        return $order_info;
    }
    
    /*
     * 得到会员订单供应商ID
     */
    public function get_order_supplier_id($order_id)  { 
       
       $data = array();
//       $query  =  $this->db->select('order_id,supplier_id')->where('order_id',$order_id)->get('trade_orders_goods');
//       if ($query->num_rows() > 0) {
//           foreach ($query->result() as $row) {
//               $data['supplier_id'] = $row->supplier_id;
//           }
//       }
        $query = $this->tb_trade_orders_goods->get_list('order_id,supplier_id',['order_id'=>$order_id]);
        foreach($query as $k=>$v)
        {
            $data['supplier_id'] = $v['supplier_id'];
        }
        return $data;
        
    }

    /** 修改订单支付方式 */
    public function update_order_payment($order, $payment)
    {
        if($order['order_prop'] == '2'){ /** 拆单 */
//            $splits = $this->db->select('order_id')->where('attach_id',$order['order_id'])->get('trade_orders')->result_array();
            $splits = $this->tb_trade_orders->get_list("order_id",['attach_id'=>$order['order_id']]);
            if($splits)foreach($splits as $split){
                $order_id[]=$split['order_id'];
            }
//            return $this->db->where_in('order_id', $order_id)->update('trade_orders', array('payment_type' => $payment));
            $res =  $this->tb_trade_orders->update_batch(["order_id"=>$order_id],['payment_type' => $payment]);
        }else{ /** 未拆单 */
//            return  $this->db->where('order_id', $order['order_id'])->update('trade_orders', array('payment_type' => $payment));
            $res =  $this->tb_trade_orders->update_one(["order_id"=>$order['order_id']],['payment_type' => $payment]);
        }
        return $res;
    }

    /**
     * 根据参数获取订单列表
     */
    public function get_order_count_by_attr($status = null, $store_code = null, $order_type = null,$order_id = null, $uid = null, $store_id = null,$page_express=null, $tracking_num = null, $start_date = null, $end_date = null,$supplier_ship_id = null,$txn_id = null)
    {
//        $this->db_slave->where_in('order_prop', array('0', '1'));
        $where['order_prop'] = array('0', '1');
        if (isset($status))
        {
//            $this->db_slave->where('status', $status);
            $where['status'] = $status;
        }
        if (isset($order_type))
        {
//            $this->db_slave->where('order_type', $order_type);
            $where['order_type'] = $order_type;
        }
        if (isset($store_code))
        {
//            $this->db_slave->where('shipper_id', $store_code);
            $where['shipper_id'] = $store_code;
        }
        if (isset($order_id))
        {
            if(strtolower(substr($order_id,0,1 ))=='p'){
//                $this->db_slave->where("`order_id` = '$order_id' or `attach_id` = '$order_id'");
                $where["`order_id` = '$order_id' or `attach_id` = '$order_id'"] = NULL;
            }  else {
//                $this->db_slave->where('order_id', $order_id);
                $where['order_id'] = $order_id;
            }
        }
        if (isset($uid))
        {
//            $this->db_slave->where('customer_id', $uid);
            $where['customer_id'] = $uid;
        }
        if (isset($store_id))
        {
//            $this->db_slave->where('shopkeeper_id', $store_id);
            $where['shopkeeper_id'] = $store_id;
        }
        if (isset($tracking_num) || isset($page_express))
        {
//            $this->db_slave->where("MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')", NULL, FALSE);
//            $this->db_slave->where('freight_info', $page_express."|".$tracking_num);
            $where["MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')"] = NULL;
            $where['freight_info'] = $page_express."|".$tracking_num;
        }
        if (isset($start_date))
        {
//            $this->db_slave->where('created_at >=', $start_date);
            $where['created_at >='] = $start_date;
        }
        if (isset($end_date))
        {
//            $this->db_slave->where('created_at <=', $end_date);
            $where['created_at <='] = $end_date;
        }
        if (!empty($supplier_ship_id))
        {
//            $this->db_slave->where_in('shipper_id', $supplier_ship_id); //add by yuan 2015/12/15
            $where['shipper_id'] = $supplier_ship_id;
        }
        if (!empty($txn_id))
        {
//            $this->db_slave->where('txn_id', $txn_id);
            $where['txn_id'] = $txn_id;
        }
//        return $this->db_slave->count_all_results('trade_orders');
        return $this->tb_trade_orders->get_counts($where);
    }


    /**
     * 根据参数获取订单列表 soly
     * 为了不影响其他地方调用 copy一份
     */
    public function get_order_count_by_attrinfo($status = null, $store_code = null, $order_type = null,$order_id = null, $uid = null, $store_id = null,$page_express=null, $tracking_num = null, $start_date = null, $end_date = null,$supplier_ship_id = null,$txn_id = null)
    {

//        $this->db_slave->where_in('order_prop', array('0', '1'));
        $where['order_prop']=array('0', '1');
        if (isset($status))
        {
//            $this->db_slave->where('status', $status);
            $where['status'] = $status;
        }
        if (isset($order_type))
        {
//            $this->db_slave->where('order_type', $order_type);
            $where['order_type'] = $order_type;
        }
        if (isset($store_code))
        {
//            $this->db_slave->where('shipper_id', $store_code);
            $where['shipper_id'] = $store_code;
        }
        if (isset($order_id))
        {
            if(strtolower(substr($order_id,0,1 ))=='p'){
//                $this->db_slave->where("`order_id` = '$order_id' or `attach_id` = '$order_id'");
                $where["`order_id` = '$order_id' or `attach_id` = '$order_id'"] = NULL;
            }  else {
//                $this->db_slave->where('order_id', $order_id);
                $where["order_id"] = $order_id;
            }
        }
        if (isset($uid))
        {
//            $this->db_slave->where('customer_id', $uid);
            $where["customer_id"] = $uid;
        }
        if (isset($store_id))
        {
//            $this->db_slave->where('shopkeeper_id', $store_id);
            $where["shopkeeper_id"] = $store_id;
        }
        if (isset($tracking_num) || isset($page_express))
        {
//             $this->db_slave->where("MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')", NULL, FALSE);
//            $this->db_slave->where('freight_info', $page_express."|".$tracking_num);
            $where["MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')"] = NULL;
            $where["freight_info"] = $page_express."|".$tracking_num;
        }
        if (isset($start_date))
        {
//            $this->db_slave->where('pay_time >=', $start_date);
            $where["pay_time >="] = $start_date;
        }
        if (isset($end_date))
        {
//            $this->db_slave->where('pay_time <=', $end_date);
            $where["pay_time <="] = $end_date;
        }
        if (!empty($supplier_ship_id))
        {
//            $this->db_slave->where_in('shipper_id', $supplier_ship_id); //add by yuan 2015/12/15
            $where["shipper_id"] = $supplier_ship_id;
        }
        if (!empty($txn_id))
        {
//            $this->db_slave->where('txn_id', $txn_id);
            $where["txn_id"] = $txn_id;
        }
//        return $this->db_slave->count_all_results('trade_orders');
        $count =  $this->tb_trade_orders->get_counts($where);
        return $count;
    }

    /* 根据供应商id获取其仓库简码 */
    public function get_store_code_by_supplier_id($supplier_id) {
        return $this->db_slave->from('mall_goods_storehouse')->select('store_code')->where('supplier_id',$supplier_id)->get()->result_array();
    }

    public function get_order_list_by_attr($offset, $limit, $status = null, $store_code = null, $order_type = null, $order_id = null, $uid = null, $store_id = null, $page_express=null,$tracking_num = null, $start_date = null, $end_date = null,$supplier_ship_id=null,$txn_id=null,$role=null)
    {
//        $this->db_slave->select('is_doba_order,order_id, customer_id, shopkeeper_id, remark, currency, goods_amount_usd, deliver_fee_usd, expect_deliver_date, status,address,consignee,ID_no,ID_front,ID_reverse,order_type,pay_time');
        $select = 'is_doba_order,order_id, customer_id, shopkeeper_id, remark, currency, goods_amount_usd, 
        deliver_fee_usd, expect_deliver_date, status,address,consignee,ID_no,ID_front,ID_reverse,order_type,pay_time';
//        $this->db_slave->where_in('order_prop', array('0', '1'));
        $where['order_prop'] = array('0', '1');
        if (isset($status))
        {
//            $this->db_slave->where('status', $status);
            $where['status'] = $status;
        }
        if (isset($store_code))
        {
//            $this->db_slave->where('shipper_id', $store_code);
            $where['shipper_id'] = $store_code;
        }
        if (isset($uid))
        {
//            $this->db_slave->where('customer_id', $uid);
            $where["customer_id"] = $uid;
        }
        if (isset($order_type))
        {
//            $this->db_slave->where('order_type', $order_type);
            $where['order_type'] = $order_type;
        }
        if (isset($order_id))
        {
            if(strtolower(substr($order_id,0,1 ))=='p' || strtolower(substr($order_id,0,1 ))=='m'){
//                $this->db_slave->where("`order_id` = '$order_id' or `attach_id` = '$order_id'");
                $where["`order_id` = '$order_id' or `attach_id` = '$order_id'"] = NULL;
            }  else {
//                $this->db_slave->where('order_id', $order_id);
                $where["order_id"] = $order_id;
            }
        }
        if (isset($store_id))
        {
//            $this->db_slave->where('shopkeeper_id', $store_id);
            $where["shopkeeper_id"] = $store_id;
        }
        if (isset($tracking_num) || isset($page_express))
        {
//            $this->db_slave->where("MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')", NULL, FALSE);
//            $this->db_slave->where('freight_info', $page_express."|".$tracking_num);
            $where["MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')"] =NULL;
            $where["freight_info"] = $page_express."|".$tracking_num;
        }
        if (isset($start_date))
        {
//            $this->db_slave->where('created_at >=', $start_date);
            $where["created_at >="] = $start_date;
        }
        if (isset($end_date))
        {
//            $this->db_slave->where('created_at <=', $end_date);
            $where["created_at <="] = $end_date;
        }
        if (!empty($supplier_ship_id))
        {
//            $this->db_slave->where_in('shipper_id', $supplier_ship_id); //add by yuan 2015/12/15
            $where["shipper_id"] = $supplier_ship_id;
        }
        if (!empty($txn_id))
        {
//            $this->db_slave->where('txn_id', $txn_id);
            $where["txn_id"] = $txn_id;
        }
        if (!empty($role)&&$role==6)
        {
//            $this->db_slave->where('area', '410');
            $where["area"] = '410';
        }
        if (!empty($role)&&$role==7)
        {
//            $this->db_slave->where('area', '344');
            $where["area"] = '344';
        }

//        $this->db_slave->order_by('created_at', 'DESC');
        $order_by['created_at']='DESC';
//        $this->db_slave->limit($limit, $offset);
//        $list = $this->db_slave->get('trade_orders')->result_array();
        $list = $this->tb_trade_orders->get_list_auto([
           "select"=>$select,
            "where"=>$where,
            "order_by"=>$order_by,
            "page_size"=>$limit,
            "page_index"=>$offset
        ]);
        //echo $this->db_slave->last_query();exit;
        foreach ($list as $k => $v)
        {
            //如果是doba订单，显示doba订单号以便美国客户对账查询
            $list[$k]['doba_order_label']='No doba order id.';
            $list[$k]['doba_order_id']='';
            if($v['is_doba_order']) {
                $doba_order_info=$this->db_slave->select('order_id_doba')->where('order_id',$v['order_id'])->get('trade_orders_doba_order_info')->row_array();
                if(!empty($doba_order_info)) {
                    $list[$k]['doba_order_label']='Doba order id is: ';
                    $list[$k]['doba_order_id']=$doba_order_info['order_id_doba'];
                }
            }
            // 顾客
            $user_info = $this->db_slave->select('name')->where('id', $v['customer_id'])->get('users')->row_array();
            if (empty($user_info['name']))
            {
                $list[$k]['customer'] = $v['customer_id'];
            }
            else
            {
                $list[$k]['customer'] = $user_info['name'];
            }

            // 店铺 id
            if ($v['shopkeeper_id'] == 0)
            {
                $list[$k]['shopkeeper_id'] = "mall";
            }

            // 商品总计
            $list[$k]['goods_amount_show'] = "USD ".number_format($v['goods_amount_usd'] / 100, 2);

            // 运费
            $list[$k]['deliver_fee_show'] = "USD ".number_format($v['deliver_fee_usd'] / 100, 2);

            // 判断是否拥有备注，包括订单备注和系统备注
            if (empty($v['order_id']))
            {
                $remark_num = 0;
            }
            else
            {
                //$remark_num = $this->db_slave->having('order_id', $v['order_id'])->get('trade_order_remark_record')->num_rows();
                //$remark_num = $this->db_slave->where('order_id', $v['order_id'])->get('trade_order_remark_record')->num_rows();
                $sql = "select count(0) as count from trade_order_remark_record where order_id =  '".$v['order_id']."'";
                $count_arr = $this->db_slave->query($sql)->row_array();
                $remark_num = $count_arr['count'];

            }
            if (!empty($v['remark']))
            {
                $remark_num++;
            }
            $list[$k]['having_remark'] = $remark_num > 0 ? 1 : 0;

            $list[$k]['address'] = $v['address'];
        }

        return $list;
    }


    /**
     * 根据参数获取订单列表 soly
     * 为了不影响其他地方调用 copy一份
     */
    public function get_order_list_by_attrinfo($offset, $limit, $status = null, $store_code = null, $order_type = null, $order_id = null, $uid = null, $store_id = null, $page_express=null,$tracking_num = null, $start_date = null, $end_date = null,$supplier_ship_id=null,$txn_id=null,$role=null)
    {
//        $this->db_slave->select('is_doba_order,order_id, customer_id, shopkeeper_id, remark, currency, goods_amount_usd, deliver_fee_usd, expect_deliver_date, status,address,consignee,ID_no,ID_front,ID_reverse,order_type,pay_time');
        $select = 'is_doba_order,order_id, customer_id, shopkeeper_id, remark, currency, goods_amount_usd, 
        deliver_fee_usd, expect_deliver_date, status,address,consignee,ID_no,ID_front,ID_reverse,order_type,pay_time';
//        $this->db_slave->where_in('order_prop', array('0', '1'));
        $where['order_prop']=array('0', '1');
        if (isset($status))
        {
//            $this->db_slave->where('status', $status);
            $where['status']=$status;
        }
        if (isset($store_code))
        {
//            $this->db_slave->where('shipper_id', $store_code);
            $where['shipper_id']=$store_code;
        }
        if (isset($uid))
        {
//            $this->db_slave->where('customer_id', $uid);
            $where['customer_id']=$uid;
        }
        if (isset($order_type))
        {
//            $this->db_slave->where('order_type', $order_type);
            $where['order_type']=$order_type;
        }
        if (isset($order_id))
        {
            if(strtolower(substr($order_id,0,1 ))=='p' || strtolower(substr($order_id,0,1 ))=='m'){
//                $this->db_slave->where("`order_id` = '$order_id' or `attach_id` = '$order_id'");
                $where["`order_id` = '$order_id' or `attach_id` = '$order_id'"]=NULL;
            }  else {
//                $this->db_slave->where('order_id', $order_id);
                $where['order_id']=$order_id;
            }
        }
        if (isset($store_id))
        {
//            $this->db_slave->where('shopkeeper_id', $store_id);
            $where['shopkeeper_id']=$store_id;
        }
        if (isset($tracking_num) || isset($page_express))
        {
//            $this->db_slave->where("MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')", NULL, FALSE);
//            $this->db_slave->where('freight_info', $page_express."|".$tracking_num);
            $where["MATCH (freight_info) AGAINST ('{$page_express}|{$tracking_num}')"]=NULL;
            $where['freight_info']=$page_express."|".$tracking_num;
        }
        if (isset($start_date))
        {
//            $this->db_slave->where('pay_time >=', $start_date);
            $where['pay_time >='] = $start_date;
        }
        if (isset($end_date))
        {
//            $this->db_slave->where('pay_time <=', $end_date);
            $where['pay_time <='] = $end_date;
        }
        if (!empty($supplier_ship_id))
        {
//            $this->db_slave->where_in('shipper_id', $supplier_ship_id); //add by yuan 2015/12/15
            $where['shipper_id'] = $supplier_ship_id;
        }
        if (!empty($txn_id))
        {
//            $this->db_slave->where('txn_id', $txn_id);
            $where['txn_id'] = $txn_id;
        }
        if (!empty($role)&&$role==6)
        {
//            $this->db_slave->where('area', '410');
            $where['area'] = '410';
        }
        if (!empty($role)&&$role==7)
        {
//            $this->db_slave->where('area', '344');
            $where['area'] = '344';
        }

//        $this->db_slave->order_by('created_at', 'DESC');
//        $this->db_slave->limit($limit, $offset);
//        $list = $this->db_slave->get('trade_orders')->result_array();
        $list = $this->tb_trade_orders->get_list_auto([
            "select"=>$select,
            "where"=>$where,
            "order_by"=>['created_at'=>'DESC'],
            "page_size"=>$limit,
            "page_index"=>$offset
        ]);

        foreach ($list as $k => $v)
        {
            //如果是doba订单，显示doba订单号以便美国客户对账查询
            $list[$k]['doba_order_label']='No doba order id.';
            $list[$k]['doba_order_id']='';
            if($v['is_doba_order']) {
                $doba_order_info=$this->db_slave->select('order_id_doba')->where('order_id',$v['order_id'])->get('trade_orders_doba_order_info')->row_array();
                if(!empty($doba_order_info)) {
                    $list[$k]['doba_order_label']='Doba order id is: ';
                    $list[$k]['doba_order_id']=$doba_order_info['order_id_doba'];
                }
            }
            // 顾客
            $user_info = $this->db_slave->select('name')->where('id', $v['customer_id'])->get('users')->row_array();
            if (empty($user_info['name']))
            {
                $list[$k]['customer'] = $v['customer_id'];
            }
            else
            {
                $list[$k]['customer'] = $user_info['name'];
            }

            // 店铺 id
            if ($v['shopkeeper_id'] == 0)
            {
                $list[$k]['shopkeeper_id'] = "mall";
            }

            // 商品总计
            $list[$k]['goods_amount_show'] = "USD ".number_format($v['goods_amount_usd'] / 100, 2);

            // 运费
            $list[$k]['deliver_fee_show'] = "USD ".number_format($v['deliver_fee_usd'] / 100, 2);

            // 判断是否拥有备注，包括订单备注和系统备注
            if (empty($v['order_id']))
            {
                $remark_num = 0;
            }
            else
            {
                //$remark_num = $this->db_slave->having('order_id', $v['order_id'])->get('trade_order_remark_record')->num_rows();
                //$remark_num = $this->db_slave->where('order_id', $v['order_id'])->get('trade_order_remark_record')->num_rows();
                $sql = "select count(0) as count from trade_order_remark_record where order_id =  '".$v['order_id']."'";
                $count_arr = $this->db_slave->query($sql)->row_array();
                $remark_num = $count_arr['count'];

            }
            if (!empty($v['remark']))
            {
                $remark_num++;
            }
            $list[$k]['having_remark'] = $remark_num > 0 ? 1 : 0;

            $list[$k]['address'] = $v['address'];
        }

        return $list;
    }

    /**
     * 扫描超过 24 小时未支付的订单，状态改为取消，每十分钟执行一次
     */
    public function scan_order_payment_timeout()
    {
        $this->load->model('m_group');
        $this->load->model('m_erp');
        $now = time();
        $cut_time = date('y-m-d H:i:s', $now - 7200);//2小时
//        $cut_time = date('y-m-d H:i:s', $now - 86400);//24小时

        // 获取创建时间超过一天且状态小于 3(等待发货) 的订单
//        $timeout_list = $this->db
//            ->select('order_id')
//            ->where('created_at <', $cut_time)
//            ->where('status', "2")
//            ->get('trade_orders')
//            ->result_array();
        $timeout_list = $this->tb_trade_orders->get_list_auto([
            "select"=>"order_id",
            "where"=>['created_at <'=>$cut_time,'status'=>"2"],
//            "page_size"=>10,
        ]);
//        var_dump($timeout_list);
        if (empty($timeout_list))
        {
            return TRUE;
        }

        $order_id_arr = array();
        $order_id_str = '';
        $count = count($timeout_list);
        foreach ($timeout_list as $key=>$v)
        {
            $order_id_arr[] = $v['order_id'];
            if($count == $key+1){
                $order_id_str .= '"'.$v['order_id'].'"';
            }else{
                $order_id_str .= '"'.$v['order_id'].'",';
            }
        }

        // 记录流水 todo
        $this->db->trans_start();
//        $this->db->query("SELECT order_id FROM `trade_orders` WHERE `order_id` IN({$order_id_str}) FOR UPDATE");
        $this->tb_trade_orders->lock_batch_trade_orders($order_id_str);
//        $this->db->where_in('order_id', $order_id_arr)->update('trade_orders', array('status' => Order_enum::STATUS_CANCEL));
        $this->tb_trade_orders->update_batch(["order_id"=>$order_id_arr],array('status' => Order_enum::STATUS_CANCEL));

        //同步到erp(2小时未付款自动取消)
        foreach($order_id_arr as $order_id) {
            if(empty($order_id)){
                continue;
            }
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = Order_enum::STATUS_CANCEL;
            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

            //获取商品数据
//            $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$order_id)->from('trade_orders_goods')->get()->result_array();
            $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                ["order_id"=>$order_id]);
            $goods_all = array();
            foreach($goods as $item){
                $goods_all[] = $item;
            }

            //取消订单加库存，同步到ERP
            $this->m_group->add_goods_number($goods_all, $order_id);
            //记录流水
            $this->add_order_log($order_id,105,'2小时未支付订单,系统自动取消!',0);
            echo($order_id."\n");
        }
//        $this->m_group->add_goods_number($goods_all);
        $this->db->trans_complete();

        echo("return true\n");
        return TRUE;
    }

    /**
     * 扫描发货时间超过一个月，状态仍然是等待收货的，状态改为等待评价
     */
    public function scan_order_cargo_receive_timeout()
    {
        $this->load->model('m_erp');
        $cut_time = date('y-m-d H:i:s', strtotime('-1 month'));

//        $timeout_list = $this->db
//            ->select('order_id')
//            ->where('deliver_time <', $cut_time)
//            ->where('status', "4")
//            ->get('trade_orders')
//            ->result_array();
        $timeout_list = $this->tb_trade_orders->get_list("order_id",[
            'deliver_time <'=>$cut_time,
            'status'=>"4",
        ]);
        if (empty($timeout_list))
        {
            return TRUE;
        }

        $order_id_arr = array();
        $order_id_str = '';
        $count = count($timeout_list);
        foreach ($timeout_list as $key=>$v)
        {
            $order_id_arr[] = $v['order_id'];
            if($count == $key+1){
                $order_id_str .= '"'.$v['order_id'].'"';
            }else{
                $order_id_str .= '"'.$v['order_id'].'",';
            }
        }

        $update_attr = array(
            'status' => Order_enum::STATUS_EVALUATION,
            'receive_time' => date('Y-m-d H:i:s'),
        );

        // 记录流水 todo
        $this->db->trans_start();

//        $this->db->query("SELECT order_id FROM `trade_orders` WHERE `order_id` IN({$order_id_str}) FOR UPDATE");
        $this->tb_trade_orders->lock_batch_trade_orders($order_id_str);
//        $this->db->where_in('order_id', $order_id_arr)->update('trade_orders', $update_attr);
        $this->tb_trade_orders->update_batch(["order_id"=>$order_id_arr],$update_attr);

        //同步到erp(发货30天，状态变更成等待评价)
        foreach($order_id_arr as $order_id) {
            if(empty($order_id)){
                continue;
            }
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = Order_enum::STATUS_EVALUATION;
            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
        }

        $this->db->trans_complete();

        return TRUE;
    }

    /**
     * 收到货物超过一个星期，状态仍然是等待评价的，状态改为已完成
     */
    public function scan_order_estimate_timeout()
    {

        $this->load->model('m_erp');
        $cut_time = date('y-m-d H:i:s', strtotime('-1 week'));

//        $timeout_list = $this->db
//            ->select('order_id')
//            ->where('receive_time <', $cut_time)
//            ->where('status', "5")
//            ->get('trade_orders')
//            ->result_array();
        $timeout_list = $this->tb_trade_orders->get_list("order_id",[
            'receive_time <'=>$cut_time,
            'status'=>"5"
        ]);
        if (empty($timeout_list))
        {
            return false;
        }

        $order_id_arr = array();
        $order_id_str = '';
        $count = count($timeout_list);
        foreach ($timeout_list as $key=>$v)
        {
            $order_id_arr[] = $v['order_id'];
            if($count == $key+1){
                $order_id_str .= '"'.$v['order_id'].'"';
            }else{
                $order_id_str .= '"'.$v['order_id'].'",';
            }
        }

        $update_attr = array(
            'status' => Order_enum::STATUS_COMPLETE,
        );

        // 记录流水 todo

        $this->db->trans_start();

//        $this->db->query("SELECT order_id FROM `trade_orders` WHERE `order_id` IN({$order_id_str}) FOR UPDATE");
        $this->tb_trade_orders->lock_batch_trade_orders($order_id_str);
//        $this->db->where_in('order_id', $order_id_arr)->update('trade_orders', $update_attr);
        $this->tb_trade_orders->update_batch(["order_id"=>$order_id_arr],$update_attr);

        //同步到erp(收到货物超过一个星期，状态仍然是等待评价的，状态改为已完成)
        foreach($order_id_arr as $order_id) {
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = Order_enum::STATUS_COMPLETE;
            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
        }

        $this->db->trans_complete();
        return TRUE;
    }

    /**
     * 根据订单 id 获取打印电子收据的 html 代码，供生成 pdf 使用
     */
    public function get_order_receipt_html($order_id, $lang_id, $uid)
    {
        $view_data = array();
        $uri = "ucenter/receipt";

        // 获取订单信息
//        $order_info = $this->db
//            ->select("order_id, customer_id, phone, reserve_num, address, goods_list, discount_type, goods_amount_usd, deliver_fee_usd, discount_amount_usd, order_amount_usd, payment_type, created_at, status")
//            ->where("order_id", $order_id)
//            ->get("trade_orders")
//            ->row_array();
        $order_info = $this->tb_trade_orders->get_one_auto([
           "select"=>"order_id, customer_id, phone, reserve_num, address, goods_list, discount_type, goods_amount_usd, 
           deliver_fee_usd, discount_amount_usd, order_amount_usd, payment_type, created_at, status",
            "where"=>["order_id"=>$order_id]
        ]);
        if (empty($order_info))
        {
            $view_data['err_msg'] = "order is not exist";
            $uri = 'ucenter/receipt_error';
            goto end;
        }

        // 只允许查询自己的订单
        if ($uid != $order_info['customer_id'])
        {
            $view_data['err_msg'] = "member info error";
            $uri = 'ucenter/receipt_error';
            goto end;
        }

        // 判断订单能不能生成收据，支付后才允许出收据
        if (!in_array($order_info['status'],array('1','3','4','5','6')))
        {
            $view_data['err_msg'] = "order pay status error";
            $uri = 'ucenter/receipt_error';
            goto end;
        }

        // 购买日期
        $order_info['purchase_date'] = date("j-M-Y", strtotime($order_info['created_at']));

        // 获取用户店铺等级
        $month_fee_map = array(
            1 => 'diamond',
            2 => 'gold',
            3 => 'silver',
            4 => 'free',
            5 => 'bronze',
        );
        $user_info = $this->db
            ->select("user_rank")
            ->where("id", $order_info['customer_id'])
            ->get("users")
            ->row_array();
        if (empty($user_info) || !isset($month_fee_map[$user_info['user_rank']]))
        {
            $view_data['err_msg'] = "member info error";
            $uri = 'ucenter/receipt_error';
            goto end;
        }
        $order_info['month_fee'] = lang($month_fee_map[$user_info['user_rank']]);

        // 顾客电话
        $order_info['user_phone'] = isset($order_info['phone']) ? $order_info['phone'] : $order_info['reserve_num'];

        // 商品列表
        $goods = array();
        /*$goods_arr = explode("$", $order_info['goods_list']);
        foreach ($goods_arr as $v)
        {
            list($goods_sn, $qty) = explode(":", $v);

            $goods_info = $this->db
                ->select("goods_sn_main, price")
                ->where("goods_sn", $goods_sn)
                ->where("language_id", $lang_id)
                ->get("mall_goods")
                ->row_array();
            if (empty($goods_info))
            {
                log_message('error', "[get_order_receipt_html] goods is not exist, sn: {$goods_sn}");
                continue;
            }

            $goods_main_info = $this->db
                ->select("goods_name")
                ->where("goods_sn_main", $goods_info['goods_sn_main'])
                ->where("language_id", $lang_id)
                ->get("mall_goods_main")
                ->row_array();
            if (empty($goods_main_info))
            {
                log_message('error', "[get_order_receipt_html] goods main is not exist, main sn: {$goods_info['goods_sn_main']}");
                continue;
            }

            $goods[] = array(
                'name' => $goods_main_info['goods_name'],
                'price' => $goods_info['price'],
                'qty' => $qty,
                'amount' => number_format($goods_info['price'] * $qty, 2, ".", ""),
            );
        }*/

//        $goods_arr = $this->db->where('order_id',$order_id)->get('trade_orders_goods')->result_array();
        $goods_arr = $this->tb_trade_orders_goods->get_list("*",["order_id"=>$order_id]);
        //是否是医疗商品
        $is_sj = 0;
        foreach ($goods_arr as $v)
        {
            $goods[] = array(
                'name' => $v['goods_name'],
                'price' => $v['goods_price'],
                'qty' => $v['goods_number'],
                'amount' => number_format($v['goods_price'] * $v['goods_number'], 2, ".", ""),
            );
            if($v['cate_id'] == '1374'){
                $is_sj = 1;
            }
        }
        $order_info['is_sj'] = $is_sj;

        if ($order_info['discount_type'] == 1)
        {
            $coupons_amount = number_format($order_info['discount_amount_usd'] / 100, 2, ".", "");
            // 订单中有获取代品券
            $goods[] = array(
                'name' => lang('order_receipt_coupons'),
                'price' => $coupons_amount,
                'qty' => 1,
                'amount' => $coupons_amount,
            );
        }
        $order_info['goods'] = $goods;

        // 总计
        $order_info['goods_amount_show'] = number_format($order_info['goods_amount_usd'] / 100, 2, ".", "");
        $order_info['deliver_fee_show'] = number_format($order_info['deliver_fee_usd'] / 100, 2, ".", "");
        $order_info['discount_amount_show'] = number_format($order_info['discount_amount_usd'] / 100, 2, ".", "");
        $order_info['order_amount_show'] = number_format($order_info['order_amount_usd'] / 100, 2, ".", "");

        // 支付方式
        $pay_map = array(
            0 => "",
            1 => "admin_order_payment_group",
            2 => "admin_order_payment_coupon",
            105 => "admin_order_payment_alipay",
            106 => "admin_order_payment_unionpay",
            107 => "admin_order_payment_paypal",
            108 => "admin_order_payment_ewallet",
            109 => "admin_order_payment_yspay",
            110 => "admin_order_payment_amount",
        );
        $order_info['payment'] = $pay_map[$order_info['payment_type']];

        $view_data = $order_info;

        // 输出 html
        end:
        ob_start();

        $this->load->view($uri, $view_data);

        $html = ob_get_contents();

        ob_end_clean();

        return $html;
    }

    /**
     * 通过order_id判断是否生成收据文件
     */
    public function is_receipt($order_id){
//        $row = $this->db->select('need_receipt')->where('order_id',$order_id)->get('trade_orders')->row_array();
        $row = $this->tb_trade_orders->get_one("need_receipt",["order_id"=>$order_id]);
        if(!empty($row) && $row['need_receipt']){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /** 訂單是否鎖定 */
    public function is_locked($order_id,$admin_id){
        return false;//都有权限取消了
        if(in_array($admin_id,array(1,3,5,96,66,35,9))){
            return FALSE;
        }
//        $row = $this->db_slave->select('is_export_lock')->where('order_id',$order_id)->get('trade_orders')->row_array();
        $row = $this->tb_trade_orders->get_one("is_export_lock",["order_id"=>$order_id]);
        if(!empty($row) && $row['is_export_lock'] == 1){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /** 獲取下單人的名稱 */
    public function get_customer_name($uid){
        $row = $this->db_slave->select('name')->where('id',$uid)->get('users')->row_array();
        if(!empty($row) && $row['name']){
            return $row['name'];
        }else{
            return '';
        }
    }

    /* 检查是否订单已经存在物流单号  */
    function check_shipping_status($order_id)
    {
//        $rs = $this->db->select('freight_info')->where('order_id', $order_id)->get('trade_orders')->row_array();
        $rs = $this->tb_trade_orders->get_one("freight_info",["order_id"=>$order_id]);
        return empty($rs['freight_info']) ? false : true;
    }

    /****************************** 订单备注 ******************************/

    /**
     * 根据订单 id 获取用户可见备注信息
     */
    public function get_order_customer_data($order_id)
    {
        return $this->db
            ->select("remark, created_at")
            ->where('order_id', $order_id)
            ->where('type', '2')
            ->get('trade_order_remark_record')
            ->result_array();
    }

    /**
     * 根据订单 id 获取备注信息
     */
    public function get_order_remark_data($order_id)
    {
        $data = array(
            'code' => 0,
            'customer' => array(),
            'system' => array(),
        );

        // 查询订单是否存在
//        $order_info = $this->db_slave
//            ->select('order_id')
//            ->where('order_id', $order_id)
//            ->get('trade_orders')
//            ->row_array();
        $order_info = $this->tb_trade_orders->get_one("order_id",["order_id"=>$order_id]);
        if (empty($order_info))
        {
            $data['code'] = 103;
            return $data;
        }

        $remark_data = $this->db_slave
            ->select("type, remark, admin_id, created_at, operator")
            ->where('order_id', $order_id)
            ->get('trade_order_remark_record')
            ->result_array();

        $admin_user_map = array();
        $customer = array();
        $system = array();
        foreach ($remark_data as $v)
        {
            if (isset($admin_user_map[$v['admin_id']]))
            {
                $admin_user = $admin_user_map[$v['admin_id']];
            }
            else
            {
                $admin_user_info = $this->db_slave
                    ->select("email")
                    ->where('id', $v['admin_id'])
                    ->get('admin_users')
                    ->row_array();
                if (empty($admin_user_info))
                {
                    //系统自动备注
                    if($v['admin_id'] == 0){
                        $admin_user = $v['operator'];
                    }else{
                        $admin_user = "unknow user, id: {$v['admin_id']}";
                    }
                }
                else
                {
                    $admin_user_map[$v['admin_id']] = $admin_user_info['email'];
                    $admin_user = $admin_user_info['email'];
                }
            }

            switch ($v['type'])
            {
                case 1:
                    $system[] = array(
                        'remark' => $v['remark'],
                        'admin_user' => $admin_user,
                        'created_at' => $v['created_at'],
                    );
                    break;

                case 2:
                    $customer[] = array(
                        'remark' => $v['remark'],
                        'admin_user' => $admin_user,
                        'created_at' => $v['created_at'],
                    );
                    break;

                default:
                    log_message("[get_order_remark_data] unknow type: {$v['type']}, order: {$order_id}");
                    break;
            }
        }

        $data['customer'] = $customer;
        $data['system'] = $system;
        return $data;
    }

    /**
     * 添加订单备注记录
     */
    public function add_order_remark_record($attr)
    {
        // 查询订单是否存在
//        $order_info = $this->db
//            ->select('order_id')
//            ->where('order_id', $attr['order_id'])
//            ->get('trade_orders');
//        if ($order_info) {
//        	$order_info = $order_info->row_array();
//        }
        $order_info = $this->tb_trade_orders->get_one("order_id",["order_id"=>$attr['order_id']]);
        if (empty($order_info))
        {
            return false;
        }
        $this->db->insert('trade_order_remark_record', $attr);
        return true;
    }

    /****************************** 物流 ******************************/

    /**
     * 添加物流公司
     */
    public function add_freight($data)
    {
        return $this->db->insert('trade_freight', $data);
    }

    /**
     * 修改物流公司
     */
    public function edit_freight($code, $data)
    {
        return $this->db->where('company_code', $code)->update('trade_freight', $data);

    }

    /**
     * 获取物流公司列表
     */
    public function get_freight_list()
    {
        return $this->db_slave->select('company_code, company_name')->get('trade_freight')->result_array();
    }

    /**
     * 根据运费规则和重量计算运费，单位分
     */
    public function get_shipping_fee($addr, $type, $weight,$store_location_code,$goods_arr, $size=0,$location_id=840)
    {
        switch ($type)
        {
            case 1:// 首重 1kg ，每增加 1kg 续重一次 中国地区
                $fee = $this->get_shipping_fee_type_1($addr,$weight,$store_location_code,$goods_arr,$location_id);
                $currency = 'CNY';
                break;
            case 2: // 香港地区
                $fee = $this->get_shipping_fee_type_2($addr,$weight,$size,$goods_arr);
                $currency = 'HKD';
                break;
            case 3: // 韩国地区
                $fee = $this->get_shipping_fee_type_3($addr,$goods_arr);
                $currency = 'KRW';
                break;
            case 4: // 美国地区
                $fee = $this->get_shipping_fee_type_4($addr,$goods_arr,$location_id);
                $currency = 'USD';
                break;
            default:
                return FALSE;
        }
        if($fee === false){
            return false;
        }
//        $res = $this->db_slave->select('rate')->from('exchange_rate')->where('currency', $currency)->get()->row_array();
        $this->load->model("tb_exchange_rate");
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate","where"=>['currency'=>$currency]]);
        return bcdiv($fee,$res['rate'],2);
    }

    /** 中國運費 */
    private function get_shipping_fee_type_1($addr, $weight ,$store_location_code,$goods_arr,$location_id_m)
    {
        $addr_lv2 = isset($addr['addr_lv2']) ? $addr['addr_lv2'] : null;

        //特定省份不发货
        foreach($goods_arr as $item){
            $res = $this->db_slave->select('id')->where('dest_code',$addr_lv2)
                ->where('goods_sn_main',$item['goods_sn_main'])
                ->where('start_weight_fee <',0)
                ->get('trade_freight_fee')->row_array();

            if(!empty($res)){
                return false;
            }
        }

        $country_id = $addr['country'];
        if($addr['country'] == 156 && $addr_lv2 == 81){
            $country_id = 344;
        }
        //过滤跨区运费
        foreach($goods_arr as $k => $goods) {
            $international_freight = $this->db->select("freight_fee")
                ->where("goods_sn_main", $goods['goods_sn_main'])
                ->where('country_id',$country_id)
                ->get("trade_freight_fee_international")->row_array();
            if(!empty($international_freight)){
                unset($goods_arr[$k]);
            }
        }
        //goods_arr为空数组,说明都是跨区商品
        if($goods_arr == array()){
            return 0;
        }
        //-----------------------------------------------------------------------------
        $location_id = $this->session->userdata('location_id');
        $cur_language_id = get_cookie('curLan_id', true);

        //手机端通过数据库读取
        if(empty($location_id) || empty($cur_language_id)){
            $this->load->model('tb_language');
            $location_id = $location_id_m;
            $cur_language_id = $this->tb_language->get_language_by_location($location_id);
        }

        $goods_type = array();
        $goods_type['spc_count'] = array();
        $goods_type['general_count'] = array();
        foreach($goods_arr as $goods){

            /** 免邮商品 */
            //leon 2016-12-22 取消like的使用
            //$row = $this->db->select('is_free_shipping')->where('goods_sn_main',$goods['goods_sn_main'])->where('language_id',$cur_language_id)->where("sale_country like '%$location_id%'")->get('mall_goods_main')->row_array();
            $row = $this->db_slave->select('is_free_shipping')->where('goods_sn_main',$goods['goods_sn_main'])->where('language_id',$cur_language_id)->where('sale_country',$location_id)->get('mall_goods_main')->row_array();

            if($row && $row['is_free_shipping'] == 1){
                $goods_type['general_count'][$goods['goods_sn_main']]['goods_sn_main'] = $goods['goods_sn_main'];
                continue;
            }

            /** 先检测特定goods_sn的运费 */
            $spc_count = $this->db->from('trade_freight_fee')->where('begin_code',$store_location_code)
                ->where('goods_sn_main',$goods['goods_sn_main'])->where('dest_code',$addr_lv2)->where('country_code',156)
                ->count_all_results();
            if($spc_count){
                $goods_type['spc_count'][$goods['goods_sn_main']]['goods_sn_main'] = $goods['goods_sn_main'];
                $goods_type['spc_count'][$goods['goods_sn_main']]['goods_number'] = isset($goods['quantity']) ? $goods['quantity'] : $goods['goods_number'];
                continue;
            }

            /** 区域的运费 */
            $general_count = $this->db_slave->from('trade_freight_fee')->where('begin_code',$store_location_code)
                ->where('dest_code',$addr_lv2)->where('country_code',156)->count_all_results();
            if($general_count){
                $goods_type['general_count'][$goods['goods_sn_main']]['goods_sn_main'] = $goods['goods_sn_main'];
                continue;
            }else{
                return FALSE;/** 2個類型的運費都不存在，無法配送 */
            }
        }
        $spc_amount = 0;
        if($goods_type['spc_count'])foreach($goods_type['spc_count'] as $spc_goods){
            /** 特定goods_sn的运费 相加计算运费 */
            $spc_fee = $this->db_slave->select('start_weight_fee,add_weight_fee')->where('begin_code',$store_location_code)
                ->where('goods_sn_main',$spc_goods['goods_sn_main'])->where('dest_code',$addr_lv2)->where('country_code',156)
                ->get('trade_freight_fee')->row_array();
            $tmp_amount = $spc_fee['start_weight_fee'];

            /** 在同一发货商下如果物品重量大于２公斤，多个物品运费相加，如果小于２公斤，选择其中的最高的运费　*/
            $goods_weight_2 = $this->db_slave->select("goods_weight")->where("goods_sn_main", $spc_goods['goods_sn_main'])
                ->where("language_id", $cur_language_id)->get("mall_goods_main")->row_array();
            if (isset($goods_weight_2['goods_weight']) && $goods_weight_2['goods_weight'] >= 2){
                $spc_amount += $spc_fee['start_weight_fee'] * $spc_goods['goods_number'];
            }else{
                $spc_amount = $spc_amount > $tmp_amount ? $spc_amount : $tmp_amount;
            }

        }

        $general_amount = 0;
        $general_weight = 0;
        if($goods_type['general_count']){

            if($goods_type['spc_count']){ /** 如果有特定产品的运费,重新计算普通产品的重量 */
                foreach($goods_type['general_count'] as $general_goods){
                    /** 普通产品的运费  按照重量 */
                    $goods_main_info = $this->db_slave->select("goods_weight,is_free_shipping")->where("goods_sn_main", $general_goods['goods_sn_main'])
                        ->where("language_id", $cur_language_id)->get("mall_goods_main")->row_array();
                    if (empty($goods_main_info)) return FALSE;
                    $goods_weight = $goods_main_info['is_free_shipping'] == 1 ? 0 : $goods_main_info['goods_weight']*1000;
                    $general_weight += $goods_weight;
                }
            }else{
                $general_weight = $weight;
            }

            /** 区域的运费 */
            $weight_fee = $this->db_slave->select('start_weight_fee,add_weight_fee')->where('begin_code',$store_location_code)
                ->where('dest_code',$addr_lv2)->where('country_code',156)->get('trade_freight_fee')->row_array();

            if ($general_weight != 0)
            {
                // 首重
                $general_amount += $weight_fee['start_weight_fee'];
                $general_weight -= 1000;

                // 续重
                while ($general_weight > 0)
                {
                    $general_amount += $weight_fee['add_weight_fee'];
                    $general_weight -= 1000;
                }
            }
        }

        $amount = $general_amount > $spc_amount ? $general_amount : $spc_amount;

        return $amount;
    }

    /** 香港運費 */
    private function get_shipping_fee_type_2($addr,$weight,$size,$goods_arr)
    {
        //过滤跨区运费
        foreach($goods_arr as $k => $goods) {
            $international_freight = $this->db_slave->select("freight_fee")
                ->where("goods_sn_main", $goods['goods_sn_main'])
                ->where('country_id',$addr['country'])
                ->get("trade_freight_fee_international")->row_array();

            if(!empty($international_freight)){
                unset($goods_arr[$k]);
            }
        }
        //goods_arr为空数组,说明都是跨区商品
        if($goods_arr == array()){
            return 0;
        }
        //-----------------------------------------------------------------------------

        $country = $addr['country'];
        $addr_lv2 = isset($addr['addr_lv2']) ? $addr['addr_lv2'] : null;
        $addr_lv3 = isset($addr['addr_lv3']) ? $addr['addr_lv3'] : null;
        $addr_lv4 = isset($addr['addr_lv4']) ? $addr['addr_lv4'] : null;
        //$addr_lv5 = isset($addr['addr_lv5']) ? $addr['addr_lv5'] : null;

        if($country != 156){ //必須是中國香港 且是服務區
            return FALSE;
        }
        if($addr_lv2 != 81){
            return FALSE;
        }
        $exception = array(810363,810364,810365,810366,810367,810368,810369,810370,810371,810372,
            810373,810374,810375,810376,810377,810378,810379,810380
        );
        if(in_array($addr_lv4,$exception)){ //非服務區不配送
            return FALSE;
        }

        if($weight == 0 && $size == 0){ //商品全部包邮
            return 0;
        }

        if($addr_lv4 == '810299' || $addr_lv3 == '8104'){ //自提
            $amount = 10*100;
            return $amount;
        }

        if($weight >= 25000){ //如果重量超过25kg，且不是自提，不配送
            return false;
        }

        if($weight <= 10000){
            $tmp_amount = 45;
        }else if($weight > 10000 && $weight <= 25000){
            $tmp_amount = 120;
        }else{
            $tmp_amount = 210;
        }
        if($size <= 60){
            $tmp_amount2 = 45;
        }else if($size > 60 && $size <= 80){
            $tmp_amount2 = 55;
        }else if($size > 80 && $size <= 100){
            $tmp_amount2 = 70;
        }else if($size > 100 && $size <= 120){
            $tmp_amount2 = 120;
        }else if($size > 120 && $size <= 140){
            $tmp_amount2 = 170;
        }else if($size > 140 && $size <= 160){
            $tmp_amount2 = 210;
        }else{
            $tmp_amount2 = 210;
        }

        $amount = $tmp_amount > $tmp_amount2 ? $tmp_amount*100 : $tmp_amount2*100;

        return $amount;
    }

    /** 韓國運費 */
    private function get_shipping_fee_type_3($addr,$goods_arr)
    {
        //过滤跨区运费
        foreach($goods_arr as $k => $goods) {
            $international_freight = $this->db_slave->select("freight_fee")
                ->where("goods_sn_main", $goods['goods_sn_main'])
                ->where('country_id',$addr['country'])
                ->get("trade_freight_fee_international")->row_array();

            if(!empty($international_freight)){
                unset($goods_arr[$k]);
            }
        }
        //goods_arr为空数组,说明都是跨区商品
        if($goods_arr == array()){
            return 0;
        }
        //-----------------------------------------------------------------------------

        $country = $addr['country'];
        if($country != 410){ //必須是韓國地區
            return FALSE;
        }

        return 0;
    }

    /** 美国免运费 */
    private function get_shipping_fee_type_4($addr, $goods_arr,$location_id_m)
    {
        //该数组的SKU运费 = 单个SKU * 数量
        $goods_sn_main_list = array(
            '62115955',
            '15784650',
            '16382424'
        );

        $addr_country = $addr['country'];

//        日本：392
//        加拿大：124
//        台湾：158
//        三个特殊国家按“其他”这个地区走运费
        switch ($addr_country)
        {
            case "392":
                $addr_country = "000";
                break;
            case "124":
                $addr_country = "000";
                break;
            case "158":
                $addr_country = "000";
                break;
        }

        $this->load->model("tb_trade_user_address");
        //过滤跨区运费
        foreach($goods_arr as $k => $goods) {
            $international_freight = $this->db_slave->select("freight_fee")
                ->where("goods_sn_main", $goods['goods_sn_main'])
                ->where('country_id',$addr_country)
//                ->where('country_id',$this->tb_trade_user_address->get_area_by_addr($addr))
                ->get("trade_freight_fee_international")->row_array();

            if(!empty($international_freight)){
                unset($goods_arr[$k]);
            }
        }
        //goods_arr为空数组,说明都是跨区商品
        if($goods_arr == array()){
//            echo($addr['country']."都是跨区商品".__FILE__.",".__LINE__."<br>");
            return 0;
        }
        //-----------------------------------------------------------------------------

        $country = $addr['country'];
        if($country != 840){ //必須美国地區
            return FALSE;
        }

        $location_id = $this->session->userdata('location_id');
        $cur_language_id = get_cookie('curLan_id', true);

        //手机端通过数据库读取
        if(empty($location_id) || empty($cur_language_id)){
            $this->load->model('tb_language');
            $location_id = $location_id_m;
            $cur_language_id = $this->tb_language->get_language_by_location($location_id);
        }

        $total_amount = 0;$general_amount = 0;
        if($goods_arr)foreach($goods_arr as $goods){
            /** 免邮商品 */
            //leon 2016-12-22 取消like的使用
            //$row = $this->db->select('is_free_shipping')->where('goods_sn_main',$goods['goods_sn_main'])->where('language_id',$cur_language_id)->where("sale_country like '%$location_id%'")->get('mall_goods_main')->row_array();
            $row = $this->db_slave->select('is_free_shipping')->where('goods_sn_main',$goods['goods_sn_main'])->where('language_id',$cur_language_id)->where('sale_country', $location_id)->get('mall_goods_main')->row_array();

            if($row && $row['is_free_shipping'] == 1){
                $general_amount += 0;
            }else{

                /** 先检测特定goods_sn的运费 */
                $spc_fee = $this->db_slave->select('start_weight_fee')->from('trade_freight_fee')
                    ->where('goods_sn_main',$goods['goods_sn_main'])->where('country_code',840)
                    ->get()->row_array();

                if(empty($spc_fee)){
                    return FALSE;
                }
                $goods_number = isset($goods['quantity']) ? $goods['quantity'] : $goods['goods_number'];

                if ($goods_number >= 1) /** 如果数量大于1，多收运费30% */
                {
                    if(in_array($goods['goods_sn_main'],$goods_sn_main_list)) {
                        $general_amount += $spc_fee['start_weight_fee'] * $goods_number;
                    }else{
                        // 首重
                        $general_amount += $spc_fee['start_weight_fee'];
                        $goods_number -= 1;

                        // 续重
                        while ($goods_number > 0) {
                            $general_amount += $spc_fee['start_weight_fee'] * 0.3;
                            $goods_number -= 1;
                        }
                    }
                }
            }
            $total_amount = $total_amount > $general_amount ? $total_amount : $general_amount;
        }
        return $total_amount;
    }

    /**
     * 根据 uid 获取商品的运费
     */
    public function get_product_shipping_fee($uid, $goods_sn_main, $currency, $cur_all, $curLocation_id)
    {
        $cur_language_id = get_cookie('curLan_id', true);
        if (empty($cur_language_id)) {
            $cur_language_id = 1;
        }

        $cur_map = array();
        foreach ($cur_all as $v)
        {
            $cur_map[$v['currency']] = $v['rate'];
        }

        // 商品信息
        $goods_main_info = $this->db_slave
            ->select('goods_weight, store_code')
            ->where('goods_sn_main', $goods_sn_main)
            ->where('language_id', $cur_language_id)
            ->get('mall_goods_main')
            ->row_array();

        // 仓库运费表
        $storehouse_info = $this->db_slave
            ->select('shipping_currency, rule_type, shipping_rule')
            ->where('store_code', $goods_main_info['store_code'])
            ->get('mall_goods_storehouse')
            ->row_array();

        // 商品重量，单位克
        $goods_weight = intval($goods_main_info['goods_weight'] * 1000);

        // 运费规则
        $rules = json_decode($storehouse_info['shipping_rule'], true);

        $fee = false;
        $this->load->model("tb_trade_user_address");
        $addr_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$curLocation_id);
        foreach ($addr_list as $addr)
        {
            if ($addr['is_default'] == 1)
            {
                $fee = $this->get_shipping_fee($addr, $storehouse_info['rule_type'], $rules, $goods_weight,$storehouse_info['store_location_code'],array(array('goods_sn_main'=>$goods_sn_main)));
                break;
            }
        }
        if ($fee == false)
        {
            return false;
        }

        if ($currency == $storehouse_info['shipping_currency'])
        {
            $show_fee = number_format($fee / 100, 2, ".", "");
        }
        else
        {
            $fee_usd = $fee / $cur_map[$storehouse_info['shipping_currency']];
            $show_fee = number_format($fee_usd * $cur_map[$currency] / 100, 2, ".", "");
        }

        return $show_fee;
    }

    /****************************** 仓库与区域 ******************************/

    /**
     * 获取发货商列表
     */
    public function get_storehouse_list()
    {
        $list = array();

        $sql = "select supplier_id,supplier_name from mall_supplier order by supplier_id ASC ";
        $shipper_list = $this->db_slave->query($sql)->result_array();

        if (empty($shipper_list))
        {
            return $list;
        }

        foreach ($shipper_list as $v)
        {
            $list[] = array(
                'supplier_id' => $v['supplier_id'],
                'supplier_name' => $v['supplier_name'],
            );
        }

        return $list;
    }


    /**
     * 获取区域映射表
     */
    public function get_area_map($lang)
    {
        $list = $this->db_slave
            ->select("country_id, name_{$lang}")
            ->get('mall_goods_sale_country')
            ->result_array();
        $map = array("999" => lang('area_global'));
        foreach ($list as $v)
        {
            $map[$v['country_id']] = $v["name_{$lang}"];
        }
        return $map;
    }

    /****************************** 订单流水 ******************************/
    public function add_order_log($order_id, $oper_code, $statement, $operator_id)
    {
        // 查询订单是否存在
//        $order_info = $this->db
//            ->select('*')
//            ->where('order_id', $order_id)
//            ->get('trade_orders')
//            ->row_array();

//        $order_info = $this->db_slave->query("select order_id from trade_orders where order_id = '$order_id'")->row_array();
        $order_info = $this->tb_trade_orders->get_one("order_id",["order_id"=>$order_id]);
        if (empty($order_info))
        {
            return false;
        }
        $attr = array(
            'order_id' => $order_id,
            'oper_code' => $oper_code,
            'statement' => $statement,
            'operator_id' => $operator_id,
            'update_time' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('trade_orders_log', $attr);
        return true;
    }
     /**
	  * 批量写入订单日志
      * @author: derrick
      * @date: 2017年3月21日
      * @param: @param array $order_ids 订单id数组, $oper_code,$statement,$operator_id三个参数不同时为null时, $order_ids只是订单id数组
      * @param: @param numeric $oper_code
      * @param: @param string $statement
      * @param: @param numeric $operator_id
      *  $oper_code, $statement, $operator_id为null时, $order_ids可以传整个参数数组, 如下
     *   demo: $data = array(
     *  	0 => array(
     *  		'order_id' => '',
     *  		'oper_code' => '',
     *  		'statement' => '',
     *  		'operator_id' => '',
     *  		'update_time' => '',
     *  	),
     *  	1 => array(
     *  		'order_id' => '',
     *  		'oper_code' => '',
     *  		'statement' => '',
     *  		'operator_id' => '',
     *  		'update_time' => '',
     *  	),
     *  	.
     *  	.
     *  )
      * @reurn: return_type
      */
    public function add_order_logs(Array $order_ids, $oper_code = null, $statement = null, $operator_id = null) {
    	$data = [];
    	if (is_null($oper_code) && is_null($statement) && is_null($operator_id)) {
    		$data = $order_ids;
    	} else {
    		foreach ($order_ids as $oi) {
    			$data[] = array(
    				'order_id' => $oi,
    				'oper_code' => $oper_code,
    				'statement' => $statement,
    				'operator_id' => $operator_id,
    				'update_time' => date("Y-m-d H:i:s"),
    			);
    		}
    	}
    	$this->db->insert_batch('trade_orders_log', $data);
    }

    /****************************** 工具接口 ******************************/

    /**
     * 如果發貨的訂單支付是paypal 記錄跟踪號到mall_orders_paypal_info
     */
    public function paypal_order_deliver($order_id, $base_info)
    {
//        $v = $this->db_slave->where('order_id', $order_id)->get('trade_orders')->row_array();
        $v = $this->tb_trade_orders->get_one("order_id,payment_type,customer_id,txn_id",
            ["order_id"=>$order_id]);
        $this->load->model('m_user_helper');
        $company = $this->m_user_helper->getFreightName($base_info['company_code']);
        if ($v['payment_type'] == '107')
        {
            $insert_arr = array(
                'uid' => $v['customer_id'],
                'order_id' => $v['order_id'],
                'txn_id' => $v['txn_id'],
                'tracking_number' => $base_info['express_id'],
                'company_name' => $company,
            );
            $this->db->insert('mall_orders_paypal_info', $insert_arr);
        }
        //套装选购
        if ($v['payment_type'] == '1')
        {
            $v1 = $this->db_slave
                ->where("payment = 'paypal' and `status` = 2 and uid=".$v['customer_id'])
                ->get('user_upgrade_order')
                ->row_array();
            if(empty($v1)){
                return false;
            }
            $insert_arr = array(
                'uid' => $v['customer_id'],
                'order_id' => $v1['order_sn'],
                'txn_id' => $v1['txn_id'],
                'tracking_number' => $base_info['express_id'],
                'company_name' => $company,
            );
            $this->db->insert('mall_orders_paypal_info', $insert_arr);
        }
    }

    public function get_doba_goods_info($goods_list){
        $this->load->model("m_split_order");

        $doba_goods_info =array();

        $new_arr = $this->m_split_order->split_goods_list($goods_list);
        foreach($new_arr as $k => $v)
        {
            $list = array();

//            $goods_info_item = $this->db_slave->select("goods_sn_main")
//                ->where("goods_sn", $k)
//                ->where('language_id',1)
//                ->get("mall_goods")
//                ->row_array();
            $this->load->model("tb_mall_goods");
            $goods_info_item = $this->tb_mall_goods->get_one('goods_sn_main',
                ['goods_sn'=>$k,'language_id'=>1]);

            if (empty($goods_info_item))
            {
                continue;
            }
            $goods_main_info_item = $this->db_slave
                ->select("doba_item_id")
                ->where("goods_sn_main", $goods_info_item['goods_sn_main'])
                ->where('language_id',1)
                ->get("mall_goods_main")
                ->row_array();
            if (empty($goods_main_info_item))
            {
                continue;
            }

            $list['item_id'] = $goods_main_info_item['doba_item_id'];
            $list['quantity'] = $v;
            if($list != array()){
                $doba_goods_info [] = $list;
            }
        }
        return $doba_goods_info;
    }

    /** 订单产品信息 */
    function get_order_goods_arr($goods_list,$cur_language_id){

        $this->load->model("o_erp");
        return $this->o_erp->get_order_goods_arr($goods_list,$cur_language_id);
    }

    /**
     * 计算doba商品运费,旧方法
     * @param $item
     * @return int
     */
    public function doba_goods_shipping_fee_old($item){
        $ship_fee_sum = 0;
        foreach($item as $v)
        {
            //购买数量每增加3个，运费翻一倍
            $multiple = ceil($v['quantity'] / 3);

            $goods_main_info = $this->db_slave->select("doba_drop_ship_fee,doba_ship_cost")
                ->where("goods_sn_main", $v['goods_sn_main'])->where("language_id", 1)
                ->get("mall_goods_main")->row_array();
            if (empty($goods_main_info)) {
                continue;
            }

            $ship_fee = ($goods_main_info['doba_drop_ship_fee']+$goods_main_info['doba_ship_cost']) * $multiple;
            $ship_fee_sum += $ship_fee;
        }
        return $ship_fee_sum;
    }

    /**
     * 调用doba接口计算doba商品运费，
     * 如果数量小于3不建议调用本函数计算，调接口速度会变慢
     * @param $item
     * @param $addr
     * @return float|int
     */
    public function doba_goods_shipping_fee($item,$addr)
    {
        $redis_pre_key = "doba:shipping:fee:";
        $doba_file = APPPATH . 'third_party/doba/doba_curl.php';
        if(!file_exists($doba_file))
        {
            log_message("ERROR","can't find $doba_file");
            return 0;
        }

        require_once $doba_file;

        $doba = new doba_curl();
        //首先，先检测redis里是否已有运费缓存
        $doba_cache_file = $doba->get_cache_file_path($item);
        $doba_cache_file = str_replace('/',':',$doba_cache_file);
        $doba_cache_file = str_replace('.','_',$doba_cache_file);
        if($doba_cache_file)
        {
            $tmp = $this->redis_get($redis_pre_key.$doba_cache_file);
            if($tmp)
            {
                return $tmp;
            }
        }
        //如果redis里未找到运费缓存走接口取运费，里面包含了tmp目录文件缓存运费
        $res = $doba->get_shipping_fee($item,$addr);

        //若缓存跟接口都取不到邮费，使用旧算法
        if(bccomp($res,0,2) < 1)
        {
            //使用旧算法
            $res = $this->doba_goods_shipping_fee_old($item);
        }else{
            //若接口取到非0运费，缓存运费到redis里
            $this->redis_set($redis_pre_key.$doba_cache_file,$res,60*60*24);
        }
        return $res;
    }

    /****推送doba订单****/
    public function send_doba_order(){

        $cron = $this->db_slave->query("select * from cron_doing WHERE cron_name = 'send_doba_order'")->row_array();
        if($cron){
            if($cron['false_count'] > 5){
                $this->db->delete('cron_doing', array('cron_name' => 'send_doba_order'));
                return false;
            }
            $this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
            return false;
        }

        //如果没有执行中的任务,则开始执行
        $this->load->model('m_goods');
        $order_list = $this->db_slave->query("select * from trade_order_doba_log WHERE status = '1'")->result_array();

        //计划任务开始,插入计划任务
        $insert_attr = array('cron_name' => 'send_doba_order');
        $this->db->insert('cron_doing', $insert_attr);

        if($order_list)foreach ($order_list as $order)
        {
            $order_id = $order['order_id'];
            $doba_goods_info = $this->get_doba_goods_info($order['goods_list']);
            $ship_info = array
            (
                'phone' => $order['phone'],
                'city' => $order['city'],
                'country' => $order['country'],
                'firstname' => $order['firstname'],
                'lastname' => $order['lastname'],
                'postal' => $order['postal'],
                'state' => $order['state'],
                'street' => $order['street']
            );

            //如果已经推送成功，则阻止推送
            $res = $this->db_slave->query("select order_id from trade_orders_doba_order_info where order_id = '$order_id'")->row_array();
            if(!empty($res)){
                $this->db->query("delete from trade_order_doba_log WHERE order_id = '$order_id'");
                continue;
            }

            $return_id = $this->m_goods->create_doba_order($order_id, $doba_goods_info, $ship_info);
            if ($return_id)
            {
                $this->db->query("delete from trade_order_doba_log WHERE order_id = '$order_id'");
            }
        }

        //计划任务完成,删除任务
        $this->db->query("delete from cron_doing WHERE cron_name = 'send_doba_order'");
    }

}
