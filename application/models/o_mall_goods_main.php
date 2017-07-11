<?php
/**
 * mall_goods_main 功能类
 * @author ticowong
 */
class o_mall_goods_main extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 计算折扣比例
     * @date: 2016-7-6
     * @author: sky yuan
     * @update:baker 2017-3-21
     * @parameter:
     *      $promote_arr：促销价格。货币美元，单位分
     *      $goods_row：  产品信息
     *      $language_id：
     * @return:
     */
    function cal_price_off($promote_arr,$goods_row,$language_id) {
        $price_arr = array();
        $s_price = $promote_arr/100; //$promote_arr['promote_price_main']/100;//$promote_arr['promote_price']/100;
        $price_arr['shop_price'] = $s_price;

        //market_price：市场价，美元
        if(($goods_row['market_price'] - $s_price) <= 0) {
            //$price_arr['price_off'] = 0;
            $price_arr['price_off'] = 0.0;
        }else {
            $price_off = floatval(round((($goods_row['market_price'] - $s_price)/$goods_row['market_price']),2));

           /* if(in_array($language_id,array(2,3))) {
                $price_arr['price_off'] = 10 - $price_off * 10;
            }else {
                $price_arr['price_off'] = $price_off * 100 . '%';
            }*/
            if(in_array($language_id,array(2,3))) {
                $price_arr['price_off'] = 10 - $price_off * 10;
            }else {
                $price_arr['price_off'] = $price_off;
            }
        }
        return $price_arr;
    }

}
