<?php
/**
 * 月杰出店铺奖预览
 * @author carl.zhou
 */
class tb_month_eminent_store_preview extends CI_Model {
    //全部字段
    private $fullFields = [
        'id',               //自增主键
        'uid',              //用户id
        'date',             //创建日期
        'amount',           //应得奖金
    ];

    protected $table_name = 'month_eminent_store_preview';
    protected $primary_key = 'id';
    protected $bonus_type = 8;  //奖金类型

    function __construct() {
        parent::__construct();
    }

    /**
     * 写入预览数据
     * @param array $data
     * @return bool
     */
    public function createMonthEminentStorePreview($data){
        $this->checkParam($data, ['uid', 'date', 'child_reward', 'percent', 'due_amount']);
        $res = $this->db->insert($this->table_name, $data);
        return $res ? true : false;
    }

    /**
     * 批量写入预览数据
     * @param array $dataList
     * @return bool
     */
    public function createMonthEminentStorePreviewBatch($dataList){
        $res = $this->db->insert_batch($this->table_name, $dataList);
        return $res ? true : false;
    }

    /**
     * 批量删除数据
     * @param $idList
     * @return bool
     */
    public function deleteMonthEminentStorePreviewByUid($idList){
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
    public function getMonthEminentStorePreview($id){
        $res = $this->getDbPrepare(['id'=>$id])->get()->row_array();
        return $res;
    }

    /**
     * 获取预览列表
     * @param array $where 检索条件
     * @param int $num  数据条数
     * @param bool $master 是否强制使用主库
     * @return array
     */
    public function getMonthEminentStorePreviewList($where, $num = 10, $master = false){
        $start = ($where['page'] - 1) * $num;
        $db = $this->getDbPrepare($where, $master);
        $res = $db->limit($num, $start)->get()->result_array();
        return $res;
    }

    /**
     * 获取预览数据数量
     * @param array $where
     * @param bool $master 是否强制使用主库
     * @return mixed
     */
    public function getMonthEminentStorePreviewRows($where, $master = false) {
        $db = $this->getDbPrepare($where, $master);
        return $db->count_all_results();
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
        if ($master === true) {
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

    public function previewMonthEminentStoreReward($num){
        $this->load->model('o_company_money_today_total');
        $this->load->model('td_system_rebate_conf');

        $conf = $this->td_system_rebate_conf->getBonusData($this->bonus_type);
        if (empty($conf[0]) || !is_array($conf[0])) {
            return false;
        }
        $conf = $conf[0];

        $bonus_percent = $conf['rate_a'] > 0 ? floatval($conf['rate_a']) : 0;
        $average_percent = $conf['rate_b'] > 0 ? floatval($conf['rate_b']) : 0;

        if ($bonus_percent==0 || $average_percent==0) {
            return false;
        }

//        $bonus_percent = 0.0707;
//        $average_percent = 0.2;

        //上月总利润详情
        $total_profit_info = $this->o_company_money_today_total->get_last_month_profit();
        //上月总利润
        $total_reward = $total_profit_info['money'];

        $sql = 'select count(uid) as total_num, sum(sharing_point) as total_point from month_sharing_members';
        $point_result = $this->db->query($sql)->row_array();
        //参与分红总人数
        $total_num = $point_result['total_num'];
        //参与分红的总分红点
        $total_point = $point_result['total_point'];

        //总利润的一部分拿出来作为奖金
        $total_bonus = $total_reward * $bonus_percent;

        //所有人平均分享总奖金的20%
        $average_bonus = $total_bonus * $average_percent / $total_num;

        //总奖金的剩余部分根据个人分红点分配
        $last_reward = $total_bonus * (1 - $average_percent);

        echo "\n-----------------------------------\n";
        echo "Total Profit : ". $total_reward . "\n";
        echo "Bonus Percent : ". $bonus_percent . "\n";
        echo "Average Percent : ". $average_percent . "\n";

        echo "Total Sharing Point : ". $total_point . "\n";
        echo "Total Member : ". $total_num . "\n";
        echo "Total Sharing Point : ". $total_point . "\n";

        echo "Average Bonus : ". $average_bonus . "\n";
        echo "Total Sharing Bonus : ". $last_reward . "\n";
        echo "-----------------------------------\n";

        $start = 0;

        while ($res = $this->db->query("select uid,sharing_point from month_sharing_members limit ".$start.",".$num)->result_array()) {
            $temp = [];
            foreach ($res as $item) {
                //每个人根据自己的分红点获得的分红
                if ($item['sharing_point']>0) {
                    $personal_bonus = $last_reward * $item['sharing_point'] / $total_point;
                } else {
                    $personal_bonus = 0;
                }
                $bonus = round($average_bonus + $personal_bonus);
                if ($bonus<0) {
                    $bonus = 0;
                }
                $temp[] = sprintf("(%s,%s)", $item['uid'], $bonus);
            }
            $sql = "insert into ".$this->table_name."(uid,amount) values".implode(',',$temp);
            $this->db->query($sql);
            $rows = $this->db->affected_rows();
            if ($rows==0) {
                echo "insert into ".$this->table_name." error\n";
                break;
            } else {
                echo "create preview ".$rows."\n";
            }
            $start += $num;
        }
    }

    /**
     * 发放月杰出店铺奖
     * @param int $num 一次发放的数量
     * @param int $debug 1调试模式只循环一次
     */
    public function monthEminentStoreRewardImplement($num = 10, $debug){
        $this->load->model('o_bonus');

        $max = 0;
        $where = [
            'page' => 1,
            'uid >' => $max,
            'order by' => ['uid'=>'asc'],
            'fields' => ['uid', 'amount as money'],
        ];

        $count = 0;
        while($res = $this->getMonthEminentStorePreviewList($where, $num, true)) {
            echo $count++.".";
            $result = $this->o_bonus->assign_bonus_batch($res, $this->bonus_type);
            //echo " status: ".var_dump($result)." ";
            if ($result===TRUE) {
                $uidList = [];
                foreach ($res as $item) {
                    $uidList[] = $item['uid'];
                    $max = $item['uid'];
                }
                $rows = $this->deleteMonthEminentStorePreviewByUid($uidList);
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
