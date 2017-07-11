<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<div class="search-well">
	<ul class="nav nav-tabs">
		<?php foreach ($tabs_map as $k => $v): ?>
			<li <?php if ($k == $tabs_type) echo " class=\"active\""; ?>>
				<a href="<?php echo base_url($v['url']); ?>">
					<?php echo $v['desc']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="block ">
	<form id='commChangeForm'>
		<div class="block-body">
			<div class="row-fluid">
				<?php echo lang('user_id');?>: <input style="width:190px" id="user_id" type="text" name="uid" value="">
				<?php echo lang('user_push_num');?>
				<input type="text" name="num" id="num" style="width: 30px;">
			</div>
			<div class="row-fluid">
    				<input id='rollbackSub' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
				<span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
			</div>
		</div>

</form>

</div>

<?php if (isset($err_msg)): ?>
	<div class="well">
		<p style="color: red;"><?php echo $err_msg; ?></p>
	</div>
<?php endif; ?>
<script>
	$("#rollbackSub").click(function () {
		var user_id = $('#user_id').val();
		var num = $('#num').val();
        //防重复提交
        if ($('#rollbackSub').val() == $('#loadingTxt').val()) {
            return false;
        }
        $('#rollbackSub').val($('#loadingTxt').val());
        $('#rollbackSub').css("background", "#858C8F");
		$.ajax({
			type: "POST",
			url: "/admin/repair_abnormality/push_num",
			data: {uid: user_id,num: num},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					$('#submit_success').text(res.msg);
					$('#error_msg').text("");
                    setTimeout(function(){
                        $('#submit_success').text('')
                        //按钮可提交
                        $('#rollbackSub').val("<?php echo lang('submit'); ?>");
                        $('#rollbackSub').css("background", "#446688");
                    },3000);
				} else {
					$('#error_msg').text(res.msg);
					$('#submit_success').text("");
                    setTimeout(function(){
                        $('#error_msg').text('')
                        //按钮可提交
                        $('#rollbackSub').val("<?php echo lang('submit'); ?>");
                        $('#rollbackSub').css("background", "#446688");
                    },3000);
				}
			}
		});
	});
</script>