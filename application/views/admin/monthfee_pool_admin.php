<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
<div class="block ">
    <form id='monthFeePoolChangeForm'>
    <p class="block-heading"><?php echo lang('monthfee_pool_add_or_reduce') ?></p>
    <div class="block-body">
        <div class="row-fluid">
            <?php echo lang('user_id');?>: <input style="width:100px" type="text" name="monthFeePoolChangeUid" value="">
            <select name="monthFeePoolChangeOper" style="width:60px">
                <option value="1">＋</option>
                <option value="2">－</option>
            </select>
            <input type="text" name="monthFeePoolChangeAmount" value="" style="width:70px">
            <?php echo lang('usd_money')?>
        </div>
        <div>
            <?php echo lang('why')?>: <textarea rows="3" cols="20" name="monthFeePoolChangeDesc"></textarea>
        </div>
        <div class="row-fluid">
            <input id='monthFeePoolChangeSub' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
            <span class='msg' id='monthFeePoolChangeMsg'></span>
        </div>
    </div>

</div>