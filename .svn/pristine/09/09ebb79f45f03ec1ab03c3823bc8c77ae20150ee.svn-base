<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prepaid_card extends MY_Controller {

	
  	public function index($card_no=null)
  	{
		if($card_no == null){
			$this->_viewData['no_data'] = '沒有卡號數據';
			$this->load->view('mall/prepaid_card', $this->_viewData);
		}else{
			$card_info = $this->db->where('card_no',$card_no)->get('users_prepaid_card_info')->row_array();
			if(!$card_info){
				$this->_viewData['no_data'] = '沒有卡號數據';
			}
			$this->_viewData['card_info'] = $card_info;
			$this->load->view('mall/prepaid_card', $this->_viewData);
		}
  	}
	
}
