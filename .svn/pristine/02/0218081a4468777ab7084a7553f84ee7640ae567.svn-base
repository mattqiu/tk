<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class my_tickets extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_tickets');
		$this->load->model('m_admin_user');
		$this->load->model('m_user');
		$this->load->model('tb_admin_tickets_reply');
		$this->load->model('tb_admin_tickets_attach');
		$this->load->model('tb_admin_tickets_logs');
		$this->load->model('tb_admin_tickets_template');
		$this->load->model('tb_admin_tickets_record');
    }

    public function index() {
        $this->_viewData['title'] = lang('my_tickets');
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
		$searchData['status'] =isset($searchData['status'])?$searchData['status']:'';
		//if($searchData['status']==''){ $searchData['status'] ='default';}//例外，默认排除已评分/已解决的工单
		$searchData['priority'] = isset($searchData['priority'])?$searchData['priority']:'';
		$searchData['language'] = isset($searchData['language'])?$searchData['language']:'';
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
		$searchData['o_time'] = isset($searchData['o_time'])?$searchData['o_time']:'';
		$searchData['o_id'] = isset($searchData['o_id'])?$searchData['o_id']:'';
		$searchData['admin_id'] = $this->_adminInfo['id'];//我名下的订单号
        $list = $this->tb_admin_tickets->get_tickets_list($searchData);
        $this->_viewData['list'] = $list;
		$url = 'admin/my_tickets';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_tickets->get_tickets_count($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
		$this->_viewData['searchData'] = $searchData;
		$o_url =  'admin/my_tickets';
		unset($searchData['o_time']);
		unset($searchData['o_id']);
		unset($searchData['admin_id']);
		add_params_to_url($o_url, $searchData);
		$this->_viewData['order_url'] = $o_url;//只做单一排序
		$this->load->model('tb_admin_tickets_customer_role');
		$role = $this->tb_admin_tickets_customer_role->get_customer_by_admin_id($this->_adminInfo['id']);//客服临时角色
		$this->_viewData['cus_role'] = !empty($role)?$role['role']:1;
		$this->_viewData['cus'] = $this->m_admin_user->get_customers();
        parent::index('admin/');
    }

	/** 查看其中一个工单 */
	public function view_or_reply_tickets($id = NULL){
		$this->_viewData['title'] = lang('tickets_info');
		if(is_numeric($id)){
			$admin_id = $this->_adminInfo['id'];
			$id_arr = array(
					'id'      =>$id,
					'admin_id'=>$admin_id,
			);
			$rows = $this->tb_admin_tickets->get_tickets_by_id_arr($id_arr);
			$user = '';
			if($rows){
				$user =  $this->tb_admin_tickets->get_users_info_by($rows['org']['uid']);
			}
			$status_arr = array(
						'tickets_id'=>$id,
						'old_data'=>100,
						'new_data'=>100,//已经查看
						'data_type'=>5,
						'admin_id'=>$admin_id,
						'is_admin'=>1,
				);
			$insert_id = $this->tb_admin_tickets_logs->add_log($status_arr);//log
			if($rows && $insert_id){
				if(isset($rows['org']) && !empty($rows['org'])){
					if($rows['org']['is_attach']==1){
						$is_reply = 0;
						$attach = $this->tb_admin_tickets_attach->get_attach_by_tickets_id($rows['org']['id'],$is_reply);//获取原始信息下的附件
						$rows['org']['attach'] = $attach;
					}
				}
				if(isset($rows['reply']) && !empty($rows['reply'])){
					foreach($rows['reply'] as &$row){
						if($row['is_attach']==1){
							$is_reply = 1;
							$attach = $this->tb_admin_tickets_attach->get_attach_by_tickets_id($row['id'],$is_reply);//获取回复信息下的附件
							$row['attach'] = $attach;
						}
				}
			}
			$customers = $this->m_admin_user->get_customers();
			$tickets_type = '';
			if(isset($rows['org']['uid'])){
				$country = $this->m_user->get_user_country_id($rows['org']['uid']);
				if($country){
					if(in_array($country['country_id'],array(1,4,8))){
						$tickets_type = config_item('tickets_problem_type');
					}else{
						$tickets_type = config_item('tickets_problem_type');
						unset($tickets_type[0]);
						//unset($tickets_type[12]);
						unset($tickets_type[13]);
					}
				}
			}
			$this->_viewData['all_cus'] 	 = $this->tb_admin_tickets->get_all_customers();
			$this->_viewData['tickets_user'] = $user;
			$this->_viewData['cus'] 	  	 = $customers;
			$this->_viewData['tickets_type'] = $tickets_type;
			$this->_viewData['rows'] 		 = $rows;
			$this->load->view('admin/view_or_reply_tickets',$this->_viewData);
			}else{
				echo '<p style="color: red;">'.lang("tickets_no_exist").'</p>';exit;
			}
		}else{
				echo '<p style="color: red;">'.lang("tickets_no_exist").'</p>';exit;
		}
	}

	public function get_template(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post(NULL,TRUE);
			if(is_numeric($data['t'])){
				$arr = $this->tb_admin_tickets_template->get_template($this->_adminInfo['id'],$type=$data['t']);
				foreach($arr as &$v){
					$v['content'] = str_replace(array('&nbsp;','<br>','<br>'),array(" ","\n","\r\n"),$v['content']);
				}
				die(json_encode(array('success' => 1,'msg'=>$arr,)));
			}
		}
	}


	/** 回复工单**/
	public function do_reply(){
		if(!$this->input->post() || count($this->input->post())<3 || !$this->input->is_ajax_request()){
			$msg = lang('tickets_send_fail');
			die(json_encode(array('success' => 0, 'msg' =>$msg )));
		}
		if($this->input->is_ajax_request()){
			$data = $this->input->post(NULL,TRUE);
			if(!isset($data['content']) && mb_strlen($data['content'],'utf8')>1500){
					die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit').'(600)')));
			}
			if(empty($data['content'])){
				if(!in_array($data['status'],array(3,6))){
					die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_content') )));
				}
			}
			$id_arr = array('id'=>$data['id'],'admin_id'=>$this->_adminInfo['id']);
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);//获取原始信息
			//$this->set_new_msg($org_arr['id']);
			$insert_arr = array(
					'content' 	=> str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['content'])),
					'uid'		=> $org_arr['uid'],
					'admin_id'	=> $this->_adminInfo['id'],
					'tickets_id'=> $org_arr['id'],
					'sender' 	=> 1,
					'is_attach'	=> count($data)>3?1:0,
			);
			$this->db->trans_start();//事务
			if($data['content']!='' && $data['status']==6){//申请关闭需要提交内容
				$this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);
				$insert_id = $this->tb_admin_tickets_reply->add_reply($insert_arr);
				$log_arr = array(
						'tickets_id'=> $org_arr['id'],
						'old_data'	=> $org_arr['status'],
						'new_data'	=> $data['status'],
						'data_type'	=> 4,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
			}elseif($data['content']=='' && $data['status']==6){//申请关闭不提交内容
				$insert_id = $this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);
				$log_arr = array(
						'tickets_id'=> $org_arr['id'],
						'old_data'	=> $org_arr['status'],
						'new_data'	=> $data['status'],
						'data_type'	=> 4,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
			}elseif($data['content']=='' && $data['status']==3){//待商议不提交内容
				if($org_arr['status']==3){
					$insert_id=true;
				}else{
					$insert_id = $this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);
				}
				$log_arr = array(
						'tickets_id'=>$org_arr['id'],
						'old_data'	=>$org_arr['status'],
						'new_data'	=>$data['status'],
						'data_type'	=>1,
						'admin_id'	=>$this->_adminInfo['id'],
						'is_admin'	=>1,
				);
			}elseif($data['status']==100){
				$insert_arr['type'] = 100;//注释
				$insert_id = $this->tb_admin_tickets_reply->add_reply($insert_arr);
				$log_arr = array(
						'tickets_id'=>$org_arr['id'],
						'old_data'	=>100,
						'new_data'	=>100,
						'data_type'	=>8,
						'admin_id'	=>$this->_adminInfo['id'],
						'is_admin'	=>1,
				);
			}else{
				$insert_id = $this->tb_admin_tickets_reply->add_reply($insert_arr);
				$this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);//改变状态
				$log_arr = array(
						'tickets_id'=>$id_arr['id'],
						'old_data'	=>$org_arr['status'],
						'new_data'	=>$data['status'],
						'data_type'	=>$data['status']==2?6:7,
						'admin_id'	=>$this->_adminInfo['id'],
						'is_admin'	=>1,
				);
			}
			$this->tb_admin_tickets_logs->add_log($log_arr);//log
			if($data['status']!=100){//注释不设置最后回复的人
				$this->tb_admin_tickets->set_tickets_last_reply_and_score($id_arr,1);//标志最后一条信息为客服回复
			}
			/**附件**/
			$content = $data['content'];
			$status = $data['status'];
			unset($data['content']);
			unset($data['status']);
			unset($data['id']);
			if($insert_id && !empty($data)){//发送成功且有附件
				$save_file_path = 'upload/tickets/'.date('Ym').'/';
				foreach($data as $val){
					$temp_arr =explode('|', urldecode($val));
					$new_save_path = $save_file_path.explode('/',$temp_arr[1])[3];

					$this->load->model('m_oss_api');
					$from_object = $temp_arr[1];
					$to_object   = $new_save_path;
					$mov_res     = $this->m_oss_api->copyObject(null, $from_object, null, $to_object);


					//$this->load->model('m_do_img');
					//if($this->m_do_img->mov_img($temp_arr[1],$new_save_path)){

					if($mov_res){

						$this->m_oss_api->doDeleteObject($from_object);

						$attach_arr =array(
								'tickets_id' => $insert_id,
								'name'	  	 => $temp_arr[0],
								'path_name'	 => $new_save_path,
								'extension'	 => $temp_arr[2],
								'is_reply'	 => 1,
						);
						$this->tb_admin_tickets_attach->add_attach($attach_arr);
					}
				}
				$this->db->trans_complete();
				if($this->db->trans_status()===FALSE){
					$msg = lang('tickets_send_fail');
				}else{
					$msg =lang('tickets_send_success');
				}
			}elseif($insert_id && empty($data)){//发送成功没有附件
				$this->db->trans_complete();
				if($this->db->trans_status()===FALSE){
					$msg = lang('tickets_send_fail');
				}else{
					if($status==6 && $content==''){
						$msg =lang('had_apply_tickets');
					}elseif($status==3 && $content==''){
						$msg =lang('change_status_success');
					}else{
						$msg =lang('tickets_send_success');
					}
				}
			}else{//没有发送成功
				$this->db->trans_complete();
				if($status==3 && $content==''){
					$msg = lang('change_status_fail');
				}else{
					$msg = lang('tickets_send_fail');
				}
			}
			die(json_encode(array('success' => $insert_id, 'msg' =>$msg )));
		}
	}

	/**修改工单的优先级*/
	public function change_tickets_priority(){
		$data = $this->input->post();
		if($this->input->is_ajax_request() && is_numeric($data['id'])){
			$id_arr = array('id'=>$data['id'],'admin_id'=>$this->_adminInfo['id']);
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
			$status_arr = array(
					'tickets_id'=> $data['id'],
					'old_data'	=> $org_arr['priority'],
					'new_data'	=> $data['p'],
					'data_type'	=> 2,
					'admin_id'	=> $this->_adminInfo['id'],
					'is_admin'	=> 1,
			);
			$this->tb_admin_tickets_logs->add_log($status_arr);//log
			$res = $this->tb_admin_tickets->change_tickets_priority($id_arr,$data['p']);
			if($res){
				$lg =  lang(config_item('tickets_priority')[$data['p']]);
				die(json_encode(array('success'=>1,'msg'=>lang('change_tickets_priority_success'),'lg'=>$lg,'p'=>$data['p'])));
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('change_tickets_fail'))));
			}
		}else{
			die(json_encode(array('success'=>0,'msg'=>lang('change_tickets_fail'))));
		}
	}

	/**修改状态**/
	public function change_tickets_status(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
			if(is_numeric($data['id'])){
				$id_arr = array('id'=>$data['id'],'admin_id'=>$this->_adminInfo['id']);
				$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
				$status_arr = array(
						'tickets_id'=> $data['id'],
						'old_data'	=> $org_arr['status'],
						'new_data'	=> $data['s'],
						'data_type'	=> 1,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
				$res = $this->tb_admin_tickets->change_tickets_status($id_arr,$data['s']);
				if($res){
					die(json_encode(array('success'=>1,'msg'=>lang('change_status_success'))));
				}else{
					die(json_encode(array('success'=>0,'msg'=>lang('change_status_fail'))));
				}
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('change_status_fail'))));
			}
		}
	}

	//type
	public function change_tickets_type(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
			if(is_numeric($data['id'])){
				$id_arr 	= array('id'=>$data['id'],'admin_id'=>$this->_adminInfo['id']);
				$org_arr 	= $this->tb_admin_tickets->get_original_ticket($id_arr);
				$status_arr = array(
						'tickets_id'=> $data['id'],
						'old_data'	=> $org_arr['type'],
						'new_data'	=> $data['val'],
						'data_type'	=> 10,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
				$res = $this->tb_admin_tickets->change_tickets_type($id_arr,$data['val']);
				if($res){
					die(json_encode(array('success'=>1,'msg'=>lang('change_type_success'),'t_type'=>lang(config_item('tickets_problem_type')[$data['val']]))));
				}
			}
		}
		die(json_encode(array('success'=>0,'msg'=>lang('change_type_fail'))));
	}

	public function close_tickets(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			if($id){
				$id_arr = array('id'=>$id,'admin_id'=>$this->_adminInfo['id']);
				$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
				$status_arr = array(
						'tickets_id'=> $id,
						'old_data'	=> $org_arr['status'],
						'new_data'	=> 6,
						'data_type'	=> 4,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
				$res = $this->tb_admin_tickets->change_tickets_status($id_arr,6);
				if($res){
					die(json_encode(array('success'=>1,'msg'=>lang('close_tickets_success'))));
				}else{
					die(json_encode(array('success'=>0,'msg'=>lang('close_tickets_fail'))));
				}
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('close_tickets_fail'))));
			}
		}else{
			die(json_encode(array('success'=>0,'msg'=>lang('close_tickets_fail'))));
		}
	}

	/** 查看日志，生命周期
	 * @param null $id
	 */
	public function view_tickets_log($id=NULL){
		if(is_numeric($id)){
			$tickets_log = $this->tb_admin_tickets_logs->get_logs_by_tickets_id($id);
			if($tickets_log){
				$this->_viewData['log'] = $tickets_log;
				$this->load->view('admin/view_tickets_log',$this->_viewData);
			}else{
				echo '<p style="color: red;">'.lang("log_no_exist").'</p>';exit;
			}
		}else{
			echo '<p style="color: red;">'.lang("log_no_exist").'</p>';exit;
		}
	}

	public function do_transfer(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();//$data['c_id'] 为目标客服id
			$id_arr = array('id'=>$data['id'],'admin_id'=>$this->_adminInfo['id']);
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
			if($org_arr){
				$status_arr = array(
						'tickets_id'=> $id_arr['id'],
						'old_data'	=> $org_arr['admin_id'],
						'new_data'	=> $data['c_id'],
						'data_type'	=> 3,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
				$record = array(
						'admin_id'  =>$org_arr['admin_id'],
						'type'      =>2,
						'count'     =>1,
						'assign_time'=>date('Y-m-d'),
				);
				$this->db->trans_start();
				$this->tb_admin_tickets_record->add_record($record);
				$record['admin_id'] = $data['c_id'];
				$record['type']		= 1;
				$this->tb_admin_tickets_record->add_record($record);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
				$this->tb_admin_tickets->transfer_tickets_to_other($id_arr,$data['c_id']);
				$this->db->trans_complete();
				if($this->db->trans_status()!=FALSE){
					die(json_encode(array('success'=>1,'msg'=>lang('transfer_tickets_success'))));
				}else{
					die(json_encode(array('success'=>0,'msg'=>lang('transfer_tickets_fail'))));
				}
			}
		}
		die(json_encode(array('success'=>0,'msg'=>lang('transfer_tickets_fail'))));

	}

	//批量转移
	public function batch_transfer(){
		$param = $this->input->post();
		if($param && isset($param['cus'])){
			$cus_id = $param['cus'];
			$this->db->trans_start();
			$this->tb_admin_tickets->batch_transfer_tickets_to_other($param['checkboxes'],$cus_id);
			$arr = array();
			foreach($param['checkboxes'] as $c){
				$a = array(
						'tickets_id'=> $c,
						'old_data'	=> $this->_adminInfo['id'],
						'new_data'	=> $cus_id,
						'data_type'	=> 3,
						'admin_id'	=> $this->_adminInfo['id'],
						'is_admin'	=> 1,
				);
				array_push($arr,$a);
			}
			$record = array(
					'admin_id'  =>$cus_id,
					'type'      =>1,
					'count'     =>count($param['checkboxes']),
					'assign_time'=>date('Y-m-d'),
			);
			$this->tb_admin_tickets_record->add_record($record);
			$record['admin_id'] = $this->_adminInfo['id'];
			$record['type']		= 2;
			$this->tb_admin_tickets_record->add_record($record);
			$this->tb_admin_tickets_logs->batch_add_logs($arr);//log
			$this->db->trans_complete();
		}
		header( "Location:".$_SERVER['HTTP_REFERER']);
	}

	public function get_tickets_status(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			if($id) {
				$status = $this->db->select('status,last_reply')->where('id',$id)->where('admin_id',$this->_adminInfo['id'])->from('admin_tickets')->get()->row_array();
				if($status){
					if(!in_array($status['status'],array(0,1,4,5,6)) && $status['last_reply']==0){
						$lg = lang('new_msg');
					}else{
						$lg = lang(config_item('tickets_status')[$status['status']]);
					}
					die(json_encode(array('success'=>1,'msg'=>$lg)));
				}else{
					die(json_encode(array('success'=>0,'msg'=>$status['status'])));
				}
			}
		}
	}

	public function get_count(){
		$id = $this->_adminInfo['id'];
		$un_assign = $this->tb_admin_tickets->get_unassigned_tickets_count();
		$un_handle = $this->tb_admin_tickets->get_my_unprocessed_tickets_count($id);
		die(json_encode(array('success'=>1,'a'=>(int)$un_assign,'h'=>(int)$un_handle)));
	}

	public function get_new_msg(){
		$data = $this->input->post();
		$this->load->driver('cache', array('adapter' => 'file'));
		//$this->cache->save('commText',$data,60);
		$id = $this->cache->get($data['id'].'mem');
		if($id){
			$this->cache->delete($data['id'].'mem');
			die(json_encode(array('success'=>1)));
		}
	}
	public function set_new_msg($id){
		$this->load->driver('cache', array('adapter' => 'file'));
		$this->cache->save($id.'cus',$id,120);
	}

	/**附件上传，上传到图片服务器*/
	public function my_upload_attach(){
		if(isset($_FILES['Filedata']['name'])){

			$a_name   = $_FILES['Filedata']['name'];
			$type     = strtolower(strstr($a_name, '.'));//限制上传格式
			$a_size   = round($_FILES['Filedata']['size'] / 1024, 2); //转换成kb
			$rand 	  = mt_rand(1000, 9999);
			$row_name = md5(date("dHis") . $rand);
			$a_path   = 'upload/temp/'.date('Ym').'/'.$row_name. $type;//待保存的图片路径

			if($a_size > 8192){

				die(json_encode(array('success'=>0,'msg'=>lang('exceeds_size_limit'))));

			}
			if (!in_array($type,array('.gif','.jpg','.png','.txt','.doc','.docx','.xls','.xlsx','.bmp','.pdf','.rar','.zip'))) {

				die(json_encode(array('success'=>0,'msg'=>lang('not_accepted_type'))));

			}

			$objName = $a_path;
			$filePath = $_FILES['Filedata']['tmp_name'];
			$this->load->model('m_oss_api');
			$res =  $this->m_oss_api->doUploadFile($objName,$filePath);

			if($res){

				$data = array('path_name'=>$a_path, 'raw_name'=>$row_name, 'file_ext'=>$type);
				die(json_encode(array('success'=>1,'data'=>$data)));

			}


//			$this->load->model('m_do_img');
//			if($this->m_do_img->upload($a_path,$_FILES['Filedata']['tmp_name'])){
//				$data = array('path_name'=>$a_path, 'raw_name'=>$row_name, 'file_ext'=>$type);
//				die(json_encode(array('success'=>1,'data'=>$data)));
//			}

		}

		die(json_encode(array('success'=>0,'msg'=>lang('result_false'))));

	}

	/**
	 * @param null $attach_id
	 * @author andy
	 */
    public function download_attach($attach_id=NULL){
        $this->load->helper('download_3.0');
		if(is_numeric($attach_id)){
			$row = $this->tb_admin_tickets_attach->get_attach_by_id($attach_id);
			if($row){
				$data = @file_get_contents(config_item('img_server_url').'/'.$row['path_name']);
				force_download($row['name'],$data);
			}else{
				echo '<p style="color: red;">'.lang("attach_no_exist").'</p>';exit;
			}
		}else{
			echo '<p style="color: red;">'.lang("attach_no_exist").'</p>';exit;
		}

    }

	//删除附件
	public function delete_attach(){
		//$this->load->model('m_do_img');
		$path_name = urldecode($this->input->post('path_name'));
		$file_name = end(explode('/', $path_name));

		$this->load->model('m_oss_api');
		$res =  $this->m_oss_api->doDeleteObject($path_name);

		//if($this->m_do_img->delete($path_name)){
		if($res){

				$success =101;//文件删除成功
				$msg	 =lang('attach_delete_success');

			}else{

				$success =102;//文件删除失败
				$msg	 =lang('attach_cannot_find');

		}

		$arr= array(
				'success'=>$success,
				'msg'	 =>$msg,
				'f_name' =>$file_name,
		);

		die(json_encode($arr));

	}

}