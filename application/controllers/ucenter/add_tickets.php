<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Add_tickets extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
		$this->load->model('tb_admin_tickets');
		$this->load->model('tb_admin_tickets_attach');
		$this->load->model('tb_admin_tickets_logs');
		$this->load->model('tb_admin_tickets_reply');
    }

    public function index(){

        $this->_viewData['title'] = lang('add_tickets');
		$country_id = $this->_userInfo['country_id'];
		$pro_type 	= config_item('tickets_problem_type');

		if($country_id == 1 || $country_id==4 || $country_id ==8)
		{

			$this->_viewData['pro_type'] = $pro_type;

		}else{

			unset($pro_type[0]);
			//unset($pro_type[12]);
			unset($pro_type[13]);
			$this->_viewData['pro_type'] = $pro_type;

		}

        parent::index();
    }

	/**
	 * 申请工单
	 */
	public function do_add(){

		if($this->input->is_ajax_request()){

			$data = $this->input->post(NULL,TRUE);

			if(empty($data['type']))
			{
				die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_type') )));
			}

			$tickets_title = trim($data['title']);
			if(empty($tickets_title) || mb_strlen($data['title'],'utf8')>100)
			{
				if(mb_strlen($data['title'],'utf8')>100)
				{
					die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
				}

					die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_title') )));
			}


			if(empty($data['content']) || mb_strlen($data['content'],'utf8')>1000)
			{
				if(mb_strlen($data['content'],'utf8')>1000)
				{
					die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
				}

					die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_content') )));
			}


			//获取自动回复推荐产品工单数据
//			$admin_id_arr	 = array();
//			$assign_admin_id = 144;

//			if($data['type']==5 && $this->_viewData['curLan_id']==2){
//
//				/**获取中文客服的数组**/
//				$this->load->model('m_admin_user');
//				$customers = $this->m_admin_user->get_area_china_customers();
//				foreach($customers as $c){
//					array_push($admin_id_arr,$c['id']);
//				}
//
//				//获取指针
//				$index = $this->tb_admin_tickets->get_cus_array_index(count($admin_id_arr));
//
//				//获取分配的客服信息
//				$assign_admin_id = $admin_id_arr[$index];
//
//			}

//			$insert_arr = array(
//				'title'				=>	$data['title'],
//				'content'			=>	str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),$data['content']),
//				'type'				=>	$data['type'],
//				'uid'				=>	$this->_userInfo['id'],
//				'language_id' 		=>	$this->_viewData['curLan_id'],
//				'admin_id' 			=>	($data['type']==5 && $this->_viewData['curLan_id']==2) ? $assign_admin_id:0,//0,
//				'status'			=>  $data['type']==5?6:0,
//				'sender '			=> 	0,
//				'last_assign_time'  => ($data['type']==5 && $this->_viewData['curLan_id']==2) ? date('Y-m-d'): 0,
//				'last_reply'		=>  $data['type']==5?1:0,
//				'is_attach'			=>  count($data)>3?1:0,
//			);

			$insert_arr = array(
					'title'				=>	htmlspecialchars($data['title']),
					'content'			=>	str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['content'])),
					'type'				=>	$data['type'],
					'uid'				=>	$this->_userInfo['id'],
					'language_id' 		=>	$this->_viewData['curLan_id'],
					'admin_id' 			=>	0,
					'status'			=>  0,
					'sender '			=> 	0,
					'last_assign_time'  =>  0,
					'last_reply'		=>  0,
					'is_attach'			=>  count($data)>3?1:0,
			);


			$this->db->trans_start();//事务
			$insert_id = $this->tb_admin_tickets->add_tickets($insert_arr);


			$status_arr = array(
				'tickets_id'	=>	$insert_id,
				'old_data'		=>	100,//100为不需要数据
				'new_data'		=>	100,
				'data_type'		=>	0,
				'admin_id'		=>	$this->_userInfo['id'],
				'is_admin'		=>	0,
			);
			$this->tb_admin_tickets_logs->add_log($status_arr);//log


