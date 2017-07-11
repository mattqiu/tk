<?php
/**
 * 导出需要过银行的订单
 * 2017/07/4
 * rongxia
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class export_bank_orders extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->model('tb_trade_orders');
        $this->load->model('tb_trade_orders_goods');
        $this->load->model('m_do_img');
        $this->load->model('tb_exchange_rate_history');
        $this->load->model('tb_exchange_rate');
        $this->load->model('tb_mall_goods_customs');
        $this->load->model('tb_export_customs_orders');

	}

	public function index(){

		$this->_viewData['title'] = lang('export_bank_orders');

		// status map
		$status_map = array(
			0 => array('class' => "text-default", 'text' => lang('admin_order_status_all')),
			Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
			Order_enum::STATUS_SHIPPING => array('class' => "text-success", 'text' => lang('admin_order_status_paied')),
			Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
			Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
			Order_enum::STATUS_EVALUATION => array('class' => "text-warning", 'text' => lang('admin_order_status_arrival')),
			Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
			Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
			Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
			Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
		);

        $this->_viewData['status_map'] = $status_map;
		parent::index('admin/');
	}

    /**
     * 导出海关订单
     */
	public function report_to_usa(){

        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        if($this->input->is_ajax_request()){
            $searchData = $this->input->post() ? $this->input->post() : array();

            $filter['status'] = isset($searchData['status']) ? $searchData['status'] : '';
            $filter['start_date'] = isset($searchData['start_date']) ? $searchData['start_date'] : '';
            $time_period = isset($searchData['time_period']) ? $searchData['time_period'] : 0;

            //不能查询这个时间之前的
            if($filter['start_date'] < '2017-04-20 10:45:59'){
//                echo 'Time is not too early';
                die(json_encode(array('success'=>1,'msg'=>"不能查询这个时间之前的")));
            }
            
            //根据时间获取订单表名
            $ym = substr(str_replace('-','',$filter['start_date']),2,4);
            $table = 'trade_orders_'.$ym;
            $table_1 = 'trade_orders_info_'.$ym;
            $sql = "SELECT a.order_id,a.customer_id,b.consignee,b.phone,b.address,b.remark,a.payment_type,a.currency,a.goods_amount_usd,a.deliver_fee,a.deliver_fee_usd,a.order_amount_usd,a.order_profit_usd,a.created_at,a.txn_id,a.pay_time,a.`status`,b.ID_no FROM $table as a LEFT JOIN $table_1 as b on a.order_id = b.order_id LEFT JOIN is_customs_bank as c ON a.order_id = c.order_id
WHERE c.order_id  is NULL and b.ID_no != ''";

            if($filter['status'] != 0 && $filter['status'] != ''){
                $sql .=" and a.status = ".$filter['status'];
            }
            if($time_period){//昨天下午三点到今天早上10点 OR  早上10点到下午3点
                if($filter['start_date']){
                    $houzui='上午';
                    $sql .=" and a.pay_time >= '".date('Y-m-d H:i:s',strtotime($filter['start_date']))."'";
                    $sql .=" and a.pay_time <= '".date('Y-m-d H:i:s',strtotime($filter['start_date'])+12*3600)."'";
                }
            } else {
                if($filter['start_date']){
                    $houzui='下午';
                    $sql .=" and a.pay_time >= '".date('Y-m-d H:i:s',strtotime($filter['start_date'])+12*3600)."'";
                    $sql .=" and a.pay_time <= '".date('Y-m-d H:i:s',strtotime($filter['start_date'])+24*3600-1)."'";
                }
            }
            $this->db->trans_begin();//事务开始

            //订单列表
            $lists = $this->db->query($sql)->result_array();
//            echo $this->db->last_query();exit;
//            fout($lists);exit;
            if(empty($lists)){
                die(json_encode(array('success'=>1,'msg'=>"该时间段内没有符合条件的数据！")));
            }
            //创建文件存放路径
            $end_date = empty($filter['end_date'])?date('Y-m-d',time()):$filter['end_date'];
            $path = '/tmp/'.$filter['start_date'].'_'.$end_date.'_'.time();
            $path_zip="/tmp";

            //判断目录存在否，存在给出提示，不存在则创建目录
            if (!is_dir($path)){
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res=mkdir(iconv("UTF-8", "GBK", $path),0777,true);
                if (!$res){
                    echo "path $path fail";exit;
                }
            }
            if (!is_dir($path_zip)){
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res=mkdir(iconv("UTF-8", "GBK", $path_zip),0777,true);
                if (!$res){
                    echo "path $path_zip fail";exit;
                }
            }
            $data = array();
            foreach($lists as $v){
                if(empty($v)){
                    continue;
                }
                $data[] = array(
                    'order_id' => $v['order_id'],
                    'type' => 1,
                    'time' => date('Y-m-d H:i:s',time()),
                );
                //  创建一个XML文档并设置XML版本和编码。。
                $dom = new DomDocument('1.0', 'utf-8');
                $guid=$this->created_guid();
                //获取时间，毫秒
                $microtime = microtime(true);
                $microtime = substr($microtime,strpos($microtime,'.') + 1,3);
                $SendTime =  date('Ymdhis',time()).$microtime;
                //查询下单时的汇率
                $rate = $this->tb_exchange_rate_history->get_one_auto(
                    [
                        "select"=>'rate',
                        "where"=>['currency'=>'CNY','create_time >=' => $v['pay_time']],
                        "order_by"=>['create_time'=>'ASC']
                    ]
                );
                if(empty($rate)){
                    $rate  = $this->tb_exchange_rate->get_one_auto(
                        [
                            "select"=>'rate',
                            "where"=>['currency'=>'CNY']
                        ]
                    );
                }
            $MessageHead_arr = array(
                'MessageID'=>$guid,                                     //报文唯一编号C36 企业系统生成36 位报文唯一序号（要求为guid36 位，英文字母大写，36位
                'MessageType' => 'CEB411',                              //CEB411-订单，6位
                'OrgCode' => '689413365',                      //企业组织机构代码或统一社会信息代码
                'CopCode' => '440316T016',                              //报文传输的企业海关注册代码（需要与接入客户端的企业身份一致）
                'CopName' => '中国工商银行股份有限公司前海分行',            //报文传输的企业海关注册名
                'SenderID' => 'DXPENT0000012148',                                //发送方
                'ReceiverID' => 'EPORT',                                //接收方
                'ReceiverDepartment' => 'CQ',                          //接受部门：填写本报文发送的监管单位，可同时填写多个监管部门：C-海关；Q-检验检疫；M-市场监管例如：同时发送至海关、检验检疫、市场监管可填写：CQM本节点根据监管部门要求将来可扩展
                'SendTime'=>$SendTime,                                   //发送时间：格式：yyyyMMddHHmmssSSS，毫秒级，17位
                'Version' => '1.0',                                     //版本号
            );
            $PaymentHead_arr = array(
                'guid' =>$guid,
                'appType' => '1',
                'appTime' => date('YmdHis', time()),
                'appStatus' => '2',
                'payCode' => '440316T016',
                'payName' => '中国工商银行股份有限公司前海分行',
                'payTransactionId' => '4403160JFXICBC'.$v['txn_id'],
                'orderNo' => $v['order_id'],
                'ebpCode' => '4403160JFX',
                'ebpName' => '深圳前海云集品电子商务有限公司',
                'payerIdType' => '1',
                'payerIdNumber' =>$v['ID_no'],
                'payerName' => $v['consignee'],
                'telephone' => $v['phone'],
                'amountPaid' => $v['order_amount_usd'],
                'currency' => '142',
                'payTime' => date('YmdHis', strtotime($v['pay_time'])),
                'note' => '',
        );

                //名称格式：CEB_CEB411_[senderId]_EPORT_[yyyyMMddHHmmssSSS+4 位流水号].xm
                $rand = sprintf("%04s", mt_rand(0, 9999));
                $xmlpatch = $path.'/CEB_CEB411_DXPENT0000012148_EPORT_'.$SendTime.$rand.'.xml';

                //创建根节点
                $index = $dom->createElement('CEB411Message');
                $dom->appendchild($index);
                //  属性数组
                $attribute_array = array(
                    'CEB411Message' => array(
                        'xmlns' => "http://www.chinaport.gov.cn/ceb",
                        'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
                        'guid' => "$guid",
                        'version' => "1.0",
//                        'xsi:schemaLocation' => "1.0"
                    )
                );
                foreach($attribute_array['CEB411Message'] as $kk=>$vv){
                    //  创建属性节点
                    $akey = $dom->createAttribute($kk);
                    $index->appendchild($akey);
                    // 创建属性值节点
                    $aval = $dom->createTextNode($vv);
                    $akey->appendChild($aval);
                }
                //创建二级节点
                $MessageHead = $dom->createElement('MessageHead');
                $index->appendchild($MessageHead);
                $Payment = $dom->createElement('Payment');
                $index->appendchild($Payment);

                //创建三级节点
                $PaymentHead = $dom->createElement('PaymentHead');
                $Payment->appendchild($PaymentHead);

                $this->create_item($dom, $MessageHead, $MessageHead_arr);
                $this->create_item($dom, $PaymentHead, $PaymentHead_arr);

                $dom -> save($xmlpatch);//保存文件
            }

            //压缩文件夹
            $zip=new ZipArchive();
            if($zip->open($path_zip.'/'.$filter['start_date'].'_'.$end_date.'_'.time().'.zip', ZipArchive::OVERWRITE)=== TRUE) {
                $is_zip = $this->addFileToZip($path, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
                $zip->close(); //关闭处理的zip文件
                if(!$is_zip){
//                    echo 'compress fail';
                    die(json_encode(array('success'=>1,'msg'=>"压缩文件失败！")));
                }

                $pathFile = 'upload/temp/export_order/'.$filter['start_date'].'_'.$end_date.'_'.time().'.zip';
                $FileName = $path_zip.'/'.$filter['start_date'].'_'.$end_date.'_'.time().'.zip';
                $result = $this->m_do_img->upload($pathFile,$FileName);
                if(!$result){
//                    echo 'upload fail';
                    die(json_encode(array('success'=>1,'msg'=>"上传失败!")));
                }
            }
            ///导出订单流水记录
            $this->db->insert_batch('is_customs_bank', $data);
            //记录导出记录
            $this->db->insert('export_customs_orders',array(
                "fifter_array" =>serialize($filter),
                "file_name"=> $filter['start_date'].'_'.$end_date.'_'.time().$houzui,//文件名
                "file_path"=> config_item('img_server_url').'/'.$pathFile,//文件路径
                "status"=>2,
                "admin_id"=>$this->_adminInfo['id'],
                "update_time"=>date("Y-m-d H:i:s",time()),
                "create_time"=>date("Y-m-d H:i:s",time()),
            ));

            //删除原有文件夹
            $this->delFile($path,'',true);
            $this->delFile($FileName);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
//                echo 'Mysql Rollback!';
                exit;
            }
            else
            {
                $this->db->trans_commit();
//                echo 'Export Success';
                die(json_encode(array('success'=>1,'msg'=>"后台处理中!")));
            }
        }
	}

    /**
     * 生成XML文件
     * @param $dom
     * @param $item
     * @param $data
     * @param $attribute
     */
    function create_item($dom, $item, $data, $attribute = array()) {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                //  创建元素
                $$key = $dom->createElement($key);
                $item->appendchild($$key);
                //  创建元素值
                $text = $dom->createTextNode($val);
                $$key->appendchild($text);
                if (isset($attribute[$key])) {
                    //  如果此字段存在相关属性需要设置
                    foreach ($attribute[$key] as $akey => $row) {
                        //  创建属性节点
                        $$akey = $dom->createAttribute($akey);
                        $$key->appendchild($$akey);
                        // 创建属性值节点
                        $aval = $dom->createTextNode($row);
                        $$akey->appendChild($aval);
                    }
                }
            }
        }
    }

    /**
     * GUID 生成函数
     * @return string
     */
    function created_guid(){
//        if(function_exists('com_create_guid')){
//            return com_create_guid();//window下
//        }else{//非windows下
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 andup.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);//字符 "-"
            $uuid =
