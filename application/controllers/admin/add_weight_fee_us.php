<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Add_weight_fee_us extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index() {
        $this->_viewData['title'] = lang('usa_weight_fee');

		$goods_sn_main = trim($this->input->post('goods_sn_main'));
		if($goods_sn_main){
            $this->load->model("tb_mall_goods_main");
            $count = $this->tb_mall_goods_main->get_counts_auto([
                "where"=>["goods_sn_main"=>$goods_sn_main]
            ]);
			if($count == 0){
				$this->_viewData['error_tip']="主SKU不存在";
			}else{
				$cur_language_id = get_cookie('curLan_id', true);
				if (empty($cur_language_id)) {
					$cur_language_id = 1;
				}
                $goods = $this->tb_mall_goods_main->get_one_auto([
                    "select"=>'goods_name,store_code',
                    "where"=>["language_id"=>$cur_language_id,'goods_sn_main'=>$goods_sn_main]
                ]);
//				$def_code = $this->db->select('rule_type')->where('store_code',$goods['store_code'])->get('mall_goods_storehouse')->row_array();
				$str = '';
//				if ($def_code['rule_type'] != '4'){
//					$str = "不是美国地区的商品";
//				}
				$this->_viewData['goods_name']=$goods['goods_name'];
				$this->_viewData['store_code']=$str;


				$provice = $this->db->query("select * from trade_freight_fee where goods_sn_main = '$goods_sn_main'")->row_array();

				if($provice){
					$this->_viewData['codes'] = $provice;
				}
			}
		}

		$this->_viewData['goods_sn_main'] = $goods_sn_main;
        parent::index('admin/');
    }

    public function do_add() {
		$start_weight_fees = $this->input->post('start_weight_fee');
		$ids = $this->input->post('id');

		if(!is_numeric($start_weight_fees)){
			die(json_encode(array('success'=>0,'msg'=>'运费格式不对')));
		}

		$goods_sn_main = trim($this->input->post('goods_sn_main'));
		$goods_type = $goods_sn_main ? 1 : 0;

		if(!$goods_sn_main){
			die(json_encode(array('success'=>0,'msg'=>'sku是必填項')));
		}

		$this->db->trans_start();
		if($ids){ /** id 运费记录存在 做批量修改操作 */

			$fee['start_weight_fee'] = $start_weight_fees*100;
			$fee['add_weight_fee'] = 0;
			$fee['id'] = $ids;

			$this->db->where('id',$ids)->update('trade_freight_fee', $fee);

		}else{
			/** 新增运费记录 */
			$count = $this->db->from('trade_freight_fee')->where('country_code',840)->where('goods_sn_main',$goods_sn_main)->count_all_results();
			if($count){
				die(json_encode(array('success'=>0,'msg'=>'此商品已存在運費')));
			}
			$fee['start_weight_fee'] = $start_weight_fees*100;
			$fee['goods_sn_main'] =$goods_sn_main;
			$fee['country_code'] =840;
			$fee['goods_type'] =$goods_type;
			$this->db->insert('trade_freight_fee', $fee);
		}
		$this->db->trans_complete();
		die(json_encode(array('success'=>1)));
	}
    


}