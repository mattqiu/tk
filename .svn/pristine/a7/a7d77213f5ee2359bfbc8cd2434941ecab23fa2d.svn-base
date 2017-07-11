<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/searchableSelect/searchableSelect.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/searchableSelect/searchableSelect.css'); ?>">
<div class="search-well">
    <form action="<?php echo base_url('admin/all_tickets'); ?>" class="form-inline" method="GET">       
        <input autocomplete="off" value="<?php if(isset($searchData['key_tickets_id'])){echo $searchData['key_tickets_id'];} ?>" name="key_tickets_id" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control tickets_input_box_trim" id="title_keywords" placeholder="<?php echo lang('pls_t_tid'); ?>" />
        <input autocomplete="off" value="<?php if(isset($searchData['key_uid_aid'])){echo $searchData['key_uid_aid'];} ?>" name="key_uid_aid" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control tickets_input_box_trim" id="title_keywords" placeholder="<?php echo lang('pls_t_uid_aid'); ?>" />
        <select name="type" style="width: 180px;">
            <option value=""><?php echo lang('type'); ?></option>
                <?php foreach (config_item('tickets_problem_type') as $key => $value) { ?>
                    <option value="<?php echo $key ?>"
                       <?php if ($searchData['type'] >= '0' && $key == $searchData['type']) {
                         echo " selected=selected";
                       }
                       ?> >
                    <?php echo lang($value); ?>
                    </option>
                <?php } ?>
        </select>

        <select name="language" id="com_type" class="com_type">
            <option value=""><?php echo lang('tickets_language'); ?></option>
            <?php foreach ($language_all as $key => $value) { ?>
                <option value="<?php echo $value['language_id']; ?>"
                    <?php if ($searchData['language'] >= '0' && $value['language_id'] == $searchData['language']) {
                        echo " selected=selected";
                    }
                    ?> >
                    <?php echo $value['name']; ?>
                </option>
            <?php } ?>
        </select>

        <select name="status" style="width: 180px;">
            <option value=""><?php echo lang('tickets_status'); ?></option>
            <?php 
            	$tickets_status = config_item('tickets_status');//移除新建的状态
            	unset($tickets_status[0]);unset($tickets_status[1]); ?>
            <?php foreach ($tickets_status as $key => $value) { ?>
                <option value="<?php echo $key ?>"
                    <?php if ($searchData['status'] >= '0' && $key == $searchData['status']) {
                        echo " selected=selected";
                    }
                    ?> >
                    <?php echo lang($value); ?>
                </option>
            <?php } ?>
        </select>

        <select name="priority" style="width: 180px;">
            <option value=""><?php echo lang('tickets_priority'); ?></option>
            <?php foreach (config_item('tickets_priority') as $key => $value) { ?>
                <option value="<?php echo $key ?>"
                    <?php if ($searchData['priority'] >= '0' && $key == $searchData['priority']) {
                        echo " selected=selected";
                    }
                    ?> >
                    <?php echo lang($value); ?>
                </option>
            <?php } ?>
        </select>

        <?php if(in_array($adminInfo['role'],array(0,2))){?>
        <select name="score" style="width: 80px;">
            <option value=""><?php echo lang('tickets_score_num'); ?></option>
            <?php foreach (array(1,2,3,4,5) as $value) { ?>
                <option value="<?php echo $value ?>"
                    <?php if ($searchData['score'] >= $value) {
                        echo " selected=selected";
                    }
                    ?> >
                    <?php echo $value; ?>
                </option>
            <?php } ?>
        </select>
        <?php } ?>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search'); ?></button>
    </form>
</div>

