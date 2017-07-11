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
</form>

<script>
$(function(){
	$("#fix_btn").click(function(){
		$.get("/admin/user_list/do_fix_user_rank", {"user_id":$("#user_id").val(), "type":$("#repair_type").val()}, function(callback){
			layer.msg(callback.message);
		}, "json");
	});
})
</script>