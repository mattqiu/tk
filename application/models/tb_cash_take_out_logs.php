<?php

/**
 * @author: jason
 */
class tb_cash_take_out_logs extends MY_Model {

    protected $table  = "cash_take_out_logs";
    protected $table_name = "cash_take_out_logs";
    function __construct() {
        parent::__construct();
    }

    /*     * *
     * @param $uid
     * @param $time
     * @return mixed
     */

    function get_cash_take_out_logs($uid, $time) {
        $data = array();
        $res = $this->db->query("select id,uid,take_out_type,amount,account_name,account_bank,card_number,status,date_format(create_time,'%Y-%m-%d')as create_time from cash_take_out_logs where create_time like '$time%' and uid = $uid order by create_time DESC ")->result_array();
        foreach ($res as $key => $item) {
            $temp = $this->temp_check($data, $item['create_time']);
            if ($temp === false) {
                $data[$key]['time'] = $item['create_time'];
                $data[$key]['list'][] = $item;
            } else {
                $count = count($data[$temp]['list']);
                $data[$temp]['list'][$count] = $item;
            }
        }
        return $data;
    }

    function temp_check($data, $value) {
        foreach ($data as $key => $item) {
            if ($item['time'] == $value) {
                return $key;
            }
        }
        return false;
    }
    /*
     * 取出paypal提现申请列表
     */
    public function get_paypal_pending_log($filter, $page = false, $perPage = 10) {

            /*         * *要注销的提交项* */
            unset($filter['page']);//分页
            unset($filter['page_num']);//每页数量
            /*         * ************** */
            //
            $this->db->select('log.id,log.order_id,log.txn_id,log.time,log.status,count(o.id) as num'); //联表查询字段
            $this->db->from('paypal_pending_log as log');
            $this->db->join('paypal_remark_list as o', 'log.order_id = o.order_id','left');
            foreach ($filter as $k => $v) {
                if ($v === '') {
                    continue;
                }
                if ($k == 'start') {
                    $this->db->where('log.time >=', $v);
                    continue;
                }
                if ($k == 'end') {
                    $end = date('Y-m-d H:i:s',strtotime($v)+86400-1);
                    $this->db->where('log.time <=', $end);
                    continue;
                }
                $this->db->where('log.'.$k, $v);
            }
            $this->db->group_by("log.order_id");
            $obj2 = clone $this->db;
            $array['num'] = $this->db->get()->num_rows();
            $array['list'] = $obj2->order_by("log.status,log.time", "asc,desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
            return $array;
        }
    /*
     * 更新提现记录状态
     */

    public function up_paypal_pending_log($batch,$where) {
        if(isset($where['order_id'])){
            $this->db->update('paypal_pending_log', array('status'=>$where['status']), array('order_id'=>$where['order_id']));
        }
        $this->db->insert('paypal_remark_list', $batch);
        return $this->db->affected_rows();
    }
    /*
     * 更新提现记录状态
     */

    public function get_remark_list($batch) {
        $this->db->select('*'); //查询字段
        $this->db->from('paypal_remark_list');
        $this->db->where($batch);
        return $this->db->get()->result_array();
    }
    /*
     * 取出paypal提现申请列表
     */

    public function get_all_paypal_take_out_logs($filter, $page = false, $perPage = 10) {

        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['page_num']);//每页数量
        /*         * ************** */
        //
        $this->db->select('masspay.trade_no,log.id,log.uid,log.amount,log.subbranch_bank,log.handle_fee,log.actual_amount,log.check_info,log.take_out_type,log.create_time,log.check_time,log.status,log.account_name,log.card_number,log.remark,log.batch_num,user.name,user.country_id,batch.exchange_rate'); //联表查询字段
        $this->db->from('cash_take_out_logs as log');
        $this->db->join('users as user', 'log.uid = user.id');
        $this->db->join('cash_take_out_batch_tb as batch', 'log.batch_num = batch.id', 'left');
        $this->db->join('mass_pay_trade_no as masspay', 'log.id = masspay.id', 'left');
        foreach ($filter as $k => $v) {
            if ($v === '') {
                continue;
            }
            if ($k == 'uid') {
                if (!is_numeric($v)) {
                    $this->db->where('user.name =', $v);
                    continue;
                }  else {
//                    $this->db->where('log.uid =', str_replace(' ','',$v));
//                    $this->db->or_where('log.id =', str_replace(' ','',$v));
                    $this->db->where('(log.uid ='.str_replace(' ','',$v).' OR log.id ='.str_replace(' ','',$v).')');
                    continue;
                }
            }
            if ($k == 'start') {
                $this->db->where('log.create_time >=', $v);
                continue;
            }
            if ($k == 'country_id') {
                if($v=='x'){
                    $arr=array(1,2,3,4);
                    $this->db->where_not_in('user.country_id ', $arr);
                    continue;
                }  else {
                    $this->db->where('user.country_id =', $v);
                    continue;
                }
            }
            if ($k == 'end') {
                $end = date('Y-m-d H:i:s',strtotime($v)+86400-1);
                $this->db->where('log.create_time <=', $end);
                continue;
            }if($k=='checkboxes'){
                $this->db->where_in('log.id',$v);
                continue;
            }
            $this->db->where('log.' . $k, $v);
        }
        $this->db->where('log.take_out_type', 5);
        $this->db->order_by('log.status','ASC');
        $this->db->order_by('log.id','asc');
        $obj2 = clone $this->db;
        $array['num'] = $this->db->get()->num_rows();
        $array['list'] = $obj2->order_by("log.create_time", "asc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
        return $array;
    }
    /*
     * 取出支付宝提现申请列表
     */

    public function get_all_cash_take_out_logs($filter, $page = false, $perPage = 10) {

        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['page_num']);//每页数量
        unset($filter['rate']);//汇率
        unset($filter['batch_number']);//批次号
        unset($filter['total']);//勾选的总数
        unset($filter['lump_sum']);//总金额
        /*         * ************** */
        //
        $this->db->select('log.id,log.uid,log.amount,log.handle_fee,log.actual_amount,log.check_info,log.take_out_type,log.create_time,log.status,log.account_name,log.card_number,log.remark,log.batch_num,user.name,batch.exchange_rate'); //联表查询字段
        $this->db->from('cash_take_out_logs as log');
        $this->db->join('users as user', 'log.uid = user.id');
        $this->db->join('cash_take_out_batch_tb as batch', 'log.batch_num = batch.id', 'left');
        foreach ($filter as $k => $v) {
            if ($v === '') {
                continue;
            }
            if ($k == 'uid') {
                if (!is_numeric($v)) {
                    $this->db->where('user.name =', str_replace(' ','',$v));
                    continue;
                }
            }
            if ($k == 'start') {
                $this->db->where('log.create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $end = date('Y-m-d H:i:s',strtotime($v)+86400-1);
                $this->db->where('log.create_time <=', $end);
                continue;
            }
            $this->db->where('log.' . $k, $v);
        }
        $this->db->where('log.take_out_type', 2);
        $this->db->order_by('status','ASC');
        $this->db->order_by('id','asc');
        $obj2 = clone $this->db;
        $array['num'] = $this->db->get()->num_rows();
        $array['list'] = $obj2->order_by("log.create_time", "asc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
        return $array;
    }
/*
     * 取出银行卡提现申请列表
     */

    public function get_all_bank_logs($filter, $page = false, $perPage = 10) {

        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['page_num']);//每页数量
        unset($filter['rate']);//汇率
        unset($filter['batch_number']);//批次号
        unset($filter['total']);//勾选的总数
        unset($filter['lump_sum']);//总金额
        /*         * ************** */
        //
        $this->db->select('log.id,log.uid,log.amount,log.handle_fee,log.actual_amount,log.check_info,log.take_out_type,log.create_time,log.status,log.account_name,log.card_number,log.remark,log.batch_num,user.name,batch.exchange_rate,batch.pay_type'); //联表查询字段
        $this->db->from('cash_take_out_logs as log');
        $this->db->join('users as user', 'log.uid = user.id');
        $this->db->join('cash_bank_take_out_batch_tb as batch', 'log.batch_num = batch.id', 'left');
        foreach ($filter as $k => $v) {
            if ($v === '') {
                continue;
            }
            if ($k == 'uid') {
                if (!is_numeric($v)) {
                    $this->db->where('user.name =', str_replace(' ','',$v));
                    continue;
                }
            }
            if ($k == 'start') {
                $this->db->where('log.create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $end = date('Y-m-d H:i:s',strtotime($v)+86400-1);
                $this->db->where('log.create_time <=', $end);
                continue;
            }
            $this->db->where('log.' . $k, $v);
        }
        $this->db->where('log.take_out_type', 6);
        $this->db->order_by('status','ASC');
        $this->db->order_by('id','asc');
        $obj2 = clone $this->db;
        $array['num'] = $this->db->get()->num_rows();
        $array['list'] = $obj2->order_by("log.create_time", "asc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
        return $array;
    }
    /*
     * 批量插入支付宝批次号
     */

    public function insert_batch_num($batch, $dota) {
        $this->db->trans_start();
        $this->db->insert('cash_take_out_batch_tb', $batch);
        $insert_id = $this->db->insert_id();
        foreach ($dota as $key => $value) {
            $data[$key]['id'] = $value;
            $data[$key]['batch_num'] = $insert_id;
            $data[$key]['status'] = 2; //生成批次的提现申请，自动变为处理中
        }
        $this->db->update_batch('cash_take_out_logs', $data, 'id');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /*
     * 批量插入银行卡批次号
     */

    public function insert_batch_bank_num($batch, $dota) {
        $this->db->trans_start();
        $this->db->insert('cash_bank_take_out_batch_tb', $batch);
        $insert_id = $this->db->insert_id();
        foreach ($dota as $key => $value) {
            $data[$key]['id'] = $value;
            $data[$key]['batch_num'] = $insert_id;
            $data[$key]['status'] = 2; //生成批次的提现申请，自动变为处理中
        }
        $this->db->update_batch('cash_take_out_logs', $data, 'id');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /*
     * 批量插入paypal批次号
     */

    public function insert_paypal_batch_num($batch, $dota) {
        $this->db->trans_start();
        $this->db->insert('cash_paypal_take_out_batch_tb', $batch);
        $insert_id = $this->db->insert_id();
        foreach ($dota as $key => $value) {
            $data[$key]['id'] = $value;
            $data[$key]['batch_num'] = $insert_id;
            $data[$key]['status'] = 2; //生成批次的提现申请，自动变为处理中
        }
        $this->db->update_batch('cash_take_out_logs', $data, 'id');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /*
     * 支付宝取消批次
     */

    public function up_batch_num($batch, $data) {
        $this->db->trans_start();
        $this->db->where('id', $batch)->delete('cash_take_out_batch_tb'); 
        $this->db->where("take_out_type",2);//支付宝提现属性
        $this->db->where("status",2);
        $this->db->update('cash_take_out_logs', $data['data'], $data['where']);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /*
     * 支付宝取消批次
     */

    public function up_bank_batch_num($batch, $data) {
        $this->db->trans_start();
        $this->db->where('id', $batch)->delete('cash_bank_take_out_batch_tb'); 
        $this->db->where("take_out_type",6);//银行卡提现属性
        $this->db->where("status",2);
        $this->db->update('cash_take_out_logs', $data['data'], $data['where']);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /*
     * paypal取消批次
     */

    public function up_paypal_batch_num($batch, $data) {
        $this->db->trans_start();
        $this->db->where('id', $batch)->delete('cash_paypal_take_out_batch_tb');
        $this->db->where("take_out_type",5);//paypal提现属性
        $this->db->where("status",2);
        $this->db->update('cash_take_out_logs', $data['data'], $data['where']);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /*
     * paypal取消批次
     */

    public function up_paypal_batch_num2($batch, $data) {
        $this->db->trans_start();
        $this->db->update('cash_paypal_take_out_batch_tb', array('status'=>3), array('id'=>$batch));
        $this->db->where("take_out_type",5);//paypal提现属性
        $this->db->update('cash_take_out_logs', $data['data'], $data['where']);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /*
     * 查询数据中是否有批次
     */

    public function select_batch_num($batch) {
        $this->db->select('batch_num');
        $this->db->from('cash_take_out_logs');
        foreach ($batch['checkboxes'] as $k => $v) {
            if (!$v) {
                continue;
            }
            $this->db->or_where('id', $v);
        }
        return $this->db->limit(1)->get()->row_array();
    }

    /*
     * 更新提现记录状态
     */

    public function up_log_num($where, $batch) {
        if(isset($where["id"]) && is_array($where["id"]) && !empty($where["id"])){
            $this->db->where_in("id",$where["id"]);
            $this->db->update('cash_take_out_logs', $batch);
        }
        $this->db->update('cash_take_out_logs', $batch, $where);
        return $this->db->affected_rows();
    }

    /*
     * 取出支付宝批次列表
     */

    public function get_all_batch($filter, $page = false, $perPage = 10) {
        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['page_num']);//每页数量
        unset($filter['rate']);//汇率
        unset($filter['batch_number']);//批次号
        /*         * ************** */
        $this->db->select('*'); //联表查询字段
        $this->db->from('cash_take_out_batch_tb');
        foreach ($filter as $k => $v) {
            if ($v==='') {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('born_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('born_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                continue;
            }
            $this->db->where($k, $v);
        }
        $obj2 = clone $this->db;
        $array['num'] = $obj2->get()->num_rows();
        $array['list'] = $this->db->order_by("born_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
       // $array["count"]["count_usd"] = $this->db->select("SUM(lump_sum) as sum")->where_in("status",array("1","3"))->get('cash_take_out_batch_tb')->row_array();//总金额 美元
      //  $array["count"]["count_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where_in("status",array("1","3"))->get('cash_take_out_batch_tb')->row_array();//总金额 人民币
        $array["count"]["three_usd"] = $this->db->select("SUM(lump_sum) as sum")->where("status",3)->get('cash_take_out_batch_tb')->row_array();//已完成  美元
        $array["count"]["three_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where("status",3)->get('cash_take_out_batch_tb')->row_array();//已完成  人民币
      //  $array["count"]["two_usd"] = $this->db->select("SUM(lump_sum) as sum")->where("status",2)->get('cash_take_out_batch_tb')->row_array();//处理中  美元
      //  $array["count"]["two_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where("status",2)->get('cash_take_out_batch_tb')->row_array();//处理中  人民币
        $array["count"]["one_usd"] = $this->db->select("SUM(lump_sum) as sum")->where("status",1)->get('cash_take_out_batch_tb')->row_array();//待处理  美元
        $array["count"]["one_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where("status",1)->get('cash_take_out_batch_tb')->row_array();//待处理  人民币
        return $array;
    }

    /*
     * 取出银行卡批次列表
     */

    public function get_bank_all_batch($filter, $page = false, $perPage = 10) {
        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['page_num']);//每页数量
        unset($filter['rate']);//汇率
        unset($filter['batch_number']);//批次号
        /*         * ************** */
        $this->db->select('*'); //联表查询字段
        $this->db->from('cash_bank_take_out_batch_tb');
        foreach ($filter as $k => $v) {
            if ($v==='') {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('born_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('born_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                continue;
            }
            $this->db->where($k, $v);
        }
        $obj2 = clone $this->db;
        $array['num'] = $obj2->get()->num_rows();
        $array['list'] = $this->db->order_by("born_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
       // $array["count"]["count_usd"] = $this->db->select("SUM(lump_sum) as sum")->where_in("status",array("1","3"))->get('cash_take_out_batch_tb')->row_array();//总金额 美元
      //  $array["count"]["count_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where_in("status",array("1","3"))->get('cash_take_out_batch_tb')->row_array();//总金额 人民币
        $array["count"]["three_usd"] = $this->db->select("SUM(lump_sum) as sum")->where("status",3)->get('cash_bank_take_out_batch_tb')->row_array();//已完成  美元
        $array["count"]["three_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where("status",3)->get('cash_bank_take_out_batch_tb')->row_array();//已完成  人民币
      //  $array["count"]["two_usd"] = $this->db->select("SUM(lump_sum) as sum")->where("status",2)->get('cash_bank_take_out_batch_tb')->row_array();//处理中  美元
      //  $array["count"]["two_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where("status",2)->get('cash_bank_take_out_batch_tb')->row_array();//处理中  人民币
        $array["count"]["one_usd"] = $this->db->select("SUM(lump_sum) as sum")->where("status",1)->get('cash_bank_take_out_batch_tb')->row_array();//待处理  美元
        $array["count"]["one_rmb"] = $this->db->select("SUM(lump_sum*exchange_rate) as sum")->where("status",1)->get('cash_bank_take_out_batch_tb')->row_array();//待处理  人民币
        return $array;
    }
    /*
     * 取出银行卡批次详情
     */

    public function get_bank_batch_detail($filter, $batch_num) {
        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['batch_number']);//批次号
        /*         * ************** */
        $rate = $this->db->select('exchange_rate,status')->where('id', $batch_num)->get('cash_take_out_batch_tb')->row_array();
        $this->db->select('log.*,user.name,batch.pay_type'); //联表查询字段
        $this->db->from('cash_take_out_logs as log');
        $this->db->join('users as user', 'log.uid = user.id');
        $this->db->join('cash_bank_take_out_batch_tb as batch', 'log.batch_num = batch.id', 'left');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'uid') {
                if (!is_numeric($v)) {
                    $this->db->where('user.name =', str_replace(' ','',$v));
                    continue;
                }
            }
//            if ($k == 'start') {
//                $this->db->where('log.create_time >=', $v);
//                continue;
//            }
//            if ($k == 'end') {
//                $this->db->where('log.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
//                continue;
//            }
            $this->db->where('log.' . $k, $v);
        }
        $this->db->where("take_out_type",6);//支付宝提现属性
        $this->db->order_by('status','ASC');
        $this->db->order_by('id','DESC');
        $obj2 = clone $this->db;
        $array['rate'] = $rate;
        $array['num'] = $this->db->get()->num_rows();
        $array['list'] = $obj2->get()->result_array();
        return $array;
    }
    /*
     * 取出支付宝批次详情
     */

    public function get_batch_detail($filter, $batch_num) {
        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['batch_number']);//批次号
        /*         * ************** */
        $rate = $this->db->select('exchange_rate,status')->where('id', $batch_num)->get('cash_take_out_batch_tb')->row_array();
        $this->db->select('log.*,user.name'); //联表查询字段
        $this->db->from('cash_take_out_logs as log');
        $this->db->join('users as user', 'log.uid = user.id');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'uid') {
                if (!is_numeric($v)) {
                    $this->db->where('user.name =', str_replace(' ','',$v));
                    continue;
                }
            }
//            if ($k == 'start') {
//                $this->db->where('log.create_time >=', $v);
//                continue;
//            }
//            if ($k == 'end') {
//                $this->db->where('log.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
//                continue;
//            }
            $this->db->where('log.' . $k, $v);
        }
        $this->db->where("take_out_type",2);//支付宝提现属性
        $this->db->order_by('status','ASC');
        $this->db->order_by('id','DESC');
        $obj2 = clone $this->db;
        $array['rate'] = $rate;
        $array['num'] = $this->db->get()->num_rows();
        $array['list'] = $obj2->get()->result_array();
        return $array;
    }
    
        /*
     * 取出paypal批次列表
     */

    public function get_paypal_all_batch($filter, $page = false, $perPage = 10) {
        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['page_num']);//每页数量
        unset($filter['rate']);//汇率
        unset($filter['batch_number']);//批次号
        /*         * ************** */
        $this->db->select('*'); //联表查询字段
        $this->db->from('cash_paypal_take_out_batch_tb');
        foreach ($filter as $k => $v) {
            if ($v==='') {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('born_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('born_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                continue;
            }
            $this->db->where($k, $v);
        }
        $obj2 = clone $this->db;
        $array['num'] = $obj2->get()->num_rows();
        $array['list'] = $this->db->order_by("born_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
        return $array;
    }

    /*
     * 取出paypal批次详情
     */

    public function get_paypal_batch_detail($filter, $batch_num) {
        /*         * *要注销的提交项* */
        unset($filter['page']);//分页
        unset($filter['checkboxes']);//勾选按钮
        unset($filter['batch_number']);//批次号
        /*         * ************** */
        $rate = $this->db->select('exchange_rate,status')->where('id', $batch_num)->get('cash_paypal_take_out_batch_tb')->row_array();
        $this->db->select('log.*,user.name,masspay.trade_no'); //联表查询字段
        $this->db->from('cash_take_out_logs as log');
        $this->db->join('users as user', 'log.uid = user.id');
        $this->db->join('mass_pay_trade_no as masspay', 'log.id = masspay.id', 'left');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'uid') {
                if (!is_numeric($v)) {
                    $this->db->where('user.name =', str_replace(' ','',$v));
                    continue;
                }
            }
//            if ($k == 'start') {
//                $this->db->where('log.create_time >=', $v);
//                continue;
//            }
//            if ($k == 'end') {
//                $this->db->where('log.create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
//                continue;
//            }
            $this->db->where('log.' . $k, $v);
        }
        $this->db->where("take_out_type",5);//paypal提现属性
        $this->db->order_by('status','ASC');
        $this->db->order_by('id','DESC');
        $obj2 = clone $this->db;
        $array['rate'] = $rate;
        $array['num'] = $this->db->get()->num_rows();
        $array['list'] = $obj2->get()->result_array();
        //echo $this->db->last_query();exit;
        return $array;
    }
    
    /**
     * 检查批次是否提交过，如果提交过，重新生成批次号
     */
//    public function batch_review($batch_no) {
//
//        $data = $this->db->select('status')->where('id', $batch_no)->get('cash_take_out_batch_tb')->row_array();
//        if ($data['status']==1) {
//            $data = array('status' => 2);
//            $this->db->update('cash_take_out_batch_tb', $data, array('id' => $batch_no));
//        }  else {
//            $batch_num = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
//            $data = array('batch_num' => $batch_num);
//            $this->db->update('cash_take_out_batch_tb', $data, array('id' => $batch_no));
//        }
//    }

    /**
     * 通过批次号得到总比数，总金额，拼接付款详细数据 john
     */
    public function alipay_api_data_info($batch_no) {

        $batch_info = $this->db->select('batch_num,exchange_rate,reason')->where('id', $batch_no)->get('cash_take_out_batch_tb')->row_array();
        $result['batch_no'] = $batch_info['batch_num'];

        $data = $this->db->select('id,actual_amount,account_name,card_number')->where('status', 2)->where('batch_num', $batch_no)->get('cash_take_out_logs')->result_array();

        $result['detail_data'] = '';
        $result['batch_fee'] = 0;

        $reason = lang('choose' . $batch_info['reason']);

        $count = 0;
        $flag = '';
        foreach ($data as $item) {

            if ($result['detail_data'] != '') {
                $flag = '|';
            }
            $actual_amount = sprintf("%.2f", $item['actual_amount'] * $batch_info['exchange_rate']);
            $result['detail_data'] .= $flag . $item['id'] . '^' . trim($item['card_number']) . '^' . trim($item['account_name']) . '^' . $actual_amount . '^' . $reason;
            $result['batch_fee'] += $actual_amount;
            $count++;
        }
        $result['batch_num'] = $count;
        return $result;
    }

    /**
     * 查看批次号是否已经处理完成
     */
    public function get_batch_process_info($batch_no) {
        return $this->db->from('cash_take_out_batch_tb')->where('batch_num', $batch_no)->get()->row_array();
    }

    /**
     * 修改批次为处理中
     */
    public function update_batch_pending($batch_id) {
        $this->db->where('id', $batch_id)->update('cash_take_out_batch_tb', array('status' => 2));
        return $this->db->affected_rows();
    }
    /**
     * 修改批次为处理中
     */
    public function update_bank_batch_pending($batch_id,$type) {
        $this->db->where('id', $batch_id)->update('cash_bank_take_out_batch_tb', array('status' => 2,'pay_type' => $type));
        return $this->db->affected_rows();
    }

}
