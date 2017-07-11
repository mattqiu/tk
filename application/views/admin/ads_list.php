<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
	<form class="form-inline" method="GET">
		<select name="language_id" >
			<?php foreach($lang_all as $v):	?>
			<option <?php if(isset($searchData['language_id']) && $v['language_id'] == $searchData['language_id']) { ?>selected<?php }?> value="<?php echo $v['language_id']; ?>"><?php echo $v['name']; ?></option>
			<?php endforeach; ?>
		</select>

		<button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>   
    <!--form class="form-inline" method="POST" action="/admin/goods/import_doba_products" enctype="multipart/form-data"> 
    	<input type="file" name="doba_goods" />
        <button class="btn" type="submit"><?php echo lang('goods_doba_import') ?></button>
    </form-->  
		<a  class="btn" target="_self" href="<?php echo base_url('admin/ads/ads_add') ?>"><?php echo lang('ads_add') ?></a>
	

	<div class="clearfix"></div>
</div>

<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th><?php echo lang('label_ads_img'); ?></th>
				<th><?php echo lang('label_ads_url'); ?></th>
				<th><?php echo lang('label_ads_location'); ?></th>
				<th><?php echo lang('label_ads_status'); ?></th>
				<th><?php echo lang('label_ads_sort'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($list){ ?>
			<?php foreach ($list as $item) { ?>
				<tr>

					<td><a href="<?php echo $img_host.$item['ad_img']?>" target="_blank"><img src="<?php echo $img_host.$item['ad_img']?>" width="120px" height="42px;" /></a></td>
					<td><a href="<?php echo $item['ad_url']?>" target="_blank"><?php echo $item['ad_url']?></a></td>
					<td><?php echo $item['location']?></td>
					<td><?php if($item['status']) echo 'Show'; else echo '<span style="color:red">Hidden</span>'?></td>
					<td><?php echo $item['sort_order']?></td>
                    
					<td><a href="<?php echo base_url("admin/ads/ads_add"),'/',$item['ad_id'];?>"><i class="icon-edit"></i></a></td>
				</tr>

		<?php }}else{ ?>
			<tr>
				<th colspan="25" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
