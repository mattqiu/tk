<div class="well">
            <div class="block">
                <?php if(isset($month_fee) && $month_fee == 'month_fee'){
                    $url = base_url('ucenter/commission/index#month_fee');
                }else{
                    $url = base_url('ucenter/#upgrade');
                }?>

                <div class="block-body">
                    <div class="row-fluid">
                        <script>
                            $(function(){
                                curAlert = $(".alert strong")
                                errAlert = $(".alert");
                                curAlert.html('<?php echo lang('back_account')?>');
                                errAlert.addClass('alert-info');
                                errAlert.removeClass('hidden');
                                add(); //首次调用add函数
                            });
                            var timerc = 3; //全局时间变量（秒数）
                            function add() { //加时函数
                                if (timerc > 0) { //如果不到5分钟
                                    $("strong span").text(timerc);
                                    --timerc; //时间变量自增1
                                    setTimeout("add()", 1000); //设置1000毫秒以后执行一次本函数
                                } else {
                                    window.location.href = '<?php echo $url?>';
                                }
                            }

                        </script>
                        <div class="alert hidden">
                            <strong></strong>
                        </div>
                        <?php if(isset($result['success']) && $result['success']){?>

                            <p class="text-success"> <?php echo lang('payment_success') ;?>. <a href="<?php echo $url?>"><?php echo lang('back') ;?></a></p>

                        <?php }else{?>
                            <p class="text-info"><?php echo lang('payment_error') ;?></p>
                            <ul>
                                <?php if (!isset($result['success'])){ ?>
                                    <li class="text-error"><?php echo lang('no_tra') ;?></li>
                                <?php }else{ ?>
                                <li class="text-error"><?php echo isset($result['msg'])? $result['msg'] : lang('no_tra'); ?></li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </div>

</div>