<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class split_order extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->_viewData['title'] = lang('split_order');
		parent::index('admin/');
	}


	/***拆分订单***/
	public function submit()
	{

        $this->load->model('tb_trade_orders');
		$this->load->model('m_split_order');
		$order_id = $this->input->post('order_id');


		//订单号不能为空
		if (trim($order_id) == '') {
			echo json_encode(array('success' => false, 'msg' => lang('order_id_not_null')));
			exit();
		}

		//没有此订单号
//		$res = $this->db->query("select * from trade_orders WHERE order_id = '$order_id'")->row_array();
		$res = $this->tb_trade_orders->get_one("*",["order_id"=>$order_id]);
		if (empty($res)) {
			echo json_encode(array('success' => false, 'msg' => lang('order_not_exits')));
			exit();
		}

		$component_id = $res['order_id'];

		//如果该订单拆过单
		if ($res['order_prop'] == 2) {
//			$children = $this->db->query("select order_id,status from trade_orders WHERE order_prop = '1' AND attach_id ='$component_id'")->result_array();
            $children = $this->tb_trade_orders->get_list("order_id,status",
                ["order_prop"=>'1',"attach_id"=>$component_id]);
			foreach ($children as $item) {
				if ($item['status'] != 3) {
					//子订单状态异常
					echo json_encode(array('success' => false, 'msg' => lang('item_status_exception')));
					exit();
				}
			}

			//把原来拆分的子订单删除,重新拆单
			foreach ($children as $item) {
				$item_order_id = $item['order_id'];
//				$this->db->query("delete from trade_orders WHERE order_id = '$item_order_id'");
                $this->tb_trade_orders->delete_one(["order_id"=>$item_order_id]);
			}

			//仓库列表
			$new_arr = $this->m_split_order->split_goods_list($res['goods_list']);
			//拆分goods_list
			$store_list = $this->m_split_order->get_store_list($new_arr);

			if(count($store_list) > 1)
			{
				$this->m_split_order->do_split_order($res,$store_list,$new_arr);
//				$children = $this->db->query("select order_id from trade_orders WHERE order_prop = '1' AND attach_id = '$component_id'")->result_array();
                $children = $this->tb_trade_orders->get_list("order_id",
                    ["order_prop"=>'1',"attach_id"=>$component_id]);
				echo json_encode(array('success' => true, 'result'=>$children,'msg' => lang('the_split_order_success')));
				exit();
			}
			else
			{
				echo json_encode(array('success' => false, 'msg' => lang('this_order_not_need_split_order')));
				exit();
			}
		}


		//没有拆单过的订单
		if($res['order_prop'] == 0)
		{
			//待发货状态的才可以拆单
			if($res['status'] == 3)
			{
				//仓库列表
				$new_arr = $this->m_split_order->split_goods_list($res['goods_list']);
				//拆分goods_list
				$store_list = $this->m_split_order->get_store_list($new_arr);
				if(count($store_list) > 1)
				{
					$this->m_split_order->do_split_order($res,$store_list,$new_arr);
//					$children = $this->db->query("select order_id from trade_orders WHERE order_prop = '1' AND attach_id = '$component_id'")->result_array();
                    $children = $this->tb_trade_orders->get_list("order_id",
                        ["order_prop"=>'1',"attach_id"=>$component_id]);
					echo json_encode(array('success' => true, 'result'=>$children,'msg' => lang('the_split_order_success')));
					exit();
				}
				else
				{
					echo json_encode(array('success' => false, 'msg' => lang('this_order_not_need_split_order')));
					exit();
				}
			}
			else
			{
				echo json_encode(array('success' => false, 'msg' => lang('only_wait_delivery_can_split_order')));
				exit();
			}
		}
	}
}