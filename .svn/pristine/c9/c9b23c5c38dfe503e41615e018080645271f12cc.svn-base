<div class="footer bj_zhuti">
  <div class="container clear">
    <div class="row top_15 clear">
      <div class="foot clear">
        <div class="col-md-2 clear">
          <div class="fenx"><a data-url="https://business.facebook.com/?business_id=800554346697342" href="javascript:;" class="jiathis_button_tsina"><s class="s1"></s><span><?php echo lang('label_s_facebook');?></span></a></div>
        </div>
        <div class="col-md-2 clear">
          <div class="fenx"><a data-url="https://twitter.com/tps188" href="javascript:;" class="jiathis_button_qzone"><s class="s2"></s><span><?php echo lang('label_s_twitter');?></span></a></div>
        </div>
        <div class="col-md-2 clear">
          <div class="fenx"><a data-url="https://www.pinterest.com/tpspartner/tpspartner/" href="javascript:;" class="jiathis_button_renren"><s class="s3"></s><span><?php echo lang('label_s_pin');?></span></a></div>
        </div>
        <div class="col-md-2 clear">
          <div class="fenx"> <a data-url="https://www.pinterest.com/tpspartner/tpspartner/" href="javascript:;" class="jiathis_button_renren"><s class="s3"></s><span><?php echo lang('label_s_pin');?></span></a>
            <div data-toggle="hidden-box" id="nav-box5" class="wx"> <img src="<?php echo base_url(THEME.'/img/weixin.jpg')?>" alt="关注微信"> </div>
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
  </div>
  <div class="container clear">
    <div class="row top_15 clear">
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
</div>
<script src="<?php echo base_url(THEME.'/js/SuperSlide2.1.js')?>"></script> 
<script src="<?php echo base_url(THEME.'/js/main.js')?>"></script>
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
</body></html>