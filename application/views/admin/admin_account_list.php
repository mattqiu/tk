<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<form class="form-inline" action="<?php echo base_url('admin/admin_account_list'); ?>" method="get" style="margin-bottom: 10px;">
    <input autocomplete="off" value="<?php if(isset($searchData['email'])){echo $searchData['email'];} ?>" name="email" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control" placeholder="<?php echo lang('email_text'); ?>" />
    <select name="role" style="width: 180px;">
        <option value=""><?php echo lang('role'); ?></option>
        <?php foreach ( config_item('admin_role') as $key => $value) { ?>
            <option value="<?php echo $key ?>"
                <?php if ($searchData['role'] >= '0' && $key == $searchData['role']) {
                    echo " selected=selected";
                }
                ?> >
                <?php echo lang($value); ?>
            </option>
        <?php } ?>
    </select>
    <select name="status" style="width: 180px;">
        <option value=""><?php echo lang('status'); ?></option>
        <?php foreach ( config_item('admin_account_status') as $key => $value) { ?>
            <option value="<?php echo $key ?>"
                <?php if ($searchData['status'] >= '0' && $key == $searchData['status']) {
                    echo " selected=selected";
                }
                ?> >
                <?php echo lang($value); ?>
            </option>
        <?php } ?>
    </select>
<button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search'); ?></button>
</form>
<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo lang('email_text') ?></th>
                <th><?php echo lang('role'); ?></th>
                <th><?php echo lang('regi_create_time'); ?></th>
                <th><?php echo lang('status'); ?></th>
                <th colspan="3"><?php echo lang('action')?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td><?php echo lang(config_item('admin_role')[$item['role']]); ?></td>
                    <td><?php echo $item['create_time']; ?></td>
                    <td><?php echo lang(config_item('admin_account_status')[$item['status']]); ?></td>
                    <td>
                        <a href="javascript:resetAdminAccountPw(<?php echo $item['id']?>,['<?php echo lang('reset_password');?>','<?php echo lang('confirm');?>','<?php echo lang('no');?>']);"><?php echo lang('reset_password')?></a>
                    </td>
                    <td>
                        <?php if($item['status'] && $item['status']!=3){ ?>
                        <a href="javascript:changeAdminAccountStatus(<?php echo $item['id']?>,0);"><?php echo lang('account_disable')?></a>
                        <?php }else{?>
                        <a href="javascript:changeAdminAccountStatus(<?php echo $item['id']?>,1);"><?php echo lang('account_enable')?></a>
                        <?php }?>
                    </td>
                    <td>
                        <a href="javascript:deleteAdminAccount(<?php echo $item['id']?>,['<?php echo lang('is_delete_uid');?>','<?php echo lang('confirm');?>','<?php echo lang('no');?>']);"><?php echo lang('delete')?></a>
                    </td>
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