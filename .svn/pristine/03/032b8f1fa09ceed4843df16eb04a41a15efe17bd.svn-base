<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style>.dropdown-menu{min-width: 0px;}</style>
<div class="search-well">
    <form class="form-inline" method="POST" action="/admin/user_list/group_stat">
        <input type="text" name="user_id" value="<?php echo $result['user_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('member_id')?>">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $result['start'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_start_time')?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $result['end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_end_time')?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
	<table>
        <tr>
            <td>
                <span><?php echo lang('register_total') ?>: <?php echo $result['register_total']?></span>
            </td>
        </tr>
        <tr>
            <td>
                <span><?php echo lang('upgrade_total') ?>: <?php echo $result['upgrade_total']?></span>
            </td>
        </tr>
        <tr>
            <td>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo lang('upgrade_total_for_c') ?>: <?php echo $result['upgrade_total_for_c']?></span>
            </td>
        </tr>
        <tr>
            <td>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo lang('upgrade_total_for_s') ?>: <?php echo $result['upgrade_total_for_s']?></span>
            </td>
        </tr>
        <tr>
            <td>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo lang('upgrade_total_for_g') ?>: <?php echo $result['upgrade_total_for_g']?></span>
            </td>
        </tr>
        <tr>
            <td>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo lang('upgrade_total_for_d') ?>: <?php echo $result['upgrade_total_for_d']?></span>
            </td>
        </tr>
    </table>
</div>