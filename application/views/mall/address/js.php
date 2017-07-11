<script src="<?php echo base_url('file/js/user_address_linkage.js?v=170418'); ?>"></script>
<script language="JavaScript">
    var country_id = "<?php echo $curLocation_id?>";
    <?php
        if(isset($data))
        {
            echo "var data = ". json_encode($data).";";
        }elseif(isset($address_list))
        {
            echo "var data = ". json_encode($address_list).";";
        }elseif(isset($address_edit))
        {
            echo "var data = ". json_encode($address_edit).";";
        }else{
            echo "var data = {};";
        }
    ?>
    console.log(data);
    //提示语言包
    var lang_map =
    {
        "156": {
        "lv2": "<?php echo lang('checkout_addr_lv2_cn'); ?>",
            "lv3": "<?php echo lang('checkout_addr_lv3_cn'); ?>",
            "lv4": "<?php echo lang('checkout_addr_lv4_cn'); ?>"
        },
        "410": {
        "lv2": "<?php echo lang('checkout_addr_lv2_kr'); ?>",
            "lv3": "",
            "lv4": ""
        },
        "840": {
        "lv2": "<?php echo lang('checkout_addr_lv2_us'); ?>",
            "lv3": "<?php echo lang('checkout_addr_lv3_us'); ?>",
            "lv4": "<?php echo lang('checkout_addr_lv4_us'); ?>"
        }
    };
    //错误提示语言包
    var err_lang_map =
    {
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
        'phone': "<?php echo lang('check_addr_rule_phone'); ?>",
        'reserve_num': "<?php echo lang('check_addr_rule_reserve_num'); ?>",
        'zip_code': "<?php echo lang('check_addr_rule_zip_code'); ?>",
        'customs_clearance': "<?php echo lang('checkout_validator_customs_clearance'); ?>"
    };

    //按钮可提交切换
    var button_enable = function(b)
    {
        if(b)
        {
            $('.layui-layer-btn0').html("<?php echo lang('checkout_deliver_add'); ?>");
            $('.layui-layer-btn0').css("background", "#d22215");
        }else{
            $('.layui-layer-btn0').html($('#loadingTxt').val());
            $('.layui-layer-btn0').css("background", "#858C8F");
        }
    }

    //按级别刷新选择框
    var refresh_box_addr_by_lv = function(id, lang, data)
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
//        obj.customComboBox(customerComboBox_Config);
        return true;
    }

    //国家级地址选择的响应
    var cb_box_country = function()
    {
        var box_country = $('#box_country');
        var country_code = box_country.val();

        box_country.nextAll().remove();

        // 韩国特殊处理
        if (country_code === '410') {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
            $('#div_customs_clearance').css('display', 'none');
            $('#div_zip_code').find('dt').find('span').text('*');
        } else {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            $('#div_customs_clearance').css('display', 'none');
        }

        //美国地址特殊处理
        if(country_code === '840'){
            $('#div_first_name').css('display', 'block');
            $('#div_last_name').css('display', 'block');
            $('#div_consignee').css('display', 'none');
            $('#BOX_nav').css('width','700px');
            $('#BOX_nav').css('height','520px');
            $('#div_zip_code').find('dt').find('span').text('*');
        }else{
            $('#box_city').css('display', 'none');
            $('#div_consignee').css('display', 'block');
            $('#div_first_name').css('display', 'none');
            $('#div_last_name').css('display', 'none');
            $('#BOX_nav').css('width','600px');
            $('#BOX_nav').css('height','520px');
            if (country_code !== '410') {
                $('#div_zip_code').find('dt').find('span').text('');
            }
        }
        if (country_code === '0') {
            box_country.css('color', "#999");
            return true;
        }
        box_country.css('color', "#333");

        // 若 leaf 不为空，则可以走到 for 里面生成下一级
        for (var i in linkage[country_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").on('change', cb_box_addr_lv2)
            );
            refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
            break;
        }
        return true;
    }

    //2级地址选择的响应
    var cb_box_addr_lv2 = function ()
    {
        var country_code = $('#box_country').val();
        var lv2_code = $('#box_addr_lv2').val();

        $('#box_addr_lv2').nextAll().remove();

        //生成City Text
        if(country_code == 840){
            $('#box_addr').append(
                $('<input type="text" id="box_city" maxlength="32"/>').addClass("input").attr('placeholder', '<?php echo lang('city'); ?>')
            );
        }

        if (lv2_code == 0) {
            $('#box_addr_lv2').css('color', "#999");
            return true;
        }
        $('#box_addr_lv2').css('color', "#333");

        for (var i in linkage[country_code].leaf[lv2_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv3").on('change', cb_box_addr_lv3)
            );
            refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
            break;
        }
        return true;
    }

    //3级地址选择的响应
    var cb_box_addr_lv3 = function()
    {
        var country_code = $('#box_country').val();
        var lv2_code = $('#box_addr_lv2').val();
        var lv3_code = $('#box_addr_lv3').val();

        $('#box_addr_lv3').nextAll().remove();

        if (lv3_code == 0) {
            $('#box_addr_lv3').css('color', "#999");
            return true;
        }
        $('#box_addr_lv3').css('color', "#333");

        if(lv3_code == 8104)
        {
            hk_address_8104(true);
        }else{
            hk_address_8104(false);
        }

        for (var i in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv4").on('change', cb_box_addr_lv4)
            );
            refresh_box_addr_by_lv("box_addr_lv4", lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
            break;
        }
        return true;
    }

    //4级地址选择响应
    var cb_box_addr_lv4 = function()
    {
        var lv4_code = $('#box_addr_lv4').val();

        if (lv4_code == 0) {
            $('#box_addr_lv4').css('color', "#999");
            return true;
        }
        $('#box_addr_lv4').css('color', "#333");
        return true;
    }

    //添加地址按钮响应
    var click_addr_add = function()
    {
        $('#box_title').text("<?php echo lang('checkout_addr_box_title_add'); ?>");
        $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add'); ?>");
        $('#box_type').val(1);

        // 清数据
        $('#box_consignee').val("");
        $('#box_phone').val("");
        $('#box_phone_1').val("");
        $('#box_phone_2').val("");
        $('#box_phone_3').val("");
        $('#box_phone_kr_1').val("");
        $('#box_phone_kr_2').val("");
        $('#box_phone_kr_3').val("");
        $('#box_reserve_num').val("");
        $('#box_first_name').val("");
        $('#box_last_name').val("");
        $('#box_zip_code').val("");
        $('#div_zip_code').find('dt').find('span').text('');

        $('#box_addr_detail').val("");
        $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
        $('#box_country').nextAll().remove();
        $('#box_country').css('color', "#999").children('[value=0]').prop('selected', true);
        $('#div_customs_clearance').css('display', 'none');

        button_enable(true);
        box_country_init();
    }

    var replace_space = function(s)
    {
        for(var i=0;i<11;i++)
        {
            s = s.replace(" ","");
        }
        return s;
    }

    //点击编辑地址
    var click_addr_edit = function (id)
    {
        button_enable(true);

        var country_code = data[id].country;
        //编辑地址时，如果是美国地址,则特殊处理
        if(country_code == 840){
            $('#box_city').css('display', 'block');
            $('#div_first_name').css('display', 'block');
            $('#div_last_name').css('display', 'block');
            $('#div_consignee').css('display', 'none');
            $('#div_zip_code').find('dt').find('span').text('*');
            $('#BOX_nav').css('width','700px');

            //填充手机号
            var phone_tmp = data[id].phone;
            phone_tmp = replace_space(phone_tmp);
            var phone_tmp_arr = phone_tmp.split('-');
            if(3 == phone_tmp_arr.length){
                $('#box_phone_1').val(phone_tmp_arr[0]);
                $('#box_phone_2').val(phone_tmp_arr[1]);
                $('#box_phone_3').val(phone_tmp_arr[2]);
            }else{
                phone_tmp = phone_tmp.replace("-","").replace("-","").replace("-","");
                $('#box_phone_1').val(phone_tmp.substr(0,3));
                $('#box_phone_2').val(phone_tmp.substr(3,3));
                $('#box_phone_3').val(phone_tmp.substr(6,4));
            }
        }else{
            if(country_code == '410')
            {
                //填充手机号
                var phone_tmp = data[id].phone;
                phone_tmp = replace_space(phone_tmp);
                var phone_tmp_arr = phone_tmp.split('-');
                if(3 == phone_tmp_arr.length){
                    $('#box_phone_kr_1').val(phone_tmp_arr[0]);
                    $('#box_phone_kr_2').val(phone_tmp_arr[1]);
                    $('#box_phone_kr_3').val(phone_tmp_arr[2]);
                }else{
                    phone_tmp = phone_tmp.replace("-","").replace("-","").replace("-","");
                    $('#box_phone_kr_1').val(phone_tmp.substr(0,3));
                    $('#box_phone_kr_2').val(phone_tmp.substr(3,4));
                    $('#box_phone_kr_3').val(phone_tmp.substr(7,4));
                }
            }
            $('#box_city').css('display', 'none');
            $('#div_first_name').css('display', 'none');
            $('#div_last_name').css('display', 'none');
            $('#div_consignee').css('display', 'block');
            $('#div_zip_code').find('dt').find('span').text('');
            $('#BOX_nav').css('width','600px');

            $('#box_phone').val(data[id].phone);
        }

        if (country_code === '410') {
            $('#div_zip_code').find('dt').find('span').text('*');
        }

        $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
        $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
        $('#box_type').val(2);
        $('#box_id').val(id);

        $('#box_consignee').val(data[id].consignee);
        $('#box_reserve_num').val(data[id].reserve_num);
        $('#box_addr_detail').val(data[id].address_detail);
        $('#box_zip_code').val(data[id].zip_code);

        // 韩国地址特殊处理
        if (country_code == '410') {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
            $('#div_customs_clearance').css('display', 'none');
        } else {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            $('#div_customs_clearance').css('display', 'none');
        }

        // 海关报关号
        $('#box_customs_clearance').val(data[id].customs_clearance);

        $('#box_country').nextAll().remove();

        $('#box_country').css('color', "#333").children('[value=' + country_code + ']').prop('selected', true);

        for (var j in linkage[country_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").attr('id', "box_addr_lv2").on('change', cb_box_addr_lv2)
            );
            refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
            break;
        }

        var lv2_code = data[id].addr_lv2;
        $('#box_addr_lv2').css('color', "#333").children('[value=' + lv2_code + ']').prop('selected', true);

        for (var j in linkage[country_code].leaf[lv2_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").attr('id', "box_addr_lv3").on('change', cb_box_addr_lv3)
            );
            refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
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

        if ($('#box_addr_lv3').length == 0) {
            return true;
        }

        var lv3_code = data[id].addr_lv3;
        $('#box_addr_lv3').css('color', "#333").children('[value=' + lv3_code + ']').prop('selected', true);

        if(lv3_code == 8104)
        {
            hk_address_8104(true);
        }else{
            hk_address_8104(false);
        }

        for (var j in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").attr('id', "box_addr_lv4")
            );
            refresh_box_addr_by_lv("box_addr_lv4", lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
            break;
        }
        if ($('#box_addr_lv4').length == 0) {
            return true;
        }

        var lv4_code = data[id].addr_lv4;
        $('#box_addr_lv4').css('color', "#333").children('[value=' + lv4_code + ']').prop('selected', true);

    }
    //保存地址
    var submit_box_save_addr = function ()
    {
        var data = {};
        //国家ID
        var country = $('#box_country').children(':selected').val();
        if (country === '0') {
            console.dir(err_lang_map.country);
            layer.msg(err_lang_map.country);
            return false;
        }
        data.country = country;
        //二级地址
        if ($('#box_addr_lv2').children(':selected').val() == 0) {
            console.dir(err_lang_map.addr_lv2[country]);
            layer.msg(err_lang_map.addr_lv2[country]);
            return false;
        }
        data.addr_lv2 = $('#box_addr_lv2').children(':selected').val();
        //三级地址
        if ($('#box_addr_lv3').children(':selected').val() == 0) {
            console.dir(err_lang_map.addr_lv3[country]);
            layer.msg(err_lang_map.addr_lv3[country]);
            return false;
        }
        data.addr_lv3 = $('#box_addr_lv3').children(':selected').val();
        data.city = $('#box_city').val();
        //data.city = $('#box_addr_lv3').children(':selected').val();
        //四级地址
        if ($('#box_addr_lv4').children(':selected').val() == 0) {
            console.dir(err_lang_map.addr_lv4[country]);
            layer.msg(err_lang_map.addr_lv4[country]);
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
        if(country_id == 840)
        {
            var phone_1 = $('#box_phone_1').val();
            var phone_2 = $('#box_phone_2').val();
            var phone_3 = $('#box_phone_3').val();
            if(phone_1.length < 2 || phone_2.length < 3 || phone_3.length < 4)
            {
                layer.msg(err_lang_map.phone);
                return false;
            }
            data.phone = phone_1+"-"+phone_2+"-"+phone_3;
        }else if(country_id == '410'){
            var phone_1 = $('#box_phone_kr_1').val();
            var phone_2 = $('#box_phone_kr_2').val();
            var phone_3 = $('#box_phone_kr_3').val();
            if(phone_1.length < 2 || phone_2.length < 3 || phone_3.length < 4)
            {
                layer.msg(err_lang_map.phone);
                return false;
            }
            data.phone = phone_1+"-"+phone_2+"-"+phone_3;
        }else{
            data.phone = $('#box_phone').val();
        }
        //备用电话
        data.reserve_num = $('#box_reserve_num').val();
        //邮编
        data.zip_code = $('#box_zip_code').val();
        //海关号
        data.customs_clearance = $('#box_customs_clearance').val();

        var url = "";
        var data_id = $('#box_id').val();
        var type = $('#box_type').val();
        if(undefined == type || "" == type)
        {
            type = 1;
        }
        if (type == 1) {
            data.uid = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;
            data.is_default = 0;
            url = "/order/do_save_user_addr";
        } else if (undefined != data_id && "" != data_id) {
            data.id = data_id;
            url = "/order/do_edit_user_addr";
        } else {
            return false;
        }
        //防重复提交
        if ($('.layui-layer-btn0').html() == $('#loadingTxt').val()) {
            console.log("防重复提交");
            return false;
        }

        button_enable(false);

        var reset_submit_btn = function()
        {
            setTimeout(function(){
                button_enable(true)
            },3000);
        }

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            success: function(data) {
                if (data.code == 0)
                {
                    window.location.reload();
                }
                else if(data.code == 204)
                {
                    layer.msg(data.msg);
                    reset_submit_btn();
                }
                else if(data.code == 105){
                    layer.msg(data.msg);
                    reset_submit_btn();
                }
                else
                {
                    if(data.msg.indexOf('country') != -1){
                        layer.msg(err_lang_map.country);
                    }else if(data.msg.indexOf('addr_lv2') != -1){
                        layer.msg(err_lang_map.addr_lv2);
                    }else if(data.msg.indexOf('address_detail') != -1){
                        layer.msg(err_lang_map.address_detail+"!!!");
                    }else if(data.msg.indexOf('consignee') != -1){
                        layer.msg(err_lang_map.consignee);
                    } else if(data.msg.indexOf('phone') != -1){
                        layer.msg(err_lang_map.phone);
                    }else if(data.msg.indexOf('reserve_num') != -1){
                        layer.msg(err_lang_map.reserve_num);
                    }else if(data.msg.indexOf('zip_code') != -1){
                        layer.msg(err_lang_map.zip_code);
                    }else {
                        layer.msg(data.msg);
                    }
                    reset_submit_btn();
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    }

    //box country的自动选择并隐藏
    var box_country_init = function()
    {
        var box_country = $('#box_country');
        box_country.val(country_id);
        //美国和韩国不显示国家选择
        if(country_id == '840' ||country_id == '410')
        {
            box_country.hide();
        }
        cb_box_country();
    }

    var phone_us_focus = function(o,n,l)
    {
        $(o).keyup(function(){
            var tmp = $(this).val();
            for(var i=0;i<10;i++){
                tmp = tmp.replace(' ','');
            }
            if($(this).val() != tmp)
            {
                $(this).val(tmp);
                console.dir(tmp);
            }
            if($(this).val().length >= l)
            {
                $(n).focus();
            }
        });
    };

    var phone_us_init = function()
    {
        phone_us_focus('#box_phone_1','#box_phone_2',3);
        phone_us_focus('#box_phone_2','#box_phone_3',3);
        phone_us_focus('#box_phone_3','#box_phone_1',4);
    };

    phone_us_init();

    var phone_kr_init = function()
    {
        phone_us_focus('#box_phone_kr_1','#box_phone_kr_2',3);
        phone_us_focus('#box_phone_kr_2','#box_phone_kr_3',4);
        phone_us_focus('#box_phone_kr_3','#box_phone_kr_1',4);
    };

    phone_kr_init();

    //地址选择框的初始化
    var address_init = function()
    {
        var box_country = $('#box_country');
        // 其他地区为000
        linkage['000'] = {name: '<?php echo lang('con_others'); ?>', leaf: []};
        if(country_id == 156){
            delete(linkage[country_id].leaf[81]);
            delete(linkage[country_id].leaf[82]);
            delete(linkage[country_id].leaf[71]);
        }
        else if(country_id == 344){
            country_id = 156;
            for ( var i= 11;i<=71;i++){
                delete(linkage[country_id].leaf[i]);
            }
            delete(linkage[country_id].leaf[82]);
        }

        //如果是其他国家
        if(country_id == "000")
        {
//            box_country.nextAll().remove();
            var other_country = {"392":{"name":"Japan"},"124":{"name":"Canada"},"158":{"name":"Taiwan"}};
            for (var code in other_country) {
                box_country.append(
                    $('<option/>').val(code).text(other_country[code].name)
                );
            }
            return true;
        }

        // box append 国家列表
        box_country.css('color', "#999");
        for (var country_code in linkage) {
            // if(in_array(country_code,[156,840,410,'000',344,446,158,'001'])) {
            if(in_array(country_code,[country_id])) {
                box_country.append(
                    $('<option/>').val(country_code).text(linkage[country_code].name)
                );
            }
        }

        box_country_init();

        //美国，韩国去掉备用号,另外邮编的必填
        if(country_id == '840' ||country_id == '410')
        {
            $("#div_reserve_num").hide();
        }

        //美国的电话号码使用334格式
        if(country_id == '840')
        {
            $("#div_phone").hide();
            $("#div_phone_us").show();
        }
        else if(country_id == '410')
        {
            $("#div_phone").hide();
            $("#div_phone_kr").show();
        }else{
            $("#div_phone").show();
            $("#div_phone_us").hide();
            $("#div_phone_kr").hide();
        }

    }

    var checkout_order_deliver_fee = function(b)
    {
        var cls = ".checkout_order_deliver_fee";
        var checkout_order_deliver_fee = "<?php echo lang('checkout_order_deliver_fee'); ?>";
        var checkout_order_deliver_fee_tps = "<?php echo lang('checkout_order_deliver_fee_tps'); ?>";
        $(cls).each(function(){
            if(b){
                console.log("4:"+checkout_order_deliver_fee_tps);
                $(this).html(checkout_order_deliver_fee_tps);
            }else{
                console.log("3:"+checkout_order_deliver_fee);
                $(this).html(checkout_order_deliver_fee);
            }
        });

        var product_info_address = $('.product_info_address');
        var tmp_html = product_info_address.html();
        var tmp_1 = checkout_order_deliver_fee.replace("：","");
        var tmp_2 = checkout_order_deliver_fee_tps.replace("：","")
        if(b){
            tmp_html = tmp_html.replace(tmp_1,tmp_2);
            console.log("1:"+tmp_1);
        }else{
            if(tmp_2 != ""){
                tmp_html = tmp_html.replace(tmp_2,tmp_1);
                console.log("2:"+tmp_2);
                console.log("2:"+tmp_1);
            }
        }
        console.log("5:"+tmp_html);
        product_info_address.html(tmp_html);

    };

    var hk_address_8104_old = "";
    var hk_address_8104 = function(b)
    {
        var addr_detail = $('#box_addr_detail');
        if(b)
        {
            var tmp = addr_detail.val();
            var addr_8104 = "4/F, Kowloon Center, No.29-39 Ashley Road, Tsim Sha Tsui, Hong Kong 尖沙咀亞士厘道29-39號九龍中心 4樓";
            if(tmp != "")
            {
                if(tmp != hk_address_8104_old){
                    hk_address_8104_old = tmp;
                }
            }
            addr_detail.val(addr_8104);
            addr_detail.attr("readonly","readonly");
            addr_detail.attr("disable","true");
        }else{
            addr_detail.removeAttr("readonly");
            if(hk_address_8104_old != "")
            {
                //addr_detail.val(hk_address_8104_old);
            }
            addr_detail.attr("disable","false");
        }
    }
</script>