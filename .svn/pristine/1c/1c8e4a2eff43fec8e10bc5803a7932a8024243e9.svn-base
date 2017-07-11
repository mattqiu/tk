<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style>
    .alert-info{color: #00466C;letter-spacing: 2px;font-size: 15px;}
</style>
<!--<div class="alert alert-info">-->
<!--	<span>--><?php //echo lang('my_tickets_tip_');?><!--</span><br>-->
<!--	<span>--><?php //echo lang('my_tickets_tip2_');?><!--</span>-->
<!--</div>-->
<div class="search-well">
    <form class="form-inline" method="GET">
    	<input autocomplete="off" value="<?php if(isset($searchData['title_or_tid'])){echo $searchData['title_or_tid']; } ?>" name="title_or_tid" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control" id="title_keywords" placeholder="<?php echo lang('pls_t_title_or_id'); ?>" />
        <select name="type" id="com_type" class="com_type">
            <option value=""><?php echo lang('type'); ?></option>
                <?php if($pro_type) {foreach ($pro_type as $key => $value) { ?>
                    <option value="<?php echo $key ?>"
                       <?php if ($searchData['type'] >= '0' && $key == $searchData['type']) {
                         echo " selected=selected";
                       }
                       ?> >
                    <?php echo lang($value); ?>
                    </option>
                <?php }} ?>
        </select>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table table-hover">
        <thead>
            <tr>
            	<th style="width: 25px;"></th>
            	<th><?php echo lang('tickets_id'); ?></th>             
                <th><?php echo lang('tickets_type'); ?></th>
				<th><?php echo lang('tickets_title'); ?></th>
				<th><?php echo lang('tickets_cus_num'); ?></th>
				<th><?php echo lang('status'); ?></th>
				<th><?php echo lang('time'); ?></th>
				<th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
               	<td><?php if($item['last_reply']==1 && !in_array($item['status'],array(4,5))){ ?> <a rel="tooltip" href="#" data-original-title="<?php echo lang('new_msg')?>"><i class="icon-bell"></i></a> <?php } ?></td>
                	<td style="font-weight: bold;">#<?php echo $item['id']; ?></td>
                    <td><?php if(!empty(config_item('tickets_problem_type')[$item['type']])){ echo lang(config_item('tickets_problem_type')[$item['type']]);} ?>
						<?php if($item['is_attach']){ ?>
							<img src="<?php echo base_url('img/huixing.png')?>" width="18px" >
						<?php }?>
					</td>
                    <td>
						<?php echo mb_substr($item['title'],0,50);if(strlen($item['title'])>50){echo '...';}  ?></td>
                    <td><span style="color: red;">
                            <?php  if(isset($all_cus[$item['admin_id']])){
								echo $all_cus[$item['admin_id']]['job_number'];
							}else{
								echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							}?></span></td>
                    <td style="color:<?php if($item['status']==0 && $item['admin_id']==0){echo '#40c;';}elseif($item['status']==4 || $item['status']==5){echo '#666;';}elseif($item['status']==6){echo '#F00';}else{echo '#0A6'; } ?>">

                        <?php  if($item['status']==0 && $item['admin_id']==0){echo lang('waiting_progress');}
                                elseif(in_array($item['status'],array(0,1,2,3))){echo lang('in_progress');}
                                elseif(in_array($item['status'],array(4,5))){echo lang('ticket_resolved');}
                                elseif($item['status']==6){echo lang('apply_close');}
                        ?>
                    </td>
					<td><?php echo $item['create_time'] ?></td>
                    <td><a href="javascript:view_or_reply_tickets(<?php echo $item['id']; ?>)"> <?php echo lang('view')?> </a></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>
<script>
function view_or_reply_tickets(id){
	layer.open({
			type: 2,
			area: ['830px', '80%'],
			fix: true, //不固定
			maxmin:true,
            shadeClose:true,
			scrollbar: false,
			title:"<?php echo lang('view_tickets_title_')?>",
			content: '<?php echo base_url('ucenter/my_tickets/view_or_reply_tickets'); ?>/' + id,
			end:function(index){
				window.location.reload();
			}
	});	

}
</script>
<style>
	.goto_page{
		height: 25px;
	}
</style>
