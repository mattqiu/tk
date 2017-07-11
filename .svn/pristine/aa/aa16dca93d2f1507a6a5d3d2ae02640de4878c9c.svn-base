<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Store_report extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
		//$this->m_global->checkPermission('Order_report',$this->_adminInfo);
        $this->load->model('m_admin_user');
        
    }

    public function index() {
		$searchData = $this->input->post();				
		$attr = $this->input->get();
		$this->_viewData['title'] = lang('store_report');		
		//2017-1-11 废除， 使用新的统计方式
		//$data = $this->process($searchData);
		//$this->_viewData['results'] = $data; 
			
		//新的统计方式
		if(empty($searchData))
		{
		    $search_time = date("Ym");
		    $searchData['sear_year'] = date("Y");		
		    $searchData['sear_month'] = date("m");
		}
		else 
		{
		    $sear_time = $searchData["sear_year"]."-".$searchData["sear_month"];
		    $search_time = date("Ym",strtotime($sear_time));
		}
		
		if($searchData['sear_year'] > (int)date("Y"))
		{		   
		    $this->_viewData['results'] = null;
		}
		else
		{
		    if($searchData['sear_year'] == (int)date("Y") && $searchData["sear_month"] > (int)date("m"))
		    {
		        $this->_viewData['results'] = null;
		    }
		    else 
		    {
		        
		        if($searchData['sear_year'] < (int)date("Y"))
		        {
		            $add_today_data = 0;
		        }
		        else if($searchData['sear_year'] == (int)date("Y"))
		        {
		            if($searchData['sear_month'] < (int)date("m"))
		            {
		                $add_today_data = 0;
		            }
		            else if($searchData['sear_month'] == (int)date("m"))
		            {
		                $add_today_data = 1;
		            }
		        }
		        
		        $this->load->model('tb_users_level_statistics_total');		   
		        		        
		        if(empty($attr))
		        {
		            $datas = $this->tb_users_level_statistics_total->get_store_statistics_total($search_time,$add_today_data,lang("admin_month_level_t"));
		        }
		        else 
		        {
		            
		            switch ($attr['tabs_type']) {
                        case 2:
                            $optype = 1;                           
                            break;
                        case 3:
                            $optype = 4;                            
                            break;
                        case 4:
                            $optype = 2;                            
                            break;
                        case 5:
                            $optype = 3;                            
                            break;
                        case 6:
                            $optype = 5;                        
                            break;
                        default:
                            $optype = 1;                           
                            break;
                    }
                    $datas = $this->tb_users_level_statistics_total->get_store_area_total_all($optype,$search_time,$add_today_data,lang("admin_month_level_t"));
		        }
		        $this->_viewData['results'] = $datas;
		    }		    
		}		    
		
		$tabs_map = array(
		    1 => array(
		        'desc' => lang('admin_store_statistics_total'),
		        'url' => "admin/store_report",
		    ),
		    2 => array(
		        'desc' => lang('label_cn'),
		        'url' => "admin/store_report?tabs_type=2",
		    ),
		    3 => array(
		        'desc' => lang('label_hk'),
		        'url' => "admin/store_report?tabs_type=3",
		    ),
		    4 => array(
		        'desc' => lang('label_us'),
		        'url' => "admin/store_report?tabs_type=4",
		    ),
		    5 => array(
		        'desc' => lang('label_ko'),
		        'url' => "admin/store_report?tabs_type=5",
		    ),
		    6 => array(
		        'desc' => lang('other'),
		        'url' => "admin/store_report?tabs_type=6",
		    )
		);
				
		
		// 标签类型
		if (isset($attr['tabs_type']) && isset($tabs_map[$attr['tabs_type']]))
		{
		    $tabs_type = $attr['tabs_type'];
		}
		else
		{
		    $tabs_type = 1;
		}
		$this->_viewData['tabs_type'] = $tabs_type;
		
		// 状态映射表
		$this->_viewData['status_map'] = array(
		    Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
		    Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
		    Order_enum::STATUS_SHIPPING => array('class' => "text-warning", 'text' => lang('admin_order_status_paied')),
		    Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
		    Order_enum::STATUS_EVALUATION => array('class' => "text-success", 'text' => lang('admin_order_status_arrival')),
		    Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
		    Order_enum::STATUS_HOLDING => array('class' => "text-holding", 'text' => lang('admin_order_status_holding')),
		    Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
		    Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
		    Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
		);
		
		
		$this->_viewData['tabs_map'] = $tabs_map;
		
		$this->_viewData['searchData'] = $searchData;		
		
		$this->load->model('m_user');
		foreach(array('未激活'=>'0','活跃中'=>1,'欠月费（休眠）'=>2,'冻结账户'=>3,'公司账户'=>4) as $k=>$v){
			$status[$k] = $this->m_user->getUserListRows(array('status'=>$v));
		}
		$this->_viewData['status'] = $status;
        parent::index('admin/');
    }
   
    public function input_data()
    {
        $search_data = $this->input->post();
        $this->load->model('tb_users_level_statistics_total');
        $datas = $this->tb_users_level_statistics_total->getDataShow($search_data['start_date'],$search_data['end_date']);       
        redirect(base_url('admin/store_report'));
    }
    
    
    
    public function process($filter){

		//国家地区
		$country_area_map = array(
			"中国" => 1,
			"美国" => 2,
			"韩国" => 3,
			"香港" => 4,
			"其他" => array("1","2","3","4"),
		);
		//产品
		$store_arr = array(
			'免费（Free）'=>4,
			//'月费会员(Only Monthly Fee)'=>0,
			'铜级(Bronze)'=>5,
			'银级（Silver）'=>3,
			'金级（Gold）'=>2,
			'钻石级（Diamond）'=>1,
		);
		$result = array();
		if($store_arr)foreach($store_arr as $store_name=>$store){

			$report = array();
			$total_deliver = 0;
			foreach($country_area_map as $country_name=>$country){

					$this->db->from('users')->where('status <>',0);

					if($store ==0 || $store ==4){
						$star_end = date('Y-m-d H:i:s',strtotime($filter['end'])+86400-1);
						$star_start = date('Y-m-d H:i:s',strtotime($filter['start']));
						if(isset($filter['start']) && $filter['start'] ){
							$this->db->where('enable_time >=', $star_start);
						}
						if(isset($filter['end']) && $filter['end'] ){
							$this->db->where('enable_time <=',	 $star_end);
						}
					}

					if($store == 0){
						$this->db->where('month_fee_rank <= 3  and user_rank = 4');
					}else if($store == 4){
						$this->db->where('month_fee_rank = 4  and user_rank = 4');
					}else{

						$star_end = date('Y-m-d H:i:s',strtotime($filter['end'])+86400-1);
						$star_start = date('Y-m-d H:i:s',strtotime($filter['start']));
						if(isset($filter['start']) && $filter['start'] ){
							$this->db->where('upgrade_time >=', $star_start);
						}
						if(isset($filter['end']) && $filter['end'] ){
							$this->db->where('upgrade_time <=',	 $star_end);
						}
						$this->db->where('user_rank',$store);
					}
					if(is_array($country)){
						$this->db->where_not_in('country_id',$country);
					}else{
						$this->db->where('country_id',$country);
					}
					$deliver = $this->db->count_all_results();
					$total_deliver = $total_deliver+$deliver;
					$report[] =  $deliver;
			}
			$report[] = $total_deliver;
			$result[$store_name] = $report;
		}
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

}