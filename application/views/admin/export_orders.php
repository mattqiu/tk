
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
	<form class="form_ajax"  method="post" action="<?php echo base_url('admin/export_orders/report_to_usa') ?>">
		<select class="input-medium" name="ext">
			<option value='2007'>EXCEL 2007</option>
			<option value='2003'>EXCEL 2003</option>
		</select>
		<select class="input-medium" name="status" id="status">
			<?php
			foreach ($status_map as $k => $v)
			{
				if($adminInfo['role'] == 6 && !in_array($k,array('1','3'))){ continue ;}
				echo "<option value=\"{$k}\">{$v['text']}</option>";
			}
			?>
			<?php if($adminInfo['role'] != 6){?>
			<option value="66"><?php echo lang('order_status_4') ?></option>
                        <option value="88"><?php echo lang('order_status_5') ?></option>
			<?php }?>
		</select>
		<select class="input-medium" name="area" class="com_type">
			<?php
			foreach ($area_map as $k => $v)
			{
				if($adminInfo['role'] == 6 && $k!= 410){ continue ;}
				if($adminInfo['role'] == 7 && $k!= 344){ continue ;}
				echo "<option value=\"{$k}\">{$v}</option>";
			}
			?>
		</select>
            <!-- 运营方 -->
                <select class="input-medium" id="supplier">
			<?php foreach ($supplier_arr as $k => $v){ ?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                        <?php } ?>
		</select>

		<?php if($adminInfo['id'] == 1){?>
		<select name="cate_id" class="input-medium">
			<option value=""><?php echo lang('label_sel_cate');?></option>
			<?php foreach($category_all as $val) { ?>
				<?php
				if($val['level'] == 2) {?>
					<option value="<?php echo $val['cate_sn']; ?>"><?php echo $val['html'],'|-',$val['cate_name']; ?></option>
				<?php
				}
				?>
			<?php  } ?>
		</select>
		<?php }?>
		<select class="input-medium" name="select_is_export_lock" class="com_type">
			<option value=""><?php echo lang('all')?></option>
			<option value="0"><?php echo lang('admin_no_lock')?></option>
			<option value="1"><?php echo lang('admin_yes_lock')?></option>
		</select>

        <select class="input-medium" name="order_type" class="com_type">
            <option value=""><?php echo lang('all_group')?></option>
            <option value="1"><?php echo lang('choose_group')?></option>
            <option value="2"><?php echo lang('admin_as_upgrade_order')?></option>
            <option value="3"><?php echo lang('generation_group')?></option>
            <option value="4"><?php echo lang('retail_group')?></option>
            <option value="5"><?php echo lang('exchange_order')?></option>
        </select>

		<select name="store_code" class="input-medium" id="store_code" multiple="multiple">
			<?php
			foreach ($shipper_all as $v)
			{
				echo "<option ";
				echo "value=\"{$v['supplier_id']}\">"."{$v['supplier_name']}"."</option>";
			}
			?>
		</select>
		<input type="hidden" id="store_code_arr" name="store_code_arr">
		<br>
		<br><br>
                <b><?php echo lang('order_pay_date')?>:</b>
                <input class="Wdate span2" id="start_date" type="text" name="start_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('start_date'); ?>">
                <input class="Wdate span2" id="end_date" type="text" name="end_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('end_date'); ?>"><br>
		<b><?php echo lang('deliver_time')?>:</b>
                <input class="Wdate span2" id="start_deliver_date" type="text" name="start_deliver_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('deliver_time')?>">
		<input class="Wdate span2" id="end_deliver_date" type="text" name="end_deliver_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('deliver_time')?>"><br>
                <b class="update_time"><?php echo lang('order_update_date')?>:</b>
                <input class="Wdate span2" id="start_update_date" type="text" name="start_update_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('order_update_date')?>">
		<input class="Wdate span2" id="end_update_date" type="text" name="end_update_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('order_update_date')?>">
		<br>
                <input autocomplete="off"  name="is_export_lock" type="checkbox" value="1"><?php echo lang('admin_select_is_lock')?><br><br>
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
         $("#supplier").change(function(){
            var supplier_id = $(this).children('option:selected').val();
            var html ="";
            $.ajax({
                type: "POST",
                url: "/admin/export_orders/ajax_shipper",
                data: {supplier_id: supplier_id},
                dataType: "json",
                success: function (res) {
                    $(res).each(function(k,v){
                        html +='<option value="'+v.supplier_id+'">'+v.supplier_name+'</option>';
                    });
                    $("#store_code").html(html);
                    query_select();
                }
            });
        })
        query_select();
        function query_select(){
            $("#store_code").multipleSelect({
                    filter: true, 
                    multiple: true,
                    placeholder: "<?php echo lang('admin_oper_shipper_ALL')?>",
                    width: 750,
                    multipleWidth: 350,
                    selectAll: false
            });
            $('[data-name="selectItemstore_code"]').click(function(){
                    var store_value = $("#store_code").multipleSelect("getSelects");
                    $('#store_code_arr').val(store_value);
            });
        }
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
		setTimeout(cleanStoreCodeArr,500);
		setTimeout(cleanSubmitBtn,5000);
		$("#store_code").multipleSelect('uncheckAll');
		return true;
	}

	function cleanStoreCodeArr(){
		$('#store_code_arr').val('');
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
                url: "/admin/export_orders/export_status_ajax",
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
                                "<div id='_filename'>"+v.filename+"</div>" +
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
                $.ajax({type: "POST",url: "/admin/export_orders/download_change_s",data:{id:id}});
                window.open("/admin/export_orders/download_file?download_id="+id);
            }else{
                layer.alert("文件为不存在");
            }
        });
        $(document).on("click",".delete",function(){
            var id = $(this).parent().attr("data-id");
            if(id){
                $.ajax({type: "POST",url: "/admin/export_orders/del_change_s",data:{id:id}});
            }else{
                layer.alert("文件为空");
            }
        });
        $(".submit_btn").click(function(){
//            alert('临时停用导出，正在恢复处理中...');return false;
            var data = $('.form_ajax').serialize();
            $.ajax({
                type: "POST",
                url: "/admin/export_orders/report_to_usa",
                data:data,
                dataType: "json",
                success: function (res) {
                    if (res.success == 1) {
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
