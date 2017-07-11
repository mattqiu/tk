<form id="upgrade_user_manually_form">
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <table class="upgradeTb">
        <tr>
            <td class="title">
                <?php echo lang('user_id')?>:
                <br/>
                (<?php echo lang('user_ids_notice')?>)
            </td>
            <td class="content"><textarea name="user_id_list" rows="3" cols="30"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" class="content">
                <table>
                    <tr class="radio_level_tr">
<!--                        <td><input class="radio_level" type="radio" name="levelType" value="month_fee_rank" /> --><?php //echo lang('monthly_fee_level')?><!--</td>-->
                        <td><input class="radio_level" type="radio" name="levelType" value="user_rank" /> <?php echo lang('store_level')?></td>
                        <td><input class="radio_level" checked type="checkbox" name="is_grant_generation" value="1"/> <?php echo lang('is_grant_generation')?></td>
                        <?php if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')){?>
                        <td><input class="radio_level" checked type="checkbox" name="is_certificate" value="1"/> <?php echo lang('is_certificate')?></td>
                        <?php }?>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="content">
                <select name="levelValue">
                    <option value=""><?php echo lang('please_sel_level');?></option>
                    <?php foreach(config_item('levels') as $key=>$item){?>
                    <?php if($key==4){ continue; }?>
                    <option value="<?php echo $key;?>"><?php echo lang($item);?></option>
                    <?php }?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="title">
                <input type="button" autocomplete="false" id="upgrade_user_manually" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
            <td class="content" id="upgrade_user_manually_msg"></td>
        </tr>
    </table>
</form>