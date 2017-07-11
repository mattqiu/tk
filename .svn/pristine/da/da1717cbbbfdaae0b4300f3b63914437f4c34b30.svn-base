<?php
/**
 * 日分红合格用户表
 * @author Terry
 */
class tb_daily_bonus_qualified_list extends MY_Model {

    protected $table_name = "daily_bonus_qualified_list";
    protected $table = "daily_bonus_qualified_list";
    function __construct() {
        parent::__construct();
    }

    /**
     * 添加用户到日分红合格表
     */
    public function addToDailyQualiList($uid){
        $this->db->trans_start();
        $res = $this->db->from($this->table)->where(['uid'=>$uid])->get()->row_array();
        if (empty($res) ||$res['amount'] <=0  ) {
            $this->db->query("delete from ".$this->table." where uid= ".$uid);
            $sql = "select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) ) as user_rank  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid=".$uid;
            $result = $this->db->query($sql)->row_array();
            $this->db->query("INSERT  INTO daily_bonus_qualified_list (uid,qualified_day,amount,user_rank) VALUES ($uid,".date('Ymd').",{$result['sale_amount']},{$result['user_rank']})");
        }
        $this->db->trans_complete();

    }
    
    /**
     * 添加用户日分红合格表
     * @param unknown $uid
     * @param unknown $s_time
     */
    public function addToDailyQualiListNew($uid,$s_time)
    {
        $time_s = date('Ym',(strtotime(date('Ym05',strtotime($s_time)))-30*24*3600));
        $exits_sql = "select * from daily_bonus_qualified_list where uid = ".$uid;
        $query_sql = $this->db->query($exits_sql);
        $query_value = $query_sql->row_array();
        if(empty($query_value))
        {
            $this->db->query("delete from ".$this->table." where uid= ".$uid);
            $sql = "select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) ) as user_rank  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`='".$time_s."' and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid=".$uid;
            $result = $this->db->query($sql)->row_array();
            if(!empty($result))
            {
                $this->db->query("INSERT  INTO daily_bonus_qualified_list (uid,qualified_day,amount,user_rank) VALUES ($uid,".date('Ymd').",{$result['sale_amount']},{$result['user_rank']})");
            }
        }        
    }
    
    

    /**
     * 删除上个月合格的日分红用户
     */
    public function delLastMonthQualifUsers(){

        $this->db->query("DELETE FROM daily_bonus_qualified_list where qualified_day<".date('Ymd'));
    }

    /*从合格列表中删除某个用户*/
    public function deleteOneUser($uid){

        $this->db->query("delete from daily_bonus_qualified_list where uid=".$uid);
    }

    /**
     * 获取今天需要发奖的日分红合格人数
     */
    public function getDailySharNum(){

        return $this->db->query("select count(*) totalNum from daily_bonus_qualified_list where qualified_day< ".date('Ymd'))->row_object()->totalNum;
    }

    public function getLikeUid($user_rank,$profit_sharing_point,$uid){

        $profit_sharing_point2 = $profit_sharing_point+150;
        $res = $this->db->query("select a.uid as r_uid from daily_bonus_qualified_list a left join users b on a.uid=b.id where a.qualified_day=0 and b.user_rank=$user_rank 
and b.profit_sharing_point>=$profit_sharing_point and b.profit_sharing_point<$profit_sharing_point2 and a.uid<>$uid order by rand() limit 1")->row_array();
        return $res?$res['r_uid']:'';

    }

    /**
     * 统计今天日分红人员的总分红点（含奖励分红点）
     */
    public function statDailySharTotalPoint(){

        $curDay = date('Ymd');
        $res = $this->db->query("select sum(profit_sharing_point) total_point from daily_bonus_qualified_list a left join 
users b on a.uid=b.id where a.qualified_day<'".$curDay."'")->row_array();

        $resReward = $this->db->query("select sum(point) total_point from daily_bonus_qualified_list a left join 
users_sharing_point_reward b on a.uid=b.uid where a.qualified_day<'".$curDay."' and b.end_time>'".date('Y-m-d')."'")->row_array();
        return tps_money_format($res['total_point']+$resReward['total_point']);
    }

    /*分页获取今天发奖的日分红人员*/
    public function getTodayDaiyUserListByPage($page,$page_size){
        $this->load->model("tb_debug_logs");
        $res = $this->get([
            'select'=>'daily_bonus_qualified_list.uid,daily_bonus_qualified_list.user_rank,daily_bonus_qualified_list.amount',
            'join'=>[
                [
                    'table'=>'users',
                    'where'=>'daily_bonus_qualified_list.uid = users.id',
                    'type'=>"inner"
                ],
                [
                    'table'=>'users_store_sale_info',
                    'where'=>'daily_bonus_qualified_list.uid = users_store_sale_info.uid',
                    'type'=>"inner"
                ],
            ],
            'where'=>[
                'users.status'=>'1'
            ],
            'limit'=>[
                'page'=>$page,
                'page_size'=>$page_size
            ]
        ],false,false,true);
        
       $this->tb_debug_logs->add_logs(['content'=>$this->db->last_query()]);
        return $res;

    }


    /**
     * ＠ａｕｔｈｏｒ brady
     * @return mixed
     */
    public function getTodayDaiyUserList()
    {
        $this->load->model("tb_debug_logs");
        $res = $this->get([
            'select'=>'daily_bonus_qualified_list.uid,users.profit_sharing_point',
            'join'=>[
                [
                    'table'=>'users',
                    'where'=>'daily_bonus_qualified_list.uid = users.id',
                    'type'=>"inner"
                ],
                [
                    'table'=>'users_store_sale_info',
                    'where'=>'daily_bonus_qualified_list.uid = users_store_sale_info.uid',
                    'type'=>"inner"
                ],

            ],
            'where'=>[
                'users.status'=>'1'
            ],

        ],true,false,true);
        $this->tb_debug_logs->add_logs(['content'=>'获取满足日分红sql:'.$this->db->last_query()]);
        $bad_users = $this->get([
            'select'=>'daily_bonus_qualified_list.uid,users.profit_sharing_point',
            'join'=>[
                [
                    'table'=>'users',
                    'where'=>'daily_bonus_qualified_list.uid = users.id',
                    'type'=>"inner"
                ]
            ],
            'where'=>[
                'daily_bonus_qualified_list.qualified_day <'=>date("Ymd"),
                'users.status !='=>'1'
            ],

        ],false,false,true);
        if (!empty($bad_users)) {
            $bad_users_arr = [];
            foreach($bad_users as $v) {
                $bad_users_arr[] = $v['uid'];
            }
            $this->m_log->createCronLog('[false] '.date("Y-m-d H:i:s").'全球日分红奖因用户状态不正常而未发的用户 '.json_encode($bad_users_arr));
        }
        return $res;
    }


    public function getTotalSale()
    {
        return     $this->db->query("select sum(amount) as total from daily_bonus_qualified_list ")->row_object()->total;

    }

    public function get_one($uid)
    {
        return $res = $this->db->from($this->table)->select("uid,amount,user_rank")->where(['uid'=>$uid])->get()->row_array();
    }

    public function get_total_rank()
    {
        $total = $this->db->query("SELECT sum(user_rank) as total from daily_bonus_qualified_list  ")->row_object()->total;
        return $total;
    }


}
