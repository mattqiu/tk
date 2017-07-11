<?php

/**
 * @author andy
 */

class tb_admin_ads_file_manage extends MY_Model{

    protected $table_name = "admin_ads_file_manage";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加一条记录
     * @author andy
     */
    public function addOneFile($insertDataArr){

        $this->db->insert('admin_ads_file_manage',$insertDataArr);
        return $this->db->insert_id();

    }

    /**
     * 获取一条详细信息
     * @author andy
     */
    public function getOneFile($id){

        $select      = 'id,admin_id,file_type,file_area,file_name,file_real_name,dir_name,file_path,file_extension,is_show,create_time';
        $where['id'] = $id;

        return $this->get_one($select,$where);
    }


    /**
     * 更新一条记录
     * @author andy
     */
    public function updateOneFile($id,$updateDataArr){

        $this->db->where('id',$id);

        $this->db->update('admin_ads_file_manage',$updateDataArr);

        return $this->db->affected_rows();

    }


    /** 获取列表
     * @param $filter
     * @param $perPage
     * @author andy
     */
    public function getFileList($filter,$perPage=10){

        $this->db->from('admin_ads_file_manage');

        $this->db->select('id,admin_id,file_type,file_area,file_name,file_real_name,dir_name,file_path,file_extension,is_show,create_time');

        $this->filter($filter);

        return $this->db->order_by('create_time','DESC')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();

    }

    /**获取总数
     * @param $filter查询条件
     * @author andy
     */
    public function getFileListCount($filter){

        $this->db->from('admin_ads_file_manage');

        $this->filter($filter);

        return $this->db->count_all_results();

    }


    /** 适配
     * @param $filter
     * @author andy
     */
    private function filter($filter){

        $this->db->where('status',1);

        foreach($filter as $k=>$v){

            if($v==='' or $k=='page')
            {
                continue;
            }

            switch($k)
            {
                case 'file_type':{
                    $this->db->where('file_type',$v);
                    break;
                }

                case 'file_area':{
                    $this->db->where('file_area',$v);
                    break;
                }

                case 'file_name':{
                    $this->db->like('file_name',$v);
                    break;
                }

                case 'is_show':{
                    $this->db->where('is_show',$v);
                    break;
                }

                case 'start_time':{
                    $this->db->where('create_time >=',$v);
                    break;
                }

                case 'end_time':{
                    $this->db->where('create_time <=',date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                }

                default: {
                    break;
                }
            }

        }
    }


    /** 添加流水
     * @param $data
     * @return mixed
     * @author andy
     */
    public function add_file_log($data){
        $this->db->insert('admin_ads_file_manage_log',$data);
        return $this->db->insert_id();
    }

}