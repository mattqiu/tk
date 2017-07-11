<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="alert alert-error hidden">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong></strong>
</div>
<!--<div class="pull-right">
    <a href="<?php /*echo base_url('ucenter/sponsorship/export'); */?>"><button class="btn btn btn-primary" <?php /*echo $referrer == NULL ? 'disabled' : '';*/?>><?php /*echo lang('export'); */?></button> </a>
</div>-->
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
//            curAlert.html('<?php //echo lang('input_start_date')?>//');
//            errAlert.removeClass('hidden');
//            hiddenAlert(errAlert,2000);
//            return false;
        }else if(end == ''){
//            curAlert.html('<?php //echo lang('input_end_date')?>//');
//            errAlert.removeClass('hidden');
//            hiddenAlert(errAlert,2000);
//            return false;
        }else if(end < start){
            curAlert.html('<?php echo lang('input_date_error')?>');
            errAlert.removeClass('hidden');
            hiddenAlert(errAlert,2000);
            return false;
        }
        return true;
    }

</script>
<!--<div class="well">-->
<div class="container">
    <table class="table" style="width:100%;border:1px solid #e3e3e3;">
        <thead>
        <tr>
            <th><?php echo lang('id'); ?></th>
            <th><?php echo lang('name'); ?></th>
            <th><?php echo lang('country'); ?></th>
            <th><?php echo lang('email'); ?></th>
            <th><?php echo lang('mobile'); ?></th>
            <th><?php echo lang('user_rank'); ?></th>
            <th><?php echo lang('store_url');?></th>
            <th><?php echo lang('create_time'); ?></th>

        </tr>
        </thead>
        <tbody>
        <?php if ($referrer){ ?>
            <?php foreach ($referrer as $item) { ?>
                <tr>
                    <td><?php echo $item['id'] ?></td>
                    <td><?php echo $item['name']?></td>
                    <td><?php if($item['country_id']) echo lang(config_item('countrys_and_areas')[$item['country_id']]);?></td>
                    <td><?php echo $item['email']?></td>
                    <td>
                        <?php if($item['mobile']!=0){
                            echo $item['mobile'];
                        }?>
                    </td>
                    <td><?php echo lang('level_'.$item['user_rank']) ?></td>
                    <td><?php echo $item['store_url']?></td>
                    <td><?php echo date('Y-m-d', $item['create_time'])?></td>
                </tr>
            <?php } ?>

        <?php }else{ ?>
            <tr>
                <th colspan="8" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;