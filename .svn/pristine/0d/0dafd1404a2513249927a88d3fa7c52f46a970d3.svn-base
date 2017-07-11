<div class="member_level">
    <table>
        <tr>
            <!--免费-->
            <td>
                <span class="level free">
                    <p class="name"><?php echo lang('member_free'); ?></p>
                    <p>(<?php echo lang('join_fee'); ?>:$0)</p>
                </span>
            </td>
            <td>
                <span class="aisle"><p>+$<?php echo $join_fee_and_month_fee[5]['join_fee']?>
                <?php echo lang('join_fee'); ?></p><p>———————></p></span>
            </td>

            <!--铜级-->
			<td>
                <span class="level free">
                    <p class="name"><?php echo lang('member_bronze'); ?></p>
                    <p>(<?php echo lang('join_fee'); ?>:$<?php echo $join_fee_and_month_fee[5]['join_fee']?>)</p>
                </span>
			</td>
			<td>
				<span class="aisle"><p>+$<?php echo $join_fee_and_month_fee[3]['join_fee'] - $join_fee_and_month_fee[5]['join_fee']?>
                <?php echo lang('join_fee'); ?></p><p>———————></p></span>
			</td>

            <!--银级-->
            <td>
                <span class="level free">
                    <p class="name"><?php echo lang('member_silver'); ?></p>
                    <p>(<?php echo lang('join_fee'); ?>:$<?php echo $join_fee_and_month_fee[3]['join_fee']?>)</p>
                </span>
            </td>
            <td>
                <span class="aisle"><p>+$<?php echo $join_fee_and_month_fee[2]['join_fee']-$join_fee_and_month_fee[3]['join_fee']?>
                <?php echo lang('join_fee'); ?></p><p>———————></p></span>
            </td>

            <!--金级-->
            <td>
                <span class="level free">
                    <p class="name"><?php echo lang('member_platinum'); ?></p>
                    <p>(<?php echo lang('join_fee'); ?>:$<?php echo $join_fee_and_month_fee[2]['join_fee']?>)</p>
                </span>
            </td>
            <td>
                <span class="aisle"><p>+$<?php echo $join_fee_and_month_fee[1]['join_fee']-$join_fee_and_month_fee[2]['join_fee']?>
                <?php echo lang('join_fee'); ?></p><p>———————></p></span>
            </td>

            <!--钻石-->
            <td>
                <span class="level free">
                    <p class="name"><?php echo lang('member_diamond'); ?></p>
                    <p>(<?php echo lang('join_fee'); ?>:$<?php echo $join_fee_and_month_fee[1]['join_fee']?>)</p>
					<input type="hidden" value="<?php echo lang('alert_register')?>" id="alert_register">
					<input type="hidden" value="<?php echo $parent_id?>" id="parent_id">
					<input type="hidden" value="<?php echo lang('ewallet_before')?>" id="ewallet_before">
					<input type="hidden" value="<?php echo lang('ewallet_after')?>" id="ewallet_after">
                </span>
            </td>
        </tr>
    </table>
</div>
<!--
<div class="block cur_level">
    <p class="block-heading"><?php echo lang('cur_level') ?></p>
    <div class="block-body cur" style="margin-top:-0.5em;height:82px;">
        <span class="level <?php echo $levelInfo['month_class']; ?>" style='width:220px;'>
            <p class="name" style="margin-bottom: 13px;">
                <?php echo lang('cur_monthly_fee_level'); ?><?php echo $levelInfo['level']; ?>
            </p>
            <p style="float:left;text-align:left;font-weight: bold;font-size:0.9em">
                <?php if($userInfo['month_fee_rank']<3){?>
                    <a href="Javascript:changeMonthlyLevelPop();">[<?php echo lang('change_monthly_level');?>]</a>
                <?php }?>
            </p>
            <p style="float:left;text-align:left;font-weight:bold;color:blue;width:800px;font-size:0.9em;">
                <?php echo $userInfo['month_fee_date']?(lang('month_fee_date').': '.$userInfo['month_fee_date'].lang('day_th')):'';?>
                <span id='monthFeeLevelChangeNote' style='color:black;'><?php echo $month_fee_level_change_note?'('.$month_fee_level_change_note.')':''?></span>
                <span id='changeMonthlyLevelPopMsg' class="msg error"></span>
            </p>
        </span>

        <span class="level <?php echo $levelInfo['class']; ?>">
            <p class="name"><?php echo lang('store_level'); ?> : <?php echo $levelInfo['name']; ?></p>
        </span>
        <strong style="margin-top:20px;float:left;" class="text-error hidden" id="payment_note"><?php echo lang('payment_note'); ?></strong>
        <div class="clearfix"></div>
    </div>
