<?php

class tb_admin_after_sale_batch extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_after_sale_batch($filter, $perPage = 10) {
        $this->db->from('admin_after_sale_batch');
        $this->filter_after_sale_batch($filter);
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }
    public function filter_after_sale_batch($filter){
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
    public function get_after_sale_batch_rows($filter) {
        $this->db->from('admin_after_sale_batch');
        $this->filter_after_sale_batch($filter);
        return $this->db->count_all_results();
    }

    /*
     * 取消批次
     */

    public function cancel_batch_num($batch_id) {
        $this->db->trans_start();
        $this->db->where('id', $batch_id)->delete('admin_after_sale_batch');
        $this->db->where('batch_id',$batch_id)->update('admin_after_sale_order',array('batch_id'=>null,'status'=>1));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * 得到批次表的信息
     */
    public function get_batch_info($batch_id) {
        return $this->db->select('id,status')->from('admin_after_sale_batch')->where('id', $batch_id)->get()->row_array();
    }

    /*
     * 更新提现记录状态
     */

    public function up_log_num($where, $batch) {
        $this->db->update('cash_take_out_logs', $batch, $where);
        return $this->db->affected_rows();
    }

    /**
     * 通过批次号得到总比数，总金额，拼接付款详细数据 john
     */
    public function alipay_data_info($batch_no) {

        $batch_info = $this->db->select('batch_num')->where('id', $batch_no)->get('admin_after_sale_batch')->row_array();
        $result['batch_no'] = $batch_info['batch_num'];

        $data = $this->db->select('as_id,refund_amount,account_name,card_number')->where('status', 7)->where('batch_id', $batch_no)->get('admin_after_sale_order')->result_array();

        $result['detail_data'] = '';
        $result['batch_fee'] = 0;

        $reason = "退款";

        $count = 0;
        $flag = '';
        foreach ($data as $item) {

            if ($result['detail_data'] != '') {
                $flag = '|';
            }

            $result['detail_data'] .= $flag . $item['as_id'] . '^' . trim($item['card_number']) . '^' . trim($item['account_name']) . '^' . sprintf("%.2f",$item['refund_amount']) . '^' . $reason;
            $result['batch_fee'] +=  sprintf("%.2f",$item['refund_amount']);
            $count++;
        }
        $result['batch_num'] = $count;
        return $result;
    }

    /**
     * 查看批次号是否已经处理完成
     */
    public function get_batch_process_info($batch_no) {
        return $this->db->from('admin_after_sale_batch')->where('batch_num', $batch_no)->get()->row_array();
    }

    /**
     * 修改批次为处理中
     */
    public function update_batch_pending($batch_id) {
        $this->db->where('id', $batch_id)->update('admin_after_sale_batch', array('status' => 2));
        return $this->db->affected_rows();
    }

}
