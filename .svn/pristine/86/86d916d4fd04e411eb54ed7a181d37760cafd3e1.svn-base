<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
<script src="<?php echo base_url('themes/admin/searchableSelect/searchableSelect.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/searchableSelect/searchableSelect.css'); ?>">
<div class="search-well">
    <form class="form-inline" method="GET">
        <style>
            .dropdown-menu{
                min-width: 0px;
            }
        </style>
        <input type="text" name="idEmail" value="<?php echo $searchData['idEmail'];?>" class="input-medium search-query span2" placeholder="<?php echo lang('card_notice')?>">
        <select class="user_ranks_sel" name="check_status">
            <option value="5" <?php echo $searchData['check_status'] == 5 ? 'selected':''?>>----<?php echo lang('check_status');?>----</option>
            <option value="1" <?php echo $searchData['check_status'] == 1 ? 'selected':''?>><?php echo lang('pending');?></option>
            <option value="2" <?php echo $searchData['check_status'] == 2 ? 'selected':''?>><?php echo lang('approve');?></option>
            <option value="0" <?php echo $searchData['check_status'] == 0 ? 'selected':''?>><?php echo lang('refuse');?></option>
            <option value="4" <?php echo $searchData['check_status'] == 4 ? 'selected':''?>><?php echo lang('no_validate');?></option>
        </select>
        <select class="country_list" name="country_id">
            <option value="" ><?php echo lang('country')?></option>
            <?php if(!empty($country_list)){
                foreach($country_list as $k=>$val){ ?>
                    <option value="<?php echo $k; ?>" <?php if($searchData['country_id']!='' && $searchData['country_id']==$k){echo 'selected';} ?>><?php echo lang($val);?></option>
            <?php } } ?>
        </select>
        <input type="text" name="id_card_num" value="<?php echo $searchData['id_card_num'];?>" class="input-medium search-query span2" placeholder="<?php echo lang('check_card_id')?>">

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_start_time')?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_end_time')?>">

        <?php //if((!empty($adminInfo) && in_array($adminInfo['role'],array(0,2)))) { ?>
        <br>
        <input class="Wdate span2 time_input" type="text" name="check_start" value="<?php echo $searchData['check_start'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('check_time')?>">
            -
        <input class="Wdate span2 time_input" type="text" name="check_end" value="<?php echo $searchData['check_end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('check_time')?>">

            <select id="cus" name="admin_id">
                <option value="" ><?php echo lang('tickets_id')?></option>
                <option value="-1" <?php echo $searchData['admin_id'] == "-1" ? 'selected':''  ?>>System</option>

                <?php if(!empty($cus)){ foreach($cus as $c){?>
                    <?php if(!in_array($adminInfo['role'],array(0,2)) && $c['id'] != $searchData['admin_id']){ continue;}?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $searchData['admin_id'] == $c['id'] ? 'selected':''  ?> ><?php echo $c['job_number'].' ('.explode('@',$c['email'])[0].')';?></option>
                <?php } }?>
            </select>
            <br><br>
        <?php //}?>

        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('id')?></th>
                <th><?php echo lang('name') ?></th>
                <th><?php echo lang('check_card_id') ?></th>
                <?php if(isset($searchData['check_status']) && $searchData['check_status']!=2){ ?>
                    <th><?php echo lang('scan') ?></th>
                    <th><?php echo lang('scan_back') ?></th>
                <?php } ?>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('check_admin') ?></th>
                <th><?php echo lang('check_time') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['id_card_num']; ?></td>
                    <?php if(isset($searchData['check_status']) && $searchData['check_status']!=2){ ?>
                        <td>
                        <?php if ($item['id_card_scan']){ ?>
                            <a data-lightbox="pic-<?php echo $item['uid']?>" href="<?php echo config_item('img_server_url').'/'.$item['id_card_scan'] ?>" class="example-image-link" rel="<?php echo $item['id_card_num']; ?>" fullname="<?php echo $item['name']; ?>">
                                <img alt="not exist" src="<?php echo config_item('img_server_url').'/'.$item['id_card_scan'] ?>" class="example-image"></a>
                        <?php }?>
                        </td>
                        <td>
                            <?php if ($item['id_card_scan_back']){ ?>
                            <a data-lightbox="pic-<?php echo $item['uid']?>" href="<?php echo config_item('img_server_url').'/'.$item['id_card_scan_back'] ?>" class="example-image-link" rel="<?php echo $item['id_card_num']; ?>"  fullname="<?php echo $item['name']; ?>">
                                <img alt="not exist" src="<?php echo config_item('img_server_url').'/'.$item['id_card_scan_back'] ?>" class="example-image"></a>
                            <?php }?>
                        </td>
                    <?php } ?>
                    <td><?php if($item['create_time']!=0){echo date('Y-m-d H:i:s', $item['create_time']);}else{echo '';} ?></td>
                    <td><?php if($item['check_admin'] != '0'){echo $item['check_admin'];}else{echo "";} ?></td>
                    <td><?php if($item['check_time']!=0){echo date('Y-m-d H:i:s', $item['check_time']);}else{echo '';} ?></td>

                    <td class="user_status_buttons">
                        <?php if($item['check_status'] == 1){?>
                        <div class="btn-group">
                            <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo lang('action');?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- dropdown menu links -->
                                <li>
                                    <a href="##" class="approve" rel="<?php echo $item['uid']; ?>"><?php echo lang('approve');?></a>
                                </li>
                                <li>
                                    <a href="##" class="refuseInfo" rel="<?php echo $item['uid']; ?>"><?php echo lang('refuse');?></a>
                                </li>
                            </ul>
                        </div>
                        <?php }else if($item['check_status'] == 2){?>
                            <strong class="text-success" style="display: block;"> <?php echo lang('validate_success');?></strong>

                            <div class="btn-group">
                                <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                    <?php echo lang('action');?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="##" class="refuseInfo" rel="<?php echo $item['uid']; ?>"><?php echo lang('refuse');?></a>
                                    </li>
                                </ul>
                            </div>
                        <?php }else{?>
                            <?php if(!$item['check_time']){?>
                            <strong class="text-error" style="display: block;"> <?php echo lang('no_validate');?></strong>
                            <?php }else{?>
                                <a data-original-title="<?php echo $item['check_info']?>" rel="tooltip" href="##">
                                    <strong class="text-error" style="display: block;"> <?php echo lang('refuse');?></strong>
                                </a>
                                <div class="btn-group">
                                    <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                        <?php echo lang('action');?>
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <!-- dropdown menu links -->
                                        <li>
                                            <a href="##" class="approve" rel="<?php echo $item['uid']; ?>"><?php echo lang('approve');?></a>
                                        </li>
                                    </ul>
                                </div>
                            <?php }?>
                        <?php }?>
                    </td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="9" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>
