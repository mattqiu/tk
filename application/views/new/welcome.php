		<!-- css3-mediaqueries.js for IE8 or older -->
        <!--[if lt IE 9]>
        <script src="<?php echo base_url('js/new/css3-mediaqueries.js'); ?>"></script>
        <![endif]-->
        
        <style type="text/css">
		@media screen and (max-width: 1200px) { 
		#opport .img_js .slider .slider-container .slider-wrapper .slide img{max-width:83%;border:0px;height:400px;float:left;}
		
		#thr_import .midd_part .mkeUl ul li img {max-width:80%;height:216px;float:left;position:relative;left:2px;}
		
		#thr_import .midd_part .mkeRbtn {background: url(../../../img/new/nr_btn.png) no-repeat; height: 98px; width: 36px;  position: absolute; top: 65px; right: 65px; cursor: pointer; z-index: 10;}
		
		#video .video_bottom .bot_img_txt .img1_txt .v_rtxt1{width:58%;height:120px;float:right;height:100%;}
		#video .video_bottom .bot_img_txt .img2_txt .v_rtxt2{width:58%;height:120px;float:right;height:100%;}
		#video .video_bottom .bot_img_txt .img3_txt .v_rtxt3{width:58%;height:120px;float:right;height:100%;}
		
		}
		</style>
         <script language="javascript">
			function init()
			{
				var isIE=!!window.ActiveXObject; 
				var isIE6=isIE&&!window.XMLHttpRequest; 
				var isIE8=isIE&&!!document.documentMode; 
				var isIE7=isIE&&!isIE6&&!isIE8; 
				if(isIE)
				{ 
					if((isIE6)||(isIE7))
					{
						alert("适合IE8及以上版本浏览器、360安全浏览器、Firefox浏览器、Google浏览器、Opera浏览器、Safari浏览器等浏览！");
						return false;
					}
				}
			}
			window.onload=init();
		</script>
<div id="pagewrap">
	<div id="opport">
    	 <div class="img_js">
            <div class="slider">
                 <div class="slider-container">
                    <div class="slider-wrapper">
                    
                      <div class="slide"> <a href="<?php echo base_url('why_tps');?>" ><img src="<?php echo base_url("img/new/$curLanguage/bigbanner-1.jpg");?>" ></a></div>
                        <div class="slide"> <a href="##" ><img src="<?php echo base_url("img/new/$curLanguage/bigbanner-5.jpg");?>"></a></div>
                        <div class="slide"> <a href="<?php echo base_url('vision');?>" ><img src="<?php echo base_url("img/new/$curLanguage/bigbanner-3.jpg");?>"></a></div>
                        <div class="slide"> <a href="<?php echo base_url('register');?>" ><img src="<?php echo base_url("img/new/$curLanguage/bigbanner-4.jpg");?>"></a></div>

                    </div>
                 </div>
            </div>
            <script src="<?php echo base_url('js/new/slider.js');?>"></script>
            <script type="text/javascript">
                (function() {
                    Slider.init({
                        target: $('.slider'),
                        time: 5000
                    });
                })();
            </script>
    	</div>
        
        <div class="rig_cont">
        	<h3><?php echo lang('nav_opportunity');?></h3>
            <h3 style="color:#274e87;margin-top:-5px;"><?php echo lang('why_tps');?> ?</h3>
            <div class="img_tools">
            	<ul>
                	<li><img src="<?php echo base_url('img/new/people3.png');?>"><br><?php echo lang('people');?></li>
                    <li style="margin:0px 0 20px 30px;"><img src="<?php echo base_url('img/new/prod2.png');?>"><br><?php echo lang('product');?></li>
                    <li style="margin:0px 0 20px 30px;"><img src="<?php echo base_url('img/new/process2.png');?>"><br><?php echo lang('process');?></li>
                </ul>
            </div>
            <div class="txt_tools">
            	<p><strong><?php echo lang('people');?> :&nbsp;</strong> <?php echo lang('people_content');?><br>
