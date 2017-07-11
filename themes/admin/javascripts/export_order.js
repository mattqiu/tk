var storage_key_file_list = "export_order_file_list";
//状态刷新重试次数
var status_over_times = 12;
//状态刷新时间间隔
var status_refresh_time = 2000;
//最大导出任务
var max_export_task = 15;

var local_storage_get = function()
{
    var file_list = window.localStorage.getItem(storage_key_file_list);
    // console.dir(file_list);
    return file_list;
};

var local_sstorage_set = function(o)
{
    window.localStorage.setItem(storage_key_file_list,o);
};

var record_info_localstorage = function(filename,pos,str)
{
    window.localStorage.setItem(filename+pos,str);
}

var get_info_localstorage = function(filename,pos,str)
{
    return window.localStorage.getItem(filename+pos);
}

var set_info = function(filename,pos,str)
{
    $('#'+filename+"_"+pos).html(str);
};

var local_storage_add = function(filename)
{
    var file_list = local_storage_get();
    if(file_list == null)
    {
        window.localStorage.setItem(storage_key_file_list,filename);
    }else{
        var file_lists = file_list.split(',');
        var exists = false;
        for(var i in file_lists)
        {
            if(file_lists[i] == filename)
            {
                exists = true;
                break;
            }
        }
        if(!exists)
        {
            if(file_list != "")
            {
                file_list = file_list + "," + filename;
            }else{
                file_list = filename;
            }
            window.localStorage.setItem(storage_key_file_list,file_list);
        }
    }
};

var local_storage_del = function(filename)
{
    var file_list = local_storage_get();
    var file_lists = file_list.split(',');
    var new_list = "";
    for(var i in file_lists){
        if(filename == file_lists[i])
        {
            continue;
        }
        if(new_list == "")
        {
            new_list += file_lists[i];
        }
        else
        {
            new_list += ","+file_lists[i];
        }
    }
    // console.dir(new_list);
    local_sstorage_set(new_list);
};

var init_by_local_storage =function()
{
    var file_list = local_storage_get();
    if(file_list == null || file_list == undefined)
    {
        return;
    }
    var file_lists = file_list.split(',');
    for(var i in file_lists)
    {
        if(i != "" && i != undefined){
            process_bar_plus(file_lists[i]);
            var tmp = get_info_localstorage(file_lists[i],"filename");
            if(tmp != undefined)
            {
                // console.dir(file_lists[i]);
                // console.dir(tmp);
                set_info(file_lists[i],"filename",tmp);
            }else{
                process_info(file_lists[i]);
            }
        }
    }
};

var process_list_count = function()
{
    var file_list = local_storage_get();
    if(file_list == null || file_list == undefined)
    {
        return 0;
    }
    var file_lists = file_list.split(',');
    return file_lists.length;
}

var validate_form = function()
{
    if($('#store_code').val() == undefined)
    {
        layer.msg('请选择供应商');
        return false;
    }
    return true;
}

var clean_form = function()
{
    //清空勾选按钮框
    $('input[name=is_export_lock]').attr('checked',false);
    //清理显示的供应商
    $('select[name=store_code]').val('');
    $('.ms-choice span').html('');
    //清理供应商列表里的勾选
    $('input[data-name=selectItemstore_code]').each(function(){$(this).attr('checked',false);});
    //清理日期选择
    $('#start_date').val('');
    $('#end_date').val('');
    $('#start_deliver_date').val('');
    $('#end_deliver_date').val('');
    //清理选择框
    $('select').val('');
}

var process_submit = function()
{
    if(!validate_form())
    {
        return;
    }
    if(process_list_count() > max_export_task - 1)
    {
        layer.msg('同时导出任务有点多哇，服务器君会很蓝瘦的，最多同时'+max_export_task+'个,请删除一些再试');
        return;
    }
    var data = $('.form_ajax').serialize();
    $.ajax({
        url: '/admin/export_orders_ajax/process_plus',
        type: "POST",
        data: data,
        dataType: "json",
        success: function(data) {
            if (data.code == 0) {
                if(data.filename != undefined)
                {
                    local_storage_add(data.filename);
                    process_bar_plus(data.filename);
                    if(data.h_filename != undefined)
                    {
                        set_info(data.filename,"filename",data.h_filename);
                        record_info_localstorage(data.filename,"filename",data.h_filename);
                    }
                    process_start(data.filename);
                }
            }else{
                if(data.filename != undefined){
                    local_storage_add(data.filename);
                    process_bar_plus(data.filename);
                    if(data.h_filename != undefined)
                    {
                        set_info(data.filename,"filename",data.h_filename);
                        record_info_localstorage(data.filename,"filename",data.h_filename);
                    }
                }
                layer.msg(data.err_msg);
            }
        },
        error: function(data) {
            console.log(data.responseText);
        }
    });
    clean_form();
};

