<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_ALL);
ignore_user_abort(); // run script in background

include_once APPPATH.'third_party/receiveMail.class.php';

class o_receive_mail extends CI_Model {

//	public $mailAccount = "supporttest@tps138.com";
//	public $mailPasswd = "Tps123456";
	public $mailAccount = "john.he@tps138.com";
	public $mailPasswd = "Tps88669250";
	public $mailAddress = "john.he@tps138.com";
	public $mailServer = "email.tps138.com";
	public $serverType = "pop3";
	public $port = "110";
	public $now       = '';
	public $savePath  = '';
	//public $webPath   = "upload/";

	public function __construct()
	{
		$this->now = time();

		$this->setSavePath();
	}

	/**
	 * mail Received()读取收件箱邮件
	 *
	 * @param
	 * @access public
	 * @return result
	 */
	public function mailReceived($start_count,$end_count)
	{
		// Creating a object of reciveMail Class
		$obj= new receivemail($this->mailAccount,$this->mailPasswd,$this->mailAddress,$this->mailServer,$this->serverType,$this->port,false);

		//Connect to the Mail Box
		$res=$obj->connect();         //If connection fails give error message and exit
		if (!$res)
		{
			return array("msg"=>"Error: Connecting to mail server");
		}
		// Get Total Number of Unread Email in mail box
		$tot=$obj->getTotalMails(); //Total Mails in Inbox Return integer value
		if($tot < 1) { //如果信件数为0,显示信息
			return array("msg"=>"No Message for ".$this->mailAccount);
		}
		else
		{


			$res=array("msg"=>"Total Mails:: $tot<br>");

			$end_count = $end_count > $tot ? $tot : $end_count;

			for($i=$start_count;$i<=$end_count;$i++)
			{
				$head=$obj->getHeaders($i);  // Get Header Info Return Array Of Headers **Array Keys are (subject,to,toOth,toNameOth,from,fromName)

				//处理邮件附件
				$files=$obj->GetAttach($i,$this->savePath); // 获取邮件附件，返回的邮件附件信息数组

				$imageList=array();
				foreach($files as $k => $file)
				{
					//type=1为附件,0为邮件内容图片
					if($file['type'] == 0)
					{
						$imageList[$file['title']]=$file['pathname'];
					}
				}
				$body = $obj->getBody($i,$this->savePath,$imageList);

				$res['mail'][]=array('head'=>$head,'body'=>$body,"attachList"=>$files);
//              $obj->deleteMails($i); // Delete Mail from Mail box
//              $obj->move_mails($i,"taskMail");
			}
			$obj->close_mailbox();   //Close Mail Box
			return $res;
		}
	}

	/**
	 * creatBox
	 *
	 * @access public
	 * @return void
	 */
	public function creatBox($boxName)
	{
		// Creating a object of reciveMail Class
		$obj= new receivemail($this->mailAccount,$this->mailPasswd,$this->mailAddress,$this->mailServer,$this->serverType,$this->port,false);
		$obj->creat_mailbox($boxName);
	}

	/**
	 * Set save path.
	 *
	 * @access public
	 * @return void
	 */
	public function setSavePath()
	{
		$savePath = "upload/email/" . date('Ym/', $this->now);
		if(!file_exists($savePath))
		{
			@mkdir($savePath, 0777, true);
			//touch($savePath . 'index.html');
		}
		$this->savePath = dirname($savePath) . '/';
	}

	/** 固定时间段去收取邮件 */
	public function get_email(){

		$cronName = 'get_email';
		$cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
		if($cron){
			if($cron['false_count'] > 29){
				$this->db->delete('cron_doing', array('cron_name' => $cronName));
				return false;
			}
			$this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
			return false;
		}

		$this->db->insert('cron_doing',array(
			'cron_name'=>$cronName
		));

		$this->load->model('tb_email_cfg');
		$this->load->model('tb_email');
		$this->load->model('tb_email_attach');
		$cfg = $this->tb_email_cfg->get_email_cfg();

		$end_count = $cfg['distance'] + $cfg['start_count'];
		$start_count = 	$cfg['start_count'] + 1;

		$res = $this->mailReceived($start_count,$end_count);

		$data = array();
		if($res['mail'])
		{
			$this->db->trans_start();
			foreach($res['mail'] as $item)
			{

				$data['title'] = $item['head']['subject'];
				$data['content'] = $item['body']['body'];
				$data['content_html'] = $item['body']['body_html'];
				$data['from_address'] = $item['head']['fromBy'];
				$data['send_date'] = $item['head']['toList'];
				$data['send_date'] = $item['head']['mailDate'];
				$data['to_address'] = $item['head']['toList'];
				$data['is_attach'] = $item['attachList'] ? 1 : 0;

				$email_id = $this->tb_email->add_email_row($data);

				if($item['attachList'])foreach($item['attachList'] as $attach){
					$attach_arr['email_id'] = $email_id;
					$attach_arr['name'] = $attach['title'];
					$attach_arr['path_name'] = $attach['pathname'];
					$attach_arr['type'] = $attach['type'];
					$attach_arr['extension'] = $attach['extension'];

					$this->tb_email_attach->add_email_attach($attach_arr);
				}
			}

			//$this->tb_email_cfg->update_email_start($cfg['id'],count($res['mail']));
			$this->db->trans_complete();
		}

		$this->db->delete('cron_doing', array('cron_name' => $cronName));

	}

	
}
