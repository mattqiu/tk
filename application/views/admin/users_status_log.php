<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
	<form class="form-inline" method="GET">
		<input class="" id="uid" type="text" name="uid" placeholder="<?php echo lang('user_id'); ?>" />

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_start_time')?>" />
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_end_time')?>" />
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
	</form>
</div> 

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>

<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th><?php echo lang('user_id'); ?></th>
				<th><?php echo lang('users_status_front'); ?></th>
				<th><?php echo lang('users_status_back'); ?></th>
				<th><?php echo lang('why'); ?></th>
				<th><?php echo lang('admin_order_remark_create_time'); ?></th>
			</tr>
		</thead>
		<tbody>
		 <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid'] ?></td>
                    <td><?php echo $sta_arr[$item['front_status']] ?></td>
                    <td><?php echo $sta_arr[$item['back_status']] ?></td>
                    <td>
                        <?php if($item['type'] == 1):?>
                            <?php if($item['front_status'] == 2):?>
                                    <?php echo lang('buckle_fee'); ?>
                                <?php else:?>
                                    <?php echo lang('buckle_fee_error'); ?>
                                <?php endif;?>
                        <?php else:?>
                                <?php echo lang('order_fee'); ?>
                        <?php endif;?>
					 </td>
                    <td><?php echo date('Y-m-d H:i:s',$item['create_time']) ?></td>
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


<!-- 冻结订单添加备注弹层 -->
<div id="div_add_remark" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 20%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo lang('fill_in_frozen_remark')?></h3>
	</div>
	<div class="modal-body">
		<table class="tab_add_remark" style="margin: 0 auto">
			<tr>
				<input type="hidden" id="hidden_order_id">
			</tr>
			<tr>
				<td>
					<textarea id="remark_content" autocomplete="off" rows="3" cols="300" style="width: 50%" placeholder="<?php echo lang("fill_in_frozen_remark")?>"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><span id="add_remark_msg" class="msg error" style="margin-left:0px"></span></td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary"  onclick="submit_frozen_remark()" id="add_remark_submit"><?php echo lang('submit'); ?></button>
	</div>
</div>



<?php
	if (isset($paginate))
	{
		echo $paginate;
	}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}

$(function() {
	'use strict';

	var page_data = <?php echo json_encode($page_data); ?>;
	if (page_data.uid != null) {
		$('#uid').val(page_data.uid);
	}
});


</script>
