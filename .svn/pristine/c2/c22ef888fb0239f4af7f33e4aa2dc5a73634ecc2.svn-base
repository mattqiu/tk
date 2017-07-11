<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Withdraw_table extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('withdraw_table',$this->_adminInfo);
        $this->load->model('tb_cash_take_out_logs');
    }

    public function index() {
        $this->_viewData['title'] = lang('alipay_withdraw');
        /*         * *汇率 */
        $rate = get_cookie('withdrawal_rate', true);

        //当前汇率
        //$this->m_global->checkPermission('cash_withdrawal_list', $this->_adminInfo);
        $this->_viewData['rate'] = $rate ? sprintf("%.2f",$rate) : sprintf("%.2f",$this->m_global->get_rate('CNY'));
        /*         * ** */
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 100;
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid'] : '';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['card_number'] = isset($searchData['card_number']) ? $searchData['card_number'] : '';
        $searchData['batch_num'] = isset($searchData['batch_num']) ? $searchData['batch_num'] : '';

        $logs = $this->tb_cash_take_out_logs->get_all_cash_take_out_logs($searchData, $searchData['page'], $searchData['page_num']);

        $tongji = array('zshuliang'=>0,'zjine'=>0,'sxf'=>0,'sjje'=>0);
        foreach ($logs['list'] as $log) {
//            if($log['status']!=3) {
                $tongji['zshuliang']+=1;
                $tongji['zjine']+=$log['amount'];
                $tongji['sxf']+=$log['handle_fee'];
                $tongji['sjje']+=$log['actual_amount'];
//            }
        }
        $tongji['format_sjje'] = '￥'.sprintf("%.2f",$tongji['sjje']*$this->_viewData['rate']);
        $this->_viewData['tongji'] = $tongji; //统计
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');

        $url = 'admin/withdraw_table';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = $searchData['page_num'];
        $config['total_rows'] =  $logs['num'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['page_num'] = $searchData['page_num'];
        $this->_viewData['searchData'] = $searchData;
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

    //生成唯一批次号
    public function only_name() {
        $num = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        echo json_encode(array('success' => TRUE, 'msg' => $num));
    }

    //批次入库
    public function generate_batch() {
        $post_array = $this->input->post();
        $post_array['batch_num']=$post_array['batch_number'];
        unset($post_array['batch_number']);
        //-------------此处查看是否已有批次
        $isy = $this->tb_cash_take_out_logs->select_batch_num($post_array);
        if ($isy['batch_num']) {
            echo json_encode(array('success' => FALSE, 'msg' => 8));
            exit;
        }
        //-------------------------------
        /*         * *汇率 */
        $rate = get_cookie('withdrawal_rate', true);
        //$rate = $rate ? $rate : $this->m_global->get_rate('CNY');
        //当前汇率
        //$this->m_global->checkPermission('cash_withdrawal_list', $this->_adminInfo);
        $exchange_rate = $rate ? $rate : $this->m_global->get_rate('CNY');
        /*         * ** */
        $batch = array(
            'batch_num' => $post_array['batch_num'],
            'total' => $post_array['total'],
            'lump_sum' => $post_array['lump_sum'],
            'reason' => $post_array['payment_reason'],
            'born_time' => date("Y-m-d H:i:s", time()),
            'exchange_rate'=>$exchange_rate,
        );
        $is = $this->tb_cash_take_out_logs->insert_batch_num($batch, $post_array['checkboxes']);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }

    //一审单项操作
    public function alone_check_one() {
        $post_array = $this->input->post();
        $where['id'] = $post_array['id'];
        $batch['status'] = $post_array['status'];
        if ($post_array['cause']) {
            $batch['check_info'] = $post_array['cause'];
        }
        $is = $this->tb_cash_take_out_logs->up_log_num($where, $batch);
        if ($is) {
            echo json_encode(array('success' => TRUE));
        } else {
            echo json_encode(array('success' => FALSE));
        }
    }

    /** 导出 */
    function exportWithdrawal() {
        /*         * *汇率 */
        $rate = get_cookie('withdrawal_rate', true);
        //$rate = $rate ? $rate : $this->m_global->get_rate('CNY');
        //当前汇率
       // $this->m_global->checkPermission('cash_withdrawal_list', $this->_adminInfo);
        $this->_viewData['rate'] = $rate ? $rate : $this->m_global->get_rate('CNY');
        /*         * ** */
        $searchData = $this->input->get() ? $this->input->get() : array();
        $page = max((int) (isset($searchData['page']) ? $searchData['page'] : 1), 1);
        $perPage = $searchData['page_num'] ? $searchData['page_num'] : 100;
        /*         * *要注销的提交项* */
        unset($searchData['page']);//分页
        unset($searchData['checkboxes']);//勾选按钮
        unset($searchData['page_num']);//每页数量
        unset($searchData['rate']);//汇率
        unset($searchData['batch_number']);//批次号
        unset($searchData['total']);//勾选的总数
        unset($searchData['lump_sum']);//总金额
        /*         * ************** */
        $logs = $this->tb_cash_take_out_logs->get_all_cash_take_out_logs($searchData, $page, $perPage);
        $data = $logs['list'];
        /*         * ***** */
        foreach ($data as $ky => $sd) {
            unset($sd['id']);//去掉ID
            
            array_splice($sd, 1, 0, $sd['name']);//插队
            unset($sd['name']);//插队之后，删掉原有
            
            $huilv=sprintf("%.2f", $sd['exchange_rate']?$sd['exchange_rate']:$this->_viewData['rate']);
            array_splice($sd, 5, 0, $huilv);//插队
            unset($sd['exchange_rate']);//插队之后，删掉原有
            
            array_splice($sd, 9, 0, substr($sd['create_time'], 0, 10));//插队
            unset($sd['create_time']);//插队之后，删掉原有
            
            array_splice($sd, 6, 0, sprintf("%.2f", $huilv * $sd['actual_amount']));//插队
            
            unset($sd['take_out_type']);//插队之后，删掉原有
            
            array_splice($sd, 7, 0, $sd['card_number']);
            unset($sd['card_number']);
            
            array_splice($sd, 8, 0, $sd['account_name']);
            unset($sd['account_name']);
            
            array_splice($sd, 9, 0, $sd['remark']);
            unset($sd['remark']);
            
            $sd['status'] = lang('tps_status_' . $sd['status']);
            
            $dota[] = $sd;
        }
        /*         * **** */
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';
        $objExcel = new PHPExcel();
        for ($i = 65; $i < 91; $i++) {
            $letter[] = strtoupper(chr($i));
        }
        //表头数组
        $tableheader = ['ID', lang('realName'), lang('money_num'), lang('fee_num'), lang('the_actual_amount'), lang('exchange_rate'), 'CNY(￥)', lang('paypal_account_t'), lang('payee_name'), lang('admin_order_remark'),lang('refuse_reason'), lang('application_time'), lang('status'), lang('batch_number')];
        //填充表头信息
        for ($i = 0; $i < count($tableheader); $i++) {
            $objExcel->getActiveSheet()->getColumnDimension("$letter[$i]")->setWidth(15);
            $objExcel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
        }
        //填充表格信息
        for ($i = 2; $i <= count($dota) + 1; $i++) {
            $j = 0;
            foreach ($dota[$i - 2] as $key => $value) {
                $objExcel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value ");
                $j++;
            }
        }
        ob_end_clean(); //清除缓冲区,避免乱码
        $filename = 'Withdrawal_' . date('Y-m-d', time()) . '_' . time();
        header('content-Type:application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}
