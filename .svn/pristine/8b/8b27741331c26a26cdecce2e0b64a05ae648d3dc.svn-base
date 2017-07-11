<div class="search-well">
    <form class="form-inline" method="GET">
    <select name="language_id" > 
        	<option value=""><?php echo lang('label_language_all');?></option>
        	<?php 
				foreach($lang_all as $v) {
			?>
						
						<option <?php if(isset($searchData['language_id']) && $v['language_id'] == $searchData['language_id']) { ?>selected<?php }?> value="<?php echo $v['language_id']; ?>"><?php echo $v['name']; ?></option>
            <?php 
				}
			?>
        </select>
        <input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-medium search-query" placeholder="<?php echo lang('label_cate_name')?>" >
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
         <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/category') ?>"><?php echo lang('add_category') ?></a>
    </form>
   
    <div class="clearfix"></div>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>            	
                <th style="width:150px;"><?php echo lang('label_cate_name'); ?></th>
                <th><?php echo lang('label_cate_icon'); ?></th>
                <th><?php echo lang('label_cate_desc'); ?></th>
                <th><?php echo lang('label_cate_sort'); ?></th>
                <th><?php echo lang('label_ads_status'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td style="text-align:left;  margin-left:15%; padding-left:10px;"><?php if($item['parent_id'] > 0) echo $item['html'],'|-';?><?php echo $item['cate_name'] ?></td>
                    <td><?php if($item['cate_img']) {?><img width="50"  height="50" src="<?php echo $img_host.$item['cate_img']?>" /><?php }?></td>
                    <td><?php echo $item['cate_desc'] ?></td>
                    <td><?php echo $item['sort_order']?></td>
                    <td><?php if($item['status']) echo 'Show'; else echo '<span style="color:red">Hidden</span>'?></td>
                    <td><a href="<?php echo base_url("admin/category/index").'/'.$item['cate_sn']?>"><i class="icon-edit"></i></a></td>
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