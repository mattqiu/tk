<?php
/**
 * 单任务机制控制表
 * @author Terry
 */
class tb_single_task_control extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
	 * 新增任务
     */
    public function addTask($task_name){

    	return $this->db->insert('single_task_control',array(
			'task_name'=>$task_name
		));
    }
    
    /**
	 * 根据任务名获取任务
	 * @author Terry
     */
    public function getTask($task_name){

    	return $this->db->from('single_task_control')->where('task_name',$task_name)->get()->row_array();
    }

    /**
	 * 删除任务
	 * @author Terry
     */
    public function deleteTask($task_name){

    	return $this->db->where('task_name',$task_name)->delete('single_task_control');
    }

    /**
	 * 增加任务的运行周期数
     */
    public function addTaskCycelNum($task_name){

		$this->db->where('task_name', $task_name)->set('run_cycle_num', 'run_cycle_num+1', FALSE)->update('single_task_control');
	}


}
