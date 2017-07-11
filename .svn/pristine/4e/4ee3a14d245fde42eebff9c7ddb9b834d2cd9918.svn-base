<div class="modal hide fade korea_order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
<!--		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
		<h3 class="board_news_title"><?php echo lang('april_title') ?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo lang('april_content_1') ?></p>
		<p>
			<label class="modal_main" style="display: inline">
				<input type="radio"value="1" name="type" autocomplete="off">
				<?php echo lang('april_content_2') ?>
			</label></p>
		<!--<p>
			<label class="modal_main" style="display: inline">
				<input type="radio"value="2" name="type" autocomplete="off">
				<?php /*echo lang('april_content_3') */?>
			</label>
		</p>-->
		<p>
			<label class="modal_main" style="display: inline">
				<input type="radio"value="3" name="type" autocomplete="off">
				<?php echo lang('april_content_4') ?>
			</label>
		</p>
		<p><?php echo lang('april_content_5') ?></p>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" disabled class="btn btn-primary" id="order_plan_submit"><?php echo lang('submit'); ?></button>
	</div>
</div>
<script>
	$(function(){
		$('.korea_order').modal({backdrop: 'static', keyboard: false});
		$('input:radio').click(function(){
			$('#order_plan_submit').attr('disabled',false);
		});
		$("#order_plan_submit").click(function () {
			var curEle = $(this);
			var oldSubVal = curEle.val();
			$(this).attr("value", $('#loadingTxt').val());
			$(this).attr("disabled","disabled");
			var oldColor = '#d22215'/*curEle.css('background-color')*/;
			curEle.css('background','#cccccc');
			var type =$('input:radio:checked').val();
			$.ajax({
				type: "POST",
				url: "/ucenter/welcome_new/create_join_plan",
				data: {type: type},
				dataType: "json",
				success: function (data) {
					if (data.success) {
						location.reload();
					} else {
						layer.msg(data.msg);
						curEle.css('background',oldColor);
						curEle.attr("value", oldSubVal);
						curEle.attr("disabled", false);
						location.reload();
					}

				}
			});
		});
	})
</script>
<style>
	.modal p{
		margin-top:13px;
	}

</style>
