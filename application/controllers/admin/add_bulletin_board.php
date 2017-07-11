<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Add_bulletin_board extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_news');
    }

    public function index($id = NULL) {
        if($id){

            $data = $this->m_news->getOneBoard($id);
            $this->_viewData['data'] = $data;
        }
        $this->_viewData['title'] = lang('add_bulletin_board');    
        
        $this->_viewData['admin_board_title_not_null'] = lang('admin_board_title_not_null'); //标题不能全部为空
        $this->_viewData['admin_board_conteng_not_null'] = lang('admin_board_conteng_not_null'); //公告内容不能全部为空
     
        $sorts = $this->m_news->getMaxSort();
        $this->_viewData['sort_nb'] = $sorts+1;
		parent::index('admin/');
    }

    public function do_add(){
        ini_set ('memory_limit', '256M');
        $data = $this->input->post();
        $data['english'] = str_replace(array("\n","\r"),"",$data['english']);
        $data['zh'] = str_replace(array("\n","\r"),"",$data['zh']);
        $data['hk'] = str_replace(array("\n","\r"),"",$data['hk']);
        $data['kr'] = str_replace(array("\n","\r"),"",$data['kr']);
        foreach($data as $key=>$item){
            switch ($key){
                case 'title_english':
//                    if(!$item){
//                        $error['title_english'] = lang('required');
//                    }
//                    else{
                        $error['english'] = 0;
//                    }
                    break;
				case 'permission':
//                    if(!$item){
//                        $error['permission'] = lang('required');
//                    }
//                    else{
                        $error['permission'] = 0;
//                    }
                    break;
				case 'title_zh':
//                    if(!$item){
//                        $error['title_zh'] = lang('required');
//                    }
//                    else{
                        $error['english'] = 0;
//                    }
                    break;
				case 'title_hk':
//                    if(!$item){
//                        $error['title_hk'] = lang('required');
//                    }
//                    else{
                        $error['english'] = 0;
//                    }
                    break;
				case 'english':
//                    if(!$item){
//                        $error['english'] = lang('required');
//                    }
//                    else{
                        $error['english'] = 0;
//                    }
                    break;

                case 'zh':
//                    if(!$item){
//                        $error['zh'] = lang('required');
//                    }
//                    else{
                        $error['zh'] = 0;
//                    }
                    break;
                case 'hk':
//                    if (!$item) {
//                        $error['hk'] = lang('required');
//                    }else{
                        $error['hk'] = 0;
//                    }
                    break;
                case 'sort':
                    if (!$item) {
                        $error['sort'] = lang('required');
                    }else{
                        $this->load->model('m_admin_helper');
                        if(!$data['board_id'] && $this->m_admin_helper->checkSort($item)){
                            $error['sort'] = lang('sort_exist');
                        }else{

                            $error['sort'] = 0;
                        }
                    }
                    break;
            }

        }

        if(isset($error['english'])&&$error['english']===0 && isset($error['hk'])&&$error['hk']===0 && isset($error['zh'])&&$error['zh']===0
			&& isset($error['sort'])&&$error['sort']===0 && isset($error['permission'])&&$error['permission']===0){
            $error_code = 0;
        }else{
            $error_code = 101;
        }
        if($error_code){
            echo json_encode(array('error_code'=>$error_code,'error'=>$error));exit;
        }

		if(isset($data['important'])){
			$data['important'] = 1;
		}else{
			$data['important'] = 0;
		}
		if(isset($data['display'])){
			$data['display'] = 1;
		}else{
			$data['display'] = 0;
		}


		/** 2016-12-26 使用新的方案 */
		
		if($data['board_id'])
		{		  
		    $this->m_news->updateBoard($data);
		}
		else
		{		   
		    $this->m_news->createBoard($data);
		}		
		
		/* 2016-12-26     注， 使用新的方案 原先方案注释掉
		if (!is_dir('upload/bulletin/')) {
			mkdir('upload/bulletin/', DIR_WRITE_MODE); // 使用最大权限0777创建文件
		}
		$root = $_SERVER['DOCUMENT_ROOT'];

		if($data['permission'] == 2){
			$sql = "select id from users where status in(1,2,3) and id!=1380100217 and parent_id>0";
		}else if($data['permission'] == 3){
			$sql = "select id from users where status in(1,2,3) and id!=1380100217 and parent_id=0;";
		}else{
			$sql = "select id from users where status in(1,2,3) and id!=1380100217";
		}

		
		$this->db->trans_start();

        if($data['board_id']){
            $this->m_news->updateBoard($data);
            
            
			$count = $this->db->from('bulletin_unread')->where('bulletin_id',$data['board_id'])->count_all_results();
			if($data['display'] == 1 && $count == 0){
				$users = $this->db->query($sql)->result_array();

				$name = "upload/bulletin/{$data['board_id']}.txt";
				if(!file_exists($name)){
					$f = fopen($name,'w+');
					$str = '';
					foreach($users as $user){
						$str .= $user['id'].','.$data['board_id']."\r\n";
					}
					fwrite($f,$str);
					fclose($f);
				}
				$this->db->query("Load Data Local InFile '$root/$name' Into Table `bulletin_unread` Fields Terminated By ',' Lines Terminated By '\r\n'");

			}
			if($data['display'] == 0){
				$this->db->query("delete from bulletin_unread where bulletin_id={$data['board_id']}");
			}
			
        }else{

			$bulletin_id = $this->m_news->createBoard($data);
			
			if($bulletin_id && $data['display'] == 1){
				$users = $this->db->query($sql)->result_array();

				$name = "upload/bulletin/$bulletin_id.txt";

				$f = fopen($name,'w+');
				$str = '';
				foreach($users as $user){
					$str .= $user['id'].','.$bulletin_id."\r\n";
				}
				fwrite($f,$str);
				fclose($f);

				$this->db->query("Load Data Local InFile '$root/$name' Into Table `bulletin_unread` Fields Terminated By ',' Lines Terminated By '\r\n'");
			}
            
        }
		$this->db->trans_complete();
		*/
        echo json_encode(array('success'=>1));exit;		
    }


}