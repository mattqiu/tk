<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        //$this->m_global->checkPermission('add_admin', $this->_adminInfo);
        $this->load->model('m_admin_user');
        $this->load->model('m_trade_invoice');

        // 针对单个人的特殊处理 sherry.zhang@shoptps.com antania.shi@shoptps.com timor.wang@shoptps.com
        $this->_viewData['private_adminids'] = array(295, 293, 503);

        // 发票管理tab_maps
        $this->_viewData['tab_map']['index']  = array('url' => 'admin/invoice', 'desc' => lang('invoice_manage'));
        if (isset($this->_viewData['adminInfo']['id']) && !in_array($this->_viewData['adminInfo']['id'], $this->_viewData['private_adminids'])) {
            $this->_viewData['tab_map']['add']    = array('url' => 'admin/invoice/add', 'desc' => lang('add_invoice'));
        }
        $this->_viewData['tab_map']['sffree'] = array('url' => 'admin/invoice/sffree', 'desc' => lang('invoice_sf_free'));

        // 允许查看 管理员 客服 客服经理 财务
        $this->_viewData['allowlook'] = array(0, 1, 2, 4);
        // 允许修改的权限
        $this->_viewData['allowedit']  = array(0, 2, 4);
        
        // 快递到达类型
        $this->_viewData['express_arrive_type'] = array(
            0  => '到达类型',
            1  => '次晨达',
            2  => '次日达',
            3  => '隔日达'
        );

        // 美元转人民币的汇率
        $this->_viewData['us_rate']    = $this->m_global->get_rate('CNY');
    }


    public function index() {
        $this->_viewData['title'] = lang('invoice_manage');
        $searchData           = $this->input->get() ? $this->input->get() : array();
        $searchData['page']   = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['invoice_num'] = isset($searchData['invoice_num'])?$searchData['invoice_num']:'';
        $searchData['uid']  = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['status'] = ($searchData['status'] == 'all') ? '' : $searchData['status'];
        $searchData['invoice_type']  = isset($searchData['invoice_type']) && $searchData['invoice_type'] ? $searchData['invoice_type'] : '';
        $searchData['invoice_type_2']  = isset($searchData['invoice_type_2']) && $searchData['invoice_type_2'] ? $searchData['invoice_type_2'] : '';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end']   = isset($searchData['end'])?$searchData['end']:'';

        // 状态
        $status_map = array(
            'all'   => lang('label_sel_status'),
            '0'     => lang('not_invoiced'),
            '1'     => lang('invoiced'),
            '2'     => lang('mailed'),
            '4'     => lang('admin_order_status_finish'),
            '8'     => lang('refuse'),
            '9'     => lang('admin_after_sale_status_6')
        );
        // 形式
        $invoice_type_map = array(
            '0'   =>  lang('all_oper_code'),
            '1'   => lang('invoice_paper'),
            '2'   => lang('invoice_electron')
        );

        $invoice_type_2_map = array(
            '0'   =>  lang('invoice_type_2'),
            '1'   => lang('invoice_type_common'),
            '2'   => lang('invoice_type_tax')
        );

        // 获取列表数据
        $this->load->library('pagination');
        $url = 'admin/invoice';
        add_params_to_url($url, $searchData);
        $config['base_url']   = base_url($url);
        $config['total_rows'] = $this->m_trade_invoice->getInvoiceListRows($searchData);
        $config['cur_page']   = $searchData['page'];
        $this->pagination->initialize_ucenter($config);

        $list = array();
        $list = $this->m_trade_invoice->getInvoiceList($searchData);
        foreach ($list as &$items) $items['invoice_address'] = unserialize($items['invoice_address']);

        $this->_viewData['fun']     = $this->router->fetch_method();
        $this->_viewData['status_map'] = $status_map;
        $this->_viewData['invoice_type_map'] = $invoice_type_map;
        $this->_viewData['invoice_type_2_map'] = $invoice_type_2_map;
        $this->_viewData['list']  = $list;
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/', 'invoice');
    }

    // 查看
    public function seeinvoice() {
        $this->load->model("tb_trade_orders_goods");
        $this->_viewData['title'] = lang('invoice_manage');
        $invoiceNum = trim($this->uri->segment(4));
        
        // 获取数据
        $list = $this->db->where('invoice_num', $invoiceNum)->get('trade_invoice')->row_array();

        // 地址
        (isset($list['invoice_address']) && $list['invoice_address']) && $list['addressinfo'] = unserialize($list['invoice_address']);

        // 获取开票订单
        $tempOrderList = $goods_list = array();
        $invoiceNum = (isset($list['invoice_num']) && $list['invoice_num']) ? trim($list['invoice_num']) : '';
        ($invoiceNum != '') && $tempOrderList = $this->db->where('invoice_num', $invoiceNum)->get('trade_inovice_order')->result_array();

        if ($tempOrderList) {
            foreach ($tempOrderList as $items) $list['orderlist'][$items['order_id']] = $items;
            unset($tempOrderList);

//            (isset($list['orderlist']) && $list['orderlist']) && $goods_list = $this->db->select('order_id,goods_number,goods_name')
//                                            ->where_in('order_id', array_keys($list['orderlist']))
//                                            ->get('trade_orders_goods')->result_array();
            (isset($list['orderlist']) && $list['orderlist']) && $goods_list = $this->tb_trade_orders_goods->get_list(
                "order_id,goods_number,goods_name",
                ['order_id'=>array_keys($list['orderlist'])]
            );

            if ($goods_list) foreach ($goods_list as $items) (isset($list['orderlist'][$items['order_id']]) && $list['orderlist'][$items['order_id']]) && $list['orderlist'][$items['order_id']]['goods_list'][] = $items;
            unset($goods_list);
        }

        // 开票明细
        (isset($list['invoice_details']) && $list['invoice_details']) && $list['details'] = unserialize($list['invoice_details']);

        // 发票编号
        (isset($list['invoice_code']) && $list['invoice_code']) && $list['fpcode'] = unserialize($list['invoice_code']);

        (isset($list['invoice_total_money']) && $list['invoice_total_money']) && $list['invoice_total_money'] = number_format($list['invoice_total_money'] / 100, 2);

        (isset($list['invoice_fact_money']) && $list['invoice_fact_money']) && $list['invoice_fact_money'] = number_format($list['invoice_fact_money'] / 100, 2);

        // 获取开票日志记录
        $logs = $this->db->where('invoice_num', $invoiceNum)->get('trade_invoice_log')->result_array();
        foreach ($logs as &$items) {
            $opemail = $this->db->select('email')->where('id', $items['operator_id'])->get('admin_users')->row_array();
            $items['operator'] = (isset($opemail['email']) && !empty($opemail['email'])) ? $opemail['email'] : 'system';
        }

        //开票类型
        $list['invoice_type_2_content'] = $list['invoice_type_2_content'] ? unserialize($list['invoice_type_2_content']) : '';

        $this->_viewData['list'] = $list;
        $this->_viewData['logs'] = $logs;
        //类型映射
        $this->_viewData['order_type'] = array(
                '1' => lang('choose_group'),
                '2' => lang('admin_as_upgrade_order'),
                '3' => lang('generation_group'),
                '4' => lang('retail_group'),
                '5' => lang('exchange_order')
        );
        $this->_viewData['status_select'] = array(
            Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
            Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
            Order_enum::STATUS_INIT=>lang('admin_order_status_init'),
            Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
            Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
            Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
            Order_enum::STATUS_HOLDING =>lang("admin_order_status_holding"),
            Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
            Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
            Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
            Order_enum::STATUS_DOBA_EXCEPTION => lang('admin_order_status_doba_exception')
        );
        $this->_viewData['fun']     = $this->router->fetch_method();

        parent::index('admin/', 'invoice_see');
    }

    // 修改发票信息
    public function editinvoice() {
        $this->load->model("tb_trade_orders_goods");
        $this->_viewData['title'] = lang('invoice_manage');
        $invoiceNum = trim($this->uri->segment(4));

        // 获取数据
        $list = $this->db->where('invoice_num', $invoiceNum)->get('trade_invoice')->row_array();

        // 地址
        (isset($list['invoice_address']) && $list['invoice_address']) && $list['addressinfo'] = unserialize($list['invoice_address']);

        // 获取开票订单
        $tempOrderList = $goods_list = array();
        $invoiceNum = (isset($list['invoice_num']) && $list['invoice_num']) ? trim($list['invoice_num']) : '';
        ($invoiceNum != '') && $tempOrderList = $this->db->where('invoice_num', $invoiceNum)->get('trade_inovice_order')->result_array();

        if ($tempOrderList) {
            foreach ($tempOrderList as $items) $list['orderlist'][$items['order_id']] = $items;
            unset($tempOrderList);

//            (isset($list['orderlist']) && $list['orderlist']) && $goods_list = $this->db->select('order_id,goods_number,goods_name')
//                                            ->where_in('order_id', array_keys($list['orderlist']))
//                                            ->get('trade_orders_goods')->result_array();
            (isset($list['orderlist']) && $list['orderlist']) && $goods_list = $this->tb_trade_orders_goods->get_list(
                "order_id,goods_number,goods_name",
                ['order_id'=>array_keys($list['orderlist'])]
            );

            if ($goods_list) foreach ($goods_list as $items) (isset($list['orderlist'][$items['order_id']]) && $list['orderlist'][$items['order_id']]) && $list['orderlist'][$items['order_id']]['goods_list'][] = $items;
            unset($goods_list);
        }

        // 开票明细
        (isset($list['invoice_details']) && $list['invoice_details']) && $list['details'] = unserialize($list['invoice_details']);


        // 一个订单出现多个分类商品的情况
        $orderids = array();
        if (isset($list['orderlist']) && $list['orderlist']) {
            foreach ($list['orderlist'] as $value) $orderids[] = trim($value['order_id']);
        }
        // 获取订单商品的主sku 连表查询
        $orderGoods = $details = array();
        $selectStr  = 'tg.order_id,tg.cate_id,tg.goods_price,td.currency_rate';
//        $orderids && $orderGoods = $this->db->select($selectStr)
//                                            ->from('trade_orders_goods tg')
//                                            ->join('trade_orders td', 'tg.order_id = td.order_id')
//                                            ->where_in('tg.order_id', $orderids)
//                                            ->get()->result_array();
        if($orderids)
        {
            $orders = $this->tb_trade_orders->get_list("currency_rate,order_id",["order_id"=>$orderids]);
            $orders_goods = $this->tb_trade_orders_goods->get_list("order_id,cate_id,goods_price",["order_id"=>$orderids]);
            $orders_list = [];
            foreach($orders as $k=>$v)
            {
                $orders_list[$v['order_id']] = $v;
            }
            foreach($orders_goods as $k=>$v)
            {
                $tmp = $orders_list[$v['order_id']];
                if($tmp)
                {
                    $orderGoods[] = array_merge($tmp,$v);
                }
            }
        }
        if ($orderGoods) {
            $tempPriceArray = array();
            foreach ($orderGoods as $rows) {
                $category = $this->m_trade_invoice->getFirstParents($rows['cate_id']);
                if (!isset($category['cate_id'])) continue;

                $tempmoney = round($rows['goods_price'] * 100);
                (isset($rows['currency_rate']) && $rows['currency_rate']) && $tempmoney = round($tempmoney * $rows['currency_rate']);

                if (isset($list['details'][$category['cate_id']]) && $list['details'][$category['cate_id']]) {
                    if (isset($list['details'][$category['cate_id']]['orderids'][$rows['order_id']]) && $list['details'][$category['cate_id']]['orderids'][$rows['order_id']]) {
                        $tempPriceArray[$category['cate_id']] += $tempmoney;
                        $list['details'][$category['cate_id']]['orderids'][$rows['order_id']] = array(
                            'order_id'  => $rows['order_id'],
                            'price'     => number_format($tempPriceArray[$category['cate_id']] / 100, 2, '.', '')
                        );
                    } else {
                        $tempPriceArray[$category['cate_id']] = $tempmoney;
                        $list['details'][$category['cate_id']]['orderids'][$rows['order_id']] = array(
                            'order_id'  => $rows['order_id'],
                            'price'     => number_format($tempmoney / 100, 2, '.', '')
                        );
                    }
                    
                }
            }
            unset($orderGoods, $tempPriceArray);
        }

        // 发票编号
        (isset($list['invoice_code']) && $list['invoice_code']) && $list['fpcode'] = unserialize($list['invoice_code']);

        (isset($list['invoice_total_money']) && $list['invoice_total_money']) && $list['invoice_total_money'] = number_format($list['invoice_total_money'] / 100, 2, '.', '');

        (isset($list['invoice_fact_money']) && $list['invoice_fact_money']) && $list['invoice_fact_money'] = number_format($list['invoice_fact_money'] / 100, 2, '.', '');

        // 获取开票日志记录
        $logs = $this->db->where('invoice_num', $invoiceNum)->get('trade_invoice_log')->result_array();
        foreach ($logs as &$items) {
            $opemail = $this->db->select('email')->where('id', $items['operator_id'])->get('admin_users')->row_array();
            $items['operator'] = (isset($opemail['email']) && !empty($opemail['email'])) ? $opemail['email'] : 'system';
        }

        $list['invoice_type_2_content'] = $list['invoice_type_2_content'] ? unserialize($list['invoice_type_2_content']) : '';
        $this->_viewData['list'] = $list;
        $this->_viewData['logs'] = $logs;
        //类型映射
        $this->_viewData['order_type'] = array(
                '1' => lang('choose_group'),
                '2' => lang('admin_as_upgrade_order'),
                '3' => lang('generation_group'),
                '4' => lang('retail_group'),
                '5' => lang('exchange_order')
        );
        $this->_viewData['status_select'] = array(
            Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
            Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
            Order_enum::STATUS_INIT=>lang('admin_order_status_init'),
            Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
            Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
            Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
            Order_enum::STATUS_HOLDING =>lang("admin_order_status_holding"),
            Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
            Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
            Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
            Order_enum::STATUS_DOBA_EXCEPTION => lang('admin_order_status_doba_exception')
        );
        $this->_viewData['fun']     = $this->router->fetch_method();
        parent::index('admin/', 'invoice_edit');
    }

    // 新增发票
    public function add() {
        $this->_viewData['title'] = lang('add_invoice');

        $invoiceNum = 'KP'.date('YmdHis').mt_rand(10, 99);
        
        $this->_viewData['invoicenum'] = $invoiceNum;
        $this->_viewData['fun']        = $this->router->fetch_method();
        
        parent::index('admin/', 'invoice_add');
    }


    // 保存添加的数据
    public function ajaxsave() {
        if ($this->input->is_ajax_request()) {
            $data        = $this->input->post();
            $time        = date('Y-m-d H:i:s');
            $invoiceNum  = trim($data['invoice_number']);
            $invoiceStat = 0;
            $ucid        = intval($data['uid']);
            $adminId     = intval($this->_adminInfo['id']);

            // 检查开票单号是否存在
            $total = $this->db->from('trade_invoice')->where('invoice_num', $invoiceNum)->count_all_results();
            if ($total) exit(json_encode(array('success' => 0, 'msg' => '开票单号已经存在,请勿重复提交')));

            $invoiceInsertData = $orderList = $detailList = array();

            // 处理订单列表
            $tempCheckedOrederIds = array();
            if (isset($data['orderlist']) && $data['orderlist']) {
                foreach ($data['orderlist'] as $odinfo) {
                    $odinfo = trim($odinfo);
                    $orders = @explode('|', $odinfo);
                    if (empty($orders)) continue;
                    $orderid   = trim($orders[0]);
                    $mark      = (isset($data['orderlistchecked']) && in_array($orderid, $data['orderlistchecked'])) ? 1 : 0;
                    ($mark) && $tempCheckedOrederIds[] = $orderid;
                    $orderList[] = array(
                        'invoice_num' => $invoiceNum,
                        'order_id'    => $orderid,
                        'order_type'  => intval($orders[1]),
                        'money'       => round($orders[2] * 100),
                        'express_free'=> $orders[3],
                        'status'      => intval($orders[4]),
                        'mark'        => $mark,
                        'cateids'     => trim($orders[5])
                    );
                }
                unset($data['orderlist']);
            }

            if (empty($tempCheckedOrederIds)) exit(json_encode(array('success' => 0, 'msg' => '没有可开票的订单信息')));

            // 检查当前的订单是否开过票
            $hasOrderids = $hastempOrderids = array();
           $tempCheckedOrederIds && $hasOrderids = $this->db->select('order_id')
                    ->where_in('order_id', $tempCheckedOrederIds)
                    ->where('mark', 1)
                    ->get('trade_inovice_order')->result_array();
            foreach ($hasOrderids as $row) $hastempOrderids[] = $row['order_id'];
            if (!empty($hasOrderids)) exit(json_encode(array('success' => 0, 'msg' => '无法提交订单'.implode(',', $hastempOrderids).'已经被开过票')));
            unset($hasOrderids, $hastempOrderids, $tempCheckedOrederIds);

            // 处理开票明细
            if (isset($data['detailinfo']) && $data['detailinfo']) {
                foreach ($data['detailinfo'] as $dtl) {
                    $dtl     = trim($dtl);
                    $dtlist  = @explode(':@', $dtl);
                    if (empty($dtlist)) continue;
                    $catid   = intval($dtlist[2]);
                    $dmoney  = $dtlist[1];
                    //if ($dmoney <= 0.00) continue;
                    $detailList[$catid] = array(
                        'cate_id'   => $catid,
                        'cate_name' => trim($dtlist[0]),
                        'money'     => $dmoney,
                        'order_id'  => trim($dtlist[3])
                    );
                }
                unset($data['orderlist']);
            }

            // 1 纸质发票; 2 电子发票
            $invoiceEmail = $invoiceAddress = $invoiceRate = $invoiceRecipient = $invoiceZhfree = $invoiceUsfree = $express_arrive = $jsinvoicenum = '';
            if ($data['invoices'] == 1) {
                $data['country'] = empty($data['country']) ? '' : $data['country'];
                $data['provice'] = empty($data['provice']) ? '' : $data['provice'];
                $data['city']    = empty($data['city']) ? '' : $data['city'];
                $data['area']    = empty($data['area']) ? '' : $data['area'];
                $data['address'] = empty($data['address']) ? '' : $data['address'];

                $invoiceAddress = (empty($data['country']) && empty($data['provice']) && empty($data['city']) && empty($data['area']) && empty($data['address'])) ? '' : serialize(array(
                    'country'   => $data['country'],
                    'provice'   => $data['provice'],
                    'city'      => $data['city'],
                    'area'      => $data['area'],
                    'address'   => $data['address']
                ));

                $invoiceRate      = $this->m_global->get_rate('CNY');
                $invoiceRecipient = trim($data['receive_people']);
                $invoiceZhfree    = empty($data['express_free']) ? 0.00 : trim($data['express_free']);
                $invoiceUsfree    = empty($data['express_free_us']) ? 0.00 : trim($data['express_free_us']);
                $express_arrive   = empty($data['express_arrive']) ? 2 : intval($data['express_arrive']);
                $jsinvoicenum     = trim($data['js_invoicenum']);

                if ($jsinvoicenum) {
                    $invoiceZhfree  = $invoiceUsfree = 0.00;
                    $express_arrive = 2;
                }
            } else if ($data['invoices'] == 2) {
                $invoiceEmail   = trim($data['invoice_send_email']);
                $invoiceRate    = $invoiceZhfree = $invoiceUsfree = 0.00;
                $express_arrive = 0;
            }

            // 检查当前会员是否有足够的金额扣款
            if ($data['invoices'] == 1 && empty($data['together']) && $invoiceUsfree > 0) {
                $userTomoney = $this->db->from('users')
                                        ->where('id', $ucid)
                                        ->where('amount >=', $invoiceUsfree)
                                        ->count_all_results();
                if (empty($userTomoney)) {
                    exit(json_encode(array('success' => 0, 'msg' => '当前会员余额不足无法进行扣款,请联系会员充值再开发票')));
                }   
            }

            $invoice_total_money = round($data['invoice_total_money'] * 100);
            $invoice_fact_money  = round($data['invoice_fact_total_money'] * 100);
            $invoiceInsertData = array(
                'invoice_num'         => $invoiceNum,
                'uid'                 => $ucid,
                'invoice_start_time'  => trim($data['start']),
                'invoice_end_time'    => trim($data['end']),
                'invoice_total_money' => $invoice_total_money,
                'invoice_fact_money'  => $invoice_fact_money,
                'invoice_head'        => trim($data['invoice_top']),
                'invoice_type'        => intval($data['invoices']),
                'mobile'              => $data['mobile'],
                'backup_num'          => trim($data['invoice_spare_num']),
                'remark'              => trim($data['remark']),
                'status'              => $invoiceStat,
                'created_at'          => $time,
                'update_at'           => $time,
                'invoice_details'     => serialize($detailList),
                'email'               => $invoiceEmail,
                'invoice_address'     => $invoiceAddress,
                'rate'                => $invoiceRate,
                'recipient'           => $invoiceRecipient,
                'zh_express_free'     => $invoiceZhfree,
                'us_express_free'     => $invoiceUsfree,
                'express_arrive'      => $express_arrive,
                'js_invoicenum'       => $jsinvoicenum,
                'adminid'             => $adminId
            );

            //发票类型处理 普通发票 or 增值税发票
            $invoice_type_2_content = array(
                'invoice_county_name_type'      => trim($data['invoice_county_name_type']),
                'invoice_identify_type'         => trim($data['invoice_identify_type']),
                'invoice_bank_name_type'        => trim($data['invoice_bank_name_type']),
                'invoice_bank_count_type'       => trim($data['invoice_bank_count_type']),
                'invoice_company_address_type'  => trim($data['invoice_company_address_type']),
                'invoice_company_phone_type'    => trim($data['invoice_company_phone_type']),
            );
            $invoiceInsertData['invoice_type_2']         = $data['invoice_type_2'];
            $invoiceInsertData['invoice_head']           = $data['invoice_type_2']==1 ? trim($data['invoice_top']) : '';
            $invoiceInsertData['invoice_type_2_content'] = $data['invoice_type_2']==2 ? serialize($invoice_type_2_content) : '';

            //开票抬头类型处理
            if($data['invoice_type_2']==1 && $data['invoice_title_type']==1){

                //普通发票且为公司类型
                $invoice_taxpayer_id_number = trim($data['invoice_taxpayer_id_number']);

                if(empty($invoice_taxpayer_id_number)) exit(json_encode(array('success' => 0, 'msg' => lang('invoice_taxpayer_id_number_error'))));

                $invoiceInsertData['invoice_taxpayer_id_number'] = htmlspecialchars($invoice_taxpayer_id_number);
            }else{
                //其他设为0
                $invoiceInsertData['invoice_taxpayer_id_number'] = 0;
            }


            unset($detailList);

            // 开启事务
            $this->db->trans_strict(TRUE);
            $this->db->trans_begin();

            // 添加发票记录
            $return = $this->db->insert('trade_invoice', $invoiceInsertData);

            if (!$return) exit(json_encode(array('success' => 0, 'msg' => lang('result_false'))));

            // 添加发票订单流水
            $this->db->insert_batch('trade_inovice_order', $orderList);
            unset($orderList);

            // 添加日志记录
            $this->db->insert('trade_invoice_log', array(
                'operator_id'   => $adminId,
                'invoice_num'   => $invoiceNum,
                'status'        => $invoiceStat,
                'remark'        => '创建开票申请单',
                'created_at'    => $time
            ));

            // 如果是与其他发票一起寄送则不需要计算金额
            $together  = empty($data['together']) ? 0 : intval($data['together']);

            // 记录会员资金变更日志
            if (intval($data['invoices']) == 1 && empty($together) && $ucid && !empty($invoiceUsfree)) {
                // 增减会员资金变动日志
                $code = $this->m_trade_invoice->ucTradeMoneyRecord(array(
                    'ucid'    => $ucid,
                    'money'   => $invoiceUsfree,
                    'desc'    => sprintf(lang('invoice_paper_remark'), $ucid).'$'.$invoiceUsfree,
                    'adminid' => $adminId
                ), '-');
            }
            unset($data, $invoiceInsertData);
            if ($this->db->trans_status() === FALSE) {
              $result['success']  = 0;
              $result['msg']      = 'System Error!';
              $this->db->trans_rollback();
            } else {
              $result['success']  = 1;
              $result['msg']      = lang('submit_success');
              $this->db->trans_complete();
            }
            
            die(json_encode($result));
        }
    }

    // 保存修改的数据
    public function editsave() {
        if ($this->input->is_ajax_request()) {
            $data       = $this->input->post();
            $time       = date('Y-m-d H:i:s');
            $adminId    = intval($this->_adminInfo['id']);
            $status     = (isset($data['active']) && $data['active']) ? intval($data['active']) : 0;
            $invoiceNum = isset($data['invoice_num']) && $data['invoice_num'] ? trim($data['invoice_num']) : '';
            $submitype  = isset($data['submitype']) && $data['submitype'] ? trim($data['submitype']) : '';

            if (empty($invoiceNum)) exit(json_encode(array('success' => 0, 'msg' => lang('info_unvalid_request'))));

            // 驳回修改重新提交的发票
            if ($submitype != '' && $submitype == 'edit') {
                // 处理订单列表
                $tempCheckedOrederIds = array();
                foreach ($data['orderlist'] as $odinfo) {
                    $odinfo = trim($odinfo);
                    $orders = @explode('|', $odinfo);
                    if (empty($orders)) continue;
                    $orderid   = trim($orders[0]);
                    $mark      = (isset($data['orderlistchecked']) && in_array($orderid, $data['orderlistchecked'])) ? 1 : 0;
                    ($mark) && $tempCheckedOrederIds[] = $orderid;

                    $orderList[] = array(
                        'invoice_num' => $invoiceNum,
                        'order_id'    => $orderid,
                        'order_type'  => intval($orders[1]),
                        'money'       => round($orders[2] * 100),
                        'express_free'=> $orders[3],
                        'status'      => intval($orders[4]),
                        'mark'        => $mark,
                        'cateids'     => trim($orders[5])
                    );
                }
                unset($data['orderlist']);

                if (empty($tempCheckedOrederIds)) exit(json_encode(array('success' => 0, 'msg' => '没有可开票的订单信息')));

                // 检查当前的订单是否开过票
                $hasOrderids = $hastempOrderids = array();
                $hasOrderids = $this->db->select('order_id')
                        ->where_in('order_id', $tempCheckedOrederIds)
                        ->where('invoice_num <>', $invoiceNum)
                        ->where('mark', 1)
                        ->get('trade_inovice_order')->result_array();
                foreach ($hasOrderids as $row) $hastempOrderids[] = $row['order_id'];
                if (!empty($hasOrderids)) exit(json_encode(array('success' => 0, 'msg' => '无法提交订单'.implode(',', $hastempOrderids).'已经被开过票')));
                unset($hasOrderids, $hastempOrderids, $tempCheckedOrederIds);

                // 处理开票明细
                foreach ($data['detailinfo'] as $dtl) {
                    $dtl     = trim($dtl);
                    $dtlist  = @explode(':@', $dtl);
                    if (empty($dtlist)) continue;
                    $catid   = intval($dtlist[2]);
                    $dmoney  = $dtlist[1];
                    //if ($dmoney <= 0.00) continue;
                    $detailList[$catid] = array(
                        'cate_id'   => $catid,
                        'cate_name' => trim($dtlist[0]),
                        'money'     => $dmoney,
                        'order_id'  => trim($dtlist[3])
                    );
                }
                unset($data['detailinfo']);

                // 1 纸质发票; 2 电子发票
                if ($data['invoicetype'] == 1) {
                    // 如果勾选与其它发票一起寄送
                    if (empty($data['together']) || empty($data['js_invoicenum'])) {
                        $data['country'] = empty($data['country']) ? '' : $data['country'];
                        $data['provice'] = empty($data['provice']) ? '' : $data['provice'];
                        $data['city']    = empty($data['city']) ? '' : $data['city'];
                        $data['area']    = empty($data['area']) ? '' : $data['area'];
                        $data['address'] = empty($data['address']) ? '' : $data['address'];

                        if (!empty($data['country']) || !empty($data['provice']) || !empty($data['city'])) {
                            $invoiceUpdateData['invoice_address'] = serialize(array(
                                'country'   => $data['country'],
                                'provice'   => $data['provice'],
                                'city'      => $data['city'],
                                'area'      => $data['area'],
                                'address'   => $data['address']
                            ));
                        }

                        if (!empty($data['express_free'])) $invoiceUpdateData['zh_express_free'] = trim($data['express_free']);
                        if (!empty($data['express_free_us'])) $invoiceUpdateData['us_express_free'] =  trim($data['express_free_us']);
                        if (!empty($data['express_arrive'])) $invoiceUpdateData['express_arrive']  = intval($data['express_arrive']);
                        $invoiceUpdateData['rate']            = $this->m_global->get_rate('CNY');  
                    }
                    
                    $invoiceUpdateData['js_invoicenum'] = empty($data['js_invoicenum']) ? '' : trim($data['js_invoicenum']);
                    $invoiceUpdateData['recipient']     = trim($data['receive_people']);
                } else if ($data['invoicetype'] == 2) {
                    $invoiceUpdateData['email'] = trim($data['invoice_send_email']);
                }

                // 扣去会员运费金额
                $ucid = empty($data['uid']) ? 0 : intval($data['uid']);
                // 如果该发票已经扣款无需重复扣款
                $freeIshas = array();
                $freeIshas = $this->db->select('us_express_free')
                                      ->where('invoice_num', $invoiceNum)
                                      ->where('uid', $ucid)
                                      ->where('status', 8)
                                      ->get('trade_invoice')->row_array();
                $tempFree  = empty($freeIshas['us_express_free']) ? 0 : $freeIshas['us_express_free'];
                unset($freeIshas);

                // 检查当前会员是否有足够的金额扣款 如果$tempFree > 0 说明已经扣过款 无需重复扣款
                if ($data['invoicetype'] == 1 && empty($data['together']) && !empty($data['express_free_us']) && $data['express_free_us'] > 0 && $tempFree <= 0) {
                    $userTomoney = $this->db->from('users')
                                        ->where('id', $ucid)
                                        ->where('amount >=', $data['express_free_us'])
                                        ->count_all_results();
                    if (empty($userTomoney)) {
                        exit(json_encode(array('success' => 0, 'msg' => '当前会员余额不足无法进行扣款,请联系会员充值再开发票')));
                    }
                }

                // 更新数据库的数据
                $invoice_total_money = round($data['invoice_total_money'] * 100);
                $invoice_fact_money  = round($data['invoice_fact_total_money'] * 100);

                $invoiceUpdateData['invoice_start_time']  = trim($data['start']);
                $invoiceUpdateData['invoice_end_time']    = trim($data['end']);
                $invoiceUpdateData['invoice_total_money'] = $invoice_total_money;
                $invoiceUpdateData['invoice_fact_money']  = $invoice_fact_money;
                $invoiceUpdateData['invoice_head']        = trim($data['invoice_top']);
                $invoiceUpdateData['mobile']              = $data['mobile'];
                $invoiceUpdateData['backup_num']          = trim($data['invoice_spare_num']);
                $invoiceUpdateData['remark']              = trim($data['remark']);
                $invoiceUpdateData['status']              = 0;
                $invoiceUpdateData['update_at']           = $time;
                $invoiceUpdateData['invoice_details']     = serialize($detailList);

                //发票类型处理 普通发票 or 增值税发票
                $invoice_type_2_content = array(
                    'invoice_county_name_type'      => trim($data['invoice_county_name_type']),
                    'invoice_identify_type'         => trim($data['invoice_identify_type']),
                    'invoice_bank_name_type'        => trim($data['invoice_bank_name_type']),
                    'invoice_bank_count_type'       => trim($data['invoice_bank_count_type']),
                    'invoice_company_address_type'  => trim($data['invoice_company_address_type']),
                    'invoice_company_phone_type'    => trim($data['invoice_company_phone_type']),
                );
                $invoiceUpdateData['invoice_type_2']         = $data['invoice_type_2'];
                $invoiceUpdateData['invoice_head']           = $data['invoice_type_2']==1 ? trim($data['invoice_top']) : '';
                $invoiceUpdateData['invoice_type_2_content'] = $data['invoice_type_2']==2 ? serialize($invoice_type_2_content) : '';

                //开票抬头类型处理
                if($data['invoice_type_2']==1 && $data['invoice_title_type_value']==1){

                    //普通发票且为公司类型
                    $invoice_taxpayer_id_number = trim($data['invoice_taxpayer_id_number']);

                    if(empty($invoice_taxpayer_id_number)) exit(json_encode(array('success' => 0, 'msg' => lang('invoice_taxpayer_id_number_error'))));

                    $invoiceUpdateData['invoice_taxpayer_id_number'] = htmlspecialchars($invoice_taxpayer_id_number);
                }else{
                    //其他设为0
                    $invoiceUpdateData['invoice_taxpayer_id_number'] = 0;
                }

                unset($detailList);

                // 开启事务
                $this->db->trans_strict(TRUE);
                $this->db->trans_begin();

                $res = $this->db->update('trade_invoice', $invoiceUpdateData, array('invoice_num' => $invoiceNum));

                if (!$res) exit(json_encode(array('success' => 0, 'msg' => lang('result_false'))));

                // 删掉数据重新添加
                $this->db->where('invoice_num', $invoiceNum)->delete('trade_inovice_order');

                // 批量添加订单数据记录
                $this->db->insert_batch('trade_inovice_order', $orderList);
                unset($orderList);

                if ($data['invoicetype'] == 1 && empty($data['together']) && $ucid && !empty($invoiceUpdateData['us_express_free']) && $tempFree <= 0) {
                    // 增减会员资金变动日志
                    $code = $this->m_trade_invoice->ucTradeMoneyRecord(array(
                        'ucid'    => $ucid,
                        'money'   => $invoiceUpdateData['us_express_free'],
                        'desc'    => sprintf(lang('invoice_paper_remark'), $ucid).'$'.$invoiceUpdateData['us_express_free'],
                        'adminid' => $adminId
                    ), '-');
                }

                // 添加日志记录
               $res && $this->db->insert('trade_invoice_log', array(
                    'operator_id'   => $adminId,
                    'invoice_num'   => $invoiceNum,
                    'status'        => 0,
                    'remark'        => '重新提交被驳回的发票',
                    'created_at'    => $time
                ));

               if ($this->db->trans_status() === FALSE) {
                  $result['success']  = 0;
                  $result['msg']      = 'System Error!';
                  $this->db->trans_rollback();
                } else {
                  $result['success']  = 1;
                  $result['msg']      = lang('submit_success');
                  $this->db->trans_complete();
                }

            } else {

                // 过滤发票编号为空的情况
                $invoiceCodeArray = array();
                foreach ($data['invoice_code'] as $invoiceCode) empty($invoiceCode) || $invoiceCodeArray[] = $invoiceCode;

                $updateInsert = array();
                empty($invoiceCodeArray) || $invoiceCodeArray = array_unique($invoiceCodeArray);
                $updateInsert['invoice_code'] = empty($invoiceCodeArray) ? '' : serialize($invoiceCodeArray);
                $updateInsert['express_num']  = (isset($data['courier_number']) && $data['courier_number']) ? trim($data['courier_number']) : NULL;
                $updateInsert['update_at']    = $time;

                // 判断快递单号是否重复
                if($data['active'] != 2)
                {
                    $expnum = 0;
                    (isset($updateInsert['express_num']) && $updateInsert['express_num']) && $expnum = $this->db->from('trade_invoice')->where('express_num', $updateInsert['express_num'])->where('status <>', 9)->count_all_results();
                    if ($expnum) exit(json_encode(array('success' => 0, 'msg' => '快递单号已经存在')));

                }else{
                    //已邮寄 检查
                    $invoiceInfo = $this->db->from('trade_invoice')->where('invoice_num',$invoiceNum)->get()->row_array();
                    if($invoiceInfo && $invoiceInfo['invoice_type']==1 && !empty($invoiceInfo['js_invoicenum']))
                    {//一起寄送处理

                        $originInfo = $this->db->from('trade_invoice')->where('invoice_num',$invoiceInfo['js_invoicenum'])->get()->row_array();
                        if($originInfo)
                        {
                            if($originInfo['express_num'] == '') exit(json_encode(array('success' => 0, 'msg' => '快递单号不能为空')));

                            if($originInfo['express_num'] != $updateInsert['express_num']) exit(json_encode(array('success' => 0, 'msg' => '快递单号不正确')));

                        }else{

                            exit(json_encode(array('success' => 0, 'msg' => '第一个开票单号不存在')));

                        }

                    }else{

                        $expnum = 0;
                        (isset($updateInsert['express_num']) && $updateInsert['express_num']) && $expnum = $this->db->from('trade_invoice')->where('express_num', $updateInsert['express_num'])->where('status <>', 9)->count_all_results();
                        if ($expnum) exit(json_encode(array('success' => 0, 'msg' => '快递单号已经存在')));

                    }

                }

                $status && $updateInsert['status']  = intval($data['active']);

                $res = $this->db->update('trade_invoice', $updateInsert, array('invoice_num' => $invoiceNum));

                if (!$res) exit(json_encode(array('success' => 0, 'msg' => lang('result_false'))));
                
                $msg = '创建开票申请单';
                switch($status) {
                    case 0 :
                      $msg = '创建开票申请单';
                      break;
                    case 1 :
                      $msg = lang('invoiced');
                      break;
                    case 2 :
                      $msg = lang('mailed');
                      break;
                }
                
               // 添加日志记录
               $res && $this->db->insert('trade_invoice_log', array(
                    'operator_id'   => $adminId,
                    'invoice_num'   => $invoiceNum,
                    'status'        => $data['active'],
                    'remark'        => $msg,
                    'created_at'    => $time
                ));
               $result['success']  = 1;
               $result['msg']      = lang('submit_success');
            }
            exit(json_encode($result));
        }
    }


    // 检查会员id是否存在
    public function check_uid_ajax() {
        if ($this->input->is_ajax_request()) {
            $uid = $this->input->get('uid');
            if (!$uid) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            $total = $this->db->from('users')->where('id', $uid)->where('status', 1)->count_all_results();
            if ($total) {
                $result['success'] = 1;
                $result['msg']     = lang('submit_success');
            } else {
                $result['success'] = 0;
                $result['msg']     = lang('pls_t_correct_ID');
            }
            exit(json_encode($result));
        }
    }

    // 获取会员的订单
    public function get_uc_orderinfo_ajax() {
        if ($this->input->is_ajax_request()) {
            $uid   = $this->input->post('uid');
            $start = $this->input->post('start');
            $end   = $this->input->post('end');

            if (!$uid || !$end || !$start) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            // 订单可开票的状态有:正在发货中，等待收货，等待评价，已完成。
            $start      = $start.' 00:00:00';
            $end        = $end.' 23:59:59';
            $ordersTemp = $orders = array();
//            $ordersTemp = $this->db->select('order_id,order_type,goods_amount_usd,currency_rate,deliver_fee_usd,status')
//                     ->where('customer_id', $uid)
//                     ->where('created_at >=', $start)
//                     ->where('created_at <=', $end)
//                     ->where_in('status', array(Order_enum::STATUS_INIT, Order_enum::STATUS_SHIPPED, Order_enum::STATUS_EVALUATION, Order_enum::STATUS_COMPLETE))
//                     ->get('trade_orders')->result_array();

            $this->load->model("tb_trade_orders");
            $ordersTemp = $this->tb_trade_orders->get_list_auto([
                "select"=>"order_id,order_type,goods_amount_usd,currency_rate,deliver_fee_usd,status",
                "where"=>[
                    'customer_id'=>$uid,
                    'created_at >='=>$start,
                    'created_at <='=>$end,
                    'status'=> array(Order_enum::STATUS_INIT, Order_enum::STATUS_SHIPPED, Order_enum::STATUS_EVALUATION, Order_enum::STATUS_COMPLETE)
                ]
            ]);

            if (empty($ordersTemp)) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('no_refund_data')
                )));
            }

            // 美元转人民币的汇率
            $gTrate = $this->m_global->get_rate('CNY');

            // 获取订单主sku
            $details    = $gdrate = array();
            $totalMoney = 0;
            foreach($ordersTemp as &$items) {

              // 订单类型
              switch($items['order_type']) {
                  case '1' :
                    $items['order_type_map'] = lang('choose_group');
                    break;
                  case '2' :
                    $items['order_type_map'] = lang('admin_as_upgrade_order');
                    break;
                  case '3' :
                    $items['order_type_map'] = lang('generation_group');
                    break;
                  case '4' :
                    $items['order_type_map'] = lang('retail_group');
                    break;
                  case '5' :
                    $items['order_type_map'] = lang('exchange_order');
                    break;

                  default :
                      $items['order_type_map'] = lang('retail_group');
                    break;
              }

              // 订单金额处理 /* 为防止浮点误差，所有商品价格转为以分为单位的整型量计算 */
              if($items['currency_rate']==1)
              {
                  $money                = round($items['goods_amount_usd'] * $gTrate);

              }else{

                  $money                = round($items['goods_amount_usd'] * $items['currency_rate']);

              }

              // 计算总金额
              $totalMoney          += $money;

              // 转浮点数
              $items['order_money'] = number_format($money / 100, 2, '.', '');

              // 处理运费
              $items['free']        = number_format($items['deliver_fee_usd'] / 100, 2);

              // 订单状态处理
              switch($items['status']) {
                  case Order_enum::STATUS_INIT :
                    $items['status_map'] = lang('admin_order_status_init');
                    break;
                  case Order_enum::STATUS_CHECKOUT :
                    $items['status_map'] = lang('admin_order_status_checkout');
                    break;
                  case Order_enum::STATUS_SHIPPING :
                    $items['status_map'] = lang('admin_order_status_paied');
                    break;
                  case Order_enum::STATUS_SHIPPED :
                    $items['status_map'] = lang('admin_order_status_delivered');
                    break;
                  case Order_enum::STATUS_EVALUATION :
                    $items['status_map'] = lang('admin_order_status_arrival');
                    break;
                  case Order_enum::STATUS_COMPLETE :
                    $items['status_map'] = lang('admin_order_status_finish');
                    break;
                  case Order_enum::STATUS_HOLDING :
                    $items['status_map'] = lang('admin_order_status_holding');
                    break;
                  case Order_enum::STATUS_RETURNING :
                    $items['status_map'] = lang('admin_order_status_returning');
                    break;
                  case Order_enum::STATUS_RETURN :
                    $items['status_map'] = lang('admin_order_status_refund');
                    break;
                  case Order_enum::STATUS_CANCEL :
                    $items['status_map'] = lang('admin_order_status_cancel');
                    break;
                  case Order_enum::STATUS_DOBA_EXCEPTION :
                    $items['status_map'] = lang('admin_order_status_doba_exception');
                    break;
              }

              $orders[$items['order_id']] = $items;
            }
            unset($ordersTemp);

            // 获取订单商品的主sku 连表查询
            $orderGoods = $goodsList = array();
