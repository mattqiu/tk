<?php
/**
 * Created by PhpStorm.
 * User: brady
 * Desc: 沃好api调用日志
 * Date: 2017/6/21
 * Time: 14:15
 */
class tb_logs_wohao_api extends  MY_Model
{
    protected $table = "logs_wohao_api";
    protected $table_name = "logs_wohao_api";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author brady.wang
     * @desc  创建日志
     * @param $uid 用户id
     * @param $url 接口地址
     * @return mixed 插入id
     */
    public function create_log($uid,$url)
    {
        $clientIp = get_real_ip();
        $data = array(
            'uid'=>$uid,
            'ip'=>$clientIp,
            'api'=>$url,
            'create_time'=>date("Y-m-d H:i:s",time())
        );
        return $this->add($data);
    }
}