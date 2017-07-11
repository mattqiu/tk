<link rel="stylesheet" href="<?php echo base_url('themes/admin/stylesheets/multiple-select.css?v=1')?>">
<script src="<?php echo base_url('themes/admin/javascripts/multiple-select.js?v=1'); ?>"></script>
<div class="well">
	<?php if (isset($err_msg)): ?>
		<div class="well">
			<p style="color: red;"><?php echo $err_msg; ?></p>
		</div>
	<?php endif; ?>
	<form class="" method="post" action="<?php echo base_url('admin/export_orders/report_to_usa') ?>" onsubmit="return clearData();">
		<select class="input-medium" name="ext">
			<option value='2007'>EXCEL 2007</option>
			<option value='2003'>EXCEL 2003</option>
		</select>
		<select class="input-medium" name="status" id="status">
			<?php
			foreach ($status_map as $k => $v)
			{
				if($adminInfo['role'] == 6 && !in_array($k,array('1','3'))){ continue ;}
				echo "<option value=\"{$k}\">{$v['text']}</option>";
			}
			?>
			<?php if($adminInfo['role'] != 6){?>
			<option value="66"><?php echo lang('order_status_4') ?></option>
			<?php }?>
		</select>
		<select class="input-medium" name="area" class="com_type">
			<?php
			foreach ($area_map as $k => $v)
			{
				if($adminInfo['role'] == 6 && $k!= 410){ continue ;}
				if($adminInfo['role'] == 7 && $k!= 344){ continue ;}
				echo "<option value=\"{$k}\">{$v}</option>";
			}
			?>
		</select>
            <!-- 运营方 -->
                <select class="input-medium" id="supplier">
			<?php foreach ($supplier_arr as $k => $v){ ?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                        <?php } ?>
		</select>

		<?php if($adminInfo['id'] == 1){?>
		<select name="cate_id" class="input-medium">
			<option value=""><?php echo lang('label_sel_cate');?></option>
			<?php foreach($category_all as $val) { ?>
				<?php
				if($val['level'] == 2) {?>
					<option value="<?php echo $val['cate_sn']; ?>"><?php echo $val['html'],'|-',$val['cate_name']; ?></option>
				<?php
				}
				?>
			<?php  } ?>
		</select>
		<?php }?>
		<select class="input-medium" name="select_is_export_lock" class="com_type">
			<option value=""><?php echo lang('all')?></option>
			<option value="0"><?php echo lang('admin_no_lock')?></option>
			<option value="1"><?php echo lang('admin_yes_lock')?></option>
		</select>

        <select class="input-medium" name="order_type" class="com_type">
            <option value=""><?php echo lang('all_group')?></option>
            <option value="1"><?php echo lang('choose_group')?></option>
            <option value="2"><?php echo lang('admin_as_upgrade_order')?></option>
            <option value="3"><?php echo lang('generation_group')?></option>
            <option value="4"><?php echo lang('retail_group')?></option>
            <option value="5"><?php echo lang('exchange_order')?></option>
        </select>

		<select name="store_code" class="input-medium" id="store_code" multiple="multiple">
			<?php
			foreach ($shipper_all as $v)
			{
				echo "<option ";
				echo "value=\"{$v['supplier_id']}\">"."{$v['supplier_name']}"."</option>";
			}
			?>
		</select>
		<input type="hidden" id="store_code_arr" name="store_code_arr">
		<!-- <?php if($adminInfo['id'] == 1){?>
		<label><input autocomplete="off"  name="brand[]" type="checkbox" value="nopal_suit">Nopal套装</label>
		<?php }?> -->
		<!--<label><input autocomplete="off"  name="brand[]" type="checkbox" value="ciliao">磁疗养生产品</label>
		<label><input autocomplete="off"  name="brand[]" type="checkbox" value="paopao">洁面泡泡</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="xinxilan">新西蘭（惠氏，可瑞康，爱他美）</label>
		-->
		<br>
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="jbb">JBB</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="nopal">Nopal</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="water">--><?php //echo lang('admin_mini_water') ?><!--</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="family_water">--><?php //echo lang('admin_family_water') ?><!--</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="jianfei">--><?php //echo lang('admin_powder') ?><!--</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="jiaonang">--><?php //echo lang('admin_flx') ?><!--</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Insight_Eye">Insight Eye</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Ginseng">Ginseng</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Dr_Cell">Dr. Cell</label>-->
<!--		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Seng_Seng_Dan">Seng Seng Dan</label>-->
		<br>
		<!--
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="99">Ninety nine(99 美白) </label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="rihua">韩国日化</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="baojian">韩国保健品</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="xiangzao">韩国香皂</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Silverloy">Silverloy(银微子)</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="gongzai">公仔（小背包，空调被）</label>
		<br>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Filma">Filma(椰子油)</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="Primaco">Primaco(椰子油)</label>
		<br>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="jiu1">国威名红酒</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="jiu2">航远红酒（大陆）</label>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="jiu3">航远红酒（香港）</label>
		<br>
		<label style="display: inline"><input autocomplete="off"  name="brand[]" type="checkbox" value="tea">茶叶</label>
		--><br>
                <b><?php echo lang('order_pay_date')?>:</b>
                <input class="Wdate span2" id="start_date" type="text" name="start_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('start_date'); ?>">
                <input class="Wdate span2" id="end_date" type="text" name="end_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('end_date'); ?>"><br>
		<b><?php echo lang('deliver_time')?>:</b>
                <input class="Wdate span2" id="start_deliver_date" type="text" name="start_deliver_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('deliver_time')?>">
		<input class="Wdate span2" id="end_deliver_date" type="text" name="end_deliver_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('deliver_time')?>"><br>
                <b class="update_time"><?php echo lang('order_update_date')?>:</b>
                <input class="Wdate span2" id="start_update_date" type="text" name="start_update_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('order_update_date')?>">
		<input class="Wdate span2" id="end_update_date" type="text" name="end_update_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('order_update_date')?>">
		<br>
		<label><input autocomplete="off"  name="is_export_lock" type="checkbox" value="1"><?php echo lang('admin_select_is_lock')?></label>
		<button class="btn submit_btn" type="submit"><i class="icon-download-alt"></i> <?php echo lang('export') ?></button>
	</form>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
