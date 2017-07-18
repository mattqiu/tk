<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="email" value="<?php echo $searchData['email'];?>" class="input-small search-query mbot10" placeholder="<?php echo lang('admin_email_or_id')?>">
        <input type="text" name="uid" value="<?php echo $searchData['uid'];?>" class="input-small search-query mbot10" placeholder="<?php echo lang('admin_after_sale_uid')?>">
        <input type="text" name="as_id" value="<?php echo $searchData['as_id'];?>" class="input-medium mbot10" placeholder="<?php echo lang('admin_after_sale_id').'/'.lang('order_id')?>">
        <select name="status" class="user_ranks_sel mbot10 input-medium">
            <option value=""><?php echo lang('status')?></option>
            <?php if($curControlName == 'after_sale_order_list'){?>
                <option value="0" <?php echo $searchData['status']=='0'? 'selected':''?>><?php echo lang('admin_after_sale_status_0')?></option>
            <?php }?>
            <option value="1" <?php echo $searchData['status']=='1'? 'selected':''?>><?php echo lang('admin_after_sale_status_1')?></option>
            <?php if($curControlName == 'after_sale_order_list'){?>
                <option value="2" <?php echo $searchData['status']=='2'? 'selected':''?>><?php echo lang('admin_after_sale_status_2')?></option>
            <?php }?>
            <option value="3" <?php echo $searchData['status']=='3'? 'selected':''?>><?php echo lang('admin_after_sale_status_3')?></option>
            <?php if($curControlName == 'after_sale_order_list'){?>
                <option value="4" <?php echo $searchData['status']=='4'? 'selected':''?>><?php echo lang('admin_after_sale_status_4')?></option>
            <?php }?>
            <option value="5" <?php echo $searchData['status']=='5'? 'selected':''?>><?php echo lang('admin_after_sale_status_5')?></option>
            <option value="6" <?php echo $searchData['status']=='6'? 'selected':''?>><?php echo lang('admin_after_sale_status_6')?></option>
            <option value="7" <?php echo $searchData['status']=='7'? 'selected':''?>><?php echo lang('admin_after_sale_status_7')?></option>
        </select>
        <select name="refund_method" style="width:150px;" >
            <option <?php if(!isset($searchData['refund_method'])){echo 'selected';}?> value=""><?php echo lang('admin_after_sale_method')?></option>
            <option <?php if(isset($searchData['refund_method'])){echo $searchData['refund_method']=='0'? 'selected':'';}?> value="0"><?php echo lang('admin_after_sale_method_0') ?></option>
            <option <?php if(isset($searchData['refund_method'])){echo $searchData['refund_method']=='1'? 'selected':'';}?> value="1"><?php echo lang('admin_after_sale_method_1') ?></option>
            <option <?php if(isset($searchData['refund_method'])){echo $searchData['refund_method']=='2'? 'selected':'';}?> value="2"><?php echo lang('admin_after_sale_method_2') ?></option>
        </select>
        <?php if (!isset($batch_info)){ ?>
        <select name="type" class="user_ranks_sel mbot10 input-medium">
            <option value=""><?php echo lang('type')?></option>
            <option value="0" <?php echo $searchData['type']=='0'? 'selected':''?>><?php echo lang('admin_after_sale_type_0')?></option>
            <option value="1" <?php echo $searchData['type']=='1'? 'selected':''?>><?php echo lang('admin_after_sale_type_1')?></option>
            <?php if($adminInfo['id'] != 5){?>
                <option value="2" <?php echo $searchData['type']=='2'? 'selected':''?>><?php echo lang('admin_after_sale_type_2')?></option>
            <?php }?>
            <option value="3" <?php echo $searchData['type']=='3'? 'selected':''?>><?php echo lang('admin_after_sale_type_3')?></option>
        </select>
        <br />
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
       <?php }else{?>
            <input type="hidden" name="batch_id" value="<?php echo $searchData['batch_id'];?>" class="input-medium mbot10">
        <?php }?>
        <?php if (isset($batch_info) && $batch_info['status'] < 3) { ?>
            <?php if (in_array($adminInfo['id'],array(1,18,68,64,99))) { ?>
                <button class="btn submit_alipay" onclick="batch_Alipay('<?php echo $batch_info['id']; ?>');" type="button"> <?php echo lang('submit_alipay') ?></button>
                <button class="btn" onclick="cancel_batch(<?php echo $batch_info['id']; ?>)" type="button"> <?php echo lang('cancel_batch') ?></button>
            <?php } ?>
        <?php } ?>
            <?php if(isset($page_num)){?>
                <select style="width:60px;" id="page_num" name="page_num">
                    <option value="30" <?php echo $page_num == '30' ? 'selected' : '' ?> >30</option>
                    <option value="50" <?php echo $page_num == '50' ? 'selected' : '' ?> >50</option>
                    <option value="100" <?php echo $page_num == '100' ? 'selected' : '' ?> >100</option>
                </select>
            <?php }?>
                <?php if($searchData['status']=='1'&& in_array($adminInfo['id'],array(1,18,68,64))){ ?>
                                <select id="batch_status" name="batch_status" disabled>
                                        <option value="" ><?php echo lang('action')?></option>
                                        <option value="2" >生成退会批次</option>
                                </select>
                                <input id="btn_type" class="btn btn-primary" name="btn_type" type="button" value="<?php echo lang('submit')?>" onclick="return return_batch_order()" disabled autocomplete="off"/>
                <?php } ?>
                                <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>

