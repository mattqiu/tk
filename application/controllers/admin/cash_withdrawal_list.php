<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Cash_withdrawal_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
        $this->m_global->checkPermission('cash_withdrawal_list',$this->_adminInfo);
    }

    public function index() {

        $this->_viewData['title'] = lang('cash_withdrawal_list');
        $searchData = $this->input->get()?$this->input->get():array();

		$rate = get_cookie('withdrawal_rate',true);
		//$rate = $rate ? $rate : $this->m_global->get_rate('CNY');
		//当前汇率
		$this->_viewData['rate'] = $rate ? $rate : $this->m_global->get_rate('CNY');

        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['idEmail'] = isset($searchData['idEmail'])?$searchData['idEmail']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['take_out_type'] = isset($searchData['take_out_type'])?$searchData['take_out_type']:'';
        $searchData['payment_method'] = isset($searchData['payment_method'])?$searchData['payment_method']:'';
        $searchData['country_id'] = isset($searchData['country_id'])?$searchData['country_id']:'';
        $searchData['name'] = isset($searchData['name'])?$searchData['name']:'';
        $lists = $this->m_admin_helper->getWithdrawalList($searchData);
        $this->_viewData['list'] = $lists;

        $this->load->library('pagination');
        $url = 'admin/cash_withdrawal_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getWithdrawalListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(TRUE);
        $this->_viewData['searchData'] = $searchData;

		$this->_viewData['take_out_type'] = config_item('take_out_type');

        parent::index('admin/');
    }

	/** 提现汇率的自定义 */
	public function withdrawal_rate(){
		if($this->input->is_ajax_request()){
			$rate = $this->input->post('rate');
			if($rate > 0 && $rate < 7 ){
				set_cookie('withdrawal_rate',$rate,0,get_public_domain());
			}
			die(json_encode(array('success'=>1)));
		}
	}

    /** 導出提現記錄報表 */
    function exportWithdrawal(){

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['country_id'] = isset($searchData['country_id'])?$searchData['country_id']:'';
        $lists = $this->m_admin_helper->exportWithdrawalList($searchData);
        $fields = array(lang('id'),lang('realName'),lang('amount'),lang('country'),lang('bank_name'),lang('bank_card_number'),lang('card_holder_name'),lang('address'),lang('check_card_id'),lang('create_time'));
        $this->load->model('m_user_helper');
		foreach($lists as &$list){
            //$list['status'] = lang('tps_status_'.$list['status']);
            $list['account_bank'] = $list['account_bank'].$list['subbranch_bank'];
			$id_card = $this->m_user_helper->getUserIdCard($list['uid']);
			$list['card_no'] = " ".$id_card['id_card_num'];
			$list['name'] = $id_card['name'];
			$list['card_number'] = " ".$list['card_number'];
            unset($list['subbranch_bank']);
        }
		require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

		$objExcel = new PHPExcel();
		$i=0;
		//表头
		$objExcel->getActiveSheet()->setCellValue('a1',  $fields[0]);
		$objExcel->getActiveSheet()->setCellValue('b1',  $fields[1]);
		$objExcel->getActiveSheet()->setCellValue('c1',  $fields[2]);
		$objExcel->getActiveSheet()->setCellValue('d1',  $fields[3]);
		$objExcel->getActiveSheet()->setCellValue('e1',  $fields[4]);
		$objExcel->getActiveSheet()->setCellValue('f1',  $fields[5]);
		$objExcel->getActiveSheet()->setCellValue('g1',  $fields[6]);
		$objExcel->getActiveSheet()->setCellValue('h1',  $fields[7]);
		$objExcel->getActiveSheet()->setCellValue('i1',  $fields[8]);
		$objExcel->getActiveSheet()->setCellValue('j1',  $fields[9]);
	//	$objExcel->getActiveSheet()->setCellValue('k1',  $fields[10]);

		if($lists)foreach($lists as $k=>$v) {
			$u1=$i+2;
			/*----------写入内容-------------*/
			$objExcel->getActiveSheet()->setCellValue('a'.$u1, $v["uid"]);
			$objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["name"]);
			$objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["amount"]);
			$objExcel->getActiveSheet()->setCellValue('d'.$u1, lang(config_item('countrys_and_areas')[$v["country_id"]]));
			$objExcel->getActiveSheet()->setCellValue('e'.$u1, $v["account_bank"]);
			$objExcel->getActiveSheet()->setCellValue('f'.$u1, $v["card_number"]);
			$objExcel->getActiveSheet()->setCellValue('g'.$u1, $v["account_name"]);
			$objExcel->getActiveSheet()->setCellValue('h'.$u1, $v["address"]);
			$objExcel->getActiveSheet()->setCellValue('i'.$u1, $v["card_no"]);
			//$objExcel->getActiveSheet()->setCellValue('j'.$u1, $v["status"]);
			$objExcel->getActiveSheet()->setCellValue('j'.$u1, $v["create_time"]);
			$i++;
		}

		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		//$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);

		ob_end_clean();//清除缓冲区,避免乱码
		$filename = 'Withdrawal_'.date('Y-m-d',time()).'_'.time();
		header('content-Type:application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

    }

    /** 批量處理中 用戶就不能取消訂單了 */
    function batch_precess(){
        $param = $this->input->post();
        $type = $param['batch_type'];
        $data = $this->m_admin_helper->getWithdrawalByIds($param['checkboxes']);
        $order_error = array();
        $all_count = 0;
        foreach($data as $v){
            $all_count ++;
            if($type == 2){
                if($v['status'] > 0){
                    array_push($order_error,$v);continue;
                }
            }else if($type == 1){
                if($v['status'] != 2){
                    array_push($order_error,$v);continue;
                }
            }
        }
        if(count($order_error) > 0){
            $res['status'] = 0;
            $res['row'] = $order_error;
            $res['msg'] = lang('no_operate');
            die(json_encode($res));
        }
        /** 更改状态为处理中 */
        $return = $this->m_admin_helper->updateWithdrawalByIds($param['checkboxes'],$type);
        if(!$return){
            $res['status'] = 0;
            $res['msg'] = lang('update_failure');
            die(json_encode($res));
        }
		foreach($param['checkboxes'] as $uid){
			if($type==1){
				$this->load->model('m_user');
				$log = current($this->m_admin_helper->getWithdrawalByIds(array($uid)));
				$user = current($this->m_user->getInfo($log['uid']));
				/** 提现处理邮件 */
				$this->m_admin_helper->sendProcessedWithdrawalEmail($user['email'],$user['country_id']);
			}
		}
        $res['status'] = 1;
        die(json_encode($res));
    }

    function process(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            $result = array(
                'check_time'=>date('Y-m-d H:i:s',time()),
                'check_admin'=>$this->_adminInfo['id'],
                'status'=>$status,
            );
            if($status == 3){
                $result['check_info'] = $this->input->post('info',true);
            }
            $this->load->model('m_admin_user');
            $count = $this->m_admin_user->updateWithdrawalInfo($id,$result);

            if($count){
                if($status == 3 || $status==1){
                    $this->load->model('m_user');
                    $log = current($this->m_admin_helper->getWithdrawalByIds(array($id)));
                    $user = current($this->m_user->getInfo($log['uid']));
					if($status == 3 && $user['country_id'] == 1 ){ /** 只有中国会员，发送拒绝提现邮件 */
						$this->m_admin_helper->sendRejectWithdrawalEmail($result['check_info'],$user['email']);
					}
					if($status == 1){ /** 提现处理邮件 */
						$this->m_admin_helper->sendProcessedWithdrawalEmail($user['email'],$user['country_id']);
					}
                }
                echo json_encode(array('success'=>1,'msg'=>'Update Success'));exit;
            }else{
                echo json_encode(array('success'=>0,'msg'=>lang('update_failure')));exit;
            }
        }
    }

	/** 导出csv并锁定maxie mobile的提现 */
	public function export_maxie(){
		$searchData = $this->input->get();
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['is_export_lock'] = isset($searchData['is_export_lock'])?$searchData['is_export_lock']:'';
		$searchData['country_id'] = isset($searchData['country_id'])?$searchData['country_id']:'';

		$this->db->select('c.id,c.card_number,c.uid,c.amount,c.remark,c.create_time')->from('cash_take_out_logs c')->join('users u','u.id=c.uid')
			->where('c.take_out_type',4)->where('c.status',0);

		if($searchData['start']){
			$this->db->where("c.create_time >=",$searchData['start']);
		}if($searchData['end']){
			$end_deliver_date = date("Y-m-d H:i:s", strtotime($searchData['end']) + 86400 - 1);
			$this->db->where("c.create_time <=",$end_deliver_date);
		}
		if($searchData['country_id']){
			$this->db->where('u.country_id', $searchData['country_id']);
		}

		$data = $this->db->get()->result_array();
		$fields = array('CardNo','MemberID','Amount','Description','Application Date','Name','Email','Mobile');
		$filename = 'Maxie_mobile_'.date('Y-m-d',time()).'_'.time();

		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
		header('Cache-Control: max-age=0');
		$fp = fopen('php://output', 'a');

		fputcsv($fp, $fields);

		$amount = 0;

		foreach ($data as $v) {
			$name = $this->db->select('name,email,mobile')->where('id',$v['uid'])->get('users')->row_array();
			$v['name'] = iconv('utf-8', 'gbk', $name['name']);
			$v['email'] = $name['email'];
			$v['mobile'] = $name['mobile'];
			foreach ($v as $k => $item) {
				if($k == 'remark'){
					$v[$k] = "\t".iconv('utf-8', 'GBK//ignore', $item);
				}else{

					$v[$k] = "\t".$item;
				}
			}

			$amount += $v['amount'];

			$update_data[] = array(
				'id'=>$v['id'],
				'status'=>2,
			);
			unset($v['id']);
			fputcsv($fp, $v);
		}
		fputcsv($fp,array("\t"));
		fputcsv($fp, array("\t","Total:",$amount));

		if($searchData['is_export_lock']){
			$this->db->update_batch('cash_take_out_logs',$update_data,'id');
		}
	}

}