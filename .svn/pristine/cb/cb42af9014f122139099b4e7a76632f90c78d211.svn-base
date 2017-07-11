<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <style>
            .dropdown-menu{
                min-width: 0px;
            }
        </style>
        <input type="text" name="idEmail" value="<?php echo $searchData['idEmail'];?>" class="input-medium span2" placeholder="<?php echo lang('id')?>">
        <input type="text" name="name" value="<?php echo $searchData['name'];?>" class="input-medium span2" placeholder="<?php echo lang('name')?>">
		<select name="country_id" class="com_type input-medium">
			<option value=""><?php echo lang('country')?></option>
			<option value="1" <?php echo $searchData['country_id']=='1'? 'selected':''?>><?php echo lang('con_china')?></option>
			<option value="2" <?php echo $searchData['country_id']=='2'? 'selected':''?>><?php echo lang('con_usa')?></option>
			<option value="3" <?php echo $searchData['country_id']=='3'? 'selected':''?>><?php echo lang('con_korea')?></option>
			<option value="4" <?php echo $searchData['country_id']=='4'? 'selected':''?>><?php echo lang('con_hongkong')?></option>
		</select>
        <select name="status" class="com_type input-medium">
            <option value=""><?php echo lang('status')?></option>
            <option value="0" <?php echo $searchData['status']=='0'? 'selected':''?>><?php echo lang('tps_status_0')?></option>
            <option value="2" <?php echo $searchData['status']=='2'? 'selected':''?>><?php echo lang('tps_status_2')?></option>
            <option value="1" <?php echo $searchData['status']=='1'? 'selected':''?>><?php echo lang('tps_status_1')?></option>
            <option value="3" <?php echo $searchData['status']=='3'? 'selected':''?>><?php echo lang('reject')?></option>
            <option value="4" <?php echo $searchData['status']=='4'? 'selected':''?>><?php echo lang('tps_status_4')?></option>
        </select>
		<select name="take_out_type" class="com_type input-medium">
			<option value=""><?php echo lang('type')?></option>
			<option value="3" <?php echo $searchData['take_out_type']=='3'? 'selected':''?>><?php echo lang('type_tps')?></option>
			<option value="4" <?php echo $searchData['take_out_type']=='4'? 'selected':''?>><?php echo lang('maxie_mobile')?></option>
		</select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
    <form class="form-inline" method="GET" action="<?php echo base_url('admin/cash_withdrawal_list/exportWithdrawal') ?>">
		<input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
		-
		<input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
		<select name="country_id" class="com_type input-medium">
			<option value=""><?php echo lang('country')?></option>
			<option value="1" <?php echo $searchData['country_id']=='1'? 'selected':''?>><?php echo lang('con_china')?></option>
			<option value="2" <?php echo $searchData['country_id']=='2'? 'selected':''?>><?php echo lang('con_usa')?></option>
			<option value="3" <?php echo $searchData['country_id']=='3'? 'selected':''?>><?php echo lang('con_korea')?></option>
			<option value="4" <?php echo $searchData['country_id']=='4'? 'selected':''?>><?php echo lang('con_hongkong')?></option>
		</select>
		<select name="status" class="com_type input-medium">
            <option value=""><?php echo lang('status')?></option>
            <option value="0" <?php echo $searchData['status']=='0'? 'selected':''?>><?php echo lang('tps_status_0')?></option>
            <option value="2" <?php echo $searchData['status']=='2'? 'selected':''?>><?php echo lang('tps_status_2')?></option>
            <option value="1" <?php echo $searchData['status']=='1'? 'selected':''?>><?php echo lang('tps_status_1')?></option>
            <option value="4" <?php echo $searchData['status']=='4'? 'selected':''?>><?php echo lang('tps_status_4')?></option>
        </select>
		<select name="take_out_type" class="com_type input-medium">
			<option value=""><?php echo lang('type')?></option>
			<option value="3" <?php echo $searchData['take_out_type']=='3'? 'selected':''?>><?php echo lang('type_tps')?></option>
			<option value="4" <?php echo $searchData['take_out_type']=='4'? 'selected':''?>><?php echo lang('maxie_mobile')?></option>
		</select>
        <button class="btn" type="submit"><i class="icon-download-alt"></i> <?php echo lang('export') ?></button>
    </form>
	<form class="form-inline" method="GET" action="<?php echo base_url('admin/cash_withdrawal_list/export_maxie') ?>">
		<input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
		-
		<input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
		<select name="country_id" class="com_type input-medium">
			<option value=""><?php echo lang('country')?></option>
			<option value="1" <?php echo $searchData['country_id']=='1'? 'selected':''?>><?php echo lang('con_china')?></option>
			<option value="2" <?php echo $searchData['country_id']=='2'? 'selected':''?>><?php echo lang('con_usa')?></option>
			<option value="3" <?php echo $searchData['country_id']=='3'? 'selected':''?>><?php echo lang('con_korea')?></option>
			<option value="4" <?php echo $searchData['country_id']=='4'? 'selected':''?>><?php echo lang('con_hongkong')?></option>
		</select>
		<label><input autocomplete="off"  name="is_export_lock" type="checkbox" value="1"><?php echo lang('admin_select_is_lock')?></label>
		<button class="btn" type="submit"><i class="icon-download-alt"></i> <?php echo lang('export') ?> Maxie Mobile</button>
	</form>

	<input value="<?php echo $rate?>" class="span2" autocomplete="off" placeholder="<?php echo lang('admin_order_rate')?>"  name="rate" type="text">
	<button class="btn withdrawal_rate" type="submit"><?php echo lang('submit')?></button>

