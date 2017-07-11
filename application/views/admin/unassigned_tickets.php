<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/searchableSelect/searchableSelect.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/searchableSelect/searchableSelect.css'); ?>">
<div class="search-well">
    <form class="form-inline" method="GET">
        <input autocomplete="off" value="<?php if(isset($searchData['keywords'])){echo $searchData['keywords'];} ?>" name="keywords" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control tickets_input_box_trim" id="title_keywords" placeholder="<?php echo lang('pls_t_tid_uid'); ?>" />
        <select name="type" id="com_type" class="com_type">
            <option value=""><?php echo lang('type'); ?></option>
                <?php foreach (config_item('tickets_problem_type') as $key => $value) { ?>
                    <option value="<?php echo $key ?>"
                       <?php if ($searchData['type'] >= '0' && $key == $searchData['type']) {
                         echo " selected=selected";
                       }
                       ?> >
                    <?php echo lang($value); ?>
                    </option>
                <?php } ?>
        </select>
        <select name="language" id="com_type" class="com_type">
            <option value=""><?php echo lang('tickets_language'); ?></option>
            <?php foreach ($language_all as $key => $value) { ?>
                <option value="<?php echo $value['language_id']; ?>"
                    <?php if ($searchData['language'] >= '0' && $value['language_id'] == $searchData['language']) {
                    echo " selected=selected";
                }
                ?> >
                    <?php echo $value['name']; ?>
                </option>
            <?php } ?>
        </select>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>

    <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
    <div>
<!--        <button class="btn" id="auto_assign_zh" type="button">--><?php //echo '分配全部中文'; ?><!--</button>-->
    </div>
    <?php } ?>

    <?php if(in_array($adminInfo['id'],array(68,144))){ ?>
        <div>
<!--            <button class="btn" id="auto_assign_kr" type="button">--><?php //echo '分配全部韩文'; ?><!--</button>-->
        </div>
    <?php } ?>

</div>

