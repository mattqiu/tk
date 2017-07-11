<?php
/**
 * Created by PhpStorm.
 * User: tico.wong
 * Date: 2017/5/24
 * Time: 9:48
 */

class tb_trade_orders_info extends MY_Model
{
    protected $table_name = "trade_orders_info";
    //调试输出开关,只允许调试时打开
    protected $DEBUG = false;

    function __construct() {
        parent::__construct();
    }

    /**
     * 创建trade_orders_info_xxxx表
     * @param $table_name
     */
    public function create_table($table_name)
    {
        if($this->db->table_exists($table_name))
        {
            return;
        }
        $sql = "CREATE TABLE `$table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `consignee` char(255) NOT NULL DEFAULT '' COMMENT '收货人',
  `phone` varchar(24) DEFAULT '' COMMENT '联系电话',
  `reserve_num` varchar(24) DEFAULT '' COMMENT '备用电话',
  `address` varchar(512) NOT NULL DEFAULT '' COMMENT '收货地址',
  `country_address` varchar(50) DEFAULT '' COMMENT '收貨國家和收貨地址分離，單獨開來',
  `zip_code` varchar(16) DEFAULT '' COMMENT '邮政编码',
  `customs_clearance` varchar(32) DEFAULT '' COMMENT '海关报关号',
  `remark` varchar(128) DEFAULT '' COMMENT '订单备注',
  `freight_info` varchar(512) DEFAULT '' COMMENT '物流信息',
  `ID_no` varchar(18) DEFAULT '' COMMENT '证件号码/护照号码',
  `ID_front` varchar(50) DEFAULT '' COMMENT '身份证正面',
  `ID_reverse` varchar(50) DEFAULT '' COMMENT '身份证背面',
  PRIMARY KEY (`id`,`order_id`),
  UNIQUE KEY `UQE_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单详情表'";
        $this->db->query($sql);
    }

    public function debug($msg,$start=0)
    {
        if($this->DEBUG || $start)
        {
            $redis_key = "tb_trade_orders_info:debug:".date("Ymdh");
            $this->redis_lPush($redis_key,$msg);
            if($this->redis_ttl($redis_key) == -1)
            {
                $this->redis_setTimeout($redis_key,60*60);
            }
        }
    }
    /**
     * 取当前表的所有字段,因为可能分表，
     * 也可能未分表，暂直接根据表名取，这里暂加_tmp以取消调用
     * @param string $table_name
     * @return array
     */
    public function column_list_tmp($table_name)
    {
        $res[] = "id";
        $res[] = "order_id";
        $res[] = "consignee";
        $res[] = "phone";
        $res[] = "reserve_num";
        $res[] = "address";
        $res[] = "country_address";
        $res[] = "zip_code";
        $res[] = "customs_clearance";
        $res[] = "remark";
        $res[] = "freight_info";
        $res[] = "ID_no";
        $res[] = "ID_front";
        $res[] = "ID_reverse";
        return $res;
    }

    public function get_one($select="*",$where=[],$or_where=[],$order_by=[],$force_master=0,$cache=1)
    {
        $order_id = 0;
        foreach($where as $k=>$v)
        {
            if($k == "order_id")
            {
                $order_id = $v;
                break;
            }
        }
        foreach($or_where as $k=>$v)
        {
            if($k == "order_id")
            {
                $order_id = $v;
                break;
            }
        }
        if(!$order_id)
        {
            log_message("ERROR","tb_trade_orders's get_one function must have order_id params");
            return [];
        }
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            extract($defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }
        $this->db->select($select);
        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);
        $this->_handle_order_by($this->db,$order_by);
        if($force_master)
        {
            $this->db->force_master();
        }
        $res =  $this->db->get($this->table_name.$this->get_table_ext($order_id));
        if($res)
        {
            $res = $res->row_array();
        }else{
            log_message("ERROR","no data found in get_one by :".var_export($defined_vars,true));
            $res = false;
        }
        if($cache) {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars, $res);
            if ($cache_data) {
                log_message("ERROR", "get_one_return_from_cache:" . var_export($cache_data, true));
                return $cache_data;
            }
        }
        return $res;
    }

    public function get_list($select="*",$where=[],$or_where=[],$page_size=1000,$page_index=0,$order_by=[],$force_master=0,$cache=1,$group_by=[])
    {
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            extract($defined_vars);
            if($cache_data)
            {
                $cache_data = $this->primary_key_replace_list($cache_data);
                return $cache_data;
            }
        }
        $this->db->select($select);
        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);
        $this->_handle_order_by($this->db,$order_by);
        $this->_handle_group_by($this->db,$group_by);
        if($page_size)
        {
            $this->db->limit($page_size,$page_index);
        }
        if($force_master)
        {
            $this->db->force_master();
        }

        $res =  $this->db->get($this->table_name);
        if($res)
        {
            $res = $res->result_array();
            if($cache) {
                $this->_hook_function("after_" . __FUNCTION__, $defined_vars, $res);
            }
        }else{
            log_message("ERROR","no data found in get_list by :".var_export($defined_vars,true));
            return [];
        }
        return $res;
    }
    public function update_one($where=[],$data=[],$or_where=[],$cache=1)
    {
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }
        if(1 > count($data))
        {
            log_message("ERROR","更新数据却未传入需要更新的数据，影响表:".$this->table_name);
            return -1;
        }
        if(1 > count($where))
        {
            log_message("ERROR","未传入条件恐怕会更新整表而不是更新一条的数据，影响表:".$this->table_name);
            return -2;
        }
        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);
        $this->db->limit(1);//限制一条
        $res =  $this->db->update($this->table_name, $data);
        if($cache) {
            $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
        }
        return $res;
    }
    public function update_batch($where=[],$data=[],$or_where=[],$cache=1,$index="")
    {
        $defined_vars = get_defined_vars();
        if($cache) {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }
        if(1 > count($data))
        {
            log_message("ERROR","更新数据却未传入需要更新的数据，影响表:".$this->table_name);
            return -1;
        }
        if($index)
        {
            $res = $this->db->update_batch($this->table_name,$data,$index);
        }else {

            if (1 > count($where)) {
                log_message("ERROR", "未传入条件恐怕会更新整表而不是更新一批的数据，影响表:" . $this->table_name);
                return -2;
            }

            $this->_handle_where($this->db, $where);
            $this->_handle_or_where($this->db, $or_where);

            $res = $this->db->update($this->table_name, $data);

        }

        if($cache) {
            $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
        }

        return $res;
    }
    public function get_counts($where=[],$or_where=[],$force_master=0){
        $this->db->from($this->table_name);

        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);

        if($force_master)
        {
            $this->db->force_master();
        }
        return $this->db->count_all_results();
    }
    public function get_sum($column="",$where=[],$or_where=[],$force_master=0)
    {
        if(!$column)
        {
            return false;
        }
        $this->db->from($this->table_name);

        $this->db->select_sum($column);

        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);

        if($force_master)
        {
            $this->db->force_master();
        }
        if($this->db)
        {
            $tmp = $this->db->get();
            if($tmp){
                return $tmp->row_array();
            }
        }
        return false;
    }

    public function insert_one($data,$cache=1)
    {
        $order_id = 0;
        foreach($data as $k=>$v)
        {
            if($k == "order_id")
            {
                $order_id = $v;
            }
        }
        if(!$order_id)
        {
            $this->debug("tb_trade_orders_info's insert_one function must have order_id params");
            log_message("ERROR","tb_trade_orders_info's insert_one function must have order_id params");
            return 0;
        }
        //取表名后缀
        $table_ext = $this->get_table_ext($order_id);

        $this->create_table($this->table_name.$table_ext);

        $defined_vars = get_defined_vars();
        if($cache) {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }
        if(1 > count($data))
        {
            $this->debug("插入数据却未传入数据，准备操作的表:".$this->table_name.$table_ext);
            log_message("ERROR","插入数据却未传入数据，准备操作的表:".$this->table_name.$table_ext);
            return -1;
        }
        if(!is_array($data))
        {
            $this->debug("插入数据应传入数组，准备操作的表:".$this->table_name.$table_ext);
            log_message("ERROR","插入数据应传入数组，准备操作的表:".$this->table_name.$table_ext);
            return -2;
        }
        $this->db->insert($this->table_name.$table_ext, $data);

        $res =  $this->db->insert_id();
        if($cache)
        {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }
        return $res;
    }
    public function insert_batch($data,$cache=1)
    {
        $defined_vars = get_defined_vars();

        if($cache)
        {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }

        if(1 > count($data))
        {
            $this->debug("批量插入数据却未传入数据，准备操作的表:".$this->table_name);
            log_message("ERROR","批量插入数据却未传入数据，准备操作的表:".$this->table_name);
            return -1;
        }
        if(!is_array($data))
        {
            $this->debug("批量插入数据应传入数组，准备操作的表:".$this->table_name);
            log_message("ERROR","批量插入数据应传入数组，准备操作的表:".$this->table_name);
            return -2;
        }
        $res =  $this->db->insert_batch($this->table_name, $data);

        if($cache)
        {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }

        return $res;
    }
    public function delete_one($where=[],$or_where=[],$cache=1)
    {
        $defined_vars = get_defined_vars();

        if($cache)
        {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }

        if(1 > count($where))
        {
            $this->debug("未传入条件恐怕会删除整表的数据，影响表:".$this->table_name);
            log_message("ERROR","未传入条件恐怕会删除整表的数据，影响表:".$this->table_name);
            return -2;
        }

        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);

        $this->db->limit(1);//限制一条数据
        $this->db->delete($this->table_name);

        $res = $this->db->affected_rows();

        if($cache)
        {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }

        return $res;
    }
    public function delete_batch($where=[],$or_where=[],$cache=1)
    {
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }
        if(1 > count($where))
        {
            log_message("ERROR","未传入条件恐怕会删除整表的数据，影响表:".$this->table_name);
            return -2;
        }

        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);

        $this->db->delete($this->table_name);
        $res = $this->db->affected_rows();

        if($cache) {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }

        return $res;
    }

}