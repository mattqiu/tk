<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Add_weight_fee extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index() {
        $this->_viewData['title'] = lang('china_weight_fee');
		$begin_code = $this->input->post('begin_code');
		if(!$begin_code){
			$this->_viewData['error_tip']="请选择省份";
		}
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
				    "select"=>'goods_name,store_code,shipper_id',
                    "where"=>["language_id"=>$cur_language_id,'goods_sn_main'=>$goods_sn_main]
                ]);
				$def_code = $this->db->select('store_location_code')->where('shipper_id',$goods['shipper_id'])->get('mall_goods_shipper')->row_array();
                $this->load->model("tb_trade_addr_linkage");
				$def_code_name = $this->tb_trade_addr_linkage->get_one("name",
                    ["code"=>$def_code['store_location_code'],"country_code"=>156]);
                $this->_viewData['goods_name']=$goods['goods_name'];
				$this->_viewData['store_code']=$def_code_name['name'];
			}
		}
		$this->_viewData['codes'] = $this->m_admin_user->get_country_province_code();
		if($begin_code){
			$sql = "select tff.id,tff.dest_code code,tff.start_weight_fee,tff.add_weight_fee,tal.name from trade_freight_fee tff,
			trade_addr_linkage tal where tff.dest_code=tal.code and tal.country_code=156 and tff.begin_code=".$begin_code;
			if($goods_sn_main){
				$sql .= " and tff.goods_sn_main=".$goods_sn_main;
			}else{
				$sql .= " and tff.goods_sn_main=''";
			}
            $provice = $this->db->query($sql);
            if($provice){
                $provice = $provice->result_array();
                foreach($provice as &$item){
                    $item['start_weight_fee'] = $item['start_weight_fee'] / 100;
                    $item['add_weight_fee'] = $item['add_weight_fee'] / 100;
                }
            }
			if($provice){
				$this->_viewData['codes'] = $provice;
			}
		}
		$this->_viewData['begin_code'] = $begin_code;
		$this->_viewData['goods_sn_main'] = $goods_sn_main;
        parent::index('admin/');
    }

    public function do_add() {
		$start_weight_fees = $this->input->post('start_weight_fee');
		$add_weight_fees = $this->input->post('add_weight_fee');
		$begin_code = $this->input->post('begin_code');
		$ids = $this->input->post('id');
		$country_code = $this->input->post('country_code');

		$goods_sn_main = $this->input->post('goods_sn_main') ?  trim($this->input->post('goods_sn_main')) : 0;
		$goods_type = $goods_sn_main ? 1 : 0;
		$data = array();

		if(!$begin_code){
			die(json_encode(array('success'=>0,'msg'=>'省份不為空')));
		}

		$is_all_zero = TRUE;
		foreach($start_weight_fees as $dest_code=>$start_weight_fee){
			if( $start_weight_fee > 0 ){
				$is_all_zero = FALSE;
			}
			if(!is_numeric($start_weight_fee)){
				die(json_encode(array('success'=>0,'msg'=>'运费格式不对')));
			}
		}
		//if($is_all_zero == TRUE){
			//die(json_encode(array('success'=>0,'msg'=>'省份首重运费不能全部为0')));
		//}

		$this->db->trans_start();
		if($ids){ /** id 运费记录存在 做批量修改操作 */

			foreach($start_weight_fees as $dest_code=>$start_weight_fee){
				$fee['dest_code'] = $dest_code;
				$fee['start_weight_fee'] = $start_weight_fee*100;
				$fee['add_weight_fee'] = $add_weight_fees[$dest_code]*100;
				$fee['id'] = $ids[$dest_code];
				$data[] = $fee;
			}
			$this->db->update_batch('trade_freight_fee', $data,'id');

		}else{
			/** 新增运费记录 */
			foreach($start_weight_fees as $dest_code=>$start_weight_fee){
				$fee['begin_code'] = $begin_code;
				$fee['dest_code'] = $dest_code;
				$fee['start_weight_fee'] = $start_weight_fee*100;
				$fee['add_weight_fee'] = $add_weight_fees[$dest_code]*100;
				$fee['goods_sn_main'] =$goods_sn_main;
				$fee['country_code'] =$country_code;
				$fee['goods_type'] =$goods_type;
				$data[] = $fee;
			}
			$this->db->insert_batch('trade_freight_fee', $data);
		}
		$this->db->trans_complete();
		die(json_encode(array('success'=>1)));
	}
    


}