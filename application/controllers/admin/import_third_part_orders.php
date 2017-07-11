<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Import_third_part_orders extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('import_third_part_orders',$this->_adminInfo);
    }

    public function index() {

        if(isset($_FILES) && isset($_FILES["orders_csv"])){
            $dir = realpath('upload').'/third_part_orders_csv';
            if (is_dir($dir) == false) {
                mkdir($dir, 0777);//在页面目录下要新建相应文件夹用来保存上传csv文件
            }

            /*存储csv文件*/
            $csv_file_path_name = $dir.'/'.time().'_'.$_FILES["orders_csv"]["name"];
            move_uploaded_file($_FILES["orders_csv"]["tmp_name"], $csv_file_path_name);

            /*读取csv文件内容到数组*/
            $result_arr = input_csv($csv_file_path_name);

            $this->load->model('M_order');
            foreach($result_arr as $v){
                
                $v['order_id'] = trim($v['order_id']);
                if($v['order_id']){
                    $v['order_id'] = 'O-'.$v['order_id'];
                    $error_code = $this->M_order->saveOneDirectMallOrders($v);
                }
            }
        }

        $this->_viewData['title'] = lang('import_third_part_orders');
        parent::index('admin/');
    }

    public function onedirect_cancel(){

        $this->load->model('o_order_cancel');
        $this->load->model('tb_one_direct_orders');

        $order_id = $this->input->post('order_id');
        if($order_id){

            $orderStatus = $this->tb_one_direct_orders->getOneDirectOrderStatus($order_id);
            if($orderStatus==1){

                $this->tb_one_direct_orders->updateOneDirectOrderStatus($order_id,2);
                $this->o_order_cancel->preWithdrawOfOrder($order_id,'1direct');
            }
        }

        $this->_viewData['title'] = lang('import_third_part_orders');
        parent::index('admin/');
    }

	/** 导入沃尔玛订单：发放奖励 */
	public function walmart_import(){
        set_time_limit(0);
		if(isset($_FILES) && isset($_FILES["orders_csv"]) && $_FILES["orders_csv"]['name']){
			$dir = realpath('upload').'/third_part_orders_csv';
			if (is_dir($dir) == false) {
				mkdir($dir, 0777);//在页面目录下要新建相应文件夹用来保存上传csv文件
			}

			/*存储csv文件*/
			$csv_file_path_name = $dir.'/'.time().'_'.$_FILES["orders_csv"]["name"];
			move_uploaded_file($_FILES["orders_csv"]["tmp_name"], $csv_file_path_name);

			/*读取csv文件内容到数组*/
			$result_arr = input_csv($csv_file_path_name);

			$this->load->model('M_order');
			$this->load->model('m_user');

            $this->load->model('tb_walmart_orders');
            foreach($result_arr as $v){

                $v['order_id'] = trim($v['order_id']);
                if($v['order_id']){
                    $order_id_arr = explode('_', $v['order_id']);
                    $format_oid = '';
                    foreach($order_id_arr as $v_oid){
                        if(is_numeric($v_oid)){
                            $v_oid = number_format($v_oid,0,'','');
                        }
                        $format_oid.='_'.$v_oid;
                    }
                    $v['order_id'] = 'A-'.substr($format_oid,1);
                    $error_code = $this->tb_walmart_orders->addWalmartOrders($v);
                }
			}
           
		}
		$this->_viewData['title'] = lang('import_third_part_orders');
		parent::index('admin/');
	}


    /** 导入沃好订单 */
    public function walhao_import(){
        set_time_limit(360000);
        if(isset($_FILES) && isset($_FILES["orders_csv"]) && $_FILES["orders_csv"]['name']){
            $dir = realpath('upload').'/third_part_orders_csv';
            if (is_dir($dir) == false) {
                mkdir($dir, 0777);//在页面目录下要新建相应文件夹用来保存上传csv文件
            }

            /*存储csv文件*/
            $csv_file_path_name = $dir.'/'.time().'_'.$_FILES["orders_csv"]["name"];
            move_uploaded_file($_FILES["orders_csv"]["tmp_name"], $csv_file_path_name);

            /*读取csv文件内容到数组*/
            $result_arr = input_csv($csv_file_path_name);

            $this->load->model('m_order');
            $this->load->model('tb_walmart_orders');

            foreach($result_arr as $v){

                $v['order_id'] = trim($v['order_id']);

                if(!$v['order_id'] || substr($v['order_id'],0,3)=='TKD' || substr($v['order_id'],0,5)=='WLTHD'){
                    continue;
                }

                $v['order_id'] = 'W-'.$v['order_id'];
                $error_code = $this->m_order->saveWohaoMallOrders($v);
            }
        }

        $this->_viewData['title'] = lang('import_third_part_orders');
        parent::index('admin/');
    }

}
