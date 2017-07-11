<?php
/**
 * 导出需要过海关的订单
 * 2017/06/17
 * Ckf
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class export_customs_orders extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_trade_orders');
        $this->load->model('tb_trade_orders_goods');
        $this->load->model('m_do_img');
        $this->load->model('tb_exchange_rate_history');
        $this->load->model('tb_exchange_rate');
        $this->load->model('tb_mall_goods_customs');
        $this->load->model('tb_export_customs_orders');

    }

    public function index()
    {

        $this->_viewData['title'] = lang('export_customs_orders');

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
    public function report_to_usa()
    {

        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        if ($this->input->is_ajax_request()) {
            $searchData = $this->input->post() ? $this->input->post() : array();

            $filter['status'] = isset($searchData['status']) ? $searchData['status'] : date('Y-m-01 Y-m-d H:i:s', strtotime(date("Y-m-d")));
            $filter['start_date'] = isset($searchData['start_date']) ? $searchData['start_date'] : '';
            $filter['end_date'] = isset($searchData['end_date']) ? $searchData['end_date'] : '';

            //不能查询这个时间之前的
            if ($filter['start_date'] < config_item('Time_too_early')) {
                die(json_encode(array('success' => 0, 'msg' => "不能查询这个时间之前的订单")));
            }

            //开始时间不能为空
            if (empty($filter['start_date'])) {
                die(json_encode(array('success' => 0, 'msg' => "开始的时间不能为空")));
            }
            //判断时间，开始时间和结束时间不能跨月份查询
            if (!empty($filter['end_date']) && (date("m", strtotime($filter['start_date'])) != date("m", strtotime($filter['end_date'])))) {
                die(json_encode(array('success' => 0, 'msg' => "开始时间和结束时间不能跨月份查询")));
            }

            //如果结束时间不输入，判断开始时间是不是本月，如果是本月，则结束时间为本月的当天；如果开始时间不是本月，则结束时间赋值为开始时间月份的最后一天
            if (empty($filter['end_date'])) {
                if (date("m", strtotime($filter['start_date'])) != date("m")) {
                    $firstday = date('Y-m-01', strtotime($filter['start_date']));
                    $filter['end_date'] = date('Y-m-d H:i:s', strtotime("$firstday +1 month -1 day"));
                }
            }
            if (!empty($filter['end_date'])) {
                $a = $filter['end_date'];
                $b = date('Y-m-d H:i:s', strtotime("$a +1 day -1 second"));
            }else{
                $b = $filter['end_date'];
            }
            //根据时间获取订单表名
            $ym = substr(str_replace('-', '', $filter['start_date']), 2, 4);
            $table = 'trade_orders_' . $ym;
            $table_1 = 'trade_orders_info_' . $ym;
            $sql = "SELECT a.order_id,a.customer_id,b.consignee,b.phone,b.address,b.remark,a.payment_type,a.currency,
a.goods_amount_usd,a.deliver_fee,a.deliver_fee_usd,a.order_amount_usd,a.order_profit_usd,a.created_at,a.txn_id,a.pay_time,
a.`status`,b.ID_no FROM $table as a LEFT JOIN $table_1 as b on a.order_id = b.order_id LEFT JOIN is_customs_bank as c ON a.order_id = c.order_id
WHERE (c.order_id  is NULL or c.type != '1' ) and b.ID_no != '' and a.pay_time >=\"" . $filter['start_date'] . '"';

            if ($filter['status'] != 0 && $filter['status'] != '') {
                $sql .= " and a.`status` = " . $filter['status'];
            }
            if ($b != '') {
                $sql .= " and a.pay_time < \"" . $b . '"';
            }
//            print_r($sql);exit;
            $this->db->trans_begin();//事务开始
            $lists = $this->db->query($sql)->result_array();
            if (empty($lists)) {
                die(json_encode(array('success' => 1, 'msg' => "该时间段内没有符合条件的数据！")));
            }
//print_r($this->db->last_query());exit;
            //支付类型
            $pay_type = array(
                '104' => '微信支付',
                '105' => '支付宝',
                '106' => '中国银联',
                '107' => 'paypal',
                '108' => '电子钱包',
                '109' => '银盛',
                '110' => '余额支付',
                '111' => '国际银联',
                '112' => 'APP微信支付',
            );

            //创建文件存放路径
            $end_date = empty($filter['end_date']) ? date('Y-m-d', time()) : $filter['end_date'];
            $path = '/tmp/' . $filter['start_date'] . '_' . $end_date . '_' . time();
            $path_zip = "/tmp";

            //判断目录存在否，存在给出提示，不存在则创建目录
            if (!is_dir($path)) {
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
                if (!$res) {
                    die(json_encode(array('success' => 1, 'msg' => "path $path fail")));
                }
            }
            if (!is_dir($path_zip)) {
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res = mkdir(iconv("UTF-8", "GBK", $path_zip), 0777, true);
                if (!$res) {
                    die(json_encode(array('success' => 1, 'msg' => "path $path_zip fail")));
                }
            }


            $data = array();
            $str_1 = '';
            $str_2 = '';
            foreach ($lists as $v) {

                //初始化
                $MessageHead_arr = array();
                $OrderList_arr = array();
                $OrderHead_arr = array();
                //  创建一个XML文档并设置XML版本和编码。。
                $dom = new DomDocument('1.0', 'UTF-8');
                if (empty($v)) {
                    continue;
                }

                $data[] = array(
                    'order_id' => $v['order_id'],
                    'type' => 1,
                    'time' => date('Y-m-d H:i:s', time()),
                );

                //获取时间，毫秒
                $microtime = microtime(true);
                $microtime = substr($microtime, strpos($microtime, '.') + 1, 3);
                $SendTime = date('Ymdhis', time()) . $microtime;
                $MessageHead_arr['SendTime'] = $SendTime;                    //发送时间：格式：yyyyMMddHHmmssSSS，毫秒级，17位
                $guid = $this->created_guid();                               //生成唯一的guid
                $SenderID = 'DXPSDC5300000003';
                $MessageHead_arr = array(
                    'MessageID' => $guid,                                   //C报文唯一编号C36 企业系统生成36 位报文唯一序号（要求为guid36 位，英文字母大写，36位
                    'MessageType' => 'CEB311',                              //CEB311-订单，6位
                    'OrgCode' => '91440300311681288R',                      //企业组织机构代码或统一社会信息代码
                    'CopCode' => '4403160JFX',                              //报文传输的企业海关注册代码（需要与接入客户端的企业身份一致）
                    'CopName' => '深圳前海云集品电子商务有限公司',            //报文传输的企业海关注册名
                    'SenderID' => $SenderID,                                //发送方
                    'ReceiverID' => 'EPORT',                                //接收方
                    'ReceiverDepartment' => 'CQM',                          //接受部门：填写本报文发送的监管单位，可同时填写多个监管部门：C-海关；Q-检验检疫；M-市场监管例如：同时发送至海关、检验检疫、市场监管可填写：CQM本节点根据监管部门要求将来可扩展
                    'SendTime' => $SendTime,                          //接受部门：填写本报文发送的监管单位，可同时填写多个监管部门：C-海关；Q-检验检疫；M-市场监管例如：同时发送至海关、检验检疫、市场监管可填写：CQM本节点根据监管部门要求将来可扩展
                    'Version' => '1.0',                                     //版本号
                );

                //查询下单时的汇率
                $rate = $this->tb_exchange_rate_history->get_one_auto(
                    [
                        "select" => 'rate',
                        "where" => ['currency' => 'CNY', 'create_time >=' => $v['pay_time']],
                        "order_by" => ['create_time' => 'ASC']
                    ]
                );
                if (empty($rate)) {
                    $rate = $this->tb_exchange_rate->get_one_auto(
                        [
                            "select" => 'rate',
                            "where" => ['currency' => 'CNY']
                        ]
                    );
                }

                //组装数组：订单信息
                $OrderHead_arr['guid'] = $guid;                                         //系统唯一序号:企业系统生成36 位单证唯一序号（要求为guid36 位，英文字母大写），36位
                $OrderHead_arr['appType'] = '1';                                        //企业报送类型。1-新增，2-变更。默认1
                $OrderHead_arr['appTime'] = date('Ymdhis', time());                      //报送时间：格式:YYYYMMDDhhmmss，14位，秒
                $OrderHead_arr['appStatus'] = '2';                                      //业务状态:1-暂存,2-申报,默认为2
                $OrderHead_arr['orderType'] = 'I';                                      //电子订单类型：I 进口,字母 i
                $OrderHead_arr['orderNo'] = $v['order_id'];                             //同一交易平台的订单编号应唯一编号
                $OrderHead_arr['ebpCode'] = '4403160JFX';                               //电商商户代码:电商企业的海关注册登记编号。
                $OrderHead_arr['ebpName'] = 'TPS商城';                                  //电商平台企业名称：电商平台的海关注册登记名称；电商平台未在海关注册登记，由电商企业发送订单的，以中国电子口岸发布的电商平台名称为准
                $OrderHead_arr['ebcCode'] = '4403160JFX';                               //电商商户代码:电商企业的海关注册登记编号。
                $OrderHead_arr['ebcName'] = 'TPS商城';                                  //电商企业的海关注册登记名称。
                $OrderHead_arr['goodsValue'] = round($rate['rate'] * $v['goods_amount_usd'], 2);  //商品价格：商品实际成交价，含非现金抵扣金额，人民币
                $OrderHead_arr['freight'] = round($rate['rate'] * $v['deliver_fee_usd'], 2);           //不包含在商品价格中的运杂费，无则填写“0”
                $OrderHead_arr['discount'] = round($rate['rate'] * ($v['order_amount_usd'] - $v['goods_amount_usd'] - $v['deliver_fee_usd']), 2); //非现金抵扣金额：使用积分、虚拟货币、代金券等非现金支付金额，无则填写“0”
                $OrderHead_arr['taxTotal'] = 0;                                         //企业预先代扣的税款金额，无则填“0”。
                $OrderHead_arr['acturalPaid'] = round($rate['rate'] * $v['order_amount_usd'], 2); //商品价格+运杂费+代扣税款-非现金抵扣金额，与支付凭证的支付金额一致
                $OrderHead_arr['currency'] = '142';                                     //限定为人民币，填写“142”。
                $OrderHead_arr['buyerRegNo'] = $v['customer_id'];                       //用户id，订购人的交易平台注册号
                $OrderHead_arr['buyerName'] = $v['consignee'];                          //用户名字，订购人的真实姓名。
                $OrderHead_arr['buyerIdType'] = 1;                                      //1-身份证，2-其它。限定为身份证，填写“1”。
                $OrderHead_arr['buyerIdNumber'] = $v['ID_no'];                          //订购人的身份证号。
                $OrderHead_arr['payCode'] = '';                                         //支付企业的海关注册登记编号
                $OrderHead_arr['payName'] = isset($pay_type[$v['payment_type']]) ? $pay_type[$v['payment_type']] : 'Unclear';//支付企业在海关注册登记的企业名称
                $OrderHead_arr['payTransactionId'] = $v['txn_id'];                      //支付企业唯一的支付流水号
                $OrderHead_arr['batchNumbers'] = '';                                    //商品批次号
                $OrderHead_arr['consignee'] = $v['consignee'];                          //收货人
                $OrderHead_arr['consigneeTelephone'] = $v['phone'];                     //收货人联系电话
                $OrderHead_arr['consigneeAddress'] = $v['address'];                     //收货地址
//                $OrderHead_arr['consigneeDistrict'] = '';                               //参照国家统计局公布的国家行政区划标准填制，身份证前6位
                $OrderHead_arr['note'] = $v['remark'];                                  //订单备注

                //查询订单下的商品
                $sku_list = $this->tb_trade_orders_goods->get_list('goods_sn_main,goods_sn,goods_name,goods_number,goods_price', $where = [
                    'order_id' => $v['order_id']
                ]);

                //名称格式：CEB_CEB311_[senderId]_EPORT_[yyyyMMddHHmmssSSS+4 位流水号].xm
                $rand = sprintf("%04s", mt_rand(0, 9999));
                $xmlpatch = $path . '/CEB_CEB311_' . $SenderID . '_EPORT_' . $SendTime . $rand . '.xml';

                //创建根节点
                $index = $dom->createElement('CEB311Message');
                $dom->appendchild($index);

                //  属性数组
                $attribute_array = array(
                    'CEB311Message' => array(
                        'xmlns' => "http://www.chinaport.gov.cn/ceb",
                        'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
                        'guid' => "$guid",
                        'version' => "1.0",
//                        'xsi:schemaLocation' => "1.0"
                    )
                );
                foreach ($attribute_array['CEB311Message'] as $kk => $vv) {
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
                $Order = $dom->createElement('Order');
                $index->appendchild($Order);

                //创建三级节点
                $OrderHead = $dom->createElement('OrderHead');
                $OrderList = $dom->createElement('OrderList');
                $Order->appendchild($OrderHead);
                $Order->appendchild($OrderList);

                $this->load->model('tb_mall_goods_main');
                foreach ($sku_list as $k => $good_info) {

                    //查询原产国和计量单位
                    $goods = $this->tb_mall_goods_main->get_one_auto(
                        [
                            "select" => 'country_code,goods_unit',
                            "where" => ['goods_sn_main =' => $good_info['goods_sn_main']],
                        ]
                    );

                    if (empty($goods) || $goods['country_code'] = '' || $goods['country_code'] = '') {
                        $str_2 .= $v['order_id'] . ' 的商品：' . $goods['goods_sn'] . ',';
                        continue 2;
                    }

                    //订单商品信息
                    $OrderList_arr['gnum'] = $k + 1;                                 //商品序号：从1 开始的递增序号
                    $OrderList_arr['itemNo'] = $good_info['goods_sn'];               //电商平台自定义的商品货号（SKU）
                    $OrderList_arr['itemName'] = $good_info['goods_name'];           //交易平台销售商品的中文名称
                    $OrderList_arr['itemDescribe'] = '';                                 //交易平台销售商品的描述信息
                    $OrderList_arr['barCode'] = '';                                      //国际通用的商品条形码，一般由前缀部分、制造厂商代码、商品代码和校验码组成
                    $OrderList_arr['unit'] = isset($goods_info['goods_unit']) ? $goods_info['goods_unit'] : '';                                   //填写海关标准的参数代码，参照《JGS-20海关业务代码集》- 计量单位代码
                    $OrderList_arr['qty'] = $good_info['goods_number'];              //商品数量
                    $OrderList_arr['price'] = round($rate['rate'] * $good_info['goods_price'], 2);                                //商品单价。赠品单价填写为“0”
                    $OrderList_arr['totalPrice'] = round($rate['rate'] * ($good_info['goods_number'] * $good_info['goods_price']), 2); //商品总价，等于单价乘以数量
                    $OrderList_arr['currency'] = '142';                                  //币种：限定为人民币，填写“142”
                    $OrderList_arr['country'] = isset($goods_info['country_code']) ? $goods_info['country_code'] : '';             //原产国：填写海关标准的参数代码，参照《JGS-20海关业务代码集》-国家（地区）代码表

                    //海关信息
                    $cus_list = $this->tb_mall_goods_customs->get_one('ciqgno,gcode,gcode,gmodel,ciqgmodel', $where = [
                        'goods_sn' => $good_info['goods_sn']
                    ]);

                    if (empty($cus_list) || $cus_list['ciqgno'] = '' || $cus_list['gcode'] = '' || $cus_list['gmodel'] = '' || $cus_list['ciqgmodel'] = '') {
                        $str_1 .= $v['order_id'] . ' 的商品：' . $good_info['goods_sn'] . ',';
                        continue 2;
                    }

                    $OrderList_arr['ciqGno'] = isset($cus_list['ciqgno']) ? $cus_list['ciqgno'] : '';  //检验检疫商品备案号：保税进口必填
                    $OrderList_arr['gcode'] = isset($cus_list['gcode']) ? $cus_list['gcode'] : '';       //商品编码：符合《中华人民共和国海关进出品税则》内列明的10 位税号
                    $OrderList_arr['gmodel'] = isset($cus_list['gmodel']) ? $cus_list['gmodel'] : '';           //海关规格型号：满足海关归类、审价以及监管的要求为准。包括：品牌、规格、型号等
                    $OrderList_arr['ciqGmodel'] = isset($cus_list['ciqgmodel']) ? $cus_list['ciqgmodel'] : '';     //检验检疫规格型号：保税进口必填

                    $OrderList_arr['brand'] = '无';                                //品牌：没有填“无”
                    $OrderList_arr['note'] = '';                                     //备注

                    $this->create_item($dom, $OrderList, $OrderList_arr);
                }

                $this->create_item($dom, $MessageHead, $MessageHead_arr, $attribute_array);
                $this->create_item($dom, $OrderHead, $OrderHead_arr);

                $dom->save($xmlpatch);//保存文件
            }


            if ($str_1 != '' || $str_2 != '') {
                if ($str_1 != '') {
                    $str_1 .= ' 海关信息不完整' . "\n\r";
                }
                if ($str_2 != '') {
                    $str_2 .= ' 原产国或或计量单位不完整';
                }
                $msg = $str_1 . $str_2;
                die(json_encode(array('success' => 1, 'msg' => $msg)));
            }


            //压缩文件夹
            $zip = new ZipArchive();
            if ($zip->open($path_zip . '/' . $filter['start_date'] . '_' . $end_date . '_' . time() . '.zip', ZipArchive::OVERWRITE) === TRUE) {
                $is_zip = $this->addFileToZip($path, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
                $zip->close(); //关闭处理的zip文件
                if (!$is_zip) {
                    die(json_encode(array('success' => 1, 'msg' => "压缩文件失败！")));
                }

                $pathFile = 'upload/temp/export_order/' . $filter['start_date'] . '_' . $end_date . '_' . time() . '.zip';
                $FileName = $path_zip . '/' . $filter['start_date'] . '_' . $end_date . '_' . time() . '.zip';
                $result = $this->m_do_img->upload($pathFile, $FileName);
                if (!$result) {
                    die(json_encode(array('success' => 1, 'msg' => "上传失败!")));
                }
            }

            ///导出订单流水记录
            $this->db->insert_batch('is_customs_bank', $data);

            //记录每次导出记录
            $this->db->insert('export_customs_orders', array(
                "fifter_array" => serialize($filter),
                "file_name" => $filter['start_date'] . '_' . $end_date . '_' . time(),//文件名
                "file_path" => config_item('img_server_url') . '/' . $pathFile,//文件路径
                "status" => 2,
                "admin_id" => $this->_adminInfo['id'],
                "update_time" => date("Y-m-d H:i:s", time()),
                "create_time" => date("Y-m-d H:i:s", time()),
            ));

            //删除原有文件夹
            $this->delFile($path, '', true);
            $this->delFile($FileName);

            if ($this->db->trans_status() === FALSE || !$result || !$is_zip) {
                $this->db->trans_rollback();
                die(json_encode(array('success' => 1, 'msg' => "Mysql Rollback!!")));
                exit;
            } else {
                $this->db->trans_commit();
                die(json_encode(array('success' => 1, 'msg' => "后台处理中!")));
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
    function create_item($dom, $item, $data, $attribute = array())
    {
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
    function created_guid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();//window下
        } else {//非windows下
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 andup.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);//字符 "-"
            $uuid =
//                chr(123)//字符 "{"
                substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
//                .chr(125);//字符 "}"
            return $uuid;
        }
    }

    /**
     * 压缩文件夹
     * @param $path
     * @param $zip
     */
    function addFileToZip($path, $zip)
    {
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    addFileToZip($path . "/" . $filename, $zip);
                } else { //将文件加入zip对象
                    $res = $zip->addFile($path . "/" . $filename);
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
    function delFile($dir, $file_type = '', $type = false)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            //打开目录 //列出目录中的所有文件并去掉 . 和 ..
            foreach ($files as $filename) {
                if ($filename != '.' && $filename != '..') {
                    if (!is_dir($dir . '/' . $filename)) {
                        if (empty($file_type)) {
                            unlink($dir . '/' . $filename);
                        } else {
                            if (is_array($file_type)) {
                                //正则匹配指定文件
                                if (preg_match($file_type[0], $filename)) {
                                    unlink($dir . '/' . $filename);
                                }
                            } else {
                                //指定包含某些字符串的文件
                                if (false != stristr($filename, $file_type)) {
                                    unlink($dir . '/' . $filename);
                                }
                            }
                        }
                    } else {
                        delFile($dir . '/' . $filename);
                        rmdir($dir . '/' . $filename);
                    }
                }
            }
        } else {
            if (file_exists($dir)) unlink($dir);
        }

        //删除当前目录
        if ($type) {
            rmdir($dir);
        }
    }

    /**
     * 间隔3秒钟请求脚本状态
     */
    function export_status_ajax()
    {
        $data = $this->tb_export_customs_orders->getList($this->_adminInfo['id']);
        if ($data) {
            echo json_encode(array("succ" => 1, "list" => $data));
        } else {
            echo json_encode(array("succ" => 0, "list" => ""));
        }

    }

    function download_file()
    {
        $id = $_GET["download_id"];
        $data = $this->db->where("id", $id)->get("export_customs_orders")->row_array();
        if (!empty($data)) {
            $update_path = $data["file_path"];
            $file_name = str_replace(array(".", ","), array("．", "，"), $data["file_name"]);//命名特殊符号替换成全角
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
    function download_change_s()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post("id");
            $this->db->where("id", $id)->update("export_customs_orders", array("status" => 3));
        }
    }

    function del_change_s()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post("id");
            $this->db->where("id", $id)->update("export_customs_orders", array("status" => 3));
        }
    }
}
?>