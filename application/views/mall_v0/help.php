<!--Page contents-->
<style>
.vouchers img{ max-width:800px; }
</style>
<div class="bj_zhuti">
  <div class="container clear">
    <div class="crumbs"><img src="<?php echo base_url(THEME.'/img/1.gif')?>" /> <a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a>  &gt;<?php if(isset($artical['parent_name'])) echo $artical['parent_name'];?>   <?php if(isset($artical['artical'])){ echo ' &gt; ',$artical['artical']['title'];}?></div>
    <div class="help clear">
      <div class="col-md-3 clear">
        <div class="sideBox clear" >
          <!--h1><?php echo lang('label_help');?></h1-->
          
          <?php foreach($artical as $k=>$art) {
					if(is_numeric($k))	{
		  ?>
          <h3 class="<?php if(isset($artical['parent_id']) && $art['type_id'] == $artical['parent_id']) echo 'on'; ?>"><em></em><?php echo $art['type_name']?></h3>
          <div class="bd" <?php if(isset($artical['parent_id']) && $art['type_id'] == $artical['parent_id']) echo 'style="display:block;"'; else echo 'style="display:none;"';?>>
          	<?php if($art['list']) {?>
            <ul>
              <?php foreach($art['list'] as $li) {?>
              <li  <?php if($artical['artical']['id'] == $li['id']) {?> class="sideB"<?php }?>><a href="
			<?php 
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
          <div class="vouchers">
            <?php  echo isset($artical['artical']) ? str_replace('{domain}',base_url(),str_replace('{language}',$curLan,$artical['artical']['html_content'])) : '';?>
          </div>
        </div>
      </div>
    </div>
  </div>
 
</div>
<script>
$(".sideBox").slide({ titCell:"h3", targetCell:".bd",effect:"slideDown",trigger:"click" });
//$('.sideBox').find('.sideB').parents('.bd').prev().trigger('click');
</script>
<!--Page contents end-->
