<?php
/**
 * @author Andy
 * 申请工单
 */
class tb_admin_tickets extends MY_Model
{
	protected $table_name = "admin_tickets";
    function __construct(){
        parent::__construct();
		$this->load->model('m_admin_user');
		$this->load->model('m_user');
    }

	/** 申请工单
	 * @param $insert_arr 申请工单的数组
	 * @return mixed
	 */
	public function add_tickets($insert_arr){
		$this->db->insert('admin_tickets',$insert_arr);
		return $this->db->insert_id();
	}

	/** 找到名下对应的原始订单号信息
	 * @param $id_arr
	 * @return mixed
	 */
	public function get_original_ticket($id_arr){
		//$this->db->from('admin_tickets as t');
		//$this->filterForTickets($id_arr);
		$this->db_slave->from('admin_tickets');
		$this->filterForTickets_for_slave($id_arr);
		$arr = $this->db_slave->get()->row_array();
		return $arr;
	}

	/**获取一个未分配工单的信息
	 * @param $id 工单id
	 * @return mixed
	 */
	public function get_unassigned_tickets_info_by_id($id){
//		$this->db->from('admin_tickets as t');
//		$this->db->select('t.*,u.name as user_name,u.email as user_email');
//		$this->db->join('users as u','u.id=t.uid','left');
//		$this->db->where('t.admin_id',0)->where('t.id',$id);
//		$org = $this->db->get()->row_array();
//		return $org;

		$this->db_slave->from('admin_tickets as t');
		$this->db_slave->select('t.*,u.name as user_name,u.email as user_email');
		$this->db_slave->join('users as u','u.id=t.uid','left');
		$this->db_slave->where('t.admin_id',0)->where('t.id',$id);
		$org = $this->db_slave->get()->row_array();
		return $org;
	}

	/**
	 * @param $id_arr tickets_id,uid or admin_id or all_id
	 * @priority 优先级
	 * @return number
	 */
	public function change_tickets_priority($id_arr,$priority){
		$this->db->set('priority',$priority);
		$this->filterForTickets($id_arr);
		$this->db->update('admin_tickets as t');
		return $this->db->affected_rows();
	}

	/** 设置最后一封信是谁回复的，and 当会员提交为解决的时候记录评分数 101 为提交为待解决的状态
	 * @param $id_arr
	 * @param $last_reply
	 * @param int $score_num
	 * @return mixed
	 */
	public function set_tickets_last_reply_and_score($id_arr,$last_reply,$score_num=101){
		$this->db->set('last_reply',$last_reply);
		if($score_num!=101 && $score_num != 0){//例外，101:提交待回应  0:提交解决没有评分，系统默认5分
			$this->db->set('score',$score_num);
		}
		$this->filterForTickets($id_arr);
		$this->db->update('admin_tickets as t');
		return $this->db->affected_rows();
	}

	/** 设置评价分数
	 * @param $id_arr
	 * @param $score
	 * @return mixed
	 */
	public function set_tickets_score($id_arr,$score){
		$this->db->set('score',$score);
		$this->filterForTickets($id_arr);
		$this->db->update('admin_tickets as t');
		return $this->db->affected_rows();
	}

	/** 工单号转移
	 * @param $id_arr tickets_id,uid or admin_id or all_id
	 * @param $customer_id 目标客服id
	 * @return bool
	 * */
	public function transfer_tickets_to_other($id_arr,$customer_id){
		$this->db->set('admin_id',$customer_id)->set('last_assign_time',date('Y-m-d',time()));
		$this->filterForTickets($id_arr);
		$this->db->update('admin_tickets as t');
		return $this->db->affected_rows();
	}
	/**批量转移到其他人名下**/
	public function batch_transfer_tickets_to_other($ids,$cus_id){
		$this->db->where_in('id', $ids)->update('admin_tickets',array('admin_id'=>$cus_id,'last_assign_time'=>date('Y-m-d',time())));
		return $this->db->affected_rows();
	}

	//批量分配,第一次分配
	public function batch_transfer($ids,$cus_id){
		//$this->db->where_in('id', $ids)->update('admin_tickets',array('admin_id'=>$cus_id,'status'=>1,'last_assign_time'=>date('Y-m-d',time())));
		$this->db->where_in('id', $ids)->update('admin_tickets',array('admin_id'=>$cus_id,'last_assign_time'=>date('Y-m-d',time())));//不改变状态
		return $this->db->affected_rows();
	}

	/**
	 * @param $id 原始工单id
	 * @param $admin_id 客服id
	 * @return mixed
	 */
	public function assign_tickets($id,$admin_id){
		$this->db->set('admin_id',$admin_id);
		//$this->db->set('status',1);//标记到我名下的时候，为1，已开启状态
		$this->db->set('last_assign_time',date('Y-m-d',time()));
		$this->db->where('id',$id);
		$this->db->update('admin_tickets');
		return $this->db->affected_rows();
	}


	/** 改变状态,申请关闭的时候需要设置时间
	 * @param $id_arr tickets_id,uid or admin_id or all_id
	 * @param $status
	 * @param $apply_close_time	//申请关闭时间
	 * @return bool
	 */
	public function change_tickets_status($id_arr,$status){
		$this->db->set('status',$status);
		if($status==6){
			$this->db->set('apply_close_time',date('Y-m-d H:i:s'),time());
		}
		$this->filterForTickets($id_arr);
		$this->db->update('admin_tickets as t');
		return $this->db->affected_rows();
	}

	public function change_tickets_type($id_arr,$type_val){
		$this->db->set('type',$type_val);
		$this->filterForTickets($id_arr);
		$this->db->update('admin_tickets as t');
		return $this->db->affected_rows();
	}


