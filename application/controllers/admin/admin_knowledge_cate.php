<?php

/**
 * 知识库分类管理
 * @date 2017-06-07
 * @author tico.wong
 */
class admin_knowledge_cate extends  MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_knowledge');
        $this->load->model('tb_admin_knowledge_cate');
        $this->load->model('tb_admin_knowledge_cate_log');
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
     * 权限的判断
     */
    public function haveModifyRole()
    {
        if($this->input->get("haveModifyRole") == "false")
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
     * 知识库分类管理首页
     * @author tico.wong
     */
    public function index(){

        $this->_viewData['title'] = lang('admin_knowledge_cate_manage');
        //设置页码大小
        $page_size = 15;

        //取页码等数据
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        //取列表的条件
        if($this->haveModifyRole()) {
            //如果有编辑条件，全部展示
            $where = [];
        }else{
            //没编辑条件只能查看未隐藏的分类
            $where = ["is_show"=>1];
        }

        //取列表数据
        $this->_viewData['list'] = $this->tb_admin_knowledge_cate->get_list_auto([
            "where"=>$where,
            "order_by"=>["sort"=>"desc","id"=>"desc"],
            "page_index"=>($searchData['page']-1)*$page_size,
            "page_size"=>$page_size,
        ]);
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


        $url = 'admin/admin_knowledge_cate';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_knowledge_cate->get_counts($where);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = $page_size;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['haveModifyRole'] = $this->haveModifyRole();

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

        $this->_viewData['haveModifyRole'] = $this->haveModifyRole();

        if($type==2){
            //编辑界面
            $id= $this->input->get('id');
            $row= $this->tb_admin_knowledge_cate->get_one("*",["id"=>$id]);

            $this->_viewData['title']  = lang('edit');
            $this->_viewData['is_add'] = 2;
            $this->_viewData['row']    = $row;
            parent::index('admin/','admin_knowledge_cate_add');

        }else{
            //添加界面
            $this->_viewData['title']  = lang('admin_knowledge_cate_add');
            $this->_viewData['is_add'] = 1;
            parent::index('admin/','admin_knowledge_cate_add');

        }

    }

    /**
     * 查看
     * author tico.wong
     */
    public function view()
    {
        $id= $this->input->get('id');
        $row= $this->tb_admin_knowledge_cate->get_one("*",["id"=>$id]);

        $this->_viewData['title']  = lang('view');
        $this->_viewData['row']    = $row;
        parent::index('admin/','admin_knowledge_cate_view');
    }

    /**
     * 添加/编辑
     * @author tico.wong
     */
    public function do_add_or_update(){
        $attr = $this->input->post();

        $rules = array(
            'name' => "required|max:100",
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
     *
     * @param $attr
     * @param $admin_id
     */
    public function do_edit($attr,$admin_id)
    {

        $this->checkRole();

        $db_data = $this->tb_admin_knowledge_cate->get_one("*",["id"=>$attr['update_id']]);
        if(!$db_data)
        {
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "data not found.";
            exit(json_encode($ret_data));
        }

        //如果是编辑
        $db_data = $this->have_knowledge_cate($attr['name'],$attr['update_id']);
        if($db_data)
        {
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "分类不允许重名";
            exit(json_encode($ret_data));
        }

        //不允许隐藏有数据的分类
        if(!$attr['is_show']){
            $da = $this->have_knowledge($attr['update_id']);
            if($da)
            {
                $ret_data['code'] = 1000;
                $ret_data['msg'] = "不能隐藏有数据的分类.";
                exit(json_encode($ret_data));
            }
        }

        //准备更新的数据
        $data['name'] = $attr['name'];
        $data['sort'] = intval($attr['sort']);
        $data['admin_id'] = $admin_id;
        $data['is_show'] = $attr['is_show'];
        $where['id'] = $attr['update_id'];
        $tmp = $this->tb_admin_knowledge_cate->update_one($where,$data);
        //记录日志
        $this->tb_admin_knowledge_cate_log->insert_one([
            "type"=>'1',//1编辑，2新增，3删除
            "knowledge_cate_id"=>$attr['update_id'],
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
     * 添加的响应
     * @param $attr
     * @param $admin_id
     */
    public function do_add($attr,$admin_id){

        $this->checkRole();

        //如果是添加
        $db_data = $this->have_knowledge_cate($attr['name']);
        if($db_data)
        {
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "不允许重名的分类";
            exit(json_encode($ret_data));
        }

        //准备插入的数据
        $data['name'] = $attr['name'];
        $data['sort'] = intval($attr['sort']);
        $data['admin_id'] = $admin_id;
        $data['is_show'] = $attr['is_show'];
        $tmp = $this->tb_admin_knowledge_cate->insert_one($data);

        //记录日志
        $this->tb_admin_knowledge_cate_log->insert_one([
            "type"=>'2',//1编辑，2新增，3删除
            "knowledge_cate_id"=>$tmp,
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

    public function have_knowledge($id)
    {
        return $this->tb_admin_knowledge->get_one("id",["category_id"=>$id]);
    }

    public function have_knowledge_cate($name,$id=0)
    {
        $name = str_replace(" ","",trim($name));
        if($id)
        {
            return $this->tb_admin_knowledge_cate->get_one("*",["name"=>$name,"id !="=>$id]);
        }else{
            return $this->tb_admin_knowledge_cate->get_one("*",["name"=>$name]);
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

        $da = $this->have_knowledge($id);
        if($da)
        {
            $ret_data['code'] = 1000;
            $ret_data['msg'] = "不能删除有数据的分类.";
            exit(json_encode($ret_data));
        }

        $tmp = $this->tb_admin_knowledge_cate->delete_one($data);

        //取admin_id
        $admin_id = $this->_adminInfo['id'];
        //记录日志
        $this->tb_admin_knowledge_cate_log->insert_one([
            "type"=>'3',//1编辑，2新增，3删除
            "knowledge_cate_id"=>$id,
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