<div class="well">
    <form method="post" id='form1' action=""  name="listForm">
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <?php if(check_right('tickets_assign_right') || $cus_role==2 || check_right('unallocated_tickets_assign_right')) { ?>
                <th><label style='display: inline'><input type='checkbox' class='all_check_input' onclick='check_all(this)' autocomplete='off'/><?php echo lang('all'); ?></label></th>
                <?php }?>
                <th><?php echo lang('tickets_id'); ?></th>
            	<th>
                    <?php echo lang('id');?>
                    <a href="<?php echo base_url($order_url.'&order_by=id-a')?>"><i class="icon-arrow-up"></i></a>
                    <a href="<?php echo base_url($order_url.'&order_by=id-d')?>"><i class="icon-arrow-down"></i></a>
                </th>
                <th><?php echo lang('tickets_type'); ?></th>
                <th><?php echo lang('tickets_title'); ?></th>
                <th>
                    <?php echo lang('time'); ?>
                    <a href="<?php echo base_url($order_url.'&order_by_time=time-a')?>"><i class="icon-arrow-up"></i></a>
                    <a href="<?php echo base_url($order_url.'&order_by_time=time-d')?>"><i class="icon-arrow-down"></i></a>
                </th>
                <th><?php echo lang('status'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr class="one_tickets <?php echo $item['id'].'t'; ?>">
                    <?php if(check_right('tickets_assign_right') || $cus_role==2 || check_right('unallocated_tickets_assign_right')) { ?>
                    <td class="modal_main">
                        <input type="checkbox" class="checkbox" name="checkboxes[]" onclick='chb_is_checked()' value="<?php echo $item['id'] ?>" autocomplete="off">
                    </td>
                    <?php }?>
                    <td style="font-weight: bold">#<?php echo $item['id'];  ?></td>
                	<td>
                        <?php if(!empty($black) && in_array($item['uid'],$black)){echo lang('tickets_black');} ?><?php echo $item['uid'];?>
                    </td>
                    <td><?php if(isset(config_item('tickets_problem_type')[$item['type']])){echo lang(config_item('tickets_problem_type')[$item['type']]);} ?>
						<?php if($item['is_attach']){ ?>
							<img src="<?php echo base_url('img/huixing.png')?>" width="18px" >
						<?php }else{echo "&nbsp;&nbsp;&nbsp;&nbsp;";}?>
					</td>
                    <td class="tips_show"><a href="javascript:see_ticket_detail(<?php echo $item['id']?>);"><?php echo mb_substr($item['title'],0,15,'utf-8');if(mb_strlen($item['title'],'utf8')>15){echo '...';};if(!$item['title']){echo 'TPS';}?></a></td>
                    <td class="cont" style="display: none"><?php echo mb_substr($item['content'],0,100,'utf-8');if(mb_strlen($item['content'],'utf8')>100){echo '...';};  ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                    <td><?php echo lang(config_item('tickets_status')[$item['status']]) ?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
        <?php if(check_right('tickets_assign_right') || $cus_role==2 || check_right('unallocated_tickets_assign_right')) { ?>
        <div style="float:left; margin-left: -22px;margin-top: 36px;">
            <select id="cus" name="cus">
                <option value="" ><?php echo lang('tickets_assign')?></option>
                <?php if(!empty($cus)){ foreach($cus as $c){?>
                <option value="<?php echo $c['id']; ?>" ><?php echo $c['job_number'].' ('.explode('@',$c['email'])[0].')';?></option>
                <?php } }?>
            </select>
            <input style="margin-bottom: 0;" id="btn_type" class="btn btn-primary" name="btn_type" type="submit" value="<?php echo lang('submit');?>" onclick="return return_batch_transfer()" disabled autocomplete="off"/>
        </div>
        <?php }?>
</form>
</div>
<div style="float: left;clear: both">
    <?php echo $pager;?>
</div>
<?php if(check_right('tickets_assign_right') || $cus_role==2 || check_right('unallocated_tickets_assign_right')){ ?>
<div style="height: 20px;width: 2px;clear: both"></div>
<div style="clear: both;">
    <div>
        <div style="float: left;"><span><input class="auto_assign" style="margin-top: -5px;" type="radio" name="assign" value="no" <?php if(!empty($auto_status) && $auto_status['value']=='no'){echo 'checked';} ?>/></span><span><?php echo lang('manual_work'); ?></span></div>
        <div style="float: left;margin-left: 20px;margin-bottom: 6px;"><span><input class="auto_assign" style="margin-top: -5px;" type="radio" name="assign" value="yes" <?php if(!empty($auto_status) && $auto_status['value']=='yes'){echo 'checked';} ?>/></span><?php echo lang('automatic'); ?></div>
    </div>
   <div class="auto_confirm" style="float:left;margin-left: 10px;margin-top: -5px;<?php if(!empty($auto_status) && $auto_status['value']=='no'){echo 'display:none;';}?>"><input <?php if(empty($list)){echo 'disabled';} ?> style="padding: 3px 6px;" id="btn_type" class="btn btn-primary auto_assign_confirm" name="btn_type" type="button" value="<?php echo lang('confirm');?>"></div>
</div>
<div class="cus_box" style="width: 100%;">
        <?php
        if(!empty($cus)){?>
         <?php for($a=0;$a<5;$a++){
                        $tempCus = array();
                        $lang[$a]    = '';
                        foreach($cus as $v){
                            if($v['job_number']<=800 && $v['job_number']>=200){
                                $tempCus[0][]   = $v;
                                $lang[0]        = lang('not_customer');
                            }elseif($v['job_number']<200 && $v['area']==1){
                                $tempCus[1][]   = $v;
                                $lang[1]        = lang('tickets_area_usa');
                            }elseif($v['job_number']>800 && $v['area']==2){
                                $tempCus[2][]   = $v;
                                $lang[2]        = lang('tickets_area_china');
                            }elseif($v['job_number']>800 && $v['area']==4){
                                $tempCus[3][]   = $v;
                                $lang[3]        = lang('tickets_area_korea');
                            }else{
                                $tempCus[4][]   = $v;
                                $lang[4]        = lang('other');
                            }
                    }
         ?>
        <table class="table table-bordered table-condensed">
        <tbody>
        <?php echo '<span style="clear: both;float: left">'.$lang[$a].'</span>'; ?>
        <?php do{ $count = 0; ?>
        <?php if(isset($tempCus[$a])){ ?>
        <tr>
           <?php  foreach($tempCus[$a] as $k=>$c){?>
                <td style="min-width: 100px;text-align: left ">
                    <input class="customer_status" type="checkbox" name="all_customer" <?php if($c['status']==1){echo 'checked'; } ?> value="<?php echo $c['id'].'|'.$c['job_number']; ?>"> <?php echo $c['job_number']; ?> : <?php if(!empty($today_result[$c['id']])){echo $today_result[$c['id']]['today_count'];}else{echo 0;}  ?>/<?php if(!empty($count_result[$c['id']])){echo $count_result[$c['id']]['all_count'];}else{echo 0;}  ?>
                </td>
            <?php $count++;unset($tempCus[$a][$k]);if($count==8){break;} ?>
            <?php }?>
            <?php if($count<8){for($i=0;$i<8-$count;$i++){ ?><td style='min-width: 100px;'></td> <?php } } ?>
        </tr>
        <?php }?>
        <?php }while(!empty($tempCus[$a])); ?>
        <?php } ?>
        <?php } ?>
        </tbody>
        </table>
</div>
<?php } ?>
<script>
    function r(){
        window.location.reload();
    }
    setInterval('r()',600000);
    $(function(){
        $('#cus').searchableSelect();
        $('.searchable-select-input').css('height','30px').css('margin-bottom','0px');
        $('.searchable-select-dropdown').css('z-index',999);
        $('.searchable-select').click(function(){
            if($('.searchable-select-input').val()==''){
                $('.searchable-select-item').removeClass('searchable-select-hide');
            }
        });
        $('footer hr').css('float','left');
        $('.one_tickets').each(function(){
            var index;
            var cont = $(this).children('.cont').text();
            $(this).children('.tips_show').mouseover(function(){
                index = layer.tips(cont, $(this), {
                    tips: [3, '#3595CC'],
                    time: 10000
                });
            }).mouseleave(function(){
                layer.close(index);
            });
        });
        $('.customer_status').change(function(){
            var work_status = 0;
            var cus_str = $(this).val();
            var cus_id  = cus_str.split('|')[0];
            var cus_num = cus_str.split('|')[1];
            if($(this).attr('checked')=='checked'){
                work_status = 1;
            }else{
                work_status = 2;
            }
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/unassigned_tickets/change_cus_work_status') ?>",
                dataType: "json",
                data:{c_id:cus_id,w_status:work_status,c_num:cus_num},
                success: function (res) {
                    if(res.success==1){
                        layer.msg(res.msg);
                        //window.location.reload();
                    }
                    if(res.success==0){
                        layer.msg(res.msg);
                    }
                }
            });
        });
        $('.auto_assign').change(function(){
            if($(this).attr('checked')=='checked'){
                if($(this).val()=='no'){
                    $('.auto_confirm').hide();
                }else{
                    $('.cus_box,.auto_confirm').show();
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/unassigned_tickets/auto_assign_status') ?>",
                    dataType: "json",
                    data:{val:$(this).val()},
                    success: function (res) {
                        if(res.success==1){
                            layer.msg(res.msg);
                            //window.location.reload();
                        }
                        if(res.success==0){
                            layer.msg(res.msg);
                        }
                    }
                });
            }
        });
        $('.auto_assign_confirm').click(function(){
            if(!window.confirm("<?php echo lang('sure');?>")){return;}
            $(this).attr('disabled',true);
            var index = layer.load(1, {shade: [0.1,'#fff'] });
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/unassigned_tickets/auto_assign_tickets') ?>",
                dataType: "json",
                success: function (res) {
                    if(res.success==1){
                        layer.close(index);
                        layer.msg(res.msg);
                        $(this).attr('disabled',false);
                        window.location.reload();
                    }
                }
            });
        });

        $('#auto_assign_zh').click(function(){
            if(!window.confirm("<?php echo lang('sure');?>")){return;}
            $(this).attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/unassigned_tickets/auto_assign_tickets_zh') ?>",
                dataType: "json",
                success: function (res) {
                    if(res.success==1){
                        layer.msg(res.msg);
                    }
                }
            });
        });

        $('#auto_assign_kr').click(function(){
            if(!window.confirm("<?php echo lang('sure');?>")){return;}
            $(this).attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/unassigned_tickets/auto_assign_tickets_kr') ?>",
                dataType: "json",
                success: function (res) {
                    if(res.success==1){
                        layer.msg(res.msg);
                    }
                }
            });
        });

    });
