<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/otherpages.css?v=2'); ?>" />
<style>
    .other_page .left_part{
        margin-top: 5px;
    }
	.video_cir{width:100%!important;width:700px;height:500px;}
</style>
<div class="other_wrap">
    <div class="other_page">
        <div class="left_part">
            <div class="m_text">
                <div class="news_locus"><h4><?php echo lang('nav_index');?> > <?php echo lang('nav_new');?> > <?php echo lang('video');?> > <span style="color:#6184b8;"> <?php echo lang('why_tps')?>?</span></h4></div>
                <div class="news_text1">
                    <div class="title1" style="margin-top:30px;"><h4><?php echo lang('why_tps')?>?</h4></div>
                    <div class="img_text1">
                        <?php if($curLanguage == 'zh' || $curLanguage == 'hk'){
                            $video = base_url('front/Chinese.mp4');
							$video2 = base_url('front/Chinese.swf');
                        }else{
                            $video = base_url('front/english.mp4');
							$video2 = base_url('front/english.swf');
                        }?>

                        <div class="my_video"></div>
                        <video class="video_cir" controls> 
                           <!-- Firefox --> 
                           <source src="mv.ogg" type="video/ogg" />  
                           <!-- Safari/Chrome-->   
                           <source src="<?php echo $video?>" type="video/mp4" />
                           <!-- 如果浏览器不支持video标签，则使用flash -->  
                           <embed src="<?php echo $video2?>" type="application/x-shockwave-flash" class="video_cir" allowscriptaccess="always" allowfullscreen="true"> </embed>  
                        </video> 

                       <!-- <script>
                            $(function(){
                                var str= '';
                                var str1 = '' ;

                                str += '<video width="100%" height="519px" controls="controls">';
                                str += '<source src="<?php echo $video;?>" type="video/mp4"></source>';
                                str += 'your browser does not support the video tag </video>';


                                str1 += '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="100%" height="519px">';
                                str1 += '<param name="autoStart" value="false" /><param name="URL" value="<?php echo $video;?>" /><param name="allowFullScreen" value="true" />';
                                //str1 += '<embed autostart="true" src="<?php echo $video;?>" type="video/x-ms-wmv" width="100%" height="519px" controls="ImageWindow" console="cons">';
								str1 += '<embed autostart="true" src="http://www.tps138.berton1/front/Chinese.swf" type="video/x-ms-wmv" width="100%" height="519px" controls="ImageWindow" console="cons">';
                                str1 += ' </embed></object>';


                                    if($.browser.msie) {
                                        $(".my_video").html(str1);
                                    }
                                    else if (!!window.ActiveXObject || "ActiveXObject" in window){
                                        $(".my_video").html(str1);
                                    }
                                    else if($.browser.safari)
                                    {
                                        $(".my_video").html(str);
                                    }
                                    else if($.browser.mozilla)
                                    {
                                        $(".my_video").html(str);
                                    }
                                    else if($.browser.opera){
                                        $(".my_video").html(str);
                                    }
                                    else if(navigator.userAgent.toLowerCase().match(/chrome/) != null) {
                                        $(".my_video").html(str);
                                    }
                                    else {
                                        $(".my_video").html(str1);
                                    }
                            });

                        </script>-->
                        <p><span style="position:relative;top:2px;"><img src="<?php echo base_url('img/new/clock1.png');?>"></span>
                            <span style="font-size:14px;">6:01 PM ET, Wed March 4, 2015</span>
                            <span style="margin-left:30px;">From: TPS Video</span></p>
                    </div>
                    <div class="v_text">
                        <p><?php echo lang('team_1')?></p>
                        <p></p>
                        <p><?php echo lang('cmn_plan_4')?></p>
                    </div>

                </div>

            </div>
        </div>

        <div class="righ_part">
            <div class="title"><h3><?php echo lang('nav_new');?></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('news');?>" target="_self"><?php echo lang('nav_new_latest');?></a></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('news_official');?>" target="_self"><?php echo lang('official_noti');?></a></h3></div>
            <div class="text1"><h3><a href="<?php echo base_url('video');?>" target="_self"><?php echo lang('video');?></a></h3></div>
            <div class="text3"><h2><?php echo lang('hot_news');?></h2></div>
            <div class="text4">
                <h3><a href="<?php echo base_url('news_detail/english/5');?>">The Top 5 Ecommerce Trends...</a><br><span style="font-size:12px;color:#a9a9a9;font-weight:normal;">12:32 PM ET, Wed March 4, 2015</span></h3>
                <p>This year could be huge: Improving the customer experience is key.</p>
                <h3><a href="<?php echo base_url('news_detail/english/6');?>">Report: Amazon to Shutter...</a><br><span style="font-size:12px;color:#a9a9a9;font-weight:normal;"> 6:01 PM ET, Wed March 4, 2015</span></h3>
                <p>Users of Amazon Webstore have received letters from the company stating...</p>
            </div>
            <div class="text3"><h2><?php echo lang('hot_video');?></h2></div>
            <div class="text5">
                <a href="<?php echo base_url('video');?>"><img src="<?php echo base_url('img/new/news_video2.jpg');?>"></a>
            </div>

        </div>
    </div>
</div>

