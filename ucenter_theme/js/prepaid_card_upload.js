$(function () {


    var showimg = $('#showimg');
    var showimg2 = $('#showimg2');
    var showimg3 = $('#showimg3');

    $("#fileupload").wrap("<form id='myupload' action='/ucenter/prepaid_card/upScan' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload2").wrap("<form id='myupload2' action='/ucenter/prepaid_card/upScan2' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload3").wrap("<form id='myupload3' action='/ucenter/prepaid_card/upScan3' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function () { //选择文件
        $("#myupload").ajaxSubmit({
            dataType: 'json', //数据格式为json 
            beforeSend: function () { //开始上传 
               // $('.ID_front').addClass('hidden');
            },
            uploadProgress: function (event, position, total, percentComplete) {

            },
            success: function (data) { //成功
                if(!data.hasOwnProperty('name')){
                    layer.msg(data);
                    return ;
                }
                $('.ID_front').addClass('hidden');
                //获得后台返回的json数据，显示文件名，大小，以及删除按钮
                //files.html("<a href='##' id='delimg' onclick='delimg()' rel='" + data.pic + "'>delete</a>");
                //$('#id_card_scan').val(data.pic);
                //显示上传后的图片
                var img = data.picUrl;
                showimg.children('a').attr('href',img);
                showimg.children('a').children('img')[0].src=img;
                showimg.children('a').next('a').attr('rel',data.pic);
                showimg.children('a').next('a').attr('href','##');
                $('#ID_front_path').val(data.pic);
                showimg.removeClass('hidden');
            },
            error: function (xhr) { //上传失败 
                if(xhr.responseText){
                    $('.ID_front').removeClass('hidden');
                }
            }
        });
    });

    $("#fileupload2").change(function () { //选择文件
        $("#myupload2").ajaxSubmit({
            dataType: 'json', //数据格式为json
            beforeSend: function () { //开始上传
               // $('.ID_reverse').addClass('hidden');

            },
            uploadProgress: function (event, position, total, percentComplete) {

            },
            success: function (data) { //成功
                if(!data.hasOwnProperty('name')){
                    layer.msg(data);
                    return ;
                }
                $('.ID_reverse').addClass('hidden');
                //获得后台返回的json数据，显示文件名，大小，以及删除按钮
                //files.html("<a href='##' id='delimg' onclick='delimg()' rel='" + data.pic + "'>delete</a>");
                //$('#id_card_scan').val(data.pic);
                //显示上传后的图片
                var img = data.picUrl;
                showimg2.children('a').attr('href',img);
                showimg2.children('a').children('img')[0].src=img;
                showimg2.children('a').next('a').attr('rel',data.pic);
                showimg2.children('a').next('a').attr('href','##');
                $('#ID_reverse_path').val(data.pic);
                showimg2.removeClass('hidden');
            },
            error: function (xhr) { //上传失败
                if(xhr.responseText){
                  //  progress.hide(); //隱藏进度条
                   // btn.html("上传失败");
                    $('.ID_reverse').removeClass('hidden');
                }
            }
        });
    });

    $("#fileupload3").change(function () { //选择文件
        $("#myupload3").ajaxSubmit({
            dataType: 'json', //数据格式为json
            beforeSend: function () { //开始上传
              //  $('.address_prove').addClass('hidden');

            },
            uploadProgress: function (event, position, total, percentComplete) {

            },
            success: function (data) { //成功
                if(!data.hasOwnProperty('name')){
                    layer.msg(data);
                    return ;
                }
                $('.address_prove').addClass('hidden');
                //获得后台返回的json数据，显示文件名，大小，以及删除按钮
                //files.html("<a href='##' id='delimg' onclick='delimg()' rel='" + data.pic + "'>delete</a>");
                //$('#id_card_scan').val(data.pic);
                //显示上传后的图片
                var img = data.picUrl;
                showimg3.children('a').attr('href',img);
                showimg3.children('a').children('img')[0].src=img;
                showimg3.children('a').next('a').attr('rel',data.pic);
                showimg3.children('a').next('a').attr('href','##');
                $('#address_prove_path').val(data.pic);
                showimg3.removeClass('hidden');
            },
            error: function (xhr) { //上传失败
                if(xhr.responseText){
                  //  progress.hide(); //隱藏进度条
                   // btn.html("上传失败");
                    $('.address_prove').removeClass('hidden');
                }
            }
        });
    });
});
function delimg() {
    var pic = $('#delimg').attr("rel");
    $.post("/ucenter/prepaid_card/delImg?act=delimg&back=0", {imagename: pic}, function (msg) {
        if (msg == 1) {
            $('.ID_front').removeClass('hidden');
            $('#showimg').addClass('hidden');
            $('#ID_front_path').val('');
        }
    });
}
function delimg2() {
    var pic = $('#delimg2').attr("rel");
    $.post("/ucenter/prepaid_card/delImg?act=delimg&back=1", {imagename: pic}, function (msg) {
        if (msg == 1) {
            $('.ID_reverse').removeClass('hidden');
            $('#showimg2').addClass('hidden');
            $('#ID_reverse_path').val('');
        }
    });
}
function delimg3() {
    var pic = $('#delimg3').attr("rel");
    $.post("/ucenter/prepaid_card/delImg?act=delimg&back=2", {imagename: pic}, function (msg) {
        if (msg == 1) {
            $('.address_prove').removeClass('hidden');
            $('#showimg3').addClass('hidden');
            $('#address_prove_path').val('');
        }
    });
}