<?php  
/**
 * 客户意见收集界面
 */
?>
<style>
	*{margin:0;padding:0}
	ul li{list-style: none}
	a{text-decoration: none}
	.userBackBox{width:900px;margin:0 auto;padding:0 15px 15px 15px;font-size:16px;font-family:'微软雅黑'}
	.userBackBox .ubTip{padding-left:50px;border-bottom:1px solid #999;height:50px;line-height:50px;font-size:13px;color:#1313fd}
	.userBackBox .ubForm{margin:30px 30px 30px 10px;}
	.userBackBox .ubForm ul li{margin-bottom:16px}
	.userBackBox .ubForm ul li .subTitle{display:inline-block;width:130px;text-align: right;margin-right:30px}
	.userBackBox .ubForm ul li .textStyle{width:300px;height:35px;outline:none;border:1px solid #e5e5e5;padding-left:10px;box-sizing: border-box;font-size:14px;font-family:'微软雅黑'}
	.userBackBox .ubForm ul li .areaStyle{width:675px;height:175px;outline:none;border:1px solid #e5e5e5;overflow: auto;resize: none;padding:10px;box-sizing: border-box;font-size:14px;font-family:'微软雅黑'}
	.userBackBox .ubForm ul li .verTop{vertical-align:top}
	.userBackBox .ubForm ul li .formTitle{width:675px;height:35px;outline:none;border:1px solid #e5e5e5;padding-left:10px;box-sizing: border-box}
	.userBackBox .ubForm ul li .code{display:inline-block;width:75px;height:35px;line-height:33px;text-align:center;border:1px solid #e5e5e5;box-sizing: border-box;vertical-align:top}
	.userBackBox .ubForm ul li .formSubmit{display:block;width:153px;height:33px;line-height:33px;text-align:center;color:#fff;background:#949090;font-size:16px;margin-left: 165px;}
	.userBackBox .ubForm ul li .formSubmit:hover{background:green}
	.userBackBox .ubForm ul li .refresh{cursor:pointer}
	.userBackBox .ubForm ul li .font{color:#f00;font-size:12px}
</style>
<div class="w1200">
    <!-- 头部面包屑 -->
    <div class="crumbs">
    	<img src="<?php echo base_url(THEME.'/img/1.gif')?>" />
		<a href="<?php echo base_url()?>"><?php echo lang('label_home')?> </a>
		&gt;
		<?php echo $artical['parent_name'];?>
		<?php if(isset($artical['artical'])){ echo ' &gt; ',$artical['artical']['title'];}?>
    </div>

    <!-- 内容 -->
    <div class="help clear">
		<!-- 左侧菜单 -->
		<div class="col-md-3 clear">
			<div class="sideBox clear" >
				<?php
					foreach($artical as $k=>$art) {
					if(is_numeric($k))	{
				?>
					<h3 class=""><em></em><?php echo $art['type_name']?></h3>
					<div class="bd" <?php if($art['type_id'] == $artical['parent_id']) echo 'style="display:block;"'; else echo 'style="display:none;"';?>>
						<?php if($art['list']) {?>
							<ul>
								<?php foreach($art['list'] as $li) {?>
								<li <?php if($artical['artical']['id'] == $li['id']) {?> class="sideB"<?php }?>>
									<a href="<?php
										if(in_array($li['id'],array(55,56,57))) {
											echo base_url(),'index/feedback';
										}else {
											echo base_url(),'index/help?aid=',$li['id'];
										} ?>">
										<?php echo $li['title']?>
									</a>
								</li>
								  <?php }?>
							</ul>
						<?php }?>
					</div>
				<?php }}?>

				<!-- 意见反馈内容 -->
	            <h3 class="on">
					<a href="<?php echo base_url(),'index/feedback_customer'; ?>">
						<em></em><?php echo lang('feedback_customer');?>
					</a>
	            </h3>

			</div>
		</div>
		<!-- 左侧菜单 end -->

		<!-- 右侧内容 -->
		<div class="col-md-9 clear">
			<div class="content">
				<h3><?php echo lang('feedback_customer_top_name');?></h3>
				<form class="js-feedback" action="" method="post">
					<!--用户反馈 start-->
					<section class="userBackBox">
						<div class="ubForm feedback_customer_event">
							<ul>
								<li>
									<!-- 名字 -->
									<span class="subTitle"><?php echo lang('feedback_customer_name');?></span>
									<input id="name" name="name" value="<?php if(!empty($user_info['name'])) echo $user_info['name']; ?>" type="text" class="textStyle" placeholder="<?php echo lang('feedback_customer_name_null');?>"/>
									<span class="font"></span>
								</li>
								<li>
									<!-- 手机 -->
									<span class="subTitle"><?php echo lang('feedback_customer_mobile');?></span>
									<input id="mobile" name="mobile" value="<?php if(!empty($user_info['mobile'])) echo $user_info['mobile']; ?>" type="text" class="textStyle" placeholder="<?php echo lang('feedback_customer_mobile_null');?>"/>
									<span class="font"></span>
								</li>
								<li>
									<!-- 邮件 -->
									<span class="subTitle"><?php echo lang('feedback_customer_email');?></span>
									<input id="email" name="email" value="<?php if(!empty($user_info['email'])) echo $user_info['email']; ?>" type="text" class="textStyle" placeholder="<?php echo lang('feedback_customer_email_null');?>"/>
									<span class="font"></span>
								</li>
								<li>
									<!-- 标题 -->
									<span class="subTitle"><?php echo lang('feedback_customer_title');?></span>
									<input id="title" name="title" type="text" class="formTitle textStyle" maxlength="200" placeholder="<?php echo lang('feedback_customer_title_length');?>"/>
									<p class="font" style="margin-left:165px;margin-top:10px;"></p>
								</li>
								<li>
									<!-- 内容 -->
									<span class="subTitle verTop"><?php echo lang('feedback_customer_content');?></span>
									<textarea id="content" name="content" maxlength="2000" onKeyDown='if (this.value.length >= 1000){alert("内容已超出长度")}' type="feedback" class="areaStyle js-content" placeholder="<?php echo lang('feedback_customer_content_length');?>"></textarea>
									<p class="font" style="margin-left:165px;margin-top:10px;"></p>
								</li>
								<li>
									<!-- 验证码 -->
									<span class="subTitle"><?php echo lang('feedback_customer_code');?></span>
									<input id="code" name="code" type="text" class="textStyle" placeholder="<?php echo lang('feedback_customer_code_null');?>"/>
									<span class="code" class="content" id="code_change"><?php echo $feedback_code; ?></span>
									<span class="refresh"><?php echo lang('feedback_customer_refresh');?></span>
									<span class="font"></span>
								</li>
								<li>
									<a class="formSubmit button-Login" href="javascript:void(0);"><?php echo lang('label_feedback_submit')?></a>
								</li>
							</ul>
						</div>
					</section>
					<!--end 用户反馈-->
				</form>
			</div>
		</div>
		<!-- 右侧内容 end -->
    </div>
</div>

<script>
	$(function() {
		//左侧菜单内容
		$(".sideBox").slide({ titCell:"h3", targetCell:".bd",effect:"slideDown",trigger:"click" });

		//提交信息按钮
		$('.button-Login').click(function() {
			
			var name    = $.trim($('#name').val());      //名字
			var mobile  = $.trim($('#mobile').val());    //电话
			var email   = $.trim($('#email').val());     //邮件
			var title   = $.trim($('#title').val());     //标题
			var content = $.trim($('.js-content').val());//问题描述
			var code    = $.trim($('#code').val());      //验证码
			
			//if(content == '') {
			//	layer.msg('<?php //echo lang('customer_info_content')?>');
			//	return;
			//}

			$.post(
				'<?php echo base_url()."index/feedback_customer_do";?>',
				{
					name:name,
					mobile:mobile,
					email:email,
					title:title,
					content:content,
					code:code,
				},
				function(data){
					data = JSON.parse(data);
					if(data.success){
						$('#name').val('');
						$('#mobile').val('');
						$('#email').val('');
						$('#title').val('');
						$('.js-content').val('');
						$('#code').val('');
						alert('<?php echo lang('info_feedback_succ');?>');
						window.location.reload();
					}else{
						$.each(data, function (itemName, checkResultVal) {
							//alert(itemName);
							//console.log(checkResultVal);
							displayFormItemCheckRes(itemName, checkResultVal);
						});
					}
				}
			);
		});

		//输入框失去焦点事件
		$(".feedback_customer_event input[type='text'],.feedback_customer_event textarea[type='feedback']").blur(function(){

			//console.log($(this).attr('name'));
			var element = $(this);
			var name = element.attr('name');
			var value = element.val();
			$.ajax({
				type:'post',
				url:'/index/feedback_customer_emelent',
				data:{
					name:name,
					value:value
				},
				dataType:"json",
				success:function(data){
					//console.log(data.msg);
					//if(data.isRight == 2){
					//	alert(data.msg);
					//}
					$.each(data, function (itemName, checkResultVal) {
						//alert(itemName);
						//console.log(checkResultVal);
						displayFormItemCheckRes(itemName, checkResultVal);
					});
				}
			});
		})

		//刷新改变验证码
		$('.refresh').click(function(){
			$.post(
					'<?php echo base_url()."index/feedback_customer_code";?>',
					{},
					function(data){
						data = JSON.parse(data);
						$('#code_change').html(data.success);
					}
			);
		});

	});
	//错误信息显示方法
	function displayFormItemCheckRes(itemName, checkResultVal){
		//alert(123);
		curElement = $("input[name='" + itemName + "'],textarea[name='" + itemName + "']");
		if(checkResultVal.isRight == 2){
			curElement.val("");
			//curElement.attr('placeholder',"---"+checkResultVal.msg);
			curElement.siblings('.font').html(checkResultVal.msg);
		}else{
			curElement.siblings('.font').html("");
		}
	}


</script>


