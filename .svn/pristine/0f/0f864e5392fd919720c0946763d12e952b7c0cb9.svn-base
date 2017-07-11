<?php
/**
 * @author Terry
 */
class tb_users extends MY_Model {

	protected $table_name = 'users';
	protected $table = "users";
    function __construct() {
        parent::__construct();
    }

	/**
	 * @param $idOrEmail ID or Email
	 * @return mixed
	 */
	public function getUserByIdOrEmail($idOrEmail) {
		$idOrEmail = trim($idOrEmail);
		if( is_email($idOrEmail) ){
			$res = $this->db_slave->from('users')->where("(email='$idOrEmail' and is_verified_email=1)")->get()->row_array();
		}elseif(is_phone($idOrEmail)){
			$res = $this->db_slave->from('users')->where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
		}elseif(preg_match('/^1\d{9}$/',$idOrEmail)) {   //检测10位ID
			$res = $this->db_slave->from('users')->where('id', $idOrEmail)->get()->row_array();
		} else {
            return false;
        }
		//$res = $this->db_slave->from('users')->where('id', $idOrEmail)->or_where("(email='$idOrEmail' and is_verified_email=1)")->or_where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
		return $res;
	}

	/**
	 * 获取店铺分布图第一层
	 * @param $uid
	 * @return array
	 */
    public function get_genealogy_tree($uid){
        $data = array('my_info' => array(), 'child_info'=>array());
        $res = $this->db->query("select id,name,user_rank,sale_rank,child_count from users where id = $uid")->row_array();

        $data['my_info'][] = $res;

        $child = $this->db->select('id,name,user_rank,sale_rank')->where('parent_id',$uid)->where('status !=',0)->from('users')->get()->result_array();
        foreach($child as $item){
            $data['child_info'][] = $item;
        }
        return $data;

    }

    /**
	 * 统计一批用户分红点总额（不含奖励分红点）
	 * @author Terry
	 * @param $uids var 1380100223,1380100456,1380100706
	 * @return string
	 */
    public function sumSharPointsOfUids($uids){

    	$res = $this->db->query("select sum(profit_sharing_point) total_point from users where id in (".$uids.")")->row_object()->total_point;
    	return tps_money_format($res);
    }

	/**
	 * 搜索店铺：ID，名称，域名前缀
	 * @param $keywords
	 * @return mixed
	 */
	public function search_store($keywords){

		$this->db->select('id,member_url_prefix,store_name')->from('users');
		$this->db->where('id',$keywords)->or_where('member_url_prefix',$keywords)->or_where('store_name',$keywords);

		return $this->db->get()->row_array();
	}

	/**
	 * 保存用戶提现密码
	 * @author Terry
	 */
	public function saveTakeCashPwd($uid,$token,$pwd){
		$this->db->where('id', $uid)->update('users', array('pwd_take_out_cash' => $this->encyTakeCashPwd($pwd, $token)));
		return $this->db->affected_rows();
	}

	/**
	 * 提现密码加密
	 */
	public function encyTakeCashPwd($pwd,$token){
		return sha1($pwd.$token);
	}

	/**
	 * 设置会员的姓名
	 * @param $uid
	 * @param $name
	 */
	public function saveUserName($uid,$name){
		$this->db->where('id', $uid)->update('users', array('name' => $name));
		$this->db->where('uid', $uid)->update('user_id_card_info', array('name' => $name));
	}
    
    
    /*
     * @desc  Redis保存注册过程验证码
     * @author JacksonZheng
     */
    public function saveRegisterCaptcha($sid,$code) {
         
        $redis_key = "register_captcha:".$sid;

        $res = $this->redis_set($redis_key,$code,1800); 
  
        return $res;     //保存成功为true  其他为非真
    }
    
    
     /*
     * @desc 获取注册过程验证码
     * 验证码过期返回-1
     * @author JacksonZheng
     */
    
