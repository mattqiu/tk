$(document).ready(function () {
    $("#manuallyMoney").keyup(function () {
        $('#manuallyPoint').val($('#manuallyMoney').val());
    });
    $("#pointNeedToMove").keyup(function () {
        $('#moneyNeedToGet').val($('#pointNeedToMove').val());
    });
    $("#cashForMonthFee").keyup(function () {
        $('#monthFeeFromCash').val($('#cashForMonthFee').val());
    });
    $(".go_upall_div").click(function(){
        $('#upall_div').addClass('curBorder');
        location.href = "#upall_div";
    });

    //关闭区域提示
    $('.close_location').click(function() {
        $('.location').addClass('hidden');
    });

    /** 信息數量*/
    /*setInterval(function(){
        $.ajax({
            type: "POST",
            url: "/ucenter/my_msg/getBoardCount",
            dataType: "json",
            success: function (res) {
                if (res.count) {
                    $('.my_badge_news').html('+' + res.count).addClass('label label-info');
                }else{
                    $('.my_badge_news').html('').removeClass('label label-info');
                }
            }
        })
    },120000);*/
    $('.btn1_cs').click(function(){
        $.ajax({
            type: "POST",
            url: "/ucenter/my_msg/hadRead",
            data:$('.board_msg').serialize(),
            dataType: "json",
            success: function (res) {
                if(res.success){
                    location.reload();
                }
            }
        });
    });
    $('.btn2_cs').click(function(){
        $.ajax({
            type: "POST",
            url: "/ucenter/my_msg/deleteMsg",
            data:$('.board_msg').serialize(),
            dataType: "json",
            success: function (res) {
                if(res.success){
                    location.reload();
                }
            }
        });
    });
    $('.getBoard').click(function(){
        var id = $(this).attr('attr_id');
        $.ajax({
            type: "POST",
            url: "/ucenter/welcome_new/get_board_msg",
            data:{id:id},
            dataType: "json",
            success: function (res) {
                if(res.success){
                    $('#board_news .board_news_title').html(res.title);
                    $('#board_news .board_news_time').html(res.time);
                    $('#board_news .board_news_content').html(res.msg);
                    $('#board_news').modal();
                    $('[attr_id="'+ id +'"]').next('img').remove();
                    if (res.hasOwnProperty('count') && res.count > 0) {
                        $('.my_badge_news').html('+' + res.count).addClass('label label-info');
                    }else{
                        $('.my_badge_news').html('').removeClass('label label-info');
                    }
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });
    $('#board_news').on('click','.alert_count_close',function(){
        $.ajax({
            type: "POST",
            url: "/ucenter/welcome_new/update_alert_count",
            data:{},
            dataType: "json",
            success: function (res) {
                if(res.success){
                    location.reload();
                }
            }
        });
    });

    /** 确认收货*/
    $('.confirm_deliver').click(function(){
        var id = $(this).attr('attr_id');
        $("#confirm_sponsor").modal();
        $("#confirm_message").html($('.sure_msg').val());
        pre_deliver(id);
    });
    //未付款时可以取消订单
    $('.confirm_cancel').click(function(){
        var id = $(this).attr('attr_id');
        $("#confirm_sponsor").modal();
        $("#confirm_message").html($('.sure_msg2').val());
        pre_cancel_order(id);
    });
    $('.order_status,.order_type,.orders_month').change(function(){
        $('.order_search').submit();
    });
    /**-- 结束收货 --*/

    /** 我的关注*/
    $('.cancel_collection').click(function(){
        var goods_id = $(this).attr('attr_id');
        $.post("/ucenter/my_collection/cancel_collection", {goods_id:goods_id}, function (data) {
            if(data.success){
                location.reload();
            }else{
                layer.msg(data.msg);
            }
        },'json');
    });

    /*补单*/
    $('.repair_order_button').click(function(){

        var order_year_month = $(this).attr('attr_order_year_month');
        $.ajax({
            type: "POST",
            url: "/ucenter/commission_order_repair/repair_order",
            data: {order_year_month: order_year_month},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    location.reload();
                } else {
                    layer.msg(res.msg);
                }

            }
        });
    });

    /** ----- start copy  url */
    $("#copy_enroll_url").zclip({
        path : 'ucenter_theme/js/ZeroClipboard.swf',
        copy : $.trim($('.enroll_url').text()),
        beforeCopy:function(){
            $(this).css('color','#666');
        },
        afterCopy:function(){
            layer.msg($('.copy_store_url_msg').text()+$('.enroll_url').text());
        }
    });
    $("#copy_member_url").zclip({
        path : 'ucenter_theme/js/ZeroClipboard.swf',
        copy : $.trim($('.member_url').text()),
        beforeCopy:function(){
            $(this).css('color','#666');
        },
        afterCopy:function(){
            layer.msg($('.copy_store_url_msg').text()+$('.member_url').text());
        }
    });
    $("#copy_walhao_url").zclip({
        path : 'ucenter_theme/js/ZeroClipboard.swf',
        copy : $.trim($('.walhao_url').text()),
        beforeCopy:function(){
            $(this).css('color','#666');
        },
        afterCopy:function(){
            layer.msg($('.copy_store_url_msg').text()+$('.walhao_url').text());
        }
    });
    /** ---- end copy  url */

    /** 提现 **/
    $('#take_cash_type').change(function(){
        change_take_type($(this).val());
    });

});

function change_take_type($type){
    if($type == 6) {
        $(".debit_card").removeClass('hidden');
        $('.manually').addClass('hidden');
        $('.alipay').addClass('hidden');
        $('.maxie').addClass('hidden');
        $('.paypal').addClass('hidden');
    }else if($type == 5){
        $(".debit_card").addClass('hidden');
        $('.manually').addClass('hidden');
        $('.alipay').addClass('hidden');
        $('.maxie').addClass('hidden');
        $('.paypal').removeClass('hidden');
    }else if($type == 4){
        $(".debit_card").addClass('hidden');
        $('.paypal').addClass('hidden');
        $('.manually').addClass('hidden');
        $('.alipay').addClass('hidden');
        $('.maxie').removeClass('hidden');
    }else if ($type == 3){
        $(".debit_card").addClass('hidden');
        $('.paypal').addClass('hidden');
        $('.maxie').addClass('hidden');
        $('.alipay').addClass('hidden');
        $('.manually').removeClass('hidden');
    }else if ($type == 2){
        $(".debit_card").addClass('hidden');
        $('.paypal').addClass('hidden');
        $('.maxie').addClass('hidden');
        $('.manually').addClass('hidden');
        $('.alipay').removeClass('hidden');
    }
}

function pre_deliver(id){
    document.getElementById('confirm_ok').onclick = function(){
        confirm_deliver(id);
        $("#confirm_sponsor").modal('hide');
    }
    document.getElementById('confirm_cancel').onclick = function(){
        $("#confirm_sponsor").modal('hide');
    }
}

function confirm_deliver(id){
    $.post("/ucenter/my_orders_action/confirm_deliver", {id:id}, function (data) {
        if(data.success){
            location.reload();
        }else{
            layer.msg(data.msg);
        }
    },'json');
}

function pre_cancel_order(id){
    document.getElementById('confirm_ok').onclick = function(){
        confirm_cancel_order(id);
        $("#confirm_sponsor").modal('hide');
    }
    document.getElementById('confirm_cancel').onclick = function(){
        $("#confirm_sponsor").modal('hide');
    }
}

function confirm_cancel_order(id){
    $.post("/ucenter/my_orders_action/confirm_cancel", {id:id}, function (data) {
        if(data.success){
               location.reload();
        }else{
            layer.msg(data.msg);
            setTimeout(function () {
                location.reload();
            }, 2000);
        }
    },'json');
}


/***转月费***/
function transfer_to_month_fee(){
    $('#transfer_to_month_fee').modal();
}

/***转账***/
function transfer_to_cash(){
    $('#transfer_to_cash').modal();
}

/**佣金自动转入分红点**/
function commissions_to_sharing_point_auto(){
    $('#commissions_to_sharing_point_auto').modal();
}

function sharing_point_to_money(){
    $('#sharing_point_to_money').modal();
}

function add_fee(){
    $('#myModal').modal();
}

/*使用月费抵用券*/
function useMonthlyFeeCoupon(){
    $.ajax({
        type: "POST",
        url: "/ucenter/welcome/useMonthlyFeeCouponAjax",
        dataType: "json",
        success: function (data) {
            layer.msg(data.msg);
            if (data.success) {
                $('#monthlyFeeCouponText').text('');
            }
        }
    });
}


function saveSharingPointProportion(proportionType) {
    proportion = $('#' + proportionType).val();
    curMsgEle = $('#' + proportionType).parent().next().next('.msg');
    $.ajax({
        type: "POST",
        url: "/ucenter/commission/saveSharingPointProportion",
        data: {proportionType: proportionType, proportion: proportion},
        dataType: "json",
        success: function (data) {
            if (data.success) {
                curMsgEle.removeClass('error');
                curMsgEle.addClass('success');
                curMsgEle.text(data.msg);
                curMsgEle.show();
                setTimeout(function () {
                    curMsgEle.hide();
                    location.reload();
                }, 2000);
            } else {
                curMsgEle.removeClass('success');
                curMsgEle.addClass('error');
                curMsgEle.text(data.msg);
                curMsgEle.show();
            }

        }
    });
}

/**
 * 选择(月费&店铺)等级
 */
function selUpallLevel(){
    levelId  = $('#upall_level option:selected').val();

    var payment_method = $("[name='upall_payment_method']").filter(":checked").val();

    if(!levelId || !payment_method){
        return;
    }

    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/getUpgradeAllMoney",
        data: {levelId: levelId,payment_method:payment_method},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#upall_amount').val(res.data.needMoney.money);
                $('#upall_format_amount').html(res.data.needMoney.format_money);
                $('#upall_msg_level').text('');
                $('#upall_msg_amount').text('');
            } else {
                $('#upall_amount').val('');
                $('#upall_format_amount').html('');
                $('#upall_msg_level').text('× '+res.msg);
            }

        }
    });
}

