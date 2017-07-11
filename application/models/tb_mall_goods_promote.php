<?php
class tb_mall_goods_promote extends MY_Model {
    protected $table_name = "mall_goods_promote";//商品促销表
    function __construct() {
        parent::__construct();
    }

    /**
     * 获取促销商品的信息
     * @date: 2016-5-27
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    function get_goods_promote_info($goods_sn_main,$day,$goods_sn = '') {
        if(!empty($goods_sn)) {
            $where['goods_sn'] = $goods_sn;
        }
        $where['goods_sn_main'] = $goods_sn_main;
        $where['start_time <='] = $day;
        $where['end_time >='] = $day;
        return $this->get_one("goods_sn_main,promote_price_main,promote_price,end_time",$where);
    }

    /**
     * 删除促销信息
     */
    public function delete_promote($sn_main) {
        $exitRet = $this
            ->db
            ->select("goods_sn_main")
            ->where("goods_sn_main", $sn_main)
            ->get("mall_goods_promote")
            ->row_array();
        if ($exitRet) {
            $ret = $this->db->delete('mall_goods_promote',array('goods_sn_main'=>$sn_main));
            return $ret;
        }
        return true;
    }

    /**
     * 取消促销数据 更改状态
     */
    public function erpapi_cancel_promote($attr){
        $retData = array(
            'code' => 200,
            'msg' => "",
        );
        $sku = trim($attr['goods_sn_main']);
        $sn = trim($attr['goods_sn']);
        $type = trim($attr['price_adjust_type']);
        $price = number_format(intval($attr['promote_price']) / 100, 2, '.', '');
        if (!$sku || !$sn || !$type || !$price) {
            $retData['code'] = 1104;
            $retData['msg'] = "缺少参数";
            return $retData;
        } else {
            // 1.删掉促销数据
            $where = array('goods_sn' => $sn);
            $delPromote = $this->db->where($where)->delete('mall_goods_promote');
            if (!$delPromote) {
                $retData['code'] = 1104;
                $retData['msg'] = "删除促销数据失败 sn:".$sn;
                return $retData;
            }
            // 2.更改促销标识
            $mainWhere = array('goods_sn_main' => $sku);
            $promoteInfo = $this->db->select("is_promote")
                ->where($mainWhere)
                ->get("mall_goods_main")
                ->row_array();
            if ($promoteInfo['is_promote'] == 1) {
                $upPromoteMark = $this->db->where($mainWhere)->update('mall_goods_main', array('is_promote' => 0));
                if (!$upPromoteMark) {
                    $retData['code'] = 1104;
                    $retData['msg'] = "更新促销标识失败 sku:".$sku;
                    return $retData;
                }
            }
            // 判断价格类型
            if ($type == 2) {
                // 更改主商品价格
                $mainPrice = number_format(intval($attr['promote_price_main']) / 100, 2, '.', '');
                $mainPriceInfo = $this->db->select("shop_price")->where($mainWhere)
                    ->get("mall_goods_main")
                    ->row_array();
                if ($mainPriceInfo['shop_price'] != $mainPrice) {
                    $upPriceRet = $this->db->where($mainWhere)->update('mall_goods_main', array('shop_price' => $mainPrice));
                    if (!$upPriceRet) {
                        $retData['code'] = 1104;
                        $retData['msg'] = "更新促销标识失败 sku:".$sku;
                        return $retData;
                    }
                }

                // 更改子商品价格
//                $goodsInfo = $this->db->select("price")->where($where)
//                    ->get("mall_goods")
//                    ->row_array();
                $this->load->model("tb_mall_goods");
                $goodsInfo = $this->tb_mall_goods->get_one("price",$where);
                if ($goodsInfo['price'] != $price) {
//                    $upPriceRet = $this->db->where($where)->update('mall_goods', array('price' => $price));
                    $upPriceRet = $this->tb_mall_goods->update_one($where,["price"=>$price]);
                    if (!$upPriceRet) {
                        $retData['code'] = 1104;
                        $retData['msg'] = "更新促销标识失败 sku:".$sku;
                        return $retData;
                    }
                }

            }
            return $retData;
        }
    }
}