<strong><?php echo lang('product');?> :&nbsp;</strong><?php echo lang('product_content');?><br>
<strong><?php echo lang('process');?> :&nbsp;</strong><?php echo lang('process_content');?>  <a href="<?php echo base_url('why_tps')?>"><?php echo lang('learn_more');?> >></a></p>
            </div>
        
        </div>

    </div>
    
    <div class="thr_bg"></div>
    
    <div id="thr_import">
    	<div class="left_part" id="import_left" onmouseover="show('import_left')" onMouseOut="hide('import_left')">
        	<div class="timg"><img src="<?php echo base_url('img/new/thr01.jpg');?>"></div>
            <div class="import_txt" id="import_txt_id">
            	<h2><?php echo lang('cme_ade');?></h2>
                <ul>
                	<li> • <?php echo lang('cme_ade_1');?> </li>
                    <li> • <?php echo lang('cme_ade_2');?></li>
                    <li> • <?php echo lang('cme_ade_3');?></li>
                </ul>
                <p><a href="<?php echo base_url('advantage')?>"><strong><?php echo lang('learn_more');?> >></strong></a></p>
            </div>
        </div>
        
        <script type="text/javascript">			
			function show(obj){
			  var XS = document.getElementById(obj);
			  if(obj=='import_left')
			  {
			 	  XS.style.backgroundColor='#edeced';
			  }
			  else
			  {
				  XS.style.backgroundColor='#f8f8f8';
			  }
			  if(window.screen.width==1024)
			  {
				  XS.style.background="url('../../../img/new/rect_bg2.png') no-repeat";
			  }
			  else
			  {
			  	XS.style.background="url('../../../img/new/rect_bg.png') no-repeat";
			  }
			  XS.style.cursor='pointer';  
			}
			function hide(obj){
			  var YC = document.getElementById(obj);
			  YC.style.background="url('')";
			  if(obj=='import_left')
			  {
			 	  YC.style.backgroundColor='#ecebec';
			  }
			  else
			  {
				  YC.style.backgroundColor='#f8f8f8';
			  }  
			}
		</script>
        <div class="midd_part" id="import_midd" onmouseover="show('import_midd')" onMouseOut="hide('import_midd')">
            <div class="kePublic">
                <div class="mkeFocus">
                 <div class="mkeUl">
                  <ul>
                   <li><a href="<?php echo base_url('mall');?>" target="_blank"><img src="<?php echo base_url('img/new/thr02.jpg');?>"/></a></li>
                   <li><a href="<?php echo base_url('mall');?>" target="_blank"><img src="<?php echo base_url("img/new/$curLanguage/th002_1.jpg");?>"/></a></li>
                  </ul>
                  <div class="mkeLbtn"></div>
                  <div class="mkeRbtn"></div>
                 </div>
                </div>
                <script language="javascript">
                var rnum=$(".mkeUl ul li").size();
                var cnum=0;
                $(".mke_ns2").html(rnum);
                $(".mkeUl ul").width(rnum*410);
                $(".mkeRbtn").click(function(){
                    cnum++;
                    if(cnum>(rnum-1)){
                    cnum=0	
                    }
                    $(".mkeUl ul").animate({"left":-cnum*410},300);
                    $(".mke_ns1").html(cnum+1);
                });
                $(".mkeLbtn").click(function(){
                    cnum--;
                    if(cnum<0){
                    cnum=rnum-1;	
                    }
                    $(".mkeUl ul").animate({"left":-cnum*410},300);
                    $(".mke_ns1").html(cnum+1);
                });
                
                function autoPlay(){
                    cnum++;
                    if(cnum>(rnum-1)){
                    cnum=0	
                    }
                    $(".mkeUl ul").animate({"left":-cnum*410},300);
                    $(".mke_ns1").html(cnum+1);
                }
                var Timer=setInterval(autoPlay,3000);
                $(".mkeFocus").hover(function(){clearInterval(Timer)},function(){Timer=setInterval(autoPlay,3000);});
                </script>
              </div>
            <div class="import_txt1">
            	<h2><?php echo lang('e_platform');?></h2>
                <div class="inner_txt"><?php echo lang('e_platform_content');?></div>
                <p style="margin-top:10px;"><a href="<?php echo base_url('mall');?>"><strong><?php echo lang('enter_platform');?></strong></a></p>
            </div>
        </div>
        <div class="righ_part" id="import_righ" onmouseover="show('import_righ')" onMouseOut="hide('import_righ')">
        	<div class="timg2"><img src="<?php echo base_url('img/new/thr03.png');?>"></div>
            <div class="import_txt2">
            	<h2><?php echo lang('cmn_plan');?></h2>
                <div class="inner_txt1">
                    <?php echo lang('cmn_plan_title');?>
                • <?php echo lang('cmn_plan_1_1');?>
                </div>
                <p><a href="<?php echo base_url('plan')?>"><strong><?php echo lang('learn_more');?> >></strong></a></p>
            </div>
        </div>        
    </div>
    
    <div class="thr_bg" style="margin-top:2px;"></div>
    
    <div id="video">
    	<div class="video_top">
            <div class="left_video"><a href="<?php echo base_url('video');?>"><img src="<?php echo base_url("img/new/$curLanguage/video.jpg");?>"></a></div>
            <div class="righ_video">
                <h2><?php echo lang('nav_new');?></h2>

                <div class="img_txt">
                    <?php if($union[0]['id']){?>
                    <div class="limg"><a href="<?php echo base_url('news_detail/index').'/'.$union[0]['id'];?>"><img src="<?php echo base_url($union[0]['img']);?>"></a></div>
                    <div class="rtxt">
                        <h5><a href="<?php echo base_url('news_detail/index').'/'.$union[0]['id'];?>"><?php echo $union[0]['title']?></a></h5>
                        <div class="time"><span><img src="<?php echo base_url('img/new/clock.png');?>"></span><?php echo $union[0]['create_time']?></div>
                        <p><a href="<?php echo base_url('news_detail/index').'/'.$union[0]['id'];?>"><?php echo $union[0]['content']?></a></p>
                    </div>
                    <?php }?>
                    <div class="new_txt">
                        <ul>
                            <?php if ($data){ ?>
                                <?php foreach ($data as $item) { ?>
                                    <li><a href="<?php echo base_url('news_detail/index').'/'.$item['id'];?>"><?php echo $item['title']?></a></li>
                                <?php } ?>
                            <?php }?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="video_bottom">
        	<h2><?php echo lang('nav_about_company'); ?></h2>
            <div class="bot_img_txt">
            	<div class="img1_txt">
                	<div class="v_limg1">
                    	<a href="<?php echo base_url('about')?>">
                        <img src="<?php echo base_url('img/new/video_bot1.jpg');?>" onMouseMove="this.src='<?php echo base_url('img/new/video_bot11.jpg');?>'" onMouseOut="this.src='<?php echo base_url('img/new/video_bot1.jpg');?>'">
                        </a>
                    </div>
                    <div class="v_rtxt1">
                    	<h4><strong><?php echo lang('who_are'); ?></strong></h4>
                        <p><?php echo lang('who_are_content'); ?><br><strong><a href="<?php echo base_url('about')?>"><?php echo lang('learn_more'); ?></a></strong></p>
                    </div>
                </div>
                <div class="img2_txt">
                	<div class="v_limg2">
                    	<a href="<?php echo base_url('vision')?>">
                        	<img src="<?php echo base_url('img/new/video_bot2.jpg');?>" onMouseMove="this.src='<?php echo base_url('img/new/video_bot22.jpg');?>'" onMouseOut="this.src='<?php echo base_url('img/new/video_bot2.jpg');?>'">
                        </a>
                    </div>
                    <div class="v_rtxt2">
                    	<h4><strong><?php echo lang('our_vision'); ?></strong></h4>
                        <p><?php echo lang('our_vision_content'); ?><br><strong><a href="<?php echo base_url('vision')?>"><?php echo lang('learn_more'); ?></a></strong></p>
                    </div>
                </div>
                <div class="img3_txt">
                	<div class="v_limg3">
                    	<a href="<?php echo base_url('team')?>">
                        	<img src="<?php echo base_url('img/new/video_bot3.jpg');?>" onMouseMove="this.src='<?php echo base_url('img/new/video_bot33.jpg');?>'" onMouseOut="this.src='<?php echo base_url('img/new/video_bot3.jpg');?>'">
                        </a>
                    </div>
                    <div class="v_rtxt3">
                    	<h4><strong><?php echo lang('mana_team'); ?></strong></h4>
                        <p><?php echo lang('mana_team_content'); ?><br><strong><a href="<?php echo base_url('team')?>"><?php echo lang('learn_more'); ?></a></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .new_txt{
        clear: both;
        padding-top: 15px;
    }
    .new_txt ul li{
        float: none;
    }
    .bot_img_txt h4{
        text-transform: uppercase;
    }
</style>

