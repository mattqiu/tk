<script>
    $(function(){
		$("#pointNeedToMove").keyup(function () {
			$('#moneyNeedToGet').val($('#pointNeedToMove').val());
		});
        var check_user;
        $('#user_id').bind('input propertychange', function () {
            clearTimeout(check_user);
            check_user = setTimeout(function () {
                var uid = $('#user_id').val();
                $.ajax({
                    type: "POST",
                    url: "/admin/sharing_point_to_cash/check",
                    data: {id: uid},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            $('#store_level').text(res['result']['store_level']);
                            $('#name').text(res['result']['name']);
                            $('#no_exist').text('');
                            $('#amount').text(res['result']['amount']);
                            $('#point').text(res['result']['profit_sharing_point']);

                                $('#transfer_point').attr('disabled',false);
                                $('#pointNeedToMove').attr('readonly',false);
                        }else{
                            $('#no_exist').text(res.msg).addClass('text-error');
                            $('#store_level,#name,#amount').text('');
                            $('#transfer_point').attr('disabled',true);
							$('#pointNeedToMove').attr('readonly',true);
                        }
                    }
                });
            }, 500);
        });
        $('#transfer_point').click(function(){
            var oldSubVal = $('#transfer_point').val();
            $('#transfer_point').val($('#loadingTxt').val());
            $('#transfer_point').attr("disabled","disabled");
            $.ajax({
                type: "POST",
                url: "/admin/sharing_point_to_cash/do_transfer",
                data: {id:$('#user_id').val(),money:$('#pointNeedToMove').val()},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $('#transfer_point_msg').html(res.msg).addClass('text-success');
                        $('#transfer_point').val(oldSubVal);
                        $('#amount').text(res.data.newAmount);
                        $('#point').text(res.data.newProfitSharingPoint);
						$('#transfer_point').attr("disabled",false);
						$('#pointNeedToMove,#moneyNeedToGet').val('');
                    }else{
                        $('#transfer_point_msg').html(res.msg).addClass('text-error');
                        $('#transfer_point').attr("disabled",false);
                        $('#transfer_point').val(oldSubVal);
                    }

                }
            });
        });
    });
</script>

    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <table class="upgradeTb" cellspacing="30px">
        <tr>
            <td >
                <?php echo lang('user_id')?>:
            </td>
            <td class="content">
                <input name="id" autocomplete="off" id="user_id" placeholder="<?php echo lang('user_id')?>">
                <span id="no_exist"></span>
            </td>
        </tr>
		<tr style="height: 50px;">
			<td >
				<?php echo lang('name')?>:
			</td>
			<td class="content">
				<strong id="name" class="text-success"></strong>
			</td>
		</tr>
        <tr style="height: 40px;">
            <td >
                <?php echo lang('store_level')?>:
            </td>
            <td class="content">
                <strong id="store_level" class="text-success"></strong>

            </td>
        </tr>
        <tr style="height: 40px;">
            <td >
                <?php echo lang('current_commission')?>:
            </td>
            <td class="content">
				<strong id="amount" class="text-success"></strong>
            </td>
        </tr>
		<tr >
            <td style="height: 40px;width: 140px;">
                <?php echo lang('sharing_point')?>:
            </td>
            <td class="content">
				<strong id="point" class="text-success"></strong>
            </td>
        </tr>

        <tr style="height: 50px;" >
			<td >
			</td>
            <td class="content" >
				<div class="proportion_input_div">
					<span class="percent"><?php echo lang('move'); ?></span>
					<span><input class="proportion_input" type="text" id="pointNeedToMove" value="" readonly="" autocomplete="off"></span>
					<span class="percent"><?php echo lang('point'); ?></span>
					<span class="percent">=</span>
					<span class="percent">$</span>
					<span><input class="proportion_input" type="text" id="moneyNeedToGet" value="" readonly="" autocomplete="off"></span>

					<span class="percent"><?php echo lang('to'); ?></span>
					<span class="percent"><?php echo lang('current_commission'); ?></span>
					<span id='pointToMoneyMsg' class="msg"></span>
				</div>
            </td>
        </tr>
        <tr>
            <td >
                <input type="button" disabled id="transfer_point" autocomplete="off" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
            <td class="content" id="transfer_point_msg"></td>
        </tr>
    </table>
