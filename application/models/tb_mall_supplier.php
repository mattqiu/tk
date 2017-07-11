<?php
/**
 * @author jason
 */
class tb_mall_supplier extends MY_Model {

    protected $table_name = "mall_supplier";
    function __construct() {
        parent::__construct();
    }

    /**
     * 获得用户推荐供应商映射表
     */
    public function get_recommend_map(){
        $res = $this->db->select('*')->where('supplier_recommend <>',0)->from('mall_supplier')->get()->result_array();

        $map = array();
        foreach($res as $k => $v){
            $uid = $v['supplier_recommend'];

            if(!isset($map[$uid])){
                $list = $this->db->select('supplier_id')->where('supplier_recommend',$uid)->from('mall_supplier')->get()->result_array();
                foreach($list as $v2){
                    $map[$uid][] = $v2['supplier_id'];
                }
            }
        }

        return $map;
    }
    /**
     * 查询商品的发货商地址
     */
    public function get_shipping_address($shipper_id){
//        return $this->db->from('mall_supplier')->select('country_code,addr_lv2,addr_lv3')->where('supplier_id',$shipper_id)->get()->row_array();
        return $this->get_one('country_code,addr_lv2,addr_lv3',['supplier_id'=>$shipper_id]);
    }

    /**
     * 获得用户推荐供应商映射表
     * @param int $start
     * @param int $num
     * @return mixed
     */
    public function getSupplierRecommendMap($start = 0, $num = 10){
        $sql = 'select a.supplier_id, a.supplier_recommend as uid, b.user_rank from mall_supplier a left join users b on a.supplier_recommend=b.id';
        $sql .= ' where a.supplier_recommend>0 order by a.supplier_id asc limit '.$start.','.$num;
        $res = $this->db->query($sql)->result_array();
        return $res;
    }

	/**
	 * Author:Soly
	 * Date:2017-05-26
	 * 供应商QQ信息缓存
	 * @param (int)     $supplier_id       供应商id
	 * @param (string)  $redis_key         redis key
	 * @param (int)     $redis_cache_time  redis 缓存的时间
	 * @return (array)
	 */
	public function getSupplierQQ($supplier_id, $redis_key = '', $redis_cache_time = 3600) {
		if (empty($supplier_id)) return [];
		
		if (!empty($redis_key)) {
			$suppliers = $this->redis_get($redis_key);
			if (!empty($suppliers)) return unserialize($suppliers);
		}
		
		$tb_mall_supplier = $this->db->select('supplier_qq')->where('supplier_id', $supplier_id)->get($this->table_name)->row_array();

		// 查询出来的数据为空直接返回
		if (empty($tb_mall_supplier['supplier_qq'])) return [];
		
		// 
		$supplier_qq = explode(",", $tb_mall_supplier['supplier_qq']);
		$supplier_qq = array_filter($supplier_qq);
		
		if (!empty($redis_key)) $this->redis_set($redis_key, serialize($supplier_qq), $redis_cache_time);
		
		return $supplier_qq;
	}
}


