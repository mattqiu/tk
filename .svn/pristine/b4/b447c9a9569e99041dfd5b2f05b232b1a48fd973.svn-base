<?php
/**
 * @author Terry
 */
class tb_mall_goods_number_exception extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 查询商品库存异常记录
     * @return boolean
     * @author Terry
     */
    public function selectAll($filter,$perPage = 10) {
        $this->db->from('mall_goods_number_exception');
        $this->filterForNews($filter);
//        $lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
//		$this->db->where('language_id',$lang_id);
        $list = $this->db->select('')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        foreach($list as $k=>$v){
            if(empty($v['number_english'])){
                $list[$k]['number_english'] = lang('number_null');
            }
            if(empty($v['number_zh'])){
                $list[$k]['number_zh'] = lang('number_null');
            }
            if(empty($v['number_hk'])){
                $list[$k]['number_hk'] = lang('number_null');
            }
            if(empty($v['number_kr'])){
                $list[$k]['number_kr'] = lang('number_null');
            }
        }
       return $list;
    }

    /**
     * 获取分页总数
     */
    function  getExceptionRows($filter){
        $this->db->from('mall_goods_number_exception');
        $this->filterForNews($filter);
        $lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
        $this->db->where('language_id',$lang_id);
        $row = $this->db->count_all_results();
        return $row;
    }

    public function filterForNews($filter){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', strtotime($v));
                    break;
                case 'goods_sn':
                    if(is_numeric($v)){
                        $this->db->where('goods_sn', $v);
                    }else{
                        $this->db->where('goods_sn', $v);
                    }
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    /**
     * @param $sku 商品sku goods_sn
     */
    public function number_exception($sku){
        //95625646-1 39149590-1 46952052-1
        $this->load->model('tb_mall_goods');
        $list = $this->tb_mall_goods->getOneGoods($sku);

        $number_english = null;
        $number_zh = null;
        $number_hk = null;
        $number_kr = null;
        $num = null;
        $goods1 = array();
        $goods1['goods_sn'] = null;
        $goods1['goods_name'] = null;
        $goods1['language_id'] = 2;

        if(!empty($list)){
            $num = $list[0]['goods_number'];
        }

        foreach($list as $k=>$v){
            if($num != $v['goods_number']){
                $goods1['goods_sn'] = $v['goods_sn'];
                $goods1['goods_name'] = $list[$k]['goods_name'];
            }
            if ($v['language_id'] == 2) {
                $number_zh = $v['goods_number'];
//                    $this->db->insert('mall_goods_number_exception',$goods2);
            }
            if ($v['language_id'] == 3) {
                $number_hk = $v['goods_number'];
            }
            if ($v['language_id'] == 1) {
                $number_english = $v['goods_number'];
            }
            if ($v['language_id'] == 4) {
                $number_kr = $v['goods_number'];
            }

        }
        $this->db->from('mall_goods_number_exception');
        $this->db->where('goods_sn',$goods1['goods_sn']);
        $row = $this->db->count_all_results();
        if(!empty($goods1['goods_sn'])){
            $goods1['number_english'] = $number_english;
            $goods1['number_zh'] = $number_zh;
            $goods1['number_hk'] = $number_hk;
            $goods1['number_kr'] = $number_kr;
            if($row > 0){
                //存在则修改
                $this->db->where('goods_sn', $goods1['goods_sn'])->update('mall_goods_number_exception',$goods1);
            }else {
                //否则添加
                $this->db->insert('mall_goods_number_exception', $goods1);
            }
        }
    }

    /* 定时检查库存信息 */
    public function check_goods_number(){
        $res = $this->db->query("select goods_sn from mall_goods where goods_sn not like '%dbus%'")->result_array();
        $this->db->trans_begin();
        foreach($res as $item){
            $this->number_exception($item['goods_sn']);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
    }
}