</div>
-->
<?php if($level != 1){?>
	<!--
    <div class="alert alert-error alert-block">
        <h4><?php echo lang('alert'); ?>!</h4>
        <span><?php echo lang('upgrade_notice'); ?></span>
    </div>

    <script>
        var index = location.href.indexOf('#');
        var lc = location.href.substr(index+1,7);
        if(index != -1 && lc == 'upgrade'){
            $('#payment_note').removeClass('hidden');
        }
    </script>-->
   <!-- <?php if($month_rank!= 1){?>
    <div class="block upgrade">
    <p class="block-heading"><?php echo lang('monthly_fee_') ?></span></p>
    <div class="block-body">
        <form id="month_pay_form" class="form-inline" method="post"  action="">
            <table>
                <tr>
                    <td><?php echo lang('monthly_fee_level'); ?>:</td>
                    <td class="modal_main">
                        <div style="margin-top: 10px">
                        <?php foreach ($monthLevels as $key => $value) { ?>
                            <?php if($key < $month_rank){ ?>
                            <label>
                                <input type="radio" name="level" value="<?php echo $key ?>" onclick="monthFee();" checked />
                                <?php echo $value; ?>
                            </label>
                            <?php } ?>
                        <?php } ?>
                        </div>
                    </td>
                    <td>
                        <span id="month_msg_level" class="msg error"></span>
                        <input type="hidden" id="month_levelMsg" value="<?php echo lang('pls_sel_level') ?>" >
                    </td>
                </tr>
                <tr>
                    <td><?php echo lang('amount'); ?>:</td>
                    <td>
                        <input autocomplete="off" class="proportion_input" name="amount" id="month_amount" value="" readonly="" type="hidden">
                        <input autocomplete="off" class="proportion_input" name="usd_money" id="usd_amount" value="" readonly="" type="hidden">
                        <strong class="text-success" id="month_format_amount"></strong>
                    </td>
                    <td>
                        <span id="month_msg_amount" class="msg error"></span>
                    </td>
                </tr>
                <tr>
                    <td><?php echo lang('payment_method'); ?>:</td>
                    <td class="modal_main">
                        <?php foreach($payments as $k=>$payment){
                            $checked = '';
                            if($curLanguage == 'english' && $payment['pay_code'] === 'm_paypal'){
                                $checked = 'checked';
                            }else if($payment['pay_code'] === 'm_alipay'){
                                $checked = 'checked';
                            }
                        ?>
                            <label  style="font-size:1.5em;font-weight:bold;">
                                <input autocomplete="off" type="radio" name="month_payment_method" value="<?php echo $payment['pay_id']?>" <?php echo $checked?>/>
                                <?php if($payment['pay_code'] === 'm_amount'){ ?>
                                    <?php echo lang('current_commission')?>
                                <?php }else{ ?>
                                    <img src="<?php echo base_url("img/paymentMethod/".$payment["pay_code"].".png");?>" alt="<?php echo $payment['pay_name']?>">
                                <?php } ?>
                            </label>
                        <?php }?>
                    </td>
                    <td>
                        <span id="month_msg_payment_method" class="msg error"></span>
                    </td>
                </tr>
                <tr style="">

                    <td colspan="3">

                        <label class="pull-left agreementText" value="one">
							<input class="agree_item" type="checkbox" name="agree" onclick="setAgree()" checked="checked" />
							<?php echo lang('regi_accept_text');?></label>
                        <input type="hidden" id="agreeMsg" value="<?php echo lang("no_agree") ?>">
                        <span id="month_msg_agree" class="msg error"></span>
                    </td>
                    <td>
                        
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" value="upgrade_month_fee" name="type">
                        <input autocomplete="off"<?php if(config_item('disable_pay')){ ?> disabled="disabled"<?php }?> class="btn btn-primary" id="upgrade_month_fee" value="<?php echo lang('go_pay'); ?>" type="button">
                    </td>
                    <td>
                        <span id="month_msg_pay"></span>
                        <span class="msg success"><?php if($msg_type=='upgrade_month_fee' && $msg){ echo $msg;}?></span>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    $(function(){
        chooseMonth();
        $("[name='month_payment_method']:radio").click(chooseMonth);
        $("#upgrade_month_fee").click(
            function(){
                go_upgrade_month();
            }
        );
    });
    function chooseMonth(){
        payment = $("[name='month_payment_method']").filter(":checked").val();
        if(payment=='110'){
            $('#month_pay_form').attr('action','<?php echo base_url('ucenter/member_upgrade/tps_amount_pay_monthly_up') ?>');
        }else{
            $('#month_pay_form').attr('action','<?php echo base_url('respond/go_month_pay') ?>');
        }
        monthFee()
    }

</script>
    <?php }?>
    -->
<div class="block upgrade">
    <p class="block-heading"><?php echo lang('product_set') ?></p>
    <input type="hidden"  id="is_can_upgrade" value="<?php echo $is_can_upgrade?>">
    <div class="block-body">
        <form id="pay_form" class="form-inline" method="post"  action="">
            <table>
                <tr>
                    <td><?php echo lang('member_level'); ?>:</td>
                    <td>
                        <select autocomplete="off" id="level" name="level" style="width: 170px;" onchange="selMemberLevel();">
                            <?php foreach ($allLevels as $key => $value) { ?>
                                <?php if($key < $level || ($key==5&&$level==4) ){ ?>
                                	<option value="<?php echo $key ?>" selected ><?php echo $value; ?></option>
                                <?php } ?>
                            <?php } ?>

                        </select>
                    </td>
                    <td>
                        <span id="msg_level" class="msg error"></span>
                        <input type="hidden" id="levelMsg" value="<?php echo lang('pls_sel_level') ?>" >
                    </td>
                </tr>
                <tr>
                    <td><?php echo lang('amount'); ?>:</td>
                    <td>
                        <input autocomplete="off" class="proportion_input" name="amount" id="amount" value="" readonly="" type="hidden">
                        <strong class="text-success" id="format_amount"></strong>
                    </td>
                    <td>
                        <span id="msg_amount" class="msg error"></span>
                    </td>
                </tr>
               <!-- <tr>
                    <td><?php echo lang('payment_method'); ?>:</td>
                    <td class="modal_main">
                        <?php foreach($payments as $k=>$payment){?>
                            <label <?php echo $k ? 'style="margin-left: 30px;"':'';?>>
                                <input autocomplete="off" type="radio" name="payment_method" value="<?php echo $payment['pay_id']?>" checked  onclick="selPaymentMethod();"/>
                                <?php if($payment['pay_code'] === 'amount'){ ?>
                                    <?php echo lang('current_commission')?>
                                <?php }else{ ?>
                                    <img src="<?php echo base_url("img/paymentMethod/".$payment["pay_code"].".png");?>" alt="<?php echo $payment['pay_name']?>">
                                <?php } ?>
                            </label>
                        <?php }?>
                    </td>
                    <td>
                        <span id="msg_payment_method" class="msg error"></span>
                    </td>
                </tr>
                -->
                <tr>

                    <td  colspan="3">

                        <label class="agree_item agreementText" value="3">
							<input class="agree_item" type="checkbox" name="upgrade_agree" onclick="setUpgradeAgree();" checked="checked" />
							<?php echo lang('regi_accept_text2');?></label>
                        <input type="hidden" id="agreeMsg" value="<?php echo lang("no_agree") ?>">
                    </td>
                    <td></td>
                    <td>
                        <span id="msg_upgrade_agree" class="msg error"></span>
                        <b><span id="you_not_choose" style="display: none; color: #F00000;"><?php echo lang('you_also_not_choose_product')?></span></b>
                    </td>
                </tr>
				<tr style="">

					<td colspan="3">

						<label class="pull-left agreementText" value="one">
							<input class="agree_item" type="checkbox" name="agree" onclick="setAgree()" checked="checked" />
							<?php echo lang('regi_accept_text');?></label>
					</td>
					<td>
					</td>
					<td>
						<input type="hidden" id="agreeMsg" value="<?php echo lang("no_agree") ?>">
						<span id="month_msg_agree" class="msg error"></span>
					</td>
				</tr>
                <tr>
					<td>
						<?php if(!config_item('upgrade_switch')){?>
							<input autocomplete="off"<?php if(config_item('disable_pay')){ ?> disabled="disabled"<?php }?> onclick="go_pay();" class="btn btn-primary" id="upgrade_fee" value="<?php echo lang('go_pay'); ?>" type="button">
						<?php }?>
                    </td>
                    <td>
                        <span id="msg_pay"></span>
                        <span class="msg success"><?php if($msg_type=='upgrade' && $msg){ echo $msg;}?></span>
						<?php if(config_item('upgrade_switch')){?>
							<div style="color: red"><?php echo lang('upgrade_switch_tip')?></div>
						<?php }?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!--
<div id="upall_div" class="block upgrade">
    <p class="block-heading"><?php echo lang('upgrade_once_in_all') ?></p>
    <div class="block-body">
        <form id="upall_pay_form" class="form-inline" method="post"  action="">
            <table>
                <tr>
                    <td><?php echo lang('upgrade_all_level_title'); ?>:</td>
                    <td>
                        <select autocomplete="off" id="upall_level" name="upall_level" style="width: 170px;" onchange="selUpallLevel();">
                            <?php foreach ($monthLevels as $key => $value) { ?>
                                <?php if($key < $level){ ?>
                                <option value="<?php echo $key ?>" <?php if($key == 1){ ?>selected <?php }?>><?php echo $value; ?></option>
                                <?php } ?>
                            <?php } ?>

                        </select>
                    </td>
                    <td>
                        <span id="msg_upall_level" class="msg error"></span>
                        <input type="hidden" id="upall_levelMsg" value="<?php echo lang('pls_sel_level') ?>" >
                    </td>
                </tr>
                <tr>
                    <td><?php echo lang('amount'); ?>:</td>
                    <td>
                        <input autocomplete="off" class="proportion_input" name="upall_amount" id="upall_amount" value="" readonly="" type="hidden">
                        <strong class="text-success" id="upall_format_amount"></strong>
                    </td>
                    <td>
                        <span id="msg_upall_amount" class="msg error"></span>
                    </td>
                </tr>
                <tr>
                    <td><?php echo lang('payment_method'); ?>:</td>
                    <td class="modal_main">
                        <label>
                        <input autocomplete="off" type="radio" name="upall_payment_method" value="CNY"  onclick="selPaymentMethodForUpall();" <?php echo $curLanguage != 'english' ? 'checked':'' ?>/>
                        <img src="<?php echo base_url('img/paymentMethod/AliPay.jpg');?>" alt="AliPay">
                        </label>
                        <label style="margin-left: 30px;">
                            <input autocomplete="off" type="radio" name="upall_payment_method" value="UP"  onclick="selPaymentMethodForUpall();"/>
                            <img src="<?php echo base_url('img/'.$curLanguage.'/payment/UP.png');?>" alt="UnionPay">
                        </label>
                        <label style="margin-left: 30px;">
                        <input autocomplete="off" type="radio"  name="upall_payment_method" value="USD" onclick="selPaymentMethodForUpall();"  >
                        <img src="<?php echo base_url('ucenter_theme/images/submit_pay.gif');?>">
                        <input type="hidden" id="upall_paymentMsg" value="<?php echo lang("no_payment") ?>" >
                        </label>
                        <label style="margin-left: 30px;">
                            <input autocomplete="off" type="radio" name="upall_payment_method" value="eWallet"  onclick="selPaymentMethod();" <?php echo $ewallet_name ? '':'disabled' ?>/>
                            <?php if(!$ewallet_name){?><a rel="tooltip" href="javascript:void(0);" data-original-title="<?php echo lang('ewallet_tip')?>" ><?php }?> <img src="<?php echo base_url('ucenter_theme/images/globalewallet.png');?>" alt="eWallet"><?php echo $ewallet_name?'':'</a>' ?>
                        </label>
                        <label style="margin-left: 30px;">
                            <input autocomplete="off" type="radio" name="upall_payment_method" value="tps_amount" onclick="selPaymentMethodForUpall();" <?php echo $curLanguage == 'english' ? 'checked':'' ?>/>
                            <?php echo lang('current_commission')?>
                        </label>
                        <?php if($uid == 1380100680 ){?>
                        <label style="margin-left: 30px;">
                            <input autocomplete="off" type="radio" name="upall_payment_method" value="yspay" />
                            <img src="<?php echo base_url('img/paymentMethod/yspay.png');?>" alt="Yspay">
                        </label>
                        <?php }?>
                    </td>
                    <td>
                        <span id="msg_upall_payment_method" class="msg error"></span>
                    </td>
                </tr>
                <tr>
                    <td  colspan="3">
                        <label style="margin-top: 10px">
                            <input class="agree_item" type="checkbox" name="upall_upgrade_agree" onclick="upallSetUpgradeAgree();" />
                            <span class="agree_item agreementText" value="two"><?php echo lang('regi_accept_text2');?></span>
                            <input type="hidden" id="upall_agreeMsg" value="<?php echo lang("no_agree") ?>">
                        </label>
                    </td>
                    <td></td>
                    <td>
                        <span id="msg_upall_upgrade_agree" class="msg error"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label style="margin-top: 10px">
                            <input class="agree_item" type="checkbox" name="upall_agree" onclick="upallSetAgree()">
                            <span class="pull-left agreementText" value="one"><?php echo lang('regi_accept_text');?></span>
                            <input type="hidden" id="upall_agreeMsg" value="<?php echo lang("no_agree") ?>">
                        </label>
                    </td>
                    <td>
                        <span id="msg_upall_agree" class="msg error"></span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" value="upall" name="type">
                        <input id="up_all_upgrade" autocomplete="off"<?php if(config_item('disable_pay')){ ?> disabled="disabled"<?php }?> onclick="upall_go_pay();" class="btn btn-primary" value="<?php echo lang('go_pay'); ?>" type="button">
                    </td>
                    <td>
                        <span id="upall_msg_pay" class="msg"></span>
                        <span class="msg success"><?php if($msg_type=='upall' && $msg){ echo $msg;}?></span>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
一/次性升级月费和加盟费-->

<script>
    $(function(){
        chooseLevel();
        //$("[name='payment_method']:radio").click(chooseLevel);
        //open popup
        $('.agree_item a').on('click', function(event){
            $('#refuse,#agree').attr('value',$(this).parent().attr('value'))
            $('.cd-popup').show();
        });
        $('#agree').on('click', function(event){
            $(".cd-popup").hide();
//            $('span[value="'+$(this).attr('value')+'"]').prev().prop('checked',true);
            if($('span[value="'+$(this).attr('value')+'"]').prev().prop('checked')===false){
                $('span[value="'+$(this).attr('value')+'"]').prev().click();
            }
        });
        $('#refuse').on('click', function(event){
            $(".cd-popup").hide();
//            $('span[value="'+$(this).attr('value')+'"]').prev().prop('checked',false);
            if($('span[value="'+$(this).attr('value')+'"]').prev().prop('checked')===true){
                $('span[value="'+$(this).attr('value')+'"]').prev().click();
            }
        });
    });
    $(function(){
        chooseUpallLevel();
        $("[name='upall_payment_method']:radio").click(chooseUpallLevel);
    });
    function chooseUpallLevel(){
        payment = $("[name='upall_payment_method']").filter(":checked").val();
        if(payment == 'USD'){
            $('#upall_pay_form').attr('action','<?php echo base_url('ucenter/paypal/do_paypal')?>');
        }else if(payment == 'CNY'){
            $('#upall_pay_form').attr('action','<?php echo base_url('ucenter/pay/do_alipay') ?>');
        }else if(payment == 'eWallet'){
            $('#upall_pay_form').attr('action','');
        }else if(payment == 'UP'){
            $('#upall_pay_form').attr('action','<?php echo base_url('unionpay/go_pay') ?>');
        }else if(payment=='tps_amount'){
            $('#upall_pay_form').attr('action','<?php echo base_url('ucenter/member_upgrade/tps_amount_pay_all_up') ?>');
        }else if(payment=='yspay'){
            $('#upall_pay_form').attr('action','<?php echo base_url('ucenter/yspay/do_yspay') ?>');
        }
        selUpallLevel();
    }
    function chooseLevel(){
        /*payment = $("[name='payment_method']").filter(":checked").val();
        if(payment=='110'){
            $('#pay_form').attr('action','<?php echo base_url('ucenter/member_upgrade/pay') ?>');
        }else{
            $('#pay_form').attr('action','<?php echo base_url('respond/go_month_pay') ?>');
        }*/
        selMemberLevel();
    }
    function setUpgradeAgree() {
        if (!$("input:checkbox[name='upgrade_agree']").is(':checked')) {
            $('#msg_upgrade_agree').html('× '+ $('#agreeMsg').val());
        } else {
            $('#msg_upgrade_agree').html('');
        }
    }
    function upallSetUpgradeAgree() {
        if (!$("input:checkbox[name='upall_upgrade_agree']").is(':checked')) {
            $('#msg_upall_upgrade_agree').html('× '+ $('#upall_agreeMsg').val());
        } else {
            $('#msg_upall_upgrade_agree').html('');
        }
    }
    function upallSetAgree() {
        if (!$("input:checkbox[name='upall_agree']").is(':checked')) {
            $('#msg_upall_agree').html('× '+ $('#upall_agreeMsg').val());
        } else {
            $('#msg_upall_agree').html('');
        }
    }
	function setAgree() {
		if (!$("input:checkbox[name='agree']").is(':checked')) {
			$('#month_msg_agree').html('× '+ $('#agreeMsg').val());
		} else {
			$('#month_msg_agree').html('');
		}
	}
</script>

    <div class="cd-popup" role="alert">
        <div class="cd-popup-container">

            <div>
                <h2 style="text-align: center"><?php echo lang('disclaimer');?></h2>
                <?php echo lang('Agreement');?></div>
            <ul class="cd-buttons">
                <li><a href="Javascript: void(0)" id="agree" value=""><?php echo lang('agree');?></a></li>
                <li><a href="Javascript: void(0)" id="refuse" value=""><?php echo lang('refuse_');?></a></li>
            </ul>
        </div>
        <!-- cd-popup-container -->
    </div>
    <script src="<?php echo base_url('js/new/modernizr.js?v=2'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('css/new/popup.css?v=2'); ?>">
    <?php if($curLanguage == "english"){?>
        <style>
            .cd-popup-container > div {
                text-indent: 0;
            }
        </style>
    <?php }?>

    <div id="info_block" class="block hide">
    <p class="block-heading"><?php echo lang('info_need_complete_for_pay_member') ?></p>
    <div class="block-body">
        <form id="info_form" class="form-inline" method="GET">
            <table class="tps_tb">
            <tr id="info_id_card_num">
                <td><?php echo lang('person_id_card_num');?>：</td>
                <td>
                    <input class="" autocomplete="off" type="text" name="info_id_card_num" value="">
                </td>
                <td>
                    <span id="msg_info_id_card_num" class="msg error"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <input onclick="sub_info()" autocomplete="off" name="submit" value="<?php echo lang('submit'); ?>" class="btn btn-primary" type="button">
                </td>
                <td>
                    <span id="msg_info" class="msg success"></span>
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>
<?php }?>

<!-- 更换月份等级弹层 -->
<div id="changeMonthlyLevelModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('change_monthly_level')?></h3>  
    </div>  
    <div class="modal-body">
        <p style="margin-left:0px;margin-bottom: 30px;;color:blue;font-weight:bold;font-size: 0.9em">
            <?php echo lang('month_fee_level_change_desc')?>
        </p>
        <form action="" method="post" class="form-horizontal" id="changeMonthliFeeLevelForm">
            <table class="enable_level_tb">
                <tr>
                    <td><?php echo lang('monthly_fee_level'); ?>:</td>
                    <td>
                        <select name="monthlyFeeLevel" id='monthlyFeeLevel'></select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><span id="changeMonthliFeeLevelFormMsg" class="msg success" style="margin-left:0px"></span></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="modal-footer">
        <button autocomplete="off" class="btn btn-primary" id="changeMonthliFeeLevelFormSub"><?php echo lang('submit'); ?></button>
    </div>
</div>
<!-- /更换月份等级弹层 -->