<?php
/**
 * User: jason
 * Date: 2016/2/20
 * Time: 17:16
 * 商品跨区运费
 */
class international_freight extends MY_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_trade_freight_fee_international');
    }

    public function index(){
        $this->_viewData['title'] = lang('international_freight');

        //地区列表
        $area_list = $this->db->query("select * from mall_goods_sale_country")->result_array();
        //国家码和名称映射表
        $country_map = array();
        $curLan = 'name_'.$this->_curLanguage;
        foreach($area_list as $item){
            $country_id = $item["country_id"];
            $country_map[$country_id] = $item[$curLan];
        }
        $this->_viewData['country_map'] = $country_map;

        $this->load->model('m_admin_helper');

        //获取查询参数
        $searchData = $this->input->get()?$this->input->get():array();

        //查询条件
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['goods_sn_main'] = isset($searchData['goods_sn_main'])?$searchData['goods_sn_main']:'';
        $searchData['country_id'] = isset($searchData['country_id'])?$searchData['country_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

        //获取记录
        $this->_viewData['list'] = $this->m_admin_helper->get_freight_log($searchData);

        $this->load->library('pagination');
        $url = 'admin/international_freight';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);

        //获取行数
        $config['total_rows'] = $this->m_admin_helper->get_freight_log_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    //按商品sku添加运费
    public function do_add_freight(){
        $data = $this->input->post();
        if($data == false){
            echo json_encode(array('success'=>false,'msg'=>lang('info_error')));
            exit();
        }

        //去空格处理
        $sku = trim($data['sku']);

        //sku不能为空
        if($sku == ''){
            echo json_encode(array('success'=>false,'msg'=>lang('please_input_right_sku')));
            exit();
        }

        //sku不存在
        $this->load->model("tb_mall_goods_main");
        $res = $this->tb_mall_goods_main->get_one_auto([
            "select"=>"goods_sn_main",
            "where"=>["goods_sn_main"=>$sku]
        ]);

        if($res == array()){
            echo json_encode(array('success'=>false,'msg'=>lang('please_input_right_sku')));
            exit();
        }

        //验证运费是否为数字
        foreach($data as $k =>$v){
            if($k == 'sku'){
                continue;
            }
            if(!is_numeric($v)){
                echo json_encode(array('success'=>false,'msg'=>lang('freight_must_is_number')));
                exit();
            }
        }

        $this->db->trans_begin();

        //提交前检测该SKU是否已经有运费，如果有,则新增的运费会替换掉原来的运费
        $old_freight = $this->db->query("select * from trade_freight_fee_international where goods_sn_main = {$data['sku']}")->result_array();
        if(!empty($old_freight)){
            $this->db->query("delete from trade_freight_fee_international where goods_sn_main = {$data['sku']}");
        }

        //增加运费
        foreach($data as $k =>$v)
        {
            if($k == 'sku'){
                continue;
            }

            if($v >= 0)
            {
                $insert_array = array(
                    'goods_sn_main'=>$data['sku'],
                    'country_id'=>$k,
                    'freight_fee'=>$v,
                    'currency'=>'USD',
                    'admin_id'=>$this->_adminInfo['id'],
                    'create_time'=>date('Y-m-d H:i:s',time())
                );
                $this->db->insert('trade_freight_fee_international',$insert_array);
            }
        }

        if ($this->db->trans_status() === FALSE)
        {
            echo json_encode(array('success'=>false,'msg'=>lang('info_error')));
            exit();
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('success'=>true,'msg'=>lang('submit_success')));
            exit();
        }
    }

    public function show_goods_name(){
        $sku = $this->input->post('sku');
        $sku = trim($sku);
        if($sku == ''){
            echo json_encode(array('success'=>false,'msg'=>lang('please_input_right_sku')));
            exit();
        }

        $language_id = get_cookie('curLan_id', true);

        $this->load->model("tb_mall_goods_main");
        //加了语言的商品名称
        $goods_name_for_language = $this->tb_mall_goods_main->get_one_auto([
            "select"=>"goods_name",
            "where"=>['goods_sn_main'=>$sku,'language_id'=>$language_id],
        ]);
        //未加语言的商品名称
        $goods_name = $this->tb_mall_goods_main->get_one_auto([
            "select"=>"goods_name",
            "where"=>['goods_sn_main'=>$sku],
        ]);

        if(!empty($goods_name_for_language))
        {
            echo json_encode(array('success'=>true,'msg'=>$goods_name_for_language['goods_name']));
            exit();
        }
        else
        {
            if(!empty($goods_name)){
                echo json_encode(array('success'=>true,'msg'=>$goods_name['goods_name']));
                exit();
            } else {
                echo json_encode(array('success'=>false,'msg'=>lang("not_find_this_goods_name")));
                exit();
            }
        }


    }

   // 获取修改的数据
    public function do_modify_getone(){
        $id = $this->input->post('id');
        $one = $this->tb_trade_freight_fee_international->findOne($id);
        if(empty($one)) {
            echo json_encode(array('success' => false, 'msg' => lang("not_find_this_product_freight")));
            exit();
        }else{
            echo json_encode(array('success' => true, 'one' => $one));
            exit;
        }

    }
    //修改运费
    public function do_modify_freight(){
        $one = $this->input->post();
        $id = isset($one['id'])?$one['id']:'';
        $sku = isset($one['sku'])?$one['sku']:'';
        $country = isset($one['country'])?$one['country']:'';
        $admin_order_deliver_fee = $one['admin_order_deliver_fee'];

        //判断sku是否存在
        if(empty($one)){
            echo json_encode(array('success' => false, 'msg' => lang("未发现该纪录")));
            exit();
        }
        if($admin_order_deliver_fee < 0){
            echo json_encode(array('success' => false, 'msg' => lang("product_freight_not_be")));
            exit();
        }
        if(!is_numeric($admin_order_deliver_fee)||strpos($admin_order_deliver_fee,".")!==false){
            echo json_encode(array('success' => false, 'msg' => lang("product_freight_not_be")));
            exit();
        }
        $res = $this->tb_trade_freight_fee_international->modifyOne($id,$admin_order_deliver_fee);
        if($res){
            echo json_encode(array('success' => true, 'msg' => lang("update_success")));
            exit();
        }else{
            echo json_encode(array('success' => false, 'msg' => lang("update_failure")));
            exit();
        }



    }
    //按商品sku删除运费
    public function do_delete_freight(){
        $id = $this->input->post('id');
        $one = $this->tb_trade_freight_fee_international->findOne($id);
        if(empty($one)) {
            echo json_encode(array('success' => false, 'msg' => lang("not_find_this_product_freight")));
            exit();
        }
        $res = $this->tb_trade_freight_fee_international->deldete($id);
        if($res){
            echo json_encode(array('success' => true, 'msg' => lang("delete_success")));
            exit();
        }else{
            echo json_encode(array('success' => false, 'msg' => lang("delete_failure")));
            exit();
        }

    }
}