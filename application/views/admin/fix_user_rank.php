<form id="fix_form" method="post">
    <table class="reset_user_pwd">
        <tr>
            <td>
                <span><?php echo lang('repair_user_sale_rank_type') ?></span>
            </td>
        </tr>
        <tr>
        	<td>
        		<select name="com_type" id="repair_type" class="com_type input-medium" style="width:220px;">
					<option value="1">修复职称等级</option>
                                        <option value="2">修复职称变动时间</option>
                                        <?php if(in_array($adminInfo['id'],array(198,464,493)) || $adminInfo['role'] == 0){?>
                                        <option value="3">修复会员对应关系</option>
                                        <?php } ?>
                </select>
        	</td>
        </tr>
        <tr>
            <td><input type="text" name='user_id' placeholder="<?php echo lang('pls_t_uid'); ?>" id="user_id" autocomplete="off"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" autocomplete="false" id="fix_btn" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
        </tr>
        
    </table>
    <style>
        .user_detail tr td{padding:10px;border:1px solid #ccc}
    </style>
    
    <table class="user_detail">
        
    </table>
</form>

<script>
$(function(){
     
	$("#fix_btn").click(function(){
                var html ="";
		$.get("/admin/user_list/do_fix_user_rank", {"user_id":$("#user_id").val(), "type":$("#repair_type").val()}, function(callback){
                        layer.msg(callback.message);
                        html +='<tr><td>查询用户下级列表</td></tr>';
                        $.each(callback.list_user, function (k, v) {
                            html +='<tr>';
                            html +='<td>uid:</td><td>'+v.uid+'</td>';
                            html +='<td>group_id:</td><td>'+v.group_id+'</td>';
                            html +='<td>mso_num:</td><td>'+v.mso_num+'</td>';
                            html +='<td>sd_num:</td><td>'+v.sd_num+'</td>';
                            html +='<td>sm_num:</td><td>'+v.sm_num+'</td>';
                            html +='<td>vp_num:</td><td>'+v.vp_num+'</td>';
                            html +='</tr>';
                        });
                        html +='<tr><td>查询用户父ID</td></tr>';
                        html +='<tr>';
                        html +='<td>id:</td><td>'+callback.group_user.uid+'</td>';
                        html +='<td>group_id:</td><td>'+callback.group_user.group_id+'</td>';
                        html +='<td>mso_num:</td><td>'+callback.group_user.mso_num+'</td>';
                        html +='<td>sd_num:</td><td>'+callback.group_user.sd_num+'</td>';
                        html +='<td>sm_num:</td><td>'+callback.group_user.sm_num+'</td>';
                        html +='<td>vp_num:</td><td>'+callback.group_user.vp_num+'</td>';
                        html +='</tr>';
                        $(".user_detail").html(html);
                        
		}, "json");
	});
})
</script>