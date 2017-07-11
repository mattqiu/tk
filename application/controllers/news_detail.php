<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_detail extends MY_Controller {

	
  	public function index($id)
{
    $this->load->model('m_news');
    $news = $this->m_news->getOneNews($id);
    if(!$news){
        redirect('news');
    }
    $this->_viewData['info'] = $news;
    $hots = $this->m_news->getHotNews(array('page'=>1),2,$this->_curLanguage);
    $this->_viewData['hots'] = $hots;
    parent::index('new/');
}

    public function zh($article){

        parent::index('new/','zh'.$article);
    }

    public function english($article){

        parent::index('new/','english'.$article);
    }

    public function do_detail($id){

    }

}
