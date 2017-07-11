<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class all_tickets extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_tickets');
		$this->load->model('m_admin_user');
		$this->load->model('tb_admin_tickets_reply');
		$this->load->model('tb_admin_tickets_attach');
		$this->load->model('tb_admin_tickets_logs');
		$this->load->model('tb_admin_tickets_template');
		$this->load->model('tb_admin_tickets_record');
    }

    public function index() {
        $this->_viewData['title'] = lang('all_tickets');
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
		$searchData['status'] =isset($searchData['status'])?$searchData['status']:'';
		//例外，默认排除已评分/已解决的工单
//		if($searchData['status']==''){
//			$searchData['status'] ='default';
//		}
		$searchData['priority'] = isset($searchData['priority'])?$searchData['priority']:'';
		$searchData['key_tickets_id'] = isset($searchData['key_tickets_id'])?$searchData['key_tickets_id']:'';
		$searchData['key_uid_aid'] = isset($searchData['key_uid_aid'])?$searchData['key_uid_aid']:'';
		$searchData['language'] = isset($searchData['language'])?$searchData['language']:'';
		$searchData['score'] = isset($searchData['score'])?$searchData['score']:'';

		if(substr($searchData['key_uid_aid'],0,3)!='138' && $searchData['key_uid_aid'] !=''){
			$searchData['is_job'] = $this->tb_admin_tickets->get_admin_id_by_job_number($searchData['key_uid_aid']);
		}else{
			$searchData['is_job'] = '';
		}

		/*添加时间优先级排序 2016-9-07**/
		$searchData['o_time'] = isset($searchData['o_time'])?$searchData['o_time']:'';
		$searchData['o_priority'] = isset($searchData['o_priority'])?$searchData['o_priority']:'';
		//$searchData['exception_id'] = 0;//已分配的工单
        $list = $this->tb_admin_tickets->get_tickets_list($searchData);
        $this->_viewData['list'] = $list;
		$url = 'admin/all_tickets';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_tickets->get_tickets_count($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
		$this->_viewData['searchData'] = $searchData;
		$this->load->model('tb_admin_tickets_customer_role');
		$role = $this->tb_admin_tickets_customer_role->get_customer_by_admin_id($this->_adminInfo['id']);//客服临时角色
		$this->_viewData['cus_role'] = !empty($role)?$role['role']:1;
		$this->_viewData['cus'] = $this->m_admin_user->get_customers();
		$this->_viewData['all_cus'] = $this->tb_admin_tickets->get_all_customers();
		$o_time = $searchData['o_time'];
		$o_priority = $searchData['o_priority'];
		unset($searchData['o_time']);
		unset($searchData['o_priority']);
		$o_url = 'admin/all_tickets';
		add_params_to_url($o_url, $searchData);
		$this->_viewData['time_order_url'] = $o_url.'&o_priority='.$o_priority;
		$this->_viewData['p_order_url'] = $o_url.'&o_time='.$o_time;
		parent::index('admin/');
    }

	/** 查看其中一个工单 */
	public function view_or_reply_tickets($id = NULL){
		$this->_viewData['title'] = lang('tickets_info');
		if(is_numeric($id)){
			$id_arr = array(
					'id'      =>$id,
					'all_id'=>'',
			);
			$log_arr = array(
					'tickets_id'=>$id_arr['id'],
					'old_data'=>100,
					'new_data'=>100,
					'data_type'=>5,
					'admin_id'=>$this->_adminInfo['id'],
					'is_admin'=>1,
			);
			$this->tb_admin_tickets_logs->add_log($log_arr);//log
			$rows = $this->tb_admin_tickets->get_tickets_by_id_arr($id_arr);
			$user = '';
			if($rows){
				$user =  $this->tb_admin_tickets->get_users_info_by($rows['org']['uid']);
			}
			if($rows){
				if(isset($rows['org']) && !empty($rows['org'])){
					if($rows['org']['is_attach']==1){//获取原始信息下的附件
						$is_reply = 0;
						$attach = $this->tb_admin_tickets_attach->get_attach_by_tickets_id($rows['org']['id'],$is_reply);
						$rows['org']['attach'] = $attach;
					}
				}
				if(isset($rows['reply']) && !empty($rows['reply'])){//获取回复信息下的附件
					foreach($rows['reply'] as &$row){
						if($row['is_attach']==1){
							$is_reply = 1;
							$attach = $this->tb_admin_tickets_attach->get_attach_by_tickets_id($row['id'],$is_reply);
							$row['attach'] = $attach;
						}
				}
			}
			$customers = $this->m_admin_user->get_customers();
			$this->_viewData['all_cus'] 	 = $this->tb_admin_tickets->get_all_customers();
			$this->_viewData['tickets_user'] = $user;
			$this->_viewData['cus'] = $customers;
			$this->_viewData['rows'] = $rows;
			$this->load->view('admin/view_or_reply_tickets_all',$this->_viewData);
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
			$data = $this->input->post();
			if(!isset($data['content']) && mb_strlen($data['content'],'utf8')>1500){
				die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit').'(600)')));
			}
			if(empty($data['content'])){
				if(!in_array($data['status'],array(3,6))){
					die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_content') )));
				}
			}
			//获取原始信息
			$id_arr = array('id'=>$data['id'],'all_id'=>'');
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
			//$this->set_new_msg($org_arr['id']);
			$insert_arr = array(
					'content'	=>str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['content'])),
					'uid'		=>$org_arr['uid'],
					'admin_id'	=>$this->_adminInfo['id'],
					'tickets_id'=>$org_arr['id'],
					'sender' 	=>1,
					'is_attach'	=>count($data)>3?1:0,
			);
			$this->db->trans_start();//事务

			if($data['content']!='' && $data['status']==6){//申请关闭需要提交内容
				$this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);
				$insert_id = $this->tb_admin_tickets_reply->add_reply($insert_arr);
				$log_arr = array(
						'tickets_id'=>$id_arr['id'],
						'old_data'	=>$org_arr['status'],
						'new_data'	=>$data['status'],
						'data_type'	=>4,
						'admin_id'	=>$this->_adminInfo['id'],
						'is_admin'	=>1,
				);
			}elseif($data['content']=='' && $data['status']==6){//申请关闭不提交内容
				$insert_id = $this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);
				$log_arr = array(
						'tickets_id'=>$id_arr['id'],
						'old_data'	=>$org_arr['status'],
						'new_data'	=>$data['status'],
						'data_type'	=>4,
						'admin_id'	=>$this->_adminInfo['id'],
						'is_admin'	=>1,
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
						'tickets_id'=>$id_arr['id'],
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
			//$insert_id = $this->tb_admin_tickets_reply->add_reply($insert_arr);
			/**改变状态**/
			$this->tb_admin_tickets_logs->add_log($log_arr);//log
			if($data['status']!=100){
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

	/**
	 * 修改工单的优先级
	 */
	public function change_tickets_priority(){
		$data = $this->input->post();
		if($this->input->is_ajax_request() && is_numeric($data['id'])){
			$id_arr = array('id'=>$data['id'],'all_id'=>'');
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
			$status_arr = array(
					'tickets_id'=>$data['id'],
					'old_data'=>$org_arr['priority'],
					'new_data'=>$data['p'],
					'data_type'=>2,
					'admin_id'=>$this->_adminInfo['id'],
					'is_admin'=>1,
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
				$id_arr = array('id'=>$data['id'],'all_id'=>'');
				$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
				$status_arr = array(
						'tickets_id'=>$data['id'],
						'old_data'=>$org_arr['status'],
						'new_data'=>$data['s'],
						'data_type'=>1,
						'admin_id'=>$this->_adminInfo['id'],
						'is_admin'=>1,
				);
				$this->tb_admin_tickets_status->add_logs($status_arr);//log
				$res = $this->tb_admin_tickets->change_tickets_status($id_arr,$data['s']);//''为全部
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
	/**申请关闭工单**/
	public function close_tickets(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			if($id){
				$id_arr = array('id'=>$id,'all_id'=>'');
				$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
				$status_arr = array(
						'tickets_id'=>$id,
						'old_data'=>$org_arr['status'],
						'new_data'=>6,
						'data_type'=>1,
						'admin_id'=>$this->_adminInfo['id'],
						'is_admin'=>1,
				);
				$this->tb_admin_tickets_status->add_logs($status_arr);//log
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
			$id_arr = array('id'=>$data['id'],'all_id'=>'');
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
			$record_arr = array();
			if($org_arr) {
				$status_arr = array(
						'tickets_id' => $id_arr['id'],
						'old_data' 	=> $org_arr['admin_id'],
						'new_data' 	=> $data['c_id'],
						'data_type' => 3,
						'admin_id' 	=> $this->_adminInfo['id'],
						'is_admin' 	=> 1,
				);
				if($org_arr['admin_id']){
					$record = array(
							'admin_id'   =>$org_arr['admin_id'],
							'type'       =>2,
							'count'      =>1,
							'assign_time'=>date('Y-m-d'),
					);
					array_push($record_arr,$record);
				}
				$record = array(
						'admin_id'   =>$data['c_id'],
						'type'       =>1,
						'count'      =>1,
						'assign_time'=>date('Y-m-d'),
				);
				array_push($record_arr,$record);
				$this->db->trans_start();
				$this->tb_admin_tickets_record->batch_add_record($record_arr);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
				$this->tb_admin_tickets->transfer_tickets_to_other($id_arr, $data['c_id']);
				$this->db->trans_complete();
				if ($this->db->trans_status() != FALSE) {
					die(json_encode(array('success' => 1, 'msg' => lang('transfer_tickets_success'))));
				} else {
					die(json_encode(array('success' => 0, 'msg' => lang('transfer_tickets_fail'))));
				}
			}
		}
		die(json_encode(array('success'=>0,'msg'=>lang('transfer_tickets_fail'))));
	}

	//批量转移
	public function batch_transfer(){
		$param = $this->input->post();
		if($param && isset($param['cus']) && in_array($this->_adminInfo['role'],array(0,2))){
			$cus_id = $param['cus'];
			$this->db->trans_start();
			$arr = array();
			$record_arr = array();
			foreach($param['checkboxes'] as $c){
				$row = $this->db->select('id,admin_id')->from('admin_tickets')->where('id',$c)->get()->row_array();
				if($row){
					$a = array(
							'tickets_id'=>$c,
							'old_data'	=>$row['admin_id']?$row['admin_id']:0,
							'new_data'	=>$cus_id,
							'data_type'	=>3,
							'admin_id'	=>$this->_adminInfo['id'],
							'is_admin'	=>1,
					);
					array_push($arr,$a);
					if($row['admin_id']){
						$record = array(
								'admin_id'  =>$row['admin_id'],
								'type'      =>2,
								'count'     =>1,
								'assign_time'=>date('Y-m-d'),
						);
						array_push($record_arr,$record);
					}
					$record = array(
							'admin_id'  =>$cus_id,
							'type'      =>1,
							'count'     =>1,
							'assign_time'=>date('Y-m-d'),
					);
					array_push($record_arr,$record);
				}
			}
			$this->tb_admin_tickets_record->batch_add_record($record_arr);
			$this->tb_admin_tickets->batch_transfer_tickets_to_other($param['checkboxes'],$cus_id);
			$this->tb_admin_tickets_logs->batch_add_logs($arr);//log
			$this->db->trans_complete();
		}
		header( "Location:".$_SERVER['HTTP_REFERER']);
	}

	public function get_tickets_status(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			if($id) {
				$status = $this->db->select('status,last_reply')->where('id',$id)->from('admin_tickets')->get()->row_array();
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
}