<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
	<form class="form-inline" method="GET">
		<select name="language_id" >
			<option value=""><?php echo lang('label_language_all');?></option>
			<?php foreach($lang_all as $v):	?>
			<option <?php if(isset($searchData['language_id']) && $v['language_id'] == $searchData['language_id']) { ?>selected<?php }?> value="<?php echo $v['language_id']; ?>"><?php echo $v['name']; ?></option>
			<?php endforeach; ?>
		</select>
		<select name="shipper_id" >
			<option value=""><?php echo lang('label_goods_shipper_sel');?></option>
			<?php foreach($supplier_all as $v): ?>
            <?php if($v['is_supplier_shipping']) {?>
			<option <?php if(isset($searchData['shipper_id']) && $v['supplier_id'] == $searchData['shipper_id']) { ?>selected<?php }?> value="<?php echo $v['supplier_id']; ?>"><?php echo $v['supplier_name']; ?></option>
            <?php } ?>
			<?php endforeach; ?>
			
		</select>
		<select name="supplier_id" >
			<option value=""><?php echo lang('label_sel_supplier');?></option>
			<?php foreach($supplier_all as $v): ?>
			<option <?php if($v['supplier_id'] == $searchData['supplier_id']) { ?>selected<?php }?> value="<?php echo $v['supplier_id']; ?>"><?php echo $v['supplier_name']; ?></option>
			<?php endforeach; ?>
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
			<option <?php if(isset($searchData['state']) && 'is_best=1' == $searchData['state']) { ?>selected<?php }?> value="is_best=1"><?php echo lang('label_goods_best');?></option>
			<option <?php if(isset($searchData['state']) && 'is_new=1' == $searchData['state']) { ?>selected<?php }?> value="is_new=1"><?php echo lang('label_goods_new');?></option>
			<option <?php if(isset($searchData['state']) && 'is_hot=1' == $searchData['state']) { ?>selected<?php }?> value="is_hot=1"><?php echo lang('label_goods_hot');?></option>
			<option <?php if(isset($searchData['state']) && 'is_home=1' == $searchData['state']) { ?>selected<?php }?> value="is_home=1"><?php echo lang('label_goods_home');?></option>
			<option <?php if(isset($searchData['state']) && 'is_free_shipping=1' == $searchData['state']) { ?>selected<?php }?> value="is_free_shipping=1"><?php echo lang('label_goods_ship');?></option>
			<option <?php if(isset($searchData['state']) && 'is_ship24h=1' == $searchData['state']) { ?>selected<?php }?> value="is_ship24h=1"><?php echo lang('label_goods_24');?></option>
			<option <?php if(isset($searchData['state']) && 'is_new=1 and is_home=1' == $searchData['state']) { ?>selected<?php }?> value="is_new=1 and is_home=1"><?php echo lang('label_new');?></option>
			<option <?php if(isset($searchData['state']) && 'is_hot=1 and is_home=1' == $searchData['state']) { ?>selected<?php }?> value="is_hot=1 and is_home=1"><?php echo lang('label_comment');?></option>
			<option <?php if(isset($searchData['state']) && 'is_alone_sale=2 and is_for_upgrade=0' == $searchData['state']) { ?>selected<?php }?> value="is_alone_sale=2 and is_for_upgrade=0"><?php echo lang('label_goods_group_sale');?></option>
			 <option <?php if(isset($searchData['state']) && 'is_alone_sale=2 and is_for_upgrade=1' == $searchData['state']) { ?>selected<?php }?> value="is_alone_sale=2 and is_for_upgrade=1"><?php echo lang('label_goods_group_sale_upgrade');?></option>
			 <option <?php if(isset($searchData['state']) && 'is_on_sale=2' == $searchData['state']) { ?>selected<?php }?> value="is_on_sale=2"><?php echo lang('label_goods_looking');?></option>
             <option <?php if(isset($searchData['state']) && 'is_voucher_goods=1' == $searchData['state']) { ?>selected<?php }?> value="is_voucher_goods=1"><?php echo lang('label_goods_voucher');?></option>
		</select>
		<input type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query Wdate span2 time_input" placeholder="<?php echo lang('start_date') ?>" />
		-
		<input type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query Wdate span2 time_input" placeholder="<?php echo lang('end_date') ?>" />
		<input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-xlarge search-query" placeholder="<?php echo lang('label_goods_name'),'/',lang('label_goods_name_cn'),'/',lang('label_goods_main_sn');?>">
		<button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>   
    <!--form class="form-inline" method="POST" action="/admin/goods/import_doba_products" enctype="multipart/form-data"> 
    	<input type="file" name="doba_goods" />
        <button class="btn" type="submit"><?php echo lang('goods_doba_import') ?></button>
    </form-->  
		<a  class="btn" target="_self" href="<?php echo base_url('admin/goods') ?>"><?php echo lang('add_goods') ?></a>
	

	<div class="clearfix"></div>
</div>

