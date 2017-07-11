<style>
    /*.main{height: 820px;}*/
    .one-row{width: 100%;float:left;}
    .font-lang{width: 20%;}
	.ranking{width:100%;}
    .ranking table tr td{border: solid 1px #CDCDCD; line-height: 30px;}
    table td{width: 20%; text-align: center;}
    .font-lang td{ width: 20%;}
    table tr td b{color: #666666;}
	#tab{width:100%;}
</style>

<div class="container" style="border:0px;">
<div class="one-row">
    <!--2x5排行-->
    <!--<div class="container">-->
        <?php $date = date("Y-m",time());?>
        <a href="#page-stats1" class="block-heading" data-toggle="collapse"><?php echo $date?>&nbsp;&nbsp;&nbsp;<?php echo lang('personnel_store') ?></a>
        <div id="page-stats1" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('user_name') ?></b></td>
                    <td><b><?php echo lang('store_number') ?></b></td>
                    <td><b><?php echo lang('pay_count') ?></b></td>
                </tr>
                <?php foreach($result as $value){ ?>
                    <?php static $i=1; ?>
                    <?php if($i<=25){ ?>
                    <tr>
                        <td class="list"><?php echo $i++ ?></td>
                        <td class="id"><?php echo $value->uid ?></td>
                        <td class="name"><?php echo $value->name ?></td>
                        <td class="sum"><?php echo $value->total_count ?></td>
                        <td class="pay_count"><?php echo $value->pay_total ?></td>
                    </tr>
                    <?php } ?>
                <?php }?>
            </table>
        </div>
    <!--</div>-->

</div>
</div>

<script>
    $(document).ready(function(){
        $(".one-row table,.two-row table").each(function(){
            $(this).find('tr').eq(1).css("color","#FF0000");
            $(this).find('tr').eq(2).css("color","#00AA55");
            $(this).find('tr').eq(3).css("color","#178BFF");
        })
    })
</script>
