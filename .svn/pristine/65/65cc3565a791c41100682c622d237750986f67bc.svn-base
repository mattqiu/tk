/* global layer */

$(document).ready(function () {
    $("[rel=tooltip]").tooltip();
    $('#login_form input[name="user_name"]').focus();

    /***See User's BackOffice***/
    $('[id="review"]').click(function(){
    
        var redirect = request('redirect');
        var url = redirect ? redirect :'ucenter';
        var uid=$(this).attr('uid');
        $.ajax({
            type: "POST",
            url: '/login/seeUserBackNew',
            data: {uid: uid},
            dataType: "json",
            async:false,
            success: function (data) {
                if (data.success) {
                    $('[id="review"]').attr('target','_blank');
                    $('[id="review"]').attr('href','/'+ url);
                    //window.location.href = '/'+ url;
                    //window.open('/'+ url);
                }else{                	
                    layer.msg(data.msg);
                    $('[id="review"]').attr('target','_self');
                    $('[id="review"]').attr('href',"###");
                }
            }
        });
    });

    $("#btn_reset_user_pwd").click(function(){
        $.ajax({
            type: "POST",
            url: "/admin/reset_user_pwd/check_data",
            data: $('#reset_user_pwd').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('.reset_user_pwd_ok').text(res.msg);
                    $('.reset_user_pwd_msg').text("");
                } else {
                    $('.reset_user_pwd_msg').text(res.msg);
                    $('.reset_user_pwd_ok').text("");
                }
            }
        });
    });

    /*佣金特别处理-加入发奖列队*/
    $("#addQualifiedBtn").click(function(){
        $('#addQualifiedBtn').attr("disabled","disabled");
        $.ajax({
            type: "POST",
            url: "/admin/commission_special_do/add_comm_qualified",
            data: $('#addQualifiedFrom').serialize(),
            dataType: "json",
            success: function (res) {
                layer.msg(res.msg);
                if (res.success) {
                    setTimeout(function () { document.getElementById("addQualifiedFrom").reset();$('#addQualifiedBtn').attr("disabled",false); $("#new_member_bonus_display").css('display',"none"); }, 1000);
                }else{
                    $('#addQualifiedBtn').attr("disabled",false);
                }
            }
        });
    });

    /*佣金特别处理-补发奖金*/
    var sub_flag = true
    $("#fix_user_commission_btn").click(function(){
        $('#fix_user_commission_btn').attr("disabled","disabled");
        $('#fix_user_commission_btn').val("处理中");
        //防重复提交

        if (sub_flag == true) {
            sub_flag = false;
            $.ajax({
                type: "POST",
                url: "/admin/commission_special_do/fix_user_commission",
                data: $('#fix_user_commission').serialize(),
                dataType: "json",
                success: function (res) {
                    layer.msg(res.msg);
                    if (res.success) {
                        setTimeout(function () { document.getElementById("fix_user_commission").reset();$('#fix_user_commission_btn').attr("disabled",false); }, 1000);
                        $('#fix_user_commission_btn').val("提交");
                    }else{
                        $('#fix_user_commission_btn').attr("disabled",false);
                        $('#fix_user_commission_btn').val("提交");
                    }
                    sub_flag = true;
                }
            });
        } else {
            layer.msg("请勿重复提交");
        }

    });

    $("#btn_reprocess_order").click(function(){
        $.ajax({
            type: "POST",
            url: "/admin/reprocess_order/do_reprocess_order",
            data: $('#reprocess_order').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('.reprocess_order_ok').text(res.msg);
                    $('.reprocess_order_msg').text("");
                } else {
                    $('.reprocess_order_msg').text(res.msg);
                    $('.reprocess_order_ok').text("");
                }
            }
        });
    });

    /** 支付方式的修改 by john */
    $(".edit_payment_button").click(function(){
        $.ajax({
            type: "POST",
            url: "/admin/edit_payment/do_edit",
            data: $('.edit_payment_form').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('.edit_payment_msg').html(res.msg).addClass('text-success');
                    setTimeout(function(){
                        location.reload();
                    },2000)

                } else {

                }
            }
        });
    });

    /** 订单报表选择月份提交查询 */
    /*$('.orders_month').change(function(){
        $('#order_search').submit();
    });*/

    $("#delete_users").click(function(){
        var curEle = $(this);
        var oldSubVal = curEle.val();
        $(this).attr("value", $('#loadingTxt').val());
        $(this).attr("disabled","disabled");
        $.ajax({
            type: "POST",
            url: "/admin/delete_users/do_delete",
            data: $('#do_delete').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#delete_users_msg').text(res.msg).addClass('text-success');
                    $('#user_id,#confirm_user_id').val('');
                    location.reload();
                } else {
                    $('#delete_users_msg').text(res.msg).addClass('text-error');
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                }
            }
        });
    });

    $("#action_users").click(function(){
        var curEle = $(this);
        var oldSubVal = curEle.val();
        $(this).attr("value", $('#loadingTxt').val());
        $(this).attr("disabled","disabled");
        $.ajax({
            type: "POST",
            url: "/admin/join_plan/do_action",
            data: $('#do_action').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#action_users_msg').text(res.msg).addClass('text-success');
                    $('#user_id,#confirm_user_id').val('');
                    location.reload();
                } else {
                    $('#action_users_msg').text(res.msg).addClass('text-error');
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                }
            }
        });
    });

    $(".return_back").click(function(){
        $.ajax({
            type: "POST",
            url: "/admin/return_back/do_return_back",
            data: $('.do_return_back').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#return_back_msg').text(res.msg).addClass('text-success');
                    $('#user_id,#confirm_user_id').val('');
                    //location.reload();
                } else {
                    $('#return_back_msg').text(res.msg).addClass('text-error');
                }
            }
        });
    });

    $("#login_submit").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/admin/sign_in/submit",
            data: $('#login_form').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    location.href = res.data.jumpUrl;
                } else {
                    $('#login_msg').text(res.msg);
                    curEle.attr("disabled", false);
                }
                curEle.html(oldSubVal);
            }
        });
    });

    $(".mem_name").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        if(!curVal){
            curVal = '';
        }
        var mem_id = $(this).parent().prev().text();
        $(this).html('<input type="text" attr-uid="'+mem_id+'" class="mem_name_input" value="'+curVal+'">');
        $(this).children().first().focus();
    });

    $(".modify_deadline_day").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        if(!curVal){
            curVal = '';
        }
        var itemId = $(this).prev().val();
        $(this).html('<input type="text" attr-itemId="'+itemId+'" class="modify_deadline_day_input" value="'+curVal+'">');
        $(this).children().first().focus();
    });

    $(".mem_email").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        if(!curVal){
            curVal = '';
        }
        var mem_id = $(this).parent().prev().prev().text();
        $(this).html('<input type="text" attr-uid="'+mem_id+'" attr-value="'+curVal+'" class="mem_email_input" value="'+curVal+'">');
        $(this).children().first().focus();
    });

    $("#info_id_card_num").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        if(!curVal){
            curVal = '';
        }
        var mem_id = $('#info_id').text();
        $(this).html('<input type="text" attr-uid="'+mem_id+'" class="info_id_card_num_input" value="'+curVal+'">');
        $(this).children().first().focus();
    });

    $("#info_address").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        if(!curVal){
            curVal = '';
        }
        var mem_id = $('#info_id').text();
        $(this).html('<textarea rows="2" cols="20" attr-uid="'+mem_id+'" class="info_address_input">'+curVal+'</textarea>');
        $(this).children().first().focus();
    });
    $(".info_address_input").live('blur',function(){
        var curEle = $(this);
        var uid = curEle.attr('attr-uid');
        var modifyVal = curEle.val();
        $.ajax({
            type: "POST",
            url: "/admin/user_list/modify_mem_info",
            data: {fieldName:'address',uid:uid,modifyVal:modifyVal},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    //curEle.parent().html('<textarea rows="2" cols="20" attr-uid="'+uid+'" class="info_address_input">'+modifyVal+'</textarea>');
                    curEle.parent().html(modifyVal);
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });
    $(".info_id_card_num_input").live('blur',function(){
        var curEle = $(this);
        var uid = curEle.attr('attr-uid');
        var modifyVal = curEle.val();
        $.ajax({
            type: "POST",
            url: "/admin/user_list/modify_mem_info",
            data: {fieldName:'id_card_num',uid:uid,modifyVal:modifyVal},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    curEle.parent().text(modifyVal);
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });

    $(".mem_mobile").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        var oldMobileNumber = $(this).text();
        if(!curVal){
            curVal = '';
        }
        var mem_id = $(this).parent().prev().prev().prev().text();
        $(this).html('<input type="text" attr-uid="'+mem_id+'"  oldMobile="'+oldMobileNumber+'" class="info_mobile_input" value="'+curVal+'">');
        $(this).children().first().focus();
    });

    $(".info_mobile_input").live('blur',function(){
        var curEle = $(this);
        var uid = curEle.attr('attr-uid');       
        var oldMobileNumber = curEle.attr('oldMobile');
        var modifyVal = curEle.val();
        var confirm_email_title = $("#confirm_email_title").val();
        var confirm_mobile_title = $("#confirm_mobile_title").val();
        var confirm_content = $("#confirm_info_content").val();
        var confirm_ok = $("#label_yes").val();
        var confirm_cancel = $("#label_no").val();
        
        layer.confirm(confirm_content, {
            icon: 3,
            title: confirm_mobile_title,
            closeBtn: 2,
            btn: [confirm_ok, confirm_cancel]
        }, function(index){        
        	layer.close(index);        	
	        $.ajax({
	            type: "POST",
	            url: "/admin/user_list/modify_mem_info",
	            data: {fieldName:'mobile',uid:uid,modifyVal:modifyVal,check:1},
	            dataType: "json",
	            success: function (res) {
	                if (res.success) {
	                    curEle.parent().text(modifyVal);
	                }else{
	                    curEle.parent().text(oldMobileNumber);
	                    layer.msg(res.msg);
	                }
	            }
	        });
        }
        ,function()
        {
        	curEle.parent().text(oldMobileNumber);
        }
        );
    });

    $(".mem_name_input").live('blur',function(){
        var curEle = $(this);
        var uid = curEle.attr('attr-uid');
        var modifyVal = curEle.val();
        $.ajax({
            type: "POST",
            url: "/admin/user_list/modify_mem_info",
            data: {fieldName:'name',uid:uid,modifyVal:modifyVal},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    curEle.parent().text(modifyVal);
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });

    $(".modify_deadline_day_input").live('blur',function(){
        var curEle = $(this);
        var itemId = curEle.attr('attr-itemId');
        var modifyVal = curEle.val();
        $.ajax({
            type: "POST",
            url: "/admin/order_repair_of_comm/modify_deadline_day",
            data: {itemId:itemId,modifyVal:modifyVal},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    curEle.parent().text(modifyVal);
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });

    $(".mem_email_input").live('blur',function(){
        var curEle = $(this);
        var uid = curEle.attr('attr-uid');
        var source_value = curEle.attr('attr-value');
        var modifyVal = curEle.val();
        var modifyVal_source = curEle.val();
        var confirm_email_title = $("#confirm_email_title").val();
        var confirm_mobile_title = $("#confirm_mobile_title").val();
        var confirm_content = $("#confirm_info_content").val();
        var confirm_ok = $("#label_yes").val();
        var confirm_cancel = $("#label_no").val();
        
        layer.confirm(confirm_content, {
            icon: 3,
            title: confirm_email_title,
            closeBtn: 2,
            btn: [confirm_ok, confirm_cancel]
        }, function(index){        
        	layer.close(index);
        	$.ajax({
                type: "POST",
                url: "/admin/user_list/modify_mem_info",
                data: {fieldName:'email',uid:uid,modifyVal:modifyVal,check:1},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        curEle.parent().text(modifyVal);
                    }else{
                        layer.msg(res.msg);
                    }
                }
            });
        }
        ,function()
        {
        	curEle.parent().text(source_value);
        }
        );        
    });

    $("#upgrade_user_manually").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.val();
        curEle.val($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/admin/upgrade_user_manually/submit",
            data: $('#upgrade_user_manually_form').serialize(),
            dataType: "json",
            success: function (res) {
                $('#upgrade_user_manually_msg').text(res.msg);
                if(res.success){
                    $('#upgrade_user_manually_msg').attr("class",'success_msg');
                    curEle.val(oldSubVal);
                    setTimeout(function () {
                        $('#upgrade_user_manually_msg').text('');
                        curEle.attr("disabled", false);
                    }, 3000);
                }else{
                    $('#upgrade_user_manually_msg').attr("class", 'error_msg');
                    curEle.attr("disabled", false);
                    curEle.val(oldSubVal);
                }
            }
        });
    });

    $("#reset_member_account_info").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.val();
        curEle.val($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/admin/clear_member_account_info/submit",
            data: $('#clear_member_account_info_form').serialize(),
            dataType: "json",
            success: function (res) {
                $('#reset_member_account_info_msg').text(res.msg);
                curEle.val(oldSubVal);
                curEle.attr("disabled", false);
                if(res.success){
                    $('#reset_member_account_info_msg').attr("class",'success_msg');
                }else{
                    $('#reset_member_account_info_msg').attr("class", 'error_msg');
                }
            }
        });
    });

    $("#commChangeSub").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.val();
        curEle.val($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/admin/commission_admin/commChangeSub",
            data: $('#commChangeForm').serialize(),
            dataType: "json",
            success: function (res) {
                $('#commChangeMsg').text(res.msg);
                curEle.val(oldSubVal);
                if(res.success){
                    $('#commChangeMsg').attr("class",'success_msg');
                    setTimeout(function () {
                        $('#commChangeMsg').text('');
                        $('#commChangeForm')[0].reset();
                        curEle.attr("disabled", false);
                        window.location.reload(true);
                    }, 3000);
                }else{
                    $('#commChangeMsg').attr("class", 'error_msg');
                    curEle.attr("disabled", false);
                    window.location.reload(true);
                }
            }
        });
    });

    $("#monthFeePoolChangeSub").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.val();
        curEle.val($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/admin/monthfee_pool_admin/monthFeePoolChangeSub",
            data: $('#monthFeePoolChangeForm').serialize(),
            dataType: "json",
            success: function (res) {
                $('#monthFeePoolChangeMsg').text(res.msg);
                curEle.val(oldSubVal);
                if(res.success){
                    $('#monthFeePoolChangeMsg').attr("class",'success_msg');
                    setTimeout(function () {
                        $('#monthFeePoolChangeMsg').text('');
                        $('#monthFeePoolChangeForm')[0].reset();
                        curEle.attr("disabled", false);
                    }, 3000);
                }else{
                    $('#monthFeePoolChangeMsg').attr("class", 'error_msg');
                    curEle.attr("disabled", false);
                }
            }
        });
    });

    $("body").keydown(function(e) {
        var a = e||window.event
        if (a.keyCode == '13') {//keyCode=13是回车键
            $("#login_submit").click();
        }
    });

	//多语言标签切换 add by denny yuan
	$('.btn_tab').click(function() {
		var $t=$(this),lang_id=$t.attr('data-id');

		$t.addClass('btn-primary').siblings('.btn_tab').removeClass('btn-primary');

		$('.tab_content').find('.lang_'+lang_id).show().siblings().hide();

	});
});

