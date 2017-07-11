
<form id="do_delete">
    <table class="reset_user_pwd">
		<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        <tr>
            <td><input type="text" name='user_id' placeholder="<?php echo lang('user_id') ?>" id="user_id" autocomplete="off"></td>
        </tr>
        <tr>
            <td><input type="text" name='confirm_user_id' placeholder="<?php echo lang('confirm_user_id') ?>" autocomplete="off" id="confirm_user_id"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" autocomplete="false" id="delete_users" class="btn btn-primary" value="<?php echo lang('submit');?>">
                <span id="delete_users_msg" ></span>
            </td>
        </tr>
    </table>
</form>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="uid" value="<?php echo $searchData['uid'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('id')?>">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>

</div>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('id'); ?></th>
            <th><?php echo lang('regi_parent_id'); ?></th>
            <th><?php echo lang('role_super'); ?></th>
            <th><?php echo lang('time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid'] ?></td>
                    <td><?php echo $item['parent_id'] ?></td>
                    <td><?php echo $item['check_admin'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>
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