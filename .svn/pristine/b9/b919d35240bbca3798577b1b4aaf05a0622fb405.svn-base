
<link rel="stylesheet" href="<?php echo base_url('ucenter_theme/stylesheets/address.css'); ?>">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<div class="address_contain">
    <!--中国大陆区收货地址-->
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <?php
        foreach($country_area as $key_country=>$value_country) {
        if($key_country == $country_id){ ?>
    <div class="address_content">
        <p><?php echo lang($value_country."_address");?><span class="num_address"><?php echo lang_attr("address_limit",['number'=>count($address[$value_country])])?></span></p>
        <a href="javascript:showBg();"  class="add_address " data-count="<?php echo count($address[$value_country]); ?>"  data-country="<?php echo $value_country;?>">+<?php echo lang("checkout_deliver_add");?></a>
        <table>
            <thead>
            <tr style="width:100%">
                <td style="width:10%;" ><?php echo lang("admin_order_consignee");?></td>
                <td style="width:20%;" ><?php echo lang("user_region");?></td>
                <td style="width:20%;"><?php echo lang("user_address_detail");?></td>
                <td style="width:10%;" ><?php echo lang("address_mobile");?></td>
                <td style="width:10%;" ><?php echo lang("admin_order_zip_code");?></td>
                <td style="width:10%;" ><?php echo lang("checkout_reserve_num");?></td>
                <td style="width:10%;"><?php echo lang("address_action");?></td>
                <td style="width:10%;color:red;cursor:pointer;"   onclick="slide_up_down(this,<?php echo $value_country; ?>)">
                    <!--<span class="down"></span>-->
                        <img data-src="up" src="<?php echo base_url('ucenter_theme/images'); ?>/up.png"/>
                        <span ><?php echo lang("pack_up");?></span>
                </td>
            </tr>
            </thead>
            <tbody id="<?php echo $value_country;?>" >
            <?php if(!empty($address[$value_country])){ foreach($address[$value_country] as $v):  ?>
            <tr  style="width:100%">
                <td  style="width:10%" > <?php echo $v['consignee'];?></td>
                <td  style="width:20%;"><?php echo $v['region'];?></td>
                <td  style='width:20%;'><?php echo $v['address_detail'];?></td>
                <td  style="width:10%" ><?php echo $v['phone'];?></td>
                <td  style="width:10%" ><?php echo $v['zip_code'];?></td>
                <td  style="width:10%" ><?php echo $v['reserve_num'];?></td>
                <td  style="width:10%" >  
                    <a href="javascript:showBg();" data-user-country="<?php echo $v['country'];?>" onclick="click_addr_edit(<?php echo $v['id'];?>,this);"  ><?php echo lang("checkout_deliver_modify");?></a>  |  <a href="javascript:void(0)" data-id="<?php echo $v['id'];?>" class="address_del"><?php echo lang("checkout_deliver_delete");?></a>
                </td>
                <td  style="width:10%">
                    <?php if($v['is_default'] == 1) { ?>
                        <span style="color:#f00;"  disabled="disabled" data-id="<?php echo $v['id'];?>"><?php echo lang('checkout_deliver_default'); ?></span>
                    <?php } else {?>
                        <span style="color:#52b6f1;cursor: pointer;" class="set_default" data-id="<?php echo $v['id'];?>"><?php echo lang('set_default_address'); ?></span>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach; }?>
            </tbody>
        </table>
    </div>
    <?php unset($address[$value_country]);break;
        }
    }
    ?>

    <?php
        foreach($address as $key=>$value) {
    ?>
            <div class="address_content">
                <p><?php echo lang($key."_address");?><span class="num_address"><?php echo lang_attr("address_limit",['number'=>count($address[$key])])?></span></p>
                <a href="javascript:showBg();"  class="add_address" data-count="<?php echo count($address[$key]); ?>"  data-country="<?php echo $key;?>">+<?php echo lang("checkout_deliver_add");?></a>
                <table>
                    <thead>
                    <tr>
                        <td style="width:10%"><?php echo lang("admin_order_consignee");?></td>
                        <td style="width:20%"><?php echo lang("user_region");?></td>
                        <td style="width:20%"><?php echo lang("user_address_detail");?></td>
                        <td style="width:10%"><?php echo lang("address_mobile");?></td>
                        <td style="width:10%"><?php echo lang("admin_order_zip_code");?></td>
                        <td style="width:10%"><?php echo lang("checkout_reserve_num");?></td>
                        <td style="width:10%"><?php echo lang("address_action");?></td>
                        <td style="width:10%" style="color:#f00;"  onclick="slide_up_down(this,<?php echo $key;?>)">
                            <!--<span class="down"></span>-->
                            <label for="">
                                <img data-src="down" src="<?php echo base_url('ucenter_theme/images'); ?>/down.png"/>
                                <span id="change" style="cursor:pointer" ><?php echo lang("spread");?></span>
                            </label>
                        </td>
                    </tr>
                    </thead>
                    <tbody  id="<?php echo $key;?>" style="display:none">
                    <?php  if(!empty($value)){ foreach( $value as $v): ?>
                        <tr>
                            <td  style="width:10%"><?php echo $v['consignee'];?></td>
                            <td  style="width:20%"><?php echo $v['region'];?></td>
                            <td style="width:20%"><?php echo $v['address_detail'];?></td>
                            <td  style="width:10%"><?php echo $v['phone'];?></td>
                            <td style="width:10%"><?php echo $v['zip_code'];?></td>
                            <td  style="width:10%"> <?php echo $v['reserve_num'];?></td>
                            <td  style="width:10%;color:#52b6f1;">
                                <a href="javascript:showBg();" data-user-country="<?php echo $v['country'];?>"  onclick="click_addr_edit(<?php echo $v['id'];?>,this);"  ><?php echo lang("checkout_deliver_modify");?></a> | <a href="javascript:void(0)" data-id="<?php echo $v['id'];?>" class="address_del"><?php echo lang("checkout_deliver_delete");?></a>
                            </td>
                            <td  style="width:10%" style="color:#52b6f1;">
                                <?php if($v['is_default'] == 1) { ?>
                                    <span style="color:red;" disabled="disabled" data-id="<?php echo $v['id'];?>"><?php echo lang('checkout_deliver_default'); ?></span>
                                <?php } else {?>
                                    <span style="color:#52b6f1;cursor: pointer;" class="set_default" data-id="<?php echo $v['id'];?>"><?php echo lang('set_default_address'); ?></span>
                                <?php }?>
                            </td>
                        </tr>
                    <?php endforeach; }?>
                    </tbody>
                </table>
            </div>
    <?php
        }
    ?>

</div>
<!-- 编辑收货地址弹出层  start-->
<link rel="stylesheet" href="<?php echo base_url('themes/mall/css/head_food_2.css?v=1.0'); ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/mall/css/base.css?v=1.0');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/store_register.css?v=1'); ?>" />
<script src="<?php echo base_url('themes/mall/js/main.js?v=20170614'); ?>"></script>
<script src="<?php echo base_url('themes/mall/js/base.js?v=1'); ?>"></script>
<!--<script src="--><?php //echo base_url('file/js/user_address_linkage.js?v=3'); ?><!--"></script>-->

<style>
    .wodl .close { position: absolute; right: 1%; top: 2%; cursor: pointer; font-size: 2.5em; display: block; }
</style>
<input type="hidden" id="data-country_id_1" />
<div id="BOX_nav" class="xm-edit-addr wodl BOX_nav clear" style="width: 780px;height:auto; display: none;padding-bottom: 25px;">

    <span class="close" onclick="closeBg();">×</span>
    <h3 id="box_title"></h3>
    <div class="item">
        <!--		<em id="box_title_em"></em>-->
        <div class="item-title"></div>
        <input type="hidden" id="box_type" />
        <input type="hidden" id="box_id" />
    </div>

    <!--收货地址-->
    <div class="item clear">
        <dl>
            <dt><?php echo lang('checkout_deliver_address'); ?><span>*</span></dt>
            <dd id="box_addr" style="margin-left:0">
                <select class="select" id="box_country" >
                    <option value="0"><?php echo lang('checkout_addr_country'); ?></option>

                </select>
            </dd>
        </dl>
    </div>

    <!--详细收货地址-->
    <div class="item clear">
        <dl>
            <dt><span>&nbsp;</span></dt>
            <dd style="margin-left:0"><textarea type="text" class="xxidz" id="box_addr_detail" maxlength="255" maxlength="100"  placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>"></textarea></dd>
        </dl>
    </div>

    <!--收货人-->
    <div class="item clear" id="div_consignee">
        <dl>
            <dt><?php echo lang('checkout_consignee'); ?><span>*</span></dt>
            <dd style="margin-left:0"><input type="text" style="width: 235px;" class="input" maxlength="50" placeholder="<?php echo lang('checkout_consignee'); ?>" id="box_consignee"></dd>
        </dl>
    </div>

    <!--First Name-->
    <div class="item clear d-n" id="div_first_name">
        <dl>
            <dt><?php echo lang('first_name'); ?><span>*</span></dt>
            <dd style="margin-left:0"><input type="text" style="width: 235px;"  maxlength="25" id="box_first_name" class="input" placeholder="<?php echo lang('first_name');?>"></dd>
        </dl>
    </div>
    <!--Last Name-->
    <div class="item clear d-n" id="div_last_name">
        <dl>
            <dt><?php echo lang('last_name'); ?><span>*</span></dt>
            <dd style="margin-left:0"><input type="text"  style="width: 235px;" maxlength="25" id="box_last_name" class="input" placeholder="<?php echo lang('last_name');?>"></dd>
        </dl>
    </div>

    <!--联系电话-->
    <div class="item clear" id="div_phone_other" >
        <dl>
            <dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd style="margin-left:0"><input type="text"  style="width: 235px;" autocomplete = "off" class="input" id="box_phone" placeholder="<?php echo lang('check_addr_rule_phone');?>"  maxlength="16"  ></dd>
        </dl>
    </div>
    <!--    美国联系电话-->
    <div class="item clear" id="div_phone_us" style="display:none;">
        <dl>
            <dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd style="position: relative;margin-left:0">
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T1_onkeyup()"  onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 30px;" autocomplete = "off" class="input"  id="box_phone_1"   maxlength="3" size="3"  >
                <span style="position: absolute;top:5px;left:55px;font-size:20px;color:#333">-</span>
                <input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'') ;return T2_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"  style="width: 30px;" autocomplete = "off" class="input"  id="box_phone_2"  maxlength="3" size="3"  >
                <span style="position: absolute;top:5px;left:117px;font-size:20px;color:#333">-</span>
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T3_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_3" maxlength="4" size="4"  >
            </dd>

        </dl>
    </div>

    <!--    韩国联系电话-->
    <div class="item clear" id="div_phone_kr" style="display:none;">
        <dl>
            <dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd style="position: relative;margin-left:0">
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T4_onkeyup()"  onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 30px;" autocomplete = "off" class="input"  id="box_phone_kr_1"   maxlength="3" size="3"  >
                <span style="position: absolute;top:5px;left:55px;font-size:20px;color:#333">-</span>
                <input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'') ;return T5_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"  style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_kr_2"  maxlength="4" size="4"  >
                <span style="position: absolute;top:5px;left:137px;font-size:20px;color:#333">-</span>
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T6_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_kr_3" maxlength="4" size="4"  >
            </dd>

        </dl>
    </div>

    <!--备用电话-->
    <div class="item clear" id="div_reserve_num">
        <dl>
            <dt><?php echo lang('checkout_reserve_num'); ?><span></span></dt>
            <dd style="margin-left:0"><input  type="text" style="width: 235px;" class="input" maxlength="20" placeholder="<?php echo lang('check_addr_rule_reserve_num');?>"  id="box_reserve_num"></dd>
        </dl>
    </div>

    <!--邮编-->
    <div class="item clear" id="div_zip_code">
        <dl>
            <dt><?php echo lang('checkout_zip_code'); ?><span></span></dt>
            <dd style="margin-left:0"><input   type="text" style="width: 235px;" maxlength="16" placeholder="<?php echo lang('check_addr_rule_zip_code');?>" class="input" id="box_zip_code"  onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"></dd>
        </dl>
    </div>

    <!--海关号-->
