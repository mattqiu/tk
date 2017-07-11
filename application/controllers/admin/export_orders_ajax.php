<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Export_orders_ajax extends MY_Controller
{

    private $_redis_key_pre_proc = "export:orders:proc:";
    private $_redis_key_pre_filter = "export:orders:filter:";
    private $_redis_key_pre_cancel = "export:orders:cancel:";
    private $_tmp_dir = "";
    private $_redis_key_time_out = 86400;

    public function __construct()
    {
        parent::__construct();
        if(isset($this->_viewData['adminInfo']['id']))
        {
            $this->_tmp_dir = DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'export_order'.DIRECTORY_SEPARATOR.date("Y-m-d").DIRECTORY_SEPARATOR.$this->_viewData['adminInfo']['id'].DIRECTORY_SEPARATOR;
        }else{
            $this->_tmp_dir = DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'export_order'.DIRECTORY_SEPARATOR.date("Y-m-d").DIRECTORY_SEPARATOR;
        }
    }

    /**
     * 下载任务文件
     */
    public function process_download()
    {
        $this->load->helper('file_helper');
        $filename = $this->input->get_post("filename");
        $this->load->model("m_oss_api");

        if($filename) {
            $filter = $this->_get_filter_by_key($filename);
            if($filter)
            {
                $h_filename = $this->_get_human_file_name_by_filter($filter);
                //能找到指定任务
                //判断任务的进度
                $this->load->model("tb_empty");
                $status = $this->tb_empty->redis_get($this->_redis_key_pre_proc.$filename);
                if($status)
                {
                    $status = unserialize($status);
                    if($status)
                    {
                        //判断指定任务的进度
                        if($status['now'] >= $status['total'])
                        {

                        }else{
                            $res['code'] = 1;
                            $res['error_msg'] = "指定任务尚未完成，不能下载";
                            exit(json_encode($res));
                        }
                    }else{
                        //找到指定任务的进度错误
                        $res['code'] = 1;
                        $res['error_msg'] = "找到指定任务的进度错误";
                        $res['filename'] = $filename;
                        exit(json_encode($res));
                    }
                }else{
                    //找不到指定任务的进度
                    $res['code'] = 1;
                    $res['error_msg'] = "找不到指定任务的进度";
                    exit(json_encode($res));
                }
//                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//                header('Cache-Control: max-age=0');

                if(file_exists($this->_tmp_dir.$filename.".csv")) {
                    $file = $this->_tmp_dir.$filename.".csv";
                    if(file_exists($file))
                    {
//                        read_file($file);
                        $this->_download($file);
                    }
                }

                if(file_exists($this->_tmp_dir.$filename.".".$this->_get_file_ext_by_filter($filter)))
                {
                    $file = $this->_tmp_dir.$filename.".".$this->_get_file_ext_by_filter($filter);
                    if(file_exists($file))
                    {
//                        read_file($file);
                        $this->_download($file);
                    }
                }

                //如果本地没有数据，从OSS拿数据
                $files = [$this->_tmp_dir.$h_filename.".csv",$this->_tmp_dir.$h_filename.".".$this->_get_file_ext_by_filter($filter)];
                foreach($files as $file)
                {
                    $oss_file_exits = $this->m_oss_api->doesObjectExist(ltrim($file,DIRECTORY_SEPARATOR));
                    if($oss_file_exits)
                    {
                        $url = "https://".$this->m_oss_api->bucket.".".$this->m_oss_api->endpoint.$file;
                        header('Location: ' . $url);
                        exit($url);
                        //如果私有空间需要取回再进行下载，但现在有机器不支持tmp目录写入
                        $tmp = $this->m_oss_api->getObjToLocalFile(ltrim($file,DIRECTORY_SEPARATOR),$file);
                        if($tmp)
                        {
                            $this->_download($file);
                        }
                        break;
                    }
                }

                //找不到指定任务的文件了
                $res['code'] = 1;
                $res['error_msg'] = "找不到指定任务的文件了";
                $res['file'] = $file;
                exit(json_encode($res));
            }else{
                //找不到指定任务了
                $res['code'] = 1;
                $res['error_msg'] = "找不到指定任务了";
                exit(json_encode($res));
            }
        }
    }

    /**
     * 取任务详情
     */
    public function process_info()
    {
        $filename = $this->input->get_post("filename");
        if(!$filename)
        {
            $res['code'] = 1;
            $res['err_msg'] = "filename必传.";
            exit(json_encode($res));
        }
        $filter = $this->_get_filter_by_key($filename);
        $h_filename = $this->_get_human_file_name_by_filter($filter);
        if(!$h_filename)
        {
            $res['code'] = 1;
            $res['err_msg'] = "取不到文件名.";
            exit(json_encode($res));
        }
        $res['code'] = 0;
        $res['filename'] = $filename;
        $res['h_filename'] = $h_filename;
        exit(json_encode($res));
    }

    /**
     * ajax提交导出任务
     */
    public function process_plus()
    {
        $filter = $this->_get_filter();
        $filename = $this->_get_file_name_by_filter($filter);
        $h_filename = $this->_get_human_file_name_by_filter($filter);
        $tmp = $this->_get_filter_by_key($filename);
        $res['code'] = 1;
        $res['filename'] = $filename;
        $res['h_filename'] = $h_filename;
        if($tmp)
        {
            $res['err_msg'] = "已经存在该任务";
            exit(json_encode($res));
        }
        $res['code'] = 0;
        $this->_set_filter_by_key($filter,$filename);
        exit(json_encode($res));
    }

    /**
     * 查看导出任务的状态
     */
    public function process_status()
    {
        $filename = $this->input->get_post("filename");
        $this->load->model("tb_empty");
        $tmp = $this->tb_empty->redis_get($this->_redis_key_pre_proc.$filename);
        if($tmp)
        {
            $tmp = unserialize($tmp);
            $res['code'] = 0;
            $res['data'] = $tmp;
        }else{
            $res['code'] = 1;
            $res['data'] = "读取缓存数据失败";
        }
        exit(json_encode($res));
    }

    /**
     * 开始导出任务
     */
    public function process_start()
    {
//        ini_set('display_errors', '0');
//        ini_set('max_execution_time', '0');
        set_time_limit(0);
        error_reporting(0); //开启错误
        ignore_user_abort();//忽略用户取消访问

        $filename = $this->input->get_post("filename");
        if($filename)
        {
            $filter = $this->_get_filter_by_key($filename);
            if($filter)
            {
                $this->export_excel($filter);
                $res['code'] = 0;
                $res['err_msg'] = "";
            }else{
                $res['code'] = 1;
                $res['err_msg'] = "找不到指定的导出任务的条件";
            }
        }else{
            $res['code'] = 1;
            $res['err_msg'] = "找不到指定的导出任务";
        }
        $this->_set_process_status($filename,['finish'=>1]);
        exit(json_encode($res));
    }

    /**
     * 删除导出任务
     */
    public function process_minus()
    {
        $filename = $this->input->get_post("filename");
        if($filename)
        {
            $filter = $this->_get_filter_by_key($filename);
            $ext = $this->_get_file_ext_by_filter($filter);
            if($filter)
            {
                if(file_exists($filename.".csv")){
                    unlink($filename.".csv");
                }
                if(file_exists($filename.".".$ext)){
                    unlink($filename.".".$ext);
                }
                $this->load->model("tb_empty");
                $this->tb_empty->redis_set($this->_redis_key_pre_cancel.$filename,"1",10);
                $tmp = $this->tb_empty->redis_get($this->_redis_key_pre_proc.$filename);
                if($tmp)
                {
                    if(!isset($tmp['finish']))
                    {
                        $this->tb_empty->redis_del($this->_redis_key_pre_proc.$filename);
                        $this->tb_empty->redis_del($this->_redis_key_pre_filter.$filename);
                    }
                }
                $res['code'] = 0;
                $res['err_msg'] = "";
            }else{
                $res['code'] = 1;
                $res['err_msg'] = "已找不到指定的任务";
            }
        }else{
            $res['code'] = 1;
            $res['err_msg'] = "请指定filename名称";
        }
        exit(json_encode($res));
    }

    /**
     * 取条件
     * @return mixed
     */
    private function _get_filter()
    {
        $searchData = $this->input->post() ? $this->input->post() : array();
        $searchData['area'] = isset($searchData['area']) ? $searchData['area'] : '';
        $area_country_map = array(
            "156" => array("156"),
            "840" => array("840"),
            "410" => array("410"),
            "344" => array("344"),
            "446" => array("446"),
            "158" => array("158"),
            "001" => array("704", "418", "116", "764", "104", "458", "702", "360", "096", "608", "626"),
            "000" => array("000"),
        );
        $filter['area'] = isset($area_country_map[$searchData['area']]) ? $area_country_map[$searchData['area']] : '';
        $filter['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $filter['cate_id'] = isset($searchData['cate_id']) ? $searchData['cate_id'] : '';
        $filter['order_type'] = isset($searchData['order_type']) ? $searchData['order_type'] : '';
        $filter['store_code'] = isset($searchData['store_code']) ? $searchData['store_code'] : '';
        $filter['store_code_arr'] = isset($searchData['store_code_arr']) ? $searchData['store_code_arr'] : '';
        $filter['start_date'] = isset($searchData['start_date']) ? $searchData['start_date'] : '';
        $filter['end_date'] = isset($searchData['end_date']) ? $searchData['end_date'] : '';
        $filter['start_update_date'] = isset($searchData['start_update_date']) ? $searchData['start_update_date'] : '';
        $filter['end_update_date'] = isset($searchData['end_update_date']) ? $searchData['end_update_date'] : '';
        $filter['start_deliver_date'] = isset($searchData['start_deliver_date']) ? $searchData['start_deliver_date'] : '';
        $filter['end_deliver_date'] = isset($searchData['end_deliver_date']) ? $searchData['end_deliver_date'] : '';
        $filter['brand'] = isset($searchData['brand']) ? $searchData['brand'] : '';
        $filter['ext'] = isset($searchData['ext']) ? $searchData['ext'] : '';
        $filter['is_export_lock'] = isset($searchData['is_export_lock']) ? $searchData['is_export_lock'] : '';
        $filter['select_is_export_lock'] = isset($searchData['select_is_export_lock']) ? $searchData['select_is_export_lock'] : '';
        $filter['language_id'] = get_cookie('admin_curLan_id') ? get_cookie('admin_curLan_id') : 1;
        //查询所属运营方
        $this->load->model("tb_mall_supplier");
        if(in_array($filter['store_code'],['1','2','3','4','100','200','201']))
        {
            $filter['operator_id'] = $filter['store_code'];
        }
        else
        {
            $suppliers = $this->tb_mall_supplier->get_one("*",["supplier_id"=>$filter['store_code']]);
            if($suppliers)
            {
                $filter['operator_id'] = $suppliers['operator_id'];
            }
        }

        return $filter;
    }

    /**
     * 根据filter取数据文件名
     * @param $filter
     * @return mixed
     */
    private function _get_file_name_by_filter($filter)
    {
        $tmp = "";
        foreach ($filter as $k => $v) {
            $tmp .= $k . $v;
        }
        $tmp = md5($tmp);
        return $tmp;
    }

    /**
     * 取易读文件名
     * @param $filter
     * @return string 名称
     */
    private function _get_human_file_name_by_filter($filter)
    {
        $tmp = "";
        $sup_id = $filter['store_code'];
        $this->load->model("tb_mall_supplier");
        $res = $this->tb_mall_supplier->get_list("supplier_name",['supplier_id'=>$sup_id]);
        foreach ($res as $k => $v)
        {
            if($tmp)
            {
                $tmp .= "_";
            }
            $tmp .= $v['supplier_name'];
        }
        if($filter['start_date'])
        {
            if($tmp)
            {
                $tmp .= "_";
            }
            $tmp .= $filter['start_date'];
        }
        if($filter['end_date'])
        {
            if($tmp)
            {
                $tmp .= "_";
            }
            $tmp .= $filter['end_date'];
        }
        if($filter['start_deliver_date'])
        {
            if($tmp)
            {
                $tmp .= "_";
            }
            $tmp .= $filter['start_deliver_date'];
        }
        if($filter['end_deliver_date'])
        {
            if($tmp)
            {
                $tmp .= "_";
            }
            $tmp .= $filter['end_deliver_date'];
        }
        return $tmp;
    }

    /**
     * 根据数据文件名存一下filter
     * @param $filter
     * @param $filename
     * @return mixed
     */
    private function _set_filter_by_key($filter, $filename)
    {
        $this->load->model("tb_empty");
        if (!$filter) return false;
        if (!$filename) return false;
        $tmp = serialize($filter);
        $this->tb_empty->redis_set($this->_redis_key_pre_filter . $filename, $tmp,$this->_redis_key_time_out);
    }

    /**
     * 根据key取filter
     * @param $filename
     * @return mixed $filter
     */
    private function _get_filter_by_key($filename)
    {
        $this->load->model("tb_empty");
        $res = $this->tb_empty->redis_get($this->_redis_key_pre_filter . $filename);
        if ($res) {
            return unserialize($res);
        }
        return false;
    }

    private function _get_file_ext_by_filter($filter)
    {
        if($filter['ext'] === '2007') { //导出excel2007文档
            return "xlsx";
        }else{//导出excel2003文档
            return "xls";
        }
    }

    /**
     * 设置进度
     * @param $filename
     */
    private function _set_process_status($filename,$data)
    {
        $this->load->model("tb_empty");
        $tmp = $this->tb_empty->redis_get($this->_redis_key_pre_proc.$filename);
        if($tmp)
        {
            $tmp = unserialize($tmp);
            if($tmp)
            {
                foreach($data as $k=>$v)
                {
                    $tmp[$k] = $v;
                }
                $this->tb_empty->redis_set($this->_redis_key_pre_proc.$filename,serialize($tmp),$this->_redis_key_time_out);
                return;
            }
        }
        $this->tb_empty->redis_set($this->_redis_key_pre_proc.$filename,serialize($data),$this->_redis_key_time_out);
    }

    /**
     * 检测并创建长目录
     * @param $dir
     * @return bool
     */
    private function _mkDirs($dir){
        if(!is_dir($dir)){
            if(!$this->_mkDirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }

    /**
     * 写入excel文件
     * @param $objExcel
     * @param $filename
     * @param $filter
     */
    private function _write_excel_file($objExcel,$filename,$filter)
    {
        $this->_mkDirs($this->_tmp_dir);
        if($filter['ext'] === '2007') { //导出excel2007文档
            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        } else {  //导出excel2003文档
            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        }
        $ext = $this->_get_file_ext_by_filter($filter);
        $this->_set_process_status($filename,[__LINE__=>'1']);
        $h_filename = $this->_get_human_file_name_by_filter($filter);
        $this->_set_process_status($filename,['save_file'=>__LINE__]);
        $objWriter->save($this->_tmp_dir.$filename.".".$ext);
        $this->_set_process_status($filename,['save_file'=>__LINE__]);
        //上传到oss
        $this->load->model("m_oss_api");
        $oss_obj = ltrim($this->_tmp_dir.$h_filename.".".$ext,DIRECTORY_SEPARATOR);
        $this->_set_process_status($filename,['upload_oss'=>__LINE__]);
        $this->m_oss_api->doUploadFile($oss_obj,$this->_tmp_dir.$filename.".".$ext);
        $this->_set_process_status($filename,['upload_oss'=>__LINE__]);
        $this->_set_process_status($filename,['finish'=>'1',__LINE__=>'1']);
        exit;

    }

    /**
     * 下载文件方法
     * @param $filename
     */
    private function _download($filename)
    {
        $file_info = pathinfo($filename);
        $filter = $this->_get_filter_by_key($file_info['filename']);
        $h_filename = $this->_get_human_file_name_by_filter($filter);
        if(!$h_filename)
        {
            $h_filename = $filename;
        }
        header('Content-type: application/x-'.$file_info['extension']);
        header('Content-Disposition: attachment; filename='.$h_filename.".".$file_info['extension']);
        header('Content-Length: '.filesize($filename));
        readfile($filename);
        exit();
    }


    /**
     * 导出的实际操作
     * @param $filter
     */
	private function report_to_usa($filter)
	{
        $filename = $this->_get_file_name_by_filter($filter);
        if ($filter['store_code_arr'] == '')
        {
            $res['code'] = 1;
            $res['err_msg'] = "请选择发货商（Please select Shipper!）";
            echo(json_encode($res));
            exit;
        }

        $this->load->model('m_admin_helper');
        if($filter['status']!= "1"){
            $filter['start_update_date'] = $filter['end_update_date'] = "";
        }
        $this->_set_process_status($filename,['now'=>1,'total'=>0]);
        $lists = $this->m_admin_helper->exportOrderReportAjax($filter, $this->_adminInfo['id'],$filename);
        $this->_set_process_status($filename,['now'=>2,'total'=>0]);
        $shipper_arr = array("2","100","3","200","201","4");


        if(empty($lists))
        {
            $this->_set_process_status($filename,['now'=>-1,'total'=>-1]);
            exit;
        }

        if(isset($lists["supplier"]) && in_array($lists["supplier"],$shipper_arr)){
           ini_set ('memory_limit', '2048M');
           $this->usa_and_kor_excel($lists,$filename,$filter);
        }else{
            ini_set ('memory_limit', '2048M');
           $this->commonly_excel($lists,$filename);
        }
	}

    /**
     * 香港订单的导出
     * @param $objExcel
     * @param $lists
     * @param $filename
     * @return int
     */
    private function xg_excel($objExcel,$lists,$filename){
        $i = 0;
        if ($lists['data'])
        {
            $total = count($lists['data']);
            foreach ($lists['data'] as $k => $v) {
                /*----------写入内容-------------*/
                $u1 = $i +2;
                $phone = $v["phone"];
                $i++;
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["goods_list"]);
                $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["country_address"]);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["order_type"]);
                $this->_set_process_status($filename,['now'=>$i,'total'=>$total],__LINE__);
            }
        }
        return $i;
    }

    /**
     * 普通订单导出
     * @param type $lists
     * @param type $filename
     */
    private function commonly_excel($lists,$filename){

        $this->_mkDirs($this->_tmp_dir);
        $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name', 'Country', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');

        $file = $this->_tmp_dir.$filename.".csv";

        //打开PHP文件句柄,php://output 表示直接输出到浏览器
        $fp = fopen($file, 'w');
//        $head_fp = fopen ( 'php://output', 'a' );
        //输出Excel列名信息
        foreach ($fields as $key => $value) {
            //CSV的Excel支持GBK编码，一定要转换，否则乱码
            $headlist[$key] = iconv('utf-8', 'gbk', $value);
        }

        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $headlist);
