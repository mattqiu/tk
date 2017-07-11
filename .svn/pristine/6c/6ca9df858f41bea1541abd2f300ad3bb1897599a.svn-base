<div class="container" style="border:0px;">
	<div class="row-fluid">
        	<div class="bull_board">
				<?php foreach ($lists as $list){if(!$list['title_'.$curLanguage]){continue;}?>
            		<div class="bull_txt" style="position:relative;" <?php if($list['id'] == 83){?>name="miao" id="miao"<?php }?>>
                    <h4 style="font-weight:normal;">
						<div style="display: inline; font-weight: bold" id="img_<?php echo $list['id']?>">
						<?php echo	$list['title_'.$curLanguage] ? $list['title_'.$curLanguage] : mb_substr(($list[$curLanguage]), 0, 15, 'utf-8')?>
							<?php if(!$list['is_read']){?>
								<img src="<?php echo base_url("../../img/$curLanguage/unread.png") ?>">
							<?php }?>
						</div>
                        <?php if(empty($list[$curLanguage.'_short'])) {  ?>
                        &nbsp;&nbsp;&nbsp;<span id="content_short_<?php echo $list['id']?>"><?php echo isset($list[$curLanguage.'_short'])?$list[$curLanguage.'_short']:""; ?><a class="bulletin_board" attr_id="<?php echo $list['id']?>" class="accordion-toggle" data-toggle="collapse" href="#<?php echo $list['id']?>" style="font-size:14px;">
							<?php echo lang('view_info').'>>'?>
                    </a></span>
                        <?php  } else {  ?>
                         <div style="font-size:14px;padding-top: 5px;" id="content_short_<?php echo $list['id']?>"><?php echo isset($list[$curLanguage.'_short'])?$list[$curLanguage.'_short']:""; ?><a class="bulletin_board" attr_id="<?php echo $list['id']?>" class="accordion-toggle" data-toggle="collapse" href="#<?php echo $list['id']?>">
							<?php echo lang('view_info').'>>'?>
                    </a></div>
                        
                        <?php  }  ?>
					</h4>
                    <div <?php if(true) { ?>id="<?php echo $list['id']?>" class="accordion-body collapse" <?php }?>><?php echo $list[$curLanguage];?></div>
					
                   
					
					<div class="time"><?php echo date('Y-m-d H:i:s',strtotime($list['create_time'])) ?></div>
				</div>
				<?php }?>
            </div>
    </div>
    <?php if(isset($pager)){echo $pager;}?>
</div>

<script>
	$(function(){
		$('.bulletin_board').click(function(){
			var id = $(this).attr('attr_id');

			$('#content_short_'+id).hide();
				$.ajax({
					type: "POST",
					url: "/ucenter/welcome_new/get_board_msg",
					data:{id:id},
					dataType: "json",
					success: function (res) {
						if(res.success){

							if (res.hasOwnProperty('count') && res.count > 0) {
								$('#my_badge_news_bulletin').html('+' + res.count).addClass('label label-info');
							}else{
								$('#my_badge_news_bulletin').html('').removeClass('label label-info');
							}
							$('#img_'+id).children('img').remove();

						}else{
							layer.msg(res.msg);
						}
					}
				});

		});
	});
</script>
