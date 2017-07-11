<div class="well">
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <form id="after_sale_form" method="post">
    <table class="upgradeTb" cellspacing="30px">
            <tr>
                    <td class="title">
                            <img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_id')?>:
                    </td>
                    <td class="content">
                            <input name="as_id" type="text" autocomplete="off" id="as_id" value=""  placeholder="" <?php echo isset($as_info) ? 'readonly' : ''?>>
                    </td>
            </tr>
            <tr>
                    <td class="title">
                            <img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo '升级订单号';?>:
                    </td>
                    <td class="content">
                            <input name="order_id" type="text" autocomplete="off" id="order_id" value=""  placeholder="多个订单号用英文,隔开" <?php echo isset($as_info) ? 'readonly' : ''?>>
                    </td>
            </tr>
            <tr>
                    <td class="title">
                            <img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo '抽回比例';?>:
                    </td>
                    <td class="content">
                            <input name="exchange_rate" type="text" autocomplete="off" id="exchange_rate" value=""  placeholder="" <?php echo isset($as_info) ? 'readonly' : ''?>>
                    </td>
            </tr>
            <tr>
                <td></td>
               
                    <td class="title">
                            <input type="button" id="after_sale" autocomplete="off" class="btn btn-primary" value="<?php echo lang('submit');?>">
                    </td>
                    <td class="content" id="after_sale_msg"></td>
            </tr>
    </table>
    </form>
</div>
<script>
    $('#after_sale').click(function(){
            var oldSubVal = $('#after_sale').val();
            $('#after_sale').val($('#loadingTxt').val());
          //  $('#after_sale').attr("disabled","disabled");
            $.ajax({
                type: "POST",
                url: "/admin/admin_demo_level/do_demo_level",
                data: $('#after_sale_form').serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                            $('#after_sale_msg').html(res.msg).addClass('text-success');
                            $('#after_sale').val(oldSubVal);
                            setTimeout(function(){
                                    location.href="/admin/after_sale_order_list";
                            },1000);
                    }else{
                            $('#after_sale_msg').html('× '+res.msg).addClass('text-error');
                            $('#after_sale').attr("disabled",false);
                            $('#after_sale').val(oldSubVal);
                    }
                }
            });
        });
</script>