$(function () {
    var showimg = $('#showimg');
    var showimg2 = $('#showimg2');
    var showimg3 = $('#showimg3');

    $("#fileupload").wrap("<form id='myupload' action='/order/upScan' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload2").wrap("<form id='myupload2' action='/order/upScan2' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload3").wrap("<form id='myupload3' action='/order/upScan3' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function () { //选择文件
        var addr_id = $('.address-list li.selected').data('id');//获取当前的收货人名字
        $("#myupload").ajaxSubmit({
            dataType: 'json', //数据格式为json
            data:{
                addr_id:addr_id
            },
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
                $("#id_img_a").val(data.pic);
                $("#img_zm").html("<img style='margin-left: 0px;' id='showimg' src='"+data.picUrl+"'>");
                var img_zm = $('#img_zm');
                img_zm.append("<a href='##' id='delimg' onclick='delimg()' rel='" + data.pic + "'>"+data.delete+"</a>");
                //当前收货人的正面信息图片路径
                $('.address-list li.selected').attr('data-img1',data.pic);

            },
            error: function (xhr) { //上传失败
                if(xhr.responseText){
                    $('.ID_front').removeClass('hidden');
                }
            }
        });
    });

    $("#fileupload2").change(function () { //选择文件
        var addr_id = $('.address-list li.selected').data('id');//获取当前的收货人名字
        $("#myupload2").ajaxSubmit({
            dataType: 'json', //数据格式为json
            data:{
                addr_id:addr_id
            },
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

                $("#id_img_b").val(data.pic);
                $("#img_bm").html("<img style='margin-left: 0px;' id='showimg2' src='"+data.picUrl+"'>");
                var img_bm = $('#img_bm');
                img_bm.append("<a href='##' id='delimg2' onclick='delimg2()' rel='" + data.pic + "'>"+data.delete+"</a>");
                //当前收货人的正面信息图片路径
                $('.address-list li.selected').attr('data-img2',data.pic);

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
});
function delimg() {
    //清空目前收货人的证件图片信息
    $('.address-list li.selected').attr('data-img1','');
    //获取当前的收货人名字
    var consignee = $('.address-list li.selected').data('addr');
    var pic = $('#delimg').attr("rel");
    $.post("/order/delImg?act=delimg&back=0",{imagename: pic,consignee:consignee}, function (msg) {
        if (msg.msg == 1) {
            $("#id_img_a").val('');
            $("#img_zm").html("<b class='d-b fw-n'>"+msg.zm+"</b>");
            var delimg = $('#delimg');
            delimg.css('display','none');
            //window.location.reload();
        }
    },"json");
}
function delimg2() {
    //清空目前收货人的证件图片信息
    $('.address-list li.selected').attr('data-img2','');
    //获取当前的收货人名字
    var consignee = $('.address-list li.selected').data('addr');
    var pic = $('#delimg2').attr("rel");
    $.post("/order/delImg?act=delimg&back=1", {imagename: pic,consignee:consignee}, function (msg) {
        if (msg.msg == 1) {
            $("#id_img_b").val('');
            $("#img_bm").html("<b class='d-b fw-n'>"+msg.bm+"</b>");
            var delimg2 = $('#delimg2');
            delimg2.css('display','none');
        }
    },"json");
}
