<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/5/17
 * Time: 18:15
 */
class tb_admin_users extends  MY_Model
{
    protected $table = "admin_users";
    protected $table_name = "admin_users";

    public function __construct()
    {
        parent::__construct();
    }
}