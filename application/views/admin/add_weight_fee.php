<div class="well">
	<strong class="text-error">注意！！！运费的单位为元.</strong>
	<form method="post" id="add_weight_fee_form" >
        <input  type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        中国<input type="hidden" value="156" name="country_code">
        <select name="begin_code" class="input-medium" id="select_provi">
            <option value="">省份</option>
            <?php foreach($codes as $code){ ?>
                <option value="<?php echo $code['code']?>"<?php echo $begin_code == $code['code'] ? 'selected' : '';?>><?php echo $code['name']?></option>
            <?php }?>
        </select>
        <br>商品SKU：<input value="<?php echo $goods_sn_main?>" id="goods_sn" name="goods_sn_main" class="input-medium" placeholder="Enter" autocomplete="off">
        <strong class="text-error"><?php echo isset($error_tip)? $error_tip : ''?></strong>
        <strong class="text-info"><?php echo isset($store_code)? '产品仓库始发地省份【'.$store_code .'】': ''?></strong>
        <strong class="text-error"><?php echo isset($goods_name)? $goods_name : ''?></strong>
        <p>
            <br>
            <label>首重(start weight fee)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;续重(add weight fee)</label>
            <br>
        </p>
        <p>
        <?php foreach($codes as $code){ ?>
            <?php echo $code['name']?>
            <br>
            <input class="input-medium" name="start_weight_fee[<?php echo $code['code']?>]" value="<?php echo isset($code['start_weight_fee'])?$code['start_weight_fee']:0?>">
            <input class="input-medium" name="add_weight_fee[<?php echo $code['code']?>]" value="<?php echo isset($code['add_weight_fee'])?$code['add_weight_fee']:0?>">
            <?php if(isset($code['id'])){?>
            已记录ID:<?php echo $code['id']?><input type="hidden" class="input-medium" name="id[<?php echo $code['code']?>]" value="<?php echo $code['id']?>">
            <?php }?>
            <br>
        <?php }?>
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
			url: "/admin/add_weight_fee/do_add",
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