    public function getRegisterCaptcha($sid) {
        
        $redis_key = "register_captcha:".$sid;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return $tmp;
        }
        return -1;
        
    }
    
    /*
     * @desc  Redis保存登陆验证码
     * @author JacksonZheng
     */
    public function saveImgCaptchaCode($sid,$code) {
         
        $redis_key = "login_img_captcha:".$sid;

        $res = $this->redis_set($redis_key,$code,600); 
  
        return $res;     //保存成功为true  其他为非真
    }
    
    /*
     * @desc 获取登陆验证码
     * 验证码过期返回-1
     * @author JacksonZheng
     */
    
    public function getImgCaptchaCode($sid) {
        
        $redis_key = "login_img_captcha:".$sid;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return $tmp;
        }
        return -1;
        
    }
    
   

	/**
	 * 设置会员的国家地区
	 * @param $uid
	 * @param $country_id
	 */
	public function set_user_country($uid,$country_id){
		$this->db->where('id', $uid)->update('users', array('country_id' => $country_id));
	}

	/**
	 * 批量获取用户分红点
	 * @param $search = array(
		'id>'=>XXX
	 )
	 */
	public function getUsersSharPoint($search=array(),$limit=10000){

		foreach($search as $k=>$v){

			$this->db->where($k,$v);
		}

		$userPointArr = $this->db->select('id,profit_sharing_point')->from('users')->where('id >',1380188951)->where('profit_sharing_point >',0)->order_by('id')->get()->result_array();
	}

	/**
	 * 设置or取消某用户账户的“现金池自动转月费池”
	 * @param $uid
	 * @param $is_auto 0:取消，1:设置
	 * @return boolean
	 */
	public function setCashPoolAutoSupplyMonthlyFeePool($uid,$is_auto){

		return $this->db->where('id', $uid)->update('users', array('is_auto' => $is_auto));
	}

	/**
	 * 获取某用户账户是否设置了“现金池自动转月费池”
	 * @param $uid
	 * @return int 0:取消，1:设置
	 */
	public function getCashPoolAutoSupplyMonthlyFeePool($uid){

		$res = $this->db->from('users')->select('is_auto')->where('id', $uid)->get()->row_array();
		return $res?$res['is_auto']:0;
	}

	/**
	 * 获取用户的普通分红点
	 * @param $uid
	 * @return 分红点
	 */
	public function getUserSharingPoint($uid){

		$res = $this->db->from('users')->select('profit_sharing_point')->where('id', $uid)->get()->row_array();
		return $res?$res['profit_sharing_point']:0;
	}

	/**
	 * 绑定会员的邮箱或手机号码
	 */
	public function binding_mobile_or_email($uid,$email_or_mobile){
		$fields = is_numeric($email_or_mobile) ? 'mobile' : 'email';
		$is_verified = is_numeric($email_or_mobile) ? 'is_verified_mobile' : 'is_verified_email';
		$this->db->where('id',$uid)->update('users',array($fields=>$email_or_mobile,$is_verified=>1,'update_time'=>time()));
		return $this->db->affected_rows();
	}

	public function unbind_mobile($uid,$mobile)
	{
		return $this->db->where(array('id !='=>$uid,'mobile'=>$mobile))->update('users',array('mobile'=>'','is_verified_mobile'=>0,'update_time'=>time()));
	}

	/**
	 * 检测会员的邮箱或手机号码是否已经绑定过
	 */
	public function is_check_binding($email_or_mobile){
		$fields = is_numeric($email_or_mobile) ? 'mobile' : 'email';
		$is_verified = is_numeric($email_or_mobile) ? 'is_verified_mobile' : 'is_verified_email';
		$count = $this->db->from('users')->where($fields,$email_or_mobile)->where($is_verified,1)->count_all_results();
		return $count;
	}

    /* 获取用户升级信息 (mobile)*/
    public function get_member_upgrade_info($uid){
        $data = array();
        $this->load->model('m_user');
        $user = $this->m_user->getUserByIdOrEmail($uid);
        $user_rank = $user['user_rank'];
        $join_fee = $this->m_user->getJoinFeeAndMonthFee();
        $already_pay = $join_fee[$user_rank]['join_fee'];

        foreach($join_fee as $k=>$item) {
            $join_fee[$k]['join_fee'] = $join_fee[$k]['join_fee'] - $already_pay;
        }

        foreach($join_fee as $k =>$item){
            if($join_fee[$k]['join_fee'] <= 0){
                unset($join_fee[$k]);
            }
        }

        //转换成移动端数组
        foreach($join_fee as $k=>$item){
            $arr = array();
            $arr['level'] = $k;
            $arr['join_fee'] = $item['join_fee'];
            $data[] = $arr;
        }

        return $data;
    }

    /**
     * @desc 会员移动位置 向上取10层，向下更改10层
     * 移动规则: 已经位于其父节点及以上节点的不能再移动位于其下  若违反移动规则将导致parent_ids字段存储的值层次错乱！
     * @param int  $moveUid    要移动位置的用户id
     * @param int  $parentUid  上级用户id
	 * @author terry  JacksonZheng
     * @date 20170307   20170411
     */
    public function movePosition($moveUid,$parentUid){
        
        //强制查询主库 避免主从可能不一致问题 
    	$res_parent_ids = $this->db->query("/*TDDL:MASTER*/select parent_ids from users where id=".$parentUid)->row_object()->parent_ids;  
        $ids_arr = explode(",",$res_parent_ids);
        
        if(in_array($moveUid,$ids_arr) || empty($ids_arr)) {
             return -1;      //违反移动规则 返回-1  若违反移动规则将导致parent_ids字段存储的值层次错乱！
        }
        
        $this->db->trans_begin();   //事务开始

        //向上只取9层加自己一层
        $parent_ids = array_slice($ids_arr,0,9);
        $res_parent_ids = implode(",",$parent_ids);
    	$newParentIds = $parentUid.','.$res_parent_ids;
         
    	$this->db->query("update users set parent_id=$parentUid,parent_ids='".$newParentIds."' where id=".$moveUid);
        $count = 1;  //控制递归次数标记变量
    	$this->upChildParentIds($moveUid,$newParentIds,$count);

    	$child_count = $this->db->query("select child_count from users where id=".$moveUid)->row_object()->child_count+1;
    	$this->db->query("update users set child_count=child_count+".$child_count." where id=".$parentUid);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        } 
        else
        {
            $this->db->trans_commit();
            return true;
        }
        
    }

    /*变更parent_ids by terry.*/
    public function upChildParentIds($uid,$uidParentIds,&$count){

    	$childParentIds = $uid.','.$uidParentIds;
    	$resList = $this->db->query("select id from users where parent_id=".$uid)->result_array();
    	if(!empty($resList)) {
            
            foreach($resList as $v){

                $v['id'] = (int)$v['id'];
                $child_arr = explode(",",$childParentIds);
                $child_sub = array_slice($child_arr,0,10);  //只保存10层
                $child_str = implode(",",$child_sub);
                //$child_str = rtrim($child_str,",");
                $this->db->query("update users set parent_ids='".$child_str."' where id=".$v['id']);
                if($count<10) {
                    $this->upChildParentIds($v['id'],$child_str,++$count);
                }
            }
            
        }
    }

    /**
	 * 验证用户的资金密码
	 * @author Terry
     */
    public function checkTakeOutPwd($uid,$pwd){
        $res = $this->db->select('id,num,time')->where('uid',(int)$uid)->get('user_pwd_error_num')->row_array();
        if(isset($res['id'])&&($res['num']>=5)){
            if(time()-(strtotime($res['time']))<=3600){
                return array('is'=>FALSE,'num'=>$res['num']);
            }  else {
                $this->db->delete('user_pwd_error_num', array('uid' => $uid));unset($res);
            }
        }
    	$userInfo = $this->get_user_info($uid,'token,pwd_take_out_cash');
        if(sha1($pwd.$userInfo['token']) !== $userInfo['pwd_take_out_cash']){
            /********************************************************************************/
            if(isset($res['id'])){
                $this->db->where(array('uid'=>$uid))->set('num','num + 1',FALSE)->update('user_pwd_error_num');
                $num=$res['num'];
            }  else {
                $this->db->insert('user_pwd_error_num',array('uid'=>$uid,'type'=>'2','num'=>1));
                $num= isset($res['num'])? $res['num']:0;
            }
            /********************************************************************************/
            return array('is'=>FALSE,'num'=>$num);
        }
        $this->db->delete('user_pwd_error_num', array('uid' => $uid));
        return array('is'=>TRUE,'num'=>'');
    }
    /**
	 * APP验证用户的资金密码
	 * @author Terry
     */
    public function checkTakeOutPwd2($uid,$pwd){
        $res = $this->db->select('id,num,time')->where('uid',(int)$uid)->get('user_pwd_error_num')->row_array();
        if(isset($res['id'])&&($res['num']>=5)){
            if(time()-(strtotime($res['time']))<=3600){
                return array('is'=>FALSE,'num'=>$res['num']);
            }  else {
                $this->db->delete('user_pwd_error_num', array('uid' => $uid));unset($res);
            }
        }
    	$userInfo = $this->get_user_info($uid,'token,pwd_take_out_cash');
        $pwd=  $this->AES_decryption($pwd);
        if(sha1($pwd.$userInfo['token']) !== $userInfo['pwd_take_out_cash']){
            /********************************************************************************/
            if(isset($res['id'])){
                $this->db->where(array('uid'=>$uid))->set('num','num + 1',FALSE)->update('user_pwd_error_num');
                $num=$res['num']+1;
            }  else {
                $this->db->insert('user_pwd_error_num',array('uid'=>$uid,'type'=>'2','num'=>1));
                $num=$res['num']+1;
            }
            /********************************************************************************/
            return array('is'=>FALSE,'num'=>$num);
        }
        $this->db->delete('user_pwd_error_num', array('uid' => $uid));
        return array('is'=>TRUE,'num'=>'');
    }
    /*
     * AES加密
     */
    public function AES_encryption($param) {
        include_once APPPATH . 'third_party/AES/AES.php';
        $aes = new aes;
        return $aes->aes128ecbEncrypt($param);//解密
    }
    /*
     * AES解密
     */
    public function AES_decryption($param) {
        include_once APPPATH . 'third_party/AES/AES.php';
        $aes = new aes;
        return $aes->aes128ecbHexDecrypt($param);//解密
    }

	/**
	 * 得到用户指定的数据
	 */
	public function get_user_info($uid,$fields=''){
		$fields = $fields == '' ? '*' : $fields;
		$res = $this->db->select($fields)->where('id',(int)$uid)->get('users');
		if ($res) {
			return $res->row_array();
		} else {
			return array();
		}
	}
	
	/**
	 * @author: derrick 批量获取指定用户记录数据
	 * @date: 2017年7月3日
	 * @param: @param array $uids
	 * @param: @param string $fields
	 * @param: @return multitype:
	 * @reurn: multitype:
	 */
	public function get_users_info($uids, $fields = '') {
		$fields = $fields == '' ? '*' : $fields;
		return $this->db->select($fields)->where_in('id', $uids)->get('users');
	}

    /**
     * 更新用户现金池
     * @param $uid 用户id
     * @param $updateAmount 更新的金额,可以为正数或者负数
     * @return boolean
     * @author Terry
     */
    public function updateUserAmount($uid,$updateAmount){
        
        if($updateAmount>=0){
            $updateAmount = '+'.$updateAmount;
        }
        $this->db->where('id', (int)$uid)->set('amount', 'amount' . $updateAmount, FALSE)->update('users');
    }

    public function getQualifiedUser($uid){

    	return $this->db->query('select profit_sharing_point from users where id='.(int)$uid.' and store_qualified=1')->row_array();
    }

	/**
	 * @author brady
	 * @desc 分页获取用户数据
	 * @param $page 当前页
	 * @param $page_size 分页大小
	 * @param $field 要查询的字段
	 * @return mixed array()
	 */
	public function get_users_by_page($page,$page_size,$field="*")
	{
		$res = $this->db->from($this->table_name)
			->select($field)
			->order_by('id')
			->limit($page_size,($page-1)*$page_size)
			->get()
			->result_array();
		return $res;
	}

	/**
	 * @author brady
	 * @return mixed int 总的条数
	 */
	public function get_total_rows()
	{
		$total = $this->db->from($this->table)
				->select('count(id) as total')
			->force_master()
				->get()
			->row_object()
			->total;
		return $total;
	}

	/**
	 * @author brady
	 * @desc 更改用户月费池
	 * @param $uid 用户id
	 * @param $amount 更改的金额
	 */
	public function update_user_month_pool($uid,$amount)
	{
		$this->db->query("update users set month_fee_pool = month_fee_pool - {$amount} where id = ".$uid);
	}

    /**
     * 获取用户信息
     */
    public function getUserInfo($uid,$fields=array()){

        $fieldsItem = $fields?implode(',', $fields):'*';
        return $this->db->from('users')->select($fieldsItem)->where('id',(int)$uid)->get()->row_array();
    }

	/**
	 * @author brady.wang
	 * @description 批量获取用户数据
	 */
	public function getUserInfoByIds($uids,$fields = array())
	{
		$fields = (empty($fields) || !is_array($fields)) ? "*" : implode(',',$fields);
		return $this->db->from('users')->select($fields)->where_in('id',$uids)->get()->result_array();
	}
	
	/**
	 * 修改用户状态
	 * @param 用户id $uid
	 * @param 用户状态 $status
	 */
	public function modify_user_status($uid,$status)
	{
	    $sql = "UPDATE users SET status = ".$status." WHERE id = " .$uid;
	    $this->db->query($sql);
	}

	

    /**
     * 更新用户级别
     * @param $where
     * @param $data
     */
	public function modify_user_rank($where,$data)
    {
        $uid = $where["id"];
        $data_user_rank = $this->get_one("user_rank",["id"=>$uid]);
        if (empty($data_user_rank)) {
        	return false;
        }
        $old_user_rank = $data_user_rank['user_rank'];
        $new_user_rank = $data['user_rank'];
        $this->update_one($where,$data);
//        $proc_sql = "call user_rank_change_week_comm('$uid','$old_user_rank','$new_user_rank',1);";
//        $this->db->query($proc_sql);

		//用户级别更改 需要变更相对应父等级的积分  brady
		$this->load->model("tb_users_credit_queue_user_rank");
		$queue_data = array(
				'uid'=>$uid,
				'before_user_rank'=>$old_user_rank,
				'after_user_rank'=>$new_user_rank,
				'created_time'=>date("Y-m-d H:i:s"),
				'type'=>1
		);
		$this->tb_users_credit_queue_user_rank->add_queue($queue_data); //添加进入队列

		$this->load->model("tb_users_store_sale_info_monthly");
		$this->tb_users_store_sale_info_monthly->user_rank_change_week_comm($uid, $old_user_rank, $new_user_rank, 1);
    }

    /**
     * 跟新用户分红点数据
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param int $uid 用户ID
     * @param: @param numeric $share_point 分红金额
     * @reurn: return_type
     */
    public function udpate_user_sharing_point($uid, $share_point) {
    	if (is_numeric($share_point)) {
    		$this->db->where('id', $uid)
    		->set('profit_sharing_point', 'profit_sharing_point+'.($share_point / 100), false)
    		->set('profit_sharing_point_from_sale', 'profit_sharing_point_from_sale+'.($share_point / 100), false)
    		->update('users');
    	}
    }
    
    /**
     * 将用户状态更新至正常
     * @author: derrick
     * @date: 2017年4月5日
     * @param: @param int $uid
     * @reurn: return_type
     */
    public function update_user_status_to_normal($uid) {
    	$this->db->where('id', $uid)
    	->set('status', 1, false)
    	->set('store_qualified', 1, false)
    	->update('users');
    }
    
	/**
	 * 用户登录账户密码检测 --- leon
	 * @param unknown $loginData   帐号信息（帐号 密码）
	 * @return multitype:boolean string |multitype:boolean unknown
	 */
	public function checkUserLogin($loginData) {
	
	    $loginName = trim($loginData['loginName']); //账号
	    $pwd = trim($loginData['pwdOriginal']);     //密码
        $captcha = trim($loginData['captcha']);     //用户输入的图片验证码
	
	    //输入的帐号为空
	    if (!$loginName) {
	        return array('success' => FALSE, 'msg' => '* ' . lang('login_need_login_name'));
	    }

	    //获取用户信息
	    $userInfo = $this->getUserByIdOrEmail($loginName);

	    if (!$userInfo) {
	        //return array('success' => FALSE, 'msg' => '* ' . lang('login_login_name_error'));//账户不存在
            return array('success' => FALSE, 'msg' => '* ' . lang('login_account_or_pwd_error'));
	    }
	
	    //输入的密码为空
	    if (!$pwd) {
	        //return array('success' => FALSE, 'msg' => '* ' . lang('regi_errormsg_repwd_2'));
            return array('success' => FALSE, 'msg' => '* ' . lang('login_account_or_pwd_error'));
	    }
        
        $conf = config_item("login_captcha");
        
        if($conf['switch'] ==1) {  //启用登陆验证码 
        
            //输入的图片验证码为空
            if (empty($captcha)) { 
                return array('success' => FALSE, 'code_msg' => '* ' . lang('email_code_not_nul'));
            }

            //比较判断用户输入的验证码和Redis/或Cookie保存的验证码
            if(isset($loginData['redis_code'])) {
                
                if($loginData['redis_code'] == -1 )  {  //Redis保存的验证码过期 则为-1
                    return array('success' => FALSE, 'code_msg' => '* ' . lang('captcha_code_expire'));
                }
                
                if(strtolower($captcha) !== $loginData['redis_code'] )  {
                    return array('success' => FALSE, 'code_msg' => '* ' . lang('mobile_code_error'));
                }
            } else {  //cookie保存加密验证码

                if(md5(strtolower($captcha)."yun#138") !== $loginData['cookie_code'])  {
                    return array('success' => FALSE, 'code_msg' => '* ' . lang('mobile_code_error'));
                }

            }
        }

	    //判断密码是不是相同
	    if ($this->pwdEncryption($pwd, $userInfo['token']) !== $userInfo['pwd']) {

			//以下注释的内容是 账户密码错误5次后冻结账户功能
			//去错误的数量 判断是不是大于五次了
			//redis开启才判断
			if(REDIS_STOP == 0) {
				$error_count = $this->record_user_login_error($userInfo['id']);
				if($error_count >= 5){
					//锁定账户  设置激活时间
					//$enable_time = date('Y-m-d H:i:s',time()+60*60*24);
					//$this->record_user_enable_time($userInfo['id'],3,$enable_time);//设置用户账户锁定 添加 激活时间
					$this->record_user_login_error($userInfo['id'],false);//记录帐号的错误次数
					return array('success' => FALSE, 'msg' => '* '.lang('account_disabled_hint'));//错误次数提示信息
				}else{
					$this->record_user_login_error($userInfo['id'],false);//记录帐号的错误次数
					return array('success' => FALSE, 'msg' => '* ' . lang('login_pwd_error'));
                    
				}
			}
			    return array('success' => FALSE, 'msg' => '* ' . lang('login_pwd_error'));
            
	    } else {
			if(REDIS_STOP == 0) {
				$error_count = $this->record_user_login_error($userInfo['id']);
				if ($error_count >= 5) {
					return array('success' => FALSE, 'msg' => '* '.lang('account_disabled_hint'));//错误次数提示信息
				}
			}
		}
 
	    //账户没有激活
	    if ($userInfo['status'] == '0') {
	        return array('success' => FALSE, 'msg' => '* ' . lang('login_status_error'));
	    }
	    
	    //账户冻结
	    if ($userInfo['status'] == '3') {

			//这里注释的内容是 账户密码错误被冻结，到解冻时间后解冻账户 --- leon
			//判断账户的冻结时间是不是已经失效，可以回复冻结状态
	        //$enable_time = strtotime($userInfo['enable_time']);
	        //if($enable_time > time()){
	        //    return array('success' => FALSE, 'msg' => '* ' . lang('account_disabled'));
	        //}else{
	            //解冻帐号    清空解冻时间
	        //    $this->record_user_enable_time($loginName,1,0);
	        //}

			return array('success' => FALSE, 'msg' => '* ' . lang('account_disabled'));
	    }
	
	    //公司预留账户，禁止登陆
	    if ($userInfo['status'] == '4') {
	        return array('success' => FALSE, 'msg' => '* ' . lang('company_account_cannot_login'));
	    }
	
	    //月费冻结，新增的冻结，leon
	    if ($userInfo['status'] == '5') {
	        return array('success' => FALSE, 'msg' => '* ' . lang('account_disabled'));
	    }
        
	    return array('success' => TRUE, 'userInfo' => $userInfo);
	}
	
	/**
	 * 检测登录的帐号 --- leon
	 * 取消is_verified_mobile字段验证  否则有些手机号登陆不了
	 * 
	 * @param unknown $idOrEmail   账户（ID，邮箱，手机号）
	 * @return unknown             账户信息
	 */
	public function getUserByIdOrEmail_v1($idOrEmail) {

		$idOrEmail = trim($idOrEmail);
		if( is_email($idOrEmail) ){
			$res = $this->db_slave->from('users')->where("(email='$idOrEmail' and is_verified_email=1)")->get()->row_array();
		}elseif( strlen($idOrEmail) > 10 ){
			$res = $this->db_slave->from('users')->where("(mobile='$idOrEmail')")->get()->row_array();
		}else{
			$res = $this->db_slave->from('users')->where('id', $idOrEmail)->get()->row_array();
		}
		
		if (empty($res)) {
			return array();
		}

		//$res = $this->db_slave->from('users')->where('id', $idOrEmail)->or_where("(email='$idOrEmail' and is_verified_email=1)")->or_where("(mobile='$idOrEmail')")->get()->result_array();
	    if (count($res) > 1) {
			if( is_email($idOrEmail) ){
				$res = $this->db_slave->from('users')->where("(email='$idOrEmail' and is_verified_email=1)")->get()->row_array();
			}elseif( strlen($idOrEmail) > 10 ){
				$res = $this->db_slave->from('users')->where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
			}else{
				$res = $this->db_slave->from('users')->where('id', $idOrEmail)->get()->row_array();
			}
			return $res;
			//return $res = $this->db_slave->from('users')->where('id', $idOrEmail)->or_where("(email='$idOrEmail' and is_verified_email=1)")->or_where("(mobile='$idOrEmail' and is_verified_mobile=1)")->get()->row_array();
	    } else {
	        return $res[0];
	    }
	}

	/**
	 * 验证用户密码 --- leon
	 * @param unknown $pwdOriginal  密码
	 * @param unknown $token        加密字段
	 * @return string               加密后的密码
	 */
	public function pwdEncryption($pwdOriginal, $token) {
		return sha1('!#*' . trim($pwdOriginal) . $token . 'tps');
	}

	/**
	 * 记录会员登录账户的 错误次数 --- leon
	 * @param unknown $loginName    账户（ID，邮箱，手机号）
	 * @param string  $session_bool  是否重置记录的次数（默认是 true 不重置）
	 * @param string  $ok            取当前数据还是新增数据（默认true 新增数据）
	 * @return number
	 */
	public function record_user_login_error($user_id,$session_bool = true,$ok=true){
		$key = "mall:login:submit:error_counts:".$user_id;
		$session_count = 0;//记录的次数
		$time_over = strtotime(date('Ymd')) + 86400;//今天夜里24点的时间戳
		$time_start = $time_over - time();//redis的缓存时间
		if($session_bool){
			//判断key是不是存在
			if( $this->redis_get($key) ){
				$session_count = $this->redis_get($key);//获取记录的值
			}else{
				$this->redis_set($key,$session_count);//初始化redis中的值

			}
		}else{
			//记录错误次数
			$record_count = $this->redis_get($key);//获取记录的值
			$session_count = $record_count + 1;//累计增加次数
			$this->redis_set($key,$session_count);
		}
		if ($this->redis_ttl($key) == -1) {
			$this->redis_settimeout($key,$time_start);//初始化redis中的值
		}
		return $session_count;
	}

	/**
	 * 账户的冻结和解冻状态的修改 --- leon
	 * @param unknown $loginName    账户（ID，邮箱，手机号）
	 * @param unknown $status       账户状态
	 * @param unknown $enable_time  激活时间
	 */
	public function record_user_enable_time($loginName,$status,$enable_time){
	    $user_info = $this->getUserByIdOrEmail($loginName);//获取用户信息
	    $update_data = array(
            'status' => $status,
	        'enable_time'=>$enable_time
        );
	    $this->update_batch(["id"=>$user_info['id']],$update_data);
	}

	/**
	 * 记录用户登录信息（ip地址，地理位置） By Terry.
	 * leon 修改
	 */
	public function recordMemberLoginInfo($uid){
	    $clientIp = get_real_ip();
	    $this->load->model('tb_ip_user_login_info');
	    $this->load->model('tb_sync_ip_to_address');
	    $this->tb_ip_user_login_info->insert_one(array('uid'=>$uid,'ip'=>$clientIp));
	    $this->tb_sync_ip_to_address->insert_one(array('ip'=>$clientIp));
	}

	/**
	 * 会员注册内容的检测 --- leon
	 * $type  默认空是注册,'ispwd'表示忘记密码
	 */
	public function checkRegisterItems_new($registerData, $type = '') {
        
		$return = array();
       
		foreach ($registerData as $itemName => $itemVal) {
			$itemVal = trim($itemVal);
			$isRight = TRUE;           //判断内容是不是有问题
			$error_code = 0;           //错误信息
			$foreachContinue = FALSE;
			switch ($itemName) {
				case 'email':
					//检测是不是邮箱
					if (empty($itemVal) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $itemVal)) {
						$isRight = FALSE;   //错误
						$error_code = 1052; //请输入正确的邮箱地址
						break;
					}

					//忘记密码中的判断
					if($type == 'ispwd'){
						//检测是不是已经存在的用户
						if (!$this->checkUserExist($itemVal)) { //检测账户
							$isRight = FALSE;
							$error_code = 1008; //用户不存在
						}
						break;
					}else{

						$user_store = $this->checkUserExist($itemVal);
						//检测账号和选择的类型是不是一致
						if( $user_store && isset($registerData['reg_type']) && $registerData['reg_type'] == 0 ){
							//选择的是没有TPS账户(检测账户是存在的)
							$isRight = FALSE;
							$error_code = 1002; //该邮箱地址已经注册
						}else if(isset($registerData['reg_type']) && $registerData['reg_type'] == 1){
							//选择的是有TPS账户
							if($user_store === FALSE){
								$isRight = FALSE;
								$error_code = 1008;//用户不存在
							}else if($user_store['parent_id'] > 0){
								$isRight = FALSE;
								$error_code = 1038;//用户已经是店主
							}

						}else if($user_store){ //普通会员注册检测是不是账户存在
							$isRight = FALSE;
							$error_code = 1053;//该邮箱地址已注册
						}

						break;
					}

					break;
				case 'mobile':
					if(empty($itemVal) || !preg_match('/^1[34578]{1}\d{9}$/',$itemVal)){
						$isRight = FALSE;
						$error_code = 1029;//请输入正确的手机号，提现将进行验证。
						break;
					}

					if($type == 'ispwd'){
						//检测是不是已经存在的用户
						if (!$this->checkUserExist($itemVal)) { //检测用户是不是存在
							$isRight = FALSE;
							$error_code = 1008;//用户不存在
						}
						break;
					}else{
						//检测是不是已经存在的用户
						//if ($this->checkUserExist($itemVal)) { //检测用户是不是存在
						//	$isRight = FALSE;
						//	$error_code = 1049;//手机号已存在
						//}

						$user_store = $this->checkUserExist($itemVal);
						//检测账号和选择的类型是不是一致
						if( $user_store && isset($registerData['reg_type']) && $registerData['reg_type'] == 0 ){
							//选择的是没有TPS账户
							$isRight = FALSE;
							$error_code = 1049; //手机号已存在
						}else if(isset($registerData['reg_type']) && $registerData['reg_type'] == 1){
							//选择的是有TPS账户
							if($user_store === FALSE){
								$isRight = FALSE;
								$error_code = 1008;//用户不存在
							}else if($user_store['parent_id'] > 0){
								$isRight = FALSE;
								$error_code = 1038;//用户已经是店主
							}
						}else if($user_store){//普通会员注册中的检测
							$isRight = FALSE;
							$error_code = 1049;//手机号已存在
						}
						break;
					}
					break;
				case 'pwd':
					//是不是为空
					if(empty($itemVal)){
						$isRight = FALSE;//错误
						$error_code = 1005;//请先填写密码
						break;
					}
					//判断格式是不是正确
					$pwdLen = strlen($itemVal);
					if ($pwdLen < 6 || $pwdLen > 18 || preg_match("/ /", $itemVal) || preg_match('/^\d+$/', $itemVal)) {
						$isRight = FALSE;
						$error_code = 1003;//密码格式不正确，密码为6-18位的字符，不能含空格,不能全为数字！
						break;
					}
					//判断账号密码是不是正确
					if( isset($registerData['reg_type']) && $registerData['reg_type'] == 1 ){

						if(empty($registerData['account'])){
							$isRight = FALSE;
							$error_code = 1010;//用户密码错误
						}else{
							$registerData['id'] = trim($registerData['account']);
							$registerData['pwdOld'] = trim($itemVal);
							if (!$this->checkUserPwd($registerData)) {
								$isRight = FALSE;
								$error_code = 1010;//用户密码错误
							}
						}
						break;
					}
					break;
				case 'pwd_again':
					if(empty($itemVal)){
						$isRight = FALSE;//错误
						$error_code = 1005;//请先填写密码
						break;
					}
					$pwdLen = strlen($itemVal);
					if ($pwdLen < 6 || $pwdLen > 18 || preg_match("/ /", $itemVal) || preg_match('/^\d+$/', $itemVal)) {
						$isRight = FALSE;
						$error_code = 1003;//密码格式不正确，密码为6-18位的字符，不能含空格,不能全为数字！
					}
					break;
				case 'captcha':
                    
                    $itemVal = trim($itemVal);
                    if(empty($itemVal)) {
                        $isRight = FALSE;
						$error_code = 1019;  //您的验证码有误！
                    }else if(isset($registerData['redis_code']) && $registerData['redis_code'] == -1) {
                        $isRight = FALSE;
						$error_code = 1043;//验证码过期失效
                    } else if(isset($registerData['redis_code']) && $registerData['redis_code'] == 0 ) {
                        $isRight = FALSE;
						$error_code = 1019; //您的验证码有误！
                    } else if (isset($registerData['cookie_code']) && $registerData['cookie_code'] === 0) {
                        $isRight = FALSE;
						$error_code = 1019;//您的验证码有误！
                    } else if (isset($registerData['redis_code']) && $registerData['redis_code'] !== $itemVal) {
                        $isRight = FALSE;
						$error_code = 1019;//您的验证码有误！
                    }else if(isset($registerData['cookie_code']) && md5(strtolower($itemVal)."yun#138") !== $registerData['cookie_code'])  {
                        $isRight = FALSE;
						$error_code = 1019;//您的验证码有误！
                    } else {

                    }

                
//					$account_captcha = trim($registerData['account']);
//					$session=$this->session->userdata($account_captcha);//通过账号 获取信息
//					if(isset($session['email_or_phone'])){
//						if (!empty($session) && strtolower($itemVal) == strtolower($session['code'])) {
//							if($session['expire_time'] < time()){
//								$isRight = FALSE;
//								$error_code = 1043;//验证码过期失效
//							}
//						}else{
//							$isRight = FALSE;
//							$error_code = 1019;//您的验证码有误！
//						}
//					}else{
//						$isRight = FALSE;
//						$error_code = 1019;//
//					}
                    
					break;
                
				case 'disclaimer':
					if(!$itemVal){
						$isRight = FALSE;
						$error_code = 1028;//没有勾选协议。
					}
					break;
				default:
					$foreachContinue = TRUE;
					break;
			}
			if ($foreachContinue) {
				continue;
			}

			/**
			 * is_numeric($error_code) 转化错误数字为正确的信息
			 */
			if ($type == 'api') {
				$return[$itemName] = array('isRight' => $isRight, 'error_code' => is_numeric($error_code) ? $error_code : 0);
			} else {
				$error = config_item('error_code');
				$return[$itemName] = array('isRight' => $isRight, 'msg' => is_numeric($error_code) ? lang($error[$error_code]) : $error_code);
			}

		}

		return $return;
	}

	/**
	 * 检查用户存在 --- leon
	 * @param $idOrEmail            账号（ID,邮箱，手机号）
	 * @param string $exceptionId
	 * @return bool
	 */
	public function checkUserExist($idOrEmail,$exceptionId='') {
		$select="*";
		$where=[];
		if(is_numeric($idOrEmail)){
			if(preg_match('/^1[34578]{1}\d{9}$/',$idOrEmail)){
				//$where = ['mobile'=>$idOrEmail,'is_verified_mobile'=>1];
				$where = ['mobile'=>$idOrEmail];
			}else{
				$where = ['id'=>$idOrEmail];
			}
		}else{
			//$where = ['email'=>$idOrEmail,'is_verified_email'=>1];
			$where = ['email'=>$idOrEmail];
		}
		if($exceptionId){
			$where = ['id !='=>$exceptionId];
		}
		$res = $this->get_one($select,$where);
		return $res ? $res : FALSE;
	}

	/**
	 * 注册账号 --- leon
	 */
	public function register($registerData) {

		//设置注册时间
		if (!isset($registerData['create_time'])) {
			$registerData['create_time'] = time();
		}
		//设置token值
		if(!isset($registerData['token'])){
			$registerData['token'] = $this->createToken($registerData['create_time']);
		}
		//设置密码值
		if (!isset($registerData['pwd'])) {
			$registerData['pwd'] = $this->pwdEncryption($registerData['pwdOriginal'], $registerData['token']);
			$registerData['pwd_ori_md5'] = md5($registerData['pwdOriginal']);
			unset($registerData['pwdOriginal']);
		}
		//设置国家
		if(isset($registerData['country_id']) && !in_array($registerData['country_id'],array(1,4))){
			$registerData['languageid'] = 2;
		}
		//手机注册 ？ 更新已验证手机标识
		if(isset($registerData['mobile']) && $registerData['mobile'] ){
			$registerData['is_verified_mobile'] = 1;
		}else{
			$registerData['is_verified_email'] = 1; //更新已验证邮箱标识
		}

		//$this->db->trans_start();  // 启动事物
		$this->db_slave->trans_begin();

		/** 店主註冊 */
		if(isset($registerData['parent_id']) && $registerData['parent_id']){

			/*构建parent_ids(所有父级id)*/
			$parent_ids_arr = array();
			$count = 1;
			$this->getTenParentIds($registerData['parent_id'],$parent_ids_arr,$count);
			$registerData['parent_ids'] = implode(',', $parent_ids_arr);


			//用户信息的更新和新增
			if($registerData['reg_type'] == 1){      //已有账户

				unset($registerData['reg_type']);
				unset($registerData['pwd']);
				unset($registerData['token']);

				$select_user_id = "id";
				if(isset($registerData['mobile']) && $registerData['mobile'] ){
					$where_user_mobile=array('mobile'=>$registerData['mobile']);
					$user_info_array_mobile = $this->get_one($select_user_id,$where_user_mobile);
					$registerData['id'] = $user_info_array_mobile['id'];
				}else{
					$where_user_email = array('email'=>$registerData['email']);
					$user_info_array_email = $this->get_one($select_user_id,$where_user_email);
					$registerData['id'] = $user_info_array_email['id'];
				}

				$registerData['member_url_prefix'] = $registerData['id'];                              //添加账号对应的地址头 ID
				$registerData['store_url'] = $registerData['id'];                                      //设置用户店铺URL

				$user_update_where = ['id'=>$registerData['id']]; //更新条件

				$registerData_id = $registerData['id'];
				unset($registerData['id']);
				$user_update_data  = $registerData;//更新内容

				$this->update_one($user_update_where,$user_update_data);
				$registerData['id'] = $registerData_id;

			}else{
				unset($registerData['reg_type']);
				$registerData['id'] = $this->insert_one($registerData);

			}

			//用戶身份证审核表
			$idCard = array('uid'=>$registerData['id'],'name'=>'');
			$this->load->model('tb_user_id_card_info');
			$this->tb_user_id_card_info->replace($idCard);

			/*插入用户一级组的信息表*/
			$this->createUserChildGroupData($registerData['id'], $registerData['parent_id']);


		}else{ /** 普通會員註冊 （沒有推薦人）*/
			unset($registerData['reg_type']);
			$registerData['name'] = '';
			$registerData['id'] = $this->insert_one($registerData);            //插入数据
		}

		//初始化用户积分 m by brady.wang 2017/06/20
        
		$this->load->model("tb_users_credit");
		$this->init_user_credit($registerData['id']);

		$res = $this->enableAccount(array('id'=>$registerData['id']),true);    //激活账户

		
		if ($this->db_slave->trans_status() === FALSE) {
			$this->db_slave->trans_rollback();
			return FALSE;
		} else {
			if($res == 0){
				$this->db_slave->trans_commit();
				return 0;
			}else{
				$this->db_slave->trans_rollback();
				return FALSE;
			}
		}

	}
	/**
	 * 账户激活
	 * @param type $data=array('id'=>用户id,'token'=>激活令牌);
	 * @param boolean $noVeri=ture(无需验证，激活接口直接调用) or false(用户邮件激活链接激活，需验证token以及过期时间)
	 * @return int $error_code #0代表成功，其他值代表各种失败错误码
	 */
	public function enableAccount($data, $noVeri = false) {

		$userInfo = $this->getUserByIdOrEmail($data['id']);//获取会员的信息

		if(!$userInfo){
			return 1008;
		}
		if(!$noVeri){
			if($data['token']!==$userInfo['token']){
				return 102;
			}
			if($userInfo['send_email_time']<(time() - 3600*3)){
				return 1012;
			}
		}

		if($userInfo['status']==1){
			return 0;
		}

		/*-----------账户激活-----------------*/
		$this->db_slave->trans_begin();

		$res = 0;//默认是 假
		$parent = $userInfo['parent_id'] ? TRUE : FALSE;
		if($parent){  /** 店主激活 */

			/*更改用户状态（激活or激活未付费）*/
			$status=1;
			$user_status_update_where = ['id'=>$data['id']];
			$user_status_update_data  = array('status' => $status,'store_url' => $data['id'],'member_url_prefix'=>$data['id'],'enable_time'=>date('Y-m-d H:i:s'));
			$res = $this->update_one($user_status_update_where,$user_status_update_data);

			if($res){
				/*统计会员每月推荐的人*/
				$this->load->model('tb_stat_intr_mem_month');
				$stat_intr_mem_month_select_select = "*";
				$stat_intr_mem_month_select_where = ['year_month'=>date('Ym'),'uid'=>$userInfo['parent_id']];
				$res_stat_intr_mem_month = $this->tb_stat_intr_mem_month->get_one($stat_intr_mem_month_select_select,$stat_intr_mem_month_select_where);

				if($res_stat_intr_mem_month){
					$stat_intr_mem_month_update_where = ['year_month'=>$res_stat_intr_mem_month['year_month'], 'uid'=>$res_stat_intr_mem_month['uid']];

					$member_free_num = $res_stat_intr_mem_month['member_free_num'] + 1;

					$stat_intr_mem_month_update_data  = array('member_free_num' => $member_free_num);

					//$stat_intr_mem_month_update_data  = ['member_free_num = member_free_num+1'=>null];

					$this->tb_stat_intr_mem_month->update_one($stat_intr_mem_month_update_where,$stat_intr_mem_month_update_data);
				}else{
					//echo "插入数据";
					$this->tb_stat_intr_mem_month->insert_one(array('year_month' => date('Ym'),'uid' => $userInfo['parent_id'],'member_free_num' => 1));
                    //$aaa = $this->db->insert("stat_intr_mem_month",array('year_month' => date('Ym'),'uid' => $userInfo['parent_id'],'member_free_num' => 1));
				}

				//需要修改成 tb_users_referrals_count_info 中的内容
				//$this->load->model('m_referrals_count');
				//$this->m_referrals_count->join_referrals_count($userInfo['parent_id'],4,4);
				$this->load->model('tb_users_referrals_count_info');
				$this->tb_users_referrals_count_info->join_referrals_count($userInfo['parent_id'],4,4);
			}

		}else{ /** 普通會員激活 */
			$res = $this->update_one(['id'=>$data['id']],array('status' => 1,'enable_time'=>date('Y-m-d H:i:s')));
		}

		//if($res){
		//	return 0;
		//}else{
		//	return 103;
		//}
		if ($this->db_slave->trans_status() === FALSE) {
			$this->db_slave->trans_rollback();
			return 103;
		} else {
			if($res){
				$this->db_slave->trans_commit();
				return 0;
			}else{
				$this->db_slave->trans_rollback();
				return 103;
			}
		}

		/*-----------/账户激活-----------------*/
	}

	/**
	 * 更新用户现金池，个人店铺销售提成统计
	 * @author: derrick
	 * @date: 2017年3月25日
	 * @param: @param int $uid 用户ID
	 * @param: @param numeric $comm_store_owner
	 * @param: @param numeric $comm_to_point 自动转分红点比例
	 * @reurn: return_type
	 */
	public function update_cash_and_store_stat_data($uid, $comm_store_owner, $comm_to_point) {
		$this->db->where('id', $uid)
		->set('amount', 'amount + '.(($comm_store_owner - $comm_to_point) / 100), false)
		->set('amount_store_commission', 'amount_store_commission + '.($comm_store_owner / 100), false)
		->update('users');
	}
	
	/**
	 * 用户注册新方法 --- leon
	 */
	public function register_user($registerData){
		$registerData = $this->initialize_user_info($registerData);              //初始化注册必要信息

		$this->db->trans_begin();                                          // 启动事物
		if(isset($registerData['parent_id']) && $registerData['parent_id']){
			/**
			 * 店铺加盟注册
			 */
			//获取会员的父类数量（10个父类SKU）
			$parent_ids_arr = array();
			$count = 1;
			$this->getTenParentIds($registerData['parent_id'],$parent_ids_arr,$count);
			$registerData['parent_ids'] = implode(',', $parent_ids_arr);

			if($registerData['reg_type'] == 1){
				/**
				 * 已有账户
				 * 更新用户信息
				 */
				unset($registerData['reg_type']);
				unset($registerData['pwd']);
				unset($registerData['token']);
                
				$registerData['id'] = $this->get_exist_users_id($registerData);                        //获取会员ID
				$registerData['member_url_prefix'] = $registerData['id'];                              //添加账号对应的地址头 ID
				$registerData['store_url'] = $registerData['id'];                                      //设置用户店铺URL
                
				$this->update_user_info($registerData);                                                //更新用户数据
			}else{
				/**
				 * 没有账户
				 * 新增用户信息
				 */
				$registerData['id'] = $this->insert_user_info($registerData);
			}

			//插入用戶身份证审核表信息
			$this->createUserIdCardInfo($registerData['id']);
			//插入用户一级组的信息表
			$this->createUserChildGroupData($registerData['id'], $registerData['parent_id']);
			//统计会员每月推荐的人
			$this->createStatIntrMemMonth($registerData['parent_id']);
			//统计会员所有的直推人
			$this->createUsersReferralsCountInfo($registerData['parent_id']);

		}else{
			/**
			 * 普通会员注册（游客注册 没有推荐人）
			 */
			unset($registerData['reg_type']);
			$registerData['name'] = '';
			$registerData['id'] = $this->insert_one($registerData);            //插入数据

		}

		//用户积分表生成
		$this->load->model("tb_users_credit");
        $ret = $this->tb_users_credit->check_exists($registerData['id']);
        if(empty($ret)) {
		$this->tb_users_credit->init_user_credit($registerData['id']);
        }
        

		//事物
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return 0;
		}

	}

	/**
	 * 更新父级用户现金池, 店铺销售提成统计
	 * @author: derrick
	 * @date: 2017年3月25日
	 * @param: @param unknown $p_uid
	 * @param: @param unknown $comm_parent
	 * @param: @param unknown $comm_to_point
	 * @reurn: return_type
	 */
	public function update_parent_cash_and_store_stat_data($p_uid, $comm_parent, $comm_to_point) {
		$this->db->where('id', $p_uid)
		->set('amount', 'amount + '.(($comm_parent - $comm_to_point) / 100), false)
		->set('team_commission', 'team_commission + '.($comm_parent / 100), false)
		->update('users');
	}

	/**
	 * 增加父類激活會員數
	 */
	public function addEnableChildCount($child_id){
		$user = $this->db_slave->from('users')->select('parent_ids')->where('id',$child_id)->get()->row_array();
		$parent_ids = explode(',',$user['parent_ids']);
		array_pop($parent_ids);//去掉最後的公司號
		if($parent_ids)foreach($parent_ids as $uid){
			$uid=(int)$uid;
			//注释掉 child_count 的更新
			//$this->db->where('id', $uid)->set('child_count', 'child_count+' . 1, FALSE)->update('users');
		}
	}

	/**
	 * 创建令牌 --- leon
	 * @param $curTimestamp  创建时间
	 * @return string        返回固定格式的加密字符串
	 */
	public function createToken($curTimestamp) {
		return md5('148' . $curTimestamp . '96tps!#@*');
	}

	/**
	 * 获取全部的父ID --- leon
	 * @param $parent_id
	 * @param $parent_ids_arr
	 * 需求调整 此方法不再使用（2017-03-16）
	 */
	public function getAllParentIds($parent_id,&$parent_ids_arr){
		$parent_ids_arr[] = $parent_id;
		$grand_parent_id = $this->db_slave->select('parent_id')->from('users')->where('id',$parent_id)->get()->row_object()->parent_id;
		if($grand_parent_id){
			$this->getAllParentIds($grand_parent_id, $parent_ids_arr);
		}
	}

	/**
	 * 获取全部的父ID --- leon    新修改的方法
	 * @param $parent_id        父类的ID
	 * @return string           全部父类ID字符串
	 *
	 * 需求变动  此方法暂时不再使用（2017-03-16）
	 */
	public function getAllParentIds_new($parent_id){
		$grand_parent_ids = $this->db_slave->select('parent_ids')->from('users')->where('id',$parent_id)->get()->row_array();
		$grand_parent_ids_str = $parent_id.",".$grand_parent_ids['parent_ids'];
		return $grand_parent_ids_str;
	}

	/**
     * @desc: 出于性能考虑 向上只递归十层推荐人
     * @author JacksonZheng
     * @date 20170306
     */
	public function getTenParentIds($parent_id,&$parent_ids_arr,&$count){
		$parent_ids_arr[] = $parent_id;
		//$grand_parent_id = $this->db_slave->select('parent_id')->from('users')->where('id',$parent_id)->get()->row_object()->parent_id;
		$select = "parent_id";
		$where  = array('id'=>$parent_id);
		$grand_parent_array = $this->get_one($select,$where);
		$grand_parent_id = $grand_parent_array['parent_id'];
		if($grand_parent_id){
			if($count<10) {
				$this->getTenParentIds($grand_parent_id, $parent_ids_arr,++$count);
			}
		}
	}

	/**
	 * 返回根据层级的上十层id
	 * @param $parent_id
	 * @param $parent_ids_arr
	 * @param $count
	 */
	public function getTenParentIds_level($parent_id,&$parent_ids_arr,&$count){
		$parent_ids_arr[$count] = $parent_id;
		$res = $this->tb_users->get([
				'select' => "id,parent_id",
				'where' => [
						'id' => $parent_id,
				]
		], false, true, true);
		if (isset($res['parent_id'])) {
			$grand_parent_id = $res['parent_id'];
			if($grand_parent_id){
				if($count < 10) {
					$this->getTenParentIds_level($grand_parent_id, $parent_ids_arr,++$count);
				}
			}
		}

	}

	public function get_ten_parent_ids($uid)
	{
		$parent_id_res = $this->db->from("users")->select("id,parent_id,parent_ids")->where(['id'=>$uid])->get()->row_array();
		$parent_ids_arr = array();
		if(!empty($parent_id_res)) {
			$parent_id = $parent_id_res['parent_id'];
			$count = 1;
			$this->load->model("tb_users");
			$this->getTenParentIds_level($parent_id,$parent_ids_arr,$count);
		}

		return $parent_ids_arr;
	}

	/**
	 * @author brady
	 * @desc  用户更新积分 调用改方法需要在事物里面
	 * @param $uid 用户id
	 * @param $old 原来的职称
	 * @param $new 新的职称
	 * @param $type 类型，升级来的，或者降级来的，
	 */
	public function update_sale_rank($user_info,$old,$new,$type)
	{
		$this->load->model("tb_commission_logs");
		$this->load->model("tb_users_store_sale_info_monthly");
		$this->db->where('id', $user_info['id'])->update('users', array('sale_rank' => $new, 'sale_rank_up_time' => date('Y-m-d H:i:s'))); //更新职称

		$this->load->model("tb_users_credit_queue_sale_rank");
		$queue_data = array(
				'uid'=>$user_info['id'],
				'before_sale_rank'=>$old,
				'after_sale_rank'=>$new,
				'created_time'=>date("Y-m-d H:i:s"),
				'type'=>$type
		);
		//$this->tb_users_credit_queue_sale_rank->add_queue($queue_data); //加入积分变化队列


		/**触发职称改变的存储过程**/
		$old_sale_rank = $old;
		$uid = $user_info["id"];


		/*更新用户职称后检查其是否满足了周领导对等奖，如果是第一次满足，则立即加入周领导对等奖。*/
		if( $new>=2 && $user_info['user_rank']==1 && !$this->tb_commission_logs->checkUserCommissionOfType($user_info["id"],7) ){
			$newMonthSaleAmount = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($user_info["id"],date('Ym'));
			if($newMonthSaleAmount>=7500 && date('Ym')<=201610){
				$this->db->replace('week_leader_members', array(
						'uid' => $user_info["id"],
				));
			}
		}

		//$proc_sql = "call user_rank_change_week_comm('$uid','$old_sale_rank','$new',0);";
		//$this->db->query($proc_sql);

		$this->load->model("tb_users_store_sale_info_monthly");
		$this->tb_users_store_sale_info_monthly->user_rank_change_week_comm($uid, $old_sale_rank, $new, 0);

		$this->tb_users_credit_queue_sale_rank->set_uids_credit($queue_data);

	}

	/**
	 * 返回根据层级的上十层id
	 * @param $parent_id
	 * @param $parent_ids_arr
	 * @param $count
	 */
	public function getTwoParentIds_level($parent_id,&$parent_ids_arr,&$count){
		$parent_ids_arr[$count] = $parent_id;
		$res = $this->tb_users->get([
				'select' => "id,parent_id",
				'where' => [
						'id' => $parent_id,
				]
		], false, true, true);

		if (!empty($res)) {
			$grand_parent_id = $res['parent_id'];
			if($grand_parent_id){
				if($count<2) {
					$this->getTwoParentIds_level($grand_parent_id, $parent_ids_arr,++$count);
				}
			}
		}

	}

	public function get_two_parent_ids($uid)
	{
		$parent_id_res = $this->db->from("users")->select("id,parent_id,parent_ids")->where(['id'=>$uid])->get()->row_array();
		$parent_id = $parent_id_res['parent_id'];
		$parent_ids_arr = array();
		$count = 1;
		$this->load->model("tb_users");
		$this->getTwoParentIds_level($parent_id,$parent_ids_arr,$count);
		return $parent_ids_arr;
	}



	/**
	 * 检测会员的密码是不是正确 --- leon
	 * @param $registerData
	 * @return bool
	 */
	public function checkUserPwd($registerData) {
		$userInfo = $this->getUserByIdOrEmail($registerData['id']);
		if(!$userInfo){
			return false;
		}
		if( $this->pwdEncryption( $registerData['pwdOld'],$userInfo['token'] ) === $userInfo['pwd'] ){
			return true;
		}
		return false;
	}

	/**
	 *  更新用户数据
	 * @author: derrick
	 * @date: 2017年3月25日
	 * @param: @param int $uid
	 * @param: @param array $user_data
	 * @reurn: return_type
	 */
	public function update_info($uid, Array $user_data) {
		$allow_update_columns = ['store_qualified'];
		$this->db->where('id', $uid);
		foreach ($user_data as $column => $value) {
			if (in_array($column, $allow_update_columns)) {
				$this->db->set($column, $value);
			}
		}
		$this->db->update('users');
	}

	/**
	 * 返回用户的ID --- leon
	 */
	public function get_exist_users_id($registerData){
		$select_user_id = "id";
		if(isset($registerData['mobile']) && $registerData['mobile'] ){
			$where_user_mobile=array('mobile'=>$registerData['mobile']);
			$user_info_array_mobile = $this->get_one($select_user_id,$where_user_mobile);
			$user_id = $user_info_array_mobile['id'];
		}else{
			$where_user_email = array('email'=>$registerData['email']);
			$user_info_array_email = $this->get_one($select_user_id,$where_user_email);
			$user_id = $user_info_array_email['id'];
		}
		return $user_id;
	}
	/**
	 * 添加用户一级组的信息
	 * @param $id
	 * @param $parent_id
	 */
	public function createUserChildGroupData($id,$parent_id){
		if ($parent_id != config_item('mem_root_id')) {
			$this->load->model('tb_users_child_group_info');
			$data = array('uid' => $parent_id,'group_id' => $id);
			$this->tb_users_child_group_info->replace($data);
          
		}
	}
	/**
	 * 插入用戶身份证审核信息 --- leon
	 */
	public function createUserIdCardInfo($users_id){
		$idCard = array('uid'=>$users_id,'name'=>'');
		$this->load->model('tb_user_id_card_info');
		$this->tb_user_id_card_info->replace($idCard);
        
	}
	/**
	 * 统计会员每月推荐的人 --- leon
	 */
	public function createStatIntrMemMonth($user_parent_id){
		/*统计会员每月推荐的人*/
		$this->load->model('tb_stat_intr_mem_month');
		$stat_intr_mem_month_select_select = "*";
		$stat_intr_mem_month_select_where = ['year_month'=>date('Ym'),'uid'=>$user_parent_id];
		$res_stat_intr_mem_month = $this->tb_stat_intr_mem_month->get_one($stat_intr_mem_month_select_select,$stat_intr_mem_month_select_where);

		//print_r($res_stat_intr_mem_month);exit;

		if($res_stat_intr_mem_month){
			$stat_intr_mem_month_update_where = ['year_month'=>$res_stat_intr_mem_month['year_month'], 'uid'=>$res_stat_intr_mem_month['uid']];
			$member_free_num = $res_stat_intr_mem_month['member_free_num'] + 1;
			$stat_intr_mem_month_update_data  = array('member_free_num' => $member_free_num);
			$this->tb_stat_intr_mem_month->update_one($stat_intr_mem_month_update_where,$stat_intr_mem_month_update_data);
		}else{
			$this->tb_stat_intr_mem_month->insert_one(array('year_month' => date('Ym'),'uid' => $user_parent_id,'member_free_num' => 1));
		}
       
	}
	/**
	 * 统计会员所有的直推人 --- leon
	 */
	public function createUsersReferralsCountInfo($user_parent_id){
		$this->load->model('tb_users_referrals_count_info');
		$this->tb_users_referrals_count_info->join_referrals_count($user_parent_id,4,4);
       
	}
	/**
	 * 注册 更新用户信息 --- leon
	 */
	public function update_user_info($registerData){
		$user_id = $registerData['id'];
		unset($registerData['id']);
		$this->update_one(['id'=>$user_id],$registerData);                      //执行
       
	}
	/**
	 * 注册 插入用户信息 --- leon
	 */
	public function insert_user_info($registerData){
		unset($registerData['reg_type']);
		$registerData['id'] = $this->insert_one($registerData);

		$register_update['member_url_prefix'] = $registerData['id'];                              //添加账号对应的地址头 ID
		$register_update['store_url']         = $registerData['id'];                              //设置用户店铺URL
		$this->update_one(['id'=>$registerData['id']],$register_update);
		return $registerData['id'];
	}
	/**
	 * 注册 初始化用户必要数据 --- leon
	 */
	public function initialize_user_info($registerData){
		//设置注册时间
		if (!isset($registerData['create_time'])) {
			$registerData['create_time'] = time();
		}
		//设置token值
		if(!isset($registerData['token'])){
			$registerData['token'] = $this->createToken($registerData['create_time']);
		}
		//设置密码值
		if (!isset($registerData['pwd'])) {
			$registerData['pwd'] = $this->pwdEncryption($registerData['pwdOriginal'], $registerData['token']);
			$registerData['pwd_ori_md5'] = md5($registerData['pwdOriginal']);
			unset($registerData['pwdOriginal']);
		}
		//设置国家
		if(isset($registerData['country_id']) && !in_array($registerData['country_id'],array(1,4))){
			$registerData['languageid'] = 2;
		}
		//手机注册 ？ 更新已验证手机标识
		if(isset($registerData['mobile']) && $registerData['mobile'] ){
			$registerData['is_verified_mobile'] = 1;
		}else{
			$registerData['is_verified_email'] = 1; //更新已验证邮箱标识
		}
		//会员账户状态
		if(!isset($registerData['status'])){
			$registerData['status'] = 1;
		}
		//会员激活时间
		if(!isset($registerData['enable_time'])){
			$registerData['enable_time'] = date('Y-m-d H:i:s');
		}
		return $registerData;
	}

	/**
	 * @author: derrick 查询时间段内某个用户下的注册用户
	 * @date: 2017年5月18日
	 * @param: @param int $start
	 * @param: @param int $end
	 * @reurn: return_type
	 */
	public function find_by_parent_and_create_time(Array $parent_ids, $start, $end, $select_fields = '*') {
		if (empty($parent_ids)) {
			return array();
		}
		return $this->db->select($select_fields)->where_in('parent_id', $parent_ids)->where('create_time >=', strtotime($start))->where('create_time <=', strtotime($end))->get($this->table_name)->result_array();
	}
	
	/**
	 * 更新用户升级时间
	 * @param unknown $uid
	 * @param unknown $time
	 */
	public function edit_user_sale_up_time($uid,$time)
	{	    
	    $time = $time . " ".date("h:i:s");	    
	    $sql = "update users set sale_rank_up_time='".$time."' where id = ".$uid;
	    $this->db->query($sql);
	}
	
	/**
	 * @author: derrick 根据父ID查询记录
	 * @date: 2017年6月29日
	 * @param: @param unknown $parent_id
	 * @reurn: return_type
	 */
	public function find_by_parent_id($select = '*', $parent_id) {
		return $this->db->select($select)->where('parent_id', $parent_id)->get($this->table_name)->result_array();
	}	

	/**
	 * @author: derrick 更新用户等级
	 * @date: 2017年6月29日
	 * @param: @param int $user_id 用户ID
	 * @param: @param int known $rank_id 职称ID
	 * @reurn: return_type
	 */
	public function update_user_rank($user_id, $rank_id) {
		return $this->db->set('user_rank', $rank_id)->where('id', $user_id)->update($this->table_name);
	}

	/**
	 * @author: derrick 统计用户下的直系付费用户数
	 * @date: 2017年6月29日
	 * @param: @param int $parent_id
	 * @reurn: return_type
	 */
	public function count_pay_user_by_parent_id($parent_id) {
		$num = $this->db->select('count(id) num')->from($this->table_name)->where('parent_id',$parent_id)->where('user_rank !=', 4)->get()->row_object()->num;
		return $num ? $num : 0; 
	}
	
	/**
	 * @author: derrick 从一个用户列表中获取指定下标的一个用户
	 * @date: 2017年7月3日
	 * @param: @param array $users
	 * @param: @param unknown $special_no
	 * @reurn: return_type
	 */
	public function find_special_one_from_users(Array $users, $special_no,$field = '') {
		$field = $field ? $field : '*';
		if (empty($users)) {
			return array();
		}
		$special_no = $special_no <= 1 ? 1 : $special_no;
		return $this->db->select($field)->where_in('id', $users)->order_by("sale_rank", "DESC")->order_by("sale_rank_up_time", "ASC")->limit(1, $special_no - 1)->get($this->table_name)->row_array();
	}
}