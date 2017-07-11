<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <select name="type" style="width: 180px;">
            <option value=""><?php echo lang('type'); ?></option>
            <?php foreach (config_item('tickets_problem_type') as $key => $value) { ?>
                <option value="<?php echo $key ?>"
                    <?php if ($searchData['type'] >= '0' && $key == $searchData['type']) {
                        echo " selected=selected";
                    }
                    ?> >
                    <?php echo lang($value); ?>
                </option>
            <?php } ?>
        </select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('time'); ?></th>
            <th><?php echo lang('tickets_type'); ?></th>
            <th><?php echo lang('tickets_title'); ?></th>
            <th><?php echo lang('status'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['create_time'] ?></td>
                    <td><?php echo lang(config_item('tickets_problem_type')[$item['type']]); ?></td>
                    <td><?php echo $item['title'] ?></td>
                    <td><?php echo lang(config_item('tickets_status')[$item['status']]);?></label></td>
                    <td><a href="javascript:view_tickets(<?php echo $item['id']?>);"> <?php echo lang('view_ticket_detail')?> </a></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>

<script>
    function view_tickets(id){
        layer.open({
            type: 2,
            area: ['900px', '680px'],
            fix: true, //不固定
            maxmin:true,
            title:"<?php echo lang('view_ticket_detail')?>",
            content: '<?php echo base_url('ucenter/history_tickets/view_tickets'); ?>/' + id,
            cancel:function(index){
                window.location.reload();
            },
        });

    }


</script>