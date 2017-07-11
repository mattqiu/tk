<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class bulletin_board extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_news');
    }

    public function index()
    {
        $this->_viewData['title'] = lang('Bulletin_title');
        $this->load->model("tb_bulletin_board");
        //参数处理
        $page = intval($this->input->get('page', true));
        $page_size = intval($this->input->get('page_size', true));
        $page = empty($page) ? 1 : $page;
        $page_size = empty($page_size) ? 10 : $page_size;
        $params = [];
        $params['page'] = $page;
        $params['page_size'] = $page_size;

        $key_prefix = config_item("redis_key")['bulletin_board_index'];
        $key =$key_prefix.$this->_userInfo['id'].":".$page;

        if(REDIS_STOP == 0) {
            if ($page == 1) {
                $res = $this->tb_bulletin_board->redis_get($key);
                $res = json_decode($res,true);
            } else {
                $res = [];
            }

            if ($res && is_array($res) && isset($res['result']) && isset($res['unread_count']) && isset($res['counts'])) {

                $is_read_list = [];
                foreach($res['result'] as $v) {
                    $is_read_list[$v['id']] = $v['is_read'];
                }
                $new_result = [];
                foreach($res['result'] as $v) {
                    $new_result[] = $this->tb_bulletin_board->get_row($v['id']);
                }
                foreach($new_result as &$v) {
                    $v['is_read'] = $is_read_list[$v['id']];
                }
                //echo "走缓存";
                $result = $new_result;
                $unread_count = $res['unread_count'];
                $counts = $res['counts'];
            } else {
                //echo "走数据库";

                $country = $this->_viewData['curLan'];
                //获取数据
                $counts = $this->tb_bulletin_board->get_list($params, $this->_userInfo['parent_id'], $this->_viewData['curLan'], true, true);
                $total_page = ceil($counts / $page_size);
                $page = ($page > $total_page) ? $total_page : $page;
                $result = $this->tb_bulletin_board->get_list($params, $this->_userInfo['parent_id'], $this->_viewData['curLan'], true, false);

                //处理是否读?
                if (!empty($result)) {
                    $result = $this->tb_bulletin_board->get_read_not($result, $this->_userInfo['id'], $country, $this->_userInfo['parent_id']);
                }
                $unread_count = $this->tb_bulletin_board->get_unread_counts($this->_userInfo['id'],$this->_viewData['curLan'],$this->_userInfo['parent_id']);
                $ids = [];
                foreach($result as $v) {
                    $ids[] = array('id'=>$v['id'],'is_read'=>$v['is_read']);
                }
                $res = array('result'=>$ids,"unread_count"=>$unread_count,"counts"=>$counts);
                if ($page == 1) {
                    $this->tb_bulletin_board->redis_set($key,json_encode($res),config_item("redis_expire")['bulletin_board_index']);
                }

            }
        }

        $this->_viewData['unread_count'] = $unread_count;
        $this->_viewData['lists'] = $result;
        $url = 'ucenter/bulletin_board';
        $this->_viewData['pager'] = $this->tb_bulletin_board->get_pager($url, $params, $counts);
        parent::index();
    }




}