<?php
/**
 * User: Ckf
 * Date: 2017/03/27
 * Time: 15:24
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class repair_users_amount extends MY_Controller {

    public function __construct() {
        parent::__construct();
//        $this->m_global->checkPermission('repair_users_amount', $this->_adminInfo);
        $this->load->model('tb_user_email_exception_list');
        $this->load->model('tb_cash_account_log_x');
        $this->load->model('tb_users');
        $this->load->model('m_log');
    }

    public function index(){
        $curComType = '';
        $this->_viewData['curComType'] = $curComType;
        $this->_viewData['commission_type'] = $this->config->item('funds_change_report');
        $this->_viewData['title'] = lang('repair_users_amount');
        parent::index('admin/');
    }

    /**
     *检验用户id
     * Ckf
     * 2017/03/27
     */
    public function checkOrderId(){
        $uid_txt=$this->input->post('uid_txt');
        //不能为空
        if(trim($uid_txt)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }
        //检查用户id是否存在
        $one = $this->tb_users->getUserInfo($uid_txt);
        if(empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }
    }

    /**
     * 查询会员资金变动总额
     * Ckf
     * 2017/03/31
     */
    public function checkData_query(){

        $txn_id=$this->input->post('uid_txt');
        //不能为空
        if(trim($txn_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }

        //检查用户id是否存在
        $one = $this->db->query("SELECT amount FROM users where id = $txn_id")->row_array();
        if(empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }
        //获取会员现金池余额
        $amount = $one['amount'];
        $sun_amount1 = $this->tb_cash_account_log_x->get_sum_amount_x_by_uid($txn_id);

        $sun_amount2 = $this->tb_cash_account_log_x->get_sum_amount_old_by_uid($txn_id);

        $sun_amount = $sun_amount1 + $sun_amount2;
//        echo $sun_amount;
        echo json_encode(array('success'=>true,'sun_amount'=>$sun_amount,'amount'=>$amount,'msg'=>''));
    }

    /**
     * 修复会员烦的现金池，以会员的资金变动为准
     * Ckf
     * 2017/03/27
     */
    public function checkData(){
        $txn_id=$this->input->post('uid_txt');
        //不能为空
        if(trim($txn_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }

        //检查用户id是否存在
        $one = $this->db->query("SELECT amount FROM users where id = $txn_id")->row_array();
        if(empty($one)){
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }
        //获取会员现金池余额
        $amount = $one['amount'];

        $sun_amount1 = $this->tb_cash_account_log_x->get_sum_amount_x_by_uid($txn_id);


        $sun_amount2 = $this->tb_cash_account_log_x->get_sum_amount_old_by_uid($txn_id);

        $sun_amount = $sun_amount1 + $sun_amount2;
        $this->db->trans_start();//事务开始
        if(round($sun_amount,2) - round($amount,2) != 0){
            $this->db->query("UPDATE users SET amount = $sun_amount WHERE id = $txn_id");
//            $this->m_log->adminActionLog($this->_adminInfo['id'],'repair_users_amount','users',$txn_id,
//                'amount',$amount,$sun_amount);
            //记录操作流水
            $this->db->insert('admin_repair_user_amount_log',array(
                'uid' => $txn_id,
                'amount_before' => $amount,
                'amount_after' => $sun_amount,
                'admin_id' => $this->_adminInfo['id'],
            ));
            $this->db->trans_complete();//事务提交
            echo json_encode(array('success'=>true,'sun_amount'=>$sun_amount,'amount'=>$amount,'msg'=>lang('update_address_successed')));
        }else{
            echo json_encode(array('success'=>false,'msg'=>lang('not_repair_amount')));
        }
    }
    
    /**
     * 导出excel
     */
    public function export()
    {
        $txn_id=$this->input->post('uid_txt');
        $com_type=$this->input->post('com_type');      
 
        $result_since_data = $this->tb_cash_account_log_x->getUserCashAccountSincesLogs($txn_id,$com_type);

        $result_data = $this->tb_cash_account_log_x->getUserCashAccountLogs($txn_id,$com_type);
        $commission_type = $this->config->item('funds_change_report');
        $item_value = "";
        $filename = 'user_cash_account_logs_'.$txn_id;        
        $str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>";
        $str .="<table border=1>";     
        $str .= "<tr><td>发奖类型</td><td>金额</td><td>时间</td></tr>";
        
                
        if (! empty($result_since_data)) {
            foreach ($result_since_data as $sv) {
                foreach ($commission_type as $key => $item_type)
                {
                    if($sv['item_type']==$key)
                    {
                        $item_value = lang($item_type);
                        break;
                    }
                }
                $str.="<tr><td>";
                $str.= $item_value; 
                $str.= "</td><td>";
                $str.= $sv['amount'];
                $str.= "</td><td>";
                $str.= $sv['create_time']; 
                $str.= "</td></tr>";
            }
        }
        
        if (! empty($result_data)) {
            foreach ($result_data as $sult) {
                if (is_array($sult) && ! empty($sult)) {
                    foreach ($sult as $st) {
                        if (is_array($st) && ! empty($st)) {
                            foreach ($st as $sv) {
                                foreach ($commission_type as $key => $item_type)
                                {
                                    if($sv['item_type']==$key)
                                    {
                                        $item_value = lang($item_type);
                                        break;
                                    }
                                }
                                 $str.="<tr><td>";
                                    $str.= $item_value; 
                                    $str.= "</td><td>";
                                    $str.= $sv['amount']; 
                                    $str.= "</td><td>";
                                    $str.= $sv['create_time']; 
                                    $str.= "</td></tr>";
                            }
                        }
                    }
                }
            }
        }
        echo $str;                
        echo "</table></body></html>";
        header( "Content-Type: application/vnd.ms-excel; name='excel'" );
        header( "Content-type: application/octet-stream" );
        header( "Content-Disposition: attachment; filename=".$filename.".xls" );
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );        
        exit();
    }
    
    
    
    
    
    
    
}