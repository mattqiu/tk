
<style>
    #level_span div {float: left; font-weight: bold; padding: 5px; text-align: center; width: 150px; color: #f38630;}
    .tab138 tr td{border: solid 1px #c4e3f3; width: 6px; height: 6px;}


</style>
<form method="post">

    <input class="inputValue" type="text"  name="move_uid" id="move_uid" value="" placeholder="<?php echo lang('user_move_position_s');?>"><br/>
    <input class="inputValue" type="text"  name="position_uid" id="position_uid" value="" placeholder="<?php echo lang('user_move_position_n');?>">
    <span class="msg"></span><br>
    <input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('submit') ?> >
</form>



<script type="application/javascript">

    $(document).ready(function() {

        $("#submit_id").click(function () {
           
            var uid = $("#move_uid").val();
            var paserd_uid = $("#position_uid").val();

			if(0==uid.trim().length || 0==paserd_uid.trim().length)
			{				
				layer.alert('<?php echo lang('uid_not_null'); ?>');
				return;
			}

            $.ajax({
                type: "POST",
                url: '/admin/user_move_position/upmoveposition',
                data: {uid: uid,paserd_uid:paserd_uid},
                dataType: "json",
                success: function (data) {
                	if (data.success === true) {
        				layer.msg('<?php echo lang('update_success')?>');
                        setTimeout(function(){
                            window.location.reload(); 
                        },1500);
        				   				
        			}else if(data.success === false){
        				layer.msg('<?php echo "修改失败";  ?>');
                        setTimeout(function(){
                           window.location.reload(); 
                        },1500);
        			} else {
                        layer.msg('<?php echo "抱歉,不支持此次移动!";  ?>');
                        setTimeout(function(){
                           window.location.reload(); 
                        },1500);
                    }
                }
            });
        })
    })
</script>








