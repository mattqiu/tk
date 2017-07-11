<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."core/Redis_Cache.php");

/**
 * @author Tico.wong
 * 将简单查询统一到本Model,便于进行优化、管理、扩展等操作
 */
class MY_Model extends Redis_Cache
{
    protected  $table_name = "";
    protected  $table = '';
    function __construct() {
        parent::__construct();
        $db_slave_config = config_item('db_slave');
        $CI = &get_instance();
        if($db_slave_config)
        {
            if(!isset($CI->db_slave))
            {
                $CI->db_slave = $this->load->database('slave', TRUE);
                log_message("ERROR", "init the slave db");
            }
        }else{
            if(!isset($CI->db_slave)) {
                $CI->db_slave = $CI->db;
                log_message("ERROR", "can't find the slave db config");
            }
        }
    }

    /**
     * 判断传入数组是否有效
     * @param $val
     * @return bool
     */
    protected function _is_validate_array($val)
    {
        if(empty($val))
        {
            return false;
        }
        if(!is_array($val))
        {
            return false;
        }
        return true;
    }

    /**
     * 取表名方法，子类构造中调并用传入__CLASS__后进行赋值即可
     * 或是子类直接设置table_name后子类中无需调用本方法
     */
    public function get_table_name($cls_name="")
    {
        if(isset($this->table_name))
        {
            if(!empty($this->table_name)){
                return $this->table_name;
            }
        }
        if(empty($cls_name))
        {
            echo(__FILE__.",".__LINE__."<BR>");
            log_message("ERROR","model必须要设置表名:table_name");
            exit('继承MY_Model的模型必须要设置表名:$table_name');
        }
        return preg_replace('/^tb_/i',"",$cls_name);
    }

    /**
     * 取一条数据
     * @param $select，查询的字段
     * @param array $where，查询的条件
     * @param  array $or_where,查询的或条件数组
     * @param array $order_by,排序条件数组
     * @param mixed $force_master,是否强制主库
     * @param mixed $cache,是否启用缓存
     * @return array|mixed row_array，返回的结果
     */
    public function get_one($select="*",$where=[],$or_where=[],$order_by=[],$force_master=0,$cache=1)
    {
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
        return $res;
    }

    /**
     * 取一条数据，传入数组作为参数，不区分参数位置
     * @param $param['select'="*",'where'=[],'or_where'=[],'order_by'=[]]
     * @return array
     */
    public function get_one_auto($param=[])
    {
        $select = "*";
        $where = [];
        $or_where = [];
        $order_by = [];
        $force_master = 0;
        $cache = 1;
        extract($param);
        return $this->get_one($select,$where,$or_where,$order_by,$force_master,$cache);
    }

    /**
     * 取数据列表
     * @param string $select，查询的字段
     * @param array $where，查询条件,where条件的值若传null则使用$this->db->where($k,null,false);
     * @param  array $or_where, 查询的或条件
     * @param  int $page_size,默认取前1000条防止忘记带limit参数,超1000的数据请传参进来
     * @param  int $page_index，取数据的页码
     * @param array $order_by,排序方式
     * @param mixed $force_master,是否强制使用主库
     * @param mixed $cache,是否启用缓存
     * @return  array result_array，返回结果集
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

    /**
     * 取数据列表，传入数组作为参数，不区分参数位置
     * @param $param
     * @return array
     */
    public function get_list_auto($param=[])
    {
        $select="*";
        $where=[];
        $or_where=[];
        $page_size=1000;
        $page_index=0;
        $order_by=[];
        $force_master = 0;
        $cache = 1;
        $group_by = [];
        extract($param);
        return $this->get_list($select,$where,$or_where,$page_size,$page_index,$order_by,$force_master,$cache,$group_by);
    }

    /**
     * 更新一条数据
     * @param array $where，更新条件
     * @param array $data，更新的数据
     * @param array $or_where，更新的或条件
     * @param mixed $cache,是否启用缓存
     * @return int，小于0则报错
     */
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

