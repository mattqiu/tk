<?php
/**
 * @author john  用户的身份证审核信息
 *
 */
class tb_user_id_card_info extends MY_Model {
    protected $table_name = "user_id_card_info";
    function __construct() {
        parent::__construct();
    }

	/**
	 * 得到会员的身份证审核信息
	 */
	public function getUserIdCard($uid) {
		 //return $this->db->from('user_id_card_info')->where('uid',$uid)->get()->row_array();
         return $this->get_one("*",array('uid'=>$uid));  //配置redis 可取信息
	}

	/**
	 * 得到会员的身份证审核信息
	 */
	public function getUserIdCardField($uid) {
		//return $this->db->select('id_card_num,id_card_scan,id_card_scan_back,check_status,check_info')->from('user_id_card_info')->where('uid',$uid)->get()->row_array();
                return $this->get_one("id_card_num,id_card_scan,id_card_scan_back,check_status,check_info",array('uid'=>$uid));
	}

	/**
	 * 新增用户身份信息
	 * @param 用户id $uid
	 */
	public function addNewuserIdCardField($uid)
	{
	    $user_exists = $this->getUserIdCardField($uid);
	    if(!empty($user_exists))
	    {
	        //$sql = "INSERT INTO user_id_card_info SET uid=".$uid;
	       // $this->db->query($sql);
                $this->insert_one(array('uid'=>$uid));
	    }
	}
	
	/**
	 * id card num 唯一性 by john 2015-7-7
	 */
	public function uniqueIdCardNum($uid,$num){
		//return $this->db->from('user_id_card_info')->where('uid <>',$uid)->where('id_card_num',$num)->count_all_results();
                return $this->get_counts("*",array('uid !='=>$uid,"id_card_num"=>$num));
	}

	/**
	 * 身份证图片的命名唯一性
	 */
	public function uniqueIdIdCardName($pathImg,$type_name){
		//return $this->db->from('user_id_card_info')->where($type_name,$pathImg)->count_all_results();
                return $this->get_counts("*",array($type_name=>$pathImg));
	}

	/**
	 * 更新会员的身份证图片地址
	 */
	public function updateIdCardPath($uid,$pathImg,$type_name){
		//$this->db->where('uid',$uid)->update('user_id_card_info',array($type_name=>$pathImg));
                return $this->update_one(array("uid"=>$uid),array($type_name=>$pathImg));
	}

	/**
	 * 删除身份图片地址
	 */
	function del_id_card($uid,$type_name){
		$this->db->where('uid', $uid)->update('user_id_card_info',array($type_name=>''));
		return $this->db->affected_rows();
	}

	/**
	 * 更新身份证状态
	 */
	function updateIdCard($uid,$data){
		$this->db->where('uid', $uid)->update('user_id_card_info',$data);
		return $this->db->affected_rows();
	}
    
    /*
     * 更新身份证审核次数 每审核失败一次 次数增加一
     */
    
    function  updateCheckTimes($uid,$data) {
        $this->db->where('uid', $uid)->update('user_id_card_info',$data);
        return $this->db->affected_rows();
    }

	//批量驳回
	public function batch_refuse_id_card(){
		set_time_limit(0);
		$this->load->model('m_admin_helper');
		//$res = $this->db->from('user_id_card_info')->where('check_status',1)->get()->result_array();
                $res =  $this->get_list("*",array("check_status"=>1));
		$count = 0;
//		$opts = array(
//				'https'=>array(
//						'timeout'=>30,
//				)
//		);
		//$context = stream_context_create($opts);
		if($res){
			foreach($res as $v){
				$id_card_scan = config_item('img_server_url').'/'.$v['id_card_scan'];
				$id_card_back = config_item('img_server_url').'/'.$v['id_card_scan_back'];
				//$resource_scan = file_get_contents($id_card_scan, false, $context);
				//$resource_back = file_get_contents($id_card_back, false, $context);
				$resource_scan = @fopen( $id_card_scan, 'r' ) ? true : false;
				$resource_back = @fopen( $id_card_back, 'r' ) ? true : false;
				if(!$resource_scan || !$resource_back){
					$info = array(
							'check_status' => 0,
							'check_admin' => 'system',
							'check_admin_id' => 0,
							'check_time' => time(),
							'check_info' => 'Sorry, your ID card photo upload failed, please re upload and submit.',
					);
					$affected = $this->m_admin_helper->updateCheckCardStatus($v['uid'], $info);
					$count+=$affected;
				}
			}
		}
		var_dump($count);
	}

}