/**
 * 选择用户等级
 * @returns {undefined}
 */
function selMemberLevel(){


    levelId  = $('#level option:selected').val();

    if(!levelId){
        return;
    }

    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/getUpgradeMoney",
        data: {levelId: levelId},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#amount').val(res.data.needMoney.money);
                $('#format_amount').html(res.data.needMoney.format_money);
                $('#msg_level').text('');
                $('#msg_amount').text('');
            } else {
                $('#amount').val('');
                $('#format_amount').html('');
                $('#msg_level').text('× '+res.msg);
            }

        }
    });
}

/**
 * 月費
 * @returns {undefined}
 */
function monthFee(){

    flag  = false
    levelId  = $('[name="level"]').filter(":checked").val();

    var payment_method = $("[name='month_payment_method']").filter(":checked").val();

    if(!levelId || !payment_method){
        return;
    }

    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/getUpgradeMonth",
        data: {levelId: levelId,payment_method:payment_method},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#month_amount').val(res.msg.money);
                $('#month_format_amount').html(res.msg.format_money);
                $('#usd_amount').val(res.msg.usd_money);
                $('#month_msg_level').text('');
                $('#month_msg_amount').text('');
            } else {
                $('#month_amount').val('');
                $('#month_format_amount').html('');
                $('#month_msg_level').text('× '+res.msg);
            }

        }
    });
}

