<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style>
    .dropdown-menu{
        min-width: 0px;
    }
</style>
<div class="search-well">
    <form class="form-inline" method="GET">
        
        <input type="text" name="idEmail" value="<?php echo $searchData['idEmail'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('search_notice')?>">
        <input type="text" name="mobile" value="<?php echo $searchData['mobile'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('mobile')?>">
        <input type="text" name="parent_id" value="<?php echo $searchData['parent_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('regi_parent_id')?>">

        <select name="user_rank" class="user_ranks_sel mbot10">
            <option value="">---<?php echo lang('pls_sel_mem_rank');?>---</option>
            <?php foreach(config_item('user_ranks') as $key=>$value){?>
            <option value="<?php echo $key?>"<?php if($key==$searchData['user_rank']){ echo " selected=selected";}?>><?php echo lang($value)?></option>
            <?php }?>
        </select>
        <select name="status" class="user_ranks_sel mbot10">
            <option value="">---<?php echo lang('status_title')?>---</option>
            <option value="0" <?php echo $searchData['status']=='0'? 'selected':''?>><?php echo lang('status_0')?></option>
            <option value="1" <?php echo $searchData['status']=='1'? 'selected':''?>><?php echo lang('status_1')?></option>
            <option value="2" <?php echo $searchData['status']=='2'? 'selected':''?>><?php echo lang('status_2')?></option>
            <option value="3" <?php echo $searchData['status']=='3'? 'selected':''?>><?php echo lang('status_3')?></option>
            <option value="4" <?php echo $searchData['status']=='4'? 'selected':''?>><?php echo lang('status_4')?></option>            
        </select>
        
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_start_time')?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_end_time')?>">
        
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<input type="hidden" name="confirm_email_title" id="confirm_email_title" value="<?php echo $confirm_email_title; ?>">
<input type="hidden" name="confirm_mobile_title" id="confirm_mobile_title" value="<?php echo $confirm_mobile_title; ?>">
<input type="hidden" name="confirm_info_content" id="confirm_info_content" value="<?php echo $confirm_info_content; ?>">
<input type="hidden" name="label_yes" id="label_yes" value="<?php echo $label_yes; ?>">
<input type="hidden" name="label_no" id="label_no" value="<?php echo $label_no; ?>">
<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo lang('realName') ?></th>
                <th><?php echo lang('email_text') ?></th>
                <th><?php echo lang('mobile') ?></th>
                <th><?php echo lang('regi_parent_id'); ?></th>

                <th><?php echo lang('pls_sel_mem_rank'); ?></th>
                <th><?php echo lang('status'); ?></th>
                <th><?php echo $admin_order_remark; ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><span class="<?php if(in_array($adminInfo['role'],array(0,1,2))){ echo 'mem_name '; }?>mdifiable"><?php echo $item['name']?$item['name']:""; ?></span></td>
                    <td><span class="<?php if(in_array($adminInfo['role'],array(0,1,2))){ echo 'mem_email '; }?>mdifiable"><?php echo $item['email']?$item['email']:""; ?></span></td>
                    <td><span class="mdifiable <?php if(in_array($adminInfo['role'],array(0,1,2))){ echo 'mem_mobile'; }?>"><?php echo $item['mobile'] ? $item['mobile'] : ''; ?></span></td>
                    <td><?php echo $item['parent_id'] ? $item['parent_id'] : ''; ?></td>
                    <td><?php echo $item['parent_id'] ? lang(config_item('user_ranks')[$item['user_rank']]) : ''; ?></td>
                    <td>
                        <?php echo lang(config_item('store_status')[$item['status']]); ?>
                    </td>
                    <td>
                        <?php if($item['looks']){ ?>
                               <a href="javascript:look_user_remark(<?php echo $item['id']; ?>)"><?php echo $process; ?></a>
                        <?php } ?>
                    </td>
                    <td width="15%">
                        <div class="btn-group">
                            <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo lang('action');?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- dropdown menu links -->
                                <li>
                                    <a target="_blank" href="/admin/user_list/user_score_list?uid=<?php echo $item['id']?>"><?php echo lang('view_detail_score')?></a>
                                </li>
                                <li>
                                    <a href="javascript:see_user_info_detail(<?php echo $item['id']?>)"><?php echo lang('view_detail_info')?></a>
                                </li>
                                 <?php if($item['status']!=1){ ?>
                                     <?php if( in_array($adminInfo['role'],array(0,2)) ){ ?>
                                        <li>
                                            <a href="javascript:click_frozen_u(<?php echo $item['id']?>,3)"><?php echo lang('resert_user_status')?></a>
                                        </li>
                                    <?php } ?>
                                <?php }?>
                                <!--超级管理员，对于店铺未激活的用户可以直接在后台激活-->
                                <?php if( in_array($adminInfo['role'],array(0,1,2)) ){ ?>
                                    <!-- 用户具有冻结解封权限才可访问 -->
                                    <?php if(1==$user_rooter) { ?>
                                        <?php if($item['status']==0){ ?>
                                            <li><a class="btn_enable" href='javascript:enable_user_account(<?php echo $item['id']?>);'><?php echo lang('account_enable');?></a></li>
                                        <?php }?>
    
                                        <?php if($item['status']==1){ ?>                                    
                                            <!-- <li><a class="btn_disable" href='javascript:disable_user_account(<?php echo $item['id']?>);'><?php echo lang('account_disable');?></a></li> -->
                                            <li><a class="btn_disable" href='javascript:click_frozen(<?php echo $item['id']?>,1);'><?php echo lang('account_disable_z');?></a></li>
                                        <?php }?>
                                        <?php if($item['status']==2){ ?>                                        
                                            <li><a class="btn_redisable" href='javascript:click_frozen(<?php echo $item['id']?>,1);'><?php echo lang('account_disable_m');?></a></li>
                                        <?php }?>
                                        <?php if($item['status']==3){ ?>
                                            <!--  <li><a class="btn_redisable" href='javascript:reenable_user_account(<?php echo $item['id']?>);'><?php echo lang('account_reenable');?></a></li> -->
                                            <li><a class="btn_redisable" href='javascript:click_frozen(<?php echo $item['id']?>,2);'><?php echo lang('account_reenable_z');?></a></li>
                                            
                                        <?php }?>
                                        <?php if($item['status']==5){ ?>                                        
                                            <li><a class="btn_redisable" href='javascript:click_frozen(<?php echo $item['id']?>,2);'><?php echo lang('account_reenable_m');?></a></li>                                        
                                        <?php }?>
                                    <?php } ?>
                                    <!--/end 用户具有冻结解封权限才可访问 -->
                                <?php }?>
                                <!--/超级管理员，对于店铺未激活的用户可以直接在后台激活-->

                                <li>
                                    <a id="review"  href="" uid=<?php echo $item['id'];?>><?php echo lang("see_user_back_office") ?></a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
               <?php if(isset($err_code) && $err_code==1003){ ?>
                    <tr>
                        <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('search_data') ?></th>
                    </tr>
             <?php  }else{?>
                    <tr>
                        <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                    </tr>
               <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>

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
            .relive_ban{
                margin-left: 20px;
            }
            .mobile_unbundling{
                margin-left: 20px;
            }
            .alipay_unbundling{
                margin-left: 20px;
            }
            .paypal_unbundling{
                margin-left: 20px;
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
                <td class="title"><?php echo lang('mobile') ?>:</td>
                <td class="main word_break" id="info_mobile"></td>
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
                <td class="title"><?php echo 'paypal'; ?>:</td>
                <td class="main" id="info_paypal_account"></td>
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
                <td class="title"><?php echo lang('store_url') ?>:</td>
                <td class="main" id="info_store_url"></td>
            </tr>

            <tr>
                <td class="title"><?php echo lang('regi_create_time') ?>:</td>
                <td class="main" id="info_join_matrix_time"></td>
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

<!-- 冻结添加备注弹层 -->
<div id="div_add_remark" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 20%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo $admin_order_remark; ?></h3>
	</div>
	<div class="modal-body">
		<table class="tab_add_remark" style="margin: 0 auto">
			<tr>
				<input type="hidden" id="hidden_user_id">
				<input type="hidden" id="hidden_user_option_type">
			</tr>
			<tr>
				<td>
					<textarea id="remark_content" autocomplete="off" rows="3" cols="300" maxlength="128" style="width: 97%" placeholder="<?php echo $admin_order_remark; ?>"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><span id="add_remark_msg" class="msg error" style="margin-left:0px"></span></td>
			</tr>
            <tr id = "frost_time_select" style="display:none">
                <td><?php echo lang('frost_user_time');?>:<input maxlength="5" autocomplete="off" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"  style="width:50px;" type="text" name = "frost_days" id="frost_days"> <?php echo lang('day');?> &nbsp; &nbsp; &nbsp; or &nbsp; &nbsp; &nbsp;<span><label for ='frost_forever' style="display:inline"><input type="checkbox" name="frost_forever"  id="frost_forever"> <?php echo lang('frost_forever');?></label></span></td>
            </tr>
		</table>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary"  onclick="submit_frozen_remark()" id="add_remark_submit"><?php echo lang('submit'); ?></button>
	</div>
</div>
<!--/end 冻结添加备注弹层 -->


<!-- 恢复用户状态备注 -->
<div id="div_add_remark_u" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 20%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo $admin_order_remark; ?></h3>
	</div>
	<div class="modal-body">
		<table class="tab_add_remark" style="margin: 0 auto">
			<tr>
				<input type="hidden" id="hidden_user_id_u">				
			</tr>
			<tr>
				<td>
					<textarea id="remark_content_u" autocomplete="off" rows="3" cols="300" maxlength="128" style="width: 97%" placeholder="<?php echo $admin_order_remark; ?>"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><span id="add_remark_msg_u" class="msg error" style="margin-left:0px"></span></td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary"  onclick="btn_resert_user_status()" id="add_remark_submit_u"><?php echo lang('submit'); ?></button>
	</div>
</div>
<!--/end 恢复用户状态备注 -->

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
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

    //添加备注弹层 用户状态恢复正常
    function click_frozen_u(id,type){
    	$('#div_add_remark_u').modal();
    	$('#hidden_user_id_u').val(id);
    }

	//恢复用户状态到正常
	function btn_resert_user_status()
	{
		var uid = $("#hidden_user_id_u").val();
    	var remarks = $("#remark_content_u").val();
    	
    	$.ajax({
    		type: "POST",
    		url: "/admin/user_list/resert_user_status",
    		data: {id:uid,remark:remarks},
    		dataType: "json",
    		success: function (res) {
    			if (res.success) {
    				layer.msg('<?php echo lang('update_success')?>')
    				window.location.reload();    				
    			}else{
    				$("#add_remark_msg_u").text(res.msg);
    				setTimeout(
    					function() {
    						$("#add_remark_msg_u").text("");
    					},
    					3000
    				)
    			}
    		}
    	});
	}

	
    //添加备注弹层
    function click_frozen(id,type){
    	$('#div_add_remark').modal();
    	$('#hidden_user_id').val(id);
    	$('#hidden_user_option_type').val(type);
        if (type == '1' ) {
            $("#frost_time_select").css('display',"block");
        } else {
            $("#frost_time_select").css('display',"none");
        }
    }
   
    //提交冻结备注
    function submit_frozen_remark(){
    	var id = $("#hidden_user_id").val();
    	var remark = $("#remark_content").val();
    	var optype = $("#hidden_user_option_type").val();
    	if(remark.trim().length==0)
    	{
    		$("#add_remark_msg").text("<?php echo $admin_remark_input_not_null; ?>");
			return;
       	}
        var data = {};
        if (optype == '1') {
            var frost_days = $("input[name='frost_days']").val();
            var frost_forever = $("input[name='frost_forever']").prop("checked");
            if (frost_forever == true) { //
                data.frost_days = 0;
                data.frost_forever = 1;
            } else { //天数冻结
                if (frost_days <= 0) {
                    $("#add_remark_msg").text("<?php echo lang("please_select_frost_time"); ?>");
                    return;
                } else {
                    data.frost_days = frost_days;
                    data.frost_forever = 0;
                }

            }

        }

        data.id = id;
        data.remark = remark;
        data.optype = optype;
    	$.ajax({
    		type: "POST",
    		url: "/admin/user_list/option_users_account",
    		data: data,
    		dataType: "json",
    		success: function (res) {
    			if (res.success) {
    				window.location.reload();    				
    			}else{
    				$("#add_remark_msg").text(res.msg);
    				setTimeout(
    					function() {
    						$("#add_remark_msg").text("");
    					},
    					3000
    				)
    			}
    		}
    	});
    }

    //查看用户备注信息
    function look_user_remark(id){    	
       
    	var screen_width = document.body.clientWidth;
    	if (screen_width > 768) {
    		screen_width = screen_width * 3 / 4;
    		if (screen_width > 1280) {
    			screen_width = 1280;
    		}
    	}

    	var screen_height = document.documentElement.clientHeight;
    	if (screen_height > 576) {
    		screen_height = screen_height * 3 / 4;
    		if (screen_height > 720) {
    			screen_height = 720;
    		}
    	} else {
    		screen_height -= 50;
    	}
		
    	$.thinkbox.iframe('<?php echo base_url('admin/user_list/get_users_remark_all'); ?>/' + id, {
    		'title': "<?php echo $admin_order_remark; ?>",
    		'dataEle': this,
    		'unload': true,
    		'width': screen_width,
    		'height': screen_height,
    		'scrolling': "yes"
    	});
    }

    $(function(){
        $("#frost_forever").click(function(){
            var is_checked = $(this).prop("checked");
            if (is_checked == true){
                $("#frost_days").attr("disabled","disabled");
                $("#frost_days").val("");
            } else {
                $("#frost_days").removeAttr("disabled");
            }
        })
    })
    
    $(function(){
        $('select[name="country"]').live('change',function() {
            if (confirm("<?php echo lang('sure');?>")) {
            var mem_id = $('#info_id').text();
            var modifyVal = $('select[name="country"] option:selected').val();
            $.post("/admin/user_list/modify_mem_info", {fieldName: 'country_id', uid: mem_id, modifyVal: modifyVal},
                function (data) {
                    if (data.success) {
                        layer.msg('<?php echo lang('update_success')?>')
                    } else {
                        layer.msg(data.msg);
                    }
                }, "json");
            }
        });
        $('#clear_ewallet').live('click',function(){
            var mem_id = $('#info_id').text();
            var modifyVal = '';
            if(confirm('<?php echo lang('sure')?>')){
                $.post("/admin/user_list/modify_mem_info", {fieldName:'ewallet_name',uid:mem_id,modifyVal:modifyVal},
                    function(data){
                        if (data.success) {
                            $('#info_ewallet_name').text('');
                        }else{
                            layer.msg(data.msg);
                        }
                    }, "json");
            }

        });
        $('.relive_ban').live('click',function(){
            var id = $('#info_id').text();
            $.ajax({
                type: "POST",
                url: "/admin/user_list/relive_ban",
                data: {id:id},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        layer.msg("<?php echo lang('relive_success'); ?>");
                        $('.relive_ban').hide();
                    }else{
                        layer.msg("<?php echo lang('relive_fail'); ?>");
                    }
                }
            });
        });

        //alipay
        $('.alipay_unbundling').live('click',function(){
            var id           = $('#info_id').text();
            var account = $('.get_alipay').text();

            layer.confirm('<?php echo lang('will_unbinding').'</br>'; ?>'+account, {
                btn: ['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>'] //按钮
            }, function(){
                $.ajax({
                    type: "POST",
                    url: "/admin/user_list/do_unbinding",
                    data: {id:id,type:1},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            layer.msg("<?php echo lang('unbundling_success'); ?>");
                            $('#info_alipay_account').hide();
                        }else{
                            layer.msg("<?php echo lang('unbundling_fail'); ?>");
                        }
                    }
                });
            }, function(){

            });
        });

        //paypal
        $('.paypal_unbundling').live('click',function(){
            var id           = $('#info_id').text();
            var paypal       = $('.get_paypal').text();

            layer.confirm('<?php echo lang('will_unbinding').'</br>'; ?>'+paypal, {
                btn: ['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>'] //按钮
            }, function(){
                $.ajax({
                    type: "POST",
                    url: "/admin/user_list/do_unbinding",
                    data: {id:id,type:2,email:paypal},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            layer.msg("<?php echo lang('unbundling_success'); ?>");
                            $('#info_paypal_account').hide();
                        }else{
                            layer.msg("<?php echo lang('unbundling_fail'); ?>");
                        }
                    }
                });
            }, function(){

            });
        });

        //mobile
        $('.mobile_unbundling').live('click',function(){
            var id           = $('#info_id').text();
            var account = $('.get_mobile').text();

            layer.confirm('<?php echo lang('will_unbinding').'</br>'; ?>'+account, {
                btn: ['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>'] //按钮
            }, function(){
                $.ajax({
                    type: "POST",
                    url: "/admin/user_list/do_unbinding",
                    data: {id:id,type:3},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            layer.msg("<?php echo lang('unbundling_success'); ?>");
                            $('#info_mobile').hide();
                        }else{
                            layer.msg("<?php echo lang('unbundling_fail'); ?>");
                        }
                    }
                });
            }, function(){

            });
        });


    });

    $(function(){
        $("#frost_days").keyup(function(){
           $("#frost_forever").prop("checked",false);
        })
    })
</script>