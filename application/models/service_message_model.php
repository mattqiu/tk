<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2016/12/26
 * Time: 14:32
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class service_message_model extends MY_Model
{

    /**
     * 错误码定义
     * 格式ABBCCDDD
     * A:错误级别。0-处理成功，1-鉴权错误，2-业务异常，4-系统异常
     * BB:系统定义。00-未定义，01-商品，02-用户，03-支付，04-订单，05-后台...
     * CC: 产品线定义。01-tps
     * DDD:具体错误原因码
     */
    private $error_code = array(
        '40001000' => array('message' => "system_error_again"),//系统错误
        '40501001' => array('message' => "hacker"),//不允许访问
        '10501001' => array('message' => "please_input_mobile"),//请输入手机号码
        '10501002' => array('message' => "mobile_format_error"),//手机号码格式错误
        '10501003' => array('message' => "please_input_code"),//请输入验证码
        '10501004' => array('message' => "binding_mobile_failed"),//验证手机号失败
        '10501005' => array('message' => "mobile_code_error"),//验证码错误
        '10501006' => array('message' => "mobile_code_expire"),//验证码过期
        '10501007' => array('message' => "phone_has_been_userd"),//该手机号已被使用 address_not_exists
        //我的地址管理
        '10501008' => array('message' => "address_not_exists"),//地址不存在
        '10501009' => array('message' => "delete_failure"),//删除失败
        '10501010' => array('message' => "please_login_first"),//请登录
        '10501011' => array('message' => "input_country"),//请选择国家/地区
        '10501012' => array('message' => "checkout_validator_addr_lv2_cn"),//请选择省份/直辖市
        '10501013' => array('message' => "checkout_validator_address_detail"),//详细地址不能为空
        '10501014' => array('message' => "checkout_validator_consignee"),//收货人不能为空
        '10501015' => array('message' => "phone_not_null"),//手机号不能为空
        '10501016' => array('message' => "phone_check_length"),//手机号8-13位
        '10501017' => array('message' => "checkout_validator_customs_clearance"),//海关好不能为空
        '10501018' => array('message' => "user_addr_full"),//地址不能超过5个
        '10501019' => array('message' => "add_template_fail"),//添加地址失败
        '10501020' => array('message' => "access_deny"),//无权限
        '10501021' => array('message' => "modify_address_failed"),//地址修改失败
        '10501022' => array('message' => "address_phone_check_1"),//手机号码只能是6-20个数字
        '10501023' => array('message' => "address_detail_length_25"),//地址长度不能超过255

        //邮箱找回密码
        '10501024' => array('message' => "tps_pwd"),//请输入密码
        '10501025' => array('message' => "new_passwd_not_null"),//新密码不能为空
        '10501026' => array('message' => "new_passwd_rule"),//资金密码只能为6位数字
        '10501027' => array('message' => "enter_re_passwd"),//再次输入资金密码
        '10501028' => array('message' => "passwd_not_same"),//两次密码不一致
        '10501029' => array('message' => "enter_tps_passwd"),//输入tps密码
        '10501030' => array('message' => "phone_code_not_null"),//短信验证码不能为空
        '10501031' => array('message' => "phone_code_rule_error"),//验证码不符合规则
        '10501032' => array('message' => "email_code_not_nul"),//验证码不符合规则
        '10501033' => array('message' => "old_pwd_error"),//原密码错误
        '10501034' => array('message' => "send_again"),//操作太频繁
        '10501035' => array('message' => "tickets_send_fail"),//
        '10501036' => array('message' => "email_rule_error"),//
        '10501037' => array('message' => "please_get_code"),//请先获取验证码
        '10501038' => array('message' => "update_take_cash_pwd_error"),//更改失败
        '10501039' => array('message' => "please_bind_email_first"),//請先綁定郵箱
        '10501040' => array('message' => "funds_pwd_tip"),//
        '10501041' => array('message' => "tps_password_wrong"),//
        '10501042' => array('message' => "email_code_not_nul"),//验证码不能为空
        '10501043' => array('message' => "re_enter_new_mobile"),//请再次输入新手机号
        '10501044' => array('message' => "not_match_your_input"),//两次输入不一致
        '10501045' => array('message' => "new_mobile_not_null"),//手机号不能为空
        '105010406' => array('message' => "mobile_verify_not"),//手机号未认证


    );

    /**
     * @param $code 错误码
     * @return array 错误信息
     */
    function error_message($code)
    {
        $code = isset($this->error_code[$code]) ? $code : '40001000';
        $msg = array(
            'error' => true,
            'code' => $code,
            "success"=>false,
            'message' => lang($this->error_code[$code]['message']),
            'msg' => lang($this->error_code[$code]['message']),
            'data' => ''
        );
        return $msg;
    }

    /**
     * @param $code 错误码
     * @print json
     */
    function error_response($code)
    {
        header('Content-type: application/json');
        echo json_encode($this->error_message($code));
        exit;
    }

    /**
     * @param $data 成功输出的新
     * @return array 转换后输出的信息
     */
    function success_message($data)
    {
        $message = !empty($data['message']) ? $data['message'] : 'success';
        $msg = array(
            'error' => false,
            "success"=>true,
            'message' => $message,
            'msg' => $message,
            'data' => $data
        );
        return $msg;
    }

    /**
     * @param $data 成功输出 json
     */
    function success_response($data)
    {
        header('Content-type: application/json');
        echo json_encode($this->success_message($data));
        exit;
    }

}
