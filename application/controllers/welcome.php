<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('m_news');
        $this->load->model('m_global');

        //$this->_viewData['is_register'] = $this->m_global->checkDomainIsWWW();

        $data = $this->m_news->getNewsList(array('page'=>1),5,$this->_curLanguage);
        $union = $this->m_news->getHotNews(array('page'=>1),1,$this->_curLanguage);
        $this->_viewData['data'] = $data;
        if($this->_curLanguage != 'english'){
            $union[0]['content'] = mb_substr($union[0]['content'],0,29,'utf-8').'...';
        }else{
            $union[0]['content'] = mb_substr($union[0]['content'],0,100,'utf-8').'...';
        }
        $this->_viewData['union'] = $union;
        parent::index('new/');
    }

}