//			if($insert_id && $data['type']==5){

//				$this->auto_reply_tickets($insert_id,$assign_admin_id);
//
//				//计数
//				$this->load->model('tb_admin_tickets_record');
//				$record = array(
//						'admin_id'  =>$assign_admin_id,
//						'type'      =>1,
//						'count'     =>1,
//						'assign_time'=>date('Y-m-d'),
//				);
//				$this->tb_admin_tickets_record->add_record($record);

//			}


			/**附件**/
			unset($data['content']);
			unset($data['title']);
			unset($data['type']);

			if(!empty($data)){//有附件

				$save_file_path = 'upload/tickets/'.date('Ym').'/';

				foreach($data as $val){

					$this->load->model('m_oss_api');
					$temp_arr 		= explode('|', urldecode($val));
					$new_save_path 	= $save_file_path.explode('/',$temp_arr[1])[3];
					$from_object 	= $temp_arr[1];
					$to_object   	= $new_save_path;
					$mov_res     	= $this->m_oss_api->copyObject(null, $from_object, null, $to_object);

					if($mov_res){

						$this->m_oss_api->doDeleteObject($from_object);

						$attach_arr =array(
								'tickets_id' => $insert_id,
								'name'	  	 => $temp_arr[0],
								'path_name'	 => $new_save_path,
								'extension'	 => $temp_arr[2],
								'is_reply'	 => 0,
						);
						$this->tb_admin_tickets_attach->add_attach($attach_arr);

					}
				}
			}

			$this->db->trans_complete();

			if($this->db->trans_status()===FALSE)
			{

				$msg 		= lang('tickets_save_fail');
				$success 	= 0;

			}else{

				$msg 		= lang('tickets_save_success');
				$success 	= 1;

			}

			die(json_encode(array('success' => $success, 'msg' =>$msg )));

		}
	}

	//自动回复工单
	private function auto_reply_tickets($insert_id,$assign_admin_id){
		$content_arr = array(
			1 =>"Hello,<br>Thank you for contacting TPS Support.<br>For vendor referral, please send information to suppliers%s@shoptps.com.
				 A departmental representative will contact you in a timely manner.<br>Thank you for your interest and support.<br>Sincerely<br>TPS support-",
			2 =>"尊敬的会员<br>您好<br>感谢您联系TPS客服服务<br>关于推荐产品，请您发送信息至邮箱：suppliers%s@shoptps.com 相关部门将尽快与您联系<br>
				 感谢您对TPS的支持与鼓励<br>诚挚<br>TPS客服",
			3 =>"尊敬的会员<br>您好<br>感谢您联系TPS客服服务<br>关于推荐产品，请您发送信息至邮箱：suppliers%s@shoptps.com 相关部门将尽快与您联系<br>
				 感谢您对TPS的支持与鼓励<br>诚挚<br>TPS客服",
			4 =>"회원님<br>안녕하십니까<br>TPS 고객센터에 연결해 주셔서 감사합니다.<br>제품 소싱에 관하여 상응한 자료를 ecosko@ktps138.com 메일로 전송해 주시길 바랍니다.<br>감사합니다.<br>
				 TPS상담원",
			//20170315 add by andy 客服要求回复
			5 =>"尊敬的会员<br>
					您好： <br>感谢您联系TPS客服服务！ <br><br>
					推荐产品需要您直接将相关资料发送至TPS招商部门的邮箱：<br>
					1.邮件主题：供货商公司全称；<br>
					2.邮件内容：企业资质，产品资质，产品报价表，产品图片（贵公司的每一分邮件我们会详细审 阅，并在5个工作日给予答复，）<br><br>
					请您将有关推荐供货商的信息发至推荐供货商的邮箱，您需要将您对应的产品发至相对应的邮箱 ，我们才能给您尽快审阅；<br><br>
					食品酒水：suppliers20@shoptps.com，suppliers28@shoptps.com，suppliers29@shoptps.com<br><br>
					美妆个体：suppliers6@shoptps.com，suppliers23@shoptps.com<br><br>
					母婴用品：suppliers5@shoptps.com，suppliers17@shoptps.com<br><br>
					营养保健：suppliers25@shoptps.com<br><br>
					数码电子：suppliers2@shoptps.com<br><br>
					家居日用：suppliers15@shoptps.com<br><br>
					服饰鞋帽：suppliers3@shoptps.com<br><br>
					钟表首饰：suppliers12@shoptps.com<br><br>
					礼品箱包：suppliers18@shoptps.com<br><br>
					汽车用品：suppliers14@shoptps.com<br><br>
					运动户外：suppliers14@shoptps.com<br><br>
					宠物生活：suppliers26@shoptps.com<br><br>
					生活服务：suppliers24@shoptps.com，suppliers22@shoptps.com<br><br>
					此工单不接受再次回复，发至以上邮箱后会有专人给您对接，请知悉。<br><br>
					感谢您的支持 <br>
					诚挚 <br>
					TPS客服部<br>",
		);
		$num_1 = rand(2,23);
		//$num_2 = rand(2,19);
		$auto_reply_arr  = array(
				'content'	=>'hello',
				'uid'		=>$this->_userInfo['id'],
				'admin_id'	=>0,//$this->_viewData['curLan_id']==2 ? $assign_admin_id :0,//中文自动回复例外，分配给某个客服
				'tickets_id'=>$insert_id,
				'sender' 	=>1,
				'is_attach'	=>0,
		);
		$this->load->model('tb_admin_tickets_reply');
		switch($this->_viewData['curLan_id']){
			case 1:{
				$auto_reply_arr['content'] = sprintf($content_arr[1],$num_1);
				$this->tb_admin_tickets_reply->add_reply($auto_reply_arr);
				$auto_reply_arr['content'] = sprintf($content_arr[2],$num_1);
				break;
			}
			case 2:{
				//$auto_reply_arr['content'] = sprintf($content_arr[2],$num_1);
				$auto_reply_arr['content'] = $content_arr[5];
				break;
			}
			case 3:{
				$auto_reply_arr['content'] = sprintf($content_arr[2],$num_1);
				break;
			}
			case 4:{
				$auto_reply_arr['content'] = $content_arr[4];
				break;
			}
			default :{
				$auto_reply_arr['content'] = sprintf($content_arr[1],$num_1);
				break;
			}
		}
		$this->tb_admin_tickets_reply->add_reply($auto_reply_arr);
		/**log**/
		$status_arr = array(
				'tickets_id'=>$insert_id,
				'old_data'  =>100,
				'new_data'  =>100,
				'data_type' =>11,
				'admin_id'  =>0,
				'is_admin'  =>1,
		);
		$this->tb_admin_tickets_logs->add_log($status_arr);//log
	}
	/**
	 *检测用户是否有正在跟进中的相同类型的工单
	 */
	function do_check_tickets(){

		$data = $this->input->post(NULL,TRUE);

		if(empty($data['type']))
		{
			die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_type') )));
		}


		if(empty($data['title']) || mb_strlen($data['title'],'utf8')>100)
		{
			if(mb_strlen($data['title'],'utf8')>100)
			{
				die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
			}
			die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_title') )));
		}


		if(empty($data['content']) || mb_strlen($data['content'],'utf8')>1000)
		{
			if(mb_strlen($data['content'],'utf8')>1000)
			{
				die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
			}

			die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_content') )));
		}

		$is_exist = $this->tb_admin_tickets->get_same_type_tickets($this->_userInfo['id'],$data['type']);

		if($is_exist)
		{
			die(json_encode(array('success' => 1, 'msg' =>sprintf(lang('tips_tickets_message'),$is_exist),'tickets_id'=>$is_exist )));

		}else{

			die(json_encode(array('success' => 2)));
		}
	}

	/**附件上传，上传到图片服务器*/
	public function my_upload_attach(){

		set_time_limit(0);
		$this->load->model('m_do_img');
		$this->load->model('m_oss_api');

		if(isset($_FILES['Filedata']['name'])){

			$a_name   = $_FILES['Filedata']['name'];
			$type     = strtolower(strstr($a_name, '.'));//限制上传格式
			$a_size   = round($_FILES['Filedata']['size'] / 1024, 2); //转换成kb
			$rand 	  = mt_rand(1000, 9999);
			$row_name = md5(date("dHis") . $rand);
			$a_path   = 'upload/temp/'.date('Ym').'/'.$row_name. $type;//待保存的图片路径

			if($a_size > 8192)
			{
				die(json_encode(array('success'=>0,'msg'=>lang('exceeds_size_limit'))));
			}

			if (!in_array($type,array('.gif','.jpg','.png','.txt','.doc','.docx','.xls','.xlsx','.bmp','.pdf','.rar','.zip')))
			{
				die(json_encode(array('success'=>0,'msg'=>lang('not_accepted_type'))));
			}

			$objName 	= $a_path;
			$filePath 	= $_FILES['Filedata']['tmp_name'];

			$res 		=  $this->m_oss_api->doUploadFile($objName,$filePath);

			//$this->load->model('m_do_img');
			//if($this->m_do_img->upload($a_path,$_FILES['Filedata']['tmp_name'])){

			if($res){

				$data = array('path_name'=>$a_path, 'raw_name'=>$row_name, 'file_ext'=>$type);
				die(json_encode(array('success'=>1,'data'=>$data)));

			}
		}

		die(json_encode(array('success'=>0,'msg'=>lang('save_false'))));

	}
	
	//删除附件
	public function delete_attach(){

		$this->load->model('m_do_img');

		$path_name = urldecode($this->input->post('path_name'));
		$file_name = end(explode('/', $path_name));


		$this->load->model('m_oss_api');
		$res =  $this->m_oss_api->doDeleteObject($path_name);


		//if($this->m_do_img->delete($path_name)){

		if($res){

			$success =101;//文件删除成功
			$msg	 =lang('attach_delete_success');

		}else{

			$success = 102;//文件删除失败
			$msg	 = lang('attach_cannot_find');

		}

		$arr= array(
				'success'=>$success,
				'msg'	 =>$msg,
				'f_name' =>$file_name,
		);

		die(json_encode($arr));

	}

	/**下载附件**/
	public function download_attach($attach_id=NULL){

		$this->load->helper('download_3.0');

		if(is_numeric($attach_id)){

			$row = $this->tb_admin_tickets_attach->get_attach_by_id($attach_id);

			if($row)
			{

				if($row['is_reply'] ==0)
				{

					$id_arr = array('id'=>$row['tickets_id'],'uid'=>$this->_userInfo['id']);
					$result = $this->tb_admin_tickets->get_original_ticket($id_arr);
					$uid   = !empty($result) ? $result['uid'] : '';

				}else{

					$result = $this->tb_admin_tickets_reply->get_reply_tickets_by_id($row['tickets_id']);
					$uid   = !empty($result) ? $result['uid'] : '';
				}

				if($uid == $this->_userInfo['id'])
				{

					$data = @file_get_contents(config_item('img_server_url').'/'.$row['path_name']);
					force_download($row['name'],$data);

				}else{

					echo '<p style="color: red;">'.lang("attach_no_exist").'</p>';exit;

				}

			}else{

				echo '<p style="color: red;">'.lang("attach_no_exist").'</p>';exit;

			}
		}else{

			echo '<p style="color: red;">'.lang("attach_no_exist").'</p>';exit;

		}
	}
}

