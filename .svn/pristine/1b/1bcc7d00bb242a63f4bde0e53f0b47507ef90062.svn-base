<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Erp_customs extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->starttime = time();
        $this->load->model("m_log");
        $this->reqtData = $this->input->post();
        $this->m_log->dd_log($this->reqtData, '接收'.$this->reqtData['service']);
        $this->m_log->dd_log(base64_decode($this->reqtData['content']), '接收解码'.$this->reqtData['service']);
    }

    public function receive() {
        // request 参数，只接受 post
        switch ($this->reqtData['service']) {
            case 'cancelOrderWithShipmentIds'://取消订单成功, 一对多，支持批量
                $this->cancelOrderWithShipmentIds(base64_decode($this->reqtData['content']));
                break;
            case 'syncTrackNumberWithBatch'://回写追踪号,多对多，支持批量
                $this->syncTrackNumberWithBatch(base64_decode($this->reqtData['content']));
                break;
            case 'pubSyncDeliveryInfoWithBatch'://发货完成同步,多对多，支持批量
                $this->pubSyncDeliveryInfoWithBatch(base64_decode($this->reqtData['content']));
                break;
            case 'notAllowedCancelWithShipmentId'://不允许取消订单, 一对多，支持批量
                break;
            case 'subStockChangeInfoToPlat'://入库库存异动反馈,一 对多
                break;
            default:
                break;
        }
    }

    //回写追踪号,多对多，支持批量
    public function syncTrackNumberWithBatch($param) {
        $array = $this->object_to_array(json_decode($param));
        foreach ($array['orderList'] as $value) {
            $return[]=['succeed'=>'true','orderCode'=>$value['orderCode'],'errorMsg'=>''];
        }
        $this->return_echo(1,$return);
    }

    //取消订单成功, 一对多，支持批量
    public function cancelOrderWithShipmentIds($param) {
        
    }

    //发货完成同步,多对多，支持批量
    public function pubSyncDeliveryInfoWithBatch($param) {
        $this->load->model('tb_empty');
        $array = $this->object_to_array(json_decode($param));
        $goods_info=$this->db->from('trade_freight')->get()->result_array();
        foreach ($goods_info as $ss) {
            $wuliu[$ss['company_shortname']]=$ss['company_name'];
        }
//        foreach ($array['orderList'] as $value) {
//            $houzui = $this->tb_empty->get_table_ext($value['orderCode']);
//            $table_list[$houzui]['order_status'][] = ['status' => '4','order_id' =>$value['orderCode']]; //订单状态
//            $table_list[$houzui]['freight_info'][] = ['order_id' =>$value['orderCode'],'freight_info' => $wuliu[$value['companyCode']] . '|' . $value['companyNumber']]; //订单详情
//            $order_log[] = ['operator_id' => 0, 'order_id' => $value['orderCode'], 'oper_code' => 102, 'statement' => $wuliu[$value['companyCode']] . ' ' . $value['companyNumber'] . '->海关发货通知', 'update_time' => date("Y-m-d H:i:s", time()),];
//            $return[]=['succeed'=>'true','orderCode'=>$value['orderCode'],'errorMsg'=>''];
//        }
//        foreach ($table_list as $key => $v) {
//            $this->db->update_batch('trade_orders'.$key, $v['order_status'], 'order_id');
//            $this->db->update_batch('trade_orders_info'.$key, $v['freight_info'], 'order_id');
//        }
//        $this->db->insert_batch('trade_orders_log',$order_log);
        foreach ($array['orderList'] as $value) {
            $houzui=$this->tb_empty->get_table_ext($value['orderCode']);
            $status = $this->db->select('status')->from('trade_orders'.$houzui)->where('order_id',$value['orderCode'])->get()->row_array();
            if($status['status']=='1'){
                $this->db->where('order_id',$value['orderCode'])->update('trade_orders'.$houzui,['status'=>'4']);//订单状态
                $this->db->where('order_id',$value['orderCode'])->update('trade_orders_info'.$houzui,['freight_info'=>$wuliu[$value['companyCode']] . '|' . $value['companyNumber']]);//订单详情
                $this->db->insert('trade_orders_log', array('operator_id' => 0,'order_id' => $value['orderCode'],'oper_code' => 102,'statement' => $wuliu[$value['companyCode']] . ' ' . $value['companyNumber'] . '->海关发货通知','update_time' => date("Y-m-d H:i:s",time()),));
                $return[]=['succeed'=>'true','orderCode'=>$value['orderCode'],'errorMsg'=>''];
            }  else {
                $return[]=['succeed'=>'false','orderCode'=>$value['orderCode'],'errorMsg'=>'订单状态异常'];
            }
        }
        $this->return_echo(1,$return);
    }

    //返回输出
    private function return_echo($num,$body) {
        $is = $num > 0 ? 'true' : 'false';
        $error = $num > 0 ? 'success' : 'failed';
        echo json_encode(['isSuccess' => $is, 'body' =>(string)json_encode($body), 'error' => $error, 'ts' => (string)(time()- $this->starttime)]);
    }

    /**
     * @author 脚本之家
     * @date 2013-6-21
     * @todo 将对象转换成数组
     * @param unknown_type $obj
     * @return unknown
     */
    function object_to_array($obj) {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

}
