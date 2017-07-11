<?php
/**
 * Created by Andy
 * mvp 颁奖报名名单
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class mvp_apply_list extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('tb_mvp_list');
    }

    public function index(){
        $this->_viewData['title'] = lang('mvp_apply_list');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['id_or_email_or_name'] = isset($searchData['id_or_email_or_name'])?$searchData['id_or_email_or_name']:'';
        $searchData['phone_number'] = isset($searchData['phone_number'])?$searchData['phone_number']:'';
        $searchData['sale_rank'] = isset($searchData['sale_rank'])?$searchData['sale_rank']:'';
        $this->_viewData['list'] = $this->tb_mvp_list->get_mvp_list($searchData);
        $url = 'admin/mvp_apply_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_mvp_list->get_mvp_list_count($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        //$this->tb_mvp_list->add_mvp(1380100287,'1380106314');
        //$arr = $this->tb_mvp_list->is_pay(1380227906);
        //var_dump($arr);
        //var_dump($arr);
        //echo $this->db->last_query();
        $this->_viewData['payment_map'] = array(
            0 => lang('admin_order_payment_unpay'),
            1 => lang('admin_order_payment_group'),
            2 => lang('admin_order_payment_coupon'),
            105 => lang('admin_order_payment_alipay'),
            106 => lang('admin_order_payment_unionpay'),
            107 => lang('admin_order_payment_paypal'),
            108 => lang('admin_order_payment_ewallet'),
            109 => lang('admin_order_payment_yspay'),
            110 => lang('admin_order_payment_amount'),
            104 => 'WxPay(微信支付)',
            111 => lang('payment_111'),
        );

        parent::index('admin/','mvp_apply_list');
    }

    public function e_excel(){
        $this->tb_mvp_list->get_mvp_list_for_excel(1);
    }

    public function e_excel_2(){//GVP
        $this->tb_mvp_list->get_mvp_list_for_excel(2);
    }
    public function e_excel_3(){//EMD
        $this->tb_mvp_list->get_mvp_list_for_excel(3);
    }

    public function e_excel_4(){
        $this->tb_mvp_list->get_mvp_list_for_excel(4);
    }

    public function e_excel_5(){
        $this->tb_mvp_list->get_mvp_list_for_excel(5);
    }

    //峰会发送信息
    public function send_msg(){
        set_time_limit(0);
        header("Content-type: text/html; charset=utf-8");
        if (isset($_FILES['user_info_excel']['type']) == null)
        {
            $result['msg'] = '文件类型错误';
            $result['success'] = 0;
            //die(json_encode($result));
            var_dump($result);exit;
        }

        $name = $_FILES['user_info_excel']['name'];

        //test
        if($name != 'test.xlsx'){
            exit;
        }

        $type = strstr($name, '.'); //限制上传格式
        if (!in_array($type,array('.xls','.xlsx'))) {
            $result['msg'] = lang('文件类型错误');
            $result['success'] = 0;
            var_dump($result);exit;
        }

        $mime = $_FILES['user_info_excel']['type'];
        $path = $_FILES['user_info_excel']['tmp_name'];

        /* 读取数据 */
        $user_info = readExcel($path, $mime);

        // 去掉第一行的标题
        unset($user_info[1]);

        if (empty($user_info))
        {
            $result['msg'] = '文件不能为空';
            $result['success'] = 0;
            //die(json_encode($result));
            var_dump($result);exit;
        }

        $user_info = $this->array_unset($user_info,0);
        /** 去除空格 */
        foreach($user_info as $k=>$item){
            foreach($item as $k1 => $value){
                $item[$k1] = trim($value);
            }
            $user_info[$k] = $item;
        }

        //var_dump($user_info);
        $send_result = array();
        $email_msg   = "您好："."<br>".
                        "很荣幸的通知您，您成功报名参加3月11-12日举行的“2017云集品TPS首届中国区领导人峰会”，参会签到时请出示本通知信息：姓名:%s，报名序号:%s，会场座位号:%s。"."<br>".
                        "每个报名序号仅限一人使用，因故委托它人参会的，以上信息不变。"."<br>".
                        "会议签到时间11日7-9点，地点：深圳宝安区宝立方中心一楼。"."<br>".
                        "因参会人数太多，请自觉遵守会场纪律。并谢绝一切空降。"."<br>".
                        "深圳前海云集品电子商务有限公司，2017年3月7日";

        $title      = '峰会通知';

        /**
        $phone_msg = "您好：
        很荣幸的通知您，您成功报名参加3月11-12日举行的“2017云集品TPS首届中国区领导人峰会”，参会签到时请出示本通知信息：姓名:%s，报名序号:%s，会场座位号:%s。
        每个报名序号仅限一人使用，因故委托它人参会的，以上信息不变。
        个别未在网上完成缴费的，可至现场缴费处缴纳。
        会议签到时间11日7-9点，地点：深圳宝安区宝立方中心一楼。
        因参会人数太多，请自觉遵守会场纪律。并谢绝一切空降。
        深圳前海云集品电子商务有限公司，2017年3月6日";
         **/

        foreach ($user_info as $line => $item) {

                $this->load->model('tb_mvp_list');

                $p_msg_2 = '{"header":"@@@@","name":"####","number":"$$$$","order":"&&&&"}';

                $p_msg_2 = str_replace('@@@@',"TPS",$p_msg_2);
                $p_msg_2 = str_replace('####',$item[1],$p_msg_2);
                $p_msg_2 = str_replace('$$$$',$item[4],$p_msg_2);
                $p_msg_2 = str_replace('&&&&',$item[5],$p_msg_2);

                $e_msg = sprintf($email_msg,$item[1],$item[4],$item[5]);

                $send_result[$item[0]] = array('uid'=>$item[0],'name'=>$item[1],'email'=>$item[2],'number'=>$item[3]);

                if(!empty($item['3'])){
                    $p   = $this->tb_mvp_list->mvp_send_phone_msg($item['3'],$p_msg_2);
                    if($p){
                        $send_result[$item[0]]['phone_success'] = 100;
                    }else{
                        $send_result[$item[0]]['phone_success'] = 0;
                    }
                }

                if(!empty($item[2])){
                    $e   = $this->tb_mvp_list->mvp_send_email($item[2],$title,$e_msg);
                    if($e){
                        $send_result[$item[0]]['email_number'] = 100;
                    }else{
                        $send_result[$item[0]]['email_number'] = 0;
                    }
                }
        }



        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("andy");
        $objExcel->setActiveSheetIndex(0);

        $u1 = 1;

        $objExcel->getActiveSheet()->setCellValue('a1', 'uid');
        $objExcel->getActiveSheet()->setCellValue('b1', 'name');
        $objExcel->getActiveSheet()->setCellValue('c1', 'email');
        $objExcel->getActiveSheet()->setCellValue('d1', 'mobile');
        $objExcel->getActiveSheet()->setCellValue('e1', 'phone_success');
        $objExcel->getActiveSheet()->setCellValue('f1', 'email_number');
        $u1++;

        foreach($send_result as $v){
            $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["uid"]);
            $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["name"]);
            $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["email"]);
            $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["number"]);
            $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["phone_success"]);
            $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["email_number"]);
            $u1++;
        }

        $filename = 'MVP-信息发送结果-'.date('mdhis');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }

    //二维数组去除特定键的重复项 //$arr->传入数组   $key->判断的key值
    public function array_unset($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                //有：销毁
                unset($value[$key]);
            }
            else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }
}