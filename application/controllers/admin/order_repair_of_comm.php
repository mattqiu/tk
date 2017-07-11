<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class order_repair_of_comm extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->m_global->checkPermission('order_repair_of_comm',$this->_adminInfo);
    }

    public function index(){

        $this->load->model('tb_withdraw_task');
        $this->_viewData['title'] = lang('commission_order_repair');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $this->_viewData['list'] = $this->tb_withdraw_task->getCommOrderRepariList($searchData);

        $this->load->library('pagination');
        $url = 'admin/order_repair_of_comm';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_withdraw_task->getCommOrderRepariNum($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    public function modify_deadline_day(){

        $this->load->model('tb_withdraw_task');
        $postData = $this->input->post();
        $postData['modifyVal'] = (int)$postData['modifyVal'];
        if($postData['modifyVal']<=0 || $postData['modifyVal']>14){
            echo json_encode(array('success'=>false,'msg'=>lang('modifyVal_illegal')));
            exit;
        }
        $itemIdArr = explode('|', $postData['itemId']);

        $res = $this->tb_withdraw_task->getCommOrderRepariList(array('uid'=>$itemIdArr[0],'comm_type'=>$itemIdArr[1],'comm_year_month'=>$itemIdArr[2]));
        if($res){
            $create_time = current($res)['create_time'];
            $new_create_time = date( 'Y-m-d',strtotime(date('Y-m-d'))- (14-$postData['modifyVal'])*3600*24 ).substr($create_time,10);
            $this->tb_withdraw_task->updateDeadLine($itemIdArr[0],$itemIdArr[1],$itemIdArr[2],$new_create_time);
        }
        echo json_encode(array('success'=>true));
    }

    public function add_comm(){

        if(!check_right('order_repair_of_comm_right')){
            die('no permission');
        }

        $data = $this->input->post();
        $this->comm_func($data,null);
    }

    public function check_comm(){
        $data = $this->input->post();
        $data = $this->comm_func($data,1);
        if(!empty($data['uid'])){
            $str = "<div style='padding: 20px 80px;'><span>".lang('you_will_add_a_comm')."</span></br></br>";
            $str .="<span>".lang('member_id')." : <span style='color:red;'>".$data['uid']."</span></span></br>";
            $str .="<span>".lang('add_comm_year_month').": <span style='color:red;'>".$data['order_year_month']."</span></span></br>";
            $str .="<span>".lang('comm_order_type')." : <span style='color:red;'>".$data['comm_type']."</span></span></br>";
            $str .="<span>".lang('need_add_comm_mount')." : <span style='color:red;'>"."$".($data['sale_amount_lack']/100)."</span></span></div>";
            die(json_encode(array('success'=>1,'str'=>$str)));
        }else{
            $str = "<div style='padding: 30px 80px;'><span>".$data['msg']."</span></br></br>";
            die(json_encode(array('success'=>0,'str'=>$str)));
        }
    }

    public function del_comm(){
        if($this->input->is_ajax_request()){
            $id_str = $this->input->post('id');
            $ids = explode("|",$id_str);
            if($ids){
                $b = $this->db->where('uid',$ids[0])->where('comm_type',$ids[1])->where('comm_year_month',$ids[2])->delete('withdraw_task');
                if($b){
                    $result['success'] = 1;
                    $result['msg']     = lang('delete_success');
                    die(json_encode($result));
                }
            }
        }
        $result['success'] = 0;
        $result['msg']     = lang('delete_failure');
        die(json_encode($result));
    }

    private function comm_func($data,$sign){

        $admin_id = '';
        if (!empty($this->_adminInfo)) {
            $admin_id = $this->_adminInfo['id'] ;
        }


        $this->load->model('tb_withdraw_task');
        $success = 1;
        $msg     = '';
        if(empty($data['comm_uid']) || !is_numeric($data['comm_uid'])){
            $success = 0;
            $msg     = lang('pls_t_uid');
        }
        if(empty($data['comm_date'])){
            $success = 0;
            $msg     = lang('pls_select_order_year');
        }
        if(empty($data['comm_type'])){
            $success = 0;
            $msg     = lang('pls_select_comm_order_type');
        }
        if(substr($data['comm_date'],0,7) >date('Y-m')){
            $success = 0;
            $msg     = lang('cur_month_add_comm_ban');
        }
        $userInfo = $this->db->from('users')->where('id',$data['comm_uid'])->get()->row_array();
        if($success && $userInfo){
            $uid = $data['comm_uid'];
            $comm_type = $data['comm_type'];
            $order_year_month = date('Ym',strtotime($data['comm_date']));
            $comm_year_month =  $order_year_month>=date('Ym') ? date('Ym'): date('Ym',strtotime("+1 month",strtotime($data['comm_date'])));
            $sale_amount_lack = 0;
            //计算 sale_amount_lack
            $this->load->model('tb_users_store_sale_info_monthly');
            $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($uid,$order_year_month);

            if($order_year_month < '201703'){

                switch($comm_type){//旧规则，201702(包括02) 以前。。

                    case 6:{//每天全球利润分红
                        if($userInfo['user_rank']==4){
                            $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;
                        }else{
                            $sale_amount_lack = (25*100 - $userSaleAmountMonth) > 0 ? 25*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }
                    case 2:{//138 见点佣金
                        $sale_amount_lack = (50*100 - $userSaleAmountMonth) > 0 ? 50*100 - $userSaleAmountMonth : 0;
                        break;
                    }
                    case 24:{//销售精英日分红
                        $sale_amount_lack = (250*100 - $userSaleAmountMonth) > 0 ? 250*100 - $userSaleAmountMonth : 0;
                        break;
                    }
                    case 8:{//每月杰出店铺分红
                        if(in_array($userInfo['user_rank'],array(1,2,3)) && in_array($userInfo['sale_rank'],array(1,2,3,4,5))){
                            $sale_amount_lack = (75*100 - $userSaleAmountMonth) > 0 ? 75*100 - $userSaleAmountMonth : 0;
                        }elseif(in_array($userInfo['user_rank'],array(1,2,3))){
                            $sale_amount_lack = (375*100 - $userSaleAmountMonth) > 0 ? 375*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }
                    case 7:{//周领导对等奖
                        if($userInfo['user_rank']==1 && in_array($userInfo['sale_rank'],array(3,4,5))){
                            $sale_amount_lack = (75*100 - $userSaleAmountMonth) > 0 ? 75*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }
                    case 23:{//每月领导分红奖
                        if($userInfo['user_rank']==1){
                            if($userInfo['sale_rank']==3){
                                $sale_amount_lack = (75*100 - $userSaleAmountMonth) > 0 ? 75*100 - $userSaleAmountMonth : 0;
                            }elseif($userInfo['sale_rank']==4){
                                $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;
                            }elseif($userInfo['sale_rank']==5){
                                $sale_amount_lack = (150*100 - $userSaleAmountMonth) > 0 ? 150*100 - $userSaleAmountMonth : 0;
                            }
                        }
                        break;
                    }
                    case 4:{//团队总裁奖
                        if($userInfo['user_rank']==1 && $userInfo['sale_rank']==5){
                            $sale_amount_lack = (250*100 - $userSaleAmountMonth) > 0 ? 250*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }

                    case 1:{//每月团队组织分红奖

                        if(in_array($userInfo['user_rank'],array(1,2,3,5))){

                            $sale_amount_lack = (75*100 - $userSaleAmountMonth) > 0 ? 75*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }

                    case 25:{//每周团队销售分红

                        if(in_array($userInfo['user_rank'],array(1,2,3)) && in_array($userInfo['sale_rank'],array(1,2,3,4,5))){

                            $sale_amount_lack = (75*100 - $userSaleAmountMonth) > 0 ? 75*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }

                    default:{
                        break;
                    }

                }

            }else{//新规则，201703(包括03)以后。。。

                switch($comm_type){
                    case 6:{//每天全球利润分红
                        if($userInfo['user_rank']==4){
                            $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;
                        }else{
                            $sale_amount_lack = (25*100 - $userSaleAmountMonth) > 0 ? 25*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }
                    case 2:{//138 见点佣金 店铺等级铜级或以上

                        if(in_array($userInfo['user_rank'],array(1,2,3,5)))
                        {

                            $sale_amount_lack = (50*100 - $userSaleAmountMonth) > 0 ? 50*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }
                    case 24:{//销售精英日分红
                        $sale_amount_lack = (250*100 - $userSaleAmountMonth) > 0 ? 250*100 - $userSaleAmountMonth : 0;
                        break;
                    }
//                case 8:{//每月杰出店铺分红
//                    if(in_array($userInfo['user_rank'],array(1,2,3)) && in_array($userInfo['sale_rank'],array(1,2,3,4,5))){
//                        $sale_amount_lack = (75*100 - $userSaleAmountMonth) > 0 ? 75*100 - $userSaleAmountMonth : 0;
//                    }elseif(in_array($userInfo['user_rank'],array(1,2,3))){
//                        $sale_amount_lack = (375*100 - $userSaleAmountMonth) > 0 ? 375*100 - $userSaleAmountMonth : 0;
//                    }
//                    break;
//                }

                    case 8:{//每月杰出店铺分红 修改:20170406
                        if(in_array($userInfo['user_rank'],array(1,2,3)) && in_array($userInfo['sale_rank'],array(1,2,3,4,5)))
                        {

                            $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }


                    case 7:{//周领导对等奖 修改:20170406  75->100
                        if($userInfo['user_rank']==1 && in_array($userInfo['sale_rank'],array(2,3,4,5)))
                        {

                            $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }
                    case 23:{//每月领导分红奖   修改:20170406
                        if($userInfo['user_rank']==1){//钻石

                            if($userInfo['sale_rank']==3){//高级市场主管 75->100

                                $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;

                            }elseif($userInfo['sale_rank']==4){//市场总监 100->200

                                $sale_amount_lack = (200*100 - $userSaleAmountMonth) > 0 ? 200*100 - $userSaleAmountMonth : 0;

                            }elseif($userInfo['sale_rank']==5){//全球销售副总裁  150->300

                                $sale_amount_lack = (300*100 - $userSaleAmountMonth) > 0 ? 300*100 - $userSaleAmountMonth : 0;

                            }
                        }
                        break;
                    }
                    case 4:{//团队总裁奖
                        if($userInfo['user_rank']==1 && $userInfo['sale_rank']==5){
                            $sale_amount_lack = (250*100 - $userSaleAmountMonth) > 0 ? 250*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }

                    case 26:{//新会员专享奖
                        if(in_array($userInfo['user_rank'],array(1,2,3,5)))
                        {
                            $sale_amount_lack = (50*100 - $userSaleAmountMonth) > 0 ? 50*100 - $userSaleAmountMonth : 0;
                        }
                        break;
                    }

                    case 1:{//每月团队组织分红奖

                        if($userInfo['user_rank']==1 && in_array($userInfo['sale_rank'],array(2,3,4,5))){//钻石 市场主管或以上

                            $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }

                    case 25:{//每周团队销售分红

                        if($userInfo['user_rank']==1 && in_array($userInfo['sale_rank'],array(1,2,3,4,5))){//钻石 资深店主及以上

                            $sale_amount_lack = (100*100 - $userSaleAmountMonth) > 0 ? 100*100 - $userSaleAmountMonth : 0;

                        }
                        break;
                    }

                    default:{
                        break;
                    }

                }

            }

            if($sale_amount_lack !=0 && $sign==null){
                $this->tb_withdraw_task->addOne($uid,$comm_type,$comm_year_month,$order_year_month,$sale_amount_lack,$admin_id);
                $success = 1;
                $msg     = lang('add_comm_success');
            }elseif($sale_amount_lack !=0 && $sign!=null){
                $arr = array(
                    'uid'       =>$uid,
                    'comm_type' =>lang(config_item('commission_type_for_order_repair')[$comm_type]),
                    'order_year_month'=>$order_year_month,
                    'sale_amount_lack'=>$sale_amount_lack,
                );
                return $arr;
            } else{
                $success = 0;
                $msg     = lang('add_comm_fail');
            }
        }
        if($success && !$userInfo){
            $success = 0;
            $msg     = lang('add_comm_fail');
        }
        if($sign==null){
            die(json_encode(array('success'=>$success,'msg'=>$msg)));
        }else{
            return array('success'=>$success,'msg'=>$msg);
        }
    }

}

