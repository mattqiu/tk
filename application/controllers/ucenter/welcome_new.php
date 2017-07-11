<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Welcome_new extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
        $this->load->model('m_user');
        $this->load->model('m_profit_sharing');
    }

    public function index(){
        $this->_viewData['title'] = lang('welcome_page');
        $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        $this->_viewData['user'] = $user;


        //当前语言ID
        $this->_viewData['language_id'] = $this->session->userdata('language_id');

        //加盟时间
        $this->_viewData['join_time'] = date('Y-m-d H:i:s',$this->_userInfo['create_time']);

        //月费券
        $this->load->model("m_coupons");
        $monthly_fee_coupon_num = $this->m_coupons->getMonthlyFeeCouponNum($this->_userInfo['id']);
        $this->_viewData['monthly_fee_coupon_num'] = $monthly_fee_coupon_num ? $monthly_fee_coupon_num : 0;
        //职称
        $this->_viewData['cur_title_text'] = lang('title_level_'.$this->_userInfo['sale_rank']);

        //职称更新时间
        $this->_viewData['sale_rank_up_time'] = $this->_userInfo['sale_rank_up_time'];


        //月费状态
        $this->_viewData['account_status']=$this->m_user->account_status($this->_userInfo['id'],$this->_userInfo['status']);

        //获取上次登录信息
        $this->_viewData['lastLoginInfo'] = $this->m_user->getLastLoginInfo($this->_userInfo['id'],$this->_viewData['curLan_id']);

        /*代品券数量*/
        $this->load->model('m_suite_exchange_coupon');
        $allCoupons = $this->m_suite_exchange_coupon->getAll($this->_userInfo['id']);
        $coupon_total_num = 0;
        foreach($allCoupons as $k=>$itemNum){
            $coupon_total_num += $itemNum;
        }
        $this->_viewData['coupon_total_num']=$coupon_total_num;

        /*月费券*/
        $monthlyFeeCoupon = $this->m_user->getMonthlyFeeCoupon($this->_userInfo['id'],0);
        if($monthlyFeeCoupon){
            switch ($monthlyFeeCoupon['coupon_level']) {
                case 1:
                    $levelNameKey = 'level_diamond';
                    break;
                case 2:
                    $levelNameKey = 'level_platinum';
                    break;
                default:
                    $levelNameKey = 'level_silver';
                    break;
            }
            $monthlyFeeCoupon['levelName'] = lang($levelNameKey);
        }
        $this->_viewData['monthlyFeeCoupon']=$monthlyFeeCoupon;

        $this->_viewData['data']=$this->choose_alert();

        //重新选购名单
        $user_id=$this->_userInfo['id'];
        if(in_array($user_id,config_item('again_choose_group')) && $this->_userInfo['is_choose']==0){
            $this->load->model('m_group');
            $this->_viewData['data']=$this->m_group->again_choose_group($user_id);
        }

        $this->_viewData['proportion'] = array(
            'sale_commissions_proportion'=>$this->m_profit_sharing->getProportion($this->_userInfo['id'],'sale_commissions_proportion'),
            'forced_matrix_proportion'=>$this->m_profit_sharing->getProportion($this->_userInfo['id'],'forced_matrix_proportion'),
            'profit_sharing_proportion'=>$this->m_profit_sharing->getProportion($this->_userInfo['id'],'profit_sharing_proportion'),
        );

        $this->_viewData['totalSharingPoint'] = $this->m_user->getTotalSharingPoint($this->_userInfo['id']);
        $this->_viewData['rewardSharingPointList'] = $this->m_user->getRewardSharingPointData($this->_userInfo['id']);

        switch ($this->_userInfo['month_fee_rank']) {
            case 1:
                $levelInfo['level'] = lang('diamond');
                break;
            case 2:
                $levelInfo['level'] = lang('gold');
                break;
            case 3:
                $levelInfo['level'] = lang('silver');
                break;
            default:
                $levelInfo['level'] = lang('free');
                break;
        }
        $msg = $this->input->get('msg');
        $this->_viewData['msg'] = $msg=='payok'?lang('payment_success_delay_notice'):'';
        $this->_viewData['note_type'] = $msg;
        $this->_viewData['levelInfo'] = $levelInfo;
        $this->_viewData['uid'] = $this->_userInfo['id'];
        $this->_viewData['ewallet_name'] = $this->_userInfo['ewallet_name'];
        $this->_viewData['user_rank'] = $this->_userInfo['user_rank'];

		$this->load->model('m_news');
        $this->_viewData['bulletins'] = $this->m_news->getFiveBoard($this->_userInfo['parent_id'],$this->_userInfo['id']);

		/** 上传了身份证 */
		$this->load->model('m_admin_helper');
		$card = $this->m_admin_helper->getCardOne($this->_userInfo['id']);
		$check = isset($card['check_status']) && $card['check_status'] > 1 ? TRUE : FALSE;
		$this->_viewData['check'] = $check;

		/** 强制弹出框 */
		$is_login = get_cookie('just_login',true);
		$is_alert = FALSE;
		if($is_login && $this->_userInfo['alert_count'] < 1 && time()<strtotime('2016-1-31 11:59:59')){
			/** 超重要通知 强制弹出，一次15秒才可以关闭 */
			$is_alert = TRUE;
		}
		$this->_viewData['is_alert'] = $is_alert;

        //是否弹层.不是游客
        if($this->_userInfo['status'] == 1 && $this->_userInfo['user_rank'] != 4 && $this->_userInfo['is_auto_notice'] == 0 && $this->_userInfo['is_auto'] == 0){
            $is_notice = true;
        }else{
            $is_notice = false;
        }

        //后台进入，不弹层
        $cookie_info = unserialize($_COOKIE['userInfo']);
        if(isset($cookie_info['readOnly']) && $cookie_info['readOnly']){
            $is_notice = false;
        }

        $this->_viewData['is_notice'] = $is_notice;
		$this->load->model('tb_users_april_plan');
		$row = $this->tb_users_april_plan->is_join_plan($this->_userInfo['id'],1);
        $this->_viewData['is_join_plan'] = $row;

        parent::index();
    }

	/** 更新弹出次数 */
	public function update_alert_count(){
		if($this->input->is_ajax_request()){
			delete_cookie('just_login',get_public_domain());
			$this->db->where('id',$this->_userInfo['id'])->set('alert_count','alert_count + 1',FALSE)->update('users');
			die(json_encode(array('success'=>1)));
		}
	}

    /*使用月费抵用券ajax*/
    public function useMonthlyFeeCouponAjax(){
        $error_code = $this->m_user->useMonthlyFeeCoupon($this->_userInfo['id'],$this->_userInfo['month_fee_pool']);
        if(!$error_code){
            $success = TRUE;
            $msg = lang('user_monthli_fee_coupon_success');
        }else{
            $success = false;
            $msg = lang(config_item('error_code')[$error_code]);
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }


    public function choose_alert(){
        $user_id=$this->_userInfo['id'];
        $user_rank=$this->_userInfo['user_rank'];
        $is_choose=$this->_userInfo['is_choose'];

        $data=null;
        if($user_rank<4 && $is_choose!=1){
            $sql="select * from users_level_change_log where level_type=2 and uid=$user_id order by create_time desc";
            $result=$this->db->query($sql)->result();
            if(!empty($result)){
                $data['user_id']=$user_id;
                $data['old_level']=$result[0]->old_level;
                $data['new_level']=$result[0]->new_level;
                $data['create_time']=$result[0]->create_time;

                if($data['old_level']==4)$data['old_level']=lang('member_free');
                if($data['old_level']==3)$data['old_level']=lang('member_silver');
                if($data['old_level']==2)$data['old_level']=lang('member_platinum');
                if($data['old_level']==1)$data['old_level']=lang('member_diamond');

                $this->load->model('m_user');
				$is_true = $this->m_user->is_first_upgrade_time_1_1($user_id);
				if($is_true){
					$joinFeeAndMonthFee = config_item('old_join_fee_and_month_fee');
				}else{
					$joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
				}

                if($data['new_level']==1){
                    $data['pay_money']=($joinFeeAndMonthFee[1]['join_fee']);
                    $data['new_level']=lang('member_diamond');
                }
                if($data['new_level']==2){
                    $data['pay_money']=($joinFeeAndMonthFee[2]['join_fee']);
                    $data['new_level']=lang('member_platinum');
                }
                if($data['new_level']==3){
                    $data['pay_money']=($joinFeeAndMonthFee[3]['join_fee']);
                    $data['new_level']=lang('member_silver');
                }

                $data['name']=$this->_userInfo['name']?ucwords($this->_userInfo['name']):$this->_userInfo['email'];
            }
        }
        return $data;
    }

	public function get_board_msg(){
       
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			$this->load->model('m_news');
			$row= $this->m_news->getOneBoard($id);
			if($row){
                //m by brady.wang 如果是12.26日以后的进行插入
                $time_till = strtotime('2016/12/26');
                if (strtotime($row['create_time']) > $time_till) {
                    $this->load->model("m_bulletin_read");
                    $uid = $this->_userInfo['id'];;
                    $data['uid'] = $uid;
                    $data['bulletin_id'] = $row['id'];
                    if(!$this->m_bulletin_read->getOne(array("uid"=>$uid,"bulletin_id"=>$row['id']))) {
                        //删除缓存 m by brady 2017 05 24 start
                        $this->load->model("tb_bulletin_board");
                        $key_prefix = config_item("redis_key")['bulletin_board_index'];
                        $keys = $this->tb_bulletin_board->redis_keys($key_prefix.$this->_userInfo['id'].":"."*");
                        foreach($keys as $v) {
                            $this->tb_bulletin_board->redis_del($v);
                        }


                        //删除缓存 m by brady 2017 05 24 end
                        $this->m_bulletin_read->add($data);
                    }
                } else {
                    $this->db->where('bulletin_id',$id)->where('uid',$this->_userInfo['id'])->delete('bulletin_unread');
                }
				$count = $this->db->affected_rows();
				if($count){
					//$unread_count = get_cookie('unread_count',true);
                    // m by brady.wang 修改获取消息方式
                    $this->load->model('tb_bulletin_board');
                    $unread_count = $this->tb_bulletin_board->get_unread_counts($this->_userInfo['id'],$this->_viewData['curLan'],$this->_userInfo['parent_id']);
					$new_count = $unread_count;
					set_cookie('unread_count',$new_count,0,get_public_domain());
					$result['count'] = $new_count;
				}else{
					$unread_count = get_cookie('unread_count',true);
					$result['count'] = $unread_count;
				}
				$result['success'] = 1;
				$result['msg'] = $row[$this->_curLanguage];
				$result['time'] = $row['create_time'];
				$result['title'] = $row['title_'.$this->_curLanguage];
			}else{
				$result['success'] = 0;
				$result['msg'] = lang('try_again');
			}
			die(json_encode($result));
		}
	}

    //点击确定按钮后不再弹出提示框
    public function is_auto_notice(){
        $confirm = $this->input->post('confirm');
        $user_id = $this->_userInfo['id'];
        if($confirm == '1'){
            $this->db->query("update users set is_auto_notice = 1 where id = $user_id");
        }

        echo json_encode(array('success'=>true));
        exit();
    }

	/** 創建休眠用户4月份加入的什么计划 */
	public function create_join_plan(){
		if($this->input->is_ajax_request()){
			$type = $this->input->post('type');
			if(!in_array($type,array('1','2','3'))){
				die(json_encode(array('success'=>false,'msg'=>lang('try_again'))));
			}
			$this->load->model('tb_users_april_plan');
			$is_success = $this->tb_users_april_plan->create_join_plan($this->_userInfo['id'],$type);
			if($type == '2'){
				$this->m_user->update_is_auto($this->_userInfo['id']);
			}
			if($is_success){
				die(json_encode(array('success'=>true)));
			}else{
				die(json_encode(array('success'=>false,'msg'=>lang('update_failure'))));
			}

		}
	}
}

