<?php
/**
 * @author carl.zhou
 */
class tb_week_leader_preview extends CI_Model {
    //全部字段
    private $fullFields = [
        'id',               //自增主键
        'uid',              //用户id
        'name',             //用户名称
        'date',             //发奖日期，值为发奖周周一的日期
        'child_reward',     //下（两）级上周总奖金
        'current_amount',   //用户当前周对等奖金总额
        'due_amount',       //本周应得奖金
        'percent',          //周领导对等奖比例
        'status',           //周领导对等奖发放状态 0未审核 1已审核 2已发放
        'pay_time',         //周领导对等奖发放时间 脚本发放奖金后写入数据库
    ];

    protected $table_name = 'week_leader_preview';
    protected $primary_key = 'id';

    public $item_type = [1,2,3,4,5,6,7,8,16,23,24,25,26,27];
    public $percent = 5;

    function __construct() {
        parent::__construct();
    }

    /**
     * 写入周领导对等奖预览数据
     * @param array $data
     * @return bool
     */
    public function createWeekLeaderPreview($data){
        $this->checkParam($data, ['uid', 'date', 'child_reward', 'percent', 'due_amount']);
        $res = $this->db->insert($this->table_name, $data);
        return $res ? true : false;
    }

    /**
     * 批量写入周领导对等奖预览数据
     * @param array $dataList
     * @return bool
     */
    public function createWeekLeaderPreviewBatch($dataList){
        $res = $this->db->insert_batch($this->table_name, $dataList);
        return $res ? true : false;
    }

    /**
     * 批量删除数据
     * @param $idList
     * @return bool
     */
    public function deleteWeekLeaderPreviewByUid($idList){
        if (empty($idList)) {
            return false;
        }
        $sql = "delete from ".$this->table_name." where uid in (".implode(',', $idList).")";
        $this->db->query($sql);
        $rows = $this->db->affected_rows();
        return $rows;
    }

    /***
     * 清空表数据
     */
    public function truncateTable(){
        $this->db->query('truncate '.$this->table_name);
    }

    /**
     * 通过id获取预览数据
     * @param $id
     * @return mixed
     */
    public function getWeekLeaderPreview($id){
        $res = $this->getDbPrepare(['id'=>$id])->get()->row_array();
        $this->initData($res);
        return $res;
    }

    /**
     * 获取预览列表
     * @param array $where 检索条件
     * @param int $num  数据条数
     * @param bool $master 是否强制使用主库
     * @return array
     */
    public function getWeekLeaderPreviewList($where, $num = 10, $master = false){
        $start = ($where['page'] - 1) * $num;
        $db = $this->getDbPrepare($where, $master);
        $res = $db->limit($num, $start)->get()->result_array();
        $this->initData($res);
        return $res;
    }

    /**
     * 获取预览数据数量
     * @param $where
     * @param bool $master 是否强制使用主库
     * @return mixed
     */
    public function getWeekLeaderPreviewRows($where, $master = false) {
        $db = $this->getDbPrepare($where, $master);
        return $db->count_all_results();
    }

    /**
     * 初始化出库数据
     * @param $data
     */
    private function initData(&$data){
        if (!is_array($data)) {
            return;
        }
        foreach ($data as $key=>&$val) {
            if (is_array($val)) {
                $this->initData($val);
            } elseif (is_scalar($val)) {
                switch ($key) {
                    case 'percent' :
                        $data['percent_show'] = ($val / 100) . '%';
                        break;
                }
            }
        }
    }

    /**
     * 获取数据库实例
     * @param array $where
     * @param bool $master 是否使用主库 true 是 false 否
     * @return mixed
     */
    private function getDbPrepare($where = [], $master = false){
        $db = $this->db->from($this->table_name);

        if (is_array($where)) {
            if (isset($where['page'])) {
                unset($where['page']);
            }
            foreach($where as $key=>$val){
                switch ($key) {
                    case 'fields':
                        $db->select($val);
                        break;
                    case 'order by':
                        if (is_array($val) && !empty($val)) {
                            foreach($val as $k=>$v) {
                                if (in_array($k, $this->fullFields)) {
                                    $db->order_by($k,$v);
                                }
                                break;
                            }
                        }
                        break;
                    default:
                        $db->where($key,$val);
                        break;
                }

            }
        }
        if ($master) {
            $db->force_master();
        }
        return $db;
    }

