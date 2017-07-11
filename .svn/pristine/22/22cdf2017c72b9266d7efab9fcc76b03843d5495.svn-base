$(document).ready(function () {
    $("input[name='submit']").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.val();
        $(this).attr("value", $('#loadingTxt').val());
        $(this).attr("disabled","disabled");
        var redirect = request('redirect');
        var url = redirect ? redirect :'ucenter';
        $.ajax({
            type: "POST",
            url: "/login/submit",
            data: $('#login_form').serialize(),
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    $('.msg').text('');
                    window.location.href = '/'+ url;
                } else {
                    $('.msg').text(data.msg);
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                }
            }
        });
    });

});

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