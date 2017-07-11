<main class="main-content">
	<div class="w1200">
		<p class="crumbs"><i class="pc-tps">&#xe632;</i> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a> &gt; <?php echo $nav_title?> </p>
		<div class="search clear">
			<p class="zshu"><?php echo sprintf(lang('label_cate_count_search'),$total_rows)?></p>
			<dl>
				<dt><?php echo lang('label_cate_rank');?>:</dt>
				<dd>
					<a class="<?php if($order == 'composite') echo 'on';?>" href="<?php echo $cate_url,'&order=composite'?>"><?php echo lang('label_cate_com_rank');?></a>
					<a class="<?php if($order == 'sale') echo 'on';?>" href="<?php echo $cate_url,'&order=sale'?>"><?php echo lang('label_cate_sale');?><i class="pc-tps">&#xe690;</i></a>
					<a class="<?php if($order == 'comments') echo 'on';?>" href="<?php echo $cate_url,'&order=comments'?>"><?php echo lang('label_cate_comments');?><i class="pc-tps">&#xe690;</i></a>
					<a class="<?php if($order == 'price') echo 'on';?>" href="<?php echo $cate_url,'&order=price&arr=',$arr?>"><?php echo lang('label_price');?><i class="pc-tps">&#xe690;</i></a>
				</dd>
			</dl>
			<ul class="clear lazy">
				<?php if($goods) {?>
				<?php foreach($goods as $k=>$item) {?>
				<?php if (($k+1)%5 == 0) { ?>
				<li class="img-xg mr-n">
				<?php } else{ ?>
				<li class="img-xg">
				<?php }?>
                	<?php if($item['is_direc_goods']) {?>
                    <p class="left-zg"><?php echo lang('label_flag_direc')?></p>
                    <?php }?>
					<p class="b-q">
						<?php if($item['price_off']) {?>
						<s class="salle" title="<?php echo lang('menu_label_nav_promote_new')?>"></s>
						<?php }elseif($item['is_hot']){?>
						<s class="hot" title="<?php echo lang('label_comment')?>"></s>
						<?php }elseif($item['is_new']){?>
						<s class="new" title="<?php echo lang('label_single_sale')?>"></s>
						<?php }?>

						<?php if($item['is_free_shipping']) {?>
						<s class="free" title="<?php echo lang('label_nav_free_ship')?>"></s>
						<?php }?>
					</p>
					<i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
					<a class="img" title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" target="_blank"><img data-original="<?php echo $img_host,$item['goods_img']?>" src="<?php echo $web_host,'/themes/mall/img/loading250_250.gif'?>" /></a>
					<dl class="tit">
						<dd class="fl">
							<p><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" target="_blank"><?php echo $item['goods_name']?></a></p>
							<p class="c-o fs-14"><?php echo $curCur_flag,number_format(format_price($item['shop_price'],$cur_rate),2);?> <span class="c-bb">
<!--									/ <s> --><?php //echo $curCur_flag,number_format(format_price($item['market_price'],$cur_rate),2);?><!--</s>-->
								</span></p>
						</dd>
						<dt class="fr">
                        	<?php if($item['country_flag'] != 'tw') {?>
							<img src="<?php echo $this->config->item('country_flag_path').$item['country_flag'].'.jpg'?>" />
                            <?php }?>
							<p class="c-bb"><?php  echo $origin_array[strtolower($item['country_flag'])]; ?></p>
						</dt>
					</dl>
				</li>
				<?php }?>
				<?php }else{?>
				<p style="text-align:center; height:300px;"> <?php echo lang('label_cate_no_records')?></p>
				<?php }?>
			</ul>

			<?php echo $pager;?>
		</div>
		<?php if(!empty($history_goods)) {?>            
		<div class="tps-stars clear">
			<div class="hd clear">
				<h2 class="title fl"><?php echo lang('label_goods_history');$his_count=count($history_goods);?></h2>
				<?php if($his_count > 5) {?>
				<ul></ul>
				<?php }?>
			</div>
			<div class="bd clear">
				<ul>
					<?php foreach($history_goods as $item){?>
					<li>
						<div class="img-xg">
                        	<?php if($item['is_direc_goods']) {?>
							<p class="left-zg"><?php echo lang('label_flag_direc')?></p>
                            <?php }?>
							<p class="b-q">
								<?php if($item['price_off']) {?>
								<s class="salle" title="<?php echo lang('menu_label_nav_promote');?>"></s>
								<?php }elseif($item['is_hot']){?>
								<s class="hot" title="<?php echo lang('label_comment');?>"></s>
								<?php }elseif($item['is_new']){?>
								<s class="new" title="<?php echo lang('label_single_sale');?>"></s>
								<?php }?>

								<?php if($item['is_free_shipping']) {?>
								<s class="free" title="<?php echo lang('label_nav_free_ship')?>"></s>
								<?php }?>
							</p>
							<i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
							<a class="img" title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" target="_blank"><img src="<?php echo $img_host,$item['goods_img']?>"  /></a>
							<dl class="tit">
								<dd class="fl">
									<p><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" target="_blank"><?php echo $item['goods_name']?></a></p>
									<p class="c-o fs-14"><?php echo $curCur_flag,number_format(format_price($item['shop_price'],$cur_rate),2);?> <span class="c-bb">
<!--                                            / <s> --><?php //echo $curCur_flag,number_format(format_price($item['market_price'],$cur_rate),2);?><!--</s>-->
                                        </span></p>
								</dd>
								<dt class="fr">
                                	<?php if($item['country_flag'] != 'tw') {?>
									<img src="<?php echo $this->config->item('country_flag_path').$item['country_flag'].'.jpg'?>" />
                                    <?php }?>
									<p class="c-bb"><?php echo $origin_array[strtolower($item['country_flag'])]; ?></p>
								</dt>
							</dl>
						</div>
					</li>
					<?php }?>
				</ul>
				<?php if($his_count > 5) {?>
				<span class="prev"><s></s></span>
				<span class="next"><s></s></span>
				<?php }?>
			</div>
		</div>
		<?php }?>
	</div>	
</main>

<div class="backToTop-up" title="Top"><i class="pc-tps">&#xe672;</i></div>
<script src="<?php echo base_url(THEME.'/js/jquery.lazyload.min.js')?>"></script> 
<script>
	$(function() {
		jQuery(".tps-stars").hover(function(){ jQuery(this).find(".prev,.next").stop(true,true).fadeTo("show",.4) },function(){ jQuery(this).find(".prev,.next").fadeOut() });
		jQuery(".tps-stars").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"left", vis:5, scroll:2, delayTime:500, autoPlay:true, autoPage:true, pnLoop:false, interTime:6000,});
	});
</script>