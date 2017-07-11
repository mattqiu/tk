<div class="well">
    <style>
        .form-horizontal .control-label{
            padding: 0px;
        }
        .font_bold{
            font-weight: bold;
        }
        .font_20px{
            font-size: 20px;
        }
    </style>
    <form action="" method="post" class="form-horizontal" id="go_payment">

        <div class="control-group">
            <label class="control-label"> <?php echo lang('current_level')?></label>
            <div class="controls">
                <label class="text-success"><?php echo lang('level_'.$user_rank)?></label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"> <?php echo lang('member_level')?></label>
            <div class="controls radio" >
                <?php foreach($levels as $k=>$level){?>
                    <label class="pull-left" <?php if($user_rank <= $level['rank_id']){ $readonly = "disabled";$checked = '';}else{$readonly='';$checked ="checked";} if ($k){?>style="margin-left: 30px;"<?php }?>>
                        <input type="radio" value="<?php echo $level['rank_id'] ?>" manage_fee="<?php echo $level['manage_fee']; ?>" annual_fee="<?php echo  $level['annual_fee']?> "  name="level" <?php echo $readonly; ?>  <?php echo $checked; ?> >
                        <?php echo lang('level_'.$level['rank_id'])  ?>
                    </label>
                <?php }?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"> <?php echo lang('opening_time')?></label>
            <div class="controls">
                <label class="text-success font_bold"> <?php echo lang('validity')?> <?php echo $validity?></label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"> <?php echo lang('payment_method')?></label>
            <div class="controls">
                <input type="radio" name="payment" value="CNY">
                <img src="<?php echo base_url('ucenter_theme/images/alipay.gif');?>">
                <input type="radio"  name="payment" value="USD" checked>
                <img src="<?php echo base_url('ucenter_theme/images/submit_pay.gif');?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo lang('annual_fee')?></label>
            <div class="controls annual_fee">
                <label class="text-error font_bold font_20px"></label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"> <?php echo lang('monthly_fee')?></label>
            <div class="controls manage_fee">
                <label class="text-error font_bold font_20px"></label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"> <?php echo lang('amount')?></label>
            <div class="controls total_fee">
                <label class="text-error font_bold font_20px"></label>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary" <?php if($user_rank == 1){?>  disabled <?php } ?> >  <?php echo lang('buy_now')?></button>
            </div>
        </div>
    </form>

    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php echo lang('confirm_purchase')?>
                    </h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="control-group">
                        <label class="control-label"> <?php echo lang('buy_member')?></label>
                        <div class="controls modal_level">
                            <label></label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> <?php echo lang('amount')?></label>
                        <div class="controls modal_cash">
                            <label></label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <h4> <?php echo lang('payment_tip')?></h4>
                        请在随后的支付宝支付页面中，选择“<strong>即时到帐交易</strong>”，如下图所示。如果选择“担保交易”需要人工确认，将会产生较大延迟。
                        <br>
                        <img src="http://www.lampym.com/bundles/topxiaweb/img/order/alipay_dualfun_example.png?3.7.4">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">
                        <?php echo lang('go_pay')?>
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->

</div>

<script>
    $(function(){
        chooseLevel();
        $("[name='level']:radio,[name='payment']:radio").click(chooseLevel);
        $(".modal-footer button").click(function(){
            $('#go_payment').submit();
        });
    });
    function chooseLevel(){
        level = $("[name='level']").filter(":checked").val();
        payment = $("[name='payment']").filter(":checked").val();
        name = $("[name='level']").filter(":checked").parent().text();
        $.post("<?php echo base_url('ucenter/upgrade_level/getUpgradeCash')?>", { level: level , payment : payment},
            function(data){
                if(data['success']){
                    $('.total_fee label').html(data.total_fee );
                    $('.annual_fee label').html(data.upgrade_annual_fee + '-'+ data.annual_fee);
                    $('.manage_fee label').html(data.manage_fee );
                    $('.modal_level').html('<span class="text-success font_bold font_20px">'+name+ '</span>');
                    $('.modal_cash').html('<span class="text-success font_bold font_20px">'+ data.total_fee + '</span>');
                }
            },'json');
       if(payment == 'USD'){
           $('#go_payment').attr('action','<?php echo base_url('ucenter/paypal/do_paypal')?>');
       }else if(payment == 'CNY'){
           $('#go_payment').attr('action','<?php echo base_url('ucenter/pay/do_alipay') ?>');
       }
    }
</script>
