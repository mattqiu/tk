<?php

class tb_mall_goods_brand extends MY_Model {
    protected $table_name = "mall_goods_brand";
    function __construct() {
        parent::__construct();
    }
    /* 获取品牌分页列表  */
    public function get_brand_list_page($filter, $per_page = 10) {
        $this->db->from('mall_goods_brand');
        $this->filter_for_brand($filter);

        return $this->db->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
    }

    /* 获取品牌总数  */
    public function get_brand_total($filter) {
        $this->db->from('mall_goods_brand');
        $this->filter_for_brand($filter);

        return $this->db->get()->num_rows();
    }

    /* 查询条件  */
    public function filter_for_brand($filter){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'keywords':
                    $this->db->where("brand_name like '%$v%'", null, false);
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    /* 获取品牌列表  */
    public function getBrandList($cate_id=null) {
        $language_id=intval($this->session->userdata('language_id'));

        if($cate_id) {
            $cate_id_arr=explode(',', $cate_id);
            $this->db->where_in('cate_id',$cate_id_arr);
        }
        //暂时隐藏不需要品牌
        $this->db->where_not_in('brand_id', [4,5,6,37,38,39]);

        return $this->db->where('language_id',$language_id)
            ->limit(10)->get('mall_goods_brand')->result_array();
    }

    /* 获取品牌列表  */
    public function getBrandListALL() {
        return $this->db->get('mall_goods_brand')->result_array();
    }

    /* 检查该doba品牌是否已经存在  */
    function check_doba_brand($brand_name) {
        $rs=$this->db->select('brand_id')->where('brand_name',$brand_name)->get('mall_goods_brand')->row_array();

        return empty($rs) ? false : $rs['brand_id'];
    }

    /* 增加品牌  */
    function add_brand($data_db) {
        if($this->check_brand($data_db['brand_name'], $data_db['language_id'])) {
            return  array('error'=>1,'msg' => lang('brand_exists'),'data'=>'2');
        }

        $this->db->trans_begin();
        $this->db->insert('mall_goods_brand',$data_db);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return  array('error'=>1,'msg' => lang('info_failed'),'data'=>'2');
        } else {
            // 同步到ERP品牌添加
            $data_db['brand_id'] = $this->db->insert_id();
            $url = 'Api/Commodity/addBrand';
            $snyRet = erp_api_query($url, $data_db);
            if ($snyRet['code'] == 200) {
                $this->db->trans_commit();
                return  array('error' => 0,'msg' => lang('info_success'));
            } else {
                $this->db->trans_rollback();
                return  array('error' => 1,'msg' => 'syn'.$snyRet['msg'],'data'=>'3');
            }
        }
    }

    /* 更新品牌  */
    function update_brand($data_db) {
        // 查询数据
        $brand_id = $data_db['brand_id'];
        $exitData = $this->db->where('brand_id',$brand_id)->get('mall_goods_brand')->row_array();
        $different = array_diff_assoc($data_db,$exitData);
        if ($different) {
            $this->db->trans_begin();
            $this->db->where('brand_id',$brand_id)->update('mall_goods_brand',$different);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return  array('error' => 1,'msg' => lang('info_failed'));
            } else {
                $url = 'Api/Commodity/updateBrand';
                $different['brand_id'] = $brand_id;
                $snyRet = erp_api_query($url, $different);
                if ($snyRet['code'] == 200) {
                    $this->db->trans_commit();
                    return  array('error' => 0,'msg' => lang('info_success'));
                } else {
                    $this->db->trans_rollback();
                    return  array('error' => 1,'msg' => $snyRet['msg']);
                }
            }
        } else {
            return  array('error' => 0,'msg' => lang('info_success'));
        }
    }

    /* 获取品牌信息  */
    function get_brand($id) {
        return $this->db->where('brand_id',$id)->get('mall_goods_brand')->row_array();
    }

    /* 检查品牌名称是否重复 */
    function check_brand($brand_name,$lang_id) {
        $rs = $this->db->where('language_id',$lang_id)->where('brand_name',$brand_name)->get('mall_goods_brand')->row_array();

        return !empty($rs) ? true : false;
    }

    /**
     * 添加品牌从ERp传递过来的数据
     */
    public function add_brand_from_erp($brandData) {
        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        if($this->check_brand($brandData['brand_name'], $brandData['language_id'])) {
            $retData['code'] = 1001;
            $retData['msg'] = "分类名重复";
            return $retData;
        }

        $this->db->trans_begin();
        $this->db->insert('mall_goods_brand', $brandData);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $retData['code'] = 1001;
            $retData['msg'] = "添加失败";
            return $retData;
        }
        $this->db->trans_commit();
        return $retData;
    }

}