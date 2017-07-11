<?php

class tb_email extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_all_email_list($filter,$per_page=20){
        return $this->db->select()->from('email')->order_by('send_date','desc')->limit($per_page,($filter['page'] - 1) * $per_page)->get()->result_array();
    }

    public function get_email_total(){
       return $this->db->from('email')->count_all_results();
    }

    public function get_email_detail($email_id=''){
        return $this->db->from('email')->where('id',$email_id)->get()->result_array();

    }
	/** 添加邮件数据 */
	public function add_email_row($email_data){

		$this->db->insert('email',$email_data);
		return $this->db->insert_id();
	}

}