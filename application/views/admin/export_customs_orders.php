
<link rel="stylesheet" href="<?php echo base_url('themes/admin/stylesheets/multiple-select.css?v=1')?>">
<script src="<?php echo base_url('themes/admin/javascripts/multiple-select.js?v=1'); ?>"></script>

<link rel="stylesheet" href="<?php echo base_url('themes/admin/stylesheets/jquery-ui.min.css')?>">
<script type="text/javascript" src="<?php echo base_url('themes/admin/javascripts/jquery-ui.min.js'); ?>"></script>

<div class="well">
	<?php if (isset($err_msg)): ?>
		<div class="well">
			<p style="color: red;"><?php echo $err_msg; ?></p>
		</div>
	<?php endif; ?>
	<form class="form_ajax"  method="post" action="<?php echo base_url('admin/export_customs_orders/report_to_usa') ?>">
		<select class="input-medium" name="status" id="status">
			<?php
			foreach ($status_map as $k => $v)
			{
				if($adminInfo['role'] == 6 && !in_array($k,array('1','3'))){ continue ;}
				echo "<option value=\"{$k}\">{$v['text']}</option>";
			}
			?>
		</select>

		<br>
		<br><br>
                <b><?php echo lang('order_pay_date')?>:</b>
                <input class="Wdate span2" id="start_date" type="text" name="start_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('start_date'); ?>">
                <input class="Wdate span2" id="end_date" type="text" name="end_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('end_date'); ?>"><br>
		<br>
		<a class="btn submit_btn" type="" onclick="return clearData();"><i class="icon-download-alt"></i> <?php echo lang('export') ?></a>
        <?php
        if(isset($adminInfo['id']) and in_array($adminInfo['id'],['63','281','230','198'])) {
            ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn submit_sync" title="数据量大于10万自动强制使用csv格式导出"><i
                        class="icon-download-alt"></i> 离线<?php echo lang('export') ?>(nancy testing...)</a>
            <?php
        }
        ?>
	</form>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

</div>
<style>
    .file_opera{font-size:24px;}
    .file_opera span{cursor:pointer;}
    .file_opera .delete{color:red;}
    .file_opera .retry{color:green;}
    .file_opera .download{color:blue;}
    .file_opera .delete_ext:hover{color:red;font-size:30px;}
    .file_opera .retry_ext:hover{color:green;font-size:30px;}
    .file_opera .download_ext:hover{color:blue;font-size:30px;}
</style>
<div id="processbarlistoffline"></div>
<div id="processbarlist"></div>

<script type="text/javascript" src="<?php echo base_url('themes/admin/javascripts/export_order.js?v=201704111144'); ?>"></script>

<script>
    status_op($("#status").val());
    $("#status").change(function(){
            status_op($(this).val());
    })
    function status_op(status){
      if(status==1){
          $("#start_update_date,#end_update_date,.update_time").show();
      }else{
          $("#start_update_date,#end_update_date,.update_time").hide();
      }
     // alert(status);
    }

	function clearData(){
		$('.submit_btn').attr('disabled',true);
		setTimeout(cleanSubmitBtn,5000);
		$("#store_code").multipleSelect('uncheckAll');
		return true;
	}

	function cleanSubmitBtn(){
		$('.submit_btn').attr('disabled',false);
	}
        var s_time;
        //date_3();
        s_time = setInterval('date_3();', 3000);
        function date_3(){
            var process = "";
            $.ajax({
                type: "POST",
                url: "/admin/export_customs_orders/export_status_ajax",
                dataType: "json",
                success: function (res) {
                    if(res.succ){
                        $(res.list).each(function(k,v){
                            var status_name = "";
                            var status_down ="";
                            var status_del ="";
                            switch(v.status){
                                case "0" :status_name="队列等待中";status_down="none";status_del="block";break;
                                case "1" :status_name="处理中";status_down="none";status_del="block";break;
                                case "2" :status_name="已完成";status_down="brock";status_del="none";break;
                                case "4" :status_name="处理失败";status_down="none";status_del="block";break;
                                case "5" :status_name="没有符合条件的数据";status_down="none";status_del="block";break;
                            }
                            process += "" +
                                "<div class='' style='height:3px;display:block;float:left;width:100%;'></div>" +
                                "<div class='span12' id='_contains'>" +
                                "<div class='span3'>" +
                                "<label></label>" +
                                "<div id='_filename'>"+v.file_name+"</div>" +
                                "</div>"+
                                "<div class='span3'>" +
                                "<div><div class='progress-labels'>"+status_name+"</div></div>" +
                                "</div>"+
                                "<div class='span1'>" +
                                "<label></label>" +
                                "<div data-id='"+v.id+"' class='file_opera'>"+
                                "<span class='download' title='下载' style='display:"+status_down+"' alt='下载'><i class='icon-download'></i>&nbsp;</span>"+
                                "<span class='delete' title='删除' style='display:"+status_del+"' alt='删除'><i class='icon-trash'></i>&nbsp;</span>"+
                                "</div>" +
                                "</div>"+
                                "<div class='span4'>" +
                                "<label></label>" +
                                "<div></div>" +
                                "</div>"+
                                "</div>";
                        })
                        $('#processbarlist').html(process);
                    }else{
                        clearInterval(s_time);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        }
        $(document).on("click",".download",function(){
            var id = $(this).parent().attr("data-id");
            if(id){
                $.ajax({type: "POST",url: "/admin/export_customs_orders/download_change_s",data:{id:id}});
                window.open("/admin/export_customs_orders/download_file?download_id="+id);
            }else{
                layer.alert("文件为不存在");
            }
        });
        $(document).on("click",".delete",function(){
            var id = $(this).parent().attr("data-id");
            if(id){
                $.ajax({type: "POST",url: "/admin/export_customs_orders/del_change_s",data:{id:id}});
            }else{
                layer.alert("文件为空");
            }
        });
        $(".submit_btn").click(function(){
            var data = $('.form_ajax').serialize();
            $.ajax({
                type: "POST",
                url: "/admin/export_customs_orders/report_to_usa",
                data:data,
                dataType: "json",
                success: function (res) {
                    if (res.success == 1) {
                        layer.alert(res.msg);
                        clearInterval(s_time);
                        s_time = setInterval('date_3();', 3000);
                    }else{
                        layer.alert(res.msg);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        })
</script>
