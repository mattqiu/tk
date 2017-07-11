<?php
/**
 * 订单验证
 * @author tico.wong
 * @date 2017.03.17
 */
class o_order_validate extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证订单是否包含不配送的产品
     * @param $goods_list,形如：sn:quantity$sn:quantity.....
     * @param $attr,用户提交的订单数据，必须包含address_id
     * @param $curLan_id,$this->_viewData数据
     * @param $curLan,$this->_viewData数据
     * @param $cur_rate,$this->_viewData数据
     * @param $curCur,$this->_viewData数据
     * @param $currency_all,$this->_viewData数据
     */
    function goods_list_deliver_validate($goods_list,$attr,$curLan_id,$curLan,$cur_rate,$curCur,$currency_all)
    {
        //验证是否存在不配送产品,开始
        $goods_list_arr = [];
        $tmp_goods_list = explode("$",$goods_list);
        foreach($tmp_goods_list as $k=>$v)
        {
            $tmp_2 = explode(":",$v);
            if($tmp_2)
            {
                if(!empty($tmp_2[0]) and !empty($tmp_2[1]))
                    $goods_list_arr[] = ['goods_sn'=>$tmp_2[0],'quantity'=>$tmp_2[1]];
            }
        }
        $this->load->model("tb_user_cart");
        $tmp_goods_list2 = $this->tb_user_cart->get_goods_data($goods_list_arr,$curLan_id,$curLan,$cur_rate);

        $this->load->model("tb_trade_user_address");
        $address = $this->tb_trade_user_address->get_one("*",['id'=>$attr['address_id']]);

        $this->load->model("M_trade","m_trade");
        $tmp_list =  $this->m_trade->get_checkout_data($tmp_goods_list2,[$address],$curCur,$cur_rate,$currency_all);

        foreach($tmp_list as $v_tmp_list)
        {
            if($v_tmp_list)
            {
                if(!empty($v_tmp_list['invalid_list']))
                {
                    if(count($v_tmp_list['invalid_list'])){
                        $str = "";
                        foreach($v_tmp_list['invalid_list'] as $v)
                        {
                            $str .= $v['goods_sn_main'].",";
                        }
                        echo json_encode(array('success' => false,'msg'=>$str.":".lang('checkout_order_deliver_unable')));
                        exit();
                    }
                }
                if(!empty($v_tmp_list['deliverable_list']))
                {
                    foreach($v_tmp_list['deliverable_list'] as $k=>$v)
                    {
                        if(empty($v['shipping_type']))
                        {
                            $str = "";
                            foreach($v['list'] as $kl=>$vl)
                            {
                                if($str)
                                {
                                    $str .= ",".$vl['goods_name'];
                                }else{
                                    $str .= $vl['goods_name'];
                                }
                            }
                            echo json_encode(array('success' => false,'msg'=>$str.":".lang('checkout_order_deliver_unable')));
                            exit();
                        }
                    }
                }
            }
        }
        //验证是否存在不配送产品,结束
    }

}