function see_ticket_detail(id){
    layer.open({
			type: 2,
			area: ['40%', '70%'],
			fix: true, //不固定
			maxmin:true,
            shadeClose:true,
			scrollbar: false,
			title:"<?php echo lang('admin_as_update')?>",
			content: '<?php echo base_url('admin/unassigned_tickets/get_unassigned_tickets_info'); ?>/' + id,
		});
}

function assign_ticket(id){
	 $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/unassigned_tickets/assign_tickets') ?>",
        dataType: "json",
        data:{id:id},
        success: function (res) {
            if(res.success==1){
            	layer.msg(res.msg);
            	window.location.reload();
            }else{
            	layer.msg(res.msg);
            }
        }
    });
}

function return_batch_transfer(){
        var batch = document.getElementById('cus').value;
        if(!batch){
        	layer.alert('<?php echo $pls_select_customer; ?>', {
      		  icon: 3,
      		  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
      		})
            return false;
        }

        var action = '/admin/unassigned_tickets/batch_transfer';
        if(window.confirm("<?php echo lang('sure')?>")){
            $("#form1").attr("action", action);
            $("#form1").submit(function(){
                var li ;
                $(this).ajaxSubmit({
                    clearForm :true,
                    resetForm :true,
                    dataType:'json',
                    success:function(res){
                        //layer.close(li);
                    },
                    error:function(){
                        layer.close(li);
                    },
                    beforeSend:function(){
                        li = layer.load();
                    },
                    complete:function(){
                    }
                });
                return false;
            });
        }else{
            return false;
        }
    }

function check_all(all){
        var ips = document.getElementsByTagName('input');
        for( var i=0;i<ips.length;i++ ){
            if( ips[i].type == 'checkbox' && !ips[i].disabled && ips[i].name == 'checkboxes[]' )
                ips[i].checked = all.checked;
        }
        chb_is_checked();
    }
function chb_is_checked(){
        var is_checked = false;
        var ips = document.getElementsByTagName('input');
        for( var i =0;i<ips.length;i++ ){
            if( ips[i].type=="checkbox" && !ips[i].disabled && ips[i].checked && ips[i].name == 'checkboxes[]' ){
                is_checked = true;
                break;
            }
        }
        var batch_status = document.getElementById('cus');
        if( is_checked ){
            if(batch_status != null){
                document.getElementById('cus').disabled = '';
                document.getElementById('btn_type').disabled = '';
            }
        } else {
            if(batch_status != null){
                document.getElementById('cus').disabled = 'disabled';
                document.getElementById('btn_type').disabled = 'disabled';
            }
        }
    }
</script>