/**
 * 用户升级时完善资料（选择国家）
 */
function selInfoContry(conId){
    if(conId==='1'){
        $('#info_id_card_num').show();
    }else{
        $('#info_id_card_num').hide();
    }
}



/**
 * 提交完善信息。
 */
function sub_info(){
    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/sub_info",
        data: $("#info_form").serialize(),
        dataType: "json",
        success: function (res) {
            
            $.each(res.data.error, function (itemName, checkResultVal) {
                if (checkResultVal) {
                    $('#msg_' + itemName).text('× ' + checkResultVal);
                } else {
                    $('#msg_' + itemName).text('');
                }
            });
            if (res.success) {
                $('#msg_info').text('√ '+res.msg);
                setTimeout(function () {
                    $('#msg_pay').text('');
                    $('#info_block').hide();
                }, 500);
            } else {
                $('#msg_info').text('');
                $.each(res.data.error, function (itemName, checkResultVal) {
                    if(checkResultVal){
                        $('#msg_'+itemName).text('× '+checkResultVal);
                    }else{
                        $('#msg_'+itemName).text('');
                    }
                });
            }
        }
    });
}

    /**
     * 去支付
     */
    function go_pay(){
        var agree = $("[name='upgrade_agree']").filter(":checked").val();
        var agree2 = $("[name='agree']").filter(":checked").val();
        if(!agree){
            $('#msg_upgrade_agree').html('× ' +$('#agreeMsg').val());
        }
        if(!agree2){
            $('#month_msg_agree').html('× '+ $('#agreeMsg').val());
        }
        if($('#is_can_upgrade').val()=='1'){
            $('#you_not_choose').css('display','block');
            return;
        }
        if(!agree || !agree2){
            return;
        }
        self_confirm(prints($('#alert_register').val(),$('#parent_id').val()),confirm_go_pay,confirm_cancel);
    }

    function confirm_go_pay(){
        $.ajax({
            type: "POST",
            url: "/ucenter/member_upgrade/go_pay",
            data: $("#pay_form").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                        location.href = '/choose_goods_for_upgrade';
                } else {

                    $('#upgrade_fee').attr('disabled',false).show();
                    if(res.msg){
                        $('#msg_pay').text('× ' + res.msg).addClass('msg');
                        return false;
                    }

                    $('#msg_pay').text('');
                    $.each(res.data.error, function (itemName, checkResultVal) {
                        if(checkResultVal){
                            $('#msg_'+itemName).text('× '+checkResultVal);
                        }else{
                            $('#msg_'+itemName).text('');
                        }
                    });
                }
            }
        });
    }
function upall_go_pay() {
    
    var upall_upgrade_agree = $("[name='upall_upgrade_agree']").filter(":checked").val();
    var upall_agree = $("[name='upall_agree']").filter(":checked").val();
    if(!upall_upgrade_agree){
        $('#msg_upall_upgrade_agree').html('× ' +$('#upall_agreeMsg').val());
    }
    if(!upall_agree){
        $('#msg_upall_agree').html('× ' +$('#upall_agreeMsg').val());
    }
    if(!upall_upgrade_agree || !upall_agree){
        return;
    }
    self_confirm(prints($('#alert_register').val(),$('#parent_id').val()),confirm_upall_go_pay,confirm_cancel);
}
    function confirm_upall_go_pay(){
        var upall_payment = $("[name='upall_payment_method']").filter(":checked").val();
        $.ajax({
            type: "POST",
            url: "/ucenter/member_upgrade/upall_go_pay",
            data: $("#upall_pay_form").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    if(upall_payment == 'eWallet'){
                        do_ewallet_upall();
                    }else{
                        $('#upall_pay_form').submit();
                    }

                } else {

                    $('#up_all_upgrade').attr('disabled',false).show();
                    if (res.msg) {
                        $('#upall_msg_pay').text('× ' + res.msg);
                        return false;
                    }
                    $('#upall_msg_pay').text('');
                    $.each(res.data.error, function (itemName, checkResultVal) {
                        if (checkResultVal) {
                            $('#msg_' + itemName).text('× ' + checkResultVal);
                        } else {
                            $('#msg_' + itemName).text('');
                        }
                    });
                }
            },
            beforeSend:function(){

                $('#up_all_upgrade').attr('disabled','disabled');
                if(upall_payment == 'eWallet'){
                    $("#upall_msg_pay").removeClass('msg').addClass('alert-error').html($('#ewallet_before').val());
                    $('#up_all_upgrade').hide();
                }
            }
        });
    }
    function confirm_cancel(){}
    function self_confirm(msg,callback_OK,callback_CANCEL){
        $("#confirm_sponsor").modal();
        $("#confirm_message").html(msg);
        document.getElementById('confirm_ok').onclick = function(){
            document.getElementById("confirm_ok").disabled=true;
            if(callback_OK && typeof callback_OK == "function")
                callback_OK( true );
            $("#confirm_sponsor").modal('hide');
            document.getElementById("confirm_ok").disabled=false;
        }
        document.getElementById('confirm_cancel').onclick = function(){
            if(callback_CANCEL && typeof callback_CANCEL== "function")
                callback_CANCEL( false );
            $("#confirm_sponsor").modal('hide');
        }
        $('tranToMemBtn').attr('disabled',false);
        return false;
    }