	/**该工单号下的所有信息,客服邮箱等信息
	 * @param $id_arr  //工单号 + uid or admin_id or all_id(可以查找任意名下的工单)
	 * @return array
	 * 从库读
	 */
	public function get_tickets_by_id_arr($id_arr){
		$select = 'id,uid,title,content,admin_id,is_attach,type,status,priority,sender,create_time';
		$where	= '';
		$org	= '';
		if($id_arr) {
			foreach ($id_arr as $k => $id) {

				if($k=='uid'){
					$where[$k] = $id;
				}else{
					if ($id != '') {
						$where[$k] = (int)$id;
					}
				}

			}

			if ($where) {
				$org = $this->get_one($select, $where);
			}

			if ($org) {
				$this->load->model('tb_admin_tickets_reply');
				$reply = $this->tb_admin_tickets_reply->get_ticket_reply($id_arr['id']);
				if ($reply) {
					$all_msg = array('org' => $org, 'reply' => $reply);
				} else {
					$all_msg = array('org' => $org);
				}
				return $all_msg;
			}
		}
		return false;


//		$this->db->from('admin_tickets as t');
//		$this->db->select('t.id,t.uid,t.title,t.content,t.admin_id,t.is_attach,t.type,t.status,t.priority,t.sender,t.create_time');
//		$this->filterForTickets($id_arr);
//		$row = $this->db->get()->row_array();
//		if($row) {
//			$this->load->model('tb_admin_tickets_reply');
//			$rows = $this->tb_admin_tickets_reply->get_ticket_reply($id_arr['id']);
//			if ($rows) {
//				$all_msg = array('org' => $row, 'reply' => $rows);
//			} else {
//				$all_msg = array('org' => $row);
//			}
//			return $all_msg;
//		}else{
//			return false;
//		}
	}

	//获取会员信息，查看某个工单时匹配会员信息，单表查询用,从库读
	public function get_users_info_by($uid){

			$res = $this->db_slave->select('id,name as user_name,email as user_email')->from('users')->where('id',(int)$uid)->get()->row_array();
			if($res){
				$row[$res['id']] = $res;
				return $row;
			}else{
				return false;
			}
	}

	/**
	 * 未分配工单列表
	 * 从库读
	**/
	public function get_unassigned_tickets_list($filter,$perPage = 10){
		$this->db_slave->from('admin_tickets');
		$this->db_slave->select('id,uid,title,content,admin_id,is_attach,type,status,create_time');
		if(!empty($filter['order_by'])){//uid
			if($filter['order_by']=='id-a'){
				$this->db_slave->order_by('uid','ASC');
			}elseif($filter['order_by']=='id-d'){
				$this->db_slave->order_by('uid','DESC');
			}
		}elseif(!empty($filter['order_by_time'])){//时间
			if($filter['order_by_time']=='time-a'){
				$this->db_slave->order_by('create_time','ASC');
			}elseif($filter['order_by_time']=='time-d'){
				$this->db_slave->order_by('create_time','DESC');
			}
		}else{
			$this->db_slave->order_by('uid', "ASC");
		}
		//$this->filterForTickets($filter);
		$this->filterForTickets_for_slave($filter);
		return $this->db_slave->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}

	/** 查询工单
	 * @param $filter
	 * @param int $perPage 每页显示几条记录
	 * @param bool $is_user 是否为会员页面的查询
	 * @return mixed
	 */
	public function get_tickets_list($filter,$is_user=false,$perPage = 10){
		$this->db->from('admin_tickets as t');
		$this->db->select('t.id,t.uid,t.title,t.content,t.admin_id,t.is_attach,t.language_id,t.type,t.status,t.priority,t.sender,t.last_reply,t.score,t.create_time');
		$this->filterForTickets($filter);
		if($is_user){
			return $this->db->order_by("t.create_time", "DESC")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();//会员
		}else{
			if(!empty($filter['key_uid_aid']) || !empty($filter['key_tickets_id']) || !empty($filter['keywords'])) {//关键字
				if(!empty($filter['o_priority'])){
					if($filter['o_priority']=='asc'){
						$this->db->order_by('t.priority','ASC');
					}else{
						$this->db->order_by('t.priority','DESC');
					}
				}
				if(!empty($filter['o_time'])){
					if($filter['o_time']=='asc'){
						$this->db->order_by('t.create_time','ASC');
					}else{
						$this->db->order_by('t.create_time','DESC');
					}
				}
				if(!empty($filter['o_id'])){
					if($filter['o_id']=='asc'){
						$this->db->order_by('t.uid','ASC');
					}else{
						$this->db->order_by('t.uid','DESC');
					}
				}
				return $this->db->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
			}elseif(!empty($filter['o_priority']) || !empty($filter['o_time'])){
				if(!empty($filter['o_priority'])){
					if($filter['o_priority']=='asc'){
						$this->db->order_by('t.priority','ASC');
					}else{
						$this->db->order_by('t.priority','DESC');
					}
				}
				if(!empty($filter['o_time'])){
					if($filter['o_time']=='asc'){
						$this->db->order_by('t.create_time','ASC');
					}else{
						$this->db->order_by('t.create_time','DESC');
					}
				}
				return $this->db->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
			}else{
				return $this->db->order_by('status','ASC')->order_by("t.create_time", "DESC")->order_by('t.priority', "DESC")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
			}
		}
	}

	/** 未处理完的工单总数 从库读
	 * @param $id
	 * @return mixed
	 */
	public function get_my_unprocessed_tickets_count($id){
		$this->db_slave->from('admin_tickets');
		$this->db_slave->where('admin_id',$id);
		$this->db_slave->where_in('status',array(1,2,3));
		return $this->db_slave->count_all_results();
	}

	/** 计算某个会员未读的消息 从库读
	 * @param $uid
	 * @return mixed
	 */
	public function mem_get_new_msg_count($uid){
		$this->db_slave->from('admin_tickets');
		$this->db_slave->where_in('status',array(0,1,2,3,6));
		$this->db_slave->where('admin_id !=',0);
		$this->db_slave->where('uid',$uid);
		$this->db_slave->where('last_reply',1);
		return $this->db_slave->count_all_results();
	}

