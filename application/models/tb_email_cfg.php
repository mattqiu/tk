<?php
/**
 * @author John
 * 邮箱服务器的配置，从哪个位置开始获取邮件，每次获取多少邮件
 */
class tb_email_cfg extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 	得到邮箱服务器配置
     */
    public function get_email_cfg(){
        $data = $this->db->select('id,distance,start_count')->get('email_cfg')->row_array();
        return $data;
    }

	/** 修改配置的起始位置 */
	public function update_email_start($id,$count){
		$this->db->where('id',$id)->set('start_count','start_count+' . $count, FALSE)->update('email_cfg');
		return $this->db->affected_rows();
	}

}
