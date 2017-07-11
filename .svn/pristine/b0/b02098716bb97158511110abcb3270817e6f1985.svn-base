
<style>
    #level_span div {float: left; font-weight: bold; padding: 5px; text-align: center; width: 150px; color: #f38630;}
    .tab138 tr td{border: solid 1px #c4e3f3; width: 6px; height: 6px;}


</style>

<b class="please_input_id"><?php echo lang('please_input_id'); ?></b><br>
<input type="hidden" value="1" class="hidden_pager">
<input class="inputValue" type="text"  name="uid" id="cashForMonthFee" value="" >
<span class="msg"></span><br>
<input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('find') ?> >



<div class="main" style="background: #C0C0C0; border: solid 1px #C0C0C0; min-height: 400px;">
    <div class="container_rank" style="margin-bottom: 50px;">
                <span id="level_span">
                    <div style="background-color:purple"><?php echo lang('diamond'); ?></div>
                    <div style="background-color:gold"><?php echo lang('gold'); ?></div>
                    <div style="background-color:green"><?php echo lang('silver'); ?></div>
                    <div  style="background-color:pink"><?php echo lang('bronze');?></div>
                    <div style="background-color:white"><?php echo lang('free'); ?></div>
                    <div style="background-color:gray"><?php echo lang('freeze'); ?></div>
                </span>
    </div>

    <div class="box138" id="tab138" style="margin: 0 auto; margin-bottom: 50px;">
        <table class="tab138">
            <?php echo $html?>
        </table>
        <div class="loadMore" style="width: 100%;background:#ECECEC;height: 30px; cursor:pointer">
            <center><span style="line-height:30px; color: #999;"><?php echo lang("load_more")?></span></center>
        </div>
    </div>
</div>

<script type="application/javascript">

    $(document).ready(function() {

        $(".loadMore").click(function () {
            //点击增加一页
            var pager = parseInt($(".hidden_pager").val());
            $(".hidden_pager").attr("value", pager + 1);
            var pager = parseInt($(".hidden_pager").val());
            $.ajax({
                type: "POST",
                url: '/admin/forced_matrix_138/load_more',
                data: {pager: pager},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $(".tab138").append(data.str);
                        if (data.code == 101) {
                            layer.msg(data.msg);
                            $(".loadMore").hide();
                        }
                    }
                }
            });
        })
    })


    /**按钮点击查找ID**/
        $(".btn").click(function(){
            var user_id=$(".inputValue").attr("value");
            $.findUserById(user_id);
        })

    /***input回车事件***/
        $(".inputValue").keypress(function(event){
            var key=event.which;
            if(key==13){
                var user_id=$(".inputValue").attr("value");
                $.findUserById(user_id);
            }
        })

    /***138点击事件****/
    $(".item_c").live("click",function(){
        $(".tab138 tr td").css("border","solid 1px #c4e3f3");
        $(this).css("border", "solid 3px #f00");
        var userId = $(this).children("span").attr('userId');

        $.findUserById(userId);
    });

    jQuery.extend({
        // 自定义方法
        findUserById:function (user_id) {
            $.ajax({
                type: "POST",
                url: '/admin/forced_matrix_138/find_user',
                data: {user_id: user_id},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $(".tab138 tr td").css("border","solid 1px #c4e3f3");
                        $(".msg").text(data.msg);
                        $(".msg").css('color','#008040');
                        $(".tab138 tr td span").each(function(){
                            if($(this).attr('userId') == user_id){
                                $(this).parent('td').css("border","solid 3px #f00");
                                return false;
                            }
                        })
                    }else{
                        $(".msg").text(data.msg);
                        $(".msg").css('color','#f00');
                        $(".tab138 tr td").css("border","solid 1px #c4e3f3");
                        setTimeout(function(){$(".msg").text("");},3000)
                    }
                }
            });

        }
    });
</script>








