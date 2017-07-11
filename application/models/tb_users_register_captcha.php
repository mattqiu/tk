<?php
/**
 * @author john
 * 会员注册的验证码，通过手机或者邮箱发送
 *
 */
class tb_users_register_captcha extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	/** 前100条没有发送验证码的记录
	 * @return mixed 前100条没有发送验证码的记录集合
	 */
	public function get_captcha_top100(){
		$codes  = $this->db->from('users_register_captcha')->where('status',0)->limit(100)->get()->result_array();
		return $codes;
	}

	/** 修改发送验证码记录：状态
	 * @param $id 记录ID
	 * @param $update 修改的内容
	 * @return mixed 返回影响的行数
	 */
	public function update_captcha($id,$update){
		$this->db->where('id',$id)->update('users_register_captcha',$update);
		return $this->db->affected_rows();
	}

	/**
	 * @param $email_or_phone 邮箱或者手机
	 * @param $language_id 会员在哪种语言下注册的
	 * @param $action_id 活动ID
	 * @param $code 验证码
	 * @return mixed
	 */
	public function add_captcha($email_or_phone,$language_id,$action_id,$code) {
		
		$this->db->insert('users_register_captcha',array(
			'email_or_phone'=>$email_or_phone,
			'language_id'=>$language_id,
			'code'=>$code,
			'action_id'=>$action_id,
			'expire_time'=>strtotime('+31 minutes'),
		));
        return $this->db->insert_id();
    }


	/**
	 * @param $email_or_phone 邮箱或者手机
	 * @param $action_id 活动ID
	 * @param $code 活动ID
	 * @return bool 整个记录，没有返回FALSE
	 */
	public function get_captcha($email_or_phone,$action_id){
		$row = $this->db->where('email_or_phone',$email_or_phone)->where('action_id',$action_id)
			->order_by('id','desc')->get('users_register_captcha')->row_array();
		return $row;
	}


}