//            $orders && array_keys($orders) && $orderGoods = $this->db->select('order_id,cate_id,goods_price,goods_number,goods_name')
//                                                ->where_in('order_id', array_keys($orders))
//                                                ->get('trade_orders_goods')->result_array();
            ($orders && array_keys($orders)) && $orderGoods = $this->tb_trade_orders_goods->get_list(
                "order_id,cate_id,goods_price,goods_number,goods_name",
                ['order_id'=>array_keys($orders)]
            );

            if (!empty($orderGoods)) {
                $orderCateIds = array();

                // 构建开票明细
                foreach ($orderGoods as $rows) {
                    $category = $this->m_trade_invoice->getFirstParents($rows['cate_id']);
                    if (!isset($category['cate_name']) && !isset($category['cate_id'])) continue;

                    $cate_name = (isset($category['cate_name']) && $category['cate_name']) ? trim($category['cate_name']) : '';

                    $tempmoney = round($rows['goods_price'] * 100 * $rows['goods_number']);

                    if(isset($orders[$rows['order_id']]['currency_rate'])){

                        $orders[$rows['order_id']]['currency_rate'] ==1 ? $tempmoney = round($tempmoney * $gTrate) : $tempmoney = round($tempmoney * $orders[$rows['order_id']]['currency_rate']);

                    }else{

                        $tempmoney = round($tempmoney * $gTrate);

                    }

                    if ($cate_name == '' || $tempmoney == '') continue;

                    $goodsList[$rows['order_id']][] = array(
                        'name'      => $rows['goods_name'],
                        'number'    => $rows['goods_number']
                    );

                    $orderCateIds[$rows['order_id']] = (isset($orderCateIds[$rows['order_id']]) && $rows['order_id'] && $category['cate_id'] && $orderCateIds[$rows['order_id']] != $category['cate_id']) 
                    ? $orderCateIds[$rows['order_id']].','.intval($category['cate_id']) 
                    : intval($category['cate_id']);

                    if (isset($details[$category['cate_id']])) {
                        $details[$category['cate_id']]['money']     += $tempmoney;
                        $details[$category['cate_id']]['attach']    .= ','.trim($rows['order_id']); 
                    } else {
                        $details[$category['cate_id']]['cate_name'] = $cate_name;
                        $details[$category['cate_id']]['money']     = $tempmoney;
                        $details[$category['cate_id']]['attach']    = trim($rows['order_id']);
                    }

                    if (isset($details[$category['cate_id']]['orderids'][$rows['order_id']])) {
                        $details[$category['cate_id']]['orderids'][$rows['order_id']]['order_id'] = $rows['order_id'];
                        $details[$category['cate_id']]['orderids'][$rows['order_id']]['gprice']  += $tempmoney;
                        $details[$category['cate_id']]['orderids'][$rows['order_id']]['price']    = number_format($details[$category['cate_id']]['orderids'][$rows['order_id']]['gprice'] / 100, 2, '.', '');
                        
                    } else {
                        $details[$category['cate_id']]['orderids'][$rows['order_id']]['order_id'] = $rows['order_id'];
                        $details[$category['cate_id']]['orderids'][$rows['order_id']]['gprice']   = $tempmoney;
                        $details[$category['cate_id']]['orderids'][$rows['order_id']]['price']    = number_format($tempmoney/ 100, 2, '.', '');
                    }
                }
                unset($orderGoods);

                foreach ($details as &$dt) $dt['money'] = number_format($dt['money'] / 100, 2, '.', '');

                // 获取订单所属分类ID
                foreach ($orders as &$item) {
                    (isset($orderCateIds[$item['order_id']]) && $orderCateIds[$item['order_id']]) && $item['cateids'] = trim($orderCateIds[$item['order_id']]);
                    (isset($goodsList[$item['order_id']]) && $goodsList[$item['order_id']]) && $item['goods_list'] = $goodsList[$item['order_id']];
                }
                unset($goodsList);
            }

            $result['success']     = 1;
            $result['orderinfo']   = $orders;
            $result['total_money'] = $totalMoney ? number_format($totalMoney / 100, 2, '.', '') : 0.00;
            $result['detailist']   = $details;
            unset($orders, $details);
            exit(json_encode($result));
        }
    }

    // 计算不同地区的快递费
    public function sf_express_free_ajax() {
        if ($this->input->is_ajax_request()) {
            $areaId    = intval($this->input->get('areaid'));
            $cityId    = intval($this->input->get('cityid'));
            $proviceId = intval($this->input->get('proviceid'));
            $type      = intval($this->input->get('type'));
            $type      = (empty($type) || $type <= 0) ? 2 : $type;

            if (empty($areaId) && empty($cityId) && empty($proviceId)) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            $free = array();
            $zh_fast_free = $zh_second_free = $zh_slow_free = $us_fast_free = $us_second_free = $us_slow_free = 0;
            if ($areaId) {
                $free = $this->db->where('area_id', $areaId)->get('trade_invoice_sf_free')->row_array();
                // 获取区域的运费
                $zh_fast_free   = (isset($free['area_fast_free']) && $free['area_fast_free']) ? $free['area_fast_free'] : 0;
                $zh_second_free = (isset($free['area_second_free']) && $free['area_second_free']) ? $free['area_second_free'] : 0;
                $zh_slow_free   = (isset($free['area_slow_free']) && $free['area_slow_free']) ? $free['area_slow_free'] : 0;
            }

            if ((empty($zh_fast_free) || empty($zh_second_free) || empty($zh_slow_free)) && $cityId) {
                $free = $this->db->where('city_id', $cityId)->where('area_id', 0)->get('trade_invoice_sf_free')->row_array();
                empty($zh_fast_free) && $zh_fast_free = (isset($free['city_fast_free']) && $free['city_fast_free']) ? $free['city_fast_free'] : 0;
                empty($zh_second_free) && $zh_second_free = (isset($free['city_second_free']) && $free['city_second_free']) ? $free['city_second_free'] : 0;
                empty($zh_slow_free) && $zh_slow_free = (isset($free['city_slow_free']) && $free['city_slow_free']) ? $free['city_slow_free'] : 0;
            }

            if ((empty($zh_fast_free) || empty($zh_second_free) || empty($zh_slow_free)) && $proviceId) {
                $free = $this->db->where('provice_id', $proviceId)
                             ->where('city_id', 0)
                             ->where('area_id', 0)->get('trade_invoice_sf_free')->row_array();
                empty($zh_fast_free) && $zh_fast_free = (isset($free['provice_fast_free']) && $free['provice_fast_free']) ? $free['provice_fast_free'] : 0;
                empty($zh_second_free) && $zh_second_free = (isset($free['provice_second_free']) && $free['provice_second_free']) ? $free['provice_second_free'] : 0;
                empty($zh_slow_free) && $zh_slow_free = (isset($free['provice_slow_free']) && $free['provice_slow_free']) ? $free['provice_slow_free'] : 0;
            }
            unset($free);

            // 计算美元运费
            $rate           = $this->m_global->get_rate('CNY');
            $us_fast_free   = empty($zh_fast_free) ? 0 : number_format(format_price_to_dollor($zh_fast_free, $rate), 2);
            $us_second_free = empty($zh_second_free) ? 0 : number_format(format_price_to_dollor($zh_second_free, $rate), 2);
            $us_slow_free   = empty($zh_slow_free) ? 0 : number_format(format_price_to_dollor($zh_slow_free, $rate), 2);

            $express_free = $express_free_us = 0;
            switch ($type) {
                case 1 :
                  $express_free    = $zh_fast_free;
                  $express_free_us = $us_fast_free;
                  break;
                case 2 :
                  $express_free    = $zh_second_free;
                  $express_free_us = $us_second_free;
                  break;
                case 3 :
                  $express_free    = $zh_slow_free;
                  $express_free_us = $us_slow_free;
                  break;
                default :
                  $express_free    = $zh_second_free;
                  $express_free_us = $us_second_free;
                  break;
            }

            exit(json_encode(array(
                'success' => 1,
                'zh_free' => number_format($express_free / 100, 2, '.', ''),
                'us_free' => number_format($express_free_us / 100, 2, '.', '')
            )));
        }
    }

    // 驳回和作废
    public function ajaxcannel() {
        if ($this->input->is_ajax_request()) {
            $invoiceNum = $this->input->post('invoice_num');
            $time       = date('Y-m-d H:i:s');
            $adminId    = intval($this->_adminInfo['id']);
            $reject     = trim($this->input->post('reject'));
            $status     = intval($this->input->post('status'));
            $reject_st     = intval($this->input->post('reject_st'));

            if (empty($invoiceNum)) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            // 作废订单处理
            $invoiceList = array();
            if ($status == 9) {
                $invoiceList = $this->db->where('invoice_num', $invoiceNum)->get('trade_invoice')->row_array();
                if (empty($invoiceList)) exit(json_encode(array(
                    'success' => 0,
                    'msg'     => '无法作废,未知错误'
                )));

                // 开启事务
                $this->db->trans_strict(TRUE);
                $this->db->trans_begin();

                // 作废发票 把运费退还给会员
                $ucid = intval($invoiceList['uid']);
                $msg  = '';
                // 记录会员资金变更日志
                if (intval($invoiceList['invoice_type']) == 1 && ($invoiceList['us_express_free'] > 0) && $ucid) {
                    // 增减会员资金变动日志
                    $code = $this->m_trade_invoice->ucTradeMoneyRecord(array(
                        'ucid'    => $ucid,
                        'money'   => $invoiceList['us_express_free'],
                        'desc'    => '发票作废退还'.'$'.$invoiceList['us_express_free'],
                        'adminid' => $adminId
                    ), '+');

                    if ($code != 200) {
                        switch ($code) {
                          case 100 :
                              $msg = lang('user_id_list_requied');
                            break;
                          case 110 :
                              $msg = lang('info_unvalid_request');
                            break;
                          case 404 :
                              $msg = lang('no_exist');
                            break;
                          case 300 :
                              $msg = lang('info_error');
                            break;
                        }
                        $this->db->trans_rollback();
                        exit(json_encode(array('success' => 0, 'msg' => $msg)));
                    }
                }

                // 修改发票状态
                $updatInsert = array(
                    'status'        => $status,
                    'cannel_remark' => $reject,
                    'update_at'     => $time
                );
                $res = $this->db->update('trade_invoice', $updatInsert, array('invoice_num' => $invoiceNum));

                // 删除开票订单标记
                $this->db->where('invoice_num', $invoiceNum)
                         ->set('mark', 0, FALSE)
                         ->update('trade_inovice_order');

                // 添加日志记录
               $res && $this->db->insert('trade_invoice_log', array(
                    'operator_id'   => $adminId,
                    'invoice_num'   => $invoiceNum,
                    'status'        => $status,
                    'remark'        => $msg.$reject,
                    'created_at'    => $time
                ));

               if ($this->db->trans_status() === FALSE) {
                    $result['success']  = 0;
                    $result['msg']      = 'System Error!';
                    $this->db->trans_rollback();
                } else {
                    $result['success']  = 1;
                    $result['msg']      = lang('update_success');
                    $this->db->trans_complete();
                }

            } else {
                $updatInsert = array(
                    'status'        => $status,
                    'cannel_remark' => $reject,
                    'update_at'     => $time
                );

                //已开票，已邮寄 驳回时，状态设置为0，未开票
                if(in_array($reject_st,array(1,2)))
                {
                    $updatInsert['status'] = 0;
                    $updatInsert['express_num'] = NULL;
                }


                $res = $this->db->update('trade_invoice', $updatInsert, array('invoice_num' => $invoiceNum));

                if (!$res) exit(json_encode(array('success' => 0, 'msg' => lang('result_false'))));

                // 添加日志记录
                $res && $this->db->insert('trade_invoice_log', array(
                    'operator_id'   => $adminId,
                    'invoice_num'   => $invoiceNum,
                    'status'        => $updatInsert['status'],
                    'remark'        => lang('reject').$reject,
                    'created_at'    => $time
                ));

                $result['success'] = 1;
                $result['msg']     = lang('update_success');
            }
            
            exit(json_encode($result));
        }
    }

    // 开票单号检查
    public function checkinvoicenum() {
        if ($this->input->is_ajax_request()) {
            $invoicenum = trim($this->input->get('invoicenum'));
            if (empty($invoicenum)) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            $total = $this->db->from('trade_invoice')->where('invoice_num', $invoicenum)->where('invoice_type', 1)->where_in('status', array(0,1))->count_all_results();
            if ($total) {
                $result['success'] = 1;
                $result['msg']     = lang('result_ok');
            } else {
                $result['success'] = 0;
                $result['msg']     = lang('result_false');
            }
            exit(json_encode($result));
        }
    }


    /** 顺丰运费管理 */
    public function sffree() {
        $this->_viewData['title'] = lang('invoice_manage');

        // 构建json数据
        $json     = array();
        $list     = $this->db->get('trade_invoice_sf_free')->result_array();

        if (isset($list) && !empty($list)) {
            // 定义根名称
            $json['name'] = lang('label_cn');

            $tempList = array();
            foreach ($list as $rows) {
                // 获取省份
                $tempList[$rows['provice_id']]['name'] = trim($rows['provice_name']);

                // 计算次晨达 次日达 隔日达
                $rows['provice_fast_free']   = $rows['provice_fast_free'] ? number_format($rows['provice_fast_free'] / 100, 2) : 0;
                $rows['provice_second_free'] = $rows['provice_second_free'] ? number_format($rows['provice_second_free'] / 100, 2) : 0;
                $rows['provice_slow_free']   = $rows['provice_slow_free'] ? number_format($rows['provice_slow_free'] / 100, 2) : 0;

                if ($rows['provice_id']) {
                    if ($rows['provice_fast_free'] || $rows['provice_second_free'] || $rows['provice_slow_free']) {
                        $tempList[$rows['provice_id']]['children'][0]['name'] = trim($rows['provice_name']).lang('choose2');
                    }

                    if (isset($tempList[$rows['provice_id']]['children'][0]) && $rows['provice_fast_free']) {
                        $tempList[$rows['provice_id']]['children'][0]['children'][0]['name'] = $rows['provice_id'].'次晨达:￥'.$rows['provice_fast_free'];
                    }

                    if (isset($tempList[$rows['provice_id']]['children'][0]) && $rows['provice_second_free']) {
                        $tempList[$rows['provice_id']]['children'][0]['children'][1]['name'] = $rows['provice_id'].'次日达:￥'.$rows['provice_second_free'];
                    }

                    if (isset($tempList[$rows['provice_id']]['children'][0]) && $rows['provice_slow_free']) {
                        $tempList[$rows['provice_id']]['children'][0]['children'][1]['name'] = $rows['provice_id'].'隔日达:￥'.$rows['provice_slow_free'];
                    }
                }

                // 是否添加了城市的数据
                if ($rows['city_id']) {

                    (!$rows['provice_slow_free'] && !$rows['provice_second_free'] && !$rows['provice_slow_free'] && !$rows['city_fast_free'] && !$rows['city_second_free'] && !$rows['city_slow_free'] && !$rows['area_fast_free'] && !$rows['area_second_free'] && !$rows['area_slow_free']) || $tempList[$rows['provice_id']]['children'][$rows['city_id']]['name'] = trim($rows['city_name']);

                    // 计算次晨达 次日达 隔日达
                    $rows['city_fast_free']   = $rows['city_fast_free'] ? number_format($rows['city_fast_free'] / 100, 2) : 0;
                    $rows['city_second_free'] = $rows['city_second_free'] ? number_format($rows['city_second_free'] / 100, 2) : 0;
                    $rows['city_slow_free']   = $rows['city_slow_free'] ? number_format($rows['city_slow_free'] / 100, 2) : 0;

                    if ($rows['city_fast_free'] || $rows['city_second_free'] || $rows['city_slow_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]['name'] = trim($rows['city_name']).lang('choose2');
                    }

                    if (isset($tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]) && $rows['city_fast_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]['children'][0]['name'] = $rows['city_id'].'次晨达:￥'.$rows['city_fast_free'];
                    }

                    if (isset($tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]) && $rows['city_second_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]['children'][1]['name'] = $rows['city_id'].'次日达:￥'.$rows['city_second_free'];
                    }

                    if (isset($tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]) && $rows['city_slow_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][0]['children'][2]['name'] = $rows['city_id'].'隔日达:￥'.$rows['city_slow_free'];
                    }
                }

                // 添加区域
                if ($rows['area_id'] && isset($tempList[$rows['provice_id']]['children'][$rows['city_id']])) {
                    $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][$rows['area_id']]['name'] = trim($rows['area_name']);

                    // 计算次晨达 次日达 隔日达
                    $rows['area_fast_free']   = $rows['area_fast_free'] ? number_format($rows['area_fast_free'] / 100, 2) : 0;
                    $rows['area_second_free'] = $rows['area_second_free'] ? number_format($rows['area_second_free'] / 100, 2) : 0;
                    $rows['area_slow_free']   = $rows['area_slow_free'] ? number_format($rows['area_slow_free'] / 100, 2) : 0;

                    if ($rows['area_fast_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][$rows['area_id']]['children'][0]['name'] = $rows['area_id'].'次晨达:￥'.$rows['area_fast_free'];
                    }

                    if ($rows['area_second_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][$rows['area_id']]['children'][1]['name'] = $rows['area_id'].'次日达:￥'.$rows['area_second_free'];
                    }

                    if ($rows['area_slow_free']) {
                        $tempList[$rows['provice_id']]['children'][$rows['city_id']]['children'][$rows['area_id']]['children'][2]['name'] = $rows['area_id'].'隔日达:￥'.$rows['area_slow_free'];
                    }
                }
            }

            $json['children'] = $tempList;
            $this->m_trade_invoice->restChildrenArrayKey($json);
            unset($tempList);
        }

        $this->_viewData['fun']     = $this->router->fetch_method();
        $this->_viewData['list']    = (array)$list;
        $this->_viewData['json']    = json_encode($json);

        parent::index('admin/', 'invoice_sf');
    }


    /** 添加运费 **/
    public function add_free() {
        if ($this->input->is_ajax_request()) {
            $freetype   = intval($this->input->post('freetype'));
            $freemoney  = trim($this->input->post('freemoney'));
            $country    = intval($this->input->post('country'));
            $provice    = intval($this->input->post('provice'));
            $city       = intval($this->input->post('city'));
            $area       = intval($this->input->post('area'));
            $proviceMe  = trim($this->input->post('provice_name'));
            $cityMe     = trim($this->input->post('city_name'));
            $areaMe     = trim($this->input->post('area_name'));
            $time       = date('Y-m-d H:i:s');
            $adminId    = intval($this->_adminInfo['id']);


            if (empty($freetype)) exit(json_encode(array('success' => 0, 'msg' => '请选择到达类型')));
            if ($freemoney == '') exit(json_encode(array('success' => 0, 'msg' => '运费不允许为空')));

            // 计算金额
            $freemoney = round($freemoney * 100);

            $updateSF = $insertSF = $sfinfo = array();

            $insertSF['country_id']   = $country;
            $insertSF['provice_id']   = $provice;
            $insertSF['city_id']      = $city;
            $insertSF['area_id']      = $area;
            $insertSF['provice_name'] = (isset($proviceMe) && !empty($proviceMe) && $provice) ? $proviceMe : '';
            $insertSF['city_name']    = (isset($cityMe) && !empty($cityMe) && $city) ? $cityMe : '';
            $insertSF['area_name']    = (isset($areaMe) && !empty($areaMe) && $area) ? $areaMe : '';
            $insertSF['operator_id']  = $adminId;
            $insertSF['created_at']   = $time;
            $insertSF['updated_at']   = $time;

            // 查看区域运费是否设定
            $return = false;
            if ($area) {
                switch ($freetype) {
                    // 次晨达
                    case 1 :
                        $updateSF['area_fast_free'] = $freemoney;
                        $insertSF['area_fast_free'] = $freemoney;
                        break;
                    // 次日达
                    case 2 :
                        $updateSF['area_second_free'] = $freemoney;
                        $insertSF['area_second_free'] = $freemoney;
                        break;
                    // 隔日达
                    case 3 :
                        $updateSF['area_slow_free'] = $freemoney;
                        $insertSF['area_slow_free'] = $freemoney;
                        break;
                    default :
                        $updateSF['area_second_free'] = $freemoney;
                        $insertSF['area_second_free'] = $freemoney;
                        break;
                }

                $sfinfo = $this->db->select('id')->where('area_id', $area)->get('trade_invoice_sf_free')->row_array();
            } elseif ($city) {
                switch ($freetype) {
                    // 次晨达
                    case 1 :
                        $updateSF['city_fast_free'] = $freemoney;
                        $insertSF['city_fast_free'] = $freemoney;
                        break;
                    // 次日达
                    case 2 :
                        $updateSF['city_second_free'] = $freemoney;
                        $insertSF['city_second_free'] = $freemoney;
                        break;
                    // 隔日达
                    case 3 :
                        $updateSF['city_slow_free'] = $freemoney;
                        $insertSF['city_slow_free'] = $freemoney;
                        break;
                    default :
                        $updateSF['city_second_free'] = $freemoney;
                        $insertSF['city_second_free'] = $freemoney;
                        break;
                }
                $sfinfo = $this->db->select('id')
                                   ->where('city_id', $city)
                                   ->where('area_id', 0)
                                   ->get('trade_invoice_sf_free')->row_array();
            } elseif ($provice) {
                switch ($freetype) {
                    // 次晨达
                    case 1 :
                        $updateSF['provice_fast_free'] = $freemoney;
                        $insertSF['provice_fast_free'] = $freemoney;
                        break;
                    // 次日达
                    case 2 :
                        $updateSF['provice_second_free'] = $freemoney;
                        $insertSF['provice_second_free'] = $freemoney;
                        break;
                    // 隔日达
                    case 3 :
                        $updateSF['provice_slow_free'] = $freemoney;
                        $insertSF['provice_slow_free'] = $freemoney;
                        break;
                    default :
                        $updateSF['provice_second_free'] = $freemoney;
                        $insertSF['provice_second_free'] = $freemoney;
                        break;
                }
                $sfinfo = $this->db->select('id')
                                   ->where('provice_id', $provice)
                                   ->where('city_id', 0)
                                   ->get('trade_invoice_sf_free')->row_array();
            }
            $updateSF['updated_at'] = $time;
            (isset($proviceMe) && !empty($proviceMe) && $provice) && $updateSF['provice_name'] = $proviceMe;
            (isset($cityMe) && !empty($cityMe) && $city) && $updateSF['city_name'] = $cityMe;
            (isset($areaMe) && !empty($areaMe) && $area) && $updateSF['area_name'] = $areaMe;

            $return = (isset($sfinfo['id']) && $sfinfo['id']) 
                        ? $this->db->update('trade_invoice_sf_free', $updateSF, array('id' => $sfinfo['id'])) 
                        : $this->db->insert('trade_invoice_sf_free', $insertSF);
            unset($insertSF, $updateSF);
            $result = $return ? array('success' => 1, 'msg' => lang('submit_success')) : array('success' => 0, 'msg' => lang('result_false'));
            exit(json_encode($result));
        }
    }
}