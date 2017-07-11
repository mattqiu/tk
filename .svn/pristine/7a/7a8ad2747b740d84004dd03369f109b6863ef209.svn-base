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
</head>

<body>

<div class="box"></div>
<div class="w100" style="margin:20px 20px;">
    <div class="well">
        <?php if(isset($row) && !empty($row)){?>

            <?php echo '标题：&nbsp'.$row['title']?><br>
            <?php echo '发件人:'.$row['admin_id']?>

            <div class="well" style="margin-bottom: 5px;">
                <p class="pull-left"><?php echo $row['content']?></p><br>
                <p class="pull-right"><?php echo '&nbsp 时间:'.$row['create_time']?></p>
            </div>
            <?php if(isset($row['attach']) && !empty($row['attach'])){
                foreach($row['attach'] as $item){
                    echo '<a href='.base_url('admin/my_tickets/download_attach/'.$item['id']).'>'.$item['name'].'</a></br>';
                }
            }
            ?>

            <hr>

        <?php }?>

    </div>
</div>

<script src="<?php echo base_url('js/jquery-1.11.2.min.js')?>"></script>
<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
</body>
</html>
