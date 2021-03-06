
<main class="main-content">
  <div class="w1200">
	<p class="crumbs"><i class="pc-tps">&#xe632;</i> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a> <?php echo $nav_title?>&gt; <?php echo $goods_info['goods_name']?></p>
	<div class="goods clear">
	  <div class="box">
		<div class="tb_pic bg_loading"><span> <img  src="<?php echo $img_host.$goods_info['img_list'][0]['big_img']?>" alt="" rel="<?php echo $img_host.$goods_info['img_list'][0]['big_img']?>" class="jqzoom"></span></div>
		<div class="imageMenu">
		  <ul>
			<?php foreach($goods_info['img_list'] as $k=>$img) {?>
			<li class="loading_small <?php if($k == 0) {echo 'sma';}?>"><a><img src="<?php echo $img_host.$img['thumb_img']?>" mid="<?php echo $img_host.$img['big_img'];?>" big="<?php echo $img_host.$img['big_img'];?>"></a> </li>
			<?php }?>
		  </ul>
		</div>
	  </div>
	  <div class="itemInfo">
		<div class="tit clear">
			<span class="m-y">
			<?php if($goods_info['is_free_shipping']) {?>
			<em><?php echo lang('label_flag_free');?></em>
			<?php }?>
			<?php //if($goods_info['price_off']) {?>  
			<!--<em><?php //echo $goods_info['price_off']?> <?php //echo lang('label_flag_promote');?></em>-->
			<?php //}?>
		  </span>
		  <h1><?php echo $goods_info['goods_name']?></h1>
		  
		  <p class="fs-13 c-o">
			<?php 
			if($goods_info['ship_note_type'] == 1) {
			  echo sprintf(lang('label_shipping_note1'),$goods_info['ship_note_val']);
			}elseif($goods_info['ship_note_type'] == 2) {
			  echo sprintf(lang('label_shipping_note2'),date('Y/m/d',$goods_info['ship_note_val']));
			}?>
		  </p>
		  <?php if(!empty($goods_info['goods_note'])) {?>
		  <p class="fs-13 c-o"><?php echo $goods_info['goods_note'];?></p>
		  <?php }?>
		</div>

		<p class="m-s"><?php echo lang('label_sku')?>:<span class="js-sku d-ib ml-10"><?php echo $goods_sn?></span>
		<?php if($goods_info['is_doba_goods']) {?>
		<span  class="ml-40"><?php echo lang('label_doba_item_id')?>:<?php echo $goods_info['doba_item_id']?></span>
		<?php }?>
		 <span class="c-99 d-ib ml-40"><?php echo lang('label_place'),': ', $origin_array[strtolower($goods_info['country_flag'])];?></span>       
		</p>

		<?php if(!empty($goods_info['seller_note'])) {?>
		<div class="shming clear"> <?php echo nl2br($goods_info['seller_note']);?> </div>
		<?php }?>
		<dl>
		  <dt><?php echo lang('label_price')?>:</dt>
		  <dd><b class="sys_item_price"><?php echo $curCur_flag,number_format(format_price($goods_info['shop_price'],$cur_rate),2);?></b>
		  <s><?php echo  $goods_info['is_promote'] ?  $curCur_flag.number_format(format_price($goods_info['old_shop_price'],$cur_rate),2) : '';?></s> 
		  </dd>
		  <dd>
			
			<?php if($goods_info['price_off']) {?>   
			<span class="jishi" ><?php echo lang('label_flag_left_time')?>&nbsp;<span id="time_coutDown" data-time="<?php echo $goods_info['left_time']?>"></span></span>
			<?php }?>   
		  </dd>
		</dl>
		<?php if(!empty($goods_info['color_list'])) {?>
		<dl class="sys_item_specpara">
		  <dt><?php echo lang('label_color')?>:</dt>
		  <dd>
			<ul class="sys_spec_img">
			  <?php 
			  foreach($goods_info['color_list'] as $color=>$img) {
				?>
			  <li class="js-li-color <?php if(isset($goods_info['color']) && $goods_info['color'] == $color) {?> on <?php }?>"> 
			  <a href="javascript:;" class="js-color" title="<?php echo $color?>"><img src="<?php echo $img_host.$img?>"></a> 
			  <i class="xuanzhong"></i>
			   </li>
			  <?php }?>
			</ul>
		  </dd>
		</dl>
		<?php }?>
		<?php if(!empty($goods_info['other_list'])) {?>
		<dl class="sys_item_specpara">
		  <dt class="other-attr-dt"><?php echo lang('label_other')?>:</dt>
		  <dd>
			<ul class="sys_spec_text other-attr">
			  <?php 
              $customer_key_arr_goods_sn = isset($goods_info['customer_key_arr'][$goods_sn]) ? $goods_info['customer_key_arr'][$goods_sn] : '';
			  $customer_key_arr = isset($goods_info['customer_key_arr'][$customer_key_arr_goods_sn]) ? $goods_info['customer_key_arr'][$customer_key_arr_goods_sn] : '';
			  foreach($goods_info['other_list'] as $other) {
				$disable = '';
				$disable = !empty($customer_key_arr) &&  !in_array($other, $customer_key_arr) && $goods_info['color_list'] ?   'disable' : '';
			  ?>
			  <li class="js-li-other  <?php if(isset($goods_info['other']) && $goods_info['other'] == $other) {?> on <?php } echo $disable;?>"> 
				<a href="javascript:;" class="js-other" title="<?php echo $other?>"><?php echo $other?></a> 
				<i class="xuanzhong"></i> 
			  </li>
			  <?php }?>
			</ul>
		  </dd>
		</dl>
		<?php }?>
		<?php if(!empty($goods_info['size_list'])) {?>
		<dl class="sys_item_specpara">
		  <dt><?php echo lang('label_size')?>:</dt>
		  <dd>
			<ul class="sys_spec_text">
			  <?php 
                $customer_list_goods_sn = isset($goods_info['customer_list'][$goods_sn]) ? $goods_info['customer_list'][$goods_sn] : '';
				$customer_list_size = isset($goods_info['customer_list'][$customer_list_goods_sn]) ? $goods_info['customer_list'][$customer_list_goods_sn] : '';
				  foreach($goods_info['size_list'] as $size) {
					$disable = '';
					$disable =  !empty($customer_list_size) && !in_array($size, $customer_list_size) && $customer_list_size  ?  'disable' : '';
					
				  ?>
				  <li class="js-li-size <?php if(isset($goods_info['size']) && $goods_info['size'] == $size && $disable == '') {?> on <?php } echo $disable;?> " >
					  <a href="javascript:;" class="js-size" title="<?php echo $size?>"><?php echo $size?></a> 
				  <i class="xuanzhong"></i> 
			  </li>
			  <?php }?>
			</ul>
		  </dd>
		</dl>
		<?php }?>        
		<?php if(!empty($gift_list)) {?>
		<dl class="sys_item_specpara">
		  <dt><?php echo lang('label_gift')?></dt>
		  <dd>
			<ul class="sys_spec_img">
			  <?php foreach($gift_list as $goods) {?>
			  <li><a target="_blank" title="<?php echo $goods['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><img width="30" height="30" src="<?php echo $img_host.$goods['goods_img']?>" /><em>×<?php echo $goods['num'];?></em></a></li>
			  <?php }?>
			</ul>
		  </dd>
		</dl>
		<?php }?>
		<dl>
		  <dt><?php echo lang('label_qty')?>:</dt>
		  <form action="<?php echo base_url(),'order/product_checkout'?>" method="get" class="js-buy-now">
			<dd>
			  <span class="tb-amount-widget mui-amount-wrap">
				<input type="hidden" value="<?php echo $goods_sn?>" name="goods_sn" />
				<input type="text" value="1" name="quantity" class="js-number tps-text" />
				<span class="mui-amount-btn">
				  <span class="mui-increase js-s1"> + </i></span>
				  <span class="js-s2"> - </span>
				</span>
				<span class="c-99"></span>
			  </span>
			  <em class="c-99 ml-30"><?php echo lang('label_stock')?><span class=" js-lanse"><?php echo $goods_info['goods_number']?></span></em>
			</dd>
		  </form>
		</dl>

		  <?php if($addrName != ''){?>
		  <p class="m-s"><?php echo lang('label_shipping_address')?>: <span class="ml-10"><?php echo $addrName?></span></p>
		  <?php }?>
		<div class="goods-cart clear">
		  <span class="goods-cart-c">
			<?php if($goods_info['is_on_sale'] == 1) {?>
			<?php if($goods_info['goods_number'] == 0) {?>
			<span><?php echo lang('label_outofstock')?></span>
			<?php }else {?>
			<a class="buy js-buynow" href="javascript:;"><i class="pc-tps">&#xe622;</i><?php echo lang('label_buy_new')?></a>
			<a class="cart js-incart" href="javascript:;"><i class="pc-tps">&#xe64c;</i><?php echo lang('label_add_to_cart')?></a>   
			<?php }?>                 
			<?php }else {?>
			<span><?php echo lang('info_buy_invalid')?></span>
			<?php }?>
		  </span>
		  <a data-sn="<?php echo $goods_info['goods_sn_main']?>" data-id="<?php echo $goods_info['goods_id'];?>"  class="shouc js-like" href="javascript:;"><i class="pc-tps">&#xe603;</i><?php echo sprintf(lang('label_wish_goods'),$wish_goods_count);?></a>

		  <div class="js-confirm tjia shux clear">
			<ul>
			  <li class="tt"><?php echo lang('label_attr_selectd');?></li>
			  <div class="js-attr"></div>
			</ul>
			<p><a href="javascript:;" class="js-sel-ok cart"><?php echo lang('label_attr_ok');?></a> <a href="javascript:;" class="js-sel-cancel buy"><?php echo lang('label_attr_cancel');?></a></p>
		  </div>

		  <div class="js-check tjia clear">
			<p><s></s><?php echo lang('info_add_cart')?></p>
			<p><a href="<?php echo base_url(),'cart'?>" class="incart cart"><?php echo lang('label_check_out')?></a><a href="javascript:;" class="goumai buy"><?php echo lang('label_contine_buy')?></a></p>
		  </div>
		  
		</div>                    
	  </div>  
	</div>
	<?php if(!empty($goods_group_info)) {?>
	<div class="w1200">
	  <h3 class="c-t"><?php echo lang('label_goods_group_info')?></h3>
	  <div class="t-c clear">
		<div class="JS-tc">
		  <ul>
			<?php foreach($goods_group_info['list'] as $g_item) {?>
			<li>
			  <dl class="clear">
				<dt  class="loading_small"><a title="<?php echo $g_item['info']['goods_name']?>" target="_blank" href="<?php echo base_url(),'index/product?snm=',$g_item['info']['goods_sn_main'];?>" target="_blank"><img src="<?php echo $img_host,$g_item['info']['goods_img']?>"  width="70" height="70"></a></dt>
				<dd>
				  <a title="<?php echo $g_item['info']['goods_name']?>" target="_blank" class="tit" href="<?php echo base_url(),'index/product?snm=',$g_item['info']['goods_sn_main'];?>" target="_blank"><?php echo $g_item['info']['goods_name']?></a>
				  <p><b><?php echo $curCur_flag,number_format(format_price($g_item['info']['shop_price'],$cur_rate),2);?> * <?php echo $g_item['num']?></b></p>
				</dd>
			  </dl>
			</li>
			<?php }?>
		  </ul>
		  <span class="prev"><i class="pc-tps">&#xe63f;</i></span>
		  <span class="next"><i class="pc-tps">&#xe648;</i></span>
		</div>
		<ul class="rig">
		  <li><?php echo lang('label_goods_group_price')?>：<em class="c-o"><?php echo $curCur_flag,number_format(format_price($goods_info['shop_price'],$cur_rate),2);?></em></li>
		  <li><?php echo lang('label_goods_group_save')?>：<?php echo $curCur_flag,number_format(format_price($goods_group_info['total']-$goods_info['shop_price'],$cur_rate),2);?></li>
		  <li><?php echo lang('label_goods_group_num')?>：<?php echo $goods_group_info['number'];?></li>
		  <?php if($goods_info['is_on_sale'] == 1 && $goods_info['goods_number'] > 0) {?>
		  <li><a href="javascript:;" class="btn-red js-buynow"><?php echo lang('label_buy_new')?></a></li>
		  <?php }?> 
		</ul>
	  </div>
	</div>
	<?php }?>
	<div class="mainCon clear">
	  <div class="w1200 goods-menu">
		<dl> 
		  <dt><img title="<?php echo $goods_info['goods_name']?>" width="40" height="40" src="<?php echo $img_host,$goods_info['goods_img']?>"></dt> 
		  <dd><strong title="<?php echo $goods_info['goods_name']?>"><?php echo $goods_info['goods_name']?></strong><p><span class="c-o"><?php echo $curCur_flag,number_format(format_price($goods_info['shop_price'],$cur_rate),2);?></span></p></dd>
		</dl>
		<ul>
		  <li><s class="jt"></s><?php echo lang('label_desc')?></li>
		  <li><s class="jt"></s><?php echo lang('label_goods_com')?></li>
		</ul>
		<?php if($goods_info['is_on_sale'] == 1 && $goods_info['goods_number'] > 0) {?>
		<a class="cart js-incart-1" href="javascript:;"><i class="pc-tps">&#xe64c;</i><?php echo lang('label_add_to_cart')?></a>
		<?php }?>   
	  </div>
	  <div class="w1200 img100 mian">
		<div class="list">
		  <h3><?php echo lang('label_category_all')?></h3>
		  <ul>
			<?php

			  foreach($category_all as $cat) {
			  if($cat['level'] == 0) {

			?>
			<li>
			  <a href="<?php echo $web_host,'index/category?sn=',$cat['cate_sn'];?>" class="b-t ellipsis" title="<?php echo $cat['cate_name']?>" target="_blank"><?php echo ucwords($cat['cate_name'])?></a>
			  <?php
			  foreach($category_all as $sub) {
			  if($sub['parent_id'] == $cat['cate_id']) {
			  ?>
			  <a class="ellipsis" href="<?php echo $web_host,'index/category?sn=',$sub['cate_sn'];?>" target="_blank" title="<?php echo $sub['cate_name']?>"><?php echo ucwords($sub['cate_name'])?></a>
			  <?php
			  }
			}
			  ?>
			</li>
			<s class="xian"></s>
			<?php
			  }
			}
			?>
		  </ul>
		</div>
		<div class="bd clear">
		  <ul>
			<li class="detail_img_list">
			  <?php
				if ( '156' == $curLocation_id && $goods_info['country_flag'] != 'cn') {
					echo "<img src='{$web_host}themes/mall/img/inform_new.jpg' >";
				}
			   ?>

			  <p class="tit"><?php echo nl2br(htmlspecialchars_decode($goods_info['extends']['goods_desc']));?> </p>
			  <?php foreach($goods_info['detail_img_list'] as $img) {?>

			  <img src="<?php echo base_url(),'/themes/mall/img/loading250_250.gif'?>" data-original="<?php echo $img_host.$img['image_url']?>" />
			  <?php }?>
			  <!--无详情图片就显示相册图片-->
			  <?php if(empty($goods_info['detail_img_list']) && !empty($goods_info['img_list'])) {?>
			  <?php foreach($goods_info['img_list'] as $k=>$img) {?>
			  <img src="<?php echo base_url(),'/themes/mall/img/loading250_250.gif'?>" data-original="<?php echo $img_host.$img['big_img']?>" />
			  <?php }?>
			  <?php }?>
			</li>
			<li>
			  <div class="topreviews clear">
				<h4><?php echo lang('label_com_avg_score')?></h4>
				<p><s class="star_bg1"><s class="star1" style="width:<?php echo ($goods_info['comment_star_avg']/5)*100,'%';?>"></s></s><span><?php echo $goods_info['comment_star_avg']?></span>(<em class="cese"><?php echo $goods_info['comment_count']?></em> <?php echo lang('label_com')?>) 
				  <!--input type="button" value="<?php echo lang('label_com_write')?>" class="input"--> 
				</p>
				<ul class="clear">
				  <?php foreach($goods_comments_star['list'] as $score) {?>
				  <li><span class="lanse"><?php echo $score['com_score']?> <?php echo lang('label_com_score')?></span><s class="hengtiao"><s class="Theng " style="width:<?php echo ($score['c']/$goods_comments_star['count'])*100,'%';?>"></s></s>
					<span>
					  <?php echo $score['c']?>(<?php echo number_format(($score['c']/$goods_comments_star['count'])*100,2),'%';?>)</span> </li>
				  <?php }?>
				</ul>
			  </div>
			  <div class="rev_list">
				<dl>
				  <dt> <span><?php echo lang('label_com_author')?></span> <span><?php echo lang('label_com_date')?></span> <span><?php echo lang('label_com_star')?></span> <!--span class="w40"><?php echo lang('label_com_content')?></span--> </dt>
				  <?php foreach($goods_comments as $k=>$com) {?>
				  <dd <?php if($k%2 != 0) {?>class="hui"<?php }?>> <span><?php echo '****',substr($com['com_user'],4)?></span> <span class="hui_999"><?php echo date('Y/m/d H:i:s',$com['add_time'])?></span> <span><s class="star_bg1"><s class="star1" style="width:<?php echo ($com['com_score']/5)*100,'%';?>"></s></s></span> <!--span class="w40"><?php echo $com['com_contents']?></span--> </dd>
				  <?php }?>
				</dl>
				<p class="huit load_comment">
				  <?php if($goods_info['comment_count'] > 20) {?>
				  <a class="" href="javascript:;"><span><?php echo $goods_info['comment_count']?> <?php echo lang('label_com')?></span></a>
				  <?php }else {?>
				  <?php echo $goods_info['comment_count']?> <?php echo lang('label_com')?>
				  <?php }?>
				</p>
			  </div>
			</li>
		  </ul>
		</div>
		<?php if(!empty($recomment_goods)) {?>
		<div class="Related clear lazy">
		  <h3><?php echo lang('label_goods_recomment') ?></h3>
		  <ul class="clear">
			<?php foreach($recomment_goods as $item){?>
			<li class="img-xg1 clear">
			  <?php if($item['is_new']) {?>
			  <s class="new"></s>
			  <?php }elseif($item['is_hot']) {?>
			  <s class="hot"></s>
			  <?php }?>
			  <a class="img" title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" ><img data-original="<?php echo $img_host,$item['goods_img']?>" src="<?php echo $web_host,'/themes/mall/img/loading250_250.gif'?>" /></a>
			  <dl class="tit">
				<dd class="fl">
				  <p><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" ><?php echo $item['goods_name']?></a></p>
				  <p class="c-o fs-14"><?php echo $curCur_flag,number_format(format_price($item['shop_price'],$cur_rate),2);?></p>
				</dd>
				<dt class="fr">
				  <img src="<?php echo $this->config->item('country_flag_path').$item['country_flag'].'.jpg'?>" width="18" height="13" />
				  <p class="c-bb"><?php  echo $origin_array[strtolower($item['country_flag'])]; ?></p>
				</dt>
			  </dl>
			</li>
			<s class="xian"></s>
			<?php }?>
		  </ul>
		</div>
		<?php }?>
	  </div>
	</div>        
	<?php if(!empty($history_goods)) {?> 
	<div class="w1200">                
	  <div class="tps-stars pr clear">
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
				<i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
				<a class="img" title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" >
					<p class="b-q">
						<?php if($item['price_off']) {?>
							<b class="pc-tps salle" title="<?php echo lang('label_nav_promote')?>">&#xe674;</b>
						<?php }elseif($item['is_hot']){?>
							<b class="pc-tps hot" title="<?php echo lang('label_comment')?>">&#xe673;</b>
						<?php }elseif($item['is_new']){?>
							<b class="pc-tps new" title="<?php echo lang('label_single_sale')?>">&#xe670;</b>
						<?php }?>
						<?php if($item['is_free_shipping']) {?>
							<b class="pc-tps free" title="<?php echo lang('label_nav_free_ship')?>">&#xe671;</b>
						<?php }?>
					</p>
					<img src="<?php echo $img_host,$item['goods_img']?>"  /></a>
				<dl class="tit">
				  <dd class="fl">
					<p><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" ><?php echo $item['goods_name']?></a></p>
					<p class="c-o fs-14"><?php echo $curCur_flag,number_format(format_price($item['shop_price'],$cur_rate),2);?></p>
				  </dd>
				  <dt class="fr">
					<img src="<?php echo $this->config->item('country_flag_path').$item['country_flag'].'.jpg'?>" width="18" height="13"/>
					<p class="c-bb"><?php  echo $origin_array[strtolower($item['country_flag'])]; ?></p>
				  </dt>
				</dl>
			  </div>
			</li>
			<?php }?>
		  </ul>
		</div>
		<?php if($his_count > 5) {?>
		<span class="prev"><i class="pc-tps">&#xe63f;</i></span>
		<span class="next"><i class="pc-tps">&#xe648;</i></span>
		<?php }?>
	  </div>           
	</div>
	<?php }?>
  </div>
