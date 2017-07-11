<div class="well" style="position:relative">

    <div class="tabbable tabs-left" >

        <div class="nav nav-tabs" style="width:200px; list-style: none;text-align:center">
                    <div style="width:201px;height:35px;line-height:35px;background:#e5e5e5;text-align:center;border:1px solid #ddd;border-bottom:none;box-sizing: border-box"><?php   echo lang('admin_file_type'); ?></div>
            <?php  $file_type = config_item('admin_file_type');  foreach($file_type as $key => $val) {  
                if( isset($select_type) && $select_type == $key) {
                   echo   "<div id =\"type_$key\" class=\"conMsg  activeMsg \" style=\"width:100%;\"><a data-toggle=\"tab\" href=\"#\" >".lang($val)."</a></div>";
                } else {
                   echo   "<div id =\"type_$key\" class=\"conMsg\" style=\"width:100%;\"><a data-toggle=\"tab\" href=\"#\" >".lang($val)."</a></div>";
                }
            }
                ?>
        </div>
        <style>
            .tabbable .conMsg{line-height:40px;border-bottom:1px solid #ddd;border-left:1px solid #ddd}
            .activeMsg{background:#ddd}

        </style>
        <div class="tab-content">
            <?php if($list){ ?>
                        <?php foreach($list as $type=>$item) {  ?>
            
           
            <div class="well type_<?php echo $type; ?> list_type"  <?php if($item['type_show'] === false) echo 'style="display:none"';  ?>>
            <table class="table table-hover">
                <tbody>
                     <?php foreach ($item['list'] as $key => $val)  {  ?>
                  <tr>
                                <td style="border-top:0; border-bottom:1px solid #ddd;"><li style="list-style-type:square;float:left;text-align: center;"></li><span style="margin-left:-10px;"><?php echo $val['file_real_name'] ?></span></td>
                                <td style="border-top:0;  border-bottom:1px solid #ddd;width:90px"><?php echo date("Y-m-d",strtotime($val['create_time']));  ?></td>
                                <td style="border-top:0;  border-bottom:1px solid #ddd;">
                                    <a href="<?php echo base_url('ucenter/file_download/download?type='.$val['file_type'].'&id='.$val['id']) ?>">
                                        <span class="file_download" ><img src="../ucenter_theme/images/download.png" width="20" height="20"/></span>
                                    </a></td>
                            </tr>
                     <?php  }  ?>
                </tbody>
            </table>
             <?php echo $item['page'];  ?>
           </div>
          <?php  }   ?>
            <?php  } else {  ?>
              <div class="well ">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                    </tr>
                 
                </tbody>
            </table>
           </div>
            <?php  }  ?>
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
    $(function() {
        var index = location.href.indexOf('#');
        var lc = location.href.substr(index+1,2);
        if(index != -1){
            $('.active').each(function(){
                $(this).removeClass('active');
            });
            $('.nav a[href="#'+lc+'"]').parent().addClass('active');
            $('#'+lc).addClass('active');
        }
        
//        $('.nav').find('.conMsg').each(function(){
//            $(this).on('click',function(){
//                var index = $(this).index();
//                $(this).addClass('activeMsg').siblings().removeClass('activeMsg');
//                $('.tab-content').children().eq(index).show().siblings().hide();
//            })
//        });
        
        $('.conMsg').click(function(){
            var name = "."+$(this).attr('id');
            //console.log(name);
            $(this).addClass('activeMsg').siblings().removeClass('activeMsg');
            $('.list_type').hide();
            $(name).show();
        });

    });
    
    
</script>