</div>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.min.js?v=1'); ?>"></script>
<script>
    $("#page_num").change(function () {
        $(".form-inline").submit()
    });
	$(function(){
		$('.do_refund').click(function(){
			var as_id = $(this).attr('rel');
			$.post("/admin/admin_as_refund_list/do_refund", {as_id:as_id}, function (data) {
				if(data.success){
					location.reload();
				}else{
					layer.msg(data.msg);
					location.reload();
				}
			},'json');
		});
		$('.as_order_submit').click(function(){
			var as_id = $('.as_id').text();
			var remark = $('.remark').val();
			$.post("/admin/admin_as_refund_list/add_as_remark_info", {as_id:as_id,remark:remark}, function (data) {
				if(data.success){
					location.reload();
				}else{
					layer.msg(data.msg);
				}
			},'json');
		});
		$("#upload").click(function(){

			var btn = $("#upload");
			var oldSubVal = btn.text();
			$('#upload_form').ajaxSubmit({
				dataType:  'json',
				beforeSend: function() {
					btn.html($('#loadingTxt').val());
					btn.attr("disabled","disabled");
				},
				success: function(data) {

					if(data.success == 0){
						$('.upload_msg').html(data.msg).addClass('text-error');
						btn.html(oldSubVal);
						btn.attr("disabled",false);
					}else{

						$('.upload_msg').html(data.msg).addClass('text-success');
						setTimeout(function(){
							$('#as_upload_modal').modal('hide');
							location.reload();
						},1000);
					}

				},
				error:function(xhr){	//上传失败
					btn.html($('#loadingTxt').val());
					btn.attr("disabled","disabled");
				}
			});
		});
	});
	function show_upload_modal($uid){
		$('.upload_as_id').val($uid);
		$('#as_upload_modal').modal();
	}
	function get_as_remark_info(id){
		$.ajax({
			type: "POST",
			url: "/admin/admin_as_refund_list/get_as_remark_info",
			data: {id:id},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					$('#table_str').html(res.result.table_str);
					$('.as_id').html(res.result.as_id);
                                        $('.payee').html(res.result.payee_str);
					$('#as_remark_modal').modal();
				}
			}
		});
	}
	function as_reject(as_id,status){

		if(status == 6 || status == 7){
			info = true;
		}else if(status == 'del'){
			var info =confirm("<?php echo lang('sure');?>");
		}
		else{
			var info =prompt("<?php echo lang('refuse_reason');?>","");
		}
		if(info){
			$.post("/admin/admin_as_refund_list/as_reject", {as_id: as_id,status:status,remark:info}, function (data) {
				if(data.success){
					location.reload();
				}else{
					layer.msg(data.msg);
					location.reload();
				}
			},'json');
		}
	}
