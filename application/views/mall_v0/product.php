<!--Page contents-->
<style type="text/css">
.cp_detail h4 { white-space: nowrap; text-overflow: ellipsis; -o-text-overflow: ellipsis; overflow: hidden; }
</style>
<div class="bj_zhuti" style="padding-bottom:20px">
  <div class="container clear">
    <div class="crumbs ellipsis"><img src="<?php echo base_url(THEME.'/img/1.gif')?>"> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a> <?php echo $nav_title?>&gt; <?php echo $goods_info['goods_name']?> </div>
    <div class="detail clear">
      <div class="title clear">
        <div class="col-md-9 clear">
          <h1 class="ellipsis"><?php echo $goods_info['goods_name']?></h1>
          <ul>
            <li> <?php echo lang('label_sku')?>: <span class="font_13 js-sku"><?php echo $goods_sn?></span></li>
            <li>
              <div class="star_bg">
                <div class="star " style="width:<?php echo ($goods_info['comment_star_avg']/5)*100,'%';?>"></div>
              </div>
              <div class="Reviews"><b><?php echo $goods_info['comment_star_avg'] * 20,'%';?></b>(<span class="se_1e5589"><?php echo $goods_info['comment_count'];?></span><?php echo lang('label_com')?>)</div>
            </li>
            <!--li>
            <div class="tub"><i class="xtb"></i><span>Share with Friends</span></div>
          </li-->
          </ul>
        </div>
        <div class="col-md-3 clear">
          <div class="guoqi"><s class="s1" style=" background-image:url(<?php echo $this->config->item('country_flag_path').$goods_info['country_flag'].'.jpg'?>)"></s><span><?php echo lang('label_place'),': ', $origin_array[strtolower($goods_info['country_flag'])]?></span></div> 
        </div>
      </div>
      <div class="product-intro clear">
        <div class="col-md-5 clear">
          <div class="preview clear">
            <div class="imageMenu clear">
              <ul>
                <?php foreach($goods_info['img_list'] as $k=>$img) {?>
                <li class="<?php if($k == 0) {echo 'sma';}?>"> <a><img width="54" height="54" src="<?php echo base_url(),$img['thumb_img']?>" mid="<?php echo base_url(),$img['big_img'];?>" big="<?php echo base_url(),$img['big_img'];?>"></a> </li>
                <?php }?>
              </ul>
              <s href="" class="prev"></s><s href="" class="next"></s> </div>
            <div class="tb_pic loading_big">
            <span>
            <img  src="<?php echo base_url(),$goods_info['img_list'][0]['big_img']?>" alt="" rel="<?php echo base_url(),$goods_info['img_list'][0]['big_img']?>" class="jqzoom">
            </span>
            </div>
          </div>
        </div>
        <div class="col-md-7 clear">
          <div class="itemInfo">
          	<div class="sale_country">
            <p class="ts_g"><s></s><?php echo sprintf(lang('label_ship_country'),$goods_info['sale_country']);?></p>
            </div>
            <div style="color:red;font-size:13px;">
			<?php 
				if($goods_info['ship_note_type'] == 1) {
					echo sprintf(lang('label_shipping_note1'),$goods_info['ship_note_val']);
				}elseif($goods_info['ship_note_type'] == 2) {
					echo sprintf(lang('label_shipping_note2'),date('Y/m/d',$goods_info['ship_note_val']));
				}
			?>
            </div>
            <?php if(!empty($goods_info['goods_note'])) {?>
            <div style="color:red;font-size:13px;"><?php echo $goods_info['goods_note'];?></div>
            <?php }?>
            <dl class="clear">
              <dt><?php echo lang('label_price')?>:&nbsp;</dt>
              <dd class="fcs-panel">
                <div></div>
                <span class="cese"><b class="sys_item_price"><?php echo $curCur_flag,format_price($goods_info['shop_price'],$cur_rate);?></b></span> <span><s><?php echo $curCur_flag,format_price($goods_info['market_price'],$cur_rate);?></s></span> <!--span class="cese">(<?php echo (1-number_format($goods_info['shop_price'] / $goods_info['market_price'],2,'.','')) * 100,'%';?> OFF)</span--> </dd>
            </dl>
           	<!--
			<?php if($goods_info['doba_ship_cost'] > 0) {?>
            <dl class="clear">
              <dt><?php echo lang('ship_cost_fee')?></dt>
              <dd>+<?php  echo $curCur_flag.format_price($goods_info['doba_ship_cost'],$cur_rate);?></dd>
            </dl>
            <?php }?>
            <?php if($goods_info['doba_drop_ship_fee'] > 0) {?>
            <dl class="clear">
              <dt><?php echo lang('drop_ship_fee')?></dt>
              <dd>+<?php  echo $curCur_flag.format_price($goods_info['doba_drop_ship_fee'],$cur_rate);?></dd>
            </dl>
            <?php }?>      
            -->
            <?php if(!empty($goods_info['color_list'])) {?>
            <dl class="sys_item_specpara clear" data-sid="1">
              <dt class="spec_img"><?php echo lang('label_color')?>:&nbsp; </dt>
              <dd>
                <ul class="sys_spec_img">
                  <?php foreach($goods_info['color_list'] as $color=>$img) {?>
                  <li  class="js-li-color<?php if(isset($goods_info['color']) && $goods_info['color'] == $color) {?> selected <?php }?>"> <a href="javascript:;" class="js-color" title="<?php echo $color?>"><img  width="50" height="50" src="<?php echo base_url().$img?>"></a> <i class="xuanzhong"></i> </li>
                  <?php }?>
                </ul>
              </dd>
            </dl>
            <?php }?>
            <?php if(!empty($goods_info['size_list'])) {?>
            <dl class="sys_item_specpara clear" data-sid="2">
              <dt class="spec_text"><?php echo lang('label_size')?>:&nbsp;</dt>
              <dd>
                <ul class="sys_spec_text">
                  <?php foreach($goods_info['size_list'] as $size) {?>
                  <li class="js-li-size<?php if(isset($goods_info['size']) && $goods_info['size'] == $size) {?> selected <?php }?>"> <a href="javascript:;" class="js-size" title="<?php echo $size?>"><?php echo $size?></a> <i class="xuanzhong"></i> </li>
                  <?php }?>
                </ul>
              </dd>
            </dl>
            <?php }?>
            
            <?php if(!empty($goods_info['other_list'])) {?>
            <dl class="sys_item_specpara clear" data-sid="2">
              <dt class="spec_text"><?php echo lang('label_other')?>:&nbsp;</dt>
              <dd>
                <ul class="sys_spec_text">
                  <?php foreach($goods_info['other_list'] as $other) {?>
                  <li class="js-li-other<?php if(isset($goods_info['other']) && $goods_info['other'] == $other) {?> selected <?php }?>"> <a href="javascript:;" class="js-other" title="<?php echo $other?>"><?php echo $other?></a> <i class="xuanzhong"></i> </li>
                  <?php }?>
                </ul>
              </dd>
            </dl>
            <?php }?>
            <?php if(!empty($gift_list)) {?>
            <div class="bj-f1 clear">
              <span><?php echo lang('label_gift')?></span>
              <ul>
              	<?php foreach($gift_list as $goods) {?>
              <li><a target="_blank" title="<?php echo $goods['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><img width="30" height="30" src="<?php echo base_url().$goods['goods_img']?>" /><em>×<?php echo $goods['num'];?></em></a></li>
                <?php }?>
              </ul>
            </div>
            <s class="xian"></s>
            <?php }?>
            <!--dl class="clear">
              <dt>Ship:</dt>
              <dd>US Warehouse</dd>
            </dl-->
            <?php if(!empty($goods_info['seller_note'])) {?>
            <div class="shming clear" > <?php echo nl2br($goods_info['seller_note']);?> </div>
            <?php }?>
            <dl class="clear">
              <dt><?php echo lang('label_qty')?>:&nbsp;</dt>
              <dd>
                <div class="huangou_sl">
                  <form action="<?php echo base_url(),'order/product_checkout'?>" method="get" class="js-buy-now">
                  <input type="hidden" value="<?php echo $goods_sn?>" name="goods_sn" />
                  <input type="text" value="1" name="quantity" class="js-number" />
                  </form>
                  <div class="de_in"><s class="s1 js-s1"></s><s class="s2 js-s2"></s></div>
                </div>
                <span class="hui_999">(<?php echo lang('label_stock')?> <em class="lanse js-lanse"><?php echo $goods_info['goods_number']?></em> )</span> </dd>
            </dl>
            <!--p class="lanse clear">Dispatch: Ships within 5-10 business days.</p-->
            <div class="anniu"> 
            <?php if($goods_info['is_on_sale'] == 1) {?>
            	 <?php if($goods_info['goods_number'] == 0) {?>
                 <span style="color:red"><?php echo lang('label_outofstock')?></span>
                 <?php }else {?>
                 <a href="javascript:;" class="goumai js-buynow" ><?php echo lang('label_buy_new')?></a> 
                 <a href="javascript:;" class="incart js-incart"><i></i><?php echo lang('label_add_to_cart')?></a> 
                 <?php }?>
            <?php }else {?>
            	<span style="color:red"><?php echo lang('info_buy_invalid')?></span>
            <?php }?>
            <a data-sn="<?php echo $goods_info['goods_sn_main']?>" data-id="<?php echo $goods_info['goods_id'];?>" id="love_img" href="javascript:;" class="xh"><s class="xh"></s><?php echo sprintf(lang('label_wish_goods'),$wish_goods_count);?></a> 
            </div>
            <div class="tjia clear" style="display:none;">
              <p><s></s><?php echo lang('info_add_cart')?></p>
              <p><a href="<?php echo base_url(),'cart'?>" class="incart"><?php echo lang('label_check_out')?></a><a href="javascript:;" class="goumai"><?php echo lang('label_contine_buy')?></a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if(!empty($goods_group_info)) {?>
  <div class="container clear">
    <div class="huangouqu xuan_tc clear">
      <h3><?php echo lang('label_goods_group_info')?></h3>
      <div class="body clear">
        <div class="col-md-10 clear">
          <div class="JS_p">
            <ul>
              <?php foreach($goods_group_info['list'] as $g_item) {?>
              <li class="col-md-3" style="float: left; width: 240px;"> <b class="jiah">+</b>
                <dl class="marg_10 clear">
                  <dt class="loading_small"><a target="_blank" href="<?php echo base_url(),'index/product?snm=',$g_item['info']['goods_sn_main'];?>"><img src="<?php echo base_url(),$g_item['info']['goods_img']?>"  width="92" height="82"></a></dt>
                  <dd><a target="_blank" class="tit" href="<?php echo base_url(),'index/product?snm=',$g_item['info']['goods_sn_main'];?>"><?php echo $g_item['info']['goods_name']?></a>
                    <p><b><?php echo $curCur_flag,format_price($g_item['info']['shop_price'],$cur_rate);?> * <?php echo $g_item['num']?></b></p>
                  </dd>
                </dl>
              </li>
              <?php }?>
            </ul>
            <a class="prev"></a> <a class="next"></a> </div>
        </div>
        <div class="col-md-2">
          <div class="rig"> <b class="dengh">=</b>
            <p><?php echo lang('label_goods_group_price')?><em><?php echo $curCur_flag,format_price($goods_info['shop_price'],$cur_rate);?></em></p>
            <p><?php echo lang('label_goods_group_save')?><em><?php echo $curCur_flag,format_price($goods_group_info['total']-$goods_info['shop_price'],$cur_rate);?></em></p>
            <p><?php echo lang('label_goods_group_num')?><em><?php echo $goods_group_info['number'];?></em></p>
            <a href="javascript:;" class="btn_hong goumai js-buynow"><?php echo lang('label_buy_new')?></a> </div>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <div class="container clear">
    <div class="main_wrap clear" style="padding:2%; margin-top:15px; background:#fff;">
      <div class="col-md-9 clear" style="position:relative;top:37px; width:80%">
        <ul class="Switch clear">
          <li class="qh_selected"><a href="javascript:;"><?php echo lang('label_desc')?></a></li>
          <li><div><a href="javascript:;"><span><?php echo lang('label_goods_com')?></span><s class="star_bg"><s class="star " style="width:<?php echo ($goods_info['comment_star_avg']/5)*100,'%';?>"></s></s></a></div></li>
          <!-- li><a href="javascript:;"><?php echo lang('label_goods_shipinfo')?></a></li>
          <li><a href="javascript:;"><?php echo lang('label_goods_warranty')?></a></li-->
        </ul>
        <ul class="contents_li">
          <li class="sel">
            <p style="font-size:14px;"> <?php echo nl2br(htmlspecialchars_decode($goods_info['extends']['goods_desc']));?> </p>
            <div style="margin-top:10px;">
              <?php foreach($goods_info['detail_img_list'] as $img) {?>
              <img src="<?php echo base_url(),'/themes/mall/img/lazyload.gif'?>" data-original="<?php echo base_url().$img['image_url']?>" style="margin-top:-4px; max-width:100%;" /><br>
              <?php }?>
              <!--无详情图片就显示相册图片-->
              <?php if(empty($goods_info['detail_img_list']) && !empty($goods_info['img_list'])) {?>
				  <?php foreach($goods_info['img_list'] as $k=>$img) {?>
                	<img src="<?php echo base_url(),'/themes/mall/img/lazyload.gif'?>" data-original="<?php echo base_url().$img['big_img']?>" style="margin-top:-4px;width:60%; height:60%;" /><br>
                 <?php }?>
             <?php }?>
            </div>
          </li>
          <li>
            <div class=" clear">
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
                <p class="huit">
                <?php if($goods_info['comment_count'] > 20) {?>
                	<a class="load_comment" href="javascript:;"><span><?php echo $goods_info['comment_count']?> <?php echo lang('label_com')?></span></a>
                <?php }else {?>
                	<?php echo $goods_info['comment_count']?> <?php echo lang('label_com')?>
                <?php }?>
                </p>
              </div>
            </div>
          </li>
          <li> dfdfdfdf </li>
          <li> fdfdfdf </li>
        </ul>
      </div>
      <div class="col-md-3 clear" style="margin-left:2%; width:18%;">
        <?php if(!empty($recomment_goods)) {?>
        <div class="Related">
          <h3><?php echo lang('label_goods_recomment') ?></h3>
          <?php foreach($recomment_goods as $goods){?>
          <div class="Drive clear">
            <div class="img_box loading_small"><a title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><img src="<?php echo base_url(),'/themes/mall/img/lazyload.gif'?>"  data-original="<?php echo base_url(),$goods['goods_img'];?>"></a></div>
            <div class="cp_detail">
              <h4><a title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><?php echo $goods['goods_name'];?></a></h4>
              <p class="title"><span class="pink"><?php echo $curCur_flag,format_price($goods['shop_price'],$cur_rate);?></span>/<s><?php echo $curCur_flag,format_price($goods['market_price'],$cur_rate);?></s></p>
              <div class="xingxing clear">
                <div class="star_bg">
                  <div class="star " style="width:<?php echo ($goods['comment_star_avg']/5)*100,'%';?>"></div>
                </div>
                <div class="Reviews">(<span class="pink"><?php echo $goods['comment_count'];?></span> <?php echo lang('label_com')?>)</div>
              </div>
            </div>
          </div>
          <?php }?>
        </div>
        <?php }?>
        <div class="Related">
          <h3><?php echo lang('label_goods_history')?></h3>
          <?php foreach($history_goods as $goods){?>
          <div class="Drive clear">
            <div class="img_box loading_small"><a title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><img  src="<?php echo base_url(),'/themes/mall/img/lazyload.gif'?>"  data-original="<?php echo base_url(),$goods['goods_img'];?>"></a></div>
            <div class="cp_detail">
              <h4><a title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><?php echo $goods['goods_name'];?></a></h4>
              <p class="title"><span class="pink"><?php echo $curCur_flag,format_price($goods['shop_price'],$cur_rate);?></span>/<s><?php echo $curCur_flag,format_price($goods['market_price'],$cur_rate);?></s></p>
              <div class="xingxing clear">
                <div class="star_bg">
                  <div class="star " style="width:<?php echo ($goods['comment_star_avg']/5)*100,'%';?>"></div>
                </div>
                <div class="Reviews">(<span class="pink"><?php echo $goods['comment_count'];?></span> <?php echo lang('label_com')?>)</div>
              </div>
            </div>
          </div>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url(THEME.'/js/jquery.lazyload.min.js')?>"></script> 
<script src="<?php echo base_url(THEME.'/js/main.js')?>"></script> 
<script src="<?php echo base_url(THEME.'/js/xiangqing.js')?>"></script> 
<script>
<?php if($goods_info['sn_list']) {?>
var ColorSize =<?php echo $goods_info['sn_list'];?>;
<?php }?>
$(function() {
	$(".JS_p").slide({ mainCell:"ul", autoPlay:true, effect:"left", vis:4, scroll:1, autoPage:true, pnLoop:false, interTime:20000, });
	$(".imageMenu").slide({ mainCell:"ul", autoPlay:true, effect:"top", vis:6, scroll:1, autoPage:true, pnLoop:false, interTime:150000, });
	//继续购买
	$('.goumai').click(function() {
		$(this).parents('.tjia').hide();
	});
	
	//立即购买
	$('.js-buynow').click(function(){
		if($('.js-number').val() > 99) {
			layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
			return;
		}
		$('.js-buy-now').submit();
	});
	
	$('.Switch > li').click(function() {
		var $t=$(this),index=$t.index();
		$t.addClass('qh_selected').siblings().removeClass('qh_selected');
		$('.contents_li > li').eq(index).addClass('sel').siblings().removeClass('sel');
	});
	
	//点击颜色尺寸
	$('.js-color').click(function() {
		var attr_color=$.trim($(this).attr('title')),
		attr_size=$.trim($('.js-li-size.selected').find('.js-size').attr('title')),
		attr_other=$.trim($('.js-li-other.selected').find('.js-other').attr('title')),
		sn='';
		
		if(!attr_size) {
			attr_size='';
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
		}
	});
	$('.js-size').click(function() {
		var attr_size=$.trim($(this).attr('title')),
		attr_color=$.trim($('.js-li-color.selected').find('.js-color').attr('title')),
		attr_other=$.trim($('.js-li-other.selected').find('.js-other').attr('title')),
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
		}
	});
	
	$('.js-other').click(function() {
		var attr_other=$.trim($(this).attr('title')),
		attr_color=$.trim($('.js-li-color.selected').find('.js-color').attr('title')),
		attr_size=$.trim($('.js-li-size.selected').find('.js-size').attr('title')),
		sn='';
		
		if(!attr_color) {
			attr_color='';
		}
		
		if(!attr_size) {
			attr_size='';
		}
		
		$.each(ColorSize,function(i,v) {			
			if(v.color == attr_color && v.size == attr_size && v.other==attr_other) {
				sn=v.sn;
			}
		});

		if(sn) {
			window.location.href='<?php echo base_url(),'index/product?snm='.$goods_info['goods_sn_main'],'&sn=';?>'+sn;
		}
	});
	
	//添加购物车
	$('.js-incart').click(function() {
		var number=$('.js-number').val(),sku=$('.js-sku').text();
		
		$.ajax({
		  type: "POST",
		  url: "<?php echo base_url('cart/do_add_to_cart')?>",
		  dataType: "json",
		  data:{goods_sn:sku,quantity:number},
		  success:function(data){
		  	if(data.code == 0) {
				$('.tjia').show();
				var $cart_num=$('.ci-count'),
				cart_num=parseInt($cart_num.text());
				$cart_num.text(cart_num + parseInt(number));
			}else if (data.code == 1037) {
				layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
			}else if (data.code == 1054) {
                layer.msg('<?php echo lang('cart_items_over_limit');?>');
            }
		  }
		});
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
		if(isNaN(num)) {
			$t.val(1);
		}else {
			$t.val(num);
		}
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
});
</script> 
<!--Page contents-->