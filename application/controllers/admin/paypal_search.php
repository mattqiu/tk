<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Paypal_search extends MY_Controller {


    public function __construct() {
        parent::__construct();
		$this->load->model('m_admin_user');
    }

    public function index() {
        $this->_viewData['title'] = lang('admin_paypal_failure_search');
        parent::index('admin/');
    }

	/** Paypal退款，撤销订单 */
	public function do_search(){

		include_once APPPATH . 'third_party/paypal/CallerService.php';
		$nvpStr = '';

		$startDateStr=$_GET['startDateStr'];
		$endDateStr=$_GET['endDateStr'];
		$transactionID=urlencode($_GET['transactionID']);
		if(isset($startDateStr)) {
			$start_time = strtotime($startDateStr);
			$iso_start = date('Y-m-d\T00:00:00\Z',  $start_time);
			$nvpStr="&STARTDATE=$iso_start";
		}

		if(isset($endDateStr)&&$endDateStr!='') {
			$end_time = strtotime($endDateStr);
			$iso_end = date('Y-m-d\T24:00:00\Z', $end_time);
			$nvpStr.="&ENDDATE=$iso_end";
		}

		if($transactionID!=''){

			$nvpStr=$nvpStr."&TRANSACTIONID=$transactionID";
		}

		//$nvpStr=$nvpStr."&TRANSACTIONCLASS=Refund";
		$nvpStr=$nvpStr."&TRANSACTIONCLASS=Reversal";
		$nvpStr=$nvpStr."&STATUS=Success";

		$resArray=hash_call("TransactionSearch",$nvpStr);
		if($resArray['success'] === FALSE){
			die(json_encode($resArray));
		}

		$ack = strtoupper($resArray["ACK"]);

		if($ack =="SUCCESS" || $ack=="SUCCESSWITHWARNING"){
			//var_dump($resArray);
			$count=0;
			//counting no.of  transaction IDs present in NVP response arrray.
			while (isset($resArray["L_TRANSACTIONID".$count])){
				$count++;
			}
			$ID=0;
			while ($count>0) {
				$transactionID2    = $resArray["L_TRANSACTIONID".$ID];
				$payerName  = $resArray["L_NAME".$ID];
				$type = $resArray["L_TYPE".$ID];
				/*$timeStamp = $resArray["L_TIMESTAMP".$ID];
				$amount  = $resArray["L_AMT".$ID];
				$status  = $resArray["L_STATUS".$ID];*/
				if($payerName !== 'PayPal'){
					$nvpStr2 = "&TRANSACTIONID=$transactionID2";
					$resArray2=hash_call("gettransactionDetails",$nvpStr2);
					if($resArray2['success'] === FALSE){
						die(json_encode($resArray2));
					}
					if(strtoupper($resArray2['ACK']) == 'SUCCESS'){
						//var_dump($resArray2);
						$email = $resArray2['EMAIL'];
						$order_id = $resArray2['INVNUM'];
						$note = $resArray2['NOTE'];
						$amount = $resArray2['AMT'];
						$create_time = $resArray2['ORDERTIME'];
						$txn_id = $resArray2['PARENTTRANSACTIONID'];
						$this->m_admin_user->insertPayPalRefund($order_id,$email,$txn_id,$amount,$note,$create_time,$payerName,$type);
					}
				}
				$count--; $ID++;
			}
		}
		die(json_encode(array('success'=>TRUE,'msg'=>lang('update_success'))));
	}

	/** 比对refund订单 */
	public function do_match(){
		$this->db->trans_start();
		$logs = $this->db->where("type",'Refund')->get('mall_orders_paypal_refund ')->result_array();
		foreach($logs as $log){
			$count = $this->db->from('one_direct_orders')->where('order_id',$log['order_id'])->count_all_results();
			if( $count > 0 ){
				$desc = '已发奖励';
			}else{
				$desc = '未发奖励';
			}
			$this->db->where('id',$log['id'])->update('mall_orders_paypal_refund',array('status'=>$desc));
		}
		$this->db->trans_complete();
	}

	/** 比对ress订单 */
	public function do_match2(){
		$this->db->trans_start();
		$logs = $this->db->where("type",'Reversal')->get('mall_orders_paypal_refund ')->result_array();
		$this->load->model('M_order','m_order');
		$this->load->model('m_user');
        $this->load->model("tb_trade_orders");
		foreach($logs as $log){
			$act = $this->m_order->getPayAction($log['order_id']);
			if($act['table'] === 'trade_orders'){
//                $order = $this->db->from($act['table'])->where($act['field'],$log['order_id'])->get()->row_array();
                $order = $this->tb_trade_orders->get_one("customer_id",[$act['field']=>$log['order_id']]);
				$userInfo = current($this->m_user->getInfo($order['customer_id']));
				if($userInfo['status'] != 4 ){
					$desc = '不是公司账户';
				}else{
					$desc = '公司账户';
				}
			}else if($act['table'] === 'user_upgrade_order'){
                $order = $this->db->from($act['table'])->where($act['field'],$log['order_id'])->get()->row_array();
				$userInfo = current($this->m_user->getInfo($order['uid']));
				if($userInfo['status'] != 4 ){
					$desc = '不是公司账户';
				}else{
					$desc = '公司账户';
				}
			}else{
                $order = $this->db->from($act['table'])->where($act['field'],$log['order_id'])->get()->row_array();
				if($order['status'] == 2 ){
					$desc = '成功';
				}else{
					$desc = '失败';
				}
			}
			$this->db->where('id',$log['id'])->update('mall_orders_paypal_refund',array('status'=>$desc));
		}
		$this->db->trans_complete();
	}

}