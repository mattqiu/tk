<?php
/**
 * User: Able
 * Date: 2017/3/20
 * Time: 9:54
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
header("Content-type: text/html; charset=utf-8");

class bonus_plan extends MY_Controller{


    protected  $configs = array(
        6 => 'week_profit_sharing', //6=>每天全球利润分红
        24 => 'daily_top_performers_sales_pool', //24=>销售精英日分红
        1 => '2x5_force_matrix', //1=>每月团队组织分红奖
        8 => 'month_leader_profit_sharing', //8=>每月杰出店铺分红
        2 => '138_force_matrix', //2=>138见点佣金
        25 => 'plan_week_share', //每周团队分红
        //7 => 'week_leader_matching', //7=>每周领导对等奖
        23 => 'month_middel_leader_profit_sharing', //23=>每月领导分红奖
       // 4 => 'group_sale_infinity', //4=>团队无限代    更改为  每月总裁销售奖
        26 => 'new_member_bonus' //新用户专属奖金
    );

    //类型 1 日奖  2 周奖 3月奖
    protected $config_type = array(
        6 =>1,
        24 =>1,
        2 =>1,
        26 =>1,
        25 =>2,
        7 =>2,
        8 =>3,
        1 =>3,
        23 =>3,
        4 =>3
    );


    public function __construct(){
        parent::__construct();
        $this->load->model('td_system_rebate_conf');
        $this->load->model('td_system_rebate_conf_child');
        $this->load->model('tb_bonus_plan_control');        
        $this->m_global->checkPermission('bonus_plan',$this->_adminInfo);
    }

    public function index(){
        $this->_viewData['title'] = lang('system_bonus_ratio');
        $rule = ['rate_a','rate_b','rate_c','rate_d','rate_e'];
        $bonus_type = [1=>'日奖',2=>'周奖',3=>'月奖'];

        $commission_type = $this->configs;
        $config_type = $this->config_type;
        $brouns_param_numbers = $this->config->item('brouns_param_numbers');
        $getBonusList = $this->td_system_rebate_conf->getBonusList();
        krsort($commission_type);
        foreach($commission_type as $k=>$v){
            $arr[$config_type[$k]][$k]['title'] = $v;
            $arr[$config_type[$k]][$k]['category_id'] = $k;
            $output = array_slice($rule,0,count($brouns_param_numbers[$k]));
            $arr[$config_type[$k]][$k]['param_name'] = array_combine($output, $brouns_param_numbers[$k]);
            $arr[$config_type[$k]][$k]['ischild'] = 0;
            foreach($getBonusList as $key=>$val){
                if($val['category_id'] == $k){
                    if($val['child_id']>0){
                        $arr[$config_type[$k]][$k]['ischild'] = 1;
                    }
                    $arr[$config_type[$k]][$k]['data'][] = $getBonusList[$key];
                    $arr[$config_type[$k]][$k]['param'] = [];
                    foreach ($arr[$config_type[$k]][$k]['param_name'] as $kk=>$u){
                        $arr[$config_type[$k]][$k]['param'][] = $kk;
                    }
                }
            }
        }
        $row = $this->tb_bonus_plan_control->getState(26);
        $showHide = 1;
        if(!empty($row)){
            if(date("Y-m-d",$row['exec_end_time']) < date("Y-m-d") || $row['status'] == 1){
                $showHide = 0;
            }
        }
        //每天10点25分后模拟按钮自动消失
        if(date("Hi")>1025){
            $showHide = 1;
        }
        $this->_viewData['showhide'] = $showHide;
        $this->_viewData['list'] = $arr;
        $this->_viewData['bonus_type'] = $bonus_type;
        parent::index('admin/');
    }

    /*
     *  浮层
     */
    public function bronus_plan_html(){
        $method = $this->input->get('method');
        $id = $this->input->get('id');
        $this->_viewData['bonusData'] = "";
        if($method == "edit"){
            $this->_viewData['bonusData'] = $this->td_system_rebate_conf->getOne(['id'=>$id]);
        }
        $this->_viewData['commission_type'] = $this->config->item('commission_type');
        //$this->_viewData['brouns_param_number'] = $this->config->item('brouns_param_number');
        $this->_viewData['brouns_param_numbers'] = $this->config->item('brouns_param_numbers');
        $this->_viewData['commission_type_child'] = $this->td_system_rebate_conf_child->getList();
        $this->_viewData['sql_param'] = ['rate_a','rate_b','rate_c','rate_d','rate_e'];
        $this->load->view('admin/add_bronus_plan',$this->_viewData);
    }

    /*
     * 操作分红
     */
    public function op_bonus(){
        $data = $this->input->post();
        $id = $this->input->get('id');
        $rule = ['rate_a','rate_b','rate_c','rate_d','rate_e'];
        if($id == ""){
            //验证有效性
            $this->load->library('form_validation');
            //设置规则
            $this->form_validation->set_rules('rate_a', 'rate_a', 'numeric');
            $this->form_validation->set_rules('rate_b', 'rate_b', 'numeric');
            $this->form_validation->set_rules('rate_c', 'rate_c', 'numeric');
            $this->form_validation->set_rules('rate_d', 'rate_d', 'numeric');
            $this->form_validation->set_rules('rate_e', 'rate_e', 'numeric');

            if ($this->form_validation->run() == false){
                echo json_encode(array('success' => false, 'msg' => lang("yz_int_length")));
                exit;
            }

            $data = $this->calculateBonus($rule,$data);

            $dataRow = $this->td_system_rebate_conf->getOne(['category_id'=>$data['category_id'],'child_id'=>$data['child_id']]);
            if(!empty($dataRow)){
                echo json_encode(array('success' => false, 'msg' => lang('data_extis')));
                exit;
            }
            $row = $this->td_system_rebate_conf->addBounsPlan($data);
            if($row>0){
                echo json_encode(array('success' => true, 'msg' => lang('add_success')));
            }else{
                echo json_encode(array('success' => false, 'msg' => lang('add_faild')));
            }
        }else{
            /*$dataRow = $this->td_system_rebate_conf->getOne(['category_id'=>["=",$data['category_id']],'child_id'=>["=",$data['child_id']],"id"=>["<>",$id]]);
            if(!empty($dataRow)){
                echo json_encode(array('success' => false, 'msg' => lang('data_extis')));
                exit;
            }*/
            $arr = explode(",",$data['postData']);
            $row = 0;
            $childId = $this->input->post("child_id");

            if(empty($childId)){

                $wid = $this->input->get('wid');
                //截取数组
                $output = array_slice($rule,0,count($arr));
                //重新组装
                $data = $this->calculatePercentage(array_combine($output, $arr));
                $row = $this->td_system_rebate_conf->updateBounsPlan(["id"=>$wid],$data);
            }else{
                $arrChild = explode(",",$this->input->post("child_id"));
                if($id == 23){
                    for($i=0;$i<count($arr)/2;$i++){
                        $chonSort[] = [$arr[2*($i*1)],$arr[2*($i*1)+1]];
                    }
                    foreach($chonSort as $k=>$v){
                        //截取数组
                        $output = array_slice($rule,0,count($v));
                        //重新组装
                        $data = $this->calculatePercentage(array_combine($output, $v));
                        $row = $this->td_system_rebate_conf->updateBounsPlan(["id"=>$arrChild[$k]],$data);
                    }

                }else{
                    foreach ($arrChild as $v){
                        $chonSort[] = [$arr[0],array_pop($arr)];
                    }
                    foreach(array_reverse($chonSort) as $k=>$v){
                        //截取数组
                        $output = array_slice($rule,0,count($v));
                        //重新组装
                        $data = $this->calculatePercentage(array_combine($output, $v));
                        $row = $this->td_system_rebate_conf->updateBounsPlan(["id"=>$arrChild[$k]],$data);
                    }
                }
            }
            //if($row>0){
                echo json_encode(array('success' => true, 'msg' => lang('up_success')));
            //}else{
                //echo json_encode(array('success' => false, 'msg' => lang('up_faild')));
           // }
        }
        exit;
    }

    /*
     * 删除分红
     */
    public function delete_bonus(){
        $data = $this->input->post();
        $row = $this->td_system_rebate_conf->deleteBonusPlan($data['id']);
        if($row>0){
            echo json_encode(array('success' => true, 'msg' => lang('delete_success')));
        }else{
            echo json_encode(array('success' => false, 'msg' => lang('delete_faild')));
        }
        exit;
    }


    //预发奖
    public function pre_expansion(){
        $this->load->model(array("tb_grant_pre_users_new_member_bonus","tb_grant_pre_bonus_state"));
        $_post_id = $this->input->post("id");
        if(empty($_post_id)){
            echo json_encode(array('success'=>false,'message'=>''));
            exit;
        }
        $rowNum = $this->tb_grant_pre_users_new_member_bonus->get_total_rows();
        if($rowNum==0){
            $this->tb_grant_pre_bonus_state->edit_state($_post_id,0,"");
        }
        $this->load->model("tb_system_grant_bonus_queue_list");

        if($this->tb_system_grant_bonus_queue_list->getTotal($_post_id)==0){
            $this->tb_system_grant_bonus_queue_list->add($_post_id,1);
        }
        echo json_encode(array('success'=>true,'message'=>'预发已开始，请稍等！'));
        exit;
    }

    /*//预发状态查询
    public function pretest_status_query(){
        $_get_id = $this->input->get("id");
        if(empty($_get_id)){
            echo json_encode(array('success'=>false,'data'=>''));
            exit;
        }
        $row = $this->tb_bonus_plan_control->getState($_get_id);
        echo json_encode(array('success'=>true,'data'=>$row));
        exit;
    }*/


    public function pre_calculate(){
        $id = $this->input->get("id");
        $brouns_param_numbers = $this->config->item('brouns_param_numbers');
        $rule = ['rate_a','rate_b','rate_c','rate_d','rate_e'];
        $output = array_slice($rule,0,count($brouns_param_numbers[$id]));
        $data['param_name'] = array_combine($output, $brouns_param_numbers[$id]);
        $getBonusList= $this->td_system_rebate_conf->getOne($id);
        $data['ischild'] = 0 ;
        foreach($getBonusList as $key=>$val){
            if($val['child_id']>0){
                $data['ischild'] = 1;
                //$data['proportion'] = $val['rate_a'];
            }
            $data['data'][] = $getBonusList[$key];
        }
        $data['rule'] = $output;

        $memUid = $this->td_system_rebate_conf->get_new_gran_new_member();
        $logs = $this->td_system_rebate_conf->getCashLog_new($memUid['uid'],$id,date("Y-m-d"));
        $amount = $this->get_bonus($memUid['uid'],$getBonusList[0]['rate_a']);

        $num = $this->td_system_rebate_conf->getCashByUid($logs[0]['uid'],date("Y-m-d"),26);

        $this->_viewData['logs'] =$logs;
        $this->_viewData['isusd'] =$num;
        $this->_viewData['amount'] = $amount;
        $this->_viewData['category_id'] = $data['data'][0]['category_id'];
        $this->load->view('admin/pre_calculate_table',$this->_viewData);
    }


    public function get_bonus($uid,$rate_a){
        $this->load->model(array('o_company_money_today_total','tb_system_rebate_conf','tb_new_member_bonus_total_weight','o_bonus'));
        /*统计公司昨天全球销售利润 单位美元*/
        $yesterdayProfit = $this->o_company_money_today_total->get_yesterday_profit();

        $totalMoney = tps_int_format($yesterdayProfit['money'] * $rate_a);

        $day = date("Ymd",time());
        $weight = $this->o_bonus->getUsersTotalWeightArr_new(array($uid));

        $totalWeight = $this->tb_new_member_bonus_total_weight->get_by_day($day);

        $amount = tps_int_format($weight[$uid]['total_money'] / $totalWeight*$totalMoney);
        //echo $weight[$uid]['total_money']."/".$totalWeight."*(".$yesterdayProfit['money']."*".$rate_a.")====".$amount;
        $desc_money =  $weight[$uid]['total_money'] - $weight[$uid]['level_money'];

        if ($amount >0  && $desc_money >=  5000 && $weight[$uid]['level_money'] >= 25000) {
            return ["success"=>true,"bonus_shar_weight"=>$weight[$uid]['total_money'],"totalWeight"=>$totalWeight,"yesterdayProfit"=>$yesterdayProfit['money'],'amount'=>$amount/100];
        }else{
            return ["success"=>false,"bonus_shar_weight"=>0,"totalWeight"=>$totalWeight,"yesterdayProfit"=>$yesterdayProfit['money'],'level_money'=>$weight[$uid]['level_money']];
        }
    }


    /**
     * 手动发新会员奖
     */
   /* public function manualAwards(){
        $id = $this->input->post("categroy_id");
        $row = $this->tb_bonus_plan_control->getState(26);
        if(!empty($row)){
            if(date("Y-m-d",$row['exec_end_time']) < date("Y-m-d") || (date("Y-m-d",$row['exec_end_time']) == date("Y-m-d") && $row['status'] == 1)){
                $this->tb_bonus_plan_control->changeExecStatus($id,['ishand'=>1]);
                echo json_encode(array('success'=>true));
                exit;
            }
        }
        echo json_encode(array('success'=>false));
        exit;
    }*/

   /* public function manualAwardsIng(){
        $id = $this->input->get("id");
        $this->tb_bonus_plan_control->changeExecStatus($id,['ishanding'=>1]);
        echo json_encode(array('success'=>true));
        exit;
    }*/

    //手动发新会员奖状态查询
    public function manual_status_query(){
        $_get_id = $this->input->get("id");
        if(empty($_get_id)){
            echo json_encode(array('success'=>false,'data'=>''));
            exit;
        }
        $row = $this->tb_bonus_plan_control->getState($_get_id);
        echo json_encode(array('success'=>true,'data'=>$row));
        exit;
    }



    /**
     * 计算浮层中的保存百分比
     */
    public function calculate_bonus_save(){
        //每天10点25分后模拟按钮自动消失
        if(date("Hi")>1025){
            echo json_encode(array('success'=>false));
            exit;
        }
        $ratio = round($this->input->post("ratio"),4);
        $uid = $this->input->post("uid");
        $this->load->model("tb_bonus_plan_control");
        $data = $this->tb_bonus_plan_control->getState(26);
        if($data['rate']!= $ratio){
            $this->tb_bonus_plan_control->changeExecStatus(26,['ishand'=>1]);
        }else{
            $this->tb_bonus_plan_control->changeExecStatus(26,['ishand'=>0]);
        }
        $this->td_system_rebate_conf->updateBounsPlan(["category_id"=>$uid],['rate_a'=>$ratio]);
        echo json_encode(array('success'=>true));
        exit;
    }


    public function verify_parent(){
        $pid = $this->input->post("pid");
        $rowData = $this->td_system_rebate_conf->getOne(["category_id"=>$pid],"rate_a");
        echo json_encode(array('data' => $rowData));
        exit;
    }


    private function calculateBonus($arr,$resultArr){
        foreach($resultArr as $k=>$v){
            if(in_array($k,$arr)){
                $resultArr[$k] = $v/100;
            }
        }
        return $resultArr;
    }


    private function calculatePercentage(&$resultArr){
        foreach($resultArr as $k=>$v){
            $resultArr[$k] = $v/100;
        }
        return $resultArr;
    }



}