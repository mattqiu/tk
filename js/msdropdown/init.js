$(document).ready(function () {
    $("#countries").msDropdown();
    $('.choose_lan').click(function () {
        var lan_id = $(this).attr("attr_id");
        var lan_name = $(this).attr("data-title");
        /*switch(lan_id)
         {
         case '2':
         icon = '¥';
         currency = 'CNY';
         currency_name = '人民币';
         break;
         case '3':
         icon = 'HK$';
         currency = 'HKD';
         currency_name = '港币';
         break;
         default:
         icon = '$';
         currency = 'USD';
         currency_name = '美元';
         break;
         }*/
        $.ajax({
            type: "POST",
            url: "/admin/sign_in/changeAdminLanguage",
            data: {lan: $(this).attr('attr_value'),lan_id:lan_id,lan_name:lan_name/*,icon:icon,currency:currency,currency_name:currency_name*/},
            dataType: "json",
            success: function (res) {
                if(res.success){
                    location.reload();
                }
            }
        });
    });

    //切换区域
    $('.change_location').click(function(){
        var $t=$(this),
            location_id = $t.attr('data-id'),
        //currency_id = $t.attr('data-cur'),
        //default_lang=$t.attr('data-lang'),
            goods_sn=$t.attr('data-goods-sn'),
            jump=$t.attr('data-jump'),
            goods_sn_main=$t.attr('data-goods-sn-main');

        $.ajax({
            type: "POST",
            url: "/common/changeLanguage",

            data: {location_id:location_id,jump:jump,goods_sn:goods_sn,goods_sn_main:goods_sn_main},
            dataType: "json",
            success: function (res) {
                if(res.success){

                    if(res.is_exist){
                        location.reload();
                    }else{
                        location.href = "/";
                    }
                }
            }
        });
    });
});