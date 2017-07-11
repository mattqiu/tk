<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Announce extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        parent::index('new/','','header_2');
    }
}