    /**
     * 更新一条数据，传入数组作为参数，不区分参数位置
     * @param $param
     * @return int
     */
    public function update_one_auto($param=[])
    {
        $where=[];
        $data=[];
        $or_where=[];
        $cache = 1;
        extract($param);
        return $this->update_one($where,$data,$or_where,$cache);
    }

    /**
     * 更新一批数据
     * @param array $where，更新条件
     * @param array $data，更新的数据
     * @param array $or_where，更新的或条件
     * @param mixed $cache,是否启用缓存操作
     * @param string $index 更新的数据的索引，此时更新条件肯定在$data里，其他条件都可以不传
     * @return int
     */
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

    /**
     * 更新一批数据，传入数组作为参数，不区分参数位置
     * @param $param
     * @return int
     */
    public function update_batch_auto($param=[])
    {
        $where=[];
        $data=[];
        $or_where=[];
        $cache = 1;
        $index = "";
        extract($param);
        return $this->update_batch($where,$data,$or_where,$cache,$index);
    }

    /**
     * 简单的统计数据总量查询
     * @param array $where，and条件
     * @param array $or_where，or条件
     * @param mixed $force_master 是否强制主库
     * @return mixed，返回数据量结果
     */
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

    /**
     * 简单的统计数据总量查询，传入数组作为参数，不区分参数位置
     * @param $param
     * @return mixed
     */
    public function get_counts_auto($param=[])
    {
        $where = [];
        $or_where = [];
        $force_master = 0;
        extract($param);
        return $this->get_counts($where,$or_where,$force_master);
    }


    /**
     * 简单的封装select_sum函数
     * @param string $column
     * @param array $where
     * @param array $or_where
     * @param int $force_master
     * @return mixed
     */
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

    public function get_sum_auto($param)
    {
        $column = "";
        $where = [];
        $or_where = [];
        $force_master = 0;
        extract($param);
        return $this->get_sum($column,$where,$or_where,$force_master);
    }

