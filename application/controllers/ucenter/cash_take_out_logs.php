<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cash_take_out_logs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_user');
    }

    /**
     * @ author m by brady.wang 2017/05/31
     * @ desc redis缓存改造 key  ucenter:cash_take_out_logs:  如果不传递时间参数，可以走缓存
     *
     */
    public function index() {
        $this->_viewData['title'] = lang('cash_take_out_logs');
        $logs = [];

        $getData = $this->input->get();
        $page = max((int)(isset($getData['page']) ? $getData['page'] : 1), 1);
        $perPage = 10;
        $uid = $this->_userInfo['id'];
        $start = !empty($getData['start']) ? $getData['start'] : '';
        $end = !empty($getData['end']) ?  $getData['end'] : '';

        $redis_key = config_item("redis_key")['cash_take_out_logs'].$uid.":".$page;

        $filter = array(
            'uid' => $uid,
            'start' => $start,
            'end' => $end,
        );

        $this->load->model("tb_cash_take_out_logs");
        if(REDIS_STOP == 0 && empty($start) && empty($end)) { //走redis
            $res = $this->tb_cash_take_out_logs->redis_get($redis_key);

            if ($res == false || !is_array(json_decode($res,true))) {
              //  echo "走数据库";
                $res = $this->get_cash_account_logs_by_db($start,$end,$uid,$page,$perPage);
            } else {
                //echo "走缓存";
            }

        } else {
            echo "走数据库";
            $res = $this->get_cash_account_logs_by_db($start,$end,$uid,$page,$perPage);
        } //未打开redis 否则走数据库

        $res = json_decode($res,true);
        $logs = $res['logs'];
        $total_rows = $res['total_rows'];

        if(!empty($logs)) {
            foreach ($logs as &$log) {
                if($log['take_out_type'] == 2) continue;
                $len = strlen($log['card_number']) - 4;
                $star = '';
                for ($i = 1; $i <= $len; $i++) {
                    $star .= '*';
                }
                $log['card_number'] = substr_replace($log['card_number'], $star, 0, $len);
            }
        }

        $this->_viewData['list'] = $logs;
        $this->load->library('pagination');
        $pager_param = [
            'page' => $page,
            'page_size' => $perPage,
            'start'=>$start,
            'end'=>$end
        ];
        $this->_viewData['pager'] = $this->tb_cash_take_out_logs->get_pager("ucenter/cash_take_out_logs", $pager_param, $total_rows,true);
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;

        parent::index();
    }

    public function get_cash_account_logs_by_db($start,$end,$uid,$page,$page_size)
    {
        $redis_key = config_item("redis_key")['cash_take_out_logs'].$uid.":".$page;
        $this->load->model("tb_cash_take_out_logs");
        if($end >= $start) {
            $params = [
                'select' => "*",
                'where' => [
                    'uid' => $uid,
                    'DATE_FORMAT(create_time, "%Y-%m-%d") >=' => $start,
                    'DATE_FORMAT(create_time, "%Y-%m-%d") <=' => empty($end) ? date("Y-m-d") : $end
                ]
            ];
            $total_rows = $this->tb_cash_take_out_logs->get($params, true);
            if($total_rows > 0) {
                $total_page = ceil($total_rows / $page_size);
                $page = ($page > $total_page) ? $total_page : $page;
                $params['order'] = 'id desc';
                $params['limit'] = [
                    'page' => $page,
                    'page_size' => $page_size
                ];
                $logs = $this->tb_cash_take_out_logs->get($params);
            }

        } else { //如果开始时间大于结束时间 直接返回空
            $logs = [];
            $total_rows = 0;
        }

        $res = array('logs'=>$logs,'total_rows'=>$total_rows);
        if (REDIS_STOP == 0 && empty($start) && empty($end)) {
            $redis_key = config_item("redis_key")['cash_take_out_logs'].$uid.":".$page;
            $this->load->model("tb_cash_take_out_logs");
            $this->tb_cash_take_out_logs->redis_set($redis_key,json_encode($res),config_item("redis_expire")['cash_take_out_logs']);
        }
        return json_encode($res);

    }

    public function cancel(){
        if($this->input->is_ajax_request()){
            $cookie=unserialize($_COOKIE['userInfo']);
            if(isset($cookie['readOnly']) && $cookie['readOnly']){
                echo json_encode(array('success'=>0,'msg'=>'hacker attack'));exit;
            }

            $id = $this->input->post('id');
            if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'cancel_cash_take_out_form', $this->input->post())) {
            	echo json_encode(array('success'=>0,'msg'=>'正在处理, 请稍候...'));
            	exit;
            }

            $this->load->model('m_user_helper');
            $log = $this->m_user_helper->getOneTakeCashLog($id);

            if(($log['status'] == 0 || $log['status'] == 3) && $log['uid'] == $this->_userInfo['id']){

                $this->load->model('m_commission');
                $this->db->trans_begin();

                $insert_count = $this->m_commission->commissionLogs($this->_userInfo['id'],12,$log['amount']);
                $update_count = $this->m_user_helper->addUserCash($log);
                $count = $this->m_user_helper->deleteOneTakeCashLog($id);

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                }

                if($count && $insert_count && $update_count){
                    //成功，清除该用户的缓存
                    if (REDIS_STOP == 0 ) {
                        $this->load->model("tb_cash_take_out_logs");
                        $this->tb_cash_take_out_logs->del_redis_page($this->_userInfo['id'],"cash_take_out_logs");
                    }
                    $this->db->trans_commit();
                    echo json_encode(array('success'=>1,'msg'=>'Update Success'));exit;
                }else{
                    $this->db->trans_rollback();
                    echo json_encode(array('success'=>0,'msg'=>'Update Failure'));exit;
                }

            }else{
                echo json_encode(array('success'=>0,'msg'=>'Hack'));exit;
            }
        }
    }
    
}