var download = function(filename)
{
    var url = "/admin/export_orders_ajax/process_download?filename="+filename;
    window.open(url);
};

var retry = function(filename)
{
    auto_refresh_status(filename);
};

var plus_retry_button =function(filename)
{
    $('#'+filename+"_opera .retry_ext").show();
};

var minus_retry_button =function(filename)
{
    $('#'+filename+"_opera .retry_ext").hide();
};

var plus_delete_button =function(filename)
{
    $('#'+filename+"_opera .delete_ext").show();
};

var minus_delete_button =function(filename)
{
    $('#'+filename+"_opera .delete_ext").hide();
};

var plus_download_button =function(filename)
{
    $('#'+filename+"_opera .download_ext").show();
};

var minus_download_button =function(filename)
{
    $('#'+filename+"_opera .download_ext").hide();
};

/**
 * 判断是否继续重试
 * @param filename
 * @returns {boolean}
 */
var if_retry = function(filename)
{
    if(window['over_time_'+filename] == undefined)
    {
        window['over_time_'+filename] = 1;
    }else{
        window['over_time_'+filename] = window['over_time_'+filename] + 1;
    }
    if(window['over_time_'+filename] > status_over_times)
    {
        stop_timer(filename);
        set_info(filename,"status","超时");
        plus_retry_button(filename);
        plus_delete_button(filename);
        return false;
    }
    return true;
};

var process_status = function(filename)
{
    var data = {
        "filename":filename
    };
    $.ajax({
        url: '/admin/export_orders_ajax/process_status',
        type: "POST",
        data: data,
        timeout:status_refresh_time,
        dataType: "json",
        success: function(data) {
            if (data.code == 0) {
                if(data.data.now != undefined && data.data.total != undefined)
                {
                    if(data.data.now == -1 && data.data.total == -1)
                    {
                        if_retry(filename);
                        stop_timer(filename);
                        set_info(filename,"status","找不到指定数据或系统错误，确定指定的供应商有单可导么？");
                        plus_retry_button(filename);
                        plus_delete_button(filename);
                        return;
                    }
                    if(data.data.now == 1 && data.data.total == 0)
                    {
                        if(data.data.page_now != undefined)
                        {
                            set_info(filename,"status","正在读取数据库...,第"+data.data.page_now+"页");
                        }else{
                            set_info(filename,"status","正在读取数据库...");
                        }
                        if(data.data.data_total != undefined && data.data.data_now != undefined)
                        {
                            if(data.data.data_total > 0 && data.data.data_now > 0)
                            {
                                var tmp0  = data.data.data_now/data.data.data_total*100;
                                $('#'+filename).progressbar("value",Math.round(tmp0));
                                set_info(filename,"status","正在处理数据:"+data.data.data_now+"/"+data.data.data_total);
                            }
                        }
                    }
                    if(data.data.now == 2 && data.data.total == 0)
                    {
                        set_info(filename,"status","读取数据库完成...");
                    }
                    if(data.data.total > 0)
                    {
                        $('#'+filename).progressbar("value",0);
                        set_info(filename,"status","正在写入文件中并通知ERP...");
                    }
                    if(data.data.now > 0 && data.data.total > 0)
                    {
                        var tmp  = data.data.now/data.data.total*100;
                        $('#'+filename).progressbar("value",Math.round(tmp));
                        if(tmp == 100)
                        {
                            set_info(filename,"status","数据处理完成，正在同步到云端");
                        }
                    }
                }
                if(data.data.finish != undefined)
                {
                    if(data.data.finish == 1){
                        stop_timer(filename);
                        plus_download_button(filename);
                        plus_delete_button(filename);
                        set_info(filename,"status","数据处理完成,共有记录:"+data.data.data_total);
                        if(window.localStorage.getItem('download_'+filename) == undefined)
                        {
                            window.localStorage.setItem('download_'+filename,1);
                            download(filename);
                        }
                    }
                }
            }else{
                if_retry(filename);
                plus_retry_button(filename);
                plus_delete_button(filename);
                if(data.err_msg != undefined)
                {
                    set_info(filename,"status",data.err_msg);
                }else{
                    set_info(filename,"status","请重试");
                }
                stop_timer(filename);
            }
            plus_delete_button(filename);
        },
        error: function(data) {
            plus_delete_button(filename);
            console.log(data.responseText);
        }
    });
};

