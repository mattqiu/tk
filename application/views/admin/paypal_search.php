<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="well">
	<?php
		if (isset($_POST['startDateStr'])) {
			$start_date_str = $_POST['startDateStr'];
		} else {
			$yesterdayDate = time()-86400;
			$start_date_str = date('Y-m-d', $yesterdayDate);
		}
		if (isset($_POST['endDateStr'])) {
			$end_date_str = $_POST['endDateStr'];
		} else {
			$currentDate = time();
			$end_date_str = date('Y-m-d', $currentDate);
		}
		?>
	<form action="<?php echo base_url('admin/paypal_search/do_search')?>" id="paypal_search_form">
		<table class="api">
			<tr>
				<td class="field">
					<?php echo lang('start_date')?>:</td>
				<td>
<!--					<input type="text" name="startDateStr" maxlength="20" size="10" value="--><?php //echo $start_date_str ?><!--" />-->
					<input class="Wdate time_input search-query" type="text" name="startDateStr" value="<?php echo $start_date_str; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})"  placeholder="<?php echo lang('start_date') ?>">
				</td>
<!--				<td>MM/DD/YYYY</td>-->
			</tr>
			<tr>
				<td class="field">
					<?php echo lang('end_date')?>:</td>
				<td>
<!--					<input type="text" name="endDateStr" maxlength="20" size="10"  value="--><?php //echo $end_date_str ?><!--" />-->
					<input class="Wdate time_input search-query" type="text" name="endDateStr" value="<?php echo $end_date_str; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" placeholder="<?php echo lang('end_date') ?>">
				</td>
<!--				<td>-->
<!--					MM/DD/YYYY-->
<!--				</td>-->
			</tr>

			<tr>
				<td class="field">
					<?php echo lang('txn_id')?>(<?php echo lang('not_require')?>):</td>
				<td>
					<input class="search-query" type="text" name="transactionID" /></td>
			</tr>
			<tr>
				<td class="field">
				</td>
				<td>
					<br />
					<input class="btn btn-primary paypal_search" type="Submit" value="<?php echo lang('submit')?>" /></td>
			</tr>
		</table>
	</form>
</div>
<script>
	$('.paypal_search').click(function() {

		var li ;
		var curEle = $(this);
		var oldSubVal = curEle.val();
		$(this).attr("value", $('#loadingTxt').val());
		$(this).attr("disabled","disabled");
		var oldColor = '#d22215';
		curEle.css('background','#cccccc');

		$.ajax({
			url: "/admin/paypal_search/do_search",
			data:$('#paypal_search_form').serialize(),
			dataType: 'json',
			success: function(res) {
				if (res.success) {
					layer.msg(res.msg);
					setTimeout(function(){
						location.reload();
					}, 2000)
				} else {
					layer.msg(res.msg);
				}
			},
			error: function() {
				layer.msg('<?php echo lang('admin_request_failed')?>');
			},
			beforeSend: function() {
				li = layer.load();
			},
			complete: function() {
				layer.close(li);
				curEle.css('background',oldColor);
				curEle.attr("value", oldSubVal);
				curEle.attr("disabled", false);
			}
		});
		return false;
	});
</script>
