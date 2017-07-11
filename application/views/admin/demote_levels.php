<script>
    $(function(){
        var check_user;
        $('#user_id').bind('input propertychange', function () {
            clearTimeout(check_user);
            check_user = setTimeout(function () {
                var uid = $('#user_id').val();
                $.ajax({
                    type: "POST",
                    url: "/admin/demote_levels/checkStoreLevel",
                    data: {id: uid},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            $('#store_level').text(res['result']['store_level']);
                            $('#monthly_fee_level').text(res['result']['monthly_fee_level']);
                            $('#store_name').text(res['result']['store_name']);
                            $('#store_status').text(res['result']['store_status']);
                            $('#no_exist,#monthly_fee_level_option,#store_level_option').text('');
                            $('#monthly_fee_level_option').append(res['result']['monthly_fee_level_option']);
                            $('#store_level_option').append(res['result']['store_level_option']);
                            if(res['result']['monthly_fee_level_option'] && res['result']['store_level_option']){

                                $('#demote_levels,#monthly_fee_level_option,#store_level_option').attr('disabled',false);
                            }
                        }else{
                            $('#no_exist').text(res.msg).addClass('text-error');
                            $('#store_name,#store_status,#store_level,#monthly_fee_level,#monthly_fee_level_option,#store_level_option').text('');
                            $('#demote_levels,#monthly_fee_level_option,#store_level_option').attr('disabled',true);
                        }
                    }
                });
            }, 500);
        });
        $('#demote_levels').click(function(){
            var oldSubVal = $('#demote_levels').val();
            $('#demote_levels').val($('#loadingTxt').val());
            $('#demote_levels').attr("disabled","disabled");
            $.ajax({
                type: "POST",
                url: "/admin/demote_levels/do_demote",
                data: {id:$('#user_id').val(),store_level_option:$("#store_level_option  option:selected").val(),
					monthly_fee_level_option:$("#monthly_fee_level_option  option:selected").val(),check_info:$('#check_info').val()},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $('#demote_levels_msg').html(res.msg).addClass('text-success');
                        $('#user_id,#store_level_option,#monthly_fee_level_option').html('');
                        $('#demote_levels').val(oldSubVal);
                        setTimeout(function(){
                            location.reload();
                        },3000);
                    }else{
                        $('#demote_levels_msg').html(res.msg).addClass('text-error');
                        $('#demote_levels').attr("disabled",false);
                        $('#demote_levels').val(oldSubVal);
                    }

                }
            });
        });
    });
</script>

    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <table class="upgradeTb" cellspacing="30px">
        <tr>
            <td class="title">
                <?php echo lang('user_id')?>:
            </td>
            <td class="content">
				<input name="id" autocomplete="off" id="user_id" placeholder="<?php echo lang('user_id')?>">
				<span id="no_exist"></span>
			</td>
        </tr>
		<tr>
			<td class="title" style="height: 40px;">
				<?php echo lang('name')?>:
			</td>
			<td class="content">
				<strong id="store_name" class="text-success"></strong>
			</td>
		</tr>
		<tr>
			<td class="title" style="height: 40px;">
				<?php echo lang('status')?>:
			</td>
			<td class="content">
				<strong id="store_status" class="text-success"></strong>
			</td>
		</tr>
        <tr style="height: 40px;">
            <td class="title">
                <?php echo lang('store_level')?>:
            </td>
            <td class="content">
                <strong id="store_level" class="text-success"></strong>

            </td>
        </tr>
        <tr style="height: 40px;">
            <td class="title">
                <?php echo lang('monthly_fee_level')?>:
            </td>
            <td class="content">
                <strong id="monthly_fee_level" class="text-success"></strong>
            </td>
        </tr>
        <tr style="height: 50px;">
            <td class="title">
                <?php echo lang('store_level')?>:
            </td>
            <td class="content">
                <select name="store_level_option" id="store_level_option" disabled >

                </select>
            </td>
        </tr>
        <tr style="height: 50px;" >
            <td class="title">
                <?php echo lang('monthly_fee_level')?>:
            </td>
            <td class="content">
                <select name="monthly_fee_level_option" id="monthly_fee_level_option" disabled>

                </select>
            </td>
        </tr>
		<tr style="height: 50px;" >
			<td class="title">
				<?php echo lang('remark')?>:
			</td>
			<td class="content">
				<textarea autocomplete="off" id="check_info" name="check_info" placeholder="<?php echo lang('remark'); ?>"></textarea>
			</td>
		</tr>
        <tr>
            <td class="title">
                <input type="button" disabled id="demote_levels" autocomplete="off" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
            <td class="content" id="demote_levels_msg"></td>
        </tr>
    </table>
