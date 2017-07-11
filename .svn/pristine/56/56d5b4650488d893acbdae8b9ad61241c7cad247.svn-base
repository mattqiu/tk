<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo lang('tps138_admin');?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="<?php echo base_url('js/jquery-1.11.2.min.js')?>"></script>
    <script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
    <style>
        .well{background-color: #fff}
        .title{font-size: large}
    </style>
</head>
<body>
<div class="well">
    <div style="margin: 10px;">
        <div><label class="title"><?php echo lang('log_info'); ?></label></div>
        <div><span><?php echo lang('tickets_take_time'); ?>:</span>
             <span style="color: #00AA66;margin-bottom: 3px;"><?php
                 if($log){
                     $end_date = end($log)['create_time'];
                     $start_date = $log[0]['create_time'];
                     $date=floor((strtotime($end_date)-strtotime($start_date))/86400);
                     $hour=floor((strtotime($end_date)-strtotime($start_date))%86400/3600);
                     $minute=floor((strtotime($end_date)-strtotime($start_date))%86400%3600/60);
                     $second=floor((strtotime($end_date)-strtotime($start_date))%86400%3600%60%60);
                     echo $date.lang('day').$hour.lang('hour').$minute.lang('minute').$second.lang('second');
                 }
                 ?></span>
        </div>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th><?php echo lang('tickets_id'); ?></th>
            <th><?php echo lang('time'); ?></th>
            <th><?php echo lang('tickets_handler'); ?></th>
            <th><?php echo lang('modified_type'); ?></th>
            <th><?php echo lang('old_data'); ?></th>
            <th><?php echo lang('new_data');?></th>
        </tr>
        </thead>
        <tbody>
        <?php if($log){
            foreach($log as $v){?>
        <tr>
                <td style="font-weight: bold">#<?php echo $v['tickets_id']; ?></td>
                <td><?php echo $v['create_time']; ?></td>
                <td><?php echo $v['email']?$v['email']:$v['admin_id']; ?></td>
                <td><?php if(isset(config_item('status_data_type')[$v['data_type']])){echo lang(config_item('status_data_type')[$v['data_type']]);} ?></td>
                <?php switch($v['data_type']){
                    case 0:{ ?>
                        <td>0</td>
                        <td><?php echo $v['tickets_id']; ?></td>
                    <?php  break; }
                    case 1:{ ?>
                        <td> <?php if($v['old_data']!=100){echo lang(config_item('tickets_status')[$v['old_data']]);}?></td>
                        <td> <?php if($v['new_data']!=100){echo lang(config_item('tickets_status')[$v['new_data']]);}?></td>
                   <?php  break; }
                    case 2:{ ?>
                        <td><?php if($v['old_data']!=100){ echo lang(config_item('tickets_priority')[$v['old_data']]);}?></td>
                        <td><?php if($v['new_data']!=100){echo lang(config_item('tickets_priority')[$v['new_data']]);} ?></td>
                   <?php  break; }
                    case 3:{ ?>
                        <td><?php echo $v['old_data']; ?></td>
                        <td><?php echo $v['new_data']; ?></td>
                    <?php  break; }
                    case 4:{ ?>
                        <td><?php if($v['old_data']!=100){echo lang(config_item('tickets_status')[$v['old_data']]);}?></td>
                        <td><?php if($v['new_data']!=100){echo lang(config_item('tickets_status')[$v['new_data']]);}?></td>
                    <?php  break; }
                    case 5:{ ?>
                        <td><?php //echo $v['old_data']; ?></td>
                        <td><?php //echo $v['new_data']; ?></td>
                    <?php  break; }
                    case 6:{ ?>
                        <td><?php if($v['old_data']!=100){echo lang(config_item('tickets_status')[$v['old_data']]);}?></td>
                        <td><?php if($v['new_data']!=100){echo lang(config_item('tickets_status')[$v['new_data']]);}?></td>
                    <?php  break; }
                    case 7:{ ?>
                        <td><?php if($v['old_data']!=100){echo lang(config_item('tickets_status')[$v['old_data']]);}?></td>
                        <td><?php if($v['new_data']!=100){echo lang(config_item('tickets_status')[$v['new_data']]);}?></td>
                    <?php  break; }
                    case 8:{ ?>
                        <td><?php //echo $v['old_data']; ?></td>
                        <td><?php //echo $v['new_data']; ?></td>
                    <?php  break; }
                    case 9:{ ?>
                        <td><?php if($v['old_data']!=100){echo lang(config_item('tickets_status')[$v['old_data']]);}?></td>
                        <td><?php if($v['new_data']!=100){echo lang(config_item('tickets_status')[$v['new_data']]);}?></td>
                    <?php  break; }
                    case 10:{ ?>
                        <td><?php if($v['old_data']!=100){echo lang(config_item('tickets_problem_type')[$v['old_data']]);}?></td>
                        <td><?php if($v['new_data']!=100){echo lang(config_item('tickets_problem_type')[$v['new_data']]);}?></td>
                        <?php  break; }
                    case 11:{ ?>
                        <td><?php //echo $v['old_data']; ?></td>
                        <td><?php //echo $v['new_data']; ?></td>
                        <?php  break; }
                    case 12:{ ?>
                        <td><?php //echo $v['old_data']; ?></td>
                        <td><?php //echo $v['new_data']; ?></td>
                        <?php  break; }
                    case 13:{ ?>
                        <td><?php //echo $v['old_data']; ?></td>
                        <td><?php //echo $v['new_data']; ?></td>
                        <?php  break; }
                    default :{
                        break;
                    }
                }?>
        </tr>
        <?php
            }
        }?>
        </tbody>
    </table>
    </div>
</div>
</body>