/**
 * 查看用戶详情
 */
function see_user_info_detail(id){
    $.ajax({
        type: "POST",
        url: "/admin/user_list/get_user_info_detail",
        data: {id:id},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $.each(res.data, function(key, item){
                     $('#info_'+key).html(item);
                 });
                $('#popUserInfo').modal();
            }
        }
    });
}

function changeAdminAccountStatus(id,status){
    $.ajax({
        type: "POST",
        url: "/admin/admin_account_list/changeAdminAccountStatus",
        data: {id:id,status:status},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}

function deleteAdminAccount(id,data){

    layer.confirm(data[0], {
        icon: 3,
        title: data[0],
        closeBtn: 2,
        btn: [data[1], data[2]]
    },function(index){
        layer.close(index);
        $.ajax({
            type: "POST",
            url: "/admin/admin_account_list/deleteAdminAccount",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    location.reload();
                }
            }
        });
    });
}

function resetAdminAccountPw(id,data){
 
    layer.confirm(data[0], {
        icon: 3,
        title: data[0],
        closeBtn: 2,
        btn: [data[1], data[2]]
    }, function(index){
        layer.close(index);
        $.ajax({
            type: "POST",
            url: "/admin/admin_account_list/resetAdminAccountPw",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                   layer.alert(res.msg,{title:res.title,btn:res.button})
                    //location.reload();
                }
            }
        });
    });
}

function enable_user_level(id){
    $.ajax({
        type: "POST",
        url: "/admin/user_list/enable_user_level",
        data: {id:id},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}

function enable_user_account(id){
    $.ajax({
        type: "POST",
        url: "/admin/user_list/enable_user_account",
        data: {id:id},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}

function disable_user_account(id){
    $.ajax({
        type: "POST",
        url: "/admin/user_list/disable_user_account",
        data: {id:id},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}

function reenable_user_account(id){
    $.ajax({
        type: "POST",
        url: "/admin/user_list/reenable_user_account",
        data: {id:id},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}

function upgrade_store_level_by_month_fee(id){
    $.ajax({
        type: "POST",
        url: "/admin/user_list/upgrade_store_level_by_month_fee",
        data: {id:id},
        dataType: "json",
        success: function (res) {
            if (res.success) {
                location.reload();
            }
        }
    });
}

/*--获取网页传递的参数--*/
function request(paras)
{
    var url = location.href;
    var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
    var paraObj = {}
    for (i=0; j=paraString[i]; i++){
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if(typeof(returnValue)=="undefined"){
        return "";
    }else{
        return returnValue;
    }
}







