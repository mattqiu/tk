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
	<script type="text/javascript" src="<?php echo base_url('themes/admin/javascripts/tickets_base.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
	<script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/layer2/layer.js?v=1'); ?>"></script>
	<style>
		.fr{float: right;margin: 3px 7px;}
		.title{text-align: center;font-size: 13px;max-width: 380px;margin: 0 auto;overflow: hidden;margin-top: 2px;}
		.head_tickets_type{margin-left: 2px;margin-right: 2px;}
		.msg_big_box{height: 420px;margin: 5px 5px 0px 5px;background-color: #eeeeee;overflow-y: auto;clear: both;}
		.well-msg{padding: 1px 1px;height: auto;overflow: hidden;border: 0px;background-color: #eeeeee;margin-bottom: 5px;box-shadow: none;}
		.left-msg{clear: both;background-color: #fff;float: left;border-radius: 4px;max-width: 500px;padding: 12px;word-wrap: break-word;}
		.right-msg{clear: both;background-color:  #7fb2f2;float: right;border-radius: 4px;max-width: 500px;padding: 12px;word-wrap: break-word;}
		.left-attach-box{margin-top: 2px;float: left;clear: both;max-width: 500px;width: auto;background-color: #d7d7d7;border-radius: 5px;}
		.left-attach-box-pic{float:left; height:30px;margin-right: 2px;}
		.left-attach-box-file{float: left; height:16px;clear: both;margin-top: 0px;padding: 0px;font-size: 11px;}
		.right-attach-box{margin-top: 2px;float: right;clear: both;max-width: 500px;width: auto;background-color: #d7d7d7;border-radius: 5px;}
		.right-attach-box-pic{float: right; height:30px;margin-left: 2px;}
		.right-attach-box-file{float: right; height:16px;clear: both;margin-top: 0px;padding: 0px;font-size: 11px;}
		.example-image{height: 25px;width: 25px;margin-bottom: -6px;}
		.example-image-link{margin: 0px 6px;padding: 0px;}
		.input_box{height: 175px;;margin: 0px 5px 5px 5px;padding: 3px;background-color: #fff;border-top:0px;border: none;}
		.input_content{float: left;clear: both;width: 100%}
		#content{}
		.select_span{margin-left: 10px;}
		.reply_submit{float: left;}
		.upload{float: right;max-width: 500px;}
		.uploadify{float: right;}
		.uploadify-queue{clear: both;}
		.uploadify-queue-item{padding: 0px;margin-top: 2px;float: right;margin-left: 3px;}
		.can_not_reply{color: red;}
		.change_c{float: left;margin: 2px -8px;}
		#content{overflow-y: visible;}
	</style>
</head>
<body>
<!--start-->
	<div class="fr"><img src="<?php echo base_url('themes/admin/images/tips_refresh.png'); ?>"></div>
	<div class="title">#<span><?php if(!empty($rows) && isset($rows['org'])){ echo $rows['org']['id'];} ?></span>
						<select class="head_tickets_type" style="width: 100px;">
						<?php if(!empty($tickets_type)){
							foreach($tickets_type as $k=>$v){?>
								<option value="<?php echo $k; ?>"<?php if(isset($rows['org']['type']) && $k==$rows['org']['type']){echo 'selected';} ?>><?php echo lang($v);?></option>
							<?php } }?>
						</select>
						<span><?php if(!empty($rows) && isset($rows['org'])) echo $rows['org']['title'];?></span>
	</div>
<?php //信息合并
if(!empty($rows) && isset($rows['org']) && !empty($rows['org'])){
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
			<?php if($row['sender']==0){?>
				<span style="float: left"><?php echo isset($tickets_user[$row['uid']]) ? $row['uid'].'('.$tickets_user[$row['uid']]['user_name'].')':''; ?></span>
				<span style="float: left;margin-left: 10px;"><?php echo date('m/d H:i',strtotime($row['create_time'])); ?></span>
				<div class="left-msg "><?php echo $row['content'];?></div>
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
							<?php 	echo '<a href='.base_url('admin/my_tickets/download_attach/'.$item['id']).'>'.$item['name'].'</a></br>';?>
							</div>
							<?php }?>
						<?php }?>

					</div>
				<?php }?>
			<?php } ?>
			<?php if($row['sender']==1){?>
			<div class="well well-msg">
				<span style="float: right;"><?php echo date('m/d H:i',strtotime($row['create_time'])); ?></span>
				<span style="float: right;margin-right: 12px;"><?php if(isset($all_cus[$row['admin_id']])){echo explode('@',$all_cus[$row['admin_id']]['email'])[0];} ?></span>
				<div class="right-msg" style="<?php if($row['type']==100){echo 'background-color:#CCC;color:#000';} ?>">
					<div style="color: red;"><?php if($row['type']==100){echo '['.lang('tickets_tips').']';} ?></div>
					<p><?php echo $row['content'];?></p>
				</div>
				<div class="right-attach-box">
						<?php if(isset($row['attach']) && !empty($row['attach'])){ ?>
							<?php foreach($row['attach'] as $item){ ?>
								<?php if(in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
									<div class="right-attach-box-pic">
									<a data-lightbox="pic-<?php echo $row['id'];?>" href="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image-link" rel="" fullname="<?php echo $item['name']; ?>">
										<img width="18px" height="18px" alt="<?php echo lang('picture_not_exist'); ?>" src="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image"></a>
									</div>
								<?php } ?>
							<?php } ?>

							<?php foreach($row['attach'] as $item){ ?>
								<?php if(!in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
								<div class="right-attach-box-file">
								<?php	echo '<a href='.base_url('admin/my_tickets/download_attach/'.$item['id']).'>'.$item['name'].'</a></br>';?>
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
		<div class="change_c">
			<span class="select_span">
           		<?php echo lang('tickets_template');?>&nbsp;
                <select class="template_type" name="template" style="width: 130px;">
					<option value="e"><?php echo lang('tickets_template');?></option>
					<?php
						foreach(config_item('tickets_problem_type') as $k=>$v){?>
							<option value="<?php echo $k; ?>"><?php echo lang($v);?></option>
					<?php } ?>

				</select>
           	</span>
			<span style="">
                <select  class="template_name" style="width: 102px;margin-bottom: 2px;margin-left: 10px;">
					<option value="e"><?php echo lang('t_template_name')?></option>
				</select>
            </span>
			<span class="select_span">
           		<?php echo lang('priority');?>&nbsp;
                <select class="priority" name="priority" style="width: 70px;">
					<option value="<?php echo $rows['org']['priority']; ?>">
						<?php echo lang(config_item('tickets_priority')[$rows['org']['priority']]);?></option>
					<?php
					$pri = config_item('tickets_priority');
					unset($pri[$rows['org']['priority']]);
					foreach($pri as $k=>$v){?>
						<option value="<?php echo $k;?>"><?php echo lang($v); ?></option>
					<?php } ?>
				</select>
           </span>
            <span class="select_span">
            	<?php echo lang('tickets_transfer');?>&nbsp;
                <select class="transfer" name="transfer" style="width: 160px;">
					<option value=""><?php echo lang('pls_select_customer');?></option>
					<?php if(!empty($cus) && isset($cus)){
						foreach ($cus as $c) { ?>
							<option value="<?php echo $c['id']; ?>" ><?php echo $c['job_number'].' ('.explode('@',$c['email'])[0].')';?></option>
						<?php } ?>
					<?php }?>
				</select>
            </span>
		</div>
		<form class="form-horizontal" id="reply_tickets" method="post" action="admin/">
			<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
			<div class="inline reply_input">
				<div>
					<div class="input_content">
						<textarea id="content" maxlength="1500" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> class="form-control"  placeholder="<?php echo lang('pls_t_content');?>" name="content" rows="6" style="width: 100%; height: 100px;"></textarea>
					</div>
					<div style="float: right;margin-top: -18px;margin-right: 14px;"><?php echo lang('remain_'); ?><span id='count'>1500</span><?php echo lang('_words'); ?></div>
				</div>
				<div style="clear: both;"></div>
				<div class="upload_tips" style="color: red;"></div>
				<div class="upload">
					<input type="file" name="file_upload" id="file_upload" />
				</div>
				<div class="reply_submit">
					<div class="dropdown">
						<button type="button" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> status="2" name="submit" class="btn btn-default"><?php echo lang('submit_as_waiting_reply')?></button>
						<button type="button" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> status="3" name="submit" class="btn btn-default"><?php echo lang('submit_as_waiting_discuss')?></button>
						<button type="button" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> status="100" name="submit" class="btn btn-default"><?php echo lang('add_tickets_tips')?></button>
						<button type="button" <?php if($rows['org']['status']==4 || $rows['org']['status']==5){ echo "disabled='disabled'";} ?> status="6" name="submit" class="btn btn-default"><?php echo lang('apply_close')?></button>
						<span class="can_not_reply"><?php if(!empty($rows) && isset($rows['org'])){if($rows['org']['status']==4 || $rows['org']['status']==5){echo lang('tickets_closed_can_not_reply');}}?></span>
					</div>
					<span class="msg" id="tickets_msg"></span>
				</div>
				<input type="hidden" name="id" value="<?php if(!empty($rows)){ echo $rows['org']['id'];} ?>">
			</div>
		</form>

	</div>
<?php }?>
<!--end-->

</body>
<script>
	var tid = <?php if(!empty($rows)){ echo $rows['org']['id'];}else{echo 0;} ?>;
	function refresh(){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('admin/my_tickets/get_new_msg');?>",
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
		//setInterval(function(){refresh()},20000);
		autosize($('textarea'));
		var id = 0;
			id = <?php if(!empty($rows)){ echo $rows['org']['id'];}else{echo 0;} ?>;
		var msg_big_box_obj = $(".msg_big_box");
		var content_obj 	= $('#content');
		var count_obj   	= $('#count');
		var t_note_content 	= getCookie('t_content'+id);
		content_obj.val(t_note_content);
		msg_big_box_obj.scrollTop(msg_big_box_obj[0].scrollHeight);
		//refresh
		$('.fr').click(function(){
			window.location.reload();
		});

		//type
		$('.head_tickets_type').change(function(){
			var type_val = $(this).find("option:selected").val();
			$.ajax({
				type:'post',
				url:'<?php echo base_url('admin/my_tickets/change_tickets_type'); ?>',
				data:{id:id,val:type_val},
				dataType:'json',
				success:function(res){
					if(res.success==1){
						layer.msg(res.msg);
						parent.$('.'+id+'type').empty().append(res.t_type);
					}else{
						layer.msg(res.msg);
					}
				}
			});
		});
		//template
		$('.template_type').change(function(){
			$('.template_name').empty().append("<option value='e'><?php echo lang('t_template_name')?></option>");
			var val = $(this).find("option:selected").val();
			if( $(this).val()!='e'){
				if( $(this).val()!='e'){
					$.ajax({
						type:"post",
						url:"<?php echo base_url('admin/my_tickets/get_template');?>",
						data:{t:val},
						dataType:"json",
						success:function(res){
							if(res.success==1){
								for(var tpl in res.msg){
									$('.template_name').append("<option value="+encodeURIComponent(res.msg[tpl].content)+">"+res.msg[tpl].name+"</option>");
								}
							}
						}
					});
				}
			}
		});
		$('.template_name').change(function(){
			var val = $(this).find("option:selected").val();
				val = decodeURIComponent(val);
			if(val!='e'){
				$('#content').val(val);
				$('#count').empty().append(1500-val.length);
			}else {
				$('#content').val('');
			}
		});
		//priority
		$('.priority').change(function(){
			var val = $(this).val();
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('admin/my_tickets/change_tickets_priority'); ?>",
				dataType: "json",
				data:{id:id,p:val},
				success: function (res) {
					if(res.success==1){
						layer.msg(res.msg);
						parent.$('.'+id+'priority').empty().append(res.lg);
						if(res.p==1){
							parent.$('.'+id+'priority').css('color','blue');
						}else if(res.p==2){
							parent.$('.'+id+'priority').css('color','red');
						}else{
							parent.$('.'+id+'priority').css('color','green');
						}
					}else{
						layer.msg(res.msg);
					}
				}
			});
		});
		//to other
		$('.transfer').change(function(){
			var val = $(this).val();
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('admin/my_tickets/do_transfer') ?>",
				dataType: "json",
				data:{id:id,c_id:val},
				success: function (res) {
					if(res.success==1){
						layer.msg(res.msg);
						parent.$('.'+id+'t').empty();
						$('.btn-default,#content,.transfer,.priority,.template_type,.template_name').attr('disabled',true);
						$('.upload,.fr').empty();
					}else{
						layer.msg(res.msg);

					}
				}
			});
		});
		/**计算字数**/
		count_obj.css('color','#FF0000').empty().append(1500-content_obj.val().length);
		content_obj.focus().keyup(function(){
			$('#count').empty().append(1500-content_obj.val().length);
			addCookie('t_content'+id,content_obj.val(),48);
		});
		/**判断状态**/
		var s = <?php if(!empty($rows['org'])){if($rows['org']['status']==4 || $rows['org']['status']==5){echo 'true';}else{echo 'false';}}else{echo 'false';} ?>;
		if(s){
			$('.upload, .change_c').empty();
			$('#dropdownMenu1').attr('disabled',true);
		}
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
                },
            },
        });
        $("[rel=tooltip]").tooltip();
		$("button[name='submit']").click(function () {
			var cont =$('#content').val();
			if($.trim(cont).length<=0){
				if($(this).attr('status')==2 || $(this).attr('status')==100){
					layer.msg('<?php echo lang('pls_t_content'); ?>');
					return;
				}
			}
			$('#reply_tickets').append("<input type='hidden' name='status' value="+$(this).attr('status')+">");		
			var curEle = $(this);
			var oldSubVal = curEle.text();
			curEle.html($('#loadingTxt').val());
			$.ajax({
				type: "POST",
				url: "/admin/my_tickets/do_reply",
				data: $('#reply_tickets').serialize(),
				dataType: "json",
				success: function (data) {
					if (data.success) {
//						$('.alert').removeClass('hidden');
//						setTimeout(function(){
//							window.location.reload();
//						},3000);
						layer.msg(data.msg);
						deleteCookie('t_content'+id);
						$('#content').val("");
						window.location.reload();
					} else {
						$('#tickets_msg').text('× '+ data.msg).css('color','red');
					}
					curEle.html(oldSubVal);
				}
			});
		});

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
			'uploader' : '<?php echo base_url('admin/my_tickets/my_upload_attach');?>',
			'method'   : 'post',
			'buttonText' : '<?php echo lang('button_text'); ?>',
			'multi'    : true,
			'uploadLimit' : 10,
			'queueSizeLimit' : 10,
			'fileTypeDesc' : 'Image Files',
			'fileTypeExts' : '<?php echo config_item('tickets_upload_file_type'); ?>',
			'fileSizeLimit' : '8096KB',
			'cancelImage': '<?php echo base_url('ucenter_theme/lib/uploadify/uploadify-cancel.png')?>',
			'formData' : { 'adminUserInfo' : '<?php echo get_cookie('adminUserInfo');?>'},
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
							url:"<?php echo base_url('admin/my_tickets/delete_attach');?>",
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

