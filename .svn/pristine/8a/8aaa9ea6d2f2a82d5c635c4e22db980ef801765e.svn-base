<?php
/**
 * 正在执行的脚本信息：脚本名称，执行时间
 */
class tb_cron_doing extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 脚本任务管理列表
     * @return boolean
     * @author Terry
     */
    public function selectAll($filter,$perPage = 10) {
        $this->db->from('cron_doing');
        $this->filterForNews($filter);
//        $lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
//		$this->db->where('language_id',$lang_id);
        $list = $this->db->select('')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
       return $list;
    }

	/**
	 * 获取分页总数
	 */
	function  getExceptionRows($filter){
		$this->db->from('cron_doing');
		$this->filterForNews($filter);
		$row = $this->db->count_all_results();
		return $row;
	}

	public function filterForNews($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'start':
					$this->db->where('create_time >=', strtotime($v));
					break;
				case 'end':
					$this->db->where('create_time <=', strtotime($v));
					break;
				case 'cron_name':
					$this->db->where("cron_name like '%$v%'");
					break;

				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	/**
	 * 查看该id是否已经存在
	 */
	public function findOne($id){
		$this->db->from('cron_doing');
		$one = $this->db->where('id',$id)->get()->row_array();
		return $one;
	}
	/**
	 *删除一条记录
	 */
	public function deldete($id){
		$res = $this->db->where('id', $id)->delete('cron_doing');
		return $res;
	}

	/** 通过脚本名称获取的脚本信息
	 * @param $cronName 脚本名
	 * @return mixed 脚本的信息
	 */
	public function get_cron_by_name($cronName){
		$cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
		return $cron;
	}

	/** 通过脚本名称删除的脚本信息
	 * @param $cronName 脚本名
	 * @return mixed
	 */
	public function del_cron_by_name($cronName){
		$this->db->delete('cron_doing', array('cron_name' => $cronName));
		return $this->db->affected_rows();
	}

	/** 修改脚本的return_false次数
	 * @param $id 脚本ID
	 * @param $update	要修改的数据
	 * @return mixed
	 */
	public function update_cron_by_name($id,$update){
		$this->db->where('id',$id)->update('cron_doing',$update);
		return $this->db->affected_rows();
	}

	/** 添加正在執行的腳本記錄 */
	public function add_cron_row($cronName){
		$this->db->insert('cron_doing',array(
			'cron_name'=>$cronName
		));
		return $this->db->insert_id();
	}

}
