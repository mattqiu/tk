
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <select name="curComType" id="com_type" class="com_type">
            <option value=""><?php echo lang('type'); ?></option>
                <?php foreach ($commission_type as $key => $value) { ?>
                    <option value="<?php echo $key ?>"
                       <?php if ($key == $curComType) {
                         echo " selected=selected";
                       }
                       ?> >
                    <?php echo lang($value); ?>
                    </option>
                <?php } ?>
        </select>
		<input type="text" name="order_id" value="<?php echo $order_id;?>" class="input-medium search-query" placeholder="<?php echo lang('order_sn')?>">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $start_time; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $language; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $end_time; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $language; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('type'); ?></th>
                <th><?php echo lang('money'); ?></th>
                <th><?php echo lang('pay_id'); ?></th>
                <th><?php echo lang('order_sn'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['create_time'] ?></td>
                    <td><?php echo lang($commission_type[$item['item_type']]); ?></td>
                    <td>
                        $ <?php echo number_format($item['amount']/100,2,'.','') ?>
                        <?php if(isset($item['relatedInfo']) && $item['relatedInfo']){ ?>
                            <a rel="tooltip" style="margin-left:5px" href="##" data-original-title="<?php echo '['.$item['relatedInfo']['commName'].']<br/>'.$item['relatedInfo']['createTime'] ?>"><i class="icon-question-sign"></i></a>
                        <?php }?>
                    </td>
                    <td>
                        <?php
                        if($item['related_uid']!=0){
                            echo $item['related_uid'];
                        }else{
                            echo(" ");
                        }
                        ?>
                    </td>
                    <td><?php echo $item['order_id']?$item['order_id']:''?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <?php if ($code == 1001){ ?>
                <tr>
                    <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                </tr>
            <?php }elseif($code == 1002){ ?>
                <tr>
                    <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_time') ?></th>
                </tr>
            <?php }elseif($code == 1003){ ?>
                <tr>
                    <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_search') ?></th>
                </tr>
            <?php }elseif($code == 1004){ ?>
                <tr>
                    <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_time_null') ?></th>
                </tr>
            <?php } elseif($code ==1005) { ?>
                 <tr>
                    <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('limit_query_month') ?></th>
                 </tr>
            <?php }  ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;