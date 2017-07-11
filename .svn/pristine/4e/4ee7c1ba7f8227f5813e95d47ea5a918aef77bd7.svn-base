<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
		<select name="cate_id">
		<?php foreach($type_all as $type) {?>
			<option <?php if($searchData['cate_id'] == $type['type_id']) echo 'selected'; ?> value="<?php echo $type['type_id']?>"><?php echo $type['type_name']?></option>
		<?php }?>
		</select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        <div class="pull-right">
            <button class="btn" type="button" rel="tooltip" data-original-title="<?php echo lang('add_news'); ?>" onclick="location.href='/admin/add_news'"><i class="icon-plus"></i><?php echo lang('add_news'); ?></button>
        </div>
    </form>

</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
            	<th><?php echo lang('news_cate'); ?></th>
                <th><?php echo lang('title'); ?></th>

                <th><?php echo lang('display'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                	 <td><?php echo $item['type_name'] ?></td>
                    <td><?php echo $item['title'] ?></td>

                    <td><?php echo $item['display']? lang('need_display') : lang('no_display') ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                    <td><a href="<?php echo base_url("admin/add_news/index").'/'.$item['id']?>"><i class="icon-edit"></i></a></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;