<!-- 弹层 -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <h3 id="myModalLabel"><?php echo lang('account_info')?></h3>
    </div>
    <div class="modal-body">
        <form action="" method="post" class="form-horizontal" id="edit_account_info">
            <table class="enable_level_tb" cellspacing="50%" cellpadding="10">
                <tr>
                    <td class="tab_name"><?php echo lang('realName') ?>:</td>
                    <td><input type="text" name="name" value="<?php echo $userInfo['name']?$userInfo['name']:'' ?>" class="input-xlarge"<?php echo $userInfo['name']?'disabled':'' ?>></td>
                </tr>
                <tr>
                    <td class="tab_name"><?php echo lang('country') ?>:</td>
                    <td>
						<select id='con_and_area' autocomplete="off" name='country_id' class="input-xlarge">
							<option value=""><?php echo lang('input_country'); ?></option>
							<?php foreach (config_item('countrys_and_areas') as $key => $value) { ?>
								<option value="<?php echo $key ?>"><?php echo lang($value); ?></option>
							<?php } ?>
						</select>
                    </td>
                </tr>
                <tr>
                    <td class="tab_name"><?php echo lang('user_address') ?>:</td>
                    <td><input type="text" name="address" value="<?php echo $userInfo['address']?$userInfo['address']:'' ?>" class="input-xlarge"></td>
                </tr>

            </table>
        </form>
    </div>
    <div class="modal-footer">
        <span class="msg" id="account_info_msg"></span>
        <button autocomplete="off" class="btn btn-primary" id="edit_account"><?php echo lang('submit'); ?></button>
    </div>
    <input type="hidden" value="<?php echo lang('name_alert')?>" id="name_alert">
    <input type="hidden" value="<?php echo lang('address_alert')?>" id="address_alert">
</div>
<!-- /弹层 -->
<?php if(isset($modal)){?>
    <script>
        $(function(){
            $('#myModal').modal({backdrop: 'static', keyboard: false});
            $('.enable_level_tb input').blur(function(){
                if($(this).attr('name') == 'name'){
                   // alert($('#name_alert').val());
                }
                if($(this).attr('name') == 'address'){
                    //alert($('#address_alert').val());
                }
            });
            $('#edit_account').click(function(){
                $.ajax({
                    type: "POST",
                    dataType:'json',
                    url: "/ucenter/account_info/edit_account",
                    data: $('#edit_account_info').serialize(),
                    success: function(data){
                        if(data.success){
                            location.href = '/ucenter';
                        }else{
                            $('#account_info_msg').html(data.msg);
                            $('#edit_account').attr('disabled',false);
                        }
                    },
                    beforeSend:function(){
                        $('#edit_account').attr('disabled',true);
                    }
                });
            });
        });
    </script>
<?php }?>