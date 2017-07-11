<?php
/**
 * @author john
 *
 2、用户在4月份做50美金销售额即可从休眠恢复正常（不发通告，但系统会发一封邮件通知到指定用户）
2.1、休眠用户在4月份登陆时会收到一个强制阅读的弹框（收到此弹框的将不会收到第一个弹层内容），内容为：
公司在4月份针对付费的老客户有一项优惠活动，如您之前有欠月费，现在可以通过在本月内做到累积50美金订单销售额来使得账户从欠月费恢复正常。
1    参加此优惠计划
2    不参加该计划，并同意设置自动从现金池转差额到月费池以支付月费。(银级：10美；金级：20美金；钻石：30美金）
3    不参加该计划，不设置自动从现金池转差额到月费池。
注意：1、此计划中完成的50美金订单不可取消；2、优惠计划有效期为2016年4月1日－2016年4月30日；3、账户恢复之前因为休眠没有拿到的奖金不补发。
 */
class tb_users_april_plan extends CI_Model {

    function __construct() {
        parent::__construct();
    }


	/** 判断是否是4月份，休眠的用户参加了计划 */
	public function is_join_plan($uid,$type = false){

		return $this->get_user_plan($uid,$type);

	}

    /**
 	* 判断休眠用户四月份加入的是什么计划
 	*/
	public function get_user_plan($uid,$type = false){
		if($type){
			$this->db->where('type',$type);
		}
		$res = $this->db->select('type,create_time')->where('uid',$uid)->get('users_april_plan')->row_array();
		return $res?$res:false;
	}

	/** 休眠用户四月份加入的计划 */
	public function create_join_plan($uid,$type){

		$this->db->insert('users_april_plan',array(
			'uid'=>$uid,
			'type'=>$type,
		));
		return $this->db->insert_id();
	}

	/**
	 * 根据用户ID删除记录
	 * @author: derrick
	 * @date: 2017年4月5日
	 * @param: @param unknown $uid
	 * @reurn: return_type
	 */
	public function delete_by_uid($uid) {
		$this->db->delete('users_april_plan', array('uid' => $uid));
	}
}
