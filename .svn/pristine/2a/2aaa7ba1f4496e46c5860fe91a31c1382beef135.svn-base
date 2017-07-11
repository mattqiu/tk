<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="keywords" value="<?php echo $searchData['keywords']; ?>"  class="input-xlarge search-query" placeholder="<?php echo lang('label_goods_group_id');?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
         <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/goods_group/index') ?>"><?php echo lang('label_goods_group_add') ?></a>
    </form>
   
    <div class="clearfix"></div>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
            	<th><?php echo lang('label_goods_group_id'); ?></th>
                <th><?php echo lang('label_goods_group_content'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['group_id']?></td>
                    
                    <td>
                    <?php if(isset($item['goods']['list']))foreach($item['goods']['list'] as $g) {?>
                    <div style="padding:5px; border:1px solid #ddd; float:left; margin-right:10px;">
                    <a href="<?php echo base_url(),'index/product?snm=',$g['info']['goods_sn_main']?>" target="_blank"><img width="92" height="82" title="<?php echo $g['info']['goods_name']?>" src="<?php echo $img_host.$g['info']['goods_img']?>" /></a>
                    <p><br />
                    <?php echo '$',$g['info']['shop_price']?> * <?php echo $g['num']?><br />
                    <a href="<?php echo base_url(),'index/product?snm=',$g['info']['goods_sn_main']?>" target="_blank"><?php echo $g['info']['goods_sn_main']?></a>
                    </p>
                    </div>
                    <div style="display:inline-table; vertical-align:middle; font-size:24px; float:left; padding:0 10px; margin-right:10px">+</div>
                    <?php }?>
                    </td>
                    
                    <td><a href="<?php echo base_url("admin/goods_group/index"),'/',$item['group_id'];?>"><i class="icon-edit"></i></a></td>
                </tr>
                
        <?php }}else{ ?>
            <tr>
                <th colspan="3" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>