<?php
/**
 * @author John
 * 附件model
 */
class tb_admin_tickets_attach extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

	/**附件临时路径
	 * @return bool|string
	 */
	public function set_attach_temp_path(){
		$attach_path ="upload/temp/".strval(date('Ym',time()))."/";
		if(!is_dir("upload/temp/")){$b = mkdir("upload/temp/",0777);if(!$b){return false;} }
        if(!is_dir($attach_path)){$b = mkdir($attach_path,0777);if(!$b){return false;} }
        return $attach_path;
	}

	/** 最后保存的附件路径
	 * @return bool|string
	 */
	public function save_attach_path(){
		$attach_path ="upload/tickets/".strval(date('Ym',time()))."/";
		if(!is_dir("upload/tickets")){$b = mkdir("upload/tickets/",0777);if(!$b){return false;}}
        if(!is_dir($attach_path)){$b = mkdir($attach_path,0777);if(!$b){return false;} }
        return $attach_path;
	}

	/**
	 * @param $name 文件名
	 * @return string
	 */
	public function set_attach_name($name){
		return date('dHis', time()) . mt_rand(0,1) . mt_rand(0, 10000) . '.' . explode('.',$name)[1];
	}

	/**保存文件的路径，名称，后缀，是否为回复的附件
	 * @param $attach_arr
	 * @return 插入的id
	 * @author andy
	 */
    public function add_attach($attach_arr){
		$this->db->insert('admin_tickets_attach',$attach_arr);
		return $this->db->insert_id();
	}

	/** 通过工单id获取该工单下的所有附件
	 * @param $tickets_id
	 * @param null $is_reply 0：原工单附件 1：回复附件
	 * @return mixed
	 * @author andy
	 */
	public function get_attach_by_tickets_id($tickets_id,$is_reply = NULL){
		$this->db->from('admin_tickets_attach');
		return $this->db->where('tickets_id',(int)$tickets_id)->where('is_reply',$is_reply)->get()->result_array();
	}

	/** 根据
	 * @param $ticket_id
	 * @return mixed
	 */
	public function get_attach_by_id($ticket_id){
		$this->db->from('admin_tickets_attach');
		return $this->db->where('id',$ticket_id)->get()->row_array();
	}

	/** 移动文件到另外的文件夹
	 * @param $old_path_name
	 * @return false or 目标路径
	 * @author andy
	 */
	public function move_files($old_path_name){
		$new_file_path = $this->save_attach_path();
		if(!$new_file_path){return false;}
		$file_name = explode('/', $old_path_name)[3];
		$new_file_path .= $file_name;
		$check = rename($old_path_name,$new_file_path);
		if($check){
			return $new_file_path;
		}else{
			return false;
		}
		
	}
}