    /**
     * 检查参数
     * @param array $data 待检查的数据
     * @param array $necessary 必须且不为空的字段
     * @param array $valid  合法字段
     * @throws Exception
     */
    private function checkParam(&$data, $necessary = [], $valid = []){
        if (!is_array($data) || empty($data) || !is_array($necessary) || !is_array($valid)) {
            throw new Exception('param_type_error');
        }

        //检查必须数据
        foreach ($necessary as $key=>$val) {
            if (empty($data[$key])) {
                throw new Exception($key.'_should_not_empty');
            }
        }

        //过滤非法字段
        $validFields = empty($valid) ? $this->fullFields : $valid;
        foreach ($data as $key=>$val) {
            if (!array_key_exists($key, $validFields)) {
                unset($data[$key]);
            }
        }
    }



    /**
     * 周领导对等奖预发放
     * @param $date
     * @param $num
     * @author carl.zhou
     */
    public function previewWeekLeaderReward($date, $num){
        $this->load->model('m_user');
        $this->load->model('tb_cash_account_log_x');

        $percent = $this->percent;
        $item_type = $this->item_type;
        $min_uid = 0;
        $previewList = [];

        //循环获取需要发放奖金的用户id
        while($res = $this->getWeekLeaderInfo($min_uid, $num)){
            foreach ($res as $item) {
                $preview = array();
                $uid = $item['uid'];
                $min_uid = $uid;
                //获取下级用户及其上周总奖金
                $children = $this->m_user->getChildMemberForLevelTwo($uid);
                if (empty($children)) {
                    $weekReward = 0;
                } else {
                    $weekReward = $this->tb_cash_account_log_x->getWeekSumAmount($children, $item_type, $date);
                }

                $preview['uid'] = $uid;
                $preview['name'] = $item['name'] == NULL ? '' : $item['name'];
                $preview['current_amount'] = $item['amount_weekly_Leader_comm'] == NULL ? 0 : $item['amount_weekly_Leader_comm'] * 100;
                $preview['date'] = strtotime($date);
                $preview['child_reward'] = $weekReward;
                $preview['percent'] = $percent * 100;
                $preview['due_amount'] = round($preview['child_reward'] * ($preview['percent'] / 10000));
                if ($preview['due_amount']<0) {
                    $preview['due_amount'] = 0;
                }
                $previewList[] = $preview;
            }
            //存储预览数据到预览表
            $res = FALSE;
            for ($i=0; $i<3; $i++) {
                $res = $this->createWeekLeaderPreviewBatch($previewList);
                if ($res === TRUE) {
                    break;
                }
            }
            if ($res === FALSE) {
                $this->m_log->createCronLog('周领导对等奖预览程序 [执行失败]');
            }
            $previewList = [];
        }
        $this->m_log->createCronLog('周领导对等奖预览程序 [执行成功]');
        return;
    }

    /**
     * 获取周领导对等奖领导信息
     * @param $start_uid
     * @param $num
     * @return mixed
     */
    public function getWeekLeaderInfo($start_uid, $num){
        $sql = 'select a.uid, b.name, b.amount_weekly_Leader_comm from week_leader_members a ';
        $sql .= 'left join users b on a.uid=b.id where a.uid>'.$start_uid.' and b.status=1 order by uid ASC limit '.$num;
        $res = $this->db->query($sql)->result_array();
        return $res;
    }

    /**
     * 周领导对等奖发奖
     * @param $num 一次下发的个数
     * @param int $debug 是否调试模式，调试模式下只发一次 1是 0否
     */
    public function weekLeaderRewardImplement($num, $debug){
//        $res = $this->db->force_master()->query('select count(*) as total from week_leader_members a left join users b on a.uid=b.id where b.status=1')->row_array();
//        $total = $res['total'];
//        $previewNum = $this->getWeekLeaderPreviewRows([], true);
//        if ($total != $previewNum) {
//            $this->m_log->createCronLog('周领导对等奖发奖程序 [执行失败，预览程序未完成]');
//            return;
//        }

        $this->load->model('o_bonus');

        $max = 0;
        $where = [
            'page' => 1,
            'uid >' => $max,
            'order by' => ['uid'=>'asc'],
            'fields' => ['uid', 'due_amount as money'],
        ];

        $count = 0;
        while($res = $this->getWeekLeaderPreviewList($where, $num, true)) {
            echo $count++.".";
            $result = $this->o_bonus->assign_bonus_batch($res, 7);
            echo " status: ".var_dump($result)." ";
            if ($result===TRUE) {
                $uidList = [];
                foreach ($res as $item) {
                    $uidList[] = $item['uid'];
                    $max = $item['uid'];
                }
                $rows = $this->deleteWeekLeaderPreviewByUid($uidList);
                if ($rows <= 0) {
                    echo "delete preview data error!\n";
                    return;
                }
                echo "del ".$rows." rows\n";
            } else {
                echo "assign_bonus_batch error!";
                return;
            }
            $where['uid >'] = $max;

            if ($debug) {
                break;
            }
        }
    }

}
