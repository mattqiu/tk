<style>
	label{ padding: 15px 0px 0px 0px }
</style>
<!--upload-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/uploadify/uploadify.css')?>"/>
<script type="text/javascript" src="<?php echo base_url('ucenter_theme/lib/uploadify/jquery.uploadify-3.1.min.js')?>"></script>
<div class="well">

<form class="form-horizontal" id="add_tickets" method="post">
        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">

		<label for="problem_type"><?php echo lang('tickets_type')?></label>
		<select name="type" id="problem_type" class="input-xxlarge">
			<option value=""><?php echo lang('tickets_type')?></option>
			<?php if($pro_type) {foreach($pro_type as $k=>$v){ ?>
				<option value="<?php echo $k;?>"><?php echo lang($v)?></option>
			<?php }}?>
		</select>

	   <label for=""><?php echo lang('tickets_title')?></label>
		<div class="inline">
			<input id="title" class="input-xxlarge" type="text" name="title" value="" placeholder="<?php echo lang('max_limit_').'100'.lang('_words');?>" maxlength="100" >
		</div>

		<label for=""><?php echo lang('tickets_content')?></label>
		<div class="inline" style="width: 532px;">
			<div>	
			<textarea  id="content" maxlength="1000" placeholder="<?php echo lang('max_limit_').'1000'.lang('_words');?>" name="content" rows="9" style="width: 532px; height: 201px;"></textarea>
		  	</div> 
		   <div style="float: right;clear: both;"><?php echo lang('remain_'); ?><span id='count'>1000</span><?php echo lang('_words'); ?></div>
		</div>		
		<div style="clear: both;"></div>
		<div class="upload_tips" style="color: red;"></div>
		<div style="margin-top: 10px;" class="upload">
		<input type="file" name="file_upload" id="file_upload" />
		</div>
        <div style="margin-top:20px; ">
      	   <button type="button" name="submit" class="btn btn-primary"><?php echo lang('submit')?></button>
			<span class="msg" id="tickets_msg"></span>
        </div>
        <div class="alert alert-info" style="color: #00466C;letter-spacing: 2px;font-size: 15px;">
			<span><?php echo lang('tickets_kindly_notice');?>：</span>
			<br>
			<?php echo lang('add_tickets_tip1')?>
			<br>
			<?php echo lang('add_tickets_tip2')?>
			<br>
			<?php echo lang('add_tickets_tip3')?>
			<br>
			<?php echo lang('add_tickets_tip4'); ?>
        </div>
</form>
</div>

<div id="confirm_submit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-body" id="confirm_submit_message" style="text-align: center;">
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary" id="confirm_ok"><?php echo lang('jixu_submit'); ?></button>
		<button autocomplete="off" class="btn btn-primary" id="confirm_cancel"><?php echo lang('jiexie_previous'); ?></button>
	</div>
</div>

<script>
	$(function(){
		$('#count').css('color','#FF0000');
			var cont = $('#content').val();
			$('#count').empty().append(1000-cont.length);
			$('#content').keyup(function(){
			cont = $('#content').val();
			$('#count').empty().append(1000-cont.length);
		});
		$('#problem_type').change(function(){
			if($(this).val()!=''){
				$('#tickets_msg').text('');
			}
		});
		$("button[name='submit']").click(function () {
			var title = $('#title').val();
			var cont = $('#content').val();
			if($.trim(title).length<=0){
				layer.msg('<?php echo lang('pls_t_title'); ?>');
				return;
			}
			if($.trim(cont).length<=0){
				layer.msg('<?php echo lang('pls_t_content'); ?>');
				return;
			}
			var curEle = $(this);
			var oldSubVal = curEle.text();
			curEle.html($('#loadingTxt').val());
			curEle.attr("disabled","disabled");

			$.ajax({
				type: "POST",
				url: "/ucenter/add_tickets/do_check_tickets",
				data: $('#add_tickets').serialize(),
				dataType: "json",
				success: function (data) {
					if (data.success == 1) {
						$("#confirm_submit").modal();
						$("#confirm_submit_message").html(data.msg);
						pre_submit_tickets(data.tickets_id);
					} else if(data.success == 0) {
						curEle.attr("disabled",false);
						$('#tickets_msg').text('× '+ data.msg);
					}else if(data.success == 2){
						submit_tickets();
					}
					curEle.html(oldSubVal);
					//curEle.attr("disabled",true);
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

	function pre_submit_tickets(tickets_id){
		document.getElementById('confirm_ok').onclick = function(){
			$('#confirm_ok').attr("disabled",true);
			submit_tickets();
			$("#confirm_submit").modal('hide');
		};
		document.getElementById('confirm_cancel').onclick = function(){
			$("#confirm_submit").modal('hide');
			$("button[name='submit']").attr("disabled",true);
			location.href = '/ucenter/my_tickets?title_or_tid='+tickets_id;
		};
	}

	function submit_tickets(){
		$.ajax({
			type: "POST",
			url: "/ucenter/add_tickets/do_add",
			data: $('#add_tickets').serialize(),
			dataType: "json",
			success: function (data) {
				if (data.success) {
					$("button[name='submit']").attr("disabled",true);
					layer.msg(data.msg,{
						time:1800,
						end:function(){
							window.location.href="<?php echo base_url('ucenter/my_tickets') ?>";
						}
					});
				} else {
					layer.msg(data.msg);
					$('#confirm_ok').attr("disabled",false);
					$("button[name='submit']").attr("disabled",false);
					//$('#tickets_msg').text('× '+ data.msg);
				}
			}
		});
	}

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