//        fputcsv($head_fp, $headlist);

        //计数器
        $num = 0;

        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 5000;
        //逐行取出数据，不浪费内存
       // echo $cc;exit;
        $total = count($lists['data']);
        foreach ($lists["data"] as $key => $value) {
            $row = array(
                iconv('utf-8', 'gbk//IGNORE', (isset($value["pay_time"])?$value["pay_time"]:"")),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["consignee"])?$value["consignee"]:"")),
                iconv('utf-8', 'gbk//IGNORE', is_numeric($value["customer_id"]) ? $value["customer_id"]."\t" : $value["customer_id"]),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["order_id"])?$value["order_id"]:"")),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["good_sku_goods"]))?$value["good_sku_goods"]:""),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["country_address"]))?$value["country_address"]:""),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["address"])?$value["address"]:"")),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["customs_clearance"]))?$value["customs_clearance"]:""),
                iconv('utf-8', 'gbk//IGNORE', is_numeric($value["zip_code"]) ? $value["zip_code"]."\t" :$value["zip_code"]),
                iconv('utf-8', 'gbk//IGNORE', is_numeric($value["phone"]) ? $value["phone"]."\t":$value["phone"]),
                iconv('utf-8', 'gbk//IGNORE', is_numeric($value["freight_info"]) ? $value["freight_info"]."\t" : $value["freight_info"]),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["deliver_time"]) ? $value["deliver_time"] : "")),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["cus_remark"]) ? $value["cus_remark"] : "")),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["sys_remark"]) ? $value["sys_remark"] : "")),
                iconv('utf-8', 'gbk//IGNORE', ($value["ID_no"] ?$value["ID_no"]."\t" : "")),
                iconv('utf-8', 'gbk//IGNORE', ($value["ID_front"] ? config_item('img_server_url') . '/' . $value["ID_front"] : "")),
                iconv('utf-8', 'gbk//IGNORE', ($value["ID_reverse"] ? config_item('img_server_url') . '/' . $value["ID_reverse"] : "")),
                iconv('utf-8', 'gbk//IGNORE', (isset($value["order_type"]) ? $value["order_type"] : "")),
            );
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
//            if ($limit == $num) {
//                ob_flush();
//                flush();
//                $num = 0;
//            }
            fputcsv($fp, $row);
