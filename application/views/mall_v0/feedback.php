<!--Page contents-->
<div class="bj_zhuti">
  <div class="container clear">
    <div class="crumbs"><img src="<?php echo base_url(THEME.'/img/1.gif')?>" /> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a>  &gt;<?php echo $artical['parent_name'];?>  <?php if(isset($artical['artical'])){ echo ' &gt; ',$artical['artical']['title'];}?></div>
    <div class="help clear">
      <div class="col-md-3 clear">
        <div class="sideBox clear" >
          <!--h1><?php echo lang('label_help');?></h1-->
          
          <?php foreach($artical as $k=>$art) {
					if(is_numeric($k))	{
		  ?>
          <h3 class="<?php if($art['type_id'] == $artical['parent_id']) echo 'on'; ?>"><em></em><?php echo $art['type_name']?></h3>
          <div class="bd" <?php if($art['type_id'] == $artical['parent_id']) echo 'style="display:block;"'; else echo 'style="display:none;"';?>>
          	<?php if($art['list']) {?>
            <ul>
              <?php foreach($art['list'] as $li) {?>
              <li  <?php if($artical['artical']['id'] == $li['id']) {?> class="sideB"<?php }?>><a href="<?php 
		  	if(in_array($li['id'],array(55,56,57))) {
				echo base_url(),'index/feedback';
			}else {
				echo base_url(),'index/help?aid=',$li['id'];
			}
		  ?>"><?php echo $li['title']?></a></li>
              <?php }?>
            </ul>
            <?php }?>
          </div>
  		  <?php }}?>
         
        </div>
      </div>
      <div class="col-md-9 clear">
        <div class="content">
          <h3><?php  echo isset($artical['artical']) ? $artical['artical']['title'] : '';?></h3>
          <div class="vouchers feedback">
          	<form class="js-feedback" action="" method="post">
          	<p><?php  echo isset($artical['artical']) ? $artical['artical']['html_content'] : '';?></p>
			<p><i><em>*</em><?php echo lang('label_email')?>：</i><input class="itxt js-email" placeholder=""></p>
			<p><i class="dw"><em>*</em><?php echo lang('label_feedback_content')?>：</i><textarea class="itxt js-content" placeholder=""></textarea></p>
			<p><i></i><input type="button" class="btn-Login" value="<?php echo lang('label_feedback_submit')?>"></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
	$(".sideBox").slide({ titCell:"h3", targetCell:".bd",effect:"slideDown",trigger:"click" });
	//$('.sideBox').find('.sideB').parents('.bd').prev().trigger('click');
	$('.btn-Login').click(function() {
		var email=$.trim($('.js-email').val()),
		content=$.trim($('.js-content').val());
		
		if(!/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(email)) {
			layer.msg('<?php echo lang('info_email')?>');
			return;
		}
		
		if(content == '') {
			layer.msg('<?php echo lang('info_content')?>');
			return;
		}
		
		$.post('<?php echo base_url()."index/feedback_do";?>',{email:email,content:content},function(data){
			if($.trim(data) == 'ok') {
				layer.msg('<?php echo lang('info_feedback_succ')?>');
				$('.js-feedback')[0].reset();
			}else {
				layer.msg('<?php echo lang('info_feedback_failed')?>');
			}
		});
	
	});
});
</script>
<!--Page contents end-->
