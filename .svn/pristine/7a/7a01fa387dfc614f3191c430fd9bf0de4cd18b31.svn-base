<?php
/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/2/15
 * Time: 15:56
 */

?>
<script src="<?php echo base_url('file/js/user_address_linkage.js'); ?>"></script>
<script>

    var linkage_china = $.extend(true,{},linkage);
    var linkage_hk = $.extend(true,{},linkage);
    delete(linkage_china[156].leaf[81]);
    delete(linkage_china[156].leaf[82]);
    delete(linkage_china[156].leaf[71]);
    for ( var i= 11;i<=71;i++){
        delete(linkage_hk[156].leaf[i]);
    }
    delete(linkage_hk[156].leaf[82]);
    //console.log(linkage[156]);
    //console.log(linkage_china[156]);
    //console.log(linkage_hk[156]);


</script>
<script>
//地址管理


    var linkage_tmps = linkage;
    var address = {
        data:{},
        linkage:linkage,
        front_verify_flag:true,//是否前台验证,
        lang_map:{ //
            "156": {
                "lv2": "<?php echo lang('checkout_addr_lv2_cn'); ?>",//省份/直辖市
                "lv3": "<?php echo lang('checkout_addr_lv3_cn'); ?>",//城市
                "lv4": "<?php echo lang('checkout_addr_lv4_cn'); ?>"//区/县
            },
            "410": {
                "lv2": "<?php echo lang('checkout_addr_lv2_kr'); ?>",//市/道
                "lv3": "",
                "lv4": ""
            },
            "840": {
                "lv2": "<?php echo lang('checkout_addr_lv2_us'); ?>",//州
                "lv3": "<?php echo lang('checkout_addr_lv3_us'); ?>",//City
                "lv4": "<?php echo lang('checkout_addr_lv4_us'); ?>"//District/County
            }
        },
        err_lang_map:{
            'country': "<?php echo lang('checkout_validator_country'); ?>",
            'addr_lv2': {
                "156": "<?php echo lang('checkout_validator_addr_lv2_cn'); ?>",
                "410": "<?php echo lang('checkout_validator_addr_lv2_kr'); ?>",
                "840": "<?php echo lang('checkout_validator_addr_lv2_us'); ?>"
            },
            'addr_lv3': {
                "156": "<?php echo lang('checkout_validator_addr_lv3_cn'); ?>",
                "410": "<?php echo lang('checkout_validator_addr_lv3_kr'); ?>",
                "840": "<?php echo lang('checkout_validator_addr_lv3_us'); ?>"
            },
            'addr_lv4': {
                "156": "<?php echo lang('checkout_validator_addr_lv4_cn'); ?>",
                "410": "<?php echo lang('checkout_validator_addr_lv4_kr'); ?>",
                "840": "<?php echo lang('checkout_validator_addr_lv4_us'); ?>"
            },
            'address_detail': "<?php echo lang('checkout_validator_address_detail'); ?>",
            'consignee': "<?php echo lang('checkout_validator_consignee'); ?>",
            'phone': "<?php echo lang('checkout_validator_phone'); ?>",
            'reserve_num': "<?php echo lang('checkout_validator_reserve_num'); ?>",
            'zip_code': "<?php echo lang('checkout_validator_zip_code'); ?>",
            'customs_clearance': "<?php echo lang('checkout_validator_customs_clearance'); ?>"
        },
        set_default:function(obj, address_id)
        {
            var data = {};
            data.id = address_id;

            //防止重复点击
            obj.html($('#loadingTxt').val());
            obj.removeClass("set_default");
            $.ajax({
                url: "/order/do_set_default_addr",
                type: "POST",
                data: data,
                dataType: "json",
                success: function(data) {
                    if (data.code == 0) {
                        layer.msg('<?php echo lang("set_success");?>')
                        setTimeout(function(){
                            obj.addClass("set_default");
                            location.reload();
                        },1000);
                    }else {
                        layer.msg(data.msg);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                }

            })
        },
        del:function(obj,address_id)
        {


            layer.confirm('<?php echo lang("you_will_del_address");?>', {
                btn: ['<?php echo lang("confirm");?>','<?php echo lang("admin_order_cancel");?>'] ,icon:3,title:'<?php echo lang("address_delete");?>',closeBtn:false,//按钮
            }, function(){
                var data = {};
                data.id = address_id;

                $.ajax({
                    url: "/order/do_delete_addr",
                    type: "POST",
                    data: data,
                    dataType: "json",
                    success: function(data) {
                        if (data.code == 0) {
                            layer.msg('<?php echo lang("delete_success");?>')
                            // setTimeout(function(){
                            location.reload();
                            // },1000);
                        }else {
                            layer.msg(data.msg);
                        }
                    },
                    error: function(data) {
                        console.log(data.msg);
                    }

                })
            }, function(){

            });
        },
        clear_data:function()
        {
            // 清数据
            $('#box_consignee').val("");
            $('#box_phone').val("");
            $('#box_phone_1').val("");
            $('#box_phone_2').val("");
            $('#box_phone_3').val("");
            $('#box_phone_kr_1').val("");
            $('#box_phone_kr_2').val("");
            $('#box_phone_kr_3').val("");
            $('#box_phone').val("");
            $('#box_reserve_num').val("");
            $('#box_first_name').val("");
            $('#box_last_name').val("");
            $('#box_zip_code').val("");
            $('#div_zip_code').find('dt').find('span').text('');

            $('#box_addr_detail').val("");
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            $('#box_country').nextAll().remove();
            $('#box_country').css('color', "#999").children('[value=0]').prop('selected', true);
            //$('#div_customs_clearance').css('display', 'none');
            $("#fullbg").css('display','block');
            $("#BOX_nav").css('display','block');
        },


        init:function(country_id)
        {

            address.clear_data();
            var country = country_id;
            //中国和香港行政区地址处理
            if (country == '156') {
                $('#box_country').css('display', 'block');//收货人显示
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('china_land').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('china_land').')'; ?>");
            } else if(country == '344') {
                $('#box_country').css('display', 'block');//收货人显示
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('china_hk').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('china_hk').')'; ?>");
            } else if(country == '840') {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('label_us').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('label_us').')'; ?>");
            } else if(country == '410') {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('label_ko').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('label_ko').')'; ?>");
            } else {
                $('#box_country').css('display', 'block');//收货人显示
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('other_region').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add').":(".lang('other_region').')'; ?>");
            }
            if(country_id == '156' || country_id == '344' || country_id == '000') {

                $('#box_country').css('display', 'block');//收货人显示
                $('#div_consignee').css('display', 'block');//收货人显示
                $('#div_first_name').css('display', 'none');//姓隐藏
                $('#div_last_name').css('display', 'none');//名隐藏
                $('#div_phone_other').css('display', 'block');//手机号码显示
                $('#div_phone_kr').css('display', 'none');//韩国手机号码隐藏
                $('#div_phone_us').css('display', 'none');//美国手机号码隐藏
                $('#div_reserve_num').css('display', 'block');//备用号码显示
                $('#div_zip_code').css('display', 'block');//邮编显示
                //$('#div_customs_clearance').css('display', 'none');//海关报税隐藏

            } else if (country_id == '410') {

                $('#div_consignee').css('display', 'block');//收货人显示
                $('#div_first_name').css('display', 'none');//姓隐藏
                $('#div_last_name').css('display', 'none');//名隐藏
                $('#div_phone_other').css('display', 'none');//手机号码显示
                $('#div_phone_us').css('display', 'none');//美国手机号码隐藏
                $('#div_phone_kr').css('display', 'block');//韩国手机号码显示
                $('#div_phone_other').css('display', 'none');//手机号码显示
                $('#div_reserve_num').css('display', 'none');//备用号码显示
                $('#div_zip_code').css('display', 'block');//邮编显示

                //$('#div_customs_clearance').css('display', 'block');//海关报税显示
                $('#div_zip_code').find('dt').find('span').text('*');
            } else {
                $('#div_consignee').css('display', 'none');//收货人显示
                $('#div_first_name').css('display', 'block');//姓隐藏
                $('#div_last_name').css('display', 'block');//名隐藏
                $('#div_phone_other').css('display', 'none');//手机号码隐藏
                $('#div_phone_us').css('display', 'block');//美国手机号码显示
                $('#div_phone_kr').css('display', 'none');//韩国手机号码隐藏
                $('#div_reserve_num').css('display', 'none');//备用号码显示
                $('#div_zip_code').css('display', 'block');//邮编显示
                $('#div_zip_code').find('dt').find('span').text('*');
                //$('#div_customs_clearance').css('display', 'none');//海关报税隐藏
            }

        },

        init_156:function(country_id)
        {
            var linkage_156;
            linkage_156 = linkage_china;
            console.log(linkage_156);
            address.init(country_id);
            // box append 国家列表
            $('#box_country').css('color', "#999");
            for (var country_code in linkage_156) {
                if(in_array(country_code,[country_id])) {
                    $('#box_country').html('<option value="0"><?php echo lang('checkout_addr_country'); ?><option value="'+country_code+'">'+linkage_156[country_code].name+'</option>');
                }
            }
            address.clear_data();

        },
        init_344:function(country_id)
        {
            var linkage_344;
            linkage_344 = linkage_hk;
            console.log(linkage_344);
            address.init(country_id);
            // box append 国家列表
            $('#box_country').css('color', "#999");
            //如果国家id是香港 那么还是还原回中国
            if (country_id == 344) {
                country_id = 156;
            }


            for (var country_code in linkage_344) {
                if(in_array(country_code,[country_id])) {
                    $('#box_country').html('<option value="0"><?php echo lang('checkout_addr_country'); ?><option value="'+country_code+'">'+linkage_344[country_code].name+'</option>');
                }
            }

            address.clear_data();

        },
        init_840:function(country_id)
        {
            linkage = linkage_tmps;
            address.init(country_id);

            for (var country_code in linkage) {
                if(in_array(country_code,[country_id])) {
                    $('#box_country').html('<option value="'+country_code+'">'+linkage[country_code].name+'</option>').hide();
                    address.cb_box_country(country_id,'');
                }
            }

        },
        init_410:function(country_id)
        {
            linkage = linkage_tmps;
            address.init(country_id);
            for (var country_code in linkage) {
                if(in_array(country_code,[country_id])) {
                    $('#box_country').html('<option value="'+country_code+'">'+linkage[country_code].name+'</option>').hide();
                    address.cb_box_country(country_id,'');
                }
            }

        },
        init_000:function(country_id)
        {
            linkage = linkage_tmps;
            address.init(country_id);
            // box append 国家列表
            $('#box_country').css('color', "#999");
            //如果国家id是香港 那么还是还原回中国

            for (var country_code in linkage) {
                if(in_array(country_code,[country_id])) {
                    $('#box_country').html('<option value="0"><?php echo lang('checkout_addr_country'); ?><option value="'+country_code+'">'+linkage[country_code].name+'</option>');
                }
            }

            address.clear_data();
        },
        refresh_box_addr_by_lv:function(id, lang, data)
        {
            var obj = $('#' + id);
            obj.children().remove();
            obj.append(
                $('<option/>').val(0).text(lang)
            );
            for (var code in data) {
                obj.append(
                    $('<option/>').val(code).text(data[code].name)
                );
            }
            return true;
        },
        cb_box_country:function(country_id,type)
        {
            $('#box_country').nextAll().remove();
            country_code = country_id;
            $('#BOX_nav').css('width','780');

            if (country_id == '344') {
                country_id = "156";
            }

            if (country_id === '0') {
                $('#box_country').css('color', "#999");
                return true;
            }
            $('#box_country').css('color', "#333");
            // 若 leaf 不为空，则可以走到 for 里面生成下一级
            if (type == 'china') {

                for (var i in linkage_china[country_id].leaf) {
                    $('#box_addr').append(
                        $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").on('change', address.cb_box_addr_lv2)
                    );
                    address.refresh_box_addr_by_lv("box_addr_lv2", address.lang_map[country_id].lv2, linkage_china[country_id].leaf);
                    break;
                }
            } else if(type == 'hk') {

                for (var i in linkage_hk[country_id].leaf) {
                    $('#box_addr').append(
                        $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").on('change', address.cb_box_addr_lv2)
                    );
                    address.refresh_box_addr_by_lv("box_addr_lv2", address.lang_map[country_id].lv2, linkage_hk[country_id].leaf);
                    break;
                }
            } else {

                for (var i in linkage[country_id].leaf) {
                    $('#box_addr').append(
                        $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").on('change', address.cb_box_addr_lv2)
                    );
                    address.refresh_box_addr_by_lv("box_addr_lv2", address.lang_map[country_id].lv2, linkage[country_id].leaf);
                    break;
                }
            }


            return true;
        },

        cb_box_addr_lv2:function()
        {
            var country_code = $('#box_country').val();
            var lv2_code = $('#box_addr_lv2').val();
            $('#box_addr_lv2').nextAll().remove();

            //生成City Text
            if(country_code == 840){
                $('#box_addr').append(
                    $('<input type="text" id="box_city" maxlength="25"/>').addClass("input").attr('placeholder', '<?php echo lang('city'); ?>')
                );
            }

            if (lv2_code == 0) {
                $('#box_addr_lv2').css('color', "#999");
                return true;
            }
            $('#box_addr_lv2').css('color', "#333");
            for (var i in linkage[country_code].leaf[lv2_code].leaf) {
                $('#box_addr').append(
                    $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv3").on('change', address.cb_box_addr_lv3)
                );
                address.refresh_box_addr_by_lv("box_addr_lv3", address.lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
                break;
            }
            return true;
        },
        cb_box_addr_lv3:function()
        {
            var country_code = $('#box_country').val();
            var lv2_code = $('#box_addr_lv2').val();
            var lv3_code = $('#box_addr_lv3').val();
            if (lv3_code == '8104') {
                $("#box_addr_detail").val('4/F, Center, No.29-39 Ashley Road, Tsim Sha Tsui,  Kowloon 尖沙咀亞士厘道29-39號九龍中心4樓');
                $("#box_addr_detail").attr('readonly','readonly');
                $("#box_addr_detail").attr('disable','disable');
            } else {
                $("#box_addr_detail").val('');
                $("#box_addr_detail").removeAttr('readonly');
                $("#box_addr_detail").removeAttr('disable');
            }
            $('#box_addr_lv3').nextAll().remove();

            if (lv3_code == 0) {
                $('#box_addr_lv3').css('color', "#999");
                return true;
            }
            $('#box_addr_lv3').css('color', "#333");

            for (var i in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
                $('#box_addr').append(
                    $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv4").on('change', address.cb_box_addr_lv4)
                );
                address.refresh_box_addr_by_lv("box_addr_lv4", address.lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
                break;
            }
            return true;
        },

        cb_box_addr_lv4 : function()
        {
            var lv4_code = $('#box_addr_lv4').val();

            if (lv4_code == 0) {
                $('#box_addr_lv4').css('color', "#999");
                return true;
            }
            $('#box_addr_lv4').css('color', "#333");
            return true;
        },
        submit_box_save_addr:function(obj)
        {
            var save_addr_flag = true;
            var data = {};

            var country = $('#box_country').children(':selected').val();
            if (country === '0') {
                layer.msg(address.err_lang_map.country); //请选择国家
                return false;
            }

            data.country = country;

            //二级地址
            if ($('#box_addr_lv2').children(':selected').val() == 0) {
                layer.msg(address.err_lang_map.addr_lv2[country]); //请选择省份/直辖市
                return false;
            }
            data.addr_lv2 = $('#box_addr_lv2').children(':selected').val();

            //三级地址
            if ($('#box_addr_lv3').children(':selected').val() == 0) {
                layer.msg(address.err_lang_map.addr_lv3[country]); //请选择城市
                return false;
            }
            data.addr_lv3 = $('#box_addr_lv3').children(':selected').val();
            data.city = $('#box_city').val();
            //data.city = $('#box_addr_lv3').children(':selected').val();

            //四级地址
            if ($('#box_addr_lv4').children(':selected').val() == 0) {
                layer.msg(address.err_lang_map.addr_lv4[country]);//请选择区/县
                return false;
            }
            data.addr_lv4 = $('#box_addr_lv4').children(':selected').val();

            //五级地址
            if ($('#box_addr_lv5').children(':selected').val() == 0) {
                console.log("box_addr_lv5 null");
                return false;
            }
            data.addr_lv5 = $('#box_addr_lv5').children(':selected').val();

            //地址详情
            data.address_detail = $('#box_addr_detail').val();

            //联系人
            data.consignee = $('#box_consignee').val();

            //姓
            data.first_name = $('#box_first_name').val();

            //名
            data.last_name = $('#box_last_name').val();

            //电话
            if (data.country == "840") {
                if ($('#box_phone_1').val().length != 3 || $('#box_phone_2').val().length != 3 || $('#box_phone_3').val().length != 4)
                {
                    layer.msg("<?php echo lang('check_addr_rule_phone');?>");
                    return ;
                }
                data.phone = $('#box_phone_1').val()+"-"+$('#box_phone_2').val()+"-"+$('#box_phone_3').val();
            } else if(data.country == '410'){
                if ($('#box_phone_kr_1').val().length > 3 || $('#box_phone_kr_1').val().length <=0 || $('#box_phone_kr_2').val().length != 4 || $('#box_phone_kr_3').val().length != 4)
                {
                    layer.msg("<?php echo lang('check_addr_rule_phone');?>");
                    return ;
                }
                data.phone = $('#box_phone_kr_1').val()+"-"+$('#box_phone_kr_2').val()+"-"+$('#box_phone_kr_3').val();
            } else {
                data.phone = $('#box_phone').val();
            }
            //备用电话
            data.reserve_num = $('#box_reserve_num').val();

            //邮编
            data.zip_code = $('#box_zip_code').val();

            //海关号
            data.customs_clearance = $('#box_customs_clearance').val();

            //数据验证
            front_verify_flag = false;
            if (front_verify_flag === true) {

            }
            var type = $('#box_type').val();
            url = "/ucenter/my_addresses/add_address";
            if (type == 1) {
                data.uid = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;
                data.is_default = 0;
            } else if (type == 2) {
                data.id = $('#box_id').val();
            } else {
                return false;
            }
            if (save_addr_flag == true) {
                save_addr_flag = false;
                var curEle = $("#checkout_save_addr");
                var oldSubVal = curEle.val();
                curEle.val($('#loadingTxt').val());
                curEle.attr("disabled", "disabled");
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    dataType: "json",
                    success: function(data) {
                        save_addr_flag = true;
                        curEle.removeAttr("disabled");
                        curEle.val(oldSubVal);
                        if (data.error === true) {
                            layer.msg(data.message)
                        } else {
                            layer.msg(data.message)
                            location.reload();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseText);
                        curEle.html(oldSubVal);
                        curEle.removeAttr("disabled");
                    }

                });
            }
        },
        click_addr_edit : function(id)
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

            //编辑地址时，如果是美国地址,则特殊处理
            if(country_id == 156){
                delete(linkage[country_id].leaf[81]);
                delete(linkage[country_id].leaf[82]);
                delete(linkage[country_id].leaf[71]);
            } else if(country_id == 344){
                country_id = 156;
                for ( var i= 11;i<=71;i++){
                    delete(linkage[country_id].leaf[i]);
                }
                delete(linkage[country_id].leaf[82]);
            }

            var location = "<?php echo $location;?>";
            if (location == '840' || location == '000') {
                $(".item dt").width("30%");
                $(".item dd").width("70%");
            }


            for (var country_codes in linkage) {
                if(in_array(country_codes,[country_id])) {
                    $('#box_country').html('<option value = "0"><?php echo lang('checkout_addr_country'); ?><option value = "'+country_codes+'">'+linkage[country_codes].name+'</option>');
                }
            }

            $('#BOX_nav').css('width','780');

            if(country_id == '156' || country_id == '344' || country_id == '000') {
                $('#div_consignee').css('display', 'block');//收货人显示
                $('#div_first_name').css('display', 'none');//姓隐藏
                $('#div_last_name').css('display', 'none');//名隐藏
                $('#div_phone_other').css('display', 'block');//手机号码显示
                $('#div_phone_us').css('display', 'none');//美国手机号码隐藏
                $('#div_phone_kr').css('display', 'none');//韩国手机号码隐藏
                $('#div_reserve_num').css('display', 'block');//备用号码显示
                $('#div_zip_code').css('display', 'block');//邮编显示
               // $('#div_customs_clearance').css('display', 'none');//海关报税隐藏

            } else if (country_id == '410') {
                $('#div_consignee').css('display', 'block');//收货人显示
                $('#div_first_name').css('display', 'none');//姓隐藏
                $('#div_last_name').css('display', 'none');//名隐藏
                $('#div_phone_other').css('display', 'none');//手机号码显示
                $('#div_phone_us').css('display', 'none');//美国手机号码隐藏
                $('#div_phone_kr').css('display', 'block');//韩国手机号码显示
                $('#div_reserve_num').css('display', 'none');//备用号码显示
                $('#div_zip_code').css('display', 'block');//邮编显示
                $('#div_zip_code').find('dt').find('span').text('*');
                //$('#div_customs_clearance').css('display', 'block');//海关报税隐藏
            } else {
                $('#div_consignee').css('display', 'none');//收货人显示
                $('#div_first_name').css('display', 'block');//姓隐藏
                $('#div_last_name').css('display', 'block');//名隐藏
                $('#div_phone_other').css('display', 'none');//手机号码隐藏
                $('#div_phone_us').css('display', 'block');//美国手机号码显示
                $('#div_phone_kr').css('display', 'none');//韩国手机号码隐藏
                $('#div_reserve_num').css('display', 'none');//备用号码显示
                $('#div_zip_code').css('display', 'block');//邮编显示
                $('#div_zip_code').find('dt').find('span').text('*');
                //$('#div_customs_clearance').css('display', 'none');//海关报税隐藏
            }

            //盒子名称
            if (country == '156') {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('china_land').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('china_land').')'; ?>");
            } else if(country == '344') {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('china_hk').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('china_hk').')'; ?>");
            } else if(country == '840') {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('label_us').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('label_us').')'; ?>");
            } else if(country == '410') {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('label_ko').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('label_ko').')'; ?>");
            } else {
                $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('other_region').')'; ?>");
                $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit').":(".lang('other_region').')'; ?>");
            }

            $('#box_type').val(2);
            $('#box_id').val(id);

            $('#box_consignee').val(data[id].consignee);
            if (country_code == '840') {
                $('#box_phone_1').val(data[id].phone_us[1]);
                $('#box_phone_2').val(data[id].phone_us[2]);
                $('#box_phone_3').val(data[id].phone_us[3]);
            }else if (country_code == '410') {
            $('#box_phone_kr_1').val(data[id].phone_kr[1]);
            $('#box_phone_kr_2').val(data[id].phone_kr[2]);
            $('#box_phone_kr_3').val(data[id].phone_kr[3]);
        } else {
                $('#box_phone').val(data[id].phone);
            }

            $('#box_reserve_num').val(data[id].reserve_num);
            $('#box_addr_detail').val(data[id].address_detail);
            $('#box_zip_code').val(data[id].zip_code);

            // 海关报关号
            $('#box_customs_clearance').val(data[id].customs_clearance);

            $('#box_country').nextAll().remove();

            $('#box_country').css('color', "#333").children('[value=' + country_code + ']').prop('selected', true);
            for (var j in linkage[country_code].leaf) {
                $('#box_addr').append(
                    $('<select/>').addClass("select").attr('id', "box_addr_lv2").on('change', address.cb_box_addr_lv2)
                );
                address.refresh_box_addr_by_lv("box_addr_lv2", address.lang_map[country_code].lv2, linkage[country_code].leaf);
                break;
            }

            var lv2_code = data[id].addr_lv2;
            $('#box_addr_lv2').css('color', "#333").children('[value=' + lv2_code + ']').prop('selected', true);
            for (var j in linkage[country_code].leaf[lv2_code].leaf) {
                $('#box_addr').append(
                    $('<select/>').addClass("select").attr('id', "box_addr_lv3").on('change', address.cb_box_addr_lv3)
                );
                address.refresh_box_addr_by_lv("box_addr_lv3", address.lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
                break;
            }

            //显示City的值
            if(country_code == 840){
                $('#box_addr').append(
                    $('<input type="text" id="box_city" maxlength="32"/>').addClass("input").attr('placeholder', '<?php echo lang('city'); ?>')
                );
                $('#box_city').val(data[id].city);
                $('#box_first_name').val(data[id].first_name);
                $('#box_last_name').val(data[id].last_name);
            }

            if (country_code == '840' || country_code == '410') {
                $("#box_country").hide();
            }
            if ($('#box_addr_lv3').length == 0) {
                return true;
            }

            var lv3_code = data[id].addr_lv3;
            $('#box_addr_lv3').css('color', "#333").children('[value=' + lv3_code + ']').prop('selected', true);

            for (var j in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
                $('#box_addr').append(
                    $('<select/>').addClass("select").attr('id', "box_addr_lv4")
                );
                address.refresh_box_addr_by_lv("box_addr_lv4", address.lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
                break;
            }
            if ($('#box_addr_lv4').length == 0) {
                return true;
            }

            var lv4_code = data[id].addr_lv4;
            $('#box_addr_lv4').css('color', "#333").children('[value=' + lv4_code + ']').prop('selected', true);

        },



    };



</script>
