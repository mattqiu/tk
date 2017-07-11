<div class="w1200 m-h-430">
    <div class="y-404 clear">
        <s></s>
        <p><?php echo lang('error_404')?></p>
    </div>
</div>

<!-- <div class="w1200 m-h-430">
    <div id="loginwrap1">
        <div class="login_bg">
            <div class="maintext">
                <div class="agreetext">
                    <h2>Oops!</h2>
                    <?php echo lang('error_404')?>
                    <div class="alert hidden">
                        <strong></strong>
                    </div>
                </div>
            </div>
            <script >
                $(function(){
                    $(".agreetext a:last").click(function(){
                        curAlert = $(".agreetext .alert strong")
                        errAlert = $(".agreetext .alert");
                        $.ajax({
                            type: "POST",
                            url: "/error_404/report_404",
                            data: {curUrl:window.location.href},
                            dataType: "json",
                            success: function (data) {
                               if(data == 0){
                                   curAlert.html('<?php echo lang('report_error')?>');
                                   errAlert.addClass('alert-error');
                                   errAlert.removeClass('hidden');
                               }else{
                                   curAlert.html('<?php echo lang('report_success')?>');
                                   errAlert.addClass('alert-info');
                                   errAlert.removeClass('hidden');
                                   add(); //首次调用add函数
                               }
                            }
                        });
                    });
                });
                var timerc = 6; //全局时间变量（秒数）
                function add() { //加时函数
                    if (timerc > 0) { //如果不到5分钟
                        $("strong span").text(timerc);
                        --timerc; //时间变量自增1
                        $("#sec").html(timerc); //写入秒数（两位）
                        setTimeout("add()", 1000); //设置1000毫秒以后执行一次本函数
                    } else {
                        window.location.href = '/';
                    }
                }

            </script>
        </div>
    </div>
</div> -->