<script>
    $(function(){
        $('#cus').searchableSelect();
        $('.searchable-select-input').css('height','30px').css('margin-bottom','0px');
        $('.searchable-select-dropdown').css('z-index',999);
        $('.searchable-select').click(function(){
            if($('.searchable-select-input').val()==''){
                $('.searchable-select-item').removeClass('searchable-select-hide');
            }
        });

        $('.approve').click(function(){
            var uid = $(this).attr('rel');
            $.post("/admin/check_card/approve", {uid:uid}, function (data) {
                if(data.success){
                    location.reload();
                }else{
                    layer.msg(data.msg);
                    location.reload();
                }
            },'json');
        });
        $('.refuseInfo').click(function(){
            var uid = $(this).attr('rel');
            var info =prompt("<?php echo lang('refuse_reason');?>","");
            if(info){
                $.post("/admin/check_card/refuse", {info: info,uid:uid}, function (data) {
                    if(data.success){
                        location.reload();
                    }else{
                        layer.msg(data.msg);
                        location.reload();
                    }
                },'json');
            }
        });
        var value2 = 0
       $(".lb-nav").rotate({

            bind:

            {

                click : function() {
                    value2 +=90;
                    if(value2 > 360){
                        value2 = 90;
                    }
                    //$('#lightbox').css({overflow:"hidden"});
                    $(this).prev().rotate({angle:45,animateTo:value2})
                    //$(this).parent().parent().rotate({angle: 0,animateTo:value2})
                    $('.lb-dataContainer').css({width:'70%'});
                }
            }
        });
        $("[rel=tooltip]").tooltip();
    });
</script>

