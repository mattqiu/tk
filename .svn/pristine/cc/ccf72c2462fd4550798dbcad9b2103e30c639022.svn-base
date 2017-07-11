<?php
/**
 * @author Terry
 */
class tb_trade_freight_fee_international extends MY_Model {

    protected $table_name = "trade_freight_fee_international";

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 查询运费纪录是否存在
     * @return boolean
     * @author Terry
     */
    public function findOne($id) {
        $this->db->from('trade_freight_fee_international');
        $one = $this->db->select('goods_sn_main,country_id,freight_fee,Id')->where('Id',$id)->get()->row_array();
        if(!empty($one)) {
            $country = array("156", "840", "410", "344", "000");
            if (!in_array($one['country_id'], $country)) {
                $one['country_id'] = '000';
            }
            $country = $this->db->from('mall_goods_sale_country')->get()->result_array();
            foreach ($country as $countryid) {
                if ($countryid['country_id'] == $one['country_id']) {
                    if ($_COOKIE['curLan'] == 'zh') {
                        $one['country_name'] = $countryid['name_zh'];
                    } elseif ($_COOKIE['curLan'] == 'hk') {
                        $one['country_name'] = $countryid['name_hk'];
                    } elseif ($_COOKIE['curLan'] == 'english') {
                        $one['country_name'] = $countryid['name_english'];
                    }

                }
            }
        }
       return $one;
    }

    /**
     * @param $id
     * 修改一条记录
     */
    public function modifyOne($id,$admin_order_deliver_fee) {
        $res = $this->db->where('Id', $id)->update('trade_freight_fee_international',array('freight_fee'=>$admin_order_deliver_fee));
        return $res;
    }

    /**
     *删除一条记录
     */
    public function deldete($id) {
        $res = $this->db->where('Id', $id)->delete('trade_freight_fee_international');
        return $res;
    }
}