//            fputcsv($head_fp, $row);
            $this->_set_process_status($filename,['now'=>$num,'total'=>$total,'line'=>__LINE__]);
        }
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            $count_head = array("","","","","Statistics Number:");
            foreach ($count_head as $key => $value) {
                $count_headlist[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $count_headlist);
            foreach ($lists["count_goods"] as $key => $value) {
                $row = array(
                    iconv('utf-8', 'gbk//IGNORE', ""),
                    iconv('utf-8', 'gbk//IGNORE', ""),
                    iconv('utf-8', 'gbk//IGNORE', ""),
                    iconv('utf-8', 'gbk//IGNORE', $key),
                    iconv('utf-8', 'gbk//IGNORE', $value["name"]),
                    iconv('utf-8', 'gbk//IGNORE', $value["count"]),
                    iconv('utf-8', 'gbk//IGNORE', date("Y-m-d H:i:s",$value["add_time"])),
                );
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
        //上传到oss
        $this->load->model("m_oss_api");
        $oss_obj = ltrim($file,DIRECTORY_SEPARATOR);
        $this->m_oss_api->doUploadFile($oss_obj,$file);
    }

    /**
     * 美国订单导出
     * @param type $objExcel
     * @param type $lists
     * @return type
     */
    private function usa_excel($objExcel,$lists,$filename){
        $wrap = "\n";
        $n = $i = $now = 0;
        if ($lists['data']){
            $total = count($lists['data']);
             foreach ($lists['data'] as $k => $v) {
                 $now++;
                 /*----------写入内容-------------*/
                 $u1 = $i +2;
                 if(count($v["goods_name_detail"])>1){
                     $new_ul = $u1+count($v["goods_name_detail"])-1;
                     $excel_arr = array("a","b","c","d","g","h","i","j","k","l","m","n","o","p","q","r");
                     foreach($excel_arr as $ev){
                        $objExcel->getActiveSheet()->mergeCells($ev.$u1.':'.$ev.$new_ul);
                     }
                     $i = $i + count($v["goods_name_detail"]);
                 }else{
                     $i++;
                 }

                 foreach($v["goods_name_detail"] as $gk=>$vv){
                     $m = $n + 2;
                     $objExcel->getActiveSheet()->setCellValue('e' . $m, $vv);
                     $objExcel->getActiveSheet()->setCellValue('f' . $m, $v["goods_count"][$gk]);
                     $n++;
                 }
                 if(strpos($v["phone"],'/')!== false){
                     $phone_arr = explode('/', $v["phone"]);
                     $phone = $phone_arr[0];
                 }else{
                     $phone = $v["phone"];
                 }

                 $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                 $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                 $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                 $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
                 $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                 $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                 $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                 $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                 $objExcel->getActiveSheet()->setCellValue('k' . $u1, $v["freight_info"]);
                 $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["deliver_time"]);
                 $objExcel->getActiveSheet()->setCellValue('m' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                 $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                 $objExcel->getActiveSheet()->setCellValue('o' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                 $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                 $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                 $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["order_type"]);
                 $this->_set_process_status($filename,['now'=>$now,'total'=>$total,__LINE__=>'1']);
            }
        }
        return $i;
    }

    /**
     * 韩国订单导出
     * @param type $objExcel
     * @param type $lists
     * @return type
     */
    private function kor_excel($objExcel,$lists,$filename){
        $wrap ="\n";
        $n = $i = $now = 0 ;
        if ($lists['data']) {
            $total = count($lists['data']);
            foreach ($lists['data'] as $k => $v) {
                $now++;
                /*----------写入内容-------------*/
                $u1 = $i + 2;
                $kor_good_list = $kor_good_count = "";
                foreach ($v["goods_name_detail"] as $gk => $vv) {
                    $kor_good_list .= $vv . $wrap;
                    $kor_good_count .= (strpos($vv, $wrap) !== false) ? ($v["goods_count"][$gk] . $wrap . "" . $wrap) : ($v["goods_count"][$gk] . $wrap);
                }
                if (strpos($v["phone"], '/') !== false) {
                    $phone_arr = explode('/', $v["phone"]);
                    $phone = $phone_arr[0];
                    $spare_phone = $phone_arr[1];
                } else {
                    $phone = $v["phone"];
                    $spare_phone = "";
                }
                $i++;
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);

                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $kor_good_list);
                $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $kor_good_count);
                $objExcel->getActiveSheet()->getStyle('f' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行

                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $spare_phone);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('s' . $u1, $v["order_type"]);
                $this->_set_process_status($filename,['now'=>$now,'total'=>$total,__LINE__=>'1']);
            }
        }
        return $i;
    }

    /**
     * 美国跟韩国的excel导出
     * @param $lists
     * @param $filename
     * @param $filter
     */
    private function usa_and_kor_excel($lists,$filename,$filter){
        $usa_shipper_arr = array("2","100");
        $kor_shipper_arr = array("3","200","201");
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'=>'16MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("john");
        $objExcel->setActiveSheetIndex(0);
        if(isset($lists["supplier"]) && in_array($lists["supplier"],$usa_shipper_arr)){
            $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type',"");
        }elseif(isset($lists["supplier"]) && in_array($lists["supplier"],$kor_shipper_arr)){
            $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone','Spare phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
        }else{
               $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name', 'Country', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type',"");
       }
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
        $objExcel->getActiveSheet()->setCellValue('j1', $fields[9]);
        $objExcel->getActiveSheet()->setCellValue('k1', $fields[10]);
        $objExcel->getActiveSheet()->setCellValue('l1', $fields[11]);
        $objExcel->getActiveSheet()->setCellValue('m1', $fields[12]);
        $objExcel->getActiveSheet()->setCellValue('n1', $fields[13]);
        $objExcel->getActiveSheet()->setCellValue('o1', $fields[14]);
        $objExcel->getActiveSheet()->setCellValue('p1', $fields[15]);
        $objExcel->getActiveSheet()->setCellValue('q1', $fields[16]);
        $objExcel->getActiveSheet()->setCellValue('r1', $fields[17]);
        $objExcel->getActiveSheet()->setCellValue('s1', $fields[18]);
        if(isset($lists["supplier"]) && in_array($lists["supplier"],$usa_shipper_arr)){
            $i = $this->usa_excel($objExcel,$lists,$filename);
       }elseif(isset($lists["supplier"]) && in_array($lists["supplier"],$kor_shipper_arr)){
            $i = $this->kor_excel($objExcel,$lists,$filename);
		}else{
            $i = $this->xg_excel($objExcel,$lists,$filename);
        }
        $i = $i + 3;
		$objExcel->getActiveSheet()->setCellValue('e' . $i, 'Statistics Number:');
		if ($lists['count_goods']) {
			sortArrByField($lists['count_goods'],'add_time',false);
			foreach ($lists['count_goods'] as $key => $item) {
				$u1 = $i + 1;
				$objExcel->getActiveSheet()->setCellValue('d' . $u1, $key);
				$objExcel->getActiveSheet()->setCellValue('e' . $u1, $item['name']);
				$objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['count']);
				$objExcel->getActiveSheet()->setCellValue('g' . $u1, date('Y-m-d H:i:s', $item['add_time']));
				$i++;
			}
		}
		// 高置列的宽度
		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(75);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(75);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
		$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
        $objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);

		ob_end_clean();//清除缓冲区,避免乱码
        $this->_write_excel_file($objExcel,$filename,$filter);
    }

    /**
     * 解决Excel2007不能导出
     * @param $objWriter
     */
    private function SaveViaTempFile($objWriter){
		$filePath = '' . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
		$objWriter->save($filePath);
		readfile($filePath);
		unlink($filePath);
	}

	/** 导出paypal */
    private function export_paypal(){
		error_reporting(E_ALL); //开启错误
		require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';
		$data = $this->db->query("select  * from mall_orders_paypal_info where create_time < '2015-10-24' order by create_time asc")->result_array();;
		if($data)foreach($data as $k=>&$v){
			$v['uid'] = " ".$v['uid'];
			$v['order_id'] = " ".$v['order_id'];
			$v['txn_id'] = " ".$v['txn_id'];
			$v['tracking_number'] = " ".$v['tracking_number'];
			$v['company_name'] = " ".$v['company_name'];
			$v['create_time'] = " ".$v['create_time'];
		}
		/*$data1 = $this->db->query("select  customer_id,order_id,txn_id,freight_info from trade_orders where payment_type = '107' and `status` in ('3','4','5','6')")->result_array();;
		$data2 = $this->db->query("select t.customer_id,u.order_sn as order_id,u.txn_id,t.freight_info from user_upgrade_order u left join trade_orders t on u.uid=t.customer_id  where u.payment = 'paypal' and u.`status` = 2 and t.`status` in ('3','4','5','6')")->result_array();;
		$data = array_merge($data1,$data2);
		$this->load->model('m_user_helper');
		if($data)foreach($data as $k=>$v){
			$v['company'] = '';
			if($v['freight_info']){
				$freight_info = explode('|',$v['freight_info']);

				if(isset($freight_info[0])){
					$v['company'] = $this->m_user_helper->getFreightName($freight_info[0]);
				}
				if(isset($freight_info[1])){
					if(strpos($freight_info[1],'#')){
						$v['freight_info'] = substr($freight_info[1],strpos($freight_info[1],'#')+1);
						$v['company'] = 'UPS';
					}else{
						$v['freight_info'] = $freight_info[1];
					}
				}
			}
			$data[$k]= $v;
		}*/

		$objExcel = new PHPExcel();
		//设置属性
		$objExcel->getProperties()->setCreator("john");
		$objExcel->setActiveSheetIndex(0);

		$i=0;
		//表头
		$objExcel->getActiveSheet()->setCellValue('a1',  'Customer_id');
		$objExcel->getActiveSheet()->setCellValue('b1',  'Order_id');
		$objExcel->getActiveSheet()->setCellValue('c1',  'Transaction number');
		$objExcel->getActiveSheet()->setCellValue('d1',  'Tracking Number');
		$objExcel->getActiveSheet()->setCellValue('e1',  'Company');
		$objExcel->getActiveSheet()->setCellValue('f1',  'Dliver Time');
		if($data)foreach($data as $v){
			$u1=$i+2;
			/*----------写入内容-------------*/
			$objExcel->getActiveSheet()->setCellValue('a'.$u1, $v["uid"]);
			$objExcel->getActiveSheet()->setCellValue('b'.$u1, $v["order_id"]);
			$objExcel->getActiveSheet()->setCellValue('c'.$u1, $v["txn_id"]);
			$objExcel->getActiveSheet()->setCellValue('d'.$u1, $v["tracking_number"]);
			$objExcel->getActiveSheet()->setCellValue('e'.$u1, $v["company_name"]);
			$objExcel->getActiveSheet()->setCellValue('f'.$u1, $v["create_time"]);
			$i++;
		}
		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition: attachment;filename="paypal.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

    /**
     * 导出方法入口
     * @param $filter
     */
	public function export_excel($filter)
    {
        $filename = $this->_get_file_name_by_filter($filter);
        $operator_id = $filter['operator_id'];
        if ($filter['store_code_arr'] == '')
        {
            $res['code'] = 1;
            $res['err_msg'] = "请选择发货商（Please select Shipper!）";
            echo(json_encode($res));
            exit;
        }

        $this->load->model('m_admin_helper');
        if($filter['status']!= "1"){
            $filter['start_update_date'] = $filter['end_update_date'] = "";
        }

        $this->_set_process_status($filename,['now'=>1,'total'=>0]);
        $lists = $this->m_admin_helper->exportOrderReportAjax($filter, $this->_adminInfo['id'],$filename);
        $this->_set_process_status($filename,['now'=>2,'total'=>0]);

        if(empty($lists))
        {
            $this->_set_process_status($filename,['now'=>-1,'total'=>-1]);
            exit;
        }

        //大于10W的数据强制使用csv格式导出
        if(isset($lists['data']) and count($lists['data']) > 100000)
        {
            $this->commonly_excel($lists,$filename);
            return;
        }

        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';

        $objExcel = new PHPExcel();
        //设置属性
        $objExcel->getProperties()->setCreator("john");
        $objExcel->setActiveSheetIndex(0);

        if(in_array($operator_id,array(1,4))){
            $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID','SKU', 'Product Name', 'Country', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
            $this->china_export_excel($objExcel,$lists,$filename);
        }

        if(in_array($operator_id,array(2,100))){
            $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type',"");
            $this->usa_export_excel($objExcel,$lists,$filename);
        }

        if(in_array($operator_id,array(3,200,201))){
            $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone','Spare phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
            $this->kor_export_excel($objExcel,$lists,$filename);
        }

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
        $objExcel->getActiveSheet()->setCellValue('j1', $fields[9]);
        $objExcel->getActiveSheet()->setCellValue('k1', $fields[10]);
        $objExcel->getActiveSheet()->setCellValue('l1', $fields[11]);
        $objExcel->getActiveSheet()->setCellValue('m1', $fields[12]);
        $objExcel->getActiveSheet()->setCellValue('n1', $fields[13]);
        $objExcel->getActiveSheet()->setCellValue('o1', $fields[14]);
        $objExcel->getActiveSheet()->setCellValue('p1', $fields[15]);
        $objExcel->getActiveSheet()->setCellValue('q1', $fields[16]);
        $objExcel->getActiveSheet()->setCellValue('r1', $fields[17]);
        $objExcel->getActiveSheet()->setCellValue('s1', $fields[18]);

        // 高置列的宽度
        $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        if(in_array($operator_id,array(1,4))){
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(75);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(75);
        }

        if(in_array($operator_id,array(2,100,3,200,201))){
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(75);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(75);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        }
        $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
        $objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50);
        $objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);
        $objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50);
        $objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
        $objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
        $objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);

        $this->_write_excel_file($objExcel,$filename,$filter);
    }

    /**
     * 中国订单
     * @param type $objExcel
     * @param type $lists
     */
    function china_export_excel($objExcel,$lists,$filename){
        $i = 0 ;
        if ($lists['data']) {
            $total = count($lists['data']);
            foreach ($lists['data'] as $k => $v) {
                $u1 = $i +2;
                $phone = $v["phone"];
                $i++;
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["good_sku_goods"]);
                $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["goods_list"]);
                $objExcel->getActiveSheet()->getStyle('f' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["country_address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('s' . $u1, $v["order_type"]);
                $this->_set_process_status($filename,['now'=>$i,'total'=>$total,__LINE__=>'1']);
            }
        }
        $i = $i + 3;
        $objExcel->getActiveSheet()->setCellValue('e' . $i, 'Statistics Number:');
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            foreach ($lists['count_goods'] as $key => $item) {
                $u1 = $i + 1;
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $key);
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['name']);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $item['count']);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, date('Y-m-d H:i:s', $item['add_time']));
                $i++;
            }
        }
    }
    /**
     * 美国订单
     * @param type $objExcel
     * @param type $lists
     */
    function usa_export_excel($objExcel,$lists,$filename){
        $wrap = "\n";
        $n = $i = 0;
        if ($lists['data']) {
            $total = count($lists['data']);
            foreach ($lists['data'] as $k => $v) {
                /*----------写入内容-------------*/
                $u1 = $i + 2;
                if (count($v["goods_name_detail"]) > 1) {
                    $new_ul = $u1 + count($v["goods_name_detail"]) - 1;
                    $excel_arr = array("a", "b", "c", "d", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r");
                    foreach ($excel_arr as $ev) {
                        $objExcel->getActiveSheet()->mergeCells($ev . $u1 . ':' . $ev . $new_ul);
                    }
                    $i = $i + count($v["goods_name_detail"]);
                } else {
                    $i++;
                }

                foreach ($v["goods_name_detail"] as $gk => $vv) {
                    $m = $n + 2;
                    $objExcel->getActiveSheet()->setCellValue('e' . $m, $vv);
                    $objExcel->getActiveSheet()->setCellValue('f' . $m, $v["goods_count"][$gk]);
                    $n++;
                }
                if (strpos($v["phone"], '/') !== false) {
                    $phone_arr = explode('/', $v["phone"]);
                    $phone = $phone_arr[0];
                } else {
                    $phone = $v["phone"];
                }

                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["order_type"]);
                $this->_set_process_status($filename, ['now' => $i, 'total' => $total, __LINE__ => '1']);
            }
        }
        $i = $i + 3;
        $objExcel->getActiveSheet()->setCellValue('d' . $i, 'Statistics Number:');
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            foreach ($lists['count_goods'] as $key => $item) {
                $u1 = $i + 1;
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $key);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $item['name']);
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['count']);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, date('Y-m-d H:i:s', $item['add_time']));
                $i++;
            }
        }
    }
    /**
     * 韩国订单
     * @param type $objExcel
     * @param type $lists
     */
    function kor_export_excel($objExcel,$lists,$filename)
    {
        $wrap = "\n";
        $i = 0;
        if ($lists['data']) {
            $total = count($lists['data']);
            foreach ($lists['data'] as $k => $v) {
                /*----------写入内容-------------*/
                $u1 = $i + 2;
                $kor_good_list = $kor_good_count = "";
                foreach ($v["goods_name_detail"] as $gk => $vv) {
                    $kor_good_list .= $vv . $wrap;
                    $kor_good_count .= (strpos($vv, $wrap) !== false) ? ($v["goods_count"][$gk] . $wrap . "" . $wrap) : ($v["goods_count"][$gk] . $wrap);
                }
                if (strpos($v["phone"], '/') !== false) {
                    $phone_arr = explode('/', $v["phone"]);
                    $phone = $phone_arr[0];
                    $spare_phone = $phone_arr[1];
                } else {
                    $phone = $v["phone"];
                    $spare_phone = "";
                }
                $i++;
                $objExcel->getActiveSheet()->setCellValue('a' . $u1, $v["pay_time"]);
                $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
                $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);

                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $kor_good_list);
                $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $kor_good_count);
                $objExcel->getActiveSheet()->getStyle('f' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行

                $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
                $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
                $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
                $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
                $objExcel->getActiveSheet()->setCellValue('k' . $u1, $spare_phone);
                $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["freight_info"]);
                $objExcel->getActiveSheet()->setCellValue('m' . $u1, $v["deliver_time"]);
                $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('o' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
                $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
                $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
                $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
                $objExcel->getActiveSheet()->setCellValue('s' . $u1, $v["order_type"]);
                $this->_set_process_status($filename, ['now' => $i, 'total' => $total, __LINE__ => '1']);
            }
        }
        $i = $i + 3;
        $objExcel->getActiveSheet()->setCellValue('d' . $i, 'Statistics Number:');
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            foreach ($lists['count_goods'] as $key => $item) {
                $u1 = $i + 1;
                $objExcel->getActiveSheet()->setCellValue('d' . $u1, $key);
                $objExcel->getActiveSheet()->setCellValue('e' . $u1, $item['name']);
                $objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['count']);
                $objExcel->getActiveSheet()->setCellValue('g' . $u1, date('Y-m-d H:i:s', $item['add_time']));
                $i++;
            }
        }
    }
}