	/**
	 * 未分配售后单总数量
	 */
	public function get_unassigned_tickets_count(){
		$this->db_slave->from('admin_tickets');
		$this->db_slave->where('admin_id',0);
		$this->db_slave->where('status',0);
		return $this->db_slave->count_all_results();
	}

	//统计
	private function get_all_statistics($admin_id='',$date=''){
		$a['all_unprocessed'] 	= $this->get_all_unprocessed_count($admin_id);
		$a['today_unprocessed'] = $this->get_today_unprocessed_count($admin_id);
		$a['today_assign']		= $this->get_today_assign_count($admin_id,$date);
		//$a['new_msg'] 			= $this->get_new_msg_tickets_count($admin_id);
		//$a['waiting_discuss'] 	= $this->get_waiting_discuss_count($admin_id);
		//$a['waiting_reply'] 		= $this->get_waiting_reply_count($admin_id);
		$a['all_tickets'] 		= $this->get_all_tickets_count($admin_id);
		return $a;
	}

	//每天
	private function get_daily_statistics($admin_id='',$date=''){
		$a['today_assign']		= $this->get_today_assign_count($admin_id,$date);
		return $a;
	}

	private function count_common_func($admin_id=''){
		if($admin_id!=''){
			$this->db->where('admin_id',$admin_id);
		}else{
			$this->db->where('admin_id !=',0);
		}
		$this->db->from('admin_tickets');
		$this->db->select('admin_id,count(*) as count');
		$this->db->group_by('admin_id');
		return $this->db->get()->result_array();
	}
	//全部未处理新工单
	public function get_all_unprocessed_count($admin_id=''){
		$this->db->where_in('status',array(0,1));
		return $this->count_common_func($admin_id);
	}
	//当天新工单未处理
	public function get_today_unprocessed_count($admin_id=''){
		$this->db->where_in('status',array(0,1));
		$this->db->where('last_assign_time',date('Y-m-d',time()));
		return $this->count_common_func($admin_id);
	}
	//当天工单总数
	public function get_today_assign_count($admin_id='',$date=''){
		$this->db->where('last_assign_time',$date);
		return $this->count_common_func($admin_id);
	}
	//新消息工单
	public function get_new_msg_tickets_count($admin_id=''){
		$this->db->where('last_reply',0);
		$this->db->where_in('status',array(2,3));
		return $this->count_common_func($admin_id);
	}
	//待商议工单
	public function get_waiting_discuss_count($admin_id=''){
		$this->db->where('status',3);
		return $this->count_common_func($admin_id);
	}
	//待回应工单
	public function get_waiting_reply_count($admin_id=''){
		$this->db->where('status',2);
		return $this->count_common_func($admin_id);
	}
	//工单总量
	public function get_all_tickets_count($admin_id=''){
		return $this->count_common_func($admin_id);
	}


