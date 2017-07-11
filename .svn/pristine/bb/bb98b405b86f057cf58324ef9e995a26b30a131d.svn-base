<?php
/**
 * 供应商推荐奖预览
 * @author carl.zhou
 */
class tb_supplier_recommendation_preview extends CI_Model {
    //全部字段
    private $fullFields = [
        'id',               //自增主键
        'uid',              //用户id
        'date',             //创建日期
        'amount',           //应得奖金
    ];

    protected $percentConf = [
        1 => 0.02,
        2 => 0.015,
        3 => 0.01,
        5 => 0.01,
    ];

    protected $table_name = 'supplier_recommendation_preview';
    protected $primary_key = 'id';
    protected $bonus_type = 27;  //奖金类型

    function __construct() {
        parent::__construct();
    }

    /**
     * 写入预览数据
     * @param array $data
     * @return bool
     */
    public function createSupplierRecommendationPreview($data){
        $this->checkParam($data, ['uid', 'date', 'amount']);
        $res = $this->db->insert($this->table_name, $data);
        return $res ? true : false;
    }

    /**
     * 批量写入预览数据
     * @param array $dataList
     * @return bool
     */
    public function createSupplierRecommendationPreviewBatch($dataList){
        $res = $this->db->insert_batch($this->table_name, $dataList);
        return $res ? true : false;
    }

    /**
     * 批量删除数据
     * @param $idList
     * @return bool
     */
    public function deleteSupplierRecommendationPreviewByUid($idList){
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
    public function getSupplierRecommendationPreview($id){
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
    public function getSupplierRecommendationPreviewList($where, $num = 10, $master = false){
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
    public function getSupplierRecommendationPreviewRows($where, $master = false) {
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

    public function previewSupplierRecommendationReward($start_time, $end_time, $num=10){
        $this->load->model('tb_mall_supplier');

        $start = 0;
        $percentConf = $this->percentConf;
        $percent_profit = 0.15;
        $rewardList = [];
        while (1) {
            $res = $this->tb_mall_supplier->getSupplierRecommendMap($start, $num);
            $start += $num;
            if (empty($res)) {
                break;
            }

            foreach ($res as $item) {
                $uid = $item['uid'];
                $supplier_id = $item['supplier_id'];
                if (!array_key_exists($item['user_rank'], $percentConf)) {
                    continue;
                }
                $percentage = $percentConf[$item['user_rank']];

                if (!isset($rewardList[$uid])) {
                    $rewardList[$uid] = ['reward'=>0, 'num'=>0];
                }

                    //获取该供应商所有的产品主编号
                $sql = "select goods_sn_main from mall_goods_main where supplier_id = $supplier_id";
                $goods_sn_main_arr = $this->db->query($sql)->result_array();//print_r($goods_sn_main_arr);exit;

                if (empty($goods_sn_main_arr)) {
                    continue;
                }

                $goods_sn = '';
                foreach ($goods_sn_main_arr as $sn) {
                    $goods_sn .= ",'" . $sn['goods_sn_main'] . "'";
                }
                $goods_sn = substr($goods_sn, 1);

                //根据产品主编号，连表获取时间段内，满足条件的所有该供应商订单产品及价格、成本
                $sql = "select o.order_id,g.goods_sn,g.goods_number,g.goods_price,m.purchase_price from trade_orders_goods g";
                $sql .= " inner join trade_orders o on g.order_id=o.order_id";
                $sql .= " inner join mall_goods m on m.goods_sn=g.goods_sn";
                $sql .= " where o.status in (4,5,6) and o.order_prop in ('0','1') and order_type in (2,4)";
                $sql .= " and o.pay_time >= '".$start_time."' and o.pay_time<'".$end_time."'";
                $sql .= " and g.goods_sn_main in (" . $goods_sn . ")";
                //echo $sql;echo "\n\n";exit;
                $goods_list = $this->db->query($sql)->result_array();

                //计算所有商品的利润
                $profit = 0;
                $total_num = 0;
                foreach ($goods_list as $goods) {
                    $number = intval($goods['goods_number']);
                    $total_num += $number;
                    $purchase_price = floatval($goods['purchase_price']);
                    $goods_price = floatval($goods['goods_price']);
                    $profit += $number * ($goods_price - $purchase_price - $goods_price * $percent_profit);
                }
                $reward = round($profit * $percentage * 100);
                if ($reward<0) {
                    $reward = 0;
                }

                $rewardList[$uid]['reward'] += $reward;
                $rewardList[$uid]['num'] += $total_num;
            }
        }
        $values = '';
        foreach ($rewardList as $uid=>$item) {
            $values .= sprintf(",(%d,%d,%d)", $uid, round($item['reward'] * 0.8), $item['num']);
        }
        $values = substr($values, 1);

        //写入预览数据 由于供应商数量不多，但是供应商对推荐人是多对一关系，为了同一个推荐人推荐多个供应商不产生多条记录，在此汇总后一次性写入数据库
        if (!empty($rewardList)) {
            $sql = "insert into ".$this->table_name."(uid,amount,num) values".$values;
            $this->db->query($sql);
            $rows = $this->db->affected_rows();
            if ($rows==0) {
                echo "insert into ".$this->table_name." error\n";
            } else {
                echo "create preview ".$rows."\n";
            }
        }
        $sql = 'update '.$this->table_name.' set name=(select name from users where users.id=uid)';
        $this->db->query($sql);
        return;
    }

    public function createTempData($start, $end){
        //$sql = "select count(order_id) from trade_orders_goods where supplier_id in (select supplier_id from mall_supplier where supplier_recommend>0)";
        $sql = "insert into order_info_temp(order_id,created_at,status) select order_id,created_at,status from trade_orders";
        $sql .= " where created_at>='".$start."' and created_at<'".$end."'";
        $this->db->query($sql);
    }

    /***
     * 清空表数据
     */
    public function truncateOrderTemp(){
        $this->db->query('truncate trade_orders');
    }

    /**
     * 发放月杰出店铺奖
     * @param int $num 一次发放的数量
     * @param int $debug 1调试模式只循环一次
     */
    public function supplierRecommendationRewardImplement($num, $debug){
        $this->load->model('o_bonus');

        $max = 0;
        $where = [
            'page' => 1,
            'id >' => $max,
            'order by' => ['id'=>'asc'],
            'fields' => ['id', 'uid', 'amount as money'],
        ];

        $count = 0;
        while($res = $this->getSupplierRecommendationPreviewList($where, $num, true)) {
            echo $count++.".";
            $result = $this->o_bonus->assign_bonus_batch($res, $this->bonus_type);
            //echo " status: ".var_dump($result)." ";
            if ($result===TRUE) {
                $uidList = [];
                foreach ($res as $item) {
                    $uidList[] = $item['uid'];
                    $max = $item['id'];
                }
                $rows = $this->deleteSupplierRecommendationPreviewByUid($uidList);
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
}
