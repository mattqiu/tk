<div class="well">
    <form id="after_sale_form" method="post">
        <table class="upgradeTb" cellspacing="30px">
            <tr>
                <td><img src="<?php echo base_url('img/new/reg_icon.jpg'); ?>"><?php echo lang('user_id'); ?>:</td>
                <td>
                    <textarea id="user_id" placeholder="<?php echo lang('wo_hao_tongbu_tishi'); ?>" rows="9" style="width: 700px;" name="user_id"></textarea>
                </td>
            </tr>
            <tr>
                <td class="title">
                    <input type="button" id="after_sale" autocomplete="off" class="btn btn-primary" value="<?php echo lang('submit'); ?>">
                </td>
                <td class="content" id="after_sale_msg"></td>
            </tr>
        </table>
    </form>
</div>
<script>
    $('#after_sale').click(function () {
        if ($("#user_id").val().replace(/[ ]/g,"") == '') {
            layer.msg('<?php echo lang('alert_commission_compensation_notNull'); ?>');
        } else {
            $.ajax({
                type: "POST",
                url: "/admin/synchronize_wo_hao/synchronize",
                data: $('#after_sale_form').serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg); //返回失败信息
                        setTimeout(function () {
                            location.href = "/admin/synchronize_wo_hao";
                        }, 1000);
                    }
                }
            });
        }
    });
</script>