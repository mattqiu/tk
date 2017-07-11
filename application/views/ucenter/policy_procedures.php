<div class="well">

    <div class="tabbable tabs-left">
        <ul class="nav nav-tabs" style="width:250px;">
            <li class="active" style="width:100%;"><a data-toggle="tab" href="#r1"><?php echo lang('title_1');?></a></li>
            <li style="width:100%;"><a data-toggle="tab" href="#r2"><?php echo lang('title_2');?></a></li>
            <li style="width:100%;"><a data-toggle="tab" href="#r3"><?php echo lang('title_3');?></a></li>
            <li style="width:100%;"><a data-toggle="tab" href="#r4"><?php echo lang('title_4');?></a></li>
            <li style="width:100%;"><a data-toggle="tab" href="#r5"><?php echo lang('title_5');?></a></li>
            <li style="width:100%;"><a data-toggle="tab" href="#r6"><?php echo lang('title_6');?></a></li>
            <li style="width:100%;"><a data-toggle="tab" href="#r7"><?php echo lang('title_7');?></a></li>
        </ul>
        <div class="tab-content">
            <div id="r1" class="tab-pane active">
                <p><?php echo lang('content_1');?></p>
            </div>
            <div id="r2" class="tab-pane">
                <p><?php echo lang('content_2');?></p>
            </div>
            <div id="r3" class="tab-pane">
                <p><?php echo lang('content_3');?></p>
            </div>
            <div id="r4" class="tab-pane">
                <p><?php echo lang('content_4');?></p>
            </div>
            <div id="r5" class="tab-pane">
                <p><?php echo lang('content_5');?></p>
            </div>
            <div id="r6" class="tab-pane">
                <p><?php echo lang('content_6');?></p>
            </div>
            <div id="r7" class="tab-pane">
                <p><?php echo lang('content_7');?></p>
            </div>
        </div>
    </div>
</div>
<style>
    .tab-pane ul li{
        font-weight: bold;
        color: darkred;
    }
</style>
<script>
    $(function(){
        var index = location.href.indexOf('#');
        var lc = location.href.substr(index+1,2);
        if(index != -1){
            $('.active').each(function(){
                $(this).removeClass('active');
            });
            $('.nav a[href="#'+lc+'"]').parent().addClass('active');
            $('#'+lc).addClass('active');
        }
    });
</script>