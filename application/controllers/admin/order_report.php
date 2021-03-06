<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Order_report extends MY_Controller {

	public function __construct() {
		parent::__construct();
		//$this->m_global->checkPermission('Order_report',$this->_adminInfo);
		$this->load->model('m_admin_user');
		$this->load->model('m_trade');
        $this->load->model('tb_trade_orders');
        $this->load->model('tb_trade_orders_goods');
		$this->load->model('tb_trade_orders_doba_order_info');
		$this->load->model('tb_trade_order_doba_log');
		$this->load->model('m_log');
		$this->load->model('m_group');
        $this->load->model('m_erp');
	}

	public function index() {
		$searchData = $this->input->get();
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['month'] = isset($searchData['month'])?$searchData['month']:'';
		$this->_viewData['title'] = lang('order_report');
		$this->_viewData['searchData'] = $searchData;
		$data = $this->process($searchData);
		$this->_viewData['results'] = $data;
		$month[] = date('Y-m');
		$month[] = date('Y-m',strtotime('-1 month'));
		$month[] = date('Y-m',strtotime('-2 month'));
		$month[] = date('Y-m',strtotime('-3 month'));
		$month[] = date('Y-m',strtotime('-4 month'));
		$month[] = date('Y-m',strtotime('-5 month'));

		$this->_viewData['months'] = $month;
		parent::index('admin/');
	}

	/**
	 *检验订单id
	 */
	public function checkOrderId(){
		$order_id=$this->input->post('order_id');
		if(trim($order_id)==''){
			echo json_encode(array('success'=>false,'msg'=>lang('orderid_not_null')));
			exit();
		}
	}

	/**
	 *检验交易id
	 */
	public function checkTxnId(){
		$txn_id=$this->input->post('txn_id');
		if(trim($txn_id)==''){
			echo json_encode(array('success'=>false,'msg'=>lang('txnid_not_null')));
			exit();
		}
	}

	/**
	 * 手动添加订单号
	 */
	public function checkData(){
		$order_id=$this->input->post('order_id');
		$txn_id=$this->input->post('txn_id');
		if(trim($order_id)==''){
			echo json_encode(array('success'=>false,'msg'=>lang('orderid_not_null')));
			exit();
		}
		if(trim($txn_id)==''){
			echo json_encode(array('success'=>false,'msg'=>lang('txnid_not_null')));
			exit();
		}
		$orderInfo=$this->tb_trade_orders->getOrderInfo($order_id,array('status','order_prop','order_type','attach_id','shopkeeper_id'));
        $status = empty($orderInfo)?false:true;
		if($status){
			if(intval($orderInfo['status']) != 2  && intval($orderInfo['status']) != 99 && intval($orderInfo['status']) != 100){
				echo json_encode(array('success'=>false,'msg'=>lang('orderid_not_exits')));
				exit();
			}
		}else{
			echo json_encode(array('success'=>false,'msg'=>lang('orderid_not_exits')));
			exit();
		}
        //修复条件：零售订单不允许修改P开头的，升级和代品券订单不允许修改C开头的，shopkeeper_id 不为0，则是零售订单
        if($orderInfo['shopkeeper_id'] == 0 && $orderInfo['order_prop'] == 1){
            echo json_encode(array('success'=>false,'msg'=>lang('not_Porder')));
            exit();
        }
        if($orderInfo['shopkeeper_id'] != 0 && $orderInfo['order_prop'] == 2){
            echo json_encode(array('success'=>false,'msg'=>lang('not_Porder')));
            exit();
        }


		$this->m_log->ordersRollbackLog($order_id,$txn_id);
		$Nid = $this->db->insert_id();
		if($Nid > 0){
                    //插入到trade_orders_log
                    $remark = "手动添加交易号";
                    $this->m_trade->add_order_log($order_id,150,$remark,$this->_adminInfo['id']);
                    echo json_encode(array('success'=>true,'msg'=>lang('orderid_ture')));
                    exit();
		}else{
                    echo json_encode(array('success'=>false,'msg'=>lang('not_repeat_insert')));
                    exit();
		}

	}

	public function process($filter){
        $start_time_total = microtime(true);
		//国家地区
		$country_area_map = array(
				156 => array("156"),
				410 => array("410"),
				840 => array("840", "000"),
				344 => array("344", "158", "446", "704", "418", "116", "764", "104", "458", "702", "360", "096", "608", "626"),
		);
		//产品
		$goods_arr = array(
				'NOPAL'=>array('cate_id'=>array(85,86,87)),
				'JBB'=>array('cate_id'=>array(55,56,57)),
				'Kelly'=>array('cate_id'=>array(52,53,54)),
				'熙媛'=>array('cate_id'=>array(58,59,60)),
				'紅酒'=>array('cate_id'=>array(201,200,199)),
				'磁能产品'=>array('goods_list'=>array('64580312','04373851','77998297','12802211')),
				'韩国99美白'=>array('goods_list'=>array('23422805','62261180','27123768','48771923')),
				'韩国香皂'=>array('goods_list'=>array('35071627')),
				'韩国保健品套餐'=>array('goods_list'=>array('79537698','45099126','76042416')),
				'韩国迷你装净水器'=>array('goods_list'=>array('29341368')),
				'韩国家庭装净水器'=>array('goods_list'=>array('61194623')),
				'韩国减肥营养粉'=>array('goods_list'=>array('79646329','45896682')),
				'韩国植物软胶囊'=>array('goods_list'=>array('18873959','48438394')),
				'Insight Eye'=>array('goods_list'=>array('12790455')),
				'Ginseng'=>array('goods_list'=>array('06132860')),
				'Dr. Cell'=>array('goods_list'=>array('55940738')),
				'Seng Seng Dan'=>array('goods_list'=>array('37536265')),
				'Grace of Graviola'=>array('goods_list'=>array('54815679')),
				'Joint CMO'=>array('goods_list'=>array('81413384')),
		);
		$result = array();
		if($goods_arr) {

            foreach ($goods_arr as $goods_name => $goods) {
                $start_time = microtime(true);
                $report = array();
                if (isset($goods['cate_id'])) {
                    $str = implode(',', $goods['cate_id']);
                    $where_str = "cate_id in ($str)";
                } else if (isset($goods['goods_list'])) {
                    $str = "";
                    //goods_sn_main加单引号才能用上索引
                    foreach($goods['goods_list'] as $k=>$v)
                    {
                        if($str)
                        {
                            $str .= ",";
                        }
                        $str .= "'".$v."'";
                    }
                    $where_str = "goods_sn_main in ($str)";
                }

                $sql = "";
                foreach ($country_area_map as $country) {
                    $where_country = "";
                    //area加单引号才能用上索引
                    foreach($country as $k=>$v)
                    {
                        if($where_country)
                        {
                            $where_country .= ",";
                        }
                        $where_country .= "'".$v."'";
                    }
                    foreach (array(array('4', '5', '6'), array('3')) as $status)
                    {
                        $where_status = implode(',', $status);
                        if($sql)
                        {
                            $sql .= " union all ";
                        }
                        $sql .= "select count(order_id) num from trade_orders tos
							where tos.order_id in (select order_id from trade_orders_goods
							where $where_str group by order_id)
							and tos.status in($where_status) and tos.area in($where_country)";
                        /*if(isset($filter['start']) && $filter['start'] ){
                            $start_date = date("Y-m-d H:i:s", strtotime($filter['start']));
                            $sql .= " and tos.created_at>='".$start_date."'";
                        }
                        if(isset($filter['end']) && $filter['end'] ){
                            $end_date = date("Y-m-d H:i:s", strtotime($filter['end']) + 86400 - 1);
                            $sql .= " and tos.created_at<='".$end_date."'";
                        }*/
                        if (isset($filter['month']) && $filter['month']) {
                            $first = date('Y-m-01 00:00:00', strtotime($filter['month']));
                            $last = date('Y-m-t 23:59:59', strtotime($filter['month']));
                            $sql .= " and tos.created_at>='" . $first . "' and tos.created_at<='" . $last . "'";
                        }
                    }
                }
                $deliver = $this->db->query($sql)->result_array();
                foreach($deliver as $v)
                {
                    $report[] = $v['num'];
                }
                $result[$goods_name] = $report;
//                $end_time = microtime(true);
//                $this->m_debug->log($end_time-$start_time);
//                $this->m_debug->log($sql);
            }
        }
//        $end_time_total = microtime(true);
//        $this->m_debug->log($end_time_total-$start_time_total);
        return $result;
	}

	public function export(){
		$searchData = $this->input->get();
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$data = $this->process($searchData);
		$area = $this->m_trade->get_area_map($this->_curLanguage);
		$fields = array('',$area[156],$area[344],$area[840],lang('zone_area_hkg_mac_twn_asean'));
		$filename = 'Order_report'.date('Y-m-d',time()).'_'.time();

		require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

		$objExcel = new PHPExcel();
		//设置属性
		$objExcel->getProperties()->setCreator("john");
		$objExcel->setActiveSheetIndex(0);

		//表头
		$objExcel->getActiveSheet()->mergeCells('B1:C1');
		$objExcel->getActiveSheet()->setCellValue('b1',  $fields[1]);
		$objExcel->getActiveSheet()->mergeCells('D1:E1');
		$objExcel->getActiveSheet()->setCellValue('D1',  $fields[2]);
		$objExcel->getActiveSheet()->mergeCells('F1:G1');
		$objExcel->getActiveSheet()->setCellValue('F1',  $fields[3]);
		$objExcel->getActiveSheet()->mergeCells('H1:I1');
		$objExcel->getActiveSheet()->setCellValue('H1',  $fields[4]);

		$objExcel->getActiveSheet()->setCellValue('b2',  lang('order_status_4'));
		$objExcel->getActiveSheet()->setCellValue('c2',  lang('order_status_3'));
		$objExcel->getActiveSheet()->setCellValue('d2',  lang('order_status_4'));
		$objExcel->getActiveSheet()->setCellValue('e2',  lang('order_status_3'));
		$objExcel->getActiveSheet()->setCellValue('f2',  lang('order_status_4'));
		$objExcel->getActiveSheet()->setCellValue('g2',  lang('order_status_3'));
		$objExcel->getActiveSheet()->setCellValue('h2',  lang('order_status_4'));
		$objExcel->getActiveSheet()->setCellValue('i2',  lang('order_status_3'));
		$i=1;
		if($data)foreach($data as $k=>$v) {


			$u1=$i+2;
			/*----------写入内容-------------*/
			$objExcel->getActiveSheet()->setCellValue('a'.$u1, $k);
			$objExcel->getActiveSheet()->setCellValue('b'.$u1, $v[0]);
			$objExcel->getActiveSheet()->setCellValue('c'.$u1, $v[1]);
			$objExcel->getActiveSheet()->setCellValue('d'.$u1, $v[2]);
			$objExcel->getActiveSheet()->setCellValue('e'.$u1, $v[3]);
			$objExcel->getActiveSheet()->setCellValue('f'.$u1, $v[4]);
			$objExcel->getActiveSheet()->setCellValue('g'.$u1, $v[5]);
			$objExcel->getActiveSheet()->setCellValue('h'.$u1, $v[6]);
			$objExcel->getActiveSheet()->setCellValue('i'.$u1, $v[7]);
			$i++;
		}
		$sum_0 = $sum_1 = $sum_2 = $sum_3 = $sum_4 = $sum_5 = $sum_6 = $sum_7 = 0;
		foreach ($data as $key=>$item) {
			$sum_0 = $sum_0+$item[0];
			$sum_1 = $sum_1+$item[1];
			$sum_2 = $sum_2+$item[2];
			$sum_3 = $sum_3+$item[3];
			$sum_4 = $sum_4+$item[4];
			$sum_5 = $sum_5+$item[5];
			$sum_6 = $sum_6+$item[6];
			$sum_7 = $sum_7+$item[7];
		}
		$u1=$i+2;
		$objExcel->getActiveSheet()->getStyle('a'.$u1)->getFont()->setBold(true);
		$objExcel->getActiveSheet()->setCellValue('a'.$u1, 'SUM(合计)');
		$objExcel->getActiveSheet()->setCellValue('b'.$u1, $sum_0);
		$objExcel->getActiveSheet()->setCellValue('c'.$u1, $sum_1);
		$objExcel->getActiveSheet()->setCellValue('d'.$u1, $sum_2);
		$objExcel->getActiveSheet()->setCellValue('e'.$u1, $sum_3);
		$objExcel->getActiveSheet()->setCellValue('f'.$u1, $sum_4);
		$objExcel->getActiveSheet()->setCellValue('g'.$u1, $sum_5);
		$objExcel->getActiveSheet()->setCellValue('h'.$u1, $sum_6);
		$objExcel->getActiveSheet()->setCellValue('i'.$u1, $sum_7);

		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	/** 回滚订单状态 等待收货->等待发货 **/
	public function order_rollback(){
		$order_id = trim($this->input->post('order_id'));
		$remark = $this->input->post('remark');

		//订单号不能空
		if(trim($order_id) == '')
		{
			echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
			exit;
		}

		// 查询订单是否存在
		$one = $this->tb_trade_orders->findOne($order_id);
		if(!empty($one)){
			if($one['status'] != '4')
			{
				echo json_encode(array('success'=>false,'msg'=>lang('order_not_accord_with')));
				exit;
			}
		}else{
			echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
			exit;
		}

		//备注不能为空
		if(trim($remark) == '')
		{
			echo json_encode(array('success'=>false,'msg'=>lang('pls_input_reson')));
			exit;
		}

		//事务开始
		$this->db->trans_begin();

		//更改订单状态为待发货,清空字段freight_info，deliver_time，并且插入备注信息
		$data = array(
				'status' => Order_enum::STATUS_SHIPPING,
				'freight_info' => null,
				'deliver_time' => null
		);
//		$this->db->where('order_id', $order_id)->update('trade_orders',$data);
        $this->tb_trade_orders->update_one(['order_id'=>$order_id],$data);
		$this->db->insert('trade_order_remark_record',array(
				'order_id'=>$order_id,
				'type'=>'1',
				'remark'=>$remark,
				'admin_id'=>$this->_adminInfo['id']
		));

		//插入到trade_orders_log
		$ret = $this->m_trade->add_order_log($order_id,104,$remark,$this->_adminInfo['id']);
		if(!$ret){
			exit;
		}

        //组装数组,添加到订单推送表
        $this->load->model('m_erp');
        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data']['order_id'] = $order_id;
        $insert_data['data']['status'] = Order_enum::STATUS_SHIPPING;
        $insert_data['data']['logistics_code'] = -1;
        $insert_data['data']['tracking_no'] = "";
        $insert_data['data']['deliver_time'] = "";

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

        $insert_data_remark = array();
        $insert_data_remark['oper_type'] = 'remark';
        $insert_data_remark['data']['order_id'] = $order_id;
        $insert_data_remark['data']['remark'] = $remark;
        $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
        $insert_data_remark['data']['recorder'] = "$this->_adminInfo['id']"; //操作人
        $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(array('success'=>false,'msg'=>lang('transaction_rollback')));
			exit;
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
			exit;
		}
	}

    /**
     * @desc 取消订单的状态还原
     * @auth tico.wong
     * @date 2017-07-11
     */
    public function order_cancel_rollback(){
        $order_id = trim($this->input->post('order_id'));
        $remark = $this->input->post('remark');
        $roll_status = $this->input->post('status');
        //订单号不能空
        if(trim($order_id) == '')
        {
            echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
            exit;
        }
        // 查询订单是否存在
        $one = $this->tb_trade_orders->findOne($order_id);
        if(!empty($one)){
            if($one['status'] != '99')
            {
                echo json_encode(array('success'=>false,'msg'=>lang('order_not_accord_with')));
                exit;
            }
        }else{
            echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
            exit;
        }
        //备注不能为空
        if(trim($remark) == '')
        {
            echo json_encode(array('success'=>false,'msg'=>lang('pls_input_reson')));
            exit;
        }

        //如果未选回滚状态，自动取回滚后的订单状态，并将判断条件加入remark
        if(!$roll_status)
        {
            $roll_status = Order_enum::STATUS_CHECKOUT;
            if ($one['receive_time'] && $one['receive_time'] != '0000-00-00 00:00:00')
            {
                $roll_status = Order_enum::STATUS_COMPLETE;
                $remark .= " receive_time:".$one['receive_time'];
            }
            elseif ($one['deliver_time'] && $one['deliver_time'] != '0000-00-00 00:00:00')
            {
                $roll_status = Order_enum::STATUS_SHIPPED;
                $remark .= " deliver_time:".$one['deliver_time'];
            }
            elseif ($one['pay_time'] && $one['pay_time'] != '0000-00-00 00:00:00')
            {
                $roll_status = Order_enum::STATUS_SHIPPING;
                $remark .= " pay_time:".$one['pay_time'];
            }
        }

//        var_dump($roll_status);
//        var_dump($one);exit;
        //事务开始
        $this->db->trans_begin();
        //更改订单状态
        $data = array(
            'status' => $roll_status
        );
        $this->tb_trade_orders->update_one(['order_id'=>$order_id],$data);
        //插入备注信息
        $this->db->insert('trade_order_remark_record',array(
            'order_id'=>$order_id,
            'type'=>'1',
            'remark'=>$remark,
            'admin_id'=>$this->_adminInfo['id']
        ));
        //插入到trade_orders_log
        $ret = $this->m_trade->add_order_log($order_id,104,$remark,$this->_adminInfo['id']);
        if(!$ret){
            exit;
        }
        //组装数组,添加到订单推送表
        $this->load->model('m_erp');
        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data']['order_id'] = $order_id;
        $insert_data['data']['status'] = $roll_status;
        $insert_data['data']['logistics_code'] = -1;
        $insert_data['data']['tracking_no'] = "";
        $insert_data['data']['deliver_time'] = "";
        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

        $insert_data_remark = array();
        $insert_data_remark['oper_type'] = 'remark';
        $insert_data_remark['data']['order_id'] = $order_id;
        $insert_data_remark['data']['remark'] = $remark;
        $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
        $insert_data_remark['data']['recorder'] = $this->_adminInfo['id']." "; //操作人
        $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());
        $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('success'=>false,'msg'=>lang('transaction_rollback')));
            exit;
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('success'=>true,'msg'=>lang('update_success')." -> ".$roll_status));
            exit;
        }
    }


	/**
	 * 手动添加daba订单推送
	 */
	public function add_doba()
    {
        $order_id = trim($this->input->post('order_id'));
        if (trim($order_id) == '') {
            echo json_encode(array('success' => false, 'msg' => lang('orderid_not_null')));
            exit();
        }
        // 查询订单是否存在
        $one = $this->tb_trade_orders->findOne($order_id);
        if (empty($one)) {
            echo json_encode(array('success' => false, 'msg' => lang('order_not_exits')));
            exit;
        }
        //查询订单是否是已推送的doba订单
        $is_doba = $this->tb_trade_orders_doba_order_info->findOne($order_id);
        if (!empty($is_doba)) {
            echo json_encode(array('success' => false, 'msg' => lang('admin_trade_isdoba')));
            exit;
        }
        //检验订单的状态
        $one = $this->tb_trade_orders->findOne($order_id);
        $status = $this->tb_trade_order_doba_log->findOne($order_id);
        //异常订单需处理
        if (!empty($status)) {
            if ($one['status'] == '111' && $status['status'] == '2') {
                //修改状态为1
                $this->db->where('order_id', $order_id)->update('trade_order_doba_log', array('status' => '1'));
                echo json_encode(array('success' => true, 'msg' => lang('orderid_ture')));
                exit;
            }
         }
        echo json_encode(array('success'=>false,'msg'=>lang('admin_trade_doba_nopush')));
	}

    /**
     *恢复普通订单状态，取消或退货恢复到未取消的状态
	 * User: Ckf
	 * Date：2016/12/31
     */
	public function order_recovery(){
        if($this->input->is_ajax_request()){
            $attr = $this->input->post();
            $remark = trim($attr['remark']);
            $order_id = trim($attr['order_id']);
            $is_commission = $attr['is_commission'];
            $status = $attr['status'];

            //订单号不能空
            if($order_id == '')
            {
                echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
                exit;
            }

            // 查询订单是否存在
            $one = $this->tb_trade_orders->getOrderInfo($order_id,array('status','customer_id','goods_amount_usd','score_year_month','order_profit_usd','shopkeeper_id','pay_time','txn_id','freight_info'));
            if(!empty($one)){
                //取消或退货的零售订单才要恢复
                if(($one['status'] != '99' && $one['status'] != '98') || $one['shopkeeper_id'] == 0)
                {
                    echo json_encode(array('success'=>false,'msg'=>lang('order_not_recovery')));
                    exit;
                }
            }else{
                echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
                exit;
            }
            //备注不能为空
            if($remark == '')
            {
                echo json_encode(array('success'=>false,'msg'=>lang('pls_input_reson')));
                exit;
            }
            //恢复到等待收货和已完成时判断该订单是否有快递信息
            if(($status == 4 || $status == 6) && $one['freight_info'] ==''){
                echo json_encode(array('success'=>false,'msg'=>lang('admin_order_not_logistics')));
                exit;
            }

            //事务开始
            $this->db->trans_begin();

            //更改订单状态
//            $pay_time = empty($one['pay_time'])?date('Y-m-d H:i:s',time()):$one['pay_time'];
			if(empty($one['pay_time']) || $one['pay_time'] == '0000-00-00 00:00:00'){
				$pay_time = date('Y-m-d H:i:s',time());
			}else{
				$pay_time = $one['pay_time'];
			}

            $txn_id = empty($one['txn_id'])?$order_id:$one['txn_id'];
//            $this->db->where('order_id', $order_id)->update('trade_orders',array('status' => $status,'pay_time' => $pay_time,'txn_id' => $txn_id));
            $this->tb_trade_orders->update_one(['order_id'=>$order_id],['status' => $status,'pay_time' => $pay_time,'txn_id' => $txn_id]);
			//恢复订单后库存变动
//            $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$order_id)
//                ->from('trade_orders_goods')->get()->result_array();
            $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                ['order_id'=>$order_id]);
            $this->m_group->update_goods_number($goods, $order_id);

            $this->db->insert('trade_order_remark_record',array(
                'order_id'=>$order_id,
                'type'=>'1',
                'remark'=>$remark,
                'admin_id'=>$this->_adminInfo['id']
            ));

            //补发佣金
            if($is_commission == 1){
                $insert_data['oid'] = $order_id;
				$insert_data['uid'] = $one['customer_id'];
				$insert_data['order_amount_usd'] = $one['goods_amount_usd'];
				$insert_data['order_profit_usd'] = $one['order_profit_usd'];
				$insert_data['order_year_month'] = $one['score_year_month'];

                $this->db->insert('new_order_trigger_queue',$insert_data);
            }
            //插入到trade_orders_log
            $this->m_trade->add_order_log($order_id,111,'状态恢复status：'.$status.','.$remark,$this->_adminInfo['id']);

            //组装数组,添加到订单推送表
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = $status;
            $insert_data['data']['txn_id'] = $txn_id;
            $insert_data['data']['pay_time'] = $pay_time;

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

            $insert_data_remark = array();
            $insert_data_remark['oper_type'] = 'remark';
            $insert_data_remark['data']['order_id'] = $order_id;
            $insert_data_remark['data']['remark'] = $remark;
            $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
            $insert_data_remark['data']['recorder'] = $this->_adminInfo['id']; //操作人
            $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('success'=>false,'msg'=>lang('transaction_rollback')));
                exit;
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
                exit;
            }
        }
	}

}