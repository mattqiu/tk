<style>
    /*.main{height: 820px;}*/
    .one-row, .two-row{height: 400px;}
    .one-row>div{float: left; margin-left: 30px; }

    .two-row{margin-top: 30px;}
    .two-row>div{float: left; margin-left: 30px;}

    .container{width: 270px; }
    .font-lang{width: 300px;}
    .ranking table tr td{border: solid 1px #E8E8E8;}
    table tr{line-height: 30px;}
    table td{width: 90px; text-align: center;}
    .font-lang td{ width: 100px;}
    table tr td b{color: #666666;}
</style>


<div class="one-row">
    <!--2x5排行-->
    <div class="container">
        <a href="#page-stats1" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_2x5') ?></a>
        <div id="page-stats1" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach($personal_commission as $key=>$value){ ?>
                    <tr>
                        <td class="list"><?php echo $key+1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->personal_commission ?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>

    <!--138排行-->
    <div class="container">
        <a href="#page-stats2" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_138') ?></a>
        <div id="page-stats2" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach($company_commission as $key=>$value){ ?>
                    <tr>
                        <td class="list"><?php echo $key+1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->company_commission ?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>

    <!--团队销售排行-->
    <div class="container">
        <a href="#page-stats3" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_generationSales') ?></a>
        <div id="page-stats3" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach($team_commission as $key=>$value){ ?>
                    <tr>
                        <td class="list"><?php echo $key+1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->team_commission ?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>

    <!--团队销售无限代奖-->
    <div class="container font-lang">
        <a href="#page-stats4" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_infinityGeneration') ?></a>
        <div id="page-stats4" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach($infinite_commission as $key=>$value){ ?>

                    <tr>
                        <td class="list"><?php echo $key+1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->infinite_commission ?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
</div>

<div class="two-row">
    <!--个人销售排行榜-->
    <div class="container">
        <a href="#page-stats5" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_personalSales') ?></a>
        <div id="page-stats5" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach ($amount_store_commission as $key => $value) { ?>
                    <tr>
                        <td class="list"><?php echo $key + 1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->amount_store_commission ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!--周分红排行-->
    <div class="container">
        <a href="#page-stats6" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_weeklyProfit') ?></a>
        <div id="page-stats6" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach ($amount_profit_sharing_comm as $key => $value) { ?>
                    <tr>
                        <td class="list"><?php echo $key + 1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->amount_profit_sharing_comm ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!--周领导对等奖-->
    <div class="container">
        <a href="#page-stats7" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_weeklyCheckMatching') ?></a>

        <div id="page-stats7" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach ($amount_weekly_Leader_comm as $key => $value) { ?>
                    <tr>
                        <td class="list"><?php echo $key + 1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->amount_weekly_Leader_comm ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!--月领导分红排行-->
    <div class="container font-lang">
        <a href="#page-stats8" class="block-heading" data-toggle="collapse"><?php echo lang('Ranking_monthlyTopPerformers') ?></a>
        <div id="page-stats8" class="ranking in collapse">
            <table id="tab">
                <tr>
                    <td><b><?php echo lang('ranking') ?></b></td>
                    <td><b><?php echo lang('user_id') ?></b></td>
                    <td><b><?php echo lang('commission') ?></b></td>
                </tr>
                <?php foreach ($amount_monthly_leader_comm as $key => $value) { ?>
                    <tr>
                        <td class="list"><?php echo $key + 1 ?></td>
                        <td class="id"><?php echo $value->id ?></td>
                        <td class="commission"><?php echo $value->amount_monthly_leader_comm ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
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