</script>
<div class="well">
	<form method="post" id='form1' action=""  name="listForm">
	<style>
		.dropdown-menu{
			min-width: 0px;
		}
		.modal.fade.in{
			top:25%;
		}
	</style>
    <table class="table" width="100%">
        <thead>
        <tr>
            <th >
			<?php
			if($searchData['status']=='1' || isset($searchData['batch_id'])){
				echo "<label style='display: inline'><input type='checkbox' class='all_check_input' onclick='check_all(this)' autocomplete='off'/>".lang('all')."</label>";
			}
			?>
            </th>
            <th width="5%"><?php echo lang('time'); ?></th>
            <th width="5%"><?php echo lang('admin_after_sale_id'); ?></th>
            <th width="5%"><?php echo lang('admin_after_sale_uid').'/'.lang('order_id'); ?></th>
            <th width="5%"><?php echo lang('admin_after_sale_name'); ?></th>
            <th width="5%"><?php echo lang('admin_after_sale_type'); ?></th>
            <th width="5%"><?php echo lang('admin_after_sale_method'); ?></th>
            <th width="5%"><?php echo lang('payee_info'); ?></th>
            <th width="5%"><?php echo lang('admin_after_sale_amount'); ?></th>
            <th width="50%"><?php echo lang('admin_after_sale_remark'); ?></th>
            <th width="5%"><?php echo lang('status'); ?></th>
            <th width="5%"></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) {?>
                <tr>

					<td class="modal_main">
                        <?php if ($item['status']=='1' && $item['refund_method'] == 2){ ?>
							<input type="checkbox" class="checkbox" name="checkboxes[]" onclick='chb_is_checked()' value="<?php echo $item['as_id']; ?>" autocomplete="off">
                        <?php } ?>
                    </td>

					<td width="5%"><?php echo $item['apply_time'] ?></td>
                                        <td width="5%" >
						<?php if($item['status'] == 3 && $item['img']){ ?>
						<a data-lightbox="card_info" href="<?php echo config_item('img_server_url').'/'.$item['img'] ?>" class="example-image-link">
							<?php  echo $item['as_id']; ?></a>
						<?php }else{ echo $item['as_id']; }?>
					</td>
                    <td width="5%"><?php echo $item['uid']?$item['uid'] : $item['order_id'] ?></td>
                    <td width="5%"><?php echo $item['name'] ?></td>
                    <td width="5%"><?php echo lang('admin_after_sale_type_'.$item['type']); echo $item['demote_level'] ? '->'.lang('level_'.$item['demote_level']) : '' ?></td>
                    <td width="5%"><?php echo lang('admin_after_sale_method_'.$item['refund_method']) ?></td>
                    <td width="5%"><?php if(in_array($item['refund_method'],array(0,2))){echo $item['account_name'];}else{echo $item['transfer_uid'];}?></td>
                    <td width="5%""><?php  echo $item['refund_method'] == 2 ? '￥'.round($item['refund_amount'],2) : '$'.round($item['refund_amount'],2);?></td>
                    <td width="50%" style="word-break:break-all"><?php echo $item['remark'];?></td>
                    <td width="5%"><?php echo lang('admin_after_sale_status_'.$item['status']);if(in_array($item['status'],array(4,5))){echo '<br><span style="color:red;">'. $item['reject_remark'].'<span>'; } ?></td>
                    <td width="5%">
                        <?php if(in_array($adminInfo['role'],array(0,2,3,4))) { ?>
                        <!-- 操作 -->
						<div class="btn-group">
							<a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
								<?php echo lang('action');?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<?php if($item['status'] == 0 && in_array($adminInfo['role'],array(0,2)) || ($item['type']==2 && $adminInfo['id'] == 18 && $item['status'] == 0) ){?>
										<a href="<?php echo base_url('admin/after_sale_order_list/demote_info/'.$item['as_id'])?>"><?php echo lang('admin_go_process')?></a>
										<a href='javascript:as_reject("<?php echo $item['as_id']?>","4")'><?php echo lang('reject')?></a>
									<?php }?>
									<?php if($item['status'] == 7 && $curControlName == 'admin_as_refund_list'){?>
										<a href='javascript:show_upload_modal("<?php echo $item['as_id']?>");'><?php echo lang('admin_as_upload_info')?></a>
										<a href='javascript:as_reject("<?php echo $item['as_id']?>","5")'><?php echo lang('reject')?></a>
									<?php }?>
									<?php if($item['status'] == 1 && $curControlName == 'admin_as_refund_list'){?>
										<!--
										<a href="##" class="do_refund" rel="<?php echo $item['as_id']; ?>" ><?php echo lang('admin_after_sale_status_7')?></a>
										-->
										<a href='javascript:as_reject("<?php echo $item['as_id']?>","7")'><?php echo lang('admin_after_sale_status_7')?></a>
										<a href='javascript:as_reject("<?php echo $item['as_id']?>","5")'><?php echo lang('reject')?></a>
									<?php }?>
									<?php if(in_array($item['status'],array(4,5))  && $curControlName == 'after_sale_order_list'){ ?>
										<a href="<?php echo isset($item["is_three_month"]) && $item["is_three_month"] !=1 ? base_url('admin/add_after_sale_order/index/'.$item['as_id']) : base_url('admin/three_month_days_order/index/'.$item['as_id']);?>"><?php echo lang('admin_as_update')?></a>
									<?php }?>
									<?php if(($item['status'] == 4 && $curControlName == 'after_sale_order_list' && in_array($adminInfo['role'],array(0))) || $adminInfo['id'] == 62){ ?>
										<a href='javascript:as_reject("<?php echo $item['as_id']?>","6")'><?php echo lang('admin_after_sale_status_6')?></a>
									<?php }?>
									<?php if($item['status'] == 3 && $curControlName == 'admin_as_refund_list' && in_array($adminInfo['role'],array(0))){ ?>
										<a href='javascript:as_reject("<?php echo $item['as_id']?>","del")'><?php echo lang('admin_as_del_upload_info')?></a>
									<?php }?>
									<a href='javascript:get_as_remark_info("<?php echo $item['as_id']?>")'><?php echo lang('admin_as_view_remark')?></a>
								</li>
							</ul>
						</div>
						<!-- end操作 -->
						<?php } ?>
					</td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="14" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
<input id="batchss" name="batch_status" type="hidden" />
</form>
<div style="float: left;clear: both;">
	<?php echo $pager;?>
</div>


<div id="as_remark_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo lang('admin_as_action_log')?></h3>
	</div>

	<div class="modal-body">
			<table class="enable_level_tb" style="float:left;">
				<tr>
					<td><?php echo lang('admin_after_sale_id') ?>:</td>
					<td><span class="as_id"></span></td>
				</tr>
				<tr>
					<td><?php echo lang('admin_after_sale_remark') ?>:</td>
					<td><textarea class="remark" autocomplete="off"></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td><input autocomplete="off" class="btn btn-primary as_order_submit" value="<?php echo lang('submit'); ?>"></td>
				</tr>
			</table>
            <div class="payee" style="width:330px;float:right;border:1px;">
                
                
            </div>
            <div style="clear: both"></div>
            <div style="float:left"><?php echo lang('admin_as_action_log') ?></div><br>
            <div id="table_str"></div>
	</div>

</div>

<div id="as_upload_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo lang('admin_as_upload_info')?></h3>
	</div>
	<div class="modal-body">
		<form action="<?php echo base_url('admin/admin_as_refund_list/admin_upload_voucher') ?>" enctype="multipart/form-data" method="post" class="form-horizontal" id="upload_form">
			<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">

			<div class="control-group">
				<label class="control-label"><?php echo lang('admin_as_upload_info');?></label>
				<div class="controls">
					<input type="hidden" name="as_id" class="upload_as_id" autocomplete="off"/>
					<input type="file" autocomplete="off" name="userfile" size="20" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<button type="button" autocomplete="off" class="btn btn-primary" id="upload"><?php echo lang('upload');?></button>
					<div class="upload_msg"></div>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script>
	function return_batch_order(){
		var batch_status = document.getElementById('batch_status').value;
		if(!batch_status){
			return false;
		}
		var action = '/admin/after_sale_order_list/batch_precess_alipay';
		if(window.confirm("<?php echo lang('sure')?>")){
			$("#form1").attr("action", action);
                        $("#batchss").val(batch_status);
			var li ;
                        $("#form1").ajaxSubmit({
                                clearForm :true,
                                resetForm :true,
                                dataType:'json',
                                success:function(res){
                                        layer.close(li);
                                        layer.msg(res.msg, {time:3000});
                                },
                                error:function(){
                                        layer.close(li);
                                },
                                beforeSend:function(){
                                        li = layer.load();
                                },
                                complete:function(){
                                    setTimeout(function(){
                                        location.href= '/admin/admin_after_sale_batch';
                                }, 3000)
                                }
                        });
                        return false;
		}else{
			return false;
		}
	}

	function check_all(all){
		var ips = document.getElementsByTagName('input');
		for( var i=0;i<ips.length;i++ ){
			if( ips[i].type == 'checkbox' && !ips[i].disabled && ips[i].name == 'checkboxes[]' )
				ips[i].checked = all.checked;
		}
		chb_is_checked();
	}
	function chb_is_checked(){
		var is_checked = false;
		var ips = document.getElementsByTagName('input');
		for( var i =0;i<ips.length;i++ ){
			if( ips[i].type=="checkbox" && !ips[i].disabled && ips[i].checked && ips[i].name == 'checkboxes[]' ){
				is_checked = true;
				break;
			}
		}
		var batch_status = document.getElementById('batch_status');
		if( is_checked ){
			if(batch_status != null){
				document.getElementById('batch_status').disabled = '';
				document.getElementById('btn_type').disabled = '';
			}
		} else {
			if(batch_status != null){
				document.getElementById('batch_status').disabled = 'disabled';
				document.getElementById('btn_type').disabled = 'disabled';
			}
		}
	}
</script>
<?php if(isset($batch_info)){?>
    <script>
        //提交支付宝js
        function batch_Alipay(id) {
            var li;
            li = layer.load();
            $('.submit_alipay').attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "/admin/admin_after_sale_batch/submit_batch_Alipay",
                data: {id: id},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $('.upload_msg').append(res.data);
                    } else {
                        layer.close(li);
                        layer.msg('<?php echo lang("result_false"); ?>');
                    }
                }
            });
        }

        //取消批次js
        function cancel_batch(id) {
            layer.confirm('<?php echo lang('double_confirm'); ?>', {icon: 3, title: '<?php echo lang('cancel_confirm'); ?>'}, function (index) {
                $.ajax({
                    type: "POST",
                    url: "/admin/admin_after_sale_batch/cancel_generate_batch",
                    data: {id: id},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            layer.msg('<?php echo lang("result_ok"); ?>');
                            location.href="/admin/admin_after_sale_batch";
                        } else {
                            layer.msg('<?php echo lang("result_false"); ?>');
                        }
                    }
                });
            });
        }


    </script>
<?php }?>


