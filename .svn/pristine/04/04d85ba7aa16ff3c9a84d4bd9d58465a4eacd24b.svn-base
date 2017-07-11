/**
 * Created by john on 15-8-17.支付页面
 */
$(function(){

    $('.go_pay_all').click(function(){
        var curEle = $(this);
        var payment = $("[name='payment_method']").filter(":checked").val();
        if(!payment){
            layer.msg($('#pls_sel_payment').val());
            return;
        }
        if(payment == 110){
            if(!$('input[name="pay_pwd"]').val()){
                layer.msg($('#enter_funds_pwd').val());
                return;
            }
        }
        var oldSubVal = curEle.val();
        curEle.attr("value", $('#loadingTxt').val());
        curEle.attr("disabled","disabled");
        var oldColor = '#004ea2';
        curEle.css('background','#cccccc');

        $.ajax({
            type: "POST",
            url: "/order/check_pay_info",
            data: $('#mall_form_submit').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
//                    if(payment == '104'){
//
//                        var order_id = $('[name="order_id"]').val()
//                        layer.open({
//                            type: 2,
//                            scrollbar: false,
//                            shift: 1, //0-6的动画形式，-1不开启
//                            shadeClose: true,
//                            shade: 0.8,
//                            area: ['34%', '500px'],
//                            title:false,
//                            content: "/respond/go_wx_order_pay/" + order_id + '/104'
//                    });
//
//                    curEle.attr('value',oldSubVal);
//                    curEle.attr("disabled",false);
//                    curEle.css('background',oldColor);
//
//                    /** 定時器請求支付是否成功，跳轉頁面 */
//                    setInterval(function(){
//                         $.ajax({
//                         type: "POST",
//                         url: "/order/get_orders_notify",
//                         data:{order_id:order_id},
//                         dataType: "json",
//                             success: function (res) {
//                                 if (res.success) {
//                                     location.href='/respond/wx_do_return/' + order_id;
//                                 }
//                             }
//                         })
//                     },3000);
//
//                    }else{
                        $('#mall_form_submit').submit();
//                    }
                } else {
                    layer.msg(res.msg);
                    curEle.attr('value',oldSubVal);
                    curEle.attr("disabled",false);
                    curEle.css('background',oldColor);
                }

            }
        });
    });
    $(".dingd .c-b").bind("click",function(){
        $('.p-dizhi').toggle();
    });
    choosePayment();
    $(".margin20 [name='payment_method']:radio").click(choosePayment);

    $(".pay_pwd").keydown(function(e) {
        var a = e||window.event
        if (a.keyCode == '13') {//keyCode=13是回车键
            $(".go_pay_all").click();
        }
    });
})
function choosePayment(){
    var payment = $(".margin20 [name='payment_method']").filter(":checked").val();
    if(payment == '110'){
        $('#mall_form_submit').attr('action','/order/go_amount_pay');
        $('.pay_p').show();
    }else{
        $('.pay_p').hide();
        $('.pay_pwd').val('');
        $('#mall_form_submit').attr('action','/respond/go_order_pay');
    }
}
