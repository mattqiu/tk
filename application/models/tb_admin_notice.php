<?php
/**
 * @author Jackson Zheng
 * 管理员身份证次数用尽 手机短信model
 */
class tb_admin_notice extends CI_Model
{
    function __construct()
    {
        parent::__construct();

    }

	/** 获取card_phone状态 0则手机短信通知打开 1则关闭
	 * @param 
	 * @return 
	 */
	public function get_card_phone() {
		$query = $this->db->query('select card_phone from admin_notice');
		if($query->num_rows()>0) {
            return $query->row()->card_phone;
        } else {
            return 1;
        }
	}
    
    /*
     * 关闭身份证接口次数用尽 手机短信通知
     */
    public function turn_off_notice() {
        
        $this->db->update('admin_notice',array('card_phone'=>1));
    }
    
    
    /*
     * 开启身份证接口次数用尽 手机短信通知
     */
    public function turn_on_notice() {
        
        $this->db->update('admin_notice',array('card_phone'=>0));
        
    }

	
}