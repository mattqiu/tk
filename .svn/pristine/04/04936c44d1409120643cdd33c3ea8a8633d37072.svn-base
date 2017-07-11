<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class week_leader_preview extends MY_Controller {

    public function __construct() {
        ini_set("display_errors","On");
        error_reporting(E_ALL);
        parent::__construct();
        $this->load->model('tb_week_leader_preview');
        //$this->m_global->checkPermission('blacklist',$this->_adminInfo);
    }

    /**
     * 周领导对等奖预览列表
     */
    public function index() {
        $this->_viewData['title'] = lang('week_leader_preview');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        $previewList = $this->tb_week_leader_preview->getWeekLeaderPreviewList($searchData, 12);
        $this->_viewData['list'] = $previewList;

        $this->load->library('pagination');
        $url = 'admin/week_leader_preview';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_week_leader_preview->getWeekLeaderPreviewRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = 12;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    /**
     * 周领导对等奖预览奖金详情
     */
    public function detail(){
        $this->load->model('m_user');
        $this->load->model('tb_cash_account_log_x');

        $this->_viewData['title'] = lang('week_leader_preview').lang('week_leader_detail');

        $uid = $this->input->get('uid');
        $date = date('Y-m-d', $this->input->get('date'));
        //用户周领导对等奖预览信息
        $preview = $this->tb_week_leader_preview->getWeekLeaderPreview($uid);
        $this->_viewData['preview'] = $preview;
        $uid = $preview['uid'];
        //获取用户底下两级用户id
        $children = $this->m_user->getChildMemberForLevelTwo($uid);

        //获取用户周领导对等奖涉及到所有下级账户上周的奖金详情
        $item_type = [1,2,3,4,5,6,7,8,16,23,24,25];
        $logList = $this->tb_cash_account_log_x->getCashAccountLogList($children, $item_type, $date);
        $temp = [];
        foreach ($logList as $val) {
            $temp[$val['uid']][] = $val;
        }
        $logList = $temp;

        $childList = [];
        $firChild = $this->m_user->getChildMembers($uid);
        foreach ($firChild as $item) {
            $firUid = $item['id'];
            $secIds = [];
            $childList[$firUid]['logs'] = isset($logList[$firUid]) ? $logList[$firUid] : [];
            $childList[$firUid]['child'] = [];
            $this->m_user->getChildMems($firUid, $secIds, 0);
            foreach ($secIds as $secId) {
                if (isset($logList[$secId])) {
                    $childList[$firUid]['child'][$secId] = $logList[$secId];
                }
            }
        }
        $this->_viewData['childList'] = $childList;
        //print_r($childList);exit;
        parent::index('admin/', 'week_leader_preview_detail');
    }

}