</div>

<div class="well">
    <form method="post" id='form1' action=""  name="listForm">
    <table class="table">
        <thead>
            <tr>
                <th><label style="display: inline"><input type="checkbox" class="all_check_input" onclick="check_all(this)" autocomplete="off"/><?php echo lang('all')?></label></th>
                <th>ID</th>
                <th><?php echo lang('name'); ?></th>
                <th><?php echo lang('money'); ?></th>
				<th><?php echo lang('withdrawal_fee_'); ?></th>
				<th><?php echo lang('withdrawal_actual_'); ?></th>
                <th><?php echo lang('bank_name'); ?></th>
                <th><?php echo lang('bank_card_number'); ?></th>
                <th style="width: 5%;"><?php echo lang('card_holder_name'); ?></th>
                <th>CNY(￥)</th>
                <th style="width: 5%;"><?php echo lang('remark'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('status'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td class="modal_main">
                        <?php if ($item['status'] != 1 && $item['status'] != 3){ ?>
                            <input type="checkbox" class="checkbox" name="checkboxes[]" onclick='chb_is_checked()' value="<?php echo $item['id'] ?>" autocomplete="off">
                        <?php } ?>
                    </td>
                    <td><?php echo $item['uid'] ?></td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo '$'.$item['amount'] ?></td>
					<td>$<?php echo $item['handle_fee'] ?></td>
					<td>$<?php echo $item['actual_amount'] ?></td>
                    <td><?php
						if($item['account_bank']){
								 if($item['subbranch_bank']){
									echo  $item['account_bank'] .'<br>'.$item['subbranch_bank'];
								 }else{
									echo  $item['account_bank'];
								 }
						}else{
							echo '';
						} ?></td>
                    <td><?php echo $item['card_number'] ?></td>
                    <td style="width: 5%;"><?php echo $item['account_name'] ?></td>
                    <td><?php echo '￥'.sprintf("%.2f", $item['actual_amount']*$rate);  ?></td>
                    <td style="width: 5%;"><?php echo $item['remark'] ?></td>
                    <td><?php echo $item['create_time']?></td>
                    <td><?php if($item['status'] == 1){ echo '<strong class="text-success">'.lang('tps_status_1').'</strong>';}else if($item['status'] == 0) {echo '<strong class="text-error">'.lang('tps_status_0').'</strong>'; }
                    else if($item['status'] == 2){ echo '<strong class="text-warning">'.lang('tps_status_2').'</strong>';}else if($item['status'] == 3){ echo '<a rel="tooltip" href="##" data-original-title="'.$item['check_info'].'"><i class="icon-question-sign"></i></a><strong class="text-warning">'.lang('reject').'</strong>';}
                    else if($item['status'] == 4){ echo '<strong class="text-info">'.lang('tps_status_4').'</strong>';} ?></td>
                    <td>
                        <?php if ($item['status'] == 2){?>
                            <div class="btn-group">
                                <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                    <?php echo lang('action');?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- dropdown menu links -->
                                    <li>
                                        <a href="##" class="process" rel="<?php echo $item['id']; ?>" status="1"><?php echo lang('tps_status_1') ?></a>
                                    </li>
                                    <li>
                                        <a href="##" class="refuseInfo" rel="<?php echo $item['id']; ?>" status="3"><?php echo lang('reject');?></a>
                                    </li>
                                </ul>
                            </div>
                        <?php } ?>
                        <?php if ($item['status'] == 0){?>
                            <div class="btn-group">
                                <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                    <?php echo lang('action');?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="##" class="process" rel="<?php echo $item['id']; ?>" status="2"><?php echo lang('tps_status_2') ?></a>
                                    </li>

                                </ul>
                            </div>
                        <?php } ?>
						<?php if ($item['status'] == 1){?>
							<div class="btn-group">
								<a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
									<?php echo lang('action');?>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="##" class="refuseInfo" rel="<?php echo $item['id']; ?>" status="3"><?php echo lang('reject');?></a>
									</li>
								</ul>
							</div>
						<?php } ?>
                    </td>

                </tr>
            <?php } ?>
            <?php }else{ ?>
                <tr>
                    <th colspan="9" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>
<div style="float:left;margin: 20px 0;">
    <label style="display: inline"><input type="checkbox" class="all_check_input" onclick="check_all(this)" autocomplete="off"/><?php echo lang('all')?></label>
    <select id="batch_type" name="batch_type" disabled>
        <option value="" ><?php echo lang('status')?></option>
        <option value="2" ><?php echo lang('tps_status_2')?></option>
        <option value="1" ><?php echo lang('tps_status_1')?></option>
    </select>
    <input id="btn_type" class="btn btn-primary" name="btn_type" type="submit" value="<?php echo lang('submit')?>" onclick="return return_batch_order()" disabled autocomplete="off"/>
</div>
<div style="float:right;"><?php echo $pager; ?>
</div>
<div class="clearfix"></div>
</form>
<script>
    $(function(){
        $('.refuseInfo').click(function(){
            var id = $(this).attr('rel');
            var status = $(this).attr('status');
            var info =prompt("<?php echo lang('refuse_reason');?>","");
            if(info){
				var li ;
				li = layer.load();
                $.post("/admin/cash_withdrawal_list/process", {info: info,id:id,status:status}, function (data) {
                    if(data.success){
                        location.reload();
                    }else{
						layer.close(li);
                        layer.msg(data.msg);
                        location.reload();
                    }
                },'json');
            }
        });
        $('.process').click(function(){
            var id = $(this).attr('rel');
            var status = $(this).attr('status');
            var choose = true;
            if(status == 1){
                choose = confirm('<?php echo lang('sure')?>');
            }
            if(choose){
					var li ;
					li = layer.load();
                    $.post("/admin/cash_withdrawal_list/process", {id:id,status:status}, function (data) {
                        if(data.success){
                            location.reload();
                        }else{
							layer.close(li);
                            layer.msg(data.msg);
                            location.reload();
                        }
                    },'json');
                }
            });
		$('.withdrawal_rate').click(function(){
			var rate = $('[name="rate"]').val();

			if(rate < 0 || rate>=7 || isNaN(rate)){
				layer.msg('<?php echo lang('admin_exchange_rate_error')?>');
				return;
			}

			var li ;
			li = layer.load();
			$.post("/admin/cash_withdrawal_list/withdrawal_rate", {rate:rate}, function (data) {
				if(data.success){
					location.reload();
				}else{
					layer.close(li);
					layer.msg(data.msg);
					location.reload();
				}
			},'json');

		});
    });

	function return_batch_order(){
        var batch_type = document.getElementById('batch_type').value;
        if(!batch_type){
            return false;
        }

        var action = '';
        switch(batch_type){
            case "1":
                action = '/admin/cash_withdrawal_list/batch_precess';
                break;
            case "2":
                action = '/admin/cash_withdrawal_list/batch_precess';
                break;
        }
        if(window.confirm("<?php echo lang('sure')?>")){

                $("#form1").attr("action", action);
                $("#form1").submit(function(){
					var li ;
                    $(this).ajaxSubmit({
                        clearForm :true,
                        resetForm :true,
                        dataType:'json',
                        success:function(json){
                            if(json['status'] == 1){

                            }else if(json['status'] == 0){
                                html ='';
                                $.each(json['row'],function(k,i){
                                    html +=i.uid;
                                    if(k+1 != json['row'].length){
                                        html +=',';
                                    }
                                });
                                layer.msg(json['msg']+':'+html);
								layer.close(li);
                            }
                        },
                        error:function(){
							layer.close(li);
                        },
                        beforeSend:function(){
							li = layer.load();
                        },
                        complete:function(){
                            location.reload();
                        }
                    });

                    return false;
                });

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
        var batch_type = document.getElementById('batch_type');
        if( is_checked ){
            if(batch_type != null){
                document.getElementById('batch_type').disabled = '';
                document.getElementById('btn_type').disabled = '';
            }
        } else {
            if(batch_type != null){
                document.getElementById('batch_type').disabled = 'disabled';
                document.getElementById('btn_type').disabled = 'disabled';
            }
        }
    }
</script>
