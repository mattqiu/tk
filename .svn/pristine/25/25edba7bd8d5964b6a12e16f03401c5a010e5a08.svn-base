<div class="well" class="span12">

<form action="" method="post" class="edit_payment_form" class="form-horizontal">
    <input type="hidden" value="Processing..." id="loadingTxt">
    <label><?php echo lang('pay_name')?></label>
    <div class="span6 pull-right"><textarea name="pay_desc" rows="21" style="width:100%;"><?php echo $payment['pay_desc']?></textarea></div>
    <input type="hidden" name="pay_id" value="<?php echo $payment['pay_id']?>">
        <input class="span6" type="text" value="<?php echo $payment['pay_name']?>" disabled>
        <?php if($payment['pay_config'])foreach(unserialize($payment['pay_config']) as $config){?>
            <label><?php echo lang($config['name'])?></label>
            <input class="span6" type="<?php echo $config['type']?>" name="<?php echo $config['name']?>" value="<?php echo $config['value']?>">
        <?php }?>
        <label><?php echo lang('is_enabled') ?></label>
        <select name="is_enabled" class="span6">
            <option value="0" <?php echo $payment['is_enabled'] == 0 ?'selected':''?> ><?php echo lang('not_enabled') ?></option>
            <option value="1" <?php echo $payment['is_enabled'] == 1 ?'selected':''?> ><?php echo lang('yes_enabled') ?></option>
        </select>
        <div >
            <input type="button" class="btn btn-primary edit_payment_button" value="<?php echo lang('submit')?>">
            <span class="edit_payment_msg"></span>
        </div>
</form>
</div>

