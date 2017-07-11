	<div class="main-footer">
		<div class="w1200">
			<div class="fl">
				<ul>
					<li>
                    <?php echo sprintf(lang('label_company_name_3'),date('Y'))?>
                    </li>
					<li>
						<p><?php echo lang('label_company_name_1')?></p>
						<p><?php echo lang('label_company_name_2')?></p>
					</li>
					<li>
                    <?php foreach($artical as $k=>$art) {
					if(is_numeric($k))	{
					?>
                    <a href="<?php echo base_url(),'index/help?aid=',$art['list'][0]['id'];?>"><i class="fa fa-angle-right"></i><?php echo $art['type_name']?></a>
                    <?php }
					}
					?>
                    </li>
				</ul>
			</div>
			<div class="fl ce-l">				
				<ul>
					<li><?php echo lang('label_company_name_contact')?></li>
					<li>
						<p>support@tps138.com</p>
                        <?php if($curLan == 'zh' || $curLan == 'hk') {?>
						<p>0755-33198568</p>
                        <?php }else {?>
                        <p>(1)323-395-2828</p>
                        <?php }?>
					</li>
					<li><a href=""><s title=""></s></a> <a href=""><s class="s1"></s></a> <a href=""><s class="s2"></s></a> <a href=""><s class="s3"></s></a></li>
				</ul>
			</div>

            <div class="fr">
				<img src="<?php echo base_url(THEME.'/img/weixin.jpg')?>" />
				<p><?php echo lang('label_company_name_download')?></p>
			</div>
		</div>
	</div>
    <script src="<?php echo base_url(THEME.'/js/main.js?v=20170614')?>"></script>
    <script src="<?php echo base_url(THEME.'/js/base.js')?>"></script> 
</body>
</html>