<div class="well">
	<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
	<form id="after_sale_form" method="post">
	<table class="upgradeTb" cellspacing="30px">
            <tr>
                <td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_id')?>:
			</td>
                <td class="content">
				<input name="id" type="text" autocomplete="off" id="id" value="<?php echo isset($as_info) ? $as_info['as_id'] : $as_id?>" readonly placeholder="" <?php echo isset($as_info) ? 'readonly' : ''?>>
				<?php if(isset($as_info)){ ?>
				<input type="hidden" name="edit_as_id" value="<?php echo $as_info['as_id']?>">
				<?php } ?>
			</td>
            </tr>
                <!-- 订单ID -->
		<tr>
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_order_id')?>:
			</td>
			<td class="content">
				<input name="order_id" type="text" autocomplete="off" id="order_id" value=""  placeholder="输入订单ID后，按回车键">
			</td>
		</tr>
                <!-- 会员ID -->
		<tr class="no_tui">
			<td class="title" style="height: 40px;">
				<?php echo lang('order_receipt_member_id')?>:
			</td>
			<td class="content">
                                <input name="uid" id="user_id" type="text" autocomplete="off" value="" readonly placeholder="">
                                <span id="store_name_span"></span>
                                <input name="name" id="store_name" type="hidden" autocomplete="off" value="" readonly placeholder="">
			</td>
			
		</tr>
                <!-- 降级等级 -->
		<tr class="demote_level">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_demote')?>:
			</td>
			<td class="content">
				<select name="demote_level">
                                    <?php foreach($level_arr as $k=>$v){ ?>
                                    <option value="<?php echo $k;?>" <?php echo isset($as_info["demote_level"]) && $as_info["demote_level"]==$k ? "selected":"";?>><?php echo $v;?></option>
                                    <?php }?>
				</select>
			</td>
			<td>
				<strong class="coupon_tip text-error"></strong>
			</td>
		</tr>
                <!-- 订单信息 -->
		<tr>
			<td class="title">
				<?php echo lang('admin_order_info')?>:
			</td>
			<td class="content">
				<div id="order_table">
					
					<table class="table">
						 
					</table>
					
				</div>
			</td>
		</tr>
                <!-- 订单发放佣金信息 -->
		<tr>
			<td class="title">
				<?php echo "订单佣金信息";?>:
			</td>
			<td class="content">
				<div id="ff_order_table">
					
					<table class="table">
						 
					</table>
					
				</div>
			</td>
		</tr>
                <tr>
                    <td class="title">
                            <?php echo "订单总金额："?>:
                    </td>
                    <td class="content">
                            <input id="dd_count" name="dd_count" type="text" autocomplete="off"  value="0"  readonly >
                    </td>
		</tr>
                
                <tr>
                    <td class="title">
                            <?php echo "取消订单总金额："?>:
                    </td>
                    <td class="content">
                            <input id="order_cance_amount" name="order_cance_amount" type="text" autocomplete="off"  value="0"  readonly >
                    </td>
		</tr>
                
		<tr>
			<td class="content">
			</td>
			<td class="content" colspan="2">
				<strong class="refund_msg text-error"></strong>
			</td>
		</tr>
		<tr style="height: 50px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_method')?>:
			</td>
			<td class="content" >
				<?php if(isset($as_info)){?>
					<input type="hidden" name="method" value="<?php echo $as_info['refund_method']?>">
					<strong>
						<?php echo lang('admin_after_sale_method_'.$as_info['refund_method'])?>
					</strong>
				<?php }else{?>
                
				
				
				<label class="modal_main" style="display: inline"><input type="radio" value="1" checked<?php echo isset($as_info)&&$as_info['refund_method'] == '1' ? 'checked':'' ?> name="method"><?php echo lang('admin_after_sale_method_1')?></label>
                                <label class="modal_main" style="display: inline"><input type="radio" value="0" checked <?php echo isset($as_info)&&$as_info['refund_method'] == '0' ? 'checked':'' ?> name="method"><?php echo lang('admin_after_sale_method_0')?></label>
                <label class="modal_main" style="<?php echo isset($as_info)&&$as_info['refund_method'] == '2' ? 'display: inline':'display: none' ?>"><input type="radio" value="2" <?php echo isset($as_info)&&$as_info['refund_method'] == '2' ? 'checked':'' ?> name="method"><?php echo lang('admin_after_sale_method_2')?></label>
                <?php }?>
			</td>
		</tr>
		<tr class="title transfer <?php echo isset($as_info)&&$as_info['refund_method'] != 1 ? 'hidden' : '' ?>">
			<td><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('payee_info');?>:</td>
			<td>
				<input name="transfer_uid" type="text" value="<?php echo isset($as_info) ? $as_info['transfer_uid'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('member_id')?>" >
			</td>
		</tr>
                <tr class="title manually <?php echo isset($as_info)&&$as_info['refund_method'] != 0 ? 'hidden' : '' ?>">
			<td><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('payee_info');?>:</td>
			<td>
				<input class="bank_name" name="account_bank" type="text" value="<?php echo isset($as_info) ? $as_info['account_bank'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('bank_name').lang('example1').'  '.lang('subbranch');?>" >
			</td>
		</tr>
		<tr class="manually <?php echo isset($as_info)&&$as_info['refund_method'] != 0 ? 'hidden' : '' ?>">
			<td></td>
			<td>
				<input class="brank_num" id="card_number" name="card_number" value="<?php echo isset($as_info) ? $as_info['card_number'] : ''?>" type="text"  autocomplete="off" placeholder="<?php echo lang('bank_card_number');?>">
			</td>
		</tr>
		<tr class="manually <?php echo isset($as_info)&&$as_info['refund_method'] != 0 ? 'hidden' : '' ?>">
			<td></td>
			<td>
				<input class="brank_pop" name="account_name" type="text" value="<?php echo isset($as_info) ? $as_info['account_name'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('card_holder_name');?>">
			</td>
		</tr>
                <!-- 支付宝取消
        <tr class="alipay <?php //echo isset($as_info)&&$as_info['refund_method'] != 2 ? 'hidden' : '' ?>">
            <td></td>
            <td>
                <input id="card_number" name="card_number" value="<?php //echo isset($as_info) ? $as_info['card_number'] : ''?>" type="text"  autocomplete="off" placeholder="<?php //echo lang('withdrawal_alipay_');?>">
            </td>
        </tr>
        <tr class="alipay <?php //echo isset($as_info)&&$as_info['refund_method'] != 2 ? 'hidden' : '' ?>">
            <td></td>
            <td>
                <input name="account_name" type="text" value="<?php //echo isset($as_info) ? $as_info['account_name'] : ''?>"  autocomplete="off" placeholder="<?php //echo lang('alipay_actual_name');?>">
            </td>
        </tr>
                -->
                <tr style="height: 40px;">
                    <td class="title">
                        <img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_amount')?>:
                    </td>
                    <td class="content">
                        <input name="refund_amount" id="refund_amount" type="text" autocomplete="off" value="" placeholder="">
                    </td>
                    <td>
                        <strong class="alipay_tip text-error hidden"><span class="alipay_amount"></span></strong>
                    </td>
                </tr>
		<tr style="height: 50px;" >
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_remark')?>:
			</td>
			<td class="content">
				<textarea readonly autocomplete="off" id="check_info" style="width: 362px; height: 164px;" name="check_info" placeholder="<?php echo lang('admin_after_sale_remark_example'); ?>"></textarea>
			</td>
		</tr>
		<tr>
			<td class="title">
				<input type="button" id="after_sale" autocomplete="off" class="btn btn-primary" value="<?php echo lang('submit');?>">
			</td>
                <input type="hidden" id="meiyuan" value="" />
			<td class="content" id="after_sale_msg"></td>
		</tr>
	</table>
	</form>
