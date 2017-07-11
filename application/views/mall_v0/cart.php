
<div class="f7f5fa">
	<div class="MyCart">
		<div class="container clear">
			<div class="row clear">
				<!-- 全部商品标头 -->
				<p class="switch-cart">
					<em><?php echo lang('cart_title'); ?></em>
					<span id="cart_item_num"><i class="zhis_cc"></i><?php echo $cart_item_num; ?></span>
				</p>
				<div class="box-bd">
					<dl class="clear">
						<!-- 表首行标题 -->
						<dt class="clear">
							<span class="col-md-7"><span class="P_left"><?php echo lang('cart_product_name'); ?></span></span>
							<span class="col-md-1 center"><?php echo lang('cart_price'); ?></span>
							<span class="col-md-2 center"><?php echo lang('cart_quantity'); ?></span>
							<span class="col-md-1 center"><?php echo lang('cart_amount_to'); ?></span>
							<span class="col-md-1 center"><?php echo lang('cart_operate'); ?></span>
						</dt>
						<?php foreach ($cart_data['items'] as $v): ?>
						<dd class="clear" id="dd<?php echo $v['goods_sn']; ?>">
							<div class="col-md-7">
								<div class="g-pic"><img src="<?php echo $img_host.$v['goods_img']; ?>" title="" class="img100"></div>
								<div class="g-info">
									<p><a href="index/product?snm=<?php echo $v['goods_sn_main']; ?>"><?php echo $v['goods_name']; ?></a></p>
									<?php if (!empty($v['color_size'])): ?>
									<p><?php echo lang('checkout_order_color_size').$v['color_size']; ?></p>
									<?php endif; ?>
									<p class="ts_g"><?php echo sprintf(lang('label_ship_country'), $v['country_str']);?></p>
								</div>
							</div>
							<div class="col-md-1 center top_10">
								<b><?php echo $v['price']; ?></b>
								<p><s><?php echo $v['market_price']; ?></s></p>
							</div>
							<div class="col-md-2 top_10">
								<div class="Spinner">
									<a class="DisDe" href="javascript:void(0)" onclick="submit_decrease('<?php echo $v['goods_sn']; ?>');"><i>-</i></a>
									<input class="Amount" value="<?php echo $v['quantity']; ?>" autocomplete="off" maxlength="3" onblur="submit_set_quantity('<?php echo $v['goods_sn']; ?>', $(this));" \>
									<a class="Increase" href="javascript:void(0)" onclick="submit_increase('<?php echo $v['goods_sn']; ?>');"><i>+</i></a>
								</div>
							</div>
							<div class="col-md-1 center top_10">
								<b class="cse"><?php echo $v['amount_to']; ?></b>
							</div>
							<div class="col-md-1 center top_10">
								<a href="javascript:void(0)" onclick="submit_trash('<?php echo $v['goods_sn']; ?>');"><s class="sc"></s></a>
							</div>
						</dd>
						<?php endforeach; ?>
						<dd class="clear">
							<div class="jies">
								<!-- 总价 -->
								<p>
									<?php echo lang_attr('cart_total_amount', array('count' => $cart_item_num)); ?>
									<b class="cse"><?php echo $cart_data['goods_amount']; ?></b>
								</p>
								<!-- 已节省 -->
								<p><?php echo lang('cart_save'); ?><span id="total_save"><?php echo $cart_data['save']; ?></span></p>
								<!-- 结算按钮 -->
								<p>
									<?php if ($cart_item_num <= 0): ?>
									<a class="btn_Bhui" href="javascript:void(0);"><?php echo lang('cart_checkout'); ?></a>
									<?php else: ?>
									<a class="btn_hong" href="/order/cart_checkout"><?php echo lang('cart_checkout'); ?></a>
									<?php endif; ?>
								</p>
							</div>
						</dd>
					</dl>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

function refresh_page_data(count, figure, sn, qty, amount_to, total_amount, total_save) {

	// 购物车商品数量
	$('.ci-count').removeClass('ss').text(count);
	if (figure > 1)
	{
		$('.ci-count').addClass('ss');
	}
	$('#cart_item_num').html("<i class=\"zhis_cc\"></i>" + count);
	$('#cart_count').text(count);

	// 指定商品 qty amount_to
	if (qty <= 0) {
		$('#dd' + sn).remove();
	} else {
		$('#dd' + sn).find('input.Amount').val(qty);
		$('#dd' + sn).find('b.cse').text(amount_to);
	}

	// total amount, save
	$('.jies').find('b.cse').text(total_amount);
	$('#total_save').text(total_save);

	if (count <= 0) {
		$('.btn_hong').removeClass('btn_hong').addClass('btn_Bhui').attr('href', "javascript:void(0);");
	}
}

// 减一 type: 1
function submit_decrease(sn)
{
	var data = {
		'type': 1,
		'sn': sn,
		'qty': 1,
	}
	$.ajax({
		url: '/cart/do_cart_edit',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				refresh_page_data(
					data.count,
					data.figure,
					data.sn,
					data.qty,
					data.amount_to,
					data.total_amout,
					data.total_save
				);
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

// 加一 type: 2
function submit_increase(sn)
{
	var data = {
		'type': 2,
		'sn': sn,
		'qty': 1,
	}
	$.ajax({
		url: '/cart/do_cart_edit',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				refresh_page_data(
					data.count,
					data.figure,
					data.sn,
					data.qty,
					data.amount_to,
					data.total_amout,
					data.total_save
				);
			} else if (data.code == 1037) {
				layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
			}else if (data.code == 1054) {
                layer.msg('<?php echo lang('cart_items_over_limit');?>');
            }
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

// 设置数量 type: 3
function submit_set_quantity(sn, obj)
{
	var data = {
		'type': 3,
		'sn': sn,
		'qty': obj.val(),
	}
	$.ajax({
		url: '/cart/do_cart_edit',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				refresh_page_data(
					data.count,
					data.figure,
					data.sn,
					data.qty,
					data.amount_to,
					data.total_amout,
					data.total_save
				);
			} else if (data.code == 1037) {
				$('#dd' + sn).find('input.Amount').val(data.qty);
				layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
			} else if (data.code == 103) {
				$('#dd' + sn).find('input.Amount').val(data.qty);
			}else if (data.code == 1054) {
                $('#dd' + sn).find('input.Amount').val(data.qty);
                layer.msg('<?php echo lang('cart_items_over_limit');?>');
            } else {
				window.location.reload();
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

// 删除 type: 4
function submit_trash(sn)
{
	var data = {
		'type': 4,
		'sn': sn,
		'qty': 0,
	}
	$.ajax({
		url: '/cart/do_cart_edit',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				refresh_page_data(
					data.count,
					data.figure,
					data.sn,
					data.qty,
					data.amount_to,
					data.total_amout,
					data.total_save
				);
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

</script>
