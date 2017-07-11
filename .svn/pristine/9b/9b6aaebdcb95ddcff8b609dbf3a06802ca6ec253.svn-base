
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <table class="tab_set_except_group">
        <tr>
            <td class="title">
                <?php echo lang('user_id')?>:
                <br/>
                <span style="color: #008000">(<?php echo lang('user_ids_notice')?>)</span>
            </td>
            <td class="content"><textarea id="set_id_list" name="user_id_list" style="width: 300px; height: 200px;"></textarea></td>
        </tr>

        <tr>
            <td></td>
            <td class="title">
                <input type="button" autocomplete="false" id="btn_set_except_group" style="width: 100px;" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
            <td><span style="color: #F00000" id="set_except_group_msg"></span></td>
        </tr>
    </table>

<script>
    $(function(){
        $('#btn_set_except_group').click(function(){
            var id_list=$.trim($('#set_id_list').val());

            $.ajax({
                type: "POST",
                data: {id_list},
                url: "/admin/set_except_group/check_data",
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#set_except_group_msg').css('color','#008000');
                        $('#set_except_group_msg').text(data.msg);
                        setTimeout("$('#set_except_group_msg').text('')", 3000);
                    }else{
                        $('#set_except_group_msg').text(data.msg);
                    }
                }
            });
        })
    })
</script>