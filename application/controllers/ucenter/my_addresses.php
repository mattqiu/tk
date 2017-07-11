<?php

/**
 * @description my address
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/1/12
 * Time: 10:22
 * Modify by tico.wong 2017.02.25
 */
class my_addresses extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_trade_user_address');
        $this->load->model('service_message_model');
    }
    public function test()
    {
        $this->load->model("tb_cash_account_log_x");
        $this->load->model("o_bonus");
        $data = [
            ['uid'=>1382193077,'money'=>100],
            ['uid'=>1382681670,'money'=>78]
        ];
        $this->o_bonus->assign_bonus_batch($data,6);
        
    }
    /**
     * @author brady.wang
     * @description my address
     *
     */
    public function index()
    {
        //website title
        $this->_viewData['title'] = lang('my_addresses');
        $this->_viewData['curControlName'] = __CLASS__;
        //user information
        $user_info = $this->_viewData['userInfo'];
        //check login
        if (empty($user_info)) {
            redirect(base_url('login'));
        }
        $user_id = $user_info['id'];
        $country_id = $this->_userInfo['country_id'];
        $location = $this->_viewData['curLocation_id'];
        //user's country and location
        $this->_viewData['country_id'] = $country_id;
        $this->_viewData['location'] = $location;
        //addres list
        $address = $this->tb_trade_user_address->get_all_address_by_type($user_id, $country_id, $this->_viewData['sale_country']);
        $address_edit = [];
        if(!empty($address)) {
            foreach ($address as &$v) {
                if(!empty($v)) {
                    foreach ($v as $key =>&$value) {
                        if ($value['country'] == '840') {
                            $phone_us = array();
                            $phone_temp = str_replace("-",'',$value['phone']);
                            $phone_us[1] = substr($phone_temp,0,3);
                            $phone_us[2] = substr($phone_temp,3,3);
                            $phone_us[3] = substr($phone_temp,6,4);
                            $phone_us[1] = $phone_us[1] == false ? '' : $phone_us[1];
                            $phone_us[2] = $phone_us[2] == false ? '' : $phone_us[2];
                            $phone_us[3] = $phone_us[3] == false ? '' : $phone_us[3];
                            $value['phone_us'] = $phone_us;



                            $temp_region = explode(' ',$value['region']);
                            $new_region = '';
                            for( $i = count($temp_region) - 1; $i >=0 ;$i--) {
                               $new_region .=" ". $temp_region[$i];
                            }
                            $value['region'] = $new_region;
                        } else if ($value['country'] == '410') {
                            $phone_kr = array();
                            $phone_kr_temp = str_replace("-",'',$value['phone']);
                            $phone_kr[1] = substr($phone_kr_temp,0,3);
                            $phone_kr[2] = substr($phone_kr_temp,3,4);
                            $phone_kr[3] = substr($phone_kr_temp,7,4);
                            $phone_kr[1] = $phone_kr[1] == false ?  '' : $phone_kr[1];
                            $phone_kr[2] = $phone_kr[2] == false ?  '' : $phone_kr[2];
                            $phone_kr[3] = $phone_kr[3] == false ?  '' : $phone_kr[3];
                            $value['phone_kr'] = $phone_kr;
                        }

                        $address_edit[$value['id']] = $value;
                    }
                }
            }
        }
        $this->_viewData['address_edit'] = $address_edit;
        $this->_viewData['address'] = $address;
        $country_area = [
            1 => '156',//中国
            2 => '840',//美国
            3 => '410',//韩国
            4 => '344',//韩国
            0 => '000',//其他地区
        ];
        $this->_viewData['country_area'] = $country_area;
        //地址编辑的时候用到
        parent::index();
    }


    public function del()
    {
        //获取数据
        $id = intval($this->input->get_post('id', true));

        try {
            if(empty($id)) {
                throw new Exception("40001000"); //系统错误
            }

            //查看是否有该地址
//            $address_res = $this->tb_trade_user_address->get_by_where([
//                'select' => 'id,uid',
//                'where' => [
//                    'id' => $id
//                ],
//                'limit' => 1
//            ], false, true);
            $address_res = $this->tb_trade_user_address->get_one("*",["id"=>$id]);
            if(empty($address_res)) {
                throw new Exception("10501008");
            }

            //删除
            $del_res = $this->tb_trade_user_address->del_address_by_id($id);
            if($del_res <= 0) {
                throw new Exception("10501009");
            } else {
                $output['message'] = lang('delete_success');
                $this->service_message_model->success_response($output);
            }
        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }

    }

    /**
     * @author brady.wang
     * @description add user address
     * @retrun array
     */
    public function add_address()
    {
        header('Content-type: application/json');
        $output = ['error' => true, 'message' => ''];
        //永远不要相信用户输入
        $data['country'] = $this->input->post('country', true);
        $data['addr_lv2'] = trim($this->input->post('addr_lv2', true));
        $data['addr_lv3'] = trim($this->input->post('addr_lv3', true));
        $data['addr_lv4'] = trim($this->input->post('addr_lv4', true));
        $data['addr_lv5'] = trim($this->input->post('addr_lv5', true));
        $data['city'] = trim($this->input->post('city', true));
        $data['consignee'] = trim($this->input->post('consignee', true));
        $data['phone'] = trim($this->input->post('phone', true));
        $data['reserve_num'] = trim($this->input->post('reserve_num', true));
        $data['zip_code'] = trim($this->input->post('zip_code', true));
        $data['customs_clearance'] = trim($this->input->post('customs_clearance', true));
        $data['first_name'] = trim($this->input->post('first_name', true));
        $data['last_name'] = trim($this->input->post('last_name', true));
        $data['address_detail'] = trim($this->input->post('address_detail'));
        $id = intval($this->input->post('id', true));
        try {
            if(empty($this->_userInfo)) {
                throw new Exception("10501010");
            }
            $data['uid'] = $uid = $this->_userInfo['id'];
            //新增
            $action = 'add';
            if(empty($id)) {
                $data['is_default'] = 0;
            } else {
                $action = 'edit';
                $data['id'] = $id;
            }
            //参数验证
            if(empty($data)) {
                throw new Exception(); //无参数
            }

//            if(empty($data['country'])) {
//                throw new Exception("10501011");
//            }
            if (!in_array($data['country'],['840','410','344','156','000'])) {
                throw new Exception();
            }

            //新版验证
            // 1、中国区：手机号码不超过11位，固定电话不超过20位，邮编不超过16位。
            // 2、美国区：手机号码不超过16位，固定电话不超过20位，邮编不超过16位。（允许输入破节号“-”）
            // 3、香港区：手机号码不超过11位，固定电话不超过20位，邮编不超过16位。
            // 4、韩国区：手机号码不超过16位，固定电话不超过20位，邮编不超过16位。（允许输入破节号“-”）（韩国的电话类似这样的 	010-2837-3379 、 010-3000-8927，）
            // 5、其他区：手机号码不超过16位，固定电话不超过20位，邮编不超过16位。（允许输入破节号“-”）
            $this->load->model('m_validate');
            if (mb_strlen($data['consignee']) > 255) {
                $data['consignee'] = mb_substr($data['consignee'],0,255);
            }
            if ($data['country'] == 156) {
                if ($data['addr_lv2'] == 81) {
                    $this->m_validate->verify_addr_for_hk($data);
                } else {
                    $this->m_validate->verify_addr_for_china($data);
                }
            } elseif($data['country'] == 840) {
                $data['consignee'] = $data['first_name'] . ' ' . $data['last_name'];
                $this->m_validate->verify_addr_for_us($data);
            }elseif($data['country'] == 410) {
                $this->m_validate->verify_addr_for_kr($data);
            }elseif($data['country'] == '000') {
                $this->m_validate->verify_addr_for_other($data);
            }


            $this->load->model("m_trade");
            if($action == 'add') {
                $this->load->model("tb_trade_user_address");
                //限制收货地址每个语言的个数上限为5个
                if($data['country'] == 156) {
                    if($data['addr_lv2'] == 81) {
//                        $count = $this->db->from('trade_user_address')->where('uid', $uid)->where(array('country' => $data['country'], 'addr_lv2 ' => '81'))->count_all_results();
                        $where = ["uid"=>$uid,"country"=>$data['country'],"addr_lv2"=>'81'];
                        $count = $this->tb_trade_user_address->get_counts($where);
                    } else {
//                        $count = $this->db->from('trade_user_address')->where('uid', $uid)->where(array('country' => $data['country'], 'addr_lv2 !=' => '81'))->count_all_results();
                        $where = ["uid"=>$uid,"country"=>$data['country'],"addr_lv2 !="=>'81'];
                        $count = $this->tb_trade_user_address->get_counts($where);
                    }
                } else {
//                    $count = $this->db->from('trade_user_address')->where('uid', $uid)->where(array('country' => $data['country']))->count_all_results();
                    $where = ["uid"=>$uid,"country"=>$data['country']];
                    $count = $this->tb_trade_user_address->get_counts($where);
                }

                if($count >= 5) {
                    throw new Exception("10501018");
                }

                $ret = $this->m_trade->add_deliver_address($data);
                if(!$ret) {
                    throw new Exception("10501019");//添加地址失败
                }
                $output['message'] = lang("orderid_ture");
                $this->service_message_model->success_response($output);
            } else {
                //验证地址是否存在
//                $address_info = $this->tb_trade_user_address->get_by_where([
//                    'select' => 'id,uid',
//                    'where' => [
//                        'id' => $id,
//                    ],
//                    'limit' => 1
//                ], false, true);
                $address_info = $this->tb_trade_user_address->get_one("*",["id"=>$id]);
                if(empty($address_info)) {
                    throw new Exception("10501008");//地址不存在
                }
                if($address_info['uid'] !== $uid) {
                    throw new Exception("10501020");
                }

                $ret = $this->m_trade->edit_deliver_address($data, $uid);
                if(!$ret) {
                    throw new Exception("10501021");
                } else {
                    $output['error'] = true;
                    $output['message'] = lang("update_success");
                    $this->service_message_model->success_response($output);
                }
            }

        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }
    }
}