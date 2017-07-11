<?php
/**
 * 供应商推荐奖预览
 * @author carl.zhou
 */
class tb_month_expense_preview extends CI_Model {
    //全部字段
    private $fullFields = [
        'id',               //自增主键
        'uid',              //用户id
        'date',             //创建日期
        'amount',           //应收费用 美分
    ];

    protected $table_name = 'month_expense_preview';
    protected $primary_key = 'id';
    protected $bonus_type = 28;  //费用类型 收取用户月费

    function __construct() {
        parent::__construct();
    }

    /**
     * 写入预览数据
     * @param array $data
     * @return bool
     */
    public function createMonthExpensePreview($data){
        $this->checkParam($data, ['uid', 'date', 'amount']);
        $res = $this->db->insert($this->table_name, $data);
        return $res ? true : false;
    }

    /**
     * 批量写入预览数据
     * @param array $dataList
     * @return bool
     */
    public function createMonthExpensePreviewBatch($dataList){
        $res = $this->db->insert_batch($this->table_name, $dataList);
        return $res ? true : false;
    }

    /**
     * 批量删除数据
     * @param $idList
     * @return bool
     */
    public function deleteMonthExpensePreviewByUid($idList){
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
    public function getMonthExpensePreview($id){
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
    public function getMonthExpensePreviewList($where, $num = 10, $master = false){
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
    public function getMonthExpensePreviewRows($where, $master = false) {
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



    public function getAmountSumGroupByUid($search, $table, $start = 0, $num = 10){
        $sql = "select uid, sum(amount) as total from ".$table;
        $sql .= " where uid>".$start;
        $sql .= " and item_type in (".implode(',', $search['item_type']).")";
        $sql .= " group by uid order by uid";
        $sql .= " limit ".$num;
        $res = $this->db->query($sql)->result_array();
        return $res;
    }

    public function getUserCoupon(){
        $sql = "update ".$this->table_name." a set a.coupon_id = ";
        $sql .= "ifnull((select id from monthly_fee_coupon b where a.uid=b.uid order by b.create_time limit 1),0)";
        $res = $this->db->query($sql);
        return $res;
    }

    public function previewMonthExpense($start_time, $num){
        $this->load->config('config_base');
        $config = $this->config->config['commission_type'];
        $item_type = array_keys($config);

        $this->load->model('tb_cash_account_log_x');


        $year_month = date('Ym', strtotime($start_time));
        $search = [
            'start_time' => date('Y-m-01 00:00:00', strtotime($start_time)),
            'end_time' => date('Y-m-01 00:00:00', strtotime('+1 month', strtotime($start_time))),
            'item_type' => $item_type,
        ];

        //是否分表取数据
        if ($year_month < config_item("cash_account_log_cut_table_end_1")) {
            $table = "cash_account_log_".$year_month;
            $times = 0;
            $spl = false;
        } else {
            $sql = "select max(id) as max_id from users limit 1";
            $res = $this->db->query($sql)->row_array();
            $max_id = $res['max_id'];
            $times = substr($max_id, 0, 4) - 1380;
            $spl = true;
        }

        for($count=0; $count<=$times; $count++) {
            $start = 0;
            if ($spl){
                $table = 'cash_account_log_'.$year_month.'_'.(1380 + $count);
            }
            while (1) {
                $res = $this->getAmountSumGroupByUid($search, $table, $start, $num);
                if (empty($res)) {
                    break;
                }

                $values = '';
                foreach ($res as $item) {
                    $uid = $item['uid'];
                    $amount = intval($item['total']);
                    $start = $uid;
                    if ($amount <= 0) {
                        $expense = 0;
                    } elseif ($amount <= 50000) { //0-500（美元 下同） 0.5%
                        $expense = $amount * 0.005;
                    } elseif ($amount <= 100000) { //500-1000 1%
                        $expense = $amount * 0.01;
                    } elseif ($amount <= 500000) { //1000-5000 2%
                        $expense = $amount * 0.02;
                    } else {    //5000以上3%
                        $expense = $amount * 0.03;
                    }
                    $expense = intval($expense);
                    if ($expense > 20000) { //超过200收200
                        $expense = 20000;
                    }
                    if ($expense == 0) {
                        continue;
                    }
                    $values .= sprintf(",(%d,%d)", $uid, $expense);
                }
                $values = substr($values, 1);

                if (!empty($values)) {
                    $sql = "insert into " . $this->table_name . "(uid,amount) values" . $values;
                    $this->db->query($sql);
                    $rows = $this->db->affected_rows();
                    if ($rows == 0) {
                        echo "insert into " . $this->table_name . " error\n";
                    } else {
                        echo "create preview " . $rows . "\n";
                    }
                } else {
                    break;
                }
            }
        }
        if (!$this->getUserCoupon()) {
            echo "get user coupons error\n";
            return false;
        }
        return true;
    }

    public function getMonthExpensePreviewArray($max=0, $num=10) {
        $sql = "select a.id, a.uid, a.amount, a.coupon_id, b.amount as money from ".$this->table_name ." a";
        $sql .= " left join users b on a.uid=b.id where a.uid>". $max;
        $sql .= " order by a.uid asc limit ".$num;
        $res = $this->db->force_master()->query($sql)->result_array();
        return $res;
    }

    /**
     * 收取会员月费
     * @param int $num 一次收取的数量
     * @param int $debug 1调试模式只循环一次
     */
    public function monthExpenseImplement($num, $debug){
        $this->load->model('o_bonus');

        $count = 0;
        $max = 0;

        while($res = $this->getMonthExpensePreviewArray($max, $num)) {
            echo $count++.".";
            $preview_array = array();
            foreach ($res as $item) {
                $pre = substr($item['uid'], 0, 4);
                $preview_array[$pre][] = $item;
            }

            $result = TRUE;
            foreach ($preview_array as $pre=>$preview) {
                $bool = $this->monthExpenseCharge($preview,$pre);
                $result = $result && $bool;
            }

            echo " status: ".var_dump($result)." ";
            if ($result===TRUE) {
                $uidList = [];
                foreach ($res as $item) {
                    $uidList[] = $item['uid'];
                    $max = $item['id'];
                }
                $rows = $this->deleteMonthExpensePreviewByUid($uidList);
                if ($rows <= 0) {
                    echo "delete preview data error!\n";
                    return;
                }
                echo "del ".$rows." rows\n";
            } else {
                echo "assign_bonus_batch error!";
                return;
            }
            $where['id >'] = $max;

            if ($debug) {
                break;
            }
        }
    }

    public function monthExpenseCharge($dataList, $pre){
        if (!is_array($dataList) || empty($dataList)) {
            return true;
        }

        $item_type = $this->bonus_type;
        $year_month = date('Ym');
        $create_time = date('Y-m-d H:i:s');


        $sql_user = "update users set amount = case id";

        $id_list = [];
        $list = array();
        $coupon_list = [];
        foreach ($dataList as $data) {
            $money_penny = $data['amount'];
            $money_dollar = tps_money_format($money_penny / 100);
            if ($data['coupon_id'] > 0) {   //有月费抵扣券
                $coupon_list[] = $data['coupon_id'];
            } else {
                $sql_user .= " when ".$data['uid']." then amount-" . $money_dollar;
                $id_list[] = $data['uid'];
                $temp = array(
                    'commissionToPoint' => $money_penny,
                    'old_amount' => $data['money'],
                    'uid' => $data['uid'],

                );
                $list[] = $temp;
            }
        }
        if ($year_month < config_item("cash_account_log_cut_table_end_1")) {
            $table = "cash_account_log_" . $year_month;
            $old = true;
        } else {
            //$table = "cash_account_log_" . $year_month . "_" . $pre;
            $table = get_cash_account_log_table_write($pre,$year_month);
            $old = false;
        }
        $sql_log = $this->tb_cash_account_log_x->get_sql_batch($list, $item_type, $table, $create_time, $old);
        $sql_user .= " END WHERE id IN (".implode(',',$id_list).")";


        //echo $sql_log;echo "\n";echo $sql_user;echo "\n\n";echo $sql_coupon;exit;
        //事物开始
        $this->db->trans_begin();

        $this->db->query($sql_log);    //写现金账户日志记录
        $this->db->query($sql_user);    //修改现金池记录
        if (!empty($coupon_list)) {
            $sql_coupon = 'delete from monthly_fee_coupon where id in('.implode(',', $coupon_list).')';
            $this->db->query($sql_coupon);  //删除月费抵扣券
        }


        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 收取用户月费成功');
        } else {
            $this->db->trans_rollback();
            $this->m_log->createCronLog('[fail] 收取用户月费失败 ' . $sql_log.' | '.$sql_user);
            return false;
        }
        return true;
    }


}