<div class="well">
    <form method="post" id='form1' action=""  name="listForm">
    <table class="table table-hover">
        <thead>
            <tr>
            	<th></th>
                <?php if(check_right('tickets_assign_right') || $cus_role==2) { ?>
                    <th><label style='display: inline'><input type='checkbox' class='all_check_input' onclick='check_all(this)' autocomplete='off'/><?php echo lang('all'); ?></label></th>
                <?php }?>
                <th><?php echo lang('tickets_id'); ?></th>
            	<th><?php echo lang('member').lang('id');?></th>
				<th><?php echo lang('customer') ?></th>
                <th><?php echo lang('tickets_type'); ?></th>
                <th><?php echo lang('tickets_title'); ?></th>
                <th>
                    <?php echo lang('time'); ?>
                    <a href="<?php echo base_url($time_order_url.'&o_time=asc')?>"><i class="icon-arrow-up"></i></a>
                    <a href="<?php echo base_url($time_order_url.'&o_time=desc')?>"><i class="icon-arrow-down"></i></a>
                </th>
                <th>
                    <?php echo lang('priority');?>
                    <a href="<?php echo base_url($p_order_url.'&o_priority=asc')?>"><i class="icon-arrow-up"></i></a>
                    <a href="<?php echo base_url($p_order_url.'&o_priority=desc')?>"><i class="icon-arrow-down"></i></a>
                </th>
                <th><?php echo lang('status'); ?></th>
                <?php if(in_array($adminInfo['role'],array(0,2))){ ?>
                <th><?php echo lang('tickets_score_num'); ?></th>
                <?php } ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($list)){ ?>
            <?php foreach ($list as $item) { ?>
                <tr class="one_tickets <?php echo $item['id'].'t'; ?>">
                    <td>
                	<?php if(in_array($item['status'],array(0,1))){echo "<a style='color:#08F' href='#'>".lang('new_tickets')."</a>";}
						  elseif($item['last_reply']==0 && !in_array($item['status'],array(0,1,4,5,6))){echo '<a rel="tooltip" href="#" data-original-title="'.lang('new_msg').'"><i class="icon-bell"></i></a>';}
					?>
                    </td>
                    <?php if(check_right('tickets_assign_right') || $cus_role==2) { ?>
                    <td>
                       <input type="checkbox" class="checkbox" name="checkboxes[]" onclick='chb_is_checked()' value="<?php echo $item['id'] ?>" autocomplete="off">
                    </td>
                    <?php }?>
                    <td style="font-weight: bold">#<?php echo $item['id'] ?></td>
                	<td><?php echo $item['uid'];?></td>
                    <td class="admin_name"><?php  echo isset($all_cus[$item['admin_id']]) ?  explode('@',$all_cus[$item['admin_id']]['email'])[0] : ''; ?><span><?php if(isset($all_cus[$item['admin_id']])){ echo '('.$all_cus[$item['admin_id']]['job_number'].')';} ?></span></td>
					<td><?php if(isset(config_item('tickets_problem_type')[$item['type']])){echo lang(config_item('tickets_problem_type')[$item['type']]);} ?>
						<?php if($item['is_attach']){ ?>
							<img src="<?php echo base_url('img/huixing.png')?>" width="18px" >
						<?php }else{echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}?>
					</td>
                    <td class="tips_show"><a href="javascript:view_or_reply_tickets(<?php echo $item['id']?>);"><?php echo mb_substr($item['title'],0,20,'utf-8');if(mb_strlen($item['title'],'utf8')>20){echo '...';} if(!$item['title']){echo 'TPS';}?></a></td>
                    <td style="display: none" class="cont"><?php echo mb_substr($item['content'],0,100,'utf-8');if(mb_strlen($item['content'],'utf8')>100){echo '...';} ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                    <td class="<?php echo $item['id'].'priority'; ?>" style="<?php  if($item['priority']==2){echo'color:red';}elseif($item['priority']==1){echo 'color:blue;';}else{echo 'color:green;';} ?>">
                    <?php echo lang(config_item('tickets_priority')[$item['priority']]);?></td>
                    <td class="<?php echo $item['id'].'status'; ?>"><?php if($item['last_reply']==0 && !in_array($item['status'],array(0,1,4,5,6))){echo lang('new_msg');}else{ echo lang(config_item('tickets_status')[$item['status']]);}?></td>
                    <?php if(in_array($adminInfo['role'],array(0,2))){ ?>
                    <td><?php if($item['status']==4 || $item['status']==5){echo $item['score'];} ?></td>
                    <?php } ?>
                    <td width="15%">
                        <div class="btn-group">
                            <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo lang('action');?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li> <a class="btn_enable" href="javascript:see_user_info_detail(<?php echo $item['uid']?>)"><?php echo lang('view_detail_info')?></a></li>
                                <li><a id="review"  href="" uid=<?php echo $item['uid'];?>><?php echo lang("see_user_back_office") ?></a></li>
                            	<li><a class="btn_enable" href='javascript:view_tickets_log(<?php echo $item['id']?>);'><?php echo lang('view_tickets_log');?></a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
            </tbody>
        </table>
        <?php if(check_right('tickets_assign_right') || $cus_role==2) { ?>
            <div style="float:left; margin-left: -22px;margin-top: 36px;">
                <select id="cus" name="cus">
                    <option value="" ><?php echo lang('tickets_assign')?></option>
                    <?php if(!empty($cus)){ foreach($cus as $c){?>
                        <option value="<?php echo $c['id']; ?>" ><?php echo $c['job_number'].' ('.explode('@',$c['email'])[0].')';?></option>
                    <?php } }?>
                </select>
                <input style="margin-bottom: 0;" id="btn_type" class="btn btn-primary" name="btn_type" type="submit" value="<?php echo lang('submit');?>" onclick="return return_batch_transfer()" disabled autocomplete="off"/>
            </div>
        <?php }?>
    </form>
</div>
<div style="float: left;clear: both">
    <?php echo $pager;?>
</div>

<!-- 用户详细信息弹层 -->
<div id="popUserInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="position:absolute;left:40%;width:900px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo lang('user_info')?></h3>
    </div>
    <div class="modal-body">
        <style>
            .word_break{
                color: #000000;
                display: block;
                font-weight: normal;
                word-break: break-all;
                word-wrap: break-word;
            }
        </style>
        <table class="enable_level_tb" style="border-collapse: separate;border-spacing: 5px;width: 850px">
            <tr>
                <td class="title">ID:</td>
                <td class="main" id="info_id"></td>
            </tr>
			<tr>
				<td class="title"><?php echo lang('name')?>:</td>
				<td class="main" id="info_name"></td>
			</tr>
            <tr>
                <td class="title"><?php echo lang('email_text') ?>:</td>
                <td class="main" id="info_email"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('regi_parent_id') ?>:</td>
                <td class="main" id="info_parent_id"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('pls_sel_mem_rank') ?>:</td>
                <td class="main" id="info_user_rank_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('cur_title') ?>:</td>
                <td class="main" id="info_sale_rank_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('current_commission') ?>:</td>
                <td class="main" id="info_amount_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('sharing_point') ?>:</td>
                <td class="main" id="info_profit_sharing_point_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('dai_coupon') ?>:</td>
                <td class="main" id="info_coupon_total_amount"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('month_fee_pool') ?>:</td>
                <td class="main" id="info_month_fee_pool_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('mobile') ?>:</td>
                <td class="main mdifiable word_break" id="info_mobile"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('country') ?>:</td>
                <td style='padding-top:13px' class="main" id="info_country_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('person_id_card_num') ?>:</td>
                <td class="main mdifiable word_break" id="info_id_card_num"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('address2') ?>:</td>
                <td class="main mdifiable word_break" id="info_address"></td>
            </tr>

            <tr>
                <td class="title"><?php echo lang('id_scan') ?>:</td>
                <td class="main" id="info_id_card_scan_text">
                </td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('type_alipay') ?>:</td>
                <td class="main" id="info_alipay_account"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('ewallet_name') ?>:</td>
                <td class="main" id="info_ewallet_name"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('status') ?>:</td>
                <td class="main" id="info_status_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('month_fee_date') ?>:</td>
                <td class="main" id="info_month_fee_date_text"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('store_url') ?>:</td>
                <td class="main" id="info_store_url"></td>
            </tr>

            <tr>
                <td class="title"><?php echo lang('lifecycle') ?>:</td>
                <td class="main" id="info_join_matrix_time"></td>
            </tr>

            <tr>
                <td class="title"><?php echo lang('month_upgrade_log_label')?>：</td>
                <td class="main"  id="info_month_level_change_log">

                </td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('shop_upgrade_log_label')?>：</td>
                <td class="main"  id="info_store_level_change_log">

                </td>
            </tr>

        </table>
    </div>
</div>
<!-- /用户详细信息弹层 -->
<style>

    .modal-body{
        max-height:700px;
    }
    .modal.fade.in {
        top: 2%;
    }
    .modal .modal-body {
        padding: 1em;
    }
    .box {
        float:left;
        text-align: left;
        font-size: 14px;
        color: white;
        padding: 10px;
        margin: 10px;
        background:#008001;
        background: -webkit-gradient(linear,left top,left bottom,from(#0a6),to(#008001));
        background: -moz-linear-gradient(top,#0a6,#008001);
        background:-ms-linear-gradient(top, #0a6 0%, #008001 100%) #008001;
        background:linear-gradient(top, #0a6 0%, #008001 100%) #008001;

        -moz-border-radius: 15px;
        -webkit-border-radius: 15px;
        border-radius: 15px;

        -webkit-box-shadow:3px 3px 5px #000;
        -moz-box-shadow: -3px -3px 5px #000;
        -ms-box-shadow: 3px 3px 5px #000;
        box-shadow: 3px 3px 5px #000;
    }
    @media screen and (min-width: 616px){
        .box {
            width: 140px;
        }
        #popUserInfo{width:600px;}
    }
    @media screen and (min-width: 576px) and (max-width: 615px) {
        .box {
            width: 50px;
        }
    }
    @media screen and (max-width: 575px) {
        .box {
            width: 30px;
        }
    }

    .arrow {
        width: 40px;
        height: 40px;
        position: relative;
        display: inline-block;
        margin: 30px 10px;
        float:left;
    }
    .arrow:before, .arrow:after {
        content: '';
        border-color: transparent;
        border-style: solid;
        position: absolute;
    }


    .arrow-right:before {
        border: none;
        background-color: #066;
        height: 30%;
        width: 50%;
        top: 35%;
        left: 0;
    }
    .arrow-right:after {
        left: 50%;
        top: 0;
        border-width: 20px 20px;
        border-left-color: #066;
    }
</style>

<script>
	$(function(){
        $('#cus').searchableSelect();
        $('.searchable-select-input').css('height','30px').css('margin-bottom','0px');
        $('.searchable-select-dropdown').css('z-index',999);
        $('.searchable-select').click(function(){
            if($('.searchable-select-input').val()==''){
                $('.searchable-select-item').removeClass('searchable-select-hide');
            }
        });
		$('.one_tickets').each(function(){
			var index;
			var cont = $(this).children('.cont').text();
			$(this).children('.tips_show').mouseover(function(){
				index = layer.tips(cont, $(this), {
					tips: [3, '#3595CC'],
					time: 10000
				});
			}).mouseleave(function(){
				layer.close(index);
			});
		});
	});

function close_ticket(id){
	layer.confirm('<?php echo lang('confirm_close_tickets'); ?>', {icon: 3, title:'<?php echo lang('close_tickets'); ?>'},function(index){
	$.ajax({
		type:"post",
		url:"<?php echo base_url('admin/all_tickets/close_tickets') ?>",
		data:{id:id},
		dataType:"json",
		async:true,
		success:function(res){
			if(res.success==1){
				layer.msg(res.msg);
				window.location.reload();
			}else{
				layer.msg(res.msg);
			}
		}
	});		
	});
	
}

function view_tickets_log(id){
	layer.open({
			type: 2,
			area: ['40%', '70%'],
			fix: true, //不固定
			maxmin:true,
			scrollbar: false,
            shadeClose:true,
			title:"<?php echo lang('view_tickets_log')?>",
			content: '<?php echo base_url('admin/all_tickets/view_tickets_log'); ?>/' + id,
			cancel:function(index){
				//window.location.reload();
			}
		});
}

function view_or_reply_tickets(id){
	layer.open({
			type: 2,
			area: ['830px', '87%'],
			fix: true, //不固定
			maxmin:true,
            shadeClose:true,
			scrollbar: false,
			title:"<?php echo lang('tickets_reply')?>",
			content: '<?php echo base_url('admin/all_tickets/view_or_reply_tickets'); ?>/' + id,
            end:function(){
                get_tickets_status(id);
            }
	});	

}

function get_tickets_status(id){
        $.ajax({
            type:"post",
            url:"<?php echo base_url('admin/all_tickets/get_tickets_status') ?>",
            data:{id:id},
            dataType:"json",
            async:true,
            success:function(res){
                if(res.success==1){
                    $('.'+id+'status').empty().append(res.msg);
                }
            }
        });
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
        var batch_status = document.getElementById('cus');
        if( is_checked ){
            if(batch_status != null){
                document.getElementById('cus').disabled = '';
                document.getElementById('btn_type').disabled = '';
            }
        } else {
            if(batch_status != null){
                document.getElementById('cus').disabled = 'disabled';
                document.getElementById('btn_type').disabled = 'disabled';
            }
        }
}

function return_batch_transfer(){
        var batch = document.getElementById('cus').value;
        if(!batch){
            return false;
        }
        var action = '/admin/all_tickets/batch_transfer';
        if(window.confirm("<?php echo lang('sure')?>")){
            $("#form1").attr("action", action);
            $("#form1").submit(function(){
                var li ;
                $(this).ajaxSubmit({
                    clearForm :true,
                    resetForm :true,
                    dataType:'json',
                    success:function(res){
                        //layer.close(li);
                    },
                    error:function(){
                        layer.close(li);
                    },
                    beforeSend:function(){
                        li = layer.load();
                    },
                    complete:function(){
                    }
                });
                return false;
            });
        }else{
            return false;
        }
}
</script>