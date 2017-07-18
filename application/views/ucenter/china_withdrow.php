<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc:
 * Date: 2017/7/5
 * Time: 18:10
 */
?>
<style type="text/css">
    td{border:0px solid #039;}
</style>
<div class="block upgrade">
    <p class="block-heading"><?php echo lang('current_commission') ?> : $<span id='curAmount'><?php echo $userInfo['amount']?></span></p>
    <div class="block-body">
        <form id="take_out_form" class="form-inline" method="GET">
            <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
            <input type="hidden" id="confirm_bank_info" value="<?php echo lang('confirm_bank_info');?>">
            <input type="hidden" value="<?php echo lang('confirm_maxie_info')?>" id="confirm_maxie_info">
            <input type="hidden" value="<?php echo lang('confirm_alipay_info')?>" id="confirm_alipay_info">
            <input type="hidden" value="<?php echo lang('confirm_paypal_info')?>" id="confirm_paypal_info">
            <input type="hidden" value="确认银行卡提现 卡号{0}" id="confirm_banks_info" id="confirm_banks_info">
            <table>
                <span style="color: #008200;margin-left:60px;"></a><?php echo lang('take_out_cash_sum').'$'.$take_out_cash_sum;?></span>
                <tr>
                    <td style="text-align:right"><span style="margin-left: 5px;"><?php echo lang('take_out_max_amount');?></span>:</td>
                    <td>
                        <span style='color: #008200;'>$<span id="curUserAmount"><?php echo $userInfo['amount']?></span></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:160px;text-align:right;text-align:right;"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('take_out_cash_type');?>:</td>
                    <td style="width:175px;">
                        <select autocomplete="off" id="take_cash_type" name="take_cash_type" style="width:auto;min-width:190px;" onchange="/*sel_take_cash_type(this.value)*/">
                            <!-- <option value=""><?php echo lang('pls_select'); ?></option> -->
                            <?php foreach ($take_out_type as $key => $value) { ?>
                                <option value="<?php echo $key ?>"><?php echo lang($value); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <!--<td><?php if(empty($pre_card)){?>
							 <span><?php echo lang('pc_apply_tip') ?></span>
						<?php }else {?>
							<span><?php echo lang('pc_applied') ?>：<a href="##" class="btn-xs">
									<?php
                        echo in_array($pre_card['status'],array('5','3')) ? lang('pc_status_pending'):lang('pc_status_'.$pre_card['status']);
                        echo $pre_card['status']=='2'?'：'.$pre_card['reject_remark']:''
                        ?>
								</a></span>
						<?php }?>
					</td> -->
                </tr>
                <tr>
                    <td style="text-align:right;"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('take_out_amount');?>:</td>
                    <td>
                        $<input id="take_out_amount" style="width:165px;display:inline" placeholder="100" autocomplete="off" type="text" name="take_out_amount">
                    </td>
                    <td class="alipay db">
                        <span class="msg"><?php echo lang("withdrawal_alipay_tip")?></span>
                    </td>
                    <td class="debit_card db">
                        <span class="msg"><?php echo lang("withdrawal_bank_tip");?></span>
                    </td>
                    <td class="paypal">
                        <span class="msg"><?php echo lang("withdrawal_paypal_tip")?></span>
                    </td>
                    <script>
                        $(function(){
                            $("#take_out_amount").keyup(function () {
                                //先把非数字的都替换掉，除了数字和.
                                this.value = this.value.replace(/[^\d.]/g,"");
                                //必须保证第一个为数字而不是.
                                this.value = this.value.replace(/^\./g,"");
                                //小數點保留2位
                                this.value = this.value.replace(/^(\d+\.\d{2}).+/g,"$1");
                                //保证只有出现一个.而没有多个.
                                this.value = this.value.replace(/\.{2,}/g,".");
                                //保证.只出现一次，而不能出现两次以上
                                this.value = this.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
                            })
                            $('input[name="take_out_pwd"]').blur(function(){
                                $.post("/ucenter/take_out_cash/validate_pwd", {pwd: $(this).val()}, function (msg) {
                                    if (msg !== '') {
                                        $('#cash_submit_msg').text(msg).removeClass('success');
                                    }else{
                                        $('#cash_submit_msg').text('');
                                    }
                                });
                            })

                            $("#card_number,#c_card_number").keyup(function () {
                                //如果输入非数字，则替换为''，如果输入数字，则在每4位之后添加一个空格分隔
                                this.value = this.value.replace(/[^\d]/g, '').replace(/(\d{4})(?=\d)/g, "$1 ");
                            })

                        });
                    </script>
                </tr>

                <tr class="alipay hidden">
                    <td style="text-align:right;"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('withdrawal_fee_');?>:</td>
                    <td>
                        $<input type="text" style="width:165px;" class="withdrawal_fee_" value="0" disabled="disabled" autocomplete="off">
                    </td>
                    <td>
                        <span class="msg"><?php echo lang("withdrawal_alipay_tip2")?></span>
                    </td>
                </tr>

                <tr class="debit_card hidden">
                    <td style="text-align:right;"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('withdrawal_fee_');?>:</td>
                    <td>
                        $<input type="text" style="width:165px;" class="withdrawal_fee_" value="0" disabled="disabled" autocomplete="off">
                    </td>
                    <td>
                        <span class="msg"><?php echo lang("bank_take_cash_fee");?></span>
                    </td>
                </tr>

                <tr class="alipay hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('withdrawal_actual_');?>:</td>
                    <td>
                        $<input type="text" style="width:165px;" class="withdrawal_actual_" value="0" disabled="disabled" autocomplete="off">
                    </td>
                </tr>



                <tr class="alipay hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('withdrawal_alipay_');?>:</td>
                    <td >

                        <input type="text" style="width:175px;" autocomplete="off" class="alipay_cc" value="<?php echo $userInfo['alipay_account']&&$userInfo['alipay_name'] ? $userInfo['alipay_account'] : ''?>" disabled="disabled">

                    </td>

                    <td><?php if($userInfo['alipay_account'] && $userInfo['alipay_name']){?>
                            <input type="button" value="<?php echo lang("alipay_unbundling") ?>" onclick="location.href='/ucenter/alipay_binding_unbundling'" class="btn btn-white btn-weak" autocomplete="off">
                        <?php }else{?>
                            <input type="button" value="<?php echo lang("alipay_binding") ?>" onclick="location.href='/ucenter/alipay_binding_unbundling'" class="btn btn-white btn-weak" autocomplete="off">
                        <?php }?>
                    </td>
                </tr>

                <tr class="alipay hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('alipay_actual_name');?>:</td>
                    <td >

                        <input type="text" style="width:175px;" autocomplete="off" class="alipay_cc" value="<?php echo $userInfo['alipay_account']&&$userInfo['alipay_name'] ? $userInfo['alipay_name'] : ''?>" disabled="disabled">

                    </td>
                </tr>

                <!-- 开户行名称-->
                <tr class="debit_card hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang("bank_name");?>:</td>
                    <td >
                        <input type="text" style="width:175px;" name="bank_name" autocomplete="off" class="alipay_cc" value="<?php echo isset($user_card['bank_name'])? $user_card['bank_name'] : '';?>" disabled="disabled">
                    </td>
                    <td><?php if(!empty($user_card) && isset($user_card['bank_number'])){?>
                            <input type="button" value="<?php echo lang('unbind_bank_card');?>" onclick="location.href='/ucenter/debit_card'" class="btn btn-white btn-weak" autocomplete="off">
                        <?php }else{?>
                            <input type="button" value="<?php echo lang('bind_bank_card');?>" onclick="location.href='/ucenter/debit_card'" class="btn btn-white btn-weak" autocomplete="off">
                        <?php }?>
                    </td>
                </tr>

                <!-- 开户行支行名称-->
                <tr class="debit_card hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang("bank_branch_name");?>:</td>
                    <td >
                        <input type="text" name="bank_branch_name" style="width:175px;" autocomplete="off" class="alipay_cc" value="<?php echo isset($user_card['bank_branch_name'])? $user_card['bank_branch_name'] : '';?>" disabled="disabled">
                    </td>
                </tr>

                <!-- 银行账号-->
                <tr class="debit_card hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('bank_number');?>:</td>
                    <td >
                        <input type="text" name="bank_number" style="width:175px;" autocomplete="off" class="alipay_cc" value="<?php echo isset($user_card['bank_number'])? $user_card['bank_number'] : '';?>" disabled="disabled">
                    </td>
                </tr>

                <!-- 开户人名称-->
                <tr class="debit_card hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('bank_user_name');?>:</td>
                    <td >
                        <input type="text" style="width:175px;" name="bank_user_name" autocomplete="off" class="alipay_cc" value="<?php echo isset($user_card['bank_user_name'])? $user_card['bank_user_name'] : '';?>" disabled="disabled">
                    </td>
                </tr>




                <!-------------paypal提现------------->
                <tr class="paypal hidden">
                    <td style="text-align:right;"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('withdrawal_fee_');?>:</td>
                    <td>
                        $<input type="text" style="width:165px;"  class="sxf_fee" value="" disabled="disabled" autocomplete="off">
                    </td>
                    <td>
                        <span class="msg"><?php echo lang("paypal_prompt1")?></span>
                    </td>
                </tr>

                <tr class="paypal hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('withdrawal_actual_');?>:</td>
                    <td>
                        $<input type="text" style="width:165px;" class="shijije" value="0" disabled="disabled" autocomplete="off">
                    </td>
                </tr>

                <tr class="paypal hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('paypal_email');?>:</td>
                    <td >

                        <input type="text" style="width:175px;" autocomplete="off" class="paypal_email"  value="<?php echo $userInfo['paypal_email']? $userInfo['paypal_email'] : ''?>" disabled="disabled">

                    </td>

                    <td><?php if($userInfo['paypal_email']){?>
                            <input type="button" value="<?php echo lang("paypal_unbundling") ?>" onclick="location.href='/ucenter/paypal_binding'" class="btn btn-white btn-weak" autocomplete="off">
                        <?php }else{?>
                            <input type="button" value="<?php echo lang("paypal_binding") ?>" onclick="location.href='/ucenter/paypal_binding'" class="btn btn-white btn-weak" autocomplete="off">
                        <?php }?>
                    </td>

                </tr>
                <!-------------paypal提现结束------------->
                <tr class="maxie hidden">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>">Maxie Mobile <?php echo lang('bank_card_number');?>:</td>
                    <td>
                        <input name="maxie_card_number" type="text" style="width:auto;width:175px;" autocomplete="off" placeholder="Maxie Mobile <?php echo lang('bank_card_number');?>" >
                    </td>
                </tr>
                <tr class="maxie hidden">
                    <td></td>
                    <td>
                        <input name="c_maxie_card_number" type="text" style="width:auto;width:175px;" autocomplete="off" placeholder="<?php echo lang('c_bank_card_number');?>" >
                    </td>
                </tr>
                <tr class="manually">
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('payee_info');?>:</td>
                    <td>
                        <input name="account_bank" type="text" style="width:auto;width:175px;" autocomplete="off" placeholder="<?php echo lang('bank_name').lang('example1');?>" >
                    </td>
                    <td>
                        <?php if($curLanguage == 'zh' || $curLanguage == 'hk'){ ?>
                            <span class="msg success">如：广东省深圳市公司银行，内地提现需要加上开户银行所在的省，市。</span>
                        <?php }?>
                    </td>
                </tr>
                <tr class="manually">
                    <td></td>
                    <td>
                        <input name="subbranch_bank" type="text" style="width:auto;width:175px;" autocomplete="off" placeholder="<?php echo lang('subbranch').lang('example2');?>">
                    </td>
                </tr>
                <tr class="manually">
                    <td></td>
                    <td>
                        <input id="card_number" name="card_number" type="text" oncopy="return false;" oncut="return false;" onpaste="return false" style="width:auto;width:175px;" autocomplete="off" placeholder="<?php echo lang('bank_card_number');?>">
                    </td>
                </tr>
                <tr class="manually">
                    <td></td>
                    <td>
                        <input id="c_card_number" name="c_card_number" type="text" oncopy="return false;" oncut="return false;" onpaste="return false" style="width:auto;width:175px;" autocomplete="off" placeholder="<?php echo lang('c_bank_card_number');?>">
                    </td>
                </tr>
                <tr class="manually">
                    <td></td>
                    <td>
                        <input name="account_name" type="text" style="width:auto;width:175px;" autocomplete="off" placeholder="<?php echo lang('card_holder_name');?>">
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('funds_pwd');?>:</td>
                    <td style="width:auto">
                        <input style="width:auto;width:175px;" autocomplete="off" type="password" name="take_out_pwd">
                    </td>
                    <td>
                        <span class="msg success"><?php echo lang('no_funds_pwd_notice')?></span>
                        <a href="<?php echo base_url('ucenter/change_funds_pwd')?>"><?php echo lang('forgot_funds_pwd');?></a>
                    </td>
                </tr>
                <tr >
                    <td style="text-align:right"><span style="margin-left: 5px;"><?php echo lang('remark');?></span>:</td>
                    <td>
                        <textarea name="remark" placeholder="<?php echo lang('remark_content');?>" style="width:auto;width:175px;" autocomplete="off" ></textarea>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right">
                        <input id="take_cash_submit_button" autocomplete="off" name="submit" value="<?php echo lang('take_out_cash');?>" class="btn btn-primary" type="button">
                    </td>
                    <td id="cash_submit_msg" class="msg success" colspan="2"></td>
                </tr>


                <tr style="color: red;font-weight:bold;">
                    <td colspan="3">
                        <span><?php echo lang('notice');?>：</span>
                        <p><?php echo lang('take_out_cash_notice_1')?></p>
                        <input type="hidden" value="<?php echo lang('card_number_match')?>" id="card_number_match">
                        <input type="hidden" value="<?php echo lang('paypal_tishi')?>" id="paypal_email">
                        <input type="hidden" value="<?php echo lang('not_fill_alipay_account')?>" id="not_fill_alipay_account">
                        <input type="hidden" value="<?php echo lang('pls_sel_take_out_type')?>" id="pls_sel_take_out_type">
                        <input type="hidden" value="<?php echo lang('pls_input_correct_amount2')?>" id="pls_input_correct_amount2">
                        <input type="hidden" value="<?php echo lang('pls_input_correct_amount')?>" id="pls_input_correct_amount">
                        <input type="hidden" value="<?php echo lang('pls_input_correct_take_out_pwd')?>" id="pls_input_correct_take_out_pwd">
                        <input type="hidden" value="<?php echo lang('payee_info_incomplete')?>" id="payee_info_incomplete">
                        <input type="hidden" value="<?php echo lang('not_bind_bank_card');;?>" id="has_not_bind_bank">
                        <input type="hidden" value="<?php echo lang('bank_card_infomation_lose');?>" id="has_not_bind_bank_1">

                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    $('#take_out_amount').blur(function(){
        var fee=$(this).val()*0.02;
        if(fee>50){
            fee=50;
        }
        $('.sxf_fee').val(fee);
        $('.shijije').val($(this).val()-fee);
    });

    $('#take_out_amount').blur(function(){
        get_withdrawal_fee();
    });


    $(function(){
        var check_user;
        $('#take_out_amount').bind('input propertychange', function () {
            clearTimeout(check_user);
            check_user = setTimeout(get_withdrawal_fee,500);
        });

        $type = $("#take_cash_type  option:selected").val();
        change_take_type($type);
    });
    function get_withdrawal_fee(){
        $type = $("#take_cash_type  option:selected").val();
        $cash = $('#take_out_amount').val()
        if(($type == 2 || $type == 6) && $cash >= 100){
            $.ajax({
                type: "POST",
                url: "/ucenter/take_out_cash/get_withdrawal_fee",
                data: {cash:$('#take_out_amount').val(),type:2},
                dataType: "json",
                success: function (res) {
                    if(res.success){
                        $('.withdrawal_fee_').val(res.data.withdrawal_fee);
                        $('.withdrawal_actual_').val(res.data.actual_fee);
                    }
                }
            });
        }
    }
</script>