var stop_timer = function(filename)
{
    var timer = "timer_"+filename;
    try{
        clearInterval(window[timer]);
    }catch(Exception){

    }
}

var process_del = function(filename)
{
    local_storage_del(filename);
    process_bar_minus(filename);
    var data = {
        "filename":filename
    };
    $.ajax({
        url: '/admin/export_orders_ajax/process_minus',
        type: "POST",
        data: data,
        dataType: "json",
        success: function(data) {
            if (data.code == 0) {
                if(data.filename)
                {
                }
            }else{

                console.log(data.err_msg);
            }
        },
        error: function(data) {
            console.log(data.responseText);
        }
    });
};

var process_start = function(filename)
{
    var data = {
        "filename":filename
    };
    $.ajax({
        url: '/admin/export_orders_ajax/process_start',
        type: "POST",
        timeout:status_refresh_time*10,
        data: data,
        dataType: "json",
        success: function(data) {
            if (data.code == 0) {
                if(data.filename)
                {
                }
            }else{
                layer.msg(data.err_msg);
            }
        },
        error: function(data) {
            console.log(data.responseText);
        }
    });
};

var auto_refresh_status = function(filename)
{
    var tmp = "timer_"+filename;
    window[tmp] = setInterval("process_status('"+filename+"');",status_refresh_time);
};

var process_bar_minus = function(filename)
{
    $('#'+filename+"_contains").remove();
};

var process_bar_plus = function(filename)
{
    var process = "" +
        "<div class='' style='height:3px;display:block;float:left;width:100%;'></div>" +
        "<div class='span12' id='"+filename+"_contains'>" +
        "<div class='span3'>" +
        "<label></label>" +
        "<div id='"+filename+"_filename'></div>" +
        "</div>"+
        "<div class='span3'title=\"这里是进度条\">" +
        "<div id=\""+filename+"\"><div class=\"progress-label\">loading...</div></div>" +
        "</div>"+
        "<div class='span1'>" +
        "<label></label>" +
        "<div id='"+filename+"_opera' data-id='"+filename+"' class='file_opera'>"+
        "<span class='download_ext' style='display:none;' title='下载'><i class='icon-download'></i>&nbsp;</span>"+
        "<span class='retry_ext' style='display:none;'  title='重试'><i class='icon-refresh'></i>&nbsp;</span>"+
        "<span class='delete_ext' style='display:none;'  title='删除'><i class='icon-trash'></i>&nbsp;</span>"+
        "</div>" +
        "</div>"+
        "<div class='span4'>" +
        "<label></label>" +
        "<div id='"+filename+"_status'></div>" +
        "</div>"+
        "</div>";
    if($('#'+filename).length != 0)
    {
        return;
    }
    $('#processbarlistoffline').prepend(process);
    init_process_bar(filename);
    auto_refresh_status(filename);
};

var process_info = function(filename)
{
    var data = {
        "filename":filename
    };
    $.ajax({
        url: '/admin/export_orders_ajax/process_info',
        type: "POST",
        data: data,
        dataType: "json",
        success: function(data) {
            if (data.code == 0) {
                if(data.filename)
                {
                    if(data.h_filename != undefined)
                    {
                        set_info(data.filename,"filename",data.h_filename);
                        record_info_localstorage(data.filename,"filename",data.h_filename);
                    }
                }
            }else{
                // console.dir(data.err_msg);
                set_info(filename,"status",data.err_msg);
                plus_retry_button(filename);
                plus_delete_button(filename);
            }
        },
        error: function(data) {
            console.log(data.responseText);
        }
    });
};

var init_process_bar = function(filename)
{
    var progressbar = $( "#"+filename ),
        progressLabel = $( "#"+filename + " .progress-label" );

    progressbar.progressbar({
//            value: "",
        change: function() {
            progressLabel.text( progressbar.progressbar( "value" ) + "%" );
        },
        complete: function() {
            progressLabel.text( "完成！" );
        },
    });
};

$(document).on("click",".download_ext",function()
{
    var file = $(this).parent().data("id");
    download(file);
});

$(document).on("click",".retry_ext",function()
{
    var filename = $(this).parent().data("id");
    window['over_time_'+filename] = 1;
    minus_retry_button(filename);
    retry(filename);
});

$(document).on("click",".delete_ext",function()
{
    var filename = $(this).parent().data("id");
    process_del(filename);
});

$(function() {
    $(".submit_sync").on("click",function(){
        process_submit();
    });
    init_by_local_storage();
});