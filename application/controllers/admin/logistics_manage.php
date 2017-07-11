<?php
/**
 * 测试
 * User: andy
 * Date: 2017/7/7
 * Time: 14:28
 */
class logistics_manage extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        if($this->_adminInfo['id']!=144){
            exit;
        }
    }

    public function index(){


        $logisticsMap = array(
            101 =>'韵达快递(101)_1_ip',
            102 =>'顺丰速运(102)_0',
            103 =>'中通快递(103)',
            104 =>'天天快递(104)',
            105 =>'申通快递(105)',
            106 =>'圆通速递(106)_1',
            107 =>'全峰快递(107)',
            110 =>'宅急送快递(110)',
            111 =>'优速快递(111)_1',
            112 =>'德邦(112)',
            113 =>'百世汇通(113)',
            114 =>'安能物流(114)',
            117 =>'邮政快递(117)',
            118 =>'国通快递(118)',
            120 => '快捷快递(120)',
            130 =>'百世快递(130)',

        );

        $data = $this->input->post();

        $keyword = isset($data['express_name']) ? trim($data['express_name']) : 0;
        $orderNo = isset($data['express_num'])  ? trim($data['express_num']) : 0 ;
        $return  = array();

        if($keyword && $orderNo )
        {
            $this->load->model('o_logistics_interface');

            $return = $this->o_logistics_interface->searchExpress($keyword,$orderNo);
        }

        $this->_viewData['searchData'] = $data;

        $this->_viewData['data'] = $return;

        $this->_viewData['title'] = '测试物流接口';
        $this->_viewData['map'] = $logisticsMap;

        parent::index('admin/');
    }

    public function get_express_info(){

        $data = $this->input->post();

        $keyword = trim($data['express_name']);
        $orderNo = trim($data['express_num']);

        $this->load->model('o_logistics_interface');

        $return = $this->o_logistics_interface->searchExpress($keyword,$orderNo);

        $this->_viewData['title'] = '测试物流接口';
        $this->_viewData['data'] = $return;

        die(json_encode(array('success'=>1,'data'=>$return)));

    }

}