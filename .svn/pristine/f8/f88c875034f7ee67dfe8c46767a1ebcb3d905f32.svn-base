<?php
/**
 * Created by PhpStorm.
 * User: brady
 * Desc:
 * Date: 2017/6/26
 * Time: 17:18
 */
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<div class="search-well">
    <ul class="nav nav-tabs">
        <?php foreach ($tabs_map as $k => $v): ?>
            <li <?php if ($k == $tabs_type) echo " class=\"active\""; ?>>
                <a href="<?php echo base_url($v['url']); ?>">
                    <?php echo $v['desc']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

用户id:<input type="text" name = "id">
<input id='rollbackSub' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">