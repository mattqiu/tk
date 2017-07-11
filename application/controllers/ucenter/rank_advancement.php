<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 15-3-26
 * Time: 下午2:06
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class rank_advancement extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->_viewData['title'] = lang('rank_advancement');
        parent::index();
    }

}