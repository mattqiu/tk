<?php
/**mvp颁奖报名名单
 * @author andy
 */
class tb_mvp_list extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    /** 写入一个记录
     * @param $uid
     * @author andy
     * @return mixed
     */
    public function add_mvp($uid,$order_id){
        $mvp = array('uid'=>$uid,'order_id'=>$order_id,'apply_number'=>'MVP'.date('YmdHis').rand(10, 99));
        $this->db->insert('mvp_list',$mvp);
        return  $this->db->insert_id();
    }

    /** 获取列表
     * @param
     * @author andy
     * @return mixed
     */
    public function get_mvp_list($filter,$perPage=10){
        return [];
        $this->db->select('u.id,u.name,u.email,u.mobile,u.parent_id,u.user_rank,u.sale_rank,t.payment_type,t.pay_time,m.id as number,m.apply_number');
        $this->db->from('users as u');
        $this->db->join('trade_orders as t','t.customer_id=u.id');
        $this->db->join('mvp_list as m','m.order_id=t.order_id');
        $this->db->where_in('t.status',array(1,3,4,5,6));
        $this->db->where('m.uid !=',138666);
        $this->filter_func($filter);
        return $this->db->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //获取第一路演直播订单列表
    public function get_mvp_live_list($filter,$perPage=10){
        return [];
        $this->db->select('t.order_id,t.payment_type,t.pay_time,t.order_amount_usd,t.phone,t.txn_id');
        $this->db->from('trade_orders as t');
        $this->db->join('mvp_list as m','m.order_id=t.order_id');
        $this->db->where_in('t.status',array(1,3,4,5,6));
        $this->db->where('m.uid',138666);
        $this->filter_func($filter);
        return $this->db->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
    }

    //直播计数
    public function get_mvp_live_list_count($filter){
        return 0;
        $this->db->from('trade_orders as t');
        $this->db->join('mvp_list as m','m.order_id=t.order_id');
        $this->db->where_in('t.status',array(1,3,4,5,6));
        $this->db->where('m.uid',138666);
        $this->filter_func($filter);
        return $this->db->count_all_results();
    }


    //计数
    public function get_mvp_list_count($filter){
        return 0;
        $this->db->from('users as u');
        $this->db->join('trade_orders as t','t.customer_id=u.id');
        $this->db->join('mvp_list as m','m.order_id=t.order_id');
        $this->db->where_in('t.status',array(1,3,4,5,6));
        $this->db->where('m.uid !=',138666);
        $this->filter_func($filter);
        return $this->db->count_all_results();
    }

    //for excel
    public function get_mvp_list_for_excel($type)
    {
        return false;
        set_time_limit(0);
        $this->db->select('u.id,u.name,u.email,u.mobile,u.parent_id,u.user_rank,u.sale_rank,t.payment_type,t.pay_time,m.id as number,m.apply_number');
        $this->db->from('users as u');
        $this->db->join('trade_orders as t', 't.customer_id=u.id');
        $this->db->join('mvp_list as m', 'm.order_id=t.order_id');
        $this->db->where_in('t.status', array(1, 3, 4, 5, 6));
        $this->db->where_in('u.sale_rank',array(4,5));
        $res = $this->db->get()->result_array();

        switch($type){
            case 1:{
                $this->get_mvp_list_for_excel_order($res,'');//不排序
                break;
            }
            case 2:{
                $this->get_mvp_list_for_excel_order($res,'GVP');//GVP
                break;
            }
            case 3:{
                $this->get_mvp_list_for_excel_order($res,'EMD');//EMD
                break;
            }
            case 4:{
                $this->get_mvp_list_for_excel_order_number($res);//座位安排
                break;
            }
            case 5:{
                //$this->get_have_dinner($res);//就餐安排
                $this->get_have_dinner_2($res);
                break;
            }
            default:{
                break;
            }
        }
 }


    public function get_have_dinner_2($res){

        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("andy");
        $objExcel->setActiveSheetIndex(0);

        $rows= 1;

        //3.11晚餐(17:30-19:30)
        $objExcel->getActiveSheet()->setCellValue('a'.$rows,'3.11晚餐(17:30-19:30)');

        $all_1 = 1741;
        $index = 1;
        $rows = $this->parse_excel_2($objExcel,$rows+1,"中餐厅",$rows+2,48,$all_1,$index);//中餐厅
        $rows = $this->parse_excel_2($objExcel,$rows+2,"5号厅",$rows+3,32,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"6号厅",$rows+3,10,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"8号厅",$rows+3,14,$all_1,$index);//14
        $rows = $this->parse_excel_2($objExcel,$rows+2,"茶室",$rows+3,6,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"4楼 VIP2",$rows+3,2,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V3",$rows+3,2,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V5",$rows+3,2,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V8",$rows+3,3,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V9",$rows+3,4,$all_1,$index);

        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V10-12",$rows+3,12,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V13",$rows+3,1,$all_1,$index);
        $rows = $this->parse_excel_2($objExcel,$rows+2,"3楼包房 V15",$rows+3,1,$all_1,$index);
        //4楼中餐厅外 过道
        $rows = $this->parse_excel_2($objExcel,$rows+2,"4楼 中餐厅外 过道",$rows+3,8,$all_1,$index);


        $objExcel->getActiveSheet()->getColumnDimension('a')->setWidth(50);
        for($h = 'b'; $h <= 'z'; $h++){
            $objExcel->getActiveSheet()->getColumnDimension($h)->setWidth(20);
            if($h=='ay'){
                break;
            }
        }


        $filename = 'MVP-新餐位-'.date('mdhis');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }

    public function parse_excel_2($objExcel,$start_at,$name,$rows,$all_z,&$count,&$index){
        if($index>1734){
            return $rows;
        }

        $objExcel->getActiveSheet()->setCellValue('b'.$start_at,$name);
        $objExcel->getActiveSheet()->setCellValue('c'.$start_at,$all_z.'桌');
        $z_r = $rows;//第几行
        $d_z = 1;//第几桌
        $need_rows = ceil($all_z/6);//共需要几行
        for($i=0;$i<$need_rows;$i++){
            for($h = 'b'; $h <= 'z'; $h++){

                $objExcel->getActiveSheet()->setCellValue($h.$z_r,"第".($d_z)."桌 ".$index .'-'.($index+11));
                if($d_z==$all_z){
                    $index = $index+12;
                    return $z_r;
                }
                $index = $index+12;
                if($index>1734){
                    return $z_r;
                }
                $d_z++;
                if($h=='g'){//ay
                    break;
                }
            }
            $z_r++;
        }


        return $z_r;
    }

    public function get_have_dinner($res){
        $gvp = array();
        $emd = array();
        $all = $res;

        foreach($res as $val){
            if($val['sale_rank']==5){
                //array_push($gvp,$val);
                for($i=0;$i < 352;$i++){
                array_push($gvp,array('number'=>$i));
                }
            }else{
                for($i=0;$i < 1148;$i++) {
                array_push($emd, array('number'=>$i));
                }
                //array_push($emd,$val);
            }
        }

        //test
//        for($i=1;$i<1501;$i++){
//            array_push($all, array('number'=>$i));
//        }

        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("andy");
        $objExcel->setActiveSheetIndex(0);


        $r = 1;//设置位置,从第一行开始
        //3.11午餐第一批自助餐(11:30-13:00)
        $objExcel->getActiveSheet()->setCellValue('a'.$r,'3.11午餐第一批自助餐(11:30-13:00)');

        $emd_1 = $emd;
        $gvp_1 = $gvp;
        //第一批
        $rows = $this->parse_excel($objExcel,2,"中餐厅",3,48,$emd_1,1);
        $rows = $this->parse_excel($objExcel,$rows+2,"5号厅",$rows+3,32,$emd_1,1);
        $rows = $this->parse_excel($objExcel,$rows+2,"6号厅",$rows+3,10,$emd_1,1);
        $rows = $this->parse_excel($objExcel,$rows+2,"8号厅",$rows+3,12,$emd_1,1);
        $rows = $this->parse_excel($objExcel,$rows+2,"茶室",$rows+3,6,$emd_1,1);

        //第二批
        $objExcel->getActiveSheet()->setCellValue('a'.($rows+2),'3.11午餐第二批自助餐(13:10-14:00)');

        $rows = $this->parse_excel($objExcel,$rows+3,"中餐厅",$rows+4,48,$gvp_1,1);


        $all_1 = $all;

        //3.11晚餐(17:30-19:30)
        $objExcel->getActiveSheet()->setCellValue('a'.($rows+2),'3.11晚餐(17:30-19:30)');

        $rows = $this->parse_excel($objExcel,$rows+3,"中餐厅",$rows+4,48,$all_1);//中餐厅
        $rows = $this->parse_excel($objExcel,$rows+2,"5号厅",$rows+3,32,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"6号厅",$rows+3,10,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"8号厅",$rows+3,14,$all_1);//14
        $rows = $this->parse_excel($objExcel,$rows+2,"茶室",$rows+3,6,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"4楼 VIP2",$rows+3,2,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V3",$rows+3,2,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V5",$rows+3,2,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V8",$rows+3,3,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V9",$rows+3,4,$all_1);

        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V10-12",$rows+3,12,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V13",$rows+3,1,$all_1);
        $rows = $this->parse_excel($objExcel,$rows+2,"3楼包房 V15",$rows+3,1,$all_1);
        //4楼中餐厅外 过道
        $rows = $this->parse_excel($objExcel,$rows+2,"4楼 中餐厅外 过道",$rows+3,8,$all_1);


        ///$emd_2 =$emd;
        //3.12午餐第一批自助餐(11:30-13:00)
        ///$objExcel->getActiveSheet()->setCellValue('a'.($rows+2),'3.12午餐第一批自助餐(11:30-13:00)');

        ///$rows = $this->parse_excel($objExcel,$rows+3,"中餐厅",$rows+4,48,$emd_2);//中餐厅
        ///$rows = $this->parse_excel($objExcel,$rows+2,"5号厅",$rows+3,32,$emd_2);
        ///$rows = $this->parse_excel($objExcel,$rows+2,"6号厅",$rows+3,10,$emd_2);
        ///$rows = $this->parse_excel($objExcel,$rows+2,"8号厅",$rows+3,12,$emd_2);//12  具体可以方几桌？
        ///$rows = $this->parse_excel($objExcel,$rows+2,"茶室",$rows+3,6,$emd_2);


        ///$gvp_2 = $gvp;
        //3.12午餐第二批自助餐(13:10-14:00)
        ///$objExcel->getActiveSheet()->setCellValue('a'.($rows+2),'3.12午餐第二批自助餐(13:10-14:00)');

        ///$rows = $this->parse_excel($objExcel,$rows+3,"中餐厅",$rows+4,48,$gvp_2);//中餐厅


        $objExcel->getActiveSheet()->getColumnDimension('a')->setWidth(50);
        for($h = 'b'; $h <= 'z'; $h++){
            $objExcel->getActiveSheet()->getColumnDimension($h)->setWidth(20);
            if($h=='ay'){
                break;
            }
        }


        $filename = 'MVP-餐位-'.date('mdhis');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }

    /**
     * @param $objExcel
     * @param $start_at //小标题
     * @param $rows //哪一行开始
     * @param $name //分类名
     * @param $all_z //总桌数
     */
    private function parse_excel($objExcel,$start_at,$name,$rows,$all_z,&$data=array(),$is_din=''){
        $objExcel->getActiveSheet()->setCellValue('b'.$start_at,$name);
        $objExcel->getActiveSheet()->setCellValue('c'.$start_at,$all_z.'桌');
        $z_r = $rows;//第几行
        $per = 1;//第几桌
        $num = 1;//第几号
        for($i=0;$i<$all_z;$i++){
            for($h = 'b'; $h <= 'z'; $h++){
                $result=array_shift($data);
                $mvp_str = $result?' MVP'.$result['number']:'';

                if($is_din==1){
                    $order = '';
                }else{
                    $order = '第'.$per.'桌';
                }

                $objExcel->getActiveSheet()->setCellValue($h.$z_r,$order.$mvp_str);
                $num++;
                if($num==13){
                    $per++;
                    $num=1;
                }
                if($h=='m'){//ay
                    break;
                }
            }
            $z_r++;
        }
        return $z_r;
    }


    private function get_mvp_list_for_excel_order_number($res){

        $gvp = array();
        $emd = array();

        $gvp_send_msg = array();
        $emd_send_msg = array();

        //等级不变情况下，排序一样
//        $gvp_num = 1;
//        $emd_num = 1;
        foreach($res as $val){
            if($val['sale_rank']==5){
                array_push($gvp,$val);
                //for($i=0;$i < 352;$i++){
                    //array_push($gvp,array('id'=>1,'name'=>'andy','email'=>'511651@qq.com','mobile'=>11111,'number'=>2));
                    //$gvp_num++;
                //}
            }else{
                array_push($emd,$val);
            }
        }

//        foreach($res as $val){
//            //array_push($emd,$val);
//            if($val['sale_rank']!=5){
//                for($i=0;$i < 1148;$i++) {
//                    array_push($emd,array('id'=>1,'name'=>'andy--2','email'=>'511651@qq.com','mobile'=>1851225,'number'=>2));
//                    //$emd_num++;
//                }
//            }
//        }


        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("andy");
        $objExcel->setActiveSheetIndex(0);

        //1--3
        //$count_gvp  = count($gvp);
        $order_num = 1;

        //$num        = 0;//依次读取数组中的数据
        $number1    = 1;//重置座位号
        for($r=1;$r<=3;$r++){
            for($h = 'a'; $h <= 'z'; $h++){
                $objExcel->getActiveSheet()->setCellValue($h.$r,' A区  '.' 第'.$r .'排'.$number1++.'座'.'序号 '.$order_num);
                $order_num++;
                //$num++;
                if($h=='as'){
                    break;
                }
            }
            $number1 = 1;
        }


        //gvp
        //$count_gvp  = count($gvp);
        $num        = 0;//依次读取数组中的数据
        $number1    = 1;//重置座位号
        for($r=4;$r<=12;$r++){
            for($h = 'a'; $h <= 'z'; $h++){
//                if($num==$count_gvp){//没有数据即跳出
//                    break;
//                }
                //$mvp_number = isset($gvp[$num]['number'])? ' MVP'.$gvp[$num]['number'] :'';
                $order_num_str = '序号 '.$order_num;
                $name_str      = isset($gvp[$num]['name'])? ' '.$gvp[$num]['name'] :'';
                $objExcel->getActiveSheet()->setCellValue($h.$r,' A区  '.' 第'.$r .'排'.$number1++.'座'.$order_num_str.$name_str);

                //导出座位号与报名对应的excel
                if(isset($gvp[$num]['name'])){
                    array_push($gvp_send_msg,array('id'=>$gvp[$num]['id'],
                                                    'name'=>$gvp[$num]['name'],
                                                    'email'=>$gvp[$num]['email'],
                                                    'mobile'=>$gvp[$num]['mobile'],
                                                    'order_id'=>$order_num,
                                                    'order_number'=> ' A区'.' 第'.$r .'排'.($number1-1).'座',
                                                    ));
                }


                $order_num++;//序号加一
                $num++;

                if($h=='as'){
                    break;
                }
            }
            $number1 = 1;
        }

        //emd 后
        $count_emd = count($emd);
        $num_emd = 0;
        $number2 = 1;
        for($r2=19;$r2<=44;$r2++){
            for($h = 'a'; $h <= 'z'; $h++){
//                if($num_emd==$count_emd){
//                    break;
//                }
                //$mvp_number2 = isset($emd[$num_emd]['number']) ? ' MVP'.$emd[$num_emd]['number'] :'';
                $order_num_str = '序号 '.$order_num;
                $name_str      = isset($emd[$num_emd]['name']) ? ' '.$emd[$num_emd]['name'] :'';
                $objExcel->getActiveSheet()->setCellValue($h.$r2,' B区  '.' 第'.($r2-18).'排'.$number2++.'座'.$order_num_str.$name_str);
                if(isset($emd[$num_emd]['name'])){
                    array_push($emd_send_msg,array(
                                            'id'=>$emd[$num_emd]['id'],
                                            'name'=>$emd[$num_emd]['name'],
                                            'email'=>$emd[$num_emd]['email'],
                                            'mobile'=>$emd[$num_emd]['mobile'],
                                            'order_id'=>$order_num,
                                            'order_number'=>' B区'.' 第'.($r2-18).'排'.($number2-1).'座',
                                            ));
                }
                $order_num++;//总监人数加一
                unset($emd[$num_emd]);
                $num_emd++;
                if($h=='am'){
                    break;
                }
            }
            $number2 = 1;
        }

        //a区后emd
        $emd = array_values($emd);
        $count_emd3 = count($emd);
        $num_emd3 = 0;
        $number3 = 1;
        for($r3=13;$r3<=16;$r3++){
            for($h = 'a'; $h <= 'z'; $h++){
//                if($num_emd3==$count_emd3){
//                    break;
//                }
                //$mvp_number3 = isset($emd[$num_emd3]['number']) ?  '  MVP'.$emd[$num_emd3]['number'] :'';
                $order_num_str = '序号 '.$order_num;
                $name_str      = isset($emd[$num_emd3]['name']) ?  ' '.$emd[$num_emd3]['name'] :'';
                $objExcel->getActiveSheet()->setCellValue($h.$r3,' A区  '.' 第'.$r3 .'排'.$number3++.'座'. $order_num_str.$name_str);

                if(isset($emd[$num_emd3]['name'])){//记录号码
                    array_push($emd_send_msg,array(
                                                'id'=>$emd[$num_emd3]['id'],
                                                'name'=>$emd[$num_emd3]['name'],
                                                'email'=>$emd[$num_emd3]['email'],
                                                'mobile'=>$emd[$num_emd3]['mobile'],
                                                'order_id'=>$order_num,
                                                'order_number'=>' A区  '.' 第'.$r3 .'排'.($number3-1).'座',
                                                ));
                }

                $order_num++;//最后
                $num_emd3++;
                if($h=='as'){
                    break;
                }
            }
            $number3 = 1;
        }

        for($h = 'a'; $h <= 'z'; $h++){
            $objExcel->getActiveSheet()->getColumnDimension($h)->setWidth(30);
                if($h=='as'){
                break;
            }
        }

        //第二张表
        //$objExcel->setActiveSheetIndex(1);
        $objExcel->getActiveSheet()->setCellValue('a60', 'uid');
        $objExcel->getActiveSheet()->setCellValue('b60', 'name');
        $objExcel->getActiveSheet()->setCellValue('c60', 'email');
        $objExcel->getActiveSheet()->setCellValue('d60', 'mobile');
        $objExcel->getActiveSheet()->setCellValue('e60', 'order_id');
        $objExcel->getActiveSheet()->setCellValue('f60', 'order_number');

        $u1 = 61;
        //$objExcel->getActiveSheet()->setCellValue('a' . $u1, 'GVP');
        //$u1++;
        if($gvp_send_msg){
            foreach($gvp_send_msg as $v){
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["id"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["name"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["email"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["mobile"]);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["order_number"]);
                $u1++;
            }
        }
        //$objExcel->getActiveSheet()->setCellValue('a' . ($u1+3), 'EMD');
        $u1++;
        if($emd_send_msg){
            foreach($emd_send_msg as $v){
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["id"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["name"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["email"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["mobile"]);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["order_number"]);
                $u1++;
            }
        }


        $filename = 'MVP-座位-'.date('mdhis');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function get_mvp_list_for_excel_order($res,$sign){

        $mvp = array();
        if($sign=='GVP'){
            foreach($res as $val){
                if($val['sale_rank']==5){
                    array_push($mvp,$val);
                }
            }
        }elseif($sign=='EMD'){
            foreach($res as $val){
                if($val['sale_rank']==4){
                    array_push($mvp,$val);
                }
            }
        }else{
            $mvp = $res;
        }

        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("andy");
        $objExcel->setActiveSheetIndex(0);
        $fields = array('ID', '真实姓名', '电子邮箱', '手机号', '店铺类型', '职称', '报名时间', '支付方式', '报名号');

        $payment_type_map = array(
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

        $i = 0;
        //表头
        $objExcel->getActiveSheet()->setCellValue('a1', $fields[0]);
        $objExcel->getActiveSheet()->setCellValue('b1', $fields[1]);
        $objExcel->getActiveSheet()->setCellValue('c1', $fields[2]);
        $objExcel->getActiveSheet()->setCellValue('d1', $fields[3]);
        $objExcel->getActiveSheet()->setCellValue('e1', $fields[4]);
        $objExcel->getActiveSheet()->setCellValue('f1', $fields[5]);
        $objExcel->getActiveSheet()->setCellValue('g1', $fields[6]);
        $objExcel->getActiveSheet()->setCellValue('h1', $fields[7]);
        $objExcel->getActiveSheet()->setCellValue('i1', $fields[8]);

        if ($mvp) {
            foreach ($mvp as $k => $v) {
                $u1 = $i +2;
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["id"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["name"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["email"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["mobile"]);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v['parent_id'] ? lang(config_item('user_ranks')[$v['user_rank']]) : '');
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, config_item('sale_rank')[$v["sale_rank"]]);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $payment_type_map[$v["payment_type"]]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, 'MVP'.$v["number"]);
                $i++;
            }
        }

        $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

        $filename = 'MVP-'.date('mdhis');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }



    //获取报名成功数
    public function get_mvp_success_count(){
        $this->db->from('users as u');
        $this->db->join('trade_orders as t','t.customer_id=u.id');
        $this->db->join('mvp_list as m','m.order_id=t.order_id');
        $this->db->where_in('t.status',array(1,3,4,5,6));
        $this->db->where('m.uid !=',138666);
        return $this->db->count_all_results();
    }

    //是否付款成功
    public function is_pay($uid){
        $this->db->from('mvp_list as m');
        $this->db->where('m.uid',$uid);
        $this->db->where('m.uid !=',138666);
        $this->db->where_in('t.status',array(1,3,4,5,6));
        $this->db->join('trade_orders as t','t.order_id=m.order_id');
        $row =  $this->db->count_all_results();
        if($row){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    private function filter_func($filter){
        foreach ($filter as $k => $v) {
            if ($v === '' || $k=='page') {
                continue;
            }
            $val = trim($v);
            switch ($k) {
                case 'id_or_email_or_name':{
                    if(is_numeric($val)){
                        $this->db->where('u.id',(int)$val);
                    }elseif(strpos($v,'@')){
                        $this->db->like('u.email',$val);
                    }else{
                        $this->db->like('u.name',$val);
                    }
                    break;
                }
                case 'phone_number': {
                    $this->db->where('u.mobile', $val);
                    break;
                }
                case 'sale_rank':{
                    $this->db->where('u.sale_rank',(int)$val);
                    break;
                }
                case 'luyan_account':{
                    $this->db->where('t.phone', $val);
                    break;
                }
                case 'payment_type':{
                    $this->db->where('t.payment_type',$val);
                    break;
                }
                case 'start':{
                    $this->db->where('t.pay_time >=',$val);
                    break;
                }
                case 'end':{
                    $this->db->where('t.pay_time <=',date('Y-m-d H:i:s',strtotime($val)+86400-1));
                    break;
                }
                default:{
                    break;
                }
            }
        }
    }


    //临时峰会发送短信-----[峰会通知]
    public function mvp_send_phone_msg($phone='',$msg=''){
            include_once APPPATH .'/third_party/taobao/TopSdk.php';
            $phone_cfg_info  =array(
                'signature' => '峰会通知',
                'template' => 'SMS_52480073',
                //'param' => '{"code":"@@@@","product":"TPS"}'
            );
            $c = new TopClient;
            $c->appkey = "23362350";
            $c->secretKey = "7615d82fa94a199d8ad2c303f2e6c9e6";
            $c->format = "json";
            $req = new AlibabaAliqinFcSmsNumSendRequest;
            $req->setSmsType("normal");
            $req->setSmsParam($msg);
            $req->setSmsFreeSignName($phone_cfg_info['signature']);
            $req->setRecNum("$phone");
            $req->setSmsTemplateCode($phone_cfg_info['template']);
            $resp = $c->execute($req);
            if(isset($resp->result->success)){
                return TRUE;
            }else{
                return FALSE;
            }
    }

    //临时峰会发送邮件
    public function mvp_send_email($email,$title,$msg){
        return send_mail($email,$title,$msg);
    }
}