<form id="clear_member_account_info_form">
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <table class="upgradeTb">
        <tr>
            <td class="content"><label style="display: inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"></label><input type="text" name='uid' placeholder="<?php echo lang('user_id') ?>"></td>
        </tr>
        <tr>
            <td class="content"><label style="display: inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"></label><input type="text" name='new_email' placeholder="<?php echo lang('new_email') ?>"></td>
        </tr>
        <tr>
            <td class="content"><label style="display: inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"></label><input type="text" name='new_card_number' placeholder="<?php echo lang('new_card_number') ?>"></td>
        </tr>
		<tr>
			<td class="content"><label style="display: inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"></label><textarea name="check_info" placeholder="<?php echo lang('remark')?>"></textarea></td>
		</tr>
        <tr>
            <td class="title">
                <input type="button" autocomplete="false" id="reset_member_account_info" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
            <td class="content" id="reset_member_account_info_msg"></td>
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
			<th><?php echo lang('receive_email'); ?></th>
			<th><?php echo lang('receive_card_number'); ?></th>
			<th><?php echo lang('transfer_card_number'); ?></th>
			<th><?php echo lang('refund_card_number'); ?></th>
			<th><?php echo lang('type'); ?></th>
			<th><?php echo lang('role_super'); ?></th>
			<th><?php echo lang('remark'); ?></th>
			<th><?php echo lang('time'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if ($list){ ?>
			<?php foreach ($list as $item) { ?>
				<tr>
					<td><?php echo $item['uid'] ?></td>
					<td><?php echo $item['receive_email'] ?></td>
					<td><?php echo $item['receive_card_number'] ?></td>
					<td><?php echo $item['transfer_card_number'] ?></td>
					<td><?php echo $item['refund_card_number'] ?></td>
					<td><?php echo lang('transfer_'.$item['type']) ?></td>
					<td><?php echo $item['check_admin'] ?></td>
					<td><?php echo $item['check_info'] ?></td>
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