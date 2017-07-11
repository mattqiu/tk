<?php
/**
 * @date: 2017-1-4
 * @author: Baker
 */
class tb_mall_goods_ads  extends MY_Model {
    function __construct() {
        parent::__construct();
        $this->table_name = "mall_goods_ads";
    }
    public $ads = array(
        '156' => array(
            'top'      => '11010',
            'ads'      => '11020',
            'mall_ads' => '11030',
            'activity' => '11025',
            'new'      => '11035',
            'hot'      => '11036',
            'free'     => '11037',
            'promote'  => '11038',

            //广告
            'VjCumOUrIi' => '11042',//美妆个护
            'MRUya5BVJ5' => '11048',//营养保健
            'AotyTAhdbv' => '11044',//食品酒水
            'u9f0KhcZci' => '11040',//母婴用品
            'zhJoOMFhRV' => '11050',//家居日用
            'K0JpROsUQY' => '11054',//礼品箱包
            'yH9zgV5oCK' => '11056', //服饰鞋帽
            'dc8hfvl17J' => '11046', //钟表首饰
            'wk1BC6QKrZ' => '11052', //数码电子
            'mWKI6osgkg' => '11058',   //汽车用品
            'AcrsRgyBJo' => '11060',   //运动户外
            'wdm5CR6M5Q' => '11062', //宠物生活

            //品牌
            'VjCumOUrIi_brand' => '11082',//美妆个护
            'MRUya5BVJ5_brand' => '11088',//营养保健
            'AotyTAhdbv_brand' => '11084',//食品酒水
            'u9f0KhcZci_brand' => '11080',//母婴用品
            'zhJoOMFhRV_brand' => '11090',//家居日用
            'K0JpROsUQY_brand' => '11094',//礼品箱包
            'yH9zgV5oCK_brand' => '11096', //服饰鞋帽
            'dc8hfvl17J_brand' => '11086', //钟表首饰
            'wk1BC6QKrZ_brand' => '11092', //数码电子
            'mWKI6osgkg_brand' => '11098',   //汽车用品
            'AcrsRgyBJo_brand' => '11100',   //运动户外
            'wdm5CR6M5Q_brand' => '11102', //宠物生活
        ),
        '344' => array(
            'top'        => '12010',
            'ads'        => '12020',
            'mall_ads'   => '12030',
            'activity'   => '12025',
            'new'        => '12035',
            'hot'        => '12036',
            'free'       => '12037',
            'promote'    => '12038',

            'VjCumOUrIi' => '12040',//美妝護理
            'Y6b1V6gh9g' => '12042',//糧油食品
            'AotyTAhdbv' => '12044',//酒水飲用
            'HzHJ2ZcxIw' => '12046',//個人配飾
            'zhJoOMFhRV' => '12048',//家居用品
            'g5k4nwGk8T' => '12050', //休閒娛樂
        ),
        
        '840' => array(
            'top'        => '13010',
            'ads'        => '13020',
            'mall_ads'   => '13030',
            'activity'   => '13025',
            'new'        => '13035',
            'hot'        => '13036',
            'free'       => '13037',
            'promote'    => '13038',

            'VjCumOUrIi' => '13042', //Health & beauty
            'dc8hfvl17J' => '13052', //Apparel & shoes
            'zhJoOMFhRV' => '13040', //Home & garden
            'd3uxgyBPpI' => '13044', //Electronics
            'AcrsRgyBJo' => '13046', //Outdoor & Sports
            'S850zZjAlk' => '13054', //Books
            'u9f0KhcZci' => '13050', //Kids, baby & toy
            'r8RlR68b1Z' => '13056', //Cleaning Products 
            '8hQ3rPF5bM' => '13048', //Tool & Industrial
            'jH44Ep2kEd' => '13058', //Eyelash
            '1eL9JDFKbI' => '13060', //Games & movies
            'wk1BC6QKrZ' => '13062', //Electric Appliance
        ),
        '410' => array(
            'top'        => '14010',
            'ads'        => '14020',
            'mall_ads'   => '14030',
            'activity'   => '14025',
            'new'        => '14035',
            'hot'        => '14036',
            'free'       => '14037',
            'promote'    => '14038',

            'ag7taJdKkk' => '14044', //패션의류/잡화
            'JKiS723c0U' => '14040', //뷰티/케어
            'KgUE9iXUli' => '14046', //식품/음료
            'eEaWZSXgMB' => '14048', //건강관리
            'bjzpVSIrNC' => '14050', //홈데크/생필품
            'J51t2pALMK' => '14042', //애완용품
            'u3F33WlsDg' => '14052', //'디지털/가전/컴퓨터'
        ),
        '000' => array(
            'top' => '19010',
            'ads' => '19020',
        ),
    );


    public function add_ads($arr) {
    	$add = [];
        if ($arr) {
        	foreach ($arr as $key => $value) {
        		$add[$key] = $value;
        	}
        	return $this->insert_one($add);
        }
        return FALSE;
    }

    public function del_ads($where) {
    	if (empty($where)) {
    		return FALSE;
    	}
    	return $this->delete_one($where);
    }

    public function edit_ads($where, $data) {
    	if (empty($where) || empty($data)) {
    		return FALSE;
    	}
        return $this->update_one($where, $data);
    }

    public function get_ads($where = [], $fields = '*', $order = [], $limit = 1000) {
        if (empty($where)) {
            return [];
        }
        $ret = [];
        $list = $this->get_list($fields, $where, [], $limit, 0, $order);
        /*$this->db->from('mall_goods_ads')->where($where);
        if (!empty($fields)) {
            $this->db->select($fields);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        $list = $this->db->get()->result_array(); */
        foreach ($list as $k => $v) {
            $ret[$v['position_id']][] = $v;
        }
        unset($list);
        return $ret;
    }

    /**
     * 获取对应馆的 品牌 logo 内容
     * author：leon
     * @param string $where 条件
     * @return array        条件内容数据
     */
    public function get_ads_list($where = '') {
        if (empty($where)) {
            return [];
        }
        return $this->get_list('*', $where);
    }
}
