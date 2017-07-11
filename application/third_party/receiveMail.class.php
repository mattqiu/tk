<?php

class receiveMail
{
	var $server='';
	var $username='';
	var $password='';

	var $marubox='';

	var $email='';

	function receiveMail($username,$password,$EmailAddress,$mailserver='localhost',$servertype='pop',$port='110',$ssl = false) //Constructure
	{
		if($servertype=='imap')
		{
			if($port=='') $port='143';
			$strConnect='{'.$mailserver.':'.$port. '}INBOX';
		}
		else
		{
			$strConnect='{'.$mailserver.':'.$port. '/pop3'.'/notls'.($ssl ? "/ssl" : "").'}INBOX';
		}
		$this->server			=	$strConnect;
		$this->username			=	$username;
		$this->password			=	$password;
		$this->email			=	$EmailAddress;
	}
	function connect() //Connect To the Mail Box
	{
		$this->marubox=@imap_open($this->server,$this->username,$this->password);

		if(!$this->marubox)
		{
			echo "Error: Connecting to mail server";
			exit;
			return false;
		}
		return true;
	}


	function getHeaders($mid) // Get Header info
	{
		if(!$this->marubox)
			return false;

		$mail_header=imap_header($this->marubox,$mid);
		$sender=$mail_header->from[0];
		$sender_replyto=$mail_header->reply_to[0];
		if(strtolower($sender->mailbox)!='mailer-daemon' && strtolower($sender->mailbox)!='postmaster')
		{
			$subject=$this->decode_mime($mail_header->subject);

			$ccList=array();
			if(isset($mail_header->cc) && $mail_header->cc)foreach ($mail_header->cc as $k => $v)
			{
				$ccList[]=$v->mailbox.'@'.$v->host;
			}
			$toList=array();
			if(isset($mail_header->to) && $mail_header->to)foreach ($mail_header->to as $k => $v)
			{
				$toList[]=$v->mailbox.'@'.$v->host;
			}
			$ccList=implode(",", $ccList);
			$toList=implode(",", $toList);
			$mail_details=array(
				'fromBy'=>strtolower($sender->mailbox).'@'.$sender->host,
				'fromName'=>$this->decode_mime($sender->personal),
				'ccList'=>$ccList,//strtolower($sender_replyto->mailbox).'@'.$sender_replyto->host,
				'toNameOth'=>$this->decode_mime($sender_replyto->personal),
				'subject'=>$subject,
				'mailDate'=>date("Y-m-d H:i:s",$mail_header->udate),
				'udate'=>$mail_header->udate,
				'toList'=>$toList//strtolower($mail_header->to[0]->mailbox).'@'.$mail_header->to[0]->host
// 					'to'=>strtolower($mail_header->toaddress)
			);
		}
		return $mail_details;
	}
	function get_mime_type(&$structure) //Get Mime type Internal Private Use
	{
		$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

		if($structure->subtype && $structure->subtype!="PNG") {
			return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
		}
		return "TEXT/PLAIN";
	}
	function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) //Get Part Of Message Internal Private Use
	{

		if(!$structure) {
			$structure = imap_fetchstructure($stream, $msg_number);
		}
		if($structure) {
			if($mime_type == $this->get_mime_type($structure))
			{
				if(!$part_number)
				{
					$part_number = "1";
				}

				$fromEncoding = $structure->parameters[0]->value;
				$text = imap_fetchbody($stream, $msg_number, $part_number);

//				if($structure->encoding == 3)
//				{
//					return iconv('GB2312','utf-8//ignore',imap_base64($text));
//				}
//				else if($structure->encoding == 4)
//				{
//					return iconv('GB2312','utf-8//ignore',imap_qprint($text));
//				}
//				else
//				{
//					return iconv('GB2312','utf-8//ignore',$text);
//				}

				if($structure->encoding == 3) {
					$text =  imap_base64($text);
				} else if($structure->encoding == 4) {
					$text =  imap_qprint($text);
				}
				$text = mb_convert_encoding($text,'utf-8',$fromEncoding);
				return $text;

			}
			if($structure->type == 1) /* multipart */
			{
				while(list($index, $sub_structure) = each($structure->parts))
				{
					$prefix = '';
					if($part_number)
					{
						$prefix = $part_number . '.';
					}
					$data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
					if($data)
					{
						return $data;
					}
				}
			}
		}
		return false;
	}
	function getTotalMails() //Get Total Number off Unread Email In Mailbox
	{
		if(!$this->marubox)
			return false;

		return imap_num_msg($this->marubox);
	}

	function GetAttach($mid,$path) // Get Atteced File from Mail
	{
		if(!$this->marubox)
			return false;

		$struckture = imap_fetchstructure($this->marubox,$mid);

		$files=array();
		if(isset($struckture->parts) && $struckture->parts)
		{
			foreach($struckture->parts as $key => $value)
			{
				$enc=$struckture->parts[$key]->encoding;

				//取邮件附件
				if($struckture->parts[$key]->ifdparameters)
				{
					//命名附件,转码
					$name=$this->decode_mime($struckture->parts[$key]->dparameters[0]->value);
					$extend =explode("." , $name);
					$file['extension'] = $extend[count($extend)-1];
					$file['pathname']  = $this->setPathName($key, $file['extension']);
					$file['title']     = !empty($name) ? htmlspecialchars($name) : str_replace('.' . $file['extension'], '', $name);
					//$file['size']      = $struckture->parts[$key]->dparameters[0]->value;
// 					$file['tmpname']   = $struckture->parts[$key]->dparameters[0]->value;
					if(@$struckture->parts[$key]->disposition=="ATTACHMENT")
					{
						$file['type']      = 1;
					}
					else
					{
						$file['type']      = 0;
					}
					$files[] = $file;

					$message = imap_fetchbody($this->marubox,$mid,$key+1);
					if ($enc == 0)
						$message = imap_8bit($message);
					if ($enc == 1)
						$message = imap_8bit ($message);
					if ($enc == 2)
						$message = imap_binary ($message);
					if ($enc == 3)//图片
						$message = imap_base64 ($message);
					if ($enc == 4)
						$message = quoted_printable_decode($message);
					if ($enc == 5)
						$message = $message;
					$fp=fopen($path.$file['pathname'],"w");
					fwrite($fp,$message);
					fclose($fp);

				}
				// 处理内容中包含图片的部分
				if(isset($struckture->parts[$key]->parts) && $struckture->parts[$key]->parts)
				{
					foreach($struckture->parts[$key]->parts as $keyb => $valueb)
					{
						$enc=$struckture->parts[$key]->parts[$keyb]->encoding;
						if($struckture->parts[$key]->parts[$keyb]->ifdparameters)
						{
							//命名图片
							$name=$this->decode_mime($struckture->parts[$key]->parts[$keyb]->dparameters[0]->value);
							$extend =explode("." , $name);
							$file['extension'] = $extend[count($extend)-1];
							$file['pathname']  = $this->setPathName($key, $file['extension']);
							$file['title']     = !empty($name) ? htmlspecialchars($name) : str_replace('.' . $file['extension'], '', $name);
//							$file['size']      = $struckture->parts[$key]->parts[$keyb]->dparameters[0]->value;
// 							$file['tmpname']   = $struckture->parts[$key]->dparameters[0]->value;
							$file['type']      = 0;
							$files[] = $file;

							$partnro = ($key+1).".".($keyb+1);

							$message = imap_fetchbody($this->marubox,$mid,$partnro);
							if ($enc == 0)
								$message = imap_8bit($message);
							if ($enc == 1)
								$message = imap_8bit ($message);
							if ($enc == 2)
								$message = imap_binary ($message);
							if ($enc == 3)
								$message = imap_base64 ($message);
							if ($enc == 4)
								$message = quoted_printable_decode($message);
							if ($enc == 5)
								$message = $message;
							$fp=fopen($path.$file['pathname'],"w");
							fwrite($fp,$message);
							fclose($fp);
						}
					}
				}
			}
		}
		//move mail to taskMailBox
		//$this->move_mails($mid, $this->marubox);

		return $files;
	}

	function getBody($mid,&$path,$imageList) // Get Message Body
	{
		if(!$this->marubox)
			return false;

		$body_html = $this->get_part($this->marubox, $mid, "TEXT/HTML");
		$body = $this->get_part($this->marubox, $mid, "TEXT/PLAIN");

		if ($body_html !== "") {
			//处理图片
			$body_html=$this->embed_images($body_html,$path,$imageList);
		}

		return array('body'=>$body,'body_html'=>$body_html);
	}

	function embed_images(&$body,&$path,$imageList)
	{
		if(!$imageList)
			return $body;

		// get all img tags
		preg_match_all('/<img.*?>/', $body, $matches);
		if (!isset($matches[0])) return;

		foreach ($matches[0] as $img)
		{
			// replace image web path with local path
			preg_match('/src="(.*?)"/', $img, $m);
			if (!isset($m[1])) continue;
			$arr = parse_url($m[1]);
			if (!isset($arr['scheme']) || !isset($arr['path']))continue;

// 			if (!isset($arr['host']) || !isset($arr['path']))continue;
			if ($arr['scheme']!="http")
			{
				$filename=explode("@", $arr['path']);
				$body = str_replace($img, '<img alt="" src="/'.$path.$imageList[$filename[0]].'" style="border: none;" />', $body);
			}
		}
		return $body;
	}

	function deleteMails($mid) // Delete That Mail
	{
		if(!$this->marubox)
			return false;

		imap_delete($this->marubox,$mid);
	}
	function close_mailbox() //Close Mail Box
	{
		if(!$this->marubox)
			return false;

		imap_close($this->marubox,CL_EXPUNGE);
	}

	//移动邮件到指定分组
	function move_mails($msglist,$mailbox)
	{
		if(!$this->marubox)
			return false;

		imap_mail_move($this->marubox, $msglist, $mailbox);
	}

	function creat_mailbox($mailbox)
	{
		if(!$this->marubox)
			return false;

		//imap_renamemailbox($imap_stream, $old_mbox, $new_mbox);
		imap_create($this->marubox, $mailbox);
	}

	/*
	 * decode_mime()转换邮件标题的字符编码,处理乱码
	 */
	function decode_mime($str){
		$str=imap_mime_header_decode($str);
		$content = '';
		foreach($str as $item){
			if ($item->charset!="default")
			{
				$content  .= iconv($item->charset,'utf-8',($item->text));
			}
			else
			{
				$content .= $item->text;
			}
		}
		return $content;
	}

	/**
	 * Set path name of the uploaded file to be saved.
	 *
	 * @param  int    $fileID
	 * @param  string $extension
	 * @access public
	 * @return string
	 */
	public function setPathName($fileID, $extension)
	{
		return date('Ym/dHis', time()) . $fileID . mt_rand(0, 10000) . '.' . $extension;
	}

}

