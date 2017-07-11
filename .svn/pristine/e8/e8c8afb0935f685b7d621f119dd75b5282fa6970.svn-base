<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<div class="w1200">
    <div class="MyCart clear">
        <!-- 确认订单信息 -->
        <h2><?php echo lang('checkout_confirm_info'); ?></h2>
        <div class="checkout-box clear">
            <!-- 收货信息 -->
            <div class="body-bd clear">
                <div class="xinzeng clear">
                    <b><?php echo lang('checkout_deliver_info'); ?></b>
                    <a href="javascript:showBg();" class="XS_btn" onclick="click_addr_add();">
                        <i>+</i>
                        <?php echo lang('checkout_deliver_add'); ?>
                    </a>
                </div>
            </div>
            <!-- 收货地址列表 -->
            <div class="body-bd clear">
                <div class="address-list clear">
                    <ul>
                        <?php foreach ($data as $v): ?>
                            <li<?php if ($v['is_default']): ?> class="selected"<?php endif; ?> data-id="<?php echo $v['addr_id']; ?>" onclick="cb_addr_change($(this));" data-addr="<?php echo $v['consignee']?>" data-num="<?php echo $v['ID_no']?>" data-img1="<?php echo $v['ID_front']?>" data-img2="<?php echo $v['ID_reverse']?>">
                                <i class="pc-tps">&#xe640;</i>
                                <span class="marker-tip"><?php echo lang('checkout_deliver_to'); ?></span>
                                <input autocomplete="off" type="radio"<?php if ($v['is_default']): ?> checked<?php endif; ?> />
                                <div class="address-info">
                                    <a href="javascript:void(0);" class="shanchu c-b" onclick="submit_delete_addr('<?php echo $v['addr_id']; ?>');"><?php echo lang('checkout_deliver_delete'); ?></a>
                                    <a href="javascript:showBg();" class="modify c-b" onclick="click_addr_edit('<?php echo $v['addr_id']; ?>');"><?php echo lang('checkout_deliver_modify'); ?></a>
                                    <label>
                                        <?php echo $v['addr_str']; ?>
                                        <?php
                                        if (!empty($v['zip_code']))
                                        {
                                            echo $v['zip_code'];
                                        }
                                        ?>
                                        (<?php echo lang_attr('checkout_deliver_consignee', array('consignee' => $v['consignee'])); ?>)
                                        <em><?php echo isset($v['phone']) ? $v['phone'] : $v['reserve_num']; ?></em>
                                        <?php
                                        if (!empty($v['customs_clearance']))
                                        {
                                            echo "<em style=\"color: #f56f44;\">[{$v['customs_clearance']}]</em>";
                                        }
                                        ?>
                                    </label>
                                    <?php if ($v['is_default']): ?>
                                        <em class="tip"><?php echo lang('checkout_deliver_default'); ?></em>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="set-default c-b" onclick="submit_set_default_addr('<?php echo $v['addr_id']; ?>');">
                                            <?php echo lang('checkout_deliver_set_default'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="product_info_address"><?php echo $order_address_info_tip?></div>
            </div>

            <!-- 证件信息 -->
            <?php if($curLocation_id == 156) { ?>
                <?php if($is_reg_num == 1 || $is_reg_img == 2 || $is_reg_all == 3){?>
                    <div class="body-bd clear">
                        <h3><?php echo lang('card_document_info'); ?></h3>
                        <div class="Shuo clear">
                            <p><span style="margin-right: 0px;" class="ml-n"><?php echo lang('card_upload_id'); ?>：</span><em style="color: Red; font-weight:bold; font-size: 14px; ">（注意：您购买了海外商品，为了您的商品能够顺利通过海关，请填写"<b id="consignee" style="color: #000;"></b>"的证件信息）</em>
                                <?php if($is_reg_num == 1 || $is_reg_all == 3){?>
                            <p><input id="id_card" autocomplete="off" value="" class="zjh" type="text" placeholder="<?php echo lang('card_is_reg'); ?>"><span id="error_msg" style="color: #f50; font-size:14px; font-weigh:bold;margin-left: 20px;"></span></p>
                            <?php }?>
                            <?php if($is_reg_img == 2 || $is_reg_all == 3){?>
                                <p>
                                    <input type="hidden" name="id_img_a" id="id_img_a" value="">
                                    <input type="hidden" id="id_img_b" name="id_img_b" value="">
                                    <a id="img_zm" class="form-btn"><b class="d-b fw-n"><?php echo lang('card_img_zm'); ?></b></a><input onclick="yz_zm()" id="fileupload" name="ID_front" type="file" class="form-btn">
                                    <a id="img_bm" class="form-btn"><b class="d-b fw-n"><?php echo lang('card_img_bm'); ?></b></a><input onclick="yz_bm()" id="fileupload2" name="ID_reverse" type="file" class="form-btn">
                                </p>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>
            <?php }?>
            <!-- 收货时间 -->
            <!--			<div class="body-bd clear">-->
            <!--				<h3>--><?php //echo lang('checkout_deliver_time'); ?><!--</h3>-->
            <!--				<div class="Shuo clear">-->
            <!--					<p>-->
            <!--						<label>-->
            <!--							<input type="radio" name="deliver_time_type" value="1" checked />-->
            <!--							<span>--><?php //echo lang('checkout_deliver_time_period1'); ?><!--</span>-->
            <!--							<i>--><?php //echo lang('checkout_deliver_time_desc1'); ?><!--</i>-->
            <!--						</label>-->
            <!--					</p>-->
            <!--					<p>-->
            <!--						<label>-->
            <!--							<input type="radio" name="deliver_time_type" value="2" />-->
            <!--							<span>--><?php //echo lang('checkout_deliver_time_period2'); ?><!--</span>-->
            <!--							<i>--><?php //echo lang('checkout_deliver_time_desc2'); ?><!--</i>-->
            <!--						</label>-->
            <!--					</p>-->
            <!--					<p>-->
            <!--						<label>-->
            <!--							<input type="radio" name="deliver_time_type" value="3" />-->
            <!--							<span>--><?php //echo lang('checkout_deliver_time_period3'); ?><!--</span>-->
            <!--						</label>-->
            <!--					</p>-->
            <!--				</div>-->
            <!--			</div>-->
            <!-- 订单商品信息 -->
            <div class="body-bd ddxx clear">
                <h3><?php echo lang('checkout_order_info'); ?></h3>
                <div class="box-bd">
                    <!-- 发货清单 -->
                    <dl class="clear">
                        <dt class="clear">
                            <span class="col-md-5"><span class="P_left"><?php echo lang('checkout_order_list'); ?></span></span>
                            <span class="col-md-2 tc"><?php echo lang('goods_attr'); ?></span>
                            <span class="col-md-2 tc"><?php echo lang('checkout_order_price'); ?></span>
                            <span class="col-md-1 tc"><?php echo lang('checkout_order_quantity'); ?></span>
                            <span class="col-md-2 tc"><?php echo lang('checkout_order_deliver'); ?></span>
                        </dt>
                        <dd class="clear">
                            <fieldset id="fs_goods_list">
                            </fieldset>
                        </dd>
                    </dl>
                    <!-- 商品清单页尾：配送时间、运费总计、订单实付 -->
                    <p class="yunfei">
						<span>
							<?php echo lang('checkout_order_deliver_time'); ?>
                            <!--							<em id="order_deliver_time_type"></em>-->
							<select id="order_deliver_time_type" name="deliver_time_type" class="form-control">
								<option value="1"><?php echo lang('checkout_deliver_time_period1'); ?></option>
								<option value="2"><?php echo lang('checkout_deliver_time_period2'); ?></option>
								<option value="3"><?php echo lang('checkout_deliver_time_period3'); ?></option>
							</select>
						</span>
                        <front class='checkout_order_deliver_fee'><?php echo lang('checkout_order_deliver_fee');?></front><?php echo " ".$curCur_flag; ?><a class="order_deliver_fee"></a>
						<em><?php echo lang('checkout_order_amount')." ".$curCur_flag; ?><a class="order_total_amount c-o"></a></em>
					</p>
					<!-- 备注 -->
					<ul class="beizhu clear">
						<li><?php echo lang('checkout_order_remark'); ?></li>
						<li>
							<textarea class="input" id="remark" placeholder="<?php echo lang('checkout_order_remark_palceholder'); ?>"></textarea>
						</li>
						<li><?php echo lang('checkout_order_remark_tip'); ?></li>
					</ul>
					<!-- 提交框 -->
					<div class="zongs clear">
						<!-- 收货信息，其他信息 -->
						<div class="left clear">
							<dl class="clear">
								<dt><?php echo lang('checkout_receive_info'); ?></dt>
								<dd id="info_addr"></dd>
							</dl>
<!--							<dl class="clear">-->
<!--								<dt>--><?php //echo lang('checkout_receive_other'); ?><!--</dt>-->
<!--								<dd>-->
<!--									--><?php //echo lang('checkout_receive_receipt'); ?>
<!--									<input class="shouju" type="checkbox" checked />-->
<!--								</dd>-->
<!--							</dl>-->
						</div>
						<!-- 金额 -->
						<div class="right clear">
							<dl class="clear">
								<dt><?php echo lang('checkout_pay_product'); ?></dt>
								<dd><?php echo $curCur_flag; ?><span id="order_goods_amount"></span></dd>
							</dl>
							<dl class="clear bk bottom_15">
								<dt class="checkout_order_deliver_fee"><?php echo lang('checkout_order_deliver_fee'); ?></dt>
								<dd><?php echo $curCur_flag; ?><span class="order_deliver_fee"></span></dd>
							</dl>
							<dl class="clear">
								<dt class="font"><?php echo lang('checkout_pay_amount'); ?></dt>
								<dd class="hse font">
									<em><?php echo $curCur_flag; ?></em><span class="order_total_amount"></span>
								</dd>
							</dl>


                            <?php if($curLocation_id == "156"){?>
                            <p style="margin: 5px 0 0 30px">
                                    <input autocomplete="off" name="email_disclaimer" checked type="checkbox" class="tps_checkbox"/>
                                    <?php echo lang('user_buy_agreement')?>

                                </p>
                            <?php }?>
							<dl class="clear">
								<dd class="nnniu">
									<a class="btn_hong" id="submit_btn" href="javascript:void(0);" onclick="submit_order();">
										<?php echo lang('checkout_order_submit'); ?>
									</a>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- 新增 / 修改模拟框 -->
<div class="xm-edit-addr wodl clear" id="BOX_nav" style="height:520px;">
    <span class="close" onclick="closeBg();">×</span>
    <h3 id="box_title"></h3>
    <div class="item">
        <em id="box_title_em"></em>
        <span class="item-title"><?php echo lang('checkout_addr_box_remark'); ?></span>
        <input type="hidden" id="box_type" />
        <input type="hidden" id="box_id" />
    </div>

    <?php $this->load->view("mall/address/checkout_form.php") ?>

</div>
<div class="xm-backdrop" id="fullbg"></div>

<script src="<?php echo base_url('/js/customComboBox.js?v=170124'); ?>"></script>

<!--上传身份证照片开始-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/prepaid_card.css?v=1'); ?>">
<script src="<?php echo base_url('js/jquery.form.js'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/js/card_upload.js?v=3'); ?>"></script>
<!--上传身份证照片结束-->

<!--地址操作js包含引用-->
<?php $this->load->view("mall/address/js.php") ?>

<script>
    //身份证要删了当前的才能上传
    function yz_zm(){
        var id_img_a = $("#id_img_a").val();
        if(id_img_a != ''){
            delimg();
        }
    }
    function yz_bm(){
        var id_img_b = $("#id_img_b").val();
        if(id_img_b != ''){
            delimg2();
        }
    }

    // 判断一个数组或对象是否为空
    function isEmpty(obj) {
        for (var p in obj) {
            return false;
        }
        return true;
    }

    // 防止重复提交
    var submit_flag = false;

    $(function() {
        'use strict';

        address_init();

        // 收货时间 change 事件，修改商品信息对应字段
//		$('[name=deliver_time_type]').on('change', function() {
//			var dlv_time = $('[name=deliver_time_type]:checked').next().text();
//			$('#order_deliver_time_type').text(dlv_time);
//		});

        // 根据默认收货地址，刷新订单信息
        for (var id in data) {
            if (data[id].is_default == 1) {
                //默认收件人的名字
                var addr_name = data[id].consignee;
                $("#consignee").text(addr_name);

                //获取收货人的证件信息
                var ID_no = data[id].ID_no;
                var ID_front = data[id].ID_front;
                var ID_reverse = data[id].ID_reverse;

                //显示已有的证件信息
                if(ID_no != null && ID_no != ''){
                    $("#id_card").val(ID_no);
                }
                $("#id_img_a").val(ID_front);
                $("#id_img_b").val(ID_reverse);

                var ID_fronts = "<?php echo $img_host?>"+ID_front;
                var ID_reverses = "<?php echo $img_host?>"+ID_reverse;

                //正面图片
                if(ID_front != null && ID_front != '') {
                    $("#img_zm").html("<img style='margin-left: 0px;' id='showimg' src='" + ID_fronts + "'>");
                    var img_zm = $('#img_zm');
                    img_zm.append("<a href='##' id='delimg' onclick='delimg()' rel='" + ID_front + "'>删除</a>");
                }
                //背面图片
                if(ID_reverse != null && ID_reverse != '') {
                    $("#img_bm").html("<img style='margin-left: 0px;' id='showimg2' src='" + ID_reverses + "'>");
                    var img_bm = $('#img_bm');
                    img_bm.append("<a href='##' id='delimg2' onclick='delimg2()' rel='" + ID_reverse + "'>删除</a>");
                }
                refresh_order_info(id);
                break;
            }
        }


        //光标离开时验证身份证号码
        $("#id_card").blur(function () {
            var id_card = $("#id_card").val();
            //身份证号码验证
            if($("#id_card").length>0){
                var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
                if(!pattern.test(id_card)){
                    $('#error_msg').text("*<?php echo lang('card_please_reg');?>");
                    $('#maxLimit').text("");
                    setTimeout("$('#error_msg').text('')", 3000);
//                    return false;
                }
            }

        });

    });

    // 修改收货地址时刷新商品清单和订单金额
    function cb_addr_change(obj) {
        var addr_id = obj.data("id");
        //获取收货人名字
        var addr_name = obj.data("addr");
        $("#consignee").text(addr_name);

        var ID_reverse = obj.attr('data-img2');
        var ID_front = obj.attr('data-img1');
        var ID_no = obj.attr('data-num');

        if(ID_no != '' && ID_no != null){
            $("#id_card").val(ID_no);
        }else {
            $("#id_card").val('');
        }
        //替换img标签，显示图片
        //显示已有的证件信息
        $("#id_img_a").val(ID_front);
        $("#id_img_b").val(ID_reverse);

        var ID_fronts = "<?php echo $img_host?>" + ID_front;
        var ID_reverses = "<?php echo $img_host?>" + ID_reverse;

        if (ID_front != '' && ID_front != null) {
            //正面图片
            $("#img_zm").html("<img style='margin-left: 0px;' id='showimg' src='" + ID_fronts + "'>");
            var img_zm = $('#img_zm');
            img_zm.append("<a href='##' id='delimg' onclick='delimg()' rel='" + ID_front + "'>删除</a>");
        } else {
            //清除正面图片信息
            $("#id_img_a").val('');
            var zm = "<?php echo lang('card_img_zm'); ?>";
            $("#img_zm").html("<b class='d-b fw-n'>"+zm+"</b>");
            var delimg = $('#delimg');
            delimg.css('display','none');
        }
        if (ID_reverse != '' && ID_reverse != null) {
            //背面图片
            $("#img_bm").html("<img style='margin-left: 0px;' id='showimg2' src='" + ID_reverses + "'>");
            var img_bm = $('#img_bm');
            img_bm.append("<a href='##' id='delimg2' onclick='delimg2()' rel='" + ID_reverse + "'>删除</a>");
        }else {
            //清除背面图片信息
            $("#id_img_b").val('');
            var bm = "<?php echo lang('card_img_bm')?>";
            $("#img_bm").html("<b class='d-b fw-n'>"+bm+"</b>");
            var delimg2 = $('#delimg2');
            delimg2.css('display','none');
        }

        // 商品信息
        refresh_order_info(addr_id);

        return true;
    }

    function shipping_type_change() {
        var addr_id = $('.address-list li.selected').data('id');
        var fee = +$(this).children(':selected').data("fee");
        $(this).parent().next().text("<font class='checkout_order_deliver_fee'><?php echo lang('checkout_order_deliver_fee')?></font><?php echo$curCur_flag; ?>" + fee.toFixed(2));
        $('.order_deliver_fee').text(fee.toFixed(2));
        $('.order_total_amount').text(+data[addr_id].product_amount_show + fee)
    }

    // 根据地址 index 刷新商品信息表，标示不能寄送的商品，计算商品总额
    function refresh_order_info(i) {
        var checkout_flag = true;
        // 清掉数据
        $('#fs_goods_list').children().remove();

        // 商品金额、运费
        var shipping_fee = 0.00;
        var order_amount = 0.00;
        var shipping_fee_show = 0.00;

        // 可配送清单
        if (isEmpty(data[i].deliverable_list)) {
            checkout_flag = false;
        } else {
            for (var j in data[i].deliverable_list) {
                $('#fs_goods_list').append(
                    $('<div/>').addClass("x-hl clear").append(function() {
                        var list_box = $('<div/>').addClass("col-md-10");
                        for (var k in data[i].deliverable_list[j].list) {

                            var item = data[i].deliverable_list[j].list[k];

                            // 规格信息
                            if (item.spec != "") {
                                var spec = $('<em/>').text(item.spec);
                            } else {
                                var spec = false;
                            }

                            list_box.append(
                                $('<div/>').addClass("col-md-9").append(
                                    $('<div/>').addClass("g-info").append(
                                        $('<span/>').append(
                                            $('<a/>').attr('href', "<?php echo base_url(); ?>index/product?snm=" + item.goods_sn_main).html("<img src='<?php echo $img_host;?>"+item.goods_img+"'/>")
                                        )
                                    ).append(
                                        $('<div/>').addClass("xq").append($('<a/>').attr('href', "<?php echo base_url(); ?>index/product?snm=" + item.goods_sn_main).html(item.goods_name))
                                            .append("<?php echo lang('label_sku')?>:"+item.goods_sn))
                                        .append(spec)
                                )
                            ).append(
                                $('<div/>').addClass("col-md-2").append(
                                    $('<b/>').addClass("mt-45").text("<?php echo $curCur_flag; ?>" + number_format(item.price_show))  //商品单价
                                )
                            ).append(
                                $('<div/>').addClass("col-md-1 tc").append(
                                    $('<span/>').addClass("d-b mt-45").text(item.quantity)
                                )
                            );
                        }

                        return list_box;
                    }).append(
                        $('<div/>').addClass("col-md-2").append(function() {
                            var peisong = $('<div/>').addClass("peisong clear");

                            var select_tag = $('<select/>').addClass("select").attr("id", "deliver_id_" + j).on('change', shipping_type_change);

                            if (isEmpty(data[i].deliverable_list[j].shipping_type)) {
                                checkout_flag = false;
                            } else {
                                var shipping = data[i].deliverable_list[j].shipping_type;
                                for (var k in shipping) {
                                    select_tag.append(
                                        $('<option/>').val(shipping[k].type).text(shipping[k].text).data("fee", shipping[k].fee)    //订单运费
                                    )
                                }
                            }

                            peisong.append(
                                $('<p/>').append(select_tag)
                            );

                            var fee = select_tag.children(':selected').data("fee");
                            if (fee != undefined) {
                                peisong.append(
                                    $('<p/>').append(
                                        "<font class='checkout_order_deliver_fee'><?php echo lang('checkout_order_deliver_fee');?></font><?php echo $curCur_flag; ?>" + number_format(fee)
                                    )
                                );
                            } else {
                                peisong.append(
                                    $('<p/>').css('color', "#e4393c").text("<?php echo lang('checkout_order_deliver_unable'); ?>")
                                )
                            }

                            // 运费
                            shipping_fee += +fee;
                            return peisong;
                        })
                    )
                );
            }
        }

        // 不能配送列表
        if (!isEmpty(data[i].invalid_list)) {
            $('#fs_goods_list').append(
                $('<div/>').addClass("x-hl clear").append(function() {
                    var list_box = $('<div/>').addClass("col-md-10");
                    for (var j in data[i].invalid_list) {

                        var item = data[i].invalid_list[j];

                        // 规格信息
                        if (item.spec != "") {
                            var spec = $('<em/>').addClass("hui").text(item.spec);
                        } else {
                            var spec = false;
                        }

                        list_box.append(
                            $('<div/>').addClass("col-md-9").append(
                                $('<div/>').addClass("g-info").append(
                                    $('<span/>').append(
                                        $('<a/>').addClass("hui").attr('href', "<?php echo base_url(); ?>index/product?snm=" + item.goods_sn_main).text(item.goods_name)
                                    )
                                ).append(
                                    $('<div/>').addClass("xq").append($('<a/>').addClass("hui").attr('href', "<?php echo base_url(); ?>index/product?snm=" + item.goods_sn_main).html(item.goods_name))
                                        .append("<?php echo lang('label_sku')?>:"+item.goods_sn))
                                    .append(spec)
                            )
                        ).append(
                            $('<div/>').addClass("col-md-2").append(
                                $('<b/>').addClass("mt-45").addClass("hui").text("<?php echo $curCur_flag; ?>" + item.price_show)
                            )
                        ).append(
                            $('<div/>').addClass("col-md-1 tc").append(
                                $('<span/>').addClass("d-b mt-45").addClass("hui").text(item.quantity)
                            )
                        ).append(
                            $('<div/>').addClass("col-md-10").append(
                                $('<p/>').text(item.limit_area)
                            )
                        );
                    }

                    return list_box;
                })
            );
        }

        // 收货信息
        $('#info_addr').text(data[i].addr_str);

        // 商品金额
        $('#order_goods_amount').text(data[i].product_amount_show);

        // 订单无法提交
        if (checkout_flag == false) {
            order_amount = +data[i].product_amount_show;

            $('.order_deliver_fee').text("--");
            $("#submit_btn").removeClass("btn_hong").addClass("btn_Bhui");
        } else {
            order_amount = +re_number(data[i].product_amount_show) + shipping_fee;

            $('.order_deliver_fee').text(number_format(shipping_fee.toFixed(2)));
            $("#submit_btn").removeClass("btn_Bhui").addClass("btn_hong");
        }

        // 订单实付金额
        $('.order_total_amount').text(number_format(order_amount.toFixed(2)));

        if(data[i].addr_lv3 == "8104")
        {
            checkout_order_deliver_fee(true);
        }else{
            checkout_order_deliver_fee(false);
        }
		return true;
	}
    //设置默认收货地址
    function submit_set_default_addr(id) {

        var data = {
            'id': id
        };

        $.ajax({
            url: '/order/do_set_default_addr',
            type: "POST",
            data: data,
            dataType: "json",
            success: function(data) {
                if (data.code == 0) {
                    window.location.reload();
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    }

    //删除地址
    function submit_delete_addr(id) {

        var data = {
            'id': id
        };

        $.ajax({
            url: '/order/do_delete_addr',
            type: "POST",
            data: data,
            dataType: "json",
            success: function(data) {
                if (data.code == 0) {
                    window.location.reload();
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    }

    var if_checked = function(o)
    {
        var cur_lang = "<?php echo $curLocation_id; ?>";
        if(cur_lang != "156")
        {
            return true;
        }
        var tmp = false;
        o.each(function(){if($(this).attr('checked') == "checked"){
            tmp = true;
        };});
        console.dir("if_checked return "+tmp);
        return tmp;
    }

    var email_disclaimer_alert = function()
    {
        $('input[name=email_disclaimer]').parent().css({"border":"3px solid red","padding":"3px"});
    }

    var email_disclaimer_normal = function()
    {
        $('input[name=email_disclaimer]').parent().css({"border":"0px solid red","padding":"3px"});
    }

    var email_disclaimer_check = function()
    {
        if(!if_checked($("input[name=email_disclaimer]")))
        {
//            showRegister();
            layer.msg("<?php echo lang('agree_buy_agreement_plz')?>");
            email_disclaimer_alert();
            return false;
        }
        email_disclaimer_normal();
        return true;
    }

    $(document).on('click','input[name=email_disclaimer]',function(){email_disclaimer_check();});

    // 提交订单
    function submit_order() {

        if(!email_disclaimer_check())
        {
            return false;
        }

        if ($('#submit_btn').hasClass("btn_Bhui")) {
            return false;
        }

        if (submit_flag == true) {
            return false;
        }

        //身份证号码验证
        if($("#id_card").length > 0){
            var id_card = $("#id_card").val();
            var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
            if(!pattern.test(id_card)){
                layer.msg("<?php echo lang('card_please_reg');?>");
                return false;
            }
        }
        //身份证号和身份证不能为空
        if($("#id_img_a").length > 0 && $("#id_img_b").length > 0) {
            var id_img_a = $("#id_img_a").val();
            var id_img_b = $("#id_img_b").val();
            //身份证图片不能为空
            if (id_img_a == '' || id_img_b == '') {
                layer.msg('<?php echo lang('card_please_img');?>');
                return false;
            }
        }

        var ajax_data = {};

        ajax_data.customer_id = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

        ajax_data.shopkeeper_id = <?php echo $store_id; ?>;

        var addr_id = $('.address-list li.selected').data('id');
        var not_deliever_flag = false;
        if (not_deliever_flag == true) {
            //冬季时间不发货的省份：
            //黑龙江、辽宁、吉林、新疆、西藏、内蒙古共6个地区，暂停发货时间暂定为：2016-11-30到2017-3-1
            //提示语：
            //您的订单中包含有不能发货到您所填写的收货地区的商品（商品编码：XXXXXXXX），请您核实后重新提交订单！
            //start
//			var start_time = "2016-11-26 00:00:00";
//			var end_time = "2017-3-1 23:59:59";
            var start_time = "<?php echo config_item('not_deliever_goods_start')?>"
            var end_time = "<?php echo config_item('not_deliever_goods_end')?>"
            console.log(start_time);
            console.log(end_time);
            var start_time_stamp = Date.parse(new Date(start_time));
            var end_time_stamp = Date.parse(new Date(end_time));

            start_time_stamp = start_time_stamp / 1000;
            end_time_stamp = end_time_stamp / 1000;
            //如果时间在这个区间进行检测
            var now_time_stamp = Date.parse(new Date());
            now_time_stamp = now_time_stamp / 1000;
            if (now_time_stamp > start_time_stamp && now_time_stamp < end_time_stamp) {
                var address_list = {};
                address_list = <?php echo json_encode($addr_list)?>;
                var address_info = '';
                if (address_list.length > 0) {
                    for (var i=0; i<address_list.length; i++) {
                        if (addr_id == address_list[i]['id']) {
                            address_info = address_list[i];
                            break;
                        }
                    }
                    //不支持的配送区域
                    var not_deliever_district = <?php echo json_encode(config_item("not_deliever_district"));?>;
                    //不支持的商品
                    var not_deliever_goods = <?php echo json_encode(config_item("not_deliever_goods"));?>;
                    if (-1 !== not_deliever_district.indexOf(address_info.addr_lv2)){
                        //区域 遍历查询是否有商品不配送
                        var not_deliver = [];
                        var goods_sn_list = <?php echo json_encode($goods_sn_list);?>;
                        for(var j = 0; j < goods_sn_list.length; j++) {
                            if (not_deliever_goods.indexOf(goods_sn_list[j]) !== -1) {
                                not_deliver.push(goods_sn_list[j]);
                            }

                        }
                        if (not_deliver.length > 0) {
                            var not_deliver_str = not_deliver.join(',');
                            var area_cannot_reach_1 = '<?php echo lang('area_cannot_reach_1')?>';
                            var area_cannot_reach_2 = '<?php echo lang('area_cannot_reach_2')?>';
                            var attention = area_cannot_reach_1+not_deliver_str+area_cannot_reach_2
                            layer.msg(attention);
                            return false;
                        }
                    } else {

                    }

                }


            } else {

            }
        }

        //限制end
        ajax_data.addr_id = addr_id;
        //配送时间
//		ajax_data.deliver_time_type = $('[name=deliver_time_type]:checked').val();
        ajax_data.deliver_time_type = $('#order_deliver_time_type').val();

        ajax_data.remark = $('#remark').val();

        //《TPS用户注册购买协议》
        ajax_data.protocol = $('input[name=email_disclaimer]').attr('checked');;

        //获取身份证号和图片
        if($("#id_card").length > 0) {
            ajax_data.ID_no = $('#id_card').val();
        }
        if($("#id_img_a").length > 0 && $("#id_img_b").length > 0) {
            ajax_data.ID_front = $('#id_img_a').val();
            ajax_data.ID_reverse = $('#id_img_b').val();
        }

        //不发送单据，0不发送，1发送
        if ($('.shouju').prop('checked')) {
//			ajax_data.need_receipt = 1;
            ajax_data.need_receipt = 0;
        } else {
            ajax_data.need_receipt = 0;
        }

        var deliver = [];
        for (var shipper_id in data[addr_id].deliverable_list) {
            var list = [];
            for (var k in data[addr_id].deliverable_list[shipper_id].list) {
                list.push({
                    goods_sn: data[addr_id].deliverable_list[shipper_id].list[k].goods_sn,
                    goods_sn_main:data[addr_id].deliverable_list[shipper_id].list[k].goods_sn_main,
                    quantity: data[addr_id].deliverable_list[shipper_id].list[k].quantity
                });
            }

            deliver.push({
                list: list,
                shipping_type: $('#deliver_id_' + shipper_id).children(':selected').val(),
                shipper_id: shipper_id
            });
        }

        ajax_data.deliver = deliver;

        ajax_data.clear_cart = "<?php echo $clear_cart; ?>";

        submit_flag = true;

        $.ajax({
            url: '/order/do_checkout_order',
            type: "POST",
            data: ajax_data,
            dataType: "json",
            success: function(data) {
                submit_flag = false;
                if (data.code == 0) {
                    window.location = "/order/pay/" + data.id;
                } else if(data.code == 204){
                    layer.msg(data.msg);
                } else {
                    console.log(data);
                    layer.msg(data.msg);
                }
            },
            error: function(data) {
                submit_flag = false;
                console.log(data.responseText);
                layer.msg(data.responseText);

            }
        });
        return true;
    }

    // 千分位用逗号分割
    function number_format(num)
    {
        var n = 2;
        var num = parseFloat((num + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
        var l = num.split(".")[0].split("").reverse(),
            r = num.split(".")[1];
        var t = "";
        for(var i = 0; i < l.length; i ++ )
        {
            t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
        }
        return t.split("").reverse().join("") + "." + r;
    }

    //去除逗号
    function re_number(num)
    {
        return parseFloat(num.replace(/[^\d\.-]/g, ""));
    }

    var customerComboBox_Config = {
        tipText : "Enter Your City",
        tipClass : "mytipclass",
        allowed : /[A-Za-z0-9\$\.\s]/,
        notallowed : /[\<\>]/,
        prefix: "",
        index : 'last',
        isEditing : function(el, status, value) {
            if (typeof window.console!='object') { return; }
            console.info('Editing status changed to (', status, ') on ', el, ' combo box and the selected value is "', value, '"');
        },
        onKeyDown : function(el, character, fulltext) {
            if (typeof window.console!='object') { return; }
            console.info('The character (', character, ') was just typed into ', el, ' combo box and the complete text is now "', fulltext, '"');
        },
        onDelete : function(el, fulltext) {
            if (typeof window.console!='object') { return; }
            console.info('A character was deleted from ', el, ' combo box and the complete text is now "', fulltext, '"');
        }
    };

    //	$(document).on("click","#box_addr_lv3",function(){
    //	    console.dir("box_addr_lv3");
    //        $("#box_addr_lv3").customComboBox(customerComboBox_Config);
    //    });

</script>
