<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
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
        <select name="cate_id" >     
       		<option value=""><?php echo lang('label_sel_cate');?></option>      
            <?php foreach($category_all as $val) { ?>
                <?php 
                    if($val['parent_id'] == 0) { 
                ?>
                <option <?php if(isset($searchData['cate_id']) && $val['cate_id'] == $searchData['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['cate_name']; ?></option>
                <?php  }else { ?>
                <option <?php if(isset($searchData['cate_id']) && $val['cate_id'] == $searchData['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['html'],'|-',$val['cate_name']; ?></option>
                <?php  
                    }
                ?>
            <?php  } ?>
        </select> 
        <input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-xlarge search-query" placeholder="<?php echo lang('label_brand_name');?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
         <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/brand') ?>"><?php echo lang('label_brand_add') ?></a>
    </form>
   
    <div class="clearfix"></div>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
            	<th><?php echo lang('label_brand_id'); ?></th>
                <th><?php echo lang('label_brand_name'); ?></th>
                <th><?php echo lang('label_goods_cate'); ?></th>
                <th><?php echo lang('label_language'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['brand_id']?></td>
                    
                    <td><?php echo $item['brand_name']?></td>
                    
                    <td>
					<?php foreach($category_all as $v) {
						if($v['cate_id'] == $item['cate_id']) {
							echo $v['cate_name'];
						}
					}?>
                    </td>
                    <td>
                    <?php foreach($lang_all as $v) {
						if($v['language_id'] == $item['language_id']) {
							echo $v['name'];
						}
					}?>
                    </td>
                    <td><a href="<?php echo base_url("admin/brand/index"),'/',$item['brand_id'];?>"><i class="icon-edit"></i></a></td>
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