<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_user');
    }

    public function index(){
        $this->_viewData['title'] = lang('welcome_page');
        $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        $this->_viewData['user'] = $user;

        if($user['status'] == 2){
            $user_month_fail = $this->db->from('users_month_fee_fail_info')->where('uid',$user['id'])->get()->row_array();

            if(!$user_month_fail){
                $user_month_fail['create_time'] = time();
            }else{
                $user_month_fail['create_time'] = strtotime($user_month_fail['create_time']);
            }
            $user_date_start = date('Y-m-d',$user_month_fail['create_time']);
            $user_date_end = date('Y-m-d',strtotime('+1 month',$user_month_fail['create_time']));
            $this->_viewData['alert_tip'] = sprintf(lang('freeze_tip_content'),$user_date_start,$user_date_end);
        }
        $this->load->model('m_news');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $lists = $this->m_news->getBoardList($searchData,6,'show');

        $this->load->library('pagination');
        $url = 'ucenter/welcome';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_news->getBoardListRows($searchData,true);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['boards'] = $lists;

        $monthlyFeeCoupon = $this->m_user->getMonthlyFeeCoupon($this->_userInfo['id'],0);
        if($monthlyFeeCoupon){
            switch ($monthlyFeeCoupon['coupon_level']) {
                case 1:
                    $monthlyFeeCoupon['img_name'] = 'monthly_fee_coupon_diamond';
                    break;
                case 2:
                    $monthlyFeeCoupon['img_name'] = 'monthly_fee_coupon_gold';
                    break;
                case 3:
                    $monthlyFeeCoupon['img_name'] = 'monthly_fee_coupon_silver';
                    break;
                default:
                    break;
            }
            
        }
        $this->_viewData['monthlyFeeCoupon'] = $monthlyFeeCoupon;
        if(!$monthlyFeeCoupon){
            if($this->m_user->getMonthlyFeeCoupon($this->_userInfo['id'])){
                $noMonthlyFeeCouponMsg = lang('no_active_monthly_fee_coupon');
            }else{
                $noMonthlyFeeCouponMsg = lang('free_mem_have_no_monthly_fee_coupon');
            }
        }else{
            $noMonthlyFeeCouponMsg = lang('no_active_monthly_fee_coupon');
        }
        $this->_viewData['noMonthlyFeeCouponMsg'] = $noMonthlyFeeCouponMsg;
        parent::index();
    }

    /*使用月费抵用券ajax*/
    public function useMonthlyFeeCouponAjax(){
        $error_code = $this->m_user->useMonthlyFeeCoupon($this->_userInfo['id'],$this->_userInfo['month_fee_pool']);
        if(!$error_code){
            $success = TRUE;
            $msg = lang('user_monthli_fee_coupon_success');
        }else{
            $success = false;
            $msg = lang(config_item('error_code')[$error_code]);
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }
}

