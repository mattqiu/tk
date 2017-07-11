<?php
/**
 *　产地
 * @date: 2016-11-17
 * @author: Baker
 */
class m_goods_origin extends MY_Model{

	public function get_origin($language = '') {
        $arr = [];
        $language = trim($language);
        if (empty($language)) {
            return $arr;
        }
		$list = $this->db->where(array('language' => $language))->select(array('country_flag', 'name', 'language'))->get('mall_goods_origin')->result_array();
        if ($list) {
            foreach($list as $val) {
                $arr[$val['country_flag']] = $val['name'];
            }
        }
        
        return $arr;
	}
}

