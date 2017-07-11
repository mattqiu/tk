<?php
/**
 * @author john  临时功能
 *
 2、用户在4月份做50美金销售额即可从休眠恢复正常（不发通告，但系统会发一封邮件通知到指定用户）
2.1、休眠用户在4月份登陆时会收到一个强制阅读的弹框（收到此弹框的将不会收到第一个弹层内容），内容为：
公司在4月份针对付费的老客户有一项优惠活动，如您之前有欠月费，现在可以通过在本月内做到累积50美金订单销售额来使得账户从欠月费恢复正常。
1    参加此优惠计划
2    不参加该计划，并同意设置自动从现金池转差额到月费池以支付月费。(银级：10美；金级：20美金；钻石：30美金）
3    不参加该计划，不设置自动从现金池转差额到月费池。
注意：1、此计划中完成的50美金订单不可取消；2、优惠计划有效期为2016年4月1日－2016年4月30日；3、账户恢复之前因为休眠没有拿到的奖金不补发。
 */
class tb_users_april_plan_order extends CI_Model {


    function __construct() {
        parent::__construct();
    }




	/** 参加活动用户下的订单 */
	public function create_plan_order($uid,$order_id){
		$this->db->insert('users_april_plan_order',array(
			'uid'=>$uid,
			'order_id'=>$order_id,
		));
		return $this->db->insert_id();
	}

	/** 检测是否是4月份的活动订单 */
	public function is_april_order($order_id){
		$count  = $this->db->from('users_april_plan_order')->where('order_id',$order_id)->count_all_results();
		return $count;
	}

}
