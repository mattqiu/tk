<style type="text/css">
.text-danger{ color:inherit}
</style>
<div class="search-well">
	<ul class="nav nav-tabs">
		<?php foreach ($tabs_map as $k => $v): ?>
			<li <?php if ($k == $tabs_type) echo " class=\"active\""; ?>>
				<a href="<?php echo base_url($v['url']); ?>">
					<?php echo $v['desc']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="well text-danger">
<table>
    <tr>
        <td>
            <span><?php echo lang('prizes_repair_type') ?></span>
        </td>
    </tr>
    <tr>
        <td colspan="3">
        	<select id="type">
    			     <option value="0"><?php echo lang('prizes_repair_personal_group') ?></option>
    			     <?php /*?><option value="1"><?php echo lang('prizes_repair_group') ?></option><?php */?>
    			</select>
        </td>
	</tr>
	
    <tr class="title_type_0 hide">
        <td>
            <span><?php echo lang('order_sn') ?></span>
        </td>
    </tr>
    <tr class="title_type_0 hide">
        <td><input class="order_id" type="text" id="order_id_0"></td>
        <td><input type="button" class="submit_id btn btn-primary" value=<?php echo lang('confirm') ?> ></td>
        <td>
        	<span id="error_msg_0" style="color: #ff0000;margin-left: 20px;"></span>
            <span  id="submit_success_0" style="color: #009900; font-size: 12px;"></span>
    	</td>
	</tr>
	
	<tr class="title_type_1 hide">
        <td>
            <span><?php echo lang('order_sn') ?></span>
        </td>
    </tr>
    <tr class="title_type_1 hide">
        <td colspan="3"><input class="order_id" type="text" id="order_id_1"></td>
	</tr>
	<tr class="title_type_1 hide">
        <td>
            <span><?php echo lang('user_id') ?></span>
        </td>
    </tr>
    <tr class="title_type_1 hide">
        <td><input class="order_id" type="text"  id="uid_1"></td>
        <td><input type="button" class="submit_id btn btn-primary" value=<?php echo lang('confirm') ?>></td>
        <td>
        	<span id="error_msg_1" style="color: #ff0000;margin-left: 20px;"></span>
            <span  id="submit_success_1" style="color: #009900; font-size: 12px;"></span>
    	</td>
	</tr>
	
	
	<tr>
        <td colspan="3">Tips:<?php echo lang('order_repair_award_prizes_tips')?></td>
    </tr>
</table>
</div>
<?php if (isset($err_msg)): ?>
<div class="well"><p style="color: red;"><?php echo $err_msg; ?></p></div>
<?php endif; ?>
<script>
    $(function() {
    	change_input($("#type").val());
		$("#type").change(function(){
			change_input($(this).val());
		});

		function change_input(value) {
			$(".title_type_"+value).show();
			$(".title_type_"+(~value + 2)).hide();
		}
        
		//提交
        $(".submit_id").click(function(){
            idx = $("#type").val();
            $.ajax({
                success: "success",
                url: "/admin/trade/trade_award_prizes",
                dataType: "json",
                type: "post",
                data: {"oid": $('#order_id_'+idx).val(), "uid":$("#uid_"+idx).val(), "type":idx},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success_'+idx).text(res.msg);
                        $('#error_msg_'+idx).text("");
                        setTimeout("$('#submit_success_'+$('#type').val()).text('')", 3000);
                    } else {
                        $('#error_msg_'+idx).text(res.msg);
                        $('#submit_success_'+idx).text("");
                        setTimeout("$('#error_msg_'+$('#type').val()).text('')", 3000);
                    }
                }
            });

        })
    })
</script>