</div>
<style>
    .table th, .table td {
        padding: 10px 20px;
    }
</style>
<script>
    chooseRadio();
    chooseSelectType();
    $("[name='method']:radio").click(chooseRadio);
    <?php if(isset($as_info["order_id"]) && $as_info["order_id"]){ ?>
            $("#order_id").val('<?php echo $as_info["order_id"];?>');
            ajax_order_detail();
    <?php } ?>
    $("#order_id").keydown(function(e) {
            var a = e||window.event
            if (a.keyCode == '13') {//keyCode=13是回车键
               ajax_order_detail();
            }
    });
    var order_check_info = "";
    
    function ajax_order_detail(){
        order_check_info = "";
        $("#check_info").val("");
        var html='<tr><th><?php echo lang('order_id') ?></th>';
            html +='<th><?php echo lang('type') ?></th>';
            html +='<th><?php echo lang('pay_amount_order') ?></th>';
            html +='<th><?php echo lang('label_shipping') ?></th>';
            html +='<th><?php echo lang('status') ?></th>';
            html +='<th><?php echo lang('action') ?></th>';
            html +='</tr>';
        var ff_html ='<tr><th><?php echo lang('order_sn'); ?></th>';
             ff_html +='<th><?php echo lang('pay_id'); ?></th>';
             ff_html +='<th><?php echo lang('money'); ?></th>';
             ff_html +='<th><?php echo lang('time'); ?></th>';
             ff_html +='</tr>';
        var order_id = $("#order_id").val();
        var cancel_order_info = "*";
         $.ajax({
            type: "POST",
            url: "/admin/three_month_days_order/get_order_detail",
            data: {"order_id":order_id},
            dataType: "json",
            success: function (data) {
                    if (data.status) {
                        if(data.data.list){
                            $.each(data.data.list,function(i,result){ 
                                html +='<tr class="success">';
                                html +='<td class="one_order_id">'+result.order_id+'</td>';
                                html +='<td>'+result.type_name+'</td>';
                                html +='<td class="one_order_amount">'+result.order_amount_usd+'</td>';
                                html +='<td>'+result.deliver_fee_usd+'</td>';
                                html +='<td>'+result.status_name+'</td><td>';
                                if(((result.order_id).substr(0,1))!="P"){
                                    html +='<input class="checkbox" onclick="chb_is_checked()" type="checkbox" checked="checked">';
                                    cancel_order_info +=result.order_id+"#";//默认全部要取消的订单
                                }
                                html +='</td></tr>';
                                if(((result.order_id).substr(0,1))=="P" || ((result.order_id).substr(0,1))=="N"){//发放过佣金的订单
                                    order_check_info +=result.order_id+"#";
                                }
                               // if(((result.order_id).substr(0,1)) !="P"){//发放过佣金的订单
                              //      cancel_order_info +=result.order_id+"#";
                              //  }
                            })
                        }else{
                              html +='<tr>';
                              html +='<th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>';
                              html +='</tr>';
                        }
                        //添加订单发放佣金信息

                        if(data.ff_order){
                            $.each(data.ff_order,function(j,re){ 
                                ff_html +='<tr class="success">';
                                ff_html +='<td>'+re.order_id+'</td>';
                                ff_html +='<td>'+re.uid+'</td>';
                                ff_html +='<td>'+re.amount+'</td>';
                                ff_html +='<td>'+re.create_time+'</td><td>';
                                ff_html +='</tr>';
                            })
                        }else{
                              ff_html +='<tr>';
                              ff_html +='<th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>';
                              ff_html +='</tr>';
                        }

                        //用户信息
                        if(data.data.user_id){
                            $("#user_id").val(data.data.user_id);
                            $("#store_name").val(data.data.user_name);
                            $("#store_name_span").html(data.data.user_name);
                            $("#check_info").val(order_check_info+cancel_order_info);
                           $("[name='transfer_uid']").val(data.data.user_id);
                        }
                        if(data.data.amout_all){
                             $("#dd_count").val(data.data.amout_all);
                             $("#order_cance_amount").val(data.data.amout_all);
                         }
                    }else{
                        html +='<tr>';
                        html +='<th colspan="6" style="text-align: center;" class="text-success"> <?php echo "此订单不是升级订单"; ?></th>';
                        html +='</tr>';
                    }
                   $("#order_table table").html(html);
                   $("#ff_order_table table").html(ff_html);

            }
        });
    }
     function chb_is_checked() {
        var zje = 0;
        var remark ="*";
        
        $(".checkbox").each(function () {
            if ($(this).attr("checked") && (($(this).parent().parent().find(".one_order_id").html()).substr(0,1))!="P") {
                remark += ($(this).parent().parent().find(".one_order_id").html())+'#';
                zje += parseFloat($(this).parent().parent().find(".one_order_amount").html());
            }
            
        });
        $("#order_cance_amount").val(zje);
        $("#check_info").val(order_check_info+remark);
     }
     /*
     function quanjiao(obj){
        var str=obj.value;
        if (str.length>0)
        {
            for (var i = str.length-1; i >= 0; i--)
            {
                unicode=str.charCodeAt(i);
                if (unicode>65280 && unicode<65375)
                {
                    alert("不能输入全角字符，请输入半角字符");
                    obj.value=str.substr(0,i);
                }
            }
        }
    }
    */
    $('#after_sale').click(function(){
            var oldSubVal = $('#after_sale').val();
           // $('#after_sale').val($('#loadingTxt').val());
            $('#after_sale').attr("disabled","disabled");
            $.ajax({
                    type: "POST",
                    url: "/admin/three_month_days_order/do_add_after_sale",
                    data: $('#after_sale_form').serialize(),
                    dataType: "json",
                    success: function (res) {
                            if (res.success) {
                                    $('#after_sale_msg').html(res.msg).addClass('text-success');
                                    $('#after_sale').val(oldSubVal);
                                    setTimeout(function(){
                                            location.href="/admin/after_sale_order_list";
                                    },1000);
                            }else{
                                    $('#after_sale_msg').html('× '+res.msg).addClass('text-error');
                                    $('#after_sale').attr("disabled",false);
                                    $('#after_sale').val(oldSubVal);
                            }

                    }
            });
    });
    function chooseSelectType(){
		var type = $("[name='type'] option:selected").val();
		if(type == 1){
			$('.demote_level').removeClass('hidden');
			$('.no_tui').removeClass('hidden');
			$('.order_input').addClass('hidden');
                        $('.demote_info').removeClass('hidden');
		}else if(type == 0){
			$('.demote_level').addClass('hidden');
			$('.no_tui').removeClass('hidden');
			$('.order_input').addClass('hidden');
                        $('.demote_info').addClass('hidden');
		}else if(type == 2 || type == 3){
			$('.order_input').removeClass('hidden');
			$('.demote_level').addClass('hidden');
			$('.no_tui').addClass('hidden');
                        $('.demote_info').addClass('hidden');
		}
	}
    function chooseRadio(){
            var type = $("[name='method']").filter(":checked").val();
            if(type == 1){
                    $('#refund_amount').attr('placeholder','请输入美金（$）');
                    if($('#meiyuan').val()){
                        $('#refund_amount').val(Number($('#meiyuan').val()).toFixed(2));
                    }
                    $('.manually').addClass('hidden');
                    $('.transfer').removeClass('hidden');
                    $('.alipay').addClass('hidden');
                    $('.alipay_tip').addClass('hidden')
            }else if (type == 0){
                    $('#refund_amount').attr('placeholder','请输入人民币（￥）');
                    $('.transfer').addClass('hidden');
                    $('.manually').removeClass('hidden');
                    $('.alipay').addClass('hidden');
                    $('.alipay_tip').removeClass('hidden').text("(退款到银行卡金额是￥人民币)，系统将自动减去0.5%的手续费.");
            }else if(type == 2){
                $('#refund_amount').attr('placeholder','请输入人民币（￥）');
                $('#refund_amount').val('');
                $('.transfer').addClass('hidden');
                $('.alipay').removeClass('hidden');
                $('.manually').addClass('hidden');
                $('.alipay_tip').removeClass('hidden').text("(退款到支付宝金额是￥人民币)，系统将自动减去0.5%的手续费.");
                }
	}
</script>