<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        <div class="pull-right">
            <button class="btn" type="button" rel="tooltip" data-original-title="<?php echo lang('add_bulletin_board'); ?>" onclick="location.href='/admin/add_bulletin_board'"><i class="icon-plus"></i><?php echo lang('add_bulletin_board'); ?></button>
        </div>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th>English</th>
                <th>中文</th>
                <th>繁體</th>
                <th>한국어</th>
                <th></th>
                <th><?php echo lang('sort'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['english'] ?></td>
                    <td><?php echo $item['zh'] ?></td>
                    <td><?php echo $item['hk']; ?></td>
                    <td><?php echo $item['kr']; ?></td>
                    <td><?php echo $item['display']? lang('need_display') : lang('no_display') ?></td>
                    <td><?php echo $item['sort'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                    <td><a href="<?php echo base_url("admin/add_bulletin_board/index").'/'.$item['id']?>"><i class="icon-edit"></i></a></td>
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
<?php echo $pager;