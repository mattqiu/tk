<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Check_prepaid_card extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
    }
    public function index($id = NULL) {

        $this->_viewData['title'] = lang('check_prepaid_card');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['ID_no'] = isset($searchData['ID_no'])?$searchData['ID_no']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:"";
        $this->_viewData['list'] = $this->m_admin_helper->getPcCardList($searchData);
        $this->load->library('pagination');
        $url = 'admin/check_prepaid_card';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getPcCardListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');

    }

	public function pc_reject(){
		$data = $this->input->post();
		$update_arr = array(
			'status'=>$data['status'],
		);

		if($data['status'] == '2'){
			$update_arr['reject_remark']=$data['remark'];
		}

		$this->db->trans_start();

		$this->db->where('id',$data['id'])->update('users_prepaid_card_info',$update_arr);

		$this->db->trans_complete();

		if($this->db->trans_status() === TRUE){
			$affected_rows = true;
			$msg = '';
		}else{
			$affected_rows = false;
			$msg = lang('try_again');
		}
		die(json_encode(array('success'=>$affected_rows,'msg'=>$msg)));
	}

	/** 导出申请的预付卡 */
	public function export_prepaid_card(){

		error_reporting(E_ALL); //开启错误
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$lists = $this->m_admin_helper->exportPrepaidCard($searchData);
		$fields = array('KYC Face to Face Date of Verification','UnionPay Card No','Application Date','Name','ID / Passport No','Chinese Name'
		,'Mobile No','Email','Address','Country','Nationality','Issuing Country','Picture Link','TPS-KYC Comment',
			'K&R-KYC Comment','Initial loading amount','ID');

		$is_lock = FALSE;
		if($searchData['is_export_lock'] && $searchData['status'] == 5){
			$is_lock = TRUE;
		}

		if($lists)foreach($lists as &$list){

			if($is_lock === TRUE){ //鎖定訂單數據
				$update = array(
					'uid'=>$list['uid'],
					'status'=>3,
				);
				$update_data[] = $update;
			}

			$list['ID_no'] = " ".$list['ID_no'];
			$list['mobile'] = " ".$list['mobile'];
			$list['uid'] = " ".$list['uid'];
			$list['pic_url'] = $this->_viewData['web_host'].'prepaid_card/index/'.$list['card_no'];
			$list['card_no'] = " ".$list['card_no'];

			//$list['address_prove'] = config_item('img_server_url').'/'.$list['address_prove'];
			//$list['ID_front'] = config_item('img_server_url').'/'.$list['ID_front'];
			//$list['ID_reverse'] = $list['ID_reverse']?config_item('img_server_url').'/'.$list['ID_front']:"";
		}

		if($is_lock === TRUE && $update_data){ //批量鎖定
			$this->db->update_batch('users_prepaid_card_info',$update_data,'uid');
		}

		require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

		$objExcel = new PHPExcel();
		$i=0;
		//设置属性
		$objExcel->getProperties()->setCreator("john");
		$objExcel->setActiveSheetIndex(0);

		//表头
		for($a='A',$b=0;$a<='Q';$a++,$b++){
			$objExcel->getActiveSheet()->setCellValue($a.'1',  $fields[$b]);
			$objExcel->getActiveSheet()->getStyle($a.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objExcel->getActiveSheet()->getStyle($a.'1')->getFill()->getStartColor()->setARGB('0093d150');
			$objExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(65);
			//垂直居中
			$objExcel->getActiveSheet()->getStyle($a.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objExcel->getActiveSheet()->getStyle($a.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//背景色下显示边框
			$objExcel->getActiveSheet()->getStyle($a.'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objExcel->getActiveSheet()->getStyle($a.'1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objExcel->getActiveSheet()->getStyle($a.'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		}

		$export_date = date('Y-m-d H:i:s');
		if($lists)foreach($lists as $k=>$v) {
			$u1=$i+2;
			$objExcel->getActiveSheet()->setCellValue('a'.$u1, $export_date);
			$objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["card_no"]);
			$objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["create_time"]);
			$objExcel->getActiveSheet()->setCellValue('d'.$u1, $v["name"]);
			$objExcel->getActiveSheet()->setCellValue('e'.$u1, $v["ID_no"]);
			$objExcel->getActiveSheet()->setCellValue('f'.$u1, $v["chinese_name"]);
			$objExcel->getActiveSheet()->setCellValue('g'.$u1, $v["mobile"]);
			$objExcel->getActiveSheet()->setCellValue('h'.$u1, $v["email"]);
			$objExcel->getActiveSheet()->setCellValue('i'.$u1, $v["ship_to_address"]);
			$objExcel->getActiveSheet()->setCellValue('j'.$u1, $v["country"]);
			$objExcel->getActiveSheet()->setCellValue('k'.$u1, $v["nationality"]);
			$objExcel->getActiveSheet()->setCellValue('l'.$u1, $v["issuing_country"]);
			$objExcel->getActiveSheet()->setCellValue('m'.$u1, $v["pic_url"]);
			//$objExcel->getActiveSheet()->setCellValue('n'.$u1, $v["ID_front"]);
			//$objExcel->getActiveSheet()->setCellValue('o'.$u1, $v["ID_reverse"]);
			$objExcel->getActiveSheet()->setCellValue('n'.$u1, 'YES');
			$objExcel->getActiveSheet()->setCellValue('o'.$u1, "");
			$objExcel->getActiveSheet()->setCellValue('p'.$u1, 0);
			$objExcel->getActiveSheet()->setCellValue('q'.$u1, $v['uid']);
			$i++;
		}

		for($j='A';$j<='Q';$j++){
			$objExcel->getActiveSheet()->getColumnDimension($j)->setWidth(25);
			if(in_array($j,array('M','I'))){
				$objExcel->getActiveSheet()->getColumnDimension($j)->setWidth(70);
			}
		}

		ob_end_clean();//清除缓冲区,避免乱码
		$filename = 'Prepaid_Card_'.date('Y-m-d-H:i:s',time());
		header('content-Type:application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

	/** 預付卡信息 */
	public function get_prepaid_card($id)
	{
		/** 查看是否已经申请银联预付卡 */
		$info = $this->db->where('id',$id)->get('users_prepaid_card_info')->row_array();
		$this->_viewData['pre_card'] = $info;

		$country_arr = array(
			'China'=>lang('con_china'),
			'Hong Kong'=>lang('con_hongkong'),
			'USA'=>lang('con_usa'),
			'Korea'=>lang('con_korea'),
			'Macao'=>'Macao',
		);
		$this->_viewData['country_arr'] = $country_arr;

		$this->load->view('admin/prepaid_card_info', $this->_viewData);
	}

	/** 修改预付卡信息 */
	public function do_update(){

		$ret_data = array(
			'code' => 0,
			'msg' => "",
		);

		$attr = $this->input->post(null,true);

		$item = $this->db->where('id',$attr['id'])->get('users_prepaid_card_info')->row_array();
		if(!$item){
			redirect(base_url('admin/check_prepaid_card'));exit;
		}

		if(!in_array($item['status'],array('3','4','5'))){
			isset($attr['card_no'])?$attr['card_no']=trimall($attr['card_no']) : '';
			//$attr['uid'] = $this->_userInfo['id'];
			//$attr['agreement'] = isset($attr['agreement']) ? $attr['agreement'] : 'off';
			$rules = array(
				'name' => "required|max:64",
				'chinese_name' => "max:64",
				'mobile' => "required|numeric",//|between:6,16
				'nationality' => "required",
				'issuing_country' => "required",
				//'ID_address' => "required|max:255",
				'ID_type' => "required|integer|in:0,1",
				'ID_no' => "required|alpha_dash",
				//'ID_front' => "required",
				//'address_prove' => "required",
				//'ID_reverse' =>"required_without:ID_front",
				'country' => "required",
				'ship_to_address' => "required|max:255",
				//'uid' => "required|integer|in:{$attr['uid']}",
				//'agreement' => "accepted",
				'email' => "required|email",
			);
			//isset($attr['id']) && $attr['id'] ? $rules['id'] = "integer":$rules['agreement'] = "accepted";
			isset($attr['card_no'])? $rules['card_no'] = "required|min:16|max:16":"";

			if (TRUE !== $this->validator->validate($attr, $rules))
			{
				$ret_data['code'] = 101;
				$ret_data['msg'] = $this->validator->get_err_lang_msg();
				echo json_encode($ret_data);
				exit;
			}
		}
		$this->db->trans_start();

		if(isset($attr['status']) && $attr['status'] == '5'){
			if(isset($attr['card_no']) && !$attr['card_no']){
				$ret_data['code'] = 101;
				$ret_data['msg'] = lang("assign_card_no");
				echo json_encode($ret_data);
				exit;
			}
			if(isset($attr['card_no']) && $attr['card_no']){
			//$count = $this->db->from('users_prepaid_card_no')->where('card_no',$attr['card_no'])->count_all_results();
				$row = $this->db->query("SELECT * FROM users_prepaid_card_no WHERE card_no={$attr['card_no']} FOR UPDATE")->row_array();
				if(empty($row)){
					/*$ret_data['code'] = 101;
					$ret_data['msg'] = lang('prepaid_card_no_exist');
					echo json_encode($ret_data);
					exit;*/
				}else if($row['status'] > 0){
					$ret_data['code'] = 101;
					$ret_data['msg'] = lang('assign_card_no_error');
					echo json_encode($ret_data);
					exit;
				}
			}
		}

		if(isset($attr['id']) && $attr['id']){

			$this->db->where('id',$attr['id'])->update('users_prepaid_card_info',$attr);
			if(isset($attr['card_no']) && $attr['card_no'] && isset($attr['status'])&& $attr['status'] == '5'){
				$this->db->where('id',$attr['id'])->update('users_prepaid_card_info',array('status'=>'5')); //分配卡号后就是待审核了
				$this->db->where('card_no',$attr['card_no'])->update('users_prepaid_card_no',array('status'=>'2'));//分配卡号后锁定卡号
			}
		}
		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE ){
			$ret_data['msg'] = lang('update_success');
		}else{
			$ret_data['code'] = 101;
			$ret_data['msg'] = lang('try_again');
		}
		echo json_encode($ret_data);
		exit;
	}

	/** 分配卡號 */
	public function assign_card_no(){
		if($this->input->is_ajax_request()){
			$item = $this->db->where('status','0')->limit(1)->get('users_prepaid_card_no')->row_array();
			if($item){
				die(json_encode(array('success'=>1,'card_no'=>$item['card_no'])));
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('pc_without'))));
			}
		}
	}

}