/**
 * 去激活等級
 */
function go_enable(){

    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/go_enable",
        data: $("#go_payment").serialize(),
        dataType: "json",
        success: function (res) {
            if (res.success) {

                $('#go_payment').submit();

            } else {
                $('#msg_pay').text('');
                $.each(res.data.error, function (itemName, checkResultVal) {
                    if(checkResultVal){
                        $('#msg_'+itemName).text('× '+checkResultVal);
                    }else{
                        $('#msg_'+itemName).text('');
                    }
                });
            }
        }
    });
}

/**
 * 第一步 升級月費
 */
function go_upgrade_month(){

    levelId  = $('[name="level"]').filter(":checked").val();
    var payment_method = $("[name='month_payment_method']").filter(":checked").val();
    var agree = $("[name='agree']").filter(":checked").val();
    if(!agree){
        $('#month_msg_agree').html('× ' +$('#agreeMsg').val());
    }
    if(!levelId || !payment_method || !agree){
        return;
    }
    self_confirm(prints($('#alert_register').val(),$('#parent_id').val()),confirm_go_upgrade_month,confirm_cancel);

}
    function confirm_go_upgrade_month(){
        var payment_method = $("[name='month_payment_method']").filter(":checked").val();
        $.ajax({
            type: "POST",
            url: "/ucenter/member_upgrade/go_upgrade_month",
            data: $("#month_pay_form").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {

                    if(payment_method == '103'){
                        do_ewallet();
                    }else{
                        $('#month_pay_form').submit();
                    }

                } else {

                    $('#upgrade_month_fee').attr('disabled',false).show();
                    if(res.msg){
                        $('#month_msg_pay').html('× '+ res.msg).addClass('msg');
                        return false;
                    }
                    $('#month_msg_pay').text('');
                    $.each(res.data.error, function (itemName, checkResultVal) {
                        if(checkResultVal){
                            $('#month_msg_'+itemName).text('× '+checkResultVal);
                        }else{
                            $('#month_msg_'+itemName).text('');
                        }
                    });
                }
            },
            beforeSend:function(){

                $('#upgrade_month_fee').attr('disabled','disabled');
                if(payment_method == 'eWallet'){
                    $("#month_msg_pay").addClass('alert-error').html($('#ewallet_before').val());
                    $('#upgrade_month_fee').hide();
                }
            }
        });
    }
function prints() {
    var num = arguments.length;
    var oStr = arguments[0];
    for (var i = 1; i < num; i++) {
        var pattern = "\\{" + (i-1) + "\\}";
        var re = new RegExp(pattern, "g");
        oStr = oStr.replace(re, arguments[i]);
    }
    return oStr;
}
/**
 * 去充值月費
 */
function go_month(){
    var payment_method = $("[name='payment_method']").filter(":checked").val();
    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/go_month",
        data: $("#go_payment").serialize(),
        dataType: "json",
        success: function (res) {
            if (res.success) {
                //if(payment_method == 'eWallet'){
                    //do_ewallet_month();
                //}else{
                    $('#go_payment').submit();
                //}
            } else {
                $('#go_month').attr('disabled',false).show();
                $.each(res.data.error, function (itemName, checkResultVal) {
                    if(checkResultVal){
                        $('#msg_'+itemName).text('× '+checkResultVal);
                    }else{
                        $('#msg_'+itemName).text('');
                    }
                });
            }
        },
        beforeSend:function(){
            $('#go_month').attr('disabled','disabled');
            if(payment_method == 'eWallet'){
                $("#msg_pay").addClass('alert-error').html($('#ewallet_before').val());
                $('#go_month').hide();
            }
        }
    });
}

function selPaymentMethod(){
    $('#msg_payment_method').text('');
    $('#msg_amount').text('');
}

function selPaymentMethodForUpall(){
    $('#upall_msg_payment_method').text('');
    $('#upall_msg_amount').text('');
}

/**
 * 刷新分红点的显示
 * @param {type} newPoint
 * @returns {undefined}
 */
function updateSharingPoint(newPoint) {
    $('#profitSharingPoint').text(newPoint);
    /*$('#curSharingPoint').text(newPoint);*/
}

/**
 * 更新用户现金余额。
 * @returns {undefined}
 */
function updateUserMoney(newUserMoney) {
    $('#curAmount').text(newUserMoney);
}

function manuallyAddSharingPoint() {
    money = $('#manuallyMoney').val();
    curMsgEle = $('#manuallyMsg');
    $.ajax({
        type: "POST",
        url: "/ucenter/commission/manuallyAddSharingPoint",
        data: {money: money},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                curMsgEle.removeClass('error');
                curMsgEle.addClass('success');
                curMsgEle.text(res.msg);
                curMsgEle.show();
                $('#loseMoneyMsg').text('- $' + money + ' = $' + res.data.newAmount);
                $('#loseMoneyMsg').show();

                setTimeout(function () {
                    curMsgEle.hide();
                    $('#loseMoneyMsg').hide();
                    updateUserMoney(res.data.newAmount);
                    updateSharingPoint(res.data.newProfitSharingPoint);
                    $('#profitSharingPointManually,#curSharingPoint').text(res.data.newProfitSharingPointManually);
                }, 2000);
            } else {
                curMsgEle.removeClass('success');
                curMsgEle.addClass('error');
                curMsgEle.text(res.msg);
                curMsgEle.show();
            }

        }
    });
}

