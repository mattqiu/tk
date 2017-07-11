<form id="from_split_order">
	<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
	<table class="tab_split_order">
		<tr>
			<td><input type="text" name='order_id' placeholder="<?php echo lang('order_id') ?>" id="order_id"></td>
			<td><input type="button" autocomplete="false" id="btn_split_order" onclick="split_order()" class="btn btn-primary" value="<?php echo lang('submit');?>"></td>
		</tr>
		<tr>
			<td><span class="split_order_msg"></span></td>
		</tr>
	</table>
</form>

<script>
	function split_order(){
		var oldVal = $('#btn_split_order').val();
		$('#btn_split_order').attr("value", $('#loadingTxt').val());
		$('#btn_split_order').attr("disabled", 'disabled');
		$.ajax({
			type: "POST",
			url: "/admin/split_order/submit",
			data: {order_id:$('#order_id').val()},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					$('#btn_split_order').attr("value", oldVal);
					$('#btn_split_order').attr("disabled", false);
					$('.split_order_msg').css('color','green');
					$('.split_order_msg').text(res.msg);
					var children = res.result;

					//删除所有class为newRow的tr
					$("tr").remove(".newRow");

					//遍历数组,追加tr
					var newRow = '';
					$.each(children,function(n,value){
						newRow += '<tr class="newRow" style="color: #880000"><td>'+value["order_id"]+'</td></tr>'
					})
					$('.tab_split_order').append(newRow);
				}else{

					//删除所有class为newRow的tr
					$("tr").remove(".newRow");

					$('#btn_split_order').attr("value", oldVal);
					$('#btn_split_order').attr("disabled", false);
					$('.split_order_msg').css('color','#f00');
					$('.split_order_msg').text(res.msg);
					setTimeout(function(){
						$('.split_order_msg').text('');
					},5000);
				}
			}
		});
	}
</script>