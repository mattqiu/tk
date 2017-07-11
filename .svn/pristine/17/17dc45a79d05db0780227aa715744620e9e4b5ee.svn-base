<style type="text/css">
/*.search-well{ padding-top:40px;}*/
    .time_input{width: 170px;}
</style>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<div class="search-well">
    <form class="form-inline" method="GET">
    	<input type="text" class="span2" placeholder="<?php echo lang('id') ?>" maxlength="10" name="uid" value="<?php echo $searchData['uid'];?>" />
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
       
    </form>

</div>
<?php if(check_right('order_repair_of_comm_right')){ ?>
<div class="well" style="padding-bottom: 0;">
    <form id="order_comm">
    <input autocomplete="off" value="" name="comm_uid" maxlength="10" type="text" style=""  placeholder="<?php echo lang('member_id'); ?>" />
    <input style="width: 186px;" class="Wdate span2 time_input" type="text" name="comm_date" value="" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('select_order_repair_date') ?>">
    <select name="comm_type" id="comm_type" class="com_type input-medium">
        <option value=""><?php echo lang('select_comm_order_type'); ?></option>
        <?php foreach (config_item('commission_type_for_order_repair') as $key => $value) { ?>
            <option value="<?php echo $key ?>"><?php echo lang($value); ?></option>
        <?php } ?>
    </select>
    <button class="btn" id="comm_btn" type="button"><?php echo lang('add') ?></button>
    </form>
</div>
<?php }?>
<input type="hidden" id="url" value="<?php echo base_url('admin/email/eamil_info'); ?>" />
<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th style="padding:0 20px"><?php echo lang('id'); ?></th>
                <th style="padding:0 20px"><?php echo lang('repair_order_year_month'); ?></th>
                <th style="padding:0 20px"><?php echo lang('commission_type'); ?> (<?php echo lang('comm_date'); ?>)</th>
                <th style="padding:0 20px"><?php echo lang('commission_withdraw_amount'); ?><br/>(<?php echo lang('if_not_repair_order_before_deadline')?>)</th>
                <th style="padding:0 20px"><?php echo lang('sale_amount_lack') ?></th>
                <th style="padding:0 20px"><?php echo lang('deadline'); ?>（<?php echo lang('day')?>）</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="tbody">
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid'];?></td>
                    <td><?php echo substr($item['order_year_month'],0,4).'-'.substr($item['order_year_month'],4); ?></td>
                    <td>
                        <?php echo lang(config_item('commission_type_for_order_repair')[$item['comm_type']]);?>
                        （<?php echo substr($item['comm_year_month'],0,4).'-'.substr($item['comm_year_month'],4); ?>）
                    </td>
                    <td><?php echo $item['comm_amount']?'$ '.$item['comm_amount']/100:''; ?></td>
                    <td><?php echo '$ '.$item['sale_amount_lack']/100; ?></td>
                    <td>
                        <input type='hidden' value='<?php echo $item['uid'].'|'.$item['comm_type'].'|'.$item['comm_year_month'] ?>'>
                        <span class="modify_deadline_day mdifiable"><?php echo 14-computer_flow_days($item['create_time']); ?></span>
                    </td>
                    <td><a class="del_comm" val="<?php echo $item['uid'].'|'.$item['comm_type'].'|'.$item['comm_year_month']; ?>" href="#"><?php echo lang('delete'); ?></a></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager; ?>
<script>
    $(function(){
        $(".del_comm").click(function(){
            if(!window.confirm("<?php echo lang('sure');?>")){return;}
            var k = $(this).attr('val');
            $.ajax({
                type: "POST",
                url: "/admin/order_repair_of_comm/del_comm",
                data: {id:k},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        layer.msg(data.msg);
                        window.location.reload();
                    }else{
                        layer.msg(data.msg);
                    }
                }
            });
        });

        $("#comm_btn").click(function () {
            $("#comm_btn").attr("disabled","disabled");
            var str = '';
            var sign = true;
            $.ajax({
                type: "POST",
                url: "/admin/order_repair_of_comm/check_comm",
                data: $('#order_comm').serialize(),
                dataType: "json",
                async: false,
                success: function (data) {
                    if (data.success) {
                        str = data.str;
                    }else{
                        str = data.str;
                        sign = false;
                    }
                    //$("#comm_btn").attr("disabled",false);
                }
            });
            layer.open({
                type: 1
                ,offset: 't'
                ,content: str
                ,area: ['390px', '330px']
                ,title: "<?php echo lang('comm_tips'); ?>"
                ,btn: ["<?php echo lang('confirm'); ?>","<?php echo lang('cancel'); ?>"]
                ,btnAlign: 'c'
                ,shade: 0
                ,yes: function(){
                    layer.closeAll();
                    if(sign){
                        $.ajax({
                            type: "POST",
                            url: "/admin/order_repair_of_comm/add_comm",
                            data: $('#order_comm').serialize(),
                            dataType: "json",
                            success: function (data) {
                                if (data.success) {
                                    layer.msg(data.msg);
                                    setTimeout(function(){
                                        window.location.reload();
                                    },2000);
                                } else {
                                    layer.msg(data.msg);
                                }
                                $("#comm_btn").attr("disabled",false);
                            }
                        });
                    }else{
                        $("#comm_btn").attr("disabled",false);
                    }
                }
                ,cancel:function(){
                    $("#comm_btn").attr("disabled",false);
                }
            });
        });
    });
</script>