function sharingPointToMoney() {
    $('#sharingPointToMoneyBtn').attr('disabled',true);
    point = $('#pointNeedToMove').val();
    curMsgEle = $('#pointToMoneyMsg');
    pointText = $('#pointText').val();
    $.ajax({
        type: "POST",
        url: "/ucenter/commission/sharingPointToMoney",
        data: {point: point},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                curMsgEle.removeClass('error');
                curMsgEle.addClass('success');
                curMsgEle.text(res.msg);
                curMsgEle.show();
                $('#losePointMsg').text('- ' + point + pointText + ' = ' + res.data.newProfitSharingPoint + pointText);
                $('#losePointMsg').show();
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else {
                curMsgEle.removeClass('success');
                curMsgEle.addClass('error');
                curMsgEle.text(res.msg);
                curMsgEle.show();
                $('#sharingPointToMoneyBtn').attr('disabled',false);
            }

        }
    });
}

function cashToMonthFee(){
    $('#cashToMonthFeeBtn').attr('disabled',true);
    money = $('#cashForMonthFee').val();
    curMsgEle = $('#cashToMonthFeeMsg');
    $.ajax({
        type: "POST",
        url: "/ucenter/commission/cashToMonthFee",
        data: {money: money},
        dataType: "json",
        success: function (res) {

            if (res.success) {
                curMsgEle.removeClass('error');
                curMsgEle.addClass('success');
                curMsgEle.text(res.msg);
                curMsgEle.show();
                $('#loseMoneyMsg').text('- $' + money + ' = $' + res.data.newAmount);
                $('#loseMoneyMsg').show();

                setTimeout(function () {
                    curMsgEle.hide();
                    $('#loseMoneyMsg').hide();
                    updateUserMoney(res.data.newAmount);
                    $('#month_fee_pool').text(res.data.newMonthFee);
                    location.reload();
                }, 2000);
            } else {
                $('#cashToMonthFeeBtn').attr('disabled',false);
                curMsgEle.removeClass('success');
                curMsgEle.addClass('error');
                curMsgEle.text(res.msg);
                curMsgEle.show();
            }

        }
    });
}

function tranToMem(){
    $('tranToMemBtn').attr('disabled',true);
    var curMsgEle = $('#tranToMemMsg');
    var amount = $('#tranToMemAmount').val();
    var uid = $('#tranToMemId').val();
    var pwd = $('#tranToMemFundsPwd').val();
    if(amount == '' || amount <= 0 ){
        $('#tranToMemMsg').html('×'+$('#positive_num_error').val());
        $('tranToMemBtn').attr('disabled',false);
        return;
    }
    if(uid == ''){
        $('#tranToMemMsg').html('×'+$('#user_id_list_requied').val());
        $('tranToMemBtn').attr('disabled',false);
        return;
    }
    if(pwd == ''){
        $('#tranToMemMsg').html('×'+$('#funds_pwd_error').val());
        $('tranToMemBtn').attr('disabled',false);
        return;
    }
    $.ajax({
        type: "POST",
        url: "/ucenter/commission/tranToMem",
        data: {tranToMemAmount: $('#tranToMemAmount').val(),tranToMemId:$('#tranToMemId').val(),tranToMemFundsPwd:$('#tranToMemFundsPwd').val(),ischeck:true,vcode:$("input[name='vcode']").val()},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                self_confirm(res.data.alertMsg,confirm_tranToMem,confirm_cancel);
            } else {
                curMsgEle.removeClass('success');
                curMsgEle.addClass('error');
                curMsgEle.text(res.msg);
                curMsgEle.show();
                $('tranToMemBtn').attr('disabled',false);
            }

        }
    });
}
    function confirm_tranToMem(){
        var curMsgEle = $('#tranToMemMsg');
        $.ajax({
            type: "POST",
            url: "/ucenter/commission/tranToMem",
            data: {tranToMemAmount: $('#tranToMemAmount').val(),tranToMemId:$('#tranToMemId').val(),tranToMemFundsPwd:$('#tranToMemFundsPwd').val()},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    curMsgEle.removeClass('error');
                    curMsgEle.addClass('success');
                    curMsgEle.text(res.msg);
                    curMsgEle.show();
                    $('#tranToMemFundsPwd').val('');
                    updateUserMoney(res.data.newAmount);
                    setTimeout(function () {
                        curMsgEle.hide();
                        location.reload();
                    }, 2000);
                } else {
                    curMsgEle.removeClass('success');
                    curMsgEle.addClass('error');
                    curMsgEle.text(res.msg);
                    curMsgEle.show();
                }

            }
        });
    }
function addZeroforNumLessTen(num) {
    return num >= 10 ? num : '0' + num;
}

function hiddenAlert($this,time){
    setTimeout(function () {
        $this.addClass('hidden');
    }, time);
}

