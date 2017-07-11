<div class="row-fluid">
<!--    <table class="tb">-->
<!--        <tr>-->
<!--            <td><span class="title">--><?php //echo lang('join_time'); ?><!--：</span>--><?php //echo $join_time; ?><!--</td>-->
<!--            <td><span class="title">--><?php //echo lang('enable_time'); ?><!--：</span>--><?php //echo $enable_time; ?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td><span class="title">--><?php //echo lang('store_rating'); ?><!--：</span><strong class="text-info">--><?php //echo $store_rating_text; ?><!--</strong></td>-->
<!--            <td><span class="title">--><?php //echo lang('cur_title'); ?><!--：</span><strong class="text-info">--><?php //echo $cur_title_text; ?><!--</strong></td>-->
<!--        </tr>-->
<!--    </table>-->
    <div class="clearfix"></div>
</div>
<input type="hidden" id="pointText" value="<?php echo lang('point'); ?>">

<div class="block ">
    <p class="block-heading"><?php echo lang('profit_sharing_pool') ?></p>
    <div class="block-body">
        <div class="row-fluid">
            <p class="title">[ <?php echo lang('my') . lang('sharing_point') ?> ] <span class="msg_conversion_formula">(1 <?php echo lang('point'); ?> = $1 )</span></p>
            <div class="point_formula">
                <div class="my-total-point"><span id='profitSharingPoint'><?php echo lang('total_point') ?> <?php echo $totalSharingPoint ?></span><span class="text"><?php echo lang('point') ?></span></div>
                <div class="operators">=</div>
                <?php foreach($rewardSharingPointList as $item){?>
                <?php if($item['end_time']>=date('Y-m-d')){?>
                <div class="my_reward"><?php echo $item['point']?><span class="text"><?php echo lang('point') ?> (<?php echo lang('reward_sharing_point') . ',' . lang('validity') . ' : '.$item['end_time']; ?>)</span></div>
                <div class="operators">+</div>
                <?php }?>
                <?php }?>
                <div class="my_sale_commissions"><?php echo $userInfo['profit_sharing_point'] ?><span class="text"><?php echo lang('point') ?> <?php echo lang('bonus_point') ?></span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <hr/>
        <table class="tb">
            <tr>
                <td>
                    <div class="row-fluid">
                        <p class="title">[ <?php echo lang('commissions_to_sharing_point_auto') ?> ]</p>
                        <div class="proportion_input_div">
                            <div><input class="proportion_input" type="text" id="sale_commissions_proportion" value="<?php echo isset($proportion['sale_commissions_proportion'])?$proportion['sale_commissions_proportion']:0 ?>"></div>
                            <div class="percent">%</div>
                            <div class="msg"></div>
                        </div>
                        <div class="clearfix"></div>
                        <input onclick="saveSharingPointProportion('sale_commissions_proportion')" autocomplete="off" name="submit" value="<?php echo lang('save'); ?>" class="btn btn-primary" type="button">
                    </div>
                </td>
                <!--
                <td>
                    <div class="row-fluid">
                        <p class="title">[ <?php echo lang('forced_matrix_sharing_point') . ' - ' . lang('proportion') ?> ]</p>
                        <div class="proportion_input_div">
                            <div><input class="proportion_input" type="text" id="forced_matrix_proportion" value="<?php echo $proportion['forced_matrix_proportion'] ?>"></div>
                            <div class="percent">%</div>
                            <div class="msg"></div>
                        </div>
                        <div class="clearfix"></div>
                        <input onclick="saveSharingPointProportion('forced_matrix_proportion')" autocomplete="off" name="submit" value="<?php echo lang('save'); ?>" class="btn btn-primary" type="button">
                    </div>
                </td>
                <td>
                    <div class="row-fluid">
                        <p class="title">[ <?php echo lang('profit_sharing_sharing_point') . ' - ' . lang('proportion') ?> ]</p>
                        <div class="proportion_input_div">
                            <div><input class="proportion_input" type="text" id="profit_sharing_proportion" value="<?php echo $proportion['profit_sharing_proportion'] ?>"></div>
                            <div class="percent">%</div>
                            <div class="msg"></div>
                        </div>
                        <div class="clearfix"></div>
                        <input onclick="saveSharingPointProportion('profit_sharing_proportion')" autocomplete="off" name="submit" value="<?php echo lang('save'); ?>" class="btn btn-primary" type="button">
                    </div>
                </td>
                -->
            </tr>
        </table>
        <hr/>
        <div class="row-fluid">
            <p class="title">[ <?php echo lang('sharing_point_to_money') ?> ]</p>
            <p class="cur_money"><?php echo lang('sharing_point_enable_exchange'); ?>：<span id='curSharingPoint'><?php echo $transfer_point ?></span><?php echo lang('point'); ?> <span id="losePointMsg"></span></p>
            <div class="proportion_input_div">
                <div class="percent"><?php echo lang('move'); ?></div>
                <div><input class="proportion_input" type="text" id="pointNeedToMove" value="" autocomplete="off"></div>
                <div class="percent"><?php echo lang('point'); ?></div>
                <div class="percent">=</div>
                <div class="percent">$</div>
                <div><input class="proportion_input" type="text" id="moneyNeedToGet" value="" readonly="" autocomplete="off"></div>

                <div class="percent"><?php echo lang('to'); ?></div>
                <div class="percent"><?php echo lang('current_commission'); ?></div>
                <div id='pointToMoneyMsg' class="msg"></div>
            </div>
            <div class="clearfix"></div>
            <input id='sharingPointToMoneyBtn' onclick="sharingPointToMoney()" autocomplete="off" name="submit" value="<?php echo lang('submit'); ?>" class="btn btn-primary" type="button">
            <div style="margin-top: 15px;">
                <?php echo lang('bonus_point_note');?>
            </div>
        </div>
    </div>
