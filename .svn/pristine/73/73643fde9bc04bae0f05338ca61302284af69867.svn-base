<?php
/**
 * @author Terry
 */
class tb_month_fee_change extends MY_Model {

    protected $table = "month_fee_change";
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 查询表
     * @return boolean
     * @author Terry
     */
    public function alllist($filter, $perPage = 10) {
        $this->db->from('month_fee_change');
        $this->filterForNews($filter);
        $list = $this->db->order_by("create_time", "desc")->where('type','5')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        $adminlist = $this->db->from('admin_users')->get()->result_array();
        foreach($list as $k=>$adminid){
            foreach($adminlist as $email){
                if($adminid['admin_id'] == $email['id']){
                    $list[$k]['admin_id'] = $email['email'];
                }
            }
        }
       return $list;
    }
    /**
     * 获取总数
     */
    public function getMonthLogListRows($filter) {
        $this->db->from('month_fee_change');
        $this->filterForNews($filter);
        return $this->db->where('type','5')->count_all_results();
    }


    /**
     *
     */
    public function filterForNews($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    /* 获取月费明细 */
    public function get_monthly_fee_logs($uid,$time){
        $data = array();
        $res = $this->db->query("select cash,coupon_num_change,type,DATE_FORMAT(create_time,'%Y-%m-%d')as create_time from month_fee_change where user_id = $uid and create_time like '$time%' order by create_time DESC ")->result_array();
        $map = array(
            1=>0,       //充值
            2=>0,       //现金池转月费池
            3=>0,       //月费券
            4=>0,       //扣月费
            5=>0,       //月费转现金池
            6=>0,       //月费抽回
            7=>0        //特殊款项处理
        );
        foreach($res as $item){
            if(!isset($data[$item['create_time']])){
                $data[$item['create_time']] = $map;
                if($item['type'] == 3){
                    $data[$item['create_time']][$item['type']] += $item['coupon_num_change'];
                }else{
                    //交月费券交月费的
                    if($item['coupon_num_change'] < 0){
                        $data[$item['create_time']][$item['type']] = $item['coupon_num_change'].'m';
                    }else{
                        $data[$item['create_time']][$item['type']] += $item['cash'];
                    }
                }
            } else{
                $data[$item['create_time']][$item['type']] += $item['cash'];
            }
        }

        //剔除为0的数据
        foreach($data as $k =>$v){
            foreach($data[$k] as $k1 => $v2)
            {
                if($data[$k][$k1] == 0){
                    unset($data[$k][$k1]);
                }
            }
        }

        $new_data = array();

        //更换成mobile数组格式
        foreach($data as $k =>$v){

            $array = array('time'=>'', 'list'=>array());
            $array['time'] = $k;

            foreach($v as $k1 =>$v1){
                $new_array = array();
                $new_array['type'] = $k1;
                $new_array['money'] = $v1;

                $array['list'][] = $new_array;
            }

            $new_data[] = $array;
        }

        return $new_data;
    }
}
