<?php
/**工单统计
 * @create time 2016.8.22
 * @author andy
 */
class tb_admin_tickets_count extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    /**
     * @param $count_arr
     * @author andy
     * @return mixed
     */
    public function add_customer($count_arr)
    {
        $this->db->insert('admin_tickets_count', $count_arr);
        return $this->db->insert_id();
    }

    public function check_today_is_exist($cus_id){
        $this->db->from('admin_tickets_count');
        $this->db->where('admin_id',$cus_id);
        $this->db->where('create_time',date('Y-m-d'));
        $num = $this->db->count_all_results();
        if($num){
            return ;
        }else{
            $arr = array(
                'admin_id' => $cus_id,
                'count'     =>0,
                'create_time'=>date('Y-m-d'),
            );
            $this->add_customer($arr);
        }
        return;
    }

    /*
     * 某个客服更新总数,今天没有记录则加入记录
     */
    public function update_count_by_cid($cid,$count){
        $this->check_today_is_exist($cid);
        if($count>0){//加入
            $this->db->set('count',"count + $count",FALSE);
            $this->db->where('admin_id',$cid);
            $this->db->where('create_time',date('Y-m-d'));
            $this->db->update('admin_tickets_count');
            return $this->db->affected_rows();
        }else{//移出
            $date = $this->check_exist_tickets_date_by_cid($cid);
            if($date){
                $this->db->set('count',"count + $count",FALSE);
                $this->db->where('admin_id',$cid);
                $this->db->where('create_time',$date);
                $this->db->update('admin_tickets_count');
                return $this->db->affected_rows();
            }else{
                return 0;
            }
        }
    }

    //查找有记录的日期
    public function check_exist_tickets_date_by_cid($cid,$num=0){
        $this->db->from('admin_tickets_count');
        $this->db->where('admin_id',$cid);
        $this->db->where('create_time',date("Y-m-d",strtotime("$num day")));
        $count_arr = $this->db->get()->row_array();
        if(!empty($count_arr) && $count_arr['count']>0){
            return $count_arr['create_time'];
        }
        if(date("Y-m-d",strtotime("$num day"))=='2016-09-01'){//直接跳出
            return 0;
        }
        --$num;
        return  $this->check_exist_tickets_date_by_cid($cid,$num);
    }

    public function batch_update_count($update_arr){
        if(!empty($update_arr)){
            foreach($update_arr as $k=>$u){
                $this->update_count_by_cid($k,$u);
            }
        }
    }

    //在昨天的记录中减去今天的批量转移的数量
    public function sub_count($cus_id,$count){
        $this->db->from('admin_tickets_count');
        $this->db->where('admin_id',$cus_id);
        $this->db->where('create_time',date('Y-m-d',strtotime("-1 day")));
        $num = $this->db->count_all_results();
        if($num){
            $this->db->set('count',"count + $count",FALSE);
            $this->db->where('admin_id',$cus_id);
            $this->db->where('create_time',date('Y-m-d',strtotime("-1 day")));
            $this->db->update('admin_tickets_count');
            return $this->db->affected_rows();
        }else{
            $arr = array(
                'admin_id'     => $cus_id,
                'count'        =>$count,
                'create_time'  =>date('Y-m-d',strtotime("-1 day")),
            );
            return $this->add_customer($arr);
        }
    }
    /* 获取某个客服的名下的总数
     * $cus_id 客服id
     */
    public function get_someone_count($cus_id){
        $this->db->from('admin_tickets_count as t');
        $this->db->join('admin_users as u','t.admin_id=u.id','left');
        $this->db->select('t.count,u.id,u.email');
        $this->db->where('admin_id',$cus_id);
        $count =  $this->db->get()->result_array();
        if($count){
            $num = 0;
            foreach($count as $c){
                $num +=$c['count'];
            }
            return array('num'=>$num,'admin_id'=>$count[0]['id'],'admin_email'=>$count[0]['email']);
        }
        return false;
    }

    /*
     * 获取某个客服某天的工单数
     */
    public function get_someone_count_by_date($cus_id,$date){
        $this->db->from('admin_tickets_count as t');
        $this->db->join('admin_users as u','t.admin_id=u.id','left');
        $this->db->select('t.count,u.id as admin_id,u.email as admin_email');
        $this->db->where('admin_id',$cus_id);
        $this->db->where('create_time',$date);
        return $this->db->get()->result_array();
    }

    /*
     * 获取全部客服的总数
     */
    public function get_each_customer_count(){
        $this->db->from('admin_tickets_count as t');
        $this->db->join('admin_users as u','t.admin_id=u.id','left');
        $this->db->select('SUM(t.count) AS count,u.id,u.email');
        $this->db->group_by('admin_id');
        return $this->db->get()->result_array();
    }

    /*
     * 获取某天每个客服分到的数量
     */
    public function get_each_count_by_date($date){
        $this->db->from('admin_tickets_count as t');
        $this->db->join('admin_users as u','t.admin_id=u.id','left');
        $this->db->select('t.count,u.id,u.email');
        $this->db->where('t.create_time',$date);
        return $this->db->get()->result_array();
    }


    //修复数据------->加到前一天
    public function repair(){
        $this->load->model('m_admin_user');
        $cus = $this->m_admin_user->get_customers();
        if($cus){
            foreach($cus as $c){
                $this->db->from('admin_tickets');
                $all =  $this->db->where('admin_id',$c['id'])->where('create_time <',date('Y-m-d'))->count_all_results();
                if($all){
                    $arr = array(
                        'admin_id' => $c['id'],
                        'count'     =>$all,
                        'create_time'=>date('Y-m-d',strtotime("-1 day")),
                    );
                    $this->add_customer($arr);
                }
            }
        }

    }
}