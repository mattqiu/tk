<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Admin_as_refund_list extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

	public function index(){
		$this->_viewData['title'] = lang('admin_as_refund');

		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['as_id'] = isset($searchData['as_id'])?$searchData['as_id']:'';
		$searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
		$searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
                $page_num = isset($searchData['page_num']) ? $searchData['page_num'] : 30;
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['email'] = isset($searchData['email'])?$searchData['email']:'';
		$searchData['batch_id'] = isset($searchData['batch_id'])?$searchData['batch_id']:'';
		$searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
		$this->_viewData['list'] = $this->m_admin_user->getAfterSaleList($searchData,$this->_adminInfo['id'],$this->_curControlName,$page_num);

		$this->load->library('pagination');
		$url = 'admin/admin_as_refund_list';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $this->m_admin_user->getAfterSaleRows($searchData,$this->_adminInfo['id'],$this->_curControlName);
                $config['per_page'] = $page_num;
		$config['cur_page'] = $searchData['page'];
                $this->_viewData['page_num'] = $page_num;
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);

        if($searchData['batch_id']){
            $this->load->model('tb_admin_after_sale_batch');
            $batch_info = $this->tb_admin_after_sale_batch->get_batch_info($searchData['batch_id']);
            $this->_viewData['batch_info'] = $batch_info;
        }

		$this->_viewData['searchData'] = $searchData;
		parent::index('admin/','after_sale_list');
	}

	/** 退款成功操作 */
	public function do_refund(){
		if($this->input->is_ajax_request()){

			$as_id  = $this->input->post('as_id');
			$count = $this->db->from('admin_after_sale_order')->where('as_id',$as_id)->count_all_results();
			if($count == 0 ){
				echo json_encode(array('success'=>0,'msg'=>lang('admin_as_not_exist')));exit;
			}
			$info = array(
				'status'=>3,
			);
			$this->db->where('as_id',$as_id)->update('admin_after_sale_order',$info);
			$affected = $this->db->affected_rows();

			$this->m_log->admin_after_sale_remark($as_id,$this->_adminInfo['id'],"已退款到银行");
			echo json_encode(array('success'=>$affected,'msg'=>lang('update_success')));exit;
		}
	}

	/** 得到备注信息 */
	public function get_as_remark_info(){
		$as_id = $this->input->post('id');
		$info = $this->db->query("select ar.*,au.email from admin_after_sale_remark ar,admin_users au where ar.admin_id=au.id and as_id='$as_id' order by ar.create_time desc")->result_array();
		$table_str = '<table>';
		if($info)foreach($info as $item){
			$table_str .= "<tr><td style='width:150px'>{$item['create_time']}</td><td style='width:150px'>{$item['email']}</td><td style='width:180px'>{$item['remark']}</td></tr>";
		}
		$table_str .= '</table>';
                $order = $this->db->select('refund_method,card_number,account_bank,account_name')->where('as_id',$as_id)->get('admin_after_sale_order')->row_array();
                $payee_str ="";
                if(isset($order["refund_method"]) && $order["refund_method"] ==0){
                    $payee_str = '<div style="text-align:center">收款人信息</div>';
                    $payee_str .= '<div>开户行名称:'.$order["account_bank"].'</div>';
                    $payee_str .= '<div>银行账号:'.$order["card_number"].'</div>';
                    $payee_str .= '<div>开户名:'.$order["account_name"].'</div>';
                }    
		die(json_encode(array('success'=>1,'result'=>array('table_str'=>$table_str,'payee_str'=>$payee_str,'as_id'=>$as_id))));
	}

	/** 添加备注信息 */
	public function add_as_remark_info(){
		$data = $this->input->post();
		if(!$data['remark']){
			die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
		}
		$insert_arr = array(
			'as_id'=>$data['as_id'],
			'admin_id'=>$this->_adminInfo['id'],
			'remark'=>$data['remark'],
		);
		$this->db->insert('admin_after_sale_remark',$insert_arr);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			$msg = '';
		}else{
			$msg = lang('try_again');
		}
		die(json_encode(array('success'=>$insert_id,'msg'=>$msg)));
	}

	/** 驳回售后订单 */
	public function as_reject(){
		$data = $this->input->post();
		$update_arr = array(
			'status'=>$data['status'],
                        'batch_id'=>NULL,
			'reject_remark'=>$data['remark'],
		);
                $as_order = $this->db->select('img,uid')->where('as_id',$data['as_id'])->get('admin_after_sale_order')->row_array();
		if($data['status'] == 'del'){
			$update_arr['status'] = 7;
			$update_arr['img'] = null;
		}

		$this->db->trans_start();

		$this->db->where('as_id',$data['as_id'])->update('admin_after_sale_order',$update_arr);
		$affected_rows = $this->db->affected_rows();

		if($data['status'] == 4){
			$remark = lang('admin_after_sale_status_4').':'.$data['remark'];
		}else if($data['status'] == 5){
			$remark = lang('admin_after_sale_status_5').':'.$data['remark'];
		}else if($data['status'] == 6){
			$remark = lang('admin_after_sale_status_6');
		}else if($data['status'] == 7){
			$remark = lang('admin_after_sale_status_7');
		}else if($data['status'] == 'del'){
			$remark = lang('admin_as_del_upload_info');
		}

		if($data['status'] == 'del' && $as_order){
			$this->load->model('m_do_img');
			$this->m_do_img->delete($as_order['img']);
		}

		$insert_arr = array(
			'as_id'=>$data['as_id'],
			'admin_id'=>$this->_adminInfo['id'],
			'remark'=>$remark,
		);
                if($data['status'] == 6){//作废的时候，更改用户状态为正常
                    $this->db->where('id', $as_order["uid"])->set('status', 1, FALSE)->update('users');
                }
		$this->db->insert('admin_after_sale_remark',$insert_arr);
		$insert_id = $this->db->insert_id();

		$this->db->trans_complete();

		if($affected_rows){
			$msg = '';
		}else{
			$msg = lang('try_again');
		}
		die(json_encode(array('success'=>$affected_rows,'msg'=>$msg)));
	}

	/** 上传回执单 */
	public function admin_upload_voucher(){

		$data = $this->input->post();
		/*if (!is_dir('upload/voucher/')){
			mkdir('upload/voucher/', DIR_WRITE_MODE);
		}*/

		if(isset($_FILES['userfile'])) { //上传图片
			$picname = $_FILES['userfile']['name'];
			$picsize = $_FILES['userfile']['size'];
			if ($picname != "") {
				if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
					$error = array('success'=>0,'msg'=>lang('id_card_scan_too_large'));
					echo json_encode($error);
					exit;
				}
				$type = strstr($picname, '.'); //限制上传格式
				if ($type != ".gif" && $type != ".jpg" && $type!=".bmp" && $type!=".png") {
					$error = array('success'=>0,'msg'=>lang('id_card_scan_type_ext_error'));
					echo json_encode($error);
					exit;
				}

				//防止上传图片名重名
				do{
					$rand = rand(100, 999);
					$pathImg = 'voucher/'.md5(date("His") . $rand) . $type;//待保存的图片路径
					$count =  $this->db->from('admin_after_sale_order')->where('img',$pathImg)->count_all_results();
				}
				while ($count > 0); //如果是订单号重复则重新生成订单

				/*上传图片*/
				$this->load->model('m_do_img');
				if($this->m_do_img->upload($pathImg,$_FILES['userfile']['tmp_name'])){

					$this->db->trans_start();
					$update_arr = array(
						'img'=>$pathImg,
						'status'=>3,
					);
					$this->db->where('as_id',$data['as_id'])->update('admin_after_sale_order',$update_arr);
					$insert_arr = array(
						'as_id'=>$data['as_id'],
						'admin_id'=>$this->_adminInfo['id'],
						'remark'=>lang('admin_as_upload_info')
					);
					$this->db->insert('admin_after_sale_remark',$insert_arr);

					$order = $this->db->select('order_id,type,refund_method,refund_amount')->where('as_id',$data['as_id'])->get('admin_after_sale_order')->row_array();
					if($order['type'] == 2 && $order['refund_method'] == 0){
						$this->load->model('m_trade');
						$insert_attr = array(
							'order_id' => $order['order_id'],
							'type' => 1,
							'remark' => sprintf(lang('admin_refund_amount'),$order['refund_amount']),
							'admin_id' => $this->_viewData['adminInfo']['id'],
						);
						$this->m_trade->add_order_remark_record($insert_attr);
						$insert_attr2 = array(
							'order_id' => $order['order_id'],
							'type' => 2,
							'remark' => sprintf(lang('admin_refund_amount'),$order['refund_amount']),
							'admin_id' => $this->_viewData['adminInfo']['id'],
						);
						$this->m_trade->add_order_remark_record($insert_attr2);
					}

					$this->db->trans_complete();
					if($this->db->trans_status() === FALSE){
						$error = array('success'=>0,'msg'=>'Upload Failure');
					}else{
						$error = array('success'=>1,'msg'=>lang('admin_after_sale_status_3'));
					}
				}else{
					$error = array('success'=>0,'msg'=>'Upload Failure');
				}
				echo json_encode($error); exit;
			}else{
				$error = array('success'=>0,'msg'=>'Please Select File');
				echo json_encode($error); exit;
			}

		}else{
			echo json_encode(array('success'=>0,'msg'=>'Please Select File'));exit;
		}
	}
}