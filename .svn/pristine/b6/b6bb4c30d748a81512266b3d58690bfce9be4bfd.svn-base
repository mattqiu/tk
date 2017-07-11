<?php
/**
 * User: ckf
 * Date: 2016/5/25
 * Time: 16:44
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class goods_number_exception extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_mall_goods_number_exception');
        $this->load->model('tb_mall_goods');
    }

    public function index(){

        $this->_viewData['title'] = lang('goods_number_exception');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['goods_sn'] = isset($searchData['goods_sn'])?$searchData['goods_sn']:'';
        $list = $this->tb_mall_goods_number_exception->selectAll($searchData);
        $this->_viewData['list'] = $list;


        $this->load->library('pagination');
        $url = 'admin/goods_number_exception';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_mall_goods_number_exception->getExceptionRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['page_data'] = array(
            'goods_sn' => $searchData['goods_sn'],
        );

        parent::index('admin/');
    }


    public function number_exception(){
        $arr = $this->db->query("select distinct goods_sn from mall_goods WHERE goods_sn not like '%dbus%'")->result_array();
//        $arr = array(
//            0 => array(
//                'goods_sn' => '95625646-1',
//            ),
//            1 => array(
//                'goods_sn' => '46952052-1'
//            )
//          );

        $num = null;
        $goods1 = array();
        $goods1['goods_sn'] = null;
        $goods1['goods_name'] = null;
        $goods1['language_id'] = 2;
        $data = array();
        $d = 0;

        foreach($arr as $k=>$v){
            $sku = $v['goods_sn'];
            // 95625646-1 46952052-1
            $query = $this->db->query("SELECT `g`.`goods_sn`, `g`.`goods_sn_main`, `g`.`language_id`, `g`.`goods_number`, `m`.`goods_name`,`m`.`shipper_id`,`m`.`is_on_sale`,`g`.`product_id` FROM (`mall_goods` g) LEFT JOIN `mall_goods_main` m ON `g`.`goods_sn_main`=`m`.`goods_sn_main` WHERE `goods_sn` = '$sku' AND `g`.`language_id` = m.language_id");
            $list=$query->result_array();
            $num = null;
            if(!empty($list)){
                $num = $list[0]['goods_number'];
            }
            $i = 0;
            $idarr = array();
            foreach($list as $kk=>$vv){
                //语种库存不一致的商品
                if($num != $vv['goods_number']){
                    $m = 0;
                    foreach($list as $k1=>$v1){
                        if($v1['is_on_sale'] == 1){
                            $m++;
                        }
                        $idarr[] = $v1['product_id'];
                    }
                    if($m == 1){
                        //只有一种上架，则以上架语种的库存为准，修改其他语种库存
                        foreach($list as $k2=>$v2){
                            if($v2['is_on_sale'] == 1) {
                                $num_z = $v2['goods_number'];
//                                print_r($list);exit;
                                for($j=0;$j<count($idarr);$j++){
//                                    echo $v2['goods_sn'];
//                                    $this->db->where('product_id', $idarr[$j])->update('mall_goods', array('goods_number' => "$num_z"));
                                    $this->load->model("tb_mall_goods");
//                                    $this->tb_mall_goods->update_one(["product_id"=>$idarr[$j]],["goods_number"=>$num_z]);
                                    $this->tb_mall_goods->mall_goods_redis_log($idarr[$j],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                                    $this->tb_mall_goods->update_goods_number_in_redis($idarr[$j],$num_z);
//                                     echo 'product_id： '.$idarr[$j].'; sku：'.$v2['goods_sn'].' 库存已修复<br/>';
                                }
//                                break 3;
                            }

                        }

                    }
                    if($m == 0){
                        //都没有语种上架的，则选一种不为零的库存替换其他语种
                        foreach($list as $k3=>$v3){
                            if($v3['goods_number'] != 0){
                                $num_z = $v3['goods_number'];
                                for($j=0;$j<count($idarr);$j++){
//                                    echo $v3['goods_sn'];
//                                    $this->db->where('product_id', $idarr[$j])->update('mall_goods', array('goods_number' => "$num_z"));
                                    $this->load->model("tb_mall_goods");
//                                    $this->tb_mall_goods->update_one(["product_id"=>$idarr[$j]],["goods_number"=>$num_z]);
                                    $this->tb_mall_goods->mall_goods_redis_log($idarr[$j],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                                    $this->tb_mall_goods->update_goods_number_in_redis($idarr[$j],$num_z);
//                                     echo 'product_id： '.$idarr[$j].'; sku：'.$v2['goods_sn'].' 库存已修复<br/>';
                                }
                                break;
                            }

                        }
                    }

                    if($m > 1) {
                        $number_english = null;
                        $number_zh = null;
                        $number_hk = null;
                        $number_kr = null;

                        $nuarr = array(1,2,3,4);
                        $e = intval($vv['shipper_id']);
                        if(in_array($e,$nuarr)){
                            //不止一个语种上架或都不上架，则记录下来，保存excel
//                        print_r($list);exit;
                            foreach ($list as $a => $b) {
                                $goods1['goods_sn'] = $b['goods_sn'];
                                if ($b['language_id'] == 2) {
                                    $number_zh = $b['goods_number'];
                                    $goods1['goods_name'] = $b['goods_name'];
                                    $goods1['shipper_id'] = $b['shipper_id'];
//                    $this->db->insert('mall_goods_number_exception',$goods2);
                                }
                                if ($b['language_id'] == 3) {
                                    $number_hk = $b['goods_number'];
                                }
                                if ($b['language_id'] == 1) {
                                    $number_english = $b['goods_number'];
                                }
                                if ($b['language_id'] == 4) {
                                    $number_kr = $b['goods_number'];
                                }
                                $goods1['number_english'] = $number_english;
                                $goods1['number_zh'] = $number_zh;
                                $goods1['number_hk'] = $number_hk;
                                $goods1['number_kr'] = $number_kr;

                            }
                            if(!empty($goods1)){
                                $data[$d] = $goods1;
                                $d++;
                            }
                        }

                    }
                    break;
                }

            }

        }
        if(!empty($data)){
            require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
            require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
            require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
            require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';
            $objExcel = new PHPExcel();
            //设置属性
            $objExcel->getProperties()->setCreator("john");
            $objExcel->setActiveSheetIndex(0);
            //居中$objExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $fields = array('商品sku','商品名称','简体中文库存','繁体中文库存','英文库存','韩文库存','是否自营');
            $filename = '自营商品库存异常记录'.date('Y-m-d',time()).'_'.time();

            $i=0;
            //表头
            $objExcel->getActiveSheet()->setCellValue('a1',  $fields[0]);
            $objExcel->getActiveSheet()->setCellValue('b1',  $fields[1]);
            $objExcel->getActiveSheet()->setCellValue('c1',  $fields[2]);
            $objExcel->getActiveSheet()->setCellValue('d1',  $fields[3]);
            $objExcel->getActiveSheet()->setCellValue('e1',  $fields[4]);
            $objExcel->getActiveSheet()->setCellValue('f1',  $fields[5]);
            $objExcel->getActiveSheet()->setCellValue('g1',  $fields[6]);

            foreach($data as $k=>$v) {
                $u1=$i+2;
                /*----------写入内容-------------*/
                $objExcel->getActiveSheet()->setCellValue('a'.$u1, $v["goods_sn"]);
                $objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["goods_name"]);
                $objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["number_english"]);
                $objExcel->getActiveSheet()->setCellValue('d'.$u1, $v["number_zh"]);
                $objExcel->getActiveSheet()->setCellValue('e'.$u1, $v["number_hk"]);
                $objExcel->getActiveSheet()->setCellValue('f'.$u1, $v["number_kr"]);
                $objExcel->getActiveSheet()->setCellValue('g'.$u1, $v["shipper_id"]);

                $i++;
            }
            $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(75);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            //$this->SaveViaTempFile($objWriter);
            exit;

        }
    }

    public function number_exception_02(){
        $arr = $this->db->query("select distinct goods_sn from mall_goods WHERE goods_sn not like '%dbus%'")->result_array();
//        $arr = array(
//            0 => array(
//                'goods_sn' => '95625646-1',
//            ),
//            1 => array(
//                'goods_sn' => '46952052-1'
//            )
//          );

        $num = null;
        $goods1 = array();
        $goods1['goods_sn'] = null;
        $goods1['goods_name'] = null;
        $goods1['language_id'] = 2;
        $data = array();
        $d = 0;

        foreach($arr as $k=>$v){
            $sku = $v['goods_sn'];
            // 95625646-1 46952052-1
            $query = $this->db->query("SELECT `g`.`goods_sn`, `g`.`goods_sn_main`, `g`.`language_id`, `g`.`goods_number`, `m`.`goods_name`,`m`.`shipper_id`,`m`.`is_on_sale`,`g`.`product_id` FROM (`mall_goods` g) LEFT JOIN `mall_goods_main` m ON `g`.`goods_sn_main`=`m`.`goods_sn_main` WHERE `goods_sn` = '$sku' AND `g`.`language_id` = m.language_id");
            $list=$query->result_array();
            $num = null;
            if(!empty($list)){
                $num = $list[0]['goods_number'];
            }
            $i = 0;
            $idarr = array();
            foreach($list as $kk=>$vv){
                //语种库存不一致的商品
                if($num != $vv['goods_number']){
                    $m = 0;
                    foreach($list as $k1=>$v1){
                        if($v1['is_on_sale'] == 1){
                            $m++;
                        }
                        $idarr[] = $v1['product_id'];
                    }
                    if($m == 1){
                        //只有一种上架，则以上架语种的库存为准，修改其他语种库存
                        foreach($list as $k2=>$v2){
                            if($v2['is_on_sale'] == 1) {
                                $num_z = $v2['goods_number'];
//                                print_r($list);exit;
                                for($j=0;$j<count($idarr);$j++){
//                                    echo $v2['goods_sn'];
//                                    $this->db->where('product_id', $idarr[$j])->update('mall_goods', array('goods_number' => "$num_z"));
                                    $this->load->model("tb_mall_goods");
//                                    $this->tb_mall_goods->update_one(["product_id"=>$idarr[$j]],["goods_number"=>$num_z]);
                                    $this->tb_mall_goods->mall_goods_redis_log($idarr[$j],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                                    $this->tb_mall_goods->update_goods_number_in_redis($idarr[$j],$num_z);
//                                     echo 'product_id： '.$idarr[$j].'; sku：'.$v2['goods_sn'].' 库存已修复<br/>';
                                }
//                                break 3;
                            }

                        }

                    }
                    if($m == 0){
                        //都没有语种上架的，则选一种不为零的库存替换其他语种
                        foreach($list as $k3=>$v3){
                            if($v3['goods_number'] != 0){
                                $num_z = $v3['goods_number'];
                                for($j=0;$j<count($idarr);$j++){
//                                    echo $v3['goods_sn'];
//                                    $this->db->where('product_id', $idarr[$j])->update('mall_goods', array('goods_number' => "$num_z"));
                                    $this->load->model("tb_mall_goods");
//                                    $this->tb_mall_goods->update_one(["product_id"=>$idarr[$j]],["goods_number"=>$num_z]);
                                    $this->tb_mall_goods->mall_goods_redis_log($idarr[$j],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                                    $this->tb_mall_goods->update_goods_number_in_redis($idarr[$j],$num_z);
//                                     echo 'product_id： '.$idarr[$j].'; sku：'.$v2['goods_sn'].' 库存已修复<br/>';
                                }
                                break;
                            }

                        }
                    }

                    if($m > 1) {
                        $number_english = null;
                        $number_zh = null;
                        $number_hk = null;
                        $number_kr = null;
                        $nuarr = array(1,2,3,4);
                        $e = intval($vv['shipper_id']);
                        if(!in_array($e,$nuarr)){
                            //不止一个语种上架或都不上架，则记录下来，保存excel
//                        print_r($list);exit;
                            foreach ($list as $a => $b) {
                                $goods1['goods_sn'] = $b['goods_sn'];
                                if ($b['language_id'] == 2) {
                                    $number_zh = $b['goods_number'];
                                    $goods1['goods_name'] = $b['goods_name'];
                                    $goods1['shipper_id'] = $b['shipper_id'];
//                    $this->db->insert('mall_goods_number_exception',$goods2);
                                }
                                if ($b['language_id'] == 3) {
                                    $number_hk = $b['goods_number'];
                                }
                                if ($b['language_id'] == 1) {
                                    $number_english = $b['goods_number'];
                                }
                                if ($b['language_id'] == 4) {
                                    $number_kr = $b['goods_number'];
                                }
                                $goods1['number_english'] = $number_english;
                                $goods1['number_zh'] = $number_zh;
                                $goods1['number_hk'] = $number_hk;
                                $goods1['number_kr'] = $number_kr;

                            }
                            if(!empty($goods1)){
                                $data[$d] = $goods1;
                                $d++;
                            }
                        }

                    }
                    break;
                }

            }

        }
        if(!empty($data)){
            require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
            require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
            require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
            require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';
            $objExcel = new PHPExcel();
            //设置属性
            $objExcel->getProperties()->setCreator("john");
            $objExcel->setActiveSheetIndex(0);
            //居中$objExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $fields = array('商品sku','商品名称','简体中文库存','繁体中文库存','英文库存','韩文库存','是否自营');
            $filename = '非自营商品库存异常记录'.date('Y-m-d',time()).'_'.time();

            $i=0;
            //表头
            $objExcel->getActiveSheet()->setCellValue('a1',  $fields[0]);
            $objExcel->getActiveSheet()->setCellValue('b1',  $fields[1]);
            $objExcel->getActiveSheet()->setCellValue('c1',  $fields[2]);
            $objExcel->getActiveSheet()->setCellValue('d1',  $fields[3]);
            $objExcel->getActiveSheet()->setCellValue('e1',  $fields[4]);
            $objExcel->getActiveSheet()->setCellValue('f1',  $fields[5]);
            $objExcel->getActiveSheet()->setCellValue('g1',  $fields[6]);

            foreach($data as $k=>$v) {
                $u1=$i+2;
                /*----------写入内容-------------*/
                $objExcel->getActiveSheet()->setCellValue('a'.$u1, $v["goods_sn"]);
                $objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["goods_name"]);
                $objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["number_english"]);
                $objExcel->getActiveSheet()->setCellValue('d'.$u1, $v["number_zh"]);
                $objExcel->getActiveSheet()->setCellValue('e'.$u1, $v["number_hk"]);
                $objExcel->getActiveSheet()->setCellValue('f'.$u1, $v["number_kr"]);
                $objExcel->getActiveSheet()->setCellValue('g'.$u1, $v["shipper_id"]);

                $i++;
            }
            $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(75);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            //$this->SaveViaTempFile($objWriter);
            exit;

        }
    }
}