<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Prepaid_card extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){

		/** 如果已经申请了预付卡 */
		$count = $this->db->from('users_prepaid_card_info')->where('uid',$this->_userInfo['id'])->count_all_results();
		if($count > 0){
			redirect(base_url('ucenter/take_out_cash'));exit;
		}

        $this->_viewData['title'] = lang('pre_card_title');
		$country_arr = array(
			'China'=>lang('con_china'),
			'Hong Kong'=>lang('con_hongkong'),
			'USA'=>lang('con_usa'),
			'Korea'=>lang('con_korea'),
			'Macao'=>'Macao',
		);
		$this->_viewData['country_arr'] = $country_arr;
		$my_amount = $this->_userInfo['amount'];

		$this->_viewData['my_amount'] = $my_amount;
        parent::index();
    }

	/** 申請預付卡 */
	public function do_apply(){

		$ret_data = array(
			'code' => 0,
			'msg' => "",
		);

		$attr = $this->input->post(null,true);

		$attr['uid'] = $this->_userInfo['id'];
		$attr['agreement'] = isset($attr['agreement']) ? $attr['agreement'] : 'off';
		isset($attr['card_no'])?$attr['card_no']=trimall($attr['card_no']) : '';
		$rules = array(
			'name' => "required|max:64",
			'chinese_name' => "max:64",
			'mobile' => "required|numeric",//|between:6,16
			'nationality' => "required",
			'issuing_country' => "required",
			//'ID_address' => "required|max:255",
			'ID_type' => "required|integer|in:0,1",
			'ID_no' => "required|alpha_dash",
			'ID_front' => "required",
			'address_prove' => "required",
			'ID_reverse' =>"required_without:ID_front",
			'country' => "required",
			'ship_to_address' => "required|max:255",
			'uid' => "required|integer|in:{$attr['uid']}",
			//'agreement' => "accepted",
			'email' => "required|email",
			'card_no' => "required|min:16|max:16",
		);
		isset($attr['id']) && $attr['id'] ? $rules['id'] = "integer":$rules['agreement'] = "accepted";

		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_lang_msg();
			echo json_encode($ret_data);
			exit;
		}

		/*if(isset($attr['card_no']) && $attr['card_no']){
			$count = $this->db->from('users_prepaid_card_no')->where('status','1')->where('card_no',$attr['card_no'])->count_all_results();
			if($count === 0){
				$ret_data['code'] = 101;
				$ret_data['msg'] = lang('prepaid_card_no_exist');
				echo json_encode($ret_data);
				exit;
			}
		}*/

		if(isset($attr['id']) && $attr['id']){
			unset($attr['agreement']);
			$attr['status'] = 1;
			$this->db->where('id',$attr['id'])->update('users_prepaid_card_info',$attr);
			$id = $this->db->affected_rows();

		}else{

			if($this->_userInfo['amount'] < 5){
				$ret_data['code'] = 101;
				$ret_data['msg'] = lang('cur_commission_lack');
				echo json_encode($ret_data);
				exit;
			}

			$this->db->trans_start();

			$this->db->where('id',$attr['uid'])->set('amount','amount - 5',FALSE)->update('users');
			$this->load->model('m_commission');
			$this->m_commission->commissionLogs($attr['uid'],20,-5);
			unset($attr['agreement']);
			$attr['status'] = 1;
			$this->db->insert('users_prepaid_card_info',$attr);

			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				$id = FALSE;
			}else{
				$id = TRUE;
			}
		}
		if($id){
			$ret_data['msg'] = lang('pc_applied_success');
		}else{
			$ret_data['code'] = 101;
			$ret_data['msg'] = lang('try_again');
		}
		echo json_encode($ret_data);
		exit;
	}

	public function upScan() {

		if(isset($_FILES['ID_front'])) { //上传图片
			$picname = $_FILES['ID_front']['name'];
			$picsize = $_FILES['ID_front']['size'];
			if ($picname != "") {
				if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
					echo json_encode(lang('id_card_scan_too_large'));
					exit;
				}
				$type = strstr($picname, '.'); //限制上传格式
				if (strtolower($type) != ".gif" && strtolower($type) != ".jpg" && strtolower($type)!=".bmp" && strtolower($type)!=".png") {
					echo json_encode(lang('id_card_scan_type_ext_error'));
					exit;
				}
				//防止上传图片名重名
				do{
					$rand = rand(100, 999);
					$pathImg = 'prepaid_card/'.md5(date("His") . $rand) . $type;//待保存的图片路径
					$count =  $this->db->from('users_prepaid_card_info')->where('ID_front',$pathImg)->count_all_results();
				}
				while ($count > 0); //如果是重复路径名则重新生成名字

				/*上传图片*/
				$this->load->model('m_do_img');
				if($this->m_do_img->upload($pathImg,$_FILES['ID_front']['tmp_name'])){
					$this->db->where('uid',$this->_userInfo['id'])->update('users_prepaid_card_info',array('ID_front'=>$pathImg));
				}
			}
			$size = round($picsize / 1024, 2); //转换成kb
			$arr = array(
				'name' => $picname,
				'pic' => $pathImg,
				'size' => $size,
				'picUrl' => config_item('img_server_url').'/'.$pathImg
			);
			echo json_encode($arr);
		}else{
			echo '';exit;
		}
	}

	public function delImg(){
		$action = $this->input->get('act');
		if ($action == 'delimg') { //删除图片
			$filename = $this->input->post('imagename');
			$back = $this->input->get('back');
			if (!empty($filename)) {
				$this->load->model('m_user_helper');
				$pc_info = $this->db->select('id')->where('uid',$this->_userInfo['id'])->get('users_prepaid_card_info')->row_array();
				if($pc_info){
					$fields = $back == 0 ? 'ID_front' : 'ID_reverse';
					if($back == 0){
						$fields = 'ID_front';
					}else if($back == 1){
						$fields = 'ID_reverse';
					}else if($back == 2){
						$fields = 'address_prove';
					}else{
						echo '0';exit;
					}
					$this->db->where('id',$pc_info['id'])->update('users_prepaid_card_info',array($fields=>''));
				}
				$this->load->model('m_do_img');
				$this->m_do_img->delete($filename);
				echo '1';exit;
			} else {
				echo '1';exit;
			}
		}
	}
	public function upScan2() {
		if(isset($_FILES['ID_reverse'])) { //上传图片
			$picname = $_FILES['ID_reverse']['name'];
			$picsize = $_FILES['ID_reverse']['size'];
			if ($picname != "") {
				if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
					echo json_encode(lang('id_card_scan_too_large'));
					exit;
				}
				$type = strstr($picname, '.'); //限制上传格式
				if (strtolower($type) != ".gif" && strtolower($type) != ".jpg" && strtolower($type)!=".bmp" && strtolower($type)!=".png") {
					echo json_encode(lang('id_card_scan_type_ext_error'));
					exit;
				}

				//防止上传图片名重名
				do{
					$rand = rand(100, 999);
					$pathImg = 'prepaid_card/'.md5(date("His") . $rand) . $type;//待保存的图片路径
					$count =  $this->db->from('users_prepaid_card_info')->where('ID_reverse',$pathImg)->count_all_results();
				}
				while ($count > 0); //如果是订单号重复则重新生成订单

				/*上传图片*/
				$this->load->model('m_do_img');
				if($this->m_do_img->upload($pathImg,$_FILES['ID_reverse']['tmp_name'])){
					$this->db->where('uid',$this->_userInfo['id'])->update('users_prepaid_card_info',array('ID_reverse'=>$pathImg));
				}
			}
			$size = round($picsize / 1024, 2); //转换成kb
			$arr = array(
				'name' => $picname,
				'pic' => $pathImg,
				'size' => $size,
				'picUrl' => config_item('img_server_url').'/'.$pathImg
			);
			echo json_encode($arr); //输出json数据
		}else{
			echo '';exit;
		}
	}

	public function upScan3() {
		if(isset($_FILES['address_prove'])) { //上传图片
			$picname = $_FILES['address_prove']['name'];
			$picsize = $_FILES['address_prove']['size'];
			if ($picname != "") {
				if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
					echo json_encode(lang('id_card_scan_too_large'));
					exit;
				}
				$type = strstr($picname, '.'); //限制上传格式
				if (strtolower($type) != ".gif" && strtolower($type) != ".jpg" && strtolower($type)!=".bmp" && strtolower($type)!=".png") {
					echo json_encode(lang('id_card_scan_type_ext_error'));
					exit;
				}

				//防止上传图片名重名
				do{
					$rand = rand(100, 999);
					$pathImg = 'prepaid_card/'.md5(date("His") . $rand) . $type;//待保存的图片路径
					$count =  $this->db->from('users_prepaid_card_info')->where('address_prove',$pathImg)->count_all_results();
				}
				while ($count > 0); //如果是订单号重复则重新生成订单

				/*上传图片*/
				$this->load->model('m_do_img');
				if($this->m_do_img->upload($pathImg,$_FILES['address_prove']['tmp_name'])){
					$this->db->where('uid',$this->_userInfo['id'])->update('users_prepaid_card_info',array('address_prove'=>$pathImg));
				}
			}
			$size = round($picsize / 1024, 2); //转换成kb
			$arr = array(
				'name' => $picname,
				'pic' => $pathImg,
				'size' => $size,
				'picUrl' => config_item('img_server_url').'/'.$pathImg
			);
			echo json_encode($arr); //输出json数据
		}else{
			echo '';exit;
		}
	}
}

