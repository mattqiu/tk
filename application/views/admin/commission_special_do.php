<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">

<div class="block ">
    <p class="block-heading"><?php echo lang('add_in_qualified_list') ?></p>
    <div class="block-body">
        <form id="addQualifiedFrom">
        <div class="row-fluid">
            <input style="float:left;" type="text" name="uid" value="" placeholder="<?php echo lang('user_id')?>">
            <select name="item_type" id="add_into" style="float:left;">
                <option value="">--<?php echo lang('pls_sel_comm_item')?>--</option>
                <?php foreach($commList as $v){?>
                    <option value="<?php echo $v ?>"><?php echo lang(config_item('funds_change_report')[$v]) ?></option>
                <?php }?>
            </select>
            <input id = "new_member_bonus_display" style="float:left;display:none" class="Wdate span2 time_input" type="text" name="new_member_start" value="" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
            <input id='addQualifiedBtn' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
        </div>
        </form>
    </div>
</div>

<div class="block ">
    <p class="block-heading"><?php echo lang('fix_user_commission') ?></p>
    <div class="block-body">
        <form id="fix_user_commission">
        <div class="row-fluid">
            <input type="text" name="uid" value="" placeholder="<?php echo lang('user_id')?>">
            <select name="item_type">
                <option value="">--<?php echo lang('pls_sel_comm_item')?>--</option>
                <?php foreach($commList as $v){?>                 
                        <option value="<?php echo $v ?>"><?php echo lang(config_item('funds_change_report')[$v]) ?></option>                              
                <?php }?>
            </select>
            <input class="Wdate span2 time_input" type="text" name="start" value="" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
            -
            <input class="Wdate span2 time_input" type="text" name="end" value="" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">

            <input id='fix_user_commission_btn' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
        </div>
        </form>
    </div>
</div>


<script>
    $(function(){
        document.getElementById("addQualifiedFrom").reset();$('#addQualifiedBtn').attr("disabled",false); $("#new_member_bonus_display").css('display',"none");
        $("#add_into").change(function(){
            var item_type = $(this).val();
            if (item_type == '26') {
                $("#new_member_bonus_display").css('display',"block");
            } else {
                $("#new_member_bonus_display").css('display',"none");
            }
        })
    })
</script>