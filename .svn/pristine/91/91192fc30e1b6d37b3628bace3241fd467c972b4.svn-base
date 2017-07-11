<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-xlarge search-query" placeholder="<?php echo lang('label_feedback_email'),'/',lang('label_feedback_userid');?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
         
    </form>
   
    <div class="clearfix"></div>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
            	<th><?php echo lang('label_feedback_email'); ?></th>
                <th><?php echo lang('label_feedback_userid'); ?></th>
                <th><?php echo lang('label_feedback_date'); ?></th>
                <th><?php echo lang('label_feedback_content'); ?></th>
                <th><?php echo lang('label_feedback_state'); ?></th>           
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                   
                    <td><a href="mailto:<?php echo $item['email']?>" target="_blank"><?php echo $item['email']?></a></td>
                    <td><?php echo $item['user_id']?></td>
                    <td><?php echo date('Y-m-d',$item['add_time'])?></td>                                      
                    <td style="width:50%"><?php echo $item['content']?></td>
                    <td><?php echo $item['state'] ? lang('label_feedback_state_yes') : lang('label_feedback_state_no');?></td>
					<?php if(!$item['state']) {?>
                    <td><a href="javascript:;" class="del" data-id="<?php echo $item['feed_id']?>"><?php echo lang('label_feedback_change_state')?></a></td>				<?php }?>
                </tr>
                
        <?php }}else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>
<script>
$(function(){
	$('.del').click(function(){
		var $t=$(this),id=$t.attr('data-id');
		$.get('/admin/feedback/chang_state',{id:id},function(data){
			if(data == 'ok') {
				$t.parents('tr').hide();
			}
		});
	});
})
</script>