$(document).ready(function () {
    $("button[name='account_info']").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
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
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    $('.account_info').removeClass('hidden');
                    $('.account_info').addClass('alert-error');
                    $('.account_info strong').html(data.msg);
                    curEle.attr("disabled", false);
                }
                curEle.html(oldSubVal);
            }
        });
    });
    
    $("#take_cash_submit_button").click(function () {

        var take_cash_type=$('[name="take_cash_type"] option:selected').val();
        var take_out_pwd=$('input[name="take_out_pwd"]').val();
        var take_out_amount=$('input[name="take_out_amount"]').val();
        var paypal_email=$('.paypal_email').val();
        if(take_cash_type == ''){
            $('#cash_submit_msg').text(' ×'+$('#pls_sel_take_out_type').val()).removeClass('success');
            return;
        }
        if(take_out_amount == ''||take_out_amount>60000){
            $('#cash_submit_msg').text(' ×'+$('#pls_input_correct_amount').val()).removeClass('success');
            return;
        }
        if(take_out_amount != '' && take_out_amount < 100){
            $('#cash_submit_msg').text(' ×'+$('#pls_input_correct_amount2').val()).removeClass('success');
            return;
        }
        if(take_out_pwd == ''){
            $('#cash_submit_msg').text(' ×'+$('#pls_input_correct_take_out_pwd').val()).removeClass('success');
            return;
        }

        if(take_cash_type == 3){

            var account_bank=$('input[name="account_bank"]').val();
            var subbranch_bank=$('input[name="subbranch_bank"]').val();
            var account_name=$('input[name="account_name"]').val();
            var card_number=$('input[name="card_number"]').val();
            var c_card_number=$('input[name="c_card_number"]').val();

            if((account_bank =='' || subbranch_bank =='' || account_name =='' || card_number =='' || c_card_number =='')){
                $('#cash_submit_msg').text(' ×'+$('#payee_info_incomplete').val()).removeClass('success');
                return;
            }
            if(card_number != c_card_number ){
                $('#cash_submit_msg').text(' ×'+$('#card_number_match').val()).removeClass('success');
                return;
            }
            if((take_out_amount !='' && take_cash_type !='' && take_out_pwd !='' && account_bank !=''
                && subbranch_bank !='' && account_name !='' && card_number !='' && c_card_number !='' )){
                self_confirm(prints($('#confirm_bank_info').val(),account_bank,subbranch_bank,card_number,account_name),confirm_take_cash,confirm_cancel);
            }
        }else if(take_cash_type == 4){
                var maxie_card_number = $('input[name="maxie_card_number"]').val();
                var c_maxie_card_number = $('input[name="c_maxie_card_number"]').val();
                if(maxie_card_number == '' || c_maxie_card_number == ''){
                    $('#cash_submit_msg').text(' ×'+$('#payee_info_incomplete').val()).removeClass('success');
                    return;
                }
                if(maxie_card_number != c_maxie_card_number ){
                    $('#cash_submit_msg').text(' ×'+$('#card_number_match').val()).removeClass('success');
                    return;
                }
                self_confirm(prints($('#confirm_maxie_info').val(),maxie_card_number),confirm_take_cash,confirm_cancel);

        }else if(take_cash_type == 2){
            var account = $(".alipay_cc").val();
            if(account == ''){
                $('#cash_submit_msg').text(' ×'+$('#not_fill_alipay_account').val()).removeClass('success');
                return;
            }
            self_confirm(prints($('#confirm_alipay_info').val(),account),confirm_take_cash,confirm_cancel);
        }else if(take_cash_type == 5){
            var paypal_account = $(".paypal_email").val();
            if(paypal_account == ''){
                $('#cash_submit_msg').text(' ×'+$('#paypal_email').val()).removeClass('success');
                return;
            }
            self_confirm(prints($('#confirm_paypal_info').val(),paypal_account),confirm_take_cash,confirm_cancel);
        }else if(take_cash_type == 6) {
            var bank_name=$('input[name="bank_name"]').val();
            var bank_number=$('input[name="bank_number"]').val();
            if (bank_name.length <= 0 || bank_number.length <= 0 ) {
                $('#cash_submit_msg').text(' ×'+$('#has_not_bind_bank_1').val()).removeClass('success');
                return ;
            }
            self_confirm(prints($('#confirm_banks_info').val(),bank_number),confirm_take_cash,confirm_cancel);
        }

    });
    function confirm_take_cash(){

            var curEle = $('#take_cash_submit_button');
            var oldSubVal = curEle.val();
            curEle.val($('#loadingTxt').val());
            curEle.attr("disabled", "disabled");
            var data = $('#take_out_form').serialize();

            var bank_name = $.trim($("input[name='bank_name']").val());
            var bank_branch_name = $.trim($("input[name='bank_branch_name']").val());
            var bank_number = $.trim($("input[name='bank_number']").val());
            var bank_user_name = $.trim($("input[name='bank_user_name']").val());

            data +="&bank_name="+bank_name;
            data +="&bank_branch_name="+bank_branch_name;
            data +="&bank_number="+bank_number;
            data +="&bank_user_name="+bank_user_name;
            console.log(data);
            $.ajax({
                type: "POST",
                url: "/ucenter/take_out_cash/submit",
                data:data ,
                dataType: "json",
                success: function (res) {
                    $('#cash_submit_msg').text(' ×'+res.msg);
                    if (res.success) {
                        $('#cash_submit_msg').addClass('success');
                    } else {
                        $('#cash_submit_msg').removeClass('success');
                        curEle.attr("disabled", false);
                        curEle.val(oldSubVal);
                    }
                    setTimeout(function () {
                        if(res.success){
                            location.href='/ucenter/cash_take_out_logs'
                        }
                    }, 3000);
                }
            });

    }

    $("#set_take_cash_pwd_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        console.log($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/take_out_cash/set_take_cash_pwd_submit",
            data: $('#set_take_cash_pwd_form').serialize(),
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    $('#take_out_pwd_msg').text(data.msg);
                    setTimeout(function () {
                        $('#myModal').modal('hide');
                        $('#set_take_cash_pwd_td').addClass('hide');
                        $('#modify_take_cash_pwd_td').removeClass('hide');
                    }, 500);
                } else {
                    $('#take_out_pwd_msg').text(data.msg);
                    curEle.attr("disabled", false);
                }
                curEle.html(oldSubVal);
            }
        });
    });
    
    $("#modify_take_cash_pwd_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/take_out_cash/modify_take_cash_pwd_submit",
            data: $('#modify_take_cash_pwd_form').serialize(),
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    $('#modify_take_out_pwd_msg').text(data.msg);
                    setTimeout(function () {
                        $('#modify_take_cash_pwd_div').modal('hide');
                        $('#set_take_cash_pwd_td').addClass('hide');
                        $('#modify_take_cash_pwd_td').removeClass('hide');
                    }, 1500);
                    location.reload();
                } else {
                    $('#modify_take_out_pwd_msg').text(data.msg);
                    curEle.attr("disabled", false);
                }
                curEle.html(oldSubVal);
            }
        });
    });
    
    $("#modify_store_url_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/modify_store_url_submit",
            data: $('#modify_store_url_form').serialize(),
            dataType: "json",
            success: function (res) {
                $('#modify_store_url_msg').text(res.msg);
                if (res.success) {
                    location.reload();
                    setTimeout(function () {
                        $('#modify_store_url_modal').modal('hide');
                        if (res.data) {
                            $('#store_url_a').text(res.data.store_url);
                            $("#store_url_a").attr("href", "http://" + res.data.store_url);
                            $("#storeModifyleftCounts").text(res.data.storeModifyleftCounts);
                        }
                        curEle.attr("disabled", false);
                        curEle.html(oldSubVal);
                        $.ajax({
                            type: "POST",
                            url: "/ucenter/account_info/modify_store_url_sync_wohao",
                            dataType: "json",
                            success:function(res){}
                        });
                    }, 500);
                }else{
                    curEle.attr("disabled", false);
                    curEle.html(oldSubVal);
                }
            }
        });
    });

    $("#modify_store_name_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/modify_store_name_submit",
            data: $('#modify_store_name_form').serialize(),
            dataType: "json",
            success: function (res) {
                $('#modify_store_name_msg').text(res.msg);
                if (res.success) {
                    setTimeout(function () {
                        $('#modify_store_name_modal').modal('hide');
                        $('.member_store_name').text(res.store_name);
                        curEle.attr("disabled", false);
                        curEle.html(oldSubVal);
                    }, 500);
                }else{
                    curEle.attr("disabled", false);
                    curEle.html(oldSubVal);
                }
            }
        });
    });

    /**
     * 修改店铺网址
     */
    $("#modify_member_url_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/modify_member_url_submit",
            data: $('#modify_member_url_form').serialize(),
            dataType: "json",
            success: function (res) {
                $('#modify_member_url_msg').text(res.msg);
                if (res.success) {
                    setTimeout(function () {
                        location.reload();
                        $('#modify_member_url_modal').modal('hide');
                        if (res.data) {
                            $('#member_url_a').text(res.data.member_url);
                            $("#member_url_a").attr("href", "http://" + res.data.member_url);
                            $("#memberModifyleftCounts").text(res.data.memUrlModifyleftCounts);
                        }
                        curEle.attr("disabled", false);
                        curEle.html(oldSubVal);
                    }, 500);
                }else{
                    curEle.attr("disabled", false);
                    curEle.html(oldSubVal);
                }
            }
        });
    });

    /*月费等级降级提交。*/
    $("#changeMonthliFeeLevelFormSub").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/member_upgrade/changeMonthliFeeLevelFormSub",
            data: $('#changeMonthliFeeLevelForm').serialize(),
            dataType: "json",
            success: function (res) {
                $('#changeMonthliFeeLevelFormMsg').text(res.msg);
                if (res.success) {
                    setTimeout(function () {
                        $('#changeMonthlyLevelModal').modal('hide');
                        curEle.attr("disabled", false);
                        curEle.html(oldSubVal);
                        $('#changeMonthliFeeLevelFormMsg').text('');
                        $('#monthFeeLevelChangeNote').html(res.monthFeeLevelChangeNote);
                    }, 500);
                }else{
                    curEle.attr("disabled", false);
                    curEle.html(oldSubVal);
                    setTimeout(function () {
                        $('#changeMonthliFeeLevelFormMsg').text('');
                    }, 2000);
                }
            }
        });
    });
});

