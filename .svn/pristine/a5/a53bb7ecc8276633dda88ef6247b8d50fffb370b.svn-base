<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }
  	public function index()
  	{
        $this->load->model('m_news');

        $data = $this->m_news->getNewsList(array('page'=>1),10,$this->_curLanguage);
        $hots = $this->m_news->getHotNews(array('page'=>1),2,$this->_curLanguage);
        $this->_viewData['list'] = $data;
        foreach($hots as &$hot){
            if($this->_curLanguage == 'english'){
                $hot['content'] = mb_substr($hot['content'],0,100,'utf-8').'...';
            }else{
                $hot['content'] = mb_substr($hot['content'],0,29,'utf-8').'...';
            }
        }
        $this->_viewData['hots'] = $hots;

        parent::index('new/');
    }

}
