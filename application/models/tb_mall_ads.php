<?php
/**
 * 广告数据访问层
 * @date: 2016-5-11
 * @author: sky yuan
 */
class tb_mall_ads extends MY_Model {

    protected $table_name = "mall_ads";
    public function __construct() {
        parent::__construct();
    }

    /**
     * 添加广告，同时重排同一位置下的显示顺序
     */
    public function add_ad_and_reset_order($attr) {

        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        $this->db->trans_start();

        $locLangMap = array(
            // 美国
            'index_840' => 1, // 首页banner
            '379' => 1, // 楼层广告-Home & Garden
            '34' => 1,  // 楼层广告-Health & Beauty
            '383' => 1, // 楼层广告-Electronics
            '64' => 1,  // 楼层广告-Outdoor & Sports
            '774' => 1, // 楼层广告-Tool & Industrial
            '407' => 1, // 楼层广告-Kids, Baby & Toy
            'app_index_840' => 1,   // 首页banner
            'app_cate_379' => 1,    // 楼层广告-Home & Garden
            'app_cate_34' => 1,     // 楼层广告-Health & Beauty
            'app_cate_383' => 1,    // 楼层广告-Electronics
            'app_cate_64' => 1,     // 楼层广告-Outdoor & Sports
            'app_cate_774' => 1,    // 楼层广告-Tool & Industrial
            'app_cate_407' => 1,    // 楼层广告-Kids, Baby & Toy
            // 中国
            'index_156' => 2, // 首页banner
            '20' => 2,  // 楼层广告-母婴用品
            '35' => 2,  // 楼层广告-美妆个护
            '92' => 2,  // 楼层广告-食品酒水
            '158' => 2, // 楼层广告-钟表首饰
            '80' => 2,  // 楼层广告-营养保健
            '122' => 2, // 楼层广告-家居日用
            '497' => 2, // 楼层广告-数码电子
            'app_index_156' => 2,   // 首页banner
            'app_cate_20' => 2,     // 楼层广告-母婴用品
            'app_cate_35' => 2,     // 楼层广告-美妆个护
            'app_cate_92' => 2,     // 楼层广告-食品酒水
            'app_cate_158' => 2,    // 楼层广告-钟表首饰
            'app_cate_80' => 2,     // 楼层广告-营养保健
            'app_cate_122' => 2,    // 楼层广告-家居日用
            'app_cate_497' => 2,    // 楼层广告-数码电子
            // 香港
            'index_344' => 3, // 首页banner
            '36' => 3,  // 楼层广告-美妝個護
            '1337' => 3,
            '93' => 3,  // 楼层广告-食品酒水
            '1351' => 3,
            '123' => 3, // 楼层广告-家居日用
            '1200' => 3,
            'app_index_344' => 3,   // 首页banner
            'app_cate_36' => 3,     // 楼层广告-美妝個護
            'app_cate_1337' => 3,
            'app_cate_93' => 3,     // 楼层广告-食品酒水
            'app_cate_1351' => 3,    // 楼层广告-鐘錶首飾
            'app_cate_123' => 3,    // 楼层广告-家居日用
            'app_cate_1200' =>3,
            // 韩国
            'index_410' => 4, // 首页banner
            '929' => 4, // 楼层广告-뷰티/케어
            '934' => 4,  // 楼层广告-애완용품
            '937' => 4, // 楼层广告-패션의류/잡화
            '939' => 4,  // 楼层广告-식품/음료
            '949' => 4, // 楼层广告-건강관리
            '952' => 4, // 楼层广告-홈데크/생필품
            'app_index_410' => 4,   // 首页banner
            'app_cate_929' => 4,    // 楼层广告-뷰티/케어
            'app_cate_934' => 4,    // 楼层广告-애완용품
            'app_cate_937' => 4,    // 楼层广告-패션의류/잡화
            'app_cate_939' => 4,    // 楼层广告-식품/음료
            'app_cate_949' => 4,    // 楼层广告-건강관리
            'app_cate_952' => 4,    // 楼层广告-홈데크/생필품
            // 其他地区
            'index_000' => 1, // 首页banner
        );

        // 插入数据
        $insertData = array(
            'ad_id' => $attr['id'],
            'ad_img' => $attr['ad_img'],
            'ad_url' => $attr['action_val'],
            'language_id' => $locLangMap[$attr['location']],
            'status' => $attr['status'],
            'location' => $attr['location'],
            'sort_order' => $attr['sort_order'],
        );
        if (false == $this->db->insert('mall_ads', $insertData)) {
            $retData['code'] = 1102;
            $retData['msg'] = "insert ad failed, ".$this->db->_error_message();
            return $retData;
        }

        // 如果存在需要重排的顺序，同时更新
        if (!empty($attr['reset_sort'])) {
            foreach ($attr['reset_sort'] as $v) {
                if (false == $this->db->where('ad_id', $v['id'])->update('mall_ads', array('sort_order' => $v['order']))) {
                    $retData['code'] = 1103;
                    $retData['msg'] = "update ad order failed, ".$this->db->_error_message();
                    return $retData;
                }
            }
        }

        $this->db->trans_complete();
        return $retData;
    }

