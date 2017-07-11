$(function(){
    $('.unprocessed_count_box').hide();
    get_count();
    setInterval('get_count()',300000);

    $('.tickets_input_box_trim').focusout(function(){
        $(this).val($.trim($(this).val()));
    }).keyup(function(){
        $(this).val($.trim($(this).val()));
    });
});
function get_count(){
    $.ajax({
        type:"post",
        url:"/admin/my_tickets/get_count",
        dataType:"json",
        async:true,
        success:function(res){
            if(res.success==1){
                $('.unprocessed_count_box').show();
                $('.unprocessed_count').empty().append('+'+res.h);
                $('.unassigned_count').empty().append('+'+res.a);
            }
        }
    });
}
