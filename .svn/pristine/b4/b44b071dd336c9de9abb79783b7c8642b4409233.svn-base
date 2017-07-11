<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo lang('tps138_admin');?></title>
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<script src="<?php echo base_url('js/jquery-1.11.2.min.js')?>"></script>
	<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
	<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/uploadify/uploadify.css')?>"/>
	<script type="text/javascript" src="<?php echo base_url('ucenter_theme/lib/uploadify/jquery.uploadify-3.1.min.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
	<script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/layer2/layer.js?v=1'); ?>"></script>
	<style>
		.fr{float: right;margin: 3px 7px;}
		.title{text-align: center;font-size: 13px;max-width: 380px;margin: 0 auto;overflow: hidden;}
		.msg_big_box{height: 420px;margin: 5px 5px 0px 5px;background-color: #eeeeee;overflow-y: auto;clear: both;}
		.well-msg{padding: 1px 1px;height: auto;overflow: hidden;border: 0px;background-color: #eeeeee;margin-bottom: 5px;box-shadow: none;}
		.left-msg{clear: both;background-color: #fff;float: left;border-radius: 4px;max-width: 500px;padding: 12px;word-wrap: break-word; }
		.right-msg{clear: both;background-color: #7fb2f2;float: right;border-radius: 4px;max-width: 500px;padding: 12px;word-wrap: break-word;}
		.left-attach-box{margin-top: 2px;float: left;clear: both;background-color: #d7d7d7;border-radius: 5px;}
		.left-attach-box-pic{float:left; height:30px;margin-right: 8px;}
		.left-attach-box-file{float: left; height:16px;clear: both;margin-top: 0px;padding: 0px;font-size: 11px;}
		.right-attach-box{margin-top: 2px;float: right;clear: both;max-width: 500px;width: auto;background-color: #d7d7d7;border-radius: 5px;}
		.right-attach-box-pic{float: right; height:30px;margin-left: 8px;}
		.right-attach-box-file{float: right; height:16px;clear: both;margin-top: 0px;padding: 0px;font-size: 11px;}
		.example-image{height: 25px;width: 25px;margin-bottom: -6px;}
		.example-image-link{margin: 0px;padding: 0px;}
		.input_box{height: 133px;padding: 3px;background-color: #fff;border-top:0px;width: 100%}
		.input_content{float: left;clear: both;width: 100%;}
		.reply_submit{float: left;}
		.upload{float: right;max-width: 500px;}
		.uploadify{float: right;}
		.uploadify-queue{clear: both;}
		.uploadify-queue-item{padding: 0px;margin-top: 2px;float: right;margin-left: 3px;}
		.can_not_reply{color: red;}
		/**star**/
		*{list-style-type:none;}
		#star{position:relative;width:300px;margin:20px auto;height:24px;}
		#star ul, #star span {float:left;display:inline;height:19px;line-height:19px;}
		.score_span{margin-left: 30px;}
		#star ul {margin:0 -18px;}
		#star li {float:left;width:24px;cursor:pointer;text-indent:-9999px;background:url(/ucenter_theme/images/tickets_star.png) no-repeat;}
		#star strong {color:#f60;padding-left:10px;}
		#star li.on {background-position:0 -28px;}
		#star p {position:absolute;top:15px;width:120px;height:32px;display:none;padding:2px;float: left;clear: both;}
	</style>

</head>

<body>
<!--start-->
<div class="fr"><img src="<?php echo base_url('themes/admin/images/tips_refresh.png'); ?>"></div>
<div class="title">#<span><?php if(!empty($rows['org'])){ echo $rows['org']['id'];} ?></span>
	&nbsp;&nbsp;
	<span><?php if(!empty($rows['org'])) echo $rows['org']['title'];?></span>
</div>
<?php //信息合并
if(!empty($rows) && !empty($rows['org'])){
	if(isset($rows['reply']) && !empty($rows['reply'])){
		//array_push($rows['reply'],$rows['org']);
		array_unshift($rows['reply'],$rows['org']);
	}else{
		$rows['reply'][1] = $rows['org'];
	}
}
if(isset($rows['reply']) && !empty($rows['reply'])){ ?>
	<div class="well msg_big_box">
		<?php foreach($rows['reply'] as $row){
			?>
			<div class="well well-msg">
				<?php if($row['sender']==1 && $row['type'] !=100){?>
					<span style="float: left;margin-right: 12px;"><?php if(isset($all_cus[$row['admin_id']])){ echo $all_cus[$row['admin_id']]['job_number'];}else{echo 'TPS';}?></span>
					<span style="float: left;margin-left: 10px;"><?php echo date('m/d H:i',strtotime($row['create_time'])); ?></span>
					<div class="left-msg"><?php echo $row['content'];?></div>
					<?php if(isset($row['attach']) && !empty($row['attach'])){ ?>
						<div class="left-attach-box">
							<?php foreach($row['attach'] as $item){
								if(in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
									<div class="left-attach-box-pic">
										<a data-lightbox="pic-<?php echo $row['id']?>" href="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image-link" rel="" fullname="<?php echo $item['name']; ?>">
											<img alt="<?php echo lang('picture_not_exist'); ?>" src="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image"></a>
									</div>
								<?php }?>
							<?php }?>
							<?php foreach($row['attach'] as $item){ ?>
								<?php if(!in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
									<div class="left-attach-box-file">
										<?php 	echo '<a href='.base_url('ucenter/add_tickets/download_attach/'.$item['id']).'>'.$item['name'].'</a></br>';?>
									</div>
								<?php }?>
							<?php }?>

						</div>
					<?php }?>
				<?php } ?>
				<?php if($row['sender']==0){?>
					<div class="well well-msg">
						<span style="float: right;"><?php echo date('m/d H:i',strtotime($row['create_time'])); ?></span>
						<span style="float: right"><?php echo isset($tickets_user[$row['uid']]) ? $tickets_user[$row['uid']]['user_name'] :''; ?>&nbsp;&nbsp;</span>
						<div class="right-msg"><?php echo $row['content'];?></div>
						<div class="right-attach-box">
							<?php if(isset($row['attach']) && !empty($row['attach'])){ ?>
								<?php foreach($row['attach'] as $item){ ?>
									<?php if(in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
										<div class="right-attach-box-pic">
											<a data-lightbox="pic-<?php echo $item['id']?>" href="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image-link" rel="" fullname="<?php echo $item['name']; ?>">
												<img width="15px" height="15px" alt="<?php echo lang('picture_not_exist'); ?>" src="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image"></a>
										</div>
									<?php } ?>
								<?php } ?>

								<?php foreach($row['attach'] as $item){ ?>
									<?php if(!in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
										<div class="right-attach-box-file">
											<?php	echo '<a href='.base_url('ucenter/add_tickets/download_attach/'.$item['id']).'>'.$item['name'].'</a></br>';?>
										</div>
									<?php } ?>
								<?php } ?>

							<?php } ?>
						</div>
					</div>

				<?php } ?>
			</div>
		<?php }?>
	</div>
	<div class="well input_box">
		<form class="form-horizontal" id="reply_tickets" method="post" action="admin/">
			<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
			<div class="inline reply_input">
				<div>
					<div class="input_content">
						<textarea id="content" maxlength="1000" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> class="form-control"  placeholder="<?php echo lang('pls_t_content');?>" name="content" rows="3" style="width: 100%; height: 90px;"></textarea>
					</div>
					<div style="float: right;margin-top: -18px;margin-right: 14px;"><?php echo lang('remain_'); ?><span id='count'>1000</span><?php echo lang('_words'); ?></div>
				</div>
				<div style="clear: both;"></div>
				<div class="upload_tips" style="color: red;"></div>
				<div class="upload">
					<input type="file" name="file_upload" id="file_upload" />
				</div>
				<div class="reply_submit">
					<div class="dropdown">
								<button   type="button" status="2" name="submit" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> class="btn btn-success"><?php echo lang('submit_as_waiting_resolve')?></button>
								<button   type="button" status="4" name="submit" <?php if(!empty($visitor) && $visitor=='admin'){echo "disabled='disabled'";} if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> class="btn btn-primary"><?php echo lang('submit_as_resolved')?></button>
								<span class="can_not_reply"><?php if(!empty($rows['org'])){if($rows['org']['status']==4 || $rows['org']['status']==5){echo lang('tickets_closed_can_not_reply');}}?></span>
					</div>
					</div>
					<span class="msg" id="tickets_msg"></span>
				</div>
				<input type="hidden" name="id" value="<?php if(!empty($rows['org'])) {echo $rows['org']['id'];}?>">
				<input id="score_num" type='hidden' name='score_num' value="0">
				<input id="tickets_status" type='hidden' name='status' value="">
			</div>
		</form>

	</div>
<?php }?>
<!--end-->


</body>
<script>
		var tid = <?php if(!empty($rows['org'])){ echo $rows['org']['id'];} ?>;

		$('#count').css('color','#FF0000');
		var cont = $('#content').val();
		$('#count').empty().append(1000-cont.length);
		$('#content').keyup(function(){
		cont = $('#content').val();
		$('#count').empty().append(1000-cont.length);
		});
		$(".msg_big_box").scrollTop($(".msg_big_box")[0].scrollHeight);
		$('#content').focus();

		//setInterval(function(){refresh()},30000);

		function refresh(){
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('ucenter/my_tickets/get_new_msg');?>",
				dataType: "json",
				data:{id:tid},
				success: function (res) {
					if(res.success==1){
						window.location.reload();
					}
				}
			});
	}

	$(function(){
		var s = <?php if(!empty($rows) && isset($rows['org'])){if($rows['org']['status']==4 || $rows['org']['status']==5){echo 'true';}else{echo 'false';}}else{echo 'false';} ?>;
		var c = <?php if(!empty($rows) && isset($rows['org'])){if($rows['org']['status']==6){echo 'true';}else{echo 'false';}}else{echo 'false';}?>;
		if(c){
			layer.alert('<?php echo '&nbsp;&nbsp;&nbsp;&nbsp;'.lang('tickets_apply_close_tips'); ?>', {
				title:'<?php echo lang('kindly_remind'); ?>',
				skin: 'layui-layer-lan'
				,closeBtn: 0
				,shift: 4 //动画类型
				,btn:['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>']
			});
			$('.layui-layer-btn0').css('background',' #4476A7');
		}
		if(s){
			$('.upload').empty();
			$('#dropdownMenu1').attr('disabled',true);
		}
		$('.fr').click(function(){
			window.location.reload();
		});
		var value2 = 0;
        $(".lb-nav").rotate({
            bind:{
                click : function() {
                    value2 +=90;
                    if(value2 > 360){
                        value2 = 90;
                    }
                    $(this).prev().rotate({angle:45,animateTo:value2});
                    $('.lb-dataContainer').css({width:'70%'});
                }
            }
        });
        $("[rel=tooltip]").tooltip();
		
		$("button[name='submit']").click(function () {
			var cont =$('#content').val();
			$('#tickets_status').val($(this).attr('status'));
			if($(this).attr('status')==4){//resolved
				layer.confirm('<?php echo lang('confirm_submit_tickets_as_resolved'); ?>', {icon: 3, title:'<?php echo lang('kindly_remind'); ?>',btn:['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>']},function(index){
				submit_as();
				},function(){
					$('#score_num').val(0);
				});
				var str = "<div id='star'><span><?php echo lang('t_pls_t_score'); ?></span><ul> <li><a href='javascript:;'>1</a></li><li><a href='javascript:;'>2</a></li><li><a href='javascript:;'>3</a></li><li><a href='javascript:;'>4</a></li><li><a href='javascript:;'>5</a></li></ul><span class='score_span'></span><p></p></div>";
				$('.layui-layer-content').append(str);
				star();
			}else{
				$('#score_num').val(101);
				if($.trim(cont).length<=0){
					layer.msg('<?php echo lang('pls_t_content'); ?>');
					return;
				}
				submit_as();
			}		
		});

		function star(){
			var star = $('#star');
			var ul =star.children('ul');
			var li  = ul.children('li');
			var span = $('.score_span');
			var p = $('p');
			var score_num = 5;
			//$('#score_num').val(0);
			span.text(5+"<?php echo lang('t_score'); ?>");
			var msg =["<?php echo lang('t_very_dissatisfied'); ?>",
				"<?php echo lang('t_dissatisfied'); ?>",
				"<?php echo lang('t_general'); ?>",
				"<?php echo lang('t_satisfaction'); ?>",
				"<?php echo lang('t_very_satisfactory'); ?>"];

			li.each(function(i){
				$(this).addClass('on');
				$(this).mouseover(function(){
					p.show().css('left',ul.position().left+i*$(this).width()).text(i+1 + "<?php echo lang('t_score').'  '; ?>"+msg[i]);
					li.each(function(){
						if($(this).index()<=i){
							$(this).addClass('on');
						}else{
							$(this).attr('class','');
						}
					});
				});
				$(this).mouseout(function(){
					li.each(function(){
						if($(this).index()<=score_num-1){
							$(this).addClass('on');
						}else{
							$(this).attr('class','');
						}
					});
					p.hide();
				});
				$(this).click(function(){
					score_num = $(this).index()+1;
					span.text(score_num+"<?php echo lang('t_score'); ?>");
					p.hide();
					var score =$(this).index()+1;
					$('#score_num').val(score);
				});
			});
		}

		function submit_as(){
			var curEle = $(this);
			var oldSubVal = curEle.text();
			curEle.html($('#loadingTxt').val());
			curEle.attr("disabled","disabled");
			$.ajax({
				type: "POST",
				url: "/ucenter/my_tickets/do_reply",
				data: $('#reply_tickets').serialize(),
				dataType: "json",
				success: function (data) {
					if (data.success) {
//						$('.alert').removeClass('hidden');
//						setTimeout(function(){
//							window.location.reload();
//						},3000);
						layer.msg(data.msg);
						$('#content').val('');
						window.location.reload();
					} else {
						$('#tickets_msg').text('× '+ data.msg).css('color','red');
					}
					curEle.html(oldSubVal);
					curEle.attr("disabled",false);
				}
			});
		}

		//upload
		var i=0;//初始化数组下标
		var uploadLimit = 10;
		$('#file_upload').uploadify({
			'auto'     : true,//自动上传
			'removeCompleted' : false,
			'isExists':'<?php echo lang('is_exists') ?>',
			"remain_upload_limit":'<?php echo lang('remain_upload_limit') ?>',
			"queue_size_limit":'<?php echo lang('queue_size_limit') ?>',
			"exceeds_size_limit":'<?php echo lang('exceeds_size_limit') ?>',
			"is_empty":'<?php echo lang('is_empty') ?>',
			"not_accepted_type":'<?php echo lang('not_accepted_type') ?>',
			"upload_limit_reached":'<?php echo lang('upload_limit_reached') ?>',
			'removeTimeout' : 1,//文件队列上传完成1秒后删除
			'swf'      : '<?php echo base_url('ucenter_theme/lib/uploadify/uploadify.swf'); ?>',
			'uploader' : '<?php echo base_url('ucenter/add_tickets/my_upload_attach');?>',
			'method'   : 'post',
			'buttonText' : '<?php echo lang('button_text'); ?>',
			'multi'    : true,
			'uploadLimit' : 10,
			'queueSizeLimit' : 10,
			'fileTypeDesc' : 'Image Files',
			'fileTypeExts' : '<?php echo config_item('tickets_upload_file_type'); ?>',
			'fileSizeLimit' : '8096KB',
			'cancelImage': '<?php echo base_url('ucenter_theme/lib/uploadify/uploadify-cancel.png')?>',
			'formData' : { 'userInfo' : '<?php echo get_cookie('userInfo');?>'},
			'onUploadSuccess' : function(file, data, response) {
				var res=JSON.parse(data);
				var f_id= '#SWFUpload_0_'+i;
				if(res.success == 1){
					//$(f_id).children('.cancel').children('a').attr('f_name',res.data.file_name);
					$(f_id).children('.cancel').children('a').attr('path_name',encodeURI(res.data.path_name));
					$(f_id).children('.cancel').show();
					$(f_id).children('.uploadify-progress').hide();
					$('.upload').append('<input name='+res.data.raw_name+' id='+res.data.raw_name+' type=hidden value='+encodeURI(file.name+'|'+ res.data.path_name+'|'+res.data.file_ext)+'>');
				}else{
					delete this.queueData.files['SWFUpload_0_'+i];
					$('#file_upload').uploadify('settings','uploadLimit', ++uploadLimit);
					layer.msg(res.msg);
					$(f_id).remove();
				}
				i++;
			},
			'onSelectError' : function(file) {
				i++;
			},
			'onCancel' : function(file) {//重复文件取消时自增id
				i++;
			},
			'onQueueComplete' : function(queueData) {
				//$(f_id).children('.uploadify-progress').hide();
				$('#file_upload-queue').children('.uploadify-queue-item').each(function(i){
					//上传成功后的删除事件
					$(this).children('.cancel').children('a').click(function(){
						var id =  $(this).parent('.cancel').parent('.uploadify-queue-item').attr('id');
						var num = $('.uploadify-queue-item').size();
						delete queueData.files[id];
						queueData.uploadsSuccessful = num-1;
						$('#file_upload').uploadify('settings','uploadLimit', ++uploadLimit);
						var path_name = $(this).attr('path_name');
						var arr = path_name.split('/');
						var id  = arr[arr.length-1].split('.')[0];
						$('.upload').children('#'+id).remove();
						$.ajax({
							type:"post",
							url:"<?php echo base_url('ucenter/add_tickets/delete_attach');?>",
							data:{path_name:path_name},
							dataType:"json",
							success:function(res){
								if(res.success){
									//layer.msg(res.msg);
								}
							}
						});
					});
				});
			}
		});
	});

		var system  ={
			win : false,
			mac : false,
			xll : false
		};
		var p       = navigator.platform;
		system.win  = p.indexOf("Win") == 0;
		system.mac  = p.indexOf("Mac") == 0;
		system.x11  = (p == "X11") || (p.indexOf("Linux") == 0);
		if(!system.win && !system.mac && !system.xll){
			$('.upload').hide();
			$('.upload_tips').append("<?php echo lang('not_support_mobile_upload'); ?>");
		}
</script>

