<?php

/** record tickets daily count
 * @author andy
 */
class tb_admin_tickets_daily_count extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    /**
     * @param $data
     * @author andy
     * @return mixed
     */
    public function batch_add_count($data){
        return $this->db->insert_batch('admin_tickets_daily_count', $data);
    }

    public function get_daily_count_list($searchData){
        $this->db->from('admin_tickets_daily_count as c');
        $this->db->select('c.admin_id,u.email,u.job_number,SUM(d_in) as d_in,SUM(d_out) as d_out,SUM(d_count) as d_count');
        $this->db->join('admin_users as u','c.admin_id=u.id','left');
        $this->filter_func($searchData);
        $this->db->group_by('c.admin_id');
        return $this->db->get()->result_array();
    }

    private function filter_func($searchData){
        foreach($searchData as $k=>$v){
            if($v==='' || $k=='page'){
                continue;
            }
            switch($k){
                case 'cus_id':{
                    $this->db->where('c.admin_id',$v);
                    break;
                }
                case 'start':{
                    $this->db->where('c.count_time >=',$v);
                    break;
                }
                case 'end':{
                    $this->db->where('c.count_time <=',$v);
                    break;
                }
                case 'date':{
                    $this->db->where('c.count_time',$v);
                    break;
                }
                default:{
                    break;
                }
            }
        }
    }

    public function input_daily_statistics_data(){
        $date = date('Y-m-d',strtotime("-1 day"));
        $this->load->model('tb_admin_tickets');
        $data = $this->tb_admin_tickets->get_tickets_statistics('','',$date);
        if(!empty($data)){
            $input_data = array();
            foreach($data as $item){
                $temp_data = array(
                            'admin_id'  =>$item['admin_id'],
                            'd_in'      =>$item['today_in'],
                            'd_out'     =>$item['today_out'],
                            'd_count'   =>$item['today_assign'],
                            'count_time'=>$date,
                );
                array_push($input_data,$temp_data);
            }
            $this->batch_add_count($input_data);
        }
    }
}