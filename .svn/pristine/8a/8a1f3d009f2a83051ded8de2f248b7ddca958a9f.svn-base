<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/otherpages.css?v=2'); ?>" />
<style>
    .other_page .left_part{
        margin-top: 5px;
    }
</style>
<div class="other_wrap">
    <div class="other_page">
        <div class="left_part">
            <div class="m_text">
                <div class="news_locus"><h4><a href="/"><?php echo lang('nav_index');?></a> > <a href="<?php echo base_url('news')?>"><?php echo lang('nav_new');?></a> > <a href="<?php echo base_url('news')?>"><?php echo lang('nav_new_latest');?> </a> > <span style="color:#6184b8;"><?php echo $info['title']?></span></h4></div>
                <div class="news_text">
                    <div class="title" style="margin-top:30px;"><h4><?php echo $info['title']?></h4></div>
                    <div class="video">
                        <p><span style="position:relative;top:2px;"><img src="<?php echo base_url('img/new/clock1.png');?>"></span>
                            <span style="font-size:14px;"><?php echo $info['create_time']?></span>
                            <span style="margin-left:30px;"><?php echo lang('source')?><?php echo $info['source']?></span></p>
                    </div>
                    <div class="v_text">
                        <div class="clearfix contdiv">
                            <?php echo $info['html_content']?>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="righ_part" >

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