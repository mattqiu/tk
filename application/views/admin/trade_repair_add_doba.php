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
            <span><?php echo lang('order_sn') ?></span>
        </td>
    </tr>
	<form method="post"  id="month_fee_to_amount">
            <td><input class="order_id" type="text"  name="order_id" id="order_id" value="" ></td>
            <td><input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('confirm') ?> ></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
            </td>
        </form>
  </tr>

</table>
</div>

<?php
if (isset($paginate))
{
    echo $paginate;
}
?>
<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>
<script>
    $(function() {
		//提交
        $("#submit_id").click(function(){
            var order_id = $('#order_id').val();
            $.ajax({
                success: "success",
                url: "/admin/order_report/add_doba",
                dataType: "json",
                type: "post",
                data: {order_id: order_id},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success').text(res.msg);
                        $('#error_msg').text("");
                        setTimeout("$('#submit_success').text('')", 3000);
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#submit_success').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        })
    })
</script>
