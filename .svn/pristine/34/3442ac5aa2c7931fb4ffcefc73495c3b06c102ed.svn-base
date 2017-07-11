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
                <option <?php if($val['cate_id'] == $searchData['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['cate_name']; ?></option>
                <?php  }else { ?>
                <option <?php if($val['cate_id'] == $searchData['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['html'],'|-',$val['cate_name']; ?></option>
                <?php  
                    }
                ?>
            <?php  } ?>
        </select> 
        <select name="state" >     
        	<option value=""><?php echo lang('label_sel_status');?></option>  
       		<option <?php if(isset($searchData['state']) && 'is_on_sale=1' == $searchData['state']) { ?>selected<?php }?> value="is_on_sale=1"><?php echo lang('label_goods_sale');?></option>  
            <option <?php if(isset($searchData['state']) && 'is_on_sale=0' == $searchData['state']) { ?>selected<?php }?> value="is_on_sale=0"><?php echo lang('label_goods_unsale');?></option>    
            <option <?php if(isset($searchData['state']) && 'is_on_sale=2' == $searchData['state']) { ?>selected<?php }?> value="is_on_sale=2"><?php echo lang('label_goods_looking');?></option>  
        </select>       
    
        <input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-xlarge search-query" placeholder="<?php echo lang('label_goods_name'),'/',lang('label_goods_main_sn');?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        <br />
        <a class="btn" target="_self" href="<?php echo base_url('supplier/goods') ?>"><?php echo lang('add_goods') ?></a>
    </form>
   
    <div class="clearfix"></div>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th width="15%"><?php echo lang('label_goods_name'); ?></th>
                <th><?php echo lang('label_goods_main_sn'); ?></th>
                <th><?php echo lang('label_goods_img'); ?></th>
                <th><?php echo lang('label_goods_weight'); ?></th>
                <th><?php echo lang('label_goods_purchase_price'); ?></th> 
                <th><?php echo lang('label_goods_sale'); ?></th>
                <th><?php echo lang('label_goods_add_time'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <th><a href="<?php echo base_url('index/product?snm='.$item['goods_sn_main']).'&is_view=1';?>" target="_blank"><?php echo $item['goods_name']; ?></a></th>
                    <td><a class="view_sub_sku" href="javascript:;" data-sn="<?php echo $item['goods_sn_main']?>" data-lang="<?php echo $item['language_id']?>"><?php echo $item['goods_sn_main']?></a></td>
                    <td><a href="/<?php echo $item['goods_img']?>" target="_blank"><img src="/<?php echo $item['goods_img']?>" width="60px" height="60px;" /></a></td>
                    <td><?php echo $item['goods_weight']?></td>
                    <td><?php echo $item['purchase_price']?></td>
                    <td><?php if($item['is_on_sale']==1){ echo  lang('label_yes');}elseif($item['is_on_sale']==2){ echo lang('label_goods_looking'); }else{ echo lang('label_no');}?></td>
                    <td><?php echo date('Y-m-d',$item['add_time'])?></td>
                    <td><a href="<?php echo base_url("supplier/goods/index"),'/',$item['goods_sn_main'];?>"><i class="icon-edit"></i></a></td>
                </tr>
                
        <?php }}else{ ?>
            <tr>
                <th colspan="25" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>
<script>
	var lang_sku='<?php echo lang('label_sub_sn');?>',
	lang_color='<?php echo lang('label_color');?>',
	lang_number='<?php echo lang('label_goods_stock');?>',
	lang_size='<?php echo lang('label_size');?>';
	//查看子sku
	$(function(){
		$('.view_sub_sku').click(function() {
			$('.sub_list').remove();
			
			var $t=$(this),main_sn=$t.attr('data-sn'),lang_id=$t.attr('data-lang');
			$.getJSON('/supplier/goods/get_sub_sn_list',{main_sn:main_sn,lang_id:lang_id},function(data) {
				if(data.error == 1) {
					$().message(data.info);
					
				}else {
					var html='<tr class="sub_list"><td colspan="1"></td><td colspan="2">'+lang_sku+'</td><td  colspan="2">'+lang_color+'</td><td  colspan="2">'+lang_size+'</td><td  colspan="2">'+lang_number+'</td></tr>';
					
					$.each(data.info,function(k,$this){
						html += '<tr  class="sub_list"><td colspan="1"></td><td colspan="2">'+$this.goods_sn+'</td><td  colspan="2">'+$this.color+'</td><td  colspan="2">'+$this.size+'</td><td  colspan="2">'+$this.goods_number+'</td></tr>';
					});

					
					$t.parents('tr').after(html);
				}
			});
		});
	});
</script>