<?php if ($supplier_qq) { ?>
  <div class="online_service">
	  <div class="headImg">
		 <img src="../<?=THEME?>/img/kefu_img.png"/>
	</div>
	<ul class="service_list">
	<?php foreach ($supplier_qq as $value) {
	?>
	  <li>
		<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?=$value?>&site=qq&menu=yes">
		  <img border="0" src="<?php echo base_url(THEME.'/gif/qq_pa.gif')?>" alt="<?=lang('role_customer_service')?>" title="<?=lang('role_customer_service')?>"/>
		  
		</a>
	 </li>
	<?php } ?>
	</ul>
  </div>
  <?php }?>
</main>
<div class="backToTop-up" title="Top"><i class="pc-tps">&#xe672;</i></div>
<script src="<?php echo base_url(THEME.'/js/jquery.lazyload.min.js')?>"></script> 
<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
<script>
  <?php if($goods_info['sn_list']) {?>
  var ColorSize =<?php echo $goods_info['sn_list'];?>;
  <?php }?>
  $(function() {
	jQuery(".tps-stars").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"left", vis:5, scroll:2, delayTime:500, autoPlay:true, autoPage:true, pnLoop:false, interTime:4000,});
	jQuery(".JS-tc").slide({ mainCell:"ul", autoPlay:true, effect:"left", vis:4, scroll:1, autoPage:true, pnLoop:false, interTime:6000, });
	jQuery(".mainCon").slide({ titCell:".goods-menu ul li", mainCell:".mian .bd ul", trigger:"click",});
	//继续购买
	$('.goumai').click(function() {
	  $(this).parents('.tjia').hide();
	});

	//立即购买
	$('.js-buynow,.js-incart,.js-incart-1').click(function(){
	  var $t=$(this),number=$('.js-number').val(),sku=$('.js-sku').text();
	  
	  if(number > 99) {
		layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
		return;
	  }
	  
	  //提示用户确认选择的商品
	  var $js_confirm = $('.js-confirm');
	  if(ColorSize.length > 1) {
		var color=$.trim($('.js-li-color.on').find('a').attr('title')),
		size=$.trim($('.js-li-size.on').find('a').text()),
		other=$.trim($('.js-li-other.on').find('a').text()),
		attr='';
		
		if(color != '') {attr += '<li><span class="c-99"><?php echo lang('label_color'),': '?></span>' + color + '<li />';}
		if(other != '') {attr += '<li><span class="c-99"><?php echo lang('label_other'),': '?></span>' + other + '<li />';}
		if(size != '') {attr += '<li><span class="c-99"><?php echo lang('label_size'),': '?></span>' + size + '<li />';}
		
		$('.js-attr').html(attr);
		
		$js_confirm.show();
		
		if($t.is('.js-incart') || $t.is('.js-incart-1')) {
		$js_confirm.find('.js-sel-ok').addClass('js-to-cart'); //标识是加购物车还是直接购买
		}
		
		if($t.is('.js-incart-1')) {
		$(window).scrollTop(200);
		}
	  }else {
		if($t.is('.js-buynow')) {
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('cart/check_goods_stock')?>",
				dataType: "json",
				data:{goods_sn:sku,quantity:number},
				success:function(data){
				  if(data.code == 0) {
					$('.js-buy-now').submit();
				  }else if (data.code == 1039) {
					layer.msg('<?php echo lang('label_outofstock');?>');
				  }
		
				}
			}); 
		}else {
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('cart/do_add_to_cart')?>",
				dataType: "json",
				data:{goods_sn:sku,quantity:number},
				success:function(data){
				  if(data.code == 0) {
					$('.js-check.tjia').show();
					if($t.is('.js-incart-1')) {
					  layer.msg('<?php echo lang('info_add_cart')?>');
					}else {
					  $('.js-check.tjia').show();
					}
		
					var $cart=$('.ci-count'),
					  cart_num=parseInt($cart.text());
					  console.dir(data);
					  try{
						  if(undefined != data.quantity)
						  {
							  $cart.text(parseInt(cart_num)+parseInt(data.quantity));
						  }
					  }catch(e)
					  {
						  $cart.text(cart_num+1);
					  }
				  }else if (data.code == 1037) {
					layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
				  }else if (data.code == 1039) {
					layer.msg('<?php echo lang('label_outofstock');?>');
				  }else if (data.code == 1054) {
					  layer.msg('<?php echo lang('cart_items_over_limit');?>');
				  }
		
				}
			  });
		}
	  }   
	
	});
	//确定立即购买
	$('.js-sel-ok').click(function() {
		var $t=$(this),number=$('.js-number').val(),sku=$('.js-sku').text();
		
		if(!$t.hasClass('js-to-cart')) { //直接购买
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('cart/check_goods_stock')?>",
				dataType: "json",
				data:{goods_sn:sku,quantity:number},
				success:function(data){
				  if(data.code == 0) {
					$('.js-buy-now').submit();
				  }else if (data.code == 1039) {
					layer.msg('<?php echo lang('label_outofstock');?>');
				  }
		
				}
			}); 
		}else { //加入购物车
			$('.js-attr').html('');
			$('.js-confirm').hide();
			$('.js-sel-ok').removeClass('js-to-cart');
		
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('cart/do_add_to_cart')?>",
				dataType: "json",
				data:{goods_sn:sku,quantity:number},
				success:function(data){
				  if(data.code == 0) {
					$('.js-check.tjia').show();
					/*if($t.is('.js-incart-1')) {
					  layer.msg('<?php echo lang('info_add_cart')?>');
					}else {
					  $('.js-check.tjia').show();
					}*/
		
					var $cart=$('.ci-count'),
					  cart_num=parseInt($cart.text());
					try{
						if(undefined != data.quantity)
						{
							$cart.text(parseInt(cart_num)+parseInt(data.quantity));
						}
					}catch(e)
					{
						$cart.text(cart_num+1);
					}

				  }else if (data.code == 1037) {
					layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
				  }else if (data.code == 1039) {
					layer.msg('<?php echo lang('label_outofstock');?>');
				  }else if (data.code == 1054) {
					  layer.msg('<?php echo lang('cart_items_over_limit');?>');
				  }
				}
			  });
		}
	  
	});
	//取消立即购买
	$('.js-sel-cancel').click(function() {
		$('.js-attr').html('');
		$('.js-confirm').hide();
		$('.js-sel-ok').removeClass('js-to-cart');
	});

	//点击颜色尺寸
	$('.js-color').click(function() { 
	  var parent= $(this).parent();
	  if(parent.hasClass('disable')) {
		return;
	  }
	  
	  var attr_color=$.trim($(this).attr('title')),
		attr_size=$.trim($('.js-li-size.on').find('.js-size').attr('title')),
		attr_other=$.trim($('.js-li-other.on').find('.js-other').attr('title')),
		sn='';

	  if(!attr_size) {
		attr_size='';
	  }

	  if(!attr_other) {
		attr_other='';
	  }

	  $.each(ColorSize,function(i,v) {      
		if(v.color == attr_color ) {
		  sn=v.sn;
		}
		/*if(v.color == attr_color && v.size == attr_size && v.other==attr_other) {
		  sn=v.sn;
		}*/
	  });

	  if(sn) {
		window.location.href='<?php echo base_url(),'index/product?snm='.$goods_info['goods_sn_main'],'&sn=';?>'+sn;
	  }else {
		$('.goods-cart-c').html('<span style="color:red;float: left;padding-top: 15px;"><?php echo lang('label_outofstock')?></span>');
	  }
	});

	$('.js-size').click(function() {
	  var parent= $(this).parent();
	  if(parent.hasClass('disable')) {
		return;
	  }
	  
	  var attr_size=$.trim($(this).attr('title')),
		attr_color=$.trim($('.js-li-color.on').find('.js-color').attr('title')),
		attr_other=$.trim($('.js-li-other.on').find('.js-other').attr('title')),
		sn='';

	  if(!attr_color) {
		attr_color='';
	  }

	  if(!attr_other) {
		attr_other='';
	  }

	  $.each(ColorSize,function(i,v) {      
		if(v.color == attr_color && v.size == attr_size && v.other==attr_other) {
		  sn=v.sn;
		} 
	  });

	  if(sn) {
		window.location.href='<?php echo base_url(),'index/product?snm='.$goods_info['goods_sn_main'],'&sn=';?>'+sn;
	  }else {
		$('.goods-cart-c').html('<span style="color:red;float: left;padding-top: 15px;"><?php echo lang('label_outofstock')?></span>');
	  }
	});

	$('.js-other').click(function() {
	  var parent= $(this).parent();
	  if(parent.hasClass('disable')) {
		return;
	  }
	  
	  var attr_other=$.trim($(this).attr('title')),
		attr_color=$.trim($('.js-li-color.on').find('.js-color').attr('title')),
		attr_size=$.trim($('.js-li-size.on').find('.js-size').attr('title')),
		sn='';    

	  if(!attr_color) {
		attr_color='';
	  }

	  if(!attr_size) {
		attr_size='';
	  }

	  $.each(ColorSize,function(i,v) {    
		if(v.color == attr_color && v.size == attr_size && $.trim(unescape(v.other.replace(/\\/g,'%')))==attr_other) {
		  sn=v.sn;
		}
	  });

	  if ('' == sn) {
		$.each(ColorSize,function(i,v) {    
		  if (attr_color  != ''  && attr_other != '' && v.color == attr_color && $.trim(unescape(v.other.replace(/\\/g,'%')))==attr_other) {
			sn=v.sn;
		  } else if (attr_color  == ''  && attr_other != ''  && $.trim(unescape(v.other.replace(/\\/g,'%')))==attr_other) {
			sn=v.sn;
		  } else if (attr_color  != ''  && attr_other == '' && v.color == attr_color ) {
			sn=v.sn;
		  }
		});
	  }
	  if(sn) {
		window.location.href='<?php echo base_url(),'index/product?snm='.$goods_info['goods_sn_main'],'&sn=';?>'+sn;
	  }else {
		$('.goods-cart-c').html('<span style="color:red;float: left;padding-top: 15px;"><?php echo lang('label_outofstock')?></span>');
	  }
	});
	
	//检查缺货sn
	/*$.each(ColorSize,function(i,v) { 
		var clean_other= $.trim(unescape(v.other.replace(/\\/g,'%')));
		if(v.number == 0) {
			if(v.color != '' && v.size == '' && clean_other == '') {
				$('.js-li-color').find('a[title="'+v.color+'"]').parent().addClass('disable');
			}else if(v.color == '' && v.size != '' && clean_other == '') {
				$('.js-size:contains("'+v.size+'")').parent().addClass('disable');
			}else if(v.color == '' && v.size == '' && clean_other != '') {
				$('.js-other:contains("'+clean_other+'")').parent().addClass('disable');
			}else {
				if(v.color != '' && v.size == '' && clean_other != '') {
					var sel_color=$.trim($('.js-li-color.on').find('a').attr('title'));
					$.each(ColorSize,function(ii,vv) { 
						var customer= $.trim(unescape(vv.other.replace(/\\/g,'%')));
						if($.trim(vv.color) == sel_color && vv.number == 0) {
							$('.js-other:contains("'+customer+'")').parent().addClass('disable');
							return false;
						}
					});
				}else if(v.color != '' && v.size != '' && clean_other == '') {
					var sel_color=$.trim($('.js-li-color.on').find('a').attr('title'));
					$.each(ColorSize,function(ii,vv) { 
						if($.trim(vv.color) == sel_color && vv.number == 0) {
							$('.js-size:contains("'+vv.size+'")').parent().addClass('disable');
							return false;
						}
					});
				}else if(v.color == '' && v.size != '' && clean_other != '') {
					var sel_other=$.trim($('.js-li-other.on').find('a').attr('title'));
					$.each(ColorSize,function(ii,vv) { 
						if($.trim(vv.other) == sel_other && vv.number == 0) {
							$('.js-size:contains("'+vv.size+'")').parent().addClass('disable');
							return false;
						}
					});
				}
			}
		}
	 });*/

	 $.each(ColorSize,
		function(i, v) {
			var clean_other = $.trim(unescape(v.other.replace(/\\/g, '%')));
			if (v.number == 0) {
				if (v.color != '' && v.size == '' && clean_other == '') {
					$('.js-li-color').find('a[title="' + v.color + '"]').parent().addClass('disable');
				} else if (v.color == '' && v.size != '' && clean_other == '') {
				   // $('.js-size:contains("' + v.size + '")').parent().addClass('disable');
					$('.js-size[title="' + v.size + '"]').parent().addClass('disable');
				} else if (v.color == '' && v.size == '' && clean_other != '') {
				   //$('.js-other:contains("' + clean_other + '")').parent().addClass('disable');
				   $('.js-other[title="' + clean_other + '"]').parent().addClass('disable');
				} else {
					if (v.color != '' && v.size == '' && clean_other != '') {
						var sel_color = $.trim($('.js-li-color.on').find('a').attr('title'));
						$.each(ColorSize,
						function(ii, vv) {
							var customer = $.trim(unescape(vv.other.replace(/\\/g, '%')));
							if ($.trim(vv.color) == sel_color && vv.number == 0) {
								//$('.js-other:contains("' + customer + '")').parent().addClass('disable');
								$('.js-other[title="' + customer + '"]').parent().addClass('disable');
								return false;
							}
						});
					} else if (v.color != '' && v.size != '' && clean_other == '') {
						var sel_color = $.trim($('.js-li-color.on').find('a').attr('title'));
						$.each(ColorSize,
						function(ii, vv) {
							if ($.trim(vv.color) == sel_color && vv.number == 0) {
							   // $('.js-size:contains("' + vv.size + '")').parent().addClass('disable');
								$('.js-size[title="' + vv.size + '"]').parent().addClass('disable');
								return false;
							}
						});
					} else if (v.color == '' && v.size != '' && clean_other != '') {
						var sel_other = $.trim($('.js-li-other.on').find('a').attr('title'));
						$.each(ColorSize,
						function(ii, vv) {
							if ($.trim(vv.other) == sel_other && vv.number == 0) {
								//$('.js-size:contains("' + vv.size + '")').parent().addClass('disable');
								$('.js-size[title="' + vv.size + '"]').parent().addClass('disable');
								return false;
							}
						});
					}
				}
			}
		});

	//增加数量
	$('.js-s1').click(function(event) {
	  event.stopPropagation();   

	  var $num = $('.js-number'),
		num=parseInt($num.val()),
		new_num=num,
		$t=$(this),   
		stock=parseInt($('.js-lanse').text()),
		$s2=$('.js-s2');

	  new_num ++ ;
	  if(new_num > stock) {
		new_num =stock;
	  }

	  if(new_num == stock) {
		$t.css({'opacity':0.5,'cursor':'default'});

	  }else {
		$t.css({'opacity':1,'cursor':'pointer'});
	  }

	  if(new_num == 1) {
		$s2.css({'opacity':0.5,'cursor':'default'});
	  }else {
		$s2.css({'opacity':1,'cursor':'pointer'});
	  }

	  if(new_num > stock) {
		return;
	  }

	  $num.val(new_num);  
	});

	//降低数量
	$('.js-s2').click(function(event) {
	  event.stopPropagation();    

	  var $num = $('.js-number'),
		num=parseInt($num.val()),
		new_num=num,
		$t=$(this),
		stock=parseInt($('.js-lanse').text()),
		$s1=$('.js-s1');

	  new_num -- ;
	  if(new_num <= 0) {
		new_num=1;      
	  }

	  if(new_num == 1) {
		$t.css({'opacity':0.5,'cursor':'default'});
	  }else {
		$t.css({'opacity':1,'cursor':'pointer'});
	  }

	  if(new_num == stock) {
		$s1.css({'opacity':0.5,'cursor':'default'});      
	  }else {
		$s1.css({'opacity':1,'cursor':'pointer'});
	  }

	  if(new_num == stock) {
		return;
	  }

	  $num.val(new_num);
	});

	//数量检查
	$('.js-number').blur(function(){
	  var $t=$(this),num=parseInt($t.val());
	  if(isNaN(num) || num < 0) {
		$t.val(1);
	  }else {
		$t.val(num);
	  }
	});

	//关注
	$('.js-like').click(function() {
	  var $t=$(this),goods_id=$t.attr('data-id'),
		goods_sn=$t.attr('data-sn');
	  /*if(is_login) {*/
	  $.get('<?php echo base_url(),'index/add_wish'?>',{goods_id:goods_id,goods_sn:goods_sn},function(data) {
		if($.trim(data.success) == 1) {

		  layer.msg('<?php echo lang('info_add_wish_success')?>');

		  $t.addClass('action');

		  var $wish_num_header=$('.wish_num').eq(0);
		  var num=parseInt($wish_num_header.text());
		  $wish_num_header.text(num+1);

		  var $wish_num_goods=$('.wish_num').eq(1);
		  var num1=parseInt($wish_num_goods.text());
		  $wish_num_goods.text(num1+1);
		}else if($.trim(data.success) == 0 ) {
		  if(data.url){
			location.href= data.url;
		  }else{
			layer.msg('<?php echo lang('info_add_wish_failed')?>');
		  }
		}
	  },'json');
	  /*} else {
	  window.location.href='<?php echo base_url(),'login'?>';
	}*/
	});

	//加载评论
	var current_page=0,
	  goods_sn_main='<?php echo $goods_sn_main?>';
	$('.load_comment').click(function() {
	  var $t=$(this),   
		$comment_list=$('.rev_list dl');

	  current_page = current_page+1;

	  $comment_list.append('<dd class="loading_small" style="margin:10px 0;height:15px"></dd>');

	  $.getJSON("<?php echo base_url('index/get_comments_page')?>",{page:current_page,goods_sn_main:goods_sn_main}, function(data){
		$comment_list.find('.loading_small').remove();

		if(data != '') {
		  var html='';
		  $.each(data, function(i,v){
			html+= '<dd ';
			if(i % 2 != 0) {
			  html+= 'class="hui">';
			}else {
			  html+= '>';
			}
			html+= '<span>'+v.com_user+'</span> <span class="hui_999">'+v.add_date+'</span> <span><s class="star_bg1"><s class="star1" style="width:'+v.com_score+'%"></s></s></span> </dd>';       

		  });

		  $comment_list.append(html);
		}else {      
		  $t.find('span').unwrap();
		}     

	  });
	});

	//计时
	var runs = 0;
	uutime($("#time_coutDown"),runs);
  });

  function uutime($obj,runs) {
	var _handle = setInterval(function(){
	  var zongshi = parseInt($obj.attr("data-time"))*1000-runs*1000;
	  if(zongshi>=0){
		var dantian = parseInt(Math.floor(zongshi/(1000*60*60*24)));
		var danshi=parseInt(Math.floor((zongshi-dantian*24*60*60*1000)/(1000*60*60)));
		if(danshi<10){danshi="0"+danshi;}
		var danfen=Math.floor(zongshi/(1000*60)) % 60;
		if(danfen<10){danfen="0"+danfen;}
		var danmiao=Math.floor(zongshi/1000) % 60;
		if(danmiao<10){danmiao="0"+danmiao;}
		$obj.html(dantian+' <?php echo lang('label_flag_day')?> '+danshi+":"+danfen+":"+danmiao);
	  }else{
		clearInterval(_handle); 
	  }
	  runs++;
	},1000);
  }
</script>