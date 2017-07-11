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
        <td>
            <span><?php echo lang('admin_trade_repair_number') ?></span>
            <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
        </td>
        
    </tr>
	<form method="post"  id="month_fee_to_amount">
            <td><input class="order_id" type="text"  name="order_id" id="order_id" value="" ></td>
            <td><input class="txn_id" type="text"  name="txn_id" id="txn_id" value=""></td>
            <td><input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('confirm') ?> ></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
            </td>
        </form>
  </tr>

</table>
</div>

<div class="search-well">
    <form class="form-inline" action="" method="GET">

        <input type="hidden" name="tabs_type" value="4" />
        <input class="input-small" id="order_sn" type="text" name="order_sn" placeholder="<?php echo lang('order_sn'); ?>" />

        <button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th><?php echo lang('order_sn'); ?></th>
            <th><?php echo lang('admin_trade_repair_number'); ?></th>
            <th><?php echo lang('status'); ?></th>
            <th><?php echo lang('process_num'); ?></th>
            <th><?php echo lang('admin_order_info_create_time'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id'] ?></td>
                    <td><?php echo $item['order_id'] ?></td>
                    <td><?php echo $item['txn_id'] ?></td>
                    <td><?php echo $statusArr[$item['status']] ?></td>
                    <td><?php echo $item['process_num'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>

                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
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
        'use strict';
        var page_data = <?php echo json_encode($page_data); ?>;
        if (page_data.order_sn != null) {
            $('#order_sn').val(page_data.order_sn);
        }


    });


    $(function() {
		//鼠标离开验证订单号
        $(".order_id").blur(function () {
            var order_id = $(this).val();
            $('#month_fee_to_amount input').val(order_id);
            $.ajax({
                success: "success",
                url: "/admin/order_report/checkOrderId",
                dataType: "json",
                type: "post",
                data: {order_id: order_id},
                success: function (res) {
                    if (res.success) {
                        $('#maxLimit').text(res.msg);
                        $('#amount_pool').val(res.amount)
                        $('#error_msg').text("");
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#maxLimit').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        });
	//鼠标离开验证交易号
         $(".txn_id").blur(function () {
            var txn_id = $(this).val();
            $('#month_fee_to_amount input').val(txn_id);
            $.ajax({
                success: "success",
                url: "/admin/order_report/checkTxnId",
                dataType: "json",
                type: "post",
                data: {txn_id: txn_id},
                success: function (res) {
                    if (res.success) {
                        $('#maxLimit').text(res.msg);
                        $('#amount_pool').val(res.amount)
                        $('#error_msg').text("");
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#maxLimit').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });
		});
		
		//提交
        $("#submit_id").click(function(){
            var order_id = $('#order_id').val();
            var txn_id=$('#txn_id').val();
            $.ajax({
                success: "success",
                url: "/admin/order_report/checkData",
                dataType: "json",
                type: "post",
                data: {order_id: order_id,txn_id:txn_id},
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
