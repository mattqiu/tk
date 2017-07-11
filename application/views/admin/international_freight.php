<style>
    .tab_area tr{ height: 40px; margin-left: 20px;}
	.form-inline label{ width: 60px;}
</style>
<form id="add_freight_form">
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
    <input type="text" class="input_sku" name="sku" placeholder="<?php echo lang(("goods_sku"))?>">
    <span id="show_goods_name"></span>

    <br>
    <br>

    <label style="color: #802420"><?php echo lang('please_input_freight_usd')?></label>
    <table class="tab_area">
        <?php foreach($country_map as $code =>$name){?>
            <tr>
                <td><label><?php echo $name?></label></td>
                <td width="30"></td>
                <td><input type="text" name="<?php echo $code?>" value="0"></td>
            </tr>
        <?php }?>
    </table>
    <br>
    <input type="button" autocomplete="false" class="btn btn-primary" id="submit_freight" value="<?php echo lang('submit');?>">
</form>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="goods_sn_main" value="<?php echo $searchData['goods_sn_main'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('goods_sku')?>">

        <select name="country_id">
            <option value=""><?php echo lang("all_country")?></option>
            <?php foreach ($country_map as $k => $item){?>
                <?php if($k == $searchData['country_id']){?>
                    <option selected value="<?php echo $k?>"><?php echo $item?></option>
                <?php }else{?>
                    <option value="<?php echo $k?>"><?php echo $item?></option>
                <?php }?>
            <?php } ?>
        </select>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('goods_sku'); ?></th>
            <th><?php echo lang('country'); ?></th>
            <th><?php echo lang('admin_order_deliver_fee'); ?></th>
            <th><?php echo lang('role_super'); ?></th>
            <th><?php echo lang('create_time'); ?></th>
            <th><?php echo lang('action'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['goods_sn_main'] ?></td>
                    <td><?php echo $country_map[$item['country_id']] ?></td>
                    <td><?php echo "$".$item['freight_fee'] ?></td>
                    <td><?php echo $item['email'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                    <td>
                    	<a class="btn btn-primary" onclick="findOne('<?php echo $item['Id'] ?>');" href="javascript:"><?php echo lang('admin_as_update') ?></a>
                    	<a class="btn btn-primary" onclick="deletes('<?php echo $item['Id'] ?>');" href="javascript:"><?php echo lang('label_goods_delete') ?></a>
                    </td>
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
<input type="hidden" id="country" value=""  />
<input type="hidden" id="freId" value=""  />
<?php echo $pager;?>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script type="text/javascript">

//获取修改的一条记录
function findOne(id)
{
	  $.ajax({
                success: "success",
                url: "/admin/international_freight/do_modify_getone",
                dataType: "json",
                type: "post",
                data: {id: id},
                success: function (res) {
                    if (res.success) {
						//$('#typeok').val(res.success);
                        $('.goods_sku').val(res.one.goods_sn_main);
						$('#country').val(res.one.country_id);
						$('#freId').val(res.one.Id);
                        $('.label_country').val(res.one.country_name)
                        $('.admin_order_deliver_fee').val(res.one.freight_fee);
						return false;
                    } else {
						layer.msg(res.msg);
						return false;
                    }
                }
            });

	var deliver_box_cont = '';
	deliver_box_cont += '<form style="margin: 20px;" class="form-inline"><div class="input-append">';
	deliver_box_cont += '<label><?php echo lang('goods_sku'); ?>:</label><input type="text" class="goods_sku" readOnly="true" id="goods_sku" placeholder="" /><br/>';
	deliver_box_cont += '<label><?php echo lang('label_country'); ?>:</label><input type="text" class="label_country" readOnly="true" id="label_country" placeholder="" /><br/>';
	deliver_box_cont += '<label><?php echo lang('admin_order_deliver_fee'); ?>:</label><input type="text" class="admin_order_deliver_fee" id="admin_order_deliver_fee" placeholder="" /><br/>';
	deliver_box_cont += '<label></label><input type="hidden" id="deliver_box_order_id" value="' + id + '" />';
	deliver_box_cont += '<button type="button" stype="width:50px;" class="btn" onclick="modify();"><?php echo lang('ok'); ?></button>';
	deliver_box_cont += ' <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>';
	deliver_box_cont += '</div></form>';

	$.thinkbox(
		deliver_box_cont,
		{
			'title': ' <?php echo lang('admin_as_update'); ?>'+ '商品跨区运费',
		}
	);
		
}

//修改一条记录
function modify(){
	var sku = $(".goods_sku").val();
	var country = $("#country").val();
	var id = $("#freId").val();
	var admin_order_deliver_fee = $(".admin_order_deliver_fee").val();
	$.ajax({
                success: "success",
                url: "/admin/international_freight/do_modify_freight",
                dataType: "json",
                type: "post",
                data: {
					    id:id,
						sku:sku,
						country:country,
						admin_order_deliver_fee:admin_order_deliver_fee
					},
                success: function (res) {
                    if (res.success) {
                        location.reload('');
						layer.msg(res.msg);
						return false;
                    } else {
						layer.msg(res.msg);
						return false;
                    }
                }
            });
	}


//删除一条记录
function deletes(id){
	
				layer.confirm("<?php echo lang('is_delete'); ?>", {
					icon: 3,
					title: "<?php echo lang('delete_ok'); ?>",
					closeBtn: 2,
					btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
				}, function(index){

                    var remark = $('#cancel_remark').val();
					var refund_type = $("input[name='refund_type']:checked").val()?$("input[name='refund_type']:checked").val():'';
					//layer.close(index);
					$.ajax({
		url: '/admin/international_freight/do_delete_freight',
		type: "POST",
		data: {'id': id},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				location.reload('');
				layer.msg(data.msg);
				return false;
			}else {
				location.reload('');
				layer.msg(data.msg);
				return false;
               }
					
					},
		error: function(data) {
			console.log(data.responseText);
		}
	});
					
				});
				
				//
		
	
	//
}
//***********//
    var flag;

    $("#submit_freight").click(function(){
        var oldVal=$('#submit_freight').val();
        $('#submit_freight').attr("value", $('#loadingTxt').val());
        $('#submit_freight').attr("disabled", 'disabled');

        $.ajax({
            type: "POST",
            url: "/admin/international_freight/do_add_freight",
            data: $('#add_freight_form').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#submit_freight').attr("value", oldVal);
                    $('#submit_freight').attr("disabled", false);
                    layer.msg(res.msg);
                    location.reload();
                } else {
                    $('#submit_freight').attr("value", oldVal);
                    $('#submit_freight').attr("disabled", false);
                    layer.msg(res.msg);
                }
            }
        });
    })


    //输入sku显示商品名
    $(function(){
        $(".input_sku").keyup(function(){

            //清除间隔时间不足一秒的按键事件
            clearTimeout(flag);

            flag = setTimeout(function(){
                $.ajax({
                    type: "POST",
                    url: "/admin/international_freight/show_goods_name",
                    data: {sku:$('.input_sku').val()},
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            $("#show_goods_name").css('color','green');
                            $("#show_goods_name").text(res.msg);
                        } else {
                            $("#show_goods_name").css('color','red');
                            $("#show_goods_name").text(res.msg);
                        }
                    }
                });
            },500)
        });
    });
</script>