<div class="well">
	<table class="table">
		<thead>
			<tr>				
				<th style="width:150px;"><?php echo lang('label_goods_name'); ?><br />/<?php echo lang('label_goods_name_cn'); ?></th>
				<th><?php echo lang('label_goods_main_sn'); ?></th>
				<th><?php echo lang('label_goods_img'); ?></th>
				<th><?php echo lang('label_goods_weight'); ?></th>
				<th><?php echo lang('label_goods_purchase_price'); ?></th>
				<th><?php echo lang('label_goods_market_price'); ?></th>
				<th><?php echo lang('label_goods_shop_price'); ?></th>

				<th><?php echo lang('label_goods_ship'); ?></th>
				<!--th><?php echo lang('label_goods_24'); ?></th-->
				<th><?php echo lang('label_goods_sale'); ?></th>
				<th><?php echo lang('label_goods_best'); ?></th>
				<th><?php echo lang('label_goods_new'); ?></th>
				<th><?php echo lang('label_goods_hot'); ?></th>
				<th><?php echo lang('label_goods_is_promote'); ?></th>
				<th><?php echo lang('label_goods_alone_sale'),'/',lang('label_goods_group_sale'); ?></th>
				<!--th><?php echo lang('label_goods_add_user'); ?></th-->
				<th><?php echo lang('label_goods_add_time'); ?></th>
				<th><?php echo lang('label_goods_sort'); ?></th>
                <th><?php echo lang('label_goods_shipper'); ?><br/>/<?php echo lang('label_goods_sale_country'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php if ($list){ ?>
			<?php foreach ($list as $item) { ?>
				<tr>					
					<th><div style="width:150px; word-wrap:break-word;"><a href="<?php echo base_url('index/product?snm='.$item['goods_sn_main'].'&is_view=1');?>" target="_blank"><?php echo $item['goods_name']; ?></a><br />/<?php echo $item['goods_name_cn']; ?></div></th>
					<td><a class="view_sub_sku" href="javascript:;" data-sn="<?php echo $item['goods_sn_main']?>" data-lang="<?php echo $item['language_id']?>"><?php echo $item['goods_sn_main']?></a></td>
					<td><a href="<?php echo $img_host.$item['goods_img']?>" target="_blank"><img src="<?php echo $img_host.$item['goods_img']?>" width="60px" height="60px;" /></a></td>
					<td><?php echo $item['goods_weight']?></td>
					<td><?php echo $item['purchase_price']?></td>
					<td><?php echo $item['market_price']?></td>
					<td><?php echo $item['shop_price']?></td>

					<td><?php if($item['is_free_shipping']) echo  lang('label_yes'); else echo lang('label_no');?></td>
					<!--td><?php if($item['is_ship24h']) echo  lang('label_yes'); else echo lang('label_no');?></td-->
					<td><?php if($item['is_on_sale']==1){ echo  '<a class="unself" href="javascript:;" data-sn="',$item['goods_sn_main'],'" style="color:green">',lang('label_yes'),'</a>';}elseif($item['is_on_sale']==2){ echo lang('label_goods_looking'); }else{ echo '<span  style="color:red">',lang('label_no'),'</span>';}?></td>
					<td><?php if($item['is_best']) echo  lang('label_yes'); else echo lang('label_no');?></td>
					<td><?php if($item['is_new']) echo  lang('label_yes'); else echo lang('label_no');?></td>
					<td><?php if($item['is_hot']) echo  lang('label_yes'); else echo lang('label_no');?></td>
					<td><?php if($item['is_promote']) echo  lang('label_yes'); else echo lang('label_no');?></td>
				   <td><?php if($item['is_alone_sale']==1) echo  lang('label_goods_alone_sale');elseif($item['is_alone_sale']==2) echo lang('label_goods_group_sale');?></td>
					<!--td><?php echo $item['add_user']?></td-->
					<td><?php echo date('Y-m-d H:i',$item['add_time'])?></td>
					<td><?php echo $item['sort_order']?></td>
                    <td><?php echo $item['supplier_name']; ?><br />/<?php echo $item['sale_country_str']; ?>
                    </td>
					<td><a href="<?php echo base_url("admin/goods/index"),'/',$item['goods_sn_main'];?>"><i class="icon-edit"></i></a></td>
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
			$.getJSON('/admin/goods/get_sub_sn_list',{main_sn:main_sn,lang_id:lang_id},function(data) {
				if(data.error == 1) {
					$().message(data.info);

				}else {
					var html='<tr class="sub_list"><td colspan="2"></td><td colspan="3">'+lang_sku+'</td><td  colspan="3">'+lang_color+'</td><td  colspan="3">'+lang_size+'</td><td  colspan="9">'+lang_number+'</td></tr>';

					$.each(data.info,function(k,$this){
						html += '<tr  class="sub_list"><td colspan="2"></td><td colspan="3">'+$this.goods_sn+'</td><td  colspan="3">'+$this.color+'</td><td  colspan="3">'+$this.size+'</td><td  colspan="9">'+$this.goods_number+'</td></tr>';
					});


					$t.parents('tr').after(html);
				}
			});
		});
		
		//下架产品
		$('.unself').click(function() {
			var $t=$(this),goods_sn=$t.attr('data-sn');			

			$.get('/admin/goods/off_self',{main_sn:goods_sn},function(data) {
				if(data == 'ok') {
					$t.parent().html('<span style="color:red">No</span>');
				}
			});

		});
	});
</script>
