//js本地图片预览，兼容ie[6-9]、火狐、Chrome17+、Opera11+、Maxthon3
function PreviewImage(fileObj,imgPreviewId,divPreviewId) {
    var allowExtention=".jpg,.bmp,.gif,.png,.jpeg";//允许上传文件的后缀名document.getElementById("hfAllowPicSuffix").value;
    var extention=fileObj.value.substring(fileObj.value.lastIndexOf(".")+1).toLowerCase();            
    var browserVersion= window.navigator.userAgent.toUpperCase();
    if(allowExtention.indexOf(extention)>-1){ 
        if(fileObj.files){//HTML5实现预览，兼容chrome、火狐7+等
            if(window.FileReader){
                var reader = new FileReader(); 
                reader.onload = function(e){
                    document.getElementById(imgPreviewId).setAttribute("src",e.target.result);
                }  
                reader.readAsDataURL(fileObj.files[0]);
            }else if(browserVersion.indexOf("SAFARI")>-1){
                alert("不支持Safari6.0以下浏览器的图片预览!");
            }
        }else if (browserVersion.indexOf("MSIE")>-1){
            if(browserVersion.indexOf("MSIE 6")>-1){//ie6
                document.getElementById(imgPreviewId).setAttribute("src",fileObj.value);
            }else{  //ie[7-9]
                fileObj.select();
                if(browserVersion.indexOf("MSIE 9")>-1)
                    fileObj.blur();//不加上document.selection.createRange().text在ie9会拒绝访问
                var newPreview =document.getElementById(divPreviewId+"New");
                if(newPreview==null){
                    newPreview =document.createElement("div");
                    newPreview.setAttribute("id",divPreviewId+"New");
                    newPreview.style.width = document.getElementById(imgPreviewId).width+"px";
                    newPreview.style.height = document.getElementById(imgPreviewId).height+"px";
                    newPreview.style.border="solid 1px #d2e2e2";
                }
                 newPreview.style.filter ="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale',src='" + document.selection.createRange().text + "')";                            
                var tempDivPreview=document.getElementById(divPreviewId);
                tempDivPreview.parentNode.insertBefore(newPreview,tempDivPreview);
                tempDivPreview.style.display="none";                    
            }
        }else if(browserVersion.indexOf("FIREFOX")>-1){//firefox
            var firefoxVersion= parseFloat(browserVersion.toLowerCase().match(/firefox\/([\d.]+)/)[1]);
            if(firefoxVersion<7){//firefox7以下版本
                document.getElementById(imgPreviewId).setAttribute("src",fileObj.files[0].getAsDataURL());
            }else{//firefox7.0+                    
                document.getElementById(imgPreviewId).setAttribute("src",window.URL.createObjectURL(fileObj.files[0]));
            }
        }else{
            document.getElementById(imgPreviewId).setAttribute("src",fileObj.value);
        }         
    }else{
        layer.msg("仅支持"+allowExtention+"为后缀名的文件!");
        fileObj.value="";//清空选中文件
        if(browserVersion.indexOf("MSIE")>-1){                        
            fileObj.select();
            document.selection.clear();
        }                
        fileObj.outerHTML=fileObj.outerHTML;
        return;
    }
    
    $(fileObj).parent().hide();
    var obj = "#"+divPreviewId;
    $(obj).parent().parent().show();
    
}

//转换图片为Base64编码  outputFormat 输出格式
function convertImgToBase64(url, callback, outputFormat,imgSize){ 
        var canvas = document.createElement('CANVAS'); 
        var ctx = canvas.getContext('2d'); 
        var img = new Image; 
        img.crossOrigin = 'Anonymous'; 
        img.onload = function(){
          var width = img.width;
          var height = img.height;
          var compressRate = 1;   // 图片压缩比率设置   初始值1
          if(imgSize<=307200) {   //小于300KB不压缩
              var rate = 1;    //真实压缩比
          } else {
              compressRate = 3;   
              var rate = (width<height ? width/height : height/width)/compressRate;
          }
          
          canvas.width = width*rate; 
          canvas.height = height*rate; 
          ctx.drawImage(img,0,0,width,height,0,0,width*rate,height*rate); 
          var dataURL = canvas.toDataURL(outputFormat || 'image/jpeg'); 
          callback.call(this, dataURL); 
          canvas = null; 
        };
        img.src = url; 
      }

       function getObjectURL(file) {
            var url = null ; 
            if (window.createObjectURL!=undefined) {  // basic
              url = window.createObjectURL(file) ;
            } else if (window.URL!=undefined) {       // mozilla(firefox)
              url = window.URL.createObjectURL(file) ;
            } else if (window.webkitURL!=undefined) { // web_kit or chrome
              url = window.webkitURL.createObjectURL(file) ;
            }
            return url ;
      }

