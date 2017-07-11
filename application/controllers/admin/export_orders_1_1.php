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

	public function report_to_usa()
	{
                ini_set('display_errors', '1');
                ini_set ('memory_limit', '2048M');
                set_time_limit(0);
		error_reporting(E_ALL); //开启错误
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
                if ($filter['store_code_arr'] != '') {
			
                    $this->load->model('m_admin_helper');
                    if($filter['status']!= "1"){
                        $filter['start_update_date'] = $filter['end_update_date'] = "";
                    }
                   // echo "<pre>";print_r($filter);exit;
                    $lists = $this->m_admin_helper->exportOrderReport($filter, $this->_adminInfo['id']);
                    if(empty($lists)){
                        $this->_viewData['err_msg'] = "该条件无数据!";
			$this->index();
                    }else{
                        $shipper_arr = array("2","100","3","200","201","4");
                       // $kor_shipper_arr = array("3","200","201");
                        if ($lists['name_str']) {
                                $filename = $lists['name_str'] . '_' . date('Y-m-d', time());
                        } else {

                                $filename = 'Order_' . date('Y-m-d', time()) . '_' . time();
                        }

                        if(isset($lists["supplier"]) && in_array($lists["supplier"],$shipper_arr)){
                           $this->usa_and_kor_excel($lists,$filename,$filter);
                        }else{
                           $this->commonly_excel($lists,$filename);
                        }
                    }
                }else{
                    $this->_viewData['err_msg'] = "请选择发货商（Please select Shipper!）";
			$this->index();
                }

	}
        function xg_excel($objExcel,$lists){
            $n = $i = 0;
            if ($lists['data']) foreach ($lists['data'] as $k => $v) {
                    /*----------写入内容-------------*/
                $u1 = $i +2;
                $phone = $v["phone"];
                $i++; 
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["goods_list"]);
                $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["country_address"]);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["order_type"]);   
            }
            return $i;
        }
        /**
         * 普通订单导出
         * @param type $objExcel
         * @param type $lists
         */
        function commonly_excel($lists,$filename){
           // echo "<pre>";print_r($lists);exit;
            if (!is_dir("img/export_excel/")) {
                mkdir("img/export_excel/", DIR_WRITE_MODE); // 使用最大权限0777创建文件
            }
            if (!is_dir("img/export_excel/{$this->_adminInfo['id']}/")) {
                mkdir("img/export_excel/{$this->_adminInfo['id']}/", DIR_WRITE_MODE); // 使用最大权限0777创建文件
            }
            $txt_name = "img/export_excel/{$this->_adminInfo['id']}/".iconv('utf-8', 'gbk', $filename)."_".date("H-i-s").".csv";
            $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID','SKU', 'Product Name', 'Country', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
            header('Cache-Control: max-age=0');

            //打开PHP文件句柄,php://output 表示直接输出到浏览器
            $fp = fopen($txt_name, 'w');
            $head_fp = fopen ( 'php://output', 'a' );
            //输出Excel列名信息
            foreach ($fields as $key => $value) {
                //CSV的Excel支持GBK编码，一定要转换，否则乱码
                $headlist[$key] = iconv('utf-8', 'gbk', $value);
            }

            //将数据通过fputcsv写到文件句柄
            fputcsv($fp, $headlist);
            fputcsv($head_fp, $headlist);
            
            //计数器
            $num = 0;

            //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
            $limit = 5000;
            //逐行取出数据，不浪费内存
           // echo $cc;exit;        
            foreach ($lists["data"] as $key => $value) {
                $row = array(
                    iconv('utf-8', 'gbk//IGNORE', $value["pay_time"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["consignee"]),
                    iconv('utf-8', 'gbk//IGNORE', is_numeric($value["customer_id"]) ? $value["customer_id"]."\t" : $value["customer_id"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["order_id"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["good_sku_goods"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["goods_list"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["country_address"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["address"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["customs_clearance"]),
                    iconv('utf-8', 'gbk//IGNORE', is_numeric($value["zip_code"]) ? $value["zip_code"]."\t" :$value["zip_code"]),
                    iconv('utf-8', 'gbk//IGNORE', is_numeric($value["phone"]) ? $value["phone"]."\t":$value["phone"]),
                    iconv('utf-8', 'gbk//IGNORE', is_numeric($value["freight_info"]) ? $value["freight_info"]."\t" : $value["freight_info"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["deliver_time"]),
                    iconv('utf-8', 'gbk//IGNORE', (isset($value["cus_remark"]) ? $value["cus_remark"] : "")),
                    iconv('utf-8', 'gbk//IGNORE', (isset($value["sys_remark"]) ? $value["sys_remark"] : "")),
                    iconv('utf-8', 'gbk//IGNORE', ($value["ID_no"] ?$value["ID_no"]."\t" : "")),
                    iconv('utf-8', 'gbk//IGNORE', ($value["ID_front"] ? config_item('img_server_url') . '/' . $value["ID_front"] : "")),
                    iconv('utf-8', 'gbk//IGNORE', ($value["ID_reverse"] ? config_item('img_server_url') . '/' . $value["ID_reverse"] : "")),
                    iconv('utf-8', 'gbk//IGNORE', $value["order_type"]),
                );
                $num++;
                //刷新一下输出buffer，防止由于数据过多造成问题
                if ($limit == $num) {
                    ob_flush();
                    flush();
                    $num = 0;
                }
                fputcsv($fp, $row);
                fputcsv($head_fp, $row);
            }
            if ($lists['count_goods']) {
                sortArrByField($lists['count_goods'],'add_time',false);
                $count_head = array("","","","","Statistics Number:");
                foreach ($count_head as $key => $value) {
                    $count_headlist[$key] = iconv('utf-8', 'gbk', $value);
                }
                fputcsv($fp, $count_headlist);
                fputcsv($head_fp, $count_headlist);
                foreach ($lists["count_goods"] as $key => $value) {
                    $row = array(
                        iconv('utf-8', 'gbk//IGNORE', ""),
                        iconv('utf-8', 'gbk//IGNORE', ""),
                        iconv('utf-8', 'gbk//IGNORE', ""),
                        iconv('utf-8', 'gbk//IGNORE', ""),
                        iconv('utf-8', 'gbk//IGNORE', $key),
                        iconv('utf-8', 'gbk//IGNORE', $value["name"]),
                        iconv('utf-8', 'gbk//IGNORE', $value["count"]),
                        iconv('utf-8', 'gbk//IGNORE', date("Y-m-d H:i:s",$value["add_time"])),
                    );
                    fputcsv($fp, $row);
                    fputcsv($head_fp, $row);
                }
            }
            fclose($fp);
        }
        /**
         * 美国订单导出
         * @param type $objExcel
         * @param type $lists
         * @return type
         */
        function usa_excel($objExcel,$lists){
            $wrap = "\n";
            $n = $i = 0;
            if ($lists['data']) foreach ($lists['data'] as $k => $v) {
                /*----------写入内容-------------*/
                $u1 = $i +2;
                if(count($v["goods_name_detail"])>1){
                    $new_ul = $u1+count($v["goods_name_detail"])-1;
                    $excel_arr = array("a","b","c","d","g","h","i","j","k","l","m","n","o","p","q","r");
                    foreach($excel_arr as $ev){
                        $objExcel->getActiveSheet()->mergeCells($ev.$u1.':'.$ev.$new_ul);
                    }
                    $i = $i+count($v["goods_name_detail"]);
                }else{
                    $i++;
                }

                foreach($v["goods_name_detail"] as $gk=>$vv){
                    $m = $n + 2;    
                    $objExcel->getActiveSheet()->setCellValue('e' . $m, $vv);
                    $objExcel->getActiveSheet()->setCellValue('f' . $m, $v["goods_count"][$gk]);
                    $n++;
                }
                if(strpos($v["phone"],'/')!== false){
                    $phone_arr = explode('/', $v["phone"]);
                    $phone = $phone_arr[0];
                }else{
                    $phone = $v["phone"]; 
                }

                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["order_type"]);   
            }
            return $i;
        }
        /**
         * 韩国订单导出
         * @param type $objExcel
         * @param type $lists
         * @param type $wrap
         * @return type
         */
        function kor_excel($objExcel,$lists){
            $wrap ="\n";
            $n = $i = 0 ;
            if ($lists['data']) foreach ($lists['data'] as $k => $v) {
                /*----------写入内容-------------*/
                $u1 = $i +2;
                $kor_good_list = $kor_good_count = "";
                foreach($v["goods_name_detail"] as $gk=>$vv){
                    $kor_good_list .= $vv.$wrap;
                    $kor_good_count .= (strpos($vv,$wrap)!== false) ? ($v["goods_count"][$gk].$wrap."".$wrap) : ($v["goods_count"][$gk].$wrap);
                }
                if(strpos($v["phone"],'/')!== false){
                    $phone_arr = explode('/', $v["phone"]);
                    $phone = $phone_arr[0];
                    $spare_phone = $phone_arr[1];
                }else{
                    $phone = $v["phone"]; 
                    $spare_phone = "";
                }
                $i++;
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);

                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $kor_good_list);
                $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $kor_good_count);
                $objExcel->getActiveSheet()->getStyle('f' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行

                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $spare_phone);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('s' . $u1, $v["order_type"]); 
            }
            return $i;
        }
        
        function usa_and_kor_excel($lists,$filename,$filter){
                $usa_shipper_arr = array("2","100");
                $kor_shipper_arr = array("3","200","201");
                require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
                require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
                require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
                require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = array('memoryCacheSize'=>'16MB');
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
                $objExcel = new PHPExcel();
                //设置属性
                $objExcel->getProperties()->setCreator("john");
                $objExcel->setActiveSheetIndex(0);
                if(isset($lists["supplier"]) && in_array($lists["supplier"],$usa_shipper_arr)){
                    $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type',"");
                }elseif(isset($lists["supplier"]) && in_array($lists["supplier"],$kor_shipper_arr)){
                    $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone','Spare phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
                }else{
                       $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name', 'Country', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type',"");
               }
                //表头
                $objExcel->getActiveSheet()->setCellValue('a1', $fields[0]);
                $objExcel->getActiveSheet()->setCellValue('b1', $fields[1]);
                $objExcel->getActiveSheet()->setCellValue('c1', $fields[2]);
                $objExcel->getActiveSheet()->setCellValue('d1', $fields[3]);
                $objExcel->getActiveSheet()->setCellValue('e1', $fields[4]);
                $objExcel->getActiveSheet()->setCellValue('f1', $fields[5]);
                $objExcel->getActiveSheet()->setCellValue('g1', $fields[6]);
                $objExcel->getActiveSheet()->setCellValue('h1', $fields[7]);
                $objExcel->getActiveSheet()->setCellValue('i1', $fields[8]);
                $objExcel->getActiveSheet()->setCellValue('j1', $fields[9]);
                $objExcel->getActiveSheet()->setCellValue('k1', $fields[10]);
                $objExcel->getActiveSheet()->setCellValue('l1', $fields[11]);
                $objExcel->getActiveSheet()->setCellValue('m1', $fields[12]);
                $objExcel->getActiveSheet()->setCellValue('n1', $fields[13]);
                $objExcel->getActiveSheet()->setCellValue('o1', $fields[14]);
                $objExcel->getActiveSheet()->setCellValue('p1', $fields[15]);
                $objExcel->getActiveSheet()->setCellValue('q1', $fields[16]);
                $objExcel->getActiveSheet()->setCellValue('r1', $fields[17]);
                $objExcel->getActiveSheet()->setCellValue('s1', $fields[18]);
                if(isset($lists["supplier"]) && in_array($lists["supplier"],$usa_shipper_arr)){
                    $i = $this->usa_excel($objExcel,$lists);
               }elseif(isset($lists["supplier"]) && in_array($lists["supplier"],$kor_shipper_arr)){
                    $i = $this->kor_excel($objExcel,$lists);
		}else{
                 $i = $this->xg_excel($objExcel,$lists);
                }
                $i = $i + 3;
		$objExcel->getActiveSheet()->setCellValue('e' . $i, 'Statistics Number:');
		if ($lists['count_goods']) {
			sortArrByField($lists['count_goods'],'add_time',false);
			foreach ($lists['count_goods'] as $key => $item) {
				$u1 = $i + 1;
				$objExcel->getActiveSheet()->setCellValue('d' . $u1, $key);
				$objExcel->getActiveSheet()->setCellValue('e' . $u1, $item['name']);
				$objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['count']);
				$objExcel->getActiveSheet()->setCellValue('g' . $u1, date('Y-m-d H:i:s', $item['add_time']));
				$i++;
			}
		}
		// 高置列的宽度
		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(75);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(75);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
		$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
                $objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
                
		ob_end_clean();//清除缓冲区,避免乱码
		if($filter['ext'] === '2007') { //导出excel2007文档
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
		} else {  //导出excel2003文档
			header('Content-Type: application/vnd.ms-excel;charset=utf-8');
			header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
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

}
?>