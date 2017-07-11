<?php
/**
 * @author tico.wong
 * @date 2017-02-16
 * @desc 扩展了本model，适应了分表，
 */
class tb_trade_orders_goods extends MY_Model
{
    protected $table_name = "trade_orders_goods";
    //调试开关
    protected $DEBUG = false;
    function __construct()
    {
        parent::__construct();
    }

    public function debug($msg)
    {
        if($this->DEBUG)
        {
            $redis_key = "tb_trade_orders_goods:debug:".date("Ymdh");
            $this->redis_lPush($redis_key,$msg);
            if($this->redis_ttl($redis_key) == -1)
            {
                $this->redis_setTimeout($redis_key,60*60);
            }
        }
    }

    /**
     * 创建trade_orders_goods_xxxx表
     * @data 2017-05-27
     * @param $table_name
     */
    public function create_table($table_name)
    {
        if($this->table_exists($table_name))
        {
            return;
        }
        $sql = "CREATE TABLE `{$table_name}` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `goods_sn_main` varchar(50) NOT NULL DEFAULT '0' COMMENT '产品主sku',
  `goods_sn` varchar(60) NOT NULL DEFAULT '0' COMMENT '产品sku',
  `goods_name` varchar(250) NOT NULL COMMENT '产品名称',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品供应商',
  `store_code` varchar(10) NOT NULL DEFAULT '0' COMMENT '产品的仓库',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `goods_attr` varchar(50) DEFAULT '' COMMENT '产品属性',
  `goods_number` smallint(6) NOT NULL DEFAULT '1' COMMENT '产品数量',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '产品价格',
  `is_doba_goods` tinyint(4) DEFAULT '0' COMMENT '是否doba平台产品',
  `supply_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本币供货价',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `store_code` (`store_code`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单商品表'";
        $this->db->query($sql);
    }


    /**
     * 根据条件获取指定定条件之集
     * @param $params
     * @param string $key
     * @return array
     */
    public function get_params_list($params,$key="order_id")
    {
        $res = [];
        foreach($params as $k=>$v)
        {
            if(strrpos($key,$k) > -1)
            {
                if(is_string($v))
                {
                    $res[] = $v;
                }
                if(is_array($v))
                {
                    foreach($v as $d)
                    {
                        $res[] = $d;
                    }
                }
            }
        }
        return $res;
    }

    //****************************************************
    //*                    重写基类                      *
    //****************************************************

    /**
     * 按表名过滤select字段，
     * 因为分表前后字段不一样，
     * 避免select不存在的字段
     * @param $select
     * @param $table_name
     * @return mixed
     */
    public function filter_select($select,$table_name)
    {
        $select_arr = [];
        if(is_string($select))
        {
            $select_arr = explode(",",$select);
        }
        elseif(is_array($select))
        {
            $select_arr = $select;
        }
        foreach($select_arr as $k=>$v)
        {
            //移除开头的空串
            $select_arr[$k] = preg_replace("/^[\\s]+/i","",$v);
            //移除结尾的空串
            $select_arr[$k] = preg_replace("/[\\s]+$/i","",$select_arr[$k]);
        }
        if(in_array("*",$select_arr))
        {
            return $select_arr;
        }
        $res_select_arr = [];
        $this->create_table($table_name);//检测数据表，如果没有就创建
        $trade_orders_goods_columns = $this->column_list($table_name);//取表的所有列
        foreach($select_arr as $k=>$v)
        {
            if(array_intersect(explode(" ",$v),$trade_orders_goods_columns))
            {
                $res_select_arr[] = $v;
            }else{
                $res_select_arr[] = "char(null) as ".$v;
            }
        }
        if($res_select_arr)
        {
            return $res_select_arr;
        }else{
            return "*";
        }
    }

    /**
     * 根据表名过滤where、or_where、group_by、order_by、data等数据
     * 因为新的分表字段与旧表不一致，
     * 过滤掉表内不存在的字段传入导致错误
     * @param $params
     * @param $table_name
     * @return array
     */
    public function filter_params($params,$table_name)
    {
        $res_params = [];
        $this->create_table($table_name);//检测数据表，如果没有就创建
        $trade_orders_goods_columns = $this->column_list($table_name);//取表的所有列
        foreach($params as $k=>$v)
        {
            if(array_intersect(explode(" ",$k),$trade_orders_goods_columns))
            {
                $res_params[$k] = $v;
            }
        }
        return $res_params;
    }

    /**
     * 重写trade_orders_goods以适应分表
     * @param $data
     * @param int $cache
     * @return int|mixed
     */
    public function insert_one($data,$cache=1)
    {
        $defined_vars = get_defined_vars();
        $this->debug("insert_one:".var_export($defined_vars,true));
        if($cache) {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }
        $ext = $this->get_table_ext();//取分表后缀
        if($ext)
        {
            $this->create_table($this->table_name.$ext);//检测数据表，如果没有就创建
        }

        //过滤掉不存在的字段
        $data = $this->filter_params($data,$this->table_name.$ext);

        if(1 > count($data))
        {
            log_message("ERROR","插入数据却未传入数据，准备操作的表:".$this->table_name.$ext);
            return -1;
        }
        if(!is_array($data))
        {
            log_message("ERROR","插入数据应传入数组，准备操作的表:".$this->table_name.$ext);
            return -2;
        }
        $this->db->insert($this->table_name.$ext, $data);
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

    /**
     * 重写get_list以适应分表
     * @param string $select
     * @param array $where
     * @param array $or_where
     * @param int $page_size
     * @param int $page_index
     * @param array $order_by
     * @param int $force_master
     * @param int $cache
     * @param array $group_by
     * @return array
     */
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
        $order_id = [];
        //取条件中所有的order_id
        if($tmp = $this->get_params_list($where))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        if($tmp = $this->get_params_list($or_where))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        //取条件中所有的attach_id
        if($tmp = $this->get_params_list($where,"attach_id"))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        if($tmp = $this->get_params_list($or_where,"attach_id"))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        //若传入了$order_id或$attach_id，则由这个数据计算所有需要查询的表的后缀
        $table_ext_list = [];
        if($order_id)
        {
            foreach($order_id as $v)
            {
                $ext = $this->get_table_ext($v);
                if(!in_array($ext,$table_ext_list))
                {
                    $table_ext_list[] = $ext;
                }
            }
        }

        //如果传了order_id或attch_id，可推出需要查的表
        if($table_ext_list)
        {
            $sql = "";
            foreach($table_ext_list as $v)
            {
                $select_tmp = $this->filter_select($select,$this->table_name.$v);
                $this->db->select($select_tmp);
                $where_tmp  = $this->filter_params($where,$this->table_name.$v);
                $this->_handle_where($this->db,$where_tmp);
                $or_where_tmp  = $this->filter_params($or_where,$this->table_name.$v);
                $this->_handle_or_where($this->db,$or_where_tmp);
                if($force_master)
                {
                    $this->db->force_master();
                }
                $tmp = $this->db->get_compiled_select($this->table_name.$v,true);
                if($sql)
                {
                    $sql .= " union all \n";
                }
                $sql .= $tmp;
            }

            if(isset($order_by_array[$this->table_name])) {
                $order_by = $order_by_array[$this->table_name];
                if($order_by)
                {
                    $sql .= " order by ";
                }
                $order_index = 0;
                foreach($order_by as $k=>$v)
                {
                    if(!$v)
                    {
                        $v = "desc";
                    }
                    if($order_index > 0)
                    {
                        $sql .= ",";
                    }
                    $sql .= $k." ".$v;
                    $order_index += 1;
                }
            }
            if(isset($group_by_array[$this->table_name])) {
                $group_by = $group_by_array[$this->table_name];
                if($group_by)
                {
                    $sql .= " group by ";
                }
                $group_by_index = 0;
                foreach($group_by as $k=>$v)
                {
                    if($group_by_index > 0)
                    {
                        $sql .= ",";
                    }
                    $sql .= $k;
                    $group_by_index += 1;
                }
            }

            if($page_size)
            {
                $sql .= " limit ".$page_index.",".$page_size;
            }

            $res = $this->db->query($sql);
            $this->debug("get_list:".$sql);
            if($res)
            {
                $res = $res->result_array();
            }else{
                log_message("ERROR",$sql);
                $res = [];
            }
        }else{
            $res = [];
            log_message("ERROR","tb_trade_orders_goods get_list must have order_id params :".var_export($defined_vars,true));
        }
        return $res;
    }

    public function get_one($select="*",$where=[],$or_where=[],$order_by=[],$force_master=0,$cache=1)
    {
        exit("function need overwrite.overwrite it first plz.".__FILE__.",".__LINE__."<BR>");
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
        $res =  $this->db->get($this->table_name);
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
        $this->debug("get_one:".var_export($defined_vars,true));
        return $res;
    }
    public function update_one($where=[],$data=[],$or_where=[],$cache=1)
    {
        exit("function need overwrite.overwrite it first plz.".__FILE__.",".__LINE__."<BR>");
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
        exit("function need overwrite.overwrite it first plz.".__FILE__.",".__LINE__."<BR>");
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
        exit("function need overwrite.overwrite it first plz.".__FILE__.",".__LINE__."<BR>");
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
        exit("function need overwrite.overwrite it first plz.".__FILE__.",".__LINE__."<BR>");
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
    public function insert_batch($data,$cache=1)
    {
        exit("function need overwrite.overwrite it first plz.".__FILE__.",".__LINE__."<BR>");
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
            log_message("ERROR","批量插入数据却未传入数据，准备操作的表:".$this->table_name);
            return -1;
        }
        if(!is_array($data))
        {
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
}
