<?php
/**
 * User: jason
 * Date: 2016/2/17
 * Time: 16:30
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class execute_sql extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
//        echo 'closed';exit;
        if($this->_adminInfo['id']!=1){
            echo 'closed';
            exit;
        }
        $this->_viewData['title'] = lang('execute_sql');

        $this->load->model('m_admin_helper');

        $this->_viewData['admin_id'] = $this->_adminInfo['id'];

        //状态
        $status_list = array(
            '0' => lang('awaiting_processing'),
            '1' => lang('has_been_completed'),
            '2' => lang('refuse')
        );

        $this->_viewData['status_list'] = $status_list;

        //获取查询参数
        $searchData = $this->input->get()?$this->input->get():array();

        //查询条件
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'0';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

        //获取记录
        $list = $this->m_admin_helper->get_execute_sql_log($searchData);
        foreach($list as $k => $item){
            if($item['admin_id'] != 0) {
                $admin_id = $this->db->select('email')->where('id', $item['admin_id'])->get('admin_users')->row()->email;
                $list[$k]['admin_id'] = $admin_id;
            }
            if($item['audit_id'] != 0) {
                $audit_id = $this->db->select('email')->where('id', $item['audit_id'])->get('admin_users')->row()->email;
                $list[$k]['audit_id'] = $audit_id;
            }
        }
        $this->_viewData['list'] = $list;

        $this->load->library('pagination');
        $url = 'admin/execute_sql';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);

        //获取行数
        $config['total_rows'] = $this->m_admin_helper->get_execute_sql_log_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }


    //提交sql语句
    public function submit_execute_sql(){
        $sql = $this->input->post('sql');
        $remark = $this->input->post('remark');

        if(trim($sql) == ''){
            echo json_encode(array('success' => false, 'code' => 101,'msg'=>'请输入SQL'));
            exit;
        }
        if(trim($remark) == ''){
            echo json_encode(array('success' => false, 'code' => 101,'msg'=>'请输入备注信息'));
            exit;
        }

//        $this->db->trans_begin();
//
//        $query = mysql_query($sql);
//
//        if($query === false)
//        {
//            echo json_encode(array('success'=>false,'msg'=>'SQL语句错误'));
//            exit();
//        }
//
//        $row = mysql_affected_rows();
//
//        $result = false;
//
//
//        //阻止提交数据
//        if ($result === FALSE)
//        {
//            $this->db->trans_rollback();
//        }

        $insert_log = array(
            'sql'=>$sql,
            'remark'=>$remark,
            'status'=>0,
            'admin_id'=>$this->_adminInfo['id']
        );

        $this->db->insert('execute_sql_log',$insert_log);

        echo json_encode(array('success'=>true,'msg'=>"已提交审核"));
        exit();
    }


    //执行SQL语句
    public function do_execute_sql(){
        $id = $this->input->post('id');

        $this->db->trans_begin();
        $res = $this->db->query("select es.sql from execute_sql_log as es where id = $id")->row_array();
        if(empty($res)){
            echo json_encode(array('success'=>false,'msg'=>'没有对应的sql语句'));
            exit();
        }

        $sql = $res['sql'];
        $sql_list = explode(';',$sql);

        $total_row = 0;

        //执行多条sql
        foreach($sql_list as $sql)
        {
            //跳过空sql
            if(trim($sql) == ''){
                continue;
            }

            $query = $this->db->query($sql);
            if($query === false)
            {
                echo json_encode(array('success'=>false,'msg'=>'SQL语句错误'));
                exit();
            }
            
            //返回受影响行数
            $row = $this->db->affected_rows();
            $total_row += $row;
        }

        //修改状态和审核人
        $this->db->where("id",$id)->set("status",1)->set("audit_id",$this->_adminInfo['id'])->update('execute_sql_log');


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('success'=>true,'msg'=>"执行成功,有".$total_row."行受到影响"));
            exit();
        }
    }

    //驳回执行的sql语句
    public function cancel_execute_sql(){

        $id = $this->input->post('id');
        $refuse_reason = $this->input->post('remark');

        if(trim($refuse_reason)== '')
        {
            echo json_encode(array('success'=>false,'msg'=>"请输入驳回的原因"));
            exit();
        }

        $this->db->trans_begin();

        //修改状态和审核人
        $this->db->where("id",$id)->set("status",2)->set("audit_id",$this->_adminInfo['id'])->set('refuse_reason',$refuse_reason)->update('execute_sql_log');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('success'=>true,'msg'=>"驳回成功"));
            exit();
        }
    }
}