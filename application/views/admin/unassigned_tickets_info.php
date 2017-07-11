<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo lang('tps138_admin');?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">

    <style>
        .msg_box{padding: 10px 0px;background-color: #fff;}
        .well{margin-bottom: 0px;padding: 0px;box-shadow: none;}
        .tickets_title{width: 100%;}
        .msg_cont{background-color: #d8e9d1;overflow: hidden;border: none;margin: 0px 4px;min-height: 120px;word-wrap:break-word;}
        .span_s{color: #0e90d2;overflow: hidden;}
        .attach_box{margin-top: 2px;}
    </style>
</head>

<body>

<div class="box"></div>
<div class="w100" style="margin:20px 20px;">
    <div class="well">
        <div class="msg_box">
        <?php if(isset($row) && !empty($row)){?>
            <div style="margin-left: 3px;">
                 <div class="tickets_title">
                <span><?php echo lang('tickets_title');?>:&nbsp;</span>
                <span class="span_s"><?php echo $row['title']?></span>
                </div>
                <div>
                <span>
            <?php
            if($row['sender']==0){
                echo lang('tickets_sender');?>:&nbsp;
                </span>
                <span class="span_s">
                <?php
                    echo $row['uid'].'('.$row['user_name'].')';
                }?>
                </span>
                </div>
                <div>
                    <span><?php echo lang('time')?>:&nbsp;</span>
                    <span class="span_s"><?php echo $row['create_time']?></span>
                </div>
                <hr style="margin-top: 2px;">
            </div>
                <div  class="well msg_cont"><p><?php echo str_replace("&nbsp;"," ",$row['content']);?></p></div>
            <div class="attach_box">
                <?php if(isset($row['attach']) && !empty($row['attach'])){
                    foreach($row['attach'] as $item){
                        if(in_array($item['extension'], config_item('tickets_attach_is_picture'))){ ?>
						<a data-lightbox="pic-<?php echo $row['id']?>" href="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image-link" rel="" fullname="<?php echo $item['name']; ?>">
                            <img alt="<?php echo lang('picture_not_exist'); ?>" src="<?php echo config_item('img_server_url').'/'.$item['path_name'] ?>" class="example-image"></a>
					<?php }?>
					<?php if(!in_array($item['extension'], config_item('tickets_attach_is_picture'))){
							echo '<a href='.base_url('admin/my_tickets/download_attach/'.$item['id']).'>'.$item['name'].'</a></br>';
						}?>
                <?php }?>
                <?php }?>
            </div>
                <hr>

            <?php if(check_right('tickets_assign_right') || check_right('unallocated_tickets_assign_right')){ ?>
                <span style="margin-left: 3px;">
            	<?php echo lang('tickets_assign');?>&nbsp;
                <select class="transfer" name="transfer" style="width: 100px;">
                    <option value=""><?php echo lang('pls_select_customer');?></option>
                    <?php if(!empty($cus)){
                        foreach ($cus as $c) { ?>
							<option value="<?php echo $c['id']; ?>" ><?php echo $c['job_number'].' ('.explode('@',$c['email'])[0].')';?></option>
                        <?php } ?>
                    <?php }?>
                </select>
            </span>
           <?php }?>
        <?php }?>
        </div>
    </div>
</div>

<script src="<?php echo base_url('js/jquery-1.11.2.min.js')?>"></script>
<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
</body>

<script>
$(function(){
	var value2 = 0;
    $(".lb-nav").rotate({
        bind:{
              click : function() {
              value2 +=90;
              if(value2 > 360){
                value2 = 90;
                }
              $(this).prev().rotate({angle:45,animateTo:value2});
              $('.lb-dataContainer').css({width:'70%'});
              }
            },
        });
    $("[rel=tooltip]").tooltip();
});


    //to other
    $('.transfer').change(function(){
        var val = $(this).val();
        var id = <?php echo $row['id'];?>;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('admin/unassigned_tickets/do_transfer') ?>",
            dataType: "json",
            data:{id:<?php echo $row['id'];?>,c_id:val},
            success: function (res) {
                if(res.success==1){
                    layer.msg(res.msg);
                    parent.$('.'+id+'t').empty();
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });

</script>
</html>
