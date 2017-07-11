<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
		<select name="store_code" >
			<option value=""><?php echo lang('label_sel_store');?></option>
			<?php
			foreach ($store_all as $v)
			{
				echo "<option ";
				if ($v['store_code'] == $searchData['store_code'])
				{
					echo "selected ";
				}
				echo "value=\"{$v['store_code']}\">".lang("admin_oper_storehouse_{$v['store_code']}")."</option>";
			}
			?>
		</select>
		<select name="supplier_id" >
			<option value=""><?php echo lang('label_sel_supplier');?></option>
			<?php foreach($supplier_all as $v): ?>
				<option <?php if($v['supplier_id'] == $searchData['supplier_id']) { ?>selected<?php }?> value="<?php echo $v['supplier_id']; ?>"><?php echo $v['supplier_name']; ?></option>
			<?php endforeach; ?>
		</select>
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<script>
	$(function(){
		$('[name="supplier_id_select"]').change(function(){

			var li ;
			li = layer.load();
			$.post("/admin/storehouse_to_supplier/update_supplier", {store_code: $(this).attr('store_code'),supplier_id:$(this).val()}, function (data) {
				/*if(data.success){

				}else{

				}*/
				layer.close(li);
				layer.msg(data.msg);
				location.reload();
			},'json');
		});
	});
</script>
<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('admin_store_code'); ?></th>
            <th><?php echo lang('admin_supplier'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo lang('admin_oper_storehouse_'.$item['store_code']); ?></td>
                    <td><select name="supplier_id_select" autocomplete="off" store_code="<?php echo $item['store_code']?>">
							<option value="">——————————————</option>
							<?php foreach($supplier_all as $v): ?>
								<option <?php if($v['supplier_id'] == $item['supplier_id']) { ?>selected<?php }?> value="<?php echo $v['supplier_id']; ?>"><?php echo $v['supplier_name']; ?></option>
							<?php endforeach; ?>
						</select></td>
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