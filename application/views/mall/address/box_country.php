<!--选择国家、地区-->
<div class="item clear">
    <dl>
        <dt><?php echo lang('checkout_deliver_address'); ?><span>*</span></dt>
        <dd id="box_addr">
            <select class="select" id="box_country" onchange="cb_box_country();">
                <option value="0"><?php echo lang('checkout_addr_country'); ?></option>
            </select>
        </dd>
    </dl>
</div>
