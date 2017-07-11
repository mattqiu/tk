<div class="bj_zhuti clear">
  <div class="footer clear">
    <div class="container clear">
      <div class="col-md-12 clear">
        <?php if($store_id == '1380141986') {?>
        <img src="<?php echo base_url(THEME.'/img/footer_'.$curLan.'_'.$store_id.'.png')?>" class="img100">
        <?php }?>
      </div>
    </div>
  </div>
  <div class="container clear">
    <div class="foot clear">
      <div class="col-md-2 clear">
        <div class="fenx"><a data-url="https://business.facebook.com/?business_id=800554346697342" href="javascript:;" class="jiathis_button_tsina"><s class="s1"></s><span><?php echo lang('label_s_facebook');?></span></a></div>
      </div>
      <div class="col-md-2 clear">
        <div class="fenx"><a data-url="https://twitter.com/tps188" href="javascript:;" class="jiathis_button_qzone"><s class="s2"></s><span><?php echo lang('label_s_twitter');?></span></a></div>
      </div>
      <div class="col-md-2 clear">
        <div class="fenx"> <a data-url="https://www.pinterest.com/tpspartner/tpspartner/" href="javascript:;" class="jiathis_button_renren"><s class="s3"></s><span><?php echo lang('label_s_pin');?></span></a> </div>
      </div>
      <div class="col-md-2 clear">
        <div class="fenx"><a data-url="http://vk.com/id320500751" href="javascript:;" class="jiathis_button_weixin" data-toggle="arrowdown" id="arrow5"><s class="s4"></s><span><?php echo lang('label_s_blog');?></span></a>
          <div data-toggle="hidden-box" id="nav-box5" class="wx"> <img src="<?php echo base_url(THEME.'/img/weixin.jpg')?>" alt="关注TPS微信"> </div>
        </div>
      </div>
      <div class="col-md-2 clear">
        <div class="fenx"><a data-url="https://www.youtube.com/channel/UCOGvxJ5S77uvwyulQG8q3pA" href="javascript:;" class="jiathis_button_douban"><s class="s5"></s><span><?php echo lang('label_s_youtube');?></span></a></div>
      </div>
      <div class="col-md-2 clear">
        <div class="fenx"><a data-url="https://plus.google.com/u/0/104908318891511731306" href="javascript:;" class="jiathis_button_tqq"><s class="s6"></s><span><?php echo lang('label_s_google');?></span></a></div>
      </div>
    </div>
  </div>
  <div class="container clear">
    <div class="I_help clear">
      <?php foreach($artical as $k=>$art) {
					if(is_numeric($k))	{
		?>
      <div class="col-md-3 clear">
        <ul>
          <li class="links"><?php echo $art['type_name']?></li>
          <?php if($art['list']) {?>
          <?php foreach($art['list'] as $li) {?>
          <li><a href="<?php 
		  	if(in_array($li['id'],array(55,56,57))) {
				echo base_url(),'index/feedback';
			}else {
				echo base_url(),'index/help?aid=',$li['id'];
			}
		  ?>"><?php echo $li['title']?></a></li>
          <?php }?>
          <?php }?>
        </ul>
      </div>
      <?php }}?>
    </div>
    <p class="fukuan"><img src="<?php echo base_url(THEME.'/img/fukuan_new.png')?>"></p>
    <p class="copyright">
      <?php if($curLan <> 'english') {?>
      Copyright &copy;2014-<?php echo date('Y');?> TPS138.com 前海云集品 版权所有. 粤ICP备14072989号-2
      <?php }else {?>
      Copyright &copy;2014-<?php echo date('Y');?> TPS138.com. All rights reserved.
      <?php }?>
    </p>
  </div>
</div>

<!--返回顶部-->
<div class="backToTop-up" ><s></s></div>
<script src="<?php echo base_url(THEME.'/js/main.js?v=20170614')?>"></script>
<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
<?php if($curLanguage == 'english'){ ?>
<script>
$(function(){
    //社交分享
    $('.foot a').click(function() {
         window.open($(this).attr('data-url'));
    });
});
</script>
<?php }else{?>
<!-- JiaThis Button BEGIN --> 
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script> 
<!-- JiaThis Button END -->
<?php }?>
<script>
var is_login=<?php echo $is_login;?>;

//加入购物车和喜欢
$(function() {
	$('.js-addCart').click(function() {
		var $t=$(this),number=1,sku=$t.attr('data-sn');
		
		$.ajax({
		  type: "POST",
		  url: "<?php echo base_url('cart/do_add_to_cart')?>",
		  dataType: "json",
		  data:{goods_sn:sku,quantity:number},
		  success:function(data){
		  	if(data.code == 0) {
				//alert('<?php echo lang('info_add_cart')?>');
				add_cart_animate($t);
			}else if (data.code == 1037) {
				layer.msg('<?php echo lang('cart_quantity_over_limit');?>');
			}else if (data.code == 1039) {
				layer.msg('<?php echo lang('label_outofstock');?>');
			}else if (data.code == 1054) {
                layer.msg('<?php echo lang('cart_items_over_limit');?>');
            }

		  }
		});
	});
	
	/*$('.js-like').click(function() {
		var $t=$(this),goods_id=$t.attr('data-id'),action='';
		
		if($t.hasClass('action')) {
			action='minus';
		}else {
			action='add';
		}
		
		$.ajax({
		  type: "get",
		  url: "<?php echo base_url('index/set_like_num')?>",
		  dataType: "json",
		  data:{goods_id:goods_id,action:action},
		  success:function(data){
		  	if(data.err == 0) {
				if($t.hasClass('action')) {
					$t.removeClass('action');
				}else {
					$t.addClass('action');
				}
			}
		  }
		});
	});*/
	
	//关注
	$('.Collect,#love_img,.js-like').click(function() {
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

});
//添加购物车动画
function add_cart_animate(obj) {
	var $add_one=obj.parent().find('.add_one'),
	$cart=$('.ci-count'),
	cart_num=parseInt($cart.text());
	
	$add_one.css({'z-index':10}).animate({
		'font-size':'50px',
		'top':'-50px',
		'opacity':0
	},
	1000,
	function() {
		$add_one.css({
			'z-index':'-2',
			'font-size':'0',
			'top':'0',
			'opacity':1
		});
		$cart.text(cart_num+1);
		layer.msg('<?php echo lang('info_add_cart')?>');
	});
}
</script>
</body></html>