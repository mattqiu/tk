<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style>
    .point_formula{
        font-size: 16px;
    }
</style>
<div class="block">
    <div class="block-heading">
            <span class="block-icon pull-right">
                <a rel="tooltip" href="<?php echo base_url('ucenter/rewards_introduced#r2')?>" data-original-title="<?php echo lang('learn_more_rule'); ?>"><i class="icon-hand-right"></i></a>
            </span>
        <a data-toggle="collapse" class=""><?php echo lang('current_algebra_title');?></a>
    </div>
    <div class="block-body">
        <div class="row-fluid">
            <div class="point_formula">

                <div class="my_sale_commissions"><?php echo lang('level_'.$level);?><span class="text"><?php echo lang('current_rank');?></span></div>

                <div class="operators">+</div>
                <a href="<?php echo base_url('ucenter/sponsorship')?>">
                    <div class="my_forced_matrix"><?php echo $QSOs;?><span class="text"><?php echo lang('QSOs');?></span></div>
                </a>
                <?php if(!use_temporary_rule()){?>
                <div class="operators">+</div>
                <a href="<?php echo base_url('ucenter/my_orders')?>">
                    <div class="my_profit_sharing"><?php echo $QRCs;?><span class="text"><?php echo lang('QRCs');?></span></div>
                </a>
                <?php }?>
                <div class="operators">=</div>

                <div class="my_manually"><span id="profitSharingPointManually"><?php echo $algebra;?></span><span class="text"><?php echo lang('current_algebra');?></span></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

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
            hiddenAlert(errAlert,5000);
            return false;
        }else if(end == ''){
            curAlert.html('<?php echo lang('input_end_date')?>');
            errAlert.removeClass('hidden');
            hiddenAlert(errAlert,5000);
            return false;
        }else if(end < start){
            curAlert.html('<?php echo lang('input_date_error')?>');
            errAlert.removeClass('hidden');
            hiddenAlert(errAlert,5000);
            return false;
        }
        return true;
    }

</script>
<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('time'); ?></th>
            <th><?php echo lang('child_id'); ?></th>
            <th><?php echo lang('push_money'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($generationLogs){ ?>
            <?php foreach ($generationLogs as $log) { ?>
                <tr>
                    <td><?php echo date('Y-m-d H:i:s', $log['create_time']) ?></td>
                    <td><?php echo $log['child_id']?></td>
                    <td><?php echo $log['push_money']?></td>
                </tr>
            <?php } ?>

        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;