    /**
     * 往表里插入一条数据
     * @param $data，需要插入的数据
     * @param $cache,是否启用缓存操作
     * @return int，返回插入数据的ID，若为负数则表明有直接报错
     */
    public function insert_one($data,$cache=1)
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
            log_message("ERROR","插入数据却未传入数据，准备操作的表:".$this->table_name);
            return -1;
        }
        if(!is_array($data))
        {
            log_message("ERROR","插入数据应传入数组，准备操作的表:".$this->table_name);
            return -2;
        }
        $this->db->insert($this->table_name, $data);

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
     * 往表里批量插入数据
     * @param $data，需要插入的数据
     * @param $cache,是否启用缓存操作
     * @return int，返回影响行数，若为负数则有直接报错
     */
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

    /**
     * 替换一条数据
     * @param $data
     */
    public function replace($data)
    {
        return $this->db->replace($this->table_name,$data);
    }

    /**
     * 删除一条数据
     * @param array $where
     * @param array $or_where
     * @param mixed $cache,是否启用缓存操作
     * @return mixed，成功失败或负数，若负数则表示直接已出错
     */
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

    /**
     * 删除一条数据，传入数组作为参数，不区分参数位置
     * @param $param
     * @return mixed
     */
    public function delete_one_auto($param=[])
    {
        $where=[];
        $or_where=[];
        $cache = 1;
        extract($param);
        return $this->delete_one($where,$or_where,$cache);
    }

    /**
     * 删除一批数据
     * @param array $where
     * @param array $or_where
     * @param mixed $cache,是否启用缓存操作
     * @return int
     */
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

    /**
     * 删除一批数据，传入数组作为参数，不区分参数位置
     * @param $param['where'=>[],'or_where'=>[]]
     * @return mixed
     */
    public function delete_batch_auto($param=[])
    {
        $where=[];
        $or_where=[];
        $cache = 1;
        extract($param);
        return $this->delete_batch($where,$or_where,$cache);
    }

    /**
     * 注册自定义的钩子函数的方法
     * 默认使用Redis_Cache类里的函数
     * 如果在子类重写则直接使用子类的
     * @param $name
     * @param $args
     * @return mixed
     */
    protected function _hook_function($name, &$args, &$data=null)
    {
        if(method_exists($this,$name))
        {
            log_message("DEBUG","MY_Model call hook function. name is:$name");
            if(!$data){
                return call_user_func_array([$this,$name],[&$args]);
            }else{
                return call_user_func_array([$this,$name],[&$data,&$args]);
            }
        }
    }

    /**
     * 处理where条件
     * @param $param_db
     * @param $where
     */
    protected function _handle_where($param_db,$where)
    {
        if($this->_is_validate_array($where)) {
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    if(!$this->_handle_like($param_db,$k,$v)) {
                        $param_db->where_in($k, $v);
                    }
                } else {
                    if(!empty($k)){
                        if(!is_null($v)){
                            if(!$this->_handle_like($param_db,$k,$v))
                            {
                                $param_db->where($k, $v);
                            }
                        }else{
                            $param_db->where($k,null,false);
                        }
                    }
                }
            }
        }
    }

    /**
     * like 字段的处理
     * @param $param_db
     * @param $k
     * @param $v
     * @return bool
     */
    protected function _handle_like($param_db,$k,$v)
    {
        if(preg_match('/(\b)+like/i',$k))
        {
            $pos = "both";
            if(is_array($v))//如果like的值传入的是数组
            {
                foreach($v as $vk=>$vv)
                {
                    if("after" == strtolower($vv))
                    {
                        $pos = "after";
                    }
                    if("before" == strtolower($vv))
                    {
                        $pos = "before";
                    }
                    $param_db->like(preg_replace('/(\b)+like/i','',$k),$vk,$pos);
                }
                return true;
            }else{//如果like的值传入其他类型(字符串)
                $param_db->like(preg_replace('/(\b)+like/i','',$k),$v,$pos);
                return true;
            }
        }
        return false;
    }

    /**
     * 处理or_where条件
     * @param $param_db
     * @param $or_where
     */
    protected function _handle_or_where($param_db,$or_where)
    {
        if($this->_is_validate_array($or_where)) {
            foreach ($or_where as $k => $v) {
                if (is_array($v)) {
                    $param_db->or_where_in($k, $v);
                }else {
                    if(!empty($k)){
                        if(!is_null($v)){
                            $param_db->or_where($k, $v);
                        }else{
                            $param_db->or_where($k,null,false);
                        }
                    }
                }
            }
        }
    }

    /**
     * 排序参数的处理
     * @param $param_db
     * @param $order_by
     */
    protected function _handle_order_by($param_db,$order_by)
    {
        if($this->_is_validate_array($order_by)) {
            foreach ($order_by as $k => $v) {
                if ($v) {
                    if (in_array(strtolower($v), ["asc", "desc"])) {
                        $param_db->order_by($k, $v);
                        continue;
                    }
                }
                $param_db->order_by($k, "desc");
            }
        }
    }

    /**
     * 分组参数的处理
     * @param $param_db
     * @param $group_by
     */
    protected function _handle_group_by($param_db,$group_by)
    {
        if($this->_is_validate_array($group_by)) {
            foreach ($group_by as $k => $v) {
                if ($v) {
                    $param_db->group_by($v);
                }
            }
        }else{
            $param_db->group_by($group_by);
        }
    }


    /**
     *
     * 查询
     * @author brady.wang
     * @param array $params
     * @param bool  $get_rows 是否返回行数
     * @param bool  $get_one 是否返回一行
     * @return array
     */
    public function get(array $params, $get_rows = false, $get_one = false,$master = false)
    {
        $slave_host = config_item('db_slave');

        $db = "db";

        if ($this->table == "grant_pre_users_daily_bonus") {
        }

        $this->$db->from($this->table);
        if ($get_rows) {
            $this->$db->select("count(0) as number");
        } else {

            if (isset($params['select'])) {
                if (isset($params['select_escape'])) {
                    $this->$db->select($params['select'], false);
                } else {
                    $this->$db->select($params['select']);
                }
            } else if(isset($params['select_escape'])) {
                if (isset($params['select_escape'])) {
                    $this->$db->select($params['select_escape'], false);
                } else {
                    $this->$db->select($params['select_escape']);
                }
            }
        }
        if (isset($params['where']) && is_array($params['where'])) {
            $this->$db->where($params['where']);
        }

        if (isset($params['where_in']) && is_array($params['where_in'])) {
            $this->$db->where_in($params['where_in']['key'], $params['where_in']['value']);
        }

        if (isset($params['join'])) {
            foreach ($params['join'] as $item) {
                $this->$db->join($item['table'], $item['where'], $item['type']);
            }
        }
        if (isset($params['limit'])) {
            if (is_array($params['limit']) && isset($params['limit']['page']) && isset($params['limit']['page_size'])) {
                $this->$db->limit($params['limit']['page_size'],($params['limit']['page']-1)*$params['limit']['page_size']);
            } else {
                $this->$db->limit($params['limit']);
            }
        }

        if ($master == true) {
            $this->db->force_master();
        }
        if (isset($params['group'])) {
            $this->$db->group_by($params['group']);
        }
        if (isset($params['order'])) {
            if (is_array($params['order'])) {
                foreach ($params['order'] as $v) {
                    $this->$db->order_by($v['key'], $v['value']);
                }
            } else {
                $this->$db->order_by($params['order']);
            }

        }

        $result = $this->$db->get();

        if (!$get_one) {
            if ($get_rows) {
                return $result? $result->row_array()['number']:0;
            } else {
                if ($result) {
                    return ($result->num_rows() > 0 ? $result->result_array() : array());
                } else {
                    return array();
                }

            }

        } else {
            if ($get_rows) {
                return $result? $result->row_array()['number'] : 0;
            } else {
                if ($result) {
                    return ($result->num_rows() > 0 ? $result->row_array() : array());
                } else {
                    return array();
                }

            }
        }
    }

    public function del_redis_page($uid,$key_prefix_str){
        $key_prefix = config_item("redis_key")[$key_prefix_str];
        $keys = $this->tb_cash_take_out_logs->redis_keys($key_prefix.$uid."*");
        foreach($keys as $v) {
            $this->tb_cash_take_out_logs->redis_del($v);
        }
    }

    public function add($data)
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * @author 返回分页字符
     * @param $url string ucenter/my_tickets
     * @param $params  搜索内容
     * @param $total   总数
     * @param $goto 是否展示跳到某页
     * @return mixed   分页
     */
    public  function get_pager($url,$params,$total,$goto=false)
    {
        $this->load->library('pagination');

        add_params_to_url($url, $params);

        $config['base_url'] = base_url($url);
        $config['total_rows'] = $total;
        $config['cur_page'] = $params['page'];
        $config['per_page'] = (isset($params['page_size'])  && (intval($params['page_size'] > 0)) ) ? $params['page_size'] : 10;

        $this->pagination->initialize_ucenter($config);
        $pager = $this->pagination->create_links($goto);
        return $pager;
    }

    /**
     * 获取表名列表
     * @param string $grep，传入的正则规则
     * @param string $order_by_sql 传入的排序规则
     * @return mixed
     */
    public function table_list($grep="",$order_by_sql=" ORDER BY CREATE_TIME DESC")
    {
        $redis_key = "SCHEMA:TABLES:".date("Ymd").":".$this->format_redis_key($grep);
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            $res = unserialize($tmp);
            if($res)
            {
                return $res;
            }
        }
        $res = [];
        //trade_orders(_[0-9]+)?$
        //trade_orders_goods(_[0-9]+)?$
        $sql = "select `table_name` from information_schema.tables where `table_schema`='".$this->db->database."'";
        if($grep)
        {
            $sql .= " and `table_name` REGEXP '{$grep}'";
        }
        $sql .= $order_by_sql;
        $tmp =  $this->db->query($sql)->result_array();
        foreach($tmp as $k=>$v)
        {
            $res[] = $v['table_name'];
        }
        if($res)
        {
            $this->redis_set($redis_key,serialize($res),60*60*24);
        }
        return $res;
    }

    /**
     * 获取字段名列表
     * @param string $table_name，传入的表名
     * @return mixed
     */
    public function column_list($table_name)
    {
        $redis_key = "SCHEMA:COLUMNS:".date("Ymd").":".$this->format_redis_key($table_name);
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            $res = unserialize($tmp);
            if($res)
            {
                return $res;
            }
        }
        $res = [];
        $sql = "select `column_name` from information_schema.columns where `table_schema`='".$this->db->database."'";
        if($table_name)
        {
            $sql .= " and `table_name` = '{$table_name}'";
        }
        $tmp = $this->db->query($sql)->result_array();
        foreach($tmp as $v)
        {
            $res[] = $v['column_name'];
        }
        if($res)
        {
            $this->redis_set($redis_key,serialize($res),60*60*24);
        }
        return $res;
    }

    /**
     * 根据订单ID或日期获取分表后缀
     * @param string $order_id_or_date
     * @param int $length
     * @return string
     */
    public function get_table_ext_str($order_id_or_date = '',$length=4)
    {
        $datetime = '';
        if ($order_id_or_date) {
            if (preg_match("/^\\d{4}$/", $order_id_or_date)) {
                $datetime = $order_id_or_date;
            } elseif (preg_match("/^[A-Z]{1}20/", $order_id_or_date)) {
                //单字母开头订单
                $datetime = substr($order_id_or_date, 3, $length);
            } elseif (preg_match("/^[A-Z]{2}20/", $order_id_or_date)) {
                //双字母开头订单
                $datetime = substr($order_id_or_date, 4, $length);
            } elseif (preg_match("/^[A-Z]{2}/", $order_id_or_date)) {
                //双字母开头订单, 2位数年订单
                $datetime = substr($order_id_or_date, 2, $length);
            } else if (preg_match("/^[A-Z]{1}/", $order_id_or_date)) {
                //单字母开头订单, 2位数年订单
                $datetime = substr($order_id_or_date, 1, $length);
            } else if (preg_match("/^\\d+$/", $order_id_or_date)) {
                //纯数字订单号, 遗留订单
                $datetime = '';
            }
        }
        return $datetime;
    }

    /**
     * 取分表名后缀,来自derrick的tb_trade_orders_v1
     * 如果返回空串表示未启用分表,否则返回分表后缀
     * 订单名依旧是支持字母加Ymd如：N20170527*********
     * @author tico.wong
     * @param string $order_id_or_date
     * @return string
     */
    public function get_table_ext($order_id_or_date = '') {
        $res = "";
        $datetime = date('ym');
        //支持精确到天的分表
        $tmp_day = $this->get_table_ext_str($order_id_or_date,8);
        if(!$tmp_day){
            $tmp_day = date('ymdH');
        }
        //此处规定拆表的日期，一经发布到正式，不允许改变，
        //否则时间差内订单必出问题。现指定17062015,即2017-06-20 15:00:00开始分表
        if(bccomp($tmp_day,17062015) >= 0)
        {
            //取按月分表的表名
            $tmp = $this->get_table_ext_str($order_id_or_date,4);
            if($tmp)
            {
                $datetime = $tmp;
            }
        }else{
            $datetime = "";
        }
        if($datetime)
        {
            $res = "_".$datetime;
        }else{
            $res = "";
        }
        return $res;
    }

    /**
     * 过滤掉并不存在的表的后缀名
     * @param $table_exts
     * @param $original_table_name
     * @return mixed
     */
    public function filter_table_ext($table_exts,$original_table_name)
    {
        if(is_array($table_exts)){
            foreach($table_exts as $k=>$v)
            {
                if(!$this->table_exists($original_table_name.$v)){
                    unset($table_exts[$k]);
                }
            }
        }elseif(is_string($table_exts)){
            if(!$this->table_exists($original_table_name.$table_exts)){
                $table_exts = "";
            }
        }
        return $table_exts;
    }

    /**
     * 检测指定表是否存在
     * @param $table_name
     * @return bool|string
     */
    public function table_exists($table_name)
    {
        $redis_key = "SCHEMA:TABLE_EXISTS:".":".$this->format_redis_key($table_name);
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            $res = $tmp;
            if($res)
            {
                return $res;
            }
        }
        $res = $this->db->table_exists($table_name);
        if($res)
        {
            $this->redis_set($redis_key,"1",60*60*24);
        }
        return $res;
    }
}
