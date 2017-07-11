<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_tickets extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_tickets');
		$this->load->model('tb_admin_tickets_reply');
		$this->load->model('tb_admin_tickets_attach');
		$this->load->model('tb_admin_tickets_logs');
    }

    public function index() {
        $this->_viewData['title'] = lang('my_tickets');
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
		$searchData['uid'] = $this->_userInfo['id'];//该会员的id
		$searchData['title_or_tid'] = isset($searchData['title_or_tid'])?$searchData['title_or_tid']:'';
        $list = $this->tb_admin_tickets->get_tickets_list($searchData,true);//true为会员请求
        $this->_viewData['list'] = $list;
		$url = 'ucenter/my_tickets';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_tickets->get_tickets_count($searchData);
		$this->_viewData['all_cus'] = $this->tb_admin_tickets->get_all_customers();
        $config['cur_page'] = $searchData['page'];;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
		//区分国家显示问题列表
		$country_id = $this->_userInfo['country_id'];
		$pro_type =config_item('tickets_problem_type');
		if($country_id == 1 || $country_id==4 || $country_id ==8){
			$this->_viewData['pro_type'] = $pro_type;
		}else{
			unset($pro_type[0]);
			//unset($pro_type[12]);
			unset($pro_type[13]);
			$this->_viewData['pro_type'] = $pro_type;
		}		
		$this->_viewData['searchData'] = $searchData;
        parent::index();
    }

	/** 查看其中一个工单 */
	public function view_or_reply_tickets($id = NULL){
		if(is_numeric($id)){
			$uid = $this->_userInfo['id'];
			$id_arr = array(
				'id' =>$id,
				'uid'=>$uid,
			);
			$rows = $this->tb_admin_tickets->get_tickets_by_id_arr($id_arr);//该工单下的所有信息
			$user = '';
			if($rows){
				$user =  $this->tb_admin_tickets->get_users_info_by($uid);
			}
			//log
			$log_arr = array(
					'tickets_id'=>$id,
					'old_data'=>100,
					'new_data'=>100,
					'data_type'=>5,
					'admin_id'=>$this->_userInfo['id'],
					'is_admin'=>0,
			);
			$log_id = $this->tb_admin_tickets_logs->add_log($log_arr);//log
			if($rows && $log_id){
				$this->_viewData['title'] = lang('tickets_info');
				if(isset($rows['org']) && !empty($rows['org']) && $rows['org']['is_attach']==1){//获取原始信息下的附件
					$is_reply = 0;
					$attach = $this->tb_admin_tickets_attach->get_attach_by_tickets_id($rows['org']['id'],$is_reply);
					$rows['org']['attach'] = $attach;
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
				$this->_viewData['all_cus'] = $this->tb_admin_tickets->get_all_customers();
				$this->_viewData['rows'] = $rows;
				$this->_viewData['tickets_user'] = $user;
				$this->load->view('ucenter/view_or_reply_tickets',$this->_viewData);
			}else{
				echo '<p style="color: red;">'.lang("tickets_no_exist").'</p>';exit;
			}
		}else{
			echo '<p style="color: red;">'.lang("tickets_no_exist").'</p>';exit;
		}
	}

	/** 回复工单**/
	public function do_reply(){
		if(!$this->input->post() || count($this->input->post())<4 || !$this->input->is_ajax_request()){
			$msg = lang('tickets_reply_fail');
			die(json_encode(array('success' => 0, 'msg' =>$msg )));
		}
		if($this->input->is_ajax_request()){
			$data = $this->input->post(NULL,TRUE);
			if(!isset($data['content']) || $data['content'] == ''|| mb_strlen($data['content'],'utf8')>1000){
				if(mb_strlen($data['content'],'utf8')>1000){
					die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
				}
				if($data['content']=='' && $data['status']!=4){//提交为待解决时内容不能为空
					die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_content') )));
				}
			}
			//获取原始邮件信息
			$id_arr = array('id'=>$data['id'],'uid'=>$this->_userInfo['id']);
			$org_arr = $this->tb_admin_tickets->get_original_ticket($id_arr);
			//$this->set_new_msg($id_arr['id']);

			$insert_arr = array(
					'content'	 => str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['content'])),
					'tickets_id' => $org_arr['id'],
					'uid'		 => $this->_userInfo['id'],
					'admin_id'	 => $org_arr['admin_id'],
					'sender'	 => 0,
					'is_attach'	 => count($data)>4?1:0,
			);
			/**评论分数**/
			if($data['score_num']==0){//默认分数为5分，提交为已解决，没有评分
				$score_num = 0;
			}elseif($data['score_num']==101){//提交为待解决
				$score_num = 101;
			}else{//已评分
				$score_num = $data['score_num'];
			}
			$this->db->trans_start();//事务
			/**判断是否需要添加最后一条信息,如果只是提交解决状态，则不需要插入数据**/
			if($data['content']=='' && $data['status']==4){
				$insert_id = false;
			}else{
				$insert_id = $this->tb_admin_tickets_reply->add_reply($insert_arr);
			}
			/**改变工单状态,提交为已解决或者已评分状态,记录log**/
			if($data['status']==4){
				if($data['score_num']==0){
					$this->tb_admin_tickets->change_tickets_status($id_arr,$data['status']);//没有主动评价，已解决
					$new_data = 4;
				}else{
					$this->tb_admin_tickets->change_tickets_status($id_arr,5);//主动评价，已评分
					$new_data =5;
				}
				$status_arr = array(
						'tickets_id' => $org_arr['id'],
						'old_data'	 => $org_arr['status'],
						'new_data'	 => $new_data,
						'data_type'	 => 9,//回复为已解决
						'admin_id'	 => $this->_userInfo['id'],
						'is_admin'	 => 0,
				);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
			}else{
				if($org_arr['status']!=0){
					$this->tb_admin_tickets->change_tickets_status($id_arr,$org_arr['status']==3 ? 3:2);
				}
				$status_arr = array(
						'tickets_id' => $org_arr['id'],
						'old_data'	 => $org_arr['status'],
						'new_data'	 => $org_arr['status']==3 ? 3:2,
						'data_type'	 => 6,//回复为待回应
						'admin_id'	 => $this->_userInfo['id'],
						'is_admin'	 => 0,
				);
				$this->tb_admin_tickets_logs->add_log($status_arr);//log
			}

			/**附件**/
			$cont = $data['content'];
			$sta  = $data['status'];
			$s_num = $score_num;
			unset($data['content']);
			unset($data['id']);
			unset($data['status']);
			unset($data['score_num']);
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
								'is_reply'	 =>	1,
						);
						$this->tb_admin_tickets_attach->add_attach($attach_arr);
					}
				}
				$this->tb_admin_tickets->set_tickets_last_reply_and_score($id_arr,0,$s_num);/**标志最后一条信息为会员回复,记录评分数，$score_num为101则不评分**/
				$this->db->trans_complete();
				if($this->db->trans_status()===FALSE){
					$msg = lang('tickets_reply_fail');
				}else{
					$msg =lang('tickets_reply_success');
				}
			}elseif($insert_id && empty($data)){//发送成功没有附件
				$this->tb_admin_tickets->set_tickets_last_reply_and_score($id_arr,0,$s_num);/**标志最后一条信息为会员回复,记录评分数，$score_num为101则不评分**/
				$this->db->trans_complete();
				$msg = lang('tickets_reply_success');
			}elseif($cont=='' && $sta==4){//提交为已解决，没有提交内容，系统默认评分为5分，状态为已解决
				if($s_num!=0 && $s_num >=1 && $s_num <=5){//只有主动评价了才记录评分
					$this->tb_admin_tickets->set_tickets_score($id_arr,$s_num);
				}
				$this->db->trans_complete();
				$insert_id = true;
				$msg = lang('tickets_reply_success');
			}else{//没有发送成功
				$this->db->trans_complete();
				$msg = lang('tickets_reply_fail');
			}
			die(json_encode(array('success' => $insert_id, 'msg' =>$msg )));
		}
	}

	//设置新消息
	public function get_new_msg(){
		$data = $this->input->post();
		$this->load->driver('cache', array('adapter' => 'file'));
		$id = $this->cache->get($data['id'].'cus');
		if($id){
			$this->cache->delete($data['id'].'cus');
			die(json_encode(array('success'=>1)));
		}

	}
	public function set_new_msg($id){
		$this->load->driver('cache', array('adapter' => 'file'));
		$this->cache->save($id.'mem',$id,120);
	}
}