$(function () {
    var bar = $('.bar');
    var percent = $('.percent');
    var showimg = $('#showimg');
    var showimg2 = $('#showimg2');
    var progress = $(".progress");
    var files = $(".files");
    var files2 = $(".files2");
    var btn = $(".btn span");
    $('#scan_card').wrap("<form id='card_upload' action='/ucenter/account_info/aliYunVerifyCard' method='post' enctype='multipart/form-data'></form>");
    //$('#idcard_upload').wrap("<form id='idcard_form' action='/ucenter/account_info/verifyCard' method='post' enctype='multipart/form-data'></form>");
    //$("#fileupload").wrap("<form id='myupload' action='/ucenter/account_info/upScan' method='post' enctype='multipart/form-data'></form>");
    //$("#fileupload2").wrap("<form id='myupload2' action='/ucenter/account_info/upScan2' method='post' enctype='multipart/form-data'></form>");
    
   var allowExtention=".jpg,.bmp,.gif,.png,.jpeg"; //允许上传文件的后缀名
   var lang = $.cookie('curLan');   //获取语言类型
   

    $("#fileupload").change(function (event) { //选择正面文件
         var imgSize = $(this)[0].files[0].size; 
         var fileName = $(this)[0].files[0].name;
         var extention = fileName.substring(fileName.lastIndexOf(".")+1).toLowerCase();
         var msg ="";
         if(allowExtention.indexOf(extention)<0) {
            if(lang == 'english') {
                msg = 'Only supports .jpg,.bmp,.gif,.png,.jpeg suffix file';
            } else if(lang == 'zh') {
                msg = '仅支持.jpg,.bmp,.gif,.png,.jpeg 为后缀名的文件!';
            } else if(lang == 'hk') {
                msg = '僅支持.jpg,.bmp,.gif,.png,.jpeg 為後綴名的文件';
            } else if(lang == 'kr') {
                msg = '겨우 지원 .jpg,.bmp,.gif,.png,.jpeg 위해 확장자 이름 파일';
            } else {
                msg = 'Only supports .jpg,.bmp,.gif,.png,.jpeg suffix file';
            }
            layer.msg(msg);
             return;
         } 
         var imageUrl = getObjectURL($(this)[0].files[0]);
            convertImgToBase64(imageUrl, function(base64Img){
              // base64Img为转好的base64,放在img src直接前台展示(<img src="data:image/jpg;base64,/9j/4QMZRXh...." />)
              //(base64Img);
              // base64转图片不需要base64的前缀data:image/jpg;base64
              //alert(base64Img.split(",")[1]);
              $('#upload_btn').hide();
              $('#showimg').show();
              $('#img_face').attr("src",base64Img).show();
              $('#delPreviewImg').show();
            
            },"",imgSize);
        event.preventDefault(); 
        
        
    });

    $("#fileupload2").change(function () { //选择反面文件
       
       var imgSize = $(this)[0].files[0].size; 
       var fileName = $(this)[0].files[0].name;
       var extention = fileName.substring(fileName.lastIndexOf(".")+1).toLowerCase();
       var msg ="";
         if(allowExtention.indexOf(extention)<0) {
            if(lang == 'english') {
                msg = 'Only supports .jpg,.bmp,.gif,.png,.jpeg suffix file';
            } else if(lang == 'zh') {
                msg = '仅支持.jpg,.bmp,.gif,.png,.jpeg 为后缀名的文件!';
            } else if(lang == 'hk') {
                msg = '僅支持.jpg,.bmp,.gif,.png,.jpeg 為後綴名的文件';
            } else if(lang == 'kr') {
                msg = '겨우 지원 .jpg,.bmp,.gif,.png,.jpeg 위해 확장자 이름 파일';
            } else {
                msg = 'Only supports .jpg,.bmp,.gif,.png,.jpeg suffix file';
            }
            layer.msg(msg);
             return;
         } 
       var imageUrl = getObjectURL($(this)[0].files[0]);
            convertImgToBase64(imageUrl, function(base64Img){
             
              $('#upload_btn2').hide();
              $('#showimg2').show();
              $('#img_back').attr("src",base64Img).show();
              $('#delPreviewImg2').show();
            
            },"",imgSize);
        event.preventDefault(); 
    });
});
   //中国区删除本地预览
    function delScanImg(obj) {
       $(obj).parent().hide();
       $('#upload_btn').show();
       $('#scan_face').attr('src',"");
       //$('#file_face').empty();
       
    }
    //中国区删除本地预览
    function delScanImg2(obj) {
       $(obj).parent().hide();
       $('#upload_btn2').show();
       $('#scan_back').attr('src',"");
    }
    
    //非中国区删除本地预览证件正面
    function delPreviewImg(){
        $('#img_face').attr("src","");
        $('#showimg').hide();
        $('#upload_btn').show();
    }
    
     //非中国区删除本地预览证件反面
    function delPreviewImg2(){
         $('#img_back').attr("src","");
         $('#showimg2').hide();
         $('#upload_btn2').show();
    }
    
function delimg() {
    var pic = $('#delimg').attr("rel");
    $.post("/ucenter/account_info/delImg?act=delimg&back=0", {imagename: pic}, function (msg) {

        if (msg.success == 1) {
            $('#upload_btn').show();
            $(".files").html("");
            $('#id_card_scan').val('');
            $('#showimg').empty(); //清空图片
        }
        location.reload();
    },'json');
}
function delimg2() {
    var pic = $('#delimg2').attr("rel");
    $.post("/ucenter/account_info/delImg?act=delimg&back=1", {imagename: pic}, function (msg) {

        if (msg.success == 1) {
            $('#upload_btn2').show();
            $(".files2").html("");
            $('#id_card_scan_back').val('');
            $('#showimg2').empty(); //清空图片
        }
        location.reload();
    },'json');
}