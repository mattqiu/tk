<!--Page contents-->
<div class="bj_zhuti">
  <div class="container banner taozhuang clear">
    <div class="row clear">
      <div class="bd clear">
        <ul>
          <li style="height:280px" class="loading_big"><img width="1200" height="280" src="<?php echo base_url(THEME.'/img/'.$curLan.'_goods_g.jpg')?>"/></li>

        </ul>
      </div>
      <!--div class="hd clear">
        <ul>
          <li></li>
        </ul>
      </div-->
    </div>
    
  </div>
  <div class="container clear">
    <div class="row clear">
      <div class="crumbs clear">
        <img src="<?php echo base_url(THEME.'/img/1.gif')?>"> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a> &gt; <?php echo $nav_title?>
      </div> 

      <div class="f-line">
        <div class="col-md-8">
         <div class="left">
          <h2><?php echo lang('label_cate_group');?></h2>
         </div>
        </div>
        <div class="col-md-4">
          <div class="right">
            <!--<?php echo lang('label_cate_view');?>:<a href=""><s class="liep"></s></a><a href=""><s class="pingp"></s></a>-->
            <span class="fp-text"><?php echo lang('label_cate_page')?> <b class="cur_page"><?php echo $cur_page?></b><em>/</em><i><?php echo $total_page?></i></span><a href="<?php if($cur_page == 1){ echo 'javascript:;';} else {echo base_url(),$page_link,'&page=',$cur_page-1;}?>" class="fp-prev <?php if($cur_page == 1){ echo 'disabled';}?>">&lt;</a><a href="<?php if($cur_page == $total_page){ echo 'javascript:;';} else {echo base_url(),$page_link,'&page=',$cur_page+1;}?>" class="fp-next <?php if($cur_page == $total_page || $total_page == 0){ echo 'disabled';}?>">&gt;</a>
          </div>
        </div>
      </div> 
    </div>  
  </div>
  <div class="container clear">
    <div class="row top_15 clear">
      <?php if($goods) {?>
  	  <?php foreach($goods as $k=>$item) {?>
      <div class="grid_1_of_5 <?php if(($k+1)%5 == 1){?>no_left<?php }?> clear">
        <div class="Drive Search clear">
          <div class="img_box v_box loading_small"><!--a href="javascript:;" data-id="<?php echo $item['goods_id'];?>" class="Collect"><s></s><?php echo lang('label_add_wish')?></a--><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><img src="<?php echo base_url(),'/themes/mall/img/lazyload.gif'?>" data-original="<?php echo base_url(),$item['goods_img']?> "></a></div>
          <div class="cp_detail clear">
            <h4><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><?php echo $item['goods_name']?></a></h4>
            <div class="xingxing clear">
               <div id="starBg" class="star_bg"> <div class="star"  style="width:<?php echo ($item['comment_star_avg']/5)*100,'%';?>"></div> </div> 
               <div class="Reviews">(<span class="pink"><?php echo $item['comment_count'];?></span> <?php echo lang('label_com')?>)</div>
            </div>
            <div class="jiage clear">
              <p class="title"><span class="pink"><?php echo $curCur_flag,format_price($item['shop_price'],$cur_rate);?></span>/<s><?php echo $curCur_flag,format_price($item['market_price'],$cur_rate);?></s></p>
              <p class="btn_box"><a href="javascript:;" data-sn="<?php echo $item['goods_sn_main'],'-1';?>" class="gwc  js-addCart"></a><a href="javascript:;" data-sn="<?php echo $item['goods_sn_main']?>" data-id="<?php echo $item['goods_id'];?>" class="xh js-like"></a><span class="add_one">+1</span></p>
            </div>
          </div>
        </div>
      </div>
      <?php }?>
      <?php }else{?>
      <p style="text-align:center; height:300px;"> <?php echo lang('label_cate_no_records')?></p>  
      <?php }?>
      <div class="col-md-12 center clear">
          <?php echo $pager;?>
      </div
    ></div>
  </div>   
  
  <div class="container clear">
  	<div class="f-line">
        <div class="col-md-8">
         <div class="left">
          <h2><?php echo lang('label_cate_group_goods');?></h2>
         </div>
        </div>
        
      </div>
    <div class="row top_15 clear">
      <?php if($goods_all_list) {?>
  	  <?php foreach($goods_all_list as $k=>$item) {?>
      <div class="grid_1_of_5 <?php if(($k+1)%5 == 1){?>no_left<?php }?> clear">
        <div class="Drive Search clear">
          <div class="img_box v_box loading_small"><!--a href="javascript:;" data-id="<?php echo $item['goods_id'];?>" class="Collect"><s></s><?php echo lang('label_add_wish')?></a--><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><img src="<?php echo base_url(),'/themes/mall/img/lazyload.gif'?>" data-original="<?php echo base_url(),$item['goods_img']?> "></a></div>
          <div class="cp_detail clear">
            <h4><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><?php echo $item['goods_name']?></a></h4>
            <div class="xingxing clear">
               <div id="starBg" class="star_bg"> <div class="star"  style="width:<?php echo ($item['comment_star_avg']/5)*100,'%';?>"></div> </div> 
               <div class="Reviews">(<span class="pink"><?php echo $item['comment_count'];?></span> <?php echo lang('label_com')?>)</div>
            </div>
            <div class="jiage clear">
              <p class="title"><span class="pink"><?php echo $curCur_flag,format_price($item['shop_price'],$cur_rate);?></span>/<s><?php echo $curCur_flag,format_price($item['market_price'],$cur_rate);?></s></p>
              <p class="btn_box"><a href="javascript:;" data-sn="<?php echo $item['goods_sn_main'],'-1';?>" class="gwc  js-addCart"></a><a href="javascript:;" data-sn="<?php echo $item['goods_sn_main']?>" data-id="<?php echo $item['goods_id'];?>" class="xh js-like"></a><span class="add_one">+1</span></p>
            </div>
          </div>
        </div>
      </div>
      <?php }?>
      <?php }else{?>
      <p style="text-align:center; height:300px;"> <?php echo lang('label_cate_no_records')?></p>  
      <?php }?>
      <div class="col-md-12 top_15 clear">
        <div class="Spagination clear">
          <?php echo $pager;?>
        </div>
      </div> 
    </div>
  </div>

</div>
<script src="<?php echo base_url(THEME.'/js/jquery.lazyload.min.js')?>"></script> 
<!--script>
$(function() {
	$(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold", autoPlay:true, autoPage:true, trigger:"click",}); 
});
</script-->
<!--Page contents end-->

