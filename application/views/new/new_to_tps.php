<script type="text/javascript" src="<?php echo base_url("js/new/jquery_2.01.js?v=2"); ?>"></script>
<div class="w1160 clear">
  <!-- 焦点图 S -->
  
  <div class="focusBox clear"> <b><?php echo lang('new_to_title');?></b>
    <div class="hd clear">
      <ul>
        <li>1</li>
      </ul>
    </div>
    <div class="bd clear">
      <ul>
        <li>
          <p><span> 1 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/1.jpg")?>"> </li>
        <li>
          <p><?php echo lang('new_to_2');?><span> 2 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/2.jpg")?>">
          <p><strong><?php echo lang('new_to_2_1');?></strong></p>
        </li>
        <li>
          <p><span> 3 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/3.jpg")?>">
          <p><strong></strong></p>
        </li>
        <li>
          <p><span> 4 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/4.jpg")?>">
          <p><strong><?php echo lang('new_to_4_1');?></strong></p>
        </li>
        <li>
          <p><?php echo lang('new_to_5');?><span> 5 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/5.jpg")?>"></li>
        <li>
          <p><span> 6 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/6.jpg")?>"></li>
        <li>
          <p><?php echo lang('new_to_8');?><span> 7 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/7.jpg")?>"></li>
        <li>
          <p><span> 8 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/8.jpg")?>"></li>
        <li>
          <p><?php echo lang('new_to_9');?><span> 9 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/9.jpg")?>"></li>
        <li>
          <p><?php echo lang('new_to_10');?><span> 10 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/10.jpg")?>"></li>
        <li>
          <p><?php echo lang('new_to_11');?><span> 11 /11</span></p>
          <img src="<?php echo base_url("img/new/$curLanguage/11.jpg")?>">
          <p><strong><?php echo lang('new_to_11_1');?></strong></p>
        </li>
      </ul>
    </div>
    <a class="prev"></a> <a class="next"></a> </div>
</div>
<?php //} ?>
<script>
jQuery(".focusBox").slide({ titCell:".hd ul", mainCell:".bd ul",autoPlay:true,delayTime:400,effect:"left", /*vis:1, scroll:1,*/ autoPage:true, trigger:"click", pnLoop:false });
</script> 
<!-- 焦点图 E --> 
