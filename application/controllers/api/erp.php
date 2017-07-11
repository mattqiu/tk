<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Erp extends CI_Controller {

    // 接口请求签名密钥
    const REQUEST_SIGN_KEY = 'TPs1#)8!6';

    private $reqtData = null;
    private $retData = array(
        'code' => 200,
        'msg' => "",
        'data' => array(),
    );

    public function __construct() {

        parent::__construct();
        $this->load->model("m_debug");
        $this->load->library('validator');
        
        // 检查 IP 限制，TODO....

        // request 参数，只接受 post
        $this->reqtData = $this->input->post();
//        $this->m_debug->log("tico.wong.reqtData start");
//        $this->m_debug->log($this->reqtData);
//        $this->m_debug->log("tico.wong.reqtData end");

        // 签名校验
        if (false === $this->_checkSign()) {
            $this->retData['code'] = 1501;
            $this->retData['msg'] = "request sign validate failed";
            $this->_output();
        }
    }

    /**
     * 检查 request sign 签名
     */
    private function _checkSign() {

        if (!isset($this->reqtData['sign'])) {
            return false;
        }
        $sign = $this->reqtData['sign'];
        unset($this->reqtData['sign']);

        // 生成有效签名，需要过滤掉指定字符
        $signData = array(
            'url' => uri_string(),
            'param' => $this->reqtData,
        );
        $filterArr = array(
            // 不同系统换行符不一致（win: CRLF, unix: LF, mac: CR），经过调用后都转成 LF，所以全部过滤掉
            "\n",
            "\r",
        );
        $clearSign = substr_replace(serialize($signData), $filterArr, "");
        $validSign = hash_hmac('md5', $clearSign, self::REQUEST_SIGN_KEY);
//        $this->m_debug->log("tico.wong.validSign-->".$validSign);
        if (false === $validSign) {
            return false;
        }

        // request 中签名与有效签名不匹配，验证失败
//        $this->m_debug->log("tico.wong.sign-->".$sign);
        if ($sign != $validSign) {
            return false;
        }
        return true;
    }

    /**
     * 接口输入
     */
    private function _output() {
        header("Content-Type: text/html; charset=UTF-8");
        if(isset($this->reqtData['debug']) && $this->reqtData['debug'] == "ticowong")
        {
            exit(json_encode($this->retData));
        }
        echo erp_api_encrypt(json_encode($this->retData));
//        echo (json_encode($this->retData));
        exit;
    }

    public function index() {
        $this->retData['msg'] = "api index, param: {$this->reqtData['param']}";
        $this->_output();
    }

    /****************************** 供应商 ******************************/

    /**
     * 添加供应商
     */
    public function supplier_create() {

        $this->load->model('m_goods');

        $attr = array(
            'id' => isset($this->reqtData['id']) ? $this->reqtData['id'] : 0,
            'supplier' => isset($this->reqtData['supplier']) ? $this->reqtData['supplier'] : "",
            'portal' => isset($this->reqtData['portal']) ? $this->reqtData['portal'] : "",
            'address' => isset($this->reqtData['address']) ? $this->reqtData['address'] : "",
            'contact' => isset($this->reqtData['contact']) ? $this->reqtData['contact'] : "",
            'telephone' => isset($this->reqtData['telephone']) ? $this->reqtData['telephone'] : "",
            'cellphone' => isset($this->reqtData['cellphone']) ? $this->reqtData['cellphone'] : "",
            'email' => isset($this->reqtData['email']) ? $this->reqtData['email'] : "",
            'qq' => isset($this->reqtData['qq']) ? $this->reqtData['qq'] : "",
            'aliim' => isset($this->reqtData['aliim']) ? $this->reqtData['aliim'] : "",
            'is_shipper' => isset($this->reqtData['is_shipper']) ? $this->reqtData['is_shipper'] : 0,
            'shipping_currency' => isset($this->reqtData['currency']) ? $this->reqtData['currency'] : '',
            'store_location' => isset($this->reqtData['address']) ? $this->reqtData['address'] : '',
            'area_rule' => isset($this->reqtData['area_rule']) ? $this->reqtData['area_rule'] : 0,
            'sale_area' => isset($this->reqtData['sale_area']) ? $this->reqtData['sale_area'] : 0,
            'store_location_code' => isset($this->reqtData['store_location_code']) ? $this->reqtData['store_location_code'] : 0,
            'supplier_recommend' => isset($this->reqtData['recommend_id']) ? intval($this->reqtData['recommend_id']) : 0,
            'status' => intval($this->reqtData['status']),
            'operator_id' => intval($this->reqtData['operator_id']),
            'country_code' => $this->reqtData['country_code'],
            'secondCode' => $this->reqtData['secondary_code'],
            'thirdCode' => $this->reqtData['third_code'] !='' ? $this->reqtData['third_code'] : 0,
        );

        if (false === $this->m_goods->erpapi_create_supplier($attr)) {
            $this->retData['code'] = 1102;
            $this->retData['msg'] = "insert database failed";
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 修改供应商信息
     */
    public function supplier_modify() {

        $this->load->model('m_goods');

        // 获取供应商 id
        if (isset($this->reqtData['id']) && intval($this->reqtData['id']) > 0) {
            $supplierId = $this->reqtData['id'];
        } else {
            $this->retData['code'] = 1001;
            $this->retData['msg'] = "invalid param id";
            $this->_output();
        }

//        file_put_contents("/tmp/supplier_modify_api.log",var_export($this->reqtData,true)."\n",8);

        // 更新参数
        $updateAttr = array();

        // 将需要更新的字段加到更新参数数组中
        if (isset($this->reqtData['supplier'])) {
            $updateAttr['supplier_name'] = $this->reqtData['supplier'];
        }
        if (isset($this->reqtData['address'])) {
            $updateAttr['supplier_address'] = $this->reqtData['address'];
        }
        if (isset($this->reqtData['contact'])) {
            $updateAttr['supplier_user'] = $this->reqtData['contact'];
        }
        if (isset($this->reqtData['telephone'])) {
            $updateAttr['supplier_phone'] = $this->reqtData['telephone'];
        }
        if (isset($this->reqtData['cellphone'])) {
            $updateAttr['supplier_tel'] = $this->reqtData['cellphone'];
        }
        if (isset($this->reqtData['portal'])) {
            $updateAttr['supplier_link'] = $this->reqtData['portal'];
        }
        if (isset($this->reqtData['email'])) {
            $updateAttr['supplier_email'] = $this->reqtData['email'];
        }
        if (isset($this->reqtData['qq'])) {
            $updateAttr['supplier_qq'] = $this->reqtData['qq'];
        }
        if (isset($this->reqtData['aliim'])) {
            $updateAttr['supplier_ww'] = $this->reqtData['aliim'];
        }
        if (isset($this->reqtData['is_shipper'])) {
            $updateAttr['is_supplier_shipping'] = $this->reqtData['country_code'];
        }
        if (isset($this->reqtData['area_rule'])) {
            $updateAttr['area_rule'] = $this->reqtData['area_rule'];
        }
        if (isset($this->reqtData['sale_area'])) {
            $updateAttr['sale_area'] = $this->reqtData['sale_area'];
        }
        if (isset($this->reqtData['store_location_code'])) {
            $updateAttr['store_location_code'] = $this->reqtData['store_location_code'];
        }
        if (isset($this->reqtData['currency'])) {
            $updateAttr['shipping_currency'] = $this->reqtData['currency'];
        }
        if (isset($this->reqtData['recommend_id'])) {
            $updateAttr['supplier_recommend'] = $this->reqtData['recommend_id'];
        }
        if (isset($this->reqtData['status'])) {
            $updateAttr['status'] = $this->reqtData['status'];
        }
        if (isset($this->reqtData['operator_id'])) {
            $updateAttr['operator_id'] = $this->reqtData['operator_id'];
        }
        if (isset($this->reqtData['secondary_code'])) {
            $updateAttr['addr_lv2'] = $this->reqtData['secondary_code'];
        }
        if (isset($this->reqtData['third_code'])) {
            $updateAttr['addr_lv3'] = empty($this->reqtData['third_code'])? 0 : $this->reqtData['third_code'];
        }
        if (isset($this->reqtData['operator_id'])) {
            $updateAttr['operator_id'] = $this->reqtData['operator_id'];
        }
        if(isset($this->reqtData['country_code'])){
            $updateAttr['country_code'] = $this->reqtData['country_code'];
        }
        if (empty($updateAttr)) {
            $this->_output();
        }

        if (false === $this->m_goods->erpapi_modify_supplier($supplierId, $updateAttr)) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "update database failed: ".$this->db->last_query();
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 添加第三方帐号
     */
    public function supplier_user_create() {

        $this->load->model('m_goods');

        // 获取供应商
        if (isset($this->reqtData['supplier_id']) && intval($this->reqtData['supplier_id']) > 0) {
            $supplierId = $this->reqtData['supplier_id'];
        } else {
            $this->retData['code'] = 1001;
            $this->retData['msg'] = "invalid param id";
            $this->_output();
        }

        // 更新参数
        $updateAttr = $this->reqtData;
        unset($updateAttr['supplier_id']);

        if (false === $this->m_goods->erpapi_modify_supplier_user($supplierId, $updateAttr)) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "update database failed";
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 修改第三方帐号信息
     */
    public function supplier_user_modify() {

        $this->load->model('m_goods');

        // 获取供应商
        if (isset($this->reqtData['supplier_id']) && intval($this->reqtData['supplier_id']) > 0) {
            $supplierId = $this->reqtData['supplier_id'];
        } else {
            $this->retData['code'] = 1001;
            $this->retData['msg'] = "invalid param id";
            $this->_output();
        }

        // 更新参数
        $updateAttr = array();

        // 将需要更新的字段加到更新参数数组中
        if (isset($this->reqtData['supplier_username'])) {
            $updateAttr['supplier_username'] = $this->reqtData['supplier_username'];
        }
        if (isset($this->reqtData['supplier_password'])) {
            $updateAttr['supplier_password'] = $this->reqtData['supplier_password'];
        }
        if (isset($this->reqtData['supplier_last_time'])) {
            $updateAttr['supplier_last_time'] = $this->reqtData['supplier_last_time'];
        }
        if (isset($this->reqtData['supplier_login_time'])) {
            $updateAttr['supplier_login_time'] = $this->reqtData['supplier_login_time'];
        }
        if (empty($updateAttr)) {
            $this->_output();
        }

        if (false === $this->m_goods->erpapi_modify_supplier_user($supplierId, $updateAttr)) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "update database failed";
            $this->_output();
        }

        $this->_output();
    }

    public function get_supplier() {

        $this->load->model('m_goods');

        $this->retData['data'] = $this->m_goods->get_supplier_list();

        $this->_output();
    }

    /****************************** 物流公司 ******************************/

    /**
     * 添加物流公司
     */
    public function logistics_create() {

        $this->load->model('m_trade');

        $attr = array(
            'company_code' => $this->reqtData['logistics_code'] ? : 0,
            'company_shortname' => $this->reqtData['logistics_shortname'] ? : "",
            'company_name' => $this->reqtData['logistics_name'] ? : "",
            'tracking_url' => $this->reqtData['tracking_url'] ? : "",
        );
        if (false === $this->m_trade->add_freight($attr)) {
            $this->retData['code'] = 1102;
            $this->retData['msg'] = "insert database failed";
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 修改物流公司
     */
    public function logistics_modify() {

        $this->load->model('m_trade');

        $code = $this->reqtData['logistics_code'] ? : 0;

        $attr = array();
        if (isset($this->reqtData['company_shortname'])) {
            $attr['company_shortname'] = $this->reqtData['company_shortname'];
        }
        if (isset($this->reqtData['logistics_name'])) {
            $attr['company_name'] = $this->reqtData['logistics_name'];
        }
        if (isset($this->reqtData['tracking_url'])) {
            $attr['tracking_url'] = $this->reqtData['tracking_url'];
        }

        if (empty($attr)) {
            $this->_output();
        }

        if (false === $this->m_trade->edit_freight($code, $attr)) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "update database failed";
            $this->_output();
        }

        $this->_output();
    }

    /****************************** 商品 ******************************/

    /**
     * 添加商品
     *    $this->reqtData = array(
     *        'goods_sn_main' => "String",
     *        'language' => array(
     *            1 => array(
     *                'goods_name' => "String",
     *                'seller_note' => "String",
     *                'goods_note' => "String",
     *                'goods_desc' => "String",
     *                'home_title' => "String",
     *                'home_note' => "String",
     *                'meta_title' => "String",
     *                'meta_keywords' => "String",
     *                'meta_desc' => "String",
     *                'tps_sale' => "Bool",
     *                'tps_cate_id' => "Int",
     *                'tps_brand_id' => "Int",
     *                'detail_img' => array(
     *                    array(
     *                        'img_uri' => "String",
     *                    ),
     *                ),
     *            ),
     *        ),
     *        'goods_name_cn' => "String",
     *        'supplier_id' => "Int",
     *        'shipper_id' => "Int",
     *        'provenance' => "String",
     *        'sale_region' => "String",
     *        'goods_weight' => "Int",
     *        'goods_volume' => "Int",
     *        'market_price' => "Int",
     *        'shop_price' => "Int",
     *        'purchase_price' => "Int",
     *        'goods_img' => "String",
     *        'create_time' => "Int",
     *        'subsidiary' => array(
     *            array(
     *                'goods_sn' => "String",
     *                'price' => "Int",
     *                'virtual_inventory' => "Int",
     *                'language' => array(
     *                    1 => array(
     *                        'color' => "String",
     *                        'size' => "String",
     *                        'customer' => "String",
     *                    ),
     *                ),
     *                'gallery' => array(
     *                    array(
     *                        'thumb_img' => "String",
     *                        'big_img' => "String",
     *                    ),
     *                ),
     *            ),
     *        ),
     *        'sort_order' => "Int",
     *        'is_new' => "Bool",
     *        'is_hot' => "Bool",
     *        'is_home' => "Bool",
     *        'is_best' => "Bool",
     *        'is_free_shipping' => "Bool",
     *        'is_ship24h' => "Bool",
     *        'is_alone_sale' => "Bool",
     *        'is_upgrade' => "Bool",
     *        'is_special' => "Bool",
     *        'is_voucher_goods' => "Bool",
     *        'group_goods_id' => "Int",
     *    );
     */
    public function commodity_create() {

        if(isset($this->reqtData['serialize_data']))
        {
            $this->reqtData =unserialize($this->reqtData['serialize_data']);
        }

        $this->load->model('m_goods');
        $modRet = $this->m_goods->erpapi_create_goods($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 修改商品
     *    $this->reqtData = array(
     *        'goods_sn_main' => "String",
     *        'language' => array(
     *            1 => array(
     *                'goods_name' => "String",
     *                'seller_note' => "String",
     *                'goods_note' => "String",
     *                'goods_desc' => "String",
     *                'home_title' => "String",
     *                'home_note' => "String",
     *                'meta_title' => "String",
     *                'meta_keywords' => "String",
     *                'meta_desc' => "String",
     *                'tps_sale' => "Bool",
     *                'tps_cate_id' => "Int",
     *                'tps_brand_id' => "Int",
     *                'detail_img' => array(
     *                    array(
     *                        'img_uri' => "String",
     *                        'old_uri' => "String",
     *                    ),
     *                ),
     *            ),
     *        ),
     *        'goods_name_cn' => "String",
     *        'supplier_id' => "Int",
     *        'shipper_id' => "Int",
     *        'provenance' => "String",
     *        'sale_region' => "String",
     *        'goods_weight' => "Int",
     *        'goods_volume' => "Int",
     *        'market_price' => "Int",
     *        'shop_price' => "Int",
     *        'purchase_price' => "Int",
     *        'goods_img' => "String",
     *        'subsidiary' => array(
     *            array(
     *                'goods_sn' => "String",
     *                'price' => "Int",
     *                'virtual_inventory' => "Int",
     *                'language' => array(
     *                    1 => array(
     *                        'color' => "String",
     *                        'size' => "String",
     *                        'customer' => "String",
     *                    ),
     *                ),
     *                'gallery' => array(
     *                    array(
     *                        'thumb_img' => "String",
     *                        'big_img' => "String",
     *                        'old_big_img' => "String",
     *                    ),
     *                ),
     *            ),
     *        ),
     *        'sort_order' => "Int",
     *        'is_new' => "Bool",
     *        'is_hot' => "Bool",
     *        'is_home' => "Bool",
     *        'is_best' => "Bool",
     *        'is_free_shipping' => "Bool",
     *        'is_ship24h' => "Bool",
     *        'is_alone_sale' => "Bool",
     *        'is_upgrade' => "Bool",
     *        'is_special' => "Bool",
     *        'is_voucher_goods' => "Bool",
     *        'group_goods_id' => "Int",
     *    );
     */
    public function commodity_modify() {

        if(isset($this->reqtData['serialize_data']))
        {
            $this->reqtData =unserialize($this->reqtData['serialize_data']);
        }

        $this->load->model('m_goods');
//        $this->reqtData =unserialize($this->reqtData['data']);
        $modRet = $this->m_goods->erpapi_modify_goods($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 商品批量修改（新）
     *    $this->reqtData = array(
     *        array(
     *            'goods_sn_main' => "String",
     *            'shop_price' => "Int",
     *            'goods_note' => "String",
     *            'subsidiary' => array(
     *                array(
     *                    'goods_sn' => "String",
     *                    'price' => "Int",
     *                ),
     *                ......
     *            ),
     *        ),
     *        ......
     *    );
     */
    public function commodity_batch_modify() {
        $this->load->model('m_goods');

        $modRet = $this->m_goods->erpapi_batch_modify_goods($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 商品批量修改（旧）
     */
    public function commodity_batch() {
        $this->load->model('m_goods');
        $modRet = $this->m_goods->erpapi_batch_goods($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];

            $this->_output();
        }
        $this->_output();
    }

    /**
     * 修复图片路径针对相册和详情
     * $syncData = array(
     *     'type' => int,
     *     'language' => int,
     *     'sku' => string,
     *     'img_new_uri' => string,
     *     'img_old_uri' => string
     * );
     */
    public function repair_img_url(){
        $this->load->model('m_goods');
        $modRet = $this->m_goods->erpapi_modify_img_url($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }
        $this->_output();
    }

    /**
     * 修复mall_goods_detail_img表数据
     */
    public function repair_mall_goods_detail_img()
    {
        $this->load->model("tb_mall_goods_detail_img");
        $data = $this->reqtData['data'];
        if(!$data){
            $retData['code'] = 1109;
            $retData['msg'] = 'data field is required';
            $this->_output();
        }
        $data = unserialize($data);
        if(!$data)
        {
            $retData['code'] = 1109;
            $retData['msg'] = 'data field must be serialize data';
            $this->_output();
        }
        if(!is_array($data))
        {
            $retData['code'] = 1109;
            $retData['msg'] = 'data field must be array type after unserialize';
            $this->_output();
        }
        //取所有goods_sn
        $goods_sns = [];
        foreach($data as $kd=>$vd)
        {
            if(!in_array($vd['goods_sn_main'],$goods_sns))
            {
                $goods_sns[] = $vd['goods_sn_main'];
            }
        }
        //删除全部数据
        if(count($goods_sns))
        {
            foreach($goods_sns as $v)
            {
                $this->tb_mall_goods_detail_img->delete_batch(['goods_sn_main'=>$v]);
            }
        }
        //插入全部数据
        $this->tb_mall_goods_detail_img->insert_batch($data);
        $this->_output();
    }

    /**
     * 修复mall_goods_gallery表数据
     */
    public function repair_mall_goods_gallery()
    {
//        $data = [
//            ['goods_sn'=>'test1','thumb_img'=>'test1','big_img'=>'test1'],
//            ['goods_sn'=>'test2','thumb_img'=>'test2','big_img'=>'test2'],
//        ];
//        $this->reqtData['data'] = serialize($data);
//        var_export($this->reqtData['data']);exit;
        $this->load->model("tb_mall_goods_gallery");
        $data = $this->reqtData['data'];
        if(!$data){
            $retData['code'] = 1109;
            $retData['msg'] = 'data field is required';
            $this->_output();
        }
        $data = unserialize($data);
        if(!$data)
        {
            $retData['code'] = 1109;
            $retData['msg'] = 'data field must be serialize data';
            $this->_output();
        }
        if(!is_array($data))
        {
            $retData['code'] = 1109;
            $retData['msg'] = 'data field must be array type after unserialize';
            $this->_output();
        }
        //取所有goods_sn
        $goods_sns = [];
        foreach($data as $kd=>$vd)
        {
            if(!in_array($vd['goods_sn'],$goods_sns))
            {
                $goods_sns[] = $vd['goods_sn'];
            }
        }
        //删除全部数据
        if(count($goods_sns))
        {
            foreach($goods_sns as $v)
            {
                $this->tb_mall_goods_gallery->delete_batch(['goods_sn'=>$v]);
            }
        }
        //插入全部数据
        $this->tb_mall_goods_gallery->insert_batch($data);
        $this->_output();
    }

    /**
     * 修复主图的图片路径
     *
     * array(
     *     'goods_sn_main' => string,
     *     'language_id' => int,
     *     'goods_img' => string
     * );
     */
    public function repair_mainPic_url(){
        $this->load->model('m_goods');
        $modRet = $this->m_goods->erpapi_modify_main_img($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }
        $this->_output();
    }

    /**
     * 修改商品库存
     */
    public function modify_store() {

        $this->load->model('m_goods');

        $attr = array(
            'goods_sn' => $this->reqtData['goods_sn'] ? : "",
            'store_number' => $this->reqtData['store_number'] ? : 0,
        );
        $res = $this->m_goods->erpapi_modify_goods_store($attr);
        if ($res['code'] != 200) {
        //if (false === $this->m_goods->erpapi_modify_goods_store($attr)) {
            $this->retData['code'] = 1102;
            $this->retData['msg'] = "insert database failed";
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 取消促销数据
     * * param: array(
     *     'goods_sn_main' => string,
     *     'goods_sn' => string,
     *     'price_adjust_type' => int,
     *     'promote_price' => int
     * );
     */
    public function cancel_promote() {
        $this->load->model('m_goods');
        $modRet = $this->m_goods->erpapi_cancel_promote($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }
        $this->_output();
    }

    /**
     * 取消商品的新品属性
     * @author james
     */
    public function cancel_goods_new() {
        $this->load->model('m_goods');
        if (!$this->reqtData['goods_sn_main'] || !$this->reqtData['language_id']) {
            $this->retData['code'] = 1003;
            $this->retData['msg'] = '参数错误';
            $this->_output();
        }
        $ret = $this->m_goods->cancel_goods_is_new($this->reqtData);
        if ($ret['code'] != 200) {
            $this->retData['code'] = $ret['code'];
            $this->retData['msg'] = $ret['msg'];
            $this->_output();
        }
        $this->_output();
    }

    /**
     * 新增品牌数据
     */
    public function create_brand() {
        $this->load->model('m_goods');
        if (!$this->reqtData['brand_id'] || !$this->reqtData['brand_name'] || !$this->reqtData['cate_id'] || !$this->reqtData['language_id']) {
            $this->retData['code'] = 1003;
            $this->retData['msg'] = '参数错误';
            $this->_output();
        }
        $ret = $this->m_goods->add_brand_from_erp($this->reqtData);
        if ($ret['code'] != 200) {
            $this->retData['code'] = $ret['code'];
            $this->retData['msg'] = $ret['msg'];
            $this->_output();
        }
        $this->_output();
    }

    /**
     * 修复图片使用 目前是描述图片
     * @author james
     */
    public function commodity_repair_desc_img() {
        $this->load->model('m_goods');
        if (!$this->reqtData['sku'] || empty($this->reqtData['data'])) {
            $this->retData['code'] = 1003;
            $this->retData['msg'] = '参数错误';
            $this->_output();
        }
        $ret = $this->m_goods->repair_desc_pic($this->reqtData);
        if ($ret['code'] != 200) {
            $this->retData['code'] = $ret['code'];
            $this->retData['msg'] = $ret['msg'];
            $this->_output();
        }
        $this->_output();
    }

    /****************************** 订单 ******************************/

    /**
     * 修改订单信息
     *
     * param: array(
     *     'order_id' => string,
     *     'status' => string,
     *     'logistics_code' => int,
     *     'tracking_no' => string,
     *     'deliver_time' => date,
     * );
     */
    public function order_modify() {

        $this->load->model('m_trade');
        $this->load->model('tb_trade_orders');
        $this->load->model('tb_trade_order_to_erp_logs');

        $orderId = $this->reqtData['order_id'] ? : 0;
        $attr = array();
        if (isset($this->reqtData['consignee'])) {
            $attr['consignee'] = $this->reqtData['consignee'];
        }
        if (isset($this->reqtData['phone'])) {
            $attr['phone'] = $this->reqtData['phone'];
        }
        if (isset($this->reqtData['reserve_num'])) {
            $attr['reserve_num'] = $this->reqtData['reserve_num'];
        }
        if (isset($this->reqtData['address'])) {
            $attr['address'] = $this->reqtData['address'];
        }
        if (isset($this->reqtData['zip_code'])) {
            $attr['zip_code'] = $this->reqtData['zip_code'];
        }
        if (isset($this->reqtData['customs_clearance'])) {
            $attr['customs_clearance'] = $this->reqtData['customs_clearance'];
        }
        if (isset($this->reqtData['status'])) {
            $attr['status'] = $this->reqtData['status'];
        }
        if (isset($this->reqtData['logistics_code']) && isset($this->reqtData['tracking_no'])) {
            $attr['freight_info'] = trim($this->reqtData['logistics_code']."|".$this->reqtData['tracking_no'],'|');
        }
        if (isset($this->reqtData['deliver_time'])) {
            $attr['deliver_time'] = $this->reqtData['deliver_time'];
        }
        //真实运费
        if (isset($this->reqtData['deliver_fee_usd'])) {
            $attr['deliver_fee_usd'] = $this->reqtData['deliver_fee_usd'];
        }
        if (empty($attr)) {
            $this->_output();
        }

        // 如果同步定时器中存在该订单流水，则不允许修改，以免修改内容被覆盖导致两边数据不同步
        if (true === $this->tb_trade_order_to_erp_logs->checkErpLogsById($orderId)) {
            $this->retData['code'] = 1008;
            $this->retData['msg'] = "order {$orderId} catch not synchronized log";
            $this->_output();
        }

        // 查询订单是否存在
        if (!$this->tb_trade_orders->getOrderInfo($orderId, array('order_id'))) {
            $this->retData['code'] = 1100;
            $this->retData['msg'] = "order {$orderId} is not exist";
            $this->_output();
        }

        // 修改信息
        if (false === $this->tb_trade_orders->updateInfoById($orderId, $attr)) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "update database failed";
            $this->_output();
        }

        // 记录订单流水
        if (false === $this->m_trade->add_order_log($orderId, 150, "data modify: ".var_export($attr, true), 0)) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "insert order log failed";
            $this->_output();
        }
        $this->_output();
    }

    /* 批量修改订单信息 */
    public function orders_modify(){
        $this->load->model('m_trade');
        $this->load->model('tb_trade_orders');
        $this->load->model('tb_trade_order_to_erp_logs');
//        $data = unserialize($this->reqtData['data']);
        $data = $this->reqtData['data'];
        if(isset($data))
            foreach($data as $item)
            {
                $attr = array();
                if (isset($item['consignee'])) {
                    $attr['consignee'] = $item['consignee'];
                }
                if (isset($item['phone'])) {
                    $attr['phone'] = $item['phone'];
                }
                if (isset($item['reserve_num'])) {
                    $attr['reserve_num'] = $item['reserve_num'];
                }
                if (isset($item['address'])) {
                    $attr['address'] = $item['address'];
                }
                if (isset($item['zip_code'])) {
                    $attr['zip_code'] = $item['zip_code'];
                }
                if (isset($item['customs_clearance'])) {
                    $attr['customs_clearance'] = $item['customs_clearance'];
                }
                if (isset($item['status'])) {
                    $attr['status'] = $item['status'];
                }
                if (isset($item['logistics_code']) && isset($item['tracking_no'])) {
                    $attr['freight_info'] = trim($item['logistics_code']."|".$item['tracking_no'],'|');
                }
                if (isset($item['deliver_time'])) {
                    $attr['deliver_time'] = $item['deliver_time'];
                }

                //真实运费
                if (isset($item['deliver_fee_usd'])) {
                    $attr['deliver_fee_usd'] = $item['deliver_fee_usd'];
                }
                if (empty($attr)) {
                    $this->_output();
                }
                // 如果同步定时器中存在该订单流水，则不允许修改，以免修改内容被覆盖导致两边数据不同步
                if (true === $this->tb_trade_order_to_erp_logs->checkErpLogsById($item['order_id'])) {
                    $this->retData['code'] = 1008;
                    $this->retData['msg'] = "order {$item['order_id']} catch not synchronized log";
                    $this->_output();
                }

                // 查询订单是否存在
                if (!$this->tb_trade_orders->getOrderInfo($item['order_id'], array('order_id'))) {
                    $this->retData['code'] = 1100;
                    $this->retData['msg'] = "order {$item['order_id']} is not exist";
                    $this->_output();
                }

                // 修改信息
                if (false === $this->tb_trade_orders->updateInfoById($item['order_id'], $attr)) {
                    $this->retData['code'] = 1103;
                    $this->retData['msg'] = "update database failed";
                    $this->_output();
                }

                // 记录订单流水
                if (false === $this->m_trade->add_order_log($item['order_id'], 150, "data modify: ".var_export($attr, true), 0)) {
                    $this->retData['code'] = 1103;
                    $this->retData['msg'] = "insert order log failed";
                    $this->_output();
                }
            }
        $this->_output();
    }

    /****************************** 广告管理 ******************************/

    /**
     * 广告添加
     *    $this->reqtData = array(
     *        'id' => "Int",
     *        'location' => "String",
     *        'ad_img' => "String",
     *        'action_val' => "String",
     *        'status' => "Int",
     *        'sort_order' => "Int",
     *        'reset_sort' => array(
     *            0 => array(
     *                'id' => "Int",
     *                'order' => "Int",
     *            ),
     *        ),
     *    );
     */
    public function tpsmall_ad_create() {

        $this->load->model('tb_mall_ads');

        $modRet = $this->tb_mall_ads->add_ad_and_reset_order($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 广告修改
     *    $this->reqtData = array(
     *        'id' => "Int",
     *        'ad_img' => "String",
     *        'action_val' => "String",
     *        'status' => "Int",
     *        'sort_order' => "Int",
     *        'reset_sort' => array(
     *            0 => array(
     *                'id' => "Int",
     *                'order' => "Int",
     *            ),
     *        ),
     *    );
     */
    public function tpsmall_ad_modify() {

        $this->load->model('tb_mall_ads');

        $modRet = $this->tb_mall_ads->edit_ad_and_reset_order($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 广告删除
     *    $this->reqtData = array(
     *        'id' => "Int",
     *        'reset_sort' => array(
     *            0 => array(
     *                'id' => "Int",
     *                'order' => "Int",
     *            ),
     *        ),
     *    );
     */
    public function tpsmall_ad_delete() {

        $this->load->model('tb_mall_ads');

        $modRet = $this->tb_mall_ads->delete_ad_and_reset_order($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 广告修改排序
     *    $this->reqtData = array(
     *        'reset_sort' => array(
     *            0 => array(
     *                'id' => "Int",
     *                'order' => "Int",
     *            ),
     *        ),
     *    );
     */
    public function tpsmall_ad_order_modify() {

        $this->load->model('tb_mall_ads');

        $modRet = $this->tb_mall_ads->reset_ad_order($this->reqtData);
        if (200 !== $modRet['code']) {
            $this->retData['code'] = $modRet['code'];
            $this->retData['msg'] = $modRet['msg'];
            $this->_output();
        }

        $this->_output();
    }

    /**
     * 添加产地
     *    $this->reqtData = array(
     *        'country_flag' => "string",
     *        'data' =>  array(‘zh’ => ‘中国’, ‘english’ => ‘china’, hk = >‘中國’, kr=>’중국’)
     *    );
     */
    public function add_goods_origin() {
        $this->load->model('m_goods_origin');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $add = [];
        $country_flag = $this->reqtData['country_flag'] ? strtolower(htmlentities(trim($this->reqtData['country_flag']))) : '';
        $data = $this->reqtData['data'];
        if (empty($data)) {
            $this->_output();
        }
        foreach ($data as $key => $value) {
            $row = $this->db->where(['country_flag'=> $country_flag, 'language' => $key])->get('mall_goods_origin')->row_array();
            if ($row) {
                continue;
            }
            if (empty($country_flag) || empty($key) || empty($value)) {
                continue;
            }
            $add[] = array (
                'country_flag' => $country_flag,
                'language' => $key ? strtolower(htmlentities(trim($key))) : '',
                'name' => $value ? htmlentities(trim($value)) : '',
            );
        }
        if ($add) {
            $status = $this->db->insert_batch('mall_goods_origin', $add);
            if ($status) {
                $this->retData['code'] = 200;
                $this->retData['msg']  = 'succeed';
            }
        }
        $this->_output();
    }
    /**
     * 修改产地
     *    $this->reqtData = array(
     *        'country_flag' => "string",
     *        'data' =>  array(‘zh’ => ‘中国’, ‘english’ => ‘china’, hk = >‘中國’, kr=>’중국’)
     *    );
     */
    public function update_goods_origin() {
        $this->load->model('m_goods_origin');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $add = [];
        $country_flag = $this->reqtData['country_flag'] ? strtolower(htmlentities(trim($this->reqtData['country_flag']))) : '';
        $data = $this->reqtData['data'];
        if (empty($data)) {
            $this->_output();
        }
        try {
            foreach ($data as $key => $value) {
                if (empty($country_flag) || empty($key) || empty($value)) {
                    continue;
                }
                $this->db->update('mall_goods_origin', 
                    ['name' => htmlentities(trim($value))],
                    ['country_flag'=> $country_flag, 'language' => $key]);
            }
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
            $this->_output();
        } catch(Exception $e) {
            $this->_output();
        }
    }
 /**
     * erp添加订单备注调用
     * User：Ckf
     * Date：2017/01/17
     */
    public function add_order_remark() {

        $this->load->model('tb_trade_orders');

        $orderId = $this->reqtData['order_id'] ? : 0;

        $attr = array();
        if (isset($this->reqtData['order_id'])) {
            $attr['order_id'] = $this->reqtData['order_id'];
        }
        if (isset($this->reqtData['type'])) {
            $attr['type'] = $this->reqtData['type'];
        }
        if (isset($this->reqtData['remark'])) {
            $attr['remark'] = $this->reqtData['remark'];
        }
        if (isset($this->reqtData['admin_id'])) {
            $attr['admin_id'] = $this->reqtData['admin_id'];
        }
        if (isset($this->reqtData['operator'])) {
            $attr['operator'] = $this->reqtData['operator'];
        }
        if (isset($this->reqtData['created_at'])) {
            $attr['created_at'] = $this->reqtData['created_at'];
        }

        if (empty($attr)) {
            $this->_output();
        }

        // 查询订单是否存在
        if (!$this->tb_trade_orders->getOrderInfo($orderId, array('order_id'))) {
            $this->retData['code'] = 1100;
            $this->retData['msg'] = "order {$orderId} is not exist";
            $this->_output();
        }

        // 添加备注信息
        $res = $this->db->insert('trade_order_remark_record', $attr);
        if (!$res) {
            $this->retData['code'] = 1103;
            $this->retData['msg'] = "add remark failed";
            $this->_output();
        }

        $this->_output();
    }
      /**
     * 添加广告
     *    $this->reqtData = array(
     *        'data' =>  array() 添加数据
     *    );
     */
    public function add_mall_goods_ads() {
        $this->load->model('tb_mall_goods_ads');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $data = $this->reqtData['data'];
        $reset_sort = $data['reset_sort'];
        unset($data['reset_sort']);
        if (empty($data)) {
            $this->_output();
        }
        foreach ($data as $key => $value) {
            $data[$key] = htmlentities(trim($value));
        }
        $id = $this->tb_mall_goods_ads->add_ads($data);
        if ($id) {
            if (!empty($reset_sort)) {
                foreach ($reset_sort as $k => $v) {
                    $this->tb_mall_goods_ads->edit_ads(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
                }
            }
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }
    /**
     * 修改广告
     *    $this->reqtData = array(
     *        'data' =>  array(),修改数据
     *        'where'=>  array() 条件
     *    );
     */

    public function edit_mall_goods_ads() {
        $this->load->model('tb_mall_goods_ads');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $data  = $this->reqtData['data'];
        $id    = (int)$data['id'];
        unset($data['id']);
        $reset_sort = $data['reset_sort'];
        unset($data['reset_sort']);
        if (empty($data) || empty($id)) {
            $this->_output();
        }
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $data[$key] = htmlentities(trim($value));
            }
        }
        $data_status = $this->tb_mall_goods_ads->edit_ads(array('id' => $id), $data);
        $sort_num    = 0;
        if (!empty($reset_sort)) {
            foreach ($reset_sort as $k => $v) {
                $status = $this->tb_mall_goods_ads->edit_ads(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
                $status && ++$sort_num;
            }
        }
        if ($data_status || $sort_num > 0) {
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }

    /**
     * 修改广告
     *    $this->reqtData = array(
     *        'data' =>  array(),修改数据
     *    );
     */

    public function sort_mall_goods_ads() {
        $this->load->model('tb_mall_goods_ads');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $reset_sort = $this->reqtData['data']['reset_sort'];
        if (empty($reset_sort)) {
            $this->_output();
        }
        foreach ($reset_sort as $k => $v) {
            $this->tb_mall_goods_ads->edit_ads(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
        }
        $this->retData['code'] = 200;
        $this->retData['msg']  = 'succeed';
        $this->_output();
    }
    /**
     * 删除广告
     *    $this->reqtData = array(
     *        'where'=>  array() 条件
     *    );
     */
    public function del_mall_goods_ads() {
        $this->load->model('tb_mall_goods_ads');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $data  = $this->reqtData['data'];
        $id    = (int)$data['id'];
        unset($data['id']);
        $reset_sort = $this->reqtData['data']['reset_sort'];
        if (empty($id)) {
            $this->_output();
        }
        $del_status = $this->tb_mall_goods_ads->del_ads(['id' => $id]);
        $sort_num   = 0;
        if (!empty($reset_sort)) {
            foreach ($reset_sort as $k => $v) {
                $status = $this->tb_mall_goods_ads->edit_ads(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
                $status && ++$sort_num;
            }
        }
        if ($del_status || $sort_num > 0) {
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }



    /**
     * 添加热搜
     *    $this->reqtData = array(
     *        'data' =>  array() 添加数据
     *    );
     */
    public function add_mall_goods_keyword() {
        $this->load->model('tb_mall_goods_keyword');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $data = $this->reqtData['data'];
        $reset_sort = $data['reset_sort'];
        unset($data['reset_sort']);
        if (empty($data)) {
           $this->_output();
        }
        foreach ($data as $key => $value) {
            $data[$key] = htmlentities(trim($value));
        }
        $id = $this->tb_mall_goods_keyword->add_keyword($data);
        if ($id) {
            if (!empty($reset_sort)) {
                foreach ($reset_sort as $k => $v) {
                    $this->tb_mall_goods_keyword->edit_keyword(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
                }
            }
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }
    /**
     * 修改热搜
     *    $this->reqtData = array(
     *        'data' =>  array(),修改数据
     *        'where'=>  array() 条件
     *    );
     */

    public function edit_mall_goods_keyword() {
        $this->load->model('tb_mall_goods_keyword');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $data  = $this->reqtData['data'];
        $id    = (int)$data['id'];
        unset($data['id']);
        $reset_sort = $data['reset_sort'];
        unset($data['reset_sort']);
        if (empty($data) || empty($id)) {
            $this->_output();
        }
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $data[$key] = htmlentities(trim($value));
            }
        }
        $data_status = $this->tb_mall_goods_keyword->edit_keyword(array('id' => $id), $data);
        $sort_num    = 0;
        if (!empty($reset_sort)) {
            foreach ($reset_sort as $k => $v) {
                $status = $this->tb_mall_goods_keyword->edit_keyword(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
                $status && ++$sort_num;
            }
        }
        if ($data_status || $sort_num > 0) {
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }
    /**
     * 删除热搜
     *    $this->reqtData = array(
     *        'where'=>  array() 条件
     *    );
     */
    public function del_mall_goods_keyword() {
        $this->load->model('tb_mall_goods_keyword');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $data  = $this->reqtData['data'];
        $id    = (int)$data['id'];
        unset($data['id']);
        $reset_sort = $this->reqtData['data']['reset_sort'];
        if (empty($id)) {
            $this->_output();
        }
        $del_status = $this->tb_mall_goods_keyword->del_keyword(['id' => $id]);
        $sort_num   = 0;
        if (!empty($reset_sort)) {
            foreach ($reset_sort as $k => $v) {
                $status = $this->tb_mall_goods_keyword->edit_keyword(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
                $status && ++$sort_num;
            }
        }
        if ($del_status || $sort_num > 0) {
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }

    /**
     * 修改排序
     *    $this->reqtData = array(
     *        'data' =>  array(),修改数据
     *    );
     */

    public function sort_mall_goods_keyword() {
        $this->load->model('tb_mall_goods_keyword');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $reset_sort = $this->reqtData['data']['reset_sort'];
        if (empty($reset_sort)) {
            $this->_output();
        }
        foreach ($reset_sort as $k => $v) {
            $this->tb_mall_goods_keyword->edit_keyword(['id'=> intval($v['id'])], ['sort_order' => intval($v['order'])]);
        }
        $this->retData['code'] = 200;
        $this->retData['msg']  = 'succeed';
        $this->_output();
    }

    /**
     * 添加套餐
     *@param goods_sn_main sku string
     *@return array 200 成功， 100失败
     */

    public function add_mall_goods_combo() {
        $this->load->model('tb_mall_goods_main');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $goods_sn_main = $this->reqtData['data'];
        $goods_sn_main = array_filter($goods_sn_main);
        $goods_num = $this->tb_mall_goods_main->update_combo($goods_sn_main, 1);
        if ($goods_num > 0) {
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        } else {
            $this->retData['msg']  = 'add failure';
        }
        $this->_output();
    }


    /**
     * 删除套餐
     *@param goods_sn_main sku array()
     *@return array 200 成功， 100失败
     */

    public function del_mall_goods_combo() {
        $this->load->model('tb_mall_goods_main');
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $goods_sn_main = $this->reqtData['data'];
        if (empty($goods_sn_main)) {
            $this->_output();
        }
        $goods_sn_main = array_filter($goods_sn_main);
        $combo_status = $this->tb_mall_goods_main->update_combo($goods_sn_main);
        if ($combo_status > 0) {
            $this->retData['code'] = 200;
            $this->retData['msg']  = 'succeed';
        }
        $this->_output();
    }

    /**
     * 返回套餐列表
     *@param array array('language_id' => 地区, goods_sn_main => sku, 'goods_name' => 商品名, range => 套餐区间, supplier_id => 供应商ID,page => 页数, 'is_on_sale'  => 是否上架 )
     *@return array
     */
    public function get_mall_goods_combo() {
        $this->load->model('tb_mall_goods_main');
        $where = $this->reqtData['data'];
        $where = array_map('trim', $where);
        $where = array_map('htmlentities', $where);
        $where = array_filter($where);
        $where['page'] = empty($where['page']) ? 1 : intval($where['page']);
        if (in_array($where['is_on_sale'], ['1', '2'])) {
            $where['is_on_sale'] = $where['is_on_sale'] == 2 ? 0 : intval($where['is_on_sale']);
        } else {
            unset($where['is_on_sale']);
        }
        $this->retData['code'] = 200;
        $this->retData['msg']  = [];
        if (empty($where)) {
            $this->_output();
        }
        $ret = $this->tb_mall_goods_main->get_goods_combo($where);
        $this->retData['msg'] = $ret;
        $this->_output();
    }
    /**
     * 返回SKU所有子商品的库存
     *@author Baker
     *@date 2017-7-4:
     */

    public function get_goods_number() {
        $this->load->model('tb_mall_goods');
        $goods_sn_main = htmlentities(trim($this->reqtData['data']['goods_sn_main']));
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $this->retData['data'] = [];
        if (empty($goods_sn_main)) {
            $this->_output();
        }
        $list = $this->tb_mall_goods->get_list('goods_sn, product_id', ['goods_sn_main' => $goods_sn_main]);
        if (empty($list)) {
            $this->_output();
        }
        $data = [];
        foreach ($list as $k => $v) {
            $data[$v['goods_sn']] = $this->tb_mall_goods->get_number($v['product_id']);
        }
        $this->retData['code'] = 200;
        $this->retData['msg']  = 'success';
        $this->retData['data'] = $data;
        $this->_output();
    }

    /**
     * @desc:批量查询库存
     * @auth: tico.wong
     * @date:2017-07-07
     * 传入array(goods_sn,goods_sn,...)
     */
    public function get_inventorys()
    {
        $this->load->model('tb_mall_goods');
        //取传入的SKU列表,传的是goods_sn_main
        $goods_sn_list = $this->reqtData['data']['list'];
        $this->retData['code'] = 100;
        $this->retData['msg']  = 'failure';
        $this->retData['data'] = [];
        if (empty($goods_sn_list)) {
            $this->_output();
        }
        $list = [];
        //根据SKU列表取SKU详情
        foreach($goods_sn_list as $k=>$v)
        {
            $tmp = $this->tb_mall_goods->get_list('goods_sn_main, goods_sn, product_id', ['goods_sn_main' => $v]);
            if($tmp)
            {
                foreach($tmp as $t)
                {
                    $list[] = $t;
                }
            }
        }
        if (empty($list)) {
            $this->_output();
        }
        $data = [];
        //根据SKU取REDIS内的库存
        foreach ($list as $k => $v) {
            $data[$v['goods_sn_main']][$v['goods_sn']] = $this->tb_mall_goods->get_number($v['product_id']);
        }
        $this->retData['code'] = 200;
        $this->retData['msg']  = 'success';
        $this->retData['data'] = $data;
        $this->_output();
    }

}
