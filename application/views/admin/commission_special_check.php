<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">

<div class="block ">
    <p class="block-heading"><?php echo lang('fix_user_commission') ?></p>
    <div class="block-body">
        <form id="fix_user_commissions" method="post" action="commission_special_check/fix_user_commission">
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

            <input  autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="submit">
        </div>
        </form>
    </div>
</div>
<?php if(!empty($amount)){ ?>
<div class="block ">
<p class="block-heading" style="margin-top: 10px;">
<select name="item_type">
                <option value="">--<?php echo lang('pls_sel_comm_item')?>--</option>
                <?php foreach($commList as $v){?>        
                    <?php if($v==$item_type){ ?>
                        <option value="<?php echo $v ?>" selected="selected"><?php echo lang(config_item('funds_change_report')[$v]) ?></option>
                    <?php } else { ?>         
                        <option value="<?php echo $v ?>"><?php echo lang(config_item('funds_change_report')[$v]) ?></option>
                        <?php } ?>                              
                <?php }?>
            </select>
    </p>        
    <?php $total =0; foreach($amount as $v){?>                 
           <div class="row-fluid" style="padding:10px;">
                                     <span>  时间： <?php echo $v['time'] ?></span>        
                                       <span style="margin-left: 20px;">金额：$<?php echo $v['amount']/100;$total += $v['amount'];?></span>  
           </div>                 
    <?php }?>
    <div class="row-fluid" style="padding:10px;">
    总金额：$<?php echo $total/100;?>
    </div> 
</div>
<?php } ?>
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