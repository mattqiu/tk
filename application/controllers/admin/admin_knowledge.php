<?php

/**
 * 知识库管理
 * @date 2017-06-07
 * @author tico.wong
 */
class admin_knowledge extends  MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_knowledge');
        $this->load->model('tb_admin_knowledge_log');
        $this->load->model('tb_admin_knowledge_cate');
    }

    /**
     * 自动权限检查
     * @author tico.wong
     */
    public function checkRole()
    {
        if(!$this->haveModifyRole())
        {
            if($this->input->is_ajax_request())
            {
                $ret_data['code'] = 1000;
                $ret_data['msg'] = lang("attach_no_permissions");
                exit(json_encode($ret_data));
            }else{
                exit(lang("attach_no_permissions"));
            }
        }
    }

    /**
     * 权限的判断,只允许0,2的权限
     */
    public function haveModifyRole()
    {
        if($this->input->get("haveModifyRole") == 'false')
        {
            return false;
        }
        $role = $this->_adminInfo['role'];
        if(!$role || !in_array($role,[0,2]))
        {
            return false;
        }
        return true;
    }

    /**
     * 取分类列表
     * @return mixed
     */
    public function get_knowledge_cate()
    {
        return $this->tb_admin_knowledge_cate->get_list("*",
            ["is_show"=>1]);
    }

    /**
     * 知识库首页
     * @author tico.wong
     */
    public function index(){

        $this->_viewData['title'] = lang('admin_knowledge_manage');

        //指定分页大小
        $page_size = 10;

        //组装条件
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        //标题搜索的处理
        if(isset($searchData['is_show']))
        {
            if($this->haveModifyRole())
            {
                //如果有权限
                if($searchData['is_show'] != -1)//如果是全部显示，不过滤
                {
                    $where['is_show'] = $searchData['is_show'];
                }
            }else{
                //如果没有权限，只能取显示的数据
                $searchData['is_show'] = '1';
                $where['is_show'] = '1';//默认只取显示的数据
            }
        }else{
            $searchData['is_show'] = '1';
            $where['is_show'] = '1';//默认只取显示的数据
        }
        //标题搜索的处理
        if(isset($searchData['title']))
        {
            $where['title like'] = [$searchData['title']=>'both'];
        }else{
            $searchData['title'] = '';
        }
        //内容搜索的处理
        if(isset($searchData['content']))
        {
            $where['content like'] = [$searchData['content']=>'both'];
        }else{
            $searchData['content'] = '';
        }
        //分类筛选的处理
        if(isset($searchData['knowledge_cate']))
        {
            if($searchData['knowledge_cate'])
            {
                $where['category_id'] = $searchData['knowledge_cate'];
            }
        }else{
            $searchData['knowledge_cate'] = '';
        }

        //取数据列表
        $this->_viewData['list'] = $this->tb_admin_knowledge->get_list_auto(
            [
                "where"=>$where,
                "page_index"=>($searchData['page']-1)*$page_size,
                "page_size"=>$page_size,
                "order_by"=>["create_time"=>"desc"],
            ]
        );

        //取admin_ids
        $admin_ids = [];
        foreach($this->_viewData['list'] as $k=>$v)
        {
            $admin_ids[] = $v['admin_id'];
        }
        //取管理员用户名
        $this->load->model("tb_admin_users");
        $admin_user = $this->tb_admin_users->get_list_auto([
           "select"=>"id,email",
            "where"=>["id"=>$admin_ids],
        ]);
        //取admin_user_info
        foreach($admin_user as $k=>$v)
        {
            $admin_user_info[$v['id']] = $v['email'];
        }
        $this->_viewData['admin_user_info'] = $admin_user_info;

        $url = 'admin/admin_knowledge';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_knowledge->get_counts($where);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = $page_size;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['haveModifyRole'] = $this->haveModifyRole();

        //取分类的列表
        $knowledge_cate = $this->get_knowledge_cate();
        $this->_viewData['knowledge_cate'] = $knowledge_cate;

        //取分类的ID对应的名字
        $knowledge_cate_name = [];
        foreach($knowledge_cate as $k=>$v)
        {
            $knowledge_cate_name[$v['id']] = $v['name'];
        }
        $this->_viewData['knowledge_cate_name'] = $knowledge_cate_name;

        parent::index('admin/');
    }

    /**
     * 添加/编辑 1->添加,2编辑
     * @author tico.wong
     */
    public function add_or_update(){

        $this->checkRole();

        $type = $this->input->get('type') ? $this->input->get('type') : 1;

        $this->mark();

        //取分类列表
        $knowledge_cate = $this->get_knowledge_cate();
        $this->_viewData['knowledge_cate'] = $knowledge_cate;
        $this->_viewData['haveModifyRole'] = $this->haveModifyRole();

        if($type==2){

            $id= $this->input->get('id');
            $row= $this->tb_admin_knowledge->get_one("*",["id"=>$id]);

            $this->_viewData['title']  = lang('edit');
            $this->_viewData['is_add'] = 2;
            $this->_viewData['row']    = $row;
            $this->_viewData['data']   = $row;
            parent::index('admin/','admin_knowledge_add');

        }else{

            $this->_viewData['title']  = lang('admin_knowledge_add');
            $this->_viewData['is_add'] = 1;
            parent::index('admin/','admin_knowledge_add');

        }

    }

    /**
     * 查看页面
     * @author tico.wong
     */
    public function view()
    {
        $id= $this->input->get('id');
        $row= $this->tb_admin_knowledge->get_one("*",["id"=>$id]);

//        $this->_viewData['title']  = lang('view');
        $this->_viewData['row']    = $row;
        parent::index('admin/','admin_knowledge_view');
    }

    /**
     * 添加/编辑
     * @author tico.wong
     */
    public function do_add_or_update(){

        $attr = $this->input->post();

        $rules = array(
            'title' => "required|max:150",
            'category_id' => "required|integer",
            'content' => "required",
        );

        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            $ret_data['msg'] = $this->validator->get_err_msg();
            echo json_encode($ret_data);
            exit;
        }

        //取admin_id
        $admin_id = $this->_adminInfo['id'];
        if(!$admin_id)
        {
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "must admin login";
            exit(json_encode($ret_data));
        }

        if($attr['update_id'])
        {
            //如果是编辑
            $this->do_edit($attr,$admin_id);
        }
        else{
            //如果是添加
            $this->do_add($attr,$admin_id);
        }
    }

    /**
     * 修改知识库
     * @param $attr
     * @param $admin_id
     * @author tico.wong
     */
    public function do_edit($attr,$admin_id)
    {

        $this->checkRole();

        $db_data = $this->tb_admin_knowledge->get_one("*",["id"=>$attr['update_id']]);
        if(!$db_data)
        {
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "data not found.";
            exit(json_encode($ret_data));
        }

        //准备更新的数据
        $data['admin_id'] = $admin_id;
        $data['title'] = $attr['title'];
        $data['content'] = $attr['content'];
        $data['category_id'] = $attr['category_id'];
        $data['is_show'] = isset($attr['is_show'])?$attr['is_show']:1;
        $where['id'] = $attr['update_id'];

        $tmp = $this->tb_admin_knowledge->update_one($where,$data);
        //记录日志
        $this->tb_admin_knowledge_log->insert_one([
            "type"=>'1',//1编辑，2新增，3删除
            "knowledge_id"=>$attr['update_id'],
            "admin_id"=>$admin_id,
            "new_data"=>serialize($data),
        ]);
        if($tmp)
        {
            $ret_data['code'] = 0;
            $ret_data['msg'] = "OK";
            exit(json_encode($ret_data));
        }else{
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "update db error:".$tmp;
            exit(json_encode($ret_data));
        }
    }

    /**
     * 增加知识库
     * @param $attr
     * @param $admin_id
     * @author tico.wong
     */
    public function do_add($attr,$admin_id)
    {

        $this->checkRole();

        //准备插入的数据
        $data['admin_id'] = $admin_id;
        $data['title'] = $attr['title'];
        $data['content'] = $attr['content'];
        $data['category_id'] = $attr['category_id'];
        $data['is_show'] = $attr['is_show'];
        $tmp = $this->tb_admin_knowledge->insert_one($data);

        //记录日志
        $this->tb_admin_knowledge_log->insert_one([
            "type"=>'2',//1编辑，2新增，3删除
            "knowledge_id"=>$tmp,
            "admin_id"=>$admin_id,
            "new_data"=>serialize($data),
        ]);

        if($tmp)
        {
            $ret_data['code'] = 0;
            $ret_data['msg'] = "OK";
            exit(json_encode($ret_data));
        }else{
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "insert db error:".$tmp;
            exit(json_encode($ret_data));
        }
    }

    /** 逻辑删除一个记录
     * @author tico.wong
     */
    public function do_delete(){

        $this->checkRole();

        $id = $this->input->post('id');

        $data = array(
            'id' => $id,
        );

        $tmp = $this->tb_admin_knowledge->delete_one($data);

        //取admin_id
        $admin_id = $this->_adminInfo['id'];
        //记录日志
        $this->tb_admin_knowledge_log->insert_one([
            "type"=>'3',//1编辑，2新增，3删除
            "knowledge_id"=>$id,
            "admin_id"=>$admin_id,
        ]);

        if($tmp)
        {
            $ret_data['code'] = 0;
            $ret_data['msg'] = "OK";
            exit(json_encode($ret_data));
        }else{
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "delete error:".$tmp;
            exit(json_encode($ret_data));
        }
    }

    //设置mark
    private  function mark(){

        $rand   =  md5('TPS'.time().mt_rand(100,999));

        $this->session->set_userdata('mark',$rand);

        $this->_viewData['mark'] = $rand;

    }

}