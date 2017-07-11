
<div class="search-well">
<form method="post" id="month_fee_to_amount_1"  name="month_fee_to_amount_1" enctype="multipart/form-data">
    <table>
        <tr>
            <td>
                <span><?php echo lang('user_id') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>
        </tr>
        <tr>            
            <td><input type="text"  name="uid_txt" id="uid_txt" value=""></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
            </td>
               
        </tr>
       

        <tr>

                <td>   
                    <input type="button" id="submit_id"  class="btn btn-primary" value=<?php echo lang('repair_amount') ?> >               
                    <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                    <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
                </td>

        </tr>
    </table>
     </form>
</div>

<?php if (isset($err_msg)): ?>
    <div class="well">
        <p style="color: red;"><?php echo $err_msg; ?></p>
    </div>
<?php endif; ?>
<?php
if (isset($paginate)) {
    echo $paginate;
}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

    function errboxHtml(msg) {
        return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
    }

</script>
<script>

    $(function() {        
        //提交
        $("#submit_id").click(function(){
            var uid_txt=$('#uid_txt').val();
            $.ajax({
                success: "success",
                url: "/admin/order_achievement_repair/repair",
                dataType: "json",
                type: "post",
                data: {uid_txt:uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success').text(res.msg);
                        $('#error_msg').text("");
                        setTimeout("$('#submit_success').text('')", 3000);
                        location.reload('');
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