/**
 * 设置提现密码
 */
function set_take_cash_pwd(){
    $.ajax({
        type: "POST",
        url: "/ucenter/take_out_cash/set_take_cash_pwd",
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#myModal').modal();
            } else {
                $('#cash_pwd_msg').text(res.msg);
            }
        }
    });
}

/**
 * 修改提現密碼
 */
function modify_take_cash_pwd(){
    $.ajax({
        type: "POST",
        url: "/ucenter/take_out_cash/modify_take_cash_pwd",
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#modify_take_cash_pwd_div').modal();
            } else {
                $('#cash_pwd_msg').text(res.msg);
            }
        }
    });
}

/**
 * 修改会员url
 */
function modify_member_url(){
    $.ajax({
        type: "POST",
        url: "/ucenter/account_info/modify_member_url",
        dataType: "json",
        success: function (res) {

            if (res.success) {
                $('#modify_member_url_msg').text('');
                $('#modify_member_url_modal').modal();
            }else{
                layer.msg(res.msg);
            }
        }
    });
}

/**
 * 修改店铺url
 */
function modify_store_url(){
    $.ajax({
        type: "POST",
        url: "/ucenter/account_info/modify_store_url",
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#modify_store_url_msg').text('');
                $('#modify_store_url_modal').modal();
            }else{
                layer.msg(res.msg);
            }
        }
    });
}

