<?php
/**
 * @date: 2017-1-4
 * @author: Baker
 */
class tb_mall_goods_keyword  extends MY_Model {
    function __construct() {
        parent::__construct();
        $this->table_name = "mall_goods_keyword";
    }
    public $keywords = array(
        '156' => array(
            'input'      => '11010',
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
        ),
        '344' => array(
            'input'      => '12010',

            'VjCumOUrIi' => '12040',//美妝護理
            'Y6b1V6gh9g' => '12042',//糧油食品
            'AotyTAhdbv' => '12044',//酒水飲用
            'HzHJ2ZcxIw' => '12046',//個人配飾
            'zhJoOMFhRV' => '12048',//家居用品
            'g5k4nwGk8T' => '12050', //休閒娛樂
        ),
        '840' => array(
            'input'      => '13010',
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
            'input'      => '14010',
            'ag7taJdKkk' => '14044', //패션의류/잡화
            'JKiS723c0U' => '14040', //뷰티/케어
            'KgUE9iXUli' => '14046', //식품/음료
            'eEaWZSXgMB' => '14048', //건강관리
            'bjzpVSIrNC' => '14050', //홈데크/생필품
            'J51t2pALMK' => '14042', //애완용품
        ),
        '000' => array(
            'input' => '19010',
        ),
    );

  

    public function add_keyword($arr) {
    	$ret = FALSE;
    	$add = [];
        if ($arr) {
        	foreach ($arr as $key => $value) {
        		$add[$key] = $value;
        	}
        	if ($this->insert_one($add)) {
            	$ret = TRUE;
       		}
        }
        return $ret;
    }

    public function del_keyword($where) {
    	if (empty($where)) {
    		return FALSE;
    	}
    	return $this->delete_one($where);
    }

    public function edit_keyword($where, $data) {
    	if (empty($where) || empty($data)) {
    		return FALSE;
    	}
    	return $this->update_one($where, $data);
    }

    public function get_keyword($where = [], $fields = '*', $order = [], $limit = 1000) {
        if (empty($where)) {
            return [];
        }
        $ret = [];
        $list = $this->get_list($fields, $where, [], $limit, 0, $order);
    /*    $this->db->from('mall_goods_keyword')->where($where);
        if (!empty($fields)) {
            $this->db->select($fields);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        $list = $this->db->get()->result_array();*/
        //exit($this->db->last_query());
        foreach ($list as $k => $v) {
            $ret[$v['position_id']][] = $v;
        }
        unset($list);
        return $ret;
    }
}
