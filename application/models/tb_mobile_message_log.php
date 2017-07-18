<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/1/6
 * Time: 14:14
 */
class tb_mobile_message_log extends MY_Model
{
    protected $table = "mobile_message_log";
    protected $table_name = "mobile_message_log";
    protected $expire_time = 1800; //验证码过期时间30分钟
    protected $code_counts = 4;

    public function __construct()
    {
        $this->expire_time = config_item("verify_code_expire_time")['mobile'];
        $this->code_counts = config_item("verify_code_counts")['mobile'];
        parent::__construct();
    }

    /**
     * @param $code 验证码
     */
    public function add_code($mobile, $code, $status, $logs = '')
    {
        if(empty($code) || empty($mobile)) {
            return false;
        }
        $data = ['code' => "$code", 'mobile' => "$mobile", 'code' => "$code", 'status' => "$status", 'create_time' => time(), 'logs' => $logs];
        $this->db->insert($this->table, $data);
        $affected_rows = $this->db->affected_rows();
        if($affected_rows > 0) {
            return $affected_rows;
        } else {
            return false;
        }
    }



    /**
     * @author brady.wang
     * @desc 给用户发送手机验证码
     * @param $mobile 手机号码
     * @param int $action_id 模板情景id
     * @return array
     */
    public function send_mobile_code($mobile, $action_id = 4)
    {

        $code = generate_verify_code($this->code_counts);
        $action_id = $action_id ? $action_id : 4;
        include_once APPPATH . '/third_party/taobao/TopSdk.php';
        $phone_cfg = config_item('phone_cfg');
        $phone_cfg_info = $phone_cfg[$action_id];
        $c = new TopClient;
        $c->appkey = "23362350";
        $c->secretKey = "7615d82fa94a199d8ad2c303f2e6c9e6";
        $c->format = "json";
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsParam(str_replace('@@@@', $code, $phone_cfg_info['param']));
        $req->setSmsFreeSignName($phone_cfg_info['signature']);
        $req->setRecNum("$mobile");
        $req->setSmsTemplateCode($phone_cfg_info['template']);
        $resp = $c->execute($req);

        $output = ['error' => true, 'msg' => ''];
        if(isset($resp->result->success)) {
            //发送成功
            $logs = json_encode($resp);
            $this->add_code($mobile, $code, 0, $logs);
            $output['error'] = false;
            $output['msg'] = 'success';
            return $output;
        } else {
            //记录发送错误记录 存入数据库,并且返回错误
            $logs = json_encode($resp);
            $this->add_code($mobile, $code, 1, $logs);
            $output['msg'] = $resp->sub_msg;
            if ($resp->sub_code == "isv.BUSINESS_LIMIT_CONTROL") {
                $output['msg'] = lang('send_code_frequency');
            } else {
                $output['msg'] = $resp->sub_msg;
            }

            if ($resp->sub_code == "isv.BUSINESS_LIMIT_CONTROL") {
                $output['msg'] = lang('send_code_frequency');
            } elseif($resp->sub_code == 'isv.MOBILE_NUMBER_ILLEGAL') {
                $output['msg'] = lang('mobile_format_error');
            } else {
                $output['msg'] = lang('mobile_system_update');
            }
            return $output;
        }
    }

    public function verify_mobile_code($mobile, $code)
    {
        $res = $this->db->from($this->table)
            ->select("id,mobile,code,create_time")
            ->where(array('mobile' => $mobile, 'status' => '0'))
            ->order_by('id desc')
            ->limit(1)
            ->get()
            ->row_array();

        $output = ['error' => true, 'message' => ''];
        if(empty($res)) {
            throw new Exception("10501005");//验证码错误
        } else {
            if($code !== $res['code']) {
                throw new Exception("10501005");//验证码错误
            }

            $time = time();
            if($time - $res['create_time'] > $this->expire_time) {
                throw new Exception("10501006");//验证码过期
            }
        }
    }

    /**
     * 验证成功 删除该用户的手机号码
     * @param $mobile 手机号
     */
    public function delete_code($mobile)
    {
        $this->db->delete($this->table, array('mobile' => $mobile, 'status' => '0'));
        return $this->db->affected_rows();
    }



}