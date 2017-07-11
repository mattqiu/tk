<div class="container-fluid">
  <div class="row-fluid">
    <div class="dialog span4">
      <div class="block">
        <div class="block-heading"><?php echo lang('login_welcome');?> </div>
        <div class="block-body">
          <form id="login_form">
            <div>
              <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
              <label><?php echo lang('user_name');?></label>
              <input type="text" name="user_name" class="span12">
              <label><?php echo lang('login_pwd');?></label>
              <input type="password" name="pwd" class="span12">
              <p id="login_msg" class="remember-me error_msg"></p>
            </div>
            <div>
              <input type="button" autocomplete="false" id="login_submit" class="btn btn-primary pull-right" value="<?php echo lang('login');?>">
              </a> </div>
            <!--<label class="remember-me"><input type="checkbox"> Remember me</label>-->
            <div class="clearfix"></div>
          </form>
        </div>
      </div>
      <!--<p class="pull-right" style=""><a href="#" target="blank">Theme by Portnine</a></p>--> 
      
      <!--<p><a href="reset-password.html">Forgot your password?</a></p>--> 
    </div>
  </div>
</div>

<script>
$(function() {
	$("#login_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/supplier/index/submit",
            data: $('#login_form').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    location.href = res.data.jumpUrl;
                } else {
                    $('#login_msg').text(res.msg);
                    curEle.attr("disabled", false);
                }
                curEle.html(oldSubVal);
            }
        });
    });
});
</script>
