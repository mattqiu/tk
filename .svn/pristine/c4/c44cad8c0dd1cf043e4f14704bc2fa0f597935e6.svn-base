
<div class="search-well">
	<form class="form-inline" method="GET">
		
		<input class="input-small" id="cron_name" type="text" name="cron_name" placeholder="<?php echo lang('cron_name'); ?>" />
		
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
				<th>ID</th>
				<th><?php echo lang('cron_name'); ?></th>
				<th><?php echo lang('false_count'); ?></th>
				<th><?php echo lang('action'); ?></th>
			</tr>
		</thead>
		<tbody>
		 <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id'] ?></td>
                    <td><?php echo $item['cron_name'] ?></td>
                    <td><?php echo $item['false_count'] ?></td>
                   
                    <td>
                    	<a class="btn btn-primary" href="<?php echo base_url('admin/cron_doing/cronDel?id='.$item['id'])?>"><?php echo lang('label_goods_delete') ?></a>
                    </td>
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
	if (page_data.cron_name != null) {
		$('#cron_name').val(page_data.cron_name);
	}

	
});


</script>