	/** 处理统计数据
	 * @param $admin_id
	 * @return  array
	 * @author andy
	 */
	public function get_tickets_statistics($admin_id='',$sign='',$date=''){
		$this->load->model('tb_admin_tickets_record');
		$admin_id   = trim($admin_id);
		$statistics = array();
		$temp       = array();
		if($sign=='all'){
			$all         = $this->get_all_statistics($admin_id,$date);
		}else{
			$all         = $this->get_daily_statistics($admin_id,$date);//手上总数
		}
		$all['a_record'] = $this->tb_admin_tickets_record->get_record_count(1,$date,$admin_id);
		$all['s_record'] = $this->tb_admin_tickets_record->get_record_count(2,$date,$admin_id);
		if(!empty($all)){
			foreach($all as $k=>$v){
				$result = array();
				if($v){
					foreach($v as $item){
						$result[$item['admin_id']] = $item;
					}
				}
				$temp[$k] = $result;
			}
		}
		$cus = $this->get_cus($admin_id);
		if($cus && $temp){
			$temp_arr = array(
					'admin_id'			=>0,
					'job_number'		=>0,
					'email'				=>0,
					'today_in'			=>0,
					'today_out'			=>0,
					'today_unprocessed'	=>0,
					'today_assign'		=>0,
					'all_unprocessed'	=>0,
					'new_msg'			=>0,
					'waiting_discuss'	=>0,
					'waiting_reply'		=>0,
					'all_tickets'		=>0,
			);
			foreach($cus as $k=>$v){
				$temp_arr['admin_id'] = $v['id'];
				$temp_arr['job_number'] = $v['job_number'];
				$temp_arr['email'] = explode('@',$v['email'])[0];
				foreach($temp as $k2=>$v2){
					switch($k2){
						case 'all_unprocessed':{
							$temp_arr['all_unprocessed'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'today_unprocessed':{
							$temp_arr['today_unprocessed'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'today_assign':{
							$temp_arr['today_assign'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'new_msg':{
							$temp_arr['new_msg'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'waiting_discuss':{
							$temp_arr['waiting_discuss'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'waiting_reply':{
							$temp_arr['waiting_reply'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'all_tickets':{
							$temp_arr['all_tickets'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 'a_record':{
							$temp_arr['today_in'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						case 's_record':{
							$temp_arr['today_out'] = isset($v2[$k]['count'])?$v2[$k]['count']:0;
							break;
						}
						default:{
							break;
						}
					}
				}
				array_push($statistics,$temp_arr);
			}
		}
		return $statistics;
	}

	//获取客服账号信息
	private function get_cus($cus_id=''){
		$cus = array();
		if($cus_id){
			$this->db->select('id,email,status,job_number')->from('admin_users')->where('id',$cus_id);
			$c = $this->db->get()->row_array();
			if($c){
				$cus[$c['id']] = $c;
			}
		}else{
			$c =  $this->m_admin_user->get_customers();//获取在职客服
			if($c){
				foreach($c as $item){
					$cus[$item['id']] = $item;
				}
			}
		}
		return $cus;
	}

	/** 得到工单的记录数
	 * @param $filter
	 * @return mixed
	 */
	public function get_tickets_count($filter) {
		$this->db->from('admin_tickets as t');
		$this->filterForTickets($filter);
		return $this->db->count_all_results();
	}

	/** 拼接查询语句 */
	private function filterForTickets($filter){
		foreach ($filter as $k => $v) {
			if ($v === '' || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'id':{
					$this->db->where('t.id',(int)$v);
					break;
				}
				case 'uid':{
					$this->db->where('t.uid',$v);
					break;
				}
				case 'admin_id':{
					$this->db->where('t.admin_id',(int)$v);//0=>未分配,other=>为客服id
					break;
				}
				case 'all_id':{
					break;//查找所有客服名下的工单
				}

				case 'exception_id':{//已分配的工单,排除admin_id = 0
					$this->db->where('t.admin_id !=',(int)$v);
					break;
				}
				case 'language':{//语言
					$this->db->where('t.language_id',(int)$v);
					break;
				}
				case 'status':{
					if(is_array($v)){
						$this->db->where_in('t.status',(int)$v);
						break;
					}else{
						if($v==7){//新工单
							$this->db->where_in('t.status',array(0,1));
							//$this->db->where('t.last_reply',0);
							break;
						}elseif($v==8){//新消息
							$this->db->where_not_in('t.status',array(0,1,4,5,6));
							$this->db->where('t.last_reply',0);
							break;
						}elseif($v=='default'){//排除已解决，已评分
							$this->db->where_in('t.status',array(0,1,2,3,6));
							break;
						}elseif(in_array($v,array(2,3))){
							$this->db->where('t.status',(int)$v);
							$this->db->where('t.last_reply !=',0);
							break;
						}else{
							$this->db->where('t.status',(int)$v);
							break;
						}
					}
				}
				case 'type':{
					$this->db->where('t.type',(int)$v);
					break;
				}
				case 'priority':{
					$this->db->where('t.priority',(int)$v);
					break;
				}
				case 'start':{
					$this->db->where('t.create_time >=', $v);
					break;
				}
				case 'end':{
					$this->db->where('t.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
					break;
				}
				case 'score':{
					$this->db->where('t.score',(int)$v);
					$this->db->where_in('t.status',array(4,5));
					break;
				}
				case 'keywords':{
					if(substr($v,0,1)=='#'){
						$this->db->where('t.id',(int)substr($v,1));
					}elseif(substr($v,0,3)=='138'){
						$this->db->where('t.uid',$v);
					} else{
						$this->db->where('t.id',(int)$v);
					}
					break;
				}
				case 'key_uid_aid':{
					if(substr($v,0,3)=='138'){
						$this->db->where('t.uid',$v);
					}else{
						//$this->db->where('u.job_number',(int)$v);
						//$admin_id = $this->get_admin_id_by_job_number($v);
						//$this->db->where('t.admin_id',$admin_id);
					}
					break;
				}
				case 'is_job':{
					$this->db->where('t.admin_id',(int)$v);
					break;
				}

				case 'key_tickets_id':{
					if(substr($v,0,1)=='#'){
						$this->db->where('t.id',(int)substr($v,1));
					}else{
						$this->db->where('t.id',(int)$v);
					}
					break;
				}

				case 'title_or_tid':{
					if(is_numeric($v)){
						$this->db->where('t.id',(int)$v);
					}elseif(substr($v,0,1)=='#' && is_numeric(substr($v,1))){
						$this->db->where('t.id',(int)substr($v,1));
					}else{
						$this->db->like('t.title',$v);
					}
					break;
				}
				default:{
					break;
				}
			}
		}
	}

	/** 从库读的条件检索
	 * @param $filter
	 * @return array
	 */
	private function filterForTickets_for_slave($filter){
		foreach ($filter as $k => $v) {
			if ($v === '' || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'id':{
					$this->db_slave->where('id',(int)$v);
					break;
				}
				case 'uid':{
					$this->db_slave->where('uid',$v);
					break;
				}
				case 'admin_id':{
					$this->db_slave->where('admin_id',(int)$v);//0=>未分配,other=>为客服id
					break;
				}
				case 'all_id':{
					break;//查找所有客服名下的工单
				}

				case 'exception_id':{//已分配的工单,排除admin_id = 0
					$this->db_slave->where('admin_id !=',(int)$v);
					break;
				}
				case 'language':{//语言
					$this->db_slave->where('language_id',(int)$v);
					break;
				}
				case 'status':{
					if(is_array($v)){
						$this->db_slave->where_in('status',(int)$v);
						break;
					}else{
						if($v==7){//新工单
							$this->db_slave->where_in('status',array(0,1));
							//$this->db->where('t.last_reply',0);
							break;
						}elseif($v==8){//新消息
							$this->db_slave->where_not_in('status',array(0,1,4,5,6));
							$this->db_slave->where('last_reply',0);
							break;
						}elseif($v=='default'){//排除已解决，已评分
							$this->db_slave->where_in('status',array(0,1,2,3,6));
							break;
						}elseif(in_array($v,array(2,3))){
							$this->db_slave->where('status',(int)$v);
							$this->db_slave->where('last_reply !=',0);
							break;
						}else{
							$this->db_slave->where('status',(int)$v);
							break;
						}
					}
				}
				case 'type':{
					$this->db_slave->where('type',(int)$v);
					break;
				}
				case 'priority':{
					$this->db_slave->where('priority',(int)$v);
					break;
				}
				case 'start':{
					$this->db_slave->where('create_time >=', $v);
					break;
				}
				case 'end':{
					$this->db_slave->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
					break;
				}
				case 'score':{
					$this->db_slave->where('score',(int)$v);
					$this->db_slave->where_in('status',array(4,5));
					break;
				}
				case 'keywords':{
					if(substr($v,0,1)=='#'){
						$this->db_slave->where('id',(int)substr($v,1));
					}elseif(substr($v,0,3)=='138'){
						$this->db_slave->where('uid',$v);
					} else{
						$this->db_slave->where('id',(int)$v);
					}
					break;
				}
				case 'key_uid_aid':{
					if(substr($v,0,3)=='138'){
						$this->db_slave->where('uid',$v);
					}else{
						//$this->db->where('u.job_number',(int)$v);
						//$admin_id = $this->get_admin_id_by_job_number($v);
						//$this->db->where('t.admin_id',$admin_id);
					}
					break;
				}
				case 'is_job':{
					$this->db_slave->where('admin_id',(int)$v);
					break;
				}

				case 'key_tickets_id':{
					if(substr($v,0,1)=='#'){
						$this->db_slave->where('id',(int)substr($v,1));
					}else{
						$this->db_slave->where('id',(int)$v);
					}
					break;
				}

				case 'title_or_tid':{
					if(is_numeric($v)){
						$this->db_slave->where('id',(int)$v);
					}elseif(substr($v,0,1)=='#' && is_numeric(substr($v,1))){
						$this->db_slave->where('id',(int)substr($v,1));
					}else{
						$this->db_slave->like('title',$v);
					}
					break;
				}
				default:{
					break;
				}
			}
		}
	}


	/** 通过工号查找客服id
	 * @param $job_number
	 * @return mixed
	 */
	public function get_admin_id_by_job_number($job_number){

			$res =  $this->db_slave->from('admin_users')->select('id')->where('job_number',(int)$job_number)->get()->row_array();
			if($res){
				$admin_id = $res['id'];
			}else{
				$admin_id = $job_number;
			}

			return $admin_id;

	}

	/** 用户是否有正在跟进中的相同类型的工单
	 * @param $uid 用户ID
	 * @param $type 类型
	 * @return int 返回上个工单的id
	 */
	public function get_same_type_tickets($uid,$type){
		$row = $this->db_slave->select('id')->where('uid',$uid)->where('type',(int)$type)->where_in('status',array(1,2,3,6))->get('admin_tickets')->row_array();
		return $row ? $row['id'] : 0;
	}

	/**关闭工单 定时每天，已经发送过email，会员没有再回复 且 状态还是申请关闭 且 超过申请关闭时间12天**/
	public function closeTickets(){
		$this->load->model('tb_admin_tickets_logs');
		$timeStart  = date("Y-m-d H:i:s",strtotime("-20 day"));
		$timeEnd 	= date("Y-m-d H:i:s",strtotime("-12 day"));
		$logs_data  = array();
		$this->db->trans_start();
		$query_sql = "SELECT id FROM `admin_tickets` WHERE `apply_close_time`>'$timeStart' AND `apply_close_time`<'$timeEnd' AND `apply_close_time`!=0 AND `status`=6 AND `send_email_num`=2 FOR UPDATE ";
		$res = $this->db->query($query_sql)->result_array();
		//$this->db->select('id')->from('admin_tickets');
		//$this->db->where('apply_close_time >',$timeStart)->where('apply_close_time <',$timeEnd)->where('apply_close_time !=',0)->where('status',6)->where('send_email_num',2);
		//$res = $this->db->get()->result_array();
		if(!empty($res)){
			foreach($res as $t){
				$temp_log = array(
						'tickets_id' =>$t['id'],
						'old_data'   =>100,
						'new_data'   =>100,
						'data_type'  =>13,
						'admin_id'   =>0,
						'is_admin'   =>1,
				);
				array_push($logs_data,$temp_log);
			}
		$this->tb_admin_tickets_logs->batch_add_logs($logs_data);
		$this->db->set('status',4);
		$this->db->where('apply_close_time >',$timeStart)->where('apply_close_time <',$timeEnd)->where('apply_close_time !=',0)->where('status',6)->where('send_email_num',2);
		$this->db->update('admin_tickets');
		}
		$this->db->trans_complete();
	}

	/**5天後，用户没有关闭“申请关闭”状态的工单，发送提醒邮件**/
	public function  send_tickets_email_5(){
		$timeStart   = date("Y-m-d H:i:s",strtotime("-15 day"));
		$timeEnd = date("Y-m-d H:i:s",strtotime("-5 day"));
		$this->db->from('admin_tickets');
		$this->db->where('apply_close_time >=',$timeStart)->where('apply_close_time <=',$timeEnd);
		$this->db->where('status',6);
		$this->db->where('send_email_num',0);
		$res = $this->db->select('id,uid')->get()->result_array();
		if($res){
			$insert_data = array();
			$update_data = array();
			if(!empty($res)){
				foreach($res as $u){
					$arr = array(
							'uid'=>$u['uid'],
							'order_id'=>$u['id'],
							'type'=>6,//工单
					);
					array_push($insert_data,$arr);
					array_push($update_data,$u['id']);
				}
			}
			$this->db->trans_start();
			$this->db->where_in('id', $update_data)->update('admin_tickets',array('send_email_num'=>1));
			$this->db->insert_batch('sync_send_receipt_email', $insert_data);
			$this->db->trans_complete();
		}
	}

	/**10天後，用户没有关闭“申请关闭”状态的工单，发送提醒邮件**/
	public function  send_tickets_email_10(){
		$this->load->model('tb_admin_tickets_logs');
		$timeStart   = date("Y-m-d H:i:s",strtotime("-20 day"));
		$timeEnd = date("Y-m-d H:i:s",strtotime("-10 day"));
		$this->db->from('admin_tickets');
		$this->db->where('apply_close_time >=',$timeStart)->where('apply_close_time <=',$timeEnd);
		$this->db->where('status',6);
		$this->db->where('send_email_num',0);
		$res = $this->db->select('id,uid')->get()->result_array();
		if(!empty($res)){
			$insert_data = array();
			$update_data = array();
			$logs_data	 = array();
			foreach($res as $t){
					$user = $this->m_user->get_user_name_email_by_id($t['uid']);
					if(!empty($user) && !empty($user['email'])){
						$arr = array(
								'uid'=>$t['uid'],
								'order_id'=>$t['id'],
								'type'=>7,//工单
						);
						array_push($insert_data,$arr);
					}
					$temp_log = array(
							'tickets_id' =>$t['id'],
							'old_data'   =>100,
							'new_data'   =>100,
							'data_type'  =>12,
							'admin_id'   =>0,
							'is_admin'   =>1,
					);
					array_push($update_data,$t['id']);
					array_push($logs_data,$temp_log);
			}
			$this->db->trans_start();
			$this->tb_admin_tickets_logs->batch_add_logs($logs_data);//log
			$this->db->where_in('id', $update_data)->update('admin_tickets',array('send_email_num'=>2));
			if($insert_data){
				$this->db->insert_batch('sync_send_receipt_email', $insert_data);
			}
			$this->db->trans_complete();
		}
	}

	/**检测分配类型**/
	private function check_is_auto(){
		$this->db->from('config_site');
		$this->db->select('value');
		$this->db->where('name','auto_assign');
		$val = $this->db->get()->row_array();
		if($val['value']=='yes'){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/** 自动分配**/
	public function auto_assign(){
		$is_auto = $this->check_is_auto();
		if(!$is_auto){return;}
		/**分拣区域工单**/
		$this->db->from('admin_tickets')->where('admin_id',0)->where('status',0);
		$t_result = $this->db->get()->result_array();
		$en_arr = array();
		$zh_arr = array();
		$kr_arr = array();
		if(!empty($t_result)){
			foreach($t_result as $res){
				switch($res['language_id']){
					case 1:{//en
						array_push($en_arr,$res);
						break;
					}
					case 2:{//zh
						array_push($zh_arr,$res);
						break;
					}
					case 3:{//hk
						array_push($zh_arr,$res);
						break;
					}
					case 4:{//kr
						array_push($kr_arr,$res);
						break;
					}
					default :{
						break;
					}
				}
			}
		}
		/**获取正常上班客服**/
		$this->db->from('admin_users')->where('status',1)->where('job_number >',0);
		$cus_result = $this->db->get()->result_array();
		$en_cus_arr = array();
		$zh_cus_arr = array();
		$kr_cus_arr = array();
		if(!empty($cus_result)){
			foreach($cus_result as $c){
				switch($c['area']){
					case 1:{
						array_push($en_cus_arr,$c);
						break;
					}
					case 2:{
						array_push($zh_cus_arr,$c);
						break;
					}
					case 3:{
						array_push($zh_cus_arr,$c);
						break;
					}
					case 4:{
						array_push($kr_cus_arr,$c);
						break;
					}
					default :{
						break;
					}
				}
			}
		}
		if(!empty($t_result) && !empty($cus_result)){
			$this->db->trans_start();
			$this->do_assign($en_arr,$en_cus_arr);
			$this->do_assign($zh_arr,$zh_cus_arr);
			$this->do_assign($kr_arr,$kr_cus_arr);
			$this->db->trans_complete();
		}
		if($this->db->trans_status()!=FALSE && !empty($t_result) && !empty($cus_result)){
			return true;
		}else{
			return false;
		}
	}

	/**按照语言区域进行分配，整数的按照轮循，余数随机分配**/
	private function do_assign($t_arr,$c_arr){
		$this->load->model('tb_admin_tickets_logs');
		$this->load->model('tb_admin_tickets_record');
		if(!empty($t_arr) && !empty($c_arr)){
			$t_count = count($t_arr);
			$c_count = count($c_arr);
			$count_m = floor($t_count/$c_count);
			$count_r = $t_count%$c_count;
			if($count_m==0 && $count_r !=0){//只有余数
				$c_ = array_rand($c_arr,$count_r);
				$c_random = array();
				if($count_r==1){
					array_push($c_random,$c_arr[$c_]);
				}else{
					foreach($c_ as $item){
					array_push($c_random,$c_arr[$item]);
					}
				}
				$num = $count_r;
				if(!empty($c_random)){
					$update_arr   = array();
					$update_count = array();
					$insert_logs  = array();
					foreach($c_random as $c){
						array_push($update_arr,array('id'=>$t_arr[$num-1]['id'],'admin_id'=>$c['id'],'status'=>1,'last_assign_time'=>date('Y-m-d',time())));
						$update_count[$c['id']] = isset($update_count[$c['id']]) ? ++$update_count[$c['id']] : 1;
						array_push($insert_logs,array('tickets_id'=>$t_arr[$num-1]['id'],'old_data'=>0,'new_data'=>$c['id'],'data_type'=>3,'admin_id'=>0,'is_admin'=>1));
						$num--;
					}
					$this->tb_admin_tickets_logs->batch_add_logs($insert_logs);
					$this->tb_admin_tickets_record->batch_add_record_2($update_count);
					$this->db->update_batch('admin_tickets', $update_arr, 'id');
				}
			}else{
				if($count_r==0){//没有余数
					$c_num = count($c_arr);
					$num = 0;
					$update_arr   = array();
					$update_count = array();
					$insert_logs  = array();
					foreach($t_arr as $t){
						array_push($update_arr,array('id'=>$t['id'],'admin_id'=>$c_arr[$num]['id'],'status'=>1,'last_assign_time'=>date('Y-m-d',time())));
						$update_count[$c_arr[$num]['id']] = isset($update_count[$c_arr[$num]['id']]) ? ++$update_count[$c_arr[$num]['id']] : 1;
						array_push($insert_logs,array('tickets_id'=>$t['id'],'old_data'=>0,'new_data'=>$c_arr[$num]['id'],'data_type'=>3,'admin_id'=>0,'is_admin'=>1));
						$num++;
						if($num==$c_num){
							$num=0;
						}
					}
					$this->tb_admin_tickets_logs->batch_add_logs($insert_logs);
					$this->tb_admin_tickets_record->batch_add_record_2($update_count);
					$this->db->update_batch('admin_tickets', $update_arr, 'id');
				}else{
					/**余数**/
					$end_arr  = array_splice($t_arr,$t_count-$count_r);
					$num = count($end_arr);
					$c_ = array_rand($c_arr,$num);
					$c_random = array();
					if($num==1){
						array_push($c_random,$c_arr[$c_]);
					}else{
						foreach($c_ as $item){
							array_push($c_random,$c_arr[$item]);
						}
					}
					if(!empty($c_random)){
						$update_arr   = array();
						$update_count = array();
						$insert_logs  = array();
						foreach($c_random as $c){
							array_push($update_arr,array('id'=>$end_arr[$num-1]['id'],'admin_id'=>$c['id'],'status'=>1,'last_assign_time'=>date('Y-m-d',time())));
							$update_count[$c['id']] = isset($update_count[$c['id']]) ? ++$update_count[$c['id']] : 1;
							array_push($insert_logs,array('tickets_id'=>$end_arr[$num-1]['id'],'old_data'=>0,'new_data'=>$c['id'],'data_type'=>3,'admin_id'=>0,'is_admin'=>1));
							$num--;
						}
						$this->tb_admin_tickets_logs->batch_add_logs($insert_logs);
						$this->tb_admin_tickets_record->batch_add_record_2($update_count);
						$this->db->update_batch('admin_tickets', $update_arr, 'id');
					}
					/**整数**/
					$c_num = count($c_arr);
					$num = 0;
					$update_arr   = array();
					$update_count = array();
					$insert_logs  = array();
					foreach($t_arr as $t){
						array_push($update_arr,array('id'=>$t['id'],'admin_id'=>$c_arr[$num]['id'],'status'=>1,'last_assign_time'=>date('Y-m-d',time())));
						$update_count[$c_arr[$num]['id']] = isset($update_count[$c_arr[$num]['id']]) ? ++$update_count[$c_arr[$num]['id']] : 1;
						array_push($insert_logs,array('tickets_id'=>$t['id'],'old_data'=>0,'new_data'=>$c_arr[$num]['id'],'data_type'=>3,'admin_id'=>0,'is_admin'=>1));
						$num++;
						if($num==$c_num){
							$num=0;
						}
					}
					$this->tb_admin_tickets_logs->batch_add_logs($insert_logs);
					$this->tb_admin_tickets_record->batch_add_record_2($update_count);
					//$this->tb_admin_tickets_count->batch_update_count($update_count);
					$this->db->update_batch('admin_tickets', $update_arr, 'id');
				}
			}
		}
	}

	/*
	 * 工单自动分配第二方案，系统按时自动分配，三天内的同一个会员工单分给同一个客服，接电话的客服只接受三天内的会员工单，其他工单不接受，其他工单分给其他人**/
	public function auto_assign_2(){

	}
	/**合并工单**/
	public function combine_tickets($main_id,$ids){

	}

	//统计工单总数
	public function calculate_tickets_count(){
		$this->db_slave->from('admin_tickets')->select('COUNT(*) as all_count,admin_id,last_assign_time')->where('admin_id !=',0)->where('last_assign_time !=',0);
		return $this->db_slave->group_by('admin_id')->get()->result_array();
	}

	public function calculate_tickets_count_by_today(){
		$this->db_slave->from('admin_tickets')->select('COUNT(*) as today_count,admin_id,last_assign_time')->where('last_assign_time',date('Y-m-d',time()));
		return $this->db_slave->group_by('admin_id')->get()->result_array();
	}

	/**
	 * 获取全部客服，包括冻结账号的客服
	 * 单表查询匹配
	 **/
	public function get_all_customers(){

		$all_data = $this->redis_get('tickets:all_customers');

		if(!$all_data)
		{
			$this->db_slave->select('id,email,job_number')->from('admin_users')->where('area !=',0)->where('job_number !=',0);
			$all =  $this->db_slave->get()->result_array();
			$all_cus = array();
			if($all){
				foreach($all as $v){
					$all_cus[$v['id']] = $v;
				}
			}

			$this->redis_set('tickets:all_customers',serialize($all_cus),60*30);

			return $all_cus;
		}else{

			return unserialize($all_data);

		}
	}

	//获取不分配的客服工号 return array
	//andy
	private function getDenyAssignCus($key){
		$this->load->model('tb_admin_right');
		return $this->tb_admin_right->getOneRightByKeyForTickets($key);
	}


	//客服要求分配全部中文工单
	//$language_id 2中文 4韩文
	//$assign_cus  在职客服
	private function auto_assign_tickets_by_language($language_id,$assign_cus){

		set_time_limit(0);
		$this->load->model('tb_admin_tickets_logs');

		//获取全部未分配的工单
		$this->db->from('admin_tickets');
		$this->db->where('admin_id',0);
		$this->db->where('language_id',$language_id);
		$this->db->where_in('status',array(0,1,2,6));
		$tickets = $this->db->get()->result_array();

		$num = count($assign_cus);
		$n 	 = 0;
		$arr = array();

		$this->db->trans_start();

		foreach($tickets as $t){

			if($t['status']!=6){
				$this->db->set('status',0);
			}

			$this->db->set('admin_id',$assign_cus[$n]['id']);
			$this->db->set('last_assign_time',date('Y-m-d',time()));
			$this->db->where('id',$t['id']);
			$this->db->update('admin_tickets');
			$this->db->affected_rows();

			//计数
			$this->load->model('tb_admin_tickets_record');
			$record = array(
					'admin_id'  =>$assign_cus[$n]['id'],
					'type'      =>1,
					'count'     =>1,
					'assign_time'=>date('Y-m-d'),
			);
			$this->tb_admin_tickets_record->add_record($record);

			//记录
			$a = array(
					'tickets_id'=>$t['id'],
					'old_data'=>0,
					'new_data'=>$assign_cus[$n]['id'],
					'data_type'=>3,
					'admin_id'=>0,
					'is_admin'=>1,
			);
			array_push($arr,$a);

			//置0
			$n = $n+1;
			if($n==$num)
			{
				$n = 0;
			}
		}

		//log
		if($arr){
			$this->tb_admin_tickets_logs->batch_add_logs($arr);
		}

		$this->db->trans_complete();

		if($this->db->trans_status()!=FALSE){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 自动分配中文工单
	 * author:andy
	 */
	public function auto_assign_zh(){
		$this->load->model('m_admin_user');

		$cus = $this->m_admin_user->get_area_china_customers();
		$assign_cus  = array();
		$deny_cus	 = $this->getDenyAssignCus('deny_assign_tickets_for_zh_customers');
		foreach($cus as $v){
			if(!in_array($v['job_number'],$deny_cus)){
				array_push($assign_cus,$v);
			}
		}

		$language_id = 2;

		return $this->auto_assign_tickets_by_language($language_id,$assign_cus);
	}

	//自动分配韩文工单
	public function auto_assign_kr(){
		$this->load->model('m_admin_user');

		$kr_cus = $this->m_admin_user->get_area_kr_customers();

		$assign_cus  = array();
		$deny_cus	 = $this->getDenyAssignCus('deny_assign_tickets_for_kr_customers');
		foreach($kr_cus as $v){
			if(!in_array($v['job_number'],$deny_cus)){
				array_push($assign_cus,$v);
			}
		}

		$language_id = 4;

		return $this->auto_assign_tickets_by_language($language_id,$assign_cus);

	}

	//自动分配繁体工单
	public function auto_assign_hk(){
		$this->load->model('m_admin_user');

		//$kr_cus = $this->m_admin_user->get_area_kr_customers();

		$zh_cus = $this->m_admin_user->get_area_china_customers();

		$assign_cus  = array();
		$deny_cus	 = $this->getDenyAssignCus('deny_assign_tickets_for_hk_customers');
		foreach($zh_cus as $v){
			if(!in_array($v['job_number'],$deny_cus)){
				array_push($assign_cus,$v);
			}
		}

		$language_id = 3;

		return $this->auto_assign_tickets_by_language($language_id,$assign_cus);

	}

	//临时，分配产品推荐---弃用
	public function auto_assign_product(){

		set_time_limit(0);
		$this->load->model('tb_admin_tickets_logs');
		$this->load->model('m_admin_user');

		//获取全部未分配的中文工单
		$this->db->from('admin_tickets');
		$this->db->where('admin_id',0);
		$this->db->where('language_id',2);
		$this->db->where('type',5);
		$this->db->where_in('status',array(0,1,2,6));
		$tickets = $this->db->get()->result_array();


		$cus = $this->m_admin_user->get_area_china_customers();
		$assign_cus  = array();
		foreach($cus as $v){
			if(!in_array($v['job_number'],array(974,975,805,806,890,817,834,835,976))){
				array_push($assign_cus,$v);
			}
		}

		$num = count($assign_cus);
		$n 	 = 0;
		$str = '';
		$arr = array();

		$this->db->trans_start();

		foreach($tickets as $t){

			$str .= $t['id'].'&&';

			if($t['status']!=6){
				$this->db->set('status',0);
			}

			$this->db->set('admin_id',$assign_cus[$n]['id']);
			$this->db->set('last_assign_time',date('Y-m-d',time()));
			$this->db->where('id',$t['id']);
			$this->db->update('admin_tickets');
			$this->db->affected_rows();

			//计数
			$this->load->model('tb_admin_tickets_record');
			$record = array(
					'admin_id'  =>$assign_cus[$n]['id'],
					'type'      =>1,
					'count'     =>1,
					'assign_time'=>date('Y-m-d'),
			);
			$this->tb_admin_tickets_record->add_record($record);

			//记录
			$a = array(
					'tickets_id'=>$t['id'],
					'old_data'=>0,
					'new_data'=>$assign_cus[$n]['id'],
					'data_type'=>3,
					'admin_id'=>0,
					'is_admin'=>1,
			);
			array_push($arr,$a);

			//置0
			$n = $n+1;
			if($n==$num)
			{
				$n = 0;
			}
		}

		//log
		if($arr){
			$this->tb_admin_tickets_logs->batch_add_logs($arr);
		}

		//记录tickets id
		$log = array(
				'uid' 		=> 0,
				'order_id'	=> 'tickets_id',
				'type'		=>100,
				'content'	=>$str,
		);
		$this->db->insert('order_cancel_log',$log);


		$this->db->trans_complete();

		if($this->db->trans_status()!=FALSE){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @param $cus_count--客服数量
	 * 获取产品推荐客服的数组指针,依次分给下一个客服
	 */
	public function get_cus_array_index($cus_count){

		$index = $this->db_slave->select('value')->from('config_site')->where('name','cus_index')->get()->row_array();
		if(isset($index['value']) && (int)$index['value'] >=$cus_count){
			$index['value'] = 0;
		}
		$this->db->set('value',(int)$index['value']+1)->where('name','cus_index')->update('config_site');

		return isset($index['value']) ? (int)$index['value'] : 0;
	}

}