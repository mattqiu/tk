
<style>
    .btn{margin-top: -10px;}
    .span_alert{margin-bottom: 20px; color: #009900;}
</style>

<div class="manage1">
    <span class="span_alert"><?php echo "<a style='color: orangered'>提示：</a>此操作用于修正2x5佣金漏发,或者错发、重发等用途"?></span>
    <form method="post" action="commission_2x5">
        <input class="proportion_input" type="text"  name="leader_id" id="cashForMonthFee" value="" placeholder="<?php echo lang('please_input_user_id')?>" >
        <select name="commissionOption" style="width:60px">
            <option value="1">＋</option>
            <option value="2">－</option>
        </select>
        <input class="proportion_input" type="text"  name="user_id" id="cashForMonthFee" value="" placeholder="<?php echo lang('please_input_pay_user_id')?>" ><br>


        <select name="why" style="width:160px;">
            <option value="0"><?php echo lang('reason') ?></php></option>
            <option value="1"><?php echo lang('commission_forget') ?></php></option>
            <option value="2"><?php echo lang('commission_error') ?></option>
            <option value="3"><?php echo lang('commission_repeat') ?></option>
        </select>
        <br>
        <br>

        <br>
        <br>
        <input type="submit" id="submit_id" class="btn btn-primary" value=<?php echo lang('commission_compensation') ?> >
    </form>
</div>



<?php if($status==1){?>
    <span style="display: block; color: #009900"><?php echo lang('alert_commission_compensation_ok')?></span>
<?php }else{ ?>
    <span style="display: none"><?php echo lang('alert_commission_compensation_fail')?></span>
<?php }?>


