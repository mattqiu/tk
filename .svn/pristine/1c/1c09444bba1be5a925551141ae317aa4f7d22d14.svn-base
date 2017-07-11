<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/otherpages.css?v=2'); ?>" />
<div class="other_wrap">
    <div class="other_page">
        <div class="left_part">
            <div class="top_img"><a href="##"><img src="<?php echo base_url('img/new/news_bg.png');?>" alt="news"></a></div>
            <div class="m_text">
                <div class="news_locus1"><h4><?php echo lang('nav_index');?> > <?php echo lang('nav_new');?> > <?php echo lang('nav_new_latest');?></h4></div>
                <div class="news_text">
                    <div class="title"><h4><?php echo lang('nav_new_latest');?></h4></div>
                    <?php if ($list){ ?>
                        <?php foreach ($list as $item) { ?>
                            <div class="img_text" >
                                <div class="l_img"><a href="<?php echo base_url('news_detail/index/').'/'.$item['id'];?>"><img src="<?php echo base_url($item['img']);?>"></a></div>
                                <div class="r_text">
                                    <h5><span><a href="<?php echo base_url('news_detail/index/').'/'.$item['id'];?>"><?php echo $item['title']?></a></span><br>
                                        <img src="<?php echo base_url('img/new/clock1.png');?>"><span style="font-size:14px;color:#a9a9a9;font-weight:normal;"><?php echo $item['create_time']?></span></h5>
                                    <p><?php echo htmlspecialchars($item['content'])?></p>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }else{ ?>
                           Empty!
                    <?php } ?>
                </div>

            </div>
        </div>

        <div class="righ_part">
            <div class="pre_nex">
                <div class="prelink">
                    <a href="<?php echo base_url('news');?>"><img src="<?php echo base_url('img/new/icon1.png');?>" onmousemove="this.src='<?php echo base_url('img/new/icon11.png');?>'" onmouseout="this.src='<?php echo base_url('img/new/icon1.png');?>'"></a>
                    <div class="txtpre"><?php echo lang('nav_new_latest');?></div>
                </div>
                <div class="nextlink">
                    <a href="<?php echo base_url('news_official');?>" target="_self"><img src="<?php echo base_url('img/new/icon2.png');?>" onmousemove="this.src='<?php echo base_url('img/new/icon22.png');?>'" onmouseout="this.src='<?php echo base_url('img/new/icon2.png');?>'"></a>
                    <div class="txtnext"><?php echo lang('official_noti');?></div>
                </div>
            </div>
            <div class="title"><h3><?php echo lang('nav_new');?></h3></div>
            <div class="text1"><h3><a href="<?php echo base_url('news');?>" target="_self"><?php echo lang('nav_new_latest');?></a></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('news_official');?>" target="_self"><?php echo lang('official_noti');?></a></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('video');?>" target="_self"><?php echo lang('video');?></a></h3></div>
            <div class="text3"><h2><?php echo lang('hot_news');?></h2></div>
            <div class="text4">
                <?php if ($hots){ ?>
                    <?php foreach ($hots as $hot) { ?>
                        <h3><a href="<?php echo base_url('news_detail/index').'/'.$hot['id'];?>"><?php echo $hot['title']?></a><br>
                            <span style="font-size:12px;color:#a9a9a9;font-weight:normal;"><?php echo $hot['create_time']?></span></h3>
                        <p><?php echo htmlspecialchars($hot['content']);?></p>
                    <?php } ?>
                <?php }?>

            </div>
            <div class="text3"><h2><?php echo lang('hot_video');?></h2></div>
            <div class="text5">
                <a href="<?php echo base_url('video');?>"><img src="<?php echo base_url('img/new/news_video2.jpg');?>"></a>
            </div>

        </div>
    </div>
</div>

