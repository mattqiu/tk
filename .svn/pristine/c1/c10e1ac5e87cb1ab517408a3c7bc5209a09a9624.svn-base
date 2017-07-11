
<div class="search-well">
    <select name="language_id" onchange="changeLang(this.value)">
    <?php foreach($lang_all as $lang) {?>
        <option <?php if(isset($language_id) && $language_id == $lang['language_id']) echo 'selected'; ?> value="<?php echo $lang['language_id']?>"><?php echo $lang['name']?></option>
    <?php }?>
    </select>

    <div class="pull-right">
        <button class="btn" type="button" rel="tooltip" data-original-title="添加" onclick="location.href='/admin/add_incentive_system_management?language_id=<?php echo $language_id; ?>'"><i class="icon-plus"></i>添加</button>
    </div>
</div>


<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th><?php echo lang('reward_name'); ?></th>
            <th><?php echo lang('reward_content'); ?></th>
            <th><?php echo lang('reward_status'); ?></th>
            <th><?php echo lang('reward_sort'); ?></th>
            <th><?php echo lang('reward_op'); ?></th>
        </tr>
        </thead>
        <tbody>
       <?php
       foreach ($list as $v)
        {
            echo "<tr>";
            echo "<td>";
            echo "<td>{$v['title']}</td>";
            echo "<td>{$v['content']}</td>";
            echo "<td>{$v['status']}</td>";
            echo "<td>{$v['sort']}</td>";
            echo "<td><a href='/admin/add_incentive_system_management?language_id=$language_id&id=".$v['id']."'>编辑</a></td>";
            echo "<td>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>


<?php
if (isset($pager))
{
    echo $pager;
}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

    function changeLang(langId){
        window.location.href = '/admin/incentive_system_management?language_id='+langId;
    }


</script>