</div>


<div class="block ">
    <p class="block-heading"><?php echo lang('current_commission') ?></p>
    <div class="block-body">
        <div class="row-fluid">
            <p class="cur_commission">$<span id='curAmount'><?php echo $userInfo['amount']?></span> <span id="loseMoneyMsg"></span></p>
            <!--            <hr/>-->
            <!--            <p class="title">[ --><?php //echo lang('manually_sharing_point') ?><!-- ]</p>-->
            <!--            <div class="proportion_input_div">-->
            <!--                <div class="percent">--><?php //echo lang('move');?><!--</div>-->
            <!--                <div class="percent">$</div>-->
            <!--                <div><input class="proportion_input" type="text" id="manuallyMoney" value="" autocomplete="off"></div>-->
            <!--                <div class="percent">=</div>-->
            <!--                <div><input class="proportion_input" type="text" id="manuallyPoint" value="" readonly="" autocomplete="off"></div>-->
            <!--                <div class="percent">--><?php //echo lang('point');?><!--</div>-->
            <!--                <div class="percent">--><?php //echo lang('to');?><!--</div>-->
            <!--                <div class="percent">--><?php //echo lang('sharing_point');?><!--</div>-->
            <!--                <div id='manuallyMsg' class="msg"></div>-->
            <!--            </div>-->
            <!--            <div class="clearfix"></div>-->
            <!--            <input onclick="manuallyAddSharingPoint()" autocomplete="off" name="submit" value="--><?php //echo lang('submit');?><!--" class="btn btn-primary" type="button">-->
        </div>
        


        <hr/>
        <div class="row-fluid">
            <p class="title">[ <?php echo lang('transfer_to_other_members') ?> ]<span style="font-size: 0.875em; color: #666;"></a><?php if(!$korea_hide) { echo lang('transfer_to_cash_sum').'$'.$transfer_cash_sum; } ?></span></p>
            <div class="proportion_input_div">
                <div class="percent"><?php echo lang('move');?></div>
                <div class="percent">$</div>
                <div><input class="proportion_input" autocomplete="off" type="text" id="tranToMemAmount" value=""></div>
                <div class="percent"><?php echo lang('give');?></div>
                <div class="percent"><?php echo lang('member');?>:</div>
                <div>
                    <input class="span2 time_input" autocomplete="off" type="text" id="tranToMemId" value="" class="input-medium search-query" style="width:110px" placeholder="<?php echo lang('member_id') ?>">
					<span id="showucname"></span>
                </div>
                <div style="margin-left: 15px">
                    <input class="span2 time_input" autocomplete="off" type="password" id="tranToMemFundsPwd" value="" class="input-medium search-query" style="width:120px" placeholder="<?php echo lang('funds_pwd') ?>">

                </div>
                <div id='tranToMemMsg' class="msg"></div>
            </div>
            <input type="hidden" value="<?php echo lang('positive_num_error')?>" id="positive_num_error">
            <input type="hidden" value="<?php echo lang('user_id_list_requied')?>" id="user_id_list_requied">
            <input type="hidden" value="<?php echo lang('funds_pwd_error')?>" id="funds_pwd_error">
            <div class="clearfix"></div>
            <input id='tranToMemBtn' onclick="tranToMem()" autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button"><span class='msg success'><?php echo lang('no_funds_pwd_notice')?></span>
            <a href="<?php echo base_url('ucenter/change_funds_pwd')?>"><?php echo lang('forgot_funds_pwd');?></a>
        </div>
    </div>
</div>


<div id="month_fee">
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo lang('add_fee')?></h3>
    </div>
    <div class="modal-body">
        <form action="<?php echo base_url('respond/go_month_pay')?>" method="post" class="form-horizontal" id="go_payment">
            <table class="enable_level_tb"  cellspacing="50%" cellpadding="10">
                <tr><td><span class="title"><?php echo lang('order_receipt_store_level'); ?></span></td>
                   <td> <strong class="text-success"><?php echo $levelInfo['level']; ?></strong></td>
                </tr>
                <tr>
                    <td class="title"><?php echo lang('month') ?>:</td>
                    <td class="modal_main">
                        <label style="display: inline">
                            <input autocomplete="off" type="radio" name="month" value="1" onclick="selmonth();" checked />
                            <?php echo lang('month_1'); ?>
                        </label>
                        <label style="display: inline">
                            <input autocomplete="off" type="radio" name="month" value="2" onclick="selmonth();" />
                            <?php echo lang('month_2'); ?>
                        </label>
                        <label style="display: inline">
                            <input autocomplete="off" type="radio" name="month" value="3" onclick="selmonth();"/>
                            <?php echo lang('month_3'); ?>
                        </label>
                        <label style="display: inline">
                            <input autocomplete="off" type="radio" name="month" value="6" onclick="selmonth();" />
                            <?php echo lang('month_6'); ?>
                        </label>
                        <span class="msg error" id="msg_month"></span>
                    </td>
                </tr>
                <tr>
                    <td class="title"><?php echo lang('amount'); ?>:</td>
                    <td class="modal_main">
                        <input autocomplete="off" class="proportion_input" name="amount" id="enable_level_amount" value="" readonly="" type="hidden">
                        <strong id="format_enable_level_amount" class="text-success"></strong>
                        <input autocomplete="off" class="proportion_input" name="usd_money" id="usd_amount" value="" readonly="" type="hidden">
                        <input type="hidden" id="monthMsg" value="<?php echo lang('no_month')?>" >
                        <span id="msg_amount" class="msg error"></span>
                    </td>
                </tr>
                <tr>
                    <td class="title"><?php echo lang('payment_method'); ?>:</td>
                    <td class="modal_main">
                        <?php foreach($payments as $k=>$payment){
                               $checked = '';
                               if($curLanguage == 'english' && $payment['pay_code'] === 'm_paypal'){
                                   $checked = 'checked';
                               }else if($payment['pay_code'] === 'm_alipay'){
                                    $checked = 'checked';
                               }
                        ?>
                            <label style="">
                                <input autocomplete="off" type="radio" name="payment_method" value="<?php echo $payment['pay_id']?>"  onclick="selPaymentMethod();" <?php echo $checked;?>/>
                                <img src="<?php echo base_url("img/paymentMethod/".$payment["pay_code"].".png");?>" alt="<?php echo $payment['pay_name']?>">
                            </label>
                        <?php }?>
                        <span id="msg_payment_method" class="msg error"></span>
                        <input name="type" value="month_fee" type="hidden">
                        <input type="hidden" id="paymentMsg" value="<?php echo lang("no_payment") ?>" >
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="modal-footer">
        <span id="msg_pay" ></span>
        <button autocomplete="off" class="btn btn-primary"<?php if(config_item('disable_pay')){ ?> disabled="disabled"<?php }?> id="go_month"><?php echo lang('go_pay'); ?></button>
        <input type="hidden" value="<?php echo lang('ewallet_before')?>" id="ewallet_before">
        <input type="hidden" value="<?php echo lang('ewallet_after')?>" id="ewallet_after">
    </div>
</div>
<script>

    /* 边框闪烁 */
    $(function(){
        var thisId = window.location.hash;
        if (thisId == '#month_fee_auto_to') {
            normal('month_fee_auto_to',15);
        }
    })

    function normal(id,times)
    {
        var obj=$("#"+id);
        obj.css("border","solid #FFF 2px");
        if(times<0)
        {
            return;
        }
        times=times-1;
        setTimeout("error('"+id+"',"+times+")",150);
    }
    function error(id,times)
    {
        var obj=$("#"+id);
        obj.css("border","solid #f00 2px");
        times=times-1;
        setTimeout("normal('"+id+"',"+times+")",150);
    }

    //勾选事件
    $("#check_select").click(function(){
        if($(this).is(':checked')){
            $.ajax({
                type: "POST",
                url: "/ucenter/commission/auto_to_month_fee_pool",
                data: {checked:1},
                dataType: "json",
                success: function (data) {
                    if (data.success) {

                    }
                }
            });
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "/ucenter/commission/cancel_auto_to_month_fee_pool",
                data: {checked:0},
                dataType: "json",
                success: function (data) {
                    if (data.success) {

                    }
                }
            });
        }
    })


    $(function () {
        $("[name='payment_method']:radio,[name='month']:radio").click(chooseLevel);
        $("#go_month").click(function () {
            go_month();
        });
    });
    function show_modal(){
        chooseLevel();
        $('#myModal').modal();
    }
    function chooseLevel() {

        payment = $("[name='payment_method']").filter(":checked").val();
        month = $("[name='month']").filter(":checked").val();

        flag = true;
        if (!payment) {
            $('#msg_payment_method').html('× '+ $('#paymentMsg').val());
            flag = false;
        }
        if (!month) {
            $('#msg_month').html('× '+ $('#monthMsg').val());
            flag = false;
        }
        if(!flag){
            return;
        }
        $.post("<?php echo base_url('ucenter/commission/getMonthCash')?>", { month: month , payment : payment},
            function(data){
                if(data['success']){
                    $('#enable_level_amount').val(data.month_fee.money);
                    $('#format_enable_level_amount').html(data.month_fee.format_money );
                    $('#usd_amount').val(data.month_fee.usd_money );
                }
            },'json');
    }

    function selmonth() {
        $('#msg_month').html('');
    }
	
	// 显示会员名称 soly
	$('#tranToMemId').blur(function() {
		ucRegexp = /^\d+$/;
		if ($(this).val() == '' || !ucRegexp.test($(this).val())) return false;
		$.getJSON("<?php echo base_url('ucenter/commission/getnamebyucid')?>", {ucid: $(this).val()}, function(res) {
			if (res.success) $('#showucname').text(res.name);
		});
	});
</script>