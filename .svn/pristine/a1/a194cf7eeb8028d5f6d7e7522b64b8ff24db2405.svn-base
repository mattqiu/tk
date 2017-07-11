<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th width="30%"><input id="rollbackSub" autocomplete="off" value="重新加密" class="btn btn-primary" type="button"></th>
                <th><?php echo lang('pay_name'); ?></th>
                <th><?php echo lang('pay_currency'); ?></th>
                <th><?php echo lang('is_enabled'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php if($item['pay_id'] === '110'){ ?>
                            <?php echo lang('current_commission')?>
                        <?php }else{ ?>
                            <img src="<?php echo base_url("img/paymentMethod/".$item["pay_code"].".png");?>" alt="<?php echo $item['pay_name']?>">
                        <?php } ?>
                    </td>
                    <td><?php echo $item['pay_name'] ?></td>
                    <td><?php echo $item['payment_currency'] ?></td>
                    <td><?php echo $item['is_enabled'] == 0 ? lang('not_enabled') : lang('yes_enabled') ?></td>
                    <td>
                    <?php if(/*$item['pay_config']*/1){?>
                    <a href="<?php echo base_url("admin/edit_payment/index").'/'.$item['pay_id']?>"><i class="icon-edit"></i></a>
                    <?php }?>
                    </td>
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
<script>
    $('#rollbackSub').click(function(){
        $.ajax({
            type:"post",
            url:"<?php echo base_url('admin/payment_list/Reappear_encryption');?>",
            data:{},
            dataType:"json",
            success:function(res){
                if (res.success) {
                    layer.msg('<?php echo lang("result_ok"); ?>');
                } else {
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
        });
</script>
<?php echo $pager;