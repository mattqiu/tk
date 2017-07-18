<div class="well">


    <div class="block">
        <p class="block-heading">OneDirect Orders</p>
        <div class="block-body">
            <form action="<?php echo base_url('admin/import_third_part_orders') ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="orders_csv" size="50" maxlength="100000" />
                <a href="<?php echo base_url('/upload/download/OneDirectTemplate.csv') ?>" style="margin-left: 25px;">OneDirect Orders 模板</a>
                <br/><br>
                <input type="submit" value="<?php echo lang('submit');?>"/>
            </form>
            <p style="color: red;"><?php echo !empty($error_msg) ? $error_msg : ''; ?></p>
        </div>

    </div>

    <br>

    <div class="block" style="display: none;">
        <p class="block-heading">Cancel OneDirect Orders</p>
        <div class="block-body">
            <form action="<?php echo base_url('admin/import_third_part_orders/onedirect_cancel') ?>" method="post">
                <input type="text" name="order_id" size="20" maxlength="50" placeholder="<?php echo lang('order_id')?>" />
                <br/>
                <input type="submit" value="<?php echo lang('submit');?>"/>
                <p style="color: red;"><?php echo !empty($onedirect_cancel_error_msg) ? $onedirect_cancel_error_msg : ''; ?></p>
            </form>
        </div>
    </div>

    <br>

    <div class="block">
        <p class="block-heading">WalMart Orders(沃尔玛订单)</p>
        <div class="block-body">
            <form action="<?php echo base_url('admin/import_third_part_orders/walmart_import') ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="orders_csv" size="50" maxlength="100000" />
                <a href="<?php echo base_url('/upload/download/WalmartTemplate.csv') ?>" style="margin-left: 25px;">WalMart Orders 模板</a>
                <br/><br>
                <input type="submit" value="<?php echo lang('submit');?>"/>
                <p style="color: red;"><?php echo !empty($walmart_error_msg) ? $walmart_error_msg : ''; ?></p>
            </form>
        </div>
    </div>

    <br>

    <div class="block" style="display: none;">
        <p class="block-heading">沃好订单</p>
        <div class="block-body">
            <form action="<?php echo base_url('admin/import_third_part_orders/walhao_import') ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="orders_csv" size="50" maxlength="100000" />
                <a href="" style="margin-left: 25px;">WoHao Orders 模板</a>
                <br/><br>
                <input type="submit" value="<?php echo lang('submit');?>"/>
                <p style="color: red;"><?php echo !empty($wohao_error_msg) ? $wohao_error_msg : ''; ?></p>
            </form>
        </div>
    </div>

    <p style="color: red;font-size: 16px;"><?php echo lang('import_third_order_tips'); ?></p>

</div>