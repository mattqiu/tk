<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/5/22
 * Time: 16:46
 */
class incentive_system_management extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model("tb_bonus_system_management");
    }


    public function index(){
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['language_id'] = isset($searchData['language_id'])?$searchData['language_id']:'';
        $page_size = 10;

        if(empty($searchData['language_id'])){
            $searchData['language_id']= 2;
        }
        $this->_viewData['list'] = $this->doDataProcess($this->tb_bonus_system_management->getList($searchData,$page_size),20);
        $this->_viewData['title'] = lang('incentive_system_management');
        //语言列表
        $this->_viewData['lang_all']=$this->m_global->getLangList();
        $this->_viewData['language_id']=$searchData['language_id'];

        $total_rows = $this->tb_bonus_system_management->getTotal($searchData);
        $total_page = ceil($total_rows/$page_size);
        $page = ($searchData['page'] > $total_page) ? $total_page : $searchData['page'];
        $url = 'admin/incentive_system_management';

        $pager = $this->tb_bonus_system_management->get_pager($url, ['language_id'=>$searchData['language_id'],'page' => $page, 'page_size' => $page_size], $total_rows, true);
        $this->_viewData['pager']  = $pager;
        parent::index('admin/');
    }


    /**
     * 数据处理
     * @param $arr
     * @param $num  截取中文字符长度
     * @return mixed
     */
    private function doDataProcess($arr,$num){
        foreach($arr as $k=>$v){
            $arr[$k]['status'] = $v['status'] == 1?"显示":"不显示";
            $total = mb_strlen($v['content'],'utf-8');
            if($total>$num){
                $arr[$k]['content'] = mb_substr($v['content'],0,$num,'utf-8')."......";
            }else{
                $arr[$k]['content'] = mb_substr($v['content'],0,$num,'utf-8');
            }
        }
        return $arr;
    }

}