/**
 * 修改店铺名称
 */
function modify_store_name(){

    $('#modify_store_name_modal').modal();

}

/**
 * 更换月费登记
 */
function changeMonthlyLevelPop(){
    $.ajax({
        type: "POST",
        url: "/ucenter/member_upgrade/changeMonthlyLevelPop",
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#changeMonthlyLevelPopMsg').text('');
                $("#monthlyFeeLevel").html(res.optionHtml);
                $('#changeMonthlyLevelModal').modal();
            } else {
                $('#changeMonthlyLevelPopMsg').text(res.msg);
                setTimeout(function () {
                    $('#changeMonthlyLevelPopMsg').text('');
                }, 2000);
            }
        }
    });
}

function viewSuitCouponRule(){
    $('#suitCouponRuleModal').modal();
}

function create_ewallet(){
    $.ajax({
        type: "POST",
        url: "/ucenter/ewallet/register",
        dataType: "json",
        data:{ewallet_name:$('#ewallet_name').val()},
        success: function (res) {
            if (res.success) {
                $("#ewallet_info").html(res.msg).addClass('alert alert-success').removeClass('msg');
               setTimeout(function(){
                    location.reload();
                },3000);
            } else {
                $('#ewallet_submit').attr('disabled',false).show();
                $("#ewallet_info").html('× '+res.msg);
            }
        },
        beforeSend:function(){
            $('#ewallet_submit').attr('disabled','disabled').hide();
            $("#ewallet_info").html($('#ewallet_before').val());
        }
    });
}
    function check_all_msg(_this){
        var ips = $('input[type="checkbox"]');
        for( var i=0;i<ips.length;i++ ){
            if(!ips[i].disabled && ips[i].name == 'checkboxes[]' )
                ips[i].checked = _this.checked;
        }
        is_checked_msg();
    }

    function is_checked_msg(){
        var is_checked = true;
        var ips = $('input[type="checkbox"]');
        for( var i =0;i<ips.length;i++ ){
            if(ips[i].checked && ips[i].name == 'checkboxes[]' ){
                is_checked = false;
                break;
            }
        }
        $('.btn1_cs,.btn2_cs').attr('disabled',is_checked);
    }

    function choose_goods(){
        $('#hidden_data').submit();
    }

    /** 评论 */
    function do_evaluate(tfd){
        $(tfd).attr('disabled',true);
        $(tfd).css('color','#666');
        $.ajax({
            type: "POST",
            url: "/ucenter/my_orders_new/do_evaluate",
            dataType: "json",
            data:$('.evaluate_form').serialize(),
            success: function (res) {
                if(res.success){
                    location.href = res.url;
                }else{
                    layer.msg(res.msg);
                    $(tfd).attr('disabled',false);
                    $(tfd).css('color','#dff0ff');
                }
            }
        });
    }

    function starchange(obj1,num)
    {
        $('#'+obj1+'>input:first').val(Number(num)+1);

        var s = document.getElementById(obj1),
            n = s.getElementsByTagName("li");
        clearAll = function ()
        {
            for (var i = 0; i < n.length; i++)
            {
                n[i].className = '';
            }
        }
        for(var i = 0; i < n.length; i++)
        {
            var q = n[num].getAttribute("rel");
            clearAll();
            for (var j = 0; j < (Number(q)+1); j++)
            {
                n[j].className = 'on';
            }
        }
    }

    /** 检测订单中心的操作 当前区域和收货区域是否一致 */
    function  check_location(order_id,next_href){
        $.ajax({
            type: "POST",
            url: "/ucenter/my_orders_action/check_location",
            dataType: "json",
            data:{order_id:order_id},
            success: function (res) {
                if(res.success){
                    location.href = next_href;
                }else{
                    $("#confirm_sponsor").modal();
                    $("#confirm_message").html($('.ucenter_loc_sure').val());
                    pre_location_cookie(res.other,next_href);
                }
            }
        });
    }

    function pre_location_cookie(data,next_href){
        document.getElementById('confirm_ok').onclick = function(){
            location_cookie(data,next_href);
            $("#confirm_sponsor").modal('hide');
        }
        document.getElementById('confirm_cancel').onclick = function(){
            $("#confirm_sponsor").modal('hide');
        }
    }

    function location_cookie(data,next_href){
        $.ajax({
            type: "POST",
            url: "/common/changeLanguage",

            data: {location_id:data.location_id,location_lang:data.location_lang,currency_id:data.currency_id},
            dataType: "json",
            success: function (res) {
                if(res.success){
                    location.href = next_href;
                }else{
                    location.reload();
                }
            }
        });
    }
