<!--邮编-->
<div class="item clear" id="div_zip_code">
    <dl>
        <dt><?php echo lang('checkout_zip_code'); ?><span></span></dt>
        <dd><input type="text" maxlength="16" class="input" id="box_zip_code" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"></dd>
    </dl>
</div>