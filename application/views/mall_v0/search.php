<!--Page contents-->
<div class="bj_zhuti">
  <div class="container clear">
    <div class="row clear">
      <div class="crumbs clear"> <img src="<?php echo base_url(THEME.'/img/1.gif')?>"> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a> &gt; <?php echo $nav_title?> </div>
      <div class="navSearch top_15 clear">
        <div class="sm-breadcrumb"><?php echo sprintf(lang('label_cate_count_search'),$total_rows)?></div>
      </div>
      <div class="f-line">
        <div class="col-md-8">
          <div class="left"> <span><?php echo lang('label_cate_rank');?></span> <a class="<?php if($order == 'composite') echo 'sel';?>" href="<?php echo $cate_url,'&order=composite'?>"><?php echo lang('label_cate_com_rank');?></a> <a class="<?php if($order == 'sale') echo 'sel';?>" href="<?php echo $cate_url,'&order=sale'?>"><?php echo lang('label_cate_sale');?><s class="jiant jiant_h down"></s></a> <a class="<?php if($order == 'comments') echo 'sel';?>" href="<?php echo $cate_url,'&order=comments'?>"><?php echo lang('label_cate_comments');?><s class="jiant jiant_h down"></s></a> <a class="<?php if($order == 'price') echo 'sel';?>" href="<?php echo $cate_url,'&order=price&arr=',$arr?>"><?php echo lang('label_price');?><s class="jiant jiant_h <?php if($arr != 'down')echo 'down';?>"></s></a> </div>
        </div>
        <div class="col-md-4">
          <div class="right"> 
            <!--<?php echo lang('label_cate_view');?>:<a href=""><s class="liep"></s></a><a href=""><s class="pingp"></s></a>--> 
            <span class="fp-text"><?php echo lang('label_cate_page')?> <b class="cur_page"><?php echo $cur_page?></b><em>/</em><i><?php echo $total_page?></i></span><a href="<?php if($cur_page == 1){ echo 'javascript:;';} else {echo base_url(),$page_link,'&page=',$cur_page-1;}?>" class="fp-prev <?php if($cur_page == 1){ echo 'disabled';}?>">&lt;</a><a href="<?php if($cur_page == $total_page){ echo 'javascript:;';} else {echo base_url(),$page_link,'&page=',$cur_page+1;}?>" class="fp-next <?php if($cur_page == $total_page){ echo 'disabled';}?>">&gt;</a> </div>
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
          <div class="img_box v_box loading_small"><!--a href="javascript:;" data-id="<?php echo $item['goods_id'];?>" class="Collect"><s></s><?php echo lang('label_add_wish')?></a--><a title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><img  src="<?php echo base_url(),$item['goods_img']?> "></a></div>
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
    </div>
  </div>
</div>
<!--Page contents end--> 
