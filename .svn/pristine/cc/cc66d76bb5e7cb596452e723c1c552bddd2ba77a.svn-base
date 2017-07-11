<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Demo extends CI_Controller {

    public function index() {
        $curpic = $this->input->get('pic')?$this->input->get('pic'):'01.jpg';
        $pics = scandir(realpath('img/demo'));
        $picFormat = array();
        foreach($pics as $pic){
            if(!in_array($pic,array('.','..','.svn'))){
                $picFormat[] = $pic;
            }
        }
        sort($picFormat);
        foreach($picFormat as $k=>$v){
            if($curpic==$v){
                $curNum = $k;
            }
        }
        $lastKey = $curNum-1<0?count($picFormat)-1:$curNum-1;
        $nextKey = $curNum+1>=count($picFormat)?0:$curNum+1;
        $viewData['pic'] = $curpic;
        $viewData['pic_last'] = $picFormat[$lastKey];
        $viewData['pic_next'] = $picFormat[$nextKey];
        $this->load->view('demo/index',$viewData);
    }

}