<link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/imgareaselect-default.css?v=1'); ?>">
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.imgareaselect.pack.js?v=1'); ?>"></script>
<div class="well">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#profile" data-toggle="tab"><?php echo lang('upload_avatar');?></a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane active in" id="profile">
            <div class="Personal">

            <form action="<?php echo base_url('ucenter/do_avatar/avatar') ?>" enctype="multipart/form-data" method="post" class="form-horizontal" id="upload_form">
                <div class="alert alert-error hidden cropped">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong></strong>
                </div>
                <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
                <div class="control-group">
                    <label class="control-label"><?php echo lang('user_avatar');?></label>
                    <div class="controls">
                        <img id="myImg" src="/<?php echo $img;?>" height="240" width="240">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo lang('new_user_avatar');?></label>
                    <div class="controls">
                       <!-- <input type="file" name="mypic">-->
                        <input type="file" name="userfile" size="20" />
                        <p class="help-block">
                            <?php echo lang('upload_tip');?>
                        </p>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="button" class="btn btn-primary" id="upload"><?php echo lang('upload');?></button>
                    </div>
                </div>
            </form>
            <form action="<?php echo base_url('ucenter/do_avatar/cropped') ?>" method="post" class="form-horizontal hidden" id="final_form" onsubmit="return checkCoords()">
                <div id="showimg"><!--初始图片--></div>
                <div class="help-block"><?php echo lang('cropped_tip');?></div>
                <input type="hidden" id="src" name="src" value="" />
                <input type="hidden" id="x" name="x" value="0" />
                <input type="hidden" id="y" name="y" value="0" />
                <input type="hidden" id="w" name="w" value="0" />
                <input type="hidden" id="h" name="h" value="0" />
                <div style="margin-top:25px ">
                <button id="cropped" class="btn btn-fat btn-primary" type="button"><?php echo lang('save');?></button>
                <a class="go-back btn btn-link" onclick="cropped_back()" rel=""><?php echo lang('reselect');?></a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("button[name='account_info']").click(function () {
            var curEle = $(this);
            var oldSubVal = curEle.text();
            curEle.html($('#loadingTxt').val());
            curEle.attr("disabled","disabled");
            $.ajax({
                type: "POST",
                url: "/ucenter/account_info/editUser",
                data: $('#account_info').serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('.account_info').removeClass('hidden');
                        $('.account_info').addClass('alert-success');
                        $('.alert strong').html(data.msg);
                    } else {
                        $('.account_info').removeClass('hidden');
                        $('.account_info').addClass('alert-error');
                        $('.account_info strong').html(data.msg);
                    }
                    hiddenAlert($('.account_info'),5000)
                    curEle.html(oldSubVal);
                    curEle.attr("disabled",false);
                }
            });
        });


        $("#upload").click(function(){
            var bar = $('.bar');
            var percent = $('.percent');
            var showimg = $('#showimg');
            var progress = $(".progress");
            var btn = $("#upload");
            var oldSubVal = btn.text();
            $('#upload_form').ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    btn.html($('#loadingTxt').val());
                    btn.attr("disabled","disabled");
                },
                success: function(data) {

                    if(data.success == 0){
                        $('.cropped').removeClass('hidden');
                        $('.cropped strong').html(data.upload_data);
                        hiddenAlert($('.cropped'),10000);
                    }else{
                        //显示上传后的图片
                        var img = data.path;
                        showimg.html("<img src='/"+img+"' id='cropbox' />");
                        $('#final_form').removeClass('hidden');
                        $('#upload_form').addClass('hidden');
                        //传给php页面，进行保存的图片值
                        $("#src").val(img);
                        $("a.go-back").attr('rel',img);
                        //截取图片的js
                        $('img#cropbox').imgAreaSelect({
                            handles: true,
                            maxHeight:200,
                            maxWidth:300,
                            onSelectEnd: updateCoords,
                            x1: 0, y1: 0, x2: 200, y2: 150
                        });
                    }
                    btn.html(oldSubVal);
                    btn.attr("disabled",false);
                },
                error:function(xhr){	//上传失败
                    btn.html($('#loadingTxt').val());
                    btn.attr("disabled","disabled");
                }
            });
        });

        $("#cropped").click(function(){
            $('img#cropbox').imgAreaSelect({
                hide:true
            });
            $('#final_form').ajaxSubmit({
                dataType:  'json',
                success: function(data) {
                    if(data.success){
                        $('#upload_form').removeClass('hidden');
                        $('#final_form').addClass('hidden');
                        $('#myImg')[0].src = '/' + data.img;
                    }else{
                        $('.cropped').removeClass('hidden');
                        $('.cropped strong').html('Cropped Failure!');
                    }
                }
            });
        });
    });
        function cropped_back(){
            var pic = $("a.go-back").attr("rel");
            $.post("/ucenter/do_avatar/avatar",{ act : 'del' , path : pic },function(data){
                $('img#cropbox').imgAreaSelect({
                    hide:true
                });
                $('#upload_form').removeClass('hidden');
                $('#final_form').addClass('hidden');
            });
        }

        function updateCoords(img,c){
            $('#x').val(c.x1);
            $('#y').val(c.y1);
            $('#w').val(c.width);
            $('#h').val(c.height);
        }

        function checkCoords(){
            if (parseInt($('#w').val())) return true;
            alert('Please select a crop region then press submit.');
            return false;
        }
</script>