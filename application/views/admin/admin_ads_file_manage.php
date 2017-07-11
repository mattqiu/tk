<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<style>
    form{margin:0 0 2px}
</style>
<div class="search-well">
    <form class="form-inline" id="admin_file_manage" action="<?php echo base_url('admin/admin_ads_file_manage'); ?>" method="get">
        <select name="file_type" style="width: 180px;">
            <option value=""><?php echo lang('admin_file_type'); ?></option>

            <?php
                foreach(config_item('admin_file_type') as $k=>$v){
             ?>

                <option value="<?php echo $k; ?>" <?php if($k==$searchData['file_type']){echo 'selected=selected';} ?> ><?php echo lang($v); ?></option>

            <?php
                }
             ?>
        </select>



        <select  name="file_area" style="width: 180px;">
            <option value=""><?php echo lang('admin_file_area'); ?></option>

            <?php foreach(config_item('admin_file_area') as $k=>$v){  ?>

                <option value="<?php echo $k; ?>" <?php if($k==$searchData['file_area']){echo 'selected=selected';} ?>><?php echo $v; ?></option>

            <?php } ?>

        </select>


        <input autocomplete="off" value="" name="file_name" type="text" class="form-control" style="border-radius: 4px;display: inline-block;width: 180px;" placeholder="<?php echo lang('admin_file_name'); ?>">

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start_time']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end_time']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">


        <button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
    </form>

    <div style="clear: both"></div>
    <a href="<?php echo base_url('admin/admin_ads_file_manage/add_or_update?type=1'); ?>" class="btn" type="button"><?php echo lang('add'); ?></a>
</div>

<div class="well">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php echo lang('admin_file_name'); ?></th>
                <th><?php echo lang('admin_file_type'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($list){ ?>
                <?php foreach($list as $item){ ?>
                    <tr>
                        <td><?php echo $item['file_name'] ?></td>
                        <td><?php echo lang(config_item('admin_file_type')[$item['file_type']]) ?></td>
                        <td><?php echo $item['create_time'] ?></td>
                        <td>
                            <a href="<?php echo base_url('admin/admin_ads_file_manage/add_or_update?type=2&id='.$item['id']) ?>"><?php echo lang('admin_file_modify'); ?></a> /
                            <a id="<?php echo $item['id']; ?>" class="file_delete" href="#"><?php echo lang('delete'); ?></a>
                        </td>
                    </tr>
                    <?php }?>
            <?php } else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php echo $pager ?>
</div>

<script>

    $(function(){

        $('.file_delete').click(function(){
            var id = $(this).attr("id");
            layer.confirm('<?php echo lang('sure'); ?>', {icon: 3, title:'<?php echo lang('delete_admin_file'); ?>'},
                function(){
                    $.ajax({
                        type:"post",
                        url:"<?php echo base_url('admin/admin_ads_file_manage/do_delete'); ?>",
                        data:{id:id},
                        dataType:"json",
                        success:function(res){
                            if(res.success==1){
                                layer.msg(res.msg);
                                window.location.reload();
                            }else{
                                layer.msg(res.msg);
                            }
                        }
                    });
                }
            );

        });

    });

</script>