<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Export_orders extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		// status map
		$status_map = array(
			0 => array('class' => "text-default", 'text' => lang('admin_order_status_all')),
			Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
			Order_enum::STATUS_SHIPPING => array('class' => "text-success", 'text' => lang('admin_order_status_paied')),
			Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
			Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
			Order_enum::STATUS_EVALUATION => array('class' => "text-warning", 'text' => lang('admin_order_status_arrival')),
			Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
			Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
			Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
			Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
		);

		// 国家地区 map
		$area_map = $this->m_trade->get_area_map($this->_viewData['curLanguage']);
		$this->_viewData['title'] = lang('admin_export_orders');
		$this->_viewData['area_map'] = $area_map;
		$this->_viewData['status_map'] = $status_map;

		//获取所有分类
		$cur_language_id = get_cookie('curLan_id',true) ? get_cookie('curLan_id',true) : 1;
		$this->_viewData['category_all']=$this->m_goods->get_all_category($cur_language_id);

		/* 获取仓库  */
		//$this->_viewData['store_all']=$this->m_global->getStoreList();

                //获取发货商列表
                $this->_viewData['shipper_all']=$this->m_global->getShipperList($this->_adminInfo['role']);
                //fout($this->_viewData['shipper_all']);exit;
                //获取运营商列表
                $this->_viewData['supplier_arr'] = array(
                    "0"=>"选择运营方",
                    "1"=>"中国深圳运营",
                    "2"=>"USA South Link Operator",
                    "3"=>" Korea South Link Operator",
                    "4"=>"中国香港运营",
                    "100"=>"USA DOBA Operator",
                    "200"=>"Korea Sophy Part Operator",
                    "201"=>"Korea Epic Sam LLC",
                );
		parent::index('admin/');
	}
        function ajax_shipper(){
            $supplier_id = $_POST["supplier_id"];
            $this->db->select('ms.supplier_name,ms.supplier_id,ms.operator_id')->from('mall_supplier ms');
            if($this->_adminInfo['role'] == 6){
                    $this->db->join('mall_goods_shipper mg','ms.supplier_id=mg.shipper_id','left');
                    $this->db->where('mg.area_rule','3');
            }
            if($this->_adminInfo['role'] == 7){
                    $this->db->join('mall_goods_shipper mg','ms.supplier_id=mg.shipper_id','left');
                    $this->db->where('mg.area_rule','2');
            }
            if($supplier_id){
                $this->db->where('ms.operator_id',$supplier_id);
                $this->db->or_where('ms.supplier_id',$supplier_id);
            }
            $data = $this->db->order_by('ms.supplier_id','asc')->get()->result_array();
            echo json_encode($data);
        }

	public function report_to_usa(){
            if($this->input->is_ajax_request()){
		$searchData = $this->input->post() ? $this->input->post() : array();
		$searchData['area'] = isset($searchData['area']) ? $searchData['area'] : '';
		$area_country_map = array(
			"156" => array("156"),
			"840" => array("840"),
			"410" => array("410"),
			"344" => array("344"),
			"446" => array("446"),
			"158" => array("158"),
			"001" => array("704", "418", "116", "764", "104", "458", "702", "360", "096", "608", "626"),
			"000" => array("000"),
		);
		$filter['area'] = isset($area_country_map[$searchData['area']]) ? $area_country_map[$searchData['area']] : '';
		$filter['status'] = isset($searchData['status']) ? $searchData['status'] : '';
		$filter['cate_id'] = isset($searchData['cate_id']) ? $searchData['cate_id'] : '';
                $filter['order_type'] = isset($searchData['order_type']) ? $searchData['order_type'] : '';
		$filter['store_code'] = isset($searchData['store_code']) ? $searchData['store_code'] : '';
		$filter['store_code_arr'] = isset($searchData['store_code_arr']) ? $searchData['store_code_arr'] : '';
		$filter['start_date'] = isset($searchData['start_date']) ? $searchData['start_date'] : '';
		$filter['end_date'] = isset($searchData['end_date']) ? $searchData['end_date'] : '';
                $filter['start_update_date'] = isset($searchData['start_update_date']) ? $searchData['start_update_date'] : '';
		$filter['end_update_date'] = isset($searchData['end_update_date']) ? $searchData['end_update_date'] : '';
		$filter['start_deliver_date'] = isset($searchData['start_deliver_date']) ? $searchData['start_deliver_date'] : '';
		$filter['end_deliver_date'] = isset($searchData['end_deliver_date']) ? $searchData['end_deliver_date'] : '';
		$filter['brand'] = isset($searchData['brand']) ? $searchData['brand'] : '';
		$filter['ext'] = isset($searchData['ext']) ? $searchData['ext'] : '';
		$filter['is_export_lock'] = isset($searchData['is_export_lock']) ? $searchData['is_export_lock'] : '';
		$filter['select_is_export_lock'] = isset($searchData['select_is_export_lock']) ? $searchData['select_is_export_lock'] : '';
		$filter['language_id'] = get_cookie('admin_curLan_id') ? get_cookie('admin_curLan_id') : 1;
                //状态不是正在发货中的时候，清空更新时间条件
                if($filter['status']!= "1"){
                    $filter['start_update_date'] = $filter['end_update_date'] = "";
                }
                if (empty($filter['store_code_arr'])) {
                    die(json_encode(array('success'=>0,'msg'=>"请选择发货商（Please select Shipper!）")));
                }
                //选择多个发货商时，组装SQL    
                $store_code_arr = explode(',',$filter['store_code_arr']);
                $store_count = count($store_code_arr);
                $store_code = '';
                if($store_count == 1){
                    $store_code = '"'.$filter['store_code_arr'].'"';
                    $store_code_str = '='.$store_code;
                }else{
                    foreach($store_code_arr as $k=>$v){
                        if($store_count-1 == $k){
                            $store_code .= '"'.$v.'"';
                        }else{
                            $store_code .= '"'.$v.'",';
                        }
                    }
                    $store_code_str = 'in ('.$store_code.')';
                }
                //查询所属运营方
                $names = $this->db->query("select supplier_id,supplier_name,operator_id from mall_supplier where supplier_id $store_code_str")->result_array();
                $name_str = "";
                if($names)foreach($names as $key=>$name){
                    if($key == 0){
                        $name_str .= $name['supplier_name'];
                        $supplier = $name['operator_id']=='0' ? $name['supplier_id'] : $name['operator_id'];
                    }else{
                        //$name_str .= '+'.$name['supplier_name'];//多选的话只用首个供应商名，避免文件名过长
                        if(($name['operator_id']=='0' ? $name['supplier_id'] : $name['operator_id']) != $supplier){
                            die(json_encode(array('success'=>0,'msg'=>"不能选择多个不同运营方")));
                        }
                    }
                }
                $this->db->insert('export_order_tmp',array(
                    "operator_id"=>$supplier,//运营方
                    "fifter_array" =>serialize($filter),
//                    "filename"=>(in_array($supplier,array('3','200','201')) ? $this->_adminInfo['id'] : $name_str) . date('Y-m-d H-i-s', time()),//文件名
                    "filename"=>str_replace('.','．',$name_str) .'_'. date('Y-m-d H-i-s', time()),//文件名
                    "filename_tmp"=>date("Y-m-d H-i-s"),
                    "status"=>0,
                    "admin_id"=>$this->_adminInfo['id'],
                    "system_time"=>date("Y-m-d H:i:s",time())
                ));
                die(json_encode(array('success'=>1,'msg'=>"后台处理中!")));
            }
	}
    /**
     * 间隔3秒钟请求脚本状态
     */
    function export_status_ajax(){
        $this->load->model('td_export_excel_tmp');
        $data = $this->td_export_excel_tmp->getList($this->_adminInfo['id']);
        if($data){
            echo json_encode(array("succ"=>1,"list"=>$data));
        }else{
            echo json_encode(array("succ"=>0,"list"=>""));
        }

    }

    /**
     * 文件下载
     */
    function download_file(){
        $id = $_GET["download_id"];
        $data = $this->db->where("id",$id)->get("export_order_tmp")->row_array();
        if(!empty($data)){
            $update_path = $data["update_path"];
            $file_name = str_replace(array(".",","),array("．","，"),$data["filename"]);//命名特殊符号替换成全角
           // fout($file_name);exit;
            ob_clean();
            ob_end_clean();
            header('Content-type: application/x-excel');
            header("Content-Disposition: attachment; filename={$file_name}.xlsx");//下载时显示的名字
            readfile($update_path);
            exit();
        }
    }
        /**
        * 下载之后改变状态
        * @param $filename
        * @param $h_filename
        */
        function download_change_s(){
           if($this->input->is_ajax_request()){
		$id = $this->input->post("id");
                $this->db->where("id",$id)->update("export_order_tmp",array("status"=>3));
           }
       }
       function del_change_s(){
           if($this->input->is_ajax_request()){
		$id = $this->input->post("id");
                $this->db->where("id",$id)->update("export_order_tmp",array("status"=>3));
           }
       }
        function ccc(){
            $my_curl = curl_init();
            curl_setopt($my_curl, CURLOPT_URL, base_url()."cron/export_excel");
            curl_setopt($my_curl,CURLOPT_RETURNTRANSFER,1);
            $str = curl_exec($my_curl);
            echo $str;
            curl_close($my_curl);
        }
	/*解决Excel2007不能导出*/
	function SaveViaTempFile($objWriter){
		$filePath = '' . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
		$objWriter->save($filePath);
		readfile($filePath);
		unlink($filePath);
	}

	/** 导出paypal */
	public function export_paypal(){
		error_reporting(E_ALL); //开启错误
		require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';
		$data = $this->db->query("select  * from mall_orders_paypal_info where create_time < '2015-10-24' order by create_time asc")->result_array();;
		if($data)foreach($data as $k=>&$v){
			$v['uid'] = " ".$v['uid'];
			$v['order_id'] = " ".$v['order_id'];
			$v['txn_id'] = " ".$v['txn_id'];
			$v['tracking_number'] = " ".$v['tracking_number'];
			$v['company_name'] = " ".$v['company_name'];
			$v['create_time'] = " ".$v['create_time'];
		}
		/*$data1 = $this->db->query("select  customer_id,order_id,txn_id,freight_info from trade_orders where payment_type = '107' and `status` in ('3','4','5','6')")->result_array();;
		$data2 = $this->db->query("select t.customer_id,u.order_sn as order_id,u.txn_id,t.freight_info from user_upgrade_order u left join trade_orders t on u.uid=t.customer_id  where u.payment = 'paypal' and u.`status` = 2 and t.`status` in ('3','4','5','6')")->result_array();;
		$data = array_merge($data1,$data2);
		$this->load->model('m_user_helper');
		if($data)foreach($data as $k=>$v){
			$v['company'] = '';
			if($v['freight_info']){
				$freight_info = explode('|',$v['freight_info']);

				if(isset($freight_info[0])){
					$v['company'] = $this->m_user_helper->getFreightName($freight_info[0]);
				}
				if(isset($freight_info[1])){
					if(strpos($freight_info[1],'#')){
						$v['freight_info'] = substr($freight_info[1],strpos($freight_info[1],'#')+1);
						$v['company'] = 'UPS';
					}else{
						$v['freight_info'] = $freight_info[1];
					}
				}
			}
			$data[$k]= $v;
		}*/

		$objExcel = new PHPExcel();
		//设置属性
		$objExcel->getProperties()->setCreator("john");
		$objExcel->setActiveSheetIndex(0);

		$i=0;
		//表头
		$objExcel->getActiveSheet()->setCellValue('a1',  'Customer_id');
		$objExcel->getActiveSheet()->setCellValue('b1',  'Order_id');
		$objExcel->getActiveSheet()->setCellValue('c1',  'Transaction number');
		$objExcel->getActiveSheet()->setCellValue('d1',  'Tracking Number');
		$objExcel->getActiveSheet()->setCellValue('e1',  'Company');
		$objExcel->getActiveSheet()->setCellValue('f1',  'Dliver Time');
		if($data)foreach($data as $v){
			$u1=$i+2;
			/*----------写入内容-------------*/
			$objExcel->getActiveSheet()->setCellValue('a'.$u1, $v["uid"]);
			$objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["order_id"]);
			$objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["txn_id"]);
			$objExcel->getActiveSheet()->setCellValue('d'.$u1, $v["tracking_number"]);
			$objExcel->getActiveSheet()->setCellValue('e'.$u1, $v["company_name"]);
			$objExcel->getActiveSheet()->setCellValue('f'.$u1, $v["create_time"]);
			$i++;
		}
		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition: attachment;filename="paypal.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

	//redis查看
	public function redis()
    {
        $s=$this->input->get("s");
        if($s != md5("redis".date("Ymd")."redis"))
        {
            exit("press_s");
        }
        $c = $this->input->get("c");
        if(!$c)
        {
            exit("press_c");
        }
        $arr =  explode(" ",$c);
        $this->load->model("tb_empty");
        if($arr && isset($arr[0]))
        {
            $command = $arr[0];
            unset($arr[0]);
            $tmp = call_user_func_array([$this->tb_empty,"redis_".$command],$arr);
            if(is_array($tmp))
            {
                foreach($tmp as $k=>$v)
                {
                    echo($v."<br>");
                }
                exit;
            }elseif(is_string($tmp))
            {
                echo($tmp."<br>");
                exit;
            }else{
                var_dump($tmp);
                exit;
            }
        }
        exit("press_e");
    }
}
?>