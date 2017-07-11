
<div class="well">
	<form id="upload_form" action="<?php echo base_url('admin/trade/admin_upload_freight_info') ?>" method="post" class="form-inline" enctype="multipart/form-data">
		<input autocomplete="off" class="input-mini" type="file" name="excelfile"/>
		<button class="btn" type="submit" id="submit_button"><i class="icon-upload"></i> <?php echo lang('admin_upload_freight')?></button>
		<a href="/upload/download/upload_model.xlsx"><?php echo lang('admin_download_model')?></a>
	</form>
<span style="color: red; display: block; height: 20px; margin-bottom: 20px;"><?php echo lang('admin_file_order_show');?></span>
	<form id="upload_freight_form" action="<?php echo base_url('admin/trade/admin_upload_return_freight') ?>" method="post" class="form-inline" enctype="multipart/form-data">
		<input autocomplete="off" class="input-mini" type="file" name="excelfile2"/>
		<button class="btn" type="submit" id="submit_button_return"><i class="icon-upload"></i> <?php echo lang('admin_upload_return_fee')?></button>
		<a href="/upload/download/return_freight.xlsx"><?php echo lang("admin_download_model")?></a>
	</form>

</div>
<?php if($adminInfo['role'] != 6){?>
<div class="well">
	<button class="btn scan_shipping" type="button"><?php echo lang('admin_scan_shipping')?></button>
</div>
<?php }?>

<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>
	$(function() {
		$('.scan_shipping').click(function() {
			window.open('<?php echo base_url('admin/trade/scan_shipping'); ?>');
		});
	});

	function errboxHtml(msg) {
		return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
	}

	$('#upload_form').submit(function() {

		if ($('[name=excelfile]').val() == '') {
			layer.msg('<?php echo lang('admin_select_file')?>');
			return false;
		}
		var li ;
		
		$('#submit_button').attr('disabled', true);
		$(this).ajaxSubmit({
			dataType: 'json',
			success: function(res) {
				if (res.success) {
					layer.open({
					  type: 1,
					  title: false,
					  closeBtn: 0,
					  shadeClose: true,
					  skin: 'layui-layer layui-anim layui-layer-dialog layui-layer-border layui-layer-msg layui-layer-hui',
					  content: res.msg
					});
					$('[name=excelfile]').val('');
				} else {
					$.thinkbox(errboxHtml(res.msg));
				}
			},
			error: function() {
				layer.open({
				  type: 4,
				  title: false,
				  closeBtn: 1,
				  shadeClose: true,
				  skin: 'layui-layer layui-anim layui-layer-dialog layui-layer-border layui-layer-msg layui-layer-hui',
				  content: '<?php echo lang('admin_request_failed')?>'
				});
				//layer.msg('<?php echo lang('admin_request_failed')?>', 0);
			},
			beforeSend: function() {
				li = layer.load();
			},
			complete: function() {
				layer.close(li);
				$('#submit_button').attr('disabled',false);
			}
		});
		return false;
	});

	$('#upload_freight_form').submit(function() {

		if ($('[name=excelfile2]').val() == '') {
			layer.msg('<?php echo lang('admin_select_file')?>');
			return false;
		}
		var li ;
		$('#submit_button_return').attr('disabled', true);
		$(this).ajaxSubmit({
			dataType: 'json',
			success: function(res) {
				if (res.success) {
					layer.msg(res.msg);
					setTimeout(function(){
						location.reload();
					}, 2000)
				} else {
					$.thinkbox(errboxHtml(res.msg));
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
				$('#submit_button_return').attr('disabled',false);
			}
		});
		return false;
	});
</script>