//                chr(123)//字符 "{"
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
//                .chr(125);//字符 "}"
            return $uuid;
//        }
    }

    /**
     * 压缩文件夹
     * @param $path
     * @param $zip
     */
    function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $res = $zip->addFile($path."/".$filename);
                }
            }
        }
        @closedir($path);
        return $res;
    }

    /*
     *
     * 删除指定目录中的所有目录及文件（或者指定文件）
     * 可扩展增加一些选项（如是否删除原目录等）
     * 删除文件敏感操作谨慎使用
     * @param $dir 目录路径
     * @param array $file_type指定文件类型
     * @param $type 是否删除本身文件，默认否
     */
    function delFile($dir,$file_type='',$type = false) {
        if(is_dir($dir)){
            $files = scandir($dir);
            //打开目录 //列出目录中的所有文件并去掉 . 和 ..
            foreach($files as $filename){
                if($filename!='.' && $filename!='..'){
                    if(!is_dir($dir.'/'.$filename)){
                        if(empty($file_type)){
                            unlink($dir.'/'.$filename);
                        }else{
                            if(is_array($file_type)){
                                //正则匹配指定文件
                                if(preg_match($file_type[0],$filename)){
                                    unlink($dir.'/'.$filename);
                                }
                            }else{
                                //指定包含某些字符串的文件
                                if(false!=stristr($filename,$file_type)){
                                    unlink($dir.'/'.$filename);
                                }
                            }
                        }
                    }else{
                        delFile($dir.'/'.$filename);
                        rmdir($dir.'/'.$filename);
                    }
                }
            }
        }else{
            if(file_exists($dir)) unlink($dir);
        }

        //删除当前目录
        if($type){
            rmdir($dir);
        }
    }

    /**
     * 间隔3秒钟请求脚本状态
     */
    function export_status_ajax(){
        $data = $this->tb_export_customs_orders->getList($this->_adminInfo['id']);
        if($data){
            echo json_encode(array("succ"=>1,"list"=>$data));
        }else{
            echo json_encode(array("succ"=>0,"list"=>""));
        }

    }
    function download_file(){
            $id = $_GET["download_id"];
            $data = $this->db->where("id",$id)->get("export_customs_orders")->row_array();
            if(!empty($data)){
                $update_path = $data["file_path"];
                $file_name = str_replace(array(".",","),array("．","，"),$data["file_name"]);//命名特殊符号替换成全角
               // fout($file_name);exit;
                header('Content-type: application/x-excel');
                header("Content-Disposition: attachment; filename={$file_name}.zip");//下载时显示的名字
                readfile($update_path);
                exit();
            }
    }
    /**
    * 下载之后改变状态
    * @param $filename
    * @param $h_filename
    */
    function download_change_s(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post("id");
            $this->db->where("id",$id)->update("export_customs_orders",array("status"=>3));
       }
   }
   function del_change_s(){
       if($this->input->is_ajax_request()){
             $id = $this->input->post("id");
            $this->db->where("id",$id)->update("export_customs_orders",array("status"=>3));
       }
   }
    function ccc(){
        $my_curl = curl_init();
        curl_setopt($my_curl, CURLOPT_URL, base_url()."cron/export_excel");
        curl_setopt($my_curl,CURLOPT_RETURNTRANSFER,1);
        $str = curl_exec($my_curl);
        echo $str;
        curl_close($my_curl);
    }
	/*解决Excel2007不能导出*/
	function SaveViaTempFile($objWriter){
		$filePath = '' . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
		$objWriter->save($filePath);
		readfile($filePath);
		unlink($filePath);
	}

	/** 导出paypal */
	public function export_paypal(){
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
}
?>