<!--    <div class="item clear d-n" id="div_customs_clearance">-->
<!--        <dl>-->
<!--            <dt>--><?php //echo lang('checkout_customs_clearance'); ?><!--<span>*</span></dt>-->
<!--            <dd><input  type="text" style="width: 235px;" class="input" autocomplete = "off"  id="box_customs_clearance" placeholder="--><?php //echo lang('checkout_customs_clearance'); ?><!--" maxlength="32"></dd>-->
<!--        </dl>-->
<!--    </div>-->


    <div class="item clear">
        <dl>
            <dt><span>&nbsp;</span></dt>
            <dd style="margin-left:0">
                &nbsp;&nbsp;&nbsp;&nbsp;<input value="<?php echo lang('checkout_save_addr'); ?>" type="button" id ="checkout_save_addr" style="width:111px;height:35px;" type="button" onclick="submit_box_save_addr(this);">
            </dd>
        </dl>
    </div>
</div>
<div id="fullbg" class="xm-backdrop" style="height: 1507px; width: 100%; display: none;"></div>
<!-- 编辑收货地址弹出层 end -->
<?php $this->load->view("ucenter/address");?>
<script>
    $(function(){
        linkage['000'] = {name: '<?php echo lang('con_others'); ?>', leaf: []};

        //设置默认地址
        $(document).on("click",".set_default",function(){
            var address_id = $(this).attr('data-id');
            address_id = parseInt(address_id);
            if (address_id <= 0) {
                layer.msg("system_error_again");
            }
            address.set_default($(this),address_id);
        });

        //地址删除
        $(document).on('click','.address_del',function(){
            var address_id = $(this).attr('data-id');
            address_id = parseInt(address_id);
            if (address_id <= 0) {
                layer.msg("system_error_again");
            }
            address.del($(this),address_id);
        });

        //地址新增
        //韩国和中国地址不能用同一个处理


        $(".add_address").click(function(){
            var country = $(this).attr('data-country'); //用户注册账号国家
            var location = "<?php echo $location;?>"; //用户位置
            var address_count = $(this).data('count');//用户地址个数

            if (location == '840' || location == '000') { //美国和其他国家 英语太长 宽度给宽些
                $(".item dt").width("30%");
                $(".item dd").width("70%");
            }

            if (address_count >= 5) { //地址超过五个 game over
                layer.msg("<?php echo lang('user_addr_full');?>")
                return false;
            }

            $('#box_type').val(1); //新增地址标志

            if(country == '156') {
                address.init_156(country);
                $("#data-country_id_1").attr('data-country_id_1','156');
            } else if(country == '344') {
                $("#data-country_id_1").attr('data-country_id_1','344');
                address.init_344(country);
            }else if(country == '410') {
                $("#data-country_id_1").attr('data-country_id_1','')
                address.init_410(country);
            }else if(country == '840') {
                $("#data-country_id_1").attr('data-country_id_1','')
                address.init_840(country);
            }else if(country == '000') {
                $("#data-country_id_1").attr('data-country_id_1','')
                address.init_000(country);
            }
        });//end add click



        $("#box_country").change(function(){
            var country_id = $('#box_country').val();
            var hk = $("#data-country_id_1").attr('data-country_id_1');
            if (country_id == '0') {
                hk = '';
            }
            if(hk == '344') {
                country_id = "344";
            }
            if (country_id == '156') {
                address.cb_box_country(country_id,'china');
            }  else if (country_id == '344') {
                address.cb_box_country(country_id,'hk');
            } else{
                address.cb_box_country(country_id,'');
            }

        })




    })

    function slide_up_down(obj,id)
    {
        if (id == 0) {
            id = '000';
        }
        var ids = "#"+id;
        $(ids).stop().slideToggle();
        var type = $(obj).find('span').text();
        var img_url = $(obj).find('img').attr("data-src");

        if(img_url == 'up') {
            $(obj).find('img').attr("src","<?php echo base_url('ucenter_theme/images'); ?>/down.png");
            $(obj).find('img').attr("data-src",'down');
        } else {
            $(obj).find('img').attr("src","<?php echo base_url('ucenter_theme/images'); ?>/up.png");
            $(obj).find('img').attr("data-src",'up');
        }
        var new_type;
        if (type == "<?php echo lang("pack_up");?>") {
            new_type = "<?php echo lang("spread");?>";
        } else if(type == "<?php echo lang("spread");?>") {
            new_type = "<?php echo lang("pack_up");?>";
        }
        $(obj).find('span').text(new_type);
    }

    function submit_box_save_addr(obj)
    {
        address.submit_box_save_addr(obj);
    }

    function  click_addr_edit(id,obj)
    {
        var data = <?php echo json_encode($address_edit); ?>;
        var country_code = data[id].country;
        if (country_code == "156") {
            if (data[id].addr_lv2 == '81') {
                country_id = country = 344;
            } else {
                country_id = country = country_code;
            }
        } else {
            country_id = country = country_code;
        }
        country = country_id;
        if(country == '156') {
            $("#data-country_id_1").attr('data-country_id_1','156');
        } else if(country == '344') {
            $("#data-country_id_1").attr('data-country_id_1','344');
        }else if(country == '410') {
            $("#data-country_id_1").attr('data-country_id_1','')
        }else if(country == '840') {
            $("#data-country_id_1").attr('data-country_id_1','')
        }else if(country == '000') {
            $("#data-country_id_1").attr('data-country_id_1','')
        }
        address.click_addr_edit(id);
    }

    function T1_onkeyup() {
        if($("#box_phone_1").val().length == 3){
            $("#box_phone_2").focus();
        }
    }
    function T2_onkeyup() {
        if($("#box_phone_2").val().length == 3){
            $("#box_phone_3").focus();
        }
    }

    function T3_onkeyup() {
        if($("#box_phone_3").val().length == 4){
            $("#box_phone_1").focus();
        }
    }
    function T4_onkeyup() {
        if($("#box_phone_kr_1").val().length == 3){
            $("#box_phone_kr_2").focus();
        }
    }
    function T5_onkeyup() {
        if($("#box_phone_kr_2").val().length == 4){
            $("#box_phone_kr_3").focus();
        }
    }

    function T6_onkeyup() {
        if($("#box_phone_kr_3").val().length == 4){
            $("#box_phone_kr_1").focus();
        }
    }
</script>
