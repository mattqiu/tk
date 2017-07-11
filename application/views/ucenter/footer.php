</div>
<footer>
    <hr>

    <!-- <p class="pull-right">Collect from <a href="<?php echo base_url('');?>" title="TPS138" target="_blank">TPS138</a></p> -->


    <p class="foot_p">Copyright &copy; 2014-<?php echo date("Y",time());  ?> <a href="<?php echo base_url('');?>" target="_blank">TPS138</a></p>
</footer>

<div id="confirm_sponsor" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-body" id="confirm_message" style="text-align: center;">
    </div>
    <div class="modal-footer">
        <button autocomplete="off" style="float:left;margin-left: 20%;" class="btn btn-primary" id="confirm_ok"><?php echo lang('ok'); ?></button>
        <button autocomplete="off" style="float:right;margin-right: 20%;" class="btn btn-primary" id="confirm_cancel"><?php echo lang('_no'); ?></button>
    </div>
</div>

</div>
</div>
</div>
<!--</div>-->
<script src="<?php echo base_url('ucenter_theme/lib/bootstrap/js/bootstrap.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
<script type="text/javascript">
    $("[rel=tooltip]").tooltip();
    $(function () {
        $('.demo-cancel-click').click(function () {
            return false;
        });
    });
</script>
<div id="card_mask_layer"></div>
<div id="card_popup_layer" style="display: none;">  
    <div style="text-align: center;border: 0 none;margin-top: 30px;margin-bottom: 22px; box-sizing: border-box;"><img style="text-align: center" id="upload_1_loading" src="<?php echo base_url('img/new/loading-min.gif'); ?>"></div>
<!--    <div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">身份证审核大约需要2分钟,</div>
    <div style="text-align: center;font-family: 微软雅黑;font-size:20px;font-weight:blod;line-height:25px;">请您耐心等待!</div>-->
 <?php echo lang('check_card_wait'); ?>
</div>
</body>
</html>