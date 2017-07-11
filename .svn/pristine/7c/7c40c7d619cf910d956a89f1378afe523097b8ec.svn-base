<script src="<?php echo base_url('file/js/user_address_linkage.js?v=170418'); ?>"></script>
<script language="JavaScript">
    var country_id = 156;
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
        'address_detail': "<?php echo lang('checkout_validator_address_detail'); ?>"
    };

    //按级别刷新选择框
    var refresh_box_addr_by_lv = function(id, lang, data, isselected)
    {
        var obj = $('#' + id);
        obj.children().remove();
        obj.append(
            $('<option/>').val(0).text(lang)
        );
        for (var code in data) {
			if (isselected != undefined && isselected && !isNaN(isselected) && code == isselected) {
				obj.append(
					$('<option/>').val(code).attr('selected', true).text(data[code].name)
				);
			} else {
				obj.append(
					$('<option/>').val(code).text(data[code].name)
				);
			}
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

        if (country_code === '0') {
            box_country.css('color', "#999");
            return true;
        }
        box_country.css('color', "#333");

        // 若 leaf 不为空，则可以走到 for 里面生成下一级
        for (var i in linkage[country_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").attr('name', 'provice').on('change', cb_box_addr_lv2)
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
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv3").attr('name', 'city').on('change', cb_box_addr_lv3)
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
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv4").attr('name', 'area').on('change', cb_box_addr_lv4)
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

    var replace_space = function(s)
    {
        for(var i=0;i<11;i++)
        {
            s = s.replace(" ","");
        }
        return s;
    }

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
    }

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
	
	function in_array(search, array) {
		for(var i in array) {
			if (array[i] == search) {
				return true;
			}
		}
		return false;
	}
	
	// 获取地址列表
	var address_list = function(country_id, provice_id, city_id, area_id, disabled) {
		var box_country = $('#box_country');

		if(country_id == 156){
            delete(linkage[country_id].leaf[81]);
            delete(linkage[country_id].leaf[82]);
            delete(linkage[country_id].leaf[71]);
        }
		
		// box append 国家列表
        box_country.css('color', "#333");
        for (var country_code in linkage) {
            if(in_array(country_code,[country_id])) {
                box_country.append(
                    $('<option/>').val(country_code).attr('selected', true).text(linkage[country_code].name)
                );
            }
        }
		
		// 获取省份列表
		if (country_id != undefined && !isNaN(country_id) && provice_id != undefined && !isNaN(provice_id) && provice_id > 0) {
			// 若 leaf 不为空，则可以走到 for 里面生成下一级
			for (var i in linkage[country_id].leaf) {
				$('#box_addr').append(
					$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").attr('name', 'provice').on('change', cb_box_addr_lv2)
				);
				refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_id].lv2, linkage[country_id].leaf, provice_id);
				break;
			}
		}
		
		// 获取城市列表
		if (provice_id != undefined && !isNaN(provice_id) && city_id != undefined && !isNaN(city_id) && city_id > 0) {
			// 若 leaf 不为空，则可以走到 for 里面生成下一级
			$('#box_addr_lv2').css('color', "#333");
			for (var i in linkage[country_id].leaf[provice_id].leaf) {
				$('#box_addr').append(
					$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv3").attr('name', 'city').on('change', cb_box_addr_lv3)
				);
				refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_id].lv3, linkage[country_id].leaf[provice_id].leaf, city_id);
				break;
			}
		}
		
		// 获取地区列表
		if (area_id != undefined && !isNaN(area_id) && city_id != undefined && !isNaN(city_id) && area_id > 0) {
			$('#box_addr_lv3').css('color', "#333");
			for (var i in linkage[country_id].leaf[provice_id].leaf[city_id].leaf) {
				$('#box_addr').append(
					$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv4").attr('name', 'area').on('change', cb_box_addr_lv4)
				);
				refresh_box_addr_by_lv("box_addr_lv4", lang_map[country_id].lv4, linkage[country_id].leaf[provice_id].leaf[city_id].leaf, area_id);
				break;
			}
			$('#box_addr_lv4').css('color', "#333");
		}
		if (disabled != undefined && disabled == 1) $('select').attr('disabled', true);
	}
	
	
	// 顺丰运费国家初始化
	var sfcountry = function() {
		var box_country = $('#box_country');
		// box append 国家列表
        box_country.css('color', "#333");
        for (var country_code in linkage) {
            if(in_array(country_code,[country_id])) {
                box_country.append(
                    $('<option/>').val(country_code).attr('selected', true).text(linkage[country_code].name)
                );
            }
        }
		
		// 初始化省份 北京市
		if (country_id != undefined && !isNaN(country_id)) {
			// 若 leaf 不为空，则可以走到 for 里面生成下一级
			for (var i in linkage[country_id].leaf) {
				$('#box_addr').append(
					$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").attr('name', 'provice').on('change', cb_box_addr_lv2)
				);
				refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_id].lv2, linkage[country_id].leaf, 11);
				break;
			}
		}
	}
	
</script>