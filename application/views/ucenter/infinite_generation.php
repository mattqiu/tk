<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="alert alert-error hidden">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong></strong>
</div>
<form class="form-search" method="get" onsubmit="return validateSub()">

    <input class="Wdate span2" type="text" name="start" value="<?php echo $start_time;?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date')?>">
    -
    <input class="Wdate span2" type="text" name="end" value="<?php echo $end_time;?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})"  class="input-medium search-query" placeholder="<?php echo lang('end_date')?>">
    <button type="submit" class="btn"><i class="icon-search"></i> <?php echo lang('search');?></button>

</form>

<script>
    function validateSub(){
        start = $('input[name="start"]').val();
        end = $('input[name="end"]').val();
        curAlert = $(".alert-error strong")
        errAlert = $(".alert-error");
        if(start == ''){
            curAlert.html('<?php echo lang('input_start_date')?>');
            errAlert.removeClass('hidden');
            hiddenAlert(errAlert,2000);
            return false;
        }else if(end == ''){
            curAlert.html('<?php echo lang('input_end_date')?>');
            errAlert.removeClass('hidden');
            hiddenAlert(errAlert,2000);
            return false;
        }else if(end < start){
            curAlert.html('<?php echo lang('input_date_error')?>');
            errAlert.removeClass('hidden');
            hiddenAlert(errAlert,2000);
            return false;
        }
        return true;
    }
</script>
<div class="well">
    <table class="table">
        <thead>
        <tr>

            <th><?php echo lang('grant_time'); ?></th>
            <th><?php echo lang('money'); ?></th>
            <th><?php echo lang('qualified_time'); ?></th>
            <th><?php echo lang('is_grant'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($logs){ ?>
            <?php foreach ($logs as $item) { ?>
                <tr>
                    <td><?php echo date('Y-m-d', $item['create_time'])?></td>
                    <td><?php echo $item['money']?></td>
                    <td><?php echo $item['qualified_time']?></td>
                    <td>
                        <?php  if ($item['grant']){?>
                            <span class="text-success" style="font-size: 1.6em;">√</span>
                        <?php }else{?>
                            <span class="text-error" style="font-size: 1.6em;">×</span>
                        <?php }?>
                    </td>
                </tr>
            <?php } ?>

        <?php }else{ ?>
            <tr>
                <th colspan="4" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;