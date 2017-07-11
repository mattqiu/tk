	<div class="main-footer">
		<div class="w1200">
			<div class="fl">
				<ul>
					<li id='tico_artical'>
                    <?php foreach($artical as $k=>$art) {
					if(is_numeric($k) && $art['list'])	{
					?>
                    <a href="<?php echo base_url(),'index/help?aid=',$art['list'][0]['id'];?>"><i class="fa fa-angle-right"></i><?php echo $art['type_name']?></a>
                    <?php }
					}
					?>
                    </li>
                    <li>
						<!-- <p><?php //echo lang('label_company_name_1')?></p>-->
						<!-- <p><?php //echo lang('label_company_name_2')?></p>-->
						<!--  <img src="<?php //echo base_url(THEME.'/img/LOGO_1.png')?>" />  -->
						<a target="_blank" href="http://si.trustutn.org/info?sn=586170509028290203075&certType=1"><img src="<?php echo base_url(THEME.'/img/LOGO_2.png')?>" /></a>
						<a id='___szfw_logo___' href='https://credit.szfw.org/CX20170512038068330639.html' target='_blank'><img src='<?php echo base_url(THEME.'/img/LOGO_3.png')?>' border='0' /></a>
						<a  key ="592fc4daefbfb024ddebb032"  logo_size="124x47"  logo_type="business"  href="http://www.anquan.org" ><script src="<?=base_url()?>themes/mall/js/aq_auth.js"></script></a>

						<!--可信网站图片LOGO安装开始-->
						<script src="https://kxlogo.knet.cn/seallogo.dll?sn=e17060744030067981js6u000000&size=0"></script>
						<!--可信网站图片LOGO安装结束-->
					</li>
                    <li>
						<?php //if($curLocation_id == '156') { ?>
						<p><?php echo sprintf(lang('label_company_name_4'),'https://mall.tps138.com');?>  </p>
						<?php // } ?>
						<p><?php echo sprintf(lang('label_company_name_3'), date('Y'))?> </p>
                    </li>
				</ul>
			</div>
			<div class="fl ce-l">				
				<ul>
					<li><?php echo lang('label_company_name_contact')?></li>
					<li>
						<!-- <p>info@shoptps.com</p> --><!-- leon 2016-12-27 修改邮箱内容,暂时隐藏内容 -->
                        <?php if($curLan == 'zh' || $curLan == 'hk') {?>
						<p>0755-88694880</p><!--    0755-33198568   -->
                        <?php }else {?>
                        <p>(1)323-395-2828</p>
                        <?php }?>
                        <p style="display:none;">elapsed_time:<?php echo $this->benchmark->elapsed_time();?>,memory_usage:{memory_usage}</p>
					</li>
					<li>
						<?php if($curLan == 'zh' || $curLan == 'hk') {?>
							<?php echo lang('label_company_name_address')?>
						<?php }?>
					</li>
					<!-- <li><a href=""><s title=""></s></a> <a href=""><s class="s1"></s></a> <a href=""><s class="s2"></s></a> <a href=""><s class="s3"></s></a></li> -->
				</ul>
			</div>
            <div class="fr">
				<img src="<?php echo base_url(THEME.'/img/weixin.jpg')?>" />
				<p><?php echo lang('label_company_name_download')?></p>
			</div>
		</div>
	</div>
    <script src="<?php echo base_url(THEME.'/js/main.js?v=20170614')?>"></script>
    <script src="<?php echo base_url(THEME.'/js/base.js?v=20170614')?>"></script>
    <script>
		function show_travel_msg() {
			layer.msg('<?php echo lang('msg_travel')?>');
		}
		function show_promote_msg() {
			layer.msg('<?php echo lang('msg_promote')?>');
		}
	</script>
	<script type='text/javascript'>
		(function(){
			document.getElementById('___szfw_logo___').oncontextmenu = function(){
				return false;
			}
		})();
	</script>

    <!--站点统计-->
    <script type="text/javascript">
        //window.onload = function(){
			var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			document.write(unescape("%3Cspan  class=d-n id='cnzz_stat_icon_1262216189'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s13.cnzz.com/z_stat.php%3Fid%3D1262216189' type='text/javascript'%3E%3C/script%3E"));
        //}
	</script>
    <script type="text/javascript">
		//var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
		//document.write(unescape("%3Cspan  class=d-n id='cnzz_stat_icon_1262216189'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s13.cnzz.com/z_stat.php%3Fid%3D1262216189' type='text/javascript'%3E%3C/script%3E"));
		window.onload = function(){
			$(function() {
				$("#cnzz_stat_icon_1262216189").hide();
			})
		}
    </script>
    
    <!-- 第三方内容 -->
	<!-- 百度统计 -->
	<script>
		window.onload = function(){
			//百度统计
			var tps_path_info = '<?php echo $tps_path_info; ?>';
			var orderInfo = {};
			if(tps_path_info == 'mall/respond'){
				var order_obj =eval(<?php echo json_encode(isset($order_info)?$order_info:"{}");?>);
				orderInfo = {
					"orderId": order_obj.order_id,
					"orderTotal": order_obj.order_total,
					"item": []
				};
				var list = order_obj.order_list;
				for(var i=0; i<list.length;i++){
					orderInfo.item.push({
						"skuId": list[i].sku_id,
						"skuName": list[i].sku_name,
						"category": list[i].category,
						"Price": list[i].price,
						"Quantity": list[i].quantity
					});
				}
			}
	        var _hmt = _hmt || [];
			_hmt.push(['_trackOrder', orderInfo]);
	        (function() {
	          var hm = document.createElement("script");
	          hm.src = "themes/mall/js/baidu_statistics.js";
	          var s = document.getElementsByTagName("script")[0]; 
	          s.parentNode.insertBefore(hm, s);
	        })();
	        $(function(){
	        	var search_hots_width = 0;
				$(".search-hots").children().each(function(){
					search_hots_width += $(this).width()+6;
					if (search_hots_width > 460 ) {
						$(this).remove();
					}
				/*	var index = $(this).index();
					if(index>14){
						$(this).css({
							'display':'none'
						})
					}*/
				});
				var obj_b = $(".search-hots a").last().next('b');
				if (obj_b[0]) {
					obj_b.remove();
				}
			});
		}
    </script>

</body>
</html>