</div>

<script>
         $("#supplier").change(function(){
            var supplier_id = $(this).children('option:selected').val();
            var html ="";
            $.ajax({
                type: "POST",
                url: "/admin/export_orders/ajax_shipper",
                data: {supplier_id: supplier_id},
                dataType: "json",
                success: function (res) {
                    $(res).each(function(k,v){
                        html +='<option value="'+v.supplier_id+'">'+v.supplier_name+'</option>';
                    });
                    $("#store_code").html(html);
                    query_select();
                }
            });
        })
        query_select();
        function query_select(){
            $("#store_code").multipleSelect({
                    filter: true, 
                    multiple: true,
                    placeholder: "<?php echo lang('admin_oper_shipper_ALL')?>",
                    width: 750,
                    multipleWidth: 350,
                    selectAll: false
            });
            $('[data-name="selectItemstore_code"]').click(function(){
                    var store_value = $("#store_code").multipleSelect("getSelects");
                    $('#store_code_arr').val(store_value);
            });
        }
        status_op($("#status").val());
        $("#status").change(function(){
                status_op($(this).val());
        })
        function status_op(status){
              if(status==1){
                  $("#start_update_date,#end_update_date,.update_time").show();
              }else{
                  $("#start_update_date,#end_update_date,.update_time").hide();
              }
             // alert(status);
        }
	

	function clearData(){
		$('.submit_btn').attr('disabled',true);
		setTimeout(cleanStoreCodeArr,500);
		setTimeout(cleanSubmitBtn,5000);
		$("#store_code").multipleSelect('uncheckAll');
		return true;
	}

	function cleanStoreCodeArr(){
		$('#store_code_arr').val('');
	}
	function cleanSubmitBtn(){
		$('.submit_btn').attr('disabled',false);
	}
</script>
