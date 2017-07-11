<div class="well">
	<strong class="text-error">注意！！！运费的单位为$美金</strong>
	<form method="post" id="add_weight_fee_form" >
	<input  type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
	<input type="hidden" value="840" name="country_code">
	<br>SKU:<input value="<?php echo $goods_sn_main?>" id="goods_sn" name="goods_sn_main" class="input-medium" placeholder="Enter" autocomplete="off">
	<strong class="text-error"><?php echo isset($error_tip)? $error_tip: ''?></strong>
	<strong class="text-info"><?php echo isset($store_code)? $store_code: ''?></strong>
	<strong class="text-error"><?php echo isset($goods_name)? $goods_name : ''?></strong>
	<p>

		USA <?php echo lang('admin_order_deliver_fee')?>:<input class="input-medium" name="start_weight_fee" value="<?php echo isset($codes['start_weight_fee'])?$codes['start_weight_fee']/100:0?>">
		<?php if(isset($codes['id'])){?>
		已记录ID:<?php echo $codes['id']?><input type="hidden" class="input-medium" name="id" value="<?php echo $codes['id']?>">
		<?php }?>
		<br>
	</p>
		<button type="button" autocomplete="off" class="btn btn-primary" id="add_weight_fee"><?php echo lang('submit')?></button>
	</form>
</div>
<script>
	$('#select_provi').change(function(){
		$('#add_weight_fee_form').submit();
	});
	$("#goods_sn").keydown(function(e) {
		var a = e||window.event
		if (a.keyCode == '13') {//keyCode=13是回车键
			$('#add_weight_fee_form').submit();
		}
	});
	$("#add_weight_fee").click(function(){
		var curEle = $(this);
		var oldSubVal = curEle.text();
		curEle.html($('#loadingTxt').val());
		curEle.attr("disabled","disabled");
		var pro = $("#select_provi option:selected").val();
		if(pro == ''){
			layer.msg("请选择始发地省份");
			curEle.html(oldSubVal);
			curEle.attr("disabled",false);
			return ;
		}
		$.ajax({
			type: "POST",
			url: "/admin/add_weight_fee_us/do_add",
			data: $('#add_weight_fee_form').serialize(),
			dataType: "json",
			success: function (data) {
				if (data.success) {
					layer.msg('Process Success!');
					location.reload();
				}else{
					layer.msg(data.msg);
					curEle.html(oldSubVal);
					curEle.attr("disabled",false);
				}

			}
		});

	});
</script>