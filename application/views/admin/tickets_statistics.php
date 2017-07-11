<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style>
    .page{padding: 4px 12px;background-color: #ccc}
    .time_input{width: 172px;}
</style>
<div class="search-well">
    <form action="<?php echo base_url('admin/tickets_statistics'); ?>" class="form-inline" method="post" style="margin-bottom: 10px;">
        <div style="float: left;margin-right: 20px;">
            <select id="cus" name="cus_id">
                <option value="" ><?php echo lang('cus_id')?></option>
                <?php if(!empty($cus)){ foreach($cus as $c){?>
                    <option value="<?php echo $c['id']; ?>" <?php if($searchData['cus_id']==$c['id']){echo 'selected';} ?> ><?php echo $c['job_number'].' ('.explode('@',$c['email'])[0].')';?></option>
                <?php } }?>
            </select>
        </div>
        <input class="Wdate time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<div class="well">
    <table class="table table-hover table-condensed table-bordered">
        <thead>
        <tr>
            <th><?php echo lang('cus_id'); ?></th>
            <th><?php echo lang('cus_name');?></th>
            <th><?php echo lang('today_in_tickets'); ?></th>
            <th><?php echo lang('today_out_tickets'); ?></th>
            <?php if($sign==2){ ?>
            <th><?php echo lang('today_unprocessed_tickets'); ?></th>
            <th><?php echo lang('today_tickets_count'); ?></th>
            <th><?php echo lang('all_unprocessed_tickets_count'); ?></th>
            <?php } ?>
            <th><?php echo lang('all_tickets_count'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $num=0;$m=16;$c=1; if(!empty($all_data)){ foreach($all_data as $v){ ?>
            <?php $num++;if($num%$m==0){$m+=16;$c++;} ?>
            <tr class="<?php echo $c; ?> t">
                <?php if($sign==2){ ?>
                    <td><?php echo $v['job_number']; ?></td>
                    <td><?php echo $v['email']; ?></td>
                    <td><?php echo $v['today_in']; ?></td>
                    <td><?php echo $v['today_out']; ?></td>
                    <td><?php echo $v['today_unprocessed']; ?></td>
                    <td><?php echo $v['today_assign']; ?></td>
                    <td><?php echo $v['all_unprocessed']; ?></td>
                    <td><?php echo $v['all_tickets']; ?></td>
                <?php }else{?>
                    <td><?php echo $v['job_number']; ?></td>
                    <td><?php echo explode('@',$v['email'])[0]; ?></td>
                    <td><?php echo $v['d_in']; ?></td>
                    <td><?php echo $v['d_out']; ?></td>
                    <td><?php echo $v['d_count']; ?></td>
                <?php }?>
            </tr>
        <?php } ?>
        <?php  }else{ ?>
            <tr>
                <th colspan="20" style="text-align: center;" class="text-success"> <?php echo lang('no_item')?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php for($i=1;$i<=$c;$i++){ ?>
            <span class="page" val="<?php echo $i; ?>"><a href="#"><?php echo $i; ?></a></span>
    <?php } ?>

</div>
<script>
    $(function(){
        $('.t').hide();
        $('.1').show();
        $('.page').click(function(){
            var id = $(this).attr('val');
            $('.t').hide();
            $('.'+id).show();
        });
    });
</script>