    /**
     * 修改广告，同时重排同一位置下的显示顺序
     */
    public function edit_ad_and_reset_order($attr) {

        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        $this->db->trans_start();

        if (empty($attr['id'])) {
            $retData['code'] = 1002;
            $retData['msg'] = "update ad order failed, ".$this->db->_error_message();
            return $retData;
        }

        $updateData = array();
        if (isset($attr['ad_img'])) {
            $updateData['ad_img'] = $attr['ad_img'];
        }
        if (isset($attr['action_val'])) {
            $updateData['ad_url'] = $attr['action_val'];
        }
        if (isset($attr['status'])) {
            $updateData['status'] = $attr['status'];
        }
        if (isset($attr['sort_order'])) {
            $updateData['sort_order'] = $attr['sort_order'];
        }
        if (false === $this->db->where('ad_id', $attr['id'])->update('mall_ads', $updateData)) {
            $retData['code'] = 1103;
            $retData['msg'] = "update main failed, ".$this->db->_error_message();
            return $retData;
        }

        // 如果存在需要重排的顺序，同时更新
        if (isset($attr['reset_sort']) && !empty($attr['reset_sort'])) {
            foreach ($attr['reset_sort'] as $v) {
                if (false == $this->db->where('ad_id', $v['id'])->update('mall_ads', array('sort_order' => $v['order']))) {
                    $retData['code'] = 1103;
                    $retData['msg'] = "update ad order failed, ".$this->db->_error_message();
                    return $retData;
                }
            }
        }

        $this->db->trans_complete();
        return $retData;
    }

    /**
     * 删除广告，同时重排同一位置下的显示顺序
     */
    public function delete_ad_and_reset_order($attr) {

        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        $this->db->trans_start();

        // 删除数据
        if (false == $this->db->delete('mall_ads', array('ad_id' => $attr['id']))) {
            $retData['code'] = 1104;
            $retData['msg'] = "delete ad failed, ".$this->db->_error_message();
            return $retData;
        }

        // 如果存在需要重排的顺序，同时更新
        if (!empty($attr['reset_sort'])) {
            foreach ($attr['reset_sort'] as $v) {
                if (false == $this->db->where('ad_id', $v['id'])->update('mall_ads', array('sort_order' => $v['order']))) {
                    $retData['code'] = 1103;
                    $retData['msg'] = "update ad order failed, ".$this->db->_error_message();
                    return $retData;
                }
            }
        }

        $this->db->trans_complete();
        return $retData;
    }

    /**
     * 广告修改排序
     */
    public function reset_ad_order($attr) {

        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        $this->db->trans_start();

        foreach ($attr['reset_sort'] as $v) {
            if (false == $this->db->where('ad_id', $v['id'])->update('mall_ads', array('sort_order' => $v['order']))) {
                $retData['code'] = 1103;
                $retData['msg'] = "update ad order failed, ".$this->db->_error_message();
                return $retData;
            }
        }

        $this->db->trans_complete();
        return $retData;
    }

    /* 获取首页广告列表 */
    function get_index_ads($location='index',$lang_id = true,$limit = 10) {
        if($lang_id)
        {
            $language_id=intval($this->session->userdata('language_id'));
            $where['language_id'] = $language_id;
        }
        $where['location'] = $location;
        $where['status'] = 1;
        $order_by["sort_order"] ="asc";
        return $this->get_list("ad_img,ad_url",$where,[],$limit,0,$order_by);
    }

}
