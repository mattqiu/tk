<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class video_and_call extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->_viewData['title'] = lang('webinar_and_conference_calls');
        parent::index();
    }

}