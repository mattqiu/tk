<div class="login_main" style="height: 620px">
    <div id="loginwrap1">
    	<div class="login_bg" style="height: 500px">
        	<div class="maintext">
                    <div class="secondtext">
                        <?php if($success){?>
                        <span style="color:#5e975e;font-size:24px;"><?php echo lang('congratulations'); ?></span>
                    	<img src="<?php echo base_url('img/new/waiting.gif')?>">
                        <?php }?>
                        <p>
                            <?php echo $success ? lang('account_active_success') : lang('account_active_false'); ?>
                            <br><br>
                            <?php if ($success) { ?>
                            <?php echo lang('account_active_success_jump'); ?>
                            <script>
                                var timerc = 6; //全局时间变量（秒数）
                                function add() { //加时函数
                                    if (timerc > 0) { //如果不到5分钟
                                        --timerc; //时间变量自增1
                                        $("#sec").html(timerc); //写入秒数（两位）
                                        setTimeout("add()", 1000); //设置1000毫秒以后执行一次本函数
                                    } else {
                                        window.location.href = '/ucenter';
                                    }
                                }
                                add(); //首次调用add函数
                            </script>
                            <?php } ?>
                        </p>
                    </div>
            </div>

        </div>
    </div>
</div>