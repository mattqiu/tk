<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2016/12/28
 * Time: 14:42
 */
class m_bulletin_unread extends MY_Model
{
    protected $table = "bulletin_unread";
    protected $table_name = "bulletin_unread";

    public function __construct()
    {
        parent::__construct();
    }
}