<?php 
/**
 * 加拿大馆
 */
?>
<div class="main-content g-world g-canada">
    <s class="ggao canada"></s>
    <div class="w1200">

        <?php if(!empty($global_mall_ads_list)){ ?>
        <div class="lunbo">
            <ul>
                <?php
                    foreach($global_mall_ads_list as $key => $value){
                        //2017-07-07 leon  修改图片地址为当前登陆人的店铺ID
                        // if($store_id != 0){
                        //     $action_val = $value['action_val'];
                        //     $action_val_array = explode('www',$action_val);
                        //     $action_val_url = $action_val_array[0].$store_id.$action_val_array[1];
                        // }else{
                        //     $action_val_url = $value['action_val'];
                        // }
                ?>
                    <li>
                        <a href="<?php echo $value['action_val']; ?>" target="_blank">
                            <img src="<?php echo $img_host.$value['ad_img'];?>" alt="">
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php if(count($global_mall_ads_list) > 5){?>
            <span class="prev"><i class="pc-tps">&#xe63f;</i></span>
            <span class="next"><i class="pc-tps">&#xe648;</i></span>
            <?php } ?>
        </div>
        <?php } ?>
        
        <?php if(!empty($new_goods) && count($new_goods) >= 4){ ?>
            <div class="tps-line">
                <div class="tc pr">
                    <s class="s1 canada_icon"></s>
                    <b class="d-ib"><?php echo lang('is_new'); ?></b>
                    <a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/global_category?sn=',$is_guo_info,'&type=is_new';?>" target="_blank">
                        <?php echo lang('more')?> >
                    </a>
                </div>
                <ul class="line clear">
                    <?php foreach($new_goods as $k=>$goods) { ?>
                        <?php if((++$k % 4) == 0){ ?>
                            <li class="detail-box mr-n">
                        <?php }else{ ?>
                            <li class="detail-box">
                        <?php } ?>
                        <a href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
                            <div class="img-box">
                                <img src="<?php echo $img_host,$goods['goods_img']?>" />
                            </div>
                            <div class="offer-detail">
                                <h3>
                                    <?php echo $goods['goods_name'];?>
                                </h3>
                                <p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
                                <p>
                                    <?php echo $origin_array[strtolower($goods['country_flag'])];?>
                                    <img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>">
                                    
                                    
                                    <?php //if($goods['country_flag'] != 'tw'  && $goods['country_flag']) {?>
									<!-- <img src="<?php //echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" /> -->
                                    <?php //}?>
                                </p>
                            </div>
                        </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
        
        <?php if(!empty($hot_goods) && count($hot_goods) >= 4){ ?>
            <div class="tps-line">
                <div class="tc pr">
                    <s class="s2 canada_icon"></s>
                    <b class="d-ib"><?php echo lang('is_hot'); ?></b>
                    <a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/global_category?sn=',$is_guo_info,'&type=is_hot';?>" target="_blank">
                        <?php echo lang('more')?> >
                    </a>
                </div>
                <ul class="line clear">
                    <?php foreach($hot_goods as $k=>$goods) { ?>
                        <?php if((++$k % 4) == 0){ ?>
                            <li class="detail-box mr-n">
                        <?php }else{ ?>
                            <li class="detail-box">
                        <?php } ?>
                        <a href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
                            <div class="img-box">
                                <img src="<?php echo $img_host,$goods['goods_img']?>" />
                            </div>
                            <div class="offer-detail">
                                <h3>
                                    <?php echo $goods['goods_name'];?>
                                </h3>
                                <p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
                                <p>
                                    <?php echo $origin_array[strtolower($goods['country_flag'])];?>
                                    <img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>">
                                </p>
                            </div>
                        </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
        
        <?php if(!empty($promote_goods) && count($promote_goods) >= 4){ ?>
            <div class="tps-line">
                <div class="tc pr">
                    <s class="s3 canada_icon"></s>
                    <b class="d-ib"><?php echo lang('label_nav_promote'); ?></b>
                    <a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/global_category?sn=',$is_guo_info,'&type=is_promote';?>" target="_blank">
                        <?php echo lang('more')?> >
                    </a>
                </div>
                <ul class="line clear">
                    <?php foreach($promote_goods as $k=>$goods) { ?>
                        <?php if((++$k % 4) == 0){ ?>
                            <li class="detail-box mr-n">
                        <?php }else{ ?>
                            <li class="detail-box">
                        <?php } ?>
                        <a href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
                            <div class="img-box">
                                <img src="<?php echo $img_host,$goods['goods_img']?>" />
                            </div>
                            <div class="offer-detail">
                                <h3>
                                    <?php echo $goods['goods_name'];?>
                                </h3>
                                <p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
                                <p>
                                    <?php echo $origin_array[strtolower($goods['country_flag'])];?>
                                    <img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>">
                                </p>
                            </div>
                        </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
        
        <?php if(!empty($global_goods)){ ?>
            <div class="tps-line">
                <div class="tc pr">
                    <s class="s1 canada_icon"></s>
                    <b class="d-ib"><?php echo lang('cart_title'); ?></b>
                    <a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/global_category?sn=',$is_guo_info;?>" target="_blank">
                        <?php echo lang('more')?> >
                    </a>
                </div>
                <ul class="line clear">
                    <?php foreach($global_goods as $k=>$goods) { ?>
                        <?php if((++$k % 4) == 0){ ?>
                            <li class="detail-box mr-n">
                        <?php }else{ ?>
                            <li class="detail-box">
                        <?php } ?>
                        <a href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
                            <div class="img-box">
                                <img src="<?php echo $img_host,$goods['goods_img']?>" />
                            </div>
                            <div class="offer-detail">
                                <h3>
                                    <?php echo $goods['goods_name'];?>
                                </h3>
                                <p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
                                <p>
                                    <?php echo $origin_array[strtolower($goods['country_flag'])];?>
                                    <img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>">
                                </p>
                            </div>
                        </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
        
        <div class="tps-line pr">
            <div class="tc pr"><s class="s4 canada_icon"></s><b class="d-ib"><?php echo lang('tps_global'); ?></b></div>
            <i class="pc-tps qqg"></i>
            <ul class="tps-wrap canada-wrap clear">
                <li class="btn-2"><a href="<?php echo base_url(),'index/global_shopping?elevent=korea'?>" target="_self"></a></li>
                <li class="btn-3"><a href="<?php echo base_url(),'index/global_shopping?elevent=gat'?>" target="_self"></a></li>
                <li class="btn-5"><a href="<?php echo base_url(),'index/global_shopping?elevent=europe'?>" target="_self"></a></li>
                <li class="btn-6"><a href="<?php echo base_url(),'index/global_shopping?elevent=australia'?>" target="_self"></a></li>
                <li class="btn-7"><a href="<?php echo base_url(),'index/global_shopping?elevent=southeast-asia'?>" target="_self"></a></li>
            </ul>
        </div>
    </div>
</div>

<script>
   jQuery(".lunbo").slide({ mainCell:"ul", effect:"left", vis:5, scroll:1, delayTime:500, autoPlay:true, autoPage:true, interTime: 3000, trigger:"click"});
</script>

