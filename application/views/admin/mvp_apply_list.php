<div class="search-well">
    <form action="<?php echo base_url('admin/mvp_apply_list'); ?>" class="form-inline" method="GET">
        <input autocomplete="off" value="<?php if(isset($searchData['id_or_email_or_name'])){echo $searchData['id_or_email_or_name'];} ?>" name="id_or_email_or_name" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control" id="id_or_email_or_name" placeholder="<?php echo lang('search_notice')?>" />
        <input autocomplete="off" value="<?php if(isset($searchData['phone_number'])){echo $searchData['phone_number'];} ?>" name="phone_number" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control" id="phone_number" placeholder="<?php echo lang('checkout_phone'); ?>" />

        <select name="sale_rank" style="width: 180px;">
            <option value=""><?php echo lang('mvp_professional_title'); ?></option>
            <option value="4" <?php echo $searchData['sale_rank']==4 ? ' selected=selected': ''; ?> ><?php echo config_item('sale_rank')[4]; ?></option>
            <option value="5" <?php echo $searchData['sale_rank']==5 ? ' selected=selected': ''; ?> ><?php echo config_item('sale_rank')[5]; ?></option>
        </select>

        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search'); ?></button>
    </form>
    <div style="clear: both;float: left;">
        <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
<!--            <form action="--><?php //echo base_url('admin/mvp_apply_list/e_excel'); ?><!--" id="e_excel" method="post">-->
<!--                <button class="btn" type="submit"> --><?php //echo lang('export'); ?><!--</button>-->
<!--            </form>-->
        <?php }?>
    </div>

    <div style="float: left;margin-left: 5px;">
    <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
<!--        <form action="--><?php //echo base_url('admin/mvp_apply_list/e_excel_2'); ?><!--" id="" method="post">-->
<!--            <button class="btn" type="submit"> --><?php //echo lang('export').'GVP'; ?><!--</button>-->
<!--        </form>-->
    <?php }?>
    </div>

    <div style="float: left;margin-left: 5px;">
    <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
<!--        <form action="--><?php //echo base_url('admin/mvp_apply_list/e_excel_3'); ?><!--" id="" method="post">-->
<!--            <button class="btn" type="submit"> --><?php //echo lang('export').'EMD'; ?><!--</button>-->
<!--        </form>-->
    <?php }?>
    </div>

    <div style="float: left;margin-left: 5px;">
    <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
<!--        <form action="--><?php //echo base_url('admin/mvp_apply_list/e_excel_4'); ?><!--" id="" method="post">-->
<!--            <button class="btn" type="submit"> --><?php //echo lang('export').'座位号'; ?><!--</button>-->
<!--        </form>-->
    <?php }?>
    </div>

    <div style="float: left;margin-left: 5px;">
    <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
<!--        <form action="--><?php //echo base_url('admin/mvp_apply_list/e_excel_5'); ?><!--" id="" method="post">-->
<!--            <button class="btn" type="submit"> --><?php //echo lang('export').'就餐'; ?><!--</button>-->
<!--        </form>-->
    <?php }?>
    </div>

    <div style="clear: both;">
        <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
<!--            <form action="--><?php //echo base_url('admin/mvp_apply_list/send_msg'); ?><!--" class="form-inline" id="" method="post" enctype="multipart/form-data">-->
<!--                <input autocomplete="off" type="file" name="user_info_excel"/>-->
<!--                <button class="btn" type="submit" id="submit_button"> --><?php //echo '发送信息'; ?><!--</button>-->
<!--            </form>-->
        <?php }?>
    </div>


</div>

<div class="well">
    <form method="post" id='form1' action=""  name="listForm">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo lang('realName') ?></th>
                <th><?php echo lang('email_text') ?></th>
                <th><?php echo lang('mobile') ?></th>
                <th><?php echo lang('pls_sel_mem_rank'); ?></th>
                <th><?php echo lang('mvp_professional_title'); ?></th>
                <th><?php echo lang('mvp_apply_time'); ?></th>
                <th><?php echo lang('payment'); ?></th>
                <th><?php echo lang('mvp_apply_number'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($list)){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td><?php echo $item['mobile']; ?></td>
                    <td><?php echo $item['parent_id'] ? lang(config_item('user_ranks')[$item['user_rank']]) : ''; ?></td>
                    <td><?php echo config_item('sale_rank')[$item['sale_rank']];?></td>
                    <td><?php echo $item['pay_time']; ?></td>
                    <td><?php echo $payment_map[$item['payment_type']]; ?></td>
                    <td><?php echo 'MVP'.$item['number']; ?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="20" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
        </table>
    </form>
</div>
<div style="float: left;clear: both">
    <?php echo $pager;?>
</div>

<script>
    $(function(){
        $('#submit_button').click(function(){
            $(this).attr('disabled',true);
        });
    });
</script>