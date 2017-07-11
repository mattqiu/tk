<?php
/**
 * @author John
 * 邮件的附件
 */
class tb_email_attach extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	/** 保存邮件附件的名称，路径 */
    public function add_email_attach($attach_arr){
		$this->db->insert('email_attach',$attach_arr);
		return $this->db->insert_id();
	}

    /**获取邮件附件的名称，路径，类型，后缀
     *param:email_id 邮件的id
     */
    public function get_email_attach($email_id){
        $this->db->from('email_attach')->select('name,path_name,type,extension');
        return $this->db->where('email_id',$email_id)->get()->result_array();
    }

    //文件夹路径
    public function set_attach_temp_path(){
        $attach_path ="upload/temp/".strval(date('Ym',time()))."/";
        if(!is_dir($attach_path)){ mkdir($attach_path,0777); }
        return $attach_path;
    }
    //自定义文件名
    public function set_attach_name($name){
        return date('dHis', time()) . mt_rand(0,1) . mt_rand(0, 10000) . '.' . explode('.',$name)[1];
    }

}
