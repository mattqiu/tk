<div class="container" style="border:0px;">
	<div class="tb100">
		<?php if($lists){ ?>
    	<div class="collection">
        	<ul>

					<?php foreach($lists as $goods_info){?>
            		<li>
						<div class="top_img">
								<a href="<?php echo base_url('index/product?snm='.$goods_info['goods_sn_main'])?>" target="_blank"><img src="<?php echo $img_host.$goods_info['goods_img']?>" /></a>
						</div>

                    <p style="margin-top:10px;height:20px;width:80%;overflow:hidden;word-wrap: break-word;">
							<a href="<?php echo base_url('index/product?snm='.$goods_info['goods_sn_main'])?>" target="_blank"><?php echo $goods_info['goods_name']?></a>
					</p>
                    <p style="color:#ff0101;margin-top:-5px;height: 25px;overflow: hidden;">

							<?php echo $this->m_currency->price_format($goods_info['shop_price']*100)?>

					</p>
                    <p style="margin-top:-10px;">
                       	<input style="color:#666" type="button" attr_goods_sn="<?php echo $goods_info['goods_sn_main']?>"  value="<?php echo lang('label_add_to_cart')?>" class="coll_btn_cn cart_collection" />
                        <input style="color: #666" type="button" attr_id="<?php echo $goods_info['goods_id']?>" value="<?php echo lang('cancel_collection'); ?>" class="coll_btn_cn cancel_collection"/>
                    </p>
                </li>
				<?php }?>
            </ul>
        </div>
		<?php }else{?>
			<?php echo lang('no_collection')?>
		<?php }?>
    </div>
</div>
<script>
	$(function(){
		//添加购物车
		$('.cart_collection').click(function(){
			var goods_sn = $(this).attr('attr_goods_sn');
			$.post("/cart/do_add_to_cart", {goods_sn:goods_sn+'-1',quantity:1}, function (data) {
				if(data.code == 0){
					location.href = '/cart';
				}else if (data.code == 1037) {
					layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
				}else if (data.code == 1039) {
					layer.msg('<?php echo lang('label_outofstock');?>');
				}else if (data.code == 1054) {
                    layer.msg('<?php echo lang('cart_items_over_limit');?>');
                }
			},'json');
		});
	});
</script>
<?php echo $pager?>
