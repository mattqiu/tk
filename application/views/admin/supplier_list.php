<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-xlarge search-query" placeholder="<?php echo lang('label_supplier_name'),'/',lang('label_supplier_user');?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
         <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/supplier') ?>"><?php echo lang('label_supplier_add') ?></a>
    </form>
   
    <div class="clearfix"></div>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
            	<th><?php echo lang('label_supplier_name'); ?></th>
                <th><?php echo lang('label_supplier_user'); ?></th>
                <th><?php echo lang('label_supplier_tel'); ?></th>
                <th><?php echo lang('label_supplier_phone'); ?></th>
                <th><?php echo lang('label_supplier_email'); ?></th> 
                <th><?php echo lang('label_supplier_qq'); ?></th>
                <th><?php echo lang('label_supplier_ww'); ?></th>
                <th><?php echo lang('label_supplier_addr'); ?></th> 
                 
                <th><?php echo lang('label_supplier_username'); ?></th>       
                <th><?php echo lang('label_supplier_shipping');?></th>    
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                   
                    <td><a href="<?php echo $item['supplier_link']?>" target="_blank"><?php echo $item['supplier_name']?></a></td>
                    <td><?php echo $item['supplier_user']?></td>
                    <td><?php echo $item['supplier_tel']?></td>                                      
                    <td><?php echo $item['supplier_phone']?></td>
                    <td><?php echo $item['supplier_email']?></td>
                    <td><?php echo $item['supplier_qq']?></td>    
                    <td><?php echo $item['supplier_ww']?></td>
                    <td><?php echo $item['supplier_address']?></td>   
                     
                    <td><?php echo $item['supplier_username']?></td>  
                    <td><?php if($item['is_supplier_shipping']) echo lang('label_yes'); else echo lang('label_no');?></td> 
                    <td><a href="<?php echo base_url("admin/supplier/index"),'/',$item['supplier_id'];?>"